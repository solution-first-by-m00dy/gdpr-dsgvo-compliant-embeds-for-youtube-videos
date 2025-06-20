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
$videos = get_posts( array(
    'post_type'   => 'dsgvo_video',
    'numberposts' => -1,
    'post_status' => 'any',
) );
foreach ( $videos as $video ) {
    wp_delete_post( $video->ID, true );
}