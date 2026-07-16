<?php
/**
 * Init Styles & scripts
 */

function masterstudy_starter_admin_script_styles() {
	if ( 'toplevel_page_cost_calculator_builder' !== get_current_screen()->base ) {
		wp_enqueue_script( 'admin_masterstudy_starter_script', STM_TEMPLATE_URI . '/includes/dashboard/assets/js/admin_scripts.js', array( 'jquery' ), STM_THEME_VERSION, true );
		wp_localize_script(
			'admin_masterstudy_starter_script',
			'stm_lms_starter_theme_data',
			array(
				'stm_lms_admin_ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
		wp_enqueue_style( 'admin_masterstudy_starter_style', STM_TEMPLATE_URI . '/includes/dashboard/assets/css/admin_styles.css', '', STM_THEME_VERSION );
		wp_enqueue_style( 'starter-icons', STM_TEMPLATE_URI . '/assets/fonts/ms/style.css', array(), STM_THEME_VERSION );
	}

	wp_enqueue_style( 'google-fonts', starter_theme_fonts(), array(), STM_THEME_VERSION );
	wp_enqueue_style( 'albert-sans' );
	wp_enqueue_style( 'masterstudy-starter-wizard' );
	wp_enqueue_script( 'freemius-checkout' );
	wp_enqueue_script( 'masterstudy-starter-freemius' );
	wp_enqueue_script( 'masterstudy-starter-wizard' );
	wp_enqueue_script( 'masterstudy-starter-plugins' );
	wp_enqueue_script( 'masterstudy-starter-demo-import' );
	wp_enqueue_script( 'masterstudy-starter-child-theme' );
}

add_action( 'admin_enqueue_scripts', 'masterstudy_starter_admin_script_styles' );

function masterstudy_starter_admin_editor_styles() {
	add_editor_style( starter_theme_fonts() );

	global $post;

	if ( isset( $post ) && 'post' === $post->post_type ) {
		wp_enqueue_style( 'starter-single-post', STM_TEMPLATE_URI . '/assets/css/components/post/single/single-post.css', array(), STM_THEME_VERSION );
		wp_enqueue_style( 'starter-icons', STM_TEMPLATE_URI . '/assets/fonts/ms/style.css', array(), STM_THEME_VERSION );
	}
}

add_action( 'enqueue_block_editor_assets', 'masterstudy_starter_admin_editor_styles' );

function masterstudy_starter_show_nav_item() {
	add_menu_page(
		esc_html__( 'Welcome to MasterStudy Templates Page', 'masterstudy_starter' ),
		esc_html__( 'MasterStudy Templates', 'masterstudy_starter' ),
		'manage_options',
		'masterstudy-starter-options',
		'masterstudy_starter_admin_page_content',
		STM_TEMPLATE_URI . '/assets/images/base/icon.png',
		'4'
	);

	add_menu_page(
		esc_html__( 'MasterStudy Freemius', 'masterstudy_starter' ),
		esc_html__( 'MasterStudy Freemius', 'masterstudy_starter' ),
		'manage_options',
		'masterstudy-starter-freemius',
		'100'
	);
	update_option( 'hfe_onboarding_triggered', 'yes' ); //for disabling the wizard from UAE elementor header footer
}

add_action( 'admin_menu', 'masterstudy_starter_show_nav_item' );

function masterstudy_starter_admin_page_content() {
	?>
	<div class="ms-lms-starter-info-box">
		<div class="ms-lms-starter-info-box-column">
			<div class="ms-lms-starter-info-box-links">
				<div class="logo">
					<img src="<?php echo esc_url( STM_TEMPLATE_URI . '/assets/images/base/icon_80.png' ); ?>" alt="">
					<div class="logo-info">
						Welcome to
						<strong>MasterStudy Templates</strong>
					</div>
				</div>
				<div class="starter-dashboard-text">MasterStudy Templates is a free WordPress theme for MasterStudy LMS, the popular
					eLearning plugin for WordPress. The fully customizable pre-built home layout with inner pages is
					ready in one-click demo import so no coding is required.
				</div>
			</div>
			<div class="ms-lms-starter-info-box-tabs">
				<a href="#" class="ms-lms-starter-templates ms-lms-starter-templates-tab active">Templates</a>
				<a href="#" class="ms-lms-starter-system-status ms-lms-starter-templates-tab">System status</a>
				<a href="#" class="ms-lms-starter-changelog ms-lms-starter-templates-tab">Change log</a>
				<?php if ( function_exists( 'masterstudy_starter_fs_verify' ) && masterstudy_starter_fs_verify() ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=masterstudy-starter-freemius-account' ) ); ?>">Account</a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=masterstudy-starter-freemius-affiliation' ) ); ?>">Affiliate</a>
				<a href="#contacts-info">Contacts</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
			require_once MS_LMS_STARTER_THEME_DIR . '/includes/dashboard/resources/templates/changelog.php';
			require_once MS_LMS_STARTER_THEME_DIR . '/includes/dashboard/resources/templates/system-status.php';
		?>
		<div class="masterstudy-starter-wizard masterstudy-starter-templates">
			<div class="masterstudy-starter-wizard__navigation">
				<ul class="masterstudy-starter-wizard__navigation-progress-bar">
					<li class="progress-step-templates active"><span><em>1</em><i class="ms-lms-icon-check"></i></span> <?php echo esc_html__( 'Choose Template', 'masterstudy_starter' ); ?></li>
					<li class="progress-step-plugins"><span><em>2</em><i class="ms-lms-icon-check"></i></span> <?php echo esc_html__( 'Plugin installation', 'masterstudy_starter' ); ?></li>
					<li class="progress-step-demo-content"><span><em>3</em><i class="ms-lms-icon-check"></i></span> <?php echo esc_html__( 'Demo content import', 'masterstudy_starter' ); ?></li>
					<li class="progress-step-child-theme"><span><em>4</em><i class="ms-lms-icon-check"></i></span> <?php echo esc_html__( 'Child theme installation', 'masterstudy_starter' ); ?></li>
				</ul>
			</div>
			<div class="masterstudy-starter-wizard__wrapper">
				<?php load_template( MS_LMS_STARTER_THEME_DIR . '/includes/dashboard/wizard/templates/templates.php', true ); ?>
			</div>
		</div>
		<?php
		$theme_builder_option = get_option( 'ms-lms-starter-theme-builder', 'gutenberg' );
		if ( 'gutenberg' !== $theme_builder_option ) {
			$header_link = admin_url( 'edit.php?post_type=elementor-hf' );
			$footer_link = admin_url( 'edit.php?post_type=elementor-hf' );
		} else {
			$header_link = admin_url( 'customize.php?autofocus[panel]=ms_lms_starter_header_panel' );
			$footer_link = admin_url( 'customize.php?autofocus[panel]=ms_lms_starter_footer_panel' );
		}
		?>
		<div class="ms-lms-starter-info-box-column">
			<div class="ms-lms-starter-info-box-customizer">
				<div class="ms-lms-starter-info-box-customizer-title">Start customizing theme</div>
				<ul class="ms-lms-starter-info-box-customizer-settings">
					<li><a href="<?php echo esc_url( $header_link ); ?>"><span class="ms-lms-icon-header-builder"></span>Header</a></li>
					<li><a href="<?php echo esc_url( $footer_link ); ?>"><span class="ms-lms-icon-footer-builder"></span>Footer</a></li>
				</ul>
			</div>
		</div>
		<div id="contacts-info" class="ms-lms-starter-info-box-buttons">
			<div class="ms-lms-starter-info-box-buttons__wrap">
				<div class="ms-lms-starter-info-box-buttons__button">
					<a href="https://docs.stylemixthemes.com/masterstudy-lms-starter-theme/" target="_blank">
						<span class="ms-lms-icon-document ms-lms-starter-info-box-buttons__button-icon"></span>
						<span class=" ms-lms-starter-info-box-buttons__button-description">
							<strong>Documentation</strong> Detailed guide about all theme functionalities.
						</span>
						<span class="ms-lms-icon-arrow-right ms-lms-starter-info-box-buttons__button-arrow"></span>
					</a>
				</div>
				<div class="ms-lms-starter-info-box-buttons__button">
					<a href="https://www.youtube.com/playlist?list=PL3Pyh_1kFGGDW2EmMYkKrALDAYzIjafUd" target="_blank">
						<span class="ms-lms-icon-play ms-lms-starter-info-box-buttons__button-icon"></span>
						<span class=" ms-lms-starter-info-box-buttons__button-description">
							<strong>Video Tutorials</strong> Video materials describing theme features.
						</span>
						<span class="ms-lms-icon-arrow-right ms-lms-starter-info-box-buttons__button-arrow"></span>
					</a>
				</div>
				<div class="ms-lms-starter-info-box-buttons__button">
					<a href="https://www.facebook.com/groups/masterstudylms" target="_blank">
						<span class="ms-lms-icon-facebook ms-lms-starter-info-box-buttons__button-icon"></span>
						<span class=" ms-lms-starter-info-box-buttons__button-description">
							<strong>Facebook Community</strong> Share your experience with theme users.
						</span>
						<span class="ms-lms-icon-arrow-right ms-lms-starter-info-box-buttons__button-arrow"></span>
					</a>
				</div>
				<div class="ms-lms-starter-info-box-buttons__button">
					<a href="https://support.stylemixthemes.com/tickets/new/support?item_id=44" target="_blank">
						<span class="ms-lms-icon-life_west ms-lms-starter-info-box-buttons__button-icon"></span>
						<span class=" ms-lms-starter-info-box-buttons__button-description">
							<strong>Submit a ticket</strong> Get premium customer support via our ticket system.
						</span>
						<span class="ms-lms-icon-arrow-right ms-lms-starter-info-box-buttons__button-arrow"></span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<?php
}

//Remove all notices
add_action(
	'admin_head',
	function() {
		$screen = get_current_screen();

		if ( 'masterstudy-starter_page_masterstudy-starter-demo-import' === $screen->id || 'toplevel_page_masterstudy-starter-options' === $screen->id ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	},
	100
);
