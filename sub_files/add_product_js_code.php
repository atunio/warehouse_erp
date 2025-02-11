<script>
    $(document).on('click', '#add_product_btn', function(e) {
        var selected_product_id = $("#selected_product_id").val();
        var product_desc = $("#product_desc").val();
        var detail_desc = $("#detail_desc2").val();
        var product_category = $("#product_category option:selected").val();
        var product_uniqueid = $("#product_uniqueid option:selected").val();

        var cmd = $("#cmd").val();
        var id = $("#id").val();

        var dataString = 'type=add_product&product_desc=' + product_desc + '&product_uniqueid=' + product_uniqueid + '&product_category=' + product_category + '&detail_desc=' + detail_desc + '&cmd=' + cmd + '&id=' + id;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data != 'Select') {
                    
                    $("#product_desc").val("");
                    $("#detail_desc2").val("");
                    $('#product_uniqueid option[value=""]').prop('selected', true).trigger('change');
                    $('#product_category option[value=""]').prop('selected', true).trigger('change');
                    
                    if(selected_product_id > '0'){
                        $("#productids_"+selected_product_id).children().last().before(data);
                    }else{
                        $("#product_id").append(data);
                    }
                    
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>