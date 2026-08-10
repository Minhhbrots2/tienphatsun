{if $total_all_stocks gt '0' && !empty($lst_projects)}

	{if $clsISO->checkPermissionGroup('DIRECTOR') || $clsISO->checkPermissionGroup('BUSINESS_AREA') || $clsISO->checkPermissionGroup('PROJECT_DIRECTOR') || $profile_id eq 289}

		<div class="card mt-2">

			<div class="d-flex flex-wrap gap-2 ">

				<div class="table-freeze border-{$_oBlock.property_code} overflow-x-auto text-nowrap w-100">

					<table cellpadding="0" cellspacing="0" class="table mb-0 dragable table-stock table-stock_{$profile_id} table-stock-color table-stock-{$_oBlock.property_code}" width="100%">

						<thead class="bg-main text-white">

							<tr class="nohover" style="background: inherit !important">

								{if $deviceType ne 'phone'}

								<th class="pheader  align-center text-center">STT</th>

								{/if}

								<th class="pheader  align-center text-left">Dự án</th>

								<th class="pheader  align-center text-center">14 ngày qua</th>

								<th class="pheader  align-center text-center">7 ngày qua</th>

								<th class="pheader  align-center text-center">3 ngày qua</th>

								<th class="pheader  align-center text-center">Tổng căn</th>

								<th class="pheader  align-center text-center">Tổng VAT</th>

								<th class="pheader  align-center text-center">Tỷ lệ</th>

							</tr>

						</thead>

						{if !empty($arr_total_project)}

						<tbody>

							{foreach from=$arr_total_project item=_oItem key=key name=i}	

								<tr class="rowspan building_{$_oBuilding.property_id}">

									{if $deviceType ne 'phone'}

									<td class="text-center" width="40px">{$smarty.foreach.i.iteration}</td>

									{/if}

									<td  class="text-left text-break">{$_oItem.project_name}</td>

									<td  class="text-center text-break"><strong class="text-main fs-16">{$_oItem.total_sold}</strong> căn bán</td>

									<td  class="text-center text-break"><strong class="text-main fs-16">{$_oItem.total_sold_7}</strong> căn bán</td>

									<td  class="text-center text-break"><strong class="text-main fs-16">{$_oItem.total_sold_3}</strong> căn bán</td>

									<td  class="text-center text-break" ><strong class="text-main fs-16">{$_oItem.total_dq}</strong> căn</td>

									<td  class="text-center text-break" ><strong class="text-main fs-16">{$clsISO->priceFormatV3($_oItem.total_grand,1)}</strong> tỷ</td>

									<td  class="text-center text-break" ><strong class="text-main fs-16">{$clsISO->getPercent($_oItem.total_dq,$total_dq)}</strong>%</td>

								</tr>

							{/foreach}

							<tr class="rowspan building_{$_oBuilding.property_id}">

								<td class="pheader text-center text-upper fw-bold fs-18 h-px-40 bg-lighter" {if $deviceType ne 'phone'}colspan="2"{/if}>Tổng</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ><strong class="text-main fs-16">{$total_sold}</strong> căn bán</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ><strong class="text-main fs-16">{$total_sold_7}</strong> căn bán</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ><strong class="text-main fs-16">{$total_sold_3}</strong> căn bán</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ><strong class="text-main fs-16">{$total_dq}</strong> căn</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ><strong class="text-main fs-16">{$clsISO->priceFormatV3($total_grand,1)}</strong> tỷ</td>

								<td  class="pheader text-center text-break h-px-40 bg-lighter" ></td>

							</tr>

						</tbody>

						{/if}

					</table>

				</div>

			</div>

		</div>

	{/if}

	{foreach from=$lst_projects item = _oProject}

	{assign var = list_blocks value = $_oProject.list_blocks}

	<div class="w-100 my-2">

		{if !empty($list_blocks)}

			{foreach from=$list_blocks item = _oBlock}

			{assign var = block_id value = $_oBlock.property_id}

			{if $_oBlock.total_stocks gt '0'}

			<div class="table-freeze border-{$_oBlock.property_code} overflow-x-auto text-nowrap mb-2" style="border-color:{$_oBlock.bgcolor} ">

				<table cellpadding="0" cellspacing="0" class="table mb-0 dragable table-stock table-stock_{$profile_id} table-stock-color table-stock-{$_oBlock.property_code}" width="100%">

					{assign var = list_buildings value = $_oBlock.list_buildings}

					{assign var = list_price_field value = $_oBlock.list_price_field}

					<thead style="background: {$_oBlock.bgcolor};color: {$_oBlock.textcolor}"><tr>

						<th class="pheader text-left text-upper" colspan="20">

							<div class="d-flex align-items-center justify-content-between">

								<strong class="fs-6">Quỹ căn {$_oBlock.title}</strong>

								<span></span>

							</div>

						</th>

					</tr>

					<tr class="nohover" style="background: inherit !important">

						<th class="pcell align-center text-center" width="40" style="max-width: 100px !important">Tòa</th>

						<th class="pcell align-center text-left">Mã căn</th>

						<th class="pcell align-center text-center">Giá VAT</th>

						{if !empty($list_price_field)}

							{foreach from = $list_price_field item = _oField}

							<th class="pcell align-center text-center">{$_oField}</th>

							{/foreach}

						{/if}

						<th class="pcell align-center text-center">Vẽ View</th>

						<th class="pcell align-center text-left">Phiếu TG</th>

						<th class="pcell align-center text-center">Loại căn</th>

						<th class="pcell align-center text-center">Hướng</th>

						<th class="pcell align-center text-center">View</th>

						<th class="pcell align-center text-center">DT_TT</th>

						<th class="pcell align-center text-center">Loại hình</th>

						{if $block_id eq $smarty.const._PROJECT_BLOCK_PARKLAND_ID}

						<th class="pcell align-center text-center">CSBH ngày</th>

						<th class="pcell align-center text-center">Thưởng sale</th>

						<th class="pcell align-center text-center">Tình trạng</th>

						{else}

						<th class="pcell align-center text-center">Thưởng sale</th>

						<th class="pcell align-center text-center">CSBH ngày</th>

						{/if}

					</tr></thead>

					{if !empty($list_buildings)}

					<tbody style="color:{$_oBlock.bgcolor}">

						{foreach name=kk from=$list_buildings item = _oBuilding}

							{assign var = building_id value = $_oBuilding.property_id}

							{assign var = list_stocks value = $_oBuilding.list_stocks}

							{if !empty($list_stocks)}

								<tr class="rowspan building_{$_oBuilding.property_id}">

									<td width="40px" class="text-center text-break" rowspan="{$_oBuilding.total_stocks}" style="max-width: 100px !important">

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

								<tr class="building_{$_oBuilding.property_id}{if isset($more_information.markup_price) && $more_information.markup_price eq '1'} stock_mark{/if}{if $_oStock.is_sp_mech eq '1'} is_sp_mech{/if}"  style="background-color: {$_oStock.bg_stock_dq}">

									<td class="text-left">{if $_oStock.is_sp_mech eq '1'}<span title="Cơ chế đặc biệt">⭐</span>{/if}<a href="javascript:void(0);" class="cursor-pointer fw-bold text-link" {if $deviceType eq 'phone'}onClick="$Core.helper.open_stock({$stock_id});"{else}data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id={$stock_id}" data-toggle="webui-popover" data-placement="auto" data-trigger="click" data-width="350"{/if}>{$_oStock.ms_code}{if !empty($_oStock.is_fund_type)}<span class="text_fund_type" title="Thứ cấp"></span>{/if}</a></td>

									<td class="text-center{if $_oProject.project_id eq $smarty.const._PROJECT_VHOP2_ID} fw-bold text-red{/if}">{if !empty($more_information.total_price_vat)}{$clsISO->priceFormatV3($more_information.total_price_vat,3)}{else}--{/if}</td>

									{if !empty($list_price_field)}

										{foreach from = $list_price_field key = _oField item = _oText}

										<td class="{$_oField} price align-center text-center">

											{if !empty($more_information[$_oField])}

												{$clsISO->priceFormatV3($more_information[$_oField],3)}

											{else}

												--

											{/if}

										</td>

										{/foreach}

									{/if}

									<td class="text-center">{$_oStock.html_stock_posters}</td>

									<td class="text-nowrap">{$_oStock.html_image_sheets}</td>

									<td class="text-center">{$_oStock.bedroom_name}</td>

									<td class="text-center">{$_oStock.home_direction_name}</td>

									<td class="text-center">

										{if !empty($_oStock.view_id)}

											{$_oStock.view_name}

										{else}

											--

										{/if}

									</td>

									<td class="text-center">{$_oStock.DT_TT}</td>

									<td class="text-center">

										{if !empty($_oStock.is_fund_type)}

											<span class="text-success" title="Thứ cấp">Thứ cấp</span>

										{else}

											{$_oStock.type_name}

										{/if}

									</td>

									{if $block_id eq $smarty.const._PROJECT_BLOCK_PARKLAND_ID}

									<td class="text-center">{$_oStock.csbh}</td>

									<td class="text-center">

										{if !empty($more_information.sale_bonus)}

											{$more_information.sale_bonus}

										{else}

											--

										{/if}

									</td>

									<td class="text-center">

										{if !empty($more_information.first_payment_amount)}

											{$more_information.first_payment_amount}

										{else}

											--

										{/if}

									</td>

									{else}

									<td class="text-center">

										{if !empty($more_information.sale_bonus)}

											{$more_information.sale_bonus}

										{else}

											--

										{/if}

									</td>

									<td class="text-center">{$_oStock.csbh}</td>

									{/if}

								</tr>

								{/foreach}

							{/if}

						{/foreach}

						</tbody>

						<!-- End Building -->

					{/if}

				</table>

			</div>

			{/if}

		{/foreach}

		<!-- End Block -->

	{/if}	

	</div>

	{/foreach}

</div>

{else}

	<div class="empty bg-white rounded-2">

		<div class="p-5 text-center">

			<img class="w-px-100" src="{$URL_IMAGES}/listing-empty.svg">

			<p class="text-muted">Không có kết quả nào phù hợp</p>

		</div>

	</div> 

{/if}