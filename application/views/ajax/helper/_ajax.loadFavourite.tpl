{* ============================================================
   Panel "Danh sach yeu thich" - mo tu icon tim tren header.
   2 tab: Can ho (server: profile.wishlist) + Du an (client:
   localStorage ptl_favs - dong bo voi nut tim o /thong-tin/,
   backend hien chua co wishlist du an).
   Tab Can ho co footer [Xem tat ca] + [Xem so sanh];
   tab Du an khong co footer.
   Backup ban cu: _ajax.loadFavourite.tpl.bak | CSS: wishlist.css
   ============================================================ *}
<link rel="stylesheet" type="text/css" href="{$URL_CSS}/wishlist.css?v={$upd_version}" />
<div class="modal right fade show" id="{$uid}" role="dialog">
	<div class="modal-dialog modal-dialog-scrollable wlp-dialog">
		<div class="modal-content wlp">
			<div class="modal-header wlp-head">
				<span class="wlp-head__ico"><i class="bx bxs-heart"></i></span>
				<div class="wlp-head__titles">
					<h5 class="modal-title wlp-head__title">Danh sách yêu thích</h5>
					<p class="wlp-head__sub"><span class="js__wlp-n-stock">{$list_stocks|@count}</span> căn hộ • <span class="js__wlp-n-project">0</span> dự án</p>
				</div>
				<button type="button" class="wlp-close" data-bs-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i> Đóng</button>
			</div>
			<div class="wlp-tabs">
				<button type="button" class="wlp-tab on" data-wltab="stock"><i class="bx bx-building-house"></i> Căn hộ (<span class="js__wlp-n-stock">{$list_stocks|@count}</span>)</button>
				<button type="button" class="wlp-tab" data-wltab="project"><i class="bx bx-home-alt"></i> Dự án (<span class="js__wlp-n-project">0</span>)</button>
			</div>
			<div class="modal-body scroller wlp-body">
				<div class="wlp-pane on" data-wlpane="stock">
					<ul class="list-unstyled mb-0" id="list_favourite" data-bs-popper="static">
						{if !empty($list_stocks)}
							{foreach from=$list_stocks item=_oItem name=i}
								{assign var=moreInformation value=$_oItem.more_information}
								<li class="wlp-item item_pop_favourite">
									<div class="wlp-item__thumb">
										{if !empty($_oItem.project_image)}
											<img src="{$clsISO->resize_image_url($_oItem.project_image, 260, 180)}" onerror="this.src='{$URL_IMAGES}/no-image.png'" loading="lazy" alt="" />
										{else}
											<span class="wlp-item__thumb-ph"><i class="bx bx-building-house"></i></span>
										{/if}
									</div>
									<div class="wlp-item__meta">
										<a href="javascript:void(0);" class="wlp-item__name"
											{if $deviceType eq 'phone'}onclick="$Core.helper.open_stock({$_oItem.stock_id})"{else} data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id={$_oItem.stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="600" data-target="webuiPopover{$_oItem.stock_id}_{$uid}"{/if}>{$_oItem.ms_code}</a>
										{if !empty($_oItem.location)}
											<p class="wlp-item__row"><i class="bx bx-map"></i><span>{$_oItem.location}{if !empty($_oItem.project_name)}, {$_oItem.project_name|escape}{/if}</span></p>
										{/if}
										<p class="wlp-item__row wlp-item__row--chips">
											{if $_oItem.bedroom}<span class="wlp-item__chip"><i class="bx bx-bed"></i>{$_oItem.bedroom}</span>{/if}
											{if $moreInformation.DT_TT}<span class="wlp-item__chip"><i class="bx bx-square"></i>{$moreInformation.DT_TT}m<sup>2</sup></span>{/if}
											{if $_oItem.home_direction}<span class="wlp-item__chip"><i class="bx bx-compass"></i>{$_oItem.home_direction}</span>{/if}
										</p>
										<div class="wlp-item__btns">
											<a href="javascript:void(0);" class="wlp-btn wlp-btn--outline"
												{if $deviceType eq 'phone'}onclick="$Core.helper.open_stock({$_oItem.stock_id})"{else} data-url="/index.php?mod=home&sub=project&act=load_stock_popover&stock_id={$_oItem.stock_id}" data-toggle="webui-popover" data-trigger="click" data-placement="auto" data-width="600" data-target="webuiPopoverBtn{$_oItem.stock_id}_{$uid}"{/if}><i class="bx bx-show"></i> Xem chi tiết</a>
											<button type="button" class="wlp-btn" title="Thêm so sánh" onclick="$Core.global.compare.add_stock_compare(this,event)" _tp="pop" action="add" stock_id="{$_oItem.stock_id}"><i class="bx bx-git-compare"></i> So sánh</button>
										</div>
									</div>
									<div class="wlp-item__side">
										<span class="wlp-item__price">Thỏa thuận</span>
										<a onClick="$Core.helper.toggle_wishlist(this,event)" data-bs-toggle="tooltip" title="Loại bỏ" stock_id="{$_oItem.stock_id}" class="btn saved p-1 pop_favourite wlp-unsave">{$clsISO->makeIcon('bx-heart fs-11')}<span>Bỏ lưu</span></a>
									</div>
								</li>
							{/foreach}
						{else}
							<div class="wlp-empty">
								<img src="{$URL_IMAGES}/listing-empty.svg" width="90px">
								<p>Chưa có căn hộ nào trong danh mục yêu thích.</p>
							</div>
						{/if}
					</ul>
				</div>
				<div class="wlp-pane" data-wlpane="project">
					{if !empty($list_projects_wl)}
						{foreach from=$list_projects_wl item=_oPrj}
							<div class="wlp-item wlp-item--project d-none" data-project-id="{$_oPrj.project_id}">
								<div class="wlp-item__thumb">
									<img src="{$clsISO->resize_image_url($_oPrj.image, 260, 180)}" onerror="this.src='{$URL_IMAGES}/no-image.png'" loading="lazy" alt="" />
									<span class="wlp-item__badge">Đang mở bán</span>
								</div>
								<div class="wlp-item__meta">
									<a class="wlp-item__name" href="{$clsProject->getLinkDetail($_oPrj.project_id,0,0,'overview',$_oPrj)}">{$_oPrj.title|escape}</a>
									{if !empty($_oPrj.address)}
										<p class="wlp-item__row"><i class="bx bx-map"></i><span>{$_oPrj.address|escape}</span></p>
									{/if}
									<p class="wlp-item__row wlp-item__row--chips">
										{if $_oPrj.arcreage}<span class="wlp-item__chip"><i class="bx bx-area"></i>{$_oPrj.arcreage|escape}</span>{/if}
										{if $_oPrj.apartment}<span class="wlp-item__chip"><i class="bx bx-building"></i>{$_oPrj.apartment|escape}</span>{/if}
									</p>
								</div>
								<div class="wlp-item__side">
									<span class="wlp-item__price">{if !empty($_oPrj.more_information.dg_price)}{$_oPrj.more_information.dg_price|escape}{else}Đang cập nhật{/if}</span>
									<span class="wlp-item__pricenote">Giá từ</span>
									<a href="javascript:void(0);" class="btn saved p-1 wlp-unsave js__wlp-unsave-project" data-project-id="{$_oPrj.project_id}" title="Bỏ lưu">{$clsISO->makeIcon('bx-heart fs-11')}<span>Bỏ lưu</span></a>
								</div>
							</div>
						{/foreach}
					{/if}
					<div class="wlp-empty d-none js__wlp-empty-project">
						<img src="{$URL_IMAGES}/listing-empty.svg" width="90px">
						<p>Chưa có dự án nào trong danh mục yêu thích.</p>
					</div>
				</div>
			</div>
			<div class="wlp-foot on js__wlp-foot">
				<a href="{$clsISO->getLink('favourite')}" class="wlp-btn"><i class="bx bx-list-ul"></i> Xem tất cả</a>
				<button type="button" class="wlp-btn wlp-btn--brand" onclick="$Core.global.compare.compare_stock()"><i class="bx bx-git-compare"></i> Xem so sánh</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var _wlpUid = '{$uid}';
</script>
{literal}
<script type="text/javascript">
(function(){
	var $panel = $('#' + _wlpUid);
	if(!$panel.length){
		return;
	}

	/* chuyen tab: footer [Xem tat ca]+[Xem so sanh] chi hien o tab Can ho */
	$panel.find('.wlp-tab').on('click', function(){
		var _tab = $(this).attr('data-wltab');
		$panel.find('.wlp-tab').removeClass('on');
		$(this).addClass('on');
		$panel.find('.wlp-pane').each(function(){
			$(this).toggleClass('on', $(this).attr('data-wlpane') === _tab);
		});
		$panel.find('.js__wlp-foot').toggleClass('on', _tab === 'stock');
	});

	/* tab Du an: doc localStorage ptl_favs - cung key voi nut tim /thong-tin/ */
	function wlpFavs(){
		try {
			return JSON.parse(localStorage.getItem('ptl_favs') || '[]');
		} catch(e){
			return [];
		}
	}
	function wlpRender(){
		var _favs = $.map(wlpFavs(), String);
		var _n = 0;
		$panel.find('.wlp-item--project').each(function(){
			var _on = $.inArray(String($(this).attr('data-project-id')), _favs) !== -1;
			$(this).toggleClass('d-none', !_on);
			if(_on){
				_n++;
			}
		});
		$panel.find('.js__wlp-n-project').text(_n);
		$panel.find('.js__wlp-empty-project').toggleClass('d-none', _n > 0);
		/* dong bo badge tim tren header = can ho (server) + du an (localStorage) */
		var $badge = $('#number_wishlist');
		if($badge.length){
			if($badge.data('wlbase') === undefined){
				$badge.data('wlbase', parseInt($badge.text(), 10) || 0);
			}
			$badge.text(($badge.data('wlbase') || 0) + _n);
		}
	}
	/* Bo luu CAN HO: toggle_wishlist (he cu) tu xoa dong + tru badge header,
	   o day chi can tru count tren tab + ha "wlbase" de cac lan sync
	   badge sau nay (theo tim du an) khong cong nguoc gia tri cu. */
	$panel.on('click', '.pop_favourite', function(){
		if(!$(this).hasClass('saved')){
			return;
		}
		var $b = $('#number_wishlist');
		if($b.length && $b.data('wlbase') !== undefined){
			$b.data('wlbase', Math.max(0, ($b.data('wlbase') || 0) - 1));
		}
		var $n = $panel.find('.js__wlp-n-stock');
		var _cur = parseInt($n.first().text(), 10) || 0;
		$n.text(Math.max(0, _cur - 1));
	});

	$panel.on('click', '.js__wlp-unsave-project', function(e){
		e.preventDefault();
		var _id = String($(this).attr('data-project-id'));
		var _favs = $.map(wlpFavs(), String);
		var _idx = $.inArray(_id, _favs);
		if(_idx !== -1){
			_favs.splice(_idx, 1);
		}
		try {
			localStorage.setItem('ptl_favs', JSON.stringify(_favs));
		} catch(e2){}
		wlpRender();
	});
	wlpRender();
})();
</script>
{/literal}
