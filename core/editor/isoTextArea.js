/*
// Created By Dzung
// isoTextArea Extend
// VietISO Office
version 1.0.0
*/
(function($){
	$.fn.isoTextArea = function() {
		var $editorID = $(this).attr('id'),
			$height = $(this).attr('height') || '200px';
		tinyMCE.execCommand('mceRemoveEditor', false, $editorID);
		tinyMCE.init({
			mode: "exact",
			height : $height,
			// theme: "modern",
			selector: `textarea#${$editorID}`,
			valid_elements : "*[*]",
			plugins: ['advlist autolink lists link image charmap print preview anchor',
				'searchreplace visualblocks code fullscreen','youTube',
				'insertdatetime media table contextmenu paste textcolor colorpicker code'],
			toolbar: (deviceType=='phone'?'insert | styleselect | forecolor backcolor | bold':'undo redo | insert | styleselect | fontselect | fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink image youTube'),
			extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align],+a[id|rel|rev|dir|onclick|tabindex|accesskey|type|name|href=javascript:void(0);|target|title|class]",
			extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align],+a[id|rel|rev|dir|onclick|tabindex|accesskey|type|name|href=javascript:void(0);|target|title|class]",
			fontsize_formats: "8px 10px 12px 14px 18px 24px 36px", 
			menubar: false,
			relative_urls: false,
			remove_string_host: false,
			convert_urls:false,
			apply_source_formatting:false,
			remove_linebreaks: true,
			gecko_spellcheck: true,
			accessibility_focus: true,
			tabfocus_elements:"major-publishing-actions",
			paste_remove_styles: true,
			paste_remove_spans: true,
			paste_strip_class_attributes:"all",
			forced_root_block : "",
			force_br_newlines : false,
			force_p_newlines : true,
			remove_redundant_brs : false,
			convert_newlines_to_brs : false,
			file_browser_callback : elFinderBrowser,
			setup : function(editor) {
				editor.on('BeforeSetContent', function(ed) {
					ed.content = ed.content.replace(/<br\s?\/?>/g,"\n");
				});
			},
			init_instance_callback: function (editor) {
				editor.on('PostProcess', function (ed) {
					ed.content = ed.content.replace(/<br\s?\/?>/g,"\n");
				});
			}
		});
		return this;
	};
})(jQuery);
(function($){
	$.fn.isoTextAreaSimple = function() {
		var $editorID = $(this).attr('id');
		tinyMCE.execCommand('mceRemoveEditor', false, $editorID);
		tinyMCE.init({
			mode: "exact",
			height : "100px",
			theme: "modern",
			selector: '#'+$editorID,
			//valid_elements : "*[*]",
			plugins: [
				'advlist autolink lists link image charmap print preview anchor',
				'searchreplace visualblocks code fullscreen youTube insertdatetime',
				'media table contextmenu paste textcolor colorpicker code'
		  	],
			toolbar: 'undo redo | insert | styleselect | fontselect | fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink image youTube',
			extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align],+a[id|rel|rev|dir|onclick|tabindex|accesskey|type|name|href=javascript:void(0);|target|title|class]",
			fontsize_formats: "8px 10px 12px 14px 18px 24px 36px", 
			menubar: false,
			relative_urls: false,
			remove_string_host: false,
			convert_urls:false,
			apply_source_formatting:false,
			remove_linebreaks: true,
			gecko_spellcheck: true,
			accessibility_focus: true,
			tabfocus_elements:"major-publishing-actions",
			paste_remove_styles: true,
			paste_remove_spans: true,
			paste_strip_class_attributes:"all",
			forced_root_block : "",
			force_br_newlines : false,
			force_p_newlines : true,
			remove_redundant_brs : false,
			convert_newlines_to_brs : false,
			file_browser_callback : elFinderBrowser,
			setup : function(editor) {
				editor.on('BeforeSetContent', function(ed) {
					ed.content = ed.content.replace(/<br\s?\/?>/g,"\n");
				});
			},
			init_instance_callback: function (editor) {
				editor.on('PostProcess', function (ed) {
					ed.content = ed.content.replace(/<br\s?\/?>/g,"\n");
				});
			}
		});
		return this;
	};
})(jQuery);
(function($){
	$.fn.isoTextAreaFix = function() {
		var $editorID = $(this).attr('id');
		tinyMCE.execCommand('mceRemoveEditor', false, $editorID);
		tinyMCE.init({
			mode: "exact",
			height : "250px",
			theme: "modern",
			selector: '#'+$editorID,
			valid_elements : "*[*]",
			plugins: ['advlist autolink lists link charmap print preview anchor','searchreplace visualblocks code fullscreen',
				'insertdatetime media table contextmenu paste textcolor colorpicker code paste'
		  	],
			toolbar: 'undo redo | insert | styleselect | fontselect | fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink image removeformat',
			extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align],+a[id|rel|rev|dir|onclick|tabindex|accesskey|type|name|href=javascript:void(0);|target|title|class]",
			fontsize_formats: "8px 10px 12px 14px 18px 24px 36px", 
			menubar: false,
			paste_as_text: true,
			relative_urls: false,
			force_br_newlines : true,
			force_p_newlines : true,
			forced_root_block : '',
			remove_string_host: false,
			setup : function(editor) {
				editor.on('BeforeSetContent', function(ed) {
					ed.content = ed.content.replace(/<br\s?\/?>/g,"\n");
				});
			},
			init_instance_callback: function (editor) {
				editor.on('PostProcess', function (ed) {
					ed.content = ed.content.replace(/<br\s?\/?>/g,"\n");
				});
			}
		});
		return this;
	};
})(jQuery);
// JavaScript Document