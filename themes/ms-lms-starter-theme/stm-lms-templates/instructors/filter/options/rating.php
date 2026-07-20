<?php
/**
 * Rating Filter Option
 * 
 * @package MS_LMS_Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="stm_lms_instructors__filter_options_item">
	<div class="stm_lms_instructors__filter_options_item_title">
		<h3><?php esc_html_e( 'Feedback Rating', 'mkh-teacher-addon' ); ?></h3>
		<div class="stm_lms_instructors__filter_options_item_title_toggler"></div>
	</div>
	<div class="stm_lms_instructors__filter_options_item_content">
		<?php foreach ( $options as $rating ) : ?>
			<div class="stm_lms_instructors__filter_options_item_category">
				<label class="stm_lms_instructors__filter_options_item_radio">
					<span class="stm_lms_instructors__filter_options_item_radio_inner">
						<input type="radio" name="rating" value="<?php echo floatval( $rating['rate'] ); ?>" <?php checked( floatval( $rating['rate'] ) === $current_value ); ?>>
						<span class="stm_lms_instructors__filter_options_item_radio_fake"></span>
					</span>
					<span class="stm_lms_instructors__filter_options_item_radio_label"><?php echo esc_html( $rating['label'] ); ?></span>
				</label>
			</div>
		<?php endforeach; ?>
	</div>
</div>
