<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   SlideShare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import/admin/views/admin-posts-list.php
 * @copyright 2014 Spoon
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	
	<?php
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	global $wpdb;
	$posts = $wpdb->get_col("select post_id from $wpdb->postmeta where meta_key='slideshare_id'");

	if(wp_verify_nonce($_REQUEST['_wpnonce'], '_wp_slideshare_posts_list_nonce')) {
		$action = empty($_REQUEST['action']) ? $_REQUEST['action2'] : $_REQUEST['action'];
		switch($action) {
			case 'delete' :
				foreach((array)$_REQUEST['posts'] as $post_id) {
					$post = get_post($post_id);
					if($post->post_status != 'trash' and !wp_delete_post($post_id)) {
						error_log("There was an error while deleting a video post $post_id. Please try again.");
					}
				}
				break;
			case 'publish' :
				foreach((array)$_REQUEST['posts'] as $post_id) {
					if(!$wpdb->query("update $wpdb->posts set post_status='publish' where ID=$post_id")) {
						error_log("There was an error while publishing your video post $post_id. Please try again.");
					}
				}
				break;
			case 'draft' :
				foreach((array)$_REQUEST['posts'] as $post_id) {
					if(!$wpdb->query("update $wpdb->posts set post_status='draft' where ID=$post_id")) {
						error_log("There was an error while drafting your video post $post_id. Please try again.");
					}
				}
				break;
			case 'refresh' :
				foreach((array)$posts as $post_id) {
					if(!wp_delete_post($post_id, true)) {
						error_log("There was an error while deleting a video post $post_id. Please try again.");
					}
				}
				break;
			default :
				break;
		}
	}
	?>
	<p>Below you can delete, publish or draft your video posts.</p>
	
	<p>Let's talk about this video list a little. This list displays all the videos that have been found in your YouTube channels and playlists. For each of these videos a WordPress post has been created. From this list you can manipulate the WordPress post by publishing, drafting or deleting it. When deleting a post you are simply moving it to the trash. Any of these actions taken does not affect the list of videos below PROVIDED THAT YOU NEVER PERMANENTLY DELETE THE POST CREATED. You cannot delete a video from the list below unless you reset the list below or manually delete a post from the trash. Publishing, deleting or drafting only affects the post associated with the YouTube video. Remember this list is simply a reference to the videos that this plugin has found in your YouTube channels and playlists. However, upon the permanent deletion of a post the import may republish the deleted video post automatically. If you wish to hide a video post from your blog, set the post to draft or move it to the trash.</p>

	<form id="wp_slideshow_list_fm" method="post" action="">
		<div class="tablenav">
			<?php 
				$paged = $_GET['paged'] ? $_GET['paged'] : 1;
				$num = 10;
				$paging_text = paginate_links(array(
					'total'		=>	ceil(count($posts)/$num),
					'current'	=>	$paged,
					'base'		=>	'admin.php?page=slideshare-posts-import-posts&%_%',
					'format'	=>	'paged=%#%'
				));
				if($paging_text) {
					$paging_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
						number_format_i18n(($paged-1)*$num+1),
						number_format_i18n(min($paged*$num,count($posts))),
						number_format_i18n(count($posts)),
						$paging_text
					);
				}
			?>
			<div class="tablenav-pages"><?php echo $paging_text; ?></div>
			<div class="alignleft actions">
				<select name="action">
					<option value="" selected="selected"><?php _e('Bulk Actions')?></option>
					<option value="delete"><?php _e('Delete')?></option>
					<option value="publish"><?php _e('Publish')?></option>
					<option value="draft"><?php _e('Draft')?></option>
				</select>
		
				<input type="submit" value="<?php _e('Apply')?>" name="do-post-action" class="button-secondary action" />
			</div>
			<br class="clear" />
		</div>
		<table class="widefat fixed" cellspacing="0">
			<thead>
			<tr class="thead">
				<th scope="col" id="cb" class="manage-column column-cb check-column"><input type="checkbox" /></th>
				<th scope="col" id="image" class="manage-column column-img" style="width:150px;"><?php _e('Preview')?></th>
				<th scope="col" id="post-id" class="manage-column column-post-id"><?php _e('Post ID')?></th>
				<th scope="col" id="title" class="manage-column column-title" style="width:20%;"><?php _e('Title')?></th>
				<th scope="col" id="url" class="manage-column column-url"><?php _e('URL')?></th>
				<th scope="col" id="video-id" class="manage-column column-id" style="width:20%;"><?php _e('SlideShare ID')?></th>
			</tr>
			</thead>
			<tfoot>
			<tr class="thead">
				<th scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></th>
				<th scope="col" class="manage-column column-img"><?php _e('Preview')?></th>
				<th scope="col" class="manage-column column-post-id"><?php _e('Post ID')?></th>
				<th scope="col" class="manage-column column-title"><?php _e('Title')?></th>
				<th scope="col" class="manage-column column-url"><?php _e('URL')?></th>
				<th scope="col" class="manage-column column-id"><?php _e('SlideShare ID')?></th>
			</tr>
			</tfoot>
			<tbody id="fields" class="list:fields field-list">
				<?php $posts = array_slice($posts, ($paged-1)*$num, $num);?>
				<?php foreach($posts as $post_id):?>
					<?php include(plugin_dir_path( __FILE__ ) . 'posts-list-item.php')?>
				<?php endforeach;?>
			</tbody>
		</table>
		<div class="tablenav">
			<div class="alignleft actions">
				<select name="action2">
					<option value="" selected="selected"><?php _e('Bulk Actions')?></option>
					<option value="delete"><?php _e('Delete')?></option>
					<option value="publish"><?php _e('Publish')?></option>
					<option value="draft"><?php _e('Draft')?></option>
				</select>
		
				<input type="submit" value="<?php _e('Apply')?>" name="do-post-action" class="button-secondary action" />
			</div>
			<br class="clear" />
		</div>
		<input type="hidden" id="page" name="page" value="slideshare-posts-import-import" />
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce('_wp_slideshare_posts_list_nonce');?>" />
	</form>
	
</div>