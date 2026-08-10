<link rel="stylesheet" href="{$URL_CSS}/{$mod}.css?v={$upd_version}" type="text/css" media="all">
{if $message eq 'invalidlicense'}
<div class="errorbox"><strong><span class="title">{$core->get_Lang("Invalid License Module")}</span></strong><br></div>
{/if}
{if $message eq 'modulenotactive'}
<div class="errorbox"><strong><span class="title">{$core->get_Lang("Module is not activated!")}</span></strong><br></div>
{/if}
<div class="container-fluid">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">
					Xin chào {$clsUser->getFullName($_loged_id)}
				</h1>
			</div>
		</div>
	</div>
	<div class="wrap">
		<div class="form-row colorbox-group-widget">
			
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-primary">
						<a href="{$DOMAIN_URL}?mod=stock&stock_type=178" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$total_highlevel}
									<span class="pull-right">{$core->makeIcon('building-o')}</span></h3>
								<p class="info-text font-12">Cao tầng</p>
								<div class="d-flex justify-content-end gap-2">
									<p class="info-ot font-12">Đã bán<span class="label label-rounded font-12">{$total_highlevel_sold}</span></p>
									<p class="info-ot font-12">Độc quyền<span class="label label-rounded font-12">{$total_highlevel_dq}</span></p>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-danger">
						<a href="{$DOMAIN_URL}?mod=stock&stock_type=177" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$total_lowfloor} 
									<span class="pull-right">{$core->makeIcon('home')}</span></h3>
								<p class="info-text font-12">Thấp tầng</p>
								<div class="d-flex justify-content-end gap-2">
									<p class="info-ot font-12">Đã bán<span class="label label-rounded font-12">{$total_lowfloor_sold}</span></p>
									<p class="info-ot font-12">Độc quyền<span class="label label-rounded font-12">{$total_lowfloor_dq}</span></p>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-warning">
						<a href="{$DOMAIN_URL}?mod=sop" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$clsSop->countItem()} 
									<span class="pull-right">{$core->makeIcon('newspaper-o')}</span></h3>
								<p class="info-text font-12">Chuyển nhượng</p>
								<p class="info-ot font-12">{$core->get_Lang('Total Pending')}<span class="label label-rounded font-12">{$clsSop->countItem('is_online=0')}</span></p>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-success">
						<a href="{$DOMAIN_URL}?mod=leasing" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$clsLeasing->countItem()} 
									<span class="pull-right">{$core->makeIcon('newspaper-o')}</span></h3>
								<p class="info-text font-12">Cho thuê</p>
								<p class="info-ot font-12">{$core->get_Lang('Total Pending')}<span class="label label-rounded font-12">{$clsLeasing->countItem('is_online=0')}</span></p>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-primary">
						<a href="{$DOMAIN_URL}" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$clsInterior->countItem()} 
									<span class="pull-right">{$core->makeIcon('newspaper-o')}</span></h3>
								<p class="info-text font-12">Thiết kế</p>
								<p class="info-ot font-12">Yêu cầu phê duyệt<span class="label label-rounded font-12">{$clsInterior->countItem("is_online=0")}</span></p>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-md-2 info-color-box">
				<div class="white-box">
					<div class="media bg-success">
						<a href="{$DOMAIN_URL}?mod=service" class="d-block text-white text-decoration-hover-none">
							<div class="media-body">
								<h3 class="info-count">{$clsService->countItem()} 
									<span class="pull-right">{$core->makeIcon('newspaper-o')}</span></h3>
								<p class="info-text font-12">Dịch vụ tiện ích</p>
								<p class="info-ot font-12">Yêu cầu phê duyệt<span class="label label-rounded font-12">{$clsService->countItem('is_online=0')}</span></p>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row d-flex flex-wrap">
			<div class="col-md-6 mb-4">
				<div class="white-box h-1 user-table h-100 mb-0">
					<div class="row">
						<div class="col-sm-6">
							<h4 class="box-title">Tin chuyển nhượng</h4>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table">
							<thead><tr>
								<th width="60px">STT</th>
								<th>Mã căn</th>
								<th class="text-left" width="150px">Người tạo</th>
								<th class="text-right" width="150px">Ngày tạo</th>
								{*<th width="45px"></th>*}
							</tr></thead>
							<tbody id="lst_SOP">
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6 mb-4">
				<div class="white-box h-1 user-table h-100 mb-0">
					<div class="row">
						<div class="col-sm-6">
							<h4 class="box-title">Tin cho thuê</h4>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table">
							<thead><tr>
								<th width="60px">STT</th>
								<th>Mã căn</th>
								<th class="text-left" width="150px">Người tạo</th>
								<th class="text-right" width="150px">Ngày tạo</th>
								{*<th width="45px"></th>*}
							</tr></thead>
							<tbody id="lst_LEASING">
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6 mb-4">
				<div class="white-box h-1 user-table h-100 mb-0">
					<div class="row">
						<div class="col-sm-6">
							<h4 class="box-title">Dịch vụ tiện ích</h4>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table">
							<thead><tr>
								<th width="60px">STT</th>
								<th>Tên dịch vụ</th>
								<th class="text-left">Điện thoại</th>
								<th class="text-left" width="150px">Người tạo</th>
								{*<th width="45px"></th>*}
							</tr></thead>
							<tbody id="lst_SERVICES">
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-6 mb-4">
				<div class="white-box h-1 user-table h-100 mb-0">
					<div class="row">
						<div class="col-sm-6">
							<h4 class="box-title">Bản thiết kế</h4>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table">
							<thead><tr>
								<th width="60px">STT</th>
								<th>Tiêu đề</th>
								<th class="text-left">Công ty</th>
								<th class="text-left" width="150px">Người tạo</th>
								<th class="text-right" width="150px">Ngày tạo</th>
								{*<th width="45px"></th>*}
							</tr></thead>
							<tbody id="lst_INTERIOR">
								
							</tbody>
						</table> 
					</div>
				</div>
			</div>
			{*<div class="col-md-12">
				<div class="white-box h-1 user-table">
					<div class="row">
						<div class="col-sm-6">
							<h4 class="box-title">Yêu cầu thiết kế</h4>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table">
							<thead><tr>
								<th width="60px">STT</th>
								<th>Họ và tên</th>
								<th class="text-left" width="150px">Điện thoại</th>
								<th class="text-left">Chi tiết</th>
								<th class="text-left">Mô tả</th>
								<th class="text-left" width="150px">Người tạo</th>
								<th class="text-right" width="150px">Ngày tạo</th>
								<th width="45px"></th>
							</tr></thead>
							<tbody id="lst_INTERIOR_REQUEST">
								
							</tbody>
						</table> 
					</div>
				</div>
			</div>*}
		</div>
	</div>
</div>