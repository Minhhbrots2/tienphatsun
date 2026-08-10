<div class="modal-dialog modal-xxl{if $deviceType eq 'phone'} modal-dialog-centered{/if}">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="bg-lighter rounded-4 p-3 mb-3">
				<div class="form-group">
					<label for="code" class="form-label mb-1">Mục tiêu của bạn</label>
					<input type="text" autocomplete="off" name="title" class="form-control form-control-lg required" 
					placeholder="Mục tiêu của bạn" value="{if $action eq '_edit'}{$oneOkrs.title}{/if}">
					<div class="form-text">Ngắn gọn và cụ thể - Không được chứa số - Truyền cảm hứng - Chỉ nên có 3 đến 5 mục tiêu)</div>
				</div>
			</div>
			<div class="form-row mb-3">
				<div class="col-12 col-md-4">
					<label for="email" class="form-label mb-1">Chu kỳ</label>
					<select class="form-control required iso-select2" onChange="$Core.okrs.set_okrs_parent(this, event)" uid="{$uid}" name="period_id" 
					data-placeholder="Chọn chu kỳ" data-width="100%">
						<option value="0">Chọn chu kỳ</option>
						{$clsProperty->getSelectByProperty('_PERIOD',$oneOkrs.period_id)}
					</select>
				</div>
				<div class="col-12 col-md-8">
					<label for="phone" class="form-label mb-1">OKR cấp trên</label>
					<select name="parent_id" class="form-control slb_okrs_parent_{$uid} iso-select2" 
					data-placeholder="Chọn OKR cấp trên" data-allow-clear="true" data-width="100%">
						{if $action eq '_edit' && !empty($oneOkrs.parent_id)}
						<option value="{$oneOkrs.parent_id}" selected="selected">{$clsOkrs->getTitle($oneOkrs.parent_id)}</option>
						{/if}
					</select>
				</div>
			</div>
			<div class="form-row mb-3">
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<label for="amout" class="form-label mb-1">Loại</label>
					<select name="type_id" uid="{$uid}" data-width="100%" data-placeholder="Loại" 
					class="form-control iso-select2 required" onChange="$Core.okrs.set_object(this,event)">
						<option{if $action eq '_edit' && $oneOkrs.type_id eq '1'} selected{/if} value="1">Cá nhân</option>
						<option{if $action eq '_edit' && $oneOkrs.type_id eq '2'} selected{/if} value="2">Phòng ban</option>
						<option{if $action eq '_edit' && $oneOkrs.type_id eq '3'} selected{/if} value="3">Công ty</option>
					</select>
				</div>
				<div class="col-12 col-md-4 mb-2 mb-lg-0">
					<div class="holder_object_{$uid}">
						{if $action eq '_edit'}
							{if $oneOkrs.type_id eq $smarty.const._TYPE_STAFF_OKRS_ID}
							<label class="form-label mb-1">Người được áp dụng</label>
							<select class="iso-selectizeNotSearch required" name="admin_id" data-width="100%" data-placeholder="Người tham gia" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff" data-width="100%">
								{if !empty($oneOkrs.for_id)}
								<option value="{$oneOkrs.for_id}" selected="selected">{$clsProfile->getIndentity($oneOkrs.for_id, false)}</option>
								{/if}
							</select>
							{elseif $oneOkrs.type_id eq $smarty.const._TYPE_DEPARTMENT_OKRS_ID}
							<label class="form-label mb-1">Phòng ban áp dụng</label>
							<div class="clearfix"></div>
							<select name="for_id" data-width="100%" class="form-control iso-select2 required">
								{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$oneOkrs.for_id,'Phòng ban')}
							</select>
							{elseif $oneOkrs.type_id eq $smarty.const._TYPE_COMPANY_OKRS_ID}
							<label class="form-label mb-1">Áp dụng</label>
							<div class="clearfix"></div>
							<input type="hidden" name="for_id" value="0" />
							<span class="d-block border radius-4 text-muted" style="padding:0.4rem">
								{$clsISO->makeIcon('bx-group','Tất cả mọi người')}
							</span>
							{/if}
						{else}
							<label class="form-label mb-1">Người được áp dụng</label>
							<select class="iso-selectizeNotSearch required" name="admin_id" data-width="100%" data-placeholder="Người tham gia" data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff" data-width="100%">
								{if !empty($oneOkrs.for_id)}
								<option value="{$oneOkrs.for_id}" selected="selected">{$clsProfile->getIndentity($oneOkrs.for_id, false)}</option>
								{/if}
							</select>
						{/if}
					</div>
				</div>
				<div class="col-12 col-md-4">
					<label class="form-label mb-1">Nhóm</label>
					<div class="clearfix"></div>
					<select name="group_id" data-width="100%" class="form-control iso-select2 required">
						<option value="0">Chọn nhóm</option>
						{$clsProperty->getSelectByProperty('_OKRS_GROUP',$oneOkrs.group_id)}
					</select>
				</div>
			</div>
			<div class="widget-block mb-3">
				<div onClick="$Core.helper.toggle_block(this,event)" class="widget-header">Kết quả chính</div>
				<div class="widget-content">
					{assign var = toId value = $clsISO->getUniqid()}
					<div id="{$toId}" class="okrs__result-wrapper">
						{if $action eq '_edit'}
							{foreach name=i from=$list_krs key=gid  item = $_oKr}
							<div class="okrs__result-item{if !$smarty.foreach.i.first} mt-2{/if} position-relative okrs__result_{$toId} bg-lighter p-3 rounded-3">
								<a class="close" onClick="$Core.okrs.delete_result(this, event)"></a>
								<div class="form-group form-row mb-2">
									<div class="col-12 col-md-6 mb-2 mb-lg-0">
										<label class="form-label mb-1">Kết quả chính</label>
										<input class="form-control required" value="{$_oKr.main_result}" placeholder="Kết quả chính" name="kr_information[{$gid}][main_result]" />
									</div>
									<div class="col-12 col-md-3 mb-2 mb-lg-0">
										<label class="form-label mb-1">Mục tiêu</label>
										<input class="form-control required numberonly" placeholder="Mục tiêu" name="kr_information[{$gid}][target]" value="{$_oKr.target}" />
									</div>
									<div class="col-12 col-md-3">
										<label class="form-label mb-1">Đơn vị</label>
										<select class="form-control required form-select" name="kr_information[{$gid}][unit_id]">
											{$clsProperty->getSelectByProperty('_UNIT', $_oKr.unit_id)}
										</select>
									</div>
								</div>
								<div class="form-group form-row">
									<div class="col-12 col-md-6 mb-2 mb-lg-0">
										<label class="form-label mb-1">Kế hoạch</label>
										<textarea class="form-control" cols="255" rows="3" placeholder="Kế hoạch" name="kr_information[{$gid}][plan]">{$_oKr.plan}</textarea>
									</div>
									<div class="col-12 col-md-6">
										<label class="form-label mb-1">Kết quả thực tế</label>
										<textarea class="form-control" cols="255" rows="3" placeholder="Kết quả thực tế" name="kr_information[{$gid}][result]">{$_oKr.result}</textarea>
									</div>
								</div>
							</div>
							{/foreach}
						{else}
							{assign var = gid value = $clsISO->getUniqid()}
							<div class="okrs__result-item position-relative okrs__result_{$toId} bg-lighter p-3 rounded-3">
								<a class="close" onClick="$Core.okrs.delete_result(this, event)"></a>
								<div class="form-group form-row mb-2">
									<div class="col-12 col-md-6 mb-2 mb-lg-0">
										<label class="form-label mb-1">Kết quả chính</label>
										<input class="form-control" placeholder="Kết quả chính" name="kr_information[{$gid}][main_result]" />
									</div>
									<div class="col-12 col-md-3 mb-2 mb-lg-0">
										<label class="form-label mb-1">Mục tiêu</label>
										<input class="form-control numberonly" placeholder="Mục tiêu" name="kr_information[{$gid}][target]" />
									</div>
									<div class="col-12 col-md-3">
										<label class="form-label mb-1">Đơn vị</label>
										<select class="form-control form-select" name="kr_information[{$gid}][unit_id]">
											{$clsProperty->getSelectByProperty('_UNIT')}
										</select>
									</div>
								</div>
								<div class="form-group form-row">
									<div class="col-12 col-md-6 mb-2 mb-lg-0">
										<label class="form-label mb-1">Kế hoạch</label>
										<textarea class="form-control" cols="255" rows="3" placeholder="Kế hoạch" name="kr_information[{$gid}][plan]"></textarea>
									</div>
									<div class="col-12 col-md-6">
										<label class="form-label mb-1">Kết quả thực tế</label>
										<textarea class="form-control" cols="255" rows="3" placeholder="Kết quả thực tế" name="kr_information[{$gid}][result]"></textarea>
									</div>
								</div>
							</div>
						{/if}
					</div>
					<div class="d-flex py-2">
						<button type="button" toId="{$toId}" onclick="$Core.okrs.add_result(this,event)" class="btn btn-outline-default">{$clsISO->makeIcon('bx-plus','Thêm kết quả')}</button>
					</div>
				</div>
			</div>
			<div class="form-group mb-3">
				<label for="amout" class="form-label mb-1">Okr chéo</label>
				<select multiple="true" class="iso-selectizeNotSearch" name="okrs_cross_id[]" data-width="100%" data-placeholder="Người tham gia" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_list_orks"></select>
			</div>
			<div class="form-group form-row">
				<label for="amout" class="form-label col-12 col-md-1">Hiển thị</label>
				<div class="col-12 col-md-11">
					<div class="form-check mb-2">
						{assign var = foId value = $clsISO->getUniqid()}
						<input name="is_public" class="form-check-input" type="radio" id="{$foId}" value="1" checked="checked">
						<label class="form-check-label" for="{$foId}">
							<img src="{$URL_IMAGES}/globe.png" width="16px" /> Công khai
						</label>
					</div>
					<div class="form-check">
						{assign var = foId value = $clsISO->getUniqid()}
						<input name="is_public" class="form-check-input" type="radio" value="0" id="{$foId}">
						<label class="form-check-label" for="{$foId}">
							<img src="{$URL_IMAGES}/lock.png" width="16px" /> Riêng tư
						</label>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" uid="{$uid}" okrs_id="{$okrs_id}" onClick="$Core.okrs.save(this, event)" class="btn btn-primary">Cập nhật</button>
		</div>
	</form>
</div>
