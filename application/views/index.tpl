<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{if $mod eq 'viewer'}
<html class="light-style layout-compact layout-menu-fixed layout-navbar-fixed" xmlns="http://www.w3.org/1999/xhtml" lang="vi" xml:lang="vi">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/bootstrap.min.css?v={$upd_version}" />
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/owl.theme.css?v={$upd_version}" />
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/owl.transitions.css?v={$upd_version}" />
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/owl.carousel.css?v={$upd_version}" />
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/font-awesome.min.css?v={$upd_version}" />
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/style.css?v={$upd_version}" />
		<link rel="stylesheet" type="text/css" href="{$URL_CSS}/viewer.css?v={$upd_version}" />
		<script type="text/javascript" src="{$URL_THEMES}/vendor/libs/jquery/jquery.js?v={$upd_version}"></script>
		<script type="text/javascript"> var $_document = $(document), $Core = {ldelim}{rdelim};</script>
		<script type="text/javascript" src="{$URL_JS}/underscore-min.js?v={$upd_version}"></script>
		<script type="text/javascript" src="{$URL_JS}/jquery-migrate-1.2.1.min.js?v={$upd_version}"></script>
		<script type="text/javascript" src="{$URL_JS}/jquery-ui.1.11.0.min.js?v={$upd_version}"></script>
		<script type="text/javascript" src="{$URL_JS}/store.min.js?v={$upd_version}"></script>
	</head>
	<body>
		{$core->getModule($mod,$sub,$act)}
	</body>
