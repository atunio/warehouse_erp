
$(document).ready(function() {
    $('#product_id').on('change', function() {
        $(".package_material_qty").hide(); 
        $('#product_id_for_package_material').val($(this).val());
        data = [];
        data[0] = product_id_for_package_material; // source field name
        data[1] = 'package_id'; // target field
        data[2] = null;
        data[3] = null;
        data[4] = null;
        generate_combo_new(data);
    });  

    
    $('#package_id').on('change', function() {
       
        var package_id = $(this).val();
        var order_qty = $("#order_qty").val();
         if(package_id !="" && package_id != '0'){
            $(".package_material_qty").show(); 
            $('#package_material_qty').val(order_qty).focus(); // Set value and focus
        }
        else{
            $(".package_material_qty").hide(); 
        }
    });


    $('#status_id_rma').on('change', function() {
        
        $("#tracking_no_rma").val(''); 
        $('#sub_location_id_barcode_rma').val('').trigger('change');
        $('#repair_type').val('').trigger('change');

        var status_id_rma = $(this).val();
        if(status_id_rma == '' || status_id_rma == null){
            $(".tracking_no_rma").hide();
            $(".repair_type").hide();
            $(".sub_location_id_barcode_rma").hide(); 
            $(".new_value").hide(); 
        }
		else if(status_id_rma == '19' || status_id_rma == '18' || status_id_rma == '22' || status_id_rma == '23' || status_id_rma == '24'){
            if(status_id_rma == '19'){
                $(".tracking_no_rma").hide();
                $(".repair_type").show();  
            }
            else{
                $(".tracking_no_rma").hide();
                $(".repair_type").hide();  
            }
            $(".new_value").show();
            $(".sub_location_id_barcode_rma").show();
        }
        else{
            $(".tracking_no_rma").show();
            $(".repair_type").hide();
            $(".sub_location_id_barcode_rma").hide();
            $(".new_value").hide(); 
            
        }
    }); 
    $('.status_id_rma_record').on('change', function() {
        var id = $(this).attr('id');
        var value = $(this).val();

		var dataString = 'source_field=status_id_rma_record&source_field_val='+value+'&id=' + id;
		$.ajax({
			type: "POST",
			url: "ajax/generate_combo.php",
			data: dataString,
			cache: false,
			success: function(data) {
                $("#checkbox_no_"+id).hide();
			},
			error: function() {
			}
        });
    }); 
    $(".add_serial_no_manual_diagnostic").click(function() {  
        var id = $(this).attr('id');
        var array               = id.split("^");
        var inputno             = parseInt(array[1]);
        var next_input_no       = inputno+1;
        var previous_input_no   = inputno-1;
        
        $(".add_serial_no_manual_diagnostic").hide();
        $(".serial_no_manual_diagnostic_input_"+next_input_no).show();
        $("#button_div_serial_no_manual_diagnostic_"+next_input_no).show();
        $(".add_serial_no_manual_diagnostic_"+next_input_no).show();
        $(".minus_serial_no_manual_diagnostic_"+next_input_no).show();
    });
    
    
    $(".minus_serial_no_manual_diagnostic").click(function() {  
        var id = $(this).attr('id');
        var array               = id.split("^");
        var inputno             = parseInt(array[1]);
        var next_input_no       = inputno+1;
        var previous_input_no   = inputno-1;

        $(".add_serial_no_manual_diagnostic_"+previous_input_no).show();
        
         $(".serial_no_manual_diagnostic_input_"+inputno).hide();
        $("#button_div_serial_no_manual_diagnostic_"+inputno).hide();
        $(".add_serial_no_manual_diagnostic_"+inputno).hide();
        $(".minus_serial_no_manual_diagnostic_"+inputno).hide();
        $("#serial_no_manual_diagnostic_"+inputno).val('');
        
    });
    
    $(".add_serial_no_manual").click(function() {  
        var id = $(this).attr('id');
        var array               = id.split("^");
        var inputno             = parseInt(array[1]);
        var next_input_no       = inputno+1;
        var previous_input_no   = inputno-1;
        
        $(".add_serial_no_manual").hide();
        $(".serial_no_manual_input_"+next_input_no).show();
        $("#button_div_serial_no_manual_"+next_input_no).show();
        $(".add_serial_no_manual_"+next_input_no).show();
        $(".minus_serial_no_manual_"+next_input_no).show();
    });
    
    
    $(".minus_serial_no_manual").click(function() {  
        var id = $(this).attr('id');
        var array               = id.split("^");
        var inputno             = parseInt(array[1]);
        var next_input_no       = inputno+1;
        var previous_input_no   = inputno-1;

        $(".add_serial_no_manual_"+previous_input_no).show();
        
         $(".serial_no_manual_input_"+inputno).hide();
        $("#button_div_serial_no_manual_"+inputno).hide();
        $(".add_serial_no_manual_"+inputno).hide();
        $(".minus_serial_no_manual_"+inputno).hide();
        $("#serial_no_manual_"+inputno).val('');
        
    });

    /////////////////////////////////////////////
    $(".show_receive_from_barcode_section").click(function() {  
        $("#receive_from_barcode_section").show();
        $(".show_receive_from_barcode_show_btn").hide();
        $(".show_receive_from_barcode_hide_btn").show();
 
        $("#receive_as_category_section").hide();
        $("#receive_as_manual_barcodes_section").hide();
        $("#update_deduct_barcode_section").hide();
        $("#receive_package_materials_section").hide();
        
        $(".show_receive_as_category_hide_btn").hide();
        $(".show_receive_as_manual_barcodes_hide_btn").hide();
        $(".show_update_deduct_barcode_hide_btn").hide();
        $(".show_receive_package_materials_hide_btn").hide();

        $(".show_receive_as_category_show_btn").show();
        $(".show_receive_as_manual_barcodes_show_btn").show();
        $(".show_update_deduct_barcode_show_btn").show();
        $(".show_receive_package_materials_show_btn").show();

         
        $("#receive_as_manual_barcodes_section").hide();
        $("#update_deduct_barcode_section").hide();

          

    });
    $(".hide_receive_from_barcode_section").click(function() {  
        $("#receive_from_barcode_section").hide();
        $(".show_receive_from_barcode_hide_btn").hide();
        $(".show_receive_from_barcode_show_btn").show(); 
    });
    /////////////////////////////////////////////

    $(".show_receive_as_category_section").click(function() {  
        $("#receive_as_category_section").show();
        $(".show_receive_as_category_show_btn").hide();
        $(".show_receive_as_category_hide_btn").show();
        $("#receive_package_materials_section").hide();
        $("#receive_package_materials_section").hide();

        $("#receive_from_barcode_section").hide();
          $("#receive_as_manual_barcodes_section").hide();
        $("#update_deduct_barcode_section").hide();

        $(".show_receive_from_barcode_hide_btn").hide();
        $(".show_receive_as_manual_barcodes_hide_btn").hide();
         $(".show_update_deduct_barcode_hide_btn").hide();
         $(".show_receive_package_materials_hide_btn").hide();

         $(".show_receive_from_barcode_show_btn").show();
          $(".show_receive_as_manual_barcodes_show_btn").show();
         $(".show_update_deduct_barcode_show_btn").show();
         $(".show_receive_package_materials_show_btn").show();
    });
    $(".hide_receive_as_category_section").click(function() {  
        $("#receive_as_category_section").hide();
        $(".show_receive_as_category_hide_btn").hide();
        $(".show_receive_as_category_show_btn").show();
 
    });
    /////////////////////////////////////////////
    
    /////////////////////////////////////////////

    $(".show_receive_package_materials_section").click(function() {  
        $("#receive_package_materials_section").show();
        $(".show_receive_package_materials_show_btn").hide();
        $(".show_receive_package_materials_hide_btn").show();

        $("#receive_from_barcode_section").hide();
        $("#receive_as_manual_barcodes_section").hide();
        $("#update_deduct_barcode_section").hide();
        $("#receive_as_category_section").hide();

        $(".show_receive_from_barcode_hide_btn").hide();
        $(".show_receive_as_manual_barcodes_hide_btn").hide();
        $(".show_update_deduct_barcode_hide_btn").hide();
        $(".show_receive_as_category_hide_btn").hide();

        $(".show_receive_from_barcode_show_btn").show();
        $(".show_receive_as_manual_barcodes_show_btn").show();
        $(".show_update_deduct_barcode_show_btn").show();
        $(".show_receive_as_category_show_btn").show();
    });
    $(".hide_receive_package_materials_section").click(function() {  
        $("#receive_package_materials_section").hide();
        $(".show_receive_package_materials_hide_btn").hide();
        $(".show_receive_package_materials_show_btn").show();
    });
    /////////////////////////////////////////////

    $(".show_receive_as_manual_barcodes_section").click(function() {  
        $("#receive_as_manual_barcodes_section").show();
        $(".show_receive_as_manual_barcodes_show_btn").hide();
        $(".show_receive_as_manual_barcodes_hide_btn").show();
        $("#receive_package_materials_section").hide();
        
        $("#receive_from_barcode_section").hide();
        $("#receive_as_category_section").hide();
        $("#update_deduct_barcode_section").hide();
        $("#receive_package_materials_section").hide(); 
         
        
        $(".show_receive_from_barcode_hide_btn").hide();
        $(".show_receive_as_category_hide_btn").hide();
        $(".show_update_deduct_barcode_hide_btn").hide();
        $(".show_receive_package_materials_hide_btn").hide();

        $(".show_receive_from_barcode_show_btn").show();
        $(".show_receive_as_category_show_btn").show();
        $(".show_update_deduct_barcode_show_btn").show();
        $(".show_receive_package_materials_show_btn").show();
 

    });
    $(".hide_receive_as_manual_barcodes_section").click(function() {  
        $("#receive_as_manual_barcodes_section").hide();
        $(".show_receive_as_manual_barcodes_hide_btn").hide();
        $(".show_receive_as_manual_barcodes_show_btn").show();
    });
    /////////////////////////////////////////////

    /////////////////////////////////////////////
    $(".show_update_deduct_barcode_section").click(function() {  
        $("#update_deduct_barcode_section").show();
        $(".show_update_deduct_barcode_show_btn").hide();
        $(".show_update_deduct_barcode_hide_btn").show();
        
        $("#receive_from_barcode_section").hide();
         $("#receive_as_category_section").hide();
         $("#receive_as_manual_barcodes_section").hide();
         $("#receive_package_materials_section").hide();
        
         $(".show_receive_from_barcode_hide_btn").hide();
         $(".show_receive_as_category_hide_btn").hide();
         $(".show_receive_as_manual_barcodes_hide_btn").hide();
         $(".show_receive_package_materials_hide_btn").hide();

         $(".show_receive_from_barcode_show_btn").show();
         $(".show_receive_as_category_show_btn").show();
         $(".show_receive_as_manual_barcodes_show_btn").show();
         $(".show_receive_package_materials_show_btn").show();

      
      });
    $(".hide_update_deduct_barcode_section").click(function() {  
        $("#update_deduct_barcode_section").hide();
        $(".show_update_deduct_barcode_hide_btn").hide();
        $(".show_update_deduct_barcode_show_btn").show();
    });
    /////////////////////////////////////////////

    /////////////////////////////////////////////
    $(".show_receive_from_barcode_section_tab6").click(function() {

        $("#receive_from_barcode_section_tab6").show();
        $(".show_receive_from_barcode_show_btn_tab6").hide();
        $(".show_receive_from_barcode_hide_btn_tab6").show();

         $("#receive_as_manual_barcodes_section_tab6").hide();
         $(".show_receive_as_manual_barcodes_hide_btn_tab6").hide();
         $(".show_receive_as_manual_barcodes_show_btn_tab6").show();

         $("#update_tested_devices_serial_from_phonechecker_tab6").hide();
         $(".update_tested_devices_serial_from_phonechecker_hide_btn_tab6").hide();
         $(".update_tested_devices_serial_from_phonechecker_show_btn_tab6").show();

    });
    $(".hide_receive_from_barcode_section_tab6").click(function() {  
        $("#receive_from_barcode_section_tab6").hide();
        $(".show_receive_from_barcode_hide_btn_tab6").hide();
        $(".show_receive_from_barcode_show_btn_tab6").show();
    });
    ///////////////////////////////////////////// 

    $(".show_receive_as_manual_barcodes_section_tab6").click(function() {  

        $("#receive_as_manual_barcodes_section_tab6").show();
        $(".show_receive_as_manual_barcodes_show_btn_tab6").hide();
        $(".show_receive_as_manual_barcodes_hide_btn_tab6").show();

        $("#receive_from_barcode_section_tab6").hide();
        $(".show_receive_from_barcode_hide_btn_tab6").hide();
        $(".show_receive_from_barcode_show_btn_tab6").show();
 
        $("#update_tested_devices_serial_from_phonechecker_tab6").hide();
        $(".update_tested_devices_serial_from_phonechecker_hide_btn_tab6").hide();
        $(".update_tested_devices_serial_from_phonechecker_show_btn_tab6").show();

    });
    $(".hide_receive_as_manual_barcodes_section_tab6").click(function() {  
        $("#receive_as_manual_barcodes_section_tab6").hide();
        $(".show_receive_as_manual_barcodes_hide_btn_tab6").hide();
        $(".show_receive_as_manual_barcodes_show_btn_tab6").show();
    });
    /////////////////////////////////////////////

    /////////////////////////////////////////////
    $(".show_update_tested_devices_serial_from_phonechecker_tab6").click(function() {  

        $("#update_tested_devices_serial_from_phonechecker_tab6").show();
        $(".update_tested_devices_serial_from_phonechecker_show_btn_tab6").hide();
        $(".update_tested_devices_serial_from_phonechecker_hide_btn_tab6").show();
 
        $("#receive_from_barcode_section_tab6").hide();
        $(".show_receive_from_barcode_hide_btn_tab6").hide();
        $(".show_receive_from_barcode_show_btn_tab6").show();

        $("#receive_as_manual_barcodes_section_tab6").hide();
        $(".show_receive_as_manual_barcodes_hide_btn_tab6").hide();
        $(".show_receive_as_manual_barcodes_show_btn_tab6").show();

    });
    $(".hide_update_tested_devices_serial_from_phonechecker_tab6").click(function() {  
        $("#update_tested_devices_serial_from_phonechecker_tab6").hide();
        $(".update_tested_devices_serial_from_phonechecker_hide_btn_tab6").hide();
        $(".update_tested_devices_serial_from_phonechecker_show_btn_tab6").show();
    });
    /////////////////////////////////////////////

    

    

    /////////////////////////////////////////////
    $(".show_receive_from_barcode_section_tab7").click(function() {

        $("#receive_from_barcode_section_tab7").show();
        $(".show_receive_from_barcode_show_btn_tab7").hide();
        $(".show_receive_from_barcode_hide_btn_tab7").show();

         $("#receive_as_manual_barcodes_section_tab7").hide();
         $(".show_receive_as_manual_barcodes_hide_btn_tab7").hide();
         $(".show_receive_as_manual_barcodes_show_btn_tab7").show();

         $("#update_tested_devices_serial_from_phonechecker_tab7").hide();
         $(".update_tested_devices_serial_from_phonechecker_hide_btn_tab7").hide();
         $(".update_tested_devices_serial_from_phonechecker_show_btn_tab7").show();

    });
    $(".hide_receive_from_barcode_section_tab7").click(function() {  
        $("#receive_from_barcode_section_tab7").hide();
        $(".show_receive_from_barcode_hide_btn_tab7").hide();
        $(".show_receive_from_barcode_show_btn_tab7").show();
    });
    ///////////////////////////////////////////// 
    
   
}); 
 

