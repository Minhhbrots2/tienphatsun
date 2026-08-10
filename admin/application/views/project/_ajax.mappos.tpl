<div class="modal-dialog modal-xss" style="width: 1440px;max-width: 1440px">
	<form class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>Cấu hình</strong></h3>
		</div>
		<div class="modal-body">
			<div id="map_canvas_{$uid}" class="map_canvas"></div>
		</div>
	</form>
</div>
{literal}
<style type="text/css">
	.map_canvas {
		margin: auto;
		width: 100%;
		height: 800px;
		position: relative;
		border-radius:3px;
		-moz-border-radius:3px;
		-khtml-border-radius:3px;
		-khtml-border-radius:3px;
	}
</style>
{/literal}