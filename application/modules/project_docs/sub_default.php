<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

/*======================================================================*\

|| #################################################################### ||

|| # The Classes configurations of the MaxxCMS                        # ||

|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||

|| # ---------------------------------------------------------------- # ||

|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||

|| # This file may not be redistributed in whole or significant part. # ||

|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||

|| #################################################################### ||

\*======================================================================*/

function default_default() {

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$title_page,$description_page,$oneProfile,$list_projects;

	$clsCache = new Cache();

	$clsSetting = new Setting();

	$clsProperty = new Property();

	$clsTag = new Tag();

	$clsProject = new Project();

	$clsProjectMeta = new ProjectMeta();

	$assign_list["clsTag"] = $clsTag;

	$assign_list["clsProjectMeta"] = $clsProjectMeta;

    ## Dữ liệu payload

	$allGet = Input::get();

	$tag_id = Input::get('tag_id',0);

	$cat_id = (int)Input::get('cat_id',0);

	$current_page = (int) Input::get('page',1);

	$project_filter = Input::get('project_filter','');

	$keyword = Input::get('keyword','');

	#

	$more_information = $oneProfile['more_information'];

	$doc_bookmarked = $core->get_field($more_information, "doc_bookmarked", 0);

	$arr_project_care = $core->get_field($more_information, "project_care", []);

	// $clsISO->print_pre($arr_project_care); die();

	$assign_list["doc_bookmarked"] = $doc_bookmarked;

	$assign_list["arr_project_care"] = $arr_project_care;

	#- Preloader

	$list_preloaders = array();

	for($i=0; $i<30; $i++){

		$list_preloaders[] = $i;

	}

	$assign_list['list_preloaders'] = $list_preloaders;

	## lấy dữ liệu dạng GET

	$query_string = [];

	$array_field_accept_query = ['page', 'cat_id', 'keyword', 'tag_id', 'project_filter'];

	if (!empty($allGet)) {

		foreach($allGet as $field => $field_get) {

			if (in_array($field, $array_field_accept_query)) {

				$query_string[$field] = $field_get;

			}

		}

	}

	$scriptJs = '<script>

		var params = '.json_encode($query_string).';

	</script>';

	$assign_list['scriptJs'] = $scriptJs;

	## danh mujc tài liệu

	$condListCat = "`is_trash`=0 AND `property_type`='_CATEGORY_DOCS'";

	$fieldListCat = "{$clsProperty->pkey},property_code,title, slug, parent_id";

	$arrListCat = $clsProperty->getAll($condListCat. " order by `order_no` ASC", $fieldListCat);

	$arrListCat = !empty($arrListCat) ? array_combine( array_column($arrListCat, 'property_id'), $arrListCat ) : [];

	$assign_list['arrListCat'] = $arrListCat;

	## dữ liệu tag theo project_id

    if (!empty($arr_project_care)) {

        $condListDocByProject = "`is_trash`=0";

		$arrCondProject = [];

		foreach ($arr_project_care as $projectItemId) {

			$arrCondProject[] = " `project_id`={$projectItemId}";

		}

		$condListDocByProject.= " AND (" . implode(" OR ", $arrCondProject) . ")";

        // $clsISO->print_pre($condListDocByProject); die();

        $tags_by_project = $clsProjectMeta->getAll("{$condListDocByProject}", "tags_slug,tags");

        if (!empty($tags_by_project)) {

            foreach ($tags_by_project as $key => $value) {

                $nameArr = explode(',', $value['tags']);

                $slugArr = array_values(array_filter(explode('|', $value['tags_slug'])));

                foreach ($slugArr as $index => $slug) {

                    $all_tags[$slug] = $nameArr[$index] ?? '';

                }

            }

        }

    } else {

        $arrTagDefault = unserialize(_ARR_TAGS_DEFAULT);

	    $all_tags = $arrTagDefault;

    }

	$assign_list['arrTagDefault'] = $all_tags;

	$assign_list['current_page'] = $current_page;

	$assign_list['keyword'] = $keyword;

	$assign_list['cat_id'] = $cat_id;

	$assign_list['project_filter'] = $project_filter;

	## project_filter

    $project_property = !empty($project_filter) ? @explode('_', $project_filter) : [];

    $assign_list['project_property'] = $project_property;	

	/*=============Title & Description Page==================*/

	$title_page = 'Kho tài liệu - ' . PAGE_NAME;

	$assign_list["title_page"] = $title_page;

	$description_page = $title_page;

	$assign_list["description_page"] = $description_page;

	$keyword_page = $description_page;

	$assign_list["keyword_page"] = $keyword_page;

}

