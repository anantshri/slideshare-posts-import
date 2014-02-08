<?php
/**
 * User REST API calls.
 *
 * @package   api/services
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
class SlideShareUserService extends SlideShareService
{
	public function getSlideshows($slideshare_user, $username = null, $password = null, $optional = array()) 
	{
		$this->credentials->setUserCredentials($username, $password);
		
		$request = new SlideShareHttpRequest('get_slideshows_by_user', $this->credentials);
		$request->addParameter('username_for', $slideshare_user);
		
		foreach($optional as $key => $value)
			$request->addParameter($key, $value);
		
		return $request->get();
	}
	
}