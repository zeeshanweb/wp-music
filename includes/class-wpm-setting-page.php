<?php
/**
 * This class handles the setting page for WP Music.
 *
 * @package wpm
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WPM_Setting_Page', false ) ) {
	/**
	 * WPM_Setting_Page Class.
	 */
	class WPM_Setting_Page {
		/**
		 * Hook.
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'save_wpm_setting' ) );
			add_action( 'admin_menu', array( $this, 'wpm_admin_menu' ) );
			add_action( 'admin_footer', array( $this, 'wpm_admin_footer' ) );
		}
		/**
		 * Handle saving of settings.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function save_wpm_setting() {
			$nonce_value = wpm_get_var( $_REQUEST['wpm_form_field'], wpm_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

			if ( ! wp_verify_nonce( $nonce_value, 'wpm_form_action' ) ) {
				return;
			}

			$user_id = get_current_user_id();

			if ( $user_id <= 0 ) {
				return;
			}
			$currency        = ! empty( $_POST['currency'] ) ? wpm_clean( wp_unslash( $_POST['currency'] ) ) : '';// @codingStandardsIgnoreLine.
			$musics_per_page = ! empty( $_POST['musics_per_page'] ) ? wpm_clean( wp_unslash( $_POST['musics_per_page'] ) ) : '';// @codingStandardsIgnoreLine.
			$setting_data    = array(
				'currency'        => $currency,
				'musics_per_page' => $musics_per_page,
			);
			// phpcs:enable
			$wpm_setting_data = update_option( 'wpm_setting_data', $setting_data );
			if ( $wpm_setting_data ) {
				add_action( 'admin_notices', array( $this, 'wpm_data_notice' ) );
			}
		}
		/**
		 * Notice displays here.
		 *
		 * @since 1.0.0
		 */
		public function wpm_data_notice() {
			echo '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__( 'Settings saved successfully', 'wp-music' ) . '</strong></p></div>';
		}
		/**
		 * Add menu items.
		 *
		 * @since 1.0.0
		 */
		public function wpm_admin_menu() {
			add_submenu_page( 'edit.php?post_type=music', esc_html__( 'Music setting', 'wp-music' ), esc_html__( 'Music setting', 'wp-music' ), 'manage_options', 'wpm-musics-settings', array( $this, 'wpm_setting_content' ) );
		}
		/**
		 * Displays setting content.
		 *
		 * @since 1.0.0
		 */
		public function wpm_setting_content() {
			$wpm_setting_data = get_option( 'wpm_setting_data' );
			?>
			<h2><?php esc_html_e( 'WP Music Setting Options', 'wp-music' ); ?></h2>
			<div class="wrap container">
				<div class="fixed">
					<form name="wpm_musics_setting" id="wpm_musics_setting" action="" method="post">
					<table class="form-table">
						<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Currency', 'wp-music' ); ?></th>
						<td>
						<select name="currency" class="currency">
							<option value=""><?php esc_html_e( 'Select Currency', 'wp-music' ); ?></option>
						<?php foreach ( wpm_get_currencies() as $key => $get_currency ) { ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( isset( $wpm_setting_data['currency'] ) ? $wpm_setting_data['currency'] : null, $key ); ?>><?php echo esc_html( $get_currency ); ?></option>
						<?php } ?>
						</select>
						</td>
						</tr>
						<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Musics Per Page', 'wp-music' ); ?></th>
						<td><input type="number" name="musics_per_page" value="<?php echo esc_attr( isset( $wpm_setting_data['musics_per_page'] ) ? $wpm_setting_data['musics_per_page'] : 10 ); ?>" /></td>
						</tr>
					</table>
				<?php wp_nonce_field( 'wpm_form_action', 'wpm_form_field' ); ?>
				<?php submit_button( __( 'Save Settings', 'wp-music' ), 'primary' ); ?>
			</form>
			</div>
			<div class="flex-item">
				<h3 class="hndle"><?php esc_html_e( 'Music Shortcode', 'wp-music' ); ?></h3>
				<div class="inside">
					<?php esc_html_e( 'Music shortcodes are listed below', 'wp-music' ); ?>
					<input type="text" onfocus="this.select();" readonly="readonly" class="large-text" value="[music]"><br>
					<input type="text" onfocus="this.select();" readonly="readonly" class="large-text" value="[music year='2021']"><br>
					<input type="text" onfocus="this.select();" readonly="readonly" class="large-text" value="[music genre='any_genre_taxonomy_slug']">
				</div>
			</div>
		</div>
			<?php
		}
		/**
		 * Add css to admin footer area.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function wpm_admin_footer() {
			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';
			if ( 'music_page_wpm-musics-settings' !== $screen_id ) {
				return;
			}
			?>
			<style>
				.container {
					display:flex;
				}
				.fixed {
					background-color:#ffffff;
					width: 75%;
				}
				.fixed,
				.flex-item {
					padding: 15px;
				}
				.flex-item {
					background-color: #ffffff;
					flex-grow: 1;
					margin-left: 20px;
				}
			</style>
			<?php
		}
	}
	new WPM_Setting_Page();
}
