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
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	
	<?php
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	if ( $_SERVER["REQUEST_METHOD"] == "POST" && wp_verify_nonce($_REQUEST['_wpnonce'], '_wp_slideshare_nonce')){
		update_option( 'SLIDESHARE_API_KEY', $_POST['api_key'] );
		update_option( 'SLIDESHARE_API_SECRET_KEY', $_POST['api_secret_key'] );
		update_option( 'SLIDESHARE_AUTOPUBLISH', $_POST['publish'] );
		update_option( 'SLIDESHARE_USERNAME', $_POST['slideshare_username'] );
		update_option( 'SLIDESHARE_PASSWORD', $_POST['slideshare_password'] );
		update_option( 'SLIDESHARE_NAME', $_POST['slideshare_name'] );
		
		$notice = array(
			'label'   => __('Update succeed'),
			'message' => __('SlideShare global settings was successfuly updated !'),
		);
	}
	?>
	
	<?php include('notice-container.php');?>
	
	<?php if(!empty($notice)): ?>
		<script type="text/javascript" charset="utf-8">
			setNoticeMessage("<?php echo $notice['label']?>", "<?php echo $notice['message']?>");
			displayNoticeContainer(true, true, true);
		</script>
	<?php endif; ?>
	
	<form name="options" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<table class="form-table">
			<tr valign="row">
				<th scope="row"><label for="api_key"><?php _e('API Key<span class="required"> (*)</span>:')?> </label></th>
				<td><input type="text" name="api_key" value="<?php echo get_option( 'SLIDESHARE_API_KEY', '' ); ?>" size="70" class="regular-text"></td>
			</tr>
			<tr valign="row">
				<th scope="row"><label for="api_secret_key"><?php _e('Consumer Secret<span class="required"> (*)</span>:')?> </label></th>
				<td><input type="text" name="api_secret_key" value="<?php echo get_option( 'SLIDESHARE_API_SECRET_KEY', '' ); ?>" size="70" class="regular-text"></td>
			</tr>
			<tr valign="row">
				<th scope="row"><label for="slideshare_name"><?php _e('Name:')?> </label></th>
				<td><input type="text" name="slideshare_name" value="<?php echo get_option( 'SLIDESHARE_NAME', '' ); ?>" size="70" class="regular-text"></td>
			</tr>
			<tr valign="row">
				<th scope="row"><label for="slideshare_username"><?php _e('Username:')?> </label></th>
				<td><input type="text" name="slideshare_username" value="<?php echo get_option( 'SLIDESHARE_USERNAME', '' ); ?>" size="70" class="regular-text"></td>
			</tr>
			<tr valign="row">
				<th scope="row"><label for="slideshare_password"><?php _e('Password:')?> </label></th>
				<td><input type="text" name="slideshare_password" value="<?php echo get_option( 'SLIDESHARE_PASSWORD', '' ); ?>" size="70" class="regular-text"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="publish"><?php _e('Automatically publish posts:')?></label></th>
				<td>
					<input type="radio" name="publish" value="1" <?php if(get_option('SLIDESHARE_AUTOPUBLISH')):?> checked<?php endif;?> /> <?php _e('yes')?> 
					<input type="radio" name="publish" value="0" <?php if(!get_option('SLIDESHARE_AUTOPUBLISH')):?> checked<?php endif;?> /> <?php _e('no')?><br />
					<p><span class="setting-description"><?php _e('This option will make posts immediately viewable to the public.')?></span></p>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save')?>">
		</p>
		<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce('_wp_slideshare_nonce');?>" />
		<input type="hidden" name="_wp_http_referer" value="<?php wp_get_referer(); ?>" />
		<br/>
		<small><?php _e('You can sign up for a API key <a href="http://fr.slideshare.net/developers/applyforapi" target="_blank">here</a>')?></small>
	</form>
</div>
