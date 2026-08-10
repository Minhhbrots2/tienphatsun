<div class="modal-dialog modal-md">
	<form class="modal-content" method="POST">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Phân quyền</strong></h3>
		</div>
		<div class="modal-body modal-body-scrollable">
			{if !empty($list_permiss)}
				{foreach from=$list_permiss item = _oGroup}
				{assign var = list_items value = $_oGroup.list_items}
				<fieldset>
					<legend>{$_oGroup.title}</legend>
					<div class="row">
						{foreach from=$list_items item = _oItem}
						<div class="col-12 col-md-3 mb-2">
							<div class="d-flex align-items-center" style="height: 40px">
								<input type='hidden' value='0' name='permiss_mod[{$_oItem.code}]'>
								<label class="switch mr-2">
									<input type="checkbox" name="permiss_mod[{$_oItem.code}]"{if $_oItem.checked eq '1'} checked{/if} value="1"  />
									<span class="slider round"></span>
								</label>
								<span>{$_oItem.title}</span>
							</div>
						</div>
						{/foreach}
					</div>
					{if $_oGroup.code eq 'group_billing'}
						{if !empty($list_projects)}
						<fieldset>
							<legend>Phân quyền {$title_permiss}</legend>
							<div class="has-table table-wrapper">
								<table class="table-curved">
									{foreach name=k from=$list_projects item = _oProject}
									{assign var = _projectId value = $_oProject.project_id}
									{assign var = _permiss_billing value = $_oProject.permiss_billing}
									<tr>
										<td width="5%" class="text-center">{$smarty.foreach.k.iteration}</td>
										<td width="20%">{$_oProject.title}</td>
										<td>
											<select name="permiss_billing[{$_projectId}][]" multiple="true" class="form-control iso-select2">
												{if !empty($list_billing_types)}
													{foreach from=$list_billing_types item = _oP}
													<option{if $clsISO->checkItemInArray($_oP.property_id, $_permiss_billing)} selected{/if} value="{$_oP.property_id}">{$_oP.title}</option>
													{/foreach}
												{/if}
											</select>
										</td>
									</tr>
									{/foreach}
								</table>
							</div>
						</fieldset>
						{/if}
					{/if}
				</fieldset>
				{/foreach}
			{/if}
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-success" profile_id="{$profile_id}" profile_type="{$profile_type}" 
				onClick="$Core.member.storage(this, event)">
				<span>Lưu lại</span>
			</button>
		</div>
	</form>
</div>
	