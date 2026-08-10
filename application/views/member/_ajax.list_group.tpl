<div class="modal-dialog">
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">Danh sách nhóm nhân viên</h5>	
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="table-wrapper dragscroll text-nowrap">
				<table class="table table-striped table-borderd">
					<thead><tr>
						<th class="algin-center h-px-40" width="3%">STT</th>
						<th class="algin-center h-px-40 text-left">Tên nhóm</th>
						<th class="algin-center h-px-40 text-left">Nhân viên</th>
						<th width="40px"></th>
					</tr></thead>
					<tbody class="table-border-bottom-0 lst_group_{$uid}"> 
					{if !empty($lstGroupProfile)}
						{foreach name=i from=$lstGroupProfile item=_oGroupProfile}
						{assign var = lstProfile value = $_oGroupProfile.lstProfile}
						<tr class="trUser">
							<td class="text-center">{$smarty.foreach.i.iteration}</td>
							<td class="text-left">{$_oGroupProfile.title}</td>
							<td class="text-left">
								{if !empty($_oGroupProfile.list_profile_id)}
									<div data-url="/index.php?mod={$mod}&act=load_list_profile_popover&group_id={$_oGroupProfile.group_profile_id}" data-toggle="webui-popover" data-trigger="hover" data-width="300" class="awe__post-profile d-flex">{$_oGroupProfile.total} nhân viên</div>
								{else}
									Chưa cập nhật
								{/if}
							</td>
							<td class="text-center">
								<div class="dropdown">
									<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="bx bx-dots-vertical-rounded"></i>
									</button>
									{if $_oGroupProfile.user_id eq $profile_id || $clsISO->checkSupper() || $clsISO->checkDEV() || $clsISO->checkPermission('manager_group')}
									<div class="dropdown-menu w-px-100" data-popper-placement="bottom-end">
										<a class="dropdown-item" onclick="$Core.member.add_group(this,event)" data-type="open" data-group_id="{$_oGroupProfile.group_profile_id}" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Sửa</a>
										<a class="dropdown-item" onclick="$Core.member.delete_group(this,event)" data-group_id="{$_oGroupProfile.group_profile_id}" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Xóa</a>
									</div>
									{/if}	
								</div>							
							</td>
						</tr>
						{/foreach}
					{else}
					<tr>
						<td class="text-center" colspan="4">
							<p class="text-muted">Danh sách trống</p>
						</td>
					</tr>
					{/if}
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary" type="button" onClick="$Core.member.add_group(this,event)" data-type="open" data-group_id="0">Tạo nhóm</button>
		</div>
	</form>
</div>


