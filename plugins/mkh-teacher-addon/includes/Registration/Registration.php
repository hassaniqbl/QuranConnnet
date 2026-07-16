<?php

namespace MKH\TeacherAddon\Registration;

use MKH\TeacherAddon\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Registration {
	public function register( Plugin $plugin ): void {
		Hooks::register( $plugin );
	}
}
