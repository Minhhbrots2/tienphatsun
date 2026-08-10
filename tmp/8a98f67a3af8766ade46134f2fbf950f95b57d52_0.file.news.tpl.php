<?php
/* Smarty version 3.1.33, created on 2026-08-07 11:49:48
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/news.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7563ece03bc6_76011702',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8a98f67a3af8766ade46134f2fbf950f95b57d52' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/news.tpl',
      1 => 1786078185,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7563ece03bc6_76011702 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript">
	var _NEWS_EVENT_CAT_ID = '<?php echo @constant('_NEWS_EVENT_CAT_ID');?>
';
<?php echo '</script'; ?>
>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="oMABKutTId mb-2">
		<h4 class="fw-bold mb-1">Bản tin <?php echo @constant('BRAND_NAME');?>
</h4>
		<span class="text-muted">Tổng hợp các bản tin nội bộ</span>
	</div>
	<section class="section section-xxs">
		<div class="row flex-row flex-wrap">
			<div class="col-md-2 mb-3 mb-lg-0">
				<div class="leftbar sticky">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('news_category');?>

				</div>
			</div>
			<div class="col-md-6">
				<div class="awe__post-page">
					<div class="awe__post-form bg-white radius-4 mb-4">
						<div class="d-flex align-items-center w-100">
							<div class="awe__post-avatar position-relative">
								<?php if ($_smarty_tpl->tpl_vars['loggedIn']->value == '1') {?>
								<img class="rounded" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value,44,44);?>
" width="44" height="44" />
								<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->get_icon_verified($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value['more_information']);?>

								<?php } else { ?>
								<img class="rounded" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" width="44" height="44" />
								<?php }?>
							</div>
							<div class="textarea">
								<a class="awe__post-input-link open_add_news" href="javascript:void(0);" onClick="open_news(this, event)" news_id="0" action="_add" cat_docs_id="<?php echo $_smarty_tpl->tpl_vars['cat_docs_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" >Viết bài, chia sẻ, đặt câu hỏi...</a>
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					<?php if (!empty($_smarty_tpl->tpl_vars['list_post']->value)) {?>
					<div class="awe__list-post"> 
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_post']->value, '_oNews', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oNews']->value) {
?>
						<?php $_smarty_tpl->_assignInScope('news_id', $_smarty_tpl->tpl_vars['_oNews']->value['news_id']);?>
						<?php $_smarty_tpl->_assignInScope('_link', $_smarty_tpl->tpl_vars['_oNews']->value['link']);?>
						<?php $_smarty_tpl->_assignInScope('events_config', $_smarty_tpl->tpl_vars['_oNews']->value['events_config']);?>
						<?php $_smarty_tpl->_assignInScope('_title', $_smarty_tpl->tpl_vars['clsNews']->value->getTitle($_smarty_tpl->tpl_vars['news_id']->value,$_smarty_tpl->tpl_vars['_oNews']->value));?>
						<?php $_smarty_tpl->_assignInScope('gid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
						<div class="awe__post-item" order_no="<?php echo $_smarty_tpl->tpl_vars['_oNews']->value['order_no'];?>
" id="post_item_<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
">
							<div class="w-100 d-flex align-items-start justify-content-between mb-3">
								<div class="d-flex gap-2 flex-wrap align-items-center">
									<div class="awe__post-profile d-flex" data-url="/index.php?mod=home&act=load_profile_popover&user_id=<?php echo $_smarty_tpl->tpl_vars['_oNews']->value['user_id'];?>
" data-toggle="webui-popover" data-trigger="hover" data-width="350">
										<div class="awe__post-avatar position-relative">
											<img class="rounded" src="<?php echo $_smarty_tpl->tpl_vars['_oNews']->value['db_profile']['avatar'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" width="44" height="44" /> 
											<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->get_icon_verified($_smarty_tpl->tpl_vars['_oNews']->value['user_id'],$_smarty_tpl->tpl_vars['_oNews']->value['db_profile']['more_information']);?>

										</div>
										<div class="awe__post-profile-body">
											<p class="awe__post-name"><?php echo $_smarty_tpl->tpl_vars['_oNews']->value['db_profile']['name'];?>
</p>
											<div class="d-flex align-items-center">
												<?php if (!empty($_smarty_tpl->tpl_vars['_oNews']->value['db_profile']['role'])) {?>
												<span class="awe__post-level mr-2 text-muted">
													<?php echo $_smarty_tpl->tpl_vars['_oNews']->value['db_profile']['role'];?>

												</span>
												<?php }?>
												<span class="awe__post-star mr-2 text-muted">
													<?php echo $_smarty_tpl->tpl_vars['_oNews']->value['db_profile']['html_star'];?>

												</span>
												<span class="awe__post-time text-muted">
													<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getTimeAgo($_smarty_tpl->tpl_vars['_oNews']->value['reg_date']);?>

												</span>
											</div>
										</div>
									</div>
									<?php if (!empty($_smarty_tpl->tpl_vars['_oNews']->value['project_tags'])) {?>
										<div class="d-flex flex-wrap gap-1">
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_oNews']->value['project_tags'], 'project_tag');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['project_tag']->value) {
?>
												<a href="<?php echo $_smarty_tpl->tpl_vars['_oTag']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['project_tag']->value;?>
" class="btn btn-xs rounded-pill btn-outline-primary"><?php echo $_smarty_tpl->tpl_vars['project_tag']->value;?>
</a>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										</div>
									<?php }?>
								</div>
								<?php if ($_smarty_tpl->tpl_vars['_oNews']->value['user_id'] == $_smarty_tpl->tpl_vars['profile_id']->value || $_smarty_tpl->tpl_vars['clsISO']->value->checkPermissionGroup('DIRECTOR') || $_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
								<div class="dropdown">
									<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
										<i class="bx bx-dots-vertical-rounded"></i>
									</button>
									<div class="dropdown-menu" data-popper-placement="bottom-end">
										<a class="dropdown-item" href="javascript:void(0);" onClick="open_news(this, event)" gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
" action="_edit"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-edit-alt me-1','Sửa');?>
</a>
										<a class="dropdown-item" href="javascript:void(0);" onClick="delete_news(this, event)" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-trash me-1','Xóa');?>
</a>
									</div>
								</div>
								<?php }?>
							</div>
							<div class="awe__post-item-body">
								<h3 class="awe__post-title mb-3" onClick="open_news(this, event)" gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" 
									news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
" action="_detail">
									<a class="fs-16 awe__post-link" title="<?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_title']->value;?>
</a>
								</h3>
								<div class="awe__post-description mb-3" data-height="10px" onClick="open_news(this, event)" 
									gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
" action="_detail">
									<?php echo $_smarty_tpl->tpl_vars['clsNews']->value->getIntro($_smarty_tpl->tpl_vars['news_id']->value,$_smarty_tpl->tpl_vars['_oNews']->value);?>

								</div>
								<?php if (!empty($_smarty_tpl->tpl_vars['_oNews']->value['attachments'])) {?>
									<?php echo $_smarty_tpl->tpl_vars['clsNews']->value->get_attachment_html($_smarty_tpl->tpl_vars['_oNews']->value['attachments']);?>

								<?php }?>
								<?php if (!empty($_smarty_tpl->tpl_vars['_oNews']->value['images'])) {?>
								<div class="awe__post-gallery gallery mb-3">
									<?php echo $_smarty_tpl->tpl_vars['clsNews']->value->getImageGrid($_smarty_tpl->tpl_vars['news_id']->value,$_smarty_tpl->tpl_vars['_oNews']->value['images']);?>

								</div>
								<?php }?>
							</div>
							<?php $_smarty_tpl->_assignInScope('total_comments', $_smarty_tpl->tpl_vars['_oNews']->value['total_comments']);?>
							<div gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" class="x1n2onr6<?php if ($_smarty_tpl->tpl_vars['_oNews']->value['total_actions'] == '0') {?> d-none<?php }?> py-1">
								<div class="d-flex justify-content-between align-items-center">
									<div gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" class="reactions-total d-flex align-items-center gap-1"><?php echo $_smarty_tpl->tpl_vars['clsNews']->value->genTotalLike($_smarty_tpl->tpl_vars['news_id']->value,$_smarty_tpl->tpl_vars['_oNews']->value);?>
</div>
									<div class="d-flex align-items-center comment gap-2">
										<span><i class="material-icons-outlined">visibility</i> <?php echo $_smarty_tpl->tpl_vars['_oNews']->value['view_num'];?>
</span>
										<span><i class="bx bx-comment"></i> <?php echo $_smarty_tpl->tpl_vars['total_comments']->value;?>
</span>
									</div>
								</div>
							</div>
							<div class="awe__post-cmd">
								<div class="d-flex justify-content-center">
									<a gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" href="javascript:void(0);" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
" class="awe__post-action flex-fill awe__post-like-action<?php if ($_smarty_tpl->tpl_vars['_oNews']->value['status_liked'] == '1') {?> liked<?php }?>" onClick="$Core.news.like(this, event)" data-name="like" data-clsTable="News" data-table_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
">
										<?php if ($_smarty_tpl->tpl_vars['_oNews']->value['status_liked'] == '1') {?>
											<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bxs-heart text-main','Thích');?>

										<?php } else { ?>
											<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-heart','Thích');?>

										<?php }?>
									</a>
									<a href="<?php echo $_smarty_tpl->tpl_vars['_link']->value;?>
" class="awe__post-action flex-fill awe__post-comment-action" 
									onClick="open_news(this, event)" gid="<?php echo $_smarty_tpl->tpl_vars['gid']->value;?>
" action="_detail" news_id="<?php echo $_smarty_tpl->tpl_vars['news_id']->value;?>
">
										<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-comment','Bình luận');?>

									</a>
								</div>
							</div>
						</div>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?> 
					</div>
					<?php }?>
					<div class="clearfix"></div>
					<?php if ($_smarty_tpl->tpl_vars['total_record']->value > $_smarty_tpl->tpl_vars['per_page']->value) {?>
					<div class="d-flex justify-content-center mb-3">
						<button onClick="load_post_more(this, event)" data-toggle="ripple" class="btn btn-block btn-lg btn-link bg-white text-dark font-weight-bold" cat_id="<?php echo $_smarty_tpl->tpl_vars['cat_id']->value;?>
" total_loaded="<?php echo $_smarty_tpl->tpl_vars['per_page']->value;?>
" total_record="<?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
"><i class="fa fa-angle-down"></i> Xem thêm</button>
					</div>
					<?php }?>
			   </div>
			</div>
			<div class="col-md-4">
				<div class="sticky">
					<div class="card ranking">
						<div class="card-body">
							<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_ranking');?>

						</div>
					</div>
					<div class="card no-shadow sticky mt-3">
						<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('top_staff');?>

					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php echo $_smarty_tpl->tpl_vars['scriptJs']->value;?>

<?php echo $_smarty_tpl->tpl_vars['script']->value;?>

<?php echo '<script'; ?>
 type="text/javascript">
	$(function(){
		if($('.awe__post-description:not(.collapsed)').length){
			$('.awe__post-description:not(.collapsed)').each((_i, _elem) => {
				var _height = $(_elem).outerHeight();
				if(_height > 200){
					$(_elem).addClass('collapsed').append('<a class="awe__link-more">Xem thêm...</a>');
				}
			});
		}
	});
<?php echo '</script'; ?>
>
<?php }
}
