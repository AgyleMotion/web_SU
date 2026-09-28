<?php
/**
 * One-click starter content: Appearance > ASTRA starter content.
 *
 * Creates the pages, testimonials, departments, FAQ items, issue cards and
 * menus from the original static site, sets the static front page and pretty
 * permalinks, and attaches the bundled photos. It never overwrites: existing
 * pages (matched by slug) are reused, and a content type that already has
 * items is left alone. Safe to run more than once.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add the admin page.
 */
function astra_uaw_starter_menu() {
	add_theme_page(
		__( 'ASTRA starter content', 'astra-uaw' ),
		__( 'ASTRA starter content', 'astra-uaw' ),
		'manage_options',
		'astra-uaw-starter',
		'astra_uaw_starter_page'
	);
}
add_action( 'admin_menu', 'astra_uaw_starter_menu' );

/**
 * Nudge admins toward the importer until it has run.
 */
function astra_uaw_starter_notice() {
	if ( get_option( 'astra_uaw_starter_done' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && 'appearance_page_astra-uaw-starter' === $screen->id ) {
		return;
	}
	printf(
		'<div class="notice notice-info"><p>%1$s <a href="%2$s">%3$s</a></p></div>',
		esc_html__( 'ASTRA-UAW theme: load the campaign\'s pages, testimonials, FAQ and menus in one click.', 'astra-uaw' ),
		esc_url( admin_url( 'themes.php?page=astra-uaw-starter' ) ),
		esc_html__( 'Load starter content', 'astra-uaw' )
	);
}
add_action( 'admin_notices', 'astra_uaw_starter_notice' );

/**
 * Render the admin page and handle the button.
 */
function astra_uaw_starter_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$log = array();
	if ( isset( $_POST['astra_uaw_starter'] ) ) {
		check_admin_referer( 'astra_uaw_starter' );
		$log = astra_uaw_import_starter_content();
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'ASTRA starter content', 'astra-uaw' ); ?></h1>
		<p><?php esc_html_e( 'This loads everything from the original campaign website: the pages, 8 testimonials with photos, 10 departments, 8 FAQ answers, 6 issue cards, and the header and footer menus. It also sets Home as the front page and turns on readable page addresses (like /faq/).', 'astra-uaw' ); ?></p>
		<p><?php esc_html_e( 'It never overwrites anything: pages that already exist are kept, and a section that already has items (for example Testimonials) is skipped. You can run it again safely.', 'astra-uaw' ); ?></p>
		<p><?php esc_html_e( 'Departments are loaded as drafts because the organizers list is hidden on the current site. Publish them under Departments when you want that section to appear.', 'astra-uaw' ); ?></p>

		<?php if ( $log ) : ?>
			<div class="notice notice-success"><p><strong><?php esc_html_e( 'Done.', 'astra-uaw' ); ?></strong></p>
				<ul style="list-style:disc;margin-left:20px">
					<?php foreach ( $log as $line ) : ?>
						<li><?php echo esc_html( $line ); ?></li>
					<?php endforeach; ?>
				</ul>
				<p><a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'View the site', 'astra-uaw' ); ?></a></p>
			</div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'astra_uaw_starter' ); ?>
			<p><button type="submit" name="astra_uaw_starter" value="1" class="button button-primary button-hero"><?php esc_html_e( 'Load starter content', 'astra-uaw' ); ?></button></p>
		</form>
	</div>
	<?php
}

/**
 * Replace {{home}} with the site address.
 *
 * @param string $text Text.
 * @return string
 */
function astra_uaw_starter_home( $text ) {
	return str_replace( '{{home}}', untrailingslashit( home_url() ), (string) $text );
}

/**
 * Copy a bundled theme image into the Media Library.
 *
 * @param string $relative Path inside the theme's assets folder.
 * @param int    $parent   Post to attach to.
 * @return int Attachment ID, or 0 on failure.
 */
