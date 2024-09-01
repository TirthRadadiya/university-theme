<?php

get_header();
pageBanner(
    array(
        'title' => get_the_archive_title(),
        "subtitle" => get_the_archive_description()
    )
);
?>



<div class="container container--narrow page-section">
    <?php while (have_posts()) {
        the_post(); ?>
        <div class="post-item">
            <h2 class="headline headline--medium headline--post-title"><a
                    href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>

            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <span class="metabox__main">
                        Posted by <?php the_author_posts_link();
                        echo " at ";
                        the_time('n.j.y');
                        echo " in ";
                        echo get_the_category_list(", "); ?>
                    </span>
                </p>
            </div>

            <div class="generic-content">
                <?php the_excerpt(); ?>
                <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue Reading &raquo;</a></p>
            </div>
        </div>

    <?php }
    echo paginate_links();
    ?>
</div>

<?php
get_footer();
?>