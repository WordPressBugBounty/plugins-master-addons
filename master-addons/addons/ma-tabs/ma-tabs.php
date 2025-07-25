<?php

namespace MasterAddons\Addons;

use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;
use MasterAddons\Inc\Helper\Master_Addons_Helper;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// If this file is called directly, abort.
class JLTMA_Tabs extends Widget_Base {
    use \MasterAddons\Inc\Traits\Widget_Notice;
    public function get_name() {
        return 'ma-tabs';
    }

    public function get_title() {
        return esc_html__( 'Advanced Tabs', 'master-addons' );
    }

    public function get_icon() {
        return 'jltma--icon eicon-tabs';
    }

    public function get_categories() {
        return ['master-addons'];
    }

    public function get_style_depends() {
        return ['jltma-bootstrap', 'font-awesome-5-all', 'font-awesome-4-shim'];
    }

    public function get_keywords() {
        return [
            'tab',
            'hover tabs',
            'click tabs',
            'horizontal tab',
            'vertical tabs',
            'columns',
            'tabbed',
            'panel',
            'tabular content',
            'left right',
            'left right content',
            'push content'
        ];
    }

    public function get_help_url() {
        return 'https://master-addons.com/demos/tabs/';
    }

    protected function is_dynamic_content() : bool {
        return false;
    }

