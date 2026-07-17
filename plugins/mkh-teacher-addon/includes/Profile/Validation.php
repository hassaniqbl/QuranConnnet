<?php

namespace MKH\TeacherAddon\Profile;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Validation {
	/**
	 * @param array<string, mixed> $fields
	 * @return array<int, array<string, string>>
	 */
	public static function validate_frontend( array $fields ): array {
		$errors = array();
		$fields[ SaveProfile::META_NATIONALITY ] = SaveProfile::resolve_nationality_value( $fields );

		//self::required_choice( $errors, $fields, SaveProfile::META_NATIONALITY, SaveProfile::nationality_options(), esc_html__( 'Nationality is required.', 'mkh-teacher-addon' ) );
		//self::optional_choice( $errors, $fields, SaveProfile::META_TIMEZONE, SaveProfile::timezone_options(), esc_html__( 'Please select a valid time zone.', 'mkh-teacher-addon' ) );
		// Use client-side validation for language selection to avoid mismatch between
		// how JS submits checkbox arrays vs how payload is normalized server-side.
		// Backend will still persist languages as long as they come through.
		// self::required_languages( $errors, $fields );
		// self::required_text( $errors, $fields, SaveProfile::META_HEADLINE, 1, 120, esc_html__( 'Professional headline is required and must be 120 characters or fewer.', 'mkh-teacher-addon' ) );
		// self::required_choice( $errors, $fields, SaveProfile::META_EXPERIENCE, SaveProfile::experience_options(), esc_html__( 'Teaching experience is required.', 'mkh-teacher-addon' ) );
		// self::required_choice( $errors, $fields, SaveProfile::META_QUALIFICATION, SaveProfile::qualification_options(), esc_html__( 'Highest qualification is required.', 'mkh-teacher-addon' ) );

		self::optional_choice( $errors, $fields, SaveProfile::META_GRADUATION_YEAR, SaveProfile::graduation_year_options(), esc_html__( 'Please select a valid graduation year.', 'mkh-teacher-addon' ) );
		self::optional_text( $errors, $fields, SaveProfile::META_INSTITUTE, 120, esc_html__( 'Institute name must be 120 characters or fewer.', 'mkh-teacher-addon' ) );
		self::optional_url_pair( $errors, $fields );

		// self::required_choice( $errors, $fields, SaveProfile::META_FREE_DEMO, array( 'yes' => 'yes', 'no' => 'no' ), esc_html__( 'Please choose whether you offer a free demo.', 'mkh-teacher-addon' ) );
		//self::required_demo_duration( $errors, $fields );
		//self::required_price( $errors, $fields );

		return apply_filters( 'mkh_teacher_addon_profile_validation_errors', $errors, $fields );
	}

	/**
	 * @param array<string, mixed> $fields
	 * @return bool
	 */
	public static function validate_video_url( string $url, string $platform ): bool {
		if ( empty( $url ) || ! wp_http_validate_url( $url ) ) {
			return false;
		}

		$host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
		if ( 'youtube' === $platform ) {
			return (bool) preg_match( '/(^|\.)youtube\.com$|(^|\.)youtu\.be$/', $host );
		}

		if ( 'vimeo' === $platform ) {
			return (bool) preg_match( '/(^|\.)vimeo\.com$/', $host );
		}

		return false;
	}

	/**
	 * @return array<string, string>
	 */
	public static function certificate_mimes(): array {
		return array(
			'pdf'  => 'application/pdf',
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'png'  => 'image/png',
		);
	}

	/**
	 * @param array<string, mixed> $file
	 * @return string
	 */
	public static function validate_certificate_file( array $file ): string {
		if ( empty( $file['name'] ) || empty( $file['tmp_name'] ) ) {
			return esc_html__( 'No file uploaded.', 'mkh-teacher-addon' );
		}

		$size = absint( $file['size'] ?? 0 );
		if ( $size > 5 * 1024 * 1024 ) {
			return esc_html__( 'File size must be 5 MB or less.', 'mkh-teacher-addon' );
		}

		$extension = strtolower( pathinfo( (string) $file['name'], PATHINFO_EXTENSION ) );
		if ( ! array_key_exists( $extension, self::certificate_mimes() ) ) {
			return esc_html__( 'Only PDF, JPG, and PNG files are allowed.', 'mkh-teacher-addon' );
		}

		$mime = (string) ( $file['type'] ?? '' );
		if ( '' !== $mime && ! in_array( $mime, self::certificate_mimes(), true ) ) {
			return esc_html__( 'The selected certificate file type is not allowed.', 'mkh-teacher-addon' );
		}

		return '';
	}

	/**
	 * @param array<int, array<string, string>> $errors
	 * @param array<string, mixed> $fields
	 */
	private static function required_choice( array &$errors, array $fields, string $key, array $allowed, string $message ): void {
		$value = sanitize_text_field( (string) ( $fields[ $key ] ?? '' ) );
		if ( '' === $value || ! array_key_exists( $value, $allowed ) ) {
			$errors[] = self::error( $key, $message, 'required' );
		}
	}

	/**
	 * @param array<int, array<string, string>> $errors
	 * @param array<string, mixed> $fields
	 */
	private static function optional_choice( array &$errors, array $fields, string $key, array $allowed, string $message ): void {
		$value = sanitize_text_field( (string) ( $fields[ $key ] ?? '' ) );
		if ( '' !== $value && ! array_key_exists( $value, $allowed ) ) {
			$errors[] = self::error( $key, $message, 'valid' );
		}
	}

