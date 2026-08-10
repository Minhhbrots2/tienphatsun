<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		<div class="col-12 col-lg-9 col-xxxl-7 mx-auto">
			<div class="card">
				<div class="card-header">
					<div class="d-flex  align-items-center justify-content-between">
						<div class="d-flex flex-column">
							<h5 class="chat-title mb-0">Danh sách trích dẫn</h5>
							<span class="text-muted fs-11">Tổng <strong class="text-main">{$total_record}</strong> câu trích dẫn</span>
						</div>
						<button onClick="$Core.quote.open(this, event)" table_id="0" type="button" class="btn btn-outline-default {if $deviceType eq 'phone'}btn-sm{/if}">
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
								<th class="align-center h-px-35 bg-lighter" >Nội dung</th>
								<th class="align-center h-px-35 bg-lighter" >Tác giả</th>
								<th class="align-center h-px-35 bg-lighter" >Chia sẻ</th>
								<th class="align-center h-px-35 bg-lighter text-center">Thời gian</th>
								<th class="align-center h-px-35 bg-lighter text-center">Trạng thái</th>
								<th class="align-center h-px-35 bg-lighter" width="40px"></th>
							</tr></thead>
							<tbody class="holder_chatlogs">
								{if !empty($lstItem)}
									{foreach from=$lstItem item=_oItem key=key name=i}
										<tr class="tr">
											{if $deviceType ne 'phone'}
											<td class="text-center" width="40">{$smarty.foreach.i.iteration}</td>
											{/if}
											<td class="text-left"><div class="limit_2line">{$_oItem.content}</div></td>
											<td class="text-left">{$_oItem.author}</td>
											<td class="text-left">{$_oItem.html_share}</td>
											<td class="text-nowrap text-center">{$clsISO->formatDate($_oItem.upd_date,4)}</td>
											<td class="text-nowrap text-center">{if !empty($_oItem.has_show)} <span class="text-success">Đã xuất hiện</span>{else}<span class="text-muted">Chưa xuất hiện</span>{/if}</td>
											<td class="text-nowrap text-center">
												{if $profile_id ne $_oItem.user_id}
													<div class="btn-group">
														<span href="javascript:void(0);" title="Sửa" class="btn btn-icon btn-sm btn-outline-default" style="cursor: no-drop;opacity:0.5"><i class="bx bx-edit-alt"></i></span>
														<span href="javascript:void(0);" title="Xóa" class="btn btn-icon btn-sm btn-outline-default" style="cursor: no-drop;opacity:0.5"><i class="bx bx-trash"></i></span>
													</div>	
												{else}
													<div class="btn-group ">
														<a href="javascript:void(0);" title="Sửa" class="btn btn-icon btn-sm btn-outline-default" onclick="$Core.quote.open(this,event)" table_id="{$_oItem.id}"><i class="bx bx-edit-alt"></i></a>
														<a href="javascript:void(0);" title="Xóa" class="btn btn-icon btn-sm btn-outline-default" onclick="$Core.quote.delete(this,event)" table_id="{$_oItem.id}"><i class="bx bx-trash"></i></a>
													</div>	
												{/if}
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