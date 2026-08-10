<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')}:</strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}" title="{$core->get_Lang('adminbuttons')}">{$core->get_Lang('adminbuttons')}</a>
	<!-- Back -->
    <a href="javascript:history.back();" class="back fr">{$core->get_Lang('Back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">
        <h2>{$core->get_Lang('Admin Buttons')}</h2>
		{assign var = setting value = 'SiteIntroModule_'|cat:$mod|cat:'_'|cat:$_LANG_ID}
		{if $clsConfiguration->getValue($setting) ne ''}
        <p>{$clsConfiguration->getValue($setting)|html_entity_decode}</p>
		{/if}
    </div>
	<div class="infobox">
		<b>Ghi chú</b><br />
		<p>Phân hệ này chỉ ch phép nhà phát triển được phép truy cập</p>
	</div>
    <form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form" style="">
		<table class="form" cellspacing="2" cellpadding="2">
		</table>
    	<fieldset>
            <dl {if $pvalTable gt 0}style="display:none"{/if}>
                <dt><label for="_type">{$core->get_Lang('Admin Buttons Type')}</label></dt>
                <dd>
                    <select name="_type" class="span20">
                        <option value="_HOME" {if $oneItem._type eq '_HOME' or $_type eq '_HOME'}selected="selected"{/if}>_HOME</option>
                        <option value="_TOP" {if $oneItem._type eq '_TOP' or $_type eq '_TOP'}selected="selected"{/if}>_TOP</option>
                        <option value="_LEFT" {if $oneItem._type eq '_LEFT' or $_type eq '_LEFT'}selected="selected"{/if}>_LEFT</option>
                        <option value="_SETTING" {if $oneItem._type eq '_SETTING' or $_type eq '_SETTING'}selected="selected"{/if}>_SETTING</option>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt><label for="is_group">{$core->get_Lang('Is Group')}</label></dt>
                <dd>
                    <select name="is_group" id="is_group" class="span10">
                        <option value="0" {if 0 eq $oneItem.is_group}selected="selected"{/if}> NO</option>
                        <option value="1" {if 1 eq $oneItem.is_group}selected="selected"{/if}> YES</option>
                    </select>
                    <script type="text/javascript">
						var $is_group = '{$oneItem.is_group}'; 
						var $pvalTable = '{$pvalTable}'; 
					</script>
                    {literal}
                    <script type="text/javascript">
						$(document).ready(function(){
							if($pvalTable>0){
								$(".is_group").hide();
								$(".is_group_"+$is_group).show();
							}
							$("#is_group").change(function(){
								$(".is_group").hide();
								$(".is_group_"+$(this).val()).show();
							});
						});
					</script>
                    {/literal}
                </dd>
            </dl>
            <dl class="is_group is_group_0">
                <dt><label for="parent_id">{$core->get_Lang('Parent Group')}</label></dt>
                <dd>
                    <select name="parent_id" id="parent_id" class="span20">
						<option value="0"> -- </option>
                    	{section name=i loop=$listParent}
                        <option value="{$listParent[i].adminbutton_id}" {if $listParent[i].adminbutton_id eq $oneItem.parent_id}selected="selected"{/if}>
                            {$listParent[i].title_page}
                        </option>
                        {/section}
                    </select>
                </dd>
            </dl>
            <dl>
                <dt><label for="title">{$core->get_Lang('Title')}</label></dt>
                <dd>
                    <input type="text" value="{$oneItem.title_page}" name="title" class="medium text">
                </dd>
            </dl>
            <dl>
                <dt><label for="title">{$core->get_Lang('Title')}</label></dt>
                <dd>
                    <textarea name="desc" class="form-control">{$oneItem.desc_page}</textarea>
                </dd>
            </dl>
			<dl>
                <dt><label for="title">{$core->get_Lang('Configuration keyword')}</label></dt>
                <dd>
                    <input type="text" value="{$oneItem.CONFIGURATION_KEY}" name="configuration_key" class="medium text">
                </dd>
            </dl>
            <dl>
                <dt><label for="mod_page">{$core->get_Lang('Mod')}</label></dt>
                <dd>
                	{assign var=lstModule value=$core->getListAdminModule()}
                    <select class="medium" id="mod_page" name="mod_page">
                    	<option value="">-</option>
                        {section name=i loop=$lstModule}
                        <option {if $oneItem.mod_page eq $lstModule[i].name}selected="selected"{/if} value="{$lstModule[i].name}">[{$lstModule[i].name}] {$lstModule[i].intro}</option>
                        {/section}
                    </select>
                    <input type="text" value="{$oneItem.act_page}" name="act_page"  class="text" style="width:80px;">
                </dd>
            </dl>
            <dl class="is_group is_group_0">
                <dt><label for="url_page">{$core->get_Lang('or')} {$core->get_Lang('URL')}</label></dt>
                <dd>
					<input style="width:40%" type="text" value="{$oneItem.url_page}" name="url_page" class="full text">
                    <span class="notice-short">({$core->get_Lang('Leave Blank to get URL auto from Mod')})</span>
                </dd>
            </dl>
			<dl>
                <dt><label for="order_no">{$core->get_Lang('Position')}</label></dt>
                <dd>
                    <input type="number" value="{if $pvalTable gt '0'}{$oneItem.order_no}{else}{$clsClassTable->getMaxOrderNo()}{/if}" name="order_no" class="medium text" style="width:60px;">
                </dd>
            </dl>
			<dl>
                <dt><label for="order_no">{$core->get_Lang('Class')}</label></dt>
                <dd>
					<input type="text" value="{$oneItem.class_page}" name="class_page" class="full text" style="width:160px;">
					<span class="notice-short">({$core->get_Lang('Leave Blank if not exist')})</span>
				</dd>
            </dl>
			<dl>
                <dt><label for="order_no">{$core->get_Lang('Class Icon')}</label></dt>
                <dd>
					<input type="text" value="{$oneItem.class_iconpage}" name="class_iconpage" class="full text" style="width:160px;">
					<span class="notice-short">({$core->get_Lang('Leave Blank if not exist')})</span>
				</dd>
            </dl>
        </fieldset>
		<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3"><tbody>
			<tr>
				<td width="20%" class="fieldlabel">Permiss Access</td>
				<td class="fieldarea">
					Choose the admin role groups to permit access to this module:
					<div class="clearfix mt5"></div>
					{section name=i loop=$listUserGroup}
					<label><input {if $clsISO->checkContainer($permiss_access,$listUserGroup[i].user_group_id)}checked="checked"{/if} type="checkbox" name="permiss[]" value="{$listUserGroup[i].user_group_id}"> {$listUserGroup[i].name}</label> 
					{/section}
				</td>
			</tr>
			<tr>
				<td width="20%" class="fieldlabel">DEV Access</td>
				<td class="fieldarea">
					<label><input type="checkbox" name="dev_access" value="1" {if $oneItem.dev_access eq '1'}checked="checked"{/if} />
					<span class="notice-short">({$core->get_Lang('Tick here if you want only developer see it')})</span></label>
				</td>
			</tr>
		</table>	
        <br class="clearfix" />
        <fieldset class="submit-buttons">
            {$saveBtn}
            <input value="Update" name="submit" type="hidden">
        </fieldset>
    </form>
</div>
{literal}
<style>
.searchmap{ background:#E9EFF3; padding:10px;}
.errorTxt{color:#c00000; display:block;margin:5px 0 0}
</style>
<script type="text/javascript">
	$().ready(function(){
		$('.check_all').change(function(){
			var _this = $(this);
			if(_this.attr('checked') || _this.attr('checked')=='checked'){
				_this.closest('tr').find('input[type="checkbox"]')
								   .attr('checked',true)
								   .parent('label')
								   .addClass('lblchecked');
				_this.addClass('lblchecked');
			}else{
				_this.closest('tr').find('input[type="checkbox"]')
								   .removeAttr('checked')
								   .parent('label')
								   .removeClass('lblchecked');
				_this.removeClass('lblchecked');
			}	
		});
		$('#search_key').bind('keyup keydown change',function(){
			var _this=$(this);
			var rows = $('#tblHolderPermission tr').size();
			if(rows > 1 && _this.val() != ''){
				s=_this.val();
				$("#tblHolderPermission tr").each(function(){
					$(this).find('td:eq(1)').text().search(new RegExp(s,"i"))<0? $(this).hide():$(this).show();
				});
			}else{
				$('#tblHolderPermission tr').each(function(){
					$(this).show();
				});
			}
		});
		$('input[type="checkbox"]').each(function(){
			var _this = $(this);
			if(_this.attr('checked') || _this.attr('checked')=='checked'){
				_this.parent('label').addClass('lblchecked');			   
			}
		});
	});
</script>
{/literal}