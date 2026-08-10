<div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
	<form class="modal-content" method="POST">
		<div class="modal-header">
			<h3 class="modal-title"><strong>Cấu hình cột</strong></h3>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<ul class="nav nav-tabs mb-0">
				{foreach from=$arr_data item=item key=key name=i}
				<li class="nav-item{if $smarty.foreach.i.first} active{/if}">
					<a href="#tabcontent_{$smarty.foreach.i.iteration}" title="{$key}" 
						class="nav-link" data-toggle="tab">{$key}</a>
				</li>
				{/foreach}
			</ul>
			<div class="tab-content p-0">
				{foreach from=$arr_data item=lstData key=key name=i}
					{assign var=number_column value=$number_column_sheet[$key]}
					{assign var=columns_sheet value=$column_data[$key]}
					{assign var=row_check value=$number_check[$key]}
					<div id="tabcontent_{$smarty.foreach.i.iteration}" class="tab-pane fade{if $smarty.foreach.i.first} show in active{/if}">
						<div class="table-container no-shadow overflow-auto h-max-350px w-100">
							<table class="table table-bordered dragable installed">	
								<tbody>
									{foreach from=$lstData item=rowData name=i_row}
										{assign var=gid value=$clsISO->getUniqid()}
										<tr>
											<td width="40">
												<div class="checkbox">
													<input type="radio" class="form-check-input" name="number_check[{$key}]" value="{$smarty.foreach.i_row.index}" id="number_check_{$gid}" {if isset($number_check[{$key}]) && $number_check[{$key}] eq $smarty.foreach.i_row.index} checked{/if}>
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
				{/foreach}	
			</div>				
		</div>
		<div class="modal-footer">				
			<div class="p__right d-flex justify-content-end">
				<button type="button" class="btn btn-success" gid="{$uid}" onClick="$Core.crm.do_config_column(this, event)" sheet_name="{$key}">
					<span>Cập nhật</span>
				</button>
			</div>
		</div>
	</form>
</div>