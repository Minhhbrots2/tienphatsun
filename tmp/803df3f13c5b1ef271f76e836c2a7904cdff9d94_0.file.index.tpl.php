<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:52:33
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/block_honor/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315f175a683_70065599',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '803df3f13c5b1ef271f76e836c2a7904cdff9d94' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/block_honor/index.tpl',
      1 => 1785927028,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315f175a683_70065599 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),1=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/function.math.php','function'=>'smarty_function_math',),));
if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value != 'share') {?>
<div class="form-row mb-2">
	<?php if (!empty($_smarty_tpl->tpl_vars['lstBilling']->value)) {?>
		<div class="col-12 col-md-4">
			<div class="card h-100" style="background-image: url('<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getImageWH('/application/themes/images/bg_mileston.png',535,500);?>
')">
				<div class="card-header">
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="card-title title_box mb-0">Milestone <?php echo smarty_modifier_date_format(time(),"%Y");?>
</h5>
						<?php if (!empty($_smarty_tpl->tpl_vars['_oBox']->value['link'])) {?>
						<a href="/m-<?php echo smarty_modifier_date_format(time(),'%Y');?>
/" class="btn btn-icon btn-sm btn-link rounded-pill">
							<i class="bx bx-link-external text-fs-14 text-muted"></i>
						</a>
						<?php }?>
					</div>
				</div>
				<div class="card-body d-flex align-items-center">
					<div id="home_<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
" class="owl owl-carousel owl-share" data-lg-slide="2" data-md-slide="1" 
						data-sm-slide="1" data-loop="1" data-nav="1" data-margin="10" >
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstBilling']->value, '_oItem', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
							<?php $_smarty_tpl->_assignInScope('oneStaff', $_smarty_tpl->tpl_vars['_oItem']->value['oneStaff']);?>
							<div class="item_mileston item_small border-double text-dark h-100">
								<div class="form-row">
									<div class="col-8 flex-fill">
										<div class="item_header d-flex align-items-center">
											<div class="billing_code px-2 py-1 fw-bold">#<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->parseNumber2($_smarty_tpl->tpl_vars['_oItem']->value['bill_number']);?>
</div>
											<div class="time_deposit fw-bold text-black fst-italic"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['_oItem']->value['deposit_date'],"%d/%m/%Y");?>
</div>
										</div>
										<div class="item_body">
											<div class="staff_info d-flex align-items-center">
												<div class="border-double img_avatar rounded-pill overflow-hidden"><img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value,70,70);?>
" alt="" class="w-100 h-100 object-fit-cover" ></div>
												<div class="flex-fill">
													<h3 class="name limit_1line" title="<?php echo $_smarty_tpl->tpl_vars['oneStaff']->value['full_name'];?>
"><?php echo $_smarty_tpl->tpl_vars['oneStaff']->value['full_name'];?>
</h3>
													<?php if (!empty($_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'])) {?>
													<p class="role mb-0"><?php echo $_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'];?>
</p>
													<?php }?>
												</div>
											</div>
											<div class="bill_info">
												<div class="bill_item">
													<span class="label_item">Dự án:</span>
													<strong class=""><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['project_name'];?>
</strong>
												</div>
												<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['bedroom'])) {?>
												<div class="bill_item">
													<span class="label_item">Loại căn:</span>
													<strong class=""><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['bedroom'];?>
</strong>
												</div>
												<?php }?>
												<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['type_villa'])) {?>
												<div class="bill_item">
													<span class="label_item">Loại căn:</span>
													<strong class=""><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['type_villa'];?>
</strong>
												</div>
												<?php }?>
												<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['total_price'])) {?>
												<div class="bill_item">
													<span class="label_item">Doanh số:</span>
													<strong class=""><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_price'];?>
</strong>
												</div>
												<?php }?>
											</div>
										</div>
									</div>
									<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['image_poster'])) {?>
									<div class="col-4">
										<div class="box_poster">
										<img src="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['image_poster'];?>
" alt="" class="w-100 object-fit-cover rounded-3 border-double image_poster" data-fancybox="poster" data-src="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['image_poster'];?>
" data-caption='<div class="staff_info d-flex justify-content-center align-items-center gap-2" loading="lazy">
											<div class="avatar avatar-md rounded-pill overflow-hidden"><img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value);?>
" alt="" class="w-100 h-100 object-fit-cover"></div>
											<div class="text-left">
												<h3 class="name mb-1"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getfullname($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value);?>
</h3>
												<?php if (!empty($_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'])) {?><p class="role mb-0"><?php echo $_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'];?>
</p><?php }?>
											</div>
										</div>'>
										</div>
									</div>
									<?php }?>
								</div>
							</div>	
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					</div>
				</div>
			</div>		
		</div>
	<?php }?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_boxs']->value, '_oBox', false, '_oKey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oKey']->value => $_smarty_tpl->tpl_vars['_oBox']->value) {
