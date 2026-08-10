{* Trình soạn thảo WYSIWYG.
   admin.js khởi tạo bằng cách đọc id rồi select lại: $('#'+editorId).isoTextArea()
   → THIẾU id là editor không bao giờ được gắn, textarea hiện ra trơ như ô thường. *}
<textarea class="form-control textarea_intro_editor" id="config-{$keyword|escape}" name="config[{$keyword|escape}]" rows="{if !empty($val.rows)}{$val.rows|escape}{else}8{/if}"{if !empty($val.placeholder)} placeholder="{$val.placeholder|escape}"{/if}>{$current|escape}</textarea>
