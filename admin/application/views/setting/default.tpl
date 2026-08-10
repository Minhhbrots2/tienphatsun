<header class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Cấu hình</h1>
			</div>
		</div>
	</div><div class="collapsible-header"><div class="collapsible-header__heading"></div></div>
</header>
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<section class="ui-card">
					<nav>
						<h2 class="helper--visually-hidden">Cấu hình</h2>
						<ul class="area-settings-nav">
							{section name=i loop=$list_settings}
							<li class="area-settings-nav__item">
								<a class="area-settings-nav__action" href="{$clsAdminButton->getURL($list_settings[i].adminbutton_id)}" aria-disabled="false">
									<div class="area-settings-nav__media">{$core->makeIcon($list_settings[i].class_iconpage)}</div>
									<div>
										<p class="area-settings-nav__title"> {$core->get_Lang($list_settings[i].title_page)}</p>
										<p class="area-settings-nav__description">{$list_settings[i].desc_page}</p>
									</div>
								</a>
							</li>
							{/section}
						</ul>
					</nav>
				</section>
			</div>
		</div>
	</div>
</div>
<div class="modal" data-tg-refresh="modal" id="modal_container" style="display: none;" aria-hidden="true" aria-labelledby="ModalTitle" tabindex="-1"></div>
<div class="modal-bg" data-tg-refresh="modal" id="modal_backdrop"></div>
