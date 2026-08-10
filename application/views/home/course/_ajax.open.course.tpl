<div class="modal-dialog modal-dialog-scrollable modal-ipad-xl">
	<form method="POST" enctype="multipart/form-data" class="modal-content">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{if $course_id gt '0'}Sửa{else}Thêm{/if} mới sự kiện</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body modal-body-scrollable">									
				{*<div class="form-group mb-3">
					<label class="col-form-label">Hình ảnh</label>
					<div id="article-image-drop" class="article-image-drop">
						<div class="aspect-ratio aspect-ratio--square aspect-ratio--interactive w-100" style="max-height: 200px">
							<img class="preview_image w-100 h-100" id="isoman_show_image" src="{$oneItem.image}" style="object-fit: cover;max-height: 200px;display:none">
						</div>
						<div class="clearfix"></div>
						<div class="ui-stack ui-stack--wrap" style="position:relative">
							<input class="form-field form-control" type="file" name="image" onChange="$Core.course.loadImage(this,event)" style="position:absolute;opacity: 0">
							<button type="buton" class="btn btn-outline-default">Thêm ảnh</button>
						</div>
					</div>
				</div>*}
				<div class="form-group form-row mb-2">
					<div class="col-12 col-xxl-8 mb-2 mb-lg-0">
						<label for="title" class="form-label mb-1">Tên sự kiện</label>
						<input type="text" class="form-control required form-field" name="title" placeholder="Tên sự kiện" value="{$oneItem.title}">
					</div>
					<div class="col-12 col-xxl-4">
						<label for="phone" class="form-label mb-1">Danh mục</label>
						<div class="w-100">
							<select name="cat_id" class="form-control form-field iso-select2 required w-100" data-width="100%">
								{$clsProperty->getSelectByProperty('_FAQs', $oneItem.cat_id)}
							</select>
						</div>
					</div>
				</div>
				<div class="form-group form-row mb-2">
					<div class="col-6 col-xxl-3 mb-2 mb-lg-0">
						<label class="form-label mb-1">Bắt đầu</label>
						<input class="form-control form-field w-100 required" type="datetime-local"  name="start_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{$oneItem.start_date}"/>
					</div>
					<div class="col-6 col-xxl-3 mb-2 mb-lg-0">
						<label class="form-label mb-1">Kết thúc</label>
						<input class="form-control form-field w-100 required" type="datetime-local"  name="due_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{$oneItem.due_date}"/>
					</div>
					<div class="col-12 col-xxl-6">
						<label class="form-label mb-1">Địa điểm</label>
						<input type="text" class="form-control form-field" name="location" required maxlength="255" placeholder="HA02-211 Vinhomes Ocean Park 1" value="{$oneItem.location}" />
					</div>
				</div>
				<div class="form-group form-row mb-2">
					<div class="col-12">
						<label class="form-label mb-1">Nội dung</label>
						<textarea class="form-control form-field isoTextArea" cols="255" rows="3" placeholder="Kết quả thực tế" name="content" data-field="content" id="{$clsISO->getUniqid()}">{$oneItem.content}</textarea>
					</div>
				</div>
				
				<div class="form-group">
					<label class="form-label mb-1">Thành phần tham gia</label>
					<fieldset class="p-3">
						<div class="form-group">
							{assign var = uid value = $clsISO->getUniqid()}
							<div class="radio mb-2">
								<input name="is_all_staff" class="form-check-input" id="{$uid}" type="radio" value="1" {if $oneItem.is_all_staff eq '1' || !isset($oneItem.is_all_staff)} checked{/if}>
								<label for="{$uid}">Tất cả nhân viên</label>
							</div>
							{assign var = uid value = $clsISO->getUniqid()}
							<div class="radio mb-2">
								<input name="is_all_staff" class="form-check-input" id="{$uid}" type="radio" value="0" {if $oneItem.is_all_staff eq '0'} checked{/if}>
								<label for="{$uid}">Phòng ban hoặc nhân viên</label>
							</div>
						</div>
						<div class="pl-4 unit_staff" {if $oneItem.is_all_staff eq '1' || !isset($oneItem.is_all_staff)} style="display: none"{/if}>
							<div class="form-group form-row">
								<div class="col-12">
									<label class="form-label mb-1">{$core->get_Lang('Phòng ban')}</label>
									<div class="w-100 mb-2">
										<select name="list_department_id" class="form-control form-field iso-select2 select2 w-100" data-width="100%" multiple="true" data-placeholder="Chọn phòng ban tham gia">
											{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$clsISO->getArrayByTextSlash($oneItem.list_department_id),'Phòng ban')}
										</select>
									</div>
								</div>
								<div class="col-12">
									<label class="form-label mb-1">{$core->get_Lang('Nhân viên')}</label>
									<div class="w-100">
										<select placeholder="Lựa chọn nhân viên" name="list_profile_id" class="form-control form-field iso-select2 select2 w-100" multiple="true" data-width="100%" data-placeholder="Chọn nhân viên tham gia">
											{assign var=arr_profile_ids value=$clsISO->getArrayByTextSlash($oneItem.list_profile_id)}
											{foreach name=i from=$list_profiles item=prof}
											<option{if $clsISO->checkItemInArray($prof.profile_id, $arr_profile_ids)} selected{/if} value="{$prof.profile_id}">{$clsProfile->getFullName($prof.profile_id, $prof)}</option>
											{/foreach}
										</select>
									</div>
								</div>
							</div>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary" onClick="$Core.course.open_course(this,event)" data-course_id="{$course_id}" data-type="{$action}">Lưu lại</button>
			</div>
		</div>
	</form>
</div>
{literal}
<script type="text/javascript">
	$(function(){
		$_document.on('change', 'input[name=is_all_staff]', function(){
			var _this = $(this);
			var form = _this.closest("form");
			if($('input[name=is_all_staff]:checked',form).val()==1){
				$('.unit_staff',form).hide();
			} else {
				$('.unit_staff',form).show();										
			}
		});
	});
</script>
{/literal}