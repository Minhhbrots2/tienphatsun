{* Ô nhập một dòng. Nhãn và ghi chú do _config_group.tpl bọc bên ngoài. *}
<input type="text" class="form-control{if !empty($val.required)} required{/if}" name="config[{$keyword|escape}]" value="{$current|escape}"{if !empty($val.required)} required{/if}{if !empty($val.placeholder)} placeholder="{$val.placeholder|escape}"{/if} />
