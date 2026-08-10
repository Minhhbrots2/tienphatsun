<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2">
		<div class="p__left">
			<h4 class="fw-bold mb-1">Khóa học đào tạo</h4>
			<span class="text-muted">Các khóa học đào tạo tại {$smarty.const.BRAND_NAME}</span>
		</div>
		<div class="p__right d-flex">			
			<div class="d-flex justify-content-end">
				<div class="btn-group">
					<button type="button" class="btn btn-icon btn-outline-default dropdown-toggle hide-arrow" 
					data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="true">
						<i class="bx bx-search"></i>
					</button>
					<div class="dropdown-menu dropdown-menu-end w-px-300" data-popper-placement="bottom-end">
						<div class="p-4">
							<div class="input-group input-group-merge mb-3">
								<span class="input-group-text"><i class="bx bx-search"></i></span>
								<input type="text" class="form-control search_field" data-field="keySearch" placeholder="Tìm kiếm" />
							</div>
							<div class="form-group">
								<button type="button" class="btn btn-success" onClick="$Core.training.do_search(this, event)">
									<i class="bx bx-search"></i> Tìm kiếm
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
	<div class="row">
		{foreach from=$lstTraining item=_oItem key=key name=i}
			<div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-4">
				<div class="item_training h-100 card no-shadow overflow-hidden" onclick="$Core.training.open(this,event)" training_id="{$_oItem.training_id}">
					<div class="box_image image-scale">
						<img class="w-100" src="{$_oItem.image}" alt="{$_oItem.title}" width="340" height="250">
					</div>
					<div class="box_imfo">
						<div class="p-3">
							<div class="author mb-2">{$_oItem.author}</div>
							<h3 class="title_training mb-0"><a class="text-dark limit_1line" href="{$clsTraining->getLink($_oItem.training_id,$_oItem)}" title="{$_oItem.title}">{$_oItem.title}</a></h3>
						</div>
						<div class="d-flex flex-wrap justify-content-between p-3 border-top gap-2">						
							<div class=""><i class='bx bx-video me-1' ></i>{$_oItem.total_lesson} bài học</div>	
							<div class="mb-1"><span data-url="/index.php?mod=training&act=load_list_participants&training_id={$_oItem.training_id}" data-toggle="webui-popover" data-trigger="hover" data-width="400" class="awe__post-profile" ><i class='bx bx-user mr-1 align-top'></i>{$_oItem.total_profile} </span></div>
							<div class="time">Thời lượng: {$clsISO->convertTimeMinute($_oItem.time_training)}</div>
						</div>
					</div>
				</div>
			</div>
		{/foreach}
	</div>
	{if !empty($html_pager)}
		<div class="pagination justify-content-center">{$html_pager}</div>
	{/if}
</div>