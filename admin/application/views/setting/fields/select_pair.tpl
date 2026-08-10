{* Hai ô đi cặp, lưu chung 1 key JSON (vd: confirm_billing = {name, staff_id}).
   Ô phụ chỉ có nghĩa với đúng một lựa chọn của ô chính nên ẩn/hiện theo nó —
   trạng thái đầu do controller tính, JS trong general.tpl lo lúc người dùng đổi. *}
<div class="setting-general__pair">

	<select class="form-control setting-general__pair-main" name="config[{$keyword|escape}][{$val.sub_keyword|escape}]" data-pair-show="{$val.pair.show_when|escape}">

		{if !empty($val.placeholder)}<option value="">{$val.placeholder|escape}</option>{/if}

		{foreach from=$val.select key=_optionKey item=_optionLabel}

		<option value="{$_optionKey|escape}"{if isset($val.current_map[$_optionKey])} selected="selected"{/if}>{$_optionLabel|escape}</option>

		{/foreach}

	</select>

	<div class="setting-general__pair-extra{if $val.pair.is_open} is-open{/if}">

		{* Danh sách nhân sự dài nên dùng chosen (.iso-select2) cho gõ tìm được;
		   chosen init với width 100% nên nằm trong khối đang ẩn vẫn ra đúng bề ngang. *}
		<select class="form-control iso-select2" name="config[{$keyword|escape}][{$val.pair.keyword|escape}]">

			{if !empty($val.pair.placeholder)}<option value="">{$val.pair.placeholder|escape}</option>{/if}

			{foreach from=$val.pair.select key=_optionKey item=_optionLabel}

			<option value="{$_optionKey|escape}"{if isset($val.pair.current_map[$_optionKey])} selected="selected"{/if}>{$_optionLabel|escape}</option>

			{/foreach}

		</select>

	</div>

</div>
