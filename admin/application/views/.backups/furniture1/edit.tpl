<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">Nội thất</span>
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
									<label>Tên sản phâm <span class="requiredMask">*</span></label>
									<input type="text" class="form-control required" name="iso-title" value="{$oneItem.title}" placeholder="Nhập tên sản phẩm" required="true" />
								</div>	
							</div>
						</div>
						<div class="form-group">
							<div class="row">	
								<div class="col-md-6">
									<label>Giá</label>
									<input type="text" class="form-control price-In" name="iso-price" placeholder="10.000.000" value="{$oneItem.price}" />
								</div>
								<div class="col-md-6">
									<label>Đơn vị tính</label>
									<select class="form-control required" name="iso-unit_id">
										{$clsProperty->getSelectByProperty('_FURNITUREUNIT',$oneItem.unit_id,'Chọn')}
									</select>
								</div>
							</div>							
						</div>
						<div class="form-group">
							<label>Mô tả sản phẩm</label>
							<textarea class="form-control w-100 isoTextArea" rows="5" data-field="intro" name="iso-intro" id="{$clsISO->getUniqid()}">{$oneItem.intro}</textarea>					
						</div>
					</div>
					
				</div>
				
				<div class="box light">
					<div class="box-title d-flex flex-wrap align-items-center">
						<div class="caption">
							<span class="bold">Thuộc tính</span>
						</div>
						<label class="switch ml-3">
							<input type="checkbox" name="is_property" onchange="$Core.furniture.set_status(this, event)" value="1" {if $more_information.is_property eq 1}checked{/if}>
							<span class="slider round"></span>
						</label>
						<div class="w-100 mt-2">Thêm mới thuộc tính giúp sản phẩm có nhiều lựa chọn, như kích cỡ chất liệu</div>
					</div>	
					<div class="box-body">
						<div id="box_property" {if $more_information.is_property eq 1}style="display:block"{else}style="display:none"{/if}>	
							{if $more_information.is_property eq 1}
								{assign var=property_values value=$more_information.property_values}
								{foreach from=$more_information.property_keys key=key item=item name=i}
									<div class="form-group item_property">
										<div class="row">
											<div class="col-md-6">
												<label>Tên thuộc tính</label>
												<input type="text" class="form-control property_keys" onChange="$Core.furniture.loadTablePrice(this,event)" name="property_keys[]" value="{$item}" placeholder="Nhập tên thuộc tính">
											</div>
											<div class="col-md-6">
												<label>Giá trị</label>												
												{if $smarty.foreach.i.index eq 0}
													<input type="text" id="input-tags" class="form-control input-tags property_values" name="property_values[]" placeholder="Gõ ký tự và nhấn enter để thêm thuộc tính" value="{$property_values[$key]}" onChange="$Core.furniture.loadTablePrice(this,event)"/>
												{else}
													<div class="d-flex align-items-center">
														<input type="text" id="input-tags" class="form-control input-tags property_values" name="property_values[]" placeholder="Gõ ký tự và nhấn enter để thêm thuộc tính" value="{$property_values[$key]}" onChange="$Core.furniture.loadTablePrice(this,event)"/>
														<a class="ml-2" href="javascript:void(0)" onClick="$Core.furniture.removeProperty(this,event)"><i class="fa fa-times" aria-hidden="true"></i></a>
													</div>
												{/if}
											</div>
										</div>
									</div>
								{/foreach}
							{/if}
							<a href="javascript:void(0)" id="addProperty" onClick="$Core.furniture.addProperty(this,event)" {if $more_information.property_keys|@count eq 2}style="display:none"{/if}><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Thêm thuộc tính khác</a>					
						</div>
					</div>
				</div>
				<div class="box light" id="boxLstProperty" style="display: none">
					<div class="box-title d-flex flex-wrap justify-content-between align-items-center">
						<div class="caption">
							<span class="bold">Phiên bản(<span id="total_property">0</span>)</span>
						</div>
					</div>	
					<div class="box-body">
						<div class="hastable">
							<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">
								<thead><tr>
									<th class="text-left" width="25%" col-span="2">Tên phiên bản</th>
									<th class="text-left" width="25%" col-span="2">Giá</th>
								</tr></thead>
								<tbody id="lstProperty"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="ui-layout__item">
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading">{$core->get_Lang('Status')}</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input {if $oneItem.is_online eq '1' || $pvalTable eq '0'} checked="checked"{/if} name="is_online" value="1" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> {$core->get_Lang('Show')}
									</div>
								</div>
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input{if $oneItem.is_online ne '1' and $pvalTable gt '0'} checked="checked"{/if} name="is_online" value="0" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> {$core->get_Lang('Hide')}
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card mt-half">
						<header class="ui-card__header">
							<h2 class="ui-heading">{$core->get_Lang('Category')}</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Category')}</label>
									<select class="form-control required iso-select2"  toId="{$toId}" name="cat_ids[]" multiple>
										{$clsProperty->getSelectByPropertyV2('_CATEGORYSFURNITURE',$clsISO->getArrayByTextSlash($oneItem.cat_id))}
									</select>
								</div>
							</div>
						</div>
					</div>
					{if $pvalTable gt 0}
						<div class="ui-card mt-half">
							<div class="box light">
								<div class="box-title d-flex justify-content-between align-items-center">
									<div class="caption">
										<span class="bold">Hình ảnh</span>
									</div>
									<input type="file" name="images[]" multiple hidden id="images" style="display:none">
									<button type="button" class="btn btn-primary text-nowrap" onclick="$Core.furniture.uploadImage(this,event);" toId="images" toImg="list_image" data-type="images" pvalTable="{$pvalTable}" clsTable="Furniture">
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
						</div>
					{/if}
				</div>
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
	var furniture_id = `{$pvalTable}`;
	$(document).ready(function(){
		$Core.furniture.loadTablePrice();
	});
</script>