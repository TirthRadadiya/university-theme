<?php

get_header();

pageBanner(
	array(
		'title'    => get_the_archive_title(),
		'subtitle' => get_the_archive_description(),
	)
);
?>

<div class="container container--narrow page-section">
	<?php
	while ( have_posts() ) {
		the_post(); ?>
		<div class="post-item">
			<h2 class="headline headline--medium headline--post-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>

			<div class="metabox metabox--position-up metabox--with-home-link">
				<p>
					<span class="metabox__main">
						<?php
						printf(
							esc_html__( 'Posted by %s on %s in %s', 'text-domain' ),
							get_the_author_posts_link(),
							get_the_time( 'n.j.y' ),
							get_the_category_list( ', ' )
						);
						?>
					</span>
				</p>
			</div>

			<div class="generic-content">
				<?php the_excerpt(); ?>
				<p>
					<a class="btn btn--blue" href="<?php the_permalink(); ?>">
						<?php esc_html_e( 'Continue Reading &raquo;', 'text-domain' ); ?>
					</a>
				</p>
			</div>
		</div>

	<?php }

	echo paginate_links();
	?>
</div>

<?php get_footer(); ?>
