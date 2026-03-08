<?php

namespace MasterAddons\Addons;

// Elementor Classes
use MasterAddons\Inc\Classes\Base\Master_Widget;
use \Elementor\Controls_Manager;
use MasterAddons\Inc\Classes\Helper;
use MasterAddons\Inc\Traits\JLTMA_Swiper_Controls;
use MasterAddons\Inc\Controls\Group\JLTMA_Transition;
use MasterAddons\Inc\Admin\Config;

/**
 * Author Name: Liton Arefin
 * Author URL : https: //jeweltheme.com
 * Date       : 02/05/2020
 */

if (!defined('ABSPATH')) exit; // If this file is called directly, abort.

class Twitter_Slider extends Master_Widget
{
	use JLTMA_Swiper_Controls;
	use \MasterAddons\Inc\Traits\Widget_Notice;
	use \MasterAddons\Inc\Traits\Widget_Assets_Trait;

	private $_query = null;

	public function get_name()
	{
		return 'jltma-twitter-slider';
	}

	public function get_title()
	{
		return esc_html__('Twitter Slider', 'master-addons' );
	}

	public function get_icon()
	{
		return 'jltma-icon ' . Config::get_addon_icon($this->get_name());
	}

	public function on_import($element)
	{
		if (!get_post_type_object($element['settings']['posts_post_type'])) {
			$element['settings']['posts_post_type'] = 'post';
		}
		return $element;
	}

	public function get_query()
	{
		return $this->_query;
	}

	public function on_export($element)
	{
		$element = Group_Control_Posts::on_export_remove_setting_from_element($element, 'posts');
		return $element;
	}

