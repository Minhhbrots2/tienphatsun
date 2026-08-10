<div class="modal-dialog modal-xl">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Cấu hình cột</strong></h3>
		</div>
		<form method="POST">
			<div class="modal-body">
				<ul class="nav nav-tabs">
					{foreach from=$arr_data item=item key=key name=i}
						<li class="btn {if $smarty.foreach.i.first}active{/if}" data-toggle="tab" href="#{$uid}_{$smarty.foreach.i.iteration}"><span>{$key}</span></li>
					{/foreach}
				</ul>
				<div class="tab-content">
					{foreach from=$arr_data item=lstData key=key name=i}
						{assign var=columns_sheet value=$column_data[$key]}
							<div id="{$uid}_{$smarty.foreach.i.iteration}" class="tab-pane fade {if $smarty.foreach.i.first}in active{/if}">
								<div style="overflow:auto; width:100%; max-height:500px">
									<table class="table text-nowrap table-bordered table-striped">
										<thead style="position:sticky; top:-1px; background:#FFF">
											<tr>
												{section loop=40 start=0 step=1 name=index}													
													{assign var=col value=$smarty.section.index.index}
													<th class="p-0" style="min-width:120px" width="120">
														<select name="columns[{$key}][{$col}]" class="form-control border-0 stock_import_field">
															<option value="">Lựa chọn</option>
															{$clsStock->getHtmlColumnFieldCrawl($stock_type, $columns_sheet[$col])}
														</select>
													</th>
												{/section}
											</tr>
										</thead>
										<tbody>
											{foreach from=$lstData item=rowData name=i_row}
												<tr class=" ">
													{section loop=40 start=0 step=1 name=index}
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
			<div class="modal-footer">				
				<div class="p__right d-flex justify-content-end"><button type="button" class="btn btn-success" agency_id="{$agency_id}" project_id="{$project_id}"  block_id="{$block_id}" stock_type="{$stock_type}" uid="{$uid}" onClick="$Core.property.do_config_column(this, event)" sheet_name="{$key}" ><span>Cập nhật</span></button></div>
			</div>
		</form>
	</div>
</div>