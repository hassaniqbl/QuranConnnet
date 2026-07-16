<?php

//Required plugins
add_filter(
	'masterstudy_starter_theme_plugins',
	function() {
		function get_plugin_status( $plugin_slug ) {
			$plugin_file = WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_slug . '.php';

			if ( file_exists( $plugin_file ) ) {
				if ( is_plugin_active( $plugin_slug . '/' . $plugin_slug . '.php' ) ) {
					return 'Activated';
				} else {
					return 'Installed but not activated';
				}
			}

			return 'Not installed';
		}

		$plugins = array(
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/ms.png',
				'title'       => 'MasterStudy LMS - WordPress LMS Plugin',
				'slug'        => 'masterstudy-lms-learning-management-system',
				'description' => get_plugin_status( 'masterstudy-lms-learning-management-system' ),
			),
			array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/mailchimp.png',
				'title'       => 'MC4WP: Mailchimp for WordPress',
				'slug'        => 'mailchimp-for-wp',
				'description' => get_plugin_status( 'mailchimp-for-wp' ),
			),
		);

		if ( 'gutenberg' !== get_option( 'ms-lms-starter-theme-builder' ) ) {
			$plugins[] = array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/elementor.png',
				'title'       => 'Elementor',
				'slug'        => 'elementor',
				'description' => get_plugin_status( 'elementor' ),
			);

			$plugins[] = array(
				'image'       => STM_TEMPLATE_URI . '/includes/dashboard/assets/images/elementor-hfe.png',
				'title'       => 'Elementor Header & Footer Builder',
				'slug'        => 'header-footer-elementor',
				'description' => get_plugin_status( 'header-footer-elementor' ),
			);
		}

		return $plugins;
	}
);

//Loading templates using ajax
add_action( 'wp_ajax_masterstudy_starter_demo_options', 'masterstudy_starter_demo_options' );
add_action( 'wp_ajax_masterstudy_starter_demo_options', 'masterstudy_starter_demo_options' );

function masterstudy_starter_demo_options() {
	check_ajax_referer( 'masterstudy_starter_wizard_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to export users.', 'masterstudy_starter' ) );
	}

	$demo    = sanitize_text_field( wp_unslash( $_POST['demo'] ?? '' ) );
	$builder = sanitize_text_field( wp_unslash( $_POST['builder'] ?? '' ) );
	if ( ! empty( $demo ) ) {
		update_option( 'masterstudy_starter_demo_name', $demo );
	}

	if ( ! empty( $builder ) ) {
		update_option( 'ms-lms-starter-theme-builder', $builder );
	}

	wp_die();
}

//Loading templates using ajax
add_action( 'wp_ajax_masterstudy_starter_template', 'masterstudy_starter_template' );
add_action( 'wp_ajax_nopriv_masterstudy_starter_template', 'masterstudy_starter_template' );

function masterstudy_starter_template() {
	check_ajax_referer( 'masterstudy_starter_wizard_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to export users.', 'masterstudy_starter' ) );
	}

	ob_start();
	require_once MS_LMS_STARTER_THEME_DIR . '/includes/dashboard/wizard/templates/' . sanitize_text_field( $_POST['template'] ) . '.php';
	$content = ob_get_clean();

	echo wp_kses_post( $content );
	wp_die();
}

// Install and activate plugins
add_action( 'wp_ajax_masterstudy_starter_plugins_install', 'masterstudy_starter_plugins_install' );
add_action( 'wp_ajax_nopriv_masterstudy_starter_plugins_install', 'masterstudy_starter_plugins_install' );

function masterstudy_starter_plugins_install() {
	check_ajax_referer( 'masterstudy_starter_plugins_nonce', 'nonce' );

	if ( ! current_user_can( 'install_plugins' ) ) {
		wp_die( esc_html__( 'You do not have permission to install plugins.', 'masterstudy_starter' ) );
	}

	$plugin_slug = isset( $_POST['plugin_slug'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_slug'] ) ) : '';

	if ( empty( $plugin_slug ) ) {
		wp_send_json_error( esc_html__( 'No plugin specified for installation.', 'masterstudy_starter' ) );
	}

	$plugin_file = $plugin_slug . '/' . $plugin_slug . '.php';

	if ( is_plugin_active( $plugin_file ) ) {
		wp_send_json_success( esc_html__( 'Plugin is already active.', 'masterstudy_starter' ) );
	}

	if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
		$result = install_plugin( $plugin_slug );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}
	}

	activate_plugin( $plugin_file );

	wp_send_json_success( esc_html__( 'Plugin installed and activated successfully.', 'masterstudy_starter' ) );
}

