jQuery(document).ready(function () {

    // Weight threshold for LTL freight
    en_weight_threshold_limit();

    jQuery("#wc_settings_abf_residential").closest('tr').addClass("wc_settings_abf_residential");
    jQuery("#avaibility_auto_residential").closest('tr').addClass("avaibility_auto_residential");
    jQuery("#avaibility_lift_gate").closest('tr').addClass("avaibility_lift_gate");
    jQuery("#wc_settings_abf_liftgate").closest('tr').addClass("wc_settings_abf_liftgate");
    jQuery("#abf_quotes_liftgate_delivery_as_option").closest('tr').addClass("abf_quotes_liftgate_delivery_as_option");
    jQuery("#abf_freight_shipmentOffsetDays").closest('tr').addClass("abf_freight_shipmentOffsetDays_tr");
    jQuery("#all_shipment_days_abf").closest('tr').addClass("all_shipment_days_abf_tr");
    jQuery(".abf_shipment_day").closest('tr').addClass("abf_shipment_day_tr");
    //            Add New Option Delivery Estimate
    jQuery("#abf_freight_orderCutoffTime").closest('tr').addClass("abf_freight_cutOffTime_shipDateOffset");
    jQuery("#abf_hold_at_terminal_checkbox_status").closest('tr').addClass("abf_hold_at_terminal_checkbox_status");
    jQuery("#service_abf_estimates_title").closest('tr').addClass("abf_estimates_title");
    jQuery("#abf_freight_cutOffTime_shipDateOffset").closest('tr').addClass("abf_ship_date_offset");
    jQuery("#abf_freight_orderCutoffTime").closest('td').addClass("abf_freight_orderCutoffTime_td");
    jQuery("#abf_freight_shipmentOffsetDays").closest('td').addClass("abf_freight_shipmentOffsetDays_td");
    jQuery("input[name=abf_delivery_estimates]").closest('tr').addClass("abf_delivery_estimates_tr");
    jQuery("#order_shipping_line_items .shipping .display_meta").css('display', 'none');
    jQuery("#order_shipping_line_items .shipping .display_meta").css('display', 'none');
    jQuery("#handling_weight_abf").closest('tr').addClass("abf_freight_shipmentOffsetDays_tr");
    jQuery("#maximum_handling_weight_abf").closest('tr').addClass("abf_freight_shipmentOffsetDays_tr");
    jQuery("#handling_weight_abf").attr('maxlength','8');
    jQuery("#maximum_handling_weight_abf").attr('maxlength','8');
    var currentTime = en_abf_admin_script.abf_freight_order_cutoff_time;
    if (currentTime == '') {

        jQuery('#abf_freight_orderCutoffTime').wickedpicker({
            now: '',
            title: 'Cut Off Time',
        });
    } else {
        jQuery('#abf_freight_orderCutoffTime').wickedpicker({

            now: currentTime,
            title: 'Cut Off Time'
        });
    }

    var delivery_estimate_val = jQuery('input[name=abf_delivery_estimates]:checked').val();
    if (delivery_estimate_val == 'dont_show_estimates') {
        jQuery("#abf_freight_orderCutoffTime").prop('disabled', true);
        jQuery("#abf_freight_shipmentOffsetDays").prop('disabled', true);
        jQuery("#all_shipment_days_abf").prop('disabled', true);
        jQuery("#monday_shipment_day_abf").prop('disabled', true);
        jQuery("#tuesday_shipment_day_abf").prop('disabled', true);
        jQuery("#wednesday_shipment_day_abf").prop('disabled', true);
        jQuery("#thursday_shipment_day_abf").prop('disabled', true);
        jQuery("#friday_shipment_day_abf").prop('disabled', true);
        jQuery("#abf_freight_shipmentOffsetDays").css("cursor", "not-allowed");
        jQuery("#abf_freight_orderCutoffTime").css("cursor", "not-allowed");
        jQuery("#all_shipment_days_abf").css("cursor", "not-allowed");
        jQuery("#monday_shipment_day_abf").css("cursor", "not-allowed");
        jQuery("#tuesday_shipment_day_abf").css("cursor", "not-allowed");
        jQuery("#wednesday_shipment_day_abf").css("cursor", "not-allowed");
        jQuery("#thursday_shipment_day_abf").css("cursor", "not-allowed");
        jQuery("#friday_shipment_day_abf").css("cursor", "not-allowed");
    } else {
        jQuery("#abf_freight_orderCutoffTime").prop('disabled', false);
        jQuery("#abf_freight_shipmentOffsetDays").prop('disabled', false);
        jQuery("#all_shipment_days_abf").prop('disabled', false);
        jQuery("#monday_shipment_day_abf").prop('disabled', false);
        jQuery("#tuesday_shipment_day_abf").prop('disabled', false);
        jQuery("#wednesday_shipment_day_abf").prop('disabled', false);
        jQuery("#thursday_shipment_day_abf").prop('disabled', false);
        jQuery("#friday_shipment_day_abf").prop('disabled', false);
        jQuery("#abf_freight_orderCutoffTime").css("cursor", "");
        jQuery("#abf_freight_shipmentOffsetDays").css("cursor", "");
        jQuery("#all_shipment_days_abf").css("cursor", "");
        jQuery("#monday_shipment_day_abf").css("cursor", "");
        jQuery("#tuesday_shipment_day_abf").css("cursor", "");
        jQuery("#wednesday_shipment_day_abf").css("cursor", "");
        jQuery("#thursday_shipment_day_abf").css("cursor", "");
        jQuery("#friday_shipment_day_abf").css("cursor", "");
    }

    jQuery("input[name=abf_delivery_estimates]").change(function () {
        var delivery_estimate_val = jQuery('input[name=abf_delivery_estimates]:checked').val();
        if (delivery_estimate_val == 'dont_show_estimates') {
            jQuery("#abf_freight_orderCutoffTime").prop('disabled', true);
            jQuery("#abf_freight_shipmentOffsetDays").prop('disabled', true);
            jQuery("#all_shipment_days_abf").prop('disabled', true);
            jQuery("#monday_shipment_day_abf").prop('disabled', true);
            jQuery("#tuesday_shipment_day_abf").prop('disabled', true);
            jQuery("#wednesday_shipment_day_abf").prop('disabled', true);
            jQuery("#thursday_shipment_day_abf").prop('disabled', true);
            jQuery("#friday_shipment_day_abf").prop('disabled', true);
            jQuery("#abf_freight_orderCutoffTime").css("cursor", "not-allowed");
            jQuery("#abf_freight_shipmentOffsetDays").css("cursor", "not-allowed");
            jQuery("#all_shipment_days_abf").css("cursor", "not-allowed");
            jQuery("#monday_shipment_day_abf").css("cursor", "not-allowed");
            jQuery("#tuesday_shipment_day_abf").css("cursor", "not-allowed");
            jQuery("#wednesday_shipment_day_abf").css("cursor", "not-allowed");
            jQuery("#thursday_shipment_day_abf").css("cursor", "not-allowed");
            jQuery("#friday_shipment_day_abf").css("cursor", "not-allowed");
        } else {
            jQuery("#abf_freight_orderCutoffTime").prop('disabled', false);
            jQuery("#abf_freight_shipmentOffsetDays").prop('disabled', false);
            jQuery("#all_shipment_days_abf").prop('disabled', false);
            jQuery("#monday_shipment_day_abf").prop('disabled', false);
            jQuery("#tuesday_shipment_day_abf").prop('disabled', false);
            jQuery("#wednesday_shipment_day_abf").prop('disabled', false);
            jQuery("#thursday_shipment_day_abf").prop('disabled', false);
            jQuery("#friday_shipment_day_abf").prop('disabled', false);
            jQuery("#abf_freight_orderCutoffTime").css("cursor", "");
            jQuery("#abf_freight_shipmentOffsetDays").css("cursor", "");
            jQuery("#all_shipment_days_abf").css("cursor", "");
            jQuery("#monday_shipment_day_abf").css("cursor", "");
            jQuery("#tuesday_shipment_day_abf").css("cursor", "");
            jQuery("#wednesday_shipment_day_abf").css("cursor", "");
            jQuery("#thursday_shipment_day_abf").css("cursor", "");
            jQuery("#friday_shipment_day_abf").css("cursor", "");
        }
    });

    /*
     * Uncheck Week days Select All Checkbox
     */
    jQuery(".abf_shipment_day").on('change load', function () {

        var checkboxes = jQuery('.abf_shipment_day:checked').length;
        var un_checkboxes = jQuery('.abf_shipment_day').length;
        if (checkboxes === un_checkboxes) {
            jQuery('.all_shipment_days_abf').prop('checked', true);
        } else {
            jQuery('.all_shipment_days_abf').prop('checked', false);
        }
    });

    /*
     * Select All Shipment Week days
     */

    var all_int_checkboxes = jQuery('.all_shipment_days_abf');
    if (all_int_checkboxes.length === all_int_checkboxes.filter(":checked").length) {
        jQuery('.all_shipment_days_abf').prop('checked', true);
    }

    jQuery(".all_shipment_days_abf").change(function () {
        if (this.checked) {
            jQuery(".abf_shipment_day").each(function () {
                this.checked = true;
            });
        } else {
            jQuery(".abf_shipment_day").each(function () {
                this.checked = false;
            });
        }
    });


    //** End: Order Cut Off Time

    //** START: Validation for Quote_setting Hold_a_terminal fee

    jQuery("#en_wd_origin_markup,#en_wd_dropship_markup,._en_product_markup").bind("cut copy paste",function(e) {
        e.preventDefault();
     });
    //** Start: Validation for domestic service level markup
    jQuery("#en_wd_origin_markup,#en_wd_dropship_markup,._en_product_markup").keypress(function (e) {
        if (!String.fromCharCode(e.keyCode).match(/^[-0-9\d\.%\s]+$/i)) return false;
    });

    jQuery("#abf_hold_at_terminal_fee, #en_wd_origin_markup, #en_wd_dropship_markup, ._en_product_markup").keydown(function (e) {
        if ((e.keyCode === 109 || e.keyCode === 189) && (jQuery(this).val().length>0) )  return false;
        if (e.keyCode === 53) if (e.shiftKey) if (jQuery(this).val().length == 0) return false; 
        
        if ((jQuery(this).val().indexOf('.') != -1) && (jQuery(this).val().substring(jQuery(this).val().indexOf('.'), jQuery(this).val().indexOf('.').length).length > 2)) {
            if (e.keyCode !== 8 && e.keyCode !== 46) { //exception
                e.preventDefault();
            }
        }

        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 53, 189]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }

        if(jQuery(this).val().length > 7){
            e.preventDefault();
        }
    });

    jQuery("#abf_hold_at_terminal_fee, #en_wd_origin_markup, #en_wd_dropship_markup, ._en_product_markup").keyup(function (e) {

        var val = jQuery(this).val();

        if (val.split('.').length - 1 > 1) {

            var newval = val.substring(0, val.length - 1);
            var countDots = newval.substring(newval.indexOf('.') + 1).length;
            newval = newval.substring(0, val.length - countDots - 1);
            jQuery(this).val(newval);
        }

        if (val.split('%').length - 1 > 1) {
            var newval = val.substring(0, val.length - 1);
            var countPercentages = newval.substring(newval.indexOf('%') + 1).length;
            newval = newval.substring(0, val.length - countPercentages - 1);
            jQuery(this).val(newval);
        }
        if (val.split('>').length - 1 > 0) {
            var newval = val.substring(0, val.length - 1);
            var countGreaterThan = newval.substring(newval.indexOf('>') + 1).length;
            newval = newval.substring(newval, newval.length - countGreaterThan - 1);
            jQuery(this).val(newval);
        }
        if (val.split('_').length - 1 > 0) {
            var newval = val.substring(0, val.length - 1);
            var countUnderScore = newval.substring(newval.indexOf('_') + 1).length;
            newval = newval.substring(newval, newval.length - countUnderScore - 1);
            jQuery(this).val(newval);
        }

        if (val.split('-').length - 1 > 1) {
            var newval = val.substring(0, val.length - 1);
            var countPercentages = newval.substring(newval.indexOf('-') + 1).length;
            newval = newval.substring(0, val.length - countPercentages - 1);
            jQuery(this).val(newval);
        }
    });
    //** END: Validation for Quote_setting Hold_a_terminal fee

    //** START: Validation for Quote_setting Markup/Handling fee

    jQuery("#wc_settings_abf_handling_fee, #handling_weight_abf, #maximum_handling_weight_abf").keydown(function (e) {

        if ((jQuery(this).val().indexOf('.') != -1) && (jQuery(this).val().substring(jQuery(this).val().indexOf('.'), jQuery(this).val().indexOf('.').length).length > 2)) {
            if (e.keyCode !== 8 && e.keyCode !== 46) { //exception
                e.preventDefault();
            }
        }

        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 53, 189]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }

    });

    jQuery("#wc_settings_abf_handling_fee, #handling_weight_abf, #maximum_handling_weight_abf").keyup(function (e) {

        var val = jQuery(this).val();

        if (val.split('.').length - 1 > 1) {

            var newval = val.substring(0, val.length - 1);
            var countDots = newval.substring(newval.indexOf('.') + 1).length;
            newval = newval.substring(0, val.length - countDots - 1);
            jQuery(this).val(newval);
        }

        if (val.split('%').length - 1 > 1) {
            var newval = val.substring(0, val.length - 1);
            var countPercentages = newval.substring(newval.indexOf('%') + 1).length;
            newval = newval.substring(0, val.length - countPercentages - 1);
            jQuery(this).val(newval);
        }
        if (val.split('>').length - 1 > 0) {
            var newval = val.substring(0, val.length - 1);
            var countGreaterThan = newval.substring(newval.indexOf('>') + 1).length;
            newval = newval.substring(newval, newval.length - countGreaterThan - 1);
            jQuery(this).val(newval);
        }
        if (val.split('_').length - 1 > 0) {
            var newval = val.substring(0, val.length - 1);
            var countUnderScore = newval.substring(newval.indexOf('_') + 1).length;
            newval = newval.substring(newval, newval.length - countUnderScore - 1);
            jQuery(this).val(newval);
        }

    });
    //** END: Validation for Quote_setting Markup/Handling fee


    jQuery("._eniture_product_nmfc_number").on('input', function (e) {
        var currentValue = jQuery(this).val();
        
        if (!/^\d*[-]?\d*$/.test(currentValue) || currentValue.charAt(0) === '-' || currentValue.length > 10) {
            jQuery(this).val(currentValue.slice(0, -1));
        }
    });


    /**
     * Offer lift gate delivery as an option and Always include residential delivery fee
     * @returns {undefined}
     */

    jQuery(".checkbox_fr_add").on("click", function () {
        var id = jQuery(this).attr("id");
        if (id == "wc_settings_abf_liftgate") {
            jQuery("#abf_quotes_liftgate_delivery_as_option").prop({checked: false});
            jQuery("#en_woo_addons_liftgate_with_auto_residential").prop({checked: false});

        } else if (id == "abf_quotes_liftgate_delivery_as_option" ||
            id == "en_woo_addons_liftgate_with_auto_residential") {
            jQuery("#wc_settings_abf_liftgate").prop({checked: false});
        }
    });

    var url = getUrlVarsAbfFreight()["tab"];
    if (url === 'abf_quotes') {
        jQuery('#footer-left').attr('id', 'wc-footer-left');
    }
    jQuery('.connection_section_class_abf input[type="text"]').each(function () {
        if (jQuery(this).parent().find('.err').length < 1) {
            jQuery(this).after('<span class="err"></span>');
        }
    });

    jQuery('.connection_section_class_abf .form-table').before('<div class="abf_warning_msg"><p><b>Note!</b> You must have an ABF Freight account to use this application. If you do not have one, contact ABF Freight at 800-610-5544.</p>');

    /*
     * Add Title To Connection Setting Fields
     */

    jQuery('#wc_settings_abf_id').attr('title', 'ID');
    jQuery('#wc_settings_abf_plugin_licence_key').attr('title', 'Eniture API Key');
    jQuery('#wc_settings_abf_handling_fee').attr('title', 'Handling Fee / Markup');
    jQuery('#wc_settings_abf_label_as').attr('title', 'Label As');
    jQuery('#wc_settings_abf_label_as').attr('maxlength', '50');

    /*
         * Save Changes At Connection Section Action
         */

    jQuery(".connection_section_class_abf .woocommerce-save-button").click(function () {
        var has_err = true;
        jQuery(".connection_section_class_abf tbody input[type='text']").each(function () {
            var input = jQuery(this).val();
            var response = validateString(input);

            var errorElement = jQuery(this).parent().find('.err');
            jQuery(errorElement).html('');
            var errorText = jQuery(this).attr('title');
            var optional = jQuery(this).data('optional');
            optional = (optional === undefined) ? 0 : 1;
            errorText = (errorText != undefined) ? errorText : '';
            if ((optional == 0) && (response == false || response == 'empty')) {
                errorText = (response == 'empty') ? errorText + ' is required.' : 'Invalid input.';
                jQuery(errorElement).html(errorText);
            }
            has_err = (response != true && optional == 0) ? false : has_err;
        });
        var input = has_err;
        if (input === false) {
            return false;
        }
    });

    /*
     * Test connection
     */

    jQuery(".connection_section_class_abf .woocommerce-save-button").text('Save Changes');
    jQuery(".connection_section_class_abf .woocommerce-save-button").before('<a href="javascript:void(0)" class="button-primary abf_test_connection">Test Connection</a>');
    jQuery('.abf_test_connection').click(function (e) {
        var has_err = true;
        jQuery(".connection_section_class_abf tbody input[type='text']").each(function () {
            var input = jQuery(this).val();
            var response = validateString(input);

            var errorElement = jQuery(this).parent().find('.err');
            jQuery(errorElement).html('');
            var errorText = jQuery(this).attr('title');
            var optional = jQuery(this).data('optional');
            optional = (optional === undefined) ? 0 : 1;
            errorText = (errorText != undefined) ? errorText : '';
            if ((optional == 0) && (response == false || response == 'empty')) {
                errorText = (response == 'empty') ? errorText + ' is required.' : 'Invalid input.';
                jQuery(errorElement).html(errorText);
            }
            has_err = (response != true && optional == 0) ? false : has_err;
        });
        var input = has_err;
        if (input === false) {
            return false;
        }
        var postForm = {
            'action': 'abf_test_conn',
            'abf_id': jQuery('#wc_settings_abf_id').val(),
            'abf_plugin_license': jQuery('#wc_settings_abf_plugin_licence_key').val(),
            'abf_rates_based_on': jQuery('input[name="abf_rates_based_on"]:checked').val(),
        }
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: postForm,
            dataType: 'json',
            beforeSend: function () {

                jQuery(".connection_save_button").remove();
                jQuery('#wc_settings_abf_id').css('background', 'rgba(255, 255, 255, 1) url("' + en_abf_admin_script.en_plugins_url + '/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/processing.gif") no-repeat scroll 50% 50%');
                jQuery('#wc_settings_abf_plugin_licence_key').css('background', 'rgba(255, 255, 255, 1) url("' + en_abf_admin_script.en_plugins_url + '/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/processing.gif") no-repeat scroll 50% 50%');
            },
            success: function (data) {
                jQuery('#message').hide();
                jQuery(".abf_error_message").remove();
                jQuery(".abf_success_message").remove();

                if (data.Success) {
                    jQuery('#wc_settings_abf_id').css('background', '#fff');
                    jQuery('#wc_settings_abf_plugin_licence_key').css('background', '#fff');
                    jQuery(".abf_success_message").remove();
                    jQuery(".abf_error_message").remove();
                    jQuery('.abf_warning_msg').before('<div class="notice notice-success abf_success_message"><p><strong>Success! ' + data.Success + '</strong></p></div>');
                } else {
                    jQuery(".abf_error_message").remove();
                    jQuery('#wc_settings_abf_id').css('background', '#fff');
                    jQuery('#wc_settings_abf_plugin_licence_key').css('background', '#fff');
                    jQuery(".abf_success_message").remove();
                    jQuery('.abf_warning_msg').before('<div class="notice notice-error abf_error_message"><p>Error! ' + data.Error + '</p></div>');
                }
            }
        });
        e.preventDefault();
    });
    jQuery('#fd_online_id').click(function (e) {
        var postForm = {
            'action': 'abf_fd',
            'company_id': jQuery('#freightdesk_online_id').val(),
            'disconnect': jQuery('#fd_online_id').attr("data")
        }
        var id_lenght = jQuery('#freightdesk_online_id').val();
        var disc_data = jQuery('#fd_online_id').attr("data");
        if(typeof (id_lenght) != "undefined" && id_lenght.length < 1) {
            jQuery(".abf_error_message").remove();
            jQuery('.user_guide_fdo').before('<div class="notice notice-error abf_error_message"><p><strong>Error!</strong> FreightDesk Online ID is Required.</p></div>');
            return;
        }
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: postForm,
            beforeSend: function () {
                jQuery('#freightdesk_online_id').css('background', 'rgba(255, 255, 255, 1) url("' + en_abf_admin_script.en_plugins_url + '/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/processing.gif") no-repeat scroll 50% 50%');
            },
            success: function (data_response) {
                if(typeof (data_response) == "undefined"){
                    return;
                }
                var fd_data = JSON.parse(data_response);
                jQuery('#freightdesk_online_id').css('background', '#fff');
                jQuery(".abf_error_message").remove();
                if((typeof (fd_data.is_valid) != 'undefined' && fd_data.is_valid == false) || (typeof (fd_data.status) != 'undefined' && fd_data.is_valid == 'ERROR')) {
                    jQuery('.user_guide_fdo').before('<div class="notice notice-error abf_error_message"><p><strong>Error! ' + fd_data.message + '</strong></p></div>');
                }else if(typeof (fd_data.status) != 'undefined' && fd_data.status == 'SUCCESS') {
                    jQuery('.user_guide_fdo').before('<div class="notice notice-success abf_success_message"><p><strong>Success! ' + fd_data.message + '</strong></p></div>');
                    window.location.reload(true);
                }else if(typeof (fd_data.status) != 'undefined' && fd_data.status == 'ERROR') {
                    jQuery('.user_guide_fdo').before('<div class="notice notice-error abf_error_message"><p><strong>Error! ' + fd_data.message + '</strong></p></div>');
                }else if (fd_data.is_valid == 'true') {
                    jQuery('.user_guide_fdo').before('<div class="notice notice-error abf_error_message"><p><strong>Error!</strong> FreightDesk Online ID is not valid.</p></div>');
                } else if (fd_data.is_valid == 'true' && fd_data.is_connected) {
                    jQuery('.user_guide_fdo').before('<div class="notice notice-error abf_error_message"><p><strong>Error!</strong> Your store is already connected with FreightDesk Online.</p></div>');

                } else if (fd_data.is_valid == true && fd_data.is_connected == false && fd_data.redirect_url != null) {
                    window.location = fd_data.redirect_url;
                } else if (fd_data.is_connected == true) {
                    jQuery('#con_dis').empty();
                    jQuery('#con_dis').append('<a href="#" id="fd_online_id" data="disconnect" class="button-primary">Disconnect</a>')
                }
            }
        });
        e.preventDefault();
    });

    var prevent_text_box = jQuery('.prevent_text_box').length;
    if (!prevent_text_box > 0) {
        jQuery("input[name*='wc_pervent_proceed_checkout_eniture']").closest('tr').addClass('wc_pervent_proceed_checkout_eniture');
        
        // backup rates for checkout fields
        jQuery(".wc_pervent_proceed_checkout_eniture input[value*='backup_rates']").after('Allow user to continue to check out with backup rates <br /><input type="text" name="eniture_backup_rates" id="eniture_backup_rates" title="Backup Rates" maxlength="50" value="' + en_abf_admin_script.backup_rates + '"> <br> <span class="description"> Enter a maximum of 50 characters as backup rates label at checkout.</span><br /> <input type="text" name="eniture_backup_rates_amount" id="eniture_backup_rates_amount" title="Backup Rates Amount" maxlength="10" value="' + en_abf_admin_script.backup_rates_amount + '"> <br /> <span class="description"> Enter the amount in $ you want to charge in case API failed to return rates.</span>');

        jQuery(".wc_pervent_proceed_checkout_eniture input[value*='allow']").after('Allow user to continue to check out and display this message <br><textarea  name="allow_proceed_checkout_eniture" class="prevent_text_box" title="Message" maxlength="250">' + en_abf_admin_script.allow_proceed_checkout_eniture + '</textarea><br><span class="description"> Enter a maximum of 250 characters.</span>');
        jQuery(".wc_pervent_proceed_checkout_eniture input[value*='prevent']").after('Prevent user from checking out and display this message<br><textarea name="prevent_proceed_checkout_eniture" class="prevent_text_box" title="Message" maxlength="250">' + en_abf_admin_script.prevent_proceed_checkout_eniture + '</textarea><br><span class="description"> Enter a maximum of 250 characters.</span>');
    }


    /*
     * Save Changes At Quote Section Action
     */

    jQuery('.quote_section_class_abf .button-primary, .quote_section_class_abf .is-primary').on('click', function () {

        jQuery(".updated").hide();
        jQuery('.error').remove();
        var Error = true;

        //              validation for custom error message
        var checkedValCustomMsg = jQuery("input[name='wc_pervent_proceed_checkout_eniture']:checked").val();
        var allow_proceed_checkout_eniture = jQuery("textarea[name=allow_proceed_checkout_eniture]").val();
        var prevent_proceed_checkout_eniture = jQuery("textarea[name=prevent_proceed_checkout_eniture]").val();
        const backup_rates = jQuery('#eniture_backup_rates').val();
        const backup_rates_amount = jQuery('#eniture_backup_rates_amount').val();

        if (checkedValCustomMsg == 'allow' && allow_proceed_checkout_eniture == '') {
            jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_custom_error_message"><p><strong>Custom message field is empty.</strong></p></div>');
            jQuery('html, body').animate({
                'scrollTop': jQuery('.abf_custom_error_message').position().top
            });
            return false;
        } else if (checkedValCustomMsg == 'prevent' && prevent_proceed_checkout_eniture == '') {
            jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_custom_error_message"><p><strong>Custom message field is empty.</strong></p></div>');
            jQuery('html, body').animate({
                'scrollTop': jQuery('.abf_custom_error_message').position().top
            });
            return false;
        } else if (checkedValCustomMsg === 'backup_rates') {
            let errorMsg = ''; 
            if (backup_rates == '') errorMsg = 'Backup rates label field is empty.';
            else if (backup_rates_amount == '') errorMsg = 'Backup rates amount field is empty.';

            if (errorMsg != '') {
                jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_custom_error_message"><p><strong>' + errorMsg + '</strong></p></div>');
                jQuery('html, body').animate({
                    'scrollTop': jQuery('.abf_custom_error_message').position().top
                });
                return false;
            }
        }

        // Validation for handling fee
        var handling_fee = jQuery('#wc_settings_abf_handling_fee').val();
        if (typeof handling_fee !== 'undefined' && handling_fee !== "") {
            if (handling_fee.slice(handling_fee.length - 1) == '%') {
                handling_fee = handling_fee.slice(0, handling_fee.length - 1)
            }
            var handling_fee_regex = /^(-?[0-9]{1,4}%?)$|(\.[0-9]{1,2})%?$/;
            if (typeof handling_fee !== 'undefined' && handling_fee != '' && !handling_fee_regex.test(handling_fee) || typeof handling_fee !== 'undefined' && handling_fee.split('.').length - 1 > 1) {
                jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_handlng_fee_error"><p><strong>Handling fee format should be 100.20 or 10%.</strong></p></div>');
                jQuery('html, body').animate({
                    'scrollTop': jQuery('.abf_handlng_fee_error').position().top
                });
                jQuery("#wc_settings_abf_handling_fee").css({'border-color': '#e81123'});
                return false;
            }

            if (typeof handling_fee !== 'undefined' && isValidNumber(handling_fee) === false) {
                jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_handlng_fee_error"><p><strong>Handling fee format should be 100.2000 or 10%.</strong></p></div>');
                jQuery('html, body').animate({
                    'scrollTop': jQuery('.abf_handlng_fee_error').position().top
                });
                return false;
            } else if (typeof handling_fee !== 'undefined' && isValidNumber(handling_fee) === 'decimal_point_err') {
                jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_handlng_fee_error"><p><strong>Handling fee format should be 100.2000 or 10% and only 4 digits are allowed after decimal.</strong></p></div>');
                jQuery('html, body').animate({
                    'scrollTop': jQuery('.abf_handlng_fee_error').position().top
                });
                return false;
            }
        }

        // Validation for hold at terminal
        var hold_at_terminal_fee = jQuery('#abf_hold_at_terminal_fee').val();
        if (typeof hold_at_terminal_fee !== 'undefined' && hold_at_terminal_fee !== "") {
            var abf_hold_at_terminal_fee_regex = /^(-?[0-9]{1,4}%?)$|(\.[0-9]{1,2})%?$/;
            if (hold_at_terminal_fee.slice(hold_at_terminal_fee.length - 1) == '%') {
                hold_at_terminal_fee = hold_at_terminal_fee.slice(0, hold_at_terminal_fee.length - 1)
            }
            if (hold_at_terminal_fee === "") {
                return true;
            } else {
                if (isValidNumber2Decimal(hold_at_terminal_fee) === false) {
                    jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_HAT_fee_error"><p><strong>Hold At Terminal fee format should be 100.20 or 10%.</strong></p></div>');
                    jQuery('html, body').animate({
                        'scrollTop': jQuery('.abf_HAT_fee_error').position().top
                    });
                    return false;
                } else if (isValidNumber2Decimal(hold_at_terminal_fee) === 'decimal_point_err') {
                    jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_HAT_fee_error"><p><strong>Hold At Terminal fee format should be 100.20 or 10% and only 2 digits are allowed after decimal.</strong></p></div>');
                    jQuery('html, body').animate({
                        'scrollTop': jQuery('.abf_HAT_fee_error').position().top
                    });
                    return false;
                } else {
                    return true;
                }
            }
        }

        //start new function
        var abf_freight_shipmentOffsetDays = jQuery("#abf_freight_shipmentOffsetDays").val();
        var abfnumberRegex = /^[0-9]+$/;
        if (abf_freight_shipmentOffsetDays != "" && abf_freight_shipmentOffsetDays < 1) {

            jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_freight_orderCutoffTime_error"><p><strong>Days should not be less than 1.</strong></p></div>');
            jQuery('html, body').animate({
                'scrollTop': jQuery('.abf_freight_orderCutoffTime_error').position().top
            });
            return false;
        }
        if (abf_freight_shipmentOffsetDays != "" && abf_freight_shipmentOffsetDays > 8) {

            jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_freight_orderCutoffTime_error"><p><strong>Days should not be greater than 8.</strong></p></div>');
            jQuery('html, body').animate({
                'scrollTop': jQuery('.abf_freight_orderCutoffTime_error').position().top
            });
            return false;

        }

        if (abf_freight_shipmentOffsetDays != "" && !abfnumberRegex.test(abf_freight_shipmentOffsetDays)) {

            jQuery("#mainform .quote_section_class_abf").prepend('<div id="message" class="error inline abf_freight_orderCutoffTime_error"><p><strong> Entered Days are not valid.</strong></p></div>');
            jQuery('html, body').animate({
                'scrollTop': jQuery('.abf_freight_orderCutoffTime_error').position().top
            });
            return false;
        }

        return Error;

    });

    // JS for edit product nested fields
    jQuery("._nestedMaterials").closest('p').addClass("_nestedMaterials_tr");
    jQuery("._nestedPercentage").closest('p').addClass("_nestedPercentage_tr");
    jQuery("._maxNestedItems").closest('p').addClass("_maxNestedItems_tr");
    jQuery("._nestedDimension").closest('p').addClass("_nestedDimension_tr");
    jQuery("._nestedStakingProperty").closest('p').addClass("_nestedStakingProperty_tr");

    if (!jQuery('._nestedMaterials').is(":checked")) {
        jQuery('._nestedPercentage_tr').hide();
        jQuery('._nestedDimension_tr').hide();
        jQuery('._maxNestedItems_tr').hide();
        jQuery('._nestedDimension_tr').hide();
        jQuery('._nestedStakingProperty_tr').hide();
    } else {
        jQuery('._nestedPercentage_tr').show();
        jQuery('._nestedDimension_tr').show();
        jQuery('._maxNestedItems_tr').show();
        jQuery('._nestedDimension_tr').show();
        jQuery('._nestedStakingProperty_tr').show();
    }

    jQuery("._nestedPercentage").attr('min', '0');
    jQuery("._maxNestedItems").attr('min', '0');
    jQuery("._nestedPercentage").attr('max', '100');
    jQuery("._maxNestedItems").attr('max', '100');
    jQuery("._nestedPercentage").attr('maxlength', '3');
    jQuery("._maxNestedItems").attr('maxlength', '3');

    if (jQuery("._nestedPercentage").val() == '') {
        jQuery("._nestedPercentage").val(0);
    }

    jQuery("._nestedPercentage").keydown(function (eve) {
        Abf_LFQ_stopSpecialCharacters(eve);
        var nestedPercentage = jQuery('._nestedPercentage').val();
        if (nestedPercentage.length == 2) {
            var newValue = nestedPercentage + '' + eve.key;
            if (newValue > 100) {
                return false;
            }
        }
    });

    jQuery("._maxNestedItems").keydown(function (eve) {
        Abf_LFQ_stopSpecialCharacters(eve);
    });

    jQuery("._nestedMaterials").change(function () {
        if (!jQuery('._nestedMaterials').is(":checked")) {
            jQuery('._nestedPercentage_tr').hide();
            jQuery('._nestedDimension_tr').hide();
            jQuery('._maxNestedItems_tr').hide();
            jQuery('._nestedDimension_tr').hide();
            jQuery('._nestedStakingProperty_tr').hide();
        } else {
            jQuery('._nestedPercentage_tr').show();
            jQuery('._nestedDimension_tr').show();
            jQuery('._maxNestedItems_tr').show();
            jQuery('._nestedDimension_tr').show();
            jQuery('._nestedStakingProperty_tr').show();
        }
    });

    var abf_delivery_estimate = jQuery('input[name=abf_delivery_estimates]:checked').val();
    if (abf_delivery_estimate == undefined) {
        jQuery('.abf_dont_show_estimate_option').prop("checked", true);
    }

    //** Start: Validat Shipment Offset Days
    jQuery("#abf_freight_shipmentOffsetDays").keydown(function (e) {
        if (e.keyCode == 8)
            return;

        var val = jQuery("#abf_freight_shipmentOffsetDays").val();
        if (val.length > 1 || e.keyCode == 190) {
            e.preventDefault();
        }
        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 53, 189]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }

    });
    // Allow: only positive numbers
    jQuery("#abf_freight_shipmentOffsetDays").keyup(function (e) {
        if (e.keyCode == 189) {
            e.preventDefault();
            jQuery("#abf_freight_shipmentOffsetDays").val('');
        }

    });
    // Add class in tr and remove border from abf handling fee
    jQuery('#wc_settings_abf_handling_fee').closest('tr').addClass('wc_settings_abf_handling_fee_tr')
    jQuery('.wc_settings_abf_handling_fee_tr').on('click', function (event) {
        jQuery('#wc_settings_abf_handling_fee').css('border', '');

    });
    
    // limited access delivery
    jQuery("#abf_limited_access_delivery").closest('tr').addClass("abf_limited_access_delivery");
    jQuery("#abf_limited_access_delivery_as_option").closest('tr').addClass("abf_limited_access_delivery_as_option");
    jQuery("#abf_limited_access_delivery_fee").closest('tr').addClass("abf_limited_access_delivery_fee");

    // limited access
    jQuery(".limited_access_add").on("click", function () {
        var id = jQuery(this).attr("id");
        
        if (id == 'abf_limited_access_delivery') {
			jQuery('#abf_limited_access_delivery_as_option').prop({ checked: false });
			
            if (jQuery('#abf_limited_access_delivery').prop('checked') == true) {
                jQuery('.abf_limited_access_delivery_fee').css('display', '');
            }
		} else if (id == 'abf_limited_access_delivery_as_option') {
            jQuery('#abf_limited_access_delivery').prop({ checked: false });
            
			if (jQuery('#abf_limited_access_delivery_as_option').prop('checked') == true) {
				jQuery('.abf_limited_access_delivery_fee').css('display', '');
			}
		}
        if (jQuery("#abf_limited_access_delivery_as_option").prop("checked") == false &&
            jQuery("#abf_limited_access_delivery").prop("checked") == false) {
            jQuery('.abf_limited_access_delivery_fee').css('display', 'none');
        }
    });

    if (jQuery("#abf_limited_access_delivery_as_option").prop("checked") == false &&
        jQuery("#abf_limited_access_delivery").prop("checked") == false) {
        jQuery('.abf_limited_access_delivery_fee').css('display', 'none');
    }

    // limited access delivery fee
    jQuery("#abf_limited_access_delivery_fee, #eniture_backup_rates_amount").keypress(function (e) {
        if (!String.fromCharCode(e.keyCode).match(/^[0-9\d\.\s]+$/i)) return false;
    });

    jQuery('#abf_limited_access_delivery_fee').keyup(function () {
		var val = jQuery(this).val();
		if (val.length > 7) {
			val = val.substring(0, 7);
			jQuery(this).val(val);
		}
	});

    jQuery('#abf_limited_access_delivery_fee, #eniture_backup_rates_amount').keyup(function () {
		var val = jQuery(this).val();
		var regex = /\./g;
		var count = (val.match(regex) || []).length;
		
        if (count > 1) {
			val = val.replace(/\.+$/, '');
			jQuery(this).val(val);
		}
    });
    
    
    // Product variants settings
    jQuery(document).on("click", '._nestedMaterials', function(e) {
        const checkbox_class = jQuery(e.target).attr("class");
        const name = jQuery(e.target).attr("name");
        const checked = jQuery(e.target).prop('checked');

        if (checkbox_class?.includes('_nestedMaterials')) {
            const id = name?.split('_nestedMaterials')[1];
            setNestMatDisplay(id, checked);
        }
    });

});

