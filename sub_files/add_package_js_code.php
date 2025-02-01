<script>
    $(document).on('click', '#add_package_btn', function(e) {
        var package_name = $("#package_name").val();
        var sku_code = $("#sku_code").val();
        var product_category = $("#company_id_customer option:selected").val();
        var inquiry_id_modal = $("#inquiry_id_modal option:selected").val();
        var cmd = $("#cmd").val();
        var id = $("#id").val();
        var module_id = $("#module_id").val();

        var dataString = 'type=add_package&sku_code=' + sku_code + '&package_name=' + package_name + '&product_id=' + inquiry_id_modal + '&product_category=' + product_category + '&cmd=' + cmd + '&id=' + id + '&module_id=' + module_id;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data != 'Select') {
                    $("#package_name").val("");
                    $("#sku_code").val("");
                    $("#package_id").append(data);
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>