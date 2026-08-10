<header class="ui-title-bar-container">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Thi đua định danh</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__actions">
				<a href="{$PCMS_URL}/?mod={$mod}&act=edit" class="btn btn-success">+ Thêm chương trình</a>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="ui-card">
					<table class="table table-hover m-0">
						<thead>
							<tr>
								<th width="50">#</th>
								<th>Tên chương trình</th>
								<th>Thời gian</th>
								<th class="text-center">Phòng ban</th>
								<th class="text-center">Trạng thái</th>
								<th width="120"></th>
							</tr>
						</thead>
						<tbody>
							{if !empty($programs)}
								{foreach from=$programs item=p}
								<tr>
									<td>{$p.id}</td>
									<td><strong>{$p.name|escape}</strong></td>
									<td>
										{if $p.period_type eq 'year'}Cả năm{elseif $p.period_type eq 'quarter'}Quý{elseif $p.period_type eq 'month'}Tháng{else}Khoảng ngày{/if}
										{if $p.start_date}<br /><small class="text-muted">{$p.start_date|date_format:"%d/%m/%Y"} - {$p.end_date|date_format:"%d/%m/%Y"}</small>{/if}
									</td>
									<td class="text-center">{$p.department_ids|@count}</td>
									<td class="text-center">
										{if $p.status}<span class="label label-success">Đang chạy</span>{else}<span class="label label-default">Tạm dừng</span>{/if}
									</td>
									<td>
										<a href="{$PCMS_URL}/?mod={$mod}&act=edit&id={$p.id}" class="btn btn-xs btn-primary text-white">Sửa</a>
										<a href="{$PCMS_URL}/?mod={$mod}&act=delete&id={$p.id}" class="btn btn-xs btn-danger" onclick="return confirm('Xoá chương trình này?')">Xoá</a>
									</td>
								</tr>
								{/foreach}
							{else}
								<tr><td colspan="6" class="text-center text-muted p-4">Chưa có chương trình nào. Bấm "+ Thêm chương trình".</td></tr>
							{/if}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