// Weight threshold for LTL freight
if (typeof en_weight_threshold_limit != 'function') {
    function en_weight_threshold_limit() {
        // Weight threshold for LTL freight
        jQuery("#en_weight_threshold_lfq").keypress(function (e) {
            if (String.fromCharCode(e.keyCode).match(/[^0-9]/g) || !jQuery("#en_weight_threshold_lfq").val().match(/^\d{0,3}$/)) return false;
        });

        jQuery('#en_plugins_return_LTL_quotes').on('change', function () {
            if (jQuery('#en_plugins_return_LTL_quotes').prop("checked")) {
                jQuery('tr.en_weight_threshold_lfq').show();
                jQuery('tr.en_only_show_ltl_rates_when_weight_exceeds').show();
            } else {
                jQuery('tr.en_weight_threshold_lfq').hide();
                jQuery('tr.en_only_show_ltl_rates_when_weight_exceeds').hide();
            }
        });

        jQuery("#en_plugins_return_LTL_quotes").closest('tr').addClass("en_plugins_return_LTL_quotes_tr");
        // Weight threshold for LTL freight
        var weight_threshold_class = jQuery("#en_weight_threshold_lfq").attr("class");
        jQuery("#en_weight_threshold_lfq").closest('tr').addClass("en_weight_threshold_lfq " + weight_threshold_class);

        var en_only_show_ltl_rates_class = jQuery("#en_only_show_ltl_rates_when_weight_exceeds").attr("class");
        jQuery("#en_only_show_ltl_rates_when_weight_exceeds").closest('tr').addClass("en_only_show_ltl_rates_when_weight_exceeds " + en_only_show_ltl_rates_class);

        // Weight threshold for LTL freight is empty
        if (jQuery('#en_weight_threshold_lfq').length && !jQuery('#en_weight_threshold_lfq').val().length > 0) {
            jQuery('#en_weight_threshold_lfq').val(150);
        }
    }
}

