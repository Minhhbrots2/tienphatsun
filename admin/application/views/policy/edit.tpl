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
	<div class="ui-title-bar__main-group">
		<div class="ui-title-bar__heading-group">
			{if $pvalTable gt '0'}
			<h1 class="ui-title-bar__title w-100">{$clsClassTable->getTitle($pvalTable)}</h1>
			{else}
			<h1 class="ui-title-bar__title">{$core->get_Lang('Addnew')}
			{/if}
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
									<label class="col-form-label">{$core->get_Lang('Title')}</label>
									<input type="text" class="form-control required" name="iso-title" value="{if $pvalTable gt '0'}{$clsClassTable->getTitle($pvalTable)}{/if}" placeholder="Tên khóa học" required maxlength="255" />
								</div>
								
								<div class="form-group">
									<fieldset>
										<legend>{$core->get_Lang('Content')}</legend>
										<div class="form-group">
											{assign var = uid value = $clsISO->getUniqid()}
											<div class="radio mb-3">
												<input id="{$uid}"{if $oneItem.is_all_staff eq '1'} checked{/if} name="is_all_staff" checked value="1" type="radio">
												<label for="{$uid}">Tất cả nhân viên</label>
											</div>
											{assign var = uid value = $clsISO->getUniqid()}
											<div class="radio">
												<input id="{$uid}"{if $oneItem.is_all_staff eq '0'} checked{/if} name="is_all_staff" 
												value="0" type="radio">
												<label for="{$uid}">Phòng ban [OR] nhân viên</label>
											</div>
										</div>
										<div class="pl-5">
											<div class="form-group">
												<label class="col-form-label">{$core->get_Lang('Phòng ban')}</label>
												<select{if $oneItem.is_all_staff eq '1'} disabled{/if} name="list_department_id[]" class="form-control iso-select2" multiple="true">{$clsISO->getSelectByPropertyTypeTitle('_DEPARTMENT',$arr_departments_ids,'Phòng ban')}</select>
											</div>
											<div class="form-group">
												<label class="col-form-label">{$core->get_Lang('Nhân viên')}</label>
												<select{if $oneItem.is_all_staff eq '1'} disabled{/if} placeholder="Lựa chọn nhân viên" name="list_profile_id[]" class="form-control iso-select2" multiple="true">
													{foreach name=i from=$list_profiles item=prof}
													<option{if $clsISO->checkItemInArray($prof.profile_id, $arr_profile_ids)} selected{/if} value="{$prof.profile_id}">{$clsProfile->getFullName($prof.profile_id, $prof)}</option>
													{/foreach}
												</select>
											</div>
										</div>
									</fieldset>
									{literal}
									<script type="text/javascript">
										$(function(){
											$_document.on('change', 'input[name=is_all_staff]', function(){
												var _this = $(this);
												if($('input[name=is_all_staff]:checked').val()==1){
													$('.iso-select2').prop('disabled', true).trigger("chosen:updated");
												} else {
													$('.iso-select2').prop('disabled', false).trigger("chosen:updated");
												}
											});
										});
									</script>
									{/literal}
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
											<input {if $oneItem.is_online eq '1' || $pvalTable eq '0'} checked="checked"{/if} name="is_online" value="1" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> {$core->get_Lang('Show')}
									</div>
								</div>
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input{if $oneItem.is_online ne '1' and $pvalTable gt '0'} checked="checked"{/if} name="is_online" value="0" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> {$core->get_Lang('Hide')}
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
								
						<label class="col-form-label">Ngày áp dụng <span class="text-red">*</span></label>
						<input type="text" class="form-control datepicker required" placeholder="dd/mm/yy" name="title" />
					
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
					{if $pvalTable gt '0'}
					<a class="btn btn-warning" data-bind-event-click="deleteModal.show()">{$core->get_Lang('Delete')}</a>
					{/if}
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