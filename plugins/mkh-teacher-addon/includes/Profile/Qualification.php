<?php

namespace MKH\TeacherAddon\Profile;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Qualification {
	public static function render( int $user_id ): void {
		$qualification   = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_QUALIFICATION );
		$institute       = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_INSTITUTE );
		$graduation_year = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_GRADUATION_YEAR );
		?>
		<section class="mkh-teacher-addon__section">
			<div class="mkh-teacher-addon__section-head">
				<h2 class="masterstudy-account-settings__title mkh-teacher-addon__section-title"><?php echo esc_html__( 'Qualifications', 'mkh-teacher-addon' ); ?></h2>
				<p class="masterstudy-account-settings__field-desc"><?php echo esc_html__( 'Add your highest qualification and formal training details.', 'mkh-teacher-addon' ); ?></p>
			</div>

			<div class="masterstudy-account-settings__fields mkh-teacher-addon__fields">
				<?php
				SaveProfile::render_select(
					array(
						'name'     => SaveProfile::META_QUALIFICATION,
						'id'       => SaveProfile::META_QUALIFICATION,
						'label'    => esc_html__( 'Highest Qualification', 'mkh-teacher-addon' ),
						'value'    => $qualification,
						'options'  => SaveProfile::qualification_options(),
						'required' => true,
					)
				);
				SaveProfile::render_input(
					array(
						'type'        => 'text',
						'name'        => SaveProfile::META_INSTITUTE,
						'id'          => SaveProfile::META_INSTITUTE,
						'label'       => esc_html__( 'Institute Name', 'mkh-teacher-addon' ),
						'value'       => $institute,
						'placeholder' => esc_html__( 'Optional', 'mkh-teacher-addon' ),
						'required'    => false,
					)
				);
				SaveProfile::render_select(
					array(
						'name'     => SaveProfile::META_GRADUATION_YEAR,
						'id'       => SaveProfile::META_GRADUATION_YEAR,
						'label'    => esc_html__( 'Graduation Year', 'mkh-teacher-addon' ),
						'value'    => $graduation_year,
						'options'  => SaveProfile::graduation_year_options(),
						'required' => false,
					)
				);
				?>
			</div>
		</section>
		<?php
	}

	public static function render_admin_certificates( int $user_id ): void {
		?>
		<section class="mkh-teacher-addon__section">
			<div class="mkh-teacher-addon__section-head">
				<h2 class="masterstudy-account-settings__title mkh-teacher-addon__section-title"><?php echo esc_html__( 'Certificates', 'mkh-teacher-addon' ); ?></h2>
				<p class="masterstudy-account-settings__field-desc"><?php echo esc_html__( 'Visible to administrators only. Files are stored in the WordPress Media Library.', 'mkh-teacher-addon' ); ?></p>
			</div>

			<?php SaveProfile::render_certificate_list( $user_id ); ?>
			<?php SaveProfile::render_certificate_upload( $user_id ); ?>
		</section>
		<?php
	}
}
