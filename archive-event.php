<?php
get_header();

pageBanner(
	array(
		'title'    => esc_html__( 'All Events', 'text-domain' ),
		'subtitle' => esc_html__( 'See what we are up to and be part of it...', 'text-domain' ),
	)
);
?>

<div class="container container--narrow page-section">
	<?php
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', 'event' );
	}
	echo paginate_links();
	?>

	<hr class="section-break">
	<p>
		<?php esc_html_e( 'Looking for our past event?', 'text-domain' ); ?>
		<a href="<?php echo esc_url( site_url( '/past-events' ) ); ?>">
			<?php esc_html_e( 'Check out our past event archive', 'text-domain' ); ?>
		</a>
	</p>
</div>

<?php get_footer(); ?>
