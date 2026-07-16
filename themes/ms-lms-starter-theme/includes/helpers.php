<?php
// phpcs:ignoreFile

/**
 * Register Menu for Header
 */

use stmLms\Classes\Vendor\LmsUpdateCallbacks;

function stm_menu_import_globalstudy() {

	$locations = get_theme_mod( 'nav_menu_locations' );
	$menus     = wp_get_nav_menus();

	if ( ! empty( $menus ) ) {
		foreach ( $menus as $menu ) {
			$menu_names = array(
				'MS LMS Starter Theme Main Menu',
				'MS Starter Gutenberg Menu',
			);

			if ( is_object( $menu ) && in_array( $menu->name, $menu_names ) ) {
				$locations['ms-lms-starter-theme-main-menu'] = $menu->term_id;
			}
		}
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	if ( empty( get_transient( 'masterstudy_starter_courses_page_generated' ) ) ) {
		set_courses_page();
	}
}

add_action( 'merlin_after_all_import', 'stm_menu_import_globalstudy' );

function set_courses_page() {
	$options = get_option( 'stm_lms_settings', array() );
	if ( empty( $options['courses_page'] ) ) {
		$args  = array(
			'post_type'   => 'page',
			'post_status' => 'publish'
		);
		$pages = get_pages( $args );

		foreach ( $pages as $page ) {
			if ( $page->post_name == 'courses-page' ) {
				$options['courses_page'] = ( $page->ID );
				set_transient( 'masterstudy_starter_courses_page_generated', $page->ID, 7 * DAY_IN_SECONDS ); // Cache for 7 day.
			}
			update_option( 'stm_lms_settings', $options );
		}
	}
}

/**
 * Get Pages by Title
 */
function stm_globalstudy_get_page_id_by_title( $title ) {
	$query = new WP_Query(
		array(
			'post_type'              => 'page',
			'title'                  => $title,
			'post_status'            => 'all',
			'fields'                 => 'ids',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'orderby'                => 'post_date ID',
			'order'                  => 'ASC',
		)
	);
	wp_reset_postdata();
	return ! empty( $query->posts[0] ) ? $query->posts[0] : null;
}

/**
 * Generating Pages for Theme Options
 */
function stm_globalstudy_generate_lms_pages() {
	$args  = array(
		'post_type'   => 'page',
		'post_status' => 'publish'
	);

	$pages = get_pages( $args );

	global $wp_filesystem;
	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}

	$options = get_option( 'stm_lms_settings', array() );

	$target_page_title = 'Courses';

	$found_page = stm_globalstudy_get_page_id_by_title( $target_page_title );

	if ( ! empty( $found_page ) ) {
		$options['courses_page'] = $found_page;
		update_option( 'stm_lms_settings', $options );
	}

	$id       = 'stm_lms_settings';
	$settings = get_option( $id, array() );

	$response = array(
		'reload'  => false,
		'updated' => false,
	);

	$response['reload'] = apply_filters( 'wpcfto_reload_after_save', $id, $settings );

	do_action( 'wpcfto_settings_saved', $id, $settings );

	$response['updated'] = update_option( $id, $settings );
	do_action( 'wpcfto_after_settings_saved', $id, $settings );
}

add_action( 'merlin_after_all_import', 'stm_globalstudy_generate_lms_pages' );

function elementor_set_default_settings_starter() {
	//Elementor Settings
	$active_kit = intval( get_option( 'elementor_active_kit', 0 ) );
	$meta       = get_post_meta( $active_kit, '_elementor_page_settings', true );

	if ( ! empty( $active_kit ) ) {
		$meta                    = ( ! empty( $meta ) ) ? $meta : array();
		$meta['container_width'] = array(
			'size'  => '1230',
			'unit'  => 'px',
			'sizes' => array(),
		);
		update_post_meta( $active_kit, '_elementor_page_settings', $meta );

		if ( class_exists( 'Elementor\Core\Responsive\Responsive' ) ) {
			Elementor\Core\Responsive\Responsive::compile_stylesheet_templates();
		}
	}

	$elementor_cpt_support = array(
		'post',
		'page',
		'stm_courses',
	);
	update_option( 'elementor_cpt_support', $elementor_cpt_support );

	// AddToAny Share Buttons
	$new_options       = array(
		'icon_size'                         => 20,
		'display_in_posts_on_front_page'    => '-1',
		'display_in_posts_on_archive_pages' => '-1',
		'display_in_excerpts'               => '-1',
		'display_in_posts'                  => '-1',
		'display_in_pages'                  => '-1',
		'display_in_attachments'            => '-1',
		'display_in_feed'                   => '-1',
	);
	$custom_post_types = array_values(
		get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			),
			'objects'
		)
	);
	foreach ( $custom_post_types as $custom_post_type_obj ) {
		$placement_name                                     = $custom_post_type_obj->name;
		$new_options[ 'display_in_cpt_' . $placement_name ] = '-1';
	}

	update_option( 'addtoany_options', $new_options );

	global $wpdb;

	$from = trim( 'https://masterstudy.stylemixthemes.com/lms-plugin/' );
	$to   = get_site_url();

	$rows_affected = $wpdb->query(
		$wpdb->prepare(
			"UPDATE {$wpdb->postmeta} 
			SET `meta_value` = REPLACE(`meta_value`, %s, %s) 
			WHERE `meta_key` = '_elementor_data' 
			AND `meta_value` 
			LIKE %s ;",
			array(
				str_replace( '/', '\\\/', $from ),
				str_replace( '/', '\\\/', $to ),
				'[%',
			)
		)
	);

	if ( class_exists( 'Elementor\Core\Responsive\Responsive' ) ) {
		Elementor\Core\Responsive\Responsive::compile_stylesheet_templates();
	}
}

add_action( 'merlin_after_all_import', 'elementor_set_default_settings_starter' );

function starter_ms_gutenberg_parse_data() {
	global $wpdb;

	$result = $wpdb->query(
		"UPDATE {$wpdb->posts}
        SET post_content = REPLACE(REPLACE(REPLACE(post_content, 'u003c', '\\\\u003c'), 'u003e', '\\\\u003e'), 'u0022', '\\\\u0022') 
        WHERE post_type = 'page' AND (post_content LIKE '%u003c%' OR post_content LIKE '%u003e%' OR post_content LIKE '%u0022%')"
	);
}

add_action('merlin_after_all_import', 'starter_ms_gutenberg_parse_data');

function starter_ms_migrate_course_date() {
	if ( get_option( 'ms_starter_lms_course_data_migration_complete', false ) || ! defined( 'STM_LMS_URL' ) ) {
		return;
	}

	LmsUpdateCallbacks::lms_migrate_course_data();
	update_option( 'ms_starter_lms_course_data_migration_complete', true );
}
add_action( 'merlin_after_all_import', 'starter_ms_migrate_course_date' );

add_action('wp_ajax_ms_starter_lms_theme_builder_option', 'ms_starter_lms_theme_builder_option');

function ms_starter_lms_theme_builder_option() {
	if ( isset( $_POST['wpnonce'] ) && wp_verify_nonce( $_POST['wpnonce'], 'merlin_nonce' ) ) {
		$theme_builder_value = sanitize_text_field($_POST['theme_builder_value']);

		update_option( 'ms-lms-starter-theme-builder', $theme_builder_value );

		wp_send_json_success( array( 'data' => 'Data saved successfully' ) );
	} else {
		wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
	}
}
