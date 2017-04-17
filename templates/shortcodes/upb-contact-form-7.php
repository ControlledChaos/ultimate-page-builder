<?php defined( 'ABSPATH' ) or die( 'Keep Silent' );
    
    // $attributes, $contents, $settings, $tag

    if ( ! upb_is_shortcode_enabled( $attributes ) ) {
        return;
    }
?>

<div <?php upb_shortcode_attribute_id( $attributes ) ?> class="<?php upb_shortcode_class( $attributes, $tag ) ?>">
    <style scoped>
        :scope {
        <?php upb_shortcode_scoped_style_background($attributes) ?>
            }
    </style>
    <div>
        <?php
            echo do_shortcode( sprintf( '[contact-form-7 id="%d" title="%s"]', absint( $attributes[ 'id' ] ), esc_html( upb_get_shortcode_title( $attributes ) ) ) );
        ?>
    </div>
</div>