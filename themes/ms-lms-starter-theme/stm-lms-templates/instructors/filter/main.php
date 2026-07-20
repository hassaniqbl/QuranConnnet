<?php
/**
 * Instructors Filter Main Template
 * 
 * Sidebar filter for instructors listing page
 * Based on courses filter structure
 * 
 * @package MS_LMS_Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get current filter values from URL parameters
$current_filters = array(
	'gender'       => isset( $_GET['gender'] ) ? sanitize_text_field( wp_unslash( $_GET['gender'] ) ) : '',
	'ijazah'       => isset( $_GET['ijazah'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['ijazah'] ) ) : array(),
	'subjects'     => isset( $_GET['subjects'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['subjects'] ) ) : array(),
	'languages'    => isset( $_GET['languages'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['languages'] ) ) : array(),
	'rate_min'     => isset( $_GET['rate_min'] ) ? floatval( wp_unslash( $_GET['rate_min'] ) ) : '',
	'rate_max'     => isset( $_GET['rate_max'] ) ? floatval( wp_unslash( $_GET['rate_max'] ) ) : '',
	'rating'       => isset( $_GET['rating'] ) ? floatval( wp_unslash( $_GET['rating'] ) ) : '',
	'country'      => isset( $_GET['country'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['country'] ) ) : array(),
	'timezone'     => isset( $_GET['timezone'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_GET['timezone'] ) ) : array(),
);

// Get available filter values from instructor profiles
$filter_data = mkh_get_instructors_filter_data();
?>

<div class="stm_lms_instructors__filter_wrapper">
	<div class="stm_lms_instructors__filter">
		<a href="#" class="stm_lms_instructors__filter_toggle">
			<?php esc_html_e( 'Filters', 'masterstudy-lms-learning-management-system' ); ?>
		</a>
		<form class="stm_lms_instructors__filter_form" method="get" action="<?php echo esc_url( remove_query_arg( array_keys( $current_filters ) ) ); ?>">
			<div class="stm_lms_instructors__filter_options">
				<?php
				// Gender Filter
				STM_LMS_Templates::show_lms_template(
					'instructors/filter/options/gender',
					array(
						'current_value' => $current_filters['gender'],
						'options'       => $filter_data['gender'],
					)
				);

				// Ijazah Filter
				if ( ! empty( $filter_data['ijazah'] ) ) {
					STM_LMS_Templates::show_lms_template(
						'instructors/filter/options/ijazah',
						array(
							'current_values' => $current_filters['ijazah'],
							'options'        => $filter_data['ijazah'],
						)
					);
				}

				// Subjects Filter (Teaching Skills)
				if ( ! empty( $filter_data['subjects'] ) ) {
					STM_LMS_Templates::show_lms_template(
						'instructors/filter/options/subjects',
						array(
							'current_values' => $current_filters['subjects'],
							'options'        => $filter_data['subjects'],
						)
					);
				}

				// Languages Filter
				if ( ! empty( $filter_data['languages'] ) ) {
					STM_LMS_Templates::show_lms_template(
						'instructors/filter/options/languages',
						array(
							'current_values' => $current_filters['languages'],
							'options'        => $filter_data['languages'],
						)
					);
				}

				// Hourly Rate Filter
				STM_LMS_Templates::show_lms_template(
					'instructors/filter/options/hourly_rate',
					array(
						'rate_min'      => $current_filters['rate_min'],
						'rate_max'      => $current_filters['rate_max'],
						'min_rate'      => $filter_data['min_rate'],
						'max_rate'      => $filter_data['max_rate'],
					)
				);

				// Rating Filter
				STM_LMS_Templates::show_lms_template(
					'instructors/filter/options/rating',
					array(
						'current_value' => $current_filters['rating'],
						'options'       => $filter_data['ratings'],
					)
				);

				// Country Filter
				if ( ! empty( $filter_data['countries'] ) ) {
					STM_LMS_Templates::show_lms_template(
						'instructors/filter/options/country',
						array(
							'current_values' => $current_filters['country'],
							'options'        => $filter_data['countries'],
						)
					);
				}

				// Timezone Filter
				if ( ! empty( $filter_data['timezones'] ) ) {
					STM_LMS_Templates::show_lms_template(
						'instructors/filter/options/timezone',
						array(
							'current_values' => $current_filters['timezone'],
							'options'        => $filter_data['timezones'],
						)
					);
				}
				?>
			</div>
			<div class="stm_lms_instructors__filter_actions">
				<input type="submit" value="<?php esc_attr_e( 'Show Results', 'masterstudy-lms-learning-management-system' ); ?>" class="stm_lms_instructors__filter_actions_button">
				<a href="<?php echo esc_url( remove_query_arg( array_keys( $current_filters ) ) ); ?>" class="stm_lms_instructors__filter_actions_reset">
					<i class="stmlms-undo2"></i>
					<span><?php esc_html_e( 'Reset All', 'masterstudy-lms-learning-management-system' ); ?></span>
				</a>
			</div>
		</form>
	</div>
</div>
