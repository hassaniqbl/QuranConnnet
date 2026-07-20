<?php
/**
 * Instructors Filter Data Helper
 * 
 * Retrieves available filter values from instructor ACF profiles
 * 
 * @package MS_LMS_Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get available filter data from instructor profiles
 * 
 * @return array Filter data with available options
 */
function mkh_get_instructors_filter_data() {
	// Get all instructors
	$instructors = get_users(
		array(
			'role' => STM_LMS_Instructor::role(),
			'number' => -1,
		)
	);

	// Initialize filter data arrays
	$filter_data = array(
		'gender'    => array(),
		'ijazah'    => array(),
		'subjects'  => array(),
		'languages' => array(),
		'countries' => array(),
		'timezones' => array(),
		'min_rate'  => 0,
		'max_rate'  => 0,
		'ratings'   => array(
			array( 'rate' => 5, 'label' => esc_html__( '5 Stars', 'mkh-teacher-addon' ) ),
			array( 'rate' => 4, 'label' => esc_html__( '4★ & Above', 'mkh-teacher-addon' ) ),
			array( 'rate' => 3, 'label' => esc_html__( '3★ & Above', 'mkh-teacher-addon' ) ),
		),
	);

	$hourly_rates = array();

	foreach ( $instructors as $instructor ) {
		$user_id = $instructor->ID;

		// Gender
		$gender = get_field( 'mkh_gender', 'user_' . $user_id );
		if ( ! empty( $gender ) && ! isset( $filter_data['gender'][ $gender ] ) ) {
			$filter_data['gender'][ $gender ] = mkh_get_gender_label( $gender );
		}

		// Ijazah (collect titles from repeater)
		$ijazah = get_field( 'ijazah', 'user_' . $user_id );
		if ( ! empty( $ijazah ) && is_array( $ijazah ) ) {
			foreach ( $ijazah as $ijazah_item ) {
				if ( ! empty( $ijazah_item['title'] ) && ! isset( $filter_data['ijazah'][ $ijazah_item['title'] ] ) ) {
					$filter_data['ijazah'][ $ijazah_item['title'] ] = $ijazah_item['title'];
				}
			}
		}

		// Subjects (teaching skills)
		$teaching_skills = get_field( 'teaching_skills', 'user_' . $user_id );
		if ( ! empty( $teaching_skills ) && is_array( $teaching_skills ) ) {
			foreach ( $teaching_skills as $skill ) {
				// Handle both return formats: 'value' (string) and 'array' (['value' => ..., 'label' => ...])
				$skill_value = is_array( $skill ) ? $skill['value'] : $skill;
				if ( ! empty( $skill_value ) && ! isset( $filter_data['subjects'][ $skill_value ] ) ) {
					$filter_data['subjects'][ $skill_value ] = mkh_get_skill_label( $skill_value );
				}
			}
		}

		// Also check for any custom subjects field if it exists
		$custom_subjects = get_field( 'mkh_subjects', 'user_' . $user_id );
		if ( ! empty( $custom_subjects ) && is_array( $custom_subjects ) ) {
			foreach ( $custom_subjects as $subject ) {
				// Handle both return formats
				$subject_value = is_array( $subject ) ? $subject['value'] : $subject;
				if ( ! empty( $subject_value ) && ! isset( $filter_data['subjects'][ $subject_value ] ) ) {
					$filter_data['subjects'][ $subject_value ] = $subject_value;
				}
			}
		}

		// Languages
		$languages = get_field( 'languages', 'user_' . $user_id );
		if ( ! empty( $languages ) && is_array( $languages ) ) {
			foreach ( $languages as $language ) {
				// Handle both return formats: 'value' (string) and 'array' (['value' => ..., 'label' => ...])
				$language_value = is_array( $language ) ? $language['value'] : $language;
				if ( ! empty( $language_value ) && ! isset( $filter_data['languages'][ $language_value ] ) ) {
					$filter_data['languages'][ $language_value ] = mkh_get_language_label( $language_value );
				}
			}
		}

		// Country
		$country = get_field( 'mkh_country', 'user_' . $user_id );
		if ( ! empty( $country ) && ! isset( $filter_data['countries'][ $country ] ) ) {
			$filter_data['countries'][ $country ] = mkh_get_country_name( $country );
		}

		// Timezone
		$timezone = get_field( 'mkh_timezone', 'user_' . $user_id );
		if ( ! empty( $timezone ) && ! isset( $filter_data['timezones'][ $timezone ] ) ) {
			$filter_data['timezones'][ $timezone ] = $timezone;
		}

		// Hourly Rate
		$hourly_rate = get_field( 'hourly_rate', 'user_' . $user_id );
		if ( ! empty( $hourly_rate ) && is_numeric( $hourly_rate ) ) {
			$hourly_rates[] = floatval( $hourly_rate );
		}
	}

	// Calculate min/max rates - fixed to 0-100 range as required
	$filter_data['min_rate'] = 0;
	$filter_data['max_rate'] = 100;

	return $filter_data;
}

