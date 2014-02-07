<?php
class SlideShare 
{
	private $id;
	private $description;
	private $conversion_state; // 0 if queued for conversion, 1 if converting, 2 if converted, 3 if conversion failed
	private $username;
	private $web_permalink;
	private $thumbnail_url;
	private $smaller_thumbnail_url;
	private $embed_code;
	private $date_created;
	private $date_last_update;
	private $language; // language, as specified by two-letter code
	private $extension; // ppt or pdf, pps, odp, doc, pot, txt, rdf
	private $available_to_download; // 1 if available to download, else 0
	private $type; // 0 if presentation, 1 if document, 2 if a portfolio, 3 if video
	private $contest_part; // 1 if part of a contest, 0 if not
	private $user_id;
	private $external_app_user_id; // ExternalAppUserID if uploaded using an external app
	private $external_app_id; // ExternalAppID for the external app
	private $ppt_location;
	private $stripped_title;
	private $tag_name;
	private $download_count;
	private $view_count;
	private $comment_count;
	private $favorite_count;
	private $slides_count;
	private $private;
	private $flagged;
	private $show_on_slideshare;
	private $secret_url_enabled;
	private $embeds_allowed;
	private $contacts_allowed;
}