function generate_combo_new(data) { 
    source_field = data[0];
    target_field = data[1];
    other_option = data[2];
    default_value = data[3];
    other_value = data[4];

    var dataString = '';
    dataString = dataString + "source_field=" + $(source_field).attr('name') + "&" + $(source_field).attr('name') + "=" + $(source_field).val() + "";
    dataString = dataString + "&target_field=" + target_field;
    if (other_option != null) {
        dataString = dataString + "&other_option=1";
    }
    if (other_value != null) {
        dataString = dataString + "&other_value=" + other_value;
    }

    //alert(dataString);
    // extra variables for query
    if (data[4] != null) {
        for (i = 4; i < data.length; i++) {
            dataString = dataString + "&" + data[i] + "=" + $('#' + data[i] + '').val() + "";
        }
    }
    //alert(source_field);
    $.ajax({
        url: 'ajax/generate_combo.php',
        type: 'POST',
        dataType: 'json',
        data: dataString,

        success: function(result) {

            $('#' + target_field).html(""); //clear old options
            result = eval(result);
            for (i = 0; i < result.length; i++) {
                for (key in result[i]) {
                    $('#' + target_field).get(0).add(new Option(result[i][key], [key]), document.all ? i : null);
                }
            }
            if (default_value != null) {
                $('#' + target_field).val(default_value); //select default value
            } else {
                $("option:first", target_field).attr("selected", "selected"); //select first option
            }

            $('#' + target_field).css("display", "inline");

        }
    });
}

