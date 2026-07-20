<?php
/**
 * Instructors Filter Asset Enqueue
 * 
 * Enqueues CSS and JS for instructor filter functionality
 * 
 * @package MS_LMS_Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue instructor filter assets on instructors page
 */
function mkh_enqueue_instructors_filter_assets() {
	$instructors_page = STM_LMS_Options::instructors_page();
	if ( ! empty( $instructors_page ) && is_page( $instructors_page ) ) {
		wp_enqueue_style( 'instructors-filter', STM_TEMPLATE_URI . '/assets/css/instructors-filter.css', array(), STM_THEME_VERSION );
		wp_enqueue_script( 'instructors-filter', STM_TEMPLATE_URI . '/assets/js/instructors-filter.js', array( 'jquery' ), STM_THEME_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'mkh_enqueue_instructors_filter_assets' );
