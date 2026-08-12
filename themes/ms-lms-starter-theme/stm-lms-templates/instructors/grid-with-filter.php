<?php
/**
 * Instructors Grid with Filter
 * 
 * Modified instructors grid that includes sidebar filter
 * 
 * @package MS_LMS_Starter_Theme
 */

// Get filtered instructors
$instructors = mkh_get_filtered_instructors();
$instructor_public = STM_LMS_Options::get_option( 'instructor_public_profile', true );
$instructor_count = count( $instructors );

if ( ! empty( $instructors ) ) : ?>
	<div class="stm_lms_instructors_wrapper">
			<div class="stm_lms_instructors__header">
					<h2><?php esc_html_e( 'Teachers', 'masterstudy-lms-learning-management-system' ); ?></h2>
					<span class="stm_lms_instructors__count">
						<?php printf( esc_html__( '%d teachers found', 'mkh-teacher-addon' ), intval( $instructor_count ) ); ?>
					</span>
				</div>
		<div class="stm_lms_instructors__archive_wrapper">
			
			<?php
			// Include filter sidebar
			STM_LMS_Templates::show_lms_template( 'instructors/filter/main' );
			?>
			
			<div class="stm_lms_instructors stm_lms_instructors__archive filter_enabled">
				
					
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
							
							// SAMPLE DATA FOR DEMONSTRATION - Remove in production
							if ( empty( $about_teacher ) ) {
								$about_teacher = 'Short description about the instructor and their teaching experience. Passionate educator dedicated to helping students achieve their goals.';
							}
							if ( empty( $teaching_skills ) ) {
								$teaching_skills = array( 
									array( 'value' => 'quran', 'label' => 'Quran' ),
									array( 'value' => 'arabic', 'label' => 'Arabic' ),
									array( 'value' => 'islamic_studies', 'label' => 'Islamic Studies' )
								);
							}
							if ( empty( $languages ) ) {
								$languages = array(
									array( 'value' => 'arabic', 'label' => 'Arabic' ),
									array( 'value' => 'english', 'label' => 'English' )
								);
							}
							if ( empty( $gender ) ) {
								$gender = 'female';
							}
							if ( empty( $timezone ) ) {
								$timezone = 'Africa/Cairo';
							}
							if ( empty( $sect ) ) {
								$sect = 'Sunni';
							}
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
				</div>
			</div>
		</div>
	<?php else : ?>
		<div class="stm_lms_instructors_wrapper">
			<div class="stm_lms_instructors__archive_wrapper">
				<?php
				// Include filter sidebar even when no results
				STM_LMS_Templates::show_lms_template( 'instructors/filter/main' );
				?>
				
				<div class="stm_lms_instructors stm_lms_instructors__archive filter_enabled">
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
