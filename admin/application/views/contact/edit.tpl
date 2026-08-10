<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('contactdepartment')}</a>
    <a>&raquo;</a>
	<a href="{$curl}" title="{$act}">{if $pvalTable}{$core->get_Lang('edit')} #{$pvalTable}{else}{$core->get_Lang('add')}{/if}</a>
    <!-- Back -->
    <a href="javascript:window.history.back();" class="back fr">{$core->get_Lang('back')}</a>
</div>
<div class="container-fluid">
    <div class="page-title">	
        <h2>{if $pvalTable}Bộ phận liên hệ &raquo; {$clsClassTable->getTitle($pvalTable)}{else}Thêm mới bộ phận liên hệ{/if}</h2>
		<p>Nhập đầy đủ các trường yêu cầu</p>
    </div>
	<div class="clearfix"><br /></div>
    <form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
    	<div class="wrap">
        	<div class="photobox fl">
                {if $_isoman_use eq '1'}
                <img src="{$oneItem.image}" alt="Hình ảnh" id="isoman_show_image" />
                <input type="hidden" id="isoman_hidden_image" name="isoman_url_image" value="{$oneItem.image}" />
                <a href="javascript:void()" title="Thay đổi" class="photobox_edit ajOpenDialog" isoman_for_id="image" isoman_val="{$oneItem.image}" isoman_name="image"><i class="iso-edit"></i></a>
                    {if $oneItem.image}
                    <a pvalTable="{$pvalTable}" clsTable="News" href="javascript:void()" title="Xóa" class="photobox_edit deleteItemImage" g="imgItem">X</a>
                    {/if}
                {else}
                <img src="{$oneItem.image}" alt="Chưa có hình ảnh" id="imgTestimonial_image" />
                <input type="hidden" name="image_src" value="" class="hidden_src" id="imgTestimonial_hidden" />
                <a href="javascript:void()" title="{$_lang->get_Lang('Change')}" class="photobox_edit editInlineImage" g="imgTestimonial"><i class="iso-edit"></i></a> 
                <input type="file" style="display:none" id="imgTestimonial_file" g="imgTestimonial" class="editInlineImageFile" name="image" />
                {/if}
            </div>
            <div class="body-form">
            	<div class="row-span">
                	<div class="fieldlabel"><b class="color_r">* {$core->get_Lang('department')}</b></div>
                    <div class="fieldarea">
                    	<input type="text" class="text full fontLarge" name="iso-title" value="{$clsClassTable->getOneField('title',$pvalTable)}" />
                    </div>
                </div>
                <div class="row-span">
                    <div class="fieldlabel"><b class="color_r">* {$core->get_Lang('status')}</b></div>
                    <div class="fieldarea">
                        <div class="vietiso_status_button"></div>
                        <script type="text/javascript">
                        var is_online = '{$clsClassTable->getOneField("is_online",$pvalTable)}';
                        var pvalTable = '{$pvalTable}';
                        </script>
                        {literal}
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('.vietiso_status_button').isoswitchvalue({
                                        _value: is_online,
                                        _selector: 'iso-is_online'
                                    });
                                });
                            </script>
                        {/literal}
                        <span class="notice" id="prv_status" {if $clsClassTable->getOneField("is_online",$pvalTable) eq 1}style="display:none;"{/if}>PRIVATE: {$core->get_Lang('This article can only be seen via the link in the admin page')}.</span>
                        <span class="notice" id="pub_status" {if $clsClassTable->getOneField("is_online",$pvalTable) eq 0}style="display:none;"{/if}>PUBLIC: {$core->get_Lang('This article is available online show normal status')}.</span>
                    </div>
                </div>
                <div class="row-span">
                	<div class="fieldlabel"><b class="color_r">* {$core->get_Lang('fullname')}</b></div>
                    <div class="fieldarea">
                    	<input type="text" class="text full required" style="width:90%" name="iso-fullname" value="{$clsClassTable->getOneField('fullname',$pvalTable)}" />
                    </div>
                </div>
                <div class="row-span">
                	<div class="fieldlabel">{$core->get_Lang('phone')} </div>
                    <div class="fieldarea">
                    	<input type="text" class="text full" style="width:90%" name="iso-phone" value="{$clsClassTable->getOneField('phone',$pvalTable)}" />
                    </div>
                </div>
                <div class="row-span">
                	<div class="fieldlabel"><b class="color_r">* {$core->get_Lang('email')}</b></div>
                    <div class="fieldarea">
                    	<input type="text" class="text full required email" name="iso-email" value="{$clsClassTable->getOneField('email',$pvalTable)}" style="width:90%" />
                    </div>
                </div>
                <div class="row-span">
                	<div class="fieldlabel">{$core->get_Lang('Skype')} </div>
                    <div class="fieldarea">
                    	<input type="text" class="text full" style="width:90%" name="iso-skype" value="{$clsClassTable->getOneField('skype',$pvalTable)}" />
                    </div>
                </div>
                <div class="row-span">
                	<div class="fieldlabel">
                    	{$core->get_Lang('color')}
                    </div>
                    <div class="fieldarea">
                    	<input type="text" class="fl mr5 text full isocolorpicker" name="iso-color" value="{$clsClassTable->getOneField('color',$pvalTable)}" style="width:10%; background:{$clsClassTable->getOneField('color',$pvalTable)}" />
                        <img src="{$URL_IMAGES}/picker.gif" />
                    </div>
                </div>
                <div class="row-span">
                	<div class="fieldlabel">
                    	{$core->get_Lang('content')}
                    </div>
                    <div class="fieldarea">
                    	{$clsForm->showInput('intro')}
                        <div class="clearfix"></div>
                        <br class="clearfix" />
                        <fieldset class="submit-buttons">
                            {$saveBtn}{$saveList}
                            <input value="Update" name="submit" type="hidden">
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="{$URL_JS}/colorpicker/js/colorpicker.js?v={$upd_version}"></script>
<script type="text/javascript" src="{$URL_JS}/colorpicker/js/eye.js"></script>
<script type="text/javascript" src="{$URL_JS}/colorpicker/js/utils.js"></script>
<link rel="stylesheet" type="text/css" href="{$URL_JS}/colorpicker/css/colorpicker.css">
{literal}
	
	<script type="text/javascript">
		$(function(){
			$('.isocolorpicker').ColorPicker({
				onSubmit: function(hsb, hex, rgb, el) {
					$(el).val(hex);
					$(el).ColorPickerHide();
				},
				onBeforeShow: function () {
					$(this).ColorPickerSetColor(this.value);
				},onChange: function (hsb, hex, rgb) {
					$('.isocolorpicker').css('backgroundColor', '#' + hex);
					$('.isocolorpicker').val('#'+hex);
				}
			});
		});
    </script>
{/literal}