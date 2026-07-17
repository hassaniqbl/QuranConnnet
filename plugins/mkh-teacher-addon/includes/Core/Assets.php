<?php

namespace MKH\TeacherAddon\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Assets {
	public function register(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'register_login_assets' ) );
	}

	public function register_frontend_assets(): void {
		wp_register_style(
			'mkh-teacher-addon',
			MKH_TEACHER_ADDON_URL . 'assets/css/mkh-teacher-addon.css',
			array(),
			MKH_TEACHER_ADDON_VERSION
		);

		wp_register_script(
			'mkh-teacher-addon',
			MKH_TEACHER_ADDON_URL . 'assets/js/mkh-teacher-addon.js',
			array( 'jquery' ),
			MKH_TEACHER_ADDON_VERSION,
			true
		);

		wp_register_style(
			'mkh-teacher-addon-profile',
			MKH_TEACHER_ADDON_URL . 'assets/css/mkh-teacher-addon-profile.css',
			array(),
			MKH_TEACHER_ADDON_VERSION
		);

		wp_register_script(
			'mkh-teacher-addon-profile',
			MKH_TEACHER_ADDON_URL . 'assets/js/mkh-teacher-addon-profile.js',
			array( 'jquery', 'mkh-teacher-addon' ),
			MKH_TEACHER_ADDON_VERSION,
			true
		);
	}

	public function register_admin_assets(): void {
		$this->register_frontend_assets();
	}

	public function register_login_assets(): void {
		$this->register_frontend_assets();
	}

	public function enqueue(): void {
		wp_enqueue_style( 'mkh-teacher-addon' );
		wp_enqueue_script( 'mkh-teacher-addon' );
	}

	public function enqueue_profile(): void {
		wp_enqueue_style( 'mkh-teacher-addon-profile' );
		wp_enqueue_script( 'mkh-teacher-addon-profile' );
	}
}
