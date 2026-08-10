<?php
function default_default(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting,$clsConfiguration;
    global $core,$clsModule,$clsButtonNav,$oneSetting,$clsISO;
    $assign_list["clsModule"]   = $clsModule;
    $user_id  = $core->_USER['user_id']; #
    $clsProperty = new Property();
	$clsCategory = new Category();
    $assign_list["clsCategory"] = $clsCategory;
	$assign_list['clsProperty'] = $clsProperty;
    
	$list_domains = $clsConfiguration->getValue('list_domains');
	$list_domains = $clsISO->to_array_json($list_domains);
	$assign_list['list_domains'] = $list_domains;
	/*Get cat_id */
    $cat_id                     = Input::get('cat_id',0);
    /*Get type of list news*/
    $type_list = Input::get('type_list');
	$domain = Input::get('domain', 'myoceancity.vn');
    $assign_list["cat_id"]      = $cat_id;
    $assign_list["type_list"]   = $type_list;
    $assign_list["domain"]   = $domain;
    if (isset($_POST['filter']) && $_POST['filter'] == 'filter') {
		$link = '';
		$domain = Input::post('domain', "");
		$cat_id  = (int) Input::post('cat_id',0);
		$keyword = Input::post('keyword');
		if(!empty($domain))
			$link .= '&domain=' . $domain;
        if ($cat_id > 0)
            $link .= '&cat_id=' . $cat_id;
        if (!empty($keyword))
            $link .= '&keyword=' . $keyword;
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $link);
		exit();
    }
    $classTable                   = "FAQ";
    $clsClassTable                = new $classTable;
    $tableName                    = $clsClassTable->tbl;
    $pkeyTable                    = $clsClassTable->pkey;
    $assign_list["clsClassTable"] = $clsClassTable;
    $assign_list["pkeyTable"]     = $pkeyTable;
    /*List all item*/
    $cond                         = "1='1'";
    #Filter By Keyword    
    $keyword                      = Input::get('keyword');
    $assign_list["keyword"]       = $keyword;
	if(!empty($domain)) {
		$cond .= " and `domain`='".$domain."'";
		$pUrl .= '&domain=' . $domain;
	}
    if (!empty($keyword)) {
        $keyword = $core->replaceSpace($keyword);
        $cond .= " and slug like '%" . $keyword . "%'";
    }
    if ($cat_id > 0) {
        $pUrl .= '&cat_id=' . $cat_id;
        $cond .= " and cat_id = '" . $cat_id . "'";
    }
    $assign_list["pUrl"] = $pUrl;
    $cond2               = $cond;
    if ($type_list == 'Active') {
        $cond .= " and is_trash=0";
    }
    if ($type_list == 'Trash') {
        $cond .= " and is_trash=1";
    }
    $orderBy                      = " order_no desc";
    #-------Page Divide---------------------------------------------------------------    
    $recordPerPage                = 20;
    $currentPage                  = isset($_GET["page"]) ? $_GET["page"] : 1;
    $start_limit                  = ($currentPage - 1) * $recordPerPage;
    $limit                        = " limit $start_limit,$recordPerPage";
    $lstAllItem                   = $clsClassTable->getAll($cond);
//	$clsISO->print_pre($lstAllItem);die;
    $totalRecord                  = (is_array($lstAllItem) && count($lstAllItem) > 0) ? count($lstAllItem) : 0;
    $totalPage                    = ceil($totalRecord / $recordPerPage);
    $assign_list['totalRecord']   = $totalRecord;
    $assign_list['recordPerPage'] = $recordPerPage;
    $assign_list['totalPage']     = $totalPage;
    $assign_list['currentPage']   = $currentPage;
    $listPageNumber               = array();
    for ($i = 1; $i <= $totalPage; $i++) {
        $listPageNumber[] = $i;
    }
    $assign_list['listPageNumber'] = $listPageNumber;
    $query_string                  = $_SERVER['QUERY_STRING'];
    $lst_query_string              = explode('&',$query_string);
    $link_page_current             = '';
    for ($i = 0; $i < count($lst_query_string); $i++) {
        $tmp = explode('=',$lst_query_string[$i]);
        if ($tmp[0] != 'page')
            $link_page_current .= ($i == 0) ? '?' . $lst_query_string[$i] : '&' . $lst_query_string[$i];
    }
    $assign_list['link_page_current'] = $link_page_current;
    #    
    $link_page_current_2              = '';
    for ($i = 0; $i < count($lst_query_string); $i++) {
        $tmp = explode('=',$lst_query_string[$i]);
        if ($tmp[0] != 'page' && $tmp[0] != 'type_list')
            $link_page_current_2 .= ($i == 0) ? '?' . $lst_query_string[$i] : '&' . $lst_query_string[$i];
    }
    $assign_list['link_page_current_2'] = $link_page_current_2;
    #-------End Page Divide-----------------------------------------------------------    
    $allItem                            = $clsClassTable->getAll($cond . " order by " . $orderBy . $limit);
	foreach($allItem as $k => $val){
		$tags = $val['tags'];
		$list_tags = !empty($tags) ? explode(',',$tags) : array();
		$allItem[$k]['list_tags'] = $list_tags;
		unset($list_tags);
	}
