<div class="content-wrapper">
	<div class="container-sm container-p-y">
		<div class="eznyDbxTuI mt-10">
			<nav class="navbar navbar-example navbar-light navbar-expand-lg gap-1 justify-content-center menu_project">
				<button class="btn btn-icon btn-outline-dark scroll-btn prev-btn d-none">
					<i class='bx bx-chevron-left'></i>
				</button>
				<div id="project_tab" class="nav nav-pill navbar-nav flex-nowrap gap-2 text-nowrap" role="tablist">
					{foreach from=$list_tabs name= i key = _oKey item  = _oI}
					<a href="/home/{$_oI.slug}" slug="{$_oI.slug}" class="nav-item rounded-pill nav-link {if !empty($_oI.is_active)} active{/if} is-effect {$_oI.slug} {if $_oKey eq 'DN'}MRD{/if}"  style="background: {$_oI.bgcolor};color: {$_oI.textcolor}">{$_oI.title}</a>
					{/foreach}
				</div>
				<button class="btn btn-icon btn-outline-dark scroll-btn next-btn d-none">
					<i class='bx bx-chevron-right'></i>
				</button>
			</nav>
			<div class="tab-content mt-3">
				{foreach from=$list_tabs name=i key = _oKey item = _oI}					
					{if !empty($_oI.is_active)}
					<div class="tab-pane fade{if !empty($_oI.is_active)} active show{/if}" id="{$_oI.slug}" role="tabpanel">
						{if $_oKey eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}
							{assign var = list_projects value = $_oI.list_projects}
							{assign var = list_price_field_configs value = $_oI.list_price_field_configs}
							<div class="w-100 table-container" >
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
											<th class="pcell align-center text-center">Phiếu TG</th>
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
						{elseif $_oKey eq 'VIN'}
							{assign var = list_projects value = $_oI.list_projects}
							{if !empty($list_projects)}
								{foreach from=$list_projects item = _oProject}
									{assign var = list_blocks value = $_oProject.list_blocks}
									{if $_oProject.total_stocks gt '0'}
									<div class="w-100 table-container table-container-{$_oI.cls}  mb-2 mt-3" >
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
													<th class="pcell align-center text-center">Phiếu TG</th>
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
						{elseif $_oKey eq 'DN' || $_oKey eq $smarty.const._PROJECT_CSD_ID}
							{assign var = list_projects value = $_oI.list_projects}
							{if !empty($list_projects)}
								{foreach from=$list_projects item = _oProject}
									{assign var = list_blocks value = $_oProject.list_blocks}
									{if $_oProject.total_stocks gt '0'}
									<div class="w-100 table-container table-container-{$_oI.cls} table-container-{$_oProject.code|lower}  mb-2 mt-3" >
										{if !empty($list_blocks)}
										<table border="0" cellpadding="0" cellspacing="0" class="table mb-0 table-stock-color table-stock-{$_oProject.code|lower}" width="100%">
										{foreach from=$list_blocks item = _oBlock}
											{assign var = block_id value = $_oBlock.property_id}
											{assign var = list_buildings value = $_oBlock.list_buildings}
											{assign var = list_price_field value = $_oBlock.list_price_field}
											{if $_oBlock.total_stocks gt '0'}
												<thead class="sticky top-0" style="z-index: 100;background: {$_oI.bgcolor};color: {$_oI.textcolor}"><tr>
													<th class="pheader text-left text-upper" colspan="20" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">
														<div class="d-flex align-items-center justify-content-between">
															<strong class="fs-6">Quỹ độc quyền {$_oBlock.title}</strong>
															<span>{$clsISO->convertTimeToText($smarty.now, true)}</span>
														</div>
													</th>
												</tr>
												<tr class="nohover">
													<th class="pcell align-center text-center sticky th-first" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Tòa</th>
													<th class="pcell align-center text-left sticky th-second" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Mã căn</th>
													<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Giá VAT</th>
													<th class="pcell align-center text-left" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Giá TTS</th>
													{if !empty($list_price_field)}
														{foreach from = $list_price_field key = _oField item = _oText}
															{if $_oField ne "total_price_early"}
																<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">{$_oText}</th>
															{/if}
														{/foreach}
													{/if}
													<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Vẽ View</th>
													<!-- <th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Video</th> -->
													<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Phiếu TG</th>
													<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Loại căn</th>
													<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">View</th>
													<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Hướng</th>
													<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">DT_TT</th>
													<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Loại hình</th>
													<!-- <th class="pcell align-center text-center">ngày ký XNĐK</th> -->
													<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Thưởng sale</th>
													<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">CSBH ngày</th>
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
						{else}
							{assign var = oneBlock value = $_oI.oneBlock}
							{assign var = list_buildings value = $_oI.list_buildings}
							{assign var = total_stocks value = $_oI.total_stocks}
							{assign var = list_price_field value = $_oI.list_price_field}
							{if !empty($list_buildings) && $total_stocks gt '0'}
							<div class="w-100 table-container table-container-{$_oI.cls} mb-2" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">
								<table border="0" cellpadding="0" cellspacing="0" class="table table-stock-color mb-0 table-stock-{$oneBlock.for_id} table-stock-{$_oI.cls}" width="100%">
									<thead class="sticky top-0" style="z-index: 100;background: {$_oI.bgcolor};color: {$_oI.textcolor}"><tr>
										<th class="pheader text-left text-upper" colspan="20" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">
											<div class="d-flex align-items-center justify-content-between">
												<strong class="fs-6">Quỹ độc quyền {$oneBlock.title}</strong>
												<span>{$clsISO->convertTimeToText($smarty.now, true)}</span>
											</div>
										</th>
									</tr>
									<tr class="nohover">
										<th class="pcell align-center text-center sticky th-first" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Tòa</th>
										<th class="pcell align-center text-left sticky th-second" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Mã căn</th>
										<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Giá VAT</th>
										<th class="pcell align-center text-left" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Giá TTS</th>
										{if !empty($list_price_field)}
											{foreach from = $list_price_field key = _oField item = _oText}
											{if $_oField ne "total_price_early"}
											<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">{$_oText}</th>
											{/if}
											{/foreach}
										{/if}
										<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Vẽ View</th>
										<!-- <th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Video</th> -->
										<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Phiếu TG</th>
										<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Loại căn</th>
										<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">View</th>
										<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Hướng</th>
										<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">DT_TT</th>
										<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Loại hình</th>
										<!--  <th class="pcell align-center text-center">Ký XNĐK</th> -->
										<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">Thưởng sale</th>
										<th class="pcell align-center text-center" style="border: 1px solid color-mix(in srgb, {$_oI.bgcolor} 50%, transparent 50%)!important">CSBH ngày</th>
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
												<td class="text-center">{$clsISO->formatPriceV2($more_information.sale_bonus)}</td>
												<!-- <td class="text-center">{$more_information.date_deposit_sign}</td> -->
												<td class="text-center">{$_oStock.csbh}</td>
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
							<div id="{$clsISO->getUniqid()}" building_id="{$_oI.building_id}" block_id="{$_oKey}" 
							tp="{$tp}" img_layout="{$_oI.img_layout}" class="map mt-3" url="{$_oI.img_layout}">
								<!-- Map -->
							</div>
							{/if}
							{/if}
						{/if}
					</div>
					{break}
				{/if}
				{/foreach}
			</div>
			<!-- End Thấp tầng -->
			<div class="tgbZRaqgdF pt-3">
				<div class="qjkYlYqD9P">
					{$onePage.content}
					{if !empty($contacts)}
						<div class="text-decoration-underline fs-5 my-2"><span >☎️&nbsp;<strong>Liên hệ hỗ trợ</strong></span></div>
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
						<div class="fs-16"><strong><em>Thông tin dự án</em></strong>:&nbsp;<a href="https://myoceancity.vn/">https://myoceancity.vn</a></div>
					{/if}
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
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
		if(!$Core.util.isEmpty(location.hash)){
			var _hash = location.hash;
			clearTimeout(_timeOut);
			_timeOut = setTimeout(() => {
				$(`#project_tab a[href="${_hash}"]`)
					.attr('clicked', 'clicked')
					.tab('show');
			}, 500);
		}
		let hash = window.location.hash;
		if (hash.includes("?")) {
			let parts = hash.split("?"); 
			let newHash = parts[0];
			let params = new URLSearchParams(parts[1]);
			let tpValue = params.get("tp");
			if (tpValue) {
				let newUrl = window.location.pathname + "?tp=" + tpValue + newHash;
				if (window.location.search.indexOf("tp=") === -1) {
					history.replaceState(null, "", newUrl); // Giữ nguyên trang, không tải lại
				}
			}
		}
	});	
</script>
{/literal}