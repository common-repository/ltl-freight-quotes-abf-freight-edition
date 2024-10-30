<?php
/**
 * Plugin Name: LTL Freight Quotes - ABF Freight Edition
 * Plugin URI: https://eniture.com/products/
 * Description: Dynamically retrieves your negotiated shipping rates from ABF Freight and displays the results in the WooCommerce shopping cart.
 * Version: 3.3.7
 * Author: Eniture Technology
 * Author URI: https://eniture.com/
 * Text Domain: eniture-technology
 * License: GPL version 2 or later - http://www.eniture.com/
 * WC requires at least: 6.4
 * WC tested up to: 9.3.1
 */

if (!defined('ABSPATH')) {
    exit;
}
define('ABF_DOMAIN_HITTING_URL', 'https://ws030.eniture.com');
define('ABF_FDO_HITTING_URL', 'https://freightdesk.online/api/updatedWoocomData');

add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

if (!function_exists('is_plugin_active')) {
    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
}
// Define reference
function en_abf_freight_plugin($plugins)
{
    $plugins['lfq'] = (isset($plugins['lfq'])) ? array_merge($plugins['lfq'], ['abf' => 'ABF_Freight_Shipping']) : ['abf' => 'ABF_Freight_Shipping'];
    return $plugins;
}

add_filter('en_plugins', 'en_abf_freight_plugin');

/**
 * Array For common Plans Notification On Product Detail Page
 */
if (!function_exists('en_woo_plans_notification_PD')) {

    function en_woo_plans_notification_PD($product_detail_options)
    {
        $eniture_plugins_id = 'eniture_plugin_';

        for ($e = 1; $e <= 25; $e++) {
            $settings = get_option($eniture_plugins_id . $e);
            if (isset($settings) && (!empty($settings)) && (is_array($settings))) {
                $plugin_detail = current($settings);
                $plugin_name = (isset($plugin_detail['plugin_name'])) ? $plugin_detail['plugin_name'] : "";

                foreach ($plugin_detail as $key => $value) {
                    if ($key != 'plugin_name') {
                        $action = $value === 1 ? 'enable_plugins' : 'disable_plugins';
                        $product_detail_options[$key][$action] = (isset($product_detail_options[$key][$action]) && strlen($product_detail_options[$key][$action]) > 0) ? ", $plugin_name" : "$plugin_name";
                    }
                }
            }
        }

        return $product_detail_options;
    }

    add_filter('en_woo_plans_notification_action', 'en_woo_plans_notification_PD', 10, 1);
}


// Product detail set plans notification message for nested checkbox
if (!function_exists('en_woo_plans_nested_notification_message')) {

    function en_woo_plans_nested_notification_message($enable_plugins, $disable_plugins, $feature)
    {
        $enable_plugins = (strlen($enable_plugins) > 0) ? "$enable_plugins: <b> Enabled</b>. " : "";
        $disable_plugins = (strlen($disable_plugins) > 0 && $feature == 'nested_material') ? " $disable_plugins: Upgrade to <b>Advance Plan to enable</b>." : "";
        return $enable_plugins . "<br>" . $disable_plugins;
    }

    add_filter('en_woo_plans_nested_notification_message_action', 'en_woo_plans_nested_notification_message', 10, 3);
}

/**
 * Load scripts for Abf Freight json tree view
 */
if (!function_exists('en_jtv_script')) {
    function en_jtv_script()
    {
        wp_register_style('json_tree_view_style', plugin_dir_url(__FILE__) . 'logs/en-json-tree-view/en-jtv-style.css');
        wp_register_script('json_tree_view_script', plugin_dir_url(__FILE__) . 'logs/en-json-tree-view/en-jtv-script.js', ['jquery'], '1.0.0');

        wp_enqueue_style('json_tree_view_style');
        wp_enqueue_script('json_tree_view_script', [
            'en_tree_view_url' => plugins_url(),
        ]);

        // Shipping rules script and styles
        wp_enqueue_script('en_abf_sr_script', plugin_dir_url(__FILE__) . '/shipping-rules/assets/js/shipping_rules.js', array(), '1.0.1');
        wp_localize_script('en_abf_sr_script', 'script', array(
            'pluginsUrl' => plugins_url(),
        ));
        wp_register_style('en_abf_shipping_rules_section', plugin_dir_url(__FILE__) . '/shipping-rules/assets/css/shipping_rules.css', false, '1.0.0');
        wp_enqueue_style('en_abf_shipping_rules_section');
    }

    add_action('admin_init', 'en_jtv_script');
}

