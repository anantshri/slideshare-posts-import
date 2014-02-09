<script type="text/javascript" charset="utf-8">

	if(typeof displayErrorContainer != 'function') {
		function displayErrorContainer(show, blink) {
			if(show && blink)
				jQuery('#notice').fadeIn().fadeOut();
			else if(show)
				jQuery('#notice').fadeIn()
			else
				jQuery('#notice').fadeOut();
		}
	}
	
	if(typeof setErrorMessage != 'function') {
		function setErrorMessage(title, message) {
			jQuery('#notice-title').html(title);
			jQuery('#notice-message').html(message);
		}
	}
	
</script>

<div id="notice" class="error">
	<strong id="notice-title"></strong>
	<p id="notice-message"></p>
</div>