// Nesting start
function Abf_LFQ_stopSpecialCharacters(e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if (jQuery.inArray(e.keyCode, [46, 9, 27, 13, 110, 190, 189]) !== -1 ||
        // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
        // let it happen, don't do anything
        e.preventDefault();
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 90)) && (e.keyCode < 96 || e.keyCode > 105) && e.keyCode != 186 && e.keyCode != 8) {
        e.preventDefault();
    }
    if (e.keyCode == 186 || e.keyCode == 190 || e.keyCode == 189 || (e.keyCode > 64 && e.keyCode < 91)) {
        e.preventDefault();
        return;
    }
}

/*
* Validate Input If Empty or Invalid
*/
function validateInput(form_id) {
    var has_err = true;
    jQuery(form_id + " input[type='text']").each(function () {
        var input = jQuery(this).val();
        var response = validateString(input);

        var errorElement = jQuery(this).parent().find('.err');
        jQuery(errorElement).html('');
        var errorText = jQuery(this).attr('title');
        var optional = jQuery(this).data('optional');
        optional = (optional === undefined) ? 0 : 1;
        errorText = (errorText != undefined) ? errorText : '';
        if ((optional == 0) && (response == false || response == 'empty')) {
            errorText = (response == 'empty') ? errorText + ' is required.' : 'Invalid input.';
            jQuery(errorElement).html(errorText);
        }
        has_err = (response != true && optional == 0) ? false : has_err;
    });
    return has_err;
}

