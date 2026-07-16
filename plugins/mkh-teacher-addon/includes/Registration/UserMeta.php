<?php

namespace MKH\TeacherAddon\Registration;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class UserMeta {
	public const META_TEACHER_STATUS              = 'teacher_status';
	public const META_PROFILE_COMPLETION          = 'profile_completion';
	public const META_WHATSAPP_NUMBER             = 'whatsapp_number';
	public const META_GENDER                      = 'gender';
	public const META_COUNTRY                     = 'country';
	public const META_ACCEPTED_TERMS              = 'accepted_terms';
	public const META_ACCEPTED_TERMS_AT           = 'accepted_terms_at';
	public const META_ACCEPTED_TERMS_IP           = 'accepted_terms_ip';
	public const META_ACCEPTED_TERMS_VERSION      = 'accepted_terms_version';
	public const META_EMAIL_VERIFIED              = 'email_verified';
	public const META_VERIFIED_AT                 = 'verified_at';
	public const META_EMAIL_VERIFY_TOKEN_HASH     = 'mkh_email_verify_token_hash';
	public const META_EMAIL_VERIFY_REQUESTED_AT   = 'mkh_email_verify_requested_at';
	public const META_REFERRAL_CODE               = 'referral_code';

	public static function init_registration_meta( int $user_id, array $data ): void {
		$profile_data = self::extract_profile_fields( $data );

		update_user_meta( $user_id, self::META_TEACHER_STATUS, 'pending_profile' );
		update_user_meta( $user_id, self::META_PROFILE_COMPLETION, 0 );

		if ( isset( $profile_data['whatsapp_number'] ) ) {
			update_user_meta( $user_id, self::META_WHATSAPP_NUMBER, sanitize_text_field( (string) $profile_data['whatsapp_number'] ) );
		}

		if ( isset( $profile_data['gender'] ) ) {
			update_user_meta( $user_id, self::META_GENDER, sanitize_key( (string) $profile_data['gender'] ) );
		}

		if ( isset( $profile_data['country'] ) ) {
			update_user_meta( $user_id, self::META_COUNTRY, sanitize_text_field( (string) $profile_data['country'] ) );
		}

		if ( isset( $profile_data['referral_code'] ) && '' !== (string) $profile_data['referral_code'] ) {
			update_user_meta( $user_id, self::META_REFERRAL_CODE, sanitize_text_field( (string) $profile_data['referral_code'] ) );
		}

		if ( ! empty( $data['privacy_policy'] ) ) {
			update_user_meta( $user_id, self::META_ACCEPTED_TERMS, 1 );
			update_user_meta( $user_id, self::META_ACCEPTED_TERMS_AT, current_time( 'mysql' ) );
			update_user_meta( $user_id, self::META_ACCEPTED_TERMS_VERSION, mkh_teacher_addon_terms_version() );
			update_user_meta( $user_id, self::META_ACCEPTED_TERMS_IP, mkh_teacher_addon_client_ip() );
		}

		update_user_meta( $user_id, self::META_EMAIL_VERIFIED, 0 );
	}

	public static function mark_email_verified( int $user_id ): void {
		update_user_meta( $user_id, self::META_EMAIL_VERIFIED, 1 );
		update_user_meta( $user_id, self::META_VERIFIED_AT, current_time( 'mysql' ) );
		delete_user_meta( $user_id, self::META_EMAIL_VERIFY_TOKEN_HASH );
		delete_user_meta( $user_id, self::META_EMAIL_VERIFY_REQUESTED_AT );
	}

	public static function mark_email_verification_requested( int $user_id, string $token_hash ): void {
		update_user_meta( $user_id, self::META_EMAIL_VERIFY_TOKEN_HASH, $token_hash );
		update_user_meta( $user_id, self::META_EMAIL_VERIFY_REQUESTED_AT, current_time( 'mysql' ) );
	}

	public static function get_email_verification_hash( int $user_id ): string {
		return (string) get_user_meta( $user_id, self::META_EMAIL_VERIFY_TOKEN_HASH, true );
	}

	/**
	 * @param array<string, mixed> $data
	 * @return array<string, mixed>
	 */
	private static function extract_profile_fields( array $data ): array {
		$fields  = array();
		$profile = $data['profile_default_fields_for_register'] ?? array();

		if ( ! is_array( $profile ) ) {
			return $fields;
		}

		foreach ( $profile as $field_key => $field ) {
			if ( ! is_array( $field ) ) {
				continue;
			}

			$fields[ (string) $field_key ] = $field['value'] ?? '';
		}

		return $fields;
	}
}
