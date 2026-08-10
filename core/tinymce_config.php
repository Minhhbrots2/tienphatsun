<?php
	$tinymce_config = '
	tinyMCE.init({
		theme : "advanced",
		editor_selector:"mceFull",
		mode : "textareas",
		plugins : "pdw,advimage,table,advlink,contextmenu,searchreplace,xhtmlxtras,preview,youtubeIframe,wordcount,inlinepopups,style,paste,fullscreen,advlist,save",
		skin:"wp_theme", theme_advanced_buttons1:"undo,redo,bold,underline,italic,bullist,numlist,forecolor,justifyleft,justifycenter,justifyright,justifyfull,image,youtubeIframe,link,unlink,anchor,pastetext,pasteword,removeformat,cleanup,pdw_toggle", theme_advanced_buttons2:"formatselect,fontselect,fontsizeselect,backcolor,media,|,|,hr,sub,sup,charmap,strikethrough,|,blockquote,outdent,indent,code ",
		theme_advanced_buttons3:"tablecontrols,search,replace,cite,ins,del,abbr,acronym,preview,styleprops,fullscreen", 
		theme_advanced_buttons4:"", 
		language:"en",
		theme_advanced_toolbar_location:"top",
		theme_advanced_toolbar_align:"left",
		theme_advanced_statusbar_location:"bottom",
		theme_advanced_resizing:"1",
		extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align]",
		theme_advanced_resize_horizontal:"",
		dialog_type:"modal",
		relative_urls:"",
		remove_script_host:"",
		convert_urls:"",
		apply_source_formatting:"",
		remove_linebreaks:"1",
		gecko_spellcheck:"1",
		accessibility_focus:"1",
		tabfocus_elements:"major-publishing-actions",
		paste_remove_styles:"1",
		paste_remove_spans:"1",
		paste_strip_class_attributes:"all",
		pdw_toggle_on : 1,
		pdw_toggle_toolbars : "2,3",
		file_browser_callback : \'elFinderBrowser\',
		setup : function(ed) {
		 ed.onKeyDown.add(function(ed, evt) {
			if (evt.keyCode == 83 && evt.ctrlKey && !evt.shiftKey && !evt.altKey && !evt.metaKey) {
			   evt.preventDefault();
			   submitFormEditor();
		   }
		 });
	   }
	});';
	$tinymce_config_simple = '
	tinyMCE.init({
		theme : "advanced",
		editor_selector:"mceSimple",
		mode : "textareas",
		plugins : "",
		skin:"wp_theme", theme_advanced_buttons1:"bold,underline,italic,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,removeformat,", 
		theme_advanced_buttons2:"",
		theme_advanced_buttons3:"", 
		theme_advanced_buttons4:"", 
		language:"en",
		theme_advanced_toolbar_location:"top",
		theme_advanced_toolbar_align:"left",
		theme_advanced_statusbar_location:"bottom",
		theme_advanced_resizing:"1",
		theme_advanced_resize_horizontal:"",
		dialog_type:"modal",
		relative_urls:"",
		remove_script_host:"",
		convert_urls:"",
		apply_source_formatting:"",
		remove_linebreaks:"1",
		gecko_spellcheck:"1",
		accessibility_focus:"1",
		tabfocus_elements:"major-publishing-actions",
		paste_remove_styles:"1",
		paste_remove_spans:"1",
		paste_strip_class_attributes:"all"
	});';					
	define("_TINYMCE_CONFIG", $tinymce_config);
	define("_TINYMCE_CONFIG_SIMPLE", $tinymce_config_simple);
?>