<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
	<form class="d-none" enctype="multipart/form-data">
		<input id="select_video_{$uid}" accept="video/*" type="file" uid={$uid} tp="video" onchange="$Core.inspire.upload_file(this,event)" charset="UTF-8" name="video">
	</form>
	<form method="POST" class="modal-content" enctype="multipart/form-data">
		<div class="modal-header">
			<h5 class="modal-title">{if $action eq '_edit'}Sửa{else}Thêm{/if} {$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="form-row">
				<div class="col-12 mb-2">
					<div class="form-group">
						<label>Tiêu đề</label>
						<input type="text" autocomplete="off" name="title" class="form-control required" placeholder="Tiêu đề" data-text="Tiêu đề" value="{$oneItem.title}" >
					</div>					
				</div>
				<div class="col-12 mb-2">
					<div class="form-group">
						<label>Tác giả</label>
						<input type="text" id="author_{$uid}" name="author" class="form-control" placeholder="Tác giả" data-text="Tác giả" value="{if !empty($oneItem)}{$oneItem.author}{else}{$oneProfile.full_name}{/if}">
					</div>					
				</div>
				<div class="col-12 mb-2">
					<div class="form-group">
						<label for="" class="">Video</label>
						<div class="input-group">
							<input type="text" placeholder="Link youtube..." name="link_video" data-text="Video" class="form-control required link_video_{$uid}" onpaste="$Core.inspire.getTimeVideo(this, event)" maxlength="255" value="{$link_video}" />
							<button class="btn btn-default bg-lighter">hoặc</button>
							<button type="button" toId="select_video_{$uid}" uid="{$uid}" onClick="$Core.inspire.select_video(this, event)" class="btn btn-outline-default">{$core->makeIcon('upload','Video')}</button>
						</div>
					</div>
				</div>
			</div>	
		</div>
		<div class="modal-footer">
			<input type="hidden" name="lesson_id" value="{$lesson_id}" >
			<button type="button" data-toggle="ripple" class="btn btn-outline-secondary flex-fill" data-bs-dismiss="modal">Hủy bỏ</button>
			<button type="button" data-toggle="ripple" class="btn btn-primary flex-fill" cat_id="{$smarty.const._CAT_INSPIRE_ID}" training_id="{$training_id}" 
				onClick="$Core.inspire.save_inspire(this, event)">Lưu lại</button>
		</div>
	</form>
</div> 