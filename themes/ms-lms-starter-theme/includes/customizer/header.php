<?php
function ms_lms_starter_customizer_header( $wp_customize ) {
	$wp_customize->add_panel(
		'ms_lms_starter_header_panel',
		array(
			'title'    => esc_html__( 'Header Settings', 'starter-text-domain' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_section(
		'ms_lms_starter_global_section',
		array(
			'title'    => esc_html__( 'Global', 'starter-text-domain' ),
			'panel'    => 'ms_lms_starter_header_panel',
			'priority' => 10,
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_logo',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'ms_lms_starter_logo',
			array(
				'label'    => esc_html__( 'Logo', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_global_section',
				'settings' => 'ms_lms_starter_logo',
			)
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_logo_width',
		array(
			'default'           => '226',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_logo_width',
		array(
			'label'    => esc_html__( 'Logo width (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_global_section',
			'settings' => 'ms_lms_starter_logo_width',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_logo_height',
		array(
			'default'           => '40',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_logo_height',
		array(
			'label'    => esc_html__( 'Logo height (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_global_section',
			'settings' => 'ms_lms_starter_logo_height',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_separator_menu',
		array(
			'default'           => '',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_separator_menu',
		array(
			'label'    => esc_html__( 'Menu', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_global_section',
			'settings' => 'ms_lms_starter_separator_menu',
			'type'     => 'hidden',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_menu_link_color',
		array(
			'default'           => '#2A3045',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_starter_menu_link_color',
			array(
				'label'    => esc_html__( 'Link color', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_global_section',
				'settings' => 'ms_lms_starter_menu_link_color',
			)
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_menu_link_hover_color',
		array(
			'default'           => '#61CE70',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_starter_menu_link_hover_color',
			array(
				'label'    => esc_html__( 'Link active and hover color', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_global_section',
				'settings' => 'ms_lms_starter_menu_link_hover_color',
			)
		)
	);

	$wp_customize->add_setting(
		'ms_lms_starter_menu_separator_color',
		array(
			'default'           => '#195EC8',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_hex_color',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'ms_lms_starter_menu_separator_color',
			array(
				'label'    => esc_html__( 'Separator line color', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_global_section',
				'settings' => 'ms_lms_starter_menu_separator_color',
			)
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_menu_link_size',
		array(
			'default'           => '15',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_menu_link_size',
		array(
			'label'    => esc_html__( 'Font size (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_global_section',
			'settings' => 'ms_lms_starter_menu_link_size',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_menu_link_line_height',
		array(
			'default'           => '15',
			'transport'         => 'refresh',
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_menu_link_line_height',
		array(
			'label'    => esc_html__( 'Line height (px)', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_global_section',
			'settings' => 'ms_lms_starter_menu_link_line_height',
			'type'     => 'number',
		)
	);
	$wp_customize->add_setting(
		'ms_lms_starter_menu_link_font_weight',
		array(
			'default'           => '400',
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'ms_lms_starter_menu_link_font_weight',
		array(
			'label'    => esc_html__( 'Font weight', 'starter-text-domain' ),
			'section'  => 'ms_lms_starter_global_section',
			'settings' => 'ms_lms_starter_menu_link_font_weight',
			'type'     => 'select',
			'choices'  => array(
				'400' => esc_html__( 'Normal', 'starter-text-domain' ),
				'500' => esc_html__( 'Medium', 'starter-text-domain' ),
				'600' => esc_html__( 'Semi bold', 'starter-text-domain' ),
				'700' => esc_html__( 'Bold', 'starter-text-domain' ),
			),
		)
	);

	if ( defined( 'MS_LMS_VERSION' ) ) {
		$wp_customize->add_setting(
			'ms_lms_starter__search_placeholder',
			array(
				'default'           => esc_html__( 'Search...', 'starter-text-domain' ),
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter__search_placeholder',
			array(
				'label'    => esc_html__( 'Search placeholder', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_global_search_section',
				'settings' => 'ms_lms_starter__search_placeholder',
				'type'     => 'text',
			)
		);
		$wp_customize->add_section(
			'ms_lms_starter_global_search_section',
			array(
				'title'    => esc_html__( 'Search', 'starter-text-domain' ),
				'panel'    => 'ms_lms_starter_header_panel',
				'priority' => 11,
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_search_presets',
			array(
				'default'           => 'search_button_inside',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_search_presets',
			array(
				'label'    => esc_html__( 'Presets', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_global_search_section',
				'settings' => 'ms_lms_starter_search_presets',
				'type'     => 'select',
				'choices'  => array(
					'search_button_inside'      => esc_html__( 'Button Inside', 'starter-text-domain' ),
					'search_button_inside_left' => esc_html__( 'Button Inside Left', 'starter-text-domain' ),
					'search_button_outside'     => esc_html__( 'Button Outside', 'starter-text-domain' ),
					'search_button_compact'     => esc_html__( 'Compact', 'starter-text-domain' ),
				),
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_search_popup',
			array(
				'default'           => true ? 1 : 0,
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_search_popup',
			array(
				'label'    => esc_html__( 'Show popup', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_global_search_section',
				'settings' => 'ms_lms_starter_search_popup',
				'type'     => 'checkbox',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_search_popup_presets',
			array(
				'default'           => 'without_wrapper',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_search_popup_presets',
			array(
				'label'           => esc_html__( 'Popup presets', 'starter-text-domain' ),
				'section'         => 'ms_lms_starter_global_search_section',
				'settings'        => 'ms_lms_starter_search_popup_presets',
				'type'            => 'select',
				'choices'         => array(
					'without_wrapper' => esc_html__( 'Without Wrapper', 'starter-text-domain' ),
					'with_wrapper'    => esc_html__( 'With Wrapper', 'starter-text-domain' ),
				),
				'active_callback' => 'ms_lms_starter_show_popup_presets_callback',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_search_button_bg',
			array(
				'default'           => '#195EC8',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_search_button_bg',
				array(
					'label'    => esc_html__( 'Button background color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_global_search_section',
					'settings' => 'ms_lms_starter_search_button_bg',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_search_button_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_search_button_color',
				array(
					'label'    => esc_html__( 'Button color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_global_search_section',
					'settings' => 'ms_lms_starter_search_button_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_search_category',
			array(
				'default'           => '',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_search_category',
			array(
				'label'    => esc_html__( 'Categories', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_global_search_section',
				'settings' => 'ms_lms_starter_search_category',
				'type'     => 'checkbox',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_search_category_button_color',
			array(
				'default'           => '#4D5E6F',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_search_category_button_color',
				array(
					'label'    => esc_html__( 'Category button color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_global_search_section',
					'settings' => 'ms_lms_starter_search_category_button_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_search_category_button_bg_color',
			array(
				'default'           => '#EEF1F7',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_search_category_button_bg_color',
				array(
					'label'    => esc_html__( 'Category button bg color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_global_search_section',
					'settings' => 'ms_lms_starter_search_category_button_bg_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_search_category_dropdown_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_search_category_dropdown_color',
				array(
					'label'    => esc_html__( 'Category dropdown color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_global_search_section',
					'settings' => 'ms_lms_starter_search_category_dropdown_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_search_category_dropdown_bg_color',
			array(
				'default'           => '#227AFF',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_search_category_dropdown_bg_color',
				array(
					'label'    => esc_html__( 'Category dropdown bg color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_global_search_section',
					'settings' => 'ms_lms_starter_search_category_dropdown_bg_color',
				)
			)
		);

		$wp_customize->add_section(
			'ms_lms_starter_authorization_section',
			array(
				'title'    => esc_html__( 'Authorization', 'starter-text-domain' ),
				'panel'    => 'ms_lms_starter_header_panel',
				'priority' => 11,
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_separator_heading',
			array(
				'default'           => '',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_separator_heading',
			array(
				'label'    => esc_html__( 'Account', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_authorization_section',
				'settings' => 'ms_lms_starter_separator_heading',
				'type'     => 'hidden',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_icon_bg_color',
			array(
				'default'           => '#227AFF',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_login_icon_bg_color',
				array(
					'label'    => esc_html__( 'Icon background color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_authorization_section',
					'settings' => 'ms_lms_starter_login_icon_bg_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_icon_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_login_icon_color',
				array(
					'label'    => esc_html__( 'Icon color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_authorization_section',
					'settings' => 'ms_lms_starter_login_icon_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_icon_width',
			array(
				'default'           => '50',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_login_icon_width',
			array(
				'label'    => esc_html__( 'Icon width (px)', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_authorization_section',
				'settings' => 'ms_lms_starter_login_icon_width',
				'type'     => 'number',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_icon_height',
			array(
				'default'           => '50',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_login_icon_height',
			array(
				'label'    => esc_html__( 'Icon height (px)', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_authorization_section',
				'settings' => 'ms_lms_starter_login_icon_height',
				'type'     => 'number',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_icon_size',
			array(
				'default'           => '14',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_login_icon_size',
			array(
				'label'    => esc_html__( 'Icon size (px)', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_authorization_section',
				'settings' => 'ms_lms_starter_login_icon_size',
				'type'     => 'number',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_link_text',
			array(
				'default'           => esc_html__( 'login/sign up', 'starter-text-domain' ),
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_login_link_text',
			array(
				'label'    => esc_html__( 'Link text', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_authorization_section',
				'settings' => 'ms_lms_starter_login_link_text',
				'type'     => 'text',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_link_size',
			array(
				'default'           => '12',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_login_link_size',
			array(
				'label'    => esc_html__( 'Link font size (px)', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_authorization_section',
				'settings' => 'ms_lms_starter_login_link_size',
				'type'     => 'number',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_link_color',
			array(
				'default'           => '#2A3045',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_login_link_color',
				array(
					'label'    => esc_html__( 'Link color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_authorization_section',
					'settings' => 'ms_lms_starter_login_link_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_link_hover_color',
			array(
				'default'           => '#2A3045',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_login_link_hover_color',
				array(
					'label'    => esc_html__( 'Link hover color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_authorization_section',
					'settings' => 'ms_lms_starter_login_link_hover_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_separator',
			array(
				'default'           => '',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_separator',
			array(
				'label'       => '',
				'section'     => 'ms_lms_starter_authorization_section',
				'settings'    => 'ms_lms_starter_separator',
				'type'        => 'hidden',
				'description' => '<hr>',
			)
		);

		$wp_customize->add_setting(
			'ms_lms_starter_separator_sing_in_heading',
			array(
				'default'           => '',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_separator_sing_in_heading',
			array(
				'label'    => esc_html__( 'Login', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_authorization_section',
				'settings' => 'ms_lms_starter_separator_sing_in_heading',
				'type'     => 'hidden',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_sing_in_icon_size',
			array(
				'default'           => '14',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_login_sing_in_icon_size',
			array(
				'label'    => esc_html__( 'Icon size (px)', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_authorization_section',
				'settings' => 'ms_lms_starter_login_sing_in_icon_size',
				'type'     => 'number',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_sing_in_icon_color',
			array(
				'default'           => '#385bce',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_login_sing_in_icon_color',
				array(
					'label'    => esc_html__( 'Icon color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_authorization_section',
					'settings' => 'ms_lms_starter_login_sing_in_icon_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_sing_in_text_size',
			array(
				'default'           => '14',
				'transport'         => 'refresh',
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			'ms_lms_starter_login_sing_in_text_size',
			array(
				'label'    => esc_html__( 'Text size (px)', 'starter-text-domain' ),
				'section'  => 'ms_lms_starter_authorization_section',
				'settings' => 'ms_lms_starter_login_sing_in_text_size',
				'type'     => 'number',
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_sing_in_text_color',
			array(
				'default'           => '#333333',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_login_sing_in_text_color',
				array(
					'label'    => esc_html__( 'Text color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_authorization_section',
					'settings' => 'ms_lms_starter_login_sing_in_text_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_sing_in_text_hover_color',
			array(
				'default'           => '#385bce',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_login_sing_in_text_hover_color',
				array(
					'label'    => esc_html__( 'Text hover color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_authorization_section',
					'settings' => 'ms_lms_starter_login_sing_in_text_hover_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_sing_in_bg_color',
			array(
				'default'           => '#f0f4fa',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_login_sing_in_bg_color',
				array(
					'label'    => esc_html__( 'Background color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_authorization_section',
					'settings' => 'ms_lms_starter_login_sing_in_bg_color',
				)
			)
		);
		$wp_customize->add_setting(
			'ms_lms_starter_login_sing_in_bg_hover_color',
			array(
				'default'           => '#f0f4fa',
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ms_lms_starter_login_sing_in_bg_hover_color',
				array(
					'label'    => esc_html__( 'Background hover color', 'starter-text-domain' ),
					'section'  => 'ms_lms_starter_authorization_section',
					'settings' => 'ms_lms_starter_login_sing_in_bg_hover_color',
				)
			)
		);
	}
}

add_action( 'customize_register', 'ms_lms_starter_customizer_header' );

function ms_lms_starter_show_popup_presets_callback( $control ): bool {
	return $control->manager->get_setting( 'ms_lms_starter_search_popup' )->value() === true;
}
