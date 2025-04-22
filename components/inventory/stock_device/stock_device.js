$(document).ready(function() { 
     $(document).on('click', '.plus_icon', function(e) {
          var id = $(this).attr("id");
           $("."+id).show();
           $(".minus_"+id).show();
           $(".plus_"+id).hide();
     });
     $(document).on('click', '.minus_icon', function(e) {
     var id = $(this).attr("id");
          $("."+id).hide();
          $(".datatr_"+id).hide();
          $(".plus_icon_sub").show();
          $(".minus_icon_sub").hide();
          $(".plus_"+id).show(); 
          $(".minus_"+id).hide();
     });
     $(document).on('click', '.plus_icon_sub', function(e) {
          var id = $(this).attr("id");
          $(".sub_minus_"+id).show();
          $(".sub_plus_"+id).hide();
          $(".dt-"+id).show();
     });
     $(document).on('click', '.minus_icon_sub', function(e) {
          var id = $(this).attr("id");
          $(".sub_plus_"+id).show(); 
          $(".sub_minus_"+id).hide();
          $(".dt-"+id).hide();
     });
     $(document).on('click', '.expand_all', function(e) {
          $(".detail_tr").show();
          $(this).hide();
          $(".collapse_all").show();
          $(".plus_icon").hide();
          $(".minus_icon").show();
          $(".plus_icon_sub").hide();
          $(".minus_icon_sub").show();
     }); 
     $(document).on('click', '.collapse_all', function(e) {
          $(".detail_tr").hide();
          $(this).hide();
          $(".expand_all").show();
          $(".plus_icon").show();
          $(".minus_icon").hide();
          $(".plus_icon_sub").show();
          $(".minus_icon_sub").hide();
     });
    $(document).on('click', '#searchButton', function(e) {
          $('#action').val('search');
    }); 
     $(document).on('click', '#exportButton', function(e) {
          $('#action').val('export');
    }); 
    var flt_bin_id = $("#flt_bin_id").val();
    var flt_serial_no = $("#flt_serial_no").val();
    if( flt_serial_no != '' && flt_serial_no != '0'){
          $(".detail_tr").show();
          $(this).hide();
          $(".collapse_all").show();
          $(".plus_icon").hide();
          $(".minus_icon").show();
          $(".plus_icon_sub").hide();
          $(".minus_icon_sub").show();
    }
    $(".minus_icon_sub").hide();
});
function viewProductDeail(product_id){
     var module_id  = $("#module_id").val(); 
     var dataString = 'type=viewProductDeail&product_id=' + product_id + '&module_id=' + module_id;
     $.ajax({
          type: "POST",
          url: "ajax/ajax_add_entries.php",
          data: dataString,
          cache: false,
          success: function(response) {
               if (response) {
                    if (response == "Product is Required" || response == "Error" ) {
                         var toastHTML = response;
                         showToast(toastHTML, "Fail");
                    }
                    else{
                         $(`tr.${product_id}`).remove(); 
                         const $row = $(`#${product_id}`).closest('tr');
                         const $newRow = $(response.trim());
                         $row.after($newRow);
                    }
               }
          },
          error: function() {
          }
     });
}
function viewProductSerialNoDeail(product_id, stock_id, inventory_status, stock_grade){
     var module_id  = $("#module_id").val(); 
     var dataString = 'type=viewProductSerialNoDeail&product_id=' + product_id + '&stock_id=' + stock_id + '&inventory_status=' + inventory_status + '&stock_grade=' + stock_grade + '&module_id=' + module_id;
     $.ajax({
          type: "POST",
          url: "ajax/ajax_add_entries.php",
          data: dataString,
          cache: false,
          success: function(response) {
               if (response) {
                    if (response == "Product is Required" || response == "Grade is Required" || response == "Status is Required" || response == "Error" ) {
                         var toastHTML = response;
                         showToast(toastHTML, "Fail");
                    }
                    else{
                         $(`tr.dt-${stock_id}`).remove(); 
                         const $row = $(`#dt-${stock_id}`).closest('tr');
                         const $newRow = $(response.trim());
                         $row.after($newRow);
                    }
               }
          },
          error: function() {
          }
     });
}
function showToast(message, type) {
     var toastClass = type === 'Success' ? 'green' : 'red';
     M.toast({
         html: message,
         classes: toastClass
     });
} 