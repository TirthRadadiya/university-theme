<?php

get_header();

while ( have_posts() ) {
    the_post();
    pageBanner();
    ?>

    <div class="container container--narrow page-section">

        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
                    <?php the_post_thumbnail( 'professorPotrait' ); ?>
                </div>
                <div class="two-thirds">
                    <?php
                    // Query to get like count for this professor
                    $like_count_query = new WP_Query(
                        array(
                            'post_type' => 'like',
                            'meta_query' => array(
                                array(
                                    'key' => 'liked_professor_id',
                                    'compare' => '=',
                                    'value' => get_the_ID(),
                                ),
                            ),
                        )
                    );

                    $exit_status = 'no';

                    // Check if the user is logged in and if they liked this professor
                    if ( is_user_logged_in() ) {
                        $exist_query = new WP_Query(
                            array(
                                'author' => get_current_user_id(),
                                'post_type' => 'like',
                                'meta_query' => array(
                                    array(
                                        'key' => 'liked_professor_id',
                                        'compare' => '=',
                                        'value' => get_the_ID(),
                                    ),
                                ),
                            )
                        );

                        if ( $exist_query->found_posts ) {
                            $exit_status = 'yes';
                        }
                    }
                    ?>

                    <span class="like-box"
                          data-like="<?php echo isset( $exist_query->posts[0]->ID ) ? esc_attr( $exist_query->posts[0]->ID ) : ''; ?>"
                          data-professor="<?php the_ID(); ?>"
                          data-exists="<?php echo esc_attr( $exit_status ); ?>">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        <span class="like-count"><?php echo esc_html( $like_count_query->found_posts ); ?></span>
                    </span>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>

        <?php
        // Get related programs
        $related_programs = get_field( 'related_programs' );
        if ( $related_programs ) {
            echo "<hr class='section-break'>";
            echo "<h2 class='headline headline--medium'>" . esc_html__( 'Subject(s) Taught', 'text-domain' ) . "</h2>";
            echo "<ul class='link-list min-list'>";

            foreach ( $related_programs as $program ) { ?>
                <li>
                    <a href="<?php echo esc_url( get_the_permalink( $program ) ); ?>">
                        <?php echo esc_html( get_the_title( $program ) ); ?>
                    </a>
                </li>
            <?php }
            echo "</ul>";
        }
        ?>
    </div>
<?php } get_footer(); ?>