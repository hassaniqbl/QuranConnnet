<?php
define( 'STM_THEME_VERSION', ( WP_DEBUG ) ? time() : wp_get_theme()->get( 'Version' ) );
define( 'STM_THEME_PATH', dirname( __FILE__ ) );
define( 'STM_INCLUDES_PATH', STM_THEME_PATH . '/includes' );
define( 'STM_TEMPLATE_URI', get_template_directory_uri() );
define( 'STM_TEMPLATE_DIR', get_template_directory() );
define( 'STM_SERVICE_URL', 'https://microservices.stylemixthemes.com/changelog/' );

require_once STM_INCLUDES_PATH . '/custom-functions.php';
require_once STM_INCLUDES_PATH . '/enqueue.php';
require_once STM_INCLUDES_PATH . '/comments.php';
require_once STM_INCLUDES_PATH . '/theme-config.php';
require_once STM_INCLUDES_PATH . '/helpers.php';

// Instructor filter functionality
require_once STM_TEMPLATE_DIR . '/inc/instructors-filter-data.php';
require_once STM_TEMPLATE_DIR . '/inc/instructors-filter-query.php';
require_once STM_TEMPLATE_DIR . '/inc/instructors-archive-override.php';
require_once STM_TEMPLATE_DIR . '/inc/instructors-filter-enqueue.php';
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Constants
 */
define( 'MS_LMS_STARTER_THEME_DIR', get_parent_theme_file_path() );
define( 'MS_LMS_STARTER_THEME_URI', get_parent_theme_file_uri() );
define( 'MS_LMS_STARTER_THEME_VERSION', ( WP_DEBUG ) ? time() : wp_get_theme()->get( 'Version' ) );

if ( get_theme_mod( 'ms_lms_starter_preloader' ) ) {
	function ms_lms_starter_footer_content() {
		get_template_part( 'templates/modals/preloader' );
	}

	add_action( 'wp_body_open', 'ms_lms_starter_footer_content' );

}
/** Register and Enqueue Styles */
function stm_ms_lms_theme_register_styles() {
	$version = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? time() : wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'stm_lms_starter_theme_css_frontend', MS_LMS_STARTER_THEME_URI . '/assets/css/style.css', array(), $version );
	wp_enqueue_style( 'masterstudy-starter-elementor-icons', MS_LMS_STARTER_THEME_URI . '/assets/fonts/elementor/icons/style.css', null, STM_THEME_VERSION, 'all' );
}
add_action( 'wp_enqueue_scripts', 'stm_ms_lms_theme_register_styles' );

function stm_ms_lms_theme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'stm_ms_lms_theme_add_woocommerce_support' );

function stm_ms_lms_theme_remove_shop_breadcrumbs() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}
add_action( 'template_redirect', 'stm_ms_lms_theme_remove_shop_breadcrumbs' );

add_action(
	'admin_init',
	function () {
		delete_transient( 'elementor_activation_redirect' );
	}
);
function stm_ms_lms_theme_woocommerce_image_size_gallery_thumbnail( $size ) {
	return array(
		'width'  => 470,
		'height' => 470,
		'crop'   => 1,
	);
}
add_filter( 'woocommerce_get_image_size_single', 'stm_ms_lms_theme_woocommerce_image_size_gallery_thumbnail' );

/* Including plugins TGM */
require_once MS_LMS_STARTER_THEME_DIR . '/includes/tgm/theme-required-plugins.php';

/* Including Customizer */
require_once MS_LMS_STARTER_THEME_DIR . '/includes/customizer/color.php';
require_once MS_LMS_STARTER_THEME_DIR . '/includes/customizer/layout.php';
require_once MS_LMS_STARTER_THEME_DIR . '/includes/customizer/header.php';
require_once MS_LMS_STARTER_THEME_DIR . '/includes/customizer/footer.php';
require_once MS_LMS_STARTER_THEME_DIR . '/includes/customizer/blog.php';
require_once MS_LMS_STARTER_THEME_DIR . '/includes/customizer/typography.php';

/* Merlin Demo Import*/
require_once MS_LMS_STARTER_THEME_DIR . '/includes/upgrade/classes/class-starter-loader.php';

//Freemius licenses activation
if ( ! function_exists( 'masterstudy_starter_fs' ) && file_exists( STM_THEME_PATH . '/freemius/start.php' ) ) {
	function masterstudy_starter_fs() {
		global $masterstudy_starter_fs;

		if ( ! isset( $masterstudy_starter_fs ) ) {
			require_once STM_THEME_PATH . '/freemius/start.php';

			$masterstudy_starter_fs = fs_dynamic_init(
				array(
					'id'              => '16465',
					'slug'            => 'masterstudy-templates',
					'premium_slug'    => 'masterstudy-templates-premium',
					'type'            => 'theme',
					'public_key'      => 'pk_1a5d1ac7060675e58a0ad41379efc',
					'is_premium'      => true,
					'is_premium_only' => true,
					'has_addons'      => false,
					'has_paid_plans'  => true,
					'has_affiliation' => 'all',
					'menu'            => array(
						'slug'       => 'masterstudy-starter-freemius',
						'first-path' => 'admin.php?page=masterstudy-starter-options',
						'support'    => false,
					),
					'is_live'         => true,
				)
			);
		}

		return $masterstudy_starter_fs;
	}

	masterstudy_starter_fs();
	do_action( 'masterstudy_starter_fs_loaded' );
}

//Freemius licenses check
function masterstudy_starter_fs_verify() {
	if ( function_exists( 'masterstudy_starter_fs' ) ) {
		return masterstudy_starter_fs()->is__premium_only() && masterstudy_starter_fs()->can_use_premium_code();
	}

	return false;
}

add_action(
	'after_setup_theme',
	function() {
		if ( is_admin() && 'yes' !== get_option( 'masterstudy_starter_redirect_done' ) ) {
			update_option( 'masterstudy_starter_redirect_done', 'yes' );
			wp_safe_redirect( admin_url( 'admin.php?page=masterstudy-starter-options' ) );
			exit;
		}
	},
	1
);

add_action(
	'after_switch_theme',
	function() {
		delete_option( 'masterstudy_starter_redirect_done' );
	}
);

/**
 * Include dashboard.php
 */
if ( is_admin() ) {
	require_once MS_LMS_STARTER_THEME_DIR . '/includes/dashboard/init.php';
	require_once MS_LMS_STARTER_THEME_DIR . '/includes/dashboard/resources/includes/license-key.php';
	require_once MS_LMS_STARTER_THEME_DIR . '/includes/dashboard/resources/includes/system-status.php';
	require_once MS_LMS_STARTER_THEME_DIR . '/includes/dashboard/resources/includes/changelog.php';
	require_once MS_LMS_STARTER_THEME_DIR . '/includes/dashboard/wizard/includes/functions.php';
	require_once MS_LMS_STARTER_THEME_DIR . '/includes/dashboard/wizard/includes/after_demo_import.php';

	Masterstudy_Templates_Changelog::init();
}
