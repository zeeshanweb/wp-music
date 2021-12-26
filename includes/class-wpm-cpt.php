<?php
/**
 * Handles all registration of music post type.
 *
 * @package     wpm
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPM_CPT' ) ) {
	/**
	 * The class that handles the custom post type.
	 */
	class WPM_CPT {
		/**
		 * Holds the custom post type name.
		 *
		 * @var object
		 */
		protected $post_name = 'Musics';
		/**
		 * Holds the custom post type slug.
		 *
		 * @var object
		 */
		protected $post_type = 'music';
		/**
		 * Set up post type and taxonomy to be registered
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_post_type' ), 0 );
			add_action( 'init', array( $this, 'register_taxonomies' ), 5 );
		}
		/**
		 * Get the custom post type registration options.
		 *
		 * @return array
		 */
		public function get_post_type_options() {
			$post_options = array(
				'label'             => $this->post_name,
				'labels'            => array(
					'name'                     => $this->post_name,
					'singular_name'            => $this->post_name,
					'add_new'                  => esc_html__( 'Add New', 'wp-music' ),
					'all_items'                => $this->post_name,
					// translators: placeholder: Post Name.
					'add_new_item'             => sprintf( esc_html_x( 'Add New %s', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'edit_item'                => sprintf( esc_html_x( 'Edit %s', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'new_item'                 => sprintf( esc_html_x( 'New %s', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'view_item'                => sprintf( esc_html_x( 'View %s', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'search_items'             => sprintf( esc_html_x( 'Search %s', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'not_found'                => sprintf( esc_html_x( 'No %s found', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'not_found_in_trash'       => sprintf( esc_html_x( 'No %s found in Trash', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'parent_item_colon'        => sprintf( esc_html_x( 'Parent %s', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					'menu_name'                => $this->post_name,
					// translators: placeholder: Post Name.
					'item_published'           => sprintf( esc_html_x( '%s Published', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'item_published_privately' => sprintf( esc_html_x( '%s Published Privately', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'item_reverted_to_draft'   => sprintf( esc_html_x( '%s Reverted to Draft', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'item_scheduled'           => sprintf( esc_html_x( '%s Scheduled', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
					// translators: placeholder: Post Name.
					'item_updated'             => sprintf( esc_html_x( '%s Updated', 'placeholder: Post Name', 'wp-music' ), $this->post_name ),
				),
				'public'            => true,
				'hierarchical'      => false,
				'show_ui'           => true,
				'has_archive'       => false,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'supports'          => array(
					'title',
					'editor',
					'thumbnail',
				),
			);
			return $post_options;
		}
		/**
		 * Register the directory post type.
		 *
		 * @return void
		 */
		public function register_post_type() {
			$post_options = $this->get_post_type_options();
			/**
			 * Filters the custom post type registration options.
			 *
			 * @since 1.0.0
			 *
			 * @param array  $post_options An array of post options.
			 * @param string $post_type    Post type slug.
			 */
			$post_options = apply_filters( 'wpm_cpt_options', $post_options, $this->post_type );
			register_post_type( $this->post_type, $post_options );
		}
		/**
		 * Register custom taxonomies.
		 */
		public function register_taxonomies() {
			if ( ! is_blog_installed() ) {
				return;
			}
			if ( taxonomy_exists( 'genre' ) ) {
				return;
			}
			// Add new Genre taxonomy, hierarchical.
			$labels = array(
				'name'              => _x( 'Genres', 'taxonomy general name', 'wp-music' ),
				'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'wp-music' ),
				'search_items'      => __( 'Search Genres', 'wp-music' ),
				'all_items'         => __( 'All Genres', 'wp-music' ),
				'parent_item'       => __( 'Parent Genre', 'wp-music' ),
				'parent_item_colon' => __( 'Parent Genre:', 'wp-music' ),
				'edit_item'         => __( 'Edit Genre', 'wp-music' ),
				'update_item'       => __( 'Update Genre', 'wp-music' ),
				'add_new_item'      => __( 'Add New Genre', 'wp-music' ),
				'new_item_name'     => __( 'New Genre Name', 'wp-music' ),
				'menu_name'         => __( 'Genre', 'wp-music' ),
			);
			$args   = apply_filters(
				'wpm_taxonomy_args_music_genre',
				array(
					'hierarchical'      => true,
					'labels'            => $labels,
					'show_ui'           => true,
					'show_in_rest'      => true,
					'show_admin_column' => true,
					'query_var'         => true,
					'rewrite'           => array( 'slug' => 'genre' ),
				)
			);
			register_taxonomy( 'genre', array( $this->post_type ), $args );
			unset( $args );
			unset( $labels );
			// Add new music_tag taxonomy, NOT hierarchical.
			$labels = array(
				'name'                       => _x( 'Music Tag', 'taxonomy general name', 'wp-music' ),
				'singular_name'              => _x( 'Music Tag', 'taxonomy singular name', 'wp-music' ),
				'search_items'               => __( 'Search Music Tag', 'wp-music' ),
				'popular_items'              => __( 'Popular Music Tag', 'wp-music' ),
				'all_items'                  => __( 'All Music Tag', 'wp-music' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit Music Tag', 'wp-music' ),
				'update_item'                => __( 'Update Music Tag', 'wp-music' ),
				'add_new_item'               => __( 'Add New Music Tag', 'wp-music' ),
				'new_item_name'              => __( 'New Music Tag Name', 'wp-music' ),
				'separate_items_with_commas' => __( 'Separate Music Tag with commas', 'wp-music' ),
				'add_or_remove_items'        => __( 'Add or remove Music Tag', 'wp-music' ),
				'choose_from_most_used'      => __( 'Choose from the most used Music Tag', 'wp-music' ),
				'not_found'                  => __( 'No Music Tag found.', 'wp-music' ),
				'menu_name'                  => __( 'Music Tag', 'wp-music' ),
			);
			$args   = apply_filters(
				'wpm_taxonomy_args_music_tag',
				array(
					'hierarchical'          => false,
					'labels'                => $labels,
					'show_ui'               => true,
					'show_in_rest'          => true,
					'show_admin_column'     => true,
					'update_count_callback' => '_update_post_term_count',
					'query_var'             => true,
					'rewrite'               => array( 'slug' => 'music_tag' ),
				)
			);
			register_taxonomy( 'music_tag', $this->post_type, $args );
		}
	}
	new WPM_CPT();
}
