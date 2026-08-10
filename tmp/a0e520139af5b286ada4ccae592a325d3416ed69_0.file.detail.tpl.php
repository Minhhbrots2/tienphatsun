<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:54:40
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/detail.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ad9100afae7_99267004',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a0e520139af5b286ada4ccae592a325d3416ed69' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/detail.tpl',
      1 => 1784300230,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./../../map/project.tpl' => 1,
  ),
),false)) {
function content_6a6ad9100afae7_99267004 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-xxl flex-grow-1 pt-2 container-p-y"> 
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/thong-tin/">Thông tin dự án</a>
			</li>
			<li class="breadcrumb-item">
				<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,0,0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getCode($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['oneProject']->value);?>
</a>
			</li>
			<?php if ($_smarty_tpl->tpl_vars['show']->value == 'block') {?>
				<li class="breadcrumb-item active"><?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['title'];?>
</li>
			<?php } elseif ($_smarty_tpl->tpl_vars['show']->value == 'building' || $_smarty_tpl->tpl_vars['show']->value == 'map') {?>
				<?php if (!empty($_smarty_tpl->tpl_vars['block_id']->value)) {?>
					<li class="breadcrumb-item">
						<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['oneBlock']->value['property_id'],0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['title'];?>
</a>
					</li>
				<?php }?>
				<?php if (!empty($_smarty_tpl->tpl_vars['building_id']->value)) {?>
				<li class="breadcrumb-item active"><?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
</li>
				<?php }?>
			<?php }?>
		</ol>
	</nav>
	<div class="clearfix"></div>
	<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("banner_stock",array('total_stock'=>$_smarty_tpl->tpl_vars['total_stock']->value));?>

	<div class="clearfix"></div>
	<div id="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="d-none w-100 mb-3"><div class="box_info">
		<h3 class="fs-5 mb-2 fw-bold">Thông tin <?php echo $_smarty_tpl->tpl_vars['subfix']->value;?>
</h3>
		<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['attrs'])) {?>
		<div class="dqbGyrlVzN" id="lst_info">
			<div class="tinyContent fs-14" data-height="45px">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value['attrs'], '_oAttr');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oAttr']->value) {
?>
				<div class="item_info mb-1">
					<label for="" class="lbl_item"><?php echo $_smarty_tpl->tpl_vars['_oAttr']->value['title'];?>
</label>
					<span class="content_item text-dark fw-semibold"><?php echo $_smarty_tpl->tpl_vars['_oAttr']->value['content'];?>
</span>
				</div>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</div>
		</div>
		<?php }?>
	</div></div>
	<!-- End thông tin -->
	<div class="clearfix"></div>
	 <?php if ($_smarty_tpl->tpl_vars['show']->value == 'map') {?>
		<!-- Map -->
			<?php $_smarty_tpl->_subTemplateRender("file:./../../map/project.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('infoLocationAround'=>$_smarty_tpl->tpl_vars['infoLocationAround']->value,'location'=>$_smarty_tpl->tpl_vars['location']->value,'project'=>$_smarty_tpl->tpl_vars['project']->value), 0, false);
?>
		<!-- end Map -->
	<?php } else { ?>
	<div class="row justify-content-center">
		<div class="col-12 col-lg-8 col-xxl-9">
			<?php if ($_smarty_tpl->tpl_vars['cat_id']->value == @constant('_PROJECT_PROGRESS_CATID')) {?>
				<div class="tien-do-wrap p-3 card no-shadow">
					<?php if (!empty($_smarty_tpl->tpl_vars['list_progress']->value)) {?>
					<div class="pt-wrap">
						<!-- Cột trái: timeline tháng (mở ra ngày nếu mốc tiến độ có ngày d/m/Y) -->
						<div class="pt-timeline">
							<div class="pt-timeline-head"><i class="bx bx-loader-circle"></i> <?php if ($_smarty_tpl->tpl_vars['show']->value == 'project') {?>TIẾN ĐỘ DỰ ÁN<?php } else { ?>TIẾN ĐỘ<?php }?></div>
							<ul class="pt-list">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_progress']->value, '_m', false, NULL, 'mo', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_m']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['index'];
?>
								<?php $_smarty_tpl->_assignInScope('_days', $_smarty_tpl->tpl_vars['_m']->value['days']);?>
								<li class="pt-group">
									<div class="pt-item<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first'] : null)) {?> active<?php if (count($_smarty_tpl->tpl_vars['_days']->value) > 1) {?> open<?php }
}?>" data-pi="<?php echo $_smarty_tpl->tpl_vars['_days']->value[0]['pi'];?>
">
										<span class="pt-item-label"><?php echo $_smarty_tpl->tpl_vars['_m']->value['month_label'];?>
</span>
										<?php if (count($_smarty_tpl->tpl_vars['_days']->value) > 1) {?><span class="pt-item-meta"><?php echo count($_smarty_tpl->tpl_vars['_days']->value);?>
 đợt cập nhật</span><i class="bx pt-chev <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first'] : null)) {?>bx-chevron-up<?php } else { ?>bx-chevron-down<?php }?>"></i><?php } elseif (count($_smarty_tpl->tpl_vars['_days']->value[0]['media']) > 1) {?><span class="pt-item-meta"><?php echo count($_smarty_tpl->tpl_vars['_days']->value[0]['media']);?>
 hình ảnh/video</span><?php }?>
									</div>
									<?php if (count($_smarty_tpl->tpl_vars['_days']->value) > 1) {?>
									<div class="pt-subwrap<?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first'] : null)) {?> d-none<?php }?>">
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_days']->value, '_d', false, NULL, 'dy', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_d']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_dy']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_dy']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_dy']->value['index'];
?>
										<div class="pt-sub<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first'] : null) && (isset($_smarty_tpl->tpl_vars['__smarty_foreach_dy']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_dy']->value['first'] : null)) {?> active<?php }?>" data-pi="<?php echo $_smarty_tpl->tpl_vars['_d']->value['pi'];?>
"><i class="bx bx-calendar-check"></i><span class="pt-sub-t"><?php if ($_smarty_tpl->tpl_vars['_d']->value['day_label']) {
echo $_smarty_tpl->tpl_vars['_d']->value['day_label'];
} else { ?>Trong tháng<?php }?></span></div>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</div>
									<?php }?>
								</li>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</ul>
						</div>
						<!-- Cột phải: viewer + lưới ảnh (mỗi ngày 1 panel) -->
						<div class="pt-main">
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_progress']->value, '_m', false, NULL, 'mo', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_m']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['index'];
?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_m']->value['days'], '_d', false, NULL, 'dy', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_d']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_dy']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_dy']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_dy']->value['index'];
?>
							<div class="td-panel<?php if (!((isset($_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_mo']->value['first'] : null) && (isset($_smarty_tpl->tpl_vars['__smarty_foreach_dy']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_dy']->value['first'] : null))) {?> d-none<?php }?>" data-pi="<?php echo $_smarty_tpl->tpl_vars['_d']->value['pi'];?>
