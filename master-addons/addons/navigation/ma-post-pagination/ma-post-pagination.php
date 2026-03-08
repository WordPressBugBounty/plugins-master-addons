<?php

namespace MasterAddons\Addons;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

use MasterAddons\Inc\Admin\Config;
use MasterAddons\Inc\Classes\Base\Master_Widget;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Post Pagination Widget
 *
 * Elementor widget that displays post pagination.
 *
 * @since 2.1.0
 */
class Post_Pagination extends Master_Widget
{
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

    /**
     * Get widget name.
     *
     * @since 2.1.0
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'ma-post-pagination';
    }

        /**
     * Get widget title.
     *
     * @since 2.1.0
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Post Pagination', 'master-addons');
    }

    /**
     * Get widget icon.
     *
     * @since 2.1.0
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
    }

    /**
     * Get widget keywords.
     *
     * @since 2.1.0
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return ['pagination', 'post', 'navigation', 'pager', 'pages'];
    }

    /**
     * Register widget controls.
     *
     * @since 2.1.0
     */
    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'section_pagination',
            [
                'label' => __('Pagination', 'master-addons'),
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label' => __('Type', 'master-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'numbers',
                'options' => [
                    'numbers' => __('Numbers', 'master-addons'),
                    'prev_next' => __('Previous/Next', 'master-addons'),
                    'numbers_and_prev_next' => __('Numbers + Previous/Next', 'master-addons'),
                ],
            ]
        );

        $this->add_control(
            'prev_label',
            [
                'label' => __('Previous Label', 'master-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('&laquo; Previous', 'master-addons'),
                'condition' => [
                    'pagination_type' => ['prev_next', 'numbers_and_prev_next'],
                ],
            ]
        );

        $this->add_control(
            'next_label',
            [
                'label' => __('Next Label', 'master-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Next &raquo;', 'master-addons'),
                'condition' => [
                    'pagination_type' => ['prev_next', 'numbers_and_prev_next'],
                ],
            ]
        );

        $this->add_control(
            'page_limit',
            [
                'label' => __('Page Limit', 'master-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'condition' => [
                    'pagination_type' => ['numbers', 'numbers_and_prev_next'],
                ],
            ]
        );

        $this->add_control(
            'show_first_last',
            [
                'label' => __('First/Last Buttons', 'master-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
                'condition' => [
                    'pagination_type' => ['numbers', 'numbers_and_prev_next'],
                ],
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => __('Alignment', 'master-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'master-addons'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'master-addons'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'master-addons'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .jltma-pagination' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'section_pagination_style',
            [
                'label' => __('Pagination', 'master-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'selector' => '{{WRAPPER}} .jltma-pagination a, {{WRAPPER}} .jltma-pagination span',
            ]
        );

        $this->add_responsive_control(
            'pagination_spacing',
            [
                'label' => __('Space Between', 'master-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .jltma-pagination' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_padding',
            [
                'label' => __('Padding', 'master-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-pagination a, {{WRAPPER}} .jltma-pagination span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('pagination_colors');

        $this->start_controls_tab(
            'pagination_normal',
            [
                'label' => __('Normal', 'master-addons'),
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => __('Color', 'master-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-pagination a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color',
            [
                'label' => __('Background Color', 'master-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-pagination a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pagination_hover',
            [
                'label' => __('Hover', 'master-addons'),
            ]
        );

        $this->add_control(
            'pagination_hover_color',
            [
                'label' => __('Color', 'master-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-pagination a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_hover_bg_color',
            [
                'label' => __('Background Color', 'master-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-pagination a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pagination_active',
            [
                'label' => __('Active', 'master-addons'),
            ]
        );

        $this->add_control(
            'pagination_active_color',
            [
                'label' => __('Color', 'master-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-pagination .current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_bg_color',
            [
                'label' => __('Background Color', 'master-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-pagination .current' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pagination_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .jltma-pagination a, {{WRAPPER}} .jltma-pagination span',
            ]
        );

        $this->add_control(
            'pagination_border_radius',
            [
                'label' => __('Border Radius', 'master-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-pagination a, {{WRAPPER}} .jltma-pagination span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pagination_box_shadow',
                'selector' => '{{WRAPPER}} .jltma-pagination a, {{WRAPPER}} .jltma-pagination span',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 2.1.0
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        global $wp_query;

        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $max_pages = $wp_query->max_num_pages;

        if ($max_pages < 2) {
            return;
        }

        $pagination_type = $settings['pagination_type'];
        $prev_label = $settings['prev_label'];
        $next_label = $settings['next_label'];
        $page_limit = !empty($settings['page_limit']) ? $settings['page_limit'] : 5;
        $show_first_last = $settings['show_first_last'] === 'yes';

        echo '<nav class="jltma-pagination" role="navigation" aria-label="' . esc_attr__('Pagination', 'master-addons') . '">';

        if ($pagination_type === 'prev_next') {
            // Previous/Next only
            if ($paged > 1) {
                echo '<a class="prev page-numbers" href="' . esc_url(get_pagenum_link($paged - 1)) . '">' . wp_kses_post($prev_label) . '</a>';
            }
            if ($paged < $max_pages) {
                echo '<a class="next page-numbers" href="' . esc_url(get_pagenum_link($paged + 1)) . '">' . wp_kses_post($next_label) . '</a>';
            }
        } else {
            // Numbers or Numbers + Prev/Next
            $args = [
                'total' => $max_pages,
                'current' => $paged,
                'show_all' => false,
                'end_size' => 1,
                'mid_size' => floor($page_limit / 2),
                'prev_next' => ($pagination_type === 'numbers_and_prev_next'),
                'prev_text' => $prev_label,
                'next_text' => $next_label,
                'type' => 'plain',
            ];

            if ($show_first_last && $paged > 2) {
                echo '<a class="first page-numbers" href="' . esc_url(get_pagenum_link(1)) . '">' . esc_html__('First', 'master-addons') . '</a>';
            }

            echo paginate_links($args);

            if ($show_first_last && $paged < ($max_pages - 1)) {
                echo '<a class="last page-numbers" href="' . esc_url(get_pagenum_link($max_pages)) . '">' . esc_html__('Last', 'master-addons') . '</a>';
            }
        }

        echo '</nav>';
    }

    /**
     * Render widget output in the editor.
     *
     * @since 2.1.0
     */
    protected function content_template()
    {
        ?>
        <nav class="jltma-pagination" role="navigation">
            <# if ( settings.pagination_type === 'prev_next' ) { #>
                <a class="prev page-numbers" href="#">{{{ settings.prev_label }}}</a>
                <a class="next page-numbers" href="#">{{{ settings.next_label }}}</a>
            <# } else { #>
                <# if ( settings.pagination_type === 'numbers_and_prev_next' ) { #>
                    <a class="prev page-numbers" href="#">{{{ settings.prev_label }}}</a>
                <# } #>
                <a class="page-numbers" href="#">1</a>
                <span class="page-numbers current">2</span>
                <a class="page-numbers" href="#">3</a>
                <span class="page-numbers dots">&hellip;</span>
                <a class="page-numbers" href="#">10</a>
                <# if ( settings.pagination_type === 'numbers_and_prev_next' ) { #>
                    <a class="next page-numbers" href="#">{{{ settings.next_label }}}</a>
                <# } #>
            <# } #>
        </nav>
        <?php
    }
}
