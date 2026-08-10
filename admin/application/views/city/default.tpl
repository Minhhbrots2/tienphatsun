<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
	<a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod=country">{$core->get_Lang('country')}</a>
	<a>&raquo;</a>
    <a>{$clsCountryEx->getTitle($country_id)}</a>
    <!-- Back-->
    <a href="javascript:window.history.back();" class="back fr">{$core->get_Lang('back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">
        <h2>{$core->get_Lang('cities')} <a class="btn btn-success" href="{$PCMS_URL}/?mod={$mod}&act=edit{$pUrl}" title="{$core->get_Lang('add')}"> <i class="icon-plus icon-white"></i></a></h2>
        <p>{$core->get_Lang('systemmanagementcities')} {if $country_id} {$clsCountryEx->getTitle($country_id)}{/if}</p>
    </div>
    <div class="clearfix"><br /></div>
    <form id="forums" method="post" action="" class="filterForm">
        <div class="ui-action">
        	<div class="fl fiterbox" style="width:100%">
                <div class="wrap">
                    <div class="searchbox" style="float:left !important; width:64%">
                    	{if $lstContinent && $clsConfiguration->getValue('SiteModActive_continent') and $core->checkAccess('continent')}
                    	<select name="continent_id" onchange="_reload();" style="width:120px;font-size:14px; padding:3px" class="slb">
                        	<option value="">-- {$core->get_Lang('Select Continent')} --</option>
                        	{section name=i loop=$lstContinent}
                            <option {if $continent_id eq $lstContinent[i].continent_id}selected="selected"{/if} value="{$lstContinent[i].continent_id}">{$clsContinent->getTitle($lstContinent[i].continent_id)}</option>
                            {/section}
                        </select>
                        {/if}
                        {if $lstCountryEx && $clsConfiguration->getValue('SiteModActive_country') and $core->checkAccess('country')}
                    	<select name="country_id" onchange="_reload();" style="width:130px;font-size:14px; padding:3px" class="slb">
                        	<option value="">-- {$core->get_Lang('Select Country')} --</option>
                            {section name=i loop=$lstCountryEx}
                            <option {if $country_id eq $lstCountryEx[i].country_id}selected="selected"{/if} value="{$lstCountryEx[i].country_id}">{$clsCountryEx->getTitle($lstCountryEx[i].country_id)}</option>
                            {/section}
                        </select>
                        {/if}
                        {if $lstRegion and $core->checkAccess('area') && $clsConfiguration->getValue('SiteActive_region')}
                        <select name="region_id" onchange="_reload();" style="font-size:14px; padding:3px" class="slb">
                        	<option value="">-- {$core->get_Lang('Select Area')} --</option>
                            {section name=i loop=$lstRegion}
                            <option {if $region_id eq $lstRegion[i].region_id}selected="selected"{/if} value="{$lstRegion[i].region_id}">{$clsRegion->getTitle($lstRegion[i].region_id)}</option>
                            {/section}
                        </select>
                        {/if}
                        <input type="text" class="m-wrap medium" style="width:130px" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
                        <a class="btn btn-success fileinput-button" href="javascript:void();" id="searchbtn" style=" padding:5px">
                            <i class="icon-search icon-white"></i>
                        </a>
                        <a href="{$PCMS_URL}/?mod={$mod}&act=setting" class="btn btn-danger" title="{$core->get_Lang('settings')}"><i class="icon-cog icon-white"></i> <span>{$core->get_Lang('settings')}</span> </a>
                    </div>
                    <div class="fr group_buttons">
                        <a href="{$PCMS_URL}/?mod={$mod}{$pUrl}" class="btn btn-warning fileinput-button">
                            <i class="icon-folder-open icon-white"></i> <span>{$core->get_Lang('all')} ({$number_all})</span> 
                        </a>
                        <a href="{$PCMS_URL}/{$link_page_current_2}&type_list=Trash" class="btn btn-danger fileinput-button">
                            <i class="icon-warning-sign icon-white"></i> <span>{$core->get_Lang('trash')} ({$number_trash})</span> 
                        </a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-delete-all" clsTable="City" style="color:#fff; display:none"> <i class="icon-remove icon-white"></i> <span>{$core->get_Lang('Delete Options')}</span> </a>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="filter" value="filter" />
    </form>
    <input id="list_selected_chkitem" style="display:none" value="0" />
    <div class="hastable">
    	<table cellspacing="0" class="tbl-grid" id="tbl_sys_country">
            <tr>
            	<td class="gridheader"><input id="check_all" type="checkbox" /></td>
                <td class="gridheader"><strong>{$core->get_Lang('index')}</strong></td>
                <td class="gridheader" style="text-align:left;"><strong>{$core->get_Lang('nameofcity')}</strong></td>
                <td class="gridheader" style="width:6%;"><strong>{$core->get_Lang('status')}</strong></td>
                <td class="gridheader" colspan="4" style="width:4%"><strong>{$core->get_Lang('move')}</strong></td>
            	<td class="gridheader"><strong>{$core->get_Lang('func')}</strong></td>
            </tr>
            {section name=i loop=$allItem}
            <tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
            	<td class="index"><input name="p_key[]" class="chkitem" type="checkbox" value="{$allItem[i].city_id}" /></td>
                <td class="index">{$smarty.section.i.index+1} </td>
                <td>
                	<strong class="title mr10">{if $clsClassTable->getOneField('is_online',$allItem[i].city_id) eq 0}<span style="color:#F90">[PRIVATE]</span>{/if} <span style="font-size:16px">{$clsClassTable->getTitle($allItem[i].city_id)}</span></strong>
                    {if $clsConfiguration->getValue('SiteHasChild_slide')}
                    <a href="{$PCMS_URL}/index.php?mod=slide&mod_page={$mod}&act_page={$act}&target_id={$allItem[i].$pkeyTable}" title="{$core->get_Lang('listslide')}">
                        <i class="fa fa-folder-open"></i>  {$core->get_Lang('listslide')} <strong style="color:#c00000;">({$clsISO->countTotalSlide($mod,$act,$allItem[i].$pkeyTable)})</strong>
                    </a>
                    {/if}
                	{if $allItem[i].is_trash eq '1'}<span class="fr" style="color:#CCC">{$core->get_Lang('intrash')}</span>{/if}
                </td>
                <td style="text-align:center">
                    <a href="javascript:void(0);" class="SiteClickPublic" clsTable="City" pkey="city_id" sourse_id="{$allItem[i].city_id}" rel="{$clsClassTable->getOneField('is_online',$allItem[i].city_id)}" title="{$core->get_Lang('Click to change status')}">
                        {if $clsClassTable->getOneField('is_online',$allItem[i].city_id) eq '1'}
                        <i class="fa fa-check-circle green"></i>
                        {else}
                        <i class="fa fa-minus-circle red"></i>
                        {/if}
                    </a>
                </td>
				<td style="vertical-align: middle;text-align:center">
                    {if !$smarty.section.i.first}
                    <a title="{$core->get_Lang('movetop')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movetop&city_id={$allItem[i].city_id}{$pUrl}"><i class="icon-circle-arrow-up"></i></a>
                    {/if}
                </td>
                <td style="vertical-align: middle;text-align:center">
                    {if !$smarty.section.i.last}
                    <a title="{$core->get_Lang('movebottom')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movebottom&city_id={$allItem[i].city_id}{$pUrl}"><i class="icon-circle-arrow-down"></i></a>
                    {/if}
                </td>
                <td style="vertical-align: middle;text-align:center">
                    {if !$smarty.section.i.first}
                    <a title="{$core->get_Lang('moveup')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=moveup&city_id={$allItem[i].city_id}{$pUrl}"><i class="icon-arrow-up"></i></a>
                    {/if}
                </td>
                <td style="vertical-align: middle;text-align:center">
                    {if !$smarty.section.i.last}
                    <a title="{$core->get_Lang('movedown')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movedown&city_id={$allItem[i].city_id}{$pUrl}"><i class="icon-arrow-down"></i></a>
                    {/if}
                </td>
                <td align="center" style="vertical-align: middle; text-align:center; width: 40px; white-space: nowrap;">
                    <div class="btn-group">
                        <button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
                        <ul class="dropdown-menu" style="right:0px !important">
                            {if $allItem[i].is_trash eq '0'}
                            <li><a href="{$DOMAIN_NAME}{$clsClassTable->getLink($allItem[i].city_id)}" target="_blank" title="{$core->get_Lang('view')}"><i class="icon-eye-open"></i> <span>{$core->get_Lang('view')}</span></a></li>
                            <li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&city_id={$core->encryptID($allItem[i].city_id)}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
                            <li><a title="{$core->get_Lang('trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&city_id={$core->encryptID($allItem[i].city_id)}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
                            {else}
                            <li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&city_id={$core->encryptID($allItem[i].city_id)}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
                            <li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&city_id={$core->encryptID($allItem[i].city_id)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('Delete')}</span></a></li>
                            {/if}
                        </ul>
                    </div>
                </td>
            </tr>	
            {/section}
        </table>
		<div class="statistical mt5">
			<table width="100%" border="0" cellpadding="3" cellspacing="0">
				<tr>
					<td width="50%" align="left">{$core->get_Lang('statistical')} <strong>{$totalRecord}</strong> {$core->get_Lang('records')}/<strong>{$totalPage}</strong> {$core->get_Lang('page')}. {$core->get_Lang('youareonpagenumber')} <strong>{$currentPage}</strong></td>
					<td width="50%" align="right">
						{$core->get_Lang('gotopage')}:
						<select name="page" onchange="window.location = this.options[this.selectedIndex].value">
							{section name=i loop=$listPageNumber}
							<option {if $listPageNumber[i] eq $currentPage}selected="selected"{/if} value="{$PCMS_URL}/{$link_page_current}&page={$listPageNumber[i]}">{$listPageNumber[i]}</option>
							{/section}
						</select>
					</td>
				</tr>
			</table>
		</div>
    </div>
</div>