{if !empty($lstDepartment)}
	{foreach name=i from=$lstDepartment item = _oItem}
		<div class="table-container no-shadow overflow-x-auto text-nowrap mb-2">		
			<table class="table table-iloocal table-computer table-bordered" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					{if empty($_oItem.is_not_area)}
						<tr><th class="align-center text-center bg-lighter fs-16 text-main fw-bold" colspan="{if $deviceType ne 'phone'}6{else}4{/if}">{$_oItem.title}</th></tr>
					{else}
						<tr><th class="align-center text-center bg-lighter fs-16 text-main fw-bold" colspan="{if $deviceType ne 'phone'}6{else}4{/if}">PKD {$_oItem.title}</th></tr>
					{/if}
					<tr>
						<th width="30px" class="align-center text-center bg-lighter">STT</th>
						<th class="align-center bg-lighter" width="30%">Họ và tên</th>
						{if $deviceType ne 'phone'}
						<th width="15%" class="align-center text-center bg-lighter">Mã nhóm</th>
						<th class="align-center bg-lighter" width="20%">Vị trí</th>
						{/if}
						<th width="68px" class="align-center text-center bg-lighter">Số ảnh</th>
						<th class="align-center text-right bg-lighter" width="20%">Số tiền</th>
					</tr>
				</thead>
				{assign var = no value = 0}
				{if !empty($_oItem.listStaff)}
					{assign var = total_staffs value = $_oItem.listStaff|@count}
					{foreach name=k from=$_oItem.listStaff item = _oStaff}
					{assign var = no value = $no + 1}
					<tr>
						<td width="30px" class="text-center">{$no}</td>
						<td class="text-left">
							<span{if $clsISO->checkHeadSale($_oStaff.role_id)} class="text-danger font-bold"{/if}>{$clsProfile->getFullName($_oStaff.profile_id, $_oStaff)}</span>
							{if $deviceType eq 'phone'}
							<br /><span class="text-muted fs-12">{$_oStaff.title}</span>
							{/if}
						</td>
						{if $deviceType ne 'phone'}
						<td class=" text-center">{$_oItem.title}</td>
						<td class="text-left">{$_oStaff.role}</td>
						{/if}
						<td class="text-center">
						{if empty($_oStaff.total_share)}
							<span class="text-main fw-bold">{$_oStaff.total_share}</span>/<span class="text-info fw-bold">8</span>
						{elseif $_oStaff.total_share gte 8}
							<span class="text-success fw-bold">{$_oStaff.total_share}</span>/<span class="text-info fw-bold">8</span>
						{else}
							<span class="text-warning fw-bold">{$_oStaff.total_share}</span>/<span class="text-info fw-bold">8</span>
						{/if}
						</td>
						<td class="text-right">							
							{if $_oStaff.total_fines gt '0'}
								-{$clsISO->formatNumberToEasyRead($_oStaff.total_fines)}đ
							{else}
								0đ
							{/if}
						</td>
					</tr>
					{/foreach}
					{if $clsISO->checkPermissionGroup("BUSINESS_AREA") || ($lstDepartment|@count eq 1 && $clsISO->checkPermissionGroup("SALE_DIRECTOR")) || !empty($_oItem.is_not_area)}
						<tr>
							<td colspan="{if $deviceType eq 'phone'}2{else}4{/if}" class="text-center bg-lighter {if ($lstDepartment|@count eq 1 && $clsISO->checkPermissionGroup('SALE_DIRECTOR')) || !empty($_oItem.is_not_area)}fs-16 text-main{/if}">
								<strong class="text-upper">Tổng phòng</strong>
							</td>
							<td class="bg-lighter text-center {if ($lstDepartment|@count eq 1 && $clsISO->checkPermissionGroup('SALE_DIRECTOR')) || !empty($_oItem.is_not_area)}fs-16 text-main{/if}">
								<span class="fw-bold">{$_oItem.total_shares}</span>
							</td>
							<td class="bg-lighter text-right fw-bold {if ($lstDepartment|@count eq 1 && $clsISO->checkPermissionGroup('SALE_DIRECTOR')) || !empty($_oItem.is_not_area)}fs-16 text-main{/if}">
								{if $_oItem.total_price gt '0'}
									-{$clsISO->formatNumberToEasyRead($_oItem.total_price)}đ
								{else}
									0đ
								{/if}						
							</td>
						</tr>
					{/if}					
				{/if}
				{if !empty($_oItem.department_child)}
					{foreach from=$_oItem.department_child item=_oItemChild key=key name=i}
						{if $clsISO->checkPermissionGroup("BUSINESS_AREA") || $lstDepartment|@count eq 1}
							<tr><th class="align-center text-center bg-lighter fs-14" colspan="{if $deviceType ne 'phone'}6{else}4{/if}">PKD {$_oItemChild.title}</th></tr>
						{/if}
						{assign var = total_staffs value = $_oItemChild.listStaff|@count}
						{foreach name=k from=$_oItemChild.listStaff item = _oStaffChild}
							{assign var = no value = $no + 1}
							<tr>
								<td width="30px" class="text-center">{$no}</td>
								<td class="text-left">
									<span{if $clsISO->checkHeadSale($_oStaffChild.role_id)} class="text-danger font-bold"{/if}>{$clsProfile->getFullName($_oStaffChild.profile_id, $_oStaffChild)}</span>
									{if $deviceType eq 'phone'}
									<br /><span class="text-muted fs-12">{$_oStaffChild.title}</span>
									{/if}
								</td>
								{if $deviceType ne 'phone'}
								<td class="text-center">{$_oItem.title}-{$_oItemChild.title}</td>
								<td class="text-left">{$_oStaffChild.role}</td>
								{/if}
								<td class="text-center">
								{if empty($_oStaffChild.total_share)}
									<span class="text-main fw-bold">{$_oStaffChild.total_share}</span>/<span class="text-info fw-bold">8</span>
								{elseif $_oStaffChild.total_share gte 8}
									<span class="text-success fw-bold">{$_oStaffChild.total_share}</span>/<span class="text-info fw-bold">8</span>
								{else}
									<span class="text-warning fw-bold">{$_oStaffChild.total_share}</span>/<span class="text-info fw-bold">8</span>
								{/if}
								</td>
								<td class="text-right fw-bold">							
									{if $_oStaffChild.total_fines gt '0'}
										-{$clsISO->formatNumberToEasyRead($_oStaffChild.total_fines)}đ
									{else}
										0đ
									{/if}
								</td>
							</tr>
						{/foreach}
						{if $clsISO->checkPermissionGroup("BUSINESS_AREA") || $lstDepartment|@count eq 1}
							<tr>
								<td colspan="{if $deviceType eq 'phone'}2{else}4{/if}" class="text-center bg-lighter">
									<strong class="text-upper">Tổng phòng</strong>
								</td>
								<td class="bg-lighter text-center">
									<span class="fw-bold">{$_oItemChild.total_shares}</span>
								</td>
								<td class="bg-lighter text-right">
									{if $_oItemChild.total_price gt '0'}
										-{$clsISO->formatNumberToEasyRead($_oItemChild.total_price)}đ
									{else}
										0đ
									{/if}						
								</td>
							</tr>
						{/if}
					{/foreach}
				{/if}
				{if $lstDepartment|@count gt 1 || (!$clsISO->checkPermissionGroup('SALE_DIRECTOR') && empty($_oItem.is_not_area))}
				<tr>
					<td colspan="{if $deviceType eq 'phone'}2{else}4{/if}" class="text-center bg-grayter fs-16 text-main">
						<strong class="text-upper">Tổng cộng</strong>
					</td>
					<td class="bg-grayter text-center fs-16 text-main">
						<span class="fw-bold">{$_oItem.total_share_area}</span>
					</td>
					<td class="bg-grayter text-right fs-16 text-main fw-bold">
						{if $_oItem.total_price_area gt '0'}
							-{$clsISO->formatNumberToEasyRead($_oItem.total_price_area)}đ
						{else}
							0đ
						{/if}						
					</td>
				</tr>
				{/if}
				</table>
			</div>
		{/foreach}
	{/if}
{literal}
<style type="text/css">
	@media screen and (max-width:575px){
		.table th,
		.table td{ padding:0.525rem 0.325rem; }
	}
</style>
{/literal}