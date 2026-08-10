{* Modal: Hành trình check-in 1 người — "Timeline Check-in" (giống chat2 journey) *}
{* Style: .pj-* nằm trong application/themes/css/report.css (engine tự nạp {$URL_CSS}/{$mod}.css theo mod=report — views/index.tpl) *} 
<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content pj-modal border-0 pb-4">
		<div class="pj-top">
			<button type="button" class="pj-back" onclick="$Core.report_checkin.back_to_list()" title="Quay lại danh sách check-in"><i class="bx bx-chevron-left"></i></button>
			<div class="pj-top-tt">
				<div class="pj-top-t">Timeline Check-in</div>
				<div class="pj-top-s">Xem hành trình hoạt động trong ngày</div>
			</div>
			<button type="button" class="pj-back" data-bs-dismiss="modal" title="Đóng"><i class="bx bx-x"></i></button>
		</div>
		<div class="pj-scroll">
		{if !$pj_allowed}
			<div class="text-center text-muted py-5 px-3">Không có quyền xem hoặc không tìm thấy nhân sự.</div>
		{else}
			<div class="pj-profile">
				<img class="pj-av" src="{$pj_profile.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" alt="">
				<div class="pj-pi overflow-hidden">
					<div class="pj-nm text-truncate">{$pj_profile.name|escape}</div>
					{if $pj_profile.role}<div class="pj-role">{$pj_profile.role|escape}</div>{/if}
					{if $pj_profile.dept}<div class="pj-dept">Phòng ban: {$pj_profile.dept|escape}</div>{/if}
				</div>
			</div>
			<div class="pj-datebar">
				<button type="button" class="pj-nav" onclick="$Core.report_checkin.journey_day(-1)" title="Ngày trước"><i class="bx bx-chevron-left"></i></button>
				<div class="pj-date">{$pj_day_label}</div>
				<button type="button" class="pj-nav" onclick="$Core.report_checkin.journey_day(1)" {if $pj_is_today}disabled{/if} title="Ngày sau"><i class="bx bx-chevron-right"></i></button>
			</div>
			<div class="pj-ov">
				<div class="pj-ovh">Tổng quan hoạt động trong ngày</div>
				<div class="pj-stats">
					<div class="pj-stat"><span class="pj-sic" style="color:#16a34a;background:rgba(22,163,74,.12)"><i class="bx bx-map-pin"></i></span><b>{$pj_stats.count}</b><span class="pj-statl">Lần check-in</span></div>
					<div class="pj-stat"><span class="pj-sic" style="color:#d97706;background:rgba(217,119,6,.12)"><i class="bx bx-been-here"></i></span><b>{$pj_stats.places}</b><span class="pj-statl">Địa điểm</span></div>
					<div class="pj-stat"><span class="pj-sic" style="color:#7c3aed;background:rgba(124,58,237,.12)"><i class="bx bx-camera"></i></span><b>{$pj_stats.photos}</b><span class="pj-statl">Ảnh đã gửi</span></div>
				</div>
			</div>
			<div class="pj-tlh">Timeline check-in trong ngày</div>
			{if empty($pj_items)}
			<div class="text-center text-muted py-4">Không có check-in trong ngày này.</div>
			{else}
			<div class="pj-timeline">
				{foreach from=$pj_items item=_it}
				<div class="pj-item">
					<div class="pj-side"><span class="pj-time">{$_it.time}</span><span class="pj-dot" style="background:{$_it.dot_color|escape}"></span></div>
					<div class="pj-card">
						<div class="pj-cardtop justify-content-between">
							<div class="d-flex flex-column gap-2">
								<div class="pj-cardmain">
									<div class="pj-place"><i class="bx bx-map-pin" style="color:{$_it.dot_color|escape}"></i> {$_it.place|escape}</div>
									{if $_it.tags}<div class="pj-tags">{foreach from=$_it.tags item=_tg}<span class="pj-tag" style="color:{$_tg.color|escape};background:{$_tg.color|escape}22">{$_tg.label|escape}</span>{/foreach}</div>{/if}
								</div>
								{if $_it.note}<div class="pj-note">{$_it.note|escape|nl2br}</div>{/if}
								{if $_it.address}<div class="pj-addr" title="{$_it.address|escape}"><i class="bx bx-map"></i> {$_it.address|escape}</div>{/if}
							</div>
							{if $_it.photo}<img class="pj-photo" src="{$_it.photo}" onerror="this.style.display='none'" data-fancybox="pj_photos" href="{$_it.photo}" data-caption="{$_it.place|escape} · {$_it.time}" alt="">{/if}
						</div>
					</div>
				</div>
				{/foreach}
			</div>
			{/if}
			<div class="pj-foot"><i class="bx bx-info-circle"></i> Dữ liệu check-in được cập nhật tự động theo thời gian thực</div>
		{/if}
		</div>
	</div>
</div>
