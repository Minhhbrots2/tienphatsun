<?php
/* Smarty version 3.1.33, created on 2026-08-08 17:31:40
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/_footer.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a77058c166897_48584513',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c8f42f235401167519d1d34a33c09692f5ef45b2' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/_footer.tpl',
      1 => 1784691760,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a77058c166897_48584513 (Smarty_Internal_Template $_smarty_tpl) {
?>		
	</div>
	<div class="clearfix"></div>
	<div id="page-footer">
		Powered by Future Tech &copy; 2023-<?php echo date('Y');?>
<br />
	</div>	       
</div>
<form class="form-upload d-none" method="post" action="" enctype="multipart/form-data">
	<input type="file" name="attachment" class="selectFile" />
</form>
<div id="ajax_loading"></div>

<!--<?php echo '<script'; ?>
 type="text/javascript">
	$.feedback({
		ajaxURL: path_ajax_script+'/feedback.cfg',
		html2canvasURL: 'html2canvas.min.js'
	});
<?php echo '</script'; ?>
>-->
<?php }
}