">
								<div class="pt-viewer-caption mb-3">
									<strong class="title_timeline position-relative pb-2">Tiến độ <?php echo mb_strtolower($_smarty_tpl->tpl_vars['_m']->value['month_label'], 'UTF-8');?>
</strong>
									<?php if ($_smarty_tpl->tpl_vars['_d']->value['day_label']) {?><span class="pt-date"><i class="bx bx-time-five"></i> Cập nhật <?php echo $_smarty_tpl->tpl_vars['_d']->value['day_label'];?>
</span><?php }?>
								</div>
								<?php if (!empty($_smarty_tpl->tpl_vars['_d']->value['video'])) {?>
								<div class="pt-viewer"><iframe src="<?php echo $_smarty_tpl->tpl_vars['_d']->value['video']['embed_url'];?>
" frameborder="0" allow="autoplay; encrypted-media; fullscreen" allowfullscreen></iframe></div>
								<?php } elseif (empty($_smarty_tpl->tpl_vars['_d']->value['media'])) {?>
								<div class="pt-viewer pt-viewer-empty"><i class="bx bx-image-alt"></i><span>Chưa có hình ảnh/video</span></div>
								<?php }?>
								<?php if (!empty($_smarty_tpl->tpl_vars['_d']->value['media'])) {?>
								<div class="pt-grid-head">Hình ảnh tiến độ</div>
								<div class="pt-grid">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_d']->value['media'], '_md');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_md']->value) {
?>
									<div class="pt-cell<?php if ($_smarty_tpl->tpl_vars['_md']->value['kind'] == 'video') {?> pt-cell-video<?php } elseif ($_smarty_tpl->tpl_vars['_md']->value['kind'] == 'document') {?> pt-cell-doc<?php }?>">
										<a data-fancybox="td<?php echo $_smarty_tpl->tpl_vars['_d']->value['pi'];?>
" data-type="<?php echo $_smarty_tpl->tpl_vars['_md']->value['fb_type'];?>
" href="<?php echo $_smarty_tpl->tpl_vars['_md']->value['fb_src'];?>
">
											<?php if ($_smarty_tpl->tpl_vars['_md']->value['thumb']) {?><img src="<?php echo $_smarty_tpl->tpl_vars['_md']->value['thumb'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['_md']->value['name'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png'"><?php } else { ?><span class="pt-noimg"><i class="bx <?php if ($_smarty_tpl->tpl_vars['_md']->value['kind'] == 'document') {?>bxs-file-pdf<?php } else { ?>bxs-image<?php }?>"></i></span><?php }?>
											<?php if ($_smarty_tpl->tpl_vars['_md']->value['kind'] == 'video') {?><span class="pt-play"><i class="bx bx-play"></i></span><?php }?>
											<?php if ($_smarty_tpl->tpl_vars['_md']->value['kind'] == 'document' && $_smarty_tpl->tpl_vars['_md']->value['thumb']) {?><span class="pt-doc"><i class="bx bxs-file-pdf"></i></span><?php }?>
										</a>
									</div>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</div>
								<?php }?>
							</div>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</div>
					</div>
					<?php } else { ?>
					<div class="d-flex justify-content-center"><div class="text-center p-4"><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-data.png" height="200"><p class="text-muted mt-n2">Chưa có dữ liệu tiến độ.</p></div></div>
					<?php }?>
				</div>
			<?php } elseif ($_smarty_tpl->tpl_vars['cat_id']->value == @constant('_PROJECT_DOCS_UTILITY_CATID')) {?>
				<?php if (!empty($_smarty_tpl->tpl_vars['list_utilities']->value)) {?>
					<div id="box_tab" class="box_tab mb-4">
						<div class="nav-align-top nav-tabs-shadow">
							<div class="w-100 overflow-x-auto mb-4">
								<ul class="nav nav-tabs" id="project_tab" role="tablist">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_utilities']->value, '_oCat', false, NULL, 'name_cat', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oCat']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_name_cat']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_name_cat']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_name_cat']->value['index'];
