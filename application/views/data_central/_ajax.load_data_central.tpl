{if $_tp eq "_campaign"}
	{if !empty($lstDataCenter)}
		{foreach from=$lstDataCenter item=_oItem key=key name=i}
		<tr ondblclick="$Core.data_central.view_activity(this, event);" customer_id="{$_oItem.id}">
			{if $deviceType ne 'phone'}
			<td class="text-center">{$index}</td>
			{/if}	
			{foreach from=$arr_columns item=_oColumn}
				{if $_oColumn.code eq 'number_call'}
				<td class="text-center align-center">
					<button type="button" class="btn btn-icon btn-sm text-main btn-outline-default" 
						onClick="$Core.data_central.loadLog(this,event)" data-id="{$_oItem.id}">{$_oItem.total_call}
					</button>
				</td>
				{elseif $_oColumn.code eq 'call_last_time'}
				<td class="align-center">
					{if !empty($_oItem.call_last_time)}
					<span class="text-success">{$clsISO->formatDate($_oItem.call_last_time,4)}</span>
					{else}--{/if}
				</td>
				{elseif $_oColumn.code eq 'ms_codes'}
				<td class="text-left align-center" data-label="{$_oColumn.title}:">
					{if !empty($_oItem.ms_codes)}
					<div class="d-flex gap-1 align-items-center justify-content-between text-nowrap">
						{foreach from=$_oItem.ms_codes item=ms_code name=name_ms}
							{if $smarty.foreach.name_ms.index gt 0}, {/if}{$ms_code}
							{if $smarty.foreach.name_ms.index eq 1}{break}{/if}
						{/foreach}
						{if !empty($_oItem.more_ms_code)}
						<span class="btn px-1 btn-default btn-xs text-main" data-url="/index.php?mod=data_central&act=load_more&id={$_oItem.id}&field=ms_code" data-toggle="webui-popover" data-trigger="hover" data-width="200">+{$_oItem.more_ms_code}</span>{/if}
					</div>
					{else}
						--
					{/if}
				</td>
				{elseif $_oColumn.code eq 'staff_name'}
					{if $clsISO->checkPermissionGroup('DIRECTOR')}
						<td class="text-left align-center" data-label="{$_oColumn.title}:">
							{if !empty($arr_staff[$_oItem.id])}
								{foreach from=$arr_staff[$_oItem.id] item=item name=st}
									{$item}
								{/foreach}
							{else}
								--
							{/if}
						</td>
					{/if}
				{elseif $_oColumn.code eq 'tags'}
				<td class="text-left align-center" data-label="{$_oColumn.title}:">
					<div class="d-flex align-items-center justify-content-start gap-1">
						<div id="list_tag_{$_oItem.id}" class="d-flex align-items-center flex-wrap gap-1">
						{if !empty($_oItem.lstTag)}
							{foreach from=$_oItem.lstTag item=_oTag name=name_tag}
								<a href="{$link}?tags={$_oTag.tag_id}" class="btn btn-xs btn-default rounded-1 text-nowrap">{$_oTag.title}</a>
								{if $smarty.foreach.name_tag.index eq 3}{break}{/if}
							{/foreach}
							{if !empty($_oItem.more_tag)}
							<span class="btn px-1 btn-default btn-xs text-main" data-url="/index.php?mod=data_central&act=load_more&id={$_oItem.id}&field=tag" data-toggle="webui-popover" data-trigger="hover" data-width="200">+{$_oItem.more_tag}</span>
							{/if}
						{/if}
						</div>
						<a href="javascript:void(0);" class="label label-primary" data-toggle="webui-popover" data-trigger="click" data-type="async" data-width="300px" data-placement="auto" data-url="{$PCMS}/index.php?mod=data_central&act=load_pop_tag&id={$_oItem.id}" title="Tags" data-target="webuiPopover8">+ Thêm</a>
					</div>
				</td>
				{elseif $_oColumn.code eq 'phone'}
					{assign var=toId value=$clsISO->getUniqid()}
					<td class="text-center align-center" data-label="{$_oColumn.title}:">
						<div class="d-flex justify-content-between align-items-center gap-1">
							<div class="d-flex gap-1 align-items-center">
								<a href="javascript:void(0)" onClick="$Core.data_central.log(this,event)" data-type="call_log" 
									data-id="{$_oItem.id}" data-href="https://zalo.me/{$_oItem.phone}" class="rounded-pill">
									<span class="zalo_chat rounded-pill"><img src="{$URL_IMAGES}/logo_white_s_40.png" width="12" height="12"></span>
								</a>
								<div data-phone="{$_oItem.phone}" class="{$toId}">{$clsDataCentral->mask($_oItem.phone,1)}</div>
								<button type="button" data-toggle="ripple" class="btn btn-icon btn-sm btn-link text-muted rounded-pill" onClick="$Core.data_central.log(this,event)" data-id="{$_oItem.id}" data-type="view_phone" toId="{$toId}">
									<i class="material-icons-outlined no-translate text-fs-13">visibility</i>
								</button>
							</div>
							{if !empty($_oItem.phone2)}
							<span class="btn btn-icon btn-default btn-xs text-main load_more_{$toId}" data-url="/index.php?mod=data_central&act=load_more&id={$_oItem.id}&field=phone&uid={$toId}{if !empty($_oItem.view_phone)}&type=full{/if}" data-toggle="webui-popover" data-trigger="click" data-width="130">+{$_oItem.phone2|@count}</span>
							{/if}
						</div>
					</td>
				{elseif $_oColumn.code eq 'email'}
				<td class="text-center align-center" data-label="{$_oColumn.title}:">
					<div class="d-flex justify-content-between align-items-center gap-1">
						<a class="text-nowrap">{$_oItem.email}</a>
					{if !empty($_oItem.more_email)}
					<span class="btn btn-icon btn-default btn-xs text-main" data-url="/index.php?mod=data_central&act=load_more&id={$_oItem.id}&field=email" data-toggle="webui-popover" data-trigger="hover" data-width="250">+{$_oItem.more_email|@count}</span>{/if}
					</div>
				</td>
				{elseif $_oColumn.code eq 'address'}
				<td class="text-left align-center" data-label="{$_oColumn.title}:">
					<div class="limit_2line cursor-pointer">{$_oItem.address}</div>
				</td>
				{else}
				<td class="text-left align-center text-break" data-label="{$_oColumn.title}:">
					{if !empty($_oItem.{$_oColumn.code})}
					{$_oItem.{$_oColumn.code}}
					{else}
					--
					{/if}
				</td>	
				{/if}
			{/foreach}
			<td class="text-center bg-white position-sticky right-0">
				<div class="btn-group">
					{if empty($check_expired) || $clsISO->checkPermissionGroup('DIRECTOR')}
						<a title="Thêm lịch" class="btn btn-icon btn-sm cursor-pointer btn-outline-default" customer_id="{$_oItem.id}" campaign_id="{$campaign_id}" 
						onclick="$Core.data_central.open_activity(this, event)" tp="follow-ups" type_id="0"><i class='bx bx-plus'></i></a></a>
					{/if}
					<a href="javascript:void(0);" title="Follow-ups" class="btn btn-icon btn-sm btn-outline-default" 
						onClick="$Core.data_central.view_activity(this, event);" customer_id="{$_oItem.id}"><i class="bx bx-bell"></i></a>
				</div>
			</td>
		</tr>
		{math equation="x+1" x=$index assign="index"}
		{/foreach}
	{else}
		<tr>
			{if $deviceType ne 'phone'}
				<td class="text-center" colspan="{$arr_columns|@count + 2}">Không có khách hàng</td>
			{else}
				<td class="text-center" colspan="{$arr_columns|@count + 1}">Không có khách hàng</td>
			{/if}
		</tr>
	{/if}
{else}
	{if !empty($lstDataCenter)}
		{foreach from=$lstDataCenter item=_oItem key=key name=i}
		<tr>
			{if $deviceType ne 'phone'}
			<td class="text-center">{$index}</td>
			{/if}		
			<td class="text-center">
				<input type="checkbox" onchange="$Core.data_central.check_item(this, event)" tp="item" value="{$_oItem.id}" class="form-check-input chk_customer">
			</td>
			{foreach from=$arr_columns item=_oColumn}
				{if $_oColumn.code eq 'number_call'}
				<td class="text-center align-center">
					<button type="button" class="btn btn-icon btn-sm text-main btn-outline-default" 
						onClick="$Core.data_central.loadLog(this,event)" data-id="{$_oItem.id}">{$_oItem.total_call}
					</button>
				</td>
				{elseif $_oColumn.code eq 'call_last_time'}
				<td class="align-center">
					{if !empty($_oItem.call_last_time)}
					<span class="text-success">{$clsISO->formatDate($_oItem.call_last_time,4)}</span>
					{else}--{/if}
				</td>
				{elseif $_oColumn.code eq 'ms_codes'}
				<td class="text-left align-center" data-label="{$_oColumn.title}:">
					{if !empty($_oItem.ms_codes)}
					<div class="d-flex gap-1 align-items-center justify-content-between text-nowrap">
						{foreach from=$_oItem.ms_codes item=ms_code name=name_ms}
							{if $smarty.foreach.name_ms.index gt 0}, {/if}{$ms_code}
							{if $smarty.foreach.name_ms.index eq 1}{break}{/if}
						{/foreach}
						{if !empty($_oItem.more_ms_code)}
						<span class="btn px-1 btn-default btn-xs text-main" data-url="/index.php?mod=data_central&act=load_more&id={$_oItem.id}&field=ms_code" data-toggle="webui-popover" data-trigger="hover" data-width="200">+{$_oItem.more_ms_code}</span>{/if}
					</div>
					{else}
						--
					{/if}
				</td>
				{elseif $_oColumn.code eq 'staff_name'}
					{$arr_staff|@var_dump}
					<td class="text-left align-center" data-label="{$_oColumn.title}:">
						{if !empty($arr_staff[$_oItem.id])}
							{foreach from=$arr_staff[$_oItem.id] item=item name=st}
								{$item}
							{/foreach}
						{else}
							--
						{/if}
					</td>
				{elseif $_oColumn.code eq 'tags'}
				<td class="text-left align-center" data-label="{$_oColumn.title}:">
					<div class="d-flex align-items-center justify-content-start gap-1">
						<div id="list_tag_{$_oItem.id}" class="d-flex align-items-center flex-wrap gap-1">
						{if !empty($_oItem.lstTag)}
							{foreach from=$_oItem.lstTag item=_oTag name=name_tag}
								<a href="{$clsISO->getLink('data_central')}?tags={$_oTag.tag_id}" class="btn btn-xs btn-default rounded-1 text-nowrap">{$_oTag.title}</a>
								{if $smarty.foreach.name_tag.index eq 3}{break}{/if}
							{/foreach}
							{if !empty($_oItem.more_tag)}
							<span class="btn px-1 btn-default btn-xs text-main" data-url="/index.php?mod=data_central&act=load_more&id={$_oItem.id}&field=tag" data-toggle="webui-popover" data-trigger="hover" data-width="200">+{$_oItem.more_tag}</span>
							{/if}
						{/if}
						</div>
						<a href="javascript:void(0);" class="label label-primary" data-toggle="webui-popover" data-trigger="click" data-type="async" data-width="300px" data-placement="auto" data-url="{$PCMS}/index.php?mod=data_central&act=load_pop_tag&id={$_oItem.id}" title="Tags" data-target="webuiPopover8">+ Thêm</a>
					</div>
				</td>
				{elseif $_oColumn.code eq 'phone'}
					{assign var=toId value=$clsISO->getUniqid()}
					<td class="text-center align-center" data-label="{$_oColumn.title}:">
						<div class="d-flex justify-content-between align-items-center gap-1">
							<div class="d-flex gap-1 align-items-center">
								<a href="javascript:void(0)" onClick="$Core.data_central.log(this,event)" data-type="call_log" 
									data-id="{$_oItem.id}" data-href="https://zalo.me/{$_oItem.phone}" class="rounded-pill">
									<span class="zalo_chat rounded-pill"><img src="{$URL_IMAGES}/logo_white_s_40.png" width="12" height="12"></span>
								</a>
								<div data-phone="{$_oItem.phone}" class="{$toId}">{$clsDataCentral->mask($_oItem.phone,1)}</div>
								<button type="button" data-toggle="ripple" class="btn btn-icon btn-sm btn-link text-muted rounded-pill" onClick="$Core.data_central.log(this,event)" data-id="{$_oItem.id}" data-type="view_phone" toId="{$toId}">
									<i class="material-icons-outlined no-translate text-fs-13">visibility</i>
								</button>
							</div>
							{if !empty($_oItem.phone2)}
							<span class="btn btn-icon btn-default btn-xs text-main load_more_{$toId}" data-url="/index.php?mod=data_central&act=load_more&id={$_oItem.id}&field=phone&uid={$toId}{if !empty($_oItem.view_phone)}&type=full{/if}" data-toggle="webui-popover" data-trigger="click" data-width="130">+{$_oItem.phone2|@count}</span>
							{/if}
						</div>
					</td>
				{elseif $_oColumn.code eq 'email'}
				<td class="text-center align-center" data-label="{$_oColumn.title}:">
					<div class="d-flex justify-content-between align-items-center gap-1">
						<a class="text-nowrap">{$_oItem.email}</a>
					{if !empty($_oItem.more_email)}
					<span class="btn btn-icon btn-default btn-xs text-main" data-url="/index.php?mod=data_central&act=load_more&id={$_oItem.id}&field=email" data-toggle="webui-popover" data-trigger="hover" data-width="250">+{$_oItem.more_email|@count}</span>{/if}
					</div>
				</td>
				{elseif $_oColumn.code eq 'address'}
				<td class="text-left align-center" data-label="{$_oColumn.title}:">
					<div class="limit_2line cursor-pointer">{$_oItem.address}</div>
				</td>
				{else}
				<td class="text-left align-center text-break" data-label="{$_oColumn.title}:">
					{if !empty($_oItem.{$_oColumn.code})}
					{$_oItem.{$_oColumn.code}}
					{else}
					--
					{/if}
				</td>	
				{/if}
			{/foreach}
			<!-- <td class="text-left text-break data_status_{$_oItem.id}" data-label="{$_oColumn.title}">
				{if !empty($_oItem.call_success)}
					<span class="btn btn-sm btn-success w-100 text-nowrap" type="button">Đã gọi</span>
				{else}
					<button class="btn btn-sm btn-outline-primary text-nowrap" onClick="$Core.data_central.log_call_success(this,event)" data-id="{$_oItem.id}" data-type="_OPEN" type="button">Xác nhận gọi</button>
				{/if}
			</td> -->
			<td class="text-center{if $deviceType ne 'phone'} border-left-0{/if} align-center">
				<div class="dropdown">
					<button type="button" class="btn btn-xs p-0 dropdown-toggle dropdown-button hide-arrow">
						<i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu">										
						<a class="dropdown-item cursor-pointer" onClick="$Core.data_central.loadLog(this,event)" data-id="{$_oItem.id}">
							<i class='bx bx-bookmark-alt me-1'></i> Logs
						</a>
						{if empty($_oItem.has_CRM)}
						<a class="dropdown-item cursor-pointer" onclick="$Core.data_central.potentialCRM(this,event)" data-id="{$_oItem.id}">
							<i class='bx bx-subdirectory-right'></i>Khách hàng CRM
						</a>
						{/if}
					</div>
				</div>
			</td>
		</tr>
		{math equation="x+1" x=$index assign="index"}
		{/foreach}
	{/if}
{/if}