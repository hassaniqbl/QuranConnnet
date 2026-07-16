<div class="masterstudy-starter-wizard__wrapper-content">
	<div class="masterstudy-starter-wizard__child-theme">
		<div class="masterstudy-starter-wizard__title">
			<?php echo esc_html__( 'Install child theme', 'masterstudy_starter' ); ?>
		</div>
		<?php
		$items = array(
			array(
				'icon'        => 'ms-lms-icon-safety',
				'title'       => 'Update Safety',
				'description' => 'Changes in a child theme are not lost when the parent theme is updated.',
			),
			array(
				'icon'        => 'ms-lms-icon-settings',
				'title'       => 'Easy Customization',
				'description' => "Customize appearance and functionality without altering the parent theme's code.",
			),
			array(
				'icon'        => 'ms-lms-icon-code',
				'title'       => 'Original Code Preservation',
				'description' => 'The parent theme remains unchanged, allowing easy reversion if needed.',
			),
			array(
				'icon'        => 'ms-lms-icon-debug',
				'title'       => 'Simplified Debugging',
				'description' => 'Errors are easier to find and fix since changes are localized in the child theme.',
			),
			array(
				'icon'        => 'ms-lms-icon-practices',
				'title'       => 'Best Practices',
				'description' => 'Using child themes ensures flexibility, reliability, and adherence to WordPress development best practices.',
			),
		);
		?>
		<ul>
			<?php foreach ( $items as $item ) : ?>
			<li class="masterstudy-starter-wizard__child-theme__icon-box">
				<div class="masterstudy-starter-wizard__child-theme__icon-box__icon">
					<span class="<?php echo esc_attr( $item['icon'] ); ?>"></span>
				</div>
				<div class="masterstudy-starter-wizard__child-theme__icon-box__info">
					<div class="masterstudy-starter-wizard__child-theme__icon-box__info-title"><?php echo esc_html( $item['title'] ); ?></div>
					<div class="masterstudy-starter-wizard__child-theme__icon-box__info-description"><?php echo esc_html( $item['description'] ); ?></div>
				</div>
			</li>
			<?php endforeach; ?>
		</ul>
		<div class="masterstudy-starter-wizard__progress-wrap">
			<div class="masterstudy-starter-wizard__progress-bar">
				<div class="masterstudy-starter-wizard__progress-bar-fill"></div>
			</div>
		</div>
		<div class="masterstudy-starter-wizard__button-box">
			<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-skip" data-template="<?php echo esc_attr( 'finish' ); ?>">
				<?php echo esc_html__( 'Skip', 'masterstudy_starter' ); ?>
			</div>
			<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-install-child">
				<?php echo esc_html__( 'Install', 'masterstudy_starter' ); ?>
			</div>
			<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-next" data-template="<?php echo esc_attr( 'finish' ); ?>">
				<?php echo esc_html__( 'Continue', 'masterstudy_starter' ); ?>
			</div>
		</div>
	</div>
</div>
