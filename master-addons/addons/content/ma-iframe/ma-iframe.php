<?php
namespace MasterAddons\Addons;

use MasterAddons\Inc\Classes\Base\Master_Widget;
use \Elementor\Controls_Manager;
use \MasterAddons\Inc\Classes\Helper;
use MasterAddons\Inc\Admin\Config;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class IFrame extends Master_Widget {
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

	public function get_name() {
		return 'jltma-iframe';
	}

	public function get_title() {
		return esc_html__('iFrame', 'master-addons' );
	}

	public function get_icon() {
		return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
	}

	public function get_keywords() {
		return [ 'iframe', 'embed' ];
	}

	//  else {
    //         return [ 'master-addons-iframe' ];
    //     }
    // }

	//  else {
	// 		return [ 'recliner', 'master-addons-iframe' ];
    //     }
	// }

	public function get_custom_help_url() {
		return 'https://www.youtube.com/@jeweltheme6643';
	}


	protected function register_controls() {
		$this->start_controls_section(
			'jltma_iframe_section_content_layout',
			[
				'label' => esc_html__( 'Layout', 'master-addons' ),
			]
		);

		$this->add_control(
			'jltma_iframe_source',
			[
				'label'         => esc_html__( 'Content Source', 'master-addons' ),
				'type'          => Controls_Manager::URL,
				'dynamic'       => [ 'active' => true ],
				'default'       => [ 'url' => 'https://example.com' ],
				'placeholder'   => esc_html__( 'https://example.com', 'master-addons' ),
				'description'   => esc_html__( 'You can put here any website url, youtube, vimeo, document or image embed url.( But please make sure about your link. If your website have SSL Certificate, please use SSL Certified Link here. Otherwise, Iframe will not work. )', 'master-addons' ),
				'label_block'   => true,
				'show_external' => false,
			]
		);

		$this->add_control(
			'jltma_iframe_auto_height',
			[
				'label'   => esc_html__( 'Auto Height', 'master-addons' ),
				'description'   => esc_html__( 'Auto height only works when cross domain with "allow origin all in header".'  , 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'jltma_iframe_show_iframe_device' => ''
				]
			]
		);

		$this->add_responsive_control(
			'jltma_iframe_height',
			[
				'label'     => esc_html__( 'Iframe Height', 'master-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'   => 100,
						'max'   => 1500,
						'step' => 10,
					],
					'vw' => [
						'min'   => 1,
						'max'   => 100,
					],
					'%' => [
						'min'   => 1,
						'max'   => 100,
					],
				],
				'size_units' => [ 'px', 'vh' ],
				'default' => [
					'size' => 640,
				],
				'selectors' => [
					'{{WRAPPER}} .jltma-iframe iframe' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'jltma_iframe_auto_height!' => 'yes',
					'jltma_iframe_show_responsive_ratio!' => 'yes',
					'jltma_iframe_show_iframe_device' => ''
				],
			]
		);

		$this->add_responsive_control(
			'jltma_iframe_size',
			[
				'label'       => esc_html__( 'Iframe Container Width', 'master-addons' ) . JLTMA_NEW_FEATURE,
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min' => 180,
						'max' => 1200,
					],
				],
				'selectors'   => [
					'{{WRAPPER}} .bdt-device-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'jltma_iframe_show_responsive_ratio',
			[
				'label'   => esc_html__( 'Responsive Ratio', 'master-addons' ) . JLTMA_NEW_FEATURE,
				'type'    => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition'   => [
					'jltma_iframe_auto_height!' => 'yes',
					'jltma_iframe_show_iframe_device' => ''
				],
			]
		);

		$this->add_control(
			'jltma_iframe_responsive_ratio_size',
			[
				'label'       => esc_html__('Size Ratio', 'master-addons'),
				'type'        => Controls_Manager::IMAGE_DIMENSIONS,
				'description' => esc_html__( 'Iframe ratio to width and height, such as 600/1280', 'master-addons' ),
				'condition'   => [
					'jltma_iframe_show_responsive_ratio' => 'yes',
					'jltma_iframe_auto_height!'          => 'yes',
					'jltma_iframe_show_iframe_device'    => ''
				],
				'default' => [
					'width' => 1280,
					'height' => 720,
				]
			]
		);

		$this->add_control(
			'jltma_iframe_align',
			[
				'label'        => esc_html__( 'Alignment', 'master-addons' ) . JLTMA_NEW_FEATURE,
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => esc_html__( 'Left', 'master-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'master-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'master-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'bdt-iframe-align-',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

        // Lazyload Settings
		$this->start_controls_section(
			'jltma_iframe_section_lazyload_settings',
			[
				'label' => esc_html__( 'Lazyload Settings', 'master-addons' ),
			]
		);

		$this->add_control(
			'jltma_iframe_lazyload',
			[
				'label'   => esc_html__( 'Lazyload', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'throttle',
			[
				'label'       => esc_html__('Throttle', 'master-addons'),
				'description' => esc_html__('millisecond interval at which to process events', 'master-addons'),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 300,
				'condition'   => [
					'jltma_iframe_lazyload' => 'yes',
				],
			]
		);

		$this->add_control(
			'jltma_iframe_threshold',
			[
				'label'       => esc_html__('Threshold', 'master-addons'),
				'description' => esc_html__('scroll distance from element before its loaded', 'master-addons'),
				'type'        => Controls_Manager::NUMBER,
				'separator'   => 'before',
				'default'     => 100,
				'condition'   => [
					'jltma_iframe_lazyload' => 'yes',
				],
			]
		);

		$this->add_control(
			'jltma_iframe_live',
			[
				'label'       => esc_html__( 'Live', 'master-addons' ),
				'description' => esc_html__('auto bind lazy loading to ajax loaded elements', 'master-addons'),
				'type'        => Controls_Manager::SWITCHER,
				'separator'   => 'before',
				'default'     => 'yes',
				'condition'   => [
					'jltma_iframe_lazyload' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'jltma_iframe_section_content_additional',
			[
				'label' => esc_html__( 'Additional Settings', 'master-addons' ),
			]
		);

		$this->add_control(
			'jltma_iframe_allowfullscreen',
			[
				'label'       => esc_html__( 'Allow Fullscreen', 'master-addons' ),
				'description' => esc_html__('Maybe you need this when you use youtube or video embed link.', 'master-addons'),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes'
			]
		);

		$this->add_control(
			'jltma_iframe_scrolling',
			[
				'label'       => esc_html__( 'Show Scroll Bar', 'master-addons' ),
				'description' => esc_html__('Specifies whether or not to display scrollbars', 'master-addons'),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'jltma_iframe_sandbox',
			[
				'label'       => esc_html__( 'Sandbox', 'master-addons' ),
				'description' => esc_html__('Enables an extra set of restrictions for the content', 'master-addons'),
				'type'        => Controls_Manager::SWITCHER,
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'jltma_iframe_sandbox_allowed_attributes',
			[
				'label'       => esc_html__('Sandbox Allowed Attributes', 'master-addons'),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options'     => [
                    'allow-forms'                             => esc_html__('Forms', 'master-addons'),
                    'allow-modals'                            => esc_html__('Modals', 'master-addons'),
                    'allow-orientation-lock'                  => esc_html__('Orientation Lock', 'master-addons'),
                    'allow-pointer-lock'                      => esc_html__('Pointer Lock', 'master-addons'),
                    'allow-popups'                            => esc_html__('Popups', 'master-addons'),
                    'allow-popups-to-escape-sandbox'          => esc_html__('Popups to Escape Sandbox', 'master-addons'),
                    'allow-presentation'                      => esc_html__('Presentation', 'master-addons'),
                    'allow-same-origin'                       => esc_html__('Same Origin', 'master-addons'),
                    'allow-scripts'                           => esc_html__('Scripts', 'master-addons'),
                    'allow-top-navigation'                    => esc_html__('Top Navigation', 'master-addons'),
                    'allow-top-navigation-by-user-activation' => esc_html__('Top Navigation by User', 'master-addons'),
				],
				'condition' => [
					'jltma_iframe_sandbox' => 'yes'
				]
			]
		);

        //allowvr="yes" allow="vr; xr; accelerometer; magnetometer; gyroscope; autoplay
		$this->add_control(
			'jltma_iframe_custom_attributes',
			[
				'label' => __( 'Custom Attributes', 'master-addons' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'key|value', 'master-addons' ),
				'description' => sprintf( __( 'Set custom attributes for the iframe tag. Each attribute in a separate line. Separate attribute key from the value using %s character.', 'master-addons' ), '<code>|</code>' ),
				'classes' => 'elementor-control-direction-ltr',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'jltma_iframe_section_content_device',
			[
				'label' => esc_html__( 'Iframe Device', 'master-addons' ) . JLTMA_NEW_FEATURE,
			]
		);

		$this->add_control(
			'jltma_iframe_show_iframe_device',
			[
				'label' => esc_html__( 'Show Iframe Device', 'master-addons' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'jltma_iframe_device_type',
			[
				'label'   => esc_html__( 'Select Device', 'master-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'imac',
				'options' => [
					'chrome'      => esc_html__( 'Chrome', 'master-addons' ),
					'chrome-dark' => esc_html__( 'Chrome Dark', 'master-addons' ),
					'imac'        => esc_html__( 'Desktop', 'master-addons' ),
					'edge'        => esc_html__( 'Edge', 'master-addons' ),
					'edge-dark'   => esc_html__( 'Edge Dark', 'master-addons' ),
					'firefox'     => esc_html__( 'Firefox', 'master-addons' ),
					'mobile'      => esc_html__( 'Mobile', 'master-addons' ),
					'safari'      => esc_html__( 'Safari', 'master-addons' ),
					'tablet'      => esc_html__( 'Tablet', 'master-addons' ),
					'custom'      => esc_html__( 'Custom', 'master-addons' ),
				],
				'condition' => [
					'jltma_iframe_show_iframe_device' => 'yes'
				]
			]
		);

		$this->add_control(
			'jltma_iframe_rotation_state',
			[
				'label'   => esc_html__( 'Horizontal Rotation State', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'conditions'   => [
					'relation' => 'and',
					'terms' => [
						[
							'name'     => 'jltma_iframe_show_iframe_device',
							'value'    => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name'     => 'jltma_iframe_device_type',
									'value'    => 'tablet',
								],
								[
									'name'     => 'jltma_iframe_device_type',
									'value'    => 'mobile',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'jltma_iframe_show_notch',
			[
				'label'   => esc_html__( 'Show Notch', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'conditions'   => [
					'relation' => 'and',
					'terms' => [
						[
							'name'     => 'jltma_iframe_show_iframe_device',
							'value'    => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name'     => 'jltma_iframe_device_type',
									'value'    => 'tablet',
								],
								[
									'name'     => 'jltma_iframe_device_type',
									'value'    => 'mobile',
								],
							],
						],
					],
				],
				'prefix_class' => 'bdt-ds-notch--',
			]
		);

		$this->add_control(
			'jltma_iframe_show_buttons',
			[
				'label'   => esc_html__( 'Show Buttons', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'conditions'   => [
					'relation' => 'and',
					'terms' => [
						[
							'name'     => 'jltma_iframe_show_iframe_device',
							'value'    => 'yes',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name'     => 'jltma_iframe_device_type',
									'value'    => 'tablet',
								],
								[
									'name'     => 'jltma_iframe_device_type',
									'value'    => 'mobile',
								],
							],
						],
					],
				],
				'prefix_class' => 'bdt-ds-buttons--',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'jltma_iframe_section_custom_device',
			[
				'label'     => esc_html__( 'Custom Device', 'master-addons' ) . JLTMA_NEW_FEATURE,
				'condition' => [
					'jltma_iframe_show_iframe_device' => 'yes',
					'jltma_iframe_device_type'        => 'custom'
				],
			]
		);

		$this->add_control(
			'jltma_iframe_slider_size_ratio',
			[
				'label'       => esc_html__('Size Ratio', 'master-addons'),
				'type'        => Controls_Manager::IMAGE_DIMENSIONS,
				'description' => esc_html__( 'Iframe ratio to width and height, such as 600/1280', 'master-addons' ),
				'default' => [
					'width' => 600,
					'height' => 1200,
				]
			]
		);

		$this->add_control(
			'jltma_iframe_custom_device_buttons',
			[
				'label'   => esc_html__( 'BUTTONS', 'master-addons' ),
				'type'    => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'jltma_iframe_show_left_button_1',
			[
				'label'   => esc_html__( 'Show Left Button 1', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'bdt-ds-left-button-1--',
			]
		);

		$this->add_control(
			'jltma_iframe_show_left_button_2',
			[
				'label'   => esc_html__( 'Show Left Button 2', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'bdt-ds-left-button-2--',
			]
		);

		$this->add_control(
			'jltma_iframe_show_left_button_3',
			[
				'label'   => esc_html__( 'Show Left Button 3', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'bdt-ds-left-button-3--',
			]
		);

		$this->add_control(
			'jltma_iframe_show_right_button_1',
			[
				'label'   => esc_html__( 'Show Right Button 1', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'bdt-ds-right-button-1--',
			]
		);

		$this->add_control(
			'jltma_iframe_show_right_button_2',
			[
				'label'   => esc_html__( 'Show Right Button 2', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'prefix_class' => 'bdt-ds-right-button-2--',
			]
		);

		$this->add_control(
			'jltma_iframe_custom_device_notch',
			[
				'label'   => esc_html__( 'NOTCH', 'master-addons' ),
				'type'    => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'jltma_iframe_show_custom_notch',
			[
				'label'   => esc_html__( 'Show notch', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'jltma_iframe_select_notch',
			[
				'label'   => esc_html__( 'Type', 'master-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'large-notch',
				'options' => [
					'large-notch' => esc_html__( 'Large Notch', 'master-addons' ),
					'small-notch' => esc_html__( 'Small Notch', 'master-addons' ),
					'drop-notch'  => esc_html__( 'Drop Notch', 'master-addons' ),
				],
				'condition' => [
					'jltma_iframe_show_custom_notch' => 'yes'
				]
			]
		);

		$this->add_control(
			'jltma_iframe_custom_device_lens',
			[
				'label'   => esc_html__( 'LENS', 'master-addons' ),
				'type'    => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'jltma_iframe_show_custom_notch' => ''
				]
			]
		);

		$this->add_control(
			'jltma_iframe_show_custom_lens',
			[
				'label'   => esc_html__( 'Show Lens', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'jltma_iframe_show_custom_notch' => ''
				]
			]
		);

		$this->add_responsive_control(
			'jltma_iframe_lens_size',
			[
				'label'   => esc_html__( 'Size', 'master-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-iframe.bdt-device-custom .phone-lens' => 'height: {{SIZE}}px; width: {{SIZE}}px;',
				],
				'condition' => [
					'jltma_iframe_show_custom_lens' => 'yes',
					'jltma_iframe_show_custom_notch' => ''
				]
			]
		);

		$this->add_responsive_control(
			'jltma_iframe_lens_horizontal',
			[
				'label'   => esc_html__( 'Horizontal Offset', 'master-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 50
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-iframe.bdt-device-custom .phone-lens' => 'left: {{SIZE}}%;',
				],
				'condition' => [
					'jltma_iframe_show_custom_lens' => 'yes',
					'jltma_iframe_show_custom_notch' => ''
				]
			]
		);

		$this->add_responsive_control(
			'jltma_iframe_lens_vertical',
			[
				'label'   => esc_html__( 'Vertical Offset', 'master-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-iframe.bdt-device-custom .phone-lens' => 'top: {{SIZE}}%;',
				],
				'condition' => [
					'jltma_iframe_show_custom_lens' => 'yes',
					'jltma_iframe_show_custom_notch' => ''
				]
			]
		);

		$this->add_control(
			'jltma_iframe_custom_device_bazel',
			[
				'label'   => esc_html__( 'BAZEL', 'master-addons' ),
				'type'    => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'jltma_iframe_custom_device_border_width',
			[
				'label'      => __( 'Width', 'master-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-iframe.bdt-device-custom iframe' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .bdt-iframe.bdt-device-custom .phone-notch svg' => 'top: calc({{TOP}}{{UNIT}} - 1px);'
				],
			]
		);

		$this->add_responsive_control(
			'jltma_iframe_custom_device_border_radius',
			[
				'label'      => __( 'Border Radius', 'master-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top'      => '40',
					'right'    => '40',
					'bottom'   => '40',
					'left'     => '40',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .bdt-iframe.bdt-device-custom iframe' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//Style
		$this->start_controls_section(
			'jltma_iframe_section_style_device',
			[
				'label' => esc_html__( 'Device', 'master-addons' ) . JLTMA_NEW_FEATURE,
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'jltma_iframe_show_iframe_device' => 'yes',
					'jltma_iframe_device_type'        => ['mobile', 'tablet', 'custom']
				],
			]
		);

		$this->add_control(
			'jltma_iframe_device_color_1',
			[
				'label'   => esc_html__( 'Color 1', 'master-addons' ),
				'type'    => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-iframe svg .bdt-ds-color-1' => 'fill: {{VALUE}};'
				],
				'condition' => [
					'jltma_iframe_device_type!' => 'custom'
				],
			]
		);

		$this->add_control(
			'jltma_iframe_device_color_2',
			[
				'label'   => esc_html__( 'Color 2', 'master-addons' ),
				'type'    => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-iframe svg .bdt-ds-color-2' => 'fill: {{VALUE}};'
				],
				'condition' => [
					'jltma_iframe_device_type!' => 'custom'
				],
			]
		);

		$this->add_control(
			'jltma_iframe_custom_device_border_color_1',
			[
				'label'   => esc_html__( 'Color 1', 'master-addons' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#343434',
				'selectors' => [
					'{{WRAPPER}} .bdt-iframe.bdt-device-custom iframe' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-iframe.bdt-device-custom .phone-notch svg .bdt-ds-color-1' => 'fill: {{VALUE}};'
				],
				'condition' => [
					'jltma_iframe_device_type' => 'custom'
				],
			]
		);

		$this->add_control(
			'jltma_iframe_custom_device_border_color_2',
			[
				'label'   => esc_html__( 'Color 2', 'master-addons' ),
				'type'    => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-iframe.bdt-device-custom .phone-notch svg .bdt-ds-color-2' => 'fill: {{VALUE}};'
				],
				'condition' => [
					'jltma_iframe_device_type' => 'custom'
				],
			]
		);

		$this->add_control(
			'jltma_iframe_device_buttons_color',
			[
				'label'   => esc_html__( 'Buttons Color', 'master-addons' ),
				'type'    => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-iframe .bdt-ds-buttons .bdt-ds-color-1' => 'fill: {{VALUE}};',
					'{{WRAPPER}} .bdt-device-container:before, {{WRAPPER}} .bdt-device-custom:after, {{WRAPPER}} .bdt-device-custom:before, {{WRAPPER}} .bdt-device-custom .bdt-iframe-device:after, {{WRAPPER}} .bdt-device-custom .bdt-iframe-device:before' => 'background: {{VALUE}};'
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'or',
							'terms' => [
								[
									'name'     => 'jltma_iframe_device_type',
									'value'    => 'mobile'
								],
								[
									'name'     => 'jltma_iframe_device_type',
									'value'    => 'tablet'
								],
							],
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name'     => 'jltma_iframe_show_left_button_1',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_left_button_2',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_left_button_3',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_right_button_1',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_right_button_2',
									'value'    => 'yes'
								],
							]
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'jltma_iframe_buttons_width',
			[
				'label'     => esc_html__( 'Buttons Width', 'master-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-device-container:before, {{WRAPPER}} .bdt-device-custom:after, {{WRAPPER}} .bdt-device-custom:before, {{WRAPPER}} .bdt-device-custom .bdt-iframe-device:after, {{WRAPPER}} .bdt-device-custom .bdt-iframe-device:before' => 'width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name'     => 'jltma_iframe_device_type',
							'value'    => 'custom'
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name'     => 'jltma_iframe_show_left_button_1',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_left_button_2',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_left_button_3',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_right_button_1',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_right_button_2',
									'value'    => 'yes'
								],
							]
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'jltma_iframe_right_button_vertical',
			[
				'label'   => esc_html__( 'Right Button Y Offset', 'master-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .bdt-device-custom:after' => 'top: {{SIZE}}%;',
					'{{WRAPPER}} .bdt-device-custom:before' => 'top: calc(9% + {{SIZE}}%);',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name'     => 'jltma_iframe_device_type',
							'value'    => 'custom'
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name'     => 'jltma_iframe_show_right_button_1',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_right_button_2',
									'value'    => 'yes'
								],
							]
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'jltma_iframe_left_button_vertical',
			[
				'label'   => esc_html__( 'Left Button Y Offset', 'master-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .bdt-device-container:before' => 'top: {{SIZE}}%;',
					'{{WRAPPER}} .bdt-device-custom .bdt-iframe-device:after' => 'top: calc(8% + {{SIZE}}%);',
					'{{WRAPPER}} .bdt-device-custom .bdt-iframe-device:before' => 'top: calc(18% + {{SIZE}}%);',
				],
				'condition' => [
					'jltma_iframe_device_type' => 'custom'
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name'     => 'jltma_iframe_device_type',
							'value'    => 'custom'
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name'     => 'jltma_iframe_show_left_button_1',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_left_button_2',
									'value'    => 'yes'
								],
								[
									'name'     => 'jltma_iframe_show_left_button_3',
									'value'    => 'yes'
								],
							]
						]
					]
				]
			]
		);

		$this->end_controls_section();

	}

	protected function render_device() {
		$settings    = $this->get_settings_for_display();

		if ( ! $this->get_settings( 'jltma_iframe_show_iframe_device' ) ) {
			return;
		}

		$device_type = $settings['jltma_iframe_device_type'];
		$rotation_state = ('yes' == $settings['jltma_iframe_rotation_state']) ? '-hr' : '';
		$svg_uri = JLTMA_PATH . 'images/devices/' . $device_type . $rotation_state . '.svg';
		$svg_url = esc_url(JLTMA_ASSETS) . 'images/devices/' . $device_type . $rotation_state . '.svg';

		$notch_type = $settings['jltma_iframe_select_notch'];
		$notch_svg_uri = JLTMA_PATH . 'images/devices/' . $notch_type . '.svg';

		?>
		<div class="bdt-iframe-device">

			<?php if ($settings['jltma_iframe_device_type'] !== 'custom') : ?>
				<?php if ($settings['jltma_iframe_device_type'] == 'mobile' or $settings['jltma_iframe_device_type'] == 'tablet') : ?>
					<?php echo Helper::jltma_load_svg( $svg_uri ); ?>
				<?php else : ?>
					<img src="<?php echo esc_url($svg_url)  ?>" alt="Device Slider">
				<?php endif; ?>
			<?php endif; ?>

			<?php if ($settings['jltma_iframe_device_type'] == 'custom' and 'yes' == $settings['jltma_iframe_show_custom_lens']) : ?>
			<img class="phone-lens" src="<?php echo esc_url(JLTMA_ASSETS); ?>images/devices/phone-lens.svg" alt="Device Slider">
			<?php endif; ?>

			<?php if ($settings['jltma_iframe_device_type'] == 'custom' and 'yes' == $settings['jltma_iframe_show_custom_notch']) : ?>
			<span class="phone-notch">
				<?php echo Helper::jltma_load_svg( $notch_svg_uri ); ?>
			</span>
			<?php endif; ?>

		</div>
		<?php
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		$device_type = $settings['jltma_iframe_device_type'];

		if ( 'imac' === $device_type ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 2560, 'height' => 1440 ] );
		} elseif ( 'safari' === $device_type ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 2800, 'height' => 1454 ] );
		} elseif ( 'chrome' === $device_type ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 2800, 'height' => 1576 ] );
		} elseif ( 'chrome-dark' === $device_type ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 2800, 'height' => 1576 ] );
		} elseif ( 'firefox' === $device_type ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 2560, 'height' => 1302 ] );
		} elseif ( 'edge' === $device_type ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 2580, 'height' => 1302 ] );
		} elseif ( 'edge-dark' === $device_type ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 2580, 'height' => 1302 ] );
		} elseif ( 'tablet' === $device_type and $settings['jltma_iframe_rotation_state'] == '' ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 1536, 'height' => 2048 ] );
		} elseif ( 'tablet' === $device_type and $settings['jltma_iframe_rotation_state'] == 'yes' ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 2048, 'height' => 1536 ] );
		} elseif ( 'mobile' === $device_type and $settings['jltma_iframe_rotation_state'] == '' ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 1200, 'height' => 2574 ] );
		} elseif ( 'mobile' === $device_type and $settings['jltma_iframe_rotation_state'] == 'yes' ) {
			$this->add_render_attribute( 'iframe', [ 'width' => 2574, 'height' => 1200 ] );
		} elseif ( 'custom' === $device_type ) {
			$this->add_render_attribute( 'iframe', [
                'width' => $settings['jltma_iframe_slider_size_ratio']['width'],
                'height' => $settings['jltma_iframe_slider_size_ratio']['height'] ] );
		}

		$rotation_state = ('yes' == $settings['jltma_iframe_rotation_state']) ? '-hr' : '';

		$this->add_render_attribute( 'iframe-container', 'class', 'bdt-iframe bdt-device-'.$device_type . $rotation_state );

		if ('yes' == $settings['jltma_iframe_lazyload']) {
			$this->add_render_attribute( 'iframe', 'class', 'bdt-lazyload' );
			$this->add_render_attribute( 'iframe', 'data-throttle', esc_attr($settings['throttle']) );
			$this->add_render_attribute( 'iframe', 'data-threshold', esc_attr($settings['jltma_iframe_threshold']) );
			$this->add_render_attribute( 'iframe', 'data-live', $settings['jltma_iframe_live'] ? 'true' : 'false' );
			$this->add_render_attribute( 'iframe', 'data-src', esc_url( do_shortcode( $settings['jltma_iframe_source']['url']) ) );
		} else {
			$this->add_render_attribute( 'iframe', 'src', esc_url( do_shortcode( $settings['jltma_iframe_source']['url'] ) ) );
		}

		if (! $settings['jltma_iframe_scrolling']) {
			$this->add_render_attribute( 'iframe', 'jltma_iframe_scrolling', 'no' );
		}

		if($settings['jltma_iframe_show_iframe_device']) {
			$this->add_render_attribute( 'iframe', 'bdt-responsive' );
		} elseif ($settings['jltma_iframe_show_responsive_ratio']) {
			$this->add_render_attribute( 'iframe', 'bdt-responsive' );
			$this->add_render_attribute( 'iframe', [ 'width' => $settings['jltma_iframe_responsive_ratio_size']['width'], 'height' => $settings['jltma_iframe_responsive_ratio_size']['height'] ] );
		} else {
			$this->add_render_attribute( 'iframe', 'data-auto_height', ($settings['jltma_iframe_auto_height']) ? 'true' : 'false' );
		}

		if ('yes' == $settings['jltma_iframe_allowfullscreen']) {
			$this->add_render_attribute( 'iframe', 'jltma_iframe_allowfullscreen' );
		} else {
			$this->add_render_attribute( 'iframe', 'donotallowfullscreen' );
		}

		if ($settings['jltma_iframe_sandbox']) {
			$this->add_render_attribute( 'iframe', 'jltma_iframe_sandbox' );

			if ($settings['jltma_iframe_sandbox_allowed_attributes']) {
				$this->add_render_attribute( 'iframe', 'jltma_iframe_sandbox', $settings['jltma_iframe_sandbox_allowed_attributes'] );
			}
		}

		if ( ! empty( $settings['jltma_iframe_custom_attributes'] ) ) {
			$attributes = explode( "\n", $settings['jltma_iframe_custom_attributes'] );

			$reserved_attr = [ 'class', 'onload', 'onclick', 'onfocus', 'onblur', 'onchange', 'onresize', 'onmouseover', 'onmouseout', 'onkeydown', 'onkeyup', 'onerror', 'jltma_iframe_sandbox', 'jltma_iframe_allowfullscreen', 'donotallowfullscreen', 'jltma_iframe_scrolling', 'data-throttle', 'data-threshold', 'data-live', 'data-src' ];

			foreach ( $attributes as $attribute ) {
				if ( ! empty( $attribute ) ) {
					$attr = explode( '|', $attribute, 2 );
					if ( ! isset( $attr[1] ) ) {
						$attr[1] = '';
					}

					if ( ! in_array( strtolower( $attr[0] ), $reserved_attr ) ) {
						$this->add_render_attribute( 'iframe', trim( $attr[0] ), trim( $attr[1] ) );
					}
				}
			}
		}

		?>
		<div class="bdt-device-container">
			<div <?php $this->print_render_attribute_string('iframe-container'); ?>>
				<iframe <?php $this->print_render_attribute_string('iframe'); ?>></iframe>
				<?php $this->render_device(); ?>
			</div>
		</div>
		<?php
	}
}