function isValidNumber(value, noNegative) {
    if (typeof (noNegative) === 'undefined')
        noNegative = false;
    var isValidNumber = false;
    var validNumber = (noNegative == true) ? parseFloat(value) >= 0 : true;
    if ((value == parseInt(value) || value == parseFloat(value)) && (validNumber)) {
        if (value.indexOf(".") >= 0) {
            var n = value.split(".");
            if (n[n.length - 1].length <= 4) {
                isValidNumber = true;
            } else {
                isValidNumber = 'decimal_point_err';
            }
        } else {
            isValidNumber = true;
        }
    }
    return isValidNumber;
}

function isValidNumber2Decimal(value, noNegative) {
    if (typeof (noNegative) === 'undefined')
        noNegative = false;
    var isValidNumber = false;
    var validNumber = (noNegative == true) ? parseFloat(value) >= 0 : true;
    if ((value == parseInt(value) || value == parseFloat(value)) && (validNumber)) {
        if (value.indexOf(".") >= 0) {
            var n = value.split(".");
            if (n[n.length - 1].length <= 2) {
                isValidNumber = true;
            } else {
                isValidNumber = 'decimal_point_err';
            }
        } else {
            isValidNumber = true;
        }
    }
    return isValidNumber;
}

/*
 * Check Input Value Is Not String
 */
