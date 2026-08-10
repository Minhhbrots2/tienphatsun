<div class="modal-dialog modal-dialog-centered">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_edit'}Sửa{else}Thêm{/if} chương trình</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<label for="trans_code" class="form-label mb-1">Loại chương trình</label>
				<div class="clearfix"></div>
				<div class="btn-group d-flex xs:w-100" role="group" aria-label="Sắp xếp">
					{foreach from=$arr_type key = _oK item = _oT}
					<input type="radio" class="btn-check" name="program_type" id="{$_oK}_{$uid}" 
						value="{$_oK}"{if $oneLuckyWheel.program_type eq $_oK} checked{/if}>
					<label for="{$_oK}_{$uid}" data-toggle="ripple" class="btn btn-outline-default">{$_oT}</label>
					{/foreach}
				</div>
			</div>
			<div class="form-group mb-2">
				<label for="name" class="form-label mb-1">Tên chương trình</label>
				<input type="text" name="title" class="form-control required" placeholder="Tên chương trình" 
					maxlength="255" charset="UTF-8" autocomplete="off" value="{if $action eq '_edit'}{$oneLuckyWheel.title}{/if}">
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-6">
					<label for="name" class="form-label mb-1">Ngày bắt đầu</label>
					<input type="datetime-local" name="start_date" class="form-control" value="{if $action eq '_edit'}{$clsISO->convertTimeToISOString($oneLuckyWheel.start_date)}{/if}" />
				</div>
				<div class="col-6">
					<label for="name" class="form-label mb-1">Ngày kết thúc</label>
					<input type="datetime-local" name="end_date" class="form-control" value="{if $action eq '_edit'}{$clsISO->convertTimeToISOString($oneLuckyWheel.end_date)}{/if}" />
				</div>
			</div>
			<div class="form-group mb-2">
				<label for="desscription" class="form-label mb-1">Miêu tả</label>
				<textarea name="description" class="form-control required" placeholder="Miêu tả chương trình" 
					maxlength="255" autocomplete="off">{if $action eq '_edit'}{$oneLuckyWheel.description}{/if}</textarea>
			</div>
			<div class="divider text-start">
				<div class="divider-text">Cài đặt chương trình</div>
			</div>
			<div class="d-flex align-items-center gap-2 mb-2">
				<span class="text-muted">Số lượt quay/mở</span>
				<input type="number" name="max_spin_per_user" class="form-control w-px-100 required" placeholder="Lần" 
				maxlength="255" value="{$oneLuckyWheel.max_spin_per_user}">
			</div>
			<div class="bg-lighter p-3 rounded-2">
				<label for="name" class="form-label">Áp dụng cho</label>
				{foreach from=$arr_targets item = $arr_targets key = _oK item = _oT}
				<div class="form-check mb-2">
					<input class="form-check-input" id="{$uid}_{$_oK}" type="radio" name="reward_type" value="{$_oK}"
						{if $oneLuckyWheel.reward_type eq $_oK} checked{/if}>
					<label for="{$uid}_{$_oK}" class="form-check-label">{$_oT}</label>
				</div>
				{/foreach}
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" wheel_id="{$wheel_id}" onClick="$Core.lucky_wheel.save(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
