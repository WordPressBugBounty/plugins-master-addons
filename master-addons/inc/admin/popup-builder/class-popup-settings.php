<?php
namespace MasterAddons\Inc\Admin\PopupBuilder;

if (!defined('ABSPATH')) {
    exit;
}

class Popup_Settings {
    
    private $default_settings = [];
    
    public function __construct() {
        $this->init_default_settings();
    }
    
    private function init_default_settings() {
        $this->default_settings = [
            'general' => [
                'enable_popups' => true,
                'load_assets_globally' => false,
                'debug_mode' => false,
                'analytics_tracking' => true,
            ],
            'appearance' => [
                'default_animation' => 'fade',
                'animation_duration' => 400,
                'overlay_color' => 'rgba(0, 0, 0, 0.7)',
                'close_button_position' => 'top-right',
                'close_button_style' => 'icon',
                'z_index' => 999999,
            ],
            'behavior' => [
                'prevent_scroll' => true,
                'close_on_overlay_click' => true,
                'close_on_esc_key' => true,
                'auto_focus' => true,
                'mobile_behavior' => 'responsive',
            ],
            'advanced' => [
                'cookie_expiration' => 30,
                'test_mode' => false,
                'test_mode_ips' => '',
                'disable_on_mobile' => false,
                'custom_css' => '',
                'custom_js' => '',
            ],
            'integrations' => [
                'google_analytics' => false,
                'google_analytics_event' => 'popup_view',
                'facebook_pixel' => false,
                'facebook_pixel_event' => 'ViewContent',
                'mailchimp_api' => '',
                'mailchimp_list_id' => '',
            ],
            'performance' => [
                'lazy_load' => true,
                'preload_fonts' => false,
                'minify_css' => true,
                'minify_js' => true,
                'cache_duration' => 3600,
            ]
        ];
    }
    
    public function get_settings($section = null) {
        $saved_settings = get_option('ma_popup_settings', []);
        $settings = wp_parse_args($saved_settings, $this->default_settings);
        
        if ($section && isset($settings[$section])) {
            return $settings[$section];
        }
        
        return $settings;
    }
    
    public function save_settings($settings) {
        $current_settings = $this->get_settings();
        $updated_settings = wp_parse_args($settings, $current_settings);
        
        return update_option('ma_popup_settings', $updated_settings);
    }
    
    public function render_settings_page() {
        if (isset($_POST['ma_popup_settings_nonce'])) {
            if (wp_verify_nonce($_POST['ma_popup_settings_nonce'], 'ma_popup_settings')) {
                $this->process_settings_save();
            }
        }
        
        $settings = $this->get_settings();
        ?>
        <div class="wrap ma-popup-settings">
            <h1><?php _e('Popup Settings', 'master-addons'); ?></h1>
            
            <form method="post" action="">
                <div class="ma-settings-tabs">
                    <ul class="ma-tabs-nav">
                        <li class="active"><a href="#general"><?php _e('General', 'master-addons'); ?></a></li>
                        <li><a href="#appearance"><?php _e('Appearance', 'master-addons'); ?></a></li>
                        <li><a href="#behavior"><?php _e('Behavior', 'master-addons'); ?></a></li>
                        <li><a href="#advanced"><?php _e('Advanced', 'master-addons'); ?></a></li>
                        <li><a href="#integrations"><?php _e('Integrations', 'master-addons'); ?></a></li>
                        <li><a href="#performance"><?php _e('Performance', 'master-addons'); ?></a></li>
                    </ul>
                    
                    <div class="ma-tabs-content">
                        <?php $this->render_general_settings($settings['general']); ?>
                        <?php $this->render_appearance_settings($settings['appearance']); ?>
                        <?php $this->render_behavior_settings($settings['behavior']); ?>
                        <?php $this->render_advanced_settings($settings['advanced']); ?>
                        <?php $this->render_integrations_settings($settings['integrations']); ?>
                        <?php $this->render_performance_settings($settings['performance']); ?>
                    </div>
                </div>
                
                <?php wp_nonce_field('ma_popup_settings', 'ma_popup_settings_nonce'); ?>
                <p class="submit">
                    <button type="submit" class="button button-primary">
                        <?php _e('Save Settings', 'master-addons'); ?>
                    </button>
                </p>
            </form>
        </div>
        <?php
    }
    
