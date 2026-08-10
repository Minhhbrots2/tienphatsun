<?php
/* Smarty version 3.1.33, created on 2026-08-07 15:47:02
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/tool/favourite.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a759b868fac68_87264597',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'aa8a025479a8fee4746728490beb71e3afd7834f' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/tool/favourite.tpl',
      1 => 1784299679,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a759b868fac68_87264597 (Smarty_Internal_Template $_smarty_tpl) {
?>

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



<div class="container-xxl flex-grow-1 container-p-y">

	<?php if ($_smarty_tpl->tpl_vars['show']->value == 'detail' && !empty($_smarty_tpl->tpl_vars['oneMeta']->value['banner'])) {?>

	<div class="banner" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['oneMeta']->value['banner'];?>
)"></div>

	<?php }?>

	<?php if ($_smarty_tpl->tpl_vars['show']->value == 'list') {?>

	<div class="card overflow-hidden">	

		<h5 class="card-header border-bottom position-relative">Căn hộ yêu thích</h5>

		<div class="card-body p-<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>2<?php } else { ?>3<?php }?>">

			<div class="input-group input-group-merge">

				<span class="input-group-text"><i class="bx bx-search"></i></span>

				<input class="form-control" onkeyup="$Core.tool.iso_search_field(this, event)" 

				toClass="iso_search_item" placeholder="Tìm kiếm" />

			</div>

			<div class="table-wrapper overflow-x-auto mt-2 text-nowrap">

				<table class="table table-bordered">

					<thead><tr>

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

						<th width="45px" class="align-center text-center bg-lighter">No.</th>

						<th class="align-center bg-lighter">Mã căn</th>

						<th class="align-center bg-lighter">Tình trạng</th>

						<th class="align-center bg-lighter">Giá F.Vat</th>

						<th class="align-center text-center bg-lighter">Diện tích</th>

						<th class="align-center bg-lighter">Loại căn</th>

						<?php } else { ?>

						<th class="align-center bg-lighter">Mã căn</th>

						<th class="align-center bg-lighter">Giá</th>

						<th class="align-center bg-lighter">M2</th>

						<th class="align-center bg-lighter">Loại</th>

						<?php }?>

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

						<th class="align-center bg-lighter">Hướng BC</th>

						<th class="align-center text-center bg-lighter">Phiếu TG</th>

						<th class="align-center text-center bg-lighter">CSBH</th>

						<th class="align-center bg-lighter" width="40px"></th>

						<?php }?>

					</tr></thead>

					<tbody class="holder_wishlist">

						<tr class="nohover">

							<td colspan="10" class="text-center">

								<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/listing-empty.svg" width="120px" />

								<p>Đang tải dữ liệu Căn hộ yêu thích...</p>

							</td>

						</tr>

					</tbody>

				</table>

			</div>

			

		</div>

		<?php echo '<script'; ?>
 type="text/javascript">

			$(function(){

				setTimeout(() => {

					$Core.tool.load_wishlist({});

				}, 1000);

			});

		<?php echo '</script'; ?>
>

	</div>

	<?php } else { ?>

	<nav aria-label="breadcrumb">

		<ol class="breadcrumb mb-2">

			<li class="breadcrumb-item">

				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/thong-tin/">Thông tin dự án</a>

			</li>

			<li class="breadcrumb-item">

				<a href="/thong-tin/p<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
.html"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getCode($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['oneProject']->value);?>
</a>

			</li>

			<li class="breadcrumb-item">

				<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['block_id']->value,0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['block_id']->value);?>
</a>

			</li>

			<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>

				<li class="breadcrumb-item">

					<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLink($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['building_id']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
</a>

				</li>

			<?php }?>

			<?php if (!empty($_smarty_tpl->tpl_vars['ms_code']->value)) {?>

				<li class="breadcrumb-item active"><?php echo $_smarty_tpl->tpl_vars['ms_code']->value;?>
</li>

			<?php }?>

		</ol>

	</nav>

	<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("banner_stock");?>


	<div class="row">

		<div class="col-12 col-xxl-6 col-lg-8 offset-xxl-3 offset-lg-2">

			<?php if ($_smarty_tpl->tpl_vars['show']->value == 'detail' && !empty($_smarty_tpl->tpl_vars['oneMeta']->value['banner'])) {?>

			<div class="banner">

				<img src="<?php echo $_smarty_tpl->tpl_vars['oneMeta']->value['banner'];?>
" />

			</div>

			<?php }?>

			<div class="rounded-3 mb-2 overflow-hidden">

				<div class="bg-main p-3 w-100 text-center">

					<div class="d-flex gap-2 justify-content-center align-items-center">

						<strong class="text-white fs-4"><?php echo $_smarty_tpl->tpl_vars['oneStock']->value['ms_code'];?>
</strong>

						<a onClick="$Core.helper.toggle_wishlist(this,event)" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" class="btn btn-icon bg-white btn-sm<?php if ($_smarty_tpl->tpl_vars['clsProfile']->value->checkInWishlist($_smarty_tpl->tpl_vars['stock_id']->value)) {?> saved<?php } else { ?> btn-outline-default<?php }?> p-2 wishlist_<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" data-bs-toggle="tooltip" title="Loại bỏ"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-heart');?>
</a>

						<?php if (!empty($_smarty_tpl->tpl_vars['vr_link']->value)) {?>

						<a title="Xem VR360" href="<?php echo $_smarty_tpl->tpl_vars['vr_link']->value;?>
" data-fancybox data-type="iframe">

							<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/vr-w-360.png" class="w-px-30">

						</a>

						<?php }?>

						<?php if ($_smarty_tpl->tpl_vars['profile_id']->value == 289) {?>

						<button class="btn btn-icon text-white" onclick="$Core.global.share_stock(this,event)" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" >

							<svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" >

							<path d="M5.5 15.5c1.07 0 2.02-.5 2.67-1.26l6.87 3.87c-.01.13-.04.26-.04.39 0 1.93 1.57 3.5 3.5 3.5s3.5-1.57 3.5-3.5-1.57-3.5-3.5-3.5c-1.07 0-2.02.5-2.67 1.26l-6.87-3.87c.01-.13.04-.26.04-.39s-.02-.26-.04-.39l6.87-3.87C16.47 8.5 17.42 9 18.5 9 20.43 9 22 7.43 22 5.5S20.43 2 18.5 2 15 3.57 15 5.5c0 .13.02.26.04.39L8.17 9.76A3.48 3.48 0 0 0 5.5 8.5C3.57 8.5 2 10.07 2 12s1.57 3.5 3.5 3.5m13 1.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5m0-13c.83 0 1.5.67 1.5 1.5S19.33 7 18.5 7 17 6.33 17 5.5 17.67 4 18.5 4m-13 6.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5S4 12.83 4 12s.67-1.5 1.5-1.5"></path>

							</svg>

						</button>

						<?php }?>

					</div>

				</div>

				<div class="p-3 bg-grayter">

					<div class="text-center">

						<div class="mb-0 position-relative text-main fs-24 fw-bold">

							<i class="material-icons-outlined fs-24 mr-1">shopping_cart</i>

							<?php if ($_smarty_tpl->tpl_vars['oneStock']->value['stock_type'] == @constant('_BLOCK_TYPE_LOWFLOOR_SALE')) {?>

								<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatPriceV2($_smarty_tpl->tpl_vars['total_price_vat']->value);?>
 tỷ

							<?php } else { ?>

								<?php if (!empty($_smarty_tpl->tpl_vars['total_price_vat']->value)) {?>

									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatPrice($_smarty_tpl->tpl_vars['total_price_vat']->value);?>


								<?php } else { ?>

									Check Admin

								<?php }?>

							<?php }?>

						</div>

						<p class="mb-0">

							<?php if ($_smarty_tpl->tpl_vars['has_price_early']->value == '1') {?>

								Giá trên là phương án <strong>Thanh Toán Sớm</strong>

							<?php } else { ?>

								Giá đã bao gồm VAT & KPBT

							<?php }?>

						</p>

						<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['csbh'])) {?>

						<p class="text-main mb-0 fs-13">Chính sách bán hàng áp dụng: <?php echo $_smarty_tpl->tpl_vars['more_information']->value['csbh'];?>
</p>

						<?php }?>

						<?php if (!empty($_smarty_tpl->tpl_vars['html_price_more']->value)) {?>

							<?php echo $_smarty_tpl->tpl_vars['html_price_more']->value;?>


						<?php }?>

						<?php if ($_smarty_tpl->tpl_vars['img_price_sheets']->value) {?>

							<?php echo $_smarty_tpl->tpl_vars['img_price_sheets']->value;?>


						<?php }?>

					</div>

					<?php if ($_smarty_tpl->tpl_vars['loggedIn']->value == '1' && $_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('edit_stock_advanced') == '1') {?>

					<div class="d-flex flex-wrap gap-2 fs-12 mt-2 justify-content-center">

						<button stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" tp="sheet_price" onClick="$Core.helper.open_quick_stock(this,event)" 

						class="btn btn-sm btn-outline-default bg-white" type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus','Thêm PTG');?>
</button>

						<button stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" tp="update_status" class="btn btn-sm btn-outline-primary" onClick="$Core.helper.open_quick_stock(this,event)"  type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check','Tình trạng');?>
</button>

						<button stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" tp="poster" class="btn btn-sm btn-outline-default bg-white" onClick="$Core.helper.open_quick_stock(this,event)" type="button"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check','Poster');?>
</button>

						<?php if ($_smarty_tpl->tpl_vars['clsRequestPTG']->value->checkRequest($_smarty_tpl->tpl_vars['stock_id']->value)) {?>

							<button class="btn btn-sm btn-success" type="button" style="cursor:no-drop"><i class="fa fa-check"></i> <span>Đã yêu cầu PTG</span></button>

						<?php } else { ?>

							<button stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" onclick="$Core.helper.requestPTG(this,event)" action="_OPEN" id="request_<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" class="btn btn-sm btn-warning" type="button"><i class="bx bx-vector"></i> <span>Yêu cầu PTG</span></button>

						<?php }?>

						<?php if (!empty($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('upload_poster'))) {?> 

							<div class="btn-group">

								<button type="button" class="btn btn-outline-primary btn-icon  dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">

									<i class="bx bx-dots-vertical-rounded"></i>

								</button>

								<ul class="dropdown-menu no-hidden dropdown-menu-end">

									<li><a onClick="$Core.helper.open_quick_stock(this,event)" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" tp="poster" class="dropdown-item" href="javascript:void(0);">Thêm postter</a></li>

									<li><a onClick="$Core.helper.open_quick_stock(this,event)" stock_id="<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
" tp="video" class="dropdown-item" href="javascript:void(0);">Thêm Video</a></li>

								</ul>

							</div>

						<?php }?>

					</div>

					<?php }?>	

					<?php if ($_smarty_tpl->tpl_vars['oneStock']->value['stock_type'] == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>

					<div class="w-full mb-2 d-flex gap-2 justify-content-center mt-2">

						<a class="text-primary  text-nowrap bg-white btn-sm rounded-pill d-flex align-items-center" href="<?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getLinkSearch($_smarty_tpl->tpl_vars['building_id']->value,$_smarty_tpl->tpl_vars['stock_id']->value,'building');?>
" target="_blank">Tòa <?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getCode($_smarty_tpl->tpl_vars['building_id']->value);?>
<i class="bx bx-link-external fs-5"></i></a>

						<a class="text-primary  text-nowrap bg-white btn-sm rounded-pill d-flex align-items-center" href="<?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getLinkSearch($_smarty_tpl->tpl_vars['building_id']->value,$_smarty_tpl->tpl_vars['stock_id']->value,'floor');?>
" target="_blank">Tầng <?php echo $_smarty_tpl->tpl_vars['oneStock']->value['floor'];?>
<i class="bx bx-link-external fs-5"></i></a>

						<a class="text-primary  text-nowrap bg-white btn-sm rounded-pill d-flex align-items-center" href="<?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getLinkSearch($_smarty_tpl->tpl_vars['building_id']->value,$_smarty_tpl->tpl_vars['stock_id']->value,'code');?>
" target="_blank">Trục <?php echo $_smarty_tpl->tpl_vars['oneStock']->value['code'];?>
<i class="bx bx-link-external fs-5"></i></a>

					</div>

					<?php }?>

				</div>

				<div class="py-3 bg-lighter position-relative">

					<?php if ($_smarty_tpl->tpl_vars['oneStock']->value['stock_type'] == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>

					<div class="w-100 mb-3 d-flex gap-1 align-items-center">

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-bedroom--sm"></i> Loại căn</p>

							<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="<?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getLinkSearch($_smarty_tpl->tpl_vars['building_id']->value,$_smarty_tpl->tpl_vars['stock_id']->value,'bedroom');?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['oneStock']->value['bedroom_id']);?>
<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-ying-yang--xl"></i> Hướng</p>

							<h4 class="fs-14 mb-0"><a class="text-primary  text-nowrap" href="<?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getLinkSearch($_smarty_tpl->tpl_vars['building_id']->value,$_smarty_tpl->tpl_vars['stock_id']->value,'direction');?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitleQr($_smarty_tpl->tpl_vars['oneStock']->value['home_direction_id']);?>
<i class="bx bx-link-external fs-6 ml-1"></i></a></h4>

						</div>

						<div class="x-box flex-fill text-center d-none d-lg-block">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Tim tường</p>

							<h4 class="fs-14 mb-0"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_Tim'];?>
 m2</h3>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Thông thuỷ</p>

							<h4 class="fs-14 mb-0"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_TT'];?>
 m2</h3>

						</div>

					</div>

					<div class="w-100 d-flex gap-1 align-items-center">

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-radio-checked--sm"></i> Tình trạng</p>

							<h4 class="fs-14 mb-0"><?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getStatus($_smarty_tpl->tpl_vars['oneStock']->value['status_id']);?>
</h4>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-sun--sm"></i> Loại hình</p>

							<h4 class="fs-14 mb-0"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getLoaiHinh($_smarty_tpl->tpl_vars['oneStock']->value['type_id'],$_smarty_tpl->tpl_vars['oneType']->value);?>
</h4>

						</div>

						<div class="x-box flex-fill text-center d-none d-lg-block">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-benefit--sm"></i> View</p>

							<h4 class="fs-14 mb-0">

								<?php if (!empty($_smarty_tpl->tpl_vars['oneStock']->value['view_id'])) {?>

									<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['oneStock']->value['view_id']);?>


								<?php } else { ?>

									--

								<?php }?>

							</h4>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-money--sm"></i> Giá/m2</p>

							<h4 class="fs-13 mb-0">

								<span><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormat($_smarty_tpl->tpl_vars['price_m2']->value);?>
 tr</span>

							</h4>

						</div>

					</div> 

					<?php } else { ?>

					<div class="w-full mb-3 d-flex">

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-bedroom--sm"></i> Loại căn</p>

							<h4 class="fs-14 mb-0"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['oneStock']->value['bedroom_id']);?>
</h3>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-ying-yang--xl"></i> Hướng</p>

							<h4 class="fs-14 mb-0"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitleQr($_smarty_tpl->tpl_vars['oneStock']->value['home_direction_id']);?>
</h3>

						</div>

						<div class="x-box flex-fill text-center d-none d-lg-block">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Tim tường</p>

							<h4 class="fs-14 mb-0"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_Tim'];?>
 m2</h3>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-size--sm"></i> Thông thuỷ</p>

							<h4 class="fs-14 mb-0"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['DT_TT'];?>
 m2</h3>

						</div>

					</div>

					<div class="w-full w-100 d-flex">

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-radio-checked--sm"></i> Tình trạng</p>

							<h4 class="fs-14 mb-0"><?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getStatus($_smarty_tpl->tpl_vars['oneStock']->value['status_id']);?>
</h4>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-sun--sm"></i> Loại hình</p>

							<h4 class="fs-14 mb-0"><?php echo $_smarty_tpl->tpl_vars['clsStock']->value->getStatus($_smarty_tpl->tpl_vars['oneStock']->value['type_id']);?>
</h4>

						</div>

						<div class="x-box flex-fill text-center d-none d-lg-block">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-benefit--sm"></i> View</p>

							<h4 class="fs-14 mb-0">

								<?php if (!empty($_smarty_tpl->tpl_vars['oneStock']->value['view_id'])) {?>

									<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['oneStock']->value['view_id']);?>


								<?php } else { ?>

									--

								<?php }?>

							</h4>

						</div>

						<div class="x-box flex-fill text-center">

							<p class="text-muted mb-1"><i class="d-inline-block re__icon-money--sm"></i> Giá/m2</p>

							<h4 class="fs-13 mb-0">

								<span><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->priceFormat($_smarty_tpl->tpl_vars['more_information']->value['total_price_vat']/$_smarty_tpl->tpl_vars['clsISO']->value->convertToNumber($_smarty_tpl->tpl_vars['DT_TT']->value));?>
</span>

							</h4>

						</div>

					</div> 

					<?php }?>

					<?php if (!empty($_smarty_tpl->tpl_vars['html_links']->value)) {?>

					<div class="px-3">

						<hr class="my-4" />

						<ul class="list-unstyled<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?> _2column<?php }?>">

							<?php echo $_smarty_tpl->tpl_vars['html_links']->value;?>


						</ul>

					</div>

					<?php }?>

				</div>

			</div>

			<?php if (!empty($_smarty_tpl->tpl_vars['oneTemplate']->value['layout_ns'])) {?>

			<div class="card no-shadow mb-2 overflow-hidden">

				<div class="card-header">

					<h5 class="card-title mb-0">Layout chi tiết căn hộ</h5>

				</div>

				<div class="card-body position-relative p-0">

					<a class="w-100" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['oneTemplate']->value['layout_ns']);?>
" data-fancybox="true">

						<img class="img-fluid" src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['oneTemplate']->value['layout_ns']);?>
" />

					</a>

				</div>

			</div>

			<?php }?>

			<?php if (!empty($_smarty_tpl->tpl_vars['oneTemplate']->value['video'])) {?>

			<div class="card no-shadow mb-2 overflow-hidden">

				<div class="card-header">

					<h5 class="card-title mb-0">Video căn mẫu</h5>

				</div>

				<div class="card-body">

					<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

						<?php $_smarty_tpl->_assignInScope('frame_height', 200);?>

					<?php } else { ?>

						<?php $_smarty_tpl->_assignInScope('frame_height', 400);?>

					<?php }?>

					<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getEmbedVideo($_smarty_tpl->tpl_vars['oneTemplate']->value['video'],'100%',$_smarty_tpl->tpl_vars['frame_height']->value);?>


				</div>

			</div>

			<?php }?>

			<?php if (!empty($_smarty_tpl->tpl_vars['list_help_links']->value)) {?>

			<div class="card no-shadow d-none d-lg-block mb-2">

				<div class="card-header">

					<h5 class="card-title mb-0">Liên kết tiện ích</h5>

				</div>

				<div class="card-body">

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_help_links']->value, 'link');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['link']->value) {
?>

					<a class="mx-1" data-fancybox<?php if ($_smarty_tpl->tpl_vars['link']->value['is_driver'] == '1') {?> data-type="iframe"<?php }?> data-bs-toggle="tooltip" title="<?php echo $_smarty_tpl->tpl_vars['link']->value['title'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['link']->value['link'];?>
">&bull; <?php echo $_smarty_tpl->tpl_vars['link']->value['title'];?>
 <?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
</a>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				</div>

			</div>

			<?php }?>

			<div class="card no-shadow d-none d-lg-block mb-2">

				<div class="card-header d-flex align-items-center justify-content-between">

					<h5 class="card-title">Điểm nổi bật</h5>

					<button clsTable="StockMeta" onClick="$Core.tool.edit_stock_field(this,event)" p_field="content" p_id="<?php echo $_smarty_tpl->tpl_vars['stock_meta_id']->value;?>
" class="d-flex align-items-center btn btn-sm btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil',' Sửa');?>
</button>

				</div>

				<div class="card-body">

					<div class="tinyContent">

						<?php if (1 == 2) {?>

							<?php if (!empty($_smarty_tpl->tpl_vars['oneMeta']->value['content'])) {?>

								<?php echo $_smarty_tpl->tpl_vars['oneMeta']->value['content'];?>


							<?php } else { ?>

								<p class="my-0 text-center text-muted">Chưa có nội dung</p>

							<?php }?>

						<?php } else { ?>

							<?php if (!empty($_smarty_tpl->tpl_vars['list_stocks_meta']->value)) {?>

								<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_stocks_meta']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

									<?php echo $_smarty_tpl->tpl_vars['list_stocks_meta']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['content'];?>


									<p class="text-muted fs-12">

										<i class='bx bx-pen'></i>

										<?php echo $_smarty_tpl->tpl_vars['list_stocks_meta']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['author'];?>


									</p>

								<?php
}
}
?>

							<?php } else { ?>

								<p class="my-0 text-center text-muted">Chưa có nội dung</p>

							<?php }?>

						<?php }?>

					</div>

				</div>

			</div>

			<?php if (!empty($_smarty_tpl->tpl_vars['html_image_price_sheets']->value)) {?>

			<div class="card no-shadow mb-2">

				<div class="card-header">

					<h5 class="card-title mb-0">Phiếu tính giá</h5>

				</div>

				<div class="card-body">

					<div class="widget-content position-relative">

						<?php echo $_smarty_tpl->tpl_vars['html_image_price_sheets']->value;?>


					</div>

				</div>

			</div>

			<?php }?>

			<div class="card no-shadow mb-2">

				<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

				<div class="card-header d-flex align-items-center justify-content-between">

					<h5 class="card-title mb-0">Media</h5>

					<div class="button-groups d-flex">

						<button onClick="$Core.tool.add_stock_image(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" stock_meta_id="<?php echo $_smarty_tpl->tpl_vars['stock_meta_id']->value;?>
" class="btn btn-sm btn-outline-default mr-2"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-plus','Ảnh');?>
</button>

						<button onClick="$Core.tool.open_stock_video(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" stock_meta_id="<?php echo $_smarty_tpl->tpl_vars['stock_meta_id']->value;?>
" class="btn btn-sm btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-plus','Video');?>
</button>

					</div>

				</div>

				<div class="card-body">

					<form method="POST" class="d-none" enctype="multipart/form-data">

						<input type="hidden" name="is_multiple" value="1" />

						<input type="file" onchange="$Core.tool.upload_stock_image(this, event)" accept="image/*" multiple="multiple" id="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" stock_meta_id="<?php echo $_smarty_tpl->tpl_vars['stock_meta_id']->value;?>
" name="images[]" />

					</form>

					<div class="holder_stock_media_<?php echo $_smarty_tpl->tpl_vars['stock_meta_id']->value;?>
">

						<div class="loader p-2 text-center">Loading...</div>

					</div>

				</div>

			</div>

			<div class="card no-shadow mb-2">

				<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

				<div class="card-header d-flex justify-content-between align-items-center">

					<h5 class="card-title mb-0">File đính kèm</h5>

					<button onClick="$Core.tool.add_stock_file(this, event)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" stock_meta_id="<?php echo $_smarty_tpl->tpl_vars['stock_meta_id']->value;?>
" class="btn btn-sm btn-outline-default"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-plus','Thêm');?>
</button>

				</div>

				<div class="card-body">

					<form method="POST" class="d-none" enctype="multipart/form-data" action="">

						<input type="file" onchange="$Core.tool.upload_stock_file(this, event)" multiple="multiple" id="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" stock_meta_id="<?php echo $_smarty_tpl->tpl_vars['stock_meta_id']->value;?>
" name="attactments[]" />

					</form>

					<div class="holder_files_<?php echo $_smarty_tpl->tpl_vars['stock_meta_id']->value;?>
">

						<div class="loader p-2 text-center">Loading...</div>

					</div>

				</div>

			</div>

			<?php if (!empty($_smarty_tpl->tpl_vars['oneBuilding']->value['intro'])) {?>

			<div class="card no-shadow mb-2">

				<div class="card-header">

					<h5 class="card-title mb-0">Giới thiệu <?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
</h5>

				</div>

				<div class="card-body">

					<div class="tinyContennt">

						<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['intro'];?>


					</div>

				</div>

			</div>

			<?php }?>

			<div class="card no-shadow mb-2">

				<div class="card-body">

					<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('comment',array('table_id'=>$_smarty_tpl->tpl_vars['stock_id']->value,'clsTable'=>'Stock'));?>


				</div>

			</div>

		</div>

		<?php echo '<script'; ?>
 type="text/javascript"> 

			var stock_id = '<?php echo $_smarty_tpl->tpl_vars['stock_id']->value;?>
',

				stock_meta_id = '<?php echo $_smarty_tpl->tpl_vars['stock_meta_id']->value;?>
';

		<?php echo '</script'; ?>
>

		

		<?php echo '<script'; ?>
 type="text/javascript">

			$(function(){

				$Core.tool.load_stock_media(stock_meta_id, {});

				$Core.member.load_list_files(stock_meta_id, 'StockMeta', {});

				$Core.news.load_comments(stock_id, 'Stock', {'action' : 'reload'});

				$('.tinyContennt').readmore({speed: 75, maxHeight: 410});

			});

		<?php echo '</script'; ?>
>

		

	</div>

	<?php }?>

</div>





<?php }
}
