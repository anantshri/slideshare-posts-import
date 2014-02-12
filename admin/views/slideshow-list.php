<script type="text/javascript" charset="utf-8">
	function fillSlideshowsTable(list) {
		$template = jQuery('#fields').children().first().detach();
		
		for(var i in list) {
			var item = list[i];
		
			var $title = jQuery('<span/>')
				.addClass('field_titles')
				.html(item.title);
			var $thumbnail = jQuery('<img/>')
				.attr('src', item.thumbnail_url)
				.attr('title', $title.html());
			var $permalink = jQuery('<a/>')
				.attr('href', item.web_permalink)
				.attr('target', '_blank')
				.html(item.web_permalink);
			
			$row = $template.clone().attr('id', 'field-'+item.id);
			$row.find('input:checkbox').attr({
				id: 'checkbox-field-'+item.id,
				value: item.id
			});
			$row.find('td.column-image').html($thumbnail);
			$row.find('td.column-id').prepend(jQuery('<strong/>').html(item.id));
			$row.find('td.column-title').html($title);
			$row.find('td.column-url').html($permalink);
			
			jQuery('#fields').append($row);
		}
		jQuery('#wp_slideshow_list_fm tr:odd').addClass('alternate');
	}
</script>

<form id="wp_slideshow_list_fm" method="post" action="">
	<div class="tablenav">
		<?php /*
			$paged = $_GET['paged'] ? $_GET['paged'] : 1;
			$num = 10;
			$paging_text = paginate_links(array(
				'total'		=>	ceil(count($videos)/$num),
				'current'	=>	$paged,
				'base'		=>	'admin.php?page=ayvpp-video-posts&%_%',
				'format'	=>	'paged=%#%'
			));
			if($paging_text) {
				$paging_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
					number_format_i18n(($paged-1)*$num+1),
					number_format_i18n(min($paged*$num,count($videos))),
					number_format_i18n(count($videos)),
					$paging_text
				);
			}
		*/?>
		<div class="tablenav-pages"><?php //echo $paging_text; ?></div>
		<div class="alignleft actions">
		<?php /*
			
			<select name="action">
				<option value="" selected="selected"><?php _e('Bulk Actions')?></option>
				<option value="delete"><?php _e('Delete')?></option>
				<!-- <option value="publish"><?php //_e('Publish')?></option> -->
				<option value="draft"><?php _e('Draft')?></option>
				<option value="import"><?php _e('Import')?></option>
			</select>
			
			<input type="submit" value="<?php _e('Apply')?>" name="doaction" class="button-secondary action" />
		*/ ?>	
			<input type="submit" value="<?php _e('Import')?>" name="do-import-action" class="button-primary action" />
		</div>
		<br class="clear" />
	</div>
	<table class="widefat fixed" cellspacing="0">
		<thead>
		<tr class="thead">
			<th scope="col" id="cb" class="manage-column column-cb check-column"><input type="checkbox" /></th>
			<th scope="col" id="image" class="manage-column column-img" style="width:150px;"><?php _e('Preview')?></th>
			<th scope="col" id="video-id" class="manage-column column-id" style="width:20%;"><?php _e('SlideShare ID')?></th>
			<th scope="col" id="title" class="manage-column column-title" style="width:20%;"><?php _e('Title')?></th>
			<th scope="col" id="url" class="manage-column column-url"><?php _e('URL')?></th>
		</tr>
		</thead>
		<tfoot>
		<tr class="thead">
			<th scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></th>
			<th scope="col" class="manage-column column-img"><?php _e('Preview')?></th>
			<th scope="col" class="manage-column column-id"><?php _e('SlideShare ID')?></th>
			<th scope="col" class="manage-column column-title"><?php _e('Title')?></th>
			<th scope="col" class="manage-column column-url"><?php _e('URL')?></th>
		</tr>
		</tfoot>
		<tbody id="fields" class="list:fields field-list">
			<?php include(plugin_dir_path( __FILE__ ) . 'slideshow-list-item.php')?>
		</tbody>
	</table>
	<div class="tablenav">
		<div class="alignleft actions">
		<?php /*
			
			<select name="action2">
				<option value="" selected="selected"><?php _e('Bulk Actions')?></option>
				<option value="delete"><?php _e('Delete')?></option>
				<!-- <option value="publish"><?php //_e('Publish')?></option> -->
				<option value="draft"><?php _e('Draft')?></option>
				<option value="import"><?php _e('Import')?></option>
			</select>
			
			<input type="submit" value="<?php _e('Apply')?>" name="doaction" class="button-secondary action" />
		*/ ?>	
			<input type="submit" value="<?php _e('Import')?>" name="do-import-action" class="button-primary action" />
		</div>
		<br class="clear" />
	</div>
	<input type="hidden" id="page" name="page" value="slideshare-posts-import-import" />
	<input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo wp_create_nonce('_wp_slideshare_import_nonce');?>" />
</form>
