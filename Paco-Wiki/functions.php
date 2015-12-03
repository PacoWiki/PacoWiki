<?php


/**
* get_all_roles
* get list of all available roles in WP
*/
function pacowiki_get_all_roles() {
	global $wp_roles;

	if ( class_exists( 'WP_Roles' ) ) {
		if ( ! isset( $wp_roles ) ) {
			$wp_roles = new WP_Roles();
		}
	}

	$all_roles = $wp_roles->roles;
	return $all_roles;
}

/**
* get_editable_roles
* get list of editable roles
*/
function pacowiki_get_editable_roles() {
	$all_roles = pacowiki_get_all_roles();
	$editable_roles = apply_filters('editable_roles', $all_roles);
	return $editable_roles;
}