<?php
/* Smarty version 3.1.33, created on 2026-08-07 11:17:44
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a755c680c4159_87277779',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2d77e9a2c99d1869287edebd3dca26b317282acc' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/index.tpl',
      1 => 1786076261,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a755c680c4159_87277779 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'viewer') {?>
<html class="light-style layout-compact layout-menu-fixed layout-navbar-fixed" xmlns="http://www.w3.org/1999/xhtml" lang="vi" xml:lang="vi">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/bootstrap.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/owl.theme.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/owl.transitions.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/owl.carousel.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/font-awesome.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/style.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
		<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/viewer.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_THEMES']->value;?>
/vendor/libs/jquery/jquery.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript"> var $_document = $(document), $Core = {};<?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/underscore-min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery-migrate-1.2.1.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery-ui.1.11.0.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/store.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	</head>
	<body>
		<?php echo $_smarty_tpl->tpl_vars['core']->value->getModule($_smarty_tpl->tpl_vars['mod']->value,$_smarty_tpl->tpl_vars['sub']->value,$_smarty_tpl->tpl_vars['act']->value);?>

	</body>
</html>
<?php } else { ?>
	<!-- layout-menu-hover -->
	<html class="light-style layout-compact layout-navbar-fixed layout-menu-fixed layout-navbar-hidden" 
		xmlns="http://www.w3.org/1999/xhtml" lang="vi" xml:lang="vi">
	<head>
		<?php $_smarty_tpl->_assignInScope('dq_title', $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('docquyen_meta_title'));?>
	<?php $_smarty_tpl->_assignInScope('dq_desc', $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('docquyen_meta_description'));?>
	<?php $_smarty_tpl->_assignInScope('dq_share', $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('docquyen_image_share'));?>
	<title><?php echo preg_replace('!<[^>]*?>!', ' ', html_entity_decode($_smarty_tpl->tpl_vars['global_title_page']->value));?>
</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=100">
	<!---<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['sub']->value == 'default' && $_smarty_tpl->tpl_vars['act']->value == 'default') {?>,viewport-fit=cover<?php }?> -->
	<meta name="viewport" content=" initial-scale=1.0, maximum-scale=1, user-scalable=0">
	<meta name="Description" content="<?php if ($_smarty_tpl->tpl_vars['dq_desc']->value) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['dq_desc']->value, ENT_QUOTES, 'UTF-8', true);
} else {
echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['global_description_page']->value);
}?>" />
	<meta name="Keywords" content="<?php echo $_smarty_tpl->tpl_vars['global_keyword_page']->value;?>
" />
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="Cache-control" content="no-cache">
	<meta name="theme-color" content="#000000" />
	<meta name="googlebot" content="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['googlebot'];?>
" />
	<meta name="copyright" content="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['copyright'];?>
" />
	<meta name="p:domain_verify" content="02f035c6d4b9bb57d35c4fbd5a355bce"/>
	<meta name="google-signin-client_id" content="<?php echo @constant('GOOGLE_CLIENT_ID');?>
">
	<meta name="google-site-verification" content="isEbRn-k69Movj5_sLH9Ko-1RK7OrcgBluRHFqFJPPk" />
	<meta name="google-site-verification" content="EOyAvfmrNI8Vxz0K-OV6T2A30x6riVexvxq4Cbwqv-w" />
	<meta name="author" content="<?php echo $_smarty_tpl->tpl_vars['PAGE_NAME']->value;?>
" />
	<meta name="copyright" content="<?php echo $_smarty_tpl->tpl_vars['PAGE_NAME']->value;?>
" />
	<link rel="manifest" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/manifest.json"/>
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
">
	<meta property="og:title" content="<?php echo preg_replace('!<[^>]*?>!', ' ', html_entity_decode($_smarty_tpl->tpl_vars['global_title_page']->value));?>
">
	<meta property="og:description" content="<?php echo preg_replace('!<[^>]*?>!', ' ', $_smarty_tpl->tpl_vars['global_description_page']->value);?>
">
	<meta property="og:image" content="<?php if ($_smarty_tpl->tpl_vars['dq_share']->value) {
echo $_smarty_tpl->tpl_vars['SITE_URL']->value;
echo $_smarty_tpl->tpl_vars['dq_share']->value;
} else {
echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/share.jpg?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;
}?>" />
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<?php $_smarty_tpl->_assignInScope('faviconUrl', $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('Favicon'));?>
	<link rel="shortcut icon" href="<?php if ($_smarty_tpl->tpl_vars['faviconUrl']->value) {
echo htmlspecialchars($_smarty_tpl->tpl_vars['faviconUrl']->value, ENT_QUOTES, 'UTF-8', true);
} else {
echo $_smarty_tpl->tpl_vars['DOMAIN_URL']->value;?>
/favicon.ico<?php }?>?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" type="image/x-icon" />
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/apple-touch-icon-57x57.png?v=1785481482" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/apple-touch-icon-72x72.png?v=1785481482" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/apple-touch-icon-114x114.png?v=1785481482" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/apple-touch-icon-144x144.png?v=1785481482" />
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="<?php echo $_smarty_tpl->tpl_vars['PAGE_NAME']->value;?>
">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<link rel="dns-prefetch" href="https://s.w.org">
	<link rel="dns-prefetch" href="https://unpkg.com">
	<link rel="dns-prefetch" href="https://www.google.com">
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin /> 
	<link crossorigin href="<?php echo $_smarty_tpl->tpl_vars['GOOGLEAPI_URL']->value;?>
/css2?family=Agbalumo&display=swap" rel="stylesheet">
	<link crossorigin href="<?php echo $_smarty_tpl->tpl_vars['GOOGLEAPI_URL']->value;?>
/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
	<link crossorigin href="<?php echo $_smarty_tpl->tpl_vars['GOOGLEAPI_URL']->value;?>
/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<!-- iPhone 14 Pro Max, 15 Pro Max, 16 Pro Max (1290 x 2796) -->
	<link rel="apple-touch-startup-image" href="/images/splash-1290x2796.png" media="(device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3)">
	<!-- iPhone 14 Pro, 15 Pro, 16 Pro (1179 x 2556) -->
	<link rel="apple-touch-startup-image" href="/images/splash-1179x2556.png" media="(device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3)">
	<!-- iPhone 13 Pro Max, 14 Plus (1284 x 2778) -->
	<link rel="apple-touch-startup-image" href="/images/splash-1284x2778.png" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3)">
	<!-- iPhone 12, 12 Pro, 13, 13 Pro, 14 (1170 x 2532) -->
	<link rel="apple-touch-startup-image" href="/images/splash-1170x2532.png" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3)">
	<!-- iPhone 11 Pro Max, XS Max (1242 x 2688) -->
	<link rel="apple-touch-startup-image" href="/images/splash-1242x2688.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)">
	<!-- iPhone 11, XR (828 x 1792) -->
	<link rel="apple-touch-startup-image" href="/images/splash-828x1792.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)">
	<!-- Preload -->
	<?php if (isset($_smarty_tpl->tpl_vars['list_img_preloaders']->value) && !empty($_smarty_tpl->tpl_vars['list_img_preloaders']->value)) {?>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_img_preloaders']->value, '_oImg');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oImg']->value) {
?>
		<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['_oImg']->value;?>
" />
		<link rel="preload" href="<?php echo $_smarty_tpl->tpl_vars['_oImg']->value;?>
" type="image/jpeg" as="image"  />
		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	<?php }?>
	
		<style>
			:root {
				--main-color: <?php echo $_smarty_tpl->tpl_vars['header_configs']->value['BrandColor'];?>

			}
		</style>
	
	<link rel="preload" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/config.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="preload" href="<?php echo $_smarty_tpl->tpl_vars['URL_THEMES']->value;?>
/vendor/js/helpers.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="preload" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery-3.5.1.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="preload" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/easyui/jquery.easyui.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="preload" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/script.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="preload" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/store.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<?php if ($_smarty_tpl->tpl_vars['mod']->value != 'auth') {?>
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/core/editor/tiny_mce/tinymce.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/emojiPicker.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<?php }?>
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_THEMES']->value;?>
/vendor/libs/popper/popper.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_THEMES']->value;?>
/vendor/js/bootstrap.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_THEMES']->value;?>
/vendor/libs/perfect-scrollbar/perfect-scrollbar.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_THEMES']->value;?>
/vendor/js/menu.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/ui-popover.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/main.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="script">
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/easyui/themes/gray/easyui.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="style" />
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/font.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="style" />
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/boxicons.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="style" />
	<link rel="prefetch" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/global.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" as="style" />
	<link rel="prefetch" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/style.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
	<link rel="prefetch" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
	<!-- Icons. Uncomment required icon fonts -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/font.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" >
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/boxicons.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" >
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/global.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/sky-theme.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/easyui/themes/gray/easyui.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/style.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
	<?php if ($_smarty_tpl->tpl_vars['mod']->value != 'auth') {?><link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/chat.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" /><?php }?>
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
	<!-- Script -->
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery-3.5.1.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/easyui/jquery.easyui.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript"> var $_easyUI = $.noConflict(true); <?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/config.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_THEMES']->value;?>
/vendor/js/helpers.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery-3.5.1.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript"> var $_document = $(document), $Core = {}, _DEV = `$<?php echo $_smarty_tpl->tpl_vars['_DEV']->value;?>
`;<?php echo '</script'; ?>
>
	<?php if ($_smarty_tpl->tpl_vars['mod']->value != 'auth') {?>	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/core/editor/tiny_mce/tinymce.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php }?>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/script.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/store.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/jquery.freeze-fixed-table.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php if ($_smarty_tpl->tpl_vars['mod']->value != 'auth') {?>	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/emojiPicker.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="/core/editor/isoTextArea.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php }?>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/app.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/assets/helper.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/assets/member.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/global.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/fnc/calculator.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/fnc/performance.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/fnc/jquery.marketing.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->_DEV()) {?>
	<?php echo '<script'; ?>
 src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
	<?php }?>
	<!-- End script header -->
	<?php echo '<script'; ?>
 type="text/javascript">
		var isiPad 		= '<?php echo $_smarty_tpl->tpl_vars['isiPad']->value;?>
';
		var deviceType 	= '<?php echo $_smarty_tpl->tpl_vars['deviceType']->value;?>
';
		var path_ajax_script='<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
';
		var BRAND_NAME 	= "<?php echo strtr(@constant('BRAND_NAME'), array("\\" => "\\\\", "'" => "\\'", "\"" => "\\\"", "\r" => "\\r", "\n" => "\\n", "</" => "<\/" ));?>
";
		var ABSPATH 	= '<?php echo $_smarty_tpl->tpl_vars['ABSPATH']->value;?>
';
		var PCMS_URL 	= '<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
';
		var URL_IMAGES 	= '<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
';
		var URL_CSS 	= '<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
';
		var URL_JS 		= '<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
';
		var MOD 		= '<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
';
		var SUB 		= '<?php echo $_smarty_tpl->tpl_vars['sub']->value;?>
';
		var ACT 		= '<?php echo $_smarty_tpl->tpl_vars['act']->value;?>
';
		var return_url 	= '<?php echo $_smarty_tpl->tpl_vars['return_url']->value;?>
';
		var loggedIn   	= '<?php echo $_smarty_tpl->tpl_vars['loggedIn']->value;?>
';
		var profile_id 	= '<?php echo $_smarty_tpl->tpl_vars['profile_id']->value;?>
';
		var logdedUser	= <?php echo json_encode($_smarty_tpl->tpl_vars['logdedUser']->value);?>
;
		var appId 		= '<?php echo $_smarty_tpl->tpl_vars['appId']->value;?>
';
		var chUrl 		= '/js/channel.html';
		var REQUEST_URI = '<?php echo $_smarty_tpl->tpl_vars['REQUEST_URI']->value;?>
';
		var TYPE        = 'token';
		var _PROJECT_OTHER_ID = '<?php echo @constant('_PROJECT_OTHER_ID');?>
';
		var SOCKET_URL	= '<?php echo @constant('_CHAT_SOCKET_URL');?>
';
		var TENANT_ID	= '<?php echo @constant('_TENANT_ID');?>
';
		var CLIENTID    = '<?php echo @constant('appIdGoogle');?>
';
		var REDIRECT    = '<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/oauth2callback';
		var OAUTHURL    = 'https://accounts.google.com/o/oauth2/auth?';
		var SCOPE       = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';
		var VALIDURL    = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=';
		var _url = OAUTHURL+'scope='+SCOPE+'&redirect_uri='+REDIRECT+'&client_id='+CLIENTID+'&response_type='+TYPE;
		var acToken, tokenType, expiresIn, _timeOut, _timeInterval, _rsSlider, 
			_STOCK_TYPE_LEASING = '<?php echo @constant('_STOCK_TYPE_LEASING');?>
',
			_REPORT_COLUMN_ADS_ID = '<?php echo @constant('_REPORT_COLUMN_ADS_ID');?>
',
			_BLOCK_TYPE_LOWFLOOR_SALE = '<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
',
			_BLOCK_TYPE_HIGHLEVEL_SALE = '<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
',
			_SOP_TYPE_HIGHLEVEL = '<?php echo @constant('_SOP_TYPE_HIGHLEVEL');?>
',
			_SOP_TYPE_LOWFLOOR = '<?php echo @constant('_SOP_TYPE_LOWFLOOR');?>
';
		var datepickerformat = 'dd/mm/yy',
			crm_datepicker_format = {
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat :datepickerformat,
				yearRange: "1900:2050"
			};
	<?php echo '</script'; ?>
>
	<?php echo $_smarty_tpl->tpl_vars['scriptlang']->value;?>

	<!-- Only Login -->
	<?php if ($_smarty_tpl->tpl_vars['loggedIn']->value == '1') {?>
		
		<?php echo '<script'; ?>
 type="text/javascript">
			(function(d, t) {
				var g = d.createElement(t),
				s = d.getElementsByTagName(t)[0];
				g.src = "https://cdn.pushalert.co/unified_8d40e7a35c6f4ca3ed288f9cb5bc9aa4.js";
				s.parentNode.insertBefore(g, s);
			}(document, "script"));
			(pushalertbyiw = window.pushalertbyiw || []).push(['onReady', onPAReady]);
			function onPAReady() {
				var subs_info = PushAlertCo.getSubsInfo();
				if(!$Core.util.isEmpty(subs_info) && subs_info['status'] == 'subscribed'){
					$.post(`${PCMS_URL}/index.php?mod=ajax&act=save_fcm_token`, {
						'push_type' : '_pushalert',
						'fcm_token' : subs_info.subs_id
					}, function(msg){});
				}
			}
		<?php echo '</script'; ?>
>
		
	<?php }?>
	<!-- Google tag (gtag.js) -->
	<?php echo '<script'; ?>
 async src="https://www.googletagmanager.com/gtag/js?id=G-77QHPDCJYT"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'G-77QHPDCJYT');
	<?php echo '</script'; ?>
>
	<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkDEV() && 1 == 2) {?>
	<?php echo '<script'; ?>
 src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
>
	  window.OneSignalDeferred = window.OneSignalDeferred || [];
	  OneSignalDeferred.push(function(OneSignal) {
		OneSignal.init({
		  appId: "489ba8ef-620f-4a33-bbb5-eb4d6045505b",
		  notifyButton: {enable: true, },
		  allowLocalhostAsSecureOrigin: true
		});
	  });
	<?php echo '</script'; ?>
>
	<?php }?>
	</head>
	<body<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> onload="$Core.util.autoload()"<?php }?> 
	class="page-template lightbox <?php echo $_smarty_tpl->tpl_vars['deviceType']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
-pag" dir="ltr"<?php if (!empty($_smarty_tpl->tpl_vars['bg_style']->value)) {?> style="<?php echo $_smarty_tpl->tpl_vars['bg_style']->value;?>
"<?php }?>>
		<div class="ajax-loading" <?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['act']->value == 'default' && $_smarty_tpl->tpl_vars['sub']->value == 'default' && $_smarty_tpl->tpl_vars['profile_id']->value == 289) {?>style="display:block"<?php }?>>
			<div class="ajax-loading-container d-flex justify-content-center align-items-center">
				<div class="ajax-loading-inner">
					<div class="ajax-loading-loader"></div>
					<img class="ajax-loading-logo w-px-50" src="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['HeaderLogo'];?>
?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
				</div>
			</div>
		</div>
		<!-- Layout wrapper -->
		<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'auth') {?>
			<?php echo $_smarty_tpl->tpl_vars['core']->value->getHeader($_smarty_tpl->tpl_vars['mod']->value,'_header');?>

			<?php echo $_smarty_tpl->tpl_vars['core']->value->getModule($_smarty_tpl->tpl_vars['mod']->value,$_smarty_tpl->tpl_vars['sub']->value,$_smarty_tpl->tpl_vars['act']->value);?>

			<?php echo $_smarty_tpl->tpl_vars['core']->value->getHeader($_smarty_tpl->tpl_vars['mod']->value,'_footer');?>

		<?php } else { ?>
			<div class="layout-wrapper layout-content-navbar <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {
echo $_smarty_tpl->tpl_vars['deviceType']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['sub']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['act']->value;
}?>">
				<div id="layout-container" class="layout-container">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->getHeader($_smarty_tpl->tpl_vars['mod']->value,'_header');?>

					<?php echo $_smarty_tpl->tpl_vars['core']->value->getModule($_smarty_tpl->tpl_vars['mod']->value,$_smarty_tpl->tpl_vars['sub']->value,$_smarty_tpl->tpl_vars['act']->value);?>

					<?php echo $_smarty_tpl->tpl_vars['core']->value->getHeader($_smarty_tpl->tpl_vars['mod']->value,'_footer');?>

				</div>
				<div class="layout-overlay layout-menu-toggle"></div>
				<div class="drag-target"></div>
			</div>
		<?php }?>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_THEMES']->value;?>
/vendor/libs/popper/popper.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" defer><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_THEMES']->value;?>
/vendor/js/bootstrap.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" defer><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_THEMES']->value;?>
/vendor/js/menu.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" defer><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/ui-popover.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" defer><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/main.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" defer><?php echo '</script'; ?>
>
		<?php if ($_smarty_tpl->tpl_vars['mod']->value != 'auth') {?>		<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/angularjs/angular.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" ><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/angularjs/lucide.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" ><?php echo '</script'; ?>
>
				<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/socket.io.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/fnc/chat.ng.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
		<?php }?>
		<!-- Insert script mod -->
		<?php echo $_smarty_tpl->tpl_vars['core']->value->getScript($_smarty_tpl->tpl_vars['mod']->value,$_smarty_tpl->tpl_vars['act']->value,'js');?>

		<!--Beep Global-->
		<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
		<audio id="beepGlobal" class="d-none" controls preload="auto" style="width:0; height:0;">
			<source src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/sound/msg_rcvd.wav?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"></source>
			<source src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/sound/msg_rcvd.ogg?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"></source>
			<source src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/sound/msg_rcvd.m4a?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"></source>
		</audio>
		<?php }?>
		<div class="toast-container position-fixed bottom-0 end-0 p-3 zindex-5"></div>
		
		<?php echo '<script'; ?>
 id="toast-normal" type="text/template">
			<div class="bs-toast toast fade my-2" 
				role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
				<div class="toast-header">
					<i class="bx bx-{{icon}} me-2"></i>
					<div class="me-auto fw-semibold">{{title}}</div>
					<!-- <small>11 mins ago</small> -->
					<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
				<div class="toast-body">{{content}}.</div>
			</div>
		<?php echo '</script'; ?>
>
		
		
		<?php echo '<script'; ?>
 id="toast-primary" type="text/template">
			<div class="bs-toast toast bg-primary fade my-2" 
				role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
				<div class="toast-header">
					<i class="bx bx-{{icon}} me-2"></i>
					<div class="me-auto fw-semibold">{{title}}</div>
					<small>{{time}}</small>
					<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
				<div class="toast-body">{{content}}.</div>
			</div>
		<?php echo '</script'; ?>
>
		
		
		<?php echo '<script'; ?>
 id="toast-warning" type="text/template">
			<div class="bs-toast toast bg-warning fade my-2" 
				role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
				<div class="toast-header">
					<i class="bx bx-{{icon}} me-2"></i>
					<div class="me-auto fw-semibold">{{title}}</div>
					<small>{{time}}</small>
					<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
				<div class="toast-body">{{content}}.</div>
			</div>
		<?php echo '</script'; ?>
>
		
	</body>
	</html>
<?php }
}
}
