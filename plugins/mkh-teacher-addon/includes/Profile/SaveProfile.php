<?php

namespace MKH\TeacherAddon\Profile;

use MKH\TeacherAddon\Core\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SaveProfile {
	public const META_COUNTRY         = 'country';
	public const META_NATIONALITY     = 'mkh_nationality';
	public const META_TIMEZONE        = 'mkh_timezone';
	public const META_LANGUAGES       = 'mkh_languages_spoken';
	public const META_HEADLINE        = 'mkh_professional_headline';
	public const META_EXPERIENCE      = 'mkh_teaching_experience_years';
	public const META_QUALIFICATION   = 'mkh_highest_qualification';
	public const META_INSTITUTE       = 'mkh_institute_name';
	public const META_GRADUATION_YEAR = 'mkh_graduation_year';
	public const META_CERTIFICATES    = 'mkh_certificate_ids';
	public const META_YOUTUBE         = 'mkh_intro_video_youtube';
	public const META_VIMEO           = 'mkh_intro_video_vimeo';
	public const META_FREE_DEMO       = 'mkh_offer_free_demo';
	public const META_DEMO_DURATION   = 'mkh_demo_duration';
	public const META_MONTHLY_PRICE   = 'mkh_monthly_package_price';
	public const META_PROFILE_COMPLETION = 'mkh_profile_completion_schema';

	public static function register( Plugin $plugin ): void {
		$plugin->hooks()->add_action( 'wp_ajax_stm_lms_save_user_info', array( __CLASS__, 'pre_save_masterstudy_profile' ), 5 );
		$plugin->hooks()->add_action( 'edit_user_profile_update', array( __CLASS__, 'save_admin_profile' ) );
	}

	/**
	 * @return array<string, string>
	 */
	public static function country_options(): array {
		if ( function_exists( 'masterstudy_lms_get_countries' ) ) {
			$countries = masterstudy_lms_get_countries( false );
			$options   = array();

			foreach ( (array) $countries as $country ) {
				$code = (string) ( $country['code'] ?? '' );
				$name = (string) ( $country['name'] ?? $code );
				if ( '' !== $code ) {
					$options[ $code ] = $name;
				}
			}

			if ( ! empty( $options ) ) {
				return $options;
			}
		}

		return array(
			'PK' => 'Pakistan',
			'SA' => 'Saudi Arabia',
			'AE' => 'United Arab Emirates',
			'US' => 'United States',
		);
	}

	/**
	 * @return array<string, string>
	 */
	public static function nationality_options(): array {
		return self::country_options();
	}

	/**
	 * @return array<string, string>
	 */
	public static function timezone_options(): array {
		$options = array();

		foreach ( timezone_identifiers_list() as $zone ) {
			$options[ $zone ] = $zone;
		}

		return $options;
	}

	/**
	 * @return array<string, string>
	 */
	public static function languages_options(): array {
		return array(
			'English' => 'English',
			'Arabic'  => 'Arabic',
			'Urdu'    => 'Urdu',
			'Hindi'   => 'Hindi',
			'French'  => 'French',
			'Turkish' => 'Turkish',
			'Punjabi' => 'Punjabi',
		);
	}

	/**
	 * @return array<string, string>
	 */
	public static function qualification_options(): array {
		return array(
			'Alim'     => 'Alim',
			'Hafiz'    => 'Hafiz',
			'Qari'     => 'Qari',
			'Mufti'    => 'Mufti',
			'Bachelor' => 'Bachelor',
			'Master'   => 'Master',
			'Other'    => 'Other',
		);
	}

	/**
	 * @return array<string, string>
	 */
	public static function experience_options(): array {
		$options = array();
		for ( $index = 0; $index <= 30; $index++ ) {
			$options[ (string) $index ] = (string) $index;
		}

		$options['30+'] = '30+';

		return $options;
	}

	/**
	 * @return array<string, string>
	 */
	public static function graduation_year_options(): array {
		$current_year = (int) gmdate( 'Y' );
		$options      = array( '' => esc_html__( 'Select year', 'mkh-teacher-addon' ) );

		for ( $year = $current_year; $year >= 1950; $year-- ) {
			$options[ (string) $year ] = (string) $year;
		}

		return $options;
	}

	/**
	 * @return array<string, string>
	 */
	public static function demo_duration_options(): array {
		return array(
			'15' => esc_html__( '15 Minutes', 'mkh-teacher-addon' ),
			'20' => esc_html__( '20 Minutes', 'mkh-teacher-addon' ),
			'30' => esc_html__( '30 Minutes', 'mkh-teacher-addon' ),
			'45' => esc_html__( '45 Minutes', 'mkh-teacher-addon' ),
			'60' => esc_html__( '60 Minutes', 'mkh-teacher-addon' ),
		);
	}

	public static function currency_symbol(): string {
		if ( class_exists( 'STM_LMS_Helpers' ) && method_exists( 'STM_LMS_Helpers', 'get_currency' ) ) {
			return (string) \STM_LMS_Helpers::get_currency();
		}

		if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {
			return (string) get_woocommerce_currency_symbol( (string) get_option( 'woocommerce_currency', 'USD' ) );
		}

		return (string) get_option( 'woocommerce_currency', 'USD' );
	}

	/**
	 * @return array<int, array<string, mixed>>
	 */
	public static function profile_form_fields(): array {
		return array(
			array(
				'id'       => self::META_NATIONALITY,
				'slug'     => self::META_NATIONALITY,
				'type'     => 'select',
				'required' => true,
			),
			array(
				'id'       => self::META_TIMEZONE,
				'slug'     => self::META_TIMEZONE,
				'type'     => 'select',
				'required' => true,
			),
			array(
				'id'       => self::META_LANGUAGES,
				'slug'     => self::META_LANGUAGES . '[]',
				'type'     => 'select',
				'required' => true,
				'multiple' => true,
			),
			array(
				'id'       => self::META_HEADLINE,
				'slug'     => self::META_HEADLINE,
				'type'     => 'text',
				'required' => true,
			),
			array(
				'id'       => self::META_EXPERIENCE,
				'slug'     => self::META_EXPERIENCE,
				'type'     => 'select',
				'required' => true,
			),
			array(
				'id'       => self::META_QUALIFICATION,
				'slug'     => self::META_QUALIFICATION,
				'type'     => 'select',
				'required' => true,
			),
			array(
				'id'       => self::META_INSTITUTE,
				'slug'     => self::META_INSTITUTE,
				'type'     => 'text',
				'required' => false,
			),
			array(
				'id'       => self::META_GRADUATION_YEAR,
				'slug'     => self::META_GRADUATION_YEAR,
				'type'     => 'select',
				'required' => false,
			),
			array(
				'id'       => self::META_YOUTUBE,
				'slug'     => self::META_YOUTUBE,
				'type'     => 'url',
				'required' => false,
			),
			array(
				'id'       => self::META_VIMEO,
				'slug'     => self::META_VIMEO,
				'type'     => 'url',
				'required' => false,
			),
			array(
				'id'       => self::META_FREE_DEMO,
				'slug'     => self::META_FREE_DEMO,
				'type'     => 'radio',
				'required' => true,
			),
			array(
				'id'       => self::META_DEMO_DURATION,
				'slug'     => self::META_DEMO_DURATION,
				'type'     => 'select',
				'required' => false,
			),
			array(
				'id'       => self::META_MONTHLY_PRICE,
				'slug'     => self::META_MONTHLY_PRICE,
				'type'     => 'number',
				'required' => true,
			),
		);
	}

	/**
	 * @return array<string, string>
	 */
	public static function front_end_meta_keys(): array {
		return array(
			self::META_NATIONALITY     => self::META_NATIONALITY,
			self::META_TIMEZONE        => self::META_TIMEZONE,
			self::META_LANGUAGES       => self::META_LANGUAGES,
			self::META_HEADLINE        => self::META_HEADLINE,
			self::META_EXPERIENCE      => self::META_EXPERIENCE,
			self::META_QUALIFICATION   => self::META_QUALIFICATION,
			self::META_INSTITUTE       => self::META_INSTITUTE,
			self::META_GRADUATION_YEAR => self::META_GRADUATION_YEAR,
			self::META_YOUTUBE         => self::META_YOUTUBE,
			self::META_VIMEO           => self::META_VIMEO,
			self::META_FREE_DEMO       => self::META_FREE_DEMO,
			self::META_DEMO_DURATION   => self::META_DEMO_DURATION,
			self::META_MONTHLY_PRICE   => self::META_MONTHLY_PRICE,
		);
	}

	public static function profile_completion_schema( int $user_id ): array {
		$schema = array(
			'personal_information'     => array(
				'label'  => esc_html__( 'Personal Information', 'mkh-teacher-addon' ),
				'fields' => array(
					self::META_NATIONALITY => self::field_completion( $user_id, self::META_NATIONALITY, true ),
					self::META_TIMEZONE    => self::field_completion( $user_id, self::META_TIMEZONE, true ),
					self::META_LANGUAGES   => self::field_completion( $user_id, self::META_LANGUAGES, true ),
				),
			),
			'professional_information' => array(
				'label'  => esc_html__( 'Professional Information', 'mkh-teacher-addon' ),
				'fields' => array(
					self::META_HEADLINE   => self::field_completion( $user_id, self::META_HEADLINE, true ),
					self::META_EXPERIENCE => self::field_completion( $user_id, self::META_EXPERIENCE, true ),
				),
			),
			'qualifications'           => array(
				'label'  => esc_html__( 'Qualifications', 'mkh-teacher-addon' ),
				'fields' => array(
					self::META_QUALIFICATION   => self::field_completion( $user_id, self::META_QUALIFICATION, true ),
					self::META_INSTITUTE       => self::field_completion( $user_id, self::META_INSTITUTE, false ),
					self::META_GRADUATION_YEAR => self::field_completion( $user_id, self::META_GRADUATION_YEAR, false ),
				),
			),
			'intro_video'              => array(
				'label'  => esc_html__( 'Introduction Video', 'mkh-teacher-addon' ),
				'fields' => array(
					self::META_YOUTUBE => self::field_completion( $user_id, self::META_YOUTUBE, false ),
					self::META_VIMEO   => self::field_completion( $user_id, self::META_VIMEO, false ),
				),
			),
			'demo_class'               => array(
				'label'  => esc_html__( 'Demo Class', 'mkh-teacher-addon' ),
				'fields' => array(
					self::META_FREE_DEMO     => self::field_completion( $user_id, self::META_FREE_DEMO, true ),
					self::META_DEMO_DURATION => self::field_completion( $user_id, self::META_DEMO_DURATION, true ),
				),
			),
			'pricing'                  => array(
				'label'  => esc_html__( 'Pricing', 'mkh-teacher-addon' ),
				'fields' => array(
					self::META_MONTHLY_PRICE => self::field_completion( $user_id, self::META_MONTHLY_PRICE, true ),
				),
			),
		);

		return apply_filters( 'mkh_teacher_addon_profile_completion_schema', $schema, $user_id );
	}

	public static function field_completion( int $user_id, string $meta_key, bool $required ): array {
		$value     = self::get_meta_value( $user_id, $meta_key );
		$completed = self::value_is_completed( $value );

		return array(
			'required'  => $required,
			'completed' => $completed,
			'status'    => $completed ? 'completed' : ( $required ? 'required' : 'optional' ),
			'value'     => $value,
		);
	}

	/**
	 * @return string|array<int, string>
	 */
	public static function get_meta_value( int $user_id, string $meta_key ) {
		$value = get_user_meta( $user_id, $meta_key, true );

		if ( is_array( $value ) ) {
			return array_values( $value );
		}

		return is_string( $value ) ? $value : '';
	}

	/**
	 * @return array<int, string>
	 */
	public static function get_array_meta_value( int $user_id, string $meta_key ): array {
		$value = get_user_meta( $user_id, $meta_key, true );

		if ( is_array( $value ) ) {
			return array_values( array_filter( array_map( 'strval', $value ) ) );
		}

		if ( is_string( $value ) && '' !== $value ) {
			$decoded = json_decode( $value, true );
			if ( is_array( $decoded ) ) {
				return array_values( array_filter( array_map( 'strval', $decoded ) ) );
			}

			return array_values( array_filter( array_map( 'trim', explode( ',', $value ) ) ) );
		}

		return array();
	}

	public static function get_country_value( int $user_id ): string {
		$personal_data = get_user_meta( $user_id, 'masterstudy_personal_data', true );
		if ( is_array( $personal_data ) && ! empty( $personal_data['country'] ) ) {
			return self::normalize_country_choice( (string) $personal_data['country'] );
		}

		$country = self::get_meta_value( $user_id, self::META_COUNTRY );
		return is_string( $country ) ? self::normalize_country_choice( $country ) : '';
	}

	public static function normalize_country_choice( string $value ): string {
		$value = sanitize_text_field( $value );
		if ( '' === $value ) {
			return '';
		}

		$options = self::country_options();
		if ( array_key_exists( $value, $options ) ) {
			return $value;
		}

		$needle = strtolower( $value );
		foreach ( $options as $code => $label ) {
			if ( strtolower( $label ) === $needle ) {
				return (string) $code;
			}
		}

		return $value;
	}

	public static function resolve_nationality_value( array $fields, int $user_id = 0 ): string {
		$nationality = self::normalize_country_choice( (string) ( $fields[ self::META_NATIONALITY ] ?? '' ) );
		if ( '' !== $nationality ) {
			return $nationality;
		}

		$fallback = self::normalize_country_choice( (string) ( $fields[ self::META_COUNTRY ] ?? '' ) );
		if ( '' !== $fallback ) {
			return $fallback;
		}

		return $user_id > 0 ? self::get_country_value( $user_id ) : '';
	}

	public static function value_is_completed( $value ): bool {
		if ( is_array( $value ) ) {
			return ! empty( array_filter( $value, static fn( $item ) => '' !== trim( (string) $item ) ) );
		}

		return '' !== trim( (string) $value );
	}

	public static function sanitize_price( $value ): string {
		$value = (string) $value;
		$value = preg_replace( '/[^0-9\.,]/', '', $value );
		$value = str_replace( ',', '.', $value );
		$parts = explode( '.', $value );

		if ( count( $parts ) > 2 ) {
			$value = array_shift( $parts ) . '.' . implode( '', $parts );
		}

		return trim( $value, '.' );
	}

	/**
	 * @return array<string, mixed>
	 */
	public static function get_request_data(): array {
		$raw = file_get_contents( 'php://input' );
		if ( false === $raw || '' === trim( $raw ) ) {
			return array();
		}

		$data = json_decode( $raw, true );
		return is_array( $data ) ? $data : array();
	}

	/**
	 * @param array<string, mixed> $payload
	 * @return array<string, mixed>
	 */
	public static function normalize_fields( array $payload ): array {
		if ( isset( $payload['meta'] ) && is_array( $payload['meta'] ) ) {
			return $payload['meta'];
		}

		if ( isset( $payload['fields'] ) && is_array( $payload['fields'] ) ) {
			return $payload['fields'];
		}

		if ( isset( $payload['profile'] ) && is_array( $payload['profile'] ) ) {
			return $payload['profile'];
		}

		if ( isset( $payload['data'] ) && is_array( $payload['data'] ) ) {
			return $payload['data'];
		}

		return $payload;
	}

	public static function pre_save_masterstudy_profile(): void {
		check_ajax_referer( 'stm_lms_save_user_info', 'nonce' );

		$user_id = get_current_user_id();
		if ( empty( $user_id ) || ! current_user_can( 'edit_user', $user_id ) ) {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => esc_html__( 'You are not allowed to update this profile.', 'mkh-teacher-addon' ),
				)
			);
		}

		$request = self::get_request_data();
		$fields  = self::normalize_fields( $request );

		if ( ! empty( $_POST ) && is_array( $_POST ) ) {
			$posted_fields = array();
			foreach ( $_POST as $key => $value ) {
				if ( is_array( $value ) ) {
					$posted_fields[ $key ] = array_values( array_filter( array_map( 'sanitize_text_field', wp_unslash( $value ) ) ) );
				} else {
					$posted_fields[ $key ] = sanitize_text_field( wp_unslash( (string) $value ) );
				}
			}

			// Normalize languages payload so validation always receives:
			// $fields['mkh_languages_spoken'] as a non-empty array.
			$languages_array_key = self::META_LANGUAGES . '[]';

			// Case A: HTML checkbox array comes in as `mkh_languages_spoken[]`.
			if ( array_key_exists( $languages_array_key, $posted_fields ) ) {
				if ( ! array_key_exists( self::META_LANGUAGES, $posted_fields ) ) {
					$posted_fields[ self::META_LANGUAGES ] = $posted_fields[ $languages_array_key ];
				}
				unset( $posted_fields[ $languages_array_key ] );
			}

			// Case B: Some clients send languages as a comma-separated string.
			if ( isset( $posted_fields[ self::META_LANGUAGES ] ) && ! is_array( $posted_fields[ self::META_LANGUAGES ] ) ) {
				$posted_fields[ self::META_LANGUAGES ] = array_filter(
					array_map( 'trim', explode( ',', (string) $posted_fields[ self::META_LANGUAGES ] ) )
				);
			}

			// Case C: Some clients may send `mkh_languages_spoken` as an array under a different key.
			// If an empty array is explicitly sent, we should NOT fallback to existing values;
			// validation must correctly fail when nothing is selected.
			if ( array_key_exists( self::META_LANGUAGES, $posted_fields ) && empty( (array) $posted_fields[ self::META_LANGUAGES ] ) ) {
				// Keep empty to let Validation::required_languages() handle it.
			}

			$fields = array_merge( $posted_fields, $fields );
		}

		// Canonicalize languages for JSON submissions too.
		$languages = $fields[ self::META_LANGUAGES ] ?? array();
		if ( is_string( $languages ) ) {
			$languages = explode( ',', $languages );
		}
		if ( ! is_array( $languages ) ) {
			$languages = array();
		}
		$fields[ self::META_LANGUAGES ] = array_values(
			array_filter(
				array_map( 'sanitize_text_field', $languages ),
				static fn( $v ) => '' !== (string) $v
			)
		);

		foreach ( self::front_end_meta_keys() as $meta_key => $mapped_key ) {
			if ( ! array_key_exists( $meta_key, $fields ) ) {
				$existing_value = self::get_meta_value( $user_id, $meta_key );
				if ( '' !== $existing_value ) {
					$fields[ $meta_key ] = $existing_value;
				}
			}
		}

		$fields[ self::META_NATIONALITY ] = self::resolve_nationality_value( $fields, $user_id );


		if ( empty( array_intersect_key( $fields, self::front_end_meta_keys() ) ) ) {
			return;
		}

		$errors = Validation::validate_frontend( $fields );
		if ( ! empty( $errors ) ) {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => $errors[0]['text'] ?? esc_html__( 'Please review the profile fields.', 'mkh-teacher-addon' ),
					'errors'  => $errors,
				)
			);
		}

		self::persist_frontend_fields( $user_id, $fields );
	}

	public static function save_admin_profile( int $user_id ): void {
		if ( empty( $user_id ) || ! current_user_can( 'edit_user', $user_id ) ) {
			return;
		}

		$nonce = isset( $_POST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) : '';
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'update-user_' . $user_id ) ) {
			return;
		}

		self::handle_admin_certificate_uploads( $user_id );
		self::handle_admin_certificate_removals( $user_id );
	}

	/**
	 * @param int $user_id
	 * @param array<string, mixed> $fields
	 */
	private static function persist_frontend_fields( int $user_id, array $fields ): void {
		self::update_or_delete_user_meta( $user_id, self::META_NATIONALITY, self::resolve_nationality_value( $fields, $user_id ), true );
		self::update_or_delete_user_meta( $user_id, self::META_TIMEZONE, sanitize_text_field( (string) ( $fields[ self::META_TIMEZONE ] ?? '' ) ), true );

		$languages = $fields[ self::META_LANGUAGES ] ?? array();
		if ( ! is_array( $languages ) ) {
			$languages = array_filter( array_map( 'trim', explode( ',', (string) $languages ) ) );
		}

		$allowed_languages = array_keys( self::languages_options() );
		$languages         = array_values( array_intersect( array_map( 'sanitize_text_field', $languages ), $allowed_languages ) );
		update_user_meta( $user_id, self::META_LANGUAGES, $languages );

		self::update_or_delete_user_meta( $user_id, self::META_HEADLINE, sanitize_text_field( (string) ( $fields[ self::META_HEADLINE ] ?? '' ) ), true );
		self::update_or_delete_user_meta( $user_id, self::META_EXPERIENCE, sanitize_text_field( (string) ( $fields[ self::META_EXPERIENCE ] ?? '' ) ), true );

		self::update_or_delete_user_meta( $user_id, self::META_QUALIFICATION, sanitize_text_field( (string) ( $fields[ self::META_QUALIFICATION ] ?? '' ) ), true );
		self::update_or_delete_user_meta( $user_id, self::META_INSTITUTE, sanitize_text_field( (string) ( $fields[ self::META_INSTITUTE ] ?? '' ) ) );
		self::update_or_delete_user_meta( $user_id, self::META_GRADUATION_YEAR, sanitize_text_field( (string) ( $fields[ self::META_GRADUATION_YEAR ] ?? '' ) ) );

		$youtube = self::sanitize_video_url( (string) ( $fields[ self::META_YOUTUBE ] ?? '' ), 'youtube' );
		$vimeo   = self::sanitize_video_url( (string) ( $fields[ self::META_VIMEO ] ?? '' ), 'vimeo' );

		if ( ! empty( $youtube ) ) {
			update_user_meta( $user_id, self::META_YOUTUBE, $youtube );
			delete_user_meta( $user_id, self::META_VIMEO );
		} elseif ( ! empty( $vimeo ) ) {
			update_user_meta( $user_id, self::META_VIMEO, $vimeo );
			delete_user_meta( $user_id, self::META_YOUTUBE );
		} else {
			delete_user_meta( $user_id, self::META_YOUTUBE );
			delete_user_meta( $user_id, self::META_VIMEO );
		}

		$free_demo = sanitize_key( (string) ( $fields[ self::META_FREE_DEMO ] ?? '' ) );
		if ( in_array( $free_demo, array( 'yes', 'no' ), true ) ) {
			update_user_meta( $user_id, self::META_FREE_DEMO, $free_demo );
		}

		$demo_duration = sanitize_text_field( (string) ( $fields[ self::META_DEMO_DURATION ] ?? '' ) );
		if ( 'yes' === $free_demo && in_array( $demo_duration, array_keys( self::demo_duration_options() ), true ) ) {
			update_user_meta( $user_id, self::META_DEMO_DURATION, $demo_duration );
		} else {
			delete_user_meta( $user_id, self::META_DEMO_DURATION );
		}

		$monthly_price = self::sanitize_price( (string) ( $fields[ self::META_MONTHLY_PRICE ] ?? '' ) );
		self::update_or_delete_user_meta( $user_id, self::META_MONTHLY_PRICE, $monthly_price, true );

		update_user_meta( $user_id, self::META_PROFILE_COMPLETION, self::profile_completion_schema( $user_id ) );
	}

	private static function update_or_delete_user_meta( int $user_id, string $meta_key, string $value, bool $required = false ): void {
		if ( '' === trim( $value ) ) {
			if ( ! $required ) {
				delete_user_meta( $user_id, $meta_key );
			}

			return;
		}

		update_user_meta( $user_id, $meta_key, $value );
	}

	private static function sanitize_video_url( string $url, string $platform ): string {
		$url = esc_url_raw( trim( $url ) );
		if ( '' === $url ) {
			return '';
		}

		return Validation::validate_video_url( $url, $platform ) ? $url : '';
	}

	private static function handle_admin_certificate_uploads( int $user_id ): void {
		if ( empty( $_FILES['mkh_certificate_files'] ) || ! is_array( $_FILES['mkh_certificate_files'] ) ) {
			return;
		}

		$files = self::normalize_files_array( $_FILES['mkh_certificate_files'] );
		if ( empty( $files ) ) {
			return;
		}

		$stored = self::get_array_meta_value( $user_id, self::META_CERTIFICATES );
		$stored = array_map( 'absint', $stored );

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		foreach ( $files as $file ) {
			$error = Validation::validate_certificate_file( $file );
			if ( ! empty( $error ) ) {
				continue;
			}

			$upload = wp_handle_upload(
				$file,
				array(
					'test_form' => false,
					'mimes'     => Validation::certificate_mimes(),
				)
			);

			if ( ! empty( $upload['error'] ) || empty( $upload['file'] ) ) {
				continue;
			}

			$attachment_id = wp_insert_attachment(
				array(
					'post_mime_type' => $upload['type'] ?? 'application/octet-stream',
					'post_title'     => sanitize_file_name( wp_basename( $upload['file'] ) ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				),
				$upload['file']
			);

			if ( is_wp_error( $attachment_id ) || empty( $attachment_id ) ) {
				continue;
			}

			$metadata = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
			wp_update_attachment_metadata( $attachment_id, $metadata );
			$stored[] = (int) $attachment_id;
		}

		$stored = array_values( array_unique( array_filter( array_map( 'absint', $stored ) ) ) );
		update_user_meta( $user_id, self::META_CERTIFICATES, $stored );
	}

	private static function handle_admin_certificate_removals( int $user_id ): void {
		$remove_ids = isset( $_POST['mkh_certificate_remove'] ) ? (array) $_POST['mkh_certificate_remove'] : array();
		$remove_ids = array_values( array_filter( array_map( 'absint', $remove_ids ) ) );

		if ( empty( $remove_ids ) ) {
			return;
		}

		$current = self::get_array_meta_value( $user_id, self::META_CERTIFICATES );
		$current = array_values( array_diff( array_map( 'absint', $current ), $remove_ids ) );

		update_user_meta( $user_id, self::META_CERTIFICATES, $current );
	}

	/**
	 * @param array<string, mixed> $files
	 * @return array<int, array<string, mixed>>
	 */
	private static function normalize_files_array( array $files ): array {
		if ( empty( $files['name'] ) || ! is_array( $files['name'] ) ) {
			return array();
		}

		$normalized = array();
		foreach ( $files['name'] as $index => $name ) {
			if ( empty( $name ) ) {
				continue;
			}

			$normalized[] = array(
				'name'     => $name,
				'type'     => $files['type'][ $index ] ?? '',
				'tmp_name' => $files['tmp_name'][ $index ] ?? '',
				'error'    => $files['error'][ $index ] ?? UPLOAD_ERR_NO_FILE,
				'size'     => $files['size'][ $index ] ?? 0,
			);
		}

		return $normalized;
	}

	public static function render_field_status( int $user_id, string $meta_key, bool $required ): string {
		$status = self::field_completion( $user_id, $meta_key, $required )['status'];

		return sprintf(
			'<span class="mkh-teacher-addon__field-status mkh-teacher-addon__field-status_%1$s">%2$s</span>',
			esc_attr( $status ),
			esc_html( ucfirst( $status ) )
		);
	}

	public static function render_input( array $args ): void {
		$defaults = array(
			'type'        => 'text',
			'name'        => '',
			'id'          => '',
			'label'       => '',
			'value'       => '',
			'placeholder' => '',
			'required'    => false,
			'help'        => '',
			'max_length'  => '',
			'min_length'  => '',
			'wrapper'     => '',
			'attributes'  => array(),
		);
		$args = wp_parse_args( $args, $defaults );

		$attributes = '';
		foreach ( (array) $args['attributes'] as $attr => $val ) {
			$attributes .= sprintf( ' %1$s="%2$s"', esc_attr( $attr ), esc_attr( (string) $val ) );
		}

		$wrapper_classes = trim( 'masterstudy-account-settings__field ' . (string) $args['wrapper'] );
		?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="masterstudy-account-settings__field-wrapper">
				<label for="<?php echo esc_attr( $args['id'] ); ?>" class="masterstudy-account-settings__field-label">
					<?php echo esc_html( $args['label'] ); ?>
					<?php echo self::render_field_status( get_current_user_id(), (string) $args['name'], (bool) $args['required'] ); ?>
				</label>
				<input
					type="<?php echo esc_attr( $args['type'] ); ?>"
					id="<?php echo esc_attr( $args['id'] ); ?>"
					name="<?php echo esc_attr( $args['name'] ); ?>"
					class="masterstudy-account-settings__input"
					value="<?php echo esc_attr( (string) $args['value'] ); ?>"
					placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
					<?php echo $args['required'] ? 'required' : ''; ?>
					<?php echo '' !== $args['max_length'] ? 'maxlength="' . esc_attr( (string) $args['max_length'] ) . '"' : ''; ?>
					<?php echo '' !== $args['min_length'] ? 'minlength="' . esc_attr( (string) $args['min_length'] ) . '"' : ''; ?>
					<?php echo $attributes; ?>
				>
				<?php if ( ! empty( $args['help'] ) ) : ?>
					<p class="masterstudy-account-settings__field-desc"><?php echo wp_kses_post( $args['help'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	public static function render_textarea( array $args ): void {
		$defaults = array(
			'name'        => '',
			'id'          => '',
			'label'       => '',
			'value'       => '',
			'placeholder' => '',
			'required'    => false,
			'wrapper'     => '',
			'rows'        => 8,
			'help'        => '',
			'counter'     => '',
		);
		$args = wp_parse_args( $args, $defaults );

		$wrapper_classes = trim( 'masterstudy-account-settings__field ' . (string) $args['wrapper'] );
		?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="masterstudy-account-settings__field-wrapper">
				<label for="<?php echo esc_attr( $args['id'] ); ?>" class="masterstudy-account-settings__field-label">
					<?php echo esc_html( $args['label'] ); ?>
					<?php echo self::render_field_status( get_current_user_id(), (string) $args['name'], (bool) $args['required'] ); ?>
				</label>
				<textarea
					id="<?php echo esc_attr( $args['id'] ); ?>"
					name="<?php echo esc_attr( $args['name'] ); ?>"
					class="masterstudy-account-settings__textarea"
					rows="<?php echo esc_attr( (string) $args['rows'] ); ?>"
					placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
					<?php echo $args['required'] ? 'required' : ''; ?>
				><?php echo esc_textarea( $args['value'] ); ?></textarea>
				<?php if ( ! empty( $args['counter'] ) ) : ?>
					<div class="mkh-teacher-addon__counter" data-mkh-counter="<?php echo esc_attr( (string) $args['counter'] ); ?>"></div>
				<?php endif; ?>
				<?php if ( ! empty( $args['help'] ) ) : ?>
					<p class="masterstudy-account-settings__field-desc"><?php echo wp_kses_post( $args['help'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	public static function render_select( array $args ): void {
		$defaults = array(
			'name'     => '',
			'id'       => '',
			'label'    => '',
			'value'    => '',
			'options'  => array(),
			'required' => false,
			'multiple' => false,
			'wrapper'  => '',
			'help'     => '',
		);
		$args = wp_parse_args( $args, $defaults );

		$multiple_name  = $args['multiple'] ? $args['name'] . '[]' : $args['name'];
		$wrapper_classes = trim( 'masterstudy-account-settings__field ' . (string) $args['wrapper'] );
		?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="masterstudy-account-settings__field-wrapper">
				<label for="<?php echo esc_attr( $args['id'] ); ?>" class="masterstudy-account-settings__field-label">
					<?php echo esc_html( $args['label'] ); ?>
					<?php echo self::render_field_status( get_current_user_id(), (string) $args['name'], (bool) $args['required'] ); ?>
				</label>
				<select
					id="<?php echo esc_attr( $args['id'] ); ?>"
					name="<?php echo esc_attr( $multiple_name ); ?>"
					class="masterstudy-account-settings__select"
					<?php echo $args['multiple'] ? 'multiple' : ''; ?>
					<?php echo $args['required'] ? 'required' : ''; ?>
				>
					<?php foreach ( (array) $args['options'] as $key => $label ) : ?>
						<option value="<?php echo esc_attr( (string) $key ); ?>" <?php echo $args['multiple'] ? selected( true, in_array( (string) $key, (array) $args['value'], true ), false ) : selected( (string) $args['value'], (string) $key, false ); ?>>
							<?php echo esc_html( (string) $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?php if ( ! empty( $args['help'] ) ) : ?>
					<p class="masterstudy-account-settings__field-desc"><?php echo wp_kses_post( $args['help'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	public static function render_radio_group( array $args ): void {
		$defaults = array(
			'name'     => '',
			'id'       => '',
			'label'    => '',
			'value'    => '',
			'options'  => array(),
			'required' => false,
			'wrapper'  => '',
			'help'     => '',
		);
		$args = wp_parse_args( $args, $defaults );

		$wrapper_classes = trim( 'masterstudy-account-settings__field ' . (string) $args['wrapper'] );
		?>
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="masterstudy-account-settings__field-wrapper">
				<div class="masterstudy-account-settings__field-label d-block">
					<?php echo esc_html( $args['label'] ); ?>
					<?php echo self::render_field_status( get_current_user_id(), (string) $args['name'], (bool) $args['required'] ); ?>
				</div>
				<div class="d-flex flex-wrap gap-3">
					<?php foreach ( (array) $args['options'] as $key => $label ) : ?>
						<div class="form-check mb-0">
							<input
								class="form-check-input"
								type="radio"
								name="<?php echo esc_attr( $args['name'] ); ?>"
								id="<?php echo esc_attr( $args['id'] . '_' . $key ); ?>"
								value="<?php echo esc_attr( (string) $key ); ?>"
								<?php checked( (string) $args['value'], (string) $key ); ?>
								<?php echo $args['required'] ? 'required' : ''; ?>
							>
							<label class="form-check-label" for="<?php echo esc_attr( $args['id'] . '_' . $key ); ?>">
								<?php echo esc_html( (string) $label ); ?>
							</label>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( ! empty( $args['help'] ) ) : ?>
					<p class="masterstudy-account-settings__field-desc"><?php echo wp_kses_post( $args['help'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	public static function render_certificate_list( int $user_id ): void {
		$certificates = self::get_array_meta_value( $user_id, self::META_CERTIFICATES );
		?>
		<div class="mkh-teacher-addon__certificates">
			<?php if ( empty( $certificates ) ) : ?>
				<p class="text-muted mb-0"><?php echo esc_html__( 'No certificates uploaded yet.', 'mkh-teacher-addon' ); ?></p>
			<?php else : ?>
				<ul class="list-group">
					<?php foreach ( $certificates as $certificate_id ) : ?>
						<?php $certificate_id = absint( $certificate_id ); ?>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							<div class="me-3">
								<strong><?php echo esc_html( get_the_title( $certificate_id ) ?: wp_basename( (string) get_attached_file( $certificate_id ) ) ); ?></strong>
								<div class="small text-muted"><?php echo esc_html( wp_get_attachment_url( $certificate_id ) ); ?></div>
							</div>
							<label class="form-check m-0">
								<input class="form-check-input" type="checkbox" name="mkh_certificate_remove[]" value="<?php echo esc_attr( (string) $certificate_id ); ?>">
								<span class="form-check-label"><?php echo esc_html__( 'Remove', 'mkh-teacher-addon' ); ?></span>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<?php
	}

	public static function render_certificate_upload( int $user_id ): void {
		?>
		<div class="masterstudy-account-settings__field">
			<div class="masterstudy-account-settings__field-wrapper">
				<label for="mkh_certificate_files" class="masterstudy-account-settings__field-label"><?php echo esc_html__( 'Upload Certificates', 'mkh-teacher-addon' ); ?></label>
				<input type="file" class="masterstudy-account-settings__input" id="mkh_certificate_files" name="mkh_certificate_files[]" multiple accept=".pdf,.jpg,.jpeg,.png">
				<p class="masterstudy-account-settings__field-desc"><?php echo esc_html__( 'PDF, JPG, and PNG files up to 5 MB each.', 'mkh-teacher-addon' ); ?></p>
			</div>
		</div>
		<?php
	}

	public static function render_currency_badge(): void {
		?>
		<span class="badge text-bg-secondary mkh-teacher-addon__currency-badge"><?php echo esc_html( self::currency_symbol() ); ?></span>
		<?php
	}
}
