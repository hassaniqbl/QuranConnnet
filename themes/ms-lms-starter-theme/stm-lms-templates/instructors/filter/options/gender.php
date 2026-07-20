<?php
/**
 * Gender Filter Option
 * 
 * @package MS_LMS_Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="stm_lms_instructors__filter_options_item">
	<div class="stm_lms_instructors__filter_options_item_title">
		<h3><?php esc_html_e( 'Gender', 'mkh-teacher-addon' ); ?></h3>
		<div class="stm_lms_instructors__filter_options_item_title_toggler"></div>
	</div>
	<div class="stm_lms_instructors__filter_options_item_content">
		<?php foreach ( $options as $value => $label ) : ?>
			<div class="stm_lms_instructors__filter_options_item_category">
				<label class="stm_lms_instructors__filter_options_item_radio">
					<span class="stm_lms_instructors__filter_options_item_radio_inner">
						<input type="radio" name="gender" value="<?php echo esc_attr( $value ); ?>" <?php checked( $current_value === $value ); ?>>
						<span class="stm_lms_instructors__filter_options_item_radio_fake"></span>
					</span>
					<span class="stm_lms_instructors__filter_options_item_radio_label"><?php echo esc_html( $label ); ?></span>
				</label>
			</div>
		<?php endforeach; ?>
	</div>
</div>
