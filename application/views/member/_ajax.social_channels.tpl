<div class="modal-dialog modal-dialog-centered" role="document">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title"><i class="bx bx-share-alt me-1"></i> Kênh mạng xã hội</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
			<div id="social_channels_{$_profile_id}" class="social-channels-container">
				{foreach from=$social_channels item=_oSC}
				<div class="social-channel-row d-flex gap-2 align-items-center mb-2">
					<select class="form-select form-control" name="category" style="width:150px;flex:0 0 150px">
						<option value="tiktok" {if $_oSC.category eq 'tiktok'}selected{/if}>TikTok</option>
						<option value="youtube" {if $_oSC.category eq 'youtube'}selected{/if}>Youtube</option>
						<option value="fanpage" {if $_oSC.category eq 'fanpage'}selected{/if}>Fanpage</option>
						<option value="facebook" {if $_oSC.category eq 'facebook'}selected{/if}>Facebook</option>
					</select>
					<input type="text" class="form-control" name="title" value="{$_oSC.title}" placeholder="Tiêu đề" style="flex:1">
					<input type="text" class="form-control" name="link" value="{$_oSC.link}" placeholder="Link" style="flex:2">
					<a href="javascript:void(0);" class="btn btn-icon btn-outline-danger" onclick="$(this).parent().remove()"><i class="bx bx-trash"></i></a>
				</div>
				{/foreach}
			</div>
			<div class="mt-3">
				<a href="javascript:void(0);" profile_id="{$_profile_id}" onclick="$Core.member.add_social_row(this,event)" class="btn btn-outline-secondary">
					<i class="bx bx-plus"></i> Thêm dòng mới
				</a>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Đóng</button>
			<button type="button" class="btn btn-primary" onclick="$Core.member.save_social_channels(this,event)" profile_id="{$_profile_id}">
				<i class="bx bx-save me-1"></i> Lưu lại
			</button>
		</div>
	</form>
</div>
