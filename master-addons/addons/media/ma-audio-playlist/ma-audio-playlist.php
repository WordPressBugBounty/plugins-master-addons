<?php

namespace MasterAddons\Addons;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

use MasterAddons\Inc\Admin\Config;
use MasterAddons\Inc\Classes\Base\Master_Widget;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Addon audio widget.
 *
 */
class Audio_Playlist extends Master_Widget
{
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

    /**
     * Get widget name.
     *
     * Retrieve audio widget name.
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'jltma-audio-playlist';
    }

    /**
     * Get widget title.
     *
     * Retrieve audio widget title.
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Audio Playlist', 'master-addons' );
    }

    /**
     * Get widget icon.
     *
     * Retrieve audio widget icon.
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
     * @return array Widget unique keywords.
     */
    public function get_unique_keywords()
    {
        return array(
            'audio',
            'player',
            'playlist',
            'embed',
        );
    }

    /**
     * Retrieve the list of scripts the audio playlist widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @return array Widget scripts dependencies.
     */
        /**
     * Register toggle widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     * @since 1.1.0 Added dynamic for `Title`, `Subtitle` and
     * audio `Links` item controls. Disabled options for 'URL' control.
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_audio',
            array(
                'label' => __('Audio Playlist', 'master-addons' ),
            )
        );

        $repeater = new Repeater();

        /* Start Tab Items Tabs */
        $repeater->start_controls_tabs(
            'audio_items_tabs',
            array()
        );

        /* Start Tab Item Audio Tab */
        $repeater->start_controls_tab(
            'audio_items_tab_audio',
            array(
                'label' => __('Audio', 'master-addons' ),
            )
        );

