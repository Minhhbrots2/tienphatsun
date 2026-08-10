<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
			<h3 class="modal-title"><strong>Gán gói tài khoản</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				{if !empty($active)}
				<div class="alert alert-info" style="padding:8px 12px;margin-bottom:12px">
					Đang dùng: <strong>{$active.package_name}</strong>{if $active.end_date} — hết hạn {$active.end_date|date_format:"%d/%m/%Y"}{else} — không giới hạn{/if}.
					<br><small class="text-muted">Gán gói mới sẽ thay thế gói hiện tại (gói cũ chuyển sang "Hết hạn").</small>
				</div>
				{/if}
				<input type="hidden" id="mf_prices" value='{$prices_json|escape:"html"}' />
				<div class="form-group">
					<label class="col-form-label">Gói <span class="text-red">*</span></label>
					<select name="package_id" id="mf_package" class="form-control required" onchange="$Core.member.calc_package_price(this)">
						<option value="0">— Chọn gói —</option>
						{foreach from=$list_packages item=p}
						<option value="{$p.property_id}">{$p.title}{if $p.property_code} ({$p.property_code}){/if}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group form-row">
					<div class="col-md-6">
						<label class="col-form-label">Kỳ hạn</label>
						<select name="cycle" id="mf_cycle" class="form-control" onchange="$Core.member.calc_package_price(this)">
							<option value="price_3month">3 tháng</option>
							<option value="price_6month">6 tháng</option>
							<option value="price_year">12 tháng</option>
						</select>
					</div>
					<div class="col-md-6">
						<label class="col-form-label">Giá đã thu (VNĐ)</label>
						<input type="text" class="form-control" name="price_paid" id="mf_price_paid" placeholder="Tự điền theo gói + kỳ hạn" value="" />
						<small class="text-muted">Tự điền theo gói + kỳ hạn — có thể sửa nếu chiết khấu.</small>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label">Ghi chú</label>
					<textarea class="form-control" name="note" rows="2" placeholder="VD: Admin cấp gói cho CTV..."></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="$Core.member.save_member_package(this, event)" data-profile_id="{$profile_id}" data-mp_id="0">Lưu lại</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">Đóng</button>
			</div>
		</form>
	</div>
</div>