//	var_dump($allItem);die;
    //print_r($cond." order by ".$orderBy.$limit);die();    
    $assign_list["allItem"]             = $allItem; #    
    $assign_list["number_trash"]        = $clsClassTable->countItem("is_trash=1 and " . $cond2);
    $assign_list["number_item"]         = $clsClassTable->countItem("is_trash=0 and " . $cond2);
    $assign_list["number_all"]          = $clsClassTable->countItem($cond2);
}
function default_edit(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$_LANG_ID,$act,$menu_current,$current_page,$oneSetting;
    global $core,$clsModule,$clsButtonNav,$dbconn,$clsConfiguration,$clsSetting;
	$clsCategory = new Category();
    $user_id  = $core->_USER['user_id'];
    $cat_id = (int) Input::get('cat_id',0);
    $domain = Input::get('domain',"myoceancity.vn");
	$assign_list["clsModule"] = $clsModule;
	$assign_list["domain"]   = $domain;
	$assign_list["clsCategory"]   = $clsCategory;
	#
    $classTable = "FAQ";
    $clsClassTable                = new $classTable;
    $tableName                    = $clsClassTable->tbl;
    $pkeyTable                    = $clsClassTable->pkey;
    $assign_list["clsClassTable"] = $clsClassTable; #    
	#
    $oneItem = array();
    $pvalTable  = isset($_GET[$pkeyTable]) ? intval($_GET[$pkeyTable]) : 0;
    if ($pvalTable > 0) {
        $oneItem = $clsClassTable->getOne($pvalTable);
        $cat_id  = $oneItem['cat_id'];
    }
    $clsSetting = new Setting();
	$clsProperty = new Property();
	$assign_list['clsProperty'] = $clsProperty;
	$assign_list['clsSetting'] = $clsSetting;
	$assign_list['typeArrProperty'] = $typeArrProperty;
    $assign_list['pvalTable'] = $pvalTable;
    $assign_list["oneItem"]   = $oneItem;
    $assign_list["cat_id"]    = $cat_id;
	#
	$pUrl  = '&domain=' . $domain;
	if(!empty($cat_id)){
		$pUrl  .= '&cat_id=' . $cat_id;
	}
    $assign_list["pUrl"]    = $pUrl;
	#
    require_once DIR_COMMON . "/Form.php";
    $clsForm = new Form();
    $clsForm->setDbTable($tableName,$pkeyTable,$pvalTable);
    $assign_list["clsForm"] = $clsForm;
    $clsForm->addInputTextArea("full",'content',"",'content',255,25,10,1,"style='width:100%'"); #=========================================#    
    if (isset($_POST['submit']) && $_POST['submit'] == 'Update') {
		$tags = Input::post('tags');
        $cat_id = (int) Input::post('iso-cat_id',0);
        $pUrl   = '&cat_id=' . $cat_id;
        $pUrl   .= '&domain=' . $domain;
        if ($pvalTable > 0) {
            $set      = "";
            $firstAdd = 0;
            foreach ($_POST as $key => $val) {
                $tmp = explode('-',$key);
                if ($tmp[0] == 'iso') {
                    if ($firstAdd == 0) {
                        $set .= $tmp[1] . "='" . addslashes($val) . "'";
                        $firstAdd = 1;
                    } else {
                        $set .= "," . $tmp[1] . "='" . addslashes($val) . "'";
                    }
                }
            } #    
			
            $set .= ",tags='" . $tags . "'";
            $set .= ",upd_date='" . time() . "'";
            $set .= ",user_id_update='" . $user_id . "'";
            $set .= ",slug='" . $core->replaceSpace(Input::post('iso-title')) . "'"; # 
            $set .= ",domain='" . $domain . "'"; # 
            if ($clsClassTable->updateOne($pvalTable,$set)) {
                if ($_POST['button'] == '_EDIT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=edit&faq_id=' . $pvalTable . $pUrl . '&message=updateSuccess');
                } else if ($_POST['button'] == '_CAT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=updateSuccess');
                } else {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=updateSuccess');
                }
            } else {
                header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=updateFailed');
            }
        } else {
            $value    = "";
            $firstAdd = 0;
            $field    = "";
            foreach ($_POST as $key => $val) {
                $tmp = explode('-',$key);
                if ($tmp[0] == 'iso') {
                    if ($firstAdd == 0) {
                        $field .= $tmp[1];
                        $value .= "'" . addslashes($val) . "'";
                        $firstAdd = 1;
                    } else {
                        $field .= ',' . $tmp[1];
                        $value .= ",'" . addslashes($val) . "'";
                    }
                }
            } #            
            $pvalTable = $clsClassTable->getMaxID();
            $field .= ",user_id,user_id_update,reg_date,upd_date,slug,{$pkeyTable},order_no,tags";
            $value .= ",'" . addslashes($user_id) . "','" . addslashes($user_id) . "','" . time() . "','" . time() . "'";
            $value .= ",'" . $core->replaceSpace($_POST['iso-title']) . "','" . $pvalTable . "','" . $clsClassTable->getMaxOrderNo() . "','".$tags."'";
			$field .=",domain";
			$value .= ",'".$domain."'";
            if ($clsClassTable->insertOne($field,$value)) {
                if ($_POST['button'] == '_EDIT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . '&act=edit&' . $pkeyTable . '=' . $pvalTable . $pUrl . '&message=updateSuccess');
                } else if ($_POST['button'] == '_CAT') {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=updateSuccess');
                } else {
                    header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=insertSuccess');
//                    header('location: ' . PCMS_URL . '/?mod=' . $mod .'&act=edit'. $pUrl . '&message=insertSuccess');
                }
            } else {
                header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=insertFailed');
            }
        }
    }
}
function default_search_tag(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page
	,$keyword_page,$extLang,$clsISO;
	$clsProperty = new Property();
	$clsFAQ = new FAQ();
	
	$results = array();
	$list_props = $clsFAQ->getAll("`is_trash`=0 and `tags`<>''","tags");
	// $clsISO->print_pre($list_props); die();
	if(!empty($list_props)){
		foreach($list_props as $key => $val){
			$tags = $val['tags'];
			$arr_tags = @explode(',',$tags);
			foreach($arr_tags as $tag){
				$results[] = array(
					'id' => $tag,
					'text' => $tag
				);
			}
		}
		unset($list_props);
	}
	// Return
	echo json_encode($results); die();
}
function default_trash(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
    global $core,$clsModule,$clsButtonNav,$oneSetting;
    $user_id       = $core->_USER['user_id']; #    
    $classTable    = "FAQ";
    $clsClassTable = new $classTable;
    $tableName     = $clsClassTable->tbl;
    $pkeyTable     = $clsClassTable->pkey;
    $pvalTable     = isset($_GET[$pkeyTable]) ? intval($_GET[$pkeyTable]) : 0;
    $cat_id        = (int) Input::get('cat_id',0);
    $pUrl          = '';
    if (intval($cat_id) != 0) {
        $pUrl .= '&cat_id=' . $cat_id;
    }
    if ($pvalTable == 0)
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=notPermission');
    if ($clsClassTable->updateOne($pvalTable,"is_trash='1'")) {
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=TrashSuccess');
    }
}
function default_restore(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
    global $core,$clsModule,$clsButtonNav,$oneSetting;
    $user_id       = $core->_USER['user_id']; #    
    $classTable    = "FAQ";
    $clsClassTable = new $classTable;
    $tableName     = $clsClassTable->tbl;
    $pkeyTable     = $clsClassTable->pkey;
    $pvalTable     = isset($_GET[$pkeyTable]) ? intval($_GET[$pkeyTable]) : 0;
    $cat_id        = (int) Input::get('cat_id',0);
    $pUrl          = '';
    if (intval($cat_id) != 0) {
        $pUrl .= '&cat_id=' . $cat_id;
    }
    if ($pvalTable == 0)
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=notPermission');
    if ($clsClassTable->updateOne($pvalTable,"is_trash='0'")) {
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=RestoreSuccess');
    }
}
function default_delete(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
    global $core,$clsModule,$clsButtonNav,$oneSetting;
    $user_id       = $core->_USER['user_id']; #    
    $classTable    = "FAQ";
    $clsClassTable = new $classTable;
    $tableName     = $clsClassTable->tbl;
    $pkeyTable     = $clsClassTable->pkey;
    $pvalTable     = isset($_GET[$pkeyTable]) ? intval($_GET[$pkeyTable]) : 0;
    $cat_id        = (int) Input::get('cat_id',0);
    $pUrl          = '';
    if (intval($cat_id) != 0) {
        $pUrl .= '&cat_id=' . $cat_id;
    }
    if ($pvalTable == 0)
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=notPermission');
    if ($clsClassTable->deleteOne($pvalTable)) {
        header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=DeleteSuccess');
    }
}
function default_move(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act;
    global $core,$clsModule,$clsButtonNav,$oneSetting;
    $user_id       = $core->_USER['user_id']; #    
    $classTable    = "FAQ";
    $clsClassTable = new $classTable;
    $tableName     = $clsClassTable->tbl;
    $pkeyTable     = $clsClassTable->pkey; #    
    $cat_id        = (int) Input::get('cat_id',0);
    $direct        = Input::get('direct','up');
    $pvalTable     = isset($_GET[$pkeyTable]) ? intval($_GET[$pkeyTable]) : 0;
    if ($pvalTable == 0 || $direct == '') {
        header('location: ' . PCMS_URL . '/?mod=' . $mod);
    }
    $one      = $clsClassTable->getOne($pvalTable);
    $order_no = $one['order_no'];
    $pUrl     = '';
    $where    = 'is_trash=0 ';
    if (intval($cat_id) > 0) {
        $pUrl .= '&cat_id=' . $cat_id;
        $where .= " and cat_id=" . $cat_id;
    }
    if ($direct == 'movedown') {
        $lst = $clsClassTable->getAll($where . " and order_no < $order_no order by order_no desc limit 0,1");
        $clsClassTable->updateOne($pvalTable,"order_no='" . $lst[0]['order_no'] . "'");
        $clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='" . $order_no . "'");
    }
    if ($direct == 'moveup') {
        $lst = $clsClassTable->getAll($where . " and order_no > $order_no order by order_no asc limit 0,1");
        $clsClassTable->updateOne($pvalTable,"order_no='" . $lst[0]['order_no'] . "'");
        $clsClassTable->updateOne($lst[0][$clsClassTable->pkey],"order_no='" . $order_no . "'");
    }
    if ($direct == 'movebottom') {
        $lst = $clsClassTable->getAll($where . " and order_no < $order_no order by order_no desc");
        $clsClassTable->updateOne($pvalTable,"order_no='" . $lst[count($lst) - 1]['order_no'] . "'");
        $lstItem = $clsClassTable->getAll($where . " and $pkeyTable <> '$pvalTable' and order_no < $order_no order by order_no asc");
        for ($i = 0; $i < count($lstItem); $i++) {
            $clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='" . ($lstItem[$i]['order_no'] + 1) . "'");
        }
    }
    if ($direct == 'movetop') {
        $lst = $clsClassTable->getAll($where . " and order_no > $order_no order by order_no asc");
        $clsClassTable->updateOne($pvalTable,"order_no='" . $lst[count($lst) - 1]['order_no'] . "'");
        $lstItem = $clsClassTable->getAll($where . " and $pkeyTable <> '$pvalTable' and order_no > $order_no order by order_no asc");
        for ($i = 0; $i < count($lstItem); $i++) {
            $clsClassTable->updateOne($lstItem[$i][$clsClassTable->pkey],"order_no='" . ($lstItem[$i]['order_no'] - 1) . "'");
        }
    }
    header('location: ' . PCMS_URL . '/?mod=' . $mod . $pUrl . '&message=PositionSuccess');
}
/*============ SITE FAQS CATEGORY ============*/
function default_cat(){
    global $assign_list,$_CONFIG,$_SITE_ROOT,$mod,$act,$clsConfiguration;
    global $core,$clsModule,$clsButtonNav,$oneSetting,$_LANG_ID,$clsISO;
    $user_id                      = $core->_USER['user_id'];
    $assign_list["msg"]           = isset($_GET['message']) ? $_GET['message'] : ''; #    
    $classTable                   = "Category";
    $clsClassTable                = new $classTable;
    $tableName                    = $clsClassTable->tbl;
    $pkeyTable                    = $clsClassTable->pkey;
    $assign_list["clsClassTable"] = $clsClassTable; #    
    $type                         = '_FAQs';
    $assign_list["type"]          = $type; #    
    $domain_id                    = Input::get('domain_id',DOMAIN_ID);
    $assign_list["domain_id"]     = $domain_id;
}
?>