<?php
/* Smarty version 3.1.33, created on 2026-08-08 17:31:40
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/_header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a77058c128ae4_92050004',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '684c2e1287084b59d20f177a705841de90d7a929' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/_header.tpl',
      1 => 1785383688,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a77058c128ae4_92050004 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['message']->value == 'NotPermission' || $_smarty_tpl->tpl_vars['message']->value == 'notPermission') {?>
<div id="message">
	<span class="updated"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('adminnotallowaccess');?>
</span>
</div>
<?php }
if ($_smarty_tpl->tpl_vars['message']->value == 'insertSuccess') {?>
<div id="message" class="add">
	<span><img align="absmiddle" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icon_admin/add.png" width="32px" /><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('addnewsuccess');?>
</span>
</div>
<?php }
if ($_smarty_tpl->tpl_vars['message']->value == 'RestoreSuccess') {?>
<div id="message" class="restore">
	<span><img align="absmiddle" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icon_admin/TimeMachine.png" width="32px" /><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('restoringsuccess');?>
</span>
</div>
<?php }
if ($_smarty_tpl->tpl_vars['message']->value == 'updateSuccess' || $_smarty_tpl->tpl_vars['message']->value == 'UpdateSuccess') {?>
<div id="message" class="update">
	<span><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icon_admin/iSync.png" width="32px" /><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('updatesuccessful');?>
</span>
</div>
<?php }
if ($_smarty_tpl->tpl_vars['message']->value == 'TrashSuccess') {?>
<div id="message" class="trash">
	<span><img align="absmiddle" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icon_admin/Trash-Full.png" width="32px" /><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('movedtotrash');?>
</span>
</div>
<?php }
if ($_smarty_tpl->tpl_vars['message']->value == 'DeleteSuccess') {?>
<div id="message" class="del">
	<span><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icon_admin/Del.png" width="32px" /><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('deletesuccess');?>
</span>
</div>
<?php }
if ($_smarty_tpl->tpl_vars['message']->value == 'invalidAccess') {?>
<div id="message" class="del">
	<span><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icon_admin/Lock.png" width="32px" /><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('youdonothavepermission');?>
</span>
</div>
<?php }
if ($_smarty_tpl->tpl_vars['message']->value == 'PositionSuccess') {?>
<div id="message" class="pos">
	<span><img align="absmiddle" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/icon_admin/Pos.png" width="32px" /><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('positionhasbeenchanged');?>
</span>
</div>
<?php }?>
<header id="page-header">
	<div id="topnavlink">
		<ul>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Dashboard');?>
</a></li>
			<li class="s">|</li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['DOMAIN_NAME']->value;
echo $_smarty_tpl->tpl_vars['access_url']->value;?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('clientpage');?>
</a></li>
			<li class="s">|</li>
			<!--<li><a class="ajManageSystemNote"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('notes');?>
</a></li>
			<li class="s">|</li>-->
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=user&act=edit&user_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['core']->value->_USER['user_id']);?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('myacount');?>
</a></li>
			<li class="s">|</li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=login&act=logout"><i class="fa fa-power-off"></i> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('logout');?>
</a></li>
		</ul>
		<div class="HD_box mr20 font12px">
			<span class="white mr5" id="SiteClock"></span>
			<span class="link-orange">|&nbsp;&nbsp;<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('yourip');?>
: <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRealIP();?>
 </span>
		</div>
	</div>
	<div class="ui-top-bar">
		<div class="ui-top-bar__branding" id="logo">
			<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['PAGE_NAME']->value;?>
">
				<img height="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getImageHeight('LogoWhite');?>
" src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" alt="<?php echo $_smarty_tpl->tpl_vars['PAGE_NAME']->value;?>
" />
			</a>
		</div>
		<div class="ui-top-bar__list">
			<div class="ui-top-bar__item ui-top-bar__item--fill">
                <section class="top-bar-search">
                    <div class="top-bar-search__input-wrapper">
                        <div class="top-bar-input-search-wrapper top-bar-navigation-search">
                           <input type="text" placeholder="Search settings..."  />
                        </div>
                    </div>
                </section>
            </div>
					</div>
	</div>
</header>
<aside class="sidebar sidebar-default" id="sidebar">
	<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('quick_menu');?>

</aside>
<div id="page-main">
	<div id="page-body">
	
	<?php echo '<script'; ?>
 type="text/javascript">
		$(function(){
			setInterval(function(){
				var $html = '';
				var $_array = new Array('Chủ nhật','Thứ hai','Thứ ba','Thứ tư','Thứ năm','Thứ sáu','Thứ bảy');
				var $CurrentDate = new Date();
				var $day = $CurrentDate.getDay();
				var $date = $CurrentDate.getDate();
				var $month = $CurrentDate.getMonth()+1;
				var $year = $CurrentDate.getFullYear();
				var $h = $CurrentDate.getHours();
				var $s = $CurrentDate.getMinutes();
				var $ms = $CurrentDate.getSeconds();
				/**/
				$html += $_array[$day]+', ';
				$html += ($date < 10 ) ? '0' + $date:$date;
				$html += '-' + ($month < 10 ? '0' + $month:$month);
				$html += '-' + $year;
				$html += '&nbsp;|&nbsp;' + $h +':'+ $s +':'+ ($ms<10?'0'+$ms:$ms)+'';
				$('#SiteClock').html($html).fadeIn();
			},1000);
		});
	<?php echo '</script'; ?>
>
	<?php }
}
