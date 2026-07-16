<?php
/**
 * Content width
 */
if ( ! isset( $content_width ) ) {
	$content_width = 900;
}

/**
 * Add Favicon
 */
function starter_get_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	echo '<link rel="shortcut icon" type="image/x-icon" href="' . esc_url( get_stylesheet_directory_uri() . '/favicon.png' ) . '" />' . "\n";

}

add_action( 'wp_head', 'starter_get_favicon' );

/**
 * Setup settings
 */
if ( ! function_exists( 'starter_setup' ) ) {
	function starter_setup() {
		load_theme_textdomain( 'starter-text-domain', get_template_directory() . '/languages' );
		add_theme_support( 'widgets' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		register_nav_menus(
			array(
				'ms-lms-starter-theme-main-menu' => esc_html__( 'Header menu', 'starter-text-domain' ),
			)
		);
	}
}

add_action( 'after_setup_theme', 'starter_setup' );

/**
 * Add ping back url
 */
function starter_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

add_action( 'wp_head', 'starter_pingback_header' );

/**
 * Custom excerpt size
 */
function starter_minimize_word( $word, $length = '40', $affix = '...' ) {
	if ( ! empty( intval( $length ) ) ) {
		$word_length = mb_strlen( $word );
		if ( $word_length > $length ) {
			$word = mb_strimwidth( $word, 0, $length, $affix );
		}
	}

	return sanitize_text_field( $word );
}

function starter_stored_theme_options() {
	$options = get_option( 'stm_theme_settings', array() );
	return apply_filters( 'starter_stored_theme_options', $options );
}

function starter_get_option( $option_name, $default = false ) {
	$options = starter_stored_theme_options();
	$option  = null;

	if ( ! empty( $options[ $option_name ] ) ) {
		$option = $options[ $option_name ];
	} elseif ( isset( $default ) ) {
		$option = $default;
	}

	return $option;
}

function starter_read_more_link() {
	if ( ! is_admin() ) {
		return '<a href="' . esc_url( get_permalink() ) . '" class="more-link"><span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a>';
	}
}

add_filter( 'excerpt_more', 'starter_read_more_link' );

function starter_admin_bar_css() {
	?>
	<style type="text/css" media="screen">
		body { margin-top: 32px !important; }
		@media screen and (max-width: 782px) { body { margin-top: 46px !important; } }
	</style>
	<?php
}

add_theme_support( 'admin-bar', array( 'callback' => 'starter_admin_bar_css' ) );

function starter_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Primary sidebar', 'starter-text-domain' ),
			'id'            => 'primary-sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'starter-text-domain' ),
			'before_widget' => '<section id="%1$s" class="widget widget-container %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}

add_action( 'widgets_init', 'starter_widgets_init' );

/**
 * Custom Pagination
 */
if ( ! function_exists( 'posts_pages_pagination' ) ) :
	function posts_pages_pagination( $paging_extra_class = '', $current_query = '' ) {
		global $wp_query, $wp_rewrite;

		if ( ! $current_query ) {
			$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
			$pages = $wp_query->max_num_pages;
		} else {
			$paged = $current_query->query_vars['paged'];
			$pages = $current_query->max_num_pages;
		}

		if ( $pages < 2 ) {
			return;
		}

		$page_num_link = html_entity_decode( get_pagenum_link() );
		$query_args    = array();
		$url_parts     = explode( '?', $page_num_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$page_num_link = remove_query_arg( array_keys( $query_args ), $page_num_link );
		$page_num_link = trailingslashit( $page_num_link ) . '%_%';

		$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $page_num_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

		$links = paginate_links(
			array(
				'base'      => $page_num_link,
				'format'    => $format,
				'total'     => $pages,
				'current'   => $paged,
				'mid_size'  => 1,
				'add_args'  => array_map( 'urlencode', $query_args ),
				'prev_text' => '<span class="ms-lms-icon-arrow-left"></span>',
				'next_text' => '<span class="ms-lms-icon-arrow-right"></span>',
				'type'      => 'list',
			)
		);

		if ( $links ) {
			echo wp_kses_post( $links );
		}
	}
endif;

/*MegaMenu*/
require_once get_template_directory() . '/includes/megamenu/main.php';

add_action( 'admin_head', 'starter_theme_nonces' );

function starter_theme_nonces() {
	$nonces = array(
		'stm_update_starter_theme',
		'starter_lms_settings_save',
	);

	$nonces_list = array();

	foreach ( $nonces as $nonce_name ) {
		$nonces_list[ $nonce_name ] = wp_create_nonce( $nonce_name );
	}

	?>
	<script>
		var starter_theme_nonces = <?php echo wp_json_encode( $nonces_list ); ?>;
	</script>
	<?php
}

add_filter( 'body_class', 'ms_lms_starter_body_classes' );
function ms_lms_starter_body_classes( $classes ) {
	$classes[] = 'theme-ms-lms-starter-theme';

	if ( has_blocks() && is_front_page() ) {
		$classes[] = 'ms-lms-guteberg-block-page';
	}

	return $classes;
}

/**
 * One-time Elementor resave runner for imported demo content.
 * Temporary init-based trigger for verification before attaching import hook.
 */

function masterstudy_resave_elementor_documents() {

	$demo_name = get_option( 'masterstudy_starter_demo_name', '' );
	if ( 'demo_7' !== $demo_name ) {
		return;
	}

	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}

	$options                 = get_option( 'stm_lms_settings', array() );
	$options['accent_color'] = 'rgba(123, 106, 244, 1)';
	$options['courses_page'] = 5511;

	update_option( 'stm_lms_settings', $options );

	$post_ids = get_posts(
		array(
			'post_type'      => array( 'page', 'post', 'stm-courses', 'elementor_library' ),
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => '_elementor_data',
					'compare' => 'EXISTS',
				),
			),
		)
	);

	$saved   = 0;
	$failed  = 0;
	$skipped = 0;

	foreach ( $post_ids as $post_id ) {
		$document = \Elementor\Plugin::$instance->documents->get( $post_id );
		if ( ! $document ) {
			$skipped++;
			continue;
		}

		if ( ! $document->is_built_with_elementor() ) {
			$document->set_is_built_with_elementor( true );
		}

		try {
			\Elementor\Plugin::$instance->documents->ajax_save(
				array(
					'editor_post_id' => $post_id,
					'status'         => \Elementor\Core\Base\Document::STATUS_PUBLISH,
					'elements'       => $document->get_elements_data(),
					'settings'       => $document->get_settings(),
				)
			);
			$saved++;
		} catch ( \Exception $e ) {
			$failed++;
			continue;
		}

		$css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
		$css_file->update();
	}

	\Elementor\Plugin::$instance->files_manager->clear_cache();
}

function masterstudy_trigger_resave_after_demo_import_elementor() {
	masterstudy_resave_elementor_documents();
}

add_action( 'masterstudy_starter_after_demo_import', 'masterstudy_trigger_resave_after_demo_import_elementor', 10, 4 );
