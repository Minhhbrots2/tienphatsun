<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>Chọn phân khu phụ trách {$oneUser.first_name} {$oneUser.last_name}</strong></h3>
		</div>
		<form action="" method="post" id="frmPermiss" encrupt="miltipart/form-data">
			<div class="modal-body">
				<fieldset class="box_check">
					<legend>Tài khoản CA phụ trách</legend>
					<div class="">
						<select placeholder="Giám đốc dự án" name="staff_permiss_id" class="form-control iso-select2">
							<option value="0">--Chọn--</option>
							{foreach from=$list_profile item=_oProfile key=key name=i}
								<option value="{$_oProfile.profile_id}" {if $more_information.staff_permiss_id eq $_oProfile.profile_id}selected{/if}>{$_oProfile.full_name}</option>
							{/foreach}
						</select>
					</div>
				</fieldset>
				{foreach from=$arr_project item=_oItem key=key name=i}
					{assign var=arr_block value=$_oItem.arr_block}
					<fieldset class="box_check">
						<legend>{$_oItem.title}</legend>
						<div class="form-check">
							<input class="form-check-input check_all" type="checkbox" value="all" id="all_{$key}_{$uid}" onChange="$Core.user.checkedAll(this,event)">
							<label class="form-check-label" for="all_{$key}_{$uid}">Chọn tất cả</label>
						</div>
						<div class="row">
							{foreach from=$arr_block item=_oBlock key=k_block name=i}
								<div class="col-md-4">
									<div class="form-check">
										<input class="form-check-input chkitem" type="checkbox" name="block_permiss[]" value="{$_oBlock.property_id}" id="block_{$_oBlock.property_id}_{$uid}" onChange="$Core.user.loadList()" {if $clsISO->checkItemInArray($_oBlock.property_id,$block_permiss)}checked{/if} >
										<label class="form-check-label" for="block_{$_oBlock.property_id}_{$uid}">{$_oBlock.title}</label>
									</div>
								</div>
							{/foreach}
						</div>
					</fieldset>	
				{/foreach}			
			</div>
			<input type="hidden" name="user_id" value="{$user_id}" />
			<div class="modal-footer">
				<button class="btn btn-primary" onClick="$Core.user.save_permiss_stock(this,event)">Lưu</button>
			</div>
		</form>
	</div>
</div>