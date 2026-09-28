<?php
/**
 * Site footer.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

$astra_uaw_email       = astra_uaw_mod( 'astra_contact_email' );
$astra_uaw_affiliation = astra_uaw_mod( 'astra_affiliation' );
?>
	</main>
	<footer class="footer">
		<div class="container footer__grid">
			<div class="footer__brand">
				<span class="brand brand--footer">
					<?php astra_uaw_brand_mark( 'footer' ); ?>
					<span class="brand__text"><strong><?php bloginfo( 'name' ); ?></strong></span>
				</span>
				<?php if ( '' !== $astra_uaw_affiliation ) : ?>
					<p class="footer__aff">
						<?php
						printf(
							/* translators: %s: union affiliation, e.g. UAW. */
							esc_html__( 'Organizing in affiliation with %s.', 'astra-uaw' ),
							'<strong>' . esc_html( $astra_uaw_affiliation ) . '</strong>'
						);
						?>
					</p>
				<?php endif; ?>
			</div>

			<?php
			$astra_uaw_columns = array(
				'footer_campaign' => __( 'Campaign', 'astra-uaw' ),
				'footer_involved' => __( 'Get involved', 'astra-uaw' ),
			);
			foreach ( $astra_uaw_columns as $astra_uaw_location => $astra_uaw_default_heading ) :
				$astra_uaw_locations = get_nav_menu_locations();
				$astra_uaw_menu      = isset( $astra_uaw_locations[ $astra_uaw_location ] ) ? wp_get_nav_menu_object( $astra_uaw_locations[ $astra_uaw_location ] ) : false;
				$astra_uaw_heading   = $astra_uaw_menu ? $astra_uaw_menu->name : $astra_uaw_default_heading;
				$astra_uaw_is_nav    = 'footer_campaign' === $astra_uaw_location;
				?>
				<<?php echo $astra_uaw_is_nav ? 'nav' : 'div'; ?> class="footer__col"<?php echo $astra_uaw_is_nav ? ' aria-label="' . esc_attr__( 'Site', 'astra-uaw' ) . '"' : ''; ?>>
					<h4><?php echo esc_html( $astra_uaw_heading ); ?></h4>
					<?php
					if ( $astra_uaw_menu ) {
						wp_nav_menu(
							array(
								'theme_location' => $astra_uaw_location,
								'container'      => false,
								'items_wrap'     => '%3$s',
								'depth'          => 1,
								'walker'         => new Astra_UAW_Nav_Walker(),
								'fallback_cb'    => false,
								'astra_style'    => 'footer',
							)
						);
					}
					if ( 'footer_involved' === $astra_uaw_location && '' !== $astra_uaw_email ) {
						printf( '<a href="%1$s">%2$s</a>', esc_url( 'mailto:' . $astra_uaw_email ), esc_html( $astra_uaw_email ) );
					}
					?>
				</<?php echo $astra_uaw_is_nav ? 'nav' : 'div'; ?>>
			<?php endforeach; ?>

			<div class="footer__col">
				<h4><?php echo esc_html( astra_uaw_mod( 'astra_social_heading' ) ); ?></h4>
				<?php
				for ( $astra_uaw_i = 1; $astra_uaw_i <= 3; $astra_uaw_i++ ) {
					$astra_uaw_label = astra_uaw_mod( "astra_social_{$astra_uaw_i}_label" );
					$astra_uaw_url   = astra_uaw_link( astra_uaw_mod( "astra_social_{$astra_uaw_i}_url" ) );
					if ( '' === $astra_uaw_label || '' === $astra_uaw_url ) {
						continue;
					}
					$astra_uaw_external = str_starts_with( $astra_uaw_url, 'http' ) && ! str_starts_with( $astra_uaw_url, home_url() );
					printf(
						'<a href="%1$s"%2$s>%3$s</a>',
						esc_url( $astra_uaw_url ),
						$astra_uaw_external || '#' === $astra_uaw_url ? ' rel="noopener"' : '',
						esc_html( $astra_uaw_label )
					);
				}
				?>
			</div>
		</div>

		<div class="container footer__bottom">
			<?php if ( '' !== astra_uaw_mod( 'astra_disclaimer' ) ) : ?>
				<p class="footer__disclaimer"><?php echo esc_html( astra_uaw_mod( 'astra_disclaimer' ) ); ?></p>
			<?php endif; ?>
			<p class="footer__copy">&copy; <span id="year"><?php echo esc_html( date_i18n( 'Y' ) ); ?></span> <?php echo esc_html( astra_uaw_mod( 'astra_copyright' ) ); ?></p>
		</div>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>
