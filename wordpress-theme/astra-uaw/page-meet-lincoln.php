<?php
/**
 * Meet Lincoln: photo (featured image), text (page content) and the heart
 * counter. The counter logic lives in js/script.js and needs #heartBtn and
 * #heartCount.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="section lincoln" id="lincoln">
		<div class="container">
			<?php astra_uaw_page_head(); ?>
			<div class="lincoln__grid">
				<figure class="lincoln__photo reveal">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) );
					} else {
						printf(
							'<img src="%1$s" alt="%2$s" loading="lazy" />',
							esc_url( ASTRA_UAW_URI . '/assets/lincoln.jpg' ),
							esc_attr__( 'Lincoln, a happy white labradoodle, standing on a sunny forest trail', 'astra-uaw' )
						);
					}
					?>
				</figure>
				<div class="lincoln__card reveal">
					<?php the_content(); ?>
					<div class="heart">
						<button class="heart__btn" id="heartBtn" type="button" aria-label="<?php esc_attr_e( 'Give Lincoln some love', 'astra-uaw' ); ?>">
							<span class="heart__icon" aria-hidden="true">&#10084;</span>
						</button>
						<p class="heart__count"><strong id="heartCount">0</strong> <span><?php esc_html_e( 'hearts for Lincoln', 'astra-uaw' ); ?></span></p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
