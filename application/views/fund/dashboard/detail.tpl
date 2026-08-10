<!-- Style -->
<link rel="stylesheet" type="text/css" href="{$URL_JS}/easyui/themes/gray/easyui.css" media="all" />
<!-- Script -->
<script type="text/javascript" src="{$URL_JS}/easyui/jquery.easyui.min.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="card no-shaddow">
		<div class="card-header">
			<a href="{$PCMS_URL}/chi-phi-van-hanh.html" class="back mr-2" title="{$core->get_Lang('Back')}">
				<img src="{$smarty.const.ICON_BACK}" />
			</a>
			<span class="text-upper">Chi phí vận hành</span>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-12 col-md-2">
					{if $deviceType eq 'phone'}
					<div class="dropdown w-100 mb-2">
						<button data-toggle="ripple" class="btn btn-block btn-outline-default dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" data-popper-placement="top-start" aria-haspopup="true" aria-expanded="false"><strong>Danh mục chi phí</strong></button>
						<div class="dropdown-menu w-100 dropdown-mega-menu">
							<div class="p-2 h-px-400 overflow-y-auto">
								<div class="menu-vertical w-100 bg-menu-theme no-background shadow-none">
									<ul class="menu-inner report_menu">
										{foreach from=$list_menus key= _oK item  = _oItem}
											<li class="menu-item {if $_oK eq $_type}active{/if}">
												<a href="javascript:void(0);" class="menu-link text-body ml-0" onClick="$Core.fund.dashboard.load_content_detail(this, '{$_oK}', {}); return false;"><div class="text-truncate">{$_oItem.title}</div></a>
											</li>
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
					<div class="menu-vertical tXLeeWFxHb bg-menu-theme shadow-none position-sticky" style="top:90px">
						<ul class="menu-inner report_menu">
							{foreach from=$list_menus key= _oK item  = _oItem}
								<li class="menu-item {if $_oK eq $_type}active{/if}" _type="{$_oK}">
									<a href="javascript:void(0);" class="menu-link text-body ml-0" onClick="$Core.fund.dashboard.load_content_detail(this, '{$_oK}', {}); return false;"><div class="text-truncate">{$_oItem.title}</div></a>
								</li>
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
<script>
	var _type = `{$_type}`;
</script>
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
		$Core.fund.dashboard.load_content(_type,{});
	});
</script>
{/literal}
