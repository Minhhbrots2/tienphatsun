{if $type eq 'user_new'}

<div class="table-wrapper overflow-auto d-flex flex-wrap">

	<table class="table table-bordered" width="100%">

		<thead><tr>

			{if $deviceType ne 'phone'}

				<th width="30px" class="align-center nosort bg-lighter">STT</th>

			{/if}

			<th class="align-center text-left bg-lighter">Họ và tên</th>

			<th class="align-center nosort text-center bg-lighter" style="width:80px">

				{if $deviceType ne 'phone'}Lượt xem{else}Lượt{/if}

			</th>

			{if $deviceType ne 'phone'}

				<th class="align-center nosort text-center bg-lighter" style="width:150px">Thời gian</th>

			{/if}

			<th class="align-center text-left bg-lighter" style="width:30px"></th>

		</tr></thead>

		{if !empty($lst_user)}

			{foreach name=i from=$lst_user name=i key=key item=item}

				<tr>

					{if $deviceType ne 'phone'}

						<td class="text-center">{$smarty.foreach.i.iteration}</td>

					{/if}

					<td class="text-left">

						<div style="width:max-content">

							<a href="{$clsISO->getLink('log-sale')}?user_id={$item.profile_id}" target="_blank">

								<i class="bx bx-link"></i>{$item.full_name}</a>

							<sup class="fs-tiny">({$item.role_name})</sup>

						</div>

					</td>			

					<td class="text-center">{$item.number_view_stock}</td>		

					{if $deviceType ne 'phone'}

						<td class="text-center">{$clsISO->convertTimeToText($item.reg_date, true)}</td>

					{/if}

					<td class="text-center"><a href="https://zalo.me/{$item.phone}" target="_blank">

						<img src="{$URL_IMAGES}/zalo_chat.png" width="20" height="20"></a>

					</td>

				</tr>

			{/foreach}

		{else}

			<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}5{else}3{/if}">Dữ liệu trống</td></tr>

		{/if}

	</table>

</div>

{elseif $type eq 'user_view_stock_sale' || $type eq 'user_view_stock_fh'}

<div class="table-wrapper overflow-auto d-flex flex-wrap">

	<table class="table table-bordered" width="100%">

		<thead><tr>

			{if $deviceType ne 'phone'}

				<th width="30px" class="align-center nosort bg-lighter">STT</th>

			{/if}

			<th class="align-center text-left bg-lighter">Họ và tên</th>

			<th class="align-center nosort text-center bg-lighter" style="width:80px">{if $deviceType ne 'phone'}Lượt xem{else}Lượt{/if}</th>

			{if $deviceType ne 'phone'}

				<th class="align-center nosort text-center bg-lighter" style="width:150px">Ngày tạo</th>

				<th class="align-center nosort text-center bg-lighter" style="width:150px">Truy cập gần nhất</th>

			{/if}

			<th class="align-center text-left bg-lighter" style="width:30px"></th>

		</tr></thead>

		{if !empty($lst_user)}

			{foreach name=i from=$lst_user name=i key=key item=item}

				<tr>

					{if $deviceType ne 'phone'}

						<td class="text-center">{$smarty.foreach.i.iteration}</td>

						

					{/if}

					<td class="text-left">

						<div style="width: max-content">

							<a href="{$clsISO->getLink('log-sale')}?user_id={$item.profile_id}" target="_blank">

							<i class="bx bx-link"></i>{$clsISO->truncate($item.full_name,15)}</a>

							{if $type eq 'user_view_stock_sale'}

								<sup class="fs-tiny">({$item.role_name})</sup>

							{/if}

						</div>

					</td>		

					<td class="text-center">{$item.number_view_stock}</td>		

					{if $deviceType ne 'phone'}

						<td class="text-center">{$clsISO->convertTimeToText($item.reg_date, true)}</td>

						<td class="text-center">{$clsISO->convertTimeToText($item.time_connect, true)}</td>

					{/if}

					<td class="text-center"><a href="https://zalo.me/{$item.phone}" target="_blank">

						<img src="{$URL_IMAGES}/zalo_chat.png" width="20" height="20"></a>

					</td>	

				</tr>

			{/foreach}

		{else}

			<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}7{else}3{/if}">Dữ liệu trống</td></tr>

		{/if}

	</table>

</div>

{elseif $type eq 'date_access_log'}

