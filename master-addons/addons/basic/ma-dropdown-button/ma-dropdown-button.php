<?php

namespace MasterAddons\Addons;

use MasterAddons\Inc\Classes\Base\Master_Widget;
use \Elementor\Utils;
use \Elementor\Icons_Manager;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Text_Shadow;

use MasterAddons\Inc\Classes\Helper;
use MasterAddons\Inc\Admin\Config;

class Dropdown_Button extends Master_Widget
{
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

    public function get_name()
    {
        return 'jltma-dropdown-button';
    }

        public function get_title()
    {
        return esc_html__('Dropdown Button', 'master-addons' );
    }

    public function get_icon()
    {
        return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
    }

    public function get_btn_skins()
    {
        $output_skins = apply_filters(
            'jltma-dropdown-btn-skins',
            [
                '' => esc_html__('None', 'master-addons' ),
                'jltma-dropdown-btn-1' => esc_html__('Animation 1', 'master-addons' ),
                'jltma-dropdown-btn-2' => esc_html__('Animation 2', 'master-addons' ),
                'jltma-dropdown-btn-3' => esc_html__('Animation 3', 'master-addons' ),
                'jltma-dropdown-btn-4' => esc_html__('Animation 4', 'master-addons' ),
                'jltma-dropdown-btn-5' => esc_html__('Animation 5', 'master-addons' ),
                'jltma-dropdown-btn-6' => esc_html__('Animation 6', 'master-addons' ),
                'jltma-dropdown-btn-7' => esc_html__('Animation 7', 'master-addons' ),
                'jltma-dropdown-btn-8' => esc_html__('Animation 8', 'master-addons' ),

            ]
        );
        return $output_skins;
    }

    protected function register_controls()
    {
        $this->jltma_dropdown_btn_content_section();
        $this->jltma_dropdown_btn_items_section();
        $this->jltma_dropdown_btn_style_section();
        $this->jltma_dropdown_btn_dropdown_style_section();
    }

