<?php
/**
 * Plugin Name: MKH Teacher Add-On
 * Description: Update-safe teacher module foundation for Muslim Kids Hub and MasterStudy LMS.
 * Version: 0.1.0
 * Requires PHP: 8.0
 * Text Domain: mkh-teacher-addon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MKH_TEACHER_ADDON_VERSION', '0.1.0' );
define( 'MKH_TEACHER_ADDON_FILE', __FILE__ );
define( 'MKH_TEACHER_ADDON_PATH', plugin_dir_path( __FILE__ ) );
define( 'MKH_TEACHER_ADDON_URL', plugin_dir_url( __FILE__ ) );
define( 'MKH_TEACHER_ADDON_BASENAME', plugin_basename( __FILE__ ) );

require_once MKH_TEACHER_ADDON_PATH . 'includes/Helpers/functions.php';
require_once MKH_TEACHER_ADDON_PATH . 'includes/Core/Autoloader.php';

MKH_Teacher_Addon_Autoloader::register();

function mkh_teacher_addon_boot(): void {
	mkh_teacher_addon()->run();
}

add_action( 'plugins_loaded', 'mkh_teacher_addon_boot' );

