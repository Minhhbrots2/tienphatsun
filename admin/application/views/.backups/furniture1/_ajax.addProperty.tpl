<div class="form-group item_property">
	<div class="row">
		<div class="col-md-6">
			<label>Tên thuộc tính</label>
			<input type="text" class="form-control property_keys" onChange="$Core.furniture.loadTablePrice(this,event)" name="property_keys[]" value="{$val_default}" placeholder="Nhập tên thuộc tính">
		</div>
		{if $number_property eq 0}
		<div class="col-md-6">
			<label>Giá trị</label>
			<input type="text" id="input-tags" class="form-control input-tags property_values" name="property_values[]" placeholder="Gõ ký tự và nhấn enter để thêm thuộc tính" value="" onChange="$Core.furniture.loadTablePrice(this,event)"/>
		</div>
		{else}
		<div class="col-md-6">
			<label>Giá trị</label>
			<div class="d-flex align-items-center">
				<input type="text" id="input-tags" class="form-control input-tags property_values" name="property_values[]" placeholder="Gõ ký tự và nhấn enter để thêm thuộc tính" value="" onChange="$Core.furniture.loadTablePrice(this,event)"/>
				<a class="ml-2" href="javascript:void(0)" onClick="$Core.furniture.removeProperty(this,event)"><i class="fa fa-times" aria-hidden="true"></i></a>
			</div>
		</div>
		{/if}
	</div>
</div>
<script>
$(".input-tags").last().selectize({
	delimiter: ",",
	persist: false,
	preload: true,
	valueField: 'text',
	labelField: 'text',
	searchField: 'text',
	create: function (input) {
		return {
			value: input,
			text: input,
		};
	}
});
</script>