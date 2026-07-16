<?php

namespace MKH\TeacherAddon\Booking;

use MKH\TeacherAddon\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Module {
	public function register( Plugin $plugin ): void {
		$plugin->hooks()->add_action( 'init', array( $this, 'boot' ) );
	}

	public function boot(): void {
	}
}

