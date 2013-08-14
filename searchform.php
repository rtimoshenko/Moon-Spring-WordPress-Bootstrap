<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
    <div>
    	<label class="screen-reader-text" for="s"><?php _e('Search for', 'moonspring'); ?>:</label>
        <input type="text" value="<?php echo isset($_GET['s']) ? $_GET['s'] : __('Search', 'moonspring'); ?>" name="s" id="s" />
        <input type="submit" id="searchsubmit" value="<?php _e('Search', 'moonspring'); ?>" />
    </div>
</form>