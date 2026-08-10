/**
 * $Core.office — cấu hình toạ độ/geofence văn phòng (module admin office).
 * Nguồn dữ liệu: OFFICE_DATA (default.tpl render từ các dòng _OFFICE).
 * Lưu: POST ?mod=office&act=save (MERGE more_information phía server).
 */
var $Core = $Core || {};
$Core.office = {
	map: null,
	marker: null,
	circle: null,
	cur: null,
	byId: {},
	simMode: false,
	simMarker: null,

	init: function(){
		if(typeof OFFICE_DATA === 'undefined' || !document.getElementById('office-map')){ return; }
		var self = this;
		OFFICE_DATA.forEach(function(o){ self.byId[o.setting_id] = o; });
		this.initMap();
		this.renderList();
		$('#f_radius').on('input', function(){
			$('#radLbl').text(this.value);
			if(self.circle){ self.circle.setRadius(parseInt(this.value, 10)); }
		});
		$('#f_acc').on('input', function(){ $('#accLbl').text(this.value); });
		$('#f_lat, #f_lng').on('input', function(){ self.syncFromInputs(); });
		$('#f_gmaps').on('input', function(){ self.parseGmaps(); });
		$('#of_locate').on('click', function(){ self.locateMe(); });
		$('#of_sim').on('click', function(){ self.simMode = true; });
		$('#of_save').on('click', function(){ self.save(); });
		$('#of_save_window').on('click', function(){ self.saveWindow(); });
		$(document).on('click', '.of-zone', function(){
			self.loadOffice(parseInt($(this).attr('data-id'), 10));
		});
	},

	initMap: function(){
		var self = this;
		this.map = L.map('office-map').setView([16.0, 107.5], 5);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(this.map);
		this.map.on('click', function(e){
			if(self.simMode){ self.doSim(e.latlng); return; }
			self.setCoord(e.latlng.lat, e.latlng.lng, false);
		});
		setTimeout(function(){ if(self.map){ self.map.invalidateSize(); } }, 250);
	},

	renderList: function(){
		var self = this, h = '';
		OFFICE_DATA.forEach(function(o){
			var hasCoord = o.lat !== 0 && o.lng !== 0;
			var meta = hasCoord ? ('📍 ' + o.lat.toFixed(4) + ', ' + o.lng.toFixed(4) + ' · ' + o.radius_m + 'm') : '<span class="of-zone-nocoord">⚠ chưa có toạ độ</span>';
			h += '<div class="of-zone' + (self.cur === o.setting_id ? ' active' : '') + '" data-id="' + o.setting_id + '">'
				+ '<div class="of-zone-nm">' + self.esc(o.title) + ' <span class="of-zone-code">' + self.esc(o.code) + '</span>'
				+ ' <span class="of-badge ' + (o.is_active ? 'of-b-on' : 'of-b-off') + '" style="margin-left:auto">' + (o.is_active ? 'Bật' : 'Tắt') + '</span></div>'
				+ '<div class="of-zone-meta">' + meta + '</div>'
				+ '</div>';
		});
		$('#office-list').html(h);
	},

	loadOffice: function(id){
		var o = this.byId[id];
		if(!o){ return; }
		this.cur = id;
		var lat = o.lat || 16.047, lng = o.lng || 108.206;
		$('#formTitle').text('Sửa: ' + o.title);
		$('#f_name').val(o.title);
		$('#f_address').val(o.address || '');
		$('#f_lat').val(lat.toFixed(6));
		$('#f_lng').val(lng.toFixed(6));
		$('#f_radius').val(o.radius_m);
		$('#radLbl').text(o.radius_m);
		$('#f_acc').val(o.max_accuracy_m);
		$('#accLbl').text(o.max_accuracy_m);
		$('#f_active').prop('checked', o.is_active == 1);
		$('#of_save').prop('disabled', false);
		$('#of_msg').html('');
		this.drawMarker(lat, lng, o.radius_m);
		this.map.setView([lat, lng], o.lat ? 16 : 6);
		this.renderList();
	},

	drawMarker: function(lat, lng, radius){
		var self = this;
		if(!this.marker){
			this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
			this.marker.on('drag', function(e){
				var p = e.target.getLatLng();
				self.setCoord(p.lat, p.lng, false);
			});
		} else {
			this.marker.setLatLng([lat, lng]);
		}
		if(!this.circle){
			this.circle = L.circle([lat, lng], { radius: radius, color: '#e11b22', fillColor: '#e11b22', fillOpacity: 0.12 }).addTo(this.map);
		} else {
			this.circle.setLatLng([lat, lng]).setRadius(radius);
		}
	},

	setCoord: function(lat, lng, recenter){
		if(this.cur === null){ return; }
		$('#f_lat').val(lat.toFixed(6));
		$('#f_lng').val(lng.toFixed(6));
		this.drawMarker(lat, lng, parseInt($('#f_radius').val(), 10));
		if(recenter){ this.map.panTo([lat, lng]); }
	},

	syncFromInputs: function(){
		var lat = parseFloat($('#f_lat').val()), lng = parseFloat($('#f_lng').val());
		if(isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180){ return; }
		this.drawMarker(lat, lng, parseInt($('#f_radius').val(), 10));
		this.map.panTo([lat, lng]);
	},

	parseGmaps: function(){
		var v = $('#f_gmaps').val();
		var m = v.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/) || v.match(/(-?\d+\.\d+),\s*(-?\d+\.\d+)/);
		if(m){
			this.setCoord(parseFloat(m[1]), parseFloat(m[2]), true);
			this.map.setView([parseFloat(m[1]), parseFloat(m[2])], 16);
		}
	},

	locateMe: function(){
		var self = this;
		if(!navigator.geolocation){ $Core.alert.error('Trình duyệt không hỗ trợ định vị'); return; }
		navigator.geolocation.getCurrentPosition(function(p){
			self.setCoord(p.coords.latitude, p.coords.longitude, true);
			self.map.setView([p.coords.latitude, p.coords.longitude], 17);
		}, function(){
			$Core.alert.error('Không lấy được vị trí (cần cho phép quyền truy cập)');
		});
	},

	/* Thử nghiệm geofence: bấm map → khoảng cách Haversine tới VP đang chọn. */
	doSim: function(ll){
		if(this.simMarker){ this.map.removeLayer(this.simMarker); }
		this.simMarker = L.circleMarker(ll, { radius: 7, color: '#2563eb', fillColor: '#3b82f6', fillOpacity: 0.9 }).addTo(this.map);
		var lat = parseFloat($('#f_lat').val()), lng = parseFloat($('#f_lng').val()), r = parseInt($('#f_radius').val(), 10);
		var d = this.haversine(lat, lng, ll.lat, ll.lng);
		var ok = d <= r;
		$('#testRes').html((ok ? '<span style="color:#16a34a">✔ TRONG vùng → cho check-in</span>' : '<span style="color:#dc2626">✘ NGOÀI vùng → KHÔNG cho check-in</span>') + ' — cách tâm <b>' + d + ' m</b> / bán kính ' + r + ' m');
		this.simMode = false;
	},

	haversine: function(lat1, lng1, lat2, lng2){
		var R = 6371000, t = Math.PI / 180;
		var dla = (lat2 - lat1) * t, dlo = (lng2 - lng1) * t;
		var a = Math.sin(dla / 2) * Math.sin(dla / 2) + Math.cos(lat1 * t) * Math.cos(lat2 * t) * Math.sin(dlo / 2) * Math.sin(dlo / 2);
		return Math.round(R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
	},

	save: function(){
		var self = this;
		if(this.cur === null){ return; }
		var lat = parseFloat($('#f_lat').val()), lng = parseFloat($('#f_lng').val());
		if(isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0){
			$('#of_msg').attr('class', 'of-val err').html('Chưa có toạ độ hợp lệ');
			return;
		}
		var data = {
			setting_id: this.cur,
			address: $('#f_address').val(),
			lat: lat,
			lng: lng,
			radius_m: parseInt($('#f_radius').val(), 10),
			max_accuracy_m: parseInt($('#f_acc').val(), 10),
			is_active: $('#f_active').is(':checked') ? 1 : 0
		};
		$('#of_save').prop('disabled', true);
		$.post(path_ajax_script + '/index.php?mod=' + mod + '&act=save', data, function(resp){
			$('#of_save').prop('disabled', false);
			if(!resp || resp.error){
				$('#of_msg').attr('class', 'of-val err').html((resp && resp.message) ? resp.message : 'Lưu thất bại');
				return;
			}
			$('#of_msg').attr('class', 'of-val ok').html(resp.message);
			var o = self.byId[self.cur];
			o.address = data.address;
			o.lat = data.lat;
			o.lng = data.lng;
			o.radius_m = resp.radius_m;
			o.max_accuracy_m = data.max_accuracy_m;
			o.is_active = data.is_active;
			$('#f_radius').val(resp.radius_m);
			$('#radLbl').text(resp.radius_m);
			if(self.circle){ self.circle.setRadius(resp.radius_m); }
			self.renderList();
		}, 'json');
	},

	/* Lưu khung giờ check-in toàn hệ thống. */
	saveWindow: function(){
		var start = $('#cfg_start').val(), end = $('#cfg_end').val();
		if(!start || !end){ $('#cfg_msg').attr('class', 'of-val err').html('Chưa nhập đủ giờ'); return; }
		$('#of_save_window').prop('disabled', true);
		$.post(path_ajax_script + '/index.php?mod=' + mod + '&act=save_window', { window_start: start, window_end: end }, function(resp){
			$('#of_save_window').prop('disabled', false);
			if(!resp || resp.error){
				$('#cfg_msg').attr('class', 'of-val err').html((resp && resp.message) ? resp.message : 'Lưu thất bại');
				return;
			}
			$('#cfg_msg').attr('class', 'of-val ok').html(resp.message);
			$('#of_window_label').text(resp.window);
		}, 'json');
	},

	esc: function(s){
		return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
	}
};

$(document).ready(function(){ $Core.office.init(); });