function install_plugin( $slug ) {
	include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

	$api = plugins_api(
		'plugin_information',
		array(
			'slug'   => $slug,
			'fields' => array(
				'sections' => false,
			),
		)
	);

	if ( is_wp_error( $api ) ) {
		return $api;
	}

	$upgrader = new Plugin_Upgrader( new WP_Ajax_Upgrader_Skin() );
	return $upgrader->install( $api->download_link );
}

// Install and activate demos
add_action( 'wp_ajax_masterstudy_starter_demo_install', 'masterstudy_starter_demo_install' );
add_action( 'wp_ajax_nopriv_masterstudy_starter_demo_install', 'masterstudy_starter_demo_install' );

function masterstudy_starter_demo_install() {
	check_ajax_referer( 'masterstudy_starter_demo_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to install demo.', 'masterstudy_starter' ) );
	}

	$builder = get_option( 'ms-lms-starter-theme-builder' );
	$demo    = get_option( 'masterstudy_starter_demo_name' );
	$type    = sanitize_text_field( wp_unslash( $_POST['type'] ?? '' ) );

	if ( 'elementor-builder' === $builder ) {
		$builder = 'elementor';
	}

	if ( 'gutenberg-builder' === $builder ) {
		$builder = 'gutenberg';
	}

	$builder = sanitize_file_name( (string) $builder );
	$demo    = sanitize_file_name( (string) $demo );

	if ( ! $demo || ! $type || ! $builder ) {
		wp_send_json_error( __( 'Required data is missing.', 'masterstudy_starter' ) );
	}

	switch ( $type ) {
		case 'demo_content':
			$xml_content = false;

			if ( defined( 'STM_DEV_MODE' ) && STM_DEV_MODE ) {
				$local_xml_path = trailingslashit( STM_TEMPLATE_DIR ) . 'includes/demo/' . $builder . '/' . $demo . '.xml';

				if ( ! file_exists( $local_xml_path ) || ! is_readable( $local_xml_path ) ) {
					wp_send_json_error( __( 'Local demo file is missing or unreadable.', 'masterstudy_starter' ) );
					break;
				}

				$xml_content = file_get_contents( $local_xml_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			} else {
				$xml_file_url = 'https://masterstudy.stylemixthemes.com/starter-demo-files/' . $builder . '/' . $demo . '.xml';
				$response     = wp_remote_get( $xml_file_url );

				if ( is_wp_error( $response ) ) {
					wp_send_json_error( __( 'Failed to download demo content.', 'masterstudy_starter' ) );
					break;
				}

				$xml_content = wp_remote_retrieve_body( $response );
			}

			if ( empty( $xml_content ) ) {
				wp_send_json_error( __( 'Demo content is empty or unavailable.', 'masterstudy_starter' ) );
				break;
			}

			$upload_dir = wp_upload_dir();

			if ( ! empty( $upload_dir['error'] ) ) {
				wp_send_json_error( __( 'Failed to prepare upload directory for demo import.', 'masterstudy_starter' ) );
				break;
			}

			wp_mkdir_p( $upload_dir['path'] );

			$temp_file_path = $upload_dir['path'] . '/' . $demo . '-demo-content.xml';
			$file_written   = file_put_contents( $temp_file_path, $xml_content ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents

			if ( false === $file_written || ! file_exists( $temp_file_path ) ) {
				wp_send_json_error( __( 'Failed to save demo content locally.', 'masterstudy_starter' ) );
				break;
			}

			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				define( 'WP_LOAD_IMPORTERS', true );
			}

			require_once STM_INCLUDES_PATH . '/dashboard/wizard/includes/wordpress-importer/class-stm-wp-import.php';

			$wp_import                    = new STM_WP_Import();
			$wp_import->theme             = 'MS Starter';
			$wp_import->layout            = $demo;
			$wp_import->builder           = $builder;
			$wp_import->fetch_attachments = true;

			ob_start();
			$wp_import->import( $temp_file_path );
			ob_end_clean();

			do_action(
				'masterstudy_starter_after_demo_import',
				$wp_import->processed_posts,
				$wp_import->processed_terms,
				$wp_import->processed_menu_items,
			);

			if ( file_exists( $temp_file_path ) ) {
				unlink( $temp_file_path );
			}

			update_option( 'masterstudy_starter_demo_activated', $demo );

			wp_send_json_success( __( 'Demo content imported successfully.', 'masterstudy_starter' ) );

			break;

		case 'theme_settings':
			$customizer_file_url = STM_TEMPLATE_URI . '/includes/demo/starter_customizer.dat';

			$response = wp_remote_get( $customizer_file_url );

			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				wp_send_json_error( __( 'Customizer settings file not found or failed to retrieve.', 'masterstudy_starter' ) );
				break;
			}

			$file_content    = wp_remote_retrieve_body( $response );
			$customizer_data = maybe_unserialize( $file_content );

			starter_theme_activation();

			if ( is_array( $customizer_data ) && ! empty( $customizer_data ) ) {
				foreach ( $customizer_data as $mod => $value ) {
					set_theme_mod( $mod, $value );
				}

				$homepage_query = new WP_Query(
					array(
						'post_type'   => 'page',
						'title'       => 'Home',
						'post_status' => 'publish',
						'numberposts' => 1,
					)
				);

				$homepage = ! empty( $homepage_query->posts ) ? $homepage_query->posts[0] : null;

				if ( $homepage ) {
					update_option( 'page_on_front', $homepage->ID );
					update_option( 'show_on_front', 'page' );
				}

				wp_send_json_success( __( 'Theme settings imported successfully.', 'masterstudy_starter' ) );
			} else {
				wp_send_json_error( __( 'Failed to decode Customizer data.', 'masterstudy_starter' ) );
			}

			break;

		case 'lms_options':
			$response = wp_remote_get( STM_TEMPLATE_URI . '/includes/demo/options.json' );

			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				wp_send_json_error( __( 'LMS options JSON file not found or failed to retrieve.', 'masterstudy_starter' ) );
				break;
			}

			$current_settings = get_option( 'stm_lms_settings', array() );

			if ( ! is_array( $current_settings ) ) {
				$current_settings = array();
			}

			$lms_settings = array_merge( $current_settings, json_decode( wp_remote_retrieve_body( $response ), true ) );

			update_option( 'stm_lms_settings', $lms_settings );

			wp_send_json_success( __( 'LMS options imported and settings updated successfully.', 'masterstudy_starter' ) );

			break;

		default:
			wp_send_json_error( __( 'Unknown import type.', 'masterstudy_starter' ) );
	}

	wp_send_json_error( false );
}

