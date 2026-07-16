<?php

namespace MKH\TeacherAddon\Admin;

use MKH\TeacherAddon\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Module {
	public function register( Plugin $plugin ): void {
		$plugin->hooks()->add_action( 'admin_init', array( $this, 'boot' ) );
	}

	public function boot(): void {
	}
}

