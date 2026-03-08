<?php

namespace MasterAddons\Inc\Admin\Theme_Builder;

defined('ABSPATH') || exit;

class Loader
{
    private static $_instance = null;

    public function __construct()
    {
        // Load the main Theme_Builder class via autoloader
        Theme_Builder::get_instance();

        // Handle admin redirects
        add_action('admin_init', [$this, 'handle_admin_redirects']);
    }

    /**
     * Handle redirects for master_template post type
     */
    public function handle_admin_redirects()
    {
        global $pagenow;
        $target_post_type = 'master_template';
        $redirect_url = admin_url('edit.php?post_type=master_template');

        if ('post.php' === $pagenow && isset($_GET['post'])) {
            if (isset($_GET['action']) && in_array($_GET['action'], ['elementor', 'trash', 'delete', 'restore', 'untrash'], true)) {
                return;
            }

            $post_id = absint($_GET['post']);
            if ($post_id) {
                $current_post_type = get_post_type($post_id);

                if ($target_post_type === $current_post_type) {
                    $master_template_type = get_post_meta($post_id, 'master_template_type', true);
                    if (!empty($master_template_type)) {
                        $redirect_url .= '&master_template_type_filter=' . $master_template_type;
                    }
                    wp_safe_redirect($redirect_url);
                    exit;
                }
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

    public static function get_instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
