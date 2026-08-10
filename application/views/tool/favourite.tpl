{literal}

<style type="text/css">

	.banner{

		width:100%;

		height:200px;

		margin-bottom:15px;

		background-size:cover;

		background-position:center center;

		background-repeat:no-repeat;

		border-radius:5px;

		-moz-border-radius:5px;

		-webkit-border-radius:5px;

		-khtml-border-radius:5px;

	}

	.right__page-breadcrumb {

		height: 60px;

		padding:15px 10px;

		background: #fff;

		border-bottom: 1px solid rgb(233 233 233);

		font-weight:bold;

		position:sticky;

		left:0; top:0;

		z-index:3;

	}

	@media screen and (min-width:992px){

		.banner{height:250px;}

	}

	@media screen and (min-width:1200px){

		.banner{height:300px;}

	}

</style>

{/literal}

<div class="container-xxl flex-grow-1 container-p-y">

	{if $show eq 'detail' && !empty($oneMeta.banner)}

	<div class="banner" style="background-image:url({$oneMeta.banner})"></div>

	{/if}

	{if $show eq 'list'}

	<div class="card overflow-hidden">	

		<h5 class="card-header border-bottom position-relative">Căn hộ yêu thích</h5>

		<div class="card-body p-{if $deviceType eq 'phone'}2{else}3{/if}">

			<div class="input-group input-group-merge">

				<span class="input-group-text"><i class="bx bx-search"></i></span>

				<input class="form-control" onkeyup="$Core.tool.iso_search_field(this, event)" 

				toClass="iso_search_item" placeholder="Tìm kiếm" />

			</div>

			<div class="table-wrapper overflow-x-auto mt-2 text-nowrap">

				<table class="table table-bordered">

					<thead><tr>

						{if $deviceType ne 'phone'}

						<th width="45px" class="align-center text-center bg-lighter">No.</th>

						<th class="align-center bg-lighter">Mã căn</th>

						<th class="align-center bg-lighter">Tình trạng</th>

						<th class="align-center bg-lighter">Giá F.Vat</th>

						<th class="align-center text-center bg-lighter">Diện tích</th>

						<th class="align-center bg-lighter">Loại căn</th>

						{else}

						<th class="align-center bg-lighter">Mã căn</th>

						<th class="align-center bg-lighter">Giá</th>

						<th class="align-center bg-lighter">M2</th>

						<th class="align-center bg-lighter">Loại</th>

						{/if}

						{if $deviceType ne 'phone'}

						<th class="align-center bg-lighter">Hướng BC</th>

						<th class="align-center text-center bg-lighter">Phiếu TG</th>

						<th class="align-center text-center bg-lighter">CSBH</th>

						<th class="align-center bg-lighter" width="40px"></th>

						{/if}

					</tr></thead>

					<tbody class="holder_wishlist">

						<tr class="nohover">

							<td colspan="10" class="text-center">

								<img src="{$URL_IMAGES}/listing-empty.svg" width="120px" />

								<p>Đang tải dữ liệu Căn hộ yêu thích...</p>

							</td>

						</tr>

					</tbody>

				</table>

			</div>

			

		</div>

		<script type="text/javascript">

			$(function(){

				setTimeout(() => {

					$Core.tool.load_wishlist({});

				}, 1000);

			});

		</script>

	</div>

	{else}

	<nav aria-label="breadcrumb">

		<ol class="breadcrumb mb-2">

			<li class="breadcrumb-item">

				<a href="{$PCMS_URL}/thong-tin/">Thông tin dự án</a>

			</li>

			<li class="breadcrumb-item">

				<a href="/thong-tin/p{$project_id}.html">{$clsProject->getCode($project_id,$oneProject)}</a>

			</li>

			<li class="breadcrumb-item">

				<a href="{$clsProject->getLinkDetail($project_id,$block_id,0,$oneProject)}">{$clsProperty->getTitle($block_id)}</a>

			</li>

			{if $stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}

				<li class="breadcrumb-item">

					<a href="{$clsProject->getLink($project_id, $building_id)}">{$oneBuilding.title}</a>

				</li>

			{/if}

			{if !empty($ms_code)}

				<li class="breadcrumb-item active">{$ms_code}</li>

			{/if}

		</ol>

	</nav>

	{$core->getBlock("banner_stock")}

	<div class="row">

		<div class="col-12 col-xxl-6 col-lg-8 offset-xxl-3 offset-lg-2">

			{if $show eq 'detail' && !empty($oneMeta.banner)}

			<div class="banner">

				<img src="{$oneMeta.banner}" />

			</div>

			{/if}

			<div class="rounded-3 mb-2 overflow-hidden">

				<div class="bg-main p-3 w-100 text-center">

					<div class="d-flex gap-2 justify-content-center align-items-center">

						<strong class="text-white fs-4">{$oneStock.ms_code}</strong>

						<a onClick="$Core.helper.toggle_wishlist(this,event)" stock_id="{$stock_id}" class="btn btn-icon bg-white btn-sm{if $clsProfile->checkInWishlist($stock_id)} saved{else} btn-outline-default{/if} p-2 wishlist_{$stock_id}" data-bs-toggle="tooltip" title="Loại bỏ">{$clsISO->makeIcon('bx-heart')}</a>

						{if !empty($vr_link)}

						<a title="Xem VR360" href="{$vr_link}" data-fancybox data-type="iframe">

							<img src="{$URL_IMAGES}/vr-w-360.png" class="w-px-30">

						</a>

						{/if}

						{if $profile_id eq 289}

						<button class="btn btn-icon text-white" onclick="$Core.global.share_stock(this,event)" stock_id="{$stock_id}" >

							<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" >

							<path d="M5.5 15.5c1.07 0 2.02-.5 2.67-1.26l6.87 3.87c-.01.13-.04.26-.04.39 0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5-1.57-3.5-3.5-3.5c-1.07 0-2.02.5-2.67 1.26l-6.87-3.87c.01-.13.04-.26.04-.39s-.02-.26-.04-.39l6.87-3.87C16.47 8.5 17.42 9 18.5 9 20.43 9 22 7.43 22 5.5S20.43 2 18.5 2 15 3.57 15 5.5c0 .13.02.26.04.39L8.17 9.76A3.48 3.48 0 0 0 5.5 8.5C3.57 8.5 2 10.07 2 12s1.57 3.5 3.5 3.5m13 1.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5m0-13c.83 0 1.5.67 1.5 1.5S19.33 7 18.5 7 17 6.33 17 5.5 17.67 4 18.5 4m-13 6.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5S4 12.83 4 12s.67-1.5 1.5-1.5"></path>

							</svg>

						</button>

						{/if}

					</div>

				</div>

				<div class="p-3 bg-grayter">

					<div class="text-center">

						<div class="mb-0 position-relative text-main fs-24 fw-bold">

							<i class="material-icons-outlined fs-24 mr-1">shopping_cart</i>

							{if $oneStock.stock_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}

								{$clsISO->formatPriceV2($total_price_vat)} tỷ

							{else}

								{if !empty($total_price_vat)}

									{$clsISO->formatPrice($total_price_vat)}

								{else}

									Check Admin

								{/if}

							{/if}

						</div>

						<p class="mb-0">

							{if $has_price_early eq '1'}

								Giá trên là phương án <strong>Thanh Toán Sớm</strong>

							{else}

								Giá đã bao gồm VAT & KPBT

							{/if}

						</p>

						{if !empty($more_information.csbh)}

						<p class="text-main mb-0 fs-13">Chính sách bán hàng áp dụng: {$more_information.csbh}</p>

						{/if}

						{if !empty($html_price_more)}

							{$html_price_more}

						{/if}

						{if $img_price_sheets}

							{$img_price_sheets}

						{/if}

					</div>

					{if $loggedIn eq '1' && $clsISO->checkPermission('edit_stock_advanced') eq '1'}

					<div class="d-flex flex-wrap gap-2 fs-12 mt-2 justify-content-center">

						<button stock_id="{$stock_id}" tp="sheet_price" onClick="$Core.helper.open_quick_stock(this,event)" 

						class="btn btn-sm btn-outline-default bg-white" type="button">{$core->makeIcon('plus', 'Thêm PTG')}</button>

						<button stock_id="{$stock_id}" tp="update_status" class="btn btn-sm btn-outline-primary" onClick="$Core.helper.open_quick_stock(this,event)"  type="button">{$core->makeIcon('check', 'Tình trạng')}</button>

						<button stock_id="{$stock_id}" tp="poster" class="btn btn-sm btn-outline-default bg-white" onClick="$Core.helper.open_quick_stock(this,event)" type="button">{$core->makeIcon('check', 'Poster')}</button>

						{if $clsRequestPTG->checkRequest($stock_id)}

							<button class="btn btn-sm btn-success" type="button" style="cursor:no-drop"><i class="fa fa-check"></i> <span>Đã yêu cầu PTG</span></button>

						{else}

							<button stock_id="{$stock_id}" onclick="$Core.helper.requestPTG(this,event)" action="_OPEN" id="request_{$stock_id}" class="btn btn-sm btn-warning" type="button"><i class="bx bx-vector"></i> <span>Yêu cầu PTG</span></button>

						{/if}

						{if !empty($clsISO->checkPermission('upload_poster'))} 

							<div class="btn-group">

								<button type="button" class="btn btn-outline-primary btn-icon  dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">

									<i class="bx bx-dots-vertical-rounded"></i>

								</button>

								<ul class="dropdown-menu no-hidden dropdown-menu-end">

									<li><a onClick="$Core.helper.open_quick_stock(this,event)" stock_id="{$stock_id}" tp="poster" class="dropdown-item" href="javascript:void(0);">Thêm postter</a></li>

									<li><a onClick="$Core.helper.open_quick_stock(this,event)" stock_id="{$stock_id}" tp="video" class="dropdown-item" href="javascript:void(0);">Thêm Video</a></li>

								</ul>

							</div>

						{/if}

					</div>

					{/if}	

					{if $oneStock.stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}

					<div class="w-full mb-2 d-flex gap-2 justify-content-center mt-2">

						<a class="text-primary  text-nowrap bg-white btn-sm rounded-pill d-flex align-items-center" href="{$clsStock->getLinkSearch($building_id,$stock_id,'building')}" target="_blank">Tòa {$clsProperty->getCode($building_id)}<i class="bx bx-link-external fs-5"></i></a>

						<a class="text-primary  text-nowrap bg-white btn-sm rounded-pill d-flex align-items-center" href="{$clsStock->getLinkSearch($building_id,$stock_id,'floor')}" target="_blank">Tầng {$oneStock.floor}<i class="bx bx-link-external fs-5"></i></a>

						<a class="text-primary  text-nowrap bg-white btn-sm rounded-pill d-flex align-items-center" href="{$clsStock->getLinkSearch($building_id,$stock_id,'code')}" target="_blank">Trục {$oneStock.code}<i class="bx bx-link-external fs-5"></i></a>

					</div>

					{/if}

				</div>

				<div class="py-3 bg-lighter position-relative">

					{if $oneStock.stock_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}

					<div class="w-100 mb-3 d-flex gap-1 align-items-center">

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-bedroom--sm"></i> Loại căn</p>

							<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="{$clsStock->getLinkSearch($building_id,$stock_id,'bedroom')}" target="_blank">{$clsProperty->getTitle($oneStock.bedroom_id)}<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-ying-yang--xl"></i> Hướng</p>

							<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="{$clsStock->getLinkSearch($building_id,$stock_id,'direction')}" target="_blank">{$clsProperty->getTitleQr($oneStock.home_direction_id)}<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>

						</div>

						<div class="x-box flex-fill text-center d-none d-lg-block">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Tim tường</p>

							<h4 class="fs-14 mb-0">{$more_information.DT_Tim} m2</h3>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Thông thuỷ</p>

							<h4 class="fs-14 mb-0">{$more_information.DT_TT} m2</h3>

						</div>

					</div>

					<div class="w-100 d-flex gap-1 align-items-center">

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-radio-checked--sm"></i> Tình trạng</p>

							<h4 class="fs-14 mb-0">{$clsStock->getStatus($oneStock.status_id)}</h4>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-sun--sm"></i> Loại hình</p>

							<h4 class="fs-14 mb-0">{$clsProperty->getLoaiHinh($oneStock.type_id,$oneType)}</h4>

						</div>

						<div class="x-box flex-fill text-center d-none d-lg-block">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-benefit--sm"></i> View</p>

							<h4 class="fs-14 mb-0">

								{if !empty($oneStock.view_id)}

									{$clsProperty->getTitle($oneStock.view_id)}

								{else}

									--

								{/if}

							</h4>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-money--sm"></i> Giá/m2</p>

							<h4 class="fs-13 mb-0">

								<span>{$clsISO->priceFormat($price_m2)} tr</span>

							</h4>

						</div>

					</div> 

					{else}

					<div class="w-full mb-3 d-flex">

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-bedroom--sm"></i> Loại căn</p>

							<h4 class="fs-14 mb-0">{$clsProperty->getTitle($oneStock.bedroom_id)}</h3>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-ying-yang--xl"></i> Hướng</p>

							<h4 class="fs-14 mb-0">{$clsProperty->getTitleQr($oneStock.home_direction_id)}</h3>

						</div>

						<div class="x-box flex-fill text-center d-none d-lg-block">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Tim tường</p>

							<h4 class="fs-14 mb-0">{$more_information.DT_Tim} m2</h3>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Thông thuỷ</p>

							<h4 class="fs-14 mb-0">{$more_information.DT_TT} m2</h3>

						</div>

					</div>

					<div class="w-full w-100 d-flex">

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-radio-checked--sm"></i> Tình trạng</p>

							<h4 class="fs-14 mb-0">{$clsStock->getStatus($oneStock.status_id)}</h4>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-sun--sm"></i> Loại hình</p>

							<h4 class="fs-14 mb-0">{$clsStock->getStatus($oneStock.type_id)}</h4>

						</div>

						<div class="x-box flex-fill text-center d-none d-lg-block">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-benefit--sm"></i> View</p>

							<h4 class="fs-14 mb-0">

								{if !empty($oneStock.view_id)}

									{$clsProperty->getTitle($oneStock.view_id)}

								{else}

									--

								{/if}

							</h4>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-money--sm"></i> Giá/m2</p>

							<h4 class="fs-13 mb-0">

								<span>{$clsISO->priceFormat($more_information.total_price_vat/$clsISO->convertToNumber($DT_TT))}</span>

							</h4>

						</div>

					</div> 

					{/if}

					{if !empty($html_links)}

					<div class="px-3">

						<hr class="my-4" />

						<ul class="list-unstyled{if $deviceType ne 'phone'} _2column{/if}">

							{$html_links}

						</ul>

					</div>

					{/if}

				</div>

			</div>

			{if !empty($oneTemplate.layout_ns)}

			<div class="card no-shadow mb-2 overflow-hidden">

				<div class="card-header">

					<h5 class="card-title mb-0">Layout chi tiết căn hộ</h5>

				</div>

				<div class="card-body position-relative p-0">

					<a class="w-100" href="{$clsISO->getGoogleUrl($oneTemplate.layout_ns)}" data-fancybox="true">

						<img class="img-fluid" src="{$clsISO->getGoogleUrl($oneTemplate.layout_ns)}" />

					</a>

				</div>

			</div>

			{/if}

			{if !empty($oneTemplate.video)}

			<div class="card no-shadow mb-2 overflow-hidden">

				<div class="card-header">

					<h5 class="card-title mb-0">Video căn mẫu</h5>

				</div>

				<div class="card-body">

					{if $deviceType eq 'phone'}

						{assign var = frame_height value = 200}

					{else}

						{assign var = frame_height value = 400}

					{/if}

					{$clsISO->getEmbedVideo($oneTemplate.video, '100%', $frame_height)}

				</div>

			</div>

			{/if}

			{if !empty($list_help_links)}

			<div class="card no-shadow d-none d-lg-block mb-2">

				<div class="card-header">

					<h5 class="card-title mb-0">Liên kết tiện ích</h5>

				</div>

				<div class="card-body">

					{foreach from=$list_help_links item = link}

					<a class="mx-1" data-fancybox{if $link.is_driver eq '1'} data-type="iframe"{/if} data-bs-toggle="tooltip" title="{$link.title}" href="{$link.link}">&bull; {$link.title} {$oneBuilding.title}</a>

					{/foreach}

				</div>

			</div>

			{/if}

			<div class="card no-shadow d-none d-lg-block mb-2">

				<div class="card-header d-flex align-items-center justify-content-between">

					<h5 class="card-title">Điểm nổi bật</h5>

					<button clsTable="StockMeta" onClick="$Core.tool.edit_stock_field(this,event)" p_field="content" p_id="{$stock_meta_id}" class="d-flex align-items-center btn btn-sm btn-outline-default">{$clsISO->makeIcon('bx-pencil', ' Sửa')}</button>

				</div>

				<div class="card-body">

					<div class="tinyContent">

						{if 1==2}

							{if !empty($oneMeta.content)}

								{$oneMeta.content}

							{else}

								<p class="my-0 text-center text-muted">Chưa có nội dung</p>

							{/if}

						{else}

							{if !empty($list_stocks_meta)}

								{section name=i loop=$list_stocks_meta}

									{$list_stocks_meta[i].content}

									<p class="text-muted fs-12">

										<i class='bx bx-pen'></i>

										{$list_stocks_meta[i].author}

									</p>

								{/section}

							{else}

								<p class="my-0 text-center text-muted">Chưa có nội dung</p>

							{/if}

						{/if}

					</div>

				</div>

			</div>

			{if !empty($html_image_price_sheets)}

			<div class="card no-shadow mb-2">

				<div class="card-header">

					<h5 class="card-title mb-0">Phiếu tính giá</h5>

				</div>

				<div class="card-body">

					<div class="widget-content position-relative">

						{$html_image_price_sheets}

					</div>

				</div>

			</div>

			{/if}

			<div class="card no-shadow mb-2">

				{assign var = toId value = $clsISO->getUniqid()}

				<div class="card-header d-flex align-items-center justify-content-between">

					<h5 class="card-title mb-0">Media</h5>

					<div class="button-groups d-flex">

						<button onClick="$Core.tool.add_stock_image(this, event)" toId="{$toId}" stock_meta_id="{$stock_meta_id}" class="btn btn-sm btn-outline-default mr-2">{$clsISO->makeIcon('bx-plus', 'Ảnh')}</button>

						<button onClick="$Core.tool.open_stock_video(this, event)" toId="{$toId}" stock_meta_id="{$stock_meta_id}" class="btn btn-sm btn-outline-default">{$clsISO->makeIcon('bx-plus', 'Video')}</button>

					</div>

				</div>

				<div class="card-body">

					<form method="POST" class="d-none" enctype="multipart/form-data">

						<input type="hidden" name="is_multiple" value="1" />

						<input type="file" onchange="$Core.tool.upload_stock_image(this, event)" accept="image/*" multiple="multiple" id="{$toId}" toId="{$toId}" stock_meta_id="{$stock_meta_id}" name="images[]" />

					</form>

					<div class="holder_stock_media_{$stock_meta_id}">

						<div class="loader p-2 text-center">Loading...</div>

					</div>

				</div>

			</div>

			<div class="card no-shadow mb-2">

				{assign var = toId value = $clsISO->getUniqid()}

				<div class="card-header d-flex justify-content-between align-items-center">

					<h5 class="card-title mb-0">File đính kèm</h5>

					<button onClick="$Core.tool.add_stock_file(this, event)" toId="{$toId}" stock_meta_id="{$stock_meta_id}" class="btn btn-sm btn-outline-default">{$clsISO->makeIcon('bx-plus', 'Thêm')}</button>

				</div>

				<div class="card-body">

					<form method="POST" class="d-none" enctype="multipart/form-data" action="">

						<input type="file" onchange="$Core.tool.upload_stock_file(this, event)" multiple="multiple" id="{$toId}" stock_meta_id="{$stock_meta_id}" name="attactments[]" />

					</form>

					<div class="holder_files_{$stock_meta_id}">

						<div class="loader p-2 text-center">Loading...</div>

					</div>

				</div>

			</div>

			{if !empty($oneBuilding.intro)}

			<div class="card no-shadow mb-2">

				<div class="card-header">

					<h5 class="card-title mb-0">Giới thiệu {$oneBuilding.title}</h5>

				</div>

				<div class="card-body">

					<div class="tinyContennt">

						{$oneBuilding.intro}

					</div>

				</div>

			</div>

			{/if}

			<div class="card no-shadow mb-2">

				<div class="card-body">

					{$core->getBlock('comment', ['table_id' => $stock_id, 'clsTable' => 'Stock'])}

				</div>

			</div>

		</div>

		<script type="text/javascript"> 

			var stock_id = '{$stock_id}',

				stock_meta_id = '{$stock_meta_id}';

		</script>

		{literal}

		<script type="text/javascript">

			$(function(){

				$Core.tool.load_stock_media(stock_meta_id, {});

				$Core.member.load_list_files(stock_meta_id, 'StockMeta', {});

				$Core.news.load_comments(stock_id, 'Stock', {'action' : 'reload'});

				$('.tinyContennt').readmore({speed: 75, maxHeight: 410});

			});

		</script>

		{/literal}

	</div>

	{/if}

</div>





