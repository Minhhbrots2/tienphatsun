$Core.crawl = {
	open_agency: (_this, e) => {
		e.preventDefault();
		var agency_id = $(_this).attr('agency_id'),
			stock_type = $(_this).attr('stock_type'),
			$_adata = {'agency_id':agency_id,'stock_type':stock_type};
		toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_agency', $_adata, function(respJson){
			toggleIndicatior(0);
			makepopup('auto','auto', respJson.html, respJson.uid);
			if(respJson.callback) eval(respJson.callback);
		}, 'json');
		return false;
	},
	open_sheet: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			gId = $(_this).attr('gId'),
			stock_type = $(_this).attr('stock_type'),
			spreadsheetId = $(`.spreadsheetId_${gId}`).val();
		if($Core.util.isEmpty(spreadsheetId)){
			$Core.alert.error("Bạn chưa nhập vào Google Sheet ID");
		} else {
			toggleIndicatior(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_sheet', {
				'uid' : uid,
				'gId' : gId,
				'stock_type' : stock_type,
				'spreadsheetId' : spreadsheetId
			}, function(respJson){
				toggleIndicatior(0);
				$Core.popup.open('auto','auto', respJson.html, 'open_sheet');
				if(respJson.callback) 
					eval(respJson.callback);
			}, 'json');
		}
		return false;
	},
	update_sheet: (_this, e) => {
		e.preventDefault();
		var gId = $(_this).attr('gId'),
			stock_type = $(_this).attr('stock_type'),
			spreadsheetId = $(_this).attr('spreadsheetId'),
			sheets = new Array(),
			sheet_ids = new Array(),
			stock_points = new Array();
		if($(`.${spreadsheetId}:checked`).length){
			if($(".is_stock_point_"+spreadsheetId+":checked").length > 0) {
				$(`.${spreadsheetId}:checked`).each(function(index,elm){
					var tr_row = $(elm).closest("tr");
					sheets.push($(elm).val());
					sheet_ids.push($(elm).attr("sheet_id"));
					var is_stock_point = $(".is_stock_point_"+spreadsheetId,tr_row).is(":checked") ? 1 : 0;
					stock_points.push(is_stock_point);
				});
			}else{
				var sheets = $Core.util.getCheckBoxValueByClass(spreadsheetId),
				sheet_ids = $Core.util.getCheckBoxAttrByClass(spreadsheetId, 'sheet_id');
				console.log(sheets);
			}
			$(`input[gId=is_stock_point_${gId}][type=hidden]`).val(stock_points.join('|'));
			$(`input[gId=${gId}][type=text]`).val(sheets.join('|')).attr("sheet_name",sheets.join('|'));
			$(`input[gId=${gId}][type=hidden]`).val(sheet_ids.join('|'));
						
			$Core.popup.close($(_this).closest('.modal'));
			if(stock_type == 177) {
				
			}
		} else {
			$Core.alert.error("Bạn phải chọn ít nhất một Sheet");
		}
		return false;
	},
	save_agency: function (_this,e){
		e.preventDefault();
		var _form = $(_this).closest('form'),
			agency_id = $(_this).attr("agency_id"),
			stock_type = $(_this).attr("stock_type"),
			$_adata = {'agency_id':agency_id, 'stock_type':stock_type};
		$("input.sheet_name").each(function(i,elm) {
			$_adata[$(elm).attr("name")] = $(elm).attr("sheet_name");
		});
		toggleIndicatior(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=save_agency',
			data: $_adata,
			dataType:'json',
			success : function(respJson){
				toggleIndicatior(0);
				if(respJson.result){
					alertify.success('Success !');
					window.location.reload();
				}else{
					alertify.error('Error!');
				}
			}
		});	
		return false;
	},
	handle_status: function (_this,e){
		var agency_id = $(_this).attr("agency_id"),
			block_id = $(_this).attr("block_id"),
			project_id = $(_this).attr("project_id"),
			stock_type = $(_this).attr("stock_type"),
			is_crawl = $(_this).is(":checked") ? 1 : 0;
			$_adata = {
				'agency_id':agency_id, 
				'block_id' : block_id, 
				'project_id' : project_id, 
				'is_crawl': is_crawl, 
				'stock_type': stock_type
			};
		toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=handle_status', $_adata, function(respJson){
			toggleIndicatior(0);
			if(respJson.result){
				alertify.success('Success !');
			}else{
				alertify.error('Error!');
			}
		}, 'json');
	},
	do_crawl: (_this, e) => {
		$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn thực hiện?", function(){
			alert("x");
		});
	},
	crawl_agency: function (_this,e){
		e.preventDefault();
		var agency_id = $(_this).attr("agency_id");
		$Core.alert.confirm("Xác nhận", "Bạn có chắc chắn muốn thực hiện?", function(){
			toggleIndicatior(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=crawl_agency', {
				'agency_id':agency_id
			}, function(respJson){
				toggleIndicatior(0);
				if(respJson.result){
					alertify.success('Success !');
				}else{
					alertify.error('Error!');
				}
			}, 'json');
		});
		return false;
	},
	open_config_column: function (_this,e){
		e.preventDefault();
		var $_adata = {},
			uid = $(_this).attr('uid'),
			gId = $(_this).attr('gId'),
			agency_id = $(_this).attr("agency_id"),
			target_id = $(_this).attr("target_id"),
			stock_type = $(_this).attr("stock_type"),
			spreadsheetId = $(`.spreadsheetId_${gId}`).val(),
			sheet_id = $(`.sheet_id_${gId}`).val(),
			sheet_name = $(`.sheet_name_${gId}`).attr("sheet_name"),
			$_adata = {'sid' : uid,'agency_id':agency_id,'target_id' : target_id,'stock_type': stock_type, 'spreadsheetId': spreadsheetId, 'sheet_id': sheet_id, 'sheet_name': sheet_name};
		
		if($(`.is_stock_point_`+gId).length > 0) {
			var group_price_sheets = $(_this).closest(".group_price_sheets"),
				is_stock_point = $(`.is_stock_point_`+gId).val();
				
			$(".sheet_configs",group_price_sheets).each(function(index,_elm){
				var building_ids = new Array();
				$(".checkitem:checked",$(_elm)).each(function(index,elm){
					building_ids.push($(elm).val());
					$_adata[$(elm).data("name")] = building_ids;
				});
			});
			$_adata["is_stock_point"] = is_stock_point;
		}
			
		toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_config_column', $_adata, function(respJson){
			toggleIndicatior(0);
			if(respJson.result){
				$Core.popup.open('auto', 'auto', respJson.html, 'open_config_column');
			}else{
				alertify.error(respJson.msg);
			}
		}, 'json');
		return false;
	},
	do_config_column: function (_this,e){
		e.preventDefault();
		var _form = $(_this).closest("form"),
			agency_id = $(_this).attr("agency_id"),
			target_id = $(_this).attr("target_id"),
			stock_type = $(_this).attr("stock_type"),
			sheet_name = $(_this).attr("sheet_name"),
			gId = $(_this).attr("gId");
			$_adata = {
				'agency_id':agency_id, 
				'target_id' : target_id,
				'stock_type': stock_type, 
				'sheet_name': sheet_name, 
				'gId': gId
			};
		toggleIndicatior(1);
		_form.ajaxSubmit({
			type : 'POST',
			url : path_ajax_script+'/index.php?mod='+mod+'&act=do_config_column',
			data: $_adata,
			dataType:'json',
			success : function(respJson){
				toggleIndicatior(0);
				if(respJson.result){
					$Core.alert.success('Success !');
					$Core.popup.close(_form.closest('.modal'));
				}else{
					$Core.alert.error('Error!');
				}
			}
		});	
		return false;
	},
	addColor: function (_this){
		var _type=$(_this).attr("_type"),
			target_id = $(_this).attr("target_id"),
			sheet_name = $(_this).attr("sheet_name"),
			_with = $(_this).attr("_with");
		if(_with == "lowfloor") {
			if(_type == "color_sold") {
				$(_this).parent().before(`<div class="w-35px">
					<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[`+target_id+`][`+sheet_name+`][color_sold][]" value="" placeholder="#ff0000" maxlength="255">
				</div>`);
			}else if(_type == "color_dq") {
				$(_this).parent().before(`<div class="w-35px">
					<input type="color" class="form-control color_dq px-0" onClick="this.select();" name="crawl_lowfloor[`+target_id+`][`+sheet_name+`][color_dq][]" value="" placeholder="#ff0000" maxlength="255">
				</div>`);
			}else if(_type == "color_break"){
				$(_this).parent().before(`<div class="col-md-2">
					<input type="color" class="form-control color_break px-0" onClick="this.select();" name="crawl_lowfloor[`+target_id+`][`+sheet_name+`][color_dq][]" value="" placeholder="#ff0000" maxlength="255">
				</div>`);
			}
		}
		var lst_parent = $(_this).closest(".lst_color");
		if($("input[type='color']",lst_parent).length > 1) {
			$(".btn-minus",lst_parent).removeClass("d-none");
		}		
		return false;
	},
	removeColor: function (_this){
		$(_this).parent().prevAll('div').first().remove();
		var lst_parent = $(_this).closest(".lst_color");
		if($(_this).parent().prevAll('div').length == 1) {
			$(_this).addClass("d-none");
		}
		return false;
	},
	
	open_help: function(_this, e){
		e.preventDefault();
		var type = $(_this).data("type");
		toggleIndicatior(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_help', {"type":type}, function(respJson){
			toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
		return false;
	},
	checkTwoItem: function(_this, e){
		e.preventDefault();
		var table = $(_this).closest("table"),
			_type = $(_this).attr("_type");
		console.log($("input[_type='"+_type+"']:checked",table).length);
		if($("input[_type='"+_type+"']:checked",table).length > 2) {
			$(_this).prop("checked",false);
			alertify.error("Chọn khoảng giá trị đầu cuối");
		}
		return false;
	},
}
