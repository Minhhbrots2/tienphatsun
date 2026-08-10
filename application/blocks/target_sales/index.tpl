<div class="mb-2">
	<div class="card h-100 mb-2 target_sale">
		{assign var=gId value=$clsISO->getUniqid()}
		<div class="card-header d-flex justify-content-between flex-wrap gap-1 align-items-center">
			<h5 class="card-title mb-0 title_box_target">Mục tiêu của tôi</h5>
			<div class="d-flex align-items-center gap-1 flex-fill justify-content-end ">
				<button type="button" class="btn btn-outline-default btn-icon btn-sm" title="Cài đặt" onClick="$Core.dashboard.addTargetSales(this,event)" action="_OPEN">
					<i class='bx bx-list-plus'></i>
				</button>
				{if ($clsISO->checkPermissionGroup('BUSINESS_AREA') || $clsISO->checkPermissionGroup('SALE_DIRECTOR'))}
					{if $deviceType eq 'phone'}
						<div class="btn-group">
							<button class="btn btn-icon hide-arrow btn-outline-default btn-sm dropdown-toggle" data-bs-toggle="dropdown">
								<i class='bx bx-filter-alt'></i>
							</button>
							<div class="dropdown-menu dropdown-menu-end w-px-150 p-2" data-popper-placement="bottom-end">
								<select class="form-control w-100 mb-2 form-select search_field" name="department_id" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
									<option value="">Cá nhân</option>
									<option value="{$oneDepartment.property_id}">{$oneDepartment.title}</option>
									{if $clsISO->checkPermissionGroup('BUSINESS_AREA') && !empty($oneDepartment.children)}
										{foreach from=$oneDepartment.children item=_oChild key=key name=i}
											<option value="{$_oChild.property_id}">PKD {$_oChild.title}</option>
										{/foreach}
									{/if}
								</select>
								<input type="month" class="form-control  search_field w-100" name="month" value="{$smarty.now|date_format:'%Y-%m'}" gId="{$gId}" onchange="$Core.dashboard.reload(this,event)">
							</div>
						</div>
					{else}
						<div class="input-group w-px-250">
							<select class="form-control w-px-100 form-control-sm form-select search_field" name="department_id" gId="{$gId}" onChange="$Core.dashboard.reload(this,event)">
								<option value="">Cá nhân</option>
								{if $oneDepartment.more_information.head_of_dep_id eq $profile_id}
								<option value="{$oneDepartment.property_id}">{$oneDepartment.title}</option>
								{/if}
								{if $clsISO->checkPermissionGroup('BUSINESS_AREA') && !empty($oneDepartment.children)}
									{foreach from=$oneDepartment.children item=_oChild key=key name=i}
										<option value="{$_oChild.property_id}">PKD {$_oChild.title}</option>
									{/foreach}
								{/if}
							</select>
							<input type="month" class="form-control form-control-sm search_field w-px-150" name="month" value="{$smarty.now|date_format:'%Y-%m'}" gId="{$gId}" onchange="$Core.dashboard.reload(this,event)">
						</div>
					{/if}
				{else}
					<div class="w-px-150">
						<input type="month" class="form-control form-control-sm search_field" name="month" value="{$smarty.now|date_format:'%Y-%m'}" gId="{$gId}" onchange="$Core.dashboard.reload(this,event)">
					</div>
				{/if}

			</div>							
		</div>
		<div class="card-body">
			<div class="box_target_sale ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_target_sales" gId="{$gId}" data-options="{ldelim}{rdelim}"></div>
		</div>
	</div>
</div>