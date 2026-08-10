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
        <h2><i class="fa fa-wrench"></i> {$core->get_Lang('Live Chat')}</h2>
		<p>{$core->get_Lang('systemmanagementsettings')}</p>
    </div>
    <div class="clearfix"></div>
    <form method="post" action="" enctype="multipart/form-data">
        <table class="form" width="100%" border="0" cellspacing="2" cellpadding="3">
            <tr>
                <td class="fieldlabel span25">{$core->get_Lang('Live Chat')}</td>
                <td class="fieldarea">
                    <select class="slb span20" multiple="multiple" size="5" style="height:50px" name="iso-SiteLiveChat">
                        <option {if $clsConfiguration->getValue('SiteLiveChat') eq 'Zopim'}selected="selected"{/if} value="Zopim"> -- Zopim -- </option>
                        <option {if $clsConfiguration->getValue('SiteLiveChat') eq 'Subiz'}selected="selected"{/if} value="Subiz"> -- Subiz -- </option>
                        <option {if $clsConfiguration->getValue('SiteLiveChat') eq 'VChat'}selected="selected"{/if} value="VChat"> -- VChat -- </option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="fieldlabel span25">{$core->get_Lang('status')}</td>
                <td class="fieldarea">
                    <label><input type="checkbox" name="SiteLiveChatStatus" value="1" {if $clsConfiguration->getValue('SiteLiveChatStatus') eq '1'}checked="checked"{/if} /></label>
                    <span>{$core->get_Lang('Tick here enable live chat')}</span>
                </td>
            </tr>
            <tr>
                <td class="fieldlabel span25">{$core->get_Lang('code')} {$core->get_Lang('Live Chat')}</td>
                <td class="fieldarea">
                    <textarea class="textarea full span55" rows="8" cols="255" name="iso-SiteLiveChatScript">{$clsConfiguration->getValue('SiteLiveChatScript')}</textarea>
                    <div class="highlightbox mt5">
                        Zopim là công cụ livechat giúp bạn tương tác trực tiếp với khách hàng nhanh chóng, dễ dàng. <br />
                        Bước 1: Đăng ký 1 tài khoản tại Zopim.com. <a href="https://account.zopim.com/signup" class="underline" target="_blank">Đăng ký tại đây</a>. <br />
                        Bước 2: Copy đoạn mã script từ tài khoản Zopim vào ô Mã widget livechat để tích hợp công cụ livechat và website của bạn.
                    </div>
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