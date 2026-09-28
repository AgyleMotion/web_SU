<?php
/**
 * Site header.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="theme-color" content="#ffffff" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
	<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'astra-uaw' ); ?></a>
	<header class="site-header" id="top">
		<nav class="nav container" aria-label="<?php esc_attr_e( 'Primary', 'astra-uaw' ); ?>">
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: site name. */ __( '%s home', 'astra-uaw' ), get_bloginfo( 'name' ) ) ); ?>">
				<?php astra_uaw_brand_mark( 'header' ); ?>
				<span class="brand__text">
					<strong><?php bloginfo( 'name' ); ?></strong>
				</span>
			</a>

			<button class="nav__toggle" aria-expanded="false" aria-controls="nav-links" aria-label="<?php esc_attr_e( 'Open menu', 'astra-uaw' ); ?>">
				<span></span><span></span><span></span>
			</button>

			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'container'       => 'div',
					'container_class' => 'nav__links',
					'container_id'    => 'nav-links',
					'items_wrap'      => '%3$s',
					'depth'           => 1,
					'walker'          => new Astra_UAW_Nav_Walker(),
					'fallback_cb'     => 'astra_uaw_primary_fallback',
					'astra_style'     => 'header',
				)
			);
			?>
		</nav>
	</header>
	<main id="main">
