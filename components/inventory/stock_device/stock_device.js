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
          $("."+id).show();
          $(".sub_minus_"+id).show();
          $(".sub_plus_"+id).hide();
     });
     $(document).on('click', '.minus_icon_sub', function(e) {
          var id = $(this).attr("id");
          $("."+id).hide();
          $(".sub_plus_"+id).show(); 
          $(".sub_minus_"+id).hide();
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
    //flt_bin_id != '' && flt_bin_id != '0' ||
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