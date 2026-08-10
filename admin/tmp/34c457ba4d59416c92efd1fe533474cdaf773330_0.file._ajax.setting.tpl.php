<?php
/* Smarty version 3.1.33, created on 2026-08-07 10:15:45
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/_ajax.setting.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a754de1d71a26_47703476',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '34c457ba4d59416c92efd1fe533474cdaf773330' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/_ajax.setting.tpl',
      1 => 1785925952,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a754de1d71a26_47703476 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['action']->value == '_form') {?>
<div class="modal-dialog modal-standard">
	<form action="" method="post" class="modal-content" onsubmit="return false;" id="frmIssue" encrupt="miltipart/form-data">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong><?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</strong></h3>
		</div>
		<div class="modal-body">
			<div class="form-group form-row">
				<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
</label>
				<div class="col-md-4">
					<input type="text" class="form-control required" placeholder="Mã" name="setting_code" value="<?php if ($_smarty_tpl->tpl_vars['setting_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['setting_code'];
}?>">
				</div>
				<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
</label>
				<div class="col-md-4">
					<input type="text" class="form-control required" placeholder="Nhập tiêu đề" name="title" value="<?php if ($_smarty_tpl->tpl_vars['setting_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneSetting']->value['title'];
}?>">
				</div>
			</div>
			<div class="form-group form-row">
				<label for="" class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('BgColor');?>
</label>
				<div class="col-md-4">
					<input type="color" class="form-control required" placeholder="Màu nền" 
					name="bgcolor" value="<?php if ($_smarty_tpl->tpl_vars['setting_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['bgcolor'];
}?>">
				</div>
				<label for="" class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('TextColor');?>
</label>
				<div class="col-md-4">
					<input type="color" class="form-control required" placeholder="Màu chữ" 
					name="textcolor" value="<?php if ($_smarty_tpl->tpl_vars['setting_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['textcolor'];
}?>">
				</div>
			</div>
			<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_PROJECT') {?>
			<div class="form-group form-row">
				<label for="" class="col-md-2 text-right col-form-label">Dự án</label>
				<div class="col-md-4">
					<select class="form-control required" onChange="$Core.setting.select_block(this, event)" 
						toId="slb_Block_Id" name="project_id">
						<option value="0">Chọn dự án</option>
						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, '_oProject');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProject']->value) {
?>
						<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['project_id'] == $_smarty_tpl->tpl_vars['_oProject']->value['project_id']) {?> selected<?php }?> 
							value="<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
</option>
						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
					</select>
				</div>
				<label for="" class="col-md-2 text-right col-form-label">Phân khu</label>
				<div class="col-md-4">
					<select class="form-control" id="slb_Block_Id" name="block_id">
						<option value="0">Chọn phân khu</option>
						<?php if (!empty($_smarty_tpl->tpl_vars['list_blocks']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_blocks']->value, '_oBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['more_information']->value['block_id'] == $_smarty_tpl->tpl_vars['_oBlock']->value['property_id']) {?> selected<?php }?> 
								value="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['property_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					</select>
				</div>
			</div>
			<div class="form-group form-row">
				<label class="col-md-2 text-right col-form-label">File quỹ ôm</label>
				<div class="col-md-4">
					<input type="text" class="form-control spreadsheetId" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['stock_hug_configs']['spreadsheetId'];?>
" 
						name="stock_hug_configs[spreadsheetId]" onChange="$Core.setting.get_worksheets(this, event)" />
				</div>
				<label class="col-md-2 text-right col-form-label">Sheet name</label>
				<div class="col-md-4">
					<div class="input-group">
						<select class="form-control slb_worksheets" name="stock_hug_configs[sheet_name]">
							<option value="0">Chọn sheet name</option>
							<?php echo $_smarty_tpl->tpl_vars['html_worksheets']->value;?>

						</select>
						<div class="input-group-btn">
							<button type="button" setting_id="<?php echo $_smarty_tpl->tpl_vars['setting_id']->value;?>
" onClick="$Core.setting.open_config_field(this, event)" 
								class="btn btn-cog btn-default"><i class="fa fa-cog"></i> Cài đặt</button>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group form-row">
				<label class="col-md-2 text-right col-form-label">Admin quản lý</label>
				<div class="col-md-10">
					<select class="form-control iso-select2" data-width="100%" multiple="true" name="project_admins[]">
						<option value="0">Admin dự án</option>
						<?php if (!empty($_smarty_tpl->tpl_vars['list_staffs']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_staffs']->value, '_oI');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oI']->value) {
?>
							<option<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oI']->value['profile_id'],$_smarty_tpl->tpl_vars['more_information']->value['project_admins'])) {?> selected="selected"<?php }?> 
								value="<?php echo $_smarty_tpl->tpl_vars['_oI']->value['profile_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oI']->value['code'];?>
 - <?php echo $_smarty_tpl->tpl_vars['_oI']->value['full_name'];?>
</option>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					</select>
				</div>
			</div>			
			<?php } elseif ($_smarty_tpl->tpl_vars['setting_type']->value == '_ACCOUNT') {?>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Query Tag</label>
					<div class="col-md-4">
						<input type="text" class="form-control spreadsheetId" value="<?php if ($_smarty_tpl->tpl_vars['setting_id']->value > '0' && !empty($_smarty_tpl->tpl_vars['more_information']->value['query_tags'])) {
echo $_smarty_tpl->tpl_vars['clsISO']->value->makeSlashListFromArray($_smarty_tpl->tpl_vars['more_information']->value['query_tags']);
}?>" name="tags" placeholder="|ASSET|" autocomplete="on" />
					</div>
					<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('ParentCategory');?>
</label>
					<div class="col-md-4">
						<div class="input-group d-flex">
							<select class="form-control" name="parent_id" value="<?php if ($_smarty_tpl->tpl_vars['setting_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneSetting']->value['title'];
}?>">
								<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectBySettingTypeTitle($_smarty_tpl->tpl_vars['setting_type']->value,$_smarty_tpl->tpl_vars['oneSetting']->value['parent_id'],"Danh mục cha");?>

							</select>
							<input type="text" class="form-control w-35" Name="account_type" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['account_type'];?>
" />
						</div>
					</div>
				</div>			
						
			<?php } elseif ($_smarty_tpl->tpl_vars['setting_type']->value == '_CRITERIA_ASSET_CATEGORY' || $_smarty_tpl->tpl_vars['setting_type']->value == '_LIABILITIES_EQUITY' || $_smarty_tpl->tpl_vars['setting_type']->value == '_CASH_FLOW_CATEGORY') {?>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Tài khoản</label>
					<div class="col-md-4">
						<select class="form-control iso-select2 " name="lst_account_id[]" multiple >
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectBySettingTypeTitle("_ACCOUNT",$_smarty_tpl->tpl_vars['more_information']->value['lst_account_id'],"Tài khoản");?>

						</select>
					</div>
					<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('ParentCategory');?>
</label>
					<div class="col-md-4">
						<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_CASH_FLOW_CATEGORY') {?>
							<div class="input-group d-flex">
								<select class="form-control" name="parent_id" value="<?php if ($_smarty_tpl->tpl_vars['setting_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneSetting']->value['title'];
}?>">
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectBySettingTypeTitle($_smarty_tpl->tpl_vars['setting_type']->value,$_smarty_tpl->tpl_vars['oneSetting']->value['parent_id'],"Danh mục cha");?>

								</select>
								<input type="text" class="form-control w-35" name="direction" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['direction'];?>
" />
							</div>
						<?php } else { ?>
						<select class="form-control" name="parent_id" value="<?php if ($_smarty_tpl->tpl_vars['setting_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneSetting']->value['title'];
}?>">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectBySettingTypeTitle($_smarty_tpl->tpl_vars['setting_type']->value,$_smarty_tpl->tpl_vars['oneSetting']->value['parent_id'],"Danh mục cha");?>

						</select>
						<?php }?>
					</div>
				</div>			
			<?php } elseif ($_smarty_tpl->tpl_vars['setting_type']->value != '_MEETING_ROOM') {?>
			<div class="form-group form-row">
				<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('ParentCategory');?>
</label>
				<div class="col-md-10">
					<select class="form-control" name="parent_id" value="<?php if ($_smarty_tpl->tpl_vars['setting_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneSetting']->value['title'];
}?>">
						<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectBySettingTypeTitle($_smarty_tpl->tpl_vars['setting_type']->value,$_smarty_tpl->tpl_vars['oneSetting']->value['parent_id'],"Danh mục cha");?>

					</select>
				</div>
			</div>
			<?php }?>
			<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == "_CHECKIN_TAGS") {?>
				<div class="form-group form-row">
					<label class="col-md-2 text-right col-form-label">Icon</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="icon" placeholder="Nhập class icon" id="icon" value="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['icon'];?>
">
					</div>
				</div>			
			<?php }?>
			<div class="form-group form-row">
				<label class="col-md-2 text-right col-form-label"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Intro');?>
</label>
				<div class="col-md-10">
					<textarea class="form-control isoTextArea" id="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid();?>
" cols="255" placeholder="Nhập giới thiệu" data-name="intro" rows="3"><?php if ($_smarty_tpl->tpl_vars['setting_id']->value > '0') {
echo $_smarty_tpl->tpl_vars['more_information']->value['intro'];
}?></textarea>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_MEETING_ROOM') {?>
				<input type="hidden" name="office_id" value="<?php echo $_smarty_tpl->tpl_vars['office_id']->value;?>
">
			<?php }?>
			<button type="button" class="btn btn-success" onClick="save_setting(this)" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" 
				_reload="<?php echo $_smarty_tpl->tpl_vars['_reload']->value;?>
" setting_id="<?php echo $_smarty_tpl->tpl_vars['setting_id']->value;?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
">
				<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('check',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Save'));?>

			</button>
		</div>
	</form>
</div>
<?php } else { ?>
    <?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_MEETING_ROOM') {?>
		<div class="table-setting text-nowrap overflow-x-auto" style="max-height: 400px">
			<table class="table table-hover table-vertical table-striped table-responsive TableListSetting_<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
" width="100%">
				<thead style="position:sticky;top:0;background: #FFF" ><tr>
					<th class="text-left" width="5%">No.</th>
					<th class="text-left" width="45%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
</th>
					<th class="text-left" width="20%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
</th>
					<th class="text-center">Tình trạng</th>
				</tr></thead>
				<tbody>
				<?php if (!empty($_smarty_tpl->tpl_vars['lstOffice']->value)) {?>
					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstOffice']->value, '_oItem', false, 'key', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
						<?php $_smarty_tpl->_assignInScope('office_id', $_smarty_tpl->tpl_vars['_oItem']->value['setting_id']);?>
						<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>
						<tr class="bold" id="<?php echo $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id'];?>
">
							<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>
							<td class="text-nowrap" data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
<a href="javascript:void(0);" onclick="open_setting(this)" office_id="<?php echo $_smarty_tpl->tpl_vars['office_id']->value;?>
" setting_type="_MEETING_ROOM" setting_id="0"><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/add.png" width="25px"></a>
							</td>
							<td class="text-center" colspan="2"></td>
						</tr>
						<?php $_smarty_tpl->_assignInScope('lstMeetingRoom', $_smarty_tpl->tpl_vars['arr_meeting_room']->value[$_smarty_tpl->tpl_vars['office_id']->value]);?>
						<tr class="d-none"></tr>
						<?php if (!empty($_smarty_tpl->tpl_vars['lstMeetingRoom']->value)) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstMeetingRoom']->value, '_oMeeting', false, 'j', 'i_mr', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['j']->value => $_smarty_tpl->tpl_vars['_oMeeting']->value) {
?>
							<?php $_smarty_tpl->_assignInScope('moreInformation', $_smarty_tpl->tpl_vars['clsISO']->value->to_array_json($_smarty_tpl->tpl_vars['_oMeeting']->value['more_information']));?>
								<tr id="<?php echo $_smarty_tpl->tpl_vars['_oMeeting']->value['setting_id'];?>
">								
									<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
										<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
											<button class="btn btn-default" onClick="open_setting(this)" office_id="<?php echo $_smarty_tpl->tpl_vars['office_id']->value;?>
" setting_id="<?php echo $_smarty_tpl->tpl_vars['_oMeeting']->value['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
											<button class="btn btn-default" onClick="delete_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['_oMeeting']->value['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
										</div>
									</td>
									<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
"><?php echo $_smarty_tpl->tpl_vars['_oMeeting']->value['title'];?>
</td>
									<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
										<?php if (!empty($_smarty_tpl->tpl_vars['moreInformation']->value['setting_code'])) {?>
											<?php echo $_smarty_tpl->tpl_vars['moreInformation']->value['setting_code'];?>

										<?php } else { ?>
										--
										<?php }?>
									</td>
									<td class="text-center">
										<label class="switch">
											<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['_oMeeting']->value['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.setting.set_status(this, event)" 
												setting_id="<?php echo $_smarty_tpl->tpl_vars['_oMeeting']->value['setting_id'];?>
" value="1" />
											<span class="slider round"></span>
										</label>
									</td>
								</tr>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
				<?php } else { ?>
					<tr>
						<td colspan="7" class="text-center">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->renderHTMLNoDocument($_smarty_tpl->tpl_vars['core']->value->get_Lang('Not any records(s) here'));?>

						</td>
					</tr>
				<?php }?>
				</tbody>
			</table>
		</div>
	<?php } elseif ($_smarty_tpl->tpl_vars['setting_type']->value == _LIST_FORM_BUSINESS) {?>
		<div class="table-setting text-nowrap overflow-x-auto" style="max-height: 400px">
		<table class="table table-hover table-vertical table-striped table-responsive TableListSetting_<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
" width="100%">
			<thead style="position:sticky;top:0;background: #FFF" ><tr>
				<th class="text-center" width="5%"></th>
				<th class="text-left" width="5%">No.</th>
				<th class="text-left" width="45%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
</th>
				<th class="text-left" width="20%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
</th>
				<th class="text-center">Tình trạng</th>
				<th class="text-left" width="10%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
</th>
			</tr></thead>
			<tbody>
			<?php if ($_smarty_tpl->tpl_vars['lstSetting']->value[0]['setting_id'] != '') {?>
				<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstSetting']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= $__section_i_0_total; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
				<?php $_smarty_tpl->_assignInScope('setting_id', $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id']);?>
				<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['more_information']);?>
				<tr class="bold" id="<?php echo $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id'];?>
">
					<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">
						<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>

					</td>
					<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
</td>
					<td class="text-nowrap" data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
"><?php echo $_smarty_tpl->tpl_vars['clsSetting']->value->getTitle($_smarty_tpl->tpl_vars['setting_id']->value);?>

					</td>
					<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
						<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['setting_code'])) {?>
							<?php echo $_smarty_tpl->tpl_vars['more_information']->value['setting_code'];?>

						<?php } else { ?>
						--
						<?php }?>
					</td>
					<td class="text-center">
						<label class="switch">
							<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.setting.set_status(this, event)" 
								setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id'];?>
" value="1" />
							<span class="slider round"></span>
						</label>
					</td>
					<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
						<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
							<button class="btn btn-default" onClick="open_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
							<button class="btn btn-default" onClick="delete_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
							<button class="btn btn-default btn-add-property" onClick="$Core.setting.addProperty(this, event)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><i class="fa fa-cog"></i></button>
						</div>
					</td>
				</tr>
				<?php $_smarty_tpl->_assignInScope('lstChild', $_smarty_tpl->tpl_vars['clsSetting']->value->getItems($_smarty_tpl->tpl_vars['setting_type']->value,$_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id']));?>
				<tr class="d-none"></tr>
				<?php if ($_smarty_tpl->tpl_vars['lstChild']->value[0]['setting_id'] != '') {?>
					<?php
$__section_j_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstChild']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_j_1_total = $__section_j_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_j'] = new Smarty_Variable(array());
if ($__section_j_1_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] <= $__section_j_1_total; $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']++){
?>
					<?php $_smarty_tpl->_assignInScope('moreInformation', $_smarty_tpl->tpl_vars['clsISO']->value->to_array_json($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['more_information']));?>
					<tr id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id'];?>
">
						<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>
</td>
						<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
</td>
						<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">+&nbsp;<?php echo $_smarty_tpl->tpl_vars['clsSetting']->value->getTitle($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id']);?>

							<?php if ($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['image'] != '') {?>
							<span class="label label-default">Icon</span>
							<?php }?>
						</td>
						<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
							<?php if (!empty($_smarty_tpl->tpl_vars['moreInformation']->value['setting_code'])) {?>
								<?php echo $_smarty_tpl->tpl_vars['moreInformation']->value['setting_code'];?>

							<?php } else { ?>
							--
							<?php }?>
						</td>
						<td class="text-center">
							<label class="switch">
								<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.setting.set_status(this, event)" 
									setting_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id'];?>
" value="1" />
								<span class="slider round"></span>
							</label>
						</td>
						<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
							<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
								<button class="btn btn-default" onClick="open_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
								<button class="btn btn-default" onClick="delete_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
							</div>
						</td>
					</tr>
					<?php $_smarty_tpl->_assignInScope('lstSubChild', $_smarty_tpl->tpl_vars['clsSetting']->value->getItems($_smarty_tpl->tpl_vars['setting_type']->value,$_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id']));?>
					<?php if (!empty($_smarty_tpl->tpl_vars['lstSubChild']->value)) {?>
						<?php
$__section_k_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstSubChild']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_k_2_total = $__section_k_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_k'] = new Smarty_Variable(array());
if ($__section_k_2_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] <= $__section_k_2_total; $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']++){
?>
						<?php $_smarty_tpl->_assignInScope('moreInformationChild', $_smarty_tpl->tpl_vars['clsISO']->value->to_array_json($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['more_information']));?>
						<tr id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id'];?>
">
							<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>
</td>
							<td data-label="No." class="text-left"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] : null);?>
</td>
							<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">++&nbsp;<?php echo $_smarty_tpl->tpl_vars['clsSetting']->value->getTitle($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id']);?>
</td>
							<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
								<?php if (!empty($_smarty_tpl->tpl_vars['moreInformationChild']->value['setting_code'])) {?>
									<?php echo $_smarty_tpl->tpl_vars['moreInformationChild']->value['setting_code'];?>

								<?php } else { ?>
								--
								<?php }?>
							</td>
							<td class="text-center">
								<label class="switch">
									<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.setting.set_status(this, event)" 
										setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id'];?>
" value="1" />
									<span class="slider round"></span>
								</label>
							</td>
							<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
								<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
									<button class="btn btn-default" onClick="open_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
									<button class="btn btn-default" onClick="delete_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
								</div>
							</td>
						</tr>
						<?php $_smarty_tpl->_assignInScope('lstSubSubChild', $_smarty_tpl->tpl_vars['clsSetting']->value->getItems($_smarty_tpl->tpl_vars['setting_type']->value,$_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id']));?>
						<?php if (!empty($_smarty_tpl->tpl_vars['lstSubSubChild']->value)) {?>
							<?php
$__section_n_3_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstSubSubChild']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_n_3_total = $__section_n_3_loop;
$_smarty_tpl->tpl_vars['__smarty_section_n'] = new Smarty_Variable(array());
if ($__section_n_3_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration'] <= $__section_n_3_total; $_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']++){
?>
							<tr id="<?php echo $_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['setting_id'];?>
">
								<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>
</td>
								<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration'] : null);?>
</td>
								<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">+++&nbsp;<?php echo $_smarty_tpl->tpl_vars['clsSetting']->value->getTitle($_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['setting_id']);?>
</td>
								<td class="text-center">
									<label class="switch">
										<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.setting.set_status(this, event)" 
											setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['setting_id'];?>
" value="1" />
										<span class="slider round"></span>
									</label>
								</td>
								<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
									<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
										<button class="btn btn-default" onClick="open_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
										<button class="btn btn-default" onClick="delete_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
									</div>
								</td>
							</tr>
							<?php
}
}
?>
						<?php }?>
						<?php
}
}
?>
					<?php }?>
					<?php
}
}
?>
				<?php }?>
				<?php
}
}
?>
			<?php } else { ?>
				<tr>
					<td colspan="7" class="text-center">
						<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->renderHTMLNoDocument($_smarty_tpl->tpl_vars['core']->value->get_Lang('Not any records(s) here'));?>

					</td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
	<?php } else { ?>
		<div class="table-setting text-nowrap overflow-x-auto">
			<table class="table table-hover table-vertical table-striped table-responsive TableListSetting_<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
" width="100%">
				<thead style="position:sticky;top:0;z-index: 2;background: #FFF" ><tr>
					<th class="text-center" width="5%"></th>
					<th class="text-left" width="10%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
</th>
					<th class="text-left" width="5%">No.</th>
					<th class="text-left" width="20%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
</th>
					<th class="text-left" width="25%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
</th>
					<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_ACCOUNT') {?>
						<th class="text-left" width="20%">Query tags</th>
					<?php }?>
					<th class="text-center">Tình trạng</th>
				</tr></thead>
				<tbody>
				<?php if ($_smarty_tpl->tpl_vars['lstSetting']->value[0]['setting_id'] != '') {?>
					<?php
$__section_i_4_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstSetting']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_4_total = $__section_i_4_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_4_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] <= $__section_i_4_total; $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
					<?php $_smarty_tpl->_assignInScope('setting_id', $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id']);?>
					<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['more_information']);?>
					<tr class="bold" id="<?php echo $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id'];?>
">
						<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B">
							<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>

						</td>
						<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
							<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
								<button class="btn btn-default" onClick="open_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
								<button class="btn btn-default" onClick="delete_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
							</div>
						</td>
						<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
</td>
						<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
							<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['setting_code'])) {?>
								<?php echo $_smarty_tpl->tpl_vars['more_information']->value['setting_code'];?>

							<?php } else { ?>
							--
							<?php }?>
						</td>
						<td class="text-nowrap" data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
"><?php echo $_smarty_tpl->tpl_vars['clsSetting']->value->getTitle($_smarty_tpl->tpl_vars['setting_id']->value);?>

							<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_ACCOUNT') {?>
								(<?php echo $_smarty_tpl->tpl_vars['more_information']->value['account_type'];?>
)
							<?php }?>
							<a href="javascript:void(0);" onclick="open_setting(this)" parent_id="<?php echo $_smarty_tpl->tpl_vars['setting_id']->value;?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
" setting_id="0"><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/add.png" width="25px" /></a>
						</td>
						<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_ACCOUNT') {?>
							<td data-label="Query tags">
								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['query_tags'])) {?>
									<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeSlashListFromArray($_smarty_tpl->tpl_vars['more_information']->value['query_tags']);?>

								<?php } else { ?>
								--
								<?php }?>
							</td>
						<?php }?>
						<!--<td class="text-center">
							<label class="switch">
								<input type="checkbox" onChange="hide_stock_globe(this, event)"<?php if (isset($_smarty_tpl->tpl_vars['more_information']->value['hide_crawl_excel']) && $_smarty_tpl->tpl_vars['more_information']->value['hide_crawl_excel'] == '1') {?> checked<?php }?> to_field="hide_crawl_excel" setting_id="<?php echo $_smarty_tpl->tpl_vars['setting_id']->value;?>
" value="1" /> <span class="slider round"></span>
							</label>
						</td>-->
						<!-- End -->
						<td class="text-center">
							<label class="switch">
								<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.setting.set_status(this, event)" 
									setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id'];?>
" value="1" />
								<span class="slider round"></span>
							</label>
						</td>
					</tr>
					<?php $_smarty_tpl->_assignInScope('lstChild', $_smarty_tpl->tpl_vars['clsSetting']->value->getItems($_smarty_tpl->tpl_vars['setting_type']->value,$_smarty_tpl->tpl_vars['lstSetting']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['setting_id']));?>
					<?php if ($_smarty_tpl->tpl_vars['lstChild']->value[0]['setting_id'] != '') {?>
						<?php
$__section_j_5_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstChild']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_j_5_total = $__section_j_5_loop;
$_smarty_tpl->tpl_vars['__smarty_section_j'] = new Smarty_Variable(array());
if ($__section_j_5_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] <= $__section_j_5_total; $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']++){
?>
						<?php $_smarty_tpl->_assignInScope('moreInformation', $_smarty_tpl->tpl_vars['clsISO']->value->to_array_json($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['more_information']));?>
						<tr id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id'];?>
">
							<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>
</td>
							<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
								<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
									<button class="btn btn-default" onClick="open_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
									<button class="btn btn-default" onClick="delete_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
								</div>
							</td>
							<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
</td>
							<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
								<?php if (!empty($_smarty_tpl->tpl_vars['moreInformation']->value['setting_code'])) {?>
									<?php echo $_smarty_tpl->tpl_vars['moreInformation']->value['setting_code'];?>

								<?php } else { ?>
								--
								<?php }?>
							</td>
							<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">+&nbsp;<?php echo $_smarty_tpl->tpl_vars['clsSetting']->value->getTitle($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id']);?>

								<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_ACCOUNT') {?>
									(<?php echo $_smarty_tpl->tpl_vars['moreInformation']->value['account_type'];?>
)
								<?php }?>
								<?php if ($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['image'] != '') {?>
								<span class="label label-default">Icon</span>
								<?php }?>
							</td>
							<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_ACCOUNT') {?>
								<td data-label="Query tags">
									<?php if (!empty($_smarty_tpl->tpl_vars['moreInformation']->value['query_tags'])) {?>
										<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeSlashListFromArray($_smarty_tpl->tpl_vars['moreInformation']->value['query_tags']);?>

									<?php } else { ?>
									--
									<?php }?>
								</td>
							<?php }?>

							<td class="text-center">
								<label class="switch">
									<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.setting.set_status(this, event)" 
										setting_id="<?php echo $_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id'];?>
" value="1" />
									<span class="slider round"></span>
								</label>
							</td>
						</tr>
						<?php $_smarty_tpl->_assignInScope('lstSubChild', $_smarty_tpl->tpl_vars['clsSetting']->value->getItems($_smarty_tpl->tpl_vars['setting_type']->value,$_smarty_tpl->tpl_vars['lstChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['setting_id']));?>
						<?php if (!empty($_smarty_tpl->tpl_vars['lstSubChild']->value)) {?>
							<?php
$__section_k_6_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstSubChild']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_k_6_total = $__section_k_6_loop;
$_smarty_tpl->tpl_vars['__smarty_section_k'] = new Smarty_Variable(array());
if ($__section_k_6_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] <= $__section_k_6_total; $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']++){
?>
							<?php $_smarty_tpl->_assignInScope('moreInformationChild', $_smarty_tpl->tpl_vars['clsISO']->value->to_array_json($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['more_information']));?>
							<tr id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id'];?>
">
								<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>
</td>
								<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
									<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
										<button class="btn btn-default" onClick="open_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
										<button class="btn btn-default" onClick="delete_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
									</div>
								</td>
								<td data-label="No." class="text-left"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] : null);?>
</td>
								<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
									<?php if (!empty($_smarty_tpl->tpl_vars['moreInformationChild']->value['setting_code'])) {?>
										<?php echo $_smarty_tpl->tpl_vars['moreInformationChild']->value['setting_code'];?>

									<?php } else { ?>
									--
									<?php }?>
								</td>
								<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">
									++&nbsp;<?php echo $_smarty_tpl->tpl_vars['clsSetting']->value->getTitle($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id']);?>

									<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_ACCOUNT') {?>
										(<?php echo $_smarty_tpl->tpl_vars['moreInformationChild']->value['account_type'];?>
)
									<?php }?>
								</td>
								<?php if ($_smarty_tpl->tpl_vars['setting_type']->value == '_ACCOUNT') {?>
									<td data-label="Query tags">
										<?php if (!empty($_smarty_tpl->tpl_vars['moreInformationChild']->value['query_tags'])) {?>
											<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeSlashListFromArray($_smarty_tpl->tpl_vars['moreInformationChild']->value['query_tags']);?>

										<?php } else { ?>
										--
										<?php }?>
									</td>
								<?php }?>

								<td class="text-center">
									<label class="switch">
										<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.setting.set_status(this, event)" 
											setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id'];?>
" value="1" />
										<span class="slider round"></span>
									</label>
								</td>
							</tr>
							<?php $_smarty_tpl->_assignInScope('lstSubSubChild', $_smarty_tpl->tpl_vars['clsSetting']->value->getItems($_smarty_tpl->tpl_vars['setting_type']->value,$_smarty_tpl->tpl_vars['lstSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['setting_id']));?>
							<?php if (!empty($_smarty_tpl->tpl_vars['lstSubSubChild']->value)) {?>
								<?php
$__section_n_7_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstSubSubChild']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_n_7_total = $__section_n_7_loop;
$_smarty_tpl->tpl_vars['__smarty_section_n'] = new Smarty_Variable(array());
if ($__section_n_7_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration'] <= $__section_n_7_total; $_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']++){
?>
								<tr id="<?php echo $_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['setting_id'];?>
">
									<td data-label="" class="text-center mySortableHandler" style="color:#2A5F8B"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('bars');?>
</td>
									<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">
										<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">
											<button class="btn btn-default" onClick="open_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
											<button class="btn btn-default" onClick="delete_setting(this)" setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['setting_id'];?>
" setting_type="<?php echo $_smarty_tpl->tpl_vars['setting_type']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
										</div>
									</td>
									<td data-label="No."><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['iteration'] : null);?>
</td>
									<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
">+++&nbsp;<?php echo $_smarty_tpl->tpl_vars['clsSetting']->value->getTitle($_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['setting_id']);?>
</td>
									<td class="text-center">
										<label class="switch">
											<input type="checkbox"<?php if ($_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['is_trash'] == '0') {?> checked<?php }?> onChange="$Core.setting.set_status(this, event)" 
												setting_id="<?php echo $_smarty_tpl->tpl_vars['lstSubSubChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_n']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_n']->value['index'] : null)]['setting_id'];?>
" value="1" />
											<span class="slider round"></span>
										</label>
									</td>
								</tr>
								<?php
}
}
?>
							<?php }?>
							<?php
}
}
?>
						<?php }?>
						<?php
}
}
?>
					<?php }?>
					<?php
}
}
?>
				<?php } else { ?>
					<tr>
						<td colspan="7" class="text-center">
							<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->renderHTMLNoDocument($_smarty_tpl->tpl_vars['core']->value->get_Lang('Not any records(s) here'));?>

						</td>
					</tr>
				<?php }?>
				</tbody>
			</table>
		</div>
	<?php }
}?>
    
    <?php }
}
