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
		$result = get_user_slideshares($user, array('detailed' => 1));

	if(is_wp_error($result)) {
        SlideshareAjaxResponse::error($result);
	} else {
		$data = array(
			'slideshows_count' => $result->getCount(),
			'slideshare_user'  => $result->getName(),
			'new_posts'        => array(),
			'skiped_posts'     => array()
		);
		
		if($result->getCount() > 0) {
			$importer = new SlideshareImporter($result->getSlideshows(), $_GET['memory']);
			$importer->import();
	
			if($importer->hasErrors()) {
				SlideshareAjaxResponse::error($importer->getErrors());
			} else {
				$data['new_posts'] = $importer->getPosts();
				$data['skiped_posts'] = $importer->getSkiped();
				SlideshareAjaxResponse::success($data);
			}
		}  else {
				SlideshareAjaxResponse::success($data);
		}	
	}
}
