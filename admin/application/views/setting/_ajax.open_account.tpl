<div class="modal-dialog modal-md" style="max-width: 600px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">API key</label>
					<div class="col-md-10">
						<input class="form-control required" placeholder="API key" name="api_key" value="{if $action eq '_edit'}{$oneItem.api_key}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Model ID</label>
					<div class="col-md-10">
						<input class="form-control required" placeholder="Model ID" maxlength="255" name="model_id" 
							   value="{if $action eq '_edit'}{$oneItem.model_id.key}{/if}" />
					</div>
				</div>
				<div class="form-group form-row">
					<label class="col-md-2 col-form-label text-right">Sử dụng</label>
					<div class="col-md-4">
						<input class="form-control required" type="number" placeholder="Lượt trong tháng" maxlength="255" name="number" value="{if $action eq '_edit'}{$oneItem.model_id.number}{/if}" min="0"/>
					</div>
					<label class="col-md-2 col-form-label text-right">Ngày reset</label>
					<div class="col-md-4">
						<input class="form-control isodatepicker mw-100 required" placeholder="dd/mm/yyyy" maxlength="255" name="date" value="{if $action eq '_edit'}{$oneItem.model_id.date|date_format:'%d/%m/%Y'}{/if}" />
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.account.save(this, event)" data-key="{$key}" class="btn btn-success">
					<span>Lưu lại</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>Đóng</span>
				</button>
			</div>
		</form>
	</div>
</div>
