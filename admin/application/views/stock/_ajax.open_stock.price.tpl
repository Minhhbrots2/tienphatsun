<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Phiếu tính giá</strong></h3>
		</div>
		<form class="d-none" enctype="multipart/form-data">
			<input id="{$toId}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" 
			class="stock_price_upload_file" charset="UTF-8" name="image" />
		</form>
		<form method="post" action="">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-xs-12 col-md-2 col-form-label">CSBH ngày</label>
					<div class="col-xs-12 col-md-10">
						<input type="text" name="csbh" class="form-control required datepicker"{if !empty($csbh)} value="{$clsISO->convertTimeToText($csbh)}"{/if} placeholder="Ngày CSBH" />
					</div>
				</div>
				<table class="table table-vertical table-bordered mb-2" width="100%">
					<thead><tr>
						<th width="30%" class="text-left">Tên phiên bản</th>
						<th class="text-left">File upload</th>
						<th width="30px"></th>
					</tr></thead>
					{if !empty($list_sheets)}
						{foreach name=i from=$list_sheets key=uid item = _oSH}
						<tr class="tr_stock_price_{$toId} tr_stock_price_{$uid}">
							<td class="text-left">
								<input type="text" placeholder="Nhập tên..." name="stock_price[{$uid}][title]" class="form-control required" maxlength="255" value="{$_oSH.title}" />
							</td>
							<td class="text-left">
								<div class="input-group">
									<input type="text" placeholder="Nhập ảnh..." name="stock_price[{$uid}][image]" class="form-control required stock_price_image_hidden_{$uid}" maxlength="255" value="{$_oSH.image}" />
									<div class="input-group-btn">
										<button type="button" toId="{$toId}" uid="{$uid}" onClick="stock_price_select_file(this, event)" stock_id="{$stock_id}" class="btn btn-default">{$core->makeIcon('upload','Chọn file')}</button>
									</div>
								</div>
							</td>
							<td class="text-center">
								{if !$smarty.foreach.i.first}
								<button type="button" onClick="stock_price_delete_line(this, event)" uid="{$uid}" 
								class="btn btn-sm btn-default">{$core->makeIcon('trash')}</button>
								{/if}
							</td>
						</tr>
						{/foreach}
					{else}
					{assign var = uid value = $clsISO->getUniqid()}
					<tr class="tr_stock_price_{$toId} tr_stock_price_{$uid}">
						<td class="text-left">
							<input type="text" placeholder="Nhập tên..." name="stock_price[{$uid}][title]" class="form-control required" maxlength="255" value="Tiêu chuẩn" />
						</td>
						<td class="text-left">
							<div class="input-group">
								<input type="text" placeholder="Nhập ảnh..." name="stock_price[{$uid}][image]" class="form-control required stock_price_image_hidden_{$uid}" maxlength="255" />
								<div class="input-group-btn">
									<button type="button" toId="{$toId}" uid="{$uid}" onClick="stock_price_select_file(this, event)" stock_id="{$stock_id}" class="btn btn-default">{$core->makeIcon('upload','Chọn file')}</button>
								</div>
							</div>
						</td>
						<td class="text-center"></td>
					</tr>
					{/if}
				</table>
				<div class="d-flex">
					<button type="button" onClick="stock_price_addline(this, event)" stock_id="{$stock_id}" 
					toId="{$toId}" class="btn btn-default">+ Thêm phiên bản</button>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="pop_save_stock_price(this, event)" uid="{$toId}" 
				stock_id="{$stock_id}" class="btn btn-success">Lưu lại</button>
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal">
					{$core->get_Lang('Close')}
				</button>
			</div>
		</form>
	</div>
</div>