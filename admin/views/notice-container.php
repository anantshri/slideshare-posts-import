<?php
/**
 * Represents the view of the flash messages on the admin pages.
 *
 * @package   Slideshare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */
?>
<script type="text/javascript" charset="utf-8">

	if(typeof displayNoticeContainer != 'function') {
		function displayNoticeContainer(show, animate, blink) {
			if(animate) {
				if(show) {
					jQuery('#notice').fadeIn();
					if(blink) {
						setInterval(function() {
							jQuery('#notice').fadeOut();
						}, 1200);
					}
				} else {
					jQuery('#notice:visible').fadeOut();
				}
			} else {
				if(show) {
					jQuery('#notice').show();
					if(blink) {
						setInterval(function() {
							jQuery('#notice').hide();
						}, 1200);
					}
				} else {
					jQuery('#notice').hide();
				}
			}
		}
	}
	
	if(typeof setNoticeMessage != 'function') {
		function setNoticeMessage(title, message, error) {
			jQuery('#notice-title').html(title);
			jQuery('#notice-message').html(message);
			
			jQuery('#notice')
				.removeClass('error updated')
				.addClass(function(index) {
					return error ? 'error' : 'updated';
				});
		}
	}
	
</script>

<div id="notice" class="">
	<p><strong id="notice-title"></strong></p>
	<p id="notice-message"></p>
</div>
