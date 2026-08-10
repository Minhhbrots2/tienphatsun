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
		<script type="text/javascript" src="{$URL_JS}/FavIconBadge.js?v={$upd_version}"></script>
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
<title>{$global_title_page|html_entity_decode|strip_tags}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="Pragma" content="no-cache" />
<META http-equiv="Expires" CONTENT="-1">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0">
<meta name="Description" content="{$global_description_page|strip_tags}" />
<meta name="Keywords" content="{$global_keyword_page}" />
<meta name="robots" content="noindex, nofollow" />
<meta name="googlebot" content="{$header_configs.googlebot}" />
<meta name="copyright" content="{$header_configs.copyright}" />
<meta name="google-site-verification" content="isEbRn-k69Movj5_sLH9Ko-1RK7OrcgBluRHFqFJPPk" />
<meta name="google-site-verification" content="EOyAvfmrNI8Vxz0K-OV6T2A30x6riVexvxq4Cbwqv-w" />
<meta name="google-signin-client_id" content="{$smarty.const.GOOGLE_CLIENT_ID}">
<meta name="p:domain_verify" content="02f035c6d4b9bb57d35c4fbd5a355bce"/>
<meta http-equiv="content-language" content="vi" /> 
<meta name="resource-type" content="Document" />
<meta name="revisit-after" content="7 days" />
<meta name="DC.Publisher" content="{$PAGE_NAME}" />
<meta name="author" content="{$PAGE_NAME}" />
<meta name="owner" content="{$PAGE_NAME}" />
<link rel="manifest" href="{$PCMS_URL}/manifest.json"/>
<meta name="apple-mobile-web-app-title" content="{$PAGE_NAME}">
<!-- <link rel="shortcut icon" type="image/x-icon" href="{$PCMS_URL}/favicon.ico?v={$pre_version}" /> -->
<link rel="apple-touch-icon" sizes="57x57" href="{$PCMS_URL}apple-touch-icon-57x57.png?v={$pre_version}" />
<link rel="apple-touch-icon" sizes="72x72" href="{$PCMS_URL}apple-touch-icon-72x72.png?v={$pre_version}" />
<link rel="apple-touch-icon" sizes="114x114" href="{$PCMS_URL}apple-touch-icon-114x114.png?v={$pre_version}" />
<link rel="apple-touch-icon" sizes="144x144" href="{$PCMS_URL}apple-touch-icon-144x144.png?v={$pre_version}" />
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="{$PCMS_URL}apple-touch-icon-57x57.png?v={$pre_version}" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{$PCMS_URL}apple-touch-icon-72x72.png?v={$pre_version}" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{$PCMS_URL}apple-touch-icon-114x114.png?v={$pre_version}" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{$PCMS_URL}apple-touch-icon-144x144.png?v={$pre_version}" />
<meta name="application-name" content="{$PAGE_NAME}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="mobile-web-app-capable" content="yes">
<link rel="canonical" href="{$full_link}"/>
<!-- Style -->
<link rel="dns-prefetch" href="//s.w.org">
<link rel="dns-prefetch" href="//www.google.com">
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
{if $mod eq 'auth'}
<link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">{/if}
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,500;1,500&display=swap" rel="stylesheet">
<!-- Icons. Uncomment required icon fonts -->
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<!-- Core CSS -->
{if $mod eq 'home' && $act eq 'zoom'}
<link rel="prefetch" href="{$URL_IMAGES}/backgrounds/MBVHOP1.jpg?v={$upd_version}" />
<link rel="preload" href="{$URL_IMAGES}/backgrounds/MBVHOP1.jpg?v={$upd_version}" as="image" type="image/jpeg" /> {/if}		
<!-- <link rel="preload" href="{$URL_THEMES}/vendor/css/core.css?v={$upd_version}" as="style" />
<link rel="preload" href="{$URL_THEMES}/vendor/css/theme-default.css?v={$upd_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/font-awesome.min.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/jquery-confirm.min.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/jquery-ui.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/filestatic.min.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/pretty-checkbox.min.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_JS}/redactor/redactor.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/jquery.webui-popover.min.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/owl.theme.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/owl.transitions.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/owl.carousel.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/sweetalert2.min.css?v={$pre_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/style.css?v={$upd_version}" as="style" />
<link rel="preload" href="{$URL_CSS}/{$mod}.css?v={$upd_version}" as="style" />
<link rel="preload" href="{$URL_JS}/alertify/alertify.core.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_JS}/alertify/alertify.default.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_JS}/easyui/themes/gray/easyui.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_CSS}/jquery.inputTags.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_CSS}/select2.min.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_CSS}/selectize.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_CSS}/images-grid.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_CSS}/bootstrap-multiselect.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_CSS}/fancybox.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_CSS}/datatables.bootstrap5.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_CSS}/responsive.bootstrap5.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_CSS}/buttons.bootstrap5.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$URL_CSS}/slick.css?v={$pre_version}" as="style"/>
<link rel="preload" href="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.css?v={$pre_version}" as="style" />-->
<link rel="stylesheet" type="text/css" href="{$URL_THEMES}/vendor/css/core.css?v={$upd_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_THEMES}/vendor/css/theme-default.css?v={$upd_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/font-awesome.min.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/jquery-confirm.min.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/jquery-ui.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/filestatic.min.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/pretty-checkbox.min.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_JS}/redactor/redactor.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/jquery.webui-popover.min.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/owl.theme.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/owl.transitions.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/owl.carousel.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/sweetalert2.min.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/style.css?v={$upd_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/{$mod}.css?v={$upd_version}" />
<!-- Vendors CSS -->
<link rel="stylesheet" type="text/css" href="{$URL_THEMES}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_THEMES}/vendor/libs/apex-charts/apex-charts.css?v={$pre_version}" />
<!-- Page CSS -->
<link rel="stylesheet" href="{$URL_JS}/alertify/alertify.core.css?v={$pre_version}" type="text/css" media="all">
<link rel="stylesheet" href="{$URL_JS}/alertify/alertify.default.css?v={$pre_version}" type="text/css" media="all">
<link rel="stylesheet" type="text/css" href="{$URL_JS}/easyui/themes/gray/easyui.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/jquery.inputTags.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/select2.min.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/selectize.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/images-grid.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/bootstrap-multiselect.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/fancybox.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/datatables.bootstrap5.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/responsive.bootstrap5.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/buttons.bootstrap5.css?v={$pre_version}" />
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/slick.css?v={$pre_version}">
<link rel="stylesheet" type="text/css" href="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.css?v={$pre_version}" media="all" />
<!-- Helpers -->
<!-- <link rel="preload" href="{$URL_THEMES}/vendor/libs/jquery/jquery.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/easyui/jquery.easyui.min.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_THEMES}/vendor/js/helpers.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/config.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_THEMES}/vendor/libs/jquery/jquery.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/jquery-migrate-1.2.1.min.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/underscore-min.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/jquery-ui.1.11.0.min.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/jquery.mobile.datepicker.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/jquery-ui-timepicker-addon.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/html2canvas.min.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/jquery.PrintArea.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/store.min.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/freeze-table.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/redactor/redactor.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/select2.full.min.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/selectize.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/alertify/alertify.min.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/canvasjs.min.js?v={$pre_version}" as="script" />
<link rel="preload" href="{$URL_JS}/app.min.js?v={$upd_version}" as="script" /> -->
<script type="text/javascript" src="{$URL_THEMES}/vendor/libs/jquery/jquery.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/easyui/jquery.easyui.min.js?v={$pre_version}"></script>
<script type="text/javascript"> var $_easyUI = $.noConflict(true); </script>
<script type="text/javascript" src="{$URL_THEMES}/vendor/js/helpers.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/config.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_THEMES}/vendor/libs/jquery/jquery.js?v={$pre_version}"></script>
<script type="text/javascript"> var $_document = $(document), $Core = {ldelim}{rdelim};</script>
<script type="text/javascript" src="{$URL_JS}/jquery-migrate-1.2.1.min.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/underscore-min.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/jquery-ui.1.11.0.min.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/jquery.mobile.datepicker.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/jquery-ui-timepicker-addon.js?v={$pre_version}"></script> 
<script type="text/javascript" src="{$URL_JS}/html2canvas.min.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/jquery.PrintArea.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/store.min.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/FavIconBadge.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/freeze-table.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/redactor/redactor.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_THEMES}/vendor/libs/apex-charts/apexcharts.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/select2.full.min.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/selectize.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/heic2any.min.js?v={$pre_version}"></script>
<script type="text/javascript" src="{$URL_JS}/alertify/alertify.min.js?v={$pre_version}"></script>
<script type="text/javascript" src="/core/editor/tiny_mce/tinymce.min.js?v={$pre_version}"></script>
<script type="text/javascript" src="/core/editor/isoTextArea.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/app.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/helper.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/global.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/calculator.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/canvasjs.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$smarty.const.DOMAIN_URL}/cropper/cropper.min.js?v={$pre_version}"></script>	
<script type="text/javascript" src="{$URL_JS}/performance.js?v={$upd_version}"></script>
<!-- End script header -->
<script type="text/javascript">
	var isiPad 		= '{$isiPad}';
	var deviceType 	= '{$deviceType}';
	var path_ajax_script='{$PCMS_URL}';
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
	var appId 		= '{$appId}';
	var chUrl 		= '/js/channel.html';
	var REQUEST_URI = '{$REQUEST_URI}';
	var TYPE        = 'token';
	var CLIENTID    = '33455945899-0538bm7a0m5eb149ggfkorqrvf7pskj3.apps.googleusercontent.com';
	var REDIRECT    = '{$PCMS_URL}/oauth2callback';
	var OAUTHURL    = 'https://accounts.google.com/o/oauth2/auth?';
	var SCOPE       = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';
	var VALIDURL    = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=';
	var _url        = OAUTHURL+'scope='+SCOPE+'&redirect_uri='+REDIRECT+'&client_id='+CLIENTID+'&response_type='+TYPE;
	var acToken, tokenType, expiresIn, _timeOut, _timeInterval, _rsSlider, 
		_STOCK_TYPE_LEASING = '{$smarty.const._STOCK_TYPE_LEASING}',
		_BLOCK_TYPE_LOWFLOOR_SALE = '{$smarty.const._BLOCK_TYPE_LOWFLOOR_SALE}',
		_BLOCK_TYPE_HIGHLEVEL_SALE = '{$smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE}'
		_REPORT_COLUMN_ADS_ID = '{$smarty.const._REPORT_COLUMN_ADS_ID}';
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
			g.src = "https://cdn.pushalert.co/integrate_6fa66fd2f150a55f760decccbbc2a703.js";
			s.parentNode.insertBefore(g, s);
		}(document, "script"));
		(pushalertbyiw = window.pushalertbyiw || []).push(['onReady', onPAReady]);
		function onPAReady() {
			var subs_info = PushAlertCo.getSubsInfo();
			if(!$Core.util.isEmpty(subs_info) && subs_info['status'] == 'subscribed'){
				$.post(`${PCMS_URL}/index.php?mod=ajax&act=save_fcm_token`, {
					'push_type' : '_pushalert',
					'fcm_token' : subs_info.subs_id
				}, function(msg){
					console.log('Subscriber success !');
				});
			}
		}
	</script>
	{/literal}
{/if}
<!-- End Login -->
</head>
<body onload="$Core.util.autoload()" class="page-template lunar lightbox {$deviceType} {$mod}-page" dir="ltr">
	<favicon-badge badge="0" src="{$PCMS_URL}/favicon.png?v={$pre_version}" textcolor="#FFF"></favicon-badge>
	{if $deviceType eq 'phone' && $use_browser eq 'iPhone'}
	<div class="refresh-indicator" onClick="$Core.util.reload(this, event)" id="refresh-indicator">
		<i class="bx bx-refresh fs-3"></i>
	</div>
	{/if}
	<div class="fh_AI" id="fh_AI"></div>
	{if $loggedIn eq '1' && $mod ne 'crm' && $act ne 'zoom'}
		<script src="https://sf-cdn.coze.com/obj/unpkg-va/flow-platform/chat-app-sdk/0.1.0-beta.4/libs/oversea/index.js"></script>
		{literal}
		<script>
			new CozeWebSDK.WebChatClient({
				config: {bot_id: '7383220017397202952'},
				componentProps: { 
					title: 'FutureAI' , 
					icon : URL_IMAGES+'/logoai.png'
				},
				el: document.getElementById('fh_AI')
			});
		</script>
		{/literal}
	{/if}
	<div class="ajax-loading">
		<div class="ajax-loading-container d-flex justify-content-center align-items-center">
			<div class="ajax-loading-inner">
				<div class="ajax-loading-loader"></div>
				<img class="ajax-loading-logo" width="40px" src="{$URL_IMAGES}/favicon.png" />
			</div>
		</div>
	</div>
	<div id="fb-root"></div>
    {literal}
	<script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.9";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
	{/literal}
	<!-- Layout wrapper -->
	{if $mod eq 'auth'}
		{$core->getHeader($mod,'_header')}
		{$core->getModule($mod,$sub,$act)}
		{$core->getHeader($mod,'_footer')}
	{else}
		<!-- layout-without-navbar -->
		<div class="layout-wrapper layout-content-navbar">
			<div id="layout-container" class="layout-container">
				<!-- Layout page -->
				{$core->getHeader($mod,'_header')}
				{$core->getModule($mod,$sub,$act)}
				{$core->getHeader($mod,'_footer')}
				<!-- / Layout page -->
			</div>
			<!-- Overlay -->
			<div class="layout-overlay layout-menu-toggle"></div>
			<!-- Drag Target Area To SlideIn Menu On Small Screens -->
			<div class="drag-target"></div>
		</div>
		{if $loggedIn} {*$core->getBlock('birthday')*} {/if}
	{/if}
	<!-- / Layout wrapper -->
	<!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
	<!-- <script type="text/javascript" src="{$URL_THEMES}/vendor/libs/masonry/masonry.js?v={$pre_version}"></script>-->
    <script type="text/javascript" src="{$URL_THEMES}/vendor/libs/popper/popper.js?v={$pre_version}"></script>
    <script type="text/javascript" src="{$URL_THEMES}/vendor/js/bootstrap.js?v={$pre_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/datatables-bootstrap5.js?v={$pre_version}"></script>
    <script type="text/javascript" src="{$URL_THEMES}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js?v={$pre_version}"></script>
    <script type="text/javascript" src="{$URL_THEMES}/vendor/js/menu.js?v={$pre_version}"></script>
	<script type="text/javascript" src="{$URL_JS}/ui-popover.js?v={$pre_version}"></script>
	<!-- endbuild -->
    <script type="text/javascript" src="{$URL_JS}/main.js?v={$pre_version}"></script>
	<!-- Insert script mod -->
	{$core->getScript($mod, $act, 'js')}
	{if $mod eq 'auth'}
	<script type="text/javascript" src="{$URL_JS}/script.min.js?v={$pre_version}"></script>
	{/if}
    <!-- <script src="{$URL_JS}/assets/extended-ui-perfect-scrollbar.js?v={$pre_version}"></script> -->
    <!-- Place this tag in your head or just before your close body tag. -->
    <!-- <script async defer src="//buttons.github.io/buttons.js?v={$pre_version}"></script> -->
	<!--Beep Global-->
	<audio id="beepGlobal" class="d-none" controls preload="auto" style="width:0; height:0;">
		<source src="{$URL_JS}/sound/msg_rcvd.wav?v={$pre_version}"></source>
		<source src="{$URL_JS}/sound/msg_rcvd.ogg?v={$pre_version}"></source>
		<source src="{$URL_JS}/sound/msg_rcvd.m4a?v={$pre_version}"></source>
	</audio>
</body>
</html>
{/if}