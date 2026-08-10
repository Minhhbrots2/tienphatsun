<div class="modal-dialog modal-sm">
	<form action="#" method="POST" onsubmit="return false;" class="modal-content">
		<div class="modal-header pb-3 border-bottom">
			<h5 class="modal-title">Chiến dịch</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div class="bg-label-warning p-3 rounded-2 mb-2">
				<input type="hidden" name="list_ids" value="{$list_ids|escape:'html'}" >
				Tổng {$total_data} data đã chọn
			</div>
			<div class="row">
				<div class="col-12 mb-2">
					<div class="form-group">
						<label class="form-label mb-1">Chiến dịch</label>
						<select class="form-control form-select required" name="campaign_id" >
							<option value="">Chọn chiến dịch</option>
							{foreach name=i from=$lstCampaign item=_oCampaign}
								<option value="{$_oCampaign.campaign_id}">{$_oCampaign.title}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="col-12 mb-2">
					<div class="form-group">
						<label class="form-label mb-1">Phòng ban</label>
						<select class="form-control iso-select2" data-width="100%" name="department_id" onChange="$Core.data_central.load_share_staffs(this,event)" toId="staff_{$uid}" >
							{*$clsProperty->getSelectSingleProperty('_DEPARTMENT',$smarty.const._DEPARTMENT_SALE_ID,0,"Phòng ban")*}
							{$clsProperty->getSelectSingleProperty('_DEPARTMENT',0,0,"Phòng ban")}
						</select>
					</div>
				</div>
				<div class="col-12 mb-2">
					<div class="form-group">
						<label class="form-label mb-1">Nhân viên</label>
						<select class="form-control iso-select2 required" data-width="100%" name="staff_id" id="staff_{$uid}" data-placeholder="Chọn nhân viên" >
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer border-top">
			<input type="hidden" name="hid" value="Update" />
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" onClick="$Core.data_central.check_data_campaign(this, event)" 
				class="btn btn-primary js__start_share_customer" toId="{$uid}" >Xác nhận</button>
		</div>
	</form>
</div>