$(document).ready(function(){
	if(mod == "home" && act == 'default') {
		$Core.home.init();
	}
});
$Core.home = {
	init: function (){
		$Core.home.load_list({"clsTable":"Sop"},"_SOP");	
		$Core.home.load_list({"clsTable":"Leasing"},"_LEASING");	
		$Core.home.load_list({"clsTable":"Service"},"_SERVICES");	
		$Core.home.load_list({"clsTable":"Interior"},"_INTERIOR");	
//		$Core.home.load_list({"clsTable":"InteriorRequest"},"_INTERIOR_REQUEST");	
	},
	load_list: function(options, type){
		var $_adata = options || {};
		$_adata["type"] = type;
		
		$Core.util.toggleIndicatior(1);
        $.post(path_ajax_script+'/index.php?mod='+mod+'&act=loadItemHome', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$("#lst"+type).html(respJson.html);			
        }, 'json');
	},
	approve: function (_this, e){
		e.preventDefault(); 
		var action = $(_this).data('action'),
			id = $(_this).data('id'),
			table = $(_this).data('table'),
			type = $(_this).data('type'),
			$_adata = {},
			url = "";
			$_adata['action'] = action;
			$_adata['id'] = id;
			$_adata['table'] = table;	
			$_adata['type'] = type;	
		if(action == "noapprove") {
			var _form = $(_this).closest("form");
			$_adata['notes'] = $("textarea[name='notes']",_form).val();
		}
		$.post(path_ajax_script+'/index.php?mod=home&act=approve', $_adata, function(html){
			if(html.indexOf('_success') >= 0){				
				if(action == "noapprove") {
					$(_this).closest(".modal").find(".close_pop").trigger("click");
				}
				alertify.success("Success !");
				$Core.home.load_list({"clsTable":table},type);	
			}else{
				alertify.error("Error !");
			}
		});
		return false;
	},	
	show_notes: function (_this,e){
		e.preventDefault(); 
		var action = $(_this).data('action'),
			table = $(_this).data('table'),
			type = $(_this).data('type'),
			id = $(_this).data('id');
		if(table != "") {
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=show_notes', { 
				'action'	:	action,
				'table'		:	table,
				'id' 		: 	id,
				'type' 		: 	type,
			}, function(html){
				if(html != ''){
					$Core.popup.open('auto', 'auto', html, 'open_notes');
				}
			});
		}
	},
	delete: function (_this, e){
		e.preventDefault(); 
		var table = $(_this).data('table'),
			id = $(_this).data('id'),
			type = $(_this).data('type'),
			$_adata = {};
			$_adata['table'] = table;
			$_adata['id'] = id;
		if (confirm('Bạn có chắc chắn muốn xoá?')) {
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete', {
				'table' : table,
				'id' : id,
			}, function(html){
				if(html.indexOf('_success') >= 0){
					alertify.success("Success !");
					$Core.home.load_list({"clsTable":table},type);	
				}else{
					alertify.error("Error !");
				}
			});
		}
		return false;
	},
	
};