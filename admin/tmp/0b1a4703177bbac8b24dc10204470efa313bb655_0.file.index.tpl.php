<?php
/* Smarty version 3.1.33, created on 2026-08-08 17:31:40
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a77058c1172d0_64449568',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0b1a4703177bbac8b24dc10204470efa313bb655' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/index.tpl',
      1 => 1786072757,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a77058c1172d0_64449568 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['act_image']->value == 'act_image' || $_smarty_tpl->tpl_vars['mod']->value == 'editor' || $_smarty_tpl->tpl_vars['act']->value == 'license') {?>
	<?php echo $_smarty_tpl->tpl_vars['core']->value->getModule($_smarty_tpl->tpl_vars['mod']->value,$_smarty_tpl->tpl_vars['sub']->value,$_smarty_tpl->tpl_vars['act']->value);?>

<?php } else { ?>
	<?php if ($_smarty_tpl->tpl_vars['mod']->value != 'login') {?>
		<!DOCTYPE html
			PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<title>Admin <?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('site_name');?>
</title>
			<!-- META TAG -->
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name='robots' content='noindex,nofollow' />
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<?php $_smarty_tpl->_assignInScope('faviconUrl', $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('Favicon'));?>
			<link rel="shortcut icon" href="<?php if ($_smarty_tpl->tpl_vars['faviconUrl']->value) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['faviconUrl']->value, ENT_QUOTES, 'UTF-8', true);
} else {
echo $_smarty_tpl->tpl_vars['DOMAIN_URL']->value;?>
/favicon.ico<?php }?>?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="image/x-icon" />
			<link rel="preconnect" href="https://fonts.googleapis.com">
			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
			<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/bootstrap.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/ui/jquery-ui-1.8.18.custom.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/admin.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/alertify/alertify.core.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/alertify/alertify.default.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/fonts.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" type="text/css" media="all">
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/chosen.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/select2.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/animate.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/easyUI/themes/gray/easyui.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all" />
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/MultiDatesPicker/css/mdp.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all" />
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/owl.carousel.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<!--Style-->
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery-1.9.1.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript">
				var $Core = {},
					$_document = $(document);
			<?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/ui/jquery-ui-1.11.2.custom.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery-migrate-1.2.1.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/alertify/alertify.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery.form.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery.price_format.1.8.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/isoTextArea.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/easyUI/jquery.easyui.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/store.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/select2.full.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery-ui-timepicker-addon.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/MultiDatesPicker/jquery-ui.multidatespicker.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
">
			<?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/editor/tiny_mce/tiny_mce.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'report') {?>
				<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery.canvasjs.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php }?>
			<?php echo '<script'; ?>
 type="text/javascript">
				var path_ajax_script = '<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
';
				var URL_JS = "<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
"; 
				var URL_IMAGES = "<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
";
				var REQUEST_URI = "<?php echo $_smarty_tpl->tpl_vars['REQUEST_URI']->value;?>
"; 
				var type= "<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
";
				var mod= "<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
";
				var act= "<?php echo $_smarty_tpl->tpl_vars['act']->value;?>
";
				var _DEV= "<?php echo $_smarty_tpl->tpl_vars['dev']->value;?>
";
				var pvalTable="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
";
				var error = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("error");?>
';
				var confim_delete = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("confirm_delete");?>
';
				var confirm_delete = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("confirm_delete");?>
';
				var confirm_cloning = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("confirm_cloning");?>
';
				var confirm_reset = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("confirm_reset");?>
';
				var user_group_required = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("user_group_required");?>
';
				var username_required = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("username_required");?>
';
				var field_required = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("field_required");?>
';
				var field_is_required = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("field_required");?>
';
				var field_is_link = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("field_link");?>
';
				var title_required = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("title_required");?>
';
				var password_required = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("password_required");?>
';
				var confirm_password_required = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("confirm_password_required");?>
';
				var confirm_password_in_valid = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("confirm_password_in_valid");?>
';
				var confirm_replication = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("confirm_replication");?>
';
				var full_name_required = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("full_name_required");?>
';
				var insert_success = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("insert_success");?>
';
				var insert_error = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("insert_error");?>
';
				var insert_error_exist = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("insert_error_exist");?>
';
				var exist_error = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("exist_error");?>
';
				var update_success = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("update_success");?>
';
				var update_error = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("update_error");?>
';
				var delete_success = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("delete_success");?>
';
				var delete_error = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("delete_error");?>
';
				var reset_success = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("reset_success");?>
';
				var user_name_invalid = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("user_name_invalid");?>
';
				var password_invalid = '<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("password_invalid");?>
';
				var loading = "<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('loading');?>
";
				var Select = "<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('select');?>
";
				var save = "<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Save');?>
";
				var datepickerformat = 'dd/mm/yy',
					crm_datepicker_format = {
						changeMonth: true,
						changeYear: true,
						showButtonPanel: true,
						yearRange: "1900:2050",
						dateFormat: datepickerformat
					},
				_MEMBER_PARKAGE_FREE_ID = '<?php echo @constant('_MEMBER_PARKAGE_FREE_ID');?>
',
				_MEMBER_PARKAGE_VIP_ID = '<?php echo @constant('_MEMBER_PARKAGE_VIP_ID');?>
',
				_MEMBER_PARKAGE_VVIP_ID = '<?php echo @constant('_MEMBER_PARKAGE_VVIP_ID');?>
',
				_STOCK_TYPE_LEASING = '<?php echo @constant('_STOCK_TYPE_LEASING');?>
',
				_BLOCK_TYPE_LOWFLOOR_SALE = '<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
',
				_BLOCK_TYPE_HIGHLEVEL_SALE = '<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
';
			<?php echo '</script'; ?>
>
			
				<?php echo '<script'; ?>
 type="text/javascript">
					$.datepicker.regional['vi'] = {
						closeText: 'Đóng',
						prevText: '&#x3c;Trước',
						nextText: 'Tiếp&#x3e;',
						currentText: 'Hôm nay',
						monthNames: ['Tháng Một', 'Tháng Hai', 'Tháng Ba', 'Tháng Tư', 'Tháng Năm', 'Tháng Sáu',
							'Tháng Bảy', 'Tháng Tám', 'Tháng Chín', 'Tháng Mười', 'Tháng Mười Một', 'Tháng Mười Hai'
						],
						monthNamesShort: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
							'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
						],
						dayNames: ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'],
						dayNamesShort: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
						dayNamesMin: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
						weekHeader: 'Tu',
						dateFormat: 'dd/mm/yy',
						firstDay: 0,
						isRTL: false,
						showMonthAfterYear: false,
						yearSuffix: ''
					};
					$.datepicker.setDefaults($.datepicker.regional['vi']);
					$(function() {
						$(".validate-form").validate();
					});
				<?php echo '</script'; ?>
>
			
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery.smartTab.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['DOMAIN_NAME']->value;?>
/core/isoman/js/jquery.cookie.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['DOMAIN_NAME']->value;?>
/core/isoman/js/man.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/admin.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/iconpicker.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/owl.carousel.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
			<link rel="stylesheet" href="/core/isoman/css/skin.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
			<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/admin-redesign.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="text/css" media="all">
		</head>
		<body class="ltr<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'setting') {?> mod-setting<?php }?>" id="wrapper">
			<div class="ui-app-frame">
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getHeader($_smarty_tpl->tpl_vars['mod']->value,'_header');?>

				<?php echo $_smarty_tpl->tpl_vars['core']->value->getModule($_smarty_tpl->tpl_vars['mod']->value,$_smarty_tpl->tpl_vars['sub']->value,$_smarty_tpl->tpl_vars['act']->value);?>

				<?php echo $_smarty_tpl->tpl_vars['core']->value->getHeader($_smarty_tpl->tpl_vars['mod']->value,'_footer');?>

			</div>
			<div id="modal" class="modal fade" style="z-index:5">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<div class="loader pt10 pb10"></div>
						</div>
					</div>
				</div>
			</div>
			
				<?php echo '<script'; ?>
 id="modal-message" type="text/template">
		<div class="modal-header"><button type="button" class="close" data-dismiss="modal">×</button><h5 class="modal-title">{{title}}</h5></div><div class="modal-body"><p>{{message}}</p></div>
	<?php echo '</script'; ?>
>
			
			
				<?php echo '<script'; ?>
 id="modal-success" type="text/template">
		<div class="modal-body text-center"><div class="big-icon success"><i class="fa fa-thumbs-o-up fa-3x"></i></div><h4>{{title}}</h4><p class="mt20">{{message}}</p></div>
	<?php echo '</script'; ?>
>
			
			
				<?php echo '<script'; ?>
 id="modal-error" type="text/template">
		<div class="modal-body text-center"><div class="big-icon error"><i class="fa fa-times fa-3x"></i></div><h4>{{title}}</h4><p class="mt20">{{message}}</p></div>
	<?php echo '</script'; ?>
>
			
			
				<?php echo '<script'; ?>
 id="modal-confirm" type="text/template">
		<div class="modal-header"><h5 class="modal-title">{{title}}</h5></div><div class="modal-body"><p>{{message}}</p></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Trở lại</button><button type="button" class="btn btn-primary" id="modal-confirm-ok">Xác Nhận</button></div>
	<?php echo '</script'; ?>
>
			
			<!-- Insert script mod -->
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getScript($_smarty_tpl->tpl_vars['mod']->value,$_smarty_tpl->tpl_vars['act']->value,'js');?>

			<?php echo '<script'; ?>
 src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"
				integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous">
			<?php echo '</script'; ?>
>
		</body>
		</html>
	<?php } else { ?>
		<?php echo $_smarty_tpl->tpl_vars['core']->value->getModule($_smarty_tpl->tpl_vars['mod']->value,$_smarty_tpl->tpl_vars['sub']->value,$_smarty_tpl->tpl_vars['act']->value);?>

	<?php }
}
}
}