function default_list_docs() {

	global $assign_list,$smarty,$_CONFIG,$core,$dbconn,$mod,$act,$clsConfiguration,$clsISO,$profile_id,$oneProfile,$list_projects;

	$clsCache = new Cache();

	$clsSetting = new Setting();

	$clsProperty = new Property();

	$clsTag = new Tag();

	$clsProfile = new Profile();

	$clsProject = new Project();

	$clsProjectMeta = new ProjectMeta();

	$assign_list["clsTag"] = $clsTag;

	$assign_list["clsProjectMeta"] = $clsProjectMeta;

    ## Input

	$tag_id = Input::post('tag_id',0);

	$keySearch= Input::post('keyword',"");

	$cat_id = (int) Input::post('cat_id',0);

	$project_id = Input::post('project_id',0);

	$project_filter = Input::post('project_filter',0);

	$current_page = (int) Input::post('page',1);

	$per_page = (int) Input::post('per_page',30);



    ## danh sách file đã lưu trữ theo cá nhân

	$more_information = $oneProfile['more_information'];

	$project_care = $core->get_field($more_information, "project_care", []);

	$doc_bookmarked = (int) $core->get_field($more_information, "doc_bookmarked", 0);

	$list_bookmarked = $core->get_field($more_information, "list_bookmarked", []);

	// $clsISO->print_pre($list_bookmarked); die();

	$cond = "`is_trash`=0";

    ## project_filter

    $project_property = [];

    $is_search_filter_project = false;

    if (!empty($project_filter)) {

        $project_property = is_array($project_filter) ? $project_filter : explode('_', $project_filter);

        if (count($project_property) == 2) {

            if ($project_property[0] == 'block') {

                $cond.= " and `block_ids` like '%|{$project_property[1]}|%'";

            } else if($project_property[0] == 'building') {

                $cond.= " and `building_ids` like '%|{$project_property[1]}|%'";

            } else if($project_property[0] == 'project') {

                $cond.= " and `project_id` = '{$project_property[1]}'";

                $is_search_filter_project = true;

            }

        }

    }

    ## condition theo project_id

    $arrProject = [];

    if(!empty($project_id)) {

        $arrCondProject = [];

        $arrProject = is_array($project_id) ? $project_id : explode(',', $project_id);

		foreach ($arrProject as $projectItemId) {

		   $arrCondProject[] = " project_id = {$projectItemId}";

		}

		$cond.= " AND (" . implode(" OR ", $arrCondProject) . ")";

	}

    $condListDocByProject = $cond;

    ## condition theo từ khóa tìm kiếm

	if(!empty($keySearch)) {

		$cond.= " and ( `title` like '%{$keySearch}%' or tags like '%{$keySearch}%' )";

	}

    ## condition theo tag_id

	if(!empty($tag_id)) {

		$arrConds = [];

		$arrTag = @explode(',', $tag_id);

		foreach($arrTag as $tagItem) {

			$arrConds[] .= " `tags_slug` like '%|{$tagItem}|%'";

		}

		$cond .= " AND (" . implode(" OR ", $arrConds) . ")";

	}

    ## condition theo yêu thích

	if($doc_bookmarked == 1){

		 $stringDocIds = implode(',', $list_bookmarked);

		 $cond .= " AND `id` IN ({$stringDocIds})";

	}

	$condGroupBy = $cond;

    ## condition theo danh mục

	if(!empty($cat_id)) {

		$cond.= " and (`cat_id`='{$cat_id}' or LOWER(`list_cat_id`) like LOWER('%|{$cat_id}|%'))";

	}

    ## đếm số lượng file theo danh mục

	$count_document = $clsProjectMeta->getAll("{$condGroupBy} GROUP BY cat_id", "cat_id, COUNT(*) AS total_document");

	$count_document = !empty($count_document) ? array_combine(array_map(function($query) {

		return $query['cat_id'];

	},$count_document), array_values($count_document)) : [];

	$assign_list["count_document"] = $count_document;

    ## xử lý phần danh mục

	$condListCat = "`is_trash`=0 AND `property_type`='_CATEGORY_DOCS'";

	$fieldListCat = "{$clsProperty->pkey},property_code,title,slug,parent_id";

	if($clsCache->has('_CATEGORY_DOCS_cached')){

		$arrListCat = $clsCache->get('_CATEGORY_DOCS_cached');

	} else {

		$arrListCat = $clsProperty->getAll($condListCat. " order by `order_no` ASC", $fieldListCat);

		$clsCache->put('_CATEGORY_DOCS_cached', json_encode($arrListCat, JSON_UNESCAPED_UNICODE), 60*60);

	}

	$arrListCat = !empty($arrListCat) ? array_combine( array_column($arrListCat, 'property_id'), $arrListCat ) : [];

	$arrListCatTree = !empty($arrListCat) ? $clsProjectMeta->buildTree($arrListCat, 0, array('list_docs' => $count_document)) : [];

	$htmlCategory = !empty($arrListCatTree) ? $clsProjectMeta->renderMenu($arrListCatTree, false, $cat_id) : "";

	$assign_list['arrListCat'] = $arrListCat;

	$assign_list['htmlCategory'] = $htmlCategory;

    ## xỷ lys phần danh sách tài liệu

	$total_record = $clsProjectMeta->countItem($cond);

	$total_page = @ceil($total_record/$per_page);

	$offset = ($current_page-1)*$per_page;

	$limitCond = " limit {$offset},{$per_page}";

	###

	$list_docs = $clsProjectMeta->getAll("{$cond} order by `reg_date` DESC".$limitCond);

	if(!empty($list_docs)){

		$arr_profile_cached = array();

		foreach($list_docs as $key => $val){

			$doc_id = $val[$clsProjectMeta->pkey];

			$cat_id = $val['cat_id'];

			$more_information = $val['more_information'];

			$more_information = $clsISO->to_array_json($more_information);

			$list_docs[$key]['more_information'] = $more_information;

			$list_files = $core->get_field($more_information, "list_files", []);

			

			$list_images = [];

			if(!empty($list_files)){ $kk = 0;

				if(count($list_files) > 1){

					foreach($list_files as $nkey => $nval){

						if($clsISO->isFileVideo($nval)){

							$_type = 'video';

						} else if($clsISO->isPDF($nval)){

							$_type = 'pdf';

						} else {

							$_type = 'google.file';

						}

						// Nếu ảnh đầu tiên là video

						if($kk == 0 && in_array($_type, array('video', 'pdf'))){

							$list_docs[$key]['type'] = $_type;

							$list_docs[$key]['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'));

						} else {

							$list_docs[$key]['type'] = $more_information['type'];

							$list_docs[$key]['link'] = $clsISO->genGoogleURL($nkey, 'view');

						}

						$list_docs[$key]['link_download'] = $clsISO->genGoogleURL($more_information['gg_id'], 'download');

						$kk>0 && $list_images[] = array(

							'gid' => $nkey,

							'type' => $_type,

							'image' =>  $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'))

						);

						++$kk;

					}

				} else {

					foreach($list_files as $nkey => $nval){

						if($clsISO->isFileVideo($nval)){

							$_type = 'video';

						} else if($clsISO->isPDF($nval)){

							$_type = 'pdf';

						} else {

							$_type = 'google.file';

						}

						$list_docs[$key]['type'] = $_type;

						$list_docs[$key]['link_download'] = $clsISO->genGoogleURL($nkey, 'download');

						$list_docs[$key]['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'));

					}

				}

			} else {

				$list_docs[$key]['type'] = $more_information['type'];

				$list_docs[$key]['link_download'] = $clsISO->getDownloadURL($val['content']);

				if($more_information['type'] == 'pdf'){

					$link_file = $clsISO->getIframeUrl($val['content']);

				} else {

					if ($clsISO->checkContainer($val['content'], 'drive.google.com', "")) {

						$parts = parse_url($val['content']);

						if (isset($parts['query'])) {

							parse_str($parts['query'], $query);

							if (isset($query['usp']) && $query['usp'] === 'drive_link') {

								$link_file = $clsISO->getIframeUrl($val['content']);

							} else {

								$link_file = $clsISO->getIframeUrl($val['content']);

							}

						} else {

							$link_file = $clsISO->getIframeUrl($val['content']);

						}

					} else {

						$link_file = $clsISO->getGoogleUrl($val['content']);

					}

				}

				$list_docs[$key]['link'] = $link_file;

			}

			$list_docs[$key]['list_images'] = $list_images;

			$list_docs[$key]['image'] = $core->get_field($more_information, "image", "");

			$list_docs[$key]['is_save'] = $clsISO->checkItemInArray($doc_id, $list_bookmarked) ? 1 : 0;

			$list_docs[$key]['cat_name'] = isset($arrListCat[$cat_id]) ? $arrListCat[$cat_id]['title'] : null;

		}

	}

	// $clsISO->print_pre($list_docs); die();

	$smarty->assign('list_docs', $list_docs);

    ## Xử lý phần giao diện tag theo project_id

    $tags_by_project = $all_tags = [];

    if (!empty($project_id)) {

        $tags_by_project = $clsProjectMeta->getAll("{$condListDocByProject}", "`tags_slug`,`tags`");

        if (!empty($tags_by_project)) {

            foreach ($tags_by_project as $key => $value) {

                $nameArr = explode(',', $value['tags']);

                $slugArr = array_values(array_filter(explode('|', $value['tags_slug'])));

                foreach ($slugArr as $index => $slug) {

                    $all_tags[$slug] = $nameArr[$index] ?? '';

                }

            }

        }

    } else {

        $arrTagDefault = unserialize(_ARR_TAGS_DEFAULT);

	    $all_tags = $arrTagDefault;

    }

	## tags

	$filter_tags = !empty($list_docs) ? array_column($list_docs, 'tags_slug', 'tags') : [];

	if (!empty($filter_tags)) {

		foreach ($filter_tags as $names => $slugs) {

			$nameArr = explode(',', $names);

			$slugArr = array_values(array_filter(explode('|', $slugs)));

			foreach ($slugArr as $index => $slug) {

				$all_tags[$slug] = $nameArr[$index] ?? '';

			}

		}

	}

    $htmlTags = '';

    foreach ($all_tags as $slugTag => $tagName) {

        $activeTag = $clsISO->checkItemInArray($slugTag,$arrTag) ? 'active' : '';

        $htmlTags .= '<a class="tag_document_item tag_document_item_'.$slugTag.' '.$activeTag.'" href="javascript:void(0);" onClick="$Core.document.select_tag(this, event);" data-tag-id="'.$slugTag.'">'.$tagName.'</a>';

    }

	$assign_list["tag_id"] = $tag_id;

    ## Xử lý phần danh dách sự án

	$arr_project = $list_projects = array();

	if($clsCache->has('_ca_SR_project_cached')){

		$list_projects = $clsCache->get('_ca_SR_project_cached');

		// $clsISO->print_pre($list_projects); die();

	}

	if(!empty($list_projects)){

		foreach($list_projects as $item_project) {

			if(!empty($item_project['project_id']) && $clsISO->checkItemInArray($item_project['project_id'], $arrProject)) {

				$arr_project[] = $item_project;

			}

		}

	}

	$htmlProject = '';

	if (!empty($arr_project)) {

		$typeProjectPropertyFilter = !empty($project_property[0]) ? $project_property[0] : null;

		$idProjectPropertyFilter = !empty($project_property[1]) ? $project_property[1] : 'project';

		$htmlProject = $clsProjectMeta->renderhtmlProject($arr_project, false, $idProjectPropertyFilter, $typeProjectPropertyFilter);

	} else {

		$htmlProject = '<div class="text-muted p-2 border border-dashed rounded-2 text-center py-4">

			<i class="bx bx-hdd fs-2 mb-1"></i>

			<div class="clearfix"></div>

			<small>Chọn các dự án bạn quan tâm</small>

		</div>';

	}

    $list_project_by_key_id = !empty($list_projects) ? array_column($list_projects, null, 'project_id') : [];

    $assign_list["list_project_by_key_id"] = $list_project_by_key_id;

	// Return

	$html = $core->build('_ajax.docs.tpl');

	echo json_encode(array(

		'html' => $html,

		'total_page' => $total_page,

		'total_record' => $total_record,

		'current_page' => $current_page,

		'per_page' => $per_page,

		'htmlCategory' => $htmlCategory,

		'htmlProject' => $htmlProject,

        'htmlTags' => $htmlTags

	)); die();

}