</html>
{else}
	<!-- layout-menu-hover -->
	<html class="light-style layout-compact layout-navbar-fixed layout-menu-fixed layout-navbar-hidden" 
		xmlns="http://www.w3.org/1999/xhtml" lang="vi" xml:lang="vi">
	<head>
	{* Cấu hình riêng trang độc quyền (admin → setting → Trang độc quyền); trống thì lùi về mặc định site. *}
	{assign var=dq_title value=$clsConfiguration->getValue('docquyen_meta_title')}
	{assign var=dq_desc value=$clsConfiguration->getValue('docquyen_meta_description')}
	{assign var=dq_share value=$clsConfiguration->getValue('docquyen_image_share')}
	<title>{$global_title_page|html_entity_decode|strip_tags}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=100">
	<!---{if $mod eq 'home' && $sub eq 'default' && $act eq 'default'},viewport-fit=cover{/if} -->
	<meta name="viewport" content=" initial-scale=1.0, maximum-scale=1, user-scalable=0">
	<meta name="Description" content="{if $dq_desc}{$dq_desc|escape}{else}{$global_description_page|strip_tags}{/if}" />
	<meta name="Keywords" content="{$global_keyword_page}" />
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="Cache-control" content="no-cache">
	<meta name="theme-color" content="#000000" />
	<meta name="googlebot" content="{$header_configs.googlebot}" />
	<meta name="copyright" content="{$header_configs.copyright}" />
	<meta name="p:domain_verify" content="02f035c6d4b9bb57d35c4fbd5a355bce"/>
	<meta name="google-signin-client_id" content="{$smarty.const.GOOGLE_CLIENT_ID}">
	<meta name="google-site-verification" content="isEbRn-k69Movj5_sLH9Ko-1RK7OrcgBluRHFqFJPPk" />
	<meta name="google-site-verification" content="EOyAvfmrNI8Vxz0K-OV6T2A30x6riVexvxq4Cbwqv-w" />
	<meta name="author" content="{$PAGE_NAME}" />
	<meta name="copyright" content="{$PAGE_NAME}" />
	<link rel="manifest" href="{$PCMS_URL}/manifest.json"/>
	<meta property="og:type" content="website">
	<meta property="og:url" content="{$PCMS_URL}">
	<meta property="og:title" content="{$global_title_page|html_entity_decode|strip_tags}">
	<meta property="og:description" content="{$global_description_page|strip_tags}">
	<meta property="og:image" content="{if $dq_share}{$SITE_URL}{$dq_share}{else}{$URL_IMAGES}/share.jpg?v={$upd_version}{/if}" />
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	{assign var=faviconUrl value=$clsConfiguration->getValue('Favicon')}
	<link rel="shortcut icon" href="{if $faviconUrl}{$faviconUrl|escape}{else}{$DOMAIN_URL}/favicon.ico{/if}?v={$upd_version}" type="image/x-icon" />
	<link rel="apple-touch-icon" sizes="57x57" href="{$PCMS_URL}/apple-touch-icon-57x57.png?v=1785481482" />
	<link rel="apple-touch-icon" sizes="72x72" href="{$PCMS_URL}/apple-touch-icon-72x72.png?v=1785481482" />
	<link rel="apple-touch-icon" sizes="114x114" href="{$PCMS_URL}/apple-touch-icon-114x114.png?v=1785481482" />
	<link rel="apple-touch-icon" sizes="144x144" href="{$PCMS_URL}/apple-touch-icon-144x144.png?v=1785481482" />
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-title" content="{$PAGE_NAME}">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<link rel="dns-prefetch" href="https://s.w.org">
	<link rel="dns-prefetch" href="https://unpkg.com">
	<link rel="dns-prefetch" href="https://www.google.com">
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin /> 
	<link crossorigin href="{$GOOGLEAPI_URL}/css2?family=Agbalumo&display=swap" rel="stylesheet">
	<link crossorigin href="{$GOOGLEAPI_URL}/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
	<link crossorigin href="{$GOOGLEAPI_URL}/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
	{if isset($list_img_preloaders) && !empty($list_img_preloaders)}
		{foreach from=$list_img_preloaders item = _oImg}
		<link rel="prefetch" href="{$_oImg}" />
		<link rel="preload" href="{$_oImg}" type="image/jpeg" as="image"  />
		{/foreach}
	{/if}
	{literal}
		<style>
			:root {
				--main-color: {/literal}{$header_configs.BrandColor}{literal}
			}
		</style>
	{/literal}
	<link rel="preload" href="{$URL_JS}/config.js?v={$upd_version}" as="script">
	<link rel="preload" href="{$URL_THEMES}/vendor/js/helpers.js?v={$upd_version}" as="script">
	<link rel="preload" href="{$URL_JS}/jquery-3.5.1.min.js?v={$upd_version}" as="script">
	<link rel="preload" href="{$URL_JS}/easyui/jquery.easyui.min.js?v={$upd_version}" as="script">
	<link rel="preload" href="{$URL_JS}/script.min.js?v={$upd_version}" as="script">
	<link rel="preload" href="{$URL_JS}/store.min.js?v={$upd_version}" as="script">
	{if $mod ne 'auth'}
	<link rel="prefetch" href="{$PCMS_URL}/core/editor/tiny_mce/tinymce.min.js?v={$upd_version}" as="script">
	<link rel="prefetch" href="{$URL_JS}/emojiPicker.js?v={$upd_version}" as="script">
	{/if}
	<link rel="prefetch" href="{$URL_THEMES}/vendor/libs/popper/popper.js?v={$upd_version}" as="script">
	<link rel="prefetch" href="{$URL_THEMES}/vendor/js/bootstrap.js?v={$upd_version}" as="script">
	<link rel="prefetch" href="{$URL_THEMES}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js?v={$upd_version}" as="script">
	<link rel="prefetch" href="{$URL_THEMES}/vendor/js/menu.js?v={$upd_version}" as="script">
	<link rel="prefetch" href="{$URL_JS}/ui-popover.js?v={$upd_version}" as="script">
	<link rel="prefetch" href="{$URL_JS}/main.js?v={$upd_version}" as="script">
	<link rel="prefetch" href="{$URL_JS}/easyui/themes/gray/easyui.css?v={$upd_version}" as="style" />
	<link rel="prefetch" href="{$URL_CSS}/font.css?v={$upd_version}" as="style" />
	<link rel="prefetch" href="{$URL_CSS}/boxicons.min.css?v={$upd_version}" as="style" />
	<link rel="prefetch" href="{$URL_CSS}/global.min.css?v={$upd_version}" as="style" />
	<link rel="prefetch" type="text/css" href="{$URL_CSS}/style.css?v={$upd_version}" />
	<link rel="prefetch" type="text/css" href="{$URL_CSS}/{$mod}.css?v={$upd_version}" />
	<!-- Icons. Uncomment required icon fonts -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
	<link rel="stylesheet" type="text/css" href="{$URL_CSS}/font.css?v={$upd_version}" >
	<link rel="stylesheet" type="text/css" href="{$URL_CSS}/boxicons.min.css?v={$upd_version}" >
	<link rel="stylesheet" type="text/css" href="{$URL_CSS}/global.min.css?v={$upd_version}" />
	<link rel="stylesheet" type="text/css" href="{$URL_CSS}/sky-theme.css?v={$upd_version}" />
	<link rel="stylesheet" type="text/css" href="{$URL_JS}/easyui/themes/gray/easyui.css?v={$upd_version}" />
	<link rel="stylesheet" type="text/css" href="{$URL_CSS}/style.css?v={$upd_version}" />
	{if $mod ne 'auth'}<link rel="stylesheet" type="text/css" href="{$URL_CSS}/chat.css?v={$upd_version}" />{/if}
	<link rel="stylesheet" type="text/css" href="{$URL_CSS}/{$mod}.css?v={$upd_version}" />
	<!-- Script -->
	<script type="text/javascript" src="{$URL_JS}/jquery-3.5.1.min.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/easyui/jquery.easyui.min.js?v={$upd_version}"></script>
	<script type="text/javascript"> var $_easyUI = $.noConflict(true); </script>
	<script type="text/javascript" src="{$URL_JS}/config.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_THEMES}/vendor/js/helpers.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/jquery-3.5.1.min.js?v={$upd_version}"></script>
	<script type="text/javascript"> var $_document = $(document), $Core = {ldelim}{rdelim}, _DEV = `${$_DEV}`;</script>
	{if $mod ne 'auth'}{* Perf WS6: trang dang nhap khong co editor *}
	<script type="text/javascript" src="{$PCMS_URL}/core/editor/tiny_mce/tinymce.min.js?v={$upd_version}"></script>
	{/if}
	<script type="text/javascript" src="{$URL_JS}/script.min.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/store.min.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/jquery.freeze-fixed-table.js?v={$upd_version}"></script>
	{if $mod ne 'auth'}{* Perf WS6: emoji + isoTextArea (phu thuoc tinymce) khong dung o trang dang nhap *}
	<script type="text/javascript" src="{$URL_JS}/emojiPicker.js?v={$upd_version}"></script>
	<script type="text/javascript" src="/core/editor/isoTextArea.js?v={$upd_version}"></script>
	{/if}
	<script type="text/javascript" src="{$URL_JS}/app.min.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/assets/helper.min.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/assets/member.min.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/global.min.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/fnc/calculator.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/fnc/performance.js?v={$upd_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/fnc/jquery.marketing.js?v={$upd_version}"></script>
	{if $clsISO->_DEV()}
	<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js?v={$upd_version}"></script>
	<script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js?v={$upd_version}"></script>
	{/if}
	<!-- End script header -->
	<script type="text/javascript">
		var isiPad 		= '{$isiPad}';
		var deviceType 	= '{$deviceType}';
		var path_ajax_script='{$PCMS_URL}';
		var BRAND_NAME 	= "{$smarty.const.BRAND_NAME|escape:'javascript'}";
		var ABSPATH 	= '{$ABSPATH}';
		var PCMS_URL 	= '{$PCMS_URL}';
		var URL_IMAGES 	= '{$URL_IMAGES}';
		var URL_CSS 	= '{$URL_CSS}';
		var URL_JS 		= '{$URL_JS}';
		var MOD 		= '{$mod}';
		var SUB 		= '{$sub}';
		var ACT 		= '{$act}';
		var return_url 	= '{$return_url}';
		var loggedIn   	= '{$loggedIn}';
		var profile_id 	= '{$profile_id}';
		var logdedUser	= {$logdedUser|@json_encode};
		var appId 		= '{$appId}';
		var chUrl 		= '/js/channel.html';
		var REQUEST_URI = '{$REQUEST_URI}';
		var TYPE        = 'token';
		var _PROJECT_OTHER_ID = '{$smarty.const._PROJECT_OTHER_ID}';
		var SOCKET_URL	= '{$smarty.const._CHAT_SOCKET_URL}';
		var TENANT_ID	= '{$smarty.const._TENANT_ID}';
		var CLIENTID    = '{$smarty.const.appIdGoogle}';
		var REDIRECT    = '{$PCMS_URL}/oauth2callback';
		var OAUTHURL    = 'https://accounts.google.com/o/oauth2/auth?';
		var SCOPE       = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';
		var VALIDURL    = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=';
		var _url = OAUTHURL+'scope='+SCOPE+'&redirect_uri='+REDIRECT+'&client_id='+CLIENTID+'&response_type='+TYPE;
		var acToken, tokenType, expiresIn, _timeOut, _timeInterval, _rsSlider, 
			_STOCK_TYPE_LEASING = '{$smarty.const._STOCK_TYPE_LEASING}',
			_REPORT_COLUMN_ADS_ID = '{$smarty.const._REPORT_COLUMN_ADS_ID}',
			_BLOCK_TYPE_LOWFLOOR_SALE = '{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}',
			_BLOCK_TYPE_HIGHLEVEL_SALE = '{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}',
			_SOP_TYPE_HIGHLEVEL = '{$smarty.const._SOP_TYPE_HIGHLEVEL}',
			_SOP_TYPE_LOWFLOOR = '{$smarty.const._SOP_TYPE_LOWFLOOR}';
		var datepickerformat = 'dd/mm/yy',
			crm_datepicker_format = {
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat :datepickerformat,
				yearRange: "1900:2050"
			};
	</script>
	{$scriptlang}
	<!-- Only Login -->
	{if $loggedIn eq '1'}
		{literal}
		<script type="text/javascript">
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
		</script>
		{/literal}
	{/if}
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-77QHPDCJYT"></script>
	{literal}<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());
		gtag('config', 'G-77QHPDCJYT');
	</script>{/literal}
	{if $clsISO->checkDEV() && 1==2}
	<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
	{literal}<script>
	  window.OneSignalDeferred = window.OneSignalDeferred || [];
	  OneSignalDeferred.push(function(OneSignal) {
		OneSignal.init({
		  appId: "489ba8ef-620f-4a33-bbb5-eb4d6045505b",
		  notifyButton: {enable: true, },
		  allowLocalhostAsSecureOrigin: true
		});
	  });
	</script>{/literal}
	{/if}
	</head>
	<body{if $deviceType eq 'phone'} onload="$Core.util.autoload()"{/if} 
	class="page-template lightbox {$deviceType} {$mod}-pag" dir="ltr"{if !empty($bg_style)} style="{$bg_style}"{/if}>
		<div class="ajax-loading" {if $mod eq 'home' && $act eq 'default' && $sub eq 'default' && $profile_id eq 289}style="display:block"{/if}>
			<div class="ajax-loading-container d-flex justify-content-center align-items-center">
				<div class="ajax-loading-inner">
					<div class="ajax-loading-loader"></div>
					<img class="ajax-loading-logo w-px-50" src="{$header_configs.HeaderLogo}?v={$upd_version}" />
				</div>
			</div>
		</div>
		<!-- Layout wrapper -->
		{if $mod eq 'auth'}
			{$core->getHeader($mod,'_header')}
			{$core->getModule($mod,$sub,$act)}
			{$core->getHeader($mod,'_footer')}
		{else}
			<div class="layout-wrapper layout-content-navbar {if $deviceType eq 'phone'}{$deviceType} {$mod} {$sub}_{$act}{/if}">
				<div id="layout-container" class="layout-container">
					{$core->getHeader($mod,'_header')}
					{$core->getModule($mod,$sub,$act)}
					{$core->getHeader($mod,'_footer')}
				</div>
				<div class="layout-overlay layout-menu-toggle"></div>
				<div class="drag-target"></div>
			</div>
		{/if}
		<script type="text/javascript" src="{$URL_THEMES}/vendor/libs/popper/popper.js?v={$upd_version}" defer></script>
		<script type="text/javascript" src="{$URL_THEMES}/vendor/js/bootstrap.js?v={$upd_version}" defer></script>
		<script type="text/javascript" src="{$URL_THEMES}/vendor/js/menu.js?v={$upd_version}" defer></script>
		<script type="text/javascript" src="{$URL_JS}/ui-popover.js?v={$upd_version}" defer></script>
		<script type="text/javascript" src="{$URL_JS}/main.js?v={$upd_version}" defer></script>
		{if $mod ne 'auth'}{* Perf WS6: trang dang nhap khong dung chat -> bo angular/lucide/chat *}
		<script src="{$URL_JS}/angularjs/angular.min.js?v={$upd_version}" ></script>
		<script src="{$URL_JS}/angularjs/lucide.min.js?v={$upd_version}" ></script>
		{* socket.io client 4.8.3 — self-host, KHỚP version server relay (nodejs.c-a.vn). Chat realtime cần `io`. *}
		<script type="text/javascript" src="{$URL_JS}/socket.io.min.js?v={$upd_version}"></script>
		<script type="text/javascript" src="{$URL_JS}/fnc/chat.ng.js?v={$upd_version}"></script>
		{/if}
		<!-- Insert script mod -->
		{$core->getScript($mod, $act, 'js')}
		<!--Beep Global-->
		{if $deviceType ne 'phone'}
		<audio id="beepGlobal" class="d-none" controls preload="auto" style="width:0; height:0;">
			<source src="{$URL_JS}/sound/msg_rcvd.wav?v={$upd_version}"></source>
			<source src="{$URL_JS}/sound/msg_rcvd.ogg?v={$upd_version}"></source>
			<source src="{$URL_JS}/sound/msg_rcvd.m4a?v={$upd_version}"></source>
		</audio>
		{/if}
		<div class="toast-container position-fixed bottom-0 end-0 p-3 zindex-5"></div>
		{literal}
		<script id="toast-normal" type="text/template">
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
		</script>
		{/literal}
		{literal}
		<script id="toast-primary" type="text/template">
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
		</script>
		{/literal}
		{literal}
		<script id="toast-warning" type="text/template">
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
		</script>
		{/literal}
	</body>
	</html>
{/if}