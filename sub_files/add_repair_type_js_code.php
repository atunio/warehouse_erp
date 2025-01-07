<script>
    $(document).on('click', '#add_repair_type_btn', function(e) {
        var repair_type_name = $("#repair_type_name").val();
        var cmd = $("#cmd").val();
        var id = $("#id").val();
        var dataString = 'type=add_repair_type&repair_type_name=' + repair_type_name;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data != 'Select') {
                    $("#repair_type_name").val("");
                    $("#repair_type").append(data);
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>