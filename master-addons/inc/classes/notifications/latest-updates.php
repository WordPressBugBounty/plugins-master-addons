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
                __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Improved: Template, kit, widget and popup libraries now load one page at a time, sized to the window, and keep the next page ready so scrolling does not wait on the network. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Improved: Libraries paint the previous visit\'s first page straight away instead of showing a loading screen. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Improved: Templates and kits are no longer copied into the site\'s uploads folder. Everything is served from the remote library, and copies left by earlier versions are removed on activation. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Template screenshots keep their own proportions instead of being cropped to a fixed frame, and are served full size. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Pro templates are marked Pro in the Template Library, and importing or inserting one without a licence now offers the upgrade instead of failing part way through. The preview stays available either way. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: The All Keywords filter lists its keywords and opens without running off the edge of the modal. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: MA Comments lost its submit button and its name, email and website fields, showed a logged-out visitor the last signed-in user\'s name, and rendered the like and dislike counts without their icons. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Author archive templates render the author name and profile picture instead of leaving them empty. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: The Template Library opens on Pages, and its page no longer has a horizontal scrollbar. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Template Kits no longer reports &quot;No Templates Found&quot; for a library it has just fetched. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Refreshing the template cache from the editor no longer throws, and the misleading cache count beside it is gone. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: The plugin no longer leaves folders under uploads/master_addons. The cache directory was recreated on every page load, so deleting the tree brought it straight back empty; anything left by an earlier version is removed on activation. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Pages imported from a kit are named after the kit again &mdash; &quot;Fundora - Home&quot; rather than &quot;New - Home&quot;. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Importing the same template twice now numbers the second copy instead of leaving two entries with the same title. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: A site with an active licence is no longer told to upgrade. Pro templates showed the Pro badge and the upgrade prompt even after activation, because the check asked only for a licence key and a site licensed through Freemius has none. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Fixed: Filtering the Template Library by category returns that category\'s templates instead of the first page of everything. </span><br>', 'master-addons')
                . __('<span class="dashicons dashicons-yes"></span> <span class="jltma-changes-list">Changed: Theme Builder lists Archive, Category, Tag, Author and Date under their own Archive heading. </span><br>', 'master-addons')
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
