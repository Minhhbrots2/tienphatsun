{if !empty($active)}
<div class="alert alert-success" style="padding:8px 12px;margin-bottom:10px">
	Đang dùng: <strong>{$pkg_names[$active.package_id]|default:''|escape}</strong>{if $active.end_date} — hết hạn {$active.end_date|date_format:"%d/%m/%Y"}{else} — không giới hạn{/if}{if $active.price_paid} · đã thu {$active.price_paid}{/if}
</div>
{else}
<div class="text-muted" style="margin-bottom:10px">Chưa gán gói nào.</div>
{/if}
<table class="table table-bordered" width="100%">
	<thead><tr>
		<th class="bg-lighter">Gói</th>
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
			<td><strong>{$pkg_names[$mp.package_id]|default:''|escape}</strong></td>
			<td>{if $mp.start_date}{$mp.start_date|date_format:"%d/%m/%Y"}{/if}</td>
			<td>{if $mp.end_date}{$mp.end_date|date_format:"%d/%m/%Y"}{else}—{/if}</td>
			<td>{$mp.price_paid}</td>
			<td>
				{if $mp.status eq 'cancelled'}<span class="label label-danger">Đã hủy</span>
				{elseif $mp.end_date and $mp.end_date < $smarty.now}<span class="label label-default">Hết hạn</span>
				{else}<span class="label label-success">Đang dùng</span>{/if}
			</td>
			<td>
				<div class="btn-group">
					<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"><i class="icon-cog"></i> <span class="caret"></span></button>
					<ul class="dropdown-menu" style="right:0px !important; left:auto">
						<li><a href="javascript:void(0)" onClick="$Core.member.open_assign_package(this)" data-profile_id="{$profile_id}" data-mp_id="{$mp.id}">Sửa</a></li>
						<li><a href="javascript:void(0)" onClick="$Core.member.delete_member_package(this)" data-profile_id="{$profile_id}" data-mp_id="{$mp.id}">Xóa</a></li>
					</ul>
				</div>
			</td>
		</tr>
		{/foreach}
	{else}
		<tr><td class="text-center" colspan="6">Chưa có lịch sử gói</td></tr>
	{/if}
	</tbody>
</table>
