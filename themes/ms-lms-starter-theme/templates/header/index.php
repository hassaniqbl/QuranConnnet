<header class="header" id="header">
	<div class="container">
		<section class="navigation">
			<?php
				get_template_part( 'templates/header/parts/logo' );
				get_template_part( 'templates/header/parts/menu' );

			if ( defined( 'MS_LMS_VERSION' ) ) {
				get_template_part( 'templates/header/parts/search-course' );
				get_template_part( 'templates/header/parts/authorization-links' );
			}
			?>
		</section>
	</div>
</header>
