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
         $(".plus_"+id).show(); 
         $(".minus_"+id).hide();
    });


    $(document).on('click', '.expand_all', function(e) {
         $(".detail_tr").show();
         $(this).hide();
         $(".collapse_all").show();
         $(".plus_icon").hide();
         $(".minus_icon").show();
          
    }); 
    $(document).on('click', '.collapse_all', function(e) {
         $(".detail_tr").hide();
         $(this).hide();
         $(".expand_all").show();
         $(".plus_icon").show();
         $(".minus_icon").hide();


    }); 
    $(document).on('click', '#searchButton', function(e) {
          $('#action').val('search');
    }); 
     $(document).on('click', '#exportButton', function(e) {
          $('#action').val('export');
    }); 
     
});