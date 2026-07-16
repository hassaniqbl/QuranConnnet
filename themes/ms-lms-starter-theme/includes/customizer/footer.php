<?php
function ms_lms_starter_customizer_footer( $wp_customize ) {
	$wp_customize->add_panel(
		'ms_lms_starter_footer_panel',
		array(
			'title'    => esc_html__( 'Footer Settings', 'starter-text-domain' ),
			'priority' => 31,
		)
	);

	$wp_customize->add_section(
		'ms_lms_starter_copyright_section',
		array(
			'title'    => esc_html__( 'Copyright', 'starter-text-domain' ),
			'panel'    => 'ms_lms_starter_footer_panel',
			'priority' => 10,
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_copyright_text',
		array(
			'default'           => esc_html__( 'Created by MasterStudy 2024', 'starter-text-domain' ),
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_copyright_text',
		array(
			'label'    => esc_html__( 'Copyright', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_copyright_section',
			'settings' => 'ms_lms_starter_copyright_text',
			'type'     => 'text',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_copyright_text_size',
		array(
			'default'           => '14',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_copyright_text_size',
		array(
			'label'    => esc_html__( 'Font size (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_copyright_section',
			'settings' => 'ms_lms_starter_copyright_text_size',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_copyright_text_color',
		array(
			'default'           => '#ffffff',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_starter_copyright_text_color',
			array(
				'label'    => esc_html__( 'Color', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_copyright_section',
				'settings' => 'ms_lms_starter_copyright_text_color',
			)
		)
	);

	$wp_customize->add_section(
		'ms_lms_starter_socials_section',
		array(
			'title'    => esc_html__( 'Socials', 'starter-text-domain' ),
			'panel'    => 'ms_lms_starter_footer_panel',
			'priority' => 10,
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_socials_twitter',
		array(
			'default'           => esc_html__( '#', 'starter-text-domain' ),
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_socials_twitter',
		array(
			'label'    => esc_html__( 'Twitter', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_socials_section',
			'settings' => 'ms_lms_starter_socials_twitter',
			'type'     => 'text',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_socials_facebook',
		array(
			'default'           => esc_html__( '#', 'starter-text-domain' ),
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_socials_facebook',
		array(
			'label'    => esc_html__( 'Facebook', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_socials_section',
			'settings' => 'ms_lms_starter_socials_facebook',
			'type'     => 'text',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_socials_instagram',
		array(
			'default'           => esc_html__( '#', 'starter-text-domain' ),
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_socials_instagram',
		array(
			'label'    => esc_html__( 'Instagram', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_socials_section',
			'settings' => 'ms_lms_starter_socials_instagram',
			'type'     => 'text',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_socials_youtube',
		array(
			'default'           => esc_html__( '#', 'starter-text-domain' ),
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_socials_youtube',
		array(
			'label'    => esc_html__( 'Youtube', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_socials_section',
			'settings' => 'ms_lms_starter_socials_youtube',
			'type'     => 'text',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_socials_color',
		array(
			'default'           => '#ffffff',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_starter_socials_color',
			array(
				'label'    => esc_html__( 'Color', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_socials_section',
				'settings' => 'ms_lms_starter_socials_color',
			)
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_socials_color_hover',
		array(
			'default'           => '#6ec1e4',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_starter_socials_color_hover',
			array(
				'label'    => esc_html__( 'Hover color', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_socials_section',
				'settings' => 'ms_lms_starter_socials_color_hover',
			)
		)
	);
}

add_action( 'customize_register', 'ms_lms_starter_customizer_footer' );
