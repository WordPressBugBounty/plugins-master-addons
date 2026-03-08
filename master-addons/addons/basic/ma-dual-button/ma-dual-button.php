<?php

namespace MasterAddons\Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;
use MasterAddons\Inc\Classes\Helper;
use MasterAddons\Inc\Admin\Config;
use MasterAddons\Inc\Classes\Base\Master_Widget;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Dual_Button extends Master_Widget
{
    use \MasterAddons\Inc\Traits\Widget_Notice;
    use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

    public function get_name()
    {
        return 'ma-dual-button';
    }

    public function get_title()
    {
        return esc_html__('Dual Button', 'master-addons');
    }

    public function get_icon()
    {
        return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
    }

    public function get_keywords()
    {
        return ['dual button', 'button', 'dual', 'cta', 'call to action', 'link'];
    }

    

    protected function register_controls()
    {

        // ------------------------------ //
        // ---------- Button 1 ---------- //
        // ------------------------------ //
        $this->start_controls_section(
            'section_button_1',
            [
                'label' => esc_html__('Button 1', 'master-addons' )
            ]
        );

        $this->add_control(
            'button_1_text',
            [
                'label' => esc_html__('Button 1 Text', 'master-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Button 1 Text', 'master-addons' )
            ]
        );

        $this->add_control(
            'button_1_link',
            [
                'label' => esc_html__('Button 1 Link', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => 'true',
                ]
            ]
        );

        $this->add_control(
            'button_1_icon_status',
            [
                'label' => esc_html__('Button 1 Icon', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_off' => esc_html__('Off', 'master-addons' ),
                'label_on' => esc_html__('On', 'master-addons' ),
                'separator' => 'before'
            ]
        );

        //		$this->add_control(
        //			'button_1_icon',
        //			[
        //				'label' => esc_html__('Choose Icon', 'master-addons' ),
        //				'type' => Controls_Manager::ICON,
        //				'label_block' => true,
        //				'default' => 'fa fa-long-arrow-right',
        //				'condition' => [
        //					'button_1_icon_status' => 'yes'
        //				]
        //			]
        //		);

        $this->add_control(
            'button_1_icon_new',
            [
                'label' => esc_html__('Choose Icon', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'button_1_icon',
                'default' => [
                    'value' => 'fas fa-long-arrow-alt-right',
                    'library' => 'solid'
                ],
                'condition' => [
                    'button_1_icon_status' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'button_1_icon_position',
            [
                'label' => esc_html__('Button 1 Icon Position', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'after' => esc_html__('After Text', 'master-addons' ),
                    'before' => esc_html__('Before Text', 'master-addons' )
                ],
                'condition' => [
                    'button_1_icon_status' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'icon_1_margin',
            [
                'label' => esc_html__('Icon Spacing', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .jltma_icon_position_before .jltma_button_1_icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .jltma_icon_position_after .jltma_button_1_icon' => 'margin-left: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'button_1_icon_status' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        // ------------------------------ //
        // ---------- Button 2 ---------- //
        // ------------------------------ //
        $this->start_controls_section(
            'section_button_2',
            [
                'label' => esc_html__('Button 2', 'master-addons' )
            ]
        );

        $this->add_control(
            'button_2_text',
            [
                'label' => esc_html__('Button 2 Text', 'master-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Button 2 Text', 'master-addons' )
            ]
        );

        $this->add_control(
            'button_2_link',
            [
                'label' => esc_html__('Button 2 Link', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => 'true',
                ]
            ]
        );

        $this->add_control(
            'button_2_icon_status',
            [
                'label' => esc_html__('Button 2 Icon', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_off' => esc_html__('Off', 'master-addons' ),
                'label_on' => esc_html__('On', 'master-addons' ),
                'separator' => 'before'
            ]
        );

        //		$this->add_control(
        //			'button_2_icon',
        //			[
        //				'label' => esc_html__('Choose Icon', 'master-addons' ),
        //				'type' => Controls_Manager::ICON,
        //				'label_block' => true,
        //				'default' => 'fa fa-long-arrow-right',
        //				'condition' => [
        //					'button_2_icon_status' => 'yes'
        //				]
        //			]
        //		);

        $this->add_control(
            'button_2_icon_new',
            [
                'label' => esc_html__('Choose Icon', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'button_2_icon',
                'default' => [
                    'value' => 'fas fa-long-arrow-alt-right',
                    'library' => 'solid'
                ],
                'condition' => [
                    'button_2_icon_status' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'button_2_icon_position',
            [
                'label' => esc_html__('Button 2 Icon Position', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'after' => esc_html__('After Text', 'master-addons' ),
                    'before' => esc_html__('Before Text', 'master-addons' )
                ],
                'condition' => [
                    'button_2_icon_status' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'icon_2_margin',
            [
                'label' => esc_html__('Icon Spacing', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .jltma_icon_position_before .jltma_button_2_icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .jltma_icon_position_after .jltma_button_2_icon' => 'margin-left: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'button_2_icon_status' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        // ----------------------------- //
        // ---------- Divider ---------- //
        // ----------------------------- //
        $this->start_controls_section(
            'section_divider',
            [
                'label' => esc_html__('Divider', 'master-addons' )
            ]
        );

        $this->add_control(
            'divider_type',
            [
                'label' => esc_html__('Divider Type', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'line',
                'options' => [
                    'line' => esc_html__('Line', 'master-addons' ),
                    'text' => esc_html__('Text', 'master-addons' ),
                    'icon' => esc_html__('Icon', 'master-addons' ),
                    'image' => esc_html__('Image', 'master-addons' ),
                    'none' => esc_html__('None', 'master-addons' )
                ]
            ]
        );

        $this->add_control(
            'divider_line_width',
            [
                'label' => esc_html__('Line Width', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .jltma_divider_line' => 'width: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'divider_type' => 'line'
                ]
            ]
        );

        $this->add_control(
            'divider_text',
            [
                'label' => esc_html__('Divider Text', 'master-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Or', 'master-addons' ),
                'condition' => [
                    'divider_type' => 'text'
                ]
            ]
        );

        //		$this->add_control(
        //			'divider_icon',
        //			[
        //				'label' => esc_html__('Divider Icon', 'master-addons' ),
        //				'type' => Controls_Manager::ICON,
        //				'label_block' => true,
        //				'default' => 'fa fa-arrows-h',
        //				'condition' => [
        //					'divider_type' => 'icon'
        //				]
        //			]
        //		);

        $this->add_control(
            'divider_icon_new',
            [
                'label' => esc_html__('Divider Icon', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'divider_icon',
                'default' => [
                    'value' => 'fas fa-arrows-alt-h',
                    'library' => 'solid'
                ],
                'condition' => [
                    'divider_type' => 'icon'
                ]
            ]
        );

        $this->add_control(
            'divider_image',
            [
                'label' => esc_html__('Divider Image', 'master-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'divider_type' => 'image'
                ]
            ]
        );

        $this->add_control(
            'divider_view_type',
            [
                'label' => esc_html__('Divider View Type', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'circle',
                'options' => [
                    'circle' => esc_html__('Circle', 'master-addons' ),
                    'square' => esc_html__('Square', 'master-addons' )
                ],
                'condition' => [
                    'divider_type!' => ['none', 'line']
                ]
            ]
        );

        $this->end_controls_section();

        // -------------------------------------- //
        // ---------- Buttons Settings ---------- //
        // -------------------------------------- //
        $this->start_controls_section(
            'section_button_settings',
            [
                'label' => esc_html__('Buttons Settings', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => esc_html__('Button Typography', 'master-addons' ),
                'selector' => '{{WRAPPER}} .jltma_button'
            ]
        );

        $this->add_control(
            'button_align',
            [
                'label' => esc_html__('Button Alignment', 'master-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => Helper::jltma_content_alignments(),
                'default' => '',
                'prefix_class' => 'jltma_button_align_',
                'selectors' => [
                    '{{WRAPPER}} .jltma_dual_button_container' => 'text-align: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__('Button Border Radius', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .jltma_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_control(
            'button_margin',
            [
                'label' => esc_html__('Button Margin', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .jltma_dual_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        // ------ Button 1 Settings ------ //
        $this->add_control(
            'button_1_settings',
            [
                'label' => esc_html__('Button 1 Settings', 'master-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes'
            ]
        );

        $this->start_popover();

        $this->add_control(
            'button_1_padding',
            [
                'label' => esc_html__('Button 1 Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .jltma_button_1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'button_1_settings' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'button_1_color',
            [
                'label' => esc_html__('Button 1 Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_button_1' => 'color: {{VALUE}};'
                ],
                'condition' => [
                    'button_1_settings' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'button_1_hover',
            [
                'label' => esc_html__('Button 1 Hover', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_button_1:hover' => 'color: {{VALUE}};'
                ],
                'condition' => [
                    'button_1_settings' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'button_1_bg_color',
            [
                'label' => esc_html__('Button 1 Background', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_button_1' => 'background-color: {{VALUE}};'
                ],
                'condition' => [
                    'button_1_settings' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'button_1_bg_hover',
            [
                'label' => esc_html__('Button 1 Background Hover', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_button_1:hover' => 'background-color: {{VALUE}};'
                ],
                'condition' => [
                    'button_1_settings' => 'yes'
                ]
            ]
        );

        $this->end_popover();

        // ------ Button 2 Settings ------ //
        $this->add_control(
            'button_2_settings',
            [
                'label' => esc_html__('Button 2 Settings', 'master-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'return_value' => 'yes'
            ]
        );

        $this->start_popover();

        $this->add_control(
            'button_2_padding',
            [
                'label' => esc_html__('Button 2 Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .jltma_button_2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'button_2_settings' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'button_2_color',
            [
                'label' => esc_html__('Button 2 Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_button_2' => 'color: {{VALUE}};'
                ],
                'condition' => [
                    'button_2_settings' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'button_2_hover',
            [
                'label' => esc_html__('Button 2 Hover', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_button_2:hover' => 'color: {{VALUE}};'
                ],
                'condition' => [
                    'button_2_settings' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'button_2_bg_color',
            [
                'label' => esc_html__('Button 2 Background', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_button_2' => 'background-color: {{VALUE}};'
                ],
                'condition' => [
                    'button_2_settings' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'button_2_bg_hover',
            [
                'label' => esc_html__('Button 2 Background Hover', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_button_2:hover' => 'background-color: {{VALUE}};'
                ],
                'condition' => [
                    'button_2_settings' => 'yes'
                ]
            ]
        );

        $this->end_popover();

        $this->add_control(
            'hover_type',
            [
                'label' => esc_html__('Button Hover Type', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'simple',
                'options' => [
                    'simple' => esc_html__('Simple Hover', 'master-addons' ),
                    'animated' => esc_html__('Animated Hover', 'master-addons' )
                ]
            ]
        );

        $this->end_controls_section();

        // -------------------------------------- //
        // ---------- Divider Settings ---------- //
        // -------------------------------------- //
        $this->start_controls_section(
            'section_divider_settings',
            [
                'label' => esc_html__('Divider Settings', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'divider_type!' => 'none'
                ]
            ]
        );

        $this->add_control(
            'divider_line_color',
            [
                'label' => esc_html__('Line Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_divider_line' => 'background-color: {{VALUE}};'
                ],
                'condition' => [
                    'divider_type' => 'line'
                ]
            ]
        );

        $this->add_control(
            'divider_size',
            [
                'label' => esc_html__('Divider Size', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .jltma_divider' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'divider_type!' => 'line'
                ]
            ]
        );

        $this->add_control(
            'divider_bg_color',
            [
                'label' => esc_html__('Divider Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_divider' => 'background-color: {{VALUE}};'
                ],
                'condition' => [
                    'divider_type!' => 'line'
                ]
            ]
        );

        $this->add_control(
            'divider_border_status',
            [
                'label' => esc_html__('Divider Border', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_off' => esc_html__('Off', 'master-addons' ),
                'label_on' => esc_html__('On', 'master-addons' ),
                'condition' => [
                    'divider_type!' => 'line'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'divider_border',
                'selector' => '{{WRAPPER}} .jltma_divider',
                'condition' => [
                    'divider_type!' => 'line',
                    'divider_border_status' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'divider_text_typography',
                'label' => esc_html__('Divider Text Typography', 'master-addons' ),
                'selector' => '{{WRAPPER}} .jltma_divider',
                'condition' => [
                    'divider_type!' => 'line',
                    'divider_type' => 'text'
                ]
            ]
        );

        $this->add_control(
            'divider_test_color',
            [
                'label' => esc_html__('Divider Text Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_divider_text' => 'color: {{VALUE}};'
                ],
                'condition' => [
                    'divider_type!' => 'line',
                    'divider_type' => 'text'
                ]
            ]
        );

        $this->add_control(
            'divider_icon_size',
            [
                'label' => esc_html__('Icon Size', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .jltma_divider_icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'divider_type!' => 'line',
                    'divider_type' => 'icon'
                ]
            ]
        );

        $this->add_control(
            'divider_icon_color',
            [
                'label' => esc_html__('Icon Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma_divider_icon' => 'color: {{VALUE}};'
                ],
                'condition' => [
                    'divider_type!' => 'line',
                    'divider_type' => 'icon'
                ]
            ]
        );

        $this->end_controls_section();

        // Upgrade to Pro
        $this->upgrade_to_pro_message();
    }

    protected function render()
    {
        $settings = $this->get_settings();

        $button_1_text = $settings['button_1_text'];
        $button_1_link = $settings['button_1_link'];
        $button_1_icon_status = $settings['button_1_icon_status'];
        $button_1_icon_position = $settings['button_1_icon_position'];

        $button_2_text = $settings['button_2_text'];
        $button_2_link = $settings['button_2_link'];
        $button_2_icon_status = $settings['button_2_icon_status'];
        $button_2_icon_position = $settings['button_2_icon_position'];

        $divider_type = $settings['divider_type'];
        $divider_text = $settings['divider_text'];
        $divider_image = $settings['divider_image'];
        $divider_view_type = $settings['divider_view_type'];

        $hover_type = $settings['hover_type'];

        $button_id = 'jltma_button_' . $this->get_id() . '';

        $migrated_1 = isset($settings['__fa4_migrated']['button_1_icon_new']);
        $is_new_1 = empty($settings['button_1_icon']);

        if ($is_new_1 || $migrated_1) {
            $button_1_icon = $settings['button_1_icon_new']['value'];
        } else {
            $button_1_icon = $settings['button_1_icon'];
        }

        $migrated_2 = isset($settings['__fa4_migrated']['button_2_icon_new']);
        $is_new_2 = empty($settings['button_2_icon']);

        if ($is_new_2 || $migrated_2) {
            $button_2_icon = $settings['button_2_icon_new']['value'];
        } else {
            $button_2_icon = $settings['button_2_icon'];
        }

        $migrated_divider = isset($settings['__fa4_migrated']['divider_icon_new']);
        $is_new_divider = empty($settings['divider_icon']);

        if ($is_new_divider || $migrated_divider) {
            $divider_icon = $settings['divider_icon_new']['value'];
        } else {
            $divider_icon = $settings['divider_icon'];
        }

        // ------------------------------------ //
        // ---------- Widget Content ---------- //
        // ------------------------------------ //
?>

        <div class="jltma_dual_button_container <?php echo esc_attr($button_id); ?>">
            <div class="jltma_dual_button hover_type_<?php echo esc_attr($hover_type); ?>">
                <a class="jltma_button jltma_button_1" href="<?php echo esc_url($button_1_link['url']); ?>" <?php echo (($button_1_link['is_external'] == true) ? 'target="_blank"' : '');
                                                                                                            echo (($button_1_link['nofollow'] == 'on') ? 'rel="nofollow"' : ''); ?>>
                    <?php
                    if ($button_1_icon_status == 'yes' && $button_1_icon_position == 'before') {
                    ?>
                        <i class="jltma_button_icon <?php echo esc_attr($button_1_icon); ?>"></i>
                    <?php
                    }

                    echo esc_html($button_1_text);

                    if ($button_1_icon_status == 'yes' && $button_1_icon_position == 'after') {
                    ?>
                        <i class="jltma_button_icon <?php echo esc_attr($button_1_icon); ?>"></i>
                    <?php
                    }
                    ?>
                </a>

                <?php
                if ($divider_type !== 'none') {
                    if ($divider_type == 'line') {
                ?>
                        <div class="jltma_divider_line"></div>
                    <?php
                    } else {
                    ?>
                        <div class="jltma_divider jltma_divider_<?php echo esc_attr($divider_type); ?> jltma_view_type_<?php echo esc_attr($divider_view_type); ?>">
                            <?php
                            if ($divider_type == 'text') {
                                echo esc_html($divider_text);
                            }

                            if ($divider_type == 'icon') {
                            ?>
                                <i class="<?php echo esc_attr($divider_icon); ?>"></i>
                            <?php
                            }

                            if ($divider_type == 'image') {
                                $image_alt = '';
                                if (!empty($divider_image['id'])) {
                                    $image_alt = get_post_meta($divider_image['id'], '_wp_attachment_image_alt', true);
                                }
                                $image_url = !empty($divider_image['url']) ? $divider_image['url'] : '';
                            ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
                            <?php
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                ?>

                <a class="jltma_button jltma_button_2" href="<?php echo esc_url($button_2_link['url']); ?>" <?php echo (($button_2_link['is_external'] == true) ? 'target="_blank"' : '');
                                                                                                            echo (($button_2_link['nofollow'] == 'on') ? 'rel="nofollow"' : ''); ?>>
                    <?php
                    if ($button_2_icon_status == 'yes' && $button_2_icon_position == 'before') {
                    ?>
                        <i class="jltma_button_icon <?php echo esc_attr($button_2_icon); ?>"></i>
                    <?php
                    }

                    echo esc_html($button_2_text);

                    if ($button_2_icon_status == 'yes' && $button_2_icon_position == 'after') {
                    ?>
                        <i class="jltma_button_icon <?php echo esc_attr($button_2_icon); ?>"></i>
                    <?php
                    }
                    ?>
                </a>
            </div>
        </div>
<?php
    }

    protected function content_template()
    {
    }

    public function render_plain_content()
    {
    }
}
