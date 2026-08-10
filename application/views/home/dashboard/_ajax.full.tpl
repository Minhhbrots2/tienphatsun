<div class="modal-dialog modal-fullscreen">
	<form class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">{$titlePage}</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			{assign var = gId value = $clsISO->getUniqid()}
			<div class="p-3 rounded-2 mb-3 d-flex align-items-center bg-lighter gap-2">
				<label class="col-form-label">Xem theo:</label>
				<input type="hidden" gId="{$gId}" name="is_bigger" value="1" />
				<div class="input-group w-px-300 d-flex" role="group">
					<select class="form-control form-select" name="date_type" gId="{$gId}" 
						onChange="$Core.dashboard.reload(this,event)">
						<option value="_month">Tháng</option>
						<option value="_quater">Quý</option>
						<option value="_half">1/2 năm</option>
					</select>
					<select class="form-control form-select" name="month" gId="{$gId}" 
						onChange="$Core.dashboard.reload(this,event)"> 
						<option value="">Tháng</option>			
						{foreach from=$list_months item = _month}
						<option value="{$_month}">Tháng {$_month}</option>
						{/foreach}
					</select>
					<select class="form-control form-select" name="year" gId="{$gId}" 
						onChange="$Core.dashboard.reload(this,event)">
						{foreach from=$list_years item = _year}
						<option{if $_year eq $smarty.now|date_format:"%Y"} selected{/if} value="{$_year}">{$_year}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
			{if $tp eq 'load_billing_chart'}
			<div class="ajax" gId="{$gId}" data-options='{ldelim}"is_bigger":"1"{rdelim}' 
			data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_billing_chart">
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
			</div>
			{elseif $tp eq 'chart_billing_type'}
			<div class="ajax" gId="{$gId}" data-options='{ldelim}"is_bigger":"1"{rdelim}' 
			data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=chart_billing_type">
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
				<div class="animate-bg rounded-2 mb-2 h-px-20 w-100"></div>
			</div>	
			{/if}
			<div class="row mt-3">
				<div class="col-12 col-lg-6 offset-lg-3">
					<div class="ajax border p-3 rounded-2" gId="{$gId}" data-options='{ldelim}"is_bigger":"1"{rdelim}' 
					data-url="{$PCMS_URL}/index.php?mod={$mod}&sub=dashboard&act=load_sales_overview">
						<div class="p-4 text-center">
							<div class="py-1">Loading...</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>