?>	
										<li class="nav-item" role="presentation">
											<a onClick="$Core.project.tab_click(this, event)" href="#<?php echo $_smarty_tpl->tpl_vars['core']->value->replaceSpace($_smarty_tpl->tpl_vars['_oCat']->value['title']);?>
" uri="<?php echo $_smarty_tpl->tpl_vars['curl']->value;?>
" slug="<?php echo $_smarty_tpl->tpl_vars['core']->value->replaceSpace($_smarty_tpl->tpl_vars['_oCat']->value['title']);?>
" id="tabclick_<?php echo $_smarty_tpl->tpl_vars['_oCat']->value['property_id'];?>
" class="btn btn_tab rounded-pill<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_name_cat']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_name_cat']->value['first'] : null)) {?> active<?php }?>" role="tab" data-bs-toggle="tab" data-bs-target="#utilities_<?php echo $_smarty_tpl->tpl_vars['_oCat']->value['property_id'];?>
" aria-controls="tab-pane_<?php echo $_smarty_tpl->tpl_vars['_oCat']->value['property_id'];?>
" aria-selected="true"><?php echo $_smarty_tpl->tpl_vars['_oCat']->value['title'];?>
</a>
										</li>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</ul>
							</div>
						</div>
						<div class="tab-content p-0">
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_utilities']->value, '_oCat', false, NULL, 'name_cat', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oCat']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_name_cat']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_name_cat']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_name_cat']->value['index'];
?>	
								<?php $_smarty_tpl->_assignInScope('_utilities', $_smarty_tpl->tpl_vars['_oCat']->value['utilities']);?>
								<div id="utilities_<?php echo $_smarty_tpl->tpl_vars['_oCat']->value['property_id'];?>
