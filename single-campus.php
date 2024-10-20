<?php

get_header();

while ( have_posts() ) {
	the_post();
	pageBanner(
		array(
			'subtitle' => esc_html__( 'Learn how the school of your dreams got started.', 'text-domain' ),
		)
	);
	?>
	<div class="container container--narrow page-section">

		<div class="metabox metabox--position-up metabox--with-home-link">
			<p>
				<a class="metabox__blog-home-link" href="<?php echo esc_url( get_post_type_archive_link( 'campus' ) ); ?>">
					<i class="fa fa-home" aria-hidden="true"></i> <?php esc_html_e( 'All Campuses', 'text-domain' ); ?>
				</a>
				<span class="metabox__main"><?php the_title(); ?></span>
			</p>
		</div>

		<div class="generic-content">
			<?php the_content(); ?>
		</div>

		<div class="acf-map">
			<?php
			$map_location = get_field( 'map_location' );
			if ( $map_location ) {
				?>
				<div class='marker' data-lat="<?php echo esc_attr( $map_location['lat'] ); ?>" data-lng="<?php echo esc_attr( $map_location['lng'] ); ?>">
					<h3><?php the_title(); ?></h3>
					<?php echo esc_html( $map_location['address'] ); ?>
				</div>
				<?php
			}
			?>
		</div>

		<?php
		wp_reset_postdata();

		$related_programs = new WP_Query(
			array(
				'posts_per_page' => -1, // Show all related programs
				'post_type'      => 'program',
				'orderby'        => 'title',
				'order'          => 'ASC', // Alphabetical order
				'meta_query'     => array(
					array(
						'key'     => 'related_campus',
						'compare' => 'LIKE',
						'value'   => '"' . get_the_ID() . '"', // Ensure to include the current campus ID in the query
					),
				),
			)
		);

		if ( $related_programs->have_posts() ) {
			echo "<hr class='section-break'>";
			echo '<h2 class="headline headline--medium">' . esc_html__( 'Programs available at this campus', 'text-domain' ) . '</h2>';
			echo '<ul class="min-list link-list">';
			while ( $related_programs->have_posts() ) {
				$related_programs->the_post();
				?>
				<li>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>
				<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
		?>
	</div>
	<?php
}
get_footer(); ?>