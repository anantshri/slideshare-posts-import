<?php
/**
 * Create WordPress posts from SlideShare slideshows
 *
 * @package   admin/includes
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */
class SlideShareImporter
{
	private $posts = array();
	private $slideshows;
	
	public function __construct($slideshows)
	{
		$this->slideshows = $slideshows;
	}
	
	public function import()
	{
		foreach($this->slideshows as $slideshow) {
			$post_id = $this->createPost($slideshow);
			
			if($post_id) {
				$this->createMetadata($post_id, $slideshow);
				$this->addThumbnail($post_id, $slideshow->getThumbnailUrl(), $slideshow->getThumbnailSize());
				$this->posts[] = get_post($post_id);
			} else {
				error_log('error creating post from slideshow '.$slideshow->getId());
			}
		}
	}
	
	public function getPosts()
	{
		return $this->posts;
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
//		  'comment_status' => [ 'closed' | 'open' ] // Default is the option 'default_comment_status', or 'closed'.
//		  'post_category'  => [ array(<category id>, ...) ] // Default empty.
//		  'tags_input'     => [ '<tag>, <tag>, ...' | array ] // Default empty.
//		  'tax_input'      => [ array( <taxonomy> => <array | string> ) ] // For custom taxonomies. Default empty.
		);
		
		return wp_insert_post($post); 
	}
	
	private function createMetadata($post_id, Slideshow $item)
	{
		foreach($item->getAvailableMetadata() as $key) {
			
			$method = join('', array_map('ucfirst', array_slice(explode('_', $key), 1)));
			$getter = 'get'.$method;
			
			if(method_exists($item, $getter)) {
				$value = call_user_func(array($item, $getter));
				delete_post_meta($post_id, $key);			
			} else {
				$getter = 'is'.$method;
			}
			
			if(method_exists($item, $getter)) {
				$value = call_user_func(array($item, $getter));
				delete_post_meta($post_id, $key);			
			}
			
			error_log("======= $post_id : $key => $getter - $value =======");
			
			if($value && !empty($value)) {
				update_post_meta($post_id, $key, $value);
			}
		}
	}
	
	private function addThumbnail($post_id, $url, $size)
	{
		$meta = get_post_meta($post_id, '_thumbnail_id', true);
		
		if(!wp_is_post_revision($post_id) and (!$meta or empty($meta) or (is_string($meta) and strlen($meta) < 1))) {
			$attachment_id = $this->uploadThumbnail($post_id, $url);
			
			if($attachment_id !== false && !$attachment_id instanceof WP_Error) {
				update_post_meta($post_id, '_thumbnail_id', $attachment_id);
			}
		}
		unset($meta);
	}
	
	private function uploadThumbnail($post_id, $url)
	{
		if ( ! empty($url) ) {
			// Download file to temp location
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
	
	private function addTag()
	{
		
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

