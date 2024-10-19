<?php

include get_theme_file_path('/inc/search-routes.php');
include get_theme_file_path('/inc/like-routes.php');

function university_custom_rest()
{
    register_rest_field(
        'post',
        'authorName',
        array(
            'get_callback' => function () {
                return get_the_author();
            }
        )
    );

    register_rest_field(
        'note',
        'userNoteCount',
        array(
            'get_callback' => function () {
                return count_user_posts(get_current_user_id(), 'note');
            }
        )
    );
}
add_action('rest_api_init', 'university_custom_rest');

function pageBanner($args = null)
{
    if (!isset($args['title'])) {
        $args['title'] = get_the_title();
    }

    if (!isset($args['subtitle'])) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }

    if (!isset($args['photo'])) {
        if (get_field('page_banner_background_image') && !is_archive() && !is_home()) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }
    ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo esc_url($args['photo']); ?>)"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo esc_html($args['title']); ?></h1>
            <div class="page-banner__intro">
                <p><?php echo esc_html($args['subtitle']); ?></p>
            </div>
        </div>
    </div>

<?php }

function university_resources()
{
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyDdWMViYEjz1b39dMani-MtP2kO6qlN2ZA', null, '1.0', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
    wp_enqueue_style('google-custom-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

    wp_localize_script(
        'main-university-js',
        'universityData',
        array(
            'root_url' => esc_url(get_site_url()),
            'nonce' => wp_create_nonce('wp_rest')
        )
    );
}
add_action('wp_enqueue_scripts', 'university_resources');

function university_features()
{
    register_nav_menu('headerMenuLocation', __('Header Menu Location'));
    register_nav_menu('footerMenuOne', __('Footer Menu One'));
    register_nav_menu('footerMenuTwo', __('Footer Menu Two'));
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPotrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}
add_action('after_setup_theme', 'university_features');

// Adjust queries for custom post types
function university_adjust_queries($query)
{
    if (!is_admin() && $query->is_main_query()) {

        // Adjust query for 'event' post type archive
        if (is_post_type_archive('event')) {
            $query->set('post_type', 'event');
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', 'event_date');
            $query->set('order', 'ASC');
            $query->set(
                'meta_query',
                array(
                    array(
                        'key'     => 'event_date',
                        'compare' => '>=',
                        'value'   => date('Ymd'),
                        'type'    => 'numeric',
                    )
                )
            );
        }

        // Adjust query for 'program' post type archive
        if (is_post_type_archive('program')) {
            $query->set('posts_per_page', -1); // Corrected 'post_per_page' to 'posts_per_page'
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
        }

        // Adjust query for 'campus' post type archive
        if (is_post_type_archive('campus')) {
            $query->set('posts_per_page', -1); // Corrected 'post_per_page' to 'posts_per_page'
        }
    }
}
add_action('pre_get_posts', 'university_adjust_queries');

// Add Google Maps API key for ACF fields
function university_map_key($api)
{
    $api['key'] = ''; // Insert your Google Maps API key here
    return $api;
}
add_filter('acf/fields/google_map/api', 'university_map_key');

// Redirect subscribers to the home page after login
function redirect_subs_to_frontend()
{
    $current_user = wp_get_current_user();
    if (count($current_user->roles) === 1 && $current_user->roles[0] === 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}
add_action('admin_init', 'redirect_subs_to_frontend');

// Remove the admin bar for subscribers
function no_subs_admin_bar()
{
    $current_user = wp_get_current_user();
    if (count($current_user->roles) === 1 && $current_user->roles[0] === 'subscriber') {
        show_admin_bar(false);
    }
}
add_action('wp_loaded', 'no_subs_admin_bar');

// Customize the WordPress login logo URL
function our_header_url()
{
    return esc_url(site_url('/'));
}
add_filter('login_headerurl', 'our_header_url');

// Load custom CSS on WordPress login page
add_action( 'login_enqueue_scripts', 'our_login_css' );

function our_login_css() {
    wp_enqueue_style( 'google-custom-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i' );
    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'university-main-styles', get_theme_file_uri( '/build/style-index.css' ) );
    wp_enqueue_style( 'university-extra-styles', get_theme_file_uri( '/build/index.css' ) );
}

// Override WordPress login logo title with site name
add_filter( 'login_headertext', 'our_login_title' );

function our_login_title() {
    return get_bloginfo( 'name' );
}

// Force note posts to be private and limit note creation
add_filter( 'wp_insert_post_data', 'make_note_private', 10, 2 );

function make_note_private( $data, $postarr ) {
    if ( 'note' === $data['post_type'] ) {
        if ( count_user_posts( get_current_user_id(), 'note' ) > 4 && ! $postarr['ID'] ) {
            wp_die( esc_html__( 'You can only create 5 notes.' ) );
        }
        $data['post_content'] = sanitize_textarea_field( $data['post_content'] );
        $data['post_title']   = sanitize_text_field( $data['post_title'] );
        if ( 'trash' !== $data['post_status'] ) {
            $data['post_status'] = 'private';
        }
    }
    return $data;
}