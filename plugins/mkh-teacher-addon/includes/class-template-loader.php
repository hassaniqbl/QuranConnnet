<?php
/**
 * Template Loader
 *
 * Integrates custom templates with the MasterStudy LMS template system.
 *
 * @package MKH_Teacher_Addon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MKH_Teacher_Addon_Template_Loader
 *
 * Handles loading of custom templates for the teacher profile page.
 */
class MKH_Teacher_Addon_Template_Loader {

	/**
	 * Plugin directory path.
	 *
	 * @var string
	 */
	protected $plugin_dir;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_dir Plugin directory path.
	 */
	public function __construct( $plugin_dir ) {
		$this->plugin_dir = $plugin_dir;
		
		// Hook into template loading for the existing edit-profile page
		add_filter( 'template_include', array( $this, 'load_edit_profile_template' ), 99 );
		
		// Hook into template loading
		add_filter( 'stm_lms_template_path', array( $this, 'add_template_path' ), 10, 3 );
	}

	/**
	 * Load custom template for edit-profile page.
	 *
	 * @param string $template Current template path.
	 * @return string Modified template path.
	 */
	public function load_edit_profile_template( $template ) {
		// Check if we're on the edit-profile page
		if ( is_page( 'edit-profile' ) ) {
			$custom_template = $this->plugin_dir . 'templates/account/teacher-profile.php';
			if ( file_exists( $custom_template ) ) {
				return $custom_template;
			}
		}
		
		return $template;
	}

	/**
	 * Add plugin template directory to MasterStudy template paths.
	 *
	 * @param string $template_path Current template path.
	 * @param string $template Template name.
	 * @param array  $args Template arguments.
	 * @return string Modified template path.
	 */
	public function add_template_path( $template_path, $template, $args ) {
		// Check if this is our custom template
		if ( 'account/teacher-profile' === $template ) {
			$custom_path = $this->plugin_dir . 'templates/account/teacher-profile.php';
			if ( file_exists( $custom_path ) ) {
				return $custom_path;
			}
		}
		
		if ( 'account/parts/teacher-profile-form' === $template ) {
			$custom_path = $this->plugin_dir . 'templates/account/parts/teacher-profile-form.php';
			if ( file_exists( $custom_path ) ) {
				return $custom_path;
			}
		}

		return $template_path;
	}
}
