<?php

/**
 * ABF Shipping Method
 * @package     Woo-commerce ABF Edition
 * @author      <https://eniture.com/>
 * @copyright   Copyright (c) 2017, Eniture
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Woo-commerce Setting Tab Class
 */
class WC_Settings_ABF_Freight extends WC_Settings_Page
{

    /**
     * Woo-commerce Setting Tab Class Constructor
     */
    public function __construct()
    {
        $this->id = 'abf_quotes';
        add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50);
        add_action('woocommerce_sections_' . $this->id, array($this, 'output_sections'));
        add_action('woocommerce_settings_' . $this->id, array($this, 'output'));
        add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
    }

    /**
     * ABF Setting Tab For Woo-commerce
     * @param $settings_tabs
     * @return string
     */
    public function add_settings_tab($settings_tabs)
    {
        $settings_tabs[$this->id] = __('ABF Freight', 'woocommerce-settings-abf_quotes');
        return $settings_tabs;
    }

    /**
     * ABF Setting Sections
     * @return array
     */
    public function get_sections()
    {
        $sections = array(
            '' => __('Connection Settings', 'woocommerce-settings-abf_quotes'),
            'section-1' => __('Quote Settings', 'woocommerce-settings-abf_quotes'),
            'section-2' => __('Warehouses', 'woocommerce-settings-abf_quotes'),
            'shipping-rules' => __('Shipping Rules', 'woocommerce-settings-abf_quotes'),
            // fdo va
            'section-4' => __('FreightDesk Online', 'woocommerce-settings-abf_quotes'),
            'section-5' => __('Validate Addresses', 'woocommerce-settings-abf_quotes'),
            'section-3' => __('User Guide', 'woocommerce-settings-abf_quotes')
        );

        // Logs data
        $enable_logs = get_option('en_abf_enable_logs');
        if ($enable_logs == 'yes') {
            $sections['en-logs'] = 'Logs';
        }

        $sections = apply_filters('en_woo_addons_sections', $sections, en_woo_plugin_abf_quotes);
        // Standard Packaging
        $sections = apply_filters('en_woo_pallet_addons_sections', $sections, en_woo_plugin_abf_quotes);
        return apply_filters('woocommerce_get_sections_' . $this->id, $sections);
    }

    /**
     * ABF Warehouse Tab
     */
    public function abf_warehouse()
    {
        require_once 'warehouse-dropship/wild/warehouse/warehouse_template.php';
        require_once 'warehouse-dropship/wild/dropship/dropship_template.php';
    }

    /**
     * ABF User Guide Tab
     */
    public function abf_user_guide()
    {
        include_once('template/guide.php');
    }

    /**
     * Display all pages on wc settings tabs
     * @param $section
     * @return array
     */
    public function get_settings($section = null)
    {
        ob_start();
        switch ($section) {
            case 'section-0' :
                $settings = ABF_Connection_Settings::abf_con_setting();
                break;

            case 'section-1' :
                $abf_quote_Settings = new ABF_Quote_Settings();
                $settings = $abf_quote_Settings->abf_quote_settings_tab();
                break;

            case 'section-2':
                $this->abf_warehouse();
                $settings = array();
                break;

            case 'shipping-rules':
                $this->shipping_rules_section();
                $settings = [];
                break;

            case 'section-3' :
                $this->abf_user_guide();
                $settings = array();
                break;
            // fdo va
            case 'section-4' :
                $this->freightdesk_online_section();
                $settings = [];
                break;

            case 'section-5' :
                $this->validate_addresses_section();
                $settings = [];
                break;
            case 'en-logs' :
                require_once 'logs/en-logs.php';
                $settings = [];
                break;

            default:
                $abf_con_settings = new ABF_Connection_Settings();
                $settings = $abf_con_settings->abf_con_setting();

                break;
        }

        $settings = apply_filters('en_woo_addons_settings', $settings, $section, en_woo_plugin_abf_quotes);
        // Standard Packaging
        $settings = apply_filters('en_woo_pallet_addons_settings', $settings, $section, en_woo_plugin_abf_quotes);
        $settings = $this->avaibility_addon($settings);
        return apply_filters('woocommerce-settings-abf_quotes', $settings, $section);
    }

    /**
     * @param array type $settings
     * @return array type
     */
    function avaibility_addon($settings)
    {
        if (is_plugin_active('residential-address-detection/residential-address-detection.php')) {
            unset($settings['avaibility_lift_gate']);
            unset($settings['avaibility_auto_residential']);
        }

        return $settings;
    }

    /**
     * WooCommerce Settings Tabs
     * @global $current_section
     */
    public function output()
    {
        global $current_section;
        $settings = $this->get_settings($current_section);
        WC_Admin_Settings::output_fields($settings);
    }

    /**
     * ABF Save Settings
     * @global $current_section
     */
    public function save()
    {
        global $current_section;
        $settings = $this->get_settings($current_section);
        if (isset($_POST['abf_freight_orderCutoffTime']) && $_POST['abf_freight_orderCutoffTime'] != '') {
            $time24Formate = $this->abfGetTimeIn24Hours($_POST['abf_freight_orderCutoffTime']);
            $_POST['abf_freight_orderCutoffTime'] = $time24Formate;
        }

        if (isset($_POST['eniture_backup_rates']) && !empty($_POST['eniture_backup_rates'])) {
            update_option('eniture_backup_rates', $_POST['eniture_backup_rates']);
        }

        if (isset($_POST['eniture_backup_rates_amount']) && !empty($_POST['eniture_backup_rates_amount'])) {
            update_option('eniture_backup_rates_amount', $_POST['eniture_backup_rates_amount']);
        }

        WC_Admin_Settings::save_fields($settings);
    }

    /**
     * @param $timeStr
     * @return false|string
     */
    public function abfGetTimeIn24Hours($timeStr)
    {
        $cutOffTime = explode(' ', $timeStr);
        $hours = $cutOffTime[0];
        $separator = $cutOffTime[1];
        $minutes = $cutOffTime[2];
        $meridiem = $cutOffTime[3];
        $cutOffTime = "{$hours}{$separator}{$minutes} $meridiem";
        return date("H:i", strtotime($cutOffTime));
    }
    // fdo va
    /**
     * FreightDesk Online section
     */
    public function freightdesk_online_section()
    {
        include_once plugin_dir_path(__FILE__) . 'fdo/freightdesk-online-section.php';
    }

    /**
     * Validate Addresses Section
     */
    public function validate_addresses_section()
    {
        include_once plugin_dir_path(__FILE__) . 'fdo/validate-addresses-section.php';
    }

    public function shipping_rules_section() 
    {
        include_once plugin_dir_path(__FILE__) . 'shipping-rules/shipping-rules-template.php';
    }
}

return new WC_Settings_ABF_Freight();
