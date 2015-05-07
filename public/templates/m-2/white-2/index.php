<?php
/**
 *
 * id: white-2
 * base: m-2 
 * title: White
 * 
 */

?>

<?php $overlay = $template_options['overlay']; ?>
<?php  if(!$overlay['hide'] || is_admin()): ?>
<div class="cc-pu-bg m-2 white-2"></div>
<?php endif;?>

<article class="pop-up-cc m-2 white-2">
	<div class="modal-inner">
		<?php $views_control = get_post_meta($id,'_chch_pusf_show_once_per',true); ?>
		<a class="cc-pu-close cc-pusf-close" data-modalId="<?php echo $id; ?>" data-views-control="yes" data-expires-control="<?php echo $views_control ?>">  <i class="fa fa-times"></i> </a>
		
		<?php $content = $template_options['contents']; ?>
		<div class="cc-pu-header-section"> 
			<h2><?php echo $content['header'];?></h2>
		</div>
		
		<div class="cc-pu-subheader-section"> 
			<h3><?php echo $content['subheader'];?></h3>
		</div>
		
		<div class="cc-pu-content-section cc-pu-content-styles"> 
			<?php echo $content['content'];?>
		</div>
		
		<?php if(get_post_meta($id,'_chch_pusf_newsletter',true) != 'no'):?>
			
			<?php if(!is_admin()):?>
			<form action="#" class="cc-pu-newsletter-form cc-pusf-newsletter-form">
			<?php else:?>
			<div class="cc-pu-newsletter-form">
			<?php endif;?>
			
				<div class="cc-pu-form-group cc-pu-smart-form"> 
					<div class="cc-pu-thank-you"><p><?php echo $content['thank_you'];?></p></div>
					<div class="cc-pu-error-message"><p><strong>WRONG EMAIL FORMAT!</strong></p></div>
					<i class="fa fa-envelope"></i>
					<?php $input = $template_options['input']; ?>
					<input type="email" class="cc-pu-form-control" placeholder="<?php echo $input['text'];?>">
					<input type="hidden" name="_ajax_nonce" id="_ajax_nonce" value="<?php echo wp_create_nonce("chch-pusf-newsletter-subscribe"); ?>" data-popup="<?php echo $id; ?>">
					
					<?php $button = $template_options['button']; ?>
					<?php $auto_close = get_post_meta($id,'_chch_pusf_auto_closed',true) ? 'yes' : 'no'; ?>
					<?php $auto_close_time = get_post_meta($id,'_chch_pusf_close_timer',true) ? get_post_meta($id,'_chch_pusf_close_timer',true) : '0'; ?>
					<button type="submit" class="cc-pu-btn" data-auto-close="<?php echo $auto_close; ?>" data-auto-close-time="<?php echo $auto_close_time; ?>"><?php echo $button['text'];?></button>
				</div>
				
			<?php if(!is_admin()):?>
			</form>
			<?php else:?>
			</div>
			<?php endif;?>	
		
		<?php endif;?>
		<footer class="cc-pu-privacy-info"> 
			<?php if(!empty($content['privacy_link'])):?>
			<a href="<?php echo $content['privacy_link'];?>">Privacy policy</a>
			<?php endif;?>
			<div class="cc-pu-privacy-section cc-pu-content-styles"> 
				<p><?php echo $content['privacy_message'];?></p>
			</div>
		</footer>
	</div>
</article>
