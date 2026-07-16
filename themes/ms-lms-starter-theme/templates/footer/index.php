<?php
$copyright_text              = get_theme_mod( 'ms_lms_starter_copyright_text' );
$copyright_text_size         = get_theme_mod( 'ms_lms_starter_copyright_text_size' ) ? get_theme_mod( 'ms_lms_starter_copyright_text_size' ) : '14';
$copyright_text_color        = get_theme_mod( 'ms_lms_starter_copyright_text_color' ) ? get_theme_mod( 'ms_lms_starter_copyright_text_color' ) : '#ffffff';
$socials_twitter_url         = get_theme_mod( 'ms_lms_starter_socials_twitter' );
$socials_facebook_url        = get_theme_mod( 'ms_lms_starter_socials_facebook' );
$socials_instagram_url       = get_theme_mod( 'ms_lms_starter_socials_instagram' );
$socials_youtube_url         = get_theme_mod( 'ms_lms_starter_socials_youtube' );
$socials_socials_color       = get_theme_mod( 'ms_lms_starter_socials_color' ) ? get_theme_mod( 'ms_lms_starter_socials_color' ) : '#ffffff';
$socials_socials_color_hover = get_theme_mod( 'ms_lms_starter_socials_color_hover' ) ? get_theme_mod( 'ms_lms_starter_socials_color_hover' ) : '#6ec1e4';
?>
<style>
:root {
	--stm-lms-copyright-text-size: <?php echo esc_attr( $copyright_text_size ); ?>px;
	--stm-lms-copyright-text-color: <?php echo esc_attr( $copyright_text_color ); ?>;
	--stm-lms-socials-color: <?php echo esc_attr( $socials_socials_color ); ?>;
	--stm-lms-socials-color-hover: <?php echo esc_attr( $socials_socials_color_hover ); ?>;
}
</style>
<footer class="footer">
	<div class="container">
		<div class="copyright">
			<?php
			if ( empty( $copyright_text ) ) {
				printf(
					esc_html__( 'Created by', 'starter-text-domain' ) . ' <a target="_blank" href="%s">%s</a> %s',
					esc_url( 'https://wordpress.org/plugins/masterstudy-lms-learning-management-system/' ),
					esc_html__( 'MasterStudy', 'starter-text-domain' ),
					esc_html__( '2024.', 'starter-text-domain' )
				);
			} else {
				echo wp_kses_post( $copyright_text );
			}
			?>
		</div>
		<ul class="social-list">
			<?php
			$socials = array(
				'twitter'   => array(
					'url'  => $socials_twitter_url,
					'icon' => 'fab fa-twitter',
				),
				'facebook'  => array(
					'url'  => $socials_facebook_url,
					'icon' => 'fab fa-facebook',
				),
				'instagram' => array(
					'url'  => $socials_instagram_url,
					'icon' => 'fab fa-instagram',
				),
				'youtube'   => array(
					'url'  => $socials_youtube_url,
					'icon' => 'fab fa-youtube',
				),
			);

			foreach ( $socials as $network => $data ) :
				if ( $data['url'] ) :
					?>
					<li>
						<a href="<?php echo esc_url( $data['url'] ); ?>">
							<i aria-hidden="true" class="<?php echo esc_attr( $data['icon'] ); ?>"></i>
						</a>
					</li>
					<?php
				endif;
			endforeach;
			?>
			</ul>
		</ul>
	</div>
</footer>
