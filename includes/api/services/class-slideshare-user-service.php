<?php
/**
 * User REST API calls.
 *
 * @package   SlideshareUserService
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-api-import
 * @see       http://fr.slideshare.net/developers/documentation#get_slideshows_by_user
 * @see       http://fr.slideshare.net/developers/documentation#get_user_groups
 * @see       http://fr.slideshare.net/developers/documentation#get_user_favorites
 * @see       http://fr.slideshare.net/developers/documentation#get_user_contacts
 * @see       http://fr.slideshare.net/developers/documentation#get_user_tags
 * @see       http://fr.slideshare.net/developers/documentation#get_user_campaigns
 * @see       http://fr.slideshare.net/developers/documentation#get_user_leads
 * @see       http://fr.slideshare.net/developers/documentation#get_user_campaign_leads
 * @copyright 2014 Spoon
 *
 * @since    1.0.0
 */
class SlideshareUserService extends SlideshareService
{
	/**
	 * Send an API request to recover slideshows of a Slideshare user.
	 *
	 * @param string $slideshare_user The Slideshare user for who retrieve slideshows
	 * @param string $username The Slideshare username account for service authentication
	 * @param string $password The Slideshare password account for service authentication
	 * @param string $optional The service optional parameters
	 * @return User The Slideshare user object with slideshows.
	 *
	 * @since    1.0.0
	 *
	 * @see http://fr.slideshare.net/developers/documentation#get_slideshows_by_user 
	 */
	public function getSlideshows($slideshare_user, $username = null, $password = null, $optional = array()) 
	{
		$this->credentials->setUserCredentials($username, $password);
		
		$request = new SlideshareHttpRequest('get_slideshows_by_user', $this->credentials);
		$request->addParameter('username_for', $slideshare_user);
		
		foreach($optional as $key => $value)
			$request->addParameter($key, $value);
		
		return $request->get();
	}
	
}