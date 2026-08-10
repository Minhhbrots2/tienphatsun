<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')}:</strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('Home')}">{$core->get_Lang('Home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}" title="{$core->get_Lang('Administrators Role')}">{$core->get_Lang('Administrators Role')}</a>
	<!-- Back -->
    <a href="javascript:history.back();" class="back fr" style="width:50px;">{$core->get_Lang('Back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">
        <h2>{$core->get_Lang("Administrator Role")}</h2>
    </div>
    <p>{$core->get_Lang("The administrator roles allow you to fine tune exactly what each of your admin users can do within the ISOCMS admin area.")}</p>
    <p><b>{$core->get_Lang("Options")}:</b> <a href="{$PCMS_URL}/?mod={$mod}&act=edit">{$core->get_Lang("Add New Administrator Role")}</a></p>
    <div class="clearfix"><br /></div>
    <table cellspacing="0" cellpadding="0" width="100%" class="tbl-grid">
    	<thead>
            <tr>
                <td class="gridheader"><strong>STT</strong></td>
                <td class="gridheader" style="text-align:left"><strong>{$core->get_Lang("Group Name")}</strong></td>
                <td class="gridheader" style="text-align:left"><strong>{$core->get_Lang("Assigned Admin Users")}</strong></td>
                <td class="gridheader" style="width:6%">{$core->get_Lang("action")}</td>
            </tr>
        </thead> 
        {section name=i loop=$allItem}
        <tr class="{if $smarty.section.i.index%2 eq 0}row1{else}row2{/if}">
            <td class="index">
                {$smarty.section.i.index+1}
            </td>
            <td><strong><a href="{$PCMS_URL}/?admin&mod={$mod}&act=edit&user_group_id={$core->encryptID($allItem[i].user_group_id)}" title="Sửa {$allItem[i].name}"> {$allItem[i].name}</a></strong></td>
            <td>{$clsISO->getAssignedAdminUsers($allItem[i].user_group_id)}</td>
            <td style="vertical-align:top; text-align:right; white-space: nowrap;"> 
				<div class="btn-group">
					<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
						<i class="icon-cog"></i> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" style="right:0px !important">
						<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?admin&mod={$mod}&act=edit&user_group_id={$core->encryptID($allItem[i].user_group_id)}"><i class="icon-edit"></i> <span>{$core->get_Lang('edit')}</span></a></li>
						{if $allItem[i].user_group_id ne 1}
						<li><a title="{$core->get_Lang('delete')}" class="confirm_delete" href="{$PCMS_URL}/?admin&mod={$mod}&act=delete&user_group_id={$core->encryptID($allItem[i].user_group_id)}"><i class="icon-remove"></i> <span>{$core->get_Lang('delete')}</span></a></li>
						{/if}
					</ul>
				</div>
            </td>
        </tr>
        {/section}
    </table>
	<div class="clearfix" style="height:5px"></div>
		<div class="pagination_box">
		<div class="wrap holderEvent_tbl" id="dataTable_paginate">
		<!-- Ajax Loading pagination -->
		</div>
	</div>
</div>