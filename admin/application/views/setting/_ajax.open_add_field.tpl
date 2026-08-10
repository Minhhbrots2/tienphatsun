<style>
.btn-property {
    width: 37px;
    height: 37px;
}
.item-field {
    border-bottom: 1px solid #ccc;
    padding-bottom: 1rem;
}
</style>
<div class="modal-dialog modal-standard">
    <div class="modal-content">
        <div class="modal-header"> 
            <a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
            <h3 class="modal-title"><strong>Chi tiết thuộc tính</strong></h3>
        </div>
        <div class="modal-body">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-body js-parent-item js-parent-item-property">
                    {if !empty($configForm)}
                        {foreach from=$configForm item=item key=key name=name}
                            <div class="form-group form-row item-field js-item-field align-items-center">
                                <div class="col-md-12 d-flex justify-content-between align-items-center">
                                    <div><h5><b>Thuộc tính</b></h5></div>
                                    <div class="group-button-property d-flex algin-items-center gap-1">
                                        <button type="button" class="btn btn-property js-btn-property js-btn-plus-property" onClick="$Core.setting.editAddProperty(this, event);" data-type="add">{$core->makeIcon('plus')}</button>
                                        <button type="button" class="btn btn-property js-btn-property js-btn-minus-property {if $configForm|@count <= 1} d-none {/if}" onClick="$Core.setting.editAddProperty(this, event);" data-type="delele">{$core->makeIcon('minus')}</button>
                                    </div>
                                </div>
                                <label class="col-md-2 mt-2 col-form-label text-center">Tên</label>
                                <div class="col-md-10 mt-2">
                                    <input class="form-control required" placeholder="Tên" maxlength="255" name="field[]" value="{$item.field}" />
                                </div>
                                <label class="col-md-2 mt-2 col-form-label text-center">Mã</label>
                                <div class="col-md-10 mt-2">
                                    <input class="form-control js-field-code required" placeholder="Mã" maxlength="255" name="field_code[]" value="{$item.field_code}" />
                                </div>
                                <label class="col-md-2 mt-2 col-form-label text-center">Placeholder</label>
                                <div class="col-md-10 mt-2">
                                    <input class="form-control required" placeholder="Placeholder" maxlength="255" name="field_placeholder[]" value="{$item.field_placeholder}" />
                                </div>
                                <label class="col-md-2 mt-2 col-form-label text-center">Kiểu nhập</label>
                                <div class="col-md-10 mt-2">
                                    <select class="form-control group-option-type" onChange="$Core.setting.addOption(this, event);" name="type[]">
                                    {foreach from=_LIST_TYPE_ARRAY item=itemType key=keyType name=nameType}
                                        <option value="{$keyType}" {if $item.type == $keyType} selected {/if}>{$itemType}</option>
                                    {/foreach}
                                    </select>
                                </div>
                                
                                {if !empty($item.option)}
                                    <div class="col-md-2"></div>
                                    <div class="group-option-item col-md-8 js-parent-item">
                                    <div class=""><h5><b>Các lựa chọn:</b></h5></div>
                                    {foreach from=$item.option item=itemOption key=keyOption}
                                        <div class="option-item d-flex js-item-field mt-2">
                                            <label class="d-flex text-right align-items-center" style="width: 45%"><span>Lựa chọn.</span> <span class="js-order-item"> {$keyOption + 1}</span></label>
                                            <input class="form-control required ml-5 js-field-option" name="option[{$item.field_code}][]" placeholder="tên lựa chọn" maxlength="255" value="{$itemOption}" >
                                            <div class="group-button-property d-flex algin-items-center col-md-1 gap-1">
                                                <button type="button" class="btn btn-property js-btn-property js-btn-plus-property" onClick="$Core.setting.editAddProperty(this, event);" data-type="add">{$core->makeIcon('plus')}</button>
                                                <button type="button" class="btn btn-property js-btn-property js-btn-minus-property {if $item.option|@count <= 1} d-none {/if}" onClick="$Core.setting.editAddProperty(this, event);" data-type="delele">{$core->makeIcon('minus')}</button>
                                            </div>
                                        </div>
                                    {/foreach}
                                    </div>
                                {else}
                                    <div class="col-md-2"></div>
                                    <div class="group-option-item d-none col-md-8 js-parent-item">
                                        <div class=""><h5><b>Các lựa chọn:</b></h5></div>
                                        <div class="option-item d-flex js-item-field mt-2">
                                            <label class="d-flex text-right align-items-center" style="width: 45%"><span>Lựa chọn.</span> <span class="js-order-item"> 1</span></label>
                                            <input class="form-control required ml-5 js-field-option" name="option[]" placeholder="tên lựa chọn" maxlength="255" value="" >
                                            <div class="group-button-property d-flex algin-items-center col-md-1 gap-1">
                                                <button type="button" class="btn btn-property js-btn-property js-btn-plus-property" onClick="$Core.setting.editAddProperty(this, event);" data-type="add">{$core->makeIcon('plus')}</button>
                                                <button type="button" class="btn btn-property js-btn-property js-btn-minus-property d-none" onClick="$Core.setting.editAddProperty(this, event);" data-type="delele">{$core->makeIcon('minus')}</button>
                                            </div>
                                        </div>
                                    </div>
                                {/if}
                            </div>
                        {/foreach}
                    {else}
                        <div class="form-group form-row item-field js-item-field align-items-center">
                            <div class="col-md-12 d-flex justify-content-between align-items-center">
                                <div><h5><b>Thuộc tính</b></h5></div>
                                <div class="group-button-property d-flex algin-items-center gap-1">
                                    <button type="button" class="btn btn-property js-btn-property js-btn-plus-property" onClick="$Core.setting.editAddProperty(this, event);" data-type="add">{$core->makeIcon('plus')}</button>
                                    <button type="button" class="btn btn-property js-btn-property js-btn-minus-property {if $configForm|@count <= 1} d-none {/if}" onClick="$Core.setting.editAddProperty(this, event);" data-type="delele">{$core->makeIcon('minus')}</button>
                                </div>
                            </div>
                            <label class="col-md-2 mt-2 col-form-label text-center">Tên</label>
                            <div class="col-md-10 mt-2">
                                <input class="form-control required" placeholder="Tên" maxlength="255" name="field[]" 
                                    value="" />
                            </div>
                            <label class="col-md-2 mt-2 col-form-label text-center">Mã</label>
                            <div class="col-md-10 mt-2">
                                <input class="form-control js-field-code required" placeholder="Mã" maxlength="255" name="field_code[]" value="" />
                            </div>
                            <label class="col-md-2 mt-2 col-form-label text-center">placeholder</label>
                                <div class="col-md-10 mt-2">
                                    <input class="form-control required" placeholder="Placeholder" maxlength="255" name="field_placeholder[]" value="{$item.field_placeholder}" />
                                </div>
                            <label class="col-md-2 mt-2 col-form-label text-center">Kiểu nhập</label>
                            <div class="col-md-10 mt-2">
                                <select class="form-control group-option-type" onChange="$Core.setting.addOption(this, event);" name="type[]">
                                    {foreach from=_LIST_TYPE_ARRAY item=itemType key=keyType name=nameType}
                                        <option value="{$keyType}">{$itemType}</option>
                                    {/foreach}
                                </select>
                            </div>
                            <div class="col-md-2"></div>
                            <div class="group-option-item d-none col-md-8 js-parent-item">
                                <div class=""><h5><b>Các lựa chọn:</b></h5></div>
                                <div class="option-item d-flex js-item-field mt-2">
                                    <label class="d-flex text-right align-items-center" style="width: 45%"><span>Lựa chọn.</span> <span class="js-order-item"> 1</span></label>
                                    <input class="form-control required ml-5 js-field-option" name="option[]" placeholder="tên lựa chọn" maxlength="255" value="" >
                                    <div class="group-button-property d-flex algin-items-center col-md-1 gap-1">
                                        <button type="button" class="btn btn-property js-btn-property js-btn-plus-property" onClick="$Core.setting.editAddProperty(this, event);" data-type="add">{$core->makeIcon('plus')}</button>
                                        <button type="button" class="btn btn-property js-btn-property js-btn-minus-property d-none" onClick="$Core.setting.editAddProperty(this, event);" data-type="delele">{$core->makeIcon('minus')}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/if}
                </div>
                <input type="hidden" name="setting_id" value="{$setting_id}">

                <div class="modal-footer">
                    <button type="button" onClick="$Core.setting.saveProperty(this, event)" class="btn btn-success">
                        <span>Lưu lại</span>
                    </button>
                    <button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
                        <span>{$core->get_Lang('Close')}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $(".group-option-type").each(function () {
        this.onchange?.call(this, new Event("change"));
    });
})
</script>