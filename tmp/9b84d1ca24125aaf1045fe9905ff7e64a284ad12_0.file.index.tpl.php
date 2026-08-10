<?php
/* Smarty version 3.1.33, created on 2026-08-05 18:51:26
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/top_ranking/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7323be4a0611_48678200',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9b84d1ca24125aaf1045fe9905ff7e64a284ad12' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/top_ranking/index.tpl',
      1 => 1785930625,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7323be4a0611_48678200 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="d-flex flex-column align-items-center justify-content-center rank__block-menu">
	<div class="btn-group rank__tab-panel">
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_buttons']->value, '_oI', false, '_oK', 'i', array (
  'first' => true,
  'last' => true,
  'index' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oK']->value => $_smarty_tpl->tpl_vars['_oI']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['index'];
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>
		<input type="radio" class="btn-check" onChange="$Core.helper.set_ranking_type(this, event)" 
			id="<?php echo $_smarty_tpl->tpl_vars['_gId']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
" name="ranking_type" value="<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_oK']->value == '_all') {?> checked<?php }?>>
		<label data-toggle="ripple" class="btn btn-ranking text-fs-13<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['first'] : null)) {?> no-border-bottom-left-radius<?php }
if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?> no-border-bottom-right-radius<?php }?>" for="<?php echo $_smarty_tpl->tpl_vars['_gId']->value;?>
_<?php echo $_smarty_tpl->tpl_vars['_oK']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['_oI']->value;?>
</label>
		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	</div>
	<div class="rank__tab-menu position-relative">
		<ul class="d-flex justify-content-between rank__tab-nav">
			<li><a href="javascript:void(0);" onClick="$Core.helper.load_top_ranking(this, event)" tp="month" 
				class="text-white rank__tab-link" ranking_type="_all">Tháng</a></li>
			<li><a href="javascript:void(0);" onClick="$Core.helper.load_top_ranking(this, event)" tp="quarter" 
				class="text-white rank__tab-link" ranking_type="_all">Quý</a></li>
			<li><a href="javascript:void(0);" onClick="$Core.helper.load_top_ranking(this, event)" tp="year" 
				class="text-white rank__tab-link" ranking_type="_all">Năm</a></li>
		</ul>
	</div>
</div> 
<div class="rank__block-wrapper">
	<div class="rank__block-column d-flex justify-content-center">
		<div class="rank__block-item rank__top-second">
			<div class="rank__block-number mt-3">2</div>
			<div class="rank__block-profile pl-4">
				<div class="rank__profile-avatar d-flex justify-content-center mb-2">
					<div class="avatar avatar-md overflow-hidden rounded-circle">
						<div class="animate-bg w-100 h-100"></div>
					</div>
				</div>
				<h3 class="rank__profile-name fs-14 text-white font-bold mb-1">
					<div class="animate-bg rounded-1 mx-auto w-px-80"></div>
				</h3>
				<div class="mb-0 text-muted fs-13">
					<div class="animate-bg mx-auto rounded-1 w-px-50"></div>
				</div>
			</div>
		</div>
		<div class="rank__block-item rank__block-onload rank__top-one">
			<div class="rank__block-number mt-0">1</div>
			<div class="rank__block-profile pl-2">
				<div class="rank__profile-avatar d-flex justify-content-center mb-2">
					<div class="avatar avatar-md overflow-hidden rounded-circle">
						<div class="animate-bg rounded-1 w-100 h-100"></div>
					</div>
				</div>
				<h3 class="rank__profile-name fs-14 text-white font-bold mb-1">
					<div class="animate-bg rounded-1 mx-auto w-px-80"></div>
				</h3>
				<div class="mb-0 text-muted fs-13">
					<div class="animate-bg rounded-1 mx-auto w-px-50"></div>
				</div>
			</div>
		</div>
		<div class="rank__block-item rank__top-three">
			<div class="rank__block-number">3</div>
			<div class="rank__block-profile pl-4">
				<div class="rank__profile-avatar d-flex justify-content-center mb-2">
					<div class="avatar avatar-md overflow-hidden rounded-circle">
						<div class="animate-bg w-100 h-100"></div>
					</div>
				</div>
				<h3 class="rank__profile-name fs-14 text-white font-bold mb-1">
					<div class="animate-bg rounded-1 mx-auto w-px-80"></div>
				</h3>
				<div class="mb-0 text-muted fs-13">
					<div class="animate-bg mx-auto w-px-50"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="rank__block-row">
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">4</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">5</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">6</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">7</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">8</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">9</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
		<div class="d-flex align-items-center rank__row-item">
			<span class="rank__item-number rounded-circle text-center mr-2">10</span>
			<div class="rank__item-avatar mr-2">
				<div class="avatar avatar-xs overflow-hidden rounded-circle">
					<div class="animate-bg w-100 h-100"></div>
				</div>
			</div>
			<div class="rank__item-content">
				<h3 class="fs-14 font-bold mb-1">
					<div class="animate-bg rounded-1 w-px-150"></div>
				</h3>
				<div class="mb-1 text-muted fs-13">
					<div class="animate-bg rounded-1 w-px-100"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php echo '<script'; ?>
 type="text/javascript">
	$(function(){
		setTimeout(() => {
			$('.rank__tab-link[tp=year]').trigger('click');
		}, 500);
	});
<?php echo '</script'; ?>
>

<?php }
}
