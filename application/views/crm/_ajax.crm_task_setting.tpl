<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable crm-ts-dialog" role="document">
	<div class="modal-content crm-ts-card">
		<div class="modal-header crm-ts-header">
			<div class="crm-ts-htitle">
				<h5 class="modal-title"><i class="bx bx-git-branch"></i> {$titlePage} <span class="crm-ts-htitle-sub">· luật NẾU → THÌ</span></h5>
				<p class="crm-ts-hsub">Gom theo tác nghiệp đầu · Gọi điện · Nhắn tin · Gặp trực tiếp</p>
			</div>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body crm-ts" data-results="{$results_by_task_json|escape:'html'}">
			<p class="crm-ts-help">Sale chọn <b>kết quả</b> sau mỗi tác nghiệp → CRM tự sinh tác nghiệp tiếp.</p>
			{* ===== BẢNG LUẬT gom theo tác nghiệp đầu ===== *}
			<div class="crm-ts-tablewrap">
				<table class="table crm-ts-table mb-0">
					<thead><tr>
						<th style="width:38%">Kết quả</th>
						<th style="width:27%">Tác nghiệp kế tiếp</th>
						<th style="width:20%">Sau bao lâu</th>
						<th class="crm-ts-delcol"></th>
					</tr></thead>
					<tbody class="js__crm-task-table-body">{$groups_html}</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
{* ===== POPUP form 1 luật (centered overlay, sibling của .modal-dialog — nằm trong .modal wrapper do $Core.popup.open tự bọc) ===== *}
<div class="crm-ts-popup d-none js__crm-task-popup">
	<div class="crm-ts-popup-card js__crm-task-form">
		<input type="hidden" class="js__crm-task-form-id" value="0">
		<div class="crm-ts-popup-h"><i class="bx bx-git-branch"></i> <span class="js__crm-task-form-title">Thêm luật tác nghiệp</span><i class="bx bx-x crm-ts-popup-x" onclick="$Core.crm.crm_task_form_cancel(this, event)"></i></div>
		<div class="crm-ts-popup-b">
			<div class="crm-ts-summary js__crm-task-summary"></div>
			<div class="crm-ts-blk">
				<div class="crm-ts-bl">NẾU — tác nghiệp &amp; kết quả</div>
				<div class="crm-ts-fld">
					<label>Tác nghiệp đầu</label>
					<select class="form-select form-select-sm js__crm-task-form-task">
						<option value="">Chọn tác nghiệp</option>
						{if !empty($list_action_task)}{foreach from=$list_action_task item=_t}<option value="{$_t.id}">{$_t.title|escape}</option>{/foreach}{/if}
					</select>
				</div>
				<div class="crm-ts-fld">
					<label>Kết quả <span class="crm-ts-hint">— chọn hoặc gõ để tạo mới</span></label>
					<select class="form-select form-select-sm js__crm-task-form-result"><option value="">Chọn / gõ kết quả…</option></select>
				</div>
				<div class="crm-ts-fld"><label class="crm-ts-chk"><input type="checkbox" class="js__crm-task-form-counter"> Đếm số lần (lần 1/2/3 — counter)</label></div>
			</div>
			<div class="crm-ts-blk">
				<div class="crm-ts-bl">THÌ — tác nghiệp tiếp</div>
				<div class="crm-ts-fld">
					<label>Tác nghiệp kế tiếp</label>
					<select class="form-select form-select-sm js__crm-task-form-next">
						<option value="">Chọn tác nghiệp tiếp</option>
						{if !empty($list_next_task)}{foreach from=$list_next_task item=_t}<option value="{$_t.id}">{$_t.title|escape}</option>{/foreach}{/if}
					</select>
				</div>
				<div class="crm-ts-fld">
					<label>Thời điểm</label>
					<div class="crm-ts-rad"><label class="crm-ts-radlbl"><input type="radio" name="crm_ts_when_{$uid}" value="delay" class="js__crm-task-when" checked> Sau một khoảng</label>
						<select class="form-select form-select-sm crm-ts-delaysel js__crm-task-form-delay">{$delay_options}</select>
					</div>
					<div class="crm-ts-rad"><label class="crm-ts-radlbl"><input type="radio" name="crm_ts_when_{$uid}" value="datetime" class="js__crm-task-when"> Cần lịch hẹn <span class="crm-ts-bdg b-warn">sale nhập giờ</span></label></div>
				</div>
			</div>
			<div class="crm-ts-blk">
				<div class="crm-ts-bl">Tuỳ chọn</div>
				<div class="crm-ts-fld"><label>Trạng thái KH</label><select class="form-select form-select-sm js__crm-task-form-status">{$status_options}</select></div>
				<div class="crm-ts-fld"><label class="crm-ts-chk"><input type="checkbox" class="js__crm-task-form-active" checked> Kích hoạt luật (Active)</label></div>
			</div>
		</div>
		<div class="crm-ts-popup-foot">
			<button type="button" class="btn btn-sm btn-outline-secondary" onclick="$Core.crm.crm_task_form_cancel(this, event)">Huỷ</button>
			<button type="button" class="btn btn-sm btn-primary" onclick="$Core.crm.crm_task_save(this, event)"><i class="bx bx-check"></i> Lưu luật</button>
		</div>
	</div>
</div>
