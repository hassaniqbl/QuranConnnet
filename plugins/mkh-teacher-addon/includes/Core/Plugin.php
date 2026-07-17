<?php

namespace MKH\TeacherAddon\Core;

use MKH\TeacherAddon\Admin\Module as Admin_Module;
use MKH\TeacherAddon\Availability\Module as Availability_Module;
use MKH\TeacherAddon\Booking\Module as Booking_Module;
use MKH\TeacherAddon\Dashboard\Module as Dashboard_Module;
use MKH\TeacherAddon\Frontend\Module as Frontend_Module;
use MKH\TeacherAddon\Registration\Registration as Registration_Module;
use MKH\TeacherAddon\Notifications\Module as Notifications_Module;
use MKH\TeacherAddon\Profile\Profile as Profile_Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {
	private Hook_Loader $hook_loader;
	private Settings $settings;
	private Assets $assets;

	/**
	 * @var array<int, object>
	 */
	private array $modules = array();

	public function __construct() {
		$this->hook_loader = new Hook_Loader();
		$this->settings    = new Settings();
		$this->assets      = new Assets();

		$this->modules = array(
			new Registration_Module(),
			new Admin_Module(),
			new Frontend_Module(),
			new Dashboard_Module(),
			new Profile_Module(),
			new Availability_Module(),
			new Booking_Module(),
			new Notifications_Module(),
		);
	}

	public function run(): void {
		load_plugin_textdomain(
			'mkh-teacher-addon',
			false,
			dirname( MKH_TEACHER_ADDON_BASENAME ) . '/languages'
		);

		$this->settings->register();
		$this->assets->register();

		$this->register_modules();
		$this->hook_loader->run();
	}

	public function settings(): Settings {
		return $this->settings;
	}

	public function assets(): Assets {
		return $this->assets;
	}

	public function hooks(): Hook_Loader {
		return $this->hook_loader;
	}

	private function register_modules(): void {
		foreach ( $this->modules as $module ) {
			if ( method_exists( $module, 'register' ) ) {
				$module->register( $this );
			}
		}
	}
}
