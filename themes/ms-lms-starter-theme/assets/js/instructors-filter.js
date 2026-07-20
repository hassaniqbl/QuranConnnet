/**
 * Instructors Filter JavaScript
 * 
 * Handles filter toggle, accordion, and form interactions
 * Based on courses filter behavior
 * 
 * @package MS_LMS_Starter_Theme
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		// Filter toggle for mobile/tablet
		$('.stm_lms_instructors__filter_toggle').on('click', function(e) {
			e.preventDefault();
			$(this).closest('.stm_lms_instructors__filter').toggleClass('active');
		});

		// Accordion toggle
		$('.stm_lms_instructors__filter_options_item_title').on('click', function() {
			$(this).closest('.stm_lms_instructors__filter_options_item').toggleClass('collapsed');
		});

		// Handle form submission with AJAX if available
		$('.stm_lms_instructors__filter_form').on('submit', function(e) {
			// Let the form submit normally for now (URL parameters)
			// AJAX can be added later if needed
		});

		// Initialize - collapse all filter items except first one
		$('.stm_lms_instructors__filter_options_item:not(:first)').addClass('collapsed');
	});

})(jQuery);
