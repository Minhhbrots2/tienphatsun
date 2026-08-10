$Core.calculator = {
	open: (_this, e) => {
		e.preventDefault();
		var stock_id = $(_this).attr('stock_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=home&sub=calculator&act=open', {
			'stock_id' : stock_id
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$('.bs-webui-popover').webuiPopover('hideAll');
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
			setTimeout(() => {
				$('input[name=price][uid='+respJson.uid+']').trigger('change');
			}, 200);
		}, 'json');
		return false;
	}, set_active: (_this, e) => {
		var _gId = $(_this).attr('id'),
			_toId = $(_this).attr('toId'),
			_value = $('#'+_toId).val();
		if($(_this).is(':checked')){
			$('#block_'+_gId)
				.removeClass('d-none')
				.addClass('d-flex');
		} else {
			if(!$Core.util.isEmpty(_value)){
				$('#range_'+_gId).val(0);
				$('#'+_toId).val(0).trigger('change');
			} else {
				$('#_toId'+_gId).val(0);
				$('#'+_toId).val(0);
			}
			$('#block_'+_gId)
				.removeClass('d-flex')
				.addClass('d-none');
		}
	}, set_bank: (_this, e) => {
		var preferentialTime = $(_this).find(":selected").attr('preferentialTime'),
			preferentialRate = $(_this).find(":selected").attr('preferentialRate');
		if(parseInt(preferentialTime) > 0){
			preferentialTime /= 12;
		} else {
			preferentialTime = 0;
		}
		$('input[name=introMonths]').val(preferentialTime);
		$('input[name=introRate]').val(preferentialRate).trigger('change');
	}, set_bank_v2: (_this, e) => {
		var preferentialTime = $(_this).find(":selected").attr('preferentialTime'),
			preferentialRate = $(_this).find(":selected").attr('preferentialRate'),
			introMonthsUnit = $(_this).find(":selected").attr('introMonthsUnit'),
			rate = $(_this).find(":selected").attr('rate');		
		$('input[name=introMonths]').val(preferentialTime);
		$('input[name=introRate]').val(preferentialRate).trigger('change');
		$('select[name=introMonthsUnit]').val(introMonthsUnit).trigger('change');
		$('input[name=rate]').val(rate).trigger('change');
	}, set_change: (_this, e) => {
		var _toId = $(_this).attr('toId'),
			_value = $(_this).val();
		$(`.${_toId}`).val(_value).trigger('change');
	}, do_calculator: (_this, e) => {
		var _uid = $(_this).attr('uid'),
			_toId = $(_this).attr('toId'),
			_form = $(_this).closest('form'),
			_name = $(_this).attr('name'),
			_value = $(_this).val();
		if($Core.util.isEmptyZero(_value) || parseInt(_value) < 0){
			_value = 0;
			$(_this).val(_value);
		}
		$(`.${_toId}`).val(_value);
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type:'POST',
			dataType : 'json',
			data:{'uid':_uid},
			url: PCMS_URL + "/index.php?mod=home&sub=calculator&act=do_calculator",
			success: function(respJson){
				$Core.util.toggleIndicatior(0);
				$('#viewSubQuote_'+_uid).html(`Tỉ lệ vay ${respJson.ratio}% - ${respJson.year} năm - ${respJson.rate}%/năm`);
				$('#html_MonthlyPayment_'+_uid).html(respJson.html_MonthlyPayment);
				$('#viewTongTienVay_'+_uid).text($Core.util.shortNumber(respJson.loan_amount));
				$('#viewCanTraTruoc_'+_uid).text($.number(respJson.down_payment,0, '.', ','));
				$('#viewGocCanTra_'+_uid).text($.number(respJson.total_principal,0, '.', ','));
				$('#viewLaiCanTra_'+_uid).text($.number(respJson.total_interest,0, '.', ','));
				$('#viewTongTienLaiPromo_'+_uid).text($.number(respJson.total_payment_promo,0, '.', ','));
				$('#viewTongTienLaiSauPromo_'+_uid).text($.number(respJson.total_payment_after,0, '.', ','));
				$('#viewTongTien_'+_uid).text($.number(respJson.total_payment,0, '.', ','));
				$('#viewLaiThangDau_'+_uid).text($.number(respJson.first_payment,0, '.', ','));
				$('#viewPhiTraNoTruocHan_'+_uid).text($.number(respJson.early_fee_value,0, '.', ','));
				$Core.calculator.make_chart(
					respJson.uid,
					respJson.total_principal,
					respJson.total_interest
				);
			}
		});
	}, make_chart: (uid,GocCanTra,LaiCanTra) => {
		$('#boxchart_'+uid).html(`<canvas id="myChart_${uid}" width="180" height="180"></canvas>`);
		var ctx = document.getElementById('myChart_'+uid),
			myChart = new Chart(ctx, {
				type: 'doughnut',
				data: {
					labels: ['Gốc cần trả', 'Lãi cần trả'],
					datasets: [{
						data: [ Number(GocCanTra).toFixed(3),Number(LaiCanTra).toFixed(3)],
						backgroundColor: ['rgba(255, 206, 86, 0.2)','rgba(255, 99, 132, 0.2)'],
						borderColor: ['rgba(255, 206, 86, 1)','rgba(255, 99, 132, 1)'],
						borderWidth: 1,
					}]
				}, options: {
					plugins: {
						legend: {
							position: 'top',
							labels: {
								padding: 15, 
								boxWidth: 20
							}
						}
					}, layout: {
						padding: 10
					}
				}
			});
	}, view: (_this, e) => {
		var uid = $(_this).attr('uid');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=home&sub=calculator&act=view', {
			'html_MonthlyPayment' : $('#html_MonthlyPayment_'+uid).html(),
		}, function(respJson){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto',respJson.html,respJson.uid);
		}, 'json');
	}, export2excel: (_this, e) => {
		e.preventDefault();
		var toId = $(_this).attr('toId');
		let table = new Table2Excel(`#table_${toId}`, {
			exclude: ".noExl",
		});
		table.export();
		return false;
	}
};