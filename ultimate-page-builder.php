<?php
    /**
     * Plugin Name: Ultimate Page Builder
     * Plugin URI: http://wordpress.org/plugins/ultimate-page-builder/
     * Description: Ultimate Page builder
     * Author: Emran Ahmed
     * Version: 1.0.0
     * Domain Path: /languages
     * Text Domain: ultimate-page-builder
     * Author URI: http://themehippo.com/
     */

    defined( 'ABSPATH' ) or die( 'Keep Silent' );

    if ( ! class_exists( 'Ultimate_Page_Builder' ) ):

        final class Ultimate_Page_Builder {

            protected static $_instance = NULL;

            private $_enabled = FALSE;

            public static function init() {
                if ( is_null( self::$_instance ) ) {
                    self::$_instance = new self();
                }

                return self::$_instance;
            }

            public function __construct() {

                $this->constants();
                $this->includes();
                $this->hooks();

                do_action( 'ultimate_page_builder_loaded', $this );
            }

            public function constants() {
                define( 'UPB_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
                define( 'UPB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

                define( 'UPB_PLUGIN_ASSETS_URI', trailingslashit( plugin_dir_url( __FILE__ ) . 'assets' ) );
                define( 'UPB_PLUGIN_VENDOR_URI', trailingslashit( plugin_dir_url( __FILE__ ) . 'vendor' ) );

                define( 'UPB_PLUGIN_INCLUDE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) . 'includes' ) );
                define( 'UPB_PLUGIN_TEMPLATES_PATH', trailingslashit( plugin_dir_path( __FILE__ ) . 'templates' ) );

                define( 'UPB_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
                define( 'UPB_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
                define( 'UPB_PLUGIN_FILE', __FILE__ );
            }

            public function includes() {

                // Common
                require_once UPB_PLUGIN_INCLUDE_PATH . "upb-functions.php";
                require_once UPB_PLUGIN_INCLUDE_PATH . "upb-template-functions.php";
                require_once UPB_PLUGIN_INCLUDE_PATH . "upb-shortcode-functions.php";
                require_once UPB_PLUGIN_INCLUDE_PATH . "upb-shortcode-preview-functions.php";

                require_once UPB_PLUGIN_INCLUDE_PATH . "upb-elements.php";
                require_once UPB_PLUGIN_INCLUDE_PATH . "upb-settings.php";

                // Tabs
                require_once UPB_PLUGIN_INCLUDE_PATH . "class-upb-tabs.php";

                // Settings
                require_once UPB_PLUGIN_INCLUDE_PATH . "class-upb-settings.php";

                // Elements
                require_once UPB_PLUGIN_INCLUDE_PATH . "class-upb-elements.php";
                require_once UPB_PLUGIN_INCLUDE_PATH . "class-upb-elements-props.php";

                // Boilerplate
                require_once UPB_PLUGIN_INCLUDE_PATH . "class-upb-boilerplate.php";


                // Preview
                require_once UPB_PLUGIN_INCLUDE_PATH . "class-upb-preview.php";

                // Load
                require_once UPB_PLUGIN_INCLUDE_PATH . "upb-hooks.php";
                require_once UPB_PLUGIN_INCLUDE_PATH . "upb-ajax-functions.php";

            }

            public function hooks() {
                add_action( 'init', array( $this, 'language' ) );
                add_action( 'wp', array( $this, 'upb_enabled' ) );
            }

            public function upb_enabled() {

                $enable = filter_var( get_post_meta( get_the_ID(), '_upb_settings_page_enable', TRUE ), FILTER_VALIDATE_BOOLEAN );

                if ( ! upb_is_preview() && $enable ) {
                    $this->_enabled = TRUE;
                }
            }

            public function is_enabled() {
                return $this->_enabled;
            }

            public function language() {
                load_plugin_textdomain( 'ultimate-page-builder', FALSE, trailingslashit( UPB_PLUGIN_DIRNAME ) . 'languages' );
            }

            public function template_path() {
                return apply_filters( 'upb_template_path', untrailingslashit( $this->plugin_path() ) . '/templates' );
            }

            public function template_uri() {
                return apply_filters( 'upb_template_uri', untrailingslashit( $this->plugin_uri() ) . '/templates' );
            }

            public function plugin_path() {
                return untrailingslashit( plugin_dir_path( __FILE__ ) );
            }

            public function plugin_basename() {
                return plugin_basename( __FILE__ );
            }

            public function template_dir() {
                return apply_filters( 'upb_template_dir', 'upb-templates' );
            }

            public function plugin_uri() {
                return untrailingslashit( plugins_url( '/', __FILE__ ) );
            }
        }

        function Ultimate_Page_Builder() {
            return Ultimate_Page_Builder::init();
        }

        // Global for backwards compatibility.
        $GLOBALS[ 'ultimate_page_builder' ] = Ultimate_Page_Builder();

    endif;
