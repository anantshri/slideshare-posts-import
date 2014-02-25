<?php
/**
 * @package   Slideshare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */

/**
 * Send an API request to recover slideshows of a Slideshare user.
 *
 * @param string $slideshare_user The Slideshare user for who retrieve slideshows
 * @param string $username The Slideshare username account for service authentication
 * @param string $password The Slideshare password account for service authentication
 * @param string $optional The service optional parameters
 * @return User|WP_Error The Slideshare user object with slideshows.
 *
 * @since    1.0.0
 *
 * @see http://fr.slideshare.net/developers/documentation#get_slideshows_by_user 
 */
function get_user_slideshares($user, $optional = array(), $username = null, $password = null) {
	$apiKey = get_option('SLIDESHARE_API_KEY');
	$apiSecret = get_option('SLIDESHARE_API_SECRET_KEY');
	
	if(!$username) {
		$username = get_option('SLIDESHARE_USERNAME', null);
	}
	if(!$password) {
		$password = get_option('SLIDESHARE_PASSWORD', null);
	}
	
	if(!$apiKey) {
		return new WP_Error(__('Bad API key'), __('You should set an API key in the settings'));
	} elseif(!$apiSecret) {
		return new WP_Error(__('Bad API shared secret'), __('You should set an API shared secret key in the settings'));
	} else {
		try {
			$service = new SlideshareUserService($apiKey, $apiSecret, $username, $password);
			$response = $service->getSlideshows($user, $username, $password, $optional);
			return $response->parse();
		} catch(SlideshareServiceException $exception) {
			return new WP_Error($exception->getCode(), $exception->getMessage());
		} catch(SlideshareException $exception) {
			return new WP_Error($exception->getLabel(), $exception->getMessage());
		} catch(Exception $exception) {
			return new WP_Error(__('Error'), $exception->getMessage());
		}
	}
}