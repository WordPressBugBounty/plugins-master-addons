<?php
/**
 * Master Addons Widget Builder Initialization
 *
 * @package MasterAddons
 * @subpackage WidgetBuilder
 */

namespace MasterAddons\Inc\Admin\WidgetBuilder;

if (!defined('ABSPATH')) {
    exit;
}

class Widget_Builder_Init {

    private static $instance = null;

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', [$this, 'initialize'], 1);
        add_action('admin_init', [$this, 'admin_redirects']);
    }

    public function initialize() {
        Widget_CPT::get_instance();
        Widget_Admin::get_instance();

        // Initialize REST API
        add_action('rest_api_init', function() {
            $controller = new REST_Controller();
            $controller->register_routes();
        });

        // Initialize Shortcode Manager
        Shortcode_Manager::get_instance();

        // Register custom widgets with Elementor
        add_action('elementor/widgets/register', [$this, 'register_custom_widgets']);
    }

    /**
     * Register custom widgets from CPT with Elementor
     */
    public function register_custom_widgets($widgets_manager) {
        // Register custom categories first
        $this->register_custom_categories();

        // Get all published widgets
        $args = [
            'post_type' => 'jltma_widget',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        ];

        $widgets = get_posts($args);

        if (empty($widgets)) {
            return;
        }

        $upload = wp_upload_dir();
        $widgets_base_dir = $upload['basedir'] . '/master_addons/widgets';

        foreach ($widgets as $widget_post) {
            $widget_id = $widget_post->ID;
            $widget_file = $widgets_base_dir . '/' . $widget_id . '/widget.php';

            if (file_exists($widget_file)) {
                require_once $widget_file;

                $class_name = 'MasterAddons\\Addons\\JLTMA_WB_' . $widget_id;

                if (class_exists($class_name)) {
                    $widgets_manager->register(new $class_name());
                }
            }
        }
    }

    /**
     * Register custom Elementor categories
     */
    private function register_custom_categories() {
        if (!did_action('elementor/loaded')) {
            return;
        }

        // Get custom categories from options
        $custom_categories = get_option('jltma_custom_widget_categories', []);

        if (empty($custom_categories) || !is_array($custom_categories)) {
            return;
        }

        $elements_manager = \Elementor\Plugin::$instance->elements_manager;

        // Register each custom category
        foreach ($custom_categories as $slug => $title) {
            // Check if category doesn't already exist
            $existing_categories = $elements_manager->get_categories();

            if (!isset($existing_categories[$slug])) {
                $elements_manager->add_category(
                    $slug,
                    [
                        'title' => $title,
                        'icon' => 'eicon-posts-ticker',
                    ]
                );
            }
        }
    }

    public function admin_redirects() {
        global $pagenow;
        $target_post_type = 'jltma_widget';
        $redirect_url = admin_url('edit.php?post_type=jltma_widget');

        if ('post.php' === $pagenow && isset($_GET['post'])) {
            if (isset($_GET['action']) && in_array($_GET['action'], ['elementor', 'trash', 'delete', 'restore', 'untrash'], true)) {
                return;
            }
            $post_id = absint($_GET['post']);
            if ($post_id && $target_post_type === get_post_type($post_id)) {
                wp_safe_redirect($redirect_url);
                exit;
            }
        }

        if ('post-new.php' === $pagenow && isset($_GET['post_type'])) {
            $current_post_type = sanitize_key($_GET['post_type']);
            if ($target_post_type === $current_post_type) {
                wp_safe_redirect($redirect_url);
                exit;
            }
        }
    }
}
