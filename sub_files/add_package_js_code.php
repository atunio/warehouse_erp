<script>
    $(document).on('click', '#add_package_btn', function(e) {
        var selected_package_id = $('#selected_package_id').val();
        var package_name = $("#package_name").val();
        var sku_code = $("#sku_code_modal").val();
        var case_pack = $("#case_pack_modal").val();
        var pack_desc = $("#pack_desc_modal").val();
          
        var product_category = $("#category_id_modal option:selected").val();
        var product_id_modal = $("#product_id_modal option:selected").val();
        var cmd = $("#cmd").val();
        var id = $("#id").val();
        var module_id = $("#module_id").val();

        var dataString = 'type=add_package&package_name=' + package_name + '&sku_code=' + sku_code + '&case_pack=' + case_pack + '&pack_desc=' + pack_desc + '&product_id=' + product_id_modal + '&product_category=' + product_category + '&cmd=' + cmd + '&id=' + id + '&module_id=' + module_id;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data != 'Select') {
                    $("#package_name").val(""); 
                    $("#sku_code_modal").val(""); 
                    $("#case_pack_modal").val(""); 
                    $("#pack_desc_modal").val(""); 
                    $('#category_id_modal option[value=""]').prop('selected', true).trigger('change');
                    $('#product_id_modal option[value=""]').prop('selected', true).trigger('change');
                    if(selected_package_id > '0'){
                        $("#packageids_"+selected_package_id).children().last().before(data);
                    }
                    else{
                        $("#package_id").append(data);
                    }
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>