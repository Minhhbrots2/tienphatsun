{* _ajax.mt_overview.tpl — Box tổng quan: 4 KPI cards + 4 action cards + banner bỏ quên. Dữ liệu: mt_kpi, mt_dept_name, mt_now_text *}
<!-- ===== Tổng quan phòng (KPI) ===== -->
<div class="crm-ld-ov mb-3">
	<div class="crm-ld-ov-head">
		<h4>Tổng quan{if $mt_dept_name} phòng {$mt_dept_name|escape}{/if}</h4>
		<span class="crm-ld-ov-upd">Cập nhật: Hôm nay {$mt_now_text}</span>
	</div>
	<div class="row g-3">
		<div class="col-6 col-xl-3">
			<div class="crm-ld-ovcard tone-purple">
				<div class="crm-ld-ov-top">
					<div class="crm-ld-ov-ic">
						<i class="bx bx-group"></i>
					</div>
					<div class="d-flex flex-column">
						<div class="crm-ld-ov-num">{$mt_kpi.tong_lead|default:0}</div>
						<div class="crm-ld-ov-lb">Tổng Lead</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-xl-3">
			<div class="crm-ld-ovcard tone-green">
				<div class="crm-ld-ov-top">
					<div class="crm-ld-ov-ic"><i class="bx bxs-heart"></i></div>
					<div class="d-flex flex-column">
						<div class="crm-ld-ov-num js__mt-drill" data-metric="dang_cham">{$mt_kpi.dang_cham|default:0}</div>
						<div class="crm-ld-ov-lb">Đang chăm sóc</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-xl-3">
			<div class="crm-ld-ovcard tone-red">
				<div class="crm-ld-ov-top">
					<div class="crm-ld-ov-ic"><i class="bx bxs-error"></i></div>
					<div class="d-flex flex-column">
						<div class="crm-ld-ov-num js__mt-drill" data-metric="bo_quen">{$mt_kpi.bo_quen|default:0}</div>
						<div class="crm-ld-ov-lb">Khách bị bỏ quên</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6 col-xl-3">
			<div class="crm-ld-ovcard tone-blue">
				<div class="crm-ld-ov-top">
					<div class="crm-ld-ov-ic">
						<i class="bx bx-trophy"></i>
					</div>
					<div class="d-flex flex-column">
						<div class="crm-ld-ov-num js__mt-drill" data-metric="deal_period">{$mt_kpi.deal_period|default:0}</div>
						<div class="crm-ld-ov-lb">Deal thành công</div>
					</div>
				</div>
				{if $mt_kpi.deal_wow_show}
				<div class="crm-ld-ov-trend {if $mt_kpi.deal_wow >= 0}up{else}down{/if}">
					<i class="bx bx-{if $mt_kpi.deal_wow >= 0}up-arrow-alt{else}down-arrow-alt{/if}"></i> {if $mt_kpi.deal_wow >= 0}+{/if}{$mt_kpi.deal_wow}% so với kỳ trước
				</div>
				{/if}
			</div>
		</div>
	</div>
</div>
<!-- ===== 4 thẻ hành động ===== -->
<div class="row g-3 mb-3">
	<div class="col-6 col-xl-3"><div class="crm-ld-actcard sev-danger"><div class="crm-ld-act-ic"><i class="bx bx-user-x"></i></div><div class="crm-ld-act-n js__mt-drill" data-metric="bo_quen">{$mt_kpi.bo_quen|default:0}</div><div class="crm-ld-act-lb">Khách bị bỏ quên</div><a href="/crm/?tab=team&overdue=1" class="crm-ld-act-cta">Xem chi tiết <i class="bx bx-chevron-right"></i></a></div></div>
	<div class="col-6 col-xl-3"><div class="crm-ld-actcard sev-warning"><div class="crm-ld-act-ic"><i class="bx bx-message-rounded-dots"></i></div><div class="crm-ld-act-n js__mt-drill" data-metric="chua_data">{$mt_kpi.chua_data|default:0}</div><div class="crm-ld-act-lb">Chưa tương tác lần nào</div><a href="/crm/?tab=team&untouched=1" class="crm-ld-act-cta">Xem chi tiết <i class="bx bx-chevron-right"></i></a></div></div>
	<div class="col-6 col-xl-3"><div class="crm-ld-actcard sev-warning"><div class="crm-ld-act-ic"><i class="bx bx-time-five"></i></div><div class="crm-ld-act-n js__mt-drill" data-metric="quan_tam">{$mt_kpi.quan_tam|default:0}</div><div class="crm-ld-act-lb">Quan tâm chưa chốt</div><a href="/crm/?tab=team&status_id=294" class="crm-ld-act-cta">Xem chi tiết <i class="bx bx-chevron-right"></i></a></div></div>
	<div class="col-6 col-xl-3"><div class="crm-ld-actcard sev-brand"><div class="crm-ld-act-ic"><i class="bx bx-flag"></i></div><div class="crm-ld-act-n js__mt-drill" data-metric="hot">{$mt_kpi.hot_lead|default:0}</div><div class="crm-ld-act-lb">Lead ưu tiên hôm nay</div><a href="/crm/?tab=team&hot=1" class="crm-ld-act-cta">Xem chi tiết <i class="bx bx-chevron-right"></i></a></div></div>
</div>
{if $mt_kpi.bo_quen > 0}
<div class="crm-ld-alert sev-warning mb-3">
	<div class="crm-ld-alert-ic sev-warning">
		<i class="bx bxs-bell-ring"></i>
	</div>
	<div class="crm-ld-alert-desc flex-grow-1"><b class="lbl">Cảnh báo:</b> Có <b>{$mt_kpi.bo_quen}</b> khách bị bỏ quên quá 2 ngày — cần đốc thúc sale xử lý ngay.</div>
</div>
{/if}
