<?php

namespace MKH\TeacherAddon\Profile;

use MKH\TeacherAddon\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Profile {
	public function register( Plugin $plugin ): void {
		$plugin->hooks()->add_action( 'stm_lms_before_profile_buttons_all', array( $this, 'render_frontend_sections' ), 20 );
		$plugin->hooks()->add_action( 'show_user_profile', array( $this, 'render_admin_sections' ) );
		$plugin->hooks()->add_action( 'edit_user_profile', array( $this, 'render_admin_sections' ) );
		$plugin->hooks()->add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ), 40 );
		$plugin->hooks()->add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ), 40 );
		$plugin->hooks()->add_filter( 'stm_lms_current_user_data', array( $this, 'filter_current_user_data' ), 20, 1 );
		$plugin->hooks()->add_filter( 'stm_lms_user_additional_fields', array( $this, 'filter_user_additional_fields' ), 20, 1 );

		SaveProfile::register( $plugin );
	}

	public function enqueue_frontend_assets(): void {
		if ( ! $this->is_frontend_profile_screen() ) {
			return;
		}

		mkh_teacher_addon()->assets()->enqueue_profile();
		wp_enqueue_style( 'mkh-teacher-addon' );

		$profile_fields_inline = sprintf(
			'window.profileForm = window.profileForm || []; window.profileForm = window.profileForm.concat(%1$s);',
			wp_json_encode( SaveProfile::profile_form_fields() )
		);
		wp_add_inline_script( 'masterstudy-account-settings', $profile_fields_inline, 'after' );

		wp_localize_script(
			'mkh-teacher-addon-profile',
			'mkhTeacherAddonProfile',
			array(
				'userId'            => get_current_user_id(),
				'rootSelector'      => '#mkh-teacher-addon-profile',
				'actionsSelector'   => '.masterstudy-account-settings__actions',
				'saveButtonSelector' => '[data-id="masterstudy-account-settings-save"]',
				'profileFields'     => SaveProfile::profile_form_fields(),
				'strings'           => array(
					'validationFailed' => esc_html__( 'Please correct the highlighted fields.', 'mkh-teacher-addon' ),
					'videoConflict'    => esc_html__( 'Choose only one video platform at a time.', 'mkh-teacher-addon' ),
					'saveHint'         => esc_html__( 'Use the native Save changes button to store your profile.', 'mkh-teacher-addon' ),
				),
			)
		);
	}

	public function enqueue_admin_assets(): void {
		if ( ! $this->is_admin_profile_screen() ) {
			return;
		}

		wp_enqueue_style( 'mkh-teacher-addon-profile' );
	}

	/**
	 * @param array<string, mixed> $current_user
	 * @return array<string, mixed>
	 */
	public function filter_current_user_data( array $current_user ): array {
		$user_id = absint( $current_user['id'] ?? 0 );
		if ( empty( $user_id ) ) {
			return $current_user;
		}

		if ( empty( $current_user['meta'] ) || ! is_array( $current_user['meta'] ) ) {
			$current_user['meta'] = array();
		}

		$current_user['meta'][ SaveProfile::META_COUNTRY ]       = SaveProfile::get_country_value( $user_id );
		$current_user['meta'][ SaveProfile::META_NATIONALITY ]   = SaveProfile::resolve_nationality_value( $current_user['meta'], $user_id );
		$current_user['meta'][ SaveProfile::META_TIMEZONE ]      = SaveProfile::get_meta_value( $user_id, SaveProfile::META_TIMEZONE );
		$current_user['meta'][ SaveProfile::META_LANGUAGES ]     = SaveProfile::get_array_meta_value( $user_id, SaveProfile::META_LANGUAGES );
		$current_user['meta'][ SaveProfile::META_HEADLINE ]      = SaveProfile::get_meta_value( $user_id, SaveProfile::META_HEADLINE );
		$current_user['meta'][ SaveProfile::META_EXPERIENCE ]    = SaveProfile::get_meta_value( $user_id, SaveProfile::META_EXPERIENCE );
		$current_user['meta'][ SaveProfile::META_QUALIFICATION ] = SaveProfile::get_meta_value( $user_id, SaveProfile::META_QUALIFICATION );
		$current_user['meta'][ SaveProfile::META_INSTITUTE ]     = SaveProfile::get_meta_value( $user_id, SaveProfile::META_INSTITUTE );
		$current_user['meta'][ SaveProfile::META_GRADUATION_YEAR ] = SaveProfile::get_meta_value( $user_id, SaveProfile::META_GRADUATION_YEAR );
		$current_user['meta'][ SaveProfile::META_YOUTUBE ]       = SaveProfile::get_meta_value( $user_id, SaveProfile::META_YOUTUBE );
		$current_user['meta'][ SaveProfile::META_VIMEO ]         = SaveProfile::get_meta_value( $user_id, SaveProfile::META_VIMEO );
		$current_user['meta'][ SaveProfile::META_FREE_DEMO ]     = SaveProfile::get_meta_value( $user_id, SaveProfile::META_FREE_DEMO );
		$current_user['meta'][ SaveProfile::META_DEMO_DURATION ]  = SaveProfile::get_meta_value( $user_id, SaveProfile::META_DEMO_DURATION );
		$current_user['meta'][ SaveProfile::META_MONTHLY_PRICE ] = SaveProfile::get_meta_value( $user_id, SaveProfile::META_MONTHLY_PRICE );
		$current_user['meta']['whatsapp_number']                 = (string) get_user_meta( $user_id, 'whatsapp_number', true );
		$current_user['meta']['gender']                          = (string) get_user_meta( $user_id, 'gender', true );
		$current_user['meta']['first_name']                      = (string) get_user_meta( $user_id, 'first_name', true );
		$current_user['meta']['last_name']                       = (string) get_user_meta( $user_id, 'last_name', true );
		$current_user['mkh_profile_completion']                   = SaveProfile::profile_completion_schema( $user_id );

		return $current_user;
	}

	/**
	 * @param array<string, array<string, mixed>> $additional_fields
	 * @return array<string, array<string, mixed>>
	 */
	public function filter_user_additional_fields( array $additional_fields ): array {
		foreach ( SaveProfile::profile_form_fields() as $field ) {
			$additional_fields[ $field['id'] ] = array(
				'label'    => $field['label'] ?? $field['id'],
				'required' => ! empty( $field['required'] ),
			);
		}

		return $additional_fields;
	}

	public function render_frontend_sections( $current_user ): void {
		$user_id = absint( is_array( $current_user ) ? ( $current_user['id'] ?? 0 ) : 0 );
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		if ( empty( $user_id ) ) {
			return;
		}

		if ( class_exists( 'STM_LMS_Instructor' ) && method_exists( 'STM_LMS_Instructor', 'is_instructor' ) ) {
			if ( ! \STM_LMS_Instructor::is_instructor( $user_id ) && ! current_user_can( 'manage_options' ) ) {
				return;
			}
		}

		?>
		<div class="masterstudy-account-settings mkh-teacher-addon-profile" id="mkh-teacher-addon-profile" data-user-id="<?php echo esc_attr( (string) $user_id ); ?>">
			<div class="mkh-teacher-addon-profile__header">
				<h2 class="masterstudy-account-settings__title"><?php echo esc_html__( 'Muslim Kids Hub Teacher Details', 'mkh-teacher-addon' ); ?></h2>
				<p class="masterstudy-account-settings__field-desc"><?php echo esc_html__( 'These sections extend the native MasterStudy instructor profile. The built-in Bio/About field remains the primary long-form bio.', 'mkh-teacher-addon' ); ?></p>
			</div>
			<?php
			PersonalInformation::render( $user_id );
			ProfessionalInformation::render( $user_id );
			Qualification::render( $user_id );
			IntroVideo::render( $user_id );
			DemoClass::render( $user_id );
			Pricing::render( $user_id );
			?>
			<div class="alert d-none" data-mkh-status></div>
		</div>
		<?php
	}

	public function render_admin_sections( \WP_User $user ): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		Qualification::render_admin_certificates( (int) $user->ID );
	}

	private function is_frontend_profile_screen(): bool {
		if ( is_admin() ) {
			return false;
		}

		if ( ! function_exists( 'get_option' ) ) {
			return false;
		}

		$settings = get_option( 'stm_lms_settings', array() );
		$user_url = absint( $settings['user_url'] ?? 0 );
		if ( empty( $user_url ) || ! is_singular() ) {
			return false;
		}

		return get_queried_object_id() === $user_url;
	}

	private function is_admin_profile_screen(): bool {
		if ( ! is_admin() ) {
			return false;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( empty( $screen ) ) {
			return false;
		}

		return in_array( $screen->id, array( 'profile', 'user-edit' ), true );
	}
}
