/**
 *  Warehouse Section Script Start
 * @package     Woocommerce ABF Edition
 * @author      <https://eniture.com/>
 * @copyright   Copyright (c) 2017, Eniture
 */

/**
 * New Warehouse Save Function
 * @returns Boolean
 */    
function abf_save_warehouse()
{
    var city     = jQuery('#abf_origin_city').val();

    var zip_err     = false;
    var city_err    = false;
    var state_err   = false;
    var country_err = false;
    if ( jQuery('#abf_origin_zip' ).val() === '')
    {
        jQuery( '.zip_invalid' ).hide();
        jQuery( '.abf_zip_validation_err' ).remove();
        jQuery( '#abf_origin_zip' ).after('<span class="abf_zip_validation_err">Zip is required.</span>');
        zip_err = 1; 
    }

    if ( city === '')
    {
        jQuery( '.abf_city_validation_err' ).remove();
        jQuery( '#abf_origin_city' ).after('<span class="abf_city_validation_err">City is required.</span>');
        city_err = 1;
    }

    if ( jQuery('#abf_origin_state' ).val() === '')
    {
        jQuery( '.abf_state_validation_err' ).remove();
        jQuery( '#abf_origin_state' ).after('<span class="abf_state_validation_err">State is required.</span>');
        state_err = 1;
    }

    if ( jQuery('#abf_origin_country' ).val() === '')
    {
        jQuery( '.abf_country_validation_err' ).remove();
        jQuery( '#abf_origin_country' ).after('<span class="abf_country_validation_err">Country is required.</span>');
        country_err = 1;
    }

    if (zip_err || city_err || state_err || country_err) {
        return false;
    }

   var postForm = {
        'action'                : 'abf_save_warehouse',
        'origin_id'             : jQuery('#edit_form_id').val(),
        'origin_city'           : city,
        'origin_state'          : jQuery('#abf_origin_state').val(),
        'origin_zip'            : jQuery('#abf_origin_zip').val(),
        'origin_country'        : jQuery('#abf_origin_country').val(),
        'location'              : jQuery('#abf_location').val(),
    };

    jQuery.ajax({
        type: 'POST', 
        url: ajaxurl, 
        data: postForm, 
        dataType: 'json',

        success: function (data) 
        {
            var WarehpuseDataId = data.id;

            /*
             * Append New Warehouse Row
             */

                if (data.insert_qry == 1) {
                    jQuery( '.warehouse_created' ).show( 'slow' ).delay(5000).hide('slow');
                    jQuery( '.warehouse_updated' ).css( 'display' , 'none' );
                    jQuery( '.warehouse_deleted' ).css( 'display' , 'none' );
                    jQuery( '.dropship_deleted' ).css( 'display' , 'none' );
                    jQuery( '.dropship_updated' ).css( 'display' , 'none' );
                    jQuery( '.dropship_created' ).css( 'display' , 'none' );
                    window.location.href = jQuery( '.close' ).attr( 'href' );
                    jQuery( '#append_warehouse tr:last' ).after( '<tr class="new_warehouse_add" id="row_'+WarehpuseDataId+'" data-id="'+WarehpuseDataId+'"><td class="abf_warehouse_list_data">'+data.origin_city+'</td><td class="abf_warehouse_list_data">'+data.origin_state+'</td><td class="abf_warehouse_list_data">'+data.origin_zip+'</td><td class="abf_warehouse_list_data">'+data.origin_country+'</td><td class="abf_warehouse_list_data"><a href="javascript(0)" title="Edit" onclick="return abf_edit_warehouse('+WarehpuseDataId+')"><img src="'+script.pluginsUrl+'/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/edit.png"></a><a href="javascript(0)" title="Delete" onclick="return abf_delete_current_warehouse('+ WarehpuseDataId +');"><img src="'+script.pluginsUrl+'/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/delete.png"></a></td></tr>' );
                }

            /*
             * Updated Existed Warehouse Row
             */

                else if(data.update_qry == 1){
                    jQuery( '.warehouse_updated' ).show( 'slow' ).delay(5000).hide('slow');
                    jQuery( '.warehouse_created' ).css( 'display' , 'none' );
                    jQuery( '.warehouse_deleted' ).css( 'display' , 'none' );
                    jQuery( '.dropship_deleted' ).css( 'display' , 'none' );
                    jQuery( '.dropship_updated' ).css( 'display' , 'none' );
                    jQuery( '.dropship_created' ).css( 'display' , 'none' );
                    window.location.href = jQuery( '.close' ).attr( 'href' );
                    jQuery( 'tr[id=row_'+WarehpuseDataId+']' ).html( '<td class="abf_warehouse_list_data">'+data.origin_city+'</td><td class="abf_warehouse_list_data">'+data.origin_state+'</td><td class="abf_warehouse_list_data">'+data.origin_zip+'</td><td class="abf_warehouse_list_data">'+data.origin_country+'</td><td class="abf_warehouse_list_data"><a href="javascript(0)" title="Edit" onclick="return abf_edit_warehouse('+WarehpuseDataId+')"><img src="'+script.pluginsUrl+'/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/edit.png"></a><a href="javascript(0)" title="Delete" onclick="return abf_delete_current_warehouse('+ WarehpuseDataId +');"><img src="'+script.pluginsUrl+'/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/delete.png"></a></td>' );
                }

            /*
             * Error Msg If Invalid Input Data
             */  

                else if( data == false ) {
                    jQuery( '.warehouse_invalid_input_message' ).show( 'slow' );
                    setTimeout( function () {
                            jQuery( '.warehouse_invalid_input_message' ).hide( 'slow' );
                        }, 5000);
                }

            /*
             * Error Msg If Warehouse Already Exist
             */        

                else{
                    jQuery('.already_exist').show('slow');
                    setTimeout(function () {
                            jQuery('.already_exist').hide('slow');
                        }, 5000);
                }
        },
    }); return false;
}

