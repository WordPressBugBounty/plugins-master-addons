<?php

namespace MasterAddons\Addons;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

use MasterAddons\Inc\Admin\Config;
use MasterAddons\Inc\Classes\Base\Master_Widget;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Image_Scroll extends Master_Widget
{
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

    /**
     * Get widget name.
     *
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'ma-image-scroll';
    }

    /**
     * Get widget title.
     *
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Image Scroll', 'master-addons' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
    }

    /**
     * Get widget unique keywords.
     *
     * Retrieve the list of unique keywords the widget belongs to.
     *
     * @since 1.0.0
     *
     * @return array Widget unique keywords.
     */
    public function get_unique_keywords()
    {
        return array(
            'scroll',
            'image',
            'roll',
            'preview',
        );
    }

    /**
     * Register controls.
     *
     * Used to add new controls to the widget.
     *
     * Should be inherited and register new controls using `add_control()`,
     * `add_responsive_control()` and `add_group_control()`, inside control
     * wrappers like `start_controls_section()`, `start_controls_tabs()` and
     * `start_controls_tab()`.
     *
     * @since 1.0.0
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_image_scroll',
            array(
                'label' => __('Settings', 'master-addons' ),
            )
        );

        $this->add_control(
            'image',
            array(
                'label' => __('Image', 'master-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => array(
                    'url' => Utils::get_placeholder_image_src(),
                ),
                'dynamic' => array('active' => true),
                'description' => __('Choose your image to scroll.', 'master-addons' ),
                'label_block' => true,
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name' => 'img_size',
                'default' => 'full',
                'exclude' => array_keys(Group_Control_Image_Size::get_all_image_sizes()),
                'separator' => 'none',
                'condition' => array(
                    'image[id]!' => '',
                ),
            )
        );

        $this->add_control(
            'scroll_type',
            array(
                'label' => __('Scroll On:', 'master-addons' ),
                'label_block' => false,
                'type' => 'jltma-choose-text',
                'options' => array(
                    'hover' => __('Hover', 'master-addons' ),
                    'mouse' => __('Mouse Scroll', 'master-addons' ),
                ),
                'default' => 'hover',
                'separator' => 'before',
                'toggle' => false,
                'prefix_class' => 'jltma-image-scroll__type-',
                'render_type' => 'template',
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'scroll_direction',
            array(
                'label' => __('Direction', 'master-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'vertical' => array(
                        'title' => __('Vertical', 'master-addons' ),
                        'icon' => 'fas fa-arrows-alt-v',
                    ),
                    'horizontal' => array(
                        'title' => __('Horizontal', 'master-addons' ),
                        'icon' => 'fas fa-arrows-alt-h',
                    ),
                ),
                'default' => 'vertical',
                'prefix_class' => 'jltma-image-scroll__',
                'label_block' => false,
                'toggle' => false,
                'render_type' => 'template',
                'frontend_available' => true,
            )
        );

        $this->add_responsive_control(
            'height',
            array(
                'label' => __('Height', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1600,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 150,
                        'step' => 0.1,
                    ),
                    'vh' => array(
                        'min' => 0,
                        'max' => 100,
                        'step' => 0.1,
                    ),
                ),
                'default' => array('unit' => 'px'),
                'size_unit' => array(
                    'px',
                    'em',
                    'vh',
                ),
                'separator' => 'before',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__outer' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__image-wrapper' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'type_animate',
            array(
                'label' => __('Animation', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    'ease' => __('Ease', 'master-addons' ),
                    'ease-in' => __('Ease In', 'master-addons' ),
                    'ease-out' => __('Ease Out', 'master-addons' ),
                    'ease-in-out' => __('Ease In Out', 'master-addons' ),
                    'linear' => __('Linear', 'master-addons' ),
                ),
                'default' => 'ease',
                'separator' => 'before',
                'render_type' => 'ui',
                'prefix_class' => 'jltma-image-scroll__',
                'condition' => array('scroll_type' => 'hover'),
            )
        );

        $this->add_responsive_control(
            'speed',
            array(
                'label' => __('Speed', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => array('size' => 0.5),
                'range' => array(
                    'px' => array(
                        'max' => 5,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__wrapper img' => 'transition-duration: {{SIZE}}s',
                ),
                'condition' => array('scroll_type' => 'hover'),
            )
        );

        $this->add_control(
            'link_type',
            array(
                'label' => __('Link', 'master-addons' ),
                'label_block' => true,
                'type' => 'jltma-choose-text',
                'options' => array(
                    'disabled' => __('Disabled', 'master-addons' ),
                    'url' => __('Custom URL', 'master-addons' ),
                    'lightbox' => __('Lightbox', 'master-addons' ),
                ),
                'default' => 'disabled',
                'toggle' => false,
                'separator' => 'before',
                'prefix_class' => 'jltma-image-scroll__',
                'render_type' => 'template',
            )
        );

        $this->add_control(
            'image_url',
            array(
                'label' => __('Url', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'master-addons' ),
                'show_external' => true,
                'default' => array(
                    'url' => '#',
                ),
                'show_label' => false,
                'condition' => array('link_type' => 'url'),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name' => 'img_size_lightbox',
                'condition' => array('link_type' => 'lightbox'),
                'exclude' => array_keys(Group_Control_Image_Size::get_all_image_sizes()),
                'condition' => array(
                    'image[id]!' => '',
                    'link_type' => 'lightbox',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_image_scroll_icon',
            array(
                'label' => __('Overlay & Label', 'master-addons' ),
            )
        );

        $this->add_control(
            'overlay_color',
            array(
                'label' => __('Overlay Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'render_type' => 'template',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__overlay' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'hide_overlay',
            array(
                'label' => __('Hide Overlay On Hover', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'prefix_class' => 'jltma-image-scroll__overlay-',
                'condition' => array(
                    'overlay_color!' => '',
                ),
            )
        );

        $this->add_control(
            'icon_label',
            array(
                'label' => __('Label', 'master-addons' ),
                'type' => 'jltma-choose-text',
                'options' => array(
                    'none' => __('Disabled', 'master-addons' ),
                    'icon' => __('Icon', 'master-addons' ),
                    'text' => __('Text', 'master-addons' ),
                ),
                'default' => 'none',
                'toggle' => false,
                'label_block' => false,
                'render_type' => 'template',
                'separator' => 'before',
            )
        );

        $this->add_control(
            'icon',
            array(
                'label' => __('Icon', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'default' => array(
                    'value' => 'fas fa-link',
                    'library' => 'solid',
                ),
                'condition' => array('icon_label' => 'icon'),
            )
        );

        $this->add_control(
            'text',
            array(
                'label' => __('Text', 'master-addons' ),
                'type' => Controls_Manager::TEXT,
                'condition' => array('icon_label' => 'text'),
                'default' => 'Label',
            )
        );

        $this->add_control(
            'hide_label',
            array(
                'label' => __('Hide Label When hovering a mouse', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'prefix_class' => 'jltma-image-scroll__label-',
                'condition' => array(
                    'icon_label!' => 'none',
                ),
            )
        );

        $this->add_responsive_control(
            'label_position_h',
            array(
                'label' => __('Label Position Horizontal(%)', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    '%' => array(
                        'max' => 100,
                        'step' => 0.1,
                    ),
                ),
                'default' => array('unit' => '%'),
                'size_unit' => array('%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__label' => 'left: {{SIZE}}%; transform: translate( -{{SIZE}}%, -{{label_position_v.SIZE}}% );',
                ),
                'separator' => 'before',
                'condition' => array(
                    'icon_label!' => 'none',
                ),
            )
        );

        $this->add_responsive_control(
            'label_position_v',
            array(
                'label' => __('Label Position Vertical(%)', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    '%' => array(
                        'max' => 100,
                        'step' => 0.1,
                    ),
                ),
                'default' => array('unit' => '%'),
                'size_unit' => array('%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__label' => 'top: {{SIZE}}%; transform: translate( -{{label_position_h.SIZE}}%, -{{SIZE}}%);',
                ),
                'condition' => array(
                    'icon_label!' => 'none',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_image_scroll_caption',
            array(
                'label' => __('Caption', 'master-addons' ),
            )
        );

        $this->add_control(
            'caption',
            array(
                'label' => __('Caption', 'master-addons' ),
                'type' => Controls_Manager::TEXT,
            )
        );

        $this->add_control(
            'caption_align',
            array(
                'label' => __('Caption Align', 'master-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'master-addons' ),
                        'icon' => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => __('Center', 'master-addons' ),
                        'icon' => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => __('Right', 'master-addons' ),
                        'icon' => 'eicon-text-align-right',
                    ),
                ),
                'prefix_class' => 'jltma-image-scroll__align-',
                'condition' => array(
                    'caption!' => '',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_label_style',
            array(
                'label' => __('Label Style', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'icon_label!' => 'none',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'typography_label',
                'label' => __('Label Typography', 'master-addons' ),
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-image-scroll__inner .elementor-widget-jltma-image-scroll__label .elementor-widget-jltma-image-scroll__label-text',
                'condition' => array(
                    'icon_label' => array('text'),
                ),
            )
        );

        $this->add_responsive_control(
            'size_icon',
            array(
                'label' => __('Label Size', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'default' => array('size' => 14),
                'range' => array(
                    'px' => array(
                        'max' => 100,
                        'step' => 1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__inner .elementor-widget-jltma-image-scroll__label i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__inner .elementor-widget-jltma-image-scroll__label svg' => 'width: {{SIZE}}{{UNIT}}',
                ),
                'condition' => array(
                    'icon_label' => array('icon'),
                ),
            )
        );

        $this->start_controls_tabs('label_tabs');

        $colors = array(
            'normal' => __('Normal', 'master-addons' ),
            'hover' => __('Hover', 'master-addons' ),
        );

        foreach ($colors as $key => $label) {
            $state = ('hover' === $key) ? ':hover' : '';
            $selector = "{{WRAPPER}} .elementor-widget-jltma-image-scroll__inner{$state} .elementor-widget-jltma-image-scroll__label";

            $this->start_controls_tab(
                "label_tab_{$key}",
                array(
                    'label' => $label,
                )
            );

            $this->add_control(
                "color_label_{$key}",
                array(
                    'label' => __('Label Color', 'master-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'render_type' => 'ui',
                    'selectors' => array(
                        $selector => 'color: {{VALUE}};',
                    ),
                )
            );

            $this->add_control(
                "label_bg_{$key}",
                array(
                    'label' => __('Label Background Color', 'master-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'render_type' => 'ui',
                    'selectors' => array(
                        $selector => 'background-color: {{VALUE}};',
                    ),
                )
            );

            $this->add_control(
                "label_bd_color_{$key}",
                array(
                    'label' => __('Label Border Color', 'master-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'render_type' => 'ui',
                    'selectors' => array(
                        $selector => 'border-color: {{VALUE}};',
                    ),
                )
            );

            $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name' => "box_shadow_{$key}",
                    'selector' => $selector,
                )
            );

            $this->end_controls_tab();
        }

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name' => 'border_label',
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-image-scroll__inner .elementor-widget-jltma-image-scroll__label',
                'exclude' => array('color'),
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'label_border_radius',
            array(
                'label' => __('Label Border Radius', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__inner .elementor-widget-jltma-image-scroll__label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'label_padding',
            array(
                'label' => __('Label Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__inner .elementor-widget-jltma-image-scroll__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_caption_style',
            array(
                'label' => __('Caption Style', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'caption!' => '',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'typography_caption',
                'label' => __('Caption Typography', 'master-addons' ),
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-image-scroll__caption',
            )
        );

        $this->add_control(
            'caption_color',
            array(
                'label' => __('Caption Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'render_type' => 'ui',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__caption' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'caption_bgc',
            array(
                'label' => __('Caption Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'render_type' => 'ui',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__caption' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name' => 'border_caption',
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-image-scroll__caption',
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'caption_padding',
            array(
                'label' => __('Caption Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'separator' => 'before',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-image-scroll__caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render image scroll widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('inner', 'class', 'elementor-widget-jltma-image-scroll__inner');

        $tag = 'a';

        if ('lightbox' === $settings['link_type']) {
            $image_src = Group_Control_Image_Size::get_attachment_image_src($settings['image']['id'], 'img_size_lightbox', $settings);

            $image_lightbox = ('' === $settings['image']['id']) ? $settings['image']['url'] : $image_src;

            $this->add_render_attribute('inner', array(
                'href' => $image_lightbox,
                'data-elementor-open-lightbox' => 'yes',
            ));
        } elseif ('url' === $settings['link_type']) {
            $this->add_link_attributes('inner', $settings['image_url']);
        } else {
            $tag = 'div';
        }

        $image_tag = Group_Control_Image_Size::get_attachment_image_html($settings, 'img_size', 'image');

        echo '<figure class="elementor-widget-jltma-image-scroll__wrapper">
			<div class="elementor-widget-jltma-image-scroll__outer">
				<' . esc_attr($tag) . ' ' . $this->get_render_attribute_string('inner') . '>
					<div class="elementor-widget-jltma-image-scroll__image-wrapper">
						<div class="elementor-widget-jltma-image-scroll__image-parent">' . wp_kses_post($image_tag);

        if ('' !== $settings['overlay_color']) {
            echo '<span class="elementor-widget-jltma-image-scroll__overlay"></span>';
        }

        echo '</div>
		</div>';

        if ('none' !== $settings['icon_label']) {
            echo '<span class="elementor-widget-jltma-image-scroll__label">';

            if ('icon' === $settings['icon_label']) {
                echo '<span class="elementor-widget-jltma-image-scroll__label-icon">';
                Icons_Manager::render_icon($settings['icon']);
                echo '</span>';
            }

            if ('text' === $settings['icon_label']) {
                echo '<span class="elementor-widget-jltma-image-scroll__label-text">' . esc_html($this->parse_text_editor($settings['text'])) . '</span>';
            }

            echo '</span>';
        }

        echo '</' . esc_attr($tag) . '>
		</div>';

        if ('' !== $settings['caption']) {
            echo '<figcaption class="elementor-widget-jltma-image-scroll__caption">' . esc_html($settings['caption']) . '</figcaption>';
        }

        echo '</figure>';
    }
}
