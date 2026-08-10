<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}&act=options" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">Phương án nội thất</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar__main-group">
		<div class="ui-title-bar__heading-group">
			{if $pvalTable gt 0}
				<h1 class="ui-title-bar__title">Chỉnh sửa</h1>
			{else}
				<h1 class="ui-title-bar__title">Thêm mới</h1>
			{/if}
		</div>
	</div>
</div>
<div class="ui-layout">
	<form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
		<div class="row">
			<div class="col-md-8">
				<div class="box light">
					<div class="box-title">
						<div class="caption">
							<span class="bold">Thông tin chung</span>
						</div>
					</div>
					<div class="box-body">
						<div class="form-group">
							<div class="row">
								<div class="col-md-12">
									<label>Tên phương án <span class="requiredMask">*</span></label>
									<input type="text" class="form-control required" name="iso-title" value="{$oneItem.title}" placeholder="Nhập tên phương án" required="true" />
								</div>	
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<label>Loại căn <span class="requiredMask">*</span></label>
									<select class="form-control required" name="bedroom_id">
										{$clsProperty->getSelectByProperty('_BEDROOM',$oneItem.table_id)}
									</select>
								</div>	
								<div class="col-md-6">
									<label>Danh mục <span class="requiredMask">*</span></label>
									<select class="form-control required iso-select2" toId="slt_furniture" name="cat_ids[]" onChange="$Core.furniture.loadFurniture(this,event)" multiple id="slt_cat_ids">
										{$clsProperty->getSelectByPropertyV2('_CATEGORYSFURNITURE',$clsISO->getArrayByTextSlash($oneItem.cat_id))}
									</select>
								</div>	
							</div>
						</div>
						
					</div>
					
				</div>
				
				<div class="box light form-group" id="loadFurniture" style="display: none">
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="box light" id="formBill">
					<div class="box-title d-flex flex-wrap align-items-center">
						<div class="caption">
							<span class="bold">Tổng đơn</span>
						</div>
					</div>	
					<div class="box-body">
												
					</div>
				</div>
				{if $oneItem.furniture_option_id gt 0}
					<div class="box light">
						<div class="box-title d-flex justify-content-between align-items-center">
							<div class="caption">
								<span class="bold">Hình ảnh</span>
							</div>
							<input type="file" name="images[]" multiple hidden id="images" style="display:none">
							<button type="button" class="btn btn-primary text-nowrap" onclick="$Core.furniture.uploadImage(this,event);" toId="images" toImg="list_image" data-type="images" pvalTable="{$oneItem.furniture_option_id}" clsTable="FurnitureOptions">
								{$core->makeIcon('plus', 'Thêm mới')}
							</button>
						</div>
						<div class="box-body">
							<div class="form-row row" id="list_image">
								{if !empty($more_information.image)}
									{foreach from=$more_information.image item=image}
										<div class="item col-xs-3 mb-3" data-fancybox="gallery" href="{$image}">
											<img class="rounded drag-item cursor-pointer" src="{$image}" alt="avatar" style="width: 100%;height: auto">
										</div>
									{/foreach}
								{else}
									<p class="w-100 mb-0 text-center">Thư viện trống</p>
								{/if}									
							</div>
						</div>
					</div>
					<div class="box light">
						<div class="box-title d-flex justify-content-between align-items-center">
							<div class="caption">
								<span class="bold">Video/ảnh 360</span> 
							</div>
						</div>
						<div class="box-body">						
							<div class="input-group box_form mb-3 w-100">
								<input type="text" class="form-control" name="link_video" placeholder="Link video/ảnh 360" value="{$more_information.link_video}" aria-label="Search" aria-describedby="button-addon2" style=" width: calc(100% - 97px);margin: 0"> 
								<button class="btn btn-primary" type="button" onClick="$Core.furniture.addVideo(this,{$oneItem.furniture_option_id})">Thay đổi</button> 
							</div>
							<div class="box_body_video rounded" id="box_video">					
								{if $more_information.link_video ne ''}
									{$clsClassTable->getEmbedVideo($more_information.link_video,'100%',250)} 
								{else}
									<p class="w-100 mb-0 text-center">Chưa có video/ảnh 360</p>
								{/if}

							</div>
						</div>
					</div>
				{/if}
			</div>
		</div>		
		<div class="ui-page-actions ui-page-actions--has-secondary">
			<input value="Update" name="submit" type="hidden">
			<div class="ui-page-actions__container">
				<div class="ui-page-actions__actions ui-page-actions__actions--secondary"></div>
				<div class="ui-page-actions__actions ui-page-actions__actions--primary">
					<div class="ui-page-actions__button-group">
						<a class="btn btn-default" href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Calcel')}</a>
						{$saveBtn}
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<script>
	var furniture_option_id = `{$pvalTable}`;
	$(document).ready(function(){
		if(furniture_option_id > 0){
			$Core.furniture.loadFurniture();
		}		
	});
</script>