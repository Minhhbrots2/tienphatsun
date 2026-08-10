<?php
/* Smarty version 3.1.33, created on 2026-08-05 16:15:05
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/request_ptg/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72ff190e0019_39121704',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8e95ce20ef1db6dec632a02a0fad868cb459654e' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/request_ptg/default.tpl',
      1 => 1784299674,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72ff190e0019_39121704 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-xxl flex-grow-1 container-p-y pt-2">

	<div class="d-flex flex-wrap justify-content-between align-items-center">

		<div class="nvzAnQLQxE mb-3">

			<h4 class="fw-bold mb-1"><span>Danh sách yêu cầu PTG</span></h4>

			<span class="text-muted">Có tổng cộng <strong>(<?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
)</strong> yêu cầu</span>

		</div>

		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == "phone") {?>

		<div class="dropdown">

			<button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" 

				aria-haspopup="true" aria-expanded="false">Chọn</button>

			<ul class="dropdown-menu dropdown-scrollable scroll-y-auto-hover">

				<li class="dropdown-item">

					<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('request_ptg');?>
" class="dropdown-link <?php if ($_smarty_tpl->tpl_vars['status']->value == '') {?>text-primary<?php } else { ?>text-dark<?php }?>">Tất cả (<?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
)</a>

				</li>

				<li class="dropdown-item">

					<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('request_ptg');?>
?status=0" class="dropdown-link <?php if ($_smarty_tpl->tpl_vars['status']->value == '0') {?>text-primary<?php } else { ?>text-dark<?php }?>">Đang chờ (<?php echo $_smarty_tpl->tpl_vars['total_pendding']->value;?>
)</a>

				</li>

				<li class="dropdown-item">

					<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('request_ptg');?>
?status=1" class="dropdown-link <?php if ($_smarty_tpl->tpl_vars['status']->value == '1') {?>text-primary<?php } else { ?>text-dark<?php }?>">Đã cập nhật (<?php echo $_smarty_tpl->tpl_vars['total_success']->value;?>
)</a>

				</li>

			</ul>

		</div>

		<?php } else { ?>

		<div class="d-flex align-items-center gap-1">

			<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('request_ptg');?>
" class="btn <?php if ($_smarty_tpl->tpl_vars['status']->value == '') {?>btn-primary<?php } else { ?>btn-outline-default<?php }?>">Tất cả (<?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
)</a>

			<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('request_ptg');?>
?status=0" class="btn <?php if ($_smarty_tpl->tpl_vars['status']->value == '0') {?>btn-primary<?php } else { ?>btn-outline-default<?php }?>">Đang chờ (<?php echo $_smarty_tpl->tpl_vars['total_pendding']->value;?>
)</a>

			<a href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('request_ptg');?>
?status=1" class="btn <?php if ($_smarty_tpl->tpl_vars['status']->value == '1') {?>btn-primary<?php } else { ?>btn-outline-default<?php }?>">Đã cập nhật (<?php echo $_smarty_tpl->tpl_vars['total_success']->value;?>
)</a>

		</div>

		<?php }?>

	</div>

	<div class="form-row">

		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">

			<div class="box_item_statistic h-100 p-3 bg-danger text-white rounded-2"> 

				<p class="title_statistic fs-6 mb-2">Tổng số yêu cầu</p>

				<div class="number_total fs-3"><?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
<span class="fs-14 ml-1">yêu cầu</span></div>

			</div>

		</div>

		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">

			<div class="box_item_statistic h-100 p-3 text-white rounded-2" style="background:#eba000"> 

				<p class="title_statistic fs-6 mb-2">Đang chờ</p>

				<div class="number_total fs-3"><?php echo $_smarty_tpl->tpl_vars['total_pendding']->value;?>
<span class="fs-14 ml-1">yêu cầu</span></div>

			</div>

		</div>

		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">

			<div class="box_item_statistic h-100 p-3 text-white rounded-2" style="background:#1d6a01"> 

				<p class="title_statistic fs-6 mb-2">Đã phản hồi</p>

				<div class="number_total fs-3"><?php echo $_smarty_tpl->tpl_vars['total_success']->value;?>
<span class="fs-14 ml-1">yêu cầu</span></div>

			</div>

		</div>

		<div class="col-6 col-md-3 col-lg-3 flex-fill mb-2">

			<div class="box_item_statistic h-100 p-3 text-white rounded-2 bg-primary"> 

				<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

				<p class="title_statistic fs-6 mb-2">T.Gian trung bình</p>

				<?php } else { ?>

				<p class="title_statistic fs-6 mb-2">Thời gian trung bình</p>

				<?php }?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_ranges']->value, '_oR');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oR']->value) {
?>

				<div class="d-flex mb-0 align-items-center justify-content-between">

					<span><?php echo $_smarty_tpl->tpl_vars['_oR']->value['title'];?>
</span>

					<span><?php echo $_smarty_tpl->tpl_vars['_oR']->value['avg_time'];?>
</span>

				</div>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			</div>

		</div>

	</div>

	<div class="card">

		<div class="card-body">

			<div class="table-container overflow-x-auto text-nowrap no-shadow table-sticky-last">

				<table class="table dragable" cellpadding="0" cellspacing="0" 

					   style="width:<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>calc(100% + 100px)<?php } else { ?>100%<?php }?>">

					<thead><tr>

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

						<th width="3%" class="align-center bg-lighter h-px-40 text-center">No.</th>

						<?php }?>

						<th width="100px" class="align-center bg-lighter h-px-40 text-left">Mã căn</th>

						<th width="" class="align-center h-px-40 bg-lighter text-left">Người yêu cầu</th>

						<th class="align-center h-px-40 bg-lighter border-right" width="150px">Thời gian gửi</th>

						<th class="align-center h-px-40 bg-lighter border-right" width="200px">Nội dung</th>

						<th width="" class="align-center bg-lighter h-px-40 text-left">Cập nhật bởi</th>

						<th class="align-center bg-lighter h-px-40 border-right" width="150px">Thời gian cập nhật</th>

						<th class="align-center bg-lighter h-px-40 text-center" width="150px">Trạng thái</th>

						<th width="100px" class="align-center bg-lighter h-px-40 text-align-center"></th>

					</tr></thead>

					<tbody class="holder_stocks">

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_requests']->value, '_oItem', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>

						<tr>

							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

							<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>

							<?php }?>

							<td class="text-left">

								<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['is_urgent'] == '1') {?>

								<span class="label bg-label-danger">GẤP</span>

								<?php }?>

								<a href="javascript:void(0)" <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>onClick="$Core.helper.open_stock('<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
');" <?php } else { ?>data-url="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=home&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" data-toggle="webui-popover" data-trigger="click" data-placement="auto"<?php }?> data-width="350"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['ms_code'];?>
</a>

								<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone' && $_smarty_tpl->tpl_vars['_oItem']->value['status_id'] == '0') {?>

								<div class="btn-group ml-1">

									<button data-toggle="ripple" type="button" onclick="$Core.helper.open_quick_stock(this,event)" tp="sheet_price" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" class="btn btn-sm btn-icon btn-outline-default text-nowrap" from="request_ptg"><i class="fa fa-plus"></i> </button>

									<button data-toggle="ripple" type="button" onclick="$Core.helper.soldout_stock(this,event)" tp="sheet_price" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" request_ptg_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['id'];?>
" class="btn btn-sm btn-icon btn-outline-danger text-nowrap" from="request_ptg"><i class="bx bx-x"></i></button>

									<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['price_sheets'])) {?>

										<button data-toggle="ripple" type="button" onclick="$Core.helper.exist_ptg(this,event)" tp="sheet_price" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" request_ptg_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['id'];?>
" class="btn btn-sm btn-icon btn-outline-danger text-nowrap" from="request_ptg"><i class='bx bx-circle'></i></button>

									<?php }?>

								</div>

								<?php }?>

								<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['status_id'] == '1' && !empty($_smarty_tpl->tpl_vars['_oItem']->value['time_feedback'])) {?>

									(<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getTimeHtml($_smarty_tpl->tpl_vars['_oItem']->value['time_feedback']);?>
)

								<?php } else { ?>

									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getTimeHtml($_smarty_tpl->tpl_vars['_oItem']->value['time_waiting'],"rồi");?>


								<?php }?>

							</td>

							<td class="text-nowrap"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['staff_name'];?>
</td>

							<td class="border-right"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oItem']->value['reg_date'],4);?>
</td>

							<td class="border-right"><?php echo preg_replace('!<[^>]*?>!', ' ', html_entity_decode($_smarty_tpl->tpl_vars['_oItem']->value['notes']));?>
</td>

							<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['user_updated_id'])) {?>

								<td class="text-nowrap"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['_oItem']->value['user_updated_id']);?>
</td>

							<?php } else { ?>

								<td>--</td>

							<?php }?>

							<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['upd_date'])) {?>

								<td class="text-nowrap"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oItem']->value['upd_date'],4);?>
</td>

							<?php } else { ?>

								<td class="text-left">--</td>

							<?php }?>

							<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['status_id'] == '0') {?>

								<td class="text-warning text-center">Đang chờ</td>

								<td class="text-center">

									<div class="btn-group">

										<button data-toggle="ripple" type="button" onclick="$Core.helper.open_quick_stock(this,event)" tp="sheet_price" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" class="btn btn-sm btn-outline-default text-nowrap" from="request_ptg"><i class="fa fa-plus"></i> PTG</button>

										<button data-toggle="ripple" type="button" onclick="$Core.helper.soldout_stock(this,event)" tp="sheet_price" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" request_ptg_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['id'];?>
" class="btn btn-sm btn-danger text-nowrap" from="request_ptg"><i class="bx bx-x"></i> Đã bán</button>

										<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['price_sheets'])) {?>

											<button data-toggle="ripple" type="button" onclick="$Core.helper.exist_ptg(this,event)" tp="sheet_price" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" request_ptg_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['id'];?>
" class="btn btn-sm btn-success text-nowrap" from="request_ptg"><i class='bx bx-circle'></i> Đã có</button>

										<?php }?>

									</div>

								</td>

							<?php } else { ?>

								<td class="text-success text-center">Đã có</td>

								<td class="text-center">--</td>

							<?php }?>										

						</tr>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					</tbody>

				</table>

			</div>			

			<?php if (!empty($_smarty_tpl->tpl_vars['html_pager']->value)) {?>

				<div class="pagination justify-content-center mt-4 flex-wrap" style="row-gap: 3px"><?php echo $_smarty_tpl->tpl_vars['html_pager']->value;?>
</div>

			<?php }?>

		</div>

	</div>

</div>



<style type="text/css">

@media screen and (min-width : 576px){

	.table-container .table tr th:nth-child(2),

	.table-container .table tr td:nth-child(2){

		z-index:2;

		position:sticky;

		left:45px; top:0;

	}

	.table-container .table tr th:nth-child(2){

		background:#F5F7F8 !important;

	}

	.table-container .table tr td:nth-child(2){

		background:var(--bs-white);

	}

}

	

.zalo_chat{

	display:inline-block;

	width:18px; 

	height:18px;

	border-radius:3px;

	-moz-border-radius:3px;

	-webkit-border-radius:3px;

	-khtml-border-radius:3px;

	background:#03a5fa url("/application/themes/images/logo_white_s_40.png") no-repeat center center;

	background-size:13px;

	transform: translate(5px, 5px);

	-moz-transform: translate(5px, 5px);

	-webkit-transform: translate(5px, 5px);

	-khtml-transform: translate(5px, 5px);

}

</style>

<?php }
}
