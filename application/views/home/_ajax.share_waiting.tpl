{if !empty($is_report)}
	<div class="modal right fade show" id="{$uid}" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-main">Báo cáo tiếp khách chờ duyệt</h5>
					<button type="button" class="btn-close close_pop" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					{if !empty($list_shares)}
						<ul class="p-0 m-0 p-0 m-0 overflow-y-auto" style="max-height: calc(100vh - 100px)">
							{foreach from=$list_shares item=_oItem key=key name=i}
								<li class="d-flex cursor-pointer pb-2 align-items-center p-2 bg-lighter rounded-2 share_item_{$_oItem.share_id} {if !$smarty.foreach.i.last} mb-2{/if}" onclick="$Core.global.share.open_share(this,event)" share_id="{$_oItem.share_id}" >
									<div class="avatar mt-1 avatar-sm position-relative flex-shrink-0 me-2" data-trigger="hover" data-width="300" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oItem.user_id}" data-toggle="webui-popover" >
										<img src="{$_oItem.db_profile.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" alt="{$_oItem.db_profile.name}" class="rounded-pill" />
										{$clsProfile->get_icon_verified($_oItem.user_id, $_oItem.db_profile.more_information)}
									</div>
									<div class="w-100">
										<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
											<small class="text-muted d-block">{$_oItem.db_profile.name} • {$_oItem.db_profile.role}-{$_oItem.db_profile.more_information.department_name}</small>
										</div>
										<h6 class="mb-0 limit_1line">{$_oItem.title}</h6>
									</div>
									<a href="javascript:void(0)" class="text-link"><i class="bx bx-link-external text-fs-14"></i></a>
								</li>
							{/foreach}
						</ul>
					{else}
						<div class="mt-3">
							<div class="p-5">
								<div class="text-center">
									<img class="mb-2" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAE0AAABqCAIAAAB2wktpAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkY0RTUxMzM1OEZBQTExRThBOUQxQjhGNDMzNjRGM0JDIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkY0RTUxMzM2OEZBQTExRThBOUQxQjhGNDMzNjRGM0JDIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RjRFNTEzMzM4RkFBMTFFOEE5RDFCOEY0MzM2NEYzQkMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjRFNTEzMzQ4RkFBMTFFOEE5RDFCOEY0MzM2NEYzQkMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6dXDCjAAAD+ElEQVR42uycC0/aUBTHaSm1iI6HlJfR4URnNDoU39F9fDcQhOjMHo4Z5SXjIaUKqDx21C0xxUkbq97Wc0JI7k1J+NH/edx7T6FEUby4uNiOxgWhatCCURS1MD/n8bgVfYputVrhSEwrkGCdTiee2CuVyso4fyYPRfHMoClrt9vRWELR16aPjlIGDVqz2Yxs79TrdZnXM1dXV3fHNpt1aMhBGhXcDACTTDYajXA4tr6+zLJsb07JOPhh1mLpJ40zm811c4Kd12oQQVdXFo1GYw/dSsc0rS0BVypCPLELHquMU4uWzxd2d/d1yAkpVDKTyea+fT/QG+eYf3RwYEAymUwePpA7NMkJAXZ5eYHjOMn8l/2vcGN15Z8Auby0wDDSfAGOem+ppOE4NDg4sBgKSjLK/0olbcdbKGnmg3OSsASZ9nM4CqlVV3nF7eZnZqYkk5eXl9uRHXjXVf58OzoSCLy7t1SC1Zh+OMHeTwaAtrtUikbjt6USo0UquFfFYqlLwC6YlLhlsVSGCBwMzmqSM5XKwEvmxZBROTOnE90+bFAqvQpOzcSh7mpWn5zT01OPXP1rIw4B5MfN9YpQbf/Lhw8bVAjxxJ72OG83Ohx2m8yL6/WGDvcT9OOfyKl2HCqXT3O5fLvTJmVF5nD4fB6VOaFoDEdiPfcOn9OOj9MMY3S5eDV1WxVEoiD/Suy0orJ/2u3W7p2YlzWKonjeqbJuOY7b2FgF/7x38/9FcqmLd9psVvXjkKW/PzA+hnkFOZETOZETOZET1yvddpL/nUplCKlyKcrA8/yYf1RlzqooxmIJou5PoVDqY00+n1dN3Z6f1wiUYlVRP5jMRS3H9ZEVV2jaq6SVUZZuWda0ubEGUmnJ21Z8ege9/ukV7ejKjUMsyw4Pew2aNcyfyImcyImcyPkSdTxU8PKPH5/BrNY3JpNJZc5ms7m1FRHPCHosAkq09bUV+SWRLN0WS2WiIA3XJ9ZX6UxWZf/sk/FAwfObuav/9rG6tdttM9NTqXSGqHOHkZFh9eOQ3z/qV7J+x7yCnMiJnMiJnMj5etYrlYqQzeZaxHTX8LzT43apzFmr1T993iatT2ppacElu7VGlm4rgkBin1T5VGX/hDqe6fVg8AtI1zmksm7NN31SmewJKeeCN/7pcNjVj0MWi2VyYhzzCnIiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3I+NWdHF1xSCul+/MHBL0XPd5NphUJRyknT9N1Tk1Q6Ay+didZopGmf16N75/R6PPTkZIBlTTqGZFkWGGmzmQuF5vWKClyhUBAYKVEUYVxvNA5+JHMnpPwTxOONYRiv1z0xMX7bvvpHgAEAO0R+YvoBo4cAAAAASUVORK5CYII=" width="40px">
									<p>Không có bất kỳ hoạt động nào<br> với khách hàng này</p><p>
								</p></div>
							</div>
						</div>
					{/if}
				</div>
			</div>
		</div>
	</div>
{else}
	{if !empty($list_shares)}
		<div class="card mb-2">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h5 class="card-title m-1 me-2">Báo cáo tiếp khách chờ xác thực</h5>
				<span class="">Tổng <strong class="text-main fw-bold">{$total_record}</strong></span>
			</div>
			<div class="card-body">
				<ul class="p-0 m-0 p-0 m-0 overflow-y-auto" style="max-height: 300px">
					{foreach from=$list_shares item=_oItem key=key name=i}
						<li class="d-flex cursor-pointer pb-2 align-items-center p-2 bg-lighter rounded-2 {if !$smarty.foreach.i.last} mb-2{/if}" onclick="$Core.share.open_share(this,event)" share_id="{$_oItem.share_id}" >
							<div class="avatar mt-1 avatar-sm position-relative flex-shrink-0 me-2" data-trigger="hover" data-width="300" data-url="/index.php?mod=home&act=load_profile_popover&user_id={$_oItem.user_id}" data-toggle="webui-popover" >
								<img src="{$_oItem.db_profile.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" alt="{$_oItem.db_profile.name}" class="rounded-pill" />
								{$clsProfile->get_icon_verified($_oItem.user_id, $_oItem.db_profile.more_information)}
							</div>
							<div class="w-100">
								<div class="d-flex w-100 flex-wrap align-items-center justify-content-between mb-1">
									<small class="text-muted d-block">{$_oItem.db_profile.name} • {$_oItem.db_profile.role}-{$_oItem.db_profile.more_information.department_name}</small>
								</div>
								<h6 class="mb-0 limit_1line">{$_oItem.title}</h6>
							</div>
							<a href="javascript:void(0)" class="text-link"><i class="bx bx-link-external text-fs-14"></i></a>
						</li>
						{if $smarty.foreach.i.iteration eq 10}{break}{/if}
					{/foreach}
				</ul>
			</div>
		</div>	
	{/if}
{/if}
