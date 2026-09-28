<?php
/**
 * Customizer: Appearance > Customize > ASTRA Campaign.
 *
 * Every setting is defined once in astra_uaw_customizer_sections(), which
 * drives both the Customizer controls and astra_uaw_mod() defaults.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

/**
 * All sections and settings, with defaults matching the original site.
 *
 * Types: text, textarea (plain, one item per line where noted), html (inline
 * HTML: <br>, <span class="u-mark">, <a>, <em>, <strong>), url, email.
 *
 * @return array
 */
function astra_uaw_customizer_sections() {
	return array(
		'astra_hero'    => array(
			'title'  => __( 'Home: hero and stats', 'astra-uaw' ),
			'fields' => array(
				'astra_hero_eyebrow'    => array( __( 'Label above the headline', 'astra-uaw' ), 'text', 'Association of Stevens Teaching and Research Assistants' ),
				'astra_hero_title'      => array( __( 'Headline', 'astra-uaw' ), 'html', 'We love what we do.<br />We\'d like a&nbsp;<span class="u-mark">say</span>&nbsp;in how it\'s done.', __( 'Use <br /> for a line break. Wrap a word in <span class="u-mark">…</span> to underline it in red.', 'astra-uaw' ) ),
				'astra_hero_lede'       => array( __( 'Text under the headline', 'astra-uaw' ), 'textarea', 'Graduate student workers at Stevens are coming together to form a union, so decisions about our pay, benefits, and working conditions are made with us, in a contract we help shape.' ),
				'astra_hero_btn1_label' => array( __( 'Main button: label', 'astra-uaw' ), 'text', 'Sign your union card' ),
				'astra_hero_btn1_url'   => array( __( 'Main button: link', 'astra-uaw' ), 'url', '/sign-card/' ),
				'astra_hero_btn2_label' => array( __( 'Second button: label', 'astra-uaw' ), 'text', 'Why we\'re organizing' ),
				'astra_hero_btn2_url'   => array( __( 'Second button: link', 'astra-uaw' ), 'url', '#issues' ),
				'astra_stat1_num'       => array( __( 'Stat 1: number', 'astra-uaw' ), 'number', '441' ),
				'astra_stat1_label'     => array( __( 'Stat 1: label', 'astra-uaw' ), 'text', 'PhD students reached' ),
				'astra_stat2_num'       => array( __( 'Stat 2: number', 'astra-uaw' ), 'number', '10' ),
				'astra_stat2_label'     => array( __( 'Stat 2: label', 'astra-uaw' ), 'text', 'departments covered' ),
				'astra_stat3_num'       => array( __( 'Stat 3: number', 'astra-uaw' ), 'number', '1' ),
				'astra_stat3_label'     => array( __( 'Stat 3: label', 'astra-uaw' ), 'text', 'united voice at the table' ),
				'astra_marquee'         => array( __( 'Scrolling banner phrases', 'astra-uaw' ), 'textarea', "Sign your card\nEvery voice counts\nLooking out for each other\nBy us, for us\nBetter together", __( 'One phrase per line.', 'astra-uaw' ) ),
			),
		),
		'astra_home'    => array(
			'title'       => __( 'Home: sections', 'astra-uaw' ),
			'description' => __( 'The "Who we are" paragraphs are the content of the Home page (Pages > Home). The issue cards are under Issues in the admin menu.', 'astra-uaw' ),
			'fields'      => array(
				'astra_about_eyebrow'     => array( __( 'About: small label', 'astra-uaw' ), 'text', 'About us' ),
				'astra_about_title'       => array( __( 'About: heading', 'astra-uaw' ), 'text', 'Who we are' ),
				'astra_point1_k'          => array( __( 'About box 1: title', 'astra-uaw' ), 'text', 'What a union is' ),
				'astra_point1_v'          => array( __( 'About box 1: text', 'astra-uaw' ), 'html', 'All of us, organized, bargaining one contract together, instead of negotiating alone.' ),
				'astra_point2_k'          => array( __( 'About box 2: title', 'astra-uaw' ), 'text', 'Who it\'s for' ),
				'astra_point2_v'          => array( __( 'About box 2: text', 'astra-uaw' ), 'html', 'Grad workers, including TAs, RAs, and those on fellowships across every department and every school at Stevens.' ),
				'astra_point3_k'          => array( __( 'About box 3: title', 'astra-uaw' ), 'text', 'How it happens' ),
				'astra_point3_v'          => array( __( 'About box 3: text', 'astra-uaw' ), 'html', 'Most of us sign cards &rarr; we ask for recognition &rarr; we negotiate a contract together.' ),
				'astra_point4_k'          => array( __( 'About box 4: title', 'astra-uaw' ), 'text', 'Who runs it' ),
				'astra_point4_v'          => array( __( 'About box 4: text', 'astra-uaw' ), 'html', 'We do. Workers make every decision democratically, from the bottom up.' ),
				'astra_issues_eyebrow'    => array( __( 'Issues: small label', 'astra-uaw' ), 'text', 'Why we\'re organizing' ),
				'astra_issues_title'      => array( __( 'Issues: heading', 'astra-uaw' ), 'text', 'What we\'re working toward' ),
				'astra_issues_intro'      => array( __( 'Issues: intro', 'astra-uaw' ), 'html', 'These are the things that matter most to us, and a contract lets us put them in writing, together.' ),
				'astra_compare_eyebrow'   => array( __( 'Comparison: small label', 'astra-uaw' ), 'text', 'The difference' ),
				'astra_compare_title'     => array( __( 'Comparison: heading', 'astra-uaw' ), 'text', 'Without a union, and with one' ),
				'astra_compare_without_t' => array( __( 'Comparison: left card title', 'astra-uaw' ), 'text', 'Without a union' ),
				'astra_compare_without'   => array( __( 'Comparison: left card points', 'astra-uaw' ), 'textarea', 'Stevens unilaterally determines our working conditions and can change them at any time, without our consent.', __( 'One point per line.', 'astra-uaw' ) ),
				'astra_compare_with_t'    => array( __( 'Comparison: right card title', 'astra-uaw' ), 'text', 'With a union' ),
				'astra_compare_with'      => array( __( 'Comparison: right card points', 'astra-uaw' ), 'textarea', "We elect a bargaining committee that gathers input from student workers across campus.\nOur bargaining committee negotiates on equal footing toward a fair agreement with Stevens.\nWe decide democratically, through a vote, whether to approve any agreement as our contract.\nThat contract secures our terms and conditions of employment and is binding and enforceable, usually through appeal to a neutral arbitrator.", __( 'One point per line.', 'astra-uaw' ) ),
				'astra_explore_eyebrow'   => array( __( 'Explore: small label', 'astra-uaw' ), 'text', 'The campaign' ),
				'astra_explore_title'     => array( __( 'Explore: heading', 'astra-uaw' ), 'text', 'Explore' ),
				'astra_explore1_title'    => array( __( 'Explore card 1: title', 'astra-uaw' ), 'text', 'Who are we' ),
				'astra_explore1_text'     => array( __( 'Explore card 1: text', 'astra-uaw' ), 'text', 'The people behind the campaign, in their own words, and your organizers by department.' ),
				'astra_explore1_url'      => array( __( 'Explore card 1: link', 'astra-uaw' ), 'url', '/who-we-are/' ),
				'astra_explore2_title'    => array( __( 'Explore card 2: title', 'astra-uaw' ), 'text', 'International students' ),
				'astra_explore2_text'     => array( __( 'Explore card 2: text', 'astra-uaw' ), 'text', 'Support for international students, visa security, and research funding.' ),
				'astra_explore2_url'      => array( __( 'Explore card 2: link', 'astra-uaw' ), 'url', '/international/' ),
				'astra_explore3_title'    => array( __( 'Explore card 3: title', 'astra-uaw' ), 'text', 'FAQ' ),
				'astra_explore3_text'     => array( __( 'Explore card 3: text', 'astra-uaw' ), 'text', 'Confidential? Free? Safe for international workers? Answers here.' ),
				'astra_explore3_url'      => array( __( 'Explore card 3: link', 'astra-uaw' ), 'url', '/faq/' ),
			),
		),
		'astra_cta'     => array(
			'title'       => __( 'Red "Ready to be part of it?" band', 'astra-uaw' ),
			'description' => __( 'Shown near the bottom of most pages.', 'astra-uaw' ),
			'fields'      => array(
				'astra_cta_title'      => array( __( 'Heading', 'astra-uaw' ), 'text', 'Ready to be part of it?' ),
				'astra_cta_text'       => array( __( 'Text', 'astra-uaw' ), 'text', 'Signing your card is confidential and takes two minutes.' ),
				'astra_cta_btn1_label' => array( __( 'White button: label', 'astra-uaw' ), 'text', 'Sign your card' ),
				'astra_cta_btn1_url'   => array( __( 'White button: link', 'astra-uaw' ), 'url', '/sign-card/' ),
				'astra_cta_btn2_label' => array( __( 'Outline button: label', 'astra-uaw' ), 'text', 'Get updates' ),
				'astra_cta_btn2_url'   => array( __( 'Outline button: link', 'astra-uaw' ), 'url', '/get-involved/#listForm' ),
			),
		),
		'astra_footer'  => array(
			'title'       => __( 'Footer', 'astra-uaw' ),
			'description' => __( 'The Campaign and Get involved link lists are menus: Appearance > Menus.', 'astra-uaw' ),
			'fields'      => array(
				'astra_affiliation'    => array( __( 'Union affiliation', 'astra-uaw' ), 'text', 'UAW' ),
				'astra_contact_email'  => array( __( 'Contact email', 'astra-uaw' ), 'email', 'info@astra-uaw.org' ),
				'astra_social_heading' => array( __( 'Third column heading', 'astra-uaw' ), 'text', 'Follow' ),
				'astra_social_1_label' => array( __( 'Link 1: label', 'astra-uaw' ), 'text', '', __( 'e.g. Instagram. A link only shows when both its label and URL are filled in.', 'astra-uaw' ) ),
				'astra_social_1_url'   => array( __( 'Link 1: URL', 'astra-uaw' ), 'url', '' ),
				'astra_social_2_label' => array( __( 'Link 2: label', 'astra-uaw' ), 'text', '', __( 'e.g. Bluesky.', 'astra-uaw' ) ),
				'astra_social_2_url'   => array( __( 'Link 2: URL', 'astra-uaw' ), 'url', '' ),
				'astra_social_3_label' => array( __( 'Link 3: label', 'astra-uaw' ), 'text', 'Sign up for updates' ),
				'astra_social_3_url'   => array( __( 'Link 3: URL', 'astra-uaw' ), 'url', '/get-involved/#listForm' ),
				'astra_disclaimer'     => array( __( 'Disclaimer', 'astra-uaw' ), 'textarea', 'ASTRA is a worker-led organizing campaign. It is independent of, and not affiliated with or endorsed by, Stevens Institute of Technology. You have a federally protected right to organize.' ),
				'astra_copyright'      => array( __( 'Copyright line (after the year)', 'astra-uaw' ), 'text', 'Association of Stevens Teaching and Research Assistants. Made by workers, for workers.' ),
			),
		),
		'astra_signing' => array(
			'title'       => __( 'Card signing', 'astra-uaw' ),
			'description' => __( 'Union cards are legally significant and confidential. They must be collected on UAW\'s official, secure card platform, never on this website. Paste that platform\'s link here and the Sign your card page will send people there instead of showing the placeholder form.', 'astra-uaw' ),
			'fields'      => array(
				'astra_card_url'   => array( __( 'Official card platform link', 'astra-uaw' ), 'url', '' ),
				'astra_card_label' => array( __( 'Button label', 'astra-uaw' ), 'text', 'Sign your card securely' ),
			),
		),
	);
}

