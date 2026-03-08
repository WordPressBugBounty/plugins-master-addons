<?php

namespace MasterAddons\Addons;

// Elementor Classes
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

use MasterAddons\Inc\Classes\Helper;
use MasterAddons\Inc\Admin\Config;
use MasterAddons\Inc\Classes\Base\Master_Widget;

/**
 * Author Name: Liton Arefin
 * Author URL : https://master-addons.com
 * Date       : 3/15/2020
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * PDF Viewer
 */
class PDF_Viewer extends Master_Widget
{
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

    public function get_name()
    {
        return 'jltma-pdf-viewer';
    }

    public function get_title()
    {
        return esc_html__('PDF Viewer', 'master-addons' );
    }

    public function get_icon()
    {
        return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
    }

    protected function register_controls()
    {

        // section start
        $this->start_controls_section(
            'pdf_viewer_content',
            [
                'label' => esc_html__('PDF Viewer', 'master-addons' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'pdf_link',
            [
                'label'       => esc_html__('Select File', 'master-addons' ),
                'type'        => 'jltma-file-select',
                'placeholder' => esc_html__('URL to File', 'master-addons' ),
                'default'     => JLTMA_IMAGE_DIR . 'sample.pdf'
            ]
        );

        $this->add_control(
            'pdf_hr_1',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'fallback',
            [
                'label'       => esc_html__('Fallback Message', 'master-addons' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default'     => esc_html__('Your browser does not support inline PDFs. Click here to view the file.', 'master-addons' )
            ]
        );

        $this->end_controls_section();

        // section start
        $this->start_controls_section(
            'pdf_viewer_style',
            [
                'label' => esc_html__('PDF Viewer', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'pdf_align',
            [
                'label'   => esc_html__('Horizontal Align', 'master-addons' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'master-addons' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'master-addons' ),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'master-addons' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .pdfobject-container-container' => 'align-items: {{VALUE}};',
                ],
                'toggle' => false
            ]
        );

        $this->add_responsive_control(
            'pdf_width',
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
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pdfobject-container' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'pdf_height',
            [
                'label' => esc_html__('Height', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 600,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pdfobject-container' => 'height: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'pdf_hr_2',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pdf_border',
                'label' => esc_html__('Border', 'master-addons' ),
                'selector' => '{{WRAPPER}} .pdfobject-container'
            ]
        );

        $this->add_control(
            'pdf_border_radius',
            [
                'label' => esc_html__('Border Radius', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .pdfobject-container' => 'border-top-left-radius: {{TOP}}{{UNIT}};border-top-right-radius: {{RIGHT}}{{UNIT}};border-bottom-right-radius: {{BOTTOM}}{{UNIT}};border-bottom-left-radius: {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'pdf_shadow',
                'label' => esc_html__('Box Shadow', 'master-addons' ),
                'selector' => '{{WRAPPER}} .pdfobject-container'
            ]
        );

        $this->end_controls_section();

        // section start
        $this->start_controls_section(
            'pdf_fallback_style',
            [
                'label' => esc_html__('Fallback Message', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pdf_fallback_typography',

                'selector' => '.jltma-pdf-viewer-msg a',
            ]
        );

        $this->add_control(
            'pdf_fallback_color',
            [
                'label' => esc_html__('Font Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .jltma-pdf-viewer-msg a' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'pdf_fallback_bg_color',
            [
                'label' => esc_html__('Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#cc0000',
                'selectors' => [
                    '{{WRAPPER}} .jltma-pdf-viewer-msg' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'pdf_fallback_padding',
            [
                'label' => esc_html__('Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .jltma-pdf-viewer-msg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => true,
                ]
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
?>
        <div class="jltma-pdf-viewer-container" style="display:flex;width:100%;flex-direction:column;">
            <div class="jltma-pdf-viewer" style="max-width:100%;" data-pdfurl="<?php echo esc_url($settings['pdf_link']); ?>" data-fallbackmsg="<?php echo esc_attr($settings['fallback']); ?>"></div>
        </div>
<?php
    }
}
