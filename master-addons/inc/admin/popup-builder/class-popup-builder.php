<?php
namespace MasterAddons\Inc\Admin\PopupBuilder;

use MasterAddons\Master_Elementor_Addons;

if (!defined('ABSPATH')) {
    exit;
}

class Popup_Builder {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        $this->init_hooks();
        $this->includes();
    }
    
    private function init_hooks() {
        add_action('admin_menu', [$this, 'add_admin_menu'], 55);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_ajax_ma_popup_save', [$this, 'save_popup']);
        add_action('wp_ajax_ma_popup_delete', [$this, 'delete_popup']);
        add_action('wp_ajax_ma_popup_duplicate', [$this, 'duplicate_popup']);
        add_action('wp_ajax_ma_popup_toggle_status', [$this, 'toggle_popup_status']);
    }
    
    private function includes() {
        // Classes are now autoloaded via the plugin autoloader
    }
    
    public function add_admin_menu() {
        add_submenu_page(
            'master-addons-settings',
            __('Popup Builder', 'master-addons'),
            __('Popup Builder', 'master-addons'),
            'manage_options',
            'jltma-popups',
            [$this, 'render_popup_page']
        );
    }
    
    public function render_popup_page() {
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        
        switch ($action) {
            case 'new':
                $this->render_new_popup_page();
                break;
            case 'edit':
                $this->render_edit_popup_page();
                break;
            case 'templates':
                $this->render_templates_page();
                break;
            default:
                $this->render_list_page();
                break;
        }
    }
    
    private function render_list_page() {
        $list_table = new Popup_List_Table();
        $list_table->prepare_items();
        ?>
        <div class="wrap ma-popup-builder">
            <h1 class="wp-heading-inline"><?php _e('Popups', 'master-addons'); ?></h1>
            <a href="<?php echo admin_url('admin.php?page=jltma-popups&action=new'); ?>" class="page-title-action">
                <?php _e('Add New', 'master-addons'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=jltma-popups&action=templates'); ?>" class="page-title-action">
                <?php _e('Templates', 'master-addons'); ?>
            </a>
            <hr class="wp-header-end">
            
            <form method="post">
                <?php $list_table->display(); ?>
            </form>
        </div>
        <?php
    }
    
    private function render_new_popup_page() {
        ?>
        <div class="wrap ma-popup-builder">
            <h1><?php _e('Add New Popup', 'master-addons'); ?></h1>
            
            <form id="ma-popup-form" method="post">
                <div class="ma-popup-builder-container">
                    <div class="ma-popup-main-content">
                        <div class="postbox">
                            <h2 class="hndle"><?php _e('Popup Details', 'master-addons'); ?></h2>
                            <div class="inside">
                                <table class="form-table">
                                    <tr>
                                        <th scope="row">
                                            <label for="popup-name"><?php _e('Popup Name', 'master-addons'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" id="popup-name" name="popup_name" class="regular-text" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="popup-type"><?php _e('Popup Type', 'master-addons'); ?></label>
                                        </th>
                                        <td>
                                            <select id="popup-type" name="popup_type">
                                                <option value="notification"><?php _e('Notification Bar', 'master-addons'); ?></option>
                                                <option value="modal"><?php _e('Modal', 'master-addons'); ?></option>
                                                <option value="slide-in"><?php _e('Slide-in', 'master-addons'); ?></option>
                                                <option value="full-screen"><?php _e('Full Screen', 'master-addons'); ?></option>
                                                <option value="corner"><?php _e('Corner Popup', 'master-addons'); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="popup-content"><?php _e('Content', 'master-addons'); ?></label>
                                        </th>
                                        <td>
                                            <?php
                                            $content = '';
                                            $editor_id = 'popup-content';
                                            $settings = array(
                                                'textarea_name' => 'popup_content',
                                                'media_buttons' => true,
                                                'textarea_rows' => 10,
                                            );
                                            wp_editor($content, $editor_id, $settings);
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <?php $this->render_trigger_settings(); ?>
                        <?php $this->render_display_conditions(); ?>
                        <?php $this->render_design_settings(); ?>
                    </div>
                    
                    <div class="ma-popup-sidebar">
                        <?php $this->render_sidebar_settings(); ?>
                    </div>
                </div>
                
                <?php wp_nonce_field('ma_popup_save', 'ma_popup_nonce'); ?>
                <input type="hidden" name="action" value="ma_popup_save">
            </form>
        </div>
        <?php
    }
    
    private function render_edit_popup_page() {
        $popup_id = isset($_GET['popup_id']) ? intval($_GET['popup_id']) : 0;
        $popup = $this->get_popup($popup_id);
        
        if (!$popup) {
            wp_die(__('Popup not found', 'master-addons'));
        }
        
        ?>
        <div class="wrap ma-popup-builder">
            <h1><?php _e('Edit Popup', 'master-addons'); ?></h1>
            
            <form id="ma-popup-form" method="post">
                <div class="ma-popup-builder-container">
                    <div class="ma-popup-main-content">
                        <div class="postbox">
                            <h2 class="hndle"><?php _e('Popup Details', 'master-addons'); ?></h2>
                            <div class="inside">
                                <table class="form-table">
                                    <tr>
                                        <th scope="row">
                                            <label for="popup-name"><?php _e('Popup Name', 'master-addons'); ?></label>
                                        </th>
                                        <td>
                                            <input type="text" id="popup-name" name="popup_name" class="regular-text" 
                                                   value="<?php echo esc_attr($popup->name); ?>" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="popup-type"><?php _e('Popup Type', 'master-addons'); ?></label>
                                        </th>
                                        <td>
                                            <select id="popup-type" name="popup_type">
                                                <option value="notification" <?php selected($popup->type, 'notification'); ?>><?php _e('Notification Bar', 'master-addons'); ?></option>
                                                <option value="modal" <?php selected($popup->type, 'modal'); ?>><?php _e('Modal', 'master-addons'); ?></option>
                                                <option value="slide-in" <?php selected($popup->type, 'slide-in'); ?>><?php _e('Slide-in', 'master-addons'); ?></option>
                                                <option value="full-screen" <?php selected($popup->type, 'full-screen'); ?>><?php _e('Full Screen', 'master-addons'); ?></option>
                                                <option value="corner" <?php selected($popup->type, 'corner'); ?>><?php _e('Corner Popup', 'master-addons'); ?></option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="popup-content"><?php _e('Content', 'master-addons'); ?></label>
                                        </th>
                                        <td>
                                            <?php
                                            $content = $popup->content;
                                            $editor_id = 'popup-content';
                                            $settings = array(
                                                'textarea_name' => 'popup_content',
                                                'media_buttons' => true,
                                                'textarea_rows' => 10,
                                            );
                                            wp_editor($content, $editor_id, $settings);
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <?php $this->render_trigger_settings($popup); ?>
                        <?php $this->render_display_conditions($popup); ?>
                        <?php $this->render_design_settings($popup); ?>
                    </div>
                    
                    <div class="ma-popup-sidebar">
                        <?php $this->render_sidebar_settings($popup); ?>
                    </div>
                </div>
                
                <?php wp_nonce_field('ma_popup_save', 'ma_popup_nonce'); ?>
                <input type="hidden" name="action" value="ma_popup_save">
                <input type="hidden" name="popup_id" value="<?php echo $popup_id; ?>">
            </form>
        </div>
        <?php
    }
    
    private function render_trigger_settings($popup = null) {
        ?>
        <div class="postbox">
            <h2 class="hndle"><?php _e('Trigger Settings', 'master-addons'); ?></h2>
            <div class="inside">
                <input type="hidden" name="trigger_type" value="load">
                <input type="hidden" name="trigger_delay" value="0">
                <table class="form-table">
                    <tr class="trigger-scroll-row" style="display:none;">
                        <th scope="row">
                            <label for="trigger-scroll-percent"><?php _e('Scroll Percentage', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <input type="number" id="trigger-scroll-percent" name="trigger_scroll_percent" min="0" max="100" value="50">
                        </td>
                    </tr>
                    <tr class="trigger-element-row" style="display:none;">
                        <th scope="row">
                            <label for="trigger-element"><?php _e('Element Selector', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="trigger-element" name="trigger_element" class="regular-text" placeholder="#element-id or .element-class">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
    
    private function render_display_conditions($popup = null) {
        ?>
        <div class="postbox">
            <h2 class="hndle"><?php _e('Display Conditions', 'master-addons'); ?></h2>
            <div class="inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label><?php _e('Show On', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="radio" name="display_on" value="all" checked>
                                <?php _e('All Pages', 'master-addons'); ?>
                            </label><br>
                            <label>
                                <input type="radio" name="display_on" value="specific">
                                <?php _e('Specific Pages', 'master-addons'); ?>
                            </label><br>
                            <label>
                                <input type="radio" name="display_on" value="exclude">
                                <?php _e('All Pages Except', 'master-addons'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr class="specific-pages-row" style="display:none;">
                        <th scope="row">
                            <label for="specific-pages"><?php _e('Select Pages', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <select id="specific-pages" name="specific_pages[]" multiple class="regular-text">
                                <?php
                                $pages = get_pages();
                                foreach ($pages as $page) {
                                    echo '<option value="' . $page->ID . '">' . $page->post_title . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e('User Conditions', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_logged_in" value="1">
                                <?php _e('Show to Logged-in Users', 'master-addons'); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="show_logged_out" value="1" checked>
                                <?php _e('Show to Logged-out Users', 'master-addons'); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="show_first_time" value="1">
                                <?php _e('Show to First-time Visitors Only', 'master-addons'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="show-frequency"><?php _e('Show Frequency', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <select id="show-frequency" name="show_frequency">
                                <option value="always"><?php _e('Always', 'master-addons'); ?></option>
                                <option value="once_session"><?php _e('Once Per Session', 'master-addons'); ?></option>
                                <option value="once_day"><?php _e('Once Per Day', 'master-addons'); ?></option>
                                <option value="once_week"><?php _e('Once Per Week', 'master-addons'); ?></option>
                                <option value="once"><?php _e('Once Ever', 'master-addons'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
    
    private function render_design_settings($popup = null) {
        ?>
        <div class="postbox">
            <h2 class="hndle"><?php _e('Design Settings', 'master-addons'); ?></h2>
            <div class="inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="popup-position"><?php _e('Position', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <select id="popup-position" name="popup_position">
                                <option value="center"><?php _e('Center', 'master-addons'); ?></option>
                                <option value="top-left"><?php _e('Top Left', 'master-addons'); ?></option>
                                <option value="top-center"><?php _e('Top Center', 'master-addons'); ?></option>
                                <option value="top-right"><?php _e('Top Right', 'master-addons'); ?></option>
                                <option value="bottom-left"><?php _e('Bottom Left', 'master-addons'); ?></option>
                                <option value="bottom-center"><?php _e('Bottom Center', 'master-addons'); ?></option>
                                <option value="bottom-right"><?php _e('Bottom Right', 'master-addons'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="popup-width"><?php _e('Width', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <input type="text" id="popup-width" name="popup_width" value="600px" class="small-text">
                            <p class="description"><?php _e('Enter value with unit (px, %, vw)', 'master-addons'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="popup-animation"><?php _e('Animation', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <select id="popup-animation" name="popup_animation">
                                <option value="fade"><?php _e('Fade', 'master-addons'); ?></option>
                                <option value="slide-down"><?php _e('Slide Down', 'master-addons'); ?></option>
                                <option value="slide-up"><?php _e('Slide Up', 'master-addons'); ?></option>
                                <option value="slide-left"><?php _e('Slide Left', 'master-addons'); ?></option>
                                <option value="slide-right"><?php _e('Slide Right', 'master-addons'); ?></option>
                                <option value="zoom"><?php _e('Zoom', 'master-addons'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e('Overlay', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_overlay" value="1" checked>
                                <?php _e('Show Overlay', 'master-addons'); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="close_on_overlay" value="1" checked>
                                <?php _e('Close on Overlay Click', 'master-addons'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e('Close Button', 'master-addons'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_close_button" value="1" checked>
                                <?php _e('Show Close Button', 'master-addons'); ?>
                            </label><br>
                            <label>
                                <input type="checkbox" name="close_on_esc" value="1" checked>
                                <?php _e('Close on ESC Key', 'master-addons'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
    
    private function render_sidebar_settings($popup = null) {
        ?>
        <div class="postbox">
            <h2 class="hndle"><?php _e('Publish', 'master-addons'); ?></h2>
            <div class="inside">
                <div class="submitbox">
                    <div id="minor-publishing">
                        <div class="misc-pub-section">
                            <label>
                                <input type="checkbox" name="popup_status" value="active" <?php echo (!$popup || $popup->status == 'active') ? 'checked' : ''; ?>>
                                <?php _e('Active', 'master-addons'); ?>
                            </label>
                        </div>
                    </div>
                    <div id="major-publishing-actions">
                        <div id="publishing-action">
                            <button type="submit" class="button button-primary button-large">
                                <?php echo $popup ? __('Update', 'master-addons') : __('Publish', 'master-addons'); ?>
                            </button>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="postbox">
            <h2 class="hndle"><?php _e('Advanced Settings', 'master-addons'); ?></h2>
            <div class="inside">
                <p>
                    <label for="popup-priority"><?php _e('Priority', 'master-addons'); ?></label><br>
                    <input type="number" id="popup-priority" name="popup_priority" value="10" class="small-text">
                    <span class="description"><?php _e('Higher priority popups show first', 'master-addons'); ?></span>
                </p>
                <p>
                    <label for="popup-class"><?php _e('Custom CSS Class', 'master-addons'); ?></label><br>
                    <input type="text" id="popup-class" name="popup_class" class="regular-text">
                </p>
                <p>
                    <label for="popup-custom-css"><?php _e('Custom CSS', 'master-addons'); ?></label><br>
                    <textarea id="popup-custom-css" name="popup_custom_css" rows="5" class="large-text code"></textarea>
                </p>
            </div>
        </div>
        <?php
    }
    
    private function render_templates_page() {
        $templates = new Popup_Templates();
        $templates->render();
    }
    
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'jltma-popups') === false) {
            return;
        }

        \MasterAddons\Inc\Classes\Assets_Manager::enqueue('popup-builder-admin');

        wp_localize_script('jltma-popup-builder-admin', 'ma_popup_builder', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ma_popup_builder'),
            'strings' => [
                'confirm_delete' => __('Are you sure you want to delete this popup?', 'master-addons'),
                'saving' => __('Saving...', 'master-addons'),
                'saved' => __('Saved!', 'master-addons'),
                'error' => __('An error occurred. Please try again.', 'master-addons'),
            ]
        ]);
    }
    
    public function save_popup() {
        if (!check_ajax_referer('ma_popup_save', 'ma_popup_nonce', false)) {
            wp_die(__('Security check failed', 'master-addons'));
        }
        
        $popup_id = isset($_POST['popup_id']) ? intval($_POST['popup_id']) : 0;
        $popup_data = [
            'name' => sanitize_text_field($_POST['popup_name']),
            'type' => sanitize_text_field($_POST['popup_type']),
            'content' => wp_kses_post($_POST['popup_content']),
            'trigger_type' => sanitize_text_field($_POST['trigger_type']),
            'trigger_delay' => intval($_POST['trigger_delay']),
            'display_on' => sanitize_text_field($_POST['display_on']),
            'show_frequency' => sanitize_text_field($_POST['show_frequency']),
            'popup_position' => sanitize_text_field($_POST['popup_position']),
            'popup_width' => sanitize_text_field($_POST['popup_width']),
            'popup_animation' => sanitize_text_field($_POST['popup_animation']),
            'status' => isset($_POST['popup_status']) ? 'active' : 'inactive',
            'settings' => [
                'show_overlay' => isset($_POST['show_overlay']),
                'close_on_overlay' => isset($_POST['close_on_overlay']),
                'show_close_button' => isset($_POST['show_close_button']),
                'close_on_esc' => isset($_POST['close_on_esc']),
                'show_logged_in' => isset($_POST['show_logged_in']),
                'show_logged_out' => isset($_POST['show_logged_out']),
                'show_first_time' => isset($_POST['show_first_time']),
                'popup_priority' => intval($_POST['popup_priority']),
                'popup_class' => sanitize_text_field($_POST['popup_class']),
                'popup_custom_css' => sanitize_textarea_field($_POST['popup_custom_css']),
            ]
        ];
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'ma_popups';
        
        if ($popup_id) {
            $result = $wpdb->update(
                $table_name,
                [
                    'name' => $popup_data['name'],
                    'type' => $popup_data['type'],
                    'content' => $popup_data['content'],
                    'settings' => json_encode($popup_data['settings']),
                    'status' => $popup_data['status'],
                    'updated_at' => current_time('mysql')
                ],
                ['id' => $popup_id]
            );
        } else {
            $result = $wpdb->insert(
                $table_name,
                [
                    'name' => $popup_data['name'],
                    'type' => $popup_data['type'],
                    'content' => $popup_data['content'],
                    'settings' => json_encode($popup_data['settings']),
                    'status' => $popup_data['status'],
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                ]
            );
            $popup_id = $wpdb->insert_id;
        }
        
        if ($result !== false) {
            wp_send_json_success([
                'message' => __('Popup saved successfully', 'master-addons'),
                'popup_id' => $popup_id
            ]);
        } else {
            wp_send_json_error(['message' => __('Failed to save popup', 'master-addons')]);
        }
    }
    
    public function delete_popup() {
        if (!check_ajax_referer('ma_popup_builder', 'nonce', false)) {
            wp_die(__('Security check failed', 'master-addons'));
        }
        
        $popup_id = intval($_POST['popup_id']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'ma_popups';
        
        $result = $wpdb->delete($table_name, ['id' => $popup_id]);
        
        if ($result) {
            wp_send_json_success(['message' => __('Popup deleted successfully', 'master-addons')]);
        } else {
            wp_send_json_error(['message' => __('Failed to delete popup', 'master-addons')]);
        }
    }
    
    public function duplicate_popup() {
        if (!check_ajax_referer('ma_popup_builder', 'nonce', false)) {
            wp_die(__('Security check failed', 'master-addons'));
        }
        
        $popup_id = intval($_POST['popup_id']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'ma_popups';
        
        $popup = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $popup_id));
        
        if ($popup) {
            $result = $wpdb->insert(
                $table_name,
                [
                    'name' => $popup->name . ' - Copy',
                    'type' => $popup->type,
                    'content' => $popup->content,
                    'settings' => $popup->settings,
                    'status' => 'inactive',
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                ]
            );
            
            if ($result) {
                wp_send_json_success(['message' => __('Popup duplicated successfully', 'master-addons')]);
            }
        }
        
        wp_send_json_error(['message' => __('Failed to duplicate popup', 'master-addons')]);
    }
    
    public function toggle_popup_status() {
        if (!check_ajax_referer('ma_popup_builder', 'nonce', false)) {
            wp_die(__('Security check failed', 'master-addons'));
        }
        
        $popup_id = intval($_POST['popup_id']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'ma_popups';
        
        $popup = $wpdb->get_row($wpdb->prepare("SELECT status FROM $table_name WHERE id = %d", $popup_id));
        
        if ($popup) {
            $new_status = $popup->status === 'active' ? 'inactive' : 'active';
            
            $result = $wpdb->update(
                $table_name,
                ['status' => $new_status],
                ['id' => $popup_id]
            );
            
            if ($result !== false) {
                wp_send_json_success([
                    'message' => __('Status updated successfully', 'master-addons'),
                    'new_status' => $new_status
                ]);
            }
        }
        
        wp_send_json_error(['message' => __('Failed to update status', 'master-addons')]);
    }
    
    private function get_popup($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ma_popups';
        
        $popup = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        
        if ($popup && !empty($popup->settings)) {
            $popup->settings = json_decode($popup->settings, true);
        }
        
        return $popup;
    }
    
    public static function create_database_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'ma_popups';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            type varchar(50) NOT NULL,
            content longtext NOT NULL,
            settings longtext,
            status varchar(20) DEFAULT 'active',
            views int DEFAULT 0,
            conversions int DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}