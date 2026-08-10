<?php
/* Smarty version 3.1.33, created on 2026-08-08 18:18:52
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/adminbutton/edit.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a77109c5be671_31742014',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ebb5a4bf2a5f8a6c97b6b3a07736ec4212b1743b' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/adminbutton/edit.tpl',
      1 => 1784691579,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a77109c5be671_31742014 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="breadcrumb">
	<strong><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('youarehere');?>
:</strong>
	<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('home');?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('home');?>
</a>
    <a>&raquo;</a>
    <a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('adminbuttons');?>
"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('adminbuttons');?>
</a>
	<!-- Back -->
    <a href="javascript:history.back();" class="back fr"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Back');?>
</a>
</div>
<div class="container-fluid">
    <div class="page-title">
        <h2><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Admin Buttons');?>
</h2>
		<?php $_smarty_tpl->_assignInScope('setting', ((('SiteIntroModule_').($_smarty_tpl->tpl_vars['mod']->value)).('_')).($_smarty_tpl->tpl_vars['_LANG_ID']->value));?>
		<?php if ($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue($_smarty_tpl->tpl_vars['setting']->value) != '') {?>
        <p><?php echo html_entity_decode($_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue($_smarty_tpl->tpl_vars['setting']->value));?>
</p>
		<?php }?>
    </div>
	<div class="infobox">
		<b>Ghi chú</b><br />
		<p>Phân hệ này chỉ ch phép nhà phát triển được phép truy cập</p>
	</div>
    <form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form" style="">
		<table class="form" cellspacing="2" cellpadding="2">
		</table>
    	<fieldset>
            <dl <?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > 0) {?>style="display:none"<?php }?>>
                <dt><label for="_type"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Admin Buttons Type');?>
</label></dt>
                <dd>
                    <select name="_type" class="span20">
                        <option value="_HOME" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['_type'] == '_HOME' || $_smarty_tpl->tpl_vars['_type']->value == '_HOME') {?>selected="selected"<?php }?>>_HOME</option>
                        <option value="_TOP" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['_type'] == '_TOP' || $_smarty_tpl->tpl_vars['_type']->value == '_TOP') {?>selected="selected"<?php }?>>_TOP</option>
                        <option value="_LEFT" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['_type'] == '_LEFT' || $_smarty_tpl->tpl_vars['_type']->value == '_LEFT') {?>selected="selected"<?php }?>>_LEFT</option>
                        <option value="_SETTING" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['_type'] == '_SETTING' || $_smarty_tpl->tpl_vars['_type']->value == '_SETTING') {?>selected="selected"<?php }?>>_SETTING</option>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt><label for="is_group"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Is Group');?>
</label></dt>
                <dd>
                    <select name="is_group" id="is_group" class="span10">
                        <option value="0" <?php if (0 == $_smarty_tpl->tpl_vars['oneItem']->value['is_group']) {?>selected="selected"<?php }?>> NO</option>
                        <option value="1" <?php if (1 == $_smarty_tpl->tpl_vars['oneItem']->value['is_group']) {?>selected="selected"<?php }?>> YES</option>
                    </select>
                    <?php echo '<script'; ?>
 type="text/javascript">
						var $is_group = '<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['is_group'];?>
'; 
						var $pvalTable = '<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
'; 
					<?php echo '</script'; ?>
>
                    
                    <?php echo '<script'; ?>
 type="text/javascript">
						$(document).ready(function(){
							if($pvalTable>0){
								$(".is_group").hide();
								$(".is_group_"+$is_group).show();
							}
							$("#is_group").change(function(){
								$(".is_group").hide();
								$(".is_group_"+$(this).val()).show();
							});
						});
					<?php echo '</script'; ?>
>
                    
                </dd>
            </dl>
            <dl class="is_group is_group_0">
                <dt><label for="parent_id"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Parent Group');?>
</label></dt>
                <dd>
                    <select name="parent_id" id="parent_id" class="span20">
						<option value="0"> -- </option>
                    	<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['listParent']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
                        <option value="<?php echo $_smarty_tpl->tpl_vars['listParent']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['adminbutton_id'];?>
" <?php if ($_smarty_tpl->tpl_vars['listParent']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['adminbutton_id'] == $_smarty_tpl->tpl_vars['oneItem']->value['parent_id']) {?>selected="selected"<?php }?>>
                            <?php echo $_smarty_tpl->tpl_vars['listParent']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title_page'];?>

                        </option>
                        <?php
}
}
?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt><label for="title"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Title');?>
</label></dt>
                <dd>
                    <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['title_page'];?>
" name="title" class="medium text">
                </dd>
            </dl>
            <dl>
                <dt><label for="title"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Title');?>
</label></dt>
                <dd>
                    <textarea name="desc" class="form-control"><?php echo $_smarty_tpl->tpl_vars['oneItem']->value['desc_page'];?>
</textarea>
                </dd>
            </dl>
			<dl>
                <dt><label for="title"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Configuration keyword');?>
</label></dt>
                <dd>
                    <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['CONFIGURATION_KEY'];?>
" name="configuration_key" class="medium text">
                </dd>
            </dl>
            <dl>
                <dt><label for="mod_page"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Mod');?>
</label></dt>
                <dd>
                	<?php $_smarty_tpl->_assignInScope('lstModule', $_smarty_tpl->tpl_vars['core']->value->getListAdminModule());?>
                    <select class="medium" id="mod_page" name="mod_page">
                    	<option value="">-</option>
                        <?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstModule']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = $__section_i_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
                        <option <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['mod_page'] == $_smarty_tpl->tpl_vars['lstModule']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['name']) {?>selected="selected"<?php }?> value="<?php echo $_smarty_tpl->tpl_vars['lstModule']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['name'];?>
">[<?php echo $_smarty_tpl->tpl_vars['lstModule']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['name'];?>
] <?php echo $_smarty_tpl->tpl_vars['lstModule']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['intro'];?>
</option>
                        <?php
}
}
?>
                    </select>
                    <input type="text" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['act_page'];?>
" name="act_page"  class="text" style="width:80px;">
                </dd>
            </dl>
            <dl class="is_group is_group_0">
                <dt><label for="url_page"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('or');?>
 <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('URL');?>
</label></dt>
                <dd>
					<input style="width:40%" type="text" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['url_page'];?>
" name="url_page" class="full text">
                    <span class="notice-short">(<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Leave Blank to get URL auto from Mod');?>
)</span>
                </dd>
            </dl>
			<dl>
                <dt><label for="order_no"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Position');?>
</label></dt>
                <dd>
                    <input type="number" value="<?php if ($_smarty_tpl->tpl_vars['pvalTable']->value > '0') {
echo $_smarty_tpl->tpl_vars['oneItem']->value['order_no'];
} else {
echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getMaxOrderNo();
}?>" name="order_no" class="medium text" style="width:60px;">
                </dd>
            </dl>
			<dl>
                <dt><label for="order_no"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Class');?>
</label></dt>
                <dd>
					<input type="text" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['class_page'];?>
" name="class_page" class="full text" style="width:160px;">
					<span class="notice-short">(<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Leave Blank if not exist');?>
)</span>
				</dd>
            </dl>
			<dl>
                <dt><label for="order_no"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Class Icon');?>
</label></dt>
                <dd>
					<input type="text" value="<?php echo $_smarty_tpl->tpl_vars['oneItem']->value['class_iconpage'];?>
" name="class_iconpage" class="full text" style="width:160px;">
					<span class="notice-short">(<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Leave Blank if not exist');?>
)</span>
				</dd>
            </dl>
        </fieldset>
		<table class="form" width="100%" border="0" cellspacing="2" cellpadding="3"><tbody>
			<tr>
				<td width="20%" class="fieldlabel">Permiss Access</td>
				<td class="fieldarea">
					Choose the admin role groups to permit access to this module:
					<div class="clearfix mt5"></div>
					<?php
$__section_i_2_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['listUserGroup']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_2_total = $__section_i_2_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_2_total !== 0) {
for ($__section_i_2_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_2_iteration <= $__section_i_2_total; $__section_i_2_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
					<label><input <?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkContainer($_smarty_tpl->tpl_vars['permiss_access']->value,$_smarty_tpl->tpl_vars['listUserGroup']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_group_id'])) {?>checked="checked"<?php }?> type="checkbox" name="permiss[]" value="<?php echo $_smarty_tpl->tpl_vars['listUserGroup']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['user_group_id'];?>
"> <?php echo $_smarty_tpl->tpl_vars['listUserGroup']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['name'];?>
</label> 
					<?php
}
}
?>
				</td>
			</tr>
			<tr>
				<td width="20%" class="fieldlabel">DEV Access</td>
				<td class="fieldarea">
					<label><input type="checkbox" name="dev_access" value="1" <?php if ($_smarty_tpl->tpl_vars['oneItem']->value['dev_access'] == '1') {?>checked="checked"<?php }?> />
					<span class="notice-short">(<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Tick here if you want only developer see it');?>
)</span></label>
				</td>
			</tr>
		</table>	
        <br class="clearfix" />
        <fieldset class="submit-buttons">
            <?php echo $_smarty_tpl->tpl_vars['saveBtn']->value;?>

            <input value="Update" name="submit" type="hidden">
        </fieldset>
    </form>
</div>

<style>
.searchmap{ background:#E9EFF3; padding:10px;}
.errorTxt{color:#c00000; display:block;margin:5px 0 0}
</style>
<?php echo '<script'; ?>
 type="text/javascript">
	$().ready(function(){
		$('.check_all').change(function(){
			var _this = $(this);
			if(_this.attr('checked') || _this.attr('checked')=='checked'){
				_this.closest('tr').find('input[type="checkbox"]')
								   .attr('checked',true)
								   .parent('label')
								   .addClass('lblchecked');
				_this.addClass('lblchecked');
			}else{
				_this.closest('tr').find('input[type="checkbox"]')
								   .removeAttr('checked')
								   .parent('label')
								   .removeClass('lblchecked');
				_this.removeClass('lblchecked');
			}	
		});
		$('#search_key').bind('keyup keydown change',function(){
			var _this=$(this);
			var rows = $('#tblHolderPermission tr').size();
			if(rows > 1 && _this.val() != ''){
				s=_this.val();
				$("#tblHolderPermission tr").each(function(){
					$(this).find('td:eq(1)').text().search(new RegExp(s,"i"))<0? $(this).hide():$(this).show();
				});
			}else{
				$('#tblHolderPermission tr').each(function(){
					$(this).show();
				});
			}
		});
		$('input[type="checkbox"]').each(function(){
			var _this = $(this);
			if(_this.attr('checked') || _this.attr('checked')=='checked'){
				_this.parent('label').addClass('lblchecked');			   
			}
		});
	});
<?php echo '</script'; ?>
>
<?php }
}
