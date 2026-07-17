<?php

namespace MKH\TeacherAddon\Profile;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class DemoClass {
	public static function render( int $user_id ): void {
		$free_demo     = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_FREE_DEMO );
		$demo_duration = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_DEMO_DURATION );
		?>
		<section class="mkh-teacher-addon__section">
			<div class="mkh-teacher-addon__section-head">
				<h2 class="masterstudy-account-settings__title mkh-teacher-addon__section-title"><?php echo esc_html__( 'Demo Class', 'mkh-teacher-addon' ); ?></h2>
				<p class="masterstudy-account-settings__field-desc"><?php echo esc_html__( 'Let families know whether you offer a free demo class and how long it lasts.', 'mkh-teacher-addon' ); ?></p>
			</div>

			<div class="masterstudy-account-settings__fields mkh-teacher-addon__fields">
				<?php
				SaveProfile::render_radio_group(
					array(
						'name'     => SaveProfile::META_FREE_DEMO,
						'id'       => SaveProfile::META_FREE_DEMO,
						'label'    => esc_html__( 'Offer Free Demo', 'mkh-teacher-addon' ),
						'value'    => $free_demo,
						'options'  => array(
							'yes' => esc_html__( 'Yes', 'mkh-teacher-addon' ),
							'no'  => esc_html__( 'No', 'mkh-teacher-addon' ),
						),
						'required' => true,
						'wrapper'  => 'masterstudy-account-settings__field_full',
					)
				);
				SaveProfile::render_select(
					array(
						'name'     => SaveProfile::META_DEMO_DURATION,
						'id'       => SaveProfile::META_DEMO_DURATION,
						'label'    => esc_html__( 'Demo Duration', 'mkh-teacher-addon' ),
						'value'    => $demo_duration,
						'options'  => SaveProfile::demo_duration_options(),
						'required' => true,
					)
				);
				?>
			</div>
		</section>
		<?php
	}
}
