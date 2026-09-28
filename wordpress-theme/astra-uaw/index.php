<?php
/**
 * Fallback template (blog posts, archives, search).
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section" id="content">
	<div class="container">
		<?php
		if ( is_singular() ) {
			astra_uaw_section_head( '', get_the_title() );
		} elseif ( is_search() ) {
			/* translators: %s: search query. */
			astra_uaw_section_head( __( 'Search', 'astra-uaw' ), sprintf( __( 'Results for "%s"', 'astra-uaw' ), get_search_query() ) );
		} elseif ( is_archive() ) {
			astra_uaw_section_head( '', wp_strip_all_tags( get_the_archive_title() ) );
		} else {
			astra_uaw_section_head( '', get_bloginfo( 'name' ) );
		}
		?>
		<div class="page-content">
			<?php if ( have_posts() ) : ?>
				<?php
				while ( have_posts() ) :
					the_post();
					if ( is_singular() ) {
						the_content();
						wp_link_pages();
					} else {
						printf( '<h2><a href="%1$s">%2$s</a></h2>', esc_url( get_permalink() ), esc_html( get_the_title() ) );
						the_excerpt();
					}
				endwhile;
				the_posts_pagination();
				?>
			<?php else : ?>
				<p><?php esc_html_e( 'Nothing here yet.', 'astra-uaw' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
