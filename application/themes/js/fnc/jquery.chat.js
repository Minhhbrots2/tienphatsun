/**
 * $Core.chat — widget Chat nội bộ (front). Tab Check-in: chụp ảnh + GPS → mod=chat&act=checkin.
 * Tab kênh (Toàn công ty / Phòng ban): thread tin nhắn + composer, poll 5s (realtime push = P-A4).
 * Block HTML: application/blocks/chat/index.tpl.
 */
var $Core = $Core || {};
$Core.chat = {
	my: null,
	feed: null,
	tab: 'chat',		// 'chat' (danh sách hội thoại) | 'checkin' — mặc định Trò chuyện (chốt user)
	view: 'list',		// trong tab chat: 'list' | 'thread'
	convFail: false,	// cờ fallback → render UI tab cũ (renderTabs) nếu default_channels lỗi/khác shape
	chans: [],
	curCh: 0,
	// Tạo nhóm (P2): danh sách NV cho picker + ảnh chọn + set id đã chọn.
	staff: [],
	groupImg: null,
	picked: null,
	// Tạo nhóm vs Thêm-TV dùng chung modal (P3); panel quản trị (P3).
	cgMode: 'create',
	cgAddCid: 0,
	mngCid: 0,
	mngInfo: null,
	lastId: 0,
	pollTimer: null,
	// Định vị check-in: watchPosition gom mẫu → giữ mẫu sai số nhỏ nhất (best-of-N), hâm nóng GPS lúc chụp ảnh.
	geoWatch: null,
	geoTimer: null,
	bestFix: null,
	bestFixT: 0,
	pendingPhoto: null,
	geoReady: false,
	posting: false,
	GEO_TARGET_M: 100,		// ngưỡng dừng sớm = mặc định lỏng nhất server (_CHECKIN_MAX_ACCURACY); gate per-VP ở server là chân lý cuối
	GEO_MAX_WAIT_MS: 15000,	// trần thời gian gom mẫu
	GEO_TTL_MS: 90000,		// mẫu định vị quá hạn này coi là cũ → đo lại trước khi gửi
	// Realtime (Socket.IO tái dùng $Core.socket)
	socketBound: false,
	typingTimer: null,
	typingSentT: 0,
	imgPosting: false,
	// Lightbox xem ảnh
	lbEl: null,
	lbSrcs: null,
	lbIdx: 0,
	lbScale: 1,
	lbTx: 0,
	lbTy: 0,
	histLastRead: 0,
	replyTo: 0,
	// Thả cảm xúc: lưu CODE (server không giữ ký tự emoji 4-byte); client map code→char.
	EMOJI: { love: '❤️', like: '👍', haha: '😆', wow: '😮', sad: '😢', angry: '😡' },
	REACTS: ['love', 'like', 'haha', 'wow', 'sad', 'angry'],
	pickerEl: null,
	pickerMid: 0,
	AV_TINTS: [['#eef0ff', '#4f46e5'], ['#fdeee1', '#c2410c'], ['#e3f6f6', '#0f766e'], ['#fdeaf1', '#be185d'], ['#e8f1fd', '#1d4ed8'], ['#eef6e3', '#4d7c0f'], ['#fbeae7', '#b91c1c'], ['#eceaf9', '#5b21b6']],
	init: function(){
		if(!document.getElementById('fhchat')){ return; }
		var self = this;
		// Nút TĨNH (fab/close/send/attach/file/chatfile/input/rb-x) gắn onclick/onchange/onkeydown thẳng trong block tpl → KHÔNG bind ở đây.
		// Còn lại là phần tử ĐỘNG do JS sinh (tab/cta/retry/album/reply/react/reacts/quote) → vẫn dùng event-delegation.
		$('.fhc-tabs').on('click', '.fhc-tab[data-tab]', function(){
			self.switchTab($(this).attr('data-tab'));
		});
		$('#fhc-body').on('click', '.fhc-conv', function(){	// 1 dòng hội thoại → mở thread (vỏ nav mới)
			self.openConv(parseInt($(this).attr('data-cid'), 10));
		});
		$('#fhchat').on('click', '#fhc-cta', function(){ 
			self.onCta(); 
		});
		$('#fhchat').on('click', '#fhc-retry', function(){ 
			self.retry(); 
		});
		$('#fhc-body').on('click', '.fhc-acell', function(){ 
			self.onAlbumClick(this); 
		});
		$('#fhc-body').on('click', '.fhc-reply', function(e){ 
			e.stopPropagation(); 
			self.startReply($(this).attr('data-id')); 
		});
		$('#fhc-body').on('click', '.fhc-react', function(e){ 
			e.stopPropagation(); 
			self.openPicker($(this).attr('data-id'), this); 
		});
		$('#fhc-body').on('click', '.fhc-reacts', function(e){ 
			e.stopPropagation(); 
			self.openPicker($(this).closest('.fhc-msg').attr('data-id'), this); 
		});
		$('#fhc-body').on('click', '.fhc-quote', function(){ 
			self.scrollToMsg($(this).attr('data-to')); 
		});
		// Long-press (mobile) trên 1 tin → mở picker cảm xúc (KHÔNG phải onclick — timer giữ-nhấn nên giữ ở JS).
		var lpTimer = null, lpMid = 0;
		$('#fhc-body').on('touchstart', '.fhc-msg', function(){ 
			lpMid = this.getAttribute('data-id'); 
			var el = this; 
			lpTimer = setTimeout(function(){ 
				self.openPicker(lpMid, el);
			}, 450); 
		});
		$('#fhc-body').on('touchmove touchend touchcancel', '.fhc-msg', function(){ 
			if(lpTimer){ 
				clearTimeout(lpTimer); 
				lpTimer = null; 
			} 
		});
		this.setHeaderMode('list');	// vỏ nav mặc định = list (header logo + tiêu đề Trò chuyện)
		this.socketInit();
		this.loadChannels();	// nạp kênh + badge chưa đọc + subscribe ngay khi tải trang (badge live cả khi panel đóng)
	},
	url: function(act){
		var b = (typeof path_ajax_script !== 'undefined' && path_ajax_script) ? path_ajax_script : '';
		return b + '/index.php?mod=chat&act=' + act;
	},
	open: function(){
		$('#fhc-panel').addClass('fhc-open');
		$('#fhc-fab').addClass('fhc-hide');
		this.loadChannels();
		this.reloadActive();
	},
	close: function(){
		this.stopPoll();
		this.stopGeo();
		this.hideTyping();
		$('#fhc-panel').removeClass('fhc-open');
		$('#fhc-fab').removeClass('fhc-hide');
	},
	/* Handler cho nút/input TĨNH gọi từ onclick/onkeydown/oninput trong block tpl. */
	onAttach: function(){
		if(this.curCh > 0){ 
			document.getElementById('fhc-chatfile').click(); 
		}
	},
	onInputKey: function(e){
		if(e.which === 13 && !e.shiftKey){ 
			e.preventDefault(); 
			this.sendMsg(); 
		}
	},
	onInputResize: function(el){
		el.style.height = 'auto';
		el.style.height = Math.min(el.scrollHeight, 92) + 'px';
		this.emitTyping();
	},

	/* Nạp lại nội dung tab đang chọn (khi mở lại panel). */
	reloadActive: function(){
		if(this.tab === 'checkin'){ this.loadCheckin(); return; }
		if(this.tab === 'chat' && this.view === 'list'){ this.renderConvList(); return; }
		if(this.curCh > 0){
			// Thread còn nguyên (panel chỉ ẩn, không huỷ) → nối tiếp poll + nạp bù, tránh reset/flash.
			if(document.querySelector('#fhc-body .fhc-thread')){
				this.showComposer(true);
				this.loadHistory(this.curCh, this.lastId, false);
				this.startPoll();
			} else {
				this.openChannel(this.curCh);
			}
		}
	},

	switchTab: function(tab){
		if(!tab){ return; }	// nút tĩnh (＋Nhóm) không có data-tab → bỏ qua
		if(tab.indexOf('ch_') === 0){ this.view = 'thread'; this.openConv(parseInt(tab.slice(3), 10)); return; }	// fallback tab-kênh cũ
		if(tab === this.tab && this.view === 'list'){ return; }
		this.hideTyping();
		this.cancelReply();	// huỷ quote đang soạn để không treo sang kênh khác
		this.stopPoll();
		$('.fhc-tab').removeClass('active');
		$('.fhc-tab[data-tab="' + tab + '"]').addClass('active');
		this.tab = tab;
		this.curCh = 0;
		this.view = 'list';
		this.showComposer(false);
		this.setHeaderMode('list');
		if(tab === 'checkin'){
			$('#fhc-sub').text('Kênh Check-in');
			this.loadCheckin();
			return;
		}
		this.stopGeo();
		this.renderConvList();	// tab chat → danh sách hội thoại
	},
	loadCheckin: function(){
		var self = this;
		this.stopGeo();
		this.bestFix = null; this.pendingPhoto = null; this.geoReady = false; this.posting = false;
		$('#fhc-body').html('<div class="fhc-empty">Đang tải…</div>');
		$.when($.getJSON(this.url('my')), $.getJSON(this.url('feed'))).done(function(a, b){
			self.my = a[0];
			self.feed = b[0];
			self.renderCheckin();
		}).fail(function(){
			$('#fhc-body').html('<div class="fhc-empty">Không tải được dữ liệu. Thử lại sau.</div>');
		});
	},
	renderCheckin: function(){
		var f = this.feed || {}, my = this.my || {};
		var checked = f.checked || 0, total = f.total || 0;
		var pct = total > 0 ? Math.round(checked / total * 100) : 0;
		var info = '<div class="fhc-info"><div class="fhc-top">'
			+ '<div class="fhc-clock">' + this.svg(this.I_CLOCK, 21) + '</div>'
			+ '<div><h2>Check-in hàng ngày</h2>'
			+ '<span class="fhc-chip"><span class="fhc-dot"></span> Khung giờ ' + (my.window || '') + '</span>'
			+ '<p>Gửi 1 ảnh kèm vị trí tại văn phòng để ghi nhận đi làm.</p></div></div>'
			+ '<div class="fhc-prog"><div class="fhc-pt"><span><b>' + checked + '</b> / ' + total + ' đã điểm danh</span><span>Còn ' + Math.max(0, total - checked) + '</span></div>'
			+ '<div class="fhc-bar"><i style="width:' + pct + '%"></i></div></div></div>';
		var feedH = '<div class="fhc-feedh"><span>Đã check-in hôm nay</span><span class="fhc-cnt">' + (f.list ? f.list.length : 0) + '</span></div>';
		var rows = '';
		if(f.list && f.list.length){
			for(var i = 0; i < f.list.length; i++){
				var it = f.list[i];
				rows += '<div class="fhc-row">'
					+ '<img class="fhc-av" src="' + this.esc(it.avatar) + '" onerror="this.style.visibility=\'hidden\'">'
					+ '<div class="fhc-who"><div class="fhc-nm">' + this.esc(it.name) + '</div><div class="fhc-tm">' + this.svg(this.I_PIN, 11) + ' cách ' + (parseInt(it.distance_m, 10) || 0) + 'm · ' + this.esc(it.time) + '</div></div>'
					+ (it.photo ? '<img class="fhc-cap" src="' + this.esc(it.photo) + '">' : '')
					+ '<div class="fhc-pill">' + this.svg(this.I_CHECK, 12) + 'Đúng</div></div>';
			}
		} else {
			rows = '<div class="fhc-feedempty">'
				+ '<div class="fhc-fe-ic">' + this.svg(this.I_SUNRISE, 26) + '</div>'
				+ '<div class="fhc-fe-t">Chưa có ai check-in</div>'
				+ '<div class="fhc-fe-s">Hãy là người đầu tiên điểm danh sáng nay.</div>'
				+ '</div>';
		}
		$('#fhc-body').html(info + '<div class="fhc-my" id="fhc-my"></div>' + feedH + rows);
		this.renderMy(my.done ? 'done' : 'idle');
		// Badge FAB do loadChannels quản (tổng tin chưa đọc), không ghi đè ở đây.
	},
	renderMy: function(state, extra){
		var box = $('#fhc-my'), my = this.my || {}, h = '';
		box.attr('class', 'fhc-my ' + state);
		if(state === 'idle'){
			h = '<div class="fhc-h"><span class="fhc-ico">' + this.svg(this.I_CAM, 18) + '</span>Đến giờ check-in</div>'
				+ '<div class="fhc-stxt">Chụp 1 ảnh tại văn phòng. Hệ thống tự xác định giờ &amp; vị trí.</div>'
				+ '<button class="fhc-cta" id="fhc-cta" type="button">' + this.svg(this.I_CAM, 18) + 'Chụp ảnh check-in</button>';
		} else if(state === 'locating'){
			h = '<div class="fhc-h"><span class="fhc-ico">' + this.svg(this.I_SPIN, 18, 'fhc-spin') + '</span>Đang định vị…</div>'
				+ '<div class="fhc-stxt" id="fhc-geo">Đang lấy vị trí GPS… <b>±—m</b><br>Đứng yên, ra chỗ thoáng để định vị chính xác hơn.</div>'
				+ '<button class="fhc-cta" disabled>' + this.svg(this.I_SPIN, 18, 'fhc-spin') + 'Đang xử lý…</button>';
		} else if(state === 'retry'){
			var ac = (extra && extra.acc) ? (' <b class="fhc-geo-bad">±' + (parseInt(extra.acc, 10) || 0) + 'm</b>') : '';
			h = '<div class="fhc-h"><span class="fhc-ico">' + this.svg(this.I_PIN, 18) + '</span>Chưa gửi được</div>'
				+ '<div class="fhc-stxt">' + this.esc((extra && extra.msg) ? extra.msg : 'Chưa lấy được vị trí.') + ac + '</div>'
				+ '<button class="fhc-cta" id="fhc-retry" type="button">' + this.svg(this.I_PIN, 18) + 'Thử lại</button>';
		} else if(state === 'done'){
			h = '<div class="fhc-h"><span class="fhc-ico">' + this.svg(this.I_CHECK, 18) + '</span>Đã check-in lúc ' + this.esc(my.time || '') + '</div>'
				+ '<div class="fhc-stxt">Cảm ơn bạn! Đã ghi nhận, khoá gửi lại trong hôm nay.</div>'
				+ (my.photo ? '<img class="fhc-thumb" src="' + this.esc(my.photo) + '">' : '');
		}
		box.html(h);
	},
	/* Tap "Chụp ảnh check-in": hâm nóng GPS + mở camera (GPS hội tụ trong lúc chụp). */
	onCta: function(){
		this.stopGeo();
		this.bestFix = null; 
        this.bestFixT = 0; 
        this.pendingPhoto = null; 
        this.geoReady = false; 
        this.posting = false;
		this.renderMy('locating');
		this.startGeo();
		document.getElementById('fhc-file').click();
	},
	/* Thử lại: còn ảnh → đo lại vị trí (GIỮ ảnh); chưa có ảnh → chụp lại từ đầu. */
	retry: function(){
		if(this.pendingPhoto){ 
            this.geoReady = false; 
            this.posting = false; 
            this.renderMy('locating'); 
            this.startGeo(); 
        } else { 
            this.onCta(); 
        }
	},
	/* watchPosition: gom mẫu, giữ mẫu sai số nhỏ nhất, dừng sớm khi ≤ ngưỡng, trần thời gian GEO_MAX_WAIT_MS. */
	startGeo: function(){
		var self = this;
		if(!navigator.geolocation){ this.geoBad('Trình duyệt không hỗ trợ định vị. Hãy mở bằng Chrome/Safari qua https.'); return; }
		this.stopGeo();
		this.bestFix = null; this.bestFixT = 0; this.geoReady = false;
		this.geoTimer = setTimeout(function(){ 
            self.onGeoTimeout(); 
        }, this.GEO_MAX_WAIT_MS);
		this.geoWatch = navigator.geolocation.watchPosition(
			function(p){ self.onGeoFix(p); },
			function(e){ self.onGeoErr(e); },
			{ enableHighAccuracy: true, maximumAge: 0, timeout: 12000 }
		);
	},
	onGeoFix: function(p){
		if(this.posting){ return; }	// đã gửi → bỏ qua callback muộn
		var acc = Math.round(p.coords.accuracy || 0);
		if(acc <= 0){ return; }
		if(!this.bestFix || acc < this.bestFix.acc){
			this.bestFix = { lat: p.coords.latitude, lng: p.coords.longitude, acc: acc };
			this.bestFixT = Date.now();
		}
		var best = this.bestFix.acc, good = best <= this.GEO_TARGET_M;
		this.geoStatus('Định vị: <b class="' + (good ? 'fhc-geo-good' : 'fhc-geo-bad') + '">±' + best + 'm</b>' + (good ? ' ✓' : '')
			+ '<br>' + (good ? 'Đã đủ chính xác.' : 'Đứng yên, ra chỗ thoáng để chính xác hơn…'));
		if(good){ this.geoReady = true; this.stopGeo(); this.trySubmit(); }
	},
	onGeoErr: function(e){
		if(this.posting){ return; }
		// Từ chối quyền (code 1) → dừng, hướng dẫn bật quyền; lỗi tạm (2/3) → để trần thời gian quyết định (watch có thể vẫn ra fix).
		if(e && e.code === 1){ this.geoBad('Chưa bật quyền vị trí. Nhấn biểu tượng khoá trên thanh địa chỉ → Quyền → Vị trí → Cho phép, rồi thử lại.'); }
	},
	onGeoTimeout: function(){
		if(this.posting){ return; }
		this.geoReady = true;
		this.stopGeo();
		if(!this.bestFix){ this.geoBad('Tín hiệu định vị yếu, chưa lấy được vị trí. Kiểm tra GPS/Định vị đã bật, ra chỗ thoáng rồi thử lại.'); return; }
		if(this.bestFix.acc > this.GEO_TARGET_M){ this.geoBad('Tín hiệu định vị yếu (tốt nhất ±' + this.bestFix.acc + 'm). Ra gần cửa sổ hoặc ngoài trời rồi thử lại.', this.bestFix.acc); return; }
		if(this.pendingPhoto){ this.trySubmit(); }
		else {
			// Có fix tốt nhưng chưa có ảnh (đang/đã huỷ camera). Chờ ảnh; nếu quá hạn vẫn không có (huỷ) → về idle để bấm lại.
			this.geoStatus('Đã định vị ±' + this.bestFix.acc + 'm. Chờ ảnh…');
			var self = this;
			this.geoTimer = setTimeout(function(){ if(!self.pendingPhoto && !self.posting){ self.renderMy('idle'); } }, this.GEO_TTL_MS);
		}
	},
	/* Đủ ảnh + 1 mẫu định vị đạt ngưỡng & còn mới → gửi 1 lần. */
	trySubmit: function(){
		if(this.posting){ return; }
		if(!this.pendingPhoto || !this.bestFix || !this.geoReady){ return; }
		if(this.bestFix.acc > this.GEO_TARGET_M){ return; }
		// Mẫu cũ quá hạn (user để lâu) → đo lại trước khi gửi, tránh gửi vị trí lỗi thời.
		if(Date.now() - this.bestFixT > this.GEO_TTL_MS){ this.renderMy('locating'); this.startGeo(); return; }
		this.posting = true;
		var self = this, fix = this.bestFix;
		this.geoStatus('Đã định vị ±' + fix.acc + 'm. <b>Đang gửi…</b>');
		$.post(this.url('checkin'), { photo: this.pendingPhoto, lat: fix.lat, lng: fix.lng, accuracy: fix.acc }, function(resp){
			self.posting = false;
			if(!resp || resp.error){ self.geoBad(resp && resp.message ? resp.message : 'Check-in thất bại', fix.acc); return; }
			self.pendingPhoto = null;
			self.loadCheckin();
		}, 'json').fail(function(){ self.posting = false; self.geoBad('Lỗi kết nối, thử lại'); });
	},
	geoStatus: function(html){
		var el = document.getElementById('fhc-geo');
		if(el){ el.innerHTML = html; }
	},
	stopGeo: function(){
		if(this.geoWatch !== null && navigator.geolocation){ navigator.geolocation.clearWatch(this.geoWatch); }
		this.geoWatch = null;
		if(this.geoTimer){ clearTimeout(this.geoTimer); this.geoTimer = null; }
	},
	/* Lỗi (yếu / từ chối quyền / server từ chối) → GIỮ ảnh, hiện lý do + nút Thử lại (đo lại vị trí, không bắt chụp lại). */
	geoBad: function(msg, acc){
		this.stopGeo();
		this.geoReady = false; this.posting = false;
		this.renderMy('retry', { msg: msg, acc: acc || 0 });
	},

	onPick: function(input){
		var self = this;
		if(!input.files || !input.files[0]){ return; }
		var reader = new FileReader();
		reader.onload = function(e){
			var img = new Image();
			img.onload = function(){
				var max = 1280, w = img.width, h = img.height;
				if(w > max){ h = Math.round(h * max / w); w = max; }
				var cv = document.createElement('canvas');
				cv.width = w; cv.height = h;
				cv.getContext('2d').drawImage(img, 0, 0, w, h);
				self.pendingPhoto = cv.toDataURL('image/jpeg', 0.82);
				self.geoStatus(self.bestFix ? ('Đã chụp ảnh · định vị ±' + self.bestFix.acc + 'm…') : 'Đã chụp ảnh · đang chờ định vị…');
				self.trySubmit();
			};
			img.src = e.target.result;
		};
		reader.readAsDataURL(input.files[0]);
		input.value = '';
	},

	/* ---------------- Chat kênh ---------------- */
	loadChannels: function(){
		var self = this;
		$.getJSON(this.url('channels'), function(r){
			if(!r || r.error || !r.channels){ self.convFail = true; }
			else { self.chans = r.channels; self.convFail = false; }
			try {
				if(self.convFail){ self.fallbackTabs(); }	// list lỗi → vỏ tab cũ (chat vẫn dùng được)
				else if(self.tab === 'chat' && self.view === 'list'){ self.renderConvList(); }	// đang xem list → refresh; thread/checkin giữ nguyên
			} catch(e){ self.convFail = true; self.fallbackTabs(); }
			self.socketInit();
			self.subscribe();
			self.refreshBadge();
		}).fail(function(){ self.convFail = true; });
	},
	fallbackTabs: function(){ this.renderTabs(); },	// GIỮ renderTabs làm fallback (KHÔNG xoá)
	/* ---- Vỏ nav mới (P4): danh sách hội thoại kiểu Zalo ⇄ thread (tầng thread ĐÓNG BĂNG) ---- */
	renderConvList: function(){
		if(this.convFail){ this.fallbackTabs(); return; }
		var sys = [], grp = [];
		for(var i = 0; i < this.chans.length; i++){ var c = this.chans[i]; (parseInt(c.type, 10) === 4 ? grp : sys).push(c); }
		grp.sort(function(a, b){ return (parseInt(b.last_ts, 10) || 0) - (parseInt(a.last_ts, 10) || 0); });	// nhóm: tin mới lên đầu
		var h = '<div class="fhc-conv-top"><span class="fhc-conv-h">Trò chuyện</span>'
			+ '<button type="button" class="fhc-conv-newbtn" onclick="$Core.chat.openCreateGroup()">＋ Tạo nhóm</button></div>';
		h += '<div class="fhc-conv-sec">Hệ thống</div>';
		for(var a = 0; a < sys.length; a++){ h += this.convRowHtml(sys[a]); }
		h += '<div class="fhc-conv-sec">Nhóm chat</div>';
		if(grp.length){ for(var b = 0; b < grp.length; b++){ h += this.convRowHtml(grp[b]); } }
		else { h += '<div class="fhc-conv-empty">Chưa có nhóm. Bấm “＋ Tạo nhóm”.</div>'; }
		$('#fhc-body').html(h);
		$('#fhc-sub').text('Trò chuyện');
	},
	convRowHtml: function(c){
		var cid = parseInt(c.channel_id, 10) || 0;
		var ub = (parseInt(c.unread, 10) || 0) > 0 ? '<span class="fhc-conv-ub">' + (c.unread > 99 ? '99+' : c.unread) + '</span>' : '';
		var pre = c.last_sender ? ('<b class="fhc-conv-who">' + this.esc(c.last_sender) + ':</b> ') : '';	// ai nói (Zalo)
		var last = c.last_msg ? (pre + this.esc(c.last_msg)) : 'Chưa có tin nhắn';
		return '<div class="fhc-conv" data-cid="' + cid + '">'
			+ this.convAvatarHtml(c)
			+ '<div class="fhc-conv-main"><div class="fhc-conv-nm">' + this.esc(c.name || '') + '</div><div class="fhc-conv-last">' + last + '</div></div>'
			+ '<div class="fhc-conv-meta"><div class="fhc-conv-tm">' + this.esc(c.last_at || '') + '</div>' + ub + '</div></div>';
	},
	convAvatarHtml: function(c){
		var cid = parseInt(c.channel_id, 10) || 0;
		var t = this.AV_TINTS[cid % this.AV_TINTS.length];
		var ch0 = (c.name || '?').charAt(0).toUpperCase();
		var glyph = '<span class="fhc-conv-gl" style="background:' + t[0] + ';color:' + t[1] + '">' + this.esc(ch0) + '</span>';
		var img = c.image ? '<img class="fhc-conv-img" src="' + this.esc(c.image) + '" onerror="this.remove()">' : '';
		return '<div class="fhc-conv-av">' + glyph + img + '</div>';
	},
	openConv: function(cid){
		if(!(cid > 0)){ return; }
		this.view = 'thread';
		this.setHeaderMode('thread', this.findChan(cid));
		this.openChannel(cid);	// ĐÓNG BĂNG: set #fhc-sub, showComposer, loadHistory
	},
	backToList: function(){
		this.stopPoll();
		this.cancelReply();
		this.hideTyping();
		this.curCh = 0;
		this.view = 'list';
		this.showComposer(false);
		this.setHeaderMode('list');
		this.renderConvList();
	},
	setHeaderMode: function(mode, ch){
		var root = $('#fhchat');
		root.toggleClass('fhc-mode-thread', mode === 'thread').toggleClass('fhc-mode-list', mode !== 'thread');
		root.toggleClass('fhc-has-gmenu', !!(ch && parseInt(ch.type, 10) === 4));	// ⋮ chỉ ở nhóm type4
	},
	renderTabs: function(){
		$('.fhc-tabs .fhc-tab-ch').remove();
		var html = '';
		for(var i = 0; i < this.chans.length; i++){
			var c = this.chans[i], dt = 'ch_' + c.channel_id;
			var ub = c.unread > 0 ? '<span class="fhc-ub">' + (c.unread > 99 ? '99+' : c.unread) + '</span>' : '';
			var on = (this.tab === dt) ? ' active' : '';
			html += '<div class="fhc-tab fhc-tab-ch' + on + '" data-tab="' + dt + '">' + this.esc(c.name) + ub + '</div>';
		}
		$('.fhc-tabs').append(html);
		this.refreshBadge();
	},
	refreshBadge: function(){
		var n = 0;
		for(var i = 0; i < this.chans.length; i++){ n += parseInt(this.chans[i].unread, 10) || 0; }
		if(n > 0){ $('#fhc-badge').text(n > 99 ? '99+' : n).show(); } else { $('#fhc-badge').hide(); }
	},
	findChan: function(cid){
		for(var i = 0; i < this.chans.length; i++){ if(this.chans[i].channel_id == cid){ return this.chans[i]; } }
		return null;
	},

	/* ---- Realtime (Socket.IO — tái dùng $Core.socket) ---- */
	socketInit: function(){
		var self = this;
		if(this.socketBound || typeof $Core === 'undefined' || !$Core.socket){ return; }
		this.socketBound = true;
		$Core.socket.on('chat:message', function(p){ if(!p){ return; } if(p.message && p.message.reaction){ self.onReaction(p.channel_id, p.message.reaction); return; } self.onIncoming(parseInt(p.channel_id, 10), p.message); });
		$Core.socket.on('chat:typing', function(d){ self.showTyping(d); });
		// (Re)connect → join lại room + nạp bù kênh đang mở (lấp tin rớt lúc mất kết nối).
		$Core.socket.on('connect', function(){ self.subscribe(); if(self.curCh > 0){ self.loadHistory(self.curCh, self.lastId, false); } });
		// Bị xoá khỏi nhóm / nhóm giải tán → thoát kênh + nạp lại list (kênh mất → không còn token → không join lại).
		$Core.socket.on('chat:kick', function(d){
			if(!d || !(parseInt(d.channel_id, 10) > 0)){ return; }
			var me = (typeof profile_id !== 'undefined') ? parseInt(profile_id, 10) : 0;
			var tgt = parseInt(d.profile_id, 10) || 0;
			if(tgt !== 0 && tgt !== me){ return; }	// không phải mình → bỏ qua
			var kc = parseInt(d.channel_id, 10);
			for(var i = self.chans.length - 1; i >= 0; i--){ if(self.chans[i].channel_id == kc){ self.chans.splice(i, 1); } }	// xoá kênh khỏi list
			self.closeManage();
			if(self.curCh === kc){ self.warn('Bạn đã rời / bị mời khỏi nhóm'); self.backToList(); }	// đang mở kênh bị kick → về list
			else if(self.tab === 'chat' && self.view === 'list'){ self.renderConvList(); }
			self.refreshBadge();
		});
	},
	subscribe: function(){
		if(typeof $Core === 'undefined' || !$Core.socket || !this.chans.length){ return; }
		var subs = [];
		for(var i = 0; i < this.chans.length; i++){ subs.push({ id: this.chans[i].channel_id, token: this.chans[i].token }); }
		$Core.socket.emit('chat:subscribe', { profile_id: (typeof profile_id !== 'undefined' ? profile_id : 0), channels: subs });
	},
	/* ---------------- Tạo nhóm (P2 — UI tạm, P4 đưa vào list) ---------------- */
	openCreateGroup: function(){
		this.cgMode = 'create';
		$('#fhc-cg').removeClass('fhc-cg-addmode');
		$('#fhc-cg .fhc-cg-title').text('Tạo nhóm chat');
		$('#fhc-cg-submit').text('Tạo nhóm');
		this._openCgModal();
	},
	/* Mở modal picker ở chế độ "Thêm thành viên" vào nhóm hiện có (tái dùng TOÀN BỘ picker tạo nhóm — DRY). */
	mngAddOpen: function(){
		this.cgMode = 'add';
		this.cgAddCid = this.mngCid || this.curCh;
		$('#fhc-cg').addClass('fhc-cg-addmode');
		$('#fhc-cg .fhc-cg-title').text('Thêm thành viên');
		$('#fhc-cg-submit').text('Thêm');
		this._openCgModal();
	},
	_openCgModal: function(){
		this.groupImg = null; this.picked = {};
		$('#fhc-cg-name').val(''); $('#fhc-cg-q').val(''); $('#fhc-cg-dept').val('');
		$('#fhc-cg-ava').css('background-image', '').removeClass('has');
		$('#fhc-cg-ava-ph').show();
		$('#fhc-cg-submit').prop('disabled', false);	// chống kẹt nếu đóng-mở giữa submit cũ
		this.updatePickCount();
		$('#fhc-cg').css('display', 'flex');
		this.loadStaff();
	},
	closeCreate: function(){
		$('#fhc-cg').css('display', 'none').removeClass('fhc-cg-addmode');
		this.groupImg = null; this.picked = {}; this.cgMode = 'create';
	},
	loadStaff: function(){
		var self = this;
		$('#fhc-cg-list').html('<div class="fhc-cg-empty">Đang tải…</div>');
		$.getJSON(this.url('staff'), function(r){
			if(!r || r.error){ $('#fhc-cg-list').html('<div class="fhc-cg-empty">Không tải được danh sách.</div>'); return; }
			self.staff = r.list || [];
			// Dropdown phòng (distinct theo dept_id).
			var seen = {}, opts = '<option value="">Tất cả phòng</option>';
			for(var i = 0; i < self.staff.length; i++){
				var d = self.staff[i].dept_id, nm = self.staff[i].dept_name || ('Phòng ' + d);
				if(d > 0 && !seen[d]){ seen[d] = 1; opts += '<option value="' + d + '">' + self.esc(nm) + '</option>'; }
			}
			$('#fhc-cg-dept').html(opts);
			self.renderStaffPicker();
		});
	},
	renderStaffPicker: function(){
		var dept = parseInt($('#fhc-cg-dept').val(), 10) || 0;
		var q = ($('#fhc-cg-q').val() || '').toLowerCase();
		var h = '', n = 0;
		for(var i = 0; i < this.staff.length; i++){
			var s = this.staff[i];
			if(dept > 0 && (parseInt(s.dept_id, 10) || 0) !== dept){ continue; }
			if(q !== '' && (s.name || '').toLowerCase().indexOf(q) === -1){ continue; }
			var on = (this.picked && this.picked[s.id]) ? ' on' : '';
			h += '<div class="fhc-cg-row' + on + '" onclick="$Core.chat.toggleStaff(' + (parseInt(s.id, 10) || 0) + ', this)">'
				+ '<img class="fhc-cg-av" src="' + this.esc(s.avatar || '') + '" onerror="this.style.visibility=\'hidden\'">'
				+ '<div class="fhc-cg-info"><div class="fhc-cg-nm">' + this.esc(s.name || '') + '</div><div class="fhc-cg-dp">' + this.esc(s.dept_name || '') + '</div></div>'
				+ '<span class="fhc-cg-ck"></span></div>';
			n++;
		}
		if(n === 0){ h = '<div class="fhc-cg-empty">Không có nhân viên phù hợp.</div>'; }
		$('#fhc-cg-list').html(h);
	},
	filterStaff: function(){ this.renderStaffPicker(); },
	toggleStaff: function(id, row){
		id = parseInt(id, 10) || 0;
		if(!(id > 0)){ return; }
		if(!this.picked){ this.picked = {}; }
		if(this.picked[id]){ delete this.picked[id]; $(row).removeClass('on'); }
		else { this.picked[id] = 1; $(row).addClass('on'); }
		this.updatePickCount();
	},
	updatePickCount: function(){
		var c = 0; if(this.picked){ for(var k in this.picked){ if(this.picked.hasOwnProperty(k)){ c++; } } }
		$('#fhc-cg-cnt').text('Đã chọn ' + c);
	},
	pickGroupImage: function(input){
		var self = this;
		if(!input.files || !input.files[0]){ return; }
		var reader = new FileReader();
		reader.onload = function(e){
			var img = new Image();
			img.onload = function(){
				var max = 256, w = img.width, h = img.height;
				if(w > h && w > max){ h = Math.round(h * max / w); w = max; }
				else if(h >= w && h > max){ w = Math.round(w * max / h); h = max; }
				var cv = document.createElement('canvas');
				cv.width = w; cv.height = h;
				cv.getContext('2d').drawImage(img, 0, 0, w, h);
				self.groupImg = cv.toDataURL('image/jpeg', 0.82);
				$('#fhc-cg-ava').css('background-image', 'url(' + self.groupImg + ')').addClass('has');
				$('#fhc-cg-ava-ph').hide();
			};
			img.src = e.target.result;
		};
		reader.readAsDataURL(input.files[0]);
		input.value = '';
	},
	submitCreate: function(){
		var self = this;
		if(this.cgMode === 'add'){ this.submitAddMembers(); return; }	// chế độ thêm-TV vào nhóm có sẵn
		var name = ($('#fhc-cg-name').val() || '').trim();
		if(name === ''){ this.warn('Nhập tên nhóm'); $('#fhc-cg-name').focus(); return; }
		var ids = []; if(this.picked){ for(var k in this.picked){ if(this.picked.hasOwnProperty(k)){ ids.push(parseInt(k, 10) || 0); } } }
		var $btn = $('#fhc-cg-submit'); if($btn.prop('disabled')){ return; }
		$btn.prop('disabled', true).text('Đang tạo…');
		$.post(this.url('create_group'), { name: name, image: this.groupImg || '', members: ids }, function(r){
			$btn.prop('disabled', false).text('Tạo nhóm');
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Tạo nhóm thất bại'); return; }
			self.addCreatedChannel(r.channel);
		}, 'json').fail(function(){ $btn.prop('disabled', false).text('Tạo nhóm'); self.warn('Lỗi kết nối'); });
	},
	addCreatedChannel: function(ch){
		if(!ch || !ch.channel_id){ this.closeCreate(); return; }
		this.chans.unshift(ch);
		this.subscribe();
		this.closeCreate();
		if(this.convFail){ this.renderTabs(); this.switchTab('ch_' + ch.channel_id); }	// vỏ tab cũ (fallback)
		else { this.openConv(parseInt(ch.channel_id, 10)); }	// vỏ nav mới (P4): mở thread nhóm vừa tạo
	},
	/* Submit chế độ "Thêm thành viên" (cgMode==='add') → POST group_members op=add. */
	submitAddMembers: function(){
		var self = this, cid = this.cgAddCid;
		var ids = []; if(this.picked){ for(var k in this.picked){ if(this.picked.hasOwnProperty(k)){ ids.push(parseInt(k, 10) || 0); } } }
		if(!ids.length){ this.warn('Chưa chọn thành viên'); return; }
		var $btn = $('#fhc-cg-submit'); if($btn.prop('disabled')){ return; }
		$btn.prop('disabled', true).text('Đang thêm…');
		$.post(this.url('group_members'), { channel_id: cid, op: 'add', member_ids: ids }, function(r){
			$btn.prop('disabled', false).text('Thêm');
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Lỗi'); return; }
			self.closeCreate();
			self.openGroupManage(cid);	// quay lại panel quản trị (đã thêm TV)
		}, 'json').fail(function(){ $btn.prop('disabled', false).text('Thêm'); self.warn('Lỗi kết nối'); });
	},
	/* ---------------- Quản trị nhóm (P3 — panel ⋮ trên thread; P4 nhúng vào vỏ nav) ---------------- */
	openGroupManage: function(cid){
		var self = this;
		cid = parseInt(cid, 10) || this.curCh;
		if(!(cid > 0)){ return; }
		this.mngCid = cid;
		$('#fhc-mng').html('<div class="fhc-mng-load">Đang tải…</div>').css('display', 'flex');
		$.post(this.url('group_info'), { channel_id: cid }, function(r){
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Lỗi'); self.closeManage(); return; }
			self.mngInfo = r;
			self.renderManage(r);
		}, 'json').fail(function(){ self.warn('Lỗi kết nối'); self.closeManage(); });
	},
	closeManage: function(){ $('#fhc-mng').css('display', 'none'); },
	renderManage: function(r){
		var own = !!parseInt(r.is_owner, 10), ms = r.members || [], h = '';
		h += '<div class="fhc-mng-box"><div class="fhc-mng-head"><button type="button" class="fhc-mng-x" onclick="$Core.chat.closeManage()" aria-label="Đóng">' + this.svg(this.I_CLOSE, 18) + '</button>'
			+ '<div class="fhc-mng-title">' + this.esc(r.name || 'Nhóm') + '</div></div>';
		h += '<div class="fhc-mng-body"><div class="fhc-mng-sec">Thành viên (' + ms.length + ')</div><div class="fhc-mng-list">';
		for(var i = 0; i < ms.length; i++){
			var m = ms[i], isOwn = parseInt(m.role, 10) === 1, pid = parseInt(m.profile_id, 10) || 0;
			h += '<div class="fhc-mng-row">'
				+ '<img class="fhc-cg-av" src="' + this.esc(m.avatar || '') + '" onerror="this.style.visibility=\'hidden\'">'
				+ '<div class="fhc-cg-info"><div class="fhc-cg-nm">' + this.esc(m.name || '') + (isOwn ? ' <span class="fhc-mng-badge">Chủ</span>' : '') + '</div></div>';
			if(own && !isOwn){
				h += '<button type="button" class="fhc-mng-mini" onclick="$Core.chat.mngTransfer(' + pid + ')" title="Chuyển quyền chủ">★</button>'
					+ '<button type="button" class="fhc-mng-mini danger" onclick="$Core.chat.mngRemove(' + pid + ')" title="Xoá khỏi nhóm">✕</button>';
			}
			h += '</div>';
		}
		h += '</div><div class="fhc-mng-acts">';
		if(own){
			h += '<button type="button" class="fhc-mng-btn" onclick="$Core.chat.mngAddOpen()">＋ Thêm thành viên</button>'
				+ '<button type="button" class="fhc-mng-btn" onclick="$Core.chat.mngRename()">Đổi tên nhóm</button>'
				+ '<label class="fhc-mng-btn">Đổi ảnh nhóm<input type="file" accept="image/*" style="display:none" onchange="$Core.chat.mngPickImage(this)"></label>'
				+ '<button type="button" class="fhc-mng-btn danger" onclick="$Core.chat.mngDisband()">Giải tán nhóm</button>';
		} else {
			h += '<button type="button" class="fhc-mng-btn danger" onclick="$Core.chat.mngLeave()">Rời nhóm</button>';
		}
		h += '</div></div></div>';	// /acts /body /box
		$('#fhc-mng').html(h);
	},
	mngRemove: function(pid){
		var self = this, cid = this.mngCid;
		if(!window.confirm('Xoá thành viên này khỏi nhóm?')){ return; }
		$.post(this.url('group_members'), { channel_id: cid, op: 'remove', member_id: pid }, function(r){
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Lỗi'); return; }
			self.openGroupManage(cid);
		}, 'json').fail(function(){ self.warn('Lỗi kết nối'); });
	},
	mngTransfer: function(pid){
		var self = this, cid = this.mngCid;
		if(!window.confirm('Chuyển quyền chủ nhóm cho người này? Bạn sẽ trở thành thành viên.')){ return; }
		$.post(this.url('group_transfer'), { channel_id: cid, to_id: pid }, function(r){
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Lỗi'); return; }
			self.openGroupManage(cid);
		}, 'json').fail(function(){ self.warn('Lỗi kết nối'); });
	},
	mngRename: function(){
		var self = this, cid = this.mngCid;
		var cur = (this.mngInfo && this.mngInfo.name) ? this.mngInfo.name : '';
		var nv = window.prompt('Tên nhóm mới:', cur);
		if(nv === null){ return; }
		nv = nv.trim(); if(nv === ''){ return; }
		$.post(this.url('group_update'), { channel_id: cid, name: nv }, function(r){
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Lỗi'); return; }
			$('#fhc-sub').text(nv);
			var ch = self.findChan(cid); if(ch){ ch.name = nv; }
			self.openGroupManage(cid);
		}, 'json').fail(function(){ self.warn('Lỗi kết nối'); });
	},
	mngPickImage: function(input){
		var self = this, cid = this.mngCid;
		if(!input.files || !input.files[0]){ return; }
		var reader = new FileReader();
		reader.onload = function(e){
			var img = new Image();
			img.onload = function(){
				var max = 256, w = img.width, h = img.height;
				if(w > h && w > max){ h = Math.round(h * max / w); w = max; }
				else if(h >= w && h > max){ w = Math.round(w * max / h); h = max; }
				var cv = document.createElement('canvas');
				cv.width = w; cv.height = h;
				cv.getContext('2d').drawImage(img, 0, 0, w, h);
				$.post(self.url('group_update'), { channel_id: cid, image: cv.toDataURL('image/jpeg', 0.82) }, function(r){
					if(!r || r.error){ self.warn(r && r.message ? r.message : 'Lỗi'); return; }
					self.openGroupManage(cid);
				}, 'json').fail(function(){ self.warn('Lỗi kết nối'); });
			};
			img.src = e.target.result;
		};
		reader.readAsDataURL(input.files[0]);
		input.value = '';
	},
	mngLeave: function(){
		var self = this, cid = this.mngCid;
		if(!window.confirm('Rời khỏi nhóm này?')){ return; }
		$.post(this.url('group_leave'), { channel_id: cid }, function(r){
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Lỗi'); return; }
			self._exitGroup(cid);
		}, 'json').fail(function(){ self.warn('Lỗi kết nối'); });
	},
	mngDisband: function(){
		var self = this, cid = this.mngCid;
		if(!window.confirm('Giải tán nhóm? Hành động không thể hoàn tác.')){ return; }
		$.post(this.url('group_disband'), { channel_id: cid }, function(r){
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Lỗi'); return; }
			self._exitGroup(cid);
		}, 'json').fail(function(){ self.warn('Lỗi kết nối'); });
	},
	_exitGroup: function(cid){
		this.closeManage();
		if(this.curCh === parseInt(cid, 10)){ this.curCh = 0; this.switchTab('checkin'); }
		this.loadChannels();
	},
	/* Nhận tin realtime: kênh đang mở → chèn bong bóng (dedup); kênh khác → +1 chưa đọc. */
	onIncoming: function(cid, m){
		if(!m || !(cid > 0)){ return; }
		m.is_me = (typeof profile_id !== 'undefined' && parseInt(m.profile_id, 10) === parseInt(profile_id, 10)) ? 1 : 0;	// TÍNH LẠI theo người nhận (PHP build theo người gửi)
		if(this.curCh === cid && this.view === 'thread'){
			var box = document.getElementById('fhc-body');
			var thread = box ? box.querySelector('.fhc-thread') : null;
			if(!thread){ $('#fhc-body').html('<div class="fhc-thread"></div>'); thread = document.querySelector('#fhc-body .fhc-thread'); }
			if(thread.querySelector('.fhc-msg[data-id="' + m.message_id + '"]')){ return; }	// đã có (tin của mình render lạc quan / poll bù)
			var near = box ? (box.scrollHeight - box.scrollTop - box.clientHeight) < 60 : true;
			thread.insertAdjacentHTML('beforeend', this.msgHtml(m));
			this.lastId = Math.max(this.lastId, parseInt(m.message_id, 10) || 0);
			this.hideTyping();
			if(near || m.is_me){ this.scrollBottom(); }
			this.afterRead(cid);
		} else if(!m.is_me){
			var ch = this.findChan(cid);
			if(ch){
				ch.unread = (parseInt(ch.unread, 10) || 0) + 1;
				ch.last_msg = (parseInt(m.type, 10) === 2) ? '[Hình ảnh]' : (m.content || '');	// preview dòng
				ch.last_at = m.time || ch.last_at || '';	// 'HH:i' (chat_msg_out)
				ch.last_ts = Math.floor(Date.now() / 1000);	// để renderConvList đẩy dòng lên đầu
				ch.last_sender = (m.name || '').split(' ').pop() || '';	// tên ngắn người nói (realtime; tin của mình bỏ qua nhánh này)
				if(this.convFail){ this.renderTabs(); }
				else if(this.tab === 'chat' && this.view === 'list'){ this.renderConvList(); }
				else { this.refreshBadge(); }
			}
		}
	},
	showTyping: function(d){
		if(!d || parseInt(d.channel_id, 10) !== this.curCh){ return; }
		if(typeof profile_id !== 'undefined' && parseInt(d.profile_id, 10) === parseInt(profile_id, 10)){ return; }
		var el = document.getElementById('fhc-typing');
		if(!el){ return; }
		el.textContent = (d.name ? d.name : 'Ai đó') + ' đang soạn…';
		el.style.display = 'block';
		var self = this;
		clearTimeout(this.typingTimer);
		this.typingTimer = setTimeout(function(){ self.hideTyping(); }, 3000);
	},
	hideTyping: function(){
		clearTimeout(this.typingTimer);
		var el = document.getElementById('fhc-typing');
		if(el){ el.style.display = 'none'; el.textContent = ''; }
	},
	emitTyping: function(){
		if(!(this.curCh > 0) || typeof $Core === 'undefined' || !$Core.socket){ return; }
		var now = Date.now();
		if(now - (this.typingSentT || 0) < 2000){ return; }	// throttle ~2s
		this.typingSentT = now;
		var nm = (typeof logdedUser !== 'undefined' && logdedUser) ? logdedUser.full_name : '';
		$Core.socket.emit('chat:typing', { channel_id: this.curCh, profile_id: (typeof profile_id !== 'undefined' ? profile_id : 0), name: nm });
	},

	openChannel: function(cid){
		if(!(cid > 0)){ return; }
		this.hideTyping();
		this.cancelReply();	// reset quote khi vào/đổi kênh (chống reply_to_id treo sang kênh khác)
		this.stopPoll();
		this.curCh = cid;
		this.lastId = 0;
		var ch = this.findChan(cid);
		$('#fhc-sub').text(ch ? ch.name : 'Trò chuyện');
		this.showComposer(true);
		$('#fhc-body').html('<div class="fhc-empty">Đang tải…</div>');
		this.loadHistory(cid, 0, true);
	},
	loadHistory: function(cid, since, initial){
		var self = this;
		// Poll (since>0): kèm id tin đang hiện để server trả cảm xúc tươi (tự chữa khi broadcast rớt).
		var rids = [];
		if(!initial){ var els = document.querySelectorAll('#fhc-body .fhc-msg[data-id]'); for(var i = 0; i < els.length; i++){ rids.push(els[i].getAttribute('data-id')); } }
		$.post(this.url('history'), { channel_id: cid, since: since, react_ids: rids }, function(r){
			if(self.curCh != cid){ return; }	// đã chuyển kênh trong lúc chờ
			if(!r || r.error){ if(initial){ $('#fhc-body').html('<div class="fhc-empty">Không tải được tin nhắn.</div>'); } return; }
			if(initial){ self.histLastRead = parseInt(r.last_read_id, 10) || 0; }	// mốc đã đọc → đặt divider "Tin chưa đọc"
			self.renderThread(r.list || [], !initial);
			if(r.react_refresh){ for(var mid in r.react_refresh){ if(r.react_refresh.hasOwnProperty(mid)){ self.applyReactions(parseInt(mid, 10), r.react_refresh[mid]); } } }
		}, 'json');
	},
	renderThread: function(list, append){
		var box = document.getElementById('fhc-body');
		if(!append){
			if(!list.length){ box.innerHTML = '<div class="fhc-empty">Chưa có tin nhắn. Bắt đầu trò chuyện 👋</div>'; this.startPoll(); return; }
			var h = '<div class="fhc-thread">';
			var lastDay = '', unreadShown = false, lr = parseInt(this.histLastRead, 10) || 0;
			for(var i = 0; i < list.length; i++){
				var m = list[i], mid = parseInt(m.message_id, 10) || 0;
				if(m.day && m.day !== lastDay){ h += '<div class="fhc-daydiv">' + this.esc(m.day) + '</div>'; lastDay = m.day; }	// divider ngày
				if(!unreadShown && lr > 0 && mid > lr && !m.is_me){ h += '<div class="fhc-unread"><span>Tin chưa đọc</span></div>'; unreadShown = true; }	// divider chưa đọc (trước tin mới đầu tiên)
				h += this.msgHtml(m);
				this.lastId = Math.max(this.lastId, mid);
			}
			h += '</div>';
			box.innerHTML = h;
			this.scrollBottom();
			this.afterRead(this.curCh);
			this.startPoll();
			return;
		}
		if(!list.length){ return; }
		var thread = box.querySelector('.fhc-thread');
		if(!thread){ this.renderThread(list, false); return; }	// kênh vừa rỗng nay có tin
		var near = (box.scrollHeight - box.scrollTop - box.clientHeight) < 60;
		var added = 0;
		for(var j = 0; j < list.length; j++){
			var m = list[j];
			if(thread.querySelector('.fhc-msg[data-id="' + m.message_id + '"]')){ continue; }	// dedup tin của chính mình
			thread.insertAdjacentHTML('beforeend', this.msgHtml(m));
			this.lastId = Math.max(this.lastId, parseInt(m.message_id, 10) || 0);
			added++;
		}
		if(added && near){ this.scrollBottom(); }
		if(added){ this.afterRead(this.curCh); }
	},
	msgHtml: function(m){
		var me = m.is_me ? ' me' : '';
		var name = m.is_me ? '' : '<div class="fhc-mname">' + this.esc(m.name) + '</div>';
		var av = m.is_me ? '' : this.avatarHtml(m);
		// Trích dẫn tin gốc (quote) — bấm → nhảy tới tin gốc.
		var quote = m.reply_to ? '<div class="fhc-quote" data-to="' + (parseInt(m.reply_to.message_id, 10) || 0) + '"><b>' + this.esc(m.reply_to.name || 'Tin gốc') + '</b><span class="fhc-q-prev">' + this.esc(m.reply_to.preview || '') + '</span></div>' : '';
		var body, bcls = 'fhc-bub';
		var imgs = (m.images && m.images.length) ? m.images : (m.image ? [m.image] : null);
		if(parseInt(m.type, 10) === 2 && imgs){
			// Tin ảnh: album grid (kiểu Zalo) + caption tuỳ chọn; bấm 1 ảnh → lightbox.
			bcls = 'fhc-bub fhc-bub-img';
			var cap = (m.content && m.content !== '') ? '<div class="fhc-bcap">' + this.esc(m.content) + '</div>' : '';
			body = this.albumHtml(imgs) + cap;
		} else {
			body = '<div class="fhc-btx">' + this.esc(m.content) + '</div>';
		}
		// Tên + quote + nội dung + giờ NẰM TRONG bong bóng; badge cảm xúc dưới bóng; nút Cảm xúc/Trả lời hiện khi hover (mobile: long-press).
		var mine = (m.reactions && m.reactions.mine) ? m.reactions.mine : '';
		return '<div class="fhc-msg' + me + '" data-id="' + m.message_id + '" data-mine="' + this.esc(mine) + '">' + av
			+ '<div class="fhc-mwrap">'
			+ '<div class="' + bcls + '">' + name + quote + body
			+ '<div class="fhc-mtime">' + this.esc(m.time) + '</div></div>'
			+ this.reactBadgeHtml(m.reactions)
			+ '</div>'
			+ '<button type="button" class="fhc-react" data-id="' + m.message_id + '" aria-label="Thả cảm xúc">' + this.svg(this.I_SMILE, 16) + '</button>'
			+ '<button type="button" class="fhc-reply" data-id="' + m.message_id + '" aria-label="Trả lời">' + this.svg(this.I_REPLY, 15) + '</button>'
			+ '</div>';
	},
	/* Badge cảm xúc dưới bóng: các emoji đang có (thứ tự REACTS) + tổng số. Class .mine khi mình đã thả (không dùng :has cho trình duyệt cũ). */
	reactBadgeHtml: function(sum){
		if(!sum || !sum.total){ return ''; }
		var mine = sum.mine || '', total = parseInt(sum.total, 10) || 0;
		var h = '<div class="fhc-reacts' + (mine ? ' mine' : '') + '">';
		for(var i = 0; i < this.REACTS.length; i++){
			var c = this.REACTS[i], n = (sum.counts && sum.counts[c]) ? sum.counts[c] : 0;
			if(n > 0){ h += '<span class="fhc-rc">' + this.EMOJI[c] + '</span>'; }
		}
		// Zalo: 1 cảm xúc → CHỈ emoji (không số); ≥2 → thêm tổng số.
		if(total > 1){ h += '<span class="fhc-rn">' + total + '</span>'; }
		return h + '</div>';
	},
	/* Render lại badge 1 tin từ summary {counts,mine,total}; lưu mine cục bộ vào data-mine (broadcast KHÔNG mang mine). */
	applyReactions: function(mid, sum){
		var el = document.querySelector('#fhc-body .fhc-msg[data-id="' + mid + '"]');
		if(!el){ return; }
		el.setAttribute('data-mine', (sum && sum.mine) ? sum.mine : '');
		var wrap = el.querySelector('.fhc-mwrap');
		if(!wrap){ return; }
		var old = wrap.querySelector('.fhc-reacts');
		if(old){ old.parentNode.removeChild(old); }
		var html = this.reactBadgeHtml(sum);
		if(html){ wrap.insertAdjacentHTML('beforeend', html); }
	},
	/* POST toggle cảm xúc; cập nhật badge từ RESPONSE (có mine của chính mình). */
	react: function(mid, code){
		var self = this, cid = this.curCh;
		$.post(this.url('react'), { channel_id: cid, message_id: mid, emoji: code }, function(r){
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Lỗi'); return; }
			if(self.curCh != cid){ return; }
			self.applyReactions(parseInt(mid, 10), r.reactions);
		}, 'json').fail(function(){ self.warn('Lỗi kết nối'); });
	},
	/* Broadcast cảm xúc (KHÔNG mine) → cập nhật counts, GIỮ mine cục bộ ở data-mine. */
	onReaction: function(cid, d){
		if(!d || this.curCh !== parseInt(cid, 10)){ return; }
		var el = document.querySelector('#fhc-body .fhc-msg[data-id="' + d.message_id + '"]');
		if(!el){ return; }
		var mine = el.getAttribute('data-mine') || '';
		this.applyReactions(parseInt(d.message_id, 10), { counts: d.counts || {}, mine: mine, total: parseInt(d.total, 10) || 0 });
	},
	/* Popup chọn 6 cảm xúc (+ Trả lời cho mobile) — 1 instance dùng chung, gắn trong #fhchat. */
	buildPicker: function(){
		if(this.pickerEl){ return; }
		var self = this;
		var el = document.createElement('div');
		el.className = 'fhc-picker'; el.id = 'fhc-picker'; el.style.display = 'none';
		(document.getElementById('fhchat') || document.body).appendChild(el);
		this.pickerEl = el;
		$(el).on('click', '.fhc-pk-emo', function(e){ e.stopPropagation(); self.react(self.pickerMid, $(this).attr('data-code')); self.closePicker(); });
		$(el).on('click', '.fhc-pk-reply', function(e){ e.stopPropagation(); var mid = self.pickerMid; self.closePicker(); self.startReply(mid); });
		$(document).on('click.fhcpk', function(){ self.closePicker(); });
		$('#fhc-body').on('scroll.fhcpk', function(){ self.closePicker(); });
		$(document).on('keydown.fhcpk', function(e){ if(e.which === 27){ self.closePicker(); } });
	},
	openPicker: function(mid, anchorEl){
		this.buildPicker();
		this.pickerMid = parseInt(mid, 10) || 0;
		if(!(this.pickerMid > 0)){ return; }
		var msgEl = document.querySelector('#fhc-body .fhc-msg[data-id="' + this.pickerMid + '"]');
		var mine = msgEl ? (msgEl.getAttribute('data-mine') || '') : '';
		var h = '';
		for(var i = 0; i < this.REACTS.length; i++){
			var c = this.REACTS[i];
			h += '<button type="button" class="fhc-pk-emo' + (c === mine ? ' on' : '') + '" data-code="' + c + '" aria-label="' + c + '">' + this.EMOJI[c] + '</button>';
		}
		h += '<span class="fhc-pk-sep"></span><button type="button" class="fhc-pk-reply" aria-label="Trả lời">' + this.svg(this.I_REPLY, 17) + '</button>';
		this.pickerEl.innerHTML = h;
		this.pickerEl.style.display = 'flex';
		// Zalo-style: nổi NGAY TRÊN bóng tin, canh theo phía tin (nhận=trái, gửi=phải); thiếu chỗ trên thì lật xuống.
		var bub = msgEl ? (msgEl.querySelector('.fhc-bub') || msgEl) : null;
		var r = bub ? bub.getBoundingClientRect() : (anchorEl && anchorEl.getBoundingClientRect ? anchorEl.getBoundingClientRect() : null);
		if(r){
			var pw = this.pickerEl.offsetWidth, ph = this.pickerEl.offsetHeight;
			var isMe = msgEl ? (msgEl.className.indexOf('me') !== -1) : false;
			var left = isMe ? (r.right - pw) : r.left;
			left = Math.min(Math.max(8, left), window.innerWidth - pw - 8);
			var top = r.top - ph - 6;
			if(top < 8){ top = r.bottom + 6; }	// không đủ chỗ trên → lật xuống dưới bóng
			this.pickerEl.style.left = left + 'px';
			this.pickerEl.style.top = top + 'px';
		}
	},
	closePicker: function(){
		if(this.pickerEl){ this.pickerEl.style.display = 'none'; }
		this.pickerMid = 0;
	},
	/* Avatar: ảnh thật; thiếu/ lỗi → fallback chữ-cái nền tint (theo profile_id). */
	avatarHtml: function(m){
		var t = this.AV_TINTS[(parseInt(m.profile_id, 10) || 0) % this.AV_TINTS.length];
		var nm = (m.name || '').trim(), parts = nm ? nm.split(/\s+/) : [];
		var last = parts.length ? parts[parts.length - 1] : '';
		var initial = (last ? last.charAt(0) : '?').toUpperCase();
		var imgTag = m.avatar ? '<img src="' + this.esc(m.avatar) + '" alt="" onerror="this.remove()">' : '';
		return '<div class="fhc-mav" style="background:' + t[0] + ';color:' + t[1] + '">' + this.esc(initial) + imgTag + '</div>';
	},
	/* Grid album theo số lượng (1/2/3/4/≥5). ≥5: render đủ ô (CSS ẩn từ ô 5), ô 4 phủ "+N"; lightbox đọc đủ src. */
	albumHtml: function(imgs){
		var n = imgs.length;
		var lay = n === 1 ? '1' : (n === 2 ? '2' : (n === 3 ? '3' : (n === 4 ? '4' : 'more')));
		var h = '<div class="fhc-album album-' + lay + '">';
		for(var i = 0; i < n; i++){
			var more = (lay === 'more' && i === 3) ? '<span class="fhc-amore">+' + (n - 4) + '</span>' : '';
			h += '<div class="fhc-acell" data-idx="' + i + '"><img src="' + this.esc(imgs[i]) + '" alt="" loading="lazy">' + more + '</div>';
		}
		return h + '</div>';
	},

	sendMsg: function(){
		var self = this, cid = this.curCh;
		if(!(cid > 0)){ return; }
		var $in = $('#fhc-input'), txt = ($in.val() || '').replace(/\s+$/, '');
		if(txt === ''){ return; }
		$('#fhc-send').prop('disabled', true);
		$.post(this.url('send'), { channel_id: cid, content: txt, reply_to_id: this.replyTo }, function(r){
			$('#fhc-send').prop('disabled', false);
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Gửi thất bại'); return; }
			if(self.curCh != cid){ return; }
			var thread = document.querySelector('#fhc-body .fhc-thread');
			if(!thread){ $('#fhc-body').html('<div class="fhc-thread"></div>'); thread = document.querySelector('#fhc-body .fhc-thread'); }
			if(r.message && !thread.querySelector('.fhc-msg[data-id="' + r.message.message_id + '"]')){
				thread.insertAdjacentHTML('beforeend', self.msgHtml(r.message));
				self.lastId = Math.max(self.lastId, parseInt(r.message.message_id, 10) || 0);
			}
			$in.val('').css('height', 'auto');
			self.cancelReply();
			self.scrollBottom();
			self.afterRead(cid);
		}, 'json').fail(function(){
			$('#fhc-send').prop('disabled', false);
			self.warn('Lỗi kết nối, thử lại');
		});
	},
	/* Đính ảnh: chọn NHIỀU (cap 9) → nén từng tấm ≤1280 (giữ thứ tự) → gửi cả album 1 tin. */
	onChatPick: function(input){
		var self = this, cid = this.curCh;
		if(!(cid > 0) || !input.files || !input.files.length){ input.value = ''; return; }
		var files = Array.prototype.slice.call(input.files, 0, 9);
		input.value = '';
		var out = new Array(files.length), done = 0, n = files.length;
		var finish = function(){ if(++done === n){ self.sendImages(cid, out.filter(Boolean)); } };
		files.forEach(function(file, idx){
			var reader = new FileReader();
			reader.onload = function(e){
				var img = new Image();
				img.onload = function(){
					var max = 1280, w = img.width, h = img.height;
					if(w > max){ h = Math.round(h * max / w); w = max; }
					var cv = document.createElement('canvas');
					cv.width = w; cv.height = h;
					cv.getContext('2d').drawImage(img, 0, 0, w, h);
					out[idx] = cv.toDataURL('image/jpeg', 0.82);
					finish();
				};
				img.onerror = finish;
				img.src = e.target.result;
			};
			reader.onerror = finish;
			reader.readAsDataURL(file);
		});
	},
	sendImages: function(cid, dataUrls){
		var self = this;
		if(this.imgPosting || !dataUrls || !dataUrls.length){ return; }
		this.imgPosting = true;
		$('#fhc-attach').prop('disabled', true);
		var caption = ($('#fhc-input').val() || '').replace(/\s+$/, '');
		$.post(this.url('send_image'), { channel_id: cid, images: dataUrls, content: caption, reply_to_id: this.replyTo }, function(r){
			self.imgPosting = false;
			$('#fhc-attach').prop('disabled', false);
			if(!r || r.error){ self.warn(r && r.message ? r.message : 'Gửi ảnh thất bại'); return; }
			if(self.curCh != cid){ return; }
			var thread = document.querySelector('#fhc-body .fhc-thread');
			if(!thread){ $('#fhc-body').html('<div class="fhc-thread"></div>'); thread = document.querySelector('#fhc-body .fhc-thread'); }
			if(r.message && !thread.querySelector('.fhc-msg[data-id="' + r.message.message_id + '"]')){
				thread.insertAdjacentHTML('beforeend', self.msgHtml(r.message));
				self.lastId = Math.max(self.lastId, parseInt(r.message.message_id, 10) || 0);
			}
			$('#fhc-input').val('').css('height', 'auto');
			self.cancelReply();
			self.scrollBottom();
			self.afterRead(cid);
		}, 'json').fail(function(){
			self.imgPosting = false;
			$('#fhc-attach').prop('disabled', false);
			self.warn('Lỗi kết nối, thử lại');
		});
	},

	/* Bấm 1 ô album → mở lightbox với TOÀN BỘ ảnh của album (kể cả ô ẩn ≥5) tại đúng vị trí. */
	onAlbumClick: function(cell){
		var album = $(cell).closest('.fhc-album')[0];
		if(!album){ return; }
		var imgs = album.querySelectorAll('.fhc-acell img'), srcs = [];
		for(var i = 0; i < imgs.length; i++){ srcs.push(imgs[i].getAttribute('src')); }
		this.lbOpen(srcs, parseInt(cell.getAttribute('data-idx'), 10) || 0);
	},

	/* ---- Lightbox xem ảnh (click → zoom, vuốt qua ảnh, đếm i/n) ---- */
	lbInit: function(){
		if(this.lbEl){ return; }
		var self = this;
		var el = document.createElement('div');
		el.className = 'fhc-lb';
		el.id = 'fhc-lb';
		el.innerHTML = '<div class="fhc-lb-bar"><span class="fhc-lb-count" id="fhc-lb-count"></span>'
			+ '<button type="button" class="fhc-lb-x" id="fhc-lb-x" aria-label="Đóng">' + this.svg(this.I_CLOSE, 22) + '</button></div>'
			+ '<button type="button" class="fhc-lb-nav fhc-lb-prev" id="fhc-lb-prev" aria-label="Trước">' + this.svg(this.I_CHEVL, 30) + '</button>'
			+ '<div class="fhc-lb-stage" id="fhc-lb-stage"><img class="fhc-lb-img" id="fhc-lb-img" alt=""></div>'
			+ '<button type="button" class="fhc-lb-nav fhc-lb-next" id="fhc-lb-next" aria-label="Sau">' + this.svg(this.I_CHEVR, 30) + '</button>';
		document.body.appendChild(el);
		this.lbEl = el;
		$(el).on('click', '#fhc-lb-x', function(){ self.lbClose(); });
		$(el).on('click', '#fhc-lb-prev', function(e){ e.stopPropagation(); self.lbNav(-1); });
		$(el).on('click', '#fhc-lb-next', function(e){ e.stopPropagation(); self.lbNav(1); });
		// Bấm nền (ngoài ảnh) khi chưa zoom → đóng.
		$(el).on('click', '#fhc-lb-stage', function(e){ if(e.target.id === 'fhc-lb-stage' && self.lbScale === 1){ self.lbClose(); } });
		this.lbBindGestures(el);
	},
	lbBindGestures: function(el){
		var self = this, img = el.querySelector('#fhc-lb-img');
		var down = false, moved = false, sx = 0, sy = 0, ox = 0, oy = 0;
		var start = function(x, y){ down = true; moved = false; sx = x; sy = y; ox = self.lbTx; oy = self.lbTy; };
		var move = function(x, y){
			if(!down){ return; }
			var dx = x - sx, dy = y - sy;
			if(Math.abs(dx) > 8 || Math.abs(dy) > 8){ moved = true; }
			if(self.lbScale > 1){ self.lbTx = ox + dx; self.lbTy = oy + dy; self.lbApply(); }	// pan khi đang zoom
		};
		var end = function(x, y){
			if(!down){ return; }
			down = false;
			var dx = x - sx;
			if(self.lbScale === 1 && moved && Math.abs(dx) > 50){ self.lbNav(dx < 0 ? 1 : -1); return; }	// vuốt đổi ảnh
			if(!moved){ self.lbZoomToggle(); }	// chạm/không kéo → bật/tắt zoom
		};
		img.addEventListener('mousedown', function(e){ e.preventDefault(); start(e.clientX, e.clientY); });
		document.addEventListener('mousemove', function(e){ if(down){ move(e.clientX, e.clientY); } });
		document.addEventListener('mouseup', function(e){ if(down){ end(e.clientX, e.clientY); } });
		img.addEventListener('touchstart', function(e){ var t = e.touches[0]; start(t.clientX, t.clientY); }, { passive: true });
		img.addEventListener('touchmove', function(e){ var t = e.touches[0]; if(self.lbScale > 1){ e.preventDefault(); } move(t.clientX, t.clientY); }, { passive: false });
		img.addEventListener('touchend', function(e){ var t = (e.changedTouches && e.changedTouches[0]) || {}; end(t.clientX || sx, t.clientY || sy); });
		$(document).on('keydown.fhclb', function(e){
			if(!self.lbEl || !self.lbEl.classList.contains('fhc-open')){ return; }
			if(e.which === 27){ self.lbClose(); } else if(e.which === 37){ self.lbNav(-1); } else if(e.which === 39){ self.lbNav(1); }
		});
	},
	lbOpen: function(srcs, idx){
		if(!srcs || !srcs.length){ return; }
		this.lbInit();
		this.lbSrcs = srcs;
		this.lbIdx = Math.max(0, Math.min(idx || 0, srcs.length - 1));
		this.lbEl.classList.add('fhc-open');
		this.lbShow();
	},
	lbShow: function(){
		this.lbScale = 1; this.lbTx = 0; this.lbTy = 0;
		var img = this.lbEl.querySelector('#fhc-lb-img');
		img.src = this.lbSrcs[this.lbIdx];
		this.lbApply();
		document.getElementById('fhc-lb-count').textContent = (this.lbIdx + 1) + ' / ' + this.lbSrcs.length;
		var multi = this.lbSrcs.length > 1 ? 'flex' : 'none';
		document.getElementById('fhc-lb-prev').style.display = multi;
		document.getElementById('fhc-lb-next').style.display = multi;
	},
	lbApply: function(){
		var img = this.lbEl.querySelector('#fhc-lb-img');
		img.style.transform = 'translate(' + this.lbTx + 'px,' + this.lbTy + 'px) scale(' + this.lbScale + ')';
		img.style.cursor = this.lbScale > 1 ? 'grab' : 'zoom-in';
	},
	lbZoomToggle: function(){
		this.lbScale = this.lbScale > 1 ? 1 : 2.4;
		this.lbTx = 0; this.lbTy = 0;
		this.lbApply();
	},
	lbNav: function(d){
		if(!this.lbSrcs || this.lbSrcs.length < 2){ return; }
		this.lbIdx = (this.lbIdx + d + this.lbSrcs.length) % this.lbSrcs.length;
		this.lbShow();
	},
	lbClose: function(){
		if(this.lbEl){ this.lbEl.classList.remove('fhc-open'); }
	},
	warn: function(msg){
		if($Core.alert && $Core.alert.error){ $Core.alert.error(msg); } else { alert(msg); }
	},

	/* ---- Quote / trả lời ---- */
	startReply: function(mid){
		mid = parseInt(mid, 10) || 0;
		var el = document.querySelector('#fhc-body .fhc-msg[data-id="' + mid + '"]');
		if(!el){ return; }
		var nmEl = el.querySelector('.fhc-mname'), txEl = el.querySelector('.fhc-btx');
		var name = nmEl ? nmEl.textContent : (el.classList.contains('me') ? 'Chính bạn' : 'Tin');
		var prev = txEl ? txEl.textContent : (el.querySelector('.fhc-album') ? '[Hình ảnh]' : '');
		this.replyTo = mid;
		var bar = document.getElementById('fhc-reply-bar');
		bar.querySelector('.fhc-rb-name').textContent = name;
		bar.querySelector('.fhc-rb-prev').textContent = prev;
		bar.style.display = 'flex';
		$('#fhc-input').focus();
	},
	cancelReply: function(){
		this.replyTo = 0;
		var bar = document.getElementById('fhc-reply-bar');
		if(bar){ bar.style.display = 'none'; }
	},
	scrollToMsg: function(mid){
		var el = document.querySelector('#fhc-body .fhc-msg[data-id="' + (parseInt(mid, 10) || 0) + '"]');
		if(!el){ return; }
		el.scrollIntoView({ block: 'center', behavior: 'smooth' });
		el.classList.add('fhc-flash');
		setTimeout(function(){ el.classList.remove('fhc-flash'); }, 1200);
	},

	/* Đánh dấu đã đọc tới lastId + xoá badge kênh + cập nhật badge FAB. */
	afterRead: function(cid){
		$.post(this.url('read'), { channel_id: cid, last_id: this.lastId });
		var ch = this.findChan(cid);
		if(ch){ ch.unread = 0; }
		$('.fhc-conv[data-cid="' + cid + '"] .fhc-conv-ub, .fhc-tab[data-tab="ch_' + cid + '"] .fhc-ub').remove();
		this.refreshBadge();
	},
	startPoll: function(){
		var self = this;
		this.stopPoll();
		// Socket là kênh đẩy chính; poll chỉ là lưới an toàn (15s) phòng socket rớt im lặng.
		this.pollTimer = setInterval(function(){
			if(self.curCh > 0 && !document.hidden && $('#fhc-panel').hasClass('fhc-open')){ self.loadHistory(self.curCh, self.lastId, false); }
		}, 15000);
	},
	stopPoll: function(){
		if(this.pollTimer){ clearInterval(this.pollTimer); this.pollTimer = null; }
	},
	showComposer: function(on){
		$('#fhc-composer').css('display', on ? 'block' : 'none');	// container dọc: pill (.fhc-cbar) + dòng gợi ý
		if(on){ $('#fhc-input').val('').css('height', 'auto'); }
	},
	scrollBottom: function(){
		var b = document.getElementById('fhc-body');
		if(b){ b.scrollTop = b.scrollHeight; }
	},
	svg: function(d, s, cls){
		return '<svg class="' + (cls || '') + '" viewBox="0 0 24 24" width="' + (s || 16) + '" height="' + (s || 16) + '" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' + d + '</svg>';
	},
	esc: function(x){ 
		return String(x == null ? '' : x).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	},
	I_CAM: '<path d="M14.5 5h-5L8 7H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-1.5-2Z"/><circle cx="12" cy="13" r="3.4"/>',
	I_CHECK: '<path d="M20 6 9 17l-5-5"/>',
	I_CLOCK: '<circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 1.8"/>',
	I_PIN: '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="2.6"/>',
	I_SPIN: '<path d="M21 12a9 9 0 1 1-6.2-8.6"/>',
	I_SUNRISE: '<path d="M17 18a5 5 0 0 0-10 0"/><line x1="12" y1="2" x2="12" y2="9"/><line x1="4.22" y1="10.22" x2="5.64" y2="11.64"/><line x1="1" y1="18" x2="3" y2="18"/><line x1="21" y1="18" x2="23" y2="18"/><line x1="18.36" y1="11.64" x2="19.78" y2="10.22"/><line x1="23" y1="22" x2="1" y2="22"/><polyline points="8 6 12 2 16 6"/>',
	I_IMAGE: '<rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.6"/><path d="m21 15-5-5L5 21"/>',
	I_CLOSE: '<path d="M18 6 6 18M6 6l12 12"/>',
	I_CHEVL: '<path d="m15 18-6-6 6-6"/>',
	I_CHEVR: '<path d="m9 18 6-6-6-6"/>',
	I_REPLY: '<path d="M9 17l-5-5 5-5"/><path d="M4 12h11a5 5 0 0 1 5 5v1"/>',
	I_SMILE: '<circle cx="12" cy="12" r="9"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>'
};

$(document).ready(function(){ 
    $Core.chat.init(); 
});