/**
 * Show plan notification on product detail page
 */
if (!function_exists('en_woo_plans_notification_message')) {

    function en_woo_plans_notification_message($enable_plugins, $disable_plugins)
    {
        $enable_plugins = (strlen($enable_plugins) > 0) ? "$enable_plugins: <b> Enabled</b>. " : "";
        $disable_plugins = (strlen($disable_plugins) > 0) ? " $disable_plugins: Upgrade to <b>Standard Plan to enable</b>." : "";
        return $enable_plugins . "<br>" . $disable_plugins;
    }

    add_filter('en_woo_plans_notification_message_action', 'en_woo_plans_notification_message', 10, 2);
}

/**
 * Check woo-commerce installation
 */
if (!is_plugin_active('woocommerce/woocommerce.php')) {
    add_action('admin_notices', 'abf_wc_avaibility_error');
}

/**
 * Show error when woo-commerce plugin is not activated
 */
function abf_wc_avaibility_error()
{
    $class = "error";
    $message = "LTL Freight Quotes - ABF Freight Edition is enabled but not effective. It requires WooCommerce in order to work , Please <a target='_blank' href='https://wordpress.org/plugins/woocommerce/installation/'>Install</a> WooCommerce Plugin. Reactive LTL Freight Quotes - ABF Freight Edition plugin to create LTL shipping class.";
    echo "<div class=\"$class\"> <p>$message</p></div>";
}

add_action('admin_enqueue_scripts', 'en_abf_freight_script');

/**
 * Load Front-end scripts for abf
 */
function en_abf_freight_script()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('en_abf_freight_script', plugin_dir_url(__FILE__) . 'js/en-abf.js', array(), '1.1.5');
    wp_localize_script('en_abf_freight_script', 'en_abf_admin_script', array(
        'en_plugins_url' => plugins_url(),
        'allow_proceed_checkout_eniture' => trim(get_option("allow_proceed_checkout_eniture")),
        'prevent_proceed_checkout_eniture' => trim(get_option("prevent_proceed_checkout_eniture")),
        'abf_freight_order_cutoff_time' => get_option("abf_freight_orderCutoffTime"),
        'backup_rates' => get_option('eniture_backup_rates'),
        'backup_rates_amount' => get_option('eniture_backup_rates_amount')
    ));
}

/**
 * Inlude Plugin Files
 */
require_once 'abf-liftgate-as-option.php';

require_once 'fdo/en-fdo.php';

require_once 'abf-curl-class.php';
require_once 'abf-test-connection.php';
require_once 'abf-shipping-class.php';
require_once 'db/abf-db.php';
require_once 'abf-admin-filter.php';

require_once 'template/products-nested-options.php';
require_once 'template/csv-export.php';

require_once 'order/en-order-export.php';
require_once 'order/en-order-widget.php';

// Origin terminal address
add_action('admin_init', 'abf_update_warehouse');
add_action('admin_init', 'create_abf_shipping_rules_db');
require_once('product/en-product-detail.php');
require_once('shipping-rules/shipping-rules-save.php');

/*
 * link files of plans and warehouse
 */
require_once('warehouse-dropship/wild-delivery.php');
require_once('warehouse-dropship/get-distance-request.php');
require_once('standard-package-addon/standard-package-addon.php');
require_once 'update-plan.php';

require_once 'abf-group-package.php';
require_once 'abf-carrier-service.php';
require_once('template/connection-settings.php');
require_once('template/quote-settings.php');
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once 'wc-update-change.php';
require_once('order/rates/order-rates.php');

add_action('admin_init', 'abf_check_wc_version');

/**
 * Check woocommerce version compatibility
 */
function abf_check_wc_version()
{
    $wcPluginVersion = new abf_get_shipping_quotes();
    $woo_version = $wcPluginVersion->abf_wc_version_number();
    $version = '2.6';
    if (!version_compare($woo_version["woocommerce_plugin_version"], $version, ">=")) {
        add_action('admin_notices', 'wc_version_incompatibility_abf');
    }
}

/**
 * Check woo-commerce version incompatibility
 */
function wc_version_incompatibility_abf()
{
    ?>
    <div class="notice notice-error">
        <p>
            <?php
            _e('LTL Freight Quotes - ABF Freight Edition plugin requires WooCommerce version 2.6 or higher to work. Functionality may not work properly.', 'wwe-woo-version-failure');
            ?>
        </p>
    </div>
    <?php
}

