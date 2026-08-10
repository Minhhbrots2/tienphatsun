<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:57:38
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_ajax.progress.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a769b227c1cc2_97726987',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1f7a9d2cc30ae09ebca837ed8d6b4849e2231bd3' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/project/_ajax.progress.tpl',
      1 => 1784691721,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a769b227c1cc2_97726987 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a>
            <h3 class="modal-title"><strong><?php echo $_smarty_tpl->tpl_vars['titlePage']->value;?>
</strong></h3>
        </div>
        <?php $_smarty_tpl->_assignInScope('toId', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
        <form method="POST" class="frmIssue d-none" enctype="multipart/form-data">
            <input type="file" onchange="$Core.project.upload_image(this, event)" name="image"
                   maxlength="255" id="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
" />
        </form>
        <form method="post" action="" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade py-3 active in" id="content" role="tabpanel" aria-labelledby="content-tab">
                        <div class="widget-block mb-5">
                            <div class="widget-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <strong class="mb-0">Tổng quan</strong>
                                    <a href="javascript:void(0);" class="widget-add" onClick="add_progress(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['pvalTable']->value;?>
" _openFrom="_block" _holderG="_attrs"><i class="fa fa-plus"></i> Thêm mốc</a>
                                </div>
                            </div>
                            <div class="widget-content">
                                <div class="progress-blocks tbody_attrs no_group">
                                    <?php if (!empty($_smarty_tpl->tpl_vars['list_project_progress']->value)) {?>
                                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_project_progress']->value, '_Item', false, 'uid', 'i', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['uid']->value => $_smarty_tpl->tpl_vars['_Item']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
?>
                                            <?php echo $_smarty_tpl->tpl_vars['core']->value->build("_ajax.progress_item.tpl",array("_Item"=>$_smarty_tpl->tpl_vars['_Item']->value,"uid"=>$_smarty_tpl->tpl_vars['_Item']->value['id'],"index"=>(isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] : null),"media_json"=>$_smarty_tpl->tpl_vars['_Item']->value['media_json']));?>

                                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                                    <?php } else { ?>
                                        <?php $_smarty_tpl->_assignInScope('uid', $_smarty_tpl->tpl_vars['clsISO']->value->getUniqid());?>
                                        <?php echo $_smarty_tpl->tpl_vars['core']->value->build("_ajax.progress_item.tpl",array("uid"=>$_smarty_tpl->tpl_vars['uid']->value));?>

                                    <?php }?>
                                </div>
                            </div>
                            <div class="pc-add-bottom">
                                <a href="javascript:void(0);" class="btn btn-default btn-block pc-add-btn" onClick="add_progress(this, event)" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" _openFrom="_block" _holderG="_attrs"><i class="fa fa-plus"></i> Thêm mốc tiến độ</a>
                            </div>
                        </div>
                        <datalist id="progress-date-suggest">
                            <option value="Quý I/2026"></option>
                            <option value="Quý II/2026"></option>
                            <option value="Quý III/2026"></option>
                            <option value="Quý IV/2026"></option>
                            <option value="Quý I/2027"></option>
                            <option value="Quý II/2027"></option>
                            <option value="Quý III/2027"></option>
                            <option value="Quý IV/2027"></option>
                            <option value="Quý I/2028"></option>
                            <option value="Quý II/2028"></option>
                            <option value="T6/2026"></option>
                            <option value="T12/2026"></option>
                            <option value="Đã hoàn thành"></option>
                            <option value="Đang thi công"></option>
                            <option value="Dự kiến"></option>
                        </datalist>
                    </div>
                </div>
            </div>
            <input type="hidden" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
">
            <input type="hidden" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" value="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
">
            <div class="modal-footer">
                <button type="button" class="btn btn-success pull-right" onClick="pop_save_progress(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" project_id="<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" _openFrom="<?php echo $_smarty_tpl->tpl_vars['_openFrom']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_openFrom']->value == '_stock') {?> toId="<?php echo $_smarty_tpl->tpl_vars['toId']->value;?>
"<?php }?>>Cập nhật</button>
                <button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Close');?>
</button>
            </div>
        </form>
    </div>
</div><?php }
}
