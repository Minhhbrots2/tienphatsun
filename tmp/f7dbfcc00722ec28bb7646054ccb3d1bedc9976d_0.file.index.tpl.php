<?php
/* Smarty version 3.1.33, created on 2026-08-07 11:36:22
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/news_category/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7560c607bf12_84549479',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f7dbfcc00722ec28bb7646054ccb3d1bedc9976d' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/news_category/index.tpl',
      1 => 1786077330,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7560c607bf12_84549479 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="awe__profile menu_news_cat <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>bg-white p-3<?php }?> radius-4">
    <ul class="awe__post-menu">
		<li class="awe__post-menu-item d-flex align-items-center justify-content-between <?php if (empty($_smarty_tpl->tpl_vars['cat_id']->value)) {?>active<?php } else { ?>text-black<?php }?>">
			<a href="/ban-tin.html" title="Tất cả">Tất cả</a> 
			<span class="text-muted"><?php echo $_smarty_tpl->tpl_vars['total_records']->value;?>
</span> 
		</li>
		<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_category']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
		<li class="awe__post-menu-item d-flex align-items-center justify-content-between <?php if ($_smarty_tpl->tpl_vars['cat_id']->value == $_smarty_tpl->tpl_vars['list_category']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['property_id']) {?>active<?php } else { ?>text-black<?php }?>">
			<a href="<?php echo $_smarty_tpl->tpl_vars['list_category']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['list_category']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title'];?>
"><?php echo $_smarty_tpl->tpl_vars['list_category']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title'];?>
</a> 
			<span class="text-muted"><?php echo $_smarty_tpl->tpl_vars['list_category']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['total_records_in'];?>
</span> 
		</li>
		<?php
}
}
?>
    </ul>
</div><?php }
}
