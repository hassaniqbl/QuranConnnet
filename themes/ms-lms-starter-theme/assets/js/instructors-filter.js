/**
 * Instructors Filter JavaScript
 * 
 * Handles filter toggle, accordion, and AJAX form interactions
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

		// Handle form submission with AJAX
		$('.stm_lms_instructors__filter_form').on('submit', function(e) {
			e.preventDefault();

			var $form = $(this);
			var $grid = $('.stm_lms_instructors__archive.filter_enabled');
			var $header = $grid.find('.stm_lms_instructors__header');
			var $countSpan = $header.find('.stm_lms_instructors__count');

			// Show loading state
			$grid.addClass('loading');

			// Collect form data
			var formData = $form.serializeArray();
			var filters = {};

			// Convert to proper format for AJAX
			$.each(formData, function(i, field) {
				if (field.name.endsWith('[]')) {
					var name = field.name.slice(0, -2);
					if (!filters[name]) {
						filters[name] = [];
					}
					filters[name].push(field.value);
				} else {
					filters[field.name] = field.value;
				}
			});

			// Add nonce
			filters.nonce = instructorFilter.nonce;
			filters.action = 'mkh_instructor_filter';

			// Debug: Log the complete payload
			console.log('MKH Instructor Filter - Form data:', formData);
			console.log('MKH Instructor Filter - Filters object:', filters);
			console.log('MKH Instructor Filter - AJAX URL:', instructorFilter.ajaxUrl);

			// AJAX request
			$.ajax({
				url: instructorFilter.ajaxUrl,
				type: 'POST',
				data: filters,
				beforeSend: function() {
					$grid.find('.stm_lms_instructors__grid').css('opacity', '0.5');
				},
				success: function(response) {
					console.log('MKH Instructor Filter - AJAX response:', response);
					if (response.success) {
						console.log('MKH Instructor Filter - Response HTML length:', response.data.html ? response.data.html.length : 0);
						console.log('MKH Instructor Filter - Response count:', response.data.count);
						// Update the grid content
						$grid.find('.stm_lms_instructors__grid').remove();
						$grid.find('.stm_lms_instructors__no_results').remove();
						$header.after(response.data.html);

						// Update the count
						$countSpan.text(response.data.count + ' teachers found');

						// Update URL without reload
						var queryString = $.param(filters);
						history.pushState(null, null, '?' + queryString);
					} else {
						console.error('Filter error:', response.data.message);
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX error:', error);
					console.error('AJAX response text:', xhr.responseText);
				},
				complete: function() {
					$grid.removeClass('loading');
					$grid.find('.stm_lms_instructors__grid').css('opacity', '1');
				}
			});
		});

		// Handle checkbox/radio changes with auto-submit (optional - comment out if not desired)
		$('.stm_lms_instructors__filter_form input[type="checkbox"], .stm_lms_instructors__filter_form input[type="radio"]').on('change', function() {
			// Uncomment to auto-submit on change
			// $(this).closest('.stm_lms_instructors__filter_form').trigger('submit');
		});

		// Handle reset button
		$('.stm_lms_instructors__filter_actions_reset').on('click', function(e) {
			e.preventDefault();
			
			// Clear all form inputs
			$('.stm_lms_instructors__filter_form input[type="checkbox"]').prop('checked', false);
			$('.stm_lms_instructors__filter_form input[type="radio"]').prop('checked', false);
			$('.stm_lms_instructors__filter_form input[type="number"]').val('');
			
			// Reset to default values for rate inputs
			$('#rate_min').val($('.stm_lms_instructors__filter_form #rate_min').attr('min') || 0);
			$('#rate_max').val($('.stm_lms_instructors__filter_form #rate_max').attr('max') || 100);
			
			// Submit the form to reset results
			$('.stm_lms_instructors__filter_form').trigger('submit');
		});

		// Initialize - collapse all filter items except first one
		$('.stm_lms_instructors__filter_options_item:not(:first)').addClass('collapsed');

		// Ensure proper form state on page load
		$('.stm_lms_instructors__filter_form input:checked').each(function() {
			$(this).closest('.stm_lms_instructors__filter_options_item').removeClass('collapsed');
		});
	});

})(jQuery);
