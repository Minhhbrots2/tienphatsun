<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="col-12 col-xxl-8 offset-xxl-2">
		<div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
			<div class="p__left">
				<h4 class="fw-bold mb-1"><span>Doanh số bán hàng</span></h4>
				<p class="text-muted mb-0">Phòng kinh doanh {$smarty.const.BRAND_NAME}</p>
			</div>
			<div class="d-flex align-items-center gap-2 p__right">
				<div class="dropdown">
					<button type="button" class="btn btn-outline-primary btn-icon dropdown-toggle hide-arrow" 
						data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true"><i class="bx bx-filter-alt"></i> </button>
					<div class="dropdown-menu dropdown-menu-end" data-popper-placement="bottom-end">
						<div class="dropdown-header text-upper">Bộ lọc tìm kiếm</div>
						<div class="px-3 pb-3">
							<div class="form-group mb-2">
								<label class="form-label mb-1">Theo thời gian</label>
								<div class="clearfix"></div>
								<div class="btn-group text-nowrap" role="group" aria-label="Hiển thị">
									{foreach from=$arr_periods key = _oK item = _oI}
									<input type="radio" class="btn-check" name="tp" onChange="$Core.kpi.set_view(this, event)" id="{$uid}_{$_oK}" value="{$_oK}"{if $_ss_view_kpi eq $_oK} checked="checked"{/if}>
									<label data-toggle="ripple" title="{$_oI}"  class="btn btn-outline-default" for="{$uid}_{$_oK}">{$_oI}</label>
									{/foreach}
								</div>
							</div>
							<div class="form-group mb-2">
								<label class="form-label mb-1">Theo loại hình</label>
								<select data-field="billing_type" class="form-control search_field form-select multiselect" 
									onchange="$Core.kpi.do_change(this, event)">
									<option value="ALL">Tất cả</option>
									<option value="CAO_TANG">Cao tầng</option>
									<option value="THAP_TANG">Thấp tầng</option>
								</select>
							</div>
							<div class="form-group">
								<label class="form-label mb-1">Hiển thị Team</label>
								<div class="clearfix"></div>
								<div class="form-check form-switch mb-2">
									<input class="form-check-input search_field w-px-40 mr-2" type="checkbox" id="{$uid}_team" 
										onChange="$Core.kpi.do_change(this, event)" data-field="is_team_enabled" checked="checked">
									<label class="form-check-label" for="{$uid}_team">Có/Không</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<a href="/kpi-config.html" title="Cài đặt chỉ tiêu" class="btn btn-icon btn-outline-default">
					{$clsISO->makeIcon('bx-cog')}
				</a>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="card">
			<div class="card-header position-relative border-bottom d-flex justify-content-center mb-3">
				<div class="d-flex gap-1 justify-content-between">
					{assign var = uid value = $clsISO->getUniqid()}
					<button type="button" uid="{$uid}" onClick="$Core.kpi.set_month(this,event)" 
					tp="prev" class="btn btn-icon btn-outline-default" title="Tháng trước">{$clsISO->makeIcon('bx-chevron-left')}</button>
					
					<div class="input-group-date">
						<input type="text" id="{$uid}" class="form-control w-px-125 search_field isodatepicker" readonly 
						value="{if $_ss_view_kpi eq 'year'}{$smarty.now|date_format:'%Y'}{elseif $_ss_view_kpi eq 'quarter'}Q{$quarter.quarter}/{$smarty.now|date_format:'%Y'}{else}{$smarty.now|date_format:'%m/%Y'}{/if}" onchange="$Core.kpi.select_month(this,event)" maxlength="255" data-field="month" />
					</div>
					<button type="button" uid="{$uid}" onClick="$Core.kpi.set_month(this,event)" 
					tp="next" class="btn btn-icon btn-outline-default" title="Tháng tiếp theo">{$clsISO->makeIcon('bx-chevron-right')}</button>
				</div>
			</div>
			<div id="holder_kpi" class="card-body">
				<div style="overflow:auto;">
					<table class="table table-sort table_sortable" width="100%">
						<thead><tr>
							<th width="5%" rowspan="2" class="align-center nosort ulpVdojZSe text-left">No.</th>
							<th rowspan="2" class="align-center nosort ulpVdojZSe text-left">Mã nhóm.</th>
							<th rowspan="2" class="align-center nosort ulpVdojZSe text-left">Leaders.</th>
							<th class="align-center nosort text-center">Chỉ tiêu</th>
							<th class="align-center nosort text-center" colspan="2">Tổng các dự án</th>
							<th width="150px" rowspan="2" class="nosort align-center ulpVdojZSe text-center">% Hoàn<br>Thành</th>
						</tr>
						<tr>
							<th class="align-center ulpVdojZSe text-center">Doanh số</th>
							<th class="align-center ulpVdojZSe text-center">T. Giao Dịch</th>
							<th class="align-center ulpVdojZSe text-center">Doanh số</th>
						</tr></thead>
						{section name=i loop=$list_preloaders max=20}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-1"></td>
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
		$Core.kpi.load_kpi({});
	});
</script>
<style type="text/css">
	.table td, .table th{
		font-size:14px;
		padding:0.425rem 0.625rem;
		white-space:nowrap;
	}
	.table thead th{
		font-weight:bold;
		color:rgba(0,0,0,1) !important;
		background:rgb(250 182 117);
		border:1px solid rgb(159 14 9);
	}
	.table tbody tr:first-child td{
		position:relative;
	}
	.table tbody tr:first-child td:after{
		content:"";
		position:absolute;
		left:0; top:0;
		width:100%;
		height:0px;
	}
	.table tbody td{
		color:rgba(0,0,0,1);
		background:rgb(255 249 204);
		border:1px solid rgb(159 14 9);
	}
	.table tfoot td{
		color:rgba(255,255,255,1);
		background:rgb(159,34,58) !important;
	}
	.is-star-club{
		display:none !important;
	} 
	tr.top-1 td{
		background:#e19d5d;
	}
	tr.top-2 td{
		background:#fab675;
	}
	tr.top-3 td{
		background:#f7cb85;
	}
</style>
{/literal}