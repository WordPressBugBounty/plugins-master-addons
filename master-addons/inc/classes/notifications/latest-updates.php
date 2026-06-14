<?php

namespace MasterAddons\Inc\Classes\Notifications;

use MasterAddons\Inc\Classes\Notifications\Model\Notice;

if (!class_exists('Latest_Updates')) {
    /**
     * Latest Plugin Updates Notice Class
     *
     * Jewel Theme <support@jeweltheme.com>
     */
    class Latest_Updates extends Notice
    {

        public $color = 'info';
        private $version_option_key = 'jltma_latest_updates_notice_version';

        /**
         * Latest Updates Notice
         *
         * @return void
         */
        public function __construct()
        {
            $this->maybe_reset_for_new_version();
            parent::__construct();
        }

        /**
         * Reset notice data when plugin version changes so the notice
         * re-appears after every update.
         *
         * @return void
         */
        private function maybe_reset_for_new_version()
        {
            if (!defined('JLTMA_VER')) {
                return;
            }

            $stored_version = get_option($this->version_option_key);

            if ($stored_version === JLTMA_VER) {
                return;
            }

            // Version changed — delete old notice data so init() rebuilds it fresh.
            delete_option('jltma_notice_' . strtolower((new \ReflectionClass($this))->getShortName()));
            update_option($this->version_option_key, JLTMA_VER);
        }


        /**
         * Notice Content
         *
         * @author Jewel Theme <support@jeweltheme.com>
         */
        public function notice_content()
        {
            $jltma_changelog_message = sprintf(
                /* translators: 1: URL to changelogs page, 2: Link text for changelogs, 3: Plugin name and version heading HTML, 4: First changelog item HTML, 5: Second changelog item HTML */
                __('%3$s %4$s <br> <strong>Check Changelogs for </strong> <a href="%1$s" target="__blank">%2$s</a>', 'master-addons'),
                esc_url_raw('https://master-addons.com/changelogs'),
                __('More Details', 'master-addons'),
                /** Changelog Items
                 * Starts from: %3$s
                 */

                '<h3 class="jltma-update-head">' . JLTMA . ' <span><small><em>v' . esc_html(JLTMA_VER) . '</em></small>' . __(' has some updates..', 'master-addons') . '</span></h3><br>', // %3$s
                // Changelogs
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Added: Timeline widget Title and Content typography and color controls. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Security: Hardened popup list sorting so it only accepts known sort options. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Security: Tightened permissions on the Mega Menu content editor so only menu managers can use it. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Improved: Custom CSS and JS in the Widget Builder are now a Pro feature; HTML stays available for everyone. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Improved: Template previews now open in a new browser tab instead of loading inside the dashboard. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Improved: Used unique name prefixes across the plugin for better compatibility with other plugins. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Improved: Updated the Select2 library to the latest stable version. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Improved: The setup wizard theme image now loads from within the plugin instead of an external site. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Improved: Review links now open the full reviews page. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Fixed: Template Live Preview link is now easy to read in Elementor\'s dark mode. </span><br>', 'master-addons'),
            );
            printf(wp_kses_post($jltma_changelog_message));
        }

        /**
         * Intervals
         *
         * @author Jewel Theme <support@jeweltheme.com>
         */
        public function intervals()
        {
            return array(0);
        }
    }
}
