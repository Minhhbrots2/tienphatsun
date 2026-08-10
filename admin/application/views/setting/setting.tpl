<div class="ui-title-bar-container">
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
			<div class="ui-title-bar__heading-group justify-content-between">
				<h1 class="ui-title-bar__title">Cài đặt thuộc tính 
					{if $group eq 'crm'}
						khách hàng
					{elseif $group eq 'billing'}
						giao dịch						
					{elseif $group eq 'profile'}
						người dùng
					{elseif $group eq 'issue'}
						công việc
					{elseif $group eq 'okrs'}
						okrs
					{elseif $group eq 'fund'}
						thu/chi
					{elseif $group eq 'stock'}
						bảng hàng
					{else}
						chung
					{/if}
				</h1>
				<button type="button" onClick="$Core.setting.storage_cache_all(this, event)" class="btn btn-default ml2" setting_id="0">{$core->makeIcon('check', $core->get_Lang('Update Cache All'))}</button> 
			</div>
		</div>
	</div>
</div>
<div class="ui-layout">
	<div class="ui-layout__sections">
		{foreach from=$lstSetting_Type key = setting_type item = setting}
		<section name="{$setting_type}" id="{$setting_type}" class="ui-annotated-section-container">
			<div class="ui-annotated-section">
				<div class="row">
					<div class="col-md-12">
						<div class="ui-annotated-section__title">
							<h2 class="ui-heading">{$setting.name}</h2>
						</div>
						<div class="ui-annotated-section__description">
							{$setting.description}
						</div>
						<button type="button" onClick="open_setting(this)" class="btn btn-default" setting_id="0" setting_type="{$setting_type}">{$core->makeIcon('plus-circle', $core->get_Lang('Addnew'))}</button>
						<button type="button" onClick="$Core.setting.storage_cache(this, event)" class="btn btn-icon btn-default ml2" setting_id="0" setting_type="{$setting_type}" title="Cache">{$core->makeIcon('cloud')}</button> 
					</div>
					<div class="col-md-12">
						<div class="ui-annotated-section__content">
							<div class="next-card">
								<div class="next-card__section">
									<div class="holderSettingType_{$setting_type}">
										Loading...
									</div>
									{literal}<script type="text/javascript">
										load_list_setting({/literal}'{$setting_type}'{literal});
									</script>{/literal}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		{/foreach}
	</div>
</div>
<script>
	var agency_id = `{$agency_id}`;
	var typeInputDynamic = '{$smarty.const._LIST_TYPE_ARRAY_HAS_OPTION|@json_encode}';
</script>
{literal}
<script type="text/javascript">
	setTimeout(() => {
		if (window.location.hash) {
			var hash = window.location.hash;
			if ($(hash).length) {
				$('html, body').animate({
					scrollTop: $(hash).offset().top
				}, 900, 'swing');
			}
		}
		if(agency_id > 0){
			$("button[setting_type='_AGENCY'][setting_id='"+agency_id+"'][onclick='open_setting(this)']").trigger("click");
		}
	}, 2000);
</script>
{/literal}