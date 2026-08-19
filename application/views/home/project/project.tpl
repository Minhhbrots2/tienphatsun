<link rel="stylesheet" type="text/css" href="{$URL_CSS}/project.css?v={$upd_version}" />
<div class="container-xxl flex-grow-1 container-p-y pt-2 ptl-page">
	<div class="ptl-head">
		<h4 class="ptl-head__title">Danh sách dự án</h4>
		<a data-toggle="ripple" href="{$clsISO->getLink('stock')}" class="btn ptl-btn ptl-btn--brand js__dropdown-stock"><i class="bx bx-table"></i> Bảng hàng dự án</a>
	</div>
	<div class="ptl-stats">
		<div class="ptl-stat">
			<span class="ptl-stat__icon c1"><i class="bx bx-buildings"></i></span>
			<div><p class="ptl-stat__label">Tổng dự án</p><p class="ptl-stat__value">{$arr_kpis.total}<small>Dự án</small></p></div>
		</div>
		<div class="ptl-stat">
			<span class="ptl-stat__icon c2"><i class="bx bx-badge-check"></i></span>
			<div><p class="ptl-stat__label">Đang mở bán</p><p class="ptl-stat__value">{$arr_kpis.open}<small>Dự án</small></p></div>
		</div>
		<div class="ptl-stat">
			<span class="ptl-stat__icon c4"><i class="bx bx-group"></i></span>
			<div><p class="ptl-stat__label">Chủ đầu tư</p><p class="ptl-stat__value">{$arr_kpis.investors}<small>Đơn vị</small></p></div>
		</div>
		<div class="ptl-stat">
			<span class="ptl-stat__icon c5"><i class="bx bx-map"></i></span>
			<div><p class="ptl-stat__label">Tỉnh / Thành</p><p class="ptl-stat__value">{$arr_kpis.cities}<small>Khu vực</small></p></div>
		</div>
	</div>
	<div class="ptl-regions" id="ptl-regions">
		<button type="button" class="on" data-area="all"><i class="bx bx-target-lock"></i> Tất cả</button>
		{if !empty($lstArea)}
			{foreach from=$lstArea item=_oArea}
				{if !empty($arr_project_area[$_oArea.setting_id])}
					<button type="button" data-area="{$_oArea.setting_id}">{$_oArea.title|escape}</button>
				{/if}
			{/foreach}
		{/if}
	</div>
	<div class="ptl-filters">
		<div class="ptl-filters__selects">
		{if !empty($arr_filter_cities)}
		<div class="ptl-fsel"><i class="bx bx-map-pin"></i>
			<select id="ptl-f-city">
				<option value="">Tỉnh / Thành</option>
				{foreach from=$arr_filter_cities key=_cid item=_ctitle}
					<option value="{$_cid}">{$_ctitle|escape}</option>
				{/foreach}
			</select>
		</div>
		{/if}
		{if !empty($arr_filter_investors)}
		<div class="ptl-fsel"><i class="bx bx-user-circle"></i>
			<select id="ptl-f-inv">
				<option value="">Chủ đầu tư</option>
				{foreach from=$arr_filter_investors key=_iid item=_ititle}
					<option value="{$_iid}">{$_ititle|escape}</option>
				{/foreach}
			</select>
		</div>
		{/if}
		<div class="ptl-fsel"><i class="bx bx-category"></i>
			<select id="ptl-f-type">
				<option value="">Loại hình</option>
				<option value="cao">Cao tầng</option>
				<option value="thap">Thấp tầng</option>
				<option value="mix">Cao tầng + Thấp tầng</option>
			</select>
		</div>
		</div>
		<button type="button" class="ptl-clearfilter" id="ptl-clear"><i class="bx bx-x-circle"></i> Xóa bộ lọc</button>
		<div class="ptl-filters__spacer"></div>
		<div class="ptl-viewtoggle" id="ptl-viewtoggle">
			<button type="button" class="on" data-v="grid"><i class="bx bx-grid-alt"></i> Lưới</button>
			<button type="button" data-v="list"><i class="bx bx-list-ul"></i> Gọn</button>
		</div>
	</div>
	<div class="ptl-grid" id="ptl-grid">
		{if !empty($lstArea)}
			{foreach from=$lstArea item=_oItem key=key name=i}
				{if !empty($arr_project_area[$_oItem.setting_id])}
					{foreach from = $arr_project_area[$_oItem.setting_id] item = _oProject}
					{assign var = _detail_link value = $clsProject->getLinkDetail($_oProject.project_id,0,0,'overview',$_oProject)}
					<div class="ptl-card" data-area="{$_oItem.setting_id}" data-city="{$_oProject.city_id}"
						data-inv="{$_oProject.investor_id}" data-st="{$_oProject.project_status}" data-type="{$_oProject.type_key}">
						<div class="ptl-card__media">
							<a href="{$_detail_link}" title="{$_oProject.title|escape}">
								<img class="ptl-card__img" src="{$clsISO->resize_image_url($_oProject.image, 480, 300)}"
									onerror="this.src='{$URL_IMAGES}/no-image.png'" loading="lazy" alt="{$_oProject.title|escape}" />
							</a>
							<span class="ptl-badge ptl-badge--{$_oProject.project_status}">
								{if $_oProject.project_status eq 'soon'}Sắp mở bán{elseif $_oProject.project_status eq 'research'}Đang nghiên cứu{else}Đang mở bán{/if}
							</span>
							<button type="button" class="ptl-card__fav" data-project="{$_oProject.project_id}" title="Thêm vào yêu thích"><i class="bx bx-heart"></i></button>
							{if !empty($_oProject.logo)}
								<span class="ptl-card__logo"><img src="{$clsISO->resize_image_url($_oProject.logo, 0, 40)}" onerror="this.parentNode.style.display='none'" loading="lazy" alt="" /></span>
							{/if}
						</div>
						<div class="ptl-card__body">
							<h3 class="ptl-card__title"><a href="{$_detail_link}" title="{$_oProject.title|escape}">{$_oProject.title|escape}</a></h3>
							<p class="ptl-card__addr" title="{$_oProject.address|escape}"><i class="bx bx-map"></i><span>{$_oProject.address|escape}</span></p>
							<div class="ptl-pstats">
								<div class="ptl-pstat"><b><i class="bx bx-building-house"></i>{if $_oProject.building}{$_oProject.building|escape}{else}--{/if}</b><span>Số tòa</span></div>
								<div class="ptl-pstat"><b><i class="bx bx-home-alt"></i>{if $_oProject.apartment}{$_oProject.apartment|escape}{else}--{/if}</b><span>Căn hộ</span></div>
								<div class="ptl-pstat"><b><i class="bx bx-area"></i>{if $_oProject.arcreage}{$_oProject.arcreage|escape}{else}--{/if}</b><span>Quy mô</span></div>
								<div class="ptl-pstat"><b><i class="bx bx-category"></i>{$_oProject.type_label}</b><span>Loại hình</span></div>
								<div class="ptl-pstat"><b><i class="bx bx-grid-small"></i>{if $_oProject.building_density}{$_oProject.building_density|escape}{else}--{/if}</b><span>Mật độ XD</span></div>
								<div class="ptl-pstat"><b><i class="bx bx-coffee"></i>{if $_oProject.total_utilities gt 0}{$_oProject.total_utilities}{else}--{/if}</b><span>Tiện ích</span></div>
							</div>
						</div>
						<div class="ptl-card__foot">
							<a class="btn ptl-btn ptl-btn--brand" href="{$_detail_link}">Thông tin</a>
							<a class="btn ptl-btn" href="/project/p{$_oProject.project_id}.html"><i class="bx bx-table"></i> Bảng hàng</a>
							{if !empty($_oProject.vr_link)}
								<a class="btn ptl-btn" href="{$_oProject.vr_link|escape}" target="_blank" rel="noopener"><i class="bx bx-street-view"></i> VR360</a>
							{else}
								<span class="btn ptl-btn ptl-btn--disabled" title="Dự án chưa có VR360"><i class="bx bx-street-view"></i> VR360</span>
							{/if}
						</div>
					</div>
					{/foreach}
				{/if}
			{/foreach}
		{/if}
	</div>
	<div class="ptl-empty" id="ptl-empty"><i class="bx bx-search-alt"></i><p>Không có dự án nào khớp bộ lọc.</p></div>
