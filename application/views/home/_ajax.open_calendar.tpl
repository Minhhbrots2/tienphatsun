<div class="modal-dialog modal-dialog-centered modal-standard">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Lịch ký</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="p-2 bg-lighter mb-2 radius-3">
				<div class="d-flex gap-2 gap-lg-3 align-items-center justify-content-between">
					<select class="form-control search_calender form-select" uid="{$uid}" onChange="$Core.billing.reload_calendar(this, event)" name="billing_type">
						<option value="0">Lựa chọn</option>
						{$clsProperty->getSelectByProperty('_BILLING_TYPE',$oneBilling.billing_type)}
					</select>
					<div class="x">
						{assign var = gId value = $clsISO->getUniqid()}
						<div class="btn-group d-flex" role="group" aria-label="Sắp xếp">
							<input type="radio" class="btn-check js__filter-date" uid="{$uid}" onChange="$Core.billing.reload_calendar(this, event)" name="type_of_date" id="all_{$gId}" value="_all" checked="checked">
							<label data-toggle="ripple" class="btn text-nowrap btn-outline-default" for="all_{$gId}">Tất cả</label>
							<input type="radio" class="btn-check js__filter-date" uid="{$uid}" onChange="$Core.billing.reload_calendar(this, event)" name="type_of_date" id="HDMB_{$gId}" value="_contract">
							<label data-toggle="ripple" class="btn text-nowrap btn-outline-default" for="HDMB_{$gId}">HĐMB</label>
							<input type="radio" class="btn-check js__filter-date" uid="{$uid}" onChange="$Core.billing.reload_calendar(this, event)" name="type_of_date" id="VBTT_{$gId}" value="_text">
							<label data-toggle="ripple" class="btn text-nowrap btn-outline-default" for="VBTT_{$gId}">VBTT</label>
						</div>
					</div>
				</div>
			</div>
			<div id="calendar_{$uid}">
				<div class="p-5 text-center">Loading...</div>
			</div>
		</div>
		<div class="modal-footer d-none">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
		</div>
	</form>
</div>