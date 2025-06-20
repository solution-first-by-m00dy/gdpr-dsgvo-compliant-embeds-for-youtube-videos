<?php

/**
 * Plugin Name:     GDPR-DSGVO compliant Embeds for YouTube Videos
 * Plugin URI:      https://solutionfirst.m00dy.org/wp-plugin/
 * Description:     Enables GDPR-compliant embedding of multiple YouTube Video iframes with user consent, selectable light/dark design, and optional privacy policy notice.
 * Version:         1.0.0
 * Author:          Solution First by M00dy
 * Author URI:      https://profiles.wordpress.org/solutionfirst/
 * Text Domain:     gdpr-dsgvo-compliant-embeds-for-youtube-videos
 * Domain Path:     /languages
 *
 * @package         GDPR_YouTube_Videos_Embed_SF
 *
 * License:         GPLv2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 */


if (! defined('ABSPATH')) exit; // Exit if accessed directly

function dsgvo_yt_plugin_action_links($links)
{
    $settings_label = __('Settings', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');
    $info_label      = __('More information', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos');

    $new_links = array(
        // Link 1: Settings
        '<a href="' . esc_url(admin_url('edit.php?post_type=dsgvo_video')) . '">'
            . esc_html($settings_label) .
            '</a>',
        // Link 2: More information
        '<a href="' . esc_url('https://solutionfirst.m00dy.org/wp-plugin/') . '" target="_blank" class="dsgvo-yt-info-link" style="color:rgb(198, 44, 44); font-weight: bold;">'
            . esc_html($info_label) .
            '</a>',
    );

    return array_merge($links, $new_links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'dsgvo_yt_plugin_action_links');


// Constants
define('DSGVO_YT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DSGVO_YT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DSGVO_YT_VERSION', '1.0.0');

// Activation & Deactivation
register_activation_hook(__FILE__, 'dsgvo_yt_activate');
function dsgvo_yt_activate()
{
    dsgvo_yt_register_post_type();
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'dsgvo_yt_deactivate');
function dsgvo_yt_deactivate()
{
    flush_rewrite_rules();
}

add_action('wp_enqueue_scripts', 'dsgvo_yt_enqueue_assets');
function dsgvo_yt_enqueue_assets()
{
    // CSS: Load CSS
    wp_enqueue_style(
        'dsgvo-yt-style',
        DSGVO_YT_PLUGIN_URL . 'assets/css/dsgvo-yt.css',
        array(),
        DSGVO_YT_VERSION
    );

    // JS: Load JS
    wp_enqueue_script(
        'dsgvo-yt-script',
        DSGVO_YT_PLUGIN_URL . 'assets/js/dsgvo-yt.js',
        ['jquery'],
        DSGVO_YT_VERSION,
        true
    );

    wp_localize_script('dsgvo-yt-script', 'dsgvoYt', array(
        'buttonText' => __('Load YouTube Video', 'gdpr-dsgvo-compliant-embeds-for-youtube-videos'),
    ));
}

// Includes
require_once DSGVO_YT_PLUGIN_DIR . 'includes/admin.php';
require_once DSGVO_YT_PLUGIN_DIR . 'includes/frontend.php';
