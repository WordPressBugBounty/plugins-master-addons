<?php do_action('masteraddons/template/before_footer'); ?>
	<div class="jltma-comments-template">
		<?php
			$template = \MasterAddons\Inc\Admin\Theme_Builder\Activator::template_ids();
			echo \MasterAddons\Inc\Admin\Theme_Builder\Theme_Builder::render_elementor_content($template[2]);
		?>
	</div>
<?php do_action('masteraddons/template/after_footer'); ?>
