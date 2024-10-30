<?php

/**
 * ABF Getting Shipping Carriers
 * @package     Woocommerce ABF Edition
 * @author      <https://eniture.com/>
 * @copyright   Copyright (c) 2017, Eniture
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get ESTES LTL Quotes Rate Class
 */
class abf_get_shipping_quotes extends Abf_Quotes_Liftgate_As_Option
{

    /**
     * Create Shipping Package
     * @param $packages
     * @return array/string
     */
    public $localdeliver;
    public $en_wd_origin_array;
    public $quote_settings;
    public $en_accessorial_excluded;

    function __construct()
    {
        $this->quote_settings = array();
    }

    function abf_shipping_array($packages, $package_plugin = "")
    {
        // FDO
        $EnabffreightFdo = new EnabffreightFdo();
        $en_fdo_meta_data = array();

        $destinationAddressABF = $this->destinationAddressABF();
        $residential_detecion_flag = get_option("en_woo_addons_auto_residential_detecion_flag");
        $abf_liftgate = "N";
        $abf_residential = "N";
        $wc_liftgate = get_option('wc_settings_abf_liftgate');
        if ($wc_liftgate == 'yes') {
            $abf_liftgate = 'Y';
        }
        $wc_residential = get_option('wc_settings_abf_residential');
        if ($wc_residential == 'yes') {
            $abf_residential = 'Y';
        }

        $liftGateAsAnOption = '0';
        if ($this->quote_settings['liftgate_delivery_option'] == "yes") {
            $liftGateAsAnOption = '1';
        }

        $this->en_wd_origin_array = (isset($packages['origin'])) ? $packages['origin'] : array();

        $aPluginVersions = $this->abf_wc_version_number();

        $domain = abf_freight_get_domain();

        $lineItem = array();
        $lineItem = array();
        $product_name = array();
        $hazardous = array();
        $shipmentWeekDays = "";
        $orderCutoffTime = "";
        $shipmentOffsetDays = "";
        $modifyShipmentDateTime = "";
        $storeDateTime = "";
        //End: check

        $abf_delivery_estimates = get_option('abf_delivery_estimates');
        $shipmentWeekDays = $this->abf_shipment_week_days();
        if ($abf_delivery_estimates == 'delivery_days' || $abf_delivery_estimates == 'delivery_date') {
            $orderCutoffTime = $this->quote_settings['orderCutoffTime'];
            $shipmentOffsetDays = $this->quote_settings['shipmentOffsetDays'];
            $modifyShipmentDateTime = ($orderCutoffTime != '' || $shipmentOffsetDays != '' || (is_array($shipmentWeekDays) && count($shipmentWeekDays) > 0)) ? 1 : 0;
            $storeDateTime = $today = date('Y-m-d H:i:s', current_time('timestamp'));
        }

        $nested_plan = apply_filters('abf_quotes_quotes_plans_suscription_and_features', 'nested_material');
        $nestingPercentage = $nestedDimension = $nestedItems = $stakingProperty = [];
        $doNesting = false;
        $product_markup_shipment = 0;

        foreach ($packages['items'] as $item) {
            $product_id = (isset($item['variantId']) && $item['variantId'] > 0) ? $item['variantId'] : $item['productId'];

            // Standard Packaging
            $ship_as_own_pallet = isset($item['ship_as_own_pallet']) && $item['ship_as_own_pallet'] == 'yes' ? 1 : 0;
            $vertical_rotation_for_pallet = isset($item['vertical_rotation_for_pallet']) && $item['vertical_rotation_for_pallet'] == 'yes' ? 1 : 0;
            $abf_counter = (isset($item['variantId']) && $item['variantId'] > 0) ? $item['variantId'] : $item['productId'];
            $nmfc_num = (isset($item['nmfc_number'])) ? $item['nmfc_number'] : '';
            $lineItem[$abf_counter] = array(
                'piecesOfLineItem' => $item['productQty'],
                'lineItemClass' => $item['productClass'],
                'lineItemWeight' => $item['productWeight'],
                'lineItemWidth' => $item['productWidth'],
                'lineItemHeight' => $item['productHeight'],
                'lineItemLength' => $item['productLength'],
                'lineItemPackageCode' => 'PLT',
                // Nested indexes
                'nestingPercentage' => $item['nestedPercentage'],
                'nestingDimension' => $item['nestedDimension'],
                'nestedLimit' => $item['nestedItems'],
                'nestedStackProperty' => $item['stakingProperty'],

                // Shippable handling units
                'lineItemPalletFlag' => $item['lineItemPalletFlag'],
                'lineItemPackageType' => $item['lineItemPackageType'],
                'lineItemNMFC' => $item['lineItemNMFC'],

                // Standard Packaging
                'shipPalletAlone' => $ship_as_own_pallet,
                'vertical_rotation' => $vertical_rotation_for_pallet
            );

            $lineItem[$abf_counter] = apply_filters('en_fdo_carrier_service', $lineItem[$abf_counter], $item);

            $product_name[] = $item['product_name'];

            isset($item['nestedMaterial']) && !empty($item['nestedMaterial']) &&
            $item['nestedMaterial'] == 'yes' && !is_array($nested_plan) ? $doNesting = 1 : "";

            if(!empty($item['markup']) && is_numeric($item['markup'])){
                $product_markup_shipment += $item['markup'];
            }
        }

        // FDO
        $en_fdo_meta_data = $EnabffreightFdo->en_cart_package($packages);

        $post_data = array(
            'plateform' => 'WordPress',
            'plugin_version' => $aPluginVersions["abf_freight_plugin_version"],
            'wordpress_version' => get_bloginfo('version'),
            'woocommerce_version' => $aPluginVersions["woocommerce_plugin_version"],
            'id' => get_option('wc_settings_abf_id'),
            'licence_key' => get_option('wc_settings_abf_plugin_licence_key'),
            'suspend_residential' => get_option('suspend_automatic_detection_of_residential_addresses'),
            'residential_detecion_flag' => $residential_detecion_flag,
            'sever_name' => $this->abf_parse_url($domain),
            'carrierName' => 'abf',
            'carrier_mode' => 'pro',
            'senderAddressLine' => $packages['origin']['address'],
            'senderCity' => $packages['origin']['city'],
            'senderState' => $packages['origin']['state'],
            'senderZip' => $packages['origin']['zip'],
            'senderCountryCode' => $this->getCountryCode($packages['origin']['country']),
            'sender_origin' => $packages['origin']['location'] . ": " . $packages['origin']['city'] . ", " . $packages['origin']['state'] . " " . $packages['origin']['zip'],
            'receiverCity' => $destinationAddressABF['city'],
            'receiverState' => $destinationAddressABF['state'],
            'receiverZip' => preg_replace('/\s+/', '', $destinationAddressABF['zip']),
            'receiverCountryCode' => $destinationAddressABF['country'],
            'product_name' => $product_name,
            'modifyShipmentDateTime' => $modifyShipmentDateTime,
            'OrderCutoffTime' => $orderCutoffTime,
            'shipmentOffsetDays' => $shipmentOffsetDays,
            'storeDateTime' => $storeDateTime,
            'shipmentWeekDays' => $shipmentWeekDays,
            'senderConsignee' => 'ShipAff',
            'accessorial' =>
                array(
                    'Acc_RDEL' => $abf_residential,
                    'Acc_GRD_DEL' => $abf_liftgate,
                ),
            'commdityDetails' => $lineItem,
            'en_fdo_meta_data' => $en_fdo_meta_data,
            // Nesting
            'doNesting' => $doNesting,
            'origin_markup' => (isset($packages['origin']['origin_markup'])) ? $packages['origin']['origin_markup'] : 0,
            'product_level_markup' => $product_markup_shipment,
            'liftGateAsAnOption' => $liftGateAsAnOption,
            'handlingUnitWeight' => get_option('handling_weight_abf'),
            'maxWeightPerHandlingUnit' => get_option('maximum_handling_weight_abf'),
        );

        // Liftgate exclude limit based on the liftgate weight restrictions shipping rule
        $shipping_rules_obj = new EnAbfShippingRulesAjaxReq();
        $liftGateExcludeLimit = $shipping_rules_obj->get_liftgate_exclude_limit();
        if (!empty($liftGateExcludeLimit) && $liftGateExcludeLimit > 0) {
            $post_data['liftgateExcludeLimit'] = $liftGateExcludeLimit;
        }

        $post_data['dimWeightBaseAccount'] = (get_option('abf_rates_based_on') == 'both' || get_option('abf_rates_based_on') == 'frtnmfcdim') ? '1' : '0';
        $post_data['nmfcBaseAccount'] = (get_option('abf_rates_based_on') == 'frtclsandnmfc' || get_option('abf_rates_based_on') == 'frtnmfcdim') ? '1' : '0';

        // preferred origin - 634158251
        if (is_plugin_active('preferred-origin/preferred-origin.php')) {
            $post_data = apply_filters('en_abf_update_request', $post_data);
        }

        $post_data = $this->abf_quotes_update_carrier_service($post_data);
        $post_data = apply_filters("en_woo_addons_carrier_service_quotes_request", $post_data, en_woo_plugin_abf_quotes);

        // Micro warehouse
        $post_data = apply_filters('en_request_handler', $post_data, 'abf');

        // Hazardous Material
        $hazardous_material = apply_filters('abf_quotes_quotes_plans_suscription_and_features', 'hazardous_material');

        if (!is_array($hazardous_material)) {
            (isset($packages['hazardous_material'])) ? $post_data['accessorial']['Acc_HAZ'] = 'Y' : 'N';
            (isset($packages['hazardous_material'])) ? $hazardous[] = 'H' : '';
            // FDO
            $post_data['en_fdo_meta_data'] = array_merge($post_data['en_fdo_meta_data'], $EnabffreightFdo->en_package_hazardous($packages, $en_fdo_meta_data));
        }

        // Hold At Terminal
        $hold_at_terminal = apply_filters('abf_quotes_quotes_plans_suscription_and_features', 'abf_hold_at_terminal');

        if (!is_array($hold_at_terminal)) {

            (isset($this->quote_settings['HAT_status']) && ($this->quote_settings['HAT_status'] == 'yes')) ? $post_data['holdAtTerminal'] = '1' : '';
        }

        // In-store pickup and local delivery
        $instore_pickup_local_devlivery_action = apply_filters('abf_quotes_quotes_plans_suscription_and_features', 'instore_pickup_local_devlivery');

        if (!is_array($instore_pickup_local_devlivery_action)) {

            $post_data = apply_filters('en_wd_standard_plans', $post_data, $post_data['receiverZip'], $this->en_wd_origin_array, $package_plugin);
        }

        $post_data['hazardous'] = $hazardous;

        // Standard Packaging
        // Configure standard plugin with pallet packaging addon
        $post_data = apply_filters('en_pallet_identify', $post_data);

        do_action("eniture_debug_mood", "Quotes Request (ABF)", $post_data);
        do_action("eniture_debug_mood", "Plugin Features (ABF)", get_option('eniture_plugin_11'));

        $post_data = $this->applyErrorManagement($post_data);

        return $post_data;
    }

