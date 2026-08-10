<div class="modal-dialog modal-md" style="max-width: 668px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		{assign var = gId value = $clsISO->getUniqid()}
		<form class="d-none" enctype="multipart/form-data">
			<input id="{$gId}" class="select_file_{$gId}" accept="image/jpeg,image/jpg,image/png,gif" type="file" charset="UTF-8" 
				onChange="$Core.slide.upload_file(this, event)" name="upload_file" />
		</form>
		<form method="post" action="" enctype="multipart/form-data" id="frmAddSlide">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-form-label text-right">Tiêu đề</label>
					<input class="form-control" placeholder="Tiêu đề" maxlength="255" name="title" value="{if $action eq '_edit'}{$oneItem.title}{/if}" />
				</div>
				<div class="form-group">
					<label class="col-form-label text-right">Link</label>
					<input class="form-control" placeholder="Nhập đường dẫn" maxlength="255" name="link" value="{if $action eq '_edit'}{$oneItem.link}{/if}" />
				</div>
				<div class="form-group">
					<label class="col-form-label text-right">Hình ảnh</label>					
					<!--<div class="input-group">
						<input type="text" id="content_file_{$gId}" class="form-control required" name="image" value="{$oneItem.image}" placeholder="Hình ảnh"/>
						<div class="input-group-btn">
							<button type="button" toid="{$gId}" onclick="$Core.slide.select_file(this, event)" class="btn btn-default"><i class="fa fa-upload"></i> <span>Chọn</span></button>
						</div>
					</div>-->
					<div class="input-group">
						<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh" id="isoman_url_image" value="{$oneItem.image}">
						<div class="input-group-btn"><button class="btn btn-icon btn-default ajOpenDialog" isoman_for_id="image" isoman_val="{$oneItem.image}" isoman_name="image"><i class="fa fa-image"></i></button></div>	
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label text-right">Hình ảnh mobile (480x320px)</label>					
					<!--<div class="input-group">
						<input type="text" id="content_file_{$gId}" class="form-control required" name="image" value="{$oneItem.image}" placeholder="Hình ảnh"/>
						<div class="input-group-btn">
							<button type="button" toid="{$gId}" onclick="$Core.slide.select_file(this, event)" class="btn btn-default"><i class="fa fa-upload"></i> <span>Chọn</span></button>
						</div>
					</div>-->
					<div class="input-group">
						<input type="text" class="form-control" name="image_mobile" placeholder="Chọn hình ảnh" id="isoman_url_image_mobile" value="{$oneItem.image_mobile}">
						<div class="input-group-btn"><button class="btn btn-icon btn-default ajOpenDialog" isoman_for_id="image_mobile" isoman_val="{$oneItem.image_mobile}" isoman_name="image_mobile"><i class="fa fa-image"></i></button></div>	
					</div>
				</div>
				<div class="form-group">
					<label class="col-form-label">Nội dung</label>
					<textarea class="form-control isoTextArea" rows="5" cols="5" data-field="content" id="{$clsISO->getUniqid()}">{if $action eq '_edit'}{$oneItem.content}{/if}</textarea>
				</div>
				{assign var = toId value = $clsISO->getUniqid()}
				<div class="form-group">
					<label class="col-form-label">Hiển thị</label>
					<select name="_site" id="" class="form-select form-control">
						<option value="">Chọn</option>
						{foreach from=$list_domains item=_oDomain name=i}						
							<option value="{$_oDomain.domain}" {if $oneItem._site eq $_oDomain.domain}selected{/if}>{$_oDomain.domain}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="type" value="{$type}">
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>{$core->get_Lang('Close')}</span>
				</button>
				<button type="button" onClick="$Core.slide.save(this, event)" {$pkeyTable}="{$pvalTable}" class="btn btn-success">
					<span>Lưu lại</span>
				</button>
			</div>
		</form>
	</div>
</div>
