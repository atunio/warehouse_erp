
$(document).ready(function() {

}); 
// Initially hide the "Remove" buttons except for the last row
updateRemoveButtonVisibility();
$(document).on('click', '.tab', function(event) {
    updateRemoveButtonVisibility();
});
// Handle "Add More" button click to show the next hidden row
$(document).on('click', '.add-more-btn', function(event) {
    event.preventDefault(); // Prevent form submission or page reload

    var id = $(this).attr('id');
    var parts = id.split('^');
    var rowno = parseInt(parts[1]);  
    var next_row = rowno+1; 
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
    
    $(".product_stock_ids_"+rowno).val('').trigger('change'); 
    $("#orderqty_"+rowno).val('').trigger('change'); 
    $("#orderprice_"+rowno).val('').trigger('change');  

    $("#row_"+rowno).hide();
    updateRemoveButtonVisibility(); // Update visibility of "Remove" buttons

    var total_products_in_po = parseInt($("#total_products_in_po").val());
    // $("#total_products_in_po").val(total_products_in_po);

    if(total_products_in_po == '' || total_products_in_po == 0 || total_products_in_po == null){
        total_products_in_po = 1;
    }
    var p_value = 0;
    for(var k = 1; k<= total_products_in_po; k++){ 
        var currentText = $("#orderprice_"+k).val(); 
        if(currentText != '' && currentText != 0 && currentText != null){
            currentText = currentText.replace(/,/g, '');
            p_value += parseFloat(currentText);
        } 
    }
    if(p_value > 0){ 
        $("#total_value").text(formatNumber(p_value, 2));
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
$(document).on('change', '.product_stock', function(event) {
    var selectedValue = $(this).val();
    var id          = $(this).attr('id');
    var parts       = id.split('_');
    var rowno       = parseInt(parts[1]);  
    var next_row    = rowno+1; 
    
    if (selectedValue === 'product_add_modal') {
        // Open the modal
        $('#product_add_modal').modal('open');
        // Reset the select box to prevent accidental selection
        $(this).val('');
        event.stopImmediatePropagation(); // Stop further actions
        return;
    } else if (selectedValue > 0) { 
        $("#row_"+next_row).show();
        updateRemoveButtonVisibility();
        var total_products_in_po = parseInt($("#total_products_in_po").val());
        $("#total_products_in_po").val(total_products_in_po+1);
    }
});
// Initialize modal (if you're using Materialize)
$('.modal').modal();

$(document).on('keyup', '.order_price', function(event) {
    var order_price     = parseInt($(this).val());
    var id              = $(this).attr('id');
    var parts           = id.split('_');
    var rowno           = parseInt(parts[1]);  
    var value           = 0;
   
    var total_products_in_po = $("#total_products_in_po").val();
    if(total_products_in_po == '' || total_products_in_po == 0 || total_products_in_po == null){
        total_products_in_po = 1;
    } 
    
    var p_value = 0;
    for(var k = 1; k<= total_products_in_po; k++){
        var currentText = $("#orderprice_"+k).val(); 
        if(currentText != '' && currentText != 0 && currentText != null){
            currentText = currentText.replace(/,/g, '');
            p_value += parseFloat(currentText);
        }
    }
    if(p_value > 0){ 
        $("#total_value").text(formatNumber(p_value, 2));
    } 

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