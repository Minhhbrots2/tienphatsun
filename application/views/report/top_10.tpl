<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="row">
		<div class="col-12 col-lg-10 offset-lg-1 col-xxxl-8 offset-xxxl-2">
			<h4 class="fw-bold mb-0">
				<span>TOP kết quả bán hàng {$smarty.now|date_format:"%Y"}</span>
			</h4>
			<p class="text-muted mb-2">Tổng hợp kết quả bán hàng {$smarty.const.BRAND_NAME}</p>
			{foreach from=$list_billing_types name=i item = _OI}
			{assign var = list_staffs value = $_OI.list_staffs}
			{if !empty($list_staffs)}
			<div class="card mb-2">
				<div class="card-header">
					<h3 class="card-title fs-5 mb-0">{$_OI.title}</h3>
				</div>
				<div class="card-body">
					<div class="table-container no-shadow overflow-x-auto text-nowrap">
						<table class="table table-bordered" cellpadding="0" cellspacing="0">
							<thead><tr>
								{if $deviceType ne 'phone'}
								<th width="5%" class="align-center h-px-40 bg-lighter text-center">No.</th>
								{/if}
								<th class="align-center bg-lighter h-px-40">Họ và tên</th>
								<th class="align-center bg-lighter h-px-40">Phòng ban</th>
								<th width="10%" class="align-center bg-lighter h-px-40 text-center">Số GD</th>
								<th width="30%" class="align-center bg-lighter h-px-40">Doanh số</th>
							</tr></thead>
							{foreach from = $list_staffs name=k item = _oStaff}
							<tr>
								{if $deviceType ne 'phone'}
								<td class="text-center">{$smarty.foreach.k.iteration}</td>{/if}
								<td class="align-center">{$_oStaff.full_name}</td>
								<td class="align-center">{$_oStaff.department_name}</td>
								<td class="text-center">{$_oStaff.total_billings}</td>
								<td class="text-left">
									<strong class="text-main">
										{$clsISO->shortNumber($_oStaff.total_revenue,3)}
									</strong>
								</td>
							</tr>
							{/foreach}
						</table>
					</div>
				</div>
			</div>
			{/if}
			{/foreach}
		</div>
	</div>
</div>