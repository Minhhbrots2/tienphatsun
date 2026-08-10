<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">Lộ trình học</span>
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
									<input type="text" class="form-control required" name="iso-title" value="{$oneItem.title}" placeholder="Tên lộ trình" required maxlength="255" />
								</div>
								<div class="form-group">
									<label class="col-form-label">Mô tả / Mục tiêu</label>
									<textarea class="form-control" name="iso-description" rows="4" placeholder="Mô tả ngắn về lộ trình, mục tiêu đạt được...">{$oneItem.description}</textarea>
								</div>
								<div class="form-group">
									<label class="col-form-label">Cấp độ</label>
									<select class="form-control custom-select" name="level">
										<option value="">-- Chọn cấp độ --</option>
										<option value="basic"{if $oneItem.level eq 'basic'} selected{/if}>Cơ bản</option>
										<option value="intermediate"{if $oneItem.level eq 'intermediate'} selected{/if}>Trung cấp</option>
										<option value="advanced"{if $oneItem.level eq 'advanced'} selected{/if}>Nâng cao</option>
									</select>
								</div>
								<div class="form-group">
									<fieldset>
										<legend>Khoá học trong lộ trình</legend>
										<div class="box-title mb-2">
											<div class="input-group" style="position:relative;">
												<input type="text" id="course_search" class="form-control" placeholder="Gõ tên khoá học để tìm và thêm..." autocomplete="off"
													onkeyup="$Core.learning_path.searchCourse(this,event)" onkeypress="return event.keyCode!=13" />
												<div id="course_search_results" class="list-group" style="display:none; position:absolute; top:100%; left:0; right:0; z-index:1050; max-height:320px; overflow:auto; box-shadow:0 4px 12px rgba(0,0,0,.15); border-radius:4px;"></div>
											</div>
											<small class="text-muted">Học viên học tự do — bật <b>Bắt buộc</b> cho các khoá cần hoàn thành để tính đậu lộ trình.</small>
										</div>
										<div class="box-body">
											<table class="table table-hover table-vertical table-striped TableListCourse">
												<thead><tr>
													<th class="align-center bg-lighter" style="width:50px"></th>
													<th class="align-center bg-lighter" style="width:50px">STT</th>
													<th class="align-left bg-lighter">Tên khoá học</th>
													<th class="align-center bg-lighter" style="width:120px">Bắt buộc</th>
													<th class="align-center bg-lighter" style="width:50px"></th>
												</tr></thead>
												<tbody class="list_course" id="list_course">
													<tr><td class="text-center text-muted" colspan="5">Đang tải...</td></tr>
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
						<header class="ui-card__header"><h2 class="ui-heading">{$core->get_Lang('Status')}</h2></header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label><input {if $oneItem.is_online eq '1'}checked="checked"{/if} name="is_online" value="1" type="radio"> <span class="custom-radio custom-icon"></span></label> {$core->get_Lang('Show')}
									</div>
								</div>
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label><input{if $oneItem.is_online ne '1'} checked="checked"{/if} name="is_online" value="0" type="radio"> <span class="custom-radio custom-icon"></span></label> {$core->get_Lang('Hide')}
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card mt-half">
						<header class="ui-card__header"><h2 class="ui-heading">{$core->get_Lang('Image')}</h2></header>
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
				<div class="ui-page-actions__button-group"></div>
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
	var path_id = `{$path_id}`;
</script>
