<?php
/**
 * Instructors Grid with Filter
 * 
 * Modified instructors grid that includes sidebar filter
 * 
 * @package MS_LMS_Starter_Theme
 */

stm_lms_register_style( 'user' );
stm_lms_register_style( 'instructors_grid' );

// Get filtered instructors
$instructors = mkh_get_filtered_instructors();
$instructor_public = STM_LMS_Options::get_option( 'instructor_public_profile', true );
$instructor_count = count( $instructors );

if ( ! empty( $instructors ) ) : ?>
	<div class="stm_lms_instructors_with_filter_wrapper">
		<div class="stm_lms_instructors_with_filter">
			<?php
			// Include filter sidebar
			STM_LMS_Templates::show_lms_template( 'instructors/filter/main' );
			?>
			
			<div class="stm_lms_instructors_with_filter_content">
				<div class="stm_lms_instructors__header">
					<h2><?php esc_html_e( 'Instructors', 'masterstudy-lms-learning-management-system' ); ?></h2>
					<span class="stm_lms_instructors__count">
						<?php printf( esc_html__( '%d instructors found', 'mkh-teacher-addon' ), intval( $instructor_count ) ); ?>
					</span>
				</div>
				
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
			</div>
		</div>
	</div>
<?php else : ?>
	<div class="stm_lms_instructors_with_filter_wrapper">
		<div class="stm_lms_instructors_with_filter">
			<?php
			// Include filter sidebar even when no results
			STM_LMS_Templates::show_lms_template( 'instructors/filter/main' );
			?>
			
			<div class="stm_lms_instructors_with_filter_content">
				<div class="stm_lms_instructors__header">
					<h2><?php esc_html_e( 'Instructors', 'masterstudy-lms-learning-management-system' ); ?></h2>
					<span class="stm_lms_instructors__count">
						<?php esc_html_e( '0 instructors found', 'mkh-teacher-addon' ); ?>
					</span>
				</div>
				<p class="stm_lms_instructors__no_results"><?php esc_html_e( 'No instructors found matching your filters. Try adjusting your filter criteria.', 'mkh-teacher-addon' ); ?></p>
			</div>
		</div>
	</div>
<?php endif; ?>
