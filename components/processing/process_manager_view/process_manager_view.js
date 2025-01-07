$(document).ready(function() {
   
    $('.bin_user_id').on('change', function() {
        var bin_id  = $(this).attr('id');
        var parts = bin_id.split('-');
        var bin_id = parts[1]; 
        var bin_user_id = $(this).val();
        var module_id = $("#module_id").val();
        let dataString = `module_id=${module_id}&type=assign_bin&bin_id=${bin_id}&bin_user_id=${bin_user_id}`;
        $.ajax({
            type: "POST",
            url: "ajax/ajax_add_entries.php",
            data: dataString,
            success: function (response) {
                // Find the element with ID 'removedBin'
                const htmlContent = $("<div>").html(response);
                // Find the element with ID 'removedBin' in the parsed HTML
                const spanElement = htmlContent.find("#removedBin");
                if (spanElement.length === 1) {
                    const toastHTML = 'Bin has been removed.';
                    showToast(toastHTML, "");
                } 
                else if (response === "Fail") {
                    var toastHTML = 'There is some Error.';
                    showToast(toastHTML, "Fail");
                } 
                else{
                    var toastHTML = 'Bin has been assigned successfully.';
                    showToast(toastHTML, "Success");
                } 
                $(".bin_users").html(response);
            },
            error: function () {
                alert('Error processing request.');
            }
        });
    }); 
    function showToast(message, type) {
        var toastClass = type === 'Success' ? 'green' : 'red';
        M.toast({
            html: message,
            classes: toastClass
        });
    } 
});