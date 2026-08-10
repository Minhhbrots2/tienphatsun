{* Ô chọn giờ (HH:MM) *}
<input type="time" class="form-control{if !empty($val.required)} required{/if}" name="config[{$keyword|escape}]" value="{$current|escape}"{if !empty($val.required)} required{/if} />
