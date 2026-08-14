{if $mod eq 'home' && $act ne 'share'}
{if !empty($lstBilling)}
	{assign var = box_col value = 'col-md-4'}
{else}
	{assign var = box_col value = 'col-md-6'}
{/if}
<div class="form-row mb-2">
	{if !empty($lstBilling)}
		<div class="col-12 col-md-4">
			<div class="card h-100" style="background-image: url('{$clsISO->getImageWH('/application/themes/images/bg_mileston.png',535,500)}')">
				<div class="card-header">
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="card-title title_box mb-0">Milestone {$smarty.now|date_format:"%Y"}</h5>
						{if !empty($_oBox.link)}
						<a href="/m-{$smarty.now|date_format:'%Y'}/" class="btn btn-icon btn-sm btn-link rounded-pill">
							<i class="bx bx-link-external text-fs-14 text-muted"></i>
						</a>
						{/if}
					</div>
				</div>
				<div class="card-body d-flex align-items-center">
					<div id="home_{$_oKey}" class="owl owl-carousel owl-share" data-lg-slide="2" data-md-slide="1" 
						data-sm-slide="1" data-loop="1" data-nav="1" data-margin="10" >
						{foreach name=i from=$lstBilling item = _oItem}
							{assign var=oneStaff value=$_oItem.oneStaff }
							<div class="item_mileston item_small border-double text-dark h-100">
								<div class="form-row">
									<div class="col-8 flex-fill">
										<div class="item_header d-flex align-items-center">
											<div class="billing_code px-2 py-1 fw-bold">#{$clsISO->parseNumber2($_oItem.bill_number)}</div>
											<div class="time_deposit fw-bold text-black fst-italic">{$_oItem.deposit_date|date_format:"%d/%m/%Y"}</div>
										</div>
										<div class="item_body">
											<div class="staff_info d-flex align-items-center">
												<div class="border-double img_avatar rounded-pill overflow-hidden"><img src="{$clsProfile->getAvatar($_oItem.staff_id,$oneStaff,70,70)}" alt="" class="w-100 h-100 object-fit-cover" ></div>
												<div class="flex-fill">
													<h3 class="name limit_1line" title="{$oneStaff.full_name}">{$oneStaff.full_name}</h3>
													{if !empty($oneStaff.more_information.department_name)}
													<p class="role mb-0">{$oneStaff.more_information.department_name}</p>
													{/if}
												</div>
											</div>
											<div class="bill_info">
												<div class="bill_item">
													<span class="label_item">Dự án:</span>
													<strong class="">{$_oItem.project_name}</strong>
												</div>
												{if !empty($_oItem.bedroom)}
												<div class="bill_item">
													<span class="label_item">Loại căn:</span>
													<strong class="">{$_oItem.bedroom}</strong>
												</div>
												{/if}
												{if !empty($_oItem.type_villa)}
												<div class="bill_item">
													<span class="label_item">Loại căn:</span>
													<strong class="">{$_oItem.type_villa}</strong>
												</div>
												{/if}
												{if !empty($_oItem.total_price)}
												<div class="bill_item">
													<span class="label_item">Doanh số:</span>
													<strong class="">{$_oItem.total_price}</strong>
												</div>
												{/if}
											</div>
										</div>
									</div>
									{if !empty($_oItem.image_poster)}
									<div class="col-4">
										<div class="box_poster">
										<img src="{$_oItem.image_poster}" alt="" class="w-100 object-fit-cover rounded-3 border-double image_poster" data-fancybox="poster" data-src="{$_oItem.image_poster}" data-caption='<div class="staff_info d-flex justify-content-center align-items-center gap-2" loading="lazy">
											<div class="avatar avatar-md rounded-pill overflow-hidden"><img src="{$clsProfile->getAvatar($_oItem.staff_id,$oneStaff)}" alt="" class="w-100 h-100 object-fit-cover"></div>
											<div class="text-left">
												<h3 class="name mb-1">{$clsProfile->getfullname($_oItem.staff_id,$oneStaff)}</h3>
												{if !empty($oneStaff.more_information.department_name)}<p class="role mb-0">{$oneStaff.more_information.department_name}</p>{/if}
											</div>
										</div>'>
										</div>
									</div>
									{/if}
								</div>
							</div>	
						{/foreach}
					</div>
				</div>
			</div>		
		</div>
	{/if}
	{foreach from=$list_boxs key = _oKey item = _oBox}
	{assign var = list_images value = $_oBox.images}
	<div class="col-12 {$box_col}">
		<div class="card h-100">
			<div class="card-header">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="card-title mb-0">{$_oBox.title}</h5>
					{if !empty($_oBox.link)}
					<a href="{$_oBox.link}" class="btn btn-icon btn-sm btn-link rounded-pill">
						<i class="bx bx-link-external text-fs-14 text-muted"></i>
					</a>
					{/if}
				</div>
			</div>
			<div class="card-body">
				{if !empty($list_images)}
				<div id="home_{$_oKey}" class="owl_honor owl-carousel">
					{assign var=number_image value=1}
					{foreach name=i from=$list_images item = _oImage}
						<div class="image-wrap rounded-1 overflow-hidden" data-fancybox="image" data-src="{$_oImage}&sz=w1500">
							<div class="w-100 img-home-honor" style="background-image:url({$_oImage}&sz=w500)"></div>
						</div>
						{math equation="x+1" x=$number_image assign="number_image"}
						{if $number_image gt 20}
							{break}
						{/if}
					{/foreach}
				</div>
				{else}
				{* Chưa cấu hình thư mục Drive hoặc thư mục rỗng — dùng empty state chung .dbx-empty (sky-theme.css). *}
				<div class="dbx-empty">
					<span class="dbx-empty__ic"><i class="bx {$_oBox.icon|escape}"></i></span>
					<div class="dbx-empty__t">{$_oBox.empty|escape}</div>
					<div class="dbx-empty__s">Ảnh sẽ hiển thị ở đây khi có cập nhật</div>
				</div>
				{/if}
			</div>
		</div>		
	</div>
	{/foreach}
