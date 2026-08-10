<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex justify-content-between flex-wrap align-items-center mb-3 gap-2">
		<div class="kYlZoryVmS">
			<h4 class="fw-bold mb-1">Truyền cảm hứng</h4>
			<i class="text-muted">Có <span class="text-main fw-bold">{$total_record}</span> video truyền cảm hứng tại {$smarty.const.BRAND_NAME}</i>
		</div>
		<div class="d-flex justify-content-end gap-2 {if $deviceType eq 'phone'}flex-fill{/if}">	
			<form action="" method="post" class="w-100">
				<input type="hidden" name="submit" value="search">
				<div class="search-block w-full d-flex align-item-center">
					<div class="input-group flex-nowrap">
						<div class="input-group input-group-merge">
							<span class="input-group-text"><i class="bx bx-search"></i></span>
							<input type="text" name="keyword" value="{$key_search}" class="form-control no-radius-right" placeholder="Nhập từ khoá">
						</div>
						<button type="submit" class="btn btn-icon btn-outline-default" >
							<i class="bx bx-search"></i>
						</button>
					</div>
				</div>
			</form>	
			<button class="btn btn-outline-primary text-nowrap {if $deviceType eq 'phone'}btn-sm{/if}" type="button" onclick="$Core.inspire.open_inspire(this,event)" training_id="0" >Đăng tải video</button>
		</div>
	</div>
	<div class="alert alert-warning fs-4 text-center">
		{if $deviceType eq 'phone'}
		<div class="d-flex flex-column justify-content-center lh-xs text-main">
			<div class="mb-2">
				<i class='bx bxs-quote-alt-left mt-n2'></i> 
				<i>Đầu tư vào tri thức luôn mang lại lợi nhuận cao nhất.</i>
				<i class='bx bxs-quote-alt-right'></i>
			</div>
			<span class="text-center text-muted text-fs-12">-- Benjamin Franklin --</span>
		</div>
		{else}
		<div class="d-flex justify-content-center text-main">
			<div class="d-inline-flex flex-column">
				<div class="d-flex gap-1 align-items-center">
					<i class='bx bxs-quote-alt-left mt-n2'></i> 
					<i>Đầu tư vào tri thức luôn mang lại lợi nhuận cao nhất.</i>
					<i class='bx bxs-quote-alt-right'></i>
				</div>
				<span class="text-right text-fs-12 text-muted">-- Benjamin Franklin</span>
			</div>
		</div>
		{/if}
	</div>
	<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 row-cols-xxl-5">
		{if !empty($lstTraining)}
			{foreach from=$lstTraining item=_oItem key=key name=i}
				{assign var=more_information value=$_oItem.more_information}
			<div class="col mb-4">
				<div class="item_training h-100 card no-shadow overflow-hidden" >
					{if $_oItem.user_id eq $profile_id}
						{if $_oItem.is_online eq 0}<span class="btn btn-lighter position-absolute zindex-1 left-0 top-0 fst-italic border btn-xs mt-1 ml-1 text-danger">Bản nháp</span>{/if}
						<div class="position-absolute top-0 right-0 zindex-2 p-2" >
							<div class="dropdown">
								<button type="button" class="btn_option btn p-0 dropdown-toggle hide-arrow text-white btn-icon btn-sm rounded-pill" data-bs-toggle="dropdown" aria-expanded="true">
									<i class="bx bx-dots-vertical-rounded"></i>
								</button>
								<div class="dropdown-menu dropdown-menu-end fs-14" data-popper-placement="bottom-end" style="width: 120px !important;min-width: 120px;">
									<a class="dropdown-item cursor-pointer px-2" onclick="$Core.inspire.open_inspire(this,event)" training_id="{$_oItem.training_id}"><i class="bx bx bx-edit-alt me-1"></i>Sửa</a>
									{if $_oItem.is_online eq 1}
										<a class="dropdown-item cursor-pointer px-2" onclick="$Core.inspire.update_field(this, event)" training_id="{$_oItem.training_id}" action="_trash" ><i class="bx bx-trash me-1"></i>Xóa</a>
									{else}
										<a class="dropdown-item cursor-pointer px-2" onclick="$Core.inspire.update_field(this, event)" training_id="{$_oItem.training_id}" action="_untrash"><i class='bx bx-revision me-1'></i>Khôi phục</a>
									{/if}
								</div>
							</div>
						</div>
					{/if}
					<div class="box_image image-scale cursor-pointer" onclick="$Core.inspire.log_inspire(this,event)" lesson_id="{$_oItem.lesson_id}" training_id="{$_oItem.training_id}" 
					 {if $more_information.data_video.type eq 'youtu.be'}
						data-fancybox="{$_oItem.training_id}" href="{$clsTraining->getLinkVideo($more_information.data_video)}"
				   {elseif $more_information.data_video.type eq 'video'} 
						data-fancybox="{$_oItem.training_id}" href="{$clsTraining->getLinkVideo($more_information.data_video)}" data-type="iframe"
				   {else}
						href="{$clsTraining->getLinkVideo($more_information.data_video)}" target="_blank"
				   {/if}
					 >
						<img class="w-100 object-fit-cover" src="{$clsISO->getUrlImageFH($_oItem.image,480,200)}" alt="{$_oItem.title}" width="340" height="200">
					</div>
					<div class="box_imfo">
						<div class="p-3 cursor-pointer" onclick="$Core.inspire.log_inspire(this,event)" lesson_id="{$_oItem.lesson_id}" training_id="{$_oItem.training_id}" 
						 	{if $more_information.data_video.type eq 'youtu.be'}
								data-fancybox="{$_oItem.training_id}" href="{$clsTraining->getLinkVideo($more_information.data_video)}"
						   {elseif $more_information.data_video.type eq 'video'} 
								data-fancybox="{$_oItem.training_id}" href="{$clsTraining->getLinkVideo($more_information.data_video)}" data-type="iframe"
						   {else}
								href="{$clsTraining->getLinkVideo($more_information.data_video)}" target="_blank"
						   {/if} >
							<h3 class="title_training mb-2 text-main limit_1line">{$_oItem.title}</h3>
							<div class="author">đăng bởi <strong>{$_oItem.author}</strong></div>
						</div>
						<div class="d-flex flex-wrap align-items-center justify-content-between p-3 border-top gap-2">
							<div class="d-flex flex-wrap align-items-center gap-2">
								<a href="javascript:void(0);" training_id="{$_oItem.training_id}" onClick="$Core.inspire.like(this,event)" class="inspire_like inspire_{$_oItem.training_id} text-dark">
									{if $clsTraining->checkLiked($_oItem.training_id, $_oItem)}
										{if $_oItem.total_liked gt 0}
											{$clsISO->makeIcon('bxs-heart text-main',$_oItem.total_liked)}
										{else}
											{$clsISO->makeIcon('bx-heart')}
										{/if}
									{else}
										{if $_oItem.total_liked gt 0}
											{$clsISO->makeIcon('bx-heart',$_oItem.total_liked)}
										{else}
											{$clsISO->makeIcon('bx-heart')}
										{/if}
									{/if}
								</a>
								<a class="mb-1"><i class="material-icons-outlined me-1">visibility</i>{$_oItem.total_view}</a>	
							</div>
							<div class="txt_like_{$_oItem.training_id}">
								{if !empty($_oItem.total_liked)}
									<a href="javascript:void(0)" data-url="/index.php?mod=training&act=load_list_like&training_id={$_oItem.training_id}" data-toggle="webui-popover" data-trigger="hover" data-width="300">
										{if $clsTraining->checkLiked($_oItem.training_id, $_oItem)}
											{if $_oItem.total_liked gt 1}
												Bạn và {$_oItem.total_liked - 1} người thích video này
											{else}
												Bạn đã thích video này
											{/if}
										{elseif !empty($_oItem.total_liked)}
											{$_oItem.total_liked} người thích video này
										{/if}
									</a>
								{/if}
							</div>
						</div>
					</div>
				</div>
			</div>
			{/foreach}
		{else}
		<div class="empty w-100 rounded-3">
			<div class="p-5 text-center bg-white">
				<img src="{$URL_IMAGES}/listing-empty.svg" />
				<p>Danh sách trống</p>
			</div>
		</div>
		{/if}
	</div>	
	{if !empty($html_pager)}
		<div class="pagination justify-content-center">{$html_pager}</div>
	{/if}
</div>