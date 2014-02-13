<?php
/**
 * Slideshow object model.
 *
 * @package   Slideshow
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import/includes/api/model/class-slideshow-model.php
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */	
class Slideshow extends SlideShareModel
{	
	private $id;
	private $title;
	private $description;
	private $conversion_state;       // 0 if queued for conversion, 1 if converting, 2 if converted, 3 if conversion failed
	private $username;
	private $web_permalink;
	private $thumbnail_url;
	private $thumbnail_size;
	private $smaller_thumbnail_url;
	private $embed_code;
	private $created_date;
	private $last_update_date;
	private $language;               // language, as specified by two-letter code
	private $format;                 // ppt or pdf, pps, odp, doc, pot, txt, rdf
	private $available_for_download; // 1 if available to download, else 0
	private $download_url;
	private $type;                   // 0 if presentation, 1 if document, 2 if a portfolio, 3 if video
	private $in_contest;             // 1 if part of a contest, 0 if not
	
	private $user_id;
	private $ppt_location;
	private $stripped_title;
	private $tags = array();
	private $audio;
	
	private $downloads_count;
	private $views_count;
	private $comments_count;
	private $favorites_count;
	private $slides_count;

	private $related_slideshows_ids = array();
	private $related_slideshows = array();
	
	private $private;
	private $flagged;
	private $show_on_slideshare;
	private $secret_url_enabled;
	private $embeds_allowed;
	private $contacts_allowed;
	
	/*
	 * TODO: don't know if it's really used...
	 */
	private $external_app_user_id;   // ExternalAppUserID if uploaded using an external app
	private $external_app_id;        // ExternalAppID for the external app
	
	/**
	 * Constructor
 	 *
 	 * @since    1.0.0
	 */
	public function __construct()
	{
		parent::__construct('slideshare_');
	}
	
