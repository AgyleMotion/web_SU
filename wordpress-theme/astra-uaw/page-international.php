<?php
/**
 * International students page (dark section).
 *
 * The cards and the note are the page content: group blocks with the CSS
 * classes "intl__grid", "intl__card" and "intl__note", so editors only change
 * the words inside them.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="section intl" id="international">
		<div class="container">
			<?php astra_uaw_page_head(); ?>
			<?php the_content(); ?>
		</div>
	</section>
	<?php
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
