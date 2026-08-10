<div class="ui-title-bar-container ui-title-bar-container--full-width">

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('Qũy Căn Hộ Dự Án')}</h1>

				<p class="type--subdued">{$core->get_Lang('Quản lý toàn bộ Qũy Căn Hộ Dự Án có trong Hệ Thống')}</p>

			</div>

		</div>

		<div class="action-bar">

			<div class="ui-title-bar__mobile-primary-actions">

				<div class="ui-title-bar__actions">

					<a href="{$PCMS_URL}/index.php?mod=project&act=edit&project_id={$project_id}" class="ui-button ui-button--transparent ui-title-bar__action mr-2" title="{$core->get_Lang('Addnew')}">{$core->makeIcon('angle-left', $core->get_Lang('Project'))}</a>

					{if $stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}

					<a href="javascript:void(0)" onClick="$Core.stock.open_import_lowfloor(this, event)" stock_type="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}" class="ui-button ui-button--transparent ui-title-bar__action">Craw bảng hàng</a>

					{elseif $stock_type eq $smarty.const._STOCK_TYPE_LEASING}

					<a href="javascript:void(0)" onClick="$Core.stock.open_import_leasing(this, event)" stock_type="{$smarty.const._STOCK_TYPE_LEASING}" class="ui-button ui-button--transparent ui-title-bar__action">Craw bảng hàng</a>

					{/if}

				</div>

			</div>

		</div>

	</div>

</div>

