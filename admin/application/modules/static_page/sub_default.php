<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| Static Page module — quản lý nội dung trang tĩnh (điều khoản, chính sách)
|| Lưu trên bảng default_page (model Page), nội dung cấu trúc nằm trong cột
|| more_information (JSON): is_static, subtitle, last_updated, hero_icon, sections[]
\*======================================================================*/

# Slug của các trang tĩnh cũ để hiển thị sẵn trong danh sách (không cần seed DB)
function static_page_legacy_slugs(){
	return array('dieu-khoan-su-dung','chinh-sach-bao-mat');
}

# Giữ ngữ cảnh lọc (keyword + tab) khi redirect sau các thao tác
function static_page_ctx_url(){
	$url = '';
	$keyword = Input::get('keyword','');
	$type_list = Input::get('type_list','');
	if($keyword!=='') $url .= '&keyword='.urlencode($keyword);
	if($type_list!=='') $url .= '&type_list='.urlencode($type_list);
	return $url;
}

# Dựng HTML 1 mục (section) — dùng chung cho render lần đầu và AJAX thêm mục
function static_page_section_html($uid,$section=array()){
	$icon  = isset($section['icon'])  ? htmlspecialchars($section['icon'],ENT_QUOTES)  : '';
	$title = isset($section['title']) ? htmlspecialchars($section['title'],ENT_QUOTES) : '';
	$intro = isset($section['intro']) ? htmlspecialchars($section['intro']) : '';
	$body  = isset($section['body'])  ? htmlspecialchars($section['body']) : '';
	$html  = '<div class="sp-section ui-card mb-half" data-uid="'.$uid.'">'
		.'<div class="ui-card__section">'
			.'<div class="form-row form-group sp-section__head">'
				.'<div class="sp-col-icon">'
					.'<label class="col-form-label">Icon</label>'
					.'<div class="iconpicker-field" data-iconpicker>'
						.'<span class="iconpicker-preview-box"><i class="iconpicker-preview '.$icon.'"></i></span>'
						.'<input type="text" class="form-control iconpicker-input sp-icon" name="sections['.$uid.'][icon]" value="'.$icon.'" placeholder="Chọn icon" />'
						.'<button type="button" class="btn btn-default iconpicker-btn" title="Chọn icon"><i class="bx bx-grid-alt"></i></button>'
					.'</div>'
				.'</div>'
				.'<div class="sp-col-title">'
					.'<label class="col-form-label">Tiêu đề mục</label>'
					.'<input type="text" class="form-control sp-title" name="sections['.$uid.'][title]" value="'.$title.'" placeholder="Tiêu đề mục" />'
				.'</div>'
				.'<div class="sp-col-act">'
					.'<a href="javascript:void(0)" class="btn btn-default sp-up" title="Lên"><i class="fa fa-arrow-up"></i></a> '
					.'<a href="javascript:void(0)" class="btn btn-default sp-down" title="Xuống"><i class="fa fa-arrow-down"></i></a> '
					.'<a href="javascript:void(0)" class="btn btn-danger sp-del" title="Xoá"><i class="fa fa-trash"></i></a>'
				.'</div>'
			.'</div>'
			.'<div class="form-group">'
				.'<label class="col-form-label">Giới thiệu ngắn</label>'
				.'<textarea class="form-control sp-intro" name="sections['.$uid.'][intro]" rows="2" placeholder="Đoạn giới thiệu ngắn cho mục này">'.$intro.'</textarea>'
			.'</div>'
			.'<div class="form-group">'
				.'<label class="col-form-label">Nội dung</label>'
				.'<textarea id="sp_body_'.$uid.'" class="textarea_intro_editor sp-body" name="sections['.$uid.'][body]" style="width:100%">'.$body.'</textarea>'
			.'</div>'
		.'</div>'
	.'</div>';
	return $html;
}

