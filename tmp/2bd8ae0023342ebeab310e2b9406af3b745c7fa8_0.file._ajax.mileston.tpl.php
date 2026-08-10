<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:44:14
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/billing/_ajax.mileston.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7697fe38fe27_92823627',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2bd8ae0023342ebeab310e2b9406af3b745c7fa8' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/billing/_ajax.mileston.tpl',
      1 => 1784299633,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7697fe38fe27_92823627 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),1=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/function.cycle.php','function'=>'smarty_function_cycle',),));
if (!empty($_smarty_tpl->tpl_vars['lstBilling']->value)) {?>
	<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstBilling']->value, '_oItem', false, 'key', 'i', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
?>
		<?php $_smarty_tpl->_assignInScope('oneStaff', $_smarty_tpl->tpl_vars['_oItem']->value['oneStaff']);?>
		<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null) && $_smarty_tpl->tpl_vars['current_page']->value == '1') {?>
		<div class="item_mileston item_big border-double  text-dark mb-3">
			<div class="form-row">
				<div class="col-9 flex-fill">
					<div class="item_header d-flex align-items-center">
						<div class="billing_code px-3 py-2 fw-bold">#<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->parseNumber2($_smarty_tpl->tpl_vars['_oItem']->value['bill_number']);?>
</div>
						<div class="time_deposit fw-bold text-black fst-italic"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['_oItem']->value['deposit_date'],"%d/%m/%Y");?>
</div>
					</div>
					<div class="item_body">
						<div class="staff_info d-flex align-items-center">
							<div class="border-double img_avatar rounded-pill overflow-hidden"><img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value,100,100);?>
" alt="" class="w-100 h-100 object-fit-cover"></div>
							<div class="flex-fill">
								<h3 class="name"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getfullname($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value);?>
</h3>
								<?php if (!empty($_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'])) {?>
								<p class="role"><?php echo $_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'];?>
</p>
								<?php }?>
							</div>
						</div>
						<div class="bill_info <?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>border-bottom<?php }?>">
							<div class="bill_item">
								<span class="label_item">Dự án:</span>
								<strong class=""><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['project_name'];?>
</strong>
							</div>
							<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['bedroom'])) {?>
							<div class="bill_item">
								<span class="label_item">Loại căn:</span>
								<strong><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['bedroom'];?>
</strong>
							</div>
							<?php }?>
							<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['type_villa'])) {?>
							<div class="bill_item">
								<span class="label_item">Loại căn:</span>
								<strong><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['type_villa'];?>
</strong>
							</div>
							<?php }?>
							<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['total_price'])) {?>
							<div class="bill_item">
								<span class="label_item">Doanh số :</span>
								<strong><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['total_price'];?>
</strong>
							</div>
							<?php }?>
						</div>
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
						<div class="content_item"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['content'];?>
</div>
						<?php }?>
					</div>
				</div>
				<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['image_poster'])) {?>
				<div class="col-3">
					<div class="box_poster">
						<img src="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['image_poster'];?>
" alt="" class="w-100 object-fit-cover rounded-3 border-double" data-fancybox="poster" data-src="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['image_poster'];?>
" data-caption='<div class="staff_info d-flex justify-content-center align-items-center gap-2" loading="lazy">
							<div class="avatar avatar-md rounded-pill overflow-hidden"><img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value);?>
" alt="" class="w-100 h-100 object-fit-cover"></div>
							<div class="text-left">
								<h3 class="name mb-1"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getfullname($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value);?>
</h3>
								<?php if (!empty($_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'])) {?>
								<p class="role mb-0"><?php echo $_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'];?>
</p>
								<?php }?>
							</div>
						</div>'>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
		<?php } else { ?>
		<li class="timeline-item <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'] : null) == 1) {?>timeline_item_first<?php }?>">
			<span class="timeline-indicator timeline-indicator-primary aos-init aos-animate p-1" data-aos="zoom-in" data-aos-delay="200">
			<img class="w-100" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/billing.png" width="30">
			</span>
			<div class="timeline-event card p-0 aos-init aos-animate" data-aos="<?php echo smarty_function_cycle(array('values'=>"fade-right,fade-left"),$_smarty_tpl);?>
">
				<div class="item_mileston item_small border-double text-dark h-100">
					<div class="form-row">
						<div class="col-8 flex-fill">
							<div class="item_header d-flex align-items-center">
								<div class="billing_code px-3 py-2 fw-bold">#<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->parseNumber2($_smarty_tpl->tpl_vars['_oItem']->value['bill_number']);?>
</div>
								<div class="time_deposit fw-bold text-black fst-italic"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['_oItem']->value['deposit_date'],"%d/%m/%Y");?>
</div>
							</div>
							<div class="item_body">
								<div class="staff_info d-flex align-items-center">
									<div class="border-double img_avatar rounded-pill overflow-hidden"><img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value,70,70);?>
" alt="" class="w-100 h-100 object-fit-cover" ></div>
									<div class="flex-fill">
										<h3 class="name"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getfullname($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value);?>
</h3>
										<?php if (!empty($_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'])) {?>
										<p class="role mb-1"><?php echo $_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'];?>
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
" alt="" class="w-100 object-fit-cover rounded-3 border-double" data-fancybox="poster" data-src="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['image_poster'];?>
" data-caption='<div class="staff_info d-flex justify-content-center align-items-center gap-2" loading="lazy">
								<div class="avatar avatar-md rounded-pill overflow-hidden"><img src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value);?>
" alt="" class="w-100 h-100 object-fit-cover"></div>
								<div class="text-left">
									<h3 class="name mb-1"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getfullname($_smarty_tpl->tpl_vars['_oItem']->value['staff_id'],$_smarty_tpl->tpl_vars['oneStaff']->value);?>
</h3>
									<?php if (!empty($_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'])) {?>
									<p class="role mb-0"><?php echo $_smarty_tpl->tpl_vars['oneStaff']->value['more_information']['department_name'];?>
</p>
									<?php }?>
								</div>
							</div>'>
							</div>
						</div>
						<?php }?>
					</div>
				</div>					
				<div class="timeline-event-time d-none"><div class="content_item fs-18 text-dark"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['content'];?>
</div></div>
			</div>
		</li>
		<?php }?>
	<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
} elseif ($_smarty_tpl->tpl_vars['page']->value > 1) {?>
	<div class="text-center text-center bg-white p-3 rounded-3" colspan="12">
		<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/listing-empty.svg" class="w-px-150">
		<p clas="text-muted">Danh sách trống</p>
	</div>
<?php }
}
}
