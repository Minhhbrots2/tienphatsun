<?php
/* Smarty version 3.1.33, created on 2026-07-30 17:03:35
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/project_map/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a6b2177a56009_23146307',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '64b35b80e4c8c90f06d666b67d5209eec26bd38c' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/project_map/index.tpl',
      1 => 1784299557,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a6b2177a56009_23146307 (Smarty_Internal_Template $_smarty_tpl) {
if ($_smarty_tpl->tpl_vars['more_information']->value['is_tiles'] == '1') {?>

	<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/leaflet/leaflet.min.css"/>

	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['URL_JS']->value;?>
/leaflet/leaflet.min.js?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
"><?php echo '</script'; ?>
>

	<div id="map_canvas" class="map_canvas"></div>

	<?php echo $_smarty_tpl->tpl_vars['scriptJs']->value;?>


	

	<style type="text/css">

		.map_canvas{

			width:100%;

			height:800px;

			border-radius:3px;

			-moz-border-radius:3px;

			-webkit-border-radius:3px;

			overflow:hidden;

		}

		.leaflet-container{

			background:var(--bs-white) !important;

		}

		@media screen and (max-width:575px){

			.map_canvas{

				height:400px;

			}

		}

	</style>

	<?php echo '<script'; ?>
 type="text/javascript">

		$(() => {

			var map = L.map('map_canvas', {

				minZoom: 1,

				maxZoom: map_configs.max_zoom,

				center: map_configs.center_point,

				zoom:3

			});

			L.tileLayer(map_configs.tiles, {

				noWrap: true,

				tileSize: 256,

				bounds: map_configs.max_bounds,

				attribution: '<a href="'+PCMS_URL+'">© Sky Realty</a>',

				maxZoom: map_configs.max_zoom,

				tms: map_configs.tms_enable,

			}).addTo(map);

		});

	<?php echo '</script'; ?>
>

	

<?php } else { ?>

<div class="img-container rounded-1 relative overflow-hidden">

	<div id="panzoom-container" class="d-block panzoom-container">

		<img class="img-fluid w-100" src="<?php echo $_smarty_tpl->tpl_vars['clsISO']->value->getGoogleUrl($_smarty_tpl->tpl_vars['more_information']->value['layout']);?>
" />

	</div>

	<div class="zoom-cmd d-flex flex-column">

		<a id="zoom-in" title="Zoom in" class="zoom-in"></a>

		<a id="zoom-out" title="Zoom out" class="zoom-out"></a>

	</div>

	<div class="zoom-map">

		<map name="positionMap" class="positionMapClass">

			<area id="topPositionMap" shape="rect" coords="20,0,40,20" title="move up" alt="move up">

			<area id="leftPositionMap" shape="rect" coords="0,20,20,40" title="move left" alt="move left">

			<area id="rightPositionMap" shape="rect" coords="40,20,60,40" title="move right" alt="move right">

			<area id="bottomPositionMap" shape="rect" coords="20,40,40,60" title="move bottom" alt="move bottom">

		</map>

		<img src="https://myoceancity.vn/application/themes/images/SmartZoom/position.png" usemap="#positionMap">  

	</div>

</div>

<?php }
}
}