function validateString(string) {
    if (string == '') {
        return 'empty';
    } else {
        return true;
    }
}

/**
 * Read a page's GET URL variables and return them as an associative array.
 */
function getUrlVarsAbfFreight() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

// Update plan
if (typeof en_update_plan != 'function') {
    function en_update_plan(input) {
        let action = jQuery(input).attr('data-action');
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: {action: action},
            success: function (data_response) {
                window.location.reload(true);
            }
        });
    }
}

if (typeof setNestedMaterialsUI != 'function') {
    function setNestedMaterialsUI() {
        const nestedMaterials = jQuery('._nestedMaterials');
        const productMarkups = jQuery('._en_product_markup');
        
        if (productMarkups?.length) {
            for (const markup of productMarkups) {
                jQuery(markup).attr('maxlength', '7');

                jQuery(markup).keypress(function (e) {
                    if (!String.fromCharCode(e.keyCode).match(/^[0-9.%-]+$/))
                        return false;
                });
            }
        }

        if (nestedMaterials?.length) {
            for (let elem of nestedMaterials) {
                const className = elem.className;

                if (className?.includes('_nestedMaterials')) {
                    const checked = jQuery(elem).prop('checked'),
                        name = jQuery(elem).attr('name'),
                        id = name?.split('_nestedMaterials')[1];
                    setNestMatDisplay(id, checked);
                }
            }
        }
    }
}

