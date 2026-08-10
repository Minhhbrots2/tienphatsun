<header class="ui-title-bar-container ">
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Suggestion</h1>
			</div>
		</div> 
	</div>
</header>
<div class="clearfix"></div>
<form action="" method="post" enctype="multipart/form-data">
	<div class="ui-layout">
		<div class="ui-layout__sections">
			<div class="ui-layout__section">
				<section class="ui-annotated-section-container">
					<div class="ui-annotated-section d-flex flex-column" id="list_suggestion">
						<div class="mb-3 item_suggestion w-100 p-3 border">
							<textarea id="textarea_suggestion_editor_{$now}" class="textarea_intro_editor w-100" name="suggestion" style="width:100%">{$suggestion}</textarea>
						</div>
						
					</div>
				</section>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="ui-page-actions ui-page-actions--has-secondary">
		<div class="ui-page-actions__container">
			<div class="ui-page-actions__actions ui-page-actions__actions--secondary"></div>
			<div class="ui-page-actions__actions ui-page-actions__actions--primary">
				<input value="UpdateSuggestion" name="submit" type="hidden">
				<div class="ui-page-actions__button-group">{$saveBtn}</div>
			</div>
		</div>
	</div>
</form>