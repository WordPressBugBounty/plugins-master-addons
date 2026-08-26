<?php

namespace MasterAddons\Inc\Classes\Notifications;

defined('ABSPATH') || exit;

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
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list"> Security: Security update. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Added: The Widgets Library is available, with the catalog stored on disk so the screen loads without waiting on the network. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Refresh in the Widgets Library now genuinely re-reads the catalog from the server instead of returning the same cached list. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Edit Widget after importing now opens the Widget Builder instead of an empty editor screen. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Removed the empty gap under shorter thumbnails in the Templates and Widgets libraries. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: The loading spinner logo no longer fails to load on the Widgets Library. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Elementor editor infinite loading issue fixed. </span><br>', 'master-addons')
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
