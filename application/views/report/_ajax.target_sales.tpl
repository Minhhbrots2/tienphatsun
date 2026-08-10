{if !empty($lstDepartment)}
	{foreach name=i from=$lstDepartment item = _oItem}
		<div class="table-container no-shadow overflow-x-auto text-nowrap mb-2">		
			<table class="table table-iloocal table-computer table-bordered" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					{if empty($_oItem.is_not_area)}
						<tr><th class="align-center text-center bg-lighter fs-16 text-main fw-bold" colspan="{if $deviceType ne 'phone'}7{else}6{/if}">{$_oItem.title}</th></tr>
					{else}
						<tr><th class="align-center text-center bg-lighter fs-16 text-main fw-bold" colspan="{if $deviceType ne 'phone'}7{else}6{/if}">PKD {$_oItem.title}</th></tr>
					{/if}
					<tr>
						<th width="60" class="align-center text-center bg-lighter" rowspan="2">STT</th>
						<th class="align-center bg-lighter" width="30%" rowspan="2">Họ và tên</th>
						<th class="align-center text-center bg-lighter" width="150" rowspan="2">Mã nhóm</th>
						<th class="align-center text-center bg-lighter" colspan="2" width="150">Chỉ tiêu</th>
						<th class="align-center text-center bg-lighter" colspan="2" width="150">Đạt được</th>
					</tr>
					<tr>
						<th class="align-center text-center bg-lighter" width="75">Số GD</th>
						<th class="align-center text-center bg-lighter" width="75">Doanh số</th>
						<th class="align-center text-center bg-lighter" width="75">Số GD</th>
						<th class="align-center text-center bg-lighter" width="75">Doanh số</th>
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
							{if $deviceType eq 'phone'}<sup><small class="text-muted fs-12">{$_oStaff.department_name}</small></sup>{/if}
						</td>
						{if $deviceType ne 'phone'}
							<td class="text-center">{$_oStaff.department_name}</td>
						{/if}
						<td class="text-center">{if !empty($_oStaff.target_quantity)}{$_oStaff.target_quantity}{else}--{/if}</td>
						<td class="text-center">{if !empty($_oStaff.target_amount)}{$clsISO->shortNumber($_oStaff.target_amount,1,1)}{else}--{/if}</td>
						<td class="text-center">{if !empty($_oStaff.achieved_quantity)}{$_oStaff.achieved_quantity}{else}0{/if}</td>
						<td class="text-center">{if !empty($_oStaff.achieved_amount)}{$clsISO->shortNumber($_oStaff.achieved_amount,1,1)}{else}0{/if}</td>
					</tr>
					{/foreach}
					{if $lstDepartment|@count eq 1 && !empty($_oItem.is_not_area)}
						<tr>
							<td colspan="{if $deviceType eq 'phone'}2{else}3{/if}" class="text-center bg-lighter {if ($lstDepartment|@count eq 1 && $clsISO->checkPermissionGroup('SALE_DIRECTOR')) || !empty($_oItem.is_not_area)}fs-16 text-main{/if}">
								<strong class="text-upper">Mục tiêu phòng</strong>
							</td>
							<td class="text-center bg-lighter">{if !empty($_oItem.target_quantity)}{$_oItem.target_quantity}{else}--{/if}</td>
							<td class="text-center bg-lighter">{if !empty($_oItem.target_amount)}{$clsISO->shortNumber($_oItem.target_amount,1,1)}{else}--{/if}</td>
							<td class="text-center bg-lighter">{if !empty($_oItem.achieved_quantity)}{$_oItem.achieved_quantity}{else}0{/if}</td>
							<td class="text-center bg-lighter">{if !empty($_oItem.achieved_amount)}{$clsISO->shortNumber($_oItem.achieved_amount,1,1)}{else}0{/if}</td>
						</tr>
					{/if}					
				{/if}
				{if empty($_oItem.is_not_area) && !empty($_oItem.department_child)}
					{foreach from=$_oItem.department_child item=_oItemChild key=key name=i}
						<tr><th class="align-center text-center bg-lighter fs-14" colspan="{if $deviceType ne 'phone'}7{else}6{/if}">PKD {$_oItemChild.title}</th></tr>
						{assign var = total_staffs value = $_oItemChild.listStaff|@count}
						{foreach name=k from=$_oItemChild.listStaff item = _oStaffChild}
							{assign var = no value = $no + 1}
							<tr>
								<td width="30px" class="text-center">{$no}</td>
								<td class="text-left">
									<span{if $clsISO->checkHeadSale($_oStaffChild.role_id)} class="text-danger font-bold"{/if}>{$clsProfile->getFullName($_oStaffChild.profile_id, $_oStaffChild)}</span>
									{if $deviceType eq 'phone'}<sup><small class="text-muted fs-12">{$_oStaffChild.department_name}</small></sup>{/if}
								</td>
								{if $deviceType ne 'phone'}
									<td class="text-center">{$_oStaffChild.department_name}</td>
								{/if}
								<td class="text-center">{if !empty($_oStaffChild.target_quantity)}{$_oStaffChild.target_quantity}{else}--{/if}</td>
								<td class="text-center">{if !empty($_oStaffChild.target_amount)}{$clsISO->shortNumber($_oStaffChild.target_amount,1,1)}{else}--{/if}</td>
								<td class="text-center">{if !empty($_oStaffChild.achieved_quantity)}{$_oStaffChild.achieved_quantity}{else}0{/if}</td>
								<td class="text-center">{if !empty($_oStaffChild.achieved_amount)}{$clsISO->shortNumber($_oStaffChild.achieved_amount,1,1)}{else}0{/if}</td>
							</tr>
						{/foreach}
						<tr>
							<td colspan="{if $deviceType eq 'phone'}2{else}3{/if}" class="text-center bg-lighter {if ($lstDepartment|@count eq 1 && $clsISO->checkPermissionGroup('SALE_DIRECTOR')) || !empty($_oItemChild.is_not_area)}fs-16 text-main{/if}">
								<strong class="text-upper">Mục tiêu phòng</strong>
							</td>
							<td class="text-center bg-lighter">{if !empty($_oItemChild.target_quantity)}{$_oItemChild.target_quantity}{else}--{/if}</td>
							<td class="text-center bg-lighter">{if !empty($_oItemChild.target_amount)}{$clsISO->shortNumber($_oItemChild.target_amount,1,1)}{else}--{/if}</td>
							<td class="text-center bg-lighter">{if !empty($_oItemChild.achieved_quantity)}{$_oItemChild.achieved_quantity}{else}0{/if}</td>
							<td class="text-center bg-lighter">{if !empty($_oItemChild.achieved_amount)}{$clsISO->shortNumber($_oItemChild.achieved_amount,1,1)}{else}0{/if}</td>
						</tr>
					{/foreach}
				{/if}
				{if empty($_oItem.is_not_area)}
				<tr>
					<td colspan="{if $deviceType eq 'phone'}2{else}3{/if}" class="text-center bg-grayter fs-16 text-main">
						{if !empty($_oItem.is_business_area)}
						<strong class="text-upper">Mục tiêu vùng</strong>
						{else}
						<strong class="text-upper">Mục tiêu phòng</strong>
						{/if}
					</td>
					<td class="text-center bg-grayter">{if !empty($_oItem.target_quantity)}{$_oItem.target_quantity}{else}--{/if}</td>
					<td class="text-center bg-grayter">{if !empty($_oItem.target_amount)}{$clsISO->shortNumber($_oItem.target_amount,1,1)}{else}--{/if}</td>
					<td class="text-center bg-grayter">{if !empty($_oItem.achieved_quantity)}{$_oItem.achieved_quantity}{else}0{/if}</td>
					<td class="text-center bg-grayter">{if !empty($_oItem.achieved_amount)}{$clsISO->shortNumber($_oItem.achieved_amount,1,1)}{else}0{/if}</td>
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