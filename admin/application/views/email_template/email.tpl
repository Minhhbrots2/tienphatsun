{if $template eq '_list'}
	{if $lstEmail}
		{foreach from = $lstEmail key = email_id item = email}
		{assign var = email_type value = $email.email_type}
		<tr>
			<td class="text-left">Gửi email đến - <strong>{if $email_type eq 'email'}{$email.email_address}{else}{$email.email_name} - {$email.email_address}{/if}</strong></td>
			<td class="text-center">
				{if $email.status eq '1'}
				<button type="button" class="btn btn-warning" onClick="status_email_notifier(this)" status="0" holderG="{$holderG}" email_id="{$email_id}">
					{$core->makeIcon('pause', $core->get_Lang('TurnOff'))}
				</button>
				{else}
				<button type="button" class="btn btn-success" onClick="status_email_notifier(this)" status="1" holderG="{$holderG}" email_id="{$email_id}">
					{$core->makeIcon('play', $core->get_Lang('TurnOn'))}
				</button>
				{/if}
			</td>
			<td class="text-center">
				<button type="button" class="btn btn-default" onClick="stop_email_notifier(this)" holderG="{$holderG}" email_id="{$email_id}">{$core->makeIcon('trash', $core->get_Lang('Delete'))}</button>
			</td>
		</tr>
		{/foreach}
	{else}
	<tr>
		<td class="text-center" colspan="3">
			{$clsISO->renderHTMLNoDocument('Chưa có địa chỉ E-Mail')}
		</td>
	</tr>
	{/if}
{else}
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Thêm thông báo {if $holderG eq 'contact'}liên hệ{else}đơn hàng{/if}</h5>
				<button type="button" class="close close_pop" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post">
				<div class="modal-body">
					<div class="form-group lines">
						<label for="" class="form-control-label">Phương thức thông báo</label>
						<select class="iso-selectize required" name="email_type" onChange="hanlder_email_type_change(this)">
							<optgroup label="Phương thức thông báo">
								<option selected value="email">Địa chỉ email</option>
							</optgroup>
							<optgroup label="hoặc gửi email cho nhân viên">
								{section name=i loop=$lstUser}
								<option value="{$lstUser[i].user_id}">{$clsUser->getFullName($lstUser[i].user_id)}</option>
								{/section}
							</optgroup>
						</select>
					</div>
					<div class="form-group email__address-group">
						<label for="" class="form-control-label">Địa chỉ email</label>
						<input type="text" class="form-control required" id="ipn__email-address" autocomplete="off" name="email_address" placeholder="Nhập địa chỉ email" />
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="submit" value="Update" />
					<button type="button" class="btn btn-secondary close_pop" data-dismiss="modal">{$core->get_Lang('Close')}</button>
					<button type="button" class="btn btn-success" holderG="{$holderG}" onClick="add_email_notifier(this)">{$core->get_Lang('Save')}</button>
				</div>
			</form>
		</div>
	</div>
{/if}