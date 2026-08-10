<?php
/* Smarty version 3.1.33, created on 2026-08-07 09:12:41
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.form.comment.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a753f19ba23c8_64542507',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'cac619eedd263891e134102bf90964a5a020b708' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.form.comment.tpl',
      1 => 1784299652,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a753f19ba23c8_64542507 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="awe__comment-reply mt-3">
	<form method="post" class="d-flex align-items-center" enctype="multipart/form-data">
		<div class="awe__comment-form d-flex w-100">
			<div class="awe__profile-avatar">
				<img class="rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value);?>
" width="30px" height="30px" />
			</div>
			<div class="awe__comment-textarea">
				<div class="awe__comment-input rounded-2 position-relative">
					<textarea onkeyup="$Core.news.fireEvent(this, event)" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="form-control textarea-emoji no-focus redactor_editor autosize height-not-auto rounded-2" rows="2" name="message" lang="<?php echo $_smarty_tpl->tpl_vars['_LANG_ID']->value;?>
" placeholder="Viết bình luận"></textarea>
					<div class="awe__comment-button">
						<input type="hidden" name="submit" value="comment" />
						<input type="hidden" name="parent_id" value="<?php echo $_smarty_tpl->tpl_vars['parent_id']->value;?>
" />
						<input type="hidden" name="table_id" value="<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" />
						<input type="hidden" name="clsTable" value="<?php echo $_smarty_tpl->tpl_vars['clsTable']->value;?>
" />
						<?php if ($_smarty_tpl->tpl_vars['clsTable']->value == 'Course') {?>
							<label for="attach-doc_rep_<?php echo $_smarty_tpl->tpl_vars['parent_id']->value;?>
" class="form-label mb-0">
								<i class="bx bx-paperclip bx-sm cursor-pointer mx-3 text-body"></i>
								<input type="file" id="attach-doc_rep_<?php echo $_smarty_tpl->tpl_vars['parent_id']->value;?>
" name="image" hidden="" onChange="$Core.news.add_comment(this, event)" holderG="_reply" comment_id="<?php echo $_smarty_tpl->tpl_vars['parent_id']->value;?>
">
							</label>
						<?php }?>
						<a onClick="$Core.news.add_comment(this, event)" comment_id="<?php echo $_smarty_tpl->tpl_vars['parent_id']->value;?>
" holderG="_reply" class="js-add-comment awe__comment-action  disabled" href="javascript:void(0)"><svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="20"><path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg></a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div><?php }
}
