$(function () {
	"use strict";
	// Áp chế độ chip đã lưu (mặc định Tình trạng). Nếu lần trước chọn Tác nghiệp thì swap TRƯỚC init để owl tính đúng group đang hiện.
	try {
		if (localStorage.getItem('crm_chip_mode') === 'task') {
			$('.js__crm-chip-mode').removeClass('active').filter('[data-mode="task"]').addClass('active');
			$('.js__crm-chips').addClass('d-none');
			$('.js__crm-chips-task').removeClass('d-none');
		}
	} catch (e) {}
	$Core.crm.init();
	var _crmAjaxInitTimer; // U-P2c: debounce re-init giảm churn (nhiều AJAX song song → init 1 lần thay vì mỗi call)
	$_document.ajaxComplete(() => {
		clearTimeout(_crmAjaxInitTimer);
		_crmAjaxInitTimer = setTimeout(() => { $Core.crm.init(); }, 150);
	});
	$('.dropdown-crm-search').on('show.bs.dropdown', () => {
		setTimeout(() => {
			$('.search_crm_field').focus();
		}, 500);
	});
	/* Router */
	/* $_document.on('click', '.goLink', function(){
		var $_this = $(this);
		if($_this.hasAttr('route')){
			$Core.util.popstate('/crm/#'+$_this.attr("route"));
		}else{
			$Core.util.dopopstate();
		}
	}); */;
	$_document.on('keyup', '.search_crm_field', (ev) => {
		var _keyCode = ev.keyCode || ev.which;
		if (_keyCode == 13) {
			var holderG = $(this).attr('holderG'),
				topM = $('.' + 'holder_customer').offset().top;
			$Core.crm.load_customers(holderG, {});
			$('html,body').animate({ scrollTop: topM }, 500);
			ev.preventDefault();
			return false;
		}
	});
	$_document.on('click', '.js__crm-quick-chip', function (e) {
		e.preventDefault();
		$(this).closest('.crm-quick-chip-group').find('.js__crm-quick-chip').removeClass('active');
		$(this).addClass('active');
		var $chip = $(this), _isStat = $chip.is('[data-status-id]'); $('.search_crm_status_field').val(_isStat ? (parseInt($chip.attr('data-status-id'), 10) || 0) : 0).trigger('change.select2'); var _task_id = _isStat ? 0 : ($chip.attr('data-task-id') || 0); // chip Tình trạng (status_id) / Tác nghiệp (task_id)
		$('.js__crm-task-filter').val(_task_id);
		$('.js__crm-hot-filter').val(0); $('.js__crm-hot-toggle').removeClass('active'); // F2: chon chip tac nghiep -> tat Hot
		$('input[name=tab][value=owner]').prop('checked', true); // chip = pipeline KH minh phu trach -> tab owner
		$Core.crm.load_customers('_desktop', { page: 1 });
	});
	// Đổi chế độ chip nhanh: Tình trạng ⇄ Tác nghiệp (mặc định Tình trạng, nhớ qua localStorage).
		$_document.on('click', '.js__crm-chip-mode', function (e) {
			e.preventDefault();
			if ($(this).hasClass('active')) { return; } // đã ở chế độ này → bỏ qua
			var mode = $(this).attr('data-mode') || 'status';
			$('.js__crm-chip-mode').removeClass('active');
			$('.js__crm-chip-mode[data-mode="' + mode + '"]').addClass('active');
			try { localStorage.setItem('crm_chip_mode', mode); } catch (e2) {}
			$('.js__crm-chips').addClass('d-none');
			$('.js__crm-chips-' + mode).removeClass('d-none');
			$Core.crm.init_quick_chip_carousel(); // init owl cho group vừa hiện (lười, idempotent)
			// reset về "Tất cả" + bỏ cả 2 bộ lọc + tab owner → tải lại danh sách
			var $g = $('.js__crm-chips-' + mode);
			$g.find('.js__crm-quick-chip').removeClass('active');
			$g.find('.js__crm-quick-chip').first().addClass('active');
			$('.js__crm-task-filter').val(0);
			$('.search_crm_status_field').val(0).trigger('change.select2');
			$('.js__crm-hot-filter').val(0); $('.js__crm-hot-toggle').removeClass('active');
			$('input[name=tab][value=owner]').prop('checked', true);
			$Core.crm.load_customers('_desktop', { page: 1 });
		});
		$_document.on('click', '.js__crm-sla-toggle', function (e) {
			e.preventDefault();
			var $c = $(this).closest('.card');
		$c.find('.crm-sla-board').toggleClass('d-none');
		$(this).find('.js__crm-sla-caret').toggleClass('bx-chevron-down bx-chevron-up');
	});
	// F4/F7 - chen mau noi dung (kich ban goi / mau tin Zalo) vao o Noi dung
	$_document.on('change', '.js__crm-tpl-insert', function () {
		var intro = $(this).find('option:selected').attr('data-intro') || '';
		if (!intro) { return; }
		var $ta = $(this).closest('form').find('textarea[name=intro]').first();
		if (!$ta.length) { return; }
		var cur = $.trim($ta.val());
		$ta.val(cur ? (cur + '\n' + intro) : intro).trigger('input').trigger('change').focus();
		$(this).val('');
	});
	$_document.on('click', '.js__crm-quick-chip-prev', function (e) {
		e.preventDefault();
		const $carousel = $(this).closest('.crm-top-quick-filter__chips-wrap').find('.js__crm-quick-chip-carousel');
		if ($carousel.length && $carousel.hasClass('owl-loaded')) {
			$carousel.trigger('prev.owl.carousel');
		}
	});
	$_document.on('click', '.js__crm-quick-chip-next', function (e) {
		e.preventDefault();
		const $carousel = $(this).closest('.crm-top-quick-filter__chips-wrap').find('.js__crm-quick-chip-carousel');
		if ($carousel.length && $carousel.hasClass('owl-loaded')) {
			$carousel.trigger('next.owl.carousel');
		}
	});
	/* Router */
	$.route("/calendar", () => {
		$('.js__open-calendar').trigger('click');
	});
	$.route("/campaigns", () => {
		$('.js__open-campain').trigger('click');
	});
	$.route("/customer/list/:status_id", (data, params) => {
		var status_id = params.status_id;
		$('.js__page-customer').filter("[status_id='" + status_id + "']").trigger("click");
		$('.js__page-customer').filter("[status_id='" + status_id + "']").parent().addClass('active');
	});
	$.route("/customer/create/:customer_id", (data, params) => {
		$('.js__add-customer').trigger('click');
	});
	$.route("/settings", () => {
		$('.js__open-setting').trigger('click');
	});
	$.route("/import", () => {
		$('.js__open-import').trigger('click');
	});
	$.route("/statistic", () => {
		$('.js__open-statistic').trigger('click');
	});
	$.route("/customer/:customer_id/overview", (data, params) => {
		var customer_id = params.customer_id;
		if (typeof (customer_id) !== 'undefined' && parseInt(customer_id) > 0) {
			$Core.crm.view_customer(customer_id, '', true);
		}
	});
	$.route("/activity/:customer_id", (data, params) => {
		var customer_id = params.customer_id;
		if (typeof (customer_id) !== 'undefined' && parseInt(customer_id) > 0) {
			$Core.crm.view_activity_gas(customer_id, {});
		}
	});
	// MyTeam period pill handler (event delegation — works after box re-render)
	$_document.on('click', '.js__mt-period', function (e) {
		e.preventDefault();
		$('.js__mt-period').removeClass('active');
		$(this).addClass('active');
		$('.js__mt-box').removeClass('loaded').html($Core.crm.mt_skel);
		$Core.crm.crm_autoload();
	});
	// MyTeam daterange go handler
	$_document.on('click', '.js__mt-daterange-go', function (e) {
		e.preventDefault();
		$('.js__mt-period').removeClass('active');
		$('.js__mt-box').removeClass('loaded').html($Core.crm.mt_skel);
		$Core.crm.crm_autoload();
	});
	// MyTeam dept select handler — đổi vùng/phòng KD → reload toàn bộ box theo scope mới
	$_document.on('change', '.js__mt-dept', function (e) {
		$('.js__mt-box').removeClass('loaded').html($Core.crm.mt_skel);
		$Core.crm.crm_autoload();
	});
	// MyTeam rep-table "xem thêm": bỏ ẩn các dòng nhân viên 11+ rồi gỡ nút (event delegation — box load AJAX)
	$_document.on('click', '.js__mt-rep-showmore', function (e) {
		e.preventDefault();
		$(this).closest('.crm-ld-panel').find('tr.js__mt-rep-more').removeClass('d-none');
		$(this).closest('.js__mt-rep-more-wrap').remove();
	});
	// MyTeam: sort bảng hiệu suất theo cột (client-side, click vào header). 1 click=giảm dần, click lại=tăng dần.
	$_document.on('click', '.js__mt-th-sort', function (e) {
		e.preventDefault();
		var $th = $(this);
		var dir = $th.hasClass('desc') ? 'asc' : 'desc';
		$th.closest('tr').find('.js__mt-th-sort').removeClass('asc desc');
		$th.addClass(dir);
		mtSortTeamTable($th.closest('table'), $th.index(), dir);
	});
	// MyTeam: click con số tổng → modal liệt kê khách theo điều kiện ô đó
	$_document.on('click', '.js__mt-drill', function (e) {
		e.preventDefault();
		e.stopPropagation();
		$Core.crm.mt_drill(this);
	});
	$_document.on('click', '.js__mt-drill-more', function (e) {
		e.preventDefault();
		$Core.crm.mt_drill_more(this);
	});
	$.router.init();
	/* End Router */
});
$Core.crm = $.extend($Core.global.crm, {
	scrolled: !1,
	init: () => {
		if ($(".tags:not(.tagged)").length) {
			$(".tags:not(.tagged)").each((_i, _elem) => {
				var _values = {};
				$.getJSON(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_tags', {}, function (data) {
					$(_elem).addClass('tagged').inputTags({
						autocomplete: {
							values: data,
							only: false
						}, create: function () {
							console.log('Tag added !');
						}
					});
				});
			});
		}
		_autoload();
		$Core.crm.init_quick_chip_carousel();
		$Core.crm.bind_fixed_customer_table();
		$Core.crm.mktpool_init();
		$Core.crm.crm_autoload();
	}, mktpool_init: () => {
		// Sale thị trường → CRM (sale_marketplace_crm.tpl): chọn nhiều dòng + thanh hành động nổi.
		// Idempotent: init() chạy lại sau mỗi ajaxComplete nên off/on theo namespace, no-op khi không ở màn này.
		var $pool = $('.crm-mktpool');
		if (!$pool.length) { return; }
		var _refresh = () => {
			var n = $pool.find('.mktpool-check:checked').length;
			$('#mktpool-count').text(n);
			$('#mktpool-bulk').toggleClass('d-none', n === 0);
		};
		$pool.off('change.mktpool').on('change.mktpool', '#mktpool-all', function () {
			$pool.find('.mktpool-check:not(:disabled)').prop('checked', this.checked);
			_refresh();
		}).on('change.mktpool', '.mktpool-check', _refresh);
		_refresh();
	}, mktpool_load: (page) => {
		// Nạp danh sách Sale thị trường qua AJAX (lọc + phân trang tại chỗ). page=số trang (mặc định 1).
		page = page || 1;
		var $box = $('#box_mktpool');
		if (!$box.length) { return; }
		$box.data('page', page);
		var data = $('.crm-mktpool .mktpool-filter').serialize() + '&page=' + page;
		$box.html('<div class="text-center p-5"><span class="spinner-border spinner-border-sm text-primary"></span><span class="text-muted ms-2">Đang tải…</span></div>');
		$.post(PCMS_URL + '/index.php?mod=crm&act=load_sale_marketplace', data, function (res) {
			if (res && res.kpi !== undefined) { $('#box_mkt_kpi').html(res.kpi); }
			if (res && res.html) { $box.html(res.html); }
		}, 'json').fail(function () {
			$box.html('<div class="alert alert-danger mb-0">Lỗi tải danh sách, thử lại.</div>');
		});
	}, mktpool_search: () => {
		// Ô tìm: debounce 400ms rồi lọc (về trang 1).
		clearTimeout($Core.crm._mktTimer);
		$Core.crm._mktTimer = setTimeout(() => { $Core.crm.mktpool_load(1); }, 400);
	}, mktpool_reset: () => {
		var $form = $('.crm-mktpool .mktpool-filter');
		$form.find('[name=kw], [name=f_from], [name=f_to]').val('');
		$form.find('[name=f_ptype], [name=f_tacc]').val('');
		$form.find('[name=f_unconv]').prop('checked', false);
		$Core.crm.mktpool_load(1);
	}, mktpool_convert: (_this) => {
		var ids = $('.crm-mktpool .mktpool-check:checked').map(function () { return this.value; }).get();
		if (!ids.length) { return; }
		var $sel = $('#mktpool-assignee'), target = $sel.length ? ($sel.val() || '') : '';
		if (!confirm('Chuyển ' + ids.length + ' tài khoản vào CRM?')) { return; }
		$Core.util.toggleIndicatior(1);
		var payload = { list_ids: ids };
		if (target) { payload.target_admin_id = target; }
		$.post(PCMS_URL + '/index.php?mod=crm&act=pop_convert_marketplace_to_crm', payload, function (res) {
			$Core.util.toggleIndicatior(0);
			if (res && res.msg === '_success') {
				var msg = 'Đã tạo ' + res.made + ' khách · bỏ qua ' + res.skipped_dup + ' (trùng SĐT)';
				if (res.skipped_no_phone) { msg += ' · ' + res.skipped_no_phone + ' (thiếu SĐT)'; }
				if (res.skipped_invalid) { msg += ' · ' + res.skipped_invalid + ' (sai loại)'; }
				$Core.alert.success(msg);
				setTimeout(function () { $Core.crm.mktpool_load($('#box_mktpool').data('page') || 1); }, 1200);
			} else {
				var em = (res && res.detail === 'max_200') ? 'Chọn tối đa 200 tài khoản mỗi lần.' : 'Có lỗi, vui lòng thử lại.';
				$Core.alert.error(em);
			}
		}, 'json').fail(function () {
			$Core.util.toggleIndicatior(0);
			$Core.alert.error('Lỗi kết nối, thử lại.');
		});
	}, init_quick_chip_carousel: () => {
		if (window.innerWidth < 576) {
			return; // mobile: KHÔNG dùng owl, chip cuộn ngang tự nhiên (overflow-x:auto trong crm.css)
		}
		if (typeof $.fn.owlCarousel !== 'function') {
			return;
		}
		$('.js__crm-quick-chip-carousel').each((_i, _elem) => {
			const $el = $(_elem);
			if ($el.data('crmQuickChipOwlInit') === 1) {
				return;
			}
			if (!$el.is(':visible')) {
				return; // group ẩn (d-none) → init owl lười khi toggle hiện ra
			}
			if ($el.hasClass('owl-loaded')) {
				$el.trigger('destroy.owl.carousel');
			}
			$el.owlCarousel({
				items: 1,
				autoWidth: true,
				margin: 8,
				dots: false,
				nav: false,
				loop: false,
				mouseDrag: true,
				touchDrag: true,
				pullDrag: true,
				freeDrag: true
			});
			$el.data('crmQuickChipOwlInit', 1);
		});
	}, bind_fixed_customer_table: () => {
		if (deviceType == "phone") {
			return;
		}
		if (typeof $.fn.freezeFixedTable !== 'function') {
			return;
		}
		const $tables = $('.holder_customer .table-crm');
		if (!$tables.length) {
			return;
		}
		// Bảng rỗng (chỉ có ô empty colspan=99): plugin freeze đo width cột 1 theo ô colspan rộng → cột header co mất. Bỏ freeze để bảng thường hiển thị đủ cột.
		if ($tables.find('.crm-empty-state, td.empty').length) {
			return;
		}
		$tables.freezeFixedTable({
			fixedLeft: 2,
			fixedRight: 1,
			minWidth: 992
		});
		setTimeout(() => {
			$tables.freezeFixedTable();
		}, 80);
		setTimeout(() => {
			$tables.freezeFixedTable();
		}, 260);
	}, parse_url: (url) => {
		try {
			const s = url || window.location.href; // nếu không có URL -> lấy hiện tại
			const u = new URL(s);
			return u.pathname;
		} catch (err) {
			console.error("URL không hợp lệ:", url);
			return "";
		}
	}, to_query_string: (obj, prefix) => {
		const pairs = [];
		for (const key in obj) {
			if (!obj.hasOwnProperty(key)) continue;
			const value = obj[key];
			const fullKey = prefix ? `${prefix}[${key}]` : key;
			if (typeof value === "object" && value !== null) {
				pairs.push(to_query_string(value, fullKey));
			} else if (fullKey != 'action') {
				pairs.push(encodeURIComponent(fullKey) + "=" + encodeURIComponent(value));
			}
		}
		return '?' + pairs.join("&");
	}, scrollToElem: (_this, e) => {
		e.preventDefault();
		var topM = $('.' + 'holder_customer').offset().top
		$('html,body').animate({ scrollTop: topM }, 500);
	}, check_item: (_this, e) => {
		e.stopPropagation();
		var _tp = $(_this).attr('tp'),
			_tab = $('.js_crm-filter-list:checked').val();
		if (_tp == 'all') {
			var _checked = $(_this).is(':checked') ? 1 : 0;
			$('.chk_customer').prop('checked', _checked);
			$('.btn-crm-action').prop('disabled', !_checked);
			if (_tab == 'following') {
				$('.btn_unshare').prop('disabled', (_checked > 0) ? 0 : 1);
			}
		} else {
			var _checkall = 1, _total_checked = 0;
			$('.chk_customer').each((_i, _elem) => {
				if (!$(_elem).is(':checked')) {
					_checkall = 0;
				} else {
					_total_checked += 1;
				}
			});
			$('input[tp=all]').prop('checked', _checkall);
			$('.btn-crm-action').prop('disabled', (_total_checked > 0) ? 0 : 1);
			if (_tab == 'following') {
				$('.btn_unshare').prop('disabled', (_total_checked > 0) ? 0 : 1);
			}
		}
	}, do_action: (_this, e) => {
		e.preventDefault();
		var _total_field = 0,
			_update_field = {},
			_form = $(_this).closest('form'),
			is_amount_paid = $("input[name='is_amount_paid']", _form).is(":checked") ? 1 : 0,
			request_id = $("select[name='request_id']", _form).val(),
			list_ids = $Core.util.getCheckBoxValueByClass('chk_customer');
		if ($Core.util.isEmpty(list_ids)) {
			$Core.alert.error('Lỗi chưa chọn contact !!!');
			return false;
		}
		if ($('.upd_field', _form).length) {
			$('.upd_field', _form).each((_i, _elem) => {
				var _field = $(_elem).attr('name');
				if (!$Core.util.isEmpty($(_elem).val())) {
					_total_field += 1;
					_update_field[_field] = $(_elem).val();
				}
			});
		}
		if (_total_field == 0) {
			$Core.alert.error('Lỗi chưa chọn điều kiện áp dụng !!!');
			return false;
		}
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=do_action', {
			'list_ids': list_ids,
			'update_field': _update_field,
			'is_amount_paid': is_amount_paid,
			'request_id': request_id,
		}, function (html) {
			$Core.util.toggleIndicatior(0);
			$Core.crm.load_customers('_desktop', {});
			$('.btn-crm-action').attr('disabled', true);
			$('.bs-webui-popover').webuiPopover('hideAll');
		});
		return false;
	}, loadAmountRequest: (_this, e) => {
		var toId = $(_this).attr('toId'),
			admin_id = $(_this).val(),
			$_adata = { 'admin_id': admin_id };
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=loadAmountRequest', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (respJson.html) {
				$("#" + toId).html(respJson.html).removeClass("d-none");
			} else {
				$("#" + toId).html("").addClass("d-none");
			}
		}, "json");
	}, showRequest: (_this, e) => {
		var toId = $(_this).attr('toId');
		if ($(_this).is(":checked")) {
			$("#" + toId).removeClass("d-none");
		} else {
			$("#" + toId).addClass("d-none");
		}
	}, get_select_city: (_this, e) => {
		var toId = $(_this).attr('toId'),
			country_id = $(_this).val(),
			$_adata = { 'country_id': country_id };
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=get_select_city', $_adata, function (html) {
			$Core.util.toggleIndicatior(0);
			$('#' + toId).html(html).val('null');
		});
	}, set_date_range_search: (_this, e) => {
		e.preventDefault();
		var rId = $(_this).attr('rId');
		if (rId == '_all') { rId = 0; }
		$('.uqozsZBxSY').removeClass('active');
		$(_this).addClass('active');
		$('select[name=reg_date_range]').val(rId).trigger('change');
		return false;
	}, load_desktop_chart_cus: (options) => {
		var $_adata = options || {};
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_desktop_chart_cus', $_adata, function (barChartData) {
			$Core.util.toggleIndicatior(0);
			$Core.chart.canvas('desktop_chart_cus', barChartData);
		}, 'json');
	}, load_desktop_chart_res: (options) => {
		var $_adata = options || {};
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_desktop_chart_res', $_adata, function (barChartData) {
			$Core.util.toggleIndicatior(0);
			$Core.chart.canvas('load_desktop_chart_res', barChartData);
		}, 'json');
	}, sw_tab: (_this, e) => {
		e.preventDefault();
		var type_id = $(_this).attr('type_id');
		$('.dashboard_tabs > li').removeClass('active');
		$(_this).parent('li').addClass('active');
		$Core.crm.load_customers('_desktop', { 'type_id': type_id });
		return false;
	}, kafa_customer: (_this, e) => {
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			start_date = $(_this).attr('start_date'),
			due_date = $(_this).attr('due_date');
		return false;
	}, page_customer: (_this, e) => {
		e.preventDefault();
		var status_id = $(_this).attr('status_id'),
			$_adata = { 'status_id': status_id };
		$Core.util.toggleIndicatior(1);
		$('.js__menu-customer').removeClass('active');
		$(_this).parent().addClass('active');
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=page_customer', $_adata, function (html) {
			$('#' + 'app').html(html);
			$Core.util.toggleIndicatior(0);
			$Core.crm.load_customers('_tablist', { 'status_id': status_id });
		});
		return false;
	}, set_button: (_this, e) => {
		e.preventDefault();
		var tp = $(_this).data('tp');
		$('.apcfHDsTFN').removeClass('active');
		$(_this).addClass('active');
		$Core.crm.load_calendar('_desktop', '_all');
		return false;
	}, set_status: (_this, e) => {
		e.preventDefault();
		var status_id = $(_this).attr('status_id');
		$('.search_crm_status_field').val(status_id).trigger('change');
		return false;
	}, set_campaign: (_this, e) => {
		e.preventDefault();
		var campaign_id = $(_this).attr('campaign_id');
		$('.search_field[name=campaign_id]').val(campaign_id).trigger('change');
		return false;
	}, load_calendar: (holderG, typeHoldder = '_all') => {
		$Core.util.toggleIndicatior(1);
		var $_adata = { 'holderG': holderG, 'typeHoldder': typeHoldder };
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_calendar', $_adata, function (html) {
			$Core.util.toggleIndicatior(0);
			var oSettings = {}, doubleClick = false;
			if (holderG == '_desktop') {
				$('.desktop_calendar').html(html);
				var _height = 300;
				if (deviceType == 'phone') {
					_height = 200;
				}
				var tp = $('.apcfHDsTFN.active').data('tp'),
					query_string = '&tp=' + tp;
				oSettings = {
					selectable: true,
					unselectAuto: false,
					contentHeight: _height,
					header: { left: '', center: 'title', right: 'prev,next' },
					events: PCMS_URL + "/index.php?mod=" + MOD + "&act=load_cell_calendar" + query_string,
					eventRender: function (event, element, view) {
						element.find(".fc-event-title").remove();
						if (parseInt(event.number) > 0) {
							var html = '<div class="fc-event-badge">'
								+ ' <span class="badge">' + event.number + '</span>'
								+ '</div>';
							element.append(html);
						}
					}, eventClick: function (event, jsEvent, view) {
						var date_id = $.fullCalendar.formatDate(event.start, "dd/MM/yyyy");
						if (tp == 'customer') {
							$('input[name=reg_date]').val($Core.util.toYMD(date_id));
							$Core.crm.load_customers('_desktop', {});
						} else {
							$Core.crm.load_desktop_followups({
								'holderG': '_desktop',
								'date_id': date_id,
								'typeHolder': '_calendar'
							});
						}
					}, dayClick: function (date, jsEvent, view) {
						var date_id = $.fullCalendar.formatDate(date, "dd/MM/yyyy");
						if (tp == 'customer') {
							$('input[name=reg_date]').val($Core.util.toYMD(date_id));
							$Core.crm.load_customers('_desktop', {});
						} else {
							$Core.crm.load_desktop_followups({
								'holderG': '_desktop',
								'date_id': date_id,
								'typeHolder': '_calendar'
							});
						}
					}
				};
				$('#calendar_desktop').fullCalendar(oSettings);
			} else {
				$('#app').html(html);
				oSettings = {
					header: {
						left: 'prev,next today myCustomButton',
						center: 'title',
						right: 'month,basicWeek,basicDay'
					},
					selectable: true,
					contentHeight: 680,
					events: PCMS_URL + '/index.php?mod=' + MOD + '&act=load_followups_month&holderG=' + holderG + '&typeHoldder=' + typeHoldder,
					eventRender: function (event, element, view) {
						element.find(".fc-event-title").remove();
						var date_id = $.fullCalendar.formatDate(event.start, "dd-MM-yyyy");
						if (parseInt(event.number) > 0) {
							var html = '<div class="fc-event-custom">'
								+ '	<span class="badge badge-important">' + event.number + '</span>'
								+ '	<div class="fc-event-list">' + event.htmlList + '</div>'
								+ '</div>';
							element.append(html);
						}
						element.bind('dblclick', function () {
							$('.fc-event-skin').popover('hide');
							$Core.crm.open_followups(date_id, 'popup');
						});
						element.popover({
							trigger: 'click',
							title: event.title + '<button onclick="$(this).closest(\'div.popover\').popover(\'hide\');" type="button" class="close" aria-hidden="true">×</button>',
							content: event.htmlTable,
							width: '300',
							multi: false,
							closeable: true,
							delay: 300,
							placement: function (context, source) {
								var position = $(source).position();
								if (position.left > 515) {
									return "left";
								} else if (position.left < 515) {
									return "right";
								} else if (position.top < 110) {
									return "bottom";
								} else {
									return "top";
								}
							},
							html: true,
							container: 'body'
						}).on("show.bs.popover", function () {
							$('div.popover').each(function () {
								$(this).popover('hide');
							});
							return $(this).data("bs.popover").tip().css({
								maxWidth: "600px"
							});
						});
					},
					dayClick: function (date, jsEvent, view) {
						if (!doubleClick) {
							doubleClick = true;
							setTimeout(() => {
								doubleClick = false;
							}, 500);
						} else {
							var date_id = $.fullCalendar.formatDate(date, "dd-MM-yyyy");
							$Core.crm.open_followups(date_id, 'popup');
						}
					}
				};
				$('#calendar').fullCalendar(oSettings);
			}
		});
	}, load_desktop_followups: (options) => {
		var $_adata = options || {};
		if ($('.search_followups').length) {
			$('.search_followups').each((_i, _elem) => {
				var field = $(_elem).data('field');
				if ($_adata.hasOwnProperty(field) == false) {
					$_adata[field] = $(_elem).val();
				}
			});
		}
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_desktop_followups', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$('.holder_followups_desktop').html(respJson.html);
			$('.total_followups_desktop').html(respJson.total_record);
			setTimeout(() => {
				const verticalExample = document.getElementById('followups_desktop');
				if (verticalExample) {
					new PerfectScrollbar(verticalExample, {
						wheelPropagation: false
					});
				}
			}, 500);
		}, 'json');
	}, load_worklist: () => {
		$('#box_worklist').html('<div class="text-center p-3"><span class="spinner-border spinner-border-sm text-main"></span></div>');
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_worklist', {}, function (respJson) {
			$('#box_worklist').html(respJson.html);
		}, 'json');
	}, toggle_worklist: (_this, e) => {
		if (e) e.preventDefault();
		var $b = $('#box_worklist');
		if ($b.hasClass('d-none')) {
			$b.removeClass('d-none'); $(_this).addClass('active'); $Core.crm.load_worklist();
		} else {
			$b.addClass('d-none'); $(_this).removeClass('active');
		}
		return false;
	}, load_marketing_control: () => {
		$('#box_marketing').html('<div class="text-center p-3"><span class="spinner-border spinner-border-sm text-main"></span></div>');
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_marketing_control', {}, function (respJson) {
			$('#box_marketing').html((respJson && respJson.error) ? '<div class="border text-center rounded-2 p-3 border-dashed text-muted">' + (respJson.message || 'Không tải được dữ liệu.') + '</div>' : respJson.html);
		}, 'json');
	}, toggle_marketing: (_this, e) => {
		if (e) e.preventDefault();
		var $b = $('#box_marketing');
		if ($b.hasClass('d-none')) {
			$b.removeClass('d-none'); $(_this).addClass('active'); $Core.crm.load_marketing_control();
		} else {
			$b.addClass('d-none'); $(_this).removeClass('active');
		}
		return false;
	}, load_growth: () => {
		$('#box_growth').html('<div class="text-center p-3"><span class="spinner-border spinner-border-sm text-main"></span></div>');
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_growth', {}, function (respJson) {
			if (respJson && respJson.error) { $('#box_growth').html('<div class="border text-center rounded-2 p-3 border-dashed text-muted">' + (respJson.message || 'Không tải được dữ liệu.') + '</div>'); return; }
			$('#box_growth').html(respJson.html);
		}, 'json');
	}, toggle_growth: (_this, e) => {
		if (e) e.preventDefault();
		var $b = $('#box_growth');
		if ($b.hasClass('d-none')) {
			$b.removeClass('d-none'); $(_this).addClass('active'); $Core.crm.load_growth();
		} else {
			$b.addClass('d-none'); $(_this).removeClass('active');
		}
		return false;
	}, load_sale_dashboard: () => {
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_sale_dashboard', {}, function (respJson) {
			if (respJson && respJson.html) { $('#box_sale_dashboard').html(respJson.html); }
		}, 'json');
	}, load_assigned: (status, btn) => {
			status = status || 'all';
			if (btn) {
				$('.crm-ma-filter .btn').removeClass('btn-primary btn-warning btn-success btn-danger').addClass('btn-outline-secondary');
				$(btn).removeClass('btn-outline-secondary').addClass('btn-' + ($(btn).data('active') || 'primary'));
			}
			$('#box_assigned').attr('data-status', status);
			$('#box_assigned').html('<div class="text-center p-5"><span class="spinner-border spinner-border-sm text-primary"></span></div>');
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_assigned', { status: status }, function (respJson) {
				if (respJson && respJson.html) { $('#box_assigned').html(respJson.html); }
			}, 'json');
		}, remind_given: (_this, e) => {
			if (e) { e.preventDefault(); }
			var customer_id = $(_this).attr('customer_id');
			if (!customer_id) { return false; }
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=do_remind_given', { customer_id: customer_id }, function (resp) {
				if (resp && resp.status === '_success') {
					$Core.alert.success('Đã gửi nhắc (thông báo + Zalo) tới người nhận · lần ' + resp.count + '.');
					$Core.crm.load_assigned($('#box_assigned').attr('data-status') || 'all');
				} else {
					$Core.alert.error('Không nhắc được — số này có thể đã được nhận.');
				}
			}, 'json');
			return false;
		}, reassign_given: (_this, e) => {
			if (e) { e.preventDefault(); }
			var customer_id = $(_this).attr('customer_id');
			if (!customer_id) { return false; }
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_reassign_given', { customer_id: customer_id }, function (respJson) {
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			}, 'json');
			return false;
		}, reassign_given_submit: (_this, e) => {
			if (e) { e.preventDefault(); }
			var $btn = $(_this),
				$modal = $btn.closest('.modal'),
				customer_id = $btn.attr('customer_id'),
				admin_id = $modal.find('[name=admin_id]').val(),
				note = $modal.find('[name=note]').val() || '',
				notify_manager = $modal.find('[name=notify_manager]').is(':checked') ? 1 : 0;
			if (!customer_id) { return false; }
			if (!admin_id) { $Core.alert.error('Vui lòng chọn người nhận mới.'); return false; }
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=do_reassign_given', { customer_id: customer_id, admin_id: admin_id, note: note, notify_manager: notify_manager }, function (resp) {
				$Core.util.toggleIndicatior(0);
				if (resp && resp.indexOf('_success') >= 0) {
					$Core.popup.close($btn.closest('.modal'));
					$Core.alert.success('Đã phân lại số cho người nhận mới.');
					$Core.crm.load_assigned($('#box_assigned').attr('data-status') || 'all');
				} else {
					$Core.alert.error('Không phân lại được — số này có thể đã được nhận.');
				}
			});
			return false;
		}, take_back_given: (_this, e) => {
			if (e) { e.preventDefault(); }
			var customer_id = $(_this).attr('customer_id');
			if (!customer_id) { return false; }
			if (!confirm('Thu hồi số này về bạn? Người nhận sẽ không còn thấy khách này.')) { return false; }
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=do_take_back_given', { customer_id: customer_id }, function (resp) {
				$Core.util.toggleIndicatior(0);
				if (resp && resp.indexOf('_success') >= 0) {
					$Core.alert.success('Đã thu hồi số về bạn.');
					$Core.crm.load_assigned($('#box_assigned').attr('data-status') || 'all');
				} else {
					$Core.alert.error('Không thu hồi được — số này có thể đã được nhận.');
				}
			});
			return false;
		}, filter_assigned: (input) => {
			var q = (input.value || '').trim().toLowerCase();
			$('#box_assigned .crm-ma-row').each(function () {
				var hay = ($(this).attr('data-find') || '').toLowerCase();
				$(this).toggle(q === '' || hay.indexOf(q) >= 0);
			});
		}, export_assigned: () => {
			var rows = [['Khách hàng', 'SĐT', 'Người nhận', 'Trạng thái', 'Thời điểm']];
			$('#box_assigned .crm-ma-row:visible').each(function () {
				var $r = $(this);
				rows.push([
					$r.find('td:eq(0) .crm-ma-open').text().trim(),
					$r.find('td:eq(0) small').text().trim(),
					$r.find('td:eq(1) .fw-semibold').text().trim(),
					$r.find('td:eq(2) .crm-st').text().trim(),
					$r.find('td:eq(2) small').text().trim()
				]);
			});
			var csv = rows.map(function (r) { return r.map(function (c) { return '"' + String(c).replace(/"/g, '""') + '"'; }).join(','); }).join('\n');
			var blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8;' });
			var a = document.createElement('a');
			a.href = URL.createObjectURL(blob);
			a.download = 'khach-toi-giao.csv';
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
		}, load_team_board: (period) => {
		period = period || 'this_month';
		$('#box_team').html('<div class="text-center p-3"><span class="spinner-border spinner-border-sm text-main"></span></div>');
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_team_board', { period: period }, function (respJson) {
			if (respJson && respJson.error) { $('#box_team').html('<div class="border text-center rounded-2 p-3 border-dashed text-muted">' + (respJson.message || 'Không tải được dữ liệu.') + '</div>'); return; }
			$('#box_team').html(respJson.html);
		}, 'json');
	}, get_cm_filters: () => {
		return {
			period: $('.js__cm-period .btn.btn-primary').data('period') || 'last_30_days',
			only_overdue: $('.js__cm-overdue').is(':checked') ? 1 : 0,
			idle_min: parseInt($('.js__cm-idle').val(), 10) || 0
		};
	}, load_care_monitor: (period) => {
		var f = $Core.crm.get_cm_filters();
		if (period) { f.period = period; }
		$('#box_care_monitor').html('<div class="text-center p-3"><span class="spinner-border spinner-border-sm text-main"></span></div>');
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_care_monitor', { period: f.period }, function (respJson) {
			if (respJson && respJson.error) { $('#box_care_monitor').html('<div class="border text-center rounded-2 p-3 border-dashed text-muted">' + (respJson.message || 'Không tải được dữ liệu.') + '</div>'); return; }
			$('#box_care_monitor').html(respJson.html);
			$Core.crm.cm_apply_sale_filter();
		}, 'json');
		return false;
	}, cm_set_period: (_this) => {
		$('.js__cm-period .btn').removeClass('btn-primary').addClass('btn-outline-secondary');
		$(_this).removeClass('btn-outline-secondary').addClass('btn-primary');
		$Core.crm.load_care_monitor($(_this).data('period'));
		return false;
	}, cm_apply_sale_filter: () => {
		var rid = parseInt($('.js__cm-sale').val(), 10) || 0;
		$('.cm-row').each(function () {
			var r = parseInt($(this).data('rep'), 10);
			var vis = (!rid || r === rid);
			$(this).toggleClass('d-none', !vis);
			if (!vis) { $('.cm-detail[data-rep="' + r + '"]').addClass('d-none'); $(this).removeClass('cm-open'); }
		});
	}, cm_filter_sale: (_this) => {
		$Core.crm.cm_apply_sale_filter();
		return false;
	}, care_monitor_detail: (_this) => {
		var $row = $(_this);
		var rid = parseInt($row.data('rep'), 10) || 0;
		if (!rid) { return false; }
		var $dr = $('.cm-detail[data-rep="' + rid + '"]');
		if (!$dr.hasClass('d-none')) { $dr.addClass('d-none'); $row.removeClass('cm-open'); return false; }
		$dr.removeClass('d-none'); $row.addClass('cm-open');
		var $box = $('#cm_detail_' + rid);
		$box.html('<div class="text-center p-3"><span class="spinner-border spinner-border-sm text-main"></span></div>');
		var f = $Core.crm.get_cm_filters();
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_care_monitor_detail', { admin_id: rid, period: f.period, only_overdue: f.only_overdue, idle_min: f.idle_min }, function (respJson) {
			if (respJson && respJson.error) { $box.html('<div class="border text-center rounded-2 p-3 border-dashed text-muted m-2">' + (respJson.message || 'Không tải được.') + '</div>'); return; }
			$box.html(respJson.html || '<div class="text-muted p-3">Không có khách phù hợp.</div>');
		}, 'json');
		return false;
	}, toggle_team_board: (_this, e) => {
		if (e) e.preventDefault();
		var $b = $('#box_team');
		if ($b.hasClass('d-none')) {
			$b.removeClass('d-none'); $(_this).addClass('active'); $Core.crm.load_team_board();
		} else {
			$b.addClass('d-none'); $(_this).removeClass('active');
		}
		return false;
	}, merge_duplicate: (_this, e) => {
		if (e) e.preventDefault();
		var $btn = $(_this);
		var to = parseInt($btn.attr('data-merge-to'), 10) || 0;
		var from = parseInt($btn.attr('data-merge-from'), 10) || 0;
		var nm = $btn.attr('data-merge-name') || ('#' + from);
		if (!to || !from) { return false; }
		if (!confirm('Gộp hồ sơ "' + nm + '" (#' + from + ') VÀO hồ sơ đang xem (#' + to + ')?\n\nToàn bộ hoạt động / lịch sử / giao dịch của hồ sơ trùng sẽ chuyển sang hồ sơ này. Hồ sơ trùng được đưa vào thùng rác (có thể khôi phục). Hãy kiểm tra kỹ trước khi gộp.')) { return false; }
		var $panel = $btn.closest('.alert');
		var oldHtml = $btn.html();
		$btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=do_merge_duplicate', { merge_to: to, merge_from: from }, function (resp) {
			if (resp && resp.status === 'success') {
				$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_duplicates', { customer_id: to }, function (r) {
					if (r && typeof r.html !== 'undefined') { $panel.parent().html(r.html); }
				}, 'json');
			} else {
				alert((resp && resp.message) ? resp.message : 'Gộp thất bại.');
				$btn.prop('disabled', false).html(oldHtml);
			}
		}, 'json').fail(function () {
			alert('Lỗi kết nối khi gộp.');
			$btn.prop('disabled', false).html(oldHtml);
		});
		return false;
	}, toggle_hot: (_this, e) => {
			if (e) e.preventDefault();
			var on = $('.js__crm-hot-filter').val() == '1';
			if (on) {
				$('.js__crm-hot-filter').val(0); $(_this).removeClass('active');
			} else {
				$('.js__crm-hot-filter').val(1); $(_this).addClass('active');
				$('.js__crm-task-filter').val(0);
				$('.js__crm-quick-chip').removeClass('active').filter('[data-task-id="0"]').addClass('active');
			}
			$Core.crm.load_customers('_desktop', { page: 1 });
			return false;
		}, set_view_desktop_followup: (_this, e) => {
		e.preventDefault();
		var tp = $(_this).attr('tp');
		$('.hnnyecGoRk.active').removeClass('active');
		$(_this).addClass('active');
		$Core.crm.load_desktop_followups({ 'tp': tp });
		return false;
	}, load_followups: (customer_id, options) => {
		var $_adata = options || {};
		$_adata['customer_id'] = customer_id;
		if ($('.search_followups').length) {
			$('.search_followups').each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_followups', $_adata, function (respJson) {
			toggleIndicatior(0);
			$('.holder_followups_' + customer_id).html(respJson.html);
			if (parseInt(respJson.totalPage) > 1) {
				$_easyUI('#pp_CRMFollowUpDesktop').pagination({
					total: respJson.totalRecord,
					pageSize: respJson.number_per_page,
					onRefresh: function (pageNumber, pageSize) {
						var sorthander = $('.sorthander_CRMFollowUpDesktop').val(),
							tmp = sorthander.split('|');
						$Core.crm.load_followups(holderG, {
							'page': pageNumber,
							'per_page': pageSize,
							'sort_by': tmp[0],
							'sort_type': tmp[1]
						});
					},
					onSelectPage: function (pageNumber, pageSize) {
						var sorthander = $('.sorthander_CRMFollowUpDesktop').val(),
							tmp = sorthander.split('|');
						$Core.crm.load_followups(holderG, {
							'page': pageNumber,
							'per_page': pageSize,
							'sort_by': tmp[0],
							'sort_type': tmp[1]
						});
					}
				});
			}
			/* SortHandler */
			$('.sorthandler').click(function () {
				var _this = $(this),
					sort_by = _this.attr('column'),
					sort_type = _this.hasClass('bs-sort-asc') ? 'desc' : 'asc';
				$Core.crm.load_followups(holderG, { 'sort_by': sort_by, 'sort_type': sort_type });
				return false;
			});
		}, 'json');
	}, done_followup: (_this, e) => {
		e.preventDefault();
		var followup_id = $(_this).attr('followup_id'),
			customer_id = $(_this).attr('customer_id');
		if ($(_this).hasClass('disabled')) {
			return false;
		} else {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=done_followup', {
				'followup_id': followup_id,
				'customer_id': customer_id
			}, function (respJson) {
				$Core.util.toggleIndicatior(0);
				$Core.crm.load_activity(customer_id, {});
				$Core.crm.load_desktop_followups({});
			}, 'json');
		}
		return false;
	}, reset_search: (_this, e) => {
		e.preventDefault();
		var holderG = $(_this).attr('holderG'),
			form = $(_this).closest('form');
		form.clearForm();
		if ($('.search_field', form).length) {
			$('.search_field', form).each((_i, _elem) => {
				if ($(_elem).attr('type') == 'text') {
					$(_elem).val("");
				} else {
					$(_elem).val(0);
				}
			});
		}
		if ($('select.selectize-control', form).length) {
			$('select.selectize-control', form).each((_i, _elem) => {
				var $_select = $(_elem).selectize(),
					$_selectize = $_select[0].selectize;
				$_selectize.clear();
			});
		}
		$('.js__crm-task-filter').val(0); // U-P0a: reset chip tac nghiep khi xoa tim kiem
		$('.js__crm-quick-chip').removeClass('active').filter('[data-task-id="0"]').addClass('active');
		$Core.crm.load_customers(holderG, {});
		return false;
	}, toggle_more: (_this, e) => {
		e.preventDefault();
		var toElem = $(_this).attr('toElem');
		$(`.${toElem}`).removeClass('d-none');
		return false;
	}, set_field: (_this, e) => {
		e.preventDefault();
		var p_value = $(_this).is(':checked') ? 1 : 0,
			p_field = $(_this).attr('p_field'),
			$_adata = { 'p_field': p_field, 'p_value': p_value };
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=set_field', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.crm.do_search(_this, e);
		});
	}, reveal_phone: function (_this, e) {
		if (e) { e.preventDefault(); e.stopPropagation(); }
		var $w = $(_this).hasClass('crm-phone-wrap') ? $(_this) : $(_this).closest('.crm-phone-wrap');
		if (!$w.length) { return false; }
		var full = $w.attr('data-full');
		if (!full) { return false; }
		$w.find('.js__ph-text').first().text(full);
		$w.find('.js__ph-eye').remove();
		$w.removeClass('cursor-pointer js__reveal-phone').removeAttr('onclick');
		return false;
	}, open_coaching: function (_this, e) {
			if (e) { e.preventDefault(); e.stopPropagation(); }
			var rep_id = $(_this).attr('rep_id');
			if (!rep_id) { return false; }
			$Core.util.toggleIndicatior(1);
			$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=load_coaching', { 'rep_id': rep_id }, function (respJson) {
				$Core.util.toggleIndicatior(0);
				if (respJson.error) { $Core.alert.error(respJson.message || 'Khong mo duoc kem cap.'); return; }
				$('#crmCoachingModal').remove();
				$('body').append(respJson.html);
				$('#crmCoachingModal').on('hidden.bs.modal', function () { $(this).remove(); }).modal('show');
			}, 'json');
			return false;
		}, confirm_receipt: function (_this, e) {
		if (e) { e.preventDefault(); e.stopPropagation(); }
		var customer_id = $(_this).attr('customer_id');
		if (!customer_id) { return false; }
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=do_confirm_receipt', { 'customer_id': customer_id }, function (resp) {
			$Core.util.toggleIndicatior(0);
			if (!resp || resp.error) { $Core.alert.error((resp && resp.message) ? resp.message : 'Không xác nhận được.'); return; }
			$Core.alert.success(resp.message || 'Đã xác nhận nhận khách.');
			$Core.crm.load_customers('_desktop', {});
		}, 'json');
		return false;
	}, toggle_filter_panel: (el, ev) => {
		if (ev) { ev.preventDefault(); ev.stopPropagation(); }
		var $btn = $(el).closest('.crm-toolbar').find('.dropdown-crm-search').filter(':visible').first();
		if (!$btn.length) { $btn = $(el).closest('.crm-toolbar').find('.dropdown-crm-search').first(); }
		if ($btn.length) { $btn[0].click(); }
	}, update_filter_badge: () => {
		var n = 0;
		$('.search_field').each(function () {
			var f = $(this).data('field');
			var v = $(this).val();
			v = (v == null) ? '' : String(v).replace(/^\s+|\s+$/g, '');
			if (typeof f === 'undefined' || f === 'staff_id') { return; }
			if (f === 'hot') { if (v === '1') { n++; } return; }
			if (v !== '' && v !== '0') { n++; }
		});
		var $b = $('.js__crm-filter-count');
		$b.text(n);
		$b.toggleClass('d-none', n === 0);
	}, load_customers: (holderG, options, _isLoading = true) => {
		var $_adata = options || {},
			tab = $('input[name=tab]:checked').val(),
			sort_by = $('.js__sort-by.active').attr('sort_by'),
			is_all = $('input[name=is_all]').is(':checked') ? 1 : 0,
			action = '_append';
		if ($('.search_field,.search_report_field').length) {
			$('.search_field,.search_report_field').each((_i, _elem) => {
				if ($(_elem).closest('.d-none').length) { return; } // skip fields inside hidden wrappers
				var _field = $(_elem).data('field'),
					_value = $(_elem).val();
				if (typeof (_field) != 'undefined' && !$Core.util.isEmpty(_field) && !$Core.util.isEmpty(_value)) {
					$_adata[_field] = _value;
					if (_field == 'admin_id') {
						if (parseInt($_adata[_field]) > 0 && tab == 'manage') {
							tab = 'following';
							$('input[name=tab][value=' + tab + ']').prop('checked', true);
						}
					}
				}
			});
		}
		$_adata['tab'] = tab;
		$_adata['is_all'] = is_all;
		$_adata['holderG'] = holderG;
		if (typeof (sort_by) !== 'undefined') {
			$_adata['sort_by'] = sort_by;
		}
		if ($Core.util.hasAttr('page', $_adata) == false) {
			var page = $('.PageCustomer_Page').val(),
				per_page = $('.PageCustomer_Length').val();
			$_adata['page'] = page;
			$_adata['per_page'] = per_page;
		}
		var query_string = $Core.crm.to_query_string($_adata);
		if (!$Core.util.isEmpty(query_string)) {
			var _path = $Core.crm.parse_url();
			$Core.util.popstate(_path + query_string);
		}
		1 == _isLoading && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_customers', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (action == '_append') {
				$('#' + 'box_warning').html(respJson.html_warning);
				$('.briefs').html(respJson.html_briefs);
				$(".total_customer").html(respJson.total_record);
				$(".total_manage").html(respJson.total_manage);
				$(".total_converted").html(respJson.total_converted);
				$(".total_assign").html(respJson.total_assign);
				$(".total_team").html(respJson.total_team);
			}
			if ($('.begin_need').length) {
				$('.begin_need').each((_i, _elem) => {
					var _h = $(_elem).outerHeight(false);
					if (_h > 35) {
						$(_elem).readmore({ speed: 75, maxHeight: 35 });
					}
				});
			}
			if (respJson.html.indexOf('empty') >= 0) {
				$(".holder_customer").html(respJson.html);
				$Core.crm.render_customer_pagination({
					current_page: 1,
					total_page: 1,
					total_record: 0,
					per_page: parseInt(respJson.per_page || 20, 10),
					holderG: holderG
				});
			} else {
				$(".holder_customer").html(respJson.html);
				$('.PageCustomer_Page').val(respJson.current_page);
				$('.PageCustomer_Length').val(respJson.per_page);
				$('.PageCustomer_Length_Select').val(respJson.per_page);
				$Core.crm.render_customer_pagination({
					current_page: parseInt(respJson.current_page || 1, 10),
					total_page: parseInt(respJson.total_page || 1, 10),
					total_record: parseInt(respJson.total_record || 0, 10),
					per_page: parseInt(respJson.per_page || 20, 10),
					holderG: holderG
				});
			}
			$Core.crm.bind_fixed_customer_table();
			$Core.crm.update_filter_badge();
			$_document.on('click', '.dropdown-button', e => {
				e.stopPropagation();
				const b = $(e.currentTarget),
					o = b.offset(),
					m = b.siblings('.dropdown-menu').clone()
						.addClass('dropdown-menu-floating')
						.css({ position: 'absolute', display: 'block' })
						.appendTo('body');
				$('.dropdown-menu-floating').not(m).remove();
				m.css({
					top: o.top + b.outerHeight(),
					left: o.left + b.outerWidth() - m.outerWidth(),
					zIndex: 1000
				});
				$_document.one('click', () => m.remove());
			});
		}, 'json');
	}, get_pager_key: (holderG) => {
		return String(holderG || 'desktop').replace(/^_+/, '');
	}, render_customer_pagination: (options) => {
		var current_page = parseInt(options.current_page || 1, 10),
			total_page = parseInt(options.total_page || 1, 10),
			total_record = parseInt(options.total_record || 0, 10),
			per_page = parseInt(options.per_page || 20, 10),
			holderG = options.holderG || '_desktop';
		if (total_page <= 0) {
			total_page = 1;
		}
		if (current_page <= 0) {
			current_page = 1;
		}
		if (current_page > total_page) {
			current_page = total_page;
		}
		var start = 0, end = 0;
		if (total_record > 0) {
			start = (current_page - 1) * per_page + 1;
			end = Math.min(current_page * per_page, total_record);
		}
		$('.js__crm-paging-summary').html('Hiển thị ' + start + ' đến ' + end + ' của ' + total_record + ' bản ghi');
		var pagerKey = $Core.crm.get_pager_key(holderG),
			$pager = $('#pager_' + pagerKey);
		if (!$pager.length) {
			return;
		}
		if (total_page > 1 && typeof $pager.pagination === 'function') {
			$pager.removeClass('d-none').pagination({
				listStyle: "pagination justify-content-center",
				currentPage: current_page,
				itemsOnPage: per_page,
				items: total_record,
				cssStyle: 'light-theme',
				prevText: '<i class="tf-icon bx bx-chevrons-left"></i>',
				nextText: '<i class="tf-icon bx bx-chevrons-right"></i>',
				onPageClick: function (pageNumber) {
					$Core.crm.load_customers(holderG, { 'page': pageNumber, 'per_page': per_page });
				}
			});
		} else {
			$pager.addClass('d-none').empty();
		}
	}, customer_per_page_change: (_this, e) => {
		e.preventDefault();
		var holderG = $(_this).attr('holderG') || 'desktop',
			per_page = parseInt($(_this).val() || 10, 10);
		$('.PageCustomer_Page').val(1);
		$('.PageCustomer_Length').val(per_page);
		$Core.crm.load_customers(holderG, {
			'page': 1,
			'per_page': per_page
		});
		return false;
	}, customer_page: (_this, e) => {
		e.preventDefault();
		if ($(_this).closest('.page-item').hasClass('disabled') || $(_this).closest('.page-item').hasClass('active')) {
			return false;
		}
		var page = parseInt($(_this).data('page') || 1, 10),
			per_page = parseInt($(_this).data('per_page') || $('.PageCustomer_Length').val() || 20, 10),
			holderG = $(_this).data('holder') || 'desktop';
		$('.PageCustomer_Page').val(page);
		$Core.crm.load_customers(holderG, {
			'page': page,
			'per_page': per_page
		});
		return false;
	}, load_more: (_this, e) => {
		return $Core.crm.customer_page(_this, e);
	}, do_stask: (_this, e) => {
		e.preventDefault();
		var holderG = $(_this).attr('holderG'),
			customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=do_stask', {
			'holderG': holderG,
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.crm.load_stack_customers({ 'holderG': holderG });
		}, 'json');
		return false;
	}, load_stack_customers: (options) => {
		var $_adata = options || {};
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_stack_customers', $_adata, function (respJson) {
			$('.holder_stacks').html(respJson.html);
		}, 'json');
	}, do_search: (_this, e) => {
		var field = $(_this).data('field'),
			holderG = $(_this).attr('holderG'),
			name = $(_this).attr('name'),
			options = {};
		if (field == 'group_id') {
			var group_val = $(_this).val();
			if (parseInt(group_val) > 0) {
				$('.js_crm-filter-list').prop('disabled', true);
			} else {
				$('.js_crm-filter-list').prop('disabled', false);
			}
		}
		if (name == 'tab' && $(".btn_unshare").length > 0) {
			var group_val = $(_this).val();
			if (group_val == "following") {
				$(".btn_unshare").removeClass("d-none");
			} else {
				$(".btn_unshare").addClass("d-none");
			}
		}
		if (name == 'tab') { // doi tab -> reset chip tac nghiep + tinh trang (chip = pipeline owner)
			$('.js__crm-task-filter').val(0);
			$('.search_crm_status_field').val(0).trigger('change.select2');
			$('.js__crm-quick-chip').removeClass('active').filter('[data-task-id="0"],[data-status-id="0"]').addClass('active');
			// tab=Nhom: hien select NV (PKD/Vung/full); an "Nguoi quan ly" tranh trung admin_id
			if ($(_this).val() == 'team') {
				$('.js-team-rep-wrap').removeClass('d-none');
				$('.js-manager-wrap').addClass('d-none');
			} else {
				$('.js-team-rep-wrap').addClass('d-none').find('[data-field="admin_id"]').val('0');
				$('.js-manager-wrap').removeClass('d-none');
			}
		}
		if (name == 'date_type') {
			var gId = $(_this).attr('gId'),
				date_type = $(_this).val(),
				year = $("select[name='year'][gId='" + gId + "']").val();
			$.post(PCMS_URL + '/index.php?mod=home&sub=dashboard&act=load_month', {
				'date_type': date_type, 'year': year
			}, function (html) {
				$('select[name=month][gId=' + gId + ']').html(html);
				var params = $.extend(options, { 'gId': gId });
				if ($('select[gId=' + gId + '],input[gId=' + gId + ']').length) {
					$('select[gId=' + gId + '],input[gId=' + gId + ']').each((_i, _elem) => {
						var p_name = $(_elem).attr('name'),
							p_value = $(_elem).val();
						if (!$Core.util.isEmptyZero(p_value)) {
							params[p_name] = p_value;
						}
					});
				}
				$('.ajax[gId=' + gId + ']').data('options', params);
				$('.ajax[gId=' + gId + ']').removeClass('loaded');
				_autoload();
			});
		} else if (name == 'year') {
			var gId = $(_this).attr('gId'),
				date_type = '_month',
				year = $(_this).val();
			if ($('select[name=date_type][gId=' + gId + ']').length) {
				date_type = $('select[name=date_type][gId=' + gId + ']').val();
			}
			if (date_type == '_quater' || date_type == '_half') {
				var params = $.extend(options, { 'gId': gId });
				if ($('select[gId=' + gId + '],input[gId=' + gId + ']').length) {
					$('select[gId=' + gId + '],input[gId=' + gId + ']').each((_i, _elem) => {
						var p_name = $(_elem).attr('name'),
							p_value = $(_elem).val();
						if (!$Core.util.isEmptyZero(p_value)) {
							params[p_name] = p_value;
						}
					});
				}
				$('.ajax[gId=' + gId + ']').data('options', params);
				$('.ajax[gId=' + gId + ']').removeClass('loaded');
				_autoload();
			} else {
				$.post(PCMS_URL + '/index.php?mod=home&sub=dashboard&act=load_month', {
					'date_type': date_type,
					'year': year,
				}, function (html) {
					$('select[name=month][gId=' + gId + ']').html(html);
					if ($('select[name=quarter][gId=' + gId + ']').length) {
						$('select[name=quarter][gId=' + gId + ']').val("");
					}
					var params = $.extend(options, { 'gId': gId });
					if ($('select[gId=' + gId + '],input[gId=' + gId + ']').length) {
						$('select[gId=' + gId + '],input[gId=' + gId + ']').each((_i, _elem) => {
							var p_name = $(_elem).attr('name'),
								p_value = $(_elem).val();
							if (!$Core.util.isEmptyZero(p_value)) {
								params[p_name] = p_value;
							}
						});
					}
					$('.ajax[gId=' + gId + ']').data('options', params);
					$('.ajax[gId=' + gId + ']').removeClass('loaded');
					_autoload();
				});
			}
		}
		$('.PageCustomer_Page').val(1);
		$Core.crm.load_customers(holderG, {});
		if ($(_this).hasClass('do_crm_search')) {
			var gId = $(_this).attr('gId');
			$("#" + gId).dropdown('toggle');
		}
	}, do_sorted: (_this, e) => {
		e.preventDefault();
		var sort_by = $(_this).attr('sort_by'),
			holderG = $(_this).attr('holderG');
		$('.js__sort-by').removeClass('active');
		$(`.js__sort-by[sort_by=${sort_by}]`).addClass('active');
		$Core.util.toggleIndicatior(1);
		/*$.post(path_ajax_script+'/index.php?mod='+MOD+'&act=handle_sorted', {
			'sort_by' : sort_by
		}, function(respJson){
			$Core.crm.load_customers(holderG, {});
		}, 'json'); */
		return false;
	}, handle_set_view: (_this, e) => {
		var view = $('input[name=view]:checked').val();
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=set_view', {
			'view': view
		}, function (respJson) {
			console.log('Success !!!');
		}, 'json');
	}, load_converted_rates: (options) => {
		var $_adata = options || {};
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=load_converted_rates', $_adata, function (respJson) {
			$('#' + 'holder_converted_rates').html(respJson.html);
		}, 'json');
	}, open_search: (_this, e) => {
		e.preventDefault();
		e.stopPropagation();
		$('html,body').animate({ scrollTop: 0 }, 500);
		var gId = $(_this).attr('gId');
		$("." + gId).trigger('click');
		$('.container-floating').trigger('hover');
		return false;
	}, view_activity_gas: (customer_id, options) => {
		var $_adata = options || {};
		$_adata['customer_id'] = customer_id;
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=view_activity', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			var _www = $(window).width();
			$Core.popup.openfull(_www - 400, respJson.html, respJson.uid);
			$('.modal-backdrop').remove();
			$Core.crm.load_activity(customer_id, {});
		}, 'json');
	}, view_activity: function (_this, e) {
		e.preventDefault();
		e.stopPropagation();
		var route = $(_this).attr('route'),
			customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=view_activity', {
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			var _www = $(window).width();
			$Core.popup.openfull(_www - 480, respJson.html, respJson.uid);
			$('.modal-backdrop').remove();
			$Core.crm.load_activity(customer_id, {});
		}, 'json');
		return false;
	}, update_field: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field'),
			form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);
		form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=update_field',
			data: { 'uid': uid, 'p_id': p_id, 'p_field': p_field },
			success: function (html) {
				$Core.util.toggleIndicatior(0);
				if (html.indexOf('_success') >= 0) {
					var tmp = html.split('|||');
					if (p_field == 'admin_id') {
						$('body').append(tmp[1]);
						$('.autoclick_' + p_id).trigger('click').remove();
						$('.btn-close', form).trigger('click');
					} else if (p_field == 'tags') {
						var holderG = $(_this).getAttr('holderG', "_pop"),
							current_page = $('.PageCustomer_Page').val(),
							per_page = $('.PageCustomer_Length').val();
						if (holderG == "_modal") {
							$('.btn-close', form).trigger('click');
						} else {
							$('.bs-webui-popover').webuiPopover('hide');
						}
						$('.tags-box_' + p_id + ' .tags-list-' + p_id).replaceWith(tmp[1]);
						$Core.crm.load_customers('_desktop', { 'page': current_page, 'per_page': per_page }, false);
					} else {
						$('.status_' + uid).html(tmp[1]);
						$('.btn-close', form).trigger('click');
						// U-P2a: ô status đã cập nhật tại chỗ (dòng trên) → bỏ reload toàn grid (cell-scoped)
					}
				}
			}
		});
		return false;
	}, open_tags: (_this, e) => {
		e.preventDefault();
		var customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(1);
		$.get(path_ajax_script + '/index.php?mod=' + MOD + '&act=load_pop_tag', {
			'holderG': '_modal',
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, inline_change_status: (_this, e) => {
		var $sel = $(_this),
			customer_id = $sel.attr('customer_id'),
			new_status = $sel.val(),
			prev_status = $sel.attr('data-prev'),
			new_bg = $sel.find('option:selected').attr('data-bg') || '#6c757d';
		if (new_status == prev_status) { return false; }
		$sel.css('background-color', new_bg).prop('disabled', true); // optimistic recolor
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=update_field', { 'uid': '', 'p_id': customer_id, 'p_field': 'status_id', 'status_id': new_status }, function (html) {
			$sel.prop('disabled', false);
			if (html.indexOf('_success') >= 0) {
				$sel.attr('data-prev', new_status);
				$Core.alert.success('Đã đổi phân loại khách');
			} else {
				$sel.val(prev_status);
				$sel.css('background-color', $sel.find('option:selected').attr('data-bg') || '#6c757d');
				$Core.alert.error('Không đổi được phân loại (không đủ quyền hoặc lỗi).');
			}
		}).fail(function () { $sel.prop('disabled', false).val(prev_status).css('background-color', $sel.find('option:selected').attr('data-bg') || '#6c757d'); $Core.alert.error('Lỗi kết nối — chưa đổi được phân loại.'); });
		return false;
	}, mb_change_status: (_this, e) => {
		// Thẻ khách mobile: đổi trạng thái từ menu "Chuyển" (cùng contract update_field như inline_change_status)
		e.preventDefault();
		if (e && e.stopPropagation) { e.stopPropagation(); }
		var $it = $(_this),
			customer_id = $it.attr('data-customer-id'),
			status_id = $it.attr('data-status-id'),
			bg = $it.attr('data-bg') || '#6c757d',
			title = $.trim($it.text()),
			$card = $it.closest('.cmc'),
			$pill = $card.find('.cmc-status'),
			prevTitle = $pill.text(),
			prevBg = $card.length ? $card[0].style.getPropertyValue('--st-bg') : '';
		$it.closest('.cmc-chuyen-menu').find('.cmc-st-item').removeClass('active');
		$it.addClass('active');
		$pill.text(title);
		if ($card.length) { $card[0].style.setProperty('--st-bg', bg); } // optimistic re-tint
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=update_field', { 'uid': '', 'p_id': customer_id, 'p_field': 'status_id', 'status_id': status_id }, function (html) {
			if (html.indexOf('_success') >= 0) {
				$Core.alert.success('Đã đổi phân loại khách');
			} else {
				$pill.text(prevTitle);
				if ($card.length) { $card[0].style.setProperty('--st-bg', prevBg); }
				$Core.alert.error('Không đổi được phân loại (không đủ quyền hoặc lỗi).');
			}
		}).fail(function () {
			$pill.text(prevTitle);
			if ($card.length) { $card[0].style.setProperty('--st-bg', prevBg); }
			$Core.alert.error('Lỗi kết nối — chưa đổi được phân loại.');
		});
		return false;
	}, open_activity: (_this, e) => {
		e.preventDefault();
		var options = {},
			tp = $(_this).attr('tp'),
			tabid = $(_this).attr('tabid'),
			type_id = $(_this).attr('type_id'),
			customer_id = $(_this).attr('customer_id');
		if ($(_this).hasClass('disabled')) {
			$Core.swal.error("Thông báo", "Chức năng này đang tạm khoá !");
			return false;
		}
		if (tp == 'follow-ups') {
			var followup_id = $(_this).attr('followup_id');
			options['followup_id'] = followup_id;
		}
		$('.' + tabid).removeClass('active');
		$(_this).addClass('active');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=open_activity', $.extend(options, {
			'tp': tp,
			'type_id': type_id,
			'customer_id': customer_id
		}), function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$('#' + respJson.uid).on('shown.bs.modal', function (e) {
				$('textarea[name=intro]').focus();
			});
		}, 'json');
		return false;
	}, open_upd_status: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(0);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=open_upd_status', {
			'uid': uid,
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, set_timerange: (_this, e) => {
		var _form = $(_this).closest('form'),
			after_time = $(_this).val(),
			reminder_before = $("input[name=reminder_before]", _form).val(),
			is_reminder_time = $("input[name=is_reminder]", _form).is(':checked') ? 1 : 0;
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=set_timerange', {
			'after_time': after_time, 'is_reminder_time': is_reminder_time, 'reminder_before': reminder_before
		}, function (respJson) {
			$('input[name=time_id]', _form).val(respJson.time);
			$('input[name=date_id]', _form).val(respJson.date).trigger('change');
			$('input[name=reminder_time_id]', _form).val(respJson.time_before);
			$('input[name=reminder_date_id]', _form).val(respJson.date_before);
			$('input[name=reminder_before]', _form).val(respJson.reminder_before);
		}, 'json');
	}, set_timebefore: (_this, e) => {
		var _form = $(_this).closest('form'),
			reminder_before = $(_this).val(),
			date_id = $('input[name=date_id]', _form).val(),
			time_id = $('input[name=time_id]', _form).val();
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=set_timebefore', {
			'date_id': date_id, 'time_id': time_id, 'reminder_before': reminder_before
		}, function (respJson) {
			$('input[name=reminder_time_id]', _form).val(respJson.time_before);
			$('input[name=reminder_date_id]', _form).val(respJson.date_before);
		}, 'json');
	}, set_change: (_this, e) => {
		var toId = $(_this).attr('toId'),
			_form = $(_this).closest('form');
		var date_id = $('input[name=date_id]', _form).val(),
			time_id = $('input[name=time_id]', _form).val();
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=campare_date_now', {
			'date_id': date_id,
			'time_id': time_id
		}, function (html) {
			if (parseInt(html) == 1) {
				$('#' + toId).prop('checked', true);
			} else {
				$('#' + toId).prop('checked', false);
			}
		});
	}, open_reply: (_this, e) => {
		e.preventDefault();
		var parent_id = $(_this).attr('parent_id'),
			followup_id = $(_this).attr('followup_id'),
			customer_id = $(_this).attr('customer_id');
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=open_reply', {
			'parent_id': parent_id,
			'followup_id': followup_id,
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$('#' + respJson.uid).on('shown.bs.modal', function (e) {
				$('textarea[name=content]').focus();
			});
		}, 'json');
		return false;
	}, save_reply: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			parent_id = $(_this).attr('parent_id'),
			followup_id = $(_this).attr('followup_id'),
			customer_id = $(_this).attr('customer_id'),
			content = $('textarea[name=content]', _form).val();
		if ($Core.util.isEmpty(content)) {
			$('textarea[name=content]', _form).focus();
			return false;
		} else {
			$Core.util.toggleIndicatior(1);
			$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=save_reply', {
				'parent_id': parent_id,
				'followup_id': followup_id,
				'customer_id': customer_id,
				'content': content
			}, function (respJson) {
				$Core.util.toggleIndicatior(0);
				$Core.crm.load_activity(customer_id, {});
				$('.btn-close', _form).trigger('click');
			}, 'json');
		}
		return false;
	}, save_activity: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			tp = $(_this).attr('tp'),
			customer_id = $(_this).attr('customer_id'),
			$_adata = { 'tp': tp, 'customer_id': customer_id };
		if (tp == 'notes') {
			note_id = $(_this).attr('note_id');
			$_adata['note_id'] = note_id;
		} else {
			followup_id = $(_this).attr('followup_id');
			$_adata['followup_id'] = followup_id;
		}
		if ($('select.required,textarea.required', _form).length) {
			$('select.required,textarea.required', _form).each((_i, _elem) => {
				if ($Core.util.isEmpty($(_elem).val())) {
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if (_validated == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + "/index.php?mod=" + MOD + "&act=save_activity",
				data: $_adata,
				dataType: 'html',
				success: function (html) {
					$Core.util.toggleIndicatior(0);
					if (html.indexOf('_success') >= 0) {
						// Đọc "Bước tiếp theo" TRƯỚC khi đóng form (đóng modal gỡ form khỏi DOM).
						var _hasNextTask = parseInt($('select[name=outcome_id]', _form).val() || '0', 10) > 0;
						$Core.alert.success('Thành công');
						$('.btn-close', _form).trigger('click');
						if (tp == 'follow-ups') {
							$Core.crm.load_activity(customer_id, {});
							if($('#desktop_calendar').length){
								$('#desktop_calendar').fullCalendar("refetchEvents");
							}
							$Core.crm.load_desktop_followups({});
							// Activity có chọn "Bước tiếp theo" → đổi tác nghiệp khách → reload danh sách để cột Tác nghiệp/Kết quả khớp.
							if (_hasNextTask && $('.PageCustomer_Page').length) {
								$Core.crm.load_customers('_desktop', {});
							}
						} else {
							$('.js__tab-activity[customer_id=' + customer_id + '][tp=notes]').trigger('click');
						}
					} else {
						$Core.alert.success('Đã xảy ra lỗi');
					}
				}
			});
		}
		return false;
	},
	load_activity: function (customer_id, options) {
		var $_adata = options || {};
		$_adata['customer_id'] = customer_id;
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_activity', $_adata, function (respJson) {
			$('.holder_activity_' + customer_id).html(respJson.html);
		}, 'json');
	},
	load_consulting: function (customer_id, options) {
		var $_adata = options || {};
		$_adata['customer_id'] = customer_id;
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_consulting', $_adata, function (respJson) {
			$('.holder_activity_' + customer_id).html(respJson.html);
		}, 'json');
	},
	load_logs: function (customer_id, options) {
		var $_adata = options || {};
		$_adata['customer_id'] = customer_id;
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_logs', $_adata, function (respJson) {
			$('.holder_activity_' + customer_id).html(respJson.html);
		}, 'json');
	},
	sw_activity: function (_this, e) {
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			tabid = $(_this).attr('tabid'),
			customer_id = $(_this).attr('customer_id');
		$('.' + tabid).removeClass('active');
		$(_this).addClass('active');
		if (tp == 'activity') {
			$Core.crm.load_activity(customer_id, {});
		} else if (tp == 'notes') {
			$Core.helper.load_list_notes(customer_id, 'Customer', {});
		} else if (tp == 'consulting') {
			$Core.crm.load_consulting(customer_id, {});
		} else if (tp == 'logs') {
			$Core.crm.load_logs(customer_id, {});
		}
		return false;
	},
	delete_activity: function (_this, e) {
		e.preventDefault();
		var followup_id = $(_this).attr('followup_id'),
			customer_id = $(_this).attr('customer_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function () {
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod=' + MOD + '&act=delete_activity', {
				'followup_id': followup_id,
				'customer_id': customer_id
			}, function (html) {
				$Core.util.toggleIndicatior(0);
				$Core.crm.load_activity(customer_id, {});
			});
		});
		return false;
	}, open_in_charge: (_this, e) => {
		e.preventDefault();
		var customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_in_charge', {
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, add_participant: function (_this, e) {
		e.preventDefault();
		var customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_participant', {
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, save_participant: function (_this, e) {
		e.preventDefault();
		var customer_id = $(_this).attr('customer_id'),
			_form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL + "/index.php?mod=" + MOD + "&act=pop_save_participant",
			data: { 'customer_id': customer_id },
			dataType: 'text',
			success: function (respJson) {
				$Core.util.toggleIndicatior(0);
				$('.btn-close', _form).trigger('click');
				$Core.alert.success('Thành công !!!');
			}
		});
		return false;
	},
	view_customer: function (customer_id, tabfocus, rollback = 1) {
		var tabfocus = tabfocus || 'summary',
			$_adata = { 'customer_id': customer_id, 'rollback': rollback };
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_customer', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			setTimeout(() => {
				if ($('.build[customer_id=' + customer_id + ']:not(.ajax)').length) {
					$('.build[customer_id=' + customer_id + ']:not(.ajax)').each(function () {
						var _this = $(this),
							_url = _this.data('url'),
							_params = _this.data('options') || {};
						$.post(_url, _params, function (respJson) {
							_this.addClass('ajax loaded').html(respJson.html);
							if (respJson.callback) {
								eval(respJson.callback);
							}
						}, 'json');
					});
				}
			}, 1000);
			setTimeout(() => {
				$Core.crm.load_activity(customer_id, {});
				$Core.crm.load_contact(customer_id, {});
				$Core.helper.load_list_notes(customer_id, 'Customer', {});
				$Core.crm.load_list_files({ billing_id: customer_id, customer_id: customer_id, for_id: customer_id, clsTable: 'Customer' });
				$Core.crm.load_list_need(customer_id, {});
			}, 1000);
		}, 'json');
	}, open_customer: (_this, e) => {
		e.preventDefault();
		e.stopPropagation();
		var $_this = $(_this),
			customer_id = $_this.attr('customer_id'),
			tabfocus = $_this.attr('tabfocus') || 'summary';
		if (!customer_id) { return false; }
		$Core.crm.view_customer(customer_id, tabfocus);
		return false;
	}, editInlineField: (_this, options) => {
		var $_adata = options || {},
			p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field'),
			p_cell = $(_this).closest('.InputCRMHandler');
		p_cell.html('<img src="' + URL_IMAGES + '/ripple-loading.svg" />');
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=edit_inline_field', $_adata, function (html) {
			p_cell.html(html);
			setTimeout(() => {
				$('.edit_customer_field_' + p_field + '_' + p_id).focus();
			}, 500);
		});
		return false;
	}, save_edit_field: (_this, e) => {
		e.preventDefault();
		var _body = $(_this).closest('.modal-body')
			, p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field'),
			p_cell = $(_this).closest('.InputCRMHandler'),
			p_value = $('.edit_customer_field_' + p_field + '_' + p_id, p_cell).val();
		//alert(p_value); return false;
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=edit_inline_field', {
			'p_id': p_id,
			'p_field': p_field,
			'p_value': p_value,
			'p_action': '_save'
		}, function (html) {
			$Core.util.toggleIndicatior(0);
			if (html.indexOf('_error_code') >= 0) {
				$Core.messager.alert('Thông báo', 'Mã nhân viên đã tồn tại !');
			} else if (html.indexOf('_error_email') >= 0) {
				$Core.messager.alert('Thông báo', 'Địa chỉ email này đã tồn tại !');
			} else {
				p_cell.html(html);
			}
		});
		return false;
	}, crm_task_result_change: (_this, e) => {
		e.preventDefault();
		var $sel = $(_this);
		var p_id = $sel.attr('p_id'),
			p_field = $sel.attr('p_field'),
			p_value = $sel.val();
		// V1 Phase 3c: result có need_datetime=1 → chặn inline, mở modal đặt giờ hẹn cho kết quả này.
		var $opt = $sel.find('option:selected');
		if (parseInt($opt.attr('data-need-datetime') || '0', 10) === 1) {
			var _ndt_rid = p_value;
			$sel.val('0'); // rollback dropdown về "Chọn kết quả"
			$Core.crm.crm_task_open_datetime(p_id, _ndt_rid);
			return false;
		}
		// V1 Phase 3: disable select trong khi POST in-flight → tránh race condition counter ladder (double-click / 2 tab).
		$sel.prop('disabled', true);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=crm_task_result_change', {
			'p_id': p_id,
			'p_field': p_field,
			'p_value': p_value,
			'p_action': '_save'
		}, function (respJson) {
			$sel.prop('disabled', false);
			if (respJson && parseInt(respJson.error, 10) === 0) {
				$('#task_next_' + p_id).html(respJson.task_next_html || '--');
				$('#waiting_time_' + p_id).html(respJson.waiting_time_html || '--');
				if (parseInt(respJson.is_meeting || 0, 10) === 1) {
					$Core.crm.open_activity(_this, e);
				}
			} else if (respJson && parseInt(respJson.error, 10) === 2 && parseInt(respJson.need_datetime || 0, 10) === 1) {
				// BE fallback: nếu FE bị bypass, BE trả error 2 → vẫn mở modal đặt giờ hẹn.
				$sel.val('0');
				$Core.crm.crm_task_open_datetime(p_id, p_value);
			} else {
				$Core.alert.error((respJson && respJson.message) ? respJson.message : 'Không tìm thấy rule tác nghiệp phù hợp.');
			}
		}, 'json').fail(function () { $sel.prop('disabled', false); });
		return false;
	}, crm_task_move_next: (_this, e) => {
		e.preventDefault();
		var customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=crm_task_move_next', {
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (respJson && parseInt(respJson.error, 10) === 0) {
				var _hasDesktopRow = $('#task_current_' + customer_id).length > 0; // có cell desktop = bảng; không có = mobile card
				var _$drawerNext = $(_this).closest('.crm-tl-next'); // promote từ drawer activity (dải "Bước tiếp theo")
				$('#task_current_' + customer_id).html(respJson.task_current_html || '--');
				$('#task_result_' + customer_id).html(respJson.task_result_html || '');
				$('#task_next_' + customer_id).html(respJson.task_next_html || '--');
				$('#waiting_time_' + customer_id).html(respJson.waiting_time_html || '--');
				if (_$drawerNext.length) {
					_$drawerNext.remove(); // hết tác nghiệp tiếp sau promote → bỏ dải trong drawer
					$Core.crm.load_activity(customer_id, {}); // refresh timeline drawer (log chuyển tác nghiệp)
				}
				if (!_hasDesktopRow) { $Core.crm.load_customers('_desktop', {}); } // mobile: reload card phản ánh tác nghiệp mới
			} else {
				$Core.alert.error((respJson && respJson.message) ? respJson.message : 'Không thể chuyển tác nghiệp.');
			}
		}, 'json');
		return false;
	}, crm_task_open_schedule: (_this, e) => {
		e.preventDefault();
		var customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=crm_task_open_schedule', {
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (respJson && parseInt(respJson.error, 10) === 0) {
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			} else {
				$Core.alert.error((respJson && respJson.message) ? respJson.message : 'Không mở được lịch tác nghiệp.');
			}
		}, 'json');
		return false;
	}, crm_task_save_schedule: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			_modal = $(_this).closest('.modal'),
			customer_id = $(_this).attr('customer_id'),
			task_time = $('input[name=task_time]', _form).val(),
			task_date = $('input[name=task_date]', _form).val();
		if ($Core.util.isEmpty(task_time) || $Core.util.isEmpty(task_date)) {
			$Core.alert.error('Vui lòng nhập đủ ngày giờ.');
			return false;
		}
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=crm_task_save_schedule', {
			'customer_id': customer_id,
			'task_time': task_time,
			'task_date': task_date
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (respJson && parseInt(respJson.error, 10) === 0) {
				$('#waiting_time_' + customer_id).html(respJson.waiting_time_html || '--');
				$('.btn-close,.closeEv', _modal).first().trigger('click');
			} else {
				$Core.alert.error((respJson && respJson.message) ? respJson.message : 'Không lưu được lịch tác nghiệp.');
			}
		}, 'json');
		return false;
	}, crm_task_open_datetime: (customer_id, result_id) => {
		// V1 Phase 3c: mở modal đặt giờ hẹn cho 1 kết quả need_datetime (tái dùng crm_task_open_schedule + result_id).
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=crm_task_open_schedule', {
			'customer_id': customer_id,
			'result_id': result_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (respJson && parseInt(respJson.error, 10) === 0) {
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			} else {
				$Core.alert.error((respJson && respJson.message) ? respJson.message : 'Không mở được giờ hẹn.');
			}
		}, 'json');
		return false;
	}, crm_task_save_datetime: (_this, e) => {
		// V1 Phase 3c: lưu giờ hẹn → gọi engine crm_task_result_change với p_datetime để resolve rule + set time.
		e.preventDefault();
		var _form = $(_this).closest('form'),
			_modal = $(_this).closest('.modal'),
			customer_id = $(_this).attr('customer_id'),
			result_id = $('input[name=result_id]', _form).val(),
			task_time = $('input[name=task_time]', _form).val(),
			task_date = $('input[name=task_date]', _form).val();
		if ($Core.util.isEmpty(task_time) || $Core.util.isEmpty(task_date) || $Core.util.isEmpty(result_id)) {
			$Core.alert.error('Vui lòng nhập đủ ngày giờ.');
			return false;
		}
		var $btn = $(_this);
		$btn.prop('disabled', true); // chống double-submit → tránh tạo follow-up trùng
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=crm_task_result_change', {
			'p_id': customer_id,
			'p_field': 'task_result_id',
			'p_value': result_id,
			'p_datetime': task_date + ' ' + task_time + ':00',
			'p_action': '_save'
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$btn.prop('disabled', false);
			if (respJson && parseInt(respJson.error, 10) === 0) {
				var _hasDesktopRow = $('#task_next_' + customer_id).length > 0; // có cell desktop = bảng; không có = mobile/drawer
				$('#task_next_' + customer_id).html(respJson.task_next_html || '--');
				$('#waiting_time_' + customer_id).html(respJson.waiting_time_html || '--');
				$('select[customer_id="' + customer_id + '"][p_field="task_result_id"]').val(result_id);
				$('.btn-close,.closeEv', _modal).first().trigger('click');
				$Core.alert.success('Đã đặt giờ hẹn');
				if (!_hasDesktopRow) { $Core.crm.load_customers('_desktop', {}); } // mobile: reload list → card cập nhật bước tiếp/giờ
			} else {
				$Core.alert.error((respJson && respJson.message) ? respJson.message : 'Không đặt được giờ hẹn.');
			}
		}, 'json').fail(function () { $Core.util.toggleIndicatior(0); $btn.prop('disabled', false); });
		return false;
	}, crm_task_result_sheet: (_this, e) => {
		// Mobile/drawer: mở sheet chọn kết quả tác nghiệp (card .cmc + drawer không có ô Kết quả như bảng desktop).
		e.preventDefault();
		var customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=crm_task_result_sheet', {
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (respJson && parseInt(respJson.error, 10) === 0) {
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid, 'crm-rsheet-modal');
			} else {
				$Core.alert.error((respJson && respJson.message) ? respJson.message : 'Không mở được danh sách kết quả.');
			}
		}, 'json');
		return false;
	}, crm_task_result_pick: (_this, e) => {
		// Tap 1 kết quả trong sheet → engine crm_task_result_change. need_datetime → đóng sheet, mở modal đặt giờ.
		e.preventDefault();
		var $btn = $(_this),
			customer_id = $btn.attr('customer_id'),
			result_id = $btn.attr('data-result-id'),
			need_datetime = parseInt($btn.attr('data-need-datetime') || '0', 10),
			$modal = $btn.closest('.modal');
		if (need_datetime === 1) {
			$('.btn-close,.closeEv', $modal).first().trigger('click');
			$Core.crm.crm_task_open_datetime(customer_id, result_id);
			return false;
		}
		$btn.prop('disabled', true); // chống double-tap → tránh đếm counter 2 lần
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=crm_task_result_change', {
			'p_id': customer_id,
			'p_field': 'task_result_id',
			'p_value': result_id,
			'p_action': '_save'
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$btn.prop('disabled', false);
			if (respJson && parseInt(respJson.error, 10) === 0) {
				$('.btn-close,.closeEv', $modal).first().trigger('click');
				$Core.alert.success('Đã ghi kết quả tác nghiệp');
				$Core.crm.load_customers('_desktop', {}); // reload list → card phản ánh status/bước tiếp/Lần N mới
			} else if (respJson && parseInt(respJson.error, 10) === 2 && parseInt(respJson.need_datetime || 0, 10) === 1) {
				$('.btn-close,.closeEv', $modal).first().trigger('click');
				$Core.crm.crm_task_open_datetime(customer_id, result_id);
			} else {
				$Core.alert.error((respJson && respJson.message) ? respJson.message : 'Không ghi được kết quả.');
			}
		}, 'json').fail(function () { $Core.util.toggleIndicatior(0); $btn.prop('disabled', false); });
		return false;
	}, cancel_edit_field: (_this, e) => {
		e.preventDefault();
		var p_id = $(_this).attr('p_id'),
			p_field = $(_this).attr('p_field'),
			p_cell = $(_this).closest('.InputCRMHandler');
		p_cell.html('<img src="' + URL_IMAGES + '/ripple-loading.svg" />');
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=edit_inline_field', {
			'p_id': p_id,
			'p_field': p_field,
			'p_action': '_cancel'
		}, function (html) {
			p_cell.html(html);
		});
		return false;
	}, open_contact: (_this, e) => {
		e.preventDefault();
		var contact_id = $(_this).attr('contact_id'),
			customer_id = $(_this).attr('customer_id');
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_contact', {
			'contact_id': contact_id,
			'customer_id': customer_id
		}, function (respJson) {
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$('input[name=phone]').change(function () {
				var _phone = $(this).val();
				if (!$Core.util.isEmpty(_phone)) {
					$(this).val(_phone.replace(/\s+/, ''));
				}
			});
		}, 'json');
		return false;
	},
	save_contact: function (_this, e) {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			contact_id = $(_this).attr('contact_id'),
			customer_id = $(_this).attr('customer_id'),
			$_adata = { 'contact_id': contact_id, 'customer_id': customer_id };
		if ($('select.required,input.required', _form).length) {
			$('select.required,input.required', _form).each((_i, _elem) => {
				if ($Core.util.isEmpty($(_elem).val())) {
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if (_validated == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + "/index.php?mod=" + MOD + "&act=save_contact",
				data: $_adata,
				dataType: 'json',
				success: function (respJson) {
					$Core.util.toggleIndicatior(0);
					if (respJson.msg.indexOf('_success') >= 0) {
						$Core.crm.load_contact(customer_id, {});
						$('.btn-close', _form).trigger('click');
					} else if (respJson.msg.indexOf('_error') >= 0) {
						$Core.swal.error("Oops", "Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					} else if (respJson.msg.indexOf('_duplicated') >= 0) {
						$Core.swal.error("Oops", "Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại");
					}
				}
			});
		}
		return false;
	},
	delete_contact: function (_this, e) {
		e.preventDefault();
		var contact_id = $(_this).attr('contact_id'),
			customer_id = $(_this).attr('customer_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function () {
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod=' + MOD + '&act=delete_contact', {
				'contact_id': contact_id,
				'customer_id': customer_id
			}, function (respJson) {
				$Core.util.toggleIndicatior(0);
				$Core.crm.load_contact(customer_id, {});
			}, 'json');
		});
		return false;
	},
	load_contact: function (customer_id, options) {
		var $_adata = options || {};
		$_adata['customer_id'] = customer_id
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_contact', $_adata, function (respJson) {
			$('.holder_contact_' + customer_id).html(respJson.html);
		}, 'json');
	},
	open_followups: function (_this, e) {
		e.preventDefault();
		e.stopPropagation();
		var followup_id = $(_this).attr('followup_id'),
			customer_id = $(_this).attr('customer_id');
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_followups', {
			'followup_id': followup_id,
			'customer_id': customer_id
		}, function (respJson) {
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	save_followups: function (_this, e) {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			followup_id = $(_this).attr('followup_id'),
			customer_id = $(_this).attr('customer_id');
		if ($('select.required,input.required', _form).length) {
			$('select.required,input.required', _form).each((_i, _elem) => {
				if ($Core.util.isEmpty($(_elem).val())) {
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if (_validated == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + "/index.php?mod=" + MOD + "&act=save_followups",
				data: { 'followup_id': followup_id, 'customer_id': customer_id },
				success: function (html) {
					$Core.util.toggleIndicatior(0);
					if (html.indexOf('_success') >= 0) {
						$Core.crm.load_followups(customer_id, {});
						$('#desktop_calendar').fullCalendar("refetchEvents");
						$('.btn-close', _form).trigger('click');
					} else if (html.indexOf('_error') >= 0) {
						$Core.swal.error("Oops", "Quá trình đăng bản tin bị lỗi. Xin vui lòng thử lại");
					} else if (html.indexOf('_duplicated') >= 0) {
						$Core.swal.error("Oops", "Tiêu đề bản tin đã tồn tại. Xin vui lòng thử lại");
					}
				}
			});
		}
		return false;
	}, delete_followups: (_this, e) => {
		e.preventDefault();
		var contact_id = $(_this).attr('contact_id'),
			customer_id = $(_this).attr('customer_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function () {
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod=' + MOD + '&act=delete_followups', {
				'contact_id': contact_id,
				'customer_id': customer_id
			}, function (respJson) {
				$Core.util.toggleIndicatior(0);
				$Core.crm.load_followups(customer_id, {});
			}, 'json');
		});
		return false;
	}, open_file: (_this, e) => {
		e.preventDefault();
		var file_id = $(_this).attr('file_id'),
			customer_id = $(_this).attr('for_id');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_file', {
			'file_id': file_id,
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$('#attachments_' + respJson.uid).MultiFile({
				list: '#MultiFile-preview_' + respJson.uid
			});
		}, 'json');
		return false;
	}, delete_file: (_this, e) => {
		e.preventDefault();
		var file_id = $(_this).attr('file_id'),
			customer_id = $(_this).attr('for_id');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function () {
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod=' + MOD + '&act=delete_file', {
				'file_id': file_id,
				'customer_id': customer_id
			}, function (html) {
				$Core.util.toggleIndicatior(0);
				if (html.indexOf('_success') >= 0) {
					$Core.helper.load_list_files(customer_id, 'Profile', {});
				} else {
					$Core.swal.error("Oops", "Quá trình xóa bị lỗi. Xin vui lòng thử lại");
				}
			});
		});
	}, save_file: (_this, e) => {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			file_id = $(_this).attr('file_id'),
			customer_id = $(_this).attr('customer_id');
		if ($('input.required,textarea.required', _form).length) {
			$('input.required,textarea.required', _form).each((_i, _elem) => {
				if ($Core.util.isEmpty($(_elem).val())) {
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if (_validated == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + '/index.php?mod=' + MOD + '&act=save_file',
				data: { 'file_id': file_id, 'customer_id': customer_id },
				dataType: 'html',
				success: function (html) {
					$Core.util.toggleIndicatior(0);
					if (html.indexOf('_success') >= 0) {
						_form[0].reset();
						$Core.member.load_list_files(customer_id, 'Customer', {});
						$('.btn-close', _form).trigger('click');
					} else {
						$Core.messager.alert("Error!", 'Upload failed !');
					}
				}
			});
		}
		return false;
	}, open_import: (_this, e) => {
		e.preventDefault();
		toggleIndicatior(1);
		$.ajax({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=open_import',
			dataType: 'json',
			success: function (respJson) {
				toggleIndicatior(0);
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			}
		});
		return false;
	}, do_export: () => {
		var type = $_this.attr('type'),
			user_id = $_this.attr('u'),
			url = PCMS_URL + '/inc/export.php?t=CRM&type=' + type + '&u=' + user_id + '&secure=' + EXPORT_KEY,
			wd = window.open(url, '_blank');
		setTimeout(function () { wd.close(); }, 5000);
	}, preview_import: function (_this, e) {
		e.preventDefault();
		var $btn = $(_this),
			$form = $btn.closest('form'),
			boxId = $btn.attr('data-preview'),
			file_id = $form.find('[name=file_id]').val() || '',
			spreadsheetId = $form.find('[name=spreadsheetId]').val() || '';
		if (!file_id && !spreadsheetId) { $Core.alert.error('Hãy chọn file và cấu hình cột (nút ⚙) trước khi xem trước.'); return false; }
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=preview_import', { file_id: file_id, spreadsheetId: spreadsheetId }, function (resp) {
			$Core.util.toggleIndicatior(0);
			if (resp && resp.html) { $('#' + boxId).html(resp.html); }
		}, 'json');
		return false;
	}, do_import: function (_this, e) {
		e.preventDefault();
		var $_form = $(_this).closest('form');
		$Core.util.toggleIndicatior(1);
		$_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=do_import_customer',
			dataType: 'html',
			success: function (html) {
				$Core.util.toggleIndicatior(0);
				var htm = html.split('$$$');
				$Core.alert.success('Imported success(s) +' + htm[1] + ', duplicate error(s) +' + htm[2]);
				setTimeout(() => { window.location.reload(); }, 3000);
			}
		});
		return false;
	}, open_setting: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod=' + MOD + '&act=load_setting', {}, function (html) {
			$Core.util.toggleIndicatior(0);
			var tmp = html.split('|||');
			$('#app').html(tmp[0]);
			if ($('.js_crm-cronjob-status').is(':checked')) {
				$('.tr_cron-setting').removeClass('hidden');
			}
			var list_property_array = tmp[1].split('|');
			list_property_array.forEach((property_type) => {
				$Core.crm.load_setting_property(property_type, { 'loading': 0 });
			});
		});
		return false;
	}, open_task_setting: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.get(path_ajax_script + '/index.php?mod=' + MOD + '&act=open_task_setting', {}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open(0, 0, respJson.html, respJson.uid, 'crm-ts-modal');
			$('#' + respJson.uid).on('shown.bs.modal', function () {
				$Core.crm.load_task_setting_list(respJson.uid);
			});
		}, 'json');
		return false;
	}, crm_task_result_options: (modal_id, task_id, selected_id, selected_title) => {
			var _modal = $('#' + modal_id);
			var _body = $('.crm-ts', _modal);
			var map = {};
			try { map = JSON.parse(_body.attr('data-results') || '{}'); } catch (err) { map = {}; }
			var list = map[task_id] || [];
			var html = '<option value="">Chọn / gõ kết quả…</option>';
			var found = false;
			$.each(list, function (i, r) {
				var sel = (String(r.id) === String(selected_id)) ? ' selected' : '';
				if (sel) { found = true; }
				html += '<option value="' + r.id + '" data-counter="' + (r.is_counter ? 1 : 0) + '" data-need-datetime="' + (r.need_datetime ? 1 : 0) + '" data-status="' + (r.customer_status_id || 0) + '"' + sel + '>' + $('<span>').text(r.title).html() + '</option>';
			});
			if (!found && selected_id && selected_title) {
				html += '<option value="' + selected_id + '" selected>' + $('<span>').text(selected_title).html() + '</option>';
			}
			return html;
		}, crm_task_form_fill_results: (_form, task_id, selected_id, selected_title) => {
			var _modal = _form.closest('.modal');
			var html = $Core.crm.crm_task_result_options(_modal.attr('id'), task_id, selected_id, selected_title);
			$('.js__crm-task-form-result', _form).html(html);
		}, crm_task_toggle_when: (_form) => {
			var when = $('.js__crm-task-when:checked', _form).val();
			$('.js__crm-task-form-delay', _form).prop('disabled', when === 'datetime');
		}, crm_task_update_summary: (_form) => {
			var taskTxt = $('.js__crm-task-form-task option:selected', _form).text().trim();
			var resVal = $('.js__crm-task-form-result', _form).val();
			var resTxt = resVal ? $('.js__crm-task-form-result option:selected', _form).text().trim() : '…';
			var nextTxt = $('.js__crm-task-form-next option:selected', _form).text().trim();
			var when = $('.js__crm-task-when:checked', _form).val();
			var whenTxt = (when === 'datetime') ? 'cần lịch hẹn' : ('sau ' + ($('.js__crm-task-form-delay option:selected', _form).text().trim() || 'Ngay'));
			var html;
			if (taskTxt && taskTxt !== 'Chọn tác nghiệp') {
				html = '<b>NẾU</b> ' + taskTxt + ' · ' + (resTxt || '…') + ' <i class="bx bx-right-arrow-alt"></i> <b>THÌ</b> ' + (nextTxt && nextTxt !== 'Chọn tác nghiệp tiếp' ? nextTxt : '…') + ' · ' + whenTxt;
			} else {
				html = '<span class="text-muted">Chọn tác nghiệp đầu để bắt đầu…</span>';
			}
			$('.js__crm-task-summary', _form).html(html);
		}, crm_task_bind_form: (_form) => {
			var _modal = _form.closest('.modal');
			var _rs = $('.js__crm-task-form-result', _form);
			if (_rs.data('select2')) { try { _rs.select2('destroy'); } catch (err) {} }
			_rs.select2({ tags: true, width: '100%', dropdownParent: _form, placeholder: 'Chọn / gõ kết quả…', language: { noResults: function () { return 'Gõ rồi Enter để tạo kết quả mới'; } } });
			_rs.off('change.crmts').on('change.crmts', function () {
				var v = String($(this).val() || '');
				if (/^[0-9]+$/.test(v)) {
					var o = $(this).find('option:selected');
					$('.js__crm-task-form-counter', _form).prop('checked', (parseInt(o.data('counter'), 10) || 0) === 1);
					$('.js__crm-task-form-status', _form).val(String(o.data('status') || '0'));
					if ((parseInt(o.data('need-datetime'), 10) || 0) === 1) {
						$('.js__crm-task-when[value="datetime"]', _form).prop('checked', true);
					} else {
						$('.js__crm-task-when[value="delay"]', _form).prop('checked', true);
					}
					$Core.crm.crm_task_toggle_when(_form);
				}
				$Core.crm.crm_task_update_summary(_form);
			});
			$('.js__crm-task-form-task', _form).off('change.crmts').on('change.crmts', function () {
				$Core.crm.crm_task_form_fill_results(_form, $(this).val(), '', '');
				$Core.crm.crm_task_bind_form(_form);
				$Core.crm.crm_task_update_summary(_form);
			});
			$('.js__crm-task-form-next, .js__crm-task-form-delay', _form).off('change.crmts').on('change.crmts', function () {
				$Core.crm.crm_task_update_summary(_form);
			});
			$('.js__crm-task-when', _form).off('change.crmts').on('change.crmts', function () {
				$Core.crm.crm_task_toggle_when(_form);
				$Core.crm.crm_task_update_summary(_form);
			});
		}, crm_task_open_form: (_this, e) => {
			e.preventDefault();
			var _modal = $(_this).closest('.modal');
			var _form = $('.js__crm-task-form', _modal);
			var task_id = $(_this).data('task') || '';
			$('.js__crm-task-form-id', _form).val(0);
			$('.js__crm-task-form-task', _form).val(String(task_id));
			$Core.crm.crm_task_form_fill_results(_form, task_id, '', '');
			$('.js__crm-task-form-next', _form).val('');
			$('.js__crm-task-form-delay', _form).prop('disabled', false).val('0|minute');
			$('.js__crm-task-form-counter', _form).prop('checked', false);
			$('.js__crm-task-form-active', _form).prop('checked', true);
			$('.js__crm-task-form-status', _form).val('0');
			$('.js__crm-task-when[value="delay"]', _form).prop('checked', true);
			$('.js__crm-task-form-title', _form).text('Thêm luật tác nghiệp');
			_form.closest('.js__crm-task-popup').removeClass('d-none');
			$Core.crm.crm_task_bind_form(_form);
			$Core.crm.crm_task_update_summary(_form);
			return false;
		}, crm_task_edit: (_this, e) => {
			e.preventDefault();
			var _btn = $(_this);
			var _modal = _btn.closest('.modal');
			var _form = $('.js__crm-task-form', _modal);
			var task_id = String(_btn.data('task') || '');
			var result_id = String(_btn.data('result') || '');
			var need_dt = parseInt(_btn.data('need-datetime'), 10) || 0;
			$('.js__crm-task-form-id', _form).val(_btn.data('id') || 0);
			$('.js__crm-task-form-task', _form).val(task_id);
			$Core.crm.crm_task_form_fill_results(_form, task_id, result_id, _btn.data('result-title') || '');
			$('.js__crm-task-form-next', _form).val(String(_btn.data('next') || ''));
			$('.js__crm-task-form-counter', _form).prop('checked', (parseInt(_btn.data('counter'), 10) || 0) === 1);
			$('.js__crm-task-form-status', _form).val(String(_btn.data('status') || '0'));
			$('.js__crm-task-form-active', _form).prop('checked', (parseInt(_btn.data('active'), 10) || 0) !== 0);
			if (need_dt) {
				$('.js__crm-task-when[value="datetime"]', _form).prop('checked', true);
				$('.js__crm-task-form-delay', _form).prop('disabled', true);
			} else {
				$('.js__crm-task-when[value="delay"]', _form).prop('checked', true);
				$('.js__crm-task-form-delay', _form).prop('disabled', false).val(_btn.data('delay') || '0|minute');
			}
			$('.js__crm-task-form-title', _form).text('Sửa luật tác nghiệp');
			_form.closest('.js__crm-task-popup').removeClass('d-none');
			$Core.crm.crm_task_bind_form(_form);
			$Core.crm.crm_task_update_summary(_form);
			return false;
		}, crm_task_form_cancel: (_this, e) => {
			e.preventDefault();
			var _form = $(_this).closest('.js__crm-task-form');
			_form.closest('.js__crm-task-popup').addClass('d-none');
			$('.js__crm-task-form-id', _form).val(0);
			return false;
		}, crm_task_save: (_this, e) => {
			e.preventDefault();
			var _modal = $(_this).closest('.modal');
			var _form = $('.js__crm-task-form', _modal);
			var modal_id = _modal.attr('id');
			var id = parseInt($('.js__crm-task-form-id', _form).val(), 10) || 0;
			var task_id = $('.js__crm-task-form-task', _form).val();
			var _rs = $('.js__crm-task-form-result', _form);
			var result_val = String(_rs.val() || '');
			var result_id = 0;
			var result_title = '';
			if (/^[0-9]+$/.test(result_val)) {
				result_id = parseInt(result_val, 10);
			} else {
				result_title = result_val.trim();
			}
			var next_task_id = $('.js__crm-task-form-next', _form).val();
			var when = $('.js__crm-task-when:checked', _form).val();
			var need_datetime = (when === 'datetime') ? 1 : 0;
			var delay_combo = need_datetime ? '' : ($('.js__crm-task-form-delay', _form).val() || '0|minute');
			var is_counter = $('.js__crm-task-form-counter', _form).is(':checked') ? 1 : 0;
			var customer_status_id = parseInt($('.js__crm-task-form-status', _form).val(), 10) || 0;
			var is_active = $('.js__crm-task-form-active', _form).is(':checked') ? 1 : 0;
			if (!task_id || (!result_id && !result_title) || !next_task_id) {
				$Core.alert.error('Vui lòng chọn tác nghiệp đầu, kết quả và tác nghiệp tiếp.');
				return false;
			}
			$Core.util.toggleIndicatior(1);
			$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=save_crm_task_setting', {
				'id': id,
				'task_id': task_id,
				'result_id': result_id,
				'result_title': result_title,
				'next_task_id': next_task_id,
				'delay_combo': delay_combo,
				'need_datetime': need_datetime,
				'is_counter': is_counter,
				'customer_status_id': customer_status_id,
				'is_active': is_active
			}, function (respJson) {
				$Core.util.toggleIndicatior(0);
				if (respJson && respJson.error == 0) {
					_form.closest('.js__crm-task-popup').addClass('d-none');
					$('.js__crm-task-form-id', _form).val(0);
					$Core.alert.success('Đã lưu.');
					$Core.crm.load_task_setting_list(modal_id);
				} else {
					$Core.alert.error((respJson && respJson.message) ? respJson.message : 'Có lỗi xảy ra, vui lòng thử lại.');
				}
			}, 'json').fail(function () {
				$Core.util.toggleIndicatior(0);
				$Core.alert.error('Lỗi mạng, chưa lưu được. Vui lòng thử lại.');
			});
			return false;
		}, crm_task_delete: (_this, e) => {
			e.preventDefault();
			var _btn = $(_this);
			var _modal = _btn.closest('.modal');
			var modal_id = _modal.attr('id');
			var _id = _btn.data('id');
			if (!_id) {
				return false;
			}
			$Core.messager.confirm('Xác nhận', 'Xoá luật này?', function () {
				$Core.util.toggleIndicatior(1);
				$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=delete_crm_task_setting', {
					'id': _id
				}, function (respJson) {
					$Core.util.toggleIndicatior(0);
					if (respJson && respJson.error == 0) {
						$Core.alert.success('Đã xoá.');
						$Core.crm.load_task_setting_list(modal_id);
					} else {
						$Core.alert.error(respJson && respJson.message ? respJson.message : 'Có lỗi xảy ra.');
					}
				}, 'json');
			});
			return false;
		}, load_task_setting_list: (modal_id) => {
			var _modal = $('#' + modal_id);
			if (!_modal.length) {
				return false;
			}
			var _tbody = $('.js__crm-task-table-body', _modal);
			$Core.util.toggleIndicatior(1);
			$.get(path_ajax_script + '/index.php?mod=' + MOD + '&act=load_crm_task_setting', {}, function (respJson) {
				$Core.util.toggleIndicatior(0);
				if (respJson && respJson.error == 0) {
					_tbody.html(respJson.html || '');
				} else {
					$Core.alert.error(respJson && respJson.message ? respJson.message : 'Không tải được dữ liệu.');
				}
			}, 'json');
			return false;
		}, save_setting: (_this, e) => {
		e.preventDefault();
		var $_this = $(this),
			action = '_general',
			$_form = $_this.closest('form');
		if ($_this.hasClass('saveSettingAuthCRM')) {
			action = '_auth';
		}
		$Core.util.toggleIndicatior(1);
		$_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=save_setting',
			data: { 'action': action },
			dataType: 'html',
			success: function (html) {
				$Core.util.toggleIndicatior(0);
				$Core.alert.success('Lưu thành công !');
			}
		});
		return false;
	}, load_setting_property: (property_type, options) => {
		var $_adata = options || {},
			loading = $_adata.hasOwnProperty('loading') ? $_adata.loading : 1;
		$_adata['property_type'] = property_type;
		$Core.util.toggleIndicatior(loading);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_propery', $_adata, function (html) {
			$Core.util.toggleIndicatior(0);
			$('.holder_setting_property_' + property_type).html(html);
			$(".tbody_setting_property_" + property_type).sortable({
				connectWith: ".tbody_setting_property_" + property_type,
				handle: ".mySortableHandler",
				update: function (event, ui) {
					var list_ids = $(this).sortable('toArray'),
						$_adata = { "list_ids": list_ids, 'property_type': property_type };
					$.post(PCMS_URL + "/index.php?mod=" + MOD + "&act=sync_order_property", $_adata, function (html) { });
				}
			}).disableSelection();
		});
	}, delete_property: (_this, e) => {
		var property_id = $(_this).attr('property_id'),
			property_type = $(_this).attr('property_type');
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function () {
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod=' + MOD + '&act=delete_property', {
				'property_id': property_id,
				'property_type': property_type
			}, function (html) {
				$Core.util.toggleIndicatior(0);
				if (html.indexOf('_success') >= 0) {
					$Core.crm.load_setting_property(property_type, {});
				} else {
					$Core.swal.error("Oops", "Quá trình xóa bị lỗi. Xin vui lòng thử lại");
				}
			});
		});
	},
	/** Thống kê */
	loadChartCrmResource: function (options) {
		var $_adata = options || {};
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=loadDataChartCrmResource', $_adata, function (barChartData) {
			$Core.util.toggleIndicatior(0);
			$Core.chart.canvas('ChartCrmResource', barChartData);
		}, 'json');
	}, loadChartCrmStatus: function (options) {
		var $_adata = options || {};
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=loadDataChartCrmStatus', $_adata, function (barChartData) {
			$Core.util.toggleIndicatior(0);
			$Core.chart.canvas('ChartCrmStatus', barChartData);
		}, 'json');
	}, loadDataChartCRMContactByClient: function (options) {
		var $_adata = options || {};
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=loadDataChartCRMCustomerAssign', $_adata, function (barChartData) {
			$Core.util.toggleIndicatior(0);
			$Core.chart.canvas('ChartStatisticCRMContactAssignedClient', barChartData);
		}, 'json');
	}, loadDataChartCRMContactInYear: function (year) {
		$Core.util.toggleIndicatior(1);
		$.ajax({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=loadDataChartCRMCustomerInYear',
			data: { 'year': year },
			dataType: 'json',
			success: function (respJson) {
				$Core.util.toggleIndicatior(0);
				$('#ListStatisticCRMContactInYear').html(respJson.htmlList);
				$Core.chart.canvas('ChartStatisticCRMContactInYear', respJson.barChartData);
				setTimeout(() => {
					const verticalExample = document.getElementById('ListStatisticCRMContactInYear');
					if (verticalExample) {
						new PerfectScrollbar(verticalExample, {
							wheelPropagation: false
						});
					}
				}, 1000);
			}
		});
	},
	/** End thống kê */
	hide_help: function (_this, e) {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod=' + MOD + '&act=hide_help', {}, function (html) {
			$Core.util.toggleIndicatior(0);
		});
		return false;
	},
	do_crm_search: function (_this, e) {
		var params = {};
		if ($('.search_report_field').length) {
			$('.search_report_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				params[field] = $(_elem).val();
			});
		}
		$Core.crm.load_cell_crm_report(params);
		$Core.crm.load_customers('_report', params);
	},
	get_select_staff: function (_this, e) {
		var params = {},
			department_id = $(_this).val();
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod=' + MOD + '&act=get_select_staff', {
			'department_id': department_id
		}, function (html) {
			$Core.util.toggleIndicatior(0);
			$('select[name=staff_id]').html(html).prop('null');
			if ($('.search_report_field').length) {
				$('.search_report_field').each((_i, _elem) => {
					var field = $(_elem).data('field');
					params[field] = $(_elem).val();
				});
			}
			$Core.crm.load_cell_crm_report(params);
			$Core.crm.load_customers('_report', params);
		});
	},
	load_cell_crm_report: function (options) {
		var $_adata = options || {};
		if ($('.tableReportSummaryStaff').length) {
			$('.tableReportSummaryStaff').each((_i, _elem) => {
				var _url = $(_elem).data('url'),
					_delay = $(_elem).data('delay');
				setTimeout(() => {
					$.post(_url, $_adata, function (respJson) {
						$(_elem).html(respJson.html);
						$('.total_cell_' + respJson.cell).text(respJson.total);
						$('.percent_cell_' + respJson.cell).text(respJson.percent + '%');
					}, 'json');
				}, _delay);
			});
		}
	},
	timer_click: function (_this, e) {
		e.preventDefault();
		var time_type = $(_this).data('time');
		$('input[name=time_type]').val(time_type);
		$('.js_choose-time').removeClass('active');
		$(_this).addClass('active');
		var params = {};
		if ($('.search_report_field').length) {
			$('.search_report_field').each((_i, _elem) => {
				var field = $(_elem).data('field');
				params[field] = $(_elem).val();
			});
		}
		$Core.crm.load_cell_crm_report(params);
		$Core.crm.load_customers('_report', params);
		return false;
	},
	change_assigned: function (_this, e) {
		e.preventDefault();
		e.stopPropagation();
		var customer_id = $(_this).attr('customer_id');
		$Core.util.toggleIndicatior(1);
		$.post('/index.php?mod=' + MOD + '&act=open_change_assigned', {
			'customer_id': customer_id
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	do_change_assigned: function (_this, e) {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			customer_id = $(_this).attr('customer_id'),
			admin_id = $('select[name=admin_id]', _form).val();
		if (!$Core.util.isEmpty(admin_id)) {
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod=' + MOD + '&act=do_change_assigned', {
				'admin_id': admin_id,
				'customer_id': customer_id
			}, function (html) {
				$Core.util.toggleIndicatior(0);
				$('.btn-close', _form).trigger('click');
				$Core.crm.load_customers('_desktop', {});
			});
			return false;
		}
	},
	set_archived: function (_this, e) {
		e.preventDefault();
		var customer_id = $(_this).attr('customer_id');
		$.post('/index.php?mod=' + MOD + '&act=set_archived', {
			'customer_id': customer_id
		}, function (html) {
			$Core.util.toggleIndicatior(0);
			$Core.crm.load_customers('_desktop', {});
		});
		return false;
	},
	open_help: function (_this, e) {
		e.preventDefault();
		$.post('/index.php?mod=' + MOD + '&act=open_help', {}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	loadStaff: function (_this, e) {
		e.preventDefault();
		var department_id = $(_this).val(),
			toId = $(_this).attr("toId");
		$.post('/index.php?mod=' + MOD + '&act=loadStaff', { "department_id": department_id }, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$("#" + toId).html(respJson.html);
			var _item = $(_this).closest(".dashboard-panel-item-all");
			$(".ajax.loaded", _item).removeClass("loaded");
			_autoload()
		}, 'json');
		return false;
	},
	reload: function (_this, e) {
		e.preventDefault();
		var _item = $(_this).closest(".dashboard-panel-item"),
			_item_all = $(_this).closest(".dashboard-panel-item-all");
		var options = {},
			gId = $(_this).attr('gId'),
			name = $(_this).attr('name');
		if (name == 'date_type') {
			var date_type = $(_this).val(),
				year = $("select[name='year'][gId='" + gId + "']").val();
			$.post(PCMS_URL + '/index.php?mod=home&sub=dashboard&act=load_month', {
				'date_type': date_type, 'year': year
			}, function (html) {
				$('select[name=month][gId=' + gId + ']').html(html);
				var params = $.extend(options, { 'gId': gId });
				if ($('select[gId=' + gId + '],input[gId=' + gId + ']').length) {
					$('select[gId=' + gId + '],input[gId=' + gId + ']').each((_i, _elem) => {
						var p_name = $(_elem).attr('name'),
							p_value = $(_elem).val();
						if (!$Core.util.isEmptyZero(p_value)) {
							params[p_name] = p_value;
						}
					});
				}
				$('.ajax[gId=' + gId + ']').data('options', params);
				$('.ajax[gId=' + gId + ']').removeClass('loaded');
			});
		} else if (name == 'year') {
			var date_type = '_month',
				year = $(_this).val();
			if ($('select[name=date_type][gId=' + gId + ']').length) {
				date_type = $('select[name=date_type][gId=' + gId + ']').val();
			}
			if (date_type == '_quater' || date_type == '_half') {
				var params = $.extend(options, { 'gId': gId });
				if ($('select[gId=' + gId + '],input[gId=' + gId + ']').length) {
					$('select[gId=' + gId + '],input[gId=' + gId + ']').each((_i, _elem) => {
						var p_name = $(_elem).attr('name'),
							p_value = $(_elem).val();
						if (!$Core.util.isEmptyZero(p_value)) {
							params[p_name] = p_value;
						}
					});
				}
				$('.ajax[gId=' + gId + ']').data('options', params);
				$('.ajax[gId=' + gId + ']').removeClass('loaded');
			} else {
				$.post(PCMS_URL + '/index.php?mod=home&sub=dashboard&act=load_month', {
					'date_type': date_type,
					'year': year,
				}, function (html) {
					$('select[name=month][gId=' + gId + ']').html(html);
					if ($('select[name=quarter][gId=' + gId + ']').length) {
						$('select[name=quarter][gId=' + gId + ']').val("");
					}
					var params = $.extend(options, { 'gId': gId });
					if ($('select[gId=' + gId + '],input[gId=' + gId + ']').length) {
						$('select[gId=' + gId + '],input[gId=' + gId + ']').each((_i, _elem) => {
							var p_name = $(_elem).attr('name'),
								p_value = $(_elem).val();
							if (!$Core.util.isEmptyZero(p_value)) {
								params[p_name] = p_value;
							}
						});
					}
					$('.ajax[gId=' + gId + ']').data('options', params);
					$('.ajax[gId=' + gId + ']').removeClass('loaded');
				});
			}
		}
		if (_item_all.length > 0) {
			$(".ajax.loaded", _item_all).removeClass("loaded");
		} else {
			$(".ajax.loaded", _item).removeClass("loaded");
		}
		_autoload()
	},
	reloadAll: function (_this, e) {
		e.preventDefault();
		var _target = $(_this).attr("data-bs-target");
		$(_target).find(".ajax.loaded").removeClass("loaded");
		_autoload()
	},
	add_setting_field: function (_this, e) {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			title = $(_this).data("title"),
			toId = $(_this).attr("toId"),
			id = $(_this).attr("id"),
			key = $(_this).data("key"),
			limit = parseInt($("input[name=limit]", _form).val());
		if ($(_this).is(":checked")) {
			if ($(".item_field_selected", _form).length >= limit) {
				$(_this).prop("checked", false);
				alertify.error(`Tối đa cho phép ${limit} trường`);
				return false;
			}
			if ($(".item_field_selected", _form).length == 0) {
				$(".list_field-selected", _form).html(``);
			}
			$(".list_field-selected", _form).append(`<li id="${toId}" class="item_field_selected d-flex justify-content-between align-items-center p-2 bg-lighter rounded-1 mb-2 text-black cursor-pointer" title="${title}" key="${key}">
				<div class="crm-flex filed-select">
					<i class='bx bx-grid-vertical'></i>
					<span class="title-ellipsis text misa-label">${title}</span>
				</div>
				<button class="btn btn-sm text-main p-0" type="button" onClick="$Core.crm.delete_setting_field(this,event)" toId="${id}">
					<i class='bx bx-x'></i></button>
			</li>`);
		} else {
			$("#" + toId, _form).remove();
		}
		$(".text_number", _form).text($(".item_field_selected", _form).length)
	},
	setting_field: function (_this, e) {
		e.preventDefault();
		var action = $(_this).attr('action'),
			view_by = $(_this).attr('view_by'),
			field_name = $(_this).attr('field_name');
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=setting_field', {
			'action': action,
			'view_by': view_by,
			'field_name': field_name
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			var _www = $(window).width();
			$Core.popup.openfull(_www - 450, respJson.html, respJson.uid);
			$('.modal-backdrop').remove();
			$('#' + respJson.uid).on('shown.bs.modal', function (e) {
				$('#' + respJson.uid).find(".list_field-selected").sortable({
					update: function (event, ui) {
						let ids = $('#' + respJson.uid).find(".list_field-selected .item_field_selected").map(function () {
							return $(this).attr("key");
						}).get();
					}
				});
				$(".text_number", $('#' + respJson.uid)).text($(".item_field_selected", $('#' + respJson.uid)).length);
			});
		}, 'json');
		return false;
	},
	delete_setting_field: function (_this, e) {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function () {
			var toId = $(_this).attr("toId");
			$("#" + toId).prop("checked", false);
			$(_this).closest(".item_field_selected").remove();
		});
		return false;
	},
	delete_all_field_selected: function (_this, e) {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa các trường đã chọn?', function () {
			var _form = $(_this).closest("form"),
				title = $(_this).data("title"),
				toId = $(_this).attr("toId"),
				id = $(_this).attr("id"),
				key = $(_this).data("key");
			$(".list_field-selected", _form).html(`<p class="text-center mb-0 fs-14 fw-italic">Không có trường nào được chọn</p>`);
			$(".text_number", _form).text($(".item_field_selected", _form).length);
			$(".list-checkbox-item input", _form).prop("checked", false);
		});
	},
	search_field: $Core.util.delay((_this, e) => {
		var _form = $(_this).closest("form"),
			_gid = $("input[name=gid]", _form).val(),
			keyword = $("input[name=keyword]", _form).val(),
			field_name = $("input[name=field_name]", _form).val();
		var list_field_data = $(".item_field_selected", _form).map(function () {
			return $(this).attr("key");
		}).get();
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=load_setting_field', {
			'gid': _gid,
			'type': "SEARCH",
			'keyword': keyword,
			'list_field_data': list_field_data,
			'field_name': field_name
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$(".list-checkbox-item", _form).html(respJson.html);
		}, 'json');
	}, 1000),
	load_setting_default_field: function (_this, e) {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn sử dụng các trường mặc định?', function () {
			var $_this = $(_this),
				_form = $_this.closest("form"),
				_gid = $("input[name=gid]", _form).val(),
				view_by = $("input[name=view_by]", _form).val(),
				field_name = $("input[name=field_name]", _form).val();
			$Core.util.toggleIndicatior(1);
			$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=load_setting_field', {
				'gid': _gid,
				'type': "DEFAULT",
				'view_by': view_by,
				'field_name': field_name
			}, function (respJson) {
				$(".list_field-selected", _form).html(respJson.html);
				$(".text_number", _form).text($(".item_field_selected", _form).length);
				$Core.crm.search_field($_this, e);
			}, 'json');
		});
		return false;
	},
	save_setting_field: function (_this, e) {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			field_name = $("input[name=field_name]", _form).val(),
			list_field_data = $(".item_field_selected", _form).map(function () {
				return $(this).attr("key");
			}).get();
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=save_setting_field', {
			"field_name": field_name,
			"list_field_data": list_field_data,
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (respJson.result) {
				$Core.alert.success('Lưu thành công!');
				$("button[data-bs-dismiss='modal']", _form).trigger("click");
				if (field_name == "fieldDataCustomer") {
					$Core.crm.load_customers('_desktop');
				} else if (field_name == "fieldDataCustomerStatus") {
					$(".box_customer_stats").removeClass("loaded");
					_autoload();
				}
			} else {
				$Core.alert.error('ERROR!');
			}
		}, 'json');
		return false;
	},
	filter_customer: function (_this, e) {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			action = $(_this).attr("action"),
			$_adata = { "action": action };
		if ($('.search_field', _form).length) {
			$('.search_field', _form).each((_i, _elem) => {
				var field = $(_elem).data('field');
				$_adata[field] = $(_elem).val();
			});
		}
		$Core.crm.load_customers('_desktop', $_adata);
		return false;
	},
	toggleReminderTime: function (_this, e) {
		e.preventDefault();
		var _form = $(_this).closest("form");
		$(".reminder_times", _form).toggleClass("d-none");
	},
	open_need: function (_this, e) {
		e.preventDefault();
		var need_id = $(_this).attr('need_id'),
			customer_id = $(_this).attr('customer_id'),
			$_adata = { 'customer_id': customer_id, 'need_id': need_id };
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=open_need', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	save_need: (_this, e) => {
		e.preventDefault();
		var need_id = $(_this).attr('need_id'),
			customer_id = $(_this).attr('customer_id'),
			$_adata = { 'customer_id': customer_id, 'need_id': need_id };
		var _validated = 0,
			_form = $(_this).closest("form");
		$('input.required,select.required', _form).each((_i, _elem) => {
			if ($Core.util.isEmpty($(_elem).val())) {
				_validated = 0;
				$(_elem).focus().addClass('errorField');
				return false;
			} else {
				$(_elem).removeClass('errorField');
			}
		});
		if (_validated == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script + '/index.php?mod=' + MOD + '&act=save_need',
				data: $_adata,
				dataType: 'json',
				success: function (respJson) {
					$Core.util.toggleIndicatior(0);
					if (respJson.result) {
						$Core.alert.success("Thành công");
						$("button[data-bs-dismiss='modal']", _form).trigger("click");
						$Core.crm.load_list_need(customer_id, {});
					} else {
						$Core.alert.error("ERROR!");
					}
				}
			});
		}
		return false;
	},
	delete_need: function (_this, e) {
		e.preventDefault();
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function () {
			var need_id = $(_this).attr('need_id'),
				customer_id = $(_this).attr('customer_id'),
				$_adata = { 'customer_id': customer_id, 'need_id': need_id };
			$Core.util.toggleIndicatior(1);
			$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=delete_need', $_adata, function (respJson) {
				$Core.util.toggleIndicatior(0);
				if (respJson.result) {
					$Core.alert.success(respJson.msg);
					$Core.crm.load_list_need(customer_id, {});
				} else {
					$Core.alert.error(respJson.msg);
				}
			}, 'json');
		});
		return false;
	},
	load_list_need: function (customer_id, options) {
		var $_adata = options || {};
		$_adata['customer_id'] = customer_id
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_list_need', $_adata, function (respJson) {
			$('.holder_needs_' + customer_id).html(respJson.html);
		}, 'json');
	},
	loadNumberRadio: function (_this, e) {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			field = $(_this).data("field"),
			number = parseInt($(_this).val());
		if (number > 5) {
			$("input[name=" + field + "]", _form).attr("disabled", true).prop("checked", false);
			$(".box_number_" + field, _form).find(".lbl_text").addClass("cursor-not-allowed");
		} else {
			$("input[name=" + field + "]", _form).removeAttr("disabled");
			$(".box_number_" + field, _form).find(".lbl_text").removeClass("cursor-not-allowed");
			$(_this).val("");
			$("input[name=" + field + "][value='" + number + "']", _form).prop("checked", true);
		}
	},
	open_billing: function (_this, e) {
		e.preventDefault();
		var gId = $(_this).attr("gId"),
			billing_id = $(_this).attr('billing_id'),
			customer_id = $(_this).attr('customer_id'),
			$_adata = { 'customer_id': customer_id, 'billing_id': billing_id, 'gId': gId };
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=open_billing', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	},
	save_billing: (_this, e) => {
		e.preventDefault();
		var gId = $(_this).attr('gId'),
			billing_id = $(_this).attr('billing_id'),
			customer_id = $(_this).attr('customer_id'),
			$_adata = { 'customer_id': customer_id, 'billing_id': billing_id };
		var _validated = 0,
			_form = $(_this).closest("form");
		$('input.required,select.required', _form).each((_i, _elem) => {
			if ($Core.util.isEmpty($(_elem).val())) {
				_validated += 1;
				$(_elem).focus().addClass('errorField');
				return false;
			} else {
				$(_elem).removeClass('errorField');
			}
		});
		if (_validated == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: path_ajax_script + '/index.php?mod=' + MOD + '&act=save_billing',
				data: $_adata,
				dataType: 'json',
				success: function (respJson) {
					$Core.util.toggleIndicatior(0);
					if (respJson.result) {
						$Core.alert.success("Thành công");
						$("button[data-bs-dismiss='modal']", _form).trigger("click");
						$_document.find("#" + gId).removeClass("loaded");
						_autoload();
					} else {
						$Core.alert.error("ERROR!");
					}
				}
			});
		}
		return false;
	},
	select_sp_file: function (_this, e) {
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			to_field = $(_this).attr('to_field'),
			modal = $(_this).closest('.modal');
		$('.upload_file_' + toId, modal).attr('to_field', to_field).trigger('click');
		return false;
	},
	upload_sp_file: function (_this, e) {
		var _form = $(_this).closest('form'),
			toId = $(_this).attr('toId'),
			to_field = $(_this).attr('to_field'),
			billing_id = $(_this).attr('billing_id');
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL + "/index.php?mod=" + MOD + "&act=upload_sp_file",
			data: { 'billing_id': billing_id, 'to_field': to_field, 'toId': toId },
			success: function (html) {
				$Core.util.toggleIndicatior(0);
				if (html.indexOf('_success') >= 0) {
					var tmp = html.split('|||');
					if (to_field == 'ccid_front' || to_field == 'ccid_back') {
						$('#' + to_field + '_' + toId).html(tmp[1]);
					} else if (to_field == 'sale_policy_file'
						|| to_field == 'capture_confirm_file'
						|| to_field == 'table_bonus_file') {
						$('#' + to_field + '_' + toId).html(tmp[1]);
					}
				}
			}
		});
	},
	upload_sp_file_clipboard: function (_this, e) {
		e.preventDefault();
		for (var i = 0; i < e.clipboardData.items.length; i++) {
			var item = e.clipboardData.items[i];
			if (item.type.indexOf("image") != -1) {
				$Core.crm.do_upload_file_clipboard(_this, item.getAsFile());
			}
		}
		return false;
	},
	do_upload_file_clipboard: function (_this, file) {
		var _form = $(_this).closest('form'),
			toId = $(_this).attr('toId'),
			to_field = $(_this).attr('to_field'),
			billing_id = $(_this).attr('billing_id'),
			billing_code = $('input[name=billing_code]', _form).val();
		var formData = new FormData();
		formData.append('toId', toId);
		formData.append('hid', 'upload');
		formData.append('to_field', to_field);
		formData.append('billing_id', billing_id);
		formData.append('billing_code', billing_code);
		formData.append('upload_file', file);
		$Core.util.toggleIndicatior(1);
		$.ajax({
			url: PCMS_URL + "/index.php?mod=" + MOD + "&act=upload_sp_file",
			method: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success: function (html) {
				$Core.util.toggleIndicatior(0);
				if (html.indexOf('_success') >= 0) {
					var tmp = html.split('|||');
					if (to_field == 'ccid_front' || to_field == 'ccid_back') {
						$('#' + to_field + '_' + toId).html(tmp[1])
					} else if (to_field == 'sale_policy_file'
						|| to_field == 'capture_confirm_file'
						|| to_field == 'table_bonus_file') {
						$('#' + to_field + '_' + toId).html(tmp[1]);
					}
				}
			}
		});
	},
	view_billing: function (_this, e) {
		e.preventDefault();
		var tabfocus = $(_this).getAttr('tabfocus', 1),
			billing_id = $(_this).attr('billing_id'),
			customer_id = $(_this).attr('customer_id'),
			from = $(_this).attr('from'),
			$_adata = { 'tabfocus': tabfocus, 'billing_id': billing_id, 'customer_id': customer_id };
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=view_billing', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$(_this).removeClass('clicked');
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$Core.crm.load_list_notes({ "customer_id": customer_id, "billing_id": billing_id });
			$Core.crm.load_list_files({ "customer_id": customer_id, "billing_id": billing_id });
			if (respJson.callback) eval(respJson.callback);
		}, 'json');
		return false;
	},
	load_list_notes: function (options) {
		var $_adata = options || {},
			billing_id = $_adata['billing_id'];
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_list_notes', $_adata, function (html) {
			if ($('.holder_notes_' + billing_id).length) {
				$('.holder_notes_' + billing_id).html(html);
			}
		});
	},
	delete_billing: function (_this, e) {
		e.preventDefault();
		var billing_id = $(_this).attr('billing_id'),
			customer_id = $(_this).attr('customer_id'),
			$_adata = { 'billing_id': billing_id, 'customer_id': customer_id };
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa giao dịch này?', function () {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=delete_billing', $_adata, function (html) {
				$Core.util.toggleIndicatior(0);
				if (html.indexOf('_success') >= 0) {
					$(_this).closest(".list_billings").removeClass("loaded");
					_autoload();
				} else {
					$Core.swal.error("Oops", "Đã xảy ra lỗi!");
				}
			});
		});
		return false;
	},
	save_notes: function (_this, e) {
		e.preventDefault();
		var _validated = 0,
			_form = $(_this).closest('form'),
			customer_id = $(_this).attr('customer_id'),
			billing_id = $(_this).attr('billing_id'),
			note_id = $(_this).attr('note_id'),
			$_adata = { 'customer_id': customer_id, 'billing_id': billing_id, 'note_id': note_id };
		if ($('select.required,input.required,textarea.required', _form).length) {
			$('select.required,input.required,textarea.required', _form).each((_i, _elem) => {
				if ($Core.util.isEmpty($(_elem).val())) {
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if (_validated == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + '/index.php?mod=' + MOD + '&act=save_notes',
				data: $_adata,
				success: function (html) {
					_form.resetForm();
					$Core.util.toggleIndicatior(0);
					$Core.crm.load_list_notes({ "customer_id": customer_id, "billing_id": billing_id });
				}
			});
		}
		return false;
	},
	delete_notes: function (_this, e) {
		e.preventDefault();
		var customer_id = $(_this).attr('customer_id'),
			billing_id = $(_this).attr('billing_id'),
			note_id = $(_this).attr('note_id'),
			$_adata = { 'action': "_delete", 'customer_id': customer_id, 'billing_id': billing_id, 'note_id': note_id };
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa không?', function () {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=save_notes', $_adata, function (html) {
				$Core.util.toggleIndicatior(0);
				$Core.crm.load_list_notes({ "customer_id": customer_id, "billing_id": billing_id });
			});
		});
		return false;
	},
	ms_save_file: function (_this, e) {
		e.preventDefault();
		var _validated = 0,
			$_form = $(_this).closest('form'),
			customer_id = $(_this).attr('customer_id'),
			billing_id = $(_this).attr('billing_id'),
			file_id = $(_this).attr('file_id'),
			tp = $(_this).attr('tp'),
			$_adata = { 'customer_id': customer_id, 'billing_id': billing_id, 'file_id': file_id };
		if ($('input.required,textarea.required', $_form).length) {
			$('input.required,textarea.required', $_form).each((_i, _elem) => {
				if ($Core.util.isEmpty($(_elem).val())) {
					_validated++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if (_validated == 0) {
			$Core.util.toggleIndicatior(1);
			$_form.ajaxSubmit({
				type: 'POST',
				url: '/index.php?mod=' + MOD + '&act=ms_save_file',
				data: $_adata,
				dataType: 'html',
				success: function (html) {
					$Core.util.toggleIndicatior(0);
					if (html.indexOf('_success') >= 0) {
						$_form[0].reset();
						if (tp == "update") {
							var _modal = $(_this).closest(".modal");
							$("button[data-bs-dismiss='modal']", _modal).trigger("click");
						}
						$Core.crm.load_list_files({ 'customer_id': customer_id, 'billing_id': billing_id });
					} else {
						$Core.messager.alert("Error!", 'Upload failed !');
					}
				}
			});
		}
		return false;
	},
	load_list_files: function (options) {
		var $_adata = options || {},
			billing_id = $_adata["billing_id"];
		$.post('/index.php?mod=' + MOD + '&act=load_list_files', $_adata, function (respJson) {
			$('.holder_files_' + billing_id).html(respJson.html);
		}, 'json');
	},
	open_file_billing_cus: function (_this, e) {
		e.preventDefault();
		var file_id = $(_this).attr('file_id'),
			customer_id = $(_this).attr('customer_id'),
			billing_id = $(_this).attr('billing_id'),
			$_adata = { 'customer_id': customer_id, 'billing_id': billing_id, 'file_id': file_id };
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_file_billing_cus', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$('#attachments_' + respJson.uid).MultiFile({
				list: '#MultiFile-preview_' + respJson.uid
			});
		}, 'json');
		return false;
	},
	delete_file_billing_cus: function (_this, e) {
		e.preventDefault();
		var file_id = $(_this).attr('file_id'),
			customer_id = $(_this).attr('customer_id'),
			billing_id = $(_this).attr('billing_id'),
			$_adata = { 'customer_id': customer_id, 'billing_id': billing_id, 'file_id': file_id };
		$Core.messager.confirm('Xác nhận', 'Bạn chắc chắn muốn xóa?', function () {
			$Core.util.toggleIndicatior(1);
			$.post('/index.php?mod=' + MOD + '&act=delete_file_billing_cus', $_adata, function (html) {
				$Core.util.toggleIndicatior(0);
				if (html.indexOf('_success') >= 0) {
					$Core.crm.load_list_files({ 'customer_id': customer_id, 'billing_id': billing_id });
				} else {
					$Core.swal.error("Oops", "Quá trình xóa bị lỗi. Xin vui lòng thử lại");
				}
			});
		});
	},
	search_dash: function (_this, e) {
		e.preventDefault();
		$(".ajax").each(function (index, elm) {
			$(elm).removeClass("loaded");
		});
		_autoload();
	},
	timer_click: function (_this, e) {
		e.preventDefault();
		var _parent = $(_this).closest(".dashboard-panel-item"),
			time_type = $(_this).attr('holderG');
		$('input[name=time_type]', _parent).val(time_type);
		$('.js_choose-time', _parent).removeClass('active');
		$(_this).addClass('active');
		var params = { "time_type": time_type };
		//		$Core.crm.load_revenue(params);
		$Core.crm.reload($(_this), e);
		return false;
	},
	load_revenue(options) {
		var $_adata = options || {};
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_revenue', $_adata, function (barChartData) {
			$Core.util.toggleIndicatior(0);
			$Core.chart.canvas('chartStockSold', barChartData);
		}, 'json');
	},
	set_time: (_this, e) => {
		e.preventDefault();
		var tp = $(_this).attr('tp'),
			gId = $(_this).attr('gId'),
			params = $(`.ajax[gId=${gId}]`).data('options') || {};
		$(`.dropdown-item[gid=${gId}]`).removeClass('active');
		$(_this).addClass('active');
		params['tp'] = tp;
		$('.ajax[gId=' + gId + ']').data('options', params);
		$('.ajax[gId=' + gId + ']').removeClass('loaded');
		_autoload();
		return false;
	},
	setTimeRemind: function (_this, e) {
		e.preventDefault();
		var action = $(_this).attr("action"),
			$_adata = { "action": action };
		if (action == "_SAVE") {
			var _form = $(_this).closest("form"),
				time_remind = $("select[name=time_remind]", _form).val();
			$_adata['time_remind'] = time_remind;
		}
		$Core.util.toggleIndicatior(1);
		$.post(path_ajax_script + '/index.php?mod=' + MOD + '&act=setTimeRemind', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (respJson.result) {
				$Core.alert.success('Cài đặt thời gian nhắc nhở thành công!');
				$('.bs-webui-popover').webuiPopover('hide');
			} else {
				$Core.alert.error('Cài đặt thời gian nhắc nhở thất bại. Vui lòng thử lại!');
			}
		}, 'json');
		return false;
	},
	setting_config_crawl: function (_this, e) {
		$Core.util.toggleIndicatior(1);
		var _type = $(_this).data("type");
		let $_adata = { "_type": _type };
		if (_type == "_OPEN") {
			$.ajax({
				url: PCMS_URL + '/index.php?mod=' + MOD + '&act=setting_config_crawl',
				method: 'POST',
				data: $_adata,
				dataType: 'json',
				success: function (respJson) {
					$Core.util.toggleIndicatior(0);
					$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
				},
			})
		} else if (_type == "_SAVE") {
			var _form = $(_this).closest("form");
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + '/index.php?mod=' + MOD + '&act=setting_config_crawl',
				data: $_adata,
				dataType: 'json',
				success: function (respJson) {
					$Core.util.toggleIndicatior(0);
					if (respJson.ressult) {
						alertify.success(respJson.msg);
						setTimeout(function () {
							window.location.reload();
						}, 500);
					} else {
						alertify.error(respJson.msg);
					}
				}
			});
		}
	},
	open_sheet: function (_this, e) {
		e.preventDefault();
		var _parent = $(_this).closest(".item_config"),
			uid = $(_this).attr('uid'),
			gId = $(_this).attr('gId'),
			stock_type = $(_this).attr('stock_type'),
			spreadsheetId = $(`.spreadsheetId_${gId}`, _parent).val();
		console.log(spreadsheetId);
		if ($Core.util.isEmpty(spreadsheetId)) {
			$(`.spreadsheetId_${gId}`, _parent).focus();
			$Core.alert.error("Bạn chưa nhập Spreadsheet ID");
		} else {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_sheet', {
				'uid': uid,
				'gId': gId,
				'stock_type': stock_type,
				'spreadsheetId': spreadsheetId
			}, function (respJson) {
				$Core.util.toggleIndicatior(0);
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			}, 'json');
		}
		return false;
	},
	update_sheet: (_this, e) => {
		e.preventDefault();
		var _modal = $(_this).closest(".modal"),
			gId = $(_this).attr('gId'),
			spreadsheetId = $(_this).attr('spreadsheetId');
		if ($(`.${spreadsheetId}:checked`).length) {
			var sheets = $Core.util.getCheckBoxValueByClass(spreadsheetId),
				sheet_ids = $Core.util.getCheckBoxAttrByClass(spreadsheetId, 'sheet_id');
			$(`input[gId=${gId}][type=text]`).val(sheets.join('|'));
			$(`input[gId=${gId}][type=hidden]`).val(sheet_ids.join('|'));
			$(".btn-close", _modal).trigger("click");
		} else {
			$Core.alert.error("Bạn phải chọn sheet cấu hình cập nhật");
		}
		return false;
	},
	configConvert: function (_this, e) {
		e.preventDefault();
		let type = $(_this).data('type');
		let itemConfig = $(_this).closest('.item_config');
		if (type == 'add') {
			let html = itemConfig.clone();
			html.addClass('mt-2');
			$("input", html).val("");
			itemConfig.after(html);
			$('.btn-delete-convert').each(function () {
				if ($(this).hasClass('d-none')) {
					$(this).removeClass('d-none');
				}
			})
		} else if (type == 'delete') {
			itemConfig.remove();
			if ($('.convert_item').length <= 1) {
				$('.btn-delete-convert').each(function () {
					if (!$(this).hasClass('d-none')) {
						$(this).addClass('d-none');
					}
				})
			}
		}
	}, open_config_column: function (_this, e) {
		e.preventDefault();
		var _parent = $(_this).closest(".item_config"),
			uid = $(_this).attr('uid'),
			gId = $(_this).attr('gId'),
			spreadsheetId = $(`.spreadsheetId_${gId}`, _parent).val(),
			sheet_id = $(`.sheet_id_${gId}`, _parent).val(),
			sheet_name = $(`.sheet_name_${gId}`, _parent).val();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_config_column', {
			'sid': uid,
			'spreadsheetId': spreadsheetId,
			'sheet_id': sheet_id,
			'sheet_name': sheet_name
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (respJson.result) {
				$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			} else {
				alertify.error(respJson.msg);
			}
		}, 'json');
		return false;
	}, do_config_column: function (_this, e) {
		e.preventDefault();
		var _form = $(_this).closest("form"),
			sheet_name = $(_this).attr("sheet_name"),
			gId = $(_this).attr("gId");
		$_adata = {
			'sheet_name': sheet_name,
			'gId': gId
		};
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=do_config_column',
			data: $_adata,
			dataType: 'json',
			success: function (respJson) {
				$Core.util.toggleIndicatior(0);
				if (respJson.result) {
					$Core.alert.success('Success !');
					$Core.popup.close(_form.closest('.modal'));
				} else {
					$Core.alert.error('Error!');
				}
			}
		});
		return false;
	}, data_distribution: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=data_distribution', {}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$Core.crm.load_share_customers('_customer', '_load', {});
			$Core.crm.load_share_staffs('_staff', {});
		}, 'json');
		return false;
	}, load_share_customers: (_holderG, action, options) => {
		var $_adata = options || {};
		if ($(`.search_field[holderG=${_holderG}]`).length) {
			$(`.search_field[holderG=${_holderG}]`).each((_i, _elem) => {
				var _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_share_customers', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			if (action == '_load') {
				$('.holder_share_customers').html(respJson.html);
			} else {
				$('.holder_share_customers .item_share_customer:last').after(respJson.html);
			}
		}, 'json');
	}, load_share_staffs: (_holderG, options) => {
		var $_adata = options || {};
		if ($(`.search_field[holderG=${_holderG}]`).length) {
			$(`.search_field[holderG=${_holderG}]`).each((_i, _elem) => {
				var _field = $(_elem).data('field');
				$_adata[_field] = $(_elem).val();
			});
		}
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=load_share_staffs', $_adata, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$('.holder_staff_customers').html(respJson.html);
		}, 'json');
	}, do_share_search: (_this, e) => {
		var _holderG = $(_this).attr('holderG');
		if (_holderG == '_staff') {
			$('.js__selected_staff').text(0);
			$Core.crm.load_share_staffs(_holderG, {});
			$('.js__start_share_customer').prop('disabled', true);
		} else if (_holderG == '_customer') {
			$('.js__selected_customer').text(0);
			$Core.crm.load_share_customers(_holderG, '_load', {});
			$('.js__start_share_customer').prop('disabled', true);
		}
	}, enter_share_search: (_this, e) => {
		var _keyCode = e.keyCode || e.which;
		if (_keyCode == 13) {
			$Core.crm.do_share_search(_this, e);
			e.preventDefault();
			return false;
		}
	}, handle_selected_staff: (_this, e) => {
		var total_checked = 0,
			uid = $(_this).attr('uid');
		total_checked = $('.js__staff_item:checked').length;
		$('.js__selected_staff').text(total_checked);
		if (parseInt(total_checked, 10) > 0) {
			$('.js__remove_selected_staff').removeClass('d-none');
		} else {
			$('.js__remove_selected_staff').addClass('d-none');
		}
		$Core.crm.handle_message(_this, e);
	}, handle_cus_staff: (_this, e) => {
		var total_checked = 0,
			uid = $(_this).attr('uid');
		total_checked = $('.js__customer_item:checked').length;
		$('.js__selected_customer').text(total_checked);
		if (parseInt(total_checked, 10) > 0) {
			$('.js__remove_selected_customer').removeClass('d-none');
		} else {
			$('.js__remove_selected_customer').addClass('d-none');
		}
		$Core.crm.handle_message(_this, e);
	}, handle_message: (_this, e) => {
		var _modal = $(_this).closest('form'),
			_total_staffs = $('.js__staff_item:checked', _modal).length,
			_total_customers = $('.js__customer_item:checked', _modal).length;
		if (_total_staffs > 0 && _total_customers > 0) {
			$('.js__start_share_customer').removeAttr('disabled');
			var balance = _total_customers % _total_staffs,
				total_share = Math.floor(_total_customers / _total_staffs);
			if (balance > 0) {
				$('.js__message-container', _modal).html(`<div class="alert alert-primary text-center mt-2 mb-0">Bạn đã chọn <strong>${_total_customers}</strong> khách hàng và <strong>${_total_staffs}</strong> Sales. ${_total_staffs - balance} người nhận được <strong>${total_share}</strong>, ${balance} người <strong>${total_share + 1}</strong> khách</div>`);
			} else {
				$('.js__message-container', _modal).html(`<div class="alert alert-primary text-center mt-2 mb-0">Bạn đã chọn <strong>${_total_customers}</strong> khách hàng và <strong>${_total_staffs}</strong> Sales. Mỗi người nhận được <strong>${total_share}</strong> khách</div>`);
			}
		} else {
			$('.js__start_share_customer', _modal).prop('disabled', true);
			$('.js__message-container', _modal).empty();
		}
	}, remove_selected_staff: (_this, e) => {
		e.preventDefault();
		$('.js__staff_item')
			.prop('checked', false)
			.trigger('change');
		return false;
	}, remove_selected_cus: (_this, e) => {
		e.preventDefault();
		$('.js__customer_item')
			.prop('checked', false)
			.trigger('change');
		return false;
	}, scroll_share_customers: (_this, e) => {
		var holderG = $(_this).attr('holderG'),
			page = $(_this).getAttr('page', 2);
		if ($(_this).scrollTop() + $(_this).innerHeight() >= _this.scrollHeight - 1) {
			$(_this).attr('page', (parseInt(page, 10) + 1));
			$Core.crm.load_share_customers(holderG, '_more', { 'page': page });
		}
	}, check_all: (_this, e) => {
		// e.preventDefault();
		var holderG = $(_this).attr('holderG');
		if (holderG == '_customer') {
			$('.js__customer_item')
				.prop('checked', true)
				.trigger('change');
		} else if (holderG == '_staff') {
			$('.js__staff_item')
				.prop('checked', true)
				.trigger('change');
		}
		$(_this).tooltip('hide');
		// return false;
	}, uncheck_all: (_this, e) => {
		// e.preventDefault();
		var holderG = $(_this).attr('holderG');
		if (holderG == '_customer') {
			$('.js__customer_item')
				.prop('checked', false)
				.trigger('change');
		} else if (holderG == '_staff') {
			$('.js__staff_item')
				.prop('checked', false)
				.trigger('change');
		}
		$(_this).tooltip('hide');
		// return false;
	}, do_share_customer: (_this, e) => {
		e.preventDefault();
		var _form = $(_this).closest('form'),
			_total_staffs = $('.js__staff_item:checked', _form).length,
			_total_customers = $('.js__customer_item:checked', _form).length;
		if (_total_staffs > 0 && _total_customers > 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + '/index.php?mod=' + MOD + '&act=do_share_customer',
				dataType: 'html',
				success: function (html) {
					$Core.util.toggleIndicatior(0);
					if (html.indexOf('_success') >= 0) {
						$Core.alert.success('Success !');
						$Core.popup.close(_form.closest('.modal'));
					} else {
						$Core.alert.error('Error!');
					}
				}
			});
		}
		return false;
	}, open_req_customer: (_this, e) => {
		e.preventDefault();
		var uid = $(_this).attr('uid'),
			openFrom = $(_this).attr('openFrom');
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=crm&act=open_req_customer', {
			'uid': uid,
			'openFrom': openFrom
		}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
		}, 'json');
		return false;
	}, save_req_customer: (_this, e) => {
		e.preventDefault();
		var _error = 0,
			_form = $(_this).closest('form');
		if ($('select.required,input.required,textarea.required', _form).length) {
			$('select.required,input.required,textarea.required', _form).each((_i, _elem) => {
				if ($Core.util.isEmpty($(_elem).val())) {
					_error++;
					$(_elem).focus();
					return false;
				}
			});
		}
		if (_error == 0) {
			$Core.util.toggleIndicatior(1);
			_form.ajaxSubmit({
				type: 'POST',
				url: PCMS_URL + "/index.php?mod=crm&act=save_req_customer",
				dataType: "JSON",
				success: function (respJson) {
					$Core.util.toggleIndicatior(0);
					if (respJson.result) {
						alertify.success("Yêu cầu của bạn đã được gửi thành công!");
						window.location.reload(true);
					} else {
						$Core.swal.error("Oops", "Quá trình gửi yêu cầu bị lỗi. Xin vui lòng thử lại");
					}
				}
			});
		}
		return false;
	}, open_data_customer: (_this, e) => {
		e.preventDefault();
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=open_data_customer', {}, function (respJson) {
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
			$Core.crm.load_share_customers('_customer', '_load', {});
		}, 'json');
		return false;
	}, un_share: (_this, e) => {
		e.preventDefault();
		var $_adata = {},
			is_all = $('input[name=is_all]').is(':checked') ? 1 : 0,
			tab = $('input[name=tab]:checked').val(),
			view = $('input[name=view]:checked').val(),
			sort_by = $('.js__sort-by.active').attr('sort_by');
		if ($('.search_field,.search_report_field').length) {
			$('.search_field,.search_report_field').each((_i, _elem) => {
				var _field = $(_elem).data('field'),
					_value = $(_elem).val();
				if (typeof (_field) != 'undefined' && !$Core.util.isEmpty(_field) && !$Core.util.isEmpty(_value)) {
					$_adata[_field] = _value;
					if (_field == 'admin_id') {
						if (parseInt($_adata[_field]) > 0 && tab == 'manage') {
							tab = 'following';
							$('input[name=tab][value=' + tab + ']').prop('checked', true);
						}
					}
				}
			});
		}
		$_adata['tab'] = tab;
		$_adata['view'] = view;
		$_adata['is_all'] = is_all;
		$Core.swal.confirm("Thông báo", 'Bạn chắc chắn muốn thu hồi khách hàng?', function () {
			$Core.util.toggleIndicatior(1);
			$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=unShare', $_adata, function (respJson) {
				$Core.util.toggleIndicatior(0);
				alertify.success(respJson.msg);
				$Core.crm.load_customers("_desktop");
			}, 'json');
		});
	}, config_column: (_this, e) => {
		e.preventDefault();
		var _tp = $(_this).attr('tp'),
			_form = $(_this).closest("form");
		if (_tp == 'google_sheet') {
			var spreadsheetId = $('input[name=spreadsheetId]', _form).val();
			if ($Core.util.isEmpty(spreadsheetId)) {
				$('input[name=spreadsheetId]', _form).focus();
				$Core.swal.error("Thông báo", "Bạn cần chọn file dữ liệu khách hàng");
			} else {
				$Core.util.toggleIndicatior(1);
				$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=configColumn', {
					'tp': _tp,
					'spreadsheetId': spreadsheetId,
				}, function (respJson) {
					$Core.util.toggleIndicatior(0);
					$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
				}, 'json');
			}
		} else {
			if ($("input[name='fileimport']", _form)[0].files.length > 0) {
				$Core.util.toggleIndicatior(1);
				_form.ajaxSubmit({
					type: 'POST',
					url: PCMS_URL + '/index.php?mod=' + MOD + '&act=configColumn',
					data: { 'tp': _tp },
					dataType: 'json',
					success: function (respJson) {
						$Core.util.toggleIndicatior(0);
						$Core.popup.open('auto', 'auto', respJson.html, respJson.uid);
						$("input[name='file_id']", _form).val(respJson.uid);
					}
				});
			} else {
				$Core.swal.error("Thông báo", "Bạn cần chọn file dữ liệu khách hàng");
			}
		}
		return false;
	}, continue_config: (_this, e) => {
		e.preventDefault();
		var _uid = $(_this).attr("uid"),
			_form = $(_this).closest("form");
		$Core.util.toggleIndicatior(1);
		_form.ajaxSubmit({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=continue_config',
			data: { 'uid': _uid },
			dataType: 'json',
			success: function (respJson) {
				$Core.util.toggleIndicatior(0);
				if (respJson.result) {
					alertify.success(respJson.msg);
					$(".btn-close", _form).trigger("click");
				} else {
					alertify.error(respJson.msg);
				}
			}
		});
		return false;
	}
	,
	mt_skel: '<div class="crm-skel"><div class="crm-skel-row"><span class="crm-skel-av"></span><span class="crm-skel-lines"><span class="crm-skel-line"></span><span class="crm-skel-line short"></span></span></div><div class="crm-skel-row"><span class="crm-skel-av"></span><span class="crm-skel-lines"><span class="crm-skel-line"></span><span class="crm-skel-line short"></span></span></div><div class="crm-skel-row"><span class="crm-skel-av"></span><span class="crm-skel-lines"><span class="crm-skel-line"></span><span class="crm-skel-line short"></span></span></div></div>',
	crm_autoload: () => {
		// MyTeam Leader Dashboard: AJAX-load each box with current period params.
		// Only targets .js__mt-box; regular .ajax boxes still handled by _autoload().
		if (!$('.js__mt-box:not(.loaded)').length) { return; }
		var period = $('.js__mt-period.active').data('period') || 'this_month';
		var from = $('.js__mt-dr-from').val() || '';
		var to = $('.js__mt-dr-to').val() || '';
		var dept = $('.js__mt-dept').val() || '';
		$('.js__mt-box:not(.loaded)').each((_i, _elem) => {
			$(_elem).addClass('loaded');
			var url = $(_elem).data('url');
			if (!url) { return; }
			$(_elem).html($Core.crm.mt_skel);
			var $_adata = { period: period };
			if (from) { $_adata['from'] = from; }
			if (to) { $_adata['to'] = to; }
			if (dept && parseInt(dept, 10) > 0) { $_adata['dept_sel'] = dept; }
			$.post(url, $_adata, function (respJson) {
				if (!respJson || typeof respJson.html === 'undefined') { return; }
				var html = respJson.html;
				if (html.indexOf('_empty') >= 0) {
					$(_elem).remove();
				} else {
					$(_elem).html(html);
					if (respJson.callback) { eval(respJson.callback); }
				}
			}, 'json').fail(function () {
				$(_elem).removeClass('loaded').html('<div class="alert alert-warning fs-12 py-2 px-3">Lỗi tải dữ liệu.</div>');
			});
		});
	},
	// MyTeam drill-down: mở modal liệt kê khách theo điều kiện 1 con số tổng.
	mt_drill: (_this) => {
		var $b = $(_this);
		var data = $Core.crm._mt_scope_params();
		data.metric = $b.data('metric');
		data.from_status = $b.data('from') || 0;
		data.to_status = $b.data('to') || 0;
		data.rep_id = $b.data('rep') || 0;
		data.dept_id = $b.data('dept') || 0;
		data.page = 1;
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=mt_drill', data, function (r) {
			$Core.util.toggleIndicatior(0);
			if (!r || r.error) { return; }
			$Core.popup.open('auto', 'auto', r.html, r.uid, 'crm-ld crm-drill-modal');
		}, 'json');
	},
	mt_drill_more: (_this) => {
		var $b = $(_this);
		var data = $Core.crm._mt_scope_params();
		data.metric = $b.data('metric');
		data.from_status = $b.data('from') || 0;
		data.to_status = $b.data('to') || 0;
		data.rep_id = $b.data('rep') || 0;
		data.dept_id = $b.data('dept') || 0;
		data.page = $b.data('page');
		data.more = 1;
		var $wrap = $b.closest('.js__mt-drill-more-wrap');
		var $list = $b.closest('.modal-body').find('.js__mt-drill-list');
		$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=mt_drill', data, function (r) {
			if (!r || r.error) { return; }
			$list.append(r.html);
			if (r.has_more) { $b.data('page', parseInt($b.data('page'), 10) + 1); } else { $wrap.remove(); }
		}, 'json');
	},
	// Đọc bộ lọc dashboard hiện tại (kỳ/khoảng ngày/vùng) để drill dùng đúng scope.
	_mt_scope_params: () => {
		var d = {};
		var period = $('.js__mt-period.active').data('period');
		if (period) { d.period = period; }
		var from = $('.js__mt-dr-from').val();
		if (from) { d.from = from; }
		var to = $('.js__mt-dr-to').val();
		if (to) { d.to = to; }
		var dept = $('.js__mt-dept').val();
		if (dept && parseInt(dept, 10) > 0) { d.dept_sel = dept; }
		return d;
	}
});
function mtSortTeamTable($table, colIdx, dir) {
	// Sort client-side bảng hiệu suất my_team theo cột. Ưu tiên data-sort (giá trị thô), fallback text cell.
	// Giữ cơ chế "top 10 + Xem thêm": sau khi sort, nếu chưa bung thì hiện 10 dòng đầu, ẩn phần còn lại.
	var $body = $table.children('tbody').length ? $table.children('tbody').first() : $table;
	var rows = $body.children('tr').filter(function () { return $(this).children('td').length > 0; }).get();
	if (rows.length < 2) { return; }
	var cellVal = function (tr, i) {
		var cell = tr.children[i];
		if (!cell) { return ''; }
		var ds = cell.getAttribute('data-sort');
		if (ds === null) { ds = cell.textContent || ''; }
		var num = parseFloat(ds);
		return isNaN(num) ? String(ds).trim().toLowerCase() : num;
	};
	rows.sort(function (a, b) {
		var av = cellVal(a, colIdx), bv = cellVal(b, colIdx);
		if (av < bv) { return dir === 'asc' ? -1 : 1; }
		if (av > bv) { return dir === 'asc' ? 1 : -1; }
		return 0;
	});
	var i;
	for (i = 0; i < rows.length; i++) { $body.append(rows[i]); }
	// đánh số lại cột # + áp lại hiển thị "top 10" nếu còn nút Xem thêm (chưa bung)
	var collapsed = $table.closest('.crm-ld-panel').find('.js__mt-rep-more-wrap').length > 0;
	for (i = 0; i < rows.length; i++) {
		var $r = $(rows[i]);
		$r.children('td').first().text(i + 1);
		if (collapsed) {
			if (i >= 10) { 
				$r.addClass('d-none js__mt-rep-more'); 
			} else { 
				$r.removeClass('d-none js__mt-rep-more'); 
			}
		}
	}
}
function loadLogsCRM(options) {
	var $_adata = options || {};
	$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadLogsCRM', $_adata, function (respJson) {
		toggleIndicatior(0);
		$('#holderLogsCRM').html(respJson.html);
		if (parseInt(respJson.totalRecord) > 1) {
			$_easyUI('#pp_LogsCRM').pagination({
				pageList: [10, 15, 20, 30, 50],
				total: respJson.totalRecord,
				pageSize: respJson.number_per_page,
				onRefresh: function (pageNumber, pageSize) {
					loadLogsCRM({ 'currentPage': pageNumber, 'number_per_page': pageSize });
				},
				onSelectPage: function (pageNumber, pageSize) {
					loadLogsCRM({ 'currentPage': pageNumber, 'number_per_page': pageSize });
				}
			});
		}
	}, 'json');
}
function loadMailboxCRM(options) {
	var opts = options || {},
		keySearch = $('.txtSearchMailbox').val();
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadListMailboxCRM',
		data: $.extend(opts, { 'keySearch': keySearch }),
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			var response = $.parseJSON(html);
			$('.holderMailboxCRM').html(response.html);
			if (parseInt(response.totalPage) > 0) {
				$_easyUI('#PageMailboxCRM').pagination({
					total: response.totalRecied,
					pageSize: response.number_per_page,
					onRefresh: function (pageNumber, pageSize) {
						loadMailboxCRM({ 'currentPage': pageNumber, 'number_per_page': pageSize });
					}, onSelectPage: function (pageNumber, pageSize) {
						loadMailboxCRM({ 'currentPage': pageNumber, 'number_per_page': pageSize });
					}
				});
			}
		}
	});
}
function loadSelectboxPropertyTypeCRM(_id, holderG, options) {
	var opts = options || {};
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=getSelectboxPropertyTypeCRM',
		data: $.extend({ 'holderG': holderG }, opts),
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			var htm = html.split('$$$');
			$(_id).html(htm[1]);
		}
	});
}
function paginatePotential(holderG, options) {
	var $_adata = options || {};
	$_adata['holderG'] = holderG;
	$_adata['keySearch'] = $(".txtSearchPotential").val();
	$_adata['changePotential'] = $(".InputChangePotential").val();
	if (holderG == '_desktop') {
		$_adata['status_id'] = $('.nav-tabs-desktopPotential li.active a').attr('status_id');
		$_adata['admin_id'] = $('.InputSearchPotential[column=admin_id]').val();
	}
	if (!$_adata.hasOwnProperty('currentPage')) {
		$_adata['currentPage'] = $(".PagePotential_currentPage").val();
	}
	if (!$_adata.hasOwnProperty('number_item_per_page')) {
		$_adata['number_item_per_page'] = $(".PagePotential_dataTables_length").val();
	}
	toggleIndicatior(1);
	$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadListPotential', $_adata, function (respJson) {
		toggleIndicatior(0);
		$('.holderCRMPotential').html(respJson.html);
		if (parseInt(respJson.totalPage) > 1) {
			$_easyUI('#PagePotential').pagination({
				total: respJson.totalRecord,
				pageSize: respJson.number_per_page,
				onRefresh: function (pageNumber, pageSize) {
					var sorthander = $('.sorthander_CRMFollowUpDesktop').val(), tmp = sorthander.split('|');
					paginatePotential(holderG, { 'currentPage': pageNumber, 'number_per_page': pageSize, 'sortby': tmp[0], 'sorttype': tmp[1] });
				},
				onSelectPage: function (pageNumber, pageSize) {
					var sorthander = $('.sorthander_CRMFollowUpDesktop').val(), tmp = sorthander.split('|');
					paginatePotential(holderG, { 'currentPage': pageNumber, 'number_per_page': pageSize, 'sortby': tmp[0], 'sorttype': tmp[1] });
				}
			});
		}
	}, 'json');
}
function formatItem(row) {
	var s = '<span class="bold" style="font-size:13px">#' + row.id + ' ' + row.text + '</span><br/>';
	if (row.companyname) s += '<span style="color:#888"><i class="fa fa-building"></i> ' + row.companyname + '</span><br />';
	if (row.email) s += '<span style="color:#888"><i class="fa fa-envelope-open-o"></i> ' + row.email + '</span><br/>';
	if (row.phone) s += '<span style="color:#888"><i class="fa fa-phone"></i> ' + row.phone + '</span>';
	return s;
}
function formatResultHTML(repo) {
	if (repo.loading) {
		return repo.text;
	}
	var markup = "<div class='select2-result-repository clearfix'>"
		+ "<div class='select2-result-repository__meta'>"
		+ "<div class='select2-result-repository__title bold'>#" + repo.id + ' ' + repo.text + "</div>"
		+ "<div class='select2-result-repository__statistics'>"
		+ "<div class='select2-result-repository__forks'><i class='fa fa-envelope-open-o'></i> " + repo.email + "</div>"
		+ "<div class='select2-result-repository__stargazers'><i class='fa fa-phone'></i> " + repo.phone + "</div>"
		+ "<div class='select2-result-repository__stargazers'><i class='fa fa-building'></i> " + repo.companyname + "</div>"
		+ "</div>"
		+ "</div>"
		+ "</div>";
	return markup;
}
function loadMenuOfPotential() {
	toggleIndicatior(1);
	var $_this = $("#ListPotential").find(".selected_tr");
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadPotentialMenu',
		data: { "potential_id": $_this.attr("potential_id") },
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			$("#PotentialMenu").html(html);
			makeSystemTab('PotentialMenu_' + $_this.attr("potential_id"));
			$('#PotentialMenu .tabsglobal li').eq($.cookie("PotentialMenu")).find('a').click();
		}
	});
}
function loadListYieldInPotential(potential_id) {
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadListYieldInPotential',
		data: { "potential_id": potential_id },
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			$("#listYieldPotential_" + potential_id).html(html);
			makadatatable("#listYieldPotential_" + potential_id, '68', false, false, "0", "asc", [7]);
			right_click_multiple($("#listYieldPotential_" + potential_id + " td.right_click"), 'js_menu_yield_potential');
		}
	});
}
function loadListBusinessCampaignInPotential(potential_id) {
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadListBusinessCampaignInPotential',
		data: { "potential_id": potential_id },
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			$("#listBusinessCampaignPotential_" + potential_id).html(html);
			makadatatable("#listBusinessCampaignPotential_" + potential_id, '68', false, false, "0", "asc", [7]);
			right_click_multiple($("#listBusinessCampaignPotential_" + potential_id + " td.right_click"), 'js_menu_business_campaign_potential');
		}
	});
}
function sentEmailPotential() {
	var $_currentPotential = $(".waitPotential:first");
	if ($_currentPotential.length) {
		$.ajax({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajSentOneEmailPotential',
			data: {
				"potential_id": $_currentPotential.attr("potential_id"),
				"title": $("#EmailPotentialTitle").val(),
				"content": $("#EmailPotentialContent").val(),
				"owner": $("#EmailPotentialOwner").val()
			},
			dataType: 'html',
			success: function (html) {
				if (html.replace(' ', '') == '1') {
					$_currentPotential.removeClass("waitPotential").text("Đã gửi").css("color", "red");
					setTimeout(function () { sentEmailPotential(); }, 5000);
				}
			}
		});
	} else {
		$("#WaitingEmailPotitenal").find(".close_pop").click();
		$("#EmailToPotential").find(".close_pop").click();
		$Core.alert.success('Email đã gửi thành công!');
	}
}
function openManagePotential(type_id, holderG, callback) {
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadManagePotential',
		data: { 'holderG': holderG, 'type_id': type_id },
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			$('#app').html(html);
			loadListPotential(holderG, { 'cat_id': type_id });
			if (typeof (callback) === 'function') {
				callback();
			}
		}
	});
}
function addNewFollowUp(date_id, tp, options) {
	var opts = options || {};
	if ($('.webui-popover ').length) {
		$('.webui-popover ').remove();
	}
	if (tp == 'popup') {
		toggleIndicatior(1);
		$.ajax({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajOpenNewFollowUp',
			data: $.extend(opts, { "date_id": date_id, "tp": tp }),
			dataType: 'html',
			success: function (html) {
				toggleIndicatior(0);
				makepopupsimple('auto', 'auto', html, 'OpenNewFollowUp');
				$('#OpenNewFollowUp').css('top', 60);
				if ($('.datepicker:enabled').length) {
					$(".datepicker").datepicker(crm_datepicker_format);
				}
				$_document.on('change', 'select[name=admin_id]', function (e) {
					var $_this = $(this),
						admin_id = $_this.val();
					$('select[name=resource_id]').html('<option value="0">' + __['Loading'] + '</option>');
					$.ajax({
						type: 'POST',
						url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadSelectPotential',
						data: { "admin_id": admin_id },
						dataType: 'html',
						success: function (html) {
							$('select[name=resource_id]').html(html);
						}
					});
					e.stopImmediatePropagation();
				});
			}
		});
	} else {//detail
		if (!$("#modal-details").hasClass('in')) {
			toggleLoading(1);
		}
		$('#modal-details-body').html('<img class="img-center" src="' + URL_IMAGES + '/loading_circle.gif" />');
		$.ajax({
			type: 'POST',
			url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajAddNewFollowUp',
			data: { "date_id": date_id, "tp": tp },
			dataType: 'html',
			success: function (html) {
				$('#modal-details-body').html(html);
				$('#modal-details').css({ 'width': '500px', 'min-width': '400px' }).removeClass('out').addClass('in');
				toggleLoading(0);
				if ($('.datepicker:enabled').length) {
					$(".datepicker").datepicker(crm_datepicker_format);
				}
				$('.selectpicker').selectpicker({
					style: 'btn-default',
					liveSearch: true
				});
				$_document.on('change', 'select[name=admin_id]', function (e) {
					var $_this = $(this),
						admin_id = $_this.val();
					$.ajax({
						type: 'POST',
						url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadSelectPotential',
						data: { "admin_id": admin_id },
						dataType: 'html',
						success: function (html) {
							$('select[name=resource_id]').html(html);
							$('.selectpicker').selectpicker('refresh');
						}
					});
					e.stopImmediatePropagation();
				});
			}
		});
	}
}
function sortTable(_this, columname, options, callback) {
	var _tr = $(_this).closest('tr'),
		_sort = $(_this).hasAttr('sort') ? $(_this).attr('sort') : 'asc';
	_tr.find('th,td').removeClass('bs-sort-desc').removeClass('bs-sort-asc')
	if (_sort == 'asc') {
		$(_this).removeClass('bs-sort-desc').addClass('bs-sort-asc').attr('sort', 'desc');
	} else {
		$(_this).removeClass('bs-sort-asc').addClass('bs-sort-desc').attr('sort', 'asc');
	}
	callback(options, { 'sortby': columname, 'sorttype': _sort })
}
function loadListCRMEmailLogs(potential_id, options) {
	var opts = options || {};
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadListCRMEmailLogs',
		data: $.extend(opts, { 'potential_id': potential_id }),
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			var response = $.parseJSON(html);
			$('.holderCRMEMailLog_' + potential_id).html(response.html);
			if (parseInt(response.totalPage) > 1) {
				$_easyUI('#PageCRMEmailLogs').pagination({
					total: response.totalRecord,
					pageSize: response.number_per_page,
					onRefresh: function (pageNumber, pageSize) {
						loadListCRMEmailLogs(potential_id, { 'currentPage': pageNumber, 'number_per_page': pageSize });
					},
					onSelectPage: function (pageNumber, pageSize) {
						loadListCRMEmailLogs(potential_id, { 'currentPage': pageNumber, 'number_per_page': pageSize });
					}
				});
			}
			/* SortHandler */
			$('.sorthandler').click(function () {
				var _this = $(this),
					_column = _this.attr('column'),
					_sorttype = _this.hasClass('bs-sort-asc') ? 'desc' : 'asc';
				loadListCRMEmailLogs(potential_id, { 'sortby': _column, 'sorttype': _sorttype });
				return false;
			});
		}
	});
}
function loadListMassMailSentItem(massmail_id, options) {
	var moreoption = options || {};
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=load_list_massmail_sent',
		data: $.extend(moreoption, { 'massmail_id': massmail_id }),
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			$('.holderMassMailSentItem_' + massmail_id).html(html);
		}
	});
}
function loadListCRMMassMail(options) {
	var opts = options || {},
		keySearch = $('.txtSearchMassMail').val();
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadListCRMMassMail',
		data: $.extend(opts, { 'keySearch': keySearch }),
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			var response = $.parseJSON(html);
			$('#holderCRMMassMail').html(response.html);
			if (parseInt(response.totalPage) > 1) {
				$_easyUI('#PageCRMMassMail').pagination({
					total: response.totalRecord,
					pageSize: response.number_per_page,
					onRefresh: function (pageNumber, pageSize) {
						loadListCRMMassMail({ 'currentPage': pageNumber, 'number_per_page': pageSize });
					},
					onSelectPage: function (pageNumber, pageSize) {
						loadListCRMMassMail({ 'currentPage': pageNumber, 'number_per_page': pageSize });
					}
				});
			}
		}
	});
}
function OpenPotentialField(obj, options) {
	var opts = options || {},
		$_this = $(obj),
		p_id = opts.p_id,
		p_field = opts.p_field,
		p_cell = $_this.closest('.InputCRMHandler'),
		p_field_id = 0,
		p_action = '_open';
	if ($_this.hasClass('custom')) {
		p_field_id = $_this.attr('p_field_id');
	}
	$_data = {
		'p_action': p_action,
		'p_field': p_field,
		'p_field_id': p_field_id,
		'p_id': p_id
	};
	if (p_field == 'phone') {
		country_code = $_this.hasAttr('country_code') ? $_this.attr('country_code') : '';
		$_data['country_code'] = country_code;
	}
	p_cell.html('<img class="img-center-ripple" src="' + URL_IMAGES + '/ripple-loading.svg" />');
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadEditPotentialField',
		data: $_data,
		dataType: 'html',
		success: function (html) {
			p_cell.html(html);
			if (p_field == 'admin_id') {
				p_cell.addClass('qmnb');
			}
			if (p_field == '_custom') {
				$('.InputPotentialField_' + p_field_id + '_' + p_id).focus();
				$('.InputPotentialField_' + p_field_id + '_' + p_id).on('click', function (e) {
					e.preventDefault();
					e.stopPropagation();
					$(this).trigger('focus');
				});
			} else if (p_field == 'client_id') {
				var $el = $('.InputPotentialField_' + p_field + '_' + p_id);
				$el.select2({
					allowClear: true,
					minimumInputLength: 2,
					minimumResultsForSearch: 20,
					ajax: {
						delay: 250,
						type: "POST",
						url: PCMS_URL + "/searchclient/?search_type=_global",
						dataType: 'json',
						data: function (param) {
							return { term: param.term };
						}, processResults: function (data) {
							return {
								results: $.map(data, function (item) {
									return {
										id: item.company_id,
										text: item.name,
										phone: item.phone,
										email: item.email,
										companyname: item.companyname
									}
								})
							};
						}
					},
					escapeMarkup: function (markup) { return markup; },
					templateResult: formatResultHTML
				});
			} else if (p_field == 'country_id') {
				var $el = $('.InputPotentialField_' + p_field + '_' + p_id);
				$el.select2({
					allowClear: true,
					minimumInputLength: 2,
					minimumResultsForSearch: 20,
					ajax: {
						delay: 250,
						type: "POST",
						url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadListCountryFlag',
						dataType: 'json',
						data: function (param) {
							return { term: param.term };
						}, processResults: function (data) {
							return {
								results: $.map(data, function (item) {
									return {
										id: item.country_id,
										text: item.name,
										code: item.code,
										calling_code: item.calling_code
									}
								})
							};
						}
					},
					escapeMarkup: function (markup) { return markup; },
					templateResult: formatCountry,
					templateSelection: function (data, container) {
						$(data.element).attr({
							'data-country_id': data.country_id,
							'data-name': data.name,
							'data-code': data.code,
							'data-calling_code': data.calling_code
						});
						return data.text;
					}
				}).on("select2:select", function (e) {
					var country_id = $(this).val(),
						code = $(this).find(':selected').data('code'),
						calling_code = $(this).find(':selected').data('calling_code');
					$(this).closest('.inline-editor-container').find('.flag-icon').removeClass().addClass('flag-icon flag-icon-' + code).show();
					if ($('.OpenPotentialField[p_field=phone]').length) {
						$('.OpenPotentialField[p_field=phone]').attr('country_code', calling_code).trigger('click');
					} else {
						$('.InputPotentialField_phone_' + p_id).val(calling_code).focus();
						var InputPotentialField_phone = $('.InputPotentialField_phone_' + p_id),
							strLength = InputPotentialField_phone.val().length;
						InputPotentialField_phone[0].setSelectionRange(strLength, strLength);
					}
				}).on("select2:unselect", function (e) {
					$(this).closest('.inline-editor-container').find('.flag-icon').hide();
				});
			} else {
				$('.InputPotentialField_' + p_field + '_' + p_id).focus();
				if (p_field == 'phone') {
					var InputPotentialField_phone = $('.InputPotentialField_phone_' + p_id),
						strLength = InputPotentialField_phone.val().length;
					InputPotentialField_phone[0].setSelectionRange(strLength, strLength);
				}
			}
		}
	});
}
function formatCountry(country) {
	if (!country.code) { return country.text; }
	return $(
		'<span class="flag-icon flag-icon-' + country.code.toLowerCase() + ' flag-icon-squared"></span> ' +
		'<span class="flag-text">' + country.text + "</span>" +
		'<span class="flag-code">(' + country.calling_code + ")</span>"
	);
};
function loadListCRMCampaignGlobe(options) {
	var opts = options || {},
		keySearch = $('.txtSearchCampaign').val(),
		status_date = $('.SearchCampaignDateEnd').val();
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadListCRMCampaignGlobe',
		data: $.extend(opts, { 'keySearch': keySearch, 'status_date': status_date }),
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			var response = $.parseJSON(html);
			$("#holderCRMCampainGlobe").html(response.html);
			if (parseInt(response.totalPage)) {
				$_easyUI('#PageCRMCampain').pagination({
					total: response.totalRecord,
					pageSize: response.number_per_page,
					onRefresh: function (pageNumber, pageSize) {
						loadListCRMCampaignGlobe({ 'currentPage': pageNumber, 'number_per_page': pageSize });
					},
					onSelectPage: function (pageNumber, pageSize) {
						loadListCRMCampaignGlobe({ 'currentPage': pageNumber, 'number_per_page': pageSize });
					}
				});
			}
		}
	});
}
function loadCompanyInBusinessCampaign(business_campaign_id) {
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadCompanyInCampaign',
		data: { "business_campaign_id": business_campaign_id },
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			$("#listCompanyInBusinessCampaign_" + business_campaign_id).html(html);
			makadatatable("#listCompanyInBusinessCampaign_" + business_campaign_id, '68', false, false, "0", "asc", [6]);
			right_click_multiple($("#listCompanyInBusinessCampaign_" + business_campaign_id + " td.right_click"), 'js_menu_business_campaign_company');
		}
	});
}
function loadListPotentialCampain(searchcond, business_campaign_id) {
	toggleIndicatior(1);
	$.ajax({
		type: 'POST',
		url: PCMS_URL + '/index.php?mod=' + MOD + '&act=ajLoadListPotentialCampain',
		data: $.extend({ 'business_campaign_id': business_campaign_id }, searchcond),
		dataType: 'html',
		success: function (html) {
			toggleIndicatior(0);
			if (html.indexOf('_empty') >= 0) {
				$('.holderPotentialCampain').empty();
			} else {
				var response = $.parseJSON(html);
				$('.holderPotentialCampain').html(response.html);
				if (parseInt(response.totalPage) > 1) {
					$_easyUI('#PagePotentialCampain').pagination({
						total: response.totalRecord,
						pageSize: response.number_per_page,
						onRefresh: function (pageNumber, pageSize) {
							loadListPotentialCampain($.extend(searchcond, { 'currentPage': pageNumber, 'number_per_page': pageSize }), business_campaign_id);
						},
						onSelectPage: function (pageNumber, pageSize) {
							loadListPotentialCampain($.extend(searchcond, { 'currentPage': pageNumber, 'number_per_page': pageSize }), business_campaign_id);
						}
					});
				}
				$('.js_choice-contact-one,.js_choice-contact-all').on('change', function (ev) {
					var $_this = $(this),
						number_checked = 0,
						check_all = true;
					if ($_this.hasClass('js_choice-contact-all')) {
						var checked = $_this.is(':checked') ? true : false;
						$('.js_choice-contact-one').prop('checked', checked).trigger('change');
					} else {
						$('.js_choice-contact-one').each(function () {
							if ($(this).is(':not(:checked)')) {
								check_all = false;
							}
						});
						$('.js_choice-contact-all').prop('checked', check_all);
					}
					var number_checked = $('.js_choice-contact-one:checked').size();
					$('.js_total-choice-contact').text(number_checked);
					if (number_checked == 0) {
						$('.js_add-contact-campaign').attr('disabled', true);
					} else {
						$('.js_add-contact-campaign').removeAttr('disabled');
					}
				});
				$('.js_add-contact-campaign').on('click', function (ev) {
					ev.preventDefault();
					ev.stopPropagation();
					var $_this = $(this),
						$_form = $_this.closest('form'),
						business_campaign_id = $_this.attr('business_campaign_id');
					toggleIndicatior(1);
					$_form.ajaxSubmit({
						type: 'POST',
						url: PCMS_URL + '/index.php?mod=' + MOD + '&act=aj_add_contact_campaign',
						data: { "business_campaign_id": business_campaign_id },
						dataType: 'html',
						success: function (html) {
							toggleIndicatior(0);
							$Core.alert.success(__['Saved']);
							loadListPotentialCampain(searchcond, business_campaign_id);
						}
					});
				});
			}
		}
	});
}
function init_page_all_follow_ups(_this) {
	toggleIndicatior(1);
	$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=init_page_all_follow_ups', {}, function (html) {
		$('#app').html(html);
		list_all_follow_ups();
		$('.filterItemFollowUpsBtn').on('click', function (_ev) {
			list_all_follow_ups({});
		});
	});
	return false;
}
function list_all_follow_ups(options) {
	var $_adata = options || {};
	if ($('.filterItemFollowUps').length) {
		$('.filterItemFollowUps').each(function () {
			var _this = $(this),
				_column = _this.data('column');
			if (typeof (_column) !== 'undefined') {
				$_adata[_column] = _this.val();
			}
		});
	}
	toggleIndicatior(1);
	$.post(PCMS_URL + '/index.php?mod=' + MOD + '&act=list_all_follow_ups', $_adata, function (respJson) {
		toggleIndicatior(0);
		$('#' + 'holderG_follow-ups').html(respJson.html);
		if (parseInt(respJson.total_record) > 1) {
			$_easyUI('#PagerFollowUps').pagination({
				total: respJson.total_record,
				pageSize: respJson.number_per_page,
				onRefresh: function (pageNumber, pageSize) {
					list_all_follow_ups({ 'page': pageNumber, 'number_per_page': pageSize });
				},
				onSelectPage: function (pageNumber, pageSize) {
					list_all_follow_ups({ 'page': pageNumber, 'number_per_page': pageSize });
				}
			});
		}
	}, 'json');
}
function _autoload() {
	if ($('.ajax:not(.loaded)').length) {
		$('.ajax:not(.loaded)').each((_i, _elem) => {
			$(_elem).addClass("loaded");
			var url = $(_elem).data('url'),
				gId = $(_elem).getAttr('gId', ""),
				$_adata = $(_elem).data('options') || {},
				_item = $(_elem).closest(".dashboard-panel-item"),
				_item_all = $(_elem).closest(".dashboard-panel-item-all").find(".group_search");
			if ($("input[name=time_type]", _item)) {
				$_adata["time_type"] = $("input[name=time_type]", _item).val();
			}
			if ($('.search_field').length) {
				$('.search_field').each((_i, elm) => {
					var name = $(elm).attr('name');
					if (typeof (name) !== 'undefined') {
						$_adata[name] = $(elm).val();
					}
				});
			}
			if ($('.search_field', _item).length) {
				$('.search_field', _item).each((_i, elm) => {
					var name = $(elm).attr('name');
					if (typeof (name) !== 'undefined') {
						$_adata[name] = $(elm).val();
					}
				});
			}
			if ($('.search_group', _item).length) {
				$('.search_group', _item).each((_i, elm) => {
					var name = $(elm).attr('name');
					console.log(name);
					if (typeof (name) !== 'undefined') {
						$_adata[name] = $(elm).val();
					}
				});
			}
			$_adata['gId'] = gId;
			if ($(_elem).hasClass('js__block-report-today')) {
				var department_id = $('.js__handle-department').val();
				$_adata['department_id'] = department_id;
			}
			$.post(url, $_adata, function (respJson) {
				let $html = respJson.html;
				if ($(_elem).hasClass('js_chart_crm_group')) {
					var toIdNumber = $(_elem).attr("toIdNumber");
					$("#" + toIdNumber).text(respJson.total_member);
				}
				if ($(_elem).hasClass('js__block-sys-staff')) {
					var toIdNumber = $(_elem).attr("toIdNumber");
					$("#" + toIdNumber).text(respJson.total_member);
				}
				if ($html.indexOf('_empty') >= 0) {
					if ($(_elem).hasClass("home_events")) {
						$(".home_block_score").show();
						$(".home_block_events").hide();
					}
					if ($(_elem).hasClass("home_shared")) {
						$(".home_block_shared").hide();
					}
					$(_elem).remove();
				} else {
					$(_elem).addClass('loaded').html($html);
					if (respJson.drawchart == 1) {
						if (typeof (respJson.multichart) != 'undefined' && respJson.multichart == 1) {
							$Core.chart.canvas_multi(respJson.uid, respJson.barChartData);
						} else {
							$Core.chart.canvas(respJson.uid, respJson.barChartData);
						}
					}
					if ($(_elem).hasClass('dashboard_sfs_report') && $(".table-sort").length) {
						$(".table-sort").tableSortable({
							cmp: (a, b) => $Core.report.toNumber(a) < $Core.report.toNumber(b) ? -1 : 1
						});
					}
					if ($(_elem).hasClass("home_events")) {
						$(".home_block_score").hide();
						$(".home_block_events").show();
					}
				}
				if (respJson.callback) {
					eval(respJson.callback);
				}
			}, 'json');
		});
	}
}