function generate_combo_new2(data) {
    source_field2 = data[0];
    target_field2 = data[1];
    other_option2 = data[2];
    default_value2 = data[3];
    other_value2 = data[4];
    //alert(other_value);

    var dataString2 = '';
    dataString2 = dataString2 + "source_field=" + $(source_field2).attr('name') + "&" + $(source_field2).attr('name') + "=" + $(source_field2).val() + "";
    dataString2 = dataString2 + "&target_field=" + target_field2;
    if (other_option2 != null) {
        dataString2 = dataString2 + "&other_option=1";
    }
    if (other_value != null) {
        dataString2 = dataString2 + "&other_value=" + other_value;
    }

    //alert(dataString2);
    // extra variables for query
    if (data[4] != null) {
        for (i = 4; i < data.length; i++) {
            dataString2 = dataString2 + "&" + data[i] + "=" + $('#' + data[i] + '').val() + "";
        }
    }
    //alert(source_field2);
    $.ajax({
        url: 'ajax/generate_combo.php',
        type: 'POST',
        dataType: 'json',
        data: dataString2,

        success: function(result) {

            $('#' + target_field2).html(""); //clear old options
            result = eval(result);
            for (i = 0; i < result.length; i++) {
                for (key in result[i]) {
                    $('#' + target_field2).get(0).add(new Option(result[i][key], [key]), document.all ? i : null);
                }
            }
            if (default_value2 != null) {
                $('#' + target_field2).val(default_value2); //select default value
            } else {
                $("option:first", target_field2).attr("selected", "selected"); //select first option
            }

            $('#' + target_field2).css("display", "inline");

        }
    });
}