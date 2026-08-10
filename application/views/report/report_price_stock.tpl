<div class="container-xxl flex-grow-1 container-p-y pt-2">	
	<div class="form-row my-2">
		<div class="col-12 col-md-8 mx-auto">
			<form method="POST">
				<div class="d-flex flex-wrap justify-content-between align-items-center py-2 mb-2 mb-lg-0">
					<div class="title mb-lg-0">
						<h4 class="fw-bold mb-1">Lịch sử thay đổi giá</span></h4>
						<span class="text-muted">Lịch sử thay đổi giá</span>
					</div>
					{if $deviceType ne 'phone'}
					<div class="search d-flex flex-wrap align-items-center gap-1">
						<div class="input-group bg-white" style="width: 350px">
							<select class="form-control js__search-department-field search_field w-px-100 form-select multiselect" onChange="$Core.report.select_block(this, event)" name="project_id" 
							id="slb_Project_Id" toId="slb_Block_Id" data-width="100%" data-field="project_id">
								{foreach name=i from=$list_projects item=project}
								<option{if $project.project_id eq $_ss_project_id} selected{/if} value="{$project.project_id}">{$project.code}</option>
								{/foreach}
							</select>
							<select class="form-control js__search-department-field search_field w-px-120 form-select multiselect" onChange="$Core.report.select_building(this, event)" data-placeholder="Phân khu" data-width="100%" data-header="true" data-filter="true" multiple id="slb_Block_Id" toId="slb_Building_Id" data-field="blocks_ids[]">
								{if !empty($list_blocks)}
									{foreach name=i from=$list_blocks item=block}
									<option{if $clsISO->checkInArray($_ss_blocks_ids, $block.property_id)} selected{/if} value="{$block.property_id}">{$block.title}</option>
									{/foreach}
								{/if}
							</select>
							<select class="form-control js__search-department-field search_field w-px-120 form-select multiselect" data-placeholder="Tòa căn hộ" data-width="100%" data-header="true" data-filter="true" onChange="$Core.report.load_report_price_stock()" multiple id="slb_Building_Id" data-field="building_ids[]">
								{if !empty($_ss_blocks_ids)}
									{foreach from=$list_ss_buildings key = block_id item = list_buildings}
									<optgroup label="{$clsProperty->getTitle($block_id)}">
										{if !empty($list_buildings)}
											{foreach name=i from=$list_buildings item=building}
											<option{if $clsISO->checkInArray($_ss_building_ids,$building.property_id)} selected{/if} value="{$building.property_id}">{$building.title}</option>
											{/foreach}
										{/if}
									</optgroup>
									{/foreach}
								{else}
									{if !empty($list_buildings)}
										{foreach name=i from=$list_buildings item=building}
										<option value="{$building.property_id}">{$building.title}</option>
										{/foreach}
									{/if}
								{/if}
							</select>
						</div>
						<div class="input-group input-group-merge w-px-150">
							<span class="input-group-text" id="basic-addon-search31"><i class="icon-base bx bx-search"></i></span>
							<input type="text" class="form-control search_field" data-field="keyword" name="keyword" placeholder="Nhập mã căn" aria-label="Nhập mã căn" aria-describedby="basic-addon-search31" onKeyUp="$Core.report.load_report_price_stock()">
						 </div>
					</div>
					{/if}
				</div>
				{if $deviceType eq 'phone'}
				<div class="search d-flex flex-wrap align-items-center mt-2">
					<div class="input-group w-100 mb-1">
						<select class="form-control js__search-department-field search_field w-px-100 form-select multiselect" onChange="$Core.report.select_block(this, event)" name="project_id" 
						id="slb_Project_Id" toId="slb_Block_Id" data-width="100%" data-field="project_id">
							{foreach name=i from=$list_projects item=project}
							<option{if $project.project_id eq $_ss_project_id} selected{/if} value="{$project.project_id}">{$project.code}</option>
							{/foreach}
						</select>
						<select class="form-control js__search-department-field search_field w-px-120 form-select multiselect" onChange="$Core.report.select_building(this, event)" data-placeholder="Phân khu" data-width="100%" data-header="true" data-filter="true" multiple id="slb_Block_Id" toId="slb_Building_Id" data-field="blocks_ids[]">
							{if !empty($list_blocks)}
								{foreach name=i from=$list_blocks item=block}
								<option{if $clsISO->checkInArray($_ss_blocks_ids, $block.property_id)} selected{/if} value="{$block.property_id}">{$block.title}</option>
								{/foreach}
							{/if}
						</select>
						<select class="form-control js__search-department-field search_field w-px-120 form-select multiselect" data-placeholder="Tòa căn hộ" data-width="100%" data-header="true" data-filter="true" onChange="$Core.report.load_report_price_stock()" multiple id="slb_Building_Id" data-field="building_ids[]">
							{if !empty($_ss_blocks_ids)}
								{foreach from=$list_ss_buildings key = block_id item = list_buildings}
								<optgroup label="{$clsProperty->getTitle($block_id)}">
									{if !empty($list_buildings)}
										{foreach name=i from=$list_buildings item=building}
										<option{if $clsISO->checkInArray($_ss_building_ids,$building.property_id)} selected{/if} value="{$building.property_id}">{$building.title}</option>
										{/foreach}
									{/if}
								</optgroup>
								{/foreach}
							{else}
								{if !empty($list_buildings)}
									{foreach name=i from=$list_buildings item=building}
									<option value="{$building.property_id}">{$building.title}</option>
									{/foreach}
								{/if}
							{/if}
						</select>
					</div>
					<div class="input-group input-group-merge w-px-150">
						<span class="input-group-text" id="basic-addon-search31"><i class="icon-base bx bx-search"></i></span>
						<input type="text" class="form-control search_field" data-field="keyword" name="keyword" placeholder="Nhập mã căn" aria-label="Nhập mã căn" aria-describedby="basic-addon-search31" onKeyUp="$Core.report.load_report_price_stock()">
					 </div>
				</div>
				{/if}
			</form>
			<hr class="my-0" />
			<div class="card mb-2">
				<div class="card-body">
					<div class="table-wrapper">
						<table border="0" class="table table-iloocal table-bordered" width="100%">
							<thead>
								<tr>
									{if $deviceType ne "phone"}<th width="6px" class="align-center text-left ">STT</th>{/if}
									<th {if $deviceType eq "phone"}width="100px"{/if} class="align-center text-left">Mã căn</th>
									<th class="align-center text-center">Nội dung</th>
								</tr>
							</thead>
							<tbody class="holder_reports_price_stock">
								{section name=i loop=$list_preloaders max=15}
								<tr>
									{if $deviceType ne "phone"}<td class="text-center">{$smarty.section.i.iteration}</td>{/if}
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
									<td><div class="animate-bg w-100 h-px-15 rounded-2"></div></td>
								</tr>
								{/section}
							</tbody>
						</table>
					</div>
				</div>
			</div>
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
	}
</style>
<script type="text/javascript">
	$(function(){
		$Core.report.load_report_price_stock({});
	});
</script>
{/literal}