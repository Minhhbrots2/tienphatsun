<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:48:19
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.load_report_billing.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7698f387ac03_60840662',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bb68eca04f0db697d084b3ca280e4b9e0bf127a6' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/_ajax.load_report_billing.tpl',
      1 => 1784299653,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7698f387ac03_60840662 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="form-row">
	<div class="col-12 col-md-6 col-xxl-3 mb-2">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_total_billing"></span>
						<span class="">Tổng giao dịch</span>
					</div>
					<span class="fs-8 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['total_billings']->value;?>
 GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_total_grand"></span>
						<span class="">Tổng doanh số</span>
					</div>
					<span class="fs-8 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->shortNumber($_smarty_tpl->tpl_vars['total_grand']->value,1);?>
</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_primary"></span>
						<span class="">Sơ cấp</span>
					</div>
					<span class="fs-8 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['total_billings']->value-$_smarty_tpl->tpl_vars['total_trans_billings']->value;?>
 GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_transfer"></span>
						<span class="">Độc quyền</span>
					</div>
					<span class="fs-8 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['total_billing_dq']->value;?>
 GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_contract"></span>
						<span class="">Đã ký HĐMB</span>
					</div>
					<span class="fs-8 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['total_registed_hdmb']->value;?>
 GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_calendar"></span>
						<span class="">Có lịch ký HĐMB</span>
					</div>
					<span class="fs-8 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['total_unregisted_hdmb']->value;?>
 GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_no_calendar"></span>
						<span class="">Chưa có lịch ký</span>
					</div>
					<span class="fs-8 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['total_not_schedule_hdmb']->value;?>
 GD</span>
				</div>
				<div class="d-flex align-items-center justify-content-between ">
					<div class="d-flex gap-1 align-items-center">
						<span class="icon_bill icon_ratio"></span>
						<span class="">Tỷ lệ ký</span>
					</div>
					<span class="fs-8 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRateNumber($_smarty_tpl->tpl_vars['total_registed_hdmb']->value,$_smarty_tpl->tpl_vars['total_billings']->value);?>
%</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-6 col-xxl-9 mb-2">
		<?php if (!empty($_smarty_tpl->tpl_vars['lstDepChild']->value)) {?>
		<div class="lst_area_billing owl owl-carousel h-100" data-md-slide="2.3" data-sm-slide="1.5" data-xs-slide="1.5" data-lg-slide="4" 
			data-dots="1" data-nav="0" data-loop="0" data-margin="10">
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstDepChild']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
				<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['total_billing'])) {?>
					<?php $_smarty_tpl->_assignInScope('total_billing', $_smarty_tpl->tpl_vars['_oItem']->value['total_billing']);?>
					<?php $_smarty_tpl->_assignInScope('total_trans_billing', $_smarty_tpl->tpl_vars['_oItem']->value['total_trans_billings']);?>
					<?php $_smarty_tpl->_assignInScope('total_billing_dq', $_smarty_tpl->tpl_vars['_oItem']->value['total_billing_dq']);?>
				<?php } else { ?>
					<?php $_smarty_tpl->_assignInScope('total_billing', 0);?>
					<?php $_smarty_tpl->_assignInScope('total_trans_billing', 0);?>
					<?php $_smarty_tpl->_assignInScope('total_billing_dq', 0);?>
				<?php }?>
				<?php $_smarty_tpl->_assignInScope('rate', $_smarty_tpl->tpl_vars['clsISO']->value->getRateNumber($_smarty_tpl->tpl_vars['_oItem']->value['total_registed_hdmb'],$_smarty_tpl->tpl_vars['total_billing']->value));?>
				<div class="item_area_billing card h-100">
					<div class="card-header item_top pb-2" style="background-color: <?php echo $_smarty_tpl->tpl_vars['_oItem']->value['bgcolor'];?>
;color: <?php echo $_smarty_tpl->tpl_vars['_oItem']->value['textcolor'];?>
">
						<h4 class="mb-2"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</h4>
						<p class="mb-0 fs-20"><?php echo $_smarty_tpl->tpl_vars['total_billing']->value;?>
 GD</p>
					</div>
					<div class="card-body item_body p-2 pb-3">
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_total_grand"></span>
								<span class="">Tổng doanh số</span>
							</div>
							<span class="fs-8 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->shortNumber($_smarty_tpl->tpl_vars['_oItem']->value['totalgrand'],1);?>
</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_primary"></span>
								<span class="">Sơ cấp</span>
							</div>
							<span class="fs-8 fw-semibold"><?php echo $_smarty_tpl->tpl_vars['total_billing']->value-$_smarty_tpl->tpl_vars['total_trans_billing']->value;?>
 GD</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_transfer"></span>
								<span class="">Độc quyền</span>
							</div>
							<span class="fs-8 fw-semibold"><?php if (!empty($_smarty_tpl->tpl_vars['total_billing_dq']->value)) {
echo $_smarty_tpl->tpl_vars['total_billing_dq']->value;
} else { ?>0<?php }?> GD</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_contract"></span>
								<span class="">Đã ký HĐMB</span>
							</div>
							<span class="fs-8 fw-semibold"><?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['total_registed_hdmb'])) {
echo $_smarty_tpl->tpl_vars['_oItem']->value['total_registed_hdmb'];
} else { ?>0<?php }?> GD</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_calendar"></span>
								<span class="">Có lịch ký HĐMB</span>
							</div>
							<span class="fs-8 fw-semibold"><?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['total_unregisted_hdmb'])) {
echo $_smarty_tpl->tpl_vars['_oItem']->value['total_unregisted_hdmb'];
} else { ?>0<?php }?> GD</span>
						</div>
						<div class="d-flex align-items-center justify-content-between border-bottom pb-1 mb-1">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_no_calendar"></span>
								<span class="">Chưa có lịch ký</span>
							</div>
							<span class="fs-8 fw-semibold"><?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['total_not_schedule_hdmb'])) {
echo $_smarty_tpl->tpl_vars['_oItem']->value['total_not_schedule_hdmb'];
} else { ?>0<?php }?> GD</span>
						</div>
						<div class="d-flex align-items-center justify-content-between ">
							<div class="d-flex gap-1 align-items-center">
								<span class="icon_bill icon_ratio"></span>
								<span class="">Tỷ lệ ký</span>
							</div>
							<span class="fs-8 fw-semibold"><?php if (!empty($_smarty_tpl->tpl_vars['rate']->value)) {
echo $_smarty_tpl->tpl_vars['rate']->value;
} else { ?>0<?php }?>%</span>
						</div>
					</div>
				</div>
			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		</div>
		<?php }?>
	</div>
</div><?php }
}
