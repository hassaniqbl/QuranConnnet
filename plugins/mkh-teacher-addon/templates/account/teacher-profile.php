<?php
/**
 * Teacher Profile Template
 *
 * Displays the ACF frontend form for teacher profile editing.
 *
 * @package MKH_Teacher_Addon
 */

// ACF form head must be called before any HTML output
acf_form_head();

// Permission check
if ( ! is_user_logged_in() ) {
	wp_safe_redirect( STM_LMS_User::login_page_url() );
	exit;
}

$user_id = get_current_user_id();

// Check if user is instructor
if ( ! STM_LMS_Instructor::is_instructor( $user_id ) ) {
	?>
	<div class="masterstudy-account">
		<div class="masterstudy-account-container">
			<div class="stm-lms-message stm-lms-message__error">
				<?php esc_html_e( 'Access Denied. This page is only available for instructors.', 'mkh-teacher-addon' ); ?>
			</div>
		</div>
	</div>
	<?php
	return;
}

$lms_current_user = STM_LMS_User::get_current_user( '', true, true );

wp_enqueue_style( 'masterstudy-account-main' );

do_action( 'stm_lms_template_main' );
do_action( 'masterstudy_before_account', $lms_current_user );
?>

<div class="masterstudy-account">
	<?php do_action( 'stm_lms_admin_after_wrapper_start', $lms_current_user ); ?>
	<div class="masterstudy-account-sidebar">
		<div class="masterstudy-account-sidebar__wrapper">
			<?php do_action( 'masterstudy_account_sidebar', $lms_current_user ); ?>
		</div>
	</div>
	<div class="masterstudy-account-container">
		<?php
		// Load teacher profile form
		require_once plugin_dir_path( __FILE__ ) . 'parts/teacher-profile-form.php';

		do_action( 'stm_lms_before_profile_buttons_all', $lms_current_user );
		?>
	</div>
</div>
<?php do_action( 'masterstudy_after_account', $lms_current_user ); ?>
