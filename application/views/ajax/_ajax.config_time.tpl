<div class="modal-dialog modal-sm">
	<form method="POST" action="#" class="modal-content" onsubmit="return false;">
		<div class="modal-header">
			<h5 class="modal-title">Cài đặt thời gian hiển thị</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{assign var = gId value = $clsISO->getUniqid()}			
			<div class="form-group  mb-2" role="group" aria-label="Sắp xếp">	
				<select class="form-control form-select" name="year" gId="{$gId}" onChange="$Core.helper.load_time(this,event)">
					{foreach from=$list_years item = _year}
					<option{if $_year eq $year} selected{/if} value="{$_year}">{$_year}</option>
					{/foreach}
				</select>
			</div>
			<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
				<select class="form-control form-select" name="quarter" gId="{$gId}" onChange="$Core.helper.load_time(this,event)"> 
					<option value="">Chọn quý</option>			
					{foreach from=$list_quarter item = _quarter}
					<option value="{$_quarter}"{if $quarter eq $_quarter} selected{/if} >Quý {$_quarter}</option>
					{/foreach}
				</select>
			</div>
			<div class="form-group mb-2" role="group" aria-label="Sắp xếp">
				<select class="form-control form-select" name="month" gId="{$gId}"> 
					<option value="">Chọn tháng</option>			
					{foreach from=$list_months item = _month}
					<option value="{$_month}"{if $month eq $_month} selected{/if} >Tháng {$_month}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="modal-footer justify-content-between">
			<input type="hidden" name="submit" value="Update" />
			<div class="d-flex align-items-center">
				<label class="switch mr-2">
					<input type="checkbox" name="is_time_now" value="1"{if !empty($time_config.is_time_now)} checked{/if}>
					<span class="slider round"></span>
				</label>
				<span>Thời gian hiện tại</span>
			</div>
			<button type="button" data-toggle="ripple" class="btn btn-primary" 
				onClick="$Core.helper.save_config_time(this,event)">Lưu lại</button>
		</div>
	</form>
</div>
