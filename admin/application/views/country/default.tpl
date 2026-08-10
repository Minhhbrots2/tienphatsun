<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    {if $clsConfiguration->getValue('SiteModActive_continent')}
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/?mod={$mod}&continent_id={$continent_id}" title="{$mod}">{$clsContinent->getTitle($continent_id)}</a>
    {/if}
    <a>&raquo;</a>
    <a href="javascript:void(0)" title="{$mod}">{$core->get_Lang('country')}</a>
    <!-- // -->
    <a href="javascript:window.history.back();" class="back fr">{$core->get_Lang('back')}</a>
</div>
<div class="clearfix"></div>
{if $msg eq 'DeleteFailed'}
<div style="padding:15px; padding-top:0;">
	<div style="padding:10px; background:red; color:#fff; font-size:14px; text-align:center; "><img src="{$URL_IMAGES}/warning-20.png" title="" align="absmiddle" />
<strong>{$core->get_Lang('Warning')}:</strong> {$core->get_Lang('exitscityofcountry')}
</div>
</div>
<div class="clearfix"></div>
{/if}
<div class="container-fluid">
    <div class="page-title">
        <h2>{$core->get_Lang('country')} <a class="btn btn-success" href="{$PCMS_URL}/?mod={$mod}&act=edit" title="{$core->get_Lang('add')}"> <i class="icon-plus icon-white"></i></a></h2>
		{assign var = setting value = 'SiteIntroModule_'|cat:$mod|cat:'_'|cat:$_LANG_ID}
		{if $clsConfiguration->getValue($setting) ne ''}
        <p>{$clsConfiguration->getValue($setting)}</p>
		{/if}
    </div>
	<div class="clearfix"><br /></div>
    <div class="wrap">
        <form id="forums" method="post" action="" name="filter" class="filterForm">
           <div class="filterbox">
                <div class="wrap">
                    <div class="searchbox">
                        {if $lstContinent && $clsConfiguration->getValue('SiteModActive_continent') && $core->checkAccess('continent')}
                        <select onchange="_reload();" name="continent_id" class="slb mr5 fl" style="padding:5px;">
                            <option value="">-- {$core->get_Lang('Select Continent')} --</option>
                            {section name=i loop=$lstContinent}
                            <option {if $continent_id eq $lstContinent[i].continent_id}selected="selected"{/if} value="{$lstContinent[i].continent_id}">{$clsContinent->getTitle($lstContinent[i].continent_id)}</option>
                            {/section}
                        </select>
                        {/if}
                        <input type="text" class="text" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
                        <a class="btn btn-success" href="javascript:void();" id="searchbtn" style=" padding:5px">
                            <i class="icon-search icon-white"></i>
                        </a>
                        <a href="{$PCMS_URL}/?mod={$mod}&act=setting" class="btn btn-danger" title="{$core->get_Lang('settings')}"><i class="icon-cog icon-white"></i> <span>{$core->get_Lang('settings')}</span> </a>
                    </div>
                    <div class="fr group_buttons">
                        <a href="{$PCMS_URL}/?mod={$mod}{$pUrl}" class="btn btn-warning" style="color:#fff"> <i class="icon-folder-open icon-white"></i> <span>{$core->get_Lang('all')} ({$number_all})</span> </a>
                        <a href="{$PCMS_URL}/?mod={$mod}{$pUrl}&type_list=Trash" class="btn btn-danger" style="color:#fff"> <i class="icon-warning-sign icon-white"></i> <span>{$core->get_Lang('trash')} ({$number_trash})</span> </a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-delete-all" clsTable="Country" style="color:#fff; display:none"> <i class="icon-remove icon-white"></i> <span>{$core->get_Lang('Delete Options')}</span> </a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="filter" value="filter" />
        </form>
        <input id="list_selected_chkitem" style="display:none" value="0" />
        <table cellspacing="0" class="tbl-grid" width="100%">
            <tr>
                <td class="gridheader"><input id="check_all" type="checkbox" /></td>
                <td class="gridheader" style="text-align:left"><strong>{$core->get_Lang('nameofcountry')}</strong></td>
                {if $core->checkAccess('city')}
                <td class="gridheader text-left" width="10%"><strong>{$core->get_Lang('city')}</strong></td>
                {/if}
                {if $clsConfiguration->getValue('SiteActive_area') and $core->checkAccess('area')}
                <td class="gridheader text-left" width="10%"><strong>{$core->get_Lang('cities')}</strong></td>
                {/if}
                {if $clsConfiguration->getValue('SiteActive_departurecity')}
                <td class="gridheader text-left" width="15%"><strong>{$core->get_Lang('departurepoint')}</strong></td>
                {/if}
                {if $clsConfiguration->getValue('SiteActive_topcity')}
                <td class="gridheader text-left" width="15%"><strong>{$core->get_Lang('topdestinations')}</strong></td>
                {/if}
                <td class="gridheader" style="width:6%;"><strong>{$core->get_Lang('status')}</strong></td>
                <td class="gridheader"><strong>{$core->get_Lang('func')}</strong></td>
            </tr>
            <tbody id="tbl_sys_country">
                {section name=i loop=$allItem}
                <tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
                    <td class="index"><input name="p_key[]" class="chkitem" type="checkbox" value="{$allItem[i].country_id}" /></td>
                    <td>
                        <strong class="title mr10">{if $clsClassTable->getOneField('is_online',$allItem[i].country_id) eq 0}<span style="color:#F90">[PRIVATE]</span>{/if} <span style="font-size:18px">{$clsClassTable->getTitle($allItem[i].country_id)}</span></strong>
                        {if $clsConfiguration->getValue('SiteActive_guide') and $core->checkAccess('guide')}
                        <a class="mr10" href="{$PCMS_URL}/?mod=guide&country_id={$allItem[i].country_id}">
                        	<i class="fa fa-folder-open"></i> {$core->get_Lang('travelguide')}
                        </a>
                        {/if}
                        {if $clsConfiguration->getValue('SiteHasChild_slide')}
                        <a href="{$PCMS_URL}/index.php?mod=slide&mod_page={$mod}&act_page={$act}&target_id={$allItem[i].$pkeyTable}" title="{$core->get_Lang('listslide')}">
                            <i class="fa fa-folder-open"></i>  {$core->get_Lang('listslide')} <strong style="color:#c00000;">({$clsISO->countTotalSlide($mod,$act,$allItem[i].$pkeyTable)})</strong>
                        </a>
                        {/if}
                        {if $allItem[i].is_trash eq '1'}<span class="fr" style="color:#CCC">{$core->get_Lang('intrash')}</span>{/if}
                    </td>
                    {if $clsConfiguration->getValue('SiteActive_area') and $core->checkAccess('region')}
                    <td>
                    	<a href="{$PCMS_URL}/index.php?mod=region&country_id={$allItem[i].country_id}{$pUrl}">
                        	<i class="fa fa-folder-open"></i> {$core->get_Lang('regions')} <span class="color_r">({$clsClassTable->countNumberRegion($allItem[i].country_id)})</span>
                        </a>
                    </td>
                    {/if}
                    {if $core->checkAccess('city')}
                    <td>
                    	<a href="{$PCMS_URL}/index.php?mod=city&country_id={$allItem[i].country_id}{$pUrl}">
                        	<i class="fa fa-folder-open"></i> {$core->get_Lang('cities')} <span class="color_r">({$clsClassTable->countNumberCity($allItem[i].country_id)})</span>
                        </a>
                    </td>
                    {/if}
                    {if $clsConfiguration->getValue('SiteActive_departurecity')}
                    <td>
                    	<a href="{$PCMS_URL}/index.php?mod={$mod}&act=store&country_id={$allItem[i].country_id}&type={$core->encryptID('DEPARTUREPOINT')}"><i class="fa fa-folder-open"></i> {$core->get_Lang('departurepoint')} <span class="color_r">({$clsClassTable->countNumberCityStore($allItem[i].country_id,'DEPARTUREPOINT')})</span></a></td>
                    {/if}
                    {if $clsConfiguration->getValue('SiteActive_topcity') && $core->checkAccess('city')}
                    <td><a href="{$PCMS_URL}/index.php?mod={$mod}&act=store&country_id={$allItem[i].country_id}&type={$core->encryptID('TOP')}"><i class="fa fa-folder-open"></i> {$core->get_Lang('topdestinations')} <span class="color_r">({$clsClassTable->countNumberCityStore($allItem[i].country_id,'TOP')})</span></a></td>
                    {/if}
                    <td style="text-align:center">
                        <a href="javascript:void(0);" class="SiteClickPublic" clsTable="Country" pkey="country_id" sourse_id="{$allItem[i].country_id}" rel="{$clsClassTable->getOneField('is_online',$allItem[i].country_id)}" title="{$core->get_Lang('Click to change status')}">
                            {if $clsClassTable->getOneField('is_online',$allItem[i].country_id) eq '1'}
                            <i class="fa fa-check-circle green"></i>
                            {else}
                            <i class="fa fa-minus-circle red"></i>
                            {/if}
                        </a>
                    </td>
                    <td style="vertical-align: middle; width: 10px; text-align:left; white-space: nowrap;">
                        <div class="btn-group">
                            <button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
                            <ul class="dropdown-menu" style="right:0px !important">
                                {if $allItem[i].is_trash eq '1'}
                                <li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&country_id={$core->encryptID($allItem[i].country_id)}{$pUrl}"><i class="icon-refresh"></i> {$core->get_Lang('restore')}</a></li>
                                <li><a title="{$core->get_Lang('delete')}" href="{$PCMS_URL}/?mod={$mod}&act=delete&country_id={$core->encryptID($allItem[i].country_id)}{$pUrl}"><i class="icon-remove"></i> {$core->get_Lang('delete')}</a></li>
                                {else}
                                <li><a title="{$core->get_Lang('view')}" target="_blank" href="{$DOMAIN_NAME}{$clsClassTable->getLink($allItem[i].country_id)}"><i class="icon-eye-open"></i> {$core->get_Lang('view')}</a></li>
                                <li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&country_id={$core->encryptID($allItem[i].country_id)}{$pUrl}"><i class="icon-edit"></i> {$core->get_Lang('edit')}</a></li>
                                <li><a title="{$core->get_Lang('trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&country_id={$core->encryptID($allItem[i].country_id)}{$pUrl}"><i class="icon-trash "></i>  {$core->get_Lang('trash')}</a></li>
                                {/if}
                            </ul>
                        </div>
                    </td>
                </tr>	
                {/section}
            </tbody>
        </table>
        <div class="adminPaging">
            <ul class="lstAdminPaging">
                {section name=i loop=$listPageNumber}
                <li><a href="{$PCMS_URL}/{$link_page_current}&page={$listPageNumber[i]}" {if $listPageNumber[i] eq $currentPage}class="active"{/if}>{$listPageNumber[i]}</a>
                </li>
                {/section}
            </ul>
            <div class="report">
                <strong>{$core->get_Lang('statistical')}</strong>: <strong>{$totalRecord}</strong> {$core->get_Lang('records')}/<strong>{$totalPage}</strong> {$core->get_Lang('page')}. {$core->get_Lang('youareonpagenumber')} <strong>{$currentPage}</strong>.
            </div>
        </div>
	</div>
</div>