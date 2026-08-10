<div class="modal-dialog modal-dialog-centered modal-fullscreen">
	<form method="POST" class="modal-content w-100 h-100">
		<div class="modal-header">
			<h3 class="modal-title">Cài đặt cột dữ liệu</h3>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="text-nowrap overflow-auto mb-0" style="height:calc(100vh - 150px)">					
				<table cellspacing="0" cellpadding="0" class="table no-width table-bordered table-striped">	
					<thead style="position:sticky; top:-1px; background:#FFF">
						<tr>
							{section loop=$highestColumnIndex start=0 step=1 name=i}													
							{assign var=col value=$smarty.section.i.index}
							<th class="bg-white" style="min-width:125px" width="{$widthColumn}%">
								<select name="columns[{$col}]" data-width="100%" class="form-control form-select stock_import_field">
									<option value="">Lựa chọn</option>
									{foreach from=$data_select item=_title key=key name=i}
									<option value="{$key}" {$select_default[$col]} {if $select_default[$col] eq $key}selected{/if}>{$_title}</option>
									{/foreach}
								</select>
							</th>
							{/section}
						</tr>
					</thead>
					<tbody>
						{foreach from=$tblData item=rowData name=i_row}
						<tr>
							{section loop=$highestColumnIndex start=0 step=1 name=index}
							<td class="text-left h-px-30">{$rowData[index]}</td>
							{/section}
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>			
		</div>
		<div class="modal-footer">					
			<div class="p__right d-flex justify-content-end">
				<button type="button" class="btn btn-primary" uid="{$uid}" onClick="$Core.marketing.save_config(this, event)">
					<i class="bx bx-chevron-right"></i>
					<span>Tiếp tục</span>
				</button>
			</div>
		</div>
	</form>
</div>