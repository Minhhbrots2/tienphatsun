<!-- Style -->
<link rel="stylesheet" type="text/css" href="{$URL_JS}/easyui/themes/gray/easyui.css" media="all" />
<!-- Script -->
<script type="text/javascript" src="{$URL_JS}/easyui/jquery.easyui.min.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="card no-shaddow">
		<div class="card-header">
			<a href="{$PCMS_URL}/fund.html" class="back mr-2" title="{$core->get_Lang('Back')}">
				<img src="{$smarty.const.ICON_BACK}" />
			</a>
			<span class="text-upper">Báo cáo thu chi nội bộ</span>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-12 col-md-2">
					{if $deviceType eq 'phone'}
					<div class="dropdown w-100 mb-2">
						<button data-toggle="ripple" class="btn btn-block btn-outline-default dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" data-popper-placement="top-start" aria-haspopup="true" aria-expanded="false"><strong>Danh mục thu/chi</strong></button>
						<div class="dropdown-menu w-100 dropdown-mega-menu">
							<div class="p-2 h-px-400 overflow-y-auto">
								<div class="menu-vertical w-100 bg-menu-theme no-background shadow-none">
									<ul class="menu-inner report_menu">
										<li class="menu-item active">
											<a href="javascript:void(0)" class="menu-link m_link text-body" onClick="$Core.fund.load_report({}); return false;"><div class="text-truncate">Tổng quan</div></a>
										</li>
										{foreach from=$list_menus key= _oK item  = _oM}
										<li class="menu-header small text-uppercase">
											<span class="menu-header-text">{$_oM.title}</span>
										</li>
											{foreach name=i from=$_oM.list_items item = _oI}
											<li class="menu-item">
												<a href="javascript:void(0);" class="menu-link text-body ml-0" onClick="$Core.fund.load_content_report(this, '{$_oK}', {$_oI.property_id}, {}); return false;"><div class="text-truncate">{$_oI.title}</div></a>
											</li>
											{/foreach}
										{/foreach}
									</ul>
								</div>
							</div>
						</div>
					</div>
					<style type="text/css">
						.menu-inner > .menu-item{ width:100% !important;}
					</style>
					{else}
					<div class="menu-vertical tXLeeWFxHb bg-menu-theme shadow-none">
						<ul class="menu-inner report_menu">
							<li class="menu-item active">
								<a href="javascript:void(0)" class="menu-link m_link text-body" onClick="$Core.fund.load_report({}); return false;"><div class="text-truncate" data-i18n="Analytics">Tổng quan</div></a>
							</li>
							{foreach from=$list_menus key= _oK item  = _oM}
							<li class="menu-header small text-uppercase">
								<span class="menu-header-text">{$_oM.title}</span>
							</li>
								{foreach name=i from=$_oM.list_items item = _oI}
								<li class="menu-item">
									<a href="javascript:void(0);" class="menu-link text-body ml-0" onClick="$Core.fund.load_content_report(this, '{$_oK}', {$_oI.property_id}, {}); return false;">
										<div class="text-truncate">{$_oI.title}</div>
									</a>
								</li>
								{/foreach}
							{/foreach}
						</ul>
					</div>
					{/if}
				</div>
				<div class="col-12 col-md-10">
					<div class="h-100 w-100 p-3 rounded-3 bg-lighter">
						<div class="loadcontent w-100 h-100">
							<div class="d-flex align-items-center justify-content-center w-100 h-100">
								<span class="text-muted">Đang tải dữ liệu...</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	@media screen and (max-width:1600px){
		.tXLeeWFxHb{ width:11.85rem !important;}
		.tXLeeWFxHb .menu-item{ width:100% !important;}
	}
	.menu-vertical.bg-menu-theme{
		background-image: unset !important;
	}
	.report_menu{ margin-left : 0 !important;}
	
</style>
<script type="text/javascript">
	$(function(){
		$Core.fund.load_report({});
	});
</script>
{/literal}