/**
 * Get gender label
 * 
 * @param string $gender Gender value.
 * @return string Gender label.
 */
function mkh_get_gender_label( $gender ) {
	$labels = array(
		'male'             => esc_html__( 'Male', 'mkh-teacher-addon' ),
		'female'           => esc_html__( 'Female', 'mkh-teacher-addon' ),
		'prefer_not_to_say' => esc_html__( 'Prefer not to say', 'mkh-teacher-addon' ),
	);

	return isset( $labels[ $gender ] ) ? $labels[ $gender ] : $gender;
}

/**
 * Get skill label
 * 
 * @param string $skill Skill value.
 * @return string Skill label.
 */
function mkh_get_skill_label( $skill ) {
	$labels = array(
		'recitation' => esc_html__( 'Recitation', 'mkh-teacher-addon' ),
		'hifz'       => esc_html__( 'Hifz', 'mkh-teacher-addon' ),
		'arabic'     => esc_html__( 'Arabic', 'mkh-teacher-addon' ),
		'tajweed'    => esc_html__( 'Tajweed', 'mkh-teacher-addon' ),
	);

	return isset( $labels[ $skill ] ) ? $labels[ $skill ] : $skill;
}

/**
 * Get language label
 * 
 * @param string $language Language value.
 * @return string Language label.
 */
function mkh_get_language_label( $language ) {
	$labels = array(
		'english'    => esc_html__( 'English', 'mkh-teacher-addon' ),
		'arabic'     => esc_html__( 'Arabic', 'mkh-teacher-addon' ),
		'urdu'       => esc_html__( 'Urdu', 'mkh-teacher-addon' ),
		'hindi'      => esc_html__( 'Hindi', 'mkh-teacher-addon' ),
		'punjabi'    => esc_html__( 'Punjabi', 'mkh-teacher-addon' ),
		'turkish'    => esc_html__( 'Turkish', 'mkh-teacher-addon' ),
		'bengali'    => esc_html__( 'Bengali', 'mkh-teacher-addon' ),
		'persian'    => esc_html__( 'Persian', 'mkh-teacher-addon' ),
		'pashto'     => esc_html__( 'Pashto', 'mkh-teacher-addon' ),
		'malay'      => esc_html__( 'Malay', 'mkh-teacher-addon' ),
		'indonesian' => esc_html__( 'Indonesian', 'mkh-teacher-addon' ),
		'french'     => esc_html__( 'French', 'mkh-teacher-addon' ),
	);

	return isset( $labels[ $language ] ) ? $labels[ $language ] : $language;
}

/**
 * Get country name from code
 * 
 * @param string $country_code Country code.
 * @return string Country name.
 */
function mkh_get_country_name( $country_code ) {
	if ( empty( $country_code ) ) {
		return '';
	}

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
		'VN' => 'Vietnam',
		'VG' => 'Virgin Islands, British',
		'VI' => 'Virgin Islands, U.S.',
		'WF' => 'Wallis and Futuna',
		'EH' => 'Western Sahara',
		'YE' => 'Yemen',
		'ZM' => 'Zambia',
		'ZW' => 'Zimbabwe',
	);

	return isset( $countries[ $country_code ] ) ? $countries[ $country_code ] : $country_code;
}
