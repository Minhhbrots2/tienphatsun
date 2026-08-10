{* Chọn màu. Ô text giữ name và là giá trị thật được lưu; ô color chỉ là bảng
   màu đồng bộ hai chiều, để giá trị không hợp lệ (rỗng, biến CSS) không bị
   trình duyệt tự nắn về #000000 rồi ghi đè mất dữ liệu. *}
<div class="input-group">

	<input type="text" class="form-control config-color__value" name="config[{$keyword|escape}]" value="{$current|escape}"{if !empty($val.placeholder)} placeholder="{$val.placeholder|escape}"{else} placeholder="#000000"{/if} />

	<div class="input-group-btn">

		<input type="color" class="form-control config-color__picker" value="{if !empty($current)}{$current|escape}{else}#000000{/if}" aria-label="{$val.label|escape}" />

	</div>

</div>
