<div class="modal-dialog modal-sm" style="max-width: 600px">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-group form-row">
					<label class="col-form-label text-right">Chức vụ</label>
					<select class="form-control iso-select2 required" name="role_id">
						{$clsProperty->getListOption('_ROLE', $oneItem.role_id)}
					</select>
				</div>
				<div class="form-group form-row">
					<label class="col-form-label text-right">Text</label>
					<input type="text" class="form-control" name="text_name" value="{$oneItem.text_name}">
				</div>
				<div class="form-group form-row">
					<label class="col-form-label text-right">Đương nhiệm</label>
					<select class="form-control iso-select2" name="staff_id">
						<option value="0">--Chọn--</option>
						{foreach from=$lstProfile item=_oProfile}
							<option value="{$_oProfile.profile_id}" {if $oneItem.staff_id eq $_oProfile.profile_id}selected{/if}>{$clsProfile->getFullname($_oProfile.profile_id,$_oProfile)}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="level" value="{$level}">
				<input type="hidden" name="id" value="{$id}">
				<input type="hidden" name="parent_id" value="{$parent_id}">
				<input type="hidden" name="common_parent_id" value="{$common_parent_id}">
				<input type="hidden" name="top" value="{$oneItem.position.top}">
				<input type="hidden" name="left" value="{$oneItem.position.left}">
				<button type="button" onClick="$Core.org_chart.render_node(this, event)" data-key="{$key}" class="btn btn-success">
					<span>Lưu lại</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>Đóng</span>
				</button>
			</div>
		</form>
	</div>
</div>
