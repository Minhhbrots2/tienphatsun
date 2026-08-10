<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}&act={$act}" title="{$mod}">{$core->get_Lang('departurepoint')}</a>    
    <!-- // -->
    <a href="javascript:window.history.back();" class="back fr">{$core->get_Lang('back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">	
        <h2>{$core->get_Lang('Departure Point Management')}</h2>
        <p>Quản lý danh sách điểm khởi hành trên hệ thống</p>
    </div>
    <div class="wrap">
        <div class="hastable fl" style="width:49%">
        	<form id="forums" method="post" class="filterForm" action="">
                <div class="fiterbox">
                    <div class="wrap">
                        <div class="searchbox" style="float:left !important; width:100%">
                        	<select name="country_id" onchange="document.getElementById('forums').submit();" class="select" style="height:29px; font-size:14px;">{$clsCountry->makeSelectboxOption($country_id)}</select>
                            <input type="text" class="m-wrap short" name="keyword" value="{$keyword}" placeholder="Tìm kiếm..." />
                            <a class="btn btn-success fileinput-button" href="javascript:void();" id="searchbtn" style="padding:5px">
                                <i class="icon-search icon-white"></i>
                            </a>
                            <input type="hidden" name="filter" value="filter" />
                        </div>
                    </div>
                </div>
            </form>
            <table cellspacing="0" class="tbl-grid">
                <tr>
                    <td class="gridheader"><strong>{$core->get_Lang('index')}</strong></td>
                    <td class="gridheader" style="text-align:left"><strong>{$core->get_Lang('nameofcity')}</strong></td>
                    <td class="gridheader" style="text-align:center"><strong>{$core->get_Lang('func')}</strong></td>
                </tr>
                {if $listCity[0].city_id ne ''}
                {section name=i loop=$listCity}
                <tr class="{cycle values="row1,row2"}">
                    <td class="index">{$smarty.section.i.index+1}</td>
                    <td><strong style="font-size:18px">{$clsClassTable->getTitle($listCity[i].city_id)}</strong></td>
                    </td>
                    <td style="vertical-align:middle; width:65px; text-align:right; white-space:nowrap;">
                        <a class="iso-button-action clkAddStartPoint" _city_id="{$listCity[i].city_id}" _country_id="{$listCity[i].country_id}" title="Thêm địa điểm này" href="javascript:void();"><i class="icon-plus-sign icon-white"></i> Thêm</a>
                    </td>
                </tr>
                {/section}
                {else}
                <tr><td colspan="6" style="text-align:center">Hiện chưa có chương trình tour nào được nhập trong hệ thống!</td></tr>
                {/if}
            </table>
            <div class="clearfix"><br /></div>
            <div id="adminPaging">
                <div class="report"><strong>Thống kê</strong>: <strong>{$totalRecord}</strong> bản ghi/<strong>{$totalPage}</strong> trang. Bạn đang ở trang số <strong>{$currentPage}</strong>.</div>
                <ul class="lstAdminPaging">
                {section name=i loop=$listPageNumber}
                    <li><a href="{$PCMS_URL}/{$link_page_current}&page={$listPageNumber[i]}" {if $listPageNumber[i] eq $currentPage}class="active"{/if}>{$listPageNumber[i]}</a></li>
                {/section}
                </ul>
            </div>
        </div>
        <div class="hastable fr" style="width:49%">
            <div class="fiterbox">
                <div class="wrap">
                    <div class="searchbox" style="float:left !important; width:100%">
                    	<h1 style="font-family:Cambria; font-weight:bold; font-style:italic;">{$core->get_Lang('departureselected')}</h1>
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
                {if $listStartPoint[0].city_id ne ''}
                {section name=i loop=$listStartPoint}
                <tr class="{cycle values="row1,row2"}">
                    <td class="index">{$smarty.section.i.index+1}</td>
                    <td><strong style="font-size:18px">{$clsClassTable->getTitle($listStartPoint[i].city_id)}</strong></td>
                    </td>
                    <td style="vertical-align: middle;text-align:center">
                        {if !$smarty.section.i.first}
                        <a title="Di chuyển lên trên cùng" href="javascript:void(0);" class="ajMoveStartPoint" _city_id="{$listStartPoint[i].city_id}" {if $country_id}_country_id="{$country_id}"{/if} direct="movetop"><i class="icon-circle-arrow-up"></i></a>
                        {/if}
                    </td>
                    <td style="vertical-align: middle;text-align:center">
                        {if !$smarty.section.i.last}
                        <a title="Di chuyển xuống dưới cùng" href="javascript:void(0);" class="ajMoveStartPoint" _city_id="{$listStartPoint[i].city_id}" {if $country_id}_country_id="{$country_id}"{/if} direct="movebottom"><i class="icon-circle-arrow-down"></i></a>
                        {/if}
                    </td>
                    <td style="vertical-align: middle;text-align:center">
                        {if !$smarty.section.i.first}
                        <a title="Di chuyển lên" href="javascript:void(0);" class="ajMoveStartPoint" _city_id="{$listStartPoint[i].city_id}" {if $country_id}_country_id="{$country_id}"{/if} direct="moveup"><i class="icon-arrow-up"></i></a>
                        {/if}
                    </td>
                    <td style="vertical-align: middle;text-align:center">
                        {if !$smarty.section.i.last}
                        <a title="Di chuyển xuống" href="javascript:void(0);" class="ajMoveStartPoint" _city_id="{$listStartPoint[i].city_id}" {if $country_id}_country_id="{$country_id}"{/if} direct="movedown"><i class="icon-arrow-down"></i></a>
                        {/if}
                    </td>
                    <td style="vertical-align: middle; width: 65px; text-align: right; white-space: nowrap;">
                        <a class="iso-cancel-action clkDeleteStartPoint" _city_id="{$listStartPoint[i].city_id}" title="Loại bỏ điểm xuất phát này" href="javascript:void();"><i class="icon-upload icon-white"></i> Loại bỏ</a>
                    </td>
                </tr>
                {/section}
                {else}
                <tr><td colspan="6" style="text-align:center">Hiện chưa có điểm khởi hành nào được nhập trong hệ thống!</td></tr>
                {/if}
            </table>
        </div>
    </tr>
</div>
{literal}<style>.disabled{-moz-opacity:.8;-webkit-opacity:.8;-o-opacity:.8;opacity:.8;filter:anpha(opacity=80)}</style>{/literal}
{literal}
<script type="text/javascript">
$().ready(function(){
	$(document).on('click', '.clkDeleteStartPoint', function(ev){
		if(confirm(confirm_delete)){
			var $_this = $(this);
			vietiso_loading(1);
			$.ajax({
				type: "POST",
				url:path_ajax_script+'/index.php?mod='+mod+'&act=ajDeleteStartPoint',
				data: {'city_id': $_this.attr('_city_id')},
				dataType: "html",
				success: function(html){
					window.location.reload(true);
				}
			});
		}
	});
	$(document).on('click', '.clkAddStartPoint', function(ev){
		var $_this = $(this);
		if($_this.hasClass('disabled')){
			alertify.error('Bạn không thể thêm điểm khởi hành mới !');
			return false;
		}
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url:path_ajax_script+'/index.php?mod='+mod+'&act=ajAddStartPoint',
			data: {'city_id': $_this.attr('_city_id'),'country_id': $_this.attr('_country_id')},
			dataType: "html",
			success: function(html){
				window.location.reload(true);
			}
		});
	});
	$(document).on('click', '.ajMoveStartPoint', function(ev){
		var $_this = $(this);
		var adata = {
			'city_id' : $_this.attr('_city_id'),
			'country_id' : $_this.attr('_country_id'),
			'direct' : $_this.attr('direct')
		};
		vietiso_loading(1);
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/?mod="+mod+"&act=ajMoveStartPoint",
			data: adata,
			dataType: "html",
			success: function(html){
				window.location.reload(true);
			}
		});
		return false;
	});
});
</script>
{/literal}