{* Một nhóm cấu hình = một thẻ chiếm trọn chiều ngang.
   id chính là slug (#cfg-*) để rail bên trái neo tới, data-* để JS tìm kiếm. *}
<section class="setting-general__group" id="{$group.slug|escape}" data-slug="{$group.slug|escape}" data-label="{$group.label|escape}">

	<div class="setting-general__group-head">

		<span class="setting-general__group-icon">{$core->makeIcon($group.icon)}</span>

		<div class="setting-general__group-heading">

			<h2 class="setting-general__group-title">{$group.label|escape}</h2>

			{if !empty($group.description)}<p class="setting-general__group-desc">{$group.description|escape}</p>{/if}

		</div>

	</div>

	<div class="setting-general__group-body">

		{foreach from=$group.fields item=_oField}

		{assign var="keyword" value=$_oField.keyword}

		<div class="setting-general__field{if !empty($_oField.raw)} setting-general__field--code{/if}" data-type="{$_oField.type|escape}" data-keyword="{$keyword|escape}" data-label="{$_oField.label|escape}">

			{if $_oField.bare}

			{include file="./fields/`$_oField.type`.tpl" keyword=$keyword val=$_oField current=$_oField.current width=$_oField.current_width height=$_oField.current_height}

			{else}

			<label class="setting-general__field-label">{$_oField.label|escape}{if !empty($_oField.required)} <span class="setting-general__field-required">*</span>{/if}{if !empty($_oField.link)} <a href="{$_oField.link|escape}" target="_blank" rel="noopener">{if !empty($_oField.title)}{$_oField.title|escape}{else}{$_oField.link|escape}{/if}</a>{/if}</label>

			{include file="./fields/`$_oField.type`.tpl" keyword=$keyword val=$_oField current=$_oField.current width=$_oField.current_width height=$_oField.current_height}

			{* help_display do ConfigDeclaration escape/lọc sẵn — KHÔNG |escape lại, sẽ hiện ra thẻ. *}
			{if !empty($_oField.help_display)}<span class="setting-general__field-help">{$_oField.help_display}</span>{/if}

			{if !empty($_oField.attention)}<span class="setting-general__field-help setting-general__field-help--warn">{$_oField.attention|escape}</span>{/if}

			{/if}

		</div>

		{/foreach}

	</div>

</section>
