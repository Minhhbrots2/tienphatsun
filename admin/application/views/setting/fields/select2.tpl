{* Danh sách chọn nâng cao (admin.js gắn chosen vào .iso-select2).
   Ô ẩn phía dưới để khi người dùng bỏ chọn hết, key vẫn có mặt trong POST —
   không có nó thì "xoá hết" bị hiểu nhầm là "thiếu field" và giữ lại giá trị cũ. *}
<select class="form-control iso-select2" name="config[{$keyword|escape}]{if !empty($val.multiple)}[]{/if}"{if !empty($val.multiple)} multiple{/if}{if !empty($val.placeholder)} placeholder="{$val.placeholder|escape}"{/if}>

	{if !empty($val.placeholder) && empty($val.multiple)}<option value="">{$val.placeholder|escape}</option>{/if}

	{foreach from=$val.select key=_optionKey item=_optionLabel}

	<option value="{$_optionKey|escape}"{if isset($val.current_map[$_optionKey])} selected="selected"{/if}>{$_optionLabel|escape}</option>

	{/foreach}

</select>

{if !empty($val.multiple)}<input type="hidden" name="config[{$keyword|escape}][]" value="" />{/if}
