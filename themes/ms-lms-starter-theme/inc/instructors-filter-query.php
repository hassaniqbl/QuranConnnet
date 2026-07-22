<?php
/**
 * Instructors Filter Query Helper
 * 
 * Applies filters to WP_User_Query for instructors
 * 
 * @package MS_LMS_Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get filtered instructors based on filter parameters
 * 
 * @param array $filters Filter parameters (optional, defaults to $_GET)
 * @return array Instructors with filter applied
 */
function mkh_get_filtered_instructors( $filters = array() ) {
	// Get filter parameters from provided array or $_GET
	if ( empty( $filters ) ) {
		$gender = isset( $_GET['gender'] ) ? sanitize_text_field( wp_unslash( $_GET['gender'] ) ) : '';
		$ijazah = isset( $_GET['ijazah'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['ijazah'] ) ) : array();
		$subjects = isset( $_GET['subjects'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['subjects'] ) ) : array();
		$languages = isset( $_GET['languages'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['languages'] ) ) : array();
		$rate_min = isset( $_GET['rate_min'] ) ? floatval( wp_unslash( $_GET['rate_min'] ) ) : '';
		$rate_max = isset( $_GET['rate_max'] ) ? floatval( wp_unslash( $_GET['rate_max'] ) ) : '';
		$rating = isset( $_GET['rating'] ) ? floatval( wp_unslash( $_GET['rating'] ) ) : '';
		$country = isset( $_GET['country'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['country'] ) ) : array();
		$timezone = isset( $_GET['timezone'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['timezone'] ) ) : array();
	} else {
		$gender = isset( $filters['gender'] ) ? sanitize_text_field( $filters['gender'] ) : '';
		$ijazah = isset( $filters['ijazah'] ) ? array_map( 'sanitize_text_field', $filters['ijazah'] ) : array();
		$subjects = isset( $filters['subjects'] ) ? array_map( 'sanitize_text_field', $filters['subjects'] ) : array();
		$languages = isset( $filters['languages'] ) ? array_map( 'sanitize_text_field', $filters['languages'] ) : array();
		$rate_min = isset( $filters['rate_min'] ) ? floatval( $filters['rate_min'] ) : '';
		$rate_max = isset( $filters['rate_max'] ) ? floatval( $filters['rate_max'] ) : '';
		$rating = isset( $filters['rating'] ) ? floatval( $filters['rating'] ) : '';
		$country = isset( $filters['country'] ) ? array_map( 'sanitize_text_field', $filters['country'] ) : array();
		$timezone = isset( $filters['timezone'] ) ? array_map( 'sanitize_text_field', $filters['timezone'] ) : array();
	}

	// Base user query args
	$user_args = array(
		'role'   => STM_LMS_Instructor::role(),
		'number' => -1,
	);

	// Get all instructors first
	$user_query = new WP_User_Query( $user_args );
	$instructors = $user_query->get_results();

	// Apply filters
	$filtered_instructors = array();

	foreach ( $instructors as $instructor ) {
		$user_id = $instructor->ID;
		$include_instructor = true;

		// Gender filter
		if ( ! empty( $gender ) ) {
			$instructor_gender = get_field( 'mkh_gender', 'user_' . $user_id );
			if ( $instructor_gender !== $gender ) {
				$include_instructor = false;
			}
		}

		// Ijazah filter
		if ( ! empty( $ijazah ) && $include_instructor ) {
			$instructor_ijazah = get_field( 'ijazah', 'user_' . $user_id );
			$ijazah_titles = array();
			if ( ! empty( $instructor_ijazah ) && is_array( $instructor_ijazah ) ) {
				foreach ( $instructor_ijazah as $ijazah_item ) {
					if ( ! empty( $ijazah_item['title'] ) ) {
						$ijazah_titles[] = $ijazah_item['title'];
					}
				}
			}
			$has_matching_ijazah = false;
			foreach ( $ijazah as $filter_ijazah ) {
				if ( in_array( $filter_ijazah, $ijazah_titles, true ) ) {
					$has_matching_ijazah = true;
					break;
				}
			}
			if ( ! $has_matching_ijazah ) {
				$include_instructor = false;
			}
		}

		// Subjects filter (teaching skills)
		if ( ! empty( $subjects ) && $include_instructor ) {
			$instructor_skills = get_field( 'teaching_skills', 'user_' . $user_id );
			$custom_subjects = get_field( 'mkh_subjects', 'user_' . $user_id );
			
			// Combine both skills and custom subjects
			$all_subjects = array();
			if ( ! empty( $instructor_skills ) && is_array( $instructor_skills ) ) {
				$all_subjects = array_merge( $all_subjects, $instructor_skills );
			}
			if ( ! empty( $custom_subjects ) && is_array( $custom_subjects ) ) {
				$all_subjects = array_merge( $all_subjects, $custom_subjects );
			}
			
			$has_matching_skill = false;
			if ( ! empty( $all_subjects ) ) {
				foreach ( $subjects as $filter_skill ) {
					if ( in_array( $filter_skill, $all_subjects, true ) ) {
						$has_matching_skill = true;
						break;
					}
				}
			}
			if ( ! $has_matching_skill ) {
				$include_instructor = false;
			}
		}

		// Languages filter
		if ( ! empty( $languages ) && $include_instructor ) {
			$instructor_languages = get_field( 'languages', 'user_' . $user_id );
			$has_matching_language = false;
			if ( ! empty( $instructor_languages ) && is_array( $instructor_languages ) ) {
				foreach ( $languages as $filter_language ) {
					if ( in_array( $filter_language, $instructor_languages, true ) ) {
						$has_matching_language = true;
						break;
					}
				}
			}
			if ( ! $has_matching_language ) {
				$include_instructor = false;
			}
		}

		// Hourly rate filter
		if ( ( ! empty( $rate_min ) || ! empty( $rate_max ) ) && $include_instructor ) {
			$instructor_rate = get_field( 'hourly_rate', 'user_' . $user_id );
			if ( empty( $instructor_rate ) || ! is_numeric( $instructor_rate ) ) {
				$include_instructor = false;
			} else {
				$instructor_rate = floatval( $instructor_rate );
				if ( ! empty( $rate_min ) && $instructor_rate < $rate_min ) {
					$include_instructor = false;
				}
				if ( ! empty( $rate_max ) && $instructor_rate > $rate_max ) {
					$include_instructor = false;
				}
			}
		}

		// Rating filter
		if ( ! empty( $rating ) && $include_instructor ) {
			$user = STM_LMS_User::get_current_user( $user_id, false, true );
			$instructor_rating = STM_LMS_Instructor::my_rating_v2( $user );
			$average_rating = ! empty( $instructor_rating['average'] ) ? floatval( $instructor_rating['average'] ) : 0;
			if ( $average_rating < $rating ) {
				$include_instructor = false;
			}
		}

		// Country filter
		if ( ! empty( $country ) && $include_instructor ) {
			$instructor_country = get_field( 'mkh_country', 'user_' . $user_id );
			if ( ! in_array( $instructor_country, $country, true ) ) {
				$include_instructor = false;
			}
		}

		// Timezone filter
		if ( ! empty( $timezone ) && $include_instructor ) {
			$instructor_timezone = get_field( 'mkh_timezone', 'user_' . $user_id );
			if ( ! in_array( $instructor_timezone, $timezone, true ) ) {
				$include_instructor = false;
			}
		}

		if ( $include_instructor ) {
			$filtered_instructors[] = $instructor;
		}
	}

	return $filtered_instructors;
}

