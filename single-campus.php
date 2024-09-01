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
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link("campus"); ?>"><i
                        class="fa fa-home" aria-hidden="true"></i> All Campuses
                </a> <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <div class="acf-map">

            <?php
            $mapLocation = get_field('map_location');
            ?>
            <div class='marker' data-lat="<?php echo $mapLocation['lat'] ?>" data-lng="<?php echo $mapLocation['lng'] ?>">
                <h3><?php the_title(); ?></h3>
                <?php echo $mapLocation['address'] ?>
            </div>


        </div>



        <?php
        wp_reset_postdata();

        $relatedPrograms = new WP_Query(
            array(
                'posts_per_page' => -1, // give all post id set to -1
                'post_type' => 'program',
                'orderby' => 'title',
                'order' => "ASC", // 'ASC' -> Ascending, 'DESC' -> Descending by default
                'meta_query' => array(
                    array(
                        'key' => 'related_campus',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            )
        );

        if ($relatedPrograms->have_posts()) {
            echo "<hr class='section-break' >";
            echo '<h2 class="headline headline--medium">Program available at this campus</h2>';
            echo '<ul class="min-list link-list">';
            while ($relatedPrograms->have_posts()) {
                $relatedPrograms->the_post(); ?>
                <li>
                    <a href="<?php echo the_permalink(); ?>">
                        <?php echo the_title(); ?>
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