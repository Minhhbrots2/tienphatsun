<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">Khóa học</span>
				</a>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
<form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
	<div class="ui-layout">
		<div class="row">
			<div class="col-md-8 col-xs-12">
				<div class="ui-layout__item">
					<div class="ui-card">
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Title')}*</label>
									<input type="text" class="form-control required" name="iso-title" value="{$oneItem.title}" placeholder="Tên khóa học" required maxlength="255" />
								</div>
								<div class="form-group">
									<label class="col-form-label">Giảng viên</label>
									<input type="text" class="form-control required" name="iso-author" value="{$oneItem.author}" placeholder="Tên giảng viên" required maxlength="255" />
								</div>
								
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Content')}</label>
									<textarea id="{$clsISO->getUniqid()}" class="form-control isoTextArea edit_profile_field_about" name="content" cols="255" rows="15">{$oneItem.content}</textarea>
								</div>
								<div class="form-group">
									<fieldset class="p-3">
										<div class="form-group">
											{assign var = uid value = $clsISO->getUniqid()}
											<div class="radio mb-2">
												<input name="is_all_staff" class="form-check-input" id="{$uid}" type="radio" value="1" onChange="$Core.training.changeStaff(this,event)" {if $oneItem.is_all_staff eq '1' || !isset($oneItem.is_all_staff)} checked{/if}>
												<label for="{$uid}">Tất cả nhân viên</label>
											</div>
											{assign var = uid value = $clsISO->getUniqid()}
											<div class="radio mb-2">
												<input name="is_all_staff" class="form-check-input" id="{$uid}" type="radio" value="0" onChange="$Core.training.changeStaff(this,event)" {if $oneItem.is_all_staff eq '0'} checked{/if}>
												<label for="{$uid}">Phòng ban hoặc nhân viên</label>
											</div>	
											<div class="pl-4 unit_staff mb-2" {if $oneItem.is_all_staff ne '0'} style="display: none"{/if}>
												<div class="form-group form-row">
													<div class="col-xs-12">
														<label class="form-label mb-1">{$core->get_Lang('Phòng ban')}</label>
														<div class="w-100 mb-2">
															<select{if $oneItem.is_all_staff eq '1'} disabled{/if} name="list_department_id[]" placeholder="Chọn phòng ban tham gia" class="form-control iso-select2" multiple="true">{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$arr_departments_ids,'Phòng ban')}</select>
														</div>
													</div>
													<div class="col-xs-12">
														<label class="form-label mb-1">{$core->get_Lang('Nhân viên')}</label>
														<div class="w-100 mb-2">
															<select{if $oneItem.is_all_staff eq '1'} disabled{/if} name="list_profile_id[]" placeholder="Lựa chọn nhân viên" class="form-control iso-select2" multiple="true">
																{foreach name=i from=$list_profiles item=prof}
																	<option{if $clsISO->checkItemInArray($prof.profile_id, $arr_profile_ids)} selected{/if} value="{$prof.profile_id}">{$clsProfile->getFullName($prof.profile_id, $prof)}</option>
																{/foreach}
															</select>
														</div>
													</div>
													<div class="col-xs-12">
														<label class="form-label mb-1">Nhóm nhân viên</label>
														<div class="w-100 mb-2">
															<select{if $oneItem.is_all_staff eq '2'} disabled{/if} placeholder="Lựa chọn nhóm" name="list_group_profile_id[]" class="form-control iso-select2" multiple="true">
																{assign var=arr_group_id value=$clsISO->getArrayByTextSlash($oneItem.list_group_profile_id)}
																{foreach name=i from=$lstGroupProfile item=group}
																	<option{if $clsISO->checkItemInArray($group.group_profile_id, $arr_group_id)} selected{/if} value="{$group.group_profile_id}">{$clsGroupProfile->getTitle($group.group_profile_id, $group)}</option>
																{/foreach}
															</select>
														</div>
													</div>
												</div>
											</div>	
										</div>
									</fieldset>
								</div>
								<div class="form-group">
									<fieldset>
										<legend>Khóa học</legend>
										<div class="box-title d-flex justify-content-end align-items-center mb-2">
											<button class="btn btn-outline-default" type="button" onClick="$Core.training.open_lesson(this,event)" data-training_id='{$training_id}' data-lesson_id="">{if $deviceType eq 'phone'}Thêm{else}Thêm mới{/if}</button>
										</div>
										<div class="box-body">
											<table class="table table-hover table-vertical table-striped table-responsive TableListLesson">
												<thead><tr>
													<th class="align-center bg-lighter" style="width:60px"></th>
													<th class="align-center bg-lighter" style="width:60px">STT</th>
													<th class="align-left bg-lighter">Tên khóa học</th>
													<th class="align-center text-left bg-lighter w-px-40" style="width:40px"></th>
												</tr></thead>
												<tbody class="list_lesson" id="list_lesson">
												{if !empty($lstLesson)}
													{foreach from=$lstLesson key=key item=item name=i}
														<tr>
															<td class="text-center">{$smarty.foreach.i.iteration}</td>
															<td>{$item.title}</td>
															<td>
																<div class="btn-group">
																	<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown"> <i class="icon-cog"></i> <span class="caret"></span></button>
																	<ul class="dropdown-menu" style="right:0px !important; left: auto">
																		<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.training.open_lesson(this,event)" data-training_id='{$training_id}' data-lesson_id="{$key}"  data-field="history_sale" data-type="edit">Sửa</a></li>
																		<li><a class="dropdown-item" href="javascript:void(0)"  onClick="$Core.training.deleteLesson(this,event)" data-training_id='{$training_id}' data-lesson_id="{$key}" data-field="history_sale">Xoá</a></li>
																	</ul>
																</div>
															</td>
														</tr>
													{/foreach}
												{else}
													<tr>
														<td class="text-center" colspan="2">Danh sách bài học trống</td>	
													</tr>
												{/if}
												</tbody>
											</table>
										</div>
									</fieldset>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="ui-layout__item">
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading">{$core->get_Lang('Status')}</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input {if $oneItem.is_online eq '1'} checked="checked"{/if} name="is_online" value="1" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> {$core->get_Lang('Show')}
									</div>
								</div>
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input{if $oneItem.is_online ne '1'} checked="checked"{/if} name="is_online" value="0" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> {$core->get_Lang('Hide')}
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading">{$core->get_Lang('Category')}</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">									
									<div class="box-body pt-4">
										<select class="iso-selectize custom-select required" required name="cat_id" id="cat_id">
											{$clsISO->getSelectByPropertyTypeTitle('_TRAINING_CAT',$oneItem.cat_id,'Danh mục')}
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card mt-half">
						<header class="ui-card__header">
							<h2 class="ui-heading">{$core->get_Lang('Image')}</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div id="article-image-drop" class="article-image-drop">
									<div class="aspect-ratio aspect-ratio--square aspect-ratio--interactive">
										<input type="hidden" id="isoman_hidden_image" name="isoman_url_image" value="{$oneItem.image}" />
										<img class="aspect-ratio__content" id="isoman_show_image" src="{$oneItem.image}">
									</div>
									<div class="clearfix"></div>
									<div class="ui-stack ui-stack--wrap">
										<div class="ui-stack-item ui-stack-item--fill">
											<button type="button" class="ui-button btn--link ajOpenDialog" isoman_for_id="image" isoman_val="{$oneItem.image}" isoman_name="image">{$core->get_Lang('Change')}</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<input value="Update" name="submit" type="hidden">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary">
				<div class="ui-page-actions__button-group">
					
				</div>
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<div class="ui-page-actions__button-group">
					<a class="btn btn-default" href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Calcel')}</a>
					{$saveBtn} {$saveList}
				</div>
			</div>
		</div>
	</div>
</form>
<script>
	var training_id = `{$training_id}`;
</script>