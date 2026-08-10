<div class="modal-dialog modal-standard modal-ipad">
	<form action="" method="post" class="modal-content" id="frmIssue" encrupt="miltipart/form-data">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$oneItem.title}</strong></h3>
		</div>
		<div class="modal-body">
		{if !empty($lst_block)}
			{foreach name=k from=$lst_block key = _oK item = block_name}
				{assign var = gId value = $uid|cat:"_"|cat:$_oK}
				<div class="form-group group_price_sheets">
					<label class="col-form-label required">Dự án {$block_name}</label>
					<input type="hidden" name="block_crawl[{$_oK}][is_crawl]" value="{$block_crawl[$_oK].is_crawl}">
					<div class="form-row">
						<div class="col-md-4">
							<input type="text" class="form-control spreadsheetId_{$gId}" onClick="this.select();" 
							name="block_crawl[{$_oK}][sheetID]" block_id="{$_oK}" value="{$block_crawl[$_oK].sheetID}" placeholder="Spreadsheet ID" />
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<input type="hidden" gId="{$gId}" name="block_crawl[{$_oK}][sheet_id]" value="{$block_crawl[$_oK].sheet_id}" class="sheet_id_{$gId}" />
								<input type="text" gId="{$gId}" class="form-control sheet_name sheet_name_{$gId}" onClick="this.select();" name="block_crawl[{$_oK}][sheet_name]" value="{$block_crawl[$_oK].sheet_name}" placeholder="SHEET_1|SHEET_2" sheet_name="{$block_crawl[$_oK].sheet_name}" readonly>
								<input type="hidden" gId="is_stock_point_{$gId}" name="block_crawl[{$_oK}][is_stock_point]" value="{$block_crawl[$_oK].is_stock_point}" class="is_stock_point_{$gId}" />
								<div class="input-group-btn">
									<button type="button" onClick="$Core.crawl.open_sheet(this, event)" 
										gId="{$gId}" uid="{$uid}" stock_type="{$stock_type}" class="btn btn-default" title="Chọn sheet">
										<i class="fa fa-cog"></i> Chọn sheet
									</button>
								</div>
							</div>
						</div>
						<div class="col-md-1">
							<label class="switch">
								<input type="checkbox" onchange="$Core.crawl.handle_status(this, event)" stock_type="{$stock_type}" project_id="" block_id="{$_oK}" agency_id="{$agency_id}" value="1" class="switch_68513c113edc9554719929" name="block_crawl[{$_oK}][is_crawl]" {if !empty($block_crawl[$_oK].is_crawl)}checked{/if}>
								<span class="slider round"></span>
							</label>
						</div>
						<div class="col-md-1">
							<button class="btn btn-outline-default btn-icon" type="button" gId="{$gId}" uid="{$uid}" onclick="$Core.crawl.open_config_column(this,event)" stock_type="{$stock_type}" agency_id="{$agency_id}" target_id="{$_oK}">
								<i class="fa fa-cogs" aria-hidden="true"></i>
							</button>
						</div>
					</div>
					{if !empty($block_crawl[$_oK].is_stock_point)}						
						{assign var=arr_sheet_name value=$block_crawl[$_oK].arr_sheet_name}
						{assign var=arr_sheet_id value=$block_crawl[$_oK].arr_sheet_id}
						{assign var=arr_stock_point value=$block_crawl[$_oK].arr_stock_point}
						{assign var=lst_building value=$block_crawl[$_oK].lstBuilding}
						{if !empty($arr_sheet_name)}
							<div class="list_sheet_config d-flex gap-2 flex-wrap">
								{foreach from=$arr_sheet_name item=sheet_name key=key_id}
									{if !empty($arr_stock_point[$key_id])}
										<div class="d-flex flex-wrap justify-content-between gap-2 p-2 border mt-2">
											<div class="w-100 bold text-info">{$sheet_name}</div>
											<div class="sheet_configs form-group form-row"  sheet_name="{$sheet_name}">
												{foreach from=$lst_building item=_oBuilding}
													<div class="col-12 col-md-4 mb-2">
														<div class="checkbox">
															<input type="checkbox" class="checkitem" gId="{$gId}" name="block_crawl[{$_oK}][{$sheet_name}][building_ids][]" data-name="building_ids[{$sheet_name}]" value="{$_oBuilding.property_id}" id="building_{$_oBuilding.property_id}_{$key_id}" {if $clsISO->checkItemInArray($_oBuilding.property_id,$block_crawl[$_oK][{$sheet_name}].building_ids)}checked{/if} >
															<label for="building_{$_oBuilding.property_id}_{$key_id}">{$_oBuilding.title}</label>
														</div>
													</div>
												{/foreach}
											</div>
										</div>
									{/if}
								{/foreach}
							</div>	
						{/if}
					{/if}
				</div>
			{/foreach}
		{/if}
		{if !empty($lst_project)}
			{foreach name=k from=$lst_project key = _oK item = project_name}
				{assign var = gId value = $uid|cat:"_"|cat:$_oK}
				{assign var=arr_sheet_name value=$crawl_lowfloor[$_oK].arr_sheet_name}
				{assign var=arr_sheet_id value=$crawl_lowfloor[$_oK].arr_sheet_id}
				<div class="form-group group_price_sheets">
					<label class="col-form-label required">Dự án {$project_name}</label>
					<input type="hidden" name="crawl_lowfloor[{$_oK}][is_crawl]" value="{$crawl_lowfloor[$_oK].is_crawl}">
					<div class="form-row mb-2">
						<div class="col-md-4">
							<input type="text" class="form-control spreadsheetId_{$gId}" onClick="this.select();" 
							name="crawl_lowfloor[{$_oK}][sheetID]" block_id="{$_oK}" value="{$crawl_lowfloor[$_oK].sheetID}" placeholder="Spreadsheet ID" />
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<input type="hidden" gId="{$gId}" name="crawl_lowfloor[{$_oK}][sheet_id]" value="{$crawl_lowfloor[$_oK].sheet_id}" class="sheet_id_{$gId}" />
								<input type="text" gId="{$gId}" class="form-control sheet_name sheet_name_{$gId}" onClick="this.select();" name="crawl_lowfloor[{$_oK}][sheet_name]" value="{$crawl_lowfloor[$_oK].sheet_name}" placeholder="SHEET_1|SHEET_2" sheet_name="{$crawl_lowfloor[$_oK].sheet_name}" readonly>
								<div class="input-group-btn">
									<button type="button" onClick="$Core.crawl.open_sheet(this, event)" 
										gId="{$gId}" uid="{$uid}" stock_type="{$stock_type}" class="btn btn-default" title="Chọn sheet">
										<i class="fa fa-cog"></i> Chọn sheet
									</button>
								</div>
							</div>
						</div>
						<div class="col-md-1">
							<label class="switch">
								<input type="checkbox" onchange="$Core.crawl.handle_status(this, event)" stock_type="{$stock_type}" project_id="{$_oK}" block_id="" agency_id="{$agency_id}" value="1" class="switch_68513c113edc9554719929" name="crawl_lowfloor[{$_oK}][is_crawl]" {if !empty($crawl_lowfloor[$_oK].is_crawl)}checked{/if}>
								<span class="slider round"></span>
							</label>
						</div>
						<div class="col-md-1 text-right">
							<button class="btn btn-outline-default btn-icon" type="button" gId="{$gId}" uid="{$uid}" onclick="$Core.crawl.open_config_column(this,event)" stock_type="{$stock_type}" agency_id="{$agency_id}" target_id="{$_oK}">
								<i class="fa fa-cogs" aria-hidden="true"></i>
							</button>
						</div>
					</div>
					<div class="list_sheet_config">
						{if !empty($arr_sheet_name)}
							{foreach from=$arr_sheet_name item=sheet_name key=key_id}
								<div class="d-flex flex-wrap justify-content-between gap-2 p-2 border mb-2">
									<div class="w-100 bold text-info">{$sheet_name}</div>
									<div class="form-group">
										<label class="col-form-label required">Màu đã bán</label>
										<div class="d-flex gap-1 lst_color">
											{if !empty($crawl_lowfloor[$_oK][$sheet_name].color_sold)}
												{foreach from=$crawl_lowfloor[$_oK][$sheet_name].color_sold item=color}
													<div class="w-35px">
														<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][{$sheet_name}][color_sold][]" value="{$color}" placeholder="#ff0000" maxlength="255">
													</div>
												{/foreach}
											{else}
												<div class="w-35px">
													<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][{$sheet_name}][color_sold][]" value="{$crawl_lowfloor[$_oK][$sheet_name].color_sold}" placeholder="#ff0000" maxlength="255">
												</div>
											{/if}

											<div class="input-group">
												<button class="btn btn-outline-default btn-icon" type="button" onclick="$Core.crawl.addColor(this,event)" _with="lowfloor" stock_type="{$stock_type}" agency_id="{$agency_id}" sheet_name="{$sheet_name}" target_id="{$_oK}"  _type="color_sold"><i class="fa fa-plus" aria-hidden="true"></i></button>
												<button class="btn btn-outline-default btn-icon btn-danger btn-minus {if $crawl_lowfloor[$_oK][$sheet_name].color_sold|@count eq 1 || empty($crawl_lowfloor[$_oK][$sheet_name].color_sold)}d-none{/if}" type="button" onclick="$Core.crawl.removeColor(this,event)" _with="lowfloor" stock_type="{$stock_type}" agency_id="{$agency_id}" target_id="{$_oK}"  _type="color_sold"><i class="fa fa-minus" aria-hidden="true"></i></button>
											</div>
										</div>
									</div>
									<div class="form-group">
										{assign var=gid value=$clsISO->getUniqid()}
										<div class="checkbox-inline my-2">
											<input type="checkbox" class="checkitem stock_item" name="crawl_lowfloor[{$_oK}][{$sheet_name}][is_color_dq]" value="1" id="{$gid}" {if !empty($crawl_lowfloor[{$_oK}][{$sheet_name}].is_color_dq)}checked{/if}>
											<label for="{$gid}">Màu độc quyền</label>
										</div>
										<div class="d-flex gap-1 lst_color">
											{if !empty($crawl_lowfloor[$_oK][$sheet_name].color_dq)}
												{foreach from=$crawl_lowfloor[$_oK][$sheet_name].color_dq item=color}
													<div class="w-35px">
														<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][{$sheet_name}][color_dq][]" value="{$color}" placeholder="#ff0000" maxlength="255">
													</div>
												{/foreach}
											{else}
												<div class="w-35px">
													<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][{$sheet_name}][color_dq][]" value="" placeholder="#ff0000" maxlength="255">
												</div>
											{/if}
											<div class="input-group">
												<button class="btn btn-outline-default btn-icon" type="button" onclick="$Core.crawl.addColor(this,event)" _with="lowfloor" stock_type="{$stock_type}" agency_id="{$agency_id}" sheet_name="{$sheet_name}" target_id="{$_oK}"  _type="color_dq"><i class="fa fa-plus" aria-hidden="true"></i></button>
												<button class="btn btn-outline-default btn-icon btn-danger btn-minus {if $crawl_lowfloor[$_oK][$sheet_name].color_dq|@count eq 1 || empty($crawl_lowfloor[$_oK][$sheet_name].color_dq)}d-none{/if}" type="button" onclick="$Core.crawl.removeColor(this,event)" _with="lowfloor" stock_type="{$stock_type}" agency_id="{$agency_id}" target_id="{$_oK}"  _type="color_dq"><i class="fa fa-minus" aria-hidden="true"></i></button>
											</div>
										</div>
									</div>
									<div class="form-group">
										{assign var=gid value=$clsISO->getUniqid()}
										<div class="checkbox-inline my-2">
											<input type="checkbox" class="checkitem stock_item" name="crawl_lowfloor[{$_oK}][{$sheet_name}][is_color_break]" value="1" id="{$gid}" {if !empty($crawl_lowfloor[{$_oK}][{$sheet_name}].is_color_break)}checked{/if} >
											<label for="{$gid}">Màu ngắt dòng</label>
										</div>
										<div class="d-flex gap-1 lst_color">
											{if !empty($crawl_lowfloor[$_oK][{$sheet_name}].color_break)}
												{foreach from=$crawl_lowfloor[$_oK][{$sheet_name}].color_break item=color}
													<div class="w-35px">
														<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][{$sheet_name}][color_break][]" value="{$color}" placeholder="#ff0000" maxlength="255">
													</div>
												{/foreach}
											{else}
												<div class="w-35px">
													<input type="color" class="form-control color_sold px-0" onClick="this.select();" name="crawl_lowfloor[{$_oK}][{$sheet_name}][color_break][]" value="" placeholder="#ff0000" maxlength="255">
												</div>
											{/if}
											<div class="input-group">
												<button class="btn btn-outline-default btn-icon" type="button" onclick="$Core.crawl.addColor(this,event)" _with="lowfloor" stock_type="{$stock_type}" agency_id="{$agency_id}" sheet_name="{$sheet_name}" target_id="{$_oK}"  _type="color_break"><i class="fa fa-plus" aria-hidden="true"></i></button>
												<button class="btn btn-outline-default btn-icon btn-danger btn-minus {if $crawl_lowfloor[$_oK][$sheet_name].color_break|@count eq 1 || empty($crawl_lowfloor[$_oK][$sheet_name].color_break)}d-none{/if}" type="button" onclick="$Core.crawl.removeColor(this,event)" _with="lowfloor" stock_type="{$stock_type}" agency_id="{$agency_id}" target_id="{$_oK}"  _type="color_break"><i class="fa fa-minus" aria-hidden="true"></i></button>
											</div>
										</div>
									</div>
								</div>	
							{/foreach}
						{/if}
					</div>					
					<hr>
				</div>
			{/foreach}
		{/if}				
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success" onClick="$Core.crawl.save_agency(this,event)" 
				stock_type="{$stock_type}" toId="{$toId}" agency_id="{$agency_id}">Lưu lại</button>
		</div>
	</form>
</div>