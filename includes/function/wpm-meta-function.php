<?php
/**
 * WP Music meta functions.
 *
 * @version 1.0.0
 * @package wpm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds metadata to custom music meta table.
 *
 * @param int    $object_id  ID of the object metadata is for.
 * @param string $meta_key   Metadata key.
 * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
 * @return int|false The meta ID on success, false on failure.
 */
function wpm_add_metadata( $object_id, $meta_key, $meta_value ) {
	global $wpdb;
	if ( ! $meta_key || ! is_numeric( $object_id ) ) {
		return false;
	}
	$meta_type = 'post';
	$object_id = absint( $object_id );
	if ( ! $object_id ) {
		return false;
	}

	$table = wpm_get_meta_table();
	if ( ! $table ) {
		return false;
	}
	$column     = sanitize_key( $meta_type . '_id' );
	$meta_key   = wp_unslash( $meta_key );
	$meta_value = wp_unslash( $meta_value );

	if ( $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM $table WHERE meta_key = %s AND $column = %d",
			$meta_key,
			$object_id
		)
	) ) {
		return false;
	}
	$meta_value = maybe_serialize( $meta_value );
	$result     = $wpdb->insert(
		$table,
		array(
			$column      => $object_id,
			'meta_key'   => $meta_key,
			'meta_value' => $meta_value,
		)
	);

	if ( ! $result ) {
		return false;
	}
	$mid = (int) $wpdb->insert_id;
	return $mid;
}
/**
 * Retrieves the name of the music metadata table.
 *
 * @return string|false Metadata table name, or false if no metadata table exists
 */
function wpm_get_meta_table() {
	global $wpdb;
	if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wpdb->prefix}wpm_music'" ) ) {
		return $wpdb->prefix . 'wpm_music';
	}
	return false;
}
/**
 * Updates metadata for the specified object to custom music meta table.
 *
 * @param int    $object_id  ID of the object metadata is for.
 * @param string $meta_key   Metadata key.
 * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
 * @return int|bool The new meta field ID if a field with the given key didn't exist
 *                  and was therefore added, true on successful update,
 *                  false on failure or if the value passed to the function
 *                  is the same as the one that is already in the database.
 */
function wpm_update_metadata( $object_id, $meta_key, $meta_value ) {
	global $wpdb;
	if ( ! $meta_key || ! is_numeric( $object_id ) ) {
		return false;
	}
	$meta_type = 'post';
	$object_id = absint( $object_id );
	if ( ! $object_id ) {
		return false;
	}
	$table = wpm_get_meta_table();
	if ( ! $table ) {
		return false;
	}
	$column    = sanitize_key( $meta_type . '_id' );
	$id_column = 'meta_id';

	// Expected_slashed.
	$raw_meta_key = $meta_key;
	$meta_key     = wp_unslash( $meta_key );
	$meta_value   = wp_unslash( $meta_value );

	$meta_ids = $wpdb->get_col( $wpdb->prepare( "SELECT $id_column FROM $table WHERE meta_key = %s AND $column = %d", $meta_key, $object_id ) );
	if ( empty( $meta_ids ) ) {
		return wpm_add_metadata( $object_id, $meta_key, $meta_value );
	}
	$meta_value = maybe_serialize( $meta_value );

	$data  = compact( 'meta_value' );
	$where = array(
		$column    => $object_id,
		'meta_key' => $meta_key,
	);
	$result = $wpdb->update( $table, $data, $where );
	if ( ! $result ) {
		return false;
	}
	return true;
}
