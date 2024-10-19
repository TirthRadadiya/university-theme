<?php

get_header();
pageBanner(
	array(
		'title'    => esc_html__( 'All Programs', 'text-domain' ),
		'subtitle' => esc_html__( 'There is something for everyone, Have a look around...', 'text-domain' ),
	)
);
?>

<div class="container container--narrow page-section">
	<ul class="link-list min-list">
		<?php
		while ( have_posts() ) {
			the_post();
			?>
			<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php }
		echo paginate_links();
		?>
	</ul>
</div>

<?php get_footer(); ?>
