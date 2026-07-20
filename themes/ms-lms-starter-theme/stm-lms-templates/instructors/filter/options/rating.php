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
			<label class="stm_lms_instructors__filter_options_item_category">
				<span class="stm_lms_instructors__filter_options_item_radio">
					<input type="radio" name="rating" value="<?php echo floatval( $rating['rate'] ); ?>" <?php checked( floatval( $rating['rate'] ) === $current_value ); ?>>
					<span class="stm_lms_instructors__filter_options_item_radio_fake"></span>
				</span>
				<div class="stm_lms_instructors__filter_options_item_rating">
					<div class="stm_lms_instructors__filter_options_item_rating_stars">
						<div class="stm_lms_instructors__filter_options_item_rating_stars_filled" style="width: <?php echo esc_attr( round( $rating['rate'] * 100 / 5, 2 ) ); ?>%;"></div>
					</div>
					<div class="stm_lms_instructors__filter_options_item_rating_quantity">
						<span><?php echo esc_html( $rating['label'] ); ?></span>
					</div>
				</div>
			</label>
		<?php endforeach; ?>
	</div>
</div>
