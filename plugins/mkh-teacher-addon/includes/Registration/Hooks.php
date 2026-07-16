<?php

namespace MKH\TeacherAddon\Registration;

use MKH\TeacherAddon\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Hooks {
	private const EMAIL_VERIFY_ACTION = 'mkh_teacher_addon_verify_email';

	public static function register( Plugin $plugin ): void {
		$plugin->hooks()->add_action( 'wp_ajax_stm_lms_register', array( __CLASS__, 'validate_before_core' ), 1 );
		$plugin->hooks()->add_action( 'wp_ajax_nopriv_stm_lms_register', array( __CLASS__, 'validate_before_core' ), 1 );
		$plugin->hooks()->add_action( 'stm_lms_after_user_register', array( __CLASS__, 'store_registration_meta' ), 20, 2 );
		$plugin->hooks()->add_filter( 'stm_lms_login', array( __CLASS__, 'filter_login_response' ), 20, 1 );
		$plugin->hooks()->add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ), 30 );
		$plugin->hooks()->add_action( 'admin_post_' . self::EMAIL_VERIFY_ACTION, array( __CLASS__, 'verify_email' ) );
		$plugin->hooks()->add_action( 'admin_post_nopriv_' . self::EMAIL_VERIFY_ACTION, array( __CLASS__, 'verify_email' ) );
	}

	public static function validate_before_core(): void {
		$data = self::get_request_data();
		if ( empty( $data ) ) {
			return;
		}

		$errors = Validation::validate( $data );
		if ( ! empty( $errors ) ) {
			wp_send_json(
				array(
					'status' => 'error',
					'errors' => $errors,
				)
			);
		}
	}

	/**
	 * @param int $user_id
	 * @param array<string, mixed> $data
	 */
	public static function store_registration_meta( $user_id, $data ): void {
		$user_id = absint( $user_id );
		if ( empty( $user_id ) || ! is_array( $data ) ) {
			return;
		}

		UserMeta::init_registration_meta( $user_id, $data );

		$premoderation = (bool) ( \STM_LMS_Options::get_option( 'user_premoderation', false ) ?? false );
		$activation_flow = $premoderation && ! empty( $_GET['user_token'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( $activation_flow ) {
			UserMeta::mark_email_verified( $user_id );
			return;
		}

		self::send_verification_email( $user_id );
	}

	/**
	 * @param array<string, mixed> $response
	 * @return array<string, mixed>
	 */
	public static function filter_login_response( array $response ): array {
		$user_id = get_current_user_id();
		if ( empty( $user_id ) ) {
			return apply_filters( 'mkh_teacher_addon_login_response', $response, $user_id );
		}

		$redirect = apply_filters(
			'mkh_teacher_addon_login_redirect',
			$response['user_page'] ?? '',
			$user_id,
			$response
		);

		if ( ! empty( $redirect ) ) {
			$response['user_page'] = esc_url_raw( $redirect );
		}

		return apply_filters( 'mkh_teacher_addon_login_response', $response, $user_id );
	}

	public static function enqueue_assets(): void {
		if ( is_admin() || ! function_exists( 'mkh_teacher_addon' ) ) {
			return;
		}

		if ( ! mkh_teacher_addon_is_registration_screen() ) {
			return;
		}

		mkh_teacher_addon()->assets()->enqueue();

		wp_localize_script(
			'mkh-teacher-addon',
			'mkhTeacherAddon',
			array(
				'termsLabel'      => esc_html__( 'I agree to the Terms & Conditions and Privacy Policy.', 'mkh-teacher-addon' ),
				'termsVersion'    => mkh_teacher_addon_terms_version(),
				'fields'          => self::get_registration_fields(),
				'genderOptions'   => self::gender_options(),
				'buttonSelectors'  => array(
					'register' => '[data-id="masterstudy-authorization-register-button"]',
				),
			)
		);
	}

	public static function verify_email(): void {
		$user_id = absint( $_GET['user_id'] ?? 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$token   = sanitize_text_field( wp_unslash( $_GET['token'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$redirect_url = self::get_login_redirect_url();
		if ( empty( $user_id ) || empty( $token ) ) {
			wp_safe_redirect( add_query_arg( 'mkh_verification', 'invalid', $redirect_url ) );
			exit;
		}

		$stored_hash = UserMeta::get_email_verification_hash( $user_id );
		if ( empty( $stored_hash ) ) {
			wp_safe_redirect( add_query_arg( 'mkh_verification', 'expired', $redirect_url ) );
			exit;
		}

		$check_hash = hash_hmac( 'sha256', $token, wp_salt( 'auth' ) );
		if ( ! hash_equals( $stored_hash, $check_hash ) ) {
			wp_safe_redirect( add_query_arg( 'mkh_verification', 'invalid', $redirect_url ) );
			exit;
		}

		UserMeta::mark_email_verified( $user_id );
		wp_safe_redirect( add_query_arg( 'mkh_verification', 'success', $redirect_url ) );
		exit;
	}

	/**
	 * @return array<string, array<string, mixed>>
	 */
	private static function get_registration_fields(): array {
		return array(
			'first_name'      => array(
				'slug'        => 'first_name',
				'label'       => esc_html__( 'First Name', 'mkh-teacher-addon' ),
				'placeholder'  => esc_html__( 'Enter first name', 'mkh-teacher-addon' ),
				'required'    => true,
				'type'        => 'text',
			),
			'last_name'       => array(
				'slug'        => 'last_name',
				'label'       => esc_html__( 'Last Name', 'mkh-teacher-addon' ),
				'placeholder'  => esc_html__( 'Enter last name', 'mkh-teacher-addon' ),
				'required'    => true,
				'type'        => 'text',
			),
			'country'         => array(
				'slug'        => 'country',
				'label'       => esc_html__( 'Country', 'mkh-teacher-addon' ),
				'placeholder'  => esc_html__( 'Enter country', 'mkh-teacher-addon' ),
				'required'    => true,
				'type'        => 'text',
			),
			'whatsapp_number'  => array(
				'slug'        => 'whatsapp_number',
				'label'       => esc_html__( 'WhatsApp Number', 'mkh-teacher-addon' ),
				'placeholder'  => esc_html__( 'Enter WhatsApp number', 'mkh-teacher-addon' ),
				'required'    => true,
				'type'        => 'tel',
			),
			'gender'          => array(
				'slug'        => 'gender',
				'label'       => esc_html__( 'Gender', 'mkh-teacher-addon' ),
				'placeholder'  => esc_html__( 'Select gender', 'mkh-teacher-addon' ),
				'required'    => true,
				'type'        => 'select',
			),
			'referral_code'   => array(
				'slug'        => 'referral_code',
				'label'       => esc_html__( 'Referral Code', 'mkh-teacher-addon' ),
				'placeholder'  => esc_html__( 'Enter referral code', 'mkh-teacher-addon' ),
				'required'    => false,
				'type'        => 'text',
			),
		);
	}

	/**
	 * @return array<int, array{value:string,label:string}>
	 */
	private static function gender_options(): array {
		return array(
			array(
				'value' => 'male',
				'label' => esc_html__( 'Male', 'mkh-teacher-addon' ),
			),
			array(
				'value' => 'female',
				'label' => esc_html__( 'Female', 'mkh-teacher-addon' ),
			),
			array(
				'value' => 'other',
				'label' => esc_html__( 'Other', 'mkh-teacher-addon' ),
			),
			array(
				'value' => 'prefer_not_to_say',
				'label' => esc_html__( 'Prefer not to say', 'mkh-teacher-addon' ),
			),
		);
	}

	private static function send_verification_email( int $user_id ): void {
		$user = get_userdata( $user_id );
		if ( ! $user || empty( $user->user_email ) ) {
			return;
		}

		$token      = wp_generate_password( 48, false, false );
		$token_hash = hash_hmac( 'sha256', $token, wp_salt( 'auth' ) );
		UserMeta::mark_email_verification_requested( $user_id, $token_hash );

		$verify_url = add_query_arg(
			array(
				'action'  => self::EMAIL_VERIFY_ACTION,
				'user_id' => $user_id,
				'token'   => $token,
			),
			admin_url( 'admin-post.php' )
		);

		$subject = esc_html__( 'Verify your email address', 'mkh-teacher-addon' );
		$message = sprintf(
			'<p>%1$s</p><p><a href="%2$s">%2$s</a></p>',
			esc_html__( 'Please verify your email address to continue using your account.', 'mkh-teacher-addon' ),
			esc_url( $verify_url )
		);

		wp_mail(
			$user->user_email,
			$subject,
			wp_kses_post( $message ),
			array( 'Content-Type: text/html; charset=UTF-8' )
		);
	}

	/**
	 * @return array<string, mixed>
	 */
	private static function get_request_data(): array {
		$raw = file_get_contents( 'php://input' );
		if ( false === $raw || '' === trim( $raw ) ) {
			return array();
		}

		$data = json_decode( $raw, true );
		return is_array( $data ) ? $data : array();
	}

	private static function get_login_redirect_url(): string {
		if ( class_exists( 'STM_LMS_User' ) && method_exists( 'STM_LMS_User', 'login_page_url' ) ) {
			return \STM_LMS_User::login_page_url();
		}

		return wp_login_url();
	}
}
