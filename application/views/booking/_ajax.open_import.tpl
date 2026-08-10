<div class="modal-dialog modal-dialog-centered">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">Import quỹ ôm</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="d-flex flex-column gap-2">
			{if !empty($arr_projects)}
				{foreach from=$arr_projects item = _oProject name=i}
				<div class="d-block">
					<a onClick="$Core.stock_hug.do_import(this, event)" project_id="{$_oProject.setting_id}" 
						class="btn btn-block btn-outline-default" >{$smarty.foreach.i.iteration}. {$_oProject.title}</a>
				</div>
				{/foreach}
			{/if}
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
		</div>
	</form>
</div>
