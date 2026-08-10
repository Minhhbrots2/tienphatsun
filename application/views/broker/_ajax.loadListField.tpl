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
					<div class="btn-group ml-2">
						<button type="button" class="btn btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
						<ul class="dropdown-menu dropdown-menu-end" style="">
							<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.formAddField(this,'open')" data-field_id="{$key}"  data-field="{$field}" data-type="edit">Sửa</a></li>
							<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.deleteField(this)" data-field_id="{$key}" data-field="{$field}">Xoá</a></li>
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
{elseif $field eq "working_process"}
	{if !empty($arr_field)}
		{foreach from=$arr_field key=key item=item}
			<tr>
				<td data-label="Thời gian">{$item.time}</td>
				<td data-label="Tên công ty">{$item.company_name}</td>
				<td data-label="Vị trí">{$item.position}</td>
				<td data-label="Nội dung">{$item.content}</td>
				<td>
					<div class="btn-group ml-2">
						<button type="button" class="btn btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
						<ul class="dropdown-menu dropdown-menu-end" style="">
							<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.formAddField(this,'open')" data-field_id="{$key}"  data-field="{$field}" data-type="edit">Sửa</a></li>
							<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.deleteField(this)" data-field_id="{$key}" data-field="{$field}">Xoá</a></li>
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
{elseif $field eq 'post'}
	{if !empty($lstPost)}
		{foreach from=$lstPost key=key item=item}
			<tr>
				<td data-label="Hình ảnh"><img class="rounded" src="{$item.image}"  alt="{$item.title}" width="50" height="50"></td>
				<td data-label="Tiêu đề">{$item.title|strip_tags:true}</td>				
				<td data-label="Nội dung"><div class="limit_1line box_content_table">{$item.content|html_entity_decode|strip_tags:true}</div></td>	
				<td data-label="Nội dung"><div class="limit_1line box_content_table">{$clsISO->formatDate($item.upd_date,4)}</div></td>	
				<td>
					<div class="btn-group ml-2">
						<button type="button" class="btn btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
						<ul class="dropdown-menu dropdown-menu-end" style="">
							<li><a class="dropdown-item" href="javascript:void(0)" onClick="$Core.broker.open_meta(this,event)" data-meta_id="{$item.id}" gId="{$gId}">Sửa</a></li>
							<li><a class="dropdown-item" href="javascript:void(0)" onClick="$Core.broker.delete_meta(this)" data-meta_id="{$item.id}" gId="{$gId}">Xoá</a></li>
						</ul>
					</div>
				</td>
			</tr>
		{/foreach}
	{else}
		<tr>
			<td class="text-center" colspan="5">Chưa có bài chia sẻ</td>	
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
				<td>
					<div class="btn-group ml-2">
						<button type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
						<ul class="dropdown-menu dropdown-menu-end" style="">
							<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.formAddField(this,'open')" data-field_id="{$key}"  data-field="{$field}" data-type="edit">Sửa</a></li>
							<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.broker.deleteField(this,'delete')" data-field_id="{$key}" data-field="{$field}">Xoá</a></li>
						</ul>
					</div>
				</td>
			</tr>
		{/foreach}
	{else}
		<tr>
			<td class="text-center" colspan="3">Chưa có dữ liệu</td>	
		</tr>
	{/if}
{/if}