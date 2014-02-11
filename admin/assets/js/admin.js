
String.prototype.nl2br = function()
{
    return this.replace(/\n/g, "<br />");
};

(function($) {
	"use strict";

	function resetImportInputs() {
		displayActivityIndicator('#slideshare-import-container', false);
		displayNoticeContainer(false);
		setNoticeMessage('', '');
	};

	$(function() {
		/*
		 * Import submission handler
		 */
		$('#slideshare-import').on('click', function() {
			
			resetImportInputs();
			displayActivityIndicator('#slideshare-import-container', true);
			
			$.ajax({
 				type : "get",
			    url : AjaxParams.ajaxurl,
			    data : {action: 'import_slideshows'},
			    dataType : "json",
			    success: function(response) {
					
					displayActivityIndicator('#slideshare-import-container', false);
					
					if(!response) {
						setNoticeMessage('Error', 'Unknown error', true);
						return;
					}
					
			        if(response.success) {
						$('#slideshare-list').html(response.data.slideshows);
						$('#slideshare-total').html(response.data.count);
			        } else {
						for(var code in response.data.errors) {
							var message = code + ' : ' + response.data.errors[code][0];
							setNoticeMessage(AjaxParams.default_error_label, message, true);
						}
						displayNoticeContainer(true, true);
			        }
			    },
			    error: function(response, textStatus, errorThrown) {
					displayActivityIndicator('#slideshare-import-container', false);
					setNoticeMessage(textStatus, errorThrown.stack.nl2br(), true);
					displayNoticeContainer(true, true);
			    }
			});
		});

	});

})(jQuery);