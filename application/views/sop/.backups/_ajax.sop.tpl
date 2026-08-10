{if $_ss_view eq 'grid' and $type_list eq 'publish'}
	{if !empty($list_sop)}
		{foreach from = $list_sop item = _oSop}
			{assign var = sop_id value = $_oSop.sop_id}
			{assign var = more_information value = $_oSop.more_information}
			{if $deviceType eq 'phone'}
				<div class="awe__product-item overflow-hidden cursor-pointer d-flex" onClick="$Core.sop.open_sop(this, event)" 
				stock_code="{$clsSop->getCode($_oSop)}" stock_id="{$_oSop.stock_id}" sop_id="{$sop_id}">
					<div class="awe__product-thumb position-relative">
						<div style="background-image:url({if $_oSop.has_img eq '1'}{$clsISO->resizeImageFromUrl($_oSop.image, 300,200)}{else}{$_oSop.image}{/if})" class="w-100 h-100 ui-bg-cover"></div>
						<div class="box_owner_badge d-flex gap-1">
							{if !empty($_oSop.label_owner)}
								{$_oSop.label_owner}
							{elseif !empty($_oSop.label_status)}
								{$_oSop.label_status}
							{/if}
						</div>
					</div>
					<div class="awe__product-body">
						<h3 class="awe__product-title">
							<a href="{$_link}" class="awe__product-link fw-semibold limit_2line" title="{$_oSop.title}">{$_oSop.title}</a>
						</h3>
						<div class="table-item hidden-xs">
							<div class="d-flex py-1 text-nowrap gap-2">
								{if !empty($_oSop.bedroom) || !empty($more_information.bedroom_num)}
									<div class="metadata-item">
										<div class="mb-0 fs-13 d-flex align-items-center">
											<i class="re__icon-bedroom--sm mr-1"></i>
											<span class="text-main">{if $_oSop.bedroom}{$_oSop.bedroom}{else}{$more_information.bedroom_num}PN{/if}</span>
										</div>
									</div>
								{/if}
								{if !empty($more_information.DT_TT)}
									<div class="metadata-item">
										<div class="mb-0 fs-13 d-flex align-items-center">
											<i class="re__icon-size--sm mr-1"></i>
											<span class="text-main">{$more_information.DT_TT}m<sup>2</sup></span>
										</div>
									</div>
								{/if}
								<div class="metadata-item">
									<div class="mb-0 fs-13 d-flex align-items-center">
										<i class="re__icon-money--sm mr-1"></i>
										<strong class="text-main">{$clsISO->shortNumber($_oSop.price,2)}</strong>
									</div>
								</div>
							</div>
							{if !empty($_oSop.location)}
							<div class="d-flex align-items-center text-nowrap">
								<i class="re__icon-location--sm mr-1"></i>
								<span class="text-main">{$_oSop.location}</span>
							</div>
							{/if}
						</div>
					</div>
				</div>
			{else}
				<div class="col-12 col-sm-6 col-lg-4 col-xxl-4 col-xxxl-3 awe__sop-item awe__sop-item-{$sop_id}">
					<div class="awe__sop-content">
						<div class="awe__sop-compact awe__sop-compact-web">
							<div class="awe__sop-media position-relative">
								<div class="box_owner_badge d-flex gap-1">
									{if !empty($_oSop.label_owner)}
										{$_oSop.label_owner}
									{/if}
									{if !empty($_oSop.label_status)}
										{$_oSop.label_status}
									{/if}
								</div>
								{if !empty($_oSop.images)}
								<div id="slick_slider_{$clsISO->getUniqid()}" class="slick-slider awe__sop-slider">
									{foreach name=k from=$_oSop.images item = _oImg}
									{if $smarty.foreach.k.index == 8}
										{break}
									{/if}
									<div class="slick-slide position-relative cursor-pointer" onClick="$Core.sop.open_sop(this, event)" 
										stock_code="{$clsSop->getCode($_oSop)}" stock_id="{$_oSop.stock_id}" sop_id="{$_oSop.sop_id}">
										<img class="awe__sop-slider-overlay position-absolute zindex-1" src="{$clsISO->resizeImageFromUrl($_oImg,0,300)}" />
										<div class="awe__sop-slider-image w-100 h-100 d-flex justify-content-center align-items-center position-relative zindex-2">
											<img src="{$clsISO->resizeImageFromUrl($_oImg,0,250)}" alt="{$_oSop.title}" /> 
										</div>
									</div>
									{/foreach}
								</div>
								{else}
								<div class="awe__sop-image cursor-pointer" onClick="$Core.sop.open_sop(this, event)" 
									stock_code="{$clsSop->getCode($_oSop)}" stock_id="{$_oSop.stock_id}" sop_id="{$_oSop.sop_id}">
									<img class="awe__sop-img" src="{$URL_IMAGES}/no-image.jpg" />
								</div>
								{/if}
								<a onclick="javascript:$Core.sop.handleLike(this, event)" data-toggle="ripple" id="like_{$_oSop.sop_id}" sop_id="{$_oSop.sop_id}" holderG="list" data-bs-toggle="tooltip" data-placement="bottom" class="like {if $_oSop.liked eq 1}liked{/if}" {if $_oSop.liked eq 1}title="Bỏ thích"{else}title="Thích"{/if}><i class="fa fa-heart-o" aria-hidden="true"></i></a>
								{if $_oSop.having_dq eq 1}
								<div class="exclusive position-absolute p-1 rounded-pill">
									<div class="exclusive_text py-1 px-2 rounded-pill fs-11 text-center">Độc quyền</div>
								</div>
								{/if}
								{if $_oSop.is_solded eq 1}
									<div class="box_solded position-absolute p-1 rounded-pill">
										<div class="is_solded_text py-1 px-1 rounded-pill fs-11 text-center">Đã bán</div>
									</div>
								{/if}
							</div>
							<div class="awe__sop-body pointer-cursor" onClick="$Core.sop.open_sop(this, event)" 
								 stock_code="{$clsSop->getCode($_oSop)}" stock_id="{$_oSop.stock_id}" sop_id="{$_oSop.sop_id}">
								<h3 class="awe__sop-title mb-1">
									{if $_oSop.is_locked eq '1'} 
									<i data-bs-toggle="tooltip" data-bs-trigger="hover" title="Căn đang khóa chờ chốt, vui lòng liên hệ với chủ nhà để có thêm thông tin chi tiết" class="material-icons-outlined fs-small text-danger">lock</i>
									{/if}
									<a href="javascript:void(0)" class="awe__sop-link fs-5 text-dark" title="{$_title}">{$_oSop.title}</a>
								</h3>
								{if !empty($_oSop.location)}
								<div class="d-flex align-items-center fs-12 awe__sop-location py-1">
									<i class="re__icon-location--sm mr-1"></i>
									<span class="text-main">
										{$_oSop.location}
									</span>
								</div>
								{/if}
								<div class="d-flex align-items-center awe__sop-meta awe__sop-config mb-1">
									{if !empty($_oSop.bedroom) || !empty($more_information.bedroom_num)}
										<div class="mr-2 text_ellipsis d-flex align-items-center">
											<i class="re__icon-bedroom--sm mr-1"></i>
											<span class="text-main">{if $_oSop.bedroom}{$_oSop.bedroom}{else}{$more_information.bedroom_num}PN{/if}</span>
										</div>
									{/if}
									{if !empty($more_information.bathroom_num)}
										<div class="mr-2 text_ellipsis d-flex align-items-center">
											<i class='bx bx-bath mr-1 fs-16'></i>
											<span class="text-main">{$more_information.bathroom_num}PT</span>
										</div>
									{/if}
									{if !empty($more_information.DT_TT)}
										<div class="mr-2 text_ellipsis d-flex align-items-center">
											<i class="re__icon-size--sm mr-1"></i>
											<span class="text-main">{$more_information.DT_TT}m<sup>2</sup></span>
										</div>
									{/if}
									{if !empty($more_information.home_direction)}
										<div class="mr-2 text_ellipsis d-flex align-items-center">
											<i class="re__icon-ying-yang--xl mr-1"></i>
											<span class="text-main">{$_oSop.home_direction}</span>
										</div>
									{/if}
									
								</div>  
								<div class="d-flex align-items-center justify-content-between awe__sop-meta awe__product-config">
									<div class="d-flex align-items-center fw-bold text_ellipsis">
										<i class="re__icon-money--sm mr-1"></i>
										<span class="text-main fs-18">{$clsISO->shortNumber($_oSop.price,2)}</span>
										{if $_oSop.is_verified}
										<span class="pl-2">
											<svg data-bs-toggle="tooltip" title="Đã xác minh" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" class="Property_verifiedIcon__6IIBo">
												<path d="M8.12 15.23c-.19 0-.38-.07-.53-.22l-1.65-1.65H3.62c-.41 0-.75-.34-.75-.75v-2.33L1.22 8.65a.75.75 0 0 1 0-1.06l1.65-1.65V3.61c0-.41.34-.75.75-.75h2.33l1.64-1.64c.29-.29.77-.29 1.06 0l1.65 1.65h2.33c.41 0 .75.34.75.75v2.33l1.65 1.65c.29.29.29.77 0 1.06l-1.65 1.65v2.33c0 .41-.34.75-.75.75H10.3l-1.65 1.65c-.15.15-.34.19-.53.19Zm-3.75-3.36h1.89c.2 0 .39.08.53.22l1.33 1.33 1.33-1.33a.75.75 0 0 1 .53-.22h1.89V9.98c0-.2.08-.39.22-.53l1.33-1.33-1.33-1.33a.75.75 0 0 1-.22-.53V4.37H9.98a.75.75 0 0 1-.53-.22L8.12 2.82 6.79 4.15a.75.75 0 0 1-.53.22H4.37v1.89c0 .2-.08.39-.22.53L2.82 8.12l1.33 1.33c.14.14.22.33.22.53v1.89Zm3.99-2.03 2.38-2.38c.29-.29.29-.77 0-1.06a.754.754 0 0 0-1.06 0L7.83 8.25l-.9-.9a.754.754 0 0 0-1.06 0c-.29.29-.29.77 0 1.06L7.3 9.84c.15.15.34.22.53.22s.38-.07.53-.22Z" fill="rgb(9,121,54)"></path>
											</svg>
										</span>
										{/if}
									</div>
									<span class="text-muted">
										<i class="material-icons-outlined">visibility</i>
										{$clsISO->formatNumber($more_information.number_view)}
									</span>
									<div class="awe__sop-date fs-11 text-muted">
										<i class="material-icons-outlined">update</i>
										{$clsISO->getTimeAgo($_oSop.upd_date)}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			{/if}
		{/foreach}
	{else}
		<div class="empty">
			<div class="p-5 text-center">
				<img src="{$URL_IMAGES}/listing-empty.svg" />
				<p>Không có kết quả nào phù hợp</p>
			</div>
		</div>
	{/if}
{else}
	{if !empty($list_sop)}
		{foreach from =$list_sop item = _oSop}
		{assign var = sop_id value = $_oSop.sop_id}
		{assign var = more_information value = $_oSop.more_information}
		<tr class="awe__sop-item awe__sop-item-{$sop_id} text-nowrap{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'} deleted{/if}">
			<td class="text-left">
				<a class="fw-bold" href="javascript:void(0)" onClick="$Core.sop.open_sop(this, event)" 
					stock_code="{$clsSop->getCode($_oSop)}" sop_id="{$_oSop.sop_id}" {$type_list}>
					{if $type_list eq 'publish'}
						{$clsSop->getCode($_oSop)}
					{else}
						{$_oSop.stock_code}
					{/if}
				 </a>
				 <span class="sop__icon-{$sop_id}">
					{$clsSop->getIcon($sop_id, $type_list)}
				</span>
			</td>
			{if $deviceType eq 'phone'}
			<td>
				{if !empty($_oSop.bedroom) || !empty($more_information.bedroom_num)}
					{if $_oSop.bedroom}{$_oSop.bedroom}{else}{$more_information.bedroom_num}PN{/if}
				{else}
					-
				{/if}
				{if !empty($more_information.DT_TT)}
					/{$more_information.DT_TT}<span class="text-muted">m<sup>2</sup></span>
				{else}
					-
				{/if}
			</td>
			<td>
				{if !empty($_oSop.price)}
					<strong class="fw-bold text-main">{$clsISO->shortNumber($_oSop.price,2)}</strong>
				{else}
					Check
				{/if}
			</td>
			{else}
			<td class="text-left">
				{if !empty($more_information.price_negotiable)}
					<a href="https://zalo.me/{$_oSop.contact_phone}" type="zalo" target="_blank" class="d-none d-xxl-block fw-bold text-main">--</a>
				{else}
					<span class="d-none d-xxl-block fw-bold text-main">
						{$clsISO->priceFormat($_oSop.price)}đ
					</span>
					<span class="d-none d-md-block d-xxl-none fw-bold text-main">
						{$clsISO->shortNumber($_oSop.price,2)}
					</span>
				{/if}
			</td>
			{if $type_list eq 'manager'}
				<td>
					{if !empty($_oSop.bedroom) || !empty($more_information.bedroom_num)}
						{if $_oSop.bedroom}{$_oSop.bedroom}{else}{$more_information.bedroom_num}PN{/if}
					{/if} / 
					{if !empty($more_information.DT_TT)}
						{$more_information.DT_TT}m<sup>2</sup>
					{/if}
				</td>
			{else}
				<td>
					{if !empty($more_information.DT_TT)}
						{$more_information.DT_TT}m<sup>2</sup>
					{else}
						-
					{/if}
				</td>
				<td>
					{if !empty($_oSop.bedroom) || !empty($more_information.bedroom_num)}
						{if $_oSop.bedroom}{$_oSop.bedroom}{else}{$more_information.bedroom_num}PN{/if}
					{else}
						-
					{/if}
				</td>
			{/if}
			<td>
				{if !empty($_oSop.home_direction)}
					{$_oSop.home_direction}
				{else}
					-
				{/if}
			</td>
			<td>{$clsSop->getRangeFloor($_oSop.floor)}</td>
			<td>{$_oSop.contact_name}</td>
			<td>{$clsProfile->mask($_oSop.contact_phone, 1)}</td>
			{/if}
			{if $type_list eq 'manager'}
			<td class="text-center">
				<label class="switch">
				  <input type="checkbox" onChange="$Core.sop.verified(this, event)" 
					sop_id="{$sop_id}"{if $_oSop.is_verified eq '1'} checked="checked"{/if} value="1" />
				  <span class="slider round"></span>
				</label>
			</td>
			<td class="text-center">
				<label class="switch">
				  <input type="checkbox" onChange="$Core.sop.approved(this, event)" 
					sop_id="{$sop_id}"{if $_oSop.is_online eq '1'} checked="checked"{/if} value="1" />
				  <span class="slider round"></span>
				</label>
			</td>
			<!-- <td class="text-center">
				<div class="switch-toggle switch-3 switch-candy">
					<input id="on_{$sop_id}" title="Duyệt" name="status_{$sop_id}" onChange="$Core.sop.approved(this, event)" tp="agree" sop_id="{$sop_id}" type="radio"{if $_oSop.is_online eq '1'} checked{/if} value="{$sop_id}" />
					<label for="on_{$sop_id}" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Duyệt"><i class="bx bx-check"></i></label>
					<input id="na_{$sop_id}" name="status_{$sop_id}" onChange="$Core.sop.approved(this, event)" sop_id="{$sop_id}" tp="wait" type="radio"{if $_oSop.is_online eq '0'} checked{/if} value="{$sop_id}" />
					<label for="na_{$sop_id}"></label>
					<input id="off_{$sop_id}" title="Không duyệt" onChange="$Core.sop.approved(this, event)" sop_id="{$sop_id}" tp="refuse" name="status_{$sop_id}" type="radio"{if $_oSop.is_online eq '2'} checked{/if} value="{$sop_id}" />
					<label for="off_{$sop_id}" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Không duyệt"><i class="bx bx-x"></i></label>
				</div>
			</td> -->
			{/if}
			{if $type_list eq 'manager' or $type_list eq 'me'}
			<td class="text-center">								
				<button class="btn btn-icon btn_show_log_{$_oSop.sop_id}" title="Thống kê" data-bs-toggle="tooltip" onClick="$Core.sop.showLog(this,event)" sop_id="{$_oSop.sop_id}"><i class='bx bx-bar-chart-alt-2'></i></button>	
			</td>
			<td class="text-center">
				<div class="dropdown">
					<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true">
						<i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu" data-popper-placement="bottom-start">
						<a class="dropdown-item" href="{$PCMS_URL}/cn/edit/{$sop_id}?return_url={$ret_url}" 
						class="btn flex-fill btn-outline-default"><i class="material-icons-outlined">border_color</i> Sửa</a>
						{if isset($more_information.is_locked) && $more_information.is_locked eq '1'}
						<a class="dropdown-item sop__menu-lock-{$sop_id}{if $clsSop->checkSolded($sop_id, $more_information) eq '1' || $clsSop->checkDeleted($sop_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.sop.mark_lock(this, event)" sop_id="{$sop_id}" href="javascript:void(0);"><i class="material-icons-outlined">lock_open</i> Mở Khoá</a>
						{else}
						<a class="dropdown-item sop__menu-lock-{$sop_id}{if $clsSop->checkSolded($sop_id, $more_information) eq '1' || $clsSop->checkDeleted($sop_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.sop.mark_lock(this, event)" sop_id="{$sop_id}" href="javascript:void(0);"><i class="material-icons-outlined">lock</i> Khoá</a>
						{/if}
						{if isset($more_information.is_solded) && $more_information.is_solded eq '1'}
						<a class="dropdown-item sop__menu-sold-{$sop_id}{if $clsSop->checkDeleted($sop_id, $more_information) eq '1'} preventDefault{/if}" sop_id="{$sop_id}" onClick="$Core.sop.mark_sold(this, event)" href="javascript:void(0);">
							<i class="material-icons-outlined">add_business</i> Mở bán</a>
						{else}
						<a class="dropdown-item sop__menu-sold-{$sop_id}{if $clsSop->checkDeleted($sop_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.sop.mark_sold(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
							<i class="material-icons-outlined">storefront</i> Báo bán</a></li>
						{/if}
						{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'}
						<a class="dropdown-item sop__menu-delete-{$sop_id}{if $_oSop.is_locked eq '1'} preventDefault{/if}" onClick="$Core.sop.delete(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
							<i class="material-icons-outlined">settings_backup_restore</i> Khôi phục</a>
						{else}
						<a class="dropdown-item sop__menu-delete-{$sop_id}{if $_oSop.is_locked eq '1'} preventDefault{/if}" onClick="$Core.sop.delete(this, event)" sop_id="{$sop_id}" href="javascript:void(0);">
							<i class="material-icons-outlined">delete</i> Xoá</a>
						{/if}
					</div>
				</div>
			</td>
			{/if}
		</tr>
		{/foreach}
	{else}
		<tr class="nohover">
			<td class="text-center" colspan="20">
				<div class="">
					<img src="{$URL_IMAGES}/listing-empty.svg" />
					<p>Không có kết quả nào phù hợp</p>
				</div>
			</td>
		</tr>
	{/if}
{/if}
