<?php
/* Smarty version 3.1.33, created on 2026-08-05 15:21:51
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/project_docs/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72f29f327402_03532537',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9cf8fc94e965813bfb9ee154498aeea51dc23194' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/project_docs/default.tpl',
      1 => 1784299667,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72f29f327402_03532537 (Smarty_Internal_Template $_smarty_tpl) {
echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/heic2any.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<div class="container-xxl flex-grow-1 container-p-y pt-2 store-document mb-sm-2" 
	data-src-no-image="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png" data-domain="<?php echo FH_URL;?>
">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
		<div class="pFJayTMEgu mb-2 mb-lg-0">
			<h4 class="fw-bold mb-1">Kho tài liệu dự án</h4>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0">
					<li class="breadcrumb-item">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
">Trang chủ</a>
					</li>
					<li class="breadcrumb-item">
						<a class="text-muted" href="/kho-tai-lieu.html">Kho tài liệu dự án</a>
					</li>
				</ol>
			</nav>
		</div>
		<div class="dthiWyHNwG xs:w-100 d-flex align-items-center">
			<div class="search w-100 d-flex align-items-center gap-1">
				<div class="d-none d-lg-block">
					<div class="d-flex align-items-center switch-custom-checkbox border rounded-2 gap-2">
						<label class="switch small">
							<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['doc_bookmarked']->value == '1') {?> checked="checked"<?php }?> value="1" onChange="$Core.document.set_bookmarked(this, event)" />
							<span class="slider round"></span>
						</label>
						<span>Chỉ hiển thị tài liệu tôi yêu thích</span>
					</div>
				</div>
				<div class="input-group input-group-merge w-px-250 xs:flex-fill">
					<span class="input-group-text" id="keysearch">
						<i class="icon-base bx bx-search"></i>
					</span>
					<input type="text" class="form-control search-keyword search_field pe-4" onkeydown="$Core.document.do_search(this, event)" 
					name="keyword" placeholder="Nhập từ khóa và nhấn Enter" aria-label="Tìm kiếm" data-field="keyword" >
				</div>
				<button type="button" id="toggleSidebar" class="btn btn-icon btn-outline-default d-sm-none">
					<i class='bx bx-filter-alt'></i>
				</button>
			</div>
		</div>
	</div>
	<div class="alert d-lg-none alert-warning">
		<div class="d-flex align-items-center gap-2">
			<label class="switch">
				<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['doc_bookmarked']->value == '1') {?> checked="checked"<?php }?>  value="1" 
					onChange="$Core.document.set_bookmarked(this, event)"/>
				<span class="slider round"></span>
			</label>
			<span>Chỉ hiển thị tài liệu tôi yêu thích</span>
		</div>
	</div>
    <div class="row box-document ">
        <div class="col-12 col-md-3 col-lg-3 col-xl-3 col-xxl-2 col-sm-12 sidebar-filter" id="sidebarFilter">
            <h4 class="d-sm-none mt-3">Danh sách lọc</h4>
            <button class="btn btn-outline-default btn-close-menu d-sm-none"><i class='bx bx-x'></i></button>
            <div class="card no-shadow mb-2 box-filter list_project_care">
                <div class="card-header d-flex align-items-center justify-content-between pe-1">
                    <h5 class="card-title m-0 me-2">Dự án</h5>
					<div class="d-flex gap-1 align-items-center">
						 <button type="button" class="btn btn-sm btn-icon btn-link collapsed" data-bs-toggle="modal" 
							data-bs-target="#careProject" aria-expanded="false" aria-controls="careProject">
								<i class="bx text-muted bx-cog"></i>
							</button>
						<button class="btn btn-icon btn-sm project-button" data-bs-toggle="collapse" 
							data-bs-target="#projectMenu" aria-expanded="true" aria-controls="projectMenu">
								<i class="bx bx-chevron-up toggle-card"></i>
						</button>
					</div>
                </div>
                <div id="projectMenu" class="card-body collapse show" style="line-height:2">
					<div class="animate-bg w-px-200 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-px-150 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-px-200 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div>
                </div>
            </div>
            <hr class="d-sm-none m-0" />
            <div class="card no-shadow mb-2 box-filter">
                <div class="card-header d-flex align-items-center justify-content-between pe-1">
                    <h5 class="card-title m-0 me-2">Danh mục</h5>
                    <button class="btn btn-sm category-button" data-bs-toggle="collapse" data-bs-target="#categoryMenu" 
						aria-expanded="true" aria-controls="categoryMenu">
                        <i class="bx bx-chevron-up toggle-card"></i>
                    </button>
                </div>
                <div id="categoryMenu" class="card-body collapse show">
                    <?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 5);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
					<div class="animate-bg w-px-200 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-px-150 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-px-200 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div>
					<?php
}
}
?>
                </div>
            </div>
            <div class="card no-shadow mb-2 d-none d-sm-block classTagsPage">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Tags</h5>
                </div>
                <div class="card-body tag_document area_document tag_need_toggle" data-length="30">
					<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = min(($__section_i_1_loop - 0), 5);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
					<div class="animate-bg w-px-200 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-px-150 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-px-200 h-px-15 mb-2 rounded-2"></div>
					<div class="animate-bg w-px-50 h-px-15 mb-2 rounded-2"></div>
					<?php
}
}
?>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-9 col-lg-9 col-xl-9 col-xxl-10 col-sm-12 classContentPage" >
            <div class="card no-shadow mb-2 h-100">
                <div class="card-header">
					<div class="area-filter d-flex align-items-center flex-wrap<?php if (!empty($_smarty_tpl->tpl_vars['keyword']->value) || !empty($_smarty_tpl->tpl_vars['arrTag']->value) || !empty($_smarty_tpl->tpl_vars['cat_id']->value) || !empty($_smarty_tpl->tpl_vars['project_property']->value)) {
} else { ?> d-none<?php }?>">
						<div class="group-filter d-flex align-items-center flex-wrap">
							<div class="tag_filter_document_item mr-1">
								<i class="bx bx-filter-alt"></i> 
								<span class="text-muted">Bộ lọc</span>
							</div>
							<div class="group-filter-keyword">
								<?php if (!empty($_smarty_tpl->tpl_vars['keyword']->value)) {?>
								<a class="tag_filter_document_item">
									<b>Tìm kiếm:</b> <?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
 <i class='bx bxs-tag-x' onClick="$Core.document.clear_keyword(this, event);"></i>
								</a>
								<?php }?>
							</div>
							<div class="group-filter-project"></div>
							<div class="group-filter-tags">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arrTag']->value, 'slug_tag', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['slug_tag']->value) {
?>
								<?php if (isset($_smarty_tpl->tpl_vars['arrTagDefault']->value[$_smarty_tpl->tpl_vars['slug_tag']->value])) {?>
									<?php $_smarty_tpl->_assignInScope('tag_name', $_smarty_tpl->tpl_vars['arrTagDefault']->value[$_smarty_tpl->tpl_vars['slug_tag']->value]);?>
									<a class="tag_filter_document_item tag_type_tag tag_<?php echo $_smarty_tpl->tpl_vars['slug_tag']->value;?>
 ms-2" data-tag-id="<?php echo $_smarty_tpl->tpl_vars['slug_tag']->value;?>
" data-tag-name="<?php echo $_smarty_tpl->tpl_vars['tag_name']->value;?>
" href="javascript:void(0);"><b>Tag:</b> <?php echo $_smarty_tpl->tpl_vars['tag_name']->value;?>
 <i class='bx bxs-tag-x' onClick="$Core.document.clear_tag(this, event);" data-tag-id="<?php echo $_smarty_tpl->tpl_vars['slug_tag']->value;?>
" data-tag-name="<?php echo $_smarty_tpl->tpl_vars['tag_name']->value;?>
"></i></a>
								<?php }?>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</div>
							<div class="group-filter-category">
								<?php if (!empty($_smarty_tpl->tpl_vars['cat_id']->value) && isset($_smarty_tpl->tpl_vars['arrListCat']->value[$_smarty_tpl->tpl_vars['cat_id']->value])) {?>
								<a class="tag_filter_document_item tag_type_category category-<?php echo $_smarty_tpl->tpl_vars['cat_id']->value;?>
 ms-2" href="javascript:void(0);">
									<b>Danh mục:</b> <?php echo $_smarty_tpl->tpl_vars['arrListCat']->value[$_smarty_tpl->tpl_vars['cat_id']->value]['title'];?>
 <i class='bx bxs-tag-x' onClick="$Core.document.clear_category(this, event);" data-cat-id="<?php echo $_smarty_tpl->tpl_vars['cat_id']->value;?>
"></i>
								</a>
								<?php }?>
							</div>
						</div>
					</div>
				</div>
                <div class="card-body holder_docs h-100">
					<div class="form-row row-cols-2 row-cols-md-4 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-5 gy-4">
						<?php
$__section_i_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_2_total = min(($__section_i_2_loop - 0), 30);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_2_total !== 0) {
for ($__section_i_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_2_iteration <= $__section_i_2_total; $__section_i_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<div class="col mb-2">
							<div class="item_document d-flex flex-column h-100 position-relative">
								<div class="img-square-wrapper cls-curso-point">
									<div class="animate-bg w-100 h-px-250"></div>
								</div>
								<div class="content_document mt-3">
									<div class="animate-bg w-100 rounded-2 mb-2 h-px-20"></div>
									<div class="animate-bg w-100 rounded-2 mb-2 h-px-15"></div>
									<div class="d-flex align-items-center gap-2 justify-content-between">
										<div class="animate-bg w-50 rounded-2 mb-2 h-px-15"></div>
										<div class="animate-bg w-50 rounded-2 mb-2 h-px-15"></div>
									</div>
									<div class="animate-bg w-50 rounded-2 mb-2 h-px-15"></div>
									<div class="animate-bg w-100 rounded-2 mb-2 h-px-15"></div>
									<div class="animate-bg w-100 rounded-2 h-px-15"></div>
								</div>
							</div>
						</div>
						<?php
}
}
?>
					</div>
				</div>
				<div class="clearfix"></div>
                <div id="pager_docs" class="simple-pagination py-4"></div>
            </div>
        </div>
		<div class="col-12 d-block d-sm-none mt-2">
            <div class="card no-shadow mb-2 classTagsPage">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Tags</h5>
                </div>
                <div class="card-body tag_document tag_need_toggle" data-length="30"></div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="current_page" value="<?php echo $_smarty_tpl->tpl_vars['current_page']->value;?>
">
<div class="modal fade" id="careProject" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-ipad-xl modal-enable-otp">
		<form method="post" class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Bạn quan tâm đến dự án nào?</h5>
				<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="group-project-care form-row row-cols-2 row-cols-lg-4">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, '_oProject');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProject']->value) {
?>
					<div class="col mb-2 item-project-care">
						<a data-toggle="ripple" class="btn w-100 h-px-150 btn-project-care p-3<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oProject']->value['project_id'],$_smarty_tpl->tpl_vars['arr_project_care']->value)) {?> active<?php }?>" onClick="$Core.document.select_this(this, event)" data-project_id="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
">
							<div class="logo mb-2 h-px-75 d-flex align-items-center justify-content-center mb-2">
								<img src="<?php echo FH_URL;
echo $_smarty_tpl->tpl_vars['_oProject']->value['logo'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
">
							</div>
							<div class="clearfix my-1"></div>
							<h3 class="text-fs-14"><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
</h3>
						</a>
					</div>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.document.store_project_setting(this, event)" 
					class="btn btn-primary">Tiếp tục </button>
			</div>
		</form>
	</div>
</div>
<?php echo $_smarty_tpl->tpl_vars['scriptJs']->value;
}
}
