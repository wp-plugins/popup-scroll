<?php
/**
 * Pop-Up CC - Scroll
 *
 * @package   ChChPopUpScroll
 * @author    Chop-Chop.org <shop@chop-chop.org>
 * @license   GPL-2.0+
 * @link      https://shop.chop-chop.org
 * @copyright 2014 
 */

/**
 * @package ChChPopUpScroll
 * @author  Chop-Chop.org <shop@chop-chop.org>
 */
class ChChPUSFTemplate { 

	private $template, $template_base, $post_id = 0;

	function __construct($template, $template_base, $post_id = 0) {
		$this->plugin = ChChPopUpScroll::get_instance(); 
		$this->plugin_slug = $this->plugin->get_plugin_slug();
		
		$this->template = $template;
		$this->template_base = $template_base;
		$this->post_id = $post_id; 
 
	} 
	
	function get_template_options(){
		if(!$options = get_post_meta($this->post_id, '_'.$this->template.'_template_data',true)){
			if(file_exists(CHCH_PUSF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/'.$this->template.'/defaults.php'))
			{
				$options = (include(CHCH_PUSF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/'.$this->template.'/defaults.php'));
			}
		}
		 
		return $options;
	} 
	
	
	function get_template(){ 
		$template_options = $this->get_template_options(); 
		$id = $this->post_id;
		include(CHCH_PUSF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/'.$this->template.'/index.php' );  
	}
	
	function build_css(){ 
		$options = $this->get_template_options();
		$template = $this->template_base;
		
		$prefix = '#modal-'.$this->post_id.' ';
		
		$css = '<style>';
		
		$size_options = $options['size'];
		if($size_options['custom'])
		{
			$css .= $prefix.'.'.$template.' .modal-inner  {
				width: '.$size_options['width'].'px;
				height: '.$size_options['height'].'px;
			}';
		}
		 
		$background_options = $options['background'];
		$css .= $prefix.'.'.$template.' .modal-inner {  
			
			background-color: '.$background_options['color'].';';
			
			switch($background_options['type']){ 
				case 'image':
					$css .= 'background-image: url('.$background_options['image'].');';
					$css .= 'background-size: cover;';
				break;
				
				case 'pattern':
					$css .= 'background-image: url('.$background_options['pattern'].');';
					$css .= 'background-repeat:'.$background_options['repeat'].';';
				break; 
			} 	
		$css .= '}';
		  
		$css .= '</style>';
	
		echo $css;  
	}
	
	function build_js()
	{
		$id = $this->post_id; 
		
		$mobile_header = 'if($(window).width() > 1024){';
		$mobile_footer = '}';
		
		if(get_post_meta($id, '_chch_pusf_show_on_mobile',true))
		{
			$mobile_header = '';
			$mobile_footer = '';	
		}
		
		if(get_post_meta($id, '_chch_pusf_show_only_on_mobile',true))
		{
			$mobile_header = 'if($(window).width() < 1025){'; 
			$mobile_footer = '}';
		}
		
		$scroll_type = get_post_meta($id, '_chch_pusf_scroll_type',true); 
		$script = '<script type="text/javascript">';
		$script .= 'jQuery(function($) {';
		
		$script .= 'if(!Cookies.get("shown_modal_'.$id.'")){ ';

		$script .= $mobile_header;
		
		$script .= '$(window).on("scroll", function() { var chch_scroll_pos = window.pageYOffset;';
		
		switch($scroll_type): 
			case 'px':
				$scroll_px = get_post_meta($id, '_chch_pusf_scroll_px',true);
				
				$scroll_head = '  	
   		 			scroll_pos_test = '.$scroll_px .'; 
 						if(chch_scroll_pos > scroll_pos_test) { ';
    		$scroll_footer = '}';
			break;
			
			case 'percent':
				$scroll_percent = get_post_meta($id, '_chch_pusf_scroll_percent',true);
				
				$scroll_head = '
					winTop = $(window).scrollTop(), docHeight = $(document).height(), winHeight = $(window).height();
				
    			scrollTrigger = '.($scroll_percent / 100).'; 
				
				if  ((winTop/(docHeight-winHeight)) > scrollTrigger) { ';
				$scroll_footer = '}';
			break;
			
			case 'item':
				$scroll_item = get_post_meta($id, '_chch_pusf_scroll_item',true);
				
				$scroll_head = ' 
					scrollEl = $("'.$scroll_item.'"); 
				
				if(scrollEl.length) { 
					scrollElOffset = scrollEl.offset().top; 
					if (chch_scroll_pos > scrollElOffset) { ';
				$scroll_footer = '}}';
			break;
		endswitch;
		
		
		$script .= $scroll_head;
		$script .= '$("#modal-'.$id.'").not(".chch_shown").show("fast"); 
			$("#modal-'.$id.'").addClass("chch_shown");
		  if($(window).width() < 768){	
  			windowPos = $(window).scrollTop();
  			windowHeight = $(window).height();
  			popupHeight = $( "#modal-'.$id.' .modal-inner" ).outerHeight();
  			popupPosition = windowPos + ((windowHeight - popupHeight)/2);
  			$( "#modal-'.$id.' .pop-up-cc").css("top",Math.abs(popupPosition)); 
		}';
		$script .= $scroll_footer;
		$script .= '});';
		$script .= $mobile_footer;
		
		$script .= '}';
		
		$script .= '});';
		$script .= '</script>'; 
		
		echo $script;		
	}
	
	function enqueue_template_style(){ 	
	
		$options = $this->get_template_options();
	
		if(file_exists(CHCH_PUSF_PLUGIN_DIR . 'public/templates/css/defaults.css')) {
			wp_enqueue_style($this->plugin_slug .'_template_defaults', CHCH_PUSF_PLUGIN_URL . 'public/templates/css/defaults.css', null, ChChPopUpScroll::VERSION, 'all');  
		}
			
		if(file_exists(CHCH_PUSF_PLUGIN_DIR . 'public/templates/css/fonts.css')) {
			wp_enqueue_style($this->plugin_slug .'_template_fonts', CHCH_PUSF_PLUGIN_URL . 'public/templates/css/fonts.css', null, ChChPopUpScroll::VERSION, 'all');  
		}
		
		if(file_exists(CHCH_PUSF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/css/base.css')){
			wp_enqueue_style('base_'.$this->template_base, CHCH_PUSF_PLUGIN_URL . 'public/templates/'.$this->template_base.'/css/base.css', null, ChChPopUpScroll::VERSION, 'all');  
			  
		} 
		
		 
		if(file_exists(CHCH_PUSF_PLUGIN_DIR . 'public/assets/js/jquery-cookie/jquery.cookie.js')){	
			wp_enqueue_script( $this->plugin_slug .'jquery-cookie', CHCH_PUSF_PLUGIN_URL . 'public/assets/js/jquery-cookie/jquery.cookie.js', array('jquery') );
			
		}
		
		if(file_exists(CHCH_PUSF_PLUGIN_DIR . 'public/assets/js/public.js')){	
			wp_enqueue_script( $this->plugin_slug .'public-script', CHCH_PUSF_PLUGIN_URL . 'public/assets/js/public.js', array('jquery') ); 
			wp_localize_script( $this->plugin_slug .'public-script', 'chch_pusf_ajax_object', array( 'ajaxUrl' => admin_url( 'admin-ajax.php' )) );
		} 
		
		if(file_exists(CHCH_PUSF_PLUGIN_DIR . 'public/templates/'.$this->template_base.'/'.$this->template.'/css/style.css')){
			wp_enqueue_style('style_'.$this->template, CHCH_PUSF_PLUGIN_URL . 'public/templates/'.$this->template_base.'/'.$this->template.'/css/style.css', null, ChChPopUpScroll::VERSION, 'all');  
			  
		}   
		 
	}
}