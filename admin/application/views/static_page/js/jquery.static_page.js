/* Static Page module — editor mục nội dung (act=edit) */
$(document).ready(function(){
	if(mod == "static_page" && act == "edit"){
		$Core.static_page.init();
	}
});
$Core.static_page = {
	init: function(){
		var $box = $("#sp_sections");
		$("#sp_add").on("click", $Core.static_page.addSection);
		$box.on("click", ".sp-del", $Core.static_page.delSection);
		$box.on("click", ".sp-up", function(e){ e.preventDefault(); $Core.static_page.move("up", $(this).closest(".sp-section")); });
		$box.on("click", ".sp-down", function(e){ e.preventDefault(); $Core.static_page.move("down", $(this).closest(".sp-section")); });
		$("#edititem").on("submit", $Core.static_page.beforeSubmit);
	},
	// Có TinyMCE hay không
	hasTiny: function(){
		return (typeof tinyMCE !== "undefined" && tinyMCE);
	},
	// Khởi tạo editor cho textarea mục mới (thêm qua AJAX)
	initEditor: function(id){
		if(!id) return;
		if($Core.static_page.hasTiny() && tinyMCE.get(id)) return;
		$("#"+id).addClass("isoTextArea").isoTextArea();
	},
	// Thêm mục: lấy HTML từ controller rồi append + init editor
	addSection: function(e){
		e.preventDefault();
		vietiso_loading(1);
		$.post(path_ajax_script+"/index.php?mod="+mod+"&act=add_section", {}, function(html){
			var $node = $(html);
			$("#sp_sections").append($node);
			$node.find(".sp-body").each(function(){ $Core.static_page.initEditor(this.id); });
			vietiso_loading(0);
		});
	},
	// Xoá mục: gỡ editor trước khi remove DOM
	delSection: function(e){
		e.preventDefault();
		if(!confirm("Xoá mục này?")) return;
		var $sec = $(this).closest(".sp-section");
		var id = $sec.find(".sp-body").attr("id");
		if($Core.static_page.hasTiny() && tinyMCE.get(id)){ tinyMCE.execCommand("mceRemoveControl", false, id); }
		$sec.remove();
	},
	// Di chuyển lên/xuống: save + gỡ editor, đổi vị trí DOM, gắn lại editor (iframe-safe)
	move: function(dir, $sec){
		var id = $sec.find(".sp-body").attr("id");
		var hadEd = ($Core.static_page.hasTiny() && tinyMCE.get(id));
		if(hadEd){ tinyMCE.get(id).save(); tinyMCE.execCommand("mceRemoveControl", false, id); }
		if(dir == "up"){ var $p = $sec.prev(".sp-section"); if($p.length) $sec.insertBefore($p); }
		else { var $n = $sec.next(".sp-section"); if($n.length) $sec.insertAfter($n); }
		if(hadEd){ tinyMCE.execCommand("mceAddControl", false, id); }
	},
	// Đẩy nội dung editor về textarea trước khi submit
	beforeSubmit: function(){
		if($Core.static_page.hasTiny()) tinyMCE.triggerSave();
	}
};
