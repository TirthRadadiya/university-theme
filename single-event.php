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
                <a class="metabox__blog-home-link" href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> <?php esc_html_e( 'Events Home', 'text-domain' ); ?>
                </a>
                <span class="metabox__main"><?php the_title(); ?></span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_field( 'main_body_content' ); ?>
        </div>

        <?php
        $related_programs = get_field( 'related_programs' );
        if ( $related_programs ) {
            echo "<h2 class='headline headline--medium'>" . esc_html__( 'Related Programs', 'text-domain' ) . "</h2>";
            echo "<hr class='section-break'>";
            echo "<ul class='link-list min-list'>";

            foreach ( $related_programs as $program ) { ?>
                <li>
                    <a href="<?php echo esc_url( get_the_permalink( $program ) ); ?>"><?php echo esc_html( get_the_title( $program ) ); ?></a>
                </li>
            <?php }
            echo "</ul>";
        }
        ?>
    </div>
<?php } get_footer(); ?>