/**
 * Flat map of setting id => definition, cached per request.
 *
 * @return array
 */
function astra_uaw_customizer_fields() {
	static $fields = null;
	if ( null === $fields ) {
		$fields = array();
		foreach ( astra_uaw_customizer_sections() as $section ) {
			$fields = array_merge( $fields, $section['fields'] );
		}
	}
	return $fields;
}

/**
 * Read a campaign setting, falling back to its default.
 *
 * @param string $id Setting id.
 * @return string
 */
function astra_uaw_mod( $id ) {
	$fields  = astra_uaw_customizer_fields();
	$default = isset( $fields[ $id ] ) ? $fields[ $id ][2] : '';
	return (string) get_theme_mod( $id, $default );
}

/**
 * Sanitize callback for inline-HTML settings.
 *
 * @param string $value Raw value.
 * @return string
 */
function astra_uaw_sanitize_html( $value ) {
	return wp_kses( (string) $value, astra_uaw_inline_html() );
}

/**
 * Sanitize callback for link settings. Allows full URLs, site-relative paths
 * (/faq/) and in-page anchors (#issues).
 *
 * @param string $value Raw value.
 * @return string
 */
function astra_uaw_sanitize_link( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	return esc_url_raw( $value, array( 'http', 'https', 'mailto' ) );
}