	protected function register_controls()
	{

		/*
        * Content: Layout
        */
		$this->start_controls_section(
			'jltma_ts_section_layout',
			[
				'label' => esc_html__('Layout', 'master-addons' ),
			]
		);

		$this->add_control(
			'jltma_ts_tweet_num',
			[
				'label'   => esc_html__('Limit', 'master-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->add_control(
			'jltma_ts_cache_time',
			[
				'label'   => esc_html__('Cache Time(m)', 'master-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 60,
			]
		);

		$this->add_control(
			'jltma_ts_show_avatar',
			[
				'label'   => esc_html__('Show Avatar', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'jltma_ts_avatar_link',
			[
				'label'     => esc_html__('Avatar Link', 'master-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'jltma_ts_show_avatar' => 'yes'
				]
			]
		);

		$this->add_control(
			'jltma_ts_show_time',
			[
				'label'   => esc_html__('Show Time', 'master-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'jltma_ts_long_time_format',
			[
				'label'     => esc_html__('Long Time Format', 'master-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'jltma_ts_show_time' => 'yes',
				]
			]
		);

		$this->add_control(
			'jltma_ts_show_meta_button',
			[
				'label' => esc_html__('Execute Buttons', 'master-addons' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'jltma_ts_exclude_replies',
			[
				'label' => esc_html__('Exclude Replies', 'master-addons' ),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'jltma_ts_alignment',
			[
				'label'   => esc_html__(
					'Alignment',
					'master-addons'
				),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => Helper::jltma_content_alignment(),
				'selectors' => [
					'{{WRAPPER}} .jltma-twitter_slider__item .card-body' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Global Navigation Controls
		$this->start_controls_section(
			'section_content_navigation',
			[
				'label' => __('Navigation', 'master-addons' ),
			]
		);
		$this->jltma_swiper_navigation_controls();
		$this->end_controls_section();

		//  Global Swiper Control Settings
		$this->start_controls_section(
			'section_carousel_settings',
			[
				'label' => __('Carousel Settings', 'master-addons' ),
			]
		);
		$this->jltma_swiper_settings_controls();

		$this->end_controls_section();

		/*
        * STYLE: Avatar
        */

		$this->start_controls_section(
			'jltma_ts_section_style_avatar',
			[
				'label'     => esc_html__('Avatar', 'master-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'jltma_ts_show_avatar' => 'yes',
				],
			]
		);

		$this->add_control(
			'jltma_ts_avatar_width',
			[
				'label' => esc_html__('Width', 'master-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 200,
						'min' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jltma-twitter_slider__item .card-body a' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'jltma_ts_avatar_align',
			[
				'label'   => esc_html__('Alignment', 'master-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'master-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'inherit' => [
						'title' => esc_html__( 'Center', 'master-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'master-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .jltma-twitter_slider__item .card-body > a' => 'float: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'jltma_ts_avatar_background',
			[
				'label'     => esc_html__('Background', 'master-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jltma-twitter_slider__item .jltma-twitter-thumb img' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'jltma_ts_avatar_padding',
			[
				'label'      => esc_html__('Padding', 'master-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .jltma-twitter_slider__item .jltma-twitter-thumb img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'jltma_ts_avatar_margin',
			[
				'label'      => esc_html__('Margin', 'master-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .jltma-twitter_slider__item .jltma-twitter-thumb img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'jltma_ts_avatar_radius',
			[
				'label'      => esc_html__('Border Radius', 'master-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .jltma-twitter_slider__item .jltma-twitter-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->add_control(
			'jltma_ts_avatar_opacity',
			[
				'label'   => esc_html__('Opacity (%)', 'master-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jltma-twitter_slider__item .jltma-twitter-thumb img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_section();

		/*
        * STYLE: Execute Button
        */

		$this->start_controls_section(
			'jltma_ts_section_style_meta',
			[
				'label'     => esc_html__('Execute Buttons', 'master-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'jltma_ts_show_meta_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'jltma_ts_meta_color',
			[
				'label'     => esc_html__('Color', 'master-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jltma-twitter_slider__item .jltma-twitter-meta-wrapper a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'jltma_ts_meta_hover_color',
			[
				'label'     => esc_html__('Hover Color', 'master-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jltma-twitter_slider__item .jltma-twitter-meta-wrapper a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		/*
        * STYLE: Time
        */

		$this->start_controls_section(
			'jltma_ts_section_style_time',
			[
				'label'     => esc_html__('Time', 'master-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'jltma_ts_show_time' => 'yes',
				],
			]
		);

		$this->add_control(
			'jltma_ts_time_color',
			[
				'label'     => esc_html__('Color', 'master-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jltma-twitter_slider__item .jltma-twitter-time-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'jltma_ts_time_hover_color',
			[
				'label'     => esc_html__('Hover Color', 'master-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jltma-twitter_slider__item .jltma-twitter-time-link:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Global Swiper Item Style
		$this->jltma_swiper_item_style_controls('twitter-carousel');

		//Navigation Style
		$this->start_controls_section(
			'section_style_navigation',
			[
				'label'      => __(
					'Navigation',
					'master-addons'
				),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'navigation',
							'operator' => '!=',
							'value'    => 'none',
						],
						[
							'name'  => 'show_scrollbar',
							'value' => 'yes',
						],
					],
				],
			]
		);

		// Global Navigation Style Controls
		$this->jltma_swiper_navigation_style_controls('twitter-carousel');

		$this->add_group_control(
			JLTMA_Transition::get_type(),
			[
				'name' 			=> 'arrows',
				'selector' 		=> '{{WRAPPER}} .jltma-swiper__button',
				'condition'		=> [
					'carousel_arrows'         => 'yes'
				]
			]
		);

		$this->end_controls_section();

		// Help Docs section (links from config.php)
		$this->jltma_help_docs();

		$this->upgrade_to_pro_message();
	}

	// Twitter Slider: Loop
	public function jltma_ts_loop_twitter($twitter_consumer_key, $consumer_secret, $access_token, $access_token_secret, $twitter_username)
	{

		$settings = $this->get_settings();

		$name            = $twitter_username;
		$exclude_replies = ('yes' === $settings['jltma_ts_exclude_replies']) ? true : false;
		$transName       = 'jltma-tweets-' . $name;                                             // Name of value in database. [added $name for multiple account use]
		$backupName      = $transName . '-backup';                                            // Name of backup value in database.

		if (false === ($tweets = get_transient($name))) :

			$connection = new \TwitterOAuth($twitter_consumer_key, $consumer_secret, $access_token, $access_token_secret);

			$totalToFetch = ($exclude_replies) ? max(50, $settings['jltma_ts_tweet_num'] * 3) : $settings['jltma_ts_tweet_num'];

			$fetchedTweets = $connection->get(
				'statuses/user_timeline',
				array(
					'screen_name'     => $name,
					'count'           => $totalToFetch,
					'exclude_replies' => $exclude_replies
				)
			);

			// Did the fetch fail?
			if ($connection->http_code != 200) :
				$tweets = get_option($backupName);  // False if there has never been data saved.
			else :
				// Fetch succeeded.
				// Now update the array to store just what we need.
				// (Done here instead of PHP doing this for every page load)
				$limitToDisplay = min($settings['jltma_ts_tweet_num'], count($fetchedTweets));

				for ($i = 0; $i < $limitToDisplay; $i++) :
					$tweet = $fetchedTweets[$i];

					// Core info.
					$name        = $tweet->user->name;
					$screen_name = $tweet->user->screen_name;
					$permalink   = 'https://twitter.com/' . $screen_name . '/status/' . $tweet->id_str;
					$tweet_id    = $tweet->id_str;

					/* Alternative image sizes method: http://dev.twitter.com/doc/get/users/profile_image/:screen_name */
					//  Check for SSL via protocol https then display relevant image - thanks SO - this should do
					if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
						// $protocol = 'https://';
						$image = $tweet->user->profile_image_url_https;
					} else {
						// $protocol = 'http://';
						$image = $tweet->user->profile_image_url;
					}

					// Process Tweets - Use Twitter entities for correct URL, hash and mentions
					$text = $this->process_links($tweet);
					// lets strip 4-byte emojis
					$text = $this->twitter_api_strip_emoji($text);

					// Need to get time in Unix format.
					$time  = $tweet->created_at;
					$time  = date_parse($time);
					$uTime = mktime($time['hour'], $time['minute'], $time['second'], $time['month'], $time['day'], $time['year']);

					// Now make the new array.
					$tweets[] = array(
						'text'      => $text,
						'name'      => $name,
						'permalink' => $permalink,
						'image'     => $image,
						'time'      => $uTime,
						'tweet_id'  => $tweet_id
					);
				endfor;

				set_transient($transName, $tweets, 60 * $settings['jltma_ts_cache_time']);
				update_option($backupName, $tweets);
			endif;
		endif;

		// Now display the tweets, if we can.
		if ($tweets) {

			$this->add_render_attribute([
				'swiper-item' => [
					'class' => [
						'jltma-twitter_slider__item',
						'jltma-swiper__slide',
						'swiper-slide',
					],
				],
			]);

			foreach ((array) $tweets as $t) {
				$parts_url = explode('/', rtrim($t['permalink'], '/'));
				$user_id = $parts_url[3]; ?>
				<div <?php echo $this->get_render_attribute_string('swiper-item'); ?>>
					<div class="card text-center">
						<div class="card-body">
							<?php if ('yes' === $settings['jltma_ts_show_avatar']) : ?>

								<?php if ('yes' === $settings['jltma_ts_avatar_link']) : ?>
									<a href="https://twitter.com/<?php echo esc_attr($user_id); ?>">
									<?php endif; ?>

									<div class="jltma-twitter-thumb">
										<img src="<?php echo esc_url($t['image']); ?>" alt="<?php echo esc_html($t['name']); ?>" />
									</div>

									<?php if ('yes' === $settings['jltma_ts_avatar_link']) : ?>
									</a>
								<?php endif; ?>

							<?php endif; ?>

							<div class="jltma-twitter-text jltma-clearfix">
								<?php echo wp_kses_post($t['text']); ?>
							</div>

							<div class="jltma-twitter-meta-wrapper">

								<?php if ('yes' === $settings['jltma_ts_show_time']) { ?>
									<a href="<?php echo esc_url($t['permalink']); ?>" target="_blank" class="jltma-twitter-time-link">
										<?php
										// Original - long time ref: hours...
										if ('yes' === $settings['jltma_ts_long_time_format']) {
											// Short Twitter style time ref: h...
											$timeDisplay = human_time_diff($t['time'], current_time('timestamp'));
										} else {
											$timeDisplay = $this->twitter_time_diff($t['time'], current_time('timestamp'));
										}
										$displayAgo = _x('ago', 'leading space is required', 'master-addons' );
										/* translators: 1: Time Display, 2: Time Ago. */
										printf(__('%1$s %2$s', 'master-addons' ), $timeDisplay, $displayAgo);
										?>
									</a>
								<?php } ?>

								<?php if ('yes' === $settings['jltma_ts_show_meta_button']) { ?>
									<div class="jltma-twitter-meta-button">
										<a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo esc_attr($t['tweet_id']); ?>" data-lang="en" class="jltma-tmb-reply" title="<?php _e('Reply', 'master-addons' ); ?>" target="_blank">
											<i class="fas fa-reply"></i>
										</a>
										<a href="https://twitter.com/intent/retweet?tweet_id=<?php echo esc_attr($t['tweet_id']); ?>" data-lang="en" class="jltma-tmb-retweet" title="<?php _e('Retweet', 'master-addons' ); ?>" target="_blank">
											<i class="fas fa-sync"></i>
										</a>
										<a href="https://twitter.com/intent/favorite?tweet_id=<?php echo esc_attr($t['tweet_id']); ?>" data-lang="en" class="jltma-tmb-favorite" title="<?php _e('Favourite', 'master-addons' ); ?>" target="_blank">
											<i class="far fa-star"></i>
										</a>
									</div>
								<?php } ?>

							</div>
						</div>
					</div>
				</div>

			<?php } // endforeach
		}
	}

	// Render
	protected function render()
	{

		if (!class_exists('TwitterOAuth')) {
			include_once JLTMA_PATH . 'inc/classes/twitteroauth/twitteroauth.php';
		}

		$settings           = $this->get_settings();
		$jltma_api_settings = get_option('jltma_api_save_settings');

		$twitter_username = (!empty($jltma_api_settings['twitter_username'])) ? $jltma_api_settings['twitter_username'] : '';

		$twitter_consumer_key = (!empty($jltma_api_settings['twitter_consumer_key'])) ? $jltma_api_settings['twitter_consumer_key'] : '';
		$consumer_secret      = (!empty($jltma_api_settings['twitter_consumer_secret'])) ? $jltma_api_settings['twitter_consumer_secret'] : '';
		$access_token         = (!empty($jltma_api_settings['twitter_access_token'])) ? $jltma_api_settings['twitter_access_token'] : '';
		$access_token_secret  = (!empty($jltma_api_settings['twitter_access_token_secret'])) ? $jltma_api_settings['twitter_access_token_secret'] : '';

		$this->jltma_ts_loop_header($settings);

		if ($twitter_consumer_key and $consumer_secret and $access_token and $access_token_secret) {
			$this->jltma_ts_loop_twitter($twitter_consumer_key, $consumer_secret, $access_token, $access_token_secret, $twitter_username);
		} else { ?>

			<div class="ma-el-alert elementor-alert elementor-alert-warning" role="alert">
				<a class="elementor-alert-dismiss"></a>
				<?php $jltma_admin_api_url = esc_url(admin_url('admin.php?page=master-addons-settings#ma_api_keys')); ?>
				<p><?php /* translators: %s: Admin API Url. */ printf(__('Please set Twitter API settings from here <a href="%s" target="_blank">Master Addons Settings</a> to show Tweet data correctly.', 'master-addons' ), $jltma_admin_api_url); ?></p>
			</div>
		<?php
		}

		$this->jltma_ts_loop_footer($settings);
	}

	// Twitter Slider: Header
	protected function jltma_ts_loop_header($settings)
	{

		$settings = $this->get_settings();

		$unique_id = implode('-', [$this->get_id(), get_the_ID()]);

		$this->add_render_attribute([
			'jltma_twitter_slider' => [
				'class' => [
					'jltma-twitter-carousel-wrapper',
					'jltma-carousel',
					'jltma-swiper',
					'jltma-swiper__container',
					'swiper',
					'elementor-twitter-slider-element-' . $unique_id
				],
				'data-twitter-carousel-template-widget-id' => $unique_id
			],
			'swiper-wrapper' => [
				'class' => [
					'jltma-twitter-carousel',
					'jltma-swiper__wrapper',
					'swiper-wrapper',
				],
			]
		]);

		//Global Header Function
		$this->jltma_render_swiper_header_attribute('twitter-carousel');

		$this->add_render_attribute('carousel', 'class', ['jltma-twitter-carousel-slider']);

		?>

		<div <?php echo $this->get_render_attribute_string('carousel'); ?>>
			<div <?php echo $this->get_render_attribute_string('jltma_twitter_slider'); ?>>
				<div <?php echo $this->get_render_attribute_string('swiper-wrapper'); ?>>
				<?php
			}

			// Twitter Slider: Footer
			protected function jltma_ts_loop_footer($settings)
			{
				$settings = $this->get_settings();
				?>

				</div> <!-- swiper-wrapper -->
			</div>
			<!--/.ma-twitter-carousel-->

			<?php $this->render_swiper_navigation(); ?>

			<?php if ('yes' === $settings['show_scrollbar']) { ?>
				<div class="swiper-scrollbar"></div>
			<?php } ?>

		</div>
<?php
			}

			private function twitter_api_strip_emoji($text)
			{
				// four byte utf8: 11110www 10xxxxxx 10yyyyyy 10zzzzzz
				return preg_replace('/[\xF0-\xF7][\x80-\xBF]{3}/', '', $text);
			}

			private function process_links($tweet)
			{

				// Is the Tweet a ReTweet - then grab the full text of the original Tweet
				if (isset($tweet->retweeted_status)) {
					// Split it so indices count correctly for @mentions etc.
					$rt_section = current(explode(":", $tweet->text));
					$text       = $rt_section . ": ";
					// Get Text
					$text .= $tweet->retweeted_status->text;
				} else {
					// Not a retweet - get Tweet
					$text = $tweet->text;
				}

				// NEW Link Creation from clickable items in the text
				$text = preg_replace_callback('/((http)+(s)?:\/\/[^<>\s]+)/i', function($matches) {
					return '<a href="' . esc_url($matches[0]) . '" target="_blank" rel="nofollow">' . esc_html($matches[0]) . '</a>';
				}, $text);
				// Clickable Twitter names
				$text = preg_replace_callback('/[@]+([A-Za-z0-9-_]+)/', function($matches) {
					return '<a href="' . esc_url('https://twitter.com/' . $matches[1]) . '" target="_blank" rel="nofollow">@' . esc_html($matches[1]) . '</a>';
				}, $text);
				// Clickable Twitter hash tags
				$text = preg_replace_callback('/[#]+([A-Za-z0-9-_]+)/', function($matches) {
					return '<a href="' . esc_url('https://twitter.com/search?q=%23' . $matches[1]) . '" target="_blank" rel="nofollow">' . esc_html($matches[0]) . '</a>';
				}, $text);
				// END TWEET CONTENT REGEX
				return $text;
			}

			private function twitter_time_diff($from, $to = '')
			{
				$diff    = human_time_diff($from, $to);
				$replace = array(
					' hour'    => 'h',
					' hours'   => 'h',
					' day'     => 'd',
					' days'    => 'd',
					' minute'  => 'm',
					' minutes' => 'm',
					' second'  => 's',
					' seconds' => 's',
				);
				return strtr($diff, $replace);
			}
		}
