<div class="card mb-2">
	{assign var = _uid value = $clsISO->getUniqid()}
	<div class="card-header{if $act eq 'share'} pb-2{/if}">
		<div class="d-flex align-items-center justify-content-between">
			<h5 class="card-title mb-0"><svg class="animated-icon" width="22" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.1019 8.96657C18.9791 8.64576 18.7005 8.4104 18.3639 8.34302L13.8437 7.43845L12.0622 3.87587C11.8928 3.53699 11.5466 3.32288 11.1676 3.32288C10.7887 3.32288 10.4425 3.53699 10.2731 3.87587L8.49155 7.43845L3.97142 8.34302C3.63474 8.4104 3.35593 8.64576 3.23337 8.96657C3.1108 9.28738 3.16134 9.64871 3.3674 9.92362L6.09891 13.566L5.18703 18.1277C5.11549 18.4871 5.24611 18.8567 5.52786 19.0913C5.80936 19.3257 6.19657 19.3875 6.53691 19.2522L11.1676 17.4006L15.7962 19.2508C16.1368 19.386 16.524 19.3242 16.8055 19.0899C17.087 18.8552 17.2178 18.4856 17.1463 18.1262L16.2342 13.5646L18.9679 9.92362C19.1739 9.64871 19.2245 9.28738 19.1019 8.96657Z" fill="#F48120"></path></svg> Tin mới nhất</h5>
			{if $act eq 'share' || $act eq 'work'}
			<div class="paginator d-flex gap-2 align-items-center">
				<button toId="{$_uid}" data-toggle="ripple" onClick="$Core.owl.prev(this, event)" type="button" 
					class="btn btn-link btn-icon btn-sm pagiPrev rounded-pill"><i class='bx bx-chevron-left text-muted'></i></button> 
				<span class="text-muted" uid="{$_uid}">1/3</span> 
				<button toId="{$_uid}" data-toggle="ripple" onClick="$Core.owl.next(this, event)" type="button" 
					class="btn btn-link btn-icon btn-sm rounded-pill pagiNext"><i class='bx bx-chevron-right text-muted'></i></button>
			</div>
			{/if}
		</div>
	</div>
    <div class="card-body">
		{if $act eq 'share' || $act eq 'work'}
		<div id="{$_uid}" class="owl owl-carousel hide-nav" data-nav="false" data-loop="false" 
			data-lg-slide="1" data-md-slide="1" data-sm-slide="1" data-xs-slide="1">
			<div class="item_group">
				{foreach from=$list_news item=_oItem name= i}
				<a onClick="open_news(this, event)" action="_detail" news_id="{$_oItem.news_id}" 
					class="item_news cursor-pointer d-flex gap-2 align-items-center rounded-1 p-2" title="{$_oItem.title}">
					{if !empty($_oItem.image)}
					<div class="img_news">
						<img class="object-fit-cover rounded-1" src="{$clsISO->resize_image_url($_oItem.image,80,80)}" 
							alt="{$_oItem.title}" width="80px" height="80px" />
					</div>
					{/if}
					<div class="content_news">
						<h3 class="title_news lh-xs fs-14 mb-1 limit_2line text-black text-upper">{$_oItem.title}</h3>
						<div class="d-flex align-items-center flex-wrap">
							<span class="d-flex align-items-center fs-11 text-muted me-2 mb-1">
								<i class='bx bx-folder-open fs-14 me-1'></i>{$_oItem.cat_name}</span>
							<span class="d-flex align-items-center fs-11 text-muted me-2 mb-1">
								<i class='bx bx-user fs-14 me-1'></i>{$_oItem.author}</span>
							<span class="d-flex align-items-center fs-11 text-muted me-2 mb-1">
								<i class='bx bx-timer fs-14 me-1'></i>{$clsISO->getTimeAgo($_oItem.reg_date)}</span>
							<span class="d-flex align-items-center fs-11 text-muted me-2 mb-1">
								<i class="material-icons-outlined fs-14 me-1">visibility</i> {$_oItem.view_num}
							</span>
						</div>
					</div>
				</a>
				{if $smarty.foreach.i.iteration%3 == '0' && !$smarty.foreach.i.last}
					</div><div class="item_group">
				{/if}
				{/foreach}
			</div>
		</div>
		{else}
		<div id="{$_uid}" class="owl_news owl-carousel">
			{foreach from=$list_news item=_oItem}
			<a onClick="open_news(this, event)" action="_detail" news_id="{$_oItem.news_id}" title="{$_oItem.title}" 
				class="item_news d-flex cursor-pointer gap-2 align-items-center bg-lighter rounded-1 p-2 h-100">
				{if !empty($_oItem.image)}
				<div class="img_news">
					<img class="object-fit-cover rounded-1" src="{$clsISO->resize_image_url($_oItem.image,100,100)}" 
						alt="{$_oItem.title}" width="100" height="100" >
				</div>
				{/if}
				<div class="content_news">
					<h3 class="title_news lh-xs fs-14 mb-1 limit_2line text-black text-upper">{$_oItem.title}</h3>
					<div class="d-flex align-items-center flex-wrap">
						<span class="d-flex align-items-center fs-11 text-muted me-2">
							<i class='bx bx-folder-open fs-14 me-1'></i>{$_oItem.cat_name}</span>
						<span class="d-flex align-items-center fs-11 text-muted me-2">
							<i class='bx bx-user fs-14 me-1'></i>{$_oItem.author}</span>
						<span class="d-flex align-items-center fs-11 text-muted me-2">
							<i class='bx bx-timer fs-14 me-1'></i>{$clsISO->getTimeAgo($_oItem.reg_date)}</span>
						<span class="d-flex align-items-center fs-11 text-muted me-2">
							<i class="material-icons-outlined no-translate fs-14 me-1">visibility</i>
							<span> {$_oItem.view_num}</span>
						</span>
					</div>
				</div>
			</a>
			{/foreach}
		</div>
		{/if}
    </div>
</div>
