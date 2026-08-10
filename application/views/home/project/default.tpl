<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			{if $show eq 'project'}
				<li class="breadcrumb-item active">{$oneProject.title}</li>
			{elseif $show eq 'block'}
				<li class="breadcrumb-item">
					<a href="{$clsProject->getLinkPr($project_id)}">{$oneProject.title}</a>
				</li>
				<li class="breadcrumb-item active">{$oneBlock.title}</li>
			{else}
				<li class="breadcrumb-item">
					<a href="{$clsProject->getLinkPr($project_id)}">{$oneProject.title}</a>
				</li>
				<li class="breadcrumb-item">
					<a href="{$clsProject->getLinkBl($project_id, $block_id)}">{$clsProperty->getTitle($block_id)}</a>
				</li>
			{/if}
		</ol>
	</nav>		  
	<div class="row">
		<div class="col-12 col-lg-4 col-xxl-3 order-2 order-lg-1">
			{if !empty($more_information.attrs)}
			<div class="card no-shadow mb-4">
				<div class="card-body">
					<small class="text-muted text-uppercase">Tổng quan</small>
					<ul class="list-unstyled fs-6 mt-3 mb-0">
						{foreach from=$more_information.attrs item = _oAttr}
						<li class="d-flex align-items-center mb-2">
							<i class="material-icons-outlined no-translate">done</i>
							<span class="fw-medium mx-2">{$_oAttr.title}:</span> 
							<span>{$_oAttr.content}</span>
						</li>
						{/foreach}
					</ul>
				</div>
			</div>
			{/if}
			{if $show ne 'project'}
			<div class="card no-shadow mb-4">
				<div class="card-header flex-grow-0">
					<div class="d-flex">
						<div class="avatar flex-shrink-0 me-3">
							<img src="https://khudothixanhvn.com/wp-content/uploads/2021/11/vinhomes-ocean-park-logo.png" alt="User" class="rounded-circle">
						</div>
						<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-1">
							<div class="me-2">
								<h5 class="mb-0">
									<a href="{$clsProject->getLinkPr($project_id)}" class="text-link">{$oneProject.title}</a>
								</h5>
								<small class="text-muted">Cập nhật: {$clsISO->convertTimeToText($oneProject.upd_date, true)}</small>
							</div>
						</div>
					</div>
				</div>
				<img class="img-fluid" src="{$smarty.const.FH_URL}{$oneProject.image}" alt="{$oneProject.title}">
				<div class="card-body">
					<p class="line-clamp-3">{$oneProject.intro}</p>
					<div class="d-flex gap-2">
						<span class="badge bg-label-primary">Chung cư</span>
						<span class="badge bg-label-primary">Shophouse</span>
						<span class="badge bg-label-primary">Biệt thự</span>
					</div>
				</div>
			</div>
			{/if}
			<div class="card no-shadow mb-4">
				<div class="card-body">
					{if $show eq 'project' || ($show eq 'block' and $block_type eq $smarty.const._BLOCK_TYPE_LOWFLOOR_SALE)}
					<small class="text-muted text-uppercase">Phân khu</small>
					<ul class="list-unstyled mb-4 mt-3">
						{foreach name=i from=$list_blocks item = _oBlock}
						<li class="d-flex py-2{if !$smarty.foreach.i.last} border-bottom{/if} align-items-center">
							<i class="bx bx-chevron-right me-1"></i>
							<a class="text-link fs-6" href="{$clsProject->getLinkBl($project_id, $_oBlock.property_id)}">Phân khu {$_oBlock.title}</a>
						</li>
						{/foreach}
					</ul>
					{/if}
					{if ($show eq 'block' and $block_type eq $smarty.const._BLOCK_TYPE_HIGHLEVEL_SALE) || $show eq 'building'}
					<small class="text-muted text-uppercase">Tòa nhà</small>
					<ul class="list-unstyled mb-4 mt-3">
						{foreach name=i from=$list_buildings item = _oBuilding}
						<li class="d-flex py-2{if !$smarty.foreach.i.last} border-bottom{/if} align-items-center">
							<i class="bx bx-chevron-right me-1"></i>
							<a class="text-link fs-6" href="{$clsProject->getLinkBu($project_id, $block_id, $_oBuilding.property_id)}">Tòa nhà {$_oBuilding.title}</a>
						</li>
						{/foreach}
					</ul>
					{/if}
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-8 col-xxl-9 order-1 order-xxl-2 mb-4 mb-md-0 mb-lg-0 mb-xxl-0">
			<div class="card no-shadow">
				<div class="card-header">

					{if $show eq 'project'}
						<h3 class="fs-30 fw-bold mb-1">{$oneProject.title}</h3>
						<p class="text-muted mb-0">
							<i class="material-icons-outlined">location_on</i>
							{$more_information.address}
						</p>
					{elseif $show eq 'block'}
						<h3 class="fs-30 fw-bold mb-1">Phân khu {$oneBlock.title}</h3>
					{else}
						<h3 class="fs-30 fw-bold mb-1">Tòa nhà {$oneBuilding.title}</h3>
					{/if}
				</div>
				<div class="card-body">
					<div class="mb-3">
						{if !empty($list_props)}
						<div class="list-results d-flex flex-wrap gap-2">
							{foreach from=$list_props item = _oResult}
							<div class="badge bg-label-primary">
								<a class="fs-6" data-fancybox{if $_oResult.is_driver eq '1'} data-type="iframe"{/if} href="{$_oResult.link}">{$_oResult.title}</a>
							</div>
							{/foreach}
						</div>
						{/if}
					</div>
					{if $show eq 'project'}
						<h1 class="my-4">Giới thiệu {$oneProject.title}</h1>
					{elseif $show eq 'block'}
						<h1 class="my-4">Giới thiệu {$oneBlock.title}</h1>
					{else}
						<h1 class="my-4">Giới thiệu {$oneBuilding.title}</h1>
					{/if}
					<div class="tinyContent">
						{if $show eq 'project'}
							{$oneProject.content}
						{elseif $show eq 'block'}
							{$oneBlock.intro}
						{else}
							{$oneBuilding.intro}
						{/if}
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>