<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
		<div class="d-flex flex-column">
			<h4 class="fw-bold mb-1">Chiến dịch + thi đua</span></h4>
			<span class="text-muted">Danh sách chiến dịch CBNV {$smarty.const.BRAND_NAME}</span>
		</div>
		<button type="button" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Thêm mới" onClick="$Core.campaign.open(this, event)" campaign_id="0" class="btn btn-outline-primary">+ Thêm mới</button>
	</div>
	<div class="card"><div class="card-body">
		<div class="table-container no-shadow overflow-x-auto text-nowrap">
			<table cellpadding="0" cellspacing="0" class="table table-bordered">
				<thead><tr>
					{if $deviceType ne 'phone'}
					<th class="align-center h-px-35 text-center bg-lighter" width="3%">No.</th>{/if}
					<th class="align-center h-px-35 text-left bg-lighter">Tên chiến dịch</th>
					<th class="align-center h-px-35 text-center bg-lighter" width="45px">Xem</th>
					<th class="align-center h-px-35 text-center bg-lighter" width="150px">Bắt đầu</th>
					<th class="align-center h-px-35 text-center bg-lighter" width="150px">Kết thúc</th>
					<th class="align-center h-px-35 text-center bg-lighter" width="150px">Ngày tạo</th>
					<th class="align-center h-px-35 text-center bg-lighter" width="45px">H.Động</th>
				</tr></thead>
				<tbody class="holder_campaigns"></tbody>
			</table>
		</div>
	</div></div>
</div>
{literal}
<style type="text/css">
	.select2-container--open{
		z-index:9999 !important;
	}
	.table > :not(caption) > * > * {
		padding: 0.525rem 0.625rem;
	}
</style>
<script type="text/javascript">
	$(function(){
		$Core.campaign.list({});
	});
</script>
{/literal}