<?php
namespace MasterAddons\Inc\Admin\PopupBuilder;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Popup_List_Table extends \WP_List_Table {
    
    public function __construct() {
        parent::__construct([
            'singular' => __('Popup', 'master-addons'),
            'plural'   => __('Popups', 'master-addons'),
            'ajax'     => false
        ]);
    }
    
    public function get_columns() {
        $columns = [
            'cb'          => '<input type="checkbox" />',
            'name'        => __('Name', 'master-addons'),
            'type'        => __('Type', 'master-addons'),
            'status'      => __('Status', 'master-addons'),
            'views'       => __('Views', 'master-addons'),
            'conversions' => __('Conversions', 'master-addons'),
            'created_at'  => __('Created', 'master-addons'),
        ];
        
        return $columns;
    }
    
    public function get_sortable_columns() {
        $sortable_columns = [
            'name'        => ['name', true],
            'type'        => ['type', false],
            'status'      => ['status', false],
            'views'       => ['views', false],
            'conversions' => ['conversions', false],
            'created_at'  => ['created_at', false],
        ];
        
        return $sortable_columns;
    }
    
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = [$columns, $hidden, $sortable];
        
        $per_page = $this->get_items_per_page('popups_per_page', 20);
        $current_page = $this->get_pagenum();
        
        $data = $this->get_popups($per_page, $current_page);
        $total_items = $this->get_popups_count();
        
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page
        ]);
        
        $this->items = $data;
    }
    
    private function get_popups($per_page = 20, $page_number = 1) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'ma_popups';
        
        $sql = "SELECT * FROM $table_name";
        
        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY created_at DESC';
        }
        
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
        
        $results = $wpdb->get_results($sql, 'ARRAY_A');
        
        return $results ? $results : [];
    }
    
    private function get_popups_count() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ma_popups';
        
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }
    
    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="popup[]" value="%s" />',
            $item['id']
        );
    }
    
    public function column_name($item) {
        $edit_url = admin_url('admin.php?page=ma-popups&action=edit&popup_id=' . $item['id']);
        $delete_url = wp_nonce_url(
            admin_url('admin.php?page=ma-popups&action=delete&popup_id=' . $item['id']),
            'delete_popup_' . $item['id']
        );
        
        $actions = [
            'edit'      => sprintf('<a href="%s">%s</a>', $edit_url, __('Edit', 'master-addons')),
            'duplicate' => sprintf(
                '<a href="#" class="ma-popup-duplicate" data-id="%s">%s</a>',
                $item['id'],
                __('Duplicate', 'master-addons')
            ),
            'delete'    => sprintf(
                '<a href="#" class="ma-popup-delete" data-id="%s">%s</a>',
                $item['id'],
                __('Delete', 'master-addons')
            ),
        ];
        
        return sprintf('%1$s %2$s',
            '<strong><a href="' . $edit_url . '">' . esc_html($item['name']) . '</a></strong>',
            $this->row_actions($actions)
        );
    }
    
    public function column_type($item) {
        $types = [
            'notification' => __('Notification Bar', 'master-addons'),
            'modal'        => __('Modal', 'master-addons'),
            'slide-in'     => __('Slide-in', 'master-addons'),
            'full-screen'  => __('Full Screen', 'master-addons'),
            'corner'       => __('Corner Popup', 'master-addons'),
        ];
        
        return isset($types[$item['type']]) ? $types[$item['type']] : $item['type'];
    }
    
    public function column_trigger($item) {
        if (!empty($item['settings'])) {
            $settings = json_decode($item['settings'], true);
            
            if (isset($settings['trigger_type'])) {
                $triggers = [
                    'load'           => __('On Load', 'master-addons'),
                    'scroll'         => __('On Scroll', 'master-addons'),
                    'element-scroll' => __('Element Scroll', 'master-addons'),
                    'exit'           => __('Exit Intent', 'master-addons'),
                    'inactivity'     => __('Inactivity', 'master-addons'),
                    'click'          => __('On Click', 'master-addons'),
                ];
                
                return isset($triggers[$settings['trigger_type']]) 
                    ? $triggers[$settings['trigger_type']] 
                    : $settings['trigger_type'];
            }
        }
        
        return __('On Load', 'master-addons');
    }
    
    public function column_status($item) {
        $status_class = $item['status'] === 'active' ? 'active' : 'inactive';
        $status_text = $item['status'] === 'active' 
            ? __('Active', 'master-addons') 
            : __('Inactive', 'master-addons');
        
        return sprintf(
            '<a href="#" class="ma-popup-status ma-status-%s" data-id="%s">%s</a>',
            $status_class,
            $item['id'],
            $status_text
        );
    }
    
    public function column_views($item) {
        return number_format_i18n($item['views']);
    }
    
    public function column_conversions($item) {
        $conversions = (int) $item['conversions'];
        $views = (int) $item['views'];
        
        if ($views > 0) {
            $rate = ($conversions / $views) * 100;
            return sprintf(
                '%s <span class="conversion-rate">(%s%%)</span>',
                number_format_i18n($conversions),
                number_format_i18n($rate, 2)
            );
        }
        
        return number_format_i18n($conversions);
    }
    
    public function column_created_at($item) {
        return date_i18n(get_option('date_format'), strtotime($item['created_at']));
    }
    
    public function get_bulk_actions() {
        $actions = [
            'bulk-delete'     => __('Delete', 'master-addons'),
            'bulk-activate'   => __('Activate', 'master-addons'),
            'bulk-deactivate' => __('Deactivate', 'master-addons'),
        ];
        
        return $actions;
    }
    
    public function process_bulk_action() {
        if ('delete' === $this->current_action()) {
            $popup_id = absint($_GET['popup_id']);
            
            if (wp_verify_nonce($_GET['_wpnonce'], 'delete_popup_' . $popup_id)) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'ma_popups';
                
                $wpdb->delete($table_name, ['id' => $popup_id]);
                
                wp_redirect(admin_url('admin.php?page=ma-popups&deleted=1'));
                exit;
            }
        }
        
        if (isset($_POST['action']) && $_POST['action'] != -1) {
            $action = $_POST['action'];
        } elseif (isset($_POST['action2']) && $_POST['action2'] != -1) {
            $action = $_POST['action2'];
        } else {
            return;
        }
        
        if (!isset($_POST['popup']) || !is_array($_POST['popup'])) {
            return;
        }
        
        $popup_ids = array_map('intval', $_POST['popup']);
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'ma_popups';
        
        switch ($action) {
            case 'bulk-delete':
                foreach ($popup_ids as $id) {
                    $wpdb->delete($table_name, ['id' => $id]);
                }
                break;
                
            case 'bulk-activate':
                foreach ($popup_ids as $id) {
                    $wpdb->update($table_name, ['status' => 'active'], ['id' => $id]);
                }
                break;
                
            case 'bulk-deactivate':
                foreach ($popup_ids as $id) {
                    $wpdb->update($table_name, ['status' => 'inactive'], ['id' => $id]);
                }
                break;
        }
        
        wp_redirect(admin_url('admin.php?page=ma-popups'));
        exit;
    }
    
    public function no_items() {
        _e('No popups found. Create your first popup to get started!', 'master-addons');
    }
}