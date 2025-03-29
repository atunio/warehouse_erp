



$(document).ready(function() { 
    
    $(".add_product_model_no").click(function() {  
        var id = $(this).attr('id');
        var array               = id.split("^");
        var inputno             = parseInt(array[1]);
        var next_input_no       = inputno+1;
        var previous_input_no   = inputno-1;
        
        $(".add_product_model_no").hide();
        $(".product_model_no_input_"+next_input_no).show();
        $("#button_div_product_model_no_"+next_input_no).show();
        $(".add_product_model_no_"+next_input_no).show();
        $(".minus_product_model_no_"+next_input_no).show();
    });
    
    $(".minus_product_model_no").click(function() {  
        var id = $(this).attr('id');
        var array               = id.split("^");
        var inputno             = parseInt(array[1]);
        var next_input_no       = inputno+1;
        var previous_input_no   = inputno-1;

        $(".add_product_model_no_"+previous_input_no).show();
        
         $(".product_model_no_input_"+inputno).hide();
        $("#button_div_product_model_no_"+inputno).hide();
        $(".add_product_model_no_"+inputno).hide();
        $(".minus_product_model_no_"+inputno).hide();
        $("#product_model_no_"+inputno).val('');
        
    });
});