<div class="modal-dialog {if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}modal-mg{else}modal-ipad{/if}">
	<div class="modal-content">
		<div class="modal-header">
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
			<h3 class="modal-title"><strong>{if $action eq '_add'}Thêm mới{else}Chỉnh sửa{/if} PTG {$stock_type_title}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<input type="hidden" name="stock_type" value="{$stock_type}" />
			<div class="modal-body">
				<div class="form-group form-row">
					<div class="col-md-9">
						<label class="col-form-label">Tên PTG <span class="text-red">*</span></label>
						<input type="text" class="form-control required" placeholder="Nhập tên..." name="title" value="{if $action eq '_edit'}{$oneItem.title}{/if}" />
					</div>
					<div class="col-md-3">
						<label class="col-form-label">Ngày áp dụng <span class="text-red">*</span></label>
						<input type="text" class="form-control datepicker required" placeholder="dd/mm/yy" name="apply_date" value="{$clsISO->convertTimeToText($oneItem.apply_date)}" />
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-4">
						<label class="col-form-label">VAT <span class="text-red">*</span></label>
						<div class="input-group-suffix">
							<input type="text" class="form-control numberonly required" placeholder="Nhập VAT..."
								name="vat_rate" onClick="this.select()" value="{if $action eq '_edit'}{$oneItem.vat_rate}{/if}" />
							<span class="suffix">%</span>
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">KPBT <span class="text-red">*</span></label>
						<div class="input-group-suffix">
							<input type="text" onClick="this.select()" class="form-control numberonly required" placeholder="Nhập KPBT"
								name="maintenance_rate" value="{if $action eq '_edit'}{$oneItem.maintenance_rate}{/if}" />
							<span class="suffix">%</span>
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Tiền cọc</label>
						<div class="input-group-suffix">
							<input type="text" class="form-control price-In" placeholder="0"
								name="deposit_amount" value="{if $oneItem.deposit_amount}{$oneItem.deposit_amount}{/if}" />
							<span class="suffix">đ</span>
						</div>
					</div>
				</div>
				<div class="form-group form-row">
					<div class="col-md-4">
						<label class="col-form-label">Tiền đất không chịu thuế /m2</label>
						<div class="input-group-suffix">
							<input type="text" class="form-control price-In" placeholder="0"
								name="tax_fee_m2" value="{if $oneItem.tax_fee_m2}{$oneItem.tax_fee_m2}{/if}" />
							<span class="suffix">đ</span>
						</div>
					</div>
					<div class="col-md-4">
						<label class="col-form-label">Chiết khấu khác <span class="text-red">*</span></label>
						<div class="input-group-suffix">
							<input type="text" class="form-control numberonly" placeholder="Nhập chiết khấu khác"
								name="other_discount" onClick="this.select()" value="{if $action eq '_edit'}{$oneItem.other_discount}{/if}" />
							<span class="suffix">%</span>
						</div>
					</div>
				</div>
				<fieldset>
					<legend>Chiết khấu early bird</legend>
						<div class="form-group form-row">
							<div class="col-md-6">
								<div class="scope_item scope_item_6a290aa2b0496588436225">
									<label class="col-form-label">Dành cho cư dân</label>
									<div class="form-row">
										<div class="col-md-8">
											<input type="text" onClick="this.select()" class="form-control numberonly " placeholder="Tiêu đề hiển thị" name="early_bird[early_bird_resident_label]" value="{if $action eq '_edit'}{$early_bird.early_bird_resident_label}{/if}" />
										</div>
										<div class="col-md-4">
											<div class="input-group-suffix">
												<input type="text" onClick="this.select()" class="form-control numberonly " placeholder="Nhập CK"
													name="early_bird[early_bird_resident_rate]" value="{if $action eq '_edit'}{$early_bird.early_bird_resident_rate}{/if}" />
												<span class="suffix">%</span>
											</div>
										</div>
									</div>	
								</div>							
							</div>
							<div class="col-md-6">
								<div class="scope_item scope_item_6a290aa2b0496588436225">
									<label class="col-form-label">Dành cho khách hàng khác</label>
									<div class="form-row">
										<div class="col-md-8">
											<input type="text" onClick="this.select()" class="form-control numberonly " placeholder="Tiêu đề hiển thị" name="early_bird[early_bird_other_label]" value="{if $action eq '_edit'}{$early_bird.early_bird_other_label}{/if}" />
										</div>
										<div class="col-md-4">
											<div class="input-group-suffix">
												<input type="text" onClick="this.select()" class="form-control numberonly " placeholder="Nhập CK"
													name="early_bird[early_bird_other_rate]" value="{if $action eq '_edit'}{$early_bird.early_bird_other_rate}{/if}" />
												<span class="suffix">%</span>
											</div>
										</div>
									</div>	
								</div>							
							</div>
						</div>
				</fieldset>
				<div class="form-group">
					<label class="col-form-label">Chính sách / Ghi chú thêm</label>
					<textarea class="form-control isoTextArea" id="{$clsISO->getUniqid()}" placeholder="VD: Early Bird KH mới 1% (ký VBTT trước 20/04); Miễn phí quản lý 12 tháng; Timeline có thể thay đổi theo CĐT..." data-name="description" rows="8" cols="255">{if $action eq '_edit'}{$oneItem.description}{/if}</textarea>
				</div>
				<fieldset>
					<legend>Phạm vi áp dụng</legend>
					<div class="group_scopes">
					{if !empty($list_scopes)}
						{foreach name=k from=$list_scopes key=uid item = _oScope}
						{assign var = list_blocks value = $_oScope.list_blocks}
						{assign var = list_buildings value = $_oScope.list_buildings}
						<div class="scope_item scope_item_{$uid}">
							<div class="form-group form-row">
								<div class="col-md-6">
									<label class="col-form-label">Chọn dự án</label>
									<select uid="{$uid}" onchange="$Core.price_sheets.load_option_block(this,event)" name="scope[{$uid}][project_id]" toId="block_{$uid}" class="form-control iso-select2 required">
										<option value="0">Chọn dự án</option>
										{foreach name=i from=$list_projects item = project}
										<option{if $project.project_id eq $_oScope.project_id} selected{/if} value="{$project.project_id}">{$project.title}</option>
										{/foreach}
									</select>
								</div>
								<div class="col-md-6">
									<label class="col-form-label">Chọn phân khu</label>
									{if $stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}
									<select uid="{$uid}" id="block_{$uid}" name="scope[{$uid}][block_id][]" multiple="multiple" class="form-control required iso-select2">
										<option value="0">Chọn phân khu</option>
										{if !empty($list_blocks)}
											{foreach name=i from=$list_blocks item = _oBlock}
											<option{if $_oBlock.selected eq '1'} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>
											{/foreach}
										{/if}
									</select>
									{else}
									<select uid="{$uid}" id="block_{$uid}" onchange="$Core.price_sheets.load_option_building(this,event)" toId="building_{$uid}" name="scope[{$uid}][block_id]" class="form-control required iso-select2">
										<option value="0">Chọn phân khu</option>
										{if !empty($list_blocks)}
											{foreach name=i from=$list_blocks item = _oBlock}
											<option{if $_oScope.block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">({$_oBlock.property_code}) {$_oBlock.title}</option>
											{/foreach}
										{/if}
									</select>
									{/if}
								</div>
							</div>
							{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
								<div id="building_group_{$uid}" class="form-group">
									<label class="col-form-label">Chọn tòa áp dụng</label>
									<select uid="{$uid}" multiple="multiple" id="building_{$uid}" data-placeholder="Chọn tòa"
									name="scope[{$uid}][building_id][]" class="form-control required iso-select2">
										{if !empty($list_buildings)}
											{foreach name=i from=$list_buildings item = _oBuilding}
											<option{if $_oBuilding.selected} selected{/if} value="{$_oBuilding.property_id}">{$_oBuilding.title}</option>
											{/foreach}
										{/if}
									</select>
								</div>
							{/if}
							{if !$smarty.foreach.k.first}
							<div class="d-flex">
								<button type="button" uid="{$uid}" onClick="$Core.price_sheets.delete_scope(this,event)"
								class="btn btn-sm btn-default">{$core->makeIcon('trash','Xóa')}</button>
							</div>
							{/if}
						</div>
						{/foreach}
					{else}
						{assign var = uid value = $clsISO->getUniqid()}
						<div class="scope_item scope_item_{$uid}">
							<div class="form-group form-row">
								<div class="col-md-6">
									<label class="col-form-label">Chọn dự án</label>
									<select uid="{$uid}" onchange="$Core.price_sheets.load_option_block(this,event)" name="scope[{$uid}][project_id]" toId="block_{$uid}" class="form-control iso-select2 required" data-error="Chưa chọn dự án">
										<option value="0">Chọn dự án</option>
										{foreach name=i from=$list_projects item = project}
										<option {if $project_id eq $project.project_id} selected{/if} value="{$project.project_id}">{$project.title}</option>
										{/foreach}
									</select>
								</div>
								<div class="col-md-6">
									<label class="col-form-label">Chọn phân khu</label>
									{if $stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}
									<select uid="{$uid}" id="block_{$uid}" toId="building_{$uid}" name="scope[{$uid}][block_id][]" multiple="multiple" class="form-control required iso-select2" data-error="Chưa chọn phân khu">
										<option value="0">Chọn phân khu</option>
										{if !empty($list_blocks)}
											{foreach name=i from=$list_blocks item = _oBlock}
											<option{if $block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>
											{/foreach}
										{/if}
									</select>
									{else}
									<select uid="{$uid}" id="block_{$uid}" onchange="$Core.price_sheets.load_option_building(this,event)" toId="building_{$uid}" name="scope[{$uid}][block_id]" class="form-control required iso-select2" data-error="Chưa chọn phân khu">
										<option value="0">Chọn phân khu</option>
										{if !empty($list_blocks)}
											{foreach name=i from=$list_blocks item = _oBlock}
											<option{if $block_id eq $_oBlock.property_id} selected{/if} value="{$_oBlock.property_id}">{$_oBlock.title}</option>
											{/foreach}
										{/if}
									</select>
									{/if}
								</div>
							</div>
							{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
							<div id="building_group_{$uid}" class="form-group {if $action eq '_add' && empty($building_id)}d-none{/if}">
								<label class="col-form-label">Chọn tòa áp dụng</label>
								<select uid="{$uid}" multiple="multiple" id="building_{$uid}" data-placeholder="Chọn tòa nhà"
								name="scope[{$uid}][building_id][]" class="form-control iso-select2" data-error="Chưa chọn tòa nhà">
									{if !empty($list_buildings)}
										{foreach name=i from=$list_buildings item = _oBuilding}
										<option {if $building_id eq $_oBuilding.property_id} selected{/if} value="{$_oBuilding.property_id}">{$_oBuilding.title}</option>
										{/foreach}
									{/if}
								</select>
							</div>
							{/if}
						</div>
					{/if}
					</div>
				</fieldset>
				<button type="button" onClick="$Core.price_sheets.add_scope(this, event)" stock_type="{$stock_type}" class="btn btn-default text-danger">Thêm áp dụng</button>
				<fieldset class="mt-3">
					<legend>Tiến độ thanh toán</legend>
					<ul class="nav nav-tabs nav-tabs-bordered tablinks_{$price_sheet_id}" role="tablist">
						{if !empty($list_plans)}
							{foreach name=p from=$list_plans item=_oPlan}
							<li class="nav-item nav-item-{$price_sheet_id}{if $smarty.foreach.p.first} active{/if}">
								<a href="#{$_oPlan.plan_id}" class="nav-link" data-toggle="tab" role="tab">{$_oPlan.title}</a>
							</li>
							{/foreach}
						{/if}
						<li class="nav-item">
							<a href="javascript:void(0);" class="nav-link text-danger" onClick="$Core.price_sheets.add_price_plan(this, event)" price_sheet_id="{$price_sheet_id}">+ Phương án</a>
						</li>
					</ul>
					<div class="tab-content tabcontents_{$price_sheet_id}">
						{if !empty($list_plans)}
							{foreach name=p from=$list_plans item=_oPlan}
							<div class="tab-pane fade py-3{if $smarty.foreach.p.first} active in{/if}" id="{$_oPlan.plan_id}" role="tabpanel">
								<div class="form-row" style="margin-bottom:10px">
									<div class="col-md-4">
										<label class="col-form-label">Chiết khấu</label>
										<input type="text" class="form-control input-sm" name="plan_meta[{$_oPlan.plan_id}][discount_rate]" placeholder="VD: ~13% hoặc Không áp dụng" value="{$_oPlan.discount_rate}" />
									</div>
									<div class="col-md-4">
										<label class="col-form-label">HTLS (Hỗ trợ lãi suất)</label>
										<input type="text" class="form-control input-sm" name="plan_meta[{$_oPlan.plan_id}][htls_rate]" placeholder="VD: 0% hoặc Không áp dụng" value="{$_oPlan.htls_rate}" />
									</div>
									<div class="col-md-4">
										<label class="col-form-label">HTLS đến</label>
										<input type="text" class="form-control input-sm" name="plan_meta[{$_oPlan.plan_id}][htls_until]" placeholder="VD: 31/12/2028" value="{$_oPlan.htls_until}" />
									</div>
								</div>
								<fieldset>
									<legend>Đợt thanh toán</legend>
									<div style="display:flex; align-items:center; gap:8px; margin-bottom:10px; flex-wrap:wrap">
										<label class="text-muted" style="margin:0">Giả định ngày cọc:</label>
										<input type="date" class="form-control input-sm ptg-cocdate" id="cocdate_{$price_sheet_id}_{$_oPlan.plan_id}" onchange="$Core.price_sheets.refresh_eta('{$price_sheet_id}','{$_oPlan.plan_id}')" data-ps="{$price_sheet_id}" data-pp="{$_oPlan.plan_id}" style="max-width:170px" />
										<small class="text-muted">(chỉ xem trước, không lưu)</small>
									</div>
									<table class="table text-nowrap">
										<thead><tr>
											<th class="text-center" width="10%">Đợt</th>
											<th class="text-left" width="35%">Tên mốc/đợt</th>
											<th class="text-right" width="15%">Cách đợt trước</th>
											<th class="text-right" width="15%">Ngày dự kiến</th>
											<th class="text-right" width="10%">Tỉ lệ nộp</th>
											<th class="text-right" width="10%">VAT</th>
											<th class="text-center" width="5%"></th>
										</tr></thead>
										<tbody class="price_sheets_{$price_sheet_id}_{$_oPlan.plan_id}">
											{$_oPlan.rows_html}
										</tbody>
										<tfoot>
											<tr class="ptg-totals-{$price_sheet_id}-{$_oPlan.plan_id}">
												<td colspan="4" class="text-right text-muted"><strong>Tổng tỷ lệ:</strong></td>
												<td class="text-right ptg-total-rate"><strong>0%</strong></td>
												<td colspan="2" class="ptg-total-note text-muted"  style="white-space: break-spaces"></td>
											</tr>
										</tfoot>
									</table>
								</fieldset>
								<button type="button" onClick="$Core.price_sheets.open_option(this, event)" price_sheet_id="{$price_sheet_id}" price_plan_id="{$_oPlan.plan_id}" stock_type="{$stock_type}" class="btn btn-default text-danger"><i class="fa fa-plus"></i> Thêm đợt thanh toán</button>
								<button type="button" onClick="$Core.price_sheets.edit_price_plan(this, event)" price_sheet_id="{$price_sheet_id}" price_plan_id="{$_oPlan.plan_id}" class="btn btn-link text-muted"><i class="fa fa-edit"></i> Sửa tên</button>
								<button type="button" onClick="$Core.price_sheets.clone_price_plan(this, event)" price_sheet_id="{$price_sheet_id}" price_plan_id="{$_oPlan.plan_id}" class="btn btn-link text-muted"><i class="fa fa-copy"></i> Nhân bản phương án</button>
								<button type="button" onClick="$Core.price_sheets.delete_price_plan(this, event)" price_sheet_id="{$price_sheet_id}" price_plan_id="{$_oPlan.plan_id}" class="btn btn-link text-muted"><i class="fa fa-trash"></i> Xóa phương án</button>
							</div>
							{/foreach}
						{else}
							<p class="text-muted py-3">Chưa có phương án. Bấm "+ Phương án" để thêm lịch thanh toán.</p>
						{/if}
					</div>
				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success pull-right" onClick="$Core.price_sheets.pop_save_price_sheet(this, event)"
					price_sheet_id="{$price_sheet_id}">{if $action eq '_add'}Lưu lại{else}Cập nhật{/if}</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">Đóng</button>
			</div>
		</form>
	</div>
</div>
{literal}
<style type="text/css">
	.datepicker{ max-width:100%}
	.form-group{ margin-bottom:10px !important;}
	.scope_item{ padding:10px; margin-bottom:5px; border:1px solid #DDD; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; -khtml-border-radius:3px; }
</style>
{/literal}
