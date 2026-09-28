<?php
/**
 * Custom fields for the custom post types and for pages.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions, keyed by post type.
 *
 * Each field: label, type (text|textarea|html), help text, and an optional
 * page slug it is limited to (for page fields that only one template uses).
 *
 * @return array
 */
function astra_uaw_meta_fields() {
	return array(
		'astra_voice' => array(
			'title'  => __( 'Testimonial details', 'astra-uaw' ),
			'fields' => array(
				'_astra_department' => array(
					'label' => __( 'Department', 'astra-uaw' ),
					'type'  => 'text',
					'help'  => __( 'Shown under the name, e.g. Biomedical Engineering.', 'astra-uaw' ),
				),
				'_astra_initials'   => array(
					'label' => __( 'Initials (optional)', 'astra-uaw' ),
					'type'  => 'text',
					'help'  => __( 'Shown in the circle when there is no photo. Leave empty to use the first letters of the name. To add a photo, set a Featured image.', 'astra-uaw' ),
				),
			),
		),
		'astra_dept'  => array(
			'title'  => __( 'Organizers', 'astra-uaw' ),
			'fields' => array(
				'_astra_organizers' => array(
					'label' => __( 'Organizer names', 'astra-uaw' ),
					'type'  => 'text',
					'help'  => __( 'Separate names with commas, e.g. Nima Kalantari, Jett Langhorn.', 'astra-uaw' ),
				),
			),
		),
		'astra_issue' => array(
			'title'  => __( 'Card icon', 'astra-uaw' ),
			'fields' => array(
				'_astra_icon' => array(
					'label' => __( 'Icon (an emoji)', 'astra-uaw' ),
					'type'  => 'text',
					'help'  => __( 'Paste a single emoji, e.g. 💵', 'astra-uaw' ),
				),
			),
		),
		'page'        => array(
			'title'  => __( 'Section heading', 'astra-uaw' ),
			'fields' => array(
				'_astra_eyebrow'  => array(
					'label' => __( 'Small label above the heading', 'astra-uaw' ),
					'type'  => 'text',
					'help'  => __( 'e.g. FAQ. Leave empty to hide it.', 'astra-uaw' ),
				),
				'_astra_heading'  => array(
					'label' => __( 'Big heading', 'astra-uaw' ),
					'type'  => 'text',
					'help'  => __( 'Leave empty to use the page title.', 'astra-uaw' ),
				),
				'_astra_intro'    => array(
					'label' => __( 'Intro sentence under the heading', 'astra-uaw' ),
					'type'  => 'html',
					'help'  => __( 'Optional.', 'astra-uaw' ),
				),
				'_astra_eyebrow2' => array(
					'label' => __( 'Organizers section: small label', 'astra-uaw' ),
					'type'  => 'text',
					'only'  => 'who-we-are',
				),
				'_astra_heading2' => array(
					'label' => __( 'Organizers section: big heading', 'astra-uaw' ),
					'type'  => 'text',
					'only'  => 'who-we-are',
				),
				'_astra_intro2'   => array(
					'label' => __( 'Organizers section: intro sentence', 'astra-uaw' ),
					'type'  => 'html',
					'only'  => 'who-we-are',
				),
				'_astra_note2'    => array(
					'label' => __( 'Organizers section: note under the list', 'astra-uaw' ),
					'type'  => 'html',
					'help'  => __( 'Links are allowed, e.g. <a href="/get-involved/">Get involved &rarr;</a>', 'astra-uaw' ),
					'only'  => 'who-we-are',
				),
				'_astra_signup_title' => array(
					'label' => __( 'Email signup box: heading', 'astra-uaw' ),
					'type'  => 'text',
					'only'  => 'get-involved',
				),
				'_astra_signup_text'  => array(
					'label' => __( 'Email signup box: text', 'astra-uaw' ),
					'type'  => 'text',
					'only'  => 'get-involved',
				),
				'_astra_steps'    => array(
					'label' => __( 'Numbered steps', 'astra-uaw' ),
					'type'  => 'textarea',
					'help'  => __( 'One step per line.', 'astra-uaw' ),
					'only'  => 'sign-card',
				),
			),
		),
	);
}

