<?php
/* Smarty version 3.1.33, created on 2026-07-30 13:50:00
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/profile/view.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6af418177e31_72852607',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b417c9cb3cf72d889cfe06c7a760300b85681ea3' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/profile/view.tpl',
      1 => 1784691718,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6af418177e31_72852607 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="ui-title-bar-container ui-title-bar-container--full-width">

	<div class="ui-title-bar">

		<div class="ui-title-bar__navigation">

			<div class="ui-breadcrumbs">

				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" class="btn btn-default ui-breadcrumb">

					<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('angle-left mr-5');?>


					<span class="ui-breadcrumb__item">Nhân viên</span>

				</a>

			</div>

		</div>

	</div>

</div>

<div class="clearfix"></div>

<div class="ui-layout ui-layout--full-width">

	<div class="row">

		<div class="col-md-8">

			<div class="box light">

				<div class="box-title no-border-bottom">

					<div class="vcard d-flex">

						<div class="vcard-media">

							<img class="avatar" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.svg'" src="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['avatar'];?>
" />

						</div>

						<div class="vcard-body">

							<h2 class="mt-0"><?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getFullName($_smarty_tpl->tpl_vars['pvalTable']->value);?>
</h2>

							<p><?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getAddress($_smarty_tpl->tpl_vars['pvalTable']->value);?>
</p>

							<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Profile" pkey="profile_id" sourse_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" rel="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['is_active'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Click to change status');?>
">

								<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['is_active'] == '1') {?>

								<i class="fa fa-check-circle green"></i>

								<span> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Ngừng kích hoạt tài khoản');?>
</span>

								<?php } else { ?>

								<i class="fa fa-minus-circle red"></i> 

								<span> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Kích hoạt tài khoản');?>
</span>

								<?php }?>

							</a>

							<form class="mt-3" target="_blank" action="<?php echo @constant('DOMAIN_URL');?>
/dang-nhap.html" method="post">

								<input type="hidden" name="user_email" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['user_name'];?>
" />

								<input type="hidden" name="user_pass" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['user_rpass'];?>
" />

								<button id="btnLogin" name="submit" value="signin" class="btn btn-success"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('sign-in');?>
 Đăng nhập</button>

							</form>

						</div>

					</div>

				</div>

				<div class="box-body">

					<div class="form-group mt20">

						<label>Ghi chú</label>

						<textarea class="form-control NoteContent" rows="2" placeholder="Nhập ghi chú"></textarea>

						<div class="clearfix mt-2">

							<button type="button" class="btn btn-success addNote" note_id="">

								<i class="icon-ok icon-white"></i> Thêm ghi chú

							</button>

						</div>

					</div>

				</div>

				<div class="box-title border-top no-border-bottom no-margin" style="background:#F9F9F9">

					<div class="col-md-4 text-center">

						<span class="note">Doanh thu</span>

						<h3 class="h3"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['oneItem']->value['money']);?>
 <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>
</h3>

					</div>

					<div class="col-md-4 text-center">

						<span class="note">Số giao dịch</span>

						<h3 class="h3"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['totalRevenue']->value);?>
 <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>
</h3>

					</div>

					<div class="col-md-4 text-center">

						<span class="note">Doanh thu trung bình</span>

						<h3 class="h3"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->formatNumberToEasyRead($_smarty_tpl->tpl_vars['totalBanlace']->value);?>
 <?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getRate();?>
</h3>

					</div>

				</div>

			</div>

			<div class="box light">

				<div class="box-title">

					<div class="caption">

						<span class="bold"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Notes');?>
</span>

					</div>

				</div>

				<div class="box-body">

					<div class="mg-wrapper">

						<div class="holderNoteContainer"></div>

					</div>

				</div>

			</div>

			<div class="box light">

				<div class="box-title">

					<div class="d-flex justify-content-between">

						<div class="caption">

							<span class="bold mr10"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Transaction log');?>
 </span>

							<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=transaction&profile_id=<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" target="_blank" class="btn btn-xs btn-primary text-white"><i class="fa fa-external-link"></i> Xem tất cả</a>

						</div>

					</div>

				</div>

				<div class="box-body">

					<table class="table table-striped table-vertical" cellpadding="0" cellspacing="0" width="100%">

						<thead><tr>

							<th class="text-left" width="5%">No.</th>

							<th class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Type');?>
</th>

							<th class="text-right" width="150px"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Money');?>
</th>

							<th class="text-center"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Status');?>
</th>

							<th class="text-right" width="200px"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Date');?>
</th>

							<th class="text-left" width="80px"></th>

						</tr></thead>

						<tr>

							<td class="text-center" colspan="6">

								<div class="empty-result text-center">

									<img src="<?php echo @constant('_IMG_NODOCUMENT');?>
" width="40px" />

									<p>Không có dữ liệu</p>

								</div>

							</td>

						</tr>

					</table>

				</div>

			</div>

			<div class="box light">

				<div class="box-title d-flex align-items-center justify-content-between">

					<div class="caption ">

						<span class="bold">Thông báo của bạn</span>

					</div>

					<a class="btn btn-xs btn-danger"><i class="fa fa-plus"></i> Thêm mới</a>

				</div>

				<div class="box-body">

					<table class="table table-striped table-vertical" cellpadding="0" cellspacing="0" width="100%">

						<thead><tr>

							<th class="text-left"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
.</th>

							<th class="text-left" width="40%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Content');?>
.</th>

							<th class="text-right">Thời gian.</th>

							<th class="text-center">Tình trạng.</th>

						</tr></thead>

						<tr>

							<td class="text-center" colspan="4">

								<div class="empty-result text-center">

									<img src="<?php echo @constant('_IMG_NODOCUMENT');?>
" width="40px" />

									<p>Không có dữ liệu</p>

								</div>

							</td>

						</tr>

					</table>

				</div>

			</div>

			<div class="box light">

				<div class="box-title">

					<div class="d-flex justify-content-between">

						<div class="caption">

							<span class="bold mr10">Lịch sử sửa đổi thông tin</span>

						</div>

					</div>

				</div>				

				<div class="box-body">

					<table border="0" class="table table-striped mb-4" width="100%">

						<thead><tr>

							<th width="150px" class="align-left">Thời gian</th>

							<th class="">Nội dung cũ</th>

							<th class="">Nội dung thay đổi</th>

						</tr> </thead>

						<tbody class="holder_logs_update">

							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['logs_update']->value, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>

								<?php $_smarty_tpl->_assignInScope('olds', $_smarty_tpl->tpl_vars['item']->value['old']);?>

								<?php $_smarty_tpl->_assignInScope('updates', $_smarty_tpl->tpl_vars['item']->value['update']);?>

								<tr>

									<td>

										<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToTextFormat($_smarty_tpl->tpl_vars['item']->value['time'],"d/m/Y H:i");?>


									</td>

									<td>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['olds']->value, 'old', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['old']->value) {
?>

											<p><span class="fw-semibold"><?php echo $_smarty_tpl->tpl_vars['arr_txt_key']->value[$_smarty_tpl->tpl_vars['k']->value];?>
</span>: <span class="text-muted"><?php if ($_smarty_tpl->tpl_vars['k']->value == 'gender_id') {
echo $_smarty_tpl->tpl_vars['arr_gender_key']->value[$_smarty_tpl->tpl_vars['old']->value];
} else {
echo $_smarty_tpl->tpl_vars['old']->value;
}?></span></p>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									</td>

									<td>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['updates']->value, 'update', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['update']->value) {
?>

											<p><span class="fw-semibold"><?php echo $_smarty_tpl->tpl_vars['arr_txt_key']->value[$_smarty_tpl->tpl_vars['k']->value];?>
</span>: <span class="text-muted"><?php if ($_smarty_tpl->tpl_vars['k']->value == 'gender_id') {
echo $_smarty_tpl->tpl_vars['arr_gender_key']->value[$_smarty_tpl->tpl_vars['update']->value];
} else {
echo $_smarty_tpl->tpl_vars['update']->value;
}?></span></p>

										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									</td>

								</tr>

							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

						</tbody>

					</table>

				</div>

			</div>

			<div class="box light">

				<div class="box-title">

					<div class="d-flex justify-content-between">

						<div class="caption">

							<span class="bold mr10">Lịch sử điểm</span>

						</div>

					</div>

				</div>				

				<div class="box-body">

					<table border="0" class="table table-striped mb-4" width="100%">

						<thead><tr>

							<th width="150px" class="align-left">Thời gian</th>

							<th width="10%" class="align-center border-end">Điểm</th>

							<th class="">Nội dung</th>

						</tr> </thead>

						<tbody class="holder_logs_point">

							<tr>

								<td colspan="5">

									<div class="empty-result text-center">

										<img src="<?php echo @constant('_IMG_NODOCUMENT');?>
" width="40px" />

										<p>Không có dữ liệu</p>

									</div>

								</td>

							</tr>

						</tbody>

					</table>

				</div>

			</div>

			<div class="box light">

				<div class="box-title">

					<div class="d-flex justify-content-between">

						<div class="caption">

							<span class="bold mr10">Danh sách tra cứu</span>

						</div>

					</div>

				</div>				

				<div class="box-body">

					<table border="0" class="table table-striped mb-4" width="100%">

						<thead><tr>

							<th width="100px" class="align-center">H.Động</th>

							<th width="150px" class="align-center border-end">Thời gian</th>

							<th class="">Nội dung</th>

						</tr> </thead>

						<tbody class="holder_logs">

							<tr>

								<td colspan="5">

									<div class="empty-result text-center">

										<img src="<?php echo @constant('_IMG_NODOCUMENT');?>
" width="40px" />

										<p>Không có dữ liệu</p>

									</div>

								</td>

							</tr>

						</tbody>

					</table>

					<div id="pager_sale_logs"></div>

				</div>

			</div>

		</div>

		<div class="col-md-4">

			<div class="box light">

				<div class="box-title no-border-bottom">

					<div class="caption"><span class="bold">Liên hệ</span></div>

					<a class="pull-right" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&<?php echo $_smarty_tpl->tpl_vars['pkeyTable']->value;?>
=<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
&profile_type=<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['profile_type'];?>
">Sửa</a>

				</div>

				<div class="box-body">

					<?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getEmail($_smarty_tpl->tpl_vars['pvalTable']->value);?>
<br />

					Không đồng ý nhận tiếp thị<br />

					<?php if ($_smarty_tpl->tpl_vars['oneItem']->value['oauth_provider'] != '_order') {?>

						Đã có tài khoản

					<?php }?>

				</div>

				<div class="box-title d-flex justify-content-between align-items-center no-border-bottom" style="border-top:1px solid #DDD">

					<div class="caption"><span class="bold">Tài khoản ngân hàng</span></div>

					<a class="pull-right" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&<?php echo $_smarty_tpl->tpl_vars['pkeyTable']->value;?>
=<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
">

						+ Thêm

					</a>

				</div>

				<div class="box-body" id="holder_address_book_<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
">

					<?php if (!empty($_smarty_tpl->tpl_vars['banks_info']->value)) {?>

					<ul class="list-banks">

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['banks_info']->value, '_obank', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_obank']->value) {
?>

						<li>

							<p class="mb-1"><?php echo $_smarty_tpl->tpl_vars['_obank']->value['account_person'];?>
 - <?php echo $_smarty_tpl->tpl_vars['_obank']->value['bank_name'];?>
</p>

							<strong><?php echo $_smarty_tpl->tpl_vars['_obank']->value['account_number'];?>
</strong>

						</li>

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					</ul>

					<?php } else { ?>

						<div class="empty-result text-center">

							<img src="<?php echo @constant('_IMG_NODOCUMENT');?>
" width="40px" />

							<p>Không có dữ liệu</p>

						</div>

					<?php }?>

				</div>

			</div>

		</div>

	</div>

</div>

<?php echo '<script'; ?>
 type="text/javascript">

	var profile_id = '<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
';

<?php echo '</script'; ?>
>



<style type="text/css">

	.vcard-media{ width:80px; height:80px; float:left; margin:0 15px 0 0;}

	.vcard-media img{overflow:hidden; border-radius:50%; -webkit-border-radius:50%; -moz-border-radius:50% }

	.vcard-body{ vertical-align:top; margin:0 0 0 65px; padding:0px 0 0;}

	.vcard-body > h2{ font-size:18px;}

	.note{ color:#637381; font-size:13px;}

	.h3{ margin-top:10px; font-size:18px;}

</style>

<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){

		setTimeout(() => {

			loadListNote({}); 

			$Core.member.load_logs({});

			$Core.member.load_logs_point({});

		}, 1000);

		/* Events */

		$_document.on('click', '.addNote,.btnsave_note', function(){

			var $_this = $(this),

				note_id = $_this.attr('note_id'),

				

				action = '_add', 

				holderG = 'NoteContent';

			if($_this.hasClass('btnsave_note')){

				action = '_edit';

				holderG = 'CrmNote_intro_'+note_id;

			}

			var NoteContent = $('.'+holderG).val();

			if($Core.util.isEmpty($.trim(NoteContent))){

				$('.'+holderG).focus();

				alertify.error('Bạn chưa nhập nội dung !');

				return false;

			}

			toggleIndicatior(1);

			$.ajax({

				type: 'POST',

				url: path_ajax_script+"/index.php?mod="+mod+"&act=ajSaveNote",

				data: {'note_id':note_id,'profile_id':profile_id,'content':NoteContent},

				dataType: 'html',

				success: function(html){

					toggleIndicatior(0);

					if(action=='_add'){

						$('.NoteContent').val('');

					}

					loadListNote({});

				}

			});

			return false;

		});

		$_document.on('click', '.btnedit_note,.btncancel_note,.btndelete_note', function(){

			var $_this = $(this),

				note_id = $_this.attr('note_id'),

				$_el = $_this.closest('.timeline-body');

			if($_this.hasClass('btndelete_note')){

				if(confirm('Bạn chắc chắn muốn xóa')){

					toggleIndicatior(1);

					$.ajax({

						type: 'POST',

						url: path_ajax_script+"/index.php?mod="+mod+"&act=ajSaveNote&action=_delete",

						data: {'note_id':note_id,'profile_id':profile_id},

						dataType: 'html',

						success: function(html){

							toggleIndicatior(0);

							loadListNote();

						}

					});

				}

			} else if($_this.hasClass('.btnedit_note')){

				$_el.find('.timeline-content-edit__'+note_id).hide();

				$_el.find('.timeline-body-content__'+note_id).show();

			} else{

				$_el.find('.timeline-body-content__'+note_id).hide();

				$_el.find('.timeline-content-edit__'+note_id).show();

			}

			return false;

		});

	});

	function loadListNote(options){

		var $_adata = options || {};

		toggleIndicatior(1);

		$_adata['profile_id'] = profile_id;

		$.post(path_ajax_script+"/index.php?mod="+mod+"&act=ajLoadListNote", $_adata, function(html){

			toggleIndicatior(0);

			$('.holderNoteContainer').html(html);

		});

	}

<?php echo '</script'; ?>
>

<?php }
}