    /**
     * @return shipment days of a week
     */
    public function abf_shipment_week_days()
    {

        $shipment_days_of_week = array();

        if (get_option('all_shipment_days_abf') == 'yes') {
            return $shipment_days_of_week;
        }
        if (get_option('monday_shipment_day_abf') == 'yes') {
            $shipment_days_of_week[] = 1;
        }
        if (get_option('tuesday_shipment_day_abf') == 'yes') {
            $shipment_days_of_week[] = 2;
        }
        if (get_option('wednesday_shipment_day_abf') == 'yes') {
            $shipment_days_of_week[] = 3;
        }
        if (get_option('thursday_shipment_day_abf') == 'yes') {
            $shipment_days_of_week[] = 4;
        }
        if (get_option('friday_shipment_day_abf') == 'yes') {
            $shipment_days_of_week[] = 5;
        }

        return $shipment_days_of_week;
    }

    /**
     * ABF freight Line Items
     * @param $packages
     * @return array
     */
    function abf_line_items($packages)
    {
        $lineItem = array();
        foreach ($packages['items'] as $item) {
            $lineItem[] = array(
                'piecesOfLineItem' => $item['productQty'],
                'lineItemClass' => $item['productClass'],
                'lineItemWeight' => $item['productWeight'],
                'lineItemWidth' => $item['productWidth'],
                'lineItemHeight' => $item['productHeight'],
                'lineItemLength' => $item['productLength'],
                'lineItemPackageCode' => 'PLT',
            );
        }
        return $lineItem;
    }

