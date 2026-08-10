<?php
/* Smarty version 3.1.33, created on 2026-07-30 13:50:12
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/property.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6af424a57a84_91061719',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '04ef50ae1f1466df5b6c173ab078c7923b0a977a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/property.tpl',
      1 => 1784691752,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6af424a57a84_91061719 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="ui-title-bar-container">

	<div class="ui-title-bar">

		<div class="ui-title-bar__navigation">

			<div class="ui-breadcrumbs">

				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default ui-breadcrumb">

					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>


					<span class="ui-breadcrumb__item"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Setting');?>
</span>

				</a>

			</div>

		</div>

	</div>

	<div class="ui-title-bar ui-title-bar--separator">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group justify-content-between">

				<h1 class="ui-title-bar__title">Cài đặt thuộc tính 

					<?php if ($_smarty_tpl->tpl_vars['group']->value == 'crm') {?>

						khách hàng

					<?php } elseif ($_smarty_tpl->tpl_vars['group']->value == 'billing') {?>

						giao dịch						

					<?php } elseif ($_smarty_tpl->tpl_vars['group']->value == 'profile') {?>

						người dùng

					<?php } elseif ($_smarty_tpl->tpl_vars['group']->value == 'issue') {?>

						công việc

					<?php } elseif ($_smarty_tpl->tpl_vars['group']->value == 'okrs') {?>

						okrs

					<?php } elseif ($_smarty_tpl->tpl_vars['group']->value == 'fund') {?>

						thu/chi

					<?php } elseif ($_smarty_tpl->tpl_vars['group']->value == 'stock') {?>

						bảng hàng

					<?php } else { ?>

						chung

					<?php }?>

				</h1>

				<button type="button" onClick="$Core.property.storage_cache_all(this, event)" class="btn btn-default ml2" property_id="0"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Update Cache All'));?>
</button> 

			</div>

		</div>

	</div>

</div>

<div class="ui-layout">

	<div class="ui-layout__sections">

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstProperty_Type']->value, 'property', false, 'property_type');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['property_type']->value => $_smarty_tpl->tpl_vars['property']->value) {
?>

		<section name="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
" class="ui-annotated-section-container">

			<div class="ui-annotated-section">

				<div class="row">

					<div class="col-md-2">

						<div class="ui-annotated-section__title">

							<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['property']->value['name'];?>
</h2>

						</div>

						<div class="ui-annotated-section__description">

							<?php echo $_smarty_tpl->tpl_vars['property']->value['description'];?>


						</div>

						<button type="button" onClick="open_property(this)" class="btn btn-default" property_id="0" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus-circle',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew'));?>
</button>

						<button type="button" onClick="$Core.property.storage_cache(this, event)" class="btn btn-icon btn-default ml2" property_id="0" property_type="<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
" title="Cache"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('cloud');?>
</button> 

					</div>

					<div class="col-md-10">

						<div class="ui-annotated-section__content">

							<div class="next-card">

								<div class="next-card__section">

									<div class="holderPropertyType_<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
">

										Loading...

									</div>

									<?php echo '<script'; ?>
 type="text/javascript">

										load_list_property('<?php echo $_smarty_tpl->tpl_vars['property_type']->value;?>
');

									<?php echo '</script'; ?>
>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</section>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	</div>

</div>

<?php echo '<script'; ?>
>

	var agency_id = `<?php echo $_smarty_tpl->tpl_vars['agency_id']->value;?>
`;

<?php echo '</script'; ?>
>



<?php echo '<script'; ?>
 type="text/javascript">

	setTimeout(() => {

		if (window.location.hash) {

			var hash = window.location.hash;

			if ($(hash).length) {

				$('html, body').animate({

					scrollTop: $(hash).offset().top

				}, 900, 'swing');

			}

		}

		if(agency_id > 0){

			$("button[property_type='_AGENCY'][property_id='"+agency_id+"'][onclick='open_property(this)']").trigger("click");

		}

	}, 2000);

<?php echo '</script'; ?>
>

<?php }
}
