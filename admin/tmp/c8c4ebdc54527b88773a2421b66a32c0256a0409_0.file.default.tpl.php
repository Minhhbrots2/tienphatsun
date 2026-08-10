<?php
/* Smarty version 3.1.33, created on 2026-08-06 16:28:58
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/permiss/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7453da8298f0_77625973',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c8c4ebdc54527b88773a2421b66a32c0256a0409' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/permiss/default.tpl',
      1 => 1786008536,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7453da8298f0_77625973 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">Định nghĩa quyền</h1>
				<p class="type--subdued">Bảng quyền hạn được truy cập</p>
			</div>
		</div>
		<div class="action-bar">
			<div class="ui-title-bar__mobile-primary-actions">
				<div class="ui-title-bar__actions">
					<a href="javascript:;" onClick="$Core.permiss.open_permiss(this, event)" parent_id="0" tp="group" profile_type="<?php echo $_smarty_tpl->tpl_vars['profile_type']->value;?>
" 
						permiss_id="0" class="ui-button ui-button--transparent ui-title-bar__action" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew');?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus','Thêm nhóm');?>
</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo '<script'; ?>
 type="text/javascript">
	var profile_type = '<?php echo $_smarty_tpl->tpl_vars['profile_type']->value;?>
';
<?php echo '</script'; ?>
>
<div class="ui-layout ui-layout--full-width">
	<div class="ui-layout__sections"><div class="ui-layout__section">
		<div class="ui-layout__item"><div class="ui-card">
			<div class="next-tab__container">
				<ul class="next-tab__list filter-tab-list">
					<li class="filter-tab-item" data-tab-index="1">
						<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&profile_type=user.fh" 
							class="filter-tab filter-tab-active show-all-items next-tab<?php if ($_smarty_tpl->tpl_vars['profile_type']->value == 'user.fh') {?> next-tab--is-active<?php }?>">Danh sách quyền</a>
					</li>
				</ul>
			</div>
			<div class="ui-card__section has-bulk-actions pages">
				<form method="post" enctype="multipart/form-data">
					<div class="form-search">
						<div class="d-flex align-items-center">
							<div class="input-group mr-2">
								<input type="text" class="form-control" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="tìm kiếm..." />
							</div>
							<input type="hidden" name="filter" value="filter" />
							<button type="submit" class="btn btn-success"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('search','Tìm');?>
</button>
						</div>
					</div>
					<div class="hastable">
						<div class="freeze-table dragscroll" style="overflow-x: scroll; width:100%;">
							<table id="tableCall" cellspacing="0" class="table table-vertical table-striped" width="100%">
								<thead><tr>
									<th width="5%" class="text-center">No</th>
									<th width="5%" class="text-center"></th>
									<th width="30%" class="text-left">Tiêu đề</th>
									<th class="text-left">Mã</th>
									<th class="text-left">Mô tả</th>
									<th class="text-center" width="90px">Hiển thị</th>
									<th width="150px" class="text-left">H.Động</th>
								</tr></thead>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_groups']->value, '_oGroup', false, NULL, 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oGroup']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
									<?php $_smarty_tpl->_assignInScope('list_permiss', $_smarty_tpl->tpl_vars['_oGroup']->value['list_permiss']);?>
									<?php $_smarty_tpl->_assignInScope('gId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqId());?>
									<tr>
										<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
</td>
										<td class="text-center">
											<button type="button" onClick="$Core.permiss.open_permiss(this, event)" tp="permiss" profile_type="<?php echo $_smarty_tpl->tpl_vars['profile_type']->value;?>
" 
												permiss_id="0" parent_id="<?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['permiss_id'];?>
" class="btn btn-success">+ Tạo</button>
										</td>
										<td class="bold"><?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['title'];?>
</td>
										<td><?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['code'];?>
</td>
										<td><?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['description'];?>
</td>
										<td class="text-center">
											<label class="switch" title="Hiển thị / Ẩn nhóm quyền">
												<input type="checkbox" tp="group" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" permiss_id="<?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['permiss_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['_oGroup']->value['is_active'] == '1') {?> checked<?php }?> 
													onclick="$Core.permiss.toggle_permiss(this, event)">
												<span class="slider round"></span>
											</label>
										</td>
										<td class="text-center">
											<button onClick="$Core.permiss.open_permiss(this, event)" tp="group" parent_id="0" permiss_id="<?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['permiss_id'];?>
" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
											<button onClick="$Core.permiss.delete_permiss(this, event)" tp="group" parent_id="0" permiss_id="<?php echo $_smarty_tpl->tpl_vars['_oGroup']->value['permiss_id'];?>
" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
										</td>
									</tr>
									<?php if (!empty($_smarty_tpl->tpl_vars['list_permiss']->value)) {?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_permiss']->value, '_oPermiss', false, NULL, 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oPermiss']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
										<tr>
											<td></td>
											<td class="text-center"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null);?>
.<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration'] : null);?>
</td>
											<td class="bold"><?php echo $_smarty_tpl->tpl_vars['_oPermiss']->value['title'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['_oPermiss']->value['code'];?>
</td>
											<td><?php echo $_smarty_tpl->tpl_vars['_oPermiss']->value['description'];?>
</td>
											<td class="text-center">
												<label class="switch" title="Hiển thị / Ẩn quyền">
													<input type="checkbox" tp="permiss" gId="<?php echo $_smarty_tpl->tpl_vars['gId']->value;?>
" permiss_id="<?php echo $_smarty_tpl->tpl_vars['_oPermiss']->value['permiss_id'];?>
"<?php if ($_smarty_tpl->tpl_vars['_oPermiss']->value['is_active'] == '1') {?> checked<?php }?> onclick="$Core.permiss.toggle_permiss(this, event)">
													<span class="slider round"></span>
												</label>
											</td>
											<td class="text-center">
												<button onClick="$Core.permiss.open_permiss(this, event)" tp="permiss" parent_id="<?php echo $_smarty_tpl->tpl_vars['_oPermiss']->value['parent_id'];?>
" parent_id="0" permiss_id="<?php echo $_smarty_tpl->tpl_vars['_oPermiss']->value['permiss_id'];?>
" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>
												<button onClick="$Core.permiss.delete_permiss(this, event)" tp="permiss" parent_id="<?php echo $_smarty_tpl->tpl_vars['_oPermiss']->value['parent_id'];?>
" permiss_id="<?php echo $_smarty_tpl->tpl_vars['_oPermiss']->value['permiss_id'];?>
" class="btn btn-default"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>
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
							</table>
						</div>
					</div>
				</form>
			</div></div>
		</div></div>
	</div>
</div><?php }
}
