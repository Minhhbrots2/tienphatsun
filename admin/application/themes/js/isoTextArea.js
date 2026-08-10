/*
// Created By Dzung
// isoTextArea Extend
// VietISO Office
version 1.0.0
*/
(function($){
	$.fn.isoTextArea = function() {
		var $editorID = $(this).attr('id');
		tinyMCE.init({
			theme : "advanced",	
			elements : $editorID, 
			mode : $editorID!='' ? "exact" : " ",	
			plugins : "pdw,advimage,table,advlink,contextmenu,searchreplace,xhtmlxtras,preview,wordcount,inlinepopups,style,paste,fullscreen,advlist",	skin:"wp_theme",	
			theme_advanced_buttons1:"undo,redo,bold,underline,italic,bullist,numlist,forecolor,justifyleft,justifycenter,justifyright,justifyfull,image,link,unlink,anchor,pastetext,pasteword,removeformat,cleanup,pdw_toggle",	
			theme_advanced_buttons2:"formatselect,fontselect,fontsizeselect,backcolor,media,|,|,hr,sub,sup,charmap,strikethrough,|,blockquote,outdent,indent,code ",
			theme_advanced_buttons3:"tablecontrols,search,replace,cite,ins,del,abbr,acronym,preview,styleprops,fullscreen",	
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
			paste_strip_class_attributes:"all",	
			pdw_toggle_on : 1, 
			pdw_toggle_toolbars : "2,3",
		});
		return this;
	};
})(jQuery);
(function($){
	$.fn.isoTextAreaSimple = function() {
		var $editorID = $(this).attr('id');
		tinyMCE.init({
			theme : "advanced",	
			elements : $editorID,
			height : 100,
			mode : $editorID !='' ? "exact" : " ",
			plugins : "pdw,advimage,table,advlink,contextmenu,searchreplace,xhtmlxtras,wordcount,inlinepopups,style,advlist",
			skin:"wp_theme",	
			theme_advanced_buttons1:"undo,redo,bold,underline,italic,bullist,numlist,fontselect,forecolor,pdw_toggle",	
			theme_advanced_buttons2:"formatselect,fontsizeselect,justifyleft,justifycenter,justifyright,justifyfull,image,link,unlink,cleanup",
			theme_advanced_buttons3:"tablecontrols,search,replace,backcolor,media",	
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
			paste_strip_class_attributes:"all",	
			pdw_toggle_on : 1, 
			pdw_toggle_toolbars : "2,3",
		});
		return this;
	};
})(jQuery);
(function($){
	$.fn.isoTextAreaFix = function() {
		var $editorID = $(this).attr('id');
		tinyMCE.init({
			elements : $editorID,
			height : 100,
			theme : "advanced",	
			mode : "exact",
			plugins : "pdw,advimage,table,advlink,contextmenu,searchreplace,xhtmlxtras,wordcount,inlinepopups,style,advlist",
			skin:"wp_theme",	
			theme_advanced_buttons1:"bold,underline,italic,bullist,numlist,forecolor,justifyleft,justifycenter,justifyright,justifyfull,image,link,unlink,pasteword,removeformat,cleanup,pdw_toggle",	
			theme_advanced_buttons2:"formatselect,fontselect,fontsizeselect,backcolor,media,hr,outdent,indent,code,styleprops",
			theme_advanced_buttons3:"tablecontrols,search,replace,cite,ins,del",	
			theme_advanced_buttons4:"",	
			language:"en",	
			theme_advanced_toolbar_location:"top",	
			theme_advanced_toolbar_align:"left",
			theme_advanced_statusbar_location:"bottom",
			theme_advanced_resizing:"false",
			theme_advanced_resize_horizontal:"false",
   		 	theme_advanced_resizing_use_cookie : false,
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
		});
		return this;
	};
})(jQuery);
// JavaScript Document

