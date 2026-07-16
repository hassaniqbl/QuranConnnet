<div class="masterstudy-starter-wizard masterstudy-starter-change-log" style="display: none;">
	<div class="masterstudy-starter-change-log__title">Change log</div>
	<?php
	$changelog = Masterstudy_Templates_Changelog::get_theme_changelog();
	if ( ! empty( $changelog ) ) {
		for ( $i = 0; $i <= 2; $i ++ ) :
			$changelog_item = $changelog[ $i ];
			?>
			<div class="masterstudy-starter-change-log__header">
				<div class="masterstudy-starter-change-log__version">Version: <?php echo esc_html( $changelog_item['heading'] ); ?></div>
				<div class="masterstudy-starter-change-log__date"><?php echo esc_html( $changelog_item['date'] ); ?></div>
			</div>
			<ul class="masterstudy-starter-change-log__list">
				<?php foreach ( $changelog_item['list'] as $item ) : ?>
				<li><?php echo wp_kses_post( $item ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php
		endfor;
	}
	?>
	<a href="<?php echo esc_url( 'https://docs.stylemixthemes.com/masterstudy-lms-starter-theme/extra-materials/changelog' ); ?>" target="_blank" class="masterstudy-starter-change-log__button">See More</a>
</div>
