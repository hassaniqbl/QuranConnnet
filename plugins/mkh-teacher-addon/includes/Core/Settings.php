<?php

namespace MKH\TeacherAddon\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Settings {
	private const OPTION_NAME = 'mkh_teacher_addon_settings';

	/**
	 * @var array<string, mixed>
	 */
	private array $defaults = array(
		'enabled' => true,
	);

	public function register(): void {
		add_filter( 'mkh_teacher_addon_settings', array( $this, 'filter_settings' ) );
	}

	/**
	 * @return array<string, mixed>
	 */
	public function all(): array {
		$stored = get_option( self::OPTION_NAME, array() );

		return wp_parse_args( is_array( $stored ) ? $stored : array(), $this->defaults );
	}

	public function get( string $key, $default = null ) {
		$settings = $this->all();

		return array_key_exists( $key, $settings ) ? $settings[ $key ] : $default;
	}

	/**
	 * @param array<string, mixed> $settings
	 * @return array<string, mixed>
	 */
	public function filter_settings( array $settings ): array {
		return wp_parse_args( $settings, $this->all() );
	}
}

