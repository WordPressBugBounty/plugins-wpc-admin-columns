<?php
defined( 'ABSPATH' ) || exit;

register_activation_hook( defined( 'WPCAC_LITE' ) ? WPCAC_LITE : WPCAC_FILE, 'wpcac_activate' );
register_deactivation_hook( defined( 'WPCAC_LITE' ) ? WPCAC_LITE : WPCAC_FILE, 'wpcac_deactivate' );
add_action( 'admin_init', 'wpcac_check_version' );

function wpcac_check_version() {
	if ( ! empty( get_option( 'wpcac_version' ) ) && ( get_option( 'wpcac_version' ) < WPCAC_VERSION ) ) {
		wpc_log( 'wpcac', 'upgraded' );
		update_option( 'wpcac_version', WPCAC_VERSION, false );
	}
}

function wpcac_activate() {
	wpc_log( 'wpcac', 'installed' );
	update_option( 'wpcac_version', WPCAC_VERSION, false );
}

function wpcac_deactivate() {
	wpc_log( 'wpcac', 'deactivated' );
}

if ( ! function_exists( 'wpc_log' ) ) {
	function wpc_log( $prefix, $action ) {
		$logs = get_option( 'wpc_logs', [] );
		$user = wp_get_current_user();

		if ( ! isset( $logs[ $prefix ] ) ) {
			$logs[ $prefix ] = [];
		}

		$logs[ $prefix ][] = [
			'time'   => current_time( 'mysql' ),
			'user'   => $user->display_name . ' (ID: ' . $user->ID . ')',
			'action' => $action
		];

		update_option( 'wpc_logs', $logs, false );
	}
}