<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex align-items-start justify-content-between mb-2">
		<div class="yvBvmnviXh">
			<h4 class="fw-bold mb-1 {if $deviceType eq 'phone'}fs-5{/if}">Tra cứu căn hộ cao tầng</h4>
			<p class="text-muted mb-0">Có <span class="total_results text-main">0</span> căn hộ phù hợp</p>
		</div>
		{if !empty($list_projects)}
		<div class="btn-group">
			{if $deviceType eq 'phone'}
			<button type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" 
				data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-filter-alt"></i></button>
			{else}
			<button type="button" class="btn btn-outline-default dropdown-toggle" data-bs-toggle="dropdown" 
				aria-expanded="false"><i class='bx bx-link-external'></i> Chuyển dự án</button>
			{/if}
			<ul class="dropdown-menu dropdown-menu-end w-px-300 overflow-y-auto">
				{foreach from=$list_projects item=_oProject key=key name=i}
				<li><a class="dropdown-item text-truncate" href="/project/p{$_oProject.project_id}.html">
					<div class="d-flex align-items-center justify-content-between">
						<span>{$_oProject.title}</span>
						<i class='bx bx-chevron-right'></i>
					</div>
				</a></li>
				{/foreach}
			</ul>
		</div>
		{/if}
	</div>
    <!-- Basic Bootstrap Table -->
    <div class="card">
		<div class="card-header om-xs:p-2">
			<form class="p-3 bg-lighter" method="POST">
				{$core->getBlock('tool_search')}
			</form>
		</div>
		<div class="card-body om-xs:p-2">
			<div id="tableStock" class="table-container no-shadow overflow-x-auto text-nowrap">
				<table cellpadding="0" cellspacing="0" class="table table-iloocal table-{$deviceType} table-bordered">
					<thead><tr>
						{if $deviceType eq 'phone'}
							<th class="align-center bg-lighter h-px-35">Mã căn 
								{if $clsISO->checkPermission('view_stock_resource')}
								<button type="button" onClick="$Core.tool.toogle_agency(this, event)" 
									class="btn btn-xs btn-toggle-agency btn-outline-default">Ẩn ĐL</button>
								{/if}
							</th>
							<th class="align-center bg-lighter h-px-35">
								<input type="hidden" name="sort_by" data-field="sort_by" class="search_field" value="asc" />
								<a onclick="$Core.tool.do_sort(this, event)" class="sortClick asc">Giá VAT</a>
							</th>
							<th class="align-center bg-lighter h-px-35 sortable ">TTS</th>
							<th class="align-center bg-lighter h-px-35 sortable">TTTĐ</th>
							<th class="align-center bg-lighter h-px-35 sortable">Vay</th>
							<th class="align-center text-center h-px-35 bg-lighter">M2</th>
							<th class="align-center bg-lighter">L.căn</th>
						{else}
							<th class="align-center bg-lighter h-px-35 w-px-20"></th>
							<th width="12%" class="align-center h-px-35 bg-lighter">Mã căn 
								{if $clsISO->checkPermission('view_stock_resource')}
								<button type="button" onClick="$Core.tool.toogle_agency(this, event)" class="btn btn-xs btn-toggle-agency btn-outline-default">Ẩn ĐL</button>
								{/if}
							</th>
							<th class="align-center bg-lighter h-px-35">Tình trạng</th>
							<th class="align-center sortable asc bg-lighter h-px-35" onclick="$Core.tool.do_sort(this, event)">
								<input type="hidden" name="sort_by" data-field="sort_by" class="search_field" value="asc" />
								<span>Giá VAT</span>
							</th>
							<th class="align-center bg-lighter h-px-35 sortable ">Giá TTS</th>
							<th class="align-center bg-lighter h-px-35 sortable">Giá TTTĐ</th>
							<th class="align-center bg-lighter h-px-35 sortable">Giá Vay</th>
							<th class="align-center bg-lighter h-px-35 sortable">Giá/m2</th>
							<th class="align-center text-center h-px-35 bg-lighter">DT_TT(m2)</th>
							<th class="align-center bg-lighter h-px-35">Loại căn</th>
							<th class="align-center bg-lighter h-px-35">Loại hình</th>
						{/if}
						<th class="align-center bg-lighter h-px-35">Hướng</th>
						<th class="align-center text-center h-px-35 bg-lighter">PTG</th>
						<th class="align-center text-center h-px-35 bg-lighter">CSBH</th>
					</tr></thead>
					<tbody class="holder_search">
						{section name=i loop=$list_preloaders}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							{if $deviceType ne 'phone'}
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>
							{/if}
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<div class="d-flex justify-content-between pt-2 text-center" id="showmorethisresult">
				<button type="button" class="showmorethisresult" onClick="$Core.tool.load_more(this, event)" page="1"> 
					<span>Xem thêm</span> 
					<img src="{$URL_IMAGES}/loading_48.gif" width="24px"> 
				</button> 
			</div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	@media (min-width: 768px) and (max-width: 1024px) {
		.table-computer th:nth-child(4),
		.table-computer td:nth-child(4),
		.table-computer th:nth-child(8),
		.table-computer td:nth-child(8),
		.table-computer th:nth-child(11),
		.table-computer td:nth-child(11){
			display:none;
		}
	}
	@media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
		.table-computer th:nth-child(4),
		.table-computer td:nth-child(4){
			display:none;
		}
	}
	@media screen and (max-width:1400px){
		.table-computer th:nth-child(7),
		.table-computer td:nth-child(7){
			display:none;
		}
	}
</style>
<script type="text/javascript">
	$(function(){ $Core.tool.do_search(); });
</script>
{/literal}
