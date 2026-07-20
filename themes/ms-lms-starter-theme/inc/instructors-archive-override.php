<?php
/**
 * Instructors Archive Override
 * 
 * Overrides the instructors archive to include filter sidebar
 * 
 * @package MS_LMS_Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Override instructors archive content to use filtered grid
 */
function mkh_override_instructors_archive_content( $content ) {
	$instructors_page = STM_LMS_Options::instructors_page();
	
	// Only modify on the instructors page
	if ( empty( $instructors_page ) || ! is_page( $instructors_page ) ) {
		return $content;
	}

	// Check if we've already processed this to prevent infinite loop
	static $processed = false;
	if ( $processed ) {
		return $content;
	}
	$processed = true;

	$extra_content  = '<div class="stm_lms_instructors_grid_wrapper">';
	$extra_content .= '<div class="stm_lms_courses stm_lms_courses__archive">';
	$extra_content .= STM_LMS_Templates::load_lms_template(
		'instructors/grid-with-filter'
	);
	$extra_content .= '</div>';
	$extra_content .= '</div>';

	return $content . $extra_content;
}

// Add a fallback for when no instructors page is set
function mkh_fallback_instructors_page( $template ) {
	// Check if we're on a custom instructors URL
	if ( is_page() || is_singular() ) {
		global $post;
		if ( $post && ( 'instructors' === $post->post_name || 'teachers' === $post->post_name ) ) {
			// Load our custom template
			$new_template = locate_template( array( 'stm-lms-templates/instructors/grid-with-filter.php' ) );
			if ( ! empty( $new_template ) ) {
				return $new_template;
			}
		}
	}
	return $template;
}
add_filter( 'template_include', 'mkh_fallback_instructors_page' );

// Remove the original instructors archive filter and add our custom one
remove_filter( 'the_content', array( 'STM_LMS_Templates', 'instructors_archive_content' ), 100 );
add_filter( 'the_content', 'mkh_override_instructors_archive_content', 100 );
