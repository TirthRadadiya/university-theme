<?php

include get_theme_file_path("/inc/search-routes.php");
include get_theme_file_path("/inc/like-routes.php");


function university_custom_rest()
{
    register_rest_field(
        "post",
        "authorName",
        array(
            'get_callback' => function () {
                return get_the_author();
            }
        )
    );

    register_rest_field(
        "note",
        "userNoteCount",
        array(
            'get_callback' => function () {
                return count_user_posts(get_current_user_id(), "note");
            }
        )
    );
}

add_action('rest_api_init', 'university_custom_rest');

function pageBanner($args = NULL)
{
    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }

    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if (!isset($args['photo'])) {
        if (get_field('page_banner_background_image') and !is_archive() and !is_home()) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri("/images/ocean.jpg");
        }
    }

    ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)"></div>
        <div class=" page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle'] ?></p>
            </div>
        </div>
    </div>

<?php }

function university_resources()
{
    wp_enqueue_script("googleMap", "//maps.googleapis.com/maps/api/js?key=AIzaSyDdWMViYEjz1b39dMani-MtP2kO6qlN2ZA", NULL, '1.0', true);
    wp_enqueue_script("main-university-js", get_theme_file_uri("/build/index.js"), array("jquery"), '1.0', true);
    wp_enqueue_style("google-custom-fonts", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style("font-awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university_main_styles", get_theme_file_uri("/build/style-index.css"));
    wp_enqueue_style("university_extra_styles", get_theme_file_uri("/build/index.css"));

    wp_localize_script(
        'main-university-js',
        'universityData',
        array(
            "root_url" => get_site_url(),
            'nonce' => wp_create_nonce('wp_rest')
        )
    );
}


add_action("wp_enqueue_scripts", "university_resources");

function university_features()
{
    register_nav_menu("headerMenuLocation", "Header Menu Location");
    register_nav_menu("footerMenuOne", "Footer Menu One");
    register_nav_menu("footerMenuTwo", "Footer Menu Two");
    add_theme_support("title-tag");
    add_theme_support("post-thumbnails");
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPotrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action("after_setup_theme", 'university_features');


function university_adjust_queries($query)
{
    if (!is_admin() and is_post_type_archive('event') and $query->is_main_query()) {
        $query->set('post_type', 'event');
        $query->set('orderby', 'meta_value_num');
        $query->set('meta_key', 'event_date');
        $query->set('order', 'ASC');
        $query->set(
            'meta_query',
            array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => date('Ymd'),
                    'type' => 'numeric'
                )
            )
        );
    }

    if (!is_admin() and is_post_type_archive('program') and $query->is_main_query()) {
        $query->set('post_per_page', '-1');
        $query->set('orderby', 'title');
        // $query->set('meta_key', 'event_date');
        $query->set('order', 'ASC');
    }

    if (!is_admin() and is_post_type_archive('campus') and $query->is_main_query()) {
        $query->set('post_per_page', '-1');
    }
}


add_action('pre_get_posts', 'university_adjust_queries');

function universityMapKey($api)
{
    $api['key'] = 'AIzaSyDdWMViYEjz1b39dMani-MtP2kO6qlN2ZA';
    return $api;
}

add_filter('acf/fields/google_map/api', 'universityMapKey');

// Redirect user to home page after login
add_action('admin_init', 'redirectSubsToFrontEnd');

function redirectSubsToFrontEnd()
{
    $currentUser = wp_get_current_user();

    if (count($currentUser->roles) == 1 and $currentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url("/"));
        exit;
    }
}

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar()
{
    $currentUser = wp_get_current_user();

    if (count($currentUser->roles) == 1 and $currentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}


// login wp logo url -> in login screen default url is different you can change by like this
add_filter("login_headerurl", 'ourHeaderUrl');

function ourHeaderUrl()
{
    return esc_url(site_url('/'));
}

// How to load our custom CSS on wordpress login page?
add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS()
{
    wp_enqueue_style("google-custom-fonts", "//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style("font-awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style("university_main_styles", get_theme_file_uri("/build/style-index.css"));
    wp_enqueue_style("university_extra_styles", get_theme_file_uri("/build/index.css"));
}

// in new wordpress you will see Powered by wordpress instead logo, So how to override that and add your site name?
add_filter('login_headerTitle', 'ourLoginTitle');

function ourLoginTitle()
{
    return get_bloginfo('name');
}

// Force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);

function makeNotePrivate($data, $postData)
{

    if ($data['post_type'] == 'note') {
        if (count_user_posts(get_current_user_id(), 'note') > 4 and !$postData['ID']) {
            die("You can only create 5 notes.");
        }
    }

    if ($data['post_type'] == 'note') {
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }

    if ($data['post_type'] == 'note' and $data['post_status'] != 'trash') {
        $data['post_status'] = 'private';
    }

    return $data;
}