/**
 * If Woo-commerce activated on store
 */
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) || is_plugin_active_for_network('woocommerce/woocommerce.php')) {

    add_action('admin_enqueue_scripts', 'abf_admin_script');

    /**
     * Load scripts for ABF
     */
    function abf_admin_script()
    {
        wp_register_style('abf_style', plugin_dir_url(__FILE__) . 'css/abf-style.css', false, '1.1.0');
        wp_register_style('abf_wickedpicker_style', plugin_dir_url(__FILE__) . 'css/wickedpicker.min.css', false, '1.0.0');
        wp_register_script('abf_wickedpicker_script', plugin_dir_url(__FILE__) . 'js/wickedpicker.js', false, '1.0.0');
        wp_enqueue_style('abf_wickedpicker_style');
        wp_enqueue_style('abf_style');
        wp_enqueue_script('abf_wickedpicker_script');

        if(is_admin() && (!empty( $_GET['page']) && 'wc-orders' == $_GET['page'] ) && (!empty( $_GET['action']) && 'new' == $_GET['action'] ))
        {
            if (!wp_script_is('eniture_calculate_shipping_admin', 'enqueued')) {
                wp_enqueue_script('eniture_calculate_shipping_admin', plugin_dir_url(__FILE__) . 'js/eniture-calculate-shipping-admin.js', array(), '1.0.0' );
            }
        }
    }

    /**
     * ABF Freight Activation Hook
     */
    register_activation_hook(__FILE__, 'create_ltl_freight_class_abf');
    register_activation_hook(__FILE__, 'create_abf_wh_db');
    register_activation_hook(__FILE__, 'create_abf_option');
    register_activation_hook(__FILE__, 'create_abf_shipping_rules_db');

    register_activation_hook(__FILE__, 'en_abf_freight_activate_hit_to_update_plan');
    register_activation_hook(__FILE__, 'old_store_abf_ltl_dropship_status');
    register_deactivation_hook(__FILE__, 'en_abf_freight_deactivate_hit_to_update_plan');
    register_deactivation_hook(__FILE__, 'en_abf_deactivate_plugin');

    /**
     * ABF plugin update now
     */
    function en_abf_update_now()
    {
        $index = 'ltl-freight-quotes-abf-freight-edition/ltl-freight-quotes-abf-freight-edition.php';
        $plugin_info = get_plugins();
        $plugin_version = (isset($plugin_info[$index]['Version'])) ? $plugin_info[$index]['Version'] : '';
        $update_now = get_option('en_abf_update_now');

        if ($update_now != $plugin_version) {
            if (!function_exists('en_abf_freight_activate_hit_to_update_plan')) {
                require_once(__DIR__ . '/update-plan.php');
            }

            create_ltl_freight_class_abf();
            create_abf_wh_db();
            create_abf_option();
            en_abf_freight_activate_hit_to_update_plan();
            old_store_abf_ltl_dropship_status();

            update_option('en_abf_update_now', $plugin_version);
        }
    }

    add_action('init', 'en_abf_update_now');


    /**
     * ABF Action And Filters
     */
    add_action('woocommerce_shipping_init', 'abf_freight_init');
    add_filter('woocommerce_shipping_methods', 'add_abf_freight');
    add_filter('woocommerce_get_settings_pages', 'abf_shipping_sections');
    add_filter('woocommerce_package_rates', 'abf_hide_shipping', 99);
    add_filter('woocommerce_shipping_calculator_enable_city', '__return_true');
    add_filter('plugin_action_links', 'abf_freight_add_action_plugin', 10, 5);
    add_filter('woocommerce_cart_no_shipping_available_html', 'abf_default_error_message', 999, 1);
    add_action('init', 'abf_no_method_available');
    add_action('init', 'abf_default_error_message_selection');


    /**
     * Update Default custom error message selection
     */
    function abf_default_error_message_selection()
    {
        $custom_error_selection = get_option('wc_pervent_proceed_checkout_eniture');
        if (empty($custom_error_selection)) {
            update_option('wc_pervent_proceed_checkout_eniture', 'prevent', true);
            update_option('prevent_proceed_checkout_eniture', 'There are no shipping methods available for the address provided. Please check the address.', true);
        }

        if (empty(get_option('eniture_backup_rates'))) {
            update_option('eniture_backup_rates', '', true);
        }

        if (empty(get_option('eniture_backup_rates_amount'))) {
            update_option('eniture_backup_rates_amount', '', true);
        }

        if (empty(get_option('error_management_settings_abf'))) {
            update_option('error_management_settings_abf', 'quote_shipping', true);
        }
    }

    /**
     * @param $message
     * @return string
     */
    if (!function_exists("abf_default_error_message")) {

        function abf_default_error_message($message)
        {

            if (get_option('wc_pervent_proceed_checkout_eniture') == 'prevent') {
                remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20, 2);
                return __(get_option('prevent_proceed_checkout_eniture'));
            } else if (get_option('wc_pervent_proceed_checkout_eniture') == 'allow') {
                add_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20, 2);
                return __(get_option('allow_proceed_checkout_eniture'));
            }
        }

    }

    /**
     * ABF action links
     * @staticvar $plugin
     * @param $actions
     * @param $plugin_file
     * @return array
     */
    function abf_freight_add_action_plugin($actions, $plugin_file)
    {
        static $plugin;
        if (!isset($plugin))
            $plugin = plugin_basename(__FILE__);
        if ($plugin == $plugin_file) {
            $settings = array('settings' => '<a href="admin.php?page=wc-settings&tab=abf_quotes">' . __('Settings', 'General') . '</a>');
            $site_link = array('support' => '<a href="https://support.eniture.com/" target="_blank">Support</a>');
            $actions = array_merge($settings, $actions);
            $actions = array_merge($site_link, $actions);
        }
        return $actions;
    }

}


