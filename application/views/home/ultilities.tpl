<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="p__left mb-2">
		<h3 class="fw-bold mb-1">Tiện ích hệ thống</h4>
		<span class="text-muted">Tổng hợp các tiện ích hệ thống</span>
	</div>
	<section class="section section-xxs">
		<div class="row flex-row flex-wrap">
			<!--<div class="col-12 col-md-6 col-xxl-4 mb-4">
				<div class="box_item_group card h-100">
					<div class="card-header">
						<h4 class="">1. Cá nhân</h4>
					</div>
					<div class="d-flex flex-wrap align-items-start gap-2 mb-3 px-3">
						<a class="item_menu d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/crm/">
							<span class="icon"><i class="menu-icon tf-icons bx bx-user-pin me-1"></i></span>
							<span class="">CRM</span>
						</a>
						<a class="item_menu d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/ban-tin.html">
							<span class="icon"><i class="menu-icon tf-icons bx bxs-news me-1"></i></span>
							<span class="">Bản tin {$smarty.const.BRAND_NAME}</span>
						</a>
						<a class="item_menu d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/lich-dao-tao.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-slideshow me-1"></i></span>
							<span class="">Sự kiện đào tạo</span>
						</a>
						<a class="item_menu d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/quan-ly-khach-hang.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-user me-1"></i></span>
							<span class="">Quản lý khách hàng</span>
						</a>
						<a class="item_menu d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/net-dep-lao-dong.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-image me-1"></i></span>
							<span class="">Hoạt động tiếp khách</span>
						</a>
						<a class="item_menu d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/report/report_stock_price.html">
							<span class="icon"><i class="menu-icon tf-icons bx bx-money me-1"></i></span>
							<span class="">Tổng quan giá phân khu</span>
						</a>
						<a class="item_menu d-flex p-2 btn btn-outline-default align-items-center btn-sm flex-fill" href="{$PCMS_URL}/vinh-danh.html">
							<span class="icon"><i class="menu-icon tf-icons bx bxs-bell-ring me-1"></i></span>
							<span class="">Vinh danh bán hàng</span>
						</a>
					</div>
				</div>
			</div>-->
			{foreach from=$arr_utilities item=_oGroup key=key name=i}
				{assign var = list_utilities value=$_oGroup.list}
				<div class="col-12 col-md-6 col-xxl-4 {if $deviceType eq 'phone'}mb-2{else}mb-4{/if}">
					<div class="box_item_group card no-shadow h-100">
						<div class="card-header">
							<h4 class="mb-0 fs-16 fw-semibold">{$smarty.foreach.i.iteration}. {$_oGroup.title}</h4>
						</div>
						<div class="card-body">
							<div class="d-flex flex-wrap align-items-start gap-2">
								{foreach from=$list_utilities item=_oUtil key=k_ultilities}
								<!-- data-toggle="ripple" -->
								<a href="{$PCMS_URL}{$_oUtil.link}" target="_blank" style="background:{$_oUtil.bgcolor}; color:{$_oUtil.textcolor}" class="item_menu d-flex p-2 border-0 btn btn-outline-default align-items-center btn-sm flex-fill">
									<span class="icon me-1"><i class="menu-icon tf-icons fs-18 {$_oUtil.icon}"></i></span>
									<span>{$_oUtil.title}</span>
								</a>
								{/foreach}
							</div>
						</div>
					</div>
				</div>
			{/foreach}
		</div>
	</section>
</div>
{$scriptJs}
<script type="text/javascript">
	$(function(){
		if($('.awe__post-description:not(.collapsed)').length){
			$('.awe__post-description:not(.collapsed)').each((_i, _elem) => {
				var _height = $(_elem).outerHeight();
				if(_height > 200){
					$(_elem).addClass('collapsed').append('<a class="awe__link-more">Xem thêm...</a>');
				}
			});
		}
	});
</script>
