<?php
/* Smarty version 3.1.33, created on 2026-08-07 15:51:18
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/_ajax.staff.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a759c862b39c9_42147628',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5fe62783d8864b5ba30f96674e47e801da6ff0d9' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/member/_ajax.staff.tpl',
      1 => 1786085971,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a759c862b39c9_42147628 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal right fade show" id="<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content overflow-hidden bg-grayter">
			<div class="modal-header bg-white gap-3 align-items-center justify-content-between">
				<div class="d-flex align-items-center gap-2 mb-lg-0">
					<form method="POST" id="frmIssue" class="mr-2" enctype="multipart/form-data">
						<div class="avatar avatar-lg position-relative">
							<img id="avatar_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" class="w-100 h-100 rounded-circle" src="<?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['avatar'];?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg'" />
							<input type="file" id="selectFile_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" onChange="$Core.member.file_upload(this, event)" profile_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" class="d-none" name="avatar" toImg="avatar_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" >
							<a href="javascript:void(0);" profile_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" class="camera" onclick=
							"$Core.member.file_explorer(this,event)" toId="selectFile_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
"><i class="bx bx-camera fs-12"></i></a>
						</div>
					</form>
					<div class="meta__info">
						<h5 class="modal-title mb-0 fs-5 fw-bold"><?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['full_name'];?>
</h5>
						<div class="d-flex gap-3 align-items-center">
							<div class="highlight-item">
								<small class="text-muted text-left">Mã NV</small>
								<div class="metadata-row-viewer"><?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['code'];?>
</div>
							</div>
							<div class="highlight-item">
								<small class="text-muted text-left">Tình trạng</small>
								<div class="metadata-row-viewer">
									<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['dbProfile']->value['status_id']);?>

								</div>
							</div>
							<div class="text-muted d-none d-lg-block">
								<small class="text-muted text-left">Cập nhật lần cuối</small>
								<div class="metadata-row-viewer"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['dbProfile']->value['upd_date'],true);?>
</div>
							</div>				
						</div>
					</div>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body scroller" style="background:rgb(245,245,245) !important"><div class="form-row">
				<div class="col-12 col-md-8">
					<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
						<?php $_smarty_tpl->_assignInScope('tabid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
						<li class="nav-item">
							<a class="nav-link active" data-bs-toggle="tab" role="tab" data-bs-target="#home_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
								<i class='bx bx-info-circle' ></i> Chi tiết</a>
						</li>
						<li class="nav-item">
							<a class="nav-link billing" data-bs-toggle="tab" role="tab" data-bs-target="#billing_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
								<i class='bx bx-chart'></i> Lịch sử giao dịch</a>
						</li>
						<li class="nav-item">
							<a class="nav-link level_logs" data-bs-toggle="tab" role="tab" data-bs-target="#level_logs_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
								<i class='bx bx-analyse'></i> Quá trình thăng tiến </a>
						</li>
						<li class="nav-item">
							<a class="nav-link action_logs" data-bs-toggle="tab" role="tab" data-bs-target="#action_logs_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
								<i class='bx bx-sync'></i> Lịch sử
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link view_stock_logs" data-bs-toggle="tab" role="tab" data-type="stock_logs" data-bs-target="#view_stock_logs_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
								<i class='bx bx-search'></i> Check căn
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link view_report_share" data-bs-toggle="tab" role="tab" data-type="report_share" data-bs-target="#view_report_share_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
">
								<i class='bx bx-run'></i> Tiếp khách
							</a>
						</li>
					</ul>
					<div class="w-100 tab-content pt-3 pb-0 px-0">
						<div class="tab-pane fade show active" id="home_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-heading">
										<div class="d-flex align-items-center justify-content-between">
											<h3 class="panel-title">Kênh mạng xã hội của bạn</h3>
											<a href="javascript:void(0);" profile_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" onClick="$Core.member.open_social_channels(this,event)" 
												class="btn btn-sm btn-outline-default"><i class="bx bx-pencil"></i> Quản lý</a>
										</div>
									</div>
									<div class="panel-body no-easyui">
										<div class="holder_social_channels_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
">
										<?php if (!empty($_smarty_tpl->tpl_vars['social_channels']->value)) {?>
											<div class="row g-2">
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['social_channels']->value, '_oSC');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oSC']->value) {
?>
											<?php $_smarty_tpl->_assignInScope('_sc_icon', 'bxl-facebook');
$_smarty_tpl->_assignInScope('_sc_color', 'text-primary');
$_smarty_tpl->_assignInScope('_sc_label', 'Facebook');?>
											<?php if ($_smarty_tpl->tpl_vars['_oSC']->value['category'] == 'tiktok') {
$_smarty_tpl->_assignInScope('_sc_icon', 'bxl-tiktok');
$_smarty_tpl->_assignInScope('_sc_color', 'text-dark');
$_smarty_tpl->_assignInScope('_sc_label', 'TikTok');
}?>
											<?php if ($_smarty_tpl->tpl_vars['_oSC']->value['category'] == 'youtube') {
$_smarty_tpl->_assignInScope('_sc_icon', 'bxl-youtube');
$_smarty_tpl->_assignInScope('_sc_color', 'text-danger');
$_smarty_tpl->_assignInScope('_sc_label', 'Youtube');
}?>
											<?php if ($_smarty_tpl->tpl_vars['_oSC']->value['category'] == 'fanpage') {
$_smarty_tpl->_assignInScope('_sc_icon', 'bxl-facebook-square');
$_smarty_tpl->_assignInScope('_sc_color', 'text-primary');
$_smarty_tpl->_assignInScope('_sc_label', 'Fanpage');
}?>
											<div class="col-12 col-sm-6">
												<a href="<?php echo $_smarty_tpl->tpl_vars['_oSC']->value['link'];?>
" target="_blank" class="d-flex align-items-center gap-2 p-2 border rounded text-decoration-none">
													<i class="bx <?php echo $_smarty_tpl->tpl_vars['_sc_icon']->value;?>
 fs-3 flex-shrink-0 <?php echo $_smarty_tpl->tpl_vars['_sc_color']->value;?>
"></i>
													<div class="flex-grow-1 overflow-hidden">
														<div class="fw-medium small"><?php if (!empty($_smarty_tpl->tpl_vars['_oSC']->value['title'])) {
echo $_smarty_tpl->tpl_vars['_oSC']->value['title'];
} else {
echo $_smarty_tpl->tpl_vars['_sc_label']->value;
}?></div>
														<div class="text-muted text-truncate" style="font-size:11px"><?php echo $_smarty_tpl->tpl_vars['_oSC']->value['link'];?>
</div>
													</div>
												</a>
											</div>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											</div>
										<?php } else { ?>
											<p class="text-muted py-1 mb-0 small">Chưa có kênh nào</p>
										<?php }?>
										</div>
									</div>
								</div>
							</div>
							<div class="dashboard-panel-item dashboard-panel-item--full">
								<div class="panel no-shadow border-0 panel-default">
									<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
									<div class="panel-heading">
										<h3 class="panel-title">Ghi chú</h3>
									</div>
									<div class="panel-body no-easyui">
										<form id="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" class="frmIssue p-3 rounded-2 bg-lighter mb-3" action="">
											<textarea class="form-control" name="content" rows="2" placeholder="Nhập ghi chú"></textarea>
											<div class="clearfix mt-2">
												<button type="button" tp="_create" class="btn btn-outline-primary" 
												for_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" clsTable="Profile" note_id="" onClick="$Core.helper.save_notes(this,event)">Thêm</button>
											</div>
										</form>
										<div class="holder_notes_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
">
											<div class="loader p-5 text-center">
												Loading...
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="dashboard-panel-item dashboard-panel-item--full">
								<div class="panel panel-default no-shadow border-0">
									<?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
									<div class="panel-heading">
										<div class="d-flex align-items-center justify-content-between">
											<h3 class="panel-title">Hồ sơ năng lực</h3>
											<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="$Core.member.toggleForm(this,event)" class="btn btn-sm btn-outline-default"><i class="fa fa-plus"></i> Thêm mới</a>
										</div>
									</div>
									<div class="panel-body no-easyui">
										<form class="frmIssue d-none p-3 rounded-2 bg-lighter mb-2" id="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" 
											enctype="multipart/form-data" action="#" name="from-file">
											<textarea class="form-control" name="description" rows="2" placeholder="Nhập nội dung"></textarea>
											<div class="form-group">
												<div class="divider my-1 text-start">
													<div class="divider-text">Chọn file đính kèm</div>
												</div>
												<input type="file" class="form-control" name="attachment" />
											</div>
											<div class="clearfix mt-2">
												<input type="hidden" name="submit" value="Insert" />
												<button type="button" class="btn btn-outline-primary" 
												for_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" clsTable="Profile" onClick="$Core.member.ms_save_file(this,event)">Thêm</button>
											</div>
										</form>
										<div class="holder_files_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
">
											<div class="loader p-5 text-center">
												Loading...
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-heading">
										<div class="d-flex align-items-center justify-content-between">
											<h3 class="panel-title">Tài khoản ngân hàng</h3>
											<a href="javascript:void(0);" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" onClick="$Core.member.open_bank(this, event)" 
										profile_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
" class="btn btn-sm btn-outline-default"><i class="fa fa-plus"></i> Thêm mới</a>
										</div>
									</div>
									<div class="panel-body no-easyui">
										<div class="widget-content holder_bank__<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
">
											<div class="loader text-center p-5">
												<span class="text-muted">Loading...</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="billing_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_billing_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
 no-easyui">
										<div class="loader p-5 text-center">
											<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/ripple-loading.svg" />
											<p>Đang tải...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="level_logs_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_level_logs_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
 no-easyui">
										<div class="loader p-5 text-center">
											<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/ripple-loading.svg" />
											<p>Đang tải...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="logs_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_login_logs_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
 no-easyui">
										<div class="loader p-5 text-center">
											<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/ripple-loading.svg" />
											<p>Đang tải...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="action_logs_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_login_logs_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
 no-easyui">
										<h3 class="text-fs-16">Lịch sử thay đổi</h3>
										<?php if (!empty($_smarty_tpl->tpl_vars['action_logs']->value)) {?>
										<ul class="logs">
											<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['action_logs']->value, '_oLog');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oLog']->value) {
?>
												<?php if (!empty($_smarty_tpl->tpl_vars['_oLog']->value['content'])) {?>
												<li><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['_oLog']->value['reg_date'],true);?>
 : <?php echo $_smarty_tpl->tpl_vars['_oLog']->value['content'];?>
</li>
												<?php }?>
											<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										<ul>
										<?php } else { ?>
										<div class="p-4 text-center">
											<div class="mb-2">
												<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/listing-empty.svg" class="w-px-75" />
											</div>
											<p class="text-muted mt-2">Chưa có lịch sử thực hiện nào</p>
										</div>
										<?php }?>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="view_stock_logs_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_stock_logs_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
 no-easyui">
										<div class="loader p-5 text-center">
											<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/ripple-loading.svg" />
											<p>Đang tải...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="view_report_share_<?php echo $_smarty_tpl->tpl_vars['uid']->value;?>
" role="tabpanel">
							<div class="dashboard-panel-item dashboard-panel-item--full mb-0">
								<div class="panel border-0 no-shadow panel-default mb-0">
									<div class="panel-body holder_report_share_<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
 no-easyui">
										<div class="loader p-5 text-center">
											<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/ripple-loading.svg" />
											<p>Đang tải...</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End -->
					</div>
				</div>
				<div class="col-12 col-md-4">
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel no-shadow border-0 panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Thông tin chi tiết</h3>
							</div>
							<div class="panel-body no-easyui">
								<div class="d-flex gap-2 align-items-center">
									<div class="gbox flex-fill rounded-2 border p-3">
										<p class="text-muted">Giao dịch</p>
										<strong class="text-main"><?php echo $_smarty_tpl->tpl_vars['total_billings']->value;?>
 GD</strong>
									</div>
									<div class="gbox flex-fill rounded-2 border p-3">
										<p class="text-muted">Doanh số</p>
										<strong class="text-primary"><?php echo $_smarty_tpl->tpl_vars['total_revenues']->value;?>
 <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>
</strong>
									</div>
									<div class="gbox flex-fill rounded-2 border p-3 cursor-pointer" onclick="$Core.global.open_Lpoint(this, event)" staff_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
">
										<p class="text-muted">Điểm Loyalty</p>
										<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/point.png" width="12px">
										<strong class="text-warning"><?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['total_Lpoint'];?>
</strong>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel no-shadow border-0 panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Thông tin chi tiết</h3>
							</div>
							<div class="panel-body no-easyui">
								<table class="clientssummarystats" width="100%">
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Họ và tên</td>
										<td class="InputCRMHandler" colspan="3">
											<?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['full_name'];?>

											<?php if (($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') && !$_smarty_tpl->tpl_vars['clsISO']->value->checkSale()) {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'full_name', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="full_name" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Điện thoại</td>
										<td  class="InputCRMHandler"colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['phone'])) {?>
												<a href="tel:<?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['phone'];?>
"><?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['phone'];?>
</a>
											<?php } else { ?>
												-- 
											<?php }?>
											<?php if (($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') && !$_smarty_tpl->tpl_vars['clsISO']->value->checkSale()) {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'phone', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="phone" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Email</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['email'])) {?>
												<a href="mailto:<?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['email'];?>
"><?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['email'];?>
</a>
											<?php } else { ?>
												-- 
											<?php }?>
											<?php if (($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') && !$_smarty_tpl->tpl_vars['clsISO']->value->checkSale()) {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'email', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="email" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Địa chỉ</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['address'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['address'];?>

											<?php } else { ?>
												-- 
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'address', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="address" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Giới tính</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if ($_smarty_tpl->tpl_vars['dbProfile']->value['gender_id'] == '1') {?>
												Nam
											<?php } elseif ($_smarty_tpl->tpl_vars['dbProfile']->value['gender_id'] == '2') {?>
												Nữ
											<?php } else { ?>
												---
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'gender_id', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="gender_id" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Ngày sinh</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['birthday'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['dbProfile']->value['birthday']);?>

											<?php } else { ?>
												-- 
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'birthday', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="birthday" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">CCID</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['CCID'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['dbProfile']->value['CCID'];?>

											<?php } else { ?>
												-- 
											<?php }?>
											<?php if (($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') && !$_smarty_tpl->tpl_vars['clsISO']->value->checkSale()) {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'CCID', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="CCID" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Ngày vào làm</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['start_date'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['dbProfile']->value['start_date']);?>

											<?php } else { ?>
												-- 
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'start_date', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="start_date" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Ngày ký HĐ</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['contract_date'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['dbProfile']->value['contract_date']);?>

											<?php } else { ?>
												-- 
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'contract_date', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="contract_date" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Phòng ban</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['department_id'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['dbProfile']->value['department_id']);?>

											<?php } else { ?>
												--
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1' && $_smarty_tpl->tpl_vars['permis_edit']->value != 1) {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'department_id', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="department_id" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Vai trò</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['role_id'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['dbProfile']->value['role_id']);?>

											<?php } else { ?>
												--
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1' && $_smarty_tpl->tpl_vars['permis_edit']->value != 1) {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'role_id', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="role_id" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Vai trò phụ <span class="text-muted fw-normal" style="font-size:11px">(kiêm nhiệm)</span></td>
										<td class="InputCRMHandler" colspan="3">
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1' && $_smarty_tpl->tpl_vars['permis_edit']->value != 1) {?>
											<div class="secondary-role-box">
												<div class="secondary-display">
													<span class="secondary-current-text"><?php if ($_smarty_tpl->tpl_vars['sec_dep_id']->value > 0) {
echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['sec_dep_id']->value);?>
 – <?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['sec_role_id']->value);
} else { ?>--<?php }?></span>
													<a class="editInlineField ml-1" onclick="$Core.member.edit_secondary(this, event)"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a>
												</div>
												<div class="secondary-editor d-none">
													<div class="d-flex input-group inline-editor-container align-items-center gap-1">
														<select name="secondary_department_id" class="form-control form-select form-control-sm w-auto" onchange="$Core.member.load_secondary_role(this, event)">
															<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getSelectByPropertyTypeTitle('_DEPARTMENT',$_smarty_tpl->tpl_vars['sec_dep_id']->value,'— Không kiêm nhiệm —');?>

														</select>
														<select name="secondary_role_id" class="form-control form-select form-control-sm w-auto">
															<?php echo $_smarty_tpl->tpl_vars['html_role_options_secondary']->value;?>

														</select>
														<button type="button" class="btn px-2 btm-sm btn-outline-success" onclick="$Core.member.save_secondary(this, event)" data-pid="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-check');?>
</button>
														<button type="button" class="btn px-2 btm-sm btn-outline-danger" onclick="$Core.member.cancel_secondary(this, event)"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-x');?>
</button>
													</div>
												</div>
											</div>
											<?php } else { ?>
												<?php if ($_smarty_tpl->tpl_vars['sec_dep_id']->value > 0) {
echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['sec_dep_id']->value);?>
 – <?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['sec_role_id']->value);
} else { ?>--<?php }?>
											<?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Cấp bậc</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['level_id'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['dbProfile']->value['level_id']);?>

											<?php } else { ?>
												--
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'level_id', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="level_id" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Tình trạng</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['dbProfile']->value['status_id'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['dbProfile']->value['status_id']);?>

											<?php } else { ?>
												--
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'status_id', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="level_id" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Zalo ID</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['zaloId'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['zaloId'];?>

											<?php } else { ?>
												--
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'zaloId', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="level_id" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<!-- <tr class="trPotentialEdit">
										<td class="text-right text-nowrap" width="25%">Twitter</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['twitter'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['twitter'];?>

											<?php } else { ?>
												--
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'twitter', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="twitter" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Facebook</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['facebook'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['facebook'];?>

											<?php } else { ?>
												--
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'facebook', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="facebook" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Linkedin</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['linkedin'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['linkedin'];?>

											<?php } else { ?>
												--
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'linkedin', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="linkedin" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Instagram</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['instagram'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['instagram'];?>

											<?php } else { ?>
												--
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permis_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'instagram', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="instagram" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr> -->
								</table>
							</div>
						</div>
					</div>
					<div class="dashboard-panel-item dashboard-panel-item--full">
						<div class="panel border-0 no-shadow panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Chứng chỉ môi giới</h3>
							</div>
							<div class="panel-body no-easyui">
								<table class="clientssummarystats" width="100%">
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap" width="25%">Số chứng chỉ</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['issue_number'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['issue_number'];?>

											<?php } else { ?>
												-- 
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'issue_number', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="issue_number" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Ngày cấp</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['issue_date'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['more_information']->value['issue_date']);?>

											<?php } else { ?>
												-- 
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'issue_date', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="issue_date" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
									<tr class="trPotentialEdit">
										<td class="text-right text-nowrap">Nơi cấp</td>
										<td class="InputCRMHandler" colspan="3">
											<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['issue_location'])) {?>
												<?php echo $_smarty_tpl->tpl_vars['more_information']->value['issue_location'];?>

											<?php } else { ?>
												-- 
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['permiss_edit']->value == '1' || $_smarty_tpl->tpl_vars['permiss_edit_full']->value == '1') {?><a class="editInlineField" onClick="$Core.member.editInlineField(this,{p_field:'issue_location', 'p_id':<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
})" p_field="issue_location" p_id="<?php echo $_smarty_tpl->tpl_vars['_profile_id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-pencil');?>
</a><?php }?>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div></div>
			</div>
		</div>
	</div>
</div>

<style type="text/css">
	.inline-editor-container {
		min-width: 150px;
		max-width: 220px;
		width: 260px;
	}
	.dashboard-panel-item,
	.dashboard-panel-item .panel{
		margin-bottom:10px !important
	}
</style>
<?php }
}
