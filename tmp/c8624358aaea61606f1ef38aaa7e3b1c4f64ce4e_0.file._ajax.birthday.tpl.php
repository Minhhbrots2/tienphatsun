<?php
/* Smarty version 3.1.33, created on 2026-08-07 10:21:31
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.birthday.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a754f3bf21035_01431790',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c8624358aaea61606f1ef38aaa7e3b1c4f64ce4e' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.birthday.tpl',
      1 => 1784299652,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a754f3bf21035_01431790 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.capitalize.php','function'=>'smarty_modifier_capitalize',),1=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
if (!empty($_smarty_tpl->tpl_vars['list_staffs']->value)) {?>
<ul id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="p-0 max-height-200 m-0">
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_staffs']->value, '_oStaff', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oStaff']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>
	<li class="d-flex gap-2 align-items-center cursor-pointer<?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?> border-bottom<?php }?> pb-2 mb-2">
		<div data-url="/index.php?mod=home&act=load_profile_popover&user_id=<?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'];?>
" data-toggle="webui-popover" data-trigger="click" data-width="300">
			<img class="avatar avatar-xs rounded-circle" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'],$_smarty_tpl->tpl_vars['_oStaff']->value,80,80);?>
" />
		</div>
		<div class="w-100">
			<h6 class="mb-1"><?php echo smarty_modifier_capitalize($_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['_oStaff']->value['profile_id'],$_smarty_tpl->tpl_vars['_oStaff']->value));?>
</h6>
			<small class="text-muted d-block">
				<span><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['_oStaff']->value['department_id']);?>
</span>
				<span>&bull; <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['_oStaff']->value['birthday'],"%d/%m/%Y");?>
 &bull; <?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['age'];?>
 tuổi &bull; 
				<span class="text-main"><?php echo $_smarty_tpl->tpl_vars['_oStaff']->value['days_to_birth'];?>
</span></span>
			</small>
		</div>
	</li>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</ul>
<?php } else { ?>
    <div class="p-3 bg-lighter rounded-3">
        <div class="text-center">Không có ai sinh nhật</div>
    </div>
<?php }
}
}
