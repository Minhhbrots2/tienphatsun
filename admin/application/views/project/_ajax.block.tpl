{if $template_type eq '_form'}
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePgae}</strong></h3>
		</div>
		{assign var = toId value = $clsISO->getUniqid()}
		<form method="POST" class="frmIssue d-none" enctype="multipart/form-data">
			<input type="file" onchange="$Core.project.upload_image(this, event)" name="image" 
			maxlength="255" id="{$toId}" toId="{$toId}" />
		</form> 
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<div class="col-md-1">
						<label class="col-form-label">Vị trí</label>
						<input type="number" class="form-control required" placeholder="Vị trí" name="order_no" value="{$oneBlock.order_no}" />
					</div>
					<div class="col-md-2">
						<label class="col-form-label">Mã phân khu<span class="text-red">*</span></label>
						<input type="text" class="form-control required" placeholder="Mã phân khu" name="property_code" value="{$oneBlock.property_code}" />
					</div>
					<div class="col-md-6">
						<label class="col-form-label">Tên phân khu<span class="text-red">*</span></label>
						<input type="text" class="form-control required" placeholder="Nhập tên dự án" name="title" value="{$oneBlock.title}" />
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Loại hình<span class="text-red">*</span></label>
						<select name="parent_id" class="form-control required">
							{$clsProperty->getSelectByProperty('_BLOCK_TYPE',$oneBlock.parent_id,'Loại hình')}
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					{foreach from=$list_price_fields key = _oKey item = _oText}
					<div class="col-md-3">
						<div class="d-flex align-items-center bg-gray p-3 radius-4 mb-2">
							<label class="switch mr-3"><input type="checkbox" name="price_field_configs[{$_oKey}][status]"{if !empty($price_field_configs.$_oKey.status) && $price_field_configs.$_oKey.status eq '1'} checked{/if} value="1">
								<span class="slider round"></span></label>
							{if !empty($price_field_configs.$_oKey.status)}
							<input type="text" class="form-control w-125px" name="price_field_configs[{$_oKey}][title]" value="{$price_field_configs.$_oKey.title}" />
							{else}
							<input type="text" class="form-control w-125px" name="price_field_configs[{$_oKey}][title]" value="{$_oText}" />
							{/if}
						</div>
					</div>
					{/foreach}
				</div>
				<div class="form-group form-row">
					<div class="col-md-2">
						<label class="col-form-label">Mở bán</label>
						<div class="d-flex align-items-center">
							<label class="switch mr-3">
								<input type="checkbox" name="on_sale"{if !empty($more_information.on_sale) && $more_information.on_sale eq '1'} checked{/if} value="1">
								<span class="slider round"></span>
							</label>
							<span class="text-muted">Đang mở bán</span>
						</div>
					</div>
					<div class="col-md-2">
						<label class="col-form-label">Dự án</label>
						<div class="d-flex gap-2 align-items-center">
							<label class="switch">
								<input type="checkbox" name="is_project"{if isset($more_information.is_project) && $more_information.is_project eq '1'} checked{/if} value="1">
								<span class="slider round"></span>
							</label>
							<span class="text-muted">Phân khu = dự án</span>
						</div>
					</div>
					<div class="col-md-5">
						<label class="col-form-label">Quỹ</label>
						<div class="d-flex align-items-center gap-3">
							<div class="d-flex align-items-center">
								<label class="switch mr-1">
									<input type="radio" name="is_stock_fund_of"{if !empty($more_information.is_stock_fund_of) && $more_information.is_stock_fund_of eq 'mas'} checked{/if} value="mas">
									<span class="slider round"></span>
								</label>
								<span class="text-muted">Quỹ Mas</span>
							</div>
							<div class="d-flex align-items-center">
								<label class="switch mr-1">
									<input type="radio" name="is_stock_fund_of"{if !empty($more_information.is_stock_fund_of) && $more_information.is_stock_fund_of eq 'vin'} checked{/if} value="vin">
									<span class="slider round"></span>
								</label>
								<span class="text-muted">Quỹ Vin</span>
							</div>
							<div class="d-flex align-items-center">
								<label class="switch mr-1">
									<input type="radio" name="is_stock_fund_of"{if !empty($more_information.is_stock_fund_of) && $more_information.is_stock_fund_of eq 'other'} checked{/if} value="other">
									<span class="slider round"></span>
								</label>
								<span class="text-muted">Quỹ Khác</span>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-4">
						<label class="col-form-label">Chủ đầu tư</label>
						<select class="form-control iso-select2" name="investor_id">
							{$clsProperty->getSelectByProperty('_INVESTOR',$more_information.investor_id)}
						</select>
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Nhận booking</label>
						<div class="d-flex gap-2 align-items-center">
							<label class="switch">
								<input type="checkbox" name="is_booking"{if isset($more_information.is_booking) && $more_information.is_booking eq '1'} checked{/if} value="1" onChange="$Core.project.change_booking(this,event)" toId="time_booking_{$uid}" >
								<span class="slider round"></span>
							</label>
							<span class="text-muted">Nhận booking</span>
						</div>
					</div>		
					<div class="col-md-5">
						<div class="form-row {if empty($more_information.is_booking)} d-none{/if}" id="time_booking_{$uid}">
							<div class="col-md-6">
								<label class="col-form-label">Từ</label>
								<div class="d-flex gap-2 align-items-center">
									<input class="form-control" type="datetime-local" name="start_booking" value="{$start_booking}">
								</div>
							</div>			
							<div class="col-md-6">
								<label class="col-form-label">Đến</label>
								<div class="d-flex gap-2 align-items-center">
									<input class="form-control" type="datetime-local" name="end_booking" value="{$end_booking}">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					{foreach from=$info_more item=_oItem key=key name=i}
						<div class="col-md-3">
							<label class="col-form-label">{$_oItem.title}</label>
							<input type="hidden" name="info_more[{$key}][title]" value="{$_oItem.title}" >
							<input type="hidden" name="info_more[{$key}][class]" value="{$_oItem.class}" >
							<input type="hidden" name="info_more[{$key}][placeholder]" value="{$_oItem.placeholder}" >
							<div class="d-flex gap-2 align-items-center">
								<input class="form-control {$_oItem.class}" type="text" name="info_more[{$key}][value]" value="{$_oItem.value}" placeholder="{$_oItem.placeholder}">
							</div>
						</div>
					{/foreach}
				</div>
				<div class="form-group form-row">
					<div class="col-md-4">
						<label class="col-form-label">Hình ảnh đại diện</label>
						<div class="input-group">
							<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh đại diện" 
							id="isoman_url_image" value="{$oneBlock.image}">
							<div class="input-group-btn"><button class="btn btn-icon btn-default ajOpenDialog" isoman_for_id="image" isoman_val="{$oneBlock.image}" isoman_name="image">{$core->makeIcon('image')}</button></div>	
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Mặt bằng fullsize</label>
						<div class="input-group">
							<input type="text" class="form-control" name="layout_ms" placeholder="Mặt bằng fullsize" 
								id="isoman_url_layout_ms" value="{$more_information.layout_ms}">
							<div class="input-group-btn"><button class="btn btn-icon btn-default ajOpenDialog" isoman_for_id="layout_ms" isoman_val="{$more_information.layout_ms}" isoman_name="layout_ms">{$core->makeIcon('image')}</button></div>	
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Mặt bằng<span class="text-red">*</span></label>
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Layout phân khu" name="layout_ns" 
								id="layout_block_{$toId}" value="{if !empty($more_information.layout_ns)}{$more_information.layout_ns}{/if}" />
							<div class="input-group-btn"><button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="layout_block_{$toId}" toId="{$toId}"><i class="fa fa-upload"></i></button></div>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-3">
						<label class="col-form-label">VR360<span class="text-red">*</span></label>
						<input type="text" class="form-control" placeholder="Nhập URL VR360" name="vr_link" value="{if !empty($more_information.vr_link)}{$more_information.vr_link}{/if}" />	
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Nguồn VR360<span class="text-red">*</span></label>
						<input type="text" class="form-control" placeholder="Nhập nguồn VR360" name="vr_source" value="{if !empty($more_information.vr_source)}{$more_information.vr_source}{/if}" />	
					</div>
					<div class="col-xs-12 col-md-3">
						<div class="form-row">
							<div class="col-xs-6 col-md-6">
								<label for="" class="col-form-label">{$core->get_Lang('BgColor')}</label>
								<input type="color" class="form-control required" placeholder="Màu nền" 
								name="bgcolor" value="{$more_information.bgcolor}">
							</div>
							<div class="col-xs-6 col-md-6">
							<label for="" class="col-form-label">{$core->get_Lang('TextColor')}</label>
								<input type="color" class="form-control required" placeholder="Màu chữ" 
								name="textcolor" value="{$more_information.textcolor}">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<label class="col-form-label mr-3">Giám đốc dự án</label>
						<select placeholder="Giám đốc dự án" name="project_manager" class="form-control iso-select2">
							<option value="0">--Chọn--</option>
							{foreach from=$list_profile item=_oProfile key=key name=i}
								<option value="{$_oProfile.profile_id}" {if $more_information.project_manager eq $_oProfile.profile_id}selected{/if}>{$_oProfile.full_name}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-6">
						<label class="col-form-label mr-3">Admin dự án</label>
						<select data-placeholder="Admin dự án" multiple="true" name="project_admins[]" class="form-control iso-select2">
							<option value="0">--Chọn--</option>
							{foreach from=$list_profile item=_oProfile key=key name=i}
								<option{if $clsISO->checkInArray($arr_project_admins, $_oProfile.profile_id)} selected{/if} value="{$_oProfile.profile_id}">{$_oProfile.full_name}</option>
							{/foreach}
						</select>
					</div>
					<div class="col-md-3">
						<label class="col-form-label mr-3">Nhân viên CS:</label>
						<select placeholder="Gõ tên [OR] email để tìm kiếm" name="stock_support_id" 
						class="form-control iso-selectizeLiveSearch" data-url="{$PCMS_URL}/index.php?mod=member&act=get_member_search">
							{if !empty($more_information.stock_support_id)}
							<option value="{$more_information.stock_support_id}" selected="selected">{$clsMember->getFullName($more_information.stock_support_id)}</option>
							{/if}
						</select>
					</div>
					<div class="col-md-3">
						<label class="col-form-label mr-3">Loại giao dịch:</label>
						<select placeholder="" name="billing_type" class="form-control iso-select2">
							{$clsProperty->getSelectByProperty('_BILLING_TYPE',$more_information.billing_type)}
						</select>
					</div>
				</div>
				<div class="p-3 bg-gray radius-3">
					<div class="form-group form-row">
						<div class="col-xs-12 col-lg-6">
							<label class="col-form-label">Link Tiles</label>
							<input class="form-control" name="tiles_link" value="{$more_information.tiles_link}" maxlength="255" />
						</div>
						<div class="col-xs-12 col-lg-6">
							<label class="col-form-label">Config Tiles</label>
							<div class="form-row">
								<div class="col-xs-12 col-lg-6">
									<div class="d-flex gap-2 align-items-center">
										<input type="hidden" name="is_tiles" value="0" />
										<label class="switch">
											<input type="checkbox" name="is_tiles"{if $more_information.is_tiles eq '1'} checked{/if} value="1">
											<span class="slider round"></span>
										</label>
										<Span>Sử dụng tiles</span>
									</div>
								</div>
								<div class="col-xs-12 col-lg-6">
									<div class="d-flex gap-2 align-items-center">
										<input type="hidden" name="is_map_tiles" value="0" />
										<label class="switch">
											<input type="checkbox" name="is_map_tiles"{if $more_information.is_map_tiles eq '1'} checked{/if} value="1">
											<span class="slider round"></span>
										</label>
										<Span>Map tiles</span>
									</div>
								</div>
							</div>
						</div>							
					</div>
					<div class="form-group form-row">
						<div class="col-md-3">
							<label class="col-form-label">Max Zoom</label>
							<input type="number" class="form-control" name="max_zoom" value="{$more_information.max_zoom}" maxlength="255" />
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Vị trí trung tâm</label>
							<input class="form-control" name="center_point" value="{$more_information.center_point}" maxlength="255" />
						</div>
						<div class="col-md-3">
							<label class="col-form-label">Vùng bound</label>
							<input class="form-control" name="max_bound" value="{$more_information.max_bound}" maxlength="255" />
						</div>
						<div class="col-md-3">
							<label class="col-form-label">TMS</label>
							<select class="form-control" name="tms_enable" value="">
								<option{if $more_information.tms_enable eq '0'} selected{/if} value="0">NO</option>
								<option{if $more_information.tms_enable eq '1'} selected{/if} value="1">YES</option>
							</select>
						</div>
					</div>
				</div>
				<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
					<li class="nav-item active">
						<a href="#content" class="nav-link" data-toggle="tab" role="tab">Giới thiệu</a>
					</li>
					<li class="nav-item">
						<a href="#csbh" class="nav-link" data-toggle="tab" role="tab">Chính sách bán hàng</a>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade py-3 active in" id="content" role="tabpanel" aria-labelledby="content-tab">
						<div class="widget-block mb-5">
							<div class="widget-header">
								<div class="d-flex align-items-center justify-content-between">
									<strong class="mb-0">Tổng quan</strong>
									<a onClick="add_property(this, event)" project_id="{$pvalTable}" _openFrom="_block" _holderG="_attrs">+ Thêm</a>
								</div>
							</div>
							<div class="widget-content p-0">
								<table width="100%" class="table table-vertical mb-0 table-stripped">
									<thead><tr>
										<th width="5%">No.</th>
										<th width="35%">Tên thuộc tính</th>
										<th width="55%">Giá trị</th>
										<th width="5%"></th>
									</tr></thead>
									<tbody class="tbody_attrs no_group">
										{if !empty($more_information.attrs)}
											{foreach from = $more_information.attrs name=i key=uid item = _Item}
											<tr id="{$uid}" class="tr_attrs">
												<td class="text-center">{$smarty.foreach.i.iteration}</td>
												<td class="text-center">
													<input class="form-control title_field_{$uid}" name="attrs[{$uid}][title]" placeholder="Nhập tiêu đề" value="{$_Item.title}" type="text" />
												</td>
												<td class="text-center">
													<input class="form-control content_field_{$uid}" name="attrs[{$uid}][content]" placeholder="Nhập giá trị" value="{$_Item.content}" type="text" />
												</td>
												<td class="text-center">
													<a class="btn btn-default" title="Xóa" href="javascript:void(0);" uid="{$uid}" onClick="delete_property(this, event)">{$core->makeIcon('trash')}</a>
												</td>
											</tr>
											{/foreach}
										{else}
											{assign var = uid value = $clsISO->getUniqid()}
											<tr id="{$uid}" class="tr_attrs">
												<td class="text-center">1</td>
												<td class="text-center">
													<input class="form-control title_field_{$uid}" name="attrs[{$uid}][title]" placeholder="Nhập tiêu đề" type="text" /></td>
												<td class="text-center">
													<input class="form-control content_field_{$uid}" name="attrs[{$uid}][content]" placeholder="Nhập giá trị" type="text" />
												</td>
												<td class="text-center">
													<a class="btn btn-default" title="Xóa" href="javascript:void(0);" uid="{$uid}" onClick="delete_property(this, event)">{$core->makeIcon('trash')}</a>
												</td>
											</tr>
										{/if}
									</tbody>
								</table>
							</div>
						</div>
						<div class="widget-block">
							<div class="widget-header">
								<strong class="mb-0">Giới thiệu</strong>
							</div>
							<div class="widget-content p-0">
								<textarea id="{$clsISO->getUniqid()}" style="width:100%" class="isoTextArea" data-name="intro" cols="255" rows="10">{if $block_id gt '0'}{$oneBlock.intro}{/if}</textarea>
							</div>
						</div>
					</div>
					<div class="tab-pane fade py-3" id="csbh" role="tabpanel" aria-labelledby="csbh-tab">
						<table class="table table-bordered">
							<thead><tr>
								<th class="align-center text-left">Tiêu đề</th>
								<th class="align-center text-left">Giá trị</th>
								<th class="align-center text-left" width="300px">Tòa áp dụng</th>
								<th class="align-center text-center" width="60px">...</th>
							</tr></thead>
							{if !empty($more_information.sales_policy)}
								{foreach from=$more_information.sales_policy name=i key=gId item = _oPolicy }
								<tr class="tr_csbh">
									<td class="text-left">
										<input type="text" name="sales_policy[{$gId}][title]" 
											class="form-control" placeholder="Tiêu đề" value="{$_oPolicy.title}" />
									</td>
									<td class="text-left">
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Layout tòa nhà" name="sales_policy[{$gId}][image]" id="sales_policy_{$gId}" value="{$_oPolicy.image}" />
											<div class="input-group-btn">
												<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="sales_policy_{$gId}" toId="{$toId}"><i class="fa fa-upload"></i></button>
											</div>
										</div>
									</td>
									<td width="100px" class="text-left">
										<select class="form-control iso-select2" 
											name="sales_policy[{$gId}][building][]" multiple="true">
											{if !empty($list_buildings)}
												{foreach from=$list_buildings item = _obuilding}
												<option{if $clsISO->checkInArray($_oPolicy.building, $_obuilding.property_id)} selected{/if} value="{$_obuilding.property_id}">{$_obuilding.title}</option>
												{/foreach}
											{/if}
										</select>
									</td>
									<td class="text-center">
										{if $smarty.foreach.i.first}
										<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.add_policy(this, event)" block_id="{$block_id}" holderG="_block" toId="{$toId}" project_id="{$pvalTable}">{$core->makeIcon('plus')}</button>
										{else}
										<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_policy(this, event)" toId="{$toId}" project_id="{$pvalTable}">{$core->makeIcon('trash')}</button>
										{/if}
									</td>
								</tr>
								{/foreach}
							{else}
								<tr class="tr_csbh">
									<td class="text-left">
										<input type="text" name="sales_policy[{$toId}][title]" 
											class="form-control" placeholder="Tiêu đề" />
									</td>
									<td class="text-left">
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Hình ảnh CSBH" name="sales_policy[{$toId}][image]" id="sales_policy_{$toId}" value="" />
											<div class="input-group-btn">
												<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="sales_policy_{$toId}" toId="{$toId}"><i class="fa fa-upload"></i></button>
											</div>
										</div>
									</td>
									<td width="100px" class="text-left">
										<select class="form-control iso-select2" 
											name="sales_policy[{$toId}][building][]" multiple="true">
											{if !empty($list_buildings)}
												{foreach from=$list_buildings item = _obuilding}
												<option value="{$_obuilding.property_id}">{$_obuilding.title}</option>
												{/foreach}
											{/if}
										</select>
									</td>
									<td class="text-center">
										<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.add_policy(this, event)" block_id="{$block_id}" toId="{$toId}" holderG="_block" project_id="{$pvalTable}">{$core->makeIcon('plus')}</button>
									</td>
								</tr>
							{/if}
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="pop_save_block(this, event)" block_id="{$block_id}" project_id="{$project_id}" _openFrom="{$_openFrom}"{if $_openFrom eq '_stock'} toId="{$toId}"{/if}>Cập nhật</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>
{else}
	<table width="100%" class="table table-vertical mb-0 table-stripped">
		<thead><tr>
			<th width="4%">No.</th>
			<th>Tên phân khu</th>
			<th width="25%"></th>
			<th class="text-center" width="60px">Vị trí</th>
			<th width="5%"></th>
		</tr></thead>
		{if !empty($list_blocks)}
			{section name=i loop=$list_blocks}
			{assign var = _block_id value = $list_blocks[i].property_id}
			{assign var = list_buildings value = $list_blocks[i].list_buildings}
			<tbody class="tbodyProject">
				<tr class="tr_selected">
					<td class="text-center">{$smarty.section.i.iteration}</td>
					<td class="text-left"><strong>{$list_blocks[i].title}</strong></td>
					<td class="text-left"><a href="javascript:void(0)" project_id="{$project_id}" block_id="{$list_blocks[i].property_id}" title="Thêm tòa nhà" data-toggle="tooltip" onClick="open_building(this, event)">+ Thêm</a></td>
					<td class="text-center">
						<input type="text" class="form-control numberonly" name="order_no" onChange="$Core.project.updateOrder(this,event)" project_id="{$project_id}" block_id="{$list_blocks[i].property_id}" value="{$list_blocks[i].order_no}">
					</td>
					<td class="text-center">
						<div class="btn-group">
							<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
							<ul class="dropdown-menu" style="right:0px !important;left:auto; min-width:130px">
								<li><a title="Chỉnh sửa" href="javascript:void(0);" uid="{$uid}" onClick="open_block(this, event)" block_id="{$list_blocks[i].property_id}" project_id="{$project_id}">{$core->makeIcon('pencil', 'Chỉnh sửa')}</a></li>
								<li><a title="Tiến độ" href="javascript:void(0);" uid="{$uid}" onClick="open_progress(this, event)" block_id="{$list_blocks[i].property_id}" project_id="{$project_id}">{$core->makeIcon('bars', 'Tiến độ')}</a></li>
								<li><a title="Xóa" href="javascript:void(0);" uid="{$uid}" block_id="{$list_blocks[i].property_id}" project_id="{$project_id}" onClick="delete_block(this, event)">{$core->makeIcon('trash', 'Xóa')}</a></li>
							</ul>
						</div>
					</td>
				</tr>
				{if !empty($list_buildings)}
				<tbody class="tbodyBlock tbodyBlock_{$_block_id}">
					{foreach from = $list_buildings item = _obuilding}
					<tr id="{$_obuilding.property_id}">
						<td><a class="mySortableHandler" href="javascript:void(0)">{$core->makeIcon('arrows')}</a></td>
						<td>{$_obuilding.title}{if $_obuilding.is_trash eq '1'} [Trashed]{/if}</td>
						<td><a href="{$PCMS_URL}/index.php?mod=stock&project_id={$project_id}&block_id={$list_blocks[i].property_id}&building_id={$_obuilding.property_id}" title="QL.Tình trạng bán hàng tòa nhà" data-toggle="tooltip"  class="label label-default">Q.Lý</a></td>
						<td class="text-center"></td>
						<td class="text-center">
							<div class="btn-group">
								<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
								<ul class="dropdown-menu" style="right:0px !important;left:auto; min-width:130px">
									<li><a href="javascript:void(0);" title="Chỉnh sửa" uid="{$uid}" onClick="open_building(this, event)" block_id="{$list_blocks[i].property_id}" project_id="{$project_id}" building_id="{$_obuilding.property_id}">{$core->makeIcon('pencil', 'Chỉnh sửa')}</a></li>
									<li><a title="Tiến độ" href="javascript:void(0);" uid="{$uid}" onClick="open_progress(this, event)" block_id="{$list_blocks[i].property_id}" project_id="{$project_id}" building_id="{$_obuilding.property_id}">{$core->makeIcon('bars', 'Tiến độ')}</a></li>
									<li><a href="javascript:void(0);" title="Xóa" class="btn_delete_building_{$_obuilding.property_id}{if $_obuilding.is_locked eq '1'} disabled{/if}" uid="{$uid}" onClick="delete_building(this, event)" block_id="{$list_blocks[i].property_id}" project_id="{$project_id}" building_id="{$_obuilding.property_id}">{$core->makeIcon('trash', 'Xóa')}</a></li>
								</ul>
							</div>
						</td>
					</tr>
					{/foreach}
				</tbody>
				{/if}
			</tbody>
			{/section}
		{/if}		
	</table>
{/if}