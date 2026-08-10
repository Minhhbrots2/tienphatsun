<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" enctype="multipart/form-data">
			<div class="modal-body form-horizontal">
				<div class="form-group">
					<label class="col-xs-12 col-md-4 text-right col-form-label">{$core->get_lang('Recharged')}</label>
					<div class="col-xs-12 col-md-8">
						<label class="col-form-label">{$money} {$clsISO->getRate()}</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-md-4 text-right col-form-label">{$core->get_lang('Money')}</label>
					<div class="col-xs-12 col-md-8">
						<input type="text" class="numberonly price-In required form-control" name="money" placeholder="{$clsISO->getRate()}" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-md-4 text-right col-form-label">{$core->get_lang('Content')}</label>
					<div class="col-xs-12 col-md-8">
						<textarea class="form-control" name="message" rows="3" cols="255"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" member_id="{$member_id}" history_id="{$history_id}" class="btn btn-primary submitClick aj_save-recharge">
					{$core->get_Lang('save')}</button> 
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal">{$core->get_Lang('Close')}</button>
			</div>
		</form>
	</div>
</div>