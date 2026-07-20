<?php
/**
 * Languages Filter Option
 * 
 * @package MS_LMS_Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="stm_lms_instructors__filter_options_item">
	<div class="stm_lms_instructors__filter_options_item_title">
		<h3><?php esc_html_e( 'Spoken Languages', 'mkh-teacher-addon' ); ?></h3>
		<div class="stm_lms_instructors__filter_options_item_title_toggler"></div>
	</div>
	<div class="stm_lms_instructors__filter_options_item_content">
		<?php foreach ( $options as $value => $label ) : ?>
			<div class="stm_lms_instructors__filter_options_item_category">
				<label class="stm_lms_instructors__filter_options_item_checkbox">
					<span class="stm_lms_instructors__filter_options_item_checkbox_inner">
						<input type="checkbox" name="languages[]" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $current_values, true ) ); ?>>
						<span><i class="stmlms-check-3"></i></span>
					</span>
					<span class="stm_lms_instructors__filter_options_item_checkbox_label"><?php echo esc_html( $label ); ?></span>
				</label>
			</div>
		<?php endforeach; ?>
	</div>
</div>
