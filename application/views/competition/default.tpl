{literal}
<style>
.top-ranker.comp-anim{ background-size:200% 200%; animation:compGradMove 8s ease infinite; }
@keyframes compGradMove{ 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
.top-ranker .table-ranker td{ color:#fff; }
.top-ranker .table-ranker .fw-semibold{ font-weight:600; }
.top-ranker .text-white-50{ color:rgba(255,255,255,.78) !important; }
</style>
{/literal}
<div class="container-xxl flex-grow-1 py-3 container-p-y">
	<div class="row">
		<div class="col-12 col-lg-8 mx-auto">
			<div class="mb-3">
				<h4 class="fw-bold mb-0">Thi đua định danh</h4>
				<span class="text-muted">Bảng xếp hạng các chương trình đang chạy</span>
			</div>
			{if empty($boards)}
				<div class="card"><div class="card-body text-center text-muted py-5">Chưa có chương trình thi đua nào đang chạy.</div></div>
			{else}
				{foreach from=$boards item=b}
				<div class="mb-4">
					{include file="./_ajax.ranking.tpl" program=$b.program ranking=$b.ranking start_txt=$b.start_txt end_txt=$b.end_txt me_id=$me_id}
				</div>
				{/foreach}
			{/if}
		</div>
	</div>
</div>
{$scriptJs}
{literal}
<script type="text/javascript">
	$Core = window.$Core || {};
	$Core.competition = $Core.competition || {};
	$Core.competition.detail = function(program_id, staff_id){
		$Core.util && $Core.util.toggleIndicatior && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=competition&act=detail', {'program_id':program_id,'staff_id':staff_id}, function(resp){
			$Core.util && $Core.util.toggleIndicatior && $Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', resp.html, resp.uid);
		}, 'json');
	};
</script>
{/literal}
