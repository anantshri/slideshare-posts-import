/**
 * Add a 'nl2br' PHP like function to String object prototype.
 *
 * @return {string} The new string.
 */
String.prototype.nl2br = function()
{
    return this.replace(/\n/g, "<br />");
};

(function($) {
	"use strict";
	
	/**
	 * Reset admin import page
	 */
	function resetImportInputs() {
		displayActivityIndicator('#slideshare-import-container', false);
		displayNoticeContainer(false);
		setNoticeMessage('', '');
		$('#slideshare-skiped-list').hide().find('ul:first').html('');
	};
	
	/**
	 * Disable import submit button on admin import page on ajax request.
	 * This disallow user to launch another import when one is already in progress
	 *
	 * @param {boolean} disabled If the button must be disabled or not.
	 */
	function disableImportButton(disabled)
	{
		if(disabled)
			$('#slideshare-import').attr('disabled', 'disabled');
		else
			$('#slideshare-import').removeAttr('disabled');
	};

	$(function() {
		/*
		 * Import submission handler
		 */
		$('#slideshare-import').on('click', function() {
			
			resetImportInputs();
			disableImportButton(true);
			displayActivityIndicator('#slideshare-import-container', true);
			
			$.ajax({
 				type : "get",
			    url : SlideShareAjaxParams.ajaxurl,
			    data : {
					action: 'import_slideshows',
					nonce: $('#_wpnonce').val(),
					memory: $('#memory').val() 
				},
			    dataType : "json",
			    success: function(response) {
					
					displayActivityIndicator('#slideshare-import-container', false);
					
					if(!response) {
						setNoticeMessage('Error', 'Unknown error', true);
					} else if(response.success) {
						var message = SlideShareAjaxParams.import_success_message
							.replace("{0}", response.data.slideshows_count)
							.replace("{1}", response.data.slideshare_user)
							.replace("{2}", response.data.new_posts.length);
						setNoticeMessage(SlideShareAjaxParams.import_success_label, message);
						
						if(response.data.skiped_posts.length > 0) {
							$('#slideshare-skiped-list span').html(SlideShareAjaxParams.import_skiped_message
							.replace("{0}", response.data.skiped_posts.length));
							
							for(var index in response.data.skiped_posts) {
								var post = response.data.skiped_posts[index];
								var $post = $('<li/>').html('<strong>'+post.ID+'</strong> : '+post.post_title);
								$('#slideshare-skiped-list ul').append($post);
							}
						
							$('#slideshare-skiped-list').fadeIn();
						}
						// fillSlideshowsTable(response.data);
						// $('#slideshare-list').show();
			        } else {
						for(var code in response.data.errors) {
							var message = code + ' : ' + response.data.errors[code].join("<br/>");
							setNoticeMessage(SlideShareAjaxParams.default_error_label, message, true);
						}
			        }
					displayNoticeContainer(true, true);
					disableImportButton(false);
			    },
			    error: function(response, textStatus, errorThrown) {
					displayActivityIndicator('#slideshare-import-container', false);
					var message = typeof errorThrown == 'object' ? errorThrown.stack : errorThrown;
					setNoticeMessage(textStatus, message.nl2br(), true);
					displayNoticeContainer(true, true);
					disableImportButton(false);
			    }
			});
		});

	});

})(jQuery);