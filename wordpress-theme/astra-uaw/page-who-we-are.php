<?php
/**
 * Who we are: testimonials, then organizers by department.
 *
 * Testimonials come from the Testimonials post type, departments from the
 * Departments post type. The organizers section only shows when at least one
 * department is published. Keep the #voices and #committee IDs: old links use them.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$astra_uaw_page_id = get_the_ID();
	$astra_uaw_voices  = astra_uaw_query( 'astra_voice' );
	$astra_uaw_depts   = astra_uaw_query( 'astra_dept' );
	?>
	<section class="section voices" id="voices">
		<div class="container">
			<?php astra_uaw_page_head(); ?>

			<?php if ( '' !== trim( get_the_content() ) ) : ?>
				<div class="page-content"><?php the_content(); ?></div>
			<?php endif; ?>

			<?php if ( $astra_uaw_voices->have_posts() ) : ?>
				<div class="voices__grid">
					<?php
					while ( $astra_uaw_voices->have_posts() ) :
						$astra_uaw_voices->the_post();
						$astra_uaw_dept     = (string) get_post_meta( get_the_ID(), '_astra_department', true );
						$astra_uaw_initials = (string) get_post_meta( get_the_ID(), '_astra_initials', true );
						if ( '' === $astra_uaw_initials ) {
							$astra_uaw_initials = astra_uaw_initials( get_the_title() );
						}
						?>
						<figure class="quote reveal">
							<blockquote class="quote__text"><?php the_content(); ?></blockquote>
							<figcaption class="quote__person">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail(
										'astra-avatar',
										array(
											'class'   => 'quote__avatar quote__avatar--photo',
											'alt'     => '',
											'loading' => 'lazy',
											'sizes'   => '96px',
										)
									);
								} else {
									printf( '<span class="quote__avatar" aria-hidden="true">%s</span>', esc_html( $astra_uaw_initials ) );
								}
								?>
								<span><strong><?php the_title(); ?></strong><?php if ( '' !== $astra_uaw_dept ) : ?><small><?php echo esc_html( $astra_uaw_dept ); ?></small><?php endif; ?></span>
							</figcaption>
						</figure>
					<?php endwhile; ?>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $astra_uaw_depts->have_posts() ) : ?>
		<section class="section committee" id="committee">
			<div class="container">
				<?php
				astra_uaw_section_head(
					(string) get_post_meta( $astra_uaw_page_id, '_astra_eyebrow2', true ),
					(string) get_post_meta( $astra_uaw_page_id, '_astra_heading2', true ),
					(string) get_post_meta( $astra_uaw_page_id, '_astra_intro2', true )
				);
				?>
				<div class="dept-grid">
					<?php
					while ( $astra_uaw_depts->have_posts() ) :
						$astra_uaw_depts->the_post();
						?>
						<div class="dept reveal"><h3><?php the_title(); ?></h3><p><?php echo esc_html( (string) get_post_meta( get_the_ID(), '_astra_organizers', true ) ); ?></p></div>
					<?php endwhile; ?>
				</div>
				<?php wp_reset_postdata(); ?>

				<?php $astra_uaw_note = (string) get_post_meta( $astra_uaw_page_id, '_astra_note2', true ); ?>
				<?php if ( '' !== $astra_uaw_note ) : ?>
					<p class="committee__note reveal"><?php echo wp_kses( $astra_uaw_note, astra_uaw_inline_html() ); ?></p>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
	<?php
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
