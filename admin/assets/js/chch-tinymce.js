
jQuery(document).ready(function($) { 

    tinymce.PluginManager.add('keyup_event', function(editor, url) { 
        editor.on('keyup', function(e) { 
			var editorId = editor.id;
            var get_ed_content = tinymce.activeEditor.getContent(); 
            do_stuff_here(editorId,get_ed_content);
        });
		
		editor.on('change', function(e) { 
			var editorId = editor.id;
            var get_ed_content = tinymce.activeEditor.getContent(); 
            do_stuff_here(editorId,get_ed_content);
        });
    });
 
    $('#content').on('keyup, change', function(e) { 
	 	var editorId = tinymce.activeEditor.id;
        var get_ed_content = tinymce.activeEditor.getContent(); 
        do_stuff_here(editorId, get_ed_content);
    });

    // This function allows the script to run from both locations (visual and text)
    function do_stuff_here(id,content) {
		var target = id.split('_');
		var text = $(content).html();
		
		if(typeof text === "undefined"){
			text='';
		}
		switch(target[0])
		{
			case 'header':
				$('#cc-pu-customize-preview-'+target[1]+' .cc-pu-header-section h2').html(text);
			break;	
			
			case 'subheader':
				$('#cc-pu-customize-preview-'+target[1]+' .cc-pu-subheader-section h3').html(text);
			break;
			
			case 'content':
				$('#cc-pu-customize-preview-'+target[1]+' .cc-pu-content-section').html(content);
			break;	
			
			case 'privacy':
				$('#cc-pu-customize-preview-'+target[2]+' .cc-pu-privacy-section p').html(text);
			break; 
		} 
    }
});