<?php

namespace MKH\TeacherAddon\Profile;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class IntroVideo {
	public static function render( int $user_id ): void {
		$youtube = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_YOUTUBE );
		$vimeo   = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_VIMEO );
		?>
		<section class="mkh-teacher-addon__section">
			<div class="mkh-teacher-addon__section-head">
				<h2 class="masterstudy-account-settings__title mkh-teacher-addon__section-title"><?php echo esc_html__( 'Introduction Video', 'mkh-teacher-addon' ); ?></h2>
				<p class="masterstudy-account-settings__field-desc"><?php echo esc_html__( 'Add either a YouTube or Vimeo link to introduce yourself.', 'mkh-teacher-addon' ); ?></p>
			</div>

			<div class="masterstudy-account-settings__fields mkh-teacher-addon__fields">
				<?php
				SaveProfile::render_input(
					array(
						'type'        => 'url',
						'name'        => SaveProfile::META_YOUTUBE,
						'id'          => SaveProfile::META_YOUTUBE,
						'label'       => esc_html__( 'YouTube URL', 'mkh-teacher-addon' ),
						'value'       => $youtube,
						'placeholder' => esc_html__( 'https://www.youtube.com/watch?v=...', 'mkh-teacher-addon' ),
						'required'    => false,
					)
				);
				SaveProfile::render_input(
					array(
						'type'        => 'url',
						'name'        => SaveProfile::META_VIMEO,
						'id'          => SaveProfile::META_VIMEO,
						'label'       => esc_html__( 'Vimeo URL', 'mkh-teacher-addon' ),
						'value'       => $vimeo,
						'placeholder' => esc_html__( 'https://vimeo.com/123456789', 'mkh-teacher-addon' ),
						'required'    => false,
					)
				);
				?>
				<div class="masterstudy-account-settings__field masterstudy-account-settings__field_full">
					<div class="masterstudy-account-settings__field-wrapper">
						<div class="mkh-teacher-addon__video-preview ratio ratio-16x9" data-mkh-video-preview>
							<div class="d-flex align-items-center justify-content-center text-muted">
								<?php echo esc_html__( 'Video preview will appear here after saving.', 'mkh-teacher-addon' ); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
