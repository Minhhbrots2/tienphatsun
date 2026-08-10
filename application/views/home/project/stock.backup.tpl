<script type="text/javascript">
	var project_id = '{$project_id}',
		block_type = '{$block_type}',
		_BLOCK_TYPE_LOWFLOOR_SALE = '{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}',
		_BLOCK_TYPE_HIGHLEVEL_SALE = '{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}';
</script>
<link rel="stylesheet" href="{$URL_CSS}/leaflet/leaflet.min.css?v={$upd_version}"/>
<link rel="stylesheet" href="{$URL_CSS}/leaflet/leaflet.draw.css?v={$upd_version}"/>
<link rel="stylesheet" href="{$URL_CSS}/leaflet/Control.MiniMap.css?v={$upd_version}" />
<script src="{$URL_JS}/leaflet/store.min.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item">
				<a href="{$PCMS_URL}/bang-hang/">Bảng hàng dự án</a>
			</li>
			<li class="breadcrumb-item">
				<a href="{$clsProject->getLinkDetail($project_id,0,0,$oneProject)}">{$clsProject->getCode($project_id,$oneProject)}</a>
			</li>
			{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
			<li class="breadcrumb-item">
				<a href="{$clsProject->getLinkDetail($project_id,$oneBuilding.for_id,0,$oneProject)}">{$clsProperty->getTitle($oneBuilding.for_id)}</a>
			</li>			
			<li class="breadcrumb-item active">{$oneBuilding.title}</li>
			{/if}
		</ol>
	</nav>
	{$core->getBlock("banner_stock", ['oneBuilding' => $oneBuilding])}
	<div class="clearfix"></div>
	<div class="card">
		<div class="card-body">
			{if $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
				<div class="d-flex justify-content-center">
					<div class="input-group justify-content-center mb-2 no-shadow">
						{if !empty($vr_link)}
						<a class=" btn js-ripple text-white{if $deviceType eq 'phone'} btn-sm d-flex align-items-center justify-content-center px-1 flex-fill{/if}" title="Xem VR360" href="{$vr_link}" data-fancybox data-type="iframe" style="background: #5a5a5a"><img src="{$URL_IMAGES}/vr360.png" class="w-px-20" style="filter: invert(1);">{if $deviceType ne 'phone'}VR360{/if}</a>
						{/if}
						{if $is_map eq '1'}
						<a href="/project/p{$project_id}/b{$building_id}/map.html" title="Xem dạng layout" class="text-white js-ripple btn{if $deviceType eq 'phone'} btn-sm px-1 flex-fill{/if}" style="background: #583400">
							<i class='bx bx-map-alt {if $deviceType eq "phone"}fs-14{/if}'></i> Map
						</a>
						{/if}
						{if $is_map_dq eq '1'}
						<a href="javascript:void(0)" class="btn js-ripple bg-main text-white{if $deviceType eq 'phone'} btn-sm px-1 flex-fill{/if}" title="Xem dạng layout banner" onClick="$Core.project.open_map(this, event)" block_id="{$block_id}" building_id="{$building_id}"><img src="{$URL_IMAGES}/logo-f.png" width="{if $deviceType eq 'phone'}14px{else}18px{/if}"> Map ĐQ</a>{/if}
						<a href="javascript:void(0);" class="btn js-ripple text-white{if $deviceType eq 'phone'} btn-sm px-1 flex-fill{/if}" title="Chọn tòa" onClick="$Core.project.toggle_dropdown(this, event)" style="background: #ffab00"><i class='bx bx-buildings {if $deviceType eq "phone"}fs-14{/if}'></i> Chọn tòa</a>
						<a href="javascript:void(0);" building_id="{$building_id}" class="btn js-ripple text-white{if $deviceType eq 'phone'} btn-sm px-1 flex-fill{/if}" onClick="$Core.stock.hide_stock_cross(this, event)" title="Ẩn/hiện quỹ chéo" style="background: #23008d"> {if $is_hide_stock_cross eq '1'}<i class='fa fa-eye-slash'></i> Hiện{else}<i class='fa fa-eye'></i> Ẩn{/if} bảng hàng tổng</a>
					</div>
				</div>
				<div id="tblStock" class="freeze-table text-nowrap">
					<table class="table fixedTable table-stock table-bordered">
						<thead><tr>
							<th class="align-center bg-white text-center"  colspan="2" style="min-width:60px">
								<strong class="fs-18">{$oneBuilding.title_vn}</strong>
							</th>
							{assign var = total_rowspan value = $total_rowspan-7}
							<th class="align-center text-center" colspan="7" style="height:30px">
								{if !empty($list_status)}
									<div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
										{foreach name=i from=$list_status item = _oProp}
											{if $clsISO->checkItemInArray($_oProp.property_id,$arr_status_id_show)}
												{if $smarty.const._STOCK_STATUS_GENERAL_ID eq $_oProp.property_id}
													{assign var = text_color value = 'danger'}
												{else}
													{assign var = text_color value = 'white'}
												{/if}
												<div class="d-flex align-items-center gap-1 text-center" >
													<span style="background:{$_oProp.bgcolor}" class="d-block border rounded-pill w-px-15 h-px-15" ></span>
													<span class="fs-11">{$_oProp.title}</span>
												</div>
											{/if}
										{/foreach}
										<div class="d-flex align-items-center gap-1 text-center" >
											<span class="status_fund_type" title="Thứ cấp"></span>
											<span class="fs-11">Quỹ thứ cấp</span>
										</div>
									</div>
								{/if}
							</th>
							
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
								{assign var = layout value = $clsProject->getFieldInCol($_code,$arr_cols,'layout')}
								{if !empty($layout)}
									<th width="60px" class="text-center js__stock-cell-focus text-white cursor-pointer" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}" data-fancybox="layout_{$_code}" data-fancybox="488" data-src="{$clsISO->getGoogleUrl($layout)}">{$_code} <i class='bx bx-layout fs-12' ></i></th>
								{else}
									<th width="60px" class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$_code}</th>
								{/if}
							{/foreach}
						</tr>
						<tr>
							<th class="cell">DT.Tim</th>
							{foreach name=k from=$arr_stocks item = _code}
							{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
							<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_Tim')}</th>
							{/foreach}
						</tr>
						<tr>
							<th class="cell">DT.TT</th>
							{foreach name=k from=$arr_stocks item = _code}
							{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
							<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'DT_TT')}</th>
							{/foreach}
						</tr>
						<tr>
							<th class="cell">H.BC</th>
							{foreach name=k from=$arr_stocks item = _code}
							{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
							<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'home_direction_id')} </th>
							{/foreach}
						</tr>
						<tr>
							<th class="cell">L. Căn</th>
							{foreach name=k from=$arr_stocks item = _code}
							{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
							<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code, $arr_cols, 'bedroom_id')}</th>
							{/foreach}
						</tr>
						<tr>
							<th class="cell">View</th>
							{foreach name=k from=$arr_stocks item = _code}
							{assign var = beroom_id value = $clsProject->getFieldInCol($_code,$arr_cols,'bedroom_id')}
							<th class="text-center js__stock-cell-focus text-white" code="{$_code}" style="background:{$bedroom_bgcolor_arrs.$beroom_id}">{$clsProject->getFieldInStock($_code,$arr_cols,'view_id',true)}</th>
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
											<th class="cell">T/C</th>
											{foreach name = k from=$_dfloor.arr_merge_cols item = _mcell}
												{assign var = bedroom_id value = $_mcell.bedroom_id}
												{if !empty($_dfloor.cell_merge_arrs)}
													{foreach from=$_dfloor.cell_merge_arrs item = _cell_merge}
														{if $smarty.foreach.k.iteration eq $_cell_merge.start_cell}
														<td rowspan="4" style="background:#ffe59a" colspan="{$_cell_merge.collspan}"></td>
														{/if}
													{/foreach}
												{/if}
												{if !empty($_mcell.code) && $_mcell.code ne '_empty'}
													{assign var=layout value=$clsProperty->getLayout($building_id,$_sfloor,$_mcell.code,'code',$oneBuilding)}
													{if !empty($layout)}
														<td style="background:{$bedroom_bgcolor_arrs.$bedroom_id}" class="text-center text-white cursor-pointer" data-fancybox="layout_{$_mcell.code}" data-fancybox="488" data-src="{$clsISO->getGoogleUrl($layout)}">
															{$_mcell.code} <i class='bx bx-layout fs-12' ></i>
														</td>
													{else}
														<td style="background:{$bedroom_bgcolor_arrs.$bedroom_id}" class="text-center text-white">{$_mcell.code}</td>
													{/if}
												{/if}
											{/foreach}
										</tr>
										<tr class="nohover">
											<th class="cell">DT_TT</th>
											{foreach name=k from=$_dfloor.arr_merge_cols item = _mcell}
											{assign var = bedroom_id value = $_mcell.bedroom_id}
											{if !empty($_mcell.code) && $_mcell.code ne '_empty'}
											<td style="background:{$bedroom_bgcolor_arrs.$bedroom_id}" 
												class="text-center text-white">{$_mcell.DT_TT}</td>
											{/if}
											{/foreach}
										</tr>
										<tr class="nohover">
											<th class="cell">L. Căn</th>
											{foreach  name=k from=$_dfloor.arr_merge_cols item = _mcell}
											{assign var = bedroom_id value = $_mcell.bedroom_id}
											{if !empty($_mcell.code) && $_mcell.code ne '_empty'}
											<td style="background:{$bedroom_bgcolor_arrs.$bedroom_id}" 
												class="text-center text-white">{$clsProperty->getTitle($bedroom_id)}</td>
											{/if}
											{/foreach}
										</tr>
										{/if}
									{/foreach}
								{/if}
							{if !empty($floor_merge_header_arrs) && $hide_row_floor_special eq '0'}
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
									<th style="background:#ffe59a" class="text-center">{$_floor}</th>
									<td class="text-center" colspan="{$more_information.number_house}">Tầng dịch vụ</td>
								</tr>
							{else}
							<tr>
								{assign var=layout_floor value=$clsProperty->getLayout($building_id,$_floor,"",'floor',$oneBuilding)}
								{if !empty($layout_floor)}
									<th class="text-center cursor-pointer" style="background:#ffe59a"  data-fancybox="layout_floor_{$_floor}" data-fancybox="488" data-src="{$clsISO->getGoogleUrl($layout_floor)}">{$_floor} <i class='bx bx-layout fs-12' ></i></th>
								{else}
									<th class="text-center" style="background:#ffe59a">{$_floor}</th>
								{/if}
								{foreach name=k from=$arr_stocks item = _code}
									{assign var = _stock_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'stock_id')}
									{assign var = _agency_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'agency_id')}
									{assign var = _status_id value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'status_id')}
									{assign var = _show_website value = $clsProject->getFieldValue($_floor,$_code,$arr_cells,'show_website')}
									{assign var = _total_price_vat value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'total_price_vat')}
									{assign var = _is_fund_type value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'is_fund_type')}
									{assign var = _is_dq value = $clsProject->getFieldValue($_floor, $_code, $arr_cells, 'is_dq')}
									{if $_stock_id gt '0'}
										{if !empty($_agency_id) && !$clsISO->checkPermission('view_stock_hidden') 
											&& ($list_agency_cached_VIN[$_agency_id] eq '1' || $list_agency_cached_MAS[$_agency_id] eq '1')}
											{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
											<td style="background:{$status_bgcolor_arrs.$_status_id};"></td>
										{else}
											{if !empty($_status_id) && $_status_id gt '0'}
												{if $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}
													<!-- Non -->
												{else}
													{if $is_hide_stock_cross eq '1' && $_agency_id ne $smarty.const._AGENCY_FH_ID}
														{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
													{/if}
													{assign var = _textcolor value = $status_textcolor_arrs.$_status_id}
													<td floor="{$_floor}" code="{$_code}" class="text-center js__stock-cell-focus cursor-pointer position-relative" style="background:{$status_bgcolor_arrs.$_status_id}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID || $_status_id eq $smarty.const._STOCK_STATUS_NON_ID}{else} {if $deviceType eq 'phone'}onClick="$Core.helper.open_stock('{$_stock_id}');" {else}data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto"{/if} data-width="350"{/if}><a href="javascript:void(0)" style="color:{$_textcolor}" class="{$_is_dq}">
														{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}
															<!-- Sold -->
														{else}
															{if !empty($_total_price_vat)}													
																{if $_is_fund_type eq 1}
																	<span class="text_fund_type {if $_is_dq eq 1}classDQ{/if}" title="Thứ cấp"></span>
																{/if}
																{if $clsStock->checkShow($_show_website, 'MOC')}
																	{$_total_price_vat}
																{else}
																	<span class="text-decoration-line-through">{$_total_price_vat}</span>
																{/if}
															{else}
																<!-- Empty -->
															{/if}
														{/if}
													</a></td>
												{/if}
											{else}
												{assign var = _status_id value = $smarty.const._STOCK_STATUS_SOLD_ID}
												{assign var = _bgcolor value = $status_bgcolor_arrs.$_status_id}
												<td floor="{$_floor}" code="{$_code}" style="cursor:pointer; background:{$_bgcolor}"{if $_status_id eq $smarty.const._STOCK_STATUS_SOLD_ID}{else} data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$_stock_id}" data-toggle="webui-popover" data-trigger="click" data-width="350"{/if} class="text-center js__stock-cell-focus"><a href="javascript:void(0)" style="color:{$_textcolor}"><!-- Sold --></a></td>
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
			<div class="d-flex gap-2 mb-2">
				<a href="javascript:void(0);" class="btn flex-fill btn-outline-primary">
					<i class="bx bx-table"></i> 
					{if $deviceType ne 'phone'}Xem dạng bảng{else}Bảng{/if}
				</a>
				<a href="{$PCMS_URL}/project/p{$project_id}/layout.html" class="btn flex-fill btn-outline-default">
					<i class="bx bx-map-pin"></i> 
					{if $deviceType ne 'phone'}Xem dạng mặt bằng{else}Mặt bằng{/if}
				</a>
			</div>
			<hr />
			<form method="POST">
				{$core->getBlock('stock_search', ['project_id' => $project_id])}
			</form>
			<hr />
			<div class="clearfix"></div>
			<div class="tableStock table-container no-shadow mt-3 freeze-table text-nowrap">
				<table class="table no-bootstrap dragable table-grid table-bordered" 
					width="100%" cellpadding="0" cellspacing="0">
					<thead><tr>
						<th class="align-center text-upper h-px-30 text-center" width="100px">Mã căn</th>
						<th class="align-center text-upper h-px-30 text-center" width="100px">Phân khu</th>
						<th class="align-center text-upper h-px-30 text-left">Dãy</th>
						<th class="align-center text-upper h-px-30 text-left" width="80px">Loại hình</th>
						<th class="align-center text-upper h-px-30 text-center" width="80px">DT Đất</th>
						<th class="align-center text-upper h-px-30 text-center" width="80px">DT XD</th>
						<th class="align-center text-upper h-px-30 text-left" width="150px">TCBG</th>
						<th class="align-center text-upper h-px-30 text-center">Hướng</th>
						<th class="align-center text-upper h-px-30 text-left" width="120px">Giá VAT&KPBT</th>
						<!-- <th class="align-center text-upper text-center">Giá Vay 24T</th>
						<th class="align-center text-upper text-center">Giá Vay 36T</th>
						<th class="align-center text-upper text-center">Giá TTTĐ</th>
						<th class="align-center text-upper text-center">Giá TTS</th> -->
						<th class="align-center text-upper h-px-30 text-left">CSBH</th>
						<th class="align-center text-upper h-px-30 text-left">Ngày ký cọc</th>
						<!--<th class="align-center text-upper text-center">Chính sách</th>-->
						<th class="align-center text-upper h-px-30 text-center">P.Tạm tính</th>
						<th class="align-center text-upper h-px-30 text-center">Loại hình ký</th>
						<th class="align-center text-upper h-px-30 text-left">Quỹ đầu tư</th>
						<th class="align-center text-upper h-px-30 text-left">Giỏ Bank</th>
						<th class="align-center text-upper h-px-30 text-left">Ghi chú</th>
						<!-- <th class="align-center">Ngày ký cọc</th> -->
						<!-- <th class="align-center text-center">CT ký HĐ</th>
						<th class="align-center text-center">Giỏ Bank TC</th>
						<th class="align-center">Tình trạng</th>
						<th class="align-center text-center">TT bán</th>
						<th class="align-center text-center">ĐL lock</th>
						<th class="align-center text-center">ĐL cọc</th> -->
					</tr></thead>
					<tbody class="holder_stock_{$project_id}">
						{section name=i loop=$list_preloaders max=30}
						<tr>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<div class="d-flex justify-content-between py-2 text-center">
				<button type="button" class="showmorethisresult d-none" title="Xem thêm" 
					onClick="$Core.stock.load_more(this, event)" page="2"> 
					<span>Xem thêm</span> 
					<i class="bx bx-chevrons-down translate-px-2"></i>
				</button> 
			</div>
			{literal}
			<script type="text/javascript">
				$(function(){ 
					setTimeout(() => {
						$Core.stock.load_stock(project_id, {}); 
					}, 500);
				});
			</script>
			{/literal}
			{/if}
			<!-- End bảng hàng -->
			{if !empty($onePolicy)}
			<div class="py-2 d-flex flex-wrap gap-2 align-items-center justify-content-center">
				{if !empty($vr_link)}
				<a class="btn btn-outline-default text-main fw-bold{if $deviceType eq 'phone'} flex-fill{/if}" href="{$vr_link}" data-fancybox data-type="iframe" data-caption="{if !empty($vr_source)}{$vr_source}{else}Bản quyền thuộc về masterihomes.com{/if}"><i class="material-icons-outlined text-main">360</i> Xem sa bàn ảo 360<sup>o</sup></a>
				{/if}
				<a href="{$onePolicy.link_ns}" class="btn btn-outline-default{if $deviceType eq 'phone'} flex-fill{/if}" target="_blank">{$clsISO->makeIcon('bx-check-shield','Chính sách bán hàng')} {$oneBuilding.title}</a> 
				<a href="{$onePolicy.link_ms}" class="btn btn-outline-default{if $deviceType eq 'phone'} flex-fill{/if}" target="_blank">{$clsISO->makeIcon('bx-spreadsheet','Phiếu tính giá')} {$oneBuilding.title}</a>
			</div>
			{/if}
			{if !empty($list_help_links)}
			<div class="d-flex py-2 flex-wrap align-items-center justify-content-center">
				{foreach from=$list_help_links item = link} <!-- data-bs-toggle="tooltip" -->
				<a class="mx-1"{if $link.is_driver eq '0'} data-fancybox{/if} target="_blank" title="{$link.title}" href="{$link.content}">&bull; {$link.title} {$oneBuilding.title}</a>
				{/foreach}
			</div>
			{/if}
		</div>
		{if !empty($oneBuilding.intro)}
		<div class="p-4 mt-4 bg-lighter rounded-2">
			<div class="tinyContent js__readmore-content">
				{$oneBuilding.intro}
			</div>
		</div>
		{/if}
	</div>
</div>
<script src="{$URL_JS}/connectingLine/jquery.connectingLine.js?v={$upd_version}"></script>
{literal}
<style type="text/css">
	.freeze-table{
		user-select:none;
		-moz-user-select:none;
		-khtml-user-select:none;
		-webkit-user-select:none;
		-o-user-select:none;
		overflow-x:auto;
		-webkit-overflow-scrolling:touch
	}
	.table-stock .cell{
		position:relative;
		background:#287e3f!important;
		color:rgba(255,255,255,1)!important
	}
	.table-tooltip th,.table-tooltip td{
		padding:.325rem .625rem
	}
	.table-grid td{
		padding: 0.325rem 0.325rem
	}
	.table-grid th{
		line-height:16px; 
		background:rgb(245 247 248);
	}
	@media screen and (min-width:1400px) {
		.table-stock{ table-layout:fixed;}
		.table-stock th,
		.table-stock td{
			font-size:10px;
			padding:.125rem .325rem; 
			height:16px; 
			line-height:16px;
		}
		.table-grid th{
			padding:0.625rem 0.325rem
		}
	}
	@media screen and (max-width:1400px) {
		.table-stock th,
		.table-stock td{font-size:8px; padding:.125rem .2rem}
	}
	@media screen and (max-width:575px) {
		.om-xs\:mb-1{ margin-bottom:10px;}
	}
	.box_tool_tip {
		font-weight: bold;
		font-size: 11px;
		color:var(--bs-white);
		background: #FFF0;
		border: none !important;
		box-shadow: none !important;
		border-radius:10px 0 10px 0;
	}
	.box_tool_tip:before,
	.box_tool_tip .leaflet-tooltip-arrow {
		/* display: none;*/
	}
	.item_tooltip {
		width: 130px;
		text-align: center;
		background: #eadcc1;
		border: 1px solid #eadcc1;
		border-radius: 10px;
		overflow: hidden;
	}
	.item_tooltip .box_code {
		padding: 3px 5px;
		font-weight: bold;
		font-size: 16px;
		background: #966334;
		color: #f5fac0;
		text-shadow: 2px 1px black;
	}
	.item_tooltip .body_tooltip {
		padding: 5px;
	}
	.item_tooltip .box_text {
		font-size: 9px;
		margin-bottom: 3px;
		color: #342828;
	}
	.item_tooltip .text-value {
		font-weight: 700;
		font-size: 11px;
	}
	.item_tooltip .text-price {
		padding: 3px;
		font-size: 14px;
		background: #966334;
		color: #f5fac0;
		text-shadow: 2px 1px #51341b;
		margin-top: 3px;
		border-radius: 5px;
	}
</style>
{/literal}