# Danh sách trang tĩnh
function default_default(){
	global $assign_list,$mod,$act,$core,$clsModule,$clsISO;
	$assign_list["clsModule"] = $clsModule;

	# Lọc -> redirect kèm keyword
	if(isset($_POST['filter']) && $_POST['filter']=='filter'){
		$keyword = Input::post('keyword','');
		$link = '';
		if(!empty($keyword)) $link .= '&keyword='.urlencode($keyword);
		header('Location: '.PCMS_URL.'/?mod='.$mod.'&act='.$act.$link);
		exit();
	}

	$type_list = Input::get('type_list','');
	$keyword   = Input::get('keyword','');
	$assign_list["type_list"] = $type_list;
	$assign_list["keyword"]   = $keyword;

	$clsClassTable = new Page();
	$pkeyTable = $clsClassTable->pkey;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"]     = $pkeyTable;

	# Phạm vi: trang đã đánh dấu is_static HOẶC các slug tĩnh cũ
	$slugs  = static_page_legacy_slugs();
	$slugIn = "'".implode("','",array_map('addslashes',$slugs))."'";
	$cond   = '(more_information LIKE \'%"is_static":1%\' OR slug IN ('.$slugIn.'))';

	if(!empty($keyword)){
		$kwSafe = addslashes(trim($keyword));
		$kwSlug = addslashes($core->replaceSpace($keyword));
		$cond  .= " and (title like '%".$kwSafe."%' or slug like '%".$kwSlug."%')";
	}

	$cond2 = $cond;
	if($type_list=='Trash'){
		$cond .= " and is_trash=1";
	} else {
		$cond .= " and is_trash=0";
	}

	# Phân trang
	$record_per_page = 20;
	$current_page = (int) Input::get('page',1);
	if($current_page < 1) $current_page = 1;
	$total_record = $clsClassTable->countItem($cond);

	$link_page_current = '';
	$query_string = $_SERVER['QUERY_STRING'];
	$lst_query_string = explode('&',$query_string);
	for($i=0;$i<count($lst_query_string);$i++){
		$tmp = explode('=',$lst_query_string[$i]);
		if($tmp[0]!='page')
			$link_page_current .= ($link_page_current=='' ? '?' : '&').$lst_query_string[$i];
	}
	$config = array(
		'total'           => $total_record,
		'current_page'    => $current_page,
		'number_per_page' => $record_per_page,
		'link'            => PCMS_URL.'/index.php'.$link_page_current
	);
	$clsPagination = new Pagination();
	$clsPagination->initianize($config);
	$assign_list["html_pager"] = $clsPagination->create_links();

	$offset = ($current_page-1)*$record_per_page;
	$field  = "page_id,title,slug,intro,image,is_online,is_trash,reg_date,upd_date,more_information";
	$allItem = $clsClassTable->getAll($cond." order by upd_date desc, page_id desc limit ".$offset.",".$record_per_page, $field);

	# Bóc tách more_information cho hiển thị
	for($i=0;$i<count($allItem);$i++){
		$mi = $clsISO->to_array_json($allItem[$i]['more_information']);
		if(!is_array($mi)) $mi = array();
		$allItem[$i]['_subtitle'] = isset($mi['subtitle']) && $mi['subtitle']!=='' ? $mi['subtitle'] : $allItem[$i]['intro'];
		$lu = isset($mi['last_updated']) ? (int)$mi['last_updated'] : 0;
		if($lu<=0) $lu = (int)$allItem[$i]['upd_date'];
		$allItem[$i]['_last_updated'] = $lu;
		$allItem[$i]['_section_count'] = (isset($mi['sections']) && is_array($mi['sections'])) ? count($mi['sections']) : 0;
	}
	$assign_list["allItem"] = $allItem; unset($allItem);

	$pUrl = '';
	if(!empty($keyword)) $pUrl .= '&keyword='.urlencode($keyword);
	$assign_list["pUrl"] = $pUrl;

	$assign_list["number_trash"] = $clsClassTable->countItem("is_trash=1 and ".$cond2);
	$assign_list["number_item"]  = $clsClassTable->countItem("is_trash=0 and ".$cond2);
	$assign_list["number_all"]   = $clsClassTable->countItem($cond2);
}

