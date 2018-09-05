 
function incluir_editor(campo, tinyMCEImageList){
	
	tinyMCE.init({
		mode : "exact",
		elements : campo,
		width: "100%",
		height: "350",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
	 	
		// Theme options
		theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,fontsizeselect,formatselect,|,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,forecolor,backcolor,|,link,unlink,anchor",
		theme_advanced_buttons2 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,image,media,cleanup,|,cut,copy,paste,pastetext,pasteword,|,charmap,emotions,advhr,|,fullscreen,code",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "estiloFuente.css",
		
		// Drop lists for link/image/media/template dialogs
		external_link_list_url : "../js/var_ficheros.js",
		external_image_list_url : "../js/var_ficheros.js",
		flash_external_list_url : "../js/var_ficheros.js",
		media_external_list_url : "../js/var_ficheros.js",
		template_external_list_url : "../js/var_ficheros.js",	
			
		// Style formats
		/*style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],*/

		// Replace values for the template plugin
		/*template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}*/
		
		file_browser_callback : "fileBrowserCallBack"
	});		 
		/*mode : "exact",
		elements : campo,
		width: "458",
		height: "350",
		theme : "advanced",
		plugins : "devkit,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",				
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_path_location : "bottom",
		content_css : "estilo.css",
		plugin_insertdate_dateFormat : "%Y-%m-%d",
		plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",		
		external_link_list_url : "js/var_ficheros.js",
		external_image_list_url : "js/var_ficheros.js",
		flash_external_list_url : "js/var_ficheros.js",
		media_external_list_url : "js/var_ficheros.js",
		template_external_list_url : "js/var_ficheros.js",		
		file_browser_callback : "fileBrowserCallBack",
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing : true,
		nonbreaking_force_tab : true,
		apply_source_formatting : true,
		template_replace_values : {
			//username : "Jack Black",
			//staffid : "991234"
		}
	});*/
}

function fileBrowserCallBack(field_name, url, type, win) {
	
    var cmsURL = "../js/editor/upload.php?win="+type+"&field_name="+field_name;    // script URL - use an absolute path!

    tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'My File Upload/Browser',
        width : 390,  // Your dimensions may differ - toy around with them!
        height : 205,
        resizable : "no",
        inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
        close_previous : "no"
    }, {
        window : win,
        input : field_name
    });
    return false;


}