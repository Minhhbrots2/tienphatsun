<?php
/* Smarty version 3.1.33, created on 2026-08-10 11:21:31
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.top_rank_25.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7951cbc9c996_10690940',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c36f479fce41672bcb0dd056fcfb6eae4954b517' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/ajax/helper/_ajax.top_rank_25.tpl',
      1 => 1785930754,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7951cbc9c996_10690940 (Smarty_Internal_Template $_smarty_tpl) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstItem']->value, '_oItem', false, 'key', 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

	<?php $_smarty_tpl->_assignInScope('_oneStaff', $_smarty_tpl->tpl_vars['_oItem']->value['oneStaff']);?>

	<div class="item_ranker flex-flow position-relative rounded-1<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['is_none'])) {?> is_none<?php }?>" style="height:calc(<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['col_height'];?>
% + 60px); background-color:<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['bgcolor'];?>
;" title="<?php echo $_smarty_tpl->tpl_vars['_oneStaff']->value['full_name'];?>
" profile_id="<?php echo $_smarty_tpl->tpl_vars['_oneStaff']->value['profile_id'];?>
" >

		<div class="box_avt position-absolute d-flex align-items-center justify-content-center cursor-pointer" style="background:url('<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['bg_avt'];?>
'); background-size:100%; background-position:bottom; background-repeat:no-repeat;<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?> width:calc(100% * 1.2)<?php }?>">

			<img class="rounded-pill w-100 object-fit-cover" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oneStaff']->value['profile_id'],$_smarty_tpl->tpl_vars['_oneStaff']->value,100,0);?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" 

				alt="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullname($_smarty_tpl->tpl_vars['_oItem']->value['profile_id'],$_smarty_tpl->tpl_vars['_oneStaff']->value);?>
" width="30" height="30">

		</div>

		<div class="text-center fw-bold d-flex flex-column info_ranker">

			<span class="text-warning lh-xs"><?php echo $_smarty_tpl->tpl_vars['_oneStaff']->value['department_name'];?>
</span>

			<span class="text-main"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_price'];?>
</span>

		</div>

	</div>

<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
