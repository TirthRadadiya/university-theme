<?php

add_action( 'rest_api_init', 'university_rest_api' );

function university_rest_api() {
    register_rest_route(
        'university/v1',
        'search',
        array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'university_results',
        )
    );
}

function university_results( $data ) {
    $mix_results = new WP_Query(
        array(
            'post_type' => array( 'post', 'page', 'professor', 'event', 'program', 'campus' ),
            's'         => sanitize_text_field( $data['term'] ),
        )
    );

    $results = array(
        'generalInfo' => array(),
        'professors'  => array(),
        'programs'    => array(),
        'events'      => array(),
        'campuses'    => array(),
    );

    while ( $mix_results->have_posts() ) {
        $mix_results->the_post();

        if ( 'post' === get_post_type() || 'page' === get_post_type() ) {
            array_push(
                $results['generalInfo'],
                array(
                    'post_type'  => get_post_type(),
                    'title'      => get_the_title(),
                    'permalink'  => get_the_permalink(),
                    'authorName' => get_the_author(),
                )
            );
        }

        if ( 'professor' === get_post_type() ) {
            array_push(
                $results['professors'],
                array(
                    'title'     => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image'     => get_the_post_thumbnail_url( 0, 'professorLandscape' ),
                )
            );
        }

        if ( 'event' === get_post_type() ) {
            $event_date = new DateTime( get_field( 'event_date' ) );

            $description = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 18 );

            array_push(
                $results['events'],
                array(
                    'title'       => get_the_title(),
                    'permalink'   => get_the_permalink(),
                    'month'       => $event_date->format( 'M' ),
                    'day'         => $event_date->format( 'd' ),
                    'description' => $description,
                )
            );
        }

        if ( 'campus' === get_post_type() ) {
            array_push(
                $results['campuses'],
                array(
                    'title'     => get_the_title(),
                    'permalink' => get_the_permalink(),
                )
            );
        }

        if ( 'program' === get_post_type() ) {
            $related_campuses = get_field( 'related_campus' );

            if ( $related_campuses ) {
                foreach ( $related_campuses as $campus ) {
                    array_push(
                        $results['campuses'],
                        array(
                            'title'     => get_the_title( $campus ),
                            'permalink' => get_the_permalink( $campus ),
                        )
                    );
                }
            }

            array_push(
                $results['programs'],
                array(
                    'title'     => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'id'        => get_the_ID(),
                )
            );
        }
    }

    if ( $results['programs'] ) {
        $professor_meta_query = array( 'relationship' => 'OR' );

        foreach ( $results['programs'] as $item ) {
            array_push(
                $professor_meta_query,
                array(
                    'key'     => 'related_programs',
                    'compare' => 'LIKE',
                    'value'   => '"' . $item['id'] . '"',
                )
            );
        }

        $related_professors_program = new WP_Query(
            array(
                'post_type'  => array( 'professor', 'event' ),
                'meta_query' => $professor_meta_query,
            )
        );

        while ( $related_professors_program->have_posts() ) {
            $related_professors_program->the_post();

            if ( 'professor' === get_post_type() ) {
                array_push(
                    $results['professors'],
                    array(
                        'title'     => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'image'     => get_the_post_thumbnail_url( 0, 'professorLandscape' ),
                    )
                );
            }

            if ( 'event' === get_post_type() ) {
                $event_date = new DateTime( get_field( 'event_date' ) );

                $description = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 18 );

                array_push(
                    $results['events'],
                    array(
                        'title'       => get_the_title(),
                        'permalink'   => get_the_permalink(),
                        'month'       => $event_date->format( 'M' ),
                        'day'         => $event_date->format( 'd' ),
                        'description' => $description,
                    )
                );
            }
        }

        $results['professors'] = array_values( array_unique( $results['professors'], SORT_REGULAR ) );
        $results['events']     = array_values( array_unique( $results['events'], SORT_REGULAR ) );
        $results['campuses']   = array_values( array_unique( $results['campuses'], SORT_REGULAR ) );
    }

    return $results;
}