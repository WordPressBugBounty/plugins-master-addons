<?php

namespace MasterAddons\Addons;

use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use MasterAddons\Inc\Controls\Group\JLTMA_Transition;
use MasterAddons\Inc\Admin\Config;
use MasterAddons\Inc\Classes\Base\Master_Widget;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Google_Map
 *
 * @since 2.0.0
 */
class Google_Maps extends Master_Widget
{
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

    /**
     * Get Name
     *
     * Get the name of the widget
     *
     * @since  2.0.0
     * @return string
     */
    public function get_name()
    {
        return 'ma-google-maps';
    }

        /**
     * Get Title
     *
     * Get the title of the widget
     *
     * @since  2.0.0
     * @return string
     */
    public function get_title()
    {
        return __('Google Map', 'master-addons');
    }

    /**
     * Get Icon
     *
     * Get the icon of the widget
     *
     * @since  2.0.0
     * @return string
     */
    public function get_icon()
    {
        return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
    }

    /**
     * Get Script Depends
     *
     * A list of scripts that the widgets is depended in
     *
     * @since  2.0.0
     * @return array
     */
        /**
     * Register Widget Controls
     *
     * @since  2.0.0
     * @return void
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_pins',
            [
                'label' => __('Locations', 'master-addons'),
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs('pins_repeater');

        $repeater->start_controls_tab('pins_pin', ['label' => __('Pin', 'master-addons')]);

        $repeater->add_control(
            'lat',
            [
                'label'        => __('Latitude', 'master-addons'),
                'dynamic'    => ['active' => true],
                'type'         => Controls_Manager::TEXT,
                'default'     => '',
            ]
        );

        $repeater->add_control(
            'lng',
            [
                'label'        => __('Longitude', 'master-addons'),
                'dynamic'    => ['active' => true],
                'type'         => Controls_Manager::TEXT,
                'default'     => '',
            ]
        );

        $repeater->add_control(
            'icon',
            [
                'label'     => __('Icon', 'master-addons'),
                'dynamic'    => ['active' => true],
                'description' => __('IMPORTANT: Your icon image needs to be a square to avoid distortion of the artwork.', 'master-addons'),
                'type'         => Controls_Manager::MEDIA,
            ]
        );

        $repeater->end_controls_tab();

        $repeater->start_controls_tab('pins_info', ['label' => __('Popup', 'master-addons')]);

        $repeater->add_control(
            'name',
            [
                'label'        => __('Title', 'master-addons'),
                'dynamic'    => ['active' => true],
                'type'         => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => __('Pin', 'master-addons'),
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'        => __('Description', 'master-addons'),
                'dynamic'    => ['active' => true],
                'type'         => Controls_Manager::WYSIWYG,
            ]
        );

        $repeater->add_control(
            'trigger',
            [
                'label'        => __('Trigger', 'master-addons'),
                'type'         => Controls_Manager::SELECT,
                'default'     => 'click',
                'label_block' => true,
                'options'    => [
                    'click'     => __('Click', 'master-addons'),
                    'auto'         => __('Auto', 'master-addons'),
                    'mouseover' => __('Mouse Over', 'master-addons'),
                ],
            ]
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'pins',
            [
                'type'         => Controls_Manager::REPEATER,
                'default'     => [
                    [
                        'name' => __('Tour Eiffel', 'master-addons'),
                        'lat' => '48.8583736',
                        'lng' => '2.2922873',
                    ],
                    [
                        'name' => __('Arc de Triomphe', 'master-addons'),
                        'lat' => '48.8737952',
                        'lng' => '2.2928335',
                    ],
                    [
                        'name' => __('Louvre Museum', 'master-addons'),
                        'lat' => '48.8606146',
                        'lng' => '2.33545',
                    ],
                ],
                'fields'         => $repeater->get_controls(),
                'title_field'     => '{{{ name }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_popups',
            [
                'label' => __('Popups', 'master-addons'),
            ]
        );

        $this->add_control(
            'popups',
            [
                'label'         => __('Enable Popups', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => 'yes',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label'     => __('Title Tag', 'master-addons'),
                'type'         => Controls_Manager::SELECT,
                'options'     => [
                    'h1'     => __('H1', 'master-addons'),
                    'h2'     => __('H2', 'master-addons'),
                    'h3'     => __('H3', 'master-addons'),
                    'h4'     => __('H4', 'master-addons'),
                    'h5'     => __('H5', 'master-addons'),
                    'h6'     => __('H6', 'master-addons'),
                    'div'    => __('div', 'master-addons'),
                    'span'     => __('span', 'master-addons'),
                ],
                'default' => 'h5',
                'condition' => [
                    'popups' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'description_tag',
            [
                'label'     => __('Description Tag', 'master-addons'),
                'type'         => Controls_Manager::SELECT,
                'default'     => 'p',
                'options'     => [
                    'p'     => __('p', 'master-addons'),
                    'div'    => __('div', 'master-addons'),
                    'span'     => __('span', 'master-addons'),
                ],
                'condition' => [
                    'popups' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_map',
            [
                'label' => __('Map', 'master-addons'),
            ]
        );

        $this->add_control(
            'heading_center',
            [
                'type'        => Controls_Manager::HEADING,
                'label'     => __('Center Map', 'master-addons'),
                'condition'    => [
                    'route'    => '',
                ],
            ]
        );

        $this->add_control(
            'fit',
            [
                'label'         => __('Fit to Locations', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => 'yes',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
                'condition'        => [
                    'route'        => '',
                ],
            ]
        );

        $this->add_control(
            'lat',
            [
                'label'        => __('Latitude', 'master-addons'),
                'type'         => Controls_Manager::TEXT,
                'dynamic'    => ['active' => true],
                'default'     => '48.8583736',
                'condition'    => [
                    'fit'     => '',
                    'route'    => '',
                ],
            ]
        );

        $this->add_control(
            'lng',
            [
                'label'        => __('Longitude', 'master-addons'),
                'type'         => Controls_Manager::TEXT,
                'dynamic'    => ['active' => true],
                'default'     => '2.2922873',
                'condition'    => [
                    'fit'     => '',
                    'route'    => '',
                ],
            ]
        );

        $this->add_control(
            'zoom',
            [
                'label'         => __('Zoom', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'default'     => [
                    'size'     => 10,
                ],
                'range'     => [
                    'px'     => [
                        'min'     => 0,
                        'max'     => 18,
                        'step'    => 1,
                    ],
                ],
                'condition' => [
                    'fit'     => '',
                    'route'    => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'heading_settings',
            [
                'type'        => Controls_Manager::HEADING,
                'label'     => __('Settings', 'master-addons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'map_type',
            [
                'label'        => __('Map Type', 'master-addons'),
                'type'         => Controls_Manager::SELECT,
                'default'     => 'ROADMAP',
                'options'    => [
                    'ROADMAP'     => __('Roadmap', 'master-addons'),
                    'SATELLITE' => __('Satellite', 'master-addons'),
                    'TERRAIN'     => __('Terrain', 'master-addons'),
                    'HYBRID'     => __('Hybrid', 'master-addons'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'scrollwheel',
            [
                'label'         => __('Scrollwheel', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'clickable_icons',
            [
                'label'         => __('Clickable Icons', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'doubleclick_zoom',
            [
                'label'         => __('Double Click to Zoom', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => 'yes',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'draggable',
            [
                'label'         => __('Draggable', 'master-addons'),
                'description'    => __('Note: Map is not draggable in edit mode.', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => 'yes',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'keyboard_shortcuts',
            [
                'label'         => __('Keyboard Shortcuts', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => 'yes',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'heading_controls',
            [
                'type'        => Controls_Manager::HEADING,
                'label'     => __('Interface', 'master-addons'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'fullscreen_control',
            [
                'label'         => __('Fullscreen Control', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'map_type_control',
            [
                'label'         => __('Map Type Control', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'rotate_control',
            [
                'label'         => __('Rotate Control', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'scale_control',
            [
                'label'         => __('Scale Control', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'streetview_control',
            [
                'label'         => __('Street View Control', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'zoom_control',
            [
                'label'         => __('Zoom Control', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_polygon',
            [
                'label' => __('Polygon', 'master-addons'),
            ]
        );

        $this->add_control(
            'polygon',
            [
                'label'         => __('Enable', 'master-addons'),
                'description'     => __('Draws a polygon on the map by connecting the locations.', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_route',
            [
                'label' => __('Route', 'master-addons'),
            ]
        );

        $this->add_control(
            'route',
            [
                'label'         => __('Enable', 'master-addons'),
                'description'     => __('Draws a route on the map between the locations.', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'route_mode',
            [
                'label'     => __('Mode', 'master-addons'),
                'type'         => Controls_Manager::SELECT,
                'default'     => 'DRIVING',
                'options'     => [
                    'DRIVING'     => __('Driving', 'master-addons'),
                    'WALKING'     => __('Walking', 'master-addons'),
                    'BICYCLING' => __('Bicycling', 'master-addons'),
                    'TRANSIT'     => __('Transit', 'master-addons'),
                ],
                'condition'     => [
                    'route!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'route_markers',
            [
                'label'         => __('Markers', 'master-addons'),
                'description'     => __('Enables direction markers to be shown on your route.', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'condition'     => [
                    'route!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_navigation',
            [
                'label' => __('Navigation', 'master-addons'),
            ]
        );

        $this->add_responsive_control(
            'navigation',
            [
                'label'         => __('Enable', 'master-addons'),
                'description'     => __('Adds a list which visitors can use to navigate through your locations.', 'master-addons'),
                'type'             => Controls_Manager::SWITCHER,
                'default'         => '',
                'label_on'         => __('Yes', 'master-addons'),
                'label_off'     => __('No', 'master-addons'),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'navigation_zoom',
            [
                'label'     => __('Zoom Level', 'master-addons'),
                'type'         => Controls_Manager::SLIDER,
                'default'     => [
                    'size'     => 18,
                ],
                'range'     => [
                    'px'     => [
                        'max' => 18,
                    ],
                ],
                'condition' => [
                    'navigation!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'navigation_hide_on',
            [
                'label'     => __('Hide On', 'master-addons'),
                'type'         => Controls_Manager::SELECT,
                'default'     => 'mobile',
                'options'     => [
                    ''             => __('None', 'master-addons'),
                    'tablet'     => __('Mobile & Tablet', 'master-addons'),
                    'mobile'     => __('Mobile Only', 'master-addons'),
                ],
                'condition' => [
                    'navigation!' => '',
                ],
                'prefix_class' => 'jltma-google-map-navigation--hide-',
            ]
        );

        $this->add_control(
            'all_text',
            [
                'label'        => __('All label', 'master-addons'),
                'type'         => Controls_Manager::TEXT,
                'default'     => __('All locations', 'master-addons'),
                'frontend_available' => true,
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_control(
            'selected_navigation_icon',
            [
                'label'             => __('Icon', 'master-addons'),
                'type'                 => Controls_Manager::ICONS,
                'fa4compatibility'     => 'navigation_icon',
                'default'             => [
                    'value'         => 'fas fa-map-marker-alt',
                    'library'         => 'fa-solid',
                ],
                'label_block'        => false,
                'skin'                 => 'inline',
                'condition'         => [
                    'navigation!'     => '',
                ],
            ]
        );

        $this->add_control(
            'navigation_icon_align',
            [
                'label' => __('Icon Position', 'master-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before', 'master-addons'),
                    'right' => __('After', 'master-addons'),
                ],
                'condition' => [
                    'navigation!' => '',
                    'selected_navigation_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'navigation_icon_indent',
            [
                'label' => __('Icon Spacing', 'master-addons'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'condition' => [
                    'navigation!' => '',
                    'selected_navigation_icon[value]!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee-icon--right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ee-icon--left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_pins',
            [
                'label' => __('Pins', 'master-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'pin_size',
            [
                'label'         => __('Size', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'description'     => __('Note: This setting only applies to custom pins.', 'master-addons'),
                'default'     => [
                    'size'     => 50,
                ],
                'range'     => [
                    'px'     => [
                        'min'     => 0,
                        'max'     => 100,
                        'step'    => 1,
                    ],
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'pin_position_horizontal',
            [
                'label'         => __('Horizontal Position', 'master-addons'),
                'description'     => __('Note: This setting only applies to custom pins.', 'master-addons'),
                'type'             => Controls_Manager::CHOOSE,
                'default'         => 'center',
                'options'         => [
                    'left'            => [
                        'title'     => __('Left', 'master-addons'),
                        'icon'         => 'eicon-h-align-left',
                    ],
                    'center'         => [
                        'title'     => __('Center', 'master-addons'),
                        'icon'         => 'eicon-h-align-center',
                    ],
                    'right'         => [
                        'title'     => __('Right', 'master-addons'),
                        'icon'         => 'eicon-h-align-right',
                    ],
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'pin_position_vertical',
            [
                'label'         => __('Vertical Position', 'master-addons'),
                'description'     => __('Note: This setting only applies to custom pins.', 'master-addons'),
                'type'             => Controls_Manager::CHOOSE,
                'default'         => 'top',
                'options'         => [
                    'top'            => [
                        'title'     => __('Top', 'master-addons'),
                        'icon'         => 'eicon-v-align-top',
                    ],
                    'middle'            => [
                        'title'     => __('Middle', 'master-addons'),
                        'icon'         => 'eicon-v-align-middle',
                    ],
                    'bottom'         => [
                        'title'     => __('Bottom', 'master-addons'),
                        'icon'         => 'eicon-v-align-bottom',
                    ],
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_map',
            [
                'label' => __('Map', 'master-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'map_style_type',
            [
                'label' => __('Add style from', 'master-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'api',
                'options' => [
                    'api'     => __('Snazzy Maps API', 'master-addons'),
                    'json'     => __('Custom JSON', 'master-addons'),
                ],
                'label_block' => true,
                'frontend_available' => true,
            ]
        );

        $sm_endpoint_option = \MasterAddons\Inc\Admin\Settings\Settings::get_api('snazzy_maps_endpoint', false);

        // TODO: Snazzy Maps control requires custom 'jltma-snazzy' control type
        // $this->add_control(
        //     'map_style_api',
        //     [
        //         'label'                 => __('Search Snazzy Maps', 'master-addons'),
        //         'type'                     => 'jltma-snazzy',
        //         'placeholder'            => __('Search styles', 'master-addons'),
        //         'snazzy_options'        => [
        //             'endpoint'            => $sm_endpoint_option ? $sm_endpoint_option : 'explore',
        //         ],
        //         'default'                => '',
        //         'frontend_available'     => true,
        //         'condition'                => [
        //             'map_style_type'    => 'api',
        //         ],
        //     ]
        // );

        $this->add_control(
            'map_style_json',
            [
                'label'                    => __('Custom JSON', 'master-addons'),
                'description'             => sprintf(__('Paste the JSON code for styling the map. You can get it from %1$sSnazzyMaps%2$s or similar services. Note: If you enter an invalid JSON string you\'ll be alerted.', 'master-addons'), '<a target="_blank" href="https://snazzymaps.com/explore">', '</a>'),
                'type'                     => Controls_Manager::TEXTAREA,
                'default'                 => '',
                'frontend_available'     => true,
                'condition'                => [
                    'map_style_type'    => 'json',
                ],
            ]
        );

        $this->add_responsive_control(
            'map_height',
            [
                'label'         => __('Height', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'size_units'     => ['px', 'vh', '%'],
                'default'     => [
                    'size'     => 400,
                ],
                'range'     => [
                    'vh'         => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%'     => [
                        'min'     => 10,
                        'max'     => 100,
                        'step'    => 1,
                    ],
                    'px'     => [
                        'min'     => 100,
                        'max'     => 1000,
                        'step'    => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_polygon',
            [
                'label' => __('Polygon', 'master-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'polygon!' => '',
                ],
            ]
        );

        $this->start_controls_tabs('polygon_tabs');

        $this->start_controls_tab('polygon_default', ['label' => __('Default', 'master-addons')]);

        $this->add_control(
            'heading_polygon_stroke',
            [
                'type'        => Controls_Manager::HEADING,
                'label'     => __('Stroke', 'master-addons'),
                'condition' => [
                    'polygon!' => '',
                ],
            ]
        );

        $this->add_control(
            'polygon_stroke_weight',
            [
                'label'         => __('Weight', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'default'     => [
                    'size'     => 2,
                ],
                'range'     => [
                    'px'     => [
                        'min'     => 0,
                        'max'     => 10,
                        'step'    => 1,
                    ],
                ],
                'condition' => [
                    'polygon!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'polygon_stroke_color',
            [
                'label'     => __('Color', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'condition' => [
                    'polygon!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'polygon_stroke_opacity',
            [
                'label'         => __('Opacity', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'default'     => [
                    'size'     => 0.8,
                ],
                'range'     => [
                    'px'     => [
                        'min'     => 0,
                        'max'     => 1,
                        'step'    => 0.01,
                    ],
                ],
                'condition' => [
                    'polygon!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'heading_polygon_fill',
            [
                'type'        => Controls_Manager::HEADING,
                'label'     => __('Fill', 'master-addons'),
                'separator' => 'before',
                'condition' => [
                    'polygon!' => '',
                ],
            ]
        );

        $this->add_control(
            'polygon_fill_color',
            [
                'label'     => __('Color', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'condition' => [
                    'polygon!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'polygon_fill_opacity',
            [
                'label'         => __('Opacity', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'default'     => [
                    'size'     => 0.35,
                ],
                'range'     => [
                    'px'     => [
                        'min'     => 0,
                        'max'     => 1,
                        'step'    => 0.01,
                    ],
                ],
                'condition' => [
                    'polygon!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('polygon_hover', ['label' => __('Hover', 'master-addons')]);

        $this->add_control(
            'heading_polygon_stroke_hover',
            [
                'type'        => Controls_Manager::HEADING,
                'label'     => __('Stroke', 'master-addons'),
                'condition' => [
                    'polygon!' => '',
                ],
            ]
        );

        $this->add_control(
            'polygon_stroke_weight_hover',
            [
                'label'         => __('Weight', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'default'     => [
                    'size'     => 2,
                ],
                'range'     => [
                    'px'     => [
                        'min'     => 0,
                        'max'     => 10,
                        'step'    => 1,
                    ],
                ],
                'condition' => [
                    'polygon!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'polygon_stroke_color_hover',
            [
                'label'     => __('Color', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'condition' => [
                    'polygon!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'polygon_stroke_opacity_hover',
            [
                'label'         => __('Opacity', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'default'     => [
                    'size'     => 0.8,
                ],
                'range'     => [
                    'px'     => [
                        'min'     => 0,
                        'max'     => 1,
                        'step'    => 0.01,
                    ],
                ],
                'condition' => [
                    'polygon!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'heading_polygon_fill_hover',
            [
                'type'        => Controls_Manager::HEADING,
                'label'     => __('Fill', 'master-addons'),
                'separator' => 'before',
                'condition' => [
                    'polygon!' => '',
                ],
            ]
        );

        $this->add_control(
            'polygon_fill_color_hover',
            [
                'label'     => __('Color', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'condition' => [
                    'polygon!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'polygon_fill_opacity_hover',
            [
                'label'         => __('Opacity', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'default'     => [
                    'size'     => 0.35,
                ],
                'range'     => [
                    'px'     => [
                        'min'     => 0,
                        'max'     => 1,
                        'step'    => 0.01,
                    ],
                ],
                'condition' => [
                    'polygon!' => '',
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_navigation',
            [
                'label' => __('Navigation', 'master-addons'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_position',
            [
                'label'        => __('Position', 'master-addons'),
                'type'         => Controls_Manager::SELECT,
                'default'     => 'top-left',
                'options'    => [
                    'top-left'         => __('Top Left', 'master-addons'),
                    'top-right'     => __('Top Right', 'master-addons'),
                    'bottom-right'     => __('Bottom Right', 'master-addons'),
                    'bottom-left'     => __('Bottom Left', 'master-addons'),
                ],
                'frontend_available' => true,
                'prefix_class' => 'jltma-google-map-navigation%s--',
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_width',
            [
                'label'         => __('Width', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'size_units'     => ['px', '%'],
                'range'         => [
                    '%'         => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px'         => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .ee-google-map__navigation' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_margin',
            [
                'label'         => __('Margin', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'range'         => [
                    'px'         => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .ee-google-map__navigation' => 'margin: {{SIZE}}{{UNIT}}; max-height: calc( 100% - {{SIZE}}px * 2 );',
                ],
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_control(
            'navigation_background',
            [
                'label'     => __('Background', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default'    => '',
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'         => 'navigation_border',
                'label'     => __('Border', 'master-addons'),
                'selector'     => '{{WRAPPER}} .ee-google-map__navigation',
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_control(
            'navigation_border_radius',
            [
                'label'         => __('Border Radius', 'master-addons'),
                'type'             => Controls_Manager::DIMENSIONS,
                'selectors'     => [
                    '{{WRAPPER}} .ee-google-map__navigation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ee-google-map__navigation__item:first-child a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
                    '{{WRAPPER}} .ee-google-map__navigation__item:last-child a' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'         => 'navigation_box_shadow',
                'selector'     => '{{WRAPPER}} .ee-google-map__navigation',
                'separator'    => '',
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_control(
            'heading_navigation_separator',
            [
                'type'        => Controls_Manager::HEADING,
                'label'     => __('Separator', 'master-addons'),
                'separator' => 'before',
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_links_separator_thickness',
            [
                'label'         => __('Thickness', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'range'         => [
                    'px'         => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .ee-google-map__navigation__item:not(:last-child) a' => 'border-bottom: {{SIZE}}px solid;',
                ],
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_control(
            'heading_navigation_links',
            [
                'type'        => Controls_Manager::HEADING,
                'label'     => __('Links', 'master-addons'),
                'separator' => 'before',
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_links_spacing',
            [
                'label'         => __('Spacing', 'master-addons'),
                'type'             => Controls_Manager::SLIDER,
                'default'        => [
                    'size'        => 0,
                ],
                'range'         => [
                    'px'         => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors'     => [
                    '{{WRAPPER}} .ee-google-map__navigation__item:not(:last-child)' => 'margin-bottom: {{SIZE}}px;',
                ],
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_control(
            'navigation_links_padding',
            [
                'label'         => __('Padding', 'master-addons'),
                'type'             => Controls_Manager::DIMENSIONS,
                'selectors'     => [
                    '{{WRAPPER}} .ee-google-map__navigation__link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'         => 'navigation_links_typography',
                'label'     => __('Typography', 'master-addons'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector'     => '{{WRAPPER}} .ee-google-map__navigation',
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->add_control(
            'navigation_links_text_align',
            [
                'label'         => __('Align Text', 'master-addons'),
                'type'             => Controls_Manager::CHOOSE,
                'default'         => 'left',
                'options'         => [
                    'left'            => [
                        'title'     => __('Left', 'master-addons'),
                        'icon'         => 'fa fa-align-left',
                    ],
                    'center'         => [
                        'title'     => __('Center', 'master-addons'),
                        'icon'         => 'fa fa-align-center',
                    ],
                    'right'         => [
                        'title'     => __('Right', 'master-addons'),
                        'icon'         => 'fa fa-align-right',
                    ],
                ],
                'condition' => [
                    'navigation!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation__link' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            JLTMA_Transition::get_type(),
            [
                'name'         => 'image',
                'selector'     => '{{WRAPPER}} .ee-google-map__navigation__link',
                'separator'    => '',
            ]
        );

        $this->start_controls_tabs('navigation_tabs');

        $this->start_controls_tab('navigation_default', ['label' => __('Default', 'master-addons')]);

        $this->add_control(
            'navigation_links_color',
            [
                'label'     => __('Color', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'condition' => [
                    'navigation!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation__link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_links_separator_color',
            [
                'label'     => __('Separator Color', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'condition' => [
                    'navigation!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation__item:not(:last-child) a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_links_background',
            [
                'label'     => __('Background', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation__link' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('navigation_hover', ['label' => __('Hover', 'master-addons')]);

        $this->add_control(
            'navigation_links_color_hover',
            [
                'label'     => __('Color', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'condition' => [
                    'navigation!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation__link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_links_separator_color_hover',
            [
                'label'     => __('Separator Color', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'condition' => [
                    'navigation!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation__item:not(:last-child) .ee-google-map__navigation__link:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_links_background_hover',
            [
                'label'     => __('Background', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation__link:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('navigation_current', ['label' => __('Current', 'master-addons')]);

        $this->add_control(
            'navigation_links_color_current',
            [
                'label'     => __('Color', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'condition' => [
                    'navigation!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation__item.ee--is-active .ee-google-map__navigation__link,
							 {{WRAPPER}} .ee-google-map__navigation__item.ee--is-active .ee-google-map__navigation__link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_links_separator_color_current',
            [
                'label'     => __('Separator Color', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'condition' => [
                    'navigation!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation__item.ee--is-active .ee-google-map__navigation__item:not(:last-child) .ee-google-map__navigation__link,
							 {{WRAPPER}} .ee-google-map__navigation__item.ee--is-active .ee-google-map__navigation__item:not(:last-child) .ee-google-map__navigation__link:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_links_background_current',
            [
                'label'     => __('Background', 'master-addons'),
                'type'         => Controls_Manager::COLOR,
                'default'    => '',
                'selectors' => [
                    '{{WRAPPER}} .ee-google-map__navigation__item.ee--is-active .ee-google-map__navigation__link,
							 {{WRAPPER}} .ee-google-map__navigation__item.ee--is-active .ee-google-map__navigation__link:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'navigation!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Render
     *
     * Render widget contents on frontend
     *
     * @since  2.0.0
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if ('' === \MasterAddons\Inc\Admin\Settings\Settings::get_api('google_maps_api_key', false)) {
            echo $this->render_placeholder([
                'body' => __('You have not set your Google Maps API key.', 'master-addons'),
            ]);

            return;
        }

        $this->add_render_attribute([
            'wrapper' => [
                'class' => [
                    'jltma-google-map-wrapper',
                ],
            ],
            'map' => [
                'class' => [
                    'jltma-google-map',
                ],
                'data-lat' => $settings['lat'],
                'data-lng' => $settings['lng'],
            ],
            'title' => [
                'class' => 'jltma-google-map__pin__title',
            ],
            'description' => [
                'class' => 'jltma-google-map__pin__description',
            ],
        ]);

        if (!empty($settings['pins'])) {

?>
            <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
                <?php

                if ('' !== $settings['navigation']) {
                    $this->render_navigation();
                }

                ?>
                <div <?php echo $this->get_render_attribute_string('map'); ?>>

                    <?php foreach ($settings['pins'] as $index => $item) {

                        $key = $this->get_repeater_setting_key('pin', 'pins', $index);
                        $title_key = $this->get_repeater_setting_key('title', 'pins', $index);
                        $description_key = $this->get_repeater_setting_key('description', 'pins', $index);

                        $this->add_render_attribute([
                            $key => [
                                'class' => [
                                    'jltma-google-map__pin',
                                ],
                                'data-trigger'     => $item['trigger'],
                                'data-lat'         => $item['lat'],
                                'data-lng'         => $item['lng'],
                                'data-id'         => $item['_id'],
                            ],
                        ]);

                        if (!empty($item['icon']['url'])) {
                            $this->add_render_attribute($key, [
                                'data-icon' => esc_url($item['icon']['url']),
                            ]);
                        }

                    ?><div <?php echo $this->get_render_attribute_string($key); ?>>
                            <?php if ('' !== $settings['popups']) {

                                // Whitelist allowed HTML tags to prevent XSS
                                $allowed_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
                                $title_tag = in_array($settings['title_tag'], $allowed_tags, true)
                                    ? $settings['title_tag']
                                    : 'h3';
                                $description_tag = in_array($settings['description_tag'], $allowed_tags, true)
                                    ? $settings['description_tag']
                                    : 'p';

                            ?><<?php echo esc_html($title_tag); ?> <?php echo $this->get_render_attribute_string('title'); ?>>
                                    <?php echo esc_html($item['name']); ?>
                                </<?php echo esc_html($title_tag); ?>>
                                <<?php echo esc_html($description_tag); ?> <?php echo $this->get_render_attribute_string('description'); ?>>
                                    <?php echo esc_html($item['description']); ?>
                                </<?php echo esc_html($description_tag); ?>>

                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php

        }
    }

    /**
     * Render Navigation
     *
     * Render widget navigation on frontend
     *
     * @since  2.0.0
     * @return void
     */
    protected function render_navigation()
    {

        $settings = $this->get_settings_for_display();
        $has_icon = false;

        $this->add_render_attribute([
            'navigation-wrapper' => [
                'class' => [
                    'jltma-google-map__navigation',
                ],
            ],
            'navigation' => [
                'class' => [
                    'jltma-nav',
                    'jltma-nav--stacked',
                    'jltma-google-map__navigation__items',
                ],
            ],
            'text' => [
                'class' => [
                    'jltma-google-map__navigation__text'
                ],
            ],
        ]);

        if (!empty($settings['navigation_icon']) || !empty($settings['selected_navigation_icon']['value'])) {
            $this->add_render_attribute('icon', 'class', [
                'jltma-button-icon',
                'jltma-icon',
                'jltma-icon-support--svg',
                'jltma-icon--' . esc_attr($settings['navigation_icon_align']),
            ]);

            $has_icon = true;
        }

        ?><div <?php echo $this->get_render_attribute_string('navigation-wrapper'); ?>>
            <ul <?php echo $this->get_render_attribute_string('navigation'); ?>>
                <?php

                $this->render_all_link($has_icon);

                foreach ($settings['pins'] as $index => $item) {

                    $item_key = $this->get_repeater_setting_key('item', 'pins', $index);
                    $link_key = $this->get_repeater_setting_key('link', 'pins', $index);

                    $this->add_render_attribute([
                        $item_key => [
                            'class' => [
                                'jltma-google-map__navigation__item',
                                'elementor-repeater-item-' . esc_attr($item['_id'])
                            ],
                            'data-id' => $item['_id']
                        ],
                        $link_key => [
                            'class' => [
                                'jltma-google-map__navigation__link',
                                'jltma-button',
                                'jltma-button-link',
                            ],
                        ],
                    ]);
                ?>
                    <li <?php echo $this->get_render_attribute_string($item_key); ?>>
                        <a <?php echo $this->get_render_attribute_string($link_key); ?>>
                            <?php
                            if ($has_icon) {
                                $this->render_navigation_icon();
                            }
                            ?>
                            <span <?php echo $this->get_render_attribute_string('text'); ?>>
                                <?php echo esc_html($item['name']); ?>
                            </span>
                        </a>
                    </li>
                <?php
                } ?>

            </ul>
        </div><?php
            }