" class="tab-pane fade<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_name_cat']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_name_cat']->value['first'] : null)) {?> active show<?php }?>">
									<div class="tab_box mb-3">
										<div class="tab_body">
											<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4">
												<?php if (!empty($_smarty_tpl->tpl_vars['_utilities']->value)) {?> 
													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_utilities']->value, '_oUtilities', false, NULL, 'i', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oUtilities']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
														<?php if (!empty($_smarty_tpl->tpl_vars['_oUtilities']->value['image'])) {?>
															<div class="col mb-2 awe__doc-item">
																<a class="link awe__doc-link overflow-hidden rounded-2 position-relative" data-fancybox="utilities" data-src="<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['image'];?>
" data-caption="<h3 class='fs-20 mb-2'><?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['title'];?>
</h3> <?php echo html_entity_decode($_smarty_tpl->tpl_vars['_oUtilities']->value['content']);?>
">
																	<div class="awe__doc-img position-relative">
																		<div class="img-background" style="background-image:url('<?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['image'];?>
'), url('<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png')"></div>
																	</div>
																	<div class="position-absolute content_utilities text-white w-100 h-100">
																		<h4 class="fs-6 fw-bold line-clamp-2  lh-sm mb-2"><span class="limit_2line"><?php echo $_smarty_tpl->tpl_vars['_oUtilities']->value['title'];?>
</span></h4>
																		<div class="awe__utilities-text limit_4line"><?php echo html_entity_decode($_smarty_tpl->tpl_vars['_oUtilities']->value['content']);?>
</div>
																	</div>
																</a>
															</div>
														<?php }?>
													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												<?php } else { ?>
													<div class="d-flex justify-content-center">
														<div class="text-center p-4">
															<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-data.png" height="200">
															<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>
														</div>
													</div>
												<?php }?>
											</div>
										</div>
									</div>
								</div>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</div>					
					</div>
				<?php } else { ?>
					<div class="d-flex justify-content-center">
						<div class="text-center p-4">
							<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-data.png" height="200">
							<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>
						</div>
					</div>
				<?php }?>
			<?php } else { ?>
			<div id="box_tab" class="box_tab mb-4">
				<?php if (!empty($_smarty_tpl->tpl_vars['list_childs']->value)) {?>
					<div class="nav-align-top nav-tabs-shadow">
						<div class="w-100 overflow-x-auto mb-3">
							<ul class="nav nav-tabs" id="project_tab" role="tablist">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_childs']->value, '_oChild', false, 'key', 'i', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oChild']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
								<?php if (!empty($_smarty_tpl->tpl_vars['_oChild']->value['list_docs']) || ($_smarty_tpl->tpl_vars['_oChild']->value['property_id'] == @constant('_PROJECT_DOCS_PR_CATID') && !empty($_smarty_tpl->tpl_vars['_oChild']->value['list_posts']))) {?>
								<li class="nav-item" role="presentation">
									<a onClick="$Core.project.tab_click(this, event)" href="#<?php echo $_smarty_tpl->tpl_vars['_oChild']->value['slug'];?>
" uri="<?php echo $_smarty_tpl->tpl_vars['curl']->value;?>
" slug="<?php echo $_smarty_tpl->tpl_vars['_oChild']->value['slug'];?>
" id="tabclick_<?php echo $_smarty_tpl->tpl_vars['_oChild']->value['slug'];?>
" class="btn btn_tab rounded-pill<?php if ($_smarty_tpl->tpl_vars['_oChild']->value['is_active'] == '1') {?> active<?php }?>" role="tab" data-bs-toggle="tab" data-bs-target="#<?php echo $_smarty_tpl->tpl_vars['_oChild']->value['slug'];?>
" aria-controls="tab-pane_<?php echo $_smarty_tpl->tpl_vars['_oChild']->value['property_id'];?>
" aria-selected="true"><?php echo $_smarty_tpl->tpl_vars['_oChild']->value['title'];?>
</a>
								</li>
								<?php }?>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</ul>
						</div>
						<div class="tab-content p-0">
							<!-- Có danh mục -->
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_childs']->value, '_oChild', false, NULL, 'i', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oChild']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
							<?php $_smarty_tpl->_assignInScope('list_docs', $_smarty_tpl->tpl_vars['_oChild']->value['list_docs']);?>
							<div id="<?php echo $_smarty_tpl->tpl_vars['_oChild']->value['slug'];?>
" class="tab-pane fade<?php if ($_smarty_tpl->tpl_vars['_oChild']->value['is_active'] == '1') {?> active show<?php }?>">
								<div class="tab_box mb-3"><div class="tab_body">
									<?php if (!empty($_smarty_tpl->tpl_vars['list_docs']->value) || ($_smarty_tpl->tpl_vars['_oChild']->value['property_id'] == @constant('_PROJECT_DOCS_PR_CATID') && !empty($_smarty_tpl->tpl_vars['list_posts']->value))) {?>
									<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-4">
										<?php if (!empty($_smarty_tpl->tpl_vars['list_posts']->value) && $_smarty_tpl->tpl_vars['_oChild']->value['property_id'] == @constant('_PROJECT_DOCS_PR_CATID')) {?>
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_posts']->value, '_oDoc', false, 'k_doc', 'n_doc', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_doc']->value => $_smarty_tpl->tpl_vars['_oDoc']->value) {
?>
											<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['_oDoc']->value['more_information']);?>
											<div class="col mb-2 awe__doc-item">
												<a class="link awe__doc-link overflow-hidden rounded-2" href="<?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['content'];?>
" target="_blank">
													<div class="awe__doc-img position-relative">
														<div class="img-background" style="background-image:url('<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['image'];?>
'), url('<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png')"></div>
													</div>
													<h4 class="fs-13 line-clamp-2 text-center text-white lh-sm mb-0"><span class="limit_2line"><?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['title'];?>
