<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		<div class="col-12 col-lg-9 col-xxxl-7 mx-auto">
			<div class="card">
				<div class="card-header">
					<div class="d-flex  align-items-center justify-content-between">
						<div class="d-flex flex-column">
							<h5 class="chat-title mb-0">Chương trình thi đua</h5>
							<span class="text-muted fs-11">Tổng <strong class="text-main">{$total_record}</strong> chương trình</span>
						</div>
						<button onClick="$Core.incentive.open(this, event)" table_id="0" type="button" class="btn btn-outline-default {if $deviceType eq 'phone'}btn-sm{/if}">
							<i class="bx bx-plus"></i> Thêm
						</button>
					</div>
				</div>
				<div class="card-body">
					<div class="table-container no-shadow overflow-x-auto mb-3">
						<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped dragable table-bordered">
							<thead><tr>
								<!--<th class="align-center h-px-35 bg-lighter">Type</th> -->
								{if $deviceType ne 'phone'}
								<th class="align-center h-px-35 bg-lighter" width="40px">STT</th>
								{/if}
								<th class="align-center h-px-35 bg-lighter" >Tiêu đề</th>
								<th class="align-center h-px-35 bg-lighter text-center">Thời gian</th>
								<th class="align-center h-px-35 bg-lighter text-center">Hiển thị</th>
								<th class="align-center h-px-35 bg-lighter" width="40px"></th>
							</tr></thead>
							<tbody class="holder_chatlogs">
								{if !empty($lstItem)}
									{foreach from=$lstItem item=_oItem key=key name=i}
										<tr class="tr">
											{if $deviceType ne 'phone'}
											<td class="text-center" width="40">{$smarty.foreach.i.iteration}</td>
											{/if}
											<td class="text-left">{$_oItem.title}</td>
											<td class="text-nowrap text-center">{$clsISO->formatDate($_oItem.start_time,4)} - {$clsISO->formatDate($_oItem.end_time,4)}</td>
											<td class="text-left">
												<label class="switch">
													<input type="checkbox" {if !empty($_oItem.is_active)}checked{/if} onchange="$Core.incentive.set_status(this, event)" table_id="{$_oItem.id}" value="1">
													<span class="slider round"></span>
												</label>
											</td>
											<td class="text-nowrap text-center">
												<div class="btn-group ">
													<a href="javascript:void(0);" title="Sửa" class="btn btn-icon btn-sm btn-outline-default" onclick="$Core.incentive.open(this,event)" table_id="{$_oItem.$pkeyTable}"><i class="bx bx-edit-alt"></i></a>
													<a href="javascript:void(0);" title="Xóa" class="btn btn-icon btn-sm btn-outline-default" onclick="$Core.incentive.delete(this,event)" table_id="{$_oItem.$pkeyTable}"><i class="bx bx-trash"></i></a>
												</div>
											</td>
										</tr>
									{/foreach} 
								{else}
									<tr class="tr">
										<td colspan="4" class="text-center">Danh sách trống</td>
									</tr>
								{/if}
							</tbody>
						</table>
					</div>
						{if !empty($html_pager)}
							<div class="pagination justify-content-center">{$html_pager}</div>
						{/if}
				</div>
			</div>
		</div>
	</div>
</div>