    private function render_general_settings($settings) {
        ?>
        <div id="general" class="ma-tab-pane active">
            <h2><?php _e('General Settings', 'master-addons'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Enable Popups', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[general][enable_popups]" 
                                   value="1" <?php checked($settings['enable_popups'], true); ?>>
                            <?php _e('Enable popup functionality on your site', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Load Assets', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[general][load_assets_globally]" 
                                   value="1" <?php checked($settings['load_assets_globally'], true); ?>>
                            <?php _e('Load popup assets on all pages (recommended for better performance)', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Debug Mode', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[general][debug_mode]" 
                                   value="1" <?php checked($settings['debug_mode'], true); ?>>
                            <?php _e('Enable debug mode for troubleshooting', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Analytics Tracking', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[general][analytics_tracking]" 
                                   value="1" <?php checked($settings['analytics_tracking'], true); ?>>
                            <?php _e('Track popup views and conversions', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    private function render_appearance_settings($settings) {
        ?>
        <div id="appearance" class="ma-tab-pane">
            <h2><?php _e('Appearance Settings', 'master-addons'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Default Animation', 'master-addons'); ?></th>
                    <td>
                        <select name="settings[appearance][default_animation]">
                            <option value="fade" <?php selected($settings['default_animation'], 'fade'); ?>>
                                <?php _e('Fade', 'master-addons'); ?>
                            </option>
                            <option value="slide-down" <?php selected($settings['default_animation'], 'slide-down'); ?>>
                                <?php _e('Slide Down', 'master-addons'); ?>
                            </option>
                            <option value="slide-up" <?php selected($settings['default_animation'], 'slide-up'); ?>>
                                <?php _e('Slide Up', 'master-addons'); ?>
                            </option>
                            <option value="zoom" <?php selected($settings['default_animation'], 'zoom'); ?>>
                                <?php _e('Zoom', 'master-addons'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Animation Duration', 'master-addons'); ?></th>
                    <td>
                        <input type="number" name="settings[appearance][animation_duration]" 
                               value="<?php echo esc_attr($settings['animation_duration']); ?>" min="100" max="2000">
                        <span class="description"><?php _e('Duration in milliseconds', 'master-addons'); ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Overlay Color', 'master-addons'); ?></th>
                    <td>
                        <input type="text" name="settings[appearance][overlay_color]" 
                               value="<?php echo esc_attr($settings['overlay_color']); ?>" 
                               class="ma-color-picker">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Close Button Position', 'master-addons'); ?></th>
                    <td>
                        <select name="settings[appearance][close_button_position]">
                            <option value="top-right" <?php selected($settings['close_button_position'], 'top-right'); ?>>
                                <?php _e('Top Right', 'master-addons'); ?>
                            </option>
                            <option value="top-left" <?php selected($settings['close_button_position'], 'top-left'); ?>>
                                <?php _e('Top Left', 'master-addons'); ?>
                            </option>
                            <option value="bottom-right" <?php selected($settings['close_button_position'], 'bottom-right'); ?>>
                                <?php _e('Bottom Right', 'master-addons'); ?>
                            </option>
                            <option value="bottom-left" <?php selected($settings['close_button_position'], 'bottom-left'); ?>>
                                <?php _e('Bottom Left', 'master-addons'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Z-Index', 'master-addons'); ?></th>
                    <td>
                        <input type="number" name="settings[appearance][z_index]" 
                               value="<?php echo esc_attr($settings['z_index']); ?>" min="1">
                        <span class="description"><?php _e('Stack order of popups', 'master-addons'); ?></span>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    private function render_behavior_settings($settings) {
        ?>
        <div id="behavior" class="ma-tab-pane">
            <h2><?php _e('Behavior Settings', 'master-addons'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Prevent Page Scroll', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[behavior][prevent_scroll]" 
                                   value="1" <?php checked($settings['prevent_scroll'], true); ?>>
                            <?php _e('Prevent page scrolling when popup is open', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Close on Overlay Click', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[behavior][close_on_overlay_click]" 
                                   value="1" <?php checked($settings['close_on_overlay_click'], true); ?>>
                            <?php _e('Close popup when overlay is clicked', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Close on ESC Key', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[behavior][close_on_esc_key]" 
                                   value="1" <?php checked($settings['close_on_esc_key'], true); ?>>
                            <?php _e('Close popup when ESC key is pressed', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Auto Focus', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[behavior][auto_focus]" 
                                   value="1" <?php checked($settings['auto_focus'], true); ?>>
                            <?php _e('Automatically focus first input field in popup', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Mobile Behavior', 'master-addons'); ?></th>
                    <td>
                        <select name="settings[behavior][mobile_behavior]">
                            <option value="responsive" <?php selected($settings['mobile_behavior'], 'responsive'); ?>>
                                <?php _e('Responsive', 'master-addons'); ?>
                            </option>
                            <option value="full-screen" <?php selected($settings['mobile_behavior'], 'full-screen'); ?>>
                                <?php _e('Full Screen', 'master-addons'); ?>
                            </option>
                            <option value="disable" <?php selected($settings['mobile_behavior'], 'disable'); ?>>
                                <?php _e('Disable on Mobile', 'master-addons'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    private function render_advanced_settings($settings) {
        ?>
        <div id="advanced" class="ma-tab-pane">
            <h2><?php _e('Advanced Settings', 'master-addons'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Cookie Expiration', 'master-addons'); ?></th>
                    <td>
                        <input type="number" name="settings[advanced][cookie_expiration]" 
                               value="<?php echo esc_attr($settings['cookie_expiration']); ?>" min="1">
                        <span class="description"><?php _e('Days until cookies expire', 'master-addons'); ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Test Mode', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[advanced][test_mode]" 
                                   value="1" <?php checked($settings['test_mode'], true); ?>>
                            <?php _e('Enable test mode (only show popups to specified IPs)', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Test Mode IPs', 'master-addons'); ?></th>
                    <td>
                        <textarea name="settings[advanced][test_mode_ips]" rows="3" class="large-text"><?php 
                            echo esc_textarea($settings['test_mode_ips']); 
                        ?></textarea>
                        <p class="description"><?php _e('Enter IP addresses, one per line', 'master-addons'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Custom CSS', 'master-addons'); ?></th>
                    <td>
                        <textarea name="settings[advanced][custom_css]" rows="5" class="large-text code"><?php 
                            echo esc_textarea($settings['custom_css']); 
                        ?></textarea>
                        <p class="description"><?php _e('Add custom CSS for all popups', 'master-addons'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Custom JavaScript', 'master-addons'); ?></th>
                    <td>
                        <textarea name="settings[advanced][custom_js]" rows="5" class="large-text code"><?php 
                            echo esc_textarea($settings['custom_js']); 
                        ?></textarea>
                        <p class="description"><?php _e('Add custom JavaScript for all popups', 'master-addons'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    private function render_integrations_settings($settings) {
        ?>
        <div id="integrations" class="ma-tab-pane">
            <h2><?php _e('Integration Settings', 'master-addons'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Google Analytics', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[integrations][google_analytics]" 
                                   value="1" <?php checked($settings['google_analytics'], true); ?>>
                            <?php _e('Track popup events in Google Analytics', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('GA Event Name', 'master-addons'); ?></th>
                    <td>
                        <input type="text" name="settings[integrations][google_analytics_event]" 
                               value="<?php echo esc_attr($settings['google_analytics_event']); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Facebook Pixel', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[integrations][facebook_pixel]" 
                                   value="1" <?php checked($settings['facebook_pixel'], true); ?>>
                            <?php _e('Track popup events in Facebook Pixel', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('FB Event Name', 'master-addons'); ?></th>
                    <td>
                        <input type="text" name="settings[integrations][facebook_pixel_event]" 
                               value="<?php echo esc_attr($settings['facebook_pixel_event']); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('MailChimp API Key', 'master-addons'); ?></th>
                    <td>
                        <input type="text" name="settings[integrations][mailchimp_api]" 
                               value="<?php echo esc_attr($settings['mailchimp_api']); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('MailChimp List ID', 'master-addons'); ?></th>
                    <td>
                        <input type="text" name="settings[integrations][mailchimp_list_id]" 
                               value="<?php echo esc_attr($settings['mailchimp_list_id']); ?>" 
                               class="regular-text">
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    private function render_performance_settings($settings) {
        ?>
        <div id="performance" class="ma-tab-pane">
            <h2><?php _e('Performance Settings', 'master-addons'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Lazy Load', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[performance][lazy_load]" 
                                   value="1" <?php checked($settings['lazy_load'], true); ?>>
                            <?php _e('Lazy load popup content', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Preload Fonts', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[performance][preload_fonts]" 
                                   value="1" <?php checked($settings['preload_fonts'], true); ?>>
                            <?php _e('Preload popup fonts for better performance', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Minify CSS', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[performance][minify_css]" 
                                   value="1" <?php checked($settings['minify_css'], true); ?>>
                            <?php _e('Minify popup CSS', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Minify JavaScript', 'master-addons'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="settings[performance][minify_js]" 
                                   value="1" <?php checked($settings['minify_js'], true); ?>>
                            <?php _e('Minify popup JavaScript', 'master-addons'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Cache Duration', 'master-addons'); ?></th>
                    <td>
                        <input type="number" name="settings[performance][cache_duration]" 
                               value="<?php echo esc_attr($settings['cache_duration']); ?>" min="60">
                        <span class="description"><?php _e('Cache duration in seconds', 'master-addons'); ?></span>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    
    private function process_settings_save() {
        $settings = isset($_POST['settings']) ? $_POST['settings'] : [];
        
        // Sanitize settings
        $sanitized_settings = $this->sanitize_settings($settings);
        
        // Save settings
        if ($this->save_settings($sanitized_settings)) {
            add_action('admin_notices', function() {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e('Settings saved successfully!', 'master-addons'); ?></p>
                </div>
                <?php
            });
        } else {
            add_action('admin_notices', function() {
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php _e('Failed to save settings. Please try again.', 'master-addons'); ?></p>
                </div>
                <?php
            });
        }
    }
    
    private function sanitize_settings($settings) {
        $sanitized = [];
        
        foreach ($settings as $section => $values) {
            foreach ($values as $key => $value) {
                if (is_array($value)) {
                    $sanitized[$section][$key] = array_map('sanitize_text_field', $value);
                } elseif (in_array($key, ['custom_css', 'custom_js', 'test_mode_ips'])) {
                    $sanitized[$section][$key] = sanitize_textarea_field($value);
                } else {
                    $sanitized[$section][$key] = sanitize_text_field($value);
                }
            }
        }
        
        return $sanitized;
    }
}