<?php
/**
 * Template Name: Instructors Page
 * 
 * Standalone instructors page template with filter functionality
 * Use this if MasterStudy LMS instructors page is not configured
 * 
 * @package MS_LMS_Starter_Theme
 */

get_header();

// Load the filter data helper
require_once get_template_directory() . '/inc/instructors-filter-data.php';
require_once get_template_directory() . '/inc/instructors-filter-query.php';

?>

<div class="stm_lms_instructors_grid_wrapper">
	<div class="stm_lms_courses stm_lms_courses__archive">
		<?php
		STM_LMS_Templates::show_lms_template( 'instructors/grid-with-filter' );
		?>
	</div>
</div>

<?php
get_footer();
