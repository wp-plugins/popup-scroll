<?php
/**
 * Pop-Up CC
 *
 * @package   ChChPopUpScrollPostType
 * @author    Chop-Chop.org <shop@chop-chop.org>
 * @license   GPL-2.0+
 * @link      https://shop.chop-chop.org
 * @copyright 2014 
 */

 if ( ! class_exists( 'ChChPUSFTemplate' ) )
    require_once( CC_PU_PLUGIN_DIR . 'public/includes/chch-pusf-template.php' );
	
/**
 * @package ChChPopUpScrollPostType
 * @author  Chop-Chop.org <shop@chop-chop.org>
 */
class ChChPopUpScrollFreePeview { 
	
	private $template_id, $template_base, $template_name, $template_options , $options_prefix;
	
	public $fields  = array();
	
	function __construct($template, $template_base, $template_name) {
		$this->plugin = ChChPopUpScroll::get_instance(); 
		$this->plugin_slug = $this->plugin->get_plugin_slug(); 
		
		$this->template_id = $template; 
		
		$this->template_name = $template_name;
		
		$this->template_base = $template_base;
		
		$this->options_prefix ='_'.$this->template_id.'_';
		
		$this->template = new ChChPUSFTemplate($this->template_id,$this->template_base, get_the_ID());
		
		$this->template_options = $this->template->get_template_options();
		
	} 
	
	/**
	 * Build preview view
	 *
	 * @since    0.1.0
	 */
	public function build_preview() {
		$template = $this->template_id; 
		$base = $this->template_base;
		
		
		echo '<div class="chch-pusf-customize-form" id="chch-pusf-customize-form-'.$template.'">';
		 
		echo '<div class="chch-pusf-customize-controls">';
		
		//preview options header
		echo '
			<div class="chch-pusf-customize-header-actions">
				<input name="publish" id="publish-customize" class="button button-primary button-large" value="Save &amp; Close" accesskey="p" type="submit" />  
				<a class="chch-pusf-customize-close" href="#" data-template="'.$template.'">
					<span class="screen-reader-text">Close</span>
				</a> 
		</div>';
		
		//preview options overlay - start
		echo '<div class="chch-pusf-options-overlay">';
		
		//preview customize info
		echo '<div class="chch-pusf-customize-info">
				<span class="preview-notice">
					You are customizing <strong class="template-title">'.$this->template_name.' Template</strong>
				</span>
			</div><!--#customize-info-->';
	
		//preview options accordion wrapper - start
		echo '<div class="customize-theme-controls"  class="accordion-section">';
		
		// build options sections
		
		echo $this->build_options();
		
		echo '
				</div><!--.accordion-section-->
			</div><!--.chch-pusf-options-overlay-->
		</div><!--#chch-pusf-customize-controls-->';
	
		echo '<div id="cc-pu-customize-preview-'.$template.'" class="cc-pu-customize-preview" style="position:relative;">';
			 
		echo '</div>';
		echo '</div>'; 
		 
	}
	
	
	private function build_options() {
		
		$fields['general'] = array( 
			'name'	=> 'General',
			'field_groups' => array(
				array(
					'option_group' => 'size',
					'title'		   => 'Size',
					'fields' => array(
						array(
							'type'	 => 'revealer',  
							'name'   => 'custom', 
							'desc'   => 'Enable custom pop-up size (If you set a custom pop-up size, the pop-up won’t be responsive.)',
							'revaeals' => array(
								'title' => 'Custom Pop-Up Size',
								'fields' => array(
									array(
										'type'	 => 'text',
										'name'   => 'width',
										'action' => 'css', 
										'target' => '.pop-up-cc',
										'attr'   => 'width',
										'unit'   =>	'px',
										'desc'   => 'Width:',
									),
									array(
										'type'	 => 'text',
										'name'   => 'height',
										'action' => 'css', 
										'target' => '.pop-up-cc',
										'attr'   => 'height',
										'unit'   =>	'px',
										'desc'   => 'Height:',
									),
								),
							),
						), 
					),
				),
				array(
					'option_group'=> 'overlay',
					'title'=> 'Overlay', 
					'fields' => array(
						array(
							'type'	 => 'checkbox',
							'name'   => 'hide',  
							'class'  => 'remover-checkbox', 
							'target' => '.cc-pu-bg',
							'attr'   => 'visible', 
							'desc'   => 'Hide overlay:',
						),
					),
				),
				array(
					'option_group'=> 'none',
					'title'=> 'Overlay',
					'disable' => true,
					'fields' => array(
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none', 
							'desc'   => 'Color:',
						),
						array(
							'type'	 => 'slider',
							'name'   => 'overlay_opacity',  
							'target' => 'none', 
							'desc'   => 'Opacity:',
						),
					),
				),
			), 	
		);
		