	/**
	 * Load object data from XML
	 *
	 * @param SimpleXMLElement $xml The XML representation of the slideshow
	 * @return Slideshow The filled Slideshow object
 	 *
 	 * @since    1.0.0
	 */
	public function loadFromXML(SimpleXMLElement $xml)
	{
		foreach($xml->children() as $child) {
			switch((string) $child->getName()) {
				case 'ID':                $this->id = (string) $child; break;
				case 'Title':             $this->title = (string) $child; break;
				case 'Description':       $this->description = (string) $child; break;
				case 'Status':            $this->conversion_state = (int) $child; break;
				case 'Username':          $this->username = (string) $child; break;
				case 'URL':               $this->web_permalink = (string) $child; break;
				case 'ThumbnailURL':      $this->thumbnail_url = (string) $child; break;
				case 'ThumbnailSize':     $this->thumbnail_size = (string) $child; break;
				case 'ThumbnailSmallURL': $this->thumbnail_smaller_url = (string) $child; break;
				case 'Embed':             $this->embed_code = (string) $child; break;
				case 'Created':           $this->created_date = new DateTime((string) $child); break;
				case 'Updated':           $this->last_update_date = new DateTime((string) $child); break;
				case 'Language':          $this->language = (string) $child; break;
				case 'Format':            $this->format = (string) $child; break;
				case 'Download':          $this->available_for_download = (bool) $child; break;
				case 'DownloadUrl':       $this->download_url = (string) $child; break;
				case 'SlideshowType':     $this->type = (string) $child; break;
				case 'InContest':         $this->in_contest = (bool) $child; break;
				case 'UserID':            $this->user_id = (string) $child; break;
				case 'PPTLocation':       $this->ppt_location = (string) $child; break;
				case 'StrippedTitle':     $this->stripped_title = (string) $child; break;
				case 'Tags': 
					foreach($child->Tag as $node) {
						$tag = new Tag();
						$this->tags[] = $tag->loadFromXML($node);
					}
					break;
				case 'Audio':             $this->audio = (bool) $child; break;
				case 'NumDownloads':      $this->downloads_count = (int) $child; break;
				case 'NumViews':          $this->views_count = (int) $child; break;
				case 'NumComments':       $this->comments_count = (int) $child; break;
				case 'NumFavorites':      $this->favorites_count = (int) $child; break;
				case 'NumSlides':         $this->slides_count = (int) $child; break;
				case 'RelatedSlideshows': break;
				case 'PrivacyLevel':      $this->private = (bool) $child; break;
				case 'FlagVisible':       $this->flagged = (bool) $child; break;
				case 'ShowOnSS':          $this->show_on_slideshare = (bool) $child; break;
				case 'SecretURL':         $this->secret_url_enabled = (bool) $child; break;
				case 'AllowEmbed':        $this->embeds_allowed = (bool) $child; break;
				case 'ShareWithContacts': $this->contacts_allowed = (bool) $child; break;
				default:
					throw new SlideShareParserException('Unknown node name', "SlideShare response XML parsing failed ! Unknown Slideshow's XML child '".$child->getName()."'");
			}
		}
		return $this;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function getConversionState()
	{
		return $this->conversion_state;
	}
	
	public function getWebPermalink()
	{
		return $this->web_permalink;
	}
	
	public function getThumbnailUrl()
	{
		return $this->thumbnail_url;
	}
	
	public function getThumbnailSize()
	{
		return $this->thumbnail_size;
	}
	
	public function getSmallerThumbnailUrl()
	{
		return $this->smaller_thumbnail_url;
	}
	
	public function getEmbedCode()
	{
		return $this->embed_code;
	}
	
	public function getCreatedDate()
	{
		return $this->created_date;
	}
	
	public function getLastUpdateDate()
	{
		return $this->last_update_date;
	}
	
	public function getLanguage()
	{
		return $this->language;
	}
	
	public function getFormat()
	{
		return $this->format;
	}
	
	public function isAvailableForDownload()
	{
		return $this->available_for_download;
	}
	
	public function getDownloadUrl()
	{
		return $this->download_url;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function isInContest()
	{
		return $this->in_contest;
	}
	
	/*
	 * Detail data
	 */
	
	public function getUserId()
	{
		return $this->user_id;
	}
	
	public function getPptLocation()
	{
		return $this->ppt_location;
	}
	
	public function getStrippedTitle()
	{
		return $this->stripped_title;
	}
	
	public function getTags()
	{
		return $this->tags;
	}
	
	/*
	 * Counts
	 */
	
	public function getDownloadsCount()
	{
		return $this->downloads_count;
	}
	
	public function getViewsCount()
	{
		return $this->views_count;
	}
	
	public function getCommentsCount()
	{
		return $this->comments_count;
	}
	
	public function getFavoritesCount()
	{
		return $this->favorites_count;
	}
	
	public function getSlidesCount()
	{
		return $this->slides_count;
	}

	/**
	 * Load related slideshows with API calls
	 *
	 * @param SlideShareSlideshowService $service The slideshows service instance for API calls.
 	 *
 	 * @since    1.0.0
	 *
	 * TODO: have to be tested !
	 */
	
	public function loadRelatedSlideshows(SlideShareSlideshowService $service)
	{
		foreach($this->related_slideshows_ids as $id) {
			try {
				$slideshow = $service->getById($id, $service->getUsername(), $service->getPassword());
				$this->related_slideshows[] = $slideshow;
			} catch(Exception $exception) {
				error_log($exception);
			}
		}
	}
	
	public function getRelatedSlideshows()
	{
		return $this->related_slideshows;
	}
	
	/*
	 * Booleans
	 */
	
	public function isAudio()
	{
		return $this->audio;
	}
	
	public function isPrivate()
	{
		return $this->private;
	}
	
	public function isFlagged()
	{
		return $this->flagged;
	}
	
	public function isShowOnSlideShare()
	{
		return $this->show_on_slideshare;
	}
	
	public function isSecretUrlEnabled()
	{
		return $this->secret_url_enabled;
	}
	
	public function isEmbedsAllowed()
	{
		return $this->embeds_allowed;
	}
	
	public function isContactsAllowed()
	{
		return $this->contacts_allowed;
	}
}