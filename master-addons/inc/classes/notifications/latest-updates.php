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
                __('%3$s %4$s %5$s %6$s %7$s %8$s %9$s %10$s %11$s %12$s <br> <strong>Check Changelogs for </strong> <a href="%1$s" target="__blank">%2$s</a>', 'master-addons'),
                esc_url_raw('https://master-addons.com/changelogs'),
                __('More Details', 'master-addons'),
                /** Changelog Items
                 * Starts from: %3$s
                 */

                '<h3 class="jltma-update-head">' . JLTMA . ' <span><small><em>v' . esc_html(JLTMA_VER) . '</em></small>' . __(' has some updates..', 'master-addons') . '</span></h3><br>', // %3$s
                // Changelogs
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Fixed: Blog Post Master Elementor Addon frontend showing single column instead of grid layout</span><br>', 'master-addons'),
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Fixed: Timeline Master Elementor Addon vertical scroll animation not working on frontend</span><br>', 'master-addons'),
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Fixed: Timeline Master Elementor Addon vertical scroll line colors not matching between editor and frontend</span><br>', 'master-addons'),
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Fixed: Multiple Master Elementor Addon missing grid CSS on frontend (Counter Up, Business Hours, Tabs, Call to Action, Image Filter Gallery, Table)</span><br>', 'master-addons'),
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Fixed: Timeline Master Elementor Addon unclosed wrapper div for post-type timelines</span><br>', 'master-addons'),
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Fixed: PHP 8.x deprecation warnings (strpos/str_replace with null values) in Master Elementor Addons</span><br>', 'master-addons'),
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Fixed: Version mismatch notice showing incorrectly when both Free and Pro Master Elementor Addons are compatible</span><br>', 'master-addons'),
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Fixed: Setup Wizard scroll issue fixed.</span><br>', 'master-addons'),
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Added: Fail-safe version guard in Master Elementor Addons Pro plugin with 3-tier version detection</span><br>', 'master-addons'),
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
