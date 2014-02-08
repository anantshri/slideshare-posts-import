<script type="text/javascript" charset="utf-8">

	if(typeof displayErrorContainer != 'function') {
		function displayErrorContainer(show) {
			if(show)
				jQuery('#notice').fadeIn();
			else
				jQuery('#notice').fadeOut();
		}
	}
	
</script>

<div id="notice" class="error">
	<strong id="notice-title"></strong>
	<p id="notice-message"></p>
</div>
