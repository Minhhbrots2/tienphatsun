<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
	<a href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('city')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}&act={$act}" title="{$mod}">{$core->get_Lang('settingcity')}</a>
</div>
<div class="clearfix"></div>
<div class="container-fluid">
    <div class="page-title">
        <h2>{$core->get_Lang('settingcity')}</h2>
        <p>{$core->get_Lang('systemmanagementsettingcity')}</p>
    </div>
    <div class="wrap mt10 mb20">
		<div class="hd">
			<span class="bold">{$core->get_Lang('selectsetting')}</span>
		</div>
		<ul class="rsl-list-buttons">
			{assign var=lstCityType value=$clsCityStore->getListType()}
			{foreach from=$lstCityType key=k item=v}
			<li>
				<a href="{$PCMS_URL}/?admin&mod=country&act=store&type={$core->encryptID($k)}">
					<img class="imgIcon" src="{$URL_IMAGES}/checklist-icon.png" width="32" height="32" />
					<span class="text">{$v}</span>
				</a>
			</li>
			{/foreach}
		</ul>
	</div>
	<div id="clienttabs">
		<ul>
			<li><a><i class="fa fa-cog"></i> {$core->get_Lang('general')}</a></li>
            <li class="tabchild"><a href="#"><i class="iso-media"></i> {$core->get_Lang('seosdvanced')}</a></li>
		</ul>
	</div>
	<div id="tab_content">
		<div class="tabbox">
			<form method="post" action="" enctype="multipart/form-data">
				<table class="form" cellpadding="3" cellspacing="3">
                	{if $_DEV && $clsConfiguration->getValue('SiteConfigModLink')}
					<tr>
						<td class="fieldlabel span20">{$core->get_Lang('link')}</td>
						<td class="fieldarea">
                        	{assign var=site_city_link value=site_city_link_$_LANG_ID}
							<input class="text full required" name="iso-{$site_city_link}" value="{$clsConfiguration->getValue($site_city_link)}" maxlength="255" type="text" />
						</td>
					</tr>
                    {/if}
					<tr>
						<td class="fieldlabel">{$core->get_Lang('intropage')}</td>
						<td class="fieldarea">
                        	{assign var=site_city_intro value=site_city_intro_$_LANG_ID}
                            <textarea id="textarea_intro_editor{$now}" class="textarea_intro_editor" name="iso-{$site_city_intro}" style="width:100%">{$clsConfiguration->getValue($site_city_intro)}</textarea>
						</td>
					</tr>
				</table>
				<div class="clearfix mt10"></div>
				<fieldset class="submit-buttons">
					{$saveBtn}
					<input value="UpdateConfiguration" name="submit" type="hidden">
				</fieldset>
			</form>
		</div>
        <div class="tabbox" style="display:none">
            {$core->getBlock('meta_box')}
        </div>
	</div>
</div>
<script type="text/javascript" src="{$PCMS_URL}/editor/tiny_mce/tiny_mce.js"></script>
{literal}
<script type="text/javascript">
	$(document).ready(function(){
		$(".datepicker").datepicker();
	});
</script>
{/literal}