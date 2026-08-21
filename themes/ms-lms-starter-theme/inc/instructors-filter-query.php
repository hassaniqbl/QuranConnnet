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
			
			// Combine both skills and custom subjects, normalizing to array of values
			$all_subjects = array();
			
			// Process teaching skills - handle both string and array return formats
			if ( ! empty( $instructor_skills ) ) {
				if ( is_array( $instructor_skills ) ) {
					foreach ( $instructor_skills as $skill ) {
						$skill_value = is_array( $skill ) ? $skill['value'] : $skill;
						if ( ! empty( $skill_value ) ) {
							$all_subjects[] = $skill_value;
						}
					}
				} else {
					$all_subjects[] = $instructor_skills;
				}
			}
			
			// Process custom subjects - handle both string and array return formats
			if ( ! empty( $custom_subjects ) ) {
				if ( is_array( $custom_subjects ) ) {
					foreach ( $custom_subjects as $subject ) {
						$subject_value = is_array( $subject ) ? $subject['value'] : $subject;
						if ( ! empty( $subject_value ) ) {
							$all_subjects[] = $subject_value;
						}
					}
				} else {
					$all_subjects[] = $custom_subjects;
				}
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
			
			// Normalize languages to array of values
			$all_languages = array();
			if ( ! empty( $instructor_languages ) ) {
				if ( is_array( $instructor_languages ) ) {
					foreach ( $instructor_languages as $language ) {
						$language_value = is_array( $language ) ? $language['value'] : $language;
						if ( ! empty( $language_value ) ) {
							$all_languages[] = $language_value;
						}
					}
				} else {
					$all_languages[] = $instructor_languages;
				}
			}
			
			$has_matching_language = false;
			if ( ! empty( $all_languages ) ) {
				foreach ( $languages as $filter_language ) {
					if ( in_array( $filter_language, $all_languages, true ) ) {
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
	// Verify nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'instructor_filter_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
	}

	// Sanitize and get filter parameters
	$filters = array(
		'gender'   => isset( $_POST['gender'] ) ? sanitize_text_field( wp_unslash( $_POST['gender'] ) ) : '',
		'ijazah'   => isset( $_POST['ijazah'] ) ? (array) wp_unslash( $_POST['ijazah'] ) : array(),
		'subjects' => isset( $_POST['subjects'] ) ? (array) wp_unslash( $_POST['subjects'] ) : array(),
		'languages' => isset( $_POST['languages'] ) ? (array) wp_unslash( $_POST['languages'] ) : array(),
		'rate_min' => isset( $_POST['rate_min'] ) ? floatval( wp_unslash( $_POST['rate_min'] ) ) : '',
		'rate_max' => isset( $_POST['rate_max'] ) ? floatval( wp_unslash( $_POST['rate_max'] ) ) : '',
		'rating'   => isset( $_POST['rating'] ) ? floatval( wp_unslash( $_POST['rating'] ) ) : '',
		'country'  => isset( $_POST['country'] ) ? (array) wp_unslash( $_POST['country'] ) : array(),
		'timezone' => isset( $_POST['timezone'] ) ? (array) wp_unslash( $_POST['timezone'] ) : array(),
	);

	// Sanitize array values
	$filters['ijazah'] = array_map( 'sanitize_text_field', $filters['ijazah'] );
	$filters['subjects'] = array_map( 'sanitize_text_field', $filters['subjects'] );
	$filters['languages'] = array_map( 'sanitize_text_field', $filters['languages'] );
	$filters['country'] = array_map( 'sanitize_text_field', $filters['country'] );
	$filters['timezone'] = array_map( 'sanitize_text_field', $filters['timezone'] );

	// Get filtered instructors
	$instructors = mkh_get_filtered_instructors( $filters );
	$instructor_public = STM_LMS_Options::get_option( 'instructor_public_profile', true );
	$instructor_count = count( $instructors );

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
				
				// Get ACF fields for additional instructor information
				$about_teacher = get_field( 'about_teacher', 'user_' . $user->ID );
				$teaching_skills = get_field( 'teaching_skills', 'user_' . $user->ID );
				$languages = get_field( 'languages', 'user_' . $user->ID );
				$gender = get_field( 'mkh_gender', 'user_' . $user->ID );
				$timezone = get_field( 'mkh_timezone', 'user_' . $user->ID );
				$sect = get_field( 'sect', 'user_' . $user->ID );
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

						<div class="stm_lms_instructor_info">
							<h3><?php echo esc_attr( $user_data['login'] ); ?></h3>

							<?php if ( ! empty( $user_data['meta']['position'] ) ) : ?>
								<h5><?php echo esc_html( sanitize_text_field( $user_data['meta']['position'] ) ); ?></h5>
							<?php endif; ?>

							<?php if ( ! empty( $teaching_skills ) && is_array( $teaching_skills ) ) : ?>
								<div class="stm_lms_instructor_teaching">
									<span class="stm_lms_instructor_label"><?php esc_html_e( 'I Can Teach:', 'mkh-teacher-addon' ); ?></span>
									<span class="stm_lms_instructor_value">
										<?php
										$skill_labels = array();
										foreach ( $teaching_skills as $skill ) {
											$skill_value = is_array( $skill ) ? $skill['value'] : $skill;
											$skill_labels[] = mkh_get_skill_label( $skill_value );
										}
										echo esc_html( implode( ', ', $skill_labels ) );
										?>
									</span>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $about_teacher ) ) : ?>
								<div class="stm_lms_instructor_description">
									<span class="stm_lms_instructor_label"><?php esc_html_e( 'Introduction:', 'mkh-teacher-addon' ); ?></span>
									<span class="stm_lms_instructor_value"><?php echo esc_html( wp_trim_words( $about_teacher, 20, '...' ) ); ?></span>
								</div>
							<?php endif; ?>

							<div class="stm_lms_instructor_details">
								<?php if ( ! empty( $languages ) && is_array( $languages ) ) : ?>
									<div class="stm_lms_instructor_detail">
										<span class="stm_lms_instructor_label"><?php esc_html_e( 'Languages:', 'mkh-teacher-addon' ); ?></span>
										<span class="stm_lms_instructor_value">
											<?php
											$language_labels = array();
											foreach ( $languages as $language ) {
												$language_value = is_array( $language ) ? $language['value'] : $language;
												$language_labels[] = mkh_get_language_label( $language_value );
											}
											echo esc_html( implode( ', ', $language_labels ) );
											?>
										</span>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $gender ) ) : ?>
									<div class="stm_lms_instructor_detail">
										<span class="stm_lms_instructor_label"><?php esc_html_e( 'Gender:', 'mkh-teacher-addon' ); ?></span>
										<span class="stm_lms_instructor_value"><?php echo esc_html( mkh_get_gender_label( $gender ) ); ?></span>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $timezone ) ) : ?>
									<div class="stm_lms_instructor_detail">
										<span class="stm_lms_instructor_label"><?php esc_html_e( 'Time Zone:', 'mkh-teacher-addon' ); ?></span>
										<span class="stm_lms_instructor_value"><?php echo esc_html( $timezone ); ?></span>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $sect ) ) : ?>
									<div class="stm_lms_instructor_detail">
										<span class="stm_lms_instructor_label"><?php esc_html_e( 'Sect:', 'mkh-teacher-addon' ); ?></span>
										<span class="stm_lms_instructor_value"><?php echo esc_html( $sect ); ?></span>
									</div>
								<?php endif; ?>
							</div>

							<?php if ( ! empty( $rating['total'] ) && $reviews ) : ?>
								<div class="stm-lms-user_rating">
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

	wp_send_json_success( array(
		'html' => $html,
		'count' => $instructor_count,
	) );
}
add_action( 'wp_ajax_mkh_instructor_filter', 'mkh_instructor_filter_ajax' );
add_action( 'wp_ajax_nopriv_mkh_instructor_filter', 'mkh_instructor_filter_ajax' );
