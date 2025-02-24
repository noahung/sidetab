jQuery(document).ready(function($) {
    $('.cst_toggle').click(function() {
        $(this).next('.cst_buttons').toggle();
        $(this).text($(this).next().is(':visible') ? '▼' : '►');
    });
});
