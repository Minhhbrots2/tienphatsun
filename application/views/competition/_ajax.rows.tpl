{if !empty($ranking)}
	{foreach from=$ranking item=r}
	<tr>
		<td class="text-center">{if $r.stt eq 1}<span class="text-warning">&#127942;</span>{elseif $r.stt eq 2}<span class="text-muted">&#129352;</span>{elseif $r.stt eq 3}<span style="color:#cd7f32">&#129353;</span>{else}{$r.stt}{/if}</td>
		<td>
			<div class="d-flex align-items-center gap-2">
				<img src="{$r.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" class="rounded-circle flex-shrink-0" width="30" height="30" style="object-fit:cover" />
				<div>
					<div class="fw-semibold">{$r.name|escape}</div>
					{if $r.dept}<small class="text-muted">{$r.dept|escape}</small>{/if}
				</div>
			</div>
		</td>
		<td class="text-center fw-bold">{$r.score}</td>
		<td class="text-center">{if $r.rank_name}{$r.rank_name|escape}{else}--{/if}</td>
	</tr>
	{/foreach}
{else}
	<tr><td colspan="4" class="text-center text-muted py-3">Chưa có dữ liệu.</td></tr>
{/if}
