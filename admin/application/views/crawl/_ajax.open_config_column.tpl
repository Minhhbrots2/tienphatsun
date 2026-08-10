<div class="modal-dialog modal-dialog-scrollable modal-xl">
	<form class="modal-content" method="POST">
		<div class="modal-header">
			<h3 class="modal-title"><strong>Cấu hình cột</strong></h3>
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
		</div>
		<div class="modal-body">
			<ul class="nav nav-tabs mb-2">
				{foreach from=$arr_data item=item key=key name=i}
				<li class="nav-item{if $smarty.foreach.i.first} active{/if}">
					<a href="#tabcontent_{$smarty.foreach.i.iteration}" title="{$key}" 
						class="nav-link" data-toggle="tab">{$key}</a>
				</li>
				{/foreach}
			</ul>
			<div class="tab-content">
				{foreach from=$arr_data item=lstData key=key name=i}
					{assign var=number_column value=$number_column_sheet[$key]}
					{assign var=columns_sheet value=$column_data[$key]}
					{assign var=row_check value=$number_check[$key]}

					{if !empty($arr_stock_points[$key])}
						<div id="tabcontent_{$smarty.foreach.i.iteration}" class="tab-pane fade{if $smarty.foreach.i.first} in active{/if}">
							<ul class="nav nav-tabs mb-2">
								{foreach from=$arr_sheet_building[$key] item=building_name name=i_b}
								<li class="nav-item{if $smarty.foreach.i_b.first} active{/if}">
									<a href="#tabcontent_building_{$smarty.foreach.i_b.iteration}" title="{$building_name}" 
										class="nav-link" data-toggle="tab">Tòa {$building_name}</a>
								</li>
								{/foreach}
							</ul>
							<div class="tab-content">
								{foreach from=$arr_sheet_building[$key] item=building_name key=building_id name=i_b}
									{assign var=gid value=$clsISO->getUniqid()}
									<div id="tabcontent_building_{$smarty.foreach.i_b.iteration}" class="tab-pane fade{if $smarty.foreach.i_b.first} in active{/if}">
										<div class="overflow-auto h-max-350px w-100">
											<table class="table text-nowrap table-bordered table-striped">
												<thead>
													<tr>
														<th></th>
														<th>Chọn cột tầng</th>
														{section loop=$number_column start= 0 step=1 name= i_col}
														{assign var=col value=$smarty.section.i_col.index}
														<th class="p-0 sticky bg-white" style="min-width:120px;vertical-align: middle" width="120px">
															<div class="checkbox">
																<input type="radio" class="checkitem" name="floor_index[{$key}][{$building_id}]" value="{$col}" id="number_col_index_{$gid}_{$col}" {if isset($code_floor_ind[{$target_id}][{$key}][{$building_id}].floor_index) && $code_floor_ind[{$target_id}][{$key}][{$building_id}].floor_index eq $col} checked{/if}>
																<label for="number_col_index_{$gid}_{$col}"></label>
															</div>
														</th>
														{/section}
													</tr>														
													<tr>
														<th>
															Chọn dòng <br> trục căn
														</th>
														{section loop=$number_column start= 0 step=1 name= index}
														<th class="text-left"></th>
														{/section}
													</tr>
												</thead>
												<tbody>
													{foreach from=$lstData item=rowData name=i_row}
														{assign var=row value=$smarty.foreach.i_row.index}
														<tr>
															<td>
																<div class="checkbox">
																	<input type="radio" class="checkitem" name="arr_code_index[{$key}][{$building_id}]" value="{$row}" id="number_check_{$gid}_{$smarty.foreach.i_row.iteration}" {if isset($code_floor_ind[{$target_id}][{$key}][{$building_id}].code_index) && $code_floor_ind[{$target_id}][{$key}][{$building_id}].code_index eq $row} checked{/if}>
																	<label for="number_check_{$gid}_{$smarty.foreach.i_row.iteration}"></label>
																</div>
															</td>
															<td>
															</td>
															{section loop=$number_column start= 0 step=1 name= index}
															<td class="text-left">{$rowData[index]}</td>
															{/section}
														</tr>
													{/foreach}
												</tbody>	
											</table>
										</div>
									</div>
								{/foreach}
							</div>
						</div>
					{else}
						<div id="tabcontent_{$smarty.foreach.i.iteration}" class="tab-pane fade{if $smarty.foreach.i.first} in active{/if}">
							<div class="overflow-auto h-max-350px w-100">
								<table class="table text-nowrap table-bordered table-striped">
									<thead>
										<tr>
											<th></th>
											{section loop=$number_column start= 0 step=1 name= index}
											{assign var=col value=$smarty.section.index.index}
											<th class="p-0 sticky bg-white" style="min-width:120px" width="120px">
												<select name="columns[{$key}][{$col}]" class="form-control border-0 radius-0 stock_import_field">
													<option value="">Lựa chọn</option>
													{$clsStock->getHtmlColumnFieldCrawl($stock_type, $columns_sheet[$col])}
												</select>
											</th>
											{/section}
										</tr>
									</thead>
									<tbody>
										{foreach from=$lstData item=rowData name=i_row}
											{assign var=gid value=$clsISO->getUniqid()}
											<tr>
												<td>
													<div class="checkbox">
														<input type="radio" class="checkitem" name="number_check[{$key}]" value="{$smarty.foreach.i_row.index}" id="number_check_{$gid}" {if isset($number_check[{$key}]) && $number_check[{$key}] eq $smarty.foreach.i_row.index} checked{/if}>
														<label for="number_check_{$gid}"></label>
													</div>
												</td>
												{section loop=$number_column start= 0 step=1 name= index}
												<td class="text-left">{$rowData[index]}</td>
												{/section}
											</tr>
										{/foreach}
									</tbody>	
								</table>
							</div>
						</div>
					{/if}
				{/foreach}	
			</div>
		</div>
		<div class="modal-footer">				
			<div class="p__right d-flex justify-content-end">
				<button type="button" class="btn btn-success" agency_id="{$agency_id}" gid="{$uid}" target_id="{$target_id}" 
					stock_type="{$stock_type}" onClick="$Core.crawl.do_config_column(this, event)" sheet_name="{$key}">
					<span>Cập nhật</span>
				</button>
			</div>
		</div>
	</form>
</div>