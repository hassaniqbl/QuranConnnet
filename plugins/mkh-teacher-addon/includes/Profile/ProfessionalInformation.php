<?php

namespace MKH\TeacherAddon\Profile;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class ProfessionalInformation {
	public static function render( int $user_id ): void {
		$headline   = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_HEADLINE );
		$experience = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_EXPERIENCE );
		?>
		<section class="mkh-teacher-addon__section">
			<div class="mkh-teacher-addon__section-head">
				<h2 class="masterstudy-account-settings__title mkh-teacher-addon__section-title"><?php echo esc_html__( 'Professional Information', 'mkh-teacher-addon' ); ?></h2>
				<p class="masterstudy-account-settings__field-desc"><?php echo esc_html__( 'Use the native MasterStudy Bio/About field above for your long-form bio. This section covers your headline and teaching experience.', 'mkh-teacher-addon' ); ?></p>
			</div>

			<div class="masterstudy-account-settings__fields mkh-teacher-addon__fields">
				<?php
				SaveProfile::render_input(
					array(
						'type'        => 'text',
						'name'        => SaveProfile::META_HEADLINE,
						'id'          => SaveProfile::META_HEADLINE,
						'label'       => esc_html__( 'Professional Headline', 'mkh-teacher-addon' ),
						'value'       => $headline,
						'placeholder' => esc_html__( 'Certified Quran & Tajweed Teacher', 'mkh-teacher-addon' ),
						'required'    => true,
						'max_length'  => 120,
						'wrapper'     => 'masterstudy-account-settings__field_full',
					)
				);
				SaveProfile::render_select(
					array(
						'name'     => SaveProfile::META_EXPERIENCE,
						'id'       => SaveProfile::META_EXPERIENCE,
						'label'    => esc_html__( 'Years of Teaching Experience', 'mkh-teacher-addon' ),
						'value'    => $experience,
						'options'  => SaveProfile::experience_options(),
						'required' => true,
					)
				);
				?>
			</div>
		</section>
		<?php
	}
}
