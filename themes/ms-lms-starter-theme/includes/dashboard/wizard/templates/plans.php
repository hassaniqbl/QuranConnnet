<?php
	$price = getAnnualPriceFromAPI();
?>
<div class="masterstudy-starter-wizard__wrapper-content">
	<div class="masterstudy-starter-wizard__plans">
		<div class="masterstudy-starter-wizard__title">
			<?php echo esc_html__( 'Select plan', 'masterstudy_starter' ); ?>
		</div>
		<div class="masterstudy-starter-wizard__price-box">
			<div class="masterstudy-starter-wizard__price-button selected-price annual">
				<small></small>
				<?php
				echo sprintf(
					'<strong>%s</strong> <span>%s / %s</span>',
					esc_html__( 'Annual', 'masterstudy_starter' ),
					wp_kses_post( $price['annual_price'] ),
					esc_html__( 'year', 'masterstudy_starter' ),
				);
				?>
			</div>
			<div class="masterstudy-starter-wizard__price-button lifetime">
				<small></small>
				<?php
				echo sprintf(
					'<strong>%s</strong> <span>%s</span>',
					esc_html__( 'Lifetime', 'masterstudy_starter' ),
					wp_kses_post( $price['lifetime_price'] ),
				);
				?>
			</div>
			<div class="masterstudy-starter-wizard__button masterstudy-starter-wizard__button-freemius">
				<?php echo esc_html__( 'Continue', 'masterstudy_starter' ); ?>
			</div>
		</div>
	</div>
</div>
