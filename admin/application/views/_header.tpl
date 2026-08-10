{if $message eq 'NotPermission' or $message eq 'notPermission'}
<div id="message">
	<span class="updated">{$core->get_Lang('adminnotallowaccess')}</span>
</div>
{/if}
{if $message eq 'insertSuccess'}
<div id="message" class="add">
	<span><img align="absmiddle" src="{$URL_IMAGES}/icon_admin/add.png" width="32px" />{$core->get_Lang('addnewsuccess')}</span>
</div>
{/if}
{if $message eq 'RestoreSuccess'}
<div id="message" class="restore">
	<span><img align="absmiddle" src="{$URL_IMAGES}/icon_admin/TimeMachine.png" width="32px" />{$core->get_Lang('restoringsuccess')}</span>
</div>
{/if}
{if $message eq 'updateSuccess' or $message eq 'UpdateSuccess'}
<div id="message" class="update">
	<span><img src="{$URL_IMAGES}/icon_admin/iSync.png" width="32px" />{$core->get_Lang('updatesuccessful')}</span>
</div>
{/if}
{if $message eq 'TrashSuccess'}
<div id="message" class="trash">
	<span><img align="absmiddle" src="{$URL_IMAGES}/icon_admin/Trash-Full.png" width="32px" />{$core->get_Lang('movedtotrash')}</span>
</div>
{/if}
{if $message eq 'DeleteSuccess'}
<div id="message" class="del">
	<span><img src="{$URL_IMAGES}/icon_admin/Del.png" width="32px" />{$core->get_Lang('deletesuccess')}</span>
</div>
{/if}
{if $message eq 'invalidAccess'}
<div id="message" class="del">
	<span><img src="{$URL_IMAGES}/icon_admin/Lock.png" width="32px" />{$core->get_Lang('youdonothavepermission')}</span>
</div>
{/if}
{if $message eq 'PositionSuccess'}
<div id="message" class="pos">
	<span><img align="absmiddle" src="{$URL_IMAGES}/icon_admin/Pos.png" width="32px" />{$core->get_Lang('positionhasbeenchanged')}</span>
</div>
{/if}
<header id="page-header">
	<div id="topnavlink">
		<ul>
			<li><a href="{$PCMS_URL}">{$core->get_Lang('Dashboard')}</a></li>
			<li class="s">|</li>
			<li><a href="{$DOMAIN_NAME}{$access_url}" target="_blank">{$core->get_Lang('clientpage')}</a></li>
			<li class="s">|</li>
			<!--<li><a class="ajManageSystemNote">{$core->get_Lang('notes')}</a></li>
			<li class="s">|</li>-->
			<li><a href="{$PCMS_URL}/index.php?mod=user&act=edit&user_id={$core->encryptID($core->_USER.user_id)}">{$core->get_Lang('myacount')}</a></li>
			<li class="s">|</li>
			<li><a href="{$PCMS_URL}/?mod=login&act=logout"><i class="fa fa-power-off"></i> {$core->get_Lang('logout')}</a></li>
		</ul>
		<div class="HD_box mr20 font12px">
			<span class="white mr5" id="SiteClock"></span>
			<span class="link-orange">|&nbsp;&nbsp;{$core->get_Lang('yourip')}: {$clsISO->getRealIP()} </span>
		</div>
	</div>
	<div class="ui-top-bar">
		<div class="ui-top-bar__branding" id="logo">
			<a href="{$PCMS_URL}" title="{$PAGE_NAME}">
				<img height="{$clsConfiguration->getImageHeight('LogoWhite')}" src="{$clsConfiguration->getValue('LogoWhite')}" alt="{$PAGE_NAME}" />
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
			{*<div class="ui-top-bar__item" style="padding:0.4rem 0;">
				<a href="#" class="top-bar-button text-center link-help" mod_page="{$mod}" act_page="{$act}">
					{$core->makeIcon('support')} 
					<div class="clearfix"></div>
					<span style="width:100%;text-align:center">{$core->get_Lang('Help')}</span>
				</a>
			</div>*}
		</div>
	</div>
</header>
<aside class="sidebar sidebar-default" id="sidebar">
	{$core->getBlock('quick_menu')}
</aside>
<div id="page-main">
	<div id="page-body">
	{literal}
	<script type="text/javascript">
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
	</script>
	{/literal}