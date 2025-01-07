<script>
    $(document).on('click', '#add_product_btn', function(e) {

        var product_desc = $("#product_desc").val();
        var product_category = $("#product_category").val();
        var detail_desc = $("#detail_desc").val();
        var product_uniqueid = $("#product_uniqueid").val();
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
                    $("#product_uniqueid").val("");
                    $("#product_desc").val("");
                    $('#product_category').val("").trigger('change');
                    $("#detail_desc2").val("");
                    $("#product_id").append(data);
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>