<?php
/**
 * Slideshow REST API calls.
 *
 * @package   api/services
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @link      http://fr.slideshare.net/developers/documentation#get_slideshow
 * @link      http://fr.slideshare.net/developers/documentation#edit_slideshow
 * @link      http://fr.slideshare.net/developers/documentation#delete_slideshow
 * @link      http://fr.slideshare.net/developers/documentation#upload_slideshow
 * @link      http://fr.slideshare.net/developers/documentation#search_slideshows
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideShareSlideshowService extends SlideShareService
{
	/**
	 * TODO: to be tested
	 */
	private function createRequest($service, $parameters, $username = null, $password = null)
	{
		$this->credentials->setUserCredentials($username, $password);
		
		$request = new SlideShareHttpRequest($service, $this->credentials);
		
		foreach($parameters as $key => $value) {
			$request->addParameter($key, $value);
		}
		return $request;
	}
	
	/**
	 * TODO: to be tested
	 */
	private function get($id = null, $url = null, $username = null, $password = null, $optional = array()) 
	{
		$params = array();
		
		if(!is_null($id))
			$params['slideshow_id'] = $id;
		if(!is_null($url))
			$params['slideshow_url'] = $url;
		
		$params = array_merge($params, $optional);
		$request = $this->createRequest('get_slideshows', $params, $username, $password);
		
		return $request->get();
	}
	
	/**
	 * TODO: to be tested
	 */
	public function getById($id, $username = null, $password = null, $optional = array()) 
	{
		return $this->get($id, null, $username, $password, $optional);
	}
	
	/**
	 * TODO: to be tested
	 */
	public function getByURL($url, $username = null, $password = null, $optional = array()) 
	{
		return $this->get(null, $url, $username, $password, $optional);
	}
	
	/**
	 * TODO: to be tested
	 */
	public function edit($id, $data, $username, $password, $optional = array()) 
	{
		$params = array_merge(array('slideshow_id' => $query), $optional);
		$request = $this->createRequest('edit_slideshow', $params, $username, $password);
		
		return $request->get();	
	}
	
	/**
	 * TODO: to be tested
	 */
	public function delete($id, $data, $username, $password, $optional = array()) 
	{
		$params = array_merge(array('slideshow_id' => $query), $optional);
		$request = $this->createRequest('delete_slideshow', $params, $username, $password);
		
		return $request->get();
	}
	
	/**
	 * TODO: to be tested
	 */
	public function uploadFromFile($src, $title, $username, $password, $optional = array()) 
	{
		return $this->upload($title, $username, $password, null, $src, $optional);
	}
	
	/**
	 * TODO: to be tested
	 */
	public function uploadFromURL($url, $title, $username, $password, $optional = array())
	{
		return $this->upload($title, $username, $password, $url, null, $optional);
	}
	
	/**
	 * TODO: to be tested
	 *
	 * Note: This method requires extra permissions. 
	 *       If you want to upload a file using SlideShare API, 
	 *       please send an email to api@slideshare.com with 
	 *       your developer account username describing the use case.
	 */
	public function upload($title, $username, $password, $url = null, $src = null, $optional = array()) 
	{
		$params = array('slideshow_title' => $title);
		
		if(!is_null($url))
			$params['upload_url'] = $url;
		
		$params = array_merge($params, $optional);
		$request = $this->createRequest('upload_slideshow', $params, $username, $password);
		
		if(!is_null($src)) {
			return $request->post($src);
		} else {
			return $request->get();
		}
	}
	
	/**
	 * TODO: to be tested
	 */
	public function search($query, $optional = array()) 
	{
		$params = array_merge(array('q' => $query), $optional);
		$request = $this->createRequest('search_slideshows', $params, $username, $password);
		
		return $request->get();
	}
}