// Install and activate child theme
add_action( 'wp_ajax_masterstudy_starter_child_theme_install', 'masterstudy_starter_child_theme_install' );
add_action( 'wp_ajax_nopriv_masterstudy_starter_child_theme_install', 'masterstudy_starter_child_theme_install' );

function masterstudy_starter_child_theme_install() {
	check_ajax_referer( 'masterstudy_starter_child_nonce', 'nonce' );

	$theme_url  = 'https://masterstudy.stylemixthemes.com/starter-demo-files/ms-lms-starter-theme-child.zip';
	$theme_slug = 'ms-lms-starter-theme-child';
	$theme_dir  = get_theme_root() . '/' . $theme_slug;

	if ( is_dir( $theme_dir ) ) {
		switch_theme( $theme_slug );
		wp_send_json_success(
			array(
				'message' => __( 'Child theme already exists and has been activated.', 'masterstudy_starter' ),
			)
		);
		return;
	}

	$temp_file = download_url( $theme_url );

	if ( is_wp_error( $temp_file ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Error downloading file: ', 'masterstudy_starter' ) . $temp_file->get_error_message(),
			)
		);
		return;
	}

	$zip_path = get_theme_root() . '/ms-lms-starter-theme-child.zip';

	if ( ! rename( $temp_file, $zip_path ) ) {
		wp_send_json_error(
			array(
				'message' => __( 'Error moving archive to the themes folder.', 'masterstudy_starter' ),
			)
		);
		return;
	}

	$unzip_result = unzip_file( $zip_path, get_theme_root() );

	if ( is_wp_error( $unzip_result ) ) {
		unlink( $zip_path );
		wp_send_json_error(
			array(
				'message' => __( 'Error while unzipping: ', 'masterstudy_starter' ) . $unzip_result->get_error_message(),
			)
		);
		return;
	}

	unlink( $zip_path );
	switch_theme( $theme_slug );

	wp_send_json_success(
		array(
			'message' => __( 'Child theme already exists and has been activated.', 'masterstudy_starter' ),
		)
	);
}

// Reset demo
add_action( 'wp_ajax_masterstudy_starter_template_reset', 'masterstudy_starter_template_reset' );
add_action( 'wp_ajax_nopriv_masterstudy_starter_template_reset', 'masterstudy_starter_template_reset' );

