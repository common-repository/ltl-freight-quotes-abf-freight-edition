<?php
/**
 * ABF WooComerce Shipping Address Updates  
 * @package     Woocommerce ABF Edition
 * @author      <https://eniture.com/>
 * @copyright   Copyright (c) 2017, Eniture
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
/**
 * ABF WooComerce Billing Address Updates Changes Class
 */
class Woo_Update_Changes_ABF
{
    /**
     * WooComerce Version Number
     * @var int 
     */
    public $WooVersion;
    /**
     * ABF WooComerce Shipping Address Updates Class Constructor
     */
    function __construct() {
        if (!function_exists('get_plugins'))
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            $plugin_folder = get_plugins('/' . 'woocommerce');
            $plugin_file = 'woocommerce.php';
            $this->WooVersion = $plugin_folder[$plugin_file]['Version'];
    }
    
    /**
     * ABF WooComerce Customer Postcode
     * @return string
     */
    function abf_postcode(){
        $postcode = "";
        switch ($this->WooVersion) {
            case ($this->WooVersion <= '2.7'):
                $postcode = WC()->customer->get_postcode();
                break;
            case ($this->WooVersion >= '3.0'):
                $postcode = WC()->customer->get_billing_postcode();
                break;

            default:
                
                break;
        }

        return $postcode;
    }
    /**
     * ABF WooComerce Customer State
     * @return string
     */
    function abf_state(){
        $postcode = "";
        switch ($this->WooVersion) {
            case ($this->WooVersion <= '2.7'):
                $postcode = WC()->customer->get_state();
                break;
            case ($this->WooVersion >= '3.0'):
                $postcode = WC()->customer->get_billing_state();
                break;

            default:
                
                break;
        }

        return $postcode;
    }
    /**
     * ABF WooComerce Customer City
     * @return string
     */
    function abf_city(){
        $postcode = "";
        switch ($this->WooVersion) {
            case ($this->WooVersion <= '2.7'):
                $postcode = WC()->customer->get_city();
                break;
            case ($this->WooVersion >= '3.0'):
                $postcode = WC()->customer->get_billing_city();
                break;

            default:
                
                break;
        }

        return $postcode;
    }
    
    /**
     * ABF WooComerce Customer Country
     * @return string
     */
    function abf_country(){
        $postcode = "";
        switch ($this->WooVersion) {
            case ($this->WooVersion <= '2.7'):
                $postcode = WC()->customer->get_country();
                break;
            case ($this->WooVersion >= '3.0'):
                $postcode = WC()->customer->get_billing_country();
                break;

            default:
                
                break;
        }

        return $postcode;
    }
    
}