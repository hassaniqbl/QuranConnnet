<div class="masterstudy-starter-wizard__wrapper-content">
	<div class="masterstudy-starter-wizard__demos">
		<div class="masterstudy-starter-wizard__title">
			<?php echo esc_html__( 'Import demo content', 'masterstudy_starter' ); ?>
		</div>
		<?php
			$stm_lms_settings = get_option( 'stm_lms_settings' );
			$builder          = get_option( 'ms-lms-starter-theme-builder' );

			$demos = array(
				array(
					'title'       => 'Demo Content',
					'slug'        => 'demo-content',
					'description' => __( 'Importing content like course samples, pages, lessons, and quizzes.', 'masterstudy_starter' ),
					'for_builder' => 'all',
				),
				array(
					'title'       => 'Theme Settings',
					'slug'        => 'theme-settings',
					'description' => __( 'Uploading preset settings like colors, logos, and fonts.', 'masterstudy_starter' ),
					'for_builder' => 'all',
				),
			);

			if ( ! isset( $stm_lms_settings['courses_view'] ) && 'gutenberg' !== $builder ) {
				$demos[] = array(
					'title'       => 'LMS Options',
					'slug'        => 'lms-options',
					'description' => __( 'Adding custom design options, checkouts, currency, and others.', 'masterstudy_starter' ),
					'for_builder' => 'all',
				);
			}
			?>
		<ul>
			<?php foreach ( $demos as $demo ) : ?>
			<li class="masterstudy-starter-wizard__demo for-<?php echo esc_attr( $demo['for_builder'] ); ?>" data-demo="<?php echo esc_attr( $demo['slug'] ); ?>">
				<div class="masterstudy-starter-wizard__demo-checkbox">
					<label>
						<span class="demo-checkbox" data-checked="true"><span class="ms-lms-icon-check"></span></span><?php echo esc_html( $demo['title'] ); ?>
					</label>
					<div class="masterstudy-starter-wizard__demo-checkbox-content"><?php echo esc_html( $demo['description'] ); ?></div>
				</div>
				<div class="masterstudy-starter-wizard__demo-indicator"><span class="ms-lms-icon-check"></span></div>
			</li>
			<?php endforeach; ?>
		</ul>
		<div class="masterstudy-starter-wizard__button-box">
			<div class="masterstudy-starter-wizard__button-message">
				<?php echo esc_html__( 'An unexpected error occurred. Please try again.', 'masterstudy_starter' ); ?>
			</div>
			<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-install-demo">
				<?php echo esc_html__( 'Install', 'masterstudy_starter' ); ?>
			</div>
			<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-next" data-template="<?php echo esc_attr( 'child-theme' ); ?>">
				<?php echo esc_html__( 'Continue', 'masterstudy_starter' ); ?>
			</div>
		</div>
	</div>
</div>
