{literal}
<style type="text/css">
	.freeze-table{user-select:none;-moz-user-select:none;-khtml-user-select:none;
	-webkit-user-select:none;-o-user-select:none;overflow-x:auto;-webkit-overflow-scrolling:touch}
	.table-stock{overflow:hidden;margin-bottom:0}
	.table-stock .cell{position:relative;background:#287e3f!important;color:rgba(255,255,255,1)!important}
	.table-tooltip th,.table-tooltip td{padding:.325rem .625rem}
	.table-grid td{padding: 0.325rem 0.325rem}
	.table-grid th{line-height:16px; background:#e7e7e7;}
	@media screen and (min-width:1366px) {
		.table-grid th{padding:0.625rem 0.325rem}
		.table-stock th,
		.table-stock td{font-size:10px;padding:0 .325rem}
	}
	@media screen and (max-width:1366px) {
		.table-stock th,
		.table-stock td{font-size:8px; padding:.125rem .2rem}
	}
	@media screen and (max-width:575px) {
		.om-xs\:mb-1{ margin-bottom:10px;}
	}
</style>
{/literal}
<script type="text/javascript">
	var project_id = '{$project_id}';
</script>
<div class="container-xxl flex-grow-1 container-p-y">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h4 class="fw-bold mb-0 {if $deviceType eq 'phone'} fs-18{/if}">
			<span class="text-muted fw-light">{$clsProject->getTitle($project_id)}</span>
			{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
				<span class="text-muted fw-light">{$clsProperty->getTitle($oneBuilding.for_id)} /</span>
				{$oneBuilding.title}
			{else}
				{if $block_id gt '0'}
					/ {$clsProperty->getTitle($block_id)}
				{/if}
				(<strong class="total_stock text-main">0</strong>)
			{/if}
		</h4>
		{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
		<div class="dropdown">
			<button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" 
			data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Chọn dự án</button>
			{if !empty($list_projects)}
			<ul class="dropdown-menu dropdown-scrollable">
				{foreach name=i from=$list_projects item = _oProject}
				{assign var = list_menu_blocks value = $_oProject.list_menu_blocks}
				{if !empty($list_menu_blocks)}
				<li class="dropdown-item">
					<a href="/project/{$_oProject.project_id}.html" class="fw-bold dropdown-link">
						<div data-i18n="Dự án {$_oProject.code}">Dự án {$_oProject.code}</div>
					</a>
					<ul class="dropdown-menu-sub list-unstyled">
						{foreach name=k from=$list_menu_blocks item = _oBlock}
						<li class="dropdown-item-sub">
							<a href="{$_oBlock.link}" class="dropdown-link">
								<div data-i18n="{$_oProject.title}">{$clsISO->makeIcon('bx-chevron-right', $_oBlock.title)}</div>
							</a>
						</li>
						{/foreach}
					</ul>
				</li>
				{/if}
				{/foreach}
			</ul>
			{/if}
		</div>
		{/if}
	</div>
	<div class="clearfix"></div>
	<div class="card">
		<div class="card-body">
			{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
			<div class="freeze-table dragscroll text-nowrap">
				<table class="table table-stock table-bordered"{if $deviceType ne 'phone'} style="table-layout:fixed"{/if}>
					<thead><tr>
						<th class="text-center" colspan="2" width="110px">
							<strong class="fs-18">{$oneBuilding.property_code}</strong>
						</th>
						{foreach name=i from=$list_status item = _oProp}
						{if $smarty.const._STOCK_STATUS_GENERAL_ID eq $_oProp.property_id}
							{assign var = text_color value = 'danger'}
						{else}
							{assign var = text_color value = 'white'}
						{/if}
						<th class="text-center" style="background:{$_oProp.bgcolor}">
							<span class="text-{$text_color}">{$_oProp.title}</span>
						</th>
						{/foreach}
						<th class="text-center" colspan="{$total_rowspan-1}">
							{if !empty($more_information.title_ts)}
								{$more_information.title_ts}
							{else}
								Note: Giá đã bao gồm VAT & KPBT
							{/if}
						</th>
					</tr>
					<tr>
						<th class="cell">T/C</th>
						{foreach name=k from=$arr_stocks item = _code}
						{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
						<th width="60px" class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$_code}</th>
						{/foreach}
					</tr>
					<tr>
						<th class="cell">DT.Tim</th>
						{foreach name=k from=$arr_stocks item = _code}
						{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
						<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_Tim')}</th>
						{/foreach}
					</tr>
					<tr>
						<th class="cell">DT.TT</th>
						{foreach name=k from=$arr_stocks item = _code}
						{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
						<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_TT')}</th>
						{/foreach}
					</tr>
					<tr>
						<th class="cell">H.BC</th>
						{foreach name=k from=$arr_stocks item = _code}
						{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
						<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'home_direction_id')} </th>
						{/foreach}
					</tr>
					<tr>
						<th class="cell">L. Căn</th>
						{foreach name=k from=$arr_stocks item = _code}
						{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
						<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'bedroom_id')}</th>
						{/foreach}
					</tr>
					<tr>
						<th class="cell">View</th>
						{foreach name=k from=$arr_stocks item = _code}
						{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
						<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code,$arr_cols,'view_id',true)}</th>
						{/foreach}
					</tr></thead>
					<tbody>
						{foreach name=i from=$arr_floors item = _floor}
							{if !empty($floor_merge_arrs)}
								{foreach from = $floor_merge_arrs key = _sfloor item = _dfloor}
									{if $_floor eq $_sfloor}
									{if $_floor eq $clsISO->getFirstItemInArray($arr_floors)}
									<tr class="nohover">
										<td colspan="{$more_information.number_house+1}" class="h-px-20"></td>
									</tr>
									{/if}
									<tr class="nohover">
										<td class="cell">T/C</td>
										{foreach name = k from=$_dfloor.arr_merge_cols item = _mcell}
											{assign var = bedroom_id value = $_mcell.bedroom_id}
											{if !empty($_dfloor.cell_merge_arrs)}
												{foreach from=$_dfloor.cell_merge_arrs item = _cell_merge}
													{if $smarty.foreach.k.iteration eq $_cell_merge.start_cell}
													<td rowspan="4" style="background:#ffe59a" colspan="{$_cell_merge.collspan}"></td>
													{/if}
												{/foreach}
											{/if}
											{if $_mcell.code ne '_empty'}
											<td style="background:{$bedroom_bgcolor_arrs.$bedroom_id}" class="text-center text-white">
												{$_mcell.code}
											</td>
											{/if}
										{/foreach}
									</tr>
									<tr class="nohover">
										<td class="cell">DT_TT</td>
										{foreach name=k from=$_dfloor.arr_merge_cols item = _mcell}
										{assign var = bedroom_id value = $_mcell.bedroom_id}
										{if $_mcell.code ne '_empty'}
										<td style="background:{$bedroom_bgcolor_arrs.$bedroom_id}" class="text-center text-white">
											{$_mcell.DT_TT}
										</td>
										{/if}
										{/foreach}
									</tr>
									<tr class="nohover">
										<th class="cell">L. Căn</th>
										{foreach  name=k from=$_dfloor.arr_merge_cols item = _mcell}
										{assign var = bedroom_id value = $_mcell.bedroom_id}
										{if $_mcell.code ne '_empty'}
										<td style="background:{$bedroom_bgcolor_arrs.$bedroom_id}" class="text-center text-white">
											{$clsProperty->getTitle($bedroom_id)}
										</td>
										{/if}
										{/foreach}
									</tr>
									{/if}
								{/foreach}
							{/if}
						{if !empty($floor_merge_header_arrs)}
							{if $clsISO->checkInArray($floor_merge_header_arrs, $_floor)}
							<tr>
								<th class="cell">T/C</th>
								{foreach name=k from=$arr_stocks item = _code}
								{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
								<th width="60px" class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$_code}</th>
								{/foreach}
							</tr>
							<tr>
								<th class="cell">L. Căn</th>
								{foreach name=k from=$arr_stocks item = _code}
								{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
								<th class="text-center text-white" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'bedroom_id')}</th>
								{/foreach}
							</tr>
							{/if}
						{/if}
						{if $clsISO->checkInArray($floor_service_arrs, $_floor)}
							<tr class="nohover">
								<td style="background:#ffe59a" class="text-center">13</td>
								<td class="text-center" colspan="19">Tầng dịch vụ</td>
							</tr>
						{else}
						<tr>
							<th class="text-center" style="background:#ffe59a">{$_floor}</th>
							{foreach name=k from=$arr_stocks item = _code}
								{assign var = _stock_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'stock_id')}
								{assign var = _agency_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'agency_id')}
								{assign var = _status_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'status_id')}
								{assign var = _total_price_vat value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'total_price_vat')}
								{if $_stock_id gt 0}
									{if !empty($_agency_id) && $list_agency_cached[$_agency_id] eq '1' && !$clsISO->checkPermissionGroup('DIRECTOR')}
										{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
										<td style="background:{$status_bgcolor_arrs.$_status_id};"></td>
									{else}
										{if !empty($_status_id)}
											{if $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}
												
											{else}
												{assign var = _textcolor value = $status_textcolor_arrs.$_status_id}
												<td class="text-center cursor-pointer" style="background:{$status_bgcolor_arrs.$_status_id}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID || $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}{else} {if $deviceType eq 'phone'}onClick="$Core.helper.open_stock('{$_stock_id}');" {else}data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto"{/if} data-width="350"{/if}><a href="javascript:void(0)" style="color:{$_textcolor}">
													{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}
														<!-- Sold -->
													{else}
														{if !empty($_total_price_vat)}
															{$_total_price_vat}
														{else}
															<!-- Empty -->
														{/if}
													{/if}
												</a></td>
											{/if}
										{else}
											{if !empty($_total_price_vat)}
												{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
												{assign var = _bgcolor value = '#FFF'}
											{else}
												{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
												{assign var = _bgcolor value = $status_bgcolor_arrs.$_status_id}
												{assign var = _textcolor value = $status_textcolor_arrs.$_status_id}
											{/if}
											<td style="cursor:pointer; background:{$_bgcolor}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}{else} data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-width="350"{/if} class="text-center"><a href="javascript:void(0)" style="color:{$_textcolor}">{if !empty($_total_price_vat)}{$_total_price_vat}{else}{/if}</a></td>
										{/if}
									{/if}
								{else}
									{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
									<td style="background:{$status_bgcolor_arrs.$_status_id};"></td>
								{/if}
							{/foreach}
						</tr>
						{/if}
						{/foreach}
					</tbody>
				</table>
			</div>
			{else}
			<form method="POST">
				<div class="p-3 rounded-2 bg-lighter">
					<div class="row om-sm:mb-3">
						<div class="col-12 col-md-4 col-lg-20 om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Phân khu/Block</label>
							<select class="form-control search_field iso-select2" data-placeholder="Phân khu/Block" data-width="100%" data-allowclear="true" 
								project_id="{$project_id}" data-maximum-selection-length="2" onChange="$Core.stock.load_range(this,event)" toId="slb_Range_Id" multiple data-field="blocks_ids[]">
								{if !empty($list_blocks)}
									{foreach name=i from=$list_blocks item=block}
									<option{if $clsISO->checkInArray($get_blocks_ids, $block.property_id)} selected{/if} value="{$block.property_id}">{$block.title}</option>
									{/foreach}
								{/if}
							</select>
						</div>
						<div class="col-12 col-md-4 col-lg-20 om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Dãy nhà</label>
							<select class="form-control search_field iso-select2" id="slb_Range_Id" data-placeholder="Dãy nhà" data-width="100%" data-allowclear="true" 
								project_id="{$project_id}" data-maximum-selection-length="2" onChange="$Core.stock.do_search(this,event)" multiple data-field="range_ids[]">
							</select>
						</div>
						<div class="col-12 col-md-4 col-lg-20 om-xs:mb-1">
							<label class="form-text d-none d-lg-block mb-1">Loại hình</label>
							<select class="form-control search_field iso-select2" data-placeholder="Chọn loại hình" data-width="100%" data-maximum-selection-length="2" onChange="$Core.stock.do_search(this, event)" project_id="{$project_id}" data-allowclear="true" multiple data-field="type_ids[]">
								{$clsProperty->getSelectByPropertyV2('_TYPE_VILLA', $get_type_ids)}
							</select>
						</div>
						<div class="col-12  col-md-6 col-lg-20 om-xs:mb-1">
							<div class="form-group w-100">
								<div class="d-flex eCPuwWeyRr align-items-center justify-content-between">
									<label class="form-text">Khoảng giá (tỷ)</label>
									<label class="form-text mb-1">
										<span class="uZyoMclMcv">0</span>-<span class="pZruODEfWq">50</span> tỷ
									</label>
								</div>
								<input type="hidden" project_id="{$project_id}" onChange="$Core.stock.do_search(this,event)" 
									   class="search_field" data-field="price_range" name="price_range" value="0,50" />
								<div class="px-2">
									<div id="slider-price"></div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6 col-lg-20">
							<div class="form-group w-100">
								<div class="d-flex eCPuwWeyRr align-items-center justify-content-between">
									<label class="form-text">Diện tích (m2)</label>
									<label class="form-text mb-1">
										<span class="wHeOiCWkzb">0</span>-<span class="PQNWDqUlGb">300</span> 
										m<sup>2</sup>
									</label>
								</div>
								<input type="hidden" project_id="{$project_id}" onChange="$Core.stock.do_search(this,event)" 
									   class="search_field" data-field="acreage_range" name="acreage_range" value="0,300" />
								<div class="px-2">
									<div id="slider-acreage"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
			<div class="clearfix"></div>
			<div class="tableStock {if $deviceType ne 'phone'}table-responsive {/if}mt-3 freeze-table dragscroll text-nowrap">
				<table class="table no-bootstrap table-grid table-bordered" width="100%" cellpadding="0" cellspacing="0">
					<thead><tr>
						{if $deviceType eq 'phone'}
						<th class="align-center text-upper text-center" width="100px">Mã căn</th>
						<th class="align-center text-upper text-center" width="80px">DT Đất</th>
						<th class="align-center text-upper text-left">Giá VAT&KPBT</th>
						{else}
						<th class="align-center text-upper text-center" width="100px">Phân khu</th>
						<th class="align-center text-upper text-left" width="80px">Loại hình</th>
						<!--<th class="align-center text-center" width="60px">Mã dãy</th>-->
						<th class="align-center text-upper text-center" width="100px">Mã căn</th>
						<th class="align-center text-upper text-center" width="80px">DT Đất</th>
						<th class="align-center text-upper text-center" width="80px">DT XD</th>
						<th class="align-center text-upper text-left" width="150px">TCBG</th>
						<!-- <th class="align-center text-center">Giá chưa VAT</th> -->
						<th class="align-center text-upper text-left" width="120px">Giá VAT&KPBT</th>
						<!-- <th class="align-center text-upper text-center">Giá Vay 24T</th>
						<th class="align-center text-upper text-center">Giá Vay 36T</th>
						<th class="align-center text-upper text-center">Giá TTTĐ</th>
						<th class="align-center text-upper text-center">Giá TTS</th> -->
						<th class="align-center text-upper text-left">CSBH</th>
						<th class="align-center text-upper text-center">Chính sách</th>
						<th class="align-center text-upper text-center">Phiếu tạm tính</th>
						<th class="align-center text-upper text-center">Loại hình ký HĐ</th>
						<th class="align-center text-upper text-center">Quỹ đầu tư</th>
						<th class="align-center text-upper text-center">Giỏ Bank</th>
						<th class="align-center text-upper text-center">Ghi chú</th>
						<!-- <th class="align-center">Ngày ký cọc</th> -->
						<!-- <th class="align-center text-center">CT ký HĐ</th>
						<th class="align-center text-center">Giỏ Bank TC</th>
						<th class="align-center">Tình trạng</th>
						<th class="align-center text-center">TT bán</th>
						<th class="align-center text-center">ĐL lock</th>
						<th class="align-center text-center">ĐL cọc</th> -->
						{/if}
					</tr></thead>
					<tbody class="holder_stock_{$project_id}">
						<tr>
							<td class="text-center" colspan="30">Loading...</td>
						</tr>
					</tbody>
				</table>
			</div>
			{literal}
			<script type="text/javascript">
				$(function(){
					$Core.stock.load_stock(project_id, {});
					var slider = $( "#slider-price" ).slider({
						range: true,
						step: 5,
						min: 0,
						max: 50,
						values: [ 0, 50 ],
						slide: function( event, ui ) {
							$('.uZyoMclMcv').text(ui.values[0]);
							$('.pZruODEfWq').text(ui.values[1]);
							$('input[name=price_range]').val(ui.values[0]+","+ui.values[1]);
							$Core.stock.load_stock(project_id, {});
						}
					}),
					slider2 = $( "#slider-acreage" ).slider({
						range: true,
						min: 0,
						step: 50,
						max: 300,
						values: [ 0, 300 ],
						slide: function( event, ui ) {
							$('.wHeOiCWkzb').text(ui.values[0]);
							$('.PQNWDqUlGb').text(ui.values[1]);
							$('input[name=acreage_range]').val(ui.values[0]+","+ui.values[1]);
							$Core.stock.load_stock(project_id, {});
						}
					});
				});
			</script>
			{/literal}
			{/if}
			<!-- End bảng hàng -->
			{if !empty($onePolicy)}
			<div class="py-2 d-flex align-items-center justify-content-center">
				<a href="{$onePolicy.link_ns}" class="btn btn-outline-default mr-2" target="_blank">{$clsISO->makeIcon('bx-check-shield','Chính sách bán hàng')} {$oneBuilding.title}</a> 
				<a href="{$onePolicy.link_ms}" class="btn btn-outline-default" target="_blank">{$clsISO->makeIcon('bx-spreadsheet','Phiếu tính giá')} {$oneBuilding.title}</a>
			</div>
			{/if}
			{if !empty($list_help_links)}
			<div class="d-flex py-2 flex-wrap align-items-center justify-content-center">
				{foreach from=$list_help_links item = link}
				<!-- data-bs-toggle="tooltip" -->
				<a class="mx-1"{if $link.is_driver eq '0'} data-fancybox{/if} target="_blank" title="{$link.title}" href="{$link.content}">&bull; {$link.title} {$oneBuilding.title}</a>
				{/foreach}
			</div>
			{/if}
		</div>
		{if !empty($oneBuilding.intro)}
		<div class="p-4 mt-4 bg-lighter rounded-2">
			<div class="tinyContennt">
				{$oneBuilding.intro}
			</div>
		</div>
		{/if}
	</div>
</div>

