$Core.performance = {
	open: function(_this, e){
		e.preventDefault();
		var stock_id = $(_this).attr('stock_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=ajax&sub=performance&act=performance', {
			'stock_id' : stock_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.bs-webui-popover').webuiPopover('hideAll');
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			$('#'+respJson.uid).on('shown.bs.modal', function(){
				var _modal = $(this);
				$('[contenteditable=true]',_modal).on('keydown', function(e) {
					var value = $(this).text();
					if ((e.keyCode === 110 || e.keyCode === 190) && value.includes('.')) {
						e.preventDefault(); // Ngăn không cho nhập thêm dấu chấm
					}
					if (
						$.inArray(e.keyCode, [46, 8, 9, 27, 110, 190]) !== -1 || // Điều khiển dấu chấm và các phím cơ bản
						(e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) || // Ctrl+A
						(e.keyCode >= 35 && e.keyCode <= 40) // Các phím mũi tên
					) {
						return;
					}

					// Đảm bảo đó là số (0-9)
					if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
						e.preventDefault(); // Ngăn không cho nhập ký tự không hợp lệ
					}
				});
				$('[contenteditable=true]',_modal).on('focus', function(e) {
					var value = $(this).text(),
						field_value  = $(this).attr("field_value");
					$(this).text(field_value);					
				});
			});
			
		}, 'json');
		return false;
	},
	formatPrice : function (value){
		value = value.replaceAll(".","");
		value = value.replaceAll(",","");
		value = value.replaceAll(" ","");
		value = value.replaceAll("đ","");
		if(value == ""){
			return 0;
		}
		value = parseInt(value);
		return value;
	},
	loadTime : function (_this,e){
		e.preventDefault();
		var _form = $(_this).closest("form"),
			time_buy = $("input[name=time_buy]",_form).val(),
			time_profit = $("input[name=time_profit]",_form).val(),
			time_leasing = $("input[name=time_leasing]",_form).val(),
			time_AHNG = $("input[name=time_AHNG]",_form).val(),
			uid = $(_this).attr("uid");
		var $_adata = {
			"time_buy"		: 	time_buy,
			"time_profit"	: 	time_profit,
			"time_leasing"	: 	time_leasing,
			"time_AHNG"		: 	time_AHNG,
		};
		$.post(PCMS_URL+'/index.php?mod=ajax&sub=performance&act=loadTime', $_adata, function(respJson){
			$Core.util.toggleIndicatior(0);
			$(".time_loan",_form).attr("field_value",respJson.number_month_loan).text(respJson.number_month_loan);
			$(".title_profit",_form).text(`Sau `+respJson.number_month_loan+` tháng chốt lời và bán`);
			$(".title_leasing",_form).text(`Sau `+respJson.number_month_leasing_buy+` tháng kể từ lúc mua`);
			$(".time_leasing",_form).attr("field_value",respJson.number_month_leasing).text(respJson.number_month_leasing);
			$(".interest_first",_form).attr("field_value",respJson.number_month_interest_first).text(respJson.number_month_interest_first);
			$(".interest_last",_form).attr("field_value",respJson.number_month_interest_last).text(respJson.number_month_interest_last);
			$(".title_interest_first",_form).text(`Lãi suất trong `+respJson.number_month_interest_first+` tháng đầu tiên`);
			$(".title_interest_last",_form).text(`Lãi suất trong `+respJson.number_month_interest_last+` tháng tiếp theo`);
			$(".time_handover",_form).text(respJson.time_handover);
			
			$("input[name=time_profit]",_form).val(respJson.time_profit).attr("min",respJson.time_min);
			$("input[name=time_leasing]",_form).val(respJson.time_leasing).attr("min",respJson.time_min);
			$Core.performance.loadPrice(uid);
		}, 'json');
	},
	changeTime : function (_this,e){
		var value = $(_this).text(),
			_form = $(_this).closest("form"),
			field_value = $(_this).attr("field_value"),
			max = $(_this).attr("max"),
			type = $(_this).attr("type");
		if(type == "_EXPENSE") {
			max = $(".time_loan",_form).attr("field_value");	
		}
		if(parseInt(value) > max){
			$(_this).text(field_value);
			$(_this).focus();
			alertify.error(`Thời gian tối đa là `+ max + ` tháng`);
			e.preventDefault();
			return false;
		}
		if($(_this).hasClass("interest_first")) {
			var interest_last = parseInt(max) - parseInt(value);
			$(".interest_last",_form).attr("field_value",interest_last).text(interest_last);
		}
	},
	loadCapital : function (_this,e){
		var _form = $(_this).closest("form"),
			pecent = $(_this).val(),
			total_price_vat = parseFloat($("input[name=total_price_vat]",_form).val()),
			uid = $(_this).attr("uid");
		pecent = (parseFloat(pecent) > 0) ? parseFloat(pecent) : 0;
		if(parseFloat(pecent) < 0 ||  parseFloat(pecent) > 100) {
			alertify.error("Giá trị tối đa là 100 %");
			pecent = 20;
			$(_this).val(20);
			$(_this).focus();
		}
		var capital = total_price_vat * pecent / 100;
		capital = Math.round(capital)
		var loan = total_price_vat - capital;
		$("input[name=capital]",_form).val(capital);
		$("input[name=loan]",_form).val(loan);
		$(".td_capital",_form).text($Core.util.format_price(capital,0,",",".")+" đ");
		$(".td_loan",_form).text($Core.util.format_price(loan,0,",",".")+" đ");
		$(".td_percent_capital",_form).text(pecent+"%");
		
		$Core.performance.loadPrice(uid);
	},
	loadPriceTable :function(_this,e){
		var value = $(_this).text(),
			unit = $(_this).attr("unit_type"),
			uid = $(_this).attr("uid");
		if(unit == "_MONEY") {
			value = $Core.performance.formatPrice(value);
			value = parseInt(value);
		}else{			
			value = value.replaceAll(" ","");
			value = value.replaceAll("%","");
			value = value.trim();
			value = value.trim(".");
			if(value == "") {
				value = 0;
			}
			value = parseFloat(value);
			if(value > 100) {
				alertify.error("Giá trị tối đa là 100 %");
				value = $(_this).attr("field_value");
				$(_this).text(value+"%");
				$(_this).focus();
				return false;
			}
		}
		console.log(value);
		$(_this).attr("field_value",value);
		if($(_this).hasClass("td_value")) {
			var unit_type = $(_this).attr("unit_type");
			if(unit_type == "_PERCENT"){
				$(_this).text(value+"%");
			}else{
				$(_this).text($Core.util.format_price(value,0,",",".")+" đ");
			}
		}
		setTimeout(function(){
			$Core.performance.loadPrice(uid);
		},100);
	},
	loadPrice : function (uid){		
		var _form = $("#"+uid).find("form"),
			total_price_vat = parseFloat($("input[name=total_price_vat]",_form).val()),
			capital = parseFloat($("input[name=capital]",_form).val()),
			loan = parseFloat($("input[name=loan]",_form).val());
		var price_risk = 0,total_price_revenue=0,total_price_expense=capital;
		$("tr.tr_input",_form).each(function(index,elm){
			var price = 0;
			var td_value = $("td.td_value",$(elm)),
				td_time = $("td.td_time",$(elm)),
				td_price = $("td.td_price",$(elm)),
				type = td_value.attr("type"),
				unit_type = td_value.attr("unit_type"),
				value = td_value.attr("field_value"),
				time = td_time.attr("field_value"),
				field = td_value.attr("field");
			if(type == "_REVENUE") {
				if(field == "value_risk") {
					var total_price = 0;
					$("td.td_price.td_input_revenue",_form).each(function(i,el){
						let price_e = $(el).attr("price");
						total_price += parseInt(price_e);
					});
					price_risk = total_price * value/100; 
					price = price_risk;
					total_price_revenue += price_risk;
					$(".title_risk",_form).text(`Tính hệ số chỉ đạt `+value+`% kế hoạch đề ra`);
				}else{
					if(unit_type == "_PERCENT") {
						if(time != "" && parseInt(time) > 0) { 
							price = total_price_vat * value * time / (12 * 100); 
						}else{
							price = total_price_vat * value / 100;
						}
					}else{
						if(time != "" && parseInt(time) > 0) {
							price = value * time; 
						}else{
							price = value;
						}
					}					
					if(!td_price.hasClass("td_input_revenue")) {
						total_price_revenue += price;
					}
				}
				
			}else{
				if(td_value.hasClass("is_interest")) {
					var price_core = loan;
				}else{
					var price_core = capital;
				}
				if(unit_type == "_PERCENT") {
					if(time != "" && parseInt(time) > 0) { 
						price = price_core * value * time / (12 * 100); 
					}else if(parseInt(time) == 0){
						price = 0;
					}else{
						price = price_core * value / 100;
					}
				}else{
					if(time != "" && parseInt(time) > 0) {
						price = value * time; 
					}else if(parseInt(time) == 0){
						price = 0;
					}else{
						price = value;
					}
				}
				var td_price = $(elm).find(".td_price");
				if(!$(elm).find(".td_price").hasClass("no_calculator")) {
					total_price_expense += parseFloat(price);
				}
			}
			$("td.td_price",$(elm)).text($Core.util.format_price(price,0,",",".")+" đ");
			$("td.td_price",$(elm)).attr("price",Math.round(price));
		});
		
		$(".total_price_revenue",_form).text($Core.util.format_price(total_price_revenue,0,",",".")+" đ");
		$(".total_price_revenue",_form).attr("price",Math.round(total_price_revenue));
		$(".total_price_expense",_form).text($Core.util.format_price(total_price_expense,0,",",".")+" đ");
		
		var rate_revenue_expense = (total_price_revenue * 100 / total_price_expense).toFixed(2);
		var time_loan = $("td.time_loan",_form).attr("field_value");
		if(parseInt(time_loan) > 0) {
			var rate_revenue_expense_1_year = (total_price_revenue * 100 / (total_price_expense *(time_loan / 12))).toFixed(2);
		}else{
			var rate_revenue_expense_1_year = 0;
		}
		
		$(".rate_revenue_expense",_form).text(rate_revenue_expense+"%");
		$(".rate_revenue_expense_1_year",_form).text(rate_revenue_expense_1_year+"%");
	}
};