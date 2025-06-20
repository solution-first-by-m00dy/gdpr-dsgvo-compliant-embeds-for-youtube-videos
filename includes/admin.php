<?php

/**
 * @package         GDPR_YouTube_Videos_Embed_SF
 * @license         GPLv2 or later
 * @license URI     https://www.gnu.org/licenses/gpl-2.0.html
 */

if (! defined('ABSPATH')) exit; // Exit if accessed directly



add_action('admin_enqueue_scripts', function () {
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
    $iframe     = get_post_meta($post->ID, '_dsgvo_yt_iframe', true);
    $template   = get_post_meta($post->ID, '_dsgvo_yt_template',  true) ?: 'light';
    $btn_text   = get_post_meta($post->ID, '_dsgvo_yt_button_text', true) ?: __('Load YouTube Video', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');
    $btn_shape = get_post_meta($post->ID, '_dsgvo_yt_button_shape', true);
    if (! in_array($btn_shape, ['rounded', 'square'], true)) {
        $btn_shape = 'rounded'; // Default
    }

    $overlay_bg = get_post_meta($post->ID, '_dsgvo_yt_overlay_bg',  true) ?: '#ffffff';
    $button_bg  = get_post_meta($post->ID, '_dsgvo_yt_button_bg',   true) ?: '#0073aa';
    $btn_color  = get_post_meta($post->ID, '_dsgvo_yt_button_color',   true) ?: '#ffffff';
    $privacy_color = get_post_meta($post->ID, '_dsgvo_yt_privacy_color', true) ?: '#666666';
    $privacy_enabled = get_post_meta($post->ID, '_dsgvo_yt_privacy_enabled', true) ?: 0;
    $privacy_link = get_post_meta($post->ID, '_dsgvo_yt_privacy_link', true) ?: '';

    $privacy_text = get_post_meta($post->ID, '_dsgvo_yt_privacy_text', true) ?: '';
    $privacy_link_text = get_post_meta($post->ID, '_dsgvo_yt_privacy_link_text', true) ?: '';

    $width = get_post_meta($post->ID, '_dsgvo_yt_width', true) ?: '100%';
    $height = get_post_meta($post->ID, '_dsgvo_yt_height', true) ?: '100%';


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

    <br>
    <hr>
    <br>

    <p>
        <label for="dsgvo_yt_iframe"><?php esc_html_e('iframe Code:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <textarea id="dsgvo_yt_iframe" name="dsgvo_yt_iframe" style="width:100%;height:100px;"><?php printf('%s', esc_textarea($iframe)); ?></textarea>

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
        <label for="dsgvo_yt_privacy_link_text"><?php esc_html_e('Privacy Policy URL Text:', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'); ?></label><br>
        <input
            id="dsgvo_yt_privacy_link_text"
            name="dsgvo_yt_privacy_link_text"
            type="text"
            value="<?php printf('%s', esc_attr($privacy_link_text)); ?>"
            style="width:100%;">
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

    <?php
}

// Save meta box data
add_action('save_post', 'dsgvo_yt_save_meta');
function dsgvo_yt_save_meta($post_id)
{
    if (! isset($_POST['dsgvo_yt_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['dsgvo_yt_nonce'])), 'dsgvo_yt_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (get_post_type($post_id) !== 'dsgvo_video') {
        return;
    }

    // Iframe
    if (isset($_POST['dsgvo_yt_iframe'])) {
        $iframe = wp_kses(wp_unslash($_POST['dsgvo_yt_iframe']), [
            'iframe' => [
                'src'            => [],
                'width'          => [],
                'height'         => [],
                'style'          => [],
                'allowfullscreen' => [],
                'loading'        => [],
                'referrerpolicy' => [],
            ]
        ]);
        update_post_meta($post_id, '_dsgvo_yt_iframe', $iframe);
    }

    // Button Text
    $btn_text = isset($_POST['dsgvo_yt_button_text'])
        ? sanitize_text_field(wp_unslash($_POST['dsgvo_yt_button_text']))
        : '';
    update_post_meta($post_id, '_dsgvo_yt_button_text', $btn_text);

    // Button Shape
    if (isset($_POST['dsgvo_yt_button_shape'])) {
        $btn_shape = sanitize_text_field(wp_unslash($_POST['dsgvo_yt_button_shape']));
        // only permitted values (rounded & square)
        if (in_array($btn_shape, ['rounded', 'square'], true)) {
            update_post_meta($post_id, '_dsgvo_yt_button_shape', $btn_shape);
        }
    }

    // Template (light|dark|custom)
    $tmpl = isset($_POST['dsgvo_yt_template']) && in_array($_POST['dsgvo_yt_template'], ['light', 'dark', 'custom'], true)
        ? sanitize_text_field(wp_unslash($_POST['dsgvo_yt_template']))
        : 'light';
    update_post_meta($post_id, '_dsgvo_yt_template', $tmpl);


    // Custom‑Colors if template custom
    $custom_fields = [
        'dsgvo_yt_overlay_bg'  => '_dsgvo_yt_overlay_bg',
        'dsgvo_yt_button_bg'   => '_dsgvo_yt_button_bg',
        'dsgvo_yt_button_color' => '_dsgvo_yt_button_color',
        'dsgvo_yt_privacy_color' => '_dsgvo_yt_privacy_color',
    ];

    if ($tmpl === 'custom') {
        foreach ($custom_fields as $field_name => $meta_key) {
            if (isset($_POST[$field_name])) {
                $color = sanitize_hex_color(wp_unslash($_POST[$field_name]));
                update_post_meta($post_id, $meta_key, $color);
            }
        }
    } else {
        // if light/dark remove custom‑Metas
        foreach ($custom_fields as $meta_key) {
            delete_post_meta($post_id, $meta_key);
        }
    }

    // Size: width & height
    if (isset($_POST['dsgvo_yt_width'])) {
        update_post_meta($post_id, '_dsgvo_yt_width', sanitize_text_field(wp_unslash($_POST['dsgvo_yt_width'])));
    }
    if (isset($_POST['dsgvo_yt_height'])) {
        update_post_meta($post_id, '_dsgvo_yt_height', sanitize_text_field(wp_unslash($_POST['dsgvo_yt_height'])));
    }

    // Privacy-Fields
    $enabled = isset($_POST['dsgvo_yt_privacy_enabled']) ? 1 : 0;
    update_post_meta($post_id, '_dsgvo_yt_privacy_enabled', $enabled);
    if (isset($_POST['dsgvo_yt_privacy_link'])) {
        update_post_meta(
            $post_id,
            '_dsgvo_yt_privacy_link',
            esc_url_raw(wp_unslash($_POST['dsgvo_yt_privacy_link']))
        );
    }

    if (isset($_POST['dsgvo_yt_privacy_text'])) {
        update_post_meta($post_id, '_dsgvo_yt_privacy_text', sanitize_text_field(wp_unslash($_POST['dsgvo_yt_privacy_text'])));
    }

    if (isset($_POST['dsgvo_yt_privacy_link_text'])) {
        update_post_meta($post_id, '_dsgvo_yt_privacy_link_text', sanitize_text_field(wp_unslash($_POST['dsgvo_yt_privacy_link_text'])));
    }
}
