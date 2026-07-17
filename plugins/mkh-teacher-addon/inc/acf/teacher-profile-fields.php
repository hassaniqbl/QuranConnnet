<?php
/**
 * Teacher Profile ACF Field Group
 *
 * Registers a complete teacher profile field group using ACF PRO.
 * All data is stored as user meta for MasterStudy instructor users.
 *
 * @package MKH_Teacher_Addon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Teacher Profile field group with ACF PRO.
 *
 * This class creates a comprehensive field group for instructor profiles
 * including basic information, about section, teaching skills, employment
 * history, certifications, and ijazah records.
 */
class MKH_Teacher_Profile_ACF {

	/**
	 * Instructor role used for field group visibility.
	 *
	 * MasterStudy LMS uses 'stm_lms_instructor' as the instructor role.
	 * Change this value if your installation uses a different role.
	 *
	 * @var string
	 */
	protected $instructor_role = 'stm_lms_instructor';

	/**
	 * Constructor.
	 *
	 * Hooks into ACF init to register the field group.
	 */
	public function __construct() {
		add_action( 'acf/init', array( $this, 'register_fields' ) );
	}

	/**
	 * Register the field group if ACF is available.
	 *
	 * Only registers if acf_add_local_field_group function exists,
	 * ensuring compatibility with ACF PRO 6.x.
	 */
	public function register_fields() {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		$field_group = array(
			'key'                   => 'group_mkh_teacher_profile',
			'title'                 => esc_html__( 'Teacher Profile', 'mkh-teacher-addon' ),
			'fields'                => $this->get_fields(),
			'location'              => array(
				array(
					array(
						'param'    => 'user_form',
						'operator' => '==',
						'value'    => 'edit',
					),
					array(
						'param'    => 'user_role',
						'operator' => '==',
						'value'    => $this->instructor_role,
					),
				),
				array(
					array(
						'param'    => 'user_form',
						'operator' => '==',
						'value'    => 'edit',
					),
					array(
						'param'    => 'user_role',
						'operator' => '==',
						'value'    => 'administrator',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array(),
			'active'                => true,
			'description'          => esc_html__( 'Extended instructor profile data stored as user meta.', 'mkh-teacher-addon' ),
		);

		acf_add_local_field_group( $field_group );
	}

	/**
	 * Build the field definitions.
	 *
	 * @return array<int, array<string, mixed>> Field definitions.
	 */
	private function get_fields() {
		$fields = array();

		// ========================================
		// SECTION 1: BASIC INFORMATION
		// ========================================

		// Tab for Basic Information
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_tab_basic',
			'name'          => 'mkh_teacher_profile_tab_basic',
			'label'         => esc_html__( 'Basic Information', 'mkh-teacher-addon' ),
			'type'          => 'tab',
			'instructions'  => esc_html__( 'Core instructor profile information.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'placement'     => 'top',
			'endpoint'      => 0,
		);

		// Accordion for Basic Information
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_accordion_basic',
			'name'          => 'mkh_teacher_profile_accordion_basic',
			'label'         => esc_html__( 'Basic Information', 'mkh-teacher-addon' ),
			'type'          => 'accordion',
			'instructions'  => esc_html__( 'Add the basic details teachers use to present themselves.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'open'          => 1,
			'multi_expand'  => 0,
			'endpoint'      => 0,
		);

		// About Teacher Field (moved from About section)
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_about',
			'name'          => 'about_teacher',
			'label'         => esc_html__( 'About Teacher', 'mkh-teacher-addon' ),
			'type'          => 'textarea',
			'instructions'  => esc_html__( 'Write a clear introduction that highlights experience, teaching approach, and character.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '100' ),
			'rows'          => 8,
			'maxlength'     => 1000,
		);


		// Fiqh Field (50% width for side-by-side layout)
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_fiqh',
			'name'          => 'fiqh',
			'label'         => esc_html__( 'Fiqh', 'mkh-teacher-addon' ),
			'type'          => 'select',
			'instructions'  => esc_html__( 'Select the madhhab the teacher follows.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '50' ),
			'choices'       => array(
				'hanafi' => esc_html__( 'Hanafi', 'mkh-teacher-addon' ),
				'shafi'  => esc_html__( 'Shafi', 'mkh-teacher-addon' ),
				'maliki' => esc_html__( 'Maliki', 'mkh-teacher-addon' ),
				'hanbali' => esc_html__( 'Hanbali', 'mkh-teacher-addon' ),
			),
			'default_value' => '',
			'allow_null'    => 1,
			'multiple'      => 0,
			'ui'            => 1,
			'return_format' => 'value',
		);

		// Sect Field (50% width for side-by-side layout)
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_sect',
			'name'          => 'sect',
			'label'         => esc_html__( 'Sect', 'mkh-teacher-addon' ),
			'type'          => 'text',
			'instructions'  => esc_html__( 'Enter the teacher\'s sect or school affiliation.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '50' ),
			'placeholder'   => esc_html__( 'Enter Sect', 'mkh-teacher-addon' ),
		);

		// Teacher Photo Field
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_photo',
			'name'          => 'teacher_photo',
			'label'         => esc_html__( 'Teacher Photo', 'mkh-teacher-addon' ),
			'type'          => 'image',
			'instructions'  => esc_html__( 'Upload an image that will be shown on the teacher profile page.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '50' ),
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'library'       => 'all',
			'mime_types'    => 'jpg,jpeg,png,webp',
		);

		// Recitation Audio Field
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_audio',
			'name'          => 'recitation_audio',
			'label'         => esc_html__( 'Recitation Audio', 'mkh-teacher-addon' ),
			'type'          => 'file',
			'instructions'  => esc_html__( 'Upload an audio file for recitation or sample content.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '50' ),
			'return_format' => 'array',
			'library'       => 'all',
			'mime_types'    => 'mp3,wav,m4a',
		);

		// Intro Video File Field
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_intro_video_file',
			'name'          => 'intro_video_file',
			'label'         => esc_html__( 'Intro Video File', 'mkh-teacher-addon' ),
			'type'          => 'file',
			'instructions'  => esc_html__( 'Upload an introduction video file if you prefer to host it locally.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '50' ),
			'return_format' => 'array',
			'library'       => 'all',
			'mime_types'    => 'mp4,mov,webm',
		);

		// Intro Video URL Field
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_intro_video_url',
			'name'          => 'intro_video_url',
			'label'         => esc_html__( 'Intro Video URL', 'mkh-teacher-addon' ),
			'type'          => 'url',
			'instructions'  => esc_html__( 'Paste a remote video URL if you prefer to use a hosted video instead of a file upload.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '50' ),
			'return_format' => 'url',
		);
		// Languages Field (changed to Multi-Select)
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_languages',
			'name'          => 'languages',
			'label'         => esc_html__( 'Languages', 'mkh-teacher-addon' ),
			'type'          => 'select',
			'instructions'  => esc_html__( 'Select the languages you teach or communicate in.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '50' ),
			'choices'       => array(
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
			),
			'allow_custom'  => 0,
			'multiple'      => 1,
			'ui'            => 1,
			'ajax'          => 0,
			'placeholder'  => esc_html__( 'Select languages', 'mkh-teacher-addon' ),
			'return_format' => 'array',
		);

		// Hourly Rate Field (moved to last position)
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_hourly_rate',
			'name'          => 'hourly_rate',
			'label'         => esc_html__( 'Hourly Rate', 'mkh-teacher-addon' ),
			'type'          => 'number',
			'instructions'  => esc_html__( 'Enter the standard hourly teaching rate for this instructor.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '50' ),
			'prepend'       => '$',
			'min'           => 0,
			'step'          => 1,
			'return_format' => 'float',
		);

		// ========================================
		// SECTION 2: TEACHING SKILLS
		// ========================================

		// Tab for Teaching Skills
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_tab_skills',
			'name'          => 'mkh_teacher_profile_tab_skills',
			'label'         => esc_html__( 'Teaching Skills', 'mkh-teacher-addon' ),
			'type'          => 'tab',
			'instructions'  => esc_html__( 'Skills and specializations offered by the teacher.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'placement'     => 'top',
			'endpoint'      => 0,
		);

		// Accordion for Teaching Skills
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_accordion_skills',
			'name'          => 'mkh_teacher_profile_accordion_skills',
			'label'         => esc_html__( 'Teaching Skills', 'mkh-teacher-addon' ),
			'type'          => 'accordion',
			'instructions'  => esc_html__( 'Select the teaching specializations that best describe this teacher.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'open'          => 1,
			'multi_expand'  => 0,
			'endpoint'      => 0,
		);

		// Teaching Skills Field
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_skills',
			'name'          => 'teaching_skills',
			'label'         => esc_html__( 'Teaching Skills', 'mkh-teacher-addon' ),
			'type'          => 'checkbox',
			'instructions'  => esc_html__( 'Choose the areas the teacher is best known for.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '100' ),
			'choices'       => array(
				'recitation' => esc_html__( 'Recitation', 'mkh-teacher-addon' ),
				'hifz'       => esc_html__( 'Hifz', 'mkh-teacher-addon' ),
				'arabic'     => esc_html__( 'Arabic', 'mkh-teacher-addon' ),
				'tajweed'    => esc_html__( 'Tajweed', 'mkh-teacher-addon' ),
			),
			'allow_custom'  => 0,
			'layout'        => 'vertical',
			'return_format' => 'array',
		);

		// ========================================
		// SECTION 3: EMPLOYMENT HISTORY
		// ========================================

		// Tab for Employment History
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_tab_employment',
			'name'          => 'mkh_teacher_profile_tab_employment',
			'label'         => esc_html__( 'Employment History', 'mkh-teacher-addon' ),
			'type'          => 'tab',
			'instructions'  => esc_html__( 'Professional employment history and previous institutions.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'placement'     => 'top',
			'endpoint'      => 0,
		);

		// Accordion for Employment History
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_accordion_employment',
			'name'          => 'mkh_teacher_profile_accordion_employment',
			'label'         => esc_html__( 'Employment History', 'mkh-teacher-addon' ),
			'type'          => 'accordion',
			'instructions'  => esc_html__( 'Add prior roles and teaching positions in a structured format.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'open'          => 1,
			'multi_expand'  => 0,
			'endpoint'      => 0,
		);

		// Employment History Repeater
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_employment_history',
			'name'          => 'employment_history',
			'label'         => esc_html__( 'Employment History', 'mkh-teacher-addon' ),
			'type'          => 'repeater',
			'instructions'  => esc_html__( 'Add any past schools, institutes, or teaching roles.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '100' ),
			'button_label'  => esc_html__( 'Add Employment', 'mkh-teacher-addon' ),
			'collapsed'     => 'field_mkh_teacher_profile_employment_institute',
			'min'           => 0,
			'layout'        => 'block',
			'sub_fields'    => array(
				array(
					'key'           => 'field_mkh_teacher_profile_employment_institute',
					'name'          => 'institute',
					'label'         => esc_html__( 'Institute', 'mkh-teacher-addon' ),
					'type'          => 'text',
					'instructions'  => esc_html__( 'Name of the institution or organization.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_mkh_teacher_profile_employment_position',
					'name'          => 'position',
					'label'         => esc_html__( 'Position', 'mkh-teacher-addon' ),
					'type'          => 'text',
					'instructions'  => esc_html__( 'Role or title held during this appointment.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_mkh_teacher_profile_employment_from',
					'name'          => 'from_date',
					'label'         => esc_html__( 'From Date', 'mkh-teacher-addon' ),
					'type'          => 'date_picker',
					'instructions'  => esc_html__( 'Start date for this role.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
					'return_format' => 'Y-m-d',
					'display_format' => 'Y-m-d',
				),
				array(
					'key'           => 'field_mkh_teacher_profile_employment_to',
					'name'          => 'to_date',
					'label'         => esc_html__( 'To Date', 'mkh-teacher-addon' ),
					'type'          => 'date_picker',
					'instructions'  => esc_html__( 'End date for this role.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
					'return_format' => 'Y-m-d',
					'display_format' => 'Y-m-d',
				),
				array(
					'key'           => 'field_mkh_teacher_profile_employment_description',
					'name'          => 'description',
					'label'         => esc_html__( 'Description', 'mkh-teacher-addon' ),
					'type'          => 'textarea',
					'instructions'  => esc_html__( 'Add a short summary of the work completed here.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '100' ),
					'rows'          => 4,
				),
			),
		);

		// ========================================
		// SECTION 4: CERTIFICATIONS
		// ========================================

		// Tab for Certifications
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_tab_certifications',
			'name'          => 'mkh_teacher_profile_tab_certifications',
			'label'         => esc_html__( 'Certifications', 'mkh-teacher-addon' ),
			'type'          => 'tab',
			'instructions'  => esc_html__( 'Academic and professional certifications.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'placement'     => 'top',
			'endpoint'      => 0,
		);

		// Accordion for Certifications
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_accordion_certifications',
			'name'          => 'mkh_teacher_profile_accordion_certifications',
			'label'         => esc_html__( 'Certifications', 'mkh-teacher-addon' ),
			'type'          => 'accordion',
			'instructions'  => esc_html__( 'List certifications and accompanying documents.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'open'          => 1,
			'multi_expand'  => 0,
			'endpoint'      => 0,
		);

		// Certifications Repeater
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_certifications',
			'name'          => 'certifications',
			'label'         => esc_html__( 'Certifications', 'mkh-teacher-addon' ),
			'type'          => 'repeater',
			'instructions'  => esc_html__( 'Add notable certifications and qualifications.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '100' ),
			'button_label'  => esc_html__( 'Add Certification', 'mkh-teacher-addon' ),
			'collapsed'     => 'field_mkh_teacher_profile_certification_title',
			'min'           => 0,
			'layout'        => 'block',
			'sub_fields'    => array(
				array(
					'key'           => 'field_mkh_teacher_profile_certification_title',
					'name'          => 'title',
					'label'         => esc_html__( 'Title', 'mkh-teacher-addon' ),
					'type'          => 'text',
					'instructions'  => esc_html__( 'Certification title or credential name.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_mkh_teacher_profile_certification_issued_by',
					'name'          => 'issued_by',
					'label'         => esc_html__( 'Issued By', 'mkh-teacher-addon' ),
					'type'          => 'text',
					'instructions'  => esc_html__( 'Name of the issuing authority or organization.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_mkh_teacher_profile_certification_year',
					'name'          => 'year',
					'label'         => esc_html__( 'Year', 'mkh-teacher-addon' ),
					'type'          => 'number',
					'instructions'  => esc_html__( 'Year the certification was issued.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
					'min'           => 1900,
					'max'           => absint( wp_date( 'Y' ) ),
				),
				array(
					'key'           => 'field_mkh_teacher_profile_certification_file',
					'name'          => 'certificate_file',
					'label'         => esc_html__( 'Certificate File', 'mkh-teacher-addon' ),
					'type'          => 'file',
					'instructions'  => esc_html__( 'Upload an official certificate or credential document.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
					'return_format' => 'array',
					'library'       => 'all',
					'mime_types'    => 'pdf,jpg,jpeg,png',
				),
			),
		);

		// ========================================
		// SECTION 5: IJAZAH
		// ========================================

		// Tab for Ijazah
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_tab_ijazah',
			'name'          => 'mkh_teacher_profile_tab_ijazah',
			'label'         => esc_html__( 'Ijazah', 'mkh-teacher-addon' ),
			'type'          => 'tab',
			'instructions'  => esc_html__( 'Ijazah and religious authorization records.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'placement'     => 'top',
			'endpoint'      => 0,
		);

		// Accordion for Ijazah
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_accordion_ijazah',
			'name'          => 'mkh_teacher_profile_accordion_ijazah',
			'label'         => esc_html__( 'Ijazah', 'mkh-teacher-addon' ),
			'type'          => 'accordion',
			'instructions'  => esc_html__( 'Record ijazah details and supporting documents.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'open'          => 1,
			'multi_expand'  => 0,
			'endpoint'      => 0,
		);

		// Ijazah Repeater
		$fields[] = array(
			'key'           => 'field_mkh_teacher_profile_ijazah',
			'name'          => 'ijazah',
			'label'         => esc_html__( 'Ijazah', 'mkh-teacher-addon' ),
			'type'          => 'repeater',
			'instructions'  => esc_html__( 'Add each ijazah, authorization, or sanad received.', 'mkh-teacher-addon' ),
			'required'      => 0,
			'wrapper'       => array( 'width' => '100' ),
			'button_label'  => esc_html__( 'Add Ijazah', 'mkh-teacher-addon' ),
			'collapsed'     => 'field_mkh_teacher_profile_ijazah_title',
			'min'           => 0,
			'layout'        => 'block',
			'sub_fields'    => array(
				array(
					'key'           => 'field_mkh_teacher_profile_ijazah_title',
					'name'          => 'title',
					'label'         => esc_html__( 'Title', 'mkh-teacher-addon' ),
					'type'          => 'text',
					'instructions'  => esc_html__( 'Name of the ijazah or certificate.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_mkh_teacher_profile_ijazah_granted_by',
					'name'          => 'granted_by',
					'label'         => esc_html__( 'Granted By', 'mkh-teacher-addon' ),
					'type'          => 'text',
					'instructions'  => esc_html__( 'Person or institution that granted the ijazah.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_mkh_teacher_profile_ijazah_date',
					'name'          => 'date',
					'label'         => esc_html__( 'Date', 'mkh-teacher-addon' ),
					'type'          => 'date_picker',
					'instructions'  => esc_html__( 'Date the ijazah was granted.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
					'return_format' => 'Y-m-d',
					'display_format' => 'Y-m-d',
				),
				array(
					'key'           => 'field_mkh_teacher_profile_ijazah_file',
					'name'          => 'upload_file',
					'label'         => esc_html__( 'Upload File', 'mkh-teacher-addon' ),
					'type'          => 'file',
					'instructions'  => esc_html__( 'Attach a supporting document or certificate.', 'mkh-teacher-addon' ),
					'required'      => 0,
					'wrapper'       => array( 'width' => '50' ),
					'return_format' => 'array',
					'library'       => 'all',
					'mime_types'    => 'pdf,jpg,jpeg,png',
				),
			),
		);

		// ========================================
		// FUTURE EXTENSIONS
		// ========================================
		// Additional sections can be added here without refactoring existing code:
		// - Availability Schedule
		// - Trial Class Settings
		// - Teaching Experience
		// - Student Levels
		// - Gender Preference
		// - Country
		// - Time Zone
		// ========================================

		return $fields;
	}
}

// Initialize the class
new MKH_Teacher_Profile_ACF();
