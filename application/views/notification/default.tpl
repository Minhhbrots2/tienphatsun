<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="form-row">
		<div class="col-12 col-lg-9 col-xxxl-7 mx-auto">
			<div class="card">
				<div class="card-header">
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex flex-column">
							<h5 class="chat-title mb-0">Thông báo</h5>
							<span class="text-muted fs-11">Tổng <strong class="text-main">{$total_record}</strong> thông báo</span>
						</div>
						<button type="button" onClick="$Core.notification.open(this, event)" notification_id="0" class="btn btn-outline-default{if $deviceType eq 'phone'} btn-sm{/if}"><i class="bx bx-plus"></i> Thêm mới</button>
					</div>
				</div>
				<div class="card-body">
					<div class="table-container no-shadow overflow-x-auto">
						<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped dragable table-bordered">
							<thead><tr>
								{if $deviceType ne 'phone'}
								<th class="align-center h-px-35 bg-lighter" width="40px">STT</th>
								{/if}
								<th class="align-center h-px-35 bg-lighter">Tiêu đề</th>
								<th class="align-center h-px-35 bg-lighter">Nội dung</th>
								<th class="align-center h-px-35 bg-lighter">Link</th>
								<th class="align-center text-center h-px-35 bg-lighter">T.trạng</th>
								<th class="align-center h-px-35 bg-lighter text-right">Thời gian</th>
								<th class="align-center h-px-35 bg-lighter" width="40px"></th>
							</tr></thead>
							<tbody class="holder_chatlogs">
								{if !empty($lstItem)}
									{foreach from=$lstItem item=_oItem key=key name=i}
									<tr class="tr">
										{if $deviceType ne 'phone'}
										<td class="text-center" width="40">{$smarty.foreach.i.iteration}</td>{/if}
										<td class="text-left">{$_oItem.title}</td>
										<td class="text-left"><div class="limit_2line">{$_oItem.content}</div></td>
										<td class="text-nowrap"><a href="{$_oItem.link}" target="_blank" class="text-link">{$_oItem.link}</a></td>
										<td class="text-nowrap text-center">
											{if $_oItem.is_send eq '1'}
											<span class="badge bg-label-success text-upper">Đã gửi</span>
											{else}
											<span class="badge bg-label-danger text-upper">Chưa gửi</span>
											{/if}
										</td>
										<td class="text-nowrap text-right">{$clsISO->formatDate($_oItem.upd_date,4)}</td>
										<td class="text-nowrap text-center">
											<div class="btn-group">
												<a href="javascript:void(0);" title="Sửa" class="btn btn-icon btn-sm btn-outline-default" onclick="$Core.notification.send(this,event)" notification_id="{$_oItem.notification_id}"><i class="bx bx-play"></i></a>
												<a href="javascript:void(0);" title="Sửa" class="btn btn-icon btn-sm btn-outline-default" onclick="$Core.notification.open(this,event)" notification_id="{$_oItem.notification_id}"><i class="bx bx-edit-alt"></i></a>
												<a href="javascript:void(0);" title="Xóa" class="btn btn-icon btn-sm btn-outline-default" onclick="$Core.notification.delete(this,event)" notification_id="{$_oItem.notification_id}"><i class="bx bx-trash"></i></a>
											</div>
										</td>
									</tr>
									{/foreach} 
								{else}
									<tr class="tr">
										{if $deviceType eq 'phone'}
										<td colspan="5" class="text-center">Danh sách trống</td>
										{else}
										<td colspan="6" class="text-center">Danh sách trống</td>
										{/if}
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