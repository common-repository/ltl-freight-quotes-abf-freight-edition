<?php

/**
 * ABF Quote Settings Page WC Settings Tab
 * @package     Woocommerce ABF Edition
 * @author      <https://eniture.com/>
 * @copyright   Copyright (c) 2017, Eniture
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class For Quote Settings Tab
 */
class ABF_Quote_Settings {

    /**
     * Quote Setting Fields
     * @return array
     */
    function abf_quote_settings_tab() {

        $disable_hold_at_terminal = "";
        $hold_at_terminal_package_required = "";
        $abf_disable_cutOffTime_shipDateOffset = "";
        $abf_cutOffTime_shipDateOffset_package_required = "";

        $action_hold_at_terminal = apply_filters('abf_quotes_quotes_plans_suscription_and_features', 'hold_at_terminal');
        if (is_array($action_hold_at_terminal)) {
            $disable_hold_at_terminal = "disabled_me";
            $hold_at_terminal_package_required = apply_filters('abf_quotes_plans_notification_link', $action_hold_at_terminal);
        }
        //      Check the cutt of time & offset days plans for disable input fields
        $abf_action_cutOffTime_shipDateOffset = apply_filters('abf_quotes_quotes_plans_suscription_and_features', 'abf_cutOffTime_shipDateOffset');
        if (is_array($abf_action_cutOffTime_shipDateOffset)) {
            $abf_disable_cutOffTime_shipDateOffset = "disabled_me";
            $abf_cutOffTime_shipDateOffset_package_required = apply_filters('abf_quotes_plans_notification_link', $abf_action_cutOffTime_shipDateOffset);
        }
        
        $ltl_enable = get_option('en_plugins_return_LTL_quotes');
        $weight_threshold_class = $ltl_enable == 'yes' ? 'show_en_weight_threshold_lfq' : 'hide_en_weight_threshold_lfq';
        $weight_threshold = get_option('en_weight_threshold_lfq');
        $weight_threshold = isset($weight_threshold) && $weight_threshold > 0 ? $weight_threshold : 150;

        echo '<div class="quote_section_class_abf">';
        $settings = array(
            'section_title_quote' => array(
                'title' => __('Quote Settings ', 'woocommerce-settings-abf_quotes'),
                'type' => 'title',
                'desc' => '',
                'id' => 'wc_settings_abf_section_title_quote'
            ),
            'label_as_abf' => array(
                'name' => __('Label As ', 'woocommerce-settings-abf_quotes'),
                'type' => 'text',
                'desc' => 'What the user sees during checkout, e.g. "Freight". Leave blank to display the "Freight".',
                'id' => 'wc_settings_abf_label_as'
            ),
            'price_sort_abf' => array(
                'name' => __("Don't sort shipping methods by price  ", 'woocommerce-settings-abf_quotes'),
                'type' => 'checkbox',
                'desc' => 'By default, the plugin will sort all shipping methods by price in ascending order.',
                'id' => 'shipping_methods_do_not_sort_by_price'
            ),
//**            Start Delivery Estimate Options
            'service_abf_estimates_title' => array(
                'name' => __('Delivery Estimate Options ', 'woocommerce-settings-en_woo_addons_packages_quotes'),
                'type' => 'text',
                'desc' => '',
                'id' => 'service_abf_estimates_title'
            ),
            'abf_show_delivery_estimates_options_radio' => array(
                'name' => __("", 'woocommerce-settings-abf'),
                'type' => 'radio',
                'default' => 'dont_show_estimates',
                'options' => array(
                    'dont_show_estimates' => __("Don't display delivery estimates.", 'woocommerce'),
                    'delivery_days' => __("Display estimated number of days until delivery.", 'woocommerce'),
                    'delivery_date' => __("Display estimated delivery date.", 'woocommerce'),
                ),
                'id' => 'abf_delivery_estimates',
                'class' => 'abf_dont_show_estimate_option',
            ),
            //** End Delivery Estimate Options
            //**Start: Cut Off Time & Ship Date Offset
            'cutOffTime_shipDateOffset_abf_freight' => array(
                'name' => __('Cut Off Time & Ship Date Offset ', 'woocommerce-settings-en_woo_addons_packages_quotes'),
                'type' => 'text',
                'class' => 'hidden',
                'desc' => $abf_cutOffTime_shipDateOffset_package_required,
                'id' => 'abf_freight_cutOffTime_shipDateOffset'
            ),
            'orderCutoffTime_abf_freight' => array(
                'name' => __('Order Cut Off Time ', 'woocommerce-settings-abf_freight_freight_orderCutoffTime'),
                'type' => 'text',
                'placeholder' => '-- : -- --',
                'desc' => 'Enter the cut off time (e.g. 2.00) for the orders. Orders placed after this time will be quoted as shipping the next business day.',
                'id' => 'abf_freight_orderCutoffTime',
                'class' => $abf_disable_cutOffTime_shipDateOffset,
            ),
            'shipmentOffsetDays_abf_freight' => array(
                'name' => __('Fullfillment Offset Days ', 'woocommerce-settings-abf_freight_shipmentOffsetDays'),
                'type' => 'text',
                'desc' => 'The number of days the ship date needs to be moved to allow the processing of the order.',
                'placeholder' => 'Fullfillment Offset Days, e.g. 2',
                'id' => 'abf_freight_shipmentOffsetDays',
                'class' => $abf_disable_cutOffTime_shipDateOffset,
            ),
            'all_shipment_days_abf' => array(
                'name' => __("What days do you ship orders?", 'woocommerce-settings-abf_quotes'),
                'type' => 'checkbox',
                'desc' => 'Select All',
                'class' => "all_shipment_days_abf $abf_disable_cutOffTime_shipDateOffset",
                'id' => 'all_shipment_days_abf'
            ),
            'monday_shipment_day_abf' => array(
                'name' => __("", 'woocommerce-settings-abf_quotes'),
                'type' => 'checkbox',
                'desc' => 'Monday',
                'class' => "abf_shipment_day $abf_disable_cutOffTime_shipDateOffset",
                'id' => 'monday_shipment_day_abf'
            ),
            'tuesday_shipment_day_abf' => array(
                'name' => __("", 'woocommerce-settings-abf_quotes'),
                'type' => 'checkbox',
                'desc' => 'Tuesday',
                'class' => "abf_shipment_day $abf_disable_cutOffTime_shipDateOffset",
                'id' => 'tuesday_shipment_day_abf'
            ),
            'wednesday_shipment_day_abf' => array(
                'name' => __("", 'woocommerce-settings-abf_quotes'),
                'type' => 'checkbox',
                'desc' => 'Wednesday',
                'class' => "abf_shipment_day $abf_disable_cutOffTime_shipDateOffset",
                'id' => 'wednesday_shipment_day_abf'
            ),
            'thursday_shipment_day_abf' => array(
                'name' => __("", 'woocommerce-settings-abf_quotes'),
                'type' => 'checkbox',
                'desc' => 'Thursday',
                'class' => "abf_shipment_day $abf_disable_cutOffTime_shipDateOffset",
                'id' => 'thursday_shipment_day_abf'
            ),
            'friday_shipment_day_abf' => array(
                'name' => __("", 'woocommerce-settings-abf_quotes'),
                'type' => 'checkbox',
                'desc' => 'Friday',
                'class' => "abf_shipment_day $abf_disable_cutOffTime_shipDateOffset",
                'id' => 'friday_shipment_day_abf'
            ),
            'abf_show_delivery_estimates' => array(
                'title' => __('', 'woocommerce'),
                'name' => __('', 'woocommerce-settings-abf_quotes'),
                'desc' => '',
                'id' => 'abf_show_delivery_estimates',
                'css' => '',
                'default' => '',
                'type' => 'title',
            ),
            //**End: Cut Off Time & Ship Date Offset
            'residential_delivery_options_label' => array(
                'name' => __('Residential Delivery', 'woocommerce-settings-wwe_small_packages_quotes'),
                'type' => 'text',
                'class' => 'hidden',
                'id' => 'residential_delivery_options_label'
            ),
            'accessorial_residential_delivery_abf' => array(
                'name' => __('', 'woocommerce-settings-abf_quotes'),
                'type' => 'checkbox',
                'desc' => 'Always quote as residential delivery',
                'id' => 'wc_settings_abf_residential',
                'class' => 'accessorial_service',
            ),
//          Auto-detect residential addresses notification
            'avaibility_auto_residential' => array(
                'name' => __('Auto-detect residential addresses', 'woocommerce-settings-abf_small_packages_quotes'),
                'type' => 'text',
                'class' => 'hidden',
                'desc' => "Click <a target='_blank' href='https://eniture.com/woocommerce-residential-address-detection/'>here</a> to add the Residential Address Detection module. (<a target='_blank' href='https://eniture.com/woocommerce-residential-address-detection/#documentation'>Learn more</a>)",
                'id' => 'avaibility_auto_residential'
            ),
            'liftgate_delivery_options_label' => array(
                'name' => __('Lift Gate Delivery ', 'woocommerce-settings-en_woo_addons_packages_quotes'),
                'type' => 'text',
                'class' => 'hidden',
                'id' => 'liftgate_delivery_options_label'
            ),
            'accessorial_liftgate_delivery_abf' => array(
                'name' => __('', 'woocommerce-settings-abf_quotes'),
                'type' => 'checkbox',
                'desc' => 'Always quote lift gate delivery',
                'id' => 'wc_settings_abf_liftgate',
                'class' => 'accessorial_service checkbox_fr_add',
            ),
            'abf_quotes_liftgate_delivery_as_option' => array(
                'name' => __('', 'woocommerce-settings-abf_quotes'),
                'type' => 'checkbox',
                'desc' => __('Offer lift gate delivery as an option', 'woocommerce-settings-abf_freight'),
                'id' => 'abf_quotes_liftgate_delivery_as_option',
                'class' => 'accessorial_service checkbox_fr_add',
            ),
//          Use my liftgate notification
            'avaibility_lift_gate' => array(
                'name' => __('Always include lift gate delivery when a residential address is detected', 'woocommerce-settings-wwe_small_packages_quotes'),
                'type' => 'text',
                'class' => 'hidden',
                'desc' => "Click <a target='_blank' href='https://eniture.com/woocommerce-residential-address-detection/'>here</a> to add the Residential Address Detection module. (<a target='_blank' href='https://eniture.com/woocommerce-residential-address-detection/#documentation'>Learn more</a>)",
                'id' => 'avaibility_lift_gate'
            ),
            
            // Limited access delivery
            'abf_limited_access_delivery_label' => array(
                'name' => __("Limited Access Delivery", 'woocommerce-settings-abf_quetes'),
                'type' => 'text',
                'class' => 'hidden',
                'desc' => '',
                'id' => 'abf_limited_access_delivery_label'
            ),
            'abf_limited_access_delivery' => array(
                'name' => __("", 'woocommerce-settings-abf_quetes'),
                'type' => 'checkbox',
                'desc' => 'Always quote limited access delivery',
                'id' => 'abf_limited_access_delivery',
                'class' => "limited_access_add",
            ),
            'abf_limited_access_delivery_as_option' => array(
                'name' => __("", 'woocommerce-settings-abf_quetes'),
                'type' => 'checkbox',
                'desc' => 'Offer limited access delivery as an option',
                'id' => 'abf_limited_access_delivery_as_option',
                'class' => "limited_access_add ",
            ),
            'abf_limited_access_delivery_fee' => array(
                'name' => __("Limited access delivery fee", 'woocommerce-settings-abf_quetes'),
                'type' => 'text',
                'desc' => "Limited access delivery fees may differ depending on the type of facility. The plugin cannot prompt for the type of
                facility, so enter the amount you'd like to collect regardless of the facility type.",
                'id' => 'abf_limited_access_delivery_fee',
                'class' => "",
            ),

//          Start Hot At Terminal
            'abf_hold_at_terminal_checkbox_status' => array(
                'name' => __('Hold At Terminal', 'woocommerce-settings-abf_small'),
                'type' => 'checkbox',
                'desc' => 'Offer Hold At Terminal as an option ' . $hold_at_terminal_package_required,
                'class' => $disable_hold_at_terminal,
                'id' => 'abf_hold_at_terminal_checkbox_status',
            ),
            'abf_hold_at_terminal_fee' => array(
                'name' => __('', 'ground-transit-settings-ground_transit'),
                'type' => 'text',
                'desc' => 'Adjust the price of the Hold At Terminal option. Enter an amount, e.g. 3.75, or a percentage, e.g. 5%.  Leave blank to use the price returned by the carrier.',
                'class' => $disable_hold_at_terminal,
                'id' => 'abf_hold_at_terminal_fee'
            ),
            // End Hot At Terminal
            // Handling Weight
            'label_handling_unit_abf' => array(
                'name' => __('Handling Unit ', 'woocommerce-settings-fedex_freight'),
                'type' => 'text',
                'class' => 'hidden',
                'id' => 'label_handling_unit_abf'
            ),
            'handling_weight_abf' => array(
                'name' => __('Weight of Handling Unit  ', 'woocommerce-settings-fedex_freight'),
                'type' => 'text',
                'desc' => 'Enter in pounds the weight of your pallet, skid, crate or other type of handling unit.',
                'id' => 'handling_weight_abf'
            ),
            // max Handling Weight
            'maximum_handling_weight_abf' => array(
                'name' => __('Maximum Weight per Handling Unit  ', 'woocommerce-settings-fedex_freight'),
                'type' => 'text',
                'desc' => 'Enter in pounds the maximum weight that can be placed on the handling unit.',
                'id' => 'maximum_handling_weight_abf'
            ),
            'handing_fee_markup_abf' => array(
                'name' => __('Handling Fee / Markup ', 'woocommerce-settings-abf_quotes'),
                'type' => 'text',
                'desc' => 'Amount excluding tax. Enter an amount, e.g 3.75, or a percentage, e.g, 5%. Leave blank to disable.',
                'id' => 'wc_settings_abf_handling_fee'
            ),
            'en_abf_enable_logs' => array(
                'name' => __("Enable Logs  ", 'woocommerce-settings-fedex_ltl_quotes'),
                'type' => 'checkbox',
                'desc' => 'When checked, the Logs page will contain up to 25 of the most recent transactions.',
                'id' => 'en_abf_enable_logs'
            ),
            'allow_other_plugins_abf' => array(
                'name' => __('Show WooCommerce Shipping Options ', 'woocommerce-settings-abf_quotes'),
                'type' => 'select',
                'default' => '3',
                'desc' => __('Enabled options on WooCommerce Shipping page are included in quote results.', 'woocommerce-settings-abf_quotes'),
                'id' => 'wc_settings_abf_allow_other_plugins',
                'options' => array(
                    'yes' => __('YES', 'YES'),
                    'no' => __('NO', 'NO')
                )
            ),
            'return_abf_quotes' => array(
                'name' => __("Return LTL quotes when an order parcel shipment weight exceeds the weight threshold  ", 'woocommerce-settings-abf_quetes'),
                'type' => 'checkbox',
                'desc' => '<span class="description" >When checked, the LTL Freight Quote will return quotes when an order’s total weight exceeds the weight threshold (the maximum permitted by WWE and UPS), even if none of the products have settings to indicate that it will ship LTL Freight. To increase the accuracy of the returned quote(s), all products should have accurate weights and dimensions. </span>',
                'id' => 'en_plugins_return_LTL_quotes'
            ),
            // Weight threshold for LTL freight
            'en_weight_threshold_lfq' => [
                'name' => __('Weight threshold for LTL Freight Quotes  ', 'woocommerce-settings-abf_quetes'),
                'type' => 'text',
                'default' => $weight_threshold,
                'class' => $weight_threshold_class,
                'id' => 'en_weight_threshold_lfq'
            ],
            'en_suppress_parcel_rates' => array(
                'name' => __("", 'woocommerce-settings-abf_quetes'),
                'type' => 'radio',
                'default' => 'display_parcel_rates',
                'options' => array(
                    'display_parcel_rates' => __("Continue to display parcel rates when the weight threshold is met.", 'woocommerce'),
                    'suppress_parcel_rates' => __("Suppress parcel rates when the weight threshold is met.", 'woocommerce'),
                ),
                'class' => 'en_suppress_parcel_rates',
                'id' => 'en_suppress_parcel_rates',
            ),
            'error_management_abf' => array(
                'name' => __('Error management ', 'woocommerce-settings-abf_quetes'),
                'type' => 'text',
                'id' => 'error_management_abf',
                'class' => 'hidden',
            ),
            'error_management_settings_abf' => array(
                'name' => __('', 'woocommerce-settings-abf_quetes'),
                'type' => 'radio',
                'default' => 'quote_shipping',
                'options' => array(
                    'quote_shipping' => __('Quote shipping using known shipping parameters, even if other items are missing shipping parameters.', 'woocommerce'),
                    'dont_quote_shipping' => __('Don\'t quote shipping if one or more items are missing the required shipping parameters.', 'woocommerce'),
                ),
                'id' => 'error_management_settings_abf',
            ),
            'unable_retrieve_shipping_clear_abf' => array(
                'title' => __('', 'woocommerce'),
                'name' => __('', 'woocommerce-settings-abf-quotes'),
                'desc' => '',
                'id' => 'unable_retrieve_shipping_clear_abf',
                'css' => '',
                'default' => '',
                'type' => 'title',
            ),
            'unable_retrieve_shipping_abf' => array(
                'name' => __('Checkout options if the plugin fails to return a rate ', 'woocommerce-settings-abf_quetes'),
                'type' => 'title',
                'desc' => '<span>When the plugin is unable to retrieve shipping quotes and no other shipping options are provided by an alternative source:</span>',
                'id' => 'wc_settings_unable_retrieve_shipping_abf',
            ),
            'pervent_checkout_proceed_abf' => array(
                'name' => __('', 'woocommerce-settings-abf_quetes'),
                'type' => 'radio',
                'id' => 'pervent_checkout_proceed_abf_packages',
                'options' => array(
                    'backup_rates' => __('', 'woocommerce-settings-abf_quotes'),
                    'allow' => __('', 'woocommerce'),
                    'prevent' => __('', 'woocommerce'),
                ),
                'id' => 'wc_pervent_proceed_checkout_eniture',
            ),
            'section_end_quote' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_quote_section_end'
            ),
        );

        if (is_plugin_active('en-dynamic-discount-toggle/en-dynamic-discount-toggle.php')) {
            $settings = apply_filters('en_dynamic_discount_add_toggle', $settings);
        }

        return $settings;
    }

}
