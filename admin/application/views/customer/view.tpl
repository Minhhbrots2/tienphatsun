<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('CustomerPage')}</span>
				</a>
			</div>
		</div>
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">{$clsClassTable->getName($pvalTable)}</h1>
			</div>            
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="ui-layout">
	<div class="row">
		<div class="col-md-8">
			<div class="box light">
				<div class="box-title no-border-bottom">
					<div class="vcard">
						<div class="vcard-media">
							<img src="https://secure.gravatar.com/avatar/15d23403fb836f2b506f4f3ad2c03356.jpg?s=80&d=blank" />
						</div>
						<div class="vcard-body">
							<h2>{$clsClassTable->getName($pvalTable)}</h2>
							<p>{$clsClassTable->getAddress($pvalTable)}</p>
							<a href="javascript:void(0);" class="SiteClickPublic" clsTable="Profile" pkey="profile_id" sourse_id="{$pvalTable}" rel="{$clsClassTable->getOneField('is_online',$pvalTable)}" title="{$core->get_Lang('Click to change status')}">
								{if $clsClassTable->getOneField('is_online',$pvalTable) eq '1'}
								<i class="fa fa-check-circle green"></i>
								{else}
								<i class="fa fa-minus-circle red"></i> 
								{/if}
								<span>{$core->get_Lang('Kích hoạt tài khoản')}</span>
							</a>
						</div>
					</div>
				</div>
				<div class="box-body">
					<div class="form-group mt20">
						<label>Ghi chú</label>
						<textarea class="form-control NoteContent" rows="2" placeholder="Nhập ghi chú"></textarea>
						<div class="clearfix mt10">
							<button type="button" class="btn btn-success addNote" note_id="">
								<i class="icon-ok icon-white"></i> Thêm ghi chú
							</button>
						</div>
					</div>
				</div>
				<div class="box-title border-top no-border-bottom no-margin" style="background:#F9F9F9">
					<div class="col-md-4 text-center">
						<span class="note">Số đơn hàng</span>
						<h3 class="h3">{$totalBooking}</h3>
					</div>
					<div class="col-md-4 text-center">
						<span class="note">Số đơn hàng</span>
						<h3 class="h3">{$clsISO->priceFormat($totalRevenue)} {$clsISO->getRate()}</h3>
					</div>
					<div class="col-md-4 text-center">
						<span class="note">Chi tiêu trung bình</span>
						<h3 class="h3">{$clsISO->priceFormat($totalBanlace)} {$clsISO->getRate()}</h3>
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
					<div class="caption">
						<span class="bold mr10">Đơn hàng của bạn </span>
						<a class="btn btn-xs btn-danger"><i class="fa fa-plus"></i> Thêm mới</a>
					</div>
				</div>
				<div class="box-body">
					<table class="table table-striped table-vertical" cellpadding="0" cellspacing="0" width="100%">
						<thead><tr>
							<th>Mã đơn hàng.</th>
							<th class="text-right">Tổng tiền.</th>
							<th class="text-right">Thời gian.</th>
							<th class="text-center">Tình trạng.</th>
						</tr></thead>
						<tbody>
							{if $lstOrderIn[0].order_id ne ''}
								{section name=i loop=$lstOrderIn}
								<tr>
									<td><a href="{$PCMS_URL}/index.php?mod=booking&act=view&order_id={$lstOrderIn[i].order_id}">{$lstOrderIn[i].vpc_OrderNo}</a></td>
									<td class="text-right">{$clsISO->priceFormat($lstOrderIn[i].vpc_total_money)} {$clsISO->getRate()}</td>
									<td class="text-right">{$clsISO->convertTimeToText($lstOrderIn[i].reg_date)}</td>
									<td class="text-center">{$clsOrder->getStatus($lstOrderIn[i].order_id)}</td>
								</tr>
								{/section}
							{else}
								<tr>
									<td class="text-center" colspan="4">
										Khách hàng chưa có đơn đặt hàng
									</td>
								</tr>
							{/if}
						</tbody>
					</table>
				</div>
			</div>
			<div class="box light">
				<div class="box-title">
					<div class="caption">
						<span class="bold mr10">Thông báo của bạn</span>
						<a class="btn btn-xs btn-danger"><i class="fa fa-plus"></i> Thêm mới</a>
					</div>
				</div>
				<div class="box-body">
					<table class="table table-striped table-vertical" cellpadding="0" cellspacing="0" width="100%">
						<thead><tr>
							<th class="text-left">{$core->get_Lang('Code')}.</th>
							<th class="text-left" width="40%">{$core->get_Lang('Content')}.</th>
							<th class="text-right">Thời gian.</th>
							<th class="text-center">Tình trạng.</th>
						</tr></thead>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="box light">
				<div class="box-title no-border-bottom">
					<div class="caption"><span class="bold">Liên hệ</span></div>
					<a class="pull-right" href="{$PCMS_URL}/index.php?mod={$mod}&act=edit&profile_id={$pvalTable}">Sửa</a>
				</div>
				<div class="box-body">
					{$clsClassTable->getEmail($pvalTable)}<br />
					Không đồng ý nhận tiếp thị<br />
					{if $oneItem.oauth_provider ne '_order'}Đã có tài khoản{/if}
				</div>
				<div class="box-title no-border-bottom" style="border-top:1px solid #DDD">
					<div class="caption"><span class="bold">Sổ địa chỉ</span></div>
					<a class="pull-right" href="javascript:void(0);" profile_id="{$pvalTable}" address_id="" onClick="open_address_book(this)">
						+ {$core->get_Lang('Add_Address')}
					</a>
				</div>
				<div class="box-body" id="holder_address_book_{$pvalTable}">
					Loading...
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.vcard-media{ width:80px; height:80px; float:left; margin:0 15px 0 0;}
	.vcard-media img{overflow:hidden; border-radius:50%; -webkit-border-radius:50%; -moz-border-radius:50% }
	.vcard-body{ vertical-align:top; margin:0 0 0 95px; padding:0px 0 0;}
	.vcard-body > h2{ font-size:18px;}
	.note{ color:#637381; font-size:13px;}
	.h3{ margin-top:10px; font-size:18px;}
</style>
{/literal}
<script type="text/javascript">
	var profile_id = '{$pvalTable}';
</script>
{literal}
<script type="text/javascript">
	$(function(){
		loadListNote({}); 
		/* Events */
		$_document.on('click', '.addNote,.btnsave_note', function(){
			var $_this = $(this),
				note_id = $_this.attr('note_id');
			
			var action = '_add',
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
			vietiso_loading(1);
			$.ajax({
				type: 'POST',
				url: path_ajax_script+"/index.php?mod="+mod+"&act=ajSaveNote",
				data: {'note_id':note_id,'profile_id':profile_id,'content':NoteContent},
				dataType: 'html',
				success: function(html){
					vietiso_loading(0);
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
					vietiso_loading(1);
					$.ajax({
						type: 'POST',
						url: path_ajax_script+"/index.php?mod="+mod+"&act=ajSaveNote&action=_delete",
						data: {'note_id':note_id,'profile_id':profile_id},
						dataType: 'html',
						success: function(html){
							vietiso_loading(0);
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
		
		vietiso_loading(1);
		$_adata['profile_id'] = profile_id;
		$.post(path_ajax_script+"/index.php?mod="+mod+"&act=ajLoadListNote", $_adata, function(html){
			vietiso_loading(0);
			$('.holderNoteContainer').html(html);
		});
	}
</script>
{/literal}