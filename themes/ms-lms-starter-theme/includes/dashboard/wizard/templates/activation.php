<div class="masterstudy-starter-wizard__wrapper-content">
	<div class="masterstudy-starter-wizard__activations">
		<div class="masterstudy-starter-wizard__title">
			<?php echo esc_html__( 'Do you have an activation key?', 'masterstudy_starter' ); ?>
		</div>
		<div class="masterstudy-starter-wizard__description">
			<?php echo esc_html__( 'If you have purchased an activation key from our site, please select "Yes." If you haven\'t, click "No" to explore our available templates.', 'masterstudy_starter' ); ?>
		</div>
		<div class="masterstudy-starter-wizard__button-box">
			<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-skip" data-template="plans">
				<?php echo esc_html__( 'No, i don’t have an activation key', 'masterstudy_starter' ); ?>
			</div>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=masterstudy-starter-freemius' ) ); ?>" class="masterstudy-starter-wizard__button">
				<?php echo esc_html__( 'Yes, i have an activation key', 'masterstudy_starter' ); ?>
			</a>
		</div>
	</div>
</div>
