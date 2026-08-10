{if $template_type eq '_manager'}
<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-white">
				<div class="d-flex gap-3 align-items-center justify-content-between w-100">
					<h5 class="modal-title">Cài đặt phần thưởng <br />
						<span class="text-fs-14 text-main">{$oneLuckyWheel.title}</span>
					</h5>
					<button wheel_id="{$wheel_id}" onClick="$Core.lucky_wheel.open_wheel_prizes(this, event)" 
						class="d-flex align-items-center gap-1 btn{if $deviceType eq 'phone'} btn-icon{/if} btn-outline-danger">
						<i class="bx bx-plus"></i>
						<span class="d-none d-lg-block">Thêm mới</span>
					</button>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-warning">
					Tổng tỷ lệ trúng của các giải thưởng phải &lt;= 100%
				</div>
				<div class="table-container no-shadow text-nowrap overflow-x-auto">
					<table id="{$clsISO->getUniqid()}" cellpadding="0" cellspacing="0" width="100%" 
						class="table table_lucky_wheel_prizes_{$wheel_id} table-bordered mb-0">
						<thead><tr>
							{if $deviceType ne 'phone'}
							<th class="align-center text-center h-px-35 bg-lighter" width="40px">STT</th>
							{/if}
							<th class="align-center text-center h-px-35 bg-lighter" width="40px">
								<i class='bx bx-move'></i>
							</th>
							<th class="align-center text-center h-px-35 bg-lighter">Phần thưởng</th>
							<th class="align-center h-px-35 bg-lighter">Số lượng</th>
							<th class="align-center h-px-35 bg-lighter">Tỷ lệ trúng</th>
							<th class="align-center h-px-35 bg-lighter" width="40px"></th>
						</tr></thead>
						<tbody class="holder_lucky_wheel_prizes">
							{section name=i loop=$list_preloaders max=25}
							<tr>
								{if $deviceType ne 'phone'}
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								{/if}
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							</tr>
							{/section}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
{else}
<div class="modal-dialog">
	<form method="POST" class="d-none" action="#" enctype="multipart/form-data">
		<input type="hidden" name="hid" value="upload" />
		<input type="file" uid="{$uid}" name="image" accept="image/jpeg,image/jpg,image/png,application/pdf" class="select_image_{$uid}" 
			onChange="$Core.lucky_wheel.select_image_upload(this, event)" />
	</form>
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_add'}Thêm{else}Sửa{/if} phần thưởng</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="alert alert-info">Tổng tỷ lệ trúng của các giải thưởng phải &lt;= 100%</div>
			<div class="form-group mb-3">
				<label for="trans_code" class="form-label mb-1">Loại giải thưởng</label>
				<div class="clearfix"></div>
				<div class="btn-group d-flex xs:w-100" role="group" aria-label="Sắp xếp">
					{foreach from=$arr_type key = _oK item = _oT}
					<input onChange="$Core.lucky_wheel.handle_prize_type(this, event)" type="radio" class="btn-check" 
					name="prize_type" id="{$_oK}_{$uid}" value="{$_oK}"{if $onePrize.prize_type eq $_oK} checked{/if}>
					<label for="{$_oK}_{$uid}" data-toggle="ripple" class="btn btn-outline-default">{$_oT}</label>
					{/foreach}
				</div>
			</div>
			<div class="form-group mb-2">
				<label for="name" class="form-label mb-1">Tên giải thưởng</label>
				<input type="text" name="prize_name" class="form-control required" placeholder="Tên giải thưởng" 
					maxlength="255" charset="UTF-8" autocomplete="off" value="{if $action eq '_edit'}{$onePrize.prize_name}{/if}">
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-12 col-md-10">
					<label for="name" class="form-label mb-1">Hình ảnh</label>
					<div class="clearfix"></div>
					<div class="d-flex align-items-start align-items-sm-center gap-3">
						<img class="d-block border rounded-2 image_show_{$uid}" src="{if $action eq '_edit'}{$onePrize.image}{/if}" 
							onerror="this.src='{$URL_IMAGES}/no-image.jpg'" height="80" width="80">
						<div class="button-wrapper">
							<button type="button" onClick="$Core.lucky_wheel.select_image(this, event)" 
								toId="select_image_{$uid}" class="btn btn-outline-default me-2 mb-2 mb-lg-3">Chọn hình ảnh</button>
							<input type="hidden" name="image" class="image_{$uid}" value="{if $action eq '_edit'}{$onePrize.image}{/if}" />
							<p class="text-muted text-fs-12 mb-0">Cho phép JPG, GIF or PNG. Max size of 800K</p>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-2">
					<label class="form-label mb-1">Màu sắc</label>
					<input type="color" name="bgcolor" class="form-control required" 
						autocomplete="off" value="{if $action eq '_edit'}{$onePrize.bgcolor}{/if}">
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label class="form-label mb-1">Số lượng</label>
					<input type="number" name="quantity" class="form-control required" placeholder="Số lượng" 
						maxlength="255" charset="UTF-8"{if $onePrize.prize_type eq 'spin'} readonly="readonly"{/if} autocomplete="off" value="{if $action eq '_edit'}{$onePrize.quantity}{/if}">
				</div>
				<div class="col-6">
					<label class="form-label mb-1">Tỷ lệ trúng</label>
					<div class="input-group input-group-merge">
						<input type="number" name="probability" class="form-control required" placeholder="Tỷ lệ trúng" 
							maxlength="255" charset="UTF-8" autocomplete="off" value="{if $action eq '_edit'}{$onePrize.probability}{/if}">
						<span class="input-group-text">%</span>
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				<label for="desscription" class="form-label mb-1">Miêu tả</label>
				<textarea name="description" class="form-control required" placeholder="Miêu tả giải thưởng" 
					maxlength="255" autocomplete="off">{if $action eq '_edit'}{$onePrize.description}{/if}</textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" wheel_id="{$wheel_id}" prize_id="{$prize_id}" 
				onClick="$Core.lucky_wheel.save_prize(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
{/if}