    protected function register_controls() {
        /**
         * -------------------------------------------
         * Tab Style MA Tabs Generel Style
         * -------------------------------------------
         */
        $this->start_controls_section( 'ma_el_section_tabs_style_preset_settings', [
            'label' => esc_html__( 'Presets', 'master-addons' ),
        ] );
        $this->add_control( 'ma_el_tabs_preset', [
            'label'       => esc_html__( 'Style Preset', 'master-addons' ),
            'type'        => Controls_Manager::SELECT,
            'default'     => 'two',
            'label_block' => false,
            'options'     => [
                'two'            => esc_html__( 'Horizontal Tabs', 'master-addons' ),
                'three'          => esc_html__( 'Vertical Tabs', 'master-addons' ),
                'four'           => esc_html__( 'Left Active Border', 'master-addons' ),
                'ma_tabular_pro' => esc_html__( 'Tabular Content (Pro)', 'master-addons' ),
            ],
        ] );
        $this->add_control( 'ma_el_tabs_icon_show', [
            'label'        => esc_html__( 'Enable Icon', 'master-addons' ),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ] );
        $this->add_responsive_control( 'ma_el_tabs_left_cols_pro_only', [
            'label'       => esc_html__( 'Column Layout', 'master-addons' ),
            'type'        => Controls_Manager::CHOOSE,
            'options'     => [
                '1' => [
                    'title' => esc_html__( '', 'master-addons' ),
                    'icon'  => 'fa fa-unlock-alt',
                ],
            ],
            'default'     => '1',
            'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> unlock this Option.</span>',
        ] );
        $this->add_responsive_control( 'ma_el_tabs_icons_style_pro_only', [
            'label'       => esc_html__( 'Icon & Tabs Style', 'master-addons' ),
            'type'        => Controls_Manager::CHOOSE,
            'options'     => [
                '1' => [
                    'title' => esc_html__( '', 'master-addons' ),
                    'icon'  => 'fa fa-unlock-alt',
                ],
            ],
            'default'     => '1',
            'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> unlock this Option.</span>',
        ] );
        $this->add_responsive_control( 'ma_el_tabs_content_style_pro_only', [
            'label'       => esc_html__( 'Tabs & Content Style', 'master-addons' ),
            'type'        => Controls_Manager::CHOOSE,
            'options'     => [
                '1' => [
                    'title' => esc_html__( '', 'master-addons' ),
                    'icon'  => 'fa fa-unlock-alt',
                ],
            ],
            'default'     => '1',
            'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> unlock this Option.</span>',
        ] );
        $this->add_responsive_control( 'ma_el_tabular_tabs_style_pro_only', [
            'label'       => esc_html__( 'Tabs Orientation', 'master-addons' ),
            'type'        => Controls_Manager::CHOOSE,
            'options'     => [
                '1' => [
                    'title' => esc_html__( '', 'master-addons' ),
                    'icon'  => 'fa fa-unlock-alt',
                ],
            ],
            'default'     => '1',
            'description' => '<span class="pro-feature"> Upgrade to  <a href="' . ma_el_fs()->get_upgrade_url() . '" target="_blank">Pro Version</a> unlock this Option.</span>',
        ] );
        $this->end_controls_section();
        /**
         * MA Tabs Content Settings
         */
        $this->start_controls_section( 'ma_el_section_tabs_content_settings', [
            'label'     => esc_html__( 'Content', 'master-addons' ),
            'seperator' => 'before',
        ] );
        $repeater = new Repeater();
        $repeater->add_control( 'ma_el_tab_show_as_default', [
            'label'        => __( 'Set as Default', 'master-addons' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'active',
        ] );
        $repeater->add_control( 'ma_el_tabs_icon_type', [
            'label'       => esc_html__( 'Icon Type', 'master-addons' ),
            'type'        => Controls_Manager::CHOOSE,
            'label_block' => false,
            'options'     => [
                'none'  => [
                    'title' => esc_html__( 'None', 'master-addons' ),
                    'icon'  => 'fa fa-ban',
                ],
                'icon'  => [
                    'title' => esc_html__( 'Icon', 'master-addons' ),
                    'icon'  => 'eicon-icon-box',
                ],
                'image' => [
                    'title' => esc_html__( 'Image', 'master-addons' ),
                    'icon'  => 'eicon-image',
                ],
            ],
            'default'     => 'icon',
        ] );
        $repeater->add_control( 'ma_el_tab_title_icon', [
            'label'            => esc_html__( 'Icon', 'master-addons' ),
            'description'      => esc_html__( 'Please choose an icon from the list.', 'master-addons' ),
            'type'             => Controls_Manager::ICONS,
            'fa4compatibility' => 'icon',
            'default'          => [
                'value'   => 'fas fa-home',
                'library' => 'solid',
            ],
            'render_type'      => 'template',
            'condition'        => [
                'ma_el_tabs_icon_type' => 'icon',
            ],
        ] );
        $repeater->add_control( 'ma_el_tab_title_image', [
            'label'     => esc_html__( 'Image', 'master-addons' ),
            'type'      => Controls_Manager::MEDIA,
            'default'   => [
                'url' => Utils::get_placeholder_image_src(),
            ],
            'condition' => [
                'ma_el_tabs_icon_type' => 'image',
            ],
        ] );
        $repeater->add_control( 'ma_el_tab_title', [
            'label'   => esc_html__( 'Tab Title', 'master-addons' ),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__( 'Tab Title', 'master-addons' ),
            'dynamic' => [
                'active' => true,
            ],
        ] );
        $repeater->add_control( 'ma_el_tab_content', [
            'label'   => esc_html__( 'Tab Content', 'master-addons' ),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'master-addons' ),
        ] );
        $this->add_control( 'ma_el_tabs', [
            'type'        => Controls_Manager::REPEATER,
            'default'     => [[
                'ma_el_tab_title' => esc_html__( 'Tab Title One', 'master-addons' ),
            ], [
                'ma_el_tab_title' => esc_html__( 'Tab Title Two', 'master-addons' ),
            ], [
                'ma_el_tab_title' => esc_html__( 'Tab Title Three', 'master-addons' ),
            ]],
            'fields'      => $repeater->get_controls(),
            'title_field' => '{{ma_el_tab_title}}',
        ] );
        $this->add_control( 'ma_el_tabs_effect', [
            'label'       => esc_html__( 'Tab Effect', 'master-addons' ),
            'type'        => Controls_Manager::SELECT,
            'label_block' => false,
            'options'     => [
                'hover' => esc_html__( 'Hover', 'master-addons' ),
                'click' => esc_html__( 'Click', 'master-addons' ),
            ],
            'default'     => 'hover',
        ] );
        $this->end_controls_section();
        /**
         * -------------------------------------------
         * Tab Style MA Tabs Heading Style
         * -------------------------------------------
         */
        $this->start_controls_section( 'ma_el_section_tabs_heading_style_settings', [
            'label' => esc_html__( 'Tabs', 'master-addons' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'ma_el_tab_heading_typography',
            'selector' => '{{WRAPPER}} .jltma--advance-tab .jltma--tab-title',
        ] );
        $this->start_controls_tabs( 'ma_el_tabs_header_tabs' );
        // Normal State Tab
        $this->start_controls_tab( 'ma_el_tabs_header_normal', [
            'label' => esc_html__( 'Normal', 'master-addons' ),
        ] );
        $this->add_control( 'ma_el_tab_text_color', [
            'label'     => esc_html__( 'Text Color', 'master-addons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#8a8d91',
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li span, {{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li i' => 'color: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'ma_el_tab_bg_color', [
            'label'     => esc_html__( 'Background Color', 'master-addons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#FFF',
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li' => 'background: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'ma_el_tab_border_color', [
            'label'     => esc_html__( 'Bottom Border Color', 'master-addons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e5e5e5',
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab.two .jltma--advance-tab-nav li' => 'border-bottom: 1px solid {{VALUE}};',
            ],
            'condition' => [
                'ma_el_tabs_preset' => 'two',
            ],
        ] );
        $this->add_control( 'ma_el_tabs_heading_padding', [
            'label'      => esc_html__( 'Padding', 'master-addons' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );
        $this->add_control( 'ma_el_tabs_heading_margin', [
            'label'      => esc_html__( 'Margin', 'master-addons' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'ma_el_tabs_heading_box_shadow',
            'selector' => '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li',
        ] );
        $this->add_control( 'ma_el_tabs_icon_size', [
            'label'          => esc_html__( 'Icon Size (px)', 'master-addons' ),
            'type'           => Controls_Manager::SLIDER,
            'size_units'     => ['px'],
            'range'          => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'default'        => [
                'unit' => 'px',
                'size' => 18,
            ],
            'selectors'      => array(
                '.jltma--advance-tab .jltma--advance-tab-nav li i'   => 'font-size:{{SIZE}}{{UNIT}} !important;',
                '.jltma--advance-tab .jltma--advance-tab-nav li svg' => 'width:{{SIZE}}{{UNIT}} !important;',
            ),
            'style_transfer' => true,
        ] );
        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'      => 'ma_el_tabs_heading_box_border',
            'separator' => 'before',
            'selector'  => '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li',
        ] );
        $this->add_control( 'ma_el_tabs_heading_border_radius', [
            'label'      => __( 'Border Radius', 'master-addons' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );
        $this->end_controls_tab();
        // Active State Tab
        $this->start_controls_tab( 'ma_el_tabs_header_active', [
            'label' => esc_html__( 'Active', 'master-addons' ),
        ] );
        $this->add_control( 'ma_el_tab_text_color_active', [
            'label'     => esc_html__( 'Text Color', 'master-addons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0a1724',
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li.active span, {{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li.active i' => 'color: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'ma_el_tab_bg_color_active', [
            'label'     => esc_html__( 'Background Color', 'master-addons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f9f9f9',
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li.active, {{WRAPPER}} .jltma--advance-tab.four .jltma--advance-tab-nav li::before' => 'background: {{VALUE}};',
                '{{WRAPPER}} .jltma--advance-tab.three .jltma--advance-tab-nav li::before'                                                                   => 'border-left-color: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'ma_el_tab_border_color_active', [
            'label'     => esc_html__( 'Bottom Border Color', 'master-addons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#704aff',
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab.two .jltma--advance-tab-nav li.active'  => 'border-bottom: 1px solid {{VALUE}};',
                '{{WRAPPER}} .jltma--advance-tab.four .jltma--advance-tab-nav li::after' => 'background: {{VALUE}};',
            ],
            'condition' => [
                'ma_el_tabs_preset' => 'two',
            ],
        ] );
        $this->add_control( 'ma_el_tab_border_left_color_active', [
            'label'     => esc_html__( 'Bottom Left Color', 'master-addons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#704aff',
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab.four .jltma--advance-tab-nav li::after' => 'background: {{VALUE}};',
            ],
            'condition' => [
                'ma_el_tabs_preset' => 'four',
            ],
        ] );
        $this->add_control( 'ma_el_tabs_heading_active_padding', [
            'label'      => esc_html__( 'Padding', 'master-addons' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li.active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );
        $this->add_control( 'ma_el_tabs_heading_active_margin', [
            'label'      => esc_html__( 'Margin', 'master-addons' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'selectors'  => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li.active' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'ma_el_tabs_heading_active_box_shadow',
            'selector' => '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li.active',
        ] );
        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'      => 'ma_el_tabs_heading_active_box_border',
            'separator' => 'before',
            'selector'  => '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li.active',
        ] );
        $this->add_control( 'ma_el_tabs_heading_active_border_radius', [
            'label'      => __( 'Border Radius', 'master-addons' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-nav li.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /**
         * -------------------------------------------
         * Tab Style MA Tabs Content Style
         * -------------------------------------------
         */
        $this->start_controls_section( 'ma_el_section_tabs_tab_content_style_settings', [
            'label' => esc_html__( 'Content', 'master-addons' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'exclusive_tabs_content_title_color', [
            'label'     => esc_html__( 'Title Color', 'master-addons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0a1724',
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content .jltma--advance-tab-content-title' => 'color: {{VALUE}};',
            ],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'ma_el_tabs_content_title_typography',
            'label'    => esc_html__( 'Title Typography', 'master-addons' ),
            'selector' => '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content-title,
					{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content h1,
					{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content h2,
					{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content h3,
					{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content h4,
					{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content h5,
					{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content h6',
        ] );
        $this->add_control( 'exclusive_tabs_content_bg_color', [
            'label'     => esc_html__( 'Background Color', 'master-addons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f9f9f9',
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content ' => 'background-color: {{VALUE}};',
            ],
        ] );
        $this->add_control( 'exclusive_tabs_content_text_color', [
            'label'     => esc_html__( 'Content Color', 'master-addons' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#333',
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content ' => 'color: {{VALUE}};',
            ],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'ma_el_tabs_content_typography',
            'label'    => esc_html__( 'Content Typography', 'master-addons' ),
            'selector' => '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content',
        ] );
        $this->add_control( 'jltma_content_alignment', [
            'label'     => esc_html__( 'Content Alignment', 'master-addons' ),
            'type'      => Controls_Manager::CHOOSE,
            'default'   => 'flex-start',
            'options'   => [
                'flex-start' => [
                    'title' => esc_html__( 'Top', 'master-addons' ),
                    'icon'  => 'eicon-v-align-top',
                ],
                'center'     => [
                    'title' => esc_html__( 'Center', 'master-addons' ),
                    'icon'  => 'eicon-v-align-middle',
                ],
                'flex-end'   => [
                    'title' => esc_html__( 'Bottom', 'master-addons' ),
                    'icon'  => 'eicon-v-align-bottom',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content' => 'flex-direction: column; justify-content: {{VALUE}};',
            ],
            "condition" => [
                'ma_el_tabs_preset!' => 'two',
            ],
        ] );
        $this->add_responsive_control( 'ma_el_tabs_content_border_radius', array(
            'label'      => esc_html__( 'Border Radius', 'master-addons' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array('px', '%'),
            'selectors'  => array(
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        ) );
        $this->add_group_control( Group_Control_Box_Shadow::get_type(), array(
            'name'     => 'ma_el_tabs_content_box_shadow',
            'exclude'  => array('box_shadow_position'),
            'selector' => '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content',
        ) );
        $this->add_responsive_control( 'ma_el_tabs_content_padding', [
            'label'      => esc_html__( 'Padding', 'master-addons' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default'    => [
                'top'      => 40,
                'right'    => 40,
                'bottom'   => 40,
                'left'     => 40,
                'isLinked' => true,
            ],
            'selectors'  => [
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ] );
        $this->add_responsive_control( 'ma_el_tabs_content_margin', array(
            'label'      => esc_html__( 'Margin', 'master-addons' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => array('px', '%'),
            'selectors'  => array(
                '{{WRAPPER}} .jltma--advance-tab .jltma--advance-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ),
        ) );
        $this->end_controls_section();
        /**
         * Content Tab: Docs Links
         */
        $this->start_controls_section( 'jltma_section_help_docs', [
            'label' => esc_html__( 'Help Docs', 'master-addons' ),
        ] );
        $this->add_control( 'help_doc_1', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => sprintf( esc_html__( '%1$s Live Demo %2$s', 'master-addons' ), '<a href="https://master-addons.com/demos/tabs/" target="_blank" rel="noopener">', '</a>' ),
            'content_classes' => 'jltma-editor-doc-links',
        ] );
        $this->add_control( 'help_doc_2', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => sprintf( esc_html__( '%1$s Documentation %2$s', 'master-addons' ), '<a href="https://master-addons.com/docs/addons/tabs-element/?utm_source=widget&utm_medium=panel&utm_campaign=dashboard" target="_blank" rel="noopener">', '</a>' ),
            'content_classes' => 'jltma-editor-doc-links',
        ] );
        $this->add_control( 'help_doc_3', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => sprintf( esc_html__( '%1$s Watch Video Tutorial %2$s', 'master-addons' ), '<a href="https://www.youtube.com/watch?v=lsqGmIrdahw" target="_blank" rel="noopener">', '</a>' ),
            'content_classes' => 'jltma-editor-doc-links',
        ] );
        $this->end_controls_section();
        $this->upgrade_to_pro_message();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $column_order = ( isset( $settings['ma_el_tabs_left_cols'] ) ? 'jltma-row' : '' );
        $this->add_render_attribute( 'ma_el_tab_wrapper', [
            'id'              => "jltma--advance-tabs-{$this->get_id()}",
            'class'           => ['jltma--advance-tab', $settings['ma_el_tabs_preset'], $column_order],
            'data-tab-effect' => $settings['ma_el_tabs_effect'],
        ] );
        if ( isset( $settings['ma_el_tabs_left_cols'] ) ) {
            $ma_el_tabs_left_cols = explode( '-', $settings['ma_el_tabs_left_cols'] );
        }
        $column_order = ( isset( $settings['ma_el_tabs_content_style'] ) ? $settings['ma_el_tabs_content_style'] : "" );
        ?>


		<div <?php 
        echo $this->get_render_attribute_string( 'ma_el_tab_wrapper' );
        ?> data-tabs>

			<?php 
        if ( isset( $settings['ma_el_tabs_preset'] ) && $settings['ma_el_tabs_preset'] == "five" ) {
            ?>
				<div class="jltma-col-<?php 
            echo esc_attr( $ma_el_tabs_left_cols[0] );
            ?> <?php 
            if ( $column_order == "float-left" ) {
                echo "jltma-order-1";
            } elseif ( $settings['ma_el_tabs_left_cols'] == "12-12" ) {
                # code...
            } else {
                echo "jltma-order-2";
            }
            ?>">
				<?php 
        }
        ?>

				<ul class="jltma--advance-tab-nav">
					<?php 
        foreach ( $settings['ma_el_tabs'] as $key => $tab ) {
            ?>
						<li class="<?php 
            echo esc_attr( $tab['ma_el_tab_show_as_default'] );
            ?>" data-tab data-tab-id="jltma-tab-<?php 
            echo esc_attr( $this->get_id() . $key );
            ?>">
							<?php 
            if ( $settings['ma_el_tabs_icon_show'] === 'yes' ) {
                if ( $tab['ma_el_tabs_icon_type'] === 'icon' ) {
                    $migrated = isset( $tab['__fa4_migrated']['ma_el_tab_title_icon'] );
                    $is_new = empty( $tab['icon'] ) && Icons_Manager::is_migration_allowed();
                    if ( $is_new || $migrated ) {
                        Icons_Manager::render_icon( $tab['ma_el_tab_title_icon'], [
                            'aria-hidden' => 'true',
                        ] );
                    } else {
                        ?>
                                        <i class="<?php 
                        echo esc_attr( $tab['icon'] );
                        ?>" aria-hidden="true"></i>
                                    <?php 
                    }
                    ?>
								<?php 
                } elseif ( $tab['ma_el_tabs_icon_type'] === 'image' ) {
                    ?>
									<img src="<?php 
                    echo esc_attr( $tab['ma_el_tab_title_image']['url'] );
                    ?>">
							<?php 
                }
            }
            ?>
							<span class="jltma--tab-title">
								<?php 
            echo $this->parse_text_editor( $tab['ma_el_tab_title'] );
            ?>
							</span>
						</li>
					<?php 
        }
        ?>
				</ul>

				<?php 
        if ( $settings['ma_el_tabs_preset'] == "five" ) {
            ?>
				</div>

				<div class="jltma-col-<?php 
            echo esc_attr( $ma_el_tabs_left_cols[1] );
            ?> <?php 
            if ( $column_order == "float-left" ) {
                echo "jltma-order-2";
            } else {
                echo "jltma-order-1";
            }
            ?>">
				<?php 
        }
        ?>

				<div class="tab-content">
					<?php 
        foreach ( $settings['ma_el_tabs'] as $key => $tab ) {
            $ma_el_find_default_tab[] = $tab['ma_el_tab_show_as_default'];
            ?>
						<div id="jltma-tab-<?php 
            echo esc_attr( $this->get_id() . $key );
            ?>" class="jltma--advance-tab-content tab-pane <?php 
            echo esc_attr( $tab['ma_el_tab_show_as_default'] );
            ?>">
							<?php 
            echo do_shortcode( $tab['ma_el_tab_content'] );
            ?>
						</div><!-- jltma--advance-tab-content -->
					<?php 
        }
        ?>
				</div> <!-- tab-content -->


				<?php 
        if ( $settings['ma_el_tabs_preset'] == "five" ) {
            ?>
				</div> <!-- col-5 -->
			<?php 
        }
        ?>

		</div>
<?php 
    }

    public function get_page_template_options( $type = '' ) {
        $page_templates = Master_Addons_Helper::ma_get_page_templates( $type );
        $options[-1] = esc_html__( 'Select', 'master-addons' );
        if ( count( $page_templates ) ) {
            foreach ( $page_templates as $id => $name ) {
                $options[$id] = $name;
            }
        } else {
            $options['no_template'] = esc_html__( 'No saved templates found!', 'master-addons' );
        }
        return $options;
    }

}
