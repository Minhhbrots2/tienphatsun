<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header ui-draggable-handle" style="cursor:move;"> 
				<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
				<h3 class="modal-title">
					<strong>{__('Reschedule FollowUp')}</strong>
				</h3>
			</div>
			<form method="post" action="" enctype="multipart/form-data">
				<div class="modal-body">
					<table class="form" cellpadding="2" cellspacing="2" width="100%">
						<tr>
							<td class="fieldlabel" width="30%">{__('CurrentDate')}</td>
							<td class="fieldarea">
								<i class="fa fa-clock-o"></i> {$clsISO->convertTimeToText($date_id,true)}
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">{__('NewDate')}{$tooltip_a}</td>
							<td class="fieldarea">
								<input type="text" class="form-control datepicker pull-left mr-half" id="datepicker_{$resource_id}" name="date_id" readonly value="{$smarty.now|date_format:"%d/%m/%Y"}" />
								<input type="text" class="form-control timepicker" id="timepicker_{$resource_id}" name="time" value="{$smarty.now|date_format:"%H:%M"}" />
							</td>
						</tr>
						<tr>
							<td class="fieldlabel text-red" valign="top">{__('Reason')}{$tooltip_b}</td>
							<td class="fieldarea">
								<textarea name="message" placeholder="{__('Reason')}" class="form-control" autocomplete="off" rows="2"></textarea>
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">{__('Send Email')}</td>
							<td class="fieldarea">
								<label class="switch">
								  <input type="checkbox" name="is_sendmail" value="1">
								  <span class="slider round"></span>
								</label>
							</td>
						</tr>
						<tr>
							<td class="fieldlabel">{__('Update Reminders')}</td>
							<td class="fieldarea">
								<label class="switch">
								  <input type="checkbox" name="upd_reminder" value="1">
								  <span class="slider round"></span>
								</label>
							</td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-success" onClick="ejs_save_followup_reschedue(this);" {$props}>{$core->makeIcon('check',__('Reschedue'))}</button> 
					<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">{$core->makeIcon('close',__('Close'))}</button>
				</div>
			</form>
		</div>
	</div>