/**
 * AJAX handler for instructor filtering
 */
function mkh_instructor_filter_ajax() {
	// Debug: Log incoming POST data
	error_log( 'MKH Instructor Filter - POST data: ' . print_r( $_POST, true ) );

	// Verify nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'instructor_filter_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
	}

	// Sanitize and get filter parameters
	$filters = array(
		'gender'   => isset( $_POST['gender'] ) ? sanitize_text_field( wp_unslash( $_POST['gender'] ) ) : '',
		'ijazah'   => isset( $_POST['ijazah'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['ijazah'] ) ) : array(),
		'subjects' => isset( $_POST['subjects'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['subjects'] ) ) : array(),
		'languages' => isset( $_POST['languages'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['languages'] ) ) : array(),
		'rate_min' => isset( $_POST['rate_min'] ) ? floatval( wp_unslash( $_POST['rate_min'] ) ) : '',
		'rate_max' => isset( $_POST['rate_max'] ) ? floatval( wp_unslash( $_POST['rate_max'] ) ) : '',
		'rating'   => isset( $_POST['rating'] ) ? floatval( wp_unslash( $_POST['rating'] ) ) : '',
		'country'  => isset( $_POST['country'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['country'] ) ) : array(),
		'timezone' => isset( $_POST['timezone'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['timezone'] ) ) : array(),
	);

	// Debug: Log sanitized filters
	error_log( 'MKH Instructor Filter - Sanitized filters: ' . print_r( $filters, true ) );

	// Get filtered instructors
	$instructors = mkh_get_filtered_instructors( $filters );
	$instructor_public = STM_LMS_Options::get_option( 'instructor_public_profile', true );
	$instructor_count = count( $instructors );

	// Debug: Log instructor count and data
	error_log( 'MKH Instructor Filter - Instructor count: ' . $instructor_count );
	error_log( 'MKH Instructor Filter - Instructors array: ' . print_r( $instructors, true ) );

	// Generate HTML output
	ob_start();
	if ( ! empty( $instructors ) ) :
		?>
		<div class="stm_lms_instructors__grid">
			<?php
			foreach ( $instructors as $user ) :
				$user_profile_url = STM_LMS_User::instructor_public_page_url( $user->ID );
				$user_data = STM_LMS_User::get_current_user( $user->ID, false, true );
				$reviews = STM_LMS_Options::get_option( 'course_tab_reviews', true );
				$rating = STM_LMS_Instructor::my_rating_v2( $user_data );
				?>
				<a
					<?php if ( $instructor_public ) { ?>
						href="<?php echo esc_url( $user_profile_url ); ?>"
					<?php } ?>
					class="stm_lms_instructors__single"
				>
					<div class="stm_lms_user_side">

						<?php if ( ! empty( $user_data['avatar'] ) ) : ?>
							<div class="stm-lms-user_avatar">
								<?php echo wp_kses_post( $user_data['avatar'] ); ?>
							</div>
						<?php endif; ?>

						<h3><?php echo esc_attr( $user_data['login'] ); ?></h3>

						<?php if ( ! empty( $user_data['meta']['position'] ) ) : ?>
							<h5><?php echo esc_html( sanitize_text_field( $user_data['meta']['position'] ) ); ?></h5>
						<?php endif; ?>

						<?php if ( ! empty( $rating['total'] ) && $reviews ) : ?>
							<div class="stm-lms-user_rating ">
								<div class="star-rating star-rating__big">
									<span style="width: <?php echo floatval( $rating['percent'] ); ?>%;"></span>
								</div>
								<strong class="rating heading_font"><?php echo floatval( $rating['average'] ); ?></strong>
								<div class="stm-lms-user_rating__total">
									<?php echo wp_kses_post( sanitize_text_field( $rating['total_marks'] ) ); ?>
								</div>
							</div>
						<?php endif; ?>

					</div>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
	else :
		?>
		<p class="stm_lms_instructors__no_results"><?php esc_html_e( 'No instructors found matching your filters. Try adjusting your filter criteria.', 'mkh-teacher-addon' ); ?></p>
		<?php
	endif;

	$html = ob_get_clean();

	// Debug: Log HTML output
	error_log( 'MKH Instructor Filter - HTML length: ' . strlen( $html ) );
	error_log( 'MKH Instructor Filter - HTML content: ' . $html );

	wp_send_json_success( array(
		'html' => $html,
		'count' => $instructor_count,
	) );
}
add_action( 'wp_ajax_mkh_instructor_filter', 'mkh_instructor_filter_ajax' );
add_action( 'wp_ajax_nopriv_mkh_instructor_filter', 'mkh_instructor_filter_ajax' );

/**
 * Localize script with AJAX URL and nonce
 */
function mkh_instructor_filter_localize_script() {
	if ( mkh_is_instructors_filter_page() ) {
		wp_localize_script( 'instructors-filter', 'instructorFilter', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'instructor_filter_nonce' ),
		) );
	}
}
add_action( 'wp_enqueue_scripts', 'mkh_instructor_filter_localize_script', 20 );