/**
 * Delete Selected Warehouse Function
 * @param {type} e
 * @returns {Boolean}
 */
function abf_delete_current_warehouse(e)
{
    var postForm = {
        'action'                : 'abf_delete_warehouse',
        'delete_id'             : e,
    };
    jQuery.ajax({
        type: 'POST', 
        url: ajaxurl, 
        data: postForm, 
        dataType: 'json',

        success: function (data) 
        {
            if (data == 1) {
                jQuery('#row_'+e).remove();
                jQuery( '.warehouse_deleted' ).show( 'slow' ).delay(5000).hide('slow');
                jQuery( '.warehouse_updated' ).css( 'display' , 'none' );
                jQuery( '.warehouse_created' ).css( 'display' , 'none' );
                jQuery( '.dropship_deleted' ).css( 'display' , 'none' );
                jQuery( '.dropship_updated' ).css( 'display' , 'none' );
                jQuery( '.dropship_created' ).css( 'display' , 'none' );
            }
        },
    }); return false;
}

/**
 * Edit Warehouse Row
 * @param {type} e
 * @returns {Boolean}
 */
function abf_edit_warehouse(e)
{
    var postForm = {
        'action'                : 'abf_edit_warehouse',
        'edit_id'               : e,
    };
    jQuery.ajax({
        type: 'POST', 
        url: ajaxurl, 
        data: postForm, 
        dataType: 'json',

        success: function (data) 
        {   
            if (data[0]) {
                jQuery( '#edit_form_id' ).val( data[0].id );
                jQuery( '#abf_origin_zip' ).val( data[0].zip );
                jQuery( '.city_select' ).hide();
                jQuery( '.city_input' ).show();
                jQuery( '#abf_origin_city' ).val( data[0].city );
                jQuery( '#abf_origin_city' ).css('background', 'none');
                jQuery( '#abf_origin_state' ).val( data[0].state );
                jQuery( '#abf_origin_country' ).val( data[0].country );

                jQuery( '.abf_zip_validation_err' ).hide();
                jQuery( '.abf_city_validation_err' ).hide();
                jQuery( '.abf_state_validation_err' ).hide();
                jQuery( '.abf_country_validation_err' ).hide();

                window.location.href = jQuery('.abf_add_warehouse_btn').attr('href');
                jQuery( '.already_exist' ).hide();
                setTimeout(function(){
                    if(jQuery('.abf_add_warehouse_popup').is(':visible')){
                      jQuery('.abf_add_warehouse_input > input').eq(0).focus();
                    }
                  },500);
            }
        },

    }); return false;
}

/**
 * New Drop Ship Save Function
 * @returns {Boolean}
 */
