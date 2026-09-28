<?php
/**
 * The red "Ready to be part of it?" band.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

$astra_uaw_buttons = array(
	array( astra_uaw_mod( 'astra_cta_btn1_label' ), astra_uaw_mod( 'astra_cta_btn1_url' ), 'btn btn--light' ),
	array( astra_uaw_mod( 'astra_cta_btn2_label' ), astra_uaw_mod( 'astra_cta_btn2_url' ), 'btn btn--outline' ),
);
?>
<section class="cta-band">
	<div class="container cta-band__inner">
		<div>
			<h2 class="cta-band__title"><?php echo esc_html( astra_uaw_mod( 'astra_cta_title' ) ); ?></h2>
			<p class="cta-band__text"><?php echo esc_html( astra_uaw_mod( 'astra_cta_text' ) ); ?></p>
		</div>
		<div class="cta-band__actions">
			<?php
			foreach ( $astra_uaw_buttons as $astra_uaw_button ) {
				if ( '' === $astra_uaw_button[0] || '' === $astra_uaw_button[1] ) {
					continue;
				}
				printf( '<a class="%1$s" href="%2$s">%3$s</a>', esc_attr( $astra_uaw_button[2] ), esc_url( astra_uaw_link( $astra_uaw_button[1], true ) ), esc_html( $astra_uaw_button[0] ) );
			}
			?>
		</div>
	</div>
</section>
