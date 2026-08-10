<div class="container-xxl flex-grow-1 container-p-y pt-2">	
	<form method="POST">
		<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2 mb-lg-0">
			<div class="title mb-2 mb-lg-0">
				<h4 class="fw-bold mb-1">Log hệ thống</span></h4>
				<span class="text-muted">Danh sách hành động trong hệ thống</span>
			</div>
			<div class="search d-flex flex-wrap align-items-center gap-1">
				<div class="input-group {if $deviceType eq 'phone'}w-100{else}w-auto{/if}">
					<select onchange="$Core.report.load_activity_log({})" data-field="_from" 
						class="form-control js__search-department-field search_field {if $deviceType eq 'phone'}flex-fill{else}w-px-100{/if} form-select">
						<option value="" >Nguồn</option>
						<option value="admin">Admin</option>
						<option value="front">Website</option>
					</select>
					<select onchange="$Core.report.load_activity_log({})" data-field="profile_id" 
						class="form-control js__search-department-field search_field {if $deviceType eq 'phone'}flex-fill{else}w-px-100{/if} form-select">
						<option value="0">Tất cả</option>
						{if !empty($arr_cache_profile)}
							{foreach from=$arr_cache_profile item = _oProfile}
							<option value="{$_oProfile.profile_id}">{$_oProfile.full_name}</option>
							{/foreach}
						{/if}
					</select>
					<select onchange="$Core.report.load_activity_log({})" data-field="tbl" 
						class="form-control js__search-tbl-field search_field {if $deviceType eq 'phone'}flex-fill{else}w-px-100{/if} form-select">
						<option value="" >Chọn loại</option>
					</select>
				</div>
				<div class="input-group {if $deviceType eq 'phone'}w-100{else}w-auto{/if}">
					<input type="date" onchange="$Core.report.load_activity_log({})" class="form-control js__search-start_date-field 
					js__search-date-field search_field {if $deviceType eq 'phone'}w-50{else}w-px-125{/if}" data-field="start_date" value="{$start_date}" max="{$smarty.now|date_format:'%Y-%m-%d'}"/>
					<input type="date" onchange="$Core.report.load_activity_log({})" class="form-control js__search-end_date-field 
					js__search-date-field search_field {if $deviceType eq 'phone'}w-50{else}w-px-125{/if}" data-field="end_date" value="{$end_date}" max="{$smarty.now|date_format:'%Y-%m-%d'}" />
				</div>
			</div>
		</div>
	</form>
	<div class="card no-shadow mb-2">
		<div class="card-body">
			<div class="table-container no-shadow overflow-x-auto">
				<table cellpadding="0" cellspacing="0" class="table mb-0" width="100%">
					<thead><tr>
						<th width="3%" class="align-center bg-lighter h-px-35 text-center">No.</th>
						<th class="align-center bg-lighter h-px-35 text-left">Tiêu đề</th>
						<th class="align-center bg-lighter h-px-35 text-left">Nội dung</th>
					</tr></thead>
					<tbody class="holder_reports_activity_log">
						{section name=i loop=$list_preloaders max=10}
						<tr>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
						</tr>
						{/section}
					</tbody>
				</table>
			</div>
			<div class="clearfix mb-1"></div>
			<div id="pager" class="simple-pagination"></div>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.table-responsive td{
		text-align:left;
	}
	.multiselect-native-select{
		width:100%
	}
	.ui-datepicker,
	.select2-container--open{
		z-index:9999 !important;
	}
	.table-iloocal tr td {
		font-weight: 400;
		font-size: 14px;
		line-height: 20px;
		padding: 6px 15px;
		background: var(--bs-white);
		border: 1px solid rgba(0, 0, 0, 0.1);
		height: 40px;
	}
	.table-iloocal thead tr th {
		background: #F9F9F9;
		border: 1px solid rgba(0, 0, 0, 0.1);
		white-space: nowrap;
		font-weight: 600;
		font-size: 14px;
		line-height: 20px;
		padding: 10px 15px
	}
	.table-iloocal .js__add-report:not(.text-muted) {
		font-weight: 600;
		font-size: 14px;
		line-height: 19px;
		color: #1756C8 !important;
		cursor: pointer;
	}
	.table-iloocal .js__add-report span.icon {
		display: inline-block;
		width: 14px;
		height: 14px;
		text-align: center;
		line-height: 12px;
		background: #1756C8;
		border-radius: 2px;
		-moz-border-radius: 2px;
		-webkit-border-radius: 2px;
		color: var(--bs-white);
		padding:3px;
		font-size: 10px;
	}
	@media screen and (max-width:767px) {
		.table-iloocal thead tr th, .table-iloocal tbody tr td {
			padding: 5px;
		}
		.table-container .table tr th:nth-child(2){
			background:#F5F7F8 !important
		}
		.table-container .table tr th:nth-child(2),
		.table-container .table tr td:nth-child(2){
			z-index:2;
			position:sticky;
			left:0px; top:0;
			background:var(--bs-white);
			border-right: 1px solid #d9dee3;
		}
	}
</style>
<script type="text/javascript">
	$(function(){
		$Core.report.load_activity_log({});
	});
</script>
{/literal}