<?php
/* Smarty version 3.1.33, created on 2026-08-07 11:55:36
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.list_comment.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7565483f78b2_28553461',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2e1cd77482f00c0d57dbec151f9512eacbfaee60' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.list_comment.tpl',
      1 => 1785564467,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7565483f78b2_28553461 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['list_comments']->value) {?>
	<?php if (($_smarty_tpl->tpl_vars['clsTable']->value == 'News' || $_smarty_tpl->tpl_vars['clsTable']->value == 'Course') && $_smarty_tpl->tpl_vars['action']->value == 'reload') {?>
	<div class="awe__comment-tabs">
		<a href="javascript:void(0);" clsTable="<?php echo $_smarty_tpl->tpl_vars['clsTable']->value;?>
" table_id="<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" class="filter-comment__item<?php if ($_smarty_tpl->tpl_vars['sort_by']->value == 'desc') {?> active<?php }?>" onclick="$Core.news.do_sort_comment(this,event)" rel="desc">Mới nhất</a>
		<a href="javascript:void(0);" class="filter-comment__item<?php if ($_smarty_tpl->tpl_vars['sort_by']->value == 'asc') {?> active<?php }?>" clsTable="<?php echo $_smarty_tpl->tpl_vars['clsTable']->value;?>
" table_id="<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" onClick="$Core.news.do_sort_comment(this,event)" rel="asc">Cũ nhất</a>
	</div>
	<?php }?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_comments']->value, '_oComment', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oComment']->value) {
?>
	<?php $_smarty_tpl->_assignInScope('_comment_id', $_smarty_tpl->tpl_vars['_oComment']->value['comment_id']);?>
	<div class="awe__comment-item">
		<div class="d-flex w-100">
			<div class="awe__profile-avatar" data-url="/index.php?mod=home&act=load_profile_popover&user_id=<?php echo $_smarty_tpl->tpl_vars['_oComment']->value['profile_id'];?>
" data-toggle="webui-popover" data-trigger="hover" data-placement="top" data-width="350">
				<img class="rounded-pill" src="<?php echo $_smarty_tpl->tpl_vars['_oComment']->value['db_profile']['avatar'];?>
" width="30" height="30" />
			</div>
			<div class="awe__comment-item-body">
				<div class="bg-lighter p-2 rounded-2">
					<div class="awe__comment-profile d-flex align-items-center gap-1 mb-1">
						<h4 class="awe__comment-name text-bold mb-0 fs-14"><?php echo $_smarty_tpl->tpl_vars['_oComment']->value['db_profile']['name'];?>
</h4>
							<?php if ($_smarty_tpl->tpl_vars['news_author_id']->value > 0 && $_smarty_tpl->tpl_vars['_oComment']->value['profile_id'] == $_smarty_tpl->tpl_vars['news_author_id']->value) {?><span class="awe__comment-author-badge">Tác giả</span><?php }?>
						<span class="awe__comment-time text-muted fs-11"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getTimeAgo($_smarty_tpl->tpl_vars['_oComment']->value['reg_date']);?>
</span>
					</div>
					<div class="awe__comment-content">
						<?php if ($_smarty_tpl->tpl_vars['_oComment']->value['is_image'] == 1) {?>
							<img class='radius-4' src='<?php echo $_smarty_tpl->tpl_vars['_oComment']->value['image'];?>
' title='comment' style='max-width:350px'>
						<?php } else { ?>
							<?php echo nl2br(html_entity_decode($_smarty_tpl->tpl_vars['_oComment']->value['content']));?>

						<?php }?>
					</div>
				</div>
				<div class="awe__comment-item-action d-flex align-items-center">
					<a href="javascript:void(0);" class="control-action <?php echo $_smarty_tpl->tpl_vars['_oComment']->value['liked'];?>
" onclick="$Core.comment.like(this, event)" comment_id="<?php echo $_smarty_tpl->tpl_vars['_comment_id']->value;?>
" data-name="like" ><i class="bx bx-like me-1" style="<?php if ($_smarty_tpl->tpl_vars['_oComment']->value['liked']) {?>color:#e24b4a<?php }?>"></i><span class="awe__comment-total-liked"><?php echo $_smarty_tpl->tpl_vars['clsComment']->value->genTotalLike($_smarty_tpl->tpl_vars['_comment_id']->value);?>
</span></a>					
					<a href="javascript:void(0);" class="awe__comment-reply-button" onClick="$Core.news.comment_reply(this, event)" table_id="<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" clsTable="<?php echo $_smarty_tpl->tpl_vars['clsTable']->value;?>
" comment_id="<?php echo $_smarty_tpl->tpl_vars['_comment_id']->value;?>
"><span><i class='fa fa-angle-down'></i> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Reply');?>
</span></a>
				</div>
				<div class="awe__comment-reply-form awe__comment-reply-form-<?php echo $_smarty_tpl->tpl_vars['_comment_id']->value;?>
"></div>
				<div class="awe__comment-reply-wrapper awe__comment-reply-wrapper-<?php echo $_smarty_tpl->tpl_vars['_comment_id']->value;?>
"></div>
				<?php if ($_smarty_tpl->tpl_vars['_oComment']->value['total_replys'] > '0') {?>
				<div class="awe__comment-reply-cmd py-1">
					<a href="javascript:void(0);" onClick="$Core.news.view_reply(this, event)" table_id="<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
" clsTable="<?php echo $_smarty_tpl->tpl_vars['clsTable']->value;?>
" comment_id="<?php echo $_smarty_tpl->tpl_vars['_comment_id']->value;?>
" class="awe__comment-reply-viewall" data-total="<?php echo $_smarty_tpl->tpl_vars['_oComment']->value['total_reply'];?>
"><span class="awe__comment-reply-total"><?php echo $_smarty_tpl->tpl_vars['_oComment']->value['total_replys'];?>
</span> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('reply');?>
</a>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php if ($_smarty_tpl->tpl_vars['total_page']->value > $_smarty_tpl->tpl_vars['page']->value) {?>
	<div class="d-flex justify-content-start py-2">
		<a href="javascript:void(0);" rel="nofollow" page="<?php echo $_smarty_tpl->tpl_vars['page']->value+1;?>
" onClick="$Core.news.load_comments(<?php echo $_smarty_tpl->tpl_vars['table_id']->value;?>
, '<?php echo $_smarty_tpl->tpl_vars['clsTable']->value;?>
', {'action' : 'more','page':<?php echo $_smarty_tpl->tpl_vars['page']->value+1;?>
})" class="btn-more awe__comment-showmorethis">
			<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-down','Hiển thị thêm bình luận');?>

		</a>
	</div>  
	<?php }
} else { ?>
	<div class="text-center no-comment">
		<div class="text-center">
			<!--?xml version="1.0" encoding="UTF-8"?-->
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="60px" height="60px" viewBox="0 0 16 16" version="1.1">
				<title>comment-discussion</title>
				<g id="Octicons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="comment-discussion" fill="#999"><path d="M15 1H6c-.55 0-1 .45-1 1v2H1c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h1v3l3-3h4c.55 0 1-.45 1-1V9h1l3 3V9h1c.55 0 1-.45 1-1V2c0-.55-.45-1-1-1zM9 11H4.5L3 12.5V11H1V5h4v3c0 .55.45 1 1 1h3v2zm6-3h-2v1.5L11.5 8H6V2h9v6z" id="Shape"><path></path></path></g></g>
				<metadata><rdf:rdf xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" xmlns:dc="http://purl.org/dc/elements/1.1/"><rdf:description about="https://iconscout.com/legal#licenses" dc:title="comment,discussion" dc:description="comment,discussion" dc:publisher="Iconscout" dc:date="2017-09-14" dc:format="image/svg+xml" dc:language="en"><dc:creator><rdf:bag>
				<rdf:li>Github</rdf:li></rdf:bag></dc:creator></rdf:description></rdf:rdf>
				</metadata>
			</svg>
			<p class="type--subdued"><?php if ($_smarty_tpl->tpl_vars['_LANG_ID']->value == 'vn') {?>Chưa có bình luận<?php } else { ?>There are no comments<?php }?></p>
		</div>
	</div>
<?php }
}
}
