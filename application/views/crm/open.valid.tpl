<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" enctype="multipart/form-data">
			<div class="modal-body">
				<table class="table table-responsive" width="100%">
					<thead><tr>
						<th width="5%">No.</th>
						<th>{__('Name')}</th>
						<th>{__('Email')}</th>
						<th>{__('Phone')}</th>
						<th>{__('Admin')}</th>
						<th>{__('Created')}</th>	
					</tr></thead>
					{section name=i loop=$lstPotential}
					{assign var = potential_id value = $lstPotential[i].potential_id}
					<tr>
						<td>{$smarty.section.i.iteration}</td>
						<td><a href="javascript:void(0);" class="ajOpenPotential gotolink" potential_id="{$potential_id}">{$lstPotential[i].name}</a></td>
						<td{if $fieldtype eq 'email'} class="text-red"{/if}>{$lstPotential[i].email}</td>
						<td{if $fieldtype eq 'phone'} class="text-red"{/if}>{$lstPotential[i].phone}</td>
						<td>{$clsUser->getFullName($lstPotential[i].admin_id)}</td>
						<td>{$clsISO->convertTimeToText($lstPotential[i].reg_date)}</td>
					</tr>
					{/section}
				</table>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default pull-right mr-half" data-dismiss="modal">{__("Close")}</button>
			</div>
		</form>
	</div>
</div>