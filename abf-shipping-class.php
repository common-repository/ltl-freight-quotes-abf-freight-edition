<?php

/**
 * ABF Shipping Method
 * @package     Woocommerce ABF Edition
 * @author      <https://eniture.com/>
 * @copyright   Copyright (c) 2017, Eniture
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ABF Shipping Calculation Method
 */
function abf_freight_init()
{
    if (!class_exists('ABF_Freight_Shipping')) {

        /**
         * ABF Shipping Calculation Class
         */
        class ABF_Freight_Shipping extends WC_Shipping_Method
        {
            public $smpkgFoundErr = array();
            public $smpkgQuoteErr = array();
            public $FedExSmallRate = array();
            public $forceAllowShipMethod = array();
            public $getPkgObj;
            public $Abf_Quotes_Liftgate_As_Option;
            public $instore_pickup_and_local_delivery;
            public $web_service_inst;
            public $package_plugin;
            public $InstorPickupLocalDelivery;
            public $woocommerce_package_rates;
            public $shipment_type;
            public $quote_settings;
            public $minPrices;
            public $accessorials;
            public $quote_settings_label = '';
            // FDO
            public $en_fdo_meta_data = [];
            public $en_fdo_meta_data_third_party = [];
            // Micro warehouse
            public $min_prices;

            /**
             * Woo-commerce Shipping Field Attributes
             * @param $instance_id
             */
            public function __construct($instance_id = 0)
            {
                error_reporting(0);
                $this->Abf_Quotes_Liftgate_As_Option = new Abf_Quotes_Liftgate_As_Option();
                $this->id = 'abf';
                $this->instance_id = absint($instance_id);
                $this->method_title = __('ABF Freight');
                $this->method_description = __('Shipping rates from ABF Freight.');
                $this->supports = array(
                    'shipping-zones',
                    'instance-settings',
                    'instance-settings-modal',
                );
                $this->enabled = "yes";
                $this->title = 'LTL Freight Quotes - ABF Freight Edition';
                $this->init();
            }

            /**
             * Woo-commerce Shipping Field Attributes
             */
            function init()
            {
                $this->init_form_fields();
                $this->init_settings();
                add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
            }

            /**
             * Enable Woo-commerce Shipping For ABF
             */
            function init_form_fields()
            {
                $this->instance_form_fields = array(
                    'enabled' => array(
                        'title' => __('Enable / Disable', 'abf'),
                        'type' => 'checkbox',
                        'label' => __('Enable This Shipping Service', 'abf'),
                        'default' => 'no',
                        'id' => 'abf_enable_disable_shipping'
                    )
                );
            }

            /**
             *
             * @param type $forceShowMethodsABF
             * @return type
             */
            public function forceAllowShipMethodABF($forceShowMethods)
            {
                if (!empty($this->getPkgObj->ValidShipmentsArrAbf) && (!in_array("ltl_freight", $this->getPkgObj->ValidShipmentsArrAbf))) {
                    $this->forceAllowShipMethod[] = "free_shipping";
                    $this->forceAllowShipMethod[] = "valid_third_party";
                } else {
                    $this->forceAllowShipMethod[] = "ltl_shipment";
                }

                $forceShowMethods = array_merge($forceShowMethods, $this->forceAllowShipMethod);
                return $forceShowMethods;
            }

            /**
             * quote settings details
             * @global $wpdb $wpdb
             */
            function abf_quote_settings()
            {
                $this->web_service_inst->quote_settings['label'] = get_option('wc_settings_abf_label_as');
                $this->web_service_inst->quote_settings['handling_fee'] = get_option('wc_settings_abf_handling_fee');
                $this->web_service_inst->quote_settings['liftgate_delivery'] = get_option('wc_settings_abf_liftgate');
                $this->web_service_inst->quote_settings['liftgate_delivery_option'] = get_option('abf_quotes_liftgate_delivery_as_option');
                $this->web_service_inst->quote_settings['residential_delivery'] = get_option('wc_settings_abf_residential');
                $this->web_service_inst->quote_settings['liftgate_resid_delivery'] = get_option('en_woo_addons_liftgate_with_auto_residential');
                $this->web_service_inst->quote_settings['limited_access_delivery'] = get_option('abf_limited_access_delivery');
                $this->web_service_inst->quote_settings['delivery_estimates'] = get_option('abf_delivery_estimates');
                $this->web_service_inst->quote_settings['orderCutoffTime'] = get_option('abf_freight_orderCutoffTime');
                $this->web_service_inst->quote_settings['shipmentOffsetDays'] = get_option('abf_freight_shipmentOffsetDays');
                $this->web_service_inst->quote_settings['HAT_status'] = get_option('abf_hold_at_terminal_checkbox_status');
                $this->web_service_inst->quote_settings['HAT_fee'] = get_option('abf_hold_at_terminal_fee');
                $this->web_service_inst->quote_settings['handling_weight'] = get_option('handling_weight_abf');
                $this->web_service_inst->quote_settings['maximum_handling_weight'] = get_option('maximum_handling_weight_abf');
            }

            /**
             * Virtual Products
             */
            public function en_virtual_products()
            {
                global $woocommerce;
                $products = $woocommerce->cart->get_cart();
                $items = $product_name = [];
                foreach ($products as $key => $product_obj) {
                    $product = $product_obj['data'];
                    $is_virtual = $product->get_virtual();

                    if ($is_virtual == 'yes') {
                        $attributes = $product->get_attributes();
                        $product_qty = $product_obj['quantity'];
                        $product_title = str_replace(array("'", '"'), '', $product->get_title());
                        $product_name[] = $product_qty . " x " . $product_title;

                        $meta_data = [];
                        if (!empty($attributes)) {
                            foreach ($attributes as $attr_key => $attr_value) {
                                $meta_data[] = [
                                    'key' => $attr_key,
                                    'value' => $attr_value,
                                ];
                            }
                        }

                        $items[] = [
                            'id' => $product_obj['product_id'],
                            'name' => $product_title,
                            'quantity' => $product_qty,
                            'price' => $product->get_price(),
                            'weight' => 0,
                            'length' => 0,
                            'width' => 0,
                            'height' => 0,
                            'type' => 'virtual',
                            'product' => 'virtual',
                            'sku' => $product->get_sku(),
                            'attributes' => $attributes,
                            'variant_id' => 0,
                            'meta_data' => $meta_data,
                        ];
                    }
                }

                $virtual_rate = [];

                if (!empty($items)) {
                    $virtual_rate = [
                        'id' => 'en_virtual_rate',
                        'label' => 'Virtual Quote',
                        'cost' => 0,
                    ];

                    $virtual_fdo = [
                        'plugin_type' => 'ltl',
                        'plugin_name' => 'wwe_quests',
                        'accessorials' => '',
                        'items' => $items,
                        'address' => '',
                        'handling_unit_details' => '',
                        'rate' => $virtual_rate,
                    ];

                    $meta_data = [
                        'sender_origin' => 'Virtual Product',
                        'product_name' => wp_json_encode($product_name),
                        'en_fdo_meta_data' => $virtual_fdo,
                    ];

                    $virtual_rate['meta_data'] = $meta_data;

                }

                return $virtual_rate;
            }

            /**
             * Calculate Shipping Rates For ABF
             * @param string $package
             * @return boolean|string
             */
            public function calculate_shipping($package = array(), $eniture_admin_order_action = false)
            {

                if (is_admin() && !wp_doing_ajax() && !$eniture_admin_order_action) {
                    return [];
                }

                // Backup rates
                if (get_option('wc_pervent_proceed_checkout_eniture') == 'backup_rates') {
                    $rate = array(
                        'id' => $this->id . ':' . 'backup_rates',
                        'label' => get_option('eniture_backup_rates'),
                        'cost' => get_option('eniture_backup_rates_amount'),
                        'plugin_name' => 'abf',
                        'plugin_type' => 'ltl',
                        'owned_by' => 'eniture'
                    );

                    $this->add_rate($rate);
                }

                $this->package_plugin = get_option('abf_freight_package');
                $coupn = WC()->cart->get_coupons();
                if (isset($coupn) && !empty($coupn)) {
                    $free_shipping = $this->abf_freight_free_shipping($coupn);
                    if ($free_shipping == 'y')
                        return FALSE;
                }
                $obj = new abf_shipping_get_package();

                $this->getPkgObj = $obj;
                $this->instore_pickup_and_local_delivery = FALSE;
                $abf_res_inst = new abf_get_shipping_quotes();

                $this->web_service_inst = $abf_res_inst;

                $this->abf_quote_settings();
                // -100% Handling Fee is Invalid
                if (isset($this->web_service_inst->quote_settings['handling_fee']) &&
                    ($this->web_service_inst->quote_settings['handling_fee'] == "-100%")) {
                    $rates = array(
                        'id' => $this->id . ':' . 'free',
                        'label' => 'Free Shipping',
                        'cost' => 0,
                        'plugin_name' => 'abf',
                        'plugin_type' => 'ltl',
                        'owned_by' => 'eniture'
                    );
                    $this->add_rate($rates);

                    return [];
                }

                $abf_package = $obj->group_abf_shipment($package, $abf_res_inst);
                if (empty($abf_package)) return [];
                
                // Apply Hide Methods Shipping Rules
                $shipping_rule_obj = new EnAbfShippingRulesAjaxReq();
                $shipping_rules_applied = $shipping_rule_obj->apply_shipping_rules($abf_package);
                if ($shipping_rules_applied) {
                    return [];
                }
                $handlng_fee = get_option('wc_settings_abf_handling_fee');
                $quotes = array();
                $smallQuotes = array();
                $rate = array();

                add_filter('force_show_methods', array($this, 'forceAllowShipMethodABF'));

                $smallPluginExist = 0;
                $calledMethod = array();
                $no_param_multi_ship = 0;
                if (isset($abf_package['error'])) {
                    return 'error';
                }

                if (count($abf_package) > 1) {
                    foreach ($abf_package as $key => $value) {
                        if (isset($value["NOPARAM"]) && $value["NOPARAM"] === 1 && empty($value["items"])) {
                            $no_param_multi_ship = 1;
                            unset($abf_package[$key]);
                        }
                    }
                }

                $small_products = [];
                $ltl_products = [];
                $eniturePluigns = json_decode(get_option('EN_Plugins'));
                if (isset($abf_package) && !empty($abf_package)) {
                    foreach ($abf_package as $locId => $sPackage) {
                        if (array_key_exists('abf', $sPackage)) {
                            $ltl_products[] = $sPackage;

                            $web_service_arr = $abf_res_inst->abf_shipping_array($sPackage, $this->package_plugin);
                            $response = $abf_res_inst->abf_get_web_quotes($web_service_arr, $abf_package, $locId);
                            $this->InstorPickupLocalDelivery = $abf_res_inst->return_abf_localdelivery_array();
                            if (empty($response)) {
                                if(!empty($this->InstorPickupLocalDelivery)){
                                    (isset($this->InstorPickupLocalDelivery->localDelivery) && ($this->InstorPickupLocalDelivery->localDelivery->status == 1)) ? $this->local_delivery($this->web_service_inst->en_wd_origin_array['fee_local_delivery'], $this->web_service_inst->en_wd_origin_array['checkout_desc_local_delivery'], $this->web_service_inst->en_wd_origin_array) : "";
                                    (isset($this->InstorPickupLocalDelivery->inStorePickup) && ($this->InstorPickupLocalDelivery->inStorePickup->status == 1)) ? $this->pickup_delivery($this->web_service_inst->en_wd_origin_array['checkout_desc_store_pickup'], $this->web_service_inst->en_wd_origin_array, $this->InstorPickupLocalDelivery->totalDistance) : "";
                                }
                                return [];
                            }
                            $quotes[] = $response;
                            continue;
                        } elseif (array_key_exists('small', $sPackage)) {
                            $small_products[] = $sPackage;
                        }
                    }

                    if (isset($small_products) && !empty($small_products) && !empty($ltl_products)) {
                        foreach ($eniturePluigns as $enIndex => $enPlugin) {
                            $freightSmallClassName = 'WC_' . $enPlugin;
                            if (!in_array($freightSmallClassName, $calledMethod)) {
                                if (class_exists($freightSmallClassName)) {
                                    $smallPluginExist = 1;
                                    $SmallClassNameObj = new $freightSmallClassName();
                                    $package['itemType'] = 'ltl';
                                    $package['sPackage'] = $small_products;
                                    $smallQuotesResponse = $SmallClassNameObj->calculate_shipping($package, true);
                                    $smallQuotes[] = $smallQuotesResponse;
                                }
                                $calledMethod[] = $freightSmallClassName;
                            }
                        }
                    }
                }

                if (count($quotes) < 1) {
                    return array();
                }

                // Micro warehouse
                $en_check_action_warehouse_appliance = apply_filters('en_check_action_warehouse_appliance', FALSE);
                if (!$en_check_action_warehouse_appliance && count($quotes) < 1) {
                    return array();
                }

                foreach ($smallQuotes as $small_key => $small_quote) {
                    if (empty($small_quote)) {
                        unset($smallQuotes[$small_key]);
                    }
                }

                $smallQuotes = $this->en_spq_sort($smallQuotes);

                // Small App Quotes
                $smallQuotes = (is_array($smallQuotes) && (!empty($smallQuotes))) ? reset($smallQuotes) : $smallQuotes;
                $smallMinRate = (is_array($smallQuotes) && (!empty($smallQuotes))) ? current($smallQuotes) : $smallQuotes;

                // Virtual products
                $virtual_rate = $this->en_virtual_products();

                // FDO
                if (isset($smallMinRate['meta_data']['en_fdo_meta_data'])) {
                    if (!empty($smallMinRate['meta_data']['en_fdo_meta_data']) && !is_array($smallMinRate['meta_data']['en_fdo_meta_data'])) {
                        $en_third_party_fdo_meta_data = json_decode($smallMinRate['meta_data']['en_fdo_meta_data'], true);
                        isset($en_third_party_fdo_meta_data['data']) ? $smallMinRate['meta_data']['en_fdo_meta_data'] = $en_third_party_fdo_meta_data['data'] : '';
                    }

                    $this->en_fdo_meta_data_third_party = (isset($smallMinRate['meta_data']['en_fdo_meta_data']['address'])) ? [$smallMinRate['meta_data']['en_fdo_meta_data']] : $smallMinRate['meta_data']['en_fdo_meta_data'];
                }

                $smpkgCost = (isset($smallMinRate['cost'])) ? $smallMinRate['cost'] : 0;

                if (isset($smallMinRate) && (!empty($smallMinRate))) {
                    switch (TRUE) {
                        case (isset($smallMinRate['minPrices'])):
                            $small_quotes = $smallMinRate['minPrices'];
                            break;
                        default :
                            $shipment_zipcode = key($smallQuotes);
                            $small_quotes = array($shipment_zipcode => $smallMinRate);
                            break;
                    }
                }

                $this->quote_settings = $this->web_service_inst->quote_settings;
                $handling_fee = $this->quote_settings['handling_fee'];
                $this->accessorials = array();

                ($this->quote_settings['liftgate_delivery'] == "yes") ? $this->accessorials[] = "L" : "";
                ($this->quote_settings['residential_delivery'] == "yes") ? $this->accessorials[] = "R" : "";
                ($this->quote_settings['limited_access_delivery'] == "yes") ? $this->accessorials[] = "LA" : "";
                
                // Virtual products
                if (count($quotes) > 1 || $smpkgCost > 0 || !empty($virtual_rate)) {
                    if (count($quotes) > 1 && !($smpkgCost > 0) && strlen($this->quote_settings['label']) > 0) {
                        $this->quote_settings_label = $this->quote_settings['label'];
                    }

                    // Multiple Shipment
                    $multi_cost = 0;
                    $s_multi_cost = 0;
                    $access_multi_cost = 0;
                    $hold_at_terminal_fee = 0;
                    $_label = "";
                    $access_label = [];
                    $access_append_label = "";
                    $this->minPrices = array();

                    $this->quote_settings['shipment'] = "multi_shipment";
                    $shipment_numbers = 0;

                    (isset($small_quotes) && count($small_quotes) > 0) ? $this->minPrices['ABF_LIFT'] = $small_quotes : "";
                    (isset($small_quotes) && count($small_quotes) > 0) ? $this->minPrices['ABF_NOTLIFT'] = $small_quotes : "";
                    (isset($small_quotes) && count($small_quotes) > 0) ? $this->minPrices['ABF_ACCESS'] = $small_quotes : "";
                    (isset($small_quotes) && count($small_quotes) > 0) ? $this->minPrices['ABF_NOTACCESS'] = $small_quotes : "";
                    (isset($small_quotes) && count($small_quotes) > 0) ? $this->minPrices['ABF_HAT'] = $small_quotes : "";

                    // Virtual products
                    if (!empty($virtual_rate)) {
                        $en_virtual_fdo_meta_data[] = $virtual_rate['meta_data']['en_fdo_meta_data'];
                        $virtual_meta_rate['virtual_rate'] = $virtual_rate;
                        $this->minPrices['ABF_LIFT'] = isset($this->minPrices['ABF_LIFT']) && !empty($this->minPrices['ABF_LIFT']) ? array_merge($this->minPrices['ABF_LIFT'], $virtual_meta_rate) : $virtual_meta_rate;
                        $this->minPrices['ABF_NOTLIFT'] = isset($this->minPrices['ABF_NOTLIFT']) && !empty($this->minPrices['ABF_NOTLIFT']) ? array_merge($this->minPrices['ABF_NOTLIFT'], $virtual_meta_rate) : $virtual_meta_rate;
                        $this->minPrices['ABF_ACCESS'] = isset($this->minPrices['ABF_ACCESS']) && !empty($this->minPrices['ABF_ACCESS']) ? array_merge($this->minPrices['ABF_ACCESS'], $virtual_meta_rate) : $virtual_meta_rate;
                        $this->minPrices['ABF_NOTACCESS'] = isset($this->minPrices['ABF_NOTACCESS']) && !empty($this->minPrices['ABF_NOTACCESS']) ? array_merge($this->minPrices['ABF_NOTACCESS'], $virtual_meta_rate) : $virtual_meta_rate;
                        if ($this->quote_settings['HAT_status'] == "yes") {
                            $this->minPrices['ABF_HAT'] = isset($this->minPrices['ABF_HAT']) && !empty($this->minPrices['ABF_HAT']) ? array_merge($this->minPrices['ABF_HAT'], $virtual_meta_rate) : $virtual_meta_rate;
                        }
                        $this->en_fdo_meta_data_third_party = !empty($this->en_fdo_meta_data_third_party) ? array_merge($this->en_fdo_meta_data_third_party, $en_virtual_fdo_meta_data) : $en_virtual_fdo_meta_data;
                    }

                    foreach ($quotes as $key => $quote) {
                        if (!empty($quote)) {
                            $key = "LTL_" . $key;

                            // Hold At Terminal is enabled
                            if (isset($quote['hold_at_terminal_quotes'])) {
                                $hold_at_terminal_quotes = $quote['hold_at_terminal_quotes'];
                                $this->minPrices['ABF_HAT'][$key] = $hold_at_terminal_quotes;

                                // FDO
                                $this->en_fdo_meta_data['ABF_HAT'][$key] = (isset($hold_at_terminal_quotes['meta_data']['en_fdo_meta_data'])) ? $hold_at_terminal_quotes['meta_data']['en_fdo_meta_data'] : [];

                                $hold_at_terminal_fee += $hold_at_terminal_quotes['cost'];
                                unset($quote['hold_at_terminal_quotes']);
                                $append_hat_label = (isset($hold_at_terminal_quotes['hat_append_label'])) ? $hold_at_terminal_quotes['hat_append_label'] : "";
                                $append_hat_label = (isset($hold_at_terminal_quotes['_hat_append_label']) && (strlen($append_hat_label) > 0)) ? $append_hat_label . $hold_at_terminal_quotes['_hat_append_label'] : $append_hat_label;
                                $hat_label = array();
                            }

                            $simple_quotes = (isset($quote['simple_quotes'])) ? $quote['simple_quotes'] : array();
                            $limited_access_quotes = isset($quote['limited_access_quotes']) ? $quote['limited_access_quotes'] : array();
                            unset($quote['limited_access_quotes']);
                            $quote = $this->remove_array($quote, 'simple_quotes');
                            $rates = (is_array($quote) && (!empty($quote))) ? $quote : array();
                            $this->minPrices['ABF_LIFT'][$key] = $rates;

                            if (!empty($limited_access_quotes)) {
                                $this->minPrices['ABF_ACCESS'][$key] = $limited_access_quotes;
                                $this->en_fdo_meta_data['ABF_ACCESS'][$key] = (isset($limited_access_quotes['meta_data']['en_fdo_meta_data'])) ? $limited_access_quotes['meta_data']['en_fdo_meta_data'] : [];

                                $access_cost = (isset($limited_access_quotes['cost'])) ? $limited_access_quotes['cost'] : 0;
                                $access_label = (isset($limited_access_quotes['label_sufex'])) ? $limited_access_quotes['label_sufex'] : array();
                                $access_append_label = (isset($limited_access_quotes['append_label'])) ? $limited_access_quotes['append_label'] : "";

                                // Product level markup
                                if(!empty($rates['product_level_markup'])){
                                    $access_cost = $this->add_handling_fee($access_cost, $rates['product_level_markup']);
                                }

                                // origin level markup
                                if(!empty($rates['origin_markup'])){
                                    $access_cost = $this->add_handling_fee($access_cost, $rates['origin_markup']);
                                }

                                $access_multi_cost += $this->add_handling_fee($access_cost, $handling_fee);
                                $this->minPrices['ABF_ACCESS'][$key]['cost'] = $this->add_handling_fee($access_cost, $handling_fee);
                                $this->en_fdo_meta_data['ABF_ACCESS'][$key]['rate']['cost'] = $this->add_handling_fee($access_cost, $handling_fee);
                            }

                            // FDO
                            $this->en_fdo_meta_data['ABF_LIFT'][$key] = (isset($rates['meta_data']['en_fdo_meta_data'])) ? $rates['meta_data']['en_fdo_meta_data'] : [];

                            $_cost = (isset($rates['cost'])) ? $rates['cost'] : 0;

                            $_label = (isset($rates['label_sufex'])) ? $rates['label_sufex'] : array();
                            $append_label = (isset($rates['append_label'])) ? $rates['append_label'] : "";
                            $handling_fee = (isset($rates['markup']) && (strlen($rates['markup']) > 0)) ? $rates['markup'] : $handling_fee;

                            // Offer lift gate delivery as an option is enabled
                            if (isset($this->quote_settings['liftgate_delivery_option']) &&
                                ($this->quote_settings['liftgate_delivery_option'] == "yes") &&
                                (!empty($simple_quotes))) {
                                $s_rates = $simple_quotes;
                                $this->minPrices['ABF_NOTLIFT'][$key] = $s_rates;

                                // FDO
                                $this->en_fdo_meta_data['ABF_NOTLIFT'][$key] = (isset($s_rates['meta_data']['en_fdo_meta_data'])) ? $s_rates['meta_data']['en_fdo_meta_data'] : [];

                                $s_cost = (isset($s_rates['cost'])) ? $s_rates['cost'] : 0;
                                $s_label = (isset($s_rates['label_sufex'])) ? $s_rates['label_sufex'] : array();
                                $s_append_label = (isset($s_rates['append_label'])) ? $s_rates['append_label'] : "";

                                // product level markup
                                if(!empty($s_rates['product_level_markup'])){
                                    $s_cost = $this->add_handling_fee($s_cost, $s_rates['product_level_markup']);
                                }

                                // origin level markup
                                if(!empty($s_rates['origin_markup'])){
                                    $s_cost = $this->add_handling_fee($s_cost, $s_rates['origin_markup']);
                                }

                                $s_multi_cost += $this->add_handling_fee($s_cost, $handling_fee);
                                $this->minPrices['ABF_NOTLIFT'][$key]['cost'] = $this->add_handling_fee($s_cost, $handling_fee);
                                $this->en_fdo_meta_data['ABF_NOTLIFT'][$key]['rate']['cost'] = $this->add_handling_fee($s_cost, $handling_fee);
                            }

                            // Product level markup
                            if(!empty($rates['product_level_markup'])){
                                $_cost = $this->add_handling_fee($_cost, $rates['product_level_markup']);
                            }

                            // origin level markup
                            if(!empty($rates['origin_markup'])){
                                $_cost = $this->add_handling_fee($_cost, $rates['origin_markup']);
                            }

                            $multi_cost += $this->add_handling_fee($_cost, $handling_fee);
                            $this->minPrices['ABF_LIFT'][$key]['cost'] = $this->add_handling_fee($_cost, $handling_fee);
                            $this->en_fdo_meta_data['ABF_LIFT'][$key]['rate']['cost'] = $this->add_handling_fee($_cost, $handling_fee);

                            $shipment_numbers++;
                        }
                    }

                    $this->quote_settings['shipment_numbers'] = $shipment_numbers;

                    // Create Array to add_rate Woocommerce
                     // Excluded accessorials
                    $en_accessorial_excluded = apply_filters('en_abf_accessorial_excluded', []);
                    ($s_multi_cost > 0) ? $rate[] = $this->arrange_multiship_freight(($s_multi_cost + $smpkgCost), 'ABF_NOTLIFT', $s_label, $s_append_label) : "";
                    if ($s_multi_cost > 0 && !empty($en_accessorial_excluded) && in_array('liftgateResidentialExcluded', $en_accessorial_excluded)) {
                        $multi_cost = 0;
                    }
                    ($multi_cost > 0 || $smpkgCost > 0) ? $rate[] = $this->arrange_multiship_freight(($multi_cost + $smpkgCost), 'ABF_LIFT', $_label, $append_label) : "";
                    ($access_multi_cost > 0 || $smpkgCost > 0) ? $rate[] = $this->arrange_multiship_freight(($access_multi_cost + $smpkgCost), 'ABF_ACCESS', $access_label, $access_append_label) : "";
                    ($hold_at_terminal_fee > 0) ? $rate[] = $this->arrange_multiship_freight(($hold_at_terminal_fee + $smpkgCost), 'ABF_HAT', $hat_label, $append_hat_label) : "";

                    // combined rates for lift gate with limited access delivery
                    if ($s_multi_cost > 0 && $multi_cost > 0 && $access_multi_cost > 0) {
                        $combined_multi_cost = 0;
                        $combined_label = ['L', 'LA'];
                        $combined_append_label = $append_label . ' and ' . $access_append_label;
                        
                        if (is_array($this->minPrices['ABF_LIFT']) && !empty($this->minPrices['ABF_LIFT'])) {
                            $lgf_rates = json_decode(json_encode($this->minPrices['ABF_LIFT']), true);
                            foreach ($lgf_rates as $key => $quote) {
                                $shipment_cost = isset($quote['cost']) ? $quote['cost'] : 0;
                                $la_fee = isset($quote['surcharges']['laFee']['Amount']) ? $quote['surcharges']['laFee']['Amount'] : 0;

                                $shipment_cost += floatval($la_fee);
                                $quote['cost'] = $shipment_cost;
                                $combined_multi_cost += floatval($shipment_cost);

                                $quote['label_sfx_arr'] = $lfg_and_limited_quotes['label_sufex'] = $combined_label;

                                if (isset($quote['meta_data']['en_fdo_meta_data'])) {
                                    $quote['meta_data']['en_fdo_meta_data']['accessorials']['liftgate'] = true;
                                    $quote['meta_data']['en_fdo_meta_data']['accessorials']['limitedaccess'] = true;
                                    $quote['meta_data']['en_fdo_meta_data']['accessorials']['residential'] = false;

                                    $quote['meta_data']['en_fdo_meta_data']['rate']['cost'] = $shipment_cost;
                                }

                                $this->minPrices['ABF_LIFTACCESS'][$key] = $quote;
                                $this->en_fdo_meta_data['ABF_LIFTACCESS'][$key] = isset($quote['meta_data']['en_fdo_meta_data']) ? $quote['meta_data']['en_fdo_meta_data'] : [];
                            }
                        }

                        ($combined_multi_cost > 0 || $smpkgCost) ? $rate[] = $this->arrange_multiship_freight(($combined_multi_cost + $smpkgCost), 'ABF_LIFTACCESS', $combined_label, $combined_append_label) : "";
                    }

                    $rates = $rate;

                    $this->shipment_type = 'multiple';
                } else {
                    // Single Shipment
                    $quote = (is_array($quotes) && (!empty($quotes))) ? reset($quotes) : array();
                    if (!empty($quote)) {
                        if (isset($quote['hold_at_terminal_quotes'])) {
                            $rates[] = $quote['hold_at_terminal_quotes'];
                            unset($quote['hold_at_terminal_quotes']);
                        }

                        $simple_quotes = (isset($quote['simple_quotes'])) ? $quote['simple_quotes'] : array();
                        $limited_access_quotes = isset($quote['limited_access_quotes']) ? $quote['limited_access_quotes'] : array();
                        unset($quote['limited_access_quotes']);
                        $lfg_quotes = $this->remove_array($quote, 'simple_quotes');
                        $rates[] = $lfg_quotes;
                        $rates[] = $limited_access_quotes;
                        
                        // check for both lift gate and limited access quotes
                        if (!empty($simple_quotes) && !empty($lfg_quotes) && !empty($limited_access_quotes)) {
                            $lfg_and_limited_quotes = json_decode(json_encode($lfg_quotes), true);
                            
                            $limitedFee = isset($lfg_and_limited_quotes['surcharges']['laFee']['Amount']) ? $lfg_and_limited_quotes['surcharges']['laFee']['Amount'] : 0;

                            $lfg_and_limited_quotes['id'] .= 'WLLA';
                            $lfg_and_limited_quotes['id'] = 'abf_with_LG_and_LA';
                            $lfg_and_limited_quotes['cost'] += floatval($limitedFee);
                            $lfg_and_limited_quotes['label_sfx_arr'] = $lfg_and_limited_quotes['label_sufex'] = ['L', 'LA'];

                            if (isset($lfg_and_limited_quotes['meta_data']['min_quotes'])) {
                                unset($lfg_and_limited_quotes['meta_data']['min_quotes']);
                            }

                            // FDO
                            if (isset($lfg_and_limited_quotes['meta_data']['en_fdo_meta_data']['accessorials'])) {
                                $lfg_and_limited_quotes['meta_data']['en_fdo_meta_data']['accessorials']['liftgate'] = true;
                                $lfg_and_limited_quotes['meta_data']['en_fdo_meta_data']['accessorials']['limitedaccess'] = true;
                                $lfg_and_limited_quotes['meta_data']['en_fdo_meta_data']['accessorials']['residential'] = false;
                            }

                            if (isset($lfg_and_limited_quotes['meta_data']['en_fdo_meta_data']['rate']['cost'])) {
                                $lfg_and_limited_quotes['meta_data']['en_fdo_meta_data']['rate']['cost'] = $lfg_and_limited_quotes['cost'];
                                $lfg_and_limited_quotes['meta_data']['en_fdo_meta_data']['rate']['label_sfx_arr'] = $lfg_and_limited_quotes['label_sufex'];
                            }

                            $rates[] = $lfg_and_limited_quotes;
                        }

                        // Offer lift gate delivery as an option is enabled
                        if (isset($this->quote_settings['liftgate_delivery_option']) &&
                            ($this->quote_settings['liftgate_delivery_option'] == "yes") &&
                            (!empty($simple_quotes))) {
                            $rates[] = $simple_quotes;
                        }

                        $cost_sorted_key = array();

                        $this->quote_settings['shipment'] = "single_shipment";
                        $this->quote_settings['shipment_numbers'] = "1";

                        if (is_array($rates) && (!empty($rates))) {
                            foreach ($rates as $key => $quote) {

                                $handling_fee = (isset($rates['markup']) && (strlen($rates['markup']) > 0)) ? $rates['markup'] : $handling_fee;
                                $_cost = (isset($quote['cost'])) ? $quote['cost'] : 0;

                                // Product level markup
                                if(!empty($quote['product_level_markup'])){
                                    $_cost = $this->add_handling_fee($_cost, $quote['product_level_markup']);
                                }

                                // origin level markup
                                if(!empty($quote['origin_markup'])){
                                    $_cost = $this->add_handling_fee($_cost, $quote['origin_markup']);
                                }

                                if (!isset($quote['hat_append_label'])) {
                                    (isset($rates[$key]['cost'])) ? $rates[$key]['cost'] = $this->add_handling_fee($_cost, $handling_fee) : "";
                                    (isset($rates[$key]['meta_data']['en_fdo_meta_data']['rate']['cost'])) ? $rates[$key]['meta_data']['en_fdo_meta_data']['rate']['cost'] = $this->add_handling_fee($_cost, $handling_fee) : "";
                                }

                                (isset($rates[$key]['cost'])) ? $rates[$key]['cost'] = $this->add_handling_fee($_cost, $handling_fee) : "";
                                (isset($rates[$key]['meta_data']['en_fdo_meta_data']['rate']['cost'])) ? $rates[$key]['meta_data']['en_fdo_meta_data']['rate']['cost'] = $this->add_handling_fee($_cost, $handling_fee) : "";

                                $cost_sorted_key[$key] = (isset($quote['cost'])) ? $quote['cost'] : 0;
                                (isset($rates[$key]['shipment'])) ? $rates[$key]['shipment'] = "single_shipment" : "";
                            }

                            // Array asec sort
                            array_multisort($cost_sorted_key, SORT_ASC, $rates);
                        }
                    }

                    $this->shipment_type = 'single';

                }

                // Sorting rates in ascending order
                $rate = $this->sort_asec_order_arr($rates);
                $rates = $this->abf_add_rate_arr($rate);

                // Origin terminal address
                if ($this->shipment_type == 'single') {
                    (isset($this->InstorPickupLocalDelivery->localDelivery) && ($this->InstorPickupLocalDelivery->localDelivery->status == 1)) ? $this->local_delivery($this->web_service_inst->en_wd_origin_array['fee_local_delivery'], $this->web_service_inst->en_wd_origin_array['checkout_desc_local_delivery'], $this->web_service_inst->en_wd_origin_array) : "";
                    (isset($this->InstorPickupLocalDelivery->inStorePickup) && ($this->InstorPickupLocalDelivery->inStorePickup->status == 1)) ? $this->pickup_delivery($this->web_service_inst->en_wd_origin_array['checkout_desc_store_pickup'], $this->web_service_inst->en_wd_origin_array, $this->InstorPickupLocalDelivery->totalDistance) : "";
                }
                return $rates;
            }

            public function en_spq_sort($smallQuotes)
            {
                $spq_quotes = [];
                foreach ($smallQuotes as $key => $quote) {
                    $quote = (is_array($quote) && (!empty($quote))) ? reset($quote) : $quote;
                    !empty($quote) && isset($quote['cost']) ? $spq_quotes[] = $quote : '';
                }

                if (!empty($spq_quotes)) {
                    $rates[] = $this->sort_asec_order_arr($spq_quotes);
                    return $rates;
                }

                return $smallQuotes;
            }

            /**
             * Multi-shipment
             * @return array
             */
            function arrange_multiship_freight($cost, $id, $label_sufex, $append_label)
            {

                $multiship = array(
                    'id' => $id,
                    'label' => "Freight",
                    'cost' => $cost,
                    'label_sufex' => $label_sufex,
                    'plugin_name' => 'abf',
                    'plugin_type' => 'ltl',
                    'owned_by' => 'eniture',
                );

                ($id == 'ABF_HAT') ? $multiship['hat_append_label'] = $append_label : $multiship['append_label'] = $append_label;

                return $multiship;
            }

            /**
             * Remove array
             * @return array
             */
            public function remove_array($quote, $remove_index)
            {
                unset($quote[$remove_index]);

                return $quote;
            }

            /**
             * add the handling fee
             * @param string type $price
             * @param string type $handling_fee
             * @return float type
             */
            function add_handling_fee($price, $handling_fee)
            {
                $handling_fee = $price > 0 ? $handling_fee : 0;
                $handelingFee = 0;
                if ($handling_fee != '' && $handling_fee != 0) {
                    if (strrchr($handling_fee, "%")) {

                        $prcnt = (float)$handling_fee;
                        $handelingFee = (float)$price / 100 * $prcnt;
                    } else {
                        $handelingFee = (float)$handling_fee;
                    }
                }

                $handelingFee = $this->smooth_round($handelingFee);

                $price = (float)$price + $handelingFee;
                return $price;
            }

            /**
             * round the price
             * @param float type $val
             * @param int type $min
             * @param int type $max
             * @return float type
             */
            function smooth_round($val, $min = 2, $max = 4)
            {
                $result = round($val, $min);

                if ($result == 0 && $min < $max) {
                    return $this->smooth_round($val, ++$min, $max);
                } else {
                    return $result;
                }
            }

            /**
             * sort array
             * @param array type $rate
             * @return array type
             */
            public function sort_asec_order_arr($rate)
            {
                $price_sorted_key = array();
                foreach ($rate as $key => $cost_carrier) {
                    $price_sorted_key[$key] = (isset($cost_carrier['cost'])) ? $cost_carrier['cost'] : 0;
                }
                array_multisort($price_sorted_key, SORT_ASC, $rate);

                return $rate;
            }

            /**
             * Pickup delivery quote
             * @return array type
             */
            function pickup_delivery($label, $en_wd_origin_array, $total_distance)
            {
                $this->woocommerce_package_rates = 1;
                $this->instore_pickup_and_local_delivery = TRUE;

                $label = (isset($label) && (strlen($label) > 0)) ? $label : 'In-store pick up';
                // Origin terminal address
                $address = (isset($en_wd_origin_array['address'])) ? $en_wd_origin_array['address'] : '';
                $city = (isset($en_wd_origin_array['city'])) ? $en_wd_origin_array['city'] : '';
                $state = (isset($en_wd_origin_array['state'])) ? $en_wd_origin_array['state'] : '';
                $zip = (isset($en_wd_origin_array['zip'])) ? $en_wd_origin_array['zip'] : '';
                $phone_instore = (isset($en_wd_origin_array['phone_instore'])) ? $en_wd_origin_array['phone_instore'] : '';
                strlen($total_distance) > 0 ? $label .= ': Free | ' . str_replace("mi", "miles", $total_distance) . ' away' : '';
                strlen($address) > 0 ? $label .= ' | ' . $address : '';
                strlen($city) > 0 ? $label .= ', ' . $city : '';
                strlen($state) > 0 ? $label .= ' ' . $state : '';
                strlen($zip) > 0 ? $label .= ' ' . $zip : '';
                strlen($phone_instore) > 0 ? $label .= ' | ' . $phone_instore : '';

                $pickup_delivery = array(
                    'id' => $this->id . ':' . 'in-store-pick-up',
                    'cost' => 0,
                    'label' => $label,
                );

                add_filter('woocommerce_package_rates', array($this, 'en_sort_woocommerce_available_shipping_methods'), 10, 2);
                $this->add_rate($pickup_delivery);
            }

            /**
             * Local delivery quote
             * @param string type $cost
             * @return array type
             */
            function local_delivery($cost, $label, $en_wd_origin_array)
            {
                $this->woocommerce_package_rates = 1;
                $this->instore_pickup_and_local_delivery = TRUE;
                $label = (isset($label) && (strlen($label) > 0)) ? $label : 'Local Delivery';

                $local_delivery = array(
                    'id' => $this->id . ':' . 'local-delivery',
                    'cost' => $cost,
                    'label' => $label,
                );

                add_filter('woocommerce_package_rates', array($this, 'en_sort_woocommerce_available_shipping_methods'), 10, 2);
                $this->add_rate($local_delivery);
            }

            /**
             * filter and update label
             * @param type $label_sufex
             * @return string
             */
            public function filter_from_label_sufex($label_sufex)
            {
                $append_label = "";
                $rad_status = true;
                $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
                if (stripos(implode($all_plugins), 'residential-address-detection.php') || is_plugin_active_for_network('residential-address-detection/residential-address-detection.php')) {
                    if(get_option('suspend_automatic_detection_of_residential_addresses') != 'yes') {
                        $rad_status = get_option('residential_delivery_options_disclosure_types_to') != 'not_show_r_checkout';
                    }
                }
                switch (TRUE) {
                    case(count($label_sufex) == 1):
                        (in_array('L', $label_sufex)) ? $append_label = " with lift gate delivery " : "";
                        (in_array('R', $label_sufex) && $rad_status == true) ? $append_label = " with residential delivery " : "";
                        (in_array('LA', $label_sufex)) ? $append_label = " with limited access delivery " : "";
                        break;
                    case(count($label_sufex) > 1):
                        (in_array('L', $label_sufex)) ? $append_label = " with lift gate delivery " : "";
                        (in_array('LA', $label_sufex)) ? $append_label .= (strlen($append_label) > 0) ? " and limited access delivery " : " with limited access delivery " : "";
                        (in_array('R', $label_sufex) && $rad_status == true) ? $append_label .= (strlen($append_label) > 0) ? " and residential delivery " : " with residential delivery " : "";
                        break;
                }

                return $append_label;
            }

            /**
             * update label in quote
             * @param array type $rate
             * @return string type
             */
            public function set_label_in_quote($rate)
            {
                $rate_label = "";
                $label_sufex = (isset($rate['label_sufex']) && (!empty($rate['label_sufex']))) ? array_unique($rate['label_sufex']) : array();
                $rate_label = (isset($rate['label'])) ? $rate['label'] : "Freight";
                $rate_label .= $this->filter_from_label_sufex($label_sufex);
                $rate_label .= (isset($rate['hat_append_label'])) ? $rate['hat_append_label'] : "";
                $rate_label .= (isset($rate['_hat_append_label'])) ? $rate['_hat_append_label'] : "";

                $shipment_type = isset($this->quote_settings['shipment']) && !empty($this->quote_settings['shipment']) ? $this->quote_settings['shipment'] : '';
                if (isset($this->quote_settings['delivery_estimates']) && !empty($this->quote_settings['delivery_estimates'])
                    && $this->quote_settings['delivery_estimates'] != 'dont_show_estimates' && $shipment_type != 'multi_shipment') {
                    if ($this->quote_settings['delivery_estimates'] == 'delivery_date') {
                        is_string($rate['deliveryTimestamp']) && strlen($rate['deliveryTimestamp']) > 0 ? $rate_label .= ' (Expected delivery by ' . date('m-d-Y', strtotime($rate['deliveryTimestamp'])) . ')' : '';
                    } else if ($this->quote_settings['delivery_estimates'] == 'delivery_days') {
                        $correct_word = ($rate['delivery_estimates'] == 1) ? 'is' : 'are';
                        is_string($rate['delivery_estimates']) && strlen($rate['delivery_estimates']) > 0 ? $rate_label .= ' (Intransit days: ' . $rate['delivery_estimates'] . ')' : '';
                    }
                }

                return $rate_label;
            }

            /**
             * rates to add_rate function woo-commerce
             * @param array type $add_rate_arr
             */
            public function abf_add_rate_arr($add_rate_arr)
            {
                if (isset($add_rate_arr) && (!empty($add_rate_arr)) && (is_array($add_rate_arr))) {

                    // Images for FDO
                    $image_urls = apply_filters('en_fdo_image_urls_merge', []);

                    add_filter('woocommerce_package_rates', array($this, 'en_sort_woocommerce_available_shipping_methods'), 10, 2);

                    // In-store pickup and local delivery
                    $instore_pickup_local_devlivery_action = apply_filters('abf_quotes_quotes_plans_suscription_and_features', 'instore_pickup_local_devlivery');

                    foreach ($add_rate_arr as $key => $rate) {

                        if (isset($rate['cost']) && $rate['cost'] > 0) {
                            
                            $rate['label'] = $this->set_label_in_quote($rate);
                            
                            // Updated label in meta data for OW details
                            if (isset($rate['meta_data']['en_fdo_meta_data']['rate'])) {
                                $rate['meta_data']['en_fdo_meta_data']['rate']['label'] = $rate['label'];
                            }

                            if (isset($rate['meta_data'])) {
                                $rate['meta_data']['label_sufex'] = (isset($rate['label_sufex'])) ? json_encode($rate['label_sufex']) : array();
                            }

                            if (isset($this->minPrices[$rate['id']])) {
                                $rate['meta_data']['min_prices'] = json_encode($this->minPrices[$rate['id']]);
                            }

                            // Micro warehouse
                            $en_check_action_warehouse_appliance = apply_filters('en_check_action_warehouse_appliance', FALSE);
                            if ($this->shipment_type == 'multiple' && $en_check_action_warehouse_appliance && !empty($this->minPrices)) {
                                $rate['meta_data']['min_quotes'] = $this->minPrices[$rate['id']];
                            }

                            if (isset($this->minPrices[$rate['id']])) {
                                $rate['meta_data']['min_prices'] = json_encode($this->minPrices[$rate['id']]);
                                $rate['meta_data']['en_fdo_meta_data']['data'] = array_values($this->en_fdo_meta_data[$rate['id']]);
                                (!empty($this->en_fdo_meta_data_third_party)) ? $rate['meta_data']['en_fdo_meta_data']['data'] = array_merge($rate['meta_data']['en_fdo_meta_data']['data'], $this->en_fdo_meta_data_third_party) : '';
                                $rate['meta_data']['en_fdo_meta_data']['shipment'] = 'multiple';
                                $rate['meta_data']['en_fdo_meta_data'] = wp_json_encode($rate['meta_data']['en_fdo_meta_data']);
                            } else {
                                $en_set_fdo_meta_data['data'] = [$rate['meta_data']['en_fdo_meta_data']];
                                $en_set_fdo_meta_data['shipment'] = 'single';
                                $rate['meta_data']['en_fdo_meta_data'] = wp_json_encode($en_set_fdo_meta_data);
                            }

                            // Images for FDO
                            $rate['meta_data']['en_fdo_image_urls'] = wp_json_encode($image_urls);
                            $rate['id'] = isset($rate['id']) && is_string($rate['id']) ? $this->id . ':' . $rate['id'] : '';

                            if ($this->web_service_inst->en_wd_origin_array['suppress_local_delivery'] == "1" && (!is_array($instore_pickup_local_devlivery_action)) && $this->shipment_type != "multiple") {
                                $rate = apply_filters('suppress_local_delivery', $rate, $this->web_service_inst->en_wd_origin_array, $this->package_plugin, $this->InstorPickupLocalDelivery);

                                if (!empty($rate)) {
                                    $this->add_rate($rate);
                                    $this->woocommerce_package_rates = 1;
                                    $add_rate_arr[$key] = $rate;
                                }
                            } else {
                                $this->add_rate($rate);
                                $add_rate_arr[$key] = $rate;
                            }
                        }
                    }
                }

                return $add_rate_arr;
            }

            /**
             * final rates sorting
             * @param array type $rates
             * @param array type $package
             * @return array type
             */
            function en_sort_woocommerce_available_shipping_methods($rates, $package)
            {
                // if there are no rates don't do anything

                if (!$rates) {
                    return array();
                }

                // Check the option to sort shipping methods by price on quote settings
                if (get_option('shipping_methods_do_not_sort_by_price') != 'yes') {

                    // Get an array of prices
                    $prices = array();
                    foreach ($rates as $rate) {
                        $prices[] = $rate->cost;
                    }

                    // Use the prices to sort the rates
                    array_multisort($prices, $rates);
                }

                return $rates;
            }

            /**
             * Check is free shipping or not
             * @param $coupon
             * @return string
             */
            function abf_freight_free_shipping($coupon)
            {
                foreach ($coupon as $key => $value) {
                    if ($value->get_free_shipping() == 1) {
                        $rates = array(
                            'id' => 'free',
                            'label' => 'Free Shipping',
                            'cost' => 0
                        );
                        $this->add_rate($rates);
                        return 'y';
                    }
                }
                return 'n';
            }

        }

    }
}
