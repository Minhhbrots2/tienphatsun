{* Board đồng bộ style với block trang chủ (.top-ranker). Gradient set inline (data-driven), hiệu ứng qua class .comp-anim (CSS tĩnh ở template). *}
<div class="top-ranker mt-2 rounded-2{if $clsComp->isGradientAnimated($program)} comp-anim{/if}" style="background:{$clsComp->gradientCss($program)};color:#fff">
	<div class="top-ranker-header d-flex align-items-center gap-2 gap-lg-3 mb-3">
		{if $program.icon}<i class="bx {$program.icon} text-white" style="font-size:32px"></i>{else}<img class="w-px-40" src="{$header_configs.LogoWhite}" />{/if}
		<div class="ranking-title ext text-white fs-18">
			<span class="text-upper">{$program.name|escape}</span><br />
			{if !empty($start_txt)}<small>( {$start_txt} - {$end_txt} )</small>{/if}
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
				{if !empty($ranking)}
					{foreach from=$ranking item=r}
					<tr class="nohover" style="cursor:pointer" onclick="$Core.competition.detail({$program.id}, {$r.profile_id})">
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
					<tr><td colspan="4" class="text-center text-white-50 py-3">Chưa có dữ liệu xếp hạng cho chương trình này.</td></tr>
				{/if}
			</tbody>
		</table>
	</div>
	{if !empty($program.tiers) || !empty($program.ranks)}
	<div class="top-ranker-note position-relative zindex-2 mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.25)">
		<div class="text-white fw-semibold mb-2" style="font-size:13px"><i class="bx bx-info-circle me-1"></i>Cơ chế tính điểm</div>
		{if !empty($program.tiers)}
		<div class="text-white-50 mb-2" style="font-size:12px;line-height:1.9">
			Mỗi giao dịch quy đổi theo giá trị căn (tỷ) — <span class="text-white">ĐQ</span> độc quyền / <span class="text-white">QC</span> quỹ chéo:<br />
			{foreach from=$program.tiers item=t name=tf}{if $t.from || $t.to}{if !$smarty.foreach.tf.first} &middot; {/if}<span class="text-white">{$t.from}{if $t.to}–{$t.to}{else}+{/if} tỷ</span>: {$t.exclusive}/{$t.cross}đ{/if}{/foreach}
		</div>
		{/if}
		{if !empty($program.ranks)}
		<div class="text-white-50" style="font-size:12px;line-height:2">
			Danh hiệu:
			{foreach from=$program.ranks item=rk}<span class="me-2 d-inline-block"><span class="badge bg-warning text-dark">{$rk.name|escape}</span> &ge; {$rk.from}đ{if $rk.reward} &middot; <span class="text-white">{$rk.reward|escape}</span>{/if}</span>{/foreach}
		</div>
		{/if}
	</div>
	{/if}
</div>
