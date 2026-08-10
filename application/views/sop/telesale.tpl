<div class="container-xxl flex-grow-1 pt-1 pb-2 container-p-y">
	<div class="d-flex align-items-center flex-wrap justify-content-between mb-2">
		<div class="zXvlIkdqgV mb-2 mb-lg-0">
			<h5 class="fw-bold mb-1">Danh sách gọi Telesales</h5>
			<span class="text-muted">Quản lý danh sách & liên hệ Telesales</span>
		</div>
		<div class="xRuJxIfsSr"></div>
	</div>
    <div class="card">
		<div class="card-body">
			<div id="sum" class="form-row row-cols-2 row-cols-lg-6 mb-2">
				{foreach from=$arr_sum_blocks key = _oKey item = _oBlock}
				<div class="col mb-2 flex-fill mb-lg-0 ">
					<div class="p-3 rounded-2 text-white tele_box relative tele_box-{$_oKey}" 
						style="background-color:{$_oBlock.bgcolor}">
						<h3 class="mb-1 fs-4">Loading...</h3>
						<p class="mb-0">{$_oBlock.title}</p>
					</div>
				</div>
				{/foreach}
			</div>
			<form class="bg-lighter rounded-1 d-flex justify-content-between p-3 mb-2" method="POST">
				<div class="form-group">
					<div class="d-flex gap-2 flex-wrap">
						<div class="d-flex align-items-center gap-2">
							<div class="w-px-150">
							<select data-width="100%" name="stock_type" onChange="$Core.sop.run_search(this, event)" 
								data-field="stock_type" class="form-control slbStockType multiselect search_field">
									{$clsProperty->getSelectByPropertyV2('_BLOCK_TYPE', $sop_configs.stock_type)}
								</select>
							</div>
							<div class="w-px-275 xs:w-100">
								<select name="project_id" onChange="$Core.sop.run_search(this, event)" 
								data-field="project_id" class="form-control slbProject multiselect search_field" data-width="100%">
								{if !empty($arr_projects)}
									{foreach from=$arr_projects item = _oProject}
									<option{if $sop_configs.project_id eq $_oProject.project_id} selected{/if} value="{$_oProject.project_id}">{$_oProject.title}</option>
									{/foreach}
								{/if}
								</select>
							</div>
							<div class="w-px-150 xs:w-100">
								<select name="block_id" onChange="$Core.sop.run_search(this, event)" 
								data-field="block_id" class="form-control multiselect slbBlock search_field" data-width="100%">
									<option value="0">Phân khu/Block</option>
									{if !empty($arr_blocks)}
										{foreach from=$arr_blocks item = _oBlock}
										<option value="{$_oBlock.property_id}">{$_oBlock.title}</option>
										{/foreach}
									{/if}
								</select>
							</div>
							<div class="w-px-150 xs:w-100">
								<select name="building_id" onChange="$Core.sop.run_search(this, event)" 
								data-field="building_id" class="form-control multiselect slbBuilding search_field" data-width="100%">
									<option value="0">Tòa/Dãy</option>
								</select>
							</div>
						</div>
						<a href="javascript:void(0)" title="Lưu cấu hình" onClick="$Core.sop.save_setting(this, event)" 
							class="btn btn-icon btn-outline-default">{$core->makeIcon('floppy-o')}</a>
					</div>
				</div>
				<div class="d-flex align-centertext-center gap-2">
					<a href="javascript:void(0)" title="Xem map" onClick="$Core.sop.link_map(this, event)" class="btn btn-icon text-nowrap btn-outline-danger"><i class='bx bx-map-alt'></i></a>
					<button type="button" class="btn btn-icon btn-outline-primary" onclick="$Core.sop.setting_field(this,event)" 
						title="Tùy chỉnh cột"><i class="bx bx-list-plus"></i>
					</button>
				</div>
			</form>
			<div id="tableCommission" class="table-datagrid no-shadow text-nowrap overflow-x-auto">
				<input type="hidden" name="sort_type" value="DESC" />
				<input type="hidden" name="sort_by" value="reg_date" />
				<table width="100%" cellpadding="0" cellspacing="0" class="table table-telesale table-{$deviceType}">
					<!-- dragable -->
					<thead class="fixedHeader"><tr>
						<th class="align-center w-px-50 bg-lighter">STT</th>
						<th class="align-center w-px-100 h-px-40 bg-lighter">Mã căn</th>
						<th class="align-center w-px-100 h-px-40 text-center w-px-100 bg-lighter">Loại</th>
						<th class="align-center w-px-150 h-px-40 bg-lighter">Liên hệ</th>
						{foreach from=$arr_prices key = _oKey item = _oPrice}
						<th class="align-center w-px-150 h-px-40 bg-lighter">
							<div class="d-flex gap-3 justify-content-between">
								<span>{$_oPrice.title}</span>
								{if $_oKey ne 'fee_included'}
								<a onClick="$Core.sop.tele_sort(this, event)" title="Sắp xếp" field="{$_oKey}" class="sortClick"></a>
								{/if}
							</div>
						</th>
						{/foreach}
						<th class="align-center w-px-150 h-px-40 text-center bg-lighter col:finish_status d-none">Hoàn thiện</th>
						<th class="align-center w-px-150 h-px-40 text-center bg-lighter">Nội thất</th>
						<th class="align-center w-px-150 h-px-40 text-center bg-lighter">Pháp lý</th>
						<th class="align-center w-px-150 h-px-40 text-center bg-lighter">Xem nhà</th>
						<th class="align-center w-px-150 h-px-40 bg-lighter">Tình trạng</th>
						<th class="align-center h-px-40 bg-lighter w-px-100">Cập nhật</th>
					</tr>
					<tr>
						<th class="align-center text-center overflow-visible"></th>
						<th class="align-center text-center p-0 overflow-visible">
							<input onChange="$Core.sop.run_search(this, event)" data-field="stock_code" 
								class="form-control rounded-0 border-0 search_field form-control-sm" placeholder="Nhập từ khoá" />
						</th>
						<th class="align-center text-center p-0 overflow-visible">
							<div class="holderx">
								{if $sop_configs.stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}
								<select multiple="true" data-filter="true" data-width="100" onChange="$Core.sop.run_search(this, event)" 
									data-field="type_id" class="form-control multiselect border-0 search_field">
									{$clsProperty->makeSelect('_TYPE_VILLA')}
								</select>
								{else}
								<select multiple="true" data-filter="true" data-width="100" onChange="$Core.sop.run_search(this, event)" 
									data-field="bedroom_id" class="form-control multiselect border-0 search_field">
									{$clsProperty->makeSelect('_BEDROOM')}
								</select>
								{/if}
							</div>
						</th>
						<th class="align-center text-center p-0 overflow-visible">
							<input data-field="contact_name" onChange="$Core.sop.run_search(this, event)" 
								class="form-control rounded-0 border-0 search_field form-control-sm" placeholder="Nhập từ khoá" />
						</th>
						{foreach from=$arr_prices key = _oKey item = _oPrice}
						<th class="align-center text-center p-0 overflow-visible">
							{if $_oKey eq 'fee_included'}
							<select multiple="true" onChange="$Core.sop.run_search(this, event)" data-field="fee_included" 
								class="form-control multiselect border-0 search_field">
								{$clsProperty->makeSelect('_FEE_TYPE')}
							</select>
							{else}
							<div class="dropdown js__dropdown-{$_oKey}">
								<button class="btn btn-block btn-sm btn-outline-default dropdown-toggle border-0 rounded-0 js__slider-drowndown js__slider-dropdown-{$_oKey}" title="{$_oPrice.title}" data-field="{$_oKey}" toId="{$_oKey}-range" data-min="{$_oPrice.min}" data-max="{$_oPrice.max}" data-bs-toggle="dropdown" data-bs-auto-close="outside">
									<span class="js__dropdown-select-value js__dropdown-{$_oKey}-text">Khoảng giá</span>
								</button>
								<div class="dropdown-menu w-px-300" data-popper-placement="bottom-start">
									<div class="px-3 py-2">
										<div class="form-group mb-3">
											<label class="col-form-label">Mức giá</label>
											<div class="slider-container">
												<div id="{$_oKey}-range"></div>
											</div>
										</div>
										<label class="form-label">Hoặc nhập khoảng (đơn vị VNĐ)</label>
										<div class="form-group form-row">
											<div class="col-6">
												<div class="form-floating" >
													<input type="text" id="{$_oKey}_min" class="form-control numberonly search_field price-In" data-field="{$_oKey}_min" name="{$_oKey}_min" placeholder="Giá từ" maxlength="255" value="{$_oPrice.min}">
													<label for="{$_oKey}_min">Giá từ</label>
												</div>
											</div>
											<div class="col-6">
												<div class="form-floating">
													<input type="text" id="{$_oKey}_max" class="form-control numberonly search_field price-In" data-field="{$_oKey}_max" name="{$_oKey}_max" placeholder="Giá đến" maxlength="255" value="{$_oPrice.max}">
													<label for="{$_oKey}_max">Giá đến</label>
											  </div>
											</div>
										</div>
										<hr class="my-2" />
										<div class="d-flex align-items-center justify-content-between">
											<button data-field="{$_oKey}" type="button" onClick="$Core.sop.close_dropdown(this, event)" 
												class="btn btn-outline-default">Đóng</button>
											<button data-field="{$_oKey}" type="button" onClick="$Core.sop.set_dropdown(this, event)" 
												class="btn btn-primary">Áp dụng</button>
										</div>
									</div>
								</div>
							</div>
							{/if}
						</th>
						{/foreach}
						<th class="align-center text-center p-0 overflow-visible col:finish_status d-none">
							<select onChange="$Core.sop.run_search(this, event)" data-field="finish_status_id" 
								class="form-control search_field multiselect border-0">
								{$clsSetting->makeSelect('_FINISH_STATUS')}
							</select>
						</th>
						<th class="align-center text-center p-0 overflow-visible">
							<select onChange="$Core.sop.run_search(this, event)" data-field="interior_id" 
								class="form-control multiselect border-0 search_field">
								{$clsProperty->makeSelect('_INTERIOR_TYPE')}
							</select>
						</th>
						<th class="align-center text-center p-0 overflow-visible">
							<select onChange="$Core.sop.run_search(this, event)" data-field="juridical_id" 
								class="form-control multiselect border-0 search_field">
								{$clsProperty->makeSelect('_JURIDICAL')}
							</select>
						</th>
						<th class="align-center text-center p-0 overflow-visible">
							<select onChange="$Core.sop.run_search(this, event)" data-field="status_viewing_id" 
								class="form-control multiselect border-0 search_field">
								{$clsProperty->makeSelect('_STATUS_VIEWING')}
							</select>
						</th>
						<th class="align-center text-center p-0 overflow-visible">
							<select onChange="$Core.sop.run_search(this, event)" data-field="status_id" 
								class="form-control multiselect border-0 search_field">
								{$clsSetting->makeSelect('_STATUS_TELESALE')}
							</select>
						</th>
						<th class="align-center text-center p-0 overflow-visible">
							<input type="date" onChange="$Core.sop.run_search(this, event)" data-field="upd_date" 
								class="form-control search_field rounded-0 border-0 form-control-sm w-px-100" />
						</th>
					</tr></thead>
					<tbody id="holder_telesale">
						{section name=i loop=$list_preloaders}
						<tr>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td class="col:finish_status d-none"><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-15"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<div class="clearfix"></div>
			<div id="pager_telesale" class="easyui-pagination"></div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		if($('.js__slider-drowndown').length){
			$('.js__slider-drowndown').each((_i, _elem) => {
				var _field = $(_elem).data('field'),
					_toId = $(_elem).attr('toId');
				$(_elem).on('shown.bs.dropdown', () => {
					var _min = $(_elem).data('min'),
						_max = $(_elem).data('max'),
						_min_price = $(`input[name=${_field}_min]`).val(),
						_max_price = $(`input[name=${_field}_max]`).val(),
						_min_price = $Core.util.toNumber(_min_price),
						_max_price = $Core.util.toNumber(_max_price);
					$("#"+_toId).slider({
						min: _min,
						max: _max,
						range: true,
						values: [_min_price, _max_price],
						slide: function( event, ui ) {
							var _min = ui.values[0],
								_max = ui.values[1],
								_min_price = $Core.chart.formatPrice(_min),
								_max_price = $Core.chart.formatPrice(_max);
							$(`input[name=${_field}_min]`).val(_min_price);
							$(`input[name=${_field}_max]`).val(_max_price);
						}
					});	
				}).on('hidden.bs.dropdown', () => {
					$("#"+_toId).slider("destroy");
				});
			});
		}
	});
</script>
<style>
	.table-telesale .multiselect{
		border:0 !important;
	}
	.form-control-sm{
		padding:0.25rem 0.325rem;
		background-size:9px 12px;
	}
	.form-control-sm:focus{
		box-shadow:none !important;
	}
	.btn-outline-default:focus,
	.btn-outline-default:hover,
	.btn-outline-default.dropdown-toggle.show{
		color:var(--bs-body-color) !important;
		background:var(--bs-white) !important;
	}
</style>
{/literal}