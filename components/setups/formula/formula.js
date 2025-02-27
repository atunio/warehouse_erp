

$(document).ready(function() { 
    $('#formula_type').on('change', function() {
        var formula_type = $(this).val();
        if(formula_type == 'Repair'){
            $(".formula_type").show();  
        }
        else{
            $(".formula_type").hide(); 
        }
    });
});