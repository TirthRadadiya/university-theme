<?php

add_action( 'rest_api_init', 'university_liked_routes' );

function university_liked_routes() {
    register_rest_route(
        'university/v1',
        'manageLike',
        array(
            'methods'  => 'POST',
            'callback' => 'create_like',
        )
    );

    register_rest_route(
        'university/v1',
        'manageLike',
        array(
            'methods'  => 'DELETE',
            'callback' => 'delete_like',
        )
    );
}

function create_like( $data ) {
    if ( is_user_logged_in() ) {
        $professor = sanitize_text_field( $data['professorId'] );

        $exist_query = new WP_Query(
            array(
                'author'     => get_current_user_id(),
                'post_type'  => 'like',
                'meta_query' => array(
                    array(
                        'key'     => 'liked_professor_id',
                        'compare' => '=',
                        'value'   => $professor,
                    ),
                ),
            )
        );

        if ( 0 === $exist_query->found_posts && 'professor' === get_post_type( $professor ) ) {
            return wp_insert_post(
                array(
                    'post_type'   => 'like',
                    'post_status' => 'publish',
                    'meta_input'  => array(
                        'liked_professor_id' => $professor,
                    ),
                )
            );
        } else {
            return new WP_Error( 'invalid_id', __( 'Invalid ID', 'text-domain' ), array( 'status' => 400 ) );
        }
    } else {
        return new WP_Error( 'not_logged_in', __( 'Only logged in users can create a like', 'text-domain' ), array( 'status' => 403 ) );
    }
}

function delete_like( $data ) {
    $like_id = sanitize_text_field( $data['like'] );

    if ( get_current_user_id() === get_post_field( 'post_author', $like_id ) && 'like' === get_post_type( $like_id ) ) {
        wp_delete_post( $like_id, true );
        return __( 'Like removed', 'text-domain' );
    } else {
        return new WP_Error( 'not_authorized', __( 'You are not authorized to delete this like', 'text-domain' ), array( 'status' => 403 ) );
    }
}