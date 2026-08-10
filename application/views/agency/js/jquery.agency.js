$(function(){
	$Core.agency.list({});
});
$Core.agency = {
	list : (options) => {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);	
		$.post(PCMS_URL+'?mod='+MOD+'&act=list', $_adata, function(html){
			$Core.util.toggleIndicatior(0);
			$('.holderPropertyType_agency').html(html);
			$(".holderPropertyType_agency").sortable({
				connectWith: ".holderPropertyType_agency",
				handle: ".mySortableHandler",
				update: function (event, ui) {
					var orderNo = $(this).sortable('toArray'),
					$_adata = {"orderNo":orderNo};
					$Core.util.toggleIndicatior(1);
					$.post(PCMS_URL+'?mod='+MOD+'&act=save_agency&action=_saveorder',$_adata,function(html){
						$Core.util.toggleIndicatior(0);
					});
				}
			}).disableSelection();
			$Core.agency.makeStickyColumns();
		});
	},
	makeStickyColumns: function () {
        let table = document.querySelector("table");
        let rows = table.rows;
        let leftOffset = 0;
        let rightOffset = 0;
        let tableWidth = table.offsetWidth;

        // Xử lý cột sticky bên trái
        let stickyLeftColumns = [];
        for (let i = 0; i < rows[0].cells.length; i++) {
            if (rows[0].cells[i].classList.contains("sticky_left")) {
                stickyLeftColumns.push(i);
            }
        }

        stickyLeftColumns.forEach((colIndex) => {
            for (let row of rows) {
                let cell = row.cells[colIndex];
                if (cell) {
                    cell.classList.add("sticky");
                    cell.style.left = `${leftOffset}px`;
                }
            }
            leftOffset += rows[0].cells[colIndex].offsetWidth;
        });

        // Xử lý cột sticky bên phải
        let stickyRightColumns = [];
        for (let i = rows[0].cells.length - 1; i >= 0; i--) {
            if (rows[0].cells[i].classList.contains("sticky_right")) {
                stickyRightColumns.push(i);
            }
        }
        stickyRightColumns.forEach((colIndex) => {
            for (let row of rows) {
                let cell = row.cells[colIndex];
                if (cell) {
                    cell.classList.add("sticky");
                    cell.style.right = `${rightOffset}px`;
                }
            }
            rightOffset += rows[0].cells[colIndex].offsetWidth;
        });
    },
	toggle_sw: function(_this, e){
		e.preventDefault();
		var action = $(_this).attr("action"),
			key = $(_this).attr('key'),
			_table = $(_this).closest("table");
		if(action == "hide") {
			if($(".switch_"+key+":checked",_table).length > 0) {
				$(".switch_"+key,_table).each(function(index,_elm){
					$(_elm).prop("checked",false).trigger("change");
				});				
			}
		}else if(action == "show") {
			$(".switch_"+key+":not(:checked)",_table).each(function(index,_elm){
				$(_elm).prop("checked",true).trigger("change");
			});
		}
	},
	hide_stock_globe :function (_this, e){
		var to_field = $(_this).attr('to_field'),
			agency_id = $(_this).attr('agency_id'),
			status = $(_this).is(':checked') ? 1 : 0; 
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'?mod='+MOD+'&act=hide_stock_globe',{
			'to_field' : to_field,
			'agency_id':agency_id,
			'status' : status
		},function(html){
			$Core.util.toggleIndicatior(0);
		});
	},
	set_status: function(_this, e){
		var agency_id = $(_this).attr('agency_id'),
			is_trash = $(_this).is(':checked') ? 0 : 1;
		$.post(PCMS_URL+'?mod='+MOD+'&act=set_trashed', {
			'agency_id':agency_id, 
			'is_trash':is_trash
		}, function(html){
			
		});
	},
	open: function(_this, e){
		e.preventDefault();
		var agency_id = $(_this).attr('agency_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/?mod='+MOD+'&act=open', {
			'agency_id' : agency_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	add_folder_price_sheets: function(_this, e){
		e.preventDefault();
		var _form = $(_this).closest("form");
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'?mod='+MOD+'&act=add_folder_price_sheets', {}, function(html){
			$Core.util.toggleIndicatior(0);
			$('.group_price_sheets:last',_form).after(html);
		});
		return false;
	},
	save_agency: function (_this){
		var toId = $(_this).attr('toId'),
			_reload = $(_this).attr('_reload'),
			$_form = $(_this).closest('form'),
			agency_id =  $(_this).attr('agency_id'),
			property_type =  $(_this).attr('property_type'),
			$_adata = {'agency_id':agency_id,'property_type':property_type};
		var _validated = 0;
		if($('.input.required,select.required',$_form).length){
			$('.input.required,select.required',$_form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if($('.isoTextArea',$_form).length){
			$('.isoTextArea', $_form).each((_i, _elem) => {
				var name = $(_elem).data('name'),
					editorId = $(_elem).attr('id');
				$_adata[name] = $Core.util.getTextAreaContent(editorId);
			});
		}
		//alert(_validated); return false;
		if(_validated==0){
			$Core.util.toggleIndicatior(1);
			$_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL+'/?mod='+MOD+'&act=save_agency',
				data: $_adata,
				dataType: 'html',
				success: function (agency_id) {
					$Core.util.toggleIndicatior(0);
					$Core.agency.list({'loading':0});
					$Core.alert.success('Saved !');
//					$Core.popup.close($_form.closest(".modal"));
				}
			});
		}
		return false;
	},
	delete: function (_this){
		var agency_id =  $(_this).attr('agency_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa đại lý này?', function(){
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL+'/?mod='+MOD+'&act=save_agency&action=_delete',
				{'agency_id':agency_id},
				function(html){
					$Core.util.toggleIndicatior(0);
					if(html.indexOf('_invalid') >= 0){
						$Core.alert.error('Errors');
					}else{
						$Core.agency.list({});
					}
				}
			);
		});
	},
	storage_cache: function (_this, e){
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/?mod='+MOD+'&act=storage_cache', {}, function(html){
			$Core.util.toggleIndicatior(0);
			if(html.indexOf('_success')>= 0){
				alertify.success('Cập nhật cache đại lý thành công!');
			} else {
				alertify.error('Error !');
			}
		});
		return false;
	},
	open_setting: function (_this, e){
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/?mod='+MOD+'&act=open_setting', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			var __w = $(window).width();
			$Core.popup.openfull(2*__w/3,respJson.html,respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				$Core.agency.load_setting_field();
			});
		},"json");
		return false;
	},
	load_setting_field: function (){
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/?mod='+MOD+'&act=load_setting_field', {}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(".holder_setting_field").html(respJson.html);
		},"json");
		return false;
	},
	open_setting_field : function (_this, e){
		e.preventDefault();
		var id = $(_this).attr('id'),
			type = $(_this).data('type');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/?mod='+MOD+'&act=open_setting_field', {
			'id':id, 'type':type
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, `open_setting_field_${respJson.uid}`);
		}, 'json');
		return false;
	},	
	delete_setting_field: function(_this, e){
		e.preventDefault();
		var id = $(_this).attr('id'),
			type = $(_this).data('type');
		if(confirm("Bạn có chắc chắn muốn xoá cấu hình này?")) {
			$Core.util.toggleIndicatior(1);
			$.ajax({
				type : 'POST',
				url : PCMS_URL+'/?mod='+MOD+'&act=save_setting_field',
				data: {'id':id,'type':type,'action':'delete'},
				dataType:'html',
				success : function(respJson){
					$Core.util.toggleIndicatior(0);
					$Core.agency.load_setting_field();
					$Core.agency.list();
				}
			});	
		}
		return false;
	},
	save_setting_field : function (_this, e){
		e.preventDefault();
		var id = $(_this).attr('id'),
			type = $(_this).data('type'),
			_form = $(_this).closest("form");
		
		var _validated = 0;
		if($('input.required,select.required', _form).length){
			$('input.required,select.required', _form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated == 0){
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type : 'POST',
				url : PCMS_URL+'/?mod='+MOD+'&act=save_setting_field',
				data: {'id':id,'type':type,'action':'save'},
				dataType:'html',
				success : function(respJson){
					$Core.util.toggleIndicatior(0);
					$(_this).closest(".modal").find(".btn-close").trigger("click");
					$Core.agency.load_setting_field();
					$Core.agency.list();
				}
			});	
		}
		return false;
	},		
	select_block: function(_this, e){
		var project_id = $(_this).val(),
			toId = $(_this).attr('toId');
		$.post( PCMS_URL+'/?mod=tool&act=load_block', {
			'project_id' : project_id
		}, function(html){
			$Core.util.toggleIndicatior(0);
			$('#'+toId).html(`<option value="0">Chọn phân khu</option>`+html);
		});
	},
};