



$(document).ready(function() { 
    
    $(".add_defect_code").click(function() {  
        var id = $(this).attr('id');
        var array               = id.split("^");
        var inputno             = parseInt(array[1]);
        var next_input_no       = inputno+1;
        var previous_input_no   = inputno-1;
        
        $(".add_defect_code").hide();
        $(".defect_code_input_"+next_input_no).show();
        $("#button_div_defect_code_"+next_input_no).show();
        $(".add_defect_code_"+next_input_no).show();
        $(".minus_defect_code_"+next_input_no).show();
    });
    
    $(".minus_defect_code").click(function() {  
        var id = $(this).attr('id');
        var array               = id.split("^");
        var inputno             = parseInt(array[1]);
        var next_input_no       = inputno+1;
        var previous_input_no   = inputno-1;

        $(".add_defect_code_"+previous_input_no).show();
        
         $(".defect_code_input_"+inputno).hide();
        $("#button_div_defect_code_"+inputno).hide();
        $(".add_defect_code_"+inputno).hide();
        $(".minus_defect_code_"+inputno).hide();
        $("#defect_code_"+inputno).val('');
        
    });
});