            /**
             * Render Navigation Icon
             *
             * @return void
             */
            protected function render_navigation_icon()
            {
                $settings = $this->get_settings();

                $migrated = isset($settings['__fa4_migrated']['selected_navigation_icon']);
                $is_new = empty($settings['navigation_icon']) && Icons_Manager::is_migration_allowed();

                ?>
        <span <?php echo $this->get_render_attribute_string('icon'); ?>>
            <?php
                if ($is_new || $migrated) {
                    Icons_Manager::render_icon($settings['selected_navigation_icon'], ['aria-hidden' => 'true']);
                } else {
            ?>
                <i class="<?php echo esc_attr($settings['navigation_icon']); ?>" aria-hidden="true"></i>
            <?php
                }
            ?></span><?php
                    }

                    /**
                     * Render All Link
                     *
                     * Render widget navigations' "all" link
                     *
                     * @since  2.0.0
                     * @return void
                     */
                    protected function render_all_link($icon = false)
                    {
                        $settings = $this->get_settings_for_display();

                        $this->add_render_attribute([
                            'all' => [
                                'class' => [
                                    'jltma-google-map__navigation__item',
                                    'jltma-google-map__navigation__item--all',
                                ],
                            ],
                            'link' => [
                                'class' => [
                                    'jltma-google-map__navigation__link',
                                    'jltma-button',
                                    'jltma-button-link',
                                ],
                            ],
                        ]);

                        ?>
        <li <?php echo $this->get_render_attribute_string('all'); ?>>
            <a <?php echo $this->get_render_attribute_string('link'); ?>>
                <?php

                        if ($icon) {
                            $this->render_navigation_icon();
                        }

                ?>
                <span <?php echo $this->get_render_attribute_string('text'); ?>>
                    <?php echo esc_html($settings['all_text']); ?>
                </span>
            </a>
        </li>

<?php
                    }

                    /**
                     * Content Template
                     *
                     * Javascript content template for quick rendering. None in this case
                     *
                     * @since  2.0.0
                     * @return void
                     */
                    protected function content_template()
                    {
                    }
                }