</span></h4>
												</a>
											</div>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										<?php }?>
										<!-- End Post -->
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_docs']->value, '_oDoc', false, 'k_doc', 'n_doc', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_doc']->value => $_smarty_tpl->tpl_vars['_oDoc']->value) {
?>
										<div class="col mb-2">
											<?php $_smarty_tpl->_assignInScope('oneItem', $_smarty_tpl->tpl_vars['_oDoc']->value);?>
											<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('item_doc',array('_type'=>"detail",'oneItem'=>$_smarty_tpl->tpl_vars['oneItem']->value));?>

										</div>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</div>
									<?php } else { ?>
										<div class="d-flex justify-content-center">
											<div class="text-center p-4">
												<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-data.png" height="200">
												<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>
											</div>
										</div>
									<?php }?>
								</div></div>
							</div>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</div>
					</div>
				<?php } else { ?>
				<!-- Không có danh mục -->
				<!-- Layout -->
				<?php if ($_smarty_tpl->tpl_vars['cat_id']->value == @constant('_PROJECT_DOCS_LAYOUT_CATID')) {?>
				<div class="tab_box mb-4">
					<div class="nav-align-top nav-tabs-shadow">
						<?php if ($_smarty_tpl->tpl_vars['show']->value == 'project') {?>
							<div class="w-100 overflow-x-auto hide-scroll-thumb mb-3">
								<ul class="nav nav-tabs dragable flex-nowrap" role="tablist">
									<li class="nav-item" role="presentation">
										<a class="btn text-nowrap btn_tab active rounded-pill" role="tab">Tổng thể</a>
									</li>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>
									<li class="nav-item" role="presentation">
										<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkInfo($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['_oBlock']->value['property_id'],0,@constant('_PROJECT_DOCS_LAYOUT_CATID'));?>
" title="Mặt bằng <?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
" class="btn btn_tab rounded-pill text-nowrap"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</a>
									</li>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</ul>
							</div>
							<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('project_map',array('more_information'=>$_smarty_tpl->tpl_vars['more_information']->value));?>

						<?php } elseif ($_smarty_tpl->tpl_vars['show']->value == 'block') {?>
							<?php if ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>
								<div class="w-100 hide-scroll-thumb overflow-x-auto mb-4">
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item" role="presentation">
											<a class="btn btn_tab active rounded-pill" role="tab">Tổng thể</a>
										</li>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_oBuilding');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBuilding']->value) {
?>
										<li class="nav-item" role="presentation">
											<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkInfo($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['block_id']->value,$_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'],@constant('_PROJECT_DOCS_LAYOUT_CATID'));?>
" title="Mặt bằng <?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
" class="btn btn_tab rounded-pill"><?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
</a>
										</li>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</ul>
								</div>
							<?php } else { ?>
								<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('project_map',array('more_information'=>$_smarty_tpl->tpl_vars['more_information']->value));?>

							<?php }?>
							<div class="img-container zoom-wrapper relative rounded-1 overflow-hidden">
								<div id="panzoom-container" class="d-block panzoom-container">
									<img class="img-fluid w-100" src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['more_information']->value['layout_ns']);?>
" />
								</div>
								<div class="zoom-cmd d-flex flex-column">
									<a id="zoom-in" title="Zoom in" class="zoom-in"></a>
									<a id="zoom-out" title="Zoom out" class="zoom-out"></a>
								</div>
								<div class="zoom-map">
									<map name="positionMap" class="positionMapClass">
										<area id="topPositionMap" shape="rect" coords="20,0,40,20" title="move up" alt="move up">
										<area id="leftPositionMap" shape="rect" coords="0,20,20,40" title="move left" alt="move left">
										<area id="rightPositionMap" shape="rect" coords="40,20,60,40" title="move right" alt="move right">
										<area id="bottomPositionMap" shape="rect" coords="20,40,40,60" title="move bottom" alt="move bottom">
									</map>
									<img src="https://myoceancity.vn/application/themes/images/SmartZoom/position.png" usemap="#positionMap">  
								</div>
							</div>
						<?php } else { ?>
							<div class="w-100 overflow-x-auto mb-4">
								<ul class="nav nav-tabs" role="tablist">
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_layouts']->value, '_oLayout', false, 'key', 'i', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oLayout']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
									<li class="nav-item" role="presentation">
										<a class="btn btn_tab rounded-pill<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?> active<?php }?>" role="tab" data-bs-toggle="tab" data-bs-target="#tab-layout_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" aria-controls="tab-pane_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" aria-selected="true"><?php echo $_smarty_tpl->tpl_vars['_oLayout']->value['title'];?>
</a>
									</li>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								</ul>
							</div>
							<div class="tab-content p-0">
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_layouts']->value, '_oLayout', false, 'key', 'i', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oLayout']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
								<div id="tab-layout_<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" class="tab-pane fade<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?> active show<?php }?>">
									<a data-fancybox class="d-block img-container rounded-1 overflow-hidden" data-src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_oLayout']->value['image']);?>
">
										<img class="img-fluid w-100" src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['_oLayout']->value['image']);?>
