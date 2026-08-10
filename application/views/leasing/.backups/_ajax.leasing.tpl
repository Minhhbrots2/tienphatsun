{if $_ss_view eq 'grid' and $type_list eq 'publish' and $deviceType ne 'phone'}
	{if !empty($list_leasing)}
		{foreach from = $list_leasing item = _oLeasing}
			{assign var = more_information value = $_oLeasing.more_information}
			{if $clsISO->checkItemInArray($_oLeasing.leasing_id,$like_leasing)}
				{assign var=liked value=1}
			{else}
				{assign var=liked value=0}
			{/if}
			<div class="col-12 col-sm-6 col-md-4 col-xxl-3 awe__leasing-item">
				<div class="awe__leasing-content">
					<div class="awe__leasing-compact awe__leasing-compact-web">
						<div class="awe__leasing-media position-relative">
							<div class="box_owner_badge d-flex gap-1">
								{if !empty($_oLeasing.label_owner)}
									{$_oLeasing.label_owner}
								{/if}
								{if !empty($_oLeasing.label_status)}
									{$_oLeasing.label_status}
								{/if}
							</div>
							{if !empty($_oLeasing.images)}
							<div id="slick_slider_{$clsISO->getUniqid()}" class="slick-slider awe__leasing-slider">
								{foreach name=k from=$_oLeasing.images item = _oImg}
								{if $smarty.foreach.k.index == 8}
									{break}
								{/if}
								<div class="slick-slide position-relative  cursor-pointer" onClick="$Core.leasing.open_leasing(this, event)" 
									stock_code="{$clsLeasing->getCode($_oLeasing)}" stock_id="{$_oLeasing.stock_id}" leasing_id="{$_oLeasing.leasing_id}">
									<img class="awe__leasing-slider-overlay position-absolute zindex-1" src="{$clsISO->resizeImageFromUrl($_oImg,0,300)}" />
									<div class="awe__leasing-slider-image w-100 h-100 d-flex justify-content-center align-items-center position-relative zindex-2">
										<img src="{$clsISO->resizeImageFromUrl($_oImg,0,250)}" alt="{$_oLeasing.title}" /> 
									</div>
								</div>
								{/foreach}
							</div>
							{else}
							<div class="awe__leasing-image cursor-pointer" onClick="$Core.leasing.open_leasing(this, event)" 
								stock_code="{$clsLeasing->getCode($_oLeasing)}" stock_id="{$_oLeasing.stock_id}" leasing_id="{$_oLeasing.leasing_id}">
								<img class="awe__leasing-img" src="{$URL_IMAGES}/no-image.jpg" />
							</div>
							{/if}
							{if $profile_id != 118}
							<a onclick="$Core.leasing.handleLike(this, {$_oLeasing.leasing_id},'list')" id="like_{$_oLeasing.leasing_id}" data-like_id="like_{$_oLeasing.leasing_id}" data-bs-toggle="tooltip" data-placement="bottom" class="like {if $liked eq 1}liked{/if}" {if $liked eq 1}title="Bỏ thích"{else}title="Thích"{/if}><i class="fa fa-heart-o" aria-hidden="true"></i></a>
							{/if}
							{if $_oLeasing.having_dq eq 1}
								<div class="exclusive position-absolute p-1 rounded-pill">
									<div class="exclusive_text py-1 px-2 rounded-pill fs-11 text-center">Độc quyền</div>
								</div>
							{/if}
							{if $_oLeasing.is_solded eq 1}
								<div class="box_solded position-absolute p-1 rounded-pill">
									<div class="is_solded_text p-1 rounded-pill fs-11 text-center">Đã cho thuê</div>
								</div>
							{/if}
						</div>
						<div class="awe__leasing-body pointer-cursor" onClick="$Core.leasing.open_leasing(this, event)" 
							 stock_code="{$clsLeasing->getCode($_oLeasing)}" stock_id="{$_oLeasing.stock_id}" leasing_id="{$_oLeasing.leasing_id}">
							<h3 class="awe__leasing-title mb-1">
								{if $_oLeasing.is_locked eq '1'} 
								<i data-bs-toggle="tooltip" data-bs-trigger="hover" title="Đã khóa" class="material-icons-outlined fs-small text-danger">lock</i>
								{/if}
								<a href="javascript:void(0)" class="awe__leasing-link fs-5 text-dark" title="{$_title}">{$_oLeasing.title}</a>
							</h3>
							<div class="d-flex align-items-center fs-12 awe__leasing-location py-1">
								<i class="re__icon-location--sm mr-1"></i>
								<span class="text-main">{$clsLeasing->getCode($_oLeasing)}
									{if !empty($_oLeasing.building_name)}
										, Tòa {$_oLeasing.building_name}
									{/if}
									{if !empty($_oLeasing.block_name)}
										, {$_oLeasing.block_name}
									{/if}
								</span>
							</div>
							<div class="d-flex align-items-center awe__leasing-meta awe__leasing-config mb-1">
								{if !empty($_oLeasing.bedroom) || !empty($more_information.bedroom_num)}
								<div class="mr-2 text_ellipsis d-flex align-items-center">
									<i class="re__icon-bedroom--sm mr-1"></i>
									<span class="text-main">{if $_oLeasing.bedroom}{$_oLeasing.bedroom}{else}{$more_information.bedroom_num}PN{/if}</span>
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
								{if !empty($_oLeasing.home_direction)}
								<div class="mr-2 text_ellipsis d-flex align-items-center">
									<i class="re__icon-ying-yang--xl mr-1"></i>
									<span class="text-main">{$_oLeasing.home_direction}</span>
								</div>
								{/if}
							</div>  
							<div class="d-flex flex-wrap align-items-center justify-content-between awe__leasing-meta awe__product-config">
								<div class="d-flex align-items-center fw-bold text_ellipsis">
									<i class="re__icon-money--sm mr-1"></i>
									<span class="text-main fs-18">{$clsISO->shortNumber($_oLeasing.price)}/<span class="fs-12">1 tháng</span></span>
								</div>
								<div class="awe__leasing-date fs-11 text-muted">
									<i class="material-icons-outlined">update</i>
									{$clsISO->getTimeAgo($_oLeasing.upd_date)}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
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

	{if !empty($list_leasing)}
		{foreach from =$list_leasing item = _oLeasing}
		{assign var = more_information value = $_oLeasing.more_information}
		<tr class="awe__leasing-item awe__leasing-item-{$_oLeasing.leasing_id} text-nowrap{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'} deleted{/if} sssssssssssss">
			<td class="text-left">
				<a class="fw-bold" href="javascript:void(0)" onClick="$Core.leasing.open_leasing(this, event)" 
					stock_code="{$clsLeasing->getCode($_oLeasing)}" leasing_id="{$_oLeasing.leasing_id}">
					{if $type_list eq 'publish'}
						{$clsLeasing->getCode($_oLeasing)}
					{else}
						{$_oLeasing.stock_code}
					{/if}
				 </a>
				<span class="leasing__icon-{$_oLeasing.leasing_id}">{$clsLeasing->getIcon($_oLeasing.leasing_id,$type_list)}</span>
			</td>
			{if $deviceType eq 'phone'}
			<td>
				{if !empty($_oLeasing.bedroom) || !empty($more_information.bedroom_num)}
					{if $_oLeasing.bedroom}{$_oLeasing.bedroom}{else}{$more_information.bedroom_num}PN{/if}
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
				{if !empty($_oLeasing.price)}
					<strong class="fw-bold text-main">{$clsISO->formatPriceV2($_oLeasing.price,3)} triệu</strong>
				{else}
					Check
				{/if}
			</td>
			{else}
			<td class="text-left">
				<span class="d-none d-xxl-block fw-bold text-main">
					{$clsISO->priceFormat($_oLeasing.price)}đ
				</span>
				<span class="d-none d-md-block d-xxl-none fw-bold text-main">
					{$clsISO->formatPriceV2($_oLeasing.price,3)} triệu
				</span>
			</td>
			{if $type_list eq 'manager'}
				<td>
					{if !empty($_oLeasing.bedroom) || !empty($more_information.bedroom_num)}
						{if $_oLeasing.bedroom}{$_oLeasing.bedroom}{else}{$more_information.bedroom_num}PN{/if}
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
					{if !empty($_oLeasing.bedroom) || !empty($more_information.bedroom_num)}
						{if $_oLeasing.bedroom}{$_oLeasing.bedroom}{else}{$more_information.bedroom_num}PN{/if}
					{else}
						-
					{/if}
				</td>
			{/if}
			<td>
				{if !empty($_oLeasing.home_direction)}
					{$_oLeasing.home_direction}
				{else}
					-
				{/if}
			</td>
			<td>{$clsLeasing->getRangeFloor($_oLeasing.floor)}</td>
			<td>{$_oLeasing.contact_name}</td>
			<td>{$clsProfile->mask($_oLeasing.contact_phone, 1)}</td>
			{/if}
			{if $type_list eq 'manager'}
			<td class="text-center">
				<label class="switch">
				  <input type="checkbox" onChange="$Core.leasing.verified(this, event)" 
					leasing_id="{$_oLeasing.leasing_id}"{if $_oLeasing.is_verified eq '1'} checked="checked"{/if} value="1" />
				  <span class="slider round"></span>
				</label>
			</td>
			<td class="text-center">
				<label class="switch">
				  <input type="checkbox" onChange="$Core.leasing.approved(this, event)" leasing_id="{$_oLeasing.leasing_id}" {if $_oLeasing.is_online eq '1'} checked="checked"{/if} value="1" />
				  <span class="slider round"></span>
				</label>
			</td>
			{*<td class="text-center">
				<div class="switch-toggle switch-3 switch-candy">
					<input id="on_{$_oLeasing.leasing_id}" title="Duyệt" name="status_{$_oLeasing.leasing_id}" onChange="$Core.leasing.approved(this, event)" tp="agree" leasing_id="{$_oLeasing.leasing_id}" type="radio"{if $_oLeasing.is_online eq '1'} checked{/if} value="{$_oLeasing.leasing_id}" />
					<label for="on_{$_oLeasing.leasing_id}"><i class="bx bx-check"></i></label>
					<input id="na_{$_oLeasing.leasing_id}" name="status_{$_oLeasing.leasing_id}" onChange="$Core.leasing.approved(this, event)" leasing_id="{$_oLeasing.leasing_id}" tp="wait" type="radio"{if $_oLeasing.is_online eq '0'} checked{/if} value="{$_oLeasing.leasing_id}" />
					<label for="na_{$_oLeasing.leasing_id}"></label>
					<input id="off_{$_oLeasing.leasing_id}" title="Không duyệt" onChange="$Core.leasing.approved(this, event)" leasing_id="{$_oLeasing.leasing_id}" tp="refuse" name="status_{$_oLeasing.leasing_id}" type="radio"{if $_oLeasing.is_online eq '2'} checked{/if} value="{$_oLeasing.leasing_id}" />
					<label for="off_{$_oLeasing.leasing_id}"><i class="bx bx-x"></i></label>
				</div>
			</td>*}
			{/if}
			{if $type_list eq 'manager' or $type_list eq 'me'}
			<td class="text-center">								
				<button class="btn btn-icon btn_show_log_{$_oLeasing.leasing_id}" title="Thống kê" data-bs-toggle="tooltip" onClick="$Core.leasing.showLog(this,event)" leasing_id="{$_oLeasing.leasing_id}"><i class='bx bx-bar-chart-alt-2'></i></button>	
			</td>
			<td class="text-center">
				<div class="dropdown">
					<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="true" {$_oLeasing.is_solded}>
						<i class="bx bx-dots-vertical-rounded"></i>
					</button>
					<div class="dropdown-menu" data-popper-placement="bottom-start">
						<a class="dropdown-item" href="{$PCMS_URL}/ct/edit/{$_oLeasing.leasing_id}?return_url={$ret_url}" 
						class="btn flex-fill btn-outline-default"><i class="material-icons-outlined">border_color</i> Sửa</a>
						{if isset($more_information.is_locked) && $more_information.is_locked eq '1'}
						<a class="dropdown-item leasing__menu-lock-{$_oLeasing.leasing_id}{if $clsLeasing->checkSolded($_oLeasing.leasing_id, $more_information) eq '1' || $clsLeasing->checkDeleted($_oLeasing.leasing_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.leasing.mark_lock(this, event)" leasing_id="{$_oLeasing.leasing_id}" href="javascript:void(0);"><i class="material-icons-outlined">lock_open</i> Mở Khoá</a>
						{else}
						<a class="dropdown-item leasing__menu-lock-{$_oLeasing.leasing_id}{if $clsLeasing->checkSolded($_oLeasing.leasing_id, $more_information) eq '1' || $clsLeasing->checkDeleted($_oLeasing.leasing_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.leasing.mark_lock(this, event)" leasing_id="{$_oLeasing.leasing_id}" href="javascript:void(0);"><i class="material-icons-outlined">lock</i> Khoá</a>
						{/if}
						{if isset($more_information.is_solded) && $more_information.is_solded eq '1'}
						<a class="dropdown-item leasing__menu-sold-{$_oLeasing.leasing_id}{if $clsLeasing->checkDeleted($_oLeasing.leasing_id, $more_information) eq '1'} preventDefault{/if}" leasing_id="{$_oLeasing.leasing_id}" onClick="$Core.leasing.mark_sold(this, event)" href="javascript:void(0);">
							<i class="material-icons-outlined">add_business</i> Mở cho thuê</a>
						{else}
						<a class="dropdown-item leasing__menu-sold-{$_oLeasing.leasing_id}{if $clsLeasing->checkDeleted($_oLeasing.leasing_id, $more_information) eq '1'} preventDefault{/if}" onClick="$Core.leasing.mark_sold(this, event)" leasing_id="{$_oLeasing.leasing_id}" href="javascript:void(0);">
							<i class="material-icons-outlined">storefront</i> Báo cho thuê</a></li>
						{/if}
						{if isset($more_information.is_deleted) && $more_information.is_deleted eq '1'}
						<a class="dropdown-item leasing__menu-delete-{$_oLeasing.leasing_id}{if $_oLeasing.is_locked eq '1'} preventDefault{/if}" onClick="$Core.leasing.delete(this, event)" leasing_id="{$_oLeasing.leasing_id}" href="javascript:void(0);">
							<i class="material-icons-outlined">delete</i> Khôi phục</a>
						{else}
						<a class="dropdown-item leasing__menu-delete-{$_oLeasing.leasing_id}{if $_oLeasing.is_locked eq '1'} preventDefault{/if}" onClick="$Core.leasing.delete(this, event)" leasing_id="{$_oLeasing.leasing_id}" href="javascript:void(0);">
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
