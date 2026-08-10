<?php
/* Smarty version 3.1.33, created on 2026-08-07 09:12:45
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.list_reply.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a753f1da22f66_81744726',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '11aa5ca74e3fe58cff48445fae5b7d560e49f3c1' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.list_reply.tpl',
      1 => 1785558502,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a753f1da22f66_81744726 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['list_reply']->value) {?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_reply']->value, '_oComment', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oComment']->value) {
?>
	<?php $_smarty_tpl->_assignInScope('_comment_id', $_smarty_tpl->tpl_vars['_oComment']->value['comment_id']);?>
	<div class="awe__comment-item awe__comment-reply-group-<?php echo $_smarty_tpl->tpl_vars['comment_id']->value;?>
">
		<div class="d-flex w-100">
			<div class="awe__profile-avatar" data-url="/index.php?mod=home&act=load_profile_popover&user_id=<?php echo $_smarty_tpl->tpl_vars['_oComment']->value['profile_id'];?>
" data-toggle="webui-popover" data-trigger="hover" data-placement="top" data-width="350">
				<img class="rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['_oComment']->value['db_profile']['avatar'];?>
" width="30" height="30" />
			</div>
			<div class="awe__comment-item-body"> 
				<div class="bg-lighter p-2 rounded-2">
					<div class="awe__comment-profile d-flex align-items-center gap-1 mb-1">
						<h4 class="awe__comment-name mb-0 text-bold fs-14"><?php echo $_smarty_tpl->tpl_vars['_oComment']->value['db_profile']['name'];?>
</h4>
						<span class="awe__comment-time text-muted fs-11"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getTimeAgo($_smarty_tpl->tpl_vars['_oComment']->value['reg_date']);?>
</span>
					</div>
					<div class="awe__comment-content">
						<?php echo html_entity_decode($_smarty_tpl->tpl_vars['_oComment']->value['content']);?>

					</div>
				</div>
				<div class="awe__comment-item-action d-flex align-items-center">
					<a href="javascript:void(0);" class="control-action <?php echo $_smarty_tpl->tpl_vars['_oComment']->value['liked'];?>
" onclick="$Core.comment.like(this, event)" comment_id="<?php echo $_smarty_tpl->tpl_vars['_comment_id']->value;?>
" data-name="like" ><i class="bx bx-like me-1" style="<?php if ($_smarty_tpl->tpl_vars['_oComment']->value['liked']) {?>color:#e24b4a<?php }?>"></i><span class="awe__comment-total-liked"><?php echo $_smarty_tpl->tpl_vars['clsComment']->value->genTotalLike($_smarty_tpl->tpl_vars['_comment_id']->value);?>
</span></a>	
					</a>
				</div>
			</div>
		</div>
	</div>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php if ($_smarty_tpl->tpl_vars['total_page']->value > $_smarty_tpl->tpl_vars['page']->value) {?>
	<div class="d-flex justify-content-center mt10">
		<a href="javascript:void(0);" rel="nofollow" page="<?php echo $_smarty_tpl->tpl_vars['page']->value+1;?>
" class="btn-more show_more_review">
			<span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Show more comments');?>
</span>
		</a>
	</div>  
	<?php }
}
}
}