define("en_woo_plugin_abf_quotes", "abf_quotes");

add_action('wp_enqueue_scripts', 'en_ltl_abf_frontend_checkout_script');

/**
 * Load Front-end scripts for ODFL
 */
function en_ltl_abf_frontend_checkout_script()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('en_ltl_abf_frontend_checkout_script', plugin_dir_url(__FILE__) . 'front/js/en-abf-checkout.js', array(), '1.0.0');
    wp_localize_script('en_ltl_abf_frontend_checkout_script', 'frontend_script', array(
        'pluginsUrl' => plugins_url(),
    ));
}

/**
 * Get Domain Name
 */
if (!function_exists('abf_freight_get_domain')) {

    function abf_freight_get_domain()
    {
        global $wp;
        $url = home_url($wp->request);
        return getHost($url);
    }

}

/**
 * Get Host Name
 */
if (!function_exists('getHost')) {

    function getHost($url)
    {
        $parseUrl = parse_url(trim($url));
        if (isset($parseUrl['host'])) {
            $host = $parseUrl['host'];
        } else {
            $path = explode('/', $parseUrl['path']);
            $host = $path[0];
        }
        return trim($host);
    }

}

/**
 * Plans Common Hooks
 */
add_filter('abf_quotes_quotes_plans_suscription_and_features', 'abf_quotes_quotes_plans_suscription_and_features', 1);

/**
 * Features with their plans
 * @param string $feature
 * @return Array/Boolean
 */
function abf_quotes_quotes_plans_suscription_and_features($feature)
{
    $package = get_option('abf_freight_package');

    $features = array
    (
        'instore_pickup_local_devlivery' => array('3'),
        'hazardous_material' => array('2', '3'),
        'hold_at_terminal' => array('3'),
        'abf_cutOffTime_shipDateOffset' => array('2', '3'),
        'nested_material' => array('3'),
    );

    if (get_option('abf_freight_quotes_store_type') == "1") {
        $features['multi_warehouse'] = array('2', '3');
        $features['multi_dropship'] = array('', '0', '1', '2', '3');
    }

    if (get_option('en_old_user_dropship_status') == "0" && get_option('abf_freight_quotes_store_type') == "0") {
        $features['multi_dropship'] = array('', '0', '1', '2', '3');
    }

    if (get_option('en_old_user_warehouse_status') === "0" && get_option('abf_freight_quotes_store_type') == "0") {
        $features['multi_warehouse'] = array('2', '3');
    }

    return (isset($features[$feature]) && (in_array($package, $features[$feature]))) ? TRUE : ((isset($features[$feature])) ? $features[$feature] : '');
}

add_filter('abf_quotes_plans_notification_link', 'abf_quotes_plans_notification_link', 1);

/**
 * Plan Notification URL To Redirect eniture.com
 * @param array $plans
 * @return string
 */
function abf_quotes_plans_notification_link($plans)
{
    $plan = current($plans);
    $plan_to_upgrade = "";
    switch ($plan) {
        case 1:
            $plan_to_upgrade = "<a target='_blank' href='https://eniture.com/woocommerce-abf-ltl-freight/'>Basic Plan required.</a>";
            break;
        case 2:
            $plan_to_upgrade = "<a target = '_blank' href='https://eniture.com/woocommerce-abf-ltl-freight/'>Standard Plan required.</a>";
            break;
        case 3:
            $plan_to_upgrade = "<a target = '_blank' href='https://eniture.com/woocommerce-abf-ltl-freight/'>Advanced Plan required.</a>";
            break;
    }

    return $plan_to_upgrade;
}

