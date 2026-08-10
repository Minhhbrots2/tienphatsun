<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="w-100 d-flex flex-wrap algin-items-center justify-content-between py-2">
		<div class="lycYJcfXJY">
			<h4 class="fw-bold mb-1">Thống kê cập nhật bảng hàng</h4>
			<span class="text-muted">Tổng quan cập nhật bảng hàng</span>
		</div>
		{*<div class="cBSpMCSbVA">
			<div class="d-flex justify-content-end gap-2 algin-items-center">				
				<div class="p-right ox:w-100">					
					<div class="input-group input-date-picker mr-1 w-px-250">
						<i class="ico ico-calendar"></i>
						<input type="text" value="{$start_date}" class="form-control from_date w-px-100 search_field" 
						placeholder="Từ ngày" data-field="start_date">
						<input type="text" value="{$end_date}" class="form-control to_date w-px-100 search_field" 
						placeholder="Đến ngày" data-field="end_date">
					</div>
				</div>
			</div>
		</div>*}
	</div>
	{assign var=gId value=$clsISO->getUniqid()}
	<div class="form-row ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=ajax_get_total_report" gId="{$gId}" data-options='{ldelim}{rdelim}'>
		<div class="col-12 col-md-6">
			<div class="mb-2 card">
				<div class="card-header">
					<h3 class="card-title">Bảng hàng cao tầng</h3>
				</div>
				<div class="card-body">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="d-flex briefStockHug flex-wrap gap-3 mb-2 align-items-center">
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Tổng quỹ</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Nhập mới</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Đã bán</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="mb-2 card">
				<div class="card-header">
					<h3 class="card-title">Bảng hàng thấp tầng</h3>
				</div>
				<div class="card-body">
					{assign var = gId value = $clsISO->getUniqid()}
					<div class="d-flex briefStockHug flex-wrap gap-3 mb-2 align-items-center">
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Tổng quỹ</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Nhập mới</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
						<div class="border cursor-pointer flex-fill p-3 rounded-2">
							<div class="d-flex mb-2 align-items-center justify-content-between">
								<h5 class="mb-0">Đã bán</h5>
								<a class="panel-help help_pop openHelp" title="Tổng số cọc đã cọc vào CĐT">
									<i class="fa fa-question-circle"></i>
								</a>
							</div>
							<ul class="list-unstyled mb-0">
								<li class="d-flex align-items-center justify-content-between">
									<span class="text-muted">Số lượng:</span>
									<strong class="fs-5 text-main">0</strong>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12 col-md-6">
			<div class="mb-2 card card_load_time">
				{assign var=gId value=$clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Tình trạng cập nhật cao tầng đại lý</h5>
					<div class="w-px-150">
						<input type="date" class="form-control" name="date" value={$smarty.now|date_format:"%Y-%m-%d"} max="{$smarty.now|date_format:'%Y-%m-%d'}" onChange="$Core.crawl.reload(this,event)" gId="{$gId}" data-options='{ldelim}"_type":"_agency"{rdelim}'>
					</div>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=ajax_load_status_log_stock&stock_type={$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}" gId="{$gId}" data-options='{ldelim}"_type":"_agency"{rdelim}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="height: 500px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
								<tr>
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 zindex-3 text-center" colspan="3">Phân khu</th>
								</tr>
								<tr>
									<th class="align-center h-px-40 text-center" width="20%">Cập nhật</th>
									<th class="align-center h-px-40 text-center" width="20%">Chưa cập nhật</th>
									<th class="align-center h-px-40 text-center" width="20%">Không cập nhật</th>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								{section name=i loop=$list_preloaders max=16}
									<tr class="tr_agency tr_agency_{$_oItem.property_id}" >									
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="mb-2 card card_load_time">
				{assign var=gId value=$clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Tình trạng cập nhật thấp tầng đại lý</h5>
					<div class="w-px-150">
						<input type="date" class="form-control" name="date" value={$smarty.now|date_format:"%Y-%m-%d"} max="{$smarty.now|date_format:'%Y-%m-%d'}" onChange="$Core.crawl.reload(this,event)" gId="{$gId}" data-options='{ldelim}"_type":"_agency"{rdelim}'>
					</div>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=ajax_load_status_log_stock&stock_type={$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}" gId="{$gId}" data-options='{ldelim}"_type":"_agency"{rdelim}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="height: 500px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
								<tr>
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 zindex-3 text-center" colspan="3">Phân khu</th>
								</tr>
								<tr>
									<th class="align-center h-px-40 text-center" width="20%">Cập nhật</th>
									<th class="align-center h-px-40 text-center" width="20%">Chưa cập nhật</th>
									<th class="align-center h-px-40 text-center" width="20%">Không cập nhật</th>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								{section name=i loop=$list_preloaders max=16}
									<tr class="tr_agency tr_agency_{$_oItem.property_id}" >									
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="form-row">
		<div class="col-12 col-md-6">
			<div class="mb-2 card card_load_time">
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Tình trạng link cập nhật tự động cao tầng</h5>
					<div class="w-px-200">
						{assign var=gId value=$clsISO->getUniqid()}
						<select gid="{$gId}" class="form-control form-control-sm form-select" data-placeholder="Chọn phân khu" name="block_id" onchange="$Core.crawl.reload(this, event)">
							<option value="0">Chọn phân khu</option>
							{foreach from=$list_group_block item=_oGroupBlock}
								{assign var=listBlocks value=$_oGroupBlock.listBlocks}
								<optgroup label="{$_oGroupBlock.title}">
									{foreach from=$listBlocks item=_oBlock}
										<option value="{$_oBlock.property_id}">[{$_oBlock.property_code}] {$_oBlock.title}</option>
									{/foreach}
								</optgroup>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=ajax_load_agency_link&stock_type={$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}" gId="{$gId}" data-options='{ldelim}"_type":"_agency"{rdelim}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="height: 300px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
								<tr>
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 zindex-3 text-center" colspan="2">Phân khu</th>
								</tr>
								<tr>
									<th class="align-center h-px-40 text-center" width="20%">Có link</th>
									<th class="align-center h-px-40 text-center" width="20%">Chưa có link</th>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								{section name=i loop=$list_preloaders max=16}
									<tr class="tr_agency tr_agency_{$_oItem.property_id}" >									
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="mb-2 card card_load_time">
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Tình trạng link cập nhật tự động thấp tầng</h5>
					<div class="w-px-150">
						{assign var=gId value=$clsISO->getUniqid()}
						<select gid="{$gId}" class="form-control form-control-sm form-select" data-placeholder="Chọn dự án" name="project_id" onchange="$Core.crawl.reload(this, event)">
								<option value="0">Chọn dự án</option>
							{foreach from=$lstProjects item=_oProject}
								<option value="{$_oProject.project_id}">{$_oProject.title}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=ajax_load_agency_link&stock_type={$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}" gId="{$gId}" data-options='{ldelim}"_type":"_agency"{rdelim}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="height: 300px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
								<tr>
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 zindex-3 text-center" colspan="2">Phân khu</th>
								</tr>
								<tr>
									<th class="align-center h-px-40 text-center" width="20%">Có link</th>
									<th class="align-center h-px-40 text-center" width="20%">Chưa có link</th>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								{section name=i loop=$list_preloaders max=16}
									<tr class="tr_agency tr_agency_{$_oItem.property_id}" >									
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
										<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="nav-align-top nav-tabs-shadow">
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item" role="presentation">
				<button type="button" class="nav-link tab_report  active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-highfloor" aria-controls="navs-highfloor" aria-selected="true">Cao tầng</button>
			</li>
			<li class="nav-item" role="presentation">
				<button type="button" class="nav-link tab_report " role="tab" data-bs-toggle="tab" data-bs-target="#navs-lowfloor" aria-controls="navs-lowfloor" aria-selected="false" tabindex="-1">Thấp tầng</button>
			</li>
		</ul>
		<div class="tab-content p-0">
			<div class="tab-pane card_load_time fade active show" id="navs-highfloor" role="tabpanel">
				{assign var=gId value=$clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Bảng theo dõi tình trạng cao tầng</h5>
					<div class="w-px-150">
						<input type="date" class="form-control" name="date" value={$smarty.now|date_format:"%Y-%m-%d"} max="{$smarty.now|date_format:'%Y-%m-%d'}" onChange="$Core.crawl.reload(this,event)" gId="{$gId}" data-options='{ldelim}"_type":"_project"{rdelim}'>
					</div>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=ajax_load_status_log_stock&stock_type={$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}" gId="{$gId}" data-options='{ldelim}"_type":"_project"{rdelim}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="height: 500px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-5" style="background: #F5F7F8 !important">
								<tr>
									{if $deviceType ne 'phone'}
										<th class="align-center h-px-40 zindex-3" width="3%" rowspan="2">No.</th>
									{/if}
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 text-center" width="20%" colspan="4">Phân khu</th>
								</tr>
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								{section name=i loop=$list_preloaders max=16}
									<tr class="tr_agency tr_agency_{$_oItem.property_id}" >
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
					</div>
				</div>
			</div>
			<div class="tab-pane fade card_load_time" id="navs-lowfloor" role="tabpanel">
				{assign var=gId value=$clsISO->getUniqid()}
				<div class="card-header d-flex flex-wrap justify-content-between">
					<h5 class="card-title mb-2 mb-lg-0 me-2">Bảng theo dõi tình trạng thấp tầng</h5>
					<div class="w-px-150">
						<input type="date" class="form-control" name="date" value={$smarty.now|date_format:"%Y-%m-%d"} max="{$smarty.now|date_format:'%Y-%m-%d'}" onChange="$Core.crawl.reload(this,event)" gId="{$gId}" data-options='{ldelim}"_type":"_project"{rdelim}'>
					</div>
				</div>
				<div class="card-body ajax" data-url="{$PCMS_URL}/index.php?mod={$mod}&act=ajax_load_status_log_stock&stock_type={$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}" gId="{$gId}" data-options='{ldelim}"_type":"_project"{rdelim}'>
					<div class="table-container overflow-auto text-nowrap no-shadow table-container2"  style="height: 500px">
						<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
							<thead class="position-sticky top-0 zindex-5" style="background: #F5F7F8 !important">
								<tr>
									{if $deviceType ne 'phone'}
										<th class="align-center h-px-40 zindex-3" width="3%" rowspan="2">No.</th>
									{/if}
									<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
									<th class="align-center h-px-40 text-center" width="20%" colspan="4">Dự án</th>
								</tr>
								<tr>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-pill"></div></td>
								</tr>
							</thead>
							<tbody class="table-border-bottom-0">
								{section name=i loop=$list_preloaders max=16}
									<tr class="tr_agency tr_agency_{$_oItem.property_id}" >
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
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>
