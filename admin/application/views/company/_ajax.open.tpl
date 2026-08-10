<div class="modal-dialog modal-md" style="max-width: 600px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		{assign var = aId value = $clsISO->getUniqid()}
		{assign var = bId value = $clsISO->getUniqid()}
		<form class="d-none" enctype="multipart/form-data">
			<input id="{$aId}" class="select_file_{$aId}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" charset="UTF-8" 
				onChange="$Core.company.upload_file(this, event)" name="upload_file" />
			<input id="{$aId}" class="select_heic_file_{$aId}" type="file" charset="UTF-8" 
				onChange="$Core.company.upload_heic_file(this, event)" name="upload_file" />
			<input id="{$bId}" class="select_file_{$bId}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" charset="UTF-8" 
				onChange="$Core.company.upload_file(this, event)" name="upload_file" />
			<input id="{$bId}" class="select_heic_file_{$bId}" type="file" charset="UTF-8" 
				onChange="$Core.company.upload_heic_file(this, event)" name="upload_file" />
		</form>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Tên công ty (đầy đủ)</label>
					<div class="col-md-10">
						<input class="form-control required" placeholder="Tên công ty (đầy đủ)" maxlength="255" name="title_vn" value="{if $action eq '_edit'}{$oneItem.title_vn}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Tên công ty (viết tắt)</label>
					<div class="col-md-10">
						<input class="form-control required" placeholder="Tên công ty (viết tắt)" maxlength="255" name="title" 
							   value="{if $action eq '_edit'}{$oneItem.title}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Loại đối tác</label>
					<div class="col-md-10">
						<select name="type" id="" class="form-control required">
							{$clsProperty->getSelectByProperty('_TYPECOMPANY',$oneItem.type)}
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Logo</label>
					<div class="col-md-4">
						<div class="input-group">
							<input type="text" id="content_file_{$aId}" class="form-control" name="image" 
								   value="{$oneItem.image}" placeholder="Tài liệu đính kèm" onChange="$Core.company.upload_file(this, event)"/>
							<div class="input-group-btn">
								<button type="button" toId="{$aId}" onClick="$Core.company.select_file(this, event)" 
								class="btn btn-default">{$core->makeIcon('upload','Chọn')}</button>
							</div>
						</div>
					</div>
					<label class="col-md-2 col-form-label text-right">Banner</label>
					<div class="col-md-4">
						<div class="input-group">
							<input type="text" id="content_file_{$bId}" class="form-control" name="banner" 
								   value="{$oneItem.banner}" placeholder="Tài liệu đính kèm" onChange="$Core.company.upload_file(this, event)"/>
							<div class="input-group-btn">
								<button type="button" toId="{$bId}" onClick="$Core.company.select_file(this, event)" 
								class="btn btn-default">{$core->makeIcon('upload','Chọn')}</button>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Điện thoại</label>
					<div class="col-md-4">
						<input class="form-control required" name="phone" placeholder="Nhập điện thoại..." value="{if $action eq '_edit'}{$oneItem.phone}{/if}" />
					</div>
					<label class="col-md-2 col-form-label text-right">Zalo</label>
					<div class="col-md-4">
						<input class="form-control required" name="zalo" placeholder="Nhập số zalo" value="{if $action eq '_edit'}{$oneItem.zalo}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Địa chỉ</label>
					<div class="col-md-10">
						<input class="form-control" name="address" placeholder="Nhập địa chỉ..." value="{if $action eq '_edit'}{$oneItem.address}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Show room</label>
					<div class="col-md-10">
						<input class="form-control" placeholder="Show room" name="show_room" value="{if $action eq '_edit'}{$more_information.show_room}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Xưởng sx</label>
					<div class="col-md-10">
						<input class="form-control" placeholder="Xưởng sx" name="factory" value="{if $action eq '_edit'}{$more_information.factory}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Website</label>
					<div class="col-md-10">
						<input class="form-control" placeholder="Website" name="website" value="{if $action eq '_edit'}{$more_information.website}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Mô tả</label>
					<div class="col-md-10">
						<textarea class="form-control w-100" rows="5" data-field="intro" name="intro" id="{$clsISO->getUniqid()}">{if $action eq '_edit'}{$oneItem.intro}{/if}</textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.company.save(this, event)" company_id="{$company_id}" class="btn btn-success">
					<span>Lưu lại</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>{$core->get_Lang('Close')}</span>
				</button>
			</div>
		</form>
	</div>
</div>
