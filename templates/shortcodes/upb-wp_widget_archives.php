<?php defined( 'ABSPATH' ) or die( 'Keep Silent' );

    // $attributes, $contents, $settings, $tag

    if ( ! upb_is_shortcode_enabled( $attributes ) ) {
        return;
    }
?>

<div id="<?php upb_shortcode_id( $attributes ) ?>" class="<?php upb_shortcode_class( $attributes, 'upb-wp_widget_archives' ) ?>">
    <style scoped>
        :scope {
        <?php upb_shortcode_scoped_style_background($attributes) ?>
            }
    </style>

    <div>
        <?php
            // Check /wp-includes/widgets/class-wp-widget-archives.php line#135

            $instance = wp_parse_args( array(
                                           'title'    => sanitize_text_field( upb_get_shortcode_title( $attributes ) ),
                                           'dropdown' => $attributes[ 'dropdown' ] ? 1 : 0,
                                           'count'    => $attributes[ 'count' ] ? 1 : 0
                                       ), array(
                                           'title'    => '',
                                           'count'    => 0,
                                           'dropdown' => ''
                                       ) );


            $args = apply_filters( 'upb-element-wp-widget-args', array(
                'before_widget' => '<div class="widget %s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widgettitle widget-title">',
                'after_title'   => '</h2>'
            ), 'WP_Widget_Archives', $attributes );

            the_widget( 'WP_Widget_Archives', $instance, $args );
        ?>
    </div>
</div>