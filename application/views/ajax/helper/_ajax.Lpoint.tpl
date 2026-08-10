{if $template_type eq '_modal'}
<div class="modal-dialog modal-lg">
	<form class="modal-content">
		<div class="modal-header position-relative">
			<div class="d-flex w-100 justify-content-between align-items-center">
				<h5 class="modal-title text-upper">
					{if $deviceType eq 'phone'}
					<span class="text-muted fs-12">Điểm Loyalty</span><br />
					{$clsProfile->getFullName($staff_id, $oProfile)}
					{else}
					Điểm Loyalty {$clsProfile->getFullName($staff_id, $oProfile)}
					{/if}
				</h5>
				<div class="text-danger border rounded-2 px-2 fs-5">
					<img src="{$URL_IMAGES}/point.png" width="12px" />
					<strong>{$clsISO->formatNumber($oProfile.total_Lpoint)}</strong>
				</div>
			</div>
			<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{assign var = Lpoint_Notes value = $clsConfiguration->getValue('SiteMsg_Lpoint_Notes')}
			{if !empty($Lpoint_Notes)}
			<div class="alert alert-warning">{$Lpoint_Notes}</div>
			{/if}
		
			<div class="d-flex gap-2 align-items-center p-3 mb-2 bg-lighter rounded-2">
				<div class="form-group ">
					<input type="text" name="keyword" class="form-control no-focus" 
					placeholder="Nhập từ khoá..." />			
				</div>
				<div class="form-group ">
					<input type="date" name="reg_date" class="form-control no-focus" />			
				</div>
			</div>
			<div class="holder_Lpoint_{$uid}">
				<table class="table">
					<thead><tr>
						<th class="align-center text-center" width="5%">No.</th>
						<th class="align-center">Nội dung</th>
						<th class="align-center text-center" width="5%">Loại điểm</th>
						<th class="align-center text-center" width="5%">Số điểm</th>
						<th class="align-center">Thời gian</th>
					</tr></thead>
					{section name=i loop=$list_preloaders max=30}
					<tr>
						<td><div class="animate-bg rounded-2 w-100 h-px-15"></td>
						<td><div class="animate-bg rounded-2 w-100 h-px-15"></td>
						<td><div class="animate-bg rounded-2 w-100 h-px-15"></td>
						<th class="align-center text-center" width="5%">Số điểm</th>
						<td><div class="animate-bg rounded-2 w-100 h-px-15"></td>
					</tr>
					{/section}
				</table>
			</div>
		</div>
	</form>
</div>
{else}
<div class="overflow-x-auto">
	<table class="table">
		<thead><tr>
			<th class="align-center text-center" width="5%">No.</th>
			<th class="align-center">Nội dung</th>
			<th class="align-center text-center" width="6%">Loại điểm</th>
			<th class="align-center text-center" width="5%">Số điểm</th>
			<th class="align-center">Thời gian</th>
		</tr></thead>
		{if !empty($list_items)}
			{foreach name=i from=$list_items item = _oI}
			<tr class="text-nowrap{if $_oI.is_cancel eq '1'} text-decoration-line-through{/if}">
				<td class="text-center">{$smarty.foreach.i.iteration}</td>
				<td>{$_oI.content}</td>
				<td class="text-left border-end">{$_oI.score_type}</td>
				<td class="text-center text-main fw-bold">{$_oI.symbol}{$_oI.score}</td>
				<td>{$clsISO->convertTimeToText($_oI.reg_date)}</td>
			</tr>
			{/foreach}
		{else}
			<tr>
				<td colspan="4" class="text-center">
					<img src="{$URL_IMAGES}/illustration-empty-results.svg" class="w-px-200" />
					<p class="text-muted">Chưa có lịch sử tích điểm</p>
				</td>
			</tr>
		{/if}
	</table>
</div>
<div id="pager_Lpoint_{$uid}" class="pager_Lpoint_{$uid}"></div>
{/if}