<?php
/* Smarty version 3.1.33, created on 2026-07-30 13:27:11
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/agency.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6aeebfd791c8_77407806',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '80003c13fa7eb3acc82e5a5472606c3ebe1e165d' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/agency.tpl',
      1 => 1784691722,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6aeebfd791c8_77407806 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="ui-title-bar-container ui-title-bar-container--full-width">

	<div class="ui-title-bar">

		<div class="ui-title-bar__main-group">

			<div class="ui-title-bar__heading-group">

				<h1 class="ui-title-bar__title w-100">Đại lý</h1>

				<p class="type--subdued"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('This system allows you to manage & edit static pages in Systems');?>
</p>

			</div>

		</div>

		<div class="action-bar">

			<div class="ui-title-bar__mobile-primary-actions">

				<div class="ui-title-bar__actions">

					<button type="button" onClick="open_property(this)" class="btn btn-default" property_id="0" property_type="_AGENCY"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('plus-circle',$_smarty_tpl->tpl_vars['core']->value->get_Lang('Addnew'));?>
</button>

					<button type="button" onClick="$Core.property.storage_cache(this, event)" class="btn btn-icon btn-default ml2" property_id="0" property_type="_AGENCY" title="Cache"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('cloud');?>
</button> 

					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS']->value;?>
/admin?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&act=agency_hidden_stock" class="btn btn-icon btn-default ml2" title="Cấu hình quỹ ẩn"><i class="fa fa-cog fs-16"></i></a> 

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

						<form method="post">

							<div class="form-search form-inline">

								<div class="form-group">

									<input type="text" class="form-control" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('search');?>
" />

								</div>

								<input type="hidden" name="filter" value="filter" />

								<button type="submit" class="btn btn-success"><?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('search','Search');?>
</button>

								<div class="form-group pull-right">

									<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;
echo $_smarty_tpl->tpl_vars['pUrl']->value;?>
" class="btn text-white btn-warning">

										<i class="icon-folder-open icon-white"></i> 

										<span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('all');?>
 (<?php echo $_smarty_tpl->tpl_vars['total_record']->value;?>
)</span>

									</a>

									<a href="javascript:void(0)" clsTable="News" class="btn btn-danger text-white btn-delete-all" style="display:none"> 

                           				<i class="icon-remove icon-white"></i> 

                           				<span><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Delete');?>
</span> 

                           			</a>

								</div>

							</div>

							<div class="freeze-table dragscroll" style="overflow-x: scroll; width:100%;">

								<table id="tableCall" cellspacing="0" class="table table-vertical table-striped no-maxwidth" width="100%">

									<thead>

										<tr>

											<th class="text-center" width="5%" rowspan="2" style="vertical-align: middle;"></th>

											<th class="text-left" width="10%" rowspan="2" style="vertical-align: middle;"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Actions');?>
</th>

											<th class="text-left" width="5%" rowspan="2" style="vertical-align: middle;">No.</th>

											<th class="text-left" width="45%" rowspan="2" style="vertical-align: middle;"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Name');?>
</th>

											<th class="text-left" width="20%" rowspan="2" style="vertical-align: middle;"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Code');?>
</th>

											<th class="text-left" width="20%" rowspan="2" style="vertical-align: middle;"></th>

											<!-- MOC -->

											<?php if (!empty($_smarty_tpl->tpl_vars['agency_hidden_stock_MOC']->value)) {?>

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['agency_hidden_stock_MOC']->value, 'hidden_stock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['hidden_stock']->value) {
?>

													<th class="text-left" width="15%"><?php echo $_smarty_tpl->tpl_vars['hidden_stock']->value['title'];?>
</th>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											<?php }?>

											<!-- user.FH -->										

											<?php if (!empty($_smarty_tpl->tpl_vars['agency_hidden_stock_FH']->value)) {?>

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['agency_hidden_stock_FH']->value, 'hidden_stock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['hidden_stock']->value) {
?>

													<th class="text-left" width="15%"><?php echo $_smarty_tpl->tpl_vars['hidden_stock']->value['title'];?>
</th>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											<?php }?>										

											<th class="text-center" rowspan="2" style="vertical-align: middle;">Tình trạng</th>

										</tr>

										<tr>

											<!-- MOC -->

											<?php if (!empty($_smarty_tpl->tpl_vars['agency_hidden_stock_MOC']->value)) {?>

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['agency_hidden_stock_MOC']->value, 'hidden_stock', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['hidden_stock']->value) {
?>

												<th class="text-left">

													<div class="input-group d-flex gap-1">

														<button class="btn btn-sm btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" onClick="$Core.property.toggleSwitch(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>

														<button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" onClick="$Core.property.toggleSwitch(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>

													</div>

												</th>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											<?php }?>

											<!-- user.FH -->										

											<?php if (!empty($_smarty_tpl->tpl_vars['agency_hidden_stock_FH']->value)) {?>

												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['agency_hidden_stock_FH']->value, 'hidden_stock', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['hidden_stock']->value) {
?>

													<th class="text-left">

														<div class="input-group d-flex">

															<button class="btn btn-sm btn-lighter" data-toggle="tooltip" title="Tắt tất cả" key="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" onClick="$Core.property.toggleSwitch(this,event)" action="hide"><i class="fa fa-eye-slash" aria-hidden="true"></i></button>

															<button class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Bật tất cả" key="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" onClick="$Core.property.toggleSwitch(this,event)" action="show"><i class="fa fa-eye" aria-hidden="true"></i></button>

														</div>

													</th>

												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

											<?php }?>

										</tr>

									</thead>

									<tbody class="holderPropertyType_agency">

										

									</tbody>

								</table>

								<div class="d-flex justify-content-center">

									<ul class="pagination">

										<?php echo $_smarty_tpl->tpl_vars['html_pager']->value;?>


									</ul>

								</div>

							</div>

						</form>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>



<?php echo '<script'; ?>
>

	$(function(){

		$Core.property.load_list_agency("_AGENCY",{});

	})

<?php echo '</script'; ?>
>

<?php }
}
