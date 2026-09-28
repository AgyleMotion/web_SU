<?php
/**
 * Small helpers used by the templates.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Turn a stored link into a URL: /path becomes a site URL, #anchor and full
 * URLs pass through. Anchors in Customizer settings point at the home page.
 *
 * @param string $link          Stored link.
 * @param bool   $anchor_on_home Resolve a bare #anchor against the home page.
 * @return string Unescaped URL.
 */
function astra_uaw_link( $link, $anchor_on_home = false ) {
	$link = trim( (string) $link );
	if ( '' === $link ) {
		return '';
	}
	if ( str_starts_with( $link, '#' ) ) {
		return ( $anchor_on_home && ! is_front_page() ) ? home_url( '/' . $link ) : $link;
	}
	if ( str_starts_with( $link, '/' ) && ! str_starts_with( $link, '//' ) ) {
		return home_url( $link );
	}
	return $link;
}

/**
 * Logo image URL: the Site Logo from the Customizer, or the bundled logo.
 *
 * @param string $where 'header' or 'footer' (the footer copy is rendered larger).
 * @return string
 */
function astra_uaw_logo_url( $where = 'header' ) {
	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$url = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return ASTRA_UAW_URI . ( 'footer' === $where ? '/assets/logo-footer.png' : '/assets/logo.png' );
}

/**
 * Split a textarea setting into trimmed, non-empty lines.
 *
 * @param string $text Text.
 * @return string[]
 */
function astra_uaw_lines( $text ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $text ) ) ) );
}

/**
 * Initials from a name: "Sara Bender-Bier" becomes "SB".
 *
 * @param string $name Name.
 * @return string
 */
function astra_uaw_initials( $name ) {
	$parts    = preg_split( '/\s+/', trim( wp_strip_all_tags( (string) $name ) ) );
	$initials = '';
	foreach ( array_slice( array_filter( $parts ), 0, 2 ) as $part ) {
		$initials .= mb_strtoupper( mb_substr( $part, 0, 1 ) );
	}
	return $initials;
}

/**
 * Print a section head (eyebrow, heading, intro) with the site's classes.
 *
 * @param string $eyebrow Small label.
 * @param string $heading Heading text.
 * @param string $intro   Intro (inline HTML allowed).
 * @param array  $opts    light (bool) for dark sections, reveal (bool), wrap (bool)
 *                        to print without the section__head wrapper.
 */
function astra_uaw_section_head( $eyebrow, $heading, $intro = '', $opts = array() ) {
	$light  = ! empty( $opts['light'] );
	$reveal = ! isset( $opts['reveal'] ) || $opts['reveal'];
	$wrap   = ! isset( $opts['wrap'] ) || $opts['wrap'];
	if ( $wrap ) {
		printf( '<div class="section__head%s">', $reveal ? ' reveal' : '' );
	}
	if ( '' !== trim( $eyebrow ) ) {
		printf( '<p class="section__eyebrow%s">%s</p>', $light ? ' section__eyebrow--light' : '', esc_html( $eyebrow ) );
	}
	if ( '' !== trim( $heading ) ) {
		printf( '<h2 class="section__title%s">%s</h2>', $light ? ' section__title--light' : '', esc_html( $heading ) );
	}
	if ( '' !== trim( $intro ) ) {
		printf( '<p class="section__intro">%s</p>', wp_kses( $intro, astra_uaw_inline_html() ) );
	}
	if ( $wrap ) {
		echo '</div>';
	}
}

/**
 * Section head for the current page from its "Section heading" fields.
 *
 * @param array $opts See astra_uaw_section_head().
 */
function astra_uaw_page_head( $opts = array() ) {
	$id      = get_the_ID();
	$heading = (string) get_post_meta( $id, '_astra_heading', true );
	astra_uaw_section_head(
		(string) get_post_meta( $id, '_astra_eyebrow', true ),
		'' !== $heading ? $heading : get_the_title(),
		(string) get_post_meta( $id, '_astra_intro', true ),
		$opts
	);
}

/**
 * Print one of the brand marks (logo image with the inline SVG fallback).
 *
 * @param string $where 'header' or 'footer'.
 */
function astra_uaw_brand_mark( $where = 'header' ) {
	$grad = 'footer' === $where ? 'rp2' : 'rp1';
	$size = 'footer' === $where ? 20 : 22;
	?>
	<span class="brand__mark" aria-hidden="true">
		<img class="brand__logo" src="<?php echo esc_url( astra_uaw_logo_url( $where ) ); ?>" alt="" onload="this.closest('.brand__mark').classList.add('has-logo')" onerror="this.style.display='none'" />
		<svg viewBox="0 0 24 24" width="<?php echo (int) $size; ?>" height="<?php echo (int) $size; ?>" aria-hidden="true">
			<defs>
				<radialGradient id="<?php echo esc_attr( $grad ); ?>" cx="50%" cy="50%" r="50%">
					<stop offset="0%" stop-color="#ffffff"/>
					<stop offset="40%" stop-color="#ffe3a0"/>
					<stop offset="75%" stop-color="#ffc233" stop-opacity=".55"/>
					<stop offset="100%" stop-color="#ffc233" stop-opacity="0"/>
				</radialGradient>
			</defs>
			<g fill="currentColor">
				<rect x="5.7" y="3.9" width="2.1" height="8.4" rx="1.05" transform="rotate(-17 6.75 12.2)"/>
				<rect x="9.0" y="2.2" width="2.1" height="9.8" rx="1.05" transform="rotate(-5 10.05 12)"/>
				<rect x="12.2" y="2.5" width="2.1" height="9.5" rx="1.05" transform="rotate(6 13.25 12)"/>
				<rect x="15.1" y="4.2" width="2.05" height="7.9" rx="1.02" transform="rotate(17 16.1 12)"/>
				<rect x="1.8" y="10.0" width="2.2" height="6.4" rx="1.1" transform="rotate(-50 2.9 13.2)"/>
				<path d="M7.2 9.8h8.8a1.75 1.75 0 0 1 1.75 1.75v3.1a6.15 6.15 0 0 1-6.15 6.15A6.15 6.15 0 0 1 5.45 14.65v-3.1A1.75 1.75 0 0 1 7.2 9.8Z"/>
			</g>
			<circle class="repulsor-halo" cx="11.6" cy="14.6" r="5.6" fill="url(#<?php echo esc_attr( $grad ); ?>)"/>
			<circle cx="11.6" cy="14.6" r="2.7" fill="#5a0a09"/>
			<circle cx="11.6" cy="14.6" r="2.15" fill="#ffe3a0"/>
			<circle class="repulsor-core" cx="11.6" cy="14.6" r="1.35" fill="#ffffff"/>
		</svg>
	</span>
	<?php
}
