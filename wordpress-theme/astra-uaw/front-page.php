<?php
/**
 * Home page.
 *
 * Hero, stats, banner and section text come from Appearance > Customize >
 * ASTRA Campaign. The "Who we are" paragraphs are this page's own content.
 * Issue cards are the Issues post type.
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

get_header();

$astra_uaw_marquee = astra_uaw_lines( astra_uaw_mod( 'astra_marquee' ) );
?>
<section class="hero" id="hero">
	<div class="hero__bg" aria-hidden="true"></div>
	<div class="hero__inner container">
		<?php if ( '' !== astra_uaw_mod( 'astra_hero_eyebrow' ) ) : ?>
			<p class="hero__eyebrow"><?php echo esc_html( astra_uaw_mod( 'astra_hero_eyebrow' ) ); ?></p>
		<?php endif; ?>
		<h1 class="hero__title"><?php echo wp_kses( astra_uaw_mod( 'astra_hero_title' ), astra_uaw_inline_html() ); ?></h1>
		<?php if ( '' !== astra_uaw_mod( 'astra_hero_lede' ) ) : ?>
			<p class="hero__lede"><?php echo esc_html( astra_uaw_mod( 'astra_hero_lede' ) ); ?></p>
		<?php endif; ?>
		<div class="hero__actions">
			<?php
			$astra_uaw_hero_buttons = array(
				array( 'astra_hero_btn1_label', 'astra_hero_btn1_url', 'btn btn--primary' ),
				array( 'astra_hero_btn2_label', 'astra_hero_btn2_url', 'btn btn--ghost' ),
			);
			foreach ( $astra_uaw_hero_buttons as $astra_uaw_b ) {
				$astra_uaw_label = astra_uaw_mod( $astra_uaw_b[0] );
				$astra_uaw_url   = astra_uaw_link( astra_uaw_mod( $astra_uaw_b[1] ), true );
				if ( '' !== $astra_uaw_label && '' !== $astra_uaw_url ) {
					printf( '<a class="%1$s" href="%2$s">%3$s</a>', esc_attr( $astra_uaw_b[2] ), esc_url( $astra_uaw_url ), esc_html( $astra_uaw_label ) );
				}
			}
			?>
		</div>

		<ul class="hero__stats" aria-label="<?php esc_attr_e( 'Campaign at a glance', 'astra-uaw' ); ?>">
			<?php for ( $astra_uaw_i = 1; $astra_uaw_i <= 3; $astra_uaw_i++ ) : ?>
				<?php
				$astra_uaw_num   = astra_uaw_mod( "astra_stat{$astra_uaw_i}_num" );
				$astra_uaw_label = astra_uaw_mod( "astra_stat{$astra_uaw_i}_label" );
				if ( '' === $astra_uaw_num && '' === $astra_uaw_label ) {
					continue;
				}
				?>
				<li class="stat">
					<span class="stat__num" data-count="<?php echo esc_attr( $astra_uaw_num ); ?>">0</span>
					<span class="stat__label"><?php echo esc_html( $astra_uaw_label ); ?></span>
				</li>
			<?php endfor; ?>
		</ul>
	</div>
</section>

<?php if ( $astra_uaw_marquee ) : ?>
<div class="marquee" aria-hidden="true">
	<div class="marquee__track">
		<?php
		// Printed twice so the scroll loops seamlessly.
		for ( $astra_uaw_pass = 0; $astra_uaw_pass < 2; $astra_uaw_pass++ ) {
			foreach ( $astra_uaw_marquee as $astra_uaw_phrase ) {
				printf( '<span class="marquee__item">%s</span><span class="marquee__star">&#9733;</span>', esc_html( $astra_uaw_phrase ) );
			}
		}
		?>
	</div>
</div>
<?php endif; ?>

<section class="section about" id="about">
	<div class="container">
		<?php astra_uaw_section_head( astra_uaw_mod( 'astra_about_eyebrow' ), astra_uaw_mod( 'astra_about_title' ) ); ?>
		<div class="about__grid">
			<div class="about__text reveal">
				<?php
				while ( have_posts() ) {
					the_post();
					the_content();
				}
				?>
			</div>
			<ul class="about__points reveal">
				<?php for ( $astra_uaw_i = 1; $astra_uaw_i <= 4; $astra_uaw_i++ ) : ?>
					<?php
					$astra_uaw_k = astra_uaw_mod( "astra_point{$astra_uaw_i}_k" );
					$astra_uaw_v = astra_uaw_mod( "astra_point{$astra_uaw_i}_v" );
					if ( '' === $astra_uaw_k && '' === $astra_uaw_v ) {
						continue;
					}
					?>
					<li class="point">
						<span class="point__k"><?php echo esc_html( $astra_uaw_k ); ?></span>
						<span class="point__v"><?php echo wp_kses( $astra_uaw_v, astra_uaw_inline_html() ); ?></span>
					</li>
				<?php endfor; ?>
			</ul>
		</div>
	</div>
</section>

<?php $astra_uaw_issues = astra_uaw_query( 'astra_issue' ); ?>
<section class="section issues" id="issues">
	<div class="container">
		<?php astra_uaw_section_head( astra_uaw_mod( 'astra_issues_eyebrow' ), astra_uaw_mod( 'astra_issues_title' ), astra_uaw_mod( 'astra_issues_intro' ) ); ?>

		<?php if ( $astra_uaw_issues->have_posts() ) : ?>
			<div class="issue-grid">
				<?php
				while ( $astra_uaw_issues->have_posts() ) :
					$astra_uaw_issues->the_post();
					$astra_uaw_icon = (string) get_post_meta( get_the_ID(), '_astra_icon', true );
					?>
					<article class="issue reveal">
						<?php if ( '' !== $astra_uaw_icon ) : ?>
							<span class="issue__icon" aria-hidden="true"><?php echo esc_html( $astra_uaw_icon ); ?></span>
						<?php endif; ?>
						<h3 class="issue__title"><?php the_title(); ?></h3>
						<div class="issue__desc"><?php the_content(); ?></div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
	</div>
</section>

<section class="section compare" id="compare">
	<div class="container">
		<?php astra_uaw_section_head( astra_uaw_mod( 'astra_compare_eyebrow' ), astra_uaw_mod( 'astra_compare_title' ) ); ?>
		<div class="compare__grid">
			<article class="compare__card compare__card--without reveal">
				<h3><span class="compare__mark" aria-hidden="true">&times;</span> <?php echo esc_html( astra_uaw_mod( 'astra_compare_without_t' ) ); ?></h3>
				<ul class="compare__list">
					<?php foreach ( astra_uaw_lines( astra_uaw_mod( 'astra_compare_without' ) ) as $astra_uaw_line ) : ?>
						<li><?php echo esc_html( $astra_uaw_line ); ?></li>
					<?php endforeach; ?>
				</ul>
			</article>
			<article class="compare__card compare__card--with reveal">
				<h3><span class="compare__mark compare__mark--yes" aria-hidden="true">&#10003;</span> <?php echo esc_html( astra_uaw_mod( 'astra_compare_with_t' ) ); ?></h3>
				<ul class="compare__list">
					<?php foreach ( astra_uaw_lines( astra_uaw_mod( 'astra_compare_with' ) ) as $astra_uaw_line ) : ?>
						<li><?php echo esc_html( $astra_uaw_line ); ?></li>
					<?php endforeach; ?>
				</ul>
			</article>
		</div>
	</div>
</section>

<section class="section explore-section">
	<div class="container">
		<?php astra_uaw_section_head( astra_uaw_mod( 'astra_explore_eyebrow' ), astra_uaw_mod( 'astra_explore_title' ), '', array( 'reveal' => false ) ); ?>
		<div class="explore-grid">
			<?php for ( $astra_uaw_i = 1; $astra_uaw_i <= 3; $astra_uaw_i++ ) : ?>
				<?php
				$astra_uaw_title = astra_uaw_mod( "astra_explore{$astra_uaw_i}_title" );
				$astra_uaw_url   = astra_uaw_link( astra_uaw_mod( "astra_explore{$astra_uaw_i}_url" ) );
				if ( '' === $astra_uaw_title || '' === $astra_uaw_url ) {
					continue;
				}
				?>
				<a class="explore-card" href="<?php echo esc_url( $astra_uaw_url ); ?>"><h3><?php echo esc_html( $astra_uaw_title ); ?> &rarr;</h3><p><?php echo esc_html( astra_uaw_mod( "astra_explore{$astra_uaw_i}_text" ) ); ?></p></a>
			<?php endfor; ?>
		</div>
	</div>
</section>

<?php
get_template_part( 'template-parts/cta-band' );
get_footer();
