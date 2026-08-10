<?php
/* Smarty version 3.1.33, created on 2026-08-05 14:34:44
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/project.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72e794b58380_07602141',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd87735f18bdc78b9de9662208f16452a5f80f6cc' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/project.tpl',
      1 => 1785915278,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72e794b58380_07602141 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-sm flex-grow-1 container-p-y pt-2">	
	<div class="form-row">
		<div class="col-xxl-12 mx-auto">
			<div class="d-flex align-items-center justify-content-between mb-3">
				<h4 class="fw-bold mb-0 fs-20">Danh sách dự án</h4>
				<div class="dropdown">
					<a  class="btn bg-warning text-white js__dropdown-stock" href="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getLink('stock');?>
" >Bảng hàng dự án</a>
									</div>
			</div>
			<?php if (!empty($_smarty_tpl->tpl_vars['lstArea']->value)) {?>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstArea']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
					<?php if (!empty($_smarty_tpl->tpl_vars['arr_project_area']->value[$_smarty_tpl->tpl_vars['_oItem']->value['setting_id']])) {?>
						<div class="divider my-2">
							<div class="divider-text text-upper fs-2 fw-semibold text-main">
								<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
 <span class="fs-16">(<?php echo count($_smarty_tpl->tpl_vars['arr_project_area']->value[$_smarty_tpl->tpl_vars['_oItem']->value['setting_id']]);?>
 dự án)</span>
							</div>
						</div>
						<div class="form-row">
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_project_area']->value[$_smarty_tpl->tpl_vars['_oItem']->value['setting_id']], '_oProject');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProject']->value) {
?>
							<div class="col-12 col-lg-4 col-xl-4 col-xxxl-3 mb-2">
								<div class="card project-card h-100 no-shadow">
									<a class="d-block" href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['_oProject']->value['project_id'],0,0,'overview',$_smarty_tpl->tpl_vars['_oProject']->value);?>
" title="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
">
										<div class="position-relative text-white">
											<span class="position-absolute top-px-20 right-px-20 bg-success rounded-pill py-1 px-3 fs-12">Đang mở bán</span>
											<img decoding="async" class="card-img-top img-project img-fluid"  onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png'" src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->resize_image_url($_smarty_tpl->tpl_vars['_oProject']->value['image'],400,300);?>
" loading="lazy">
										</div>
										<div class="card-body">
											<div class="d-flex align-items-center justify-content-between gap-2">
												<div class="awe__project-info">
													<h4 class="card-title mb-2" title="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
" >
														<span class="text-dark text-fs-22 fw-semibold limit_1line"><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
</span>
													</h4>
													<p class="text-dark mb-1 text-fs-13 limit_2line" title="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['address'];?>
" >
														<i class='bx bx-map'></i> <?php echo $_smarty_tpl->tpl_vars['_oProject']->value['address'];?>

													</p>
													<div class="mb-1 text-fs-13 text-muted limit_2line" title="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['apartment'];?>
" >
														<i class='bx bx-home-alt'></i> Quy mô: <?php echo $_smarty_tpl->tpl_vars['_oProject']->value['apartment'];?>

													</div>
													<div class="text-fs-13 align-items-center text-muted limit_1line" title="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['arcreage'];?>
" >
														<i class='bx bx-code'></i> Diện tích: <?php echo $_smarty_tpl->tpl_vars['_oProject']->value['arcreage'];?>

													</div>	
												</div>
												<div class="awe__project-icon d-none d-lg-block">
													<img class="img-fluid h-px-50" src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->resize_image_url($_smarty_tpl->tpl_vars['_oProject']->value['logo'],0,50);?>
"  onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png'" loading="lazy" />
												</div>
											</div>	
										</div>
									</a>
								</div>
							</div>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</div>
					<?php }?>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			<?php }?>
		</div>
	</div>
</div>
<?php }
}
