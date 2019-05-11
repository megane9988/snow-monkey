<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 */

use Framework\Helper;
?>

<?php
if ( Helper::is_active_sidebar( 'archive-top-widget-area' ) ) {
	Helper::get_template_part( 'template-parts/widget-area/archive-top' );
}
?>

<div class="c-entry">
	<header class="c-entry__header">
		<h1 class="c-entry__title"><?php echo wp_kses_post( Helper::get_page_title_from_breadcrumbs() ); ?></h1>
	</header>

	<div class="c-entry__body">
		<?php if ( ! is_paged() && term_description() ) : ?>
			<div class="p-term-description p-entry-content">
				<?php echo wp_kses_post( term_description() ); ?>
			</div>
		<?php endif; ?>

		<div class="c-entry__content p-entry-content">
			<div class="p-archive">
				<?php
				$infeed_ads      = get_option( 'mwt-google-infeed-ads' );
				$data_infeed_ads = ( $infeed_ads ) ? 'true' : 'false';
				$archive_layout  = get_theme_mod( get_post_type() . '-entries-layout' );
				?>

				<ul class="c-entries c-entries--<?php echo esc_attr( $archive_layout ); ?>" data-has-infeed-ads="<?php echo esc_attr( $data_infeed_ads ); ?>">
					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>
						<li class="c-entries__item">
							<?php Helper::get_template_part( 'template-parts/loop/entry-summary', get_post_type() ); ?>
						</li>
					<?php endwhile; ?>
				</ul>
			</div>
		</div>

		<?php
		if ( ! empty( $wp_query->max_num_pages ) && $wp_query->max_num_pages >= 2 ) {
			Helper::get_template_part( 'template-parts/archive/pagination' );
		}
		?>
	</div>
</div>