    function destinationAddressABF()
    {
        $en_order_accessories = apply_filters('en_order_accessories', []);
        if (isset($en_order_accessories) && !empty($en_order_accessories)) {
            return $en_order_accessories;
        }

        $wc_change_class = new Woo_Update_Changes_ABF();
        $freight_zipcode = (strlen(WC()->customer->get_shipping_postcode()) > 0) ? WC()->customer->get_shipping_postcode() : $wc_change_class->abf_postcode();
        $freight_state = (strlen(WC()->customer->get_shipping_state()) > 0) ? WC()->customer->get_shipping_state() : $wc_change_class->abf_state();
        $freight_country = (strlen(WC()->customer->get_shipping_country()) > 0) ? WC()->customer->get_shipping_country() : $wc_change_class->abf_country();
        $freight_city = (strlen(WC()->customer->get_shipping_city()) > 0) ? WC()->customer->get_shipping_city() : $wc_change_class->abf_city();
        return array(
            'city' => $freight_city,
            'state' => $freight_state,
            'zip' => $freight_zipcode,
            'country' => $freight_country
        );
    }

    /**
     * Check LTL Class For Product
     * @param $slug
     * @param $values
     * @return array
     * @global $woocommerce
     */
    function abf_product_with_ltl_class($slug, $values)
    {
        global $woocommerce;
        $product_in_cart = false;
        $_product = $values['data'];
        $terms = get_the_terms($_product->get_id(), 'product_shipping_class');
        if ($terms) {
            foreach ($terms as $term) {
                $_shippingclass = "";
                $_shippingclass = $term->slug;
                if ($slug === $_shippingclass) {
                    $product_in_cart[] = $_shippingclass;
                }
            }
        }
        return $product_in_cart;
    }

    /**
     * Get Nearest Address If Multiple Warehouses
     * @param $warehous_list
     * @param $receiverZipCode
     * @return array
     */
    function abf_multi_warehouse($warehous_list)
    {

        if (count($warehous_list) == 1) {
            $warehous_list = reset($warehous_list);
            return $this->abf_origin_array($warehous_list);
        }
        $abf_distance_request = new Get_abf_freight_distance();
        $accessLevel = "MultiDistance";
        $response_json = $abf_distance_request->abf_freight_address($warehous_list, $accessLevel, $this->destinationAddressABF());
        $response_json = json_decode($response_json);

        return $this->abf_origin_array($response_json->origin_with_min_dist);
    }

    /**
     * Create Origin Array
     * @param $origin
     * @return array
     */
    function abf_origin_array($origin)
    {
        // In-store pickup and local delivery
        if (has_filter("en_wd_origin_array_set")) {
            return apply_filters("en_wd_origin_array_set", $origin);
        }

        $zip = $origin->zip;
        $city = $origin->city;
        $state = $origin->state;
        $country = $origin->country;
        $location = $origin->location;
        $locationId = $origin->id;
        return array('locationId' => $locationId, 'zip' => $zip, 'city' => $city, 'state' => $state, 'location' => $location, 'country' => $country);
    }

    /**
     * Refine URL
     * @param $domain
     * @return string
     */
    function abf_parse_url($domain)
    {
        $domain = trim($domain);
        $parsed = parse_url($domain);
        if (empty($parsed['scheme'])) {
            $domain = 'http://' . ltrim($domain, '/');
        }
        $parse = parse_url($domain);
        $refinded_domain_name = $parse['host'];
        $domain_array = explode('.', $refinded_domain_name);
        if (in_array('www', $domain_array)) {
            $key = array_search('www', $domain_array);
            unset($domain_array[$key]);
            if(phpversion() < 8) {
                $refinded_domain_name = implode($domain_array, '.');
            }else {
                $refinded_domain_name = implode('.', $domain_array);
            }
        }
        return $refinded_domain_name;
    }

