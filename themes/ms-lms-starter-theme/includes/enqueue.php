<?php
// phpcs:ignoreFile

if ( ! function_exists( 'starter_styles_and_scripts' ) && ! is_admin() ) {
	function starter_styles_and_scripts() {

		/*Styles*/
		wp_enqueue_style( 'starter-style', get_stylesheet_uri(), array(), STM_THEME_VERSION );
		wp_enqueue_style( 'starter-base', STM_TEMPLATE_URI . '/assets/css/style.css', array(), STM_THEME_VERSION );
		wp_enqueue_style( 'google-fonts', starter_theme_fonts(), array(), STM_THEME_VERSION );

		wp_add_inline_style( 'starter-style', starter_color_styles() );

		wp_register_style( 'starter-404', STM_TEMPLATE_URI . '/assets/css/components/pages/404.css', array(), STM_THEME_VERSION );
		wp_register_style( 'starter-navigation', STM_TEMPLATE_URI . '/assets/css/components/header/navigation.css', array(), STM_THEME_VERSION );
		wp_enqueue_script( 'starter-header', STM_TEMPLATE_URI . '/assets/js/components/header/header.js', array( 'jquery' ), STM_THEME_VERSION, true );
		wp_enqueue_style( 'starter-navigation' );
		wp_register_style( 'starter-single-post', STM_TEMPLATE_URI . '/assets/css/components/post/single/single-post.css', array(), STM_THEME_VERSION );
		wp_register_style( 'starter-posts-list', STM_TEMPLATE_URI . '/assets/css/components/post/archive/posts-list.css', array(), STM_THEME_VERSION );
		wp_register_style( 'starter-search-list', STM_TEMPLATE_URI . '/assets/css/components/pages/search.css', array(), STM_THEME_VERSION );
		wp_register_style( 'starter-comments', STM_TEMPLATE_URI . '/assets/css/components/comments/comments.css', array(), STM_THEME_VERSION );
		wp_register_script( 'starter-comments', STM_TEMPLATE_URI . '/assets/js/comments.js', array( 'jquery' ), STM_THEME_VERSION, true );
		wp_enqueue_style( 'starter-icons', STM_TEMPLATE_URI . '/assets/fonts/ms/style.css', array(), STM_THEME_VERSION );

		if ( is_single() ) {
			wp_enqueue_style( 'starter-single-post' );
		}

		if ( ( is_archive() || is_author() || is_category() || is_tag() || is_home() ) && 'post' === get_post_type() ) {
			wp_enqueue_style( 'starter-posts-list' );
		}

		if ( is_search() ) {
			wp_enqueue_style( 'starter-search-list' );
		}

		if ( is_404() ) {
			wp_enqueue_style( 'starter-404' );
		}

		if ( is_singular() ) {
			wp_enqueue_script( 'starter-comments' );
			wp_enqueue_style( 'starter-comments' );
		}

	}

	add_action( 'wp_enqueue_scripts', 'starter_styles_and_scripts' );
}

function masterstudy_starter_admin_register_script_styles() {
	
	wp_register_style( 'albert-sans', 'https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&display=swap', '', STM_THEME_VERSION );
	wp_register_style( 'masterstudy-starter-wizard', STM_TEMPLATE_URI . '/includes/dashboard/assets/css/wizard.css', '', STM_THEME_VERSION );
	wp_register_style( 'masterstudy-starter-wizard', STM_TEMPLATE_URI . '/includes/dashboard/assets/css/wizard.css', '', STM_THEME_VERSION );
	wp_register_script( 'masterstudy-starter-freemius', STM_TEMPLATE_URI . '/includes/dashboard/assets/js/freemius.js', array( 'jquery' ), STM_THEME_VERSION, true );
	wp_register_script( 'freemius-checkout', 'https://checkout.freemius.com/checkout.min.js', array(), STM_THEME_VERSION, true );
	wp_register_script( 'masterstudy-starter-wizard', STM_TEMPLATE_URI . '/includes/dashboard/assets/js/wizard.js', array( 'jquery' ), STM_THEME_VERSION, true );
	wp_localize_script(
		'masterstudy-starter-wizard',
		'masterstudy_starter_wizard',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'wpnonce' => wp_create_nonce( 'masterstudy_starter_wizard_nonce' ),
		)
	);
	wp_register_script( 'masterstudy-starter-plugins', STM_TEMPLATE_URI . '/includes/dashboard/assets/js/install-plugins.js', array( 'jquery' ), STM_THEME_VERSION, true );
	wp_localize_script(
		'masterstudy-starter-plugins',
		'masterstudy_starter_plugins',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'wpnonce' => wp_create_nonce( 'masterstudy_starter_plugins_nonce' ),
		)
	);
	wp_register_script( 'masterstudy-starter-demo-import', STM_TEMPLATE_URI . '/includes/dashboard/assets/js/demo-import.js', array( 'jquery' ), STM_THEME_VERSION, true );
	wp_localize_script(
		'masterstudy-starter-demo-import',
		'masterstudy_starter_demo',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'wpnonce' => wp_create_nonce( 'masterstudy_starter_demo_nonce' ),
		)
	);
	wp_register_script( 'masterstudy-starter-child-theme', STM_TEMPLATE_URI . '/includes/dashboard/assets/js/child-theme.js', array( 'jquery' ), STM_THEME_VERSION, true );
	wp_localize_script(
		'masterstudy-starter-child-theme',
		'masterstudy_starter_child',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'wpnonce' => wp_create_nonce( 'masterstudy_starter_child_nonce' ),
		)
	);
}

add_action( 'admin_enqueue_scripts', 'masterstudy_starter_admin_register_script_styles' );

if ( ! function_exists( 'starter_move_jquery_into_footer' ) ) {
	function starter_move_jquery_into_footer( $wp_scripts ) {

		if ( is_admin() ) {
			return;
		}

		$wp_scripts->add_data( 'jquery', 'group', 1 );
		$wp_scripts->add_data( 'jquery-core', 'group', 1 );
		$wp_scripts->add_data( 'jquery-migrate', 'group', 1 );
	}
}

add_action( 'wp_default_scripts', 'starter_move_jquery_into_footer' );
function ms_lms_starter_generate_theme_option_css() {

	$inline_preloader_color     = get_theme_mod( 'ms_lms_loader_customizer_color_primary' );
	$outline_color_preloader = get_theme_mod( 'ms_lms_loader_customizer_color_secondary' );

	if ( ! empty( $inline_preloader_color ) ) :
		?>
		<style type="text/css" id="ms_lms_starter-theme-option-css">
			.ms_lms_loader {
				border-color: <?php echo esc_attr__( $outline_color_preloader ); ?> <?php echo esc_attr__( $outline_color_preloader ); ?> transparent transparent;
			}
			.ms_lms_loader::before, .ms_lms_loader::after {
				border-color: transparent transparent<?php echo esc_attr__( $inline_preloader_color ); ?> <?php echo esc_attr__( $inline_preloader_color ); ?>;
			}
		</style>
		<?php

	endif;
}

add_action( 'wp_head', 'ms_lms_starter_generate_theme_option_css' );
