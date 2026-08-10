<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}" title="{$core->get_Lang('Page')}">{$core->get_Lang('contactdepartment')}</a>
    <!-- // -->
    <a href="javascript:window.history.back();" class="back fr">{$core->get_Lang('back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">
        <h2>{$core->get_Lang('contactdepartment')} <a class="btn btn-success" href="{$PCMS_URL}/?mod={$mod}&act=edit" title="{$core->get_Lang('add')}"> <i class="icon-plus icon-white"></i></a></h2>
		<p>{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
    </div>
	<div class="clearfix"><br /></div>
	<div class="statistical mb5">
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
	<div class="hastable">
		<table width="100%" cellspacing="0" class="tbl-grid">
			<tr>
				<td class="gridheader"><strong>{$core->get_Lang('index')}</strong></td>
				<td class="gridheader" style="text-align:left"><strong>{$core->get_Lang('title')}</strong></td>
                <td class="gridheader" style="width:10%"><strong>{$core->get_Lang('status')}</strong></td>
				<td class="gridheader" colspan="4" style="width:4%"><strong>{$core->get_Lang('move')}</strong></td>
				<td class="gridheader"><strong>{$core->get_Lang('Action')}</strong></td>
			</tr>
			{if $allItem[0].contact_id ne ''}
			{section name=i loop=$allItem}
			<tr class="{cycle values="row1,row2"}">
				<td class="index">{$smarty.section.i.iteration}</td>
                <td>
                	<span style="display:inline-block; width:12px; height:12px; background:{$clsClassTable->getOneField('color',$allItem[i].contact_id)}"></span>
                	<strong class="title">{$clsClassTable->getTitle($allItem[i].contact_id)}</strong>
                	{if $allItem[i].is_trash eq '1'}<span class="fr" style="color:#CCC">{$core->get_Lang('intrash')}</span>{/if}
                </td>
                <td style="text-align:center">
                    <a href="javascript:void(0);" class="SiteClickPublic" clsTable="Page" pkey="contact_id" sourse_id="{$allItem[i].contact_id}" rel="{$clsClassTable->getOneField('is_online',$allItem[i].contact_id)}" title="{$core->get_Lang('Click to change status')}">
                        {if $clsClassTable->getOneField('is_online',$allItem[i].contact_id) eq '1'}
                        <i class="fa fa-check-circle green"></i>
                        {else}
                        <i class="fa fa-minus-circle red"></i>
                        {/if}
                    </a>
                </td>
				<td style="vertical-align: middle;text-align:center">
					{if !$smarty.section.i.first}
					<a title="{$core->get_Lang('movetop')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movetop&contact_id={$allItem[i].contact_id}">
						<i class="icon-circle-arrow-up"></i>
					</a>
					{/if}
				</td>
				<td style="vertical-align: middle;text-align:center">
					{if !$smarty.section.i.last}
					<a title="{$core->get_Lang('movebottom')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movebottom&contact_id={$allItem[i].contact_id}"><i class="icon-circle-arrow-down"></i></a>
					{/if}
				</td>
				<td style="vertical-align: middle;text-align:center">
					{if !$smarty.section.i.first}
					<a title="{$core->get_Lang('moveup')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=moveup&contact_id={$allItem[i].contact_id}">
						<i class="icon-arrow-up"></i>
					</a>
					{/if}
				</td>
				<td style="vertical-align: middle;text-align:center">
					{if !$smarty.section.i.last}
					<a title="{$core->get_Lang('movedown')}" href="{$PCMS_URL}/index.php?mod={$mod}&act=move&direct=movedown&contact_id={$allItem[i].contact_id}">
						<i class="icon-arrow-down"></i>
					</a>
					{/if}
				</td>
                <td style="vertical-align: middle; width: 40px; text-align: center; white-space: nowrap;">
					<div class="btn-group">
						<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
						<ul class="dropdown-menu" style="right:0px !important">
                        	{if $allItem[i].is_trash eq '0'}
							<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&contact_id={$core->encryptID($allItem[i].contact_id)}"><i class="icon-edit"></i> {$core->get_Lang('edit')}</a></li>
                            <li><a title="{$core->get_Lang('trash')}" href="{$PCMS_URL}/?mod={$mod}&act=trash&contact_id={$core->encryptID($allItem[i].contact_id)}{$pUrl}"><i class="icon-trash"></i> <span>{$core->get_Lang('trash')}</span></a></li>
                            {else}
                            <li><a title="{$core->get_Lang('restore')}" href="{$PCMS_URL}/?mod={$mod}&act=restore&contact_id={$core->encryptID($allItem[i].contact_id)}{$pUrl}"><i class="icon-refresh"></i> <span>{$core->get_Lang('restore')}</span></a></li>
                            <li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=delete&contact_id={$core->encryptID($allItem[i].contact_id)}{$pUrl}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
                            {/if}
						</ul>
					</div>
                </td>
			</tr>	
			{/section}
			{else}<tr><td colspan="10" style="text-align:center">{$core->get_Lang('nodata')}</td></tr>{/if}
		</table>
	</div>
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