		$fields['content'] = array( 
			'name'	=> 'Content',
			'field_groups' => array(
				array(
					'option_group' => 'contents',
					'title'		   => 'Content',
					'fields' => array(
						array(
							'type'	 => 'editor', 
							'name'   => 'header',  
							'target' => '.chch-pusf-header-section', 
							'desc'   => 'Header:',
						), 
						array(
							'type'	 => 'editor', 
							'name'   => 'subheader',  
							'target' => '.chch-pusf-subheader-section', 
							'desc'   => 'Subheader:',
						),
						array(
							'type'	 => 'editor', 
							'name'   => 'content',  
							'target' => '.chch-pusf-content-section', 
							'desc'   => 'Content:',
						),
						array(
							'type'	 => 'editor', 
							'name'   => 'privacy_message',  
							'target' => '.chch-pusf-privacy-section', 
							'desc'   => 'Privacy Message:',
						),
						array(
							'type'	 => 'text', 
							'action' => 'text',
							'name'   => 'privacy_link',  
							'target' => '.chch-pusf-privacy-info a', 
							'attr' => 'href',
							'desc'   => 'Privacy Policy Link (if there is no URL provided, the link will not be displayed):',
						),  
						array(
							'type'	 => 'text', 
							'action' => 'text',
							'name'   => 'thank_you',  
							'target' => '.cc-pu-thank-you p', 
							'desc'   => 'Success Message:',
						),
					),
				), 
				array(
					'option_group' => 'input',
					'title'		   => 'Input',
					'fields' => array( 
						array(
							'type'	 => 'text', 
							'action' => 'text',
							'name'   => 'text',  
							'target' => 'input', 
							'attr'   => 'placeholder', 
							'desc'   => 'Text:',
						),
					),
				),
				array(
					'option_group' => 'button',
					'title'		   => 'Submit Button', 
					'fields' => array(
						array(
							'type'	 => 'text',
							'action' => 'text', 
							'name'   => 'text',  
							'target' => '.cc-pu-newsletter-form button',  
							'desc'   => 'Button Text:',
						),  
					),
				),
			), 	
		);
		
		$fields['background'] = array(
			'name'	=> 'Background',
			'field_groups' => array(
				array(
					'option_group' => 'background',
					'title'		   => 'Background',
					'fields' => array( 
						array(
							'type'	 => 'color_picker',
							'name'   => 'color', 
							'target' => '.modal-inner',
							'attr'   => 'background-color', 
							'desc'   => 'Color:',
						),
						array(
							'type'	 => 'revealer_group', 
							'name'   => 'type',  
							'desc'   => 'Background Type:', 
							'target' => '.modal-inner',
							'attr'   => 'background-image',  
							
							'options' => array(
								'no'  => 'No Image',
								'image' => 'Image',
								'pattern' => 'Pattern',
							),
							'revaeals' => array(  
								array(
									'section_title' => 'Background Image',
									'section_id' => 'image',
									'fields' => array(
										array(
											'type'	 => 'upload',
											'name'   => 'image', 
											'target' => '.modal-inner',
											'attr'   => 'background-image', 
											'desc'   => 'Enter a URL or upload an image:',
										), 
									),
								),
								array(
									'section_title' => 'Background Pattern',
									'section_id' => 'pattern',
									'fields' => array(
										array(
											'type'	 => 'upload',
											'name'   => 'pattern', 
											'target' => '.modal-inner',
											'attr'   => 'background-image', 
											'desc'   => 'Enter a URL or upload an image:',
										), 
										array(
											'type'	 => 'select',
											'name'   => 'repeat', 
											'target' => '.modal-inner',
											'attr'   => 'background-repeat', 
											'desc'   => 'Pattern Repeat:',
											'options' => array(
												'repeat'  => 'Repeat',
												'repeat-x'  => 'Repeat-x',
												'repeat-y'  => 'Repeat-y',
												'no-repeat' => 'No Repeat', 
											),
										), 
									),
								),
							),
						), 
					),
				), 
			), 	
		);
		
