{* _ajax.mt_drill.tpl — Modal drill-down: liệt kê khách theo điều kiện 1 con số tổng. class crm-ld để --ld-* + .crm-ld-urgent* áp đúng. *}
<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header crm-drill-head">
			<div class="min-w-0">
				<h5 class="modal-title crm-drill-title">{$drill_title|escape}</h5>
				<div class="crm-drill-sub"><i class="bx bx-group"></i> {$drill_total} khách</div>
			</div>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
		</div>
		<div class="modal-body crm-drill-body">
			{if $drill_rows}
			<div class="js__mt-drill-list crm-drill-list">
				{$drill_rows_html}
			</div>
			{if $drill_has_more}
			<div class="text-center pt-2 pb-1 js__mt-drill-more-wrap">
				<button type="button" class="btn btn-sm btn-outline-default js__mt-drill-more" data-metric="{$drill_metric}" data-from="{$drill_from}" data-to="{$drill_to}" data-rep="{$drill_rep}" data-dept="{$drill_dept}" data-page="{$drill_next}"><i class="bx bx-chevron-down"></i> Tải thêm</button>
			</div>
			{/if}
			{else}
			<div class="text-center text-muted py-4"><i class="bx bx-folder-open fs-3 d-block mb-1"></i> Không có khách phù hợp.</div>
			{/if}
		</div>
	</div>
</div>

