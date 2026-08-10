<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
			<h3 class="modal-title"><strong>Cài đặt</strong></h3>
		</div>
		<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
			<div class="modal-body">
				{if $mod_page eq 'sop'}
				<div class="form-group form-row lines">
					<label class="col-form-label col-md-8">Duyệt trước khi hiển thị</label>
					<div class="col-md-4">
						<label class="switch">
							<input type="checkbox" name="sop_moderation"{if $arr_setting.sop_moderation eq '1'} checked{/if} value="1"  />
							<span class="slider round"></span>
						</label>
					</div>
				</div>
				{elseif $mod_page eq 'leasing'}
				<div class="form-group form-row">
					<label class="col-form-label col-md-8">Duyệt trước khi hiển thị</label>
					<div class="col-md-4 text-right">
						<label class="switch">
							<input type="checkbox" name="leasing_moderation"{if $arr_setting.leasing_moderation eq '1'} checked{/if} value="1"  />
							<span class="slider round"></span>
						</label>
					</div>
				</div>
				{/if}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" onClick="$Core.global.save_setting(this, event)" mod_page="{$mod_page}">
					{$core->makeIcon('check', $core->get_Lang('Save'))}
				</button>
			</div>
		</form>
	</div>
</div>