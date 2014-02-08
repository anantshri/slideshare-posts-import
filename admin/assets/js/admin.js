(function ( $ ) {
	"use strict";

	function displayActivityIndicator(selector, show) {
		if(show)
			$(selector).find('.activity-indicator').show();
		else
			$(selector).find('.activity-indicator').hide();
	}

	$(function () {

	});

}(jQuery));