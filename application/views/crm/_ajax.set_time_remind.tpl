<form class="p-2">
	<div class="form-group mb-2">
		<label class="form-label mb-1 text-nowrap">Thời gian</label>
		<select onChange="$Core.crm.set_timerange(this, event)" data-type="time_remind" name="time_remind" toId="{$toId}" class="form-control form-select">
			{foreach from=$list_times key = _oK item = _oT}
			<option{if $time_remind eq $_oK} selected{/if} value="{$_oK}">{$_oT}</option>
			{/foreach}
		</select>
	</div>
	<div class="d-flex justify-content-end gap-2">
		<button type="button" onClick="$Core.crm.setTimeRemind(this, event)" action="_SAVE" class="btn btn-block btn-primary">
			<span>Lưu</span>
		</button>
	</div>
</form>