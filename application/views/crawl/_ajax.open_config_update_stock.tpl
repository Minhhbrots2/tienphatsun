<div class="modal-dialog modal-fullscreen">
	<div class="modal-content">
		<form method="POST" action="" enctype="multipart/form-data">
			<div class="modal-header"> 
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
				<h3 class="modal-title"><strong>Quản lý đại lý không cập nhật bảng hàng</strong></h3>
			</div>
			<div class="modal-body">
				<div class="table-container no-shadow overflow-auto" style="max-height:calc(100vh - 120px)">
					<table class="table table-bordered dragable installed" cellpadding="0" cellspacing="0" width="100%">
						{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
							<thead class="position-sticky top-0 zindex-5" style="background: #f5f7f8 !important;">
								<tr>
									{if $deviceType ne 'phone'}
										<th class="align-center h-px-40 zindex-3" width="3%" rowspan="3">No.</th>
									{/if}
									<th class="align-center h-px-40 zindex-3" rowspan="3" width="15%">Đại lý</th>
									<th class="align-center h-px-40 text-center"  colspan="{$listBlocks|@count}">Phân khu</th>
								</tr>
								<tr>
									{foreach from=$listBlocks item = _oBlock key=key name=i}
										<th class="align-center h-px-40 text-center no-sticky">{$_oBlock.property_code}</th>
									{/foreach}
								</tr>
								<tr>
									{foreach from=$listBlocks item = _oBlock key=key name=i}
										<th class="text-left">
											<div class="d-flex gap-1 justify-content-center">
												<button class="btn btn-sm btn-icon btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="{$_oBlock.property_id}_{$uid}" onClick="$Core.crawl.toggleSwitch(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
												<button class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="{$_oBlock.property_id}_{$uid}" onClick="$Core.crawl.toggleSwitch(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>
											</div>
										</th>
									{/foreach}
								</tr>
							</thead>
						{else}
							<thead class="position-sticky top-0 zindex-5" style="background: #f5f7f8 !important;">
								<tr>
									{if $deviceType ne 'phone'}
										<th class="align-center h-px-40 zindex-3" width="3%" rowspan="3">No.</th>
									{/if}
									<th class="align-center h-px-40 zindex-3" rowspan="3" width="15%">Đại lý</th>
									<th class="align-center h-px-40 text-center"  colspan="{$lstProjects|@count}">Dự án</th>
								</tr>
								<tr>
									{foreach from=$lstProjects item = _oProject key=key name=i}
										<th class="align-center h-px-40 text-center no-sticky">{$_oProject.code}</th>
									{/foreach}
								</tr>
								<tr>
									{foreach from=$lstProjects item = _oProject key=key name=i}
										<th class="text-left">
											<div class="d-flex gap-1 justify-content-center">
												<button class="btn btn-sm btn-icon btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="{$_oProject.project_id}_{$uid}" onClick="$Core.crawl.toggleSwitch(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
												<button class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="{$_oProject.project_id}_{$uid}" onClick="$Core.crawl.toggleSwitch(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>
											</div>
										</th>
									{/foreach}
								</tr>
							</thead>
						{/if}
						<tbody class="table-border-bottom-0">
						{if !empty($list_agency) }
							{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
								{foreach from=$list_agency item=_oItem name=i }
									<tr class="tr_agency tr_agency_{$_oItem.property_id}" >
										{if $deviceType ne 'phone'}
										<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
										{/if}
										<td class="text-nowrap" data-label="Tiêu đề" width="100px">
											<div class="d-flex align-items-center justify-content-between gap-1">{$_oItem.title}</td>
										{foreach from=$listBlocks item=_oBlock key=key}
											{assign var=gId value=$clsISO->getUniqid()}
											<td class="text-center" >
												<label class="switch">
													<input type="checkbox" value="{$_oBlock.property_id}" class="switch_{$_oBlock.property_id}_{$uid}" name="block_not_upd_{$_oItem.property_id}[]" {} {if $clsISO->checkItemInArray($_oBlock.property_id,$_oItem.arr_block_not_upd)}checked{/if} >
													<span class="slider round"></span>
												</label>
											</td>
										{/foreach}
									</tr>
								{/foreach}
							{else}
								{foreach from=$list_agency item=_oItem name=i }
									<tr class="tr_agency tr_agency_{$_oItem.property_id}" >
										{if $deviceType ne 'phone'}
										<td class="align-center text-center">{$smarty.foreach.i.iteration}</td>
										{/if}
										<td class="text-nowrap" data-label="Tiêu đề" width="100px">
											<div class="d-flex align-items-center justify-content-between gap-1">{$_oItem.title}</td>
										{foreach from=$lstProjects item=_oProject key=key}
											{assign var=gId value=$clsISO->getUniqid()}
											<td class="text-center" >
												<label class="switch">
													<input type="checkbox" value="{$_oProject.project_id}" class="switch_{$_oProject.project_id}_{$uid}" name="project_not_upd_{$_oItem.property_id}[]" {} {if $clsISO->checkItemInArray($_oProject.project_id,$_oItem.arr_project_not_upd)}checked{/if} >
													<span class="slider round"></span>
												</label>
											</td>
										{/foreach}
									</tr>
								{/foreach}
							{/if}
						{else}
							<tr>
								<td class="text-center" colspan="{if $deviceType eq 'phone'}4{else}5{/if}">
									Danh sách trống !
								</td>
							</tr>
						{/if}
						</tbody>
					</table>				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
					<button type="button" onclick="$Core.crawl.save_config_update_stock(this,event)" stock_type="{$stock_type}" class="btn btn-primary">Lưu lại</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>