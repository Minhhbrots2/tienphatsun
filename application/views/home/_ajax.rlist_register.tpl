<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Đăng ký tham gia</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body scroller">
				<div class="table-responsive text-nowrap">
					<table class="table table-striped">
						<thead> <tr>
							<th width="10%">No.</th>
							<th>Họ và tên</th>
						</tr> </thead>
						{foreach name=i from=$list_registers item = _oRegis}
						<tr>
							<td class="text-center">{$smarty.foreach.i.iteration}</td>
							<td>
								<h4 class="fs-14 mb-1">{$_oRegis.full_name}</h4>
								<p class="text-muted mb-0">
									{$clsISO->makeIcon('bx-phone', {$_oRegis.phone})}
									<i class="mx-2">-</i>
									{$clsISO->makeIcon('bx-envelope', {$_oRegis.email})}
								</p>
							</td>
						</tr>
						{/foreach}
					</table>
				</div>
			</div>
		</div>
	</div>               
</div>
{literal}
<style type="text/css">
	.rlist_register{
		margin;0;
		padding:0;
		list-style:none;
	}
	.rlist_register li{
		padding:10px;
		margin-bottom:10px;
		border:1px solid #EEE;
		border-radius:3px;
		-moz-border-radius:3px;
		-webkit-border-radius:3px;
	}
</style>
{/literal}