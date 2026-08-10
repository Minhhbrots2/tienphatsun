<div class="modal-dialog modal-ipad-xl"><div class="modal-content">
	<div class="modal-header">
		<h5 class="modal-title mb-2">Cấu hình quỹ Thu/Chi</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	</div>
	<div class="modal-body">
		<div class="form-row">
			<div class="col-12 col-md-4 mb-2 mb-lg-0">
				<h4 class="text-center bg-lighter rounded-2 p-2 mb-1">Danh mục chi phí</h4>
				<div class="border overflow-y-auto hide-scroll-thumb rounded-2 p-2 h-90">
					<ul class="list-group list-unstyled sortable" id="expense">
					{foreach from=$list_property item = _oProp}
						{if $_oProp.ms_value eq $smarty.const._THUCCHI_GROUP_EXPENSE_ID}
						<li class="list-group-item" id="{$_oProp.property_id}">
							<i class='bx bx-move'></i>
							{$_oProp.title}
						</li>
						{/if}
					{/foreach}
					</ul>
				</div>
			</div>
			<div class="col-12 col-md-4 mb-2 mb-lg-0">
				<h4 class="text-center bg-lighter rounded-2 p-2 mb-1">Danh mục</h4>
				<div class="border overflow-y-auto hide-scroll-thumb rounded-2 p-2 h-90" >
					<ul class="list-group list-unstyled sortable" id="category">
					{foreach from=$list_property item = _oProp}
						{if $_oProp.ms_value eq '0'}
						<li class="list-group-item" id="{$_oProp.property_id}">
							<i class='bx bx-move'></i>
							{$_oProp.title}
						</li>
						{/if}
					{/foreach}
					</ul>
				</div>
			</div>
			<div class="col-12 col-md-4">
				<h4 class="text-center bg-lighter rounded-2 p-2 mb-1">Danh mục đầu tư</h4>
				<div class="border overflow-y-auto hide-scroll-thumb rounded-2 p-2 h-90">
					<ul class="list-group list-unstyled sortable" id="invest">
					{foreach from=$list_property item = _oProp}
						{if $_oProp.ms_value eq $smarty.const._THUCCHI_GROUP_INVEST_ID}
						<li class="list-group-item" id="{$_oProp.property_id}">
							<i class='bx bx-move'></i>
							{$_oProp.title}
						</li>
						{/if}
					{/foreach}
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer"></div>
</div></div>
<style>
	.list-group{ 
		min-height:200px; 
		max-height:400px;
	}
</style>