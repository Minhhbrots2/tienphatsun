<link rel="stylesheet" type="text/css" href="{$URL_JS}/daterangepicker/daterangepicker.css?v={$upd_version}" />
<script type="text/javascript" src="{$URL_JS}/daterangepicker/moment.min.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/daterangepicker/daterangepicker.js?v={$upd_version}"></script>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<div class="d-flex justify-content-between align-items-center py-2 mb-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1"><span>Danh sách tình trạng thông tin dự án</span></h4>
			<p class="text-muted mb-0">Hệ thống hỗ trợ bán hàng Ocean City</p>
		</div>
	</div>
	<div class="row">	
		{if !empty($arr_data)}
			{foreach from=$arr_data item=_oProject}
				{assign var=menuProject value=$_oProject.menu}
				{assign var=list_block value=$_oProject.list_block}
				<div class="col-12 col-md-3 col-xxl-3 mb-2">
					<div class="card no-shadow">
						<div class="card-body">
							<ul id="myUL" class="fs-14">
								<li>
									<div class="caret d-flex justify-content-between align-items-center">
										<div class="fw-bold fs-20 text-main flex-fill d-flex justify-content-between align-items-center">{$_oProject.project_name} <span class="text-muted fs-11"><strong class="{if $_oProject.total_success eq $_oProject.total}text-success{else}text-main{/if}">{$_oProject.total_success}</strong>/{$_oProject.total}</span></div>
									</div>
									
									<ul class="ul_parent nested active">
										{foreach from=$menuProject item=_oMenuProject}
											{assign var=menu_child_project value=$_oMenuProject.menu_child}
											{if !empty($menu_child_project)}
												<li>
													<span class="caret text-dark fs-16">{$_oMenuProject.cat_name}</span>
													<ul class="ul_parent project nested active">
														{foreach from=$menu_child_project key=key_menu_child item=val_menu_child}
															<li class="nested_child"><a href="{$val_menu_child.link_edit}" class="d-flex align-items-center justify-content-between w-100" target="_blank">{$val_menu_child.title} {if !empty($val_menu_child.status)}<i class='bx bx-check fs-24 fw-bold text-success'></i>{else}<i class='bx bx-x-circle fw-bold fs-24 text-danger' ></i>{/if}</a></li>
														{/foreach}
													</ul>
												</li> 
											{else}
												<li class="nested_child"><a href="{$_oMenuProject.link_edit}" class="d-flex align-items-center justify-content-between w-100" target="_blank"><span class="fs-16">{$_oMenuProject.cat_name}</span> {if !empty($_oMenuProject.status)}<i class='bx bx-check fs-24 fw-bold text-success'></i>{else}<i class='bx bx-x-circle fw-bold fs-24 text-danger' ></i>{/if}</a></li>
											{/if}
										{/foreach}
										{if !empty($list_block)}
											{foreach from=$list_block item=_oBlock}
												{assign var=menuBlock value=$_oBlock.menu}
												{assign var=list_building value=$_oBlock.list_building}
												<li>
													<span class="caret text-dark fs-16">{$_oBlock.block_name}</span>
													<ul class="ul_block ul_parent nested active">
														{foreach from=$menuBlock item=_oMenuBlock}
															{assign var=menu_child_block value=$_oMenuBlock.menu_child}
															{if !empty($menu_child_block)}
																<li>
																	<span class="caret">{$_oMenuBlock.cat_name}</span>
																	<ul class="nested active">
																		{foreach from=$menu_child_block key=key_menu_child item=val_menu_child}
																			<li class="nested_child"><a href="{$val_menu_child.link_edit}" class="d-flex align-items-center justify-content-between w-100" target="_blank">{$val_menu_child.title} {if !empty($val_menu_child.status)}<i class='bx bx-check fs-24 fw-bold text-success'></i>{else}<i class='bx bx-x-circle fw-bold fs-24 text-danger' ></i>{/if}</a></li>
																		{/foreach}
																	</ul>
																</li> 
															{else}
																<li class="nested_child"><a href="{$_oMenuBlock.link_edit}" class="d-flex align-items-center justify-content-between w-100" target="_blank"><span class="fs-16">{$_oMenuBlock.cat_name}</span> {if !empty($_oMenuBlock.status)}<i class='bx bx-check fs-24 fw-bold text-success'></i>{else}<i class='bx bx-x-circle fw-bold fs-24 text-danger' ></i>{/if}</a></li>
															{/if}
														{/foreach}
														
														{if !empty($list_building)}
															{foreach from=$list_building item=_oBuilding}
																{assign var=menuBuilding value=$_oBuilding.menu}
																<li>
																	<span class="caret text-dark fs-16">{$_oBuilding.building_name}</span>
																	<ul class="ul_parent nested active">
																		{foreach from=$menuBuilding item=_oMenuBuilding}
																			{assign var=menu_child_building value=$_oMenuBuilding.menu_child}
																			{if !empty($menu_child_building)}
																				<li>
																					<span class="caret">{$_oMenuBuilding.cat_name}</span>
																					<ul class="nested active">
																						{foreach from=$menu_child_building key=key_menu_child item=val_menu_child}
																							<li class="nested_child"><a href="{$val_menu_child.link_edit}" class="d-flex align-items-center justify-content-between w-100" target="_blank">{$val_menu_child.title} {if !empty($val_menu_child.status)}<i class='bx bx-check fs-24 fw-bold text-success'></i>{else}<i class='bx bx-x-circle fw-bold fs-24 text-danger' ></i>{/if}</a></li>
																						{/foreach}
																					</ul>
																				</li> 
																			{else}
																				<li class="nested_child"><a href="{$_oMenuBuilding.link_edit}" class="d-flex align-items-center justify-content-between w-100" target="_blank"><span class="fs-16">{$_oMenuBuilding.cat_name}</span> {if !empty($_oMenuBuilding.status)}<i class='bx bx-check fs-24 fw-bold text-success'></i>{else}<i class='bx bx-x-circle fw-bold fs-24 text-danger' ></i>{/if}</a></li>
																			{/if}
																		{/foreach}
																	</ul>
																</li>
															{/foreach}
														{/if}
													</ul>
												</li>
											{/foreach}
										{/if}
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>
			{/foreach}
		{/if}
	</div>
