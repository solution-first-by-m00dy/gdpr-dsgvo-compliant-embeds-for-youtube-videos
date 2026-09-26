<?php

/**
 * @package         GDPR_YouTube_Videos_Embed_SF
 * @license         GPLv2 or later
 * @license URI     https://www.gnu.org/licenses/gpl-2.0.html
 */

if (! defined('ABSPATH')) exit; // Exit if accessed directly



add_action('admin_enqueue_scripts', function () {
    $screen = get_current_screen();
    if (!$screen || 'dsgvo_video' !== $screen->post_type) {
        return;
    }
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script(
        'dsgvo-yt-color-picker',
        DSGVO_YT_PLUGIN_URL . 'assets/js/dsgvo-yt-color-picker.js',
        ['wp-color-picker', 'jquery'],
        DSGVO_YT_VERSION,
        true
    );
});

// Register Custom Post Type
add_action('init', 'dsgvo_yt_register_post_type');
function dsgvo_yt_register_post_type()
{
    register_post_type('dsgvo_video', array(
        'labels' => array(
            'name'               => __('Videos', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'),
            'singular_name'      => __('Video', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'),
            'add_new_item'       => __('Add New Video', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'),
            'edit_item'          => __('Edit Video', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'),
            'all_items'          => __('All Videos', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'),
        ),
        'public'        => false,
        'publicly_queryable' => false,
        'show_in_rest'  => false,
        'query_var'     => false,
        'rewrite'       => false,
        'exclude_from_search' => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'supports'      => array('title'),
        'menu_icon'     => 'dashicons-video-alt',
    ));
}

// Add meta box for DSGVO Videos
add_action('add_meta_boxes', function () {
    add_meta_box(
        'dsgvo_yt_video_settings',
        __('Video Settings', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'),
        'dsgvo_yt_video_settings_callback',
        'dsgvo_video',
        'normal',
        'high'
    );
});

// Render settings fields
function dsgvo_yt_video_settings_callback($post)
{
    wp_nonce_field('dsgvo_yt_save', 'dsgvo_yt_nonce');

    // Retrieve existing values or defaults
    $iframe     = dsgvo_yt_meta($post->ID, '_dsgvo_yt_iframe');
    $template   = dsgvo_yt_meta($post->ID, '_dsgvo_yt_template') ?: 'light';
    $btn_text   = dsgvo_yt_meta($post->ID, '_dsgvo_yt_button_text') ?: __('Load YouTube Video', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');
    $btn_shape = dsgvo_yt_meta($post->ID, '_dsgvo_yt_button_shape');
    if (! in_array($btn_shape, ['rounded', 'square'], true)) {
        $btn_shape = 'rounded'; // Default
    }

    $overlay_bg = dsgvo_yt_meta($post->ID, '_dsgvo_yt_overlay_bg') ?: '#ffffff';
    $button_bg  = dsgvo_yt_meta($post->ID, '_dsgvo_yt_button_bg') ?: '#0073aa';
    $btn_color  = dsgvo_yt_meta($post->ID, '_dsgvo_yt_button_color') ?: '#ffffff';
    $btn_font_size = dsgvo_yt_meta($post->ID, '_dsgvo_yt_button_font_size');
    $privacy_color = dsgvo_yt_meta($post->ID, '_dsgvo_yt_privacy_color') ?: '#666666';
    $privacy_enabled = dsgvo_yt_meta($post->ID, '_dsgvo_yt_privacy_enabled') ?: 0;
    $privacy_link = dsgvo_yt_meta($post->ID, '_dsgvo_yt_privacy_link') ?: '';

    $privacy_text = dsgvo_yt_meta($post->ID, '_dsgvo_yt_privacy_text') ?: '';
    $privacy_link_text = dsgvo_yt_meta($post->ID, '_dsgvo_yt_privacy_link_text') ?: '';
    $privacy_font_size = dsgvo_yt_meta($post->ID, '_dsgvo_yt_privacy_font_size');
    $privacy_link_font_size = dsgvo_yt_meta($post->ID, '_dsgvo_yt_privacy_link_font_size');
    $message_text = dsgvo_yt_meta($post->ID, '_dsgvo_yt_message_text') ?: '';
    $message_font_size = dsgvo_yt_meta($post->ID, '_dsgvo_yt_message_font_size');

    $width = dsgvo_yt_meta($post->ID, '_dsgvo_yt_width', '100%');
    $height = dsgvo_yt_meta($post->ID, '_dsgvo_yt_height', '100%');
    $load_all_enabled = dsgvo_yt_meta($post->ID, '_dsgvo_yt_load_all_enabled') ?: 0;
    $remember_enabled = dsgvo_yt_meta($post->ID, '_dsgvo_yt_remember_enabled') ?: 0;
    $remember_style = dsgvo_yt_meta($post->ID, '_dsgvo_yt_remember_style', 'classic');
    if (!in_array($remember_style, array('classic', 'modern'), true)) {
        $remember_style = 'classic';
    }
    $remember_text = dsgvo_yt_meta($post->ID, '_dsgvo_yt_remember_text') ?: __('Remember selection', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');
    $remember_font_size = dsgvo_yt_meta($post->ID, '_dsgvo_yt_remember_font_size');
    $remember_color = dsgvo_yt_meta($post->ID, '_dsgvo_yt_remember_color');


    // Set default if empty
    if ('' === $width) {
        $width = '100%';
    }
    if ('' === $height) {
        $height = '100%';
    }
    ?>
    <p>
        <strong><?php esc_html_e('Shortcode:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></strong><br>
        <input type="text" readonly style="width:100%;" value="<?php echo esc_attr("[dsgvo_video id=\"{$post->ID}\"]"); ?>" onclick="this.select();">
    </p>
    <p>
        <strong><?php esc_html_e('Reset consent in page content', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></strong><br>
        <?php
        /* translators: %s: the standalone reset shortcode, displayed as code. */
        printf(esc_html__('Insert %s anywhere in the page content to show a separate reset button. It unloads all videos on the current page and removes the remembered choice for this website.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'), '<code>[dsgvo_video_reset]</code>');
        ?>
    </p>

    <br>
    <hr>
    <br>

    <p>
        <label for="dsgvo_yt_iframe"><?php esc_html_e('iframe Code:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <textarea id="dsgvo_yt_iframe" name="dsgvo_yt_iframe" style="width:100%;height:100px;" aria-describedby="dsgvo-yt-iframe-help"><?php printf('%s', esc_textarea($iframe)); ?></textarea>
        <span id="dsgvo-yt-iframe-help" class="description"><?php esc_html_e('Paste a YouTube embed iframe with an HTTPS source URL. Other iframe sources are not supported.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>

    </p>

    <h4><?php esc_html_e('Button Settings', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></h4>

    <p>
        <label><?php esc_html_e('Button Text:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            type="text"
            name="dsgvo_yt_button_text"
            value="<?php printf('%s', esc_attr($btn_text)); ?>"
            style="width:100%;" />
    </p>

    <p>
        <label for="dsgvo_yt_button_font_size"><?php esc_html_e('Button Font Size:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_button_font_size"
            name="dsgvo_yt_button_font_size"
            type="text"
            value="<?php printf('%s', esc_attr($btn_font_size)); ?>"
            style="width:100px;"
            placeholder="<?php esc_attr_e('Theme default', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>"><br>
        <span class="description"><?php esc_html_e('Theme default; leave empty to keep existing appearance.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>
    </p>

    <p>
        <label><?php esc_html_e('Button Type:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <label style="margin-right:1em;">
            <input
                type="radio"
                name="dsgvo_yt_button_shape"
                value="rounded"
                <?php checked($btn_shape, 'rounded'); ?> />
            <?php esc_html_e('Rounded', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>
        </label>
        <label>
            <input
                type="radio"
                name="dsgvo_yt_button_shape"
                value="square"
                <?php checked($btn_shape, 'square'); ?> />
            <?php esc_html_e('Squared', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>
        </label>
    </p>

    <h4><?php esc_html_e('Style Settings', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></h4>

    <p>
        <label><?php esc_html_e('Design:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <select id="dsgvo_yt_template" name="dsgvo_yt_template">
            <option value="light" <?php selected($template, 'light'); ?>><?php esc_html_e('Light', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></option>
            <option value="dark" <?php selected($template, 'dark');  ?>><?php esc_html_e('Dark',  'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></option>
            <option value="custom" <?php selected($template, 'custom'); ?>><?php esc_html_e('Custom', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></option>
        </select>
    </p>

    <div id="dsgvo_yt_custom_colors" style="display:<?php printf('%s', esc_attr($template === 'custom' ? 'block' : 'none')); ?>;">
        <p>
            <label><?php esc_html_e('Overlay Background Color:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
            <input
                type="text"
                name="dsgvo_yt_overlay_bg"
                value="<?php printf('%s', esc_attr($overlay_bg)); ?>"
                class="wp-color-picker-field"
                data-default-color="#ffffff" />
        </p>

        <p>
            <label><?php esc_html_e('Button Background Color:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
            <input
                type="text"
                name="dsgvo_yt_button_bg"
                value="<?php printf('%s', esc_attr($button_bg)); ?>"
                class="wp-color-picker-field"
                data-default-color="#0073aa" />
        </p>

        <p>
            <label><?php esc_html_e('Button Text Color:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
            <input
                type="text"
                name="dsgvo_yt_button_color"
                value="<?php printf('%s', esc_attr($btn_color)); ?>"
                class="wp-color-picker-field"
                data-default-color="#ffffff" />
        </p>

        <p>
            <label><?php esc_html_e('Privacy Info Text Color:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
            <input
                type="text"
                name="dsgvo_yt_privacy_color"
                value="<?php printf('%s', esc_attr($privacy_color)); ?>"
                class="wp-color-picker-field"
                data-default-color="#666666" />
        </p>
    </div>

    <h4><?php esc_html_e('Size Settings (% or px)', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></h4>

    <p>
        <label for="dsgvo_yt_width"><?php esc_html_e('Width:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label>
        <input
            id="dsgvo_yt_width"
            name="dsgvo_yt_width"
            type="text"
            value="<?php printf('%s', esc_attr($width)); ?>"
            style="width:100px;"
            placeholder="<?php esc_attr_e('100% or 600px', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>">
    </p>
    <p>
        <label for="dsgvo_yt_height"><?php esc_html_e('Height:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label>
        <input
            id="dsgvo_yt_height"
            name="dsgvo_yt_height"
            type="text"
            value="<?php printf('%s', esc_attr($height)); ?>"
            style="width:100px;"
            placeholder="<?php esc_attr_e('100% or 450px', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>">
    </p>

    <h4><?php esc_html_e('Privacy Settings', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></h4>

    <p>
        <input type="hidden" name="dsgvo_yt_privacy_enabled" value="0">
        <label>
            <input
                type="checkbox"
                name="dsgvo_yt_privacy_enabled"
                value="1"
                <?php checked($privacy_enabled, 1); ?>>
            <?php esc_html_e('Enable privacy notice', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>
        </label>
    </p>

    <p>
        <label for="dsgvo_yt_privacy_text"><?php esc_html_e('Privacy Policy Text:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_privacy_text"
            name="dsgvo_yt_privacy_text"
            type="text"
            value="<?php printf('%s', esc_attr($privacy_text)); ?>"
            style="width:100%;">
    </p>

    <p>
        <label for="dsgvo_yt_privacy_font_size"><?php esc_html_e('Privacy Text Font Size:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_privacy_font_size"
            name="dsgvo_yt_privacy_font_size"
            type="text"
            value="<?php printf('%s', esc_attr($privacy_font_size)); ?>"
            style="width:100px;"
            placeholder="<?php esc_attr_e('Theme default', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>"><br>
        <span class="description"><?php esc_html_e('Theme default; leave empty to keep existing appearance.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>
    </p>

    <p>
        <label for="dsgvo_yt_privacy_link_text"><?php esc_html_e('Privacy Policy URL Text:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_privacy_link_text"
            name="dsgvo_yt_privacy_link_text"
            type="text"
            value="<?php printf('%s', esc_attr($privacy_link_text)); ?>"
            style="width:100%;">
    </p>

    <p>
        <label for="dsgvo_yt_privacy_link_font_size"><?php esc_html_e('Privacy Link Font Size:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_privacy_link_font_size"
            name="dsgvo_yt_privacy_link_font_size"
            type="text"
            value="<?php printf('%s', esc_attr($privacy_link_font_size)); ?>"
            style="width:100px;"
            placeholder="<?php esc_attr_e('Theme default', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>"><br>
        <span class="description"><?php esc_html_e('Theme default; leave empty to keep existing appearance.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>
    </p>

    <p>
        <label for="dsgvo_yt_privacy_link"><?php esc_html_e('Privacy Policy URL:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_privacy_link"
            name="dsgvo_yt_privacy_link"
            type="url"
            value="<?php printf('%s', esc_attr($privacy_link)); ?>"
            style="width:100%;">
    </p>

    <p>
        <label for="dsgvo_yt_message_text"><?php esc_html_e('Overlay Message:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <textarea
            id="dsgvo_yt_message_text"
            name="dsgvo_yt_message_text"
            style="width:100%;height:70px;"><?php printf('%s', esc_textarea($message_text)); ?></textarea><br>
        <span class="description"><?php esc_html_e('Optional text shown between the button and the privacy notice.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>
    </p>

    <p>
        <label for="dsgvo_yt_message_font_size"><?php esc_html_e('Message Font Size:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_message_font_size"
            name="dsgvo_yt_message_font_size"
            type="text"
            value="<?php printf('%s', esc_attr($message_font_size)); ?>"
            style="width:100px;"
            placeholder="<?php esc_attr_e('Theme default', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>"><br>
        <span class="description"><?php esc_html_e('Theme default; leave empty to keep existing appearance.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>
    </p>

    <h4><?php esc_html_e('Load Behavior', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></h4>

    <p>
        <input type="hidden" name="dsgvo_yt_load_all_enabled" value="0">
        <label>
            <input
                type="checkbox"
                name="dsgvo_yt_load_all_enabled"
                value="1"
                <?php checked($load_all_enabled, 1); ?>>
            <?php esc_html_e('Load all videos on one page', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>
        </label><br>
        <span class="description"><?php esc_html_e('If this option is enabled for multiple videos on the same page, one click loads all enabled videos together.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>
    </p>

    <p>
        <input type="hidden" name="dsgvo_yt_remember_enabled" value="0">
        <label>
            <input
                type="checkbox"
                name="dsgvo_yt_remember_enabled"
                value="1"
                <?php checked($remember_enabled, 1); ?>>
            <?php esc_html_e('Show remember selection', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>
        </label><br>
        <span class="description"><?php esc_html_e('Shows a checkbox in the overlay. If checked when loading, a site-wide cookie remembers consent for 180 days for videos with this option enabled. Use show_reset="true" for a reset control inside the video, or place [dsgvo_video_reset] separately in the page content.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>
    </p>

    <p>
        <label for="dsgvo_yt_remember_style"><?php esc_html_e('Checkbox design', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <select id="dsgvo_yt_remember_style" name="dsgvo_yt_remember_style" aria-describedby="dsgvo_yt_remember_style_help">
            <option value="classic" <?php selected($remember_style, 'classic'); ?>><?php esc_html_e('Existing design', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></option>
            <option value="modern" <?php selected($remember_style, 'modern'); ?>><?php esc_html_e('Modern design (larger checkbox)', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></option>
        </select><br>
        <span id="dsgvo_yt_remember_style_help" class="description"><?php esc_html_e('The modern design is optional. Existing videos keep their current checkbox until you select it here.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>
    </p>

    <p>
        <label for="dsgvo_yt_remember_text"><?php esc_html_e('Remember Selection Text:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_remember_text"
            name="dsgvo_yt_remember_text"
            type="text"
            value="<?php printf('%s', esc_attr($remember_text)); ?>"
            style="width:100%;">
    </p>

    <p>
        <label for="dsgvo_yt_remember_font_size"><?php esc_html_e('Remember Selection Font Size:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_remember_font_size"
            name="dsgvo_yt_remember_font_size"
            type="text"
            value="<?php printf('%s', esc_attr($remember_font_size)); ?>"
            style="width:100px;"
            placeholder="<?php esc_attr_e('Theme default', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>"><br>
        <span class="description"><?php esc_html_e('Theme default; leave empty to keep existing appearance.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>
    </p>

    <p>
        <label for="dsgvo_yt_remember_color"><?php esc_html_e('Remember Selection Text Color:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_remember_color"
            type="text"
            name="dsgvo_yt_remember_color"
            value="<?php printf('%s', esc_attr($remember_color)); ?>"
            class="wp-color-picker-field"
            placeholder="<?php esc_attr_e('Theme default', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?>" /><br>
        <span class="description"><?php esc_html_e('Theme default; leave empty to keep existing appearance.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></span>
    </p>

    <?php
}

/** Keep unchanged legacy values byte-for-byte; reject invalid edits without silent resets. */
function dsgvo_yt_save_size($post_id, $field, $value, $font = false)
{
    $key = '_dsgvo_yt_' . $field;
    $previous = dsgvo_yt_meta($post_id, $key);
    if (null === $value || $value === $previous || $value === str_replace(array("\r", "\n"), '', $previous)) {
        return;
    }
    if ('' === trim($value)) {
        update_post_meta($post_id, $key, '');
        return;
    }
    $clean = $font ? dsgvo_yt_sanitize_font_size($value, null) : dsgvo_yt_sanitize_dimension($value, null);
    if (null === $clean) {
        set_transient('dsgvo_yt_size_error_' . get_current_user_id(), 1, MINUTE_IN_SECONDS);
        return;
    }
    update_post_meta($post_id, $key, wp_slash($clean));
}

// Save only this post type and only authorized, intentional editor submissions.
add_action('save_post_dsgvo_video', 'dsgvo_yt_save_meta');
function dsgvo_yt_save_meta($post_id)
{
    if (!isset($_POST['dsgvo_yt_nonce']) || !is_string($_POST['dsgvo_yt_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['dsgvo_yt_nonce'])), 'dsgvo_yt_save')) {
        return;
    }
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || 'dsgvo_video' !== get_post_type($post_id) || !current_user_can('edit_post', $post_id)) {
        return;
    }
    // Missing or non-scalar fields are ignored instead of erasing existing values.
    $input = static function ($key) {
        // The enclosing handler verified the nonce and edit capability; each returned value is sanitized for its specific field below.
        // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        return isset($_POST[$key]) && is_string($_POST[$key]) ? wp_unslash($_POST[$key]) : null;
    };
    $iframe_input = $input('dsgvo_yt_iframe');
    $iframe_previous = dsgvo_yt_meta($post_id, '_dsgvo_yt_iframe');
    // Browsers submit textarea newlines as CRLF; this does not constitute an author edit.
    $iframe_changed = null !== $iframe_input && str_replace(array("\r\n", "\r"), "\n", $iframe_input) !== str_replace(array("\r\n", "\r"), "\n", $iframe_previous);
    if ($iframe_changed) {
        $iframe = dsgvo_yt_sanitize_iframe($iframe_input);
        if ('' === trim($iframe_input) || '' !== $iframe) {
            update_post_meta($post_id, '_dsgvo_yt_iframe', wp_slash($iframe));
        } else {
            set_transient('dsgvo_yt_iframe_error_' . get_current_user_id(), 1, MINUTE_IN_SECONDS);
        }
    }
    $text_fields = array('button_text', 'privacy_text', 'privacy_link_text', 'remember_text');
    foreach ($text_fields as $field) {
        $value = $input('dsgvo_yt_' . $field);
        if (null !== $value) {
            update_post_meta($post_id, '_dsgvo_yt_' . $field, wp_slash(sanitize_text_field(substr($value, 0, 2000))));
        }
    }
    $message = $input('dsgvo_yt_message_text');
    if (null !== $message) {
        update_post_meta($post_id, '_dsgvo_yt_message_text', wp_slash(sanitize_textarea_field(substr($message, 0, 8000))));
    }
    foreach (array('button_font_size', 'privacy_font_size', 'privacy_link_font_size', 'message_font_size', 'remember_font_size') as $field) {
        $value = $input('dsgvo_yt_' . $field);
        if (null !== $value) {
            dsgvo_yt_save_size($post_id, $field, $value, true);
        }
    }
    foreach (array('button_shape' => array('rounded', 'square'), 'template' => array('light', 'dark', 'custom'), 'remember_style' => array('classic', 'modern')) as $field => $allowed) {
        $value = $input('dsgvo_yt_' . $field);
        if (null !== $value && in_array($value, $allowed, true)) {
            update_post_meta($post_id, '_dsgvo_yt_' . $field, $value);
        }
    }
    foreach (array('overlay_bg', 'button_bg', 'button_color', 'privacy_color', 'remember_color') as $field) {
        $value = $input('dsgvo_yt_' . $field);
        if (null !== $value) {
            $color = sanitize_hex_color($value);
            if ($color) {
                update_post_meta($post_id, '_dsgvo_yt_' . $field, $color);
            } elseif ('' === $value) {
                delete_post_meta($post_id, '_dsgvo_yt_' . $field);
            }
        }
    }
    foreach (array('width', 'height') as $field) {
        $value = $input('dsgvo_yt_' . $field);
        if (null !== $value) {
            dsgvo_yt_save_size($post_id, $field, $value);
        }
    }
    foreach (array('privacy_enabled', 'load_all_enabled', 'remember_enabled') as $field) {
        $value = $input('dsgvo_yt_' . $field);
        if (null !== $value && in_array($value, array('0', '1'), true)) {
            update_post_meta($post_id, '_dsgvo_yt_' . $field, (int) $value);
        }
    }
    $privacy_link = $input('dsgvo_yt_privacy_link');
    if (null !== $privacy_link) {
        update_post_meta($post_id, '_dsgvo_yt_privacy_link', esc_url_raw($privacy_link, array('https', 'http')));
    }
}

add_action('admin_notices', function () {
    $screen = get_current_screen();
    $size_key = 'dsgvo_yt_size_error_' . get_current_user_id();
    if ($screen && 'dsgvo_video' === $screen->post_type && get_transient($size_key)) {
        delete_transient($size_key);
        echo '<div class="notice notice-error"><p>' . esc_html__('One or more size values were not saved because they contain unsupported CSS. The previous values have been kept.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos') . '</p></div>';
    }
    $key = 'dsgvo_yt_iframe_error_' . get_current_user_id();
    if ($screen && 'dsgvo_video' === $screen->post_type && get_transient($key)) {
        delete_transient($key);
        echo '<div class="notice notice-error"><p>' . esc_html__('The iframe was not saved because it is not a supported HTTPS YouTube embed. The previous embed has been kept.', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos') . '</p></div>';
    }
});
