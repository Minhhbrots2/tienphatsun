<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<form action="#" enctype="multipart/form-data" method="POST" onSubmit="return false;" >
		<div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
			<div class="d-flex flex-column gap-1">
				<h4 class="fw-bold mb-0 fs-5">Chiến dịch khách hàng</h4>
				<span class="text-muted fs-12">Tổng <strong class="total_record text-main">{if !empty($total_record)}{$total_record}{else}0{/if}</strong> dữ liệu khách hàng</span>
			</div>
			<div class="d-flex align-items-center flex-fill gap-1 justify-content-end">
				<input type="hidden" name="filter" value="filter" />
				{if $deviceType eq 'computer'}
					<div class="p-2 rounded-2 d-flex align-items-center gap-1">
						<div class="w-px-150">
							<select class="form-control search_field form-select" onChange="$Core.data_central.do_search(this,event)"
								name="campaign_id" data-width="100%" data-field="campaign_id" data-placeholder="Chiến dịch">
								{foreach name=i from=$lstCampaign item=_oCampaign}
								<option value="{$_oCampaign.campaign_id}">{$_oCampaign.title}</option>
								{/foreach}
							</select>
						</div>
						<div class="search-block w-full d-flex align-item-center">
							<div class="input-group">
								<div class="input-group input-group-merge w-px-150">
									<span class="input-group-text"><i class="bx bx-search"></i></span>
									<input type="text" name="keyword" data-field="keyword" value="{$keyword}" onChange="$Core.data_central.do_search(this, event)" 
										class="form-control search_field no-radius-right" placeholder="Nhập từ khoá">
								</div>
							</div>
							<div class="dropdown">
								<button type="button" class="btn btn-icon btn-default dropdown-toggle hide-arrow no-radius-left border-left-0" data-bs-toggle="dropdown" 
									data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>
								<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-400" data-popper-placement="top-end">
									<div class="p-3">
										<div class="form-group form-row mb-2">
											<div class="col-6 col-md-6">
												{assign var = uid value = $clsISO->getUniqid()}
												<div class="form-floating">
													<input type="date" class="form-control search_field" name="birthday" data-field="birthday" id="{$uid}" 
													placeholder="dd/mm/yy" max="{$smarty.now|date_format:'%Y-%m-%d'}">
													<label for="{$uid}">Ngày sinh</label>
												</div>
											</div>
											<div class="col-6 col-md-6">
												{assign var = uid value = $clsISO->getUniqid()}
												<div class="form-floating">
													<input type="date" class="form-control search_field" name="date_call" data-field="date_call" 
														id="{$uid}" placeholder="dd/mm/yy" max="{$smarty.now|date_format:'%Y-%m-%d'}">
													<label for="{$uid}">Ngày liên hệ</label>
												</div>
											</div>
										</div>
										<div class="form-group form-row mb-2">
											<div class="col-6 col-md-6">
												{assign var = uid value = $clsISO->getUniqid()}
												<div class="form-floating form-floating-multiselect">
													<select class="form-select form-control search_field search_data_status_field" name="status_id" data-width="100%" data-field="status_id">
														<option value="">Tình trạng</option>
														{$clsProperty->getSelectByProperty('DATA_CENTRAL_STATUS',$status_id, "", true)}
													</select>
													<label for="{$uid}">Tình trạng</label>
												</div>
											</div>
											<div class="col-6 col-md-6 box_search_tag">
												{assign var = uid value = $clsISO->getUniqid()}
												<div class="form-floating form-floating-multiselect">
													<select class="form-control search_field multiselect" name="tags" onChange="$Core.data_central.do_search(this, event)" data-placeholder="Tags" data-width="100%" data-header="true" data-filter="true" multiple data-field="tags[]">
													{if !empty($list_tags)}
														{foreach from=$list_tags item=_oTag}
														<option value="{$_oTag.tag_id}" {if $clsISO->checkInArray($_ss_tag_ids,$_oTag.tag_id)}selected{/if} >{$_oTag.title}</option>
														{/foreach}
													{/if}
													</select>
													<label for="{$uid}">Tags</label>
												</div>
											</div>
										</div>
										<hr class="my-3" />
										<div class="d-flex align-items-center gap-2">
											<button type="button" onClick="$Core.data_central.do_search(this, event)" class="btn btn-primary do_search">
												{$clsISO->makeIcon('bx-search', 'Áp dụng')}
											</button>
											<button type="button" class="btn btn-warning" onClick="$Core.data_central.resetForm(this,event)">
												{$clsISO->makeIcon('bx-refresh', 'Xóa')}
											</button>
										</div>
									</div>
								</div> 
							</div>
						</div>
					</div>				
				{else}
					<div class="input-group">
						<div class="w-px-150">
							<select class="form-control search_field form-select no-radius-right no-focus" onChange="$Core.data_central.do_search(this,event)"
								name="campaign_id" data-width="100%" data-field="campaign_id" data-placeholder="Chiến dịch">
								{foreach name=i from=$lstCampaign item=_oCampaign}
								<option value="{$_oCampaign.campaign_id}">{$_oCampaign.title}</option>
								{/foreach}
							</select>
						</div>
						<div class="dropdown">
							<button type="button" class="btn btn-icon btn-default dropdown-toggle hide-arrow no-radius-left" data-bs-toggle="dropdown" 
								data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true"><i class="bx bx-filter-alt"></i></button>
							<div class="dropdown-menu mega-dropdown-menu dropdown-menu-end w-px-350" data-popper-placement="top-end">
								<div class="p-3">
									<div class="form-group form-row mb-2">
										<div class="col-12 mb-2">
											{assign var = uid value = $clsISO->getUniqid()}
											<div class="form-floating">
												<input type="text" name="keyword" data-field="keyword" value="{$keyword}" class="form-control search_field" id="{$uid}" placeholder="Nhập từ khoá">
												<label for="{$uid}">Từ khóa</label>
											</div>
										</div>
										<div class="col-6 col-md-6 mb-2">
											{assign var = uid value = $clsISO->getUniqid()}
											<div class="form-floating">
												<input type="date" class="form-control search_field" name="birthday" data-field="birthday" id="{$uid}" 
												placeholder="dd/mm/yy" max="{$smarty.now|date_format:'%Y-%m-%d'}">
												<label for="{$uid}">Ngày sinh</label>
											</div>
										</div>
										<div class="col-6 col-md-6 mb-2">
											{assign var = uid value = $clsISO->getUniqid()}
											<div class="form-floating">
												<input type="date" class="form-control search_field" name="date_call" data-field="date_call" 
													id="{$uid}" placeholder="dd/mm/yy" max="{$smarty.now|date_format:'%Y-%m-%d'}">
												<label for="{$uid}">Ngày liên hệ</label>
											</div>
										</div>
										<div class="col-6 col-md-6 mb-2">
											{assign var = uid value = $clsISO->getUniqid()}
											<div class="form-floating form-floating-multiselect">
												<select class="form-select form-control search_field search_data_status_field" name="status_id" data-width="100%" data-field="status_id">
													<option value="">Tình trạng</option>
													{$clsProperty->getSelectByProperty('DATA_CENTRAL_STATUS',$status_id, "", true)}
												</select>
												<label for="{$uid}">Tình trạng</label>
											</div>
										</div>
										<div class="col-6 col-md-6 box_search_tag mb-2 flex-fill">
											{assign var = uid value = $clsISO->getUniqid()}
											<div class="form-floating form-floating-multiselect">
												<select class="form-control search_field multiselect" name="tags" onChange="$Core.data_central.do_search(this, event)" data-placeholder="Tags" data-width="100%" data-header="true" data-filter="true" multiple data-field="tags[]">
												{if !empty($list_tags)}
													{foreach from=$list_tags item=_oTag}
													<option value="{$_oTag.tag_id}" {if $clsISO->checkInArray($_ss_tag_ids,$_oTag.tag_id)}selected{/if} >{$_oTag.title}</option>
													{/foreach}
												{/if}
												</select>
												<label for="{$uid}">Tags</label>
											</div>

										</div>
									</div>
									<hr class="my-3" />
									<div class="d-flex align-items-center gap-2">
										<button type="button" onClick="$Core.data_central.do_search(this, event)" class="btn btn-primary do_search">
											{$clsISO->makeIcon('bx-search', 'Áp dụng')}
										</button>
										<button type="button" class="btn btn-warning" onClick="$Core.data_central.resetForm(this,event)">
											{$clsISO->makeIcon('bx-refresh', 'Xóa')}
										</button>
									</div>
								</div>
							</div> 
						</div>
					</div>
				{/if}
			</div>
		</div>	
	</form>
	<div class="card mb-2">
		<div class="card-body">
			<div class="briefs gap-2 gap-lg-3 d-flex flex-wrap">
				{foreach name=i from=$lstStatus item=_oItem}
				<div class="brief-item cursor-pointer a1a h-px-100" style="background-color:{$_oItem.bgcolor}" >
					<p class="text-fs-16 mb-0 d-inline-block pb-2 border-bottom mb-2">{$_oItem.title}</p>
					<h3 class="text-fs-24 xs:text-fs-16 mb-0 text-white">0</h3>
				</div>
				{/foreach}
			</div>
		</div>
	</div>
	<div class="form-row mb-2">
		<div class="col-12 {if $clsISO->checkPermissionGroup('DIRECTOR')}col-xxxl-8{else}col-xxxl-6 mb-xxl-0{/if} mb-2">
			<div class="card h-100">
				<div class="card-header">
					<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
						{foreach from=$list_boxs key= _oKey item = _oBox}
						<li class="nav-item flex-fill" role="presentation">
							<button onClick="$Core.data_central.set_view_desktop_followup(this, event)" 
								class="nav-link hnnyecGoRk{if $_oKey eq 'today'} active{/if}" tp="{$_oKey}" role="tab">
								<i class='bx {$_oBox.icon}'></i> {$_oBox.title}
							</button>
						</li>
						{/foreach}	
					</ul>
				</div>
				<div class="card-body">
					<div id="followups_desktop" class="overflow-y-auto h-px-150 holder_followups_desktop">
						<div class="p-5 text-muted text-center">
							<div class="d-flex flex-column justify-content-center align-items-center gap-2 p-4">
								<i class="fa fa-circle-o-notch fa-spin fa-3x"></i>
								<span>Loading...</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 {if $clsISO->checkPermissionGroup('DIRECTOR')}col-xxxl-4{else}col-xxxl-3 mb-xxl-0{/if} mb-2">
			<div class="card h-100">
				<div class="card-header">
					<h3 class="card-title mb-0">⚠️ Thông báo</h3>
				</div>
				<div id="box_warning" class="card-body">
					{foreach from=$list_alerts key = _bgColor item = _oAlert}
					<div class="d-flex align-items-center gap-2 alert-message bg-label-{$_bgColor}  p-2 rounded-2">
						<div class="w-px-40 p-2">
							<i class="fa fa-{$_oAlert} text-fs-20"></i>
						</div>
						<div style="width:calc(100% - 50px)" class="d-flex flex-column">
							<div class="mb-1 animate-bg w-100 h-px-10 rounded-2"></div>
							<div class="animate-bg w-100 h-px-10 rounded-2"></div>
						</div>
					</div>
					{/foreach}
				</div>	
			</div>
		</div>
		{if $clsISO->checkPermissionGroup('DIRECTOR')}
			<div class="col-12 col-md-8 mb-2 mb-xxl-0 flex-fill">
				<div class="card h-100">
					<div class="card-header">
						<div class="d-flex align-items-center justify-content-between">
							<h5 class="card-title mb-2 mb-lg-0 me-2">Báo cáo hiệu suất sale</h5>
						</div>
					</div>
					<div class="card-body">
						<div class="table-container no-shadow overflow-auto" style="max-height: 175px">
							<table class="table table-bordered text-nowrap dragable" width="100%" cellpadding="0" cellspacing="0">
								<thead class="sticky top-0 zindex-3" ><tr>
									{if $deviceType ne 'phone'}
									<th class="text-center bg-lighter h-px-40" width="40px">STT</th>
									{/if}
									<th class="align-center bg-lighter h-px-40">Sale chăm</th>
									<th class="align-center text-center bg-lighter h-px-40">Tổng khách</th>
									{foreach from=$lstStatus item=_oStatus key=k_stt name=n_stt}
										{if $_oStatus.property_id ne $smarty.const._DATA_STATUS_DONTCARE_ID}
											<th class="align-center text-center bg-lighter h-px-40">{$_oStatus.title}</th>
										{/if}
									{/foreach}
								</tr></thead>
								<tbody class="ajax lst_performance_campaign" gId="{$gId}" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=load_performance_campaign" data-options='{ldelim}{rdelim}'>
									{section name=i loop=$list_preloaders max = 10}
									<tr>
										{if $deviceType ne 'phone'}
											<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										{/if}
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
									{/section}
								</tbody>
							</table>
							<div class="d-flex justify-content-between text-center " id="showmorethisresult">
								<button type="button" class="showmorethisresult py-3" onClick="$Core.data_central.load_more(this, event)" page="1"> 
									<span>Xem thêm</span> 
									<img src="{$URL_IMAGES}/loading_48.gif" width="24px"> 
								</button> 
							</div>
						</div>
					</div>
				</div>
			</div>
		{/if}
		<div class="col-12 {if $clsISO->checkPermissionGroup('DIRECTOR')}col-xxxl-4{else}col-xxxl-3{/if} mb-2 mb-xxl-0">
			<div class="card h-100">
				<div class="card-header">
					<div class="d-flex align-items-center justify-content-between">
						<h5 class="card-title mb-2 mb-lg-0 me-2">Tỉ lệ chuyển đổi</h5>
						<button class="btn btn-icon btn-sm rounded-pill btn-link">
							<i class='bx bx-dots-horizontal-rounded text-muted'></i>
						</button>
					</div>
				</div>
				<div id="holder_converted_rates" class="card-body"></div>
			</div>
		</div>
	</div>
	<div class="card no-shadow">
		<div class="card-body" id="table_data_central">
			<div class="table-container no-shadow overflow-x-auto sticky_last">
				<table class="table table-bordered text-nowrap dragable" width="100%" cellpadding="0" cellspacing="0">
					<thead id="thead_data_central"><tr>
						{if $deviceType ne 'phone'}
						<th class="text-center bg-lighter h-px-40" width="40px">STT</th>
						{/if}
						{foreach from=$arr_columns item=_oItem}
							{if $_oItem.code ne 'staff_name' || $clsISO->checkPermissionGroup('DIRECTOR')}
								<th class="align-center{if $_oItem.code eq 'number_call'} text-center{/if} bg-lighter h-px-40">{$_oItem.title}</th>
							{/if}
						{/foreach}
						<!-- <th class="text-center bg-lighter h-px-40">Trạng thái</th> -->
						<th class="text-center border-left-0 h-px-40 bg-lighter" width="30px">
						</th>
					</tr></thead>
					<tbody class="tbody_data_central">
						{section name=i loop=$list_preloaders max = 30}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
							<td{if $deviceType ne 'phone'} class="border-left-0"{/if}><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
				<div class="d-flex justify-content-between text-center " id="showmorethisresult">
					<button type="button" class="showmorethisresult py-3" onClick="$Core.data_central.load_more(this, event)" page="1"> 
						<span>Xem thêm</span> 
						<img src="{$URL_IMAGES}/loading_48.gif" width="24px"> 
					</button> 
				</div>
			</div>
			{if !empty($html_pager)}
			<div class="pagination justify-content-center flex-wrap gap-1 mt-4">{$html_pager}</div>
			{/if}
		</div>
	</div>
</div>