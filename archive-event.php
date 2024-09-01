<?php
get_header();
pageBanner(
    array(
        'title' => "All Events",
        "subtitle" => "See What we are upto and be part of it..."
    )
);
?>



<div class="container container--narrow page-section">
    <?php while (have_posts()) {
        the_post();
        get_template_part("template-parts/content", "event");
    }
    echo paginate_links();
    ?>

    <hr class="section-break">
    <p>Looking for our past event <a href="<?php echo site_url('/past-events') ?>">Check out our past event archive</a>
    </p>
</div>

<?php
get_footer();
?>