<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:52:31
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/banner/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315efc39bc6_39807948',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3dd6343c9b914540f64a6805984fc17787d51b7a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/banner/index.tpl',
      1 => 1785927026,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315efc39bc6_39807948 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" class="alert alert-warning px-lg-4 mb-2" 

	style="background:linear-gradient(to right, <?php echo $_smarty_tpl->tpl_vars['arr_color']->value['bgcolor'];?>
, color-mix(in srgb, <?php echo $_smarty_tpl->tpl_vars['arr_color']->value['bgcolor'];?>
 60%, white 30%), color-mix(in srgb,<?php echo $_smarty_tpl->tpl_vars['arr_color']->value['bgcolor'];?>
 30%, white 70%))!important">

	<div class="form-row">

		<div class="col-12 col-lg-5 wXqKYnaIvN mb-2 mb-lg-0">

			<?php $_smarty_tpl->_assignInScope('greeting', $_smarty_tpl->tpl_vars['clsISO']->value->get_greeting());?>

			<div class="greeting d-flex align-items-center gap-2">

				<div class="greeting__emoji mb-2 mb-lg-0">

					<img src="<?php echo $_smarty_tpl->tpl_vars['greeting']->value['emoji'];?>
" class="w-px-50" />

				</div>

				<div class="greeting__text" style="color:<?php echo $_smarty_tpl->tpl_vars['arr_color']->value['color'];?>
!important">

					<h1 class="mb-1 fw-bold text-fs-20">Chào <?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullName($_smarty_tpl->tpl_vars['profile_id']->value,$_smarty_tpl->tpl_vars['oneProfile']->value);?>
</h1>

					<div class="text-fs-14 xs:text-fs-12"><?php echo $_smarty_tpl->tpl_vars['greeting']->value['wellcome'];?>
</div>

				</div>

			</div>

		</div>

		<div class="col-12 col-lg-7 uFoxaAAvgL">

			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'computer') {?>

			<p class="fs-12 fst-italic mb-1 text-white text-right">

				Hôm nay <?php echo $_smarty_tpl->tpl_vars['today']->value;?>
 (tức <?php echo $_smarty_tpl->tpl_vars['luna_date']->value;?>
 Âm lịch)

			</p>

			<?php }?>

			<div class="d-flex flex-column align-items-end fs-5 w-100" style="color:<?php echo $_smarty_tpl->tpl_vars['arr_color']->value['color'];?>
!important">

				<div class="d-flex gap-1 align-items-center position-relative px-4">

					<i class="bx bxs-quote-alt-left position-absolute top-0 left-0"></i> 

					<i class="text-center lh-lg xs:text-left xs:text-fs-16"><?php echo $_smarty_tpl->tpl_vars['oneQuote']->value['content'];?>
.</i>

					<i class="bx bxs-quote-alt-right position-absolute bottom-0 right-0"></i>

				</div>

				<?php if (!empty($_smarty_tpl->tpl_vars['oneQuote']->value['author'])) {?>

				<div class="w-100 text-right text-fs-12 text-white">-- <?php echo $_smarty_tpl->tpl_vars['oneQuote']->value['author'];?>
</div>

				<?php }?>

			</div>

		</div>

	</div>

</div>

<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkSale() && $_smarty_tpl->tpl_vars['transactions_configs']->value['days_since_sold'] > '5' && $_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

<div class="call-to-action mb-2 rounded-2 p-3" style="background:<?php echo $_smarty_tpl->tpl_vars['oneColor']->value['bgcolor'];?>
">

	<div class="d-flex gap-3 align-items-center position-relative">

		<div class="days-container position-relative">

			<div class="counter-bg"></div>

			<div class="days-number" style="color:<?php echo $_smarty_tpl->tpl_vars['oneColor']->value['color'];?>
"><?php echo $_smarty_tpl->tpl_vars['transactions_configs']->value['days_since_sold'];?>
</div>

		</div>

		<div class="days-label d-flex flex-column gap-1 lh-base" style="color:<?php echo $_smarty_tpl->tpl_vars['oneColor']->value['color'];?>
">

			<span class="fst-italic"><?php echo $_smarty_tpl->tpl_vars['oneColor']->value['message'];?>
</span>

			<strong class="fst-italic fs-6">Chốt thôi !!! </strong>

		</div>

		<svg class="animated-icon text-main position-absolute right-0 bottom-0" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16">

			<path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>

		</svg>

	</div>

</div>

<?php }?>

<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkSale() && !empty($_smarty_tpl->tpl_vars['oneColorShare']->value) && $_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

<div class="call-to-action mb-2 rounded-2 p-3" style="background:<?php echo $_smarty_tpl->tpl_vars['oneColorShare']->value['bgcolor'];?>
">

	<div class="d-flex gap-3 align-items-center  justify-content-center position-relative">

		<div class="days-container position-relative">

			<div class="counter-bg"></div>

			<div class="days-number" style="color:<?php echo $_smarty_tpl->tpl_vars['oneColorShare']->value['color'];?>
"><?php echo $_smarty_tpl->tpl_vars['oneColorShare']->value['total_share'];?>
</div>

		</div>

		<div class="days-label d-flex flex-column gap-1 lh-base text-center fs-16 fw-bold" style="color:<?php echo $_smarty_tpl->tpl_vars['oneColorShare']->value['color'];?>
;max-width: calc(100% - 60px)">

			<span class="fst-italic"><?php echo $_smarty_tpl->tpl_vars['oneColorShare']->value['message'];?>
</span>

		</div>

	</div>

</div>

<?php }
}
}
