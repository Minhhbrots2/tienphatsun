<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
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
					<label class="col-form-label">Tiêu đề</label>
					<input name="title" value="{$oneItem.title}" class="form-control required" placeholder="Tiêu đề" />			
				</div>		
			</div>	
			<div class="form-group mb-2">
				<label for="nameSlideTop" class="form-label">Mô tả</label>
				<textarea id="{$clsISO->getUniqid()}" class="form-control" cols="20" rows="5" name="content" data-name="content">{if !empty($oneItem.content)}{$oneItem.content}{/if}</textarea>
			</div>
			{*<div class="form-group mb-2">
				<label for="nameSlideTop" class="form-label">Hiển thị</label>
				<div class="d-flex align-items-center gap-2">
					<div class="form-check">
					  <input name="type" class="form-check-input" type="radio" value="0" id="type_public_{$uid}" onChange="$Core.docs.check_type(this,event)" uid="{$uid}" {if $type eq '0'}checked{/if}>
					  <label class="form-check-label" for="type_public_{$uid}"> Công khai </label>
					</div>
					<div class="form-check">
					  <input name="type" class="form-check-input" type="radio" value="1" id="type_private_{$uid}" onChange="$Core.docs.check_type(this,event)" uid="{$uid}" {if $type eq '1'}checked{/if}>
					  <label class="form-check-label" for="type_private_{$uid}"> Nhóm phòng ban</label>
					</div>
				</div>
			</div>
			<div class="form-group mb-2 {if $type eq '0'}d-none{/if}" id="department_{$uid}" >
				<label  class="form-label">Phòng ban</label>
				<div class="selecttize-lg">
					<select class="multiselect w-100" data-width="100%" data-placeholder="Phòng ban" data-selected_text="phòng ban" name="department_ids[]" data-optgroup="false" multiple >
						{$clsProperty->getSelectByPropertyV2("_DEPARTMENT",$oneItem.department,"",1,"parent")}
					</select>
				</div>
			</div>*}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" data-folder_id="{$oneItem.folder_id}" class="btn btn-primary" 
			onClick="$Core.docs.save_folder(this, event)" >Lưu lại</button>
		</div>
		
	</form>
</div>