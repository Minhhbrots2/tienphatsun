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
				<h1 class="ui-title-bar__title">Thành viên</h1>
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
						<a href="{$PCMS_URL}/index.php?mod={$mod}" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">Danh sách thành viên</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<form method="post">
					<div class="form-search  radius-4 form-inline">
						<div class="form-group">
							<div class="input-group column-count-1 w-150px">
								<select class="iso-selectize" data-width="100%" name="role_id">
									{$clsProperty->getSelectByProperty('_PACKAGE',$role_id,'Vai trò')}
								</select>
							</div>
							<div class="input-group column-count-1 w-150px">
								<select class="iso-selectize" data-width="100%" name="exist_phone">
									<option value="">Có/Không có số điện thoại</option>
									<option value="1" {if $exist_phone eq '1'}selected{/if}>Có số điện thoại</option>
									<option value="0" {if $exist_phone eq '0'}selected{/if}>Không có số điện thoại</option>
								</select>
							</div>
							<div class="input-group column-count-1 w-200px">
								<select class="iso-selectize" data-width="100%" name="is_FH">
									<option value="">Thành viên FH</option>
									<option value="1" {if $is_FH eq '1'}selected{/if}>Là thành viên FH</option>
									<option value="0" {if $is_FH eq '0'}selected{/if}>Không là thành viên FH</option>
								</select>
							</div>
							<div class="input-group w-150px">
								<select class="iso-selectize" name="status_id">{$clsISO->getSelectByPropertyTypeTitle('_STATUS_STAFF',$status_id,'Tình trạng')}
								</select>
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
					<div class="hastable w-100">
						<div class="freeze-table dragscroll text-nowrap w-100">
							<table id="tableCall" cellspacing="0" class="table table-vertical mb-0 table-striped" 
								cellpadding="0" width="100%">
								<thead><tr>
									<th class="text-left" width="3%">No.</th>
									<th class="text-left" width="6%">{$core->get_Lang('Code')}</th>
									<th class="text-center" width="6%">{$core->get_Lang('Avatar')}</th>
									<th class="text-left" width="20%">{$core->get_Lang('FullName')}</th>
									<th class="text-left">Email</th>
									<th class="text-left">{$core->get_Lang('Phone')}</th>
									<th class="text-right">Điểm</th>
									<th class="text-center">{$core->get_Lang('Status')}</th>
									<th class="text-center">Xác thực</th>
									<th class="text-center">Nâng cấp</th>
									<th class="text-right">Ngày tạo</th>
									<th class="text-right">Ngày cập nhật</th>
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
													{$core->makeIcon('eye', $core->get_Lang('View'))}</a></li>
												<li><a title="{$core->get_Lang('edit')}" href="{$PCMS_URL}/?mod={$mod}&act=edit&profile_id={$allItem[i].profile_id}">
													{$core->makeIcon('pencil', $core->get_Lang('edit'))}</a> </li>
												<li><a href="{$PCMS_URL}/?mod={$mod}&act=delete&profile_id={$allItem[i].profile_id}{$pUrl}" title="{$core->get_Lang('delete')}" class="confirm_delete">{$core->makeIcon('trash',$core->get_Lang('delete'))}</a></li>
												<li class="divider"></li>
												<li><a href="javascript:void(0);" profile_id="{$allItem[i].profile_id}" email_type="wellcome" 
													onClick="$Core.member.send_email(this, event);" title="Gửi email"><i class="fa fa-envelope-o mr-2" aria-hidden="true"></i>Gửi email chúc mừng</a></li>
												<li><a href="javascript:void(0);" profile_id="{$allItem[i].profile_id}" email_type="upgrade"
													onClick="$Core.member.send_email(this, event);" title="Gửi email" data-source="MF"><i class="fa fa-envelope-o mr-2" aria-hidden="true"></i>Gửi email VIP MF</a></li>
												<li class="divider"></li>
												<li class="d-none"><a title="{$core->get_Lang('Thay mật khẩu')}" profile_id="{$allItem[i].profile_id}" href="javascript:void(0);" onClick="open_password(this, event);">
													{$core->makeIcon('lock', $core->get_Lang('Thay mật khẩu'))}</a></li>
												<li class="divider"></li>
												<li><a title="{$core->get_Lang('Permission')}" profile_id="{$allItem[i].profile_id}" data-source="MOC" href="javascript:void(0);"
													onClick="$Core.member.open_permiss(this, event);">{$core->makeIcon('lock', 'Phân quyền MOC')}</a></li>
												<li><a title="{$core->get_Lang('Permission')}" profile_id="{$allItem[i].profile_id}" data-source="MF" href="javascript:void(0);"
													onClick="$Core.member.open_permiss(this, event);">{$core->makeIcon('lock', 'Phân quyền MF')}</a></li>
											</ul>
											</ul>
										</div>
									</td>
									<td>{$allItem[i].code}</td>
									<td class="text-center">
										<img class="avatar m-0 small" src="{$clsClassTable->getAvatar($allItem[i].profile_id,$allItem[i],30,30)}" onerror="this.src='{$URL_IMAGES}/no-avatar.svg'" >
									</td>
									<td><a href="{$PCMS_URL}/?mod={$mod}&act=view&profile_id={$allItem[i].profile_id}">
										<strong>{$allItem[i].profile_id} - {$clsClassTable->getFullName($allItem[i].profile_id)}{if $allItem[i].is_trial eq 1}<sup class="text-muted fs-tiny">(Dùng thử)</sup>{/if}</strong></a><br />
										<span class="text-muted font11">MOC: {if $allItem[i].role_id gt 0}{$clsProperty->getTitle($allItem[i].role_id)}{else}—{/if}</span><br />
										<span class="text-muted font11">MF: {if $allItem[i].package_id gt 0}{$clsProperty->getTitle($allItem[i].package_id)}{else}—{/if} · </span><a href="javascript:void(0);" class="font11" onClick="$Core.member.open_assign_package(this)" data-profile_id="{$allItem[i].profile_id}">Nâng cấp gói</a>
									</td>
									<td>{$allItem[i].email}</td>
									<td>{$allItem[i].phone}</td>
									<td class="text-center" id="total_point_{$allItem[i].profile_id}">{$allItem[i].total_point}</td>
									<td bgcolor="#F5F5F5" class="text-center">
										<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Member" toField="is_active" pkey="is_active" sourse_id="{$allItem[i].profile_id}" rel="{$allItem[i].is_active}" title="{$core->get_Lang('Click to change status')}">
											{if $allItem[i].is_active eq '1'}
											<i class="fa fa-check-circle green"></i>{else}
											<i class="fa fa-minus-circle red"></i>{/if}
										</a>
									</td>
									<td bgcolor="#F5F5F5" class="text-center">
										<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Member" toField="is_verified" pkey="is_verified" sourse_id="{$allItem[i].profile_id}" rel="{$allItem[i].is_verified}" title="{$core->get_Lang('Click to change status')}">
											{if $allItem[i].is_verified eq '1'}
											<i class="fa fa-check-circle green"></i>{else}
											<i class="fa fa-minus-circle red"></i>{/if}
										</a>
									</td>
									<td bgcolor="#F5F5F5" class="text-center">
										<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Member" toField="is_upgrade" pkey="is_upgrade" sourse_id="{$allItem[i].profile_id}" rel="{$allItem[i].is_upgrade}" title="{$core->get_Lang('Click to change status')}">
											{if $allItem[i].is_upgrade eq '1'}
											<i class="fa fa-check-circle green"></i>{else}
											<i class="fa fa-minus-circle red"></i>{/if}
										</a>
									</td>
									<td class="text-right">{$clsISO->convertTimeToText($allItem[i].reg_date, true)}</td>
									<td class="text-right">
										{if !empty($allItem[i].upd_date)}
											{$clsISO->convertTimeToText($allItem[i].upd_date, true)}
										{else}
											<span class="text-muted">Chưa có</span>
										{/if}
									</td>
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