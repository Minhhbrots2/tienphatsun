<div id="fhchat">
	<button type="button" class="fhc-fab" id="fhc-fab" aria-label="Chat nội bộ" onclick="$Core.chat.open()">
		<svg viewBox="0 0 24 24" width="25" height="25" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
			<path d="M21 15a3 3 0 0 1-3 3H8l-5 4V6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3Z"/>
		</svg>
		<span class="fhc-badge" id="fhc-badge" style="display:none">0</span>
	</button>
	<div class="fhc-panel" id="fhc-panel">
		<div class="fhc-head">
			<button type="button" class="fhc-back" aria-label="Quay lại" onclick="$Core.chat.backToList()">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
			</button>
			<div class="fhc-logo"><b>FH</b></div>
			<div class="fhc-ti"><div class="fhc-t">Chat nội bộ</div><div class="fhc-s" id="fhc-sub">Trò chuyện</div></div>
			<button type="button" class="fhc-x" id="fhc-manage" aria-label="Quản lý nhóm" onclick="$Core.chat.openGroupManage($Core.chat.curCh)">
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="12" cy="5" r="1.3"/><circle cx="12" cy="12" r="1.3"/><circle cx="12" cy="19" r="1.3"/></svg>
			</button>
			<button type="button" class="fhc-x" id="fhc-close" aria-label="Đóng" onclick="$Core.chat.close()">
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
					<path d="M18 6 6 18M6 6l12 12"/>
				</svg>
			</button>
		</div>
		<div class="fhc-tabs">
			<div class="fhc-tab active" data-tab="chat">Trò chuyện</div>
			<div class="fhc-tab" data-tab="checkin">
				<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="4" width="18" height="18" rx="3"/>
					<path d="M16 2v4M8 2v4M3 10h18"/>
				</svg> Check-in
			</div>
		</div>
		<div class="fhc-body" id="fhc-body"></div>
			<div class="fhc-typing" id="fhc-typing" style="display:none"></div>
			<div class="fhc-reply-bar" id="fhc-reply-bar" style="display:none">
				<svg class="fhc-rb-ic" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" 
					stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
					<path d="M9 17l-5-5 5-5"/>
					<path d="M4 12h11a5 5 0 0 1 5 5v1"/>
				</svg>
				<div class="fhc-rb-body"><div class="fhc-rb-name"></div><div class="fhc-rb-prev"></div></div>
				<button type="button" class="fhc-rb-x" id="fhc-rb-x" aria-label="Huỷ trả lời" onclick="$Core.chat.cancelReply()">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
					<path d="M18 6 6 18M6 6l12 12"/></svg>
				</button>
			</div>
		<div class="fhc-composer d-flex flex-column gap-1" id="fhc-composer" style="display:none">
			<div class="fhc-cbar">
				<button type="button" class="fhc-attach" id="fhc-attach" aria-label="Đính kèm ảnh" onclick="$Core.chat.onAttach()">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
						<path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
					</svg>
				</button>
				<textarea id="fhc-input" rows="1" placeholder="Nhập tin nhắn…" maxlength="2000" onkeydown="$Core.chat.onInputKey(event)" 
					oninput="$Core.chat.onInputResize(this)"></textarea>
				<button type="button" class="fhc-send" id="fhc-send" aria-label="Gửi" onclick="$Core.chat.sendMsg()">
					<svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
						<path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7Z"/>
					</svg>
				</button>
			</div>
			<div class="fhc-hint text-center">Enter gửi · <b>Shift</b>+Enter xuống dòng</div>
		</div>
	</div>
	<!-- Modal Tạo nhóm (overlay trên panel; z-index > panel ở chat.css). UI tạm — P4 đưa vào list Zalo. -->
	<div class="fhc-cg" id="fhc-cg" style="display:none">
		<div class="fhc-cg-box">
			<div class="fhc-cg-head">
				<div class="fhc-cg-title">Tạo nhóm chat</div>
				<button type="button" class="fhc-cg-x" onclick="$Core.chat.closeCreate()" aria-label="Đóng">
					<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
				</button>
			</div>
			<div class="fhc-cg-top">
				<label class="fhc-cg-ava" id="fhc-cg-ava" title="Chọn ảnh nhóm">
					<input type="file" id="fhc-cg-file" accept="image/*" style="display:none" onchange="$Core.chat.pickGroupImage(this)">
					<span class="fhc-cg-ava-ph" id="fhc-cg-ava-ph">＋</span>
				</label>
				<input type="text" class="fhc-cg-name" id="fhc-cg-name" placeholder="Tên nhóm…" maxlength="120">
			</div>
			<div class="fhc-cg-filter">
				<select class="fhc-cg-dept" id="fhc-cg-dept" onchange="$Core.chat.filterStaff()"><option value="">Tất cả phòng</option></select>
				<input type="text" class="fhc-cg-q" id="fhc-cg-q" placeholder="Tìm tên…" oninput="$Core.chat.filterStaff()">
			</div>
			<div class="fhc-cg-list" id="fhc-cg-list"><div class="fhc-cg-empty">Đang tải…</div></div>
			<div class="fhc-cg-foot">
				<span class="fhc-cg-cnt" id="fhc-cg-cnt">Đã chọn 0</span>
				<button type="button" class="fhc-cg-btn" id="fhc-cg-submit" onclick="$Core.chat.submitCreate()">Tạo nhóm</button>
			</div>
		</div>
	</div>
	<!-- Panel quản trị nhóm (P3) — nội dung render động bởi $Core.chat.renderManage. Overlay trên panel. -->
	<div class="fhc-mng" id="fhc-mng" style="display:none"></div>
	<input type="file" id="fhc-file" accept="image/*" capture="environment" style="display:none" onchange="$Core.chat.onPick(this)">
	<input type="file" id="fhc-chatfile" accept="image/*" multiple style="display:none" onchange="$Core.chat.onChatPick(this)">
</div>
