<?php

get_header();

while (have_posts()) {
    the_post();
    pageBanner(
        array(
            "subtitle" => "Learn how the school of your dreams got started."
        )
    );
    ?>
    <div class="container container--narrow page-section">

        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link("program"); ?>"><i
                        class="fa fa-home" aria-hidden="true"></i> All Programs
                </a> <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php

        $relatedProfessors = new WP_Query(
            array(
                'posts_per_page' => -1, // give all post id set to -1
                'post_type' => 'professor',
                'orderby' => 'title',
                'order' => "ASC", // 'ASC' -> Ascending, 'DESC' -> Descending by default
                'meta_query' => array(
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            )
        );

        if ($relatedProfessors->have_posts()) {
            echo "<hr class='section-break' >";
            echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors </h2>';

            echo "<ul class='professor-cards'>";
            while ($relatedProfessors->have_posts()) {
                $relatedProfessors->the_post(); ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink() ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape') ?>" />
                        <span class="professor-card__name"><?php the_title(); ?></span>
                    </a>
                </li>

            <?php }
            echo "</ul>";
        }

        wp_reset_postdata();

        $relatedEvents = new WP_Query(
            array(
                'posts_per_page' => 2, // give all post id set to -1
                'post_type' => 'event',
                'orderby' => 'meta_value_num', // 'title' -> alphabatically order by title, 'rand' -> getting random order, meta_value will enable sorting by custom fiels, meta_value is suitable for string values, we can use meta_value_num for numbers
                'meta_key' => 'event_date', // after setting meta_value need to tell word press by which custom value we want sorting 
                'order' => "ASC", // 'ASC' -> Ascending, 'DESC' -> Descending by default
                'meta_query' => array(
                    array(
                        'key' => 'event_date',
                        'compare' => '>=',
                        'value' => date('Ymd'),
                        'type' => 'numeric'
                    ),
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            )
        );

        if ($relatedEvents->have_posts()) {
            echo "<hr class='section-break' >";
            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events </h2>';

            while ($relatedEvents->have_posts()) {
                $relatedEvents->the_post();
                get_template_part("template-parts/content", "event");
            }
        }


        wp_reset_postdata();

        $relatedCampus = get_field('related_campus');
        if ($relatedCampus) {
            echo "<hr class='section-break' >";
            echo '<h2 class="headline headline--medium">' . get_the_title() . ' is available at these campus</h2>';
            echo '<ul class="min-list link-list">';
            foreach ($relatedCampus as $campus) { ?>
                <li>
                    <a href="<?php echo get_the_permalink($campus); ?>">
                        <?php echo get_the_title($campus); ?>
                    </a>
                </li>
            <?php }
            echo "</ul>";
        }
        ?>

    </div>
<?php }
get_footer();
?>