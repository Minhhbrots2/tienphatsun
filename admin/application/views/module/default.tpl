<div class="breadcrumb">
	<strong>{$core->get_Lang('You are here')}:</strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('Home')}">{$core->get_Lang('Home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}" title="{$core->get_Lang('Module')}">{$core->get_Lang('Module')}</a>
    <a href="javascript:history.back();" class="back fr" style="width:50px;">{$core->get_Lang('Back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">
        <h2>{$core->get_Lang("Admin Modules")}</h2>
    </div>
    {if $message eq 'sysinvalidtoken'}
    <div class="errorbox"><strong><span class="title">{$core->get_Lang("Invalid Token")}</span></strong><br></div>
    {/if}
    {if $message eq 'sysactivesuccess'}
    <div class="successbox"><strong><span class="title">{$core->get_Lang("Module Activated")}</span></strong><br></div>
    {/if}
    {if $message eq 'sysdeactivatesuccess'}
    <div class="successbox"><strong><span class="title">{$core->get_Lang("Module Deactivated")}</span></strong><br>If successful, you can return a message to show the user here</div>
    {/if}
    <p>This is where you can activate and manage addon modules in your ISOCMS installation. Older legacy modules will still allow you to activate/deactivate and configure access rights, but will not be able to show any configuration options, version or author information.</p> 
    <div class="clearfix"><br /></div>
    <div class="hastable">
		<table width="100%" cellspacing="0" class="tbl-grid">
			<tr>
				<td class="gridheader">{$core->get_Lang("index")}</td>
				<td class="gridheader text-left">{$core->get_Lang("Module")}</td>
				<td width="100" class="gridheader">{$core->get_Lang("Version")}</td>
				<td width="100" class="gridheader">{$core->get_Lang("Author")}</td>
				<td class="gridheader" width="250">{$core->get_Lang("Action")}</td>
			</tr>
			
			{section name=i loop=$allItem}
			{assign var=active value=$core->checkActiveModule($allItem[i].name)}
			<tr class="{cycle values="row1,row2"}">
				<td class="index">{$smarty.section.i.index+1}</td>
				<td style="{if $active eq 1}background-color:#e9ffd9;{/if}text-align:left;">
					<a name="act{$allItem[i].name}"></a><a name="{$allItem[i].name}"></a><b>&nbsp;» {$allItem[i].Display_Name}</b>
					<br>
					{$allItem[i].intro|html_entity_decode}
				</td>
				<td style="{if $active eq 1}background-color:#e9ffd9;{/if}text-align:center;">{$allItem[i].version}</td>
				<td style="{if $active eq 1}background-color:#e9ffd9;{/if}text-align:center;">
					{if $allItem[i].author_link ne ''}
						<a href="{$allItem[i].author_link}" targer="_blank">{$allItem[i].author}</a>
					{else}
						{$allItem[i].author}
					{/if}
				</td>
				<td style="{if $active eq 1}background-color:#e9ffd9;{/if}text-align:center;">
					<input type="button" value="{$core->get_Lang('Activate')}" {if $active eq 1}disabled="disabled"{else}onclick="window.location='{$PCMS_URL}/index.php?act=sysactivate&module={$allItem[i].name}&token={$core->gentoken()}'"{/if} class="btn {if $active eq 1}btn-default disabled{else}btn-success{/if}"> 
					<input type="button" value="{$core->get_Lang('Deactivate')}" class="btn {if $active eq 0}disabled{else}btn-danger{/if}" {if $active eq 0}disabled="disabled"{else}onclick="deactivateMod('{$allItem[i].name}');return false"{/if}> 
					<input type="button" value="{$core->get_Lang('Configure')}" class="btn {if $active eq 0}disabled{else}btn-warning{/if}" {if $active eq 0}disabled="disabled"{else}onclick="showConfig('{$allItem[i].name}')"{/if}>
				</td>
			</tr>
			{/section}
		</table>
	</div>
    <script type="text/javascript">
		var token = '{$core->gentoken()}';
	</script>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		$('.mceSimple').each(function(){
			var $editorID = $(this).attr('id');
			$('#'+$editorID).isoTextArea();
			setInterval(function(){
				$('#'+$editorID+'_ifr').height(100);
			},100);
		});
	});
	function showConfig(module) {
		$("#"+module+"config").fadeToggle();
	}
	function deactivateMod(id) {
		if (confirm("Are you sure you want to deactivate this module?")) {
			window.location=path_ajax_script+'/index.php?act=sysdeactivate&module='+id+'&token='+token;
		}
	}
</script>
{/literal}