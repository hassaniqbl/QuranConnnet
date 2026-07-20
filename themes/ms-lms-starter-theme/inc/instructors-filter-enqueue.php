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
 * Check if current page is an instructors page that needs filter assets.
 *
 * Uses direct post meta check for reliability during wp_enqueue_scripts.
 *
 * @return bool
 */
function mkh_is_instructors_filter_page() {
	// Check via MasterStudy LMS instructors page setting
	$instructors_page = STM_LMS_Options::instructors_page();
	if ( ! empty( $instructors_page ) && is_page( $instructors_page ) ) {
		return true;
	}

	// Check via page template meta (most reliable check)
	if ( is_page() ) {
		$page_id       = get_queried_object_id();
		$page_template = get_post_meta( $page_id, '_wp_page_template', true );
		if ( 'page-instructors.php' === $page_template ) {
			return true;
		}
	}

	// Check via page slug as fallback
	if ( is_page() ) {
		$post_slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( in_array( $post_slug, array( 'instructors', 'teachers' ), true ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Enqueue instructor filter assets on instructors page
 */
function mkh_enqueue_instructors_filter_assets() {
	if ( mkh_is_instructors_filter_page() ) {
		// Enqueue with proper dependencies to ensure MasterStudy styles load first
		wp_enqueue_style( 'instructors-filter', STM_TEMPLATE_URI . '/assets/css/instructors-filter.css', array('stm_lms_styles', 'user', 'instructors_grid'), STM_THEME_VERSION );
		wp_enqueue_script( 'instructors-filter', STM_TEMPLATE_URI . '/assets/js/instructors-filter.js', array( 'jquery' ), STM_THEME_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'mkh_enqueue_instructors_filter_assets' );
