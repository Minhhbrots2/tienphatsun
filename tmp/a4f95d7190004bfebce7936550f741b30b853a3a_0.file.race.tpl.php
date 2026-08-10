<?php
/* Smarty version 3.1.33, created on 2026-08-06 09:31:10
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/campaign/race.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a73f1eec18577_05723807',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a4f95d7190004bfebce7936550f741b30b853a3a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/campaign/race.tpl',
      1 => 1785983456,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a73f1eec18577_05723807 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-xxl flex-grow-1 pt-2 container-p-y">
    <div class="col-12 col-md-8 offset-lg-2">
		<div class="alert alert-warning text-center mb-2">
			<a href="javascript:;" class="text-main d-flex font-bold text-upper align-items-center justify-content-center fs-20" campaign_id="<?php echo $_smarty_tpl->tpl_vars['oneCampaign']->value['campaign_id'];?>
"> <?php echo $_smarty_tpl->tpl_vars['oneCampaign']->value['title'];?>
</a>
			<ul class="countdown mb-0" data-end="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToTextFormat($_smarty_tpl->tpl_vars['end_date']->value);?>
 23:59:59">
				<li><span class="days">00</span>
					<p class="mb-0 days_text">ngày</p>
				</li>
				<li class="seperator">:</li>
				<li><span class="hours">00</span>
					<p class="mb-0 hours_text">giờ</p>
				</li>
				<li class="seperator">:</li>
				<li><span class="minutes">00</span>
					<p class="mb-0 minutes_text">phút</p>
				</li>
				<li class="seperator">:</li>
				<li><span class="seconds">00</span>
					<p class="mb-0 seconds_text">giây</p>
				</li>
			</ul>
		</div>
		<div class="iKuJnjIFyr template_2">
			<div class="hJsiGEcCOJ template_2">
				<table width="100%" class="table table-campaign text-wrap template_2">
					<thead><tr class="nohover">
						<th class="p_header text-center text-upper" colspan="10">
							<div class="mb-2">
								<img src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" width="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageWidth('LogoWhite');?>
" height="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageHeight('LogoWhite');?>
" alt="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['CompanyName'];?>
" />
							</div>
							<strong>Bảng xếp hạng Thi đua cá nhân <?php echo $_smarty_tpl->tpl_vars['current_year']->value;?>
</strong><br />
							(<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['start_date']->value);?>
-
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['end_date']->value);?>
)
						</th>
					</tr>
					<tr>
						<th class="p_head text-center">STT</th>
						<th class="p_head text-left text-wrap">Họ và tên</th>
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
						<th class="p_head text-center text-wrap">Tổng điểm</th>
						<th class="p_head text-center">Xếp loại</th>
						<?php } else { ?>
						<th class="p_head text-center text-wrap">Điểm</th>
						<?php }?>
					</tr></thead>
					<?php echo $_smarty_tpl->tpl_vars['html_table']->value;?>

				</table>
			</div>
		</div>
    </div>
</div><?php }
}
