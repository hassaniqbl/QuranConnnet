<?php

namespace MKH\TeacherAddon\Profile;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PersonalInformation {
	public static function render( int $user_id ): void {
		$nationality = SaveProfile::resolve_nationality_value( array(), $user_id );
		$timezone    = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_TIMEZONE );
		$languages   = SaveProfile::get_array_meta_value( $user_id, SaveProfile::META_LANGUAGES );
		$country     = (string) SaveProfile::get_country_value( $user_id );

		$languages_options  = SaveProfile::languages_options();
		$selected_languages = is_array( $languages ) ? $languages : array();

		?>
		<section class="mkh-teacher-addon__section">
			<div class="mkh-teacher-addon__section-head">
				<h2 class="masterstudy-account-settings__title mkh-teacher-addon__section-title"><?php echo esc_html__( 'Personal Information', 'mkh-teacher-addon' ); ?></h2>
				<p class="masterstudy-account-settings__field-desc"><?php echo esc_html__( 'Country stays synced with the native MasterStudy profile, while the fields below extend your instructor details.', 'mkh-teacher-addon' ); ?></p>
			</div>

			<div class="masterstudy-account-settings__fields mkh-teacher-addon__fields">
				<div class="masterstudy-account-settings__field masterstudy-account-settings__field_full">
					<div class="masterstudy-account-settings__field-wrapper">
						<label class="masterstudy-account-settings__field-label"><?php echo esc_html__( 'Country', 'mkh-teacher-addon' ); ?></label>
						<input
							class="masterstudy-account-settings__input"
							value="<?php echo esc_attr( $country ? $country : esc_html__( 'Already managed in MasterStudy profile', 'mkh-teacher-addon' ) ); ?>"
							disabled
						>
					</div>
				</div>

				<?php
					SaveProfile::render_select(
						array(
							'name'     => SaveProfile::META_NATIONALITY,
							'id'       => SaveProfile::META_NATIONALITY,
							'label'    => esc_html__( 'Nationality', 'mkh-teacher-addon' ),
							'value'    => $nationality,
							'options'  => SaveProfile::nationality_options(),
							'required' => true,
						)
					);

					SaveProfile::render_select(
						array(
							'name'     => SaveProfile::META_TIMEZONE,
							'id'       => SaveProfile::META_TIMEZONE,
							'label'    => esc_html__( 'Time Zone', 'mkh-teacher-addon' ),
							'value'    => $timezone,
							'options'  => SaveProfile::timezone_options(),
							'required' => true,
						)
					);
					?>

				<div class="masterstudy-account-settings__field masterstudy-account-settings__field_full">
					<div class="masterstudy-account-settings__field-wrapper">
						<div class="masterstudy-account-settings__field-label d-block">
							<?php echo esc_html__( 'Languages Spoken', 'mkh-teacher-addon' ); ?>
							<?php echo SaveProfile::render_field_status( $user_id, SaveProfile::META_LANGUAGES, true ); ?>
						</div>

						<div class="d-flex flex-wrap gap-3 mt-2">
							<?php foreach ( $languages_options as $value => $label ) : ?>
								<?php
									$is_checked = in_array( (string) $value, array_map( 'strval', $selected_languages ), true );
									$input_id   = SaveProfile::META_LANGUAGES . '_' . sanitize_title( (string) $value );
									?>
								<div class="form-check mb-0">
									<input
										class="form-check-input"
										type="checkbox"
										id="<?php echo esc_attr( $input_id ); ?>"
										name="<?php echo esc_attr( SaveProfile::META_LANGUAGES ); ?>[]"
										value="<?php echo esc_attr( (string) $value ); ?>"
										<?php echo $is_checked ? 'checked' : ''; ?>
									>
									<label class="form-check-label" for="<?php echo esc_attr( $input_id ); ?>">
										<?php echo esc_html( (string) $label ); ?>
									</label>
								</div>
							<?php endforeach; ?>
						</div>

						<p class="masterstudy-account-settings__field-desc mt-2 mb-0">
							<?php echo esc_html__( 'Select at least one language.', 'mkh-teacher-addon' ); ?>
						</p>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}

