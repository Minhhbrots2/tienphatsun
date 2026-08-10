<div class="modal-dialog modal-ipad">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
			<h3 class="modal-title"><strong>{if $action eq '_add'}Thêm gói{else}Sửa gói{/if} tài khoản</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<div class="col-md-8">
						<label class="col-form-label">Tên gói <span class="text-red">*</span></label>
						<input type="text" class="form-control required" placeholder="VD: Gói Pro" name="title" value="{$oneItem.title}" />
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Mã gói</label>
						<input type="text" class="form-control" placeholder="VD: PRO" name="property_code" value="{$oneItem.property_code}" />
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-4">
						<label class="col-form-label">Giá 3 tháng</label>
						<div class="input-group-suffix">
							<input type="text" class="form-control price-In" placeholder="0" name="price_3month" value="{if $mi.price_3month}{$mi.price_3month}{/if}" />
							<span class="suffix">đ</span>
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Giá 6 tháng</label>
						<div class="input-group-suffix">
							<input type="text" class="form-control price-In" placeholder="0" name="price_6month" value="{if $mi.price_6month}{$mi.price_6month}{/if}" />
							<span class="suffix">đ</span>
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Giá 12 tháng</label>
						<div class="input-group-suffix">
							<input type="text" class="form-control price-In" placeholder="0" name="price_year" value="{if $mi.price_year}{$mi.price_year}{/if}" />
							<span class="suffix">đ</span>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-4">
						<label class="col-form-label">Số ngày dùng thử</label>
						<div class="input-group-suffix">
							<input type="number" min="0" step="1" class="form-control numberonly" placeholder="0" name="day_trial" value="{if $mi.day_trial}{$mi.day_trial}{/if}" />
							<span class="suffix">ngày</span>
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Thứ tự</label>
						<input type="number" class="form-control numberonly" name="order_no" value="{$oneItem.order_no}" />
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Hiển thị bảng giá (web)</label>
						<div>
							<label class="switch">
								<input type="hidden" name="show_pricing" value="0" />
								<input type="checkbox" name="show_pricing" value="1" {if $mi.show_pricing}checked{/if} />
								<span class="slider round"></span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label">Quyền lợi / tính năng <small class="text-muted">(tạo danh sách ul/li, in đậm…)</small></label>
					<textarea class="form-control isoTextArea" id="{$clsISO->getUniqid()}" data-name="features" rows="8">{$mi.features}</textarea>
				</div>
				<div class="form-group">
					<label class="col-form-label">Giới thiệu gói</label>
					<textarea class="form-control" name="package_intro" rows="3">{$mi.package_intro}</textarea>
				</div>
				<div class="form-group">
					<label class="col-form-label">Điều khoản dùng thử</label>
					<textarea class="form-control" name="trial_terms" rows="3">{$mi.trial_terms}</textarea>
				</div>
				<div class="form-group form-row">
					<div class="col-md-4">
						<label class="col-form-label">Màu nhãn gói</label>
						<input type="color" class="form-control" name="bgcolor" value="{if $oneItem.bgcolor}{$oneItem.bgcolor}{else}#a4161a{/if}" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="$Core.package.pop_save(this, event)" property_id="{$property_id}">{if $action eq '_add'}Lưu lại{else}Cập nhật{/if}</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">Đóng</button>
			</div>
		</form>
	</div>
</div>
