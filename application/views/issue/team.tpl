<div class="container-xxl flex-grow-1 pt-0 container-p-y issue_page">
	<div class="issue-head">
		<div class="ih-title">
			<h1>Sức khỏe nhóm</h1>
			<p class="ih-sub">Tải công việc &amp; quá hạn theo từng thành viên</p>
		</div>
		<div class="issue-head-actions">
			<a href="/issue.html" class="issue-icon-btn" title="Về danh sách"><i class="bx bx-list-ul"></i></a>
			<button type="button" class="issue-icon-btn" id="issue-team-refresh" title="Làm mới"><i class="bx bx-refresh"></i></button>
		</div>
	</div>

	<div id="issue-team-holder">
		<div class="issue-team-loading"><i class="bx bx-loader-alt bx-spin"></i> Đang tải bảng nhóm…</div>
	</div>

	<div id="issue-team-drill" class="issue-team-drill">
		<div class="itd-backdrop"></div>
		<div class="itd-panel">
			<button type="button" class="itd-close" title="Đóng">&times;</button>
			<div class="itd-body"></div>
		</div>
	</div>
</div>

{literal}
<script>
(function(){
	function loadBoard(){
		$('#issue-team-holder').html('<div class="issue-team-loading"><i class="bx bx-loader-alt bx-spin"></i> Đang tải bảng nhóm…</div>');
		$.post(PCMS_URL+'/index.php?mod=issue&act=load_team_board', {}, function(res){
			if(res && res.msg==='_success'){ $('#issue-team-holder').html(res.html); }
			else { $('#issue-team-holder').html('<div class="issue-team-empty">Không tải được bảng nhóm.</div>'); }
		}, 'json');
	}
	function closeDrill(){ $('#issue-team-drill').removeClass('open'); }
	$(function(){
		loadBoard();
		$('#issue-team-refresh').on('click', loadBoard);
		$(document).on('click', '.issue-team-overdue', function(e){
			e.preventDefault();
			var pid = $(this).data('pid');
			if(!pid){ return; }
			$.post(PCMS_URL+'/index.php?mod=issue&act=load_overdue', {assign_to_id: pid}, function(res){
				if(res && res.msg==='_success'){
					$('#issue-team-drill .itd-body').html(res.html);
					$('#issue-team-drill').addClass('open');
				}
			}, 'json');
		});
		$('#issue-team-drill').on('click', '.itd-backdrop, .itd-close', closeDrill);
	});
})();
</script>
{/literal}
