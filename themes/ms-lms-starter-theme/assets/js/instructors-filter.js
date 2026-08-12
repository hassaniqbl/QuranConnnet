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

			// Check if instructorFilter is defined
			if (typeof instructorFilter === 'undefined') {
				console.error('MKH Instructor Filter - instructorFilter object not defined');
				alert('Filter system not properly initialized. Please refresh the page.');
				return;
			}

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

			// Add nonce and action
			filters.nonce = instructorFilter.nonce;
			filters.action = 'mkh_instructor_filter';

			// AJAX request
			$.ajax({
				url: instructorFilter.ajaxUrl,
				type: 'POST',
				dataType: 'json',
				data: filters,
				beforeSend: function() {
					$grid.find('.stm_lms_instructors__grid').css('opacity', '0.5');
				},
				success: function(response) {
					console.log('AJAX Response:', response);

					if (response.success && response.data && response.data.html !== undefined) {
						const html = response.data.html;
						const count = response.data.count;

						console.log('HTML:', html);
						console.log('Count:', count);

						// Target the correct grid container
						const $instructorGrid = $grid.find('.stm_lms_instructors__grid');
						const $noResults = $grid.find('.stm_lms_instructors__no_results');

						console.log('Instructor grid element:', $instructorGrid.length);
						console.log('No results element:', $noResults.length);

						// Remove existing no results message
						$noResults.remove();

						// Replace the grid content
						if ($instructorGrid.length) {
							// Replace existing grid with new HTML
							$instructorGrid.replaceWith(html);
							console.log('Replaced existing grid');
						} else {
							// Insert new grid after header if no grid exists
							$header.after(html);
							console.log('Inserted new grid after header');
						}

						// Verify the insertion
						const $newGrid = $grid.find('.stm_lms_instructors__grid');
						const cardCount = $newGrid.find('.stm_lms_instructors__single').length;
						console.log('New grid element:', $newGrid.length);
						console.log('Cards after insertion:', cardCount);

						// Update the count
						$countSpan.text(count + ' teachers found');

						// Update URL without reload (remove nonce and action from URL)
						var urlFilters = $.extend({}, filters);
						delete urlFilters.nonce;
						delete urlFilters.action;
						var queryString = $.param(urlFilters);
						history.pushState(null, null, '?' + queryString);
					} else {
						console.error('Filter error:', response);
						alert('Filter error: ' + (response.data && response.data.message ? response.data.message : 'Unknown error'));
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', xhr, status, error);
					alert('There was an error processing your request. Please try again.');
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
			
			// Clear URL parameters
			history.pushState(null, null, window.location.pathname);
			
			// Submit the form to reset results via AJAX
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