function astra_uaw_starter_media( $relative, $parent = 0 ) {
	$source = ASTRA_UAW_DIR . '/assets/' . $relative;
	if ( ! is_readable( $source ) ) {
		return 0;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_astra_uaw_source', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- one-off import.
			'meta_value'     => $relative, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- one-off import.
		)
	);
	if ( $existing ) {
		return (int) $existing[0];
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( basename( $relative ) );
	if ( ! $tmp || ! copy( $source, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload(
		array(
			'name'     => basename( $relative ),
			'tmp_name' => $tmp,
		),
		$parent
	);
	if ( is_wp_error( $id ) ) {
		wp_delete_file( $tmp );
		return 0;
	}
	update_post_meta( $id, '_astra_uaw_source', $relative );
	return (int) $id;
}

/**
 * Whether a post type already has any items (in any status except trash).
 *
 * @param string $post_type Post type.
 * @return bool
 */
function astra_uaw_starter_has_items( $post_type ) {
	$counts = wp_count_posts( $post_type );
	foreach ( array( 'publish', 'draft', 'pending', 'private', 'future' ) as $status ) {
		if ( ! empty( $counts->$status ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Run the import.
 *
 * @return string[] Log lines.
 */
function astra_uaw_import_starter_content() {
	$data = require ASTRA_UAW_DIR . '/inc/starter-data.php';
	$log  = array();
	$ids  = array();

	// Site title and tagline, only if still at WordPress defaults.
	if ( in_array( get_option( 'blogname' ), array( '', 'My WordPress Website', 'My Blog' ), true ) ) {
		update_option( 'blogname', 'ASTRA' );
	}
	if ( in_array( get_option( 'blogdescription' ), array( '', 'Just another WordPress site' ), true ) ) {
		update_option( 'blogdescription', 'Association of Stevens Teaching and Research Assistants' );
	}

	// Pages.
	foreach ( $data['pages'] as $order => $page ) {
		$existing = get_page_by_path( $page['slug'], OBJECT, 'page' );
		if ( $existing ) {
			$ids[ $page['slug'] ] = $existing->ID;
			/* translators: %s: page title. */
			$log[] = sprintf( __( 'Kept existing page: %s', 'astra-uaw' ), $existing->post_title );
			continue;
		}
		$id = wp_insert_post(
			wp_slash(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => $page['slug'],
				'post_title'   => $page['title'],
				'post_content' => astra_uaw_starter_home( $page['content'] ),
				'post_excerpt' => $page['excerpt'],
				'menu_order'   => $order,
			)
			),
			true
		);
		if ( is_wp_error( $id ) ) {
			continue;
		}
		foreach ( $page['meta'] as $key => $value ) {
			update_post_meta( $id, $key, wp_slash( astra_uaw_starter_home( $value ) ) );
		}
		$ids[ $page['slug'] ] = $id;
		/* translators: %s: page title. */
		$log[] = sprintf( __( 'Created page: %s', 'astra-uaw' ), $page['title'] );
	}

	// Lincoln's photo.
	if ( ! empty( $ids['meet-lincoln'] ) && ! has_post_thumbnail( $ids['meet-lincoln'] ) ) {
		$photo = astra_uaw_starter_media( 'lincoln.jpg', $ids['meet-lincoln'] );
		if ( $photo ) {
			update_post_meta( $photo, '_wp_attachment_image_alt', 'Lincoln, a happy white labradoodle, standing on a sunny forest trail' );
			set_post_thumbnail( $ids['meet-lincoln'], $photo );
		}
	}

	// Content types.
	$types = array(
		'astra_voice' => array( 'voices', __( 'testimonials', 'astra-uaw' ), 'publish' ),
		'astra_dept'  => array( 'depts', __( 'departments (as drafts)', 'astra-uaw' ), 'draft' ),
		'astra_faq'   => array( 'faqs', __( 'FAQ answers', 'astra-uaw' ), 'publish' ),
		'astra_issue' => array( 'issues', __( 'issue cards', 'astra-uaw' ), 'publish' ),
	);
	foreach ( $types as $type => $conf ) {
		list( $key, $label, $status ) = $conf;
		if ( astra_uaw_starter_has_items( $type ) ) {
			/* translators: %s: content type, e.g. testimonials. */
			$log[] = sprintf( __( 'Skipped %s: some already exist.', 'astra-uaw' ), $label );
			continue;
		}
		$count = 0;
		foreach ( $data[ $key ] as $order => $item ) {
			$id = wp_insert_post(
				wp_slash(
				array(
					'post_type'    => $type,
					'post_status'  => $status,
					'post_title'   => $item['title'],
					'post_content' => astra_uaw_starter_home( $item['content'] ?? '' ),
					'menu_order'   => $order,
				)
				),
				true
			);
			if ( is_wp_error( $id ) ) {
				continue;
			}
			$meta = array(
				'_astra_department' => $item['department'] ?? '',
				'_astra_initials'   => $item['initials'] ?? '',
				'_astra_organizers' => $item['organizers'] ?? '',
				'_astra_icon'       => $item['icon'] ?? '',
			);
			foreach ( $meta as $meta_key => $value ) {
				if ( '' !== $value ) {
					update_post_meta( $id, $meta_key, wp_slash( $value ) );
				}
			}
			if ( ! empty( $item['photo'] ) ) {
				$photo = astra_uaw_starter_media( 'testimonials/' . $item['photo'], $id );
				if ( $photo ) {
					set_post_thumbnail( $id, $photo );
				}
			}
			++$count;
		}
		/* translators: 1: number of items, 2: content type. */
		$log[] = sprintf( __( 'Added %1$d %2$s.', 'astra-uaw' ), $count, $label );
	}

	// Front page and permalinks.
	if ( ! empty( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
		$log[] = __( 'Set Home as the front page.', 'astra-uaw' );
	}
	if ( '' === (string) get_option( 'permalink_structure' ) ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$log[] = __( 'Turned on readable page addresses (/%postname%/).', 'astra-uaw' );
	}
	flush_rewrite_rules( false );

	// Menus.
	$home = untrailingslashit( home_url() );
	$page = static function ( $slug, $title = '' ) use ( $ids ) {
		return empty( $ids[ $slug ] ) ? null : array(
			'type'   => 'page',
			'id'     => $ids[ $slug ],
			'title'  => $title,
			'class'  => '',
		);
	};
	$link = static function ( $url, $title, $class = '' ) {
		return array(
			'type'  => 'custom',
			'url'   => $url,
			'title' => $title,
			'class' => $class,
		);
	};
	$menus = array(
		'primary'         => array(
			'name'  => __( 'Main menu', 'astra-uaw' ),
			'items' => array(
				$page( 'home', 'Home' ),
				$link( $home . '/#about', 'About' ),
				$link( $home . '/#issues', 'Why we\'re organizing' ),
				$page( 'who-we-are', 'Testimonials' ),
				$page( 'international', 'International students' ),
				$page( 'faq', 'FAQ' ),
				$page( 'meet-lincoln', 'Meet Lincoln' ),
				empty( $ids['sign-card'] ) ? null : array_merge( $page( 'sign-card', 'Sign your card' ), array( 'class' => 'nav__cta' ) ),
			),
		),
		'footer_campaign' => array(
			'name'  => 'Campaign',
			'items' => array(
				$link( $home . '/#about', 'About us' ),
				$link( $home . '/#issues', 'Why we\'re organizing' ),
				$page( 'international', 'International students' ),
				$link( $home . '/who-we-are/#voices', 'Voices' ),
				$page( 'faq', 'FAQ' ),
			),
		),
		'footer_involved' => array(
			'name'  => 'Get involved',
			'items' => array(
				$page( 'sign-card', 'Sign your card' ),
				$link( $home . '/sign-card/#cardForm', 'Join the committee' ),
			),
		),
	);

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	foreach ( $menus as $location => $menu ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			/* translators: %s: menu name. */
			$log[] = sprintf( __( 'Kept existing menu: %s', 'astra-uaw' ), $menu['name'] );
			continue;
		}
		$menu_obj = wp_get_nav_menu_object( $menu['name'] );
		$menu_id  = $menu_obj ? (int) $menu_obj->term_id : (int) wp_create_nav_menu( $menu['name'] );
		if ( ! $menu_id || is_wp_error( $menu_id ) ) {
			continue;
		}
		if ( ! $menu_obj ) {
			foreach ( array_filter( $menu['items'] ) as $position => $item ) {
				$args = array(
					'menu-item-title'    => $item['title'],
					'menu-item-status'   => 'publish',
					'menu-item-position' => $position + 1,
					'menu-item-classes'  => $item['class'],
				);
				if ( 'page' === $item['type'] ) {
					$args['menu-item-type']      = 'post_type';
					$args['menu-item-object']    = 'page';
					$args['menu-item-object-id'] = $item['id'];
				} else {
					$args['menu-item-type'] = 'custom';
					$args['menu-item-url']  = $item['url'];
				}
				wp_update_nav_menu_item( $menu_id, 0, $args );
			}
		}
		$locations[ $location ] = $menu_id;
		/* translators: %s: menu name. */
		$log[] = sprintf( __( 'Created menu: %s', 'astra-uaw' ), $menu['name'] );
	}
	set_theme_mod( 'nav_menu_locations', $locations );

	update_option( 'astra_uaw_starter_done', time() );
	return $log;
}
