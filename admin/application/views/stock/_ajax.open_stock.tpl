<div class="modal-dialog modal-ipad">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePgae}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<ul class="nav nav-tabs nav-tabs-bordered" id="myTab" role="tablist">
					<li class="nav-item active">
						<a href="#properties" class="nav-link" data-toggle="tab" role="tab">
							<span>{$core->makeIcon('cube', 'Thuộc tính')}</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#content" class="nav-link" data-toggle="tab" role="tab">
							<span>{$core->makeIcon('plug', 'Phiếu tính giá')}</span>
						</a>
					</li>
					<li class="nav-item">
						<a href="#history" class="nav-link" data-toggle="tab" role="tab">
							<span>{$core->makeIcon('history', 'Lịch sử')}</span>
						</a>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<div id="properties" class="tab-pane fade py-3 active in" role="tabpanel">
						<table class="table table-striped vertical-center mb-2">
							<thead><tr>
								<th class="text-left" width="25%">Tiêu đề</th>
								<th class="text-left">Nội dung</th>
								<th width="40px"></th>
							</tr></thead>
							<tbody class="holder_property_{$stock_id} no_group">
								{if !empty($list_property)}
									{foreach from=$list_property key=uid item=_oT}
									<tr class="tr_stock_line_{$uid} tr_stock_line_{$stock_id}">
										<td class="text-left">
											<input type="text" placeholder="Nhập tiêu đề" maxlength="255" name="properties[{$uid}][title]" value="{$_oT.title}" class="form-control" />
										</td>
										<td class="text-left">
											<input type="text" placeholder="Nhập giá trị" maxlength="255" name="properties[{$uid}][content]" value="{$_oT.content}" class="form-control" />
										</td>
										<td class="text-center">
											<button type="button" onClick="$Core.stock.delete_stock_line(this, event)" stock_id="{$stock_id}" uid="{$uid}" class="btn py-px-9 btn-default">{$core->makeIcon('trash')}</button>
										</td>
									</tr>
									{/foreach}
								{else}
									{assign var = uid value = $clsISO->getUniqid()}
									<tr class="tr_stock_line_{$uid} tr_stock_line_{$stock_id}">
										<td class="text-left">
											<input type="text" placeholder="Nhập tiêu đề" maxlength="255" name="properties[{$uid}][title]" class="form-control" />
										</td>
										<td class="text-left">
											<input type="text" placeholder="Nhập giá trị" maxlength="255" name="properties[{$uid}][content]" class="form-control" />
										</td>
										<td class="text-center"></td>
									</tr>
								{/if}
							</tbody>
						</table>
						<div class="d-flex">
							<button type="button" onClick="$Core.stock.add_stock_line(this, event)" stock_id="{$stock_id}" 
							class="btn btn-default">+ Thêm dòng</button>
						</div>
						<div class="modal-footer px-0">
							<div class="w-100 d-flex align-items-center justify-content-between">
								<div class="d-flex align-items-center ml-half">
									{foreach from=$list_website item = _oW}
									<div class="checkbox mt-0 mr-3">
										<input type="checkbox"{if $clsISO->checkItemInArray($_oW, $show_website_arrs)} checked{/if} 
										name="show_website[]" value="{$_oW}" stock_id="{$stock_id}" class="cb_stock_site" onchange="$Core.stock.toggle_site(this, event)" />
										<label>{$_oW}</label>
									</div>
									{/foreach}
									<div class="checkbox mt-0 mr-3">
										<input type="checkbox" name="markup_price"{if isset($more_information.markup_price) && $more_information.markup_price eq '1'} checked{/if} value="1" />
										<label>Có ++ hoa hồng</label>
									</div>
								</div>
								<div class="group_buttons">
									<button type="button" onClick="pop_save_stock(this, event)" stock_id="{$stock_id}" 
									class="btn btn-success"><span>Lưu lại</span></button>
									<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
										<span>{$core->get_Lang('Close')}</span>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div id="content" class="tab-pane fade py-3" role="tabpanel">
						<table class="table mb-2">
							<thead><tr>
								<th class="text-left" width="25%">Ngày</th>
								<th class="text-left">Số phiên bản</th>
								<th width="90px"></th>
							</tr></thead>
							<tbody class="holder_stock_price_{$stock_id}">
								<tr>
									<td colspan="3">Loading...</td>
								</tr>
							</tbody>
						</table>
						<div class="modal-footer px-0">
							<button type="button" onClick="open_stock_price(this, event)" stock_id="{$stock_id}" class="btn mr-half btn-default">+ Phiếu tính giá</button>
						</div>
					</div>
					<div id="history" class="tab-pane fade py-3" role="tabpanel">
						<table class="table" width="100%">
							<thead><tr>
								<th width="25%">Ngày thay đổi</th>
								<th>Người thay đổi</th>
								<th>Nội dung</th>
							</tr></thead>
							{if !empty($list_logs)}
								{foreach from=$list_logs item = _oLog}
								<tr>
									<td>{$clsISO->convertTimeToText($_oLog.reg_date, true)}</td>
									<td>{$_oLog.full_name}</td>
									<td>{$clsStock->getContentLog($_oLog)}</td>
								</tr>
								{/foreach}
							{else}
								<tr>
									<td class="text-center" colspan="4">
										<img src="{$smarty.const._IMG_NODOCUMENT}" width="40px" />
										<p>Không có bất kỳ lịch sử giá nào !</p>
									</td>
								</tr>
							{/if}
						</table>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
{literal}
<style type="text/css">
	.py-px-9{
		padding-top:9px;
		padding-bottom:9px;
	}
	.modal-footer.px-0{
		width:calc(100% + 30px);
		margin-left:-15px;
		margin-bottom:-15px;
	}
</style>
{/literal}