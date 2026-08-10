<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-1 mb-2">
		<div class="mb-2 mb-lg-0">
			<h4 class="fw-bold mb-0">Xác nhận hoa hồng</span></h4>
			<span class="text-muted">Danh sách xác nhận hoa hồng</span>
		</div>
		<div class="btn-groups">
			<button type="button" data-toggle="ripple" title="Thêm mới" onClick="$Core.transaction.open(this, event)" 
			class="btn btn-outline-primary">+ Thêm mới</button>
		</div>
	</div>
	<div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
			<div class="d-flex gap-2 mb-2 mb-lg-0 align-items-center">
				<label class="col-form-label d-none d-lg-block">Loại giao dịch</label>
				<div class="btn-group js__block-filter-type-list d-flex gap-2" role="group">
					<label class="we-radio" >
						<input type="radio" name="transaction_type" value="_all">
						<span>Tất cả (10)</span>
					</label>
					{foreach name=i from=$list_transaction_type item = _oItem}
					<label class="we-radio" >
						<input type="radio" name="transaction_type" value="{$_oItem.property_id}">
						<span>{$_oItem.title} (0)</span>
					</label>
					{/foreach}
				</div>
			</div>
			<form{if $deviceType eq 'phone'} class="w-100"{/if} method="POST">
				<div class="search d-flex gap-1">
					<div class="input-group input-group-merge">
						<span class="input-group-text"><i class="bx bx-search"></i></span>
						<input type="text" class="form-control search_field" data-field="keySearch" placeholder="{$core->get_Lang('Search')}" />
						{if $clsISO->checkPermissionGroup('ADMIN_PROJECT')}
						<div class="btn-group mr-1">
							<button data-toggle="ripple" type="button" class="btn no-radius-left btn-icon btn-outline-default dropdown-toggle hide-arrow" 
							data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
								<i class="bx bx-cog"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-end">
								
							</div>
						</div>
						{/if}
					</div>
					<div class="input-group input-group-merge">
						<select class="form-control w-px-75 form-select">
							<option value="">Tháng</option>
						</select>
						<select class="form-control w-px-75 form-select">
							<option value="">Năm</option>
						</select>
					</div>	
				</div>
			</form>
		</div>
		<div class="card-body">
			<div id="holder_transactions">
				<div class="table-responsive freeze-table dragscroll text-nowrap">
					<table class="table table-striped mb-4" width="100%">
						<thead><tr>
							<th class="align-center" width="3%">STT</th>
							<th class="align-center" width="5%">Mã GD</th>
							<th class="align-center">Ngày gửi</th>
							<th class="align-center">Ngày gửi</th>
							<th class="align-center">Ngày gửi</th>
							<th class="align-center">Nhân viên</th>
							<th class="align-center">Tiến độ xử lý</th>
							<th class="align-center" width="10%">Tình trạng</th>
							<th width="45px"></th>
						</tr></thead>
						{section name=i loop=$list_preloaders max=30}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						</tr>
						{/section}
					</table>
				</div>	
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		$Core.transaction.list({});
	});
</script>
{/literal}