<header class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a class="btn btn-default ui-breadcrumb" href="{$PCMS_URL}/index.php?mod=home" title="{$core->get_Lang('Setting')}">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Home')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Nhân viên</h1>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="{$PCMS_URL}/?mod={$mod}&act=edit" class="btn btn-success ui-title-bar__action" title="{$core->get_Lang('Addnew')}">{$core->makeIcon('plus', $core->get_Lang('Addnew'))}</a>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="clearfix"></div>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections"><div class="ui-layout__section">
		<div class="ui-layout__item"><div class="ui-card">
			<div class="next-tab__container">
				<ul class="next-tab__list filter-tab-list">
					<li class="filter-tab-item" data-tab-index="1">
						<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">Danh sách nhân viên</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<form method="post">
					<div class="form-search form-inline">
						<div class="form-group">
							<div class="input-group w-200px">
								<select class="iso-selectize" data-width="100%" name="department_id">
									{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$department_id,'Phòng ban')}
								</select>
							</div>
							<div class="input-group column-count-1 w-200px">
								<select class="iso-selectize" data-width="100%" name="exist_phone">
									<option value="">Có/Không có số điện thoại</option>
									<option value="1" {if $exist_phone eq '1'}selected{/if}>Có số điện thoại</option>
									<option value="0" {if $exist_phone eq '0'}selected{/if}>Không có số điện thoại</option>
								</select>
							</div>
							<div class="input-group w-200px">
								<select class="iso-selectize" name="status_id">
									{$clsISO->getSelectByPropertyTypeTitle('_STATUS_STAFF',$status_id,'Tình trạng')}
								</select>
							</div>
							<div class="input-group w-px-100">
								<input type="text" class="form-control" name="birthday" value="{$birthday}" placeholder="Sinh nhật" onfocus="(this.type='month')" onblur="(this.type='text')" />
							</div>
							<div class="input-group">
								<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
							</div>
						</div>
						<input type="hidden" name="filter" value="filter" />
						<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
						<div class="pull-right">
							<div class="fr d-flex group_buttons">
								<a href="{$PCMS_URL}/?mod={$mod}" class="btn mr-2 btn-warning">{$core->makeIcon('folder-o')} {$core->get_Lang('all')} ({$totalRecord})</a>
								<div class="dropdown">
									<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"> {$core->get_Lang('Exportto')}
									<span class="caret"></span></button>
									<ul class="dropdown-menu w-100">
										<li><a href="{$PCMS_URL}/index.php?mod={$mod}&act=export{$pUrl}">{$core->makeIcon('file-excel-o', $core->get_Lang('Excel'))}</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="hastable">
						<div class="dragscroll">
							<table id="tableCall" cellspacing="0" class="table table-vertical table-striped" cellpadding="0" width="100%">
								<thead><tr>
									<th class="text-center" width="5%">No.</th>
									<th class="text-left">{$core->get_Lang('Code')}</th>
									<th class="text-center" width="5%">{$core->get_Lang('Avatar')}</th>
									<th class="text-left" width="20%">{$core->get_Lang('FullName')}</th>
									<th class="text-left">Phòng ban</th>
									<th class="text-left">Ngày sinh</th>
									<th class="text-left">Giới tính</th>
									<th class="text-left">Email</th>
									<th class="text-left">{$core->get_Lang('Phone')}</th>
									<th class="text-center">{$core->get_Lang('Status')}</th>
									<th class="text-right">{$core->get_Lang('Register Date')}</th>
									<th class="text-right">Ngày kết thúc</th>
									<th class="text-right">Ngày cập nhật</th>
									<th class="text-center">Tình trạng</th>
								</tr></thead>
								{section name=i loop=$allItem}
								<tr{if $allItem[i].status_id eq $smarty.const._STATUS_STAFF_OFF_ID} class="tr_Off"{/if}>
									<td class="text-center" style="white-space: nowrap;">
										<div class="btn-group{if $smarty.section.i.last} dropup{/if}">
											<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown">
												{$core->makeIcon('cog')} <span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li style="display: none"><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=view&profile_id={$allItem[i].profile_id}">
													{$core->makeIcon('eye', $core->get_Lang('View'))}</a>
												</li>
												<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&profile_id={$allItem[i].profile_id}">
													{$core->makeIcon('pencil', $core->get_Lang('edit'))}</a> 
												</li>
												<li><a href="{$PCMS_URL}/?mod={$mod}&act=delete&profile_id={$allItem[i].profile_id}{$pUrl}" title="{$core->get_Lang('delete')}" class="confirm_delete">{$core->makeIcon('trash',$core->get_Lang('delete'))}</a></li>
												<li class="divider"></li>
												<li><a href="javascript:void(0);" profile_id="{$allItem[i].profile_id}" onClick="$Core.member.send_email(this, event);" title="Gửi email"><i class="fa fa-envelope-o mr-2" aria-hidden="true"></i>Gửi email chào mừng</a></li>
												<li class="divider"></li>
												<li class="d-none"><a title="{$core->get_Lang('Thay mật khẩu')}" profile_id="{$allItem[i].profile_id}" href="javascript:void(0);" onClick="open_password(this, event);">{$core->makeIcon('lock', $core->get_Lang('Thay mật khẩu'))}</a></li>
												<li><a title="{$core->get_Lang('Permission')}" profile_id="{$allItem[i].profile_id}" href="javascript:void(0);" onClick="$Core.member.open_permiss(this, event);">
													{$core->makeIcon('lock', $core->get_Lang('Permission'))}</a>
												</li>
											</ul>
										</div>
									</td>
									<td>{$allItem[i].code}</td>
									<td class="text-center">
										<img class="avatar m-0 small" src="{$clsClassTable->getAvatar($allItem[i].profile_id,$allItem[i],30,30)}" onerror="this.src='{$URL_IMAGES}/no-avatar.svg'" >
									</td>
									<td class="text-nowrap"><a href="{$PCMS_URL}/?mod={$mod}&act=view&profile_id={$allItem[i].profile_id}">
										<strong>{$allItem[i].profile_id} - {$clsClassTable->getFullName($allItem[i].profile_id)}</strong><br />
										<span class="text-muted font11">{$clsProperty->getTitle($allItem[i].role_id)}</span>
									</a></td>
									<td>
										<select class="form-control input-sm" onChange="$Core.member.update_field(this,event)" 
											p_field="department_id" p_id="{$allItem[i].profile_id}" >
											<option value="0">-- Chọn --</option>
											{foreach from=$arr_departments key=_oKey item = _oDep}
											<option value="{$_oKey}"{if $_oKey eq $allItem[i].department_id} selected{/if}>{$_oDep}</option>
											{/foreach}
										</select>
									</td>
									<td>{$allItem[i].birthday}</td>
									<td>{if $allItem[i].gender_id eq '1'}Nam{elseif $allItem[i].gender_id eq '2'}Nữ{else}--{/if}</td>
									<td>{$allItem[i].email}</td>
									<td>{$allItem[i].phone}</td>
									<td bgcolor="#F5F5F5" class="text-center">
										<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Profile" toField="is_active" pkey="is_active" 
										sourse_id="{$allItem[i].profile_id}" rel="{$allItem[i].is_active}" title="{$core->get_Lang('Click to change status')}">
											{if $allItem[i].is_active eq '1'}
											<i class="fa fa-check-circle green"></i>{else}
											<i class="fa fa-minus-circle red"></i>{/if}
										</a>
									</td>
									<td class="text-right">{$clsISO->convertTimeToText($allItem[i].reg_date, true)}</td>
									<td class="text-right">{if !empty($allItem[i].end_date)}{$clsISO->convertTimeToText($allItem[i].end_date, true)}{else}--{/if}</td>
									<td class="text-right">{$clsISO->convertTimeToText($allItem[i].upd_date, true)}</td>
									<td class="text-center">{$allItem[i].status_name}</td>
								</tr>
								{/section}
							</table>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="d-flex justify-content-center">
						<ul class="pagination">
							{$html_pager}
						</ul>
					</div>	
				</form>
			</div></div>
		</div></div>
	</div>
</div>
{literal}
<style>
	.table{
		margin-bottom:0;
		max-width:10000px;
	}
	.freeze-table {
        user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
		padding-bottom:30px;
		cursor:move;
	}
	.tr_Off td{
		background:#ffcccc;
	}
</style>
<script type="text/javascript">
$(function(){
	setTimeout(() => {
		if($('#'+'tableCall').length){
			var __www = $('#'+'tableCall').outerWidth(false);
			$('#'+'tableCall').width(__www+200);
			$('.freeze-table').freezeTable({
				'columnNum': 4,
				'scrollable': true,
				'columnKeep': false,
			});
		}
	},1000);
});
</script>
{/literal}