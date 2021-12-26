<?php
/**
 * Functions for uninstall WP Music
 *
 * @since 1.0.0
 *
 * @package wpm
 */

// if uninstall.php is not called by WordPress, die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

/**
 * Fires on plugin uninstall.
 */
do_action( 'wpm_uninstall' );
