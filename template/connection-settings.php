<?php
/**
 * ABF Connection Settings Tab Class
 * @package     Woocommerce ABF Edition
 * @author      <https://eniture.com/>
 * @copyright   Copyright (c) 2017, Eniture
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ABF Connection Settings Tab Class
 */
class ABF_Connection_Settings
{
    /**
     * Connection Settings Fields
     * @return array
     */
    public function abf_con_setting()
    {
        echo '<div class="connection_section_class_abf">';
        $settings = array(
            'section_title_abf' => array(
                'name' => __('', 'woocommerce-settings-abf_quotes'),
                'type' => 'title',
                'desc' => '<br> ',
                'id' => 'wc_settings_abf_title_section_connection',
            ),

            'id_abf' => array(
                'name' => __('ID ', 'woocommerce-settings-abf_quotes'),
                'type' => 'text',
                'desc' => __('', 'woocommerce-settings-abf_quotes'),
                'id' => 'wc_settings_abf_id'
            ),

            'plugin_licence_key_abf' => array(
                'name' => __('Eniture API Key ', 'woocommerce-settings-abf_quotes'),
                'type' => 'text',
                'desc' => __('Obtain a Eniture API Key from <a href="https://eniture.com/woocommerce-abf-ltl-freight/" target="_blank" >eniture.com </a>', 'woocommerce-settings-abf_quotes'),
                'id' => 'wc_settings_abf_plugin_licence_key'
            ),

            'abf_rates_based_on' => array(
                'name' => __('ABF rates my freight on weight and...', 'woocommerce-settings-abf_quotes'),
                'type' => 'radio',
                'id' => 'abf_rates_based_on',
                'default' => 'frtclass',
                'options' => array(
                    'frtclass' => __('freight class', 'woocommerce-settings-abf_quotes'),
                    'frtclsandnmfc' => __('freight class and NMFC number', 'woocommerce-settings-abf_quotes'),
                    'both' => __('freight class and dimensions', 'woocommerce-settings-abf_quotes'),
                    'frtnmfcdim' => __('freight class, NMFC number, and dimensions.', 'woocommerce-settings-abf_quotes'),
                )
            ),

            'section_end_abf' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_abf_plugin_licence_key'
            ),
        );
        return $settings;
    }
}
