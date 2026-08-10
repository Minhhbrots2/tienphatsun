	<style>
		#edititem .content-image{
			width: 100%;
			/* height: 120px; */
			border: 1px solid rgba(195, 207, 216, .3);
			border-radius: 8px;
			display: flex;
			flex-wrap: wrap;
			flex-direction: row;
			align-items: center;
			padding: 10px;
		}
		#edititem .item-choosed{
			height: 120px;
			border-radius: 8px;
			display: flex;
			flex-direction: row;
			align-items: center;
		}
		#edititem .dropzone {
			--bs-dz-icon-bg: #eeedf0;
			position: relative;
			border: 2px dashed #e4e6e8;
			border-radius: .5rem;
			cursor: pointer;
			inline-size: 100%;
		}
		#edititem .dropzone .dz-message {
			font-size: 1.5rem;
			font-weight: 500;
			margin-block: 4rem 3rem;
			margin-inline: 0;
		}
		#edititem .img-empty {
			width: 100px;
			height: 100px;
			border: 2px dashed #e4e6e8;
		}
		#edititem .item-img {
			position: relative;
			display: inline-block;
			align-items: center;
			justify-content: center;
			text-align: center;
			font-size: 30px;
			color: #e4e6e8;
			margin-left: 5px;
			margin-top: 5px;
		}
		#edititem .item-img.img-empty {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			font-size: 30px;
			color: #e4e6e8;
			margin-left: 5px;
			margin-top: 5px;
		}
		#edititem .item-img .btn-del-image {
			position: absolute;
			top: -12px;
			right: -12px;
			background: rgba(255, 255, 255, 0.8);
			border: none;
			cursor: pointer;
			z-index: 10;
			border-radius: 50%;
			width: 25px;
			height: 25px;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		#edititem .item-img .btn-del-image i {
			color: red;
		}
	</style>
<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('NewsPage')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar__main-group">
		<div class="ui-title-bar__heading-group">
			{if $pvalTable gt '0'}
			<h1 class="ui-title-bar__title w-100">{$clsClassTable->getTitle($pvalTable)}</h1>
			<div class="action-bar__item action-bar__item--link-container">
				<div class="action-bar__top-links">
					<a href="{$DOMAIN_NAME}{$clsClassTable->getLink($pvalTable)}" class="ui-button ui-button--transparent action-bar__link"  target="_blank">{$core->makeIcon('eye', $core->get_Lang('ViewOnWeb'))}</a>
				</div>
			</div>
			{else}
			<h1 class="ui-title-bar__title">{$core->get_Lang('Addnew News')}
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
									<label class="col-form-label">{$core->get_Lang('Title')}</label>
									<input type="text" class="form-control required" name="iso-title" value="{if $pvalTable gt '0'}{$clsClassTable->getTitle($pvalTable)}{/if}" required maxlength="255" />
								</div>
								<div class="form-group">
									<label class="col-form-label">Mô tả ngắn</label>
									 <textarea name="iso-intro" id="" cols="30" rows="5" class="form-control w-100">{$oneItem.intro}</textarea>
								</div>
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Content')}</label>
									 {$clsForm->showInput('content')}
								</div>
								{* <div class="form-group">
									<label class="col-form-label">Hiển thị danh sách ảnh</label>
									<div class="clearfix"></div>
									<label class="switch" >
										<input type="checkbox" class="display-image" name="display_gallery" tp="_project" value="1" {if !empty($more_information['config_gallery']['display_gallery'])} checked {/if}>
										<span class="slider round"></span>
									</label>
								</div> *}
								<div class="config-gallery">
									<div class="form-group">
										<label class="col-form-label">Kiểu hiển thị</label>
										<select name="type_display_gallery" class="form-control" data="">
											<option value="slide" {if !empty($more_information['config_gallery']['type_display_gallery']) && $more_information['config_gallery']['type_display_gallery'] == 'slide'} selected {/if}>Slide</option>
											<option value="grid" {if !empty($more_information['config_gallery']['type_display_gallery']) && $more_information['config_gallery']['type_display_gallery'] == 'grid'} selected {/if}>Danh sách ô</option>
										</select>
									</div>
									<label class="col-form-label">Chọn ảnh</label>
									<div class="dropzone content-image " style="cursor: pointer;">
										{assign var = imagesGallery value = $more_information['config_gallery']['images']}
										{if !empty($imagesGallery)}
											{foreach from=$imagesGallery item=_item}
												<div class="item-img" isoman_for_id="image-content" isoman_val="" isoman_name="image">
													<img width="100" height="100" id="isoman_show_image-content" src="{$_item}">
													<button type="button" class="btn btn-del-image"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
													<input type="hidden" name="image_gallery[]" value="{$_item}">
												</div>
											{/foreach}
										{/if}
										<div class="item-img img-empty ajOpenDialog" isoman_for_id="image-content" isoman_multiple="1" isoman_val="" isoman_name="image">
											<i class="fa fa-plus-circle"></i>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-form-label">Tag</label>
									<div class="clearfix"></div>
									<input type="text" id="input-tags" class="input-tags" name="iso-list_tags" placeholder="Nhập keyword" value="{$oneItem.list_tags}" />
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
					<div class="ui-card mt-half">
						<header class="ui-card__header">
							<h2 class="ui-heading">{$core->get_Lang('Category')}</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<label class="col-form-label">{$core->get_Lang('Category')}</label>
									<select name="iso-cat_id" class="form-control required" id="sltCategory">
										<option value="">Chọn</option>
										{$clsProperty->getListOption( "_NEWS_CATEGORY",$oneItem.cat_id, $catParent_id)}
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card mt-half">
						<header class="ui-card__header">
							<h2 class="ui-heading">Dự án</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<select name="project_ids[]" class="form-control iso-select2" multiple data-placeholder="Chọn dự án" >
										{foreach from=$lstProject item=_oItem key=key name=i}
											<option value="{$_oItem.setting_id}" {if $clsISO->checkItemInArray($_oItem.setting_id,$oneItem.listProjectIds) } selected{/if} >{$_oItem.title}</option>
										{/foreach}
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
					<a class="btn btn-warning" onClick="delete_globe(this)" clsTable="News" pval_id="{$pvalTable}" pkey="{$pkeyTable}" return_url="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Delete')}</a>
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
<script type="text/javascript">
	var $type = '_NEWS';
	var $news_id = '{$pvalTable}';