</div>
{literal}
<style>
	ul, #myUL {
		list-style-type: none;
	}
	#myUL {
		margin: 0;
		padding: 0;
	}
	.caret {
		cursor: pointer;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
	.caret::before {
		content: "\ea44";
		font-family: boxicons !important;
		color: black;
		display: inline-block;
		margin-right: 6px;
		font-size: 20px;
		font-weight:400
	}
	.caret.active::before {
		content: "\ebc0";
	}
	.caret-down::before {
		-ms-transform: rotate(90deg);
		-webkit-transform: rotate(90deg);
	  transform: rotate(90deg);  
	}
	.nested {
	  display: none;
	}
	.active {
	  display: block;
	}
	.nested.active {
		position: relative;
	}
	.nested.active>li:after, .nested.active>.nested_child:before, .ul_parent.active>li:before{		
		position: absolute;
		content: "";
		display: inline-block;
	}
	.nested.active>li:after {
		border-left: 1px solid;
		height: 100%;
		left: -23px;
		top: -10px;
	}
	.ul_parent.active:not(.project)>li:after {
		height: calc(100% + 28px);
		top: -13px;
	}
	
	.ul_parent.active:not(.project)>li:last-child:after {
		display: none;
	}
	.nested.active>.nested_child:before {
		width: 20px;
		border-top: 1px solid;
		left: -23px;
		top: 14px;
	}
	.nested.active>.nested_child,.ul_parent.active>li {
		position: relative;
	}
	.ul_parent.active>li:before {
		width: 20px;
		border-top: 1px solid;
		left: -23px;
		top: 15px;
	}
	.nested_child {
		display: flex;
		justify-content: space-between;
		align-items: center;
		/* padding-bottom: 0px; */
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		var toggler = document.getElementsByClassName("caret");
		var i;
		$(".caret").on("click",function(){
			$(this).toggleClass("active");
			$(this).next().toggleClass("active");
		});
	});
</script>
{/literal}