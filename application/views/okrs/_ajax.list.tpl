{if !empty($list_okrs)}
	{foreach from=$list_okrs item = _okrs}
	{assign var = okrs_id value = $_okrs.okrs_id}
	<tr data-node-id="{$okrs_id}">
		<td class="text-left fw-bold">{$_okrs.title}</td>
		<td class="text-center">
			<button onClick="$Core.okrs.view_kr('{$okrs_id}')" okrs_id="{$okrs_id}" class="btn btn-sm btn-outline-default">{$_okrs.total_kr} kết quả</button>
		</td>
		<td class="text-center">{$clsOkrs->getProgress()}</td>
		<td class="text-center">0%</td>
		<!-- <td class="text-left">{$clsProperty->getTextColor($_okrs.group_id)}</td> -->
		<td class="text-left">{$clsOkrs->getLabel($_okrs.type_id)}</td>
		<td class="text-left">{$clsOkrs->getStatus($_okrs.okrs_id)}</td>
		<td class="text-center">
			<div class="dropdown">
				<button type="button" class="btn p-0 dropdown-toggle hide-arrow"
					data-bs-toggle="dropdown"> <i class="bx bx-dots-vertical-rounded"></i>
				</button>
				<div class="dropdown-menu">
					<a class="dropdown-item" onClick="$Core.okrs.open(this,event)" okrs_id="{$okrs_id}" href="javascript:void(0);"><i class="bx bx-pencil me-1"></i> Sửa</a>
					<a class="dropdown-item" onClick="$Core.okrs.delete(this,event)" okrs_id="{$okrs_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
				</div>
			</div>
		</td>
	</tr>
	{$clsOkrs->getSubTable($okrs_id)}
	{/foreach}
{else}
	<tr class="nohover">
		<td colspan="10" class="text-center">
			<div class="py-3">
				<img src="{$URL_IMAGES}/table-no-data.png" width="150" />
				<p class="my-2 text-muted">Không có dữ liệu</p>
			</div>
		</td>
	</tr>
{/if}
