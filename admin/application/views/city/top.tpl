<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
    <a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod=country" title="{$core->get_Lang('country')}">{$core->get_Lang('country')}</a>
	<a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}" title="{$mod}">{$core->get_Lang('topcities')}</a>
    <!-- Back-->
    <a href="javascript:window.history.back();" class="back fr">{$core->get_Lang('back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">
        <h2>{$core->get_Lang('listtopcities')} {if $country_id}{$clsCountry->getTitle($country_id)}{/if}</h2>
        <p>{$core->get_Lang('systemmanagementtopcities')}</p>
    </div>
    <div class="clearfix"></div>
    <div class="wrap">
        <div class="span60 fl">
            <div class="hastable">
            	<form id="forums" method="post" class="filterForm" action="">
					<div class="wrap filterbox">
						<div class="searchbox" style="float:left !important; width:100%">
							<input id="key" type="text" class="m-wrap short" name="keyword" placeholder="{$core->get_Lang('search')} ..." /> 
						</div>
					</div>
                </form>
                <div class="clearfix"><br /></div>
                <table cellspacing="0" class="tbl-grid">
                    <tr>
                        <td class="gridheader"><strong>{$core->get_Lang('index')}</strong></td>
                        <td class="gridheader" style="text-align:left"><strong>{$core->get_Lang('nameofcities')}</strong></td>
                        <td class="gridheader" style="text-align:left"><strong>{$core->get_Lang('nameofcountry')}</strong></td>
                        <td class="gridheader" style="width:3%" colspan="4"><strong>{$core->get_Lang('move')}</strong></td>
                        <td class="gridheader" style=" width:8%;text-align:center"><strong>{$core->get_Lang('func')}</strong></td>
                    </tr>
                   <tbody id="tblHoderCity"></tbody>
                </table>
				<div class="clearfix" style="height:5px"></div>
				<div class="pagination_box">
					<div class="wrap holderEvent_tbl" id="dataTable_paginate" style="min-height:16px">
					<!-- Ajax Loading pagination -->
					</div>
				</div>
            </div>
        </div>
        <div style=" width:38%" class="fr">
            <div class="row-field">
                <div class="row-heading"><i class="icon-search"></i> {$core->get_Lang('Selecting new top cities')}</div>
                <div class="coltrols">
					<u class="color_r">{$core->get_Lang('Select country')}</u>&nbsp;
                    <select class="slb span60" style="padding:5px; background:#CCC" id="slb_country">
                        {$clsCountry->makeSelectboxOption($country_id, $domain_id)}
                    </select>
					<div class="clearfix"></div>
					<div class="infobox" style="margin-left:0px !important">
                    	{$core->get_Lang('Tick choose the city to add new attractions for each Country')}
					</div>
                    <ul id="quickSearch" class="listSearchQuick" style="height:300px">
                    </ul>
                    <input type="hidden" id="list_selected_chkitem" />
                    <div class="clearfix" style="height:10px"></div>
                    <label><input type="checkbox" id="check_all" /> {$core->get_Lang('selectall')}</label>
                    <a href="javascript:;" title="{$core->get_Lang('save')}" id="clickToSaveTop"> 
                       <u class="color_r">{$core->get_Lang('save')}</u>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	var $country_id = '{$country_id}';
</script>
{literal}
<style>.listSearchQuick{ max-height:400px;}</style>
{/literal}
<script type="text/javascript" src="{$URL_TEMPLATES}/default/city/jquery.city.js"></script>