<script>
    $(document).on('click', '#add_customer_btn', function(e) {
        var customer_name = $("#customer_name").val();
        var phone_primary = $("#phone_primary").val();
        var email_primary = $("#email_primary").val();
        var address_primary = $("#address_primary").val();
        var address_primary_city = $("#address_primary_city").val();
        var address_primary_state = $("#address_primary_state").val();
        var address_primary_country = $("#address_primary_country").val();
        var module_id = $("#module_id").val();
        var dataString = 'type=add_customer&customer_name=' + customer_name + '&phone_primary=' + phone_primary + '&email_primary=' + email_primary + '&address_primary=' + address_primary + '&address_primary_city=' + address_primary_city + '&address_primary_state=' + address_primary_state + '&address_primary_country=' + address_primary_country + '&module_id=' + module_id;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data != 'Select') {
                    $("#customer_name").val("");
                    $("#phone_primary").val("");
                    $("#email_primary").val("");
                    $("#address_primary").val("");
                    $("#address_primary_city").val("");
                    $("#address_primary_state").val("");
                    $('#address_primary_country').val("").trigger('change');
                    $("#customer_id").append(data);
                }
            },
            error: function() {
                ;
            }
        });
    });
</script>