<?php
if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
}

$buttons = get_option('cstp_buttons');
$bg_color = get_option('cstp_bg_color');
$padding = get_option('cstp_padding');
$spacing = get_option('cstp_spacing');
$position = get_option('cstp_position');
$vertical_offset = get_option('cstp_vertical_offset');

if (isset($_POST['cstp_save_settings'])) {
    check_admin_referer('cstp_save_settings');

    $new_buttons = array();
    for ($i = 0; $i < count($_POST['button_text']); $i++) {
        $new_buttons[] = array(
            'icon' => esc_url($_POST['button_icon'][$i]),
            'text' => sanitize_text_field($_POST['button_text'][$i]),
            'link' => esc_url($_POST['button_link'][$i]),
        );
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
    <form method="post" action="">
        <?php wp_nonce_field('cstp_save_settings'); ?>
        <h2>Buttons</h2>
        <?php for ($i = 0; $i < count($buttons); $i++) : ?>
            <div class="cstp-button-settings">
                <label>Icon URL:</label>
                <input type="text" name="button_icon[<?php echo $i; ?>]" value="<?php echo esc_attr($buttons[$i]['icon']); ?>" /><br>
                <label>Text:</label>
                <input type="text" name="button_text[<?php echo $i; ?>]" value="<?php echo esc_attr($buttons[$i]['text']); ?>" /><br>
                <label>Link:</label>
                <input type="text" name="button_link[<?php echo $i; ?>]" value="<?php echo esc_attr($buttons[$i]['link']); ?>" /><br>
            </div>
        <?php endfor; ?>
        <h2>Tab Settings</h2>
        <label>Background Color (Hex):</label>
        <input type="text" name="bg_color" value="<?php echo esc_attr($bg_color); ?>" /><br>
        <label>Padding (px):</label>
        <input type="number" name="padding" value="<?php echo esc_attr($padding); ?>" /><br>
        <label>Spacing Between Buttons (px):</label>
        <input type="number" name="spacing" value="<?php echo esc_attr($spacing); ?>" /><br>
        <label>Position:</label>
        <select name="position">
            <option value="right" <?php selected($position, 'right'); ?>>Right</option>
            <option value="left" <?php selected($position, 'left'); ?>>Left</option>
        </select><br>
        <label>Vertical Offset (px from top):</label>
        <input type="number" name="vertical_offset" value="<?php echo esc_attr($vertical_offset); ?>" /><br>
        <input type="submit" name="cstp_save_settings" class="button-primary" value="Save Settings" />
    </form>
</div>
