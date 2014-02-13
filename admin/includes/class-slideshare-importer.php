<?php
/**
 * Create WordPress posts from SlideShare slideshows
 *
 * @package   SlideShareImporter
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import/admin/includes/class-slideshare-importer.php
 * @copyright 2014 Spoon
 */
class SlideShareImporter
{
	private $posts = array();
	private $errors = null;
	private $errorsCode;
	private $slideshows;
	
	public function __construct($slideshows)
	{
		$this->slideshows = $slideshows;
		$this->errorsCode = __('Import error');
	}
	
	public function import()
	{
		foreach($this->slideshows as $slideshow) {
			try {
				$post_id = $this->createPost($slideshow);
			
				if(!$post_id instanceof WP_Error) {
					$this->createMetadata($post_id, $slideshow);
					$this->addThumbnail($post_id, $slideshow->getThumbnailUrl(), $slideshow->getThumbnailSize());
					$this->addTags($post_id, $slideshow->getTags());
					$this->posts[] = get_post($post_id);
				} else {
					$error = $post_id;
					throw new SlideShareException($this->errorsCode, $error->get_error_message());
				}
			} catch(SlideShareException $exception) {
				$this->setError($exception->getMessage());
			}
		}
	}
	
	public function getPosts()
	{
		return $this->posts;
	}
	
	private function setError($error)
	{
		if(!$this->errors) {
			$this->errors = new WP_Error($this->errorsCode);
		}
		$this->errors->add($this->errorsCode, $error);
	}
	
	public function hasErrors()
	{
		return $this->errors && count($this->errors->get_error_messages()) > 0;
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	private function createPost(Slideshow $item)
	{
		$post = array(
//		  'ID'             => $item->getId(), // Are you updating an existing post?
		  'post_content'   => $item->getDescription(), // The full text of the post.
		  'post_name'      => sanitize_title($item->getTitle()), // The name (slug) for your post
		  'post_title'     => $item->getTitle(), // The title of your post.
		  'post_status'    => (bool) get_option('SLIDESHARE_AUTOPUBLISH') ? 'publish' : 'draft', // Default 'draft'.
		  'post_type'      => 'post', // Default 'post'.
		  'post_author'    => get_option( 'SLIDESHARE_PUBLISHER'), // The user ID number of the author. Default is the current user ID.
//		  'ping_status'    => [ 'closed' | 'open' ] // Pingbacks or trackbacks allowed. Default is the option 'default_ping_status'.
//		  'post_parent'    => // Sets the parent of the new post, if any. Default 0.
//		  'to_ping'        => // Space or carriage return-separated list of URLs to ping. Default empty string.
//		  'pinged'         => // Space or carriage return-separated list of URLs that have been pinged. Default empty string.
//		  'post_password'  => // Password for post, if any. Default empty string.
//		  'guid'           => // Skip this and let Wordpress handle it, usually.
//		  'post_content_filtered' => // Skip this and let Wordpress handle it, usually.
//		  'post_excerpt'   =>  // For all your post excerpt needs.
		  'post_date'      => $this->getFormattedDate($item->getCreatedDate()), // [ Y-m-d H:i:s ] // The time post was made.
		  'post_date_gmt'  => $this->getFormattedGMTDate($item->getCreatedDate()), // [ Y-m-d H:i:s ] // The time post was made, in GMT.
		  'comment_status' => 'open', // [ 'closed' | 'open' ] Default is the option 'default_comment_status', or 'closed'.
//		  'post_category'  => [ array(<category id>, ...) ] // Default empty.
//		  'tags_input'     => [ '<tag>, <tag>, ...' | array ] // Default empty.
//		  'tax_input'      => [ array( <taxonomy> => <array | string> ) ] // For custom taxonomies. Default empty.
		);
		
		return wp_insert_post($post, true); 
	}
	
	private function createMetadata($post_id, Slideshow $item)
	{
		foreach($item->getAvailableMetadata() as $key) {
			
			$method = join('', array_map('ucfirst', array_slice(explode('_', $key), 1)));
			
			if(method_exists($item, 'get'.$method)) {
				$getter = 'get'.$method;
			}else if(method_exists($item, 'is'.$method)) {
				$getter = 'is'.$method;
			} else {
				$getter = null;
			}
			
			if($getter) {
				$value = call_user_func(array($item, $getter));
				delete_post_meta($post_id, $key);			
			}

			$result = null;
			if($value && !empty($value)) {
				$result = update_post_meta($post_id, $key, $value);
			}
			
			if($result instanceof WP_Error) {
				throw new SlideShareException($this->errorsCode, $result->get_error_message());
			}
		}
	}
	
	private function addThumbnail($post_id, $url, $size)
	{
		$meta = get_post_meta($post_id, '_thumbnail_id', true);

		if(!wp_is_post_revision($post_id) and (!$meta or empty($meta) or (is_string($meta) and strlen($meta)))) {
			$attachment_id = $this->uploadThumbnail($post_id, $url);
			
			$result = null;
			if($attachment_id instanceof WP_Error) {
				$result = $attachment_id;
			} else if($attachment_id !== false) {
				$result = update_post_meta($post_id, '_thumbnail_id', $attachment_id);
			}
			
			if($result instanceof WP_Error) {
				throw new SlideShareException($this->errorsCode, $result->get_error_message());
			}
		}
		unset($meta);
	}
	
	private function uploadThumbnail($post_id, $url)
	{
		if ( ! empty($url) ) {
			
			// Download file to temp location
			$scheme = parse_url( $url, PHP_URL_SCHEME );
			if(empty($scheme)) {
				$url = 'http:'.$url;
			}
			
			add_filter( 'http_request_args', array($this, 'allowUnsafeUrlsCallback'));
			$tmp = download_url( $url );
		
			// Set variables for storage
			// fix file filename for query strings
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $url, $matches );
			$file_array['name'] = basename($matches[0]);
			$file_array['tmp_name'] = $tmp;
		
			// If error storing temporarily, unlink
			if ( is_wp_error( $tmp ) ) {
				@unlink($file_array['tmp_name']);
				$file_array['tmp_name'] = '';
				error_log($tmp->get_error_message());
			}
		
			// do the validation and storage stuff
			$id = media_handle_sideload( $file_array, $post_id, null );
			
			// If error storing permanently, unlink
			if ( is_wp_error($id) ) {
				@unlink($file_array['tmp_name']);
			}
				
			return $id;
		}
		return false;	
	}
	
	public function allowUnsafeUrlsCallback($args)
	{
	   $args['reject_unsafe_urls'] = false;
	   return $args;
	}
	
	private function addTags($post_id, $list)
	{
		$tags = $list ? join(',', $list) : '';
		return wp_set_post_tags($post_id, $tags, false);
	}
	
	private function getFormattedDate($date)
	{
		if(!$date)
			$date = new DateTime("now");
		return $date->format('Y-m-d H:i:s');
	}
	
	private function getFormattedGMTDate($date)
	{
		if(!$date)
			$date = new DateTime("now");
		return gmdate('Y-m-d H:i:s', $date->getTimestamp());
	}
}

