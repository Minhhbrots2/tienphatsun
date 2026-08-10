<form id="forums" method="post" action="">
    <div id="clienttabs">
        <ul>
            <li class="tabchild"><a href="javascript:void();"><i class="iso-status"></i> Page Info</a></li>
            <li class="tabchild"><a href="javascript:void();"><i class="iso-media"></i> Seo Advanced</a></li>
        </ul>
    </div>
    <div id="tab_content">
        <div class="tabbox" style="display:block">
            <div class="row-field">
                <div class="coltrols">
                    {$clsForm->showInput($intro)}
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="submit-buttons">
                <center>{$saveBtn}</center>
            </div>
        </div>
        <div class="tabbox" style="display:none">
            <div class="row-field">
                <div class="coltrols">
                    <dl>
                        <dt><label>Meta Title</label></dt>
                        <dd><input class="text full required" name="{$config_value_title}" value="{$clsMeta->getMetaTitle($meta_id)}" type="text" /></dd>
                    </dl>
                    <dl>
                        <dt><label>Meta Description</label></dt>
                        <dd><textarea class="full" style="height:40px" name="{$config_value_intro}">{$clsMeta->getMetaDescription($meta_id)}</textarea></dd>
                    </dl>
                    <dl>
                        <dt><label>Meta Keyword</label></dt>
                        <dd><textarea class="full" style="height:40px" name="{$config_value_keyword}">{$clsMeta->getMetaKeyword($meta_id)}</textarea></dd>
                    </dl>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="submit-buttons">
                <center>{$saveBtn}</center>
            </div>
        </div>
    </div>
    <input type="hidden" name="submit" value="Update"> 
 </form>