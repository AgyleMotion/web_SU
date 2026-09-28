<?php
/**
 * Custom post types: Testimonials, Departments, FAQ, Issues.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build a labels array for a post type.
 *
 * @param string $plural   Plural label, e.g. "Testimonials".
 * @param string $singular Singular label, e.g. "Testimonial".
 * @return array
 */
function astra_uaw_cpt_labels( $plural, $singular ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		'menu_name'          => $plural,
		/* translators: %s: singular post type name. */
		'add_new_item'       => sprintf( __( 'Add new %s', 'astra-uaw' ), strtolower( $singular ) ),
		/* translators: %s: singular post type name. */
		'edit_item'          => sprintf( __( 'Edit %s', 'astra-uaw' ), strtolower( $singular ) ),
		/* translators: %s: singular post type name. */
		'new_item'           => sprintf( __( 'New %s', 'astra-uaw' ), strtolower( $singular ) ),
		/* translators: %s: singular post type name. */
		'view_item'          => sprintf( __( 'View %s', 'astra-uaw' ), strtolower( $singular ) ),
		/* translators: %s: plural post type name. */
		'search_items'       => sprintf( __( 'Search %s', 'astra-uaw' ), strtolower( $plural ) ),
		/* translators: %s: plural post type name. */
		'not_found'          => sprintf( __( 'No %s found', 'astra-uaw' ), strtolower( $plural ) ),
		/* translators: %s: plural post type name. */
		'not_found_in_trash' => sprintf( __( 'No %s found in trash', 'astra-uaw' ), strtolower( $plural ) ),
		/* translators: %s: plural post type name. */
		'all_items'          => sprintf( __( 'All %s', 'astra-uaw' ), strtolower( $plural ) ),
	);
}

/**
 * Register the four content types the committee edits.
 */
function astra_uaw_register_post_types() {
	// These items only ever appear inside a page, so they have no single view of their own.
	$common = array(
		'public'              => true,
		'show_in_rest'        => true,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
		'has_archive'         => false,
		'rewrite'             => false,
		'show_in_nav_menus'   => false,
	);

	register_post_type(
		'astra_voice',
		array_merge(
			$common,
			array(
				'labels'        => astra_uaw_cpt_labels( __( 'Testimonials', 'astra-uaw' ), __( 'Testimonial', 'astra-uaw' ) ),
				'description'   => __( 'Quotes shown on the Testimonials page. Title is the person\'s name, the text is their quote.', 'astra-uaw' ),
				'menu_icon'     => 'dashicons-format-quote',
				'menu_position' => 21,
				'supports'      => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'astra_dept',
		array_merge(
			$common,
			array(
				'labels'        => astra_uaw_cpt_labels( __( 'Departments', 'astra-uaw' ), __( 'Department', 'astra-uaw' ) ),
				'description'   => __( 'Departments and their organizers, shown on the Testimonials page.', 'astra-uaw' ),
				'menu_icon'     => 'dashicons-groups',
				'menu_position' => 22,
				'supports'      => array( 'title', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'astra_faq',
		array_merge(
			$common,
			array(
				'labels'        => astra_uaw_cpt_labels( __( 'FAQ', 'astra-uaw' ), __( 'Question', 'astra-uaw' ) ),
				'description'   => __( 'Questions and answers for the FAQ page.', 'astra-uaw' ),
				'menu_icon'     => 'dashicons-editor-help',
				'menu_position' => 23,
				'supports'      => array( 'title', 'editor', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'astra_issue',
		array_merge(
			$common,
			array(
				'labels'        => astra_uaw_cpt_labels( __( 'Issues', 'astra-uaw' ), __( 'Issue', 'astra-uaw' ) ),
				'description'   => __( 'The "What we\'re working toward" cards on the home page.', 'astra-uaw' ),
				'menu_icon'     => 'dashicons-flag',
				'menu_position' => 24,
				'supports'      => array( 'title', 'editor', 'page-attributes' ),
			)
		)
	);
}
add_action( 'init', 'astra_uaw_register_post_types' );

/**
 * Plainer title placeholders so editors know what goes in the title field.
 *
 * @param string  $text Default placeholder.
 * @param WP_Post $post Post being edited.
 * @return string
 */
function astra_uaw_title_placeholder( $text, $post ) {
	switch ( $post->post_type ) {
		case 'astra_voice':
			return __( 'Person\'s name', 'astra-uaw' );
		case 'astra_dept':
			return __( 'Department name', 'astra-uaw' );
		case 'astra_faq':
			return __( 'The question', 'astra-uaw' );
		case 'astra_issue':
			return __( 'Card heading, e.g. Fair, livable pay', 'astra-uaw' );
	}
	return $text;
}
add_filter( 'enter_title_here', 'astra_uaw_title_placeholder', 10, 2 );

/**
 * Query items of one of our post types in the order editors set.
 *
 * @param string $post_type Post type slug.
 * @return WP_Query
 */
function astra_uaw_query( $post_type ) {
	return new WP_Query(
		array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'no_found_rows'  => true,
		)
	);
}

/**
 * Show the order column in the admin lists so reordering is obvious.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function astra_uaw_order_column( $columns ) {
	$columns['menu_order'] = __( 'Order', 'astra-uaw' );
	return $columns;
}

/**
 * Print the order value.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function astra_uaw_order_column_value( $column, $post_id ) {
	if ( 'menu_order' === $column ) {
		echo esc_html( (string) get_post_field( 'menu_order', $post_id ) );
	}
}

foreach ( array( 'astra_voice', 'astra_dept', 'astra_faq', 'astra_issue' ) as $astra_uaw_type ) {
	add_filter( "manage_{$astra_uaw_type}_posts_columns", 'astra_uaw_order_column' );
	add_action( "manage_{$astra_uaw_type}_posts_custom_column", 'astra_uaw_order_column_value', 10, 2 );
}
unset( $astra_uaw_type );

/**
 * Sort the admin lists the same way the site does.
 *
 * @param WP_Query $query Main admin query.
 */
function astra_uaw_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	$type = $query->get( 'post_type' );
	if ( in_array( $type, array( 'astra_voice', 'astra_dept', 'astra_faq', 'astra_issue' ), true ) && ! $query->get( 'orderby' ) ) {
		$query->set(
			'orderby',
			array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			)
		);
	}
}
add_action( 'pre_get_posts', 'astra_uaw_admin_order' );
