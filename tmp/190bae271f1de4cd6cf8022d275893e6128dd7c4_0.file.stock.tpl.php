<?php
/* Smarty version 3.1.33, created on 2026-08-05 15:31:54
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/stock.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a72f4fa38b644_18540870',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '190bae271f1de4cd6cf8022d275893e6128dd7c4' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/home/project/stock.tpl',
      1 => 1785918589,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a72f4fa38b644_18540870 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/www/wwwroot/tienphatsunrise.c-a.vn/core/smarty/plugins/function.math.php','function'=>'smarty_function_math',),));
echo '<script'; ?>
 type="text/javascript">
	var project_id = '<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
',
		block_type = '<?php echo $_smarty_tpl->tpl_vars['block_type']->value;?>
',
		block_id = '<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
',
		is_project_block = '<?php echo $_smarty_tpl->tpl_vars['is_project_block']->value;?>
',
		_BLOCK_TYPE_LOWFLOOR_SALE = '<?php echo @constant('_BLOCK_TYPE_LOWFLOOR_SALE');?>
',
		_BLOCK_TYPE_HIGHLEVEL_SALE = '<?php echo @constant('_BLOCK_TYPE_HIGHLEVEL_SALE');?>
';
<?php echo '</script'; ?>
>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/leaflet/leaflet.min.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"/>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/leaflet/leaflet.draw.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"/>
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/leaflet/Control.MiniMap.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/leaflet/store.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/connectingLine/jquery.connectingLine.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<!-- Zoom bảng hàng (zoom/cuộn lăn/pinch/kéo/fullscreen/chụp ảnh) -->
<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/stock-zoom.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"/>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/html2canvas.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/stock-zoom.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<div class="container-xxl flex-grow-1 pt-2 container-p-y">
	<nav aria-label="breadcrumb" class=" d-flex justify-content-between align-items-center gap-2">
		<ol class="breadcrumb mb-2">
			<li class="breadcrumb-item">
				<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/bang-hang/">Bảng hàng</a>
			</li>
			<li class="breadcrumb-item">
				<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,0,0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getCode($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['oneProject']->value);?>
</a>
			</li>
			<?php if ($_smarty_tpl->tpl_vars['block_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>
			<li class="breadcrumb-item">
				<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['oneBuilding']->value['for_id'],0,$_smarty_tpl->tpl_vars['oneProject']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getTitle($_smarty_tpl->tpl_vars['oneBuilding']->value['for_id']);?>
</a>
			</li>			
			<li class="breadcrumb-item active"><?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
</li>
			<?php } elseif (!empty($_smarty_tpl->tpl_vars['block_is_project']->value)) {?>
				<li class="breadcrumb-item active"><?php echo $_smarty_tpl->tpl_vars['oneBlock']->value['title'];?>
</li>
			<?php }?>
		</ol>
		<?php if ($_smarty_tpl->tpl_vars['block_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE') && $_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
			<div class="d-flex justify-content-end">
				<div class="btn-group mb-2 justify-content-center gap-1">
					<button class="btn btn-outline-default btn-icon rounded-pill btn_bg_sold light btn-sm <?php if ($_smarty_tpl->tpl_vars['stock_bg_sold']->value == 'light') {?>active<?php }?>" onClick="$Core.stock.setBgSold(this,event)" data-type="light" ></button>
					<button class="btn btn-outline-default btn-icon rounded-pill btn_bg_sold dark btn-sm <?php if ($_smarty_tpl->tpl_vars['stock_bg_sold']->value == 'dark') {?>active<?php }?>" onClick="$Core.stock.setBgSold(this,event)" data-type="dark"></button>
				</div>
			</div>
		<?php }?>
	</nav>
	<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock("banner_stock",array('oneBuilding'=>$_smarty_tpl->tpl_vars['oneBuilding']->value));?>

	<div class="clearfix"></div>
	<div class="card">
		<div class="card-body">
			<?php if ($_smarty_tpl->tpl_vars['block_type']->value == @constant('_BLOCK_TYPE_HIGHLEVEL_SALE')) {?>	
				<div class="d-flex justify-content-between">
					<div class="input-group justify-content-center mb-2 no-shadow flex-fill">
						<?php if (!empty($_smarty_tpl->tpl_vars['vr_link']->value)) {?>
						<a data-toggle="ripple" class="btn text-white<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm d-flex align-items-center justify-content-center px-1 flex-fill fs-10<?php }?>" title="Xem VR360" href="<?php echo $_smarty_tpl->tpl_vars['vr_link']->value;?>
" data-fancybox data-type="iframe" style="background: #5a5a5a"><img src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/vr360.png" class="w-px-20" style="filter: invert(1);"><?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>VR360<?php }?></a><?php }?>
						<?php if ($_smarty_tpl->tpl_vars['is_map']->value == '1') {?>
						<a data-toggle="ripple" href="/project/p<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
/b<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
/map.html" title="Xem dạng layout" class="text-white btn<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm px-1 flex-fill fs-10<?php }?>" style="background: #583400"><i class='bx bx-map-alt <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == "phone") {?>fs-14<?php }?>'></i> Map</a><?php }?>
						<?php if ($_smarty_tpl->tpl_vars['is_map_dq']->value == '1') {?>
						<a data-toggle="ripple" href="javascript:void(0)" class="btn bg-main text-white<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm px-1 flex-fill fs-10<?php }?>" title="Xem dạng layout banner" onClick="$Core.project.open_map(this, event)" block_id="<?php echo $_smarty_tpl->tpl_vars['block_id']->value;?>
" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['clsConfiguration']->value->getValue('LogoWhite');?>
" width="<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>14px<?php } else { ?>18px<?php }?>" alt="<?php echo $_smarty_tpl->tpl_vars['header_configs']->value['CompanyName'];?>
" /> Map ĐQ</a><?php }?>
						<!-- <a data-toggle="ripple" href="javascript:void(0);" class="btn text-white<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm px-1 flex-fill<?php }?>" title="Chọn tòa" onClick="$Core.project.toggle_dropdown(this, event)" style="background: #ffab00"><i class='bx bx-buildings <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == "phone") {?>fs-14<?php }?>'></i> Chọn tòa</a> -->
						<a data-toggle="ripple" href="javascript:void(0);" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" class="btn text-white<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm px-1 flex-fill fs-10<?php }?>" onClick="$Core.stock.toggle_floor_empty(this, event)" title="Ẩn tầng trống" style="background:#136b32"><i class='fa fa-eye'></i> Ẩn tầng trống</a> <!-- data-bind="click" -->
						<a data-toggle="ripple" href="javascript:void(0);" building_id="<?php echo $_smarty_tpl->tpl_vars['building_id']->value;?>
" class="btn text-white<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> btn-sm px-1 flex-fill fs-10<?php }?>" onClick="$Core.stock.hide_stock_cross(this, event)" title="Ẩn/hiện quỹ chéo" style="background:#23008d"> <?php if ($_smarty_tpl->tpl_vars['is_hide_stock_cross']->value == '1') {?><i class='fa fa-eye-slash'></i> Hiện<?php } else { ?><i class='fa fa-eye'></i> Ẩn<?php }?> <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>Q/Chéo<?php } else { ?>quỹ chéo<?php }?></a>
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>
							<div class="dropdown dropdown_price">
								<button data-toggle="ripple" class="btn bg-warning text-white dropdown-toggle btn-sm px-1 no-radius-left flex-fill fs-10" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" data-popper-placement="top-start" aria-haspopup="true" aria-expanded="false">Giá VAT</button>
								<ul class="dropdown-menu" style="">
									<li style="background: #fff8ff">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom active" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_vat" data-text="Giá VAT" 
										   style="background: #05009c;color: #FFF !important;"
										><span>Giá VAT</span><span class="text-muted">(tỷ)</span></a>
									</li>
									<li style="background: #fff8ff">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_m2" data-text="VAT/m2" 
										   style="background: #05009c;color: #FFF !important;"
										><span>Giá VAT/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
									</li>
									<li style="background: #edfff7">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_early" data-text="Giá TTS" 
										   style="background: #650202;color: #FFF !important;"
									   ><span>Giá TTS</span><span class="text-muted">(tỷ)</span></a>
									</li>
									<li style="background: #edfff7">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_tts_m2" data-text="TTS/m2" 
										   style="background: #650202;color: #FFF !important;"
									   ><span>Giá TTS/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
									</li>
									<li style="background: #d2fff7">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_progress" data-text="Giá TTTĐ" 
										   style="background: #4c0057;color: #FFF !important;"
									   ><span>Giá TTTĐ</span><span class="text-muted">(tỷ)</span></a>
									</li>
									<li style="background: #d2fff7">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_tttd_m2" data-text="TTTĐ/m2" 
										   style="background: #4c0057;color: #FFF !important;"
									   ><span>Giá TTTĐ/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
									</li>
									<li style="background: #e3ebff">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_bank" data-text="Giá Vay" 
										   style="background: #8e7803;color: #FFF !important;"
									   ><span>Giá Vay</span><span class="text-muted">(tỷ)</span></a>
									</li>
									<li style="background: #e3ebff">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_vay_m2" data-text="Vay /m2" 
										   style="background: #8e7803;color: #FFF !important;"
									   ><span>Giá Vay/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
									</li>
									<li style="background: #fde2d2">
										<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_full" data-text="3PA" 
										   style="background: #00624e;color: #FFF !important;"
									   ><span>Giá 3PA thanh toán</span><span class="text-muted">(tỷ)</span></a>
									</li>
									<li style="background: #fde2d2">
										<a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_full_m2" data-text="3PA/m2" 
										   style="background: #00624e;color: #FFF !important;"
									   ><span>Giá 3PA/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
									</li>
								</ul>
							</div>
						<?php }?>
					</div>
					<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>
						<div class="d-flex align-items-center gap-2">
							<div class="btn-group gap-1">
								<button class="btn btn-outline-default btn_bg_sold light btn-icon rounded-pill <?php if ($_smarty_tpl->tpl_vars['stock_bg_sold']->value == 'light') {?>active<?php }?>" onClick="$Core.stock.setBgSold(this,event)" data-type="light" ></button>
								<button class="btn btn-outline-default btn_bg_sold dark btn-icon rounded-pill <?php if ($_smarty_tpl->tpl_vars['stock_bg_sold']->value == 'dark') {?>active<?php }?>" onClick="$Core.stock.setBgSold(this,event)" data-type="dark"></button>
							</div>
							<div class="dropdown dropdown_price">
							<button data-toggle="ripple" class="btn bg-warning text-white dropdown-toggle w-px-140" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" data-popper-placement="top-start" aria-haspopup="true" aria-expanded="false">Giá full VAT</button>
							<ul class="dropdown-menu" style="">
								<li style="background: #fff8ff">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom active" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_vat" data-text="Giá VAT" 
									   style="background: #05009c;color: #FFF !important;"
								   ><span>Giá VAT</span><span class="text-muted">(tỷ)</span></a>
								</li>
								<li style="background: #fff8ff">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_m2" data-text="Giá VAT/m2" 
									   style="background: #05009c;color: #FFF !important;"
								  ><span>Giá VAT/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
								</li>
								<li style="background: #edfff7">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_early" data-text="Giá TTS" 
									   style="background: #650202;color: #FFF !important;"
								   ><span>Giá TTS</span><span class="text-muted">(tỷ)</span></a>
								</li>
								<li style="background: #edfff7">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_tts_m2" data-text="Giá TTS/m2" 
									   style="background: #650202;color: #FFF !important;"
								   ><span>Giá TTS/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
								</li>
								<li style="background: #d2fff7">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_progress" data-text="Giá TTTĐ" 
									   style="background: #4c0057;color: #FFF !important;"
								   ><span>Giá TTTĐ</span><span class="text-muted">(tỷ)</span></a>
								</li>
								<li style="background: #d2fff7">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_tttd_m2" data-text="Giá TTTĐ/m2" 
									   style="background: #4c0057;color: #FFF !important;"
								   ><span>Giá TTTĐ/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
								</li>
								<li style="background: #e3ebff">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="total_price_bank" data-text="Giá Vay" 
									   style="background: #8e7803;color: #FFF !important;"
								   ><span>Giá Vay</span><span class="text-muted">(tỷ)</span></a>
								</li>
								<li style="background: #e3ebff">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_vay_m2" data-text="Giá Vay /m2" 
									   style="background: #8e7803;color: #FFF !important;"
								   ><span>Giá Vay/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
								</li>
								<li style="background: #fde2d2">
									<a class="dropdown-item d-flex align-items-center justify-content-between border-bottom" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_full" data-text="Giá 3PA" 
									   style="background: #00624e;color: #FFF !important;"
								   ><span>Giá 3PA thanh toán</span><span class="text-muted">(tỷ)</span></a>
								</li>
								<li style="background: #fde2d2">
									<a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0);" onClick="$Core.project.show_price(this,event)" data-type="price_full_m2" data-text="Giá 3PA /m2" 
									   style="background: #00624e;color: #FFF !important;"
								   ><span>Giá 3PA/m<sup>2</sup></span><span class="text-muted">(triệu)</span></a>
								</li>
						  	</ul>
						</div>
						</div>
					<?php }?>
				</div>	
				<div id="tblStock" class="freeze-table text-nowrap table-container" data-building="<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['property_code'];?>
">
					<table class="table fixedTable table-stock table-bordered dragable installed nohover" cellpadding="0" cellspacing="0" >
						<thead><tr>
							<th class="align-center bg-white text-center"  colspan="2" style="min-width:60px">
								<strong class="fs-18"><?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title_vn'];?>
 - <?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['property_code'];?>
</strong>
							</th>
							<?php $_smarty_tpl->_assignInScope('total_rowspan', $_smarty_tpl->tpl_vars['total_rowspan']->value-7);?>
							<th class="align-center text-center" colspan="7" style="height:30px">
								<?php if (!empty($_smarty_tpl->tpl_vars['list_status']->value)) {?>
									<div class="d-flex align-items-center justify-content-center flex-wrap gap-2">
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['list_status']->value, '_oProp', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oProp']->value) {
?>
											<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oProp']->value['property_id'],$_smarty_tpl->tpl_vars['arr_status_id_show']->value)) {?>
												<?php if (@constant('_STOCK_STATUS_GENERAL_ID') == $_smarty_tpl->tpl_vars['_oProp']->value['property_id']) {?>
													<?php $_smarty_tpl->_assignInScope('text_color', 'danger');?>
												<?php } else { ?>
													<?php $_smarty_tpl->_assignInScope('text_color', 'white');?>
												<?php }?>
												<div class="d-flex align-items-center gap-1 text-center" >
													<span style="background:<?php echo $_smarty_tpl->tpl_vars['_oProp']->value['bgcolor'];?>
" class="d-block border rounded-pill w-px-15 h-px-15" ></span>
													<span class="fs-11"><?php echo $_smarty_tpl->tpl_vars['_oProp']->value['title'];?>
</span>
												</div>
											<?php }?>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
										<div class="d-flex align-items-center gap-1 text-center" >
											<span class="status_fund_type" title="Thứ cấp"></span>
											<span class="fs-11">Quỹ thứ cấp</span>
										</div>
									</div>
								<?php }?>
							</th>
							<th class="text-center" colspan="<?php echo $_smarty_tpl->tpl_vars['total_rowspan']->value-1;?>
">
								<?php if (!empty($_smarty_tpl->tpl_vars['more_information']->value['title_ts'])) {?>
									<?php echo $_smarty_tpl->tpl_vars['more_information']->value['title_ts'];?>

								<?php } else { ?>
									Note: Giá đã bao gồm VAT & KPBT
								<?php }?>
							</th>
						</tr> 
						<?php if (!empty($_smarty_tpl->tpl_vars['group_cols_template']->value)) {?>
							<tr>
								<th class=""></th>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['group_cols_template']->value, '_col', false, '_symbol', 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_symbol']->value => $_smarty_tpl->tpl_vars['_col']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
									<th class="text-center fs-16" colspan="<?php echo $_smarty_tpl->tpl_vars['_col']->value;?>
" style="height:30px"><?php echo $_smarty_tpl->tpl_vars['_symbol']->value;?>
</th>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</tr>
						<?php }?>
						<tr>
							<th class="cell" width="60px">T/C</th>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_cols']->value, 'oneCode', false, '_code', 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_code']->value => $_smarty_tpl->tpl_vars['oneCode']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
								<?php $_smarty_tpl->_assignInScope('bedroom_id', $_smarty_tpl->tpl_vars['oneCode']->value['bedroom_id']);?>								
								<?php $_smarty_tpl->_assignInScope('layout', $_smarty_tpl->tpl_vars['oneCode']->value['layout']);?>
								<?php if (!empty($_smarty_tpl->tpl_vars['layout']->value)) {?>
									<th width="60px" class="text-center js__stock-cell-focus text-white cursor-pointer" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['bedroom_id']->value];?>
" data-fancybox="layout_<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" data-fancybox="488" data-src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['layout']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['oneCode']->value['code'];?>
<i class='bx bx-layout fs-12' ></i></th>
								<?php } else { ?>
									<th width="60px" class="text-center js__stock-cell-focus text-white" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['bedroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['oneCode']->value['code'];?>
</th>
								<?php }?>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tr>
						<tr>
							<th class="cell">DT.Tim</th>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_stocks']->value, '_code', false, NULL, 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
							<?php $_smarty_tpl->_assignInScope('beroom_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInCol($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cols']->value,'bedroom_id'));?>
							<th class="text-center js__stock-cell-focus text-white" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['beroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInStock($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cols']->value,'DT_Tim');?>
</th>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tr>
						<tr>
							<th class="cell">DT.TT</th>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_stocks']->value, '_code', false, NULL, 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
							<?php $_smarty_tpl->_assignInScope('beroom_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInCol($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cols']->value,'bedroom_id'));?>
							<th class="text-center js__stock-cell-focus text-white" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['beroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInStock($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cols']->value,'DT_TT');?>
</th>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tr>
						<tr>
							<th class="cell">H.BC</th>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_stocks']->value, '_code', false, NULL, 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
							<?php $_smarty_tpl->_assignInScope('beroom_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInCol($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cols']->value,'bedroom_id'));?>
							<th class="text-center js__stock-cell-focus text-white" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['beroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInStock($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cols']->value,'home_direction_id');?>
 </th>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tr>
						<tr>
							<th class="cell">L. Căn</th>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_stocks']->value, '_code', false, NULL, 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
							<?php $_smarty_tpl->_assignInScope('beroom_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInCol($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cols']->value,'bedroom_id'));?>
							<th class="text-center js__stock-cell-focus text-white" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['beroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInStock($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cols']->value,'bedroom_id');?>
</th>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tr>
						<tr>
							<th class="cell">View</th>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_stocks']->value, '_code', false, NULL, 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
							<?php $_smarty_tpl->_assignInScope('beroom_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInCol($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cols']->value,'bedroom_id'));?>
							<th class="text-center js__stock-cell-focus text-white" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['beroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInStock($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cols']->value,'view_id',true);?>
</th>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tr></thead>
						<tbody>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_floors']->value, '_floor', false, NULL, 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_floor']->value) {
?>
								<?php if (!empty($_smarty_tpl->tpl_vars['floor_merge_arrs']->value)) {?>				
									<?php $_smarty_tpl->_assignInScope('num_merge', 0);?>
									<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['floor_merge_arrs']->value, '_dfloor', false, '_sfloor');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_sfloor']->value => $_smarty_tpl->tpl_vars['_dfloor']->value) {
?>		
										<?php $_smarty_tpl->_assignInScope('cell_col_index', $_smarty_tpl->tpl_vars['_dfloor']->value['cell_col_index']);?>
										<?php $_smarty_tpl->_assignInScope('arr_cols', $_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols']);?>
										<?php if ($_smarty_tpl->tpl_vars['_floor']->value == strval($_smarty_tpl->tpl_vars['_sfloor']->value) && !$_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['floor_service_arrs']->value)) {?>
											<?php if (!empty($_smarty_tpl->tpl_vars['_dfloor']->value['is_out_order'])) {?>
												<?php $_smarty_tpl->_assignInScope('is_out_order', 1);?>
												<?php $_smarty_tpl->_assignInScope('_arr_stock', $_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks']);?>
												<?php echo smarty_function_math(array('equation'=>"x-y",'x'=>count($_smarty_tpl->tpl_vars['arr_stocks']->value),'y'=>count($_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks']),'assign'=>"num_merge"),$_smarty_tpl);?>

											<?php } else { ?>
												<?php $_smarty_tpl->_assignInScope('is_out_order', 0);?>
												<?php $_smarty_tpl->_assignInScope('_arr_stock', $_smarty_tpl->tpl_vars['arr_stocks']->value);?>
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['_floor']->value == $_smarty_tpl->tpl_vars['clsISO']->value->getFirstItemInArray($_smarty_tpl->tpl_vars['arr_floors']->value)) {?>
												<tr class="nohover">
													<td colspan="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['number_house']+1;?>
" class="h-px-20"></td>
												</tr>
											<?php }?>
											<tr class="nohover">							
												<td class="cell">
													<div class="d-flex justify-content-between">
														T/C <button class="btn btn-icon btn-xs text-white btn-default hide" toCls="<?php echo $_smarty_tpl->tpl_vars['_sfloor']->value;?>
" type="button" onClick="$Core.project.toggle_row_stock(this,event)"><i class='bx bx-chevron-down'></i></button>
													</div>
												</td>
												<?php $_smarty_tpl->_assignInScope('check', 1);?>			
												<?php $_smarty_tpl->_assignInScope('rowspan', 1);?>													
												<?php if (count($_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs']) > 1) {?>
													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_arr_stock']->value, '_code', false, 'key', 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
														<?php $_smarty_tpl->_assignInScope('index_merge', (isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration'] : null));?>
														<?php if (!empty($_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs']) && !$_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'])) {?>
															<?php $_smarty_tpl->_assignInScope('check', 0);?>
															<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'], '_cell_merge');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_cell_merge']->value) {
?>
																<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['index_merge']->value,$_smarty_tpl->tpl_vars['_cell_merge']->value['arr_cols_merge']) && $_smarty_tpl->tpl_vars['index_merge']->value == $_smarty_tpl->tpl_vars['_cell_merge']->value['arr_cols_merge'][0]) {?>
																	<th colspan="<?php echo $_smarty_tpl->tpl_vars['_cell_merge']->value['collspan'];?>
" rowspan="<?php echo $_smarty_tpl->tpl_vars['_cell_merge']->value['rowspan'];?>
" class="td_hidden_row_<?php echo $_smarty_tpl->tpl_vars['_sfloor']->value;?>
" floor="<?php echo $_smarty_tpl->tpl_vars['_sfloor']->value;?>
" style="background:#ffe59a;border-width:0 !important"></th>
																	<?php $_smarty_tpl->_assignInScope('rowspan', $_smarty_tpl->tpl_vars['_cell_merge']->value['rowspan']);?>	
																<?php }?>
															<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
														<?php } elseif ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'])) {?>
															<?php $_smarty_tpl->_assignInScope('beroom_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInCol($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols'],'bedroom_id'));?>	
															<th width="60px" class="text-center notFixedTable js__stock-cell-focus text-white" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['beroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols'][$_smarty_tpl->tpl_vars['_code']->value]['code'];?>
</th>
														<?php }?>
													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												<?php } else { ?>
													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_arr_stock']->value, '_code', false, 'key', 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>														
														<?php if (!empty($_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs']) && !$_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks']) && !empty($_smarty_tpl->tpl_vars['check']->value)) {?>
															<?php $_smarty_tpl->_assignInScope('check', 0);?>
															<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'], '_cell_merge');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_cell_merge']->value) {
?>
																<th colspan="<?php echo $_smarty_tpl->tpl_vars['_cell_merge']->value['collspan'];?>
" rowspan="<?php echo $_smarty_tpl->tpl_vars['_cell_merge']->value['rowspan'];?>
" class="td_hidden_row_<?php echo $_smarty_tpl->tpl_vars['_sfloor']->value;?>
" floor="<?php echo $_smarty_tpl->tpl_vars['_sfloor']->value;?>
" style="background:#ffe59a;border-width:0 !important"></th>
																<?php $_smarty_tpl->_assignInScope('rowspan', $_smarty_tpl->tpl_vars['_cell_merge']->value['rowspan']);?>						
																<?php $_smarty_tpl->_assignInScope('floor_flag', $_smarty_tpl->tpl_vars['_sfloor']->value);?>
															<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
														<?php } elseif ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'])) {?>
															<?php $_smarty_tpl->_assignInScope('rowspan', $_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'][0]['rowspan']);?>	
															<?php $_smarty_tpl->_assignInScope('beroom_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInCol($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols'],'bedroom_id'));?>	
															<th width="60px" class="text-center notFixedTable js__stock-cell-focus text-white" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['beroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols'][$_smarty_tpl->tpl_vars['_code']->value]['code'];?>
</th>
														<?php }?>
													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												<?php }?>
												<?php if (!empty($_smarty_tpl->tpl_vars['num_merge']->value)) {?>
													<th colspan="<?php echo $_smarty_tpl->tpl_vars['num_merge']->value;?>
" rowspan="<?php echo $_smarty_tpl->tpl_vars['rowspan']->value;?>
" class="td_hidden_row_<?php echo $_smarty_tpl->tpl_vars['_sfloor']->value;?>
" floor="<?php echo $_smarty_tpl->tpl_vars['_sfloor']->value;?>
" style="background:#ffe59a;border-width:0 !important"></th>																		
													<?php $_smarty_tpl->_assignInScope('floor_flag', $_smarty_tpl->tpl_vars['_sfloor']->value);?>
												<?php }?>
											</tr>
											<tr class="tr_hidden tr_hidden_<?php echo $_smarty_tpl->tpl_vars['_sfloor']->value;?>
 nohover d-none">
												<td class="cell">DT.TT</td>
												<?php $_smarty_tpl->_assignInScope('check_merge', 0);?>
												<?php $_smarty_tpl->_assignInScope('col_plus', 1);?>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'], '_code', false, 'key', 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>	
													<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'])) {?>	
														<?php $_smarty_tpl->_assignInScope('index', $_smarty_tpl->tpl_vars['key']->value);?>	
														<?php if (((!empty($_smarty_tpl->tpl_vars['cell_col_index']->value[$_smarty_tpl->tpl_vars['key']->value+1]) || (empty($_smarty_tpl->tpl_vars['cell_col_index']->value[$_smarty_tpl->tpl_vars['key']->value+1]) && !empty($_smarty_tpl->tpl_vars['check_merge']->value))))) {?> 
															<?php if (!empty($_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'])) {?>
																<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'], 'cell');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['cell']->value) {
?>
																	<?php if (($_smarty_tpl->tpl_vars['cell']->value['start_cell'] == $_smarty_tpl->tpl_vars['key']->value+1)) {?>
																		<?php $_smarty_tpl->_assignInScope('col_plus', $_smarty_tpl->tpl_vars['cell']->value['collspan']);?>
																	<?php }?>
																<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
															<?php }?>
															<?php echo smarty_function_math(array('equation'=>"x+y",'x'=>$_smarty_tpl->tpl_vars['index']->value,'y'=>$_smarty_tpl->tpl_vars['col_plus']->value,'assign'=>"index"),$_smarty_tpl);?>

															<?php $_smarty_tpl->_assignInScope('check_merge', 1);?>
														<?php }?>	
														<?php $_smarty_tpl->_assignInScope('beroom_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInCol($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols'],'bedroom_id'));?>	
														<th width="60px" class="text-center notFixedTable js__stock-cell-focus text-white"  code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['beroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInStock($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols'],'DT_TT');?>
</th>
													<?php }?>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											</tr>
											<tr class="tr_hidden tr_hidden_<?php echo $_smarty_tpl->tpl_vars['_sfloor']->value;?>
 nohover d-none">
												<td class="cell">H.BC</td>
												<?php $_smarty_tpl->_assignInScope('check_merge', 0);?>
												<?php $_smarty_tpl->_assignInScope('col_plus', 1);?>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'], '_code', false, 'key', 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
													<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'])) {?>
														<?php $_smarty_tpl->_assignInScope('index', $_smarty_tpl->tpl_vars['key']->value);?>
														<?php if (((!empty($_smarty_tpl->tpl_vars['cell_col_index']->value[$_smarty_tpl->tpl_vars['key']->value+1]) || (empty($_smarty_tpl->tpl_vars['cell_col_index']->value[$_smarty_tpl->tpl_vars['key']->value+1]) && !empty($_smarty_tpl->tpl_vars['check_merge']->value))))) {?>
															<?php if (!empty($_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'])) {?>
																<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'], 'cell');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['cell']->value) {
?>
																	<?php if (($_smarty_tpl->tpl_vars['cell']->value['start_cell'] == $_smarty_tpl->tpl_vars['key']->value+1)) {?>
																		<?php $_smarty_tpl->_assignInScope('col_plus', $_smarty_tpl->tpl_vars['cell']->value['collspan']);?>
																	<?php }?>
																<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
															<?php }?>
															<?php echo smarty_function_math(array('equation'=>"x+y",'x'=>$_smarty_tpl->tpl_vars['index']->value,'y'=>$_smarty_tpl->tpl_vars['col_plus']->value,'assign'=>"index"),$_smarty_tpl);?>

															<?php $_smarty_tpl->_assignInScope('check_merge', 1);?>
														<?php }?>
														<?php $_smarty_tpl->_assignInScope('beroom_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInCol($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols'],'bedroom_id'));?>	
														<th width="60px" class="text-center notFixedTable js__stock-cell-focus text-white" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['beroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInStock($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols'],'home_direction_id');?>
</th>
													<?php }?>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											</tr>
											<tr class="tr_hidden tr_hidden_<?php echo $_smarty_tpl->tpl_vars['_sfloor']->value;?>
 nohover d-none">
												<td class="cell">L. Căn</td>
												<?php $_smarty_tpl->_assignInScope('check_merge', 0);?>
												<?php $_smarty_tpl->_assignInScope('col_plus', 1);?>
												<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'], '_code', false, 'key', 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
													<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'])) {?>
														<?php $_smarty_tpl->_assignInScope('index', $_smarty_tpl->tpl_vars['key']->value);?>
														<?php if (((!empty($_smarty_tpl->tpl_vars['cell_col_index']->value[$_smarty_tpl->tpl_vars['key']->value+1]) || (empty($_smarty_tpl->tpl_vars['cell_col_index']->value[$_smarty_tpl->tpl_vars['key']->value+1]) && !empty($_smarty_tpl->tpl_vars['check_merge']->value))))) {?>
															<?php if (!empty($_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'])) {?>
																<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'], 'cell');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['cell']->value) {
?>
																	<?php if (($_smarty_tpl->tpl_vars['cell']->value['start_cell'] == $_smarty_tpl->tpl_vars['key']->value+1)) {?>
																		<?php $_smarty_tpl->_assignInScope('col_plus', $_smarty_tpl->tpl_vars['cell']->value['collspan']);?>
																	<?php }?>
																<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
															<?php }?>
															<?php echo smarty_function_math(array('equation'=>"x+y",'x'=>$_smarty_tpl->tpl_vars['index']->value,'y'=>$_smarty_tpl->tpl_vars['col_plus']->value,'assign'=>"index"),$_smarty_tpl);?>

															<?php $_smarty_tpl->_assignInScope('check_merge', 1);?>
														<?php }?>
														<?php $_smarty_tpl->_assignInScope('beroom_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInCol($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols'],'bedroom_id'));?>	
														<th width="60px" class="text-center notFixedTable js__stock-cell-focus text-white" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" style="background:<?php echo $_smarty_tpl->tpl_vars['bedroom_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['beroom_id']->value];?>
"><?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getFieldInStock($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_cols'],'bedroom_id');?>
</th>
													<?php }?>
												<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
											</tr>
											<?php break 1;?>
										<?php }?>
									<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
								<?php }?>
								<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['floor_service_arrs']->value)) {?>
									<tr class="nohover">
										<td style="background:#ffe59a" class="text-center"><?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
</td>
										<td class="text-center" colspan="<?php echo $_smarty_tpl->tpl_vars['more_information']->value['number_house'];?>
">Tầng dịch vụ hoặc lánh nạn</td>
									</tr>
								<?php } elseif (!empty($_smarty_tpl->tpl_vars['floor_merge_service_arrs']->value[$_smarty_tpl->tpl_vars['_floor']->value])) {?>
									<?php $_smarty_tpl->_assignInScope('_dfloor', $_smarty_tpl->tpl_vars['floor_merge_service_arrs']->value[$_smarty_tpl->tpl_vars['_floor']->value]);?>	
									<?php $_smarty_tpl->_assignInScope('arr_merge_cols', $_smarty_tpl->tpl_vars['_dfloor']->value['arr_merge_cols']);?>
									<tr class="nohover">
										<td style="background:#ffe59a" class="text-center py-3" ><?php echo $_smarty_tpl->tpl_vars['_dfloor']->value['name_floor'];?>
</td>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_merge_cols']->value, '_oItem');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
											<?php $_smarty_tpl->_assignInScope('_stock_id', $_smarty_tpl->tpl_vars['_oItem']->value['stock_id']);?>
											<?php $_smarty_tpl->_assignInScope('_agency_id', $_smarty_tpl->tpl_vars['_oItem']->value['agency_id']);?>
											<?php $_smarty_tpl->_assignInScope('_status_id', $_smarty_tpl->tpl_vars['_oItem']->value['status_id']);?>											
											<?php $_smarty_tpl->_assignInScope('_total_price_vat', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_vat'));
$_smarty_tpl->_assignInScope('_total_price_early', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_early'));?>
											<?php $_smarty_tpl->_assignInScope('_total_price_progress', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_progress'));?>
											<?php $_smarty_tpl->_assignInScope('_total_price_bank', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_bank'));?>
											<?php $_smarty_tpl->_assignInScope('_price_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_price_tts_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_tts_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_price_tttd_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_tttd_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_price_vay_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_vay_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_is_dq', $_smarty_tpl->tpl_vars['_oItem']->value['is_dq']);?>
											<?php $_smarty_tpl->_assignInScope('index', $_smarty_tpl->tpl_vars['key']->value);?>
											<?php if ($_smarty_tpl->tpl_vars['_oItem']->value['stock_id'] > '0') {?>
												<?php if ((!empty($_smarty_tpl->tpl_vars['_oItem']->value['agency_id']) && ($_smarty_tpl->tpl_vars['list_agency_cached_VIN']->value[$_smarty_tpl->tpl_vars['_oItem']->value['agency_id']] == '1' || $_smarty_tpl->tpl_vars['list_agency_cached_MAS']->value[$_smarty_tpl->tpl_vars['_oItem']->value['agency_id']] == '1')) || ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_HIDDEN_ID') && !$_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('view_stock_hidden'))) {?>
													<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
													<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['code'];?>
" floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
												<?php } else { ?>
													<?php if (!empty($_smarty_tpl->tpl_vars['_status_id']->value) && $_smarty_tpl->tpl_vars['_status_id']->value > '0') {?>
														<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_NON_ID')) {?>
															<!-- Non -->
														<?php } else { ?>
															<?php if ($_smarty_tpl->tpl_vars['is_hide_stock_cross']->value == '1' && $_smarty_tpl->tpl_vars['_agency_id']->value != @constant('_AGENCY_FH_ID')) {?>
																<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
															<?php }?>
															<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID')) {?>
																<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" 
																class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
															<?php } else { ?>
																<?php $_smarty_tpl->_assignInScope('_textcolor', $_smarty_tpl->tpl_vars['status_textcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value]);?>
																<td floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" code="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['code'];?>
" class="stock_cell stock_cell_<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
 text-center js__stock-cell-focus cursor-pointer position-relative " style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
"<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID') || $_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_NON_ID')) {
} else { ?> <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>onClick="$Core.helper.open_stock('<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
');" <?php } else { ?>data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" data-toggle="webui-popover" data-trigger="click" data-placement="auto"<?php }?> data-width="350"<?php }?>><a href="javascript:void(0)" style="color:<?php echo $_smarty_tpl->tpl_vars['_textcolor']->value;?>
" class="stock_show <?php echo $_smarty_tpl->tpl_vars['_is_dq']->value;?>
" data-total_price_vat="<?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
" data-total_price_early="<?php echo $_smarty_tpl->tpl_vars['_total_price_early']->value;?>
" data-total_price_progress="<?php echo $_smarty_tpl->tpl_vars['_total_price_progress']->value;?>
" data-total_price_bank="<?php echo $_smarty_tpl->tpl_vars['_total_price_bank']->value;?>
" data-price_m2="<?php echo $_smarty_tpl->tpl_vars['_price_m2']->value;?>
" data-price_tts_m2="<?php echo $_smarty_tpl->tpl_vars['_price_tts_m2']->value;?>
" data-price_tttd_m2="<?php echo $_smarty_tpl->tpl_vars['_price_tttd_m2']->value;?>
" data-price_vay_m2="<?php echo $_smarty_tpl->tpl_vars['_price_vay_m2']->value;?>
" >
																	<?php if (!empty($_smarty_tpl->tpl_vars['_total_price_vat']->value)) {?>	
																		<?php if ($_smarty_tpl->tpl_vars['clsStock']->value->checkShow($_smarty_tpl->tpl_vars['_oItem']->value['show_website'],'MOC')) {?>
																			<span class="price_show"><?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
</span>
																		<?php } else { ?>
																			<span class="text-decoration-line-through price_show"><?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
</span>
																		<?php }?>
																		<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_oItem']->value['stock_id'],$_smarty_tpl->tpl_vars['arr_stock_lock']->value)) {?>
																			<span class="lock_stock"><i class='bx bx-lock-alt' ></i></span>
																		<?php }?>
																	<?php } else { ?>
																		<!-- Empty -->
																	<?php }?>
																</a></td>
															<?php }?>
														<?php }?>
													<?php } else { ?>
														<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
														<?php $_smarty_tpl->_assignInScope('_bgcolor', $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value]);?>
														<td floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" code="<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['code'];?>
" style="cursor:pointer; background:<?php echo $_smarty_tpl->tpl_vars['_bgcolor']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID')) {
} else { ?> data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['_oItem']->value['stock_id'];?>
" data-toggle="webui-popover" data-trigger="click" data-width="350"<?php }?> class="text-center js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
													<?php }?>
												<?php }?>
											<?php }?>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
									</tr>
								<?php } else { ?>
								<tr class="<?php echo $_smarty_tpl->tpl_vars['floor_flag']->value;?>
">
									<?php $_smarty_tpl->_assignInScope('layout_floor', $_smarty_tpl->tpl_vars['clsProperty']->value->getLayout($_smarty_tpl->tpl_vars['building_id']->value,$_smarty_tpl->tpl_vars['_floor']->value,'','floor',$_smarty_tpl->tpl_vars['oneBuilding']->value));?>
									<?php if (!empty($_smarty_tpl->tpl_vars['layout_floor']->value)) {?>
										<td class="text-center cursor-pointer" style="background:#ffe59a" data-fancybox="layout_floor_<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" data-fancybox="488" data-src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['layout_floor']->value);?>
"><?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
 <i class='bx bx-layout fs-12' ></i></td>
									<?php } else { ?>
										<td class="text-center" style="background:#ffe59a"><?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
</td>
									<?php }?>
									<?php if (!empty($_smarty_tpl->tpl_vars['floor_merge_arrs']->value[$_smarty_tpl->tpl_vars['_floor']->value])) {?>											
										<?php $_smarty_tpl->_assignInScope('check_merge', 0);?>
										<?php $_smarty_tpl->_assignInScope('col_plus', 0);?>
										<?php $_smarty_tpl->_assignInScope('_dfloor', $_smarty_tpl->tpl_vars['floor_merge_arrs']->value[$_smarty_tpl->tpl_vars['_floor']->value]);?>																				
										<?php $_smarty_tpl->_assignInScope('cell_col_index', $_smarty_tpl->tpl_vars['_dfloor']->value['cell_col_index']);?>
										<?php if (!empty($_smarty_tpl->tpl_vars['_dfloor']->value['is_out_order'])) {?>
											<?php $_smarty_tpl->_assignInScope('is_out_order', 1);?>
											<?php $_smarty_tpl->_assignInScope('_arr_stock', $_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks']);?>
										<?php } else { ?>
											<?php $_smarty_tpl->_assignInScope('is_out_order', 0);?>
											<?php $_smarty_tpl->_assignInScope('_arr_stock', $_smarty_tpl->tpl_vars['arr_stocks']->value);?>
										<?php }?>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'], '_code', false, 'key', 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>													
											<?php $_smarty_tpl->_assignInScope('_stock_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'stock_id'));?>
											<?php $_smarty_tpl->_assignInScope('_agency_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'agency_id'));?>
											<?php $_smarty_tpl->_assignInScope('_status_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'status_id'));?>
											<?php $_smarty_tpl->_assignInScope('_show_website', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'show_website'));?>
											<?php $_smarty_tpl->_assignInScope('_total_price_vat', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_vat'));
$_smarty_tpl->_assignInScope('_total_price_early', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_early'));?>
											<?php $_smarty_tpl->_assignInScope('_total_price_progress', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_progress'));?>
											<?php $_smarty_tpl->_assignInScope('_total_price_bank', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_bank'));?>
											<?php $_smarty_tpl->_assignInScope('_price_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_price_tts_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_tts_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_price_tttd_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_tttd_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_price_vay_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_vay_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_is_fund_type', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'is_fund_type'));?>
											<?php $_smarty_tpl->_assignInScope('_is_dq', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'is_dq'));?>
											<?php $_smarty_tpl->_assignInScope('index', $_smarty_tpl->tpl_vars['key']->value);?>
									
											<?php if (((!empty($_smarty_tpl->tpl_vars['cell_col_index']->value[$_smarty_tpl->tpl_vars['key']->value+1]) || (empty($_smarty_tpl->tpl_vars['cell_col_index']->value[$_smarty_tpl->tpl_vars['key']->value+1]) && !empty($_smarty_tpl->tpl_vars['check_merge']->value))))) {?>
												<?php if (!empty($_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'])) {?>
													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'], 'cell');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['cell']->value) {
?>
														<?php if (($_smarty_tpl->tpl_vars['cell']->value['start_cell'] == $_smarty_tpl->tpl_vars['key']->value+1)) {?>
															<?php $_smarty_tpl->_assignInScope('col_plus', $_smarty_tpl->tpl_vars['cell']->value['collspan']);?>
														<?php }?>
													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												<?php }?>
												<?php echo smarty_function_math(array('equation'=>"x+y",'x'=>$_smarty_tpl->tpl_vars['index']->value,'y'=>$_smarty_tpl->tpl_vars['col_plus']->value,'assign'=>"index"),$_smarty_tpl);?>

												<?php $_smarty_tpl->_assignInScope('check_merge', 1);?>
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'])) {?>	
												<?php if ($_smarty_tpl->tpl_vars['_stock_id']->value > '0') {?>
													<?php if ((!empty($_smarty_tpl->tpl_vars['_agency_id']->value) && ($_smarty_tpl->tpl_vars['list_agency_cached_VIN']->value[$_smarty_tpl->tpl_vars['_agency_id']->value] == '1' || $_smarty_tpl->tpl_vars['list_agency_cached_MAS']->value[$_smarty_tpl->tpl_vars['_agency_id']->value] == '1')) || ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_HIDDEN_ID') && !$_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('view_stock_hidden'))) {?>
														<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
														<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
													<?php } else { ?>
														<?php if (!empty($_smarty_tpl->tpl_vars['_status_id']->value) && $_smarty_tpl->tpl_vars['_status_id']->value > '0') {?>
															<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_NON_ID')) {?>
																<!-- Non -->xxx
															<?php } else { ?>
																<?php if ($_smarty_tpl->tpl_vars['is_hide_stock_cross']->value == '1' && $_smarty_tpl->tpl_vars['_agency_id']->value != @constant('_AGENCY_FH_ID')) {?>
																	<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
																<?php }?>
																<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID')) {?>
																	<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" 
																	class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
																<?php } else { ?>
																	<?php $_smarty_tpl->_assignInScope('_textcolor', $_smarty_tpl->tpl_vars['status_textcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value]);?>
																	<td floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" class="stock_cell stock_cell_<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
 text-center js__stock-cell-focus cursor-pointer position-relative" style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
"<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID') || $_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_NON_ID')) {
} else { ?> <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>onClick="$Core.helper.open_stock('<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
');" <?php } else { ?>data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
" data-toggle="webui-popover" data-trigger="click" data-placement="auto"<?php }?> data-width="350"<?php }?>><a href="javascript:void(0)" style="color:<?php echo $_smarty_tpl->tpl_vars['_textcolor']->value;?>
" class="stock_show <?php echo $_smarty_tpl->tpl_vars['_is_dq']->value;?>
" data-total_price_vat="<?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
" data-total_price_early="<?php echo $_smarty_tpl->tpl_vars['_total_price_early']->value;?>
" data-total_price_progress="<?php echo $_smarty_tpl->tpl_vars['_total_price_progress']->value;?>
" data-total_price_bank="<?php echo $_smarty_tpl->tpl_vars['_total_price_bank']->value;?>
" data-price_m2="<?php echo $_smarty_tpl->tpl_vars['_price_m2']->value;?>
" data-price_tts_m2="<?php echo $_smarty_tpl->tpl_vars['_price_tts_m2']->value;?>
" data-price_tttd_m2="<?php echo $_smarty_tpl->tpl_vars['_price_tttd_m2']->value;?>
" data-price_vay_m2="<?php echo $_smarty_tpl->tpl_vars['_price_vay_m2']->value;?>
" >
																		<?php if (!empty($_smarty_tpl->tpl_vars['_total_price_vat']->value)) {?>	
																			<?php if ($_smarty_tpl->tpl_vars['_is_fund_type']->value == 1) {?>
																				<span class="text_fund_type <?php if ($_smarty_tpl->tpl_vars['_is_dq']->value == 1) {?>classDQ<?php }?>" title="Thứ cấp"></span>
																			<?php }?>
																			<?php if ($_smarty_tpl->tpl_vars['clsStock']->value->checkShow($_smarty_tpl->tpl_vars['_show_website']->value,'MOC')) {?>
																				<span class="price_show"><?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
</span>
																			<?php } else { ?>
																				<span class="text-decoration-line-through price_show"><?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
</span>
																			<?php }?>
																			<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_stock_id']->value,$_smarty_tpl->tpl_vars['arr_stock_lock']->value)) {?>
																				<span class="lock_stock"><i class='bx bx-lock-alt' ></i></span>
																			<?php }?>
																		<?php } else { ?>
																			<!-- Empty -->
																		<?php }?>
																	</a></td>
																<?php }?>
															<?php }?>
														<?php } else { ?>
															<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
															<?php $_smarty_tpl->_assignInScope('_bgcolor', $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value]);?>
															<td floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" style="cursor:pointer; background:<?php echo $_smarty_tpl->tpl_vars['_bgcolor']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID')) {
} else { ?> data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
" data-toggle="webui-popover" data-trigger="click" data-width="350"<?php }?> class="text-center js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
														<?php }?>
													<?php }?>
												<?php } elseif (!empty($_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['index']->value])) {?>
													<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
													<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
												<?php }?>
											<?php }?>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>											
									<?php } elseif (!empty($_smarty_tpl->tpl_vars['arr_floor_merge_not_config']->value[$_smarty_tpl->tpl_vars['_floor']->value]) && $_smarty_tpl->tpl_vars['profile_id']->value == 289) {?>											
										<?php $_smarty_tpl->_assignInScope('check_merge', 0);?>
										<?php $_smarty_tpl->_assignInScope('col_plus', 0);?>
										<?php $_smarty_tpl->_assignInScope('floor_not_config', $_smarty_tpl->tpl_vars['arr_floor_merge_not_config']->value[$_smarty_tpl->tpl_vars['_floor']->value]);?>										
										<?php $_smarty_tpl->_assignInScope('_dfloor', $_smarty_tpl->tpl_vars['floor_merge_arrs']->value[$_smarty_tpl->tpl_vars['floor_not_config']->value]);?>																				
										<?php $_smarty_tpl->_assignInScope('cell_col_index', $_smarty_tpl->tpl_vars['_dfloor']->value['cell_col_index']);?>
										<?php if (!empty($_smarty_tpl->tpl_vars['_dfloor']->value['is_out_order'])) {?>
											<?php $_smarty_tpl->_assignInScope('is_out_order', 1);?>
											<?php $_smarty_tpl->_assignInScope('_arr_stock', $_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks']);?>
										<?php } else { ?>
											<?php $_smarty_tpl->_assignInScope('is_out_order', 0);?>
											<?php $_smarty_tpl->_assignInScope('_arr_stock', $_smarty_tpl->tpl_vars['arr_stocks']->value);?>
										<?php }?>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'], '_code', false, 'key', 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>													
											<?php $_smarty_tpl->_assignInScope('_stock_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'stock_id'));?>
											<?php $_smarty_tpl->_assignInScope('_agency_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'agency_id'));?>
											<?php $_smarty_tpl->_assignInScope('_status_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'status_id'));?>
											<?php $_smarty_tpl->_assignInScope('_show_website', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'show_website'));?>
											<?php $_smarty_tpl->_assignInScope('_total_price_vat', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_vat'));
$_smarty_tpl->_assignInScope('_total_price_early', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_early'));?>
											<?php $_smarty_tpl->_assignInScope('_total_price_progress', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_progress'));?>
											<?php $_smarty_tpl->_assignInScope('_total_price_bank', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_bank'));?>
											<?php $_smarty_tpl->_assignInScope('_price_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_price_tts_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_tts_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_price_tttd_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_tttd_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_price_vay_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_vay_m2'));?>
											<?php $_smarty_tpl->_assignInScope('_is_fund_type', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'is_fund_type'));?>
											<?php $_smarty_tpl->_assignInScope('_is_dq', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'is_dq'));?>
											<?php $_smarty_tpl->_assignInScope('index', $_smarty_tpl->tpl_vars['key']->value);?>
											<?php if (((!empty($_smarty_tpl->tpl_vars['cell_col_index']->value[$_smarty_tpl->tpl_vars['key']->value+1]) || (empty($_smarty_tpl->tpl_vars['cell_col_index']->value[$_smarty_tpl->tpl_vars['key']->value+1]) && !empty($_smarty_tpl->tpl_vars['check_merge']->value))))) {?>
												<?php if (!empty($_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'])) {?>
													<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_dfloor']->value['cell_merge_arrs'], 'cell');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['cell']->value) {
?>
														<?php if (($_smarty_tpl->tpl_vars['cell']->value['start_cell'] == $_smarty_tpl->tpl_vars['key']->value+1)) {?>
															<?php $_smarty_tpl->_assignInScope('col_plus', $_smarty_tpl->tpl_vars['cell']->value['collspan']);?>
														<?php }?>
													<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
												<?php }?>
												<?php echo smarty_function_math(array('equation'=>"x+y",'x'=>$_smarty_tpl->tpl_vars['index']->value,'y'=>$_smarty_tpl->tpl_vars['col_plus']->value,'assign'=>"index"),$_smarty_tpl);?>

												<?php $_smarty_tpl->_assignInScope('check_merge', 1);?>
											<?php }?>
											<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['_dfloor']->value['arr_stocks'])) {?>
												<?php if ($_smarty_tpl->tpl_vars['_stock_id']->value > '0') {?>
													<?php if ((!empty($_smarty_tpl->tpl_vars['_agency_id']->value) && ($_smarty_tpl->tpl_vars['list_agency_cached_VIN']->value[$_smarty_tpl->tpl_vars['_agency_id']->value] == '1' || $_smarty_tpl->tpl_vars['list_agency_cached_MAS']->value[$_smarty_tpl->tpl_vars['_agency_id']->value] == '1')) || ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_HIDDEN_ID') && !$_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('view_stock_hidden'))) {?>
														<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
														<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
													<?php } else { ?>
														<?php if (!empty($_smarty_tpl->tpl_vars['_status_id']->value) && $_smarty_tpl->tpl_vars['_status_id']->value > '0') {?>
															<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_NON_ID')) {?>
																<!-- Non -->
															<?php } else { ?>
																<?php if ($_smarty_tpl->tpl_vars['is_hide_stock_cross']->value == '1' && $_smarty_tpl->tpl_vars['_agency_id']->value != @constant('_AGENCY_FH_ID')) {?>
																	<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
																<?php }?>
																<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID')) {?>
																	<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" 
																	class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
																<?php } else { ?>
																<?php $_smarty_tpl->_assignInScope('_textcolor', $_smarty_tpl->tpl_vars['status_textcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value]);?>
																<td floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" class="stock_cell stock_cell_<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
 text-center js__stock-cell-focus cursor-pointer position-relative" style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
"<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID') || $_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_NON_ID')) {
} else { ?> <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>onClick="$Core.helper.open_stock('<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
');" <?php } else { ?>data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
" data-toggle="webui-popover" data-trigger="click" data-placement="auto"<?php }?> data-width="350"<?php }?>><a href="javascript:void(0)" style="color:<?php echo $_smarty_tpl->tpl_vars['_textcolor']->value;?>
" class="stock_show <?php echo $_smarty_tpl->tpl_vars['_is_dq']->value;?>
" data-total_price_vat="<?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
" data-total_price_early="<?php echo $_smarty_tpl->tpl_vars['_total_price_early']->value;?>
" data-total_price_progress="<?php echo $_smarty_tpl->tpl_vars['_total_price_progress']->value;?>
" data-total_price_bank="<?php echo $_smarty_tpl->tpl_vars['_total_price_bank']->value;?>
" data-price_m2="<?php echo $_smarty_tpl->tpl_vars['_price_m2']->value;?>
" data-price_tts_m2="<?php echo $_smarty_tpl->tpl_vars['_price_tts_m2']->value;?>
" data-price_tttd_m2="<?php echo $_smarty_tpl->tpl_vars['_price_tttd_m2']->value;?>
" data-price_vay_m2="<?php echo $_smarty_tpl->tpl_vars['_price_vay_m2']->value;?>
" >
																	<?php if (!empty($_smarty_tpl->tpl_vars['_total_price_vat']->value)) {?>	
																		<?php if ($_smarty_tpl->tpl_vars['_is_fund_type']->value == 1) {?>
																			<span class="text_fund_type <?php if ($_smarty_tpl->tpl_vars['_is_dq']->value == 1) {?>classDQ<?php }?>" title="Thứ cấp"></span>
																		<?php }?>
																		<?php if ($_smarty_tpl->tpl_vars['clsStock']->value->checkShow($_smarty_tpl->tpl_vars['_show_website']->value,'MOC')) {?>
																			<span class="price_show"><?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
</span>
																		<?php } else { ?>
																			<span class="text-decoration-line-through price_show"><?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
</span>
																		<?php }?>
																		<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_stock_id']->value,$_smarty_tpl->tpl_vars['arr_stock_lock']->value)) {?>
																			<span class="lock_stock"><i class='bx bx-lock-alt' ></i></span>
																		<?php }?>
																	<?php } else { ?>
																		<!-- Empty -->
																	<?php }?>
																</a></td>
																<?php }?>
															<?php }?>
														<?php } else { ?>
															<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
															<?php $_smarty_tpl->_assignInScope('_bgcolor', $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value]);?>
															<td floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" style="cursor:pointer; background:<?php echo $_smarty_tpl->tpl_vars['_bgcolor']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID')) {
} else { ?> data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
" data-toggle="webui-popover" data-trigger="click" data-width="350"<?php }?> class="text-center js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
														<?php }?>
													<?php }?>
												<?php } elseif (!empty($_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['index']->value])) {?>
													<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
													<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php if (!empty($_smarty_tpl->tpl_vars['is_out_order']->value)) {
echo $_smarty_tpl->tpl_vars['arr_stocks']->value[$_smarty_tpl->tpl_vars['key']->value];
} else {
echo $_smarty_tpl->tpl_vars['_code']->value;
}?>" floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
												<?php }?>
											<?php }?>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>											
									<?php } else { ?>
										<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_stocks']->value, '_code', false, NULL, 'k', array (
  'iteration' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_code']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']++;
?>
										<?php $_smarty_tpl->_assignInScope('_stock_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'stock_id'));?>
										<?php $_smarty_tpl->_assignInScope('_agency_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'agency_id'));?>
										<?php $_smarty_tpl->_assignInScope('_status_id', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'status_id'));?>
										<?php $_smarty_tpl->_assignInScope('_show_website', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'show_website'));?>
										<?php $_smarty_tpl->_assignInScope('_total_price_vat', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_vat'));?>
										<?php $_smarty_tpl->_assignInScope('_total_price_early', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_early'));?>
										<?php $_smarty_tpl->_assignInScope('_total_price_progress', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_progress'));?>
										<?php $_smarty_tpl->_assignInScope('_total_price_bank', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'total_price_bank'));?>
										<?php $_smarty_tpl->_assignInScope('_price_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_m2'));?>
										<?php $_smarty_tpl->_assignInScope('_price_tts_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_tts_m2'));?>
										<?php $_smarty_tpl->_assignInScope('_price_tttd_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_tttd_m2'));?>
										<?php $_smarty_tpl->_assignInScope('_price_vay_m2', $_smarty_tpl->tpl_vars['clsProject']->value->getPriceFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'price_vay_m2'));?>
										<?php $_smarty_tpl->_assignInScope('_is_fund_type', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'is_fund_type'));?>
										<?php $_smarty_tpl->_assignInScope('_is_dq', $_smarty_tpl->tpl_vars['clsProject']->value->getFieldValue($_smarty_tpl->tpl_vars['_floor']->value,$_smarty_tpl->tpl_vars['_code']->value,$_smarty_tpl->tpl_vars['arr_cells']->value,'is_dq'));?>
										<?php if (!$_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray((isset($_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_k']->value['iteration'] : null),$_smarty_tpl->tpl_vars['config_floor']->value[$_smarty_tpl->tpl_vars['_floor']->value])) {?>
											<?php if ($_smarty_tpl->tpl_vars['_stock_id']->value > '0') {?>
												<?php if ((!empty($_smarty_tpl->tpl_vars['_agency_id']->value) && ($_smarty_tpl->tpl_vars['list_agency_cached_VIN']->value[$_smarty_tpl->tpl_vars['_agency_id']->value] == '1' || $_smarty_tpl->tpl_vars['list_agency_cached_MAS']->value[$_smarty_tpl->tpl_vars['_agency_id']->value] == '1')) || ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_HIDDEN_ID') && !$_smarty_tpl->tpl_vars['clsISO']->value->checkPermission('view_stock_hidden'))) {?>
													<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
													<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" 
													class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
												<?php } else { ?>
													<?php if (!empty($_smarty_tpl->tpl_vars['_status_id']->value) && $_smarty_tpl->tpl_vars['_status_id']->value > '0') {?>
														<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_NON_ID')) {?>
															<!-- Non -->
														<?php } else { ?>
															<?php if ($_smarty_tpl->tpl_vars['is_hide_stock_cross']->value == '1' && $_smarty_tpl->tpl_vars['_agency_id']->value != @constant('_AGENCY_FH_ID')) {?>
																<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
															<?php }?>
															<?php $_smarty_tpl->_assignInScope('_textcolor', $_smarty_tpl->tpl_vars['status_textcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value]);?>
															<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID')) {?>
																<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" 
																class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
															<?php } else { ?>
															<td floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" class="text-center js__stock-cell-focus cursor-pointer position-relative" style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
"<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID') || $_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_NON_ID')) {
} else { ?> <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>onClick="$Core.helper.open_stock('<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
');" <?php } else { ?>data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
" data-toggle="webui-popover" data-trigger="click" data-placement="auto"<?php }?> data-width="350"<?php }?>><a href="javascript:void(0)" style="color:<?php echo $_smarty_tpl->tpl_vars['_textcolor']->value;?>
" class="stock_show <?php echo $_smarty_tpl->tpl_vars['_is_dq']->value;?>
" data-total_price_vat="<?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
" data-total_price_early="<?php echo $_smarty_tpl->tpl_vars['_total_price_early']->value;?>
" data-total_price_progress="<?php echo $_smarty_tpl->tpl_vars['_total_price_progress']->value;?>
" data-total_price_bank="<?php echo $_smarty_tpl->tpl_vars['_total_price_bank']->value;?>
" data-price_m2="<?php echo $_smarty_tpl->tpl_vars['_price_m2']->value;?>
" data-price_tts_m2="<?php echo $_smarty_tpl->tpl_vars['_price_tts_m2']->value;?>
" data-price_tttd_m2="<?php echo $_smarty_tpl->tpl_vars['_price_tttd_m2']->value;?>
" data-price_vay_m2="<?php echo $_smarty_tpl->tpl_vars['_price_vay_m2']->value;?>
" >
																<?php if (!empty($_smarty_tpl->tpl_vars['_total_price_vat']->value)) {?>													
																	<?php if ($_smarty_tpl->tpl_vars['_is_fund_type']->value == 1) {?>
																		<span class="text_fund_type <?php if ($_smarty_tpl->tpl_vars['_is_dq']->value == 1) {?>classDQ<?php }?>" title="Thứ cấp"></span>
																	<?php }?>
																	<?php if ($_smarty_tpl->tpl_vars['clsStock']->value->checkShow($_smarty_tpl->tpl_vars['_show_website']->value,'MOC')) {?>
																		<span class="price_show"><?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
</span>
																	<?php } else { ?>
																		<span class="text-decoration-line-through price_show"><?php echo $_smarty_tpl->tpl_vars['_total_price_vat']->value;?>
</span>
																	<?php }?>
																	<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->checkItemInArray($_smarty_tpl->tpl_vars['_stock_id']->value,$_smarty_tpl->tpl_vars['arr_stock_lock']->value)) {?>
																		<span class="lock_stock"><i class='bx bx-lock-alt' ></i></span>
																	<?php }?>
																<?php } else { ?>
																	<!-- Empty -->
																<?php }?>
															</a></td>
															<?php }?>
														<?php }?>
													<?php } else { ?>
														<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
														<?php $_smarty_tpl->_assignInScope('_bgcolor', $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value]);?>
														<td floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" style="cursor:pointer; background:<?php echo $_smarty_tpl->tpl_vars['_bgcolor']->value;?>
"<?php if ($_smarty_tpl->tpl_vars['_status_id']->value == @constant('_STOCK_STATUS_SOLD_ID')) {
} else { ?> data-url="/index.php?mod=<?php echo $_smarty_tpl->tpl_vars['mod']->value;?>
&sub=project&act=load_stock_popover&stock_id=<?php echo $_smarty_tpl->tpl_vars['_stock_id']->value;?>
" data-toggle="webui-popover" data-trigger="click" data-width="350"<?php }?> class="text-center js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
													<?php }?>
												<?php }?>
											<?php } else { ?>
												<?php $_smarty_tpl->_assignInScope('_status_id', @constant('_STOCK_STATUS_SOLD_ID'));?>
												<td style="background:<?php echo $_smarty_tpl->tpl_vars['status_bgcolor_arrs']->value[$_smarty_tpl->tpl_vars['_status_id']->value];?>
;" code="<?php echo $_smarty_tpl->tpl_vars['_code']->value;?>
" 
													floor="<?php echo $_smarty_tpl->tpl_vars['_floor']->value;?>
" class="js__stock-cell-focus bg_sold <?php echo $_smarty_tpl->tpl_vars['stock_bg_sold']->value;?>
"></td>
											<?php }?>
										<?php }?>
										<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>	
									<?php }?>
								</tr>
								<?php }?>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tbody>
					</table>
				</div>
			<?php } else { ?>
			<div class="d-flex justify-content-center mb-2">
				<div class="btn-group">
					<a href="javascript:void(0);" class="btn flex-fill btn-primary">
						<i class="bx bx-table"></i> 
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>Xem dạng bảng<?php } else { ?>Bảng<?php }?>
					</a>
					<?php if ($_smarty_tpl->tpl_vars['is_project_block']->value == '1') {?>
					<a href="<?php echo $_smarty_tpl->tpl_vars['clsProperty']->value->getLink('stock_layout',array('project_id'=>$_smarty_tpl->tpl_vars['project_id']->value,'block_id'=>$_smarty_tpl->tpl_vars['block_id']->value));?>
" class="btn flex-fill btn-outline-default">
						<i class="bx bx-map-pin"></i> 
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>Xem dạng mặt bằng<?php } else { ?>Mặt bằng<?php }?>
					</a>
					<?php } else { ?>
					<a href="<?php echo $_smarty_tpl->tpl_vars['PCMS_URL']->value;?>
/project/p<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
/layout.html" class="btn flex-fill btn-outline-default">
						<i class="bx bx-map-pin"></i> 
						<?php if ($_smarty_tpl->tpl_vars['deviceType']->value != 'phone') {?>Xem dạng mặt bằng<?php } else { ?>Mặt bằng<?php }?>
					</a>
					<?php }?>
				</div>
			</div>
			<hr />
			<form method="POST">
				<?php echo $_smarty_tpl->tpl_vars['core']->value->getBlock('stock_search',array('project_id'=>$_smarty_tpl->tpl_vars['project_id']->value,'block_id'=>$_smarty_tpl->tpl_vars['block_id']->value,'is_project_block'=>$_smarty_tpl->tpl_vars['is_project_block']->value));?>

			</form>
			<hr />
			<?php if ($_smarty_tpl->tpl_vars['clsISO']->value->_DEV() || 1 == 1) {?>
				<div class="clearfix"></div>
				<div class="tableStock table-container no-shadow mt-3 freeze-table text-nowrap">
					<table class="table no-bootstrap dragable table-grid table-bordered nohover" 
						width="100%" cellpadding="0" cellspacing="0">
						<thead><tr>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_field']->value, '_label', false, '_field', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_field']->value => $_smarty_tpl->tpl_vars['_label']->value) {
?>
								<th class="align-center text-upper h-px-30 text-center" width="100px"><?php echo $_smarty_tpl->tpl_vars['_label']->value;?>
</th>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						</tr></thead>
						<tbody class="holder_stock_<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
">
							<?php
$__section_i_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_0_total = min(($__section_i_0_loop - 0), 30);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_0_total !== 0) {
for ($__section_i_0_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_0_iteration <= $__section_i_0_total; $__section_i_0_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
							<tr>
								<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_field']->value, '_label', false, '_field', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_field']->value => $_smarty_tpl->tpl_vars['_label']->value) {
?>
								<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
								<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
							</tr>
							<?php
}
}
?>
						</tbody>
					</table>
				</div>
				<div class="d-flex justify-content-between py-2 text-center">
					<button type="button" class="showmorethisresult d-none" title="Xem thêm" 
						onClick="$Core.stock.load_more(this, event)" page="2"> 
						<span>Xem thêm</span> 
						<i class="bx bx-chevrons-down translate-px-2"></i>
					</button> 
				</div>
				
				<?php echo '<script'; ?>
 type="text/javascript">
					$(function(){ 
						setTimeout(() => {
							$Core.stock.load_stock(project_id, {
								'block_id':block_id,
								'is_project_block':is_project_block
							}); 
						}, 500);
					});
				<?php echo '</script'; ?>
>
				
			<?php } else { ?>
			<div class="clearfix"></div>
			<div class="tableStock table-container no-shadow mt-3 freeze-table text-nowrap">
				<table class="table no-bootstrap dragable table-grid table-bordered nohover" 
					width="100%" cellpadding="0" cellspacing="0">
					<thead><tr>
						<th class="align-center text-upper h-px-30 text-center" width="100px">Mã căn</th>
						<th class="align-center text-upper h-px-30 text-center" width="100px">Phân khu</th>
						<th class="align-center text-upper h-px-30 text-left">Dãy</th>
						<th class="align-center text-upper h-px-30 text-left" width="80px">Loại hình</th>
						<th class="align-center text-upper h-px-30 text-center" width="80px">DT Đất</th>
						<th class="align-center text-upper h-px-30 text-center" width="80px">DT XD</th>
						<th class="align-center text-upper h-px-30 text-left" width="150px">TCBG</th>
						<th class="align-center text-upper h-px-30 text-center">Hướng</th>
						<th class="align-center text-upper h-px-30 text-left" width="120px">Giá VAT&KPBT</th>
						<!-- <th class="align-center text-upper text-center">Giá Vay 24T</th>
						<th class="align-center text-upper text-center">Giá Vay 36T</th>
						<th class="align-center text-upper text-center">Giá TTTĐ</th>
						<th class="align-center text-upper text-center">Giá TTS</th> -->
						<th class="align-center text-upper h-px-30 text-left">CSBH</th>
						<th class="align-center text-upper h-px-30 text-left">Ngày ký cọc</th>
						<!--<th class="align-center text-upper text-center">Chính sách</th>-->
						<th class="align-center text-upper h-px-30 text-center">PTG</th>
						<th class="align-center text-upper h-px-30 text-center">Loại hình ký</th>
						<th class="align-center text-upper h-px-30 text-left">Quỹ đầu tư</th>
						<th class="align-center text-upper h-px-30 text-left">Giỏ Bank</th>
						<th class="align-center text-upper h-px-30 text-left">Ghi chú</th>
						<!-- <th class="align-center">Ngày ký cọc</th> -->
						<!-- <th class="align-center text-center">CT ký HĐ</th>
						<th class="align-center text-center">Giỏ Bank TC</th>
						<th class="align-center">Tình trạng</th>
						<th class="align-center text-center">TT bán</th>
						<th class="align-center text-center">ĐL lock</th>
						<th class="align-center text-center">ĐL cọc</th> -->
					</tr></thead>
					<tbody class="holder_stock_<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
">
						<?php
$__section_i_1_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['list_preloaders']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_i_1_total = min(($__section_i_1_loop - 0), 30);
$_smarty_tpl->tpl_vars['__smarty_section_i'] = new Smarty_Variable(array());
if ($__section_i_1_total !== 0) {
for ($__section_i_1_iteration = 1, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index'] = 0; $__section_i_1_iteration <= $__section_i_1_total; $__section_i_1_iteration++, $_smarty_tpl->tpl_vars['__smarty_section_i']->value['index']++){
?>
						<tr>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
							<td><div class="animate-bg w-100 h-px-20 rounded-2"></div></td>
						</tr>
						<?php
}
}
?>
					</tbody>
				</table>
			</div>
			<div class="d-flex justify-content-between py-2 text-center">
				<button type="button" class="showmorethisresult d-none" title="Xem thêm" 
					onClick="$Core.stock.load_more(this, event)" page="2"> 
					<span>Xem thêm</span> 
					<i class="bx bx-chevrons-down translate-px-2"></i>
				</button> 
			</div>
			
			<?php echo '<script'; ?>
 type="text/javascript">
				$(function(){ 
					setTimeout(() => {
						$Core.stock.load_stock(project_id, {
							'block_id':block_id,
							'is_project_block':is_project_block
						}); 
					}, 500);
				});
			<?php echo '</script'; ?>
>
			
			<?php }?>
			<?php }?>
			<!-- End bảng hàng -->
			<div class="py-2 d-flex flex-wrap gap-2 align-items-center justify-content-center">
				<?php if (!empty($_smarty_tpl->tpl_vars['vr_link']->value)) {?>
				<a class="btn btn-outline-default text-main fw-bold<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> flex-fill<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['vr_link']->value;?>
" data-fancybox data-type="iframe" data-caption="<?php if (!empty($_smarty_tpl->tpl_vars['vr_source']->value)) {
echo $_smarty_tpl->tpl_vars['vr_source']->value;
} else { ?>Bản quyền thuộc về masterihomes.com<?php }?>"><i class="material-icons-outlined text-main">360</i> Xem sa bàn ảo 360<sup>o</sup></a>
				<?php }?>
				<?php if (!empty($_smarty_tpl->tpl_vars['onePolicy']->value)) {?>
					<a href="<?php echo $_smarty_tpl->tpl_vars['onePolicy']->value['link_ns'];?>
" class="btn btn-outline-default<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> flex-fill<?php }?>" target="_blank"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-check-shield','Chính sách bán hàng');?>
 <?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
</a> 
					<a href="<?php echo $_smarty_tpl->tpl_vars['onePolicy']->value['link_ms'];?>
" class="btn btn-outline-default<?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?> flex-fill<?php }?>" target="_blank"><?php echo $_smarty_tpl->tpl_vars['clsISO']->value->makeIcon('bx-spreadsheet','Phiếu tính giá');?>
 <?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['title'];?>
</a>
				<?php }?>
			</div>
		</div>
		<?php if (!empty($_smarty_tpl->tpl_vars['oneBuilding']->value['intro'])) {?>
		<div class="p-4 mt-4 bg-lighter rounded-2">
			<div class="tinyContent js__readmore-content">
				<?php echo $_smarty_tpl->tpl_vars['oneBuilding']->value['intro'];?>

			</div>
		</div>
		<?php }?>
	</div>
</div>

<style type="text/css">
	.freeze-table{
		user-select:none;
		-moz-user-select:none;
		-khtml-user-select:none;
		-webkit-user-select:none;
		-o-user-select:none;
		overflow-x:auto;
		-webkit-overflow-scrolling:touch
	}
	.table-stock .cell{
		position:relative;
		background:#287e3f!important;
		color:rgba(255,255,255,1)!important
	}
	.table-container .table thead tr th.cell:not(.no-sticky):first-child, .table-container .table tfoot tr td:not(.no-sticky):first-child, .table-container .table tfoot tr th:not(.no-sticky):first-child {
		background:#287e3f!important;
		color:rgba(255,255,255,1)!important
	}
	.table-container:not(.no-shadow) .table thead tr th:not(.cell):not(.no-sticky):nth-child(1) {
		background: #FFF !important;
		box-shadow: 0 0;
	}
	.table-tooltip th,.table-tooltip td{
		padding:.325rem .625rem
	}
	.table-grid td{
		padding: 0.325rem 0.325rem
	}
	.table-grid th{
		line-height:16px; 
		background:rgb(245 247 248);
		text-overflow: inherit
	}
	.table-stock th{
		text-overflow: inherit
	}
	.dropdown_price .dropdown-item:not(.active) {
		opacity: 0.8;
	}
	@media screen and (min-width:1400px) {
/*		.table-stock{ table-layout:fixed;}*/
		.table-stock th,
		.table-stock td{
			font-size:10px;
			padding:.125rem .325rem; 
			height:16px; 
			line-height:16px;
		}
		.table-grid th{
			padding:0.625rem 0.325rem
		}
	}
	@media screen and (max-width:1400px) {
		.table-stock th,
		.table-stock td{font-size:8px; padding:.125rem .2rem}
	}
	@media screen and (max-width:575px) {
		.om-xs\:mb-1{ margin-bottom:10px;}
	}
	.box_tool_tip {
		font-weight: bold;
		font-size: 11px;
		color:var(--bs-white);
		background: #FFF0;
		border: none !important;
		box-shadow: none !important;
		border-radius:10px 0 10px 0;
	}
	.box_tool_tip:before,
	.box_tool_tip .leaflet-tooltip-arrow {
		/* display: none;*/
	}
	.item_tooltip {
		width: 130px;
		text-align: center;
		background: #eadcc1;
		border: 1px solid #eadcc1;
		border-radius: 10px;
		overflow: hidden;
	}
	.item_tooltip .box_code {
		padding: 3px 5px;
		font-weight: bold;
		font-size: 16px;
		background: #966334;
		color: #f5fac0;
		text-shadow: 2px 1px black;
	}
	.item_tooltip .body_tooltip {
		padding: 5px;
	}
	.item_tooltip .box_text {
		font-size: 9px;
		margin-bottom: 3px;
		color: #342828;
	}
	.item_tooltip .text-value {
		font-weight: 700;
		font-size: 11px;
	}
	.item_tooltip .text-price {
		padding: 3px;
		font-size: 14px;
		background: #966334;
		color: #f5fac0;
		text-shadow: 2px 1px #51341b;
		margin-top: 3px;
		border-radius: 5px;
	}
</style>
<?php }
}
