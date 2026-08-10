<div id="{$clsISO->getUniqid()}" class="alert alert-warning px-lg-4 mb-2" 
	style="background:linear-gradient(to right, {$arr_color.bgcolor}, color-mix(in srgb, {$arr_color.bgcolor} 60%, white 30%), color-mix(in srgb,{$arr_color.bgcolor} 30%, white 70%))!important">
	<div class="form-row">
		<div class="col-12 col-lg-5 wXqKYnaIvN mb-2 mb-lg-0">
			{assign var = greeting value = $clsISO->get_greeting()}
			<div class="greeting d-flex align-items-center gap-2">
				<div class="greeting__emoji mb-2 mb-lg-0">
					<img src="{$greeting.emoji}" class="w-px-50" />
				</div>
				<div class="greeting__text" style="color:{$arr_color.color}!important">
					<h1 class="mb-1 fw-bold text-fs-20">Chào {$clsProfile->getFullName($profile_id, $oneProfile)}</h1>
					<div class="text-fs-14 xs:text-fs-12">{$greeting.wellcome}</div>
				</div>
			</div>
		</div>
		<div class="col-12 col-lg-7 uFoxaAAvgL">
			{if $deviceType eq 'computer'}
			<p class="fs-12 fst-italic mb-1 text-white text-right">
				Hôm nay {$today} (tức {$luna_date} Âm lịch)
			</p>
			{/if}
			<div class="d-flex flex-column align-items-end fs-5 w-100" style="color:{$arr_color.color}!important">
				<div class="d-flex gap-1 align-items-center position-relative px-4">
					<i class="bx bxs-quote-alt-left position-absolute top-0 left-0"></i> 
					<i class="text-center lh-lg xs:text-left xs:text-fs-16">{$oneQuote.content}.</i>
					<i class="bx bxs-quote-alt-right position-absolute bottom-0 right-0"></i>
				</div>
				{if !empty($oneQuote.author)}
				<div class="w-100 text-right text-fs-12 text-white">-- {$oneQuote.author}</div>
				{/if}
			</div>
		</div>
	</div>
</div>
{if $clsISO->checkSale() && $transactions_configs.days_since_sold gt '5' && $deviceType eq 'phone'}
<div class="call-to-action mb-2 rounded-2 p-3" style="background:{$oneColor.bgcolor}">
	<div class="d-flex gap-3 align-items-center position-relative">
		<div class="days-container position-relative">
			<div class="counter-bg"></div>
			<div class="days-number" style="color:{$oneColor.color}">{$transactions_configs.days_since_sold}</div>
		</div>
		<div class="days-label d-flex flex-column gap-1 lh-base" style="color:{$oneColor.color}">
			<span class="fst-italic">{$oneColor.message}</span>
			<strong class="fst-italic fs-6">Chốt thôi !!! </strong>
		</div>
		<svg class="animated-icon text-main position-absolute right-0 bottom-0" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16">
			<path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
		</svg>
	</div>
</div>
{/if}
{if $clsISO->checkSale() && !empty($oneColorShare) && $deviceType eq 'phone'}
<div class="call-to-action mb-2 rounded-2 p-3" style="background:{$oneColorShare.bgcolor}">
	<div class="d-flex gap-3 align-items-center  justify-content-center position-relative">
		<div class="days-container position-relative">
			<div class="counter-bg"></div>
			<div class="days-number" style="color:{$oneColorShare.color}">{$oneColorShare.total_share}</div>
		</div>
		<div class="days-label d-flex flex-column gap-1 lh-base text-center fs-16 fw-bold" style="color:{$oneColorShare.color};max-width: calc(100% - 60px)">
			<span class="fst-italic">{$oneColorShare.message}</span>
		</div>
	</div>
</div>
{/if}