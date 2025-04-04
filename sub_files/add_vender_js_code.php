<script>
    $(document).on('click', '#add_vender_btn', function(e) {
        var vender_name = $("#vender_name").val();
        var phone_no = $("#phone_no").val();
        var address = $("#address").val();
        var note_about_vender = $("#note_about_vender").val();
        var warranty_period_in_days = $("#warranty_period_in_days").val();
        var id = $("#id").val();
        var vender_type = $("#vender_type_id").val();
        var purchasing_agent_id = $("#purchasing_agent_id_modal option:selected").val();

        var dataString = 'type=add_vender&vender_name=' + vender_name + '&purchasing_agent_id=' + purchasing_agent_id + '&vender_type=' + vender_type + '&phone_no=' + phone_no + '&address=' + address + '&warranty_period_in_days=' + warranty_period_in_days + '&note_about_vender=' + note_about_vender + '&id=' + id;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data != 'Select') {
                    $("#vender_name").val("");
                    $("#phone_no").val("");
                    $("#address").val("");
                    $("#note_about_vender").val("");
                    $("#vender_id").append(data);
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>