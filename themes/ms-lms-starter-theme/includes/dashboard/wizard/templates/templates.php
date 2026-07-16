
<div class="masterstudy-starter-wizard__wrapper-header">
	<div class="masterstudy-starter-wizard__wrapper-header__title">
		<?php echo esc_html__( 'Select template', 'masterstudy_starter' ); ?>
	</div>
	<?php if ( ! masterstudy_starter_fs_verify() ) : ?>
	<div class="masterstudy-starter-wizard__wrapper-header__price masterstudy-starter-wizard__button-freemius" data-plan="annual">
		<?php
		echo esc_html__( 'All Premium templates just for', 'masterstudy_starter' );

		$annualPrice = getAnnualPriceFromAPI();

		if ( is_array( $annualPrice ) && isset( $annualPrice['annual_price'] ) ) {
			echo wp_kses_post( $annualPrice['annual_price'] );
		}
		?>
	</div>
	<?php endif; ?>
	<div class="masterstudy-starter-wizard__wrapper-header__builders">
		<span class="masterstudy-starter-wizard__builder-elementor active"><?php echo esc_html__( 'Elementor', 'masterstudy_starter' ); ?></span>
		<span class="masterstudy-starter-wizard__builder-gutenberg"><?php echo esc_html__( 'Gutenberg', 'masterstudy_starter' ); ?></span>
	</div>
