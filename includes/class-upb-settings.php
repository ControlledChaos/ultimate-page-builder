<?php

    defined( 'ABSPATH' ) or die( 'Keep Silent' );

    if ( ! class_exists( 'UPB_Settings' ) ):

        class UPB_Settings {

            private static $instance = NULL;

            private $settings = array();

            private $prefix = '_upb_settings_page_';

            private function __construct() {
                //$this->props = new UPB_Elements_Props();
            }

            public static function getInstance() {

                if ( is_null( self::$instance ) ) {
                    self::$instance = new self();
                }

                return self::$instance;
            }

            private function get_the_ID() {
                return absint( $_POST[ 'id' ] );
            }

            public function getAll() {

                $saved_settings = array();

                foreach ( $this->settings as $i => $options ) {

                    $options = $this->setAttrBasedOnType( $options[ 'id' ], $options );

                    $saved_settings[ $i ][ '_upb_field_type' ]  = 'upb-input-' . $options[ 'type' ];
                    $saved_settings[ $i ][ '_upb_field_attrs' ] = $options;

                    $saved_settings[ $i ][ 'metaId' ]    = $options[ 'id' ];
                    $saved_settings[ $i ][ 'metaKey' ]   = $options[ '_id' ];
                    $saved_settings[ $i ][ 'metaValue' ] = $options[ 'value' ];
                }

                return $saved_settings;
            }

            public function getID() {
                return get_the_ID();
            }

            public function getJSON() {
                return wp_json_encode( $this->settings );
            }

            public function register( $id, $options ) {


                // _upb_settings_page_ enabled
                // _upb_settings_page_ position

                // type: text | textarea | toggle | radio | select, desc


                $_id = $this->prefix . $id;

                $options[ 'id' ]          = $id;
                $options[ '_id' ]         = $_id;
                $options[ 'desc' ]        = isset( $options[ 'desc' ] ) ? $options[ 'desc' ] : FALSE;
                $options[ 'default' ]     = isset( $options[ 'default' ] ) ? $options[ 'default' ] : '';
                $options[ 'value' ]       = $options[ 'default' ];
                $options[ 'placeholder' ] = isset( $options[ 'placeholder' ] ) ? $options[ 'placeholder' ] : '';
                $options[ 'use' ]         = isset( $options[ 'use' ] ) ? $options[ 'use' ] : FALSE;


                switch ( $options[ 'type' ] ) {
                    case 'select':
                    case 'select2':
                        if ( isset( $options[ 'multiple' ] ) && $options[ 'multiple' ] && ! is_array( $options[ 'default' ] ) ) {
                            $options[ 'default' ] = array();
                        }
                        break;

                    case 'checkbox':

                        if ( ! is_array( $options[ 'default' ] ) ) {
                            $options[ 'default' ] = array();
                        }

                        break;

                    case 'image':
                        $options[ 'size' ] = isset( $options[ 'size' ] ) ? $options[ 'size' ] : 'full';
                        break;
                }


                // if no option saved show default else show saved one
                //$options = $this->setAttrBasedOnType( $id, $options );


                $this->settings[] = $options;
            }

            private function setAttrBasedOnType( $id, $options ) {

                $value = $this->get_setting( $id );

                switch ( $options[ 'type' ] ):

                    case 'color':
                        $options[ 'alpha' ] = isset( $options[ 'alpha' ] ) ? $options[ 'alpha' ] : FALSE;
                        $options[ 'value' ] = empty( $value ) ? $options[ 'default' ] : $value;
                        break;

                    case 'select':
                    case 'select2':
                    case 'checkbox':
                        $options[ 'value' ] = empty( $value ) ? $options[ 'default' ] : $value;
                        break;

                    case 'toggle':
                        $options[ 'value' ] = ( $value === '' ) ? $options[ 'default' ] : $value;
                        $options[ 'value' ] = filter_var( $options[ 'value' ], FILTER_VALIDATE_BOOLEAN );
                        break;

                    case 'editor':
                        $options[ 'value' ] = ( $value === '' ) ? wp_kses_post( wpautop( $options[ 'default' ] ) ) : wp_kses_post( $value );
                        break;

                    case 'textarea':
                        $options[ 'value' ] = ( $value === '' ) ? $options[ 'default' ] : apply_filters( 'upb_settings_textarea_options', wp_kses( $value, array() ), $value );
                        break;

                    default:
                        $options[ 'value' ] = ( $value === '' ) ? $options[ 'default' ] : $value;
                        break;
                endswitch;

                return apply_filters( 'upb_settings_options', $options, $value );

            }

            public function get_setting( $id ) {
                $_id = $this->prefix . $id;

                return get_post_meta( $this->get_the_ID(), $_id, TRUE );
            }

            public function set_setting( $id, $value ) {
                $_id = $this->prefix . $id;

                return update_post_meta( $this->get_the_ID(), $_id, $value );
            }

            public function set_settings( $settings ) {
                foreach ( $settings as $setting ) {
                    $this->set_setting( $setting[ 'metaId' ], $setting[ 'metaValue' ] );
                }
            }
        }
    endif;