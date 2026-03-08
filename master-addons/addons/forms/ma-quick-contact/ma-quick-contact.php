<?php

namespace MasterAddons\Addons;

use MasterAddons\Inc\Classes\Base\Master_Widget;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Background;
use \Elementor\Icons_Manager;

use MasterAddons\Inc\Admin\Config;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Quick_Contact extends Master_Widget
{
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

    public function get_name()
    {
        return 'jltma-quick-contact';
    }

    public function get_title()
    {
        return esc_html__('Quick Contact', 'master-addons');
    }

    public function get_icon()
    {
        return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
    }

    public function get_keywords()
    {
        return ['help', 'desk', 'livechat', 'messenger', 'telegram', 'email', 'whatsapp', 'contact', 'quick'];
    }

            public function get_custom_help_url()
    {
        return 'https://master-addons.com/docs/';
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('Layout', 'master-addons'),
            ]
        );

        $this->add_control(
            'position',
            [
                'label'   => __('Position', 'master-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'left'  => __('Left', 'master-addons'),
                    'right' => __('Right', 'master-addons'),
                ],
                'default'   => 'right',
                'selectors' => [
                    '{{WRAPPER}} .jltma-quick-contact-icons' => '{{VALUE}} : 30px;',
                ],
            ]
        );

        $this->add_control(
            'main_icon',
            [
                'label'            => esc_html__('Icon', 'master-addons'),
                'type'             => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-life-ring',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'   => esc_html__('Icon Size', 'master-addons'),
                'type'    => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 35,
                        'max' => 100,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'size' => 50,
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-item, {{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-open-button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_space',
            [
                'label'   => esc_html__('Icon Space', 'master-addons'),
                'type'    => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .jltma-quick-contact-icons-open:checked ~ .jltma-quick-contact-icons-item:nth-child(3)' => 'transform: translate3d(0, calc(-{{SIZE}}{{UNIT}} - {{icon_size.SIZE}}{{UNIT}}), 0);',
                    '{{WRAPPER}} .jltma-quick-contact-icons-open:checked ~ .jltma-quick-contact-icons-item:nth-child(4)' => 'transform: translate3d(0, calc(-{{SIZE}}{{UNIT}} * 2 - {{icon_size.SIZE}}{{UNIT}} * 2), 0);',
                    '{{WRAPPER}} .jltma-quick-contact-icons-open:checked ~ .jltma-quick-contact-icons-item:nth-child(5)' => 'transform: translate3d(0, calc(-{{SIZE}}{{UNIT}} * 3 - {{icon_size.SIZE}}{{UNIT}} * 3), 0);',
                    '{{WRAPPER}} .jltma-quick-contact-icons-open:checked ~ .jltma-quick-contact-icons-item:nth-child(6)' => 'transform: translate3d(0, calc(-{{SIZE}}{{UNIT}} * 4 - {{icon_size.SIZE}}{{UNIT}} * 4), 0);',
                    '{{WRAPPER}} .jltma-quick-contact-icons-open:checked ~ .jltma-quick-contact-icons-item:nth-child(7)' => 'transform: translate3d(0, calc(-{{SIZE}}{{UNIT}} * 5 - {{icon_size.SIZE}}{{UNIT}} * 5), 0);',
                    '{{WRAPPER}} .jltma-quick-contact-icons-open:checked ~ .jltma-quick-contact-icons-item:nth-child(8)' => 'transform: translate3d(0, calc(-{{SIZE}}{{UNIT}} * 6 - {{icon_size.SIZE}}{{UNIT}} * 6), 0);',
                    '{{WRAPPER}} .jltma-quick-contact-icons-open:checked ~ .jltma-quick-contact-icons-item:nth-child(9)' => 'transform: translate3d(0, calc(-{{SIZE}}{{UNIT}} * 7 - {{icon_size.SIZE}}{{UNIT}} * 7), 0);',
                ],
            ]
        );

        $this->add_control(
            'main_title',
            [
                'label'       => esc_html__('Main Icon Title', 'master-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Quick Contact',
                'label_block' => true,
                'dynamic'     => ['active' => true],
            ]
        );

        $this->add_control(
            'tooltip_enable',
            [
                'label'   => esc_html__('Tooltip', 'master-addons'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Messenger Section
        $this->start_controls_section(
            'section_messenger',
            [
                'label' => esc_html__('Messenger', 'master-addons'),
            ]
        );

        $this->add_control(
            'messenger_show',
            [
                'label'   => esc_html__('Show Messenger', 'master-addons'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'messenger_title',
            [
                'label'       => esc_html__('Title', 'master-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Messenger',
                'label_block' => true,
                'dynamic'     => ['active' => true],
                'condition'   => [
                    'messenger_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'messenger_link',
            [
                'label'       => __('Username', 'master-addons'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('username', 'master-addons'),
                'default'     => [
                    'url' => '',
                ],
                'condition'   => [
                    'messenger_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // WhatsApp Section
        $this->start_controls_section(
            'section_whatsapp',
            [
                'label' => esc_html__('WhatsApp', 'master-addons'),
            ]
        );

        $this->add_control(
            'whatsapp_show',
            [
                'label'   => esc_html__('Show WhatsApp', 'master-addons'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'whatsapp_title',
            [
                'label'       => esc_html__('Title', 'master-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'WhatsApp',
                'label_block' => true,
                'dynamic'     => ['active' => true],
                'condition'   => [
                    'whatsapp_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'whatsapp_link',
            [
                'label'       => __('Phone Number', 'master-addons'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('1234567890', 'master-addons'),
                'default'     => [
                    'url' => '',
                ],
                'condition'   => [
                    'whatsapp_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Telegram Section
        $this->start_controls_section(
            'section_telegram',
            [
                'label' => esc_html__('Telegram', 'master-addons'),
            ]
        );

        $this->add_control(
            'telegram_show',
            [
                'label'   => esc_html__('Show Telegram', 'master-addons'),
                'type'    => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'telegram_title',
            [
                'label'       => esc_html__('Title', 'master-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Telegram',
                'label_block' => true,
                'dynamic'     => ['active' => true],
                'condition'   => [
                    'telegram_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'telegram_link',
            [
                'label'       => __('Username', 'master-addons'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('username', 'master-addons'),
                'default'     => [
                    'url' => '',
                ],
                'condition'   => [
                    'telegram_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Email Section
        $this->start_controls_section(
            'section_email',
            [
                'label' => esc_html__('Email', 'master-addons'),
            ]
        );

        $this->add_control(
            'email_show',
            [
                'label'   => esc_html__('Show Email', 'master-addons'),
                'type'    => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'email_title',
            [
                'label'       => esc_html__('Title', 'master-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Email Us',
                'label_block' => true,
                'dynamic'     => ['active' => true],
                'condition'   => [
                    'email_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'email_address',
            [
                'label'       => __('Email Address', 'master-addons'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __('email@example.com', 'master-addons'),
                'default'     => '',
                'label_block' => true,
                'condition'   => [
                    'email_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'email_subject',
            [
                'label'       => __('Subject', 'master-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
                'condition'   => [
                    'email_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Custom Link Section
        $this->start_controls_section(
            'section_custom',
            [
                'label' => esc_html__('Custom Link', 'master-addons'),
            ]
        );

        $this->add_control(
            'custom_show',
            [
                'label'   => esc_html__('Show Custom Link', 'master-addons'),
                'type'    => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'custom_title',
            [
                'label'       => esc_html__('Title', 'master-addons'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Custom',
                'label_block' => true,
                'dynamic'     => ['active' => true],
                'condition'   => [
                    'custom_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'custom_icon',
            [
                'label'            => esc_html__('Icon', 'master-addons'),
                'type'             => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-link',
                    'library' => 'fa-solid',
                ],
                'condition'   => [
                    'custom_show' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'custom_link',
            [
                'label'       => __('Link', 'master-addons'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('https://example.com', 'master-addons'),
                'default'     => [
                    'url' => '',
                ],
                'condition'   => [
                    'custom_show' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style: Main Button
        $this->start_controls_section(
            'section_style_main',
            [
                'label' => esc_html__('Main Button', 'master-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'main_color',
            [
                'label'     => esc_html__('Color', 'master-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-open-button' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-open-button svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'main_background',
                'selector' => '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-open-button'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'main_border',
                'selector' => '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-open-button'
            ]
        );

        $this->add_control(
            'main_border_radius',
            [
                'label'      => __('Border Radius', 'master-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-open-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'main_shadow',
                'selector' => '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-open-button'
            ]
        );

        $this->end_controls_section();

        // Style: Contact Icons
        $this->start_controls_section(
            'section_style_icons',
            [
                'label' => esc_html__('Contact Icons', 'master-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_icons_style');

        $this->start_controls_tab(
            'tab_icons_normal',
            [
                'label' => esc_html__('Normal', 'master-addons')
            ]
        );

        $this->add_control(
            'icons_color',
            [
                'label'     => esc_html__('Color', 'master-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-item' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-item svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'icons_background',
                'selector' => '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-item'
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icons_hover',
            [
                'label' => esc_html__('Hover', 'master-addons')
            ]
        );

        $this->add_control(
            'icons_hover_color',
            [
                'label'     => esc_html__('Color', 'master-addons'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-item:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-item:hover svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'icons_hover_background',
                'selector' => '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-item:hover'
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'icons_border',
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-item'
            ]
        );

        $this->add_control(
            'icons_border_radius',
            [
                'label'      => __('Border Radius', 'master-addons'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'icons_shadow',
                'selector' => '{{WRAPPER}} .jltma-quick-contact .jltma-quick-contact-icons-item'
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id       = 'jltma-quick-contact-icons-' . $this->get_id();

        ?>
        <div class="jltma-quick-contact">
            <nav class="jltma-quick-contact-icons">
                <input type="checkbox" class="jltma-quick-contact-icons-open" name="jltma-quick-contact-icons-open" id="<?php echo esc_attr($id); ?>" />
                <label class="jltma-quick-contact-icons-open-button" for="<?php echo esc_attr($id); ?>" title="<?php echo esc_html($settings['main_title']); ?>">
                    <?php Icons_Manager::render_icon($settings['main_icon'], ['aria-hidden' => 'true', 'class' => 'fa-fw']); ?>
                </label>
                <?php $this->render_messenger($settings); ?>
                <?php $this->render_whatsapp($settings); ?>
                <?php $this->render_telegram($settings); ?>
                <?php $this->render_email($settings); ?>
                <?php $this->render_custom($settings); ?>
            </nav>
        </div>
        <?php
    }

    protected function render_messenger($settings)
    {
        if ('yes' != $settings['messenger_show']) {
            return;
        }

        $this->add_render_attribute('messenger', 'class', ['jltma-quick-contact-icons-item', 'jltma-qc-messenger']);

        if (!empty($settings['messenger_link']['url'])) {
            $final_link = 'https://m.me/' . esc_attr($settings['messenger_link']['url']);
            $this->add_render_attribute('messenger', 'href', $final_link);
            $this->add_render_attribute('messenger', 'target', '_blank');
        }

        if ($settings['tooltip_enable'] === 'yes') {
            $this->add_render_attribute('messenger', 'title', $settings['messenger_title']);
        }
        ?>
        <a <?php echo $this->get_render_attribute_string('messenger'); ?>>
            <i class="fab fa-facebook-messenger" aria-hidden="true"></i>
        </a>
        <?php
    }

    protected function render_whatsapp($settings)
    {
        if ('yes' != $settings['whatsapp_show']) {
            return;
        }

        $this->add_render_attribute('whatsapp', 'class', ['jltma-quick-contact-icons-item', 'jltma-qc-whatsapp']);

        if (!empty($settings['whatsapp_link']['url'])) {
            $final_link = 'https://wa.me/' . esc_attr($settings['whatsapp_link']['url']);
            $this->add_render_attribute('whatsapp', 'href', $final_link);
            $this->add_render_attribute('whatsapp', 'target', '_blank');
        }

        if ($settings['tooltip_enable'] === 'yes') {
            $this->add_render_attribute('whatsapp', 'title', $settings['whatsapp_title']);
        }
        ?>
        <a <?php echo $this->get_render_attribute_string('whatsapp'); ?>>
            <i class="fab fa-whatsapp" aria-hidden="true"></i>
        </a>
        <?php
    }

    protected function render_telegram($settings)
    {
        if ('yes' != $settings['telegram_show']) {
            return;
        }

        $this->add_render_attribute('telegram', 'class', ['jltma-quick-contact-icons-item', 'jltma-qc-telegram']);

        if (!empty($settings['telegram_link']['url'])) {
            $final_link = 'https://telegram.me/' . esc_attr($settings['telegram_link']['url']);
            $this->add_render_attribute('telegram', 'href', esc_url($final_link));
            $this->add_render_attribute('telegram', 'target', '_blank');
        }

        if ($settings['tooltip_enable'] === 'yes') {
            $this->add_render_attribute('telegram', 'title', $settings['telegram_title']);
        }
        ?>
        <a <?php echo $this->get_render_attribute_string('telegram'); ?>>
            <i class="fab fa-telegram-plane" aria-hidden="true"></i>
        </a>
        <?php
    }

    protected function render_email($settings)
    {
        if ('yes' != $settings['email_show']) {
            return;
        }

        $this->add_render_attribute('email', 'class', ['jltma-quick-contact-icons-item', 'jltma-qc-email']);

        if (!empty($settings['email_address'])) {
            $final_link = 'mailto:' . sanitize_email($settings['email_address']);

            if (!empty($settings['email_subject'])) {
                $final_link .= '?subject=' . rawurlencode($settings['email_subject']);
            }

            $this->add_render_attribute('email', 'href', $final_link);
        }

        if ($settings['tooltip_enable'] === 'yes') {
            $this->add_render_attribute('email', 'title', $settings['email_title']);
        }
        ?>
        <a <?php echo $this->get_render_attribute_string('email'); ?>>
            <i class="fas fa-envelope" aria-hidden="true"></i>
        </a>
        <?php
    }

    protected function render_custom($settings)
    {
        if ('yes' != $settings['custom_show']) {
            return;
        }

        $this->add_render_attribute('custom', 'class', ['jltma-quick-contact-icons-item', 'jltma-qc-custom']);

        if (!empty($settings['custom_link']['url'])) {
            $this->add_render_attribute('custom', 'href', esc_url($settings['custom_link']['url']));

            if ($settings['custom_link']['is_external']) {
                $this->add_render_attribute('custom', 'target', '_blank');
            }

            if ($settings['custom_link']['nofollow']) {
                $this->add_render_attribute('custom', 'rel', 'nofollow');
            }
        }

        if ($settings['tooltip_enable'] === 'yes') {
            $this->add_render_attribute('custom', 'title', $settings['custom_title']);
        }
        ?>
        <a <?php echo $this->get_render_attribute_string('custom'); ?>>
            <?php Icons_Manager::render_icon($settings['custom_icon'], ['aria-hidden' => 'true', 'class' => 'fa-fw']); ?>
        </a>
        <?php
    }
}
