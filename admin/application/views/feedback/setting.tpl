<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
	<a href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('feedback')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}&act={$act}" title="{$mod}">{$core->get_Lang('settingfeedback')}</a>
</div>
<div class="clearfix"></div>
<div class="container-fluid">
    <div class="page-title">
        <h2>{$core->get_Lang('settingfeedback')}</h2>
        <p>{$core->get_Lang('systemmanagementsettingfeedback')}</p>
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
                        	{assign var=site_feedback_link value=site_feedback_link_$_LANG_ID}
							<input class="text full required" name="iso-{$site_feedback_link}" value="{$clsConfiguration->getValue($site_feedback_link)}" maxlength="255" type="text" />
						</td>
					</tr>
                    {/if}
                     <fieldset>
                        <div class="row-field">
                            <div class="row-heading notToogle">{$core->get_Lang('Image')} (2000x460px)*:</div>
                            <div class="controls">
                                <div class="photobox span98" style="height:300px">
                                    <img src="{$clsConfiguration->getValue('SlidePageContactUs')}" alt="{$core->get_Lang('images')}"  id="isoman_show_image_Slide" class="span100" height="470px" style="width:100%; height:300px" />
                                    <input type="hidden" id="isoman_hidden_image_Slide" name="iso-SlidePageContactUs" value="{$clsConfiguration->getValue('SlidePageContactUs')}" />
                                    <a href="javascript:void()" class="photobox_edit ajOpenDialog" isoman_for_id="image_Slide" isoman_val="{$clsConfiguration->getValue('SlidePageContactUs')}" isoman_name="image" title="{$core->get_Lang('edit')}">
                                        <i class="iso-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </fieldset>
					<tr>
						<td class="fieldlabel">{$core->get_Lang('intropage')}</td>
						<td class="fieldarea">
                        	{assign var=site_feedback_intro value=site_feedback_intro_$_LANG_ID}
                            <textarea id="textarea_intro_editor{$now}" class="textarea_intro_editor" name="iso-{$site_feedback_intro}" style="width:100%">{$clsConfiguration->getValue($site_feedback_intro)}</textarea>
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