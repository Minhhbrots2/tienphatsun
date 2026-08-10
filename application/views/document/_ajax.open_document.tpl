<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-ipad-xl">
	{assign var = toId value = $clsISO->getUniqid()}
	<form class="d-none" enctype="multipart/form-data">
		<input id="select_file_{$uid_file}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" uid="{$uid_file}" onchange="$Core.docs.upload_file(this,event)" charset="UTF-8" name="image">
	</form>
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-2">
				<div class="form-row">
					<div class="col-md-6 col-sm-12 col-12 mb-2">
						<label class="col-form-label">Tiêu đề</label>
						<input name="title" value="{$oneItem.title}" class="form-control required" placeholder="Tiêu đề" />	
					</div>	
					<div class="col-md-6 col-sm-12 col-12 mb-2">
						<label class="col-form-label">Số hiệu</label>
						<input name="document_number" value="{$more_information.document_number}" class="form-control" placeholder="Số hiệu" />
					</div>		
				</div>		
			</div>	
			<div class="form-group mb-2">
				<label for="nameSlideTop" class="form-label">Mô tả</label>
				<textarea id="{$clsISO->getUniqid()}" class="form-control" cols="20" rows="5" name="content" data-name="content">{if !empty($oneItem.content)}{$oneItem.content}{/if}</textarea>
			</div>
			<div class="form-group mb-2">
				<div class="form-row">
					<div class="col-md-6 col-sm-12 col-12 mb-2">
						<label  class="form-label">Danh mục</label>
						<div class="selecttize-lg">
							<select class="form-select w-100" placeholder="Danh mục" name="cat_id" data-optgroup="false" >
								<option value="">Danh mục</option>
								{foreach from=$lstCategory_doc item=_oFolder}
									<option value="{$_oFolder.folder_id}" {if $_oFolder.folder_id eq $cat_id}selected{/if}>{$_oFolder.title}</option>
								{/foreach}
							</select>
						</div>
					</div>
					<div class="col-md-6 col-sm-12 col-12 mb-2">
						<label  class="form-label">Phòng ban</label>
						<div class="selecttize-lg">
							<select class="multiselect w-100" data-width="100%" data-placeholder="Phòng ban" data-selected_text="phòng ban" name="department_id[]" data-optgroup="false" multiple onChange="$Core.docs.loadRole(this,event)" toId="slt_role_{$uid}" >
								{$clsProperty->getSelectByPropertyV2("_DEPARTMENT",$oneItem.department,"",1,"parent")}
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				<label class="form-label">Vai trò</label>
				<div class="selecttize-lg w-100">
					<select class="iso-select2 w-100" data-width="100%" placeholder="Vai trò" name="role_ids[]" data-optgroup="false" multiple id="slt_role_{$uid}" >
						{if !empty($html_role)}
							{$html_role}
						{/if}
					</select>
				</div>
			</div>
			<div class="form-group mb-2">
				<label  class="form-label">Tags</label>
				<input type="text" id="input-tags" class="form-control add_field input-tags" name="tags" placeholder="Tags" value="{$oneItem.list_tags}" />
			</div>
			<div class="form-group attachments mb-2">
				<label class="form-label mb-1">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_{$uid}">
				{if !empty($oneItem.file_doc)}
					{foreach name=i from = $oneItem.file_doc item = _oFile}
					<div class="MultiFile-label">
						<a class="MultiFile-remove" href="javascript:void(0)" onclick="$Core.docs.removeFile(this,event)" doc_id="{$oneItem.doc_id}" data-url="{$_oFile.url}">x</a> 
						<span><span class="MultiFile-label" title="{$_oFile.name}">
							<span class="MultiFile-title">{$_oFile.name}</span></span>
						</span>
					</div>
					{/foreach}
				{/if}
				</div>
				<div class="clearfix"></div>
				<input name="files[]" type="file" class="maxsize-10240" id="attachments_{$uid}" multiple />
			</div>
			<div class="widget-block mb-2 collapsed">
				<div onclick="$Core.helper.toggle_block(this,event)" class="widget-header">Thông tin thêm</div>
				<div class="widget-content">
					<div class="form-row">	
						<div class="col-md-6 col-sm-12 col-12 mb-2">
							<label class="col-form-label">Ngày ban hành</label>
							<input type="date" name="effective_date" value="{$more_information.effective_date}" class="form-control" placeholder="Ngày ban hành" />
						</div>
						<div class="col-md-6 col-sm-12 col-12 mb-2">
							<label class="col-form-label">Người ban hành</label>
							<select class="iso-select2 w-100" data-width="100%" placeholder="Người ban hành" name="authorized_person">
								<option value="0">Chọn người ban hành</option>
								{if !empty($list_staffs)}
									{foreach from=$list_staffs item=item}
										<option value="{$item.profile_id}" {if $more_information.authorized_person eq $item.profile_id}selected{/if}>{$item.full_name}</option>
									{/foreach}
								{/if}
							</select>
						</div>	
					</div>
				</div>
			</div>
			<div class="form-group mb-2">
				<div class="d-flex align-items-center gap-2">
					<div class="form-check">
					  <input name="is_important" class="form-check-input" type="checkbox" value="1" id="is_important_{$uid}" {if $oneItem.is_important eq '1'}checked{/if}>
					  <label class="form-check-label" for="is_important_{$uid}"> Văn bản quan trọng </label>
					</div>
					<div class="form-check">
					  <input name="is_top" class="form-check-input" type="checkbox" value="1" id="is_top_{$uid}" {if $oneItem.is_top eq '1'}checked{/if}>
					  <label class="form-check-label" for="is_top_{$uid}"> Ghim đầu trang</label>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" data-doc_id="{$oneItem.doc_id}" class="btn btn-primary" 
			onClick="$Core.docs.save_doc(this, event)" >Lưu lại</button>
		</div>
		
	</form>
</div>