</div>
<script type="text/javascript">
	var PTL_AREA_CITIES = {$arr_area_cities|@json_encode};
	var PTL_ALL_CITIES = {$arr_filter_cities|@json_encode};
</script>
{literal}
<script type="text/javascript">
$(function(){
	var $cards = $('.ptl-card');
	var _f = { area: 'all', city: '', inv: '', type: '' };

	function ptlApply(){
		var _shown = 0;
		$cards.each(function(){
			var $c = $(this);
			var _match = (_f.area === 'all' || String($c.attr('data-area')) === String(_f.area))
				&& (!_f.city || String($c.attr('data-city')) === String(_f.city))
				&& (!_f.inv || String($c.attr('data-inv')) === String(_f.inv))
				&& (!_f.type || $c.attr('data-type') === _f.type);
			$c.toggle(_match);
			if(_match){
				_shown++;
			}
		});
		$('#ptl-empty').toggleClass('on', _shown === 0);
		/* nut Xoa bo loc chi hien khi co filter dang ap dung */
		$('#ptl-clear').toggleClass('on', !!(_f.city || _f.inv || _f.type));
	}

	/* Select tinh/thanh doi theo tab mien: chi hien cac tinh dang co du an
	   thuoc mien do (du lieu arr_area_cities co san tu code cu). */
	function ptlRebuildCities(_area){
		var $sel = $('#ptl-f-city');
		if(!$sel.length){
			return;
		}
		var _opts = '<option value="">Tỉnh / Thành</option>';
		if(_area === 'all'){
			$.each(PTL_ALL_CITIES || {}, function(_cid, _title){
				_opts += '<option value="' + _cid + '">' + _title + '</option>';
			});
		} else {
			var _cities = (PTL_AREA_CITIES || {})[_area] || {};
			$.each(_cities, function(_cid, _oCity){
				_opts += '<option value="' + _oCity.city_id + '">' + _oCity.title + ' (' + _oCity.total + ')</option>';
			});
		}
		$sel.html(_opts);
		_f.city = '';
	}

	$('#ptl-regions button').on('click', function(){
		$('#ptl-regions button').removeClass('on');
		$(this).addClass('on');
		_f.area = $(this).attr('data-area');
		ptlRebuildCities(_f.area);
		ptlApply();
	});
	$('#ptl-f-city').on('change', function(){ _f.city = $(this).val(); ptlApply(); });
	$('#ptl-f-inv').on('change', function(){ _f.inv = $(this).val(); ptlApply(); });
	$('#ptl-f-type').on('change', function(){ _f.type = $(this).val(); ptlApply(); });
	$('#ptl-clear').on('click', function(){
		_f.city = _f.inv = _f.type = '';
		$('#ptl-f-city, #ptl-f-inv, #ptl-f-type').val('');
		ptlApply();
	});

	/* Che do Luoi / Gon - nho lua chon */
	function ptlSetView(_v){
		$('#ptl-viewtoggle button').removeClass('on');
		$('#ptl-viewtoggle button[data-v="' + _v + '"]').addClass('on');
		$('#ptl-grid').toggleClass('list-view', _v === 'list');
		try {
			localStorage.setItem('ptl_view', _v);
		} catch(e){}
	}
	$('#ptl-viewtoggle button').on('click', function(){
		ptlSetView($(this).attr('data-v'));
	});
	try {
		var _saved = localStorage.getItem('ptl_view');
		if(_saved === 'list'){
			ptlSetView('list');
		}
	} catch(e){}

	/* Nut tim: yeu thich du an, luu localStorage theo trinh duyet
	   (he thong wishlist hien tai chi ho tro can ho - stock_id). */
	function ptlLoadFavs(){
		try {
			return JSON.parse(localStorage.getItem('ptl_favs') || '[]');
		} catch(e){
			return [];
		}
	}
	function ptlSaveFavs(_favs){
		try {
			localStorage.setItem('ptl_favs', JSON.stringify(_favs));
		} catch(e){}
	}
	function ptlRenderFavs(){
		var _favs = ptlLoadFavs();
		$('.ptl-card__fav').each(function(){
			var _on = $.inArray(String($(this).attr('data-project')), _favs) !== -1;
			$(this).toggleClass('on', _on);
			$(this).find('i').attr('class', _on ? 'bx bxs-heart' : 'bx bx-heart');
		});
		/* cong so du an da tim vao badge tim tren header
		   (server chi dem can ho trong profile.wishlist) */
		var $badge = $('#number_wishlist');
		if($badge.length){
			if($badge.data('wlbase') === undefined){
				$badge.data('wlbase', parseInt($badge.text(), 10) || 0);
			}
			$badge.text(($badge.data('wlbase') || 0) + _favs.length);
		}
	}
	$('#ptl-grid').on('click', '.ptl-card__fav', function(e){
		e.preventDefault();
		e.stopPropagation();
		var _id = String($(this).attr('data-project'));
		var _favs = ptlLoadFavs();
		var _idx = $.inArray(_id, _favs);
		if(_idx === -1){
			_favs.push(_id);
		} else {
			_favs.splice(_idx, 1);
		}
		ptlSaveFavs(_favs);
		ptlRenderFavs();
	});
	ptlRenderFavs();
});
</script>
{/literal}
