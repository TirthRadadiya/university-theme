<?php

get_header();

while ( have_posts() ) {
    the_post();
    pageBanner(
        array(
            'subtitle' => 'Learn how the school of your dreams got started.',
        )
    );
    ?>
    <div class="container container--narrow page-section">

        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo esc_url( get_post_type_archive_link( 'program' ) ); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All Programs
                </a>
                <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php
        // Query to get related professors
        $related_professors = new WP_Query(
            array(
                'posts_per_page' => -1, // Show all posts
                'post_type' => 'professor',
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"',
                    ),
                ),
            )
        );

        if ( $related_professors->have_posts() ) {
            echo "<hr class='section-break'>";
            echo '<h2 class="headline headline--medium">' . esc_html( get_the_title() ) . ' Professors</h2>';
            echo "<ul class='professor-cards'>";

            while ( $related_professors->have_posts() ) {
                $related_professors->the_post(); ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image" src="<?php echo esc_url( get_the_post_thumbnail_url( null, 'professorLandscape' ) ); ?>" alt="<?php esc_attr_e( 'Professor Image', 'text-domain' ); ?>" />
                        <span class="professor-card__name"><?php the_title(); ?></span>
                    </a>
                </li>
            <?php }
            echo "</ul>";
        }

        wp_reset_postdata();

        // Query to get related events
        $related_events = new WP_Query(
            array(
                'posts_per_page' => 2, // Limit to 2 events
                'post_type' => 'event',
                'orderby' => 'meta_value_num',
                'meta_key' => 'event_date',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'event_date',
                        'compare' => '>=',
                        'value' => date( 'Ymd' ),
                        'type' => 'numeric',
                    ),
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"',
                    ),
                ),
            )
        );

        if ( $related_events->have_posts() ) {
            echo "<hr class='section-break'>";
            echo '<h2 class="headline headline--medium">Upcoming ' . esc_html( get_the_title() ) . ' Events</h2>';

            while ( $related_events->have_posts() ) {
                $related_events->the_post();
                get_template_part( 'template-parts/content', 'event' );
            }
        }

        wp_reset_postdata();

        // Get related campus
        $related_campus = get_field( 'related_campus' );
        if ( $related_campus ) {
            echo "<hr class='section-break'>";
            echo '<h2 class="headline headline--medium">' . esc_html( get_the_title() ) . ' is available at these campus</h2>';
            echo '<ul class="min-list link-list">';
            foreach ( $related_campus as $campus ) { ?>
                <li>
                    <a href="<?php echo esc_url( get_the_permalink( $campus ) ); ?>">
                        <?php echo esc_html( get_the_title( $campus ) ); ?>
                    </a>
                </li>
            <?php }
            echo "</ul>";
        }
        ?>

    </div>
<?php } get_footer(); ?>