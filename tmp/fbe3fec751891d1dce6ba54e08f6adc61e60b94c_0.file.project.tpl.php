<?php
/* Smarty version 3.1.33, created on 2026-08-06 18:29:22
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/map/project.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a747012965b22_30551139',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'fbe3fec751891d1dce6ba54e08f6adc61e60b94c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/views/map/project.tpl',
      1 => 1784299660,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a747012965b22_30551139 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" type="text/css" href="<?php echo URL_CSS;?>
/leaflet/leaflet.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
<?php if ($_smarty_tpl->tpl_vars['mod']->value == 'home' && $_smarty_tpl->tpl_vars['show']->value == 'map') {?>
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/map.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
<?php }?>
<!--<link rel="stylesheet" href="<?php echo URL_CSS;?>
/leaflet/leaflet.draw.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"/>-->
<?php echo '<script'; ?>
 src="<?php echo URL_JS;?>
/leaflet/leaflet.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<!--<?php echo '<script'; ?>
 src="<?php echo URL_JS;?>
/leaflet/leaflet.draw.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>-->
<!--<?php echo '<script'; ?>
 src="<?php echo URL_JS;?>
/leaflet/Path.Drag.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
> -->
<?php echo '<script'; ?>
 src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCBckOSR54eG9pSvVGDniTY8k93RdbK9QQ&libraries=places"><?php echo '</script'; ?>
>
<link rel="stylesheet" href="<?php echo URL_CSS;?>
/leaflet/leaflet-routing-machine.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
" />
<?php echo '<script'; ?>
 src="<?php echo URL_JS;?>
/leaflet/leaflet-routing-machine.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>
<div class="project-map-detail">
    <div class="box-menu-location d-flex align-items-center flex-wrap <?php if ($_smarty_tpl->tpl_vars['deviceType']->value == 'phone') {?>justify-content-center<?php } else { ?>justify-content-between<?php }?>">
		<ul class="menu-location d-flex ps-0">
			<?php if (!empty($_smarty_tpl->tpl_vars['info_locations']->value) && !empty($_smarty_tpl->tpl_vars['project_id']->value) && empty($_smarty_tpl->tpl_vars['block_id']->value) && empty($_smarty_tpl->tpl_vars['building_id']->value)) {?>
				<li class="item-menu mt-2">
				<button class="btn btn-action-map btn-block" data-type='block'>
					<i class='bx bx-area'  ></i> 
					Phân khu
				</button>
				</li>
			<?php }?>
			<li class="item-menu mt-2">
			<button class="btn btn-action-map btn-highlight" data-type='highlight'>
				<i class='bx  bx-star'  ></i> 
				Nổi bật
			</button>
			</li>
			<li class="item-menu mt-2">
			<button class="btn btn-action-map" data-type='hospital'>
				<i class='bx  bx-pulse'  ></i> 
				Y tế
			</button>
			</li>
			<li class="item-menu mt-2">
			<button class="btn btn-action-map" data-type='market'>
				<i class='bx bx-cart'></i> 
				Mua sắm
			</button>
			</li>
			<li class="item-menu mt-2">
			<button class="btn btn-action-map" data-type='university'>
				<i class='bx bxs-graduation'></i> 
				Trường học
			</button>
			</li>
			<li class="item-menu mt-2">
			<button class="btn btn-action-map" data-type='airport'>
				<i class='bx bxs-plane-alt'></i> 
				Sân bay
			</button>
			</li>
			<li class="item-menu mt-2">
			<button class="btn btn-action-map" data-type='entertainment'>
				<i class='bx  bx-party'  ></i> 
				Giải trí
			</button>
			</li>
		</ul>
		<form>
			<div class="group-search-location input-group d-flex" style="width: 350px;">
				<input type="text" name="location_keyword" value="" placeholder="Nhập địa điểm bạn muốn tìm ..." class="form-control location-keyword js-location-keyword">
				<button class="btn btn-search-location" type="submit" onClick="$Core.projectMap.submitSearchLocation(this, event);" data-radius="15"><i class='bx bx-search'></i></button>
			</div>
		</form>
    </div>
	<div class="mt-3 box-map map_block d-none">
		<div class="position-relative w-100 h-100">
			<div class="position-relative min-height-500 zindex-1" id="map" 
				data-location="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['location']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" data-map-zoom="<?php echo $_smarty_tpl->tpl_vars['map_zoom']->value;?>
"></div>
		</div>
		<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['info_locations']->value, '_oBlock');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oBlock']->value) {
?>
		<div class="box-info-project card w-px-250 h-100 no-shadow project-<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['block_id'];?>
 d-none">
			<a class="d-block" href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['_oBlock']->value['block_id'],0,'overview',$_smarty_tpl->tpl_vars['_oBlock']->value);?>
" title="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
">
				<div class="position-relative text-white mb-3">
					<span class="position-absolute top-px-20 right-px-20 bg-success rounded-pill py-1 px-3 fs-12">Đang mở bán</span>
					<img class="card-img-top img-project img-fluid" src="<?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['image'];?>
" style="width: 100%; width: 100%; height: 177px;">
				</div>
				<div class="d-flex align-items-center justify-content-between">
					<div class="awe__project-info w-100">
						<h4 class="card-title mb-2">
							<span class="text-dark fw-semibold"><?php echo $_smarty_tpl->tpl_vars['_oBlock']->value['title'];?>
</span>
						</h4>
						<?php if (!empty($_smarty_tpl->tpl_vars['_oBlock']->value['attrs'])) {?>
							<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['_oBlock']->value['attrs'], '_oItem', false, 'key', 'i', array (
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['_oItem']->value) {
?>
								<div class="mb-1 text-muted">
									<span class="text-nowrap"><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['title'];?>
:</span> <strong><?php echo $_smarty_tpl->tpl_vars['_oItem']->value['content'];?>
</strong>
								</div>
							<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
						<?php }?>
						<div class="d-flex group-button-detail mt-2 align-items-center">
							<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkDetail($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['_oBlock']->value['block_id'],0,'overview',$_smarty_tpl->tpl_vars['_oProject']->value);?>
" class="btn flex-fill text-white btn-info btn-sm d-flex justify-content-between align-items-center me-2">
								Thông tin<i class='bx bx-right-arrow-alt'></i></a>
							<a href="<?php echo $_smarty_tpl->tpl_vars['clsProject']->value->getLinkInfo($_smarty_tpl->tpl_vars['project_id']->value,$_smarty_tpl->tpl_vars['_oBlock']->value['block_id'],0,'_map');?>
" class="btn flex-fill btn-outline-info btn-sm d-flex justify-content-between align-items-center">
								Chi tiết<i class='bx bx-right-arrow-alt'></i></a>
						</div>
					</div>
				</div>
			</a>
		</div>
		<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	</div>
    <div class="form-row mt-2 map_location">
		<div class="col-12 col-md-3 box-router">
			<div class="box-location">
			<div class="title-location">
				<h3 class="text-center">Địa điểm</h3>
			</div>
			<ul class="router d-flex">
			</ul> 
			</div>
		</div> 
		<div class="col-12 col-md-9 box-map">
			<div style="position: relative; width: 100%; height: 100%;">
				<div id="map-detail" data-id="<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
"></div>
			</div>
			<div class='tooltip'>Trở về vị trí trung tâm </div>
			</button>
			<div class="box-info-project card h-100 no-shadow project-<?php echo $_smarty_tpl->tpl_vars['project']->value['project_id'];?>
 d-none">
			<a class="d-block" href="<?php echo $_smarty_tpl->tpl_vars['project']->value['link'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['project']->value['title'];?>
">
				<div class="position-relative text-white">
				<span class="position-absolute top-px-20 right-px-20 bg-success rounded-pill py-1 px-3 fs-12">Đang mở bán</span>
				<img decoding="async" class="card-img-top img-project img-fluid" src="<?php echo $_smarty_tpl->tpl_vars['project']->value['image'];?>
">
				</div>
				<div class="card-body">
				<div class="d-flex align-items-center justify-content-between">
					<div class="awe__project-info">
					<h4 class="card-title mb-2">
						<span class="text-dark fw-semibold"><?php echo $_smarty_tpl->tpl_vars['project']->value['title'];?>
</span>
					</h4>
					<p class="text-dark mb-1">
						<i class='bx bx-map'></i> <?php echo $_smarty_tpl->tpl_vars['project']->value['infomation_ex']['address'];?>

					</p>
					<div class="d-flex gap-1 mb-1 align-items-center text-muted">
						<i class='bx bx-home-alt'></i> Quy mô: <?php echo $_smarty_tpl->tpl_vars['project']->value['apartment'];?>

					</div>
					<div class="d-flex gap-1 align-items-center text-muted">
						<i class='bx bx-code'></i> Diện tích: <?php echo $_smarty_tpl->tpl_vars['project']->value['arcreage'];?>

					</div>
					</div>
					<div class="awe__project-icon d-none d-lg-block">
					<img class="img-fluid h-px-50" src="<?php echo $_smarty_tpl->tpl_vars['project']->value['logo'];?>
" />
					</div>
				</div>
				</div>
			</a>
			</div>
		</div>
    
    </div>
</div>
<div id="google-helper-map" style="display: none;"></div>
<?php echo '<script'; ?>
>
	var $location_block = `<?php echo $_smarty_tpl->tpl_vars['location_block']->value;?>
`;
	var $location = `<?php echo $_smarty_tpl->tpl_vars['location']->value;?>
`;
	var $map_zoom = `17`;
<?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>
	$(function() {
		$Core.projectMap.init({
			locationInfo: <?php echo $_smarty_tpl->tpl_vars['location']->value;?>
,
			locationHot: <?php echo $_smarty_tpl->tpl_vars['locations']->value;?>
,
			mapZoom: <?php echo $_smarty_tpl->tpl_vars['map_zoom']->value;?>

		});
		var $overlay = $('#mapCtrlOverlay');
		var $mapDiv = $('#map-detail');
		if ($mapDiv.length && $overlay.length) {
			$mapDiv.on('wheel', function(e) {
			if (e.originalEvent.ctrlKey) {
				$overlay.css('display', 'none');
			} else {
				$overlay.css('display', 'flex');
			}
			});
		}
	});
<?php echo '</script'; ?>
>
<?php }
}
