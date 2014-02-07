<?php
/**
 * @package   SlideShare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */

function get_user_slideshares($user, $username = null, $password = null) {
	$apiKey = get_option('SLIDESHARE_API_KEY');
	$apiSecret = get_option('SLIDESHARE_API_SECRET_KEY');
	
	if($apiKey && $apiSecret) {
		$service = new SlideShareAPIUser($apiKey, $apiSecret);
		return $service->getSlideShows($user, $username, $password);
	} else {
		return null;
	}
}