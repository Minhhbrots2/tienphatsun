{assign var= toId value = $clsISO->getUniqid()}
<div class="row d-flex">
	<div class="col-12 col-md-10 col-lg-8 mx-auto">
		<div class="ui-title-bar-container ui-title-bar-container--full-width">
			<div class="ui-title-bar">
				<div class="ui-title-bar__main-group">
					<div class="ui-title-bar__heading-group">
						{if $action eq "stock"}
						<h1 class="ui-title-bar__title w-100">Log đồng bộ api căn hộ sơ cấp HouseNow</h1>
						{else}
						<h1 class="ui-title-bar__title w-100">Log đồng bộ api chuyển nhượng HouseNow</h1>
						{/if}
						<p class="type--subdued">{$core->get_Lang('Quản lý dữ liệu đồng bộ')}</p>
					</div>
				</div>
				<div class="action-bar">
					<div class="ui-title-bar__mobile-primary-actions">
						<div class="ui-title-bar__actions">
							{if $action eq "stock"}
							<a href="javascript:void(0);" onClick="$Core.api_log.sync_data_stock(this,event)" title="Đồng bộ" 
							   class="ui-button ui-button--transparent js_create_add ui-title-bar__action mr-2">Đồng bộ</a>
							{else}
							<a href="javascript:void(0);" onClick="$Core.api_log.sync_data_sop(this,event)" title="Đồng bộ" 
								class="ui-button ui-button--transparent js_create_add ui-title-bar__action mr-2">Đồng bộ</a>
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
		<form class="d-none" method="post" enctype="multipart/form-data">
			<input type="file" class="select_file select_file_{$toId}" name="import_file" />
		</form>
		<div class="ui-layout ui-layout--full-width">
			<div class="ui-layout__sections"><div class="ui-layout__section">
				<div class="ui-layout__item"><div class="ui-card">
					<div class="next-tab__container">
						<ul class="next-tab__list filter-tab-list">
							<li class="filter-tab-item" data-tab-index="1">
								<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">Danh sách đồng bộ</a>
							</li>
						</ul>
					</div>
					<div class="ui-card__section has-bulk-actions pages">
						<table class="table" width="100%" cellpadding="0" cellspacing="0">
							<thead><tr>
								<th class="text-left" width="70%">Tài khoản log</th>								
								<th width="120px">Thời gian</th>	
							</tr></thead>
							<tbody class="holder_logs">
								<tr>
									<td class="text-center" colspan="10">
										Loading...
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div></div>
			</div></div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var action = `{$action}`;
</script>