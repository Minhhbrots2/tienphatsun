<?php
/* Smarty version 3.1.33, created on 2026-08-08 15:47:38
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/project_docs/_ajax.docs.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a76ed2a3d2071_42693511',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '940d39978705350cc92da666fb94eaf22269fde2' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/project_docs/_ajax.docs.tpl',
      1 => 1784299667,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a76ed2a3d2071_42693511 (Smarty_Internal_Template $_smarty_tpl) {
if (!empty($_smarty_tpl->tpl_vars['list_docs']->value)) {?>
<div class="form-row row-cols-2 row-cols-md-4 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-5 gy-4">
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_docs']->value, '_oDocument', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oDocument']->value) {
?>
        <?php $_smarty_tpl->_assignInScope('_list_images', $_smarty_tpl->tpl_vars['_oDocument']->value['list_images']);?>
        <?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['_oDocument']->value['more_information']);?>
		<div class="col mb-2">
			<div class="item_document d-flex flex-column h-100 position-relative">
				<?php if (!empty($_smarty_tpl->tpl_vars['_list_images']->value)) {?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_list_images']->value, 'item', false, 'key', 'name', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_name']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_name']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_name']->value['index'];
?>
						<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_name']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_name']->value['first'] : null)) {?>
							<a data-preload="true" class="img-square-wrapper cls-curso-point" target="_blank"
							<?php if ($_smarty_tpl->tpl_vars['item']->value['type'] == 'youtu.be') {?>
								data-fancybox="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['id'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['item']->value['image'];?>
"
							<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['type'] == 'google.file') {?>
								data-fancybox="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['id'];?>
"
								<?php if (!empty($_smarty_tpl->tpl_vars['_list_images']->value)) {?> 
									data-src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->genGoogleURL($_smarty_tpl->tpl_vars['_more_information']->value['gg_id']);?>
"
								<?php } else { ?> 
									href="<?php echo $_smarty_tpl->tpl_vars['item']->value['image'];?>
"
								<?php }?>
							<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['type'] == 'video' || $_smarty_tpl->tpl_vars['item']->value['type'] == 'pdf' || $_smarty_tpl->tpl_vars['item']->value['type'] == 'other' || $_smarty_tpl->tpl_vars['item']->value['type'] == 'docx' || $_smarty_tpl->tpl_vars['item']->value['type'] == 'docs.google.com') {?> 
								data-fancybox="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['id'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['item']->value['image'];?>
" data-type="iframe"
							<?php } else { ?>
								href="<?php if (strpos($_smarty_tpl->tpl_vars['_oDocument']->value['content'],"http") === false) {
echo FH_URL;?>
 <?php }
echo $_smarty_tpl->tpl_vars['_oDocument']->value['content'];?>
" target="_blank"
							<?php }?>>
								<img class="image_background" src="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['image'];?>
">
								<img src="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['image'];?>
" alt="" class="item_document-image">
								<?php if ($_smarty_tpl->tpl_vars['item']->value['type'] == 'youtu.be' || $_smarty_tpl->tpl_vars['item']->value['type'] == 'video') {?>
									<div class="img-play"><i class="bx bx-play"></i></div>
								<?php }?>
								<button data-toggle="ripple" class="btn d-flex alig-item-center justify-content-center file-favarite <?php if ($_smarty_tpl->tpl_vars['_oDocument']->value['is_save'] == '1') {?> saved <?php }?>" onClick="$Core.document.bookmarked(this, event);" doc_id="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['id'];?>
">
									<i class="bx <?php if ($_smarty_tpl->tpl_vars['_oDocument']->value['is_save'] == '1') {?>bxs<?php } else { ?>bx<?php }?>-heart icon-favarite"></i>
								</button>
								<?php if (!empty($_smarty_tpl->tpl_vars['_oDocument']->value['link_download']) && count($_smarty_tpl->tpl_vars['_list_images']->value) <= 1 && $_smarty_tpl->tpl_vars['_oDocument']->value['type'] != 'youtu.be' && strpos($_smarty_tpl->tpl_vars['_oDocument']->value['content'],"drive.google.com") !== false) {?> 
									<a href="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['_oDocument']->value['link_download'])===null||$tmp==='' ? "#" : $tmp);?>
" download class="file-icon d-flex justify-content-center align-items-center" title="Download tài liệu <?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['title'];?>
">
										<i class='bx bxs-download'></i>
									</a>
								<?php } elseif (!empty($_smarty_tpl->tpl_vars['_oDocument']->value['content']) && strpos($_smarty_tpl->tpl_vars['_oDocument']->value['content'],"drive.google.com") === false) {?>
									<a href="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['_oDocument']->value['content'])===null||$tmp==='' ? "#" : $tmp);?>
" target="_blank" class="file-icon d-flex justify-content-center align-items-center" title="Truy cập tài liệu <?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['title'];?>
">
										<i class='bx bx-link-external' ></i>
									</a>
								<?php } elseif (!empty($_smarty_tpl->tpl_vars['_oDocument']->value['content']) && $_smarty_tpl->tpl_vars['item']->value['type'] != 'youtu.be') {?>
									<a href="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['_oDocument']->value['content'])===null||$tmp==='' ? "#" : $tmp);?>
" target="_blank" class="file-icon d-flex justify-content-center align-items-center" title="Truy cập tài liệu <?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['title'];?>
">
										<i class='bx bxs-folder-open'></i>
									</a>
								<?php }?>
							</a>
						<?php } else { ?>
						<a class="d-none" data-fancybox="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['id'];?>
"<?php if ($_smarty_tpl->tpl_vars['item']->value['type'] == 'video' || $_smarty_tpl->tpl_vars['item']->value['type'] == 'pdf' || $_smarty_tpl->tpl_vars['_oDoc']->value['type'] == 'other' || $_smarty_tpl->tpl_vars['_oDoc']->value['type'] == 'docx' || $_smarty_tpl->tpl_vars['_oDoc']->value['type'] == 'docs.google.com') {?> data-type="iframe"<?php }?> data-src="<?php echo $_smarty_tpl->tpl_vars['item']->value['image'];?>
" data-caption="<?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['title'];?>
"></a>
						<?php }?>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php } else { ?>
                    <a data-preload="true" class="img-square-wrapper cls-curso-point" target="_blank"
                    <?php if ($_smarty_tpl->tpl_vars['_oDocument']->value['type'] == 'youtu.be') {?>
                        data-fancybox="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['id'];?>
" href="https://www.youtube.com/watch?v=<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['youtu_id'];?>
"
                    <?php } elseif ($_smarty_tpl->tpl_vars['_oDocument']->value['type'] == 'google.file') {?>
                        data-fancybox="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['id'];?>
"
                        <?php if (!empty($_smarty_tpl->tpl_vars['_list_images']->value)) {?> 
                            data-src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->genGoogleURL($_smarty_tpl->tpl_vars['_more_information']->value['gg_id']);?>
"
                        <?php } else { ?> 
                            data-type="iframe"
                            href="<?php echo trim($_smarty_tpl->tpl_vars['_oDocument']->value['link']);?>
"
                        <?php }?>
                    <?php } elseif ($_smarty_tpl->tpl_vars['_oDocument']->value['type'] == 'video' || $_smarty_tpl->tpl_vars['_oDocument']->value['type'] == 'pdf' || $_smarty_tpl->tpl_vars['_oDocument']->value['type'] == 'other' || $_smarty_tpl->tpl_vars['_oDocument']->value['type'] == 'docx' || $_smarty_tpl->tpl_vars['_oDocument']->value['type'] == 'docs.google.com') {?> 
                        data-fancybox="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['id'];?>
" href="<?php echo trim($_smarty_tpl->tpl_vars['_oDocument']->value['link']);?>
" data-type="iframe"
                    <?php } else { ?>
                        <?php if (strpos($_smarty_tpl->tpl_vars['_oDocument']->value['content'],"drive.google.com") === false) {?>
                            data-type="iframe"
                        <?php }?>
                        data-fancybox="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['id'];?>
" href="<?php if (strpos($_smarty_tpl->tpl_vars['_oDocument']->value['content'],"http") === false) {
echo FH_URL;?>
 <?php }
echo trim($_smarty_tpl->tpl_vars['_oDocument']->value['content']);?>
" target="_blank"
                    <?php }?>>
                        <img class="image_background" src="<?php echo trim($_smarty_tpl->tpl_vars['_oDocument']->value['image']);?>
" data-src="<?php echo trim($_smarty_tpl->tpl_vars['_oDocument']->value['image']);?>
">
                        <img src="<?php echo trim($_smarty_tpl->tpl_vars['_oDocument']->value['image']);?>
" data-src="<?php echo trim($_smarty_tpl->tpl_vars['_oDocument']->value['image']);?>
" alt="" class="item_document-image">
                        <?php if ($_smarty_tpl->tpl_vars['_oDocument']->value['type'] == 'youtu.be' || $_smarty_tpl->tpl_vars['_oDocument']->value['type'] == 'video') {?>
                            <div class="img-play"><i class="bx bx-play"></i></div>
                        <?php }?>
                        <button data-toggle="ripple" class="btn d-flex alig-item-center justify-content-center file-favarite <?php if ($_smarty_tpl->tpl_vars['_oDocument']->value['is_save'] == '1') {?> saved <?php }?>" onClick="$Core.document.bookmarked(this, event);" doc_id="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['id'];?>
">
                            <i class="bx <?php if ($_smarty_tpl->tpl_vars['_oDocument']->value['is_save'] == '1') {?>bxs<?php } else { ?>bx<?php }?>-heart icon-favarite"></i>
                        </button>
                        <?php if (!empty($_smarty_tpl->tpl_vars['_oDocument']->value['link_download']) && count($_smarty_tpl->tpl_vars['_list_images']->value) <= 1 && $_smarty_tpl->tpl_vars['_oDocument']->value['type'] != 'youtu.be' && strpos($_smarty_tpl->tpl_vars['_oDocument']->value['content'],"drive.google.com") !== false) {?> 
                            <a href="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['_oDocument']->value['link_download'])===null||$tmp==='' ? "#" : $tmp);?>
" download class="file-icon d-flex justify-content-center align-items-center link-download-document" title="Download tài liệu <?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['title'];?>
">
                               <i class='bx bxs-download'></i>
                            </a>
                        <?php } elseif (!empty($_smarty_tpl->tpl_vars['_oDocument']->value['content']) && strpos($_smarty_tpl->tpl_vars['_oDocument']->value['content'],"drive.google.com") === false) {?>
                            <a href="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['_oDocument']->value['content'])===null||$tmp==='' ? "#" : $tmp);?>
" target="_blank" class="file-icon d-flex justify-content-center align-items-center" title="Truy cập tài liệu <?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['title'];?>
">
                               <i class='bx bx-link-external' ></i>
                            </a>
                        <?php } elseif (!empty($_smarty_tpl->tpl_vars['_oDocument']->value['content']) && $_smarty_tpl->tpl_vars['_oDocument']->value['type'] != 'youtu.be') {?>
                            <a href="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['_oDocument']->value['content'])===null||$tmp==='' ? "#" : $tmp);?>
" target="_blank" class="file-icon d-flex justify-content-center align-items-center" title="Truy cập tài liệu <?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['title'];?>
">
                               <i class='bx bxs-folder-open'></i>
                            </a>
                        <?php }?>
                    </a>
                <?php }?>
				<div class="content_document mt-3">
					<h4 class="title fs-6 mb-1 fw-bold cls-curso-point" title="<?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['title'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['title'];?>
</h4>
					<div class="d-flex align-items-center gap-1">
						<span class="text-fs-12"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatDate($_smarty_tpl->tpl_vars['_oDocument']->value['upd_date'],'7');?>
 </span> 
						<span class="text-fs-12"><?php echo $_smarty_tpl->tpl_vars['_oDocument']->value['cat_name'];?>
</span>
					</div>
					<div class="my-1 d-flex align-items-center">
						<i class='bx bx-building-house' ></i>
						<span><?php echo $_smarty_tpl->tpl_vars['list_project_by_key_id']->value[$_smarty_tpl->tpl_vars['_oDocument']->value['project_id']]['title'];?>
</span></a><br>
						<span class="<?php echo $_smarty_tpl->tpl_vars['_oslide']->value;?>
"></span>
					</div>
                    <?php echo $_smarty_tpl->tpl_vars['clsProjectMeta']->value->getHTMLTag($_smarty_tpl->tpl_vars['_oslide']->value['slide_id'],$_smarty_tpl->tpl_vars['_oDocument']->value,true);?>

				</div>
			</div>
		</div>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div>
<?php } else { ?>
	<div class="d-flex justify-content-center h-100">
		<p class="d-flex text-center align-items-center fs-5">Không có dữ liệu</p>
	</div>
<?php }
}
}
