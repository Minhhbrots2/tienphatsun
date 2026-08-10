<div class="modal-dialog{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE} modal-lg{/if}">

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

				{if $stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}

					<div class="form-group form-row">

						<div class="col-md-3">

							<label class="col-form-label">Mã dãy<span class="text-red">*</span></label>

							<input type="text" class="form-control required" placeholder="Mã dãy nhà" name="property_code" value="{$oneBuilding.property_code}" />

						</div>

						<div class="col-md-6">

							<label class="col-form-label">Tên dãy<span class="text-red">*</span></label>

							<input type="text" class="form-control required" placeholder="Nhập dãy nhà" name="title" value="{$oneBuilding.title}" />

						</div>

						<div class="col-md-3">

							<label class="col-form-label">Phân khu<span class="text-red">*</span></label>

							<select class="form-control required  iso-select2" name="ns_block_id">

								{if !empty($list_blocks)}

									{foreach from=$list_blocks item = _oBlock}

									<option{if $block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>

									{/foreach}

								{/if}

							</select>

						</div>

					</div>

					<div class="form-group">

						<label class="col-form-label">Giới thiệu</label>

						<textarea class="form-control" placeholder="Giới thiệu" name="intro"></textarea>

					</div>

				{else}

				<div class="form-group form-row">

					<div class="col-md-2">

						<label class="col-form-label">Mã tòa nhà<span class="text-red">*</span></label>

						<input type="text" class="form-control required" placeholder="Mã tòa nhà" name="property_code" value="{$oneBuilding.property_code}" />

					</div>

					<div class="col-md-2">

						<label class="col-form-label">Tên tòa nhà<span class="text-red">*</span></label>

						<input type="text" class="form-control required" placeholder="Nhập tòa nhà" name="title" value="{$oneBuilding.title}" />

					</div>

					<div class="col-md-2">

						<label class="col-form-label">Tên rút gọn<span class="text-red">*</span></label>

						<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title_vn" value="{$oneBuilding.title_vn}">

					</div>

					<div class="col-md-3">

						<label class="col-form-label">Mẫu mã căn hộ<span class="text-red">*</span></label>

						<textarea id="inputor" style="height:34px" onchange="$(this).val($(this).val().replace('%',''))" title="Gõ % để lựa chọn" data-toggle="tooltip" class="form-control disabled-resize-y required" placeholder="Mẫu mã căn hộ" name="stock_template">{if $building_id gt '0'}{$more_information.stock_template}{/if}</textarea>

					</div>

					<div class="col-md-1">

						<label class="col-form-label">Tầng/tòa<span class="text-red">*</span></label>

						<input type="number" class="form-control required" placeholder="Số căn hộ/tầng" name="number_floor" value="{if !empty($more_information.number_floor)}{$more_information.number_floor}{/if}" />

					</div>

					<div class="col-md-2">

						<label class="col-form-label">Căn hộ/tầng<span class="text-red">*</span></label>

						<input type="number" class="form-control required" placeholder="Số căn hộ/tầng" name="number_house" value="{if !empty($more_information.number_house)}{$more_information.number_house}{/if}" />

					</div>

				</div>

				<div class="alert p-3 alert-info">

					Ghi chú<br />

					<ul>

						<li>Mẫu mã căn hộ: Mẫu được định nghĩa để tự động sinh mã căn hộ</li>

						<li>Số căn hộ/tầng: Số căn hộ mở bán trên mỗi tầng</li>

						<li>Tạo bảng hàng: Hệ thống sẽ dựa vào số tầng, mẫu thuộc tính căn hộ để tạo bảng hàng ban đầu.</li>

					</ul>

				</div>

				<div class="form-group form-row">

					<div class="col-xs-12 col-md-3">

						<label class="col-form-label">Số thang máy</label>

						<input type="text" class="form-control" placeholder="Số thang máy" name="number_of_elevator" value="{$more_information.number_of_elevator}">

					</div>

					<div class="col-xs-12 col-md-3">

						<label class="col-form-label">Số tầng hầm</label>

						<input type="text" class="form-control" placeholder="Số tầng hầm" name="number_of_besement" value="{$more_information.number_of_besement}">

					</div>

					<div class="col-xs-12 col-md-3">

						<label class="col-form-label">Phong cách xây dựng</label>

						<input type="text" class="form-control" placeholder="Nhập tiêu đề" name="construction_style" value="{$more_information.construction_style}">

					</div>

					<div class="col-xs-12 col-md-3">

						<label class="col-form-label">Thời gian bàn giao</label>

						<input type="text" class="form-control" placeholder="Tháng/năm" name="handover_time" value="{$more_information.handover_time}">

					</div>

				</div>

				<div class="form-group form-row">

					<div class="col-xs-12 col-md-2">

						<label class="col-form-label">Khoảng giá</label>

						<input type="text" class="form-control" placeholder="Giá bán" name="price_range" value="{$more_information.price_range}">

					</div>

					<div class="col-xs-12 col-md-2">

						<label class="col-form-label">Mở bán</label>

						<div class="d-flex align-items-center gap-2">

							<label class="switch">

								<input type="checkbox" name="on_sale"{if !empty($more_information.on_sale) && $more_information.on_sale eq '1'} checked{/if} value="1">

								<span class="slider round"></span>

							</label>

							<span class="text-muted">Mở bán</span>

						</div>

					</div>

					<div class="col-xs-12 col-md-2">

						<label class="col-form-label">Symbol tòa</label>

						<div class="d-flex align-items-center gap-2">

							<label class="switch">

								<input type="checkbox" name="is_symbol"{if !empty($more_information.is_symbol) && $more_information.is_symbol eq '1'} checked{/if} value="1">

								<span class="slider round"></span>

							</label>

							<span class="text-muted">Có Symbol</span>

						</div>

					</div>

					<div class="col-xs-12 col-md-2">

						<label class="col-form-label">Symbol tầng</label>

						<div class="d-flex align-items-center gap-2">

							<label class="switch">

								<input type="checkbox" name="is_symbol_floor"{if !empty($more_information.is_symbol_floor) && $more_information.is_symbol_floor eq '1'} checked{/if} value="1">

								<span class="slider round"></span>

							</label>

							<span class="text-muted">Có Symbol</span>

						</div>

					</div>

					<div class="col-xs-12 col-md-4">

						<label class="col-form-label">Tầng thứ cấp</label>

						<select name="floor_hierarchy[]" id="" class="iso-select2" multiple>

							{$clsISO->getFloor($more_information.number_floor,$more_information.floor_hierarchy)}

						</select>

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

					<div class="col-md-6">

						<label class="col-form-label">Tiêu đề bảng hàng</label>

						<input type="text" class="form-control" placeholder="Tiêu đề bảng hàng" 

						name="title_ts" value="{if !empty($more_information.title_ts)}{$more_information.title_ts}{/if}" />

					</div>

					<div class="col-md-6">

						{assign var = for_id value = $clsISO->getUniqid()}

						<label class="col-form-label">Danh sách tầng <a href="javascript:void(0);" 

						onClick="gen_floor(this, event)" data-toggle="tooltip" title="Tạo nhanh danh sách tầng" 

						toId="{$for_id}">{$core->makeIcon('plus-circle')}</a> (cách nhau bởi dấu [,])</label>

						<input autocomplete="off" class="{$for_id} form-control required" placeholder="Số tầng" name="floor" 

						value="{if !empty($more_information.floor)}{$more_information.floor}{/if}" />

					</div>

				</div>

				<div class="form-group form-row">

					<div class="col-md-6">

						<label class="col-form-label">Hình ảnh</label>

						<div class="input-group">

							<input type="text" class="form-control" name="image" placeholder="Chọn hình ảnh đại diện" id="isoman_url_image" value="{$oneBuilding.image}">

							<div class="input-group-btn">

								<button class="btn btn-default ajOpenDialog" isoman_for_id="image" isoman_val="{$oneBuilding.image}" isoman_name="image" style="padding:9px 10px">{$core->makeIcon('image')}</button>

							</div>	

						</div>

					</div>

					<div class="col-md-6">

						<label class="col-form-label mr-3">Nhân viên CS:</label>

						<select placeholder="Gõ tên [OR] email để tìm kiếm" name="stock_support_id" 

						class="form-control iso-selectizeLiveSearch" data-url="{$PCMS_URL}/index.php?mod=member&act=get_member_search">

							{if !empty($more_information.stock_support_id)}

							<option value="{$more_information.stock_support_id}" selected="selected">{$clsMember->getFullName($more_information.stock_support_id)}</option>

							{/if}

						</select>

					</div>

				</div>

				<div class="form-group form-row">

					<div class="col-md-6">

						<label class="col-form-label">Layout Map</label>

						<div class="input-group">

							<input type="text" class="form-control" name="layout_map" placeholder="Chọn hình ảnh" 

								id="isoman_url_layout_map" value="{$more_information.layout_map}">

							<div class="input-group-btn">

								<button class="btn btn-default ajOpenDialog" isoman_for_id="layout_map" isoman_val="{$more_information.layout_map}" isoman_name="layout_map" style="padding:9px 10px">{$core->makeIcon('image')}</button>

							</div>	

						</div>

					</div>

					<div class="col-md-6">

						<label class="col-form-label">Layout Map độc quyền</label>

						<div class="input-group">

							<input type="text" class="form-control" name="layout_map_FH" placeholder="Chọn hình ảnh" 

								id="isoman_url_layout_map_FH" value="{$more_information.layout_map_FH}">

							<div class="input-group-btn">

								<button class="btn btn-default ajOpenDialog" isoman_for_id="layout_map_FH" isoman_val="{$more_information.layout_map_FH}" isoman_name="layout_map_FH" style="padding:9px 10px">{$core->makeIcon('image')}</button>

							</div>	

						</div>

					</div>					

					{*<div class="col-md-3">

						<label class="col-form-label">VR360<span class="text-red">*</span></label>

						<input type="text" class="form-control" placeholder="Nhập URL VR360" name="vr_link" value="{if !empty($more_information.vr_link)}{$more_information.vr_link}{/if}" />	

					</div>

					<div class="col-md-3">

						<label class="col-form-label">Nguồn VR360<span class="text-red">*</span></label>

						<input type="text" class="form-control" placeholder="Nhập nguồn VR360" name="vr_source" value="{if !empty($more_information.vr_source)}{$more_information.vr_source}{/if}" />	

					</div>*}

				</div>

				<div class="form-row form-group">

					<div class="col-md-3">

						<div class="d-flex align-items-center bg-gray p-3 radius-4">

							<label class="col-form-label mr-3">Không cho phép xóa:</label>

							<label class="switch">

								<input type="checkbox" name="is_locked" value="1"{if $oneBuilding.is_locked eq '1'} checked{/if}>

								<span class="slider round"></span>

							</label>

						</div>

					</div>

					<div class="col-md-3">

						<div class="d-flex align-items-center bg-gray p-3 radius-4">

							<label class="col-form-label mr-3">Ẩn bảng hàng:</label>

							<label class="switch">

								<input type="checkbox" name="is_trash" value="1"{if $oneBuilding.is_trash eq '1'} checked{/if}>

								<span class="slider round"></span>

							</label>

						</div>

					</div>

					<div class="col-md-3">

						<div class="d-flex align-items-center bg-gray p-3 radius-4">

							<label class="col-form-label mr-3">Ẩn header lặp:</label>

							<label class="switch">

								<input type="checkbox" name="hide_row_floor_special" value="1"{if $more_information.hide_row_floor_special eq '1'} checked{/if}>

								<span class="slider round"></span>

							</label>

						</div>

					</div>

					<div class="col-md-3">

						<a class="btn btn-block btn-lg btn-default" onClick="$Core.project.open_setup_floor(this, event)" 

						building_id="{$building_id}">{$core->makeIcon('cog', 'Cài đặt tầng đặc biệt')}</a>

					</div>

				</div>

				<ul class="nav nav-tabs nav-tabs-bordered" id="myTab" role="tablist">

					<li class="nav-item active">

						<a href="#content" class="nav-link" data-toggle="tab" role="tab">Giới thiệu</a>

					</li>

					<li class="nav-item">

						<a href="#profile" class="nav-link" data-toggle="tab" role="tab">Mẫu thuộc tính căn hộ</a>

					</li>

					<li class="nav-item">

						<a href="#layout" class="nav-link" data-toggle="tab" role="tab">Layout</a>

					</li>

					<li class="nav-item">

						<a href="#floor_range" class="nav-link" data-toggle="tab" role="tab">Khoảng tầng</a>

					</li>

					<li class="nav-item d-none">

						<a href="#layout_map_floor" class="nav-link" data-toggle="tab" role="tab">Layout map tầng</a>

					</li>

					<li class="nav-item d-none">

						<a href="#csbh" class="nav-link" data-toggle="tab" role="tab">CSBH</a>

					</li>

				</ul>

				<div class="tab-content">

					<div class="tab-pane fade py-3 active in" id="content" role="tabpanel" aria-labelledby="content-tab">

						<div class="widget-block mb-5">

							<div class="widget-header">

								<div class="d-flex align-items-center justify-content-between">

									<strong class="mb-0">Tổng quan</strong>

									<a onClick="add_property(this, event)" project_id="{$pvalTable}" _openFrom="_building" _holderG="_attrs">+ Thêm</a>

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

													<a class="btn btn-icon btn-default" title="Xóa" href="javascript:void(0);" uid="{$uid}" onClick="delete_property(this, event)">{$core->makeIcon('trash')}</a>

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

													<a class="btn btn-icon btn-default" title="Xóa" href="javascript:void(0);" uid="{$uid}" onClick="delete_property(this, event)">{$core->makeIcon('trash')}</a>

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

								<textarea id="{$clsISO->getUniqid()}" data-name="intro" class="isoTextArea" rows="15" cols="255" style="width:100%">{if $building_id gt '0'}{$oneBuilding.intro}{/if}</textarea>

							</div>

						</div>

					</div>

					<div class="tab-pane py-3 fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

						<div class="d-flex mb-3 align-items-center">							

							<button type="button" class="btn btn-default mr-2" toId="{$toId}" onClick="load_template_building(this, event)" building_id="{$building_id}" project_id="{$project_id}">{$core->makeIcon('plus-circle','Tạo căn hộ')}</button>

							<button type="button" class="btn btn-default mr-2" toId="{$toId}" onClick="add_template_line(this, event)" building_id="{$building_id}" project_id="{$project_id}">{$core->makeIcon('plus-circle','Thêm dòng')}</button>

							<button type="button" block_id="{$block_id}" toId="{$toId}" building_id="{$building_id}" class="btn btn-default mr-2" onClick="$Core.project.open_copyfrom_building(this, event)" project_id="{$project_id}">{$core->makeIcon('clipboard','Copy từ tòa khác')}</button>

							<button type="button" block_id="{$block_id}" toId="{$toId}" class="btn btn-default mr-2 d-none" onClick="open_copypaste_excel(this, event)" project_id="{$project_id}">{$core->makeIcon('clipboard','Copy/Paste')}</button>

							{if $building_id gt '0'}<button type="button" block_id="{$block_id}" class="btn btn-default mr-2" onClick="start_create_stock(this, event)" holderG="create" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}">{$core->makeIcon('plus','Tạo bảng hàng')}</button>

							<button type="button" block_id="{$block_id}" class="btn btn-default" holderG="update" onClick="start_create_stock(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}">{$core->makeIcon('clipboard','Cập nhật bảng hàng')}</button>

							{/if}

						</div>

						<div class="holder_template_table_floor mt-3">	

							<div class="d-flex d-flex align-items-start">

								<ul class="nav nav-tabs nav-tabs-bordered list_head_tab flex-wrap">

									<li class="nav-item active nav-item_tab_floor" key="general"><a class="nav-link" data-toggle="tab" href="#tab_general">Tầng chung</a></li>

									{foreach from=$more_information.floor_specical item=floor_specical key=key name=i}

										<li class="nav-item nav-item_tab_floor" key="{$key}">

											<a class="nav-link" data-toggle="tab" href="#tab_{$key}"><span class="txt_nav">Tầng {$floor_specical}</span> 

												<button class="btn btn-sm btn-default ml-2 border-0 px-1" type="button" title="Xóa tầng" onclick="$Core.project.deleteFloorSpecical(this,event)" toId="{$key}"><i class="fa fa-minus-circle" aria-hidden="true"></i></button>

												<button class="btn btn-sm btn-default ml-1 border-0 px-1" type="button" title="Sửa tầng" onclick="$Core.project.gen_floor(this,event)" toId="{$key}" data-type="_EDIT"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

											</a>											

										</li>

									{/foreach}

								</ul>

								<button class="btn btn-icon ml-2 border" type="button" onClick="$Core.project.gen_floor(this,event)" data-type="_ADD" data-toggle="tooltip" title="" toid="{$uid}" data-original-title="Thêm mới tầng đặc biệt" building_id='{$building_id}' project_id="{$project_id}" style="background: #FFF"><i class="fa fa-plus" aria-hidden="true"></i></button>

								<div class="lst_input_specical">

								{if !empty($more_information.floor_specical)}

									{foreach from=$more_information.floor_specical item=floor_specical key=key name=i}

										<input type="hidden" autocomplete="off" class="form-control required mr-2 floor_specical {$key}" placeholder="Số tầng" name="floor_specical[{$key}]" value="{$floor_specical}">

									{/foreach}

								{/if}

								</div>

								{*<div class="lst_input_number_house_specical">

								{if !empty($more_information.number_house_specical)}

									{foreach from=$more_information.number_house_specical item=number_house_specical key=key name=i}

										<input type="hidden" autocomplete="off" class="form-control required mr-2 number_house_specical {$key}" placeholder="Số căn/tầng" name="number_house_specical[{$key}]" value="{$number_house_specical}">

									{/foreach}

								{/if}

								</div>*}

							</div>

							<div class="tab-content list_content_tab">

								<div id="tab_general" class="tab-pane fade in active overflow-x-auto holder_template_building">

									<table class="table no-maxwidth" cellpadding="0" cellspacing="0" style="width: calc(100% + 200px)">

										<thead><tr>

											<th width="7%">Căn số</th>

											<th width="7%">Symbol</th>

											<th width="10%">Số PN</th>

											<th width="10%">Hướng BC</th>

											<th width="10%">DT_TT(m2)</th>

											<th width="10%">DT_Tim(m2)</th>

											<th width="10%">View</th>

											<th width="15%">Layout</th>

											<th width="15%">Layout chi tiết</th>

											<th width="15%">Video</th>

											<th width="45px"></td>

										</tr></thead>

										<tbody class="holder_template_general_building">

											{if !empty($more_information.template)}

												{foreach from = $more_information.template key = _code item = _oItem}

													<tr class="tr_template_{$building_id}">

														<td class="text-left">

															<input type="text" name="template[{$_code}][code]" class="form-control" value="{$_oItem.code}" />

														</td>

														<td class="text-left">

															<input type="text" name="template[{$_code}][symbol]" class="form-control" value="{$_oItem.symbol}" />

														</td>

														<td class="text-left">

															<select class="form-control iso-select2" name="template[{$_code}][bedroom_id]">

																{$clsProperty->getSelectOptimizeProperty('_BEDROOM',$_oItem.bedroom_id,$arrBedRooms)}

															</select>

														</td>

														<td class="text-left">

															<select class="form-control iso-select2" name="template[{$_code}][home_direction_id]">

																{$clsProperty->getSelectOptimizeProperty('_DIRECTION', $_oItem.home_direction_id,$arrDirections)}

															</select>

														</td>

														<td class="text-left">

															<div class="input-group-suffix">

																<input type="text" name="template[{$_code}][DT_TT]" class="form-control w-100px" value="{$_oItem.DT_TT}" />

																<span class="suffix">m2</span>

															</div>

														</td>

														<td class="text-left">

															<div class="input-group-suffix">

																<input type="text" name="template[{$_code}][DT_Tim]" class="form-control w-100px" value="{$_oItem.DT_Tim}" />

																<span class="suffix">m2</span>

															</div>

														</td>

														<td class="text-left">

															<select class="form-control iso-select2" name="template[{$_code}][view_id]">

																{$clsProperty->getSelectOptimizeProperty('_VIEW',$_oItem.view_id, $arrViews)}

															</select>

														</td>

														<td width="15%">

															<div class="input-group">

																<input type="text" id="layout_{$_code}_{$toId}" name="template[{$_code}][layout]" class="form-control" value="{$_oItem.layout}" placeholder="URL Layout" />

																<div class="input-group-btn"><button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_{$_code}_{$toId}" toId="{$toId}" class="btn btn-default">{$core->makeIcon('upload','&nbsp;')}</button></div>

															</div>

														</td>

														<td width="15%">

															<div class="input-group">

																<input type="text" id="layout_ns_{$_code}_{$toId}" name="template[{$_code}][layout_ns]" class="form-control" value="{$_oItem.layout_ns}" placeholder="URL Layout" />

																<div class="input-group-btn"><button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_{$_code}_{$toId}" toId="{$toId}" class="btn btn-default">{$core->makeIcon('upload','&nbsp;')}</button></div>

															</div>

														</td>

														<td width="15%">

															<input type="text" name="template[{$_code}][video]" class="form-control" value="{$_oItem.video}" maxlength="255" placeholder="URL Youtube" />

														</td>

														<td class="text-center">

															<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_template_line(this, event)"><i class="fa fa-trash"></i></button>

														</td>

													</tr>

												{/foreach}

											{/if}

										</tbody>

									</table>

								</div>

								{if !empty($more_information.template_specical)}

									{foreach from=$more_information.template_specical item=template_specical key=key name=i}

										<div id="tab_{$key}" class="tab-pane fade overflow-x-auto">

											<table class="table no-maxwidth" cellpadding="0" cellspacing="0" style="width: calc(100% + 200px)">

												<thead><tr>

													<th width="7%">Căn số</th>

													<th width="7%">Symbol</th>

													<th width="10%">Số PN</th>

													<th width="10%">Hướng BC</th>

													<th width="10%">DT_TT(m2)</th>

													<th width="10%">DT_Tim(m2)</th>

													<th width="10%">View</th>

													<th width="15%">Layout</th>

													<th width="15%">Layout chi tiết</th>

													<th width="15%">Video</th>

													<th width="45px"></td>

												</tr></thead>

												<tbody>

													{if !empty($template_specical)}

														{foreach from = $template_specical key = _code item = _oItem}

															<tr class="tr_template_{$building_id}">

																<td class="text-left">

																	<input type="text" name="template_specical[{$key}][{$_code}][code]" class="form-control" value="{$_oItem.code}" />

																</td>

																<td class="text-left">

																	<input type="text" name="template_specical[{$key}][{$_code}][symbol]" class="form-control" value="{$_oItem.symbol}" />

																</td>

																<td class="text-left">

																	<select class="form-control iso-select2" name="template_specical[{$key}][{$_code}][bedroom_id]">

																		{$clsProperty->getSelectOptimizeProperty('_BEDROOM',$_oItem.bedroom_id,$arrBedRooms)}

																	</select>

																</td>

																<td class="text-left">

																	<select class="form-control iso-select2" name="template_specical[{$key}][{$_code}][home_direction_id]">

																		{$clsProperty->getSelectOptimizeProperty('_DIRECTION', $_oItem.home_direction_id,$arrDirections)}

																	</select>

																</td>

																<td class="text-left">

																	<div class="input-group-suffix">

																		<input type="text" name="template_specical[{$key}][{$_code}][DT_TT]" class="form-control w-100px" value="{$_oItem.DT_TT}" />

																		<span class="suffix">m2</span>

																	</div>

																</td>

																<td class="text-left">

																	<div class="input-group-suffix">

																		<input type="text" name="template_specical[{$key}][{$_code}][DT_Tim]" class="form-control w-100px" value="{$_oItem.DT_Tim}" />

																		<span class="suffix">m2</span>

																	</div>

																</td>

																<td class="text-left">

																	<select class="form-control iso-select2" name="template_specical[{$key}][{$_code}][view_id]">

																		{$clsProperty->getSelectOptimizeProperty('_VIEW',$_oItem.view_id, $arrViews)}

																	</select>

																</td>

																<td width="15%">

																	<div class="input-group">

																		<input type="text" id="layout_{$key}_{$_code}_{$toId}" name="template_specical[{$key}][{$_code}][layout]" class="form-control" value="{$_oItem.layout}" placeholder="URL Layout" />

																		<div class="input-group-btn"><button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_{$key}_{$_code}_{$toId}" toId="{$toId}" class="btn btn-default">{$core->makeIcon('upload','&nbsp;')}</button></div>

																	</div>

																</td>

																<td width="15%">

																	<div class="input-group">

																		<input type="text" id="layout_ns_{$key}_{$_code}_{$toId}" name="template_specical[{$key}][{$_code}][layout_ns]" class="form-control" value="{$_oItem.layout_ns}" placeholder="URL Layout" />

																		<div class="input-group-btn"><button type="button" onClick="$Core.project.select_image(this, event)" gId="layout_ns_{$key}_{$_code}_{$toId}" toId="{$toId}" class="btn btn-default">{$core->makeIcon('upload','&nbsp;')}</button></div>

																	</div>

																</td>

																<td width="15%">

																	<input type="text" name="template_specical[{$key}][{$_code}][video]" class="form-control" value="{$_oItem.video}" maxlength="255" placeholder="URL Youtube" />

																</td>

																<td class="text-center">

																	<button onClick="$Core.project.delete_template_line(this, event)" type="button" class="btn btn-icon btn-default"><i class="fa fa-trash"></i></button>

																</td>

															</tr>

														{/foreach}

													{/if}

												</tbody>

											</table>

										</div>

									{/foreach}

								{/if}

							</div>

						</div>

					</div>

					<div class="tab-pane fade py-3" id="layout" role="tabpanel" aria-labelledby="home-tab">

						<table class="table table-bordered">

							<thead><tr>

								<th class="align-center text-left">Tiêu đề</th>

								<th class="align-center text-left">Giá trị</th>

								<th class="align-center text-left" width="60px"></th>

							</tr></thead>

							<tr class="tr_layout">

								<td class="text-left">

									<input type="text" readonly value="Layout điển hình" class="form-control" />

								</td>

								<td class="text-left">

									<div class="input-group">

										<input type="text" class="form-control" placeholder="Layout tòa nhà" name="layout_ns" id="layout_building_{$toId}" value="{if !empty($more_information.layout_ns)}{$more_information.layout_ns}{/if}" />

										<div class="input-group-btn">

											<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="layout_building_{$toId}" toId="{$toId}"><i class="fa fa-upload"></i></button>

										</div>

									</div>

								</td>

								<td class="text-center">

									<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.add_layout(this, event)" toId="{$toId}" project_id="{$pvalTable}">{$core->makeIcon('plus')}</button>

								</td>

							</tr>

							{if !empty($more_information.layout_ms)}

								{foreach from=$more_information.layout_ms key = _oKey item = _oLayout}

								<tr class="tr_layout">

									<td class="text-left">

										<input type="text" name="layout_ms[{$_oKey}][title]" placeholder="Layout tầng..." class="form-control" value="{$_oLayout.title}" />

									</td>

									<td class="text-left">

										<div class="input-group">

											<input type="text" class="form-control" placeholder="Layout tòa nhà" name="layout_ms[{$_oKey}][image]" id="layout_building_{$_oKey}" value="{$_oLayout.image}" />

											<div class="input-group-btn">

												<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="layout_building_{$_oKey}" toId="{$toId}"><i class="fa fa-upload"></i></button>

											</div>

										</div>

									</td>

									<td class="text-center">

										<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.delete_layout(this, event)">{$core->makeIcon('trash')}</button>

									</td>

								</tr>

								{/foreach}

							{/if}

						</table>

					</div>

					<div class="tab-pane fade py-3" id="floor_range" role="tabpanel" 

						aria-labelledby="home-tab">

						<div class="form-row mb-2">

							{foreach from=$floor_level_configs key = _oK item = _oI}

							<div class="xol-xs-12 col-md-4">

								<label class="col-form-label">{$_oI.title}</label>

								<input type="hidden" name="floor_level_configs[{$_oK}][title]" value="{$_oI.title}" />

								<div class="p-3 border radius-3">

									<div class="form-group form-row mb-2">

										<label class="col-form-label col-xs-4 text-right">Từ tầng</label>

										<div class="col-xs-12 col-md-8">

											<input type="number" class="form-control" name="floor_level_configs[{$_oK}][from]" value="{$_oI.from}" placeholder="Số tầng" />

										</div>

									</div>

									<div class="form-group form-row mb-0">

										<label class="col-form-label col-xs-4 text-right">Tới tầng</label>

										<div class="col-xs-12 col-md-8">

											<input type="number" name="floor_level_configs[{$_oK}][to]" class="form-control" value="{$_oI.to}" placeholder="Số tầng" />

										</div>

									</div>

								</div>

							</div>

							{/foreach}

						</div>

						<table class="table table-striped" cellpadding="0" cellspacing="0">

							<thead><tr>

								<th class="align-center text-left" width="60px">STT</th>

								<th class="align-center text-left" width="20%">Loại</th>

								<th class="align-center text-left" width="20%">Loại tầng</th>

								<th class="align-center text-left">Khoảng tầng</th>

								<th class="align-center text-left" width="60px"></th>

							</tr></thead>

							<tbody class="holder_floor_range_configs">

								{if !empty($floor_range_configs)}

									{foreach from=$floor_range_configs key = uid item = _oI}

									<tr id="{$uid}" class="tr_floor_range_config">

										<td class="text-center mySortableHandler">

											<i class="fa fa-bars p-3">

										</td>

										<td class="text-left">

											<select uid="{$uid}" onClick="$Core.project.handle_floor_type_changed(this, event)" 

												name="floor_range_configs[{$uid}][floor_type]" class="form-control iso-select2">

												<option{if $_oI.floor_type eq 'consecutive'} selected{/if} value="consecutive">Liên tục(Consecutive)</option>

												<option{if $_oI.floor_type eq 'non_consecutive'} selected{/if} value="non_consecutive">Không liên tục(Non-consecutive)</option>

											</select>

										</td>

										<td class="text-left">

											<select name="floor_range_configs[{$uid}][level]" class="form-control iso-select2">

												<option{if $_oI.level eq 'low_floor'} selected{/if} value="low_floor">Tầng thấp</option>

												<option{if $_oI.level eq 'mid_floor'} selected{/if} value="mid_floor">Tầng trung</option>

												<option{if $_oI.level eq 'high_floor'} selected{/if} value="high_floor">Tầng cao</option>

											</select>

										</td>

										<td class="text-left">

											<div class="floor_range_consecutive{if $_oI.floor_type eq 'non_consecutive'} d-none{/if}">

												<div class="input-group d-flex align-items-center">

													<input type="number" placeholder="Từ tầng" name="floor_range_configs[{$uid}][from]" 

														class="form-control numberonly" value="{$_oI.from}" onClick="this.select()" />

													<input type="number" placeholder="Tới tầng" name="floor_range_configs[{$uid}][to]" 

														class="form-control numberonly" value="{$_oI.to}" onClick="this.select()" />

												</div>

											</div>

											<input type="text" placeholder="Nhập các tầng cách nhau bằng dấu (,)" name="floor_range_configs[{$uid}][floor]" value="{$_oI.floor}" class="form-control floor_range_non_consecutive{if $_oI.floor_type eq 'consecutive'} d-none{/if}" />

										</td>

										<td class="text-center">

											<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_floor_range_config(this, event)">{$core->makeIcon('trash')}</button>

										</td>

									</tr>

									{/foreach}

								{else}

									{assign var=uid value = $clsISO->getUniqid()}

									<tr id="{$uid}" class="tr_floor_range_config">

										<td class="text-center mySortableHandler">

											<i class="fa fa-bars p-3">

										</td>

										<td class="text-left">

											<select uid="{$uid}" onClick="$Core.project.handle_floor_type_changed(this, event)" 

												name="floor_range_configs[{$uid}][floor_type]" class="form-control iso-select2">

												<option value="consecutive">Liên tục(Consecutive)</option>

												<option value="non_consecutive">Không liên tục(Non-consecutive)</option>

											</select>

										</td>

										<td class="text-left">

											<select name="floor_range_configs[{$uid}][level]" class="form-control iso-select2">

												<option value="low">Tầng thấp</option>

												<option value="middle">Tầng trung</option>

												<option value="high">Tầng cao</option>

											</select>

										</td>

										<td class="text-left">

											<div class="floor_range_consecutive">

												<div class="input-group d-flex align-items-center">

													<input type="number" placeholder="Từ tầng" name="floor_range_configs[{$uid}][from]" 

														class="form-control numberonly" onClick="this.select()" />

													<input type="number" placeholder="Tới tầng" name="floor_range_configs[{$uid}][to]" 

														class="form-control numberonly" onClick="this.select()" />

												</div>

											</div>

											<input type="text" placeholder="Nhập các tầng cách nhau bằng dấu (,)" name="floor_range_configs[{$uid}][floor]" class="form-control floor_range_non_consecutive d-none" />

										</td>

										<td class="text-center">

											<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.delete_floor_range_config(this, event)">{$core->makeIcon('trash')}</button>

										</td>

									</tr>

								{/if}

							</tbody>

							<tfoot><tr>

								<td colspan="4">

									<button type="button" onClick="$Core.project.add_floor_range_config(this, event)" project_id="{$project_id}" building_id="{$building_id}" class="btn btn-default">Thêm khoảng tầng</button>

								</td>

							</tr></tfoot>

						</table>

					</div>

					<div class="tab-pane fade py-3" id="layout_map_floor" role="tabpanel" aria-labelledby="home-tab">

						<table class="table table-bordered">

							<thead><tr>

								<th class="align-center text-left">Tiêu đề</th>

								<th class="align-center text-left">Giá trị</th>

								<th class="align-center text-left" width="60px"></th>

							</tr></thead>

							{if !empty($more_information.layout_map_floor)}

								{foreach from=$more_information.layout_map_floor key = _oKey item = _oLayout name=i}

								<tr class="tr_layout">

									<td class="text-left">

										<input type="text" name="layout_map_floor[{$_oKey}][title]" placeholder="Layout tầng..." 

											class="form-control" value="{$_oLayout.title}" />

									</td>

									<td class="text-left">

										<div class="input-group">

											<input type="text" class="form-control" name="layout_map_floor[{$_oKey}][image]" placeholder="Chọn hình ảnh" 

												id="isoman_url_layout_map_floor[{$_oKey}][image]" value="{$_oLayout.image}">

											<div class="input-group-btn">

												<button class="btn btn-default ajOpenDialog" isoman_for_id="layout_map_floor[{$_oKey}][image]" isoman_val="{$_oLayout.image}" isoman_name="layout_map_floor[{$_oKey}][image]" style="padding:9px 10px">{$core->makeIcon('image')}</button>

											</div>	

										</div>

									</td>

									<td class="text-center">

										{if $smarty.foreach.i.first}

											<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.add_layout_map_floor(this, event)" project_id="{$pvalTable}">{$core->makeIcon('plus')}</button>

										{else}

											<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.delete_layout(this, event)">{$core->makeIcon('trash')}</button>

										{/if}

									</td>

								</tr>

								{/foreach}

							{else}

								{assign var=uid value=$clsISO->getUniqid()}

								<tr class="tr_layout">

									<td class="text-left">

										<input type="text" name="layout_map_floor[{$uid}][title]" placeholder="Layout map tầng..." 

											class="form-control" value="" />

									</td>

									<td class="text-left">

										<div class="input-group">

											<input type="text" class="form-control" name="layout_map_floor[{$uid}][image]" placeholder="Chọn hình ảnh" 

												id="isoman_url_layout_map_floor_{$uid}" value="">

											<div class="input-group-btn">

												<button class="btn btn-default ajOpenDialog" isoman_for_id="layout_map_floor_{$uid}" isoman_val="" isoman_name="layout_map_floor[{$uid}][image]" style="padding:9px 10px">{$core->makeIcon('image')}</button>

											</div>	

										</div>

									</td>

									<td class="text-center">

										<button type="button" class="btn btn-icon btn-default" onClick="$Core.project.add_layout_map_floor(this, event)" project_id="{$pvalTable}">{$core->makeIcon('plus')}</button>

									</td>

								</tr>

							{/if}

						</table>

					</div>

					<div class="tab-pane fade py-3" id="csbh" role="tabpanel" aria-labelledby="csbh-tab">

						<table class="table table-bordered">

							<thead><tr>

								<th class="align-center text-left">Tiêu đề</th>

								<th class="align-center text-left">Giá trị</th>

								<th class="align-center text-left" width="60px"></th>

							</tr></thead>

							{if !empty($more_information.sales_policy)}

								{foreach from=$more_information.sales_policy name=i key=toId item = _oPolicy }

								<tr class="tr_csbh">

									<td class="text-left">

										<input type="text" name="sales_policy[{$toId}][title]" 

											class="form-control" placeholder="Tiêu đề" value="{$_oPolicy.title}" />

									</td>

									<td class="text-left">

										<div class="input-group">

											<input type="text" class="form-control" placeholder="Layout tòa nhà" name="sales_policy[{$toId}][image]" id="sales_policy_{$toId}" value="{$_oPolicy.image}" />

											<div class="input-group-btn">

												<button type="button" class="btn btn-icon btn-default" onclick="$Core.project.select_image(this, event)" gId="sales_policy_{$toId}" toId="{$toId}"><i class="fa fa-upload"></i></button>

											</div>

										</div>

									</td>

									<td class="text-center">

										{if $smarty.foreach.i.first}

										<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.add_policy(this, event)" toId="{$toId}" project_id="{$pvalTable}">{$core->makeIcon('plus')}</button>

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

									<td class="text-center">

										<button type="button" class="btn btn-icon btn-success" onClick="$Core.project.add_policy(this, event)" toId="{$toId}" project_id="{$pvalTable}">{$core->makeIcon('plus')}</button>

									</td>

								</tr>

							{/if}

						</table>

					</div>

				</div>

				{/if}

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-success pull-right" onClick="$Core.project.pop_save_building(this, event)" stock_type="{$stock_type}" block_id="{$block_id}"{if $_openFrom eq '_stock'} toId="{$toId}"{/if} _openFrom="{$_openFrom}" building_id="{$building_id}" project_id="{$project_id}">Cập nhật</button>

				<button type="button" class="btn btn-default mr-2 pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>

			</div>

		</form>

	</div>

</div>

