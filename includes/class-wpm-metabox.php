<?php
/**
 * Handles metaboxes.
 *
 * @package     wpm
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPM_Metabox' ) ) {
	/**
	 * The class that handles the metaboxes for music post type.
	 */
	class WPM_Metabox {
		/**
		 * Holds the custom post type name.
		 *
		 * @var object
		 */
		protected $post_name = 'Music';
		/**
		 * Holds the custom post type slug.
		 *
		 * @var object
		 */
		protected $post_type = 'music';
		/**
		 * Hook into the appropriate actions when the class is constructed.
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save' ) );
		}
		/**
		 * Adds the meta box container.
		 *
		 * @param array $post_type Post type.
		 */
		public function add_meta_box( $post_type ) {
			// Limit meta box to certain post types.
			$post_types = array( 'music' );
			if ( in_array( $post_type, $post_types, true ) ) {
				add_meta_box(
					'music_metabox',
					__( 'Music', 'wp-music' ),
					array( $this, 'render_meta_box_content' ),
					$post_type,
					'advanced',
					'high'
				);
			}
		}
		/**
		 * Get the meta value using given meta key.
		 *
		 * @param string $meta_key The meta key name.
		 * @param int    $post_id The ID of the post being saved.
		 */
		public function get_meta_value( $meta_key, $post_id ) {
			if ( empty( $meta_key ) || empty( $post_id ) ) {
				return false;
			}
			return get_post_meta( $post_id, '_' . $meta_key, true );
		}
		/**
		 * Render Meta Box content.
		 *
		 * @param WP_Post $post The post object.
		 */
		public function render_meta_box_content( $post ) {
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'wp_music_custom_box', 'wp_music_custom_box_nonce' );
			// Display the form, using the current value.
			?>
			<p>
				<label for="composer_name">
					<strong><?php esc_attr_e( 'Composer Name', 'wp-music' ); ?></strong>
				</label>
				<input type="text" class="required" id="composer_name" name="composer_name" value="<?php echo esc_attr( $this->get_meta_value( 'composer_name', $post->ID ) ); ?>" style="width:100%" />
			</p>
			<p>
				<label for="publisher">
					<strong><?php esc_attr_e( 'Publisher', 'wp-music' ); ?></strong>
				</label>
				<input type="text" id="publisher" name="publisher" value="<?php echo esc_attr( $this->get_meta_value( 'publisher', $post->ID ) ); ?>" style="width:100%" />
			</p>
			<p>
				<label for="year_of_recording">
					<strong><?php esc_attr_e( 'Year of recording', 'wp-music' ); ?></strong>
				</label>
				<input type="number" id="year_of_recording" name="year_of_recording" value="<?php echo esc_attr( $this->get_meta_value( 'year_of_recording', $post->ID ) ); ?>" style="width:100%" />
			</p>
			<p>
				<label for="additional_contributors">
					<strong><?php esc_attr_e( 'Additional Contributors', 'wp-music' ); ?></strong>
				</label>
				<input type="text" id="additional_contributors" name="additional_contributors" value="<?php echo esc_attr( $this->get_meta_value( 'additional_contributors', $post->ID ) ); ?>" style="width:100%" />
			</p>
			<p>
				<label for="url">
					<strong><?php esc_attr_e( 'URL', 'wp-music' ); ?></strong>
				</label>
				<input type="text" id="url" name="url" value="<?php echo esc_attr( $this->get_meta_value( 'url', $post->ID ) ); ?>" style="width:100%" />
			</p>
			<p>
				<label for="price">
					<strong><?php esc_attr_e( 'Price', 'wp-music' ); ?></strong>
				</label>
				<input type="text" id="price" name="price" value="<?php echo esc_attr( $this->get_meta_value( 'price', $post->ID ) ); ?>" style="width:100%" />
			</p>
			<?php
		}
		/**
		 * Save the meta when the post is saved.
		 *
		 * @param int $post_id The ID of the post being saved.
		 */
		public function save( $post_id ) {
			/*
			* We need to verify this came from the our screen and with proper authorization,
			* because save_post can be triggered at other times.
			*/
			// Check if our nonce is set.
			if ( ! isset( $_POST['wp_music_custom_box_nonce'] ) ) {
				return $post_id;
			}
			// Add nonce for security and authentication.
			$nonce = $_POST['wp_music_custom_box_nonce'];// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $nonce, 'wp_music_custom_box' ) ) {
				return $post_id;
			}

			/*
			* If this is an autosave, our form has not been submitted,
			* so we don't want to do anything.
			*/
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
			// Check the user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
			// Check if not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return $post_id;
			}
			// Check if not a revision.
			if ( wp_is_post_revision( $post_id ) ) {
				return $post_id;
			}
			// Handle fields.
			$metabox_fields = array(
				'composer_name',
				'publisher',
				'year_of_recording',
				'additional_contributors',
				'url',
				'price',
			);
			foreach ( $metabox_fields as $field_name ) {
				$field_value = ! empty( $_POST[ $field_name ] ) ? wpm_clean( wp_unslash( $_POST[ $field_name ] ) ) : '';// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingSanitizedInput
				// Update the meta field.
				update_post_meta( $post_id, '_' . $field_name, $field_value );
				wpm_update_metadata( $post_id, '_' . $field_name, $field_value );
			}
		}
	}
	new WPM_Metabox();
}
