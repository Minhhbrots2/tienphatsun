$(function(){
	var _width = $("#tree-level-container").width(),
	_height = $("#tree-level-container").height(),
	scale = _width/1500;
	$("#tree-level-container").css("height",(_width * 1400 / 1500)+"px");
	$(".img_node .avatar").css({"width":"calc(55px * "+scale+")","height":"calc(55px * "+scale+")","border":"calc(3px * "+scale+") solid #FFF"});
	$(".node .box_content:not(.no_staff)").css({"width":"calc(140px * "+scale+")","height":"calc(60px * "+scale+")","font-size":"calc(12px * "+scale+")","border-radius":"calc(12px * "+scale+")","margin-top":"calc(-15px * "+scale+")","padding":"calc(20px * "+scale+") calc(5px * "+scale+") calc(5px * "+scale+")"});
	$(".node .box_content.no_staff").css({"width":"calc(140px * "+scale+")","height":"calc(82px * "+scale+")","font-size":"calc(12px * "+scale+")","border-radius":"calc(12px * "+scale+")","padding":"calc(5px * "+scale+")"});
	$(".node .box_content .txt_name").css({"font-size":"calc(12px * "+scale+")","margin-bottom":"calc(3px * "+scale+")"});
	$(".node").each(function(index, elm) {		
		if(deviceType == 'phone') {
			var pos = $(elm).position();
//			$(elm).css("top", "calc("+(pos.top)+"px * "+scale*3+")");	
		}		
//		console.log("calc("+(pos.top)+"px + 15px)");
//		$Core.org_chart.drawTreeConnectors();
	});
	$(".control-handle").each(function(index, elm) {	
		$(elm).css({"width":"calc(12px * "+scale+")","height":"calc(12px * "+scale+")"});
		if(deviceType == 'phone') {
			var pos = $(elm).position();
//			$(elm).css("top", "calc("+(pos.top)+"px * "+scale*3+")");	
		}		
//		console.log("calc("+(pos.top)+"px + 15px)");
	});
	$Core.org_chart.drawTreeConnectors();
});
$Core.org_chart = {
	// Hàm vẽ tất cả các đường nối kiểu "elbow"
	drawTreeConnectors: function () {
		$("#tree-connectors").empty();
		var containerOffset = $("#tree-level-container").offset();
		$(".node",$("#tree-level-container")).each(function(index, elm) {
			var $child = $(elm);
			var level = $child.data("level");
//			console.log($child);
			if (level > 0) {
				// Nếu là node con riêng (individual)
				if ($child.attr("data-parent-id")) {
					var parentID = $child.attr("data-parent-id");
					var $parent = $('.node[data-id="' + parentID + '"]',$("#tree-level-container"));
					if ($parent.length) {
						$Core.org_chart.drawElbowLineBetween($parent, $child, containerOffset, "individual");
					}
				}
				// Nếu là node con chung (common)
				if ($child.attr("data-common-parent-ids")) {
					var arr = $child.attr("data-common-parent-ids").split(",");
					arr.forEach(function(pid) {
						var $parent = $('.node[data-id="' + pid + '"]',$("#tree-level-container"));
						if ($parent.length) {
							$Core.org_chart.drawElbowLineBetween($parent, $child, containerOffset, "common");
						}
					});
				}
			}
		});		
	},
	drawElbowLineBetween:function ($parent, $child, containerOffset, type) {		
		var parentPos = $parent.position();
		var pX = parentPos.left + $parent.outerWidth() / 2;
		var pY = parentPos.top + $parent.outerHeight() / 2;
		var parentPoint = { x: pX, y: pY };

		// Tọa độ điểm kết nối của node con: trung tâm trên
		var childPos = $child.position();
		var cX = childPos.left + $child.outerWidth() / 2;
		var cY = childPos.top;
		var childPoint = { x: cX, y: cY };
		// Nếu handle chưa tồn tại, tạo handle mới
		var parentId = $parent.data('id');
		var childId = $child.data('id');
		var $point = 'handle_'+ $parent.data("id") + '_'+$child.data('id');
			if ($("#" + $point).length === 0) {
			  var $handle = $("<div class='control-handle'></div>");
			  $handle.attr("id", $point);
				console.log($handle);
			  $("#tree-level-container").append($handle);  
			  // Mặc định: handle chưa được người dùng kéo (false)
			  $handle.data("userMoved", false);
			  
			}		
            // Lấy handle của kết nối hiện tại
            var $handle = $("#" + $point);
            
            // Nếu handle chưa được điều chỉnh, cập nhật vị trí mặc định dựa theo endpoints:
            // mặc định: hₓ = Pₓ, h_y = (P_y + C_y) / 2
            if (!$handle.data("userMoved") && !$handle.data("dragging") && !$handle.hasClass("dragged")) {
				// Cập nhật vị trí mặc định của handle
				var defaultHX = pX;
				var defaultHY = (pY + cY) / 2;
				var hW = $handle.outerWidth();
				var hH = $handle.outerHeight();
				$handle.css({
					left: (defaultHX - hW / 2) + "px",
					top: (defaultHY - hH / 2) + "px"
				});
			}
            
            // Lấy vị trí hiện tại (center) của handle
            var handlePos = $handle.position();
            var hWidth = $handle.outerWidth();
            var hHeight = $handle.outerHeight();
            var hX = handlePos.left + hWidth / 2;
            var hY = handlePos.top + hHeight / 2;
            var controlPoint = { x: hX, y: hY };
            
            // Xây dựng chuỗi lệnh cho SVG path theo thiết kế:
            // 1. Từ parent's point đến (controlPoint.x, parent's point.y) → đoạn ngang
            // 2. Từ (controlPoint.x, parent's point.y) đến (controlPoint.x, controlPoint.y) → đoạn đứng
            // 3. Từ (controlPoint.x, controlPoint.y) đến (childPoint.x, controlPoint.y) → đoạn ngang
            // 4. Từ (childPoint.x, controlPoint.y) đến childPoint → đoạn đứng
            var d = "M " + parentPoint.x + " " + parentPoint.y + " " +
                    "L " + controlPoint.x + " " + parentPoint.y + " " +
                    "L " + controlPoint.x + " " + controlPoint.y + " " +
                    "L " + childPoint.x + " " + controlPoint.y + " " +
                    "L " + childPoint.x + " " + childPoint.y;
            var $svg = $("#tree-connectors");
            // Nếu đường nối (path) chưa tồn tại, tạo mới
            var pathId = "path_" + parentId + "_" + childId;
            var $path = $("#" + pathId);
            if ($path.length === 0) {
              var newPath = document.createElementNS("http://www.w3.org/2000/svg", "path");
              newPath.setAttribute("id", pathId);
              newPath.setAttribute("stroke", "black");
              newPath.setAttribute("stroke-width", "1");
              newPath.setAttribute("fill", "none");
              $svg.append(newPath);
              $path = $("#" + pathId);
            }		
		$path.attr("d", d);	
		
	},
}