    /**
     * Curl Request To Get Quotes
     * @param $request_data
     * @return array
     */
    function abf_get_web_quotes($request_data, $abf_package, $loc_id)
    {
        do_action("eniture_debug_mood", "Build Query (ABF)", http_build_query($request_data));
        // check response from session
        $currentData = md5(json_encode($request_data));

        $requestFromSession = WC()->session->get('previousRequestData');
        $requestFromSession = ((is_array($requestFromSession)) && (!empty($requestFromSession))) ? $requestFromSession : array();

        if (isset($requestFromSession[$currentData]) && (!empty($requestFromSession[$currentData]))) {
            do_action("eniture_debug_mood", "Session Response (ABF)", json_decode($requestFromSession[$currentData]));
            $this->localdeliver = isset(json_decode($requestFromSession[$currentData])->InstorPickupLocalDelivery) ? json_decode($requestFromSession[$currentData])->InstorPickupLocalDelivery : '';
            return $this->parse_abf_output($requestFromSession[$currentData], $request_data, $abf_package, $loc_id);
        }

        if (is_array($request_data) && count($request_data) > 0 && !empty($request_data['senderZip'])) {
            $abf_curl_obj = new ABF_Curl_Request();
            $output = $abf_curl_obj->abf_get_curl_response(ABF_DOMAIN_HITTING_URL . '/index.php ', $request_data);

            // Set response in session
            $response = json_decode($output);
            // preferred origin
            if (is_plugin_active('preferred-origin/preferred-origin.php')) {
                if(isset($response->q->ERROR)){
                    apply_filters('en_check_response', 'ERROR');
                }
            }

            $this->localdeliver = isset($response->InstorPickupLocalDelivery) && !empty($response->InstorPickupLocalDelivery) ? $response->InstorPickupLocalDelivery : '';
            do_action("eniture_debug_mood", "Response (ABF)", $response);
            if (!isset($response->error, $response->q->ERROR) &&
                (isset($response->q, $response->q->CHARGE))) {

                if (isset($response->autoResidentialSubscriptionExpired) &&
                    ($response->autoResidentialSubscriptionExpired == 1)) {
                    $flag_api_response = "no";
                    $request_data['residential_detecion_flag'] = $flag_api_response;
                    $currentData = md5(json_encode($request_data));
                }

                $requestFromSession[$currentData] = $output;
                WC()->session->set('previousRequestData', $requestFromSession);
            }

            return $this->parse_abf_output($output, $request_data, $abf_package, $loc_id);
        }
    }

    function return_abf_localdelivery_array()
    {
        return $this->localdeliver;
    }

