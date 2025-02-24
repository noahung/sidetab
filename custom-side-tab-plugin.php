<?php
/*
Plugin Name: Custom Side Tab Plugin
Description: A customizable side tab with buttons for WordPress websites.
Version: 1.0
Author: Your Name
Plugin Icon: logo.png
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue styles and scripts
function cstp_enqueue_scripts() {
    wp_enqueue_style('cstp-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_script('cstp-script', plugins_url('js/script.js', __FILE__), array('jquery', 'media-upload', 'thickbox'), '1.0', true);
    wp_enqueue_media(); // Enqueue media uploader
    wp_enqueue_style('thickbox');
}
add_action('wp_enqueue_scripts', 'cstp_enqueue_scripts');
add_action('admin_enqueue_scripts', 'cstp_enqueue_scripts'); // Also enqueue in admin

// Register admin menu
function cstp_register_admin_menu() {
    add_menu_page('Side Tab Settings', 'Side Tab', 'manage_options', 'cstp-settings', 'cstp_settings_page', plugins_url('plugin-logo.png', __FILE__));
}
add_action('admin_menu', 'cstp_register_admin_menu');

// Include settings page
function cstp_settings_page() {
    include 'includes/settings.php';
}

// Activation hook
function cstp_activate() {
    // Create default options with 2 buttons
    add_option('cstp_buttons', array(
        array('icon' => '', 'text' => '', 'link' => ''),
        array('icon' => '', 'text' => '', 'link' => ''),
    ));
    add_option('cstp_bg_color', '#ff8c69');
    add_option('cstp_padding', '10');
    add_option('cstp_spacing', '5');
    add_option('cstp_position', 'right');
    add_option('cstp_vertical_offset', '50');
}
register_activation_hook(__FILE__, 'cstp_activate');

// Add shortcode to display the tab
function cstp_display_tab() {
    $buttons = get_option('cstp_buttons', array());
    $bg_color = get_option('cstp_bg_color', '#ff8c69');
    $padding = get_option('cstp_padding', '10');
    $spacing = get_option('cstp_spacing', '5');
    $position = get_option('cstp_position', 'right');
    $vertical_offset = get_option('cstp_vertical_offset', '50');

    ob_start();
    ?>
    <div class="cstp-side-tab <?php echo $position; ?>" style="background-color: <?php echo $bg_color; ?>; padding: <?php echo $padding; ?>px; top: <?php echo $vertical_offset; ?>px;">
        <button class="cstp-toggle-btn">></button>
        <div class="cstp-content">
            <?php foreach ($buttons as $button) : ?>
                <?php if (!empty($button['text']) || !empty($button['link']) || !empty($button['icon'])) : ?>
                    <a href="<?php echo esc_url($button['link']); ?>" class="cstp-button" style="margin-bottom: <?php echo $spacing; ?>px;">
                        <?php if (!empty($button['icon'])) : ?>
                            <img src="<?php echo esc_url($button['icon']); ?>" alt="<?php echo esc_attr($button['text']); ?>">
                        <?php endif; ?>
                        <?php echo esc_html($button['text']); ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_side_tab', 'cstp_display_tab');
