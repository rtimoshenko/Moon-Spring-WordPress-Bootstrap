<div id="sidebar">
	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('custom-sidebar')) : ?>

		<?php catmenu(); ?>
		<?php /*gravity_form($id, $display_title=true, $display_description=true, $display_inactive=false, $field_values=null, $ajax=false, $tabindex);*/ ?>

	<?php endif; ?>
</div><!-- / sidebar -->