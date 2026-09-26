<?php
/**
 * @package         GDPR_YouTube_Videos_Embed_SF
 * @license         GPLv2 or later
 * @license URI     https://www.gnu.org/licenses/gpl-2.0.html
 */


if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete all DSGVO YouTube Video posts and their metadata
$dsgvo_yt_videos = get_posts( array(
    'post_type'   => 'dsgvo_video',
    'numberposts' => -1,
    'post_status' => 'any',
) );
foreach ( $dsgvo_yt_videos as $dsgvo_yt_video ) {
    wp_delete_post( $dsgvo_yt_video->ID, true );
}
