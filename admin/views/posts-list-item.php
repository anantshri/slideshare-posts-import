<?php
/**
 * View of an item of the imported posts on admin posts list page.
 *
 * @package   SlideShare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import/admin/views/posts-list-item.php
 * @copyright 2014 Spoon
 */
?>
<?php
	$post = get_post($post_id);
	$slideshare_id = get_post_meta($post_id, 'slideshare_id', true);
?>
<tr id='field-<?php echo $slideshare_id;?>'>
	<th scope='row' class='check-column'>
		<input type='checkbox' name='posts[]' id='field_<?php echo $slideshare_id;?>' value='<?php echo $post->ID;?>' />
	</th>
	<td class="image column-image"><?php echo get_the_post_thumbnail($post->ID, 'thumbnail');?></td>
	<td class="post-id column-post-id">
		<strong><?php edit_post_link($post->ID, null, null, $post->ID)?></strong>
		<div class="row-actions">
		<?php 
			$isFirstLink = true;
			$nonce = wp_create_nonce('_wp_slideshare_posts_list_nonce');
		?>
		<?php if($post->ID and $post->post_status != 'trash'):?>
			<?php 
				$isFirstLink = false;
				$href = "admin.php?page=slideshare-posts-import-posts&posts%5B%5D=$post->ID&action=delete&_wpnonce=$nonce";
			?>
			<span class="edit"><a href="<?php echo $href?>"><?php _e('Delete')?></a></span>
		<?php endif;?>
		<?php if($post->ID and $post->post_status != 'publish'):?>
			<?php echo $isFirstLink?'':'|'?>
			<?php 
				$isFirstLink = false;
				$href = "admin.php?page=slideshare-posts-import-posts&posts%5B%5D=$post->ID&action=publish&_wpnonce=$nonce";
			?>
			<span class="edit"><a href="<?php echo $href?>"><?php _e('Publish')?></a></span>
		<?php endif;?>
		<?php if($post->ID and $post->post_status != 'draft'):?>
			<?php echo $isFirstLink?'':'|'?>
			<?php $href = "admin.php?page=slideshare-posts-import-posts&posts%5B%5D=$post->ID&action=draft&_wpnonce=$nonce"?>
			<span class="edit"><a href="<?php echo $href?>"><?php _e('Draft')?></a></span>
			</span>
		<?php endif;?>
		</div>
	</td>
	<td class="title column-title"><?php echo $post->post_title?></td>
	<td class="url column-url">
		<?php $web_permalink = get_post_meta($post_id, 'slideshare_web_permalink', true);?>
		<a href="<?php echo $web_permalink?>"><?php echo $web_permalink?></a>
	</td>
	<td class="id column-id"><?php echo $slideshare_id;?></td>
</tr>