<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex justify-content-between align-items-center gap-2 mb-2">
		<div class="d-flex flex-column gap-1">
			<h4 class="fw-bold mb-0 fs-5">Dữ liệu cư dân</h4>
			<span class="text-muted fs-12">Tổng (<strong class="total_record text-main">{$total_record}</strong>) dữ liệu cư dân trên hệ thống</span>
		</div>
		<button class="btn btn-outline-default" onClick="$Core.data_central.open_campaign(this,event)" campaign_id="0" >Tạo chiến dịch</button>
	</div>
	<div class="card no-shadow">
		<div class="card-body" id="table_data_central">
			<form action="#" enctype="multipart/form-data" method="POST">
				<div class="d-flex align-items-center flex-fill gap-1 justify-content-between mb-2">
					<button class="btn btn-outline-default btn_customer_campaign" type="button" disabled="disabled" onClick="$Core.data_central.open_data_campaign(this,event)" campaign_id="0" >Phân data</button>
					<input type="hidden" name="filter" value="filter" />
					{if $deviceType eq 'computer'}
						{$core->getBlock('data_central_search')}					
					{else}
						<div class="d-flex gap-1">
							{if $clsISO->checkPermission("import_data_central")}
							<button type="button" class="btn btn-icon btn-default" onclick="$Core.data_central.open_import(this, event)" 
								title="Cập nhật dữ liệu"><i class="fa fa-upload"></i>
							</button>			
							{/if}
							<div class="dropdown">
								<button type="button" class="btn btn-icon btn-default dropdown-toggle hide-arrow" data-bs-toggle="dropdown" 
									data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>
								<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-350" data-popper-placement="top-end">
									{$core->getBlock('data_central_search')}
								</div> 
							</div>
						</div>
					{/if}
				</div>
			</form>
			<div class="table-container no-shadow overflow-x-auto">
				<table class="table table-bordered text-nowrap dragable" width="100%" cellpadding="0" cellspacing="0">
					<thead id="thead_data_central"><tr>
						{if $deviceType ne 'phone'}
						<th class="text-center bg-lighter h-px-40" width="40px">STT</th>
						{/if}
						<th class="align-center bg-grayter text-center h-px-40 border-0" width="40px">
							<input type="checkbox" class="form-check-input" tp="all" onchange="$Core.data_central.check_item(this, event)" style="font-size:0.85rem !important">
						</th>
						{foreach from=$arr_columns item=_oItem}
						<th class="align-center{if $_oItem.code eq 'number_call'} text-center{/if} bg-lighter h-px-40">{$_oItem.title}</th>
						{/foreach}
						<!-- <th class="text-center bg-lighter h-px-40">Trạng thái</th> -->
						<th class="text-center border-left-0 h-px-40 bg-lighter" width="30px">
							<button type="button" class="btn btn-xs btn-icon btn-sm rounded-pill btn-link" action="_OPEN" 
								onclick="$Core.data_central.setting_field(this,event)" title="Tùy chỉnh cột"><i class="bx bx-cog text-muted"></i>
							</button>
						</th>
					</tr></thead>
					<tbody class="tbody_data_central">
						{section name=i loop=$list_preloaders max = 30}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td{if $deviceType ne 'phone'} class="border-left-0"{/if}><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
				<div class="d-flex justify-content-between text-center" id="showmorethisresult">
					<button type="button" class="showmorethisresult py-3" onClick="$Core.data_central.load_more(this, event)" page="1"> 
						<span>Xem thêm</span> 
						<img src="{$URL_IMAGES}/loading_48.gif" width="24px"> 
					</button> 
				</div>
			</div>
			{if !empty($html_pager)}
			<div class="pagination justify-content-center flex-wrap gap-1 mt-4">{$html_pager}</div>
			{/if}
		</div>
	</div>
</div>