if (typeof setNestMatDisplay != 'function') {
    function setNestMatDisplay (id, checked) {
        
        jQuery(`input[name="_nestedPercentage${id}"]`).attr('min', '0');
        jQuery(`input[name="_nestedPercentage${id}"]`).attr('max', '100');
        jQuery(`input[name="_nestedPercentage${id}"]`).attr('maxlength', '3');
        jQuery(`input[name="_maxNestedItems${id}"]`).attr('min', '0');
        jQuery(`input[name="_maxNestedItems${id}"]`).attr('max', '100');
        jQuery(`input[name="_maxNestedItems${id}"]`).attr('maxlength', '3');

        jQuery(`input[name="_nestedPercentage${id}"], input[name="_maxNestedItems${id}"]`).keypress(function (e) {
            if (!String.fromCharCode(e.keyCode).match(/^[0-9]+$/))
                return false;
        });

        jQuery(`input[name="_nestedPercentage${id}"]`).closest('p').css('display', checked ? '' : 'none');
        jQuery(`select[name="_nestedDimension${id}"]`).closest('p').css('display', checked ? '' : 'none');
        jQuery(`input[name="_maxNestedItems${id}"]`).closest('p').css('display', checked ? '' : 'none');
        jQuery(`select[name="_nestedStakingProperty${id}"]`).closest('p').css('display', checked ? '' : 'none');
    }
}
