<?php

get_header();

pageBanner(
	array(
		'title'    => esc_html__( 'Our Campuses', 'text-domain' ),
		'subtitle' => esc_html__( 'We have several conveniently located campuses...', 'text-domain' ),
	)
);
?>

<div class="container container--narrow page-section">
	<div class="acf-map">
		<?php
		while ( have_posts() ) {
			the_post();
			$map_location = get_field( 'map_location' );
			if ( $map_location ) {
				?>
				<div class="marker" data-lat="<?php echo esc_attr( $map_location['lat'] ); ?>" data-lng="<?php echo esc_attr( $map_location['lng'] ); ?>">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php echo esc_html( $map_location['address'] ); ?>
				</div>
				<?php
			}
		}
		?>
	</div>
</div>

<?php get_footer(); ?>