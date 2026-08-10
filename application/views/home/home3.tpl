<div class="layout-page">
	<div class="content-wrapper"> 
		<aside id="layout-menu" class="layout-menu menu-vertical menu menu-no-animation bg-menu-theme">
			<div class="menu-inner-shadow"></div>
			<ul id="project_tab" class="menu-inner py-1 ps ps--active-y" role="tablist">
			{if !empty($arr_project)}
				{foreach from=$arr_project item=_oItem key=key name=j}
					{assign var=listTabs value=$_oItem.listTabs}
					{if !empty($listTabs)}
					<li class="menu-item open">
						<a class="menu-link fw-bold" style="color:{$_oItem.bgcolor};">
							<img class="me-2" src="{$_oItem.logo}" width="30" height="30" style="object-fit:contain">
							{$_oItem.title}
						</a>
						<ul class="menu-sub">
							{foreach from=$listTabs name= i key = _oKey item  = _oI}	
							<li class="menu-item {if (($smarty.foreach.j.first && $smarty.foreach.i.first) && empty($block_name)) || $block_name eq $_oI.slug} active{/if}">
								<a href="#{$_oI.slug}" data-bs-toggle="tab" data-bs-target="#{$_oI.slug}" onClick="$Core.project.tab_click(this, event)" slug="{$_oI.slug}" class="menu-link"> 
									<span class="text-truncate">{$_oI.title} - {$_oI.property_code}</span>
								</a>
							</li>
							{/foreach}
						</ul>	
					</li>	
					{/if}
				{/foreach}
			{/if}
			</ul>
		</aside>
		<div class="container-xxl flex-grow-1 container-p-y pt-2">
			<div class="eznyDbxTuI mt-10">
				<div class="d-flex align-items-center justify-content-center mb-3 position-relative">
					<div class="layout-menu-toggle navbar-nav align-items-xl-center me-2 me-xl-0 d-xl-none position-absolute start-0">
						<a class="nav-item nav-link px-0 me-xl-4 text-main" href="javascript:void(0)">
							<i class="bx bx-menu bx-sm"></i>
						</a>
					</div>
				</div>
				<div class="tab-content mt-2">
					{foreach from=$list_tabs name=i key = _oKey item = _oI}
					<div class="tab-pane fade {if ($smarty.foreach.i.first && empty($block_name)) || $block_name eq $_oI.slug} active show{/if}" id="{$_oI.slug}" role="tabpanel">					
							<h2 class="text-center fw-bold text-upper fs-3" style="color: {$_oI.bgcolor}">{$_oI.title}</h2>
						{if $_oI.tab_id eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}
							{assign var = list_projects value = $_oI.list_projects}
							{assign var = list_price_field_configs value = $_oI.list_price_field_configs}
							<div class="w-100 table-container overflow-x-auto" style="max-height:700px">
								<table border="0" cellpadding="0" cellspacing="0" class="table mb-0 table-stock" width="100%">
								{if !empty($list_projects)}
									{foreach from=$list_projects item = _oProject}
									{assign var = list_ms_stocks value = $_oProject.list_ms_stocks}
									{if !empty($list_ms_stocks)}
										<thead class="sticky top-0" style="z-index: 100;"><tr>
											<th class="pheader text-left text-upper" colspan="20">
												<div class="d-flex align-items-center justify-content-between">
													<strong class="fs-6">Quỹ độc quyền {$_oProject.code}</strong>
													<span>{$clsISO->convertTimeToText($smarty.now, true)}</span>
												</div>
											</th>
										</tr>
										<tr class="nohover">
											<th width="49px" class="pcell align-center text-center sticky th-first">STT</th>
											<th class="pcell align-center text-left sticky th-second">Mã căn</th>
											<th class="pcell align-center text-center">Giá Full</th>
											<th class="pcell align-center text-left">Giá TTS</th>
											{foreach from = $list_price_field_configs key = _oField item = _oText}
												{if $_oField ne "total_price_early"}
													<th class="pcell align-center text-center">{$_oText}</th>
												{/if}
											{/foreach}
											<th class="pcell align-center text-left">Phân khu</th>
											<th class="pcell align-center text-center">PTG</th>
											<th class="pcell align-center text-center">Loại hình</th>
											<th class="pcell align-center text-center">Hướng</th>
											<th class="pcell align-center text-center">DT Đất</th>
											<th class="pcell align-center text-center">DT XD</th>
											<th class="pcell align-center text-center">CSBH ngày</th>
											<th class="pcell align-center text-center">Ký cọc</th>
											<th class="pcell align-center text-left">Loại hình ký</th>
										</tr></thead>
										{if !empty($list_ms_stocks)}
											{foreach name=i from=$list_ms_stocks item = _oStock}
												{assign var = stock_id value = $_oStock.stock_id}
												{assign var = price_field_configs value = $_oStock.price_field_configs}
												{assign var = more_information value = $_oStock.more_information}
												<tr class="text-nowrap">
													<td class="text-center sticky">{$smarty.foreach.i.iteration}</td>
													<td class="text-lefts sticky td-first"><a href="javascript:;" class="cursor-pointer fw-bold text-link" data-url="/index.php?mod={$mod}&sub=project&act=get_stock_popover&stock_id={$stock_id}" data-toggle="webui-popover" data-placement="auto" data-trigger="click" data-width="350">{$_oStock.ms_code} {if !empty($_oStock.is_fund_type)}<span class="text_fund_type" title="Thứ cấp"></span>{/if}</a></td>
													<td class="text-center">{if !empty($more_information.total_price_vat)}{$clsISO->formatPriceV4($more_information.total_price_vat)}{else}-{/if}</td>
													<td class="text-center total_price_early">{if !empty($more_information.total_price_early)}{$clsISO->formatPriceV4($more_information.total_price_early)}{else}-{/if}</td>
													{if !empty($list_price_field_configs)}
														{foreach from=$list_price_field_configs key=_OK item = _OF}
															{if $_OK ne "total_price_early"}
															<td class="text-center">
																{if $clsISO->checkInArray($price_field_configs, $_OK)}
																	{if !empty($more_information.$_OK)}
																		{$clsISO->formatPriceV4($more_information.$_OK)}
																	{else}
																		--
																	{/if}
																{else}
																	--
																{/if}
															</td>
															{/if}
														{/foreach}
													{/if}
													<td class="text-left">{$_oStock.block_name}</td>
													<td class="text-center">{if !empty($more_information.price_temporary_ns)}<a class="text-link text-nowrap fs-13" href="{$more_information.price_temporary_ns}" target="_blank">PTG TẠM TÍNH <i class="bx bx-link-external"></i></a>{/if}
													</td>
													<td class="text-center">{if !empty($_oStock.is_fund_type)}<span class="text-success" title="Thứ cấp">Thứ cấp</span>{else}{$_oStock.type_name}{/if}</td>
													<td class="text-center">{$_oStock.home_direction_name}</td>
													<td class="text-center">{$more_information.DT_TT}</td>
													<td class="text-center">{$more_information.DT_Tim}</td>
													<td class="text-center">{$_oStock.csbh}</td>
													<td class="text-center">{$more_information.deposit_date}</td>
													<td class="text-left">{$_oStock.contract_type}</td>
												</tr>
											{/foreach}
										{/if}
									{/if}
									{/foreach}
								{/if}
								</table>
							</div>
							<div class="fs-12 my-1 text-danger">
								<i class='bx bx-help-circle'></i> Click vào mã căn để xem chi tiết
							</div>
						{elseif $_oI.tab_id eq 'VIN'}
							{assign var = list_projects value = $_oI.list_projects}
							{if !empty($list_projects)}
								{foreach from=$list_projects item = _oProject}
									{assign var = list_blocks value = $_oProject.list_blocks}
									{if $_oProject.total_stocks gt '0'}
									<div class="w-100 table-container overflow-x-auto mb-2 mt-3" style="max-height: 700px">
										{if !empty($list_blocks)}
										<table border="0" cellpadding="0" cellspacing="0" class="table mb-0 table-stock-{$_oProject.project_id}" width="100%">
										{foreach from=$list_blocks item = _oBlock}
											{assign var = block_id value = $_oBlock.property_id}
											{assign var = list_buildings value = $_oBlock.list_buildings}
											{assign var = list_price_field value = $_oBlock.list_price_field}
											{if $_oBlock.total_stocks gt '0'}
												<thead class="sticky top-0" style="z-index: 100"><tr>
													<th class="pheader text-left text-upper" colspan="20">
														<div class="d-flex align-items-center justify-content-between">
															<strong class="fs-6">Quỹ độc quyền {$_oBlock.title}</strong>
															<span>{$clsISO->convertTimeToText($smarty.now, true)}</span>
														</div>
													</th>
												</tr>
												<tr class="nohover">
													<th class="pcell align-center text-center sticky th-first">Tòa</th>
													<th class="pcell align-center text-left sticky th-second">Mã căn</th>
													<th class="pcell align-center text-center">Giá VAT</th>
													<th class="pcell align-center text-left">Giá TTS</th>
													{if !empty($list_price_field)}
														{foreach from = $list_price_field key = _oField item = _oText}
															{if $_oField ne "total_price_early"}
																<th class="pcell align-center text-center">{$_oText}</th>
															{/if}
														{/foreach}
													{/if}
													<th class="pcell align-center text-center">Vẽ View</th>
													<th class="pcell align-center text-center">Video</th>
													<th class="pcell align-center text-center">PTG</th>
													<th class="pcell align-center text-center">Loại căn</th>
													<th class="pcell align-center text-center">View</th>
													<!-- <th class="pcell align-center text-center">Video</th> -->
													<th class="pcell align-center text-center">Hướng</th>
													<th class="pcell align-center text-center">DT_TT</th>
													<th class="pcell align-center text-center">Loại hình</th>
													<!-- <th class="pcell align-center text-center">ngày ký XNĐK</th> -->
													<th class="pcell align-center text-center">Thưởng sale</th>
													<th class="pcell align-center text-center">CSBH ngày</th>
												</tr></thead>
												{if !empty($list_buildings)}
													<tbody>
													{foreach from=$list_buildings item = _oBuilding name = _oB}
														{assign var = building_id value = $_oBuilding.property_id}
														{assign var = list_stocks value = $_oBuilding.list_stocks}
														{if !empty($list_stocks)}
															<tr class="building_{$_oBuilding.property_id}">
																<td class="text-center td-group sticky{if $smarty.foreach._oB.last} td-rowspan{/if}" rowspan="{$_oBuilding.total_stocks}">
																	{if !empty($_oBuilding.title_vn)}
																		{$_oBuilding.title_vn}
																	{else}
																		{$_oBuilding.title}
																	{/if}
																</td>
															</tr>
															{foreach name=i from=$list_stocks item = _oStock}
															{assign var = stock_id value = $_oStock.stock_id}
															{assign var = more_information value = $_oStock.more_information}
															<tr class="text-nowrap{if $_oStock.is_sp_mech eq '1'} is_sp_mech{/if} building_{$_oBuilding.property_id}{if isset($more_information.markup_price) && $more_information.markup_price eq '1'} stock_mark nohover{/if}">
																<td class="text-left td-first sticky">{if $_oStock.is_sp_mech eq '1'}<span title="Cơ chế đặc biệt">⭐</span>{/if}<a href="javascript:void(0);" class="cursor-pointer fw-bold text-link" data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$stock_id}" data-toggle="webui-popover" data-placement="auto" data-trigger="click" data-width="350">{$_oStock.ms_code} {if !empty($_oStock.is_fund_type)}<span class="text_fund_type" title="Thứ cấp"></span>{/if}</a></td>
																<td class="text-center{if $_oProject.project_id eq $smarty.const._PROJECT_VHOP2_ID} fw-bold text-red{/if}">{if !empty($more_information.total_price_vat)}{$clsISO->formatPriceV4($more_information.total_price_vat)}{else}-{/if}</td>
																<td class="text-center total_price_early">{if !empty($more_information.total_price_early)}{$clsISO->formatPriceV4($more_information.total_price_early)}{else}-{/if}</td>
																{if !empty($list_price_field)}
																	{foreach from = $list_price_field key = _oField item = _oText}
																		{if $_oField ne "total_price_early"}
																			<td class="bg-price {$_oField} align-center text-center">
																				{if !empty($more_information[$_oField])}
																					{$clsISO->priceFormatV3($more_information[$_oField],3)}
																				{else}
																					-
																				{/if}
																			</td>
																		{/if}
																	{/foreach}
																{/if}
																<td class="text-center">{$_oStock.html_stock_posters}</td>
																<!-- <td class="text-center">{$_oStock.html_stock_posters_video}</td> -->
																<td class="text-center">{$_oStock.html_image_sheets}</td>
																<td class="text-center">{$_oStock.bedroom_name}</td>
																<td class="text-center">
																	{if !empty($_oStock.view_id)}
																		{$_oStock.view_name}
																	{else}
																		--
																	{/if}
																</td>
																<td class="text-center">{$_oStock.home_direction_name}</td>
																<td class="text-center">{$_oStock.DT_TT}</td>
																<td class="text-center">{if !empty($_oStock.is_fund_type)}<span class="text-success" title="Thứ cấp">Thứ cấp</span>{else}{$_oStock.type_name}{/if}</td>
																<!-- <td class="text-center">{$more_information.reg_confirm_date}</td> -->
																<td class="text-center">{$clsISO->formatPriceV2($more_information.sale_bonus)}</td>
																<!-- <td class="text-center">{$more_information.date_deposit_sign}</td> -->
																<td class="text-center">{$_oStock.csbh}</td>
															</tr>
															{/foreach}
														{/if}
													{/foreach}
													</tbody>
													<!-- End Building -->
												{/if}
											{/if}
										{/foreach}
										<!-- End Block -->
										</table>
									{/if}	
									</div>
									<div class="d-flex gap-1 fs-12 align-items-center">
										<div class="w-px-10 bg-yellow h-px-10 rounded-pill"></div>
										<span class="text-danger">Các căn được đánh dấu vui lòng liên hệ admin để check</span>
									</div>
								{/if} <!-- Total stock -->
								{/foreach}
							{/if}
						{elseif $_oI.tab_id eq 'DN' || $_oI.tab_id eq $smarty.const._PROJECT_CSD_ID}
							{assign var = list_projects value = $_oI.list_projects}
							{if !empty($list_projects)}
								{foreach from=$list_projects item = _oProject}
									{assign var = list_blocks value = $_oProject.list_blocks}
									{if $_oProject.total_stocks gt '0'}
									<div class="w-100 table-container overflow-auto mb-2 mt-3" style="max-height: 700px">
										{if !empty($list_blocks)}
										<table border="0" cellpadding="0" cellspacing="0" class="table mb-0 table-stock-color table-stock-{$_oProject.code|lower}" width="100%">
										{foreach from=$list_blocks item = _oBlock}
											{assign var = block_id value = $_oBlock.property_id}
											{assign var = list_buildings value = $_oBlock.list_buildings}
											{assign var = list_price_field value = $_oBlock.list_price_field}
											{if $_oBlock.total_stocks gt '0'}
												<thead class="sticky top-0" style="z-index: 100;background: {$_oI.bgcolor};color: {$_oI.textcolor}"><tr>
													<th class="pheader text-left text-upper" colspan="20">
														<div class="d-flex align-items-center justify-content-between">
															<strong class="fs-6">Quỹ độc quyền {$_oBlock.title}</strong>
															<span>{$clsISO->convertTimeToText($smarty.now, true)}</span>
														</div>
													</th>
												</tr>
												<tr class="nohover">
													<th class="pcell align-center text-center sticky th-first">Tòa</th>
													<th class="pcell align-center text-left sticky th-second" >Mã căn</th>
													<th class="pcell align-center text-center" >Giá VAT</th>
													<th class="pcell align-center text-left" >Giá TTS</th>
													{if !empty($list_price_field)}
														{foreach from = $list_price_field key = _oField item = _oText}
															{if $_oField ne "total_price_early"}
																<th class="pcell align-center text-center" >{$_oText}</th>
															{/if}
														{/foreach}
													{/if}
													<th class="pcell align-center text-center" >Vẽ View</th>
													<!-- <th class="pcell align-center text-center">Video</th> -->
													<th class="pcell align-center text-center">PTG</th>
													<th class="pcell align-center text-center">Loại căn</th>
													<th class="pcell align-center text-center">View</th>
													<th class="pcell align-center text-center">Hướng</th>
													<th class="pcell align-center text-center">DT_TT</th>
													<th class="pcell align-center text-center">Loại hình</th>
													<!-- <th class="pcell align-center text-center">ngày ký XNĐK</th> -->
													<th class="pcell align-center text-center">Thưởng sale</th>
													<th class="pcell align-center text-center">CSBH ngày</th>
												</tr></thead>
												{if !empty($list_buildings)}
													<tbody>
													{foreach from=$list_buildings item = _oBuilding name = _oB}
														{assign var = building_id value = $_oBuilding.property_id}
														{assign var = list_stocks value = $_oBuilding.list_stocks}
														{if !empty($list_stocks)}
															<tr class="building_{$_oBuilding.property_id}">
																<td class="text-center td-group sticky{if $smarty.foreach._oB.last} td-rowspan{/if}" rowspan="{$_oBuilding.total_stocks}">
																	{if !empty($_oBuilding.title_vn)}
																		{$_oBuilding.title_vn}
																	{else}
																		{$_oBuilding.title}
																	{/if}
																</td>
															</tr>
															{foreach name=i from=$list_stocks item = _oStock}
															{assign var = stock_id value = $_oStock.stock_id}
															{assign var = more_information value = $_oStock.more_information}
															<tr class="text-nowrap{if $_oStock.is_sp_mech eq '1'} is_sp_mech{/if} building_{$_oBuilding.property_id}{if isset($more_information.markup_price) && $more_information.markup_price eq '1'} stock_mark nohover{/if}">
																<td class="text-left td-first sticky">{if $_oStock.is_sp_mech eq '1'}<span title="Cơ chế đặc biệt">⭐</span>{/if}<a href="javascript:void(0);" class="cursor-pointer fw-bold text-link" data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$stock_id}" data-toggle="webui-popover" data-placement="auto" data-trigger="click" data-width="350">{$_oStock.ms_code} {if !empty($_oStock.is_fund_type)}<span class="text_fund_type" title="Thứ cấp"></span>{/if}</a></td>
																<td class="text-center{if $_oProject.project_id eq $smarty.const._PROJECT_VHOP2_ID} fw-bold text-red{/if}">{if !empty($more_information.total_price_vat)}{$clsISO->formatPriceV4($more_information.total_price_vat)}{else}-{/if}</td>
																<td class="text-center total_price_early">{if !empty($more_information.total_price_early)}{$clsISO->formatPriceV4($more_information.total_price_early)}{else}-{/if}</td>
																{if !empty($list_price_field)}
																	{foreach from = $list_price_field key = _oField item = _oText}
																		{if $_oField ne "total_price_early"}
																			<td class="bg-price {$_oField} align-center text-center">
																				{if !empty($more_information[$_oField])}
																					{$clsISO->priceFormatV3($more_information[$_oField],3)}
																				{else}
																					-
																				{/if}
																			</td>
																		{/if}
																	{/foreach}
																{/if}
																<td class="text-center">{$_oStock.html_stock_posters}</td>
																<!-- <td class="text-center">{$_oStock.html_stock_posters_video}</td> -->
																<td class="text-center">{$_oStock.html_image_sheets}</td>
																<td class="text-center">{$_oStock.bedroom_name}</td>
																<td class="text-center">
																	{if !empty($_oStock.view_id)}
																		{$_oStock.view_name}
																	{else}
																		--
																	{/if}
																</td>
																<td class="text-center">{$_oStock.home_direction_name}</td>
																<td class="text-center">{$_oStock.DT_TT}</td>
																<td class="text-center">{if !empty($_oStock.is_fund_type)}<span class="text-success" title="Thứ cấp">Thứ cấp</span>{else}{$_oStock.type_name}{/if}</td>
																<!-- <td class="text-center">{$more_information.reg_confirm_date}</td> -->
																<td class="text-center">
																	{if !empty($more_information.sale_bonus)}
																		{$clsISO->formatPriceV2($more_information.sale_bonus)}
																	{else}
																		<span class="text-muted">Không có</span>
																	{/if}
																</td>
																<!-- <td class="text-center">{$more_information.date_deposit_sign}</td> -->
																<td class="text-center">{$_oStock.csbh}</td>
															</tr>
															{/foreach}
														{/if}
													{/foreach}
													</tbody>
													<!-- End Building -->
												{/if}
											{/if}
										{/foreach}
										<!-- End Block -->
										</table>
									{/if}	
									</div>
									<div class="d-flex gap-1 fs-12 align-items-center">
										<div class="w-px-10 bg-yellow h-px-10 rounded-pill"></div>
										<span class="text-danger">Các căn được đánh dấu vui lòng liên hệ admin để check</span>
									</div>
								{/if} <!-- Total stock -->
								{/foreach}
							{/if}
						{else}
							{assign var = oneBlock value = $_oI.oneBlock}
							{assign var = list_buildings value = $_oI.list_buildings}
							{assign var = total_stocks value = $_oI.total_stocks}
							{assign var = list_price_field value = $_oI.list_price_field}
							{if !empty($list_buildings) && $total_stocks gt '0'}
							<div class="w-100 table-container overflow-x-auto mb-2" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">
								<table border="0" cellpadding="0" cellspacing="0" class="table table-stock-color mb-0" width="100%">
									<thead style="background:{$_oI.bgcolor}; color:{$_oI.textcolor}"><tr>
										<th class="pheader top-0 text-left text-upper" colspan="20">
											<div class="d-flex align-items-center justify-content-between">
												<strong class="fs-6">Quỹ độc quyền {$oneBlock.title}</strong>
												<span>{$clsISO->convertTimeToText($smarty.now, true)}</span>
											</div>
										</th>
									</tr>
									<tr class="nohover">
										<th class="pcell align-center sticky text-center th-first" style="background:{$_oI.bgcolor}">Tòa</th>
										<th class="pcell align-center sticky text-left th-second" style="background:{$_oI.bgcolor}">Mã căn</th>
										<th class="pcell align-center text-center">Giá VAT</th>
										<th class="pcell align-center text-left">Giá TTS</th>
										{if !empty($list_price_field)}
											{foreach from = $list_price_field key = _oField item = _oText}
											{if $_oField ne "total_price_early"}
											<th class="pcell align-center text-center">{$_oText}</th>
											{/if}
											{/foreach}
										{/if}
										<th class="pcell align-center text-center">Vẽ View</th>
										<!-- <th class="pcell align-center text-center">Video</th> -->
										<th class="pcell align-center text-center">PTG</th>
										<th class="pcell align-center text-center">Loại căn</th>
										<th class="pcell align-center text-center">View</th>
										<th class="pcell align-center text-center">Hướng</th>
										<th class="pcell align-center text-center">DT_TT</th>
										<th class="pcell align-center text-center">Loại hình</th>
										<!--  <th class="pcell align-center text-center">Ký XNĐK</th> -->
										{if $oneBlock.property_id eq $smarty.const._BLOCK_TPL_ID}
										<th class="pcell align-center text-center">CSBH ngày</th>
										<th class="pcell align-center text-center">Ghi chú</th>
										<th class="pcell align-center text-center">Tình trạng</th>
										{else}
										<th class="pcell align-center text-center">Thưởng sale</th>
										<th class="pcell align-center text-center">CSBH ngày</th>
										{/if}
									</tr></thead>
									<tbody style="color: {$_oI.bgcolor}">
									{foreach from=$list_buildings item = _oBuilding name = _oB}
										{assign var = building_id value = $_oBuilding.property_id}
										{assign var = list_stocks value = $_oBuilding.list_stocks}
										{if !empty($list_stocks)}
											<tr class="building_{$_oBuilding.property_id}">
												<td class="text-center sticky td-group {if $smarty.foreach._oB.last} td-rowspan{/if}" rowspan="{$_oBuilding.total_stocks+1}">
													{if !empty($_oBuilding.title_vn)}
														{$_oBuilding.title_vn}
													{else}
														{$_oBuilding.title}
													{/if}
												</td>
											</tr>
											{foreach name=i from=$list_stocks item = _oStock}
											{assign var = stock_id value = $_oStock.stock_id}
											{assign var = more_information value = $_oStock.more_information}
											<tr class="text-nowrap{if $_oStock.is_sp_mech eq '1'} is_sp_mech{/if} building_{$_oBuilding.property_id}{if isset($more_information.markup_price) && $more_information.markup_price eq '1'} stock_mark nohover{/if}">
												<td class="text-left sticky td-first">{if $_oStock.is_sp_mech eq '1'}<span title="Cơ chế đặc biệt">⭐</span>{/if}<a href="javascript:void(0);" class="fw-bold text-link" data-url="/index.php?mod={$mod}&sub=project&act=load_stock_popover&stock_id={$stock_id}" data-toggle="webui-popover" data-placement="auto" data-trigger="click" data-width="350">{$_oStock.ms_code} {if !empty($_oStock.is_fund_type)}<span class="text_fund_type" title="Thứ cấp"></span>{/if}</a></td>
												<td class="text-center">{if !empty($more_information.total_price_vat)}{$clsISO->formatPriceV4($more_information.total_price_vat)}{else}-{/if}</td>
												<td class="text-center total_price_early">{if !empty($more_information.total_price_early)}{$clsISO->formatPriceV4($more_information.total_price_early)}{else}-{/if}</td>
												{if !empty($list_price_field)}
													{foreach from = $list_price_field key = _oField item = _oText}
														{if $_oField ne "total_price_early"}
															<td class="bg-price {$_oField} align-center text-center {$_oText}">
																{if !empty($more_information[$_oField])}
																	{$clsISO->priceFormatV3($more_information[$_oField],3)}
																{else}
																	-
																{/if}
															</td>
														{/if}
													{/foreach}
												{/if}
												<td class="text-center">{$_oStock.html_stock_posters}</td>
												<!-- <td class="text-center">{$_oStock.html_stock_posters_video}</td> -->
												<td class="text-center">{$_oStock.html_image_sheets}</td>
												<td class="text-center">{$_oStock.bedroom_name}</td>
												<td class="text-center">
													{if !empty($_oStock.view_id)}
														{$_oStock.view_name}
													{else}
														--
													{/if}
												</td>
												<td class="text-center">{$_oStock.home_direction_name}</td>
												<td class="text-center">{$_oStock.DT_TT}</td>
												<td class="text-center">{if !empty($_oStock.is_fund_type)}<span class="text-success" title="Thứ cấp">Thứ cấp</span>{else}{$_oStock.type_name}{/if}</td>
												<!-- <td class="text-center">{$more_information.reg_confirm_date}</td> -->
												{if $oneBlock.property_id eq $smarty.const._BLOCK_TPL_ID}
												<td class="text-center">{$_oStock.csbh}</td>
												<td class="text-center">
													{if !empty($more_information.sale_bonus)}
														{*$clsISO->formatPriceV2($more_information.sale_bonus)*}
														{$more_information.sale_bonus}
													{else}
														<span class="text-muted">Không có</span>
													{/if}
												</td>
												<td class="text-center">
													{if !empty($more_information.first_payment_amount)}
														{$clsISO->formatPriceV2($more_information.first_payment_amount)}
													{else}
														--
													{/if}
												</td>
												{else}
												<td class="text-center">
													{if !empty($more_information.sale_bonus)}
														{$clsISO->formatPriceV2($more_information.sale_bonus)}
													{else}
														<span class="text-muted">Không có</span>
													{/if}
												</td>
												<!-- <td class="text-center">{$more_information.date_deposit_sign}</td> -->
												<td class="text-center">{$_oStock.csbh}</td>
												{/if}
											</tr>
											{/foreach}
										{/if}
									{/foreach}
									</tbody>
								</table>
							</div>
							<div class="d-flex gap-1 fs-12 align-items-center">
								<div class="w-px-10 bg-yellow h-px-10 rounded-pill"></div>
								<span class="text-danger">Các căn được đánh dấu vui lòng liên hệ admin để check</span>
							</div>
							{if !empty($_oI.img_layout)}
							<div id="{$clsISO->getUniqid()}" building_id="{$_oI.building_id}" block_id="{$_oI.tab_id}" 
							tp="{$tp}" img_layout="{$_oI.img_layout}" class="map mt-3" url="{$_oI.img_layout}">
								<!-- Map -->
							</div>
							{/if}
							{/if}
						{/if}
					</div>
					{/foreach}
				</div>
				<!-- End Thấp tầng -->
				<div class="tgbZRaqgdF pt-3">
					<div class="qjkYlYqD9P">
						{$onePage.content}
						{if !empty($contacts)}
							<div class="text-decoration-underline fs-5 my-2">
								<span >☎️&nbsp;<strong>Liên hệ hỗ trợ</strong></span>
							</div>
							<div class="row">
							{foreach from=$contacts item=oneContact name=n_contact}
								{assign var=lstContact value=$oneContact.contact}
								<div class="col-12 col-md-6 col-lg-4 mb-2 fs-16">
									<div class="">
										<div class="fw-bold"><strong>{$smarty.foreach.n_contact.iteration}. {$oneContact.project_name}</strong></div> 
										{foreach from=$lstContact item=contact}
											<div>📞{$contact}</div> 
										{/foreach}
									</div>
								</div>
							{/foreach}
							</div>
							<div class="fs-16">
								<strong><em>Thông tin dự án</em></strong>:&nbsp;<a href="https://myoceancity.vn/">https://myoceancity.vn</a>
							</div>
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script> var block_name = '{$block_name}'; </script>
{literal}
<style>
	.layout-navbar-fixed .layout-wrapper:not(.layout-horizontal):not(.layout-without-menu) .layout-page {
		padding-top: 10px !important;
	}
	.menu-vertical {
		width: 17rem;
	}
	.menu-vertical .menu-inner > .menu-item{
		width: 100%
	}
	#project_tab {
		max-height: calc(100vh - 70px);
		overflow-y: auto;
	}
	.menu-vertical .menu-item .menu-link{
		font-size: 14px
	}
	.bg-menu-theme .menu-inner > .menu-item.active:before {
		background-color: var(--color) !important;
		z-index: 1;
		right: unset !important;
		border-top-right-radius: 9px !important;
		border-bottom-right-radius: 9px !important;
	}
	.bg-menu-theme .menu-inner > .menu-item.active > .menu-link {
		background-color: #e8f0ff !important;
		padding-left: 20px !important;
		color: var(--color) !important;
	}
	.menu-vertical .menu-inner > .menu-item .menu-link{
		transition-delay: .1s;
        transition-property: all;
	}
	/* @media screen and (min-width: 1200px) {
		.layout-page {
			padding-left: 17rem !important;
		}
		.app-brand .layout-menu-toggle{
			left: 14.6rem
		}
		.menu-vertical .menu-inner>.menu-item {
			width: 100%;
			position: relative
		}
		.layout-menu-collapsed:not(.layout-menu-hover):not(.layout-menu-offcanvas):not(.layout-menu-fixed-offcanvas) .layout-menu .app-brand .layout-menu-toggle {
			opacity: 0;
			left: calc(8rem - 1.5rem);
		}
		.layout-menu-hover.layout-menu-collapsed .layout-menu .layout-menu-toggle i {
			transform: rotate(180deg) !important;
			transition-duration: .3s !important;
			transition-property: transform !important;
		}
		.menu-link >:not(.menu-icon) {
			flex: 0 1 auto;
			opacity: 1;
		}
		.menu-vertical .menu-icon{
			width:auto
		}
		.layout-wrapper:not(.layout-horizontal) .bg-menu-theme .menu-inner > .menu-item.active:before{
			height: 100%
		}
		.layout-menu-collapsed:not(.layout-menu-hover):not(.layout-menu-offcanvas):not(.layout-menu-fixed-offcanvas) .layout-menu .app-brand{
			width: auto
		}
	*/
</style>
<script type="text/javascript">
	$(function() {
		$Core.project.init_map();
		let scrollAmount = 150,
			$tabList = $("#project_tab");
		$(".prev-btn").on("click", function(e) {
			e.preventDefault();
			$tabList.animate({scrollLeft:"-="+scrollAmount},100);
			return false;
		});
		$(".next-btn").on("click", function(e) {
			e.preventDefault();
			$tabList.animate({scrollLeft:"+="+scrollAmount},100);
			return false;
		});
		if(!$Core.util.isEmpty(block_name)){
			clearTimeout(_timeOut);
			_timeOut = setTimeout(() => {
				var current_tab = $(`#project_tab a[href="#${block_name}"]`),
					title = current_tab.text();
				current_tab.attr('clicked', 'clicked').tab('show');
				document.title = `${title} | Quỹ căn độc quyền {/literal}{$smarty.const.BRAND_NAME}{literal}`;
			}, 300);
		}
	});	
</script>
{/literal}