?>
	<?php $_smarty_tpl->_assignInScope('list_images', $_smarty_tpl->tpl_vars['_oBox']->value['images']);?>
	<div class="col-12 col-md-4">
		<div class="card h-100">
			<div class="card-header">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="card-title mb-0"><?php echo $_smarty_tpl->tpl_vars['_oBox']->value['title'];?>
</h5>
					<?php if (!empty($_smarty_tpl->tpl_vars['_oBox']->value['link'])) {?>
					<a href="<?php echo $_smarty_tpl->tpl_vars['_oBox']->value['link'];?>
" class="btn btn-icon btn-sm btn-link rounded-pill">
						<i class="bx bx-link-external text-fs-14 text-muted"></i>
					</a>
					<?php }?>
				</div>
			</div>
			<div class="card-body">
				<?php if (!empty($_smarty_tpl->tpl_vars['list_images']->value)) {?>
				<div id="home_<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
" class="owl_honor owl-carousel">
					<?php $_smarty_tpl->_assignInScope('number_image', 1);?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_images']->value, '_oImage', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oImage']->value) {
?>
						<div class="image-wrap rounded-1 overflow-hidden" data-fancybox="image" data-src="<?php echo $_smarty_tpl->tpl_vars['_oImage']->value;?>
&sz=w1500">
							<div class="w-100 img-home-honor" style="background-image:url(<?php echo $_smarty_tpl->tpl_vars['_oImage']->value;?>
&sz=w500)"></div>
						</div>
						<?php echo smarty_function_math(array('equation'=>"x+1",'x'=>$_smarty_tpl->tpl_vars['number_image']->value,'assign'=>"number_image"),$_smarty_tpl);?>

						<?php if ($_smarty_tpl->tpl_vars['number_image']->value > 20) {?>
							<?php break 1;?>
						<?php }?>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				</div>
				<?php } else { ?>
								<div class="dbx-empty">
					<span class="dbx-empty__ic"><i class="bx <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oBox']->value['icon'], ENT_QUOTES, 'UTF-8', true);?>
"></i></span>
					<div class="dbx-empty__t"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['_oBox']->value['empty'], ENT_QUOTES, 'UTF-8', true);?>
</div>
					<div class="dbx-empty__s">Ảnh sẽ hiển thị ở đây khi có cập nhật</div>
				</div>
				<?php }?>
			</div>
		</div>		
	</div>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div>
<?php } else { ?>
<div class="awe__list-post awe__list-share"> 
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_images']->value, '_oImage', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oImage']->value) {
?>
		<?php if (!empty($_smarty_tpl->tpl_vars['_oImage']->value)) {?>
		<div class="awe__post-item awe__share-item" >
			<div class="w-100 d-flex align-items-center justify-content-between mb-3 post_header">
				<div class="awe__post-profile d-flex" data-url="/index.php?mod=home&act=load_profile_popover&user_id=<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
" data-toggle="webui-popover" data-trigger="hover" data-width="350">
					<div class="awe__post-avatar position-relative">
						<img class="rounded-pill" width="44" height="44" src="<?php echo $_smarty_tpl->tpl_vars['oneUser']->value['avatar'];?>
" 
						onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" /> 
						<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->get_icon_verified($_smarty_tpl->tpl_vars['user_id']->value,$_smarty_tpl->tpl_vars['oneUser']->value['moreinformation']);?>

					</div>
					<div class="awe__post-profile-body">
						<p class="awe__post-name"><?php echo $_smarty_tpl->tpl_vars['oneUser']->value['name'];?>
</p>
						<div class="d-flex align-items-center">
							<?php if (!empty($_smarty_tpl->tpl_vars['oneUser']->value['level'])) {?>
							<span class="awe__post-level mr-2 text-muted"><?php echo $_smarty_tpl->tpl_vars['oneUser']->value['level'];?>
</span>
							<?php }?>
							<span class="awe__post-star mr-2 text-muted"><?php echo $_smarty_tpl->tpl_vars['oneUser']->value['html_star'];?>
</span>
						</div>
					</div>
				</div>
			</div>
			<div class="awe__post-item-body">
				<div class="awe__post-gallery gallery mb-2">
					<div  class="imgs-grid imgs-grid-1">
						<div class="imgs-grid-image">
							<div class="image-wrap" data-fancybox="image" data-src="<?php echo $_smarty_tpl->tpl_vars['_oImage']->value;?>
">
								<img class="w-100" src="<?php echo $_smarty_tpl->tpl_vars['_oImage']->value;?>
" alt="" title="" style="max-height:unset">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div>
<?php }?>

<style type="text/css">
	.img-home-honor{
		height:160px;
		background-repeat:no-repeat;
		background-size:cover;
		background-position:top;
	}
</style>
<?php }
}
