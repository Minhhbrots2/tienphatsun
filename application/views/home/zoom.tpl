<div class="content-wrapper">
    <div class="container-xxl flex-grow-1{if $deviceType eq 'phone'} pt-0{else} pt-1{/if} container-p-y">
		<div class="zoom-loading bg-grayter rounded-3">
			<div class="d-flex align-items-center justify-content-center w-100 h-100">
				<div class="text-center">
					<div class="d-flex align-items-center justify-content-center mb-2">
						<i class="fa text-muted fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
					</div>
					<span class="text-muted">Đang tải dữ liệu...</span>
				</div>
			</div>
		</div>
		<div class="zoom-wrapper relative" style="display:none">
			<div class="zoom-container rounded-3 overflow-hidden">
				<img id="imageFullScreen" src="{$URL_IMAGES}/backgrounds/MBVHOP1.jpg?v={$upd_version}" />
			</div>
			<div class="zoom-cmd d-flex flex-column">
				<a id="zoomInButton" title="Zoom in" class="zoom-in"></a>
				<a id="zoomOutButton" title="Zoom out" class="zoom-out"></a>
			</div>
			<div class="zoom-map">
				<map name="positionMap" class="positionMapClass">
					<area id="topPositionMap" shape="rect" coords="20,0,40,20" title="move up" alt="move up"/>
					<area id="leftPositionMap" shape="rect" coords="0,20,20,40" title="move left" alt="move left"/>
					<area id="rightPositionMap" shape="rect" coords="40,20,60,40" title="move right" alt="move right"/>
					<area id="bottomPositionMap" shape="rect" coords="20,40,40,60" title="move bottom" alt="move bottom"/>
				</map>
				<img src="{$URL_IMAGES}/SmartZoom/position.png" usemap="#positionMap" /> </span> 
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
	$(function() {
		function zoomButtonClickHandler(e){
			var scaleToAdd = 0.8;
			if(e.target.id == 'zoomOutButton')
				scaleToAdd = -scaleToAdd;
			$('#'+'imageFullScreen').smartZoom('zoom', scaleToAdd);
		}
		function moveButtonClickHandler(e){
			var pixelsToMoveOnX = 0,
				pixelsToMoveOnY = 0;
			switch(e.target.id){
				case "leftPositionMap":
					pixelsToMoveOnX = 50;	
				break;
				case "rightPositionMap":
					pixelsToMoveOnX = -50;
				break;
				case "topPositionMap":
					pixelsToMoveOnY = 50;	
				break;
				case "bottomPositionMap":
					pixelsToMoveOnY = -50;	
				break;
			}
			$('#'+'imageFullScreen').smartZoom('pan', pixelsToMoveOnX, pixelsToMoveOnY);
		}
		$(window).on("load", function() {
			$('.zoom-loading').stop(false, true).hide();
			$('.zoom-wrapper').stop(false, true).show(function(){
				$('#'+'imageFullScreen').smartZoom({'containerClass':'zoomableContainer'});
				$('#'+'imageFullScreen').smartZoom('zoom', 0.1);
				$('#topPositionMap,#leftPositionMap,#rightPositionMap,#bottomPositionMap').bind("click", moveButtonClickHandler);
  				$('#zoomInButton,#zoomOutButton').bind("click", zoomButtonClickHandler);
			});
		});
	});
</script>
{/literal}