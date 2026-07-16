<?php

namespace MKH\TeacherAddon\Registration;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Validation {
	/**
	 * @param array<string, mixed> $data
	 * @return array<int, array<string, string>>
	 */
	public static function validate( array $data ): array {
		$errors = array();
		$fields = self::extract_registration_fields( $data );

		self::require_value( $errors, $fields, 'first_name', esc_html__( 'First name is required.', 'mkh-teacher-addon' ) );
		self::require_value( $errors, $fields, 'last_name', esc_html__( 'Last name is required.', 'mkh-teacher-addon' ) );
		self::require_value( $errors, $fields, 'country', esc_html__( 'Country is required.', 'mkh-teacher-addon' ) );
		self::require_value( $errors, $fields, 'whatsapp_number', esc_html__( 'WhatsApp number is required.', 'mkh-teacher-addon' ) );
		self::require_value( $errors, $fields, 'gender', esc_html__( 'Gender is required.', 'mkh-teacher-addon' ) );

		$email = sanitize_email( (string) ( $data['register_user_email'] ?? '' ) );
		if ( empty( $email ) || ! is_email( $email ) ) {
			$errors[] = self::error( 'register_user_email', esc_html__( 'Please enter a valid email address.', 'mkh-teacher-addon' ), 'valid' );
		}

		$password = (string) ( $data['register_user_password'] ?? '' );
		$password_confirm = (string) ( $data['register_user_password_re'] ?? '' );

		if ( empty( $password ) ) {
			$errors[] = self::error( 'register_user_password', esc_html__( 'Password is required.', 'mkh-teacher-addon' ), 'required' );
		}

		if ( empty( $password_confirm ) ) {
			$errors[] = self::error( 'register_user_password_re', esc_html__( 'Password confirmation is required.', 'mkh-teacher-addon' ), 'required' );
		}

		if ( ! empty( $password ) && ! empty( $password_confirm ) && $password !== $password_confirm ) {
			$errors[] = self::error( 'register_user_password_re', esc_html__( 'Passwords do not match.', 'mkh-teacher-addon' ), 'not_match' );
		}

		$privacy_policy = ! empty( $data['privacy_policy'] );
		if ( ! $privacy_policy ) {
			$errors[] = self::error( 'privacy_policy', esc_html__( 'You must agree to the Terms & Conditions and Privacy Policy.', 'mkh-teacher-addon' ), 'policy' );
		}

		$whatsapp_number = preg_replace( '/[^\d\+\-\(\)\s]/', '', (string) ( $fields['whatsapp_number']['value'] ?? '' ) );
		if ( ! empty( $fields['whatsapp_number']['value'] ) && ! preg_match( '/^[0-9\+\-\(\)\s]{7,20}$/', $whatsapp_number ) ) {
			$errors[] = self::error( 'whatsapp_number', esc_html__( 'Please enter a valid WhatsApp number.', 'mkh-teacher-addon' ), 'valid' );
		}

		$gender = sanitize_key( (string) ( $fields['gender']['value'] ?? '' ) );
		$allowed_genders = array( 'male', 'female', 'other', 'prefer_not_to_say' );
		if ( ! empty( $fields['gender']['value'] ) && ! in_array( $gender, $allowed_genders, true ) ) {
			$errors[] = self::error( 'gender', esc_html__( 'Please select a valid gender.', 'mkh-teacher-addon' ), 'valid' );
		}

		$country = sanitize_text_field( (string) ( $fields['country']['value'] ?? '' ) );
		if ( ! empty( $fields['country']['value'] ) && strlen( $country ) > 80 ) {
			$errors[] = self::error( 'country', esc_html__( 'Please enter a valid country.', 'mkh-teacher-addon' ), 'valid' );
		}

		return $errors;
	}

	/**
	 * @param array<string, mixed> $data
	 * @return array<string, array<string, mixed>>
	 */
	public static function extract_registration_fields( array $data ): array {
		$fields = array();

		$default_fields = $data['profile_default_fields_for_register'] ?? array();
		if ( is_array( $default_fields ) ) {
			foreach ( $default_fields as $field_key => $field ) {
				if ( ! is_array( $field ) ) {
					continue;
				}

				$fields[ (string) $field_key ] = array(
					'value'    => $field['value'] ?? '',
					'required' => ! empty( $field['required'] ),
					'label'    => isset( $field['label'] ) ? sanitize_text_field( (string) $field['label'] ) : '',
					'raw'      => $field,
				);
			}
		}

		return $fields;
	}

	/**
	 * @param array<int, array<string, string>> $errors
	 */
	private static function require_value( array &$errors, array $fields, string $field_key, string $message ): void {
		$value = $fields[ $field_key ]['value'] ?? '';
		if ( empty( $value ) ) {
			$errors[] = self::error( $field_key, $message, 'required' );
		}
	}

	/**
	 * @return array<string, string>
	 */
	private static function error( string $field, string $text, string $id ): array {
		return array(
			'field' => $field,
			'text'  => $text,
			'id'    => $id,
		);
	}
}
