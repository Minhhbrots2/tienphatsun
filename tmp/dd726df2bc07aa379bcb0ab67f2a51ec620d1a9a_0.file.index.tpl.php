<?php
/* Smarty version 3.1.33, created on 2026-08-05 17:52:33
  from '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/note_calendar/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a7315f1856cf1_82089048',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dd726df2bc07aa379bcb0ab67f2a51ec620d1a9a' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/application/blocks/note_calendar/index.tpl',
      1 => 1785927046,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a7315f1856cf1_82089048 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="card bg-main mb-2">

	<div class="card-header d-flex align-items-center justify-content-between">

		<h5 class="card-title text-white m-0 d-flex align-items-center gap-1">

			<i class='bx bx-calendar'></i>

			<span>Ghi chú cá nhân</span>

		</h5>

	</div>

	<div class="card-body mt-0">

		<div class="note_calendar position-relative">

			<div class="calendar">

				<div class="header">

					<button id="prev" class="btn btn-outline-default btn-icon btn-sm">

						<i class='bx bx-chevron-left'></i>

					</button>

					<div id="text_month" class="text_month text-upper fw-bold"></div>

					<button id="next" class="btn btn-outline-default btn-icon btn-sm">

						<i class='bx bx-chevron-right'></i>

					</button>

				</div>

				<div class="weekdays">

					<div class="weekday">CN</div>

					<div class="weekday">T2</div>

					<div class="weekday">T3</div>

					<div class="weekday">T4</div>

					<div class="weekday">T5</div>

					<div class="weekday">T6</div>

					<div class="weekday">T7</div>

				</div>

				<div class="grid" id="load_calendar"></div>

			</div>

			<div id="tooltip" class="tooltip-custom" aria-hidden="true"></div>

		</div>

	</div>

</div>

<?php echo '<script'; ?>
>

	var arr_note = `<?php echo $_smarty_tpl->tpl_vars['arr_note']->value;?>
`; 

	arr_note = JSON.parse(arr_note);

	var special = `<?php echo $_smarty_tpl->tpl_vars['arr_special']->value;?>
`; 

	special = JSON.parse(special);

<?php echo '</script'; ?>
>



<style>

  :root{--bg:#f8fafc;--card:#fff;--accent:#2563eb;--muted:#6b7280;--note:#16a34a}

  .note_calendar .wrap{max-width:900px;margin:0 auto}

  .note_calendar .calendar{background:var(--card);border-radius:12px;padding:12px;box-shadow:0 8px 30px rgba(2,6,23,0.06)}

  .note_calendar .header{display:flex;gap:10px;align-items:center;justify-content:center;margin-bottom:10px}

  .note_calendar .weekdays{display:grid;grid-template-columns:repeat(7,1fr);gap:6px;margin-bottom:8px}

  .note_calendar .weekday{text-align:center;font-weight:700;color:var(--muted);padding:8px 6px;border-radius:6px;background:#f1f5f9}

  .note_calendar .grid{display:grid;grid-template-columns:repeat(7,1fr);gap:8px}

  .note_calendar .cell{background:transparent;border-radius:10px;aspect-ratio:1/1;padding:8px;border:1px solid transparent;display:flex;flex-direction:column;justify-content:flex-start;align-items:flex-start;position:relative;overflow:hidden;cursor:pointer;width:100%}

  .note_calendar .cell.box{background:var(--card);border:1px solid #eef2f7;position-relative}

  .note_calendar .cell.other{opacity:.45}

  .note_calendar .cell.today{outline:3px solid rgba(37,99,235,0.10);border-color:var(--accent)}

  .note_calendar .solar{font-weight:700;font-size:16px}

  .note_calendar .lunar{font-size:10px;color:var(--muted);/*margin-top:6px*/}

  .note_calendar .badge{position:absolute;right: 3px;top: 7px;background:var(--note);color:#fff;border-radius:12px;padding: 3px;font-size: 10px;aspect-ratio: 1 / 1;min-height: 16px;line-height: 100%;z-index:1}

  .note_calendar .info-icon{font-size:14px;color:inherit;cursor:pointer;position:absolute;right:2px;top:2px}

 .note_calendar .tooltip-custom{position:absolute;z-index:1100;background:rgba(0,0,0,.82);color:#fff;padding:6px 8px;border-radius:6px;font-size:13px;display:none;white-space:nowrap}

	.text_month{

		font-size: 20px

	}

	.box_birthday {

		position: absolute;

		width: 18px;

		height: 18px;

		bottom: 2px;

		right: 1px;

		z-index: 0;

		background-size: 100%;

		background-repeat: no-repeat;

	}

	@media screen and (max-width: 1280px) and (min-width:992px){

		.text_month{font-size:16px}

	}

	@media screen and (max-width: 1600px) and (min-width:992px){

		.note_calendar .grid{gap:3px} 

		  .note_calendar .solar {font-size: 11px}

		  .note_calendar .cell{/*min-height: 50px;*/padding:3px;padding: 3px;border-radius: 5px}

		  .note_calendar .badge {top: 0;right: 0;padding: 2px;font-size: 8px;min-height: 12px}

		  .note_calendar .info-icon i {font-size: 12px}

		  .note_calendar .info-icon {top: 2px;right: 2px}

		  .note_calendar .lunar {font-size: 7px;margin-top: 0}

		.box_birthday{ width: 12px;

        height: 12px;

        bottom: 1px;

        right: 0px;}

	}

  @media screen and (max-width:800px){ .note_calendar .cell{/*min-height:72px;*/padding:6px} .note_calendar .solar{font-size:14px} .note_calendar .lunar{font-size:11px} }

  @media screen and (max-width:480px){ 

	  .note_calendar .grid{gap:3px} 

	  .note_calendar .solar {font-size: 11px}

	  .note_calendar .cell{/*min-height: 50px;*/padding:3px;padding: 3px;border-radius: 5px}

	  .note_calendar .badge {top: 0;right: 0;padding: 2px;font-size: 8px;min-height: 12px}

	  .note_calendar .info-icon i {font-size: 12px}

	  .note_calendar .info-icon {top: 2px;right: 2px}

	  .note_calendar .lunar {font-size: 7px;margin-top: 0}

	}

	/*======*/

.timepicker {max-width: 95px;background: #FFF url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAYAAABWzo5XAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyBpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBXaW5kb3dzIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjJCMzNBNTk1OTBCMzExRTg4QTE3Qzc2OTEyMUU0MUM1IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjJCMzNBNTk2OTBCMzExRTg4QTE3Qzc2OTEyMUU0MUM1Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6MkIzM0E1OTM5MEIzMTFFODhBMTdDNzY5MTIxRTQxQzUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6MkIzM0E1OTQ5MEIzMTFFODhBMTdDNzY5MTIxRTQxQzUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6qceb8AAACMklEQVR42oyTT0hVQRTG59x7KYlqUUFCLaTW/YGCduLigS0qlFwYCAoFLhTX1cqVm0jETYgu0l25bRPPaNEmCw3aBS50k4gWaCJl997jb+6MNN53HzRwmJkz3znznTPfSK3WEZujQ7G85IswMY1D6/X3BTYpAazzuEg8ifuix26oZsMs9nzCypGU9jbRMfC3mX0i2TQmw2d2/ydR6mfLblv173Viethynr/C99OfpU0IFA56IlcdSFcoa5D9PfbnHCTuVs3fkPAF/ktRFLXkefa13DObKBOJBph/uARynqA5Vf3kMVdgNwSmn4vmVaUV30iZVdFsbnwuknwAuEhZNXy/AsxH/K9J9pRkg3metlf1yDZPAYzBYF01feCT2AtiZ/GoiEl5uYdgVgl5FsjkHyNVc4FE3bTobqmRGWTvwHaNmLUiUrPHJK6TsE1EVv1ljhGOG46Ffg4aiAzkJo3vE9GX3o/pEvgt/LcqXk3OANhh/dsHFMoGPMX2NOvNQD/7HG8zn616/nWXzJzEdg77QxmPnI4K+uJKNSdYWll8b2g2AUvUbNXcEfhtGV8gFpYL08i+2Cn8iw2JbM0cTFPKuGcVqjdUfgvwCbCzroqjOoqc5aO2drT0DgKXfWBgwislddZ/eMUnVX8u8c49SuyE1QwBVoAL2LKv8hrWyR6lZ/eZd5v9NT/rFoLs4gvU0FUvJXf5Xn3jkj7mt80+bPBp3e90KrdssgXVyl8gZUUfjgMBBgDITNQbxFBBugAAAABJRU5ErkJggg==') no-repeat calc(100% - 5px) center;}

/** Timepicker */

.ui-timepicker {position: absolute;z-index: 1000;float: left;width: 160px;padding-bottom: 5px;margin: 2px 0 0 0;list-style: none;font-size: 14px;text-align: center;background-color: #fff;border: 1px solid #ccc;border: 1px solid rgba(0, 0, 0, 0.15);border-radius: 4px;-webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);background-clip: padding-box}

.ui-timepicker:after,.ui-timepicker:before {content: "";border-top: 0;display: inline-block;position: absolute}

.ui-timepicker .icon-up {background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAeCAYAAABuUU38AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo1MjMyRjZENzU1NjIxMUU3OTkwMjhDMDYyNDEwRUNBQSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo1MjMyRjZEODU1NjIxMUU3OTkwMjhDMDYyNDEwRUNBQSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjUyMzJGNkQ1NTU2MjExRTc5OTAyOEMwNjI0MTBFQ0FBIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjUyMzJGNkQ2NTU2MjExRTc5OTAyOEMwNjI0MTBFQ0FBIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+GdMqPQAAAZdJREFUeNrUmMlKxEAQhlsE8eQSFyTxWTx5Vhx1xn3XkydvvoMgCOJV8CUUd8UFL+rFN/AV3B39GybQBDNWdTpJpeCDQKqT+ajqJaNUulEG52Af9KuCxiKogp8a76BSNIkVQ8DkE4wXXcKUmZAusRxppzg+JFdmiSAQlRkruoTZZhVJElVLkbAyucssJJQQsTTPO5IwK1POY7P7Jv7AQ3AnUYbTTlqiGXSDB0abjUqSOAItxlhfigxnThxHJGxkdJuNuJaYY8yJkxiJMALwyKiMM5lZpkQr4ZkBozJvYDhLiVPQxni2z5Qp2UrMgC/ii86YEraVGeK+YDoDCRuZV47MFKOd9Gdsu4N56DNlBikS1EpcAM/hyhi4kuFIXDqqRDR6k8pMMiU8lV74jH3mBQyEA0uCJGzaTMv06UFPxAFXoCPDEzZHRh9O1TNRwlPZB/U4c6+TN/9JugadOX6FUmTWdGIT2ItJuAVdAv4XqCezDRrDRH2xG0m4ESJRb2ne+iuxAazXJs5GSvtE0ugBO+AArJo3fgUYAKf2/v/Eyp9gAAAAAElFTkSuQmCC);}

.ui-timepicker .icon-down {background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAeCAYAAABuUU38AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo2MzU2NEE0ODU1NjIxMUU3ODcxRUQxRjVDQkRBMzYxNSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo2MzU2NEE0OTU1NjIxMUU3ODcxRUQxRjVDQkRBMzYxNSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjYzNTY0QTQ2NTU2MjExRTc4NzFFRDFGNUNCREEzNjE1IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjYzNTY0QTQ3NTU2MjExRTc4NzFFRDFGNUNCREEzNjE1Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+NuD5CwAAAaNJREFUeNrUmMlKxEAQhlsE8aSOG5LxWTx5Vhx1dNz3kydvvoMgCOJV8CUUd8UFL44X38BXcBl1xr9gGjSYpCtJd2oKPphDpdPfVHV3EqX+xjo4AnugT8mLHNgCx2ATNP2XtANqvyiDfkESPeDON8d90KwT6MeuL0HzBPJCJO4D5ngAWihpIyBBikw3uI2Y4zYlPkYkZSnTCW4M5vei6gunZkDZsUyXoQTxTBcMgFdhMlSJa8M5fYGCvnCIIUNt5gmSmPYPMAzeGJWxsTXnmBIzQQNxZfIpV+IqDYm4Ml5Klbg0vOe3iYSOEYeV6QAXjErMcm9AMu+WZbgSc3H/rQJTxmNKnDPaaT5p/45aqEw7OHMpoWMMfDDOmTCZNqbEQtp7PMlUErYZSZwajlEFi7ZO3XFGZfwyJHHCkFiy/RzElekFrYyHUycSOoqMNntgSNCaWHb9rsCRyXxNRMUEo83EtFOYTCWhxIqULxwk8xlTRIyEjskYlREnoaNkKEPttKqEx5RBm62pBolSiEzDSARtzdUsDru0YhAc1l9jizZv9CPAACzc/v9R+XQaAAAAAElFTkSuQmCC);}

.ui-timepicker .icon-down,.ui-timepicker .icon-up {display: inline-block;width: 55px;height: 38px;background-position: center center;background-repeat: no-repeat;background-size: 45%;

opacity: 0.4;cursor: pointer;}

.ui-timepicker .icon-down:hover,

.ui-timepicker .icon-up:hover {

opacity: 0.7;

}

.ui-timepicker:before {

top: -7px;

left: 6px;

border-left: 7px solid transparent;

border-right: 7px solid transparent;

border-bottom: 7px solid rgba(0, 0, 0, 0.15);

border-bottom-color: rgba(0, 0, 0, 0.2);

}

.ui-timepicker:after {

top: -6px;

left: 7px;

border-left: 6px solid transparent;

border-right: 6px solid transparent;

border-bottom: 6px solid #fff;

}

.ui-timepicker .title {

padding: 10px 0 5px 0;

color: #888;

width: 100%;

cursor: default;

}

.ui-timepicker .cell-2 {

float: left;

width: 20%;

min-height: 14px;

cursor: default;

}

.ui-timepicker .cell-4 {

width: 40%;

float: left;

}

.ui-timepicker .handle,

.ui-timepicker .text {

position: relative;

}

.ui-timepicker .chose-all {

position: relative;

margin: 0 10px 0 10px;

}

.ui-timepicker .chose-all .text {

font: bold 18px/22px arial, sans-serif;

}

.ui-timepicker a {

color: #aaa;

text-decoration: none;

}

.ui-timepicker .text {

color: #666;

}

.ui-timepicker .text a {

color: #444;

}

.ui-timepicker .text a:active,

.ui-timepicker .text a:focus,

.ui-timepicker .text a:hover {

color: #222;

}

.ui-timepicker ul {

list-style: none;

padding: 0;

margin: 0 5px;

}

.ui-timepicker li.cell-2 {

padding: 3px 0;

cursor: pointer;

}

.ui-timepicker li.cell-2:hover {

background: #ccc;

color: #555;

}

.modal_note {

background: #00000082;

}

	/*======*/

/*.item_note:before {

    content: "";

    position: absolute;

    display: block;

    width: 5px;

    height: 100%;

    background: #a42d44;

    left: 0;

	top:0

}*/

</style>

<?php echo '<script'; ?>
>

	function INT(d) {

		return Math.floor(d);

	}

	function LunarDate(dd, mm, yy, leap, jd) {

		this.day = dd;

		this.month = mm;

		this.year = yy;

		this.leap = leap;

		this.jd = jd;		

	}

	var TK19 = new Array(

		0x30baa3, 0x56ab50, 0x422ba0, 0x2cab61, 0x52a370, 0x3c51e8, 0x60d160, 0x4ae4b0, 0x376926, 0x58daa0,

		0x445b50, 0x3116d2, 0x562ae0, 0x3ea2e0, 0x28e2d2, 0x4ec950, 0x38d556, 0x5cb520, 0x46b690, 0x325da4,

		0x5855d0, 0x4225d0, 0x2ca5b3, 0x52a2b0, 0x3da8b7, 0x60a950, 0x4ab4a0, 0x35b2a5, 0x5aad50, 0x4455b0,

		0x302b74, 0x562570, 0x4052f9, 0x6452b0, 0x4e6950, 0x386d56, 0x5e5aa0, 0x46ab50, 0x3256d4, 0x584ae0,

		0x42a570, 0x2d4553, 0x50d2a0, 0x3be8a7, 0x60d550, 0x4a5aa0, 0x34ada5, 0x5a95d0, 0x464ae0, 0x2eaab4,

		0x54a4d0, 0x3ed2b8, 0x64b290, 0x4cb550, 0x385757, 0x5e2da0, 0x4895d0, 0x324d75, 0x5849b0, 0x42a4b0,

		0x2da4b3, 0x506a90, 0x3aad98, 0x606b50, 0x4c2b60, 0x359365, 0x5a9370, 0x464970, 0x306964, 0x52e4a0,

		0x3cea6a, 0x62da90, 0x4e5ad0, 0x392ad6, 0x5e2ae0, 0x4892e0, 0x32cad5, 0x56c950, 0x40d4a0, 0x2bd4a3,

		0x50b690, 0x3a57a7, 0x6055b0, 0x4c25d0, 0x3695b5, 0x5a92b0, 0x44a950, 0x2ed954, 0x54b4a0, 0x3cb550,

		0x286b52, 0x4e55b0, 0x3a2776, 0x5e2570, 0x4852b0, 0x32aaa5, 0x56e950, 0x406aa0, 0x2abaa3, 0x50ab50

	); /* Years 2000-2099 */

	var TK20 = new Array(

		0x3c4bd8, 0x624ae0, 0x4ca570, 0x3854d5, 0x5cd260, 0x44d950, 0x315554, 0x5656a0, 0x409ad0, 0x2a55d2,

		0x504ae0, 0x3aa5b6, 0x60a4d0, 0x48d250, 0x33d255, 0x58b540, 0x42d6a0, 0x2cada2, 0x5295b0, 0x3f4977,

		0x644970, 0x4ca4b0, 0x36b4b5, 0x5c6a50, 0x466d50, 0x312b54, 0x562b60, 0x409570, 0x2c52f2, 0x504970,

		0x3a6566, 0x5ed4a0, 0x48ea50, 0x336a95, 0x585ad0, 0x442b60, 0x2f86e3, 0x5292e0, 0x3dc8d7, 0x62c950,

		0x4cd4a0, 0x35d8a6, 0x5ab550, 0x4656a0, 0x31a5b4, 0x5625d0, 0x4092d0, 0x2ad2b2, 0x50a950, 0x38b557,

		0x5e6ca0, 0x48b550, 0x355355, 0x584da0, 0x42a5b0, 0x2f4573, 0x5452b0, 0x3ca9a8, 0x60e950, 0x4c6aa0,

		0x36aea6, 0x5aab50, 0x464b60, 0x30aae4, 0x56a570, 0x405260, 0x28f263, 0x4ed940, 0x38db47, 0x5cd6a0,

		0x4896d0, 0x344dd5, 0x5a4ad0, 0x42a4d0, 0x2cd4b4, 0x52b250, 0x3cd558, 0x60b540, 0x4ab5a0, 0x3755a6,

		0x5c95b0, 0x4649b0, 0x30a974, 0x56a4b0, 0x40aa50, 0x29aa52, 0x4e6d20, 0x39ad47, 0x5eab60, 0x489370,

		0x344af5, 0x5a4970, 0x4464b0, 0x2c74a3, 0x50ea50, 0x3d6a58, 0x6256a0, 0x4aaad0, 0x3696d5, 0x5c92e0

	); /* Years 1900-1999 */

	var TK21 = new Array(

		0x46c960, 0x2ed954, 0x54d4a0, 0x3eda50, 0x2a7552, 0x4e56a0, 0x38a7a7, 0x5ea5d0, 0x4a92b0, 0x32aab5,

		0x58a950, 0x42b4a0, 0x2cbaa4, 0x50ad50, 0x3c55d9, 0x624ba0, 0x4ca5b0, 0x375176, 0x5c5270, 0x466930,

		0x307934, 0x546aa0, 0x3ead50, 0x2a5b52, 0x504b60, 0x38a6e6, 0x5ea4e0, 0x48d260, 0x32ea65, 0x56d520,

		0x40daa0, 0x2d56a3, 0x5256d0, 0x3c4afb, 0x6249d0, 0x4ca4d0, 0x37d0b6, 0x5ab250, 0x44b520, 0x2edd25,

		0x54b5a0, 0x3e55d0, 0x2a55b2, 0x5049b0, 0x3aa577, 0x5ea4b0, 0x48aa50, 0x33b255, 0x586d20, 0x40ad60,

		0x2d4b63, 0x525370, 0x3e49e8, 0x60c970, 0x4c54b0, 0x3768a6, 0x5ada50, 0x445aa0, 0x2fa6a4, 0x54aad0,

		0x4052e0, 0x28d2e3, 0x4ec950, 0x38d557, 0x5ed4a0, 0x46d950, 0x325d55, 0x5856a0, 0x42a6d0, 0x2c55d4,

		0x5252b0, 0x3ca9b8, 0x62a930, 0x4ab490, 0x34b6a6, 0x5aad50, 0x4655a0, 0x2eab64, 0x54a570, 0x4052b0,

		0x2ab173, 0x4e6930, 0x386b37, 0x5e6aa0, 0x48ad50, 0x332ad5, 0x582b60, 0x42a570, 0x2e52e4, 0x50d160,

		0x3ae958, 0x60d520, 0x4ada90, 0x355aa6, 0x5a56d0, 0x462ae0, 0x30a9d4, 0x54a2d0, 0x3ed150, 0x28e952

	); /* Years 2000-2099 */

	var TK22 = new Array(

		0x4eb520, 0x38d727, 0x5eada0, 0x4a55b0, 0x362db5, 0x5a45b0, 0x44a2b0, 0x2eb2b4, 0x54a950, 0x3cb559,

		0x626b20, 0x4cad50, 0x385766, 0x5c5370, 0x484570, 0x326574, 0x5852b0, 0x406950, 0x2a7953, 0x505aa0,

		0x3baaa7, 0x5ea6d0, 0x4a4ae0, 0x35a2e5, 0x5aa550, 0x42d2a0, 0x2de2a4, 0x52d550, 0x3e5abb, 0x6256a0,

		0x4c96d0, 0x3949b6, 0x5e4ab0, 0x46a8d0, 0x30d4b5, 0x56b290, 0x40b550, 0x2a6d52, 0x504da0, 0x3b9567,

		0x609570, 0x4a49b0, 0x34a975, 0x5a64b0, 0x446a90, 0x2cba94, 0x526b50, 0x3e2b60, 0x28ab61, 0x4c9570,

		0x384ae6, 0x5cd160, 0x46e4a0, 0x2eed25, 0x54da90, 0x405b50, 0x2c36d3, 0x502ae0, 0x3a93d7, 0x6092d0,

		0x4ac950, 0x32d556, 0x58b4a0, 0x42b690, 0x2e5d94, 0x5255b0, 0x3e25fa, 0x6425b0, 0x4e92b0, 0x36aab6,

		0x5c6950, 0x4674a0, 0x31b2a5, 0x54ad50, 0x4055a0, 0x2aab73, 0x522570, 0x3a5377, 0x6052b0, 0x4a6950,

		0x346d56, 0x585aa0, 0x42ab50, 0x2e56d4, 0x544ae0, 0x3ca570, 0x2864d2, 0x4cd260, 0x36eaa6, 0x5ad550,

		0x465aa0, 0x30ada5, 0x5695d0, 0x404ad0, 0x2aa9b3, 0x50a4d0, 0x3ad2b7, 0x5eb250, 0x48b540, 0x33d556

	); /* Years 2100-2199 */

	var CAN = new Array("Gi\341p", "\u1EA4t", "B\355nh", "\u0110inh", "M\u1EADu", "K\u1EF7", "Canh", "T\342n", "Nh\342m", "Qu\375");

	var CHI = new Array("T\375", "S\u1EEDu", "D\u1EA7n", "M\343o", "Th\354n", "T\u1EF5", "Ng\u1ECD", "M\371i", "Th\342n", "D\u1EADu", "Tu\u1EA5t", "H\u1EE3i");

	var TUAN = new Array("Ch\u1EE7 nh\u1EADt", "Th\u1EE9 hai", "Th\u1EE9 ba", "Th\u1EE9 t\u01B0", "Th\u1EE9 n\u0103m", "Th\u1EE9 s\341u", "Th\u1EE9 b\u1EA3y");

	var GIO_HD = new Array("110100101100", "001101001011", "110011010010", "101100110100", "001011001101", "010010110011");

	var TIETKHI = new Array("Xu\u00E2n ph\u00E2n", "Thanh minh", "C\u1ED1c v\u0169", "L\u1EADp h\u1EA1", "Ti\u1EC3u m\u00E3n", "Mang ch\u1EE7ng",

		"H\u1EA1 ch\u00ED", "Ti\u1EC3u th\u1EED", "\u0110\u1EA1i th\u1EED", "L\u1EADp thu", "X\u1EED th\u1EED", "B\u1EA1ch l\u1ED9",

		"Thu ph\u00E2n", "H\u00E0n l\u1ED9", "S\u01B0\u01A1ng gi\u00E1ng", "L\u1EADp \u0111\u00F4ng", "Ti\u1EC3u tuy\u1EBFt", "\u0110\u1EA1i tuy\u1EBFt",

		"\u0110\u00F4ng ch\u00ED", "Ti\u1EC3u h\u00E0n", "\u0110\u1EA1i h\u00E0n", "L\u1EADp xu\u00E2n", "V\u0169 Th\u1EE7y", "Kinh tr\u1EADp"

	);

	/* ========== Main render logic ========== */

	let view = new Date();

	view.setDate(1);

	view.setHours(0, 0, 0, 0);

	const today = new Date();

	today.setHours(0, 0, 0, 0);

	/* navigation */

	$('#prev').on('click', function() {

		view.setMonth(view.getMonth() - 1);

		$Core.note_calendar.renderCalendar();

	});

	$('#next').on('click', function() {

		view.setMonth(view.getMonth() + 1);

		$Core.note_calendar.renderCalendar();

	});

	$_document.on('click', function() {

		$(".tooltip.show").remove();

	});

	/* initial render */

	$(function() {

		$Core.note_calendar.renderCalendar();

		var FIRST_DAY = $Core.note_calendar.jdn(25, 01, 1800),

		LAST_DAY = $Core.note_calendar.jdn(31, 12, 2199);

		var today = new Date();

		var currentLunarDate = $Core.note_calendar.getLunarDate(today.getDate(), today.getMonth()+1, today.getFullYear());

		var currentMonth = today.getMonth()+1;

		var currentYear = today.getFullYear();

	});

<?php echo '</script'; ?>
>



<?php }
}
