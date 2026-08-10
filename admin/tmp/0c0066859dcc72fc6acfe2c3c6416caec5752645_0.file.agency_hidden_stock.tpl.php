<?php
/* Smarty version 3.1.33, created on 2026-08-08 09:46:57
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/agency_hidden_stock.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7698a12b3172_66796316',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0c0066859dcc72fc6acfe2c3c6416caec5752645' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/agency_hidden_stock.tpl',
      1 => 1784691722,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7698a12b3172_66796316 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/function.math.php','function'=>'smarty_function_math',),));
?>
<div class="ui-title-bar-container ui-title-bar-container--full-width">

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title w-100">Cấu hình ẩn bảng hàng</h1>

				<p class="type--subdued"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('This system allows you to manage & edit static pages in Systems');?>
</p>

			</div>

		</div>

		<div class="action-bar">

			<div class="ui-title-bar__mobile-primary-actions">

				<div class="ui-title-bar__actions">

					<button type="button" onClick="$Core.property.open_hidden_stock(this,event)" class="btn btn-default" agency_hidden_stock_id="" data-type="_FH"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus-circle',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew'));?>
</button>

				</div>

			</div>

		</div>

	</div>

</div>

<div class="ui-layout ui-layout--full-width">

	<div class="ui-layout__sections">

		<div class="ui-layout__section">

			<div class="ui-layout__item">

				<div class="ui-card">

					<div class="next-tab__container">

						<ul class="next-tab__list filter-tab-list">

							<li class="filter-tab-item" data-tab-index="1">

								<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">

                                    <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('AllPages');?>


                                </a>

							</li>

						</ul>

					</div>

					<div class="ui-card__section has-bulk-actions pages">

						<div class="hastable">

							<table class="table mb-0 table-striped" cellspacing="0" cellpadding="0" width="100%">

								<thead><tr>

									<th class="text-left" width="5%">No.</th>

									<th class="text-left">Tiêu đề</th>

									<th class="text-left" width="20%">Phân khu</th>

									<th class="text-left" width="100px">Ẩn trên Website</th>

									<th class="text-left" width="10%"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
</th>

								</tr></thead>

								<tbody>

									<?php if (!empty($_smarty_tpl->tpl_vars['agency_hidden_stock_FH']->value) || !empty($_smarty_tpl->tpl_vars['agency_hidden_stock_MOC']->value)) {?>

										<?php $_smarty_tpl->_assignInScope('stt', 1);?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['agency_hidden_stock_FH']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

											<tr>

												<td data-label="No."><?php echo $_smarty_tpl->tpl_vars['stt']->value;?>
</td>

												<td class="text-nowrap" data-label="Tiêu đề"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>

												<td>

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['_oItem']->value['block_id']);?>


												</td>

												<td class="text-center">

													<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['site'] == "_FH") {?>CA<?php } else { ?>MOC<?php }?>

												</td>

												<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">

													<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">

														<button class="btn btn-default" onClick="$Core.property.open_hidden_stock(this,event)" class="btn btn-default" agency_hidden_stock_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-type="_FH" ><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>

														<button class="btn btn-default" onClick="$Core.property.save_hidden_stock(this,event)" agency_hidden_stock_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-type="_FH" data-action="delete"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

													</div>

												</td>

											</tr>

											<?php echo smarty_function_math(array('equation'=>"x+1",'x'=>$_smarty_tpl->tpl_vars['stt']->value,'assign'=>"stt"),$_smarty_tpl);?>


										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['agency_hidden_stock_MOC']->value, '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>

											<tr>

												<td data-label="No."><?php echo $_smarty_tpl->tpl_vars['stt']->value;?>
</td>

												<td class="text-nowrap" data-label="Tiêu đề"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</td>

												<td>

													<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['_oItem']->value['block_id']);?>


												</td>

												<td class="text-center">

													<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['site'] == "_FH") {?>CA<?php } else { ?>MOC<?php }?>

												</td>

												<td data-label="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
">

													<div class="d-flex btn-group btn-group-xs ui-btn-group-custom">

														<button class="btn btn-default" onClick="$Core.property.open_hidden_stock(this,event)" class="btn btn-default" agency_hidden_stock_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-type="_MOC"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('pencil');?>
</button>

														<button class="btn btn-default" onClick="$Core.property.save_hidden_stock(this,event)" agency_hidden_stock_id="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" data-type="_MOC" data-action="delete"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('trash');?>
</button>

													</div>

												</td>

											</tr>

											<?php echo smarty_function_math(array('equation'=>"x+1",'x'=>$_smarty_tpl->tpl_vars['stt']->value,'assign'=>"stt"),$_smarty_tpl);?>


										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

									<?php } else { ?>

										<tr><td class="text-center" colspan="5">Danh sách trống</td></tr>

									<?php }?>

								</tbody>

							</table>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div><?php }
}
