(function($) {
	"use strict";

	function resetImportInputs() {
		displayActivityIndicator('#slideshare-import-container', false);
		displayErrorContainer(false);
		setErrorMessage('', '');
	}

	$(function() {
		/*
		 * Import submission handler
		 */
		$('#slideshare-import').on('click', function() {
			
			displayActivityIndicator('#slideshare-import-container', true);
			
			$.ajax({
			    url : AjaxParams.submit_import_url,
 				type : "GET",
//				async: false,
//				contentType: 'Content-Type: application/json; charset=UTF-8',
			    dataType : "json",
//			    data : {},
			    success: function(response) {
					console.log('success');
					console.log(response);
					
					displayActivityIndicator('#slideshare-import-container', false);
					
					if(!response) {
						setNoticeMessage('Error', 'Unknown error', true);
						return;
					}
					
			        if(response.success) {
						var list = response.data;
						console.log(list);
						$('#slideshare-list').html(response.data);
						$('#slideshare-count').html(response.data.length);
			        } else {
						console.log(response.data);
						setNoticeMessage(response.data.error_code, response.data.error_message, true);
			        }
			    },
			    error: function(response, textStatus, errorThrown) {
					displayActivityIndicator('#slideshare-import-container', false);
					setNoticeMessage(textStatus, errorThrown, true);
					displayNoticeContainer(true);
			    }
			});
		});

	});

}(jQuery));