<div class="modal-dialog modal-fullscreen">
	<div class="modal-content">
		<div class="modal-header"> 
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="position: absolute;right: 30px;top: 30px"></button>
			<h3 class="modal-title"><strong>Import bảng hàng Cao tầng </strong></h3>
		</div>
		<form method="POST">
			<div class="modal-body">
				<div class="d-flex flex-wrap align-items-center mb-3 p-3" style="border: 2px dashed #ff1c00">
					<label class="text-muted text-nowrap mr-2">Phân khu:</label>
					<div class="d-flex flex-wrap align-items-start">
						{if !empty($lstBlock)}
							{foreach from=$lstBlock key=block_id item=block_name}
								<div class="radius-half p-2 px-3 mr-2">
									<div class="checkbox">
										<input class="form-check-input me-2" type="checkbox" id="block_{$block_id}" name="block_id[]" value="{$block_id}" {if $target_id eq $block_id}checked{/if}>
										<label for="block_{$block_id}">{$block_name}</label>
									</div>
								</div>
							{/foreach}
						{else}
							<span class="text-main fw-bold">Bạn chưa được cấp quyền cập nhật bảng hàng</span>
						{/if}
					</div>
				</div>
				<div class="text-nowrap overflow-auto mb-0" style="height:calc(100vh - 300px)">					
					{if empty($type)}
						<table cellspacing="0" cellpadding="0" class="table no-width table-bordered table-striped">	
							<thead style="position:sticky; top:-1px; background:#FFF">
								<tr>
									{section loop=$highestColumnIndex start=0 step=1 name=index}													
										{assign var=col value=$smarty.section.index.index}
										<th class="bg-white" style="min-width:125px" width="{$widthColumn}%">
											<select name="columns[{$col}]" data-width="100%" class="form-control form-select stock_import_field">
												<option value="">Lựa chọn</option>
												{$clsStock->getHtmlColumnFieldCrawl($stock_type,$arr_column[$col])}
											</select>
										</th>
									{/section}
								</tr>
							</thead>
							<tbody>
								{foreach from=$tblData item=rowData name=i_row}
									<tr class=" ">
										{section loop=$highestColumnIndex start=0 step=1 name=index}
											<td class="text-left">{$rowData[index]}</td>
										{/section}
									</tr>
								{/foreach}
							</tbody>
						</table>
					{else}
						<table cellspacing="0" cellpadding="0" class="table no-width table-bordered table-striped mb-0">	
							<thead style="position:sticky; top:-1px; background:#FFF">
								<tr>
									<th class="bg-white text-center" style="min-width:50px">
										STT
									</th>
									{section loop=$highestColumnIndex start=0 step=1 name=index}													
										{assign var=col value=$smarty.section.index.index}
										<th class="bg-white" style="min-width:120px" width="120px">
											<select name="columns[{$col}]" data-width="100%" class="form-control form-select stock_import_field">
												<option value="">Lựa chọn</option>
												{$clsStock->getHtmlColumnFieldCrawl($stock_type,$arr_column[$col])}
											</select>
										</th>
									{/section}
								</tr>
							</thead>
						</table>
					{/if}
					<div id="spreadsheet_{$uid}" class="spreadsheet_hide_head"></div>
				</div>			
			</div>
			<div class="modal-footer align-items-center justify-content-between">	
				<div class="p__left d-flex gap-2">
					<div class="d-flex align-items-center">
						<label class="switch mr-2">
							<input type="checkbox" name="opt_ignore_empty" value="1" checked>
							<span class="slider round"></span>
						</label>
						<span>Bỏ qua giá trị trống</span>
					</div>
					{if !empty($type)}
						<div class="d-flex align-items-center">
							<label class="switch mr-2">
								<input type="checkbox" name="opt_update_ptg_only" value="1" />
								<span class="slider round"></span>
							</label>
							<span>Chỉ cập nhật PTG</span>
						</div>
					{/if}
				</div>
				<input type="hidden" name="type" value="{$type}">				
				<div class="p__right d-flex justify-content-end"><button type="button" class="btn btn-primary" agency_id="{$agency_id}" target_id="{$target_id}" stock_type="{$stock_type}" uid="{$uid}" onClick="$Core.crawl.do_import(this, event)" ><span>Cập nhật bảng hàng</span></button></div>
			</div>
		</form>
	</div>
</div>