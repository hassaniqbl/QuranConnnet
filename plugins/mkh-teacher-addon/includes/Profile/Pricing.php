<?php

namespace MKH\TeacherAddon\Profile;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Pricing {
	public static function render( int $user_id ): void {
		$price = (string) SaveProfile::get_meta_value( $user_id, SaveProfile::META_MONTHLY_PRICE );
		?>
		<section class="mkh-teacher-addon__section">
			<div class="mkh-teacher-addon__section-head">
				<h2 class="masterstudy-account-settings__title mkh-teacher-addon__section-title"><?php echo esc_html__( 'Pricing', 'mkh-teacher-addon' ); ?></h2>
				<p class="masterstudy-account-settings__field-desc"><?php echo esc_html__( 'Set your monthly package price. Currency follows the active MasterStudy or WordPress configuration.', 'mkh-teacher-addon' ); ?></p>
			</div>

			<div class="masterstudy-account-settings__fields mkh-teacher-addon__fields">
				<div class="masterstudy-account-settings__field masterstudy-account-settings__field_full">
					<div class="masterstudy-account-settings__field-wrapper">
						<label for="<?php echo esc_attr( SaveProfile::META_MONTHLY_PRICE ); ?>" class="masterstudy-account-settings__field-label">
							<?php echo esc_html__( 'Monthly Package Price', 'mkh-teacher-addon' ); ?>
							<?php echo SaveProfile::render_field_status( $user_id, SaveProfile::META_MONTHLY_PRICE, true ); ?>
						</label>
						<div class="input-group">
							<span class="input-group-text"><?php echo esc_html( SaveProfile::currency_symbol() ); ?></span>
							<input
								type="number"
								step="0.01"
								min="0"
								name="<?php echo esc_attr( SaveProfile::META_MONTHLY_PRICE ); ?>"
								id="<?php echo esc_attr( SaveProfile::META_MONTHLY_PRICE ); ?>"
								class="masterstudy-account-settings__input"
								value="<?php echo esc_attr( $price ); ?>"
								placeholder="0.00"
								required
							>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
}
