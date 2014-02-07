<?php
/**
 * Slideshow object model.
 *
 * @package   Slideshow
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */	
class Slideshow
{
	private $id;
	private $title;
	private $description;
	private $conversion_state; // 0 if queued for conversion, 1 if converting, 2 if converted, 3 if conversion failed
	private $username;
	private $web_permalink;
	private $thumbnail_url;
	private $thumbnail_size;
	private $smaller_thumbnail_url;
	private $embed_code;
	private $date_created;
	private $date_last_update;
	private $language; // language, as specified by two-letter code
	private $format; // ppt or pdf, pps, odp, doc, pot, txt, rdf
	private $available_for_download; // 1 if available to download, else 0
	private $download_url;
	private $type; // 0 if presentation, 1 if document, 2 if a portfolio, 3 if video
	private $in_contest; // 1 if part of a contest, 0 if not
	
	private $user_id;
	private $ppt_location;
	private $stripped_title;
	private $tags = array();
	private $is_audio;
	
	private $downloads_count;
	private $views_count;
	private $comments_count;
	private $favorites_count;
	private $slides_count;

	private $related_slideshows = array();
	
	private $external_app_user_id; // ExternalAppUserID if uploaded using an external app
	private $external_app_id; // ExternalAppID for the external app
	
	private $private;
	private $flagged;
	private $show_on_slideshare;
	private $secret_url_enabled;
	private $embeds_allowed;
	private $contacts_allowed;
	
	/**
	 * Load object data from XML
	 *
	 * @param SimpleXMLElement The XML representation of the slideshow
	 * @return Slideshow The filled Slideshow object
 	 *
 	 * @since    1.0.0
	 */
	public function loadFromXML($xml)
	{
		foreach($xml->children() as $child) {
			switch((string) $child->getName()) {
				case 'ID':                $this->id = (string) $child; break;
				case 'Title':             $this->title = (string) $child; break;
				case 'Description':       $this->description = (string) $child; break;
				case 'Status':            $this->conversion_state = (string) $child; break;
				case 'Username':          $this->username = (string) $child; break;
				case 'URL':               $this->web_permalink = (string) $child; break;
				case 'ThumbnailURL':      $this->thumbnail_url = (string) $child; break;
				case 'ThumbnailSize':     $this->thumbnail_size = (string) $child; break;
				case 'ThumbnailSmallURL': $this->thumbnail_smaller_url = (string) $child; break;
				case 'Embed':             $this->embed_code = (string) $child; break;
				case 'Created':           $this->date_created = (string) $child; break;
				case 'Updated':           $this->date_last_update = (string) $child; break;
				case 'Language':          $this->language = (string) $child; break;
				case 'Format':            $this->format = (string) $child; break;
				case 'Download':          $this->available_for_download = (string) $child; break;
				case 'DownloadURL':       $this->download_url = (string) $child; break;
				case 'SlideshowType':     $this->type = (string) $child; break;
				case 'InContest':         $this->in_contest = (string) $child; break;
				case 'UserID':            $this->user_id = (string) $child; break;
				case 'PPTLocation':       $this->ppt_location = (string) $child; break;
				case 'StrippedTitle':     $this->stripped_title = (string) $child; break;
				case 'Tags': 
					foreach($child->Tags as $tagXML) {
						$tag = new Tag();
						$this->tags[] = $tag->loadFromXML($tagXML);
					}
					break;
				case 'Audio':             $this->is_audio = (string) $child; break;
				case 'NumDownloads':      $this->downloads_count = (string) $child; break;
				case 'NumViews':          $this->views_count = (string) $child; break;
				case 'NumComments':       $this->comments_count = (string) $child; break;
				case 'NumFavorites':      $this->favorites_count = (string) $child; break;
				case 'NumSlides':         $this->slides_count = (string) $child; break;
				case 'RelatedSlideshows': break;
				case 'PrivacyLevel':      $this->private = (bool) $child; break;
				case 'FlagVisible':       $this->flagged = (bool) $child; break;
				case 'ShowOnSS':          $this->show_on_slideshare = (bool) $child; break;
				case 'SecretURL':         $this->secret_url_enabled = (bool) $child; break;
				case 'AllowEmbed':        $this->embeds_allowed = (bool) $child; break;
				case 'ShareWithContacts': $this->contacts_allowed = (bool) $child; break;
				default:
			}
		}
		return $this;
	}
}