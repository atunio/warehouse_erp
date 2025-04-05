
$(document).ready(function() { 
    // Initially hide the "Remove" buttons except for the last row
    updateRemoveButtonVisibility();
    partsUpdateRemoveButtonVisibility();
    // Handle "Add More" button click to show the next hidden row
    $(document).on('click', '#show_tab1', function(event) {
        var first_tab_url = $("#first_tab_url").val();
        window.location.href = first_tab_url;
    });
    $(document).on('click', '.tab', function(event) {
        updateRemoveButtonVisibility();
        partsUpdateRemoveButtonVisibility();
    });
    $(document).on('click', '.add-more-btn', function(event) {
        event.preventDefault(); // Prevent form submission or page reload
        
        var vender_invoice_no = $("#vender_invoice_no").val();
        var id = $(this).attr('id');
        var parts = id.split('^');
        var rowno = parseInt(parts[1]);  
        var next_row = rowno+1; 
        $(".product_ids_"+next_row).val('').trigger('change'); 
        $("#productcondition_"+next_row).val('').trigger('change'); 
        $("#orderqty_"+next_row).val('').trigger('change'); 
        $("#orderprice_"+next_row).val('').trigger('change'); 
        $("#expectedstatus_"+next_row).val('').trigger('change'); 
        $("#value_"+next_row).text('').trigger('change');
        $("#invoiceno_"+next_row).val(vender_invoice_no).trigger('change'); 
        $("#row_"+next_row).show();
        updateRemoveButtonVisibility(); // Update visibility of "Remove" buttons

        var total_products_in_po = parseInt($("#total_products_in_po").val());
        $("#total_products_in_po").val(total_products_in_po+1);

        if(rowno == 0){
            // $(this).hide();
        }

    });
    $(document).on('click', '.add-more-btn2', function(event) {
        $("#row_1").show();
        $(this).hide();
        updateRemoveButtonVisibility(); // Update visibility of "Remove" buttons
        
        var total_products_in_po = parseInt($("#total_products_in_po").val());
        $("#total_products_in_po").val(total_products_in_po+1);
    });
 
    $(document).on('click', '.remove-row', function(event) {
        event.preventDefault(); // Prevent form submission
        var id = $(this).attr('id');
        var parts = id.split('^');
        var rowno = parseInt(parts[1]); 
        
        $(".product_ids_"+rowno).val('').trigger('change'); 
        $("#productcondition_"+rowno).val('').trigger('change'); 
        $("#orderqty_"+rowno).val('').trigger('change'); 
        $("#orderprice_"+rowno).val('').trigger('change'); 
        $("#expectedstatus_"+rowno).val('').trigger('change'); 
        $("#value_"+rowno).text('').trigger('change');
         $("#invoiceno_"+rowno).val('').trigger('change');
       
        $("#row_"+rowno).hide();
        updateRemoveButtonVisibility(); // Update visibility of "Remove" buttons
        
        var total_products_in_po = parseInt($("#total_products_in_po").val());
        // $("#total_products_in_po").val(total_products_in_po);

        if(total_products_in_po == '' || total_products_in_po == 0 || total_products_in_po == null){
            total_products_in_po = 1;
        }
        var p_value =  total_qty = total_price = 0;
        for(var k = 1; k<= total_products_in_po; k++){
            var currentText = $("#value_"+k).text(); 
            var orderqty = $("#orderqty_"+k).val();
            if(currentText != '' && currentText != 0 && currentText != null){
                currentText = currentText.replace(/,/g, '');
                p_value += parseFloat(currentText);
            }
            if(orderqty != '' && orderqty != null){
                total_qty += parseFloat(orderqty);
            }
        }
        if(p_value > 0){ 
            $("#total_value").text(formatNumber(p_value, 2));
        } 
        if(total_qty > 0){
            $("#total_qty").text(total_qty);
        } 
        if(rowno == 1){
            $(".first_row").show();
        }
    });

    // Function to show/hide the "Remove" button
    function updateRemoveButtonVisibility() {
        $('.add-more-btn').hide(); // Hide all "Remove" buttons
        $('#page-length-option1 tbody .dynamic-row:visible:last .add-more-btn').show(); // Show only the last "Remove" button
    }

    // Handle the select change event to open the modal when "Add New Product" is selected
    $(document).on('change', '.product-select', function(event) {
 
        var selectedValue = $(this).val();
        var id          = $(this).attr('id');
        var parts       = id.split('_');
        var rowno       = parseInt(parts[1]);  
        var next_row    = rowno+1; 
        
        if (selectedValue === 'product_add_modal') {
            // Open the modal
            $('#product_add_modal').modal('open');
            $('#selected_product_id').val(rowno);
            // Reset the select box to prevent accidental selection
            $(this).val('');
            event.stopImmediatePropagation(); // Stop further actions
            return;
        } else if (selectedValue > 0) { 
            $("#row_"+next_row).show();
            updateRemoveButtonVisibility();
            var total_products_in_po = parseInt($("#total_products_in_po").val());
            $("#total_products_in_po").val(total_products_in_po+1);

            get_pkg_stock_of_product(selectedValue, rowno);
        }

    });
    // Initialize modal (if you're using Materialize)
    $('.modal').modal();

    $(document).on('keyup', '.order_qty', function(event) {
 
        var order_qty           = parseInt($(this).val());
        var id                  = $(this).attr('id');
        var parts               = id.split('_');
        var rowno               = parseInt(parts[1]);  
        var value               = 0;
        var order_price         = parseFloat($("#orderprice_"+rowno).val());
        var pkg_stock_in_hand   = parseFloat($("#pkg_stock_of_product_"+rowno).text());
        
         if(!isNaN(order_price) && !isNaN(order_qty)){
            value = (order_price * order_qty);
            $("#value_"+rowno).text(formatNumber(value, 2));
        }
        else{
            $("#value_"+rowno).text('');
        }

        var total_products_in_po = $("#total_products_in_po").val();
        if(total_products_in_po == '' || total_products_in_po == 0 || total_products_in_po == null){
            total_products_in_po = 1;
        }
        var p_value =  total_qty = total_price = 0;
        for(var k = 1; k<= total_products_in_po; k++){
            var currentText = $("#value_"+k).text(); 
            var orderqty    = $("#orderqty_"+k).val();
            if(currentText != '' && currentText != 0 && currentText != null){
                currentText = currentText.replace(/,/g, '');
                p_value += parseFloat(currentText);
            }
            if(orderqty != '' && orderqty != null){
                total_qty += parseFloat(orderqty);
            }
        }
        if(p_value > 0){ 
            $("#total_value").text(formatNumber(p_value, 2));
        } 
        if(total_qty > 0){
            $("#total_qty").text(total_qty);
        } 
        if(pkg_stock_in_hand > 0){
            var pkg_stock_of_product_needed = order_qty - pkg_stock_in_hand;
        }else{
            var pkg_stock_of_product_needed = order_qty - 0;
        }
        
        if(pkg_stock_of_product_needed < 0){
            pkg_stock_of_product_needed = 0;
        }
        $("#pkg_stock_of_product_needed_"+rowno).text(pkg_stock_of_product_needed);
    });
    $(document).on('keyup', '.order_price', function(event) {
        var order_price     = parseInt($(this).val());
        var id              = $(this).attr('id');
        var parts           = id.split('_');
        var rowno           = parseInt(parts[1]);  
        var value           = 0;
        var order_qty     = parseFloat($("#orderqty_"+rowno).val());
        if(!isNaN(order_price) && !isNaN(order_qty)){
            value = (order_price * order_qty);
            $("#value_"+rowno).text(formatNumber(value, 2));
        }
        else{
            $("#value_"+rowno).text('');
        }
        var total_products_in_po = $("#total_products_in_po").val();
        if(total_products_in_po == '' || total_products_in_po == 0 || total_products_in_po == null){
            total_products_in_po = 1;
        } 
        
        var p_value =  total_qty = total_price = 0;
        for(var k = 1; k<= total_products_in_po; k++){
            var currentText = $("#value_"+k).text(); 
            var orderqty = $("#orderqty_"+k).val();
             if(currentText != '' && currentText != 0 && currentText != null){
                currentText = currentText.replace(/,/g, '');
                p_value += parseFloat(currentText);
             }
             if(orderqty != '' && orderqty != null){
                 total_qty += parseFloat(orderqty);
             }
        }
        if(p_value > 0){ 
            $("#total_value").text(formatNumber(p_value, 2));
        } 
        if(total_qty > 0){
            $("#total_qty").text(total_qty);
        } 
    });

    $(".package_material_parts").click(function() {
        var icon = $(this).find('i.material-icons');
        
        if ($('#package_parts').is(':visible')) {
            $("#package_parts").hide();
            icon.text('visibility_off'); // Change icon to visibility_off
        } else {
            $("#package_parts").show();
            icon.text('visibility'); // Change icon to visibility
            partsUpdateRemoveButtonVisibility();
        }
    });

    var icon = $(this).find('i.visibility_icon');
    if ($('#package_parts').is(':visible')) {
        icon.text('visibility');
    } else {
        icon.text('visibility_off'); // Change icon to visibility_off // Change icon to visibility
    }
    $(document).on('click', '.add-more-part-btn', function(event) {
        event.preventDefault(); // Prevent form submission or page reload
    
        var id = $(this).attr('id');
        var parts = id.split('_');
        var rowno = parseInt(parts[1]);  
        
        var next_row = rowno+1; 
        $("#packageids_"+next_row).val('').trigger('change'); 
        $("#orderpartqty_"+next_row).val('').trigger('change'); 
        $("#case_pack_" + next_row).text('').trigger('change');
        $("#total_case_pack_" + next_row).text('').trigger('change');

        $("#row_part_"+next_row).show();
        partsUpdateRemoveButtonVisibility(); // Update visibility of "Remove" buttons
    
        var total_products_part_in_po = parseInt($("#total_products_part_in_po").val());
        $("#total_products_part_in_po").val(total_products_part_in_po+1);
    
    
        if(rowno == 0){
            // $(this).hide();
        }
    });
    $(document).on('click', '.add-part-more-btn2', function(event) {
        $("#row_part_1").show();
        $("#case_pack_1").text('').trigger('change');
        $("#total_case_pack_1").text('').trigger('change');
        $(this).hide();
        partsUpdateRemoveButtonVisibility(); // Update visibility of "Remove" buttons
    });
    $(document).on('click', '.remove-row-part', function(event) {
        event.preventDefault(); // Prevent form submission
        var id = $(this).attr('id');
        var parts = id.split('_');
        var rowno = parseInt(parts[1]); 
        
        $("#packageids_"+rowno).val('').trigger('change'); 
        $("#orderpartqty_"+rowno).val('').trigger('change');
        $("#orderpartprice_"+rowno).val('').trigger('change'); 
        $("#case_pack_" + rowno).text('').trigger('change');
        $("#total_case_pack_" + rowno).text('').trigger('change');
        var order_part_price = parseFloat($("#orderpartprice_"+rowno).val());
        if(!isNaN(order_part_price) && !isNaN(order_part_qty)){
            part_value = (order_part_price * order_part_qty);
            $("#part_value_"+rowno).text(formatNumber(part_value, 2));
        }
        else{
            $("#part_value_"+rowno).text('');
        }
        $("#row_part_"+rowno).hide();
        partsUpdateRemoveButtonVisibility(); // Update visibility of "Remove" buttons
        var total_products_part_in_po = $("#total_products_part_in_po").val();
        if(total_products_part_in_po == '' || total_products_part_in_po == 0 || total_products_part_in_po == null){
            total_products_part_in_po = 1;
        }
        var part_value =  total_part_qty = total_part_price = 0;
        for(var k = 1; k<= total_products_part_in_po; k++){
            var currentText = $("#part_value_"+k).text(); 
            var orderpartqty = $("#orderpartqty_"+k).val();
            if(currentText != '' && currentText != 0 && currentText != null){
                currentText = currentText.replace(/,/g, '');
                part_value += parseFloat(currentText); 
            } 
            if(orderpartqty != '' && orderpartqty != null){
                total_part_qty += parseFloat(orderpartqty);
            }
        }
        if(part_value > 0){ 
            $("#total_part_value").text(formatNumber(part_value, 2));
        } 
        if(total_part_qty > 0){
            $("#total_part_qty").text(total_part_qty);
        } 
        if(rowno == 1){
            $(".first_row_part").show();
        }
    });
  
    $(document).on('change', '.product_packages', function(event) {
        var selectedValue = $(this).val();
        var id = $(this).attr('id');
        var parts = id.split('_');
        var rowno = parseInt(parts[1]);
        var next_row = rowno + 1;
    
        if (selectedValue === 'package_add_modal') {
            $('#package_add_modal').modal('open');
            $('#selected_package_id').val(rowno);
            $(this).val('');
            event.stopImmediatePropagation();
            return;
        } else if (selectedValue > 0) {
            $("#row_part_" + next_row).show();
            partsUpdateRemoveButtonVisibility();
            var total_products_part_in_po = parseInt($("#total_products_part_in_po").val());
            $("#total_products_part_in_po").val(total_products_part_in_po + 1);
    
            get_case_pack(selectedValue, rowno).then(function(case_pack) {
                var orderpartqty = parseFloat($("#orderpartqty_" + rowno).val());
                var total_case_pack = 0;
    
                if (case_pack > 0 && orderpartqty > 0) {
                    total_case_pack = orderpartqty / case_pack;
                    total_case_pack = Math.ceil(total_case_pack);
                }
    
                if (case_pack > 0 && isFinite(total_case_pack) && total_case_pack > 0) {
                    $("#total_case_pack_" + rowno).text(total_case_pack);
                } else {
                    $("#total_case_pack_" + rowno).text(''); // Clear the value if invalid
                }
            });
        }
    });
    
    
    function partsUpdateRemoveButtonVisibility() {
        // Hide all "Add More" buttons
        $('.add-more-part-btn').hide();
        $('#page-length-option2 tbody .dynamic-row-part:visible').last().find('.add-more-part-btn').show();
    }

    $(document).on('keyup', '.order_part_qty', function(event) {
 
        var order_part_qty   = parseInt($(this).val());
        var id          = $(this).attr('id');
        var parts       = id.split('_');
        var rowno       = parseInt(parts[1]);  
        var part_value       = 0;
        var order_part_price = parseFloat($("#orderpartprice_"+rowno).val());
        if(!isNaN(order_part_price) && !isNaN(order_part_qty)){
            part_value = (order_part_price * order_part_qty);
            $("#part_value_"+rowno).text(formatNumber(part_value, 2));
        }
        else{
            $("#part_value_"+rowno).text('');
        }
    
        var total_products_part_in_po = $("#total_products_part_in_po").val();
        if(total_products_part_in_po == '' || total_products_part_in_po == 0 || total_products_part_in_po == null){
            total_products_part_in_po = 1;
        }
        var part_value =  total_part_qty = total_part_price = 0;
        for(var k = 1; k<= total_products_part_in_po; k++){
            var currentText = $("#part_value_"+k).text(); 
            var orderpartqty = $("#orderpartqty_"+k).val();
            if(currentText != '' && currentText != 0 && currentText != null){
                currentText = currentText.replace(/,/g, '');
                part_value += parseFloat(currentText); 
            } 
            if(orderpartqty != '' && orderpartqty != null){
                total_part_qty += parseFloat(orderpartqty);
            }
            var case_pack = parseFloat($("#case_pack_" + k).text());
            var total_case_pack = 0;
            if(orderpartqty > 0 && case_pack > 0){
                total_case_pack = (orderpartqty / case_pack);
                total_case_pack = Math.ceil(total_case_pack);
            }
            if(total_case_pack > 0 ){
                $("#total_case_pack_" + k).text(total_case_pack);
            }else{
                $("#total_case_pack_" + k).text('');
            }
        }
        if(part_value > 0){ 
            $("#total_part_value").text(formatNumber(part_value, 2));
        } 
        if(total_part_qty > 0){
            $("#total_part_qty").text(total_part_qty);
        } 
    });
    $(document).on('keyup', '.order_part_price', function(event) {
        var order_part_price = parseInt($(this).val());
        var id               = $(this).attr('id');
        var parts            = id.split('_');
        var rowno            = parseInt(parts[1]);  
        var part_value       = 0;
        var order_part_qty   = parseFloat($("#orderpartqty_"+rowno).val());
        if(!isNaN(order_part_price) && !isNaN(order_part_qty)){
            part_value = (order_part_price * order_part_qty);
            $("#part_value_"+rowno).text(formatNumber(part_value, 2));
        }
        else{
            $("#part_value_"+rowno).text('');
        }
        var total_products_part_in_po = $("#total_products_part_in_po").val();
        if(total_products_part_in_po == '' || total_products_part_in_po == 0 || total_products_part_in_po == null){
            total_products_part_in_po = 1;
        } 
        
        var part_value =  total_part_qty = total_part_price = 0;
        for(var k = 1; k<= total_products_part_in_po; k++){
            var currentText = $("#part_value_"+k).text(); 
            var orderpartqty = $("#orderpartqty_"+k).val();
             if(currentText != '' && currentText != 0 && currentText != null){
                currentText = currentText.replace(/,/g, '');
                part_value += parseFloat(currentText); 
            }
            if(orderpartqty != '' && orderpartqty != null){
                total_part_qty += parseFloat(orderpartqty);
            }
        }
        if(part_value > 0){ 
            $("#total_part_value").text(formatNumber(part_value, 2));
        } 
        if(total_part_qty > 0){
            $("#total_part_qty").text(total_part_qty);
        } 
    });
    
    $('.package_id').on('change', function() {
        var id      = $(this).attr('id');
        var array   = id.split("_");
        var inputno = parseInt(array[1]);
        var package_id = $(this).val();
        var order_qty = $("#orderqty_"+inputno).val();
        if(package_id !="" && package_id != '0'){
            $("#packagematerialqty_"+inputno).show(); 
            $('#packagematerialqty_'+inputno).val(order_qty).focus(); // Set value and focus
        }
        else{
            $("#packagematerialqty_"+inputno).hide(); 
        }
    });
 
    $('#status_id_rma').on('change', function() {
        
        $("#tracking_no_rma").val(''); 
        $('#sub_location_id_barcode_rma').val('').trigger('change');
        $('#repair_type').val('').trigger('change');

        var status_id_rma = $(this).val();
        if(status_id_rma == '18'){
            $(".partial_refund_status").show();  
        }
        else{
            $(".partial_refund_status").hide(); 
        }
        if(status_id_rma == '' || status_id_rma == null){
            $(".tracking_no_rma").hide();
            $(".repair_type").hide();
            $(".sub_location_id_barcode_rma").hide(); 
            $(".new_value").hide(); 
            $(".partial_refund_status").hide(); 
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

    /////////////////////////////////////////////
    $(".show_receive_from_barcode_section_tab6_2").click(function() {

        $("#receive_from_barcode_section_tab6_2").show();
        $(".show_receive_from_barcode_show_btn_tab6_2").hide();
        $(".show_receive_from_barcode_hide_btn_tab6_2").show();

         $("#receive_as_manual_barcodes_section_tab6_2").hide();
        //  $(".show_receive_as_manual_barcodes_hide_btn_tab6").hide();
        //  $(".show_receive_as_manual_barcodes_show_btn_tab6").show();

        //  $("#update_tested_devices_serial_from_phonechecker_tab6").hide();
        //  $(".update_tested_devices_serial_from_phonechecker_hide_btn_tab6").hide();
        //  $(".update_tested_devices_serial_from_phonechecker_show_btn_tab6").show();

    });
    $(".hide_receive_from_barcode_section_tab6_2").click(function() {  
        $("#receive_from_barcode_section_tab6_2").hide();
        $(".show_receive_from_barcode_hide_btn_tab6_2").hide();
        $(".show_receive_from_barcode_show_btn_tab6_2").show();
    });
    /////////////////////////////////////////////

    /////////////////////////////////////////////
    $(".show_broken_device_section_tab6").click(function() {
        $("#broken_device_section_tab6").show();
        $(".show_broken_device_show_btn_tab6").hide();
        $(".show_broken_device_hide_btn_tab6").show();
    });
    $(".hide_broken_device_section_tab6").click(function() {  
        $("#broken_device_section_tab6").hide();
        $(".show_broken_device_hide_btn_tab6").hide();
        $(".show_broken_device_show_btn_tab6").show();
    });
    ///////////////////////////////////////////// 

    ///////////////////////////////////////////// 
    $(".show_fake_serial_no_section_tab6").click(function() {

        $("#fake_serial_no_section_tab6").show();
        $(".show_fake_serial_no_show_btn_tab6").hide();
        $(".show_fake_serial_no_hide_btn_tab6").show();
    });
    $(".hide_fake_serial_no_section_tab6").click(function() {  
        $("#fake_serial_no_section_tab6").hide();
        $(".show_fake_serial_no_hide_btn_tab6").hide();
        $(".show_fake_serial_no_show_btn_tab6").show();
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

function formatNumber(value, decimals) {
    // Ensure value is a number
    value = parseFloat(value);
    
    // Fix to specified decimals
    value = value.toFixed(decimals);
    
    // Add thousands separator
    let parts = value.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    
    return parts.join('.');
}



function get_case_pack(package_id, rowno) {
    let deferred = $.Deferred();
    let dataString = `type=get_case_pack&package_id=${package_id}`;
    $.ajax({
        type: "POST",
        url: "ajax/ajax_add_entries.php",
        data: dataString,
        success: function(response) {
            response = $.trim(response);
            if (response) {
                $("#case_pack_" + rowno).text(response);
                deferred.resolve(parseFloat(response));
            } else {
                $("#case_pack_" + rowno).text('');
                deferred.resolve(0);
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred while fetching the case pack.');
            deferred.reject(error);
        }
    });
    return deferred.promise();
}
function get_pkg_stock_of_product(product_id, rowno) {
    let deferred = $.Deferred();
    let dataString = `type=get_pkg_stock_of_product&product_id=${product_id}`;
    $.ajax({
        type: "POST",
        url: "ajax/ajax_add_entries.php",
        data: dataString,
        success: function(response) {
            response = $.trim(response);
            if (response) {
                $("#pkg_stock_of_product_" + rowno).text(response);
                deferred.resolve(parseFloat(response));
            } else {
                $("#pkg_stock_of_product_" + rowno).text('');
                deferred.resolve(0);
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred while fetching the case pack.');
            deferred.reject(error);
        }
    });
    return deferred.promise();
}

function showToast(message, type) {
    var toastClass = type === 'Success' ? 'green' : 'red';
    M.toast({
        html: message,
        classes: toastClass
    });
}
$(document).on('change', '#stage_status', function(event) {
    var stage_status = $(this).val();
    var previous_stage_status = $('#previous_stage_status').val();
    var id = $("#id").val();
    var module_id = $("#module_id").val();
    if(stage_status != "" && id != ""){
        var dataString = 'type=update_po_stage_status&stage_status=' + stage_status + '&id=' + id + '&module_id=' + module_id;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(response) {
                if (response) {
                   
                    if (response === "Fail") {
                        var toastHTML = 'Some errors check.';
                        showToast(toastHTML, "Fail");
                    } 
                    else{
                        var toastHTML = "Stage status updated successfully.";
                        showToast(toastHTML, "Success");
                        location.href = window.location.href;
                        //location.reload();
                       
                        // if((stage_status == 'Committed' && previous_stage_status != 'Committed') ){
                        // }else if((stage_status != 'Committed' && previous_stage_status == 'Committed')){
                        //     location.reload();
                        // }

                    }
                    
                }
            },
            error: function() {
                
            }
        });
    }
});

$(document).on('select2:open', '.fetched_productids1', function() {
    $("#programmaticChange").val('');
});
$(document).on('change', '.fetched_productids1', function(event) {
    var programmaticChange = $("#programmaticChange").val();
    if(programmaticChange == ""){
        var fetched_productid = $(this).val();
        var fetched_productid = $(this).val();
        var serial_no = $(this).attr('id'); 
        var modalNo = $("#modelNo_"+serial_no).val(); 
        var module_id = $("#module_id").val();
        $("#product_id_update_modelno").val(fetched_productid) ;
        $("#modelno_update_productid").val(modalNo) ;
        if(fetched_productid != "" && modalNo != ""){
            var dataString = 'type=update_product_modelno&product_id=' + fetched_productid + '&modalNo=' + modalNo + '&module_id=' + module_id;
            $.ajax({
                type: "POST",
                url: "ajax/ajax_add_entries.php",
                data: dataString,
                cache: false,
                success: function(response) {
                    if (response) {
                        // alert(response);
                        if (response === "Fail") {
                            // var toastHTML = 'Select Product';
                            // showToast(toastHTML, "Fail");
                        } 
                        else{
                            // var toastHTML = "Model No updated successfully.";
                            // showToast(toastHTML, "Success");
                            var product_id_update_modelno = $("#product_id_update_modelno").val();
                            var modelno_update_productid = $("#modelno_update_productid").val();
                            $(this).find("option[value='" + product_id_update_modelno + "']").text("ProductID: "+product_id_update_modelno+", Model#: "+modelno_update_productid);

                            $(".fetched_productids1").each(function() {
                                var serial_no2 = $(this).attr('id');
                                var phone_check_model_no = $("#modelNo_"+serial_no2).val(); 
                                $("#"+serial_no2).find("option[value='" + product_id_update_modelno + "']").text("ProductID: "+product_id_update_modelno+", Model#: "+modelno_update_productid);
                                if(modelno_update_productid == phone_check_model_no){
                                    $("#"+serial_no2).val(product_id_update_modelno).change();
                                }
                            }); 
                        }
                        $("#programmaticChange").val("Changed");
                    }
                },
                error: function() {
                    
                }
            });
        }
    } 
});


