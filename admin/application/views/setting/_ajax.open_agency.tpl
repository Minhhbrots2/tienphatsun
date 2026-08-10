<div class="modal-dialog modal-standard modal-md" style="max-width: 500px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$oneItem.title}</strong></h3>
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					{if !empty($lst_block)}
						{foreach name=k from=$lst_block key = _oK item = block_name}
							<div class="form-group group_price_sheets">
								<label class="col-form-label required">Dự án {$block_name}</label>
								<input type="hidden" name="block_crawl[{$_oK}][is_crawl]" value="{$block_crawl[$_oK].is_crawl}">
								<div class="form-row">
									<div class="col-md-6">
										<input type="text" class="form-control" onClick="this.select();" name="block_crawl[{$_oK}][sheetID]" value="{$block_crawl[$_oK].sheetID}" placeholder="SpreadsheetID" maxlength="255">
									</div>
									<div class="col-md-6">
										<input type="text" class="form-control" onClick="this.select();" name="block_crawl[{$_oK}][sheet_name]" value="{$block_crawl[$_oK].sheet_name}" placeholder="SHEET_1|SHEET_2" maxlength="255">
									</div>
								</div>

							</div>
						{/foreach}
					{/if}
					{if !empty($lst_project)}
						{foreach name=k from=$lst_project key = _oK item = project_name}
							<div class="form-group group_price_sheets">
								<label class="col-form-label required">Dự án {$project_name}</label>
								<input type="hidden" name="crawl_lowfloor[{$_oK}][is_crawl]" value="{$crawl_lowfloor[$_oK].is_crawl}">
								<div class="form-row">
									<div class="col-md-6">
										<input type="text" class="form-control sheetID" onClick="this.select();" name="crawl_lowfloor[{$_oK}][sheetID]" value="{$crawl_lowfloor[$_oK].sheetID}" placeholder="SpreadsheetID" maxlength="255">
									</div>
									<div class="col-md-5">
										<input type="text" class="form-control sheet_name" onClick="this.select();" name="crawl_lowfloor[{$_oK}][sheet_name]" value="{$crawl_lowfloor[$_oK].sheet_name}" placeholder="SHEET_1|SHEET_2" maxlength="255">
									</div>
									<!--<div class="col-md-2">
										<input type="color" class="form-control color_sold" onClick="this.select();" name="crawl_lowfloor[{$_oK}][color_sold]" value="{$crawl_lowfloor[$_oK].color_sold}" placeholder="#ff0000" maxlength="255">
									</div>-->
									<div class="col-md-1">
										<button class="btn btn-outline-default btn-icon" type="button" onclick="$Core.property.start_config_column(this,event)" stock_type="{$stock_type}" agency_id="{$agency_id}" project_id="{$_oK}" block_id=""><i class="fa fa-cogs" aria-hidden="true"></i></button>
									</div>
								</div>
								<div class="form-row">
									<div class="col-md-6">
										<label class="col-form-label required">Màu đã bán</label>
										<div class="form-row">
											{if !empty($crawl_lowfloor[$_oK].color_sold)}
												{foreach from=$crawl_lowfloor[$_oK].color_sold item=color}
													<div class="col-md-2">
														<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][color_sold][]" value="{$color}" placeholder="#ff0000" maxlength="255">
													</div>
												{/foreach}
											{else}
												<div class="col-md-2">
													<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][color_sold][]" value="{$crawl_lowfloor[$_oK].color_sold}" placeholder="#ff0000" maxlength="255">
												</div>
											{/if}
											
											<div class="col-md-2">
												<button class="btn btn-outline-default btn-icon" type="button" onclick="$Core.property.addColor(this,event)" stock_type="{$stock_type}" agency_id="{$agency_id}" project_id="{$_oK}" block_id="" _type="color_sold"><i class="fa fa-plus" aria-hidden="true"></i></button>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										{assign var=gid value=$clsISO->getUniqid()}
										<label class="col-form-label required">Màu độc quyền <input type="checkbox" class="form-check" onchange="$Core.property.toggle(this,event)" name="crawl_lowfloor[{$_oK}][is_color_dq]" value="1" {if !empty($crawl_lowfloor[$_oK].is_color_dq)}checked{/if} toId="{$gid}"></label>
										<div class="form-row {if empty($crawl_lowfloor[$_oK].is_color_dq)}d-none{/if}" id="{$gid}">
											{if !empty($crawl_lowfloor[$_oK].color_dq)}
												{foreach from=$crawl_lowfloor[$_oK].color_dq item=color}
													<div class="col-md-2">
														<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][color_dq][]" value="{$color}" placeholder="#ff0000" maxlength="255">
													</div>
												{/foreach}
											{else}
												<div class="col-md-2">
													<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][color_dq][]" value="" placeholder="#ff0000" maxlength="255">
												</div>
											{/if}
											<div class="col-md-2">
												<button class="btn btn-outline-default btn-icon" type="button" onclick="$Core.property.addColor(this,event)" stock_type="{$stock_type}" agency_id="{$agency_id}" project_id="{$_oK}" block_id=""  _type="color_dq"><i class="fa fa-plus" aria-hidden="true"></i></button>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										{assign var=gid value=$clsISO->getUniqid()}
										<label class="col-form-label required">Màu ngắt dòng <input type="checkbox" class="form-check" onchange="$Core.property.toggle(this,event)" name="crawl_lowfloor[{$_oK}][is_color_break]" value="1" {if !empty($crawl_lowfloor[$_oK].is_color_break)}checked{/if} toId="{$gid}"></label>
										<div class="form-row {if empty($crawl_lowfloor[$_oK].is_color_break)}d-none{/if}" id="{$gid}">
											{if !empty($crawl_lowfloor[$_oK].color_break)}
												{foreach from=$crawl_lowfloor[$_oK].color_break item=color}
													<div class="col-md-2">
														<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][color_break][]" value="{$color}" placeholder="#ff0000" maxlength="255">
													</div>
												{/foreach}
											{else}
												<div class="col-md-2">
													<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][color_break][]" value="" placeholder="#ff0000" maxlength="255">
												</div>
											{/if}
											<div class="col-md-2">
												<button class="btn btn-outline-default btn-icon" type="button" onclick="$Core.property.addColor(this,event)" stock_type="{$stock_type}" agency_id="{$agency_id}" project_id="{$_oK}" block_id=""  _type="color_break"><i class="fa fa-plus" aria-hidden="true"></i></button>
											</div>
										</div>
									</div>
								</div>	
								<hr>
							</div>
						{/foreach}
					{/if}
				</div>				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" onClick="$Core.property.save_agency_crawl(this,event)" stock_type="{$stock_type}" toId="{$toId}" _reload="{$_reload}" agency_id="{$agency_id}">
					Lưu
				</button>
			</div>
		</form>
	</div>
</div>