<div class="table-wrapper overflow-auto d-flex flex-wrap">

	<table class="table table-bordered" width="100%">

		<thead><tr>

			{if $deviceType ne 'phone'}

				<th width="30px" class="align-center nosort bg-lighter">STT</th>

			{/if}

			<th class="align-center text-left bg-lighter" style="width:200px">Họ và tên</th>

			<th class="align-center text-left bg-lighter">Link</th>

			<th class="align-center nosort text-center bg-lighter" style="width:80px">IP</th>

			{if $deviceType ne 'phone'}

				<th class="align-center nosort text-center bg-lighter" style="width:150px">Thời gian</th>

			{/if}

		</tr></thead>

		{if !empty($array_data)}

			{foreach name=i from=$array_data name=i key=key item=item}

				<tr>

					{if $deviceType ne 'phone'}

						<td class="text-center">{$smarty.foreach.i.iteration}</td>

					{/if}

					{if $item.is_popup eq 1}

						<td class="text-left"><div data-url="/index.php?mod=report&act=load_profile_popover&user_id={$item.profile_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400" class="awe__post-profile d-flex">{$item.full_name}</div></td>	

					{else}

						<td class="text-left">{$item.full_name}</td>	

					{/if}

					<td class="text-left"><a href="{$DOMAIN_URL}{$item.url|ltrim:"/"}" target="_blank">

						<i class="bx bx-link"></i>

						{if $deviceType ne 'phone'}{$DOMAIN_URL}{$item.url|ltrim:"/"}{else}{$item.url}{/if}</a>

					</td>									

					<td class="text-center">{$item.user_ip}</td>		

					{if $deviceType ne 'phone'}

						<td class="text-center">{$clsISO->convertTimeToText($item.reg_date, true)}</td>

					{/if}

				</tr>

			{/foreach}

		{else}

			<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}5{else}3{/if}">Dữ liệu trống</td></tr>

		{/if}

	</table>

</div>

{elseif $type eq 'list_stock_DQ'}

<div class="table-wrapper overflow-auto d-flex flex-wrap">

	<table class="table table-bordered" width="100%">

		<thead><tr>

			{if $deviceType ne 'phone'}

			<th width="30px" class="align-center nosort bg-lighter">STT</th>

			{/if}

			<th class="align-center text-left bg-lighter" style="width:100px">Mã căn</th>

			{if $deviceType eq phone}

			<th class="align-center text-left bg-lighter" style="width:200px">Địa chỉ</th>

			{else}

			<th class="align-center text-left bg-lighter">Dự án</th>

			<th class="align-center text-left bg-lighter">Phân khu</th>

			<th class="align-center text-left bg-lighter">Toà nhà</th>

			{/if}

			<th class="align-center text-left bg-lighter">Tra cứu {$smarty.const.BRAND_NAME}</th>

			<th class="align-center text-left bg-lighter">Tra cứu Myocean</th>

		</tr></thead>

		{if !empty($lstStockDQ)}

			{foreach name=i from=$lstStockDQ name=i key=key item=oStock}

			<tr>

				{if $deviceType ne 'phone'}

				<td class="text-center">{$smarty.foreach.i.iteration}</td>

				{/if}

				<td class="text-left">{$oStock.ms_code}</td>

				{if $deviceType eq phone}

				<td class="text-left">{$oStock.building_name}, {$oStock.block_name}, {$oStock.project_name}</td>									

				{else}

				<td class="text-left">{$oStock.project_name}</td>									

				<td class="text-left">{$oStock.block_name}</td>									

				<td class="text-left">{$oStock.building_name}</td>									

				{/if}

				<td class="text-left">{$oStock.logs_FH}</td>	

				<td class="text-left">{$oStock.logs_MYOCEAN}</td>	

			</tr>

			{/foreach}

		{else}

			<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}6{else}3{/if}">Dữ liệu trống</td></tr>

		{/if}

	</table>

</div>

{elseif $type eq 'agent'}

<div class="table-wrapper overflow-auto d-flex flex-wrap">

	<table class="table table-bordered table-sm" width="100%">

		<thead>

			<tr>

				{if $deviceType ne 'phone'}

				<th class="align-center text-center bg-lighter"  rowspan="3" width="50px">STT</th>{/if}

				<th class="align-center text-left bg-lighter" rowspan="2" width="100px">Đại lý</th>

				<th class="align-center text-center bg-lighter text-wrap" rowspan="2" width="50px">Độc quyền</th>

				{foreach from = $list_buildings item = _oB}

				<th class="align-center text-center bg-lighter" colspan="5">{$_oB.title}</th>

				{/foreach}

			</tr>

			<tr>

				{foreach from = $list_buildings item = _oB}

					{foreach from=$list_bedroom item=bedroom}

					<th class="align-center text-center text-white fs-10" style="background-color:{$bedroom.bgcolor}">

						{$bedroom.title}

					</th>

					{/foreach} 	

				{/foreach}

			</tr>

			<tr>

				<th class="text-left text-nowrap fw-bold bg-lighter">Tổng số</th>

				<th class="text-center text-nowrap fw-bold bg-lighter">{$total_stocks}</th>

				{foreach from = $list_buildings item = _oB}

				{assign var = _building_id value = $_oB.property_id}

					{foreach from=$list_bedroom item=bedroom}

					{assign var = _bedroom_id value = $bedroom.property_id}

					<th class="align-center text-center fw-bold bg-lighter">

						{$arr_all_total_stocks.$_building_id.$_bedroom_id}

					</th>

					{/foreach}

				{/foreach}

			</tr>

		</thead>

		{if !empty($list_agent)}

			{foreach name=i from=$list_agent name=i key=key item=item}

			{assign var = arr_total_stocks value = $item.arr_total_stocks}

				{if $item.property_id eq $smarty.const._AGENCY_FH_ID}

					{assign var=classColor value="text-white"}

				{else}

					{assign var=classColor value=""}

				{/if}

				<tr {$item.property_id}{$smarty.const._AGENCY_FH_ID} {if $item.property_id eq $smarty.const._AGENCY_FH_ID}class="bg-main"{/if}>

					<td class="text-center text-nowrap {$classColor}">{$smarty.foreach.i.iteration}</td>

					<td class="text-left text-nowrap {$classColor}">{$item.title}</td>

					<td class="text-center {$classColor}">{$item.total_DQ}</td>

					{foreach from = $list_buildings item = _oB}

						{assign var = _building_id value = $_oB.property_id}

						{foreach from = $list_bedroom item=bedroom}

							{assign var = _bedroom_id value = $bedroom.property_id}

							{assign var = number_stock value = 0}

							{if isset($arr_total_stocks.$_building_id.$_bedroom_id)}

								{assign var = number_stock value = $arr_total_stocks[$_building_id].$_bedroom_id}

							{/if}

							<td class="align-center text-center {$classColor}"{if $number_stock gte 5} style="color:#ff2600"{/if}>

								{if $number_stock gt '0'}{$number_stock}{/if}

							</td>

						{/foreach}

					{/foreach}

				</tr>

			{/foreach}

		{else}

			<tr><td class="text-center" colspan="3">Dữ liệu trống</td></tr>

		{/if}

		

	</table>

