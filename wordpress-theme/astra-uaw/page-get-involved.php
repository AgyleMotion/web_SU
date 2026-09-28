<?php
/**
 * Get involved page (dark section) with the email signup box.
 *
 * The signup form is a front-end placeholder: addresses are not stored or
 * sent anywhere until it is connected to a mailing list provider.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$astra_uaw_signup_title = (string) get_post_meta( get_the_ID(), '_astra_signup_title', true );
	$astra_uaw_signup_text  = (string) get_post_meta( get_the_ID(), '_astra_signup_text', true );
	$astra_uaw_btn_label    = astra_uaw_mod( 'astra_cta_btn1_label' );
	$astra_uaw_btn_url      = astra_uaw_link( astra_uaw_mod( 'astra_cta_btn1_url' ) );
	?>
	<section class="section involved" id="involved">
		<div class="container">
			<div class="involved__grid">
				<div class="involved__copy reveal">
					<?php astra_uaw_page_head( array( 'light' => true, 'wrap' => false ) ); ?>
					<?php the_content(); ?>
					<?php if ( '' !== $astra_uaw_btn_label && '' !== $astra_uaw_btn_url ) : ?>
						<div class="involved__actions">
							<a class="btn btn--primary" href="<?php echo esc_url( $astra_uaw_btn_url ); ?>"><?php echo esc_html( $astra_uaw_btn_label ); ?></a>
						</div>
					<?php endif; ?>
				</div>

				<form class="involved__signup reveal" id="listForm" novalidate>
					<h3><?php echo esc_html( '' !== $astra_uaw_signup_title ? $astra_uaw_signup_title : __( 'Get campaign updates', 'astra-uaw' ) ); ?></h3>
					<?php if ( '' !== $astra_uaw_signup_text ) : ?>
						<p><?php echo esc_html( $astra_uaw_signup_text ); ?></p>
					<?php endif; ?>
					<div class="signup__row">
						<input id="l-email" name="email" type="email" placeholder="<?php esc_attr_e( 'you@gmail.com', 'astra-uaw' ); ?>" aria-label="<?php esc_attr_e( 'Your email', 'astra-uaw' ); ?>" required />
						<button class="btn btn--primary" type="submit"><?php esc_html_e( 'Keep me posted', 'astra-uaw' ); ?></button>
					</div>
					<p class="form__note" id="listNote" role="status" aria-live="polite"></p>
				</form>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
