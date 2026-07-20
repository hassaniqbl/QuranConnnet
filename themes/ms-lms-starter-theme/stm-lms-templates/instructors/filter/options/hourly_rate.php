<?php
/**
 * Hourly Rate Filter Option
 * 
 * @package MS_LMS_Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rate_min = ! empty( $rate_min ) ? $rate_min : 0;
$rate_max = ! empty( $rate_max ) ? $rate_max : 100;
$current_min = ! empty( $rate_min ) ? $rate_min : $min_rate;
$current_max = ! empty( $rate_max ) ? $rate_max : $max_rate;
?>

<div class="stm_lms_instructors__filter_options_item">
	<div class="stm_lms_instructors__filter_options_item_title">
		<h3><?php esc_html_e( 'Hourly Rate', 'mkh-teacher-addon' ); ?></h3>
		<div class="stm_lms_instructors__filter_options_item_title_toggler"></div>
	</div>
	<div class="stm_lms_instructors__filter_options_item_content">
		<div class="stm_lms_instructors__filter_options_item_price_range">
			<div class="stm_lms_instructors__filter_options_item_price_inputs">
				<div class="stm_lms_instructors__filter_options_item_price_input">
					<label for="rate_min"><?php esc_html_e( 'Min', 'mkh-teacher-addon' ); ?></label>
					<input type="number" name="rate_min" id="rate_min" value="<?php echo esc_attr( $current_min ); ?>" min="<?php echo esc_attr( $min_rate ); ?>" max="<?php echo esc_attr( $max_rate ); ?>" step="1">
				</div>
				<div class="stm_lms_instructors__filter_options_item_price_input">
					<label for="rate_max"><?php esc_html_e( 'Max', 'mkh-teacher-addon' ); ?></label>
					<input type="number" name="rate_max" id="rate_max" value="<?php echo esc_attr( $current_max ); ?>" min="<?php echo esc_attr( $min_rate ); ?>" max="<?php echo esc_attr( $max_rate ); ?>" step="1">
				</div>
			</div>
		</div>
	</div>
</div>