</div>

{elseif $type eq 'agent_log'}

<div class="table-wrapper overflow-auto d-flex flex-wrap">

	<table class="table table-bordered" width="100%">

		<thead><tr>

			{if $deviceType ne 'phone'}

				<th width="30px" class="align-center nosort bg-lighter">STT</th>

			{/if}

			<th class="align-center text-left bg-lighter" style="width:200px">Đại lý</th>

			{foreach from=$arr_time item=time}

				<th class="align-center text-left bg-lighter" width="15%">{$time}</th>

			{/foreach}

		</tr></thead>

		{if !empty($array_data)}

			{foreach name=i from=$array_data name=i key=key item=item}

				<tr>

					{if $deviceType ne 'phone'}

						<td class="text-center">{$smarty.foreach.i.iteration}</td>

					{/if}

					<td class="text-left">{$item.title}</td>

					{foreach from=$arr_time item=time}

					<td class="text-left">

						{$clsISO->makeSlashListFromArray($item.$time,", ",0)|nl2br} {if $item.$time|@count gt 0}({$item.$time|@count}){/if}

					</td>

					{/foreach}	

				</tr>

			{/foreach}

		{else}

			<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}6{else}3{/if}">Dữ liệu trống</td></tr>

		{/if}

	</table>

</div>

{elseif $type eq 'user_access_MOC'}

	<div id="chartMOC{$uid}" style="min-height:350px" class="chartContainer"></div>



{elseif $type eq 'service_MOC'}

	<div id="chartServiceMOC{$uid}" style="min-height:350px" class="chartContainer"></div>

{else}

<div class="table-wrapper overflow-auto d-flex flex-wrap">

	<table class="table table-bordered" width="100%">

		<thead><tr>

			{if $deviceType ne 'phone'}

			<th width="30px" class="align-center nosort bg-lighter">STT</th>

			{/if}

			{if $type eq 'access_url_logs'}

				<th class="align-center text-left bg-lighter">Link page</th>

			{else if $type eq 'access_user_logs'}

				<th class="align-center text-left bg-lighter">Họ và tên</th>

			{/if}

			<th class="align-center nosort text-center bg-lighter" style="width:100px">Lượt</th>

		</tr></thead>

		{if !empty($array_data)}

			{foreach name=i from=$array_data name=i key = _oK item = _oI}

				{if $smarty.foreach.i.index == 10}

					{break}

				{/if}

				{assign var = type_id value = $_oI.type_id}

				<tr>

					{if $deviceType ne 'phone'}

					<td class="text-center">{$smarty.foreach.i.iteration}</td>

					{/if}

					{if $type eq 'access_url_logs'}

						<td class="text-left"><a href="{$DOMAIN_URL}/{$_oI.url|ltrim:"/"}" target="_blank">

							<i class="bx bx-link"></i>{if $deviceType ne 'phone'}{$DOMAIN_URL}/{$_oI.url|ltrim:"/"}{else}{$_oI.url}{/if}</a>

						</td>

					{else if $type eq 'access_user_logs'}

						<td class="text-left">{$clsProfile->getFullName($_oI.profile_id)}</td>

					{/if}

					<td class="text-center">{$_oI.total}</td>

				</tr>

			{/foreach}

		{else}

			<tr><td class="text-center" colspan="{if $deviceType ne 'phone'}3{else}2{/if}">Dữ liệu trống</td></tr>

		{/if}

	</table>

</div>

{/if}

