<?php
/**
 * Menu walker that outputs the flat <a> markup the stylesheet expects.
 *
 * Header: <a class="nav__link"> (or nav__cta when the menu item has the CSS
 * class "nav__cta"), with aria-current="page" on the current tab, because the
 * active-tab underline is styled on [aria-current="page"].
 * Footer: plain <a> tags.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Flat anchor walker. No <ul>/<li>.
 */
class Astra_UAW_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * No submenu wrappers.
	 *
	 * @param string   $output Output.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * No submenu wrappers.
	 *
	 * @param string   $output Output.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Args.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * One <a> per item.
	 *
	 * @param string   $output            Output.
	 * @param WP_Post  $data_object       Menu item.
	 * @param int      $depth             Depth.
	 * @param stdClass $args              wp_nav_menu() args.
	 * @param int      $current_object_id Current object ID.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item    = $data_object;
		$classes = empty( $item->classes ) ? array() : array_filter( (array) $item->classes );
		$style   = ( is_object( $args ) && isset( $args->astra_style ) ) ? $args->astra_style : 'header';

		$atts         = array();
		$atts['href'] = ! empty( $item->url ) ? $item->url : '';
		if ( ! empty( $item->target ) ) {
			$atts['target'] = $item->target;
		}
		if ( ! empty( $item->xfn ) ) {
			$atts['rel'] = $item->xfn;
		} elseif ( '_blank' === $item->target ) {
			$atts['rel'] = 'noopener';
		}
		if ( ! empty( $item->attr_title ) ) {
			$atts['title'] = $item->attr_title;
		}

		if ( 'header' === $style ) {
			$is_cta        = in_array( 'nav__cta', $classes, true );
			$atts['class'] = $is_cta ? 'nav__cta' : 'nav__link';

			// WordPress marks anchor links to the current page (/#about) as current
			// too; only the real page link should get the underline.
			$is_current = in_array( 'current-menu-item', $classes, true ) && false === strpos( (string) $item->url, '#' );
			if ( $is_current ) {
				$atts['aria-current'] = 'page';
			}
		}

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$output .= "\n" . str_repeat( "\t", 4 ) . '<a' . $attributes . '>' . wp_kses_post( $title ) . '</a>';
	}

	/**
	 * Nothing to close.
	 *
	 * @param string   $output      Output.
	 * @param WP_Post  $data_object Menu item.
	 * @param int      $depth       Depth.
	 * @param stdClass $args        Args.
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
}

/**
 * Header menu fallback before a menu is assigned: Home plus published pages.
 */
function astra_uaw_primary_fallback() {
	echo '<div class="nav__links" id="nav-links">';
	$home_current = is_front_page() ? ' aria-current="page"' : '';
	printf( '<a class="nav__link" href="%1$s"%2$s>%3$s</a>', esc_url( home_url( '/' ) ), $home_current, esc_html__( 'Home', 'astra-uaw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attribute.
	$pages = get_pages(
		array(
			'sort_column' => 'menu_order,post_title',
			'parent'      => 0,
			'exclude'     => (int) get_option( 'page_on_front' ),
		)
	);
	foreach ( $pages as $page ) {
		$current = is_page( $page->ID ) ? ' aria-current="page"' : '';
		printf( '<a class="nav__link" href="%1$s"%2$s>%3$s</a>', esc_url( get_permalink( $page ) ), $current, esc_html( get_the_title( $page ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attribute.
	}
	echo '</div>';
}