# Thêm / sửa trang tĩnh
function default_edit(){
	global $assign_list,$mod,$act,$core,$clsModule,$clsISO,$dbconn;
	$assign_list["clsModule"] = $clsModule;
	$user_id = $core->_USER['user_id'];

	$clsClassTable = new Page();
	$pkeyTable = $clsClassTable->pkey;
	$assign_list["clsClassTable"] = $clsClassTable;
	$assign_list["pkeyTable"]     = $pkeyTable;

	$string = isset($_GET[$pkeyTable]) ? $_GET[$pkeyTable] : '';
	$pvalTable = intval($core->decryptID($string));
	$assign_list["pvalTable"] = $pvalTable;

	$oneItem = array();
	$more_information = array();
	if($pvalTable > 0){
		$oneItem = $clsClassTable->getOne($pvalTable);
		if(empty($oneItem)) $oneItem = array();
		$more_information = $clsISO->to_array_json(isset($oneItem['more_information']) ? $oneItem['more_information'] : '');
		if(!is_array($more_information)) $more_information = array();
	}

	# Lưu
	if(isset($_POST['submit']) && $_POST['submit']=='Update'){
		$title     = trim(strip_tags(isset($_POST['title']) ? $_POST['title'] : ''));
		$subtitle  = trim(strip_tags(isset($_POST['subtitle']) ? $_POST['subtitle'] : ''));
		$hero_icon = trim(strip_tags(isset($_POST['hero_icon']) ? $_POST['hero_icon'] : ''));
		$is_online = (int) Input::post('is_online',0);

		$lu = isset($_POST['last_updated']) ? trim($_POST['last_updated']) : '';
		$last_updated = $lu!=='' ? strtotime($lu) : time();
		if($last_updated===false || $last_updated<=0) $last_updated = time();

		# Đọc sections RAW từ $_POST để giữ nguyên HTML (Input::post sẽ xss-clean)
		$sections = array();
		if(isset($_POST['sections']) && is_array($_POST['sections'])){
			foreach($_POST['sections'] as $sec){
				$sIcon  = isset($sec['icon'])  ? trim(strip_tags($sec['icon']))  : '';
				$sTitle = isset($sec['title']) ? trim(strip_tags($sec['title'])) : '';
				$sIntro = isset($sec['intro']) ? trim(strip_tags($sec['intro'])) : '';
				$sBody  = isset($sec['body'])  ? trim($sec['body']) : '';
				if($sTitle==='' && $sIntro==='' && $sBody==='') continue;
				$sections[] = array('icon'=>$sIcon,'title'=>$sTitle,'intro'=>$sIntro,'body'=>$sBody);
			}
		}

		# Ảnh hero
		if(defined('_isoman_use') && _isoman_use){
			$image = Input::post('isoman_url_image','');
		} else {
			$image = Input::post('image_src','');
		}

		$more_information['is_static']    = 1;
		$more_information['subtitle']     = $subtitle;
		$more_information['last_updated'] = $last_updated;
		$more_information['hero_icon']    = $hero_icon;
		$more_information['sections']     = $sections;
		$miJson = json_encode($more_information, JSON_UNESCAPED_UNICODE);

		$slug = $core->replaceSpace($title);

		if($pvalTable > 0){
			$set = array(
				'title'           => $title,
				'slug'            => $slug,
				'intro'           => $subtitle,
				'image'           => $image,
				'is_online'       => $is_online,
				'more_information'=> $miJson,
				'upd_date'        => time(),
				'user_id_update'  => $user_id
			);
			$clsClassTable->updateOne($pvalTable,$set);
			if(isset($_POST['button']) && $_POST['button']=='_LIST'){
				header('Location: '.PCMS_URL.'/?mod='.$mod.'&message=updateSuccess');
			} else {
				header('Location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$string.'&message=updateSuccess');
			}
			exit();
		} else {
			$pvalTable = $clsClassTable->getMaxId();
			$data = array(
				$pkeyTable        => $pvalTable,
				'user_id'         => $user_id,
				'user_id_update'  => $user_id,
				'cat_id'          => 0,
				'title'           => $title,
				'slug'            => $slug,
				'intro'           => $subtitle,
				'content'         => '',
				'more_information'=> $miJson,
				'image'           => $image,
				'reg_date'        => time(),
				'upd_date'        => time(),
				'is_about_us'     => 0,
				'is_online'       => $is_online,
				'is_trash'        => 0
			);
			$clsClassTable->insert($data);
			if(isset($_POST['button']) && $_POST['button']=='_LIST'){
				header('Location: '.PCMS_URL.'/?mod='.$mod.'&message=insertSuccess');
			} else {
				header('Location: '.PCMS_URL.'/?mod='.$mod.'&act=edit&'.$pkeyTable.'='.$core->encryptID($pvalTable).'&message=insertSuccess');
			}
			exit();
		}
	}

	# Render
	$sections = (isset($more_information['sections']) && is_array($more_information['sections'])) ? $more_information['sections'] : array();
	$sections_html = '';
	if(!empty($sections)){
		foreach($sections as $sec){
			$sections_html .= static_page_section_html($clsISO->getUniqid(),$sec);
		}
	} else {
		$sections_html = static_page_section_html($clsISO->getUniqid(),array());
	}

	$lu = isset($more_information['last_updated']) ? (int)$more_information['last_updated'] : 0;

	$assign_list["oneItem"]          = $oneItem;
	$assign_list["more_information"] = $more_information;
	$assign_list["sections_html"]    = $sections_html;
	$assign_list["subtitle"]         = isset($more_information['subtitle']) ? $more_information['subtitle'] : (isset($oneItem['intro']) ? $oneItem['intro'] : '');
	$assign_list["hero_icon"]        = isset($more_information['hero_icon']) ? $more_information['hero_icon'] : '';
	$assign_list["last_updated"]     = $lu;
}

# AJAX: thêm 1 mục rỗng
function default_add_section(){
	global $clsISO;
	echo static_page_section_html($clsISO->getUniqid(),array());
	die();
}

# Bật/tắt hiển thị
function default_toggle(){
	global $mod,$core;
	$clsClassTable = new Page();
	$pkeyTable = $clsClassTable->pkey;
	$string = isset($_GET[$pkeyTable]) ? $_GET[$pkeyTable] : '';
	$pvalTable = intval($core->decryptID($string));
	if($pvalTable==0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		exit();
	}
	$cur = (int) $clsClassTable->getOneField('is_online',$pvalTable);
	$clsClassTable->updateOne($pvalTable,array('is_online'=>($cur ? 0 : 1)));
	header('Location: '.PCMS_URL.'/?mod='.$mod.static_page_ctx_url().'&message=updateSuccess');
	exit();
}

# Đưa vào thùng rác
function default_trash(){
	global $mod,$core;
	$clsClassTable = new Page();
	$pkeyTable = $clsClassTable->pkey;
	$string = isset($_GET[$pkeyTable]) ? $_GET[$pkeyTable] : '';
	$pvalTable = intval($core->decryptID($string));
	if($pvalTable==0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		exit();
	}
	$clsClassTable->updateOne($pvalTable,array('is_trash'=>1,'upd_date'=>time()));
	header('Location: '.PCMS_URL.'/?mod='.$mod.static_page_ctx_url().'&message=TrashSuccess');
	exit();
}

# Khôi phục
function default_restore(){
	global $mod,$core;
	$clsClassTable = new Page();
	$pkeyTable = $clsClassTable->pkey;
	$string = isset($_GET[$pkeyTable]) ? $_GET[$pkeyTable] : '';
	$pvalTable = intval($core->decryptID($string));
	if($pvalTable==0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		exit();
	}
	$clsClassTable->updateOne($pvalTable,array('is_trash'=>0,'upd_date'=>time()));
	header('Location: '.PCMS_URL.'/?mod='.$mod.static_page_ctx_url().'&message=RestoreSuccess');
	exit();
}

# Xoá vĩnh viễn
function default_delete(){
	global $mod,$core;
	$clsClassTable = new Page();
	$pkeyTable = $clsClassTable->pkey;
	$string = isset($_GET[$pkeyTable]) ? $_GET[$pkeyTable] : '';
	$pvalTable = intval($core->decryptID($string));
	if($pvalTable==0){
		header('Location: '.PCMS_URL.'/?mod='.$mod.'&message=notPermission');
		exit();
	}
	$clsClassTable->deleteOne($pvalTable);
	header('Location: '.PCMS_URL.'/?mod='.$mod.static_page_ctx_url().'&message=DeleteSuccess');
	exit();
}
?>
