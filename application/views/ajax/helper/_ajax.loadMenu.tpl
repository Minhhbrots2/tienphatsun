{if $deviceType eq 'phone'}
	{if !empty($lstUtilities)}
	<div class="form-row row-cols-4">
		{foreach from=$lstUtilities item=_oUtilities key=key name=i}
		<div class="col mb-3 item_menu_grid{if $smarty.foreach.i.iteration gt 4} item_more d-none{/if}">
			<a class="text-dark text-center text-center d-block" href="{$_oUtilities.link}" {$_oUtilities.attr}>
				<span class="item_icon card mb-2 d-flex justify-content-center align-items-center mx-auto position-relative">
					<i class='fs-30 {$_oUtilities.icon} text-main'></i>
					{if $_oUtilities.badge eq 'hot'}
					<svg class="svg-icon position-absolute top-0 left-0" style="width:1.25em; height:1.25em;vertical-align:middle;fill:currentColor;overflow:hidden;" \
						viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg">
						<path d="M828.5 68.1H200.9c-57.8 0-104.6 44.4-104.6 99.3v694c0 13.7 2.9 26.8 8.3 38.7 22.3 49.9 83.9 68.2 130.5 39.7l244.7-149.6c28.1-17.2 63.5-16.6 91 1.4l221.1 145c59.3 38.9 139.4-1.8 141.3-72.7V167.3c-0.1-54.8-46.9-99.2-104.7-99.2z" fill="#187903" />
						<path d="M365.9 552.7h-32.7v-114H205.1v114.1h-32.7V299.6h32.7v110h128.1v-110h32.7v253.1zM414.9 429.2c0-40.9 11.1-73.4 33.2-97.6s52.1-36.3 89.9-36.3c35.2 0 63.5 11.8 85 35.5 21.5 23.6 32.2 54.4 32.2 92.3 0 41.1-11 73.7-33 97.7-22 24.1-51.4 36.1-88.3 36.1-36 0-64.8-11.8-86.5-35.5-21.6-23.5-32.5-54.3-32.5-92.2z m34.4-2.6c0 30.6 7.8 55.1 23.5 73.7 15.6 18.4 36 27.7 61.2 27.7 27 0 48.2-8.8 63.7-26.6 15.4-17.7 23.1-42.4 23.1-74.3 0-32.7-7.5-57.9-22.6-75.7-15.1-17.8-35.8-26.6-62-26.6-25.8 0-46.7 9.4-62.8 28.1-16.1 18.7-24.1 43.3-24.1 73.7zM857 328.7h-73v224.1h-32.9V328.7h-72.7v-29.1H857v29.1z" fill="#FFFFFF" />
					</svg>
					{/if}
				</span>
				<span class="text-dark fw-semibold fs-12">{$_oUtilities.title}</span>
			</a>
		</div>
		{/foreach}
	</div>
	<button type="button" data-toggle="ripple" class="btn btn-icon position-absolute btn_menu_more rounded-pill card hide text-main" onClick="$Core.mobile.view_menu(this,event)"><i class='bx bxs-chevrons-down' ></i></button>
	{/if}
{else}
	{if !empty($lstUtilities)}
	<div class="d-flex align-items-start justify-content-between flex-wrap gap-2">
		{foreach from=$lstUtilities item=_oUtilities key=key name=i}
		<div class="item_menu_grid w-px-100">
			<a href="{$_oUtilities.link}" class="text-dark text-center text-center d-block" {$_oUtilities.attr}>
				<span  class="item_icon card d-flex justify-content-center align-items-center mx-auto position-relative mb-2">
					<i class='fs-30 {$_oUtilities.icon} text-main'></i>
					{if !empty($_oUtilities.total) || ($_oUtilities.id eq '11110')}
					<span class="badge bg-danger rounded-pill position-absolute p-1 d-flex align-items-center justify-content-center" style="aspect-ratio: 1 / 1;top: -5px;right: -5px; width:22px;font-size:8px">{$_oUtilities.total}</span>
					{/if}
					{if $_oUtilities.badge eq 'hot'}
					<svg class="svg-icon position-absolute top-0 left-0" style="width:1.25em; height:1.25em;vertical-align:middle;fill:currentColor;overflow:hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg">
						<path d="M828.5 68.1H200.9c-57.8 0-104.6 44.4-104.6 99.3v694c0 13.7 2.9 26.8 8.3 38.7 22.3 49.9 83.9 68.2 130.5 39.7l244.7-149.6c28.1-17.2 63.5-16.6 91 1.4l221.1 145c59.3 38.9 139.4-1.8 141.3-72.7V167.3c-0.1-54.8-46.9-99.2-104.7-99.2z" fill="#187903" />
						<path d="M365.9 552.7h-32.7v-114H205.1v114.1h-32.7V299.6h32.7v110h128.1v-110h32.7v253.1zM414.9 429.2c0-40.9 11.1-73.4 33.2-97.6s52.1-36.3 89.9-36.3c35.2 0 63.5 11.8 85 35.5 21.5 23.6 32.2 54.4 32.2 92.3 0 41.1-11 73.7-33 97.7-22 24.1-51.4 36.1-88.3 36.1-36 0-64.8-11.8-86.5-35.5-21.6-23.5-32.5-54.3-32.5-92.2z m34.4-2.6c0 30.6 7.8 55.1 23.5 73.7 15.6 18.4 36 27.7 61.2 27.7 27 0 48.2-8.8 63.7-26.6 15.4-17.7 23.1-42.4 23.1-74.3 0-32.7-7.5-57.9-22.6-75.7-15.1-17.8-35.8-26.6-62-26.6-25.8 0-46.7 9.4-62.8 28.1-16.1 18.7-24.1 43.3-24.1 73.7zM857 328.7h-73v224.1h-32.9V328.7h-72.7v-29.1H857v29.1z" fill="#FFFFFF" />
					</svg>
					{/if}
				</span>
				<span class="text-dark fw-bold text-fs-12 limit_2line">{$_oUtilities.title}</span>
			</a>
		</div>
		{/foreach}
	</div>
	{/if}
{/if}