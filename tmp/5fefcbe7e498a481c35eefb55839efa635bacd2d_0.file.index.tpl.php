<?php
/* Smarty version 3.1.33, created on 2026-08-07 11:55:35
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/comment/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a756547d48523_25120613',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5fefcbe7e498a481c35eefb55839efa635bacd2d' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/comment/index.tpl',
      1 => 1785927032,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a756547d48523_25120613 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="awe__post-comment<?php if ($_smarty_tpl->tpl_vars['clsTable']->value == 'News') {?> awe__news-comment<?php }?>">
	<?php if ($_smarty_tpl->tpl_vars['clsTable']->value != 'Post' && $_smarty_tpl->tpl_vars['clsTable']->value != 'News' && $_smarty_tpl->tpl_vars['clsTable']->value != 'Share') {?>
	<div class="d-flex align-items-center mb-3">
		<h3 class="text-fs-16 oHHgwgcTnX m-0">Bình luận <span class="total_comments">0</span></h3>
	</div>
	<?php }?>
	<?php if ($_smarty_tpl->tpl_vars['clsTable']->value != 'News') {?>
		<div class="awe__comment-form">
			<form method="post" class="d-flex align-items-center" enctype="multipart/form-data">
				<div class="d-flex align-items-center w-100">
					<div class="awe__profile-avatar">
						<img class="rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value);?>
" 
						width="30px" height="30px" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" />
					</div>
					<div class="awe__comment-textarea">
						<div class="awe__comment-input rounded-1 position-relative">
							<div class="w-100">
								<textarea onkeyup="$Core.news.fireEvent(this, event)" class="form-control no-focus redactor_editor autosize height-not-auto rounded-1 textarea-emoji" name="message" rows="2" placeholder="Viết bình luận"></textarea>
							</div>
							<div class="awe__comment-button">
								<input type="hidden" name="parent_id" value="0" />
								<input type="hidden" name="submit" value="comment" />
								<input type="hidden" name="table_id" value="<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" />
								<input type="hidden" name="clsTable" value="<?php echo $_smarty_tpl->tpl_vars['clsTable']->value;?>
" />
								<?php if ($_smarty_tpl->tpl_vars['clsTable']->value == 'Course') {?>
								<label for="attach-doc" class="form-label mb-0">
									<i class="bx bx-paperclip bx-sm cursor-pointer mx-1 text-body"></i>
									<input type="file" id="attach-doc" name="image" onChange="$Core.news.add_comment(this, event)" holderG="_comment" comment_id="0" class="d-none">
								</label>
								<?php }?>
								<a href="javascript:void(0)" onClick="$Core.news.add_comment(this, event)" type="text" class="js-add-comment awe__comment-action disabled" comment_id="0" holderG="_comment"><svg fill="#CCC" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="20"><path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg></a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	<?php }?>
	<div class="clearfix"></div>
	<div class="holder_comments_<?php echo $_smarty_tpl->tpl_vars['table_id']->value;
if (isset($_smarty_tpl->tpl_vars['autoload']->value) && $_smarty_tpl->tpl_vars['autoload']->value == '1') {?> awe__comment-autoload<?php }?> comments-list mt-3" table_id="<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" clsTable="<?php echo $_smarty_tpl->tpl_vars['clsTable']->value;?>
" >
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_preloaders']->value, '_oItem');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
		<div class="awe__comment-item mb-2">
			<div class="d-flex gap-2 w-100 align-items-start">
				<div class="avatar animate-bg rounded-pill"></div>
				<div class="bAxZCbpcyR w-100">
					<div class="animate-bg w-50 h-px-15 rounded-2 mb-2"></div>
					<div class="animate-bg w-100 h-px-15 rounded-2"></div>
				</div>
			</div>
		</div>
		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	</div>
</div>
<?php echo '<script'; ?>
 type="text/javascript">
	var _LANG_ID = '<?php echo $_smarty_tpl->tpl_vars['_LANG_ID']->value;?>
',
		table_id = '<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
',
		clsTable = '<?php echo $_smarty_tpl->tpl_vars['clsTable']->value;?>
';
<?php echo '</script'; ?>
><?php }
}
