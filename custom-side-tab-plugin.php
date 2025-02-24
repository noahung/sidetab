<?php
/*
Plugin Name: Custom Side Tab Plugin
Description: A customizable side tab plugin for WordPress.
Version: 1.0
Author: Noah Aung
*/

// Enqueue scripts and styles
function cst_enqueue_scripts() {
    wp_enqueue_style('cst-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('cst-script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'cst_enqueue_scripts');

// Create the settings page
function cst_create_menu() {
    add_menu_page('Custom Side Tab', 'Side Tab Settings', 'manage_options', 'cst-settings', 'cst_settings_page', null, 99);
}
add_action('admin_menu', 'cst_create_menu');

// Settings page callback
function cst_settings_page() {
    if (isset($_POST['cst_save_settings'])) {
        update_option('cst_buttons', $_POST['cst_buttons']);
        update_option('cst_bg_color', $_POST['cst_bg_color']);
        update_option('cst_position', $_POST['cst_position']);
        update_option('cst_toggle', $_POST['cst_toggle']);
    }
    $buttons = get_option('cst_buttons', []);
    $bg_color = get_option('cst_bg_color', '#ffffff');
    $position = get_option('cst_position', 'left');
    $toggle = get_option('cst_toggle', 'yes');
    
    ?>
    <div class="wrap">
        <h1>Custom Side Tab Settings</h1>
        <form method="post" action="">
            <h3>Buttons</h3>
            <div id="cst_buttons_container">
                <?php foreach ($buttons as $index => $button) : ?>
                    <div class="cst_button">
                        <h4>Button <?php echo $index + 1; ?></h4>
                        <input type="text" name="cst_buttons[<?php echo $index; ?>][text]" placeholder="Button Text" value="<?php echo esc_attr($button['text']); ?>" />
                        <input type="text" name="cst_buttons[<?php echo $index; ?>][link]" placeholder="Button Link" value="<?php echo esc_url($button['link']); ?>" />
                        <input type="file" name="cst_buttons[<?php echo $index; ?>][icon]" placeholder="Upload Icon" />
                        <input type="hidden" name="cst_buttons[<?php echo $index; ?>][icon_url]" value="<?php echo esc_url($button['icon_url']); ?>" />
                        <br />
                            <input type="color" name="cst_bg_color" value="<?php echo $bg_color; ?>" />
                            <input type="number" name="cst_position" placeholder="Vertical Position in Pixels" value="<?php echo esc_attr($position); ?>" />
                            <label><input type="checkbox" name="cst_toggle" value="yes" <?php checked($toggle, 'yes'); ?>> Toggle Visibility</label>
                            <button type="button" class="remove_button">Remove</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add_button">Add Button</button>
            <input type="submit" name="cst_save_settings" value="Save Settings" />
        </form>
    </div>
    <script>
    jQuery(document).ready(function($) {
        // Add new button logic
        $('#add_button').click(function() {
            $('#cst_buttons_container').append('<div class="cst_button"><h4>Button</h4><input type="text" name="cst_buttons[][text]" placeholder="Button Text" /><input type="text" name="cst_buttons[][link]" placeholder="Button Link" /><input type="file" name="cst_buttons[][icon]" placeholder="Upload Icon" /><button type="button" class="remove_button">Remove</button></div>');
        });

        // Remove button logic
        $(document).on('click', '.remove_button', function() {
            $(this).closest('.cst_button').remove();
        });
    });
    </script>
    <?php
}

// Render the side tab
function cst_render_side_tab() {
    $buttons = get_option('cst_buttons', []);
    $bg_color = get_option('cst_bg_color', '#ffffff');
    $position = get_option('cst_position', 'left');
    $toggle = get_option('cst_toggle', 'yes');
    
    if ($toggle === 'yes') {
        echo '<div class="cst_side_tab" style="background-color: ' . esc_attr($bg_color) . '; position: fixed; ' . ($position == 'left' ? 'left: 0;' : 'right: 0;') . ' top: ' . esc_attr($position) . 'px;">';
        echo '<div class="cst_toggle">►</div>';
        echo '<div class="cst_buttons" style="display:none;">';
        
        foreach ($buttons as $button) {
            echo '<a href="' . esc_url($button['link']) . '" class="cst_button"><img src="' . esc_url($button['icon_url']) . '" /><span>' . esc_html($button['text']) . '</span></a>';
        }
        
        echo '</div></div>';
    }
}
add_action('wp_footer', 'cst_render_side_tab');

?>
