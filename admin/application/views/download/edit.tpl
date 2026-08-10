<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Documents')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar__main-group">
		<div class="ui-title-bar__heading-group">
			{if $pvalTable gt '0'}
			<h1 class="ui-title-bar__title w-100">{$clsClassTable->getTitle($pvalTable)}</h1>
			{else}
			<h1 class="ui-title-bar__title">{$core->get_Lang('add')} {$core->get_Lang('download')}</h1>
			{/if}
		</div>
	</div>
</div>
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
									<input type="text" class="form-control required" name="iso-{$title}" value="{if $pvalTable gt '0'}{$oneItem.$title}{/if}" required maxlength="255" />
								</div>
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Category')}*</label>
									<select name="cat_id" class="form-control iso-selectize required">
										{$clsCategory->makeSelectboxOption(0,'_DOWNLOAD',$cat_id)}
									</select>
								</div>
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Content')}</label>
									 {$clsForm->showInput($content)}
								</div>
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Attachment File')}</label>
									<div class="clearfix"></div>
									{if $oneItem.attachment_file ne ''}
									<a class="download" href="{$oneItem.attachment_file}"><i class="fa fa-download"></i> <span>{$oneItem.attachment_file}</span></a>{/if}
									<input type="file" class="filestyle" name="attachment_file" maxlength="255" />
									<label class="col-form-label">{$core->get_Lang('OR')}</label>
									<input type="text" class="form-control" name="iso-attachment_url" value="{if $pvalTable gt '0'}{$oneItem.attachment_url}{/if}" placeholder="Đường dẫn URL" maxlength="255" />
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
										<div class="ui-stack-item{if $pvalTable gt '0' && $oneItem.image}{else} hidden{/if}">
											<button type="button" pvalTable="{$pvalTable}" clsTable="News" g="imgItem" class="ui-button btn--link deleteItemImage">Xóa</button>
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
					{if $pvalTable gt '0'}
					<a class="btn btn-warning" onClick="delete_globe(this)" clsTable="Service" pval_id="{$pvalTable}" pkey="{$pkeyTable}" return_url="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Delete')}</a>
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