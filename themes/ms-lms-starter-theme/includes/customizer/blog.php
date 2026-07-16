<?php
function ms_lms_starter_customizer_blog( $wp_customize ) {
	$wp_customize->add_panel(
		'ms_lms_starter_blog_panel',
		array(
			'title'    => esc_html__( 'Blog Settings', 'starter-text-domain' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_section(
		'ms_lms_starter_blog_section',
		array(
			'title'    => esc_html__( 'Global', 'starter-text-domain' ),
			'priority' => 10,
			'panel'    => 'ms_lms_starter_blog_panel',
		)
	);

	$wp_customize->add_setting(
		'ms_lms_starter_blog_skin',
		array(
			'default'           => 'classic',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'ms_lms_starter_blog_skin',
		array(
			'label'   => esc_html__( 'Skin', 'starter-text-domain' ),
			'section' => 'ms_lms_starter_blog_section',
			'type'    => 'select',
			'choices' => array(
				'list'  => esc_html__( 'Classic', 'starter-text-domain' ),
				'cards' => esc_html__( 'Cards', 'starter-text-domain' ),
			),
		)
	);

	$wp_customize->add_setting(
		'ms_lms_starter_blog_skin_columns',
		array(
			'default'           => '3',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'ms_lms_starter_blog_skin_columns',
		array(
			'label'           => esc_html__( 'Skin', 'starter-text-domain' ),
			'section'         => 'ms_lms_starter_blog_section',
			'type'            => 'select',
			'choices'         => array(
				'1' => esc_html__( '1', 'starter-text-domain' ),
				'2' => esc_html__( '2', 'starter-text-domain' ),
				'3' => esc_html__( '3', 'starter-text-domain' ),
				'4' => esc_html__( '4', 'starter-text-domain' ),
			),
			'active_callback' => function() use ( $wp_customize ) {
				$skin = $wp_customize->get_setting( 'ms_lms_starter_blog_skin' )->value();
				return 'cards' === $skin;
			},
		)
	);
}

add_action( 'customize_register', 'ms_lms_starter_customizer_blog' );
