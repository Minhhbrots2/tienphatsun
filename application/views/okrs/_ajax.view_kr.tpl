{if $template_type eq 'view_kr'}
<div class="modal-dialog modal-xl modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<table class="table">
				<thead><tr>
					<th class="text-left">Kết quả chính</th>
					<th class="text-center">Mục tiêu</th>
					<th class="text-center">Đơn vị</th>
					<th class="text-left">Đạt được</th>
					<th class="text-left">Tiến độ</th>
					<th class="text-center">Thay đổi</th>
					<th class="text-center">Hành động</th>
				</tr></thead>
				<tbody class="holder_kr_{$okrs_id}"></tbody>
			</table>
		</div>
		<div class="modal-footer">
			{if $permiss_action eq '1'}
			<button onClick="$Core.okrs.open_kr(this, event)" kr_id="" okrs_id="{$okrs_id}" class="btn btn-primary">+ Thêm mục tiêu</button>
			{/if}
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
		</div>
	</div>
</div>
{elseif $template_type eq 'open_kr'}
<div class="modal-dialog modal-dialog-centered">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-3">
				<label class="form-label mb-1">Kết quả chính</label>
				<input class="form-control required" placeholder="Kết quả chính" name="main_result" value="{if $action eq '_edit'}{$oneKr.main_result}{/if}" />
			</div>
			<div class="form-group form-row mb-3">
				<div class="col-12 col-md-6 mb-3 mb-lg-0">
					<label class="form-label mb-1">Mục tiêu</label>
					<input class="form-control required numberonly" placeholder="Mục tiêu" name="target" value="{if $action eq '_edit'}{$oneKr.target}{/if}" />
				</div>
				<div class="col-12 col-md-6">
					<label class="form-label mb-1">Đơn vị</label>
					<select class="form-control form-select" placeholder="Đơn vị" name="unit_id">
						{$clsProperty->getSelectByProperty('_UNIT', $oneKr.unit_id)}
					</select>
				</div>
			</div>
			<div class="form-group mb-3">
				<label class="form-label mb-1">Kế hoạch</label>
				<textarea class="form-control" cols="255" placeholder="Kế hoạch" name="plan" rows="3">{if $action eq '_edit'}{$oneKr.plan}{/if}</textarea>
			</div>
			<div class="form-group ">
				<label class="form-label mb-1">Kết quả thực tế</label>
				<textarea class="form-control" cols="255" placeholder="Kết quả thực tế" name="result" rows="3">{if $action eq '_edit'}{$oneKr.result}{/if}</textarea>
			</div>
		</div>
		<div class="modal-footer border-top bg-lighter">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button onClick="$Core.okrs.save_kr(this, event)" kr_id="{$kr_id}" okrs_id="{$okrs_id}" class="btn btn-primary">Cập nhật</button>
		</div>
	</form>
</div>
{elseif $template_type eq 'list_kr'}
	{if !empty($list_krs)}
		{foreach from=$list_krs key=kr_id item = _oKr}
		<tr>
			<td class="text-left">{$_oKr.main_result}</td>
			<td class="text-center">{$_oKr.target}</td>
			<td class="text-center">{$clsProperty->getTitle($_oKr.unit_id)}</td>
			<td class="text-center">0</td>
			<td class="text-center">{$clsOkrs->getProgress()}</td>
			<td class="text-center">0%</td>
			<td class="text-center">
				<button{if $permiss_action eq '0'} disabled{/if} kr_id="{$kr_id}" okrs_id="{$okrs_id}" onClick="$Core.okrs.open_kr(this, event);" class="btn p-1 btn-outline-default">{$clsISO->makeIcon('bx-pencil')}</button>
				<button{if $permiss_action eq '0'} disabled{/if} kr_id="{$kr_id}" okrs_id="{$okrs_id}" onClick="$Core.okrs.delete_kr(this, event);" class="btn p-1 btn-outline-default">{$clsISO->makeIcon('bx-trash')}</button>
			</td>
		</tr>
		{/foreach}
	{else}
		<tr class="nohover">
			<td colspan="10" class="text-center">
				<div class="py-3">
					<img src="{$URL_IMAGES}/table-no-data.png" />
					<p class="text-muted">Không có dữ liệu</p>
				</div>
			</td>
		</tr>
	{/if}
{/if}