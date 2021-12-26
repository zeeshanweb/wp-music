<?php
/**
 * Handles Musics shortcode.
 *
 * @package     wpm
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WPM_Shortcode_Musics' ) ) {
	/**
	 * Musics shortcode class.
	 */
	class WPM_Shortcode_Musics {
		/**
		 * Initialize shortcode.
		 */
		public function __construct() {
			add_shortcode( 'music', array( $this, 'render' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wpm_music_style' ) );
		}
		/**
		 * Enqueue scripts and styles.
		 */
		public function wpm_music_style() {
			wp_enqueue_style( 'cmxform-styles', WPM_PLUGIN_URL . '/assets/css/music-style.css', array(), wp_rand(), false );
		}
		/**
		 * List related products.
		 *
		 * @param array $atts Attributes.
		 * @return string
		 */
		public function render( $atts = array() ) {
			$wpm_setting_data = get_option( 'wpm_setting_data' );
			$limit            = isset( $wpm_setting_data['musics_per_page'] ) ? $wpm_setting_data['musics_per_page'] : 10;
			// @codingStandardsIgnoreStart
			$atts = shortcode_atts( array(
				'limit'    => ! empty( $limit ) ? $limit : 10,
				'year'  => '',
				'genre'  => '',
			), $atts, 'render' );
			// @codingStandardsIgnoreEnd
			$args = array(
				'post_type'   => 'music',
				'post_status' => 'publish',
			);
			if ( isset( $atts['genre'] ) && ! empty( $atts['genre'] ) ) {
				$args['tax_query'] = array( // phpcs:ignore
					array(
						'taxonomy' => 'genre',
						'field'    => 'slug',
						'terms'    => $atts['genre'],
					),
				);
			}
			if ( isset( $atts['year'] ) && ! empty( $atts['year'] ) ) {
				$args['meta_query'] = array( // phpcs:ignore
					array(
						'key'     => '_year_of_recording',
						'value'   => $atts['year'],
						'compare' => '=',
					),
				);
			}
			$args['posts_per_page'] = absint( $atts['limit'] ); // phpcs:ignore
			$args                   = apply_filters( 'wpm_filter_query', $args );
			$query                  = new WP_Query( $args );
			include WPM_PLUGIN_PATH . '/includes/templates/template-music.php';
			/* Restore original Post Data */
			wp_reset_postdata();
			$output = trim( $output ) . "\n";
			return $output;
		}
	}
	new WPM_Shortcode_Musics();
}
