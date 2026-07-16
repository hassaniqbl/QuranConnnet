<?php
function ms_lms_starter_customizer_register( $wp_customize ) {
	$wp_customize->add_panel(
		'ms_lms_starter_layout_panel',
		array(
			'title'    => esc_html__( 'Layout', 'starter-text-domain' ),
			'priority' => 28,
		)
	);

	$wp_customize->add_section(
		'ms_lms_starter_layout_section',
		array(
			'title'    => esc_html__( 'Preloader', 'starter-text-domain' ),
			'panel'    => 'ms_lms_starter_layout_panel',
			'priority' => 10,
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_preloader',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_preloader',
		array(
			'label'    => esc_html__( 'Enable Preloader', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_layout_section',
			'settings' => 'ms_lms_starter_preloader',
			'type'     => 'checkbox',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_loader_customizer_color_primary',
		array(
			'default'           => '#04bfbf',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_loader_customizer_color_primary',
			array(
				'label'    => esc_html__( 'Preloader Outline Color', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_layout_section',
				'settings' => 'ms_lms_loader_customizer_color_primary',
			)
		)
	);
	$wp_customize->add_setting(
		'ms_lms_loader_customizer_color_secondary',
		array(
			'default'           => '#45ace0',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_loader_customizer_color_secondary',
			array(
				'label'    => esc_html__( 'Preloader Inline Color', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_layout_section',
				'settings' => 'ms_lms_loader_customizer_color_secondary',
			)
		)
	);

	$wp_customize->add_section(
		'ms_lms_starter_container_section',
		array(
			'title'    => esc_html__( 'Container', 'starter-text-domain' ),
			'panel'    => 'ms_lms_starter_layout_panel',
			'priority' => 10,
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_container_width',
		array(
			'default'           => '1200',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_container_width',
		array(
			'label'    => esc_html__( 'Width (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_container_section',
			'settings' => 'ms_lms_starter_container_width',
			'type'     => 'number',
		)
	);
}

add_action( 'customize_register', 'ms_lms_starter_customizer_register' );