" />
									</a>
								</div>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</div>
						<?php }?>
					</div>
				</div>
				<?php }?>
				<!-- End Layout -->
				<div class="clearfix"></div>
				<div class="tab_box mb-3">
					<div class="tab_body">
						<?php if (!empty($_smarty_tpl->tpl_vars['list_policy']->value)) {?>
						<div class="form-row mb-2">
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_policy']->value, '_oDoc', false, 'k_doc', 'n_doc', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_doc']->value => $_smarty_tpl->tpl_vars['_oDoc']->value) {
?>
							<div class="col-12 col-lg-6 col-xxxl-4 mb-2 awe__doc-item">
								<a class="link awe__doc-link overflow-hidden rounded-2" data-fancybox href="<?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['image'];?>
">
									<div class="awe__doc-img">
										<div class="img-background h-px-600" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['image'];?>
),url(<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png)"></div>
									</div>
									<h4 class="fs-13 text-center line-clamp-2 text-white lh-sm mb-0">
										<span class="limit_2line"><?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['title'];?>
</span>
									</h4>
								</a>
							</div>	
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</div>
						<?php }?>
						<?php if (!empty($_smarty_tpl->tpl_vars['list_docs']->value) || !empty($_smarty_tpl->tpl_vars['list_policy_docs']->value)) {?>
							<?php if ($_smarty_tpl->tpl_vars['view_doc']->value == "grid") {?>
							<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-4">
								<?php if (!empty($_smarty_tpl->tpl_vars['list_docs']->value)) {?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_docs']->value, '_oDoc', false, 'k_doc', 'n_doc', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_doc']->value => $_smarty_tpl->tpl_vars['_oDoc']->value) {
?>
									<div class="col mb-2">
										<?php $_smarty_tpl->_assignInScope('oneItem', $_smarty_tpl->tpl_vars['_oDoc']->value);?>
										<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('item_doc',array('_type'=>"detail",'oneItem'=>$_smarty_tpl->tpl_vars['oneItem']->value));?>

									</div>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
								<?php if (!empty($_smarty_tpl->tpl_vars['list_policy_docs']->value)) {?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_policy_docs']->value, '_oDoc', false, NULL, 'n_doc', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oDoc']->value) {
?>
									<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['_oDoc']->value['more_information']);?>
									<div class="col mb-2 awe__doc-item">
										<a class="link awe__doc-link overflow-hidden rounded-2" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['link_ns'];?>
">
											<div class="awe__doc-img">
												<div class="img-background" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['image'];?>
), url(<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png)"></div>
											</div>
											<h4 class="fs-13 text-white line-clamp-2 text-center lh-sm mb-0"><span class="limit_2line"><?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['title'];?>
 (Áp dụng <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['_oDoc']->value['ms_date']);?>
)</span></h4>
										</a>
									</div>	
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
							</div>
							<?php } else { ?>
								<?php if (!empty($_smarty_tpl->tpl_vars['list_docs']->value)) {?>
									<div class="d-flex align-items-center justify-content-between py-3 mb-2">
										<h3 class="mb-0 fs-5 text-dark"><?php echo $_smarty_tpl->tpl_vars['oneCat']->value['title'];?>
 <?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['title'];?>
</h3>
									</div>
									<div class="lst_docs form-row">
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_docs']->value, '_oDoc', false, 'k_doc', 'n_doc', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k_doc']->value => $_smarty_tpl->tpl_vars['_oDoc']->value) {
?>
											<?php $_smarty_tpl->_assignInScope('_list_images', $_smarty_tpl->tpl_vars['_oDoc']->value['list_images']);?>
											<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['_oDoc']->value['more_information']);?>
											<div class="col-12 col-xxl-6 mb-2">
												<div class="border rounded-2 bg-white item_doc d-flex h-100 <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>py-2<?php } else { ?>p-3<?php }?>" >
													<div class="img_item_doc p-2 position-relative">
														<a href="<?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['content'];?>
" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['image'];?>
" alt="" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png'" width="100" height="120" class="w-100 h-100 object-fit-cover position-relative zindex-1 rounded-2"></a>
													</div>
													<div class="content_item_doc flex-fill <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>py-2<?php }?> px-3 d-flex <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> gap-1 <?php } else { ?> gap-3<?php }?> position-relative flex-column align-items-end justify-content-center">
														<div class="content1 w-100">
															<h3 class="title_item_doc mb-2 <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>fs-16<?php }?>"><a href="<?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['content'];?>
" target="_blank" class="limit_2line text-dark"><?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['title'];?>
</a></h3>
															<div class="intro <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>fs-13 limit_2line<?php } else { ?>limit_3line<?php }?>"><?php echo html_entity_decode($_smarty_tpl->tpl_vars['_more_information']->value['intro']);?>
</div>
														</div>
														<a href="<?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['content'];?>
