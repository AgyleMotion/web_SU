<?php
/**
 * Generic page template. Any page made in Pages > Add New uses this, so new
 * pages automatically get the site header, footer and styling.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="section" id="content">
		<div class="container">
			<?php astra_uaw_page_head(); ?>
			<div class="page-content">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
