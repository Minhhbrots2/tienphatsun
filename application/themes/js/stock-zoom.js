/*======================================================================*\
|| stock-zoom.js — Zoom bảng hàng cho home/project/stock (vanilla JS).    ||
|| Port từ AngularJS (dev.myfuture.vn: directive tblZoom/zoomHold +       ||
|| StockCtrl) sang jQuery/Smarty thuần — KHÔNG phụ thuộc Angular.         ||
||                                                                        ||
|| Bọc #tblStock bằng .tbl-zoom-host (persistent) + toolbar; zoom         ||
|| table.table-stock bằng CSS `zoom` (desktop, giữ sticky) / transform    ||
|| scale (iOS). Bảng nạp/đổi qua AJAX (load_stock/load_more/đổi tòa) →     ||
|| re-fit qua MutationObserver. Bảng/khung lấy MỚI mỗi lần (firstTable/    ||
|| boxEl) → chịu được khi tbody hoặc #tblStock bị thay.                    ||
||                                                                        ||
|| Kiến trúc: listener document/window gắn 1 LẦN (module) → dispatch tới  ||
|| engine ACTIVE; listener trên host + toolbar theo từng host. Tránh rò.  ||
||                                                                        ||
|| Tính năng: nút +/−/reset/khóa cuộn-zoom/fullscreen/chụp ảnh; cuộn lăn;  ||
|| pinch 2 ngón; kéo-pan; fullscreen (API + fallback iOS); PNG html2canvas.||
\*======================================================================*/
(function () {
	'use strict';
	if (window.__stockZoomLoaded) return;
	window.__stockZoomLoaded = true;

	var TBL = 'table-stock';   // class bảng được zoom (nguồn dùng 'bt')
	var ACTIVE = null;         // engine của host đang hoạt động (1 bảng/trang)

	// iOS/WebKit: CSS zoom<1 phình bảng → phải dùng transform (mất sticky). Khác thì giữ CSS zoom (sticky OK).
	var _isIOS = /iP(hone|od|ad)/.test(navigator.platform || '')
		|| (navigator.maxTouchPoints > 1 && /Macintosh/.test(navigator.userAgent || ''))
		|| /iPhone|iPad|iPod/.test(navigator.userAgent || '');

	function _toolbarHtml() {
		return '' +
			'<div class="tbl-zoom-bar">' +
				'<button type="button" class="tbl-zoom-btn tbl-zoom-toggle rounded-pill" data-zoom="bar" title="Menu zoom"><i class="bx bx-cog"></i></button>' +
				'<div class="tbl-zoom-actions">' +
					'<button type="button" class="tbl-zoom-btn" data-zoom="in" title="Phóng to"><i class="bx bx-plus"></i></button>' +
					'<button type="button" class="tbl-zoom-btn" data-zoom="out" title="Thu nhỏ"><i class="bx bx-minus"></i></button>' +
					'<button type="button" class="tbl-zoom-btn" data-zoom="reset" title="Đặt lại"><i class="bx bx-reset"></i></button>' +
					'<button type="button" class="tbl-zoom-btn" data-zoom="lock" title="Khóa cuộn-zoom"><i class="bx bx-lock-open-alt"></i></button>' +
					'<button type="button" class="tbl-zoom-btn" data-zoom="fs" title="Toàn màn hình"><i class="bx bx-fullscreen"></i></button>' +
					'<button type="button" class="tbl-zoom-btn" data-zoom="cap" title="Chụp ảnh bảng"><i class="bx bx-camera"></i></button>' +
				'</div>' +
			'</div>';
	}

	// ======================================================================
	// Engine zoom cho 1 host (.tbl-zoom-host bọc #tblStock)
	// ======================================================================
	function makeEngine(host) {
		var S = {
			zoom: 1,
			minZoom: 0.5,
			locked: true,
			isFs: false,
			barOpen: localStorage.getItem('stock_zoombar_open') !== 'false'
		};
		var _natW = 0, _natH = 0, _fitDone = false, _lastW = -1;

		function firstTable() { return host.getElementsByClassName(TBL)[0]; }
		function tables() { return host.getElementsByClassName(TBL); }
		function boxEl() { return host.querySelector('#tblStock') || firstTable(); } // khung cuộn (freeze-table)

		function _clearScale(t) { t.style.zoom = ''; t.style.transform = ''; t.style.marginRight = ''; t.style.marginBottom = ''; t.style.width = 'auto'; }

		function applyZoom() {
			var z = S.zoom, ts = tables();
			for (var i = 0; i < ts.length; i++) {
				var t = ts[i];
				t.classList.toggle('tbl-flat', !!(_isIOS && _natW && _natH && z !== 1)); // iOS zoom → tắt sticky cột
				if (_isIOS && _natW && _natH && z !== 1) {
					t.style.zoom = '';
					t.style.width = _natW + 'px';
					t.style.transformOrigin = 'top left';
					t.style.transform = 'scale(' + z + ')';
					t.style.marginRight = (_natW * (z - 1)) + 'px';
					t.style.marginBottom = (_natH * (z - 1)) + 'px';
				} else if (_isIOS) {
					t.style.zoom = ''; t.style.transform = ''; t.style.marginRight = ''; t.style.marginBottom = ''; t.style.width = 'auto';
				} else {
					t.style.transform = ''; t.style.marginRight = ''; t.style.marginBottom = '';
					t.style.width = _natW ? (_natW + 'px') : 'auto';
					t.style.zoom = z;
				}
			}
		}

		// ----- Fit / cận dưới zoom ------------------------------------------
		function computeFitZoom() {
			var t = firstTable(), box = boxEl();
			if (!t || !box) return 0;
			_clearScale(t);
			var W = t.offsetWidth, C = box.clientWidth;
			if (!W || !C || W >= C) return 1;
			return Math.min(2, Math.floor((C / W) * 100) / 100);
		}
		function calcMinZoom() {
			var tb = firstTable(), boxv = boxEl();
			if (!tb || !boxv) return 0.5;
			var W = tb.offsetWidth, H = tb.offsetHeight;
			_natW = W; _natH = H;
			var vv = window.visualViewport;
			var vpW = (vv && vv.width) || document.documentElement.clientWidth || window.innerWidth || boxv.clientWidth;
			var vpH = (vv && vv.height) || document.documentElement.clientHeight || window.innerHeight;
			var Cw = Math.min(boxv.clientWidth, vpW);
			var fs = document.fullscreenElement || document.webkitFullscreenElement || host.classList.contains('tbl-fs-fallback');
			var availH = fs ? Math.min(boxv.clientHeight, vpH) : (vpH - Math.max(0, boxv.getBoundingClientRect().top) - 8);
			if (availH < 120) availH = 120;
			var fitW = W > 0 ? Cw / W : 1, fitH = H > 0 ? availH / H : 1;
			return Math.max(0.1, Math.min(0.5, Math.floor(Math.min(fitW, fitH) * 100) / 100));
		}
		function _fitMeasure(prevW, tries) {
			var tb = firstTable();
			if (!tb) { if (tries < 12) requestAnimationFrame(function () { _fitMeasure(-1, tries + 1); }); return; }
			_clearScale(tb);
			var w = tb.offsetWidth;
			if (w !== prevW && tries < 12) { requestAnimationFrame(function () { _fitMeasure(w, tries + 1); }); return; }
			_lastW = w;                          // nhớ bề ngang tự nhiên đã fit
			var f = computeFitZoom(); if (f) S.zoom = f;
			S.minZoom = calcMinZoom();
			_fitDone = true; applyZoom();
		}
		function refit() { _fitMeasure(-1, 0); }
		function onTableChange() {
			var tb = firstTable();
			if (!tb) { _fitDone = false; return; }
			_clearScale(tb);
			var w = tb.offsetWidth;
			if (!_fitDone || w !== _lastW) {     // bảng mới / đổi tòa (đổi bề ngang) → fit lại
				requestAnimationFrame(function () { requestAnimationFrame(function () { _fitMeasure(-1, 0); }); });
			} else {                             // chỉ đổi chiều cao (load_more) → giữ zoom user, cập nhật cận dưới
				S.minZoom = calcMinZoom();
				applyZoom();
			}
		}
		function recalcMin() {
			var tb = firstTable(); if (!tb) return;
			_clearScale(tb); S.minZoom = calcMinZoom(); applyZoom();
		}
		var _rzTimer = null;
		function recalcDebounced() { clearTimeout(_rzTimer); _rzTimer = setTimeout(recalcMin, 150); }

		// ----- Lưu zoom + áp zoom -------------------------------------------
		var _saveT = null;
		function _saveZoom() { clearTimeout(_saveT); _saveT = setTimeout(function () { try { localStorage.setItem('stock_zoom', S.zoom); } catch (e) {} }, 250); }
		function _zoomTo(z) { if (z === S.zoom) return; S.zoom = z; applyZoom(); _saveZoom(); }

		// ----- Cuộn lăn ------------------------------------------------------
		var _pending = null, _raf = null;
		function onWheel(e) {
			if (S.locked) return;
			e.preventDefault();
			var base = (_pending !== null) ? _pending : S.zoom;
			var z = Math.round((base + (e.deltaY < 0 ? 0.05 : -0.05)) * 100) / 100;
			_pending = Math.min(2, Math.max(S.minZoom || 0.5, z));
			if (_raf) return;
			_raf = requestAnimationFrame(function () {
				_raf = null; var p = _pending; _pending = null;
				if (p === null || p === S.zoom) return;
				_zoomTo(p);
			});
		}

		// ----- Pinch 2 ngón --------------------------------------------------
		var _pinching = false, _gesture = false, _pinchD0 = 0, _pinchZ0 = 1, _tPending = null, _tRaf = null;
		function _dist(t) { var dx = t[0].clientX - t[1].clientX, dy = t[0].clientY - t[1].clientY; return Math.sqrt(dx * dx + dy * dy); }
		function _pinchTo(z) {
			_tPending = Math.min(2, Math.max(S.minZoom || 0.5, Math.round(z * 100) / 100));
			if (_tRaf) return;
			_tRaf = requestAnimationFrame(function () {
				_tRaf = null; var p = _tPending; _tPending = null;
				if (p === null || p === S.zoom) return; _zoomTo(p);
			});
		}
		function _savePinch() { try { localStorage.setItem('stock_zoom', S.zoom); } catch (err) {} }
		function onGestureStart(e) { if (S.locked) return; e.preventDefault(); _gesture = true; _pinchZ0 = S.zoom; }
		function onGestureChange(e) { if (!_gesture) return; e.preventDefault(); _pinchTo(_pinchZ0 * e.scale); }
		function onGestureEnd(e) { if (_gesture) { e.preventDefault(); _gesture = false; _savePinch(); } }
		function onTouchStart(e) {
			if (_gesture) return;
			if (e.touches.length === 2 && !S.locked) { _pinching = true; _pinchD0 = _dist(e.touches); _pinchZ0 = S.zoom; e.preventDefault(); }
		}
		function onTouchMove(e) {
			if (_gesture || !_pinching || e.touches.length !== 2) return;
			e.preventDefault();
			if (_pinchD0 > 0) _pinchTo(_pinchZ0 * (_dist(e.touches) / _pinchD0));
		}
		function onTouchEnd(e) { if (_pinching && (!e.touches || e.touches.length < 2)) { _pinching = false; _savePinch(); } }

		// ----- Kéo chuột để pan ---------------------------------------------
		function _axisScroller(fromEl, horiz) {
			for (var el = fromEl; el && el.nodeType === 1 && el !== document.body; el = el.parentElement) {
				var cs = window.getComputedStyle(el);
				if (horiz) { if (/(auto|scroll)/.test(cs.overflowX) && el.scrollWidth - el.clientWidth > 1) return el; }
				else { if (/(auto|scroll)/.test(cs.overflowY) && el.scrollHeight - el.clientHeight > 1) return el; }
			}
			var doc = document.scrollingElement || document.documentElement;
			if (horiz ? (doc.scrollWidth - doc.clientWidth > 1) : (doc.scrollHeight - doc.clientHeight > 1)) return doc;
			return null;
		}
		var scX = null, scY = null, down = false, moved = false, sx = 0, sy = 0, slX = 0, slY = 0;
		function panStart(e) {
			if (e.button !== 0 || !e.target.closest || !e.target.closest('.' + TBL)) return;
			if (!host.contains(e.target)) return;
			scX = _axisScroller(e.target, true); scY = _axisScroller(e.target, false);
			if (!scX && !scY) return;
			down = true; moved = false; sx = e.clientX; sy = e.clientY;
			slX = scX ? scX.scrollLeft : 0; slY = scY ? scY.scrollTop : 0;
		}
		function panMove(e) {
			if (!down) return;
			var dx = e.clientX - sx, dy = e.clientY - sy;
			if (!moved && (Math.abs(dx) > 4 || Math.abs(dy) > 4)) { moved = true; document.body.classList.add('tbl-zoom-grabbing'); }
			if (moved) { if (scX) scX.scrollLeft = slX - dx; if (scY) scY.scrollTop = slY - dy; e.preventDefault(); }
		}
		function panUp() {
			if (moved) {
				document.body.classList.remove('tbl-zoom-grabbing');
				var sup = function (ev) { ev.stopPropagation(); ev.preventDefault(); document.removeEventListener('click', sup, true); };
				document.addEventListener('click', sup, true);
			}
			down = false; moved = false; scX = null; scY = null;
		}

		// ----- Chụp ảnh (html2canvas) ---------------------------------------
		function captureTable() {
			var src = firstTable();
			if (!src || typeof window.html2canvas !== 'function') return;
			var clone = src.cloneNode(true);
			clone.classList.add('tbl-cap');
			_clearScale(clone); clone.style.maxWidth = 'none';
			var stage = document.createElement('div');
			stage.style.cssText = 'position:fixed;top:0;left:-100050px;width:100000px;overflow:hidden;';
			stage.appendChild(clone); document.body.appendChild(stage);
			// Lưới border do CSS '.table-stock.tbl-cap' lo (separate + border phải/dưới mỗi ô): html2canvas
			// render kém 'border-collapse:collapse' + 'var(--bs-border-width)' của Bootstrap → mất viền.
			requestAnimationFrame(function () {
				try {
					var w = clone.offsetWidth, h = clone.offsetHeight;
					var pad = Math.round(h * 0.1) + 80;
					var sc = Math.max(1, Math.min(2, 8000 / Math.max(w, h + pad, 1)));
					window.html2canvas(clone, {
						backgroundColor: '#ffffff', scale: sc, useCORS: true,
						width: w + 24, height: h + pad, windowWidth: w + 80, windowHeight: h + pad
					}).then(function (full) {
						if (stage.parentNode) stage.parentNode.removeChild(stage);
						var out = _trim(full);
						var a = document.createElement('a');
						var _box = host.querySelector('#tblStock');
						var _bc = String((_box && _box.getAttribute('data-building')) ||
							(typeof building_id !== 'undefined' ? building_id : '') ||
							(typeof block_id !== 'undefined' ? block_id : '')).replace(/[^a-zA-Z0-9._-]+/g, '-').replace(/^-+|-+$/g, '');
						a.download = 'bang-hang-toa-' + (_bc || 'na') + '.png';
						a.href = out.toDataURL('image/png'); a.click();
					}).catch(function () { if (stage.parentNode) stage.parentNode.removeChild(stage); });
				} catch (e2) { if (stage.parentNode) stage.parentNode.removeChild(stage); }
			});
		}
		function _trim(cv) {
			try {
				var ctx = cv.getContext('2d'), W = cv.width, H = cv.height;
				var rowHas = function (yy) { var d = ctx.getImageData(0, yy, W, 1).data; for (var i = 0; i < d.length; i += 4) if (d[i] < 250 || d[i + 1] < 250 || d[i + 2] < 250) return true; return false; };
				var colHas = function (xx, y0, y1) { var d = ctx.getImageData(xx, y0, 1, y1 - y0 + 1).data; for (var i = 0; i < d.length; i += 4) if (d[i] < 250 || d[i + 1] < 250 || d[i + 2] < 250) return true; return false; };
				var top = -1, bot = -1, left = 0, right = W - 1, y, x;
				for (y = 0; y < H; y++) if (rowHas(y)) { top = y; break; }
				if (top < 0) return cv;
				for (y = H - 1; y >= 0; y--) if (rowHas(y)) { bot = y; break; }
				for (x = 0; x < W; x++) if (colHas(x, top, bot)) { left = x; break; }
				for (x = W - 1; x >= 0; x--) if (colHas(x, top, bot)) { right = x; break; }
				if (top === 0 && left === 0 && bot === H - 1 && right === W - 1) return cv;
				var out = document.createElement('canvas');
				out.width = right - left + 1; out.height = bot - top + 1;
				out.getContext('2d').drawImage(cv, -left, -top);
				return out;
			} catch (e) { return cv; }
		}

		// ----- Fullscreen ----------------------------------------------------
		function _exitFsFallback() {
			if (!host.classList.contains('tbl-fs-fallback')) return;
			host.classList.remove('tbl-fs-fallback');
			document.body.style.overflow = '';
			if (host.__fsMarker && host.__fsMarker.parentNode) { host.__fsMarker.parentNode.insertBefore(host, host.__fsMarker); host.__fsMarker.parentNode.removeChild(host.__fsMarker); }
			S.isFs = false; _syncFs();
		}
		function toggleFs() {
			var req = host.requestFullscreen || host.webkitRequestFullscreen;
			if (req) {
				var fsEl = document.fullscreenElement || document.webkitFullscreenElement;
				if (!fsEl) { req.call(host); }
				else { var exit = document.exitFullscreen || document.webkitExitFullscreen; if (exit) exit.call(document); }
			} else {
				var on = !host.classList.contains('tbl-fs-fallback');
				if (on) {
					if (!host.__fsMarker) host.__fsMarker = document.createComment('fs-host');
					if (host.parentNode && host.parentNode !== document.body) host.parentNode.insertBefore(host.__fsMarker, host);
					document.body.appendChild(host);
					host.classList.add('tbl-fs-fallback');
					document.body.style.overflow = 'hidden';
				} else { _exitFsFallback(); }
				S.isFs = on; _syncFs();
				setTimeout(refit, 80);
			}
		}
		function onFsChange() {
			var fsEl = document.fullscreenElement || document.webkitFullscreenElement;
			S.isFs = !!fsEl; _syncFs();
		}

		// ----- Điều khiển zoom ----------------------------------------------
		function _setZoom(z) {
			S.zoom = Math.min(2, Math.max(S.minZoom || 0.5, Math.round(z * 100) / 100));
			try { localStorage.setItem('stock_zoom', S.zoom); } catch (e) {}
			applyZoom();
		}
		function zoomIn() { _setZoom(S.zoom + 0.05); }
		function zoomOut() { _setZoom(S.zoom - 0.05); }
		function zoomReset() { refit(); }
		function toggleLock() { S.locked = !S.locked; try { localStorage.setItem('stock_zoom_locked', S.locked); } catch (e) {} _syncLock(); }
		function toggleBar() { S.barOpen = !S.barOpen; try { localStorage.setItem('stock_zoombar_open', S.barOpen); } catch (e) {} _syncBar(); }

		// ----- Đồng bộ nút ---------------------------------------------------
		function _q(act) { return host.querySelector('.tbl-zoom-bar [data-zoom="' + act + '"]'); }
		function _syncBar() {
			var a = host.querySelector('.tbl-zoom-actions'); if (a) a.style.display = S.barOpen ? '' : 'none';
			var b = _q('bar'); if (b) { var ic = b.querySelector('i'); if (ic) ic.className = 'bx ' + (S.barOpen ? 'bx-x' : 'bx-cog'); b.title = S.barOpen ? 'Ẩn menu zoom' : 'Hiện menu zoom'; }
		}
		function _syncLock() {
			var b = _q('lock'); if (!b) return;
			b.classList.toggle('active', S.locked);
			var ic = b.querySelector('i'); if (ic) ic.className = 'bx ' + (S.locked ? 'bx-lock-alt' : 'bx-lock-open-alt');
			b.title = S.locked ? 'Mở khóa cuộn-zoom' : 'Khóa cuộn-zoom';
		}
		function _syncFs() { var b = _q('fs'); if (!b) return; var ic = b.querySelector('i'); if (ic) ic.className = 'bx ' + (S.isFs ? 'bx-exit-fullscreen' : 'bx-fullscreen'); }

		// ----- Toolbar (host-local; dựng lại theo host) ---------------------
		function _bindHold(btn, fn) {
			if (!btn) return;
			var to = null, iv = null;
			function tick() { fn(); }
			function stop() { if (to) { clearTimeout(to); to = null; } if (iv) { clearInterval(iv); iv = null; } }
			function startH(e) { if (e.type === 'mousedown' && e.button !== 0) return; e.preventDefault(); stop(); tick(); to = setTimeout(function () { iv = setInterval(tick, 80); }, 350); }
			btn.addEventListener('mousedown', startH); btn.addEventListener('touchstart', startH, { passive: false });
			['mouseup', 'mouseleave', 'touchend', 'touchcancel'].forEach(function (ev) { btn.addEventListener(ev, stop); });
		}
		var bar = host.querySelector('.tbl-zoom-bar');
		if (bar) {
			bar.addEventListener('click', function (e) {
				var btn = e.target.closest('[data-zoom]'); if (!btn) return;
				var act = btn.getAttribute('data-zoom');
				if (act === 'reset') zoomReset();
				else if (act === 'lock') toggleLock();
				else if (act === 'bar') toggleBar();
				else if (act === 'fs') toggleFs();
				else if (act === 'cap') captureTable();
			});
			_bindHold(_q('in'), zoomIn);
			_bindHold(_q('out'), zoomOut);
		}
		_syncBar(); _syncLock(); _syncFs();

		return {
			host: host, onTableChange: onTableChange, refit: refit, recalcDebounced: recalcDebounced, onFsChange: onFsChange,
			onWheel: onWheel, onGestureStart: onGestureStart, onGestureChange: onGestureChange, onGestureEnd: onGestureEnd,
			onTouchStart: onTouchStart, onTouchMove: onTouchMove, onTouchEnd: onTouchEnd,
			panStart: panStart, panMove: panMove, panUp: panUp, exitFsFallback: _exitFsFallback
		};
	}

	// ======================================================================
	// Listener document/window — gắn 1 LẦN, dispatch tới ACTIVE
	// ======================================================================
	function _overHost(e) { return ACTIVE && e.target && e.target.closest && e.target.closest('.tbl-zoom-host') === ACTIVE.host; }
	function bindGlobals() {
		window.addEventListener('resize', function () { if (ACTIVE) ACTIVE.recalcDebounced(); });
		document.addEventListener('fullscreenchange', function () { if (ACTIVE) { ACTIVE.onFsChange(); requestAnimationFrame(ACTIVE.refit); } });
		document.addEventListener('webkitfullscreenchange', function () { if (ACTIVE) { ACTIVE.onFsChange(); requestAnimationFrame(ACTIVE.refit); } });
		if (window.visualViewport) window.visualViewport.addEventListener('resize', function () { if (ACTIVE) ACTIVE.recalcDebounced(); });
		document.addEventListener('wheel', function (e) { if (_overHost(e)) ACTIVE.onWheel(e); }, { passive: false });
		document.addEventListener('gesturestart', function (e) { if (_overHost(e)) ACTIVE.onGestureStart(e); }, { passive: false });
		document.addEventListener('gesturechange', function (e) { if (ACTIVE) ACTIVE.onGestureChange(e); }, { passive: false });
		document.addEventListener('gestureend', function (e) { if (ACTIVE) ACTIVE.onGestureEnd(e); }, { passive: false });
		document.addEventListener('touchstart', function (e) { if (_overHost(e)) ACTIVE.onTouchStart(e); }, { passive: false });
		document.addEventListener('touchmove', function (e) { if (ACTIVE) ACTIVE.onTouchMove(e); }, { passive: false });
		document.addEventListener('touchend', function (e) { if (ACTIVE) ACTIVE.onTouchEnd(e); }, { passive: false });
		document.addEventListener('touchcancel', function (e) { if (ACTIVE) ACTIVE.onTouchEnd(e); }, { passive: false });
		document.addEventListener('mousedown', function (e) { if (ACTIVE) ACTIVE.panStart(e); });
		document.addEventListener('mousemove', function (e) { if (ACTIVE) ACTIVE.panMove(e); });
		document.addEventListener('mouseup', function () { if (ACTIVE) ACTIVE.panUp(); });
	}

	// ======================================================================
	// Bọc #tblStock + toolbar (host persistent). Engine 1 lần / host.
	// ======================================================================
	function ensureHost() {
		var box = document.getElementById('tblStock');
		if (!box) return null;
		var host = box.closest('.tbl-zoom-host');
		if (host) { if (!host.__eng) { host.insertAdjacentHTML('afterbegin', _toolbarHtml()); host.__eng = makeEngine(host); ACTIVE = host.__eng; } return host; }
		host = document.createElement('div');
		host.className = 'tbl-zoom-host position-relative';
		box.parentNode.insertBefore(host, box);
		host.appendChild(box);
		host.insertAdjacentHTML('afterbegin', _toolbarHtml());
		host.__eng = makeEngine(host);
		ACTIVE = host.__eng;
		return host;
	}

	var _obTimer = null, _observed = null;
	function _tick() {
		clearTimeout(_obTimer);
		_obTimer = setTimeout(function () {
			var host = ensureHost();
			if (host && host.__eng) { ACTIVE = host.__eng; host.__eng.onTableChange(); _observe(host); }
		}, 60);
	}
	function _observe(host) {
		// Quan sát tổ tiên ổn định (server-render) để bắt cả khi #tblStock/tbody bị thay.
		var target = (host && host.parentNode) || document.getElementById('tblStock');
		if (!target || target === _observed) return;
		_observed = target;
		new MutationObserver(_tick).observe(target, { childList: true, subtree: true });
	}
	function start() {
		if (!document.getElementById('tblStock')) return; // không phải trang bảng hàng
		bindGlobals();
		var host = ensureHost();
		_observe(host);
		_tick();
	}

	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
	else start();
})();
