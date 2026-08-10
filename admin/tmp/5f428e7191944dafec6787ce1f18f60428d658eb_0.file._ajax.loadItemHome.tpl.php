<?php
/* Smarty version 3.1.33, created on 2026-08-10 08:31:27
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/home/_ajax.loadItemHome.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7929ef41f5a5_43256422',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5f428e7191944dafec6787ce1f18f60428d658eb' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/home/_ajax.loadItemHome.tpl',
      1 => 1784691677,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7929ef41f5a5_43256422 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/function.math.php','function'=>'smarty_function_math',),));
if (!empty($_smarty_tpl->tpl_vars['lstItem']->value)) {?>

	<?php if ($_smarty_tpl->tpl_vars['type']->value == '_SOP') {?>

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstItem']->value, '_oItem', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

			<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>

			<?php echo smarty_function_math(array('equation'=>"x+1",'x'=>$_smarty_tpl->tpl_vars['offset']->value,'assign'=>"offset"),$_smarty_tpl);?>


			<tr>

				<td><?php echo $_smarty_tpl->tpl_vars['offset']->value;?>
</td>

				<td class="text-left"><a class="fw-bold" href="<?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getLink($_smarty_tpl->tpl_vars['_oItem']->value['sop_id'],$_smarty_tpl->tpl_vars['more_information']->value['stock_code']);?>
" target="_blank" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" sop_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['sop_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_code'];?>
</a></td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['clsMember']->value->getFullName($_smarty_tpl->tpl_vars['_oItem']->value['user_id']);?>
</td>

				<td class="text-right"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['_oItem']->value['reg_date'],true);?>
</td>

				
			</tr>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	<?php } elseif ($_smarty_tpl->tpl_vars['type']->value == '_LEASING') {?>

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstItem']->value, '_oItem', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

			<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>

			<?php echo smarty_function_math(array('equation'=>"x+1",'x'=>$_smarty_tpl->tpl_vars['offset']->value,'assign'=>"offset"),$_smarty_tpl);?>


			<tr>

				<td><?php echo $_smarty_tpl->tpl_vars['offset']->value;?>
</td>

				<td class="text-left"><a class="fw-bold" href="<?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getLink($_smarty_tpl->tpl_vars['_oItem']->value['leasing_id'],$_smarty_tpl->tpl_vars['more_information']->value['stock_code']);?>
" target="_blank" stock_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" leasing_id="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['leasing_id'];?>
"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_code'];?>
</a></td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['clsMember']->value->getFullName($_smarty_tpl->tpl_vars['_oItem']->value['user_id']);?>
</td>

				<td class="text-right"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['_oItem']->value['reg_date'],true);?>
</td>

				
			</tr>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	<?php } elseif ($_smarty_tpl->tpl_vars['type']->value == '_SERVICES') {?>

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstItem']->value, '_oItem', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

			<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']);?>

			<?php echo smarty_function_math(array('equation'=>"x+1",'x'=>$_smarty_tpl->tpl_vars['offset']->value,'assign'=>"offset"),$_smarty_tpl);?>


			<tr>

				<td><?php echo $_smarty_tpl->tpl_vars['offset']->value;?>
</td>

				<td class="text-left"><a class="fw-bold" href="<?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getLink($_smarty_tpl->tpl_vars['_oItem']->value['service_id'],$_smarty_tpl->tpl_vars['_oItem']->value);?>
" target="_blank" ><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['name'];?>
</a></td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['more_information']->value['phone'];?>
</td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['clsMember']->value->getFullName($_smarty_tpl->tpl_vars['_oItem']->value['user_id']);?>
</td>

				
			</tr>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	<?php } elseif ($_smarty_tpl->tpl_vars['type']->value == '_INTERIOR') {?>

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstItem']->value, '_oItem', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

			<?php echo smarty_function_math(array('equation'=>"x+1",'x'=>$_smarty_tpl->tpl_vars['offset']->value,'assign'=>"offset"),$_smarty_tpl);?>


			<tr>

				<td><?php echo $_smarty_tpl->tpl_vars['offset']->value;?>
</td>

				<td class="text-left"><a class="fw-bold" href="<?php echo $_smarty_tpl->tpl_vars['clsClassTable']->value->getLink($_smarty_tpl->tpl_vars['_oItem']->value['interior_id'],$_smarty_tpl->tpl_vars['_oItem']->value);?>
" target="_blank" ><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
</a></td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['clsCompany']->value->getTitle($_smarty_tpl->tpl_vars['_oItem']->value['company_id']);?>
</td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['clsMember']->value->getFullName($_smarty_tpl->tpl_vars['_oItem']->value['user_id']);?>
</td>

				<td class="text-right"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['_oItem']->value['reg_date'],true);?>
</td>

				
			</tr>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	<?php } elseif ($_smarty_tpl->tpl_vars['type']->value == '_INTERIOR_REQUEST') {?>

		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstItem']->value, '_oItem', false, NULL, 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>

			<?php echo smarty_function_math(array('equation'=>"x+1",'x'=>$_smarty_tpl->tpl_vars['offset']->value,'assign'=>"offset"),$_smarty_tpl);?>


			<?php $_smarty_tpl->_assignInScope('more_information', $_smarty_tpl->tpl_vars['_oItem']->value['more_information']['field']);?>

			<tr>

				<td><?php echo $_smarty_tpl->tpl_vars['offset']->value;?>
</td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['name'];?>
</td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['phone'];?>
</td>

				<td class="text-left">

					<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['more_information']->value, '_oItemMore');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItemMore']->value) {
?>

						<p class=""><strong><?php echo $_smarty_tpl->tpl_vars['_oItemMore']->value['title'];?>
:</strong> <?php echo $_smarty_tpl->tpl_vars['_oItemMore']->value['value'];?>
</p>

					<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

				</td>

				<td class="text-left"><?php echo html_entity_decode($_smarty_tpl->tpl_vars['_oItem']->value['intro']);?>
</td>

				<td class="text-left"><?php echo $_smarty_tpl->tpl_vars['clsMember']->value->getFullName($_smarty_tpl->tpl_vars['_oItem']->value['profile_id']);?>
</td>

				<td class="text-right"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->convertTimeToText($_smarty_tpl->tpl_vars['_oItem']->value['reg_date'],true);?>
</td>

				
			</tr>

		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

	<?php }?>

<?php } else { ?>

	<tr>

		<td class="text-center" colspan="<?php if ($_smarty_tpl->tpl_vars['type']->value == '_INTERIOR') {?>5<?php } elseif ($_smarty_tpl->tpl_vars['type']->value == '_INTERIOR_REQUEST') {?>8<?php } else { ?>4<?php }?>">

			<div class="text-center">

				<img class="mb-2" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAE0AAABqCAIAAAB2wktpAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkY0RTUxMzM1OEZBQTExRThBOUQxQjhGNDMzNjRGM0JDIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkY0RTUxMzM2OEZBQTExRThBOUQxQjhGNDMzNjRGM0JDIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RjRFNTEzMzM4RkFBMTFFOEE5RDFCOEY0MzM2NEYzQkMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RjRFNTEzMzQ4RkFBMTFFOEE5RDFCOEY0MzM2NEYzQkMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6dXDCjAAAD+ElEQVR42uycC0/aUBTHaSm1iI6HlJfR4URnNDoU39F9fDcQhOjMHo4Z5SXjIaUKqDx21C0xxUkbq97Wc0JI7k1J+NH/edx7T6FEUby4uNiOxgWhatCCURS1MD/n8bgVfYputVrhSEwrkGCdTiee2CuVyso4fyYPRfHMoClrt9vRWELR16aPjlIGDVqz2Yxs79TrdZnXM1dXV3fHNpt1aMhBGhXcDACTTDYajXA4tr6+zLJsb07JOPhh1mLpJ40zm811c4Kd12oQQVdXFo1GYw/dSsc0rS0BVypCPLELHquMU4uWzxd2d/d1yAkpVDKTyea+fT/QG+eYf3RwYEAymUwePpA7NMkJAXZ5eYHjOMn8l/2vcGN15Z8Auby0wDDSfAGOem+ppOE4NDg4sBgKSjLK/0olbcdbKGnmg3OSsASZ9nM4CqlVV3nF7eZnZqYkk5eXl9uRHXjXVf58OzoSCLy7t1SC1Zh+OMHeTwaAtrtUikbjt6USo0UquFfFYqlLwC6YlLhlsVSGCBwMzmqSM5XKwEvmxZBROTOnE90+bFAqvQpOzcSh7mpWn5zT01OPXP1rIw4B5MfN9YpQbf/Lhw8bVAjxxJ72OG83Ohx2m8yL6/WGDvcT9OOfyKl2HCqXT3O5fLvTJmVF5nD4fB6VOaFoDEdiPfcOn9OOj9MMY3S5eDV1WxVEoiD/Suy0orJ/2u3W7p2YlzWKonjeqbJuOY7b2FgF/7x38/9FcqmLd9psVvXjkKW/PzA+hnkFOZETOZETOZET1yvddpL/nUplCKlyKcrA8/yYf1RlzqooxmIJou5PoVDqY00+n1dN3Z6f1wiUYlVRP5jMRS3H9ZEVV2jaq6SVUZZuWda0ubEGUmnJ21Z8ege9/ukV7ejKjUMsyw4Pew2aNcyfyImcyImcyPkSdTxU8PKPH5/BrNY3JpNJZc5ms7m1FRHPCHosAkq09bUV+SWRLN0WS2WiIA3XJ9ZX6UxWZf/sk/FAwfObuav/9rG6tdttM9NTqXSGqHOHkZFh9eOQ3z/qV7J+x7yCnMiJnMiJnMj5etYrlYqQzeZaxHTX8LzT43apzFmr1T993iatT2ppacElu7VGlm4rgkBin1T5VGX/hDqe6fVg8AtI1zmksm7NN31SmewJKeeCN/7pcNjVj0MWi2VyYhzzCnIiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3IiJ3I+NWdHF1xSCul+/MHBL0XPd5NphUJRyknT9N1Tk1Q6Ay+didZopGmf16N75/R6PPTkZIBlTTqGZFkWGGmzmQuF5vWKClyhUBAYKVEUYVxvNA5+JHMnpPwTxOONYRiv1z0xMX7bvvpHgAEAO0R+YvoBo4cAAAAASUVORK5CYII=" width="40px">

				<p class="type--subdued">Danh sách trống</p>

			</div>

		</td>

	</tr>

<?php }
}
}