</script>
	<script type="text/javascript">
		var urls = {$imageGallery|@json_encode nofilter};
		urls = urls.length > 0 ? JSON.parse(urls) : [];
		$('.display-image').on('change', function() {
			if ($(this).is(':checked')) {
				$('.config-gallery').show();
			} else {
				$('.config-gallery').hide();
			}
		});
		$('.content-image').on('click', '.btn-del-image', function() {
			let url_image = $(this).parents('.item-img').find('img').attr('src');
			urls = urls.filter(item => item !== url_image);
			$(this).parents('.item-img').remove();
			console.log('urls', urls);
		});
		function isoman_callback(isoman_for_id) {
			$(".isoman-image.isoman-checked").each(function() {
				let isoman_url = $(this).attr("isoman_url");
				if (!urls.includes(isoman_url)) {
					urls.push(isoman_url);
					let html = `<div class="item-img" isoman_for_id="image-content" isoman_val="" isoman_name="image">
									<img width="100" height="100" id="isoman_show_image-content" src="`+isoman_url+`">
									<button type="button" class="btn btn-del-image"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
									<input type="hidden" name="image_gallery[]" value="`+isoman_url+`">
								</div>`;
					$(html).insertBefore('.img-empty')			
				}
			});
		}
		$(document).ready(function() {
			if($(".input-tags").length){
				$(".input-tags").selectize({
					delimiter: ",",
					persist: false,
					preload: true,
					valueField: 'text',
					labelField: 'text',
					searchField: 'text',
					create: function (input) {
						return { value: input, text: input};
					}, load: function(query, callback) {
						var self = $(this);
						$.ajax({
							url:path_ajax_script+'/index.php?mod='+mod+'&act=search_tag',
							type: 'GET',
							dataType:'json',
							cache: true,
							error: function() {
								callback();
							}, success: function(res) {
								callback(res);
							}
						});
					}
				});
			}
		});
	</script>
