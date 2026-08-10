{if !empty($info_agency.head_office) || !empty($branch_office) || !empty($project)}
	<div class="scroll-y-auto-hover" style="max-height: 300px">
		<div class="position-sticky top-0 p-2 bg-white zindex-1">
			<h3 class="text-upper fs-16 fw-bold mb-0 text-main">{$oneAgency.title} {if !empty($oneAgency.title_vn)}({$oneAgency.title_vn}){/if}</h3>
		</div>
		<ul class="custom-list list-unstyled">
			{if !empty($info_agency.head_office)}
				<li class="border rounded-2 p-2 mb-2">
					<div class="">
						<h6 class="text-info fw-semibold mb-2">Trụ sở</h6>
						<div class="d-flex gap-1 align-items-start mb-1 px-2">
						🏢 {$info_agency.head_office}
						</div>
					</div>
				</li>
			{/if}
			{if !empty($branch_office)}
				<li class="border rounded-2 p-2 mb-2">
					<div class="">
						<h6 class="text-info fw-semibold mb-2">Chi nhánh</h6>
						<div class="px-2">
						{foreach from=$branch_office item=_branch key=key name=i}
							<div class="d-flex gap-1 align-items-start mb-1">👉 {$_branch}</div>
						{/foreach}
						</div>
					</div>
				</li>	
			{/if}
			{if !empty($project)}
				<li class="border rounded-2 p-2 mb-2">
					<div class="">
						<h6 class="text-info fw-semibold mb-2">Dự án</h6>
						<div>
							{foreach from=$project item=_oProject key=key name=i}
								<div class="position-relative border mb-2 p-2 rounded-1">
									<div class="d-flex flex-column">
										<h3 class="text-fs-16 xs:text-fs-14 mb-2">📁 {$_oProject.title}</h3>
										<div class="d-flex flex-column gap-1">
											<a class="d-flex align-items-center text-nowrap gap-1 text-link" target="_blank" href="javascript:void(0)"><span class="text-fs-13">🏙 GĐDA: {$_oProject.project_manager}</span>
											</a>
											<a class="d-flex align-items-center text-nowrap gap-1 text-link" target="_blank" href="javascript:void(0)"><span class="text-fs-13">📯 Admin: {$_oProject.project_admin}</span>
											</a>
										</div>
									</div>
								</div>
							{/foreach}	
						</div>
					</div>
				</li>	
			{/if}
		</ul>
	</div>
{/if}