<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:52:33
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/top_ranker_25/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315f189ebc1_57358059',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9d68ccfef0071cf3442aed37a4e7aa11e60ead4a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/top_ranker_25/index.tpl',
      1 => 1785927055,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315f189ebc1_57358059 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="my-zoom-wrapper" class="w-100 overflow-hidden rounded-2 position-relative zoom-container-wrapper mb-3">
    <div class="w-100 h-100 zoom-container">
		<div class="box_ranker rounded-2 position-relative overflow-hidden mb-3">			
			<div class="menu_rank menu_rank_pc">
				<button class="btn btn-icon text-white dropdown-toggle  hide-arrow" data-bs-toggle="dropdown">
					<i class='bx bx-menu fs-30'></i>
				</button>
				<div class="rank__tab-menu dropdown-menu">
					<ul class="d-flex justify-content-between rank__tab-nav">
						<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="month" class="text-white rank_link">Tháng</a></li>
						<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="quarter" class="text-white rank_link">Quý</a></li>
						<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="year" class="text-white rank_link">Năm</a></li>
					</ul>
				</div>
			</div>
			<div class="box_content position-absolute left-0">
				<div class="box_title position-relative d-inline-block">
					<div class="d-flex align-item-start gap-1">
						<div class="menu_rank menu_rank_mobile">
							<button class="btn btn-icon text-white dropdown-toggle  hide-arrow" data-bs-toggle="dropdown">
								<i class='bx bx-menu fs-30'></i>
							</button>
							<div class="rank__tab-menu dropdown-menu">
								<ul class="d-flex justify-content-between rank__tab-nav">
									<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="month" class="text-white rank_link">Tháng</a></li>
									<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="quarter" class="text-white rank_link">Quý</a></li>
									<li><a onclick="$Core.helper.load_top_ranking25(this, event)" tp="year" class="text-white rank_link">Năm</a></li>
								</ul>
							</div>
						</div>
						<img src="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['LogoWhite'];?>
" class="image_logo w-px-100">
					</div>
					<h2 class="text-upper mb-2">Đại lộ danh vọng</h2>
					<div class="light_title position-absolute left-0 w-100"></div>
				</div>
				<div class="box_body_content text-white">
					<h3 class="text-upper fs-3 mb-2"><?php echo $_smarty_tpl->tpl_vars['title_content']->value;?>
</h3>
					<p class=" fs-16 text-white">(<?php echo $_smarty_tpl->tpl_vars['title_content_time']->value;?>
)</p>
				</div>
			</div>
			<div class="d-flex align-items-end lst_chart">
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstItem']->value, '_oItem', false, 'key', 'i', array (
  'last' => true,
  'iteration' => true,
  'total' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] = $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['iteration'] === $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['total'];
?>
				<div class="item_ranker flex-flow position-relative<?php if (!empty($_smarty_tpl->tpl_vars['_oItem']->value['is_none'])) {?> is_none<?php }?> rounded-1" 
					style="height:<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['col_height'];?>
%; background-color:<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['bgcolor'];?>
;" >
					<div class="box_avt position-absolute d-flex align-items-center justify-content-center cursor-pointer" style="background:url(<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['bg_image'];?>
); background-size:100%; background-position: bottom; background-repeat: no-repeat;<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_i']->value['last'] : null)) {?> width:calc(100% * 1.8)<?php }?>">
						<img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.jpg?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" 
							class="rounded-pill w-100" width="30" height="30">
					</div>
					<div class="text-center fw-bold d-flex flex-column info_ranker">
						<span class="text-warning"></span>
						<span class="text-main"></span>
					</div>
				</div>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			</div>	
		</div>
	</div>
</div>

<?php echo '<script'; ?>
 type="text/javascript">
	$(() => {
		setTimeout(() => {
			$('.rank_link[tp=year]:first-child').trigger('click');
			$('#my-zoom-wrapper').zoomPan({
				zoomStep: 0.5,
				minScale: 1,
				maxScale: 5
			});
		}, 500)
	});
<?php echo '</script'; ?>
>

<?php }
}
