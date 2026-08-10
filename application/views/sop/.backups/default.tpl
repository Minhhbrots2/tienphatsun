<script type="text/javascript">
	var type_list = '{$type_list}', 
		current_page = '{$current_page}';
</script>
<link rel="stylesheet" href="{$URL_CSS}/swiper-bundle.min.css?v={$upd_version}" media="all" />
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex align-items-center flex-wrap justify-content-between py-2">
		<div class="sop_left xs:mb-2">
			{if $type_list eq 'me'}
			<h5 class="fw-bold mb-1">Chuyển nhượng - Thứ cấp đã đăng</h5>
			{elseif $type_list eq 'publish'}
			<h5 class="fw-bold mb-1">Chuyển nhượng{if $deviceType ne 'phone'} Vinhomes{/if} Ocean Park</h5>
			{else}
			<h5 class="fw-bold mb-1">Quản lý Mua bán/Chuyển nhượng</h5>
			{/if}
			<span class="srp-total-count text-muted">Hiện có <strong class="total-stock text-main">0</strong> tin chuyển nhượng.</span>
		</div>
		<div class="d-flex w-xs-100 gap-2">
			{if $clsISO->checkPermission('create_transfer') && $type_list ne 'manager'}
			<a href="{$PCMS_URL}/cn/me/" data-toggle="ripple" title="Quản lý tin đăng" 
		   class="d-flex flex-fill align-items-center btn btn-info">
				{$clsISO->makeIcon('bx-user-check', '&nbsp;Quản lý tin đăng')}
			</a>
			{/if}
			{if $loggedIn}
				<a href="{$PCMS_URL}/cn/add" data-toggle="ripple" title="Đăng tin" 
			   class="d-flex flex-fill align-items-center btn btn-primary">
					{$clsISO->makeIcon('bx-user-plus', '&nbsp;Đăng bán')}
				</a>
			{else}
				<a href="{$PCMS_URL}/dang-nhap/ret=/cn/add" data-toggle="ripple" title="Đăng tin" 
			   class="d-flex flex-fill align-items-center btn btn-primary">
					{$clsISO->makeIcon('bx-user-plus', '&nbsp;Đăng bán')}
				</a>
			{/if}
		</div>
	</div>
    <div class="card">
		<div class="card-header border-bottom om-xs:p-2">
			<form method="POST">
				{$core->getBlock('sop_search')}
				<input class="search_field" type="hidden" data-field="page" name="page" value="{$page}">
			</form>
		</div>
		<div class="card-body pt-3">
			{if $_ss_view eq 'grid' and $type_list eq 'publish'}
				{if $deviceType eq 'phone'}
					<!-- Start HOT -->
					{$core->getBlock('sop_hot')}
					<!-- End HOT -->
					<div class="holder_sop">
						{section name=i loop=$list_preloaders}
						<div class="awe__product-item overflow-hidden cursor-pointer d-flex">
							<div class="awe__product-thumb position-relative">
								<div class="animate-bg w-100 h-100 ui-bg-cover"></div>
							</div>
							<div class="awe__product-body">
								<div class="animate-bg w-100 h-px-20 rounded-2 mb-2"></div>
								<div class="table-item hidden-xs">
									<div class="d-flex py-1 text-nowrap gap-2">
										<div class="metadata-item">
											<div class="animate-bg w-px-50 h-px-15 rounded-2"></div>
										</div>
										<div class="metadata-item">
											<div class="animate-bg w-px-50 h-px-15 rounded-2"></div>
										</div>
										<div class="metadata-item">
											<div class="animate-bg w-px-50 h-px-15 rounded-2"></div>
										</div>
									</div>
									<div class="d-flex align-items-center text-nowrap">
										<div class="animate-bg w-px-50 h-px-15 rounded-2"></div>
									</div>
								</div>
							</div>
						</div>
						{/section}
					</div>
					<div id="pagination-container" class="pagination d-flex justify-content-center my-2"></div>
				{else}
				<div class="row">
					<div class="col-12 col-lg-9 col-xxl-9">
						{$core->getBlock('sop_filter')}
						<div class="holder_sop awe__sop-list row">
							{section name=i loop=$list_preloaders}
							<div class="col-12 col-lg-4 col-xxl-4 col-xxxl-3">
								<div class="awe__sop-item-preloader w-100 mb-3">
									<div class="logo-box mb-2">
										<div class="preloader h-px-175 rounded-3 animate-bg"></div>
									</div>
									<div class="col-content">
										<div class="animate-bg mb-2 rounded-1 h-px-20 w-60"></div>
										<div class="job-title mb-2">
											<div class="animate-bg rounded-1 h-px-20 mb-2"></div>
											<div class="animate-bg rounded-1 h-px-15"></div>
										</div>
										<div class="mb-2 text_ellipsis">
											<div class="animate-bg rounded-1 h-px-15 w-100"></div>
										</div>
										<div class="w-100 w-full d-flex align-items-center justify-content-between table-item">
											<div class="w-40">

												<div class="animate-bg rounded-1 h-px-15 w-100"></div>
											</div>
											<div class="w-40">
												<div class="animate-bg rounded-1 h-px-15 w-50"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							{/section}
						</div>
						<div id="pagination-container" class="pagination d-flex justify-content-center my-2"></div>
					</div>
					<div class="col-12 col-lg-3">
						{*{$core->getBlock('sidebar-search')}*}
						<div id="box_sidebar_search"></div>
						{$core->getBlock('sidebar-hotline')}
						{$core->getBlock('ads')}
						<div class="sticky">
							{$core->getBlock('sop_hot')}
						</div>
					</div>
				</div>
				{/if}
			{else}
				{$core->getBlock('sop_filter')}
				{if $_ss_view eq 'list' && $type_list eq 'publish' && $deviceType eq 'phone'}
					{$core->getBlock('sop_hot')}
				{/if}
				<table class="table table-iloocal table-{$deviceType} table-bordered">
					<thead><tr>
						{if $deviceType eq 'phone'}
						<th class="align-center bg-lighter"><a class="sort_click asc">Mã căn</a></th>
						<th class="align-center bg-lighter">Loại căn</th>
						<th class="align-center text-left bg-lighter">Giá</th>
						{else}
						<th width="10%" class="align-center bg-lighter">Mã căn</th>
						<th class="align-center bg-lighter"><a class="sort_click asc">Giá VAT</a></th>
						{if $type_list eq 'manager'}
						<th class="align-center bg-lighter">Loại căn</th>
						{else}
						<th class="align-center text-left bg-lighter">Thông thủy</th>
						<th class="align-center bg-lighter">Loại căn</th>
						{/if}
						<th class="align-center bg-lighter">Hướng BC</th>
						<th class="align-center bg-lighter">khoảng tầng</th> 
						<th class="align-center text-left bg-lighter">Người liên hệ</th>
						<th class="align-center text-left bg-lighter">Điện thoại</th>
						{/if}
						{if $type_list eq 'manager'}
						<th class="align-center text-center bg-lighter w-px-75">Xác minh</th>
						<th class="align-center text-center bg-lighter w-px-75">Duyệt</th>
						{/if}
						{if $type_list eq 'manager' || $type_list eq 'me'}						
						<th class="align-center text-left bg-lighter w-px-50">Thống kê</th>
						<th class="align-center text-left bg-lighter w-px-40"></th>
						{/if}
					</tr></thead>
					<tbody class="holder_sop">
						{section name=i loop=$list_preloaders}
						<tr>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							{if $deviceType ne 'phone'}
								{if $type_list eq 'manager'}
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								{else}
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
								{/if}
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							{/if}
							{if $type_list eq 'manager'}
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							{/if}
							{if $type_list eq 'manager' || $type_list eq 'me'}
							<td><div class="animate-bg w-100 rounded-2 h-px-20"></div></td>
							{/if}
						</tr>
						{/section}
					</tbody>
				</table>
				<div id="pagination-container" class="pagination d-flex justify-content-center mt-3"></div>
			{/if}
		</div>
	</div>
</div>
{if $_ss_add_stock eq '1'}
<div class="modal fade show" style="display: block" id="modalTop" tabindex="-1" aria-modal="true" role="dialog">
	<div class="modal-dialog modal-xs {if $deviceType eq 'phone'} {/if} modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="d-flex py-3 flex-column justify-content-center">
					<div class="awe__reviews-success text-center"> 
						<img class="mb-3 w-px-300" src="{$URL_IMAGES}/thankyou.svg">
						<h3 class="fs-5 mb-3">{$clsProfile->getFullName($profile_id, $oneProfile)} đăng tin thành công!</h3>
						<div class="button-wrap"> 
							<button type="button" class="btn close_pop btn-outline-default" data-bs-dismiss="modal">Đóng lại</a> 
						</div> 
					</div> 
				</div> 
			</div>
		</div>
	</div>
</div>
{/if}
<script>
	var _TYPE_LOWFLOOR = `{$smarty.const._TYPE_LOWFLOOR}`;
</script>
<script src="{$URL_JS}/jquery.sharer.js?v={$upd_version}"></script>
{$scriptJs}
