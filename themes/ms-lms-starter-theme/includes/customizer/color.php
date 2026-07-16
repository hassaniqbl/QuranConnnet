<?php
function ms_lms_starter_customizer_colors( $wp_customize ) {
	$wp_customize->add_section(
		'ms_lms_starter_colors_settings_section',
		array(
			'title'    => esc_html__( 'Colors', 'starter-text-domain' ),
			'priority' => 29,
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_primary_color',
		array(
			'default'           => '#303441',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_starter_primary_color',
			array(
				'label'    => esc_html__( 'Primary', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_colors_settings_section',
				'settings' => 'ms_lms_starter_primary_color',
			)
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_accent_color',
		array(
			'default'           => '#234dd4',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_starter_accent_color',
			array(
				'label'    => esc_html__( 'Accent', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_colors_settings_section',
				'settings' => 'ms_lms_starter_accent_color',
			)
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_accent_second_color',
		array(
			'default'           => '#43C370',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_starter_accent_second_color',
			array(
				'label'    => esc_html__( 'Second Accent', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_colors_settings_section',
				'settings' => 'ms_lms_starter_accent_second_color',
			)
		)
	);
}

add_action( 'customize_register', 'ms_lms_starter_customizer_colors' );
