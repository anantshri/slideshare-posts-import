<?php
/**
 * User REST API calls.
 *
 * @package   SlideShareAPIUser
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @link      http://fr.slideshare.net/developers/documentation#get_slideshows_by_user
 * @link      http://fr.slideshare.net/developers/documentation#get_user_groups
 * @link      http://fr.slideshare.net/developers/documentation#get_user_favorites
 * @link      http://fr.slideshare.net/developers/documentation#get_user_contacts
 * @link      http://fr.slideshare.net/developers/documentation#get_user_tags
 * @link      http://fr.slideshare.net/developers/documentation#get_user_campaigns
 * @link      http://fr.slideshare.net/developers/documentation#get_user_leads
 * @link      http://fr.slideshare.net/developers/documentation#get_user_campaign_leads
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideShareAPIUser 
{
	private $credentials;
	
	public function __construct($apiKey, $sharedSecret) 
	{
		$this->credentials = new SlideShareCredentials($apiKey, $sharedSecret);
	}
	
	public function getSlideShows($slideshare_user, $username = null, $password = null, $optional = array()) 
	{
		$this->credentials->setUserCredentials($username, $password);
		
		$request = new SlideShareAPIRequest('get_slideshows_by_user', $this->credentials);
		$request->addParameter('username_for', $slideshare_user);
		
		foreach($optional as $key => $value)
			$request->addParameter($key, $value);
		
		return $request->get();
	}
	
}