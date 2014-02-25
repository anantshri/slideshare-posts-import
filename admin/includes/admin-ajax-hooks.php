<?php
/**
 * AJAX hooks declaration
 *
 * @package   Slideshare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */

/**
 * Handler of 'import_slideshows' AJAX action
 *
 * @since    1.0.0
 */
function action_ajax_import() 
{
	$user = get_option('SLIDESHARE_NAME');

	if(!$user)
		$result = new WP_Error(__('Bad user'), __('You should set a Slideshare user in the settings'));
	else
		$result = get_user_slideshares($user, array('detailed' => 1, 'limit' => 5));

	if(is_wp_error($result))
		send_ajax_response(false, $result);
	else {
		$importer = new SlideshareImporter($result->getSlideshows());
		$importer->import();
	
		if($importer->hasErrors()) {
			send_ajax_response(false, $importer->getErrors());
		} else {
			send_ajax_response(true, array(
				'slideshows_count' => $result->getCount(),
				'slideshare_user' => $result->getName(),
				'posts_count' => count($importer->getPosts()),
			));
		}
	}
}

/**
 * Display a JSON encoded structure on standard input for AJAX responses.
 *
 * @param boolean $success The status of the response.
 * @param object $data The data of the response.
 *
 * @since    1.0.0
 */
function send_ajax_response($success, $data)
{
	header('Content-Type: application/json');
	$encoder = new JSONEncoder();
	echo $encoder->json_encode(array('success' => $success, 'data' => $data));
	exit;
}
