<?php
/**
 * Dashboard Menu Integration
 *
 * Integrates the Teacher Profile menu item into the MasterStudy LMS Instructor Dashboard.
 *
 * @package MKH_Teacher_Addon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MKH_Teacher_Addon_Dashboard_Menu
 *
 * Adds the Edit Profile menu item to the MasterStudy Instructor Dashboard.
 */
class MKH_Teacher_Addon_Dashboard_Menu {

	/**
	 * Constructor.
	 *
	 * Hooks into MasterStudy menu filters to add the teacher profile menu item.
	 */
	public function __construct() {
		add_filter( 'stm_lms_menu_items', array( $this, 'add_teacher_profile_menu' ), 15 );
	}

	/**
	 * Add Teacher Profile menu item to the dashboard.
	 *
	 * @param array $menu_items Existing menu items.
	 * @return array Modified menu items.
	 */
	public function add_teacher_profile_menu( $menu_items ) {
		// Only add for instructors
		if ( ! STM_LMS_Instructor::is_instructor() ) {
			return $menu_items;
		}

		$current_slug = STM_LMS_User_Menu::get_current_account_slug();

		$menu_items[] = array(
			'order'        => 105,
			'id'           => 'teacher_profile',
			'slug'         => 'edit-profile',
			'lms_template' => 'account/teacher-profile',
			'menu_title'   => esc_html__( 'Edit Profile', 'mkh-teacher-addon' ),
			'menu_icon'    => 'stmlms-menu-settings',
			'menu_url'     => home_url( '/edit-profile/' ),
			'is_active'    => 'edit-profile' === $current_slug,
			'menu_place'   => 'main',
			'section'      => 'account',
		);

		return $menu_items;
	}
}

// Initialize the class
new MKH_Teacher_Addon_Dashboard_Menu();
