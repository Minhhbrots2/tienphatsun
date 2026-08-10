{* Ô nhập nhiều dòng. Luôn escape khi in ra: giá trị chứa & hoặc </textarea>
   mà không escape sẽ vừa vỡ HTML vừa bị đổi nội dung mỗi lần lưu lại. *}
<textarea class="form-control{if !empty($val.required)} required{/if}" name="config[{$keyword|escape}]" rows="{if !empty($val.rows)}{$val.rows|escape}{else}3{/if}"{if !empty($val.required)} required{/if}{if !empty($val.placeholder)} placeholder="{$val.placeholder|escape}"{/if}>{$current|escape}</textarea>
