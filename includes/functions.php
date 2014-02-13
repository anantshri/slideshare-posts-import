<?php
/**
 * @package   SlideShare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */

function get_user_slideshares($user, $optional = array(), $username = null, $password = null) {
	$apiKey = get_option('SLIDESHARE_API_KEY');
	$apiSecret = get_option('SLIDESHARE_API_SECRET_KEY');
	
	if(!$apiKey) {
		return new WP_Error(__('Bad API key'), __('You should set an API key in the settings'));
	} elseif(!$apiSecret) {
		return new WP_Error(__('Bad API shared secret'), __('You should set an API shared secret key in the settings'));
	} else {
		try {
			$service = new SlideShareUserService($apiKey, $apiSecret);
			$response = $service->getSlideshows($user, $username, $password, $optional);
			return $response->parse();
		} catch(SlideShareServiceException $exception) {
			return new WP_Error($exception->getCode(), $exception->getMessage());
		} catch(SlideShareException $exception) {
			return new WP_Error($exception->getLabel(), $exception->getMessage());
		} catch(Exception $exception) {
			return new WP_Error(__('Error'), $exception->getMessage());
		}
	}
}