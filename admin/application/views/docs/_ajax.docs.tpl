{if !empty($list_docs)}
	{foreach name=i from=$list_docs item = _odocs}
	{assign var = list_tags value = $_odocs.list_tags}
	<tr>
		<td class="text-center">
			<div class="checkbox">
				<input type="checkbox" name="p_key[]" class="chkitem styled" 
				value="{$_odocs.id}" />
				<label></label>
			</div>
		</td>
		<td class="text-center"><img src="{$_odocs.image}" onerror="this.src='{$URL_IMAGES}/no-image.jpg'" class="border radius-3" style="width:40px;height:40px;object-fit:cover" /></td>
		<td><strong class="font-bold">{$_odocs.title}</strong>{if $_odocs.file_type} <span class="label label-info fs-tiny">{$_odocs.file_type}</span>{/if}<br />
			{$_odocs.title_search}
			{if !empty($list_tags)}
			<div class="mt-2">
				{foreach from=$list_tags item = tag}
					<span class="label label-primary p-1 fs-tiny">{$tag}</span>
					{/foreach}
				{/if}
			</div>
		</td>
		<td><a href="{$_odocs.content}" title="{$_odocs.content}" data-toggle="tooltip" target="_blank">Link</a></td>
		<td class="text-left">{$_odocs.type}</td>
		<td class="text-left">{$_odocs.project_name}</td>
		<td class="text-left">{$_odocs.block_name}</td>
		<td class="text-left">{$_odocs.building_name}</td>
		<td class="text-left">{$_odocs.cat_name}</td>
		<td class="text-left text-nowrap">{$clsISO->formatDate($_odocs.upd_date,4)}</td>
		<td class="text-center">
			<div class="d-flex gap-2 align-items-center">
				{if $type_list eq 'trash'}
				<button onClick="$Core.docs.restore(this, event)" project_meta_id="{$_odocs.id}" class="btn btn-icon btn-sm btn-default" title="Khôi phục">{$core->makeIcon('undo')}</button>
				<button onClick="$Core.docs.force_delete(this, event)" project_meta_id="{$_odocs.id}" class="btn btn-icon btn-sm btn-danger" title="Xoá vĩnh viễn">{$core->makeIcon('trash')}</button>
				{else}
				<button onClick="$Core.docs.open(this, event)" project_meta_id="{$_odocs.id}" class="btn btn-icon btn-sm btn-default">{$core->makeIcon('pencil')}</button>
				<button onClick="$Core.docs.delete(this, event)" project_meta_id="{$_odocs.id}" class="btn btn-icon btn-sm btn-default">{$core->makeIcon('trash')}</button>
				{/if}
			</div>
			
		</td>
	</tr>
	{/foreach}
{/if}