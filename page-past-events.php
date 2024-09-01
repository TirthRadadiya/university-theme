<?php

get_header();
pageBanner(
    array(
        'title' => "Past Events",
        "subtitle" => "See What we we have been upto and don't miss a chance to join us..."
    )
);

?>

<div class="container container--narrow page-section">
    <?php
    $pastEventsPage = new WP_Query(
        array(
            'paged' => get_query_var('paged', 1),
            // 'posts_per_page' => 1, // give all post id set to -1
            'post_type' => 'event',
            'orderby' => 'meta_value_num', // 'title' -> alphabatically order by title, 'rand' -> getting random order, meta_value will enable sorting by custom fiels, meta_value is suitable for string values, we can use meta_value_num for numbers
            'meta_key' => 'event_date', // after setting meta_value need to tell word press by which custom value we want sorting 
            'order' => "ASC", // 'ASC' -> Ascending, 'DESC' -> Descending by default
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'compare' => '<',
                    'value' => date('Ymd'),
                    'type' => 'numeric'
                )
            )
        )
    );
    while ($pastEventsPage->have_posts()) {
        $pastEventsPage->the_post(); 
        get_template_part("template-parts/content", "event");
    }
    echo paginate_links(
        array(
            'total' => $pastEventsPage->max_num_pages
        )
    );
    ?>
</div>

<?php
get_footer();
?>