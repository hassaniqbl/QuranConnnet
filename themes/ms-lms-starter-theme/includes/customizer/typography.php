<?php
function ms_lms_starter_customizer_typography( $wp_customize ) {
	$google_fonts_array = json_decode( wp_remote_retrieve_body( wp_remote_get( MS_LMS_STARTER_THEME_URI . '/assets/fonts/google/google-fonts.json' ) ), true );

	if ( isset( $google_fonts_array['items'] ) && is_array( $google_fonts_array['items'] ) ) {
		$font_choices = array_reduce(
			$google_fonts_array['items'],
			function ( $result, $font ) {
				$result[ $font['family'] ] = $font['family'];
				return $result;
			},
			array()
		);
	} else {
		$font_choices = array();
	}

	$font_choices = array( 'default' => 'Default' ) + $font_choices;

	$wp_customize->add_panel(
		'ms_lms_starter_typography_panel',
		array(
			'title'    => esc_html__( 'Typography', 'starter-text-domain' ),
			'priority' => 32,
		)
	);

	$wp_customize->add_section(
		'ms_lms_starter_typography_section',
		array(
			'title'    => esc_html__( 'Body Font', 'starter-text-domain' ),
			'panel'    => 'ms_lms_starter_typography_panel',
			'priority' => 10,
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_body_font',
		array(
			'default'           => 'Montserrat',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_body_font',
		array(
			'label'    => esc_html__( 'Font Family', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_section',
			'settings' => 'ms_lms_starter_body_font',
			'type'     => 'select',
			'choices'  => $font_choices,
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_body_font_size',
		array(
			'default'           => '14',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_body_font_size',
		array(
			'label'    => esc_html__( 'Font size (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_section',
			'settings' => 'ms_lms_starter_body_font_size',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_body_font_weight',
		array(
			'default'           => '400',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_body_font_weight',
		array(
			'label'    => esc_html__( 'Font weight', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_section',
			'settings' => 'ms_lms_starter_body_font_weight',
			'type'     => 'select',
			'choices'  => array(
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900',
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_body_line_height',
		array(
			'default'   => '24',
			'transport' => 'refresh',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_body_line_height',
		array(
			'label'    => esc_html__( 'Line height (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_section',
			'settings' => 'ms_lms_starter_body_line_height',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_body_text_transform',
		array(
			'default'           => 'none',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_body_text_transform',
		array(
			'label'    => esc_html__( 'Text Transform', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_section',
			'settings' => 'ms_lms_starter_body_text_transform',
			'type'     => 'select',
			'choices'  => array(
				'none'       => esc_html__( 'None', 'starter-text-domain' ),
				'capitalize' => esc_html__( 'Capitalize', 'starter-text-domain' ),
				'lowercase'  => esc_html__( 'Lowercase', 'starter-text-domain' ),
				'uppercase'  => esc_html__( 'Uppercase', 'starter-text-domain' ),
			),
		)
	);

	$wp_customize->add_section(
		'ms_lms_starter_typography_heading_section',
		array(
			'title'    => esc_html__( 'H1 -H6', 'starter-text-domain' ),
			'panel'    => 'ms_lms_starter_typography_panel',
			'priority' => 20,
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_heading_font',
		array(
			'default'           => 'Montserrat',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_heading_font',
		array(
			'label'    => esc_html__( 'Font Family', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_heading_font',
			'type'     => 'select',
			'choices'  => $font_choices,
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_separator_h1',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_separator_h1',
		array(
			'label'    => esc_html__( 'H1', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_separator_h1',
			'type'     => 'hidden',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h1_font_size',
		array(
			'default'           => '50',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h1_font_size',
		array(
			'label'    => esc_html__( 'Font size (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h1_font_size',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h1_font_weight',
		array(
			'default'           => '700',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h1_font_weight',
		array(
			'label'    => esc_html__( 'Font weight', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h1_font_weight',
			'type'     => 'select',
			'choices'  => array(
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900',
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h1_line_height',
		array(
			'default'           => '50',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h1_line_height',
		array(
			'label'    => esc_html__( 'Line height (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h1_line_height',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h1_text_transform',
		array(
			'default'           => 'none',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h1_text_transform',
		array(
			'label'    => esc_html__( 'Text Transform', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h1_text_transform',
			'type'     => 'select',
			'choices'  => array(
				'none'       => esc_html__( 'None', 'starter-text-domain' ),
				'capitalize' => esc_html__( 'Capitalize', 'starter-text-domain' ),
				'lowercase'  => esc_html__( 'Lowercase', 'starter-text-domain' ),
				'uppercase'  => esc_html__( 'Uppercase', 'starter-text-domain' ),
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h1_separator_after',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h1_separator_after',
		array(
			'label'       => '',
			'section'     => 'ms_lms_starter_typography_heading_section',
			'settings'    => 'ms_lms_starter_h1_separator_after',
			'type'        => 'hidden',
			'description' => '<hr>',
		)
	);

	$wp_customize->add_setting(
		'ms_lms_starter_separator_h2',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_separator_h2',
		array(
			'label'    => esc_html__( 'H2', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_separator_h2',
			'type'     => 'hidden',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h2_font_size',
		array(
			'default'           => '32',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h2_font_size',
		array(
			'label'    => esc_html__( 'Font size (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h2_font_size',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h2_font_weight',
		array(
			'default'           => '700',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h2_font_weight',
		array(
			'label'    => esc_html__( 'Font weight', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h2_font_weight',
			'type'     => 'select',
			'choices'  => array(
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900',
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h2_line_height',
		array(
			'default'           => '34',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h2_line_height',
		array(
			'label'    => esc_html__( 'Line height (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h2_line_height',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h2_text_transform',
		array(
			'default'           => 'none',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h2_text_transform',
		array(
			'label'    => esc_html__( 'Text Transform', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h2_text_transform',
			'type'     => 'select',
			'choices'  => array(
				'none'       => esc_html__( 'None', 'starter-text-domain' ),
				'capitalize' => esc_html__( 'Capitalize', 'starter-text-domain' ),
				'lowercase'  => esc_html__( 'Lowercase', 'starter-text-domain' ),
				'uppercase'  => esc_html__( 'Uppercase', 'starter-text-domain' ),
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h2_separator_after',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h2_separator_after',
		array(
			'label'       => '',
			'section'     => 'ms_lms_starter_typography_heading_section',
			'settings'    => 'ms_lms_starter_h2_separator_after',
			'type'        => 'hidden',
			'description' => '<hr>',
		)
	);

	$wp_customize->add_setting(
		'ms_lms_starter_separator_h3',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_separator_h3',
		array(
			'label'    => esc_html__( 'H3', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_separator_h3',
			'type'     => 'hidden',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h3_font_size',
		array(
			'default'           => '30',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h3_font_size',
		array(
			'label'    => esc_html__( 'Font size (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h3_font_size',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h3_font_weight',
		array(
			'default'           => '700',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h3_font_weight',
		array(
			'label'    => esc_html__( 'Font weight', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h3_font_weight',
			'type'     => 'select',
			'choices'  => array(
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900',
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h3_line_height',
		array(
			'default'           => '32',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h3_line_height',
		array(
			'label'    => esc_html__( 'Line height (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h3_line_height',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h3_text_transform',
		array(
			'default'           => 'none',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h3_text_transform',
		array(
			'label'    => esc_html__( 'Text Transform', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h3_text_transform',
			'type'     => 'select',
			'choices'  => array(
				'none'       => esc_html__( 'None', 'starter-text-domain' ),
				'capitalize' => esc_html__( 'Capitalize', 'starter-text-domain' ),
				'lowercase'  => esc_html__( 'Lowercase', 'starter-text-domain' ),
				'uppercase'  => esc_html__( 'Uppercase', 'starter-text-domain' ),
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h3_separator_after',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h3_separator_after',
		array(
			'label'       => '',
			'section'     => 'ms_lms_starter_typography_heading_section',
			'settings'    => 'ms_lms_starter_h3_separator_after',
			'type'        => 'hidden',
			'description' => '<hr>',
		)
	);

	$wp_customize->add_setting(
		'ms_lms_starter_separator_h4',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_separator_h4',
		array(
			'label'    => esc_html__( 'H4', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_separator_h4',
			'type'     => 'hidden',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h4_font_size',
		array(
			'default'           => '28',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h4_font_size',
		array(
			'label'    => esc_html__( 'Font size (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h4_font_size',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h4_font_weight',
		array(
			'default'           => '700',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h4_font_weight',
		array(
			'label'    => esc_html__( 'Font weight', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h4_font_weight',
			'type'     => 'select',
			'choices'  => array(
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900',
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h4_line_height',
		array(
			'default'           => '30',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h4_line_height',
		array(
			'label'    => esc_html__( 'Line height (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h4_line_height',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h4_text_transform',
		array(
			'default'           => 'none',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h4_text_transform',
		array(
			'label'    => esc_html__( 'Text Transform', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h4_text_transform',
			'type'     => 'select',
			'choices'  => array(
				'none'       => esc_html__( 'None', 'starter-text-domain' ),
				'capitalize' => esc_html__( 'Capitalize', 'starter-text-domain' ),
				'lowercase'  => esc_html__( 'Lowercase', 'starter-text-domain' ),
				'uppercase'  => esc_html__( 'Uppercase', 'starter-text-domain' ),
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h4_separator_after',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h4_separator_after',
		array(
			'label'       => '',
			'section'     => 'ms_lms_starter_typography_heading_section',
			'settings'    => 'ms_lms_starter_h4_separator_after',
			'type'        => 'hidden',
			'description' => '<hr>',
		)
	);

	$wp_customize->add_setting(
		'ms_lms_starter_separator_h5',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_separator_h5',
		array(
			'label'    => esc_html__( 'H5', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_separator_h5',
			'type'     => 'hidden',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h5_font_size',
		array(
			'default'           => '24',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h5_font_size',
		array(
			'label'    => esc_html__( 'Font size (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h5_font_size',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h5_font_weight',
		array(
			'default'           => '700',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h5_font_weight',
		array(
			'label'    => esc_html__( 'Font weight', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h5_font_weight',
			'type'     => 'select',
			'choices'  => array(
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900',
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h5_line_height',
		array(
			'default'           => '26',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h5_line_height',
		array(
			'label'    => esc_html__( 'Line height (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h5_line_height',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h5_text_transform',
		array(
			'default'           => 'none',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h5_text_transform',
		array(
			'label'    => esc_html__( 'Text Transform', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h5_text_transform',
			'type'     => 'select',
			'choices'  => array(
				'none'       => esc_html__( 'None', 'starter-text-domain' ),
				'capitalize' => esc_html__( 'Capitalize', 'starter-text-domain' ),
				'lowercase'  => esc_html__( 'Lowercase', 'starter-text-domain' ),
				'uppercase'  => esc_html__( 'Uppercase', 'starter-text-domain' ),
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h5_separator_after',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h5_separator_after',
		array(
			'label'       => '',
			'section'     => 'ms_lms_starter_typography_heading_section',
			'settings'    => 'ms_lms_starter_h5_separator_after',
			'type'        => 'hidden',
			'description' => '<hr>',
		)
	);

	$wp_customize->add_setting(
		'ms_lms_starter_separator_h6',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_separator_h6',
		array(
			'label'    => esc_html__( 'H6', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_separator_h6',
			'type'     => 'hidden',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h6_font_size',
		array(
			'default'           => '18',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h6_font_size',
		array(
			'label'    => esc_html__( 'Font size (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h6_font_size',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h6_font_weight',
		array(
			'default'           => '700',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h6_font_weight',
		array(
			'label'    => esc_html__( 'Font weight', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h6_font_weight',
			'type'     => 'select',
			'choices'  => array(
				'100' => '100',
				'200' => '200',
				'300' => '300',
				'400' => '400',
				'500' => '500',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900',
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h6_line_height',
		array(
			'default'           => '20',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h6_line_height',
		array(
			'label'    => esc_html__( 'Line height (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h6_line_height',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h6_text_transform',
		array(
			'default'           => 'none',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h6_text_transform',
		array(
			'label'    => esc_html__( 'Text Transform', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_typography_heading_section',
			'settings' => 'ms_lms_starter_h6_text_transform',
			'type'     => 'select',
			'choices'  => array(
				'none'       => esc_html__( 'None', 'starter-text-domain' ),
				'capitalize' => esc_html__( 'Capitalize', 'starter-text-domain' ),
				'lowercase'  => esc_html__( 'Lowercase', 'starter-text-domain' ),
				'uppercase'  => esc_html__( 'Uppercase', 'starter-text-domain' ),
			),
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_h6_separator_after',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_h6_separator_after',
		array(
			'label'       => '',
			'section'     => 'ms_lms_starter_typography_heading_section',
			'settings'    => 'ms_lms_starter_h6_separator_after',
			'type'        => 'hidden',
			'description' => '<hr>',
		)
	);
}

add_action( 'customize_register', 'ms_lms_starter_customizer_typography' );
