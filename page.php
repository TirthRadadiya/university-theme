<?php
get_header();

while ( have_posts() ) {
	the_post();
	pageBanner();
	?>

	<div class="container container--narrow page-section">
		<?php
		$parent_id = wp_get_post_parent_id( get_the_ID() );
		if ( $parent_id ) {
			?>
			<div class="metabox metabox--position-up metabox--with-home-link">
				<p>
					<a class="metabox__blog-home-link" href="<?php echo esc_url( get_permalink( $parent_id ) ); ?>">
						<i class="fa fa-home" aria-hidden="true"></i> 
						<?php esc_html_e( 'Back to', 'text-domain' ); ?> 
						<?php echo esc_html( get_the_title( $parent_id ) ); ?>
					</a>
					<span class="metabox__main"><?php the_title(); ?></span>
				</p>
			</div>
			<?php
		}

		$child_pages = get_pages(
			array(
				'child_of' => get_the_ID(),
			)
		);

		if ( $parent_id || $child_pages ) {
			?>
			<div class="page-links">
				<h2 class="page-links__title">
					<a href="<?php echo esc_url( get_permalink( $parent_id ) ); ?>">
						<?php echo esc_html( get_the_title( $parent_id ) ); ?>
					</a>
				</h2>
				<ul class="min-list">
					<?php
					$find_children_of = $parent_id ? $parent_id : get_the_ID();
					wp_list_pages(
						array(
							'title_li'    => null,
							'child_of'    => $find_children_of,
							'sort_column' => 'menu_order',
						)
					);
					?>
				</ul>
			</div>
			<?php
		}
		?>

		<div class="generic-content">
			<?php the_content(); ?>
		</div>
	</div>
	<?php
}

get_footer(); ?>