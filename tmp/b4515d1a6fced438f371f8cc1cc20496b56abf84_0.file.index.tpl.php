<?php
/* Smarty version 3.1.33, created on 2026-08-06 14:36:27
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/chat/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a74397b3e6fe3_53013422',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b4515d1a6fced438f371f8cc1cc20496b56abf84' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/chat/index.tpl',
      1 => 1786001783,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a74397b3e6fe3_53013422 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div id="fhchat" ng-controller="ChatCtrl as c" ng-cloak
	ng-class="{'fhc-mode-thread': c.tab==='chat' && c.view==='thread', 'fhc-mode-list': !(c.tab==='chat' && c.view==='thread'), 'fhc-has-gmenu': c.isGroup()}">
	<?php echo '<script'; ?>
>window.CHAT_BOOT = { profile_id: (typeof profile_id !== 'undefined' ? profile_id : 0), ajax: (typeof path_ajax_script !== 'undefined' ? path_ajax_script : '') };<?php echo '</script'; ?>
>
	<!-- FAB -->
	<button type="button" class="fhc-fab" ng-class="{'fhc-hide': c.open}" ng-click="c.toggle()" aria-label="Chat nội bộ">
		<svg viewBox="0 0 24 24" width="25" height="25" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a3 3 0 0 1-3 3H8l-5 4V6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3Z"/></svg>
		<span class="fhc-badge" ng-show="c.unreadTotal > 0">{{ c.unreadTotal > 99 ? '99+' : c.unreadTotal }}</span>
	</button>
	<!-- Panel -->
	<div class="fhc-panel" ng-class="{'fhc-open': c.open}">
		<!-- Header -->
		<div class="fhc-head" ng-if="!(c.tab==='checkin' && c.ck.state==='journey')">
			<button type="button" class="fhc-back" ng-click="c.backToList()" aria-label="Quay lại">
				<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
			</button>
			<img class="fhc-logo-img" src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" width="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageWidth('LogoWhite');?>
" height="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageHeight('LogoWhite');?>
" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['header_configs']->value['CompanyName'], ENT_QUOTES, 'UTF-8', true);?>
" />
			<div class="fhc-ti">
				<div class="fhc-t">{{(c.tab==='checkin' ? 'Check-In' : 'Trò chuyện')}}</div>
				<div class="fhc-s">{{ c.view==='thread' ? c.curName() : (c.tab==='checkin' ? 'Kênh Check-in' : 'Trò chuyện') }}</div>
			</div>
			<button type="button" class="fhc-x" ng-if="c.tab==='chat' && c.view==='thread'" ng-click="c.searchToggle()" aria-label="Tìm tin nhắn">
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
			</button>
			<button type="button" class="fhc-x" ng-if="c.tab==='chat' && c.view==='thread' && c.isGroup()" ng-click="c.gAddOpen()" aria-label="Thêm thành viên">
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
					<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
					<circle cx="9" cy="7" r="4"/>
					<line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
				</svg>
			</button>
			<button type="button" class="fhc-x" id="fhc-manage" ng-click="c.manageGroup()" aria-label="Cài đặt nhóm">
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
			</button>
			<button type="button" class="fhc-x fhc-bell" ng-if="c.notifyNeedsEnable()" ng-click="c.askNotify()" aria-label="Bật thông báo">
					<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
				</button>
				<button type="button" class="fhc-x" ng-click="c.close()" aria-label="Đóng">
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
			</button>
		</div>
		<!-- Tabs -->
		<div class="fhc-tabs" ng-if="!(c.tab==='checkin' && c.ck.state==='journey')">
			<div class="fhc-tab" ng-class="{active: c.tab==='checkin'}" ng-click="c.setTab('checkin')">
				<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="4" width="18" height="18" rx="3"/>
					<path d="M16 2v4M8 2v4M3 10h18"/>
				</svg> Check-in
			</div>
			<div class="fhc-tab" ng-class="{active: c.tab==='chat'}" ng-click="c.setTab('chat')">
				<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 15a3 3 0 0 1-3 3H8l-5 4V6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3Z"></path>
				</svg> Trò chuyện
			</div>
		</div>

		<!-- Banner nhắc bật thông báo trình duyệt (khi chưa cấp quyền) -->
		<div class="fhc-notifybar" ng-if="c.notifyNeedsEnable() && c.notifyHint">
			<span class="fhc-nb-ic"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></span>
			<span class="fhc-nb-txt">Bật thông báo để không bỏ lỡ tin nhắn mới.</span>
			<button type="button" class="fhc-nb-on" ng-click="c.askNotify()">Bật</button>
			<button type="button" class="fhc-nb-x" ng-click="c.notifyHint = false" aria-label="Ẩn">×</button>
		</div>
		<!-- Ghim tin nhắn đính liền dưới tabbar (hỗ trợ nhiều tin ghim) -->
		<div class="fhc-pinbar-container" ng-if="c.tab==='chat' && c.view==='thread' && c.pinnedList.length > 0">
			<div class="fhc-pinbar" ng-click="c.pinnedList.length === 1 ? c.scrollToMsg(c.pinnedList[0].message_id) : (c.pinOpen = !c.pinOpen)">
				<svg class="fhc-pb-ic" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4h6l-1 7 3 3v2H7v-2l3-3-1-7Z"/><line x1="12" y1="16" x2="12" y2="22"/></svg>
				<div class="fhc-pb-body">
					<div class="fhc-pb-lbl">Tin đã ghim ({{ c.pinnedList.length }})</div>
					<div class="fhc-pb-prev"><b ng-if="c.pinnedList[0].name">{{ c.pinnedList[0].name }}:</b> {{ c.pinnedList[0].preview }}</div>
				</div>
				<!-- Nút toggle mở rộng nếu có nhiều hơn 1 tin ghim -->
				<button type="button" class="fhc-pb-toggle" ng-if="c.pinnedList.length > 1" ng-click="c.pinOpen = !c.pinOpen; $event.stopPropagation();" aria-label="Xem thêm ghim">
					<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" ng-style="{transform: c.pinOpen ? 'rotate(180deg)' : 'none'}"><path d="M6 9l6 6 6-6"/></svg>
				</button>
				<!-- Nút bỏ ghim nếu chỉ có 1 tin -->
				<button type="button" class="fhc-pb-x" ng-if="c.pinnedList.length === 1" ng-click="c.unpinOne(c.pinnedList[0].message_id, $event)" aria-label="Bỏ ghim">
					<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
				</button>
			</div>
			
			<!-- Danh sách chi tiết các tin ghim khi bấm mở rộng -->
			<div class="fhc-pin-list" ng-if="c.pinnedList.length > 1 && c.pinOpen">
				<div class="fhc-pin-item" ng-repeat="p in c.pinnedList track by p.message_id" ng-click="c.scrollToMsg(p.message_id)">
					<svg class="fhc-pb-ic" viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; opacity: 0.7;"><path d="M9 4h6l-1 7 3 3v2H7v-2l3-3-1-7Z"/><line x1="12" y1="16" x2="12" y2="22"/></svg>
					<div class="fhc-pin-item-body"><b ng-if="p.name">{{ p.name }}:</b> {{ p.preview }}</div>
					<button type="button" class="fhc-pin-item-x" ng-click="c.unpinOne(p.message_id, $event)" aria-label="Bỏ ghim">✕</button>
				</div>
			</div>
		</div>

		<!-- Body -->
		<div class="fhc-body" id="fhc-ng-body" ng-class="{'fhc-body-jn': c.tab==='checkin' && c.ck.state==='journey'}">
			<!-- TAB CHECK-IN (camera + GPS) -->
			<div ng-if="c.tab==='checkin'">
				<div class="fhc-empty" ng-if="c.ck.loading">Đang tải…</div>
				<div ng-if="!c.ck.loading && c.ck.my">
					<!-- FORM XÁC NHẬN: ảnh + vị trí + ghi chú → bấm "Hoàn tất" mới gửi (chỉ hiện khi state='review') -->
					<!-- Toggle Check-in / Check-out (chế độ bắt đầu / kết thúc ngày làm việc) -->
					<div class="fhc-io" ng-if="c.ck.state==='idle' || c.ck.state==='review'">
						<button type="button" class="fhc-io-btn io-in" ng-class="{on: c.ck.io==='in'}" ng-click="c.ckSetIo('in')">
							<span class="fhc-io-ic in"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg></span>
							<span class="fhc-io-tt"><b>Check-in</b><small>Bắt đầu ngày làm việc</small></span>
						</button>
						<button type="button" class="fhc-io-btn io-out" ng-class="{on: c.ck.io==='out'}" ng-click="c.ckSetIo('out')">
							<span class="fhc-io-ic out"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.5 2.5L16 9"/></svg></span>
							<span class="fhc-io-tt"><b>Check-out</b><small>Kết thúc ngày làm việc</small></span>
						</button>
					</div>
					<div class="fhc-io-hint" ng-if="c.ck.state==='idle' || c.ck.state==='review'">Bạn đang ở chế độ <b>{{ c.ck.io==='out' ? 'Check-out' : 'Check-in' }}</b>. Hãy chọn tag và ghi chú hoạt động của bạn.</div>
					<div class="fhc-review" ng-if="c.ck.state==='review'">
						<div class="fhc-rvh">
							<span class="fhc-rvt">Thông tin {{ c.ck.io==='out' ? 'check-out' : 'check-in' }}</span>
							<button type="button" class="fhc-rvx" ng-click="c.ckCancel()" aria-label="Đóng">×</button>
						</div>
						<div class="fhc-fld">
							<label class="fhc-flbl">Vị trí (GPS)</label>
							<div class="fhc-loc" ng-class="{ready: c.ck.address && !c.ck.geoErr, bad: c.ck.geoErr}">
								<svg class="fhc-locico" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
								<span class="fhc-loctxt">
									<span ng-if="c.ck.geoErr">{{ c.ck.geoErr }} <a class="fhc-rvlink" ng-click="c.ckRetryGeo()">Thử lại</a><a class="fhc-rvlink fhc-rvcoarse" ng-if="c.ck.geoCoarse > 0" ng-click="c.ckAcceptCoarse()">Vẫn check-in với vị trí này (±{{ c.ck.geoCoarse }}m)</a></span>
									<span ng-if="!c.ck.geoErr && c.ck.address">{{ c.ck.address }}</span>
									<span ng-if="!c.ck.geoErr && !c.ck.address && c.ck.addrLoading" class="fhc-locwait">Đang lấy địa chỉ…</span>
									<span ng-if="!c.ck.geoErr && !c.ck.address && !c.ck.addrLoading && c.ck.geoReady">Không lấy được địa chỉ <a class="fhc-rvlink" ng-click="c.ckRetryAddr()">Thử lại</a></span>
									<span ng-if="!c.ck.geoErr && !c.ck.geoReady" class="fhc-locwait">{{ c.ck.geoMsg || 'Đang định vị…' }}</span>
								</span>
								<svg class="fhc-locok" ng-if="c.ck.address && !c.ck.geoErr" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
							</div>
						</div>
						<div class="fhc-fld">
							<label class="fhc-flbl">Ảnh chụp</label>
							<div class="fhc-shot"><img ng-src="{{ c.ck.photo }}" alt=""></div>
						</div>
						<div class="fhc-fld">
							<label class="fhc-flbl" ng-if="c.CKTAG_LIST.length">Chọn tag <span class="fhc-flbl-sub">(có thể chọn nhiều)</span></label>
							<div class="fhc-cats" ng-if="c.CKTAG_LIST.length">
								<button type="button" class="fhc-cat" ng-repeat="tg in c.CKTAG_LIST track by tg.key" ng-class="{on: c.ckTagOn(tg.key)}" ng-click="c.ckToggleTag(tg.key)" ng-style="c.ckTagOn(tg.key) ? {color: tg.color, borderColor: tg.color, background: c.ckSoft(tg.key)} : {}">
									<i class="{{ tg.icon }} fhc-cat-ic" ng-style="{color: tg.color}"></i>
									<span class="fhc-cat-lb">{{ tg.label }}</span>
									<i class="bx bxs-check-circle fhc-cat-ck" ng-if="c.ckTagOn(tg.key)" ng-style="{color: tg.color}"></i>
								</button>
							</div>
						</div>
						<div class="fhc-fld">
							<label class="fhc-flbl">Ghi chú (không bắt buộc)</label>
							<input type="text" class="fhc-noteinp" ng-model="c.ck.note" maxlength="255" placeholder="Bạn đang làm gì? Gặp ai? …">
						</div>
						<div class="fhc-hint-out" ng-if="c.ck.geoReady && !c.ck.atOffice"><i class="bx bx-error-circle"></i> Do bạn đang checkin bên ngoài VP làm việc, vui lòng ghi rõ nội dung và hoạt động.</div>
							<div class="fhc-suberr" ng-if="c.ck.subErr">{{ c.ck.subErr }}</div>
						<button class="fhc-cta" type="button" ng-click="c.ckSubmit()" ng-disabled="!c.ck.address || c.ck.posting || c.ckNeedTagNote()">
							<svg ng-if="c.ck.posting" class="fhc-spin" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 12a9 9 0 1 1-6.2-8.5"/></svg>
							{{ c.ck.posting ? 'Đang gửi…' : ('Hoàn tất ' + (c.ck.io==='out' ? 'check-out' : 'check-in')) }}
						</button>
					</div>
					<!-- Header info + tiến độ -->					
					
						<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('BUSINESS_AREA') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
							<div class="fhc-info ng-scope border" ng-if="c.ck.state==='idle'" style="background:var(--fhc-warn-soft)">
								<a class="fhc-top" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('report-checkin');?>
">
									<div class="fhc-clock">
										<i class="bx bx-line-chart"></i>
									</div>
									<div>
										<h2>Báo cáo</h2>
										<p>Báo cáo check-in các thành viên thuộc đơn vị vùng/phòng ban</p>
									</div>
								</a>
							</div>
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('BO') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('BUSINESS_AREA') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
						<?php echo '<script'; ?>
>window.CHAT_CAN_STATS = 1;<?php echo '</script'; ?>
>
						
						<div class="fhc-stats" ng-if="c.ck.state==='idle' && c.st.on">
							<div class="fhc-stats-hd">
								<span class="fhc-stats-ic"><i class="bx bx-bar-chart-alt-2"></i></span>
								<span class="fhc-stats-t">Thống kê</span>
								<span class="fhc-stats-scope" ng-if="c.st.mode!=='sale'" ng-click="c.stToggle()">{{ c.st.label || 'Toàn công ty' }} <i class="bx bx-chevron-down"></i></span>
								<span class="fhc-stdate">
									<span class="fhc-stchip" ng-class="{on:c.stIsToday()}" ng-click="c.stToday()"><i class="bx bx-calendar-check"></i> {{ c.stDateLabel() }}</span>
									<label class="fhc-stcal"><i class="bx bx-calendar"></i><input type="date" ng-model="c.st.dateObj" ng-change="c.stDatePick()" max="{{ c.stMax() }}"></label>
								</span>
							</div>
							<div class="fhc-stmenu" ng-if="c.st.open">
								<div ng-if="c.st.mode==='director'">
									<button type="button" class="fhc-stopt" ng-class="{on:!c.st.dept}" ng-click="c.stPick(0)"><i class="bx bx-globe"></i> Toàn công ty <i class="bx bx-check fhc-stck" ng-if="!c.st.dept"></i></button>
									<div class="fhc-stgrp" ng-if="c.st.regions.length">{{ c.st.saleRootTitle || 'Theo vùng' }}</div>
									<button type="button" class="fhc-stopt" ng-repeat="r in c.st.regions track by r.id" ng-style="{'padding-left': (10 + r.depth*12) + 'px'}" ng-class="{on:c.st.dept==r.id}" ng-click="c.stPick(r.id)"><i class="bx bx-group"></i> {{ r.title }} <i class="bx bx-check fhc-stck" ng-if="c.st.dept==r.id"></i></button>
									<div class="fhc-stgrp" ng-if="c.st.blocks.length">Khối khác</div>
									<button type="button" class="fhc-stopt" ng-repeat="b in c.st.blocks track by b.id" ng-style="{'padding-left': (10 + b.depth*12) + 'px'}" ng-class="{on:c.st.dept==b.id}" ng-click="c.stPick(b.id)"><i class="bx bx-buildings"></i> {{ b.title }} <i class="bx bx-check fhc-stck" ng-if="c.st.dept==b.id"></i></button>
								</div>
								<div ng-if="c.st.mode==='region'">
									<button type="button" class="fhc-stopt" ng-class="{on:!c.st.dept}" ng-click="c.stPick(0)"><i class="bx bx-group"></i> {{ (c.st.myRegion&&c.st.myRegion.title) || 'Toàn vùng' }} <i class="bx bx-check fhc-stck" ng-if="!c.st.dept"></i></button>
									<div class="fhc-stgrp" ng-if="c.st.regionDepts.length">Theo phòng</div>
									<button type="button" class="fhc-stopt" ng-repeat="d in c.st.regionDepts track by d.id" ng-style="{'padding-left': (10 + d.depth*12) + 'px'}" ng-class="{on:c.st.dept==d.id}" ng-click="c.stPick(d.id)"><i class="bx bx-buildings"></i> {{ d.title }} <i class="bx bx-check fhc-stck" ng-if="c.st.dept==d.id"></i></button>
								</div>
							</div>
							<div class="fhc-stnums">
								<div class="fhc-stnum" role="button" tabindex="0" ng-click="c.stOpenList('has_checkin')"><span class="fhc-stn-ic" style="color:#16a34a;background:rgba(22,163,74,.12)"><i class="bx bx-check-square"></i></span><b style="color:#16a34a">{{ c.st.stats.checked_in }}</b><span>Đã check-in</span></div>
								<div class="fhc-stnum" role="button" tabindex="0" ng-click="c.stOpenList('not_checkin')"><span class="fhc-stn-ic" style="color:#f59e0b;background:rgba(245,158,11,.12)"><i class="bx bx-user"></i></span><b style="color:#f59e0b">{{ c.st.stats.not_checked_in }}</b><span>Chưa check-in</span></div>
								<div class="fhc-stnum" role="button" tabindex="0" ng-click="c.stOpenList('all')"><span class="fhc-stn-ic" style="color:#7c3aed;background:rgba(124,58,237,.12)"><i class="bx bxs-group"></i></span><b style="color:#7c3aed">{{ c.st.stats.total }}</b><span>Tổng nhân sự</span></div>
							</div>
							<div class="fhc-stfoot"><span>Cập nhật: {{ c.st.updated }}</span><button type="button" class="fhc-strefresh" ng-class="{spin:c.st.loading}" ng-click="c.loadStats()"><i class="bx bx-refresh"></i></button></div>
						</div>
						
						<?php }?>
					
					<div class="fhc-info" ng-if="c.ck.state==='idle'">
						<div class="fhc-top">
							<div class="fhc-clock">
								<svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
									<circle cx="12" cy="12" r="9"/>
									<path d="M12 7v5l3 2"/>
								</svg>
							</div>
							<div>
								<div class="d-flex justify-content-between align-items-start">
									<div>
										<h2>Check-in</h2>
										<span class="fhc-chip" ng-if="c.ck.my.count"><span class="fhc-dot"></span> Hôm nay {{ c.ck.my.count }} lần · gần nhất {{ c.ck.my.last_time }}</span>
									</div>
									<button class="fhc-jnbtn" ng-click="c.ckOpenJourney()"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="19" r="2"></circle><circle cx="18" cy="5" r="2"></circle><path d="M8 19h6a4 4 0 0 0 0-8H10a4 4 0 0 1 0-8h6"></path></svg><span>Hành trình</span></button>
								</div>
								<p>Gửi 1 ảnh kèm vị trí để ghi nhận. Có thể check-in nhiều lần trong ngày.</p>
							</div>
						</div>
					</div>
					<!-- ===== MÀN HÀNH TRÌNH (Timeline Check-in) — giao diện; data từ feed.mine hôm nay, backend act=journey giai đoạn sau ===== -->
					<div class="fhc-jn" ng-if="c.ck.state==='journey'">
						<div class="fhc-jn-top">
							<button type="button" class="fhc-jn-back" ng-click="c.ckJourneyBack()" aria-label="Quay lại"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg></button>
							<div class="fhc-jn-tt"><div class="fhc-jn-t">Timeline Check-in</div><div class="fhc-jn-s">Xem hành trình hoạt động trong ngày</div></div>
						</div>
						<div class="fhc-jn-profile" ng-if="c.ck.journey">
							<img class="fhc-jn-av" ng-src="{{ c.ck.journey.profile.avatar }}" alt="" onerror="this.style.visibility='hidden'">
							<div class="fhc-jn-pi">
								<div class="fhc-jn-nm">{{ c.ck.journey.profile.name || 'Tôi' }}<span class="fhc-jn-badge" ng-if="c.ck.journey.profile.badge">{{ c.ck.journey.profile.badge }}</span></div>
								<div class="fhc-jn-role" ng-if="c.ck.journey.profile.role">{{ c.ck.journey.profile.role }}</div>
								<div class="fhc-jn-dept" ng-if="c.ck.journey.profile.dept">Phòng ban: {{ c.ck.journey.profile.dept }}</div>
							</div>
						</div>
						<div class="fhc-jn-datebar" ng-if="c.ck.journey">
							<button type="button" class="fhc-jn-nav" ng-click="c.ckJnDay(-1)" aria-label="Ngày trước"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg></button>
							<div class="fhc-jn-datepick"><div class="fhc-jn-date">{{ c.ck.journey.dateLabel }}</div><input type="date" class="fhc-jn-dateinp" ng-model="c.ck.jnDate" ng-change="c.ckJnPick()" max="{{ c.ck.jnMax }}" onclick="try{this.showPicker()}catch(e){}" aria-label="Chọn ngày"></div>
							<button type="button" class="fhc-jn-nav" ng-click="c.ckJnDay(1)" ng-disabled="c.ckJnIsToday()" aria-label="Ngày sau"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg></button>
							<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('BUSINESS_AREA') || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('SALE_DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
							<a class="fhc-jn-report d-none" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('report-checkin');?>
"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><rect x="7" y="11" width="3" height="6"/><rect x="12" y="7" width="3" height="10"/><rect x="17" y="13" width="3" height="4"/></svg> Báo cáo ngày</a>
							<?php }?>
						</div>
						<div class="fhc-jn-ov" ng-if="c.ck.journey">
							<div class="fhc-jn-ovh">Tổng quan hoạt động trong ngày</div>
							<div class="fhc-jn-stats">
								<div class="fhc-jn-stat"><span class="fhc-jn-sic" style="color:#16a34a;background:rgba(22,163,74,.12)"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg></span><b>{{ c.ck.journey.stats.count }}</b><span class="fhc-jn-statl">Lần check-in</span></div>
								<div class="fhc-jn-stat"><span class="fhc-jn-sic" style="color:#d97706;background:rgba(217,119,6,.12)"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="19" r="2"/><circle cx="18" cy="5" r="2"/><path d="M8 19h6a4 4 0 0 0 0-8H10a4 4 0 0 1 0-8h6"/></svg></span><b>{{ c.ck.journey.stats.places }}</b><span class="fhc-jn-statl">Địa điểm</span></div>
								<div class="fhc-jn-stat"><span class="fhc-jn-sic" style="color:#7c3aed;background:rgba(124,58,237,.12)"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2Z"/><circle cx="12" cy="13" r="4"/></svg></span><b>{{ c.ck.journey.stats.photos }}</b><span class="fhc-jn-statl">Ảnh đã gửi</span></div>
							</div>
						</div>
						<div class="fhc-jn-tlh" ng-if="c.ck.journey">Timeline check-in trong ngày</div>
						<div class="fhc-jn-empty" ng-if="c.ck.journey.loading">Đang tải…</div>
							<div class="fhc-jn-empty" ng-if="c.ck.journey && !c.ck.journey.loading && !c.ck.journey.items.length">Chưa có check-in trong ngày này.</div>
						<div class="fhc-jn-tl" ng-if="c.ck.journey && c.ck.journey.items.length">
							<div class="fhc-jn-item" ng-repeat="it in c.ck.journey.items track by $index">
								<div class="fhc-jn-side"><span class="fhc-jn-time">{{ it.time }}</span><span class="fhc-jn-dot" ng-style="{background: c.ckCat(it.tags[0]).color}"></span></div>
								<div class="fhc-jn-card">
									<div class="fhc-jn-cardtop">
										<div class="d-flex flex-column gap-2">
											<div class="d-flex align-items-start gap-2">
												<span class="fhc-jn-pin" ng-style="{color: c.ckCat(it.tags[0]).color}"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg></span>
												<div class="fhc-jn-cardmain">
													<div class="fhc-jn-place">{{ it.place || 'Vị trí check-in' }}</div>
													<span class="fhc-jn-tag" ng-repeat="tg in it.tags" ng-style="{color: c.ckCat(tg).color, background: c.ckSoft(tg)}">{{ c.ckCat(tg).label }}</span>
												</div>
											</div>
											<div class="fhc-jn-note" ng-if="it.note">{{ it.note }}</div>
											<div class="fhc-jn-addr" ng-if="it.address && it.address !== it.place"><svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg> {{ it.address }}</div>
										</div>
										<img class="fhc-jn-photo" ng-if="it.photo" ng-src="{{ it.photo }}" alt="" ng-click="c.lbOpenSrc(it.photo)">
									</div>
								</div>
							</div>
						</div>
						<div class="fhc-jn-foot" ng-if="c.ck.journey"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg> Dữ liệu check-in được cập nhật tự động theo thời gian thực</div>
					</div>
					<!-- Thẻ trạng thái: chỉ còn 'idle' (chụp ảnh) — 'review' xử lý ở form xác nhận phía trên -->
					<div class="fhc-my idle" ng-if="c.ck.state==='idle'">
						<div ng-if="c.ck.state==='idle'">
							<div class="fhc-h">
								<span class="fhc-ico"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2Z"/><circle cx="12" cy="13" r="4"/></svg></span> Sẵn sàng check-in
							</div>
							<div class="fhc-stxt">Chụp 1 ảnh kèm vị trí GPS để ghi nhận.</div>
							<button class="fhc-cta" type="button" ng-click="c.ckOnCta()">
								<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
								<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2Z"/><circle cx="12" cy="13" r="4"/></svg>
								Chụp ảnh {{ c.ck.io==='out' ? 'check-out' : 'check-in' }}
							</button>
							<div class="fhc-ck-pcnote" ng-if="c.isDesktop">
								<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2.5"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
								<span>Vui lòng sử dụng <b>điện thoại (Mobile)</b> để chụp hình check-in.</span>
							</div>
						</div>
					</div>
					<!-- Feed: tách 2 khu vực — của tôi / người khác -->
					<div ng-if="c.ck.state==='idle' && (c.ckFeedMine().length || c.ckFeedList().length)">
						<!-- Của tôi -->
						<div class="fhc-feedh"><span>Của tôi</span><span class="fhc-cnt">{{ c.ckFeedMine().length }}</span></div>
						<div class="fhc-row" ng-repeat="it in c.ckFeedMine() track by $index">
						<img class="fhc-av" ng-src="{{ it.avatar }}" alt="" onerror="this.style.visibility='hidden'">
						<div class="fhc-who"><div class="fhc-nm"><span class="fhc-nmtxt">{{ it.name }}</span><span class="fhc-dept" ng-if="it.dept">{{ it.dept }}</span></div><div class="fhc-tm"><a ng-if="it.lat || it.lng" ng-href="{{ c.ckMapUrl(it) }}" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg> <span ng-if="it.address">{{ it.address }}</span><span ng-if="!it.address">{{ it.lat | number:5 }},{{ it.lng | number:5 }}</span></a> · {{ it.time }}</div><div class="fhc-rnote" ng-if="it.note">{{ it.note }}</div></div>
						<img class="fhc-cap" ng-if="it.photo" ng-src="{{ it.photo }}" alt="" ng-click="c.lbOpenSrc(it.photo)">
					</div>
						<div class="fhc-feedhint" ng-if="!c.ckFeedMine().length">Bạn chưa check-in hôm nay.</div>
						<!-- Người khác -->
						<div class="fhc-feedh fhc-feedh2"><span>Người khác</span><span class="fhc-cnt">{{ c.ckFeedOthers().length }}</span></div>
						<div class="fhc-row" ng-repeat="it in c.ckFeedOthers() track by $index">
							<img class="fhc-av" ng-src="{{ it.avatar }}" alt="" onerror="this.style.visibility='hidden'">
							<div class="fhc-who">
								<div class="fhc-nm">
									<button type="button" class="fhc-jnbtn btn-icon btn-xs p-0" ng-click="c.ckOpenJourney(it)" title="Xem hành trình check-in"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="19" r="2"/><circle cx="18" cy="5" r="2"/><path d="M8 19h6a4 4 0 0 0 0-8H10a4 4 0 0 1 0-8h6"/></svg></button>
									<span class="fhc-nmtxt">{{ it.name }}</span>
									<span class="fhc-dept" ng-if="it.dept">{{ it.dept }}</span>
								</div>
								<div class="fhc-tm"><a ng-if="it.lat || it.lng" ng-href="{{ c.ckMapUrl(it) }}" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg> <span ng-if="it.address">{{ it.address }}</span><span ng-if="!it.address">{{ it.lat | number:5 }},{{ it.lng | number:5 }}</span></a> · {{ it.time }}</div><div class="fhc-rnote" ng-if="it.note">{{ it.note }}</div></div>
							<img class="fhc-cap" ng-if="it.photo" ng-src="{{ it.photo }}" alt="" ng-click="c.lbOpenSrc(it.photo)">
						</div>
						<div class="fhc-emptyc" ng-if="!c.ckFeedOthers().length">
								<div class="fhc-emptyc-ic"><svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
								<div class="fhc-emptyc-t">Chưa có ai khác check-in</div>
								<div class="fhc-emptyc-s">Đồng nghiệp check-in hôm nay sẽ hiện ở đây.</div>
							</div>
					</div>
					<div class="fhc-loadmore" ng-if="c.ck.state==='idle' && c.ck.feedMore"><button type="button" ng-click="c.ckLoadMore()" ng-disabled="c.ck.loadingMore">{{ c.ck.loadingMore ? 'Đang tải…' : 'Tải thêm' }}</button></div>
						<div class="fhc-feedempty" ng-if="!(c.ckFeedMine().length || c.ckFeedList().length) && c.ck.state==='idle'">
						<div class="fhc-fe-ic"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 18a5 5 0 0 0-10 0M12 2v7M4.2 10.2l1.4 1.4M1 18h2M21 18h2M18.4 11.6l1.4-1.4M23 22H1M8 6l4-3 4 3"/></svg></div>
						<div class="fhc-fe-t">Chưa có ai check-in</div>
						<div class="fhc-fe-s">Hãy là người đầu tiên check-in hôm nay.</div>
					</div>
				</div>
				<input type="file" id="fhc-ck-file" accept="image/*" capture="environment" class="d-none" fhc-file="c.ckOnPhoto($input)">
			</div>
			<!-- TAB CHAT — LỚP LIST -->
			<div ng-if="c.tab==='chat' && c.view==='list'">
				<div class="fhc-conv-top"><span class="fhc-conv-h">Trò chuyện</span>
					<button type="button" class="fhc-conv-newbtn" ng-click="c.createGroup()">＋ Tạo nhóm</button></div>
				<div class="fhc-conv-search">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
					<input type="text" ng-model="c.convQ" placeholder="Tìm hội thoại…">
					<button type="button" class="fhc-conv-clr" ng-if="c.convQ" ng-click="c.convQ=''" aria-label="Xoá tìm">×</button>
				</div>
				<div class="fhc-conv-sec" ng-if="c.sysChans().length">Hệ thống</div>
				<div class="fhc-conv" ng-repeat="ch in c.sysChans()" ng-click="c.openConv(ch.channel_id)">
					<div class="fhc-conv-av"><span class="fhc-conv-gl" ng-style="{background: c.tint(ch.channel_id)[0], color: c.tint(ch.channel_id)[1]}">{{ c.initial(ch.name) }}</span><img class="fhc-conv-img" ng-if="ch.image" ng-src="{{ch.image}}" onerror="this.remove()"></div>
					<div class="fhc-conv-main"><div class="fhc-conv-nm">{{ ch.name }}</div><div class="fhc-conv-last"><b class="fhc-conv-who" ng-if="ch.last_sender">{{ ch.last_sender }}:</b> {{ ch.last_msg || 'Chưa có tin nhắn' }}</div></div>
					<div class="fhc-conv-meta"><svg class="fhc-conv-muteic" ng-if="ch.muted" viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M13.73 21a2 2 0 0 1-3.46 0"/><path d="M18.63 13A17.89 17.89 0 0 1 18 8"/><path d="M6.26 6.26A5.86 5.86 0 0 0 6 8c0 7-3 9-3 9h14"/><path d="M18 8a6 6 0 0 0-9.33-5"/><line x1="1" y1="1" x2="23" y2="23"/></svg><div class="fhc-conv-tm">{{ ch.last_at }}</div><span class="fhc-conv-ub" ng-if="ch.unread > 0">{{ ch.unread > 99 ? '99+' : ch.unread }}</span></div>
					<div class="fhc-conv-act" ng-click="$event.stopPropagation()">
						<button type="button" class="fhc-conv-more" ng-click="c.openConvMenu(ch, $event)" aria-label="Tuỳ chọn"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" stroke="none"><circle cx="5" cy="12" r="1.6"/><circle cx="12" cy="12" r="1.6"/><circle cx="19" cy="12" r="1.6"/></svg></button>
						<div class="fhc-conv-menu" ng-if="ch._cmenu">
							<button type="button" class="fhc-menu-item" ng-if="ch.type==4" ng-click="c.togglePin(ch, $event); c.closeConvMenu()"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4h6l-1 7 3 3v2H7v-2l3-3-1-7Z"/><line x1="12" y1="16" x2="12" y2="22"/></svg> {{ ch.pinned ? 'Bỏ ghim' : 'Ghim hội thoại' }}</button>
							<button type="button" class="fhc-menu-item" ng-click="c.toggleMute(ch, $event); c.closeConvMenu()"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg> {{ ch.muted ? 'Bật thông báo' : 'Tắt thông báo' }}</button>
						</div>
					</div>
				</div>
				<div class="fhc-conv-sec" ng-if="c.grpChans().length">Nhóm chat</div>
				<div class="fhc-conv" ng-repeat="ch in c.grpChans()" ng-click="c.openConv(ch.channel_id)">
					<div class="fhc-conv-av"><span class="fhc-conv-gl" ng-style="{background: c.tint(ch.channel_id)[0], color: c.tint(ch.channel_id)[1]}">{{ c.initial(ch.name) }}</span><img class="fhc-conv-img" ng-if="ch.image" ng-src="{{ch.image}}" onerror="this.remove()"></div>
					<div class="fhc-conv-main"><div class="fhc-conv-nm">{{ ch.name }}</div><div class="fhc-conv-last"><b class="fhc-conv-who" ng-if="ch.last_sender">{{ ch.last_sender }}:</b> {{ ch.last_msg || 'Chưa có tin nhắn' }}</div></div>
					<div class="fhc-conv-meta"><svg class="fhc-conv-muteic" ng-if="ch.muted" viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M13.73 21a2 2 0 0 1-3.46 0"/><path d="M18.63 13A17.89 17.89 0 0 1 18 8"/><path d="M6.26 6.26A5.86 5.86 0 0 0 6 8c0 7-3 9-3 9h14"/><path d="M18 8a6 6 0 0 0-9.33-5"/><line x1="1" y1="1" x2="23" y2="23"/></svg><div class="fhc-conv-tm">{{ ch.last_at }}</div><span class="fhc-conv-ub" ng-if="ch.unread > 0">{{ ch.unread > 99 ? '99+' : ch.unread }}</span></div>
					<div class="fhc-conv-act" ng-click="$event.stopPropagation()">
						<button type="button" class="fhc-conv-more" ng-click="c.openConvMenu(ch, $event)" aria-label="Tuỳ chọn"><svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" stroke="none"><circle cx="5" cy="12" r="1.6"/><circle cx="12" cy="12" r="1.6"/><circle cx="19" cy="12" r="1.6"/></svg></button>
						<div class="fhc-conv-menu" ng-if="ch._cmenu">
							<button type="button" class="fhc-menu-item" ng-if="ch.type==4" ng-click="c.togglePin(ch, $event); c.closeConvMenu()"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4h6l-1 7 3 3v2H7v-2l3-3-1-7Z"/><line x1="12" y1="16" x2="12" y2="22"/></svg> {{ ch.pinned ? 'Bỏ ghim' : 'Ghim hội thoại' }}</button>
							<button type="button" class="fhc-menu-item" ng-click="c.toggleMute(ch, $event); c.closeConvMenu()"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg> {{ ch.muted ? 'Bật thông báo' : 'Tắt thông báo' }}</button>
						</div>
					</div>
				</div>
				<div class="fhc-conv-empty" ng-if="c.convQ && !c.sysChans().length && !c.grpChans().length">Không tìm thấy hội thoại nào.</div>
				<div class="fhc-conv-empty" ng-if="!c.convQ && !c.grpChans().length">Chưa có nhóm. Bấm “＋ Tạo nhóm”.</div>
			</div>
			<!-- TAB CHAT — LỚP THREAD -->
			<div ng-if="c.tab==='chat' && c.view==='thread'" class="fhc-thread">
				<div class="fhc-empty" ng-if="c.loading">Đang tải…</div>
				<div class="fhc-emptyc" ng-if="!c.loading && !c.msgs.length">
					<div class="fhc-emptyc-ic"><svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a3 3 0 0 1-3 3H8l-5 4V6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3Z"/></svg></div>
					<div class="fhc-emptyc-t">Chưa có tin nhắn</div>
					<div class="fhc-emptyc-s">Hãy gửi tin nhắn đầu tiên để bắt đầu cuộc trò chuyện trong nhóm này.</div>
				</div>
				<div class="fhc-loadmore" ng-if="c.hasMoreHistory && !c.loading"><button type="button" ng-click="c.loadMore()" ng-disabled="c.loadingMore">{{ c.loadingMore ? 'Đang tải…' : 'Tải tin cũ hơn' }}</button></div>
				<div class="fhc-msg" id="fhc-m-{{ m.message_id }}" ng-class="{me: m.is_me}" ng-repeat="m in c.msgs track by m.message_id" data-mine="{{ m.reactions.mine }}">
					<div class="fhc-mav" ng-if="!m.is_me" ng-style="{background: c.tint(m.profile_id)[0], color: c.tint(m.profile_id)[1]}">{{ c.initial(m.name) }}<img ng-if="m.avatar" ng-src="{{ m.avatar }}" alt="" onerror="this.remove()"></div>
					<div class="fhc-mwrap">
						<div class="fhc-bub" ng-class="{'fhc-bub-img': m.type==2 && c.album(m).length, 'fhc-bub-recalled': m.recalled, 'fhc-bub-men': m._memMe && !m.is_me}">
							<div class="fhc-mname" ng-if="!m.is_me">{{ m.name }}</div>
							<div class="fhc-recalled" ng-if="m.recalled"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v6h6"/><path d="M3.5 13a9 9 0 1 0 2.6-7.5L3 8"/></svg> Tin nhắn đã được thu hồi</div>
							<div class="fhc-quote" ng-if="m.reply_to && !m.recalled" ng-click="c.scrollToMsg(m.reply_to.message_id)"><b>{{ m.reply_to.name || 'Tin gốc' }}</b><span class="fhc-q-prev">{{ m.reply_to.preview }}</span></div>
							<div class="fhc-album album-{{ c.albumLay(m) }}" ng-if="m.type==2 && c.album(m).length && !m.recalled">
								<div class="fhc-acell" ng-repeat="src in c.album(m) track by $index" ng-click="c.lbOpen(m, $index)"><img ng-src="{{ src }}" alt="" loading="lazy"><span class="fhc-amore" ng-if="c.albumLay(m)=='more' && $index==3">+{{ c.album(m).length - 4 }}</span></div>
							</div>
							<div class="fhc-bcap" ng-if="m.type==2 && m.content && !m.recalled">{{ m.content }}</div>
							<div class="fhc-btx" ng-if="m.type!=2 && !m.recalled"><span ng-repeat="pt in m._parts track by $index" ng-class="{'fhc-men': pt.men}">{{ pt.men ? '@'+pt.name : pt.v }}</span></div>
							<div class="fhc-mtime">{{ m.time }}</div>
							<button type="button" class="fhc-react-btn" ng-if="!m.recalled" ng-click="c.openPicker(m, $event)" aria-label="Thả cảm xúc"><svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></button>
							<div class="fhc-picker-quick" ng-class="{'fhc-pk-open': m._picker}" ng-if="!m.recalled"><button type="button" class="fhc-pk-emo" ng-class="{on: m.reactions.mine===code}" ng-repeat="code in ::c.REACTS" ng-click="c.react(m, code, $event)">{{ ::c.EMOJI[code] }}</button></div>
						</div>
						<div class="fhc-reacts" ng-class="{mine: m.reactions.mine}" ng-if="m.reactions.total && !m.recalled" ng-click="c.openReactors(m, $event)">
							<span class="fhc-rc" ng-repeat="e in c.reactCells(m)">{{ e }}</span><span class="fhc-rn" ng-if="m.reactions.total > 1">{{ m.reactions.total }}</span>
						</div>
					</div>
					<button type="button" class="fhc-reply" ng-if="!m.recalled" ng-click="c.startReply(m, $event)" aria-label="Trả lời">
						<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 17l-5-5 5-5"/><path d="M4 12h11a5 5 0 0 1 5 5v1"/></svg>
					</button>
					<div class="fhc-act" ng-if="!m.recalled">
						<button type="button" class="fhc-more" ng-class="{active: m._menu}" ng-click="c.openMenu(m, $event)" aria-label="Thêm">
							<svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor" stroke="none"><circle cx="5" cy="12" r="1.6"/><circle cx="12" cy="12" r="1.6"/><circle cx="19" cy="12" r="1.6"/></svg>
						</button>
						<div class="fhc-menu" ng-if="m._menu">
							<button type="button" class="fhc-menu-item" ng-click="c.pinMessage(m, $event); c.closeMenu()"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4h6l-1 7 3 3v2H7v-2l3-3-1-7Z"/><line x1="12" y1="16" x2="12" y2="22"/></svg> {{ c.isPinned(m.message_id) ? 'Bỏ ghim' : 'Ghim' }}</button>
							<button type="button" class="fhc-menu-item fhc-menu-danger" ng-if="c.canRecall(m)" ng-click="c.recall(m, $event); c.closeMenu()"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v6h6"/><path d="M3.5 13a9 9 0 1 0 2.6-7.5L3 8"/></svg> Thu hồi</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="fhc-statusbar" ng-if="(c.typingName || c.seenList.length) && c.tab==='chat' && c.view==='thread'">
			<div class="fhc-typing" ng-if="c.typingName">{{ c.typingName }} đang soạn…</div>
			<div class="fhc-seen" ng-if="c.seenList.length">
					<span class="fhc-seen-lbl">Đã xem</span>
					<img class="fhc-seen-av" ng-repeat="sv in c.seenList | limitTo:6 track by sv.profile_id" ng-src="{{ sv.avatar }}" alt="" title="{{ sv.name }}" onerror="this.style.visibility='hidden'">
					<span class="fhc-seen-more" ng-if="c.seenList.length > 6">+{{ c.seenList.length - 6 }}</span>
				</div>
		</div>
			<!-- Composer (chỉ ở thread) -->
		<div class="fhc-composer d-flex flex-column gap-1" ng-if="c.tab==='chat' && c.view==='thread'">
			<!-- Thanh trả lời (quote) -->
			<div class="fhc-reply-bar" ng-if="c.replyTo">
				<svg class="fhc-rb-ic" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M9 17l-5-5 5-5"/><path d="M4 12h11a5 5 0 0 1 5 5v1"/></svg>
				<div class="fhc-rb-body"><div class="fhc-rb-name">{{ c.replyTo.name }}</div><div class="fhc-rb-prev">{{ c.replyTo.preview }}</div></div>
				<button type="button" class="fhc-rb-x" ng-click="c.cancelReply()" aria-label="Huỷ trả lời">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
				</button>
			</div>
			<div class="fhc-at" ng-if="c.atOpen && c.atFiltered().length">
				<button type="button" class="fhc-at-item" ng-class="{'fhc-at-on': $index === c.atActive}" ng-repeat="mem in c.atFiltered() track by mem.profile_id" ng-click="c.pickMention(mem)" ng-mouseenter="c.atActive = $index">
					<img class="fhc-at-av" ng-if="mem.avatar" ng-src="{{ mem.avatar }}" alt="" onerror="this.style.visibility='hidden'">
					<span class="fhc-at-nm">{{ mem.name }}</span>
				</button>
			</div>
			<div class="fhc-cbar">
				<label class="fhc-attach" aria-label="Đính kèm ảnh">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
					<input type="file" accept="image/*" multiple class="d-none" fhc-file="c.onPickFiles($input)">
				</label>
				<textarea id="fhc-input" rows="1" placeholder="Nhập tin nhắn…" maxlength="2000" ng-model="c.draft" ng-keydown="c.onKey($event)" ng-keyup="c.onCompose($event)" fhc-autogrow></textarea>
				<button type="button" class="fhc-send" ng-click="c.send()" ng-disabled="c.imgPosting" aria-label="Gửi">
					<svg viewBox="0 0 24 24" width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7Z"/></svg>
				</button>
			</div>
			<div class="fhc-hint text-center">Enter gửi · <b>Shift</b>+Enter xuống dòng</div>
		</div>
	</div>
	<!-- Camera check-in trong app (getUserMedia) + filter trực tiếp — logic ở chat2.camera.js -->
	<div class="fhc-cam" ng-cloak ng-if="c.cam.open">
		<video id="fhc-cam-video" class="fhc-cam-video" ng-class="{mirror: c.cam.facing==='user'}" ng-style="{filter: c.cam.filter}" autoplay playsinline muted></video>
		<div class="fhc-cam-top">
			<button type="button" class="fhc-cam-ic" ng-click="c.camClose()" aria-label="Đóng"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
			<span class="fhc-cam-hint" ng-if="c.cam.starting">Đang mở camera…</span>
			<button type="button" class="fhc-cam-ic" ng-click="c.camFlip()" aria-label="Đổi camera trước/sau"><svg viewBox="0 0 24 24" width="21" height="21" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg></button>
		</div>
		<div class="fhc-cam-bottom">
			<div class="fhc-cam-filters">
				<button type="button" class="fhc-cam-fbtn" ng-class="{on: c.cam.filter===f.k}" ng-repeat="f in c.cam.filters" ng-click="c.camPick(f.k)">{{ f.label }}</button>
			</div>
			<button type="button" class="fhc-cam-shoot" ng-click="c.camShoot()" aria-label="Chụp ảnh"></button>
		</div>
	</div>
	<!-- Lightbox xem ảnh (toàn màn hình) -->
	<div class="fhc-lb" ng-class="{'fhc-open': c.lb.open}">
		<div class="fhc-lb-bar">
			<span class="fhc-lb-count">{{ c.lb.idx + 1 }} / {{ c.lb.srcs.length }}</span>
			<button type="button" class="fhc-lb-x" ng-click="c.lbClose()" aria-label="Đóng">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
			</button>
		</div>
		<button type="button" class="fhc-lb-nav fhc-lb-prev" ng-if="c.lb.srcs.length > 1" ng-click="c.lbNav(-1, $event)" aria-label="Trước">
			<svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
		</button>
		<div class="fhc-lb-stage" ng-click="c.lbClose()">
			<img class="fhc-lb-img" ng-src="{{ c.lb.srcs[c.lb.idx] }}" alt="" ng-style="{transform: c.lb.zoom ? 'scale(2.4)' : 'scale(1)', cursor: c.lb.zoom ? 'zoom-out' : 'zoom-in'}" ng-click="c.lbZoom($event)">
		</div>
		<button type="button" class="fhc-lb-nav fhc-lb-next" ng-if="c.lb.srcs.length > 1" ng-click="c.lbNav(1, $event)" aria-label="Sau">
			<svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
		</button>
	</div>
	<!-- Modal tạo nhóm / thêm thành viên -->
	<div class="fhc-srch" ng-cloak ng-if="c.srch.open && c.tab==='chat' && c.view==='thread'">
		<div class="fhc-srch-bar">
			<svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
			<input id="fhc-srch-input" type="text" ng-model="c.srch.q" ng-keyup="c.searchRun()" placeholder="Tìm trong cuộc trò chuyện…">
			<button type="button" class="fhc-srch-x" ng-click="c.searchToggle()" aria-label="Đóng">×</button>
		</div>
		<div class="fhc-srch-results">
			<div class="fhc-srch-hint" ng-if="c.srch.loading">Đang tìm…</div>
			<div class="fhc-srch-hint" ng-if="!c.srch.loading && c.srch.results && !c.srch.results.length">Không tìm thấy tin nhắn.</div>
			<div class="fhc-srch-hint" ng-if="!c.srch.loading && !c.srch.results">Gõ từ khoá để tìm tin nhắn.</div>
			<button type="button" class="fhc-srch-item" ng-repeat="r in c.srch.results track by r.message_id" ng-click="c.searchGo(r.message_id)">
				<div class="fhc-srch-rhead"><b>{{ r.name }}</b><span>{{ r.time }}</span></div>
				<div class="fhc-srch-snip">{{ r.content }}</div>
			</button>
		</div>
	</div>
	<div class="fhc-cg" ng-cloak ng-class="{'fhc-cg-addmode': c.cg.mode==='add'}" ng-show="c.cg.open">
		<div class="fhc-cg-box">
			<div class="fhc-cg-head">
				<div class="fhc-cg-title">{{ c.cg.mode==='add' ? 'Thêm thành viên' : 'Tạo nhóm chat' }}</div>
				<button type="button" class="fhc-cg-x" ng-click="c.closeCreate()" aria-label="Đóng">
					<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
				</button>
			</div>
			<div class="fhc-cg-top">
				<label class="fhc-cg-ava" ng-class="{has: c.cg.img}" ng-style="c.cg.img ? {'background-image': 'url(' + c.cg.img + ')'} : {}" title="Chọn ảnh nhóm">
					<input type="file" accept="image/*" class="d-none" fhc-file="c.cgPickImage($input)">
					<span class="fhc-cg-ava-ph" ng-hide="c.cg.img">＋</span>
				</label>
				<input type="text" class="fhc-cg-name" placeholder="Tên nhóm…" maxlength="120" ng-model="c.cg.name">
			</div>
			<div class="fhc-cg-filter">
				<select class="fhc-cg-dept" ng-model="c.cg.dept" ng-options="d.id as d.name for d in c.cgDepts()"><option value="">Tất cả phòng</option></select>
				<input type="text" class="fhc-cg-q" placeholder="Tìm tên…" ng-model="c.cg.q">
			</div>
			<div class="fhc-cg-list">
				<div class="fhc-cg-empty" ng-if="!c.cgFiltered().length">Không có nhân viên phù hợp.</div>
				<div class="fhc-cg-row" ng-class="{on: c.cgIsPicked(s)}" ng-repeat="s in c.cgFiltered() track by s.id" ng-click="c.cgToggle(s)">
					<img class="fhc-cg-av" ng-src="{{ s.avatar }}" alt="" onerror="this.style.visibility='hidden'">
					<div class="fhc-cg-info"><div class="fhc-cg-nm">{{ s.name }}</div><div class="fhc-cg-dp">{{ s.dept_name }}</div></div>
					<span class="fhc-cg-ck"></span>
				</div>
			</div>
			<div class="fhc-cg-foot">
				<span class="fhc-cg-cnt">Đã chọn {{ c.cgCount() }}</span>
				<button type="button" class="fhc-cg-btn" ng-click="c.cgSubmit()" ng-disabled="c.cg.submitting">{{ c.cg.mode==='add' ? 'Thêm' : 'Tạo nhóm' }}</button>
			</div>
		</div>
	</div>
	<!-- Popup: ai đã thả cảm xúc (tái dùng pattern modal .fhc-cg) -->
	<div class="fhc-rx" ng-cloak ng-show="c.rx.open" ng-click="c.closeReactors()">
		<div class="fhc-rx-box" ng-click="$event.stopPropagation()">
			<div class="fhc-cg-head">
				<div class="fhc-cg-title">Cảm xúc<span class="fhc-rx-total" ng-if="c.rx.total"> · {{ c.rx.total }}</span></div>
				<button type="button" class="fhc-cg-x" ng-click="c.closeReactors()" aria-label="Đóng">
					<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
				</button>
			</div>
			<div class="fhc-rx-sum" ng-if="c.rx.summary.length">
				<span class="fhc-rx-sumi" ng-repeat="s in c.rx.summary track by $index">{{ s.emoji }}<b>{{ s.n }}</b></span>
			</div>
			<div class="fhc-rx-list">
				<div class="fhc-cg-empty" ng-if="c.rx.loading">Đang tải…</div>
				<div class="fhc-cg-empty" ng-if="!c.rx.loading && !c.rx.list.length">Chưa có ai thả.</div>
				<div class="fhc-rl-item" ng-repeat="rr in c.rx.list track by $index">
					<img class="fhc-rl-av" ng-if="rr.avatar" ng-src="{{ rr.avatar }}" alt="" onerror="this.style.visibility='hidden'">
					<span class="fhc-rl-nm">{{ rr.name }}</span>
					<span class="fhc-rl-emo">{{ c.EMOJI[rr.emoji] }}</span>
				</div>
			</div>
		</div>
	</div>
	<!-- Panel quản trị nhóm -->
	<div class="fhc-mng" ng-show="c.mng.open">
		<div class="fhc-mng-load" ng-if="c.mng.loading">Đang tải…</div>
		<div class="fhc-mng-box" ng-if="!c.mng.loading && c.mng.info">
			<div class="fhc-mng-head">
				<button type="button" class="fhc-mng-x" ng-click="c.closeManage()" aria-label="Đóng">
					<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
				</button>
				<div class="fhc-mng-title">{{ c.mng.info.name || 'Nhóm' }}</div>
				<button type="button" class="fhc-mng-hbtn" ng-click="c.mngAddOpen()" title="Thêm thành viên"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg></button>
				<button type="button" class="fhc-mng-hbtn" ng-if="c.mngIsOwner()" ng-click="c.mngRename()" title="Đổi tên nhóm"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></button>
				<label class="fhc-mng-hbtn" ng-if="c.mngIsOwner()" title="Đổi ảnh nhóm"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg><input type="file" accept="image/*" class="d-none" fhc-file="c.mngPickImage($input)"></label>
			</div>
			<div class="fhc-mng-body">
				<div class="fhc-mng-sec">Thành viên ({{ c.mngMembers().length }})</div>
				<div class="fhc-mng-list">
					<div class="fhc-mng-row" ng-repeat="m in c.mngMembers() track by m.profile_id">
						<img class="fhc-cg-av" ng-src="{{ m.avatar }}" alt="" onerror="this.style.visibility='hidden'">
						<div class="fhc-cg-info"><div class="fhc-cg-nm">{{ m.name }} <span class="fhc-mng-badge" ng-if="c.mngIsRoleOwner(m)">Chủ</span></div></div>
						<button type="button" class="fhc-mng-mini" ng-if="c.mngIsOwner() && !c.mngIsRoleOwner(m)" ng-click="c.mngTransfer(m)" title="Chuyển quyền chủ">★</button>
						<button type="button" class="fhc-mng-mini danger" ng-if="c.mngIsOwner() && !c.mngIsRoleOwner(m)" ng-click="c.mngRemove(m)" title="Xoá khỏi nhóm">✕</button>
					</div>
				</div>
				<div class="fhc-mng-acts">
					<button type="button" class="fhc-mng-btn danger" ng-if="c.mngIsOwner()" ng-click="c.mngDisband()">Giải tán nhóm</button>
					<button type="button" class="fhc-mng-btn danger" ng-if="!c.mngIsOwner()" ng-click="c.mngLeave()">Rời nhóm</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php }
}
