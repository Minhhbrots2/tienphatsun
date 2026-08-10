{* Công tắc bật/tắt. Tự dựng cả khối vì nhãn nằm cạnh ô đánh dấu ('bare' => true).
   Ô ẩn cùng name đứng trước để khi bỏ tick vẫn gửi lên 0 thay vì thiếu hẳn key. *}
<label class="setting-general__check">

	<input type="hidden" name="config[{$keyword|escape}]" value="0" />

	<input type="checkbox" name="config[{$keyword|escape}]" value="1"{if $val.is_checked} checked="checked"{/if} />

	<span class="setting-general__check-text">{$val.label|escape}</span>

</label>

{* help_display do ConfigDeclaration escape/lọc sẵn — KHÔNG |escape lại, sẽ hiện ra thẻ. *}
{if !empty($val.help_display)}<span class="setting-general__field-help">{$val.help_display}</span>{/if}

{if !empty($val.attention)}<span class="setting-general__field-help setting-general__field-help--warn">{$val.attention|escape}</span>{/if}
