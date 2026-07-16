<?php

namespace MKH\TeacherAddon\Frontend;

use MKH\TeacherAddon\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Module {
	public function register( Plugin $plugin ): void {
		$plugin->hooks()->add_action( 'wp_enqueue_scripts', array( $this, 'boot' ), 20 );
	}

	public function boot(): void {
	}
}

