jQuery(document).ready(function($) {
    // Media Uploader for Icons
    $('.cstp-media-upload').on('click', function(e) {
        e.preventDefault();
        var input = $(this).data('input');
        var frame = wp.media({
            title: 'Select or Upload Icon',
            button: { text: 'Use this image' },
            multiple: false
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#' + input).val(attachment.url);
            $('#' + input).siblings('.cstp-icon-preview').remove();
            $(`<img src="${attachment.url}" alt="Preview" class="cstp-icon-preview" style="max-width: 50px; margin-left: 10px;">`).insertAfter('#' + input);
        });
        frame.open();
    });

    // Add New Button
    $('#cstp-add-button').on('click', function() {
        var container = $('#cstp-buttons-container');
        var buttonCount = container.children('.cstp-button-settings').length;
        if (buttonCount < 10) { // Limit to 10 buttons for practicality
            var newButton = `
                <div class="cstp-button-settings">
                    <h3>Button ${buttonCount + 1}</h3>
                    <div class="cstp-field">
                        <label>Icon:</label>
                        <input type="hidden" name="button_icon[${buttonCount}]" id="cstp_button_icon_${buttonCount}" value="" />
                        <button type="button" class="button cstp-media-upload" data-input="cstp_button_icon_${buttonCount}">Upload Icon</button>
                    </div>
                    <div class="cstp-field">
                        <label>Text:</label>
                        <input type="text" name="button_text[${buttonCount}]" value="" />
                    </div>
                    <div class="cstp-field">
                        <label>Link:</label>
                        <input type="text" name="button_link[${buttonCount}]" value="" />
                    </div>
                    <button type="button" class="button cstp-remove-button" data-index="${buttonCount}">Remove Button</button>
                </div>
            `;
            container.append(newButton);
            // Reinitialize media uploader for the new button
            $('.cstp-media-upload').off('click').on('click', function(e) {
                e.preventDefault();
                var input = $(this).data('input');
                var frame = wp.media({
                    title: 'Select or Upload Icon',
                    button: { text: 'Use this image' },
                    multiple: false
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#' + input).val(attachment.url);
                    $('#' + input).siblings('.cstp-icon-preview').remove();
                    $(`<img src="${attachment.url}" alt="Preview" class="cstp-icon-preview" style="max-width: 50px; margin-left: 10px;">`).insertAfter('#' + input);
                });
                frame.open();
            });
        } else {
            alert('Maximum 10 buttons allowed.');
        }
    });

    // Remove Button
    $(document).on('click', '.cstp-remove-button', function() {
        var index = $(this).data('index');
        $(this).closest('.cstp-button-settings').remove();
        // Re-number the remaining buttons
        $('#cstp-buttons-container .cstp-button-settings').each(function(i) {
            $(this).find('h3').text('Button ' + (i + 1));
        });
    });

    // Side tab toggle (existing functionality)
    $('.cstp-toggle-btn').on('click', function() {
        $(this).closest('.cstp-side-tab').toggleClass('expanded');
        setTimeout(function() {
            $(this).closest('.cstp-side-tab').removeClass('expanded');
        }.bind(this), 5000); // Auto-hide after 5 seconds
    });
});
