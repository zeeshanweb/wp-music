<?php
/**
 * Plugin Name: WP Music
 * Plugin URI: https://khanzeeshan.in
 * Description: WP Music plugin.
 * Author: Zeeshan
 * Author URI: https://khanzeeshan.in
 * Version: 1.0.0
 * Text Domain: wp-music
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package wpm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Required minimums and constants
 */
define( 'WPM_VERSION', '1.1.1' ); // WRCS: DEFINED_VERSION.
define( 'WPM_MAIN_FILE', __FILE__ );
define( 'WPM_PLUGIN_BASENAME', plugin_basename( WPM_MAIN_FILE ) );
define( 'WPM_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'WPM_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
add_action( 'plugins_loaded', 'wpm_init' );
/**
 * Required minimums and constants.
 */
function wpm_init() {
	load_plugin_textdomain( 'wp-music', false, plugin_basename( dirname( __FILE__ ) ) . '/i18n/languages' );
	if ( ! class_exists( 'WPM_Load_Files' ) ) {
		/**
		 * WPM_Load_Files class.
		 */
		class WPM_Load_Files {
			/**
			 * Instance of this class.
			 *
			 * @var Singleton The reference the *Singleton* instance of this class
			 */
			private static $instance;
			/**
			 * Returns the *Singleton* instance of this class.
			 *
			 * @return Singleton The *Singleton* instance.
			 */
			public static function get_instance() {
				if ( null === self::$instance ) {
					self::$instance = new self();
				}
				return self::$instance;
			}
			/**
			 * Protected constructor to prevent creating a new instance of the
			 * *Singleton* via the `new` operator from outside of this class.
			 */
			private function __construct() {
				add_action( 'admin_init', array( $this, 'install' ) );
				$this->init();
				add_filter( 'plugin_action_links_' . WPM_PLUGIN_BASENAME, array( $this, 'wpm_action_links' ) );
			}
			/**
			 * Include required core files used in admin and on the frontend.
			 *
			 * @version 1.0.0
			 * @since   1.0.0
			 */
			public function init() {
				require_once dirname( __FILE__ ) . '/includes/function/wpm-meta-function.php';
				require_once dirname( __FILE__ ) . '/includes/function/helper-function.php';
				require_once dirname( __FILE__ ) . '/includes/class-wpm-cpt.php';
				require_once dirname( __FILE__ ) . '/includes/class-wpm-setting-page.php';
				require_once dirname( __FILE__ ) . '/includes/class-wpm-metabox.php';
				require_once dirname( __FILE__ ) . '/includes/class-wpm-shortcode-musics.php';
			}
			/**
			 * Link to UWC settings page from plugins screen.
			 *
			 * @since 1.0.0
			 * @version 1.0.0
			 * @param string[] $actions An array of plugin action links.
			 * @return array
			 */
			public function wpm_action_links( $actions ) {
				$uwc_link = array(
					'settings' => '<a href="' . admin_url( 'edit.php?post_type=music&page=wpm-musics-settings' ) . '" aria-label="' . esc_attr__( 'View WP music settings', 'wp-music' ) . '">' . esc_html__( 'Settings', 'wp-music' ) . '</a>',
				);
				return array_merge( $uwc_link, $actions );
			}
			/**
			 * Updates the plugin version in db
			 *
			 * @since 1.0.0
			 * @version 1.0.0
			 */
			public function update_plugin_version() {
				delete_option( 'wpm_version' );
				update_option( 'wpm_version', WPM_VERSION );
			}
			/**
			 * Handles upgrade routines.
			 *
			 * @since 1.0.0
			 * @version 1.0.0
			 */
			public function install() {
				if ( ! is_plugin_active( plugin_basename( __FILE__ ) ) ) {
					return;
				}

				if ( WPM_VERSION !== get_option( 'wpm_version' ) ) {
					do_action( 'wpm_updated' );
					$this->update_plugin_version();
				}
			}
		}
		WPM_Load_Files::get_instance();
	}
}
/**
 * The code that runs during plugin activation.
 */
function wp_music_activation_hook() {
	require_once dirname( __FILE__ ) . '/includes/class-wpm-install.php';
}
register_activation_hook( __FILE__, 'wp_music_activation_hook' );
