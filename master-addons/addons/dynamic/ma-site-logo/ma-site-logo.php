<?php

namespace MasterAddons\Addons;

// Elementor Classes
use MasterAddons\Inc\Classes\Base\Master_Widget;
use \Elementor\Icons_Manager;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use MasterAddons\Inc\Classes\Helper;
use MasterAddons\Inc\Admin\Config;

/**
 * Author Name: Liton Arefin
 * Author URL : https://master-addons.com
 * Date       : 3/15/2020
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

class Site_Logo extends Master_Widget
{
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

    public function get_name()
    {
        return 'jltma-site-logo';
    }

        public function get_title()
    {
        return esc_html__('Site Logo', 'master-addons' );
    }

    public function get_icon()
    {
        return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
    }

    public function register_controls()
    {
        $this->jltma_site_logo_content_section();
        $this->jltma_site_logo_style_section();
    }

    protected function jltma_site_logo_content_section()
    {
        $this->start_controls_section(
            'logo_section',
            [
                'label' => esc_html__('Site Logo', 'master-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'logo_source',
            [
                'label' => esc_html__('Source', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'custom',
                'options' => [
                    'custom' => esc_html__('Custom Logo', 'master-addons' ),
                    'customizer' => esc_html__('Customizer', 'master-addons' )
                ],
                'frontend_available' => true
            ]
        );

        $this->start_controls_tabs('tabs_thumbnail_style');

        $this->start_controls_tab(
            'tab_desktop',
            [
                'label' => esc_html__('Desktop', 'master-addons' ),
                'condition' => [
                    'logo_source' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'before_image',
            [
                'label' => esc_html__('Logo', 'master-addons' ),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'logo_source' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'img_size',
            [
                'label' => esc_html__('Image Size', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'full',
                'options' => Helper::get_available_image_sizes(),
                'condition' => [
                    'logo_source' => 'custom'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_mobile',
            [
                'label' => esc_html__('Mobile', 'master-addons' ),
                'condition' => [
                    'logo_source' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'after_image',
            [
                'label' => esc_html__('Logo', 'master-addons' ),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'logo_source' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'mobile_img_size',
            [
                'label' => esc_html__('Image Size', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'large',
                'options' => Helper::get_available_image_sizes(),
                'condition' => [
                    'logo_source' => 'custom'
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'logo_hr_1',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => [
                    'logo_source' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'master-addons' ),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'default' => [
                    'url' => home_url('/'),
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'condition' => [
                    'logo_source' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'logo_hr_2',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => [
                    'logo_source' => 'custom'
                ]
            ]
        );

        $this->add_control(
            'breakpoint',
            [
                'label' => esc_html__('Mobile Breakpoint', 'master-addons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => get_option('elementor_viewport_lg', true),
                'condition' => [
                    'logo_source' => 'custom'
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function jltma_site_logo_style_section()
    {
        $this->start_controls_section(
            'section_logo_style',
            [
                'label' => esc_html__('Site Logo', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'logo_h_align',
            [
                'label' => esc_html__('Horizontal Align', 'master-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'master-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'master-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'master-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .jltma-site-logo-container' => 'align-items: {{VALUE}};',
                ],
                'toggle' => false
            ]
        );

        $this->add_responsive_control(
            'logo_max_width',
            [
                'label' => esc_html__('Maximum Width', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'rem'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .jltma-site-logo-container img' => 'max-width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_width',
            [
                'label' => esc_html__('Width', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'rem'],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .jltma-site-logo-container img' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'logo_hr_3',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'logo_bg_color',
            [
                'label' => esc_html__('Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .jltma-site-logo-container img' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'logo_border',
                'label' => esc_html__('Border', 'master-addons' ),
                'selector' => '{{WRAPPER}} .jltma-site-logo-container img'
            ]
        );

        $this->add_control(
            'logo_border_radius',
            [
                'label' => esc_html__('Border Radius', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-site-logo-container img' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'logo_shadow',
                'label' => esc_html__('Box Shadow', 'master-addons' ),
                'selector' => '{{WRAPPER}} .jltma-site-logo-container img'
            ]
        );

        $this->add_control(
            'logo_hr_4',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'logo_padding',
            [
                'label' => esc_html__('Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-site-logo-container img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_margin',
            [
                'label' => esc_html__('Margin', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-site-logo-container img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $target = $settings['link']['is_external'] ? ' target="_blank"' : '';
        $nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
?>
        <div class="jltma-site-logo-container" style="display:flex;flex-direction:column;">
            <?php if ($settings['logo_source'] == 'customizer') {
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    echo '<strong>' . esc_html__('Please add a logo from customizer.', 'master-addons' ) . '</strong>';
                }
            } else {
                if ($settings['link']['url']) {
                    if ($settings['before_image']['url']) {
                        echo '<a href="' . esc_url($settings['link']['url']) . '" class="jltma-logo-desktop"' . esc_attr($target) . ' ' . esc_attr($nofollow) . '><span>' . wp_get_attachment_image($settings['before_image']['id'], $settings['img_size']) . '</span></a>';
                    }
                    if ($settings['after_image']['url'] && $settings['breakpoint']) {
                        echo '<a href="' . esc_url($settings['link']['url']) . '" class="jltma-logo-mobile"' . esc_attr($target) . ' ' . esc_attr($nofollow) . '><span>' . wp_get_attachment_image($settings['after_image']['id'], $settings['mobile_img_size']) . '</span></a>';
                    }
                } else {
                    if ($settings['before_image']['url']) {
                        echo '<div class="jltma-logo-desktop"><span>' . wp_get_attachment_image($settings['before_image']['id'], esc_attr($settings['img_size'])) . '</span></div>';
                    }
                    if ($settings['after_image']['url'] && $settings['breakpoint']) {
                        echo '<div class="jltma-logo-mobile"><span>' . wp_get_attachment_image($settings['after_image']['id'], esc_attr($settings['mobile_img_size'])) . '</span></div>';
                    }
                }
            } ?>
        </div>
        <?php if ($settings['after_image']['url'] && $settings['breakpoint']) { ?>
            <style>
                @media screen and (min-width: <?php echo (esc_attr($settings['breakpoint']) + 1) . 'px'; ?>) {
                    .jltma-logo-desktop span {
                        display: block;
                    }

                    .jltma-logo-mobile span {
                        display: none;
                    }
                }

                @media screen and (max-width: <?php echo esc_attr($settings['breakpoint']) . 'px'; ?>) {
                    .jltma-logo-desktop span {
                        display: none;
                    }

                    .jltma-logo-mobile span {
                        display: block;
                    }
                }
            </style>
        <?php } ?>
<?php }
}
