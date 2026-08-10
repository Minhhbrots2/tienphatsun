<header class="ui-title-bar-container ">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Setting')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar ui-title-bar--separator">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Media</h1>
			</div>
		</div>
	</div><div class="collapsible-header"><div class="collapsible-header__heading"></div></div>
</header>
<div class="clearfix"></div>
<iframe frameborder="0" width="100%" height="600px" src="{$PCMS_URL}/editor/elfinder/elfinder.php.html?v={$smarty.now}"></iframe>