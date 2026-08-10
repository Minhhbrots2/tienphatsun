<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:51:33
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/contact_footer/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315b50c09e9_32209385',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2af86c082b25f45095b71be14b53215ee6fca6b7' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/contact_footer/index.tpl',
      1 => 1785927033,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315b50c09e9_32209385 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="col-12">
	<div class="card no-shadow mb-sm-2 mb-4 pb-sm-2 pb-4" >
		<?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
		<div class="card-body">		
			<div class="d-flex justify-content-center align-items-center gap-2 mb-2">
				<h3 class="card-title text-main d-flex align-items-center gap-2 justify-content-center mb-0">
					<i class='bx bxs-phone fs-20'></i> Đầu mối hỗ trợ
				</h3>
				<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('edit_contact_footer')) {?>
				<button class="btn btn-icon btn-sm top-0 right-0" type="button" onClick="$Core.contact.open(this,event)">
					<i class='bx bx-edit-alt'></i>
				</button>
				<?php }?>
			</div>
			<?php if (!empty($_smarty_tpl->tpl_vars['ContactFooter']->value)) {?>
			<div class="form-row row-cols-xxl-3 row-cols-xxxl-4 row-cols-lg-3">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['ContactFooter']->value, '_oContact', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oContact']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
				<div class="col<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null) > $_smarty_tpl->tpl_vars['number_show']->value) {?> toggleItem d-none<?php }?>">
					<div class="contact-box position-relative border mb-2 p-2 rounded-1">
						<div class="d-flex flex-column">
							<h3 class="text-fs-16 xs:text-fs-14 mb-2"><?php echo $_smarty_tpl->tpl_vars['_oContact']->value['title'];?>
</h3>
							<div class="d-flex align-items-center gap-1 gap-lg-2">
								<a class="d-flex align-items-center text-nowrap gap-1 contact-link text-link" target="_blank" 
									href="https://zalo.me/<?php echo $_smarty_tpl->tpl_vars['_oContact']->value['phone'];?>
">
									<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/zalo_chat.png" class="w-px-15" />
									<span class="text-fs-13"><?php echo $_smarty_tpl->tpl_vars['_oContact']->value['name'];?>
</span>
								</a>
								<a class="d-flex align-items-center gap-1 contact-link text-link" href="tel:<?php echo $_smarty_tpl->tpl_vars['_oContact']->value['phone'];?>
">
									<i class="bx bx-phone"></i>
									<span class="text-fs-13"><?php echo $_smarty_tpl->tpl_vars['_oContact']->value['phone'];?>
</span>
								</a>
							</div>
						</div>
					</div>
				</div>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</div>
			<div class="d-flex align-items-center justify-content-center">
				<button data-toggle="ripple" onClick="$Core.contact.collapsed(this, event)" 
					class="btn btn-sm px-3 btn-outline-default rounded-pill">
					<i class='bx bx-chevron-down'></i>
					<span>Xem thêm</span>
				</button>
			</div>
			<?php }?>
		</div>
	</div>
</div>

<style>
	.contact-link,
	.contact-link:hover{
		text-decoration:none;
	}
	.contact-link{
		background: #f5f5f5;
		padding: 2px 8px;
		border-radius: 20px;
	}
	.contact-box:after{
		content:'';
		position:absolute;
		top:9px; right:9px;
		width:10px; height:10px;
		background:#EEE;
		border-radius:50%;
	}
</style>

<?php }
}
