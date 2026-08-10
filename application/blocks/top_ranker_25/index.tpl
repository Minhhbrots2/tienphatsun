<div id="my-zoom-wrapper" class="w-100 overflow-hidden rounded-2 position-relative zoom-container-wrapper mb-3">
    <div class="w-100 h-100 zoom-container">
		<div class="box_ranker rounded-2 position-relative overflow-hidden mb-3">			
			<div class="menu_rank menu_rank_pc">
				<button class="btn btn-icon text-white dropdown-toggle  hide-arrow" data-bs-toggle="dropdown">
					<i class='bx bx-menu fs-30'></i>
				</button>
				<div class="rank__tab-menu dropdown-menu">
					<ul class="d-flex justify-content-between rank__tab-nav">
						<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="month" class="text-white rank_link">Tháng</a></li>
						<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="quarter" class="text-white rank_link">Quý</a></li>
						<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="year" class="text-white rank_link">Năm</a></li>
					</ul>
				</div>
			</div>
			<div class="box_content position-absolute left-0">
				<div class="box_title position-relative d-inline-block">
					<div class="d-flex align-item-start gap-1">
						<div class="menu_rank menu_rank_mobile">
							<button class="btn btn-icon text-white dropdown-toggle  hide-arrow" data-bs-toggle="dropdown">
								<i class='bx bx-menu fs-30'></i>
							</button>
							<div class="rank__tab-menu dropdown-menu">
								<ul class="d-flex justify-content-between rank__tab-nav">
									<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="month" class="text-white rank_link">Tháng</a></li>
									<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="quarter" class="text-white rank_link">Quý</a></li>
									<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="year" class="text-white rank_link">Năm</a></li>
								</ul>
							</div>
						</div>
						<img src="{$header_configs.LogoWhite}" class="image_logo w-px-100">
					</div>
					<h2 class="text-upper mb-2">Đại lộ danh vọng</h2>
					<div class="light_title position-absolute left-0 w-100"></div>
				</div>
				<div class="box_body_content text-white">
					<h3 class="text-upper fs-3 mb-2">{$title_content}</h3>
					<p class=" fs-16 text-white">({$title_content_time})</p>
				</div>
			</div>
			<div class="d-flex align-items-end lst_chart">
				{foreach from=$lstItem item=_oItem key=key name=i}
				<div class="item_ranker flex-flow position-relative{if !empty($_oItem.is_none)} is_none{/if} rounded-1" 
					style="height:{$_oItem.col_height}%; background-color:{$_oItem.bgcolor};" >
					<div class="box_avt position-absolute d-flex align-items-center justify-content-center cursor-pointer" style="background:url({$_oItem.bg_image}); background-size:100%; background-position: bottom; background-repeat: no-repeat;{if $smarty.foreach.i.last} width:calc(100% * 1.8){/if}">
						<img src="{$URL_IMAGES}/no-avatar.jpg?v={$upd_version}" 
							class="rounded-pill w-100" width="30" height="30">
					</div>
					<div class="text-center fw-bold d-flex flex-column info_ranker">
						<span class="text-warning"></span>
						<span class="text-main"></span>
					</div>
				</div>
				{/foreach}
			</div>	
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(() => {
		setTimeout(() => {
			$('.rank_link[tp=year]:first-child').trigger('click');
			$('#my-zoom-wrapper').zoomPan({
				zoomStep: 0.5,
				minScale: 1,
				maxScale: 5
			});
		}, 500)
	});
</script>
{/literal}
