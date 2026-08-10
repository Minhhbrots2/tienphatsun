<?php
/* Smarty version 3.1.33, created on 2026-08-08 17:31:40
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/blocks/quick_menu.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a77058c1418b4_58920432',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e0a670fb57687afa9134b7061453ac637c4a519d' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/blocks/quick_menu.tpl',
      1 => 1784691581,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a77058c1418b4_58920432 (Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
 type="text/javascript">

	$(function(){

		$(document).on('click', 'a.item-header', function(ev){

			var $_this = $(this),

				$_sub = $_this.parent().find('.submenu');

			if($_sub.is(':visible')){

				$_sub.stop(false,true).slideUp();

				$_this.find('.arrow').removeClass('fa-angle-up').addClass('fa-angle-down');

			}else{

				$('.submenu:visible').stop(false,true).slideUp();

				$('.arrow').removeClass('fa-angle-up').addClass('fa-angle-down');

				$_sub.stop(false,true).slideDown();

				$_this.find('.arrow').removeClass('fa-angle-down').addClass('fa-angle-up');

			}

		});

	});

<?php echo '</script'; ?>
>



<div class="sidebar--nav">

	<ul class="nav nav-list">

		<li class="<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home') {?>active<?php }?>">

			<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
" title="<?php echo $_smarty_tpl->tpl_vars['PAGE_NAME']->value;?>
" style="color:#f58220">

				<i class="fa fa-home"></i>

				<span class="menu-text bold"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('home');?>
</span>

			</a>

		</li>

		<?php $_smarty_tpl->_assignInScope('lstAdminButtonLeft', $_smarty_tpl->tpl_vars['clsAdminButton']->value->getAll('is_active=1 and is_group=1 and _type="_LEFT" order by order_no asc'));?>

		<?php
$__section_k_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstAdminButtonLeft']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_k_0_total = $__section_k_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_k'] = new Smarty_Variable(array());
if ($__section_k_0_total !== 0) {
for ($__section_k_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] = 0; $__section_k_0_iteration <= $__section_k_0_total; $__section_k_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']++){
?>

			<?php $_smarty_tpl->_assignInScope('id', $_smarty_tpl->tpl_vars['lstAdminButtonLeft']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['adminbutton_id']);?>

			<?php $_smarty_tpl->_assignInScope('lstAdminButtonLeftChild', $_smarty_tpl->tpl_vars['clsAdminButton']->value->getChild($_smarty_tpl->tpl_vars['id']->value));?>

			<?php if ($_smarty_tpl->tpl_vars['clsAdminButton']->value->checkConfiguration($_smarty_tpl->tpl_vars['lstAdminButtonLeft']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['CONFIGURATION_KEY'])) {?>

			<li class="<?php if ($_smarty_tpl->tpl_vars['mod']->value == $_smarty_tpl->tpl_vars['lstAdminButtonLeft']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['mod_page']) {?>active<?php }?>">

				<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['clsAdminButton']->value->getRootURL($_smarty_tpl->tpl_vars['lstAdminButtonLeft']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['adminbutton_id']);?>
" class="item-header <?php echo $_smarty_tpl->tpl_vars['lstAdminButtonLeft']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['class_page'];?>
">

					<i class="<?php echo $_smarty_tpl->tpl_vars['lstAdminButtonLeft']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['class_iconpage'];?>
"></i>

					<span class="menu-text"> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang($_smarty_tpl->tpl_vars['lstAdminButtonLeft']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null)]['title_page']);?>
</span>

					<?php if ($_smarty_tpl->tpl_vars['lstAdminButtonLeftChild']->value[0]['adminbutton_id'] != '') {?><b class="arrow fa fa-angle-down"></b><?php }?>

				</a>

				<?php if (!empty($_smarty_tpl->tpl_vars['lstAdminButtonLeftChild']->value)) {?>

				<div class="submenu" <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_k']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_k']->value['index'] : null) == 0) {?>style="display:block"<?php }?>>

					<ul class="nav-list sublist">

						<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['lstAdminButtonLeftChild']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = $__section_i_1_loop;
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>

						<?php if ($_smarty_tpl->tpl_vars['clsAdminButton']->value->checkConfiguration($_smarty_tpl->tpl_vars['lstAdminButtonLeftChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['CONFIGURATION_KEY'])) {?>

						<li<?php if ($_smarty_tpl->tpl_vars['lstAdminButtonLeftChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['class_page'] != '') {?> class="<?php echo $_smarty_tpl->tpl_vars['lstAdminButtonLeftChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['class_page'];?>
"<?php }?>>

							<a data-toggle="ripple" title="<?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang($_smarty_tpl->tpl_vars['lstAdminButtonLeftChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title_page']);?>
" href="<?php echo $_smarty_tpl->tpl_vars['clsAdminButton']->value->getURL($_smarty_tpl->tpl_vars['lstAdminButtonLeftChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['adminbutton_id']);?>
"><span><i class="<?php echo $_smarty_tpl->tpl_vars['lstAdminButtonLeftChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['class_iconpage'];?>
"></i> <?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang($_smarty_tpl->tpl_vars['lstAdminButtonLeftChild']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] : null)]['title_page']);?>
</span>

							</a>

						</li>

						<?php }?>

						<?php
}
}
?>

					</ul>

				</div>

				<?php }?>

			</li>

			<?php }?>

		<?php
}
}
?>

		<li class="hidden-sm hidden-xs d-none">

			<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=feedback" title="<?php echo $_smarty_tpl->tpl_vars['PAGE_NAME']->value;?>
">

				<img src="//bizweb.dktcdn.net/assets/admin/images/feedback.png" width="20px">

				<span class="menu-text bold"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Feedback');?>
</span>

			</a>

		</li>

	</ul>

	<div class="nav-user ">

		<div class="separate"></div>

		<div class="account-info<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'setting') {?> active<?php }?>">

			<a data-toggle="ripple" href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/index.php?mod=setting" id="submenu__link_settings" class="clearfix">

				<?php echo $_smarty_tpl->tpl_vars['core']->value->makeIcon('cog fs-16');?>


				<span class="menu-name"><?php echo $_smarty_tpl->tpl_vars['core']->value->get_Lang('Config');?>
</span>

			</a>

		</div>

	</div>

</div><?php }
}