    /**
     * Get Shipping Array For Single Shipment
     * @param $response
     * @return array
     */
    function parse_abf_output($response, $request_data, $abf_package, $loc_id)
    {
        $accessorials = array();
        $result = json_decode($response);

        // Apply Override Rates Shipping Rules
        $abf_shipping_rules = new EnAbfShippingRulesAjaxReq();
        $abf_shipping_rules->apply_shipping_rules($abf_package, true, $result, $loc_id);

        // FDO
        $en_fdo_meta_data = (isset($request_data['en_fdo_meta_data'])) ? $request_data['en_fdo_meta_data'] : '';
        if (isset($result->debug)) {
            $en_fdo_meta_data['handling_unit_details'] = $result->debug;
        }

        // Excluded accessoarials
        $liftGateExcluded = isset($result->liftgateExcluded) && $result->liftgateExcluded == '1';
        $excluded = false;
        if ($liftGateExcluded) {
            isset($en_fdo_meta_data['accessorials']['liftgate']) ? $en_fdo_meta_data['accessorials']['liftgate'] = false : '';
            $this->quote_settings['liftgate_delivery'] = 'no';
            $this->quote_settings['liftgate_resid_delivery'] = "no";
            $this->en_accessorial_excluded = ['liftgateResidentialExcluded'];
            add_filter('en_abf_accessorial_excluded', [$this, 'en_abf_accessorial_excluded'], 10, 1);
            $en_fdo_meta_data['accessorials']['residential'] = false;
            $en_fdo_meta_data['accessorials']['liftgate'] = false;
            $excluded = true;
        }
        
        (($this->quote_settings['liftgate_delivery'] == "yes") || isset($result->liftGateStatus) && $result->liftGateStatus == 'l') ? $accessorials[] = "L" : "";
        (($this->quote_settings['residential_delivery'] == "yes") || isset($result->residentialStatus) && $result->residentialStatus == 'r') ? $accessorials[] = "R" : "";
        ($this->quote_settings['limited_access_delivery'] == "yes") ? $accessorials[] = "LA" : "";
        $this->quote_settings['limited_access_delivery_option'] = get_option('abf_limited_access_delivery_as_option');
        (is_array($request_data['hazardous']) && !empty($request_data['hazardous'])) ? $accessorials[] = "H" : "";

        //limited access
        in_array('LA', $accessorials) ? $en_fdo_meta_data['accessorials']['limitedaccess'] = true : '';

        // Standard packaging
        $standard_packaging = isset($result->standardPackagingData) ? $result->standardPackagingData : [];

        $label_sufex_arr = $this->filter_label_sufex_array_abf_quotes($result);
        if ($this->quote_settings['liftgate_delivery'] == "yes") {
            $suff_key = array_search('L', $label_sufex_arr);
            unset($label_sufex_arr[$suff_key]);
        }

        // Removes lift gate label suffix in case of always quote lift gate delivery option
        if ($this->quote_settings['liftgate_delivery'] == "yes") {
            if (is_array($label_sufex_arr) && in_array('L', $label_sufex_arr)) {
                $lg_key = array_search('L', $label_sufex_arr);
                unset($label_sufex_arr[$lg_key]);
            }
        }

        if (isset($result->q) && empty($result->q->ERROR)) {

            $meta_data = $this->get_mata_data($request_data, $result->q, $accessorials, $standard_packaging);

            if (is_plugin_active('en-dynamic-discount-toggle/en-dynamic-discount-toggle.php') && isset($result->q->CHARGE)) {
                $result = apply_filters('en_dynamic_discount_apply_discount_settings', $result);
            }

            $price = (isset($result->q->CHARGE)) ? $result->q->CHARGE : 0;
            $surcharges = (isset($result->q->INCLUDEDCHARGES)) ? $result->q->INCLUDEDCHARGES : '';
            $transit_time = (isset($result->q->ADVERTISEDTRANSIT) && $result->q->ADVERTISEDTRANSIT != '') ? explode(' ', $result->q->ADVERTISEDTRANSIT)[0] : '';

            // Add limited access delivery fee in quote price and surcharge in rates surcharges array
            $limited_access_active = $this->quote_settings['limited_access_delivery_option'] == 'yes' || $this->quote_settings['limited_access_delivery'] == 'yes';
            if ($limited_access_active && !in_array('R', $label_sufex_arr)) {
                $price = $this->addLimitedAccessDelFee($price);
                $surcharges = isset($surcharges) ? $surcharges : [];
                
                $surcharges = $this->addLimitedAccessDelInSurcharges($surcharges);
            } else {
                unset($label_sufex_arr['LA']);
                unset($accessorials['LA']);
                $en_fdo_meta_data['accessorials']['limitedaccess'] = '';
            }
            
            $simple_quotes = [];

            $quotes = array(
                'id' => 'abf',
                'plugin_name' => 'abf',
                'plugin_type' => 'ltl',
                'owned_by' => 'eniture',

                'cost' => $price,
                'label' => (strlen($this->quote_settings['label']) > 0) ? $this->quote_settings['label'] : 'Freight',
                'transit_time' => $transit_time,
                'delivery_estimates' => $result->q->totalTransitTimeInDays,
                'deliveryTimestamp' => isset($result->q->ADVERTISEDDUEDATE) && is_string($result->q->ADVERTISEDDUEDATE) ? $result->q->ADVERTISEDDUEDATE : '',
                'label_sfx_arr' => $label_sufex_arr,
                'surcharges' => (isset($surcharges)) ? $this->update_parse_abf_quotes_output($surcharges) : 0,
                'meta_data' => $meta_data,
                'markup' => $this->quote_settings['handling_fee'],
                'origin_markup' => $request_data['origin_markup'],
                'product_level_markup' => $request_data['product_level_markup'],
            );

            // Micro warehouse
            $quotes = array_merge($quotes, $meta_data);
            $quotes = apply_filters('add_warehouse_appliance_handling_fee', $quotes, $request_data);
            // FDO
            $en_fdo_meta_data['rate'] = $quotes;
            if (isset($en_fdo_meta_data['rate']['meta_data'])) {
                unset($en_fdo_meta_data['rate']['meta_data']);
            }
            $en_fdo_meta_data['quote_settings'] = $this->quote_settings;
            $quotes['meta_data']['en_fdo_meta_data'] = $en_fdo_meta_data;

            // To Identify Auto Detect Residential address detected Or Not
            $quotes = apply_filters("en_woo_addons_web_quotes", $quotes, en_woo_plugin_abf_quotes);
            $label_sufex = (isset($quotes['label_sfx_arr'])) ? $quotes['label_sfx_arr'] : array();
            $label_sufex = $this->label_R_freight_view($label_sufex);
            $quotes['label_sufex'] = $label_sufex;

            //FDO
            in_array('R', $accessorials) ? $quotes['meta_data']['en_fdo_meta_data']['accessorials']['residential'] = true : '';
            in_array('L', $accessorials) ? $quotes['meta_data']['en_fdo_meta_data']['accessorials']['liftgate'] = true : '';

            //  When Hold At Terminal Enabled
            $hold_at_terminal = apply_filters('abf_quotes_quotes_plans_suscription_and_features', 'hold_at_terminal');
            if (isset($result->holdAtTerminalResponse, $result->holdAtTerminalResponse->totalNetCharge) && !is_array($hold_at_terminal) && $this->quote_settings['HAT_status'] == 'yes' || (isset($result->holdAtTerminalResponse->severity) && $result->holdAtTerminalResponse->severity != 'ERROR')) {
                $hold_at_terminal_fee = (isset($result->holdAtTerminalResponse->totalNetCharge)) ? $result->holdAtTerminalResponse->totalNetCharge : 0;

                $ABF_Freight_Shipping = new ABF_Freight_Shipping();
                
                // Product level markup
                if ( !empty($request_data['product_level_markup'])) {
                    $hold_at_terminal_fee = $ABF_Freight_Shipping->add_handling_fee($hold_at_terminal_fee, $request_data['product_level_markup']);
                }

                // Origin level markup
                if ( !empty($request_data['origin_markup'])) {
                    $hold_at_terminal_fee = $ABF_Freight_Shipping->add_handling_fee($hold_at_terminal_fee, $request_data['origin_markup']);
                }

                if (isset($this->quote_settings['HAT_fee']) && (strlen($this->quote_settings['HAT_fee']) > 0)) {
                    $hold_at_terminal_fee = $ABF_Freight_Shipping->add_handling_fee($hold_at_terminal_fee, $this->quote_settings['HAT_fee']);
                }

                $meta_data['service_type'] = 'FreightHAT_Abf';
                $_accessorials = (in_array('H', $accessorials)) ? array('HAT', 'H') : array('HAT');

                $meta_data['accessorials'] = json_encode($_accessorials);
                $meta_data['sender_origin'] = $request_data['sender_origin'];
                $meta_data['product_name'] = json_encode($request_data['product_name']);
                $meta_data['address'] = (isset($result->holdAtTerminalResponse->address)) ? json_encode($result->holdAtTerminalResponse->address) : array();
                $meta_data['_address'] = (isset($result->holdAtTerminalResponse->address, $result->holdAtTerminalResponse->custServicePhoneNbr, $result->holdAtTerminalResponse->distance)) ? $this->get_address_terminal($result->holdAtTerminalResponse->address, $result->holdAtTerminalResponse->custServicePhoneNbr, $result->holdAtTerminalResponse->distance) : '';
                $meta_data['quote_id'] = isset($result->holdAtTerminalResponse->QUOTEID) ? $result->holdAtTerminalResponse->QUOTEID : '';
                // Standard packaging
                $meta_data['standard_packaging'] = wp_json_encode($standard_packaging);

                $hold_at_terminal_resp = (isset($result->holdAtTerminalResponse)) ? $result->holdAtTerminalResponse : [];

                $transit = (isset($result->holdAtTerminalResponse->transitDays) && $result->holdAtTerminalResponse->transitDays != '') ? explode(' ', $result->holdAtTerminalResponse->transitDays)[0] : '';
                $hat_quotes = array(
                    'id' => $meta_data['service_type'],
                    'cost' => $hold_at_terminal_fee,
                    'plugin_name' => 'abf',
                    'plugin_type' => 'ltl',
                    'owned_by' => 'eniture',
                    'label' => (strlen($this->quote_settings['label']) > 0) ? $this->quote_settings['label'] : 'Freight',
                    'address' => $meta_data['address'],
                    '_address' => $meta_data['_address'],
                    'transit_time' => $transit,
                    'delivery_estimates' => $result->q->totalTransitTimeInDays,
                    'deliveryTimestamp' => isset($result->q->ADVERTISEDDUEDATE) && is_string($result->q->ADVERTISEDDUEDATE) ? $result->q->ADVERTISEDDUEDATE : '',
                    'label_sfx_arr' => $label_sufex_arr,
                    'hat_append_label' => ' with hold at terminal',
                    '_hat_append_label' => $meta_data['_address'],
                    'meta_data' => $meta_data,
                    'markup' => $this->quote_settings['handling_fee'],
                    'origin_markup' => $request_data['origin_markup'],
                    'product_level_markup' => $request_data['product_level_markup'],
                );

                // FDO
                $en_fdo_meta_data['rate'] = $hat_quotes;
                if (isset($en_fdo_meta_data['rate']['meta_data'])) {
                    unset($en_fdo_meta_data['rate']['meta_data']);
                }
                $en_fdo_meta_data['quote_settings'] = $this->quote_settings;
                $en_fdo_meta_data['holdatterminal'] = $hold_at_terminal_resp;
                $hat_quotes['meta_data']['en_fdo_meta_data'] = $en_fdo_meta_data;
                $accessorials_hat = [
                    'holdatterminal' => true,
                    'residential' => false,
                    'liftgate' => false,
                ];
                if (isset($hat_quotes['meta_data']['en_fdo_meta_data']['accessorials'])) {
                    $hat_quotes['meta_data']['en_fdo_meta_data']['accessorials'] = array_merge($hat_quotes['meta_data']['en_fdo_meta_data']['accessorials'], $accessorials_hat);
                } else {
                    $hat_quotes['meta_data']['en_fdo_meta_data']['accessorials']['holdatterminal'] = true;
                }

                // -100% Fee is invalid
                if (isset($this->quote_settings['HAT_fee']) &&
                    ($this->quote_settings['HAT_fee'] == "-100%")) {
                    $hat_quotes = array();
                }
            }
        }

        if (isset($result->quotesWithoutLiftgate) && empty($result->quotesWithoutLiftgate->ERROR)) {

            if(!empty($quotes)){
                $quotes['append_label'] = " with lift gate delivery ";
            }

            if(in_array('L', $accessorials)){
                $lg_key = array_search('L', $accessorials);
                unset($accessorials[$lg_key]);
            }

            if(is_array($label_sufex_arr) && in_array('L', $label_sufex_arr)){
                $lg_key = array_search('L', $label_sufex_arr);
                unset($label_sufex_arr[$lg_key]);
            }

            if(in_array('LA', $accessorials)){
                $la_key = array_search('LA', $accessorials);
                unset($accessorials[$la_key]);
            }

            if(is_array($label_sufex_arr) && in_array('LA', $label_sufex_arr)){
                $la_key = array_search('LA', $label_sufex_arr);
                unset($label_sufex_arr[$la_key]);
            }

            $meta_data = $this->get_mata_data($request_data, $result->quotesWithoutLiftgate, $accessorials, $standard_packaging);

            if (is_plugin_active('en-dynamic-discount-toggle/en-dynamic-discount-toggle.php') && isset($result->q->CHARGE)) {
                $result = apply_filters('en_dynamic_discount_apply_discount_settings', $result);
            }

            $price = (isset($result->quotesWithoutLiftgate->CHARGE)) ? $result->quotesWithoutLiftgate->CHARGE : 0;
            $surcharges = (isset($result->quotesWithoutLiftgate->INCLUDEDCHARGES)) ? $result->quotesWithoutLiftgate->INCLUDEDCHARGES : '';
            $transit_time = (isset($result->quotesWithoutLiftgate->ADVERTISEDTRANSIT) && $result->quotesWithoutLiftgate->ADVERTISEDTRANSIT != '') ? explode(' ', $result->quotesWithoutLiftgate->ADVERTISEDTRANSIT)[0] : '';

            // Add limited access delivery fee in quote price and surcharge in rates surcharges array
            if (($this->quote_settings['limited_access_delivery_option'] == 'yes' || $this->quote_settings['limited_access_delivery'] == 'yes') && !in_array('R', $label_sufex_arr)) {
                $price = $this->addLimitedAccessDelFee($price);
                $surcharges = isset($surcharges) ? $surcharges : [];
                
                $surcharges = $this->addLimitedAccessDelInSurcharges($surcharges);
            } else {
                unset($label_sufex_arr['LA']);
                unset($accessorials['LA']);
                $en_fdo_meta_data['accessorials']['limitedaccess'] = '';
            }

            $simple_quotes = array(
                'id' => 'abf_with_out_LG',
                'plugin_name' => 'abf',
                'plugin_type' => 'ltl',
                'owned_by' => 'eniture',
                'cost' => $price,
                'label' => (strlen($this->quote_settings['label']) > 0) ? $this->quote_settings['label'] : 'Freight',
                'transit_time' => $transit_time,
                'delivery_estimates' => $result->quotesWithoutLiftgate->totalTransitTimeInDays,
                'deliveryTimestamp' => isset($result->quotesWithoutLiftgate->ADVERTISEDDUEDATE) && is_string($result->quotesWithoutLiftgate->ADVERTISEDDUEDATE) ? $result->quotesWithoutLiftgate->ADVERTISEDDUEDATE : '',
                'label_sfx_arr' => $label_sufex_arr,
                'surcharges' => (isset($surcharges)) ? $this->update_parse_abf_quotes_output($surcharges) : 0,
                'meta_data' => $meta_data,
                'markup' => $this->quote_settings['handling_fee'],
                'origin_markup' => $request_data['origin_markup'],
                'product_level_markup' => $request_data['product_level_markup'],
            );

            // Micro warehouse
            $simple_quotes = array_merge($simple_quotes, $meta_data);
            $simple_quotes = apply_filters('add_warehouse_appliance_handling_fee', $simple_quotes, $request_data);

            // FDO
            $en_fdo_meta_data['rate'] = $simple_quotes;
            if (isset($en_fdo_meta_data['rate']['meta_data'])) {
                unset($en_fdo_meta_data['rate']['meta_data']);
            }
            $en_fdo_meta_data['quote_settings'] = $this->quote_settings;
            $simple_quotes['meta_data']['en_fdo_meta_data'] = $en_fdo_meta_data;

            // To Identify Auto Detect Residential address detected Or Not
            $simple_quotes = apply_filters("en_woo_addons_web_quotes", $simple_quotes, en_woo_plugin_abf_quotes);
            $label_sufex = (isset($simple_quotes['label_sfx_arr'])) ? $simple_quotes['label_sfx_arr'] : array();
            $label_sufex = $this->label_R_freight_view($label_sufex);
            $simple_quotes['label_sufex'] = $label_sufex;

            //FDO
            in_array('R', $label_sufex_arr) ? $simple_quotes['meta_data']['en_fdo_meta_data']['accessorials']['residential'] = true : '';
        } else if ($excluded) {
            $simple_quotes = $quotes;
        }

        // When Limited Access As An Option Enabled
        $limited_access_quotes = [];
        if ($this->quote_settings['limited_access_delivery_option'] == 'yes' && !in_array('R', $label_sufex_arr)) {
            $limited_access_quotes = !empty($simple_quotes) ? $simple_quotes : (!empty($quotes) ? $quotes : []);

            $limited_access_quotes['label_sfx_arr'] = $limited_access_quotes['label_sufex'] = ['LA'];
            $limited_access_quotes['id'] = "abf_with_LA";

            $lg_fee = (isset($limited_access_quotes['surcharges']['liftgateFee'])) ? $limited_access_quotes['surcharges']['liftgateFee'] : 0;
            $la_fee = (isset($limited_access_quotes['surcharges']['laFee']['Amount'])) ? $limited_access_quotes['surcharges']['laFee']['Amount'] : 0;
            $limited_access_quotes['cost'] -= floatval($lg_fee); 

            // when lift gate as option is enabled
            if (!empty($simple_quotes) && isset($simple_quotes['cost'])) {
                $simple_quotes['cost'] -= floatval($la_fee);
                $quotes['cost'] -= floatval($la_fee);
            } else {
                $quotes['cost'] -= floatval($la_fee);
            }

            $limited_access_quotes['append_label'] = " with limited access delivery ";

            // FDO
            $limited_access_quotes['meta_data']['en_fdo_meta_data']['accessorials']['liftgate'] = false;
            $limited_access_quotes['meta_data']['en_fdo_meta_data']['accessorials']['residential'] = false;
            $limited_access_quotes['meta_data']['en_fdo_meta_data']['accessorials']['limitedaccess'] = true;
            $limited_access_quotes['meta_data']['en_fdo_meta_data']['rate']['cost'] = $limited_access_quotes['cost'];
            $limited_access_quotes['meta_data']['en_fdo_meta_data']['rate']['label_sfx_arr'] = $limited_access_quotes['meta_data']['en_fdo_meta_data']['rate']['label_sufex_arr'] = ['LA'];
            $limited_access_quotes['meta_data']['en_fdo_meta_data']['rate']['append_label'] = " with limited access delivery ";

            if (!empty($simple_quotes)) {
                if (isset($simple_quotes['meta_data']['min_quotes'])) {
                    unset($simple_quotes['meta_data']['min_quotes']);
                }

                $simple_quotes = apply_filters('add_warehouse_appliance_handling_fee', $simple_quotes, $request_data);

                // FDO
                if (isset($simple_quotes['meta_data']['en_fdo_meta_data']['rate']['cost'])) {
                    $simple_quotes['meta_data']['en_fdo_meta_data']['rate']['cost'] = $simple_quotes['cost'];
                }
            }
        }

        (!empty($simple_quotes)) ? $quotes['simple_quotes'] = $simple_quotes : "";
        (!empty($limited_access_quotes)) ? $quotes['limited_access_quotes'] = $limited_access_quotes : "";
        (!empty($hat_quotes)) ? $quotes['hold_at_terminal_quotes'] = $hat_quotes : "";

        return $quotes;
    }

