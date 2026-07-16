<?php
wp_enqueue_style( 'profile-auth-links-style' );

$login_icon_width         = get_theme_mod( 'ms_lms_starter_login_icon_width' ) ? get_theme_mod( 'ms_lms_starter_login_icon_width' ) : '50';
$login_icon_height        = get_theme_mod( 'ms_lms_starter_login_icon_height' ) ? get_theme_mod( 'ms_lms_starter_login_icon_height' ) : '50';
$login_icon_size          = get_theme_mod( 'ms_lms_starter_login_icon_size' ) ? get_theme_mod( 'ms_lms_starter_login_icon_size' ) : '14';
$login_icon_color         = get_theme_mod( 'ms_lms_starter_login_icon_color' ) ? get_theme_mod( 'ms_lms_starter_login_icon_color' ) : '#ffffff';
$login_icon_bg_color      = get_theme_mod( 'ms_lms_starter_login_icon_bg_color' ) ? get_theme_mod( 'ms_lms_starter_login_icon_bg_color' ) : '#227AFF';
$login_link_text          = get_theme_mod( 'ms_lms_starter_login_link_text' ) ? get_theme_mod( 'ms_lms_starter_login_link_text' ) : esc_html__( 'login/sign up', 'starter-text-domain' );
$login_link_size          = get_theme_mod( 'ms_lms_starter_login_link_size' ) ? get_theme_mod( 'ms_lms_starter_login_link_size' ) : '12';
$login_link_color         = get_theme_mod( 'ms_lms_starter_login_link_color' ) ? get_theme_mod( 'ms_lms_starter_login_link_color' ) : '#2A3045';
$login_link_hover_color   = get_theme_mod( 'ms_lms_starter_login_link_hover_color' ) ? get_theme_mod( 'ms_lms_starter_login_link_hover_color' ) : '#2A3045';
$sing_in_icon_size        = get_theme_mod( 'ms_lms_starter_login_sing_in_icon_size' ) ? get_theme_mod( 'ms_lms_starter_login_sing_in_icon_size' ) : '14';
$sing_in_icon_color       = get_theme_mod( 'ms_lms_starter_login_sing_in_icon_color' ) ? get_theme_mod( 'ms_lms_starter_login_sing_in_icon_color' ) : '#385bce';
$sing_in_text_size        = get_theme_mod( 'ms_lms_starter_login_sing_in_text_size' ) ? get_theme_mod( 'ms_lms_starter_login_sing_in_text_size' ) : '14';
$sing_in_text_color       = get_theme_mod( 'ms_lms_starter_login_sing_in_text_color' ) ? get_theme_mod( 'ms_lms_starter_login_sing_in_text_color' ) : '#333333';
$sing_in_text_hover_color = get_theme_mod( 'ms_lms_starter_login_sing_in_text_hover_color' ) ? get_theme_mod( 'ms_lms_starter_login_sing_in_text_hover_color' ) : '#385bce';
$sing_in_bg_color         = get_theme_mod( 'ms_lms_starter_login_sing_in_bg_color' ) ? get_theme_mod( 'ms_lms_starter_login_sing_in_bg_color' ) : '#f0f4fa';
$sing_in_bg_hover_color   = get_theme_mod( 'ms_lms_starter_login_sing_in_bg_hover_color' ) ? get_theme_mod( 'ms_lms_starter_login_sing_in_bg_hover_color' ) : '#f0f4fa';
?>
<style>
:root {
	--stm-lms-auth-links-login-icon-width: <?php echo esc_attr( $login_icon_width ); ?>px;
	--stm-lms-auth-links-login-icon-height: <?php echo esc_attr( $login_icon_height ); ?>px;
	--stm-lms-auth-links-login-icon-size: <?php echo esc_attr( $login_icon_size ); ?>px;
	--stm-lms-auth-links-login-icon-color: <?php echo esc_attr( $login_icon_color ); ?>;
	--stm-lms-auth-links-login-icon-bg-color: <?php echo esc_attr( $login_icon_bg_color ); ?>;
	--stm-lms-auth-links-login-link-size: <?php echo esc_attr( $login_link_size ); ?>px;
	--stm-lms-auth-links-login-link-color: <?php echo esc_attr( $login_link_color ); ?>;
	--stm-lms-auth-links-login-link-hover-color: <?php echo esc_attr( $login_link_hover_color ); ?>;
	--stm-lms-auth-links-sing-in-icon-size: <?php echo esc_attr( $sing_in_icon_size ); ?>px;
	--stm-lms-auth-links-sing-in-icon-color: <?php echo esc_attr( $sing_in_icon_color ); ?>;
	--stm-lms-auth-links-sing-in-text-size: <?php echo esc_attr( $sing_in_text_size ); ?>px;
	--stm-lms-auth-links-sing-in-text-color: <?php echo esc_attr( $sing_in_text_color ); ?>;
	--stm-lms-auth-links-sing-in-text-hover-color: <?php echo esc_attr( $sing_in_text_hover_color ); ?>;
	--stm-lms-auth-links-sing-in-bg-color: <?php echo esc_attr( $sing_in_bg_color ); ?>;
	--stm-lms-auth-links-sing-in-bg-hover-color: <?php echo esc_attr( $sing_in_bg_hover_color ); ?>;
}
</style>
<?php

if ( ! is_user_logged_in() ) {
	$url = '';
	if ( class_exists( 'STM_LMS_User' ) ) {
		$url = \STM_LMS_User::login_page_url();
	}
	?>
	<a href="<?php echo esc_url( $url ); ?>" class="ms-lms-authorization">
		<span class="ms-lms-authorization-icon">
			<i class="fas fa-user" aria-hidden="true"></i>
		</span>
		<a href="<?php echo esc_url( $url ); ?>">
			<span class="ms-lms-authorization-title">
				<?php echo esc_html( $login_link_text ); ?>
			</span>
		</a>
	</a>
	<?php
} else {
	\STM_LMS_Templates::show_lms_template( 'global/account-dropdown' );
}
