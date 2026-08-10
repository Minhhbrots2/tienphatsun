<div class="modal-dialog">
	{assign var = toId value = $clsISO->getUniqid()}
	<form class="d-none" method="POST" enctype="multipart/form-data">
		<input type="file" onchange="$Core.tool.upload_stock_image(this,event)" accept="image/*" 
		id="{$toId}" toId="{$toId}" class="upload_stock_image" name="image" />
	</form>
	<form method="POST" class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		{if $template_type eq 'video'}
		<div class="modal-body">
			<div class="form-group">
				<label class="col-form-label">Tiêu đề</label>
				<input name="title" value="{$oneVideo.title}" class="form-control required" placeholder="Tiêu đề" />
			</div>
			<div class="form-group">
				<label class="col-form-label">Đường dẫn Video</label>
				<input name="url" value="{$oneVideo.url}" class="form-control required" placeholder="https://" />
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" media_id="{$media_id}" stock_meta_id="{$stock_meta_id}" class="btn btn-primary" 
			onClick="$Core.tool.add_stock_video(this, event)" >Lưu lại</button>
		</div>
		{elseif $template_type eq 'edit_image'}
		<div class="modal-body">
			<div class="form-group mb-3">
				<div class="thumbnail position-relative">
					<div class="dIimTDVyye position-absolute">
						<a href="javascript:;" class="text-dark" toId="{$toId}" onClick="$Core.tool.add_stock_image(this, event)">{$clsISO->makeIcon('bx-upload')}</a>
					</div>
					<input type="hidden" class="hidden_{$toId}" name="url" value="{$oneImage.url}" />
					<img src="{$oneImage.url}" class="img-fluid img-thumbnail image_{$toId} radius-4" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-form-label">Tiêu đề</label>
				<input type="text" name="title" class="form-control required" placeholder="Tiêu đề" 
				value="{$oneImage.title}" maxlength="255" />
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="submit" value="edit_image" />
			<input type="hidden" name="media_id" value="{$media_id}" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" stock_meta_id="{$stock_meta_id}" class="btn add_stock_image btn-primary" onClick="$Core.tool.add_stock_image(this, event)" >Lưu lại</button>
		</div>
		{elseif $template_type eq 'image'}
		<div class="modal-body">
			{foreach name=i from=$list_images item = _oImage}
			{assign var = uid value = $clsISO->getUniqid()}
			<div class="form-group">
				<div class="thumbnail">
					<img src="{$clsISO->getGoogleUrl($_oImage)}" class="img-fluid img-thumbnail img-responsive" />
				</div>
				<label class="col-form-label">Tiêu đề</label>
				<input type="hidden" name="images[{$uid}][url]" value="{$_oImage}" />
				<input type="text" name="images[{$uid}][title]" class="form-control required" placeholder="Tiêu đề" />
			</div>
			{/foreach}
			<div class="modal-footer">
				<input type="hidden" name="submit" value="add_image" />
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" onClick="$Core.tool.add_stock_image(this, event)" stock_meta_id="{$stock_meta_id}" 
				class="btn btn-primary add_stock_image">Lưu lại</button>
			</div>
		</div>
		{else}
		<div class="modal-body">
			<div class="form-group">
				<textarea name="content" cols="255" rows="10" id="{$clsISO->getUniqid()}" 
				class="form-control hasIsoRedactor">{$p_value}</textarea>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" p_id="{$p_id}" p_field="{$p_field}" class="btn btn-primary" 
			onClick="$Core.tool.update_stock_field(this, event)" >Lưu lại</button>
		</div>
		{/if}
	</form>
</div>