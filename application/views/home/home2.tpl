<div class="content-wrapper">
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="eznyDbxTuI mt-10">
			<div class="main-nav {$deviceType}">
				<nav class="navbar navbar-example navbar-light navbar-expand-lg gap-1 justify-content-center menu_project {if $deviceType eq 'phone'}py-0{/if}">
					{if $deviceType eq 'phone'}
						<div class="btn-group w-100">
							<button class="w-100 border-0 dropdown-toggle fw-bold hide-arrow d-flex align-items-center justify-content-between" type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="true" style="background: #FFF0;color: #950c26;font-size: 16px;text-transform: uppercase">
								<span class="flex-fill text-center title_project px-4">Dự án</span>
								<svg class="position-absolute end-0" xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
								fill="currentColor" viewBox="0 0 24 24" >
								<path d="M5 3a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4M5 10a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4M5 17a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7.33 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4"></path>
								</svg>
							</button>
							<ul class="dropdown-menu nav nav-pill overflow-y-auto w-100" aria-labelledby="defaultDropdown" data-popper-placement="bottom-start" style="max-height: 80vh;overflow-y: auto;display: none" id="project_tab">
								{foreach from=$list_tabs name= i key = _oKey item  = _oI}
									<li><a href="#{$_oI.slug}" onClick="$Core.project.tab_click(this, event)" slug="{$_oI.slug}" class="nav-item dropdown-item {if $smarty.foreach.i.first} active{/if} is-effect {$_oI.slug} {if $_oI.tab_id eq 'DN'}MRD{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#{$_oI.slug}" color="{$_oI.bgcolor}" style="color: {$_oI.bgcolor}">{$_oI.title}</a></li>									
								{/foreach}
							</ul>
						</div>
					{else}
						<div id="project_tab" class="nav nav-pill navbar-nav gap-1 text-nowrap" role="tablist">
							{foreach from=$list_tabs name= i key = _oKey item  = _oI}
							<a href="#{$_oI.slug}" onClick="$Core.project.tab_click(this, event)" slug="{$_oI.slug}" class="nav-item rounded-1 nav-link flex-flow {if $smarty.foreach.i.first} active{/if} is-effect {$_oI.slug} {if $_oI.tab_id eq 'DN'}MRD{/if}" role="tab" data-bs-toggle="tab" data-bs-target="#{$_oI.slug}" style="background: {$_oI.bgcolor};color: {$_oI.textcolor}">{$_oI.title}</a>
							{/foreach}
						</div>
					{/if}
				</nav>
			</div>
			<div class="tab-content mt-2">
				{foreach from=$list_tabs name=i key = _oKey item = _oI}
				<div class="tab-pane fade{if $smarty.foreach.i.first} active show{/if}" id="{$_oI.slug}" role="tabpanel">
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
												<th class="pcell align-center text-center">Loại ký</th>
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
															<td class="text-center">
																{if !empty($more_information.contract_sign_type)}
																	{$more_information.contract_sign_type}
																{else}
																	--
																{/if}
															</td>
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
												<th class="pcell align-center text-center">Loại ký</th>
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
															<td class="text-center">
																{if !empty($more_information.contract_sign_type)}
																	{$more_information.contract_sign_type}
																{else}
																	--
																{/if}
															</td>
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
									<th class="pcell align-center text-center">Loại ký</th>
									{else}
									<th class="pcell align-center text-center">Thưởng sale</th>
									<th class="pcell align-center text-center">CSBH ngày</th>
									<th class="pcell align-center text-center">Loại ký</th>
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
										<tr class="text-nowrap{if $_oStock.is_sp_mech eq '1'} is_sp_mech{/if} building_{$_oBuilding.property_id}{if isset($more_information.markup_price) && $more_information.markup_price eq '1'} stock_mark nohover{/if}"  style="background-color: {$_oStock.bg_stock_dq}">
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
														{if $more_information.first_payment_amount|@is_numeric}
															{$clsISO->formatPriceV2($more_information.first_payment_amount)}
														{else}
															{$more_information.first_payment_amount}
														{/if}
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
											<td class="text-center">
												{if !empty($more_information.contract_sign_type)}
													{$more_information.contract_sign_type}
												{else}
													--
												{/if}
											</td>
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
			<div class="tgbZRaqgdF pt-4">
				<div class="qjkYlYqD9P">
				{$onePage.content}
				</div>
			</div>
		</div>
	</div>
</div>
<script> var block_name = '{$block_name}'; </script>
{literal}
<script type="text/javascript">
	$(function() {
		$Core.project.init_map();
		let scrollAmount = 200;
		let $tabList = $("#project_tab");
		{/literal}{if $deviceType eq 'phone'}{literal}
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
		{/literal}{else}{literal}
		let isScrolling = false;
		let direction = 0;
		// ===== CLICK =====
		$(".prev-btn").on("click", function(e) {
			e.preventDefault();
			$tabList.stop().animate({ scrollLeft: "-=" + scrollAmount }, 150);
		});
		$(".next-btn").on("click", function(e) {
			e.preventDefault();
			$tabList.stop().animate({ scrollLeft: "+=" + scrollAmount }, 150);
		});
		// ===== HOVER AUTO SCROLL (desktop tự có hover, mobile thì ignore) =====
		function autoScroll() {
			if (!isScrolling) return;
			let maxScroll = $tabList[0].scrollWidth - $tabList.outerWidth();
			let current = $tabList.scrollLeft();
			let next = current + direction * 3;
			if (next <= 0 || next >= maxScroll) return;
			$tabList.scrollLeft(next);
			requestAnimationFrame(autoScroll);
		}
		$(".prev-btn").on("mouseenter", function() {
			isScrolling = true;
			direction = -1;
			autoScroll();
		});
		$(".next-btn").on("mouseenter", function() {
			isScrolling = true;
			direction = 1;
			autoScroll();
		});
		$(".prev-btn, .next-btn").on("mouseleave", function() {
			isScrolling = false;
		});
		{/literal}{/if}{literal}
		if(!$Core.util.isEmpty(block_name)){
			clearTimeout(_timeOut);
			_timeOut = setTimeout(() => {
				var current_tab = $(`#project_tab a[href="#${block_name}"]`),
					title = current_tab.text();
				current_tab.attr('clicked', 'clicked').tab('show');
				document.title = `${title} | Quỹ căn độc quyền {/literal}{$smarty.const.BRAND_NAME}{literal}`;
				if($(".title_project").length > 0) {
					var color = current_tab.attr("color");
					$(".title_project").text(title);
				}
			}, 300);
		}
	});	
</script>
{/literal}