/**
 * Register the meta boxes.
 *
 * @param string  $post_type Current post type.
 * @param WP_Post $post      Current post.
 */
function astra_uaw_add_meta_boxes( $post_type, $post ) {
	$defs = astra_uaw_meta_fields();
	if ( ! isset( $defs[ $post_type ] ) || ! $post instanceof WP_Post ) {
		return;
	}
	add_meta_box(
		'astra_uaw_fields',
		$defs[ $post_type ]['title'],
		'astra_uaw_render_meta_box',
		$post_type,
		'normal',
		'high',
		array( 'fields' => $defs[ $post_type ]['fields'] )
	);
}
add_action( 'add_meta_boxes', 'astra_uaw_add_meta_boxes', 10, 2 );

/**
 * Whether a field applies to this post (some page fields are template-specific).
 *
 * @param array   $field Field definition.
 * @param WP_Post $post  Post.
 * @return bool
 */
function astra_uaw_field_applies( $field, $post ) {
	return empty( $field['only'] ) || $field['only'] === $post->post_name;
}

/**
 * Render the fields.
 *
 * @param WP_Post $post Post being edited.
 * @param array   $box  Meta box args.
 */
function astra_uaw_render_meta_box( $post, $box ) {
	wp_nonce_field( 'astra_uaw_save_meta', 'astra_uaw_meta_nonce' );
	echo '<div class="astra-uaw-fields">';
	foreach ( $box['args']['fields'] as $key => $field ) {
		if ( ! astra_uaw_field_applies( $field, $post ) ) {
			continue;
		}
		$value = (string) get_post_meta( $post->ID, $key, true );
		$id    = 'astra-uaw-' . sanitize_html_class( ltrim( $key, '_' ) );
		echo '<p style="margin:0 0 16px">';
		printf( '<label for="%1$s" style="display:block;font-weight:600;margin-bottom:4px">%2$s</label>', esc_attr( $id ), esc_html( $field['label'] ) );
		if ( 'text' === $field['type'] ) {
			printf( '<input type="text" class="widefat" id="%1$s" name="%2$s" value="%3$s" />', esc_attr( $id ), esc_attr( $key ), esc_attr( $value ) );
		} else {
			printf( '<textarea class="widefat" rows="3" id="%1$s" name="%2$s">%3$s</textarea>', esc_attr( $id ), esc_attr( $key ), esc_textarea( $value ) );
		}
		if ( ! empty( $field['help'] ) ) {
			printf( '<span class="description" style="display:block;margin-top:4px">%s</span>', esc_html( $field['help'] ) );
		}
		echo '</p>';
	}
	echo '</div>';
}

/**
 * Save the fields.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function astra_uaw_save_meta( $post_id, $post ) {
	if ( ! isset( $_POST['astra_uaw_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['astra_uaw_meta_nonce'] ) ), 'astra_uaw_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$defs = astra_uaw_meta_fields();
	if ( ! isset( $defs[ $post->post_type ] ) ) {
		return;
	}

	foreach ( $defs[ $post->post_type ]['fields'] as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized per type below.
		switch ( $field['type'] ) {
			case 'html':
				$value = wp_kses( (string) $raw, astra_uaw_inline_html() );
				break;
			case 'textarea':
				$value = sanitize_textarea_field( (string) $raw );
				break;
			default:
				$value = sanitize_text_field( (string) $raw );
		}
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}
add_action( 'save_post', 'astra_uaw_save_meta', 10, 2 );

/**
 * Inline HTML allowed in short text fields (intros, notes, the hero title).
 *
 * @return array
 */
function astra_uaw_inline_html() {
	return array(
		'a'      => array(
			'href'   => true,
			'target' => true,
			'rel'    => true,
			'class'  => true,
		),
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'span'   => array( 'class' => true ),
	);
}