function default_set_bookmarked(){

	global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$profile_id,$oneProfile;

	$clsProfile = new Profile();

	

	$msg = "_error";

	$doc_bookmarked = (int) Input::post('doc_bookmarked', 0);

    $more_information = $oneProfile["more_information"];

    $more_information['doc_bookmarked'] = $doc_bookmarked;

	// $clsISO->print_pre($more_information); die();

	if($clsProfile->updateOne($profile_id, array(

		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

	))){

		$msg = "_success";

	}

	// Return

	echo json_encode(array(

		'msg' => $msg

	)); die();

}

function default_store_project_setting(){

	global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$profile_id,$oneProfile;

	$clsProfile = new Profile();

	

	$msg = "_error";

	$project_care = Input::post('project_care', []);

	## Xử lý phần dự án quan tâm

    $more_information = $oneProfile["more_information"];

    $more_information['project_care'] = $project_care;

	// $clsISO->print_pre($more_information); die();

	if($clsProfile->updateOne($profile_id, array(

		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

	))){

		$msg = "_success";

	}

	// Return

	echo json_encode(array(

		'msg' => $msg

	)); die();

}

function default_bookmarked(){

	global $assign_list,$mod,$act,$core,$dbconn,$clsISO,$profile_id,$oneProfile;

	$clsProfile = new Profile();

	###

	$result = [ "result" => false, "msg" => "error"];

	$doc_id = (int) Input::post('doc_id', 0);

	$sheet_id = Input::post('sheet_id', "");

	$action = Input::post('action', "save");

	$more_information = $oneProfile["more_information"];

	$list_bookmarked = $core->get_field($more_information, "list_bookmarked", []);

	if($action == "save") {

		$list_bookmarked[] = $doc_id;

	}else{

		if(in_array($list_bookmarked, $doc_id)){

			$index = array_search($doc_id, $list_bookmarked); // tìm vị trí

			array_splice($list_bookmarked, $index, 1); // xoá tại index vừa tìm

		}

	}

	// $clsISO->print_pre($list_bookmarked); die();

	$more_information["list_bookmarked"] = $list_bookmarked;

	if($clsProfile->updateOne($profile_id,array(

		'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

	))) {

		$result = [

			"result" => true,

			"msg" => "Lưu thành công"

		];

	}

	// Return

	echo json_encode($result); die();

}