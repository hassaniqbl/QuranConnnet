<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class MKH_Teacher_Addon_Autoloader {
	private const PREFIX   = 'MKH\\TeacherAddon\\';
	private const BASE_DIR = MKH_TEACHER_ADDON_PATH . 'includes/';

	public static function register(): void {
		spl_autoload_register( array( self::class, 'autoload' ) );
	}

	public static function autoload( string $class ): void {
		if ( 0 !== strpos( $class, self::PREFIX ) ) {
			return;
		}

		$relative_class = substr( $class, strlen( self::PREFIX ) );
		$file_path      = self::BASE_DIR . str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';

		if ( is_readable( $file_path ) ) {
			require_once $file_path;
		}
	}
}