" target="_blank" class="text-link text-decoration-underline text-nowrap">Xem tài liệu</a>
													</div>
												</div>
											</div>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</div>
								<?php }?>
							<?php }?>
						<?php } else { ?>
							<?php if ($_smarty_tpl->tpl_vars['cat_id']->value == @constant('_PROJECT_DOCS_LAYOUT_CATID') && (empty($_smarty_tpl->tpl_vars['list_layouts']->value) && !empty($_smarty_tpl->tpl_vars['more_information']->value['layout_ns']))) {?>
							<div class="d-flex justify-content-center">
								<div class="text-center p-3">
									<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-data.png" height="200">
									<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>
								</div>
							</div>
							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['cat_id']->value != @constant('_PROJECT_DOCS_LAYOUT_CATID')) {?>
							<div class="d-flex justify-content-center">
								<div class="text-center p-3">
									<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-data.png" height="200">
									<p class="text-muted mt-n2">Xin lỗi. Chưa có dữ liệu trong thư mục này!</p>
								</div>
							</div>
							<?php }?>
						<?php }?>
						<?php if (!empty($_smarty_tpl->tpl_vars['list_policy_blocks']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_policy_blocks']->value, '_oBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>
							<?php $_smarty_tpl->_assignInScope('list_policy_docs', $_smarty_tpl->tpl_vars['_oBlock']->value['list_policy_docs']);?>
							<?php if (!empty($_smarty_tpl->tpl_vars['list_policy_docs']->value)) {?>
							<div class="tab_box mb-2">
								<div class="tab_header d-flex align-items-center justify-content-between mb-3">
									<h3 class="title_box mb-0 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</h3>
								</div>
								<div class="tab_body">
									<div class="form-row row-cols-2 row-cols-lg-3 row-cols-xl-4">
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_policy_docs']->value, '_oDoc', false, NULL, 'n_doc', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oDoc']->value) {
?>
										<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['_oDoc']->value['more_information']);?>
										<div class="col mb-2 awe__doc-item">
											<a class="link awe__doc-link overflow-hidden rounded-2" target="_blank" href="<?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['link_ns'];?>
">
												<div class="awe__doc-img">
													<div class="img-background" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['_more_information']->value['image'];?>
), url(<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png)"></div>
												</div>
												<h4 class="fs-13 line-clamp-2 text-white text-center lh-sm mb-0"><?php echo $_smarty_tpl->tpl_vars['_oDoc']->value['title'];?>
 (Áp dụng <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['_oDoc']->value['ms_date']);?>
)</h4>
											</a>
										</div>	
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</div>
								</div>
							</div>
							<?php }?>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					</div>
				</div>
				<!-- End không có danh mục -->
			<?php }?>
			</div>
			<?php }?>
		</div>
		<div class="col-12 col-lg-4 col-xxl-3">			
			<?php if (($_smarty_tpl->tpl_vars['show']->value == "project" || ($_smarty_tpl->tpl_vars['stock_type']->value == @constant('_BLOCK_TYPE_LOWFLOOR_SALE') && $_smarty_tpl->tpl_vars['show']->value == 'block')) && !empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>
			<div class="box_block no-shadow position-sticky " style="top:60px">
				<div class="d-flex align-items-center justify-content-between py-3">
					<h3 class="mb-0 fs-5 text-dark">Phân khu</h3>
				</div>
				<div class="list_item">
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock', false, NULL, 'i', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
					<?php $_smarty_tpl->_assignInScope('_more_information', $_smarty_tpl->tpl_vars['_oBlock']->value['more_information']);?>
						<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['_oBlock']->value['property_id'],0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
" title="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
" class="d-flex align-items-center gap-2 text-dark mb-2 border rounded-2 p-2 item_sidebar">
							<div class="box_image rounded-2 overflow-hidden">
								<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getImageWH($_smarty_tpl->tpl_vars['_oBlock']->value['image'],40,40);?>
" class="w-100 h-100 img-cover" width="40" height="40" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png'">
							</div>
							<div class="box_content w-60 px-2 flex-fill">
								<h3 class="title_item mb-0 fs-18"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</h3>
								<div class="d-flex align-items-center gap-1 fs-12">
									<span class="text-muted">Loại hình:</span> <span class=""><?php if ($_smarty_tpl->tpl_vars['_oBlock']->value['parent_id'] == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>Cao tầng<?php } else { ?>Thấp tầng<?php }?></span>
								</div>
							</div>
						</a>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</div>
			</div>
			<?php } elseif ($_smarty_tpl->tpl_vars['show']->value == 'block' || $_smarty_tpl->tpl_vars['show']->value == 'building') {?>
				<?php if (!empty($_smarty_tpl->tpl_vars['list_buildings']->value)) {?>
				<div class="box_building no-shadow position-sticky p-3 card no-shadow" style="top:60px">
					<div class="d-flex align-items-center justify-content-between py-3">
						<h3 class="mb-0 fs-5 text-dark">Tòa nhà (<?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['title'];?>
)</h3>
					</div>
					<div class="list_item">
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_buildings']->value, '_oBuilding', false, NULL, 'i', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBuilding']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
							<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkInfo($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['block_id']->value,$_smarty_tpl->tpl_vars['_oBuilding']->value['property_id'],$_smarty_tpl->tpl_vars['cat_id']->value);?>
" title="<?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
" class="d-flex align-items-center gap-2 text-dark mb-2 border rounded-2 p-2 item_sidebar">
								<div class="box_image rounded-2 overflow-hidden">
									<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getImageWH($_smarty_tpl->tpl_vars['_oBuilding']->value['image'],40,40);?>
" class="w-100 h-100 img-cover" width="40" height="40" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png'">
								</div>
								<div class="box_content w-60 px-2 flex-fill">
									<h3 class="title_item mb-0 fs-18">Tòa <?php echo $_smarty_tpl->tpl_vars['_oBuilding']->value['title'];?>
</h3>
								</div>
							</a>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					</div>
					<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>
						<div class="d-flex align-items-center justify-content-between py-3">
							<h3 class="mb-0 fs-5 text-dark">Phân khu khác</h3>
						</div>
						<div class="list_item">
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock', false, NULL, 'i', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
								<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['_oBlock']->value['property_id'],0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
" title="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
" class="d-flex align-items-center gap-2 text-dark mb-2 border rounded-2 p-2 item_sidebar">
									<div class="box_image rounded-2 overflow-hidden">
										<img src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getImageWH($_smarty_tpl->tpl_vars['_oBlock']->value['image'],40,40);?>
" class="w-100 h-100 img-cover" width="40" height="40" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-image.png'">
									</div>
									<div class="box_content w-60 px-2 flex-fill">
										<h3 class="title_item mb-0 fs-18"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</h3>
										<div class="d-flex align-items-center gap-1 fs-12">
											<span class="text-muted">Loại hình:</span> <span class=""><?php if ($_smarty_tpl->tpl_vars['_oBlock']->value['parent_id'] == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>Cao tầng<?php } else { ?>Thấp tầng<?php }?></span>
										</div>
									</div>
								</a>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</div>
					<?php }?>
				</div>
				<?php }?>
				
			<?php }?>
			<!-- End Block -->
		</div>
	</div>
	<?php }?>
