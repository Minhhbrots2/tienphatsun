<?php
/* Smarty version 3.1.33, created on 2026-08-08 20:29:26
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/competition/default.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a772f36ecdd09_01263805',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e66e1fc8f27143eef67c08f02976c6e0e935955d' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/competition/default.tpl',
      1 => 1786190297,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:./_ajax.ranking.tpl' => 1,
  ),
),false)) {
function content_6a772f36ecdd09_01263805 (Smarty_Internal_Template $_smarty_tpl) {
?>
<style>
.top-ranker.comp-anim{ background-size:200% 200%; animation:compGradMove 8s ease infinite; }
@keyframes compGradMove{ 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
.top-ranker .table-ranker td{ color:#fff; }
.top-ranker .table-ranker .fw-semibold{ font-weight:600; }
.top-ranker .text-white-50{ color:rgba(255,255,255,.78) !important; }
</style>

<div class="container-xxl flex-grow-1 py-3 container-p-y">
	<div class="row">
		<div class="col-12 col-lg-8 mx-auto">
			<div class="mb-3">
				<h4 class="fw-bold mb-0">Thi đua định danh</h4>
				<span class="text-muted">Bảng xếp hạng các chương trình đang chạy</span>
			</div>
			<?php if (empty($_smarty_tpl->tpl_vars['boards']->value)) {?>
				<div class="card"><div class="card-body text-center text-muted py-5">Chưa có chương trình thi đua nào đang chạy.</div></div>
			<?php } else { ?>
				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['boards']->value, 'b');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['b']->value) {
?>
				<div class="mb-4">
					<?php $_smarty_tpl->_subTemplateRender("file:./_ajax.ranking.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('program'=>$_smarty_tpl->tpl_vars['b']->value['program'],'ranking'=>$_smarty_tpl->tpl_vars['b']->value['ranking'],'start_txt'=>$_smarty_tpl->tpl_vars['b']->value['start_txt'],'end_txt'=>$_smarty_tpl->tpl_vars['b']->value['end_txt'],'me_id'=>$_smarty_tpl->tpl_vars['me_id']->value), 0, true);
?>
				</div>
				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
			<?php }?>
		</div>
	</div>
</div>
<?php echo $_smarty_tpl->tpl_vars['scriptJs']->value;?>


<?php echo '<script'; ?>
 type="text/javascript">
	$Core = window.$Core || {};
	$Core.competition = $Core.competition || {};
	$Core.competition.detail = function(program_id, staff_id){
		$Core.util && $Core.util.toggleIndicatior && $Core.util.toggleIndicatior(1);
		$.post(PCMS_URL+'/index.php?mod=competition&act=detail', {'program_id':program_id,'staff_id':staff_id}, function(resp){
			$Core.util && $Core.util.toggleIndicatior && $Core.util.toggleIndicatior(0);
			$Core.popup.open('auto','auto', resp.html, resp.uid);
		}, 'json');
	};
<?php echo '</script'; ?>
>

<?php }
}
