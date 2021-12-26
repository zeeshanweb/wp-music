<?php
/**
 * Handles creation of required DB tables.
 *
 * @package     wpm
 * @license     https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPM_Install' ) ) {
	/**
	 * WPM_Install Class.
	 */
	class WPM_Install {
		/**
		 * Hook into the appropriate actions when the class is constructed.
		 */
		public function __construct() {
			$this->create_tables();
		}
		/**
		 * Set up the database tables which the plugin needs to function.
		 */
		public function create_tables() {
			global $wpdb;
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $this->get_schema() );
		}
		/**
		 * Get Table schema.
		 *
		 * @return string
		 */
		public function get_schema() {
			global $wpdb;
			$collate = '';

			if ( $wpdb->has_cap( 'collation' ) ) {
				$collate = $wpdb->get_charset_collate();
			}

			$tables = "
			CREATE TABLE {$wpdb->prefix}wpm_music (
			meta_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			post_id BIGINT UNSIGNED NOT NULL,
			meta_key varchar(255) NULL,
			meta_value longtext NULL,
			PRIMARY KEY  (meta_id),
			KEY post_id (post_id),
			KEY meta_key (meta_key)
			) $collate;";

			return $tables;
		}
	}
	new WPM_Install();
}
