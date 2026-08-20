<script type="text/javascript">
	var project_id = '{$project_id}',
		block_id = '{$block_id}',
		building_id = '{$building_id}';
	window.PROJECT_OVERVIEW_DATA = {ldelim}
		project_id: {$project_id|default:0},
		block_id: {$block_id|default:0},
		building_id: {$building_id|default:0},
		show: '{$show}'
	{rdelim};
</script>
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/overview.css?v={$upd_version}" />
{* smartZoom (zoom mat bang) da co san trong store.min.js nap toan cuc — KHONG nap lai (trung Table2Excel) *}
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item">
				<a href="{$PCMS_URL}/bang-hang/">Bảng hàng</a>
			</li>
			{if $show eq 'building'}
				<li class="breadcrumb-item">
					<a href="/project/pt{$project_id}.html">{$clsProject->getCode($project_id,$oneProject)}</a>
				</li>
				<li class="breadcrumb-item">
					<a href="/project/pt{$project_id}/bl{$block_id}.html">{$oneBlock.title|escape}</a>
				</li>
				<li class="breadcrumb-item active">{$oneBuilding.title|escape}</li>
			{elseif $show eq 'block'}
				<li class="breadcrumb-item">
					<a href="/project/pt{$project_id}.html">{$clsProject->getCode($project_id,$oneProject)}</a>
				</li>
				<li class="breadcrumb-item active">{$oneBlock.title|escape}</li>
			{else}
				<li class="breadcrumb-item active">{$clsProject->getCode($project_id,$oneProject)}</li>
			{/if}
		</ol>
	</nav>
	{$core->getBlock("banner_stock", ['oneBuilding' => $oneBuilding])}
	<div class="clearfix"></div>
	<div ng-app="overviewApp" ng-controller="OverviewCtrl" ng-cloak>
		{literal}
		<!-- LOADING -->
		<div ng-if="loading" class="text-center py-5">
			<div class="spinner-border text-danger" role="status">
				<span class="visually-hidden">Đang tải...</span>
			</div>
		</div>
		{/literal}
		<div ng-if="!loading">
			<div ng-include="'/application/views/home/project/partials/overview.html?v={$upd_version}'"></div>
			{literal}
			<!-- STATS BAR: cap toa -->
			<div class="stats-project-bar mt-3" ng-if="data.show == 'building' && data.oneBuilding">
				<div class="stat-bar-item" ng-if="data.more_information.number_floor">
					<div class="stat-bar-num">{{data.more_information.number_floor}} tầng</div>
					<div class="stat-bar-label">Tầng căn hộ</div>
				</div>
				<div class="stat-bar-item" ng-if="data.more_information.number_of_besement">
					<div class="stat-bar-num">{{data.more_information.number_of_besement}}</div>
					<div class="stat-bar-label">Tầng hầm</div>
				</div>
				<div class="stat-bar-item" ng-if="data.more_information.number_house">
					<div class="stat-bar-num">{{data.more_information.number_house}} căn</div>
					<div class="stat-bar-label">Căn hộ / sàn</div>
				</div>
				<div class="stat-bar-item" ng-repeat="item in data.more_information.info_more">
					<div class="stat-bar-num">{{item.value}}</div>
					<div class="stat-bar-label">{{item.title}}</div>
				</div>
				<div class="stat-bar-item" ng-if="data.more_information.handover_time">
					<div class="stat-bar-num">{{data.more_information.handover_time}}</div>
					<div class="stat-bar-label">Bàn giao</div>
				</div>
			</div>
			<!-- STATS BAR: cap phan khu -->
			<div class="stats-project-bar mt-3" ng-if="data.show == 'block'">
				<div class="stat-bar-item" ng-if="data.is_lowfloor == 0">
					<div class="stat-bar-num">{{data.list_buildings.length || 0}} tòa nhà</div>
					<div class="stat-bar-label">Phân khu</div>
				</div>
				<div class="stat-bar-item" ng-repeat="item in data.more_information.info_more">
					<div class="stat-bar-num">{{item.value}}</div>
					<div class="stat-bar-label">{{item.title}}</div>
				</div>
			</div>
			<!-- STATS BAR: cap du an -->
			<div class="stats-project-bar mt-3" ng-if="data.show == 'project'">
				<div class="stat-bar-item" ng-if="data.more_information_project.arcreage">
					<div class="stat-bar-num">{{data.more_information_project.arcreage}}</div>
					<div class="stat-bar-label">Tổng quy mô</div>
				</div>
				<div class="stat-bar-item" ng-if="data.more_information_project.total_investment">
					<div class="stat-bar-num">{{data.more_information_project.total_investment}}</div>
					<div class="stat-bar-label">Tổng vốn</div>
				</div>
				<div class="stat-bar-item">
					<div class="stat-bar-num">{{data.list_blocks.length || 0}}</div>
					<div class="stat-bar-label">Phân khu</div>
				</div>
				<div class="stat-bar-item" ng-if="data.more_information_project.construction_type">
					<div class="stat-bar-num">{{data.more_information_project.construction_type}}</div>
					<div class="stat-bar-label">Các loại hình</div>
				</div>
				<div class="stat-bar-item" ng-if="data.more_information_project.handover">
					<div class="stat-bar-num">{{data.more_information_project.handover}}</div>
					<div class="stat-bar-label">Bàn giao</div>
				</div>
				<div class="stat-bar-item" ng-if="data.more_information_project.legal_status">
					<div class="stat-bar-num">{{data.more_information_project.legal_status}}</div>
					<div class="stat-bar-label">Pháp lý</div>
				</div>
			</div>
			{/literal}
		</div>
	</div>
</div>
{* defer: chay sau angular.min.js (nap sync o footer), truoc khi Angular bootstrap ng-app *}
<script src="/application/views/home/project/js/jquery.overview.js?v={$upd_version}" defer></script>
