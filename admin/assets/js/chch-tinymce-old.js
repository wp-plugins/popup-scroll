
jQuery(document).ready(function($) { 

    tinymce.PluginManager.add('keyup_event', function(editor) { 
		
	   editor.onKeyUp.add(function(){
			var editorId = editor.id;
            var get_ed_content = tinymce.activeEditor.getContent(); 
            do_stuff_here(editorId,get_ed_content);
		});
		
		editor.onChange.add(function(){
			var editorId = editor.id;
            var get_ed_content = tinymce.activeEditor.getContent(); 
            do_stuff_here(editorId,get_ed_content);
		});
		 
    }); 

    
    function do_stuff_here(id,content) {
		var target = id.split('_');
		var text = $(content).html();
		
		if(typeof text === "undefined"){
			text='';
		}
		switch(target[0])
		{
			case 'header':
				$('#cc-pu-customize-preview-'+target[1]+' .chch-pusf-header-section h2').html(text);
			break;	
			
			case 'subheader':
				$('#cc-pu-customize-preview-'+target[1]+' .chch-pusf-subheader-section h3').html(text);
			break;
			
			case 'content':
				$('#cc-pu-customize-preview-'+target[1]+' .chch-pusf-content-section').html(content);
			break;	
			
			case 'privacy':
				$('#cc-pu-customize-preview-'+target[2]+' .chch-pusf-privacy-section p').html(text);
			break; 
		} 
    }
});