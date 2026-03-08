<?php
/**
 * Master Addons Widget Builder Admin Page
 * Similar to Popup Builder Admin
 *
 * @package MasterAddons
 * @subpackage WidgetBuilder
 */

namespace MasterAddons\Inc\Admin\WidgetBuilder;

use MasterAddons\Inc\Classes\Helper;
use MasterAddons\Inc\Classes\Assets_Manager;

if (!defined('ABSPATH')) {
    exit;
}

class Widget_Admin {

    private static $instance = null;
    private $widget_cpt;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->widget_cpt = Widget_CPT::get_instance();
        $this->init_hooks();

    }

    private function init_hooks() {
        add_action('admin_menu', [$this, 'add_admin_menu'], 56);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('admin_footer', [$this, 'render_widget_modals_in_footer']);
        add_action('wp_ajax_jltma_widget_get_data', [$this, 'get_widget_data']);
        add_action('wp_ajax_jltma_widget_save_data', [$this, 'save_widget_data']);
        add_action('wp_ajax_jltma_widget_delete', [$this, 'delete_widget']);
        add_action('wp_ajax_jltma_widget_update_category', [$this, 'update_widget_category']);
        add_action('wp_ajax_jltma_widget_get_conditions', [$this, 'get_widget_conditions']);
        add_action('wp_ajax_jltma_widget_save_conditions', [$this, 'save_widget_conditions']);
        add_action('wp_ajax_jltma_widget_render_preview', [$this, 'render_preview']);
        add_filter('submenu_file', [$this, 'highlight_widget_menu'], 10, 2);
        add_action('admin_print_styles', [$this, 'hide_admin_notices']);
    }

    public function add_admin_menu() {
        // Main list → native CPT list (no callback)
        add_submenu_page(
            'master-addons-settings',
            __('Widget Builder', 'master-addons'),
            __('Widget Builder', 'master-addons'),
            'manage_options',
            'edit.php?post_type=jltma_widget'
        );

        // Hidden editor page for React app
        add_submenu_page(
            '',
            __('Edit Widget', 'master-addons'),
            __('Edit Widget', 'master-addons'),
            'manage_options',
            'jltma-widget-editor',
            [$this, 'render_widget_editor_page']
        );
    }

    public function highlight_widget_menu($submenu_file, $parent_file) {
        global $current_screen;

        if ($current_screen && (
            $current_screen->post_type === $this->widget_cpt->get_post_type() ||
            $current_screen->id === 'admin_page_jltma-widget-editor'
        )) {
            $submenu_file = 'edit.php?post_type=jltma_widget';
        }

        return $submenu_file;
    }

    /**
     * Render widget editor page (React app) — hidden submenu page
     */
    public function render_widget_editor_page() {
        $widget_id = isset($_GET['widget_id']) ? intval($_GET['widget_id']) : 0;

        if (!$widget_id) {
            wp_safe_redirect(admin_url('edit.php?post_type=jltma_widget'));
            exit;
        }

        ?>
        <div class="wrap jltma-widget-builder-wrap">
            <div id="jltma-widget-builder-app"></div>
        </div>
        <?php
    }

    /**
     * Render widget modals in footer
     */
    public function render_widget_modals_in_footer() {
        $screen = get_current_screen();
        if ($screen && ($screen->id === 'edit-jltma_widget' || $screen->id === 'admin_page_jltma-widget-editor')) {
            $this->render_widget_modal();
        }
    }

    private function render_widget_modal() {
        // Get Elementor categories
        $elementor_categories = $this->get_elementor_categories();

        ?>
        <!-- Widget Settings Modal -->
        <div id="jltma_widget_builder_modal" class="jltma_widget_builder_modal jltma-modal">
            <div class="jltma-modal-backdrop"></div>
            <div class="jltma-modal-dialog">
                <div class="jltma-modal-content">
                    <div class="jltma-pop-contents-head">
                        <div class="jltma-header-top">
                            <div class="jltma-popup-head-content" style="display: flex; align-items:center; gap:8px">
                                <span>
                                    <img src="<?php echo JLTMA_IMAGE_DIR . 'logo.svg'; ?>" style="width: 30px;">
                                </span>
                                <h3 class="jltma-modal-title"><?php echo esc_html__('Edit Widget', 'master-addons'); ?></h3>
                            </div>
                            <div class="jltma-pop-close">
                                <button class="close-btn" data-dismiss="modal"><span class="dashicons dashicons-no-alt"></span></button>
                            </div>
                        </div>
                    </div>

                    <div class="jltma-pop-contents-body" id="jltma_widget_modal_body">
                        <div class="jltma-spinner"></div>

                        <div class="jltma-pop-contents-padding">
                            <form id="jltma-widget-form">
                                <input type="hidden" name="widget_id" id="jltma_widget_id" value="">

                                <div class="jltma-modal-content-area">
                                    <div class="jltma-tab-content jltma-tab-general active">
                                        <div class="jltma-label-container">
                                            <div class="jltma-row">

                                                <div class="jltma-form-group mb-2 jltma-col-6">
                                                    <label for="jltma_widget_title" style="font-size: 16px;">
                                                        <strong><?php _e('Widget Title', 'master-addons'); ?> <span style="color: red;">*</span></strong>
                                                    </label>
                                                </div>
                                                <div class="jltma-form-group mb-2 jltma-col-6">
                                                    <input required type="text" name="widget_title" id="jltma_widget_title" class="jltma-form-control" placeholder="<?php echo esc_attr__('Enter a descriptive name for your widget', 'master-addons'); ?>">
                                                </div>

                                                <div class="jltma-form-group mb-2 jltma-col-6">
                                                    <label for="jltma_widget_category" style="font-size: 16px;">
                                                        <strong><?php _e('Widget Category', 'master-addons'); ?></strong>
                                                    </label>
                                                </div>
                                                <div class="jltma-form-group mb-2 jltma-col-6">
                                                    <select name="widget_category" id="jltma_widget_category" class="jltma-form-control">
                                                        <?php if (!empty($elementor_categories)) : ?>
                                                            <?php foreach ($elementor_categories as $category_slug => $category_data) : ?>
                                                                <option value="<?php echo esc_attr($category_slug); ?>">
                                                                    <?php echo esc_html($category_data['title']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php else : ?>
                                                            <option value="general"><?php _e('General', 'master-addons'); ?></option>
                                                            <option value="basic"><?php _e('Basic', 'master-addons'); ?></option>
                                                            <option value="pro"><?php _e('Pro', 'master-addons'); ?></option>
                                                        <?php endif; ?>
                                                        <option value="__add_new__" data-is-pro="<?php echo Helper::jltma_premium() ? '0' : '1'; ?>">
                                                            <?php echo Helper::jltma_premium()
                                                                ? __('+ Add New Category', 'master-addons')
                                                                : __('+ Add New Category (Pro)', 'master-addons');
                                                            ?>
                                                        </option>
                                                    </select>
                                                    <p class="description" style="margin-top: 8px; font-size: 12px; color: #6c757d;">
                                                        <?php _e('Select an Elementor category for this widget.', 'master-addons'); ?>
                                                    </p>

                                                    <!-- Inline Add New Category -->
                                                    <div id="jltma-inline-category-add" style="display: none; margin-top: 12px;">
                                                        <input type="text" id="jltma_new_category_title" placeholder="<?php _e('Enter Category name', 'master-addons'); ?>" style="width: 100%; padding: 6px 12px; border: 1px solid #5b6aff; border-radius: 6px; font-size: 14px; outline: none; margin-bottom: 12px; transition: border-color 0.2s ease;">
                                                        <div style="display: flex; gap: 12px; align-items: center; justify-content: flex-start;">
                                                            <button type="button" class="button button-primary jltma-save-inline-category" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 20px; background: var(--jltma-primary-glow); color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s ease;">
                                                                <span class="dashicons dashicons-plus-alt"></span>
                                                                <?php _e('Add Category', 'master-addons'); ?>
                                                            </button>
                                                            <button type="button" class="button jltma-cancel-inline-category" style="background: transparent; color: #ff4757; border: 2px solid #ff4757; padding: 2px 20px; border-radius: 6px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; box-shadow: none; text-shadow: none; white-space: nowrap;"><?php _e('Cancel', 'master-addons'); ?></button>
                                                        </div>
                                                        <p style="margin-top: 12px; font-size: 13px; color: #8a909a; font-style: italic; margin-bottom: 0;">
                                                            <?php _e('Enter a name for the new category and click Add Category.', 'master-addons'); ?>
                                                        </p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="jltma-modal-footer">
                                    <button type="submit" class="jltma-save-widget jltma-save-btn jltma-color-two">
                                        <?php _e('Save Changes', 'master-addons'); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Widget Dropzone Modal -->
        <div id="jltma_import_widget_modal" class="jltma-import-modal" style="display: none;">
            <div class="jltma-import-backdrop"></div>
            <div class="jltma-import-dialog">
                <div class="jltma-import-header">
                    <h3><?php _e('Import Widget', 'master-addons'); ?></h3>
                    <button class="jltma-import-close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="jltma-import-body">
                    <div class="jltma-dropzone" id="jltma-dropzone">
                        <div class="jltma-dropzone-content">
                            <div class="jltma-upload-icon-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                            </div>
                            <h4><?php _e('Import Widget', 'master-addons'); ?></h4>
                            <p class="jltma-dropzone-subtitle"><?php _e('Drag & drop your .json file here', 'master-addons'); ?></p>
                            <label class="jltma-browse-btn">
                                <?php _e('Browse files', 'master-addons'); ?>
                                <input type="file" id="jltma-import-file" accept=".json,application/json" style="display: none;" />
                            </label>
                            <p class="jltma-supported-format"><?php _e('Only .json files are supported', 'master-addons'); ?></p>
                        </div>
                        <div class="jltma-importing" style="display: none;">
                            <div class="jltma-import-progress">
                                <div class="jltma-progress-info">
                                    <span class="jltma-progress-label"><?php _e('Importing...', 'master-addons'); ?></span>
                                </div>
                                <div class="jltma-progress-bar">
                                    <div class="jltma-progress-fill"></div>
                                </div>
                                <p class="jltma-progress-hint"><?php _e('Do not close this window', 'master-addons'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Import Widget Modal - shadcn/ui style (matches Template Kit upload modal) */
            .jltma-import-modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 999999;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .jltma-import-backdrop {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
            }
            .jltma-import-dialog {
                position: relative;
                max-width: 520px;
                width: 90%;
                background: #ffffff;
                border: 1px solid hsl(214.3 31.8% 91.4%);
                border-radius: 12px;
                box-shadow: 0 16px 70px -12px rgba(0, 0, 0, 0.25);
                z-index: 1;
            }
            .jltma-import-header {
                padding: 16px;
                border-bottom: 1px solid hsl(220 13% 91%);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .jltma-import-header h3 {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
                color: hsl(222.2 84% 4.9%);
                letter-spacing: -0.025em;
            }
            .jltma-import-close {
                background: transparent;
                border: none;
                color: hsl(215.4 16.3% 46.9%);
                cursor: pointer;
                padding: 0;
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 6px;
                transition: all 0.15s ease;
            }
            .jltma-import-close:hover {
                background: hsl(210 40% 96.1%);
                color: hsl(222.2 84% 4.9%);
            }
            .jltma-import-body {
                padding: 16px;
            }
            .jltma-dropzone {
                border: 1.5px dashed hsl(214.3 31.8% 91.4%);
                border-radius: 10px;
                padding: 40px 24px;
                text-align: center;
                background: hsl(210 40% 98.5%);
                transition: all 0.2s ease;
                cursor: pointer;
            }
            .jltma-dropzone:hover,
            .jltma-dropzone.dragging {
                border-color: hsl(222.2 84% 4.9% / 0.3);
                background: hsl(210 40% 97%);
            }
            .jltma-dropzone.dragging {
                border-color: hsl(222.2 84% 4.9% / 0.5);
                background: hsl(210 40% 96%);
            }
            .jltma-dropzone-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0;
            }
            .jltma-upload-icon-circle {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                border: 1px solid hsl(214.3 31.8% 91.4%);
                background: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
                color: hsl(215.4 16.3% 46.9%);
                margin-bottom: 16px;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            }
            .jltma-dropzone h4 {
                color: hsl(222.2 84% 4.9%);
                font-size: 14px;
                font-weight: 600;
                letter-spacing: -0.01em;
                margin: 0;
            }
            .jltma-dropzone-subtitle {
                margin: 4px 0 0 !important;
                font-size: 13px !important;
                color: hsl(215.4 16.3% 46.9%) !important;
            }
            .jltma-browse-btn {
                display: inline-block;
                margin-top: 16px;
                padding: 8px 16px;
                font-size: 13px;
                font-weight: 500;
                color: hsl(222.2 84% 4.9%);
                background: #ffffff;
                border: 1px solid hsl(214.3 31.8% 91.4%);
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.15s ease;
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                text-transform: none;
                letter-spacing: normal;
            }
            .jltma-browse-btn:hover {
                background: hsl(210 40% 96.1%);
                border-color: hsl(214.3 31.8% 85%);
                transform: none;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            }
            .jltma-supported-format {
                color: hsl(215.4 16.3% 60%) !important;
                font-size: 12px !important;
                margin: 12px 0 0 0 !important;
            }
            .jltma-importing {
                text-align: center;
            }
            .jltma-import-progress {
                padding: 8px 0;
            }
            .jltma-progress-info {
                display: flex;
                justify-content: center;
                margin-bottom: 12px;
            }
            .jltma-progress-label {
                font-size: 13px;
                font-weight: 500;
                color: hsl(222.2 84% 4.9%);
            }
            .jltma-progress-bar {
                height: 6px;
                background: hsl(214.3 31.8% 91.4%);
                border-radius: 100px;
                overflow: hidden;
            }
            .jltma-progress-fill {
                height: 100%;
                width: 100%;
                background: hsl(222.2 84% 4.9%);
                border-radius: 100px;
                animation: jltma-progress-indeterminate 1.5s ease-in-out infinite;
            }
            .jltma-progress-hint {
                margin: 8px 0 0 !important;
                font-size: 12px !important;
                color: hsl(215.4 16.3% 60%) !important;
            }
            @keyframes jltma-progress-indeterminate {
                0% { transform: translateX(-100%); }
                50% { transform: translateX(0%); }
                100% { transform: translateX(100%); }
            }
        </style>
        <?php
    }

    public function enqueue_admin_assets($hook) {
        $screen = get_current_screen();
        if (!$screen) {
            return;
        }

        $is_list = ($screen->id === 'edit-jltma_widget');
        $is_editor = ($screen->id === 'admin_page_jltma-widget-editor');

        if (!$is_list && !$is_editor) {
            return;
        }

        // Enqueue WordPress Media Library
        if (!did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }

        // Enqueue Icon Library CSS Files
        $icon_library_helper = Icon_Library_Helper::get_instance();
        $icon_library_helper->enqueue_icon_libraries();

        // Enqueue all Widget Builder assets via Assets_Manager
        Assets_Manager::enqueue(['widget-builder', 'widget-admin', 'widget-builder-app']);

        // Localize script for list view
        wp_localize_script('jltma-widget-admin', 'jltmaWidgetAdmin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'admin_url' => admin_url(),
            'rest_url' => rest_url('jltma/v1'),
            'widget_nonce' => wp_create_nonce('jltma_widget_nonce'),
            'rest_nonce' => wp_create_nonce('wp_rest'),
            'strings' => [
                'confirm_delete' => __('Are you sure you want to delete this widget?', 'master-addons'),
                'saving' => __('Saving...', 'master-addons'),
                'saved' => __('Widget saved successfully!', 'master-addons'),
                'error' => __('An error occurred. Please try again.', 'master-addons'),
                'widget_title_required' => __('Widget title is required.', 'master-addons'),
                'category_added' => __('Category added successfully!', 'master-addons'),
                'copied' => __('Copied to clipboard!', 'master-addons'),
            ]
        ]);

        // Localize script for React app
        $widget_id = isset($_GET['widget_id']) ? intval($_GET['widget_id']) : 0;
        $localize_data = [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wp_rest'),
            'widget_id' => $widget_id,
            'rest_url' => rest_url('jltma/v1'),
            'apiBase' => rest_url('jltma/v1'),
            'pluginUrl' => JLTMA_URL,
            'isPro' => Helper::jltma_premium(),
            'proControls' => [], // Pro controls added via filter
        ];

        // Allow Pro version to add controls
        $localize_data = apply_filters('jltma_widget_builder_localize_data', $localize_data);

        wp_localize_script('jltma-widget-builder-app', 'JLTMAWidgetBuilder', $localize_data);

        // Localize Icon Library Configuration
        $icon_library_helper->localize_icon_library();
    }

    /**
     * Hide third-party admin notices on Widget Builder pages
     * Only show Master Addons notices
     */
    public function hide_admin_notices() {
        $screen = get_current_screen();

        // Only hide notices on the widget editor page (not the native CPT list)
        if (!$screen || $screen->id !== 'admin_page_jltma-widget-editor') {
            return;
        }

        // Hide all admin notices except Master Addons notices
        ?>
        <style>
            .notice:not(.jltma-notice):not(.master-addons-notice),
            .update-nag:not(.jltma-notice):not(.master-addons-notice),
            .updated:not(.jltma-notice):not(.master-addons-notice),
            .error:not(.jltma-notice):not(.master-addons-notice) {
                display: none !important;
            }
        </style>
        <?php
    }

    public function get_widget_data() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'You do not have permission to perform this action.'], 403);
        }

        check_ajax_referer('jltma_widget_nonce', '_nonce');

        $widget_id = intval($_GET['widget_id']);

        if (!$widget_id) {
            wp_send_json_error(['message' => 'Invalid widget ID']);
        }

        $widget = get_post($widget_id);
        if (!$widget || $widget->post_type !== $this->widget_cpt->get_post_type()) {
            wp_send_json_error(['message' => 'Widget not found']);
        }

        $category = get_post_meta($widget_id, '_jltma_widget_category', true) ?: 'general';

        $data = [
            'id' => $widget_id,
            'title' => $widget->post_title,
            'widget_name' => get_post_meta($widget_id, '_jltma_widget_name', true),
            'category' => $category,
            'widget_data' => get_post_meta($widget_id, '_jltma_widget_data', true) ?: [],
        ];

        wp_send_json_success($data);
    }

    public function save_widget_data() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'You do not have permission to perform this action.'], 403);
        }

        check_ajax_referer('jltma_widget_nonce', '_nonce');

        $widget_id = intval($_POST['widget_id']);
        $title = sanitize_text_field($_POST['widget_title']);
        $category = sanitize_text_field($_POST['widget_category']);

        // Generate widget name from title if creating new
        $widget_name = !$widget_id ? sanitize_title($title) : get_post_meta($widget_id, '_jltma_widget_name', true);
        if (empty($widget_name)) {
            $widget_name = sanitize_title($title);
        }

        if ($widget_id) {
            // Update existing widget
            wp_update_post([
                'ID' => $widget_id,
                'post_title' => $title,
            ]);
        } else {
            // Create new widget
            $widget_id = wp_insert_post([
                'post_title' => $title,
                'post_type' => $this->widget_cpt->get_post_type(),
                'post_status' => 'publish',
                'post_content' => '',
            ]);

            if (is_wp_error($widget_id)) {
                return;
            }

            // Verify widget was created
            $verify_widget = get_post($widget_id);
        }

        // Save meta data
        update_post_meta($widget_id, '_jltma_widget_name', $widget_name);
        update_post_meta($widget_id, '_jltma_widget_category', $category);

        // Clear post cache to ensure REST API can fetch it immediately
        clean_post_cache($widget_id);

        // Generate widget files
        $this->generate_widget_files($widget_id);

        $edit_url = admin_url('admin.php?page=jltma-widget-editor&widget_id=' . $widget_id);

        $response_data = [
            'id' => $widget_id,
            'title' => $title,
            'widget_name' => $widget_name,
            'category' => $category,
            'edit_url' => $edit_url,
        ];

        wp_send_json_success($response_data);
    }

    /**
     * Generate widget files using the generator
     *
     * @param int $widget_id
     */
    private function generate_widget_files($widget_id) {
        $generator = new Widget_Generator($widget_id);
        $result = $generator->generate();
    }

    public function delete_widget() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'You do not have permission to perform this action.'], 403);
        }

        check_ajax_referer('jltma_widget_nonce', '_nonce');

        $widget_id = intval($_POST['widget_id']);

        if (!$widget_id) {
            wp_send_json_error(['message' => 'Invalid widget ID']);
        }

        // Delete widget files first
        Widget_Generator::delete_widget_files($widget_id);

        $result = wp_delete_post($widget_id, true);

        if ($result) {
            wp_send_json_success(['message' => 'Widget deleted successfully']);
        } else {
            wp_send_json_error(['message' => 'Failed to delete widget']);
        }
    }

    public function update_widget_category() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'You do not have permission to perform this action.'], 403);
        }

        check_ajax_referer('jltma_widget_nonce', '_nonce');

        $widget_id = intval($_POST['widget_id']);
        $category = sanitize_text_field($_POST['category']);

        if (!$widget_id) {
            wp_send_json_error(['message' => 'Invalid widget ID']);
        }

        update_post_meta($widget_id, '_jltma_widget_category', $category);

        wp_send_json_success(['message' => 'Category updated successfully']);
    }

    /**
     * Get all registered Elementor categories
     */
    private function get_elementor_categories() {
        if (!defined('ELEMENTOR_VERSION')) {
            return [];
        }

        $categories = [];

        try {
            $elementor_categories = \Elementor\Plugin::$instance->elements_manager->get_categories();

            foreach ($elementor_categories as $category_slug => $category_data) {
                $categories[$category_slug] = [
                    'title' => $category_data['title'],
                    'icon' => $category_data['icon'] ?? '',
                ];
            }
        } catch (\Exception $e) {
            // Fallback categories if Elementor is not available
            $categories = [
                'general' => ['title' => 'General', 'icon' => ''],
                'basic' => ['title' => 'Basic', 'icon' => ''],
                'pro' => ['title' => 'Pro', 'icon' => ''],
            ];
        }

        // Add custom categories from options (same as REST controller)
        $custom_categories = get_option('jltma_custom_widget_categories', []);
        if (!empty($custom_categories) && is_array($custom_categories)) {
            foreach ($custom_categories as $slug => $title) {
                // Check if not already in list (Elementor categories take precedence)
                if (!isset($categories[$slug])) {
                    $categories[$slug] = [
                        'title' => $title,
                        'icon' => '',
                    ];
                }
            }
        }

        return $categories;
    }

    /**
     * Get widget conditions via AJAX
     */
    public function get_widget_conditions() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'You do not have permission to perform this action.'], 403);
        }

        check_ajax_referer('jltma_widget_nonce', '_nonce');

        $widget_id = intval($_GET['widget_id']);

        if (!$widget_id) {
            wp_send_json_error(['message' => 'Invalid widget ID']);
        }

        $conditions = get_post_meta($widget_id, '_jltma_widget_conditions', true);

        if (empty($conditions)) {
            $conditions = [
                'enabled' => false,
                'user_roles' => [],
                'device' => '',
                'page_type' => [],
                'date_start' => '',
                'date_end' => ''
            ];
        }

        wp_send_json_success(['conditions' => $conditions]);
    }

    /**
     * Save widget conditions via AJAX
     */
    public function save_widget_conditions() {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'You do not have permission to perform this action.'], 403);
        }

        check_ajax_referer('jltma_widget_nonce', '_nonce');

        $widget_id = intval($_POST['widget_id']);

        if (!$widget_id) {
            wp_send_json_error(['message' => 'Invalid widget ID']);
        }

        $conditions = [
            'enabled' => !empty($_POST['enabled']),
            'user_roles' => isset($_POST['user_roles']) ? array_map('sanitize_text_field', (array) $_POST['user_roles']) : [],
            'device' => sanitize_text_field($_POST['device'] ?? ''),
            'page_type' => isset($_POST['page_type']) ? array_map('sanitize_text_field', (array) $_POST['page_type']) : [],
            'date_start' => sanitize_text_field($_POST['date_start'] ?? ''),
            'date_end' => sanitize_text_field($_POST['date_end'] ?? '')
        ];

        update_post_meta($widget_id, '_jltma_widget_conditions', $conditions);

        wp_send_json_success(['message' => 'Conditions saved successfully']);
    }

    /**
     * Render widget preview with PHP execution
     * Handles AJAX request to render widget HTML with PHP code executed
     */
    public function render_preview() {
        // Capability check: only administrators can execute widget previews (contains eval)
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'You do not have permission to perform this action.'], 403);
            return;
        }

        $nonce = isset($_POST['_nonce']) ? $_POST['_nonce'] : (isset($_GET['_nonce']) ? $_GET['_nonce'] : '');
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            wp_send_json_error(['message' => 'Nonce verification failed']);
            return;
        }

        $html_code = isset($_POST['html_code']) ? wp_unslash($_POST['html_code']) : '';
        $css_code = isset($_POST['css_code']) ? wp_unslash($_POST['css_code']) : '';
        $controls = isset($_POST['controls']) ? json_decode(wp_unslash($_POST['controls']), true) : [];

        // Build mock settings array from controls
        $settings = [];
        if (!empty($controls)) {
            foreach ($controls as $control) {
                if (isset($control['name']) && isset($control['default'])) {
                    $settings[$control['name']] = $control['default'];
                }
            }
        }

        // Replace placeholders with PHP variables using regex to handle dot notation
        // Handles: {{field}}, {{field.property}}, {{field.property.subproperty}}
        if (!empty($controls)) {
            foreach ($controls as $control) {
                if (isset($control['name'])) {
                    $control_name = $control['name'];

                    // Regex to match {{control_name}} or {{control_name.property}} or {{control_name.property.subproperty}}
                    // Pattern: {{control_name}} followed by optional .property.property...
                    $pattern = '/\{\{' . preg_quote($control_name, '/') . '((?:\.[a-zA-Z0-9_]+)*)\}\}/';

                    $html_code = preg_replace_callback($pattern, function($matches) use ($control_name) {
                        $properties = $matches[1]; // e.g., "" or ".tabs" or ".property.subproperty"

                        if (empty($properties)) {
                            // No properties, just {{control_name}}
                            // Return: $settings['control_name']
                            return "\$settings['" . $control_name . "']";
                        } else {
                            // Has properties like .tabs or .property.subproperty
                            // Convert to: $settings['control_name']['tabs'] or $settings['control_name']['property']['subproperty']
                            $props = explode('.', ltrim($properties, '.'));
                            $result = "\$settings['" . $control_name . "']";
                            foreach ($props as $prop) {
                                if (!empty($prop)) {
                                    $result .= "['" . $prop . "']";
                                }
                            }
                            return $result;
                        }
                    }, $html_code);
                }
            }
        }

        // Execute PHP code in the HTML
        ob_start();

        // Make settings available in the PHP context
        extract($settings, EXTR_SKIP);

        // Evaluate the PHP code
        // Wrap in PHP tags if not present
        if (strpos($html_code, '<?php') === false && strpos($html_code, '<?=') === false) {
            // No PHP code, just output as is
            echo $html_code;
        } else {
            // Has PHP code, evaluate it
            try {
                eval('?>' . $html_code . '<?php ;');
            } catch (ParseError $e) {
                echo '<div style="color:red;padding:20px;background:#fff3cd;border:1px solid #ffc107;">';
                echo '<h3>PHP Parse Error in Preview:</h3>';
                echo '<pre>' . esc_html($e->getMessage()) . '</pre>';
                echo '<h4>Line ' . $e->getLine() . '</h4>';
                echo '</div>';
            } catch (Exception $e) {
                echo '<div style="color:red;padding:20px;background:#fff3cd;border:1px solid #ffc107;">';
                echo '<h3>PHP Error in Preview:</h3>';
                echo '<pre>' . esc_html($e->getMessage()) . '</pre>';
                echo '</div>';
            }
        }

        $rendered_html = ob_get_clean();

        // Build full HTML document with CSS
        $output = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>' . $css_code . '</style>
</head>
<body>' . $rendered_html . '</body>
</html>';

        wp_send_json_success(['html' => $output]);
    }
}
