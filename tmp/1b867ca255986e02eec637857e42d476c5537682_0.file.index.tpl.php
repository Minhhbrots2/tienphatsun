<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:51:33
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/item_doc/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315b50a80e6_85387436',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1b867ca255986e02eec637857e42d476c5537682' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/item_doc/index.tpl',
      1 => 1785927041,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315b50a80e6_85387436 (Smarty_Internal_Template $_smarty_tpl) {
?>

<?php if ($_smarty_tpl->tpl_vars['_type']->value == "detail") {?>

	<?php $_smarty_tpl->_assignInScope('_list_images', $_smarty_tpl->tpl_vars['oneItem']->value['list_images']);?>

	<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['oneItem']->value['more_information']);?>

	<div class="awe__doc-item position-relative">

		<a href="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['link_download'];?>
" class="btn btn-default bg-white text-dark btn-sm p-1 position-absolute zindex-1" target="_blank" <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>style="top:15px;right: 15px"<?php } else { ?>style="top:8px;right: 8px"<?php }?>>

			<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'folder' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'youtu.be' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'docs.google.com' || $_smarty_tpl->tpl_vars['_more_information']->value['file_type'] == 'folder' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'user.fh') {?>

				<i class='bx bx-link-external'></i>

			<?php } else { ?>

				<i class="bx bx-download"></i>

			<?php }?>

		</a>

		<a href="javascript:void(0)" onClick="$Core.project.save_docs(this,event)" sheet_id="<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['gg_id'];?>
" docs_id="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['id'];?>
" class="awe__doc-button awe__doc-save rounded-pill d-flex justify-content-center align-items-center d-none <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_more_information']->value['gg_id'],$_smarty_tpl->tpl_vars['arr_docs_save']->value)) {?> saved<?php }?>"><i class="fs-16 bx bx-heart bx-sm"></i></a>

		<a class="link awe__doc-link overflow-hidden rounded-3" data-preload="false" data-caption="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
" 

		   <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'youtu.be') {?>

				data-fancybox="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['id'];?>
" href="https://www.youtube.com/watch?v=<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['youtu_id'];?>
"

		   <?php } elseif ($_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'google.file') {?>

				data-fancybox="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['id'];?>
"

			   <?php if (!empty($_smarty_tpl->tpl_vars['_list_images']->value)) {?> 

					data-src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->genGoogleURL($_smarty_tpl->tpl_vars['_more_information']->value['gg_id']);?>
"

			   <?php } else { ?> 

					href="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['link'];?>
"

			   <?php }?>

		   <?php } elseif ($_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'video' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'pdf' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'other' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'docx' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'docs.google.com') {?> 

				data-fancybox="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['id'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['link'];?>
" data-type="iframe"

		   <?php } else { ?>

				href="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['content'];?>
" target="_blank"

		   <?php }?>>

			<div class="awe__doc-img position-relative">

				<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'video' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'youtu.be') {?>

				<div class="img-play"><i class="bx bx-play"></i></div><?php }?>

				<div class="img-background" style="background-image:url('<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['image'];?>
'),url('<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png')"></div>

			</div>

			<?php if (!empty($_smarty_tpl->tpl_vars['oneItem']->value['image'])) {?>

				<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['oneItem']->value['image'],150);?>
" alt="" class="d-none" loading="lazy">

			<?php }?>

			<h4 class="fs-13 line-clamp-2 text-center text-white lh-sm mb-0"><span class="limit_2line"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
</span></h4>

		</a>

		<?php if (!empty($_smarty_tpl->tpl_vars['_list_images']->value)) {?>

			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_list_images']->value, '_oImage');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oImage']->value) {
?>

				<a class="d-none" data-fancybox="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['id'];?>
"<?php if ($_smarty_tpl->tpl_vars['_oImage']->value['type'] == 'video' || $_smarty_tpl->tpl_vars['_oImage']->value['type'] == 'pdf' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'other' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'docx' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'docs.google.com') {?> data-type="iframe"<?php }?> data-src="<?php echo $_smarty_tpl->tpl_vars['_oImage']->value['image'];?>
" data-caption="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title'];?>
">

					<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_oImage']->value['image'],150);?>
" alt="" class="d-none" loading="lazy">

				</a>

			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

		<?php }?>

	</div> 

