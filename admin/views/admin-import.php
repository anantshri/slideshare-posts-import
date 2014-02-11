<?php
/**
 * Represents the view for the administration SlideShare import page.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   SlideShare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */
?>

<div id="slideshare-import-container" class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<p>Below you can delete, publish or draft your video posts.</p>
	
	<p>Let's talk about this video list a little. This list displays all the videos that have been found in your YouTube channels and playlists. For each of these videos a WordPress post has been created. From this list you can manipulate the WordPress post by publishing, drafting or deleting it. When deleting a post you are simply moving it to the trash. Any of these actions taken does not affect the list of videos below PROVIDED THAT YOU NEVER PERMANENTLY DELETE THE POST CREATED. You cannot delete a video from the list below unless you reset the list below or manually delete a post from the trash. Publishing, deleting or drafting only affects the post associated with the YouTube video. Remember this list is simply a reference to the videos that this plugin has found in your YouTube channels and playlists. However, upon the permanent deletion of a post the import may republish the deleted video post automatically. If you wish to hide a video post from your blog, set the post to draft or move it to the trash.</p>
	
	<?php
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	if(isset($_REQUEST['do-import-action'])) {
		?>
		
		<script type="text/javascript" charset="utf-8">
			jQuery('#slideshare-list').show();
		</script>
		
		<?php
		if(wp_verify_nonce($_REQUEST['_wpnonce'],'_wp_slideshare_import_nonce')) {
			
		}
	}
	?>
	
	<?php include('notice-container.php');?>
	
	<p><?php _e('You will be notified when your import has finished. DO NOT LEAVE THIS PAGE UNTIL YOU HAVE RECEIVED NOTIFICATION.')?></p>
	
	<?php include('activity-indicator.php');?>
	
	<p class="submit">
		<input type="submit" id="slideshare-import" name="submit" class="button-primary" value="<?php _e('Import shares')?>" />
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce('_wp_slideshare_import_nonce');?>" />
	</p>
	
	<div id="slideshare-list">
		<?php include('slideshow-list.php');?>
	</div>
</div>