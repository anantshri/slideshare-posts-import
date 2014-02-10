<script type="text/javascript" charset="utf-8">

	if(typeof displayNoticeContainer != 'function') {
		function displayNoticeContainer(show, blink) {
			if(show && blink)
				jQuery('#notice').fadeIn().fadeOut();
			else if(show)
				jQuery('#notice').fadeIn()
			else
				jQuery('#notice').fadeOut();
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
