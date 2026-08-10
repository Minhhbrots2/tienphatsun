<link rel="stylesheet" href="{$URL_CSS}/setting.css?v={$upd_version}">
<header class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title">Cấu hình</h1>				
				<button class="btn btn-default btn_start" type="button" onClick="$Core.org_chart.open_org(0,0)" data-id="0" data-level="0">Khởi tạo</button>
			</div>
		</div>
	</div><div class="collapsible-header"><div class="collapsible-header__heading"></div></div>
</header>
<div class="card">
	<div class="card-body">
		<div id="tree-level-container">
			{if !empty($arr_node_level)}
				{foreach from=$arr_node_level item=lstNode key=key}
					<div class="tree-level" data-level="{$key}">
						{foreach from=$lstNode item=_oNode}
							{assign var=oneRole value=$_oNode.oneRole}
							{assign var=oneStaff value=$_oNode.oneStaff}
							{if !empty($_oNode.staff_id)}
								<div id="{$_oNode.id}" class="node" data-id="{$_oNode.id}" data-level="{$_oNode.level}" data-parent-id="{$_oNode.parentId}" data-common-parent-ids="{$_oNode.commonParentIds}" data-role_id="{$_oNode.role_id}" data-staff_id="{$_oNode.staff_id}" data-text_name="{$_oNode.text_name}" style="left: {$_oNode.position.left}%; top: {$_oNode.position.top}%;">
									<div class="img_node"><img class="avatar m-0" src="{$clsProfile->getAvatar($staff_id,$oneStaff)}" onerror="this.src='{$URL_IMAGES}/no-avatar.svg'"></div>
									<div class="box_content">
										<h3 class="txt_name m-0 fs-16 fw-bold">{$clsProfile->getFullname($staff_id,$oneStaff)}</h3>
										<!--<span class="txt_role fs-11">{$oneRole["title"]}{if !empty({$_oNode["text_name"]})}-{$_oNode["text_name"]}{/if}</span>-->
										<span class="txt_role fs-11">{if !empty({$_oNode["text_name"]})}{$_oNode["text_name"]}{else}{$oneRole["title"]}{/if}</span>
									</div>
								</div>
							{else}
								<div id="{$_oNode.id}" class="node" data-id="{$_oNode.id}" data-level="{$_oNode.level}" data-parent-id="{$_oNode.parentId}" data-common-parent-ids="{$_oNode.commonParentIds}" data-role_id="{$_oNode.role_id}" data-staff_id="{$_oNode.staff_id}" data-text_name="{$_oNode.text_name}" style="left: {$_oNode.position.left}%; top: {$_oNode.position.top}%;">
									<div class="img_node"><img class="avatar m-0" src="{$URL_IMAGES}/no-avatar.svg" onerror="this.src='{$URL_IMAGES}/no-avatar.svg'"></div>
									<div class="box_content">
										<span class="txt_role fs-11">{$oneRole["title"]}{if !empty({$_oNode["text_name"]})}-{$_oNode["text_name"]}{/if}</span>
									</div>
								</div>
							{/if}
							
						{/foreach}
					</div>
				{/foreach}
			{/if}
			<!-- SVG overlay dùng để vẽ các đường nối -->
			<svg id="tree-connectors"></svg>			
			{if !empty($lst_point)}
				{foreach from=$lst_point item=_oPoint}
					{assign var=position value=$_oPoint.position}
					<div class="control-handle dragged" data-index="{$_oNode.id}" id="{$_oPoint.id}" left="{$position.left}" top="{$position.top}" style="left:{$position.left}%;top:{$position.top}%"></div>
				{/foreach}
			{/if}
		</div>
		<!-- Context menu (ẩn ban đầu) -->
		<div id="context-menu">
			<ul>
			<li data-action="add-child">Thêm node con</li>
			<li data-action="edit-node">Sửa node</li>
			<li data-action="delete-node">Xóa node</li>
			<li data-action="add-common-child">Thêm node con chung</li>
			</ul>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
{literal}
<script>
	$(document).ready(function() {
		var selectedNodes = []; // Lưu các node được chọn

		// Khởi tạo draggable cho các node ban đầu
		$(".node").each(function() {
			$Core.org_chart.initDraggable($(this));
			$Core.org_chart.drawTreeConnectors();
		});

		// Ẩn context menu khi click ra ngoài
		$(document).click(function() {
			$("#context-menu").hide();
		});

		// Xử lý sự kiện chuột phải trên node
		$(document).on("contextmenu", ".node", function(e) {
			e.preventDefault();
			var $node = $(this);

			if (!e.ctrlKey) {
				$(".node").removeClass("selected");
				selectedNodes = [];
			}
			if (!$node.hasClass("selected")) {
				$node.addClass("selected");
				selectedNodes.push($node);
			}

			// Nếu lựa chọn nhiều node cùng cấp thì chỉ hiện option "Thêm node con chung"
			var menuOptions = $("#context-menu li");
			if (selectedNodes.length > 1) {
				var level = $(selectedNodes[0]).data("level");
				var sameLevel = selectedNodes.every(function(idx, elem) {
					return $(elem).data("level") === level;
				});
				console.log(sameLevel);
				if (sameLevel) {
					menuOptions.hide();
					console.log(1);
					$("#context-menu li").hide();
					$("#context-menu li[data-action='add-common-child']").show();
				} else {
					menuOptions.show();
				}
			} else {
				menuOptions.show();
				$("#context-menu li[data-action='add-common-child']").hide();
			}

			$("#context-menu").css({
				top: e.pageY,
				left: e.pageX
			}).show();

			e.stopPropagation();
		});

		// Xử lý click chuột trái để chọn node (hỗ trợ multi-select với Ctrl)
		$(document).on("click", ".node", function(e) {
			if (!e.ctrlKey) {
				$(".node").removeClass("selected");
				selectedNodes = [];
			}
			var $node = $(this);
			if (!$node.hasClass("selected")) {
				$node.addClass("selected");
				selectedNodes.push($node);
			} else if (e.ctrlKey) {
				$node.removeClass("selected");
				selectedNodes = selectedNodes.filter(function(item) {
					return item[0] !== $node[0];
				});
			}
			let childId = $node.attr("id"),
				parentId = $node.data("parent-id");
//			$(".control-handle").css({"opacity":0,"z-index":-1});
			console.log("#handle_"+parentId+"_"+childId);
			$("#handle_"+parentId+"_"+childId).css({"opacity":1,"z-index":1});
			
			e.stopPropagation();
		});

		// Xử lý các lệnh từ context menu
		$(document).on("click", "#context-menu li", function(e) {
			var action = $(this).data("action");

			// THÊM NODE CON (riêng)
			if (action === "add-child") {
				if (selectedNodes.length === 1) {
					var $parent = selectedNodes[0];
					var parentLevel = $parent.data("level");
					var childLevel = parentLevel + 1;
					var newID = "node_" + new Date().getTime();
					$Core.org_chart.open_org(newID,childLevel,$parent.data("id"),"");
				}
			}
			// SỬA NODE
			else if (action === "edit-node") {
				if (selectedNodes.length === 1) {
					var $node = selectedNodes[0];
					var currentName = $node.text();
//					var newName = prompt("Sửa tên node:", currentName);
					$Core.org_chart.open_org($node.data("id"),$node.data("level"),$node.data("parent-id"),$node.data("common-parent-ids"));
					console.log($node.data("id"),$node.data("level"),$node.data("parent-id"),$node.data("common-parent-ids"));
//					if (newName) {
//						$node.text(newName);
//					}
				}
			}
			// XÓA NODE
			else if (action === "delete-node") {
				if (selectedNodes.length === 1) {
					if (confirm("Bạn có chắc muốn xóa node này?")) {
						selectedNodes[0].remove();
						$Core.org_chart.drawTreeConnectors();	
					}
				}
			}
			// THÊM NODE CON CHUNG
			else if (action === "add-common-child") {
				if (selectedNodes.length >= 2) {
					var level = $(selectedNodes[0]).data("level");
					var childLevel = level + 1;
					var newID = "common_node_" + new Date().getTime();
					var parentIds = selectedNodes.map(function($n) {
						return $n.data("id");
					}).join(",");
					$Core.org_chart.open_org(newID,childLevel,"",parentIds);
				}
			}

			$("#context-menu").hide();
//			drawTreeConnectors();
			e.stopPropagation();
		});



		// Cập nhật lại đường nối khi cửa sổ thay đổi kích thước
		$(window).resize(function() {
//			drawTreeConnectors();
		});

	});
</script>
{/literal}
