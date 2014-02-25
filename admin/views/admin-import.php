<?php
/**
 * Represents the view for the administration Slideshare import page.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Slideshare_Posts_Import
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */
?>

<div id="slideshare-import-container" class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	
	<?php
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	if(isset($_REQUEST['do-import-action'])) {
		if(wp_verify_nonce($_REQUEST['_wpnonce'],'_wp_slideshare_import_nonce')) {
			
		}
	}
	?>
	
	<p><strong><?php _e('Important note:')?></strong> <?php _e('Dependent on the number of slideshows being imported this import may require more memory than what your server allows PHP for a single process.<br />This import script may need to increase PHP\'s memory limit.')?></p>
	<p><strong><?php _e('USE THIS AT YOUR OWN RISK. IT IS IMPORTANT TO KNOW THE LIMITATIONS OF YOUR SERVER')?></strong>.</p>
	
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="memory"><?php _e('Maximum Memory Limit:')?></label></th>
			<td>
				<select id="memory" name="memory">
					<option value="32">32M</option>
					<option value="37">37M</option>
					<option value="42">42M</option>
					<option value="47">47M</option>
					<option value="52">52M</option>
					<option value="57">57M</option>
					<option value="62">62M</option>
					<option value="67">67M</option>
				</select>
			</td>
		</tr>
	</table>
	<p><?php _e('You will be notified when your import has finished.<br /><strong>DO NOT LEAVE THIS PAGE UNTIL YOU HAVE RECEIVED NOTIFICATION</strong>.')?></p>
	
	<p class="submit">
		<input type="submit" id="slideshare-import" name="submit" class="button-primary" value="<?php _e('Import shares')?>" />
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce('_wp_slideshare_import_nonce');?>" />
	</p>
	
	<?php include('notice-container.php');?>
	
	<?php include('activity-indicator.php');?>
	
	<div id="slideshare-skiped-list" class="warning">
		<p><span><?php _e('Skiped posts')?></span></p>
		<ul></ul>
	</div>
</div>
