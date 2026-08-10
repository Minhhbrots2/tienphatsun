<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('faqs')}</span>
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
									<input type="text" class="form-control required" name="iso-title" value="{if $pvalTable gt '0'}{$clsClassTable->getTitle($pvalTable)}{/if}" required maxlength="255" />
								</div>								
								<div class="form-group">
									<label class="form-label mb-1">Từ khóa tìm kiếm:</label>
									<input type="text" id="input-tags" class="input-tags" name="tags" placeholder="Nhập keyword" value="{if $pvalTable gt 0}{$oneItem.tags}{/if}" />
								</div>
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Content')}</label>
									 {$clsForm->showInput('content')}
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
							<h2 class="ui-heading">{$core->get_Lang('Category')}</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Category')}</label>
									<select name="iso-cat_id" class="form-control iso-selectize">
										<option value="">Chọn danh mục</option>
										{$clsProperty->getListOption('_CATEGORYFAQS_MGP',$cat_id)}
									</select>
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
					{if $pvalTable gt '0'}
					<a class="btn btn-warning" data-bind-event-click="deleteModal.show()">{$core->get_Lang('Delete')}</a>
					{/if}
				</div>
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<div class="ui-page-actions__button-group">
					<a class="btn btn-default" href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Calcel')}</a>
					{$saveBtn} {$saveList} {$saveCat}
				</div>
			</div>
		</div>
	</div>
</form>