    protected function jltma_dropdown_btn_content_section()
    {
        $this->start_controls_section(
            'button_content',
            [
                'label' => esc_html__('Button', 'master-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'text',
            [
                'label' => esc_html__('Button Text', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Click Here', 'master-addons' )
            ]
        );

        $this->add_control(
            'size',
            [
                'label' => esc_html__('Button Size', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'jltma-dropdown-btn-md',
                'options' => [
                    'jltma-dropdown-btn-md' => esc_html__('Normal', 'master-addons' ),
                    'jltma-dropdown-btn-lg' => esc_html__('Large', 'master-addons' ),
                    'jltma-dropdown-btn-sm' => esc_html__('Small', 'master-addons' )
                ],
            ]
        );

        $this->add_responsive_control(
            'h_align',
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
                    '{{WRAPPER}} .jltma-dropdown-wrapper' => 'align-items: {{VALUE}};',
                ],
                'toggle' => false
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => esc_html__('Button Text Align', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'master-addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'master-addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'master-addons' ),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dropdown-btn-wrapper' => 'text-align: {{VALUE}};'
                ],
                'toggle' => true,
            ]
        );

        $this->add_responsive_control(
            'dropdown_align',
            [
                'label' => esc_html__('Dropdown Text Align', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'master-addons' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'master-addons' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'master-addons' ),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu li a' => 'text-align: {{VALUE}};'
                ],
                'toggle' => true,
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => esc_html__('Button Icon', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-caret-down',
                    'library' => 'solid',
                ],
            ]
        );

        $this->add_control(
            'icon_position',
            [
                'label' => esc_html__('Button Icon Position', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'after' => esc_html__('After', 'master-addons' ),
                    'before' => esc_html__('Before', 'master-addons' )
                ],
            ]
        );

        $this->add_control(
            'btn_id',
            [
                'label' => esc_html__('Button ID', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => esc_html__('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows A-Z 0-9 & underscore chars without spaces.', 'master-addons' ),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function jltma_dropdown_btn_items_section()
    {
        $this->start_controls_section(
            'dropdown_content',
            [
                'label' => esc_html__('Dropdown Items', 'master-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'title_icon',
            [
                'label' => esc_html__('Icon', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::ICONS
            ]
        );

        $repeater->add_control(
            'title_icon_position',
            [
                'label' => esc_html__('Icon Position', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'after' => esc_html__('After', 'master-addons' ),
                    'before' => esc_html__('Before', 'master-addons' )
                ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'website_link',
            [
                'label' => esc_html__('Link to', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://www.thememasters.club', 'master-addons' ),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => esc_html__('Menu Items', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'show_label' => false,
                'default' => [
                    [
                        'title_icon' => '',
                        'title_icon_position' => 'before',
                        'title' => esc_html__('Menu Item #1', 'master-addons' ),
                        'website_link' => ''
                    ],
                    [
                        'title_icon' => '',
                        'title_icon_position' => 'before',
                        'title' => esc_html__('Menu Item #2', 'master-addons' ),
                        'website_link' => ''
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function jltma_dropdown_btn_style_section()
    {
        $this->start_controls_section(
            'section_btn_style',
            [
                'label' => esc_html__('Button', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',

                'selector' => '{{WRAPPER}} .jltma-dd-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .jltma-dd-button',
            ]
        );

        $this->add_control(
            'skin',
            [
                'label' => esc_html__('Animation', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->get_btn_skins(),
            ]
        );

        $this->add_control(
            'dropdown_hr_3',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'master-addons' ),
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-button' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'bg_color',
            [
                'label' => esc_html__('Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-button' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'bg_color_gradient',
                'label' => esc_html__('Background', 'master-addons' ),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .jltma-dd-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => esc_html__('Border', 'master-addons' ),
                'selector' => '{{WRAPPER}} .jltma-dd-button'
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-button' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'border_shadow',
                'label' => esc_html__('Box Shadow', 'master-addons' ),
                'selector' => '{{WRAPPER}} .jltma-dd-button'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'master-addons' ),
            ]
        );

        $this->add_control(
            'text_hover_color',
            [
                'label' => esc_html__('Text Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-dropdown:hover .jltma-dd-button' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'bg_hover_color',
            [
                'label' => esc_html__('Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-dropdown:hover .jltma-dd-button' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'bg_color_hover_gradient',
                'label' => esc_html__('Background', 'master-addons' ),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .jltma-dropdown:hover .jltma-dd-button',
            ]
        );

        $this->add_control(
            'animation_color',
            [
                'label' => esc_html__('Animation Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-button:before' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'hover_border',
                'label' => esc_html__('Border', 'master-addons' ),
                'selector' => '{{WRAPPER}} .jltma-dropdown:hover .jltma-dd-button'
            ]
        );

        $this->add_responsive_control(
            'border_hover_radius',
            [
                'label' => esc_html__('Border Radius', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dropdown:hover .jltma-dd-button' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'border_hover_shadow',
                'label' => esc_html__('Box Shadow', 'master-addons' ),
                'selector' => '{{WRAPPER}} .jltma-dropdown:hover .jltma-dd-button'
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'dropdown_hr_4',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__('Icon Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-button i' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => esc_html__('Icon Padding', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-button i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => esc_html__('Icon Margin', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-button i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => esc_html__('Padding', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_width',
            [
                'label' => esc_html__('Button Width', 'master-addons' ),
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
                    '{{WRAPPER}} .jltma-dd-button' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .jltma-dropdown' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function jltma_dropdown_btn_dropdown_style_section()
    {
        $this->start_controls_section(
            'section_dropdown_style',
            [
                'label' => esc_html__('Dropdown', 'master-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_typography',

                'selector' => '{{WRAPPER}} .jltma-dd-menu li a',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'dropdown_text_shadow',
                'selector' => '{{WRAPPER}} .jltma-dd-menu li a',
            ]
        );

        $this->add_responsive_control(
            'dropdown_border_radius',
            [
                'label' => esc_html__('Border Radius', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_border_shadow',
                'label' => esc_html__('Box Shadow', 'master-addons' ),
                'selector' => '{{WRAPPER}} .jltma-dd-menu'
            ]
        );

        $this->add_control(
            'dropdown_hr_1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->start_controls_tabs('tabs_dropdown_style');

        $this->start_controls_tab(
            'tab_dropdown_normal',
            [
                'label' => esc_html__('Normal', 'master-addons' ),
            ]
        );

        $this->add_control(
            'dropdown_text_color',
            [
                'label' => esc_html__('Text Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu li a' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'dropdown_bg_color',
            [
                'label' => esc_html__('Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#eeeeee',
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu li a' => 'background-color: {{VALUE}};',
                ]
            ]
        );

        $this->add_control(
            'seperator_color',
            [
                'label' => esc_html__('Seperator Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu li' => 'border-bottom-color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dropdown_hover',
            [
                'label' => esc_html__('Hover', 'master-addons' ),
            ]
        );

        $this->add_control(
            'dropdown_text_hover_color',
            [
                'label' => esc_html__('Text Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu li a:hover' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'dropdown_bg_hover_color',
            [
                'label' => esc_html__('Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#cccccc',
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu li a:hover' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'seperator_hover_color',
            [
                'label' => esc_html__('Seperator Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu li:hover' => 'border-bottom-color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'dropdown_hr_5',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'dropdown_position',
            [
                'label' => esc_html__('Dropdown Position', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'jltma-dd-menu-bottom',
                'options' => [
                    'jltma-dd-menu-bottom' => esc_html__('Bottom', 'master-addons' ),
                    'jltma-dd-menu-top' => esc_html__('Top', 'master-addons' ),
                    'jltma-dd-menu-right' => esc_html__('Right', 'master-addons' ),
                    'jltma-dd-menu-left' => esc_html__('Left', 'master-addons' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_width',
            [
                'label' => esc_html__('Dropdown Width', 'master-addons' ),
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
                    '{{WRAPPER}} .jltma-dd-menu' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .jltma-dd-menu li' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .jltma-dd-menu li a' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'dropdown_hr_2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_responsive_control(
            'dropdown_icon_spacing',
            [
                'label' => esc_html__('Icon Padding', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu li a i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_padding',
            [
                'label' => esc_html__('Padding', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'dropdown_margin',
            [
                'label' => esc_html__('Margin', 'master-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-dd-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $icon_position = $settings['icon_position'];
?>
        <div class="jltma-dropdown-btn-wrapper jltma-dropdown-wrapper">
            <div class="jltma-dropdown">
                <div tabindex="1" class="jltma-dd-button <?php echo esc_attr($settings['size']); ?> <?php echo esc_attr($settings['skin']); ?>">
                    <?php
                    if ($icon_position == 'before') {
                        \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);
                        echo esc_html($settings['text']);
                    } else {
                        echo esc_html($settings['text']);
                        \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']);
                    } ?>
                </div>
                <?php if ($settings['list']) { ?>
                    <ul class="jltma-dd-menu <?php echo esc_attr($settings['dropdown_position']); ?>">
                        <?php foreach ($settings['list'] as $item) {
                            $target = $item['website_link']['is_external'] ? ' target="_blank"' : '';
                            $nofollow = $item['website_link']['nofollow'] ? ' rel="nofollow"' : '';
                        ?>
                            <li>
                                <a href="<?php echo esc_url($item['website_link']['url']); ?>" <?php echo esc_attr($target); ?> <?php echo esc_attr($nofollow); ?>>
                                    <?php
                                    if ($item['title_icon_position'] == 'before') {
                                        \Elementor\Icons_Manager::render_icon($item['title_icon'], ['aria-hidden' => 'true']);
                                        echo esc_html($item['title']);
                                    } else {
                                        echo esc_html($item['title']);
                                        \Elementor\Icons_Manager::render_icon($item['title_icon'], ['aria-hidden' => 'true']);
                                    } ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
<?php
    }
}
