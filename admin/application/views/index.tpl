{if $act_image  eq 'act_image' or $mod eq 'editor' or $act eq 'license'}
	{$core->getModule($mod,$sub,$act)}
{else}
	{if $mod ne 'login'}
		<!DOCTYPE html
			PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<title>Admin {$clsConfiguration->getValue('site_name')}</title>
			<!-- META TAG -->
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<meta name='robots' content='noindex,nofollow' />
			<meta name="viewport" content="width=device-width, initial-scale=1">
			{assign var=faviconUrl value=$clsConfiguration->getValue('Favicon')}
			<link rel="shortcut icon" href="{if $faviconUrl}{$faviconUrl|escape}{else}{$DOMAIN_URL}/favicon.ico{/if}?v={$upd_version}" type="image/x-icon" />
			<link rel="preconnect" href="https://fonts.googleapis.com">
			<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
			<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
			<link rel="stylesheet" href="{$URL_CSS}/bootstrap.css?v={$upd_version}" type="text/css" media="all">
			<link rel="stylesheet" href="{$URL_JS}/ui/jquery-ui-1.8.18.custom.css?v={$upd_version}" type="text/css" media="all">
			<link rel="stylesheet" href="{$URL_CSS}/admin.css?v={$upd_version}" type="text/css" media="all">
			<link rel="stylesheet" href="{$URL_JS}/alertify/alertify.core.css?v={$upd_version}" type="text/css" media="all">
			<link rel="stylesheet" href="{$URL_JS}/alertify/alertify.default.css?v={$upd_version}" type="text/css" media="all">
			<link rel="stylesheet" href="{$URL_CSS}/fonts.min.css?v={$upd_version}" type="text/css" media="all">
			<link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" type="text/css" media="all">
			<link rel="stylesheet" href="{$URL_CSS}/chosen.css?v={$upd_version}" type="text/css" media="all">
			<link rel="stylesheet" type="text/css" href="{$URL_CSS}/select2.min.css?v={$upd_version}" />
			<link rel="stylesheet" href="{$URL_CSS}/animate.min.css?v={$upd_version}" type="text/css" media="all">
			<link rel="stylesheet" href="{$URL_JS}/easyUI/themes/gray/easyui.css?v={$upd_version}" type="text/css" media="all" />
			<link rel="stylesheet" href="{$URL_JS}/MultiDatesPicker/css/mdp.css?v={$upd_version}" type="text/css" media="all" />
			<link rel="stylesheet" href="{$URL_CSS}/owl.carousel.css?v={$upd_version}" type="text/css" media="all">
			<link rel="stylesheet" href="{$URL_CSS}/{$mod}.css?v={$upd_version}" type="text/css" media="all">
			<!--Style-->
			<script type="text/javascript" src="{$URL_JS}/jquery-1.9.1.min.js?v={$upd_version}"></script>
			<script type="text/javascript">
				var $Core = {},
					$_document = $(document);
			</script>
			<script type="text/javascript" src="{$URL_JS}/ui/jquery-ui-1.11.2.custom.min.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/jquery-migrate-1.2.1.min.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/alertify/alertify.min.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/jquery.form.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/jquery.price_format.1.8.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/isoTextArea.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/easyUI/jquery.easyui.min.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/store.min.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/select2.full.min.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/jquery-ui-timepicker-addon.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/MultiDatesPicker/jquery-ui.multidatespicker.js?v={$upd_version}">
			</script>
			<script type="text/javascript" src="{$PCMS_URL}/editor/tiny_mce/tiny_mce.js?v={$upd_version}"></script>
			{if $mod eq 'report'}
				<script type="text/javascript" src="{$URL_JS}/jquery.canvasjs.min.js?v={$upd_version}"></script>
			{/if}
			<script type="text/javascript">
				var path_ajax_script = '{$PCMS_URL}';
				var URL_JS = "{$URL_JS}"; 
				var URL_IMAGES = "{$URL_IMAGES}";
				var REQUEST_URI = "{$REQUEST_URI}"; 
				var type= "{$mod}";
				var mod= "{$mod}";
				var act= "{$act}";
				var _DEV= "{$dev}";
				var pvalTable="{$pvalTable}";
				var error = '{$core->get_Lang("error")}';
				var confim_delete = '{$core->get_Lang("confirm_delete")}';
				var confirm_delete = '{$core->get_Lang("confirm_delete")}';
				var confirm_cloning = '{$core->get_Lang("confirm_cloning")}';
				var confirm_reset = '{$core->get_Lang("confirm_reset")}';
				var user_group_required = '{$core->get_Lang("user_group_required")}';
				var username_required = '{$core->get_Lang("username_required")}';
				var field_required = '{$core->get_Lang("field_required")}';
				var field_is_required = '{$core->get_Lang("field_required")}';
				var field_is_link = '{$core->get_Lang("field_link")}';
				var title_required = '{$core->get_Lang("title_required")}';
				var password_required = '{$core->get_Lang("password_required")}';
				var confirm_password_required = '{$core->get_Lang("confirm_password_required")}';
				var confirm_password_in_valid = '{$core->get_Lang("confirm_password_in_valid")}';
				var confirm_replication = '{$core->get_Lang("confirm_replication")}';
				var full_name_required = '{$core->get_Lang("full_name_required")}';
				var insert_success = '{$core->get_Lang("insert_success")}';
				var insert_error = '{$core->get_Lang("insert_error")}';
				var insert_error_exist = '{$core->get_Lang("insert_error_exist")}';
				var exist_error = '{$core->get_Lang("exist_error")}';
				var update_success = '{$core->get_Lang("update_success")}';
				var update_error = '{$core->get_Lang("update_error")}';
				var delete_success = '{$core->get_Lang("delete_success")}';
				var delete_error = '{$core->get_Lang("delete_error")}';
				var reset_success = '{$core->get_Lang("reset_success")}';
				var user_name_invalid = '{$core->get_Lang("user_name_invalid")}';
				var password_invalid = '{$core->get_Lang("password_invalid")}';
				var loading = "{$core->get_Lang('loading')}";
				var Select = "{$core->get_Lang('select')}";
				var save = "{$core->get_Lang('Save')}";
				var datepickerformat = 'dd/mm/yy',
					crm_datepicker_format = {
						changeMonth: true,
						changeYear: true,
						showButtonPanel: true,
						yearRange: "1900:2050",
						dateFormat: datepickerformat
					},
				_MEMBER_PARKAGE_FREE_ID = '{$smarty.const._MEMBER_PARKAGE_FREE_ID}',
				_MEMBER_PARKAGE_VIP_ID = '{$smarty.const._MEMBER_PARKAGE_VIP_ID}',
				_MEMBER_PARKAGE_VVIP_ID = '{$smarty.const._MEMBER_PARKAGE_VVIP_ID}',
				_STOCK_TYPE_LEASING = '{$smarty.const._STOCK_TYPE_LEASING}',
				_BLOCK_TYPE_LOWFLOOR_SALE = '{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}',
				_BLOCK_TYPE_HIGHLEVEL_SALE = '{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}';
			</script>
			{literal}
				<script type="text/javascript">
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
				</script>
			{/literal}
			<script type="text/javascript" src="{$URL_JS}/jquery.smartTab.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$DOMAIN_NAME}/core/isoman/js/jquery.cookie.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$DOMAIN_NAME}/core/isoman/js/man.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/admin.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/iconpicker.js?v={$upd_version}"></script>
			<script type="text/javascript" src="{$URL_JS}/owl.carousel.js?v={$upd_version}"></script>
			<link rel="stylesheet" href="/core/isoman/css/skin.css?v={$upd_version}" type="text/css" media="all">
			<link rel="stylesheet" href="{$URL_CSS}/admin-redesign.css?v={$upd_version}" type="text/css" media="all">
		</head>
		<body class="ltr{if $mod=='setting'} mod-setting{/if}" id="wrapper">
			<div class="ui-app-frame">
				{$core->getHeader($mod,'_header')}
				{$core->getModule($mod,$sub,$act)}
				{$core->getHeader($mod,'_footer')}
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
			{literal}
				<script id="modal-message" type="text/template">
		<div class="modal-header"><button type="button" class="close" data-dismiss="modal">×</button><h5 class="modal-title">{{title}}</h5></div><div class="modal-body"><p>{{message}}</p></div>
	</script>
			{/literal}
			{literal}
				<script id="modal-success" type="text/template">
		<div class="modal-body text-center"><div class="big-icon success"><i class="fa fa-thumbs-o-up fa-3x"></i></div><h4>{{title}}</h4><p class="mt20">{{message}}</p></div>
	</script>
			{/literal}
			{literal}
				<script id="modal-error" type="text/template">
		<div class="modal-body text-center"><div class="big-icon error"><i class="fa fa-times fa-3x"></i></div><h4>{{title}}</h4><p class="mt20">{{message}}</p></div>
	</script>
			{/literal}
			{literal}
				<script id="modal-confirm" type="text/template">
		<div class="modal-header"><h5 class="modal-title">{{title}}</h5></div><div class="modal-body"><p>{{message}}</p></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Trở lại</button><button type="button" class="btn btn-primary" id="modal-confirm-ok">Xác Nhận</button></div>
	</script>
			{/literal}
			<!-- Insert script mod -->
			{$core->getScript($mod, $act, 'js')}
			<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"
				integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous">
			</script>
		</body>
		</html>
	{else}
		{$core->getModule($mod,$sub,$act)}
	{/if}
{/if}