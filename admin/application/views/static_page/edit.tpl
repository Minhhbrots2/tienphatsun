<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">Trang tĩnh</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar__main-group">
		<div class="ui-title-bar__heading-group">
			{if $pvalTable gt '0'}
			<h1 class="ui-title-bar__title w-100">{$pvalTable}#{$oneItem.title|escape:'html'}</h1>
			{else}
			<h1 class="ui-title-bar__title">Thêm trang tĩnh</h1>
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
									<label class="col-form-label">Tiêu đề <span class="text-red">*</span></label>
									<input type="text" class="form-control required" name="title" value="{$oneItem.title|escape:'html'}" required maxlength="255" />
								</div>
								<div class="form-group">
									<label class="col-form-label">Mô tả ngắn / phụ đề</label>
									<textarea class="form-control" name="subtitle" rows="3" placeholder="Mô tả ngắn hiển thị dưới tiêu đề">{$subtitle|escape:'html'}</textarea>
								</div>
								<div class="form-row form-group">
									<div class="col-md-6">
										<label class="col-form-label">Cập nhật lần cuối</label>
										<input type="date" class="form-control" name="last_updated" value="{if $last_updated gt 0}{$last_updated|date_format:'%Y-%m-%d'}{/if}" />
									</div>
									<div class="col-md-6">
										<label class="col-form-label">Icon tiêu đề (hero)</label>
										<input type="text" class="form-control" name="hero_icon" value="{$hero_icon|escape:'html'}" placeholder="vd: fa-shield" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card mt-half">
						<header class="ui-card__header">
							<h2 class="ui-heading">Các mục nội dung</h2>
						</header>
						<div class="ui-card__section">
							<div id="sp_sections" data-mod="{$mod}">{$sections_html}</div>
							<a href="javascript:void(0)" id="sp_add" class="btn btn-default"><i class="fa fa-plus-circle mr-5"></i> Thêm mục</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-xs-12">
				<div class="ui-layout__item">
					<div class="ui-card">
						<header class="ui-card__header">
							<h2 class="ui-heading">Trạng thái</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input {if $oneItem.is_online eq '1' || $pvalTable eq '0'}checked="checked"{/if} name="is_online" value="1" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> Hiện
									</div>
								</div>
								<div class="form-group">
									<div class="custom-radio-wrapper core-radio-custom">
										<label class="">
											<input{if $oneItem.is_online ne '1' and $pvalTable gt '0'} checked="checked"{/if} name="is_online" value="0" type="radio"> <span class="custom-radio custom-icon"></span>
										</label> Ẩn
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ui-card mt-half">
						<header class="ui-card__header">
							<h2 class="ui-heading">Ảnh tiêu đề</h2>
						</header>
						<div class="ui-card__section">
							<div class="ui-type-container">
								<div id="article-image-drop" class="article-image-drop">
									<div class="aspect-ratio aspect-ratio--square aspect-ratio--interactive">
										<input type="hidden" id="isoman_hidden_image" name="isoman_url_image" value="{$oneItem.image|escape:'html'}" />
										<img class="aspect-ratio__content" id="isoman_show_image" src="{$oneItem.image}">
									</div>
									<div class="clearfix"></div>
									<div class="ui-stack ui-stack--wrap">
										<div class="ui-stack-item ui-stack-item--fill">
											<button type="button" class="ui-button btn--link ajOpenDialog" isoman_for_id="image" isoman_val="{$oneItem.image|escape:'html'}" isoman_name="image">Đổi ảnh</button>
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
					<a class="btn btn-warning confirm_delete" href="{$PCMS_URL}/?mod={$mod}&act=trash&{$pkeyTable}={$core->encryptID($pvalTable)}">Xoá vào thùng rác</a>
					{/if}
				</div>
			</div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<div class="ui-page-actions__button-group">
					<a class="btn btn-default" href="{$PCMS_URL}/index.php?mod={$mod}">Huỷ</a>
					{$saveBtn} {$saveList}
				</div>
			</div>
		</div>
	</div>
</form>
{literal}
<style type="text/css">
	.sp-section__head{display:flex;gap:10px;align-items:flex-start;flex-wrap:wrap}
	.sp-col-icon{width:160px}
	.sp-col-title{flex:1 1 240px}
	.sp-col-act{padding-top:26px;white-space:nowrap}
	#sp_sections .sp-section{border:1px solid #e3e3e3}
</style>
{/literal}
