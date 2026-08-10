{* Danh sách chọn một. Option đang chọn tra bằng map đã dựng ở controller,
   template không tự so sánh giá trị. *}
<select class="form-control{if !empty($val.required)} required{/if}" name="config[{$keyword|escape}]"{if !empty($val.required)} required{/if}>

	{if !empty($val.placeholder)}<option value="">{$val.placeholder|escape}</option>{/if}

	{foreach from=$val.select key=_optionKey item=_optionLabel}

	<option value="{$_optionKey|escape}"{if isset($val.current_map[$_optionKey])} selected="selected"{/if}>{$_optionLabel|escape}</option>

	{/foreach}

</select>
