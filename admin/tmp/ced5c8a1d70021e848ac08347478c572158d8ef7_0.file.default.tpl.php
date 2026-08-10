<?php
/* Smarty version 3.1.33, created on 2026-08-07 19:39:56
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/adminbutton/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75d21c4a1604_23470415',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ced5c8a1d70021e848ac08347478c572158d8ef7' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/adminbutton/default.tpl',
      1 => 1784691579,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75d21c4a1604_23470415 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/function.cycle.php','function'=>'smarty_function_cycle',),));
?>
<div class="breadcrumb">
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
    <p><b><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("options");?>
:</b> <a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit<?php if ($_smarty_tpl->tpl_vars['_type']->value != '') {?>&_type=<?php echo $_smarty_tpl->tpl_vars['_type']->value;
}?>"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang("Add New");?>
</a></p>
    <p>
    	<strong><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Filter By Admin Buttons Type');?>
</strong>: 
        <a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&_type=_HOME" style="<?php if ($_smarty_tpl->tpl_vars['_type']->value == '_HOME') {?>font-weight:bold;<?php }?>">_HOME</a> | 
        <a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&_type=_TOP" style="<?php if ($_smarty_tpl->tpl_vars['_type']->value == '_TOP') {?>font-weight:bold;<?php }?>">_TOP</a> | 
        <a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&_type=_LEFT" style="<?php if ($_smarty_tpl->tpl_vars['_type']->value == '_LEFT') {?>font-weight:bold;<?php }?>">_LEFT</a>| 
        <a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&_type=_SETTING" style="<?php if ($_smarty_tpl->tpl_vars['_type']->value == '_SETTING') {?>font-weight:bold;<?php }?>">_SETTING</a>
    </p>
	<div class="infobox">
		<b>Ghi chú</b><br />
		<p>Phân hệ này chỉ ch phép nhà phát triển được phép truy cập</p>
	</div>
    <div class="hastable mt20">
        <table width="100%" cellspacing="0" class="table table-striped">
        	<tr>
                <td class="gridheader" style="text-align:left; width:30px;"><strong><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('No.');?>
</strong></td>
                <td class="gridheader" style="text-align:left;"><strong><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
</strong></td>
                <td class="gridheader" style="text-align:left;"><strong><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Mod');?>
</strong></td>
                <td class="gridheader" style="text-align:left;"><strong><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Act');?>
</strong></td>
                <td class="gridheader" style="text-align:left;"><strong><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('URL');?>
</strong></td>
				<td class="gridheader" style="width:6%;"><strong><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Public');?>
</strong></td>
                <td class="gridheader" style="text-align:left;"><strong><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Icon');?>
</strong></td>
                <td class="gridheader"><strong>Action</strong></td>
            </tr>
            <?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['allItem']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
            <tr class="<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)%2 == 0) {?>row1<?php } else { ?>row2<?php }?>">
                <td class="index"><?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)+1;?>
 </td>
                <td class="posts column-posts num">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&adminbutton_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['adminbutton_id']);?>
"><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title_page'];?>
</a>
                </td>
                <td class="posts column-posts num"> <?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['mod_page'];?>
</td>
                <td class="posts column-posts num"><?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['act_page'];?>
</td>
                <td class="posts column-posts num"> <?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['url_page'];?>
 </td>
				<td style="text-align:center">
					<a href="javascript:void(0);" class="SiteClickPublic" clsTable="AdminButton" pkey="<?php echo $_smarty_tpl->tpl_vars['pkeyTable']->value;?>
" sourse_id="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)][$_smarty_tpl->tpl_vars['pkeyTable']->value];?>
" toField="is_active" rel="<?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getOneField('is_active',$_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)][$_smarty_tpl->tpl_vars['pkeyTable']->value]);?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Click to change status');?>
">
						<?php if ($_smarty_tpl->tpl_vars['clsClassTable']->value->getOneField('is_active',$_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)][$_smarty_tpl->tpl_vars['pkeyTable']->value]) == '1') {?>
						<i class="fa fa-check-circle green"></i>
						<?php } else { ?>
						<i class="fa fa-minus-circle red"></i>
						<?php }?>
					</a>
				</td>
                <td class="posts column-posts num">
                    <img src="<?php echo $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['image'];?>
" width="32px" />
                </td>
                <td style="vertical-align: top; width: 30px; text-align: right; white-space: nowrap;">
					<div class="btn-group">
						<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
							<i class="icon-cog"></i> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" style="right:0px !important">
							 <li><a title="Sửa" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?admin&mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&adminbutton_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['adminbutton_id']);?>
"> <i class="icon-edit"></i> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('edit');?>
</a></li>
							 <li><a class="confirm_delete" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?admin&mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=delete&adminbutton_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['adminbutton_id']);?>
"><i class="icon-trash"></i>  <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
</a></li>
						</ul>
					</div>
                </td>
            </tr>	
                <?php $_smarty_tpl->_assignInScope('id', $_smarty_tpl->tpl_vars['allItem']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['adminbutton_id']);?>
                <?php $_smarty_tpl->_assignInScope('listChild', $_smarty_tpl->tpl_vars['clsClassTable']->value->getAll("is_trash=0 and is_group=0 and parent_id='".((string)$_smarty_tpl->tpl_vars['id']->value)."' order by order_no asc"));?>
                <?php
$__section_j_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['listChild']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_j_1_total = $__section_j_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_j'] = new Smarty_Variable(array());
if ($__section_j_1_total !== 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] <= $__section_j_1_total; $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']++){
?>
                <tr class="<?php echo smarty_function_cycle(array('values'=>"row1,row2"),$_smarty_tpl);?>
">
                    <td class="text-center">|_</td>
                    <td class="posts column-posts num">
                        <?php echo (isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['iteration'] : null);?>
. <a title="Sửa" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?admin&mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&adminbutton_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['adminbutton_id']);?>
"><strong><?php echo $_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['title_page'];?>
</strong></a>
                    </td>
                    <td class="posts column-posts num">
                        <?php echo $_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['mod_page'];?>

                    </td>
                    <td class="posts column-posts num">
                        <?php echo $_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['act_page'];?>

                    </td>
                    <td class="posts column-posts num">
                        <?php echo $_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['url_page'];?>

                    </td>
					<td style="text-align:center">
						<a href="javascript:void(0);" class="SiteClickPublic" clsTable="AdminButton" pkey="<?php echo $_smarty_tpl->tpl_vars['pkeyTable']->value;?>
" sourse_id="<?php echo $_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)][$_smarty_tpl->tpl_vars['pkeyTable']->value];?>
" toField="is_active" rel="<?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getOneField('is_active',$_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)][$_smarty_tpl->tpl_vars['pkeyTable']->value]);?>
" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Click to change status');?>
">
							<?php if ($_smarty_tpl->tpl_vars['clsClassTable']->value->getOneField('is_active',$_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)][$_smarty_tpl->tpl_vars['pkeyTable']->value]) == '1') {?>
							<i class="fa fa-check-circle green"></i>
							<?php } else { ?>
							<i class="fa fa-minus-circle red"></i>
							<?php }?>
						</a>
					</td>
                    <td class="posts column-posts num">
                        <img src="<?php echo $_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['image'];?>
" width="24px" />
                    </td>
                    <td style="vertical-align: top; width: 30px; text-align: right; white-space: nowrap;">
						 <div class="btn-group">
							<button class="btn iso-button-standard dropdown-toggle" type="button" data-toggle="dropdown">
								<i class="icon-cog"></i> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" style="right:0px !important">
					 			 <li><a title="Sửa" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?admin&mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=edit&adminbutton_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['adminbutton_id']);?>
"> <i class="icon-edit"></i> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('edit');?>
</a></li>
								 <li><a class="confirm_delete" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?admin&mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=delete&adminbutton_id=<?php echo $_smarty_tpl->tpl_vars['core']->value->encryptID($_smarty_tpl->tpl_vars['listChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_j']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_j']->value['index'] : null)]['adminbutton_id']);?>
"><i class="icon-trash"></i>  <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('delete');?>
</a></li>
							</ul>
						</div>
                    </td>
                </tr>
                <?php
}
}
?>
            <?php
}
}
?>
        </table>
    </div>
</div>
<?php }
}
