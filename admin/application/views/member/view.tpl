<div class="ui-title-bar-container ui-title-bar-container--full-width">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
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
							<img class="avatar" onerror="this.src='{$URL_IMAGES}/no-avatar.svg'" src="{$oneItem.avatar}" />
						</div>
						<div class="vcard-body">
							<h2 class="mt-0">{$clsClassTable->getFullName($pvalTable)}</h2>
							<p>{$clsClassTable->getAddress($pvalTable)}</p>
							<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Member" pkey="profile_id" sourse_id="{$pvalTable}" rel="{$oneItem.is_active}" title="{$core->get_Lang('Click to change status')}">
								{if $oneItem.is_active eq '1'}
								<i class="fa fa-check-circle green"></i>
								<span> {$core->get_Lang('Ngừng kích hoạt tài khoản')}</span>
								{else}
								<i class="fa fa-minus-circle red"></i> 
								<span> {$core->get_Lang('Kích hoạt tài khoản')}</span>
								{/if}
							</a>
							<form class="mt-3" target="_blank" action="{$smarty.const.DOMAIN_URL}/dang-nhap.html" method="post">
								<input type="hidden" name="user_email" value="{$oneItem.user_name}" />
								<input type="hidden" name="user_pass" value="{$oneItem.user_rpass}" />
								<button id="btnLogin" name="submit" value="signin" class="btn btn-success">{$core->makeIcon('sign-in')} Đăng nhập</button>
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
						<h3 class="h3">{$clsISO->formatNumberToEasyRead($oneItem.money)} {$clsISO->getRate()}</h3>
					</div>
					<div class="col-md-4 text-center">
						<span class="note">Số giao dịch</span>
						<h3 class="h3">{$clsISO->formatNumberToEasyRead($totalRevenue)} {$clsISO->getRate()}</h3>
					</div>
					<div class="col-md-4 text-center">
						<span class="note">Doanh thu trung bình</span>
						<h3 class="h3">{$clsISO->formatNumberToEasyRead($totalBanlace)} {$clsISO->getRate()}</h3>
					</div>
				</div>
			</div>
			<div class="box light">
				<div class="box-title">
					<div class="caption">
						<span class="bold">{$core->get_Lang('Notes')}</span>
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
							<span class="bold mr10">{$core->get_Lang('Transaction log')} </span>
							<a href="{$PCMS_URL}/index.php?mod={$mod}&act=transaction&profile_id={$pvalTable}" target="_blank" class="btn btn-xs btn-primary text-white"><i class="fa fa-external-link"></i> Xem tất cả</a>
						</div>
					</div>
				</div>
				<div class="box-body">
					<table class="table table-striped table-vertical" cellpadding="0" cellspacing="0" width="100%">
						<thead><tr>
							<th class="text-left" width="5%">No.</th>
							<th class="text-left">{$core->get_Lang('Type')}</th>
							<th class="text-right" width="150px">{$core->get_Lang('Money')}</th>
							<th class="text-center">{$core->get_Lang('Status')}</th>
							<th class="text-right" width="200px">{$core->get_Lang('Date')}</th>
							<th class="text-left" width="80px"></th>
						</tr></thead>
						<tr>
							<td class="text-center" colspan="6">
								<div class="empty-result text-center">
									<img src="{$smarty.const._IMG_NODOCUMENT}" width="40px" />
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
							<th class="text-left">{$core->get_Lang('Code')}.</th>
							<th class="text-left" width="40%">{$core->get_Lang('Content')}.</th>
							<th class="text-right">Thời gian.</th>
							<th class="text-center">Tình trạng.</th>
						</tr></thead>
						<tr>
							<td class="text-center" colspan="4">
								<div class="empty-result text-center">
									<img src="{$smarty.const._IMG_NODOCUMENT}" width="40px" />
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
							{foreach from=$logs_update item=item}
								{assign var=olds value=$item.old}
								{assign var=updates value=$item.update}
								<tr>
									<td>
										{$clsISO->convertTimeToTextFormat($item.time,"d/m/Y H:i")}
									</td>
									<td>
										{foreach from=$olds key=k item=old}
											<p><span class="fw-semibold">{$arr_txt_key.$k}</span>: <span class="text-muted">{if $k eq 'gender_id'}{$arr_gender_key.$old}{else}{$old}{/if}</span></p>
										{/foreach}
									</td>
									<td>
										{foreach from=$updates key=k item=update}
											<p><span class="fw-semibold">{$arr_txt_key.$k}</span>: <span class="text-muted">{if $k eq 'gender_id'}{$arr_gender_key.$update}{else}{$update}{/if}</span></p>
										{/foreach}
									</td>
								</tr>
							{/foreach}
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
										<img src="{$smarty.const._IMG_NODOCUMENT}" width="40px" />
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
										<img src="{$smarty.const._IMG_NODOCUMENT}" width="40px" />
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
					<a class="pull-right" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&{$pkeyTable}={$pvalTable}">Sửa</a>
				</div>
				<div class="box-body">
					{$clsClassTable->getEmail($pvalTable)}<br />
					Không đồng ý nhận tiếp thị<br />
					{if $oneItem.oauth_provider ne '_order'}
						Đã có tài khoản
					{/if}
				</div>
				<div class="box-title d-flex justify-content-between align-items-center no-border-bottom" style="border-top:1px solid #DDD">
					<div class="caption"><span class="bold">Tài khoản ngân hàng</span></div>
					<a class="pull-right" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&{$pkeyTable}={$pvalTable}">
						+ Thêm
					</a>
				</div>
				<div class="box-body" id="holder_address_book_{$pvalTable}">
					{if !empty($banks_info)}
					<ul class="list-banks">
						{foreach name=i from=$banks_info item = _obank}
						<li>
							<p class="mb-1">{$_obank.account_person} - {$_obank.bank_name}</p>
							<strong>{$_obank.account_number}</strong>
						</li>
						{/foreach}
					</ul>
					{else}
						<div class="empty-result text-center">
							<img src="{$smarty.const._IMG_NODOCUMENT}" width="40px" />
							<p>Không có dữ liệu</p>
						</div>
					{/if}
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var profile_id = '{$pvalTable}';
</script>
{literal}
<style type="text/css">
	.vcard-media{ width:80px; height:80px; float:left; margin:0 15px 0 0;}
	.vcard-media img{overflow:hidden; border-radius:50%; -webkit-border-radius:50%; -moz-border-radius:50% }
	.vcard-body{ vertical-align:top; margin:0 0 0 65px; padding:0px 0 0;}
	.vcard-body > h2{ font-size:18px;}
	.note{ color:#637381; font-size:13px;}
	.h3{ margin-top:10px; font-size:18px;}
</style>
<script type="text/javascript">
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
</script>
{/literal}