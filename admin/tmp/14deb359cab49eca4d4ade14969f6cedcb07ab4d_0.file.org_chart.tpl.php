<?php
/* Smarty version 3.1.33, created on 2026-08-07 16:45:37
  from '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/org_chart.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_6a75a9416aeb69_53953318',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '14deb359cab49eca4d4ade14969f6cedcb07ab4d' => 
    array (
      0 => '/www/wwwroot/tienphatsunrise.c-a.vn/admin/application/views/setting/org_chart.tpl',
      1 => 1784691723,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6a75a9416aeb69_53953318 (Smarty_Internal_Template $_smarty_tpl) {
?><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['URL_CSS']->value;?>
/setting.css?v=<?php echo $_smarty_tpl->tpl_vars['upd_version']->value;?>
">

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

			<?php if (!empty($_smarty_tpl->tpl_vars['arr_node_level']->value)) {?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['arr_node_level']->value, 'lstNode', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['lstNode']->value) {
?>

					<div class="tree-level" data-level="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
">

						<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lstNode']->value, '_oNode');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oNode']->value) {
?>

							<?php $_smarty_tpl->_assignInScope('oneRole', $_smarty_tpl->tpl_vars['_oNode']->value['oneRole']);?>

							<?php $_smarty_tpl->_assignInScope('oneStaff', $_smarty_tpl->tpl_vars['_oNode']->value['oneStaff']);?>

							<?php if (!empty($_smarty_tpl->tpl_vars['_oNode']->value['staff_id'])) {?>

								<div id="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['id'];?>
" class="node" data-id="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['id'];?>
" data-level="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['level'];?>
" data-parent-id="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['parentId'];?>
" data-common-parent-ids="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['commonParentIds'];?>
" data-role_id="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['role_id'];?>
" data-staff_id="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['staff_id'];?>
" data-text_name="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['text_name'];?>
" style="left: <?php echo $_smarty_tpl->tpl_vars['_oNode']->value['position']['left'];?>
%; top: <?php echo $_smarty_tpl->tpl_vars['_oNode']->value['position']['top'];?>
%;">

									<div class="img_node"><img class="avatar m-0" src="<?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getAvatar($_smarty_tpl->tpl_vars['staff_id']->value,$_smarty_tpl->tpl_vars['oneStaff']->value);?>
" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.svg'"></div>

									<div class="box_content">

										<h3 class="txt_name m-0 fs-16 fw-bold"><?php echo $_smarty_tpl->tpl_vars['clsProfile']->value->getFullname($_smarty_tpl->tpl_vars['staff_id']->value,$_smarty_tpl->tpl_vars['oneStaff']->value);?>
</h3>

										<!--<span class="txt_role fs-11"><?php echo $_smarty_tpl->tpl_vars['oneRole']->value["title"];
ob_start();
echo $_smarty_tpl->tpl_vars['_oNode']->value["text_name"];
$_prefixVariable1 = ob_get_clean();
if (!empty($_prefixVariable1)) {?>-<?php echo $_smarty_tpl->tpl_vars['_oNode']->value["text_name"];
}?></span>-->

										<span class="txt_role fs-11"><?php ob_start();
echo $_smarty_tpl->tpl_vars['_oNode']->value["text_name"];
$_prefixVariable2 = ob_get_clean();
if (!empty($_prefixVariable2)) {
echo $_smarty_tpl->tpl_vars['_oNode']->value["text_name"];
} else {
echo $_smarty_tpl->tpl_vars['oneRole']->value["title"];
}?></span>

									</div>

								</div>

							<?php } else { ?>

								<div id="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['id'];?>
" class="node" data-id="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['id'];?>
" data-level="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['level'];?>
" data-parent-id="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['parentId'];?>
" data-common-parent-ids="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['commonParentIds'];?>
" data-role_id="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['role_id'];?>
" data-staff_id="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['staff_id'];?>
" data-text_name="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['text_name'];?>
" style="left: <?php echo $_smarty_tpl->tpl_vars['_oNode']->value['position']['left'];?>
%; top: <?php echo $_smarty_tpl->tpl_vars['_oNode']->value['position']['top'];?>
%;">

									<div class="img_node"><img class="avatar m-0" src="<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.svg" onerror="this.src='<?php echo $_smarty_tpl->tpl_vars['URL_IMAGES']->value;?>
/no-avatar.svg'"></div>

									<div class="box_content">

										<span class="txt_role fs-11"><?php echo $_smarty_tpl->tpl_vars['oneRole']->value["title"];
ob_start();
echo $_smarty_tpl->tpl_vars['_oNode']->value["text_name"];
$_prefixVariable3 = ob_get_clean();
if (!empty($_prefixVariable3)) {?>-<?php echo $_smarty_tpl->tpl_vars['_oNode']->value["text_name"];
}?></span>

									</div>

								</div>

							<?php }?>

							

						<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

					</div>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php }?>

			<!-- SVG overlay dùng để vẽ các đường nối -->

			<svg id="tree-connectors"></svg>			

			<?php if (!empty($_smarty_tpl->tpl_vars['lst_point']->value)) {?>

				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['lst_point']->value, '_oPoint');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['_oPoint']->value) {
?>

					<?php $_smarty_tpl->_assignInScope('position', $_smarty_tpl->tpl_vars['_oPoint']->value['position']);?>

					<div class="control-handle dragged" data-index="<?php echo $_smarty_tpl->tpl_vars['_oNode']->value['id'];?>
" id="<?php echo $_smarty_tpl->tpl_vars['_oPoint']->value['id'];?>
" left="<?php echo $_smarty_tpl->tpl_vars['position']->value['left'];?>
" top="<?php echo $_smarty_tpl->tpl_vars['position']->value['top'];?>
" style="left:<?php echo $_smarty_tpl->tpl_vars['position']->value['left'];?>
%;top:<?php echo $_smarty_tpl->tpl_vars['position']->value['top'];?>
%"></div>

				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			<?php }?>

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

<?php echo '<script'; ?>
 src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"><?php echo '</script'; ?>
>



<?php echo '<script'; ?>
>

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

<?php echo '</script'; ?>
>



<?php }
}
