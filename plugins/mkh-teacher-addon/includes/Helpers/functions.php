<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mkh_teacher_addon(): \MKH\TeacherAddon\Core\Plugin {
	static $plugin = null;

	if ( null === $plugin ) {
		$plugin = new \MKH\TeacherAddon\Core\Plugin();
	}

	return $plugin;
}

function mkh_teacher_addon_path( string $path = '' ): string {
	return MKH_TEACHER_ADDON_PATH . ltrim( $path, '/\\' );
}

function mkh_teacher_addon_url( string $path = '' ): string {
	return MKH_TEACHER_ADDON_URL . ltrim( $path, '/\\' );
}

function mkh_teacher_addon_settings(): array {
	return mkh_teacher_addon()->settings()->all();
}

function mkh_teacher_addon_terms_version(): string {
	$settings = mkh_teacher_addon_settings();

	return (string) ( $settings['terms_version'] ?? MKH_TEACHER_ADDON_VERSION );
}

function mkh_teacher_addon_client_ip(): string {
	$server_keys = array(
		'HTTP_CF_CONNECTING_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_REAL_IP',
		'REMOTE_ADDR',
	);

	foreach ( $server_keys as $server_key ) {
		if ( empty( $_SERVER[ $server_key ] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			continue;
		}

		$raw_ip = (string) wp_unslash( $_SERVER[ $server_key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$ips    = array_map( 'trim', explode( ',', $raw_ip ) );

		foreach ( $ips as $ip ) {
			if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
				return $ip;
			}
		}
	}

	return '';
}

function mkh_teacher_addon_is_registration_screen(): bool {
	if ( is_admin() ) {
		return false;
	}

	if ( ! function_exists( 'get_option' ) ) {
		return false;
	}

	$settings = get_option( 'stm_lms_settings', array() );
	$page_ids = array_filter(
		array(
			absint( $settings['user_url'] ?? 0 ),
			absint( $settings['instructor_registration_page'] ?? 0 ),
		)
	);

	if ( ! empty( $page_ids ) && is_singular() ) {
		return in_array( get_queried_object_id(), $page_ids, true );
	}

	return true;
}

function mkh_teacher_addon_is_profile_screen(): bool {
	if ( is_admin() ) {
		return false;
	}

	$settings = get_option( 'stm_lms_settings', array() );
	$user_url = absint( $settings['user_url'] ?? 0 );

	if ( empty( $user_url ) || ! is_singular() ) {
		return false;
	}

	return get_queried_object_id() === $user_url;
}
