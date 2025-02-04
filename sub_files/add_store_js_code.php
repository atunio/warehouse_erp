<script>
    $(document).on('click', '#add_store_btn', function(e) {
        var store_name = $("#store_name").val();
        var cmd = $("#cmd").val();
        var id = $("#id").val();
        var dataString = 'type=add_store&store_name=' + store_name + '&cmd=' + cmd + '&id=' + id;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data != 'Select') {
                    $("#store_name").val("");
                    $("#store_id").append(data);
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>