		$fields['borders'] = array( 
			'name'	=> 'Borders',
			'field_groups' => array(
				array(
					'option_group' => 'none',
					'disable' => true,
					'title'		   => 'Border',
					'fields' => array(
						array(
							'type'	 => 'slider', 
							'name'   => 'border_radius',  
							'target' => 'none', 
							'desc'   => 'Border Radius:',
						),
						array(
							'type'	 => 'slider', 
							'name'   => 'border_width', 
							'target' => 'none',  
							'desc'   => 'Width:',
						),
						array(
							'type'	  => 'select',
							'name'    => 'style', 
							'target'  => 'none', 
							'desc'    => 'Border Style:',
							'options' => array(
								'solid'  => 'Solid',
								'dashed' => 'Dashed',
								'dotted' => 'Dotted',
							),
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none', 
							'desc'   => 'Color:',
						),
					),
				), 
			), 	
		);
		 
		$fields['fonts'] = array(
			'name'	=> 'Fonts and Colors',
			'field_groups' => array(
				array(
					'option_group' => 'none',
					'disable' => true,
					'title'		   => 'Header',
					'fields' => array(
						array(
							'type'	 => 'select', 
							'name'   => 'font',  
							'target' => 'none', 
							'desc'   => 'Header Font:',
							'options' => array(
								'Open Sans'  => 'Open Sans', 
							),
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none',  
							'desc'   => 'Color:',
						), 
					),
				),
				array(
					'option_group' => 'none',
					'disable' => true,
					'title'	=> 'Subheader',
					'fields' => array(
						array(
							'type'	 => 'select', 
							'name'   => 'font',  
							'target' => 'none',
							'desc'   => 'Subheader Font:',
							'options' => array(
								'Open Sans'  => 'Open Sans', 
							),
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none', 
							'desc'   => 'Color:',
						), 
					),
				), 
				array(
					'option_group' => 'none',
					'disable' => true,
					'title'		   => 'Content',
					'fields' => array(
						array(
							'type'	 => 'select', 
							'name'   => 'font',  
							'target' => 'none', 
							'desc'   => 'Header Font:',
							'options' => array(
								'Open Sans'  => 'Open Sans', 
							),
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none', 
							'desc'   => 'Color:',
						), 
					),
				),
			), 	
		);
		
		
		
		$fields['inputs'] = array( 
			'name'	=> 'Inputs',
			'field_groups' => array( 
				array(
					'option_group' => 'none',
					'title'		   => 'Input',
					'disable' => true,
					'fields' => array(
						array(
							'type'	 => 'slider', 
							'name'   => 'input_radius',  
							'target' => 'none',
							'desc'   => 'Border Radius:',
						), 
						array(
							'type'	 => 'select', 
							'name'   => 'font',  
							'target' => 'none', 
							'desc'   => 'Input Font:',
							'options' => array(
								'Open Sans'  => 'Open Sans', 
							),
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none', 
							'desc'   => 'Color:',
						), 
					),
				), 
			), 	
		);
		
		$fields['button'] = array( 
			'name'	=> 'Buttons',
			'field_groups' => array(
				
				array(
					'option_group' => 'none',
					'title'		   => 'Submit Button',
					'disable' => true,
					'fields' => array(
						array(
							'type'	 => 'slider', 
							'name'   => 'button_radius',  
							'target' => 'none',  
							'desc'   => 'Border Radius:',
						), 
						array(
							'type'	 => 'select', 
							'name'   => 'font',  
							'target' => 'button',
							'attr'   => 'none', 
							'desc'   => 'Font:',
							'options' => array(
								'Open Sans'  => 'Open Sans', 
							),
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none',  
							'desc'   => 'Color:',
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'background',  
							'target' => 'none',
							'desc'   => 'Color:',
						),  
					),
				), 
			), 	
		);
		
		$fields['link'] = array( 
			'name'	=> 'Link',
			'field_groups' => array(
				array(
					'option_group' => 'none',
					'title'		   => 'Link',
					'disable' => true,
					'fields' => array( 
						array(
							'type'	 => 'select', 
							'name'   => 'font',  
							'target' => 'none', 
							'desc'   => 'Font:',
							'options' => array(
								'Open Sans'  => 'Open Sans', 
							),
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target'   => 'none', 
							'desc'   => 'Color:',
						), 
					),
				), 
			), 	
		);
		
