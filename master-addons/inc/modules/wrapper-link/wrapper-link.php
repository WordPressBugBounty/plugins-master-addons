<?php

namespace MasterAddons\Modules;

use \Elementor\Controls_Manager;
use \Elementor\Element_Base;


/**
 * Author Name: Liton Arefin
 * Author URL: https://jeweltheme.com
 * Date: 2/12/2020
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly.


class JLTMA_Extension_Wrapper_Link
{

    private static $instance = null;

    private function __construct()
    {
        add_action('elementor/element/container/section_layout/after_section_end', [$this, 'jltma_wrapper_link_add_controls_section'], 1);
        add_action('elementor/element/column/section_advanced/after_section_end', [$this, 'jltma_wrapper_link_add_controls_section'], 1);
        add_action('elementor/element/section/section_advanced/after_section_end', [$this, 'jltma_wrapper_link_add_controls_section'], 1);
        add_action('elementor/element/common/_section_style/after_section_end', [$this, 'jltma_wrapper_link_add_controls_section'], 1);

        add_action('elementor/frontend/before_render', [$this, 'widget_before_render_content'], 1);
    }

    public function jltma_wrapper_link_add_controls_section(Element_Base $element)
    {

        $tabs = Controls_Manager::TAB_CONTENT;

        if ('section' === $element->get_name() || 'column' === $element->get_name()  || 'container' === $element->get_name()) {
            $tabs = Controls_Manager::TAB_LAYOUT;
        }

        $element->start_controls_section(
            'jltma_section_wrapper_link',
            [
                'label' => JLTMA_BADGE . esc_html__('Wrapper Link', 'master-addons'),
                'tab'   => $tabs,
            ]
        );

        $element->add_control(
            'jltma_section_element_link',
            [
                'label'       => esc_html__('Link', 'master-addons'),
                'type'        => Controls_Manager::URL,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => 'https://wrapper-link.com',
            ]
        );

        $element->end_controls_section();
    }



    public function widget_before_render_content(Element_Base $element)
    {
        $settings = $element->get_settings_for_display('jltma_section_element_link');
        if (empty($settings['url'])) return;
        $settings['url'] = esc_url($settings['url']);
        $element->add_link_attributes('jltma_wrapper_link', $settings);
        $element->add_render_attribute('jltma_wrapper_link', [
            'class' => 'jltma-wrapper-link',
            'aria-label' => esc_html__('More Details', 'master-addons'),
            'style' => wp_strip_all_tags('position:absolute;width:100%;height:100%;top:0;left:0;z-index:99999;')
        ]);

    ?>
        <a <?php $element->print_render_attribute_string('jltma_wrapper_link'); ?>></a>
    <?php
    }

    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}

JLTMA_Extension_Wrapper_Link::get_instance();
