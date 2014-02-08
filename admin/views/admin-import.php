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
	
	<?php
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
	
	<?php include('error-container.php');?>
	
	<p><?php _e('You will be notified when your import has finished. DO NOT LEAVE THIS PAGE UNTIL YOU HAVE RECEIVED NOTIFICATION.')?></p>
	
	<?php include('activity-indicator.php');?>
	
	<p class="submit">
		<input type="submit" id="slideshare-import" name="submit" class="button-primary" value="<?php _e('Import shares')?>" />
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce('_wp_slideshare_nonce');?>" />
	</p>
	<h3 id="slideshare_total"></h3>
	<p id="slideshare_list"></p>
</div>