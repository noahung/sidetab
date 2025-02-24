<?php
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

$buttons = get_option('cstp_buttons', array(
    array('icon' => '', 'text' => '', 'link' => ''),
    array('icon' => '', 'text' => '', 'link' => ''),
)); // Default to 2 buttons
$bg_color = get_option('cstp_bg_color', '#ff8c69');
$padding = get_option('cstp_padding', '10');
$spacing = get_option('cstp_spacing', '5');
$position = get_option('cstp_position', 'right');
$vertical_offset = get_option('cstp_vertical_offset', '50');

if (isset($_POST['cstp_save_settings'])) {
    check_admin_referer('cstp_save_settings');

    $new_buttons = array();
    $button_texts = isset($_POST['button_text']) ? $_POST['button_text'] : array();
    $button_links = isset($_POST['button_link']) ? $_POST['button_link'] : array();
    $button_icons = isset($_POST['button_icon']) ? $_POST['button_icon'] : array();

    for ($i = 0; $i < count($button_texts); $i++) {
        if (!empty($button_texts[$i]) || !empty($button_links[$i]) || !empty($button_icons[$i])) {
            $new_buttons[] = array(
                'icon' => esc_url($button_icons[$i]),
                'text' => sanitize_text_field($button_texts[$i]),
                'link' => esc_url($button_links[$i]),
            );
        }
    }
    update_option('cstp_buttons', $new_buttons);
    update_option('cstp_bg_color', sanitize_hex_color($_POST['bg_color']));
    update_option('cstp_padding', intval($_POST['padding']));
    update_option('cstp_spacing', intval($_POST['spacing']));
    update_option('cstp_position', sanitize_text_field($_POST['position']));
    update_option('cstp_vertical_offset', intval($_POST['vertical_offset']));
    $buttons = $new_buttons;
    $bg_color = get_option('cstp_bg_color');
    $padding = get_option('cstp_padding');
    $spacing = get_option('cstp_spacing');
    $position = get_option('cstp_position');
    $vertical_offset = get_option('cstp_vertical_offset');
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="" id="cstp-settings-form">
        <?php wp_nonce_field('cstp_save_settings'); ?>

        <h2>Buttons</h2>
        <div id="cstp-buttons-container">
            <?php foreach ($buttons as $index => $button) : ?>
                <div class="cstp-button-settings">
                    <h3>Button <?php echo $index + 1; ?></h3>
                    <div class="cstp-field">
                        <label>Icon:</label>
                        <input type="hidden" name="button_icon[<?php echo $index; ?>]" id="cstp_button_icon_<?php echo $index; ?>" value="<?php echo esc_attr($button['icon']); ?>" />
                        <button type="button" class="button cstp-media-upload" data-input="cstp_button_icon_<?php echo $index; ?>">Upload Icon</button>
                        <?php if (!empty($button['icon'])) : ?>
                            <img src="<?php echo esc_url($button['icon']); ?>" alt="Preview" class="cstp-icon-preview" style="max-width: 50px; margin-left: 10px;">
                        <?php endif; ?>
                    </div>
                    <div class="cstp-field">
                        <label>Text:</label>
                        <input type="text" name="button_text[<?php echo $index; ?>]" value="<?php echo esc_attr($button['text']); ?>" />
                    </div>
                    <div class="cstp-field">
                        <label>Link:</label>
                        <input type="text" name="button_link[<?php echo $index; ?>]" value="<?php echo esc_attr($button['link']); ?>" />
                    </div>
                    <?php if ($index > 1) : ?>
                        <button type="button" class="button cstp-remove-button" data-index="<?php echo $index; ?>">Remove Button</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button" id="cstp-add-button">Add New Button</button>

        <h2>Tab Settings</h2>
        <div class="cstp-tab-settings">
            <div class="cstp-field">
                <label>Background Color (Hex):</label>
                <input type="text" name="bg_color" value="<?php echo esc_attr($bg_color); ?>" />
            </div>
            <div class="cstp-field">
                <label>Padding (px):</label>
                <input type="number" name="padding" value="<?php echo esc_attr($padding); ?>" min="0" />
            </div>
            <div class="cstp-field">
                <label>Spacing Between Buttons (px):</label>
                <input type="number" name="spacing" value="<?php echo esc_attr($spacing); ?>" min="0" />
            </div>
            <div class="cstp-field">
                <label>Position:</label>
                <select name="position">
                    <option value="right" <?php selected($position, 'right'); ?>>Right</option>
                    <option value="left" <?php selected($position, 'left'); ?>>Left</option>
                </select>
            </div>
            <div class="cstp-field">
                <label>Vertical Offset (px from top):</label>
                <input type="number" name="vertical_offset" value="<?php echo esc_attr($vertical_offset); ?>" min="0" />
            </div>
        </div>

        <input type="submit" name="cstp_save_settings" class="button-primary" value="Save Settings" />
    </form>
</div>

<style>
    .cstp-button-settings, .cstp-tab-settings {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 4px;
    }
    .cstp-field {
        margin-bottom: 10px;
    }
    .cstp-field label {
        display: inline-block;
        width: 200px;
        font-weight: bold;
    }
    .cstp-icon-preview {
        vertical-align: middle;
    }
</style>
