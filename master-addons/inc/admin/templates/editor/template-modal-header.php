<?php

/**
 * Template Library Header
 *
 * Rendered as an Underscore/Backbone view template on editor load, so no cache
 * lookups happen here. The cache count is fetched over AJAX only when the
 * Master Addons templates modal is actually opened.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div id="ma-el-template-modal-header-logo-area"></div>
<div id="ma-el-template-modal-header-tabs"></div>
<div id="ma-el-template-modal-header-actions">
    <?php if (class_exists('MasterAddons\Inc\Classes\Template_Library_Cache')): ?>
        <div id="ma-el-template-cache-status" class="elementor-template-library-header-item" style="display:none;" title="<?php echo esc_attr__('Cache Status', 'master-addons'); ?>">
            <i class="eicon-database"></i>
            <span class="cache-count"></span>
        </div>
        <div id="ma-el-template-cache-refresh" class="elementor-template-library-header-item" title="<?php echo esc_attr__('Refresh Cache', 'master-addons'); ?>">
            <i class="eicon-sync"></i>
        </div>
    <?php endif; ?>
</div>
<div id="ma-el-template-modal-header-close-modal" class="elementor-template-library-header-item" title="<?php echo esc_attr__( 'Close', 'master-addons' ); ?>">
        <i class="eicon-close" title="Close"></i>
</div>
