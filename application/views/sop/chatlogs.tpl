<div class="container-xxl flex-grow-1 pt-1 pb-2 container-p-y">
	<div class="d-flex align-items-center flex-wrap justify-content-between mb-2">
		<div class="vRHjwnrkGa d-flex align-items-center gap-2">
			<a href="{$PCMS_URL}" class="back goToPage" title="Quay lại">
				<img src="{$smarty.const.ICON_BACK}" alt="Quay lại" />
			</a>
			<div class="ysXwpeiJqL">
				<h4 class="fw-bold mb-1">Tin Zalo Group</h4>
				<small class="text-muted fs-13">Tổng hợp các tin nhắn từ Zalo Group</small>
			</div>
		</div>
		<div class="xRuJxIfsSr">
			{$core->getBlock('chatlogs_search')}
		</div>
	</div>
	<div class="chatlogs-container">
		<div class="chatlogs overflow-y-auto">
			{section name=i loop=$list_preloaders max=20}
			<div class="awe__chat-item awe__chat-preloader js__chat-item">
				<div class="w-100 d-flex gap-2">
					<div class="awe__chat-avatar">
						<a class="bs-webui-popover" data-target="webuiPopover19">
							<div class="w-px-40 h-px-40 animate-bg rounded-pill"></div>
						</a>
					</div>
					<div class="awe__chat-item-body relative py-2 px-4 rounded-3 bg-white">
						<div class="animate-bg mb-2 w-px-100 h-px-15 rounded-2"></div>
						<div class="awe__chat-description mb-2">
							<div class="animate-bg mb-1 w-px-150 h-px-15 rounded-2"></div>
							<div class="animate-bg mb-1 w-px-100 h-px-15 rounded-2"></div>
							<div class="animate-bg w-px-200 h-px-15 rounded-2"></div>
						</div>
						<div class="animate-bg w-px-100 h-px-15 rounded-2"></div>
					</div>
				</div>
			</div>
			{/section}
		</div>
		<div class="clearfix"></div>
		<div class="chatlogs_more d-flex justify-content-center">
			<button type="button" onClick="$Core.sop.load_chatlogs_more(this, event)" class="showmorethis d-none text-muted">
				<i class="fa fa-circle-o-notch fa-spin fa-fw"></i>
				<span>Tải thêm</span>
			</button>
		</div>
	</div>
</div>
{literal}
<style type="text/css">
	.chatlogs{
		height:calc(100vh - 200px);
	}
</style>
<script type="text/javascript">
	$(function(){
		setTimeout(() => {
			$Core.sop.load_chatlogs({});
		}, 1000);
	});
</script>
{/literal}