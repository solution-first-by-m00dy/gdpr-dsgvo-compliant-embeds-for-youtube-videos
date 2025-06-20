<?php

/**
 * @package         GDPR_YouTube_Videos_Embed_SF
 * @license         GPLv2 or later
 * @license URI     https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_shortcode('dsgvo_video', function ($atts) {
    $atts = shortcode_atts(
        array('id' => '', 'class' => ''),
        $atts,
        'dsgvo_video'
    );
    $id = intval($atts['id']);


    $btn_text      = get_post_meta($id, '_dsgvo_yt_button_text', true) ?: __('Load Video', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');
    $btn_shape      = get_post_meta($id, '_dsgvo_yt_button_shape', true);
    $overlay_bg    = get_post_meta($id, '_dsgvo_yt_overlay_bg',  true);
    $button_bg     = get_post_meta($id, '_dsgvo_yt_button_bg',   true);
    $btn_color     = get_post_meta($id, '_dsgvo_yt_button_color', true);
    $privacy_color = get_post_meta($id, '_dsgvo_yt_privacy_color', true);

    $iframe          = get_post_meta($id, '_dsgvo_yt_iframe', true);
    $template        = get_post_meta($id, '_dsgvo_yt_template', true);
    $privacy_enabled = get_post_meta($id, '_dsgvo_yt_privacy_enabled', true);
    $privacy_link    = get_post_meta($id, '_dsgvo_yt_privacy_link', true);

    
    $width_input  = get_post_meta($id, '_dsgvo_yt_width', true);
    $height_input = get_post_meta($id, '_dsgvo_yt_height', true);

    
    $width_val  = $width_input  ? trim($width_input)  : '100%';
    $height_val = $height_input ? trim($height_input) : '100%';

    $privacy_text = get_post_meta($id, '_dsgvo_yt_privacy_text', true) ?: __('Please see our', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');
    $privacy_link_text = get_post_meta($id, '_dsgvo_yt_privacy_link_text', true) ?: __('Privacy Policy', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');

    // Append 'px' if numeric
    if (preg_match('/^\d+$/', $width_val)) {
        $width_val .= 'px';
    }
    if (preg_match('/^\d+$/', $height_val)) {
        $height_val .= 'px';
    }

    if (! $iframe) {
        return '';
    }

    // Build inline style
    if (substr($height_val, -1) === '%') {
        // Height is percentage - use padding-bottom for aspect ratio
        $style_attr = sprintf(
            'width:%s;position:relative;height:0;padding-bottom:%s;overflow:hidden;',
            esc_attr($width_val),
            esc_attr($height_val)
        );
    } else {
        // Fixed height in px or other unit
        $style_attr = sprintf(
            'width:%s;height:%s;position:relative;overflow:hidden;',
            esc_attr($width_val),
            esc_attr($height_val)
        );
    }

    // Classes & inline styles
    $class = 'dsgvo-yt-' . (in_array($template, ['light', 'dark']) ? $template : 'custom');
    $overlay_style = $template === 'custom'
        ? 'background-color:' . esc_attr($overlay_bg) . ';'
        : '';
    $btn_style = $template === 'custom'
        ? 'background-color:' . esc_attr($button_bg) . ';color:' . esc_attr($btn_color) . ';'
        : '';
    $privacy_style = $template === 'custom'
        ? 'color:' . esc_attr($privacy_color) . ';'
        : '';


    $btn_shape_style = $btn_shape === 'rounded'
        ? 'border-radius:15px;'
        : 'border-radius:0;';

    $b64 = base64_encode($iframe);

    // Build output
    $html  = '<div class="dsgvo-yt-container ' . esc_attr($class) . '" style="' . $style_attr . '">';
    $html .= '<div class="dsgvo-yt-overlay ' . esc_attr($class) . '" style="' . $overlay_style . '" data-iframe="' . esc_attr($b64) . '">';
    $html .= '<button class="dsgvo-yt-load-btn ' . esc_attr($class) . '" style="' . $btn_shape_style . $btn_style . '">'
        . esc_html($btn_text) .
        '</button>';
    if ($privacy_enabled && $privacy_link) {
        $html .= '<div class="dsgvo-yt-privacy-info ' . esc_attr($class) . '" style="' . $privacy_style . '">'
            . esc_html( $privacy_text )
            . ' <a href="' . esc_url($privacy_link) . '" target="_blank" style="' . $privacy_style . '">'
            . esc_html( $privacy_link_text )
            . '</a></div>';
    }
    $html .= '</div></div>';

    return $html;
});
