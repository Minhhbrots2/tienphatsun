<div class="modal-dialog modal-sm">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title">
				<strong>{__('Import Client')}</strong>
			</h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data" id="frmFollowUpCalendar">
			<div class="modal-body">
				<div class="form-group">
					<label>{__('Client ID')}</label>
					<input type="number" min="0" onFocus="this.select();" class="form-control" style="margin-bottom: 5px;" onChange="ejs_get_name_client(this);" name="client_id" />
					<span class="holder_client_import text-green hidden"></span>
				</div>
				<div class="form-group">
					<label>{__('Group')}</label>
					<select name="type_id" class="form-control">
						{$clsISO->getSelectClientGroup(0)}
					</select>
				</div>
				<div class="form-group">
					<label>{__('Admin')}</label>
					<select name="admin_id" class="form-control iso-selectbox {if $permiss_assign_admin eq '0'} disabled{/if}" data-width="100%" data-placeholder="{__('Admin')}">
						{$clsISO->getSelectUser($adminid)}
					</select>
				</div>
				<div class="form-group">
					<label>{__('Type')}</label>
					<select name="type_id" class="form-control">
						{$clsISO->getSelectByPropertyTypeNotTitle('_CONTACT_TYPE',$type_id)}
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success js_start-import-client">{__('Import')}</button>
				<button type="button" class="btn btn-default close_pop" data-dismiss="modal" aria-hidden="true">{__('Close')}</button>
			</div>
		</form>
	</div>
</div>