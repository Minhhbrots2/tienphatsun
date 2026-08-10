<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
    <a href="{$curl}" title="{$mod}">{$clsCityStore->getTitle($type)}</a>    
    <!-- // -->
    <a href="javascript:window.history.back();" class="back fr">{$core->get_Lang('back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">	
        <h2>{$clsCityStore->getTitle($type)}</h2>
        <p>{$core->get_Lang('Management departures list system')}</p>
    </div>
    <div class="wrap">
        <div class="hastable fl" style="width:52%">
        	<form id="forums" method="post" class="filterForm" action="">
                <div class="fiterbox">
                    <div class="wrap">
                        <div class="searchbox" style="float:left !important; width:100%">
                        	<select name="country_id" onchange="document.getElementById('forums').submit();" class="select" style="height:29px; font-size:14px;">{$clsCountry->makeSelectboxOption($country_id)}</select>
                            <input type="text" class="m-wrap short" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}..." />
                            <a class="btn btn-success fileinput-button" href="javascript:void();" id="searchbtn" style="padding:5px">
                                <i class="icon-search icon-white"></i>
                            </a>
                            <input type="hidden" name="filter" value="filter" />
                        </div>
                    </div>
                </div>
            </form>
            <table cellspacing="0" class="tbl-grid" width="100%">
                <tr>
                	<td class="gridheader"></td>
                    <td class="gridheader"><strong>{$core->get_Lang('index')}</strong></td>
                    <td class="gridheader" style="text-align:left"><strong>{$core->get_Lang('nameofcity')}</strong></td>
                    <td class="gridheader" style="text-align:center"><strong>{$core->get_Lang('func')}</strong></td>
                </tr>
                {if $listItem[0].city_id ne ''}
                {section name=i loop=$listItem}
                <tr class="{cycle values="row1,row2"}">
                	<td class="index"><input class="chkitem" name="chkid_city[]" value="{$listItem[i].city_id}" type="checkbox"></td>
                    <td class="index">{$smarty.section.i.index+1}</td>
                    <td><strong style="font-size:18px">{$clsClassTable->getTitle($listItem[i].city_id)}</strong></td>
                    </td>
                    <td style="vertical-align:middle; width:65px; text-align:right; white-space:nowrap;">
                        <a class="iso-button-action" href="{$PCMS_URL}/?mod={$mod}&act={$act}&action=Add&type={$core->encryptID($type)}&city_id={$listItem[i].city_id}&country_id={$country_id}" title="Thêm địa điểm này"><i class="icon-plus-sign icon-white"></i> {$core->get_Lang('Add')}</a>
                    </td>
                </tr>
                {/section}
                {else}
                <tr><td colspan="6" style="text-align:center">{$core->get_Lang('No Data')}!</td></tr>
                {/if}
            </table>
            <input type="hidden" id="list_selected_chkitem" />
            <div class="clearfix" style="height:10px"></div>
            <div style="width:96%; padding:2%; background: #fff;border:1px solid #ccc">
                <label><input type="checkbox" id="check_all" /> {$core->get_Lang('selectall')}</label>
                <a _type="{$type}" class="btn btn-success fileinput-button clickToSaveCityStore"> 
                    <i class="icon-plus icon-white"></i> 
                    <span>{$core->get_Lang('Save')}</span>
                </a>
            </div>
            <div class="clearfix"></div>
            <div class="adminPaging">
                <ul class="lstAdminPaging">
                {section name=i loop=$listPageNumber}
                    <li>
                        <a href="{$PCMS_URL}/{$link_page_current}&page={$listPageNumber[i]}" {if $listPageNumber[i] eq $currentPage}class="active"{/if}>{$listPageNumber[i]}</a>
                    </li>
                {/section}
                </ul>
                <div class="report">
                    <strong>{$core->get_Lang('statistical')}</strong>: <strong>{$totalRecord}</strong> {$core->get_Lang('records')}/<strong>{$totalPage}</strong> {$core->get_Lang('page')}. {$core->get_Lang('youareonpagenumber')} <strong>{$currentPage}</strong>.
                </div>
            </div>
        </div>
        <div class="hastable fr" style="width:46%">
            <div class="fiterbox">
                <div class="wrap">
                    <div class="searchbox" style="float:left !important; width:100%">
                    	<h1 style="font-family:Cambria; font-weight:bold; font-style:italic;">{$clsCityStore->getTitle($type)} {$core->get_Lang('selected')}</h1>
                    </div>
                </div>
            </div>
            <table cellspacing="0" class="tbl-grid">
                <tr>
                    <td class="gridheader"><strong>{$core->get_Lang('index')}</strong></td>
                    <td class="gridheader" style="text-align:left"><strong>{$core->get_Lang('nameofcity')}</strong></td>
                    <td class="gridheader" colspan="4" style="width:4%"><strong>{$core->get_Lang('move')}</strong></td>
                    <td class="gridheader" style="text-align:center"><strong>{$core->get_Lang('func')}</strong></td>
                </tr>
                {if $listSelected[0].city_id ne ''}
                {section name=i loop=$listSelected}
                <tr class="{cycle values="row1,row2"}">
                    <td class="index">{$smarty.section.i.index+1}</td>
                    <td><strong style="font-size:18px">{$clsClassTable->getTitle($listSelected[i].city_id)}</strong></td>
                    </td>
                    <td style="vertical-align: middle;text-align:center">
                        {if !$smarty.section.i.first}
                        <a title="{$core->get_Lang('movetop')}" href="javascript:void(0);" class="ajMoveCityStore" _citystore_id="{$listSelected[i].citystore_id}" _city_id="{$listSelected[i].city_id}" {if $country_id}_country_id="{$country_id}"{/if} direct="movetop"><i class="icon-circle-arrow-up"></i></a>
                        {/if}
                    </td>
                    <td style="vertical-align: middle;text-align:center">
                        {if !$smarty.section.i.last}
                        <a title="{$core->get_Lang('movebottom')}" href="javascript:void(0);" class="ajMoveCityStore" _citystore_id="{$listSelected[i].citystore_id}" _city_id="{$listSelected[i].city_id}" {if $country_id}_country_id="{$country_id}"{/if} direct="movebottom"><i class="icon-circle-arrow-down"></i></a>
                        {/if}
                    </td>
                    <td style="vertical-align: middle;text-align:center">
                        {if !$smarty.section.i.first}
                        <a title="{$core->get_Lang('moveup')}" href="javascript:void(0);" class="ajMoveCityStore" _citystore_id="{$listSelected[i].citystore_id}" _city_id="{$listSelected[i].city_id}" {if $country_id}_country_id="{$country_id}"{/if} direct="moveup"><i class="icon-arrow-up"></i></a>
                        {/if}
                    </td>
                    <td style="vertical-align: middle;text-align:center">
                        {if !$smarty.section.i.last}
                        <a title="{$core->get_Lang('movedown')}" href="javascript:void(0);" class="ajMoveCityStore" _citystore_id="{$listSelected[i].citystore_id}" _city_id="{$listSelected[i].city_id}" {if $country_id}_country_id="{$country_id}"{/if} direct="movedown"><i class="icon-arrow-down"></i></a>
                        {/if}
                    </td>
                    <td style="vertical-align: middle; width: 65px; text-align: right; white-space: nowrap;">
                        <a class="iso-cancel-action clkDeleteCityStore" _citystore_id="{$listSelected[i].citystore_id}" title="{$core->get_Lang('Cancel')}" href="javascript:void();"><i class="icon-upload icon-white"></i> {$core->get_Lang('Cancel')}</a>
                    </td>
                </tr>
                {/section}
                {else}
                <tr><td colspan="7" style="text-align:center">{$core->get_Lang('No Data')}!</td></tr>
                {/if}
            </table>
        </div>
    </tr>
</div>
{literal}<style>.disabled{-moz-opacity:.8;-webkit-opacity:.8;-o-opacity:.8;opacity:.8;filter:anpha(opacity=80)}</style>{/literal}
<script type="text/javascript">
var required_country = "{$core->get_Lang('You not selected country')}";
var required_city = "{$core->get_Lang('You not selected city')}";
</script>
{literal}
<script type="text/javascript">
$().ready(function(){
	$(document).on('click', '.clkDeleteCityStore', function(ev){
		if(confirm(confirm_delete)){
			var $_this = $(this);
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url:path_ajax_script+'/index.php?mod='+mod+'&act=ajDeleteCityStore',
				data: {'citystore_id': $_this.attr('_citystore_id')},
				dataType: "html",
				success: function(html){
					window.location.reload(true);
				}
			});
		}
	});
	$(document).on('click', '.ajMoveCityStore', function(ev){
		var $_this = $(this);
		var adata = {
			'citystore_id' : $_this.attr('_citystore_id'),
			'city_id' : $_this.attr('_city_id'),
			'country_id' : $_this.attr('_country_id'),
			'direct' : $_this.attr('direct')
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/?mod="+mod+"&act=ajMoveCityStore",
			data: adata,
			dataType: "html",
			success: function(html){
				window.location.reload(true);
			}
		});
		return false;
	});
	$(document).on('click', '.clickToSaveCityStore', function(ev){
		var _this = $(this);
		if($('select[name=country_id]').val()==''){
			alertify.error(required_country);
			$('select[name=country_id]').focus();
			return false;
		}
		if($('#list_selected_chkitem').val()==''){
			alertify.error(required_city);
			return false;
		}
		var adata = {
			'country_id': $('select[name=country_id]').val(),
			'list_city_id' : $('#list_selected_chkitem').val(),
			'type' : _this.attr('_type')
		};
		_this.find('span').text(loading);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod="+mod+"&act=ajSaveStoreForCity",
			data: adata,
			dataType: "html",
			success: function(html){
				_this.find('span').text(save);
				$('#check_all').removeAttr('checked');
				window.location.reload(true);
			}
		});
	});
});
</script>
{/literal}