<div class="ui-layout ui-layout--full-width">

	<div class="ui-layout__sections"><div class="ui-layout__section">

		<div class="ui-layout__item"><div class="ui-card">

			<div class="next-tab__container">

				<ul class="next-tab__list filter-tab-list">

					<li class="filter-tab-item" data-tab-index="1">

						<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">{$core->get_Lang('Qũy Căn Hộ Dự Án')}</a>

					</li>

				</ul>

			</div>

			<div class="ui-card__section has-bulk-actions pages">

				{assign var = toId value = $clsISO->getUniqid()}

				<form class="d-none" method="post" enctype="multipart/form-data">

					<input type="file" name="layout_file" id="{$toId}" onChange="$Core.stock.layout_upload_file(this, event)" />

				</form>

				<form method="post" enctype="multipart/form-data">

					<div id="admincp_stat" class="block w-100 my-2">

						<div class="content stats-me w-100">

							<div class="stat-item" status="-1">

								<div class="item-outer">

									<div class="item-icon">{$core->makeIcon('compass')}</div>

									<div class="item-info">

										<div class="item-number">{$total_record}</div>

										<div class="item-text">Tổng căn/nhà</div>

									</div>

								</div>

							</div>

							{section name=i loop=$arrStatus}

							<div class="stat-item" status="1">

								<div class="item-outer">

									<div class="item-icon">{$core->makeIcon('gavel')}</div>

									<div class="item-info">

										<div class="item-number">{$arrStatus[i].total_stock}</div>

										<div class="item-text">{$arrStatus[i].title}</div>

									</div>

								</div>

							</div>

							{/section}

						</div>

					</div>

					<div class="form-search">

						<div class="d-flex align-items-center gap-2 justify-content-between">

							<div class="d-flex align-items-center">

								<input type="hidden" name="stock_type" value="{$stock_type}" />

								<div class="w-200px">
									<select class="form-control mr-2 gl-reload w-200px iso-select2" name="project_id">

										{if $stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}

										<option value="0">Lựa chọn dự án</option>

										{/if}

										{foreach name=i from=$list_projects item = _project}

										<option value="{$_project.project_id}"{if $project_id eq $_project.project_id} selected{/if}>{$_project.title}</option>

										{/foreach}

									</select>
								</div>

								{if $project_id gt '0'}

									<div class="w-150px">
										<select class="form-control iso-select2 mr-2 gl-reload w-150px" name="block_id">

											{$clsProperty->getOptionOrigin('_BLOCK',$project_id,$block_id,'Phân khu')}

										</select>
									</div>

									{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}

										{if $block_id gt '0'}

											<div class="w-150px">
												<select class="form-control mr-2 w-150px gl-reload iso-select2" id="slb_Building" name="building_id">

													{$clsProperty->getOptionOrigin('_BUILDING',$block_id,$building_id,'Tòa nhà')}

												</select>
											</div>

										{/if}

										{if $building_id gt '0'}


											<div class="w-150px">
												<select class="form-control custom-select w-150px gl-reload mr-2 iso-select2" name="floor">

													{$html_floor_option}

												</select>
											</div>

										{/if}

									{else}

										{if $block_id gt '0'}

											<select class="form-control mr-2 w-150px gl-reload iso-select2" id="slb_Building" name="building_id">

												{$clsProperty->getOptionOrigin('_RANGE',$block_id,$building_id,'Dãy')}

											</select>

										{/if}

									{/if}

								{/if}

								<div class="dropdown mega-dropdown mr-2">

									<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">&nbsp;{$core->makeIcon('angle-down')}&nbsp;</button>

									<div class="dropdown-menu mega-dropdown-menu py-3 px-2">

										{if $stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}

										<div class="form-group mb-2">

											<label class="col-form-label col-md-4">Loại hình</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control custom-select iso-select2" name="type_id">

													{$clsProperty->getSelectOptimizeProperty('_TYPE_VILLA', $type_id, $arrTypeVilla)}

												</select>

											</div>

										</div>

										{else}

										<div class="form-group mb-2">

											<label class="col-form-label col-md-4">Phòng ngủ</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control custom-select iso-select2" name="bedroom_id">

													{$clsProperty->getSelectOptimizeProperty('_BEDROOM', $bedroom_id, $arrBedRooms)}

												</select>

											</div>

										</div>

										{/if}

										<div class="form-group mb-2">

											<label class="col-form-label col-md-4">View</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control custom-select iso-select2" name="view_id">

													{$clsProperty->getSelectOptimizeProperty('_VIEW', $view_id, $arrViews)}

												</select>

											</div>

										</div>

										<div class="form-group mb-2">

											<label class="col-form-label col-md-4">Hướng BC</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control w-100 custom-select iso-select2" name="home_direction_id">

													{$clsProperty->getSelectOptimizeProperty('_DIRECTION', $home_direction_id, $arrDirections)}

												</select>

											</div>

										</div>

										<div class="form-group mb-2">

											<label class="col-form-label col-md-4">Đại lý</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control w-100 custom-select iso-select2" name="agency_id">

													{$clsProperty->getSelectOptimizeProperty('_AGENCY', $agency_id, $arrAgencies)}

												</select>

											</div>

										</div>

										<div class="form-group">

											<label class="col-form-label col-md-4">Tình Trạng</label>

											<div class="col-xs-12 col-md-8">

												<select class="form-control w-100 custom-select iso-select2" name="status_id">

													{$clsProperty->getSelectOptimizeProperty('_STATUS', $status_id, $arrStatus)}

												</select>

											</div>

										</div>

									</div>

								</div>

								<div class="input-group mr-2">

									<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="tìm kiếm..." />

								</div>

								<input type="hidden" name="filter" value="filter" />

								<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Tìm')}</button>

							</div>

							<span class="">Kết quả: <strong class="text-danger">{$total_record}</strong> căn</span>

						</div>

					</div>

					<div class="hastable">

						<input type="hidden" name="filter" value="filter" />

						<div class="d-flex justify-content-between align-items-center my-3">

							<div class="p__left d-flex align-items-center">

								<div class="btn-group mr-1">

									<button class="btn btn-default" project_id="{$project_id}" block_id="{$block_id}" stock_type="{$stock_type}" building_id="{$building_id}" onClick="add_line(this,event)">{$core->makeIcon('plus-circle',$core->get_Lang('Add Line'))}</button>

									<button type="button" onClick="open_import_file(this, event)" tp="blank" project_id="{$project_id}" stock_type="{$stock_type}" block_id="{$block_id}" building_id="{$building_id}" title="Import bằng Excel nhưng không cần tải mẫu" data-toggle="tooltip" class="btn btn-default text-primary">{$core->makeIcon('upload', 'Import Excel')}</button>

								</div>

								{if !empty($building_id)}

									{assign var = building_name value = $clsProperty->getTitle($building_id)}

									<div class="btn-group mr-1">

										<button type="button" onClick="open_building(this, event)" property_type="_BUILDING" toId="slb_Building" _openFrom="_stock" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" title="Chỉnh sửa tòa nhà" data-toggle="tooltip" class="btn btn-default text-info">{$core->makeIcon('pencil')} Sửa {$building_name}</button>

										<button type="button"{if $oneBuilding.is_locked eq '1'} disabled{/if} onClick="delete_stock_building(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" title="Xóa bảng hàng tòa nhà" data-toggle="tooltip" class="btn btn-default text-danger btn_reset_building_{$building_id}">{$core->makeIcon('trash')} Xóa bảng hàng {$building_name}</button>

										<button type="button" onClick="do_stock_sold(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" title="Cập nhật bảng hàng<br />thành [Đã bán]" data-html="true" data-toggle="tooltip" class="btn btn-default text-primary">{$core->makeIcon('check')} Đã bán {$building_name}</button>

										<button onClick="$Core.stock.open_copy(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" class="btn btn-default text-primary" title="Copy hàng tới hàng">

											<i class="fa fa-files-o" aria-hidden="true"></i>

											<i class="fa fa-long-arrow-right" aria-hidden="true"></i>

											<i class="fa fa-files-o" aria-hidden="true"></i>

										</button>

										<button type="button" onClick="$Core.stock.updateStructCode(this, event)" project_id="{$project_id}" block_id="{$block_id}" building_id="{$building_id}" building_name="{$building_name}" title="Cập nhật mã căn tòa {$building_name}" data-html="true" class="btn btn-default text-primary">{$core->makeIcon('refresh')} Cập nhật mã căn</button>

									</div>

									<input class="form-control w-120px" data-toggle="tooltip" data-html="true" title="Nhập mã căn & nhấn<br />[Enter]" placeholder="Mã căn..." project_id="{$project_id}" onkeydown="do_stock_once_sold(this, event)" />

								{/if}

							</div>

							<div class="d-flex align-items-center admincp-buttons-action">

								<select class="form-control mr-1 iso-select2" name="per_page">

									<option value="20"{if $per_page eq '20'} selected{/if}>30</option>

									<option value="50"{if $per_page eq '50'} selected{/if}>50</option>

									<option value="100"{if $per_page eq '100'} selected{/if}>100</option>

									<option value="500"{if $per_page eq '500'} selected{/if}>500</option>

								</select>

								<div class="btn-group d-flex">

									<button href="javascript:void(0)" class="btn btn-default do_action disabled" cmd="update" onClick="do_action(this, event);">Cập nhật</button>

									{if $smarty.const._STOCK_DELETE_ENABLE eq '1'}

									<button href="javascript:void(0)" class="btn btn-default do_action disabled" cmd="delete" onClick="do_action(this, event);" >Xóa chọn</button>

									{/if}

								</div>

							</div>

						</div>

						<div class="freeze-table dragscroll" style="overflow-x: scroll; width:100%;">

							<table id="tableCall" cellspacing="0" class="table table-vertical table-striped no-maxwidth" width="100%">

								<thead><tr>

									<th class="text-left">

										<div class="checkbox">

											<input type="checkbox" class="checkAll" />

											<label></label>

										</div>

									</th>

									{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}

									<th class="text-left">PTG</th>

									<th class="text-left"></th>

									<th class="text-left">Mã căn (FULL)</th>

									<th class="text-left">Tổng giá VAT KPBT</th>

									<th class="text-left">Giá chưa VAT&KPBT</th>

									<th class="text-left">Giá TTS</th>

									<th class="text-left">Giá TTTD</th>

									<th class="text-left">Giá Vay 80%</th>

									<th class="text-left">Giá Vay 50%</th>

									<th class="text-left">Tình trạng</th>

									<th class="text-left">Đại lý</th>

									<th class="text-left">Số tầng</th>

									<th class="text-left">Mã căn</th>

									<th class="text-left">DT_TT(m2)</th>

									<th class="text-left">DT_Tim(m2)</th>

									<th class="text-left">Số PN</th>

									<th class="text-left">Hướng BC</th>

									<th class="text-left">View</th>

									<th class="text-left">Loại hình</th>

									<th class="text-left">CSBH</th>

									<th class="text-left">Ngày ký TTĐC</th>

									<th class="text-left">Ngày ký XNĐK</th>

									<th class="text-left">Thưởng sale</th>

									<th class="text-left">Cơ chế HH</th>

									<th class="text-left" width="300px">Layout(Ưu tiên)</th>

									<th class="text-left" width="300px">Layout TS(Ưu tiên)</th>

									{elseif $stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}

									<th class="text-left"></th>

									<th class="text-left">Đại lý</th>

									<th class="text-left">Phân khu</th>

									{if $project_id gt '0'}

									<th class="text-left">Dãy</th>

									{/if}

									<th class="text-left">Loại quỹ</th>

									<th class="text-left">Tình trạng</th>

									<th class="text-left">Loại hình</th>

									<th class="text-left">Mã căn</th>

									<th class="text-left">DT Đất(m2)</th>

									<th class="text-left">DT XD(m2)</th>

									<th class="text-left">TCBG</th>

									<th class="text-left">Hướng Nhà</th>

									<th class="text-left">Tổng giá VAT KPBT</th>

									<th class="text-left">Giá chưa VAT&KPBT</th>

									<th class="text-left">Giá vay 12T</th>

									<th class="text-left">Giá vay 18T</th>

									<th class="text-left">Giá Vay 24T</th>

									<th class="text-left">Giá Vay 30T</th>

									<th class="text-left">Giá vay 36T</th>

									<th class="text-left">Giá TTTD</th>

									<th class="text-left">Giá TTS</th>

									<th class="text-left">CSBH</th>

									<th class="text-left">Chính sách</th>

									<th class="text-left">Phiếu tạm tính</th>

									<th class="text-left">Loại hình ký</th>

									<th class="text-left">Qũy đầu tư</th>

									<th class="text-left">Giỏ Bank</th>

									<th class="text-left">Ngày ký cọc</th>

									<th class="text-left">Ghi chú</th>

									<th class="text-left" width="300px">Layout</th>

									{else}

									<th class="text-left"></th>

									<th class="text-left">Đại lý</th>

									<th class="text-left">Loại quỹ</th>

									<th class="text-left">Tình trạng</th>

									<th class="text-left">Loại hình</th>

									<th class="text-left">Mã căn</th>

									<th class="text-left">DT Đất(m2)</th>

									<th class="text-left">DT XD(m2)</th>

									<th class="text-left">TCBG</th>

									<th class="text-left">Hướng Nhà</th>

									<th class="text-left">Giá thuê</th>

									<th class="text-left">Hoàn thiện</th>

									<th class="text-left">Tiến độ TT</th>

									<th class="text-left">Link ảnh</th>

									<th class="text-left">Ghi chú</th>

									{/if}

								</tr></thead>

								<tbody class="tbody">

									{section name=i loop=$list_stocks}

									{assign var = stock_id value = $list_stocks[i].stock_id}

									{assign var = more_information value = $list_stocks[i].more_information}

									<tr class="sop_row sop_row_{$stock_id}{if isset($more_information.markup_price) && $more_information.markup_price eq '1'} stock_mask{/if}">

										<td bgcolor="#EEE" width="40px">

											<div class="checkbox">

												<input type="checkbox" class="checkitem stock_item" value="{$stock_id}" />

												<label></label>

											</div>

										</td>

										{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}

										<td bgcolor="#EEE" width="40px">

											<div class="checkbox">

												<input type="checkbox" title="Ẩn PTG" disabled{if isset($more_information.hide_price_sheets) && $more_information.hide_price_sheets eq '1'} checked{/if} value="{$stock_id}" />

												<label></label>

											</div>

										</td>

										{/if}

										<td bgcolor="#EEE"  width="120px">

											<div class="btn-group d-flex">

												{if $smarty.const._STOCK_DELETE_ENABLE eq '1'}

												<button type="button" class="btn btn-icon btn-xs btn-default" title="{$core->get_Lang('Delete')}" onClick="delete_line(this, event)" stock_id="{$stock_id}">{$core->makeIcon('trash')}</button>{/if}

												<button type="button" class="btn btn-icon btn-xs btn-default" title="{$core->get_Lang('Edit')}" onClick="open_stock(this, event)" stock_id="{$stock_id}">{$core->makeIcon('pencil')}</button>

											</div>

										</td>

										{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}

										<td class="text-left">

											<input type="text" class="form-control stock_field text-bold w-100px" stock_id="{$stock_id}" data-field="ms_code" value="{$list_stocks[i].ms_code}" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_vat" value="{$more_information.total_price_vat}" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price" value="{$more_information.total_price}" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_early" value="{$more_information.total_price_early}" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_progress" value="{$more_information.total_price_progress}" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_bank" value="{$more_information.total_price_bank}" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_bank_half" value="{$more_information.total_price_bank_half}" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" 

											data-field="status_id">

												{$clsProperty->getSelectOptimizeProperty('_STATUS', $list_stocks[i].status_id, $arrStatus)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" 

											data-field="agency_id">

												{$clsProperty->getSelectOptimizeProperty('_AGENCY', $list_stocks[i].agency_id, $arrAgencies)}

											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-50px" stock_id="{$stock_id}" data-field="floor" value="{$list_stocks[i].floor}" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-50px" stock_id="{$stock_id}" data-field="code" 

											value="{$list_stocks[i].code}" />

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="{$stock_id}" data-field="DT_TT" value="{$more_information.DT_TT}" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="{$stock_id}" data-field="DT_Tim" value="{$more_information.DT_Tim}" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" data-field="bedroom_id">

												{$clsProperty->getSelectOptimizeProperty('_BEDROOM', $more_information.bedroom_id, $arrBedRooms)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="{$stock_id}" data-field="home_direction_id">

												{$clsProperty->getSelectOptimizeProperty('_DIRECTION', $more_information.home_direction_id, $arrDirections)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" data-field="view_id">

												{$clsProperty->getSelectOptimizeProperty('_VIEW', $more_information.view_id, $arrViews)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" data-field="type_id">

												{$clsProperty->getSelectOptimizeProperty('_TYPE', $more_information.type_id, $arrTypes)}

											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="csbh" 

											value="{$more_information.csbh}" placeholder="dd/mm/yyy" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-100px" stock_id="{$stock_id}" data-field="date_deposit_sign" placeholder="dd/mm/yyy" value="{$more_information.date_deposit_sign}" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-100px" stock_id="{$stock_id}" data-field="reg_confirm_date" placeholder="dd/mm/yyy" value="{$more_information.reg_confirm_date}" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-100px" stock_id="{$stock_id}" data-field="sale_bonus" 

											value="{$more_information.sale_bonus}" placeholder="0.00đ" />

										</td>

										<td class="text-left">

											<div class="input-group">

												<input type="text" class="form-control stock_field w-50px" stock_id="{$stock_id}" data-field="sale_commission" value="{$more_information.sale_commission}" placeholder="0" />

												<span class="input-group-addon">%</span>

											</div>

										</td>

										<td class="text-center">

											<div class="input-group">

												<input type="text" placeholder="Nhập ảnh..." class="form-control stock_layout_{$stock_id} w-100px" maxlength="255" value="{if !empty($more_information.layout)}{$more_information.layout}{/if}" />

												<div class="input-group-btn">

													<button type="button" toId="{$toId}" onClick="$Core.stock.layout_select_file(this, event)" 

													stock_id="{$stock_id}" tofield="layout" class="btn btn-default">{$core->makeIcon('upload','Chọn')}</button>

												</div>

											</div>

										</td>

										<td class="text-center">

											<div class="input-group">

												<input type="text" placeholder="Nhập ảnh..." class="form-control stock_layout_ns_{$stock_id} w-100px" maxlength="255" value="{if !empty($more_information.layout_ns)}{$more_information.layout_ns}{/if}" />

												<div class="input-group-btn">

													<button type="button" toId="{$toId}" tofield="layout_ns" onClick="$Core.stock.layout_select_file(this, event)" stock_id="{$stock_id}" class="btn btn-default">{$core->makeIcon('upload','Chọn')}</button>

												</div>

											</div>

										</td>

										{elseif $stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" 

											data-field="agency_id">

												{$clsProperty->getSelectOptimizeProperty('_AGENCY', $list_stocks[i].agency_id, $arrAgencies)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" data-field="block_id">

												{if $project_id gt '0'}

													{$clsProperty->getSelectOptimizeProperty('_BLOCK', $list_stocks[i].block_id, $arrBlock)}

												{else}

													{$clsProperty->getSelectFromSource($arrBlock, $list_stocks[i].block_id, '_BLOCK')}

												{/if}

											</select>

										</td>

										{if $project_id gt '0'}

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" data-field="building_id">

												{$clsProperty->getSelectFromSource($arrBlock, $list_stocks[i].building_id, '_RANGE')}

											</select>

										</td>

										{/if}

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" 

											data-field="stock_hold_id">

												{$clsProperty->getSelectOptimizeProperty('_STOCK_HOLD', $more_information.stock_hold_id, $arrStockHold)}

											</select>

										</td>

										<!-- <td class="text-left">

											<input type="text" class="form-control stock_field w-100px" stock_id="{$stock_id}" data-field="block_name" 

											value="{$list_stocks[i].block_name}" />

										</td> -->

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" 

											data-field="status_id">

												{$clsProperty->getSelectOptimizeProperty('_STATUS', $list_stocks[i].status_id, $arrStatus)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" data-field="type_id">

												{$clsProperty->getSelectOptimizeProperty('_TYPE', $more_information.type_id, $arrTypes)}

											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field text-bold w-100px" stock_id="{$stock_id}" 

												   data-field="ms_code" value="{$list_stocks[i].ms_code}" />

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="{$stock_id}" 

													   data-field="DT_TT" value="{$more_information.DT_TT}" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="{$stock_id}" 

													   data-field="DT_Tim" value="{$more_information.DT_Tim}" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" 

												   data-field="TCBG" value="{$more_information.TCBG}" placeholder="Tiêu chuẩn bàn giao" />

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="{$stock_id}" data-field="home_direction_id">

												{$clsProperty->getSelectOptimizeProperty('_DIRECTION', $more_information.home_direction_id, $arrDirections)}

											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_vat" value="{$more_information.total_price_vat}" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price" value="{$more_information.total_price}" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_bank_12" value="{$more_information.total_price_bank_12}" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_bank_18" value="{$more_information.total_price_bank_18}" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_bank" value="{$more_information.total_price_bank}" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_bank_30" value="{$more_information.total_price_bank_30}" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_bank_36" value="{$more_information.total_price_bank_36}" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_progress" value="{$more_information.total_price_progress}" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_early" value="{$more_information.total_price_early}" placeholder="0.00" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="csbh" 

											value="{$more_information.csbh}" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="cs_policy_ns" 

											value="{$more_information.cs_policy_ns}" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="price_temporary_ns" value="{$more_information.price_temporary_ns}" />

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="{$stock_id}" data-field="contract_type_id">

												{$clsProperty->getSelectOptimizeProperty('_CONTRACT_TYPE', $more_information.contract_type_id, $arrContractType)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="{$stock_id}" data-field="invest_fund_id">

												{$clsProperty->getSelectOptimizeProperty('_INVEST_FUND', $more_information.invest_fund_id, $arrInvestFund)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="{$stock_id}" data-field="bank_id">

												{$clsProperty->getSelectOptimizeProperty('_BANK', $more_information.bank_id, $arrBank)}

											</select>

										</td>

										<td class="text-left">

											<!-- input_mask -->

											<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="deposit_date" placeholder="dd/mm/yyyy" data-inputmask="99/99/9999"  value="{$more_information.deposit_date}" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="notes" 

											value="{$more_information.notes}" />

										</td>

										<td class="text-center">

											<div class="input-group">

												<input type="text" placeholder="Nhập ảnh..." class="form-control stock_layout_{$stock_id} w-100px" maxlength="255" value="{if !empty($more_information.layout)}{$more_information.layout}{/if}" />

												<div class="input-group-btn">

													<button type="button" toId="{$toId}" onClick="$Core.stock.layout_select_file(this, event)" 

													stock_id="{$stock_id}" tofield="layout" class="btn btn-default">{$core->makeIcon('upload','Chọn')}</button>

												</div>

											</div>

										</td>

										{else}

										<!-- LEASING -->

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" 

											data-field="agency_id">

												{$clsProperty->getSelectOptimizeProperty('_AGENCY', $list_stocks[i].agency_id, $arrAgencies)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" 

											data-field="stock_hold_id">

												{$clsProperty->getSelectOptimizeProperty('_STOCK_HOLD', $more_information.stock_hold_id, $arrStockHold)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" 

											data-field="status_leasing_id">

												{$clsProperty->getSelectOptimizeProperty('_STATUS', $more_information.status_leasing_id, $arrStatus)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-120px iso-select2" stock_id="{$stock_id}" data-field="type_id">

												{$clsProperty->getSelectOptimizeProperty('_TYPE', $more_information.type_id, $arrTypes)}

											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field text-bold w-100px" stock_id="{$stock_id}" 

												   data-field="ms_code" value="{$list_stocks[i].ms_code}" />

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="{$stock_id}" 

													   data-field="DT_TT" value="{$more_information.DT_TT}" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<div class="input-group-suffix">

												<input type="text" class="form-control numberonly stock_field w-90px" stock_id="{$stock_id}" 

													   data-field="DT_Tim" value="{$more_information.DT_Tim}" />

												<span class="suffix">m2</span>

											</div>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" 

												   data-field="TCBG" value="{$more_information.TCBG}" placeholder="Tiêu chuẩn bàn giao" />

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="{$stock_id}" data-field="home_direction_id">

												{$clsProperty->getSelectOptimizeProperty('_DIRECTION', $more_information.home_direction_id, $arrDirections)}

											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control price-In stock_field w-120px" stock_id="{$stock_id}" data-field="total_price_vat" value="{$more_information.dg_price}" placeholder="0.00" />

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="{$stock_id}" data-field="completed_floor_id">

												{$clsProperty->getSelectOptimizeProperty('_COMPLETED_FLOOR', $more_information.completed_floor_id, $arrCompleteFloors)}

											</select>

										</td>

										<td class="text-left">

											<select class="form-control stock_field w-100px iso-select2" stock_id="{$stock_id}" data-field="payment_progress_id">

												{$clsProperty->getSelectOptimizeProperty('_STATUS_PAYMENT_PROGRESS', $more_information.payment_progress_id, $arrStatusPaymentProgress)}

											</select>

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="link_image" 

											value="{$more_information.link_image}" placeholder="https://drive.google.com/xxxx" />

										</td>

										<td class="text-left">

											<input type="text" class="form-control stock_field w-120px" stock_id="{$stock_id}" data-field="notes" 

											value="{$more_information.notes}" />

										</td>

										{/if}

									</tr>

									{/section}

								</tbody>

							</table>

						</div>

						{if $total_page gt '1'}

						<div class="d-flex justify-content-center">

							<ul class="pagination">

								{$html_pager}

							</ul>

						</div>

						{/if}

					</div>

				</form>

			</div></div>

		</div></div>

	</div>

</div>

{literal}

<style type="text/css">

	.w-100px{width:100px !important;}

	.w-120px{width:120px !important;}

	.w-250px{width:250px !important;}

	.table{margin-bottom:0;max-width:18000px;}

	.input-group-suffix .suffix{ right:10px;}

	.freeze-table {

        user-select: none;

        -moz-user-select: none;

        -khtml-user-select: none;

        -webkit-user-select: none;

        -o-user-select: none;

	}

	tr.stock_mask > td:first-child{

		position:relative;

	} 

	tr.stock_mask > td:first-child:before{

		content: "";

		position: absolute;

		left: -8px; top: -3px;

		border-bottom: 10px solid #C00000;

		border-left: 10px solid transparent;

		border-right: 10px solid transparent;

		transform: rotate(-45deg);

		-moz-transform: rotate(-45deg);

		-webkit-transform: rotate(-45deg);

	}

	.mega-dropdown-menu{

		min-width:300px;

	}

	.dropdown-menu > li > a.disabled{

		color:gray;

		opacity:0.2l

		filter:alpha(opacity=20);

	}

</style>

<script type="text/javascript">

	$(function(){

		$(".mega-dropdown-menu").click(function(e){

		   e.stopPropagation();

		});

		setTimeout(() => {

			$('.freeze-table').freezeTable({

				'columnNum': 5,

				'scrollable': true

			});

		},1000);

		$_document.on('change', '.gl-reload', function(){

			var _this = $(this),

				_form = _this.closest('form');

			$('button[type=submit]', _form).trigger('click');

			return false;

		});

	});

</script>

{/literal}

<script type="text/javascript" src="{$URL_VIEWS}/project/js/jquery.project.js?v={$upd_version}"></script>