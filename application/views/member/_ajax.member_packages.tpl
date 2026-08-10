{if !empty($active)}
<div class="alert alert-success" style="padding:8px 12px;margin-bottom:10px">
	Đang dùng: <strong>{$active.package_name}</strong>{if $active.end_date} — hết hạn {$active.end_date|date_format:"%d/%m/%Y"}{else} — không giới hạn{/if}{if $active.price_paid} · đã thu {$active.price_paid} đ{/if}
</div>
{else}
<div class="text-muted" style="margin-bottom:10px">Chưa gán gói nào.</div>
{/if}
<table class="table table-bordered" width="100%">
	<thead><tr>
		<th class="bg-lighter">Gói</th>
		<th class="bg-lighter">Loại</th>
		<th class="bg-lighter">Bắt đầu</th>
		<th class="bg-lighter">Hết hạn</th>
		<th class="bg-lighter">Giá đã thu</th>
		<th class="bg-lighter">Trạng thái</th>
		<th class="bg-lighter" width="40px"></th>
	</tr></thead>
	<tbody>
	{if !empty($list)}
		{foreach from=$list item=mp}
		<tr>
			<td><strong>{$mp.package_name}</strong></td>
			<td>
				{if $mp.change_type eq 'upgrade'}Nâng cấp
				{elseif $mp.change_type eq 'downgrade'}Hạ cấp
				{elseif $mp.change_type eq 'renew'}Gia hạn
				{elseif $mp.change_type eq 'trial'}Dùng thử
				{elseif $mp.change_type eq 'admin'}Admin cấp
				{else}Cấp mới{/if}
				{if $mp.source} <small class="text-muted">({$mp.source})</small>{/if}
			</td>
			<td>{if $mp.start_date}{$mp.start_date|date_format:"%d/%m/%Y"}{else}—{/if}</td>
			<td>{if $mp.end_date}{$mp.end_date|date_format:"%d/%m/%Y"}{else}—{/if}</td>
			<td>{if $mp.price_paid}{$mp.price_paid} đ{/if}</td>
			<td>
				{if $mp.status eq 'cancel'}<span class="label label-danger">Đã hủy</span>
				{elseif $mp.status eq 'expired'}<span class="label label-default">Hết hạn</span>
				{elseif $mp.end_date and $mp.end_date < $smarty.now}<span class="label label-default">Hết hạn</span>
				{else}<span class="label label-success">Đang dùng</span>{/if}
			</td>
			<td>
				<a href="javascript:void(0)" class="text-red" title="Xóa khỏi lịch sử" onClick="$Core.member.delete_member_package(this)" data-profile_id="{$profile_id}" data-mp_id="{$mp.id}"><i class="icon-trash"></i></a>
			</td>
		</tr>
		{/foreach}
	{else}
		<tr><td class="text-center" colspan="7">Chưa có lịch sử gói</td></tr>
	{/if}
	</tbody>
</table>
