<?php
/* Smarty version 3.1.33, created on 2026-07-30 10:21:51
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/home/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ac34f3475a3_11670074',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f7158547c70847223281a2840f65bd406539eb57' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/home/default.tpl',
      1 => 1784300138,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ac34f3475a3_11670074 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">

<?php if ($_smarty_tpl->tpl_vars['message']->value == 'invalidlicense') {?>

<div class="errorbox"><strong><span class="title"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("Invalid License Module");?>
</span></strong><br></div>

<?php }?>

<?php if ($_smarty_tpl->tpl_vars['message']->value == 'modulenotactive') {?>

<div class="errorbox"><strong><span class="title"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("Module is not activated!");?>
</span></strong><br></div>

<?php }?>

<div class="container-fluid">

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title">

					Xin chào <?php echo $_smarty_tpl->tpl_vars['clsUser']->value->getFullName($_smarty_tpl->tpl_vars['_loged_id']->value);?>


				</h1>

			</div>

		</div>

	</div>

	<div class="wrap">

		<div class="form-row colorbox-group-widget">

			

			<div class="col-md-2 info-color-box">

				<div class="white-box">

					<div class="media bg-primary">

						<a href="<?php echo $_smarty_tpl->tpl_vars['DOMAIN_URL']->value;?>
?mod=stock&stock_type=178" class="d-block text-white text-decoration-hover-none">

							<div class="media-body">

								<h3 class="info-count"><?php echo $_smarty_tpl->tpl_vars['total_highlevel']->value;?>


									<span class="pull-right"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('building-o');?>
</span></h3>

								<p class="info-text font-12">Cao tầng</p>

								<div class="d-flex justify-content-end gap-2">

									<p class="info-ot font-12">Đã bán<span class="label label-rounded font-12"><?php echo $_smarty_tpl->tpl_vars['total_highlevel_sold']->value;?>
</span></p>

									<p class="info-ot font-12">Độc quyền<span class="label label-rounded font-12"><?php echo $_smarty_tpl->tpl_vars['total_highlevel_dq']->value;?>
</span></p>

								</div>

							</div>

						</a>

					</div>

				</div>

			</div>

			<div class="col-md-2 info-color-box">

				<div class="white-box">

					<div class="media bg-danger">

						<a href="<?php echo $_smarty_tpl->tpl_vars['DOMAIN_URL']->value;?>
?mod=stock&stock_type=177" class="d-block text-white text-decoration-hover-none">

							<div class="media-body">

								<h3 class="info-count"><?php echo $_smarty_tpl->tpl_vars['total_lowfloor']->value;?>
 

									<span class="pull-right"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('home');?>
</span></h3>

								<p class="info-text font-12">Thấp tầng</p>

								<div class="d-flex justify-content-end gap-2">

									<p class="info-ot font-12">Đã bán<span class="label label-rounded font-12"><?php echo $_smarty_tpl->tpl_vars['total_lowfloor_sold']->value;?>
</span></p>

									<p class="info-ot font-12">Độc quyền<span class="label label-rounded font-12"><?php echo $_smarty_tpl->tpl_vars['total_lowfloor_dq']->value;?>
</span></p>

								</div>

							</div>

						</a>

					</div>

				</div>

			</div>

			<div class="col-md-2 info-color-box">

				<div class="white-box">

					<div class="media bg-warning">

						<a href="<?php echo $_smarty_tpl->tpl_vars['DOMAIN_URL']->value;?>
?mod=sop" class="d-block text-white text-decoration-hover-none">

							<div class="media-body">

								<h3 class="info-count"><?php echo $_smarty_tpl->tpl_vars['clsSop']->value->countItem();?>
 

									<span class="pull-right"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('newspaper-o');?>
</span></h3>

								<p class="info-text font-12">Chuyển nhượng</p>

								<p class="info-ot font-12"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Total Pending');?>
<span class="label label-rounded font-12"><?php echo $_smarty_tpl->tpl_vars['clsSop']->value->countItem('is_online=0');?>
</span></p>

							</div>

						</a>

					</div>

				</div>

			</div>

			<div class="col-md-2 info-color-box">

				<div class="white-box">

					<div class="media bg-success">

						<a href="<?php echo $_smarty_tpl->tpl_vars['DOMAIN_URL']->value;?>
?mod=leasing" class="d-block text-white text-decoration-hover-none">

							<div class="media-body">

								<h3 class="info-count"><?php echo $_smarty_tpl->tpl_vars['clsLeasing']->value->countItem();?>
 

									<span class="pull-right"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('newspaper-o');?>
</span></h3>

								<p class="info-text font-12">Cho thuê</p>

								<p class="info-ot font-12"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Total Pending');?>
<span class="label label-rounded font-12"><?php echo $_smarty_tpl->tpl_vars['clsLeasing']->value->countItem('is_online=0');?>
</span></p>

							</div>

						</a>

					</div>

				</div>

			</div>

			<div class="col-md-2 info-color-box">

				<div class="white-box">

					<div class="media bg-primary">

						<a href="<?php echo $_smarty_tpl->tpl_vars['DOMAIN_URL']->value;?>
" class="d-block text-white text-decoration-hover-none">

							<div class="media-body">

								<h3 class="info-count"><?php echo $_smarty_tpl->tpl_vars['clsInterior']->value->countItem();?>
 

									<span class="pull-right"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('newspaper-o');?>
</span></h3>

								<p class="info-text font-12">Thiết kế</p>

								<p class="info-ot font-12">Yêu cầu phê duyệt<span class="label label-rounded font-12"><?php echo $_smarty_tpl->tpl_vars['clsInterior']->value->countItem("is_online=0");?>
</span></p>

							</div>

						</a>

					</div>

				</div>

			</div>

			<div class="col-md-2 info-color-box">

				<div class="white-box">

					<div class="media bg-success">

						<a href="<?php echo $_smarty_tpl->tpl_vars['DOMAIN_URL']->value;?>
?mod=service" class="d-block text-white text-decoration-hover-none">

							<div class="media-body">

								<h3 class="info-count"><?php echo $_smarty_tpl->tpl_vars['clsService']->value->countItem();?>
 

									<span class="pull-right"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('newspaper-o');?>
</span></h3>

								<p class="info-text font-12">Dịch vụ tiện ích</p>

								<p class="info-ot font-12">Yêu cầu phê duyệt<span class="label label-rounded font-12"><?php echo $_smarty_tpl->tpl_vars['clsService']->value->countItem('is_online=0');?>
</span></p>

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

								
							</tr></thead>

							<tbody id="lst_INTERIOR">

								

							</tbody>

						</table> 

					</div>

				</div>

			</div>

			
		</div>

	</div>

</div><?php }
}
