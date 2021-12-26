<?php
/**
 * WP Music's built-in thumbnails template
 *
 * @package wpm
 * @license https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

$thumbnails_default = WPM_PLUGIN_URL . '/assets/images/defaul_image.png';
$output             = '<!-- WP Music -->' . "\n";
$output            .= '<h3>' . __( 'WP Music Posts', 'wp-music' ) . '</h3>' . "\n";
$no_results         = '<p>' . __( 'No WP music posts found.', 'wp-music' ) . '</p>';
if ( $query->have_posts() ) {
	$output .= '<div class="music-thumbnails-horizontal">' . "\n";
	while ( $query->have_posts() ) {
		$query->the_post();

		$output .= "<a class='music-thumbnail' rel='norewrite' href='" . get_permalink() . "' title='" . the_title_attribute( 'echo=0' ) . "'>\n";

		$post_thumbnail_html = '';
		if ( has_post_thumbnail() ) {
			$output .= get_the_post_thumbnail( null, 'thumbnail' );
		} else {
			$output .= '<span class="music-thumbnail-default"><img src="' . esc_url( $thumbnails_default ) . '" alt="Default Thumbnail" data-pin-nopin="true" /></span>';
		}
		$output .= '<span class="music-thumbnail-title">' . get_the_title() . '</span>';
		$output .= '</a>' . "\n";

	}
	$output .= "</div>\n";
} else {
	$output .= $no_results;
}
