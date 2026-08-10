<div class="d-flex flex-column align-items-center justify-content-center rank__block-menu">
	<div class="btn-group rank__tab-panel">
		{foreach name=i from=$arr_buttons key= _oK item=_oI}
		<input type="radio" class="btn-check" onChange="$Core.helper.set_ranking_type(this, event)" 
			id="{$_gId}_{$_oK}" name="ranking_type" value="{$_oK}"{if $_oK eq '_all'} checked{/if}>
		<label data-toggle="ripple" class="btn btn-ranking text-fs-13{if $smarty.foreach.i.first} no-border-bottom-left-radius{/if}{if $smarty.foreach.i.last} no-border-bottom-right-radius{/if}" for="{$_gId}_{$_oK}">{$_oI}</label>
		{/foreach}
	</div>
	<div class="rank__tab-menu position-relative">
		<ul class="d-flex justify-content-between rank__tab-nav">
			<li><a href="javascript:void(0);" onClick="$Core.helper.load_top_ranking(this, event)" tp="month" 
				class="text-white rank__tab-link" ranking_type="_all">Tháng</a></li>
			<li><a href="javascript:void(0);" onClick="$Core.helper.load_top_ranking(this, event)" tp="quarter" 
				class="text-white rank__tab-link" ranking_type="_all">Quý</a></li>
			<li><a href="javascript:void(0);" onClick="$Core.helper.load_top_ranking(this, event)" tp="year" 
				class="text-white rank__tab-link" ranking_type="_all">Năm</a></li>
		</ul>
	</div>
</div> 
<div class="rank__block-wrapper">
	<div class="rank__block-column d-flex justify-content-center">
		<div class="rank__block-item rank__top-second">
			<div class="rank__block-number mt-3">2</div>
			<div class="rank__block-profile pl-4">
				<div class="rank__profile-avatar d-flex justify-content-center mb-2">
					<div class="avatar avatar-md overflow-hidden rounded-circle">
						<div class="animate-bg w-100 h-100"></div>
					</div>
				</div>
				<h3 class="rank__profile-name fs-14 text-white font-bold mb-1">
					<div class="animate-bg rounded-1 mx-auto w-px-80"></div>
				</h3>
				<div class="mb-0 text-muted fs-13">
					<div class="animate-bg mx-auto rounded-1 w-px-50"></div>
				</div>
			</div>
		</div>
		<div class="rank__block-item rank__block-onload rank__top-one">
			<div class="rank__block-number mt-0">1</div>
			<div class="rank__block-profile pl-2">
				<div class="rank__profile-avatar d-flex justify-content-center mb-2">
					<div class="avatar avatar-md overflow-hidden rounded-circle">
						<div class="animate-bg rounded-1 w-100 h-100"></div>
					</div>
				</div>
				<h3 class="rank__profile-name fs-14 text-white font-bold mb-1">
					<div class="animate-bg rounded-1 mx-auto w-px-80"></div>
				</h3>
				<div class="mb-0 text-muted fs-13">
					<div class="animate-bg rounded-1 mx-auto w-px-50"></div>
				</div>
			</div>
		</div>
		<div class="rank__block-item rank__top-three">
			<div class="rank__block-number">3</div>
			<div class="rank__block-profile pl-4">
				<div class="rank__profile-avatar d-flex justify-content-center mb-2">
					<div class="avatar avatar-md overflow-hidden rounded-circle">
						<div class="animate-bg w-100 h-100"></div>
					</div>
				</div>
				<h3 class="rank__profile-name fs-14 text-white font-bold mb-1">
					<div class="animate-bg rounded-1 mx-auto w-px-80"></div>
				</h3>
				<div class="mb-0 text-muted fs-13">
					<div class="animate-bg mx-auto w-px-50"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="rank__block-row">
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">4</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">5</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">6</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">7</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">8</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">9</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">10</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		setTimeout(() => {
			$('.rank__tab-link[tp=year]').trigger('click');
		}, 500);
	});
</script>
{/literal}