		$fields['close_button'] = array( 
			'name'	=> 'Close Button',
			'field_groups' => array(
				array(
					'option_group' => 'none',
					'title'		   => 'Close Button',
					'disable' => true,
					'fields' => array(  
						array(
							'type'	 => 'color_picker',
							'name'   => 'color',  
							'target' => 'none',  
							'desc'   => 'Color:',
						),
						array(
							'type'	 => 'color_picker',
							'name'   => 'background',  
							'target' => 'none', 
							'desc'   => 'Color:',
						),  
					),
				), 
			), 	
		);
		
		return $this->build_tabs($fields);
	}  
	
	private function build_tabs($fields) {
		if(!is_array($this->fields)) return; 
		 
		$controls ='';
		$i=0;
		foreach($fields as $field):
		
			$section_name = !empty($field['name']) ? $field['name'] : 'Section';
			$controls .='
				<h3 class="accordion-section-title" tabindex="'.$i.'">
					'.$section_name.'
					<span class="screen-reader-text">Press return or enter to expand</span> 
				</h3>';	
			$controls .= '<div class="accordion-section-content">';	 
			
			foreach($field['field_groups'] as $option):   
				$controls .= $this->build_sections($option); 
			endforeach;
			$i++;
			$controls .= '</div>'; 
		endforeach;
		
		return $controls; 
	}
	
	/**
	 * Build fields groups
	 *
	 * @since     1.0.0
	 *
	 * @return    $section - html
	 */
	private function build_sections($fields) {
		if(!is_array($fields)) return; 
		
		$section = '<div class="chch-pusf-fields-wrapper">';
		
		if(isset($fields['disable'])){
			$section .= '
				<div class="chch-pusf-overlay">
					<a href="http://ch-ch.org/pupro" target="_blank">AVAILABLE IN PRO</a>
				</div>'; 	
		}
		
		$section .= '<h4>'.$fields['title'].'</h4>'; 
		
		foreach($fields['fields'] as $field): 
			$type_func = 'build_field_'.$field['type'];  
			$section .= $this->$type_func($field, $fields['option_group']);
		endforeach; 
		 
		$section .= ' </div>'; 	 
		
		return $section;  
         
    }  
	
	
	/**
	 * Build slider field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private function build_field_slider($field, $options_group) {  
		 
		$option_html = '<label>';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		 				
		$option_html .= '<script type="text/javascript">
						jQuery(document).ready( function ($) { 
							 $( "#'.$this->template_id.'_'.$field['name'].'-slider" ).slider({
								max: 1,
								min: 0,
								step: 0.1,
								value: 0 
							});
									 
						}); 
						</script>
						<div id="'.$this->template_id.'_'.$field['name'].'-slider"></div>';
						
		$option_html .= '</label>';	
				
		return $option_html;
					 
    }
	
	
	/**
	 * Build color picker field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private  function build_field_color_picker($field, $options_group) { 
		 
		$option_html = '<label class="chch-pusf-option-active">';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		$option_html .= '<input type="text" ';
		$option_html .= $this->build_field_attributes($field, $options_group);	 
		$option_html .= '>';
		$option_html .= '</label>';					
		
		return $option_html; 		 
    }
	
	
	/**
	 * Build revealer field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private function build_field_revealer($field, $options_group) {
		
		$options_prefix = $this->options_prefix;
		$template = $this->template_id;
		 
		$name = $options_prefix.$options_group.'_'.$field['name']; 
		$target = $name.'-revealer';
		
		$options = $this->template_options[$options_group];
		
		$checked = $options[$field['name']] ? 'checked' : '';
		
		$option_html = '<label>';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		$option_html .= '
		<input 
			type="checkbox" 
			name="'.$name.'"
			id="'.$name .'" 
			class="revealer"
			data-customize-target="'.$target.'"    
			data-template="'.$template.'" 
			'.$checked.'
		>';	
		
		$option_html .= '</label>';	
		
		$hide = $options[$field['name']] ? 'chch-pusf-option-active' : 'hide-section';
			
		$option_html .= '<div class="'.$hide.'" id="'.$target.'">';
		$option_html .= '<h4>'.$field['revaeals']['title'].'</h4>';	
				
		foreach($field['revaeals']['fields'] as $reveals): 
			$type_func = 'build_field_'.$reveals['type'];  
		 	$option_html .= $this->$type_func($reveals, $options_group);
		endforeach;
					
		$option_html .= '</div>';	
		
		return $option_html;
					 
    }
	
	
	/**
	 * Build revealer group field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private function build_field_revealer_group($field, $options_group) {
		
		$options_prefix = $this->options_prefix;
		$template = $this->template_id;
		
		$option_name =  $field['name'];
		$name = $options_prefix.$options_group.'_'.$field['name']; 
		$group = $options_group.'-'.$field['name'].'-group';
		
		$options = $this->template_options[$options_group]; 
		
		$option_html = '<label>';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		
		$option_html .= '<select 
						name="'.$name.'" 
						class="revealer-group" 
						data-group="'.$group.'"  
						data-customize-target="'.$field['target'].'"  
						data-attr="'.$field['attr'].'" 
						data-template="'.$template.'"  
						> ';
						
		if(!empty($field['options'])):
			foreach($field['options'] as $val => $desc):
				$selected = '';
				if($options[$field['name']] == $val){
						$selected = 'selected';
				}
				$option_html .= '<option value="'.$val.'" '.$selected.'>'.$desc.'</option> ';
			endforeach;
		endif; 
		
		$option_html .= '</select>';	
		$option_html .= '</label>';	
		
		foreach($field['revaeals'] as $reveals): 
			$hide = 'hide-section';
			if($this->template_options[$options_group][$option_name] == $reveals['section_id']){
				$hide = 'chch-pusf-option-active';	
			}
				
			$option_html .= '<div class="'.$hide.' '.$group.' '.$this->template_options[$options_group][$field['name']].'" id="'.$reveals['section_id'].'">';
						
			foreach($reveals['fields'] as $field): 
				$type_func = 'build_field_'.$field['type'];  
		 		$option_html .= $this->$type_func($field, $options_group);
			endforeach;
			
			$option_html .= '</div>';	
		endforeach;	 
		
		return $option_html;
					 
    }
	
	
	/**
	 * Build text field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private  function build_field_text($field, $options_group) {  
		
		$option_html = '<label class="chch-pusf-option-active">';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		
		$option_html .= '<input type="text" '; 
		$option_html .= $this->build_field_attributes($field, $options_group);	
		$option_html .= '>';
		
		$option_html .= '</label>';		
					
		return $option_html;
					 
    }
	
	
	/**
	 * Build upload field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private  function build_field_upload($field, $options_group) {
		$options_prefix = $this->options_prefix;
		$template = $this->template_id;
		
		$name = $options_prefix.$options_group.'_'.$field['name']; 
		 
	   
		$option_html = '<label><span class="customize-control-title">'.$field['desc'].'</span>';
		$option_html .= '<input  
						type="text" 
						name="'.$name .'"
						id="'.$name .'" 
						value = "'.$this->template_options[$options_group][$field['name']].'"
						class="chch-pusf-customize-style"
						data-customize-target="'.$field['target'].'"  
						data-attr="'.$field['attr'].'"  
						data-template="'.$template.'"  
						>';
		$option_html .= '<input class="chch-pusf-image-upload button" type="button" value="Upload Image" data-target="'.$name .'"/>
							<br />'.$field['desc'];
		$option_html .= '</label>';	
						
		return $option_html;
					 
    }
	
	
	/**
	 * Build select field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private  function build_field_select($field, $options_group) { 
		
		$option_html = '<label><span class="customize-control-title">'.$field['desc'].'</span>';
		
		$option_html .= '<select ';
		$option_html .= $this->build_field_attributes($field, $options_group);	 
		$option_html .= '>';
		
		$option_html .= $this->build_field_values($field, $options_group);
		
		$option_html .= '</select></label>';					
		return $option_html;
					 
    }
	 
	/**
	 * Build checkbox field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private  function build_field_checkbox($field, $options_group) {  
		
		$option_html = '<label class="chch-pusf-option-active">';
		$option_html .= '<span class="customize-control-title">'.$field['desc'].'</span>';
		
		$option_html .= '<input type="checkbox" '; 
		$option_html .= $this->build_field_attributes($field, $options_group);	
		$option_html .= $this->build_field_values($field, $options_group);
		$option_html .= '>';
		
		$option_html .= '</label>';		
					
		return $option_html;
					 
    }
	
	/**
	 * Build editor field
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	private function build_field_editor($field, $options_group) {
		$options_prefix = $this->options_prefix;
		$template = $this->template_id;
		
		$options = $this->template_options[$options_group];
		
		$name = $options_prefix.$options_group.'_'.$field['name'];
		
		ob_start();  
 
		$settings = array( 
			'editor_class' => 'chch-pusf-customize-content',
			'media_buttons' => false,
			'quicktags' => false,
			'wpautop' => false,
			'textarea_name' => $name,
			'tinymce' => array(
				'toolbar1'=> ', bold,italic,underline,link,unlink,undo,redo',
				'toolbar2'=> '',
				'toolbar3'=> ''
			),
			'forced_root_block' => '',
			'force_p_newlines' => '',
		);
						 
		echo '<label><span class="customize-control-title">'.$field['desc'].'</span>';
		 wp_editor( wpautop($options[$field['name']]), $field['name'].'_'.$template, $settings ); 
	  
		echo '</label>';
		$option_html = ob_get_clean();					
		return $option_html;
					 
    }  
	 
	
	/**
	 * Return field attributes
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	function build_field_attributes($atts, $options_group){ 
		
		$type = $atts['type'];   
		  
		$attributes = ' ';	
		
		if(isset($atts['name']) && !empty($atts['name']))
		{
			$name = $this->options_prefix.$options_group.'_'.$atts['name'];	
		}
		else
		{
			$name = $this->options_prefix.$options_group.'_field';		
		}
		
		if(isset($atts['id']) && !empty($atts['id']))
		{
			$id = $atts['id'];	
		}
		else
		{
			$id = $name;		
		}
		
		$target = '';	
		if(isset($atts['target']) && !empty($atts['target']))
		{
			$target = $atts['target'];	
		} 
		
		$unit = '';
		if(isset($atts['unit']) && !empty($atts['unit']))
		{
			$unit = $atts['unit'];	
		}
		
		$attr = '';
		if(isset($atts['attr']) && !empty($atts['attr']))
		{
			$attr = $atts['attr'];	
		} 
		
		$value =  $this->build_field_values($atts, $options_group);
		 
		
		$action = '';
		
		if(isset($atts['action']) && !empty($atts['action']))
		{
			if($atts['target'] !=='none')
			{
				switch($atts['action']){
					case 'css': 
						$action = 'chch-pusf-customize-style';
						break;
					case 'text': 
						$action = 'chch-pusf-customize-content';
						break; 	 			
				}
			}
		}
		else
		{
			switch($type){
				case 'color_picker': 
					$action = 'chch-pusf-colorpicker';
					break;
					
				case 'revealer': 
					$action = 'revealer';
					break;
					 
				case 'revealer_group': 
					$action = 'revealer-group';
					break;
					 	
				case 'font': 
					$action = 'chch-pusf-fonts';
					break; 	
					 	
			}
			
			if(($type != 'revealer' || $type != 'revealer_group' || $type != 'text') && $atts['target'] !=='none') 
			{
				$action .= ' chch-pusf-customize-style';
			}
		}
		
		if(isset($atts['class']) && !empty($atts['class'])){
			$action .= ' '.$atts['class'];
		}
		
		$attributes .= 'name="'.$name.'" ';	
		$attributes .= 'id="'.$id.'" ';	
		$attributes .= 'class="'.$action.'" ';	 
		$attributes .= 'data-template="'.$this->template_id.'" ';
		$attributes .= 'data-customize-target="'.$target.'" '; 
		
		if($unit) {
			$attributes .= 'data-unit="'.$unit.'" '; 	
		}
		
		if($attr) {
			$attributes .= 'data-attr="'.$attr.'" '; 	
		}
		
		$exclude_types = array('revealer','revealer_group','checkbox','select');
		if(!in_array($type, $exclude_types)) 
		{
			$attributes .= 'value="'.$value.'" '; 
		}
		
		return $attributes; 
	}
	
	/**
	 * get field values
	 *
	 * @since     1.0.0
	 *
	 * @return    $option_html - html
	 */
	function build_field_values($atts, $options_group){ 
		$option = '';
		if(isset($this->template_options[$options_group][$atts['name']]))
		{
			$option = $this->template_options[$options_group][$atts['name']];	
		}
		
		switch($atts['type']):
			case 'select':
				$select_option ='';
				foreach($atts['options'] as $val => $desc):
					$selected = '';
					if($option == $val){
							$selected = 'selected';
					}
					$select_option .= '<option value="'.$val.'" '.$selected.'>'.$desc.'</option> ';	
				endforeach; 	
				return $select_option;
			break; 
			case 'checkbox':
				if($option):
					return 'checked'; 
				endif;	
			break;
			default :
			
				if(!empty($option)):
					return $option;
				else:
					return '';
				endif;
				
			break;
		endswitch; 
	}
}