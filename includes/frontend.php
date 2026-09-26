<?php
/**
 * Consent placeholder for published YouTube configurations.
 *
 * @package GDPR_YouTube_Videos_Embed_SF
 * @license GPLv2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('dsgvo_video', 'dsgvo_yt_shortcode');
function dsgvo_yt_shortcode($atts)
{
    $atts = shortcode_atts(array('id' => '', 'class' => '', 'show_reset' => 'false'), $atts, 'dsgvo_video');
    if (!is_scalar($atts['id']) || !preg_match('/^[1-9][0-9]*$/D', (string) $atts['id'])) {
        return '';
    }
    $id = (int) $atts['id'];
    $video = get_post($id);
    if (!$video || 'dsgvo_video' !== $video->post_type || 'publish' !== $video->post_status || !empty($video->post_password)) {
        return '';
    }
    // Validate existing metadata on every render, including entries saved by older versions.
    $iframe = dsgvo_yt_sanitize_iframe(dsgvo_yt_meta($id, '_dsgvo_yt_iframe'));
    if ('' === $iframe) {
        return '';
    }
    // Preserve the original fallback semantics, including the literal string "0".
    $btn_text = dsgvo_yt_meta($id, '_dsgvo_yt_button_text') ?: __('Load Video', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');
    $btn_shape = dsgvo_yt_meta($id, '_dsgvo_yt_button_shape');
    $template = dsgvo_yt_meta($id, '_dsgvo_yt_template');
    $custom_colors = 'custom' === $template;
    $template = in_array($template, array('light', 'dark'), true) ? $template : 'custom';
    $color = static function ($key, $property) use ($id, $custom_colors) {
        $value = sanitize_hex_color(dsgvo_yt_meta($id, '_dsgvo_yt_' . $key));
        // Empty legacy colors inherit the original stylesheet, including its opacity.
        return $custom_colors && $value ? $property . ':' . $value . ';' : '';
    };
    $font = static function ($key) use ($id) {
        $value = dsgvo_yt_sanitize_font_size(dsgvo_yt_meta($id, '_dsgvo_yt_' . $key), '');
        return '' !== $value ? 'font-size:' . $value . ';' : '';
    };
    $privacy_enabled = '1' === dsgvo_yt_meta($id, '_dsgvo_yt_privacy_enabled');
    $privacy_link = esc_url_raw(dsgvo_yt_meta($id, '_dsgvo_yt_privacy_link') ?: '', array('https', 'http'));
    $load_all_enabled = '1' === dsgvo_yt_meta($id, '_dsgvo_yt_load_all_enabled') ? 1 : 0;
    $remember_enabled = '1' === dsgvo_yt_meta($id, '_dsgvo_yt_remember_enabled') ? 1 : 0;
    $privacy_text = dsgvo_yt_meta($id, '_dsgvo_yt_privacy_text') ?: __('Please see our', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');
    $privacy_link_text = dsgvo_yt_meta($id, '_dsgvo_yt_privacy_link_text') ?: __('Privacy Policy', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');
    $message_text = dsgvo_yt_meta($id, '_dsgvo_yt_message_text');
    $remember_text = dsgvo_yt_meta($id, '_dsgvo_yt_remember_text', __('Remember selection', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'));
    // Only raw "0" had this legacy fallback; " 0 " and "0px" remain zero-sized.
    $width = dsgvo_yt_sanitize_dimension(dsgvo_yt_meta($id, '_dsgvo_yt_width') ?: '100%');
    $height = dsgvo_yt_sanitize_dimension(dsgvo_yt_meta($id, '_dsgvo_yt_height') ?: '100%');
    $style_attr = 'width:' . $width . ';position:relative;overflow:hidden;';
    $style_attr .= '%' === substr($height, -1) ? 'height:0;padding-bottom:' . $height . ';' : 'height:' . $height . ';';
    $class = 'dsgvo-yt-' . $template;
    // Older releases accepted but never applied "class". Keep that behavior:
    // activating previously ignored theme classes can override saved dimensions.
    $show_reset = is_scalar($atts['show_reset']) && in_array(strtolower((string) $atts['show_reset']), array('true', '1'), true);
    $overlay_style = $color('overlay_bg', 'background-color');
    $btn_style = $color('button_bg', 'background-color') . $color('button_color', 'color');
    $btn_style .= $font('button_font_size') . 'border-radius:' . ('rounded' === $btn_shape ? '15px' : '0') . ';';
    $privacy_style = $color('privacy_color', 'color');
    $privacy_text_style = $privacy_style . $font('privacy_font_size');
    $privacy_link_style = $privacy_style . $font('privacy_link_font_size');
    $message_style = $privacy_style . $font('message_font_size');
    $remember_color = sanitize_hex_color(dsgvo_yt_meta($id, '_dsgvo_yt_remember_color'));
    $remember_style = ($remember_color ? 'color:' . $remember_color . ';' : $privacy_style) . $font('remember_font_size');
    $b64 = base64_encode($iframe);
    $html = '<div class="dsgvo-yt-container ' . esc_attr($class) . '" style="' . esc_attr($style_attr) . '">';
    $html .= '<div class="dsgvo-yt-overlay ' . esc_attr($class) . '" style="' . esc_attr($overlay_style) . '" data-iframe="' . esc_attr($b64) . '" data-load-all="' . esc_attr((string) $load_all_enabled) . '" data-remember-enabled="' . esc_attr((string) $remember_enabled) . '" data-show-reset="' . ($show_reset ? '1' : '0') . '" data-video-id="' . esc_attr((string) $id) . '">';
    $html .= '<button type="button" class="dsgvo-yt-load-btn ' . esc_attr($class) . '" style="' . esc_attr($btn_style) . '">' . esc_html($btn_text) . '</button>';
    if ('' !== $message_text) {
        $html .= '<div class="dsgvo-yt-message ' . esc_attr($class) . '" style="' . esc_attr($message_style) . '">' . esc_html($message_text) . '</div>';
    }
    if ($privacy_enabled && '' !== $privacy_link) {
        $html .= '<div class="dsgvo-yt-privacy-info ' . esc_attr($class) . '" style="' . esc_attr($privacy_text_style) . '">'
            . esc_html($privacy_text)
            . ' <a href="' . esc_url($privacy_link, array('https', 'http')) . '" target="_blank" rel="noopener noreferrer" style="' . esc_attr($privacy_link_style) . '">'
            . esc_html($privacy_link_text) . '</a></div>';
    }
    if ($remember_enabled) {
        $remember_design = 'modern' === dsgvo_yt_meta($id, '_dsgvo_yt_remember_style') ? ' dsgvo-yt-remember-choice--modern' : '';
        $html .= '<label class="dsgvo-yt-remember-choice ' . esc_attr($class) . $remember_design . '" style="' . esc_attr($remember_style) . '">'
            . '<input type="checkbox" class="dsgvo-yt-remember-checkbox" value="1"> <span>' . esc_html($remember_text) . '</span></label>';
    }
    return $html . '</div></div>';
}

/** A separate withdrawal control, usable in page text or a privacy-policy page. */
add_shortcode('dsgvo_video_reset', 'dsgvo_yt_reset_shortcode');
function dsgvo_yt_reset_shortcode($atts)
{
    $default = __('Reset YouTube choice', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');
    $atts = shortcode_atts(array('text' => $default), $atts, 'dsgvo_video_reset');
    $text = is_string($atts['text']) && '' !== trim($atts['text']) ? $atts['text'] : $default;
    return '<div class="dsgvo-yt-reset"><button type="button" class="dsgvo-yt-reset-btn">' . esc_html($text)
        . '</button><span class="dsgvo-yt-reset-status" role="status"></span></div>';
}
