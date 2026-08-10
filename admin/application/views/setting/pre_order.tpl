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
        <h2><i class="fa fa-wrench"></i> {$core->get_Lang('Pre Order')}</h2>
		<p>{$core->get_Lang('systemmanagementsettings')}</p>
    </div>
    <div class="clearfix"></div>
    <form method="post" action="" enctype="multipart/form-data">
        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
            <tr>
                <td width="20%" class="fieldlabel">{$core->get_Lang('prefixfeedback')}</td>
                <td class="fieldarea">
                    <input style="width:40%; padding:5px" type="text" name="iso-SitePrefixFeedback" value="{$clsConfiguration->getValue('SitePrefixFeedback')}">
                </td>
            </tr>
            <tr>
                <td class="fieldlabel">{$core->get_Lang('prefixordertour')}</td>
                <td class="fieldarea">
                    <input style="width:40%; padding:5px" type="text" name="iso-SitePrefixOrderTour" value="{$clsConfiguration->getValue('SitePrefixOrderTour')}">
                </td>
            </tr>
            <tr>
                <td class="fieldlabel">{$core->get_Lang('prefixorderhotel')}</td>
                <td class="fieldarea">
                    <input style="width:40%; padding:5px" type="text" name="iso-SitePrefixOrderHotel" value="{$clsConfiguration->getValue('SitePrefixOrderHotel')}">
                </td>
            </tr>
            <tr>
                <td class="fieldlabel">{$core->get_Lang('prefixordertailor')}</td>
                <td class="fieldarea">
                    <input style="width:40%; padding:5px" type="text" name="iso-SitePrefixOrderTailor" value="{$clsConfiguration->getValue('SitePrefixOrderTailor')}">
                </td>
            </tr>
        </table>
        <fieldset class="submit-buttons">
            {$saveBtn}
            <input value="UpdateConfiguration" name="submit" type="hidden">
        </fieldset>
    </form>
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