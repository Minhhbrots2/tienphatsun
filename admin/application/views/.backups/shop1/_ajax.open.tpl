<style>
.config-gallery .content-image{
    width: 100%;
    /* height: 120px; */
    border: 1px solid rgba(195, 207, 216, .3);
    border-radius: 8px;
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    align-items: center;
    padding: 10px;
}
.config-gallery .item-choosed{
    height: 120px;
    border-radius: 8px;
    display: flex;
    flex-direction: row;
    align-items: center;
}

.config-gallery .dropzone {
    --bs-dz-icon-bg: #eeedf0;
    position: relative;
    border: 2px dashed #e4e6e8;
    border-radius: .5rem;
    cursor: pointer;
    inline-size: 100%;
}

.config-gallery .dropzone .dz-message {
    font-size: 1.5rem;
    font-weight: 500;
    margin-block: 4rem 3rem;
    margin-inline: 0;
}
.config-gallery .img-empty,
.img-add {
    width: 100px;
    height: 100px;
    border: 2px dashed #e4e6e8;
}
.config-gallery .item-img {
    position: relative;
    display: inline-block;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: 30px;
    color: #e4e6e8;
    margin-left: 5px;
    margin-top: 5px;
}
.config-gallery .item-img.img-empty,
.img-add {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: 30px;
    color: #e4e6e8;
    margin-left: 5px;
    margin-top: 5px;
}
.config-gallery .item-img .btn-del-image,
.btn-del-image-menu {
    position: absolute;
    top: -12px;
    right: -12px;
    background: rgba(255, 255, 255, 0.8);
    border: none;
    cursor: pointer;
    z-index: 10;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.config-gallery .item-img .btn-del-image i {
    color: red;
}
.btn-exchange {
    position: absolute;
    bottom: 0;
    left: 0;
    padding: 5px;
    width: 100%;
}
</style>
<div class="modal-dialog modal-md">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		{assign var = gId value = $clsISO->getUniqid()}
		<form class="d-none" enctype="multipart/form-data">
			<input id="{$gId}" class="select_file_{$gId}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" charset="UTF-8" 
				onChange="$Core.shop.upload_file(this, event)" name="upload_file" />
			<input id="{$gId}" class="select_heic_file_{$gId}" type="file" charset="UTF-8" 
				onChange="$Core.shop.upload_heic_file(this, event)" name="upload_file" />
		</form>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Tiêu đề</label>
					<div class="col-md-7">
						<input class="form-control required" placeholder="Tên cửa hàng/tiện ích" maxlength="255" name="title" 
							   value="{if $action eq '_edit'}{$oneShop.title}{/if}" />
					</div>
					<label class="col-md-1 col-form-label text-right">Danh mục</label>
					{assign var = toId value = $clsISO->getUniqid()}
					<div class="col-md-2">
						<select class="form-control" onChange="$Core.shop.get_subcategory(this, event)" toId="{$toId}" name="cat_id">
							{$clsProperty->getSelectByProperty('_SHOP',$oneShop.cat_id)}
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Template</label>
					<div class="col-md-10">
						<select name="template_id" class="form-control" onChange="$Core.shop.getForm(this, event)" data-shop-id="{$oneShop.shop_id}" data-tempate-id="{if isset($more_information.dynamic.template_id)}{$more_information.dynamic.template_id}{/if}">
							<option value="">--- Chọn mẫu ---</option>
                            {foreach from=$lstSetting item=itemSetting key=keySetting name=nameSetting}
                            <option value="{$itemSetting.setting_id}" {if isset($more_information.dynamic.template_id) and $itemSetting.setting_id eq $more_information.dynamic.template_id} selected {/if}>{$itemSetting.title}</option>
                            {/foreach}
						</select>
					</div>
				</div>
                <div class="js-form-template">
                    {if !empty($more_information.dynamic) and !empty($configForm)}
                        {include file="./_ajax.template.tpl" information=$more_information.dynamic configForm=$configForm}
                    {/if}
                </div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Danh mục con</label>
					<div class="col-md-10">
						<select id="{$toId}" name="list_cat_id[]" multiple="multiple" class="form-control iso-select2">
							{$html_subcategory_options}
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Điện thoại</label>
					<div class="col-md-10">
						<input class="form-control" name="phone" placeholder="Nhập điện thoại..." value="{if $action eq '_edit'}{$more_information.phone}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Tags</label>
					<div class="col-md-10">
						<input class="form-control input-tags" placeholder="Nhập tag..." name="tags" value="{if $action eq '_edit'}{$oneShop.tags}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Vị trí Map</label>
					<div class="col-md-10">
						<input class="form-control" name="map" placeholder="https://goo.gl/maps/..." value="{if $action eq '_edit'}{$more_information.map}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Thời gian mở</label>
					<div class="col-md-2">
						<input type="time" class="form-control" name="open_at_time" placeholder="https://goo.gl/maps/..." value="{if $action eq '_edit'}{$more_information.open_at_time}{/if}" />
					</div>
					<label class="col-md-2 col-form-label text-right">Thời gian đóng</label>
					<div class="col-md-2">
						<input type="time" class="form-control" name="close_at_time" placeholder="https://goo.gl/maps/..." value="{if $action eq '_edit'}{$more_information.close_at_time}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Hình ảnh</label>
					<div class="col-md-10">
						<div class="input-group">
							<input type="text" id="content_file_{$gId}" class="form-control required" name="image" 
								   value="{if $action eq '_edit'}{$oneShop.image}{/if}" placeholder="Tài liệu đính kèm" />
							<div class="input-group-btn">
								<button type="button" toId="{$gId}" onClick="$Core.shop.select_file(this, event)" 
								class="btn btn-default">{$core->makeIcon('upload','Chọn')}</button>
								<!--<button type="button" toId="{$gId}" onClick="$Core.shop.select_heic_file(this, event)" 
								class="btn btn-default">{$core->makeIcon('upload','Chọn HEIC')}</button> -->
							</div>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Dự án</label>
					<div class="col-md-3">
						<select name="project_id" onChange="$Core.shop.select_block(this, event)" toId="slb_Block_Id" class="form-control">
							<option value="0">Chọn dự án</option>
							{foreach name=i from=$list_projects item = _project}
							<option{if $oneShop.project_id eq $_project.project_id} selected{/if} value="{$_project.project_id}">{$_project.title}</option>
							{/foreach}
						</select>
					</div>
					<label class="col-md-1 col-form-label">Phân khu</label>
					<div class="col-md-2">
						<select name="block_id" id="slb_Block_Id" onChange="$Core.shop.select_building(this, event)" 
						toId="slb_Building_Id" class="form-control iso-select2">
							<option>Chọn phân khu</option>
							{if !empty($list_blocks)}
								{foreach from=$list_blocks item = _oBlock}
								<option{if $oneShop.block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
					<label class="col-md-1 col-form-label">Tòa nhà</label>
					<div class="col-md-3">
						<select name="building_id" id="slb_Building_Id" class="form-control iso-select2">
							<option>Chọn tòa nhà</option>
							{if !empty($list_buildings)}
								{foreach from=$list_buildings item = _oBuilding}
								<option{if $oneShop.building_id eq $_oBuilding.property_id} selected{/if} value="{$_oBuilding.property_id}">{$_oBuilding.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
				</div>
                <div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Địa chị cụ thể: </label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="address" placeholder="Địa chỉ cụ thể" value="{if $action eq '_edit'}{$more_information.address}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Nội dung</label>
					<div class="col-md-10">
						<textarea class="form-control isoTextArea" data-field="intro" id="{$clsISO->getUniqid()}">{if $action eq '_edit'}{$oneShop.intro}{/if}</textarea>
					</div>
				</div>
                <div class="form-group config-gallery form-row">
                    <label class="col-md-2 col-form-label text-right">Ảnh menu</label>
                    <div class="col-md-10">
                        <div class="item-img img-add box-image-menu js-box-select-image js-box-image-menu" >
                            <img width="100%" height="100%" id="isoman_show_image-menu" src="{if $more_information.image_menu} {$more_information.image_menu} {else} {FH_URL}/admin/application/themes/images/no-image.jpg {/if}">
                            <button class="btn btn-exchange ajOpenDialog d-none" isoman_for_id="image-menu" isoman_val="" isoman_name="image">
                                <i class="fa fa-exchange fs-5 text-dark"></i>
                            </button>
                            <button type="button" class="btn btn-del-image-menu d-none" onClick="$Core.shop.del_photo_menu(this, event)">
                                <i class="fa fa-times-circle" aria-hidden="true"></i>
                            </button>
                        <input type="hidden" id="isoman_url_image-menu" name="image_menu" value="{if $more_information.image_menu} {$more_information.image_menu} {/if}">

                        </div>
                    </div>
				</div>
				<div class="form-group config-gallery form-row">
                    <label class="col-md-2 col-form-label text-right">Chọn ảnh Cửa hàng</label>
                    <div class="col-md-10">
                        <div class="dropzone content-image" style="cursor: pointer;">
                            {assign var = imagesGallery value = $more_information['image_shop_gallery']}
                            {if !empty($imagesGallery)}
                                {foreach from=$imagesGallery item=_item}
                                    <div class="item-img" isoman_for_id="image-content" isoman_val="" isoman_name="image">
                                        <img width="100" height="100" id="isoman_show_image-content" src="{$_item}">
                                        <button type="button" class="btn btn-del-image" onClick="$Core.shop.del_photo(this, event)"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                                        <input type="hidden" name="image_gallery[]" value="{$_item}">
                                    </div>
                                {/foreach}
                            {/if}
                            <div class="item-img img-empty ajOpenDialog" isoman_for_id="image-content" isoman_multiple="1" isoman_val="" isoman_name="image">
                                <i class="fa fa-plus-circle"></i>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.shop.save(this, event)" shop_id="{$shop_id}" class="btn btn-success">
					<span>Lưu lại</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>{$core->get_Lang('Close')}</span>
				</button>
			</div>
		</form>
	</div>
</div>