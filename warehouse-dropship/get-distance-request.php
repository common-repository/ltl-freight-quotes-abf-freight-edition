<?php

/**
 * WWE Small Get Distance
 *
 * @package     WWE Small Quotes
 * @author      Eniture-Technology
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Distance Request Class
 */
class Get_abf_freight_distance
{

    function __construct()
    {
        add_filter("en_wd_get_address", array($this, "sm_address"), 10, 2);
    }

    /**
     * Get Address Upon Access Level
     * @param $map_address
     * @param $accessLevel
     */
    function abf_freight_address($map_address, $accessLevel, $destinationZip = array())
    {

        $domain = abf_freight_get_domain();
        $postData = array(
            'acessLevel' => $accessLevel,
            'address' => $map_address,
            'originAddresses' => (isset($map_address)) ? $map_address : "",
            'destinationAddress' => (isset($destinationZip)) ? $destinationZip : "",
            'eniureLicenceKey' => get_option('wc_settings_abf_plugin_licence_key'),
            'ServerName' => $domain,
        );

        $abf_Curl_Request = new ABF_Curl_Request();
        $output = $abf_Curl_Request->abf_get_curl_response(ABF_DOMAIN_HITTING_URL . '/addon/google-location.php', $postData);
        return $output;
    }

}