function save_abf_dropship()
{ 
    var city     = jQuery('#abf_dropship_city').val();

    var zip_err     = false;
    var city_err    = false;
    var state_err   = false;
    var country_err = false;
    if ( jQuery('#abf_dropship_zip' ).val() === '')
    {
        jQuery( '.zip_invalid' ).hide();
        jQuery( '.abf_zip_validation_err' ).remove();
        jQuery( '#abf_dropship_zip' ).after('<span class="abf_zip_validation_err">Zip is required.</span>');
        zip_err = 1; 
    }

    if ( city === '')
    {
        jQuery( '.abf_city_validation_err' ).remove();
        jQuery( '#abf_dropship_city' ).after('<span class="abf_city_validation_err">City is required.</span>');
        city_err = 1;
    }

    if ( jQuery('#abf_dropship_state' ).val() === '')
    {
        jQuery( '.abf_state_validation_err' ).remove();
        jQuery( '#abf_dropship_state' ).after('<span class="abf_state_validation_err">State is required.</span>');
        state_err = 1;
    }

    if ( jQuery('#abf_dropship_country' ).val() === '')
    {
        jQuery( '.abf_country_validation_err' ).remove();
        jQuery( '#abf_dropship_country' ).after('<span class="abf_country_validation_err">Country is required.</span>');
        country_err = 1;
    }

    if (zip_err || city_err || state_err || country_err) {
        return false;
    }

    var postForm = {
        'action'                : 'abf_save_dropship',
        'dropship_id'             : jQuery( '#edit_dropship_form_id' ).val(),
        'dropship_city'           : city,
        'dropship_state'          : jQuery( '#abf_dropship_state' ).val(),
        'dropship_zip'            : jQuery( '#abf_dropship_zip' ).val(),
        'dropship_country'        : jQuery( '#abf_dropship_country' ).val(),
        'nickname'                : jQuery( '#abf_dropship_nickname' ).val(),
        'location'                : jQuery( '#abf_dropship_location' ).val(),
    };

    jQuery.ajax({
        type: 'POST', 
        url: ajaxurl, 
        data: postForm, 
        dataType: 'json',

        success: function (data) 
        {
            var WarehpuseDataId = data.id;

            /*
             * Append New Drop Ship Row
             */

            if (data.insert_qry == 1) {
                jQuery( '.dropship_created' ).show( 'slow' ).delay(5000).hide('slow');
                jQuery( '.dropship_updated' ).css( 'display' , 'none' );
                jQuery( '.dropship_deleted' ).css( 'display' , 'none' );
                jQuery( '.warehouse_deleted' ).css( 'display' , 'none' );
                jQuery( '.warehouse_updated' ).css( 'display' , 'none' );
                jQuery( '.warehouse_created' ).css( 'display' , 'none' );
                window.location.href = jQuery( '.close' ).attr( 'href' );
                jQuery( '#append_dropship tr:last' ).after( '<tr class="new_dropship_add" id="row_'+WarehpuseDataId+'" data-id="'+WarehpuseDataId+'"><td class="abf_dropship_list_data">'+data.nickname+'</td><td class="abf_dropship_list_data">'+data.origin_city+'</td><td class="abf_dropship_list_data">'+data.origin_state+'</td><td class="abf_dropship_list_data">'+data.origin_zip+'</td><td class="abf_dropship_list_data">'+data.origin_country+'</td><td class="abf_dropship_list_data"><a href="javascript(0)" title="Edit" onclick="return abf_edit_dropship('+WarehpuseDataId+')"><img src="'+script.pluginsUrl+'/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/edit.png"></a><a href="javascript(0)" title="Delete" onclick="return abf_delete_current_dropship('+ WarehpuseDataId +');"><img src="'+script.pluginsUrl+'/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/delete.png"></a></td></tr>' );
            }

            /*
             * Updated Existed Warehouse Row
             */

            else if(data.update_qry == 1){
                jQuery( '.dropship_updated' ).show( 'slow' ).delay(5000).hide('slow');
                jQuery( '.dropship_created' ).css( 'display' , 'none' );
                jQuery( '.dropship_deleted' ).css( 'display' , 'none' );
                jQuery( '.warehouse_deleted' ).css( 'display' , 'none' );
                jQuery( '.warehouse_updated' ).css( 'display' , 'none' );
                jQuery( '.warehouse_created' ).css( 'display' , 'none' );
                window.location.href = jQuery( '.close' ).attr( 'href' );
                jQuery( 'tr[id=row_'+WarehpuseDataId+']' ).html( '<td class="abf_dropship_list_data">'+data.nickname+'</td><td class="abf_dropship_list_data">'+data.origin_city+'</td><td class="abf_dropship_list_data">'+data.origin_state+'</td><td class="abf_dropship_list_data">'+data.origin_zip+'</td><td class="abf_dropship_list_data">'+data.origin_country+'</td><td class="abf_dropship_list_data"><a href="javascript(0)" title="Edit" onclick="return abf_edit_dropship('+WarehpuseDataId+')"><img src="'+script.pluginsUrl+'/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/edit.png"></a><a href="javascript(0)" title="Delete" onclick="return abf_delete_current_dropship('+ WarehpuseDataId +');"><img src="'+script.pluginsUrl+'/ltl-freight-quotes-abf-freight-edition/warehouse-dropship/wild/assets/images/delete.png"></a></td>' );
            }

        /*
         * Error Msg If Invalid Input Data
         */  

            else if( data == false ) {
                jQuery( '.warehouse_invalid_input_message' ).show( 'slow' );
                setTimeout( function () {
                        jQuery( '.warehouse_invalid_input_message' ).hide( 'slow' );
                    }, 5000);
            }

        /*
         * Error Msg If Warehouse Already Exist
         */

            else{
                jQuery('.already_exist').show('slow');
                setTimeout(function () {
                        jQuery('.already_exist').hide('slow');
                    }, 5000);
            }
        },

    }); return false;
}

