<?php
/* Smarty version 3.1.33, created on 2026-07-30 09:57:08
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/profile/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6abd84ac8291_67254950',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ac91b7f6df1a67ffa718ca902c1c0e7ac7058c65' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/profile/default.tpl',
      1 => 1784691718,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6abd84ac8291_67254950 (Smarty_Internal_Template $_smarty_tpl) {
?><header class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a class="btn btn-default ui-breadcrumb" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=home" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Setting');?>
">
					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>

					<span class="ui-breadcrumb__item"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Home');?>
</span>
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
					<a href="javascript:void(0)" onClick="open_import(this, event)" class="btn btn-primary ui-title-bar__action" title="Import nhân sự từ Google Sheet"><i class="fa fa-upload"></i> Import</a>
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit" class="btn btn-success ui-title-bar__action" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew'));?>
</a>
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
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">Danh sách nhân viên</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<form method="post">
					<div class="form-search form-inline">
						<div class="form-group">
							<div class="input-group w-200px">
								<select class="iso-selectize" data-width="100%" name="department_id">
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_DEPARTMENT',$_smarty_tpl->tpl_vars['department_id']->value,'Phòng ban');?>

								</select>
							</div>
							<div class="input-group column-count-1 w-200px">
								<select class="iso-selectize" data-width="100%" name="exist_phone">
									<option value="">Có/Không có số điện thoại</option>
									<option value="1" <?php if ($_smarty_tpl->tpl_vars['exist_phone']->value == '1') {?>selected<?php }?>>Có số điện thoại</option>
									<option value="0" <?php if ($_smarty_tpl->tpl_vars['exist_phone']->value == '0') {?>selected<?php }?>>Không có số điện thoại</option>
								</select>
							</div>
							<div class="input-group w-200px">
								<select class="iso-selectize" name="status_id">
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_STATUS_STAFF',$_smarty_tpl->tpl_vars['status_id']->value,'Tình trạng');?>

								</select>
							</div>
							<div class="input-group w-px-100">
								<input type="text" class="form-control" name="birthday" value="<?php echo $_smarty_tpl->tpl_vars['birthday']->value;?>
" placeholder="Sinh nhật" onfocus="(this.type='month')" onblur="(this.type='text')" />
							</div>
							<div class="input-group">
								<input type="text" class="form-control" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('search');?>
" />
							</div>
						</div>
						<input type="hidden" name="filter" value="filter" />
						<button type="submit" class="btn btn-success"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('search','Search');?>
</button>
						<div class="pull-right">
							<div class="fr d-flex group_buttons">
								<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn mr-2 btn-warning"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('folder-o');?>
 <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('all');?>
 (<?php echo $_smarty_tpl->tpl_vars['totalRecord']->value;?>
)</a>
								<div class="dropdown">
									<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Exportto');?>

									<span class="caret"></span></button>
									<ul class="dropdown-menu w-100">
										<li><a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=export<?php echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('file-excel-o',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Excel'));?>
</a></li>
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
									<th class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
</th>
									<th class="text-center" width="5%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Avatar');?>
</th>
									<th class="text-left" width="20%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('FullName');?>
</th>
									<th class="text-left">Phòng ban</th>
									<th class="text-left">Ngày sinh</th>
									<th class="text-left">Giới tính</th>
									<th class="text-left">Email</th>
									<th class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Phone');?>
</th>
									<th class="text-center"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Status');?>
</th>
									<th class="text-right"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Register Date');?>
</th>
									<th class="text-right">Ngày kết thúc</th>
									<th class="text-right">Ngày cập nhật</th>
									<th class="text-center">Tình trạng</th>
								</tr></thead>
								<?php
$__section_i_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['allItem']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_2_total = $__section_i_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_2_total !== 0) {
for ($__section_i_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_2_iteration <= $__section_i_2_total; $__section_i_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
$_smarty_tpl->tpl_vars['__smarty_section_i']->value['last'] = ($__section_i_2_iteration === $__section_i_2_total);
?>
								<tr<?php if ($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['status_id'] == @constant('_STATUS_STAFF_OFF_ID')) {?> class="tr_Off"<?php }?>>
									<td class="text-center" style="white-space: nowrap;">
										<div class="btn-group<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['last'] : null)) {?> dropup<?php }?>">
											<button class="btn btn-xs btn-default dropdown-toggle" type="button" data-toggle="dropdown">
												<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('cog');?>
 <span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li style="display: none"><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('edit');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=view&profile_id=<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'];?>
">
													<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('eye',$_smarty_tpl->tpl_vars['core']->value->get_Lang('View'));?>
</a>
												</li>
												<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('edit');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&profile_id=<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'];?>
">
													<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil',$_smarty_tpl->tpl_vars['core']->value->get_Lang('edit'));?>
</a> 
												</li>
												<li><a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=delete&profile_id=<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'];
echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
" class="confirm_delete"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash',$_smarty_tpl->tpl_vars['core']->value->get_Lang('delete'));?>
</a></li>
												<li class="divider"></li>
												<li><a href="javascript:void(0);" profile_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'];?>
" onClick="$Core.member.send_email(this, event);" title="Gửi email"><i class="fa fa-envelope-o mr-2" aria-hidden="true"></i>Gửi email chào mừng</a></li>
												<li class="divider"></li>
												<li class="d-none"><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Thay mật khẩu');?>
" profile_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'];?>
" href="javascript:void(0);" onClick="open_password(this, event);"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('lock',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Thay mật khẩu'));?>
</a></li>
												<li><a title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Permission');?>
" profile_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'];?>
" href="javascript:void(0);" onClick="$Core.member.open_permiss(this, event);">
													<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('lock',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Permission'));?>
</a>
												</li>
											</ul>
										</div>
									</td>
									<td><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['code'];?>
</td>
									<td class="text-center">
										<img class="avatar m-0 small" src="<?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getAvatar($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'],$_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)],30,30);?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.svg'" >
									</td>
									<td class="text-nowrap"><a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=view&profile_id=<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'];?>
">
										<strong><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'];?>
 - <?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getFullName($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id']);?>
</strong><br />
										<span class="text-muted font11"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['role_id']);?>
</span>
									</a></td>
									<td>
										<select class="form-control input-sm" onChange="$Core.member.update_field(this,event)" 
											p_field="department_id" p_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'];?>
" >
											<option value="0">-- Chọn --</option>
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_departments']->value, '_oDep', false, '_oKey');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oKey']->value => $_smarty_tpl->tpl_vars['_oDep']->value) {
?>
											<option value="<?php echo $_smarty_tpl->tpl_vars['_oKey']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_oKey']->value == $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['department_id']) {?> selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['_oDep']->value;?>
</option>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										</select>
									</td>
									<td><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['birthday'];?>
</td>
									<td><?php if ($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['gender_id'] == '1') {?>Nam<?php } elseif ($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['gender_id'] == '2') {?>Nữ<?php } else { ?>--<?php }?></td>
									<td><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['email'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['phone'];?>
</td>
									<td bgcolor="#F5F5F5" class="text-center">
										<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Profile" toField="is_active" pkey="is_active" 
										sourse_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['profile_id'];?>
" rel="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_active'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Click to change status');?>
">
											<?php if ($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_active'] == '1') {?>
											<i class="fa fa-check-circle green"></i><?php } else { ?>
											<i class="fa fa-minus-circle red"></i><?php }?>
										</a>
									</td>
									<td class="text-right"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['reg_date'],true);?>
</td>
									<td class="text-right"><?php if (!empty($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['end_date'])) {
echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['end_date'],true);
} else { ?>--<?php }?></td>
									<td class="text-right"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['upd_date'],true);?>
</td>
									<td class="text-center"><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['status_name'];?>
</td>
								</tr>
								<?php
}
}
?>
							</table>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="d-flex justify-content-center">
						<ul class="pagination">
							<?php echo $_smarty_tpl->tpl_vars['html_pager']->value;?>

						</ul>
					</div>	
				</form>
			</div></div>
		</div></div>
	</div>
</div>

<style>
	.table{
		margin-bottom:0;
		max-width:10000px;
	}
	.tr_Off td{
		background:#ffcccc;
	}
</style>

<?php }
}