	/**
	 * @param array<int, array<string, string>> $errors
	 * @param array<string, mixed> $fields
	 */
	private static function required_text( array &$errors, array $fields, string $key, int $min, int $max, string $message ): void {
		$value = sanitize_text_field( (string) ( $fields[ $key ] ?? '' ) );
		$length = strlen( $value );

		if ( '' === $value || $length < $min || $length > $max ) {
			$errors[] = self::error( $key, $message, 'required' );
		}
	}

	/**
	 * @param array<int, array<string, string>> $errors
	 * @param array<string, mixed> $fields
	 */
	private static function required_textarea( array &$errors, array $fields, string $key, int $min, int $max, string $message ): void {
		$value = sanitize_textarea_field( (string) ( $fields[ $key ] ?? '' ) );
		$length = strlen( $value );

		if ( '' === $value || $length < $min || $length > $max ) {
			$errors[] = self::error( $key, $message, 'required' );
		}
	}

	/**
	 * @param array<int, array<string, string>> $errors
	 * @param array<string, mixed> $fields
	 */
	private static function optional_text( array &$errors, array $fields, string $key, int $max, string $message ): void {
		$value = sanitize_text_field( (string) ( $fields[ $key ] ?? '' ) );
		if ( '' !== $value && strlen( $value ) > $max ) {
			$errors[] = self::error( $key, $message, 'valid' );
		}
	}

	/**
	 * @param array<int, array<string, string>> $errors
	 * @param array<string, mixed> $fields
	 */
	private static function required_languages( array &$errors, array $fields ): void {
		$languages = $fields[ SaveProfile::META_LANGUAGES ] ?? array();

		if ( is_string( $languages ) ) {
			// Allow comma-separated string fallback.
			$languages = explode( ',', $languages );
		}

		if ( ! is_array( $languages ) ) {
			$languages = array();
		}

		// Canonicalize: strip empty values and ensure strings.
		$languages = array_values(
			array_filter(
				array_map(
					static fn( $v ) => trim( is_scalar( $v ) ? (string) $v : '' ),
					$languages
				),
				static fn( $v ) => '' !== (string) $v
			)
		);

		$allowed = array_keys( SaveProfile::languages_options() );
		$values  = array_values( array_intersect( array_map( 'sanitize_text_field', $languages ), $allowed ) );

		if ( empty( $values ) ) {
			$errors[] = self::error(
				SaveProfile::META_LANGUAGES,
				esc_html__( 'Please select at least one language.', 'mkh-teacher-addon' ),
				'required'
			);
		}
	}


	/**
	 * @param array<int, array<string, string>> $errors
	 * @param array<string, mixed> $fields
	 */
	private static function optional_url_pair( array &$errors, array $fields ): void {
		$youtube = esc_url_raw( (string) ( $fields[ SaveProfile::META_YOUTUBE ] ?? '' ) );
		$vimeo   = esc_url_raw( (string) ( $fields[ SaveProfile::META_VIMEO ] ?? '' ) );

		if ( '' !== $youtube && ! self::validate_video_url( $youtube, 'youtube' ) ) {
			$errors[] = self::error( SaveProfile::META_YOUTUBE, esc_html__( 'Please enter a valid YouTube URL.', 'mkh-teacher-addon' ), 'valid' );
		}

		if ( '' !== $vimeo && ! self::validate_video_url( $vimeo, 'vimeo' ) ) {
			$errors[] = self::error( SaveProfile::META_VIMEO, esc_html__( 'Please enter a valid Vimeo URL.', 'mkh-teacher-addon' ), 'valid' );
		}

		if ( '' !== $youtube && '' !== $vimeo ) {
			$errors[] = self::error( SaveProfile::META_VIMEO, esc_html__( 'Choose only one video platform at a time.', 'mkh-teacher-addon' ), 'valid' );
		}
	}

	/**
	 * @param array<int, array<string, string>> $errors
	 * @param array<string, mixed> $fields
	 */
	private static function required_demo_duration( array &$errors, array $fields ): void {
		$free_demo     = sanitize_key( (string) ( $fields[ SaveProfile::META_FREE_DEMO ] ?? '' ) );
		$demo_duration = sanitize_text_field( (string) ( $fields[ SaveProfile::META_DEMO_DURATION ] ?? '' ) );

		if ( 'yes' === $free_demo ) {
			$allowed = array_keys( SaveProfile::demo_duration_options() );
			if ( '' === $demo_duration || ! in_array( $demo_duration, $allowed, true ) ) {
				$errors[] = self::error( SaveProfile::META_DEMO_DURATION, esc_html__( 'Please choose a demo duration.', 'mkh-teacher-addon' ), 'required' );
			}
		}
	}

	/**
	 * @param array<int, array<string, string>> $errors
	 * @param array<string, mixed> $fields
	 */
	private static function required_price( array &$errors, array $fields ): void {
		$price = SaveProfile::sanitize_price( (string) ( $fields[ SaveProfile::META_MONTHLY_PRICE ] ?? '' ) );
		if ( '' === $price || ! is_numeric( $price ) || (float) $price < 0 ) {
			$errors[] = self::error( SaveProfile::META_MONTHLY_PRICE, esc_html__( 'Please enter a valid monthly package price.', 'mkh-teacher-addon' ), 'required' );
		}
	}

	private static function error( string $field, string $text, string $id ): array {
		return array(
			'field' => $field,
			'text'  => $text,
			'id'    => $id,
		);
	}
}