/**
 * old customer check dropship / warehouse status on plugin update
 */
function old_store_abf_ltl_dropship_status()
{
    global $wpdb;

//  Check total no. of dropships on plugin updation
    $table_name = $wpdb->prefix . 'warehouse';
    $count_query = "select count(*) from $table_name where location = 'dropship' ";
    $num = $wpdb->get_var($count_query);

    if (get_option('en_old_user_dropship_status') == "0" && get_option('abf_freight_quotes_store_type') == "0") {
        $dropship_status = ($num > 1) ? 1 : 0;
        update_option('en_old_user_dropship_status', "$dropship_status");
    } elseif (get_option('en_old_user_dropship_status') == "" && get_option('abf_freight_quotes_store_type') == "0") {
        $dropship_status = ($num == 1) ? 0 : 1;
        update_option('en_old_user_dropship_status', "$dropship_status");
    }

//  Check total no. of warehouses on plugin updation
    $table_name = $wpdb->prefix . 'warehouse';
    $warehouse_count_query = "select count(*) from $table_name where location = 'warehouse' ";
    $warehouse_num = $wpdb->get_var($warehouse_count_query);

    if (get_option('en_old_user_warehouse_status') == "0" && get_option('abf_freight_quotes_store_type') == "0") {
        $warehouse_status = ($warehouse_num > 1) ? 1 : 0;
        update_option('en_old_user_warehouse_status', "$warehouse_status");
    } elseif (get_option('en_old_user_warehouse_status') == "" && get_option('abf_freight_quotes_store_type') == "0") {
        $warehouse_status = ($warehouse_num == 1) ? 0 : 1;
        update_option('en_old_user_warehouse_status', "$warehouse_status");
    }
}
add_action('wp_ajax_nopriv_abf_fd', 'abf_fd_api');
add_action('wp_ajax_abf_fd', 'abf_fd_api');
/**
 * ABF AJAX Request
 */
function abf_fd_api()
{
    $store_name = abf_freight_get_domain();
    $company_id = $_POST['company_id'];
    $data = [
        'plateform'  => 'wp',
        'store_name' => $store_name,
        'company_id' => $company_id,
        'fd_section' => 'tab=abf_quotes&section=section-4',
    ];
    if (is_array($data) && count($data) > 0) {
        if($_POST['disconnect'] != 'disconnect') {
            $url =  'https://freightdesk.online/validate-company';
        }else {
            $url = 'https://freightdesk.online/disconnect-woo-connection';
        }
        $response = wp_remote_post($url, [
                'method' => 'POST',
                'timeout' => 60,
                'redirection' => 5,
                'blocking' => true,
                'body' => $data,
            ]
        );
        $response = wp_remote_retrieve_body($response);
    }
    if($_POST['disconnect'] == 'disconnect') {
        $result = json_decode($response);
        if ($result->status == 'SUCCESS') {
            update_option('en_fdo_company_id_status', 0);

        } else {
            //error message show
        }
    }
    echo $response;
    exit();
}
add_action('rest_api_init', 'en_rest_api_init_status');
function en_rest_api_init_status()
{
    register_rest_route('fdo-company-id', '/update-status', array(
        'methods' => 'POST',
        'callback' => 'en_abf_fdo_data_status',
        'permission_callback' => '__return_true'
    ));
}

/**
 * Update FDO coupon data
 * @param array $request
 * @return array|void
 */
function en_abf_fdo_data_status(WP_REST_Request $request)
{
    $status_data = $request->get_body();
    $status_data_decoded = json_decode($status_data);
    if (isset($status_data_decoded->connection_status)) {
        update_option('en_fdo_company_id_status', $status_data_decoded->connection_status);
        update_option('en_fdo_company_id', $status_data_decoded->fdo_company_id);
    }
    return true;
}

add_filter('en_suppress_parcel_rates_hook', 'supress_parcel_rates');
if (!function_exists('supress_parcel_rates')) {
    function supress_parcel_rates() {
        $exceedWeight = get_option('en_plugins_return_LTL_quotes') == 'yes';
        $supress_parcel_rates = get_option('en_suppress_parcel_rates') == 'suppress_parcel_rates';
        return ($exceedWeight && $supress_parcel_rates);
    }
}