<?php } else { ?>

	<?php $_smarty_tpl->_assignInScope('_list_images', $_smarty_tpl->tpl_vars['oneItem']->value['list_images']);?>

	<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['oneItem']->value['more_information']);?>

	<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>

	<div class="item_document position-relative rounded-3 mb-2 overflow-hidden border <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>p-3<?php } else { ?>p-2<?php }?> h-100">

		<div class="rounded-3 mb-2 overflow-hidden">

			<a class="link awe__doc-link overflow-hidden rounded-3" data-preload="false" 

			   <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'youtu.be') {?>

					data-fancybox href="https://www.youtube.com/watch?v=<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['youtu_id'];?>
"

			   <?php } elseif ($_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'google.file') {?>

					data-fancybox="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" <?php if (!empty($_smarty_tpl->tpl_vars['_list_images']->value)) {?> data-src="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['link'];?>
"<?php } else { ?> href="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['link'];?>
"<?php }?>

			   <?php } elseif ($_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'video' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'pdf' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'other' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'docx' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'docs.google.com') {?> 

					data-fancybox="<?php echo $_smarty_tpl->tpl_vars['result_id']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['link'];?>
" data-type="iframe"

			   <?php } else { ?>

					href="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['link'];?>
" target="_blank"

			   <?php }?>>

				<div class="awe__doc-img position-relative">

					<div class="img-background" style="background-image:url('<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['image'];?>
'),url(<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png)"></div>

					<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'video' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'youtu.be') {?>

					<div class="img-play"><i class="bx bx-play"></i></div>

					<?php }?>

				</div>

				<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['oneItem']->value['image'],150);?>
" width="150" alt="" class="d-none" loading="lazy">

			</a>

			<?php if (!empty($_smarty_tpl->tpl_vars['_list_images']->value)) {?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_list_images']->value, '_link');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_link']->value) {
?>

					<a class="d-none" data-preload="true" 

					   <?php if ($_smarty_tpl->tpl_vars['_link']->value['type'] == 'youtu.be') {?>

							data-fancybox href="https://www.youtube.com/watch?v=<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['youtu_id'];?>
"

					   <?php } elseif ($_smarty_tpl->tpl_vars['_link']->value['type'] == 'google.file') {?>

							data-fancybox="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" <?php if (!empty($_smarty_tpl->tpl_vars['_list_images']->value)) {?> data-src="<?php echo $_smarty_tpl->tpl_vars['_link']->value['image'];?>
"<?php } else { ?> href="<?php echo $_smarty_tpl->tpl_vars['_link']->value['image'];?>
"<?php }?>

					   <?php } elseif ($_smarty_tpl->tpl_vars['_link']->value['type'] == 'video' || $_smarty_tpl->tpl_vars['_link']->value['type'] == 'pdf' || $_smarty_tpl->tpl_vars['_link']->value['type'] == 'other' || $_smarty_tpl->tpl_vars['_link']->value['type'] == 'docx' || $_smarty_tpl->tpl_vars['_link']->value['type'] == 'docs.google.com') {?> 

							data-fancybox="<?php echo $_smarty_tpl->tpl_vars['result_id']->value;?>
" href="<?php echo $_smarty_tpl->tpl_vars['_link']->value['image'];?>
" data-type="iframe"

					   <?php } else { ?>

							href="<?php echo $_smarty_tpl->tpl_vars['_link']->value['image'];?>
" target="_blank"

					   <?php }?>>

						<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_link']->value['image'],150);?>
" width="150" alt="" class="d-none" loading="lazy">

					</a>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php }?>

		</div>

		<h4 class="fs-6 lh-sm mb-0 text-dark limit_2line" title="<?php echo $_smarty_tpl->tpl_vars['_oResult']->value['title'];?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->replaceString($_smarty_tpl->tpl_vars['_oResult']->value['title'],$_smarty_tpl->tpl_vars['keyword']->value);?>
</h4>

		<a href="<?php echo $_smarty_tpl->tpl_vars['_oResult']->value['link_download'];?>
" class="btn btn-default bg-white text-dark btn-sm p-1 position-absolute zindex-1" target="_blank" <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>style="top:15px;right: 15px"<?php } else { ?>style="top:8px;right: 8px"<?php }?>>

		<?php if ($_smarty_tpl->tpl_vars['_more_information']->value['file_type'] == 'folder' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'youtu.be' || $_smarty_tpl->tpl_vars['oneItem']->value['type'] == 'docs.google.com' || $_smarty_tpl->tpl_vars['_more_information']->value['file_type'] == 'user.fh') {?>

			<i class='bx bx-link-external'></i>

		<?php } else { ?>

			<i class="bx bx-download"></i>

		<?php }?>

		</a>

		<?php if (!empty($_smarty_tpl->tpl_vars['_oResult']->value['html_info'])) {?><div class="fs-12 my-2"><?php echo $_smarty_tpl->tpl_vars['_oResult']->value['html_info'];?>
</div><?php }?>

		<?php if (!empty($_smarty_tpl->tpl_vars['_oResult']->value['list_tags'])) {?>

			<div class="tags">

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_oResult']->value['list_tags'], 'tag', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['tag']->value) {
?>

					<a href="/tim-kiem/<?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
.html" class="tag"><?php echo $_smarty_tpl->tpl_vars['core']->value->replaceString($_smarty_tpl->tpl_vars['tag']->value,$_smarty_tpl->tpl_vars['keyword']->value);?>
</a>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			</div>

		<?php }?>

	</div>

<?php }
}
}
