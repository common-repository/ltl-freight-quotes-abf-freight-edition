<?php

/**
 * ABF Admin Settings
 * @package     Woocommerce ABF Edition
 * @author      <https://eniture.com/>
 * @copyright   Copyright (c) 2017, Eniture
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add ABF Method In WOO Method List
 * @param $methods
 * @return string
 */
function add_abf_freight($methods)
{
    $methods['abf'] = 'ABF_Freight_Shipping';
    return $methods;
}

/**
 * Add Woo Setting Tab For ABF
 * @param $settings
 * @return string
 */
function abf_shipping_sections($settings)
{
    include('abf-tab-class.php');
    return $settings;
}

/**
 * Hide Other Shipping Options
 * @param $available_methods
 * @return array
 */
function abf_hide_shipping($available_methods)
{
    // Remove backup Rates in case other rates are available
    if (get_option('wc_pervent_proceed_checkout_eniture') == 'backup_rates' && count($available_methods) > 1) {
        $backup_rate_id = 'abf:backup_rates';
        foreach ($available_methods as $key => $value) {
            if (isset($value->id) && $value->id == $backup_rate_id) {
                unset($available_methods[$key]);
            }
        }
    }

    if (get_option('wc_settings_abf_allow_other_plugins') == 'no'
        && count($available_methods) > 0) {
        $plugins_array = array();
        $eniture_plugins = get_option('EN_Plugins');
        if ($eniture_plugins) {
            $plugins_array = json_decode($eniture_plugins, true);
        }

        // flag to check if rates available of current plugin
        $rates_available = false;
        foreach ($available_methods as $value) {
            if ($value->method_id == 'abf') {
                $rates_available = true;
                break;
            }
        }

        // add methods which not exist in array
        $plugins_array[] = 'ltl_shipping_method';
        $plugins_array[] = 'daylight';
        $plugins_array[] = 'tql';
        $plugins_array[] = 'unishepper_small';
        $plugins_array[] = 'usps';

        if ($rates_available) {
            foreach ($available_methods as $index => $method) {
                if (!in_array($method->method_id, $plugins_array)) {
                    unset($available_methods[$index]);
                }
            }
        }
    }
    return $available_methods;
}

/**
 * Filter For CSV Import
 */
if (!function_exists('en_import_dropship_location_csv')) {

    /**
     * Import drop ship location CSV
     * @param $data
     * @param $this
     * @return array
     */
    function en_import_dropship_location_csv($data, $parseData)
    {
        $_product_freight_class = $_product_freight_class_variation = '';
        $_dropship_location = $locations = [];
        foreach ($data['meta_data'] as $key => $metaData) {
            $location = explode(',', trim($metaData['value']));
            switch ($metaData['key']) {
                // Update new columns
                case '_product_freight_class':
                    $_product_freight_class = trim($metaData['value']);
                    unset($data['meta_data'][$key]);
                    break;
                case '_product_freight_class_variation':
                    $_product_freight_class_variation = trim($metaData['value']);
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location_nickname':
                    $locations[0] = $location;
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location_zip_code':
                    $locations[1] = $location;
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location_city':
                    $locations[2] = $location;
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location_state':
                    $locations[3] = $location;
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location_country':
                    $locations[4] = $location;
                    unset($data['meta_data'][$key]);
                    break;
                case '_dropship_location':
                    $_dropship_location = $location;
            }
        }

        // Update new columns
        if (strlen($_product_freight_class) > 0) {
            $data['meta_data'][] = [
                'key' => '_ltl_freight',
                'value' => $_product_freight_class,
            ];
        }

        // Update new columns
        if (strlen($_product_freight_class_variation) > 0) {
            $data['meta_data'][] = [
                'key' => '_ltl_freight_variation',
                'value' => $_product_freight_class_variation,
            ];
        }

        if (!empty($locations) || !empty($_dropship_location)) {
            if (isset($locations[0]) && is_array($locations[0])) {
                foreach ($locations[0] as $key => $location_arr) {
                    $metaValue = [];
                    if (isset($locations[0][$key], $locations[1][$key], $locations[2][$key], $locations[3][$key])) {
                        $metaValue[0] = $locations[0][$key];
                        $metaValue[1] = $locations[1][$key];
                        $metaValue[2] = $locations[2][$key];
                        $metaValue[3] = $locations[3][$key];
                        $metaValue[4] = $locations[4][$key];
                        $dsId[] = en_serialize_dropship($metaValue);
                    }
                }
            } else {
                $dsId[] = en_serialize_dropship($_dropship_location);
            }

            $sereializedLocations = maybe_serialize($dsId);
            $data['meta_data'][] = [
                'key' => '_dropship_location',
                'value' => $sereializedLocations,
            ];
        }
        return $data;
    }

    add_filter('woocommerce_product_importer_parsed_data', 'en_import_dropship_location_csv', '99', '2');
}

/**
 * Serialize drop ship
 * @param $metaValue
 * @return string
 * @global $wpdb
 */

if (!function_exists('en_serialize_dropship')) {
    function en_serialize_dropship($metaValue)
    {
        global $wpdb;
        $dropship = (array)reset($wpdb->get_results(
            "SELECT id
                        FROM " . $wpdb->prefix . "warehouse WHERE nickname='$metaValue[0]' AND zip='$metaValue[1]' AND city='$metaValue[2]' AND state='$metaValue[3]' AND country='$metaValue[4]'"
        ));

        $dropship = array_map('intval', $dropship);

        if (empty($dropship['id'])) {
            $data = en_csv_import_dropship_data($metaValue);
            $wpdb->insert(
                $wpdb->prefix . 'warehouse', $data
            );

            $dsId = $wpdb->insert_id;
        } else {
            $dsId = $dropship['id'];
        }

        return $dsId;
    }
}

/**
 * Filtered Data Array
 * @param $metaValue
 * @return array
 */
if (!function_exists('en_csv_import_dropship_data')) {
    function en_csv_import_dropship_data($metaValue)
    {
        return array(
            'city' => $metaValue[2],
            'state' => $metaValue[3],
            'zip' => $metaValue[1],
            'country' => $metaValue[4],
            'location' => 'dropship',
            'nickname' => (isset($metaValue[0])) ? $metaValue[0] : "",
        );
    }
}

/**
 * Shipping Message On Cart If No Method Available
 */
if (!function_exists("abf_no_method_available")) {

    function abf_no_method_available()
    {
        $allow_checkout = (isset($_POST['allow_proceed_checkout_eniture'])) ? $_POST['allow_proceed_checkout_eniture'] : get_option('allow_proceed_checkout_eniture');
        $prevent_checkout = (isset($_POST['prevent_proceed_checkout_eniture'])) ? $_POST['prevent_proceed_checkout_eniture'] : get_option('prevent_proceed_checkout_eniture');

        if (get_option('allow_proceed_checkout_eniture') !== false) {
            update_option('allow_proceed_checkout_eniture', $allow_checkout);
            update_option('prevent_proceed_checkout_eniture', $prevent_checkout);
        } else {
            $deprecated = null;
            $autoload = 'no';
            add_option('allow_proceed_checkout_eniture', $allow_checkout, $deprecated, $autoload);
            add_option('prevent_proceed_checkout_eniture', $prevent_checkout, $deprecated, $autoload);
        }
    }

}