<?php
/**
 * Sign your card page.
 *
 * LEGAL: union authorization cards are confidential and legally significant.
 * This theme never collects them. The form below is a front-end placeholder
 * only (nothing is stored or sent). Once UAW's official card platform link is
 * entered in Customize > ASTRA Campaign > Card signing, the placeholder is
 * replaced by a button to that platform.
 *
 * Page content: the first block is the intro paragraph, the numbered steps
 * (from the "Numbered steps" field) are printed after it, then the rest of the
 * content (the confidentiality note).
 *
 * @package astra-uaw
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$astra_uaw_steps    = astra_uaw_lines( (string) get_post_meta( get_the_ID(), '_astra_steps', true ) );
	$astra_uaw_card_url = astra_uaw_mod( 'astra_card_url' );

	$astra_uaw_blocks = array_values(
		array_filter(
			parse_blocks( get_the_content() ),
			static function ( $block ) {
				return null !== $block['blockName'] || '' !== trim( $block['innerHTML'] );
			}
		)
	);
	$astra_uaw_first  = $astra_uaw_blocks ? serialize_blocks( array_slice( $astra_uaw_blocks, 0, 1 ) ) : '';
	$astra_uaw_rest   = serialize_blocks( array_slice( $astra_uaw_blocks, 1 ) );
	?>
	<section class="section card-cta" id="card">
		<div class="container">
			<div class="card-cta__inner">
				<div class="card-cta__copy reveal">
					<?php astra_uaw_page_head( array( 'light' => true, 'wrap' => false ) ); ?>
					<?php echo apply_filters( 'the_content', $astra_uaw_first ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core content filter output. ?>

					<?php if ( $astra_uaw_steps ) : ?>
						<ol class="card-steps">
							<?php foreach ( $astra_uaw_steps as $astra_uaw_n => $astra_uaw_step ) : ?>
								<li class="step"><span class="step__n"><?php echo (int) $astra_uaw_n + 1; ?></span><span><?php echo esc_html( $astra_uaw_step ); ?></span></li>
							<?php endforeach; ?>
						</ol>
					<?php endif; ?>

					<?php echo apply_filters( 'the_content', $astra_uaw_rest ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core content filter output. ?>
				</div>

				<?php if ( '' !== $astra_uaw_card_url ) : ?>
					<div class="card-form" id="cardForm">
						<h3 class="card-form__title"><?php esc_html_e( 'Add your name', 'astra-uaw' ); ?></h3>
						<p><?php esc_html_e( 'Cards are collected on the union\'s secure, confidential platform. It opens in a new tab.', 'astra-uaw' ); ?></p>
						<a class="btn btn--primary btn--block" href="<?php echo esc_url( astra_uaw_link( $astra_uaw_card_url ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( astra_uaw_mod( 'astra_card_label' ) ); ?></a>
					</div>
				<?php else : ?>
					<?php // Placeholder only: see the LEGAL note at the top of this file. ?>
					<form class="card-form" id="cardForm" novalidate>
						<h3 class="card-form__title"><?php esc_html_e( 'Add your name', 'astra-uaw' ); ?></h3>

						<div class="field">
							<label for="f-name"><?php esc_html_e( 'Full name', 'astra-uaw' ); ?> <span aria-hidden="true">*</span></label>
							<input id="f-name" name="name" type="text" autocomplete="name" required />
						</div>

						<div class="field">
							<label for="f-email"><?php esc_html_e( 'Personal email', 'astra-uaw' ); ?> <span aria-hidden="true">*</span></label>
							<input id="f-email" name="email" type="email" autocomplete="email" placeholder="<?php esc_attr_e( 'you@gmail.com (not @stevens.edu)', 'astra-uaw' ); ?>" required />
						</div>

						<div class="field field--row">
							<div>
								<label for="f-role"><?php esc_html_e( 'Your role', 'astra-uaw' ); ?></label>
								<select id="f-role" name="role">
									<option value=""><?php esc_html_e( 'Select…', 'astra-uaw' ); ?></option>
									<option><?php esc_html_e( 'Graduate worker / PhD', 'astra-uaw' ); ?></option>
									<option><?php esc_html_e( 'Teaching assistant', 'astra-uaw' ); ?></option>
									<option><?php esc_html_e( 'Research assistant', 'astra-uaw' ); ?></option>
									<option><?php esc_html_e( 'Postdoctoral researcher', 'astra-uaw' ); ?></option>
									<option><?php esc_html_e( 'Research / academic staff', 'astra-uaw' ); ?></option>
									<option><?php esc_html_e( 'Other', 'astra-uaw' ); ?></option>
								</select>
							</div>
							<div>
								<label for="f-dept"><?php esc_html_e( 'Department', 'astra-uaw' ); ?></label>
								<input id="f-dept" name="dept" type="text" />
							</div>
						</div>

						<label class="check">
							<input type="checkbox" name="authorize" required />
							<span><?php esc_html_e( 'I authorize ASTRA to represent me for the purposes of collective bargaining.', 'astra-uaw' ); ?> <span aria-hidden="true">*</span></span>
						</label>

						<button class="btn btn--primary btn--block" type="submit"><?php esc_html_e( 'Sign my card', 'astra-uaw' ); ?></button>
						<p class="form__note" id="formNote" role="status" aria-live="polite"></p>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
