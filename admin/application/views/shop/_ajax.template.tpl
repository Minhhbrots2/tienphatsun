{if !empty($configForm)}
    {assign var="dynamic" value=$more_information.dynamic}
    {foreach from=$configForm item=item key=key name=name}
    {assign var="fieldName" value="dynamic_"|cat:$item.field_code}
    <div class="form-group {if $item.type eq 'input_image_mutiple'} config-gallery {/if} form-row">
        <label class="col-md-2 col-form-label text-right">{$item.field}</label>
        <div class="col-md-10">
            {if $item.type == 'input'}
            <input type="text" class="form-control" placeholder="{$item.field_placeholder}" name="{$fieldName}" value="{$dynamic.$fieldName|default:''}">
            {elseif $item.type == 'input_time'}
                <input type="time" name="dynamic_{$item.field_code}" value="{$dynamic.$fieldName|default:''}">
            {elseif $item.type == 'input_password'}
                <input type="password" name="dynamic_{$item.field_code}" value="{$dynamic.$fieldName|default:''}">
            {elseif $item.type == 'input_datetime'}
                <input type="datetime-local" name="dynamic_{$item.field_code}" value="{$dynamic.$fieldName|default:''}">
            {elseif $item.type == 'input_checkbox'}
                {foreach from=$item.option item=itemOption key=keyOption name=nameOption}
                    <div class="d-flex">
                        <input type="checkbox" id="{$itemOption}" name="dynamic_{$item.field_code}[]" placeholder="{$item.field_placeholder}" value="{$itemOption}" {if $clsISO->checkItemInArray($itemOption, $dynamic.$fieldName) } checked {/if}>
                        <label class="col-form-label ml-2" for="{$itemOption}">{$itemOption}</label>
                    </div>
                {/foreach}
            {elseif $item.type == 'input_radio'}
                {foreach from=$item.option item=itemOption key=keyOption name=nameOption}
                    <div class="d-flex">
                        <input type="radio" id="{$itemOption}" name="dynamic_{$item.field_code}" class="form-check-input" value="{$itemOption}" {if $dynamic.$fieldName eq $itemOption} checked {/if}>
                        <label class="col-form-label ml-2" for="{$itemOption}">{$itemOption}</label>
                    </div>
                {/foreach}
            {elseif $item.type == 'input_image'}
                <div class="item-img img-add js-box-select-image box-{$item.field_code} js-box-{$item.field_code}">
                    <img width="100%" height="100%" id="isoman_show_{$item.field_code}" src="{if $dynamic.$fieldName } {$dynamic.$fieldName} {else}{FH_URL}/admin/application/themes/images/no-image.jpg{/if}">
                    <button class="btn btn-exchange ajOpenDialog d-none" isoman_for_id="{$item.field_code}" isoman_val="" isoman_name="image" style="left:unset; width: 96px;">
                        <i class="fa fa-exchange fs-5 text-dark"></i>
                    </button>
                    <button type="button" class="btn btn-del-{$item.field_code} d-none" onclick="$Core.shop.del_photo_menu(this, event)">
                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                    </button>
                    <input type="hidden" id="isoman_url_{$item.field_code}" name="dynamic_{$item.field_code}" value="{if $dynamic.$fieldName } {$dynamic.$fieldName} {/if}">
                </div>
            {elseif $item.type == 'input_image_mutiple'}
                {if !empty($dynamic.$fieldName)}
                    {foreach from=$dynamic.$fieldName item=_item}
                        <div class="item-img" isoman_for_id="{$fieldName}" isoman_val="" isoman_name="image">
                            <img width="100" height="100" id="isoman_show_{$fieldName}" src="{$_item}">
                            <button type="button" class="btn btn-del-image"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
                            <input type="hidden" name="{$fieldName}[]" value="{$_item}">
                        </div>
                    {/foreach}
                {/if}
                <div class="dropzone content-image" style="cursor: pointer;">
                    <div class="item-img img-empty ajOpenDialog" isoman_for_id="{$fieldName}" isoman_multiple="1" isoman_val="" isoman_name="image">
                        <i class="fa fa-plus-circle"></i>
                    </div>
                </div>
            {elseif $item.type == 'select'}
                <select class="form-control group-option-type" name="dynamic_{$item.field_code}">
                    <option value="">-- Chọn ---</option>
                    {foreach from=$item.option item=itemOption key=keyOption name=nameOption}
                        <option value="{$itemOption}" {if $dynamic.$fieldName eq $itemOption} selected {/if}>{$itemOption}</option>
                    {/foreach}
                </select>
            {elseif $item.type == 'select_multiple'}
                <select name="dynamic_{$item.field_code}[]" multiple="multiple" class="form-control iso-select2">
                    {foreach from=$item.option item=itemOption key=keyOption name=nameOption}
                        <option value="{$itemOption}" {if $clsISO->checkItemInArray($itemOption, $dynamic.$fieldName) } selected {/if}>{$itemOption}</option>
                    {/foreach}
                </select>
            {elseif $item.type == 'textarea'}
                <textarea name="dynamic_{$item.field_code}" placeholder="{$item.field_placeholder}"></textarea>
            {/if}
        </div>
    </div>
    {/foreach}
{/if}