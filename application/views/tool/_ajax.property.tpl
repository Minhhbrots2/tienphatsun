<div class="modal-dialog">
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_edit'}Sửa{else}Thêm{/if} thuộc tính</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-group mb-3">
				<label for="name" class="form-label">Tên thuộc tính</label>
				<input type="text" name="title" value="{if $action eq '_edit'}{$oneProperty.title}{/if}" class="form-control required" placeholder="Tên thuộc tính" maxlength="255" charset="UTF-8" autocomplete="off">
			</div>
			<div class="form-group mb-3">
				<label for="address" class="form-label">Mô tả</label>
				<textarea name="intro" class="form-control" placeholder="Mô tả" cols="255" rows="3" charset="UTF-8" autocomplete="off">{if $action eq '_edit'}{$oneProperty.intro}{/if}</textarea>
			</div>
			<div class="form-group form-row mb-3">
				<div class="col-6">
					<label for="name" class="form-label">Background</label>
					<input type="color" name="bgcolor" value="{if $action eq '_edit'}{$oneProperty.bgcolor}{/if}" class="form-control required" maxlength="255" charset="UTF-8" autocomplete="off">
				</div>
				<div class="col-6">
					<label for="name" class="form-label">Màu chữ</label>
					<input type="color" name="textcolor" value="{if $action eq '_edit'}{$oneProperty.textcolor}{/if}" class="form-control required" maxlength="255" charset="UTF-8" autocomplete="off">
				</div>
			</div>
			{if $property_type eq 'BANK_ACCOUNT'}
			<div class="form-group mb-3">
				<label for="name" class="form-label">Ngân hàng</label>
				<select data-width="100%" data-allow-clear="true" data-placeholder="Ngân hàng" class="iso-select2" name="image">
					<option value="0">Lựa chọn ngân hàng</option>
					<option{if $oneProperty.image eq 'QTM'} selected{/if} value="QTM">Quỹ tiền mặt</option>
					{if !empty($list_banks)}
						{foreach from=$list_banks item = _oBank}
						<option{if $oneProperty.image eq $_oBank.code} selected{/if} value="{$_oBank.code}">{$_oBank.name}({$_oBank.code})</option>
						{/foreach}
					{/if}
				</select>
			</div>
			<div class="form-group mb-3">
				<label for="name" class="form-label">Số tiền ban đầu</label>
				<div class="input-group input-group-merge">
					<input type="text" name="ms_value" value="{if $action eq '_edit'}{$oneProperty.ms_value}{/if}" class="form-control price-In numberonly required" placeholder="0.00" maxlength="255" charset="UTF-8" autocomplete="off">
					<span class="input-group-text">{$clsISO->getRate()}</span>
				</div>
			</div>
			{/if}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Huỷ bỏ</button>
			<button type="button" toId="{$toId}" property_id="{$property_id}" property_type="{$property_type}" 
				onClick="$Core.property.pop_save_property(this, event)" class="btn btn-primary">Lưu lại</button>
		</div>
	</form>
</div>
