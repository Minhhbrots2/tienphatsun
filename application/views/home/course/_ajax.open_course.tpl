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
				{if $_tp eq "event"}
				<div class="form-group mb-2">
					<label for="title" class="form-label mb-1">Tên sự kiện</label>
					<input type="text" class="form-control required form-field" name="title" placeholder="Tên sự kiện" value="{$oneItem.title}">
					<input type="hidden" class="form-field" name="cat_id" value="{$cat_id}">
				</div>
				<div class="form-group form-row mb-2">
					<div class="col-12 col-xxl-6">
						<label for="title" class="form-label mb-1">Dự án</label>
						<select class="form-control form-select form-field" onChange="$Core.course.load_block(this, event)" name="project_id" 
						id="slb_Project_Id" toId="slb_Block_Id_{$uid}" data-width="100%" data-field="project_id" block_id="{$oneItem.block_id}">
							<option value="0">Chọn dự án</option>
							{foreach name=i from=$list_projects item=project}
							<option{if $project.project_id eq $more_information.project_id} selected{/if} value="{$project.project_id}">{$project.code}</option>
							{/foreach}
						</select>
					</div>
					<div class="col-12 col-xxl-6">
						<label for="title" class="form-label mb-1">Phân khu</label>
						<select class="form-control form-select form-field" onChange="$Core.data_central.select_building(this, event)" data-placeholder="Phân khu" data-width="100%" data-header="true" data-filter="true" id="slb_Block_Id_{$uid}" toId="slb_Building_Id" data-field="blocks_ids[]">
							{if !empty($list_ss_blocks)}
								{foreach name=i from=$list_ss_blocks item=block}
								<option{if $clsISO->checkInArray($more_information.block_id, $block.property_id)} selected{/if} value="{$block.property_id}">{$block.title}</option>
								{/foreach}
							{/if}
						</select>
					</div>
				</div>
				{else}
				<div class="form-group form-row mb-2">
					<div class="col-12 col-xxl-8 mb-2 mb-lg-0">
						<label for="title" class="form-label mb-1">Tên sự kiện</label>
						<input type="text" class="form-control required form-field" name="title" placeholder="Tên sự kiện" value="{$oneItem.title}">
					</div>
					<div class="col-12 col-xxl-4">
						<label for="phone" class="form-label mb-1">Danh mục</label>
						<div class="w-100">
							<select name="cat_id" class="form-control form-field iso-select2 required w-100" data-width="100%">
								{$clsProperty->getSelectByProperty('_FAQs', $oneItem.cat_id,"",0,1)}
							</select>
						</div>
					</div>
				</div>
				{/if}
				<div class="form-group form-row mb-2">
					<div class="col-6 col-xxl-3 mb-2 mb-lg-0">
						<label class="form-label mb-1">Bắt đầu</label>
						<input class="form-control form-field w-100 required" type="datetime-local"  name="start_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{$start_date}"/>
					</div>
					<div class="col-6 col-xxl-3 mb-2 mb-lg-0">
						<label class="form-label mb-1">Kết thúc</label>
						<input class="form-control form-field w-100 {if $_tp ne 'event'}required{/if}" type="datetime-local"  name="due_date" min="{$smarty.now|date_format:"%Y-%m-%d"}T{$smarty.now|date_format:"%H:%M"}" value="{$due_date}"/>
					</div>
					<div class="col-12 col-xxl-6">
						<label class="form-label mb-1">Địa điểm</label>
						<input type="text" class="form-control form-field" name="location" required maxlength="255" placeholder="HA02-211 Vinhomes Ocean Park 1" value="{$oneItem.location}" />
					</div>
				</div>
				<div class="d-flex align-items-center mb-2">
					<a class="text-muted cursor-pointer" onclick="$Core.util.toggle_block(this, event)" toId="more_info_{$uid}">
						<i class="bx bx-chevron-down"></i> Thêm thông tin
					</a>
				</div>
				<div id="more_info_{$uid}" class="d-none mb-2">
					<div class="form-group mb-2">
						<label class="form-label mb-1">Link chia sẻ</label>
						<input class="form-control form-field w-100" type="text" placeholder="https://" name="link" value="{$more_information.link}"/>
					</div>
					<div class="form-group">
						<label class="form-label mb-1">Người quản lý</label>
						<select class="iso-selectizeImageSearch form-field w-100" placeholder="Thêm người quản lý" multiple="multiple" name="manager_ids" 
						 data-url="{$PCMS_URL}/index.php?mod=home&act=list_staff&holderG=permiss" data-optgroup="false">
						{if !empty($oneItem.manager_ids)}
							{foreach from=$oneItem.manager_ids item = _profile_id}
							<option value="{$_profile_id}" selected>{$clsProfile->getFullName($_profile_id)}</option>
							{/foreach}
						{/if}
						 </select>
					</div>
				</div>
				<div class="form-group form-row mb-2">
					<div class="col-12">
						<label class="form-label mb-1">Nội dung</label>
						<textarea class="form-control form-field isoTextArea" cols="255" rows="3" placeholder="Kết quả thực tế" 
							name="content" data-field="content" id="{$clsISO->getUniqid()}">{$oneItem.content}</textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label mb-1">Thành phần tham gia</label>
					<fieldset class="p-3 bg-lightest rounded-2">
						<div class="form-group">
							{assign var = uid value = $clsISO->getUniqid()}
							<div class="radio mb-2">
								<input name="is_all_staff" class="form-check-input" id="{$uid}" type="radio" 
									value="1" {if $oneItem.is_all_staff eq '1' || !isset($oneItem.is_all_staff)} checked{/if}>
								<label for="{$uid}">Tất cả nhân viên</label>
							</div>
							{assign var = uid value = $clsISO->getUniqid()}
							<div class="radio mb-2">
								<input name="is_all_staff" class="form-check-input" id="{$uid}" type="radio" 
									value="0" {if $oneItem.is_all_staff eq '0'} checked{/if}>
								<label for="{$uid}">Phòng ban hoặc nhân viên</label>
							</div>	
							<div class="pl-4 unit_staff mb-2"{if $oneItem.is_all_staff ne '0'} style="display: none"{/if}>
								<div class="form-group">
									<div class="w-100 mb-2">
										<label class="form-label mb-1">{$core->get_Lang('Phòng ban')}</label>
										<div class="w-100 clearfix"></div>
										<select name="list_department_id" class="form-control form-field iso-select2 select2 w-100" data-width="100%" 
											multiple="true" data-placeholder="Chọn phòng ban tham gia">{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$clsISO->getArrayByTextSlash($oneItem.list_department_id),'Phòng ban')}
										</select>
									</div>
									<div class="w-100 mb-0">
										<label class="form-label mb-1">{$core->get_Lang('Nhân viên')}</label>
										<div class="w-100 clearfix"></div>
										<select placeholder="Lựa chọn nhân viên" name="list_profile_id" class="form-control form-field iso-select2 select2 w-100" multiple="true" data-width="100%" data-placeholder="Chọn nhân viên tham gia">
											{assign var=arr_profile_ids value=$clsISO->getArrayByTextSlash($oneItem.list_profile_id)}
											{foreach name=i from=$list_profiles item=prof}
											<option{if $clsISO->checkItemInArray($prof.profile_id, $arr_profile_ids)} selected{/if} value="{$prof.profile_id}">{$clsProfile->getFullName($prof.profile_id, $prof)}</option>
											{/foreach}
										</select>
									</div>
								</div>
							</div>						
							{assign var = uid value = $clsISO->getUniqid()}
							<div class="radio mb-2">
								<input name="is_all_staff" class="form-check-input" id="{$uid}" type="radio" 
									value="2"{if $oneItem.is_all_staff eq '2'} checked{/if}>
								<label for="{$uid}">Nhóm nhân viên</label>
							</div>
							<div class="pl-4 unit_group_staff" {if $oneItem.is_all_staff ne '2'} style="display: none"{/if}>
								<div class="form-group">
									<div class="w-100">
										<select name="list_group_profile_id" class="form-control form-field iso-select2 select2 w-100" data-width="100%" multiple="true" data-placeholder="Chọn nhóm nhân viên">
											{assign var=arr_group_id value=$clsISO->getArrayByTextSlash($oneItem.list_group_profile_id)}
											{foreach name=i from=$lstGroupProfile item=group}
												<option{if $clsISO->checkItemInArray($group.group_profile_id, $arr_group_id)} selected{/if} value="{$group.group_profile_id}">{$clsGroupProfile->getTitle($group.group_profile_id, $group)}</option>
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
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
				<button type="button" class="btn btn-outline-primary" onClick="$Core.course.open_course(this,event)" 
					data-course_id="{$course_id}" _tp="{$_tp}" data-type="{$action}">Lưu lại</button>
				<button type="button" class="btn btn-outline-success" onClick="$Core.course.open_course(this,event)" 
					data-course_id="{$course_id}" _tp="_ZALO" data-type="{$action}">Lưu & Gửi Zalo</button>
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
			var is_all_staff = $('input[name=is_all_staff]:checked',form).val();
			if(is_all_staff == 1){
				console.log(1);
				$('.unit_staff',form).hide();
				$('.unit_group_staff',form).hide();
			}else if(is_all_staff == 2){
				console.log(2);
				$('.unit_staff',form).hide();
				$('.unit_group_staff',form).show();
			} else {
				console.log(3);
				$('.unit_staff',form).show();
				$('.unit_group_staff',form).hide();									
			}
		});
	});
</script>
{/literal}