<div class="modal right fade" id="{$uid}" role="dialog">
	<div class="modal-dialog overflow-hidden" role="document">
		<div class="modal-content overflow-hidden">
			<div class="modal-header position-relative">
				<h5 class="modal-title">Trung tâm trợ giúp</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="clearfix"></div>
			<div class="modal-body modal-body-scrollable pt-1 content_helper">
				{$helper_page.content|html_entity_decode}
			</div>
		</div>
	</div>
</div>