<?php

get_header();

pageBanner(
	array(
		'title'    => esc_html__( 'Past Events', 'text-domain' ),
		'subtitle' => esc_html__( "See what we've been up to and don't miss a chance to join us...", 'text-domain' ),
	)
);
?>

<div class="container container--narrow page-section">
	<?php
	$past_events_page = new WP_Query(
		array(
			'paged'          => get_query_var( 'paged', 1 ),
			'post_type'      => 'event',
			'orderby'        => 'meta_value_num',
			'meta_key'       => 'event_date',
			'order'          => 'ASC',
			'meta_query'     => array(
				array(
					'key'     => 'event_date',
					'compare' => '<',
					'value'   => date( 'Ymd' ),
					'type'    => 'numeric',
				),
			),
		)
	);

	while ( $past_events_page->have_posts() ) {
		$past_events_page->the_post();
		get_template_part( 'template-parts/content', 'event' );
	}

	echo paginate_links(
		array(
			'total' => $past_events_page->max_num_pages,
		)
	);
	?>
</div>

<?php get_footer(); ?>