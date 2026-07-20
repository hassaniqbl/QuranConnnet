<?php
/**
 * Template Name: Instructors Page
 * 
 * Standalone instructors page template with filter functionality
 * Use this if MasterStudy LMS instructors page is not configured
 * 
 * @package MS_LMS_Starter_Theme
 */

// Load the filter data helper — must be BEFORE get_header() so enqueues fire before wp_head()
require_once get_template_directory() . '/inc/instructors-filter-data.php';
require_once get_template_directory() . '/inc/instructors-filter-query.php';

// Register required styles
stm_lms_register_style( 'user' );
stm_lms_register_style( 'instructors_grid' );

// Directly enqueue instructors filter assets — must be BEFORE get_header()/wp_head()
wp_enqueue_style( 'instructors-filter', STM_TEMPLATE_URI . '/assets/css/instructors-filter.css', array(), STM_THEME_VERSION,true  );
wp_enqueue_script( 'instructors-filter', STM_TEMPLATE_URI . '/assets/js/instructors-filter.js', array( 'jquery' ), STM_THEME_VERSION, true );

get_header();
?>

<div id="wrapper" class="wrapper">
	<div class="container">
		<?php
		STM_LMS_Templates::show_lms_template( 'instructors/grid-with-filter' );
		?>
	</div>
</div>

<?php
get_footer();
