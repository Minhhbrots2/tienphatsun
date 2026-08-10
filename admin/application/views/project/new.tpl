<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('advert')}</a>
    <a>&raquo;</a>
    <a href="{$curl}" title="{$act}">{$core->get_Lang('Add')}</a>
    <!-- // -->
    <a href="javascript:window.history.back();" class="back fr">{$core->get_Lang('back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">
        <h2>{$core->get_Lang('Ads Management')} &raquo; <font class="color_r">{$core->get_Lang('add')}</font></h2>
        <p>{$core->get_Lang('Please enter all required fields')}</p>
    </div>
	<div class="clearfix"><br /></div>
    <form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
    	<fieldset>
            <legend>{$core->get_Lang('Setting advert')}</legend>
            <div class="wrap">
                 <div class="photobox fl mr20 image">
                 	{if $_isoman_use eq '1'}
                        <img src="" alt="{$core->get_Lang('images')}"  id="isoman_show_image" class="span100" height="156px"  />
                        <a href="javascript:void()" class="photobox_edit ajOpenDialog" isoman_for_id="image" isoman_val="" isoman_name="image" title="{$core->get_Lang('edit')}">
                            <i class="iso-edit"></i>
                        </a>
                        <input type="hidden" id="isoman_hidden_image" name="isoman_url_image" value="">
                    {else}
                        <img src="{$oneItem.image}" alt="{$core->get_Lang('images')}" id="imgTestimonial_image" />
                        <input type="hidden" name="image_src" value="{$oneItem.image}" class="hidden_src" id="imgTestimonial_hidden" />
                        <a href="javascript:void()" title="{$core->get_Lang('change')}" class="photobox_edit editInlineImage" g="imgTestimonial">
                            <i class="iso-edit"></i>
                        </a> 
                        <input type="file" style="display:none" id="imgTestimonial_file" g="imgTestimonial" class="editInlineImageFile" name="image" />
                	{/if}
                </div>
                <div class="fl span75">
                    <div class="row-span">
                        <div class="fieldlabel">{$core->get_Lang('nameofads')}<font color="red">*</font></div>
                        <div class="fieldarea">
                            <input class="text full fontLarge required" autocomplete="off" name="iso-title" maxlength="255" type="text" />
                        </div>
                    </div>
                    <div class="row-span">
                        <div class="fieldlabel">{$core->get_Lang('status')}</div>
                        <div class="fieldarea">
                        <div class="vietiso_status_button"></div>
                        <script type="text/javascript">
                            var is_online = '{$clsClassTable->getOneField("is_online",$pvalTable)}';
                        </script>
                        {literal}
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $('.vietiso_status_button').isoswitchvalue({
                                    _value:is_online,
                                    _selector:'iso-is_online'
                                });
                            });
                        </script>
                        {/literal}
                        <span class="notice" id="prv_status" {if $clsClassTable->getOneField("is_online",$pvalTable) eq 1}style="display:none;"{/if}>PRIVATE: {$core->get_Lang('This article can only be seen via the link in the admin page')}.</span>
                        <span class="notice" id="pub_status" {if $clsClassTable->getOneField("is_online",$pvalTable) eq 0}style="display:none;"{/if}>PUBLIC: {$core->get_Lang('This article is available online show normal status')}.</span>
                        </div>
                    </div>
                    <div class="row-span">
                        <div class="fieldlabel">{$core->get_Lang('type')} <font color="red">*</font></div>
                        <div class="fieldarea">
                            <select class="slb required" name="iso-type" style="width:122px">
                                <option value="IMAGE">{$core->get_Lang('Images')}</option>
                                <option value="FLASH">{$core->get_Lang('Flash')}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row-span">
                        <div class="fieldlabel">{$core->get_Lang('link')}</div>
                        <div class="fieldarea">
                            <input class="text full url" name="iso-url" value="http://" maxlength="255" type="text" />
                        </div>
                    </div>
                    {if $clsConfiguration->getValue('SiteHasGroup_Ads') eq 1}
                    <br class="clearfix" />
                    <fieldset style="background:#f9f9f9;display:block;clear:both;margin-top:20px;">
                        <legend>{$core->get_Lang('Display ads on the page')}</legend>
                        <p class="font12px">{$core->get_Lang('Tick selected in the page types like to display ads')}.</p>
                        <ul style="list-style:none;">
                            {section name=i loop=$lstAdsGroup}
                            <li style="padding:3px 0px;"><label><input disabled="disabled" name="lstGroup[]" type="checkbox" value="{$lstAdsGroup[k].ads_group_id}" /> {$clsAdsGroup->getTitle($lstAdsGroup[i].ads_group_id)}</label></li>
                            <!-- Start child -->
                            {assign var = lstAdsGroup2 value = $clsAdsGroup->getChild($lstAdsGroup[i].ads_group_id)}
                            {if $lstAdsGroup2[0].ads_group_id ne ''}
                            {section name=k loop=$lstAdsGroup2}
                            <li style="padding:3px 0px;">----- <label><input {if $clsAdsGroup->checkAds($lstAdsGroup2[k].ads_group_id,$pvalTable)}checked="checked"{/if} name="lstGroup[]" type="checkbox" value="{$lstAdsGroup2[k].ads_group_id}" /> {$clsAdsGroup->getTitle($lstAdsGroup2[k].ads_group_id)}</label></li>
                            {/section}
                            {/if}
                            {/section}
                        </ul>
                    </fieldset>
                    {/if}
                </div>
            </div>
        </fieldset>
        <div class="clearfix"><br /></div>
        <fieldset class="submit-buttons">
            {$saveBtn}{$saveList}
            <input value="Insert" name="submit" type="hidden" />
        </fieldset>
    </form>
</div>