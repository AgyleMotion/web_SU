<?php
/**
 * Page not found.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section" id="content">
	<div class="container">
		<?php astra_uaw_section_head( __( 'Page not found', 'astra-uaw' ), __( 'We couldn\'t find that page', 'astra-uaw' ), __( 'It may have moved. Try the home page or the menu above.', 'astra-uaw' ) ); ?>
		<div class="hero__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the home page', 'astra-uaw' ); ?></a>
		</div>
	</div>
</section>
<?php
get_footer();