function masterstudy_starter_template_reset() {
	check_ajax_referer( 'masterstudy_starter_wizard_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to export users.', 'masterstudy_starter' ) );
	}

	// Remove all posts marked with 'masterstudy_starter_demo' meta
	$all_posts = get_posts(
		array(
			'numberposts' => -1,
			'post_type'   => 'any',
			'post_status' => 'any',
			'meta_key'    => 'masterstudy_starter_demo',
		)
	);

	foreach ( $all_posts as $post ) {
		wp_delete_post( $post->ID, true );
	}

	// Remove all terms marked with 'masterstudy_starter_demo' meta
	$taxonomies = get_taxonomies( array(), 'objects' );
	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy->name,
				'hide_empty' => false,
			)
		);

		foreach ( $terms as $term ) {
			$meta_value = get_term_meta( $term->term_id, 'masterstudy_starter_demo', true );
			if ( $meta_value ) {
				wp_delete_term( $term->term_id, $taxonomy->name );
			}
		}
	}

	// Remove all menu items marked with 'masterstudy_starter_demo' meta
	$menu_items = get_posts(
		array(
			'numberposts' => -1,
			'post_type'   => 'nav_menu_item',
			'post_status' => 'any',
			'meta_key'    => 'masterstudy_starter_demo',
		)
	);

	foreach ( $menu_items as $menu_item ) {
		wp_delete_post( $menu_item->ID, true );
	}

	// Remove options and widgets
	update_option( 'show_on_front', 'posts' );

	wp_send_json_success( 'Database was reset successfully!' );
}

// Custom icons for Elementor
if ( defined( 'ELEMENTOR_VERSION' ) ) {
	function masterstudy_starter_theme_icons( $tabs = array() ) {
		$new_icons['masterstudy-theme-icons'] = array(
			'name'          => 'masterstudy-icons',
			'label'         => 'Masterstudy Icons',
			'url'           => '',
			'enqueue'       => '',
			'prefix'        => '',
			'displayPrefix' => '',
			'labelIcon'     => 'fa-icon-stm_icon_ms_logo',
			'ver'           => '0.1.0',
			'fetchJson'     => get_template_directory_uri() . '/assets/fonts/elementor/elementor-widget-icons.json?v=' . time(),
		);

		return array_merge( $tabs, $new_icons );
	}

	add_action( 'elementor/icons_manager/additional_tabs', 'masterstudy_starter_theme_icons', 9999999, 1 );

	function masterstudy_starter_icons_font() {
		wp_enqueue_style( 'masterstudy-starter-elementor-icons', get_template_directory_uri() . '/assets/fonts/elementor/icons/style.css', null, STM_THEME_VERSION, 'all' );
	}

	add_action( 'elementor/editor/before_enqueue_scripts', 'masterstudy_starter_icons_font', 99999, 1 );
}

function getAnnualPriceFromAPI() {
	$response = wp_remote_get( 'https://stylemixthemes.com/api/freemius/masterstudy-templates.json' );
	if ( is_wp_error( $response ) ) {
		return 'Error: Unable to fetch data from API.';
	}

	$jsonContent = wp_remote_retrieve_body( $response );
	if ( empty( $jsonContent ) ) {
		return 'Error: Empty response from API.';
	}

	$data = json_decode( $jsonContent, true );
	if ( null === $data ) {
		return 'Error: Unable to decode JSON data.';
	}

	$defaultPlanId = $data['default_plan_id'] ?? null;
	if ( $defaultPlanId && isset( $data['plans'][ $defaultPlanId ] ) ) {
		$pricing       = $data['plans'][ $defaultPlanId ]['pricing'][0];
		$annualPrice   = $pricing['annual_price'] ?? '';
		$lifetimePrice = $pricing['lifetime_price'] ?? '';
		$sale          = check_data_sale();

		if ( $sale ) {
			return array(
				'annual_price'   => ' $<s>' . esc_html( $annualPrice ) . '</s> $' . esc_html( number_format( $annualPrice * 0.75, 0, '.', '' ) ),
				'lifetime_price' => ' $' . $lifetimePrice,
			);
		} else {
			return array(
				'annual_price'   => ' $' . $annualPrice,
				'lifetime_price' => ' $' . $lifetimePrice,
			);
		}
	}

	return 'Plan or pricing information not found.';
}

function check_data_sale() {
	$current_date = new DateTime();
	$start_date   = new DateTime( '2024-12-19' );
	$end_date     = new DateTime( '2025-01-11' );

	return $current_date >= $start_date && $current_date <= $end_date;
}

add_filter(
	'stm_add_post_meta_key',
	function ( $key ) {
		return wp_slash( $key );
	},
	10,
	1
);

add_filter(
	'stm_add_post_meta_value',
	function ( $value ) {
		return function_exists( 'wp_slash_strings_only' )
			? wp_slash_strings_only( $value )
			: wp_slash( $value );
	},
	10,
	1
);
