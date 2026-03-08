<?php

namespace MasterAddons\Addons;

use MasterAddons\Inc\Classes\Base\Master_Widget;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use MasterAddons\Inc\Admin\Config;

if (!defined('ABSPATH')) exit; // If this file is called directly, abort.

class Floating_Button extends Master_Widget
{
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

    public function get_name()
    {
        return 'boosted-elements-button-floating';
    }

        public function get_title()
    {
        return esc_html__('Floating Button - Boosted', 'boosted-elements-progression');
    }

    public function get_icon()
    {
        return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_title_boosted_global_options',
            [
                'label' => esc_html__('Floating Button Main Settings', 'boosted-elements-progression')
            ]
        );

        $this->add_control(
            'boosted_elements_btn_text',
            [
                'label' => esc_html__('Text', 'boosted-elements-progression'),
                'default' => esc_html__('Learn More', 'boosted-elements-progression'),
                'type' => Controls_Manager::TEXT,
            ]
        );
        $this->add_inline_editing_attributes('boosted_elements_btn_text', 'none');

        $this->add_control(
            'boosted_elements_btn_url',
            [
                'label' => esc_html__('Link', 'boosted-elements-progression'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'http://progressionstudios.com',
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'boosted_elements_btn_align',
            [
                'label' => esc_html__('Button Align', 'boosted-elements-progression'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'boosted-elements-progression'),
                        'icon' => 'fas fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'boosted-elements-progression'),
                        'icon' => 'fas fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'boosted-elements-progression'),
                        'icon' => 'fas fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'boosted_elements_btn_icon',
            [
                'label' => esc_html__('Icon', 'boosted-elements-progression'),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'boosted_elements_btn_icon_align',
            [
                'label' => esc_html__('Icon Position', 'boosted-elements-progression'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => esc_html__('Before', 'boosted-elements-progression'),
                    'right' => esc_html__('After', 'boosted-elements-progression'),
                ],
                'condition' => [
                    'boosted_elements_btn_icon!' => '',
                ],
            ]
        );

        $this->add_control(
            'boosted_elements_btn_icon_indent',
            [
                'label' => esc_html__('Icon Spacing', 'boosted-elements-progression'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 60,
                    ],
                ],
                'condition' => [
                    'boosted_elements_btn_icon!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .boosted-btn-icon-spacing-icon-right' => 'margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .boosted-btn-icon-spacing-icon-left' => 'margin-right: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_boosted_button_position',
            [
                'label' => esc_html__('Button Position', 'boosted-elements-progression')
            ]
        );

        $this->add_control(
            'section_title_boosted_btn_vertical_align',
            [
                'label' => esc_html__('Vertical', 'boosted-elements-progression'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'top' => [
                        'title' => esc_html__('Top', 'boosted-elements-progression'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'boosted-elements-progression'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'boosted-elements-progression'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'default' => 'bottom',
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container' => '{{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'top' => 'top:0px;',
                    'middle' => 'top:50%;',
                    'bottom' => 'bottom:0px;',
                ],

            ]
        );

        $this->add_control(
            'section_title_boosted_btn_vertical_align_position',
            [
                'label' => esc_html__('Vertical Spacing', 'boosted-elements-progression'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button ' => 'bottom: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'section_title_boosted_btn_horizontal_align',
            [
                'label' => esc_html__('Align', 'boosted-elements-progression'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'boosted-elements-progression'),
                        'icon' => 'fas fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'boosted-elements-progression'),
                        'icon' => 'fas fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'boosted-elements-progression'),
                        'icon' => 'fas fa-align-right',
                    ],
                ],
                'default' => 'right',
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container' => ' {{VALUE}}',
                ],
                'selectors_dictionary' => [
                    'left' => 'left:0px;',
                    'center' => 'right:50%;',
                    'right' => 'right:0px;',
                ],

            ]
        );

        $this->add_control(
            'section_title_boosted_btn_horizontal_align_position',
            [
                'label' => esc_html__('Horizontal Spacing', 'boosted-elements-progression'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button' => 'right: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_control(
            'boosted_elements_btn_rotation',
            [
                'label' => esc_html__('Rotate Button Degrees', 'boosted-elements-progression'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -180,
                        'max' => 180,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button' => 'transform: rotate({{SIZE}}deg);',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_title_boosted_button_styles',
            [
                'label' => esc_html__('Button Styles', 'boosted-elements-progression'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'section_title_boosted_btn_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
                'selector' => '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button',
            ]
        );

        $this->add_responsive_control(
            'section_title_boosted_btn_padding',
            [
                'label' => esc_html__('Padding', 'boosted-elements-progression'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('boosted_elements_button_tabs');

        $this->start_controls_tab('normal', ['label' => esc_html__('Normal', 'boosted-elements-progression')]);

        $this->add_control(
            'boosted_elements_button_text_color',
            [
                'label' => esc_html__('Text Color', 'boosted-elements-progression'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'boosted_elements_button_background_color',
            [
                'label' => esc_html__('Background Color', 'boosted-elements-progression'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'boosted_elements_btn_border',
                'selector' => '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button',
            ]
        );

        $this->add_control(
            'boosted_elements_btn_border_radius',
            [
                'label' => esc_html__('Border Radius', 'boosted-elements-progression'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('boosted_elements_hover', ['label' => esc_html__('Hover', 'boosted-elements-progression')]);

        $this->add_control(
            'boosted_elements_button_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'boosted-elements-progression'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'boosted_elements_button_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'boosted-elements-progression'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'boosted_elements_button_hover_border_color',
            [
                'label' => esc_html__('Border Color', 'boosted-elements-progression'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'btn_text_shadow',
                'selector' => '{{WRAPPER}} .boosted-elements-progression-button-floating-container .boosted-elements-button',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {

        $settings = $this->get_settings();

?>

        <div class="boosted-elements-progression-button-floating-container">
            <a href="<?php echo esc_url($settings['boosted_elements_btn_url']['url']); ?>" <?php if (!empty($settings['boosted_elements_btn_url']['is_external'])) : ?>target="_blank" <?php endif; ?> class="boosted-elements-button" <?php if (!empty($settings['boosted_elements_btn_url']['nofollow'])) : ?>rel="nofollow" <?php endif; ?>>
                <?php if (!empty($settings['boosted_elements_btn_icon']) && $settings['boosted_elements_btn_icon_align'] == 'left') : ?>
                    <?php \Elementor\Icons_Manager::render_icon($settings['boosted_elements_btn_icon'], ['aria-hidden' => 'true', 'class' => 'boosted-btn-icon-spacing-icon-left']); ?>
                <?php endif; ?>
                <?php echo '<div ' . $this->get_render_attribute_string('boosted_elements_btn_text') . '>' . esc_html($this->parse_text_editor($settings['boosted_elements_btn_text'])) . '</div>'; ?>
                <?php if (!empty($settings['boosted_elements_btn_icon']) && $settings['boosted_elements_btn_icon_align'] == 'right') : ?>
                    <?php \Elementor\Icons_Manager::render_icon($settings['boosted_elements_btn_icon'], ['aria-hidden' => 'true', 'class' => 'boosted-btn-icon-spacing-icon-right']); ?>
                <?php endif; ?>
            </a>
            <div class="clearfix-boosted-element"></div>
        </div><!-- close .boosted-elements-progression-button-floating-container -->

<?php

    }

    protected function content_template()
    {
    }
}
