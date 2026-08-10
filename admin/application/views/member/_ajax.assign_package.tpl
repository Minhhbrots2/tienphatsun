<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
			<h3 class="modal-title"><strong>{if $mp.id}Sửa{else}Gán{/if} gói tài khoản</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label">Gói <span class="text-red">*</span></label>
					<select name="package_id" class="form-control required" onChange="$Core.member.package_changed(this)">
						<option value="0">— Chọn gói —</option>
						{foreach from=$list_packages item=p}
						<option value="{$p.property_id}"{if $mp.package_id eq $p.property_id} selected{/if} data-price-1="{$p.prices.1}" data-price-3="{$p.prices.3}" data-price-6="{$p.prices.6}" data-price-12="{$p.prices.12}">{$p.title|escape}{if $p.property_code} ({$p.property_code|escape}){/if}</option>
						{/foreach}
					</select>
				</div>
				<div class="form-group form-row">
					<div class="col-md-6">
						<label class="col-form-label">Thời hạn</label>
						<select name="duration_preset" class="form-control" onChange="$Core.member.apply_package_duration(this)">
							<option value="0">— Tự nhập ngày —</option>
							<option value="1">1 tháng</option>
							<option value="3">3 tháng</option>
							<option value="6">6 tháng</option>
							<option value="12">1 năm</option>
						</select>
					</div>
					<div class="col-md-6">
						<label class="col-form-label">Giá đã thu</label>
						<input type="text" class="form-control" name="price_paid" placeholder="VD: 450.000" value="{$mp.price_paid}" />
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-6">
						<label class="col-form-label">Ngày bắt đầu</label>
						<input type="text" class="form-control datepicker" name="start_date" placeholder="dd/mm/yyyy" value="{if $mp.start_date}{$mp.start_date|date_format:'%d/%m/%Y'}{/if}" />
					</div>
					<div class="col-md-6">
						<label class="col-form-label">Ngày hết hạn <small class="text-muted">(trống = không giới hạn)</small></label>
						<input type="text" class="form-control datepicker" name="end_date" placeholder="dd/mm/yyyy" value="{if $mp.end_date}{$mp.end_date|date_format:'%d/%m/%Y'}{/if}" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label">Trạng thái</label>
					<select name="status" class="form-control">
						<option value="active"{if not $mp.id or $mp.status eq 'active'} selected{/if}>Đang dùng</option>
						<option value="expired"{if $mp.status eq 'expired'} selected{/if}>Hết hạn</option>
						<option value="cancelled"{if $mp.status eq 'cancelled'} selected{/if}>Đã hủy</option>
					</select>
				</div>
				<div class="form-group">
					<label class="col-form-label">Ghi chú</label>
					<textarea class="form-control" name="note" rows="2">{$mp.note|escape}</textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="$Core.member.save_member_package(this, event)" data-profile_id="{$profile_id}" data-mp_id="{if $mp.id}{$mp.id}{else}0{/if}">Lưu lại</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">Đóng</button>
			</div>
		</form>
	</div>
</div>
