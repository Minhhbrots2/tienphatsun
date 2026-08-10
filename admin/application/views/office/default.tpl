<link rel="stylesheet" type="text/css" href="{$URL_JS}/leaflet/leaflet.css?v={$upd_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/office.css?v={$upd_version}" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<header class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Cấu hình toạ độ văn phòng (Check-in)</h1>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>

<div class="ui-layout ui-layout--full-width">
	<div class="office-wrap">

		<div class="of-note">
			ℹ️ Văn phòng = <b>7 dòng <code class="of-k">_OFFICE</code></b> trong <code class="of-k">default_setting</code>. Form này chỉ thêm <b>toạ độ + bán kính</b> vào từng VP. <b>Check-in ở văn phòng nào cũng được</b> — không gán phòng ban; khung giờ áp dụng toàn hệ thống.
		</div>

		<div class="of-card" style="margin-bottom:16px">
			<div class="of-card-bd">
				<div class="of-section"><span class="of-ico">🕘</span> Khung giờ check-in (toàn hệ thống)</div>
				<div class="of-coordbar" style="align-items:flex-end">
					<div style="flex:1"><label class="of-fl">Giờ mở</label><input type="time" class="of-input" id="cfg_start" value="{$win_start}"></div>
					<div style="flex:1"><label class="of-fl">Giờ đóng (sau = vắng)</label><input type="time" class="of-input" id="cfg_end" value="{$win_end}"></div>
					<button type="button" class="of-btn of-btn-p of-btn-sm" id="of_save_window">Lưu khung giờ</button>
				</div>
				<div class="of-val" id="cfg_msg"></div>
				<div class="of-hint">Áp dụng cho mọi văn phòng. Trong khung + trong vùng = điểm danh hợp lệ; ngoài khung = không cho.</div>
			</div>
		</div>

		<div class="of-grid">

			<!-- ===== LEFT: danh sách VP ===== -->
			<div class="of-card">
				<div class="of-card-hd">Văn phòng (_OFFICE)</div>
				<div class="of-card-bd" id="office-list"></div>
			</div>

			<!-- ===== RIGHT: form ===== -->
			<div class="of-card">
				<div class="of-card-hd">
					<span id="formTitle">Chọn văn phòng để cấu hình</span>
					<label class="of-switch" title="Bật/tắt nhận check-in tại VP này">
						<input type="checkbox" id="f_active" checked><span class="of-sl"></span>
					</label>
				</div>
				<div class="of-card-bd">

					<div class="of-section"><span class="of-ico">①</span> Thông tin</div>
					<div class="of-fg">
						<label class="of-fl">Tên văn phòng</label>
						<input type="text" class="of-input" id="f_name" readonly>
					</div>
					<div class="of-fg">
						<label class="of-fl">Địa chỉ (tuỳ chọn)</label>
						<input type="text" class="of-input" id="f_address" placeholder="Số nhà, đường, quận…">
					</div>

					<div class="of-divider"></div>
					<div class="of-section"><span class="of-ico">②</span> Toạ độ &amp; bán kính (geofence)</div>
					<div id="office-map"></div>
					<div class="of-coordbar">
						<div style="flex:1"><input type="number" class="of-input" id="f_lat" step="0.000001" placeholder="Vĩ độ (lat)"></div>
						<div style="flex:1"><input type="number" class="of-input" id="f_lng" step="0.000001" placeholder="Kinh độ (lng)"></div>
						<button type="button" class="of-btn of-btn-g of-btn-sm" id="of_locate">📍 Vị trí của tôi</button>
					</div>
					<div class="of-fg" style="margin-top:10px">
						<label class="of-fl">Dán link Google Maps (tự tách toạ độ)</label>
						<input type="text" class="of-input" id="f_gmaps" placeholder="https://maps.google.com/...@20.97,105.95...">
					</div>
					<div class="of-fg">
						<label class="of-fl">Bán kính cho phép: <b id="radLbl">200</b> m</label>
						<input class="of-slider" type="range" id="f_radius" min="30" max="250" step="10" value="200">
						<div class="of-hint">Khuyến nghị 150–250m (đã cap tối đa 250m).</div>
					</div>
					<div class="of-fg">
						<label class="of-fl">Ngưỡng sai số GPS tối đa: <b id="accLbl">80</b> m</label>
						<input class="of-slider" type="range" id="f_acc" min="20" max="150" step="10" value="80">
					</div>

					<div class="of-divider"></div>
					<div class="of-global-note">
						<span>🕘</span>
						<div><b>Khung giờ check-in toàn hệ thống: <span id="of_window_label">{$checkin_window}</span></b> — Trong khung + trong vùng = điểm danh hợp lệ; ngoài vùng hoặc ngoài giờ = không cho.</div>
					</div>

					<div class="of-divider"></div>
					<div class="of-section"><span class="of-ico">③</span> Thử nghiệm geofence</div>
					<div class="of-test">
						<div class="of-hint" style="margin:0 0 8px">Bấm lên bản đồ để giả lập 1 vị trí check-in → xem khoảng cách &amp; kết quả.</div>
						<button type="button" class="of-btn of-btn-g of-btn-sm" id="of_sim">🎯 Bật chế độ chọn điểm thử</button>
						<div class="of-test-res" id="testRes">Chưa có điểm thử.</div>
					</div>

					<div class="of-foot">
						<button type="button" class="of-btn of-btn-p" id="of_save" disabled>Lưu cấu hình</button>
					</div>
					<div class="of-val" id="of_msg"></div>
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript">
	var OFFICE_DATA = {$offices_json};
</script>
