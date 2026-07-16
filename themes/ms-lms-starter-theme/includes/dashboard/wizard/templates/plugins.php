<div class="masterstudy-starter-wizard__wrapper-content">
	<div class="masterstudy-starter-wizard__plugins">
		<div class="masterstudy-starter-wizard__title">
			<?php echo esc_html__( 'Install plugins', 'masterstudy_starter' ); ?>
		</div>
		<ul>
		<?php
		$plugins = apply_filters( 'masterstudy_starter_theme_plugins', array() );

		foreach ( $plugins as $plugin ) :
			?>
			<li
				class="masterstudy-starter-wizard__plugin for-<?php echo esc_attr( $plugin['for_builder'] ); ?> 
				<?php echo ( 'Activated' === $plugin['description'] ) ? 'masterstudy-starter-wizard__plugin-loaded' : ''; ?>"
				data-plugin="<?php echo esc_attr( $plugin['slug'] ); ?>"
				data-status="<?php echo esc_attr( $plugin['description'] ); ?>"
			>
				<div class="masterstudy-starter-wizard__plugin-image">
					<img src="<?php echo esc_url( $plugin['image'] ); ?>" alt="<?php echo esc_html( $plugin['title'] ); ?>">
				</div>
				<div class="masterstudy-starter-wizard__plugin-info">
					<div class="masterstudy-starter-wizard__plugin-info__title"><?php echo esc_html( $plugin['title'] ); ?></div>
					<div class="masterstudy-starter-wizard__plugin-info__description"><?php echo esc_html( $plugin['description'] ); ?></div>
				</div>
				<div class="masterstudy-starter-wizard__plugin-indicator"><span class="ms-lms-icon-check"></span></div>
			</li>
		<?php endforeach; ?>
		</ul>
		<div class="masterstudy-starter-wizard__button-box">
			<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-skip" data-template="<?php echo esc_attr( 'demo-content' ); ?>">
				<?php echo esc_html__( 'Skip', 'masterstudy_starter' ); ?>
			</div>
			<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-install">
				<?php echo esc_html__( 'Install and activate', 'masterstudy_starter' ); ?>
			</div>
			<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-next" data-template="<?php echo esc_attr( 'demo-content' ); ?>">
				<?php echo esc_html__( 'Continue', 'masterstudy_starter' ); ?>
			</div>
		</div>
	</div>
</div>
