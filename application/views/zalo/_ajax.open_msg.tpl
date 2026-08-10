<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-standard">
	<form enctype="multipart/form-data" method="post" class="d-none">
		<input type="file" multiple="multiple" class="select_file_{$uid}" 
			onchange="$Core.zalo.do_upload(this, event)" uid="{$uid}" name="images[]" />
	</form>
	<form method="POST" action="#" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_edit'}Sửa{else}Thêm{/if} nội dung gửi</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row">
				<div class="col-12 col-md-7 mb-3 mb-lg-0">
					<div class="leftCol">
						<div class="form-group mb-2">
							<div class="btn-group w-100" role="group" aria-label="Hiển thị">
								{foreach from=$arr_types key =_oKey item = _oText}
								<input type="radio" class="btn-check" name="send_type" uid="{$uid}" id="{$_oKey}_{$uid}"{if $oneMsg.send_type eq $_oKey} checked{/if} 
									value="{$_oKey}" onChange="$Core.zalo.load_send_recipients(this, event)" msg_id="{$msg_id}">
								<label data-toggle="ripple" title="{$_oText}" for="{$_oKey}_{$uid}" class="btn btn-outline-default" >{$_oText}</label>
								{/foreach}	
							</div>
						</div>
						<div class="form-group mb-2">
							<label class="form-label mb-1">Tiêu đề</label>
							<input class="form-control required" maxlength="255" name="title" placeholder="Nhập tiêu đề" value="{if $action eq '_edit'}{$oneMsg.title}{/if}" />
						</div>
						<div class="form-group mb-3">
							<label class="form-label mb-1">Nội dung</label>
							<textarea class="form-control" name="message" placeholder="" rows="8">{$more_information.message}</textarea>
						</div>
						<div class="form-group mb-2">
							<label class="form-label mb-1">Hình ảnh</label>
							<div class="we-filedrop-wrapper cursor-pointer">
								<div class="we-filedrop" > 
									<a href="javascript:void(0);" class="d-block mb-2 text-muted" uid="{$uid}" 
										onclick="$Core.zalo.select_file(this, event);">
										{$smarty.const.ICON_UPLOAD} 
										<p class="mb-0">Bấm để chọn một hoặc nhiều hình ảnh cần tải lên !!!</p>
									</a>
									<div id="imageList" class="imageList imageList_{$uid} gap-2 d-grid">
										{foreach from=$arr_images item = _oImage}
										<div class="item bg-lightest">
											<img src="{$_oImage}" />
											<input type="hidden" name="images[]" value="{$_oImage}" />
											<a href="javascript:void(0);" class="delete" src="{$_oImage}" title="Xóa" onClick="$Core.zalo.delete_image(this, event)"><i class="bx bx-x"></i></a> 
										</div>
										{/foreach}
									</div>
								</div>
							</div>
						</div>
						<div class="bg-lighter rounded-2 p-3 mb-2">
							<p class="fw-bold">Cấu hình gửi</p>
							{assign var = gId value = $clsISO->getUniqid()}
							<div class="form-check mb-2">
								<input class="form-check-input" id="{$gId}_now" gId="{$gId}" type="radio" name="schedule_type" value="_now" 
									onchange="$Core.zalo.sw_status_repeat(this, event)"{if $more_information.schedule_type eq '_now'} checked="checked"{/if} />
								<label class="form-check-label cursor-pointer" for="{$gId}_now">Gửi một lần</label>
							</div>
							<div class="form-check mb-2">
								<input class="form-check-input" onchange="$Core.zalo.sw_status_repeat(this, event)" id="{$gId}_repeat" type="radio" 
									name="schedule_type" value="_repeat" gId="{$gId}"{if $more_information.schedule_type eq '_repeat'} checked="checked"{/if} />
								<label class="form-check-label cursor-pointer" for="{$gId}_repeat">Gửi hàng ngày</label>
							</div>
							<div class="{$gId} d-flex ps-4 gap-2 xs:flex-wrap align-items-center form-group{if $more_information.schedule_type eq '_repeat'}{else} d-none{/if}">
								<div class="w-px-125 xs:flex-fill">
									<label class="form-label mb-1">Từ</label>
									<input type="time" class="form-control" name="time_start" value="{$more_information.time_start}" />
								</div>
								<div class="w-px-125 xs:flex-fill">
									<label class="form-label mb-1">Tới</label>
									<input type="time" class="form-control" name="time_end" value="{$more_information.time_end}" />
								</div>
								<div class="w-px-75 xs:flex-fill">
									<label class="form-label mb-1">sau</label>
									<input type="text" class="form-control numberonly" onClick="this.select()" name="repeat_interval" 
										value="{$more_information.repeat_interval}" />
								</div>
								<div class="w-px-75 xs:flex-fill">
									<label class="form-label mb-1">Đơn vị</label>
									<select class="form-select" name="repeat_unit">
										{foreach from=$arr_timers key = _oKey item = _oValue}
										<option{if $more_information.repeat_unit eq $_oKey} selected="selected"{/if} value="{$_oKey}">{$_oValue}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>	
						<div class="form-group">
							<label class="form-label mb-1">Người quản lý</label>
							<select class="iso-selectizeImageSearch form-field w-100" placeholder="Thêm người quản lý" multiple="multiple" name="manager_ids[]" 
							 data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" data-optgroup="false">
							{if !empty($oneMsg.manager_ids)}
								{foreach from=$oneMsg.manager_ids item = _profile_id}
								<option value="{$_profile_id}" selected>{$clsProfile->getFullName($_profile_id)}</option>
								{/foreach}
							{/if}
							 </select>
						</div>
					</div>
				</div>
				<div class="col-12 col-lg-5">
					<div class="p-3 border rounded-3 sticky top-0">
						<div class="holder_recipients_{$uid} max-height-600 rightCol webkit-scrollbar overflow-y-auto">
						{if $send_type eq 'send_customer'}
							{if !empty($list_customers)}
								{foreach from=$list_customers item = _oCustomer}
								<div class="d-flex mb-2">
									<label class="d-flex align-items-center gap-2 ant-checkbox">
										<input{if $clsISO->checkItemInArray($_oCustomer.customer_id, $arr_customers)} checked="checked"{/if} 
											name="list_customer_id[]" value="{$_oCustomer.customer_id}" type="checkbox" />
										<span class="text-nowrap line-clamp-1">{$_oCustomer.status_name} {$_oCustomer.name}</span>
									<label>
								</div>
								{/foreach}
							{/if}
						{else}
							{if !empty($list_groups)}
								{foreach from=$list_groups item = _oGroup}
								<div class="d-flex mb-2">
									<label class="d-flex align-items-center gap-2 ant-checkbox">
										<input{if $clsISO->checkItemInArray($_oGroup.id_group, $arr_groups)} checked="checked"{/if} 
											name="list_group_id[]" value="{$_oGroup.id_group}" type="checkbox" />
										<span class="text-nowrap line-clamp-1">{$_oGroup.type_name} {$_oGroup.name_group}</span>
									<label>
								</div>
								{/foreach}
							{/if}
						{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer border-top">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button data-toggle="ripple" type="button" msg_id="{$msg_id}" 
				onClick="$Core.zalo.save_msg(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
