<script>
    $(document).on('click', '#add_category_btn', function(e) {
        var category_name = $("#category_name").val();
        var cmd = $("#cmd").val();
        var id = $("#id").val();
        var module_id = $("#module_id").val();

        var dataString = 'type=add_category&category_name=' + category_name + '&cmd=' + cmd + '&id=' + id + '&module_id=' + module_id;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data != 'Select') {
                    $("#category_name").val("");
                    $("#product_category2").append(data);
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>