<script>
    $(document).on('click', '#add_agent_btn', function(e) {
        var agent_name = $("#agent_name").val();
        var phone_no = $("#agent_phone_no").val();
        var address = $("#agent_address").val();
        var note_about_agent = $("#note_about_agent").val();
        var cmd = $("#cmd").val();
        var id = $("#id").val();
        var dataString = 'type=add_agent&agent_name=' + agent_name + '&phone_no=' + phone_no + '&address=' + address + '&note_about_agent=' + note_about_agent + '&cmd=' + cmd + '&id=' + id;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data != 'Select') {
                    $("#agent_name").val("");
                    $("#agent_phone_no").val("");
                    $("#agent_address").val("");
                    $("#note_about_agent").val("");
                    $("#purchasing_agent_id").append(data);
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>