{if $_type eq "_agency"}
	{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
		<div class="table-container overflow-auto text-nowrap no-shadow table-container2 " style="max-height: 500px">
			<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
				<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
					<tr>
						<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
						<th class="align-center h-px-40 zindex-3 text-center" colspan="3">Cập nhật phân khu</th>
					</tr>
					<tr>
						<th class="align-center h-px-40 text-center" width="20%">Thành công</th>
						<th class="align-center h-px-40 text-center" width="20%">Lỗi</th>
						<th class="align-center h-px-40 text-center" width="20%">Không cập nhật</th>
					</tr>
				</thead>
				<tbody class="table-border-bottom-0">
					{if !empty($list_agency)}
						{assign var=index value=0}
						{foreach from=$list_agency item=_oItem name=i }
							{assign var = more_information value = $_oItem.more_information}
							{if !empty($more_information.spreadsheetId)}
								<tr class="tr_agency tr_agency_{$_oItem.property_id}" >
									<td class="text-nowrap" data-label="Tiêu đề" width="100px">{$_oItem.title}</td>
									{if $_oItem.total_upd gt 0}
									<td class="text-center text-success fw-bold fs-16">{$_oItem.total_upd}</td>
									{else}
									<td class="text-center text-muted">{$_oItem.total_upd}</td>
									{/if}
									{if $_oItem.total_not_upd gt 0}
									<td class="text-center text-danger fw-bold fs-16">{$_oItem.total_not_upd}</td>
									{else}
									<td class="text-center text-muted">{$_oItem.total_not_upd}</td>
									{/if}
									<td class="text-center text-muted fw-bold">{$_oItem.total_dont_upd}</td>
								</tr>
							{/if}
						{/foreach}
					{else}
						<tr>
							<td class="text-center" colspan="4">
								Danh sách trống!
							</td>
						</tr>
					{/if}
				</tbody>
			</table>
		</div>
	{else}
		<div class="table-container overflow-auto text-nowrap no-shadow table-container2 "  style="max-height: 500px">
			<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
				<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
					<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
					<tr>
						<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
						<th class="align-center h-px-40 zindex-3 text-center" colspan="3">Cập nhật dự án</th>
					</tr>
					<tr>
						<th class="align-center h-px-40 text-center" width="20%">Thành công</th>
						<th class="align-center h-px-40 text-center" width="20%">Lỗi</th>
						<th class="align-center h-px-40 text-center" width="20%">Không cập nhật</th>
					</tr>
				</thead>
				</thead>
				<tbody class="table-border-bottom-0">
					{if !empty($list_agency)}
						{assign var=index value=0}
						{foreach from=$list_agency item=_oItem name=i }
							<tr class="tr_agency tr_agency_{$_oItem.property_id}" >
								<td class="text-nowrap" data-label="Tiêu đề" width="100px">{$_oItem.title}</td>
								{if $_oItem.total_upd gt 0}
								<td class="text-center text-success fw-bold fs-16">{$_oItem.total_upd}</td>
								{else}
								<td class="text-center text-muted">{$_oItem.total_upd}</td>
								{/if}
								{if $_oItem.total_not_upd gt 0}
								<td class="text-center text-danger fw-bold fs-16">{$_oItem.total_not_upd}</td>
								{else}
								<td class="text-center text-muted">{$_oItem.total_not_upd}</td>
								{/if}
								<td class="text-center text-muted fw-bold">{$_oItem.total_dont_upd}</td>
							</tr>
						{/foreach}
					{else}
						<tr>
							<td class="text-center fs-12" colspan="{if $deviceType eq 'phone'}4{else}5{/if}">
								Danh sách trống!
							</td>
						</tr>
					{/if}
				</tbody>
			</table>
		</div>
	{/if}
{else}
	{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}
		<div class="table-container overflow-auto text-nowrap no-shadow table-container2 " style="max-height: 80vh">
			<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
				<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
					<tr>
						{if $deviceType ne 'phone'}
							<th class="align-center h-px-40 zindex-3 " width="3%" rowspan="2">No.</th>
						{/if}
						<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
						<th class="align-center h-px-40 text-center" width="20%" colspan="{$list_blocks|@count}">
							<div class="d-flex align-items-center justify-content-center gap-1">
								Phân khu
								<span class="fw-bold fs-20 text-warning">{$totalStock}</span>
							</div>
						</th>
					</tr>
					<tr>
						{foreach from=$list_blocks item = _block_name key=key name=i}
							<th class="align-center h-px-40 text-center no-sticky" width="20%" {if $smarty.foreach.i.last}style="border-right: 1px solid #d9dee3"{/if}>
								<div class="d-flex flex-column">									
									<div  class="d-flex align-items-center gap-1 justify-content-center">{$_block_name}<span class="fw-bold fs-20 text-warning">{$arr_total_block[$key]}</span></div>
									<div class="d-flex gap-1 text-none fw-normal fs-10">
										<div class="d-flex gap-1 align-items-center flex-fill text-success">
											<span class="">Cập nhật:</span><span class="text-dark">{$lstBlockTotalUpd[$key].agency_upd}</span>
										</div>
										<div class="d-flex gap-1 align-items-center flex-fill text-danger">
											<span class="">Chưa cập nhật:</span><span class="text-dark">{$lstBlockTotalUpd[$key].agency_not_upd}</span>
										</div>
									</div>
								</div>
							</th>
						{/foreach}
					</tr>
				</thead>
				<tbody class="table-border-bottom-0">
					{if !empty($list_agency)}
						{assign var=index value=0}
						{foreach from=$list_agency item=_oItem name=i }
							{assign var = list_block value = $_oItem.list_block}
							{assign var = more_information value = $_oItem.more_information}
							{if !empty($more_information.spreadsheetId)}
								{math equation="x+1" x=$index assign="index"}
								<tr class="tr_agency tr_agency_{$_oItem.property_id}" >
									{if $deviceType ne 'phone'}
									<td class="align-center text-center">{$index}</td>
									{/if}
									<td class="text-nowrap" data-label="Tiêu đề" width="100px">
										<div class="d-flex align-items-center justify-content-between gap-1">
											{$_oItem.title}
											<span class="fw-bold fs-20 text-warning">{$_oItem.total_stock}</span>
										</div>
									</td>
									{foreach from=$list_blocks item=_block_name key=key}
										{if !empty($list_block[$key].is_crawl)}
											<td class="text-center" {if empty($list_block[$key].is_success)}style="background:#ffe1e1"{else}style="background:#e6ffd8"{/if}>
												<div class="fs-11 d-flex flex-wrap gap-1 align-items-center lst_action_crawl">
													<div class="d-flex flex-wrap gap-1 fs-12" title="{$list_block[$key].title_log}">														
														<a class="fs-5" href="javascript:void(0);" onclick="$Core.crawl.open_import_logs(this, event)" agency_id="{$_oItem.property_id}" stock_type="{$stock_type}" target_id="{$key}" data-toggle="tooltip" title="" data-original-title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
														<div class="d-flex gap-1 align-items-center flex-fill">
															<span class="">Cập nhật:</span><span class="text-dark">{$list_block[$key].total_upd}</span>
														</div>
														<div class="d-flex gap-1 align-items-center text-success flex-fill">
															<span class="">Thành công:</span><span class="text-success">{$list_block[$key].total_success}</span>
														</div>
														<div class="d-flex gap-1 align-items-center text-danger flex-fill">
															<span class="">Thất bại:</span><span class="text-danger">{$list_block[$key].total_fail}</span>
														</div>	
														<div class="d-flex gap-1 align-items-center text-muted flex-fill">
															<span class="">Lần cuối:</span>{if !empty($list_block[$key].time)}{$list_block[$key].time}{else}--{/if}
														</div>													
													</div>
													<span class="fw-bold fs-20 text-warning">{$list_block[$key].total_stock}</span>
												</div>
											</td>
										{else}
											<td class="text-center text-muted fw-bold fs-12">Không cập nhật</td>
										{/if}							
									{/foreach}
								</tr>
							{/if}
						{/foreach}
					{else}
						<tr>
							<td class="text-center fs-12" colspan="{if $deviceType eq 'phone'}4{else}5{/if}">
								Danh sách trống!
							</td>
						</tr>
					{/if}
				</tbody>
			</table>
		</div>
	{else}
		<div class="table-container overflow-auto text-nowrap no-shadow table-container2 " style="max-height: 80vh">
			<table class="table table-bordered dragable installed" width="100%" cellpadding="0" cellspacing="0" >
				<thead class="position-sticky top-0 zindex-3 fs-12" style="background: #F5F7F8 !important">
					<tr>
						{if $deviceType ne 'phone'}
							<th class="align-center h-px-40 zindex-3" width="3%" rowspan="2">No.</th>
						{/if}
						<th class="align-center h-px-40 zindex-3" rowspan="2" width="15%">Đại lý</th>
						<th class="align-center h-px-40 text-center" width="20%" colspan="{$lst_project|@count}">
							<div class="d-flex align-items-center justify-content-center gap-1">
								Dự án
								<span class="fw-bold fs-20 text-warning">{$totalStock}</span>
							</div>
						</th>
					</tr>
					<tr>
						{foreach from=$lst_project item = _oProject key=key name=i}
							<th class="align-center h-px-40 text-center no-sticky" width="20%" {if $smarty.foreach.i.last}style="border-right: 1px solid #d9dee3"{/if}>
								<div class="d-flex flex-column">
									<div  class="d-flex align-items-center gap-1 justify-content-center">{$_oProject.project_code}<span class="fw-bold fs-20 text-warning">{$arr_total_project[$key]}</span></div>
									<div class="d-flex gap-1 text-none fw-normal fs-10">
										<div class="d-flex gap-1 align-items-center flex-fill text-success">
											<span class="">Cập nhật:</span><span class="text-dark">{$lstBlockTotalUpd[$key].agency_upd}</span>
										</div>
										<div class="d-flex gap-1 align-items-center flex-fill text-danger">
											<span class="">Chưa cập nhật:</span><span class="text-dark">{$lstBlockTotalUpd[$key].agency_not_upd}</span>
										</div>
									</div>
								</div>
							</th>
						{/foreach}
					</tr>
				</thead>
				<tbody class="table-border-bottom-0">
					{if !empty($list_agency)}
						{assign var=index value=0}
						{foreach from=$list_agency item=_oItem name=i }
							{assign var = list_block value = $_oItem.list_block}
							{assign var = more_information value = $_oItem.more_information}
							{math equation="x+1" x=$index assign="index"}
							<tr class="tr_agency tr_agency_{$_oItem.property_id}" >
								{if $deviceType ne 'phone'}
								<td class="align-center text-center">{$index}</td>
								{/if}
								<td class="text-nowrap" data-label="Tiêu đề" width="100px">
									<div class="d-flex align-items-center justify-content-between gap-1">
										{$_oItem.title}
										<span class="fw-bold fs-20 text-warning">{$_oItem.total_stock}</span>
									</div>
								</td>
								{foreach from=$lst_project item=_oProject key=key}
									{if !empty($list_block[$key].is_crawl)}
										<td class="text-center" {if empty($list_block[$key].is_success)}style="background:#ffe1e1"{else}style="background:#e6ffd8"{/if}>
											<div class="fs-11 d-flex flex-wrap gap-1 align-items-center lst_action_crawl">
												<div class="d-flex flex-wrap gap-1 fs-12" title="{$list_block[$key].title_log}">	
													<a class="fs-5" href="javascript:void(0);" onclick="$Core.crawl.open_import_logs(this, event)" agency_id="{$_oItem.property_id}" stock_type="{$stock_type}" target_id="{$key}" data-toggle="tooltip" title="" data-original-title="Lịch sử cập nhật"><i class="fa fa-history" aria-hidden="true"></i></a>
													<div class="d-flex gap-1 align-items-center flex-fill">
														<span class="">Cập nhật:</span><span class="text-dark">{$list_block[$key].total_upd}</span>
													</div>
													<div class="d-flex gap-1 align-items-center text-success flex-fill">
														<span class="">Thành công:</span><span class="text-success">{$list_block[$key].total_success}</span>
													</div>
													<div class="d-flex gap-1 align-items-center text-danger flex-fill">
														<span class="">Thất bại:</span><span class="text-danger">{$list_block[$key].total_fail}</span>
													</div>	
													<div class="d-flex gap-1 align-items-center text-muted flex-fill">
														<span class="">Lần cuối:</span>{if !empty($list_block[$key].time)}{$list_block[$key].time}{else}--{/if}
													</div>													
												</div>
												<span class="fw-bold fs-20 text-warning">{$list_block[$key].total_stock}</span>													
											</div>
										</td>
									{else}
										<td class="text-center text-muted fw-bold fs-12">Không cập nhật</td>
									{/if}
								{/foreach}
							</tr>
						{/foreach}
					{else}
						<tr>
							<td class="text-center fs-12" colspan="{if $deviceType eq 'phone'}4{else}5{/if}">
								Danh sách trống!
							</td>
						</tr>
					{/if}
				</tbody>
			</table>
		</div>
	{/if}
{/if}
