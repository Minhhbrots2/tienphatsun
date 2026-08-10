{literal}
<style>
.top-ranker.comp-anim{ background-size:200% 200%; animation:compGradMove 8s ease infinite; }
@keyframes compGradMove{ 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
.top-ranker .table-ranker td{ color:#fff; }
.top-ranker .table-ranker .fw-semibold{ font-weight:600; }
.top-ranker .text-white-50{ color:rgba(255,255,255,.78) !important; }
</style>
{/literal}
{if !empty($boards)}
	{foreach from=$boards item=b}
	{* Gradient set inline (data-driven), hiệu ứng qua class .comp-anim (CSS tĩnh phía trên) *}
	<div class="top-ranker mt-2 rounded-2{if $clsComp->isGradientAnimated($b.program)} comp-anim{/if}" style="background:{$clsComp->gradientCss($b.program)};color:#fff">
		<div class="top-ranker-header d-flex align-items-center gap-2 gap-lg-3 mb-3">
			{if $b.program.icon}<i class="bx {$b.program.icon} text-white" style="font-size:32px"></i>{else}<img class="w-px-40" src="{$header_configs.LogoWhite}" />{/if}
			<div class="ranking-title ext text-white {if $deviceType ne 'phone'}fs-18{else}fs-16{/if}">
				<span class="text-upper">{$b.program.name|escape}</span><br />
				<small>( {$b.start_txt} - {$b.end_txt} )</small>
			</div>
		</div>
		<div class="top-ranker-body position-relative zindex-2">
			<table class="table table-ranker" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th class="align-center w-px-50 h-px-40 text-center">STT</th>
						<th class="align-center h-px-40">Họ và tên</th>
						<th class="align-center h-px-40 text-center">Điểm</th>
						{if $deviceType ne 'phone'}<th class="align-center h-px-40 text-center">Xếp hạng</th>{/if}
					</tr>
				</thead>
				<tbody>
					{if !empty($b.ranking)}
						{foreach from=$b.ranking item=r}
						<tr class="nohover" style="cursor:pointer" onclick="$Core.competition.detail({$b.program.id}, {$r.profile_id})">
							<td class="text-center">{if $r.stt eq 1}<span class="text-warning">&#127942;</span>{elseif $r.stt eq 2}&#129352;{elseif $r.stt eq 3}&#129353;{else}{$r.stt}{/if}</td>
							<td>
								<div class="d-flex align-items-center gap-2">
									<img src="{$r.avatar}" onerror="this.src='{$URL_IMAGES}/no-avatar.jpg'" class="rounded-circle flex-shrink-0" width="30" height="30" style="object-fit:cover" />
									<div>
										<div class="fw-semibold">{$r.name|escape}{if $r.profile_id eq $me_id} <span class="badge bg-warning text-dark">Bạn</span>{/if}</div>
										{if $r.dept}<small class="text-white-50">{$r.dept|escape}</small>{/if}
									</div>
								</div>
							</td>
							<td class="text-center fw-bold">{$r.score}</td>
							{if $deviceType ne 'phone'}<td class="text-center">{if $r.rank_name}{$r.rank_name|escape}{else}--{/if}</td>{/if}
						</tr>
						{/foreach}
					{else}
						<tr><td colspan="4" class="text-center text-white-50 py-3">Chưa có dữ liệu.</td></tr>
					{/if}
				</tbody>
			</table>
		</div>
		{if !empty($b.program.tiers) || !empty($b.program.ranks)}
		<div class="top-ranker-note position-relative zindex-2 mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.25)">
			<div class="text-white fw-semibold mb-2" style="font-size:13px"><i class="bx bx-info-circle me-1"></i>Cơ chế tính điểm</div>
			{if !empty($b.program.tiers)}
			<div class="text-white-50 mb-2" style="font-size:12px;line-height:1.9">
				Mỗi giao dịch quy đổi theo giá trị căn (tỷ) — <span class="text-white">ĐQ</span> độc quyền / <span class="text-white">QC</span> quỹ chéo:<br />
				{foreach from=$b.program.tiers item=t name=tf}{if $t.from || $t.to}{if !$smarty.foreach.tf.first} &middot; {/if}<span class="text-white">{$t.from}{if $t.to}–{$t.to}{else}+{/if} tỷ</span>: {$t.exclusive}/{$t.cross}đ{/if}{/foreach}
			</div>
			{/if}
			{if !empty($b.program.ranks)}
			<div class="text-white-50" style="font-size:12px;line-height:2">
				Danh hiệu:
				{foreach from=$b.program.ranks item=rk}
				<span class="me-2 d-inline-block">
					<span class="badge bg-warning text-dark">{$rk.name|escape}</span> &ge; {$rk.from}đ{if $rk.reward} &middot; <span class="text-white">{$rk.reward|escape}</span>{/if}
				</span>
				{/foreach}
			</div>
			{/if}
		</div>
		{/if}
		<div class="text-end mt-2 position-relative zindex-2">
			<a href="{$PCMS_URL}/chuong-trinh-{$clsComp->programHash($b.program.id)}" class="text-white" style="font-size:13px;text-decoration:underline">Xem toàn bộ bảng xếp hạng &rarr;</a>
		</div>
	</div>
	{/foreach}
{else}
	<div class="top-ranker mt-2 rounded-2 p-3 text-center text-white-50">Chưa có chương trình thi đua nào đang chạy.</div>
{/if}
{literal}
<script type="text/javascript">
	$Core.competition = $Core.competition || {};
	$Core.competition.detail = function(program_id, staff_id){
		$Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=competition&act=detail', {'program_id':program_id,'staff_id':staff_id}, function(resp){
			$Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', resp.html, resp.uid);
		}, 'json');
	};
</script>
{/literal}
