<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	{if $share_type ne 'secret'}
	<div class="d-flex flex-wrap justify-content-between align-items-center pt-2 pb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>{$titlePage}</span></h4>
			<p class="text-muted mb-0">Ghi nhận những hoạt động nổi bật tại {$smarty.const.BRAND_NAME}</p>
		</div>
	</div>
	{else}
	<div class="row">
		<div class="col-12 col-md-6 order-0 mb-3 order-xl-1 {if $share_type eq 'secret'}mx-auto{/if}">
			<h4 class="fw-bold mb-1"><span>{$titlePage}</span></h4>
			<p class="text-muted mb-0">Ghi nhận những hoạt động nổi bật tại {$smarty.const.BRAND_NAME}</p>
		</div>
	</div>
	{/if}
	<section class="section section-xxs">
		<div class="row">
			{if $share_type ne 'secret'}
			<div class="col-md-3 mb-3 mb-lg-0 order-1 order-xl-0 hidden-xs hidden-sm">
				<div class="card leftbar sticky">
					<div class="card-header d-flex align-items-center justify-content-between">
						<h5 class="card-title m-0 me-2">Giao dịch mới</h5>
					</div>
					<div class="card-body ajax load" data-bind="{$uid}" 
						data-url="{$PCMS_URL}/index.php?mod={$mod}&act=dashboard&tp=top_billing" data-options='{ldelim}{rdelim}'>
						<ul class="p-0 m-0">
							{section name = i loop=$staff_placeholders}
							<li class="d-flex mb-2 pb-1">
								<div class="avatar flex-shrink-0 me-2">
									<div class="animate-bg w-100 h-100 rounded">Avatar</div>
								</div>
								<div class="d-flex align-items-center justify-content-between gap-2 w-100">
									<div class="me-1">
										<div class="animate-bg radius-2 w-50 mb-1" style="height:10px">FH000</div>
										<div class="animate-bg radius-2 w-100" style="height:15px">{$oneProfile.full_name}</div>
									</div>
									<div class="user-progress d-flex align-items-center gap-1">
										<span class="animate-bg fs-13 mr-1" style="height:15px">00</span>lần
									</div>
								</div>
							</li>
							{/section}
						</ul>
					</div>	
				</div>
			</div>				
			{/if}
			<!--  offset-md-3 -->
			<div class="col-12 col-md-6 order-0 mb-2 order-xl-1{if $share_type eq 'secret'} mx-auto{/if} {if $share_type eq 'honor'}col-lg-5{/if}">
				<div class="awe__post-page awe__post-page_{$share_type}">
					{if !empty($list_files)}
					<div class="awe__list-post awe__list-share"> 
						{foreach name=i from=$list_files item = image}
							{if !empty($image)}
								<div class="awe__post-item awe__share-item" reg_date="{$_oShare.reg_date}" id="post_item_{$share_id}">							
									<div class="awe__post-item-body">
										<div class="awe__post-gallery gallery mb-2" data-fancybox="image" data-src="{$image}&sz=w1500">
											<img src="{$image}&sz=w760" alt="" class="w-100"> 
										</div>
									</div>
								</div>
							{/if} 
						{/foreach}
					</div>
					{else}
					<div class="p-6 bg- no-result text-center">
						<img src="{$URL_IMAGES}/empty.svg?v={$upd_version}" width="100px" />
						<p class="text-muted mt-3">Không có vinh danh nào</p>
					</div>
					{/if}
					<div class="clearfix"></div>
					{if $total_page gt $current_page}
					<div class="d-flex justify-content-center">
						<button onClick="$Core.share.load_honor_more(this, event)" share_type="{$share_type}" class="btn btn-block btn-lg btn-link bg-white font-weight-bold" page="{$current_page}" total_loaded="{$per_page}" total_record="{$total_record}">Xem thêm</button>
					</div>
					{/if}
			   </div>
			</div>
			<div class="col-md-3 order-2 order-xl-2">
				<div class="sticky">
					<div class="card">
						{$core->getBlock('top_staff')}
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
{$scriptJs}
{literal}
<style type="text/css">
	.awe__post-form,
	.awe__post-item{
		box-shadow:0px 0px 6px rgb(173 168 168 / 20%);
		-moz-box-shadow:0px 0px 6px rgb(173 168 168 / 20%);
		-webkit-box-shadow:0px 0px 6px rgb(173 168 168 / 20%);
		-khtml-box-shadow:0px 0px 6px rgb(173 168 168 / 20%);
	}
	.selecttize-lg .selectize-dropdown, 
	.selecttize-lg .selectize-input, 
	.selecttize-lg .selectize-input input{
		line-height:36px !important;
	}
	@media screen and (min-width:1200px){
		.sticky{ top:86px;}
	}
</style>
{/literal}