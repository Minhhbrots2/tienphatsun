		<!-- {if $profile_id eq $smarty.const._PROFILE_TECH_ID}
			{$core->getBlock('callaction')}
		{/if} -->
		<div class="modal right fade" id="online-modal" tabindex="-1" aria-modal="true" aria-hidden="true" role="dialog">
			<div class="modal-dialog modal-dialog-scrollable" role="document">
				<div class="modal-content">
					<div class="modal-header pb-2">
						<h5 class="modal-title text-fs-16">Người Online</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body pt-2">
						<div id="online-list" class="online-list"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Footer -->
        <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl py-2 py-lg-3">
				{if $deviceType ne 'phone' && ($mod eq 'home' && $sub eq 'default' && $act eq 'default')}
					{$core->getBlock('top_ranker_25')}
				{/if}
				{if $sub ne 'lucky_wheel' && !($mod eq 'fund' && $sub eq 'dashboard')}
					{$core->getBlock('contact_footer')}
				{/if}
				{if $header_configs.CompanyName}
					<div class="mb-0 text-center mb-md-0">
						©Copyright {$smarty.now|date_format:"%Y"} by {$header_configs.CompanyName|escape}
					</div>
				{/if}
            </div>
        </footer>
        <!-- /Footer -->
		<!-- Menu bottom -->
		{if $deviceType eq 'phone'}
		<div class="bottom-navbar zindex-4">
			<div class="pt-2 pb-4 px-2 d-flex">
				{$core->getBlock('menu_bottom', ['oneColor' => $oneColor])}
			</div>
		</div>
		<div class="modal fade bottom modal_fade_bottom" id="modal_search_mobile" tabindex="-1" aria-modal="true" role="dialog"> 
			<div class="modal-dialog modal-xs modal-dialog-centered">
				<form class="modal-content">
					<div class="modal-header">
						<h3 class="title_modal text-center flex-fill mb-0">Tìm kiếm</h3>
						<button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-close btn-icon btn-sm p-0" style="background: #ffffff26;box-shadow: 0 0 3px #FFF"><i class='bx bx-x' ></i></button> 
					</div>
					<div class="modal-body">
						<div class="form-group mb-2">
							<label class="form-label">Loại hình</label>
							<div class="btn-group text-nowrap w-100" role="group" aria-label="Hiển thị" {$deviceType}>
								{assign var = gId value = $clsISO->getUniqid()}
								<input type="radio" class="btn-check" name="stock_type" onchange="$Core.helper.handle_stock_type(this, event)" id="{$gId}" value="{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}"{if $get_stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE} checked="checked"{/if}>
								<label data-toggle="ripple" title="Cao tầng" class="btn js__search-stock-type {if $get_stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}btn-outline-danger active{else}btn-outline-default{/if}" for="{$gId}" title="Cao tầng">Cao tầng</label>
								{assign var = gId value = $clsISO->getUniqid()}
								<input type="radio" class="btn-check" name="stock_type" id="{$gId}" onchange="$Core.helper.handle_stock_type(this, event)" value="{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}"{if $get_stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE} checked="checked"{/if}>
								<label data-toggle="ripple" title="Thấp tầng" class="btn js__search-stock-type {if $get_stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}btn-outline-danger active{else}btn-outline-default{/if}" for="{$gId}" title="Thấp tầng">Thấp tầng</label>
								{assign var = gId value = $clsISO->getUniqid()}
								<input type="radio" class="btn-check" name="stock_type" id="{$gId}" onchange="$Core.helper.handle_stock_type(this, event)" value="1"{if $get_stock_type eq '1'} checked="checked"{/if}>
								<label data-toggle="ripple" title="Thông tin" class="btn js__search-stock-type{if $get_stock_type eq '1'} btn-outline-danger active{else} btn-outline-default{/if}" for="{$gId}" title="Thông tin">Thông tin</label>
							</div>
						</div>
						<div class="form-group mb-2">
							<label class="form-label">Từ khóa</label>
							<input type="text" class="form-control form-control-lg js__top-search-input top_select_all shadow-none" name="keyword" placeholder="Tìm bất cứ thứ gì..."  value="{$keyword}" />
						</div>
					</div>
					<div class="modal-footer justify-content-center">
						<button data-toggle="ripple" class="btn btn-outline-primary btn-lg m-0 form-control" type="button" 
							onClick="$Core.mobile.search_all(this,event)">Tìm kiếm</button>
					</div>
				</form>
			</div>
		</div>
		{/if}
		<!-- /Menu bottom -->
        <div class="content-backdrop fade"></div>
    </div>
    <!-- /Content wrapper -->
</div>
{$core->getBlock('chat')}
{$core->getBlock('quick_action')}
