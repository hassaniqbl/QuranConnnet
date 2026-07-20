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
 * Get filtered instructors based on URL parameters
 * 
 * @return array Instructors with filter applied
 */
function mkh_get_filtered_instructors() {
	// Get filter parameters
	$gender = isset( $_GET['gender'] ) ? sanitize_text_field( wp_unslash( $_GET['gender'] ) ) : '';
	$ijazah = isset( $_GET['ijazah'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['ijazah'] ) ) : array();
	$subjects = isset( $_GET['subjects'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['subjects'] ) ) : array();
	$languages = isset( $_GET['languages'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['languages'] ) ) : array();
	$rate_min = isset( $_GET['rate_min'] ) ? floatval( wp_unslash( $_GET['rate_min'] ) ) : '';
	$rate_max = isset( $_GET['rate_max'] ) ? floatval( wp_unslash( $_GET['rate_max'] ) ) : '';
	$rating = isset( $_GET['rating'] ) ? floatval( wp_unslash( $_GET['rating'] ) ) : '';
	$country = isset( $_GET['country'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['country'] ) ) : array();
	$timezone = isset( $_GET['timezone'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['timezone'] ) ) : array();

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
