<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-md-3 col-form-label">Ngày thanh toán<span class="text-red">*</span></label>
					<div class="col-md-9">
						<input type="text" id="{$clsISO->getUniqid()}" autocomplete="off" class="form-control isodatepicker required" placeholder="dd/mm/yyyy" name="date_id" value="{if !empty($oneOption.date_id)}{$clsISO->convertTimeToText($oneOption.date_id)}{/if}" />
						{literal}
						<style type="text/css">
							.ui-datepicker{ z-index:99999 !important}
						</style>
						{/literal}
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-form-label">K/C<span class="text-red">*</span></label>
					<div class="col-md-9">
						<div class="input-group d-flex">
							<input type="number" class="form-control numberonly" value="{if !empty($oneOption.time_value)}{$oneOption.time_value}{/if}" name="time_value" />
							<select class="form-control required" name="time_unit">
								{$clsISO->getSelectByPropertyTypeNotTitle('_TIME_UNIT', $oneOption.time_unit)}
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-form-label">Tỷ lệ thanh toán</label>
					<div class="col-md-9">
						<div class="input-group-suffix">
							<input type="number" class="form-control numberonly required" name="payment_rate" value="{$oneOption.payment_rate}" />
							<span class="suffix">%</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-form-label">Thuế VAT</label>
					<div class="col-md-9">
						<div class="input-group-suffix">
							<input type="text" class="form-control numberonly required" name="tax_rate" value="{$oneOption.tax_rate}" />
							<span class="suffix">%</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 col-form-label">Mô tả</label>
					<div class="col-md-9">
						<textarea class="form-control required" rows="5" placeholder="Mô tả" name="description">{$oneOption.description}</textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success clickToSaveOptions pull-right" project_id="{$project_id}" option_id="{$option_id}">Lưu lại</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>