</div>

<?php echo '<script'; ?>
 type="text/javascript">
	const elem = document.getElementById('panzoom-container');
	const zoomInButton = document.getElementById('zoom-in');
	const zoomOutButton = document.getElementById('zoom-out');
	//const resetButton = document.getElementById('reset');
	if(elem && elem.innerHTML.length){
		const panzoom = Panzoom(elem, {
			animate : true,
			canvas : true,
			minScale: 0.75
		});
		const parent = elem.parentElement;
		// No function bind needed
		parent.addEventListener('wheel', panzoom.zoomWithWheel);
		zoomInButton.addEventListener('click', panzoom.zoomIn);
		zoomOutButton.addEventListener('click', panzoom.zoomOut);
		// resetButton.addEventListener('click', panzoom.reset);
	}
	$(function(){
		if(!$Core.util.isEmpty(location.hash)){
			const _hash = location.hash;
			clearTimeout(_timeOut);
			_timeOut = setTimeout(() => {
				// $(`a#tabclick_${_hash}`).trigger('click');
				$(`#project_tab a[href="${_hash}"]`).tab('show');
			}, 100);
		}
		$('[data-fancybox]').fancybox({
			buttons : ['zoom','download','close'],
			afterShow : function(instance, current) {
				var _src = current.src;
				if(_src.indexOf('drive.google.com') >= 0){
					var _gid = _src.split(/id=(.*)\&sz=(.*)/)[1],
						_url = `https://drive.usercontent.google.com/download?id=${_gid}&export=download&authuser=0`;
				} else {
					_url = $.trim(_src);
				}
				$("[data-fancybox-download]").attr('href', _url);
				let _timeout;
				/*$_document.on('touchstart mousedown', '.fancybox-image', (ev) => {
					 if (ev.type === "touchstart" && ev.touches && ev.touches.length > 1) {
						return; 
					}
					_timeout = setTimeout(() => {
						var _link = document.createElement("a"),
							_src = $(this).attr('src');
						if(_src.indexOf('drive.google.com') >= 0){
							var _gid = _src.split(/id=(.*)\&sz=(.*)/)[1],
								_src = `https://drive.usercontent.google.com/download?id=${_gid}&export=download&authuser=0`;
						} else {
							_src = $.trim(_src);
						}
						_link.href = _src;
						_link.download = "";
						_link.click();
					}, 2000);
				});*/
				$_document.on('touchend mouseup mouseleave', '.fancybox-image', (ev) => {
					clearTimeout(_timeout);
				});
			}, beforeShow: function(instance, current){}
		});
	});
	function scrollDown(_this, e){
		e.preventDefault();
		var toId = $(_this).attr('toId'),
			topM = $('.'+toId).offset().top;
		$('html,body').animate({scrollTop:topM}, 500);
		return false;
	}
<?php echo '</script'; ?>
>
<?php }
}