/**
 * Sanitize callback for the stat numbers.
 *
 * @param string $value Raw value.
 * @return string
 */
function astra_uaw_sanitize_number( $value ) {
	return (string) absint( $value );
}

/**
 * Register the panel, sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function astra_uaw_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'astra_campaign',
		array(
			'title'    => __( 'ASTRA Campaign', 'astra-uaw' ),
			'priority' => 30,
		)
	);

	$sanitizers = array(
		'text'     => 'sanitize_text_field',
		'textarea' => 'sanitize_textarea_field',
		'html'     => 'astra_uaw_sanitize_html',
		'url'      => 'astra_uaw_sanitize_link',
		'email'    => 'sanitize_email',
		'number'   => 'astra_uaw_sanitize_number',
	);
	$controls   = array(
		'text'     => 'text',
		'textarea' => 'textarea',
		'html'     => 'textarea',
		'url'      => 'text',
		'email'    => 'email',
		'number'   => 'number',
	);

	$priority = 10;
	foreach ( astra_uaw_customizer_sections() as $section_id => $section ) {
		$wp_customize->add_section(
			$section_id,
			array(
				'title'       => $section['title'],
				'description' => $section['description'] ?? '',
				'panel'       => 'astra_campaign',
				'priority'    => $priority++,
			)
		);

		foreach ( $section['fields'] as $id => $def ) {
			$type = $def[1];
			$wp_customize->add_setting(
				$id,
				array(
					'default'           => $def[2],
					'sanitize_callback' => $sanitizers[ $type ],
					'transport'         => 'refresh',
				)
			);
			$control = array(
				'label'       => $def[0],
				'section'     => $section_id,
				'type'        => $controls[ $type ],
				'description' => $def[3] ?? '',
			);
			if ( 'number' === $type ) {
				$control['input_attrs'] = array( 'min' => 0 );
			}
			if ( 'url' === $type && '' === ( $def[3] ?? '' ) ) {
				$control['description'] = __( 'A full address (https://…), a page path like /faq/, or an anchor like #issues.', 'astra-uaw' );
			}
			$wp_customize->add_control( $id, $control );
		}
	}
}
add_action( 'customize_register', 'astra_uaw_customize_register' );
