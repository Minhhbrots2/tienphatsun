<?php
/* Smarty version 3.1.33, created on 2026-07-30 11:55:36
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/tool/tool.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6ad948375ef7_20715996',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1b936037791d9a79f778e2b412891c6ab3c4d0be' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/tool/tool.tpl',
      1 => 1784299679,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6ad948375ef7_20715996 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="container-xxl flex-grow-1 pt-2 container-p-y">

	<div class="d-flex align-items-start justify-content-between mb-2">

		<div class="yvBvmnviXh">

			<h4 class="fw-bold mb-1 <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>fs-5<?php }?>">Tra cứu căn hộ cao tầng</h4>

			<p class="text-muted mb-0">Có <span class="total_results text-main">0</span> căn hộ phù hợp</p>

		</div>

		<?php if (!empty($_smarty_tpl->tpl_vars['list_projects']->value)) {?>

		<div class="btn-group">

			<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

			<button type="button" class="btn btn-outline-default btn-icon dropdown-toggle hide-arrow" 

				data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-filter-alt"></i></button>

			<?php } else { ?>

			<button type="button" class="btn btn-outline-default dropdown-toggle" data-bs-toggle="dropdown" 

				aria-expanded="false"><i class='bx bx-link-external'></i> Chuyển dự án</button>

			<?php }?>

			<ul class="dropdown-menu dropdown-menu-end w-px-300 overflow-y-auto">

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_projects']->value, '_oProject', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oProject']->value) {
?>

				<li><a class="dropdown-item text-truncate" href="/project/p<?php echo $_smarty_tpl->tpl_vars['_oProject']->value['project_id'];?>
.html">

					<div class="d-flex align-items-center justify-content-between">

						<span><?php echo $_smarty_tpl->tpl_vars['_oProject']->value['title'];?>
</span>

						<i class='bx bx-chevron-right'></i>

					</div>

				</a></li>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			</ul>

		</div>

		<?php }?>

	</div>

    <!-- Basic Bootstrap Table -->

    <div class="card">

		<div class="card-header om-xs:p-2">

			<form class="p-3 bg-lighter" method="POST">

				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('tool_search');?>


			</form>

		</div>

		<div class="card-body om-xs:p-2">

			<div id="tableStock" class="table-container no-shadow overflow-x-auto text-nowrap">

				<table cellpadding="0" cellspacing="0" class="table table-iloocal table-<?php echo $_smarty_tpl->tpl_vars['deviceType']->value;?>
 table-bordered">

					<thead><tr>

						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>

							<th class="align-center bg-lighter h-px-35">Mã căn 

								<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('view_stock_resource')) {?>

								<button type="button" onClick="$Core.tool.toogle_agency(this, event)" 

									class="btn btn-xs btn-toggle-agency btn-outline-default">Ẩn ĐL</button>

								<?php }?>

							</th>

							<th class="align-center bg-lighter h-px-35">

								<input type="hidden" name="sort_by" data-field="sort_by" class="search_field" value="asc" />

								<a onclick="$Core.tool.do_sort(this, event)" class="sortClick asc">Giá VAT</a>

							</th>

							<th class="align-center bg-lighter h-px-35 sortable ">TTS</th>

							<th class="align-center bg-lighter h-px-35 sortable">TTTĐ</th>

							<th class="align-center bg-lighter h-px-35 sortable">Vay</th>

							<th class="align-center text-center h-px-35 bg-lighter">M2</th>

							<th class="align-center bg-lighter">L.căn</th>

						<?php } else { ?>

							<th class="align-center bg-lighter h-px-35 w-px-20"></th>

							<th width="12%" class="align-center h-px-35 bg-lighter">Mã căn 

								<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('view_stock_resource')) {?>

								<button type="button" onClick="$Core.tool.toogle_agency(this, event)" class="btn btn-xs btn-toggle-agency btn-outline-default">Ẩn ĐL</button>

								<?php }?>

							</th>

							<th class="align-center bg-lighter h-px-35">Tình trạng</th>

							<th class="align-center sortable asc bg-lighter h-px-35" onclick="$Core.tool.do_sort(this, event)">

								<input type="hidden" name="sort_by" data-field="sort_by" class="search_field" value="asc" />

								<span>Giá VAT</span>

							</th>

							<th class="align-center bg-lighter h-px-35 sortable ">Giá TTS</th>

							<th class="align-center bg-lighter h-px-35 sortable">Giá TTTĐ</th>

							<th class="align-center bg-lighter h-px-35 sortable">Giá Vay</th>

							<th class="align-center bg-lighter h-px-35 sortable">Giá/m2</th>

							<th class="align-center text-center h-px-35 bg-lighter">DT_TT(m2)</th>

							<th class="align-center bg-lighter h-px-35">Loại căn</th>

							<th class="align-center bg-lighter h-px-35">Loại hình</th>

						<?php }?>

						<th class="align-center bg-lighter h-px-35">Hướng</th>

						<th class="align-center text-center h-px-35 bg-lighter">PTG</th>

						<th class="align-center text-center h-px-35 bg-lighter">CSBH</th>

					</tr></thead>

					<tbody class="holder_search">

						<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = $__section_i_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

						<tr>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<td><div class="animate-bg w-100 h-px-15 rounded-pill"></i><</td>

							<?php }?>

						</tr>

						<?php
}
}
?>

					</tbody>

				</table>

			</div>

			<div class="d-flex justify-content-between pt-2 text-center" id="showmorethisresult">

				<button type="button" class="showmorethisresult" onClick="$Core.tool.load_more(this, event)" page="1"> 

					<span>Xem thêm</span> 

					<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/loading_48.gif" width="24px"> 

				</button> 

			</div>

		</div>

	</div>

</div>



<style type="text/css">

	@media (min-width: 768px) and (max-width: 1024px) {

		.table-computer th:nth-child(4),

		.table-computer td:nth-child(4),

		.table-computer th:nth-child(8),

		.table-computer td:nth-child(8),

		.table-computer th:nth-child(11),

		.table-computer td:nth-child(11){

			display:none;

		}

	}

	@media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {

		.table-computer th:nth-child(4),

		.table-computer td:nth-child(4){

			display:none;

		}

	}

	@media screen and (max-width:1400px){

		.table-computer th:nth-child(7),

		.table-computer td:nth-child(7){

			display:none;

		}

	}

</style>

<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){ $Core.tool.do_search(); });

<?php echo '</script'; ?>
>



<?php }
}