    /**
     * Get Terminal Address From Response
     * @param object $address
     * @param string $phone_nbr
     * @param object $distance
     * @return string
     */
    public function get_address_terminal($address, $phone_nbr, $distance)
    {
        $address_terminal = '';

        $address_terminal .= (isset($distance->text)) ? ' | ' . $distance->text : '';
        $address_terminal .= (isset($address->DESTTERMADDRESS)) ? ' | ' . $address->DESTTERMADDRESS : '';
        $address_terminal .= (isset($address->DESTTERMCITY)) ? ' ' . $address->DESTTERMCITY : '';
        $address_terminal .= (isset($address->DESTTERMSTATE)) ? ' ' . $address->DESTTERMSTATE : '';
        $address_terminal .= (isset($address->DESTTERMZIP)) ? ' ' . $address->DESTTERMZIP : '';
        $address_terminal .= (strlen($phone_nbr) > 0) ? ' | T: ' . $phone_nbr : '';
        return $address_terminal;
    }

    /**
     * check "R" in array
     * @param array type $label_sufex
     * @return array type
     */
    public function label_R_freight_view($label_sufex)
    {
        if ($this->quote_settings['residential_delivery'] == 'yes' && (in_array("R", $label_sufex))) {
            $label_sufex = array_flip($label_sufex);
            unset($label_sufex['R']);
            $label_sufex = array_keys($label_sufex);
        }

        return $label_sufex;
    }

