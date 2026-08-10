<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
		<div class="row">
			<div class="col-12 col-lg-6 offset-lg-3">
				<div class="euro-wrapper {$theme}"><div class="euro-wrapper-inner">
					<div class="d-flex align-items-center mb-3 gap-2 justify-content-center position-relative">
						{if $theme eq 'blueskin'}
						<img src="{$URL_IMAGES}/logo_euro.png" class="h-px-75">
						{else}
						<img src="{$URL_IMAGES}/euro2024/logo-fh-euro.png" class="h-px-75">
						{/if}
						<a href="javascript:void(0);" class="position-absolute text-white" data-toggle="webui-popover" data-title="Hướng dẫn tính điểm euro" title="Quy định tính điểm Euro 2024" data-placement="auto" data-width="285px" data-trigger="click" data-url="/index.php?mod={$mod}&act=load_config&setting=Euro_Notes" style="right:0"><i class='bx bx-help-circle'></i></a>
					</div>
					<table class="table table-euro">
						<thead><tr>
							<th width="{if $deviceType eq 'phone'}2{else}10{/if}%" class="algin-center text-center">No.</th>
							<th class="algin-center">Họ và tên</th>
							<th class="algin-center text-center">Tỷ lệ</th>
							<th class="algin-center text-center">Điểm</th>
						</tr></thead>
						{foreach name=i from=$list_staffs item = _oItem}
						<tr title="Xem chi tiết" 
							onClick="$Core.home.open_euro(this, event)" 
							row="{$_oItem.row}" total_score="{$_oItem.total_score}" class="nohover{if $max_score eq $_oItem.total_score} text-warning{elseif $second_score eq $_oItem.total_score} text-green{elseif $three_score eq $_oItem.total_score} text-yellow{elseif $top_4_score eq $_oItem.total_score} text-info{/if}">
							<td class="cursor-pointer text-center">
								{if $max_score eq $_oItem.total_score}
								<img src="{$URL_IMAGES}/top-1.png" class="w-px-20" />
								{elseif $second_score eq $_oItem.total_score}
								<img src="{$URL_IMAGES}/euro2024/top-2-green.png" class="w-px-20" />
								{elseif $three_score eq $_oItem.total_score}
								<img src="{$URL_IMAGES}/euro2024/top-3-yellow.png" class="w-px-20" />
								{elseif $top_4_score eq $_oItem.total_score}
								<img src="{$URL_IMAGES}/euro2024/top-4-info.png" class="w-px-20" />
								{elseif $top_5_score eq $_oItem.total_score}
								<img src="{$URL_IMAGES}/top-5.png" class="w-px-20" />
								{else}
									{$smarty.foreach.i.iteration}
								{/if}
							</td>
							<td class="cursor-pointer fw-bold">{$_oItem.full_name}</td>
							<td class="cursor-pointer text-center">{$_oItem.total_goal}/{$_oItem.total_match}</td>
							<td class="cursor-pointer text-center">{$_oItem.total_score}</td>
						</tr>
						{/foreach}
					</table>
				</div>
			</div></div>
		</div>
	</div>
</div>