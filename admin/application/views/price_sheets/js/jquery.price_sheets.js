$Core.price_sheets = {
	open: (_this, e) => {
		e.preventDefault();
		var price_sheet_id = $(_this).attr('price_sheet_id'),
			stock_type = $(_this).attr('stock_type');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_price_sheet', {
			'price_sheet_id':price_sheet_id,
			'stock_type':stock_type
		}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'open_price_sheet');
			setTimeout(function(){ $Core.price_sheets.init_all(respJson.price_sheet_id); }, 150);
		}, 'json');
		return false;
	},
	pop_save_price_sheet: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			$_form = $(_this).closest('form'),
			price_sheet_id = $(_this).attr('price_sheet_id');
		if($('input.required', $_form).length){
			$('input.required', $_form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			// Lấy nội dung mọi editor isoTextArea qua util (thống nhất với module project)
			var more = {};
			$('.isoTextArea', $_form).each(function(){
				var name = $(this).data('name'), editorId = $(this).attr('id');
				if(name) more[name] = $Core.util.getTextAreaContent(editorId);
			});
			vietiso_loading(1);
			$_form.ajaxSubmit({
				type:'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_price_sheet',
				data: $.extend(more, {"price_sheet_id":price_sheet_id}),
				dataType:'json',
				success: function(respJson){
					vietiso_loading(0);
					if(respJson.msg && respJson.msg.indexOf('_success') >= 0){
						$Core.alert.success('Đã lưu!');
						$Core.popup.close($_form.closest(".modal"));
						window.location.reload();
					} else {
						$Core.alert.error('Lưu thất bại!');
					}
				}
			});
		}
		return false;
	},
	// ---- Scope (phạm vi áp dụng) ----
	add_scope: (_this, e) => {
		e.preventDefault();
		var stock_type = $(_this).attr('stock_type');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=add_scope', {
			'stock_type' : stock_type
		}, function(html){
			vietiso_loading(0);
			$('.group_scopes>.scope_item:last').after(html);
		});
		return false;
	},
	delete_scope: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid');
		$('.scope_item_'+uid).remove();
		return false;
	},
	load_option_block: (_this, e) => {
		var uid = $(_this).attr('uid'),
			toId = $(_this).attr('toId'),
			project_id = $(_this).val();
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_option_block', {
			'project_id' : project_id
		}, function(html){
			vietiso_loading(0);
			$('#'+toId).html(html).trigger("chosen:updated");
			$('#building_group_'+uid).addClass('d-none');
			$('#building_'+uid).val('').trigger("chosen:updated");
		});
	},
	load_option_building: (_this, e) => {
		var uid = $(_this).attr('uid'),
			toId = $(_this).attr('toId'),
			block_id = $(_this).val(),
			parent_id = $(_this).find('option:selected').attr('parent_id');
		if(parent_id == _BLOCK_TYPE_LOWFLOOR_SALE){
			$('#building_group_'+uid).addClass('d-none');
		} else {
			vietiso_loading(1);
			$('#building_group_'+uid).removeClass('d-none');
			$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_option_building', {
				'block_id' : block_id
			}, function(html){
				vietiso_loading(0);
				$('#'+toId).html(html).trigger("chosen:updated");
			});
		}
	},
	// ---- Phương án × Đợt thanh toán (ma trận - GĐ2) ----
	add_price_plan: (_this, e) => {
		e.preventDefault();
		var price_sheet_id = $(_this).attr('price_sheet_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_price_plan', {
			'price_sheet_id':price_sheet_id
		}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'open_price_plan');
		}, 'json');
		return false;
	},
	edit_price_plan: (_this, e) => {
		e.preventDefault();
		var price_sheet_id = $(_this).attr('price_sheet_id'),
			price_plan_id = $(_this).attr('price_plan_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_price_plan', {
			'price_sheet_id':price_sheet_id,
			'price_plan_id':price_plan_id
		}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'open_price_plan');
		}, 'json');
		return false;
	},
	pop_save_price_plan: (_this, e) => {
		e.preventDefault();
		var price_sheet_id = $(_this).attr('price_sheet_id'),
			price_plan_id = $(_this).attr('price_plan_id');
		var _validated = 0,
			$_form = $(_this).closest('form');
		if($('input.required', $_form).length){
			$('input.required', $_form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			vietiso_loading(1);
			$_form.ajaxSubmit({
				type:'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_price_plan',
				data: {"price_sheet_id":price_sheet_id, "price_plan_id":price_plan_id},
				dataType:'json',
				success: function(respJson){
					vietiso_loading(0);
					$Core.popup.close($_form.closest(".modal"));
					if(respJson.action === '_edit'){
						// Cập nhật tên tab tại chỗ
						$(`.tablinks_${price_sheet_id} a[href="#${respJson.price_plan_id}"]`).text(respJson.title || '');
					} else {
						if($(`.tablinks_${price_sheet_id} li.nav-item-${price_sheet_id}`).length){
							$(`.tablinks_${price_sheet_id} li.nav-item-${price_sheet_id}:last`).after(respJson.tablink);
							$(`.tabcontents_${price_sheet_id} .tab-pane:last`).after(respJson.tabcontent);
						} else {
							$(`.tablinks_${price_sheet_id} li.nav-item:last`).before(respJson.tablink);
							$(`.tabcontents_${price_sheet_id}`).html(respJson.tabcontent);
						}
						if(respJson.price_plan_id){
							$Core.price_sheets.init_sortable(price_sheet_id, respJson.price_plan_id);
							$Core.price_sheets.refresh_totals(price_sheet_id, respJson.price_plan_id);
						}
					}
				}
			});
		}
		return false;
	},
	delete_price_plan: (_this, e) => {
		e.preventDefault();
		if(!confirm('Xóa phương án này (gồm tất cả đợt bên trong)?')) return false;
		var price_sheet_id = $(_this).attr('price_sheet_id'),
			price_plan_id = $(_this).attr('price_plan_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete_price_plan', {
			'price_sheet_id':price_sheet_id,
			'price_plan_id':price_plan_id
		}, function(){
			vietiso_loading(0);
			$(`.tablinks_${price_sheet_id} a[href="#${price_plan_id}"]`).closest('li').remove();
			$('#'+price_plan_id).remove();
			var $firstTab = $(`.tablinks_${price_sheet_id} li.nav-item-${price_sheet_id}:first > a`);
			if($firstTab.length){ $firstTab.tab('show'); }
		});
		return false;
	},
	clone_price_plan: (_this, e) => {
		e.preventDefault();
		var price_sheet_id = $(_this).attr('price_sheet_id'),
			price_plan_id = $(_this).attr('price_plan_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=clone_price_plan', {
			'price_sheet_id':price_sheet_id,
			'price_plan_id':price_plan_id
		}, function(respJson){
			vietiso_loading(0);
			if(respJson.msg && respJson.msg.indexOf('_success') >= 0){
				$(`.tablinks_${price_sheet_id} li.nav-item-${price_sheet_id}:last`).after(respJson.tablink);
				$(`.tabcontents_${price_sheet_id} .tab-pane:last`).after(respJson.tabcontent);
				var $newTab = $(`.tablinks_${price_sheet_id} li.nav-item-${price_sheet_id}:last > a`);
				if($newTab.length){ $newTab.tab('show'); }
				if(respJson.price_plan_id){
					$Core.price_sheets.init_sortable(price_sheet_id, respJson.price_plan_id);
					$Core.price_sheets.refresh_totals(price_sheet_id, respJson.price_plan_id);
				}
			} else {
				$Core.alert.error('Nhân bản thất bại!');
			}
		}, 'json');
		return false;
	},
	load_tbl_options: (price_sheet_id, price_plan_id, options) => {
		var $_adata = options || {};
		$_adata['price_plan_id'] = price_plan_id;
		$_adata['price_sheet_id'] = price_sheet_id;
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=load_tbl_options', $_adata, function(respJson){
			vietiso_loading(0);
			$(`.price_sheets_${price_sheet_id}_${price_plan_id}`).html(respJson.html);
			$Core.price_sheets.init_sortable(price_sheet_id, price_plan_id);
			$Core.price_sheets.refresh_totals(price_sheet_id, price_plan_id);
			$Core.price_sheets.refresh_eta(price_sheet_id, price_plan_id);
		}, 'json');
	},
	open_option: (_this, e) => {
		e.preventDefault();
		var option_id = $(_this).attr('option_id'),
			stock_type = $(_this).attr('stock_type'),
			price_plan_id = $(_this).attr('price_plan_id'),
			price_sheet_id = $(_this).attr('price_sheet_id');
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=open_option', {
			'option_id':option_id,
			'stock_type' : stock_type,
			'price_plan_id' : price_plan_id,
			'price_sheet_id':price_sheet_id
		}, function(respJson){
			vietiso_loading(0);
			$Core.popup.open('auto', 'auto', respJson.html, 'open_option');
		}, 'json');
		return false;
	},
	pop_save_option: function(_this, e){
		e.preventDefault();
		var $btn = $(_this),
			stay = $btn.attr('data-stay') === '1',
			option_id = $btn.attr('option_id'),
			stock_type = $btn.attr('stock_type'),
			price_plan_id = $btn.attr('price_plan_id'),
			price_sheet_id = $btn.attr('price_sheet_id');
		var _validated = 0,
			$_form = $(_this).closest('form');
		if($('input.required', $_form).length){
			$('input.required', $_form).each((_i, _elem) => {
				if($Core.util.isEmpty($(_elem).val())){
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if(_validated==0){
			vietiso_loading(1);
			$_form.ajaxSubmit({
				type:'POST',
				url : path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_option',
				data: {
					"price_plan_id" : price_plan_id,
					"price_sheet_id":price_sheet_id,
					"stock_type":stock_type,
					'option_id':option_id
				},
				dataType:'html',
				success: function(html){
					vietiso_loading(0);
					$Core.alert.success('Đã lưu!');
					$Core.price_sheets.load_tbl_options(price_sheet_id, price_plan_id, {});
					if(stay){
						// Giữ modal mở để nhập đợt kế tiếp — reset về mặc định days/percent
						$('input[name="name"]', $_form).val('');
						$('input[name="payment_days"]', $_form).val('');
						$('input[name="fixed_date"]', $_form).val('');
						$('input[name="estimated_text"]', $_form).val('');
						$('input[name="payment_rate"]', $_form).val(0);
						$('input[name="payment_amount"]', $_form).val('');
						$('input[type="checkbox"][name="include_kpbt"]', $_form).prop('checked', false);
						$('input[name="tax_rate"]', $_form).val(0);
						$('input[name="date_mode"][value="days"]', $_form).prop('checked', true);
						$('input[name="amount_mode"][value="percent"]', $_form).prop('checked', true);
						$_form.find('.date-mode-group').hide();
						$_form.find('.date-mode-days').show();
						$_form.find('.amount-mode-group').hide();
						$_form.find('.amount-mode-percent').show();
						$('input[name="name"]', $_form).focus();
					} else {
						$Core.popup.close($_form.closest(".modal"));
					}
				}
			});
		}
		return false;
	},
	delete_option: (_this, e) => {
		e.preventDefault();
		if(!confirm('Xóa đợt thanh toán này?')) return false;
		var option_id = $(_this).attr('option_id'),
			price_plan_id = $(_this).attr('price_plan_id'),
			price_sheet_id = $(_this).attr('price_sheet_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=pop_save_option&action=_delete', {
			'option_id':option_id,
			'price_plan_id':price_plan_id,
			'price_sheet_id':price_sheet_id
		}, function(){
			vietiso_loading(0);
			$Core.price_sheets.load_tbl_options(price_sheet_id, price_plan_id, {});
		});
		return false;
	},
	clone_option: (_this, e) => {
		e.preventDefault();
		var option_id = $(_this).attr('option_id'),
			price_plan_id = $(_this).attr('price_plan_id'),
			price_sheet_id = $(_this).attr('price_sheet_id');
		vietiso_loading(1);
		$.post(path_ajax_script+'/index.php?mod='+mod+'&act=clone_option', {
			'option_id':option_id,
			'price_plan_id':price_plan_id,
			'price_sheet_id':price_sheet_id
		}, function(){
			vietiso_loading(0);
			$Core.price_sheets.load_tbl_options(price_sheet_id, price_plan_id, {});
		});
		return false;
	},
	refresh_eta: (price_sheet_id, price_plan_id) => {
		var $cocdate = $('#cocdate_'+price_sheet_id+'_'+price_plan_id);
		var $tbody = $('.price_sheets_'+price_sheet_id+'_'+price_plan_id);
		if(!$tbody.length) return;
		var dateStr = $cocdate.val();
		var anchor = null;
		if(dateStr){
			var parts = dateStr.split('-');
			if(parts.length === 3){
				anchor = new Date(parseInt(parts[0]), parseInt(parts[1])-1, parseInt(parts[2]));
			}
		}
		function fmt(d){
			var dd = String(d.getDate()).padStart(2,'0');
			var mm = String(d.getMonth()+1).padStart(2,'0');
			return dd+'/'+mm+'/'+d.getFullYear();
		}
		$tbody.find('tr[data-uid]').each(function(){
			var mode = $(this).attr('data-date-mode') || 'days';
			var $eta = $(this).find('.ptg-eta');
			if(mode === 'fixed'){
				var ts = parseInt($(this).attr('data-fixed-ts')) || 0;
				if(ts > 0){
					var d = new Date(ts*1000);
					anchor = d; // reset anchor cho các đợt 'days' kế tiếp
					$eta.text(fmt(d));
				} else { $eta.text('—'); }
			} else if(mode === 'estimated'){
				var txt = $(this).attr('data-estimated') || '';
				anchor = null; // không cộng dồn được nữa
				$eta.text(txt ? '(dự kiến) '+txt : '—');
			} else {
				if(anchor){
					var days = parseInt($(this).attr('data-days')) || 0;
					anchor = new Date(anchor.getTime() + days*86400000);
					$eta.text(fmt(anchor));
				} else { $eta.text('—'); }
			}
		});
	},
	refresh_totals: (price_sheet_id, price_plan_id) => {
		var $tbody = $('.price_sheets_'+price_sheet_id+'_'+price_plan_id);
		if(!$tbody.length) return;
		var totalRate = 0, totalAmount = 0, hasKpbt = false;
		$tbody.find('tr[data-uid]').each(function(){
			var mode = $(this).attr('data-amount-mode') || 'percent';
			if(mode === 'fixed'){
				totalAmount += parseInt($(this).attr('data-amount')) || 0;
			} else {
				totalRate += parseFloat($(this).attr('data-rate')) || 0;
			}
			if(parseInt($(this).attr('data-kpbt')) === 1) hasKpbt = true;
		});
		var $totalRow = $('.ptg-totals-'+price_sheet_id+'-'+price_plan_id);
		var totalDisp = (Math.round(totalRate*100)/100) + '%';
		var note = '', noteClass = 'text-muted';
		if(totalRate < 100){
			note = '(còn '+(Math.round((100-totalRate)*100)/100)+'%)';
			noteClass = 'text-danger';
		} else if(totalRate > 100){
			note = '(vượt '+(Math.round((totalRate-100)*100)/100)+'%)';
			noteClass = 'text-danger';
		} else {
			note = '✓ Đủ 100%';
			noteClass = 'text-success';
		}
		if(totalAmount > 0){
			note += ' + ' + totalAmount.toLocaleString('vi-VN') + ' đ cố định';
		}
		if(hasKpbt){
			note += ' + KPBT';
		}
		$totalRow.find('.ptg-total-rate').html('<strong>'+totalDisp+'</strong>');
		$totalRow.find('.ptg-total-note').removeClass('text-muted text-danger text-success').addClass(noteClass).text(note);
	},
	toggle_date_mode: function(radio){
		var mode = $(radio).val();
		var $form = $(radio).closest('form');
		$form.find('.date-mode-group').hide();
		$form.find('.date-mode-'+mode).show();
	},
	toggle_amount_mode: function(radio){
		var mode = $(radio).val();
		var $form = $(radio).closest('form');
		$form.find('.amount-mode-group').hide();
		$form.find('.amount-mode-'+mode).show();
	},
	init_sortable: (price_sheet_id, price_plan_id) => {
		var $tbody = $('.price_sheets_'+price_sheet_id+'_'+price_plan_id);
		if(!$tbody.length) return;
		try { if($tbody.data('uiSortable')){ $tbody.sortable('destroy'); } } catch(err) {}
		$tbody.sortable({
			handle: '.mySortableHandler',
			items: '> tr[data-uid]',
			axis: 'y',
			helper: function(e, tr){
				var $originals = tr.children();
				var $helper = tr.clone();
				$helper.children().each(function(idx){ $(this).width($originals.eq(idx).outerWidth()); });
				return $helper;
			},
			update: function(event, ui){
				var order = $(this).sortable('toArray');
				vietiso_loading(1);
				$.post(path_ajax_script+'/index.php?mod='+mod+'&act=reorder_options', {
					'price_sheet_id':price_sheet_id,
					'price_plan_id':price_plan_id,
					'order':order
				}, function(){
					vietiso_loading(0);
					$Core.price_sheets.load_tbl_options(price_sheet_id, price_plan_id, {});
				});
			}
		});
	},
	init_all: (price_sheet_id) => {
		$('.tabcontents_'+price_sheet_id+' > .tab-pane').each(function(){
			var price_plan_id = $(this).attr('id');
			if(!price_plan_id) return;
			$Core.price_sheets.init_sortable(price_sheet_id, price_plan_id);
			$Core.price_sheets.refresh_totals(price_sheet_id, price_plan_id);
		});
		// Gắn TinyMCE cho mọi textarea.isoTextArea trong modal PTG đang mở
		$('.modal textarea.isoTextArea').each(function(){
			var $ta = $(this), id = $ta.attr('id');
			if(typeof tinyMCE === 'undefined' || !tinyMCE.get(id)){
				try { $ta.isoTextArea(); } catch(err){}
			}
		});
	}
}
// Delegation backup: nếu inline onchange của radio không fire trong AJAX popup,
// vẫn bắt được change event ở cấp document.
$(function(){
	$(document).off('change.ptg_date_mode').on('change.ptg_date_mode', 'input[name="date_mode"]', function(){
		if($Core && $Core.price_sheets && $Core.price_sheets.toggle_date_mode){
			$Core.price_sheets.toggle_date_mode(this);
		}
	});
	$(document).off('change.ptg_amount_mode').on('change.ptg_amount_mode', 'input[name="amount_mode"]', function(){
		if($Core && $Core.price_sheets && $Core.price_sheets.toggle_amount_mode){
			$Core.price_sheets.toggle_amount_mode(this);
		}
	});
});
