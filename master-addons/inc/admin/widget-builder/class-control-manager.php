<?php
namespace MasterAddons\Inc\Admin\WidgetBuilder;

defined('ABSPATH') || exit;

/**
 * Control Manager
 * Loads and manages all control type builders
 */
class Control_Manager {

    private static $instance = null;
    private $controls = [];

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->load_controls();
    }

    /**
     * Load all control classes
     */
    private function load_controls() {
        // Load base class first
        require_once __DIR__ . '/controls/class-control-base.php';

        // Load FREE control type classes only
        $control_files = [
            'color',
            'heading',
            'hidden',
            'text',
            'textarea',
            'url',
        ];

        foreach ($control_files as $file) {
            $file_path = __DIR__ . '/controls/' . $file . '.php';
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }

        // Allow Pro to load additional controls
        do_action('jltma_widget_builder_load_controls');
    }

    /**
     * Get control builder instance by type
     *
     * @param string $type Control type (TEXT, TEXTAREA, etc.) - can be uppercase or lowercase
     * @return Controls\Control_Base|null
     */
    public function get_control($type) {
        // Convert to uppercase for consistency (React UI sends lowercase, but we need uppercase for mapping)
        $type = strtoupper($type);

        // Return cached instance if exists
        if (isset($this->controls[$type])) {
            return $this->controls[$type];
        }

        // Map control types to classes (FREE controls only)
        $class_map = [
            'COLOR' => 'Color',
            'HEADING' => 'Heading',
            'HIDDEN' => 'Hidden',
            'TEXT' => 'Text',
            'TEXTAREA' => 'Textarea',
            'URL' => 'Url',
        ];

        // Allow Pro to add additional control class mappings
        $class_map = apply_filters('jltma_widget_builder_control_class_map', $class_map);

        if (!isset($class_map[$type])) {
            return null;
        }

        // Check if class_map value is a full class name (from Pro) or just class name (free)
        if (strpos($class_map[$type], '\\') !== false) {
            $class_name = $class_map[$type];
        } else {
            $class_name = '\\MasterAddons\\Inc\\Admin\\WidgetBuilder\\Controls\\' . $class_map[$type];
        }

        if (!class_exists($class_name)) {
            return null;
        }

        // Create and cache instance
        $this->controls[$type] = new $class_name();

        return $this->controls[$type];
    }

    /**
     * Build control output
     *
     * @param string $control_key Control key
     * @param array $field Field configuration
     * @param string $type Control type
     * @return string
     */
    public function build_control($control_key, $field, $type) {
        $control = $this->get_control($type);
        
        if (!$control) {
            // Fallback for unknown control types
            return $this->build_fallback_control($control_key, $field, $type);
        }

        return $control->build($control_key, $field);
    }

    /**
     * Build fallback control for unknown types
     *
     * @param string $control_key Control key
     * @param array $field Field configuration
     * @param string $type Control type
     * @return string
     */
    private function build_fallback_control($control_key, $field, $type) {
        $label = !empty($field['label']) ? esc_js($field['label']) : 'Control';

        $content = "\t\t\$this->add_control(\n";
        $content .= "\t\t\t'{$control_key}',\n";
        $content .= "\t\t\t[\n";
        $content .= "\t\t\t\t'label' => esc_html__('{$label}', 'master-addons'),\n";
        $content .= "\t\t\t\t'type' => Controls_Manager::{$type},\n";

        if (isset($field['default'])) {
            $default = is_string($field['default']) ? "'" . esc_js($field['default']) . "'" : $field['default'];
            $content .= "\t\t\t\t'default' => {$default},\n";
        }

        $content .= "\t\t\t]\n";
        $content .= "\t\t);\n\n";

        return $content;
    }
}
