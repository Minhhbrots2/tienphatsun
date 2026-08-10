<form id="forums" method="post" action="">
    <div class="row-field">
        <div class="row-heading">Meta Title*:</div>
        <div class="coltrols">
            <input class="text full required" name="config_value_title" value="{$clsMeta->getMetaTitle($meta_id)}" maxlength="255" type="text">
            <div class="clearfix mt5"></div>
            <i>{$core->get_Lang('notetitlemeta')}</i>
        </div>
    </div>
    <div class="row-field">
        <div class="row-heading">Meta Description:</div>
        <div class="coltrols">
            <textarea name="config_value_intro" class="text full required" style="height:60px">{$clsMeta->getMetaDescription($meta_id)}</textarea>
            <div class="clearfix mt5"></div>
            <i>{$core->get_Lang('noteintrometa')}</i>
        </div>
    </div>
    <div class="row-field">
        <div class="row-heading">Meta Keyword:</div>
        <div class="coltrols">
            <textarea name="config_value_keyword" class="text full required" style="height:60px">{$clsMeta->getMetaKeyword($meta_id)}</textarea>
            <div class="clearfix mt5"></div>
           <i>{$core->get_Lang('notekeywordmeta')}</i>
        </div>
    </div>
    <fieldset class="submit-buttons">
        {$saveBtn}
        <input value="UpdateMeta" name="submit" type="hidden">
    </fieldset>
</form>