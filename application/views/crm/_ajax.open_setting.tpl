<div class="modal-dialog modal-standard" style="max-width:500px">
	<div class="modal-content">
		<div class="modal-header"> 
			<h3 class="modal-title"><strong>Cấu hình</strong></h3>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label required">Cấu hình</label>
					{if !empty($crm_crawl_config)}
						{foreach from=$crm_crawl_config key=key item=crawl_config}
							{assign var=gId value=$clsISO->getUniqid()}
							<div class="form-row item_config pb-2 border-bottom mb-2">
								<div class="col-md-12 mb-2">
									<input type="text" class="form-control required spreadsheetId_{$gId}" data-label="Spreadsheet ID" onclick="this.select();" name="crawl_config[spreadsheet_id][]" value="{$crawl_config.spreadsheet_id}" placeholder="Spreadsheet ID">
								</div>
								<div class="col-md-12">
									<div class="input-group gap-1">
										<input type="hidden" gid="{$gId}" name="crawl_config[sheet_id][]" value="{$crawl_config.sheet_id}" class="sheet_id_{$gId}">
										<input type="text" gid="{$gId}" class="form-control sheet_name sheet_name_{$gId}" data-label="Sheet name" onclick="this.select();" name="crawl_config[sheet_name][]" value="{$crawl_config.sheet_name}" placeholder="SHEET_1" readonly>
										<div class="input-group-btn">
											<button type="button" onclick="$Core.crm.open_sheet(this, event)" gid="{$gId}" uid="{$gId}" stock_type="178" class="btn btn-default" title="Chọn sheet">
												<i class="fa fa-cog"></i> Chọn sheet
											</button>
											<button class="btn btn-outline-default btn-icon" type="button" gid="{$gId}" uid="{$gId}" onclick="$Core.crm.open_config_column(this,event)">
												<i class="fa fa-cogs" aria-hidden="true"></i>
											</button>
										</div>
										<div class="d-flex align-items-center justify-content-center convert_item">
											<button type="button" class="btn btn-icon btn-outline-default btn-add-convert" onclick="$Core.crm.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="add"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
											<button type="button" class="btn btn-icon btn-danger btn-delete-convert ml-1 {if $crm_crawl_config|@count eq 1}d-none{/if}" onclick="$Core.crm.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="delete"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
										</div>
									</div>
								</div>
							</div>
						{/foreach}
					{else}
						{assign var=gId value=$clsISO->getUniqid()}
						<div class="form-row item_config pb-2 border-bottom mb-2">
							<div class="col-md-12 mb-2">
								<input type="text" class="form-control required spreadsheetId_{$gId}" data-label="Spreadsheet ID" onclick="this.select();" name="crawl_config[spreadsheet_id][]" value="" placeholder="Spreadsheet ID">
							</div>
							<div class="col-md-12">
								<div class="input-group gap-1">
									<input type="hidden" gid="{$gId}" name="crawl_config[sheet_id][]" value="" class="sheet_id_{$gId}">
									<input type="text" gid="{$gId}" class="form-control sheet_name required sheet_name_{$gId}" data-label="Sheet name" onclick="this.select();" name="crawl_config[sheet_name][]" value="" placeholder="SHEET_1" readonly>
									<div class="input-group-btn">
										<button type="button" onclick="$Core.crm.open_sheet(this, event)" gid="{$gId}" uid="{$gId}" stock_type="178" class="btn btn-default" title="Chọn sheet">
											<i class="fa fa-cog"></i> Chọn sheet
										</button>
										<button class="btn btn-outline-default btn-icon" type="button" gid="{$gId}" uid="{$gId}" onclick="$Core.crm.open_config_column(this,event)">
											<i class="fa fa-cogs" aria-hidden="true"></i>
										</button>
									</div>
									<div class="d-flex align-items-center justify-content-center convert_item">
										<button type="button" class="btn btn-icon btn-outline-default btn-add-convert" onclick="$Core.crm.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="add"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
										<button type="button" class="btn btn-icon btn-danger btn-delete-convert ml-1 d-none" onclick="$Core.crm.configConvert(this, event)" style="width: 35px;height: 35px;" data-type="delete"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>
									</div>
								</div>
							</div>
						</div>
					{/if}
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" class="form-control " name="project_id" value="{$project_id}">
				<button type="button" class="btn btn-success" onClick="$Core.crm.setting_config_crawl(this, event)" data-type="_SAVE">Lưu</button>
			</div>
		</form>
	</div>
</div>