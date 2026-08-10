<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:20:31
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.report_top_share.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76926f7f07d9_68401658',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '15bc4fa470baf6b70c885f4f443b5831644e0fe5' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/report/_ajax.report_top_share.tpl',
      1 => 1784299673,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76926f7f07d9_68401658 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['lst_staff_share']->value)) {?>

<div class="card h-100 mb-2">

	<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

	<div class="card-header d-flex align-items-center justify-content-between">

		<h5 class="card-title m-0 me-2">Top 10 sale tiếp khách</h5>

		<a><i class="bx bx-help-circle"></i></a>

	</div>

	<div class="card-body">

		<ul class="p-0 m-0">

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_staff_share']->value, '_oItem', false, 'key', 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

				<li class="d-flex <?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?>pb-2<?php }?>">

					<div class="avatar mt-1 avatar-sm position-relative flex-shrink-0 me-2" data-trigger="hover" data-width="300" data-url="/index.php?mod=home&act=load_profile_popover&user_id=<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['profile_id'];?>
" data-toggle="webui-popover" >

						<img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oItem']->value['profile_id'],$_smarty_tpl->tpl_vars['_oItem']->value,40,40);?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" alt="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['full_name'];?>
" class="rounded-pill" />

						<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->get_icon_verified($_smarty_tpl->tpl_vars['_oItem']->value['profile_id'],$_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>


					</div>

					<div class="w-100">

						<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">

							<small class="text-muted d-block"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['code'];?>
-<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['department_name'];?>
</small>

							<div class="user-progress d-flex align-items-center gap-1">

								<h6 class="mb-0 text-main"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_share'];?>
 lượt</h6>

							</div>

						</div>

						<h6 class="mb-0"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['full_name'];?>
</h6>

					</div>

				</li>

				<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null) == 10) {
break 1;
}?>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		</ul>

	</div>

</div>

<?php }?>

<?php if (!empty($_smarty_tpl->tpl_vars['list_dep_area']->value)) {?>

<div class="card h-100 mb-2">

	<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

	<div class="card-header d-flex align-items-center justify-content-between">

		<h5 class="card-title m-0 me-2">Top vùng kinh doanh tiếp khách</h5>

		<a><i class="bx bx-help-circle"></i></a>

	</div>

	<div class="card-body">

		<div class="mb-3">

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_dep_area']->value, '_oArea', false, 'key', 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oArea']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

				<div class="d-flex gap-2 align-items-center w-100 mb-1">

					<span class="fs-14 text-right w-px-75 text-nowrap"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->replace($_smarty_tpl->tpl_vars['_oArea']->value['title'],'kinh doanh','');?>
</span>

					<div class="d-flex flex-column" style="width:calc(100% - 150px)">

						<div class="progress w-100" style="height:12px;">

						  <div class="progress-bar bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" 

							style="width:<?php echo $_smarty_tpl->tpl_vars['_oArea']->value['total_share_area']*100/$_smarty_tpl->tpl_vars['total_share']->value;?>
%;"></div>

						</div>

					</div>

					<span class="fs-12 text-nowrap w-px-75 text-right"><strong class="text-fs-18 text-main"><?php echo $_smarty_tpl->tpl_vars['_oArea']->value['total_share_area'];?>
</strong> lượt</span>

				</div>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		</div>

	</div>

</div>

<?php }
}
}