</div>
{else}
<div class="awe__list-post awe__list-share"> 
	{foreach name=i from=$list_images item = _oImage}
		{if !empty($_oImage)}
		<div class="awe__post-item awe__share-item" >
			<div class="w-100 d-flex align-items-center justify-content-between mb-3 post_header">
				<div class="awe__post-profile d-flex" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$user_id}" data-toggle="webui-popover" data-trigger="hover" data-width="350">
					<div class="awe__post-avatar position-relative">
						<img class="rounded-pill" width="44" height="44" src="{$oneUser.avatar}" 
						onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" /> 
						{$clsProfile->get_icon_verified($user_id,$oneUser.moreinformation)}
					</div>
					<div class="awe__post-profile-body">
						<p class="awe__post-name">{$oneUser.name}</p>
						<div class="d-flex align-items-center">
							{if !empty($oneUser.level)}
							<span class="awe__post-level mr-2 text-muted">{$oneUser.level}</span>
							{/if}
							<span class="awe__post-star mr-2 text-muted">{$oneUser.html_star}</span>
						</div>
					</div>
				</div>
			</div>
			<div class="awe__post-item-body">
				<div class="awe__post-gallery gallery mb-2">
					<div  class="imgs-grid imgs-grid-1">
						<div class="imgs-grid-image">
							<div class="image-wrap" data-fancybox="image" data-src="{$_oImage}">
								<img class="w-100" src="{$_oImage}" alt="" title="" style="max-height:unset">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		{/if}
	{/foreach}
</div>
{/if}
{literal}
<style type="text/css">
	.img-home-honor{
		height:160px;
		background-repeat:no-repeat;
		background-size:cover;
		background-position:top;
	}
</style>
{/literal}