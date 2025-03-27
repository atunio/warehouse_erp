$(document).ready(function() {
    $('.select2').on('select2:open', function() {
        setTimeout(function() {
            $('.select2-search__field').on('keydown', function(e) {
                if (e.key === "Tab") {
                    e.preventDefault(); // Prevent default tab behavior
                    let highlighted = $('.select2-results__option--highlighted');
                    alert(highlighted);
                    if (highlighted.length) {
                        highlighted.trigger('mouseup'); // Select the highlighted option
                    }
                }
            });
        }, 100);
    });
});