</div>
<div class="masterstudy-starter-wizard__wrapper-content">
	<?php
		$templates = array(
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-free.jpg',
				'status'      => 'Free',
				'title'       => 'Starter',
				'slug'        => 'demo_1',
				'builder'     => 'elementor',
				'old_builder' => 'elementor-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-creators.jpg',
				'status'      => 'Premium',
				'title'       => 'Creators',
				'slug'        => 'demo_7',
				'builder'     => 'elementor',
				'old_builder' => 'elementor-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/creators/',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-marketing.jpg',
				'status'      => 'Premium',
				'title'       => 'Marketing',
				'slug'        => 'demo_2',
				'builder'     => 'elementor',
				'old_builder' => 'elementor-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/template-marketing',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-university.jpg',
				'status'      => 'Premium',
				'title'       => 'University',
				'slug'        => 'demo_3',
				'builder'     => 'elementor',
				'old_builder' => 'elementor-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/template-university',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-art.jpg',
				'status'      => 'Premium',
				'title'       => 'Art',
				'slug'        => 'demo_4',
				'builder'     => 'elementor',
				'old_builder' => 'elementor-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/template-art',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-coaching.jpg',
				'status'      => 'Premium',
				'title'       => 'Coaching',
				'slug'        => 'demo_5',
				'builder'     => 'elementor',
				'old_builder' => 'elementor-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/template-coaching/',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-technology.jpg',
				'status'      => 'Premium',
				'title'       => 'Technology',
				'slug'        => 'demo_6',
				'builder'     => 'elementor',
				'old_builder' => 'elementor-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/template-technology/',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-free.jpg',
				'status'      => 'Free',
				'title'       => 'Starter',
				'slug'        => 'gutenberg_demo_1',
				'builder'     => 'gutenberg',
				'old_builder' => 'gutenberg-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-marketing.jpg',
				'status'      => 'Premium',
				'title'       => 'Coming Soon',
				'slug'        => '#',
				'builder'     => 'gutenberg',
				'old_builder' => 'gutenberg-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/template-marketing',
				'demo_status' => 'pending',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-university.jpg',
				'status'      => 'Premium',
				'title'       => 'Coming Soon',
				'slug'        => '#',
				'builder'     => 'gutenberg',
				'old_builder' => 'gutenberg-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/template-university',
				'demo_status' => 'pending',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-art.jpg',
				'status'      => 'Premium',
				'title'       => 'Coming Soon',
				'slug'        => '#',
				'builder'     => 'gutenberg',
				'old_builder' => 'gutenberg-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/template-art',
				'demo_status' => 'pending',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-coaching.jpg',
				'status'      => 'Premium',
				'title'       => 'Coming Soon',
				'slug'        => '#',
				'builder'     => 'gutenberg',
				'old_builder' => 'gutenberg-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/template-coaching',
				'demo_status' => 'pending',
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/template-technology.jpg',
				'status'      => 'Premium',
				'title'       => 'Coming Soon',
				'slug'        => '#',
				'builder'     => 'gutenberg',
				'old_builder' => 'gutenberg-builder',
				'preview'     => 'https://masterstudy.stylemixthemes.com/lms-plugin/template-technology',
				'demo_status' => 'pending',
			),
		);
		?>
	<ul class="masterstudy-starter-wizard__templates">
	<?php
	foreach ( $templates as $template ) :
		$activated_demo = get_option( 'masterstudy_starter_demo_activated' );
		$active_builder = get_option( 'ms-lms-starter-theme-builder' );

		$is_installed = ( $activated_demo === $template['slug'] ) ||
						( empty( $activated_demo ) &&
						'Starter' === $template['title'] &&
						$active_builder === $template['old_builder'] );
		?>
		<li class="masterstudy-starter-wizard__template-<?php echo esc_attr( $template['builder'] ); ?>">
			<div class="masterstudy-starter-wizard__template
			<?php
			echo esc_attr( $is_installed ? 'installed' : '' );
			echo esc_attr( ! empty( $template['demo_status'] ) ? 'pending' : '' );
			?>
			">
				<div class="masterstudy-starter-wizard__template-image">
					<div class="masterstudy-starter-wizard__template-status <?php if ( 'Premium' === $template['status'] ) : ?>
						premium
					<?php endif; ?>">
						<?php echo esc_attr( $template['status'] ); ?>
					</div>
					<img src="<?php echo esc_url( $template['image'] ); ?>" width="" height="" alt="<?php echo esc_attr( $template['title'] ); ?>">
					<a href="<?php echo esc_url( $template['preview'] ); ?>" class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-preview" target="_blank"><?php echo esc_html__( 'Live preview', 'masterstudy_starter' ); ?></a>
					<div class="masterstudy-starter-wizard__template-installed"><span class="ms-lms-icon-check"></span> <?php echo esc_html__( 'Already installed', 'masterstudy_starter' ); ?></div>
				</div>
				<div class="masterstudy-starter-wizard__template-title"><?php echo esc_html( $template['title'] ); ?></div>
				<?php if ( 'Premium' === $template['status'] && ! masterstudy_starter_fs_verify() ) : ?>
				<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-activate-freemius" data-template="activation">
					<?php echo esc_html__( 'Activate Premium', 'masterstudy_starter' ); ?>
				</div>
				<?php else : ?>
				<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-continue <?php echo ( ! empty( get_option( 'masterstudy_starter_demo_activated' ) ) ) ? 'demo-activated' : ''; ?>" data-template="<?php echo esc_attr( 'plugins' ); ?>" data-builder="<?php echo esc_attr( $template['builder'] ); ?>" data-demo="<?php echo esc_attr( $template['slug'] ); ?>">
					<?php echo esc_html__( 'Continue', 'masterstudy_starter' ); ?>
				</div>
				<?php endif; ?>
				<div class="masterstudy-starter-wizard__template-message">
					<?php echo esc_html__( 'You can change template later', 'masterstudy_starter' ); ?>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
	<div style="display: none;" class="masterstudy-starter-wizard__template-popup">
		<div class="masterstudy-starter-wizard__template-popup__content">
			<span class="masterstudy-starter-wizard__button-close"></span>
			<h2><?php echo esc_html__( 'Your current theme and settings will be reset', 'masterstudy_starter' ); ?></h2>
			<p><?php echo esc_html__( 'All pages, usernames, passwords, users, and settings will be reset. Make sure to back up your site before proceeding. This action is irreversible.', 'masterstudy_starter' ); ?></p>
			<p><?php echo esc_html__( 'The following items will be removed:', 'masterstudy_starter' ); ?></p>
			<ul>
				<li>Home Page</li>
				<li>Courses</li>
				<li>There will be news</li>
				<li>Menu</li>
				<li>Demo Images</li>
			</ul>
			<div class="masterstudy-starter-wizard__demo-checkbox">
				<label><span class="demo-checkbox" data-checked="false"><span class="ms-lms-icon-check"></span></span><?php echo esc_html__( 'I understand that these changes cannot be undone', 'masterstudy_starter' ); ?></label>
			</div>
			<div class="masterstudy-starter-wizard__progress-wrap">
				<div class="masterstudy-starter-wizard__progress-bar">
					<div class="masterstudy-starter-wizard__progress-bar-fill"></div>
				</div>
			</div>
			<div class="masterstudy-starter-wizard__button-box">
				<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-skip" data-template="<?php echo esc_attr( 'plugins' ); ?>">
					<?php echo esc_html__( 'Skip', 'masterstudy_starter' ); ?>
				</div>
				<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-reset disabled">
					<?php echo esc_html__( 'Continue anyway & Reset', 'masterstudy_starter' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
