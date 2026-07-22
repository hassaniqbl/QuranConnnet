<?php
/**
 * Teacher Profile Public Display Functions
 *
 * Displays ACF Teacher Profile information on public instructor profile.
 *
 * SECURITY:
 * - Escape all output.
 * - Guard against ACF values that may return arrays/objects (PHP 8.1+ fatal on array offsets in isset/empty).
 *
 * @package MS_LMS_Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display ACF Teacher Profile information on public instructor profile.
 *
 * @param int $instructor_id The instructor's WordPress user ID.
 */
function mkh_display_teacher_profile_info( $instructor_id ) {
	if ( empty( $instructor_id ) ) {
		return;
	}

	// Load all ACF fields once.
	$about_teacher       = get_field( 'about_teacher', 'user_' . $instructor_id );
	$age                 = get_field( 'mkh_age', 'user_' . $instructor_id );
	$languages           = get_field( 'languages', 'user_' . $instructor_id );
	$country             = get_field( 'mkh_country', 'user_' . $instructor_id );
	$timezone            = get_field( 'mkh_timezone', 'user_' . $instructor_id );
	$fiqh                 = get_field( 'fiqh', 'user_' . $instructor_id );
	$sect                 = get_field( 'sect', 'user_' . $instructor_id );
	$gender               = get_field( 'mkh_gender', 'user_' . $instructor_id );
	$teaching_skills     = get_field( 'teaching_skills', 'user_' . $instructor_id );
	$hourly_rate         = get_field( 'hourly_rate', 'user_' . $instructor_id );
	$teacher_photo       = get_field( 'teacher_photo', 'user_' . $instructor_id );
	$recitation_audio    = get_field( 'recitation_audio', 'user_' . $instructor_id );
	$intro_video_file    = get_field( 'intro_video_file', 'user_' . $instructor_id );
	$intro_video_url     = get_field( 'intro_video_url', 'user_' . $instructor_id );
	$employment_history  = get_field( 'employment_history', 'user_' . $instructor_id );
	$certifications      = get_field( 'certifications', 'user_' . $instructor_id );
	$ijazah              = get_field( 'ijazah', 'user_' . $instructor_id );

	// Early return if everything is empty.
	$has_data = $about_teacher || $age || $languages || $country || $timezone || $fiqh || $sect || $gender || $teaching_skills || $hourly_rate || $teacher_photo || $recitation_audio || $intro_video_file || $intro_video_url || $employment_history || $certifications || $ijazah;
	if ( ! $has_data ) {
		return;
	}

	// Labels.
	$language_labels = array(
		'english'    => __( 'English', 'mkh-teacher-addon' ),
		'arabic'     => __( 'Arabic', 'mkh-teacher-addon' ),
		'urdu'       => __( 'Urdu', 'mkh-teacher-addon' ),
		'hindi'      => __( 'Hindi', 'mkh-teacher-addon' ),
		'punjabi'    => __( 'Punjabi', 'mkh-teacher-addon' ),
		'turkish'    => __( 'Turkish', 'mkh-teacher-addon' ),
		'bengali'    => __( 'Bengali', 'mkh-teacher-addon' ),
		'persian'    => __( 'Persian', 'mkh-teacher-addon' ),
		'pashto'     => __( 'Pashto', 'mkh-teacher-addon' ),
		'malay'      => __( 'Malay', 'mkh-teacher-addon' ),
		'indonesian' => __( 'Indonesian', 'mkh-teacher-addon' ),
		'french'     => __( 'French', 'mkh-teacher-addon' ),
	);

	$fiqh_labels = array(
		'hanafi'  => __( 'Hanafi', 'mkh-teacher-addon' ),
		'shafi'   => __( 'Shafi', 'mkh-teacher-addon' ),
		'maliki'  => __( 'Maliki', 'mkh-teacher-addon' ),
		'hanbali' => __( 'Hanbali', 'mkh-teacher-addon' ),
	);

	$gender_labels = array(
		'male'             => __( 'Male', 'mkh-teacher-addon' ),
		'female'           => __( 'Female', 'mkh-teacher-addon' ),
		'prefer_not_to_say' => __( 'Prefer not to say', 'mkh-teacher-addon' ),
	);

	// Get country name from code
	$mkh_country_name = static function ( $country_code ) use ( $language_labels ) {
		if ( empty( $country_code ) ) {
			return '';
		}

		// Create a simple country mapping (can be expanded)
		$countries = array(
			'AF' => 'Afghanistan',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote D\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island and Mcdonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran, Islamic Republic of',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KP' => 'Korea, Democratic People\'s Republic of',
			'KR' => 'Korea, Republic of',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao People\'s Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia, The Former Yugoslav Republic of',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States of',
			'MD' => 'Moldova, Republic of',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory, Occupied',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'VC' => 'Saint Vincent and the Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome and Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia and the South Sandwich Islands',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SH' => 'St. Helena',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard and Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan, Province of China',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania, United Republic of',
			'TH' => 'Thailand',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Minor Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Viet Nam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
			'WF' => 'Wallis and Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);

		return isset( $countries[ $country_code ] ) ? $countries[ $country_code ] : $country_code;
	};

	$skill_labels = array(
		'recitation' => __( 'Recitation', 'mkh-teacher-addon' ),
		'hifz'       => __( 'Hifz', 'mkh-teacher-addon' ),
		'arabic'     => __( 'Arabic', 'mkh-teacher-addon' ),
		'tajweed'    => __( 'Tajweed', 'mkh-teacher-addon' ),
	);

	// Helper: validate URL.
	$mkh_safe_url = static function ( $maybe_url ) {
		if ( empty( $maybe_url ) || ! is_string( $maybe_url ) ) {
			return '';
		}
		$maybe_url = trim( $maybe_url );
		if ( $maybe_url === '' ) {
			return '';
		}
		// Basic allowlist: http/https only.
		if ( 0 !== strpos( $maybe_url, 'http://' ) && 0 !== strpos( $maybe_url, 'https://' ) ) {
			return '';
		}
		return esc_url( $maybe_url );
	};

	// Helper: normalize ACF select values that might return arrays.
	$mkh_select_value = static function ( $val ) {
		if ( is_scalar( $val ) ) {
			return (string) $val;
		}
		if ( is_array( $val ) ) {
			if ( isset( $val['value'] ) && is_scalar( $val['value'] ) ) {
				return (string) $val['value'];
			}
			// Fallback: first scalar entry.
			foreach ( $val as $v ) {
				if ( is_scalar( $v ) ) {
					return (string) $v;
				}
			}
		}
		return '';
	};
	?>

	<div class="mkh-teacher-profile-public">
		<?php if ( $about_teacher ) : ?>
			<div class="mkh-teacher-profile-section">
				<h3 class="mkh-teacher-profile-section-title"><?php echo esc_html__( 'Introduction', 'mkh-teacher-addon' ); ?></h3>
				<div class="mkh-teacher-profile-content"><?php echo wp_kses_post( $about_teacher ); ?></div>
			</div>
		<?php endif; ?>

		<?php
		// Row 1: Age | Gender (2-column)
		$has_age    = ! empty( $age );
		$gender_key = $mkh_select_value( $gender );
		$has_gender = $gender_key && isset( $gender_labels[ $gender_key ] );
		if ( $has_age || $has_gender ) :
			?>
			<div class="mkh-teacher-profile-section">
				
				<div class="mkh-teacher-profile-two-column">
					<?php if ( $has_age ) : ?>
						<div class="mkh-teacher-profile-column">
							<span class="mkh-teacher-profile-label"><?php echo esc_html__( 'Age', 'mkh-teacher-addon' ); ?></span>
							<span class="mkh-teacher-profile-value"><?php echo esc_html( $age ); ?> <?php echo esc_html__( 'Years', 'mkh-teacher-addon' ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( $has_gender ) : ?>
						<div class="mkh-teacher-profile-column">
							<span class="mkh-teacher-profile-label"><?php echo esc_html__( 'Gender', 'mkh-teacher-addon' ); ?></span>
							<span class="mkh-teacher-profile-value"><?php echo esc_html( $gender_labels[ $gender_key ] ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php
		$has_languages = $languages && is_array( $languages );
		$has_skills    = $teaching_skills && is_array( $teaching_skills );
		if ( $has_languages || $has_skills ) :
			?>
			<div class="mkh-teacher-profile-section">
				<div class="mkh-teacher-profile-two-column">
					<?php if ( $has_languages ) : ?>
						<div class="mkh-teacher-profile-column">
							<h3 class="mkh-teacher-profile-section-title"><?php echo esc_html__( 'Languages', 'mkh-teacher-addon' ); ?></h3>
							<div class="mkh-teacher-profile-languages">
								<?php
								$rendered = false;
								foreach ( $languages as $lang ) {
									$lang_key = $mkh_select_value( $lang );
									if ( $lang_key && isset( $language_labels[ $lang_key ] ) ) {
										$rendered = true;
										?>
										<span class="mkh-teacher-profile-badge"><?php echo esc_html( $language_labels[ $lang_key ] ); ?></span>
										<?php
									}
								}
								?>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( $has_skills ) : ?>
						<div class="mkh-teacher-profile-column">
							<h3 class="mkh-teacher-profile-section-title"><?php echo esc_html__( 'Quran Courses', 'mkh-teacher-addon' ); ?></h3>
							<div class="mkh-teacher-profile-skills">
								<?php foreach ( $teaching_skills as $skill ) : ?>
									<?php
										$skill_key = $mkh_select_value( $skill );
										if ( $skill_key && isset( $skill_labels[ $skill_key ] ) ) :
											?>
											<span class="mkh-teacher-profile-badge mkh-teacher-profile-badge-skill">
												<span class="mkh-teacher-profile-check">✓</span>
												<?php echo esc_html( $skill_labels[ $skill_key ] ); ?>
											</span>
											<?php
										endif;
										?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
				
		<?php
		// Row 2: Country | Timezone (2-column)
		$country_name   = $mkh_country_name( $mkh_select_value( $country ) );
		$timezone_value = $mkh_select_value( $timezone );
		$has_country    = ! empty( $country_name );
		$has_timezone   = ! empty( $timezone_value );
		if ( $has_country || $has_timezone ) :
			?>
			<div class="mkh-teacher-profile-section">
				<h3 class="mkh-teacher-profile-section-title"><?php echo esc_html__( 'Location', 'mkh-teacher-addon' ); ?></h3>
				<div class="mkh-teacher-profile-two-column">
					<?php if ( $has_country ) : ?>
						<div class="mkh-teacher-profile-column">
							<span class="mkh-teacher-profile-label"><?php echo esc_html__( 'Country', 'mkh-teacher-addon' ); ?></span>
							<span class="mkh-teacher-profile-value"><?php echo esc_html( $country_name ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( $has_timezone ) : ?>
						<div class="mkh-teacher-profile-column">
							<span class="mkh-teacher-profile-label"><?php echo esc_html__( 'Timezone', 'mkh-teacher-addon' ); ?></span>
							<span class="mkh-teacher-profile-value"><?php echo esc_html( $timezone_value ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php
		// Row 3: Fiqh | Sect (2-column)
		$fiqh_key = $mkh_select_value( $fiqh );
		$has_fiqh = $fiqh_key && isset( $fiqh_labels[ $fiqh_key ] );
		$has_sect = is_scalar( $sect ) && $sect;
		if ( $has_fiqh || $has_sect ) :
			?>
			<div class="mkh-teacher-profile-section">
				<h3 class="mkh-teacher-profile-section-title"><?php echo esc_html__( 'Islamic Background', 'mkh-teacher-addon' ); ?></h3>
				<div class="mkh-teacher-profile-two-column">
					<?php if ( $has_fiqh ) : ?>
						<div class="mkh-teacher-profile-column">
							<span class="mkh-teacher-profile-label"><?php echo esc_html__( 'Fiqh', 'mkh-teacher-addon' ); ?></span>
							<span class="mkh-teacher-profile-value"><?php echo esc_html( $fiqh_labels[ $fiqh_key ] ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( $has_sect ) : ?>
						<div class="mkh-teacher-profile-column">
							<span class="mkh-teacher-profile-label"><?php echo esc_html__( 'Sect', 'mkh-teacher-addon' ); ?></span>
							<span class="mkh-teacher-profile-value"><?php echo esc_html( $sect ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

	
		<?php if ( ! empty( $hourly_rate ) ) : ?>
			<div class="mkh-teacher-profile-section">
				<h3 class="mkh-teacher-profile-section-title"><?php echo esc_html__( 'Hourly Rate', 'mkh-teacher-addon' ); ?></h3>
				<div class="mkh-teacher-profile-content">
					<span class="mkh-teacher-profile-rate">
						<?php echo esc_html__( '$', 'mkh-teacher-addon' ) . esc_html( number_format( (float) $hourly_rate ) ); ?>
						<?php echo esc_html__( '/ Hour', 'mkh-teacher-addon' ); ?>
					</span>
				</div>
			</div>
		<?php endif; ?>

		<?php
		$avatar_fallback = '';
		if ( function_exists( 'get_avatar_url' ) ) {
			$avatar_fallback = get_avatar_url( $instructor_id, array( 'size' => 512 ) );
		}
		?>

		<?php
		$teacher_photo_url = '';
		if ( is_array( $teacher_photo ) && isset( $teacher_photo['url'] ) ) {
			$teacher_photo_url = $teacher_photo['url'];
		}
		$teacher_photo_url = $mkh_safe_url( $teacher_photo_url );
		if ( empty( $teacher_photo_url ) ) {
			$teacher_photo_url = $mkh_safe_url( $avatar_fallback );
		}
	/* 	// Teacher Photo section commented out as requested
		// <?php if ( $teacher_photo_url ) : ?>
		// 	<div class="mkh-teacher-profile-section">
		// 		<h3 class="mkh-teacher-profile-section-title"><?php echo esc_html__( 'Teacher Photo', 'mkh-teacher-addon' ); ?></h3>
		// 		<div class="mkh-teacher-profile-photo">
		// 			<img src="<?php echo esc_url( $teacher_photo_url ); ?>" alt="<?php echo esc_attr__( 'Teacher Photo', 'mkh-teacher-addon' ); ?>" />
		// 		</div>
		// 	</div>
		// <?php endif; ?> */

		// Recitation Audio
		$audio_url = '';
		if ( is_array( $recitation_audio ) && isset( $recitation_audio['url'] ) ) {
			$audio_url = $recitation_audio['url'];
		}
		$audio_url = $mkh_safe_url( $audio_url );

		if ( $audio_url ) : ?>
			<div class="mkh-teacher-profile-section">
				<h3 class="mkh-teacher-profile-section-title"><?php echo esc_html__( 'Recitation Audio', 'mkh-teacher-addon' ); ?></h3>
				<div class="mkh-teacher-profile-audio">
					<audio controls>
						<source src="<?php echo esc_url( $audio_url ); ?>" type="audio/mpeg" />
						<?php echo esc_html__( 'Your browser does not support the audio element.', 'mkh-teacher-addon' ); ?>
					</audio>
				</div>
			</div>
		<?php endif; ?>

		<?php
		$video_src = '';
		if ( ! empty( $intro_video_url ) ) {
			$video_src = $intro_video_url;
		} elseif ( is_array( $intro_video_file ) && isset( $intro_video_file['url'] ) ) {
			$video_src = $intro_video_file['url'];
		}
		$video_src = $mkh_safe_url( $video_src );
		if ( $video_src ) : ?>
			<div class="mkh-teacher-profile-section">
				<h3 class="mkh-teacher-profile-section-title"><?php echo esc_html__( 'Intro Video', 'mkh-teacher-addon' ); ?></h3>
				<div class="mkh-teacher-profile-video">
					<?php
					// YouTube.
					if ( strpos( $video_src, 'youtube.com' ) !== false || strpos( $video_src, 'youtu.be' ) !== false ) {
						$video_id = '';
						if ( strpos( $video_src, 'youtube.com/watch?v=' ) !== false ) {
							$video_id = explode( 'v=', $video_src )[1] ?? '';
							$video_id = explode( '&', $video_id )[0] ?? '';
						} elseif ( strpos( $video_src, 'youtu.be/' ) !== false ) {
							$video_id = explode( 'youtu.be/', $video_src )[1] ?? '';
							$video_id = explode( '?', $video_id )[0] ?? '';
						}

						if ( $video_id ) {
							echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . esc_attr( $video_id ) . '" frameborder="0" allowfullscreen></iframe>';
						}
					} elseif ( strpos( $video_src, 'vimeo.com' ) !== false ) {
						$video_id = explode( 'vimeo.com/', $video_src )[1] ?? '';
						$video_id = explode( '?', $video_id )[0] ?? '';
						if ( $video_id ) {
							echo '<iframe src="https://player.vimeo.com/video/' . esc_attr( $video_id ) . '" width="560" height="315" frameborder="0" allowfullscreen></iframe>';
						}
					} else {
						// Direct video file.
						?>
						<video controls width="560" height="315">
							<source src="<?php echo esc_url( $video_src ); ?>" type="video/mp4" />
							<?php echo esc_html__( 'Your browser does not support the video tag.', 'mkh-teacher-addon' ); ?>
						</video>
						<?php
					}
					?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $employment_history && is_array( $employment_history ) ) : ?>
			<?php
			$has_items = false;
			foreach ( $employment_history as $emp ) {
				if ( is_array( $emp ) && ( ! empty( $emp['institute'] ) || ! empty( $emp['position'] ) || ! empty( $emp['from_date'] ) || ! empty( $emp['to_date'] ) || ! empty( $emp['description'] ) ) ) {
					$has_items = true;
					break;
				}
			}
			if ( $has_items ) : ?>
				<div class="mkh-teacher-profile-section mkh-accordion">
					<h3 class="mkh-teacher-profile-section-title mkh-accordion-header" data-accordion="employment">
						<?php echo esc_html__( 'Employment History', 'mkh-teacher-addon' ); ?>
						<span class="mkh-accordion-icon">+</span>
					</h3>
					<div class="mkh-accordion-content mkh-accordion-collapsed" id="employment-content">
						<div class="mkh-teacher-profile-timeline">
							<?php foreach ( $employment_history as $employment ) : ?>
								<?php
								if ( ! is_array( $employment ) ) {
									continue;
								}
								$institute = $employment['institute'] ?? '';
								$position  = $employment['position'] ?? '';
								$from_date  = $employment['from_date'] ?? '';
								$to_date    = $employment['to_date'] ?? '';
								$desc        = $employment['description'] ?? '';
								if ( empty( $institute ) && empty( $position ) && empty( $from_date ) && empty( $to_date ) && empty( $desc ) ) {
									continue;
								}
								?>
								<div class="mkh-teacher-profile-timeline-item">
									<?php if ( ! empty( $institute ) ) : ?>
										<h4 class="mkh-teacher-profile-timeline-title"><?php echo esc_html( $institute ); ?></h4>
									<?php endif; ?>
									<?php if ( ! empty( $position ) ) : ?>
										<div class="mkh-teacher-profile-timeline-position"><?php echo esc_html( $position ); ?></div>
									<?php endif; ?>
									<?php if ( ! empty( $from_date ) || ! empty( $to_date ) ) : ?>
										<div class="mkh-teacher-profile-timeline-date">
											<?php
											if ( ! empty( $from_date ) ) {
												echo esc_html( date( 'F Y', strtotime( (string) $from_date ) ) );
											}
											if ( ! empty( $from_date ) && ! empty( $to_date ) ) {
												echo ' — ';
											}
											if ( ! empty( $to_date ) ) {
												echo esc_html( date( 'F Y', strtotime( (string) $to_date ) ) );
											}
											?>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $desc ) ) : ?>
										<div class="mkh-teacher-profile-timeline-description"><?php echo wp_kses_post( $desc ); ?></div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( $certifications && is_array( $certifications ) ) : ?>
			<?php
			$has_items = false;
			foreach ( $certifications as $cert ) {
				if ( is_array( $cert ) && ! empty( $cert['title'] ) ) {
					$has_items = true;
					break;
				}
			}
			if ( $has_items ) : ?>
				<div class="mkh-teacher-profile-section mkh-accordion">
					<h3 class="mkh-teacher-profile-section-title mkh-accordion-header" data-accordion="certifications">
						<?php echo esc_html__( 'Certifications', 'mkh-teacher-addon' ); ?>
						<span class="mkh-accordion-icon">+</span>
					</h3>
					<div class="mkh-accordion-content mkh-accordion-collapsed" id="certifications-content">
						<div class="mkh-teacher-profile-cards">
							<?php foreach ( $certifications as $cert ) : ?>
								<?php
								if ( ! is_array( $cert ) ) {
									continue;
								}
								$title     = $cert['title'] ?? '';
								$issued_by = $cert['issued_by'] ?? '';
								$year      = $cert['year'] ?? '';
								$file_url  = '';
								if ( isset( $cert['certificate_file'] ) && is_array( $cert['certificate_file'] ) && isset( $cert['certificate_file']['url'] ) ) {
									$file_url = $cert['certificate_file']['url'];
								}
								$file_url = $mkh_safe_url( $file_url );
								if ( empty( $title ) && empty( $issued_by ) && empty( $year ) && empty( $file_url ) ) {
									continue;
								}
								?>
								<div class="mkh-teacher-profile-card">
									<?php if ( ! empty( $title ) ) : ?>
										<h4 class="mkh-teacher-profile-card-title"><?php echo esc_html( $title ); ?></h4>
									<?php endif; ?>

									<?php if ( ! empty( $issued_by ) ) : ?>
										<div class="mkh-teacher-profile-card-meta">
											<span class="mkh-teacher-profile-label"><?php echo esc_html__( 'Issued By', 'mkh-teacher-addon' ); ?>:</span>
											<span class="mkh-teacher-profile-value"><?php echo esc_html( $issued_by ); ?></span>
										</div>
									<?php endif; ?>

									<?php if ( ! empty( $year ) ) : ?>
										<div class="mkh-teacher-profile-card-meta">
											<span class="mkh-teacher-profile-label"><?php echo esc_html__( 'Year', 'mkh-teacher-addon' ); ?>:</span>
											<span class="mkh-teacher-profile-value"><?php echo esc_html( $year ); ?></span>
										</div>
									<?php endif; ?>

									<?php if ( $file_url ) : ?>
										<div class="mkh-teacher-profile-card-action">
											<a href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noopener" class="mkh-teacher-profile-link"><?php echo esc_html__( 'View Certificate', 'mkh-teacher-addon' ); ?></a>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( $ijazah && is_array( $ijazah ) ) : ?>
			<?php
			$has_items = false;
			foreach ( $ijazah as $item ) {
				if ( is_array( $item ) && ! empty( $item['title'] ) ) {
					$has_items = true;
					break;
				}
			}
			if ( $has_items ) : ?>
				<div class="mkh-teacher-profile-section mkh-accordion">
					<h3 class="mkh-teacher-profile-section-title mkh-accordion-header" data-accordion="ijazah">
						<?php echo esc_html__( 'Ijazah', 'mkh-teacher-addon' ); ?>
						<span class="mkh-accordion-icon">+</span>
					</h3>
					<div class="mkh-accordion-content mkh-accordion-collapsed" id="ijazah-content">
						<div class="mkh-teacher-profile-cards">
							<?php foreach ( $ijazah as $ijazah_item ) : ?>
								<?php
								if ( ! is_array( $ijazah_item ) ) {
									continue;
								}
								$title      = $ijazah_item['title'] ?? '';
								$granted_by = $ijazah_item['granted_by'] ?? '';
								$date       = $ijazah_item['date'] ?? '';
								$file_url   = '';
								if ( isset( $ijazah_item['upload_file'] ) && is_array( $ijazah_item['upload_file'] ) && isset( $ijazah_item['upload_file']['url'] ) ) {
									$file_url = $ijazah_item['upload_file']['url'];
								}
								$file_url = $mkh_safe_url( $file_url );
								if ( empty( $title ) && empty( $granted_by ) && empty( $date ) && empty( $file_url ) ) {
									continue;
								}
								?>
								<div class="mkh-teacher-profile-card">
									<?php if ( ! empty( $title ) ) : ?>
										<h4 class="mkh-teacher-profile-card-title"><?php echo esc_html( $title ); ?></h4>
									<?php endif; ?>

									<?php if ( ! empty( $granted_by ) ) : ?>
										<div class="mkh-teacher-profile-card-meta">
											<span class="mkh-teacher-profile-label"><?php echo esc_html__( 'Granted By', 'mkh-teacher-addon' ); ?>:</span>
											<span class="mkh-teacher-profile-value"><?php echo esc_html( $granted_by ); ?></span>
										</div>
									<?php endif; ?>

									<?php if ( ! empty( $date ) ) : ?>
										<div class="mkh-teacher-profile-card-meta">
											<span class="mkh-teacher-profile-label"><?php echo esc_html__( 'Date', 'mkh-teacher-addon' ); ?>:</span>
											<span class="mkh-teacher-profile-value"><?php echo esc_html( date( 'F Y', strtotime( (string) $date ) ) ); ?></span>
										</div>
									<?php endif; ?>

									<?php if ( $file_url ) : ?>
										<div class="mkh-teacher-profile-card-action">
											<a href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noopener" class="mkh-teacher-profile-link"><?php echo esc_html__( 'View Document', 'mkh-teacher-addon' ); ?></a>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<style>
		.mkh-teacher-profile-public {
			margin: 30px 0;
			padding: 30px;
			background: #fff;
			border-radius: 8px;
			box-shadow: 0 2px 8px rgba(0,0,0,0.1);
		}
		.mkh-teacher-profile-section {
			margin-bottom: 30px;
			padding-bottom: 30px;
			border-bottom: 1px solid #e0e0e0;
		}
		.mkh-teacher-profile-section:last-child {
			margin-bottom: 0;
			padding-bottom: 0;
			border-bottom: none;
		}
		.mkh-teacher-profile-section-title {
			font-size: 20px;
			font-weight: 700;
			color: #273044;
			margin-bottom: 20px;
		}
		.mkh-teacher-profile-content {
			font-size: 14px;
			color: #757575;
			line-height: 1.6;
		}
		.mkh-teacher-profile-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
			gap: 20px;
		}
		.mkh-teacher-profile-grid-item {
			padding: 15px;
			background: #f9f9f9;
			border-radius: 4px;
		}
		.mkh-teacher-profile-two-column {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 30px;
		}
		.mkh-teacher-profile-column {
			min-width: 0;
		}
		.mkh-teacher-profile-column:only-child {
			grid-column: 1 / -1;
		}
		.mkh-teacher-profile-label {
			display: block;
			font-size: 13px;
			color: #757575;
			margin-bottom: 5px;
		}
		.mkh-teacher-profile-value {
			font-size: 14px;
			font-weight: 600;
			color: #273044;
		}
		.mkh-teacher-profile-languages,
		.mkh-teacher-profile-skills {
			display: flex;
			flex-wrap: wrap;
			gap: 10px;
		}
		.mkh-teacher-profile-badge {
			display: inline-block;
			padding: 8px 16px;
			background: #273044;
			color: #fff;
			border-radius: 20px;
			font-size: 13px;
			font-weight: 500;
		}
		.mkh-teacher-profile-badge-skill {
			background: #f0f0f0;
			color: #273044;
		}
		.mkh-teacher-profile-check {
			color: #4caf50;
			font-weight: bold;
			margin-right: 5px;
		}
		.mkh-teacher-profile-rate {
			font-size: 24px;
			font-weight: 700;
			color: #273044;
		}
		.mkh-teacher-profile-photo img {
			max-width: 100%;
			height: auto;
			border-radius: 8px;
		}
		.mkh-teacher-profile-audio,
		.mkh-teacher-profile-video {
			width: 100%;
		}
		.mkh-teacher-profile-audio audio,
		.mkh-teacher-profile-video video,
		.mkh-teacher-profile-video iframe {
			width: 100%;
			max-width: 560px;
		}
		.mkh-teacher-profile-timeline {
			position: relative;
			padding-left: 20px;
		}
		.mkh-teacher-profile-timeline-item {
			position: relative;
			padding: 20px;
			margin-bottom: 20px;
			background: #f9f9f9;
			border-radius: 4px;
			border-left: 3px solid #273044;
		}
		.mkh-teacher-profile-timeline-title {
			font-size: 16px;
			font-weight: 600;
			color: #273044;
			margin-bottom: 5px;
		}
		.mkh-teacher-profile-timeline-position {
			font-size: 14px;
			color: #757575;
			margin-bottom: 5px;
		}
		.mkh-teacher-profile-timeline-date {
			font-size: 13px;
			color: #9e9e9e;
			margin-bottom: 10px;
		}
		.mkh-teacher-profile-timeline-description {
			font-size: 14px;
			color: #757575;
			line-height: 1.6;
		}
		.mkh-teacher-profile-cards {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
			gap: 20px;
		}
		.mkh-teacher-profile-card {
			padding: 20px;
			background: #f9f9f9;
			border-radius: 8px;
			border: 1px solid #e0e0e0;
		}
		.mkh-teacher-profile-card-title {
			font-size: 16px;
			font-weight: 600;
			color: #273044;
			margin-bottom: 15px;
		}
		.mkh-teacher-profile-card-meta {
			display: flex;
			justify-content: space-between;
			margin-bottom: 10px;
			font-size: 14px;
		}
		.mkh-teacher-profile-card-action {
			margin-top: 15px;
		}
		.mkh-teacher-profile-link {
			display: inline-block;
			padding: 8px 16px;
			background: #273044;
			color: #fff;
			text-decoration: none;
			border-radius: 4px;
			font-size: 13px;
			font-weight: 500;
			transition: background 0.3s;
		}
		.mkh-teacher-profile-link:hover {
			background: #3ba2f2;
		}

		/* Accordion Styles */
		.mkh-accordion {
			border: 1px solid #e0e0e0;
			border-radius: 8px;
			margin-bottom: 20px;
			overflow: hidden;
		}
		.mkh-accordion-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 15px 20px;
			background: #f9f9f9;
			cursor: pointer;
			transition: background 0.3s;
			margin-bottom: 0;
			user-select: none;
		}
		.mkh-accordion-header:hover {
			background: #f0f0f0;
		}
		.mkh-accordion-header:focus {
			outline: 2px solid #273044;
			outline-offset: -2px;
		}
		.mkh-accordion-icon {
			font-size: 24px;
			font-weight: bold;
			color: #273044;
			transition: transform 0.3s ease;
		}
		.mkh-accordion-header.active .mkh-accordion-icon {
			transform: rotate(45deg);
		}
		.mkh-accordion-content {
			max-height: 0;
			overflow: hidden;
			transition: max-height 0.3s ease-out, padding 0.3s ease;
			padding: 0 20px;
		}
		.mkh-accordion-content.mkh-accordion-expanded {
			max-height: 2000px;
			padding: 20px;
		}
		.mkh-accordion-collapsed {
			display: none;
		}

		@media (max-width: 768px) {
			.mkh-teacher-profile-grid {
				grid-template-columns: 1fr;
			}
			.mkh-teacher-profile-cards {
				grid-template-columns: 1fr;
			}
			.mkh-teacher-profile-two-column {
				grid-template-columns: 1fr;
			}
			.mkh-teacher-profile-public {
				padding: 20px;
				margin: 20px 0;
			}
			.mkh-accordion-header {
				padding: 12px 15px;
			}
			.mkh-accordion-content.mkh-accordion-expanded {
				padding: 15px;
			}
		}
	</style>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const accordionHeaders = document.querySelectorAll('.mkh-accordion-header');

			accordionHeaders.forEach(function(header) {
				header.addEventListener('click', function() {
					const accordionId = this.getAttribute('data-accordion');
					const content = document.getElementById(accordionId + '-content');

					if (!content) return;

					// Toggle current accordion
					const isExpanded = content.classList.contains('mkh-accordion-expanded');

					// Close all other accordions
					document.querySelectorAll('.mkh-accordion-content').forEach(function(otherContent) {
						if (otherContent !== content) {
							otherContent.classList.remove('mkh-accordion-expanded');
							otherContent.classList.add('mkh-accordion-collapsed');
							otherContent.style.maxHeight = '0';
							otherContent.style.padding = '0 20px';
						}
					});

					// Reset all icons
					document.querySelectorAll('.mkh-accordion-header').forEach(function(otherHeader) {
						if (otherHeader !== header) {
							otherHeader.classList.remove('active');
						}
					});

					// Toggle current accordion
					if (isExpanded) {
						content.classList.remove('mkh-accordion-expanded');
						content.classList.add('mkh-accordion-collapsed');
						content.style.maxHeight = '0';
						content.style.padding = '0 20px';
						header.classList.remove('active');
					} else {
						content.classList.remove('mkh-accordion-collapsed');
						content.classList.add('mkh-accordion-expanded');
						content.style.maxHeight = content.scrollHeight + 'px';
						content.style.padding = '20px';
						header.classList.add('active');
					}
				});

				// Keyboard accessibility
				header.addEventListener('keydown', function(e) {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						this.click();
					}
				});
			});
		});
	</script>
	<?php
}

