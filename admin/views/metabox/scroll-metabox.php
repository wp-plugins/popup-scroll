<table class="form-table cmb_metabox">
	<tbody> 
		<?php $adapter = get_post_meta($post->ID,'_chch_pusf_scroll_type',true) ? get_post_meta($post->ID,'_chch_pusf_scroll_type',true) : 'px';?> 
		<tr class="cmb-type-radio">
			<th style="width:18%"><label for="_chch_pusf_scroll_adapter">Pixels:</label></th>
			<td> 
				<ul class="cmb_radio_list cmb_list">	
					<li>
					<input class="cmb_option" name="_chch_pusf_scroll_type" id="_chch_pusf_scroll_type1" value="px" <?php if($adapter == 'px') echo 'checked="checked"'; ?> type="radio"> <label for="_chch_pusf_role1"></label>
					</li> 
				</ul>
				<?php $px_option = get_post_meta($post->ID,'_chch_pusf_scroll_px',true);  ?>
				<label for="_chch_pusf_email">Pixels from top:</label><br />
				<input class="cmb_text_medium" name="_chch_pusf_px" id="_chch_pusf_px" value="<?php echo $px_option; ?>" type="text">
				<br /> <span class="cmb_metabox_description">Show after scrolling down a given number of pixels from top.</span>
				</td>
		</tr>
		
		<tr class="cmb-type-radio">
			<th style="width:18%"><label for="_chch_pusf_scroll_adapter">Percent:</label></th>
			<td> 
				<ul class="cmb_radio_list cmb_list">	
					<li>
					<input class="cmb_option" name="_chch_pusf_scroll_type" id="_chch_pusf_scroll_type2" value="percent" <?php if($adapter == 'percent') echo 'checked="checked"'; ?> type="radio"> <label for="_chch_pusf_scroll_type2"></label>
					</li> 
				</ul>
				<?php $percent_option = get_post_meta($post->ID,'_chch_pusf_scroll_percent',true);  ?>
				<label for="_chch_pusf_email">Percents from top:</label><br />
				<input class="cmb_text_medium" name="_chch_pusf_percent" id="_chch_pusf_percent" value="<?php echo $percent_option; ?>" type="text">
				<br /> <span class="cmb_metabox_description">Show after scrolling down to a certain percentage of the page height.</span>
				</td>
		</tr>
		
		<tr class="cmb-type-radio">
			<th style="width:18%"><label for="_chch_pusf_scroll_adapter">Element:</label></th>
			<td> 
				<ul class="cmb_radio_list cmb_list">	
					<li>
					<input class="cmb_option" name="_chch_pusf_scroll_type" id="_chch_pusf_scroll_type3" value="item" <?php if($adapter == 'item') echo 'checked="checked"'; ?> type="radio"> <label for="_chch_pusf_scroll_type3"></label>
					</li> 
				</ul>
				<?php $item_option = get_post_meta($post->ID,'_chch_pusf_scroll_item',true);  ?>
				<label for="_chch_pusf_email">Element:</label><br />
				<input class="cmb_text_medium" name="_chch_pusf_item" id="_chch_pusf_item" value="<?php echo $item_option; ?>" type="text">
				<br /> <span class="cmb_metabox_description">Show after scrolling down to a certain page element (.class or #id).</span>
			</td>
		</tr>  
	</tbody>
</table>
