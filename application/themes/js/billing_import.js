/*
 * Import giao dịch (billing) từ Google Sheet.
 * $Core.billingImport — mở modal, chọn sheet, map cột (config dùng chung),
 * Xem trước (dry-run, KHÔNG ghi) rồi mới Ghi vào hệ thống.
 * Server: mod=home&act=open_billing_import|get_billing_sheets|config_billing_import|save_billing_import_config|run_billing_import
 */
(function(){
	window.$Core = window.$Core || {};
	var MOD = 'home';

	function esc(v){
		if(v === null || v === undefined){ return ''; }
		return String(v).replace(/[&<>"']/g, function(c){
			return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
		});
	}

	function money(v){
		var n = parseFloat(v);
		if(isNaN(n)){ return esc(v); }
		return n.toLocaleString('vi-VN');
	}

	$Core.billingImport = {
		/** Mở modal import chính. */
		open: function(_this, e){
			if(e){ e.preventDefault(); }
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_billing_import', {}, function(resp){
				$Core.util.toggleIndicatior(0);
				if(resp.result === false){ $Core.alert.error(resp.msg); return; }
				$Core.popup.open('auto', 'auto', resp.html, resp.uid);
			}, 'json');
			return false;
		},

		/** Nạp danh sách tab khi nhập/đổi Spreadsheet ID. */
		loadSheets: function(_this, e){
			if(e){ e.preventDefault(); }
			var spreadsheetId = $(_this).val(), toId = $(_this).attr('toId');
			if($Core.util.isEmpty(spreadsheetId)){ return false; }
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=get_billing_sheets', {spreadsheetId: spreadsheetId}, function(resp){
				$Core.util.toggleIndicatior(0);
				if(!resp.result){ $Core.alert.error(resp.msg); }
				else { $('#' + toId).html(resp.html); }
			}, 'json');
			return false;
		},

		/** Mở modal cấu hình map cột. */
		openConfig: function(_this, e){
			if(e){ e.preventDefault(); }
			var _form = $(_this).closest('form'),
				spreadsheetId = $('input[name=spreadsheetId]', _form).val(),
				sheet_name = $('select[name=sheet_name]', _form).val(),
				header_row = $('input[name=header_row]', _form).val(),
				start_row = $('input[name=start_row]', _form).val();
			if($Core.util.isEmpty(spreadsheetId) || $Core.util.isEmpty(sheet_name)){
				$Core.swal.error('Thông báo', 'Nhập Spreadsheet ID và chọn sheet trước.');
				return false;
			}
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=config_billing_import', {
				spreadsheetId: spreadsheetId,
				sheet_name: sheet_name,
				header_row: header_row,
				start_row: start_row
			}, function(resp){
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto', 'auto', resp.html, resp.uid);
			}, 'json');
			return false;
		},

		/** Lưu cấu hình map cột (dùng chung). */
		saveConfig: function(_this, e){
			if(e){ e.preventDefault(); }
			var _form = $(_this).closest('form');
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + '/index.php?mod=' + MOD + '&act=save_billing_import_config',
				dataType: 'json',
				success: function(resp){
					$Core.util.toggleIndicatior(0);
					if(resp.result){
						alertify.success(resp.msg);
						$('.btn-close', _form).trigger('click');
					} else {
						alertify.error(resp.msg);
					}
				}
			});
			return false;
		},

		/** Xem trước (dry-run). */
		preview: function(_this, e){
			return $Core.billingImport._run(_this, e, 1);
		},

		/** Xác nhận rồi ghi thật. */
		confirmRun: function(_this, e){
			if(e){ e.preventDefault(); }
			if($(_this).hasClass('disabled')){ return false; }
			if(!window.confirm('Ghi các giao dịch hợp lệ vào hệ thống? Thao tác này ghi trực tiếp lên dữ liệu thật.')){
				return false;
			}
			return $Core.billingImport._run(_this, e, 0);
		},

		/** Gọi import. isPreview=1: chỉ xem trước; 0: ghi thật. */
		_run: function(_this, e, isPreview){
			if(e){ e.preventDefault(); }
			var _form = $(_this).closest('form');
			var _error = 0;
			$('select.required,input.required', _form).each(function(){
				if($Core.util.isEmpty($(this).val())){ $(this).addClass('is-invalid'); _error++; }
				else { $(this).removeClass('is-invalid'); }
			});
			if(_error > 0){
				$Core.alert.error('Nhập đủ Spreadsheet ID, sheet, dòng bắt đầu và loại hình giao dịch.');
				return false;
			}
			$('input[name=is_preview]', _form).val(isPreview);
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + '/index.php?mod=' + MOD + '&act=run_billing_import',
				dataType: 'json',
				success: function(resp){
					$Core.util.toggleIndicatior(0);
					if(resp.result === false){ $Core.alert.error(resp.msg); return; }
					$Core.billingImport.renderReport(_form, resp, isPreview);
				},
				error: function(){
					$Core.util.toggleIndicatior(0);
					$Core.alert.error('Có lỗi khi import. Thử lại.');
				}
			});
			return false;
		},

		/** Render báo cáo kết quả vào .billing-import-report trong modal. */
		renderReport: function(_form, resp, isPreview){
			var box = $('.billing-import-report', _form);
			var okLabel = isPreview ? 'Sẽ ghi' : 'Đã ghi';
			var head = isPreview ? 'Kết quả xem trước' : 'Kết quả import';
			var html = '';
			html += '<div class="alert ' + (resp.errors > 0 ? 'alert-warning' : 'alert-success') + ' py-2 mb-2">';
			html += '<strong>' + esc(head) + ':</strong> ' + resp.total + ' dòng — ';
			html += '<span class="text-success">' + esc(okLabel) + ': ' + resp.inserted + '</span> · ';
			html += '<span class="text-warning">Trùng (bỏ qua): ' + resp.skipped + '</span> · ';
			html += '<span class="text-danger">Lỗi: ' + resp.errors + '</span>';
			html += '</div>';
			html += '<div class="table-responsive" style="max-height:320px;overflow:auto">';
			html += '<table class="table table-sm table-bordered mb-0"><thead><tr class="bg-lighter">';
			html += '<th>Dòng</th><th>Trạng thái</th><th>Nhân viên</th><th>Mã căn</th><th>Ngày PS</th><th class="text-end">Số tiền</th><th>Ghi chú</th>';
			html += '</tr></thead><tbody>';
			var rows = resp.rows || [];
			for(var i = 0; i < rows.length; i++){
				var r = rows[i], p = r.preview || {};
				var badge = 'bg-label-success', label = okLabel;
				if(r.status === 'skip'){ badge = 'bg-label-warning'; label = 'Trùng'; }
				else if(r.status === 'error'){ badge = 'bg-label-danger'; label = 'Lỗi'; }
				var note = r.reason ? esc(r.reason) : '';
				if(r.warnings && r.warnings.length){
					note += (note ? '<br>' : '') + '<span class="text-warning">' + esc(r.warnings.join('; ')) + '</span>';
				}
				html += '<tr>';
				html += '<td>' + esc(r.line) + '</td>';
				html += '<td><span class="badge ' + badge + '">' + esc(label) + '</span></td>';
				html += '<td>' + esc(p.staff) + '</td>';
				html += '<td>' + esc(p.stock_code) + '</td>';
				html += '<td>' + esc(p.deposit_date) + '</td>';
				html += '<td class="text-end">' + money(p.totalgrand) + '</td>';
				html += '<td><small>' + note + '</small></td>';
				html += '</tr>';
			}
			html += '</tbody></table></div>';
			box.html(html);
			// Bật nút ghi thật khi xem trước có dòng hợp lệ
			var runBtn = $('.bi-btn-run', _form);
			if(isPreview && resp.inserted > 0){
				runBtn.removeClass('disabled').prop('disabled', false);
			} else if(!isPreview){
				runBtn.addClass('disabled').prop('disabled', true);
				if(resp.inserted > 0){
					box.append('<button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="location.reload()"><i class="bx bx-refresh me-1"></i> Tải lại danh sách</button>');
				}
			}
		}
	};

	/*
	 * Tỷ lệ chi phí công ty: Ban lãnh đạo + quỹ Back Office, trong quỹ chia tiếp cho các phòng.
	 * $Core.backofficeRate — chỉ Admin trưởng mở được (gate ở server).
	 * Server: mod=home&act=open_backoffice_rate|save_backoffice_rate
	 */
	$Core.backofficeRate = {
		/** Mở popup cấu hình. */
		open: function(_this, e){
			if(e){ e.preventDefault(); }
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_backoffice_rate', {}, function(resp){
				$Core.util.toggleIndicatior(0);
				if(resp.result === false){ $Core.alert.error(resp.msg); return; }
				$Core.popup.open('auto', 'auto', resp.html, resp.uid);
				setTimeout(function(){ $Core.backofficeRate._bind(resp.uid); }, 120);
			}, 'json');
			return false;
		},

		/** Cộng lại tỷ lệ các phòng của MỘT quỹ, tô đỏ khi khác 100% — chia hụt hay vượt quỹ. */
		_sum: function($f){
			$('.js__bo-sum', $f).each(function(){
				var $o = $(this);
				var key = $o.attr('data-key');
				var t = 0;
				$('.js__bo-sub[data-key="' + key + '"]', $f).each(function(){
					t += parseFloat(String($(this).val() || '').replace(',', '.')) || 0;
				});
				// Làm tròn 2 số: 3 phòng chia đều ra 33,33x3 = 99,99 chứ không tròn 100
				t = Math.round(t * 100) / 100;
				$o.text(String(t).replace('.', ','));
				$o.closest('span').toggleClass('text-danger fw-bold', Math.abs(t - 100) > 0.01);
			});
		},

		/** Gắn sự kiện cho ĐÚNG popup vừa mở — popup cũ chỉ bị ẩn chứ không gỡ khỏi DOM. */
		_bind: function(uid){
			var $f = $('#' + uid).find('form').first();
			if(!$f.length){ return; }
			$f.off('input.bo').on('input.bo', '.js__bo-sub', function(){
				$Core.backofficeRate._sum($f);
			});
			$Core.backofficeRate._sum($f);
		},

		/**
		 * Đẩy tỷ lệ vừa lưu sang màn quyết toán ĐANG MỞ rồi tính lại tiền.
		 *
		 * Đổi cấu hình mà tiền trên form đứng im thì người dùng bấm lưu quyết toán bằng số cũ —
		 * số nhìn thấy một đằng, số server ghi một nẻo. Popup này còn mở được từ menu bánh răng
		 * lúc không có modal quyết toán nào, nên phải chịu được cả trường hợp rỗng.
		 */
		_apply: function(rows){
			if(!rows || !rows.length){ return; }
			var $f = $('.js__st-bo').closest('form');
			if(!$f.length){ return; }
			$.each(rows, function(i, r){
				$('.js__st-bo[data-key="' + r.key + '"]', $f)
					.attr('data-rate', r.rate).attr('data-parent', r.rate_parent);
				$('.js__st-bo-rate[data-key="' + r.key + '"]', $f).text(String(r.rate).replace('.', ','));
			});
			$Core.billingSettlement.recalc($f);
		},

		/** Lưu cấu hình. Server tự chặn tổng khác 100%, chỗ này chỉ báo sớm cho đỡ mất công gửi. */
		save: function(_this, e){
			if(e){ e.preventDefault(); }
			var _form = $(_this).closest('form');
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + '/index.php?mod=' + MOD + '&act=save_backoffice_rate',
				dataType: 'json',
				success: function(resp){
					$Core.util.toggleIndicatior(0);
					if(resp.result){
						alertify.success(resp.msg);
						$Core.backofficeRate._apply(resp.rows);
						$('.btn-close', _form).trigger('click');
					} else {
						alertify.error(resp.msg);
					}
				},
				error: function(){
					$Core.util.toggleIndicatior(0);
					$Core.alert.error('Có lỗi khi lưu. Thử lại.');
				}
			});
			return false;
		}
	};

	/*
	 * Quản lý tỷ lệ hoa hồng bậc thang theo vị trí (TPKD, Giám đốc, CV/TP/GĐ PTĐT).
	 * $Core.commissionTier — chỉ Admin trưởng mở được (gate ở server).
	 * Server: mod=home&act=open_commission_tier|save_commission_tier
	 */
	$Core.commissionTier = {
		/** Mở popup cấu hình. */
		open: function(_this, e){
			if(e){ e.preventDefault(); }
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_commission_tier', {}, function(resp){
				$Core.util.toggleIndicatior(0);
				if(resp.result === false){ $Core.alert.error(resp.msg); return; }
				$Core.popup.open('auto', 'auto', resp.html, resp.uid);
				setTimeout($Core.commissionTier._initPrice, 120);
			}, 'json');
			return false;
		},

		/** Format phân tách nghìn cho ô mốc doanh số. */
		_initPrice: function(){
			if($.fn.priceFormat){
				$('.js__tier-row .price-In:not(.priceFormat)').priceFormat({thousandsSeparator: '.', clearOnEmpty: true, centsLimit: ''});
			}
		},

		/** Thêm 1 bậc mới cho vị trí. Bậc cuối còn trống thì focus vào đó, không đẻ thêm dòng rỗng. */
		addRow: function(_this, e, role){
			if(e){ e.preventDefault(); }
			var rows = $('[data-role="' + role + '"]');
			var last = $('.js__tier-row:last input.price-In', rows);
			if(last.length && $Core.util.isEmpty(last.val())){
				last.focus();
				return false;
			}
			var idx = 'n' + (new Date().getTime());
			var html = '<div class="d-flex align-items-center gap-2 mb-2 js__tier-row">'
				+ '<div class="input-group">'
				+ '<input type="text" class="form-control text-end price-In numberonly" name="tiers[' + role + '][' + idx + '][min]" value="">'
				+ '<span class="input-group-text">đ</span></div>'
				+ '<div class="input-group w-px-125 flex-grow-0">'
				+ '<input type="text" class="form-control text-end" name="tiers[' + role + '][' + idx + '][rate]" value="">'
				+ '<span class="input-group-text">%</span></div>'
				+ '<button type="button" class="btn btn-icon btn-outline-danger flex-shrink-0" title="Xoá bậc" onclick="$Core.commissionTier.removeRow(this,event)"><i class="bx bx-trash"></i></button>'
				+ '</div>';
			rows.append(html);
			$Core.commissionTier._initPrice();
			$('.js__tier-row:last input.price-In', rows).focus();
			return false;
		},

		/** Xoá 1 bậc. */
		removeRow: function(_this, e){
			if(e){ e.preventDefault(); }
			$(_this).closest('.js__tier-row').remove();
			return false;
		},

		/** Lưu cấu hình. */
		save: function(_this, e){
			if(e){ e.preventDefault(); }
			var _form = $(_this).closest('form');
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + '/index.php?mod=' + MOD + '&act=save_commission_tier',
				dataType: 'json',
				success: function(resp){
					$Core.util.toggleIndicatior(0);
					if(resp.result){
						alertify.success(resp.msg);
						$('.btn-close', _form).trigger('click');
					} else {
						alertify.error(resp.msg);
					}
				},
				error: function(){
					$Core.util.toggleIndicatior(0);
					$Core.alert.error('Có lỗi khi lưu. Thử lại.');
				}
			});
			return false;
		}
	};

	/**
	 * $Core.billingSettlement — modal Quyết toán 1 giao dịch.
	 * Server: mod=home&act=open_billing_settlement|save_billing_settlement
	 *
	 * Số hiện live ở đây CHỈ để xem trước; server luôn tính lại bằng BillingCalc rồi mới lưu.
	 * Công thức dưới đây phải soi gương models/BillingCalc.php — sửa một bên thì sửa cả hai.
	 */
	$Core.billingSettlement = {

		/** Đọc ô tiền: bỏ dấu phân cách nghìn rồi ép nguyên. */
		_money: function($f, sel){
			// sel có thể là selector hoặc chính ô đã tìm được — $(jQueryObj, ctx) trả về rỗng
			var v = (sel && sel.jquery) ? sel.val() : $(sel, $f).val();
			if(!v){ return 0; }
			v = String(v).replace(/[₫\s;()]/g, '').replace(/,/g, '.').replace(/\./g, '');
			var n = parseInt(v, 10);
			return isNaN(n) ? 0 : n;
		},

		/** Đọc ô phần trăm: GIỮ phần thập phân (3.6 là 3,6% chứ không phải 36%). */
		_pct: function($f, sel, def){
			var v = $(sel, $f).val();
			if(v === undefined || v === null || String(v).trim() === ''){ return def; }
			v = String(v).replace(/[₫%\s;()]/g, '').replace(/,/g, '.');
			var n = parseFloat(v);
			return isNaN(n) ? def : n;
		},

		_fmt: function(n){
			return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		},

		/**
		 * Chèn dấu phân cách nghìn cho các ô tiền "để trống = tự tính".
		 *
		 * KHÔNG dùng plugin price-In cho nhóm này: nó ghi "0" vào ô rỗng lúc focusout,
		 * biến "để trống = tự tính" thành "đè bằng 0" — tức trả 0đ cho cả một vai trò.
		 * Ở đây ô rỗng được giữ NGUYÊN rỗng.
		 */
		_fmtInput: function($o){
			var raw = String($o.val() || '');
			if(raw.trim() === ''){ return; }
			var so = raw.replace(/[^\d]/g, '');
			if(so === ''){ $o.val(''); return; }
			var moi = so.replace(/^0+(?=\d)/, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
			if(moi === $o.val()){ return; }
			// Giữ con trỏ theo SỐ CHỮ SỐ bên phải, không theo vị trí ký tự: chèn một dấu chấm
			// làm mọi vị trí sau nó dịch đi, gõ giữa số sẽ bị nhảy con trỏ về sai chỗ.
			var el = $o[0];
			var conlai = el && el.selectionStart !== undefined
				? raw.slice(el.selectionStart).replace(/[^\d]/g, '').length : -1;
			$o.val(moi);
			if(conlai >= 0){
				var i = moi.length, d = 0;
				while(i > 0 && d < conlai){ i--; if(/\d/.test(moi.charAt(i))){ d++; } }
				el.setSelectionRange(i, i);
			}
		},

		/**
		 * Điền lại một ô "tự tính" — dùng chung cho MỌI ô tiền trên màn này.
		 *
		 * Ô nào người dùng đã gõ (data-typed=1) thì KHÔNG đụng vào — đó là cả điểm của việc
		 * cho sửa. Ô chưa đụng tới thì bám theo doanh số, đúng như phía server tính lại.
		 */
		_auto: function($o, tien){
			if($o.attr('data-typed') === '1'){
				return $Core.billingSettlement._money(null, $o);
			}
			$o.val($Core.billingSettlement._fmt(tien));
			return Math.round(tien);
		},

		/**
		 * Tính lại khối chi phí công ty theo tổng doanh số hiện tại.
		 *
		 * Ô nào người dùng đã gõ đè (data-typed=1) thì KHÔNG đụng vào — đó là cả điểm của
		 * việc cho sửa. Ô chưa đụng tới thì bám theo doanh số, đúng như phía server tính.
		 */
		_boRecalc: function($f, totalAG){
			var st = $Core.billingSettlement;
			var tong = 0;
			$('.js__st-bo', $f).each(function(){
				var $o = $(this);
				var rate = parseFloat($o.attr('data-rate')) || 0;
				var parent = parseFloat($o.attr('data-parent')) || 0;
				// Tầng 2 thì gốc là QUỸ ĐÃ LÀM TRÒN, không phải doanh số — phải tròn đúng hai lần
				// như BillingCalc::backOfficeRows(), gộp một phép nhân là lệch 1đ so với sổ.
				var goc = parent > 0 ? Math.round(totalAG * parent / 100) : totalAG;
				tong += st._auto($o, goc * rate / 100);
			});
			$('.js__st-bo-total', $f).text(st._fmt(tong));
		},

		/**
		 * Tính lại toàn bộ số dẫn xuất mỗi khi người dùng gõ.
		 * $f là form CỦA CHÍNH modal đang thao tác — popup cũ chỉ bị ẩn chứ không gỡ khỏi DOM,
		 * nên bám selector toàn cục sẽ vớ phải form của giao dịch mở trước đó.
		 */
		recalc: function($f){
			if(!$f || !$f.length){ return; }
			var st = $Core.billingSettlement;
			var R = st._money($f, '[name="_r_base"]');
			var T = st._pct($f, '[name="_t_rate"]', 0);
			var AB = st._money($f, '[name="total_deduction"]');
			var AC = st._pct($f, '[name="total_deduction_percent_sales"]', 0);
			var AE = st._money($f, '[name="total_deduction_company"]');
			// "Sales chịu" (AD) cũng là ô tự tính cho sửa đè — cùng một luật với mọi ô tiền khác:
			// chưa gõ thì bám AB×AC, gõ rồi thì giữ nguyên số người nhập.
			var AD = st._auto($('[name="total_deduction_sales"]', $f), AB * AC / 100);
			var AF = AB - AD - AE;

			// AF sua duoc: da go thi giu nguyen, va AF do phai thay the so tinh de moi so duoi
			// dung theo. Ghi de vo dieu kien nhu truoc thi nguoi dung go xong bi xoa ngay.
			AF = st._auto($('.js__st-out-af', $f), AF);

			// Mọi số hoa hồng đều PER-SALE: mỗi dòng có tỷ lệ chia VÀ % hoa hồng riêng.
			// Tổng cả căn phải CỘNG các dòng, không được tính lại ở tỷ lệ 100% — vì AI khác nhau từng người.
			var totalAG = 0, totalAJ = 0, totalAL = 0, totalAM = 0, over = 0;
			$f.find('tbody tr[data-ratio]').each(function(){
				var $tr = $(this);
				var r = (parseFloat($tr.attr('data-ratio')) || 0) / 100;
				// Dùng ĐÚNG số trên ô, không thay 0 bằng mặc định — soi gương BillingCalc::compute().
				// 0 là con số thật: căn bán qua đối tác F2 hoặc CTV thì sale nội bộ ăn 0đ.
				var ai = st._pct($tr, '.js__st-row-ai', 0);
				// Gõ nhầm % hoa hồng thổi tiền lên nhiều lần mà không có gì báo — tô đỏ ngay ô đó
				if(ai > 100){ over++; }
				$('.js__st-row-ai', $tr).toggleClass('is-invalid', ai > 100);
				var ag = (R * r * T / 100) - (AF * r);
				var aj = ag * ai / 100;
				var al = AD * r;
				totalAG += Math.round(ag);
				totalAJ += Math.round(aj);
				totalAL += Math.round(al);
				totalAM += Math.round(aj - al);
				$('.js__st-row-ag', $tr).text(st._fmt(ag));
				$('.js__st-row-aj', $tr).text(st._fmt(aj));
				$('.js__st-row-al', $tr).text(st._fmt(al));
				$('.js__st-row-am', $tr).text(st._fmt(aj - al));

				// CTV ăn trên AG của CHÍNH dòng này (khác đại lý ăn trên R cả căn).
				var sid = $tr.attr('data-sid');
				if(sid){
					var $amt = $('.js__st-ctv-amount[data-sid="' + sid + '"]', $f);
					var crate = st._pct($f, '[name="share[' + sid + '][ctv_rate]"]', 0);
					st._auto($amt, ag * crate / 100);
					$('.js__st-ctv-hint[data-sid="' + sid + '"]', $f).text('');
				}
			});
			$('.js__st-out-ag-total', $f).text(st._fmt(totalAG));
			$('.js__st-out-aj', $f).text(st._fmt(totalAJ));
			$('.js__st-out-al', $f).text(st._fmt(totalAL));
			$('.js__st-out-am', $f).text(st._fmt(totalAM));
			$('.js__st-warn-ai', $f).toggleClass('d-none', over === 0);
				// Vai trò cấp giao dịch (PTĐT / GĐ dự án / TP GDDA): số tiền TỰ TÍNH = tổng realized (AG) × %,
				// soi gương server BillingCalc::roleAmount($total_realized, rate). Gõ đè (data-typed) thì giữ nguyên.
				['channel_exec', 'channel_manager', 'channel_director', 'project_director', 'project_manager'].forEach(function(pre){
					var rr = st._pct($f, '[name="' + pre + '_rate"]', 0);
					st._auto($('[name="' + pre + '_amount"]', $f), Math.round(totalAG * rr / 100));
				});

			// Chi phí công ty ăn trên TỔNG doanh số cả căn, nên phải chạy lại mỗi lần giảm trừ đổi —
			// trước đây số này render từ server nên sửa giảm trừ xong nó vẫn đứng im ở số cũ.
			// data-eff đã quy hai tầng về % của AH nên ở đây chỉ còn một phép nhân.
			// GD có dòng sale thì totalAG (tổng AG các dòng) đã là AH cả căn. GD KHÔNG có dòng sale
			// (F2 bán thẳng) thì totalAG = 0 nhưng công ty vẫn ăn trên doanh số sau giảm trừ của căn
			// ⇒ tính AH cả căn = R×T/100 − AF. commission_value do admin nhập; chưa nhập (=0) thì để 0,
			// KHÔNG lấy giá bán thay thế (R có thể đã fallback về giá bán nên phải soi cval thô).
			var boBase = totalAG;
			if($f.find('tbody tr[data-ratio]').length === 0){
				// cval THÔ để phân biệt "admin chưa nhập giá tính HH" (=0 ⇒ back office 0) với số thật.
				// Field vắng (template chưa cập nhật) thì lùi về R (đã fallback) để GD cval>0 vẫn chạy.
				var $cval = $('[name="_cval_raw"]', $f);
				var cvalRaw = $cval.length ? st._money($f, '[name="_cval_raw"]') : R;
				boBase = cvalRaw > 0 ? Math.round(R * T / 100 - AF) : 0;
			}
			st._boRecalc($f, boBase);

			// Đại lý ăn trên R của CẢ CĂN, khác CTV ăn trên AG từng dòng.
			var arate = st._pct($f, '[name="agency_rate"]', 0);
			st._auto($('.js__st-agency-amount', $f), R * arate / 100);
			$('.js__st-agency-hint', $f).text('');
		},

		/** Gắn sự kiện cho ĐÚNG modal vừa mở, tìm theo uid mà server trả về. */
		_bind: function(uid){
			var $f = $('#' + uid).find('form').first();
			if(!$f.length){ return; }
			if($.fn.priceFormat){
				$('.price-In:not(.priceFormat)', $f).priceFormat({thousandsSeparator: '.', clearOnEmpty: true, centsLimit: ''});
			}
			// Số server trả xuống đã có dấu chấm sẵn; chỗ này lo phần người dùng gõ thêm.
			$f.off('input.stamt').on('input.stamt', '.js__st-amt', function(){
				$Core.billingSettlement._fmtInput($(this));
			});
			// Gõ vào bất kỳ ô tiền nào = chốt số đó, recalc thôi bám theo doanh số nữa.
			$f.off('input.stbo').on('input.stbo', '.js__st-amt', function(){
				$(this).attr('data-typed', '1');
			});
			$f.off('keyup.st change.st').on('keyup.st change.st', 'input', function(){
				$Core.billingSettlement.recalc($f);
			});
			$Core.billingSettlement.recalc($f);
		},

		/** Mở modal Quyết toán của 1 giao dịch. */
		open: function(_this, e){
			if(e){ e.preventDefault(); }
			var billing_id = $(_this).attr('billing_id');
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_billing_settlement', {billing_id: billing_id}, function(resp){
				$Core.util.toggleIndicatior(0);
				if(resp.result === false){ $Core.alert.error(resp.msg); return; }
				$Core.popup.open('auto', 'auto', resp.html, resp.uid);
				setTimeout(function(){ $Core.billingSettlement._bind(resp.uid); }, 150);
			}, 'json');
			return false;
		},

		/** Lưu. Chỉ gửi ô người dùng nhập; số dẫn xuất để server tự tính lại. */
		save: function(_this, e){
			if(e){ e.preventDefault(); }
			// Bám form chứa chính nút vừa bấm — không dùng selector toàn cục, tránh gửi nhầm
			// billing_id của modal mở trước đó (popup cũ chỉ bị ẩn, vẫn còn trong DOM)
			var $f = $(_this).closest('form');
			if(!$f.length){ return false; }
			$Core.util.toggleIndicatior(1);
			$.ajax({
				type: 'POST',
				url: PCMS_URL + '/index.php?mod=' + MOD + '&act=save_billing_settlement',
				data: $f.serialize(),
				dataType: 'json',
				success: function(resp){
					$Core.util.toggleIndicatior(0);
					if(resp.result){
						$Core.swal.success('Thông báo', 'Đã lưu quyết toán.');
						setTimeout(function(){ window.location.reload(); }, 900);
					} else {
						$Core.alert.error(resp.msg || 'Lưu quyết toán thất bại.');
					}
				},
				error: function(){
					$Core.util.toggleIndicatior(0);
					$Core.alert.error('Có lỗi khi lưu. Thử lại.');
				}
			});
			return false;
		}
	};
})();
