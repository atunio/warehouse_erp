<script>
    $(document).on('click', '#add_productid_btn', function(e) {
        var product_id = $("#product_id_modal").val();
        var cmd = $("#cmd").val();
        var id = $("#id").val();
        var module_id = $("#module_id").val();

        var dataString = 'type=add_productid&product_id=' + product_id + '&cmd=' + cmd + '&id=' + id + '&module_id=' + module_id;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data != 'Select') {
                    $("#product_id_modal").val("");
                    $("#product_uniqueid2").append(data);
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>