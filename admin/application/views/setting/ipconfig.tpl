{literal}
<style>
	#clienttabs > ul > li > a{ padding:0px 10px !important;}
</style>
{/literal}
<div class="breadcrumb">
    <strong>{$core->get_Lang('youarehere')} : </strong>
    <a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}" title="{$mod}">{$core->get_Lang('settings')}</a>
    <!-- Back-->
    <a href="javascript:window.history.back();" class="back fr">{$core->get_Lang('back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">
        <h2><i class="fa fa-wrench"></i> {$core->get_Lang('IP Config')} <a href="javascript:void();" class="color_r ajaxConfigIPMode underline">&raquo; Cài đặt IP Mode</a></h2>
		<p>{$core->get_Lang('systemmanagementsettings')}</p>
    </div>
    <div class="filterbox wrap">
        <div class="searchbox" style="float:left !important; width:100%">
            <input type="text" class="text fl mr5" name="keyword" id="keyword_ip" placeholder="Tìm kiếm..." />
            <a href="javascript:void();" style="padding:4px" class="btn btn-success btnCreateNewIP">
                <i class="icon-plus icon-white"></i> Thêm mới
            </a>
            <a href="javascript:void();" style="padding:4px" tp="{$clsISO->getRealIP()}" class="btn btn-warning btnCreateNewIP">
                <i class="icon-plus icon-white"></i> Thêm Your IP {$clsISO->getRealIP()}
            </a>
        </div>
    </div>
    <div class="hastable" style=" width:50%">
        <div class="infobox" style=" margin-left:0; margin-right:0;">
            <b>Ghi chú</b><br />
            Thêm địa chỉ IP vào đây để cho phép được truy cập.
        </div>
        <table cellpadding="0" class="tbl-grid">
            <thead>
                <tr>
                    <td class="gridheader" style="text-align:center; width:3%;">#</td>
                    <td class="gridheader bold" style="text-align:left">{$core->get_Lang('IP Address')}</th>
                    <td class="gridheader bold" style="width:35%; text-align:right">Cập nhật</td>
                    <td class="gridheader bold"  style="width:20%;text-align:center;">{$core->get_Lang('func')}</td>
                </tr>
            </thead>
            <tbody id="loadViewHolderBankAccount">
            </tbody>
        </table>
        <div class="clearfix" style="height:5px"></div>
        <div class="pagination_box">
            <div class="wrap holderEvent_tbl" id="dataTable_paginate">
                <!-- Ajax Loading pagination -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{$URL_THEMES}/setting/jquery.setting.js"></script>
{literal}
<script type="text/javascript">
	$(function(){
		$('select[name^=iso]').each(function(){
			var $_this = $(this);
			if($_this.val()==1){
				$_this.css({'border-color':'#0C0', 'background':'#e9ffd9'});
			}
		});
	});
</script>
{/literal}