<?php

/**
 * ABF Test connection AJAX Request
 * @package     Woo-commerce ABF Edition
 * @author      <https://eniture.com/>
 * @copyright   Copyright (c) 2017, Eniture
 */
if (!defined('ABSPATH')) {
    exit;
}
add_action('wp_ajax_nopriv_abf_test_conn', 'abf_test_submit');
add_action('wp_ajax_abf_test_conn', 'abf_test_submit');

/**
 * ABF Test connection AJAX Request
 */
function abf_test_submit()
{
    $id = $_POST['abf_id'];
    $abf_rates_based_on = '0';
    $nmfcBaseAccount = '0';
    if(isset($_POST['abf_rates_based_on']) && !empty($_POST['abf_rates_based_on'])){
        if('both' == $_POST['abf_rates_based_on'] || 'frtnmfcdim' == $_POST['abf_rates_based_on']){
            $abf_rates_based_on = '1';
        }

        if('frtclsandnmfc' == $_POST['abf_rates_based_on'] || 'frtnmfcdim' == $_POST['abf_rates_based_on']){
            $nmfcBaseAccount = '1';
        }
    }
    $lcns = $_POST['abf_plugin_license'];
    $domain = abf_freight_get_domain();
    $data = array(
        'id' => $id,
        'dimWeightBaseAccount' => $abf_rates_based_on,
        'nmfcBaseAccount' => $nmfcBaseAccount,
        'licence_key' => $lcns,
        'sever_name' => $domain,
        'carrierName' => 'abf',
        'plateform' => 'WordPress',
        'carrier_mode' => 'test',
    );

    if (is_array($data) && count($data) > 0) {
        $url = ABF_DOMAIN_HITTING_URL . '/index.php ';
        $field_string = http_build_query($data);
        $response = wp_remote_post($url, array(
                'method' => 'POST',
                'timeout' => 60,
                'redirection' => 5,
                'blocking' => true,
                'body' => $field_string,
            )
        );

        $response = wp_remote_retrieve_body($response);
    }
    
    $result = isset($response) && !empty($response) ? json_decode($response) : [];
    if (isset($result->error)) {
        $test_error = $result->error;
        $test_error = (is_object($test_error)) ? reset($test_error) : $test_error;
        $response = array('Error' => $test_error);
    } elseif (isset($result->q->ERROR) && $result->q->ERROR != "") {
        $response = array('Error' => $result->q->ERROR->ERRORMESSAGE);
    } elseif (isset($result->q) && $result->q != "") {
        $response = array('Success' => 'The test resulted in a successful connection.');
    } else {
        $response = array('Error' => 'Please verify credentials and try again.');
    }
    echo json_encode($response);
    exit();
}
