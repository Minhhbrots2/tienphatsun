{* Tài khoản hỗ trợ theo từng loại bảng hàng: mỗi loại một tài khoản chính và
   một tài khoản phụ. Danh sách dòng và tên thành viên đã dựng sẵn ở controller
   ('bare' => true nên khối này tự dựng nhãn). *}
<label class="setting-general__field-label">{$val.label|escape}</label>

<div class="setting-general__support">

	<div class="setting-general__support-head">

		<span>Loại bảng hàng</span>

		<span>Tài khoản chính</span>

		<span>Tài khoản phụ</span>

	</div>

	{foreach from=$val.support_rows item=_oRow}

	<div class="setting-general__support-row">

		<span class="setting-general__support-name">{$_oRow.title|escape}</span>

		<select placeholder="{$val.placeholder|escape}" name="config[{$val.keyword|escape}][{$_oRow.property_id|escape}]" class="form-control iso-selectizeLiveSearch" data-url="{$PCMS_URL}/index.php?mod=member&act=get_member_search">

			{if $_oRow.main_id}<option value="{$_oRow.main_id|escape}" selected="selected">{$_oRow.main_name|escape}</option>{/if}

		</select>

		<select placeholder="{$val.placeholder|escape}" name="config[{$val.pair_keyword|escape}][{$_oRow.property_id|escape}]" class="form-control iso-selectizeLiveSearch" data-url="{$PCMS_URL}/index.php?mod=member&act=get_member_search">

			{if $_oRow.extra_id}<option value="{$_oRow.extra_id|escape}" selected="selected">{$_oRow.extra_name|escape}</option>{/if}

		</select>

	</div>

	{/foreach}

</div>
