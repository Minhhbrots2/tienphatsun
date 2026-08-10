<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" style="max-width: 600px">

	{assign var = uid_file value = $clsISO->getUniqid()}

	<form class="d-none" enctype="multipart/form-data">

		<input id="select_file_{$uid_file}" accept="image/jpeg,image/jpg,image/png,application/pdf" type="file" uid="{$uid_file}" onchange="$Core.notification.upload_image(this,event)" charset="UTF-8" name="image">

	</form>

	<form method="POST" class="modal-content" enctype="multipart/form-data">

		<div class="modal-header">

			{if !empty($table_id)}

				<h5 class="modal-title" id="modalTopTitle">Sửa thông báo</h5>

			{else}

				<h5 class="modal-title" id="modalTopTitle">Thêm mới thông báo</h5>

			{/if}

			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

		</div>

		<div class="modal-body scroller">

			<div class="form-group mb-2">

				<div class="form-group mb-2">

					<label class="w-100 form-label mb-1">Tiêu đề</label>

					<input type="text" class="form-control required" name="title" maxlength="255" 

					placeholder="Nhập tiêu đề" value="{if $table_id gt '0'}{$oneItem.title}{/if}">

				</div>

			</div>

			<div class="form-group mb-2">

				<label for="nameSlideTop" class="form-label">Nội dung</label>

				<textarea id="{$clsISO->getUniqid()}" class="form-control isoTextArea" cols="255" rows="10" data-name="content">{if $table_id gt '0'}{$oneItem.content}{/if}</textarea>

			</div>

			<div class="form-group mb-2">

				<label for="nameSlideTop" class="form-label">Hình ảnh</label>

				<div class="input-group">

					<input type="text" placeholder="Nhập ảnh..." name="image" value="{$oneItem.image}" class="form-control" tp="sheet_price" uid="{$uid_file}" id="image_{$uid_file}" maxlength="255">

					<button type="button" toid="select_file_{$uid_file}" uid="{$uid_file}" onclick="$Core.notification.select_image(this, event)" stock_id="103047" tp="sheet_price" class="btn btn-outline-default"><i class="fa fa-upload"></i> <span>Chọn ảnh</span></button>

				</div>

			</div>

			<div class="bg-lighter rounded-2 mb-2 p-3">

				<div class="form-check text-upper mb-2">

					<input class="form-check-input" type="radio" name="send_notify" value="email" 

						id="send_email_{$uid}" {if $oneItem.send_notify eq 'email'} checked{/if} {if !empty($table_id) && empty($oneItem.is_online)}disabled{/if} >

					<label class="form-check-label" for="send_email_{$uid}"> Thông báo email</label>

				</div>

				<div class="form-check text-upper mb-2">

					<input class="form-check-input" type="radio" name="send_notify" value="zalo" 

					id="send_zalo{$uid}" {if $oneItem.send_notify eq 'zalo' || empty($table_id)} checked{/if} {if !empty($table_id) && empty($oneItem.is_online)}disabled{/if} >

					<label class="form-check-label" for="send_zalo{$uid}"> Thông báo Zalo {$smarty.const.BRAND_NAME}</label>

				</div>

				{if empty($table_id) || (!empty($table_id) && !empty($oneItem.is_online))}

				<div class="d-flex flex-wrap align-items-center gap-2">

					<label for="nameSlideTop" class="form-label mb-0">Gửi thông báo lúc</label>

					<div class="d-flex gap-2">

						{*<div class="input-group w-px-150">

							<input type="text" class="form-control numberonly mimax_number {if !empty($table_id)}update{/if}" name="time_after_send" placeholder="30 phút" aria-label="30 phút" aria-describedby="30 phút" min="30" onChange="$Core.notification.checkMinute(this,event)" value="{$time_after_send}">

							<span class="input-group-text" id="basic-addon13">phút</span>

						</div>*}

						<div class="w-px-130 flex-fill">

							<input type="time" class="form-control no-focus text-dark" min="30" onChange="$Core.notification.checkMinute(this,event)" name="time_end" value="{$end_date}">

						</div>

					</div>

					<div class=""><i>(Tối thiểu sau 30 phút)</i></div>

				</div>

				{/if}

			</div>

		</div>

		<div class="modal-footer justify-content-between">

			<div class="d-flex align-items-center gap-2">

				{if empty($oneItem.is_online)}

					<span>Đã xuất bản</span>				

				{else}

					<label class="switch">

						<input type="checkbox" name="is_trash"{if $oneItem.is_trash eq '0'} checked{/if} value="0">

						<span class="slider round"></span>

					</label>

					<span>Xuất bản</span>

				{/if}

			</div>

			<div class="d-flex gap-2 align-items-center">

				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>

				<button type="button" onClick="$Core.notification.save(this, event)" table_id="{$table_id}" 

				class="btn btn-primary">Lưu lại</button>

			</div>

		</div>

	</form>

</div>