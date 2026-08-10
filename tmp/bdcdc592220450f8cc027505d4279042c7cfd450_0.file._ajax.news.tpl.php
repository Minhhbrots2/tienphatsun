<?php
/* Smarty version 3.1.33, created on 2026-08-07 11:55:35
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.news.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a756547d335f9_48913324',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bdcdc592220450f8cc027505d4279042c7cfd450' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.news.tpl',
      1 => 1786078224,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a756547d335f9_48913324 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['action']->value == '_detail') {?>
<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-ipad-xl">
	<div class="modal-content overflow-y<?php if ($_smarty_tpl->tpl_vars['oneNews']->value['cat_id'] == $_smarty_tpl->tpl_vars['_NEWS_GRATITUDE_CAT_ID']->value) {?> item-gratitude<?php }?>">
		<?php if ($_smarty_tpl->tpl_vars['oneNews']->value['cat_id'] == $_smarty_tpl->tpl_vars['_NEWS_GRATITUDE_CAT_ID']->value) {?>
			<div class='stars_animation'></div>
			<div class='stars_animation2'></div>
			<div class='stars_animation3'></div>
		<?php }?>
		<div class="modal-header">
			<h5 class="modal-title mb-0">
				<span class="text-upper"><?php echo $_smarty_tpl->tpl_vars['oneNews']->value['title'];?>
</span><br />
				<span class="fs-11 text-muted">Đăng bởi <strong class="text-decoration-underline"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['oneNews']->value['user_id']);?>
</strong> vào <u><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getTimeAgo($_smarty_tpl->tpl_vars['oneNews']->value['reg_date']);?>
</u></span>
			</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body modal-body-news">
			<div class="tinyContent " data-height="200px">
				<?php echo $_smarty_tpl->tpl_vars['clsNews']->value->formatHTML($_smarty_tpl->tpl_vars['oneNews']->value['content']);?>

			</div>
			<?php if (!empty($_smarty_tpl->tpl_vars['oneNews']->value['attachments'])) {?>
				<?php echo $_smarty_tpl->tpl_vars['clsNews']->value->get_attachment_html($_smarty_tpl->tpl_vars['oneNews']->value['attachments']);?>

			<?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['oneNews']->value['project_tags'])) {?>
			<div class="d-flex flex-wrap my-1 gap-1">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['oneNews']->value['project_tags'], 'project_tag');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['project_tag']->value) {
?>
				<a  title="<?php echo $_smarty_tpl->tpl_vars['project_tag']->value;?>
" class="btn btn-xs rounded-pill btn-outline-primary"><?php echo $_smarty_tpl->tpl_vars['project_tag']->value;?>
</a>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</div>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['oneNews']->value['cat_id'] == $_smarty_tpl->tpl_vars['_NEWS_GRATITUDE_CAT_ID']->value) {?>
				<div class="lst_gratitude mt-3">
					<?php if (!empty($_smarty_tpl->tpl_vars['oneNews']->value['department'])) {?>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['oneNews']->value['department'], 'department');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['department']->value) {
?>
							<span class="badge px-2 me-2 mb-2 cursor-pointer" onClick="$Core.gratitude.historyGratitude(this,event)" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
" data-type="dep" data-id="<?php echo $_smarty_tpl->tpl_vars['department']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['department']->value['title'];?>
</span>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					<?php }?>
					<?php if (!empty($_smarty_tpl->tpl_vars['oneNews']->value['staff'])) {?>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['oneNews']->value['staff'], 'staff');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['staff']->value) {
?>
							<span class="badge px-2 me-2 mb-2 cursor-pointer" onClick="$Core.gratitude.historyGratitude(this,event)" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
" data-type="staff" data-id="<?php echo $_smarty_tpl->tpl_vars['staff']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['staff']->value['full_name'];?>
</span>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					<?php }?>
				</div>
			<?php }?>
			<?php if (!empty($_smarty_tpl->tpl_vars['oneNews']->value['images'])) {?>
			<div class="awe__post-item-body my-2">
				<div class="awe__post-gallery rounded-2 overflow-hidden gallery">
					<div id="gallery-grid-<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
"></div>
				</div>
			</div>
			<?php }?>
			<?php $_smarty_tpl->_assignInScope('total_comments', $_smarty_tpl->tpl_vars['oneNews']->value['total_comments']);?>
			<div gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" class="x1n2onr6<?php if ($_smarty_tpl->tpl_vars['oneNews']->value['total_actions'] == '0') {?> d-none<?php }?> py-1">
				<div class="d-flex justify-content-between align-items-center">
					<div gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" class="reactions-total d-flex align-items-center gap-1"><?php echo $_smarty_tpl->tpl_vars['clsNews']->value->genTotalLike($_smarty_tpl->tpl_vars['news_id']->value,$_smarty_tpl->tpl_vars['oneNews']->value);?>
</div>
					<div class="d-flex align-items-end comment gap-2">
						<span><i class="material-icons-outlined">visibility</i> <?php echo $_smarty_tpl->tpl_vars['oneNews']->value['view_num'];?>
</span>
						<span><i class="bx bx-comment"></i> <?php echo $_smarty_tpl->tpl_vars['total_comments']->value;?>
</span>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="awe__post-cmd">
				<div class="d-flex justify-content-center">
					<a gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" href="javascript:void(0);" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
" class="awe__post-action awe__post-like-action<?php if ($_smarty_tpl->tpl_vars['status_liked']->value == '1') {?> liked<?php }?>" onClick="$Core.news.like(this, event)" data-name="like" data-clsTable="News" data-table_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
">
						<?php if ($_smarty_tpl->tpl_vars['status_liked']->value == '1') {?>
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bxs-heart','Thích');?>

						<?php } else { ?>
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-heart','Thích');?>

						<?php }?>
					</a>					
					<?php if ($_smarty_tpl->tpl_vars['oneNews']->value['cat_id'] == $_smarty_tpl->tpl_vars['_NEWS_GRATITUDE_CAT_ID']->value) {?>
						<a href="javascript:void(0)" class="awe__post-action awe__post-comment-action" onClick="$Core.gratitude.open_list_gratitude(this, event)" gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-donate-heart','Tri ân');?>

						</a>
					<?php }?>
					<a href="javascript:void(0);" class="awe__post-action awe__post-comment-action" action="_detail" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
">
						<?php if ($_smarty_tpl->tpl_vars['oneNews']->value['total_comments'] > '0') {?>
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-comment',($_smarty_tpl->tpl_vars['oneNews']->value['total_comments']).(' Bình luận'));?>

						<?php } else { ?>
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-comment','Bình luận');?>

						<?php }?>
					</a>
				</div>
			</div>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('comment',array('table_id'=>$_smarty_tpl->tpl_vars['news_id']->value,'clsTable'=>'News'));?>

		</div> 
		<div class="awe__news-comment modal-footer p-0 m-0">
			<div class="awe__comment-form m-0">
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
									<input type="hidden" name="table_id" value="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
" />
									<input type="hidden" name="clsTable" value="News" />
									<a href="javascript:void(0)" onClick="$Core.news.add_comment(this, event)" type="text" class="js-add-comment awe__comment-action disabled" comment_id="0" holderG="_comment"><svg fill="#CCC" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="20"><path fill="currentColor" d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg></a>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php } else { ?>
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-ipad-xl">
	<?php $_smarty_tpl->_assignInScope('uid_file', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
	<form class="d-none" enctype="multipart/form-data">
		<input id="select_file_<?php echo $_smarty_tpl->tpl_vars['uid_file']->value;?>
" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" uid="<?php echo $_smarty_tpl->tpl_vars['uid_file']->value;?>
" onchange="$Core.global.news.upload_file(this,event)" charset="UTF-8" name="image">
	</form>
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title" id="modalTopTitle">Thêm mới bản tin</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body scroller">
			<div class="form-group mb-2">
				<?php $_smarty_tpl->_assignInScope('_uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
				<div class="form-floating">
					<input type="text" class="form-control required" id="<?php echo $_smarty_tpl->tpl_vars['_uid']->value;?>
" name="title" maxlength="255" 
					placeholder="Nhập tiêu đề bản tin" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['oneNews']->value['title'], ENT_QUOTES, 'UTF-8', true);?>
">
					<label for="<?php echo $_smarty_tpl->tpl_vars['_uid']->value;?>
">Tiêu đề</label>
				</div>
			</div>
			<div class="form-group form-row mb-2">
				<div class="col-12 col-md-6 mb-2 mb-lg-0">
					<?php $_smarty_tpl->_assignInScope('_uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
					<div class="form-floating">
						<select class="form-control form-select required" name="cat_id">
							<option value="0">Chọn danh mục</option>
							<?php if (!empty($_smarty_tpl->tpl_vars['arr_ca_cats']->value)) {?>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_ca_cats']->value, '_oT', false, '_oK');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oK']->value => $_smarty_tpl->tpl_vars['_oT']->value) {
?>
								<option<?php if ($_smarty_tpl->tpl_vars['oneNews']->value['cat_id'] == $_smarty_tpl->tpl_vars['_oK']->value) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_oT']->value['title'];?>
</option>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php }?>
						</select>
						<label for="<?php echo $_smarty_tpl->tpl_vars['_uid']->value;?>
">Danh mục</label>
					</div>
				</div>
				<?php if (!empty($_smarty_tpl->tpl_vars['lstProjectTag']->value)) {?>
					<div class="col-12 col-md-6">
						<div class="box_input_tag">
							<label  class="form-label fs-10">Dự án</label>
							<select class="iso-select2 w-100" data-width="100%" data-placeholder="Chọn dự án" name="project_ids[]" data-optgroup="false" multiple >
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstProjectTag']->value, '_oItem', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['setting_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oItem']->value['setting_id'],$_smarty_tpl->tpl_vars['oneNews']->value['listProjectIds'])) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</option>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</select>
						</div>
					</div>
				<?php }?>
			</div>
			<div class="form-group mb-2">
				<label for="nameSlideTop" class="form-label">Nội dung</label>
				<textarea id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" class="form-control isoTextArea" cols="255" rows="25" 
					data-name="content"><?php if ($_smarty_tpl->tpl_vars['news_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneNews']->value['content'];
}?></textarea>
			</div>
			<div class="form-group">
				<label for="nameSlideTop" class="form-label">Hình ảnh</label>
				<div class="we-filedrop-wrapper mb-2">
					<input id="selectFile_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onChange="$Core.upload.file_upload(this, event)" class="d-none" accept="image/*" multiple="multiple" type="file" tabindex="-1">
					<div class="we-filedrop mb-1" toId="selectFile_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onclick="$Core.upload.file_explorer(this,event);"> 
						<svg class="mb-2" width="70" height="70" viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M118.42 75.84C118.43 83.2392 116.894 90.5589 113.91 97.33H16.09C12.8944 90.0546 11.3622 82.1579 11.6049 74.2154C11.8477 66.2728 13.8593 58.4844 17.4932 51.4177C21.1271 44.3511 26.2918 38.1841 32.6109 33.3662C38.93 28.5483 46.2443 25.2008 54.0209 23.5676C61.7976 21.9345 69.8406 22.0568 77.564 23.9257C85.2873 25.7946 92.4965 29.363 98.6661 34.3709C104.836 39.3787 109.81 45.6999 113.228 52.8739C116.645 60.0478 118.419 67.8937 118.42 75.84Z" fill="#F2F2F2"></path><path d="M5.54 97.33H126.37" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M97 97.33H49.91V34.65C49.91 34.3848 50.0154 34.1305 50.2029 33.9429C50.3904 33.7554 50.6448 33.65 50.91 33.65H84.18C84.6167 33.6541 85.0483 33.7445 85.4499 33.9162C85.8515 34.0878 86.2152 34.3372 86.52 34.65L96.02 44.15C96.3321 44.4533 96.5811 44.8153 96.7527 45.2151C96.9243 45.615 97.0152 46.0449 97.02 46.48L97 97.33Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" fill="white"></path><path d="M59.09 105.64H42.09C41.8248 105.64 41.5704 105.535 41.3829 105.347C41.1954 105.16 41.09 104.905 41.09 104.64V41.79C41.09 41.5248 41.1954 41.2705 41.3829 41.0829C41.5704 40.8954 41.8248 40.79 42.09 40.79H77.33L89 52.42V104.62C89 104.885 88.8946 105.14 88.7071 105.327C88.5196 105.515 88.2652 105.62 88 105.62H74.86" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M88.97 52.42H77.33V40.77L88.97 52.42Z" fill="#D7D7D7" stroke="#63666A" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M27.32 65.49V70.6" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M29.88 68.04H24.76" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M110.49 32.5601V39.9901" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M114.2 36.27H106.77" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M34.07 14.58V25.59" stroke="#D7D7D7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"></path><path d="M39.57 20.08H28.57" stroke="#D7D7D7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M67 115.86V67.12" stroke="#63666A" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" fill="white"></path><path d="M55.5 78.61L67 67.12L78.5 78.61" stroke="#63666A" stroke-width="1" stroke-miterlimit="10"></path></svg> 
						<p class="mb-0">Bấm để chọn một hoặc nhiều hình ảnh cần tải lên !!!</p>
					</div>
					<div id="imageList" class="d-flex flex-wrap box-done-img">
						<?php if (!empty($_smarty_tpl->tpl_vars['list_images']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_images']->value, '_oImage');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oImage']->value) {
?>
							<span class="item">
								<img src="<?php echo $_smarty_tpl->tpl_vars['_oImage']->value;?>
" />
								<input type="hidden" name="images[]" value="<?php echo $_smarty_tpl->tpl_vars['_oImage']->value;?>
" />
								<a class="delete" src="<?php echo $_smarty_tpl->tpl_vars['_oImage']->value;?>
" onClick="$Core.upload.delete(this, event)"></a>
							</span>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					</div>
				</div>
			</div>
			<div class="form-group attachments mb-2">
				<label class="form-label mb-1">File đính kèm</label>
				<div class="clearfix"></div>
				<div class="MultiFile-preview" id="MultiFile-preview_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
				<?php if (!empty($_smarty_tpl->tpl_vars['oneNews']->value['attachments'])) {?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['oneNews']->value['attachments'], '_oFile', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oFile']->value) {
?>
					<div class="MultiFile-label">
						<a class="MultiFile-remove cursor-pointer" onclick="$Core.global.news.removeFile(this,event)" 
							news_id="<?php echo $_smarty_tpl->tpl_vars['oneNews']->value['news_id'];?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['_oFile']->value;?>
">x</a> 
							<span>
								<span class="MultiFile-label" title="<?php echo $_smarty_tpl->tpl_vars['_oFile']->value;?>
">
									<span class="MultiFile-title"><?php echo $_smarty_tpl->tpl_vars['_oFile']->value['url'];?>
</span>
								</span>
							</span>
						</a>
					</div>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php }?>
				</div>
				<div class="clearfix"></div>
				<input name="attachments[]" type="file" multiple="multiple" class="maxsize-10240" id="attachments_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" />
			</div>
			<div class="bg-lighter rounded-2 mb-2 p-3 d-none">
				<div class="form-check text-upper">
					<input class="form-check-input" type="checkbox" name="show_pop" value="1" 
						id="show_pop_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"<?php if (!empty($_smarty_tpl->tpl_vars['oneNews']->value['show_pop'])) {?> checked<?php }?>>
					<label class="form-check-label" for="show_pop_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"> Hiện popup</label>
				</div>
				<div class="form-check text-upper">
					<input class="form-check-input" type="checkbox" name="is_send_email" value="1" 
						id="send_email_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"<?php if (!empty($_smarty_tpl->tpl_vars['oneNews']->value['is_send_email'])) {?> checked<?php }?>>
					<label class="form-check-label" for="send_email_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"> Gửi mail toàn bộ</label>
				</div>
				<div id="moc_cat_block_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" class="pl-4 mb-2 d-none">
					<select class="form-control formm-select" name="cat_moc_id">
						<option value="0">Chọn danh mục</option>
						<?php if (!empty($_smarty_tpl->tpl_vars['arr_cat_mocs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_cat_mocs']->value, '_oT', false, '_oK');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oK']->value => $_smarty_tpl->tpl_vars['_oT']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['oneNews']->value['cat_moc_id'] == $_smarty_tpl->tpl_vars['_oK']->value) {?> selected<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_oT']->value;?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					</select>
				</div>
			</div>
		</div>
		<div class="modal-footer justify-content-between">
			<div class="d-flex align-items-center gap-2">
				<label class="switch">
					<input type="checkbox" name="is_online"<?php if ($_smarty_tpl->tpl_vars['oneNews']->value['is_online'] == '1') {?> checked<?php }?> value="1">
					<span class="slider round"></span>
				</label>
				<span>Xuất bản</span>
			</div>
			<div class="d-flex gap-2 align-items-center">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
				<button type="button" onClick="pop_save_news(this, event)" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
" 
				class="btn btn-primary">Lưu lại</button>
			</div>
		</div>
	</form>
</div>
<?php }
}
}
