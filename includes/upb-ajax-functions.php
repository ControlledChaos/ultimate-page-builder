<?php

    defined( 'ABSPATH' ) or die( 'Keep Silent' );


    // AJAX Requests
    add_action( 'wp_ajax__upb_save', function () {

        // Should have edit_pages cap :)
        if ( ! current_user_can( 'edit_pages' ) ) {
            status_header( 403 );
            wp_send_json_error( 'upb_not_allowed' );
        }

        if ( ! check_ajax_referer( '_upb', '_nonce', FALSE ) ) {
            status_header( 400 );
            wp_send_json_error( 'bad_nonce' );
        }

        if ( ! is_array( $_POST[ 'states' ] ) ) {
            status_header( 400 );
            wp_send_json_error( 'missing_contents' );
        }

        // SAVE ON PAGE META :D

        $post_id = absint( $_POST[ 'id' ] );

        if ( ! empty( $_POST[ 'shortcode' ] ) ) {
            update_post_meta( $post_id, '_upb_sections', $_POST[ 'states' ][ 'sections' ] );
            update_post_meta( $post_id, '_upb_shortcodes', trim( $_POST[ 'shortcode' ] ) );
        } else {
            delete_post_meta( $post_id, '_upb_sections' );
            delete_post_meta( $post_id, '_upb_shortcodes' );
        }

        upb_settings()->set_settings( $_POST[ 'states' ][ 'settings' ] );

        wp_send_json_success( TRUE );

    } );


    // Section Template Save
    add_action( 'wp_ajax__save_section', function () {

        // Should have manage_options cap :)
        if ( ! current_user_can( 'manage_options' ) ) {
            status_header( 403 );
            wp_send_json_error( 'upb_not_allowed' );
        }

        if ( ! check_ajax_referer( '_upb', '_nonce', FALSE ) ) {
            status_header( 400 );
            wp_send_json_error( 'bad_nonce' );
        }

        if ( empty( $_POST[ 'contents' ] ) || ! is_array( $_POST[ 'contents' ] ) ) {
            status_header( 400 );
            wp_send_json_error( 'missing_contents' );
        }

        $sections   = (array) get_option( '_upb_saved_sections', array() );
        $sections[] = wp_kses_post_deep( stripslashes_deep( $_POST[ 'contents' ] ) );


        $update = update_option( '_upb_saved_sections', $sections, FALSE );

        wp_send_json_success( $update );
    } );


    // Modify Saved Template
    add_action( 'wp_ajax__save_section_all', function () {

        if ( ! current_user_can( 'manage_options' ) ) {
            status_header( 403 );
            wp_send_json_error( 'upb_not_allowed' );
        }

        if ( ! check_ajax_referer( '_upb', '_nonce', FALSE ) ) {
            status_header( 400 );
            wp_send_json_error( 'bad_nonce' );
        }

        if ( empty( $_POST[ 'contents' ] ) ) {
            $update = update_option( '_upb_saved_sections', array(), FALSE );
        } else {
            $sections = (array) $_POST[ 'contents' ];
            $update   = update_option( '_upb_saved_sections', $sections, FALSE );
        }

        wp_send_json_success( $update );
    } );

    // Section Panel Contents
    add_action( 'wp_ajax__get_upb_sections_panel_contents', function () {

        if ( ! current_user_can( 'customize' ) ) {
            status_header( 403 );
            wp_send_json_error( 'upb_not_allowed' );
        }

        if ( ! check_ajax_referer( '_upb', '_nonce', FALSE ) ) {
            status_header( 400 );
            wp_send_json_error( 'bad_nonce' );
        }

        $post_id = absint( $_POST[ 'id' ] );

        $sections = get_post_meta( $post_id, '_upb_sections', TRUE );

        wp_send_json_success( upb_elements()->set_upb_options_recursive( $sections ) );

    } );

    // Settings Panel Contents
    add_action( 'wp_ajax__get_upb_settings_panel_contents', function () {

        if ( ! current_user_can( 'customize' ) ) {
            status_header( 403 );
            wp_send_json_error( 'upb_not_allowed' );
        }

        if ( ! check_ajax_referer( '_upb', '_nonce', FALSE ) ) {
            status_header( 400 );
            wp_send_json_error( 'bad_nonce' );
        }

        // return get_post_meta( get_the_ID(), '_upb_settings', TRUE );

        wp_send_json_success( upb_settings()->getAll() );
    } );


    add_action( 'wp_ajax__get_upb_elements_panel_contents', function () {

        if ( ! current_user_can( 'customize' ) ) {
            status_header( 403 );
            wp_send_json_error( 'upb_not_allowed' );
        }

        if ( ! check_ajax_referer( '_upb', '_nonce', FALSE ) ) {
            status_header( 400 );
            wp_send_json_error( 'bad_nonce' );
        }

        //wp_send_json_success( upb_elements()->getNonCore() );
        wp_send_json_success( upb_elements()->getAll() );
    } );


    // Get Saved Section
    add_action( 'wp_ajax__get_saved_sections', function () {

        if ( ! current_user_can( 'customize' ) ) {
            status_header( 403 );
            wp_send_json_error( 'upb_not_allowed' );
        }

        if ( ! check_ajax_referer( '_upb', '_nonce', FALSE ) ) {
            status_header( 400 );
            wp_send_json_error( 'bad_nonce' );
        }

        $saved_sections = (array) get_option( '_upb_saved_sections', array() );

        $saved_sections = upb_elements()->set_upb_options_recursive( wp_kses_post_deep( stripslashes_deep( $saved_sections ) ) );

        wp_send_json_success( $saved_sections );
    } );
