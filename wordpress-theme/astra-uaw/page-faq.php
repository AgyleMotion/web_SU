<?php
/**
 * FAQ page: an accordion of items from the FAQ post type.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$astra_uaw_faqs = astra_uaw_query( 'astra_faq' );
	?>
	<section class="section faq" id="faq">
		<div class="container">
			<?php astra_uaw_page_head(); ?>

			<?php if ( '' !== trim( get_the_content() ) ) : ?>
				<div class="page-content"><?php the_content(); ?></div>
			<?php endif; ?>

			<?php if ( $astra_uaw_faqs->have_posts() ) : ?>
				<div class="faq__list reveal">
					<?php
					while ( $astra_uaw_faqs->have_posts() ) :
						$astra_uaw_faqs->the_post();
						?>
						<details class="faq__item">
							<summary><?php the_title(); ?></summary>
							<div class="faq__a"><?php the_content(); ?></div>
						</details>
					<?php endwhile; ?>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</section>
	<?php
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
