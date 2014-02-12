
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
			    data : {
					action: 'import_slideshows',
					nonce: AjaxParams.import_nonce
				},
			    dataType : "json",
			    success: function(response) {
					
					displayActivityIndicator('#slideshare-import-container', false);
					
					if(!response) {
						setNoticeMessage('Error', 'Unknown error', true);
					} else if(response.success) {
						var message = AjaxParams.import_success_message
							.replace("{0}", response.data.slideshows_count)
							.replace("{1}", response.data.slideshare_user)
							.replace("{2}", response.data.posts_count);
						setNoticeMessage(AjaxParams.import_success_label, message);
						// fillSlideshowsTable(response.data);
						// $('#slideshare-list').show();
			        } else {
						for(var code in response.data.errors) {
							var message = code + ' : ' + response.data.errors[code].join("<br/>");
							setNoticeMessage(AjaxParams.default_error_label, message, true);
						}
			        }
					displayNoticeContainer(true, true);
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