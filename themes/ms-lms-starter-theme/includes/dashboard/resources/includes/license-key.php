<?php

masterstudy_starter_fs()->add_action( 'connect/after', 'masterstudy_starter_activation_info' );

function masterstudy_starter_activation_info() {
	?>
	<div class="masterstudy-starter-accordion">
		<div class="masterstudy-starter-accordion-header active">
			<span><?php echo esc_html__( 'How to find a license key if I purchased the template through the WordPress admin dashboard?', 'starter-text-domain' ); ?></span>
			<span class="ms-lms-icon-arrow-top"></span>
		</div>
		<div class="masterstudy-starter-accordion-content active">
			<?php echo esc_html__( 'When purchasing the templates from the admin dashboard, you get an email with the account details and license key from Freemius. You can copy and paste it here.', 'starter-text-domain' ); ?>
		</div>

		<div class="masterstudy-starter-accordion-header">
			<span><?php echo esc_html__( 'I bought templates from your website, how can I find my license key?', 'starter-text-domain' ); ?></span>
			<span class="ms-lms-icon-arrow-top"></span>
		</div>
		<div class="masterstudy-starter-accordion-content">
			<?php echo esc_html__( 'If you purchased the templates on our website, you\'ll receive an email with detail from Freemius. Open it and copy the license key to add here.', 'starter-text-domain' ); ?>
		</div>

		<div class="masterstudy-starter-accordion-header">
			<span><?php echo esc_html__( 'I haven\'t received an email with the license, what should i do?', 'starter-text-domain' ); ?></span>
			<span class="ms-lms-icon-arrow-top"></span>
		</div>
		<div class="masterstudy-starter-accordion-content">
			<?php echo esc_html__( 'We recommend checking if the email you entered is correct or looking through the spam folder in your email inbox.', 'starter-text-domain' ); ?>
		</div>

		<div class="masterstudy-starter-accordion-header">
			<span><?php echo esc_html__( 'I have more questions, how to get answers to them?', 'starter-text-domain' ); ?></span>
			<span class="ms-lms-icon-arrow-top"></span>
		</div>
		<div class="masterstudy-starter-accordion-content">
			<?php echo esc_html__( 'For more questions, you can send your inquires to the chatbot on our website and our support team will get back to you as soon as possible.', 'starter-text-domain' ); ?>
		</div>
	</div>
	<?php
}
