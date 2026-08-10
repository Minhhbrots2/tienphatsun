<?php
/* Smarty version 3.1.33, created on 2026-08-06 08:51:34
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/setting.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a73e8a6419db8_58784085',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '86c220f5917f1b02e5ec53846632923655fe5a9d' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/setting.tpl',
      1 => 1785980760,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a73e8a6419db8_58784085 (Smarty_Internal_Template $_smarty_tpl) {
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
				<button type="button" onClick="$Core.setting.storage_cache_all(this, event)" class="btn btn-default ml2" setting_id="0"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Update Cache All'));?>
</button> 
			</div>
		</div>
	</div>
</div>
<div class="ui-layout">
	<div class="ui-layout__sections">
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstSetting_Type']->value, 'setting', false, 'setting_type');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['setting_type']->value => $_smarty_tpl->tpl_vars['setting']->value) {
?>
		<section name="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
" id="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
" class="ui-annotated-section-container">
			<div class="ui-annotated-section">
				<div class="row">
					<div class="col-md-12">
						<div class="ui-annotated-section__title">
							<h2 class="ui-heading"><?php echo $_smarty_tpl->tpl_vars['setting']->value['name'];?>
</h2>
						</div>
						<div class="ui-annotated-section__description">
							<?php echo $_smarty_tpl->tpl_vars['setting']->value['description'];?>

						</div>
						<button type="button" onClick="open_setting(this)" class="btn btn-default" setting_id="0" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus-circle',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew'));?>
</button>
						<button type="button" onClick="$Core.setting.storage_cache(this, event)" class="btn btn-icon btn-default ml2" setting_id="0" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
" title="Cache"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('cloud');?>
</button> 
					</div>
					<div class="col-md-12">
						<div class="ui-annotated-section__content">
							<div class="next-card">
								<div class="next-card__section">
									<div class="holderSettingType_<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
">
										Loading...
									</div>
									<?php echo '<script'; ?>
 type="text/javascript">
										load_list_setting('<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
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
	var typeInputDynamic = '<?php echo json_encode(@constant('_LIST_TYPE_ARRAY_HAS_OPTION'));?>
';
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
			$("button[setting_type='_AGENCY'][setting_id='"+agency_id+"'][onclick='open_setting(this)']").trigger("click");
		}
	}, 2000);
<?php echo '</script'; ?>
>
<?php }
}
