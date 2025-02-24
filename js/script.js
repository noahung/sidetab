// JavaScript source code
jQuery(document).ready(function($) {
    $('.cstp-toggle-btn').on('click', function() {
        $(this).closest('.cstp-side-tab').toggleClass('expanded');
        setTimeout(function() {
            $(this).closest('.cstp-side-tab').removeClass('expanded');
        }.bind(this), 5000); // Auto-hide after 5 seconds
    });
});