    /**
     * Change Country Code
     * @param $country
     * @return string
     */
    function getCountryCode($country)
    {
        $countryCode = $country;
        $country = strtolower($country);
        switch ($country) {
            case 'usa':
                $countryCode = 'US';
                break;
            case 'can':
                $countryCode = 'CA';
                break;
            case 'cn':
                $countryCode = 'CA';
                break;
            default:
                $countryCode = strtoupper($country);
                break;
        }

        return $countryCode;
    }

    /**
     * Return woo-commerce and ABF version
     * @return int
     */
    function abf_wc_version_number()
    {
        if (!function_exists('get_plugins'))
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');

        $plugin_folder = get_plugins('/' . 'woocommerce');
        $plugin_file = 'woocommerce.php';
        $abf_plugin_folder = get_plugins('/' . 'ltl-freight-quotes-abf-freight-edition');
        $abf_plugin_file = 'ltl-freight-quotes-abf-freight-edition.php';
        $wc_plugin = (isset($plugin_folder[$plugin_file]['Version'])) ? $plugin_folder[$plugin_file]['Version'] : "";
        $abf_plugin = (isset($abf_plugin_folder[$abf_plugin_file]['Version'])) ? $abf_plugin_folder[$abf_plugin_file]['Version'] : "";

        $pluginVersions = array(
            "woocommerce_plugin_version" => $wc_plugin,
            "abf_freight_plugin_version" => $abf_plugin
        );

        return $pluginVersions;
    }