/**
 * Edit Drop Ship Row
 * @param {type} e
 * @returns {Boolean}
 */
function abf_edit_dropship(e)
{
    var postForm = {
        'action'                : 'abf_edit_dropship',
        'dropship_edit_id'      : e,
    };
    jQuery.ajax({
        type: 'POST', 
        url: ajaxurl, 
        data: postForm, 
        dataType: 'json',

        success: function (data) 
        {
            /*
             * Popup With Row Data
             * Data Shows In Input Fields
             */

            if (data[0]) {
                jQuery( '#edit_dropship_form_id' ).val( data[0].id );
                jQuery( '#abf_dropship_zip' ).val( data[0].zip );
                jQuery( '#abf_dropship_nickname' ).val( data[0].nickname );
                jQuery( '.city_select' ).hide();
                jQuery( '.city_input' ).show();
                jQuery( '#abf_dropship_city' ).val( data[0].city );
                jQuery( '#abf_dropship_city' ).css('background', 'none'); 
                jQuery( '#abf_dropship_state' ).val( data[0].state );
                jQuery( '#abf_dropship_country' ).val( data[0].country );

                jQuery( '.abf_zip_validation_err' ).hide();
                jQuery( '.abf_city_validation_err' ).hide();
                jQuery( '.abf_state_validation_err' ).hide();
                jQuery( '.abf_country_validation_err' ).hide();

                window.location.href = jQuery('.abf_add_dropship_btn').attr('href');
                jQuery( '.already_exist' ).hide();
                setTimeout(function(){
                    if(jQuery('.ds_popup').is(':visible')){
                      jQuery('.ds_input > input').eq(0).focus();
                    }
                  },500);
            }
        },

    }); return false;
}
  
/**
 * Confirm Delete Drop Ship Popup
 * @param {type} e
 * @returns {Boolean}
 */
function abf_delete_current_dropship(e)
{
    var id = e;
    window.location.href = jQuery('.abf_delete_dropship_btn').attr('href');
    jQuery('.cancel_delete').on('click', function(){
        window.location.href = jQuery('.cancel_delete').attr('href');
    });
    jQuery('.confirm_delete').on('click', function(){
        window.location.href = jQuery('.confirm_delete').attr('href');
        return confirm_abf_delete_dropship(id);
    });
    return false;
}

/**
 * Delete Drop Ship Row
 * @param {type} e
 * @returns {Boolean}
 */
function confirm_abf_delete_dropship(e)
{
    var postForm = {
        'action'                : 'abf_delete_dropship',
        'dropship_delete_id'    : e,
    };
    jQuery.ajax({
        type: 'POST', 
        url: ajaxurl, 
        data: postForm, 
        dataType: 'json',

        success: function (data) 
        {
            if (data == 1) {
                jQuery('#row_'+e).remove();
                jQuery( '.dropship_deleted' ).show( 'slow' ).delay(5000).hide('slow');
                jQuery( '.dropship_updated' ).css( 'display' , 'none' );
                jQuery( '.dropship_created' ).css( 'display' , 'none' );
                jQuery( '.warehouse_deleted' ).css( 'display' , 'none' );
                jQuery( '.warehouse_updated' ).css( 'display' , 'none' );
                jQuery( '.warehouse_created' ).css( 'display' , 'none' );
            }
        },

    }); return false;
}