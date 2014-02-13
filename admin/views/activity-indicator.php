<?php
/**
 * Represents the view of the activity indicator on the admin pages.
 *
 * @package   SlideShare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import/admin/views/activity-indicator.php
 * @copyright 2014 Spoon
 */
?>
<script type="text/javascript" charset="utf-8">

	if(typeof displayActivityIndicator != 'function') {
		function displayActivityIndicator(selector, show) {
			if(show)
				jQuery(selector).find('.activity-indicator').show();
			else
				jQuery(selector).find('.activity-indicator').hide();
		}
	}
	
</script>

<div class="activity-indicator">
	<h3><?php _e('Import in progress...')?></h3>
	<span><img src="<?php echo plugins_url( '../assets/images/ajax-loader.gif', __FILE__ )?>" /></span>
</div>
