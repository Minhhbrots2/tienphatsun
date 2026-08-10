$(function(){
	if($(".input-tags").length){
		$(".input-tags").selectize({
			delimiter: ",",
			persist: false,
			preload: true,
			valueField: 'text',
			labelField: 'text',
			searchField: 'text',
			create: function (input) {
				return {
					value: input,
					text: input,
				};
			}
		});
	}
	
});
$Core.furniture = {
	set_status : function(_this,e){
		e.preventDefault();
		if($(_this).prop("checked") == true){
			$Core.furniture.addProperty(_this,e);
			$("#box_property").show();
		}else{
			$("#box_property").find(".item_property").remove();
			$("#box_property,#boxLstProperty").hide();	
		}
	},
	addProperty : function(_this,e){
		var number_property = $("#box_property").find(".item_property").length;
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=addProperty', {
			'number_property' : number_property
		}, function(respJson){
			vietiso_loading(0);
			$(respJson.html).insertBefore("#addProperty");
			if(respJson.is_max){
				$("#addProperty").hide();
			}
			$("#total_property").text(respJson.number);
		},"json");
	},
	removeProperty: function(_this, e){
		e.preventDefault();
		$(_this).closest(".item_property").remove();
		$("#addProperty").show();
	},
	loadTablePrice: function(_this, e){
		var _form = $("#edititem"),
			property_values = new Array(),
			property_keys = new Array();
		$("input[name='property_values[]']",_form).each(function(index,elm){
			if($(elm).closest(".item_property").find(".property_keys").val() != "" && $(elm).val() != ""){
				property_values.push($(elm).val());
			}			
		});
		$("input[name='property_keys[]']",_form).each(function(index,elm){
			if($(elm).closest(".item_property").find(".property_values").val() != "" && $(elm).val() != ""){
				property_keys.push($(elm).val());	
			}			
		});
		if(property_values.length > 0 && property_keys.length == property_values.length){
			vietiso_loading(1);
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=loadFormPrice', {
				'property_keys' : property_keys,
				'property_values' : property_values,
				'furniture_id' : furniture_id,
			}, function(html){
				vietiso_loading(0);
				$("#boxLstProperty").show();
				$("#lstProperty").html(html);	
				console.log($("#lstProperty").find("tr").length);
				$("#total_property").text($("#lstProperty").find("tr").length);
			});
		}else{
			$("#boxLstProperty").hide();
			$("#lstProperty").html("");
			$("#total_property").text(0);
		}
	},	
	uploadImage :function(_this,e){ 
		var toId = $(_this).attr('toId'),
			toImg = $(_this).attr('toImg'),
			$clsTable = $(_this).attr('clsTable'),
			pvalTable = $(_this).attr('pvalTable');
		let type = $(_this).data('type');
		$('#'+toId).val("").trigger('click');
		$('#'+toId).off().change(function(){
			let files = $('#'+toId).prop('files');
			if (files && files.length) {
				var formData = new FormData();
				formData.append('type',type);
				for (var i = 0; i < files.length; i++) {
					formData.append('images[]',files[i]);
				} 
				formData.append('pvalTable',pvalTable);
				formData.append('clsTable',$clsTable);
				var params = {"type":type, 'toImg':toImg}
				$Core.furniture.image_upload(formData,params);				
			}
		});
	},
	image_upload: function(formData, params){
		vietiso_loading(1);
		$.ajax({
			url: path_ajax_script+"/index.php?mod="+mod+"&act=uploadImage",
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function (html) {
				vietiso_loading(0);
				if(html.indexOf('_success') >= 0){
					var tmp = html.split('|||');
					if(params.type == "images"){
						if($('#'+params.toImg+' .item').length > 0){
							$('#'+params.toImg+' .item:last-child').after(tmp[1]);
						} else {
							$('#'+params.toImg).html(tmp[1]);
						}
					}else if(params.type == 'avatar' || params.type == 'banner'){
						$('#'+params.toImg).attr("src",tmp[1]); 
					}
					
				}
			}
		});
	},
	addVideo: function(_this,option_id){
		var $_adata ={};
		$_adata['option_id'] = option_id;
		var link_file = $(_this).closest(".box_form").find("input[name='link_video']").val();
		$_adata['link_file'] = link_file;
		if(link_file != ''){
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=addVideo', $_adata, function(html){
				if(html != ""){
					console.log(html);
					$('#box_video').html(html);	
				}
				
			}, 'html');
		}
	},
	loadFurniture: function(){
		var slt_cat_ids = $("#slt_cat_ids");
		var toId = slt_cat_ids.attr("toId"),
			cat_ids = slt_cat_ids.val();
		if(cat_ids != "" && cat_ids != null && cat_ids.length > 0){
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=loadFurniture', {
				'cat_ids' : cat_ids,'furniture_option_id':furniture_option_id
			}, function(respJson){
				console.log(respJson);
				vietiso_loading(0);
				$("#loadFurniture").html(respJson.html).show();
				if(furniture_option_id > 0){
					$Core.furniture.loadBill();					
				}
			},"json");
		}else{
			$("#loadFurniture").html("").hide()
		}
		
	},
	dupplicate: function(_this,e){
		e.preventDefault();
		var cat_id = $(_this).data("cat_id"),
			id_dup = $(_this).data("id_dup"),
			typeOption = $(_this).data("type");
		if(cat_id > 0){
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=duplicate', {
				'cat_id' : cat_id,'id_dup':id_dup,'typeOption':typeOption,'furniture_option_id':furniture_option_id
			}, function(respJson){
				console.log(respJson);
				if(respJson.result){
					console.log($(_this).closest(".item_property"));
					$(_this).closest(".item_property").after(respJson.html)
				}	
			},"json");
		}		
	},
	proprertyFurniture: function(_this,type){
		var toId = $(_this).attr("toId"),
			furniture_id = $(_this).data("furniture_id"),
			typeOption = $(_this).data("type"),
			id_dup = $(_this).data("id_dup"),
			cat_id = $(_this).data("cat_id");
		
		if(type == "open"){
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=propertyFurniture', {
				'furniture_id' : furniture_id,
				'type' : type,
				'cat_id' : cat_id,
				'typeOption' : typeOption,
				'id_dup' : id_dup,
			}, function(respJson){
				vietiso_loading(0);
				if(respJson.result){
					$Core.popup.open('auto', 'auto', respJson.html, 'openProprertyFurniture');	
				}				
			},"json");	
		}else if(type == 'load'){
			var _form = $(_this).closest("form"),
				furniture_id = $(_this).data("furniture_id"),
				cat_id = $(_this).data("cat_id");
			var arr_number = [],arr_title = [],arr_price = [];
			var html_p_detail = "";
			$("select[name='number']",_form).each(function(index,elm){
				if($(elm).val() != ""){
					let number = $(elm).val(),
						title = $(elm).data('title'),
						price = $(elm).data('price');
					arr_number.push(number);
					arr_title.push(title);
					arr_price.push(price);
					html_p_detail += `<p>`+title+` (`+number+` x `+$Core.furniture.formatPrice(price,0)+`đ)</p>`;
				}						
			});
			console.log(cat_id,arr_title.toString(),arr_number,arr_price);
			if(typeOption == "duplicate"){
				$("#loadFurniture").find(".row_"+id_dup+"_"+furniture_id+" .property_hidden_dup").val(arr_title.toString());
				$("#loadFurniture").find(".row_"+id_dup+"_"+furniture_id+" .number_hidden_dup").val(arr_number.toString());
				$("#loadFurniture").find(".row_"+id_dup+"_"+furniture_id+" .price_hidden_dup").val(arr_price.toString());
				$("#loadFurniture").find(".row_"+id_dup+"_"+furniture_id+" .p_detail").html(html_p_detail);
			}else{
				$("#loadFurniture").find(".row_"+cat_id+"_"+furniture_id+" .property_hidden").val(arr_title.toString());
				$("#loadFurniture").find(".row_"+cat_id+"_"+furniture_id+" .number_hidden").val(arr_number.toString());
				$("#loadFurniture").find(".row_"+cat_id+"_"+furniture_id+" .price_hidden").val(arr_price.toString());
				$("#loadFurniture").find(".row_"+cat_id+"_"+furniture_id+" .p_detail").html(html_p_detail);
			}			
			$Core.popup.close(_form.closest('.modal'));
			$Core.furniture.loadBill();
		}
	},
	updateNumberSelect : function(_this,event){
		var parent = $(_this).closest("tr"),
			price = $(_this).data("price"),
			number = $(_this).val(),
			typeOption = $(_this).data("type"),
			id_dup = $(_this).data("id_dup"),
			html_p_detail ="";
		if(number == ''){
			parent.find(".number_hidden,.number_hidden_dup").val("");
			parent.find(".price_hidden,.price_hidden_dup").val('');
			parent.find(".p_detail").html(html_p_detail);
		}else{
			html_p_detail += `<p>`+number+` x `+$Core.furniture.formatPrice(price,0)+`đ</p>`;
			if(typeOption == "duplicate"){
				parent.find(" .number_hidden_dup").val(number.toString());
				parent.find(".price_hidden_dup").val(price.toString());
				parent.find(".p_detail").html(html_p_detail);
			}else{
				parent.find(" .number_hidden").val(number.toString());
				parent.find(".price_hidden").val(price.toString());
				parent.find(".p_detail").html(html_p_detail);
			}
			
		}
		
		$Core.furniture.loadBill();	
	},
	loadBill : function(){
		var _form = $("#edititem"),
			dataForm = new FormData($("#edititem")[0]);
		$.ajax({
			'type'	: "POST",
			'dataType'	: "html",
			processData: false,
        	contentType: false,
			data:dataForm,
			url	:	path_ajax_script+'/index.php?mod='+mod+'&act=loadBill',
			success: function(html) {
			   $("#formBill").html(html);
			},
		});
	},
	formatPrice : function (price, decimalCount = 2, decimal = ".", thousands = ",") {
		try {
			decimalCount = Math.abs(decimalCount);
			decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

			const negativeSign = price < 0 ? "-" : "";

			let i = parseInt(price = Math.abs(Number(price) || 0).toFixed(decimalCount)).toString();
			let j = (i.length > 3) ? i.length % 3 : 0;

			return negativeSign +
			(j ? i.substr(0, j) + thousands : '') +
			i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
			(decimalCount ? decimal + Math.abs(price - i).toFixed(decimalCount).slice(2) : "");
		} catch (e) {
			console.log(e);
		}
	},
	deleteProperty : function (_this,e){
		e.preventDefault();
		$(_this).closest(".item_property").remove();
		$Core.furniture.loadBill();	
	}
}