    /**
     * This function creates and returns meta data array for ODW
     */
    public function get_mata_data($request_data, $result, $accessorials, $standard_packaging){
        $meta_data = [];
        $meta_data['service_type'] = 'ABF_Freight';
        $meta_data['accessorials'] = json_encode($accessorials);
        $meta_data['sender_origin'] = $request_data['sender_origin'];
        $meta_data['product_name'] = json_encode($request_data['product_name']);
        $meta_data['quote_id'] = isset($result->QUOTEID) ? $result->QUOTEID : '';
        // Standard Packaging
        $meta_data['standard_packaging'] = wp_json_encode($standard_packaging);

        // Micro warehouse
        $meta_data['quote_settings'] = json_encode($this->quote_settings);

        return $meta_data;
    }

    function addLimitedAccessDelFee($charges) 
    {
        $is_limited_access_active = get_option('abf_limited_access_delivery') == 'yes' || get_option('abf_limited_access_delivery_as_option') == 'yes';
        $limited_access_fee = !empty(get_option('abf_limited_access_delivery_fee')) ? get_option('abf_limited_access_delivery_fee') : 0;

        if ($is_limited_access_active) {
            $charges = $charges + floatval($limited_access_fee);
        }

        return $charges;
    }

    function addLimitedAccessDelInSurcharges($surcharges)
    {
        $surcharges->laFee = [
            'Type' => 'LA',
            'Title' => 'Limited Access Delivery',
            'Amount' => get_option('abf_limited_access_delivery_fee')
        ];

        return $surcharges;
    }

    function applyErrorManagement($quotes_request)
    {
        // error management will be applied only for more than 1 product
        if (empty($quotes_request) || empty($quotes_request['commdityDetails']) || (!empty($quotes_request['commdityDetails']) && count($quotes_request['commdityDetails']) < 2)) return $quotes_request;

        $error_option = get_option('error_management_settings_abf');
        $dont_quote_shipping = false;

        foreach ($quotes_request['commdityDetails'] as $key => $product) {
            $empty_dims_check = empty($product['lineItemWidth']) || empty($product['lineItemHeight']) || empty($product['lineItemLength']);
            $empty_shipping_class_check = empty($product['lineItemClass']);
            $weight = $product['lineItemWeight'];

            if (empty($weight) || ($empty_dims_check && $empty_shipping_class_check)) {
                if ($error_option == 'dont_quote_shipping') {
                    $dont_quote_shipping = true;
                    break;
                } else {
                    unset($quotes_request['commdityDetails'][$key]);
                    $quotes_request['error_management'] = $error_option;
                }
            }
        }
        
        // error management will be applied for all products in case of dont quote shipping option
        if ($dont_quote_shipping) {
            $quotes_request['commdityDetails'] = [];
            $quotes_request['error_management'] = $error_option;
        }

        return $quotes_request;
    }

    /**
     * Accessoarials excluded
     * @param $excluded
     * @return array
    */
    function en_abf_accessorial_excluded($excluded)
    {
        return array_merge($excluded, $this->en_accessorial_excluded);
    }
}
