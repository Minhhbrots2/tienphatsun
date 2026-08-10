{$scriptJs}
<div class="container-xxl flex-grow-1 container-p-y pt-2 pb-0">
	<div class="d-flex flex-wrap justify-content-between align-items-center py-2 {if $deviceType eq 'phone'}gap-2{else}mb-4{/if}">
		<div class="p__left">
			<h4 class="fw-bold mb-1">Trắc nghiệm</h4>
			<span class="text-muted">Các bài trắc nghiệm tại {$smarty.const.BRAND_NAME}</span>
		</div>
		<div class="p__right {if $deviceType eq 'phone'}w-100{/if}">			
			<div class="input-group">
				<button type="button" class="btn btn-outline-default btn_status btn_status_ON_GOING active {if $deviceType eq 'phone'}flex-flow px-2 fs-14{/if}" onClick="$Core.quiz.loadMyQuiz('_ON_GOING')">Đang diễn ra</button>
				<button type="button" class="btn btn-outline-default btn_status btn_status_YES {if $deviceType eq 'phone'}flex-flow px-2 fs-14{/if}" onClick="$Core.quiz.loadMyQuiz('_YES')">Đã làm</button>
				<button type="button" class="btn btn-outline-default btn_status btn_status_NO {if $deviceType eq 'phone'}flex-flow px-2 fs-14{/if}" onClick="$Core.quiz.loadMyQuiz('_NO')">Chưa làm</button>
			</div>	
		</div>
	</div>
	<div class="lst_quiz">
	
	</div>
	
</div>
<script src="https://cdn.rawgit.com/hilios/jQuery.countdown/2.2.0/dist/jquery.countdown.min.js"></script>
<script>
	var current_page = `{$current_page}`;
</script>
{literal}
<script type="text/javascript">
	$(function(){
		$Core.quiz.loadMyQuiz("_ON_GOING");
	});
</script>
{/literal}