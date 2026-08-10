{if $field eq "history_sale"}
	{if !empty($arr_field)}
		{foreach from=$arr_field key=key item=item}
			<tr>
				<td data-label="Mã căn">{$item.stock_code}</td>
				<td data-label="Dự án">{$item.project}</td>
				<td data-label="Số tiền">{$clsISO->shortNumber($item.price)}</td>
				<td data-label="Tên khách hàng">{$item.customer_name}</td>
				<td data-label="Ngày giao dịch">{$item.date_trading}</td>
				<td>
					<div class="btn-group">
						<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
						<ul class="dropdown-menu" style="right:0px !important; left: auto">
							<li><a title="Sửa" href="javascript:void(0)"  onClick="$Core.member.formAddField(this,'open')"  data-member_id='{$member_id}' data-field_id="{$key}" data-field="{$field}" data-type="edit"><i class="icon-edit"></i> <span>Sửa</span></a></li>
							<li><a href="javascript:void(0)" onClick="$Core.member.deleteField(this,'delete')" data-member_id='{$member_id}' data-field_id="{$key}" data-field="{$field}"><i class="icon-trash"></i> <span>Xoá</span></a></li>
						</ul>
					</div>
				</td>
			</tr>
		{/foreach}
	{else}
		<tr>
			<td class="text-center" colspan="6">Chưa có dữ liệu</td>	
		</tr>
	{/if}
{else}
	{if !empty($arr_field)}
		{foreach from=$arr_field key=key item=item}
			<tr>
				<td data-label="Hình ảnh"><img class="rounded" src="{$item.image}" alt="{$item.title}" width="50" height="50"></td>
				<td data-label="Tiêu đề">{$item.title}</td>
				{if $field eq 'project'}
					<td class="text-center" data-label="Số căn bán">{$item.total_sale_project}</td>
				{/if}
				{if $field eq "customer_review"}
					<td class="text-center" data-label="Số sao">{$item.star}</td>
					<td data-label="Nội dung"><div class="text_4line">{$item.content}</div></td>				
					<td class="text-center d-none" data-label="Ngày đánh giá">{$item.date}</td>
				{/if}
				<td>
					<div class="btn-group">
						<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
						<ul class="dropdown-menu dropdown-menu-end" style="">
							<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.member.formAddField(this,'open')" data-member_id='{$member_id}' data-field_id="{$key}"  data-field="{$field}" data-type="edit">Sửa</a></li>
							<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.member.deleteField(this)" data-member_id='{$member_id}' data-field_id="{$key}" data-field="{$field}">Xoá</a></li>
						</ul>
					</div>
				</td>
			</tr>
		{/foreach}
	{else}
		<tr>
			<td class="text-center" colspan="{if $field eq 'project'}4{else if $field eq 'customer_review'}6 {else}3{/if}">Chưa có dữ liệu</td>	
		</tr>
	{/if}
{/if}