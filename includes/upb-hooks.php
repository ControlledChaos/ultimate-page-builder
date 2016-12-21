<?php

    defined( 'ABSPATH' ) or die( 'Keep Silent' );

    function upb_elements_register_action() {
        do_action( 'upb_register_element', upb_elements() );
    }

    add_action( 'wp_loaded', 'upb_elements_register_action' );

    function upb_tabs_register_action() {
        do_action( 'upb_register_tab', upb_tabs() );
    }

    add_action( 'wp_loaded', 'upb_tabs_register_action' );

    function upb_settings_register_action() {
        do_action( 'upb_register_setting', upb_settings() );
    }

    add_action( 'wp_loaded', 'upb_settings_register_action' );


    // Content Load

    add_filter( 'upb-before-contents', function ( $contents, $shortcodes ) {
        ob_start();

        upb_get_template( 'wrapper/before.php', compact( 'contents', 'shortcodes' ) );

        return ob_get_clean();
    }, 10, 2 );

    add_filter( 'upb-on-contents', function ( $contents, $shortcodes ) {
        ob_start();
        upb_get_template( 'wrapper/contents.php', compact( 'contents', 'shortcodes' ) );

        return ob_get_clean();
    }, 10, 2 );

    add_filter( 'upb-after-contents', function ( $contents, $shortcodes ) {
        ob_start();

        upb_get_template( 'wrapper/after.php', compact( 'contents', 'shortcodes' ) );

        return ob_get_clean();
    }, 10, 2 );

    add_filter( 'the_content', function ( $contents ) {

        if ( upb_is_enabled() ):
            $position   = get_post_meta( get_the_ID(), '_upb_settings_page_position', TRUE );
            $shortcodes = get_post_meta( get_the_ID(), '_upb_shortcodes', TRUE );

            return apply_filters( $position, $contents, $shortcodes );
        endif;

        return $contents;


    } );

    // Body Class
    add_filter( 'body_class', function ( $classes ) {
        if ( upb_is_enabled() ):
            array_push( $classes, 'ultimate-page-builder' );
        endif;

        return $classes;
    } );

    // Scripts
    add_action( 'wp_enqueue_scripts', function () {

        if ( upb_is_enabled() ):
            wp_enqueue_style( 'upb-grid' );
        endif;
    } );


