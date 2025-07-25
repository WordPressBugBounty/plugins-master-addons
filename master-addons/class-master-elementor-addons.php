<?php

namespace MasterAddons;

use MasterAddons\Admin\Dashboard\Master_Addons_Admin_Settings;
use MasterAddons\Admin\Dashboard\Addons\Extensions\JLTMA_Addon_Extensions;
use MasterAddons\Admin\Dashboard\Addons\Elements\JLTMA_Addon_Elements;
use MasterAddons\Inc\Helper\Master_Addons_Helper;
use MasterAddons\Inc\Classes\Feedback;
use MasterAddons\Inc\Classes\Pro_Upgrade;
use MasterAddons\Inc\Classes\Recommended_Plugins;
use MasterAddons\Inc\Classes\Master_Addons_White_Label;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// No, Direct access Sir !!!
if ( !class_exists( 'Master_Elementor_Addons' ) ) {
    final class Master_Elementor_Addons {
        public static $class_namespace = '\\MasterAddons\\Inc\\Classes\\';

        public $controls_manager;

        const VERSION = JLTMA_VER;

        const MINIMUM_PHP_VERSION = '7.0';

        const MINIMUM_ELEMENTOR_VERSION = '3.5.0';

        private $_localize_settings = [];

        private $reflection;

        private static $plugin_path;

        private static $plugin_url;

        private static $plugin_slug;

        public static $plugin_dir_url;

        private static $instance = null;

        private $jltma_classes = array();

        public static function get_instance() {
            if ( !self::$instance ) {
                self::$instance = new self();
                self::$instance->jltma_init();
            }
            return self::$instance;
        }

        public function __construct() {
            $this->reflection = new \ReflectionClass($this);
            $this->constants();
            $this->jltma_register_autoloader();
            $this->jltma_include_files();
            self::$plugin_slug = 'master-addons';
            self::$plugin_path = untrailingslashit( plugin_dir_path( '/', __FILE__ ) );
            self::$plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
            // Load textdomain for translations
            add_action( 'init', [$this, 'load_textdomain'] );
            // Initialize Plugin
            add_action( 'plugins_loaded', [$this, 'jltma_plugins_loaded'] );
            // $this->jltma_is_plugin_row_meta_and_actions_link();
            // add_filter('plugin_action_links', [$this, 'jltma_is_plugin_row_meta_and_actions_link'], 10, 2);
            //Hook: elementor/elements/categories_registere
            // add_action('elementor/init', [$this, 'jltma_add_category_to_editor']);
            add_action( 'elementor/init', [$this, 'jltma_add_actions_to_elementor'], 0 );
            // Add Elementor Widgets
            add_action( 'elementor/widgets/register', [$this, 'jltma_init_widgets'] );
            add_action( 'elementor/elements/categories_registered', [$this, 'jltma_add_category_to_editor'] );
            //Register Controls
            add_action( 'elementor/controls/register', [$this, 'jltma_register_controls'] );
            //Body Class
            add_action( 'body_class', [$this, 'jltma_body_class'] );
            // Override Freemius Filters
            ma_el_fs()->add_filter( 'support_forum_submenu', [$this, 'jltma_override_support_menu_text'] );
            ma_el_fs()->add_filter( 'support_forum_url', [$this, 'jltma_support_forum_url'] );
            ma_el_fs()->add_filter( 'plugin_icon', [$this, 'jltma_freemius_logo_icon'] );
            // Disable deactivation feedback form
            ma_el_fs()->add_filter( 'show_deactivation_feedback_form', '__return_false' );
        }

        public function jltma_freemius_logo_icon() {
            return $this->jltma_plugin_path() . '/assets/images/master-addons.png';
        }

        public function jltma_is_plugin_row_meta_and_actions_link() {
            add_filter(
                'plugin_row_meta',
                [$this, 'jltma_plugin_row_meta'],
                10,
                2
            );
        }

        public function jltma_init() {
            $this->jltma_image_size();
            //Redirect Hook
            add_action( 'admin_init', [$this, 'jltma_add_redirect_hook'] );
        }

        public function jltma_override_support_menu_text() {
            return __( 'Support', 'master-addons' );
        }

        /**
         * Support Forum URL
         *
         * @param [type] $support_url and Pro Support
         *
         * @return void
         */
        public function jltma_support_forum_url( $support_url ) {
            // if (ma_el_fs()->is_premium()) {
            // 	$support_url = 'https://master-addons.com/support/';
            // } else {
            $support_url = 'https://wordpress.org/support/plugin/master-addons/#new-topic-0';
            // }
            return $support_url;
        }

        public static function jltma_elementor() {
            return \Elementor\Plugin::$instance;
        }

        // Deactivation Hook
        public static function jltma_plugin_deactivation_hook() {
            delete_option( 'jltma_activation_time' );
        }

        // Activation Hook
        public static function jltma_plugin_activation_hook() {
            self::activated_widgets();
            self::activated_extensions();
            self::activated_third_party_plugins();
            self::activated_icons_library();
            $jltma_white_label_setting = jltma_get_options( 'jltma_white_label_settings' ) ?? [];
            if ( !empty( $jltma_white_label_setting ) && isset( $jltma_white_label_setting['jltma_wl_plugin_tab_white_label'] ) ) {
                $jltma_white_label_setting['jltma_wl_plugin_tab_white_label'] = 0;
                update_option( 'jltma_white_label_settings', $jltma_white_label_setting );
            }
            ma_el_fs()->add_action( 'after_premium_version_activation', array('\\MasterAddons\\Master_Elementor_Addons', 'jltma_network_activate') );
        }

        // Multisite Activation
        public static function jltma_network_activate( $network_wide ) {
            if ( function_exists( 'is_multisite' ) && is_multisite() ) {
                //do nothing for multisite!
            } else {
                //Make sure we redirect to the welcome page
                set_transient( JLTMA_ACTIVATION_REDIRECT_TRANSIENT_KEY, true, 30 );
            }
        }

        public function set_plugin_activation_time() {
            if ( is_multisite() ) {
                if ( get_site_option( 'jltma_activation_time' ) === false ) {
                    if ( !function_exists( 'is_plugin_active_for_network' ) ) {
                        require_once ABSPATH . '/wp-admin/includes/plugin.php';
                    }
                    if ( is_plugin_active_for_network( 'master-addons-pro/master-addons.php' ) || is_plugin_active_for_network( 'master-addons/master-addons.php' ) ) {
                        update_site_option( 'jltma_activation_time', strtotime( "now" ) );
                    }
                }
            } else {
                if ( get_option( 'jltma_activation_time' ) === false ) {
                    update_option( 'jltma_activation_time', strtotime( "now" ) );
                }
            }
        }

        // Initialize
        public function jltma_plugins_loaded() {
            $this->jltma_load_extensions();
            $this->set_plugin_activation_time();
            // Check if Elementor installed and activated
            if ( !did_action( 'elementor/loaded' ) ) {
                add_action( 'admin_notices', array($this, 'jltma_admin_notice_missing_main_plugin') );
                return;
            }
            // Check for required Elementor version
            if ( defined( 'ELEMENTOR_VERSION' ) && !version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
                add_action( 'admin_notices', array($this, 'jltma_admin_notice_minimum_elementor_version') );
                return;
            }
            // Check for required PHP version
            if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
                add_action( 'admin_notices', array($this, 'jltma_admin_notice_minimum_php_version') );
                return;
            }
            // self::jltma_plugin_activation_hook();
        }

        public function constants() {
            //Defined Constants
            if ( !defined( 'JLTMA_BADGE' ) ) {
                define( 'JLTMA_BADGE', '<span class="jltma-badge"></span>' );
            }
            if ( !defined( 'JLTMA_NEW_FEATURE' ) ) {
                define( 'JLTMA_NEW_FEATURE', '<span class="jltma-new-feature"></span>' );
            }
            if ( !defined( 'JLTMA_SCRIPT_SUFFIX' ) ) {
                define( 'JLTMA_SCRIPT_SUFFIX', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) );
            }
            if ( !defined( 'JLTMA_URL' ) ) {
                define( 'JLTMA_URL', self::jltma_plugin_url() );
            }
            if ( !defined( 'JLTMA_PATH' ) ) {
                define( 'JLTMA_PATH', self::jltma_plugin_path() );
            }
            if ( !defined( 'JLTMA_DIR_URL' ) ) {
                define( 'JLTMA_DIR_URL', self::jltma_plugin_dir_url() );
            }
            if ( !defined( 'JLTMA_IMAGE_DIR' ) ) {
                define( 'JLTMA_IMAGE_DIR', self::jltma_plugin_dir_url() . '/assets/images/' );
            }
            if ( !defined( 'JLTMA_PRO_IMAGE_DIR' ) ) {
                define( 'JLTMA_PRO_IMAGE_DIR', self::jltma_plugin_dir_url() . '/premium/assets/images/' );
            }
            if ( !defined( 'JLTMA_ASSETS' ) ) {
                define( 'JLTMA_ASSETS', self::jltma_plugin_url() . '/assets/' );
            }
            if ( !defined( 'JLTMA_ADMIN_ASSETS' ) ) {
                define( 'JLTMA_ADMIN_ASSETS', self::jltma_plugin_dir_url() . '/inc/admin/assets/' );
            }
            if ( !defined( 'JLTMA_ADDONS' ) ) {
                define( 'JLTMA_ADDONS', plugin_dir_path( __FILE__ ) . 'addons/' );
            }
            if ( !defined( 'JLTMA_PRO_ADDONS' ) ) {
                define( 'JLTMA_PRO_ADDONS', plugin_dir_path( __FILE__ ) . 'premium/addons/' );
            }
            if ( !defined( 'JLTMA_PRO_EXTENSIONS' ) ) {
                define( 'JLTMA_PRO_EXTENSIONS', plugin_dir_path( __FILE__ ) . 'premium/modules/' );
            }
            if ( !defined( 'JLTMA_TEMPLATES' ) ) {
                define( 'JLTMA_TEMPLATES', plugin_dir_path( __FILE__ ) . 'inc/template-parts/' );
            }
            define( 'JLTMA_ACTIVATION_REDIRECT_TRANSIENT_KEY', '_master_addons_activation_redirect' );
        }

        public function jltma_register_autoloader() {
            spl_autoload_register( [__CLASS__, 'jltma_autoload'] );
        }

        function jltma_autoload( $class ) {
            if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
                return;
            }
            if ( !class_exists( $class ) ) {
                $filename = strtolower( preg_replace( [
                    '/^' . __NAMESPACE__ . '\\\\/',
                    '/([a-z])([A-Z])/',
                    '/_/',
                    '/\\\\/'
                ], [
                    '',
                    '$1-$2',
                    '-',
                    DIRECTORY_SEPARATOR
                ], $class ) );
                $filename = JLTMA_PATH . $filename . '.php';
                if ( is_readable( $filename ) ) {
                    include $filename;
                }
            }
        }

        function jltma_add_category_to_editor( $widgets_manager ) {
            $widgets_manager->add_category( 'master-addons', [
                'title' => esc_html__( 'Master Addons', 'master-addons' ),
                'icon'  => 'font',
            ], 1 );
        }

        public function jltma_image_size() {
            add_image_size(
                'master_addons_team_thumb',
                250,
                330,
                true
            );
        }

        // Widget Elements
        public static function activated_widgets() {
            $jltma_default_element_settings = array_fill_keys( Master_Addons_Admin_Settings::jltma_addons_array(), true );
            $jltma_get_element_settings = get_option( 'maad_el_save_settings', $jltma_default_element_settings );
            $jltma_new_element_settings = array_diff_key( $jltma_default_element_settings, $jltma_get_element_settings );
            $jltma_updated_element_settings = array_merge( $jltma_get_element_settings, $jltma_new_element_settings );
            if ( $jltma_get_element_settings === false ) {
                $jltma_get_element_settings = array();
            }
            update_option( 'maad_el_save_settings', $jltma_updated_element_settings );
            return $jltma_get_element_settings;
        }

        // Extensions
        public static function activated_extensions() {
            $jltma_default_extensions_settings = array_fill_keys( Master_Addons_Admin_Settings::jltma_addons_extensions_array(), true );
            $jltma_get_extension_settings = get_option( 'ma_el_extensions_save_settings', $jltma_default_extensions_settings );
            $jltma_new_extension_settings = array_diff_key( $jltma_default_extensions_settings, $jltma_get_extension_settings );
            $jltma_updated_extension_settings = array_merge( $jltma_get_extension_settings, $jltma_new_extension_settings );
            if ( $jltma_get_extension_settings === false ) {
                $jltma_get_extension_settings = array();
            }
            update_option( 'ma_el_extensions_save_settings', $jltma_updated_extension_settings );
            return $jltma_get_extension_settings;
        }

        // Third Party Plugins
        public static function activated_third_party_plugins() {
            $jltma_third_party_plugins_settings = array_fill_keys( Master_Addons_Admin_Settings::jltma_addons_third_party_plugins_array(), true );
            $jltma_get_third_party_plugins_settings = get_option( 'ma_el_third_party_plugins_save_settings', $jltma_third_party_plugins_settings );
            $jltma_new_third_party_plugins_settings = array_diff_key( $jltma_third_party_plugins_settings, $jltma_get_third_party_plugins_settings );
            $maad_el_updated_extension_settings = array_merge( $jltma_get_third_party_plugins_settings, $jltma_new_third_party_plugins_settings );
            if ( $jltma_get_third_party_plugins_settings === false ) {
                $jltma_get_third_party_plugins_settings = array();
            }
            update_option( 'ma_el_third_party_plugins_save_settings', $maad_el_updated_extension_settings );
            return $jltma_get_third_party_plugins_settings;
        }

        // Icons Library
        public static function activated_icons_library() {
            $jltma_icons_library_settings = array_fill_keys( Master_Addons_Admin_Settings::jltma_addons_icons_library_array(), true );
            $jltma_get_icons_library_settings = get_option( 'jltma_icons_library_save_settings', $jltma_icons_library_settings );
            $jltma_new_icons_library_settings = array_diff_key( $jltma_icons_library_settings, $jltma_get_icons_library_settings );
            $maad_el_updated_extension_settings = array_merge( $jltma_get_icons_library_settings, $jltma_new_icons_library_settings );
            if ( $jltma_get_icons_library_settings === false ) {
                $jltma_get_icons_library_settings = array();
            }
            update_option( 'jltma_icons_library_save_settings', $maad_el_updated_extension_settings );
            return $jltma_get_icons_library_settings;
        }

        // public function jltma_add_actions_to_elementor()
        // {
        // 	$classes = glob(JLTMA_PATH . '/inc/classes/JLTMA_*.php');
        // 	// include all classes
        // 	foreach ($classes as $key => $value) {
        // 		require_once $value;
        // 	}
        // 	// instance all classes
        // 	foreach ($classes as $key => $value) {
        // 		$name = pathinfo($value, PATHINFO_FILENAME);
        // 		$class = self::$class_namespace . $name;
        // 		$this->jltma_classes[strtolower($name)] = new $class();
        // 	}
        // }
        /**
         * Load all extension classes and instance them.
         *
         * This method will:
         * 1. Get all PHP files inside the inc/classes directory.
         * 2. Include all of them.
         * 3. Create an instance of each class.
         * 4. Store the instance in the $jltma_classes property.
         *
         * @since 1.1.0
         * @return void
         */
        public function jltma_add_actions_to_elementor() {
            $classes = glob( JLTMA_PATH . '/inc/classes/JLTMA_*.php' );
            // include all classes
            foreach ( $classes as $key => $value ) {
                require_once $value;
            }
            // instance all classes
            foreach ( $classes as $key => $value ) {
                $name = pathinfo( $value, PATHINFO_FILENAME );
                $class = self::$class_namespace . $name;
                // Now this will no longer trigger a deprecated warning
                $this->jltma_classes[strtolower( $name )] = new $class();
            }
        }

        public function jltma_register_controls( $controls_manager ) {
            $controls_manager = \Elementor\Plugin::$instance->controls_manager;
            $controls = array(
                'jltma-visual-select'     => array(
                    'file'  => JLTMA_PATH . '/inc/controls/visual-select.php',
                    'class' => 'MasterAddons\\Inc\\Controls\\MA_Control_Visual_Select',
                    'type'  => 'single',
                ),
                'jltma-transitions'       => array(
                    'file'  => JLTMA_PATH . '/inc/controls/group/transitions.php',
                    'class' => 'MasterAddons\\Inc\\Controls\\MA_Group_Control_Transition',
                    'type'  => 'group',
                ),
                'jltma-filters-hsb'       => array(
                    'file'  => JLTMA_PATH . '/inc/controls/group/filters-hsb.php',
                    'class' => 'MasterAddons\\Inc\\Controls\\MA_Group_Control_Filters_HSB',
                    'type'  => 'group',
                ),
                'jltma-button-background' => array(
                    'file'  => JLTMA_PATH . '/inc/controls/group/button-background.php',
                    'class' => 'MasterAddons\\Inc\\Controls\\MA_Group_Control_Button_Background',
                    'type'  => 'group',
                ),
                'jltma-choose-text'       => array(
                    'file'  => JLTMA_PATH . '/inc/controls/choose-text.php',
                    'class' => 'MasterAddons\\Inc\\Controls\\JLTMA_Control_Choose_Text',
                    'type'  => 'single',
                ),
                'jltma-file-select'       => array(
                    'file'  => JLTMA_PATH . '/inc/controls/file-select.php',
                    'class' => 'MasterAddons\\Inc\\Controls\\JLTMA_Control_File_Select',
                    'type'  => 'single',
                ),
                'jltma_query'             => array(
                    'file'  => JLTMA_PATH . '/inc/controls/jltma-query.php',
                    'class' => 'MasterAddons\\Inc\\Controls\\JLTMA_Control_Query',
                    'type'  => 'single',
                ),
            );
            foreach ( $controls as $control_type => $control_info ) {
                if ( !empty( $control_info['file'] ) && !empty( $control_info['class'] ) ) {
                    include_once $control_info['file'];
                    if ( class_exists( $control_info['class'] ) ) {
                        $class_name = $control_info['class'];
                    } elseif ( class_exists( __NAMESPACE__ . '\\' . $control_info['class'] ) ) {
                        $class_name = __NAMESPACE__ . '\\' . $control_info['class'];
                    }
                    if ( $control_info['type'] === 'group' ) {
                        $controls_manager->add_group_control( $control_type, new $class_name() );
                    } else {
                        $controls_manager->register( new $class_name() );
                    }
                }
            }
        }

        public function get_widgets() {
            return [];
        }

        public function jltma_init_widgets() {
            $activated_widgets = self::activated_widgets();
            // Network Check
            if ( defined( 'JLTMA_NETWORK_ACTIVATED' ) && JLTMA_NETWORK_ACTIVATED ) {
                global $wpdb;
                $blogs = $wpdb->get_results( "\n\t\t\t\t    SELECT blog_id\n\t\t\t\t    FROM {$wpdb->blogs}\n\t\t\t\t    WHERE site_id = '{$wpdb->siteid}'\n\t\t\t\t    AND spam = '0'\n\t\t\t\t    AND deleted = '0'\n\t\t\t\t    AND archived = '0'\n\t\t\t\t" );
                $original_blog_id = get_current_blog_id();
                foreach ( $blogs as $blog_id ) {
                    switch_to_blog( $blog_id->blog_id );
                    $widget_manager = Master_Addons_Helper::jltma_elementor()->widgets_manager;
                    $jltma_all_addons = Master_Addons_Admin_Settings::jltma_merged_addons_array();
                    ksort( $jltma_all_addons );
                    foreach ( $jltma_all_addons as $key => $widget ) {
                        if ( isset( $activated_widgets[$widget['key']] ) && $activated_widgets[$widget['key']] == true ) {
                            $widget_file = JLTMA_ADDONS . $widget['key'] . '/' . $widget['key'] . '.php';
                            if ( !ma_el_fs()->can_use_premium_code__premium_only() && (isset( $widget['is_pro'] ) && $widget['is_pro']) ) {
                                continue;
                            }
                            if ( file_exists( $widget_file ) ) {
                                require_once $widget_file;
                            }
                            $class_name = $widget['class'];
                            $widget_manager->register( new $class_name() );
                        }
                    }
                }
                switch_to_blog( $original_blog_id );
            } else {
                $widget_manager = Master_Addons_Helper::jltma_elementor()->widgets_manager;
                $jltma_all_addons = Master_Addons_Admin_Settings::jltma_merged_addons_array();
                ksort( $jltma_all_addons );
                foreach ( $jltma_all_addons as $key => $widget ) {
                    if ( isset( $activated_widgets[$widget['key']] ) && $activated_widgets[$widget['key']] == true ) {
                        $widget_file = JLTMA_ADDONS . $widget['key'] . '/' . $widget['key'] . '.php';
                        if ( !ma_el_fs()->can_use_premium_code__premium_only() && (isset( $widget['is_pro'] ) && $widget['is_pro']) ) {
                            continue;
                        }
                        if ( file_exists( $widget_file ) ) {
                            require_once $widget_file;
                        }
                        $class_name = $widget['class'];
                        $widget_manager->register( new $class_name() );
                    }
                }
            }
        }

        public function jltma_load_extensions() {
            // Extension
            $activated_extensions = self::activated_extensions();
            // Network Check
            if ( defined( 'JLTMA_NETWORK_ACTIVATED' ) && JLTMA_NETWORK_ACTIVATED ) {
                global $wpdb;
                $blogs = $wpdb->get_results( "\n\t\t\t\t    SELECT blog_id\n\t\t\t\t    FROM {$wpdb->blogs}\n\t\t\t\t    WHERE site_id = '{$wpdb->siteid}'\n\t\t\t\t    AND spam = '0'\n\t\t\t\t    AND deleted = '0'\n\t\t\t\t    AND archived = '0'\n\t\t\t\t" );
                $original_blog_id = get_current_blog_id();
                foreach ( $blogs as $blog_id ) {
                    switch_to_blog( $blog_id->blog_id );
                    ksort( JLTMA_Addon_Extensions::$jltma_extensions['jltma-extensions']['extension'] );
                    foreach ( JLTMA_Addon_Extensions::$jltma_extensions['jltma-extensions']['extension'] as $extensions ) {
                        if ( isset( $activated_extensions[$extensions['key']] ) && $activated_extensions[$extensions['key']] == true ) {
                            $extensions_file = JLTMA_PATH . '/inc/modules/' . $extensions['key'] . '/' . $extensions['key'] . '.php';
                            if ( !ma_el_fs()->can_use_premium_code__premium_only() && (isset( $extensions['is_pro'] ) && $extensions['is_pro']) ) {
                                continue;
                            }
                            if ( file_exists( $extensions_file ) ) {
                                require_once $extensions_file;
                            }
                        }
                    }
                }
                switch_to_blog( $original_blog_id );
            } else {
                ksort( JLTMA_Addon_Extensions::$jltma_extensions['jltma-extensions']['extension'] );
                foreach ( JLTMA_Addon_Extensions::$jltma_extensions['jltma-extensions']['extension'] as $extensions ) {
                    if ( isset( $activated_extensions[$extensions['key']] ) && $activated_extensions[$extensions['key']] == true ) {
                        $extensions_file = JLTMA_PATH . '/inc/modules/' . $extensions['key'] . '/' . $extensions['key'] . '.php';
                        if ( !ma_el_fs()->can_use_premium_code__premium_only() && (isset( $extensions['is_pro'] ) && $extensions['is_pro']) ) {
                            continue;
                        }
                        if ( file_exists( $extensions_file ) ) {
                            require_once $extensions_file;
                        }
                    }
                }
            }
        }

        public function jltma_editor_scripts_enqueue_js() {
            wp_enqueue_script(
                'ma-el-rellaxjs-lib',
                JLTMA_URL . '/assets/vendor/rellax/rellax.min.js',
                array('jquery'),
                self::VERSION,
                true
            );
        }

        public function jltma_editor_scripts_css() {
            wp_enqueue_style( 'master-addons-editor', JLTMA_URL . '/assets/css/master-addons-editor.css' );
        }

        public function is_elementor_activated( $plugin_path = 'elementor/elementor.php' ) {
            $installed_plugins_list = get_plugins();
            return isset( $installed_plugins_list[$plugin_path] );
        }

        /*
         * Activation Plugin redirect hook
         */
        public function jltma_add_redirect_hook() {
            if ( is_plugin_active( 'elementor/elementor.php' ) ) {
                if ( get_option( 'ma_el_update_redirect', false ) ) {
                    delete_option( 'ma_el_update_redirect' );
                    delete_transient( 'ma_el_update_redirect' );
                    if ( !isset( $_GET['activate-multi'] ) && $this->is_elementor_activated() ) {
                        wp_redirect( 'admin.php?page=master-addons-settings' );
                        exit;
                    }
                }
            }
        }

        // Plugin URL
        public static function jltma_plugin_url() {
            if ( self::$plugin_url ) {
                return self::$plugin_url;
            }
            return self::$plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
        }

        // Plugin Path
        public static function jltma_plugin_path() {
            if ( self::$plugin_path ) {
                return self::$plugin_path;
            }
            return self::$plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
        }

        /**
         * @method plugin_dir_url
         * Get the URL directory path (with trailing slash) for the plugin __FILE__ passed in
         * Reference: https://developer.wordpress.org/reference/functions/plugin_dir_url/
         *
         * @return void
         */
        public static function jltma_plugin_dir_url() {
            if ( self::$plugin_dir_url ) {
                return self::$plugin_dir_url;
            }
            return self::$plugin_dir_url = untrailingslashit( plugin_dir_url( __FILE__ ) );
        }

        public function plugin_actions_links( $links ) {
            if ( is_admin() ) {
                $links[] = sprintf( '<a href="admin.php?page=master-addons-settings">' . esc_html__( 'Settings', 'master-addons' ) . '</a>' );
                // $links[] = '<a href="https://master-addons.com/support/" target="_blank">' . esc_html__('Support', 'master-addons' ) . '</a>';
                // $links[] = '<a href="https://master-addons.com/docs/" target="_blank">' . esc_html__('Documentation', 'master-addons' ) . '</a>';
            }
            // go pro
            // if (!ma_el_fs()->can_use_premium_code__premium_only()) {
            // 	$links[] = sprintf('<a href="https://master-addons.com/" target="_blank" style="color: #39b54a; font-weight: bold;">' . esc_html__('Go Pro', 'master-addons' ) . '</a>');
            // }
            return $links;
        }

        public function jltma_plugin_row_meta( $links, $file ) {
            if ( JLTMA_BASE === $file ) {
                $new_links = array(
                    'demo'    => '<a href="' . esc_url( 'https://master-addons.com' ) . '" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span>Live Demo</a>',
                    'doc'     => '<a href="' . esc_url( 'https://master-addons.com/docs/' ) . '" target="_blank"><span class="dashicons dashicons-media-document"></span>Documentation</a>',
                    'support' => '<a href="https://master-addons.com/support/" target="_blank"><span class="dashicons dashicons-admin-users"></span>Support</a>',
                    'pro'     => '<a href="' . esc_url( 'https://master-addons.com' ) . '" target="_blank"><span class="dashicons dashicons-cart"></span>Premium version</a>',
                );
                $links = array_merge( $links, $new_links );
            }
            return $links;
        }

        // Include Files
        public function jltma_include_files() {
            // Helper Class
            include_once JLTMA_PATH . '/inc/classes/helper-class.php';
            // Assets Manager
            include_once JLTMA_PATH . '/inc/classes/assets-manager.php';
            // Templates Control Class
            include_once JLTMA_PATH . '/inc/classes/template-controls.php';
            //Reset Theme Styles
            include_once JLTMA_PATH . '/inc/classes/class-reset-themes.php';
            // Dashboard Settings
            include_once JLTMA_PATH . '/inc/admin/dashboard-settings.php';
            //Utils
            include_once JLTMA_PATH . '/inc/classes/utils.php';
            //Rollback
            include_once JLTMA_PATH . '/inc/classes/rollback.php';
            //White Label
            include_once JLTMA_PATH . '/inc/classes/white-label.php';
            // Templates
            require_once JLTMA_PATH . '/inc/templates/templates.php';
            // Extensions
            require_once JLTMA_PATH . '/inc/classes/JLTMA_Extension_Prototype.php';
            // Extensions
            require_once JLTMA_PATH . '/inc/classes/Animation.php';
            // Traits: Global Controls
            require_once JLTMA_PATH . '/inc/traits/swiper-controls.php';
            include_once JLTMA_PATH . '/inc/traits/widget-notice.php';
            // Recommeded Plugins
            // require_once JLTMA_PATH . '/lib/Recommended.php';
            // require_once JLTMA_PATH . '/inc/classes/Recommended_Plugins.php';
            // Notifications
            require_once JLTMA_PATH . '/inc/classes/Notifications/Base/Date.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Base/Data.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Base/User_Data.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Model/Notification.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Model/Notice.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Model/Popup.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Latest_Updates.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Ask_For_Rating.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Subscribe.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/What_We_Collect.php';
            require_once JLTMA_PATH . '/inc/classes/Pro_Upgrade.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Upgrade_Notice.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Manager.php';
            require_once JLTMA_PATH . '/inc/classes/Notifications/Notifications.php';
            require_once JLTMA_PATH . '/inc/classes/Feedback.php';
            require_once JLTMA_PATH . '/lib/Featured.php';
            new Feedback();
        }

        public function jltma_body_class( $classes ) {
            global $pagenow;
            if ( in_array( $pagenow, ['post.php', 'post-new.php'], true ) && \Elementor\Utils::is_post_support() ) {
                $post = get_post();
                $mode_class = ( \Elementor\Plugin::$instance->db->is_built_with_elementor( $post->ID ) ? 'elementor-editor-active' : 'elementor-editor-inactive master-addons' );
                $classes .= ' ' . $mode_class;
            }
            return $classes;
        }

        public function get_localize_settings() {
            return $this->_localize_settings;
        }

        public function add_localize_settings( $setting_key, $setting_value = null ) {
            if ( is_array( $setting_key ) ) {
                $this->_localize_settings = array_replace_recursive( $this->_localize_settings, $setting_key );
                return;
            }
            if ( !is_array( $setting_value ) || !isset( $this->_localize_settings[$setting_key] ) || !is_array( $this->_localize_settings[$setting_key] ) ) {
                $this->_localize_settings[$setting_key] = $setting_value;
                return;
            }
            $this->_localize_settings[$setting_key] = array_replace_recursive( $this->_localize_settings[$setting_key], $setting_value );
        }

        public function jltma_admin_notice_missing_main_plugin() {
            $plugin = 'elementor/elementor.php';
            if ( $this->is_elementor_activated() ) {
                if ( !current_user_can( 'activate_plugins' ) ) {
                    return;
                }
                $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
                $message = __( '<b>Master Addons</b> requires <strong>Elementor</strong> plugin to be active. Please activate Elementor to continue.', 'master-addons' );
                $button_text = __( 'Activate Elementor', 'master-addons' );
            } else {
                if ( !current_user_can( 'install_plugins' ) ) {
                    return;
                }
                $activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
                $message = sprintf( __( '<b>Master Addons</b> requires %1$s"Elementor"%2$s plugin to be installed and activated. Please install Elementor to continue.', 'master-addons' ), '<strong>', '</strong>' );
                $button_text = __( 'Install Elementor', 'master-addons' );
            }
            $button = '<p><a href="' . esc_url( $activation_url ) . '" class="button-primary">' . esc_html( $button_text ) . '</a></p>';
            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message, $button );
        }

        public function jltma_admin_notice_minimum_elementor_version() {
            if ( isset( $_GET['activate'] ) ) {
                unset($_GET['activate']);
            }
            $message = sprintf(
                esc_html__( '"%1$s" requires "%2$s" version %3$s or greater. ', 'master-addons' ),
                '<strong>' . esc_html__( 'Master Addons for Elementor', 'master-addons' ) . '</strong>',
                '<strong>' . esc_html__( 'Elementor', 'master-addons' ) . '</strong>',
                self::MINIMUM_ELEMENTOR_VERSION
            );
            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
        }

        public function jltma_admin_notice_minimum_php_version() {
            if ( isset( $_GET['activate'] ) ) {
                unset($_GET['activate']);
            }
            $message = sprintf(
                esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'master-addons' ),
                '<strong>' . esc_html__( 'Master Addons for Elementor', 'master-addons' ) . '</strong>',
                '<strong>' . esc_html__( 'PHP', 'master-addons' ) . '</strong>',
                self::MINIMUM_PHP_VERSION
            );
            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
        }

        // Add this method to load the textdomain properly
        public function load_textdomain() {
            load_plugin_textdomain( 'master-addons', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }

    }

    Master_Elementor_Addons::get_instance();
}