        $repeater->add_control(
            'insert_url',
            array(
                'label' => __('External URL', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
            )
        );

        $repeater->add_control(
            'hosted_url',
            array(
                'label' => __('Choose File', 'master-addons' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => array(
                    'active' => true,
                    'categories' => array(TagsModule::MEDIA_CATEGORY),
                ),
                'media_type' => 'audio',
                'description' => 'Supported Audio File Formats: MP3, WAV and OGG',
                'condition' => array('insert_url' => ''),
            )
        );

        $repeater->add_control(
            'external_url',
            array(
                'label' => esc_html__('URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                    'categories' => array(
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY,
                    ),
                ),
                'options' => false,
                'show_external' => false,
                'label_block' => true,
                'condition' => array('insert_url' => 'yes'),
                'description' => 'Supported Audio File Formats: MP3, WAV and OGG',
            )
        );

        $repeater->add_control(
            'track_title',
            array(
                'label' => esc_html__('Title', 'master-addons' ),
                'label_block' => true,
                'dynamic' => array(
                    'active' => true,
                ),
                'type' => Controls_Manager::TEXT,
            )
        );

        $repeater->add_control(
            'track_subtitle',
            array(
                'label' => esc_html__('Subtitle', 'master-addons' ),
                'label_block' => true,
                'dynamic' => array(
                    'active' => true,
                ),
                'type' => Controls_Manager::TEXT,
            )
        );

        $repeater->end_controls_tab();

        /* Start Tab Item Links Tab */
        $repeater->start_controls_tab(
            'audio_items_tab_links',
            array(
                'label' => __('Links', 'master-addons' ),
            )
        );

        $repeater->add_control(
            'audio_source',
            array(
                'label' => __('Source', 'master-addons' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => array(
                    'amazon' => __('Amazon', 'master-addons' ),
                    'apple' => __('Apple', 'master-addons' ),
                    'google' => __('Google', 'master-addons' ),
                    'radiopublic' => __('RadioPublic', 'master-addons' ),
                    'rss' => __('RSS', 'master-addons' ),
                    'soundcloud' => __('SoundCloud', 'master-addons' ),
                    'spotify' => __('Spotify', 'master-addons' ),
                    'tunein' => __('TuneIn', 'master-addons' ),
                    'custom_1' => __('Custom 1', 'master-addons' ),
                    'custom_2' => __('Custom 2', 'master-addons' ),
                    'custom_3' => __('Custom 3', 'master-addons' ),
                ),
                'multiple' => true,
                'default' => array(
                    'amazon',
                    'apple',
                ),
                'separator' => 'before',
            )
        );

        $repeater->add_control(
            'audio_amazon_url',
            array(
                'label' => __('Amazon URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'amazon'),
            )
        );

        $repeater->add_control(
            'audio_apple_url',
            array(
                'label' => __('Apple URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'apple'),
            )
        );

        $repeater->add_control(
            'audio_google_url',
            array(
                'label' => __('Google URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'google'),
            )
        );

        $repeater->add_control(
            'audio_radiopublic_url',
            array(
                'label' => __('RadioPublic URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'radiopublic'),
            )
        );

        $repeater->add_control(
            'audio_rss_url',
            array(
                'label' => __('RSS URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'rss'),
            )
        );

        $repeater->add_control(
            'audio_soundcloud_url',
            array(
                'label' => __('SoundCloud URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'soundcloud'),
            )
        );

        $repeater->add_control(
            'audio_spotify_url',
            array(
                'label' => __('Spotify URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'spotify'),
            )
        );

        $repeater->add_control(
            'audio_tunein_url',
            array(
                'label' => __('TuneIn URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'tunein'),
            )
        );

        $repeater->add_control(
            'audio_custom_1_url',
            array(
                'label' => __('Custom 1 URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'custom_1'),
            )
        );

        $repeater->add_control(
            'audio_custom_2_url',
            array(
                'label' => __('Custom 2 URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'custom_2'),
            )
        );

        $repeater->add_control(
            'audio_custom_3_url',
            array(
                'label' => __('Custom 3 URL', 'master-addons' ),
                'type' => Controls_Manager::URL,
                'dynamic' => array(
                    'active' => true,
                ),
                'show_external' => false,
                'condition' => array('audio_source' => 'custom_3'),
            )
        );

        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'audio_list',
            array(
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => array(
                    array(
                        'title' => '',
                        'hosted_url' => array(
                            'url' => 'http://mihalich-themes.net/wp-content/uploads/2019/12/Twenty-one-pilots.mp3',
                        ),
                    ),
                    array(
                        'title' => '',
                        'hosted_url' => array(
                            'url' => 'http://mihalich-themes.net/wp-content/uploads/2019/12/Twenty-one-pilots.mp3',
                        ),
                    ),
                ),
                'title_field' => '<# if ( \'\' === track_title ) { if ( \'\' !== track_subtitle  ) { #> {{{ track_subtitle }}} <span class="jltma-repeat-item-num hidden"></span><# } else { #>Audio Track <span class="jltma-repeat-item-num"></span> <# } } else { #> {{{ track_title }}} <# if ( \'\' !== track_subtitle ) { #> - {{{ track_subtitle }}} <# } #> <span class="jltma-repeat-item-num hidden"></span><# } #>',
            )
        );

        $this->add_control(
            'audio_size',
            array(
                'label' => __('Player Size', 'master-addons' ),
                'type' => 'jltma-choose-text',
                'options' => array(
                    'small' => __('Small', 'master-addons' ),
                    'medium' => __('Medium', 'master-addons' ),
                ),
                'default' => 'medium',
                'label_block' => false,
                'toggle' => false,
                'prefix_class' => 'jltma-audio-size-',
                'render_type' => 'template',
                'frontend_available' => true,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'audio_playlist_type',
            array(
                'label' => __('Playlist Type', 'master-addons' ),
                'type' => 'jltma-choose-text',
                'label_block' => false,
                'options' => array(
                    'static' => array('title' => __('Static', 'master-addons' )),
                    'toggle' => array('title' => __('Toggle', 'master-addons' )),
                ),
                'default' => 'static',
                'toggle' => false,
                'prefix_class' => 'jltma-audio-playlist-type-',
                'frontend_available' => true,
                'render_type' => 'template',
                'separator' => 'before',
            )
        );

        $this->add_control(
            'audio_player_separator',
            array(
                'label' => __('Separator', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => array('audio_size' => 'small'),
                'prefix_class' => 'jltma-player-sep-',
                'render_type' => 'template',
            )
        );

        $this->add_control(
            'audio_poster',
            array(
                'label' => __('Poster', 'master-addons' ),
                'type' => Controls_Manager::MEDIA,
                'default' => array('url' => Utils::get_placeholder_image_src()),
                'dynamic' => array('active' => true),
                'separator' => 'before',
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name' => 'audio_poster',
                'default' => 'full',
                'separator' => 'none',
                'condition' => array(
                    'audio_size' => 'medium',
                    'audio_poster[id]!' => '',
                ),
            )
        );

        $this->add_control(
            'audio_poster_position',
            array(
                'label' => __('Position', 'master-addons' ),
                'type' => 'jltma-choose-text',
                'options' => array(
                    'singly' => __('Side', 'master-addons' ),
                    'with_title' => __('With Title', 'master-addons' ),
                    'top' => __('Top', 'master-addons' ),
                ),
                'default' => 'singly',
                'toggle' => false,
                'prefix_class' => 'jltma-poster-position-',
                'render_type' => 'template',
                'condition' => array(
                    'audio_size' => 'medium',
                    'audio_poster[id]!' => '',
                ),
            )
        );

        $this->add_responsive_control(
            'audio_poster_width',
            array(
                'label' => __('Width', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'range' => array(
                    'px' => array(
                        'min' => 50,
                        'max' => 1000,
                        'step' => 10,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}.jltma-poster-position-singly .elementor-widget-jltma-audio-playlist__player_left' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-poster-position-with_title .elementor-widget-jltma-audio-playlist__poster' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-poster-position-top .elementor-widget-jltma-audio-playlist__player_left' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'audio_size' => 'medium',
                    'audio_poster[id]!' => '',
                ),
            )
        );

        $this->end_controls_section();

        // Started Container Style Controls
        $this->start_controls_section(
            'section_audio_container',
            array(
                'label' => esc_html__('Container', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'audio_container_bg',
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__player-bg',
                'fields_options' => array(
                    'color' => array(
                        'selectors' => array(
                            '{{SELECTOR}}' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__advanced_inner' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__speed-variations' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__volume-progress-wrap' => 'background-color: {{VALUE}};',
                        ),
                    ),
                    'background' => array(
                        'description' => 'When choosing the Background Image the Color option should be also set.  Color will be applied as the background for Advanced and Volume block',
                    ),
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            array(
                'name' => 'audio_container_css_filters',
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__player-bg',
                'condition' => array(
                    'audio_container_bg_background' => array('classic', 'gradient'),
                ),
            )
        );

        $this->add_control(
            'audio_container_overlay_blend_mode',
            array(
                'label' => __('Blend Mode', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => array(
                    '' => __('Normal', 'master-addons' ),
                    'multiply' => 'Multiply',
                    'screen' => 'Screen',
                    'overlay' => 'Overlay',
                    'darken' => 'Darken',
                    'lighten' => 'Lighten',
                    'color-dodge' => 'Color Dodge',
                    'saturation' => 'Saturation',
                    'color' => 'Color',
                    'luminosity' => 'Luminosity',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__player-bg' => 'mix-blend-mode: {{VALUE}}',
                ),
                'condition' => array(
                    'audio_container_bg_background' => array('classic', 'gradient'),
                ),
            )
        );

        $this->add_control(
            'audio_container_bg_overlay',
            array(
                'label' => __('Background Color Overlay', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__player-bg-overlay' => 'background-color: {{VALUE}}',
                ),
                'condition' => array(
                    'audio_container_bg_background' => array('classic', 'gradient'),
                ),
            )
        );

        $this->add_responsive_control(
            'audio_container_padding',
            array(
                'label' => esc_html__('Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_inner' => 'padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist-list' => 'padding-bottom: {{BOTTOM}}{{UNIT}};',

                    '{{WRAPPER}}.jltma-audio-size-medium .elementor-widget-jltma-audio-playlist__player' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__player > *' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__player > *:first-child' => 'padding-left: {{LEFT}}{{UNIT}} !important;',
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__player > *:last-child' => 'padding-right: {{RIGHT}}{{UNIT}} !important;',
                ),
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name' => 'audio_container_border',
                'label' => __('Border', 'master-addons' ),
                'fields_options' => array(
                    'color' => array(
                        'label' => _x('Border Color', 'Border Control', 'master-addons' ),
                    ),
                ),
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__player-bg',
            )
        );

        $this->add_control(
            'audio_container_border_radius',
            array(
                'label' => __('Border Radius', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__player-bg,
					{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist.absolute' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'audio_container_separator',
            array(
                'label' => __('Separator', 'master-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'audio_size' => 'small',
                    'audio_player_separator' => 'yes',
                ),
            )
        );

        $this->add_control(
            'audio_container_separator_border_width',
            array(
                'label' => __('Width', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 5,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__player > *:not(:last-child)' => 'border-right-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__playlist_inner' => 'border-top-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__playlist_item.jltma-playlist-item-separator:after' => 'height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'audio_size' => 'small',
                    'audio_player_separator' => 'yes',
                ),
            )
        );

        $this->add_control(
            'audio_container_separator_border_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__player > *:not(:last-child)' => 'border-right-color: {{VALUE}};',
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__playlist_inner' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__playlist_item.jltma-playlist-item-separator:after' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'audio_size' => 'small',
                    'audio_player_separator' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        // Started Poster Style Controls
        $this->start_controls_section(
            'section_audio_poster',
            array(
                'label' => esc_html__('Poster', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => array(
                    'audio_size' => 'medium',
                    'audio_poster[id]!' => '',
                ),
            )
        );

        $this->add_control(
            'audio_poster_h_align',
            array(
                'label' => __('Align', 'master-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'master-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'right' => array(
                        'title' => __('Right', 'master-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ),
                ),
                'default' => 'left',
                'toggle' => false,
                'prefix_class' => 'jltma-poster-align-',
                'selectors_dictionary' => array(
                    'left' => 'row;',
                    'right' => 'row-reverse;',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.jltma-poster-position-singly .elementor-widget-jltma-audio-playlist__player' => 'flex-direction: {{VALUE}};',
                    '{{WRAPPER}}.jltma-poster-position-with_title .elementor-widget-jltma-audio-playlist__player_left' => 'flex-direction: {{VALUE}};',
                ),
                'condition' => array('audio_poster_position!' => 'top'),
            )
        );

        $this->add_responsive_control(
            'audio_poster_gap',
            array(
                'label' => __('Gap', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 150,
                    ),
                ),
                'selectors' => array(
                    '(desktop+){{WRAPPER}}.jltma-poster-position-singly.jltma-poster-align-left .elementor-widget-jltma-audio-playlist__player_left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.jltma-poster-position-singly.jltma-poster-align-right .elementor-widget-jltma-audio-playlist__player_left' => 'margin-left: {{SIZE}}{{UNIT}};',

                    '(desktop+){{WRAPPER}}.jltma-poster-position-with_title.jltma-poster-align-left .elementor-widget-jltma-audio-playlist__player_left .elementor-widget-jltma-audio-playlist__poster' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '(desktop+){{WRAPPER}}.jltma-poster-position-with_title.jltma-poster-align-right .elementor-widget-jltma-audio-playlist__player_left .elementor-widget-jltma-audio-playlist__poster' => 'margin-left: {{SIZE}}{{UNIT}};',

                    '(desktop+){{WRAPPER}}.jltma-poster-position-top .elementor-widget-jltma-audio-playlist__player_left .elementor-widget-jltma-audio-playlist__poster' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'device_args' => array(
                    Controls_Stack::RESPONSIVE_TABLET => array(
                        'selectors' => array(
                            '(tablet+)(tablet-){{WRAPPER}}.jltma-poster-position-singly.jltma-poster-align-left .elementor-widget-jltma-audio-playlist__player_left' => 'margin-right: {{SIZE}}{{UNIT}};',
                            '(tablet+)(tablet-){{WRAPPER}}.jltma-poster-position-singly.jltma-poster-align-right .elementor-widget-jltma-audio-playlist__player_left' => 'margin-left: {{SIZE}}{{UNIT}};',

                            '(tablet+)(tablet-){{WRAPPER}}.jltma-poster-position-with_title.jltma-poster-align-left .elementor-widget-jltma-audio-playlist__player_left .elementor-widget-jltma-audio-playlist__poster' => 'margin-right: {{SIZE}}{{UNIT}};',
                            '(tablet+)(tablet-){{WRAPPER}}.jltma-poster-position-with_title.jltma-poster-align-right .elementor-widget-jltma-audio-playlist__player_left .elementor-widget-jltma-audio-playlist__poster' => 'margin-left: {{SIZE}}{{UNIT}};',

                            '(tablet+)(tablet-){{WRAPPER}}.jltma-poster-position-top .elementor-widget-jltma-audio-playlist__player_left .elementor-widget-jltma-audio-playlist__poster' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ),
                    ),
                    Controls_Stack::RESPONSIVE_MOBILE => array(
                        'selectors' => array(
                            '(mobile-){{WRAPPER}}.jltma-poster-position-singly .elementor-widget-jltma-audio-playlist__player_left' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                            '(mobile-){{WRAPPER}}.jltma-poster-position-with_title .elementor-widget-jltma-audio-playlist__player_left .elementor-widget-jltma-audio-playlist__poster' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                            '(mobile-){{WRAPPER}}.jltma-poster-position-top .elementor-widget-jltma-audio-playlist__player_left .elementor-widget-jltma-audio-playlist__poster' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                        ),
                    ),
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name' => 'audio_poster_border',
                'label' => __('Border', 'master-addons' ),
                'fields_options' => array(
                    'color' => array(
                        'label' => _x('Border Color', 'Border Control', 'master-addons' ),
                    ),
                ),
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__poster',
            )
        );

        $this->add_control(
            'audio_poster_border_radius',
            array(
                'label' => __('Border Radius', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__poster' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // Started Audio Track Name Info Style Controls
        $this->start_controls_section(
            'section_audio_track_name_info',
            array(
                'label' => esc_html__('Track Name Info', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_control(
            'audio_track_name_info_one_line',
            array(
                'label' => __('In a row', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'render_type' => 'template',
                'prefix_class' => 'jltma-track-name-info-one-line-',
            )
        );

        $this->add_control(
            'audio_track_name_info_title_count',
            array(
                'label' => __('Track title line count', 'master-addons' ),
                'type' => 'jltma-choose-text',
                'options' => array(
                    '1' => __('1', 'master-addons' ),
                    '2' => __('2', 'master-addons' ),
                ),
                'default' => '1',
                'label_block' => false,
                'toggle' => false,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track_name_info_inner .elementor-widget-jltma-audio-playlist__track-name-title' => '-webkit-line-clamp: {{VALUE}};',
                ),
                'condition' => array('audio_track_name_info_one_line' => ''),
            )
        );

        $this->add_control(
            'audio_track_name_info_v_align',
            array(
                'label' => __('Vertical Align', 'master-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'top' => array(
                        'title' => __('Top', 'master-addons' ),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'middle' => array(
                        'title' => __('Middle', 'master-addons' ),
                        'icon' => 'eicon-v-align-middle',
                    ),
                    'bottom' => array(
                        'title' => __('Bottom', 'master-addons' ),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                ),
                'default' => 'middle',
                'toggle' => false,
                'selectors_dictionary' => array(
                    'top' => 'flex-start',
                    'middle' => 'center',
                    'bottom' => 'flex-end',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.jltma-poster-position-with_title .elementor-widget-jltma-audio-playlist__track_name_info' => 'align-self: {{VALUE}};',
                ),
                'condition' => array(
                    'audio_poster[id]!' => '',
                    'audio_poster_position' => 'with_title',
                ),
            )
        );

        $this->add_control(
            'audio_track_name_info_h_singly_align',
            array(
                'label' => __('Align', 'master-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'master-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'right' => array(
                        'title' => __('Right', 'master-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ),
                ),
                'default' => 'left',
                'toggle' => false,
                'prefix_class' => 'jltma-track-name-info-h-align-',
                'selectors_dictionary' => array(
                    'left' => 'text-align: left;',
                    'right' => 'text-align: right;',
                ),
                'selectors' => array(
                    '{{WRAPPER}}:not(.jltma-poster-position-top) .elementor-widget-jltma-audio-playlist__track_name_info_inner' => '{{VALUE}}',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name' => 'audio_poster[id]',
                            'operator' => '=',
                            'value' => '',
                        ),
                        array(
                            'name' => 'audio_poster_position',
                            'operator' => '!==',
                            'value' => 'top',
                        ),
                    ),
                ),
            )
        );

        $this->add_control(
            'audio_track_name_info_h_top_align',
            array(
                'label' => __('Align', 'master-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => __('Left', 'master-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => __('Center', 'master-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ),
                    'right' => array(
                        'title' => __('Right', 'master-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ),
                ),
                'default' => 'left',
                'toggle' => false,
                'prefix_class' => 'jltma-track-name-info-h-align-',
                'selectors_dictionary' => array(
                    'left' => 'text-align: left;',
                    'center' => 'text-align: center;',
                    'right' => 'text-align: right;',
                ),
                'selectors' => array(
                    '{{WRAPPER}}:not(.jltma-poster-position-with_title) .elementor-widget-jltma-audio-playlist__track_name_info_inner' => '{{VALUE}}',
                ),
                'condition' => array(
                    'audio_poster[id]!' => '',
                    'audio_poster_position' => 'top',
                ),
            )
        );

        $this->add_responsive_control(
            'audio_track_name_info_ver_gap',
            array(
                'label' => __('Gap', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px'),
                'allowed_dimensions' => 'vertical',
                'placeholder' => array(
                    'top' => '',
                    'right' => 'auto',
                    'bottom' => '',
                    'left' => 'auto',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track_name_info' => 'margin-top: {{TOP}}{{UNIT}};',
                    '{{WRAPPER}} .jltma-empty-poster .elementor-widget-jltma-audio-playlist__track_name_info' => 'margin-bottom: {{BOTTOM}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-poster-position-singly .elementor-widget-jltma-audio-playlist__track_name_info' => 'margin-bottom: {{BOTTOM}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-poster-position-with_title .elementor-widget-jltma-audio-playlist__player_left' => 'margin-bottom: {{BOTTOM}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-poster-position-top .elementor-widget-jltma-audio-playlist__track_name_info' => 'margin-bottom: {{BOTTOM}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'audio_track_name_info_typography',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track_name_info_inner',
                ),
                'condition' => array('audio_track_name_info_one_line' => 'yes'),
            )
        );

        $this->add_control(
            'audio_track_name_info_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track_name_info_inner' => 'color: {{VALUE}}',
                ),
                'condition' => array('audio_track_name_info_one_line' => 'yes'),
            )
        );

        $this->start_controls_tabs(
            'tabs_audio_track_name_info_style',
            array(
                'separator' => 'before',
                'condition' => array('audio_track_name_info_one_line' => ''),
            )
        );

        $this->start_controls_tab(
            'tab_audio_track_name_info_title_style',
            array(
                'label' => esc_html__('Title', 'master-addons' ),
            )
        );

        $this->add_control(
            'audio_track_name_info_title_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-name-title' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'audio_track_name_info_title_typography',
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-name-title',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_audio_track_name_info_subtitle_style',
            array(
                'label' => esc_html__('Subtitle', 'master-addons' ),
            )
        );

        $this->add_control(
            'audio_track_name_info_subtitle_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-name-subtitle' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'audio_track_name_info_subtitle_typography',
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-name-subtitle',
            )
        );

        $this->add_responsive_control(
            'audio_track_name_info_subtitle_gap',
            array(
                'label' => __('Gap Between', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}:not(.jltma-track-name-info-one-line-yes) .elementor-widget-jltma-audio-playlist__track-name-subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Started Control Button Style Controls
        $this->start_controls_section(
            'section_audio_control_buttons',
            array(
                'label' => esc_html__('Control Buttons', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'audio_control_button_prev_next',
            array(
                'label' => __('Prev & Next', 'master-addons' ),
                'type' => 'jltma-choose-text',
                'options' => array(
                    'none' => array('title' => __('None', 'master-addons' )),
                    'next' => array('title' => __('Next', 'master-addons' )),
                    'both' => array('title' => __('Both', 'master-addons' )),
                ),
                'default' => 'both',
                'label_block' => true,
                'toggle' => false,
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_control(
            'audio_control_button_backward_forward',
            array(
                'label' => __('Backward & Forward', 'master-addons' ),
                'type' => 'jltma-choose-text',
                'options' => array(
                    'none' => array('title' => __('None', 'master-addons' )),
                    'forward' => array('title' => __('Forward', 'master-addons' )),
                    'both' => array('title' => __('Both', 'master-addons' )),
                ),
                'default' => 'none',
                'label_block' => true,
                'toggle' => false,
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_control(
            'audio_control_button_backward_size',
            array(
                'label' => __('Backward Size', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'options' => array(
                    '3' => __('3 sec', 'master-addons' ),
                    '5' => __('5 sec', 'master-addons' ),
                    '10' => __('10 sec', 'master-addons' ),
                    '15' => __('15 sec', 'master-addons' ),
                    '30' => __('30 sec', 'master-addons' ),
                    '60' => __('1 min', 'master-addons' ),
                    '120' => __('2 min', 'master-addons' ),
                    '180' => __('3 min', 'master-addons' ),
                ),
                'frontend_available' => true,
                'condition' => array(
                    'audio_size' => 'medium',
                    'audio_control_button_backward_forward' => 'both',
                ),
            )
        );

        $this->add_control(
            'audio_control_button_forward_size',
            array(
                'label' => __('Forward Size', 'master-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => '3',
                'options' => array(
                    '3' => __('3 sec', 'master-addons' ),
                    '5' => __('5 sec', 'master-addons' ),
                    '10' => __('10 sec', 'master-addons' ),
                    '15' => __('15 sec', 'master-addons' ),
                    '30' => __('30 sec', 'master-addons' ),
                    '60' => __('1 min', 'master-addons' ),
                    '120' => __('2 min', 'master-addons' ),
                    '180' => __('3 min', 'master-addons' ),
                ),
                'frontend_available' => true,
                'condition' => array(
                    'audio_size' => 'medium',
                    'audio_control_button_backward_forward!' => 'none',
                ),
            )
        );

        $this->add_control(
            'audio_control_button_volume',
            array(
                'label' => __('Volume', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => 'The volume can be changed on the frontend only',
            )
        );

        $this->add_control(
            'audio_control_button_loop',
            array(
                'label' => __('Loop', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_control(
            'audio_control_button_shuffle',
            array(
                'label' => __('Shuffle', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_responsive_control(
            'audio_control_buttons_font_size',
            array(
                'label' => __('Size', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 50,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--buttons-font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'audio_control_medium_buttons_play_font_size',
            array(
                'label' => __('Play Size', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 3,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--medium-buttons-play-font-size: {{SIZE}};',
                ),
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_responsive_control(
            'audio_control_small_buttons_play_font_size',
            array(
                'label' => __('Play Size', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 3,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--buttons-play-font-size: {{SIZE}};',
                ),
                'condition' => array('audio_size' => 'small'),
            )
        );

        $this->add_responsive_control(
            'audio_control_buttons_medium_gap',
            array(
                'label' => __('Gap Between', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 3,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}.jltma-audio-size-medium .elementor-widget-jltma-audio-playlist__controls-button' => 'margin: 0 {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-audio-size-medium .elementor-widget-jltma-audio-playlist__volume-wrap' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ),
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_responsive_control(
            'audio_control_buttons_h_small_gap',
            array(
                'label' => __('Horizontal Gap Between', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 40,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__player > *' => 'padding-left: calc( {{SIZE}}{{UNIT}} / 2 ); padding-right: calc( {{SIZE}}{{UNIT}} / 2 );',
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__player > *:first-child' => 'padding-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__player > *:last-child' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-audio-size-small.jltma-player-sep-yes .elementor-widget-jltma-audio-playlist__player > *' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array('audio_size' => 'small'),
            )
        );

        $this->add_responsive_control(
            'audio_control_buttons_v_small_gap',
            array(
                'label' => __('Vertical Gap', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 2,
                        'max' => 30,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}.jltma-audio-size-small .elementor-widget-jltma-audio-playlist__player > *' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-audio-size-small.jltma-player-sep-yes .elementor-widget-jltma-audio-playlist__player > *' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array('audio_size' => 'small'),
            )
        );

        $this->start_controls_tabs(
            'tabs_audio_control_buttons_color',
            array(
                'separator' => 'before',
            )
        );

        $this->start_controls_tab(
            'tab_audio_control_buttons_color_normal',
            array(
                'label' => esc_html__('Normal', 'master-addons' ),
            )
        );

        $this->add_control(
            'audio_control_buttons_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__controls-button' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__controls-button.jltma-button-off-active:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__controls-button.jltma-button-off-active.jltma-active-button' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__advanced-icon' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_audio_control_buttons_hover_color',
            array(
                'label' => esc_html__('Hover', 'master-addons' ),
            )
        );

        $this->add_control(
            'audio_control_buttons_hover_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__controls-button:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__advanced-icon:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__volume-inner:hover > .elementor-widget-jltma-audio-playlist__controls-button' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_audio_control_buttons_active_color',
            array(
                'label' => esc_html__('Active', 'master-addons' ),
            )
        );

        $this->add_control(
            'audio_control_buttons_active_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__controls-button.jltma-active-button' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .jltma-advanced-opened .elementor-widget-jltma-audio-playlist__advanced-icon' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'audio_control_buttons_text_shadow',
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__controls-button > i, {{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-name-title, {{WRAPPER}} .elementor-widget-jltma-audio-playlist__advanced > i',
                'fields_options' => array(
                    'text_shadow_type' => array('label' => _x('Shadow', 'Text Shadow', 'master-addons' )),
                ),
            )
        );

        $this->end_controls_section();

        // Started Progress Container Style Controls
        $this->start_controls_section(
            'section_audio_progress',
            array(
                'label' => esc_html__('Progress Container', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'audio_progress_gap',
            array(
                'label' => __('Gap', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 50,
                    ),
                ),
                'separator' => 'after',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__progress-container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_control(
            'audio_progress_container',
            array(
                'label' => __('Progress', 'master-addons' ),
                'type' => Controls_Manager::HEADING,
            )
        );

        $this->add_control(
            'audio_progress_external_color',
            array(
                'label' => __('External Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__progress-inner' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__volume-progress:before' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'audio_progress_inner_color',
                'selector' => '
					{{WRAPPER}} .elementor-widget-jltma-audio-playlist__progress-inner > div,
					{{WRAPPER}} .elementor-widget-jltma-audio-playlist__progress-inner > div:before,
					{{WRAPPER}} .elementor-widget-jltma-audio-playlist__volume-progress > div,
					{{WRAPPER}} .elementor-widget-jltma-audio-playlist__volume-progress > span
				',
                'fields_options' => array(
                    'background' => array(
                        'label' => _x('Inner Background', 'Background Control', 'master-addons' ),
                    ),
                ),
                'exclude' => array(
                    'image',
                    'position',
                    'xpos',
                    'ypos',
                    'attachment',
                    'attachment_alert',
                    'repeat',
                    'size',
                    'bg_width',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name' => 'audio_progress_bd',
                'label' => __('Border', 'master-addons' ),
                'fields_options' => array(
                    'width' => array(
                        'selectors' => array(
                            '{{SELECTOR}}' => '--progress-bd-top-width: {{TOP}}{{UNIT}}; --progress-bd-right-width: {{RIGHT}}{{UNIT}}; --progress-bd-bottom-width: {{BOTTOM}}{{UNIT}}; --progress-bd-left-width: {{LEFT}}{{UNIT}};',
                        ),
                    ),
                    'color' => array(
                        'label' => _x('Border Color', 'Border Control', 'master-addons' ),
                    ),
                ),
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__progress',
            )
        );

        $this->add_control(
            'audio_progress_bd_radius',
            array(
                'label' => __('Border Radius', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array(
                    'px',
                    '%',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__progress,
					{{WRAPPER}} .elementor-widget-jltma-audio-playlist__progress > div,
					{{WRAPPER}} .elementor-widget-jltma-audio-playlist__progress > div > div' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'audio_progress_height',
            array(
                'label' => __('Height', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 6,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--progress-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name' => 'audio_progress_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__progress-inner',
            )
        );

        $this->add_control(
            'audio_progress_current_total_time',
            array(
                'label' => __('Current & Total Time', 'master-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'audio_progress_current_total_time_show',
            array(
                'label' => __('Show', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            )
        );

        $this->add_control(
            'audio_progress_current_total_time_position',
            array(
                'label' => __('Position', 'master-addons' ),
                'type' => 'jltma-choose-text',
                'options' => array(
                    'top' => array(
                        'title' => __('Top', 'master-addons' ),
                    ),
                    'inside' => array(
                        'title' => __('Inside', 'master-addons' ),
                    ),
                    'bottom' => array(
                        'title' => __('Bottom', 'master-addons' ),
                    ),
                ),
                'default' => 'top',
                'label_block' => false,
                'toggle' => false,
                'prefix_class' => 'jltma-current-total-time-position-',
                'render_type' => 'template',
                'condition' => array(
                    'audio_size' => 'medium',
                    'audio_progress_current_total_time_show' => 'yes',
                ),
            )
        );

        $this->add_control(
            'audio_progress_current_total_time_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__current-time' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__total-time' => 'color: {{VALUE}};',
                ),
                'condition' => array('audio_progress_current_total_time_show' => 'yes'),
            )
        );

        $this->add_responsive_control(
            'audio_progress_current_total_time_size',
            array(
                'label' => __('Size', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 6,
                        'max' => 30,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__current-time' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__total-time' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__progress-time' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array('audio_progress_current_total_time_show' => 'yes'),
            )
        );

        $this->add_responsive_control(
            'audio_progress_current_total_time_gap',
            array(
                'label' => __('Gap', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 6,
                        'max' => 15,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}' => '--time-gap: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'audio_progress_current_total_time_show' => 'yes',
                    'audio_size' => 'medium',
                ),
            )
        );

        $this->end_controls_section();

        // Started Advanced Style Controls
        $this->start_controls_section(
            'section_audio_advanced',
            array(
                'label' => esc_html__('Advanced', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_control(
            'audio_advanced_speed',
            array(
                'label' => __('Speed', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'audio_advanced_download',
            array(
                'label' => __('Download', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'audio_advanced_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__advanced_inner' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__speed-button' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__speed-title-wrap' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__speed-rate' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__download' => 'color: {{VALUE}}',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name' => 'audio_advanced_speed',
                            'operator' => '=',
                            'value' => 'yes',
                        ),
                        array(
                            'name' => 'audio_advanced_download',
                            'operator' => '=',
                            'value' => 'yes',
                        ),
                    ),
                ),
            )
        );

        $this->add_control(
            'audio_advanced_hover_color',
            array(
                'label' => __('Hover & Active Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__speed-button:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__speed-button.jltma-choose-speed' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__speed-title-wrap:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__speed-title' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__download:hover' => 'color: {{VALUE}}',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name' => 'audio_advanced_speed',
                            'operator' => '=',
                            'value' => 'yes',
                        ),
                        array(
                            'name' => 'audio_advanced_download',
                            'operator' => '=',
                            'value' => 'yes',
                        ),
                    ),
                ),
            )
        );

        $this->add_responsive_control(
            'audio_advanced_padding',
            array(
                'label' => __('Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__advanced_inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => array(
                        array(
                            'name' => 'audio_advanced_speed',
                            'operator' => '=',
                            'value' => 'yes',
                        ),
                        array(
                            'name' => 'audio_advanced_download',
                            'operator' => '=',
                            'value' => 'yes',
                        ),
                    ),
                ),
            )
        );

        $this->add_responsive_control(
            'audio_advanced_item_gap',
            array(
                'label' => __('Item Gap', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 10,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__advanced_inner > * + *' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'audio_advanced_speed' => 'yes',
                    'audio_advanced_download' => 'yes',
                ),
            )
        );

        $this->end_controls_section();

        // Started Playlist Style Controls
        $this->start_controls_section(
            'section_audio_playlist',
            array(
                'label' => __('Playlist', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'audio_playlist_absolute',
            array(
                'label' => __('Absolute', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'jltma-audio-playlist-absolute-',
                'render_type' => 'template',
                'condition' => array('audio_playlist_type' => 'toggle'),
            )
        );

        $this->add_responsive_control(
            'audio_playlist_max_height',
            array(
                'label' => __('Max Height', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 150,
                        'max' => 400,
                        'step' => 10,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist-list' => 'max-height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'audio_playlist_bg',
            array(
                'label' => __('Background Color Overlay', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_inner' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_responsive_control(
            'audio_playlist_medium_vertical_gap',
            array(
                'label' => __('Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px'),
                'allowed_dimensions' => 'vertical',
                'placeholder' => array(
                    'top' => '',
                    'right' => 'auto',
                    'bottom' => '',
                    'left' => 'auto',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_inner' => 'padding-top: {{TOP}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist-list' => 'padding-bottom: {{BOTTOM}}{{UNIT}};',
                ),
                'condition' => array('audio_size' => 'medium'),
            )
        );

        $this->add_responsive_control(
            'audio_playlist_small_gap',
            array(
                'label' => __('Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist-list' => 'padding-bottom: {{BOTTOM}}{{UNIT}};',
                ),
                'condition' => array('audio_size' => 'small'),
            )
        );

        $this->end_controls_section();

        // Started Playlist Item Style Controls
        $this->start_controls_section(
            'section_audio_playlist_item',
            array(
                'label' => esc_html__('Playlist Item', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'audio_playlist_item_one_line',
            array(
                'label' => __('In a row', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'render_type' => 'template',
                'prefix_class' => 'jltma-track-name-one-line-',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'audio_playlist_item_typography',
                'selector' => '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-info',
                'fields_options' => array(
                    'line_height' => array(
                        'selectors' => array(
                            '{{SELECTOR}}' => 'line-height: {{SIZE}}{{UNIT}}',
                            '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-equalizer_wrap' => 'height: {{SIZE}}{{UNIT}}',
                            '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-number' => 'height: {{SIZE}}{{UNIT}}',
                        ),
                    ),
                ),
            )
        );

        $this->add_responsive_control(
            'audio_playlist_item_gap',
            array(
                'label' => __('Gap Between', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 5,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_item:after' => 'bottom: calc( -{{SIZE}}{{UNIT}} / 2 );',
                ),
            )
        );

        $this->add_responsive_control(
            'audio_playlist_item_padding',
            array(
                'label' => esc_html__('Padding', 'master-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs(
            'tabs_audio_playlist_item_style',
            array(
                'separator' => 'before',
            )
        );

        $this->start_controls_tab(
            'tab_audio_playlist_item_normal',
            array(
                'label' => esc_html__('Normal', 'master-addons' ),
            )
        );

        $this->add_control(
            'audio_playlist_item_normal_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-podcast a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-podcast a > svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-equalizer-item' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'audio_playlist_item_normal_bg',
            array(
                'label' => __('Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_item' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_audio_playlist_item_hover',
            array('label' => esc_html__('Hover', 'master-addons' ))
        );

        $this->add_control(
            'audio_playlist_item_hover_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-podcast a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .jltma-active-track > .elementor-widget-jltma-audio-playlist__track-podcast a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-podcast a:hover > svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .jltma-active-track > .elementor-widget-jltma-audio-playlist__track-podcast > a:hover > svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track:hover .elementor-widget-jltma-audio-playlist__track-equalizer-item' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'audio_playlist_item_hover_bg',
            array(
                'label' => __('Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_item:hover' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_audio_playlist_item_active',
            array('label' => esc_html__('Active', 'master-addons' ))
        );

        $this->add_control(
            'audio_playlist_item_active_color',
            array(
                'label' => __('Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .jltma-active-track > .elementor-widget-jltma-audio-playlist__track' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .jltma-active-track:hover > .elementor-widget-jltma-audio-playlist__track' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .jltma-active-track > .elementor-widget-jltma-audio-playlist__track-podcast a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .jltma-active-track > .elementor-widget-jltma-audio-playlist__track-podcast svg' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} .jltma-active-track > .elementor-widget-jltma-audio-playlist__track .elementor-widget-jltma-audio-playlist__track-equalizer-item' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .jltma-active-track:hover > .elementor-widget-jltma-audio-playlist__track .elementor-widget-jltma-audio-playlist__track-equalizer-item' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'audio_playlist_item_active_bg',
            array(
                'label' => __('Background Color', 'master-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_item.jltma-active-track' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__playlist_item.jltma-active-track:hover' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'audio_playlist_item_separator',
            array(
                'label' => __('Separator', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'audio_playlist_item_marker_heading',
            array(
                'label' => __('Marker', 'master-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'audio_playlist_item_marker',
            array(
                'label' => __('Type', 'master-addons' ),
                'type' => 'jltma-choose-text',
                'options' => array(
                    'none' => __('None', 'master-addons' ),
                    'icon' => __('Icon', 'master-addons' ),
                    'number' => __('Number', 'master-addons' ),
                ),
                'default' => 'none',
                'label_block' => false,
                'toggle' => false,
                'render_type' => 'template',
                'prefix_class' => 'jltma-playlist-marker-',
            )
        );

        $this->add_control(
            'audio_playlist_item_number_additional_symbol',
            array(
                'label' => __('Number Additional Symbol', 'master-addons' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Enter your additional symbol', 'master-addons' ),
                'label_block' => true,
                'condition' => array('audio_playlist_item_marker' => 'number'),
            )
        );

        $this->add_control(
            'audio_playlist_item_icon',
            array(
                'label' => esc_html__('Icon', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fab fa-itunes-note',
                    'library' => 'fa-bold',
                ),
                'condition' => array('audio_playlist_item_marker' => 'icon'),
            )
        );

        $this->add_responsive_control(
            'audio_playlist_item_icon_gap',
            array(
                'label' => __('Gap', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 3,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-info' => 'padding-left: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array('audio_playlist_item_marker!' => 'none'),
            )
        );

        $this->add_responsive_control(
            'audio_playlist_item_icon_size',
            array(
                'label' => __('Size', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 30,
                        'step' => 1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}.jltma-playlist-marker-icon .elementor-widget-jltma-audio-playlist__track-equalizer_wrap' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-playlist-marker-number .elementor-widget-jltma-audio-playlist__track-number' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.jltma-playlist-marker-number .elementor-widget-jltma-audio-playlist__track-number > span:before' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array('audio_playlist_item_marker!' => 'none'),
            )
        );

        $this->end_controls_section();

        // Started Playlist Item Links Style Controls
        $this->start_controls_section(
            'section_audio_playlist_item_links',
            array(
                'label' => esc_html__('Playlist Item Links', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'audio_source_sequence',
            array(
                'label' => __('Sequence of elements', 'master-addons' ),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => array(
                    'amazon' => __('Amazon', 'master-addons' ),
                    'apple' => __('Apple', 'master-addons' ),
                    'google' => __('Google', 'master-addons' ),
                    'radiopublic' => __('RadioPublic', 'master-addons' ),
                    'rss' => __('RSS', 'master-addons' ),
                    'soundcloud' => __('SoundCloud', 'master-addons' ),
                    'spotify' => __('Spotify', 'master-addons' ),
                    'tunein' => __('TuneIn', 'master-addons' ),
                    'custom_1' => __('Custom 1', 'master-addons' ),
                    'custom_2' => __('Custom 2', 'master-addons' ),
                    'custom_3' => __('Custom 3', 'master-addons' ),
                ),
                'multiple' => true,
                'control_options' => array(
                    'plugins' => array(
                        'drag_drop',
                    ),
                ),
                'default' => array(
                    'amazon',
                    'apple',
                    'google',
                    'radiopublic',
                    'rss',
                    'soundcloud',
                    'spotify',
                    'tunein',
                    'custom_1',
                    'custom_2',
                    'custom_3',
                ),
                'separator' => 'before',
            )
        );

        $this->add_control(
            'audio_playlist_item_icon_links_hidden',
            array(
                'label' => __('Hidden Links', 'master-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'prefix_class' => 'jltma-track-links-hidden-',
                'description' => 'When turned on, links will be displayed only on hover and on the active track',
            )
        );

        $this->add_responsive_control(
            'audio_playlist_item_icon_links_gap',
            array(
                'label' => __('Gap', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-podcast > a' => 'margin-left: calc( {{SIZE}}{{UNIT}} / 2 ); margin-right: calc( {{SIZE}}{{UNIT}} / 2 );',
                ),
            )
        );

        $this->add_responsive_control(
            'audio_playlist_item_icon_links_size',
            array(
                'label' => __('Size', 'master-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 20,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-podcast' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-widget-jltma-audio-playlist__track-podcast svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'audio_playlist_item_icon_links_custom_1',
            array(
                'label' => esc_html__('Custom 1', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fab fa-itunes-note',
                    'library' => 'fa-bold',
                ),
            )
        );

        $this->add_control(
            'audio_playlist_item_icon_links_custom_2',
            array(
                'label' => esc_html__('Custom 2', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fab fa-itunes-note',
                    'library' => 'fa-bold',
                ),
            )
        );

        $this->add_control(
            'audio_playlist_item_icon_links_custom_3',
            array(
                'label' => esc_html__('Custom 3', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fab fa-itunes-note',
                    'library' => 'fa-bold',
                ),
            )
        );

        $this->end_controls_section();

        // Started Icons Controls
        $this->start_controls_section(
            'section_audio_icons',
            array(
                'label' => esc_html__('Icons', 'master-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'amazon_icon',
            array(
                'label' => esc_html__('Amazon', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fas fa-bold',
                    'library' => 'fa-solid',
                ),
            )
        );

        $this->add_control(
            'apple_icon',
            array(
                'label' => esc_html__('Apple', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fas fa-bold',
                    'library' => 'fa-solid',
                ),
            )
        );

        $this->add_control(
            'google_icon',
            array(
                'label' => esc_html__('Google', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fas fa-bold',
                    'library' => 'fa-solid',
                ),
            )
        );

        $this->add_control(
            'radiopublic_icon',
            array(
                'label' => esc_html__('RadioPublic', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fas fa-bold',
                    'library' => 'fa-solid',
                ),
            )
        );

        $this->add_control(
            'rss_icon',
            array(
                'label' => esc_html__('RSS', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fas fa-bold',
                    'library' => 'fa-solid',
                ),
            )
        );

        $this->add_control(
            'soundcloud_icon',
            array(
                'label' => esc_html__('SoundCloud', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fab fa-soundcloud',
                    'library' => 'fa-bold',
                ),
            )
        );

        $this->add_control(
            'spotify_icon',
            array(
                'label' => esc_html__('Spotify', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fas fa-bold',
                    'library' => 'fa-solid',
                ),
            )
        );

        $this->add_control(
            'tunein_icon',
            array(
                'label' => esc_html__('TuneIn', 'master-addons' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => array(
                    'value' => 'fas fa-bold',
                    'library' => 'fa-solid',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function print_icon_control($icon_control)
    {
        $icons = array(
            'play_icon' => 'fas fa-play',
            'prev_icon' => 'fas fa-fast-backward',
            'next_icon' => 'fas fa-fast-forward',
            'backward_icon' => 'fas fa-undo',
            'forward_icon' => 'fas fa-redo-alt',
            'shuffle_icon' => 'fas fa-bezier-curve',
            'loop_icon' => 'fas fa-retweet',
            'volume_up_icon' => 'fas fa-volume-up',
            'list_icon' => 'fas fa-list',
        );

        return $icons[$icon_control];
    }

    /**
     * Render audio poster on the widget frontend.
     *
     * Written in PHP and used to generate the audio poster HTML.
     */
    protected function print_audio_poster()
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['audio_poster']['id'])) {
            return;
        }

        $widget_name = 'elementor-widget-jltma-audio-playlist';

        echo '<div class="' . esc_attr($widget_name) . '__poster">' .
            Group_Control_Image_Size::get_attachment_image_html($settings, 'audio_poster') .
            '</div>';
    }

    /**
     * Print control button.
     *
     * @return array Audio control button.
     */
    protected function print_control_button($button, $title, $add_attr, $add_icon, $icon_class, $icon)
    {
        $widget_name = 'elementor-widget-jltma-audio-playlist';
        $controls_button = $widget_name . '__controls-button';

        echo '<div
			class="' . $controls_button . ' jltma-player-' . $button . '"
			title="' . esc_attr($title) . '"' .
            ('' !== $add_attr ? ' ' . $add_attr : '') .
            '>';

        if ($add_icon) {
            echo '<i class="' .
                ('' !== $icon_class ? $widget_name . $icon_class : '') .
                ('' !== $icon_class && '' !== $icon ? ' ' : '') .
                esc_attr($this->print_icon_control($icon)) .
                '"></i>';
        }

        echo '</div>';
    }

    /**
     * Print current time.
     *
     * @return array Audio current time.
     */
    protected function print_current_time()
    {
        $widget_name = 'elementor-widget-jltma-audio-playlist';

        echo '<div class="' . esc_attr($widget_name) . '__current-time" title="' . esc_attr__('Current Time', 'master-addons' ) . '">' .
            '<span class="' . esc_attr($widget_name) . '__current-time-value">' .
            esc_html('00:00') .
            '</span>' .
            '</div>';
    }

    /**
     * Print progress.
     *
     * @return array Audio progress.
     */
    protected function print_progress()
    {
        $widget_name = 'elementor-widget-jltma-audio-playlist';

        echo '<div class="' . $widget_name . '__progress-wrap">' .
            '<div class="' . $widget_name . '__progress">' .
            '<div class="' . $widget_name . '__progress-inner"></div>' .
            '<span class="' . $widget_name . '__progress-time">' .
            '<span class="' . $widget_name . '__progress-time-value"></span>' .
            '</span>' .
            '</div>' .
            '</div>';
    }

    /**
     * Print total time.
     *
     * @return array Audio total time.
     */
    protected function print_total_time()
    {
        $widget_name = 'elementor-widget-jltma-audio-playlist';

        echo '<div class="' . $widget_name . '__total-time" title="' . esc_attr__('Total Time', 'master-addons' ) . '">' .
            '<span class="' . $widget_name . '__total-time-value">' .
            esc_html('00:00') .
            '</span>' .
            '</div>';
    }

    /**
     * Print volume.
     *
     * @return array Audio volume.
     */
    protected function print_volume()
    {
        $widget_name = 'elementor-widget-jltma-audio-playlist';

        echo '<div class="' . $widget_name . '__volume-wrap">' .
            '<div class="' . $widget_name . '__volume-inner">';

        $this->print_control_button('volume', '100%', '', true, '__volume-icon', 'volume_up_icon');

        echo '<div class="' . $widget_name . '__volume-progress-wrap">' .
            '<div class="' . $widget_name . '__volume-progress"></div>' .
            '</div>' .
            '</div>' .
            '</div>';
    }

    /**
     * Render progress for medium type on the widget frontend.
     *
     * Written in PHP and used to generate the progress HTML.
     */
    protected function print_progress_medium()
    {
        $settings = $this->get_settings_for_display();

        $widget_name = 'elementor-widget-jltma-audio-playlist';
        $time_position = $settings['audio_progress_current_total_time_position'];

        echo '<div class="' . $widget_name . '__progress-container">';

        if ('top' !== $time_position) {
            $this->print_progress();
        }

        if ('yes' === $settings['audio_progress_current_total_time_show']) {
            $this->print_current_time();

            $this->print_total_time();
        }

        if ('top' === $time_position) {
            $this->print_progress();
        }

        echo '</div>';
    }

    /**
     * Print control buttons left.
     *
     * @return array Audio control buttons left.
     */
    protected function print_control_buttons_left()
    {
        $settings = $this->get_settings_for_display();

        $widget_name = 'elementor-widget-jltma-audio-playlist';
        $audio_list = $settings['audio_list'];

        echo '<div class="' . $widget_name . '__control_buttons_left">';

        if ('both' === $settings['audio_control_button_prev_next'] && '1' < count($audio_list)) {
            $this->print_control_button('prev', 'Prev', 'disabled', true, '', 'prev_icon');
        }

        if ('both' === $settings['audio_control_button_backward_forward']) {
            $this->print_control_button('backward', 'Backward', '', true, '', 'backward_icon');
        }

        $this->print_control_button('play fas fa-play', 'Play', '', false, '', '');

        if ('none' !== $settings['audio_control_button_backward_forward']) {
            $this->print_control_button('forward', 'Forward', '', true, '', 'forward_icon');
        }

        if ('none' !== $settings['audio_control_button_prev_next'] && '1' < count($audio_list)) {
            $this->print_control_button('next', 'Next', '', true, '', 'next_icon');
        }

        echo '</div>';
    }

    /**
     * Print track name.
     *
     * @return array Audio track name.
     */
    protected function print_track_name($audio_url, $track_title_cl, $track_title_media, $one_line_control, $track_separator_cl, $track_subtitle_cl, $track_subtitle_media)
    {
        $settings = $this->get_settings_for_display();

        $widget_name = 'elementor-widget-jltma-audio-playlist';

        echo '<span class="' . $widget_name . $track_title_cl . '">';

        $id = attachment_url_to_postid($audio_url);
        $track_title_metadata = isset(wp_get_attachment_metadata($id)['title']) ? wp_get_attachment_metadata($id)['title'] : '';
        $track_title = !empty($track_title_media) ? esc_html($track_title_media) : esc_html($track_title_metadata);

        if ('' !== $track_title) {
            echo esc_html($track_title);
        } else {
            echo esc_html__('Enter track name', 'master-addons' );
        }

        echo '</span>';

        $track_subtitle_metadata = isset(wp_get_attachment_metadata($id)['artist']) ? wp_get_attachment_metadata($id)['artist'] : '';
        $track_subtitle = !empty($track_subtitle_media) ? esc_html($track_subtitle_media) : esc_html($track_subtitle_metadata);

        if ('' !== $track_subtitle) {
            if ('yes' === $settings[$one_line_control]) {
                echo '<span class="' . $widget_name . $track_separator_cl . '">-</span>';
            }

            echo '<span class="' . $widget_name . $track_subtitle_cl . '">' .
                esc_html($track_subtitle) .
                '</span>';
        }
    }

    /**
     * Print control buttons right.
     *
     * @return array Audio control buttons right.
     */
    protected function print_control_buttons_right()
    {
        $settings = $this->get_settings_for_display();

        $widget_name = 'elementor-widget-jltma-audio-playlist';

        echo '<div class="' . $widget_name . '__control_buttons_right">';

        if ($settings['audio_control_button_shuffle'] && '1' < count($settings['audio_list'])) {
            $this->print_control_button('shuffle', 'Shuffle', '', true, '', 'shuffle_icon');
        }

        if ($settings['audio_control_button_loop']) {
            $this->print_control_button('loop jltma-loop-disabled', 'Loop', '', true, '', 'loop_icon');
        }

        if ($settings['audio_control_button_volume']) {
            $this->print_volume();
        }

        if ('toggle' === $settings['audio_playlist_type'] && '1' < count($settings['audio_list'])) {
            $this->print_control_button('list', 'Show Playlist', '', true, '', 'list_icon');
        }

        echo '</div>';
    }

    /**
     * Render track name for medium type on the widget frontend.
     *
     * Written in PHP and used to generate the track name HTML.
     */
    protected function print_track_name_info()
    {
        $settings = $this->get_settings_for_display();

        $widget_name = 'elementor-widget-jltma-audio-playlist';
        $poster = $settings['audio_poster']['id'];

        if (!empty($poster) && 'with_title' === $settings['audio_poster_position']) {
            $this->print_audio_poster();

            echo '<div class="' . $widget_name . '__track_name_info_wrap">';
            $this->print_advanced();
        }

        $list = $settings['audio_list'];

        if (!empty($list[0]['insert_url'])) {
            $audio_url = $list[0]['external_url']['url'];
        } else {
            $audio_url = $list[0]['hosted_url']['url'];
        }

        if (empty($audio_url)) {
            return;
        }

        echo '<div class="' . $widget_name . '__track_name_info">' .
            '<div class="' . $widget_name . '__track_name_info_inner">';

        if (!empty($list[0]['insert_url'])) {
            $track_title = pathinfo($list[0]['external_url']['url'])['filename'];
        } else {
            $track_title = $list[0]['track_title'];
        }

        $this->print_track_name(
            $audio_url,
            '__track-name-title',
            $track_title,
            'audio_track_name_info_one_line',
            '__track-name-separator',
            '__track-name-subtitle',
            $list[0]['track_subtitle']
        );

        echo '</div>' .
            '</div>';

        if (!empty($poster) && 'with_title' === $settings['audio_poster_position']) {
            echo '</div>';
        }
    }

    /**
     * Render track name for medium type on the widget frontend.
     *
     * Written in PHP and used to generate the track name HTML.
     */
    protected function print_advanced()
    {
        $settings = $this->get_settings_for_display();

        $widget_name = 'elementor-widget-jltma-audio-playlist';
        $speed_button = $widget_name . '__speed-button';
        $speed_value = 'jltma-player-speed-';
        $list = $settings['audio_list'];

        $speeds = array(
            '0.5' => 'backward-0.5',
            '0.75' => 'backward-0.75',
            'Normal' => 'normal jltma-choose-speed',
            '1.25' => 'forward-1.25',
            '1.5' => 'forward-1.5',
            '1.75' => 'forward-1.75',
            '2' => 'forward-2',
        );

        if ('yes' === $settings['audio_advanced_download'] || 'yes' === $settings['audio_advanced_speed']) {
            echo '<div class="' . $widget_name . '__advanced">' .
                '<i class="' . $widget_name . '__advanced-icon fas fa-ellipsis-v" title="' . esc_attr__('Advanced', 'master-addons' ) . '"></i>' .
                '<div class="' . $widget_name . '__advanced_inner">';

            if ('yes' === $settings['audio_advanced_speed']) {
                echo '<span class="' . $widget_name . '__speed">' .
                    '<span class="' . $widget_name . '__speed-variations">';

                foreach ($speeds as $key => $value) {
                    echo '<span class="' . $speed_button . ' ' . $speed_value . $value . '">' . $key . '</span>';
                }

                echo '</span>' .
                    '<span class="' . $widget_name . '__speed-title-wrap">' .
                    '<span class="' . $widget_name . '__speed-title">' . esc_html__('Speed:', 'master-addons' ) . '</span>' .
                    '<span class="' . $widget_name . '__speed-rate">' . esc_html__('Normal', 'master-addons' ) . '</span>' .
                    '</span>' .
                    '</span>';
            }

            if ('yes' === $settings['audio_advanced_download']) {
                $audio_url = '';

                if (!empty($list[0]['insert_url'])) {
                    $audio_url = $list[0]['external_url']['url'];
                } else {
                    $audio_url = $list[0]['hosted_url']['url'];
                }

                $path_parts = pathinfo($audio_url)['filename'];

                echo '<a class="' . $widget_name . '__download" href="' . $audio_url . '" download="' . $path_parts . '">' .
                    '<i class="fas fa-download"></i>' .
                    '<span>' . esc_html__('Download', 'master-addons' ) . '</span>' .
                    '</a>';
            }

            echo '</div>' .
                '</div>';
        }
    }

    /**
     * Get audio link on the widget frontend.
     *
     * Written in PHP and used to generate the audio link.
     */
    protected function get_audio_link($item, $audio_item)
    {
        $settings = $this->get_settings_for_display();

        foreach ($item['audio_source'] as $source_item) {
            if ($audio_item === $source_item) {
                if ('custom_1' === $source_item || 'custom_2' === $source_item || 'custom_3' === $source_item) {
                    $icon = $settings['audio_playlist_item_icon_links_' . $source_item];
                } else {
                    $icon = $settings[$source_item . '_icon'];
                }

                $source_item_url = $item['audio_' . $source_item . '_url']['url'];

                if ($source_item_url && $icon) {
                    echo '<a href="' . esc_url($source_item_url) . '" title="' . esc_attr($source_item) . '" target="_blank">';

                    Icons_Manager::render_icon($icon);

                    echo '</a>';
                }
            }
        }
    }

    /**
     * Render audio link on the widget frontend.
     *
     * Written in PHP and used to generate the audio link HTML.
     */
    protected function print_audio_link($item)
    {
        $settings = $this->get_settings_for_display();

        foreach ($settings['audio_source_sequence'] as $audio_item) {
            switch ($audio_item) {
                case 'apple':
                    $this->get_audio_link($item, $audio_item);

                    break;
                case 'spotify':
                    $this->get_audio_link($item, $audio_item);

                    break;
                case 'soundcloud':
                    $this->get_audio_link($item, $audio_item);

                    break;
                case 'google':
                    $this->get_audio_link($item, $audio_item);

                    break;
                case 'amazon':
                    $this->get_audio_link($item, $audio_item);

                    break;
                case 'tunein':
                    $this->get_audio_link($item, $audio_item);

                    break;
                case 'radiopublic':
                    $this->get_audio_link($item, $audio_item);

                    break;
                case 'rss':
                    $this->get_audio_link($item, $audio_item);

                    break;
                case 'custom_1':
                    $this->get_audio_link($item, $audio_item);

                    break;
                case 'custom_2':
                    $this->get_audio_link($item, $audio_item);

                    break;
                case 'custom_3':
                    $this->get_audio_link($item, $audio_item);

                    break;
            }
        }
    }

    /**
     * Render audio item output on the frontend.
     *
     * Written in PHP and used to generate the audio item HTML.
     */
    protected function print_audio_marker()
    {
        $settings = $this->get_settings_for_display();

        $widget_name = 'elementor-widget-jltma-audio-playlist';
        $equalizer_item = $widget_name . '__track-equalizer-item';
        $item_marker = $settings['audio_playlist_item_marker'];

        if ('icon' === $item_marker) {
            echo '<div class="' . $widget_name . '__track-equalizer_wrap">';

            if ('' !== $settings['audio_playlist_item_icon']['value']) {
                Icons_Manager::render_icon($settings['audio_playlist_item_icon']);
            } else {
                echo '<i class="fab fa-itunes-note"></i>';
            }

            echo '<div class="' . $widget_name . '__track-equalizer">' .
                '<div class="' . $equalizer_item . '"></div>' .
                '<div class="' . $equalizer_item . '"></div>' .
                '<div class="' . $equalizer_item . '"></div>' .
                '</div>' .
                '</div>';
        }

        if ('number' === $item_marker) {
            echo '<span class="' . $widget_name . '__track-number">' .
                '<span class="' . $widget_name . '__track-number-text"></span>';

            $additional_symbol = $settings['audio_playlist_item_number_additional_symbol'];

            if ('' !== $additional_symbol) {
                echo '<span class="' . $widget_name . '__track-number-additional-symbol">' .
                    $additional_symbol .
                    '</span>';
            }

            echo '</span>';
        }
    }

    /**
     * Render audio item output on the frontend.
     *
     * Written in PHP and used to generate the audio item HTML.
     */
    protected function print_audio_item($item, $active)
    {
        $settings = $this->get_settings_for_display();

        if (!empty($item['insert_url'])) {
            $audio_url = $item['external_url']['url'];
        } else {
            $audio_url = $item['hosted_url']['url'];
        }

        if (empty($audio_url)) {
            return;
        }

        $widget_name = 'elementor-widget-jltma-audio-playlist';
        $playlist_item_separator = ('yes' === $settings['audio_playlist_item_separator'] ? ' jltma-playlist-item-separator' : '');

        echo '<li class="' . $widget_name . '__playlist_item' . $playlist_item_separator . ($active ? ' jltma-active-track' : '') . '">' .
            '<span class="' . $widget_name . '__track" data-href="' . esc_url($audio_url) . '">';

        $this->print_audio_marker();

        echo '<span class="' . $widget_name . '__track-info">';

        if (!empty($item['insert_url'])) {
            $track_title = pathinfo($item['external_url']['url'])['filename'];
        } else {
            $track_title = $item['track_title'];
        }

        $this->print_track_name(
            $audio_url,
            '__track-title',
            $track_title,
            'audio_playlist_item_one_line',
            '__track-separator',
            '__track-subtitle',
            $item['track_subtitle']
        );

        echo '</span>' .
            '</span>' .
            '<span class="' . $widget_name . '__track-podcast">';

        $this->print_audio_link($item);

        echo '</span>' .
            '</li>';
    }

    /**
     * Render audio playlist output on the frontend.
     *
     * Written in PHP and used to generate the audio playlist HTML.
     */
    protected function print_audio_playlist()
    {
        $settings = $this->get_settings_for_display();

        $widget_name = 'elementor-widget-jltma-audio-playlist';
        $list = $settings['audio_list'];

        echo '<div class="' . $widget_name . '__playlist ' . esc_attr($settings['audio_playlist_type']) . '">' .
            '<div class="' . $widget_name . '__playlist_inner">' .
            '<ul class="' . $widget_name . '__playlist-list">';

        $active = true;

        foreach ($list as $item) {
            $this->print_audio_item($item, $active);

            if ($active) {
                $active = false;
            }
        }

        echo '</ul>' .
            '</div>' .
            '</div>';
    }

    /**
     * Render audio medium player output on the frontend.
     *
     * Written in PHP and used to generate the audio medium player HTML.
     */
    protected function print_audio_medium_player()
    {
        $settings = $this->get_settings_for_display();

        $widget_name = 'elementor-widget-jltma-audio-playlist';
        $poster = $settings['audio_poster']['id'];
        $poster_position = $settings['audio_poster_position'];

        echo '<div class="' . $widget_name . '__player_left">';

        if (!empty($poster) && 'with_title' !== $poster_position) {
            $this->print_audio_poster();
        }

        if ('with_title' === $poster_position) {
            $this->print_track_name_info();
        }

        echo '</div>' .
            '<div class="' . $widget_name . '__player_right' . (empty($poster) ? ' jltma-empty-poster' : '') . '">';

        if ('with_title' !== $poster_position) {
            $this->print_advanced();

            $this->print_track_name_info();
        }

        $this->print_progress_medium();

        echo '<div class="' . $widget_name . '__control_buttons_wrap">';

        $this->print_control_buttons_left();

        $this->print_control_buttons_right();

        echo '</div>' .
            '</div>';
    }

    /**
     * Render audio small player output on the frontend.
     *
     * Written in PHP and used to generate the audio small player HTML.
     */
    protected function print_audio_small_player()
    {
        $settings = $this->get_settings_for_display();

        $time_show = $settings['audio_progress_current_total_time_show'];

        $this->print_control_button('play fas fa-play', 'Play', '', false, '', '');

        if ('yes' === $time_show) {
            $this->print_current_time();
        }

        $this->print_progress();

        if ('yes' === $time_show) {
            $this->print_total_time();
        }

        if ($settings['audio_control_button_volume']) {
            $this->print_volume();
        }

        if ('toggle' === $settings['audio_playlist_type'] && '1' < count($settings['audio_list'])) {
            $this->print_control_button('list', 'Show Playlist', '', true, '', 'list_icon');
        }
    }

    /**
     * Render audio widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $widget_name = 'elementor-widget-jltma-audio-playlist';
        $list = $settings['audio_list'];
        $speed = $settings['audio_advanced_speed'];
        $download = $settings['audio_advanced_download'];
        $advanced = '';

        if (!empty($poster) && 'with_title' === $settings['audio_poster_position']) {
            $this->print_audio_poster();

            $this->print_advanced();
        }

        if ($speed || $download) {
            $advanced = ' jltma-enable-advanced';
        }

        if (!empty($list[0]['insert_url'])) {
            $audio_url = $list[0]['external_url']['url'];
        } else {
            $audio_url = $list[0]['hosted_url']['url'];
        }

        echo '<div class="' . $widget_name . '__player_wrap">' .
            '<div class="' . $widget_name . '__player-bg">' .
            '<div class="' . $widget_name . '__player-bg-overlay"></div>' .
            '<audio class="' . $widget_name . '__player-audio" preload="metadata" itemprop="audio" tabindex="0" type="audio/mpeg">' .
            '<source class="' . $widget_name . '__player-source" type="audio/mp3" autoplay="autoplay" src="' . $audio_url . '">' .
            '</audio>' .
            '<div class="' . $widget_name . '__player' . esc_attr($advanced) . '">';

        if ('medium' === $settings['audio_size']) {
            $this->print_audio_medium_player();
        } else {
            $this->print_audio_small_player();
        }

        echo '</div>';

        $this->print_audio_playlist();

        echo '</div>' .
            '</div>';
    }

    /**
     * Render audio widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     */
    protected function content_template()
    {
    }
}
