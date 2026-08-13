<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

/*======================================================================*\

|| #################################################################### ||

|| # The Classes configurations of the MaxxCMS                        # ||

|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||

|| # ---------------------------------------------------------------- # ||

|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||

|| # This file may not be redistributed in whole or significant part. # ||

|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||

|| #################################################################### ||

\*======================================================================*/

class ProjectMeta extends dbBasic{

	function __construct(){

		global $_LANG_ID;

		$this->pkey = "id";

		$this->tbl = DB_PREFIX."project_meta";

	}

	function get_files($service, $folderId, $path = '') {

		$resultArray = [];

		$results = $service->files->listFiles([

			'orderBy' => "name",

			'q' => "'".$folderId."' in parents",

			'corpora' => "allDrives",

			'supportsAllDrives' => 'true',

			'includeItemsFromAllDrives' => 'true'

		]);

		// return $results;

		$files = $results->getFiles();

		foreach ($files as $file) {

			$filePath = $path . '/' . $file->getName();

			if ($file->mimeType == 'application/vnd.google-apps.folder') {

				$resultArray = array_merge($resultArray, $this->get_files(

					$service, $file->getId(), $filePath)

				);

			} else{

				if($file->mimeType != 'application/vnd.google-apps.shortcut'){

					$resultArray[$file->getId()] = $filePath;

				}

			}

		} 

		return $resultArray;

	}

	function crawl($content){

		global $core, $clsISO;

		$ret = array();

		if($clsISO->checkContainer($content,"drive.google.com","")){

			/** Load API */

			require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';

			/** Init Client */

			$client = new Google_Client();

			$client->setClientId(GOOGLE_CLIENT_ID);

			$client->setClientSecret(GOOGLE_CLIENT_SECRET);

			$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);

			$client->setScopes(Google_Service_Drive::DRIVE);

			$service = new Google_Service_Drive($client);

			if(@preg_match('/folders/', $content)){

				@preg_match('/.*[^-\w]([-\w]{25,})[^-\w]?.*/', $content, $matches);

				$folder_id = $matches[1];

				$list_files = $this->get_files($service, $folder_id);

				// $clsISO->print_pre($list_files); die();

				if(!empty($list_files)){

					$gg_id = "";

					foreach($list_files as $okey => $oval){

						$gg_id = $okey;

						break;

					}

					if($gg_id){

						$image = sprintf('https://drive.google.com/thumbnail?id=%s&sz=w2000', $gg_id);

						$ret['gg_id'] = $gg_id;

						$ret['type'] = 'google.file';

						$ret['image'] = $image;

						$ret['list_files'] = $list_files;

						$ret['file_type'] = "folder";

					}

				}

			} else if(preg_match('/file/', $content)){

				preg_match('~/d/\K[^/]+(?=/)~', $content, $matches);

				$gg_id = $matches[0];

				/** Load API */

				require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';

				/** Init Client */

				$client = new Google_Client();

				$client->setClientId(GOOGLE_CLIENT_ID);

				$client->setClientSecret(GOOGLE_CLIENT_SECRET);

				$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);

				$client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);

				$client->setAccessType('offline');

				try {

					// Tạo dịch vụ Google Drive

					$service = new Google_Service_Drive($client);

					// Get file metadata

					$file = $service->files->get($gg_id,array(

						'fields'=>'name,mimeType',

						'supportsAllDrives' => true,

						'supportsTeamDrives' => true,

					));

					// Bảng ánh xạ MIME type sang phần mở rộng file

					$mimeMap = [

						'application/zip' => 'zip',

						'application/pdf' => 'pdf',

						'image/jpeg' => 'google.file',

						'image/png' => 'google.file',

						'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',

						'application/msword' => 'doc',

						'application/vnd.google-apps.document' => 'gdoc',

						'application/vnd.google-apps.spreadsheet' => 'gsheet',

						'application/vnd.google-apps.presentation' => 'gslides',

						'application/vnd.ms-excel' => 'xls',

						'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',

						'application/json' => 'json',

						'video/mp4'  => 'video',

						'audio/mpeg' => 'video',

					];

					$type = $mimeMap[$file->mimeType];

					if($file->mimeType == "image/jpeg" || $file->mimeType == "image/png") {

						$file_type = "image";

					}elseif($file->mimeType == "video/mp4" || $file->mimeType == "audio/mpeg") {

						$file_type = "video";

					}else{

						$file_type = "file";

					}

					

				} catch (Exception $e) {

					$type = 'google.file';

					$file_type = "";

				}

				$ret['type'] = $type;

				$ret['gg_id'] = $gg_id;

				$ret['image'] = sprintf('https://drive.google.com/thumbnail?id=%s&sz=w2000', $gg_id);

				$ret['file_type'] = $file_type;

			} 

		} else if(preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/', $content)){

			preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/", $content, $matches);

			$youtu_id = $matches[1];

			$ret['type'] = 'youtu.be';

			$ret['youtu_id'] = $youtu_id;

			$ret['image'] = sprintf('https://i.ytimg.com/vi/%s/maxresdefault.jpg', $youtu_id);

			$ret['file_type'] = "video";

		} else if($clsISO->checkContainer($content,"docs.google.com","")){

			preg_match('~/d/\K[^/]+(?=/)~', $content, $matches);

			$docs_id = $matches[0];

			$ret['docs_id'] = $docs_id;

			$ret['type'] = 'docs.google.com';

			$ret['image'] = sprintf('https://drive.fife.usercontent.google.com/u/1/d/%s=w320-h250-p-k-rw-v1-nu-iv125', $docs_id);

			$ret['file_type'] = "file";

		} else {

			$ret['type'] = 'user.fh';

			$ret['image'] = $content;

			$ret['file_type'] = "image";

		}

		return $ret;

	}

	function getHTMLTag($id,$one = null){

		global $core, $dbconn, $clsISO;

		$clsTag = new Tag();

		if(!isset($one['tags'])) {

			$one = $this->getOne($id,"tags");

		}

		$html = "";

		if(!empty($one["tags"])) {

			$tags_arrs = $clsISO->getArrayByTextSlash($one["tags"]);

			if(!empty($tags_arrs)){

				$html.= '<div class="tags mb-2">';

				foreach($tags_arrs as $tag){

					// $html.= '<a href="/kho-tai-lieu/'.$core->replaceSpace($tag).'.html" class="tag">'.$tag.'</a>';

					$html.= '<a href="javascript:void(0)" onClick="$Core.document.select_tag(this, event);" data-tag-id="'.$core->replaceSpace($tag).'" class="tag">'.$tag.'</a>';

				}

				$html.= '</div>';

			}

		}

		return $html;

	}

	function buildTree($items, $parentId = 0, $data_extends=[]) {

		global $profile_id;

		$branch = [];

		$list_docs = isset($data_extends['list_docs']) ? $data_extends['list_docs'] : [];

		foreach ($items as $item) {

			if ($item['parent_id'] == $parentId) {

				// Lấy các con của item hiện taji

				$children = $this->buildTree($items, $item['property_id'], $data_extends);

				

				$item['total_docs'] = isset($list_docs[$item['property_id']]) ? $list_docs[$item['property_id']]['total_document']: null;

				if ($children) {

					$array_column_list_docs = array_column($children, 'total_docs');

					$array_column_list_docs = !empty($array_column_list_docs) ? array_map(function($query) {

							return (int) $query;

					}, $array_column_list_docs) : [];

					$total_docs_childrent = array_sum($array_column_list_docs) + (int) $item['total_docs'];

					$item['total_docs'] = $total_docs_childrent;

					$item['children'] = $children;

				}

				$branch[] = $item;

			}

		}

		return $branch;

	}

	function renderMenu($trees, $isSubmenu = false, $cat_current_id = null) {

		$ulClass = $isSubmenu ? 'submenu' : 'ps-0';

		$html = '<ul class="list-unstyled w-100 ' . $ulClass . '" style="line-height:2">';

		foreach($trees as $node) {

			$hasChildren = !empty($node['children']);

			$cat_id = isset($node['property_id']) ? $node['property_id'] : null;

			$active = ($cat_current_id == $cat_id) ? 'active' : '';

			$total_docs = isset($node['total_docs']) ? $node['total_docs'] : 0;

			$html .= '<li class="d-flex flex-wrap align-items-center gap-1 category_item category_item_'.$cat_id.' '.$active.'">';

			if ($hasChildren) {

				$html .= '<span class="toggle-icon clickable" onClick="$Core.document.clickToggleCat(this, event);">

					<i class="bx bx-chevron-right"></i>

				</span> ';

			} else {

				$html .= '<span class="toggle-icon"><i class="bx bx-chevron-right"></i></span> ';

			}

			$link = '/danh-muc/' . $node['slug'];

			$cat_id = isset($node['property_id']) ? $node['property_id'] : null;

			$html .= '<a href="javascript:void(0)" class="category_link d-flex justify-content-between flex-fill" onClick="$Core.document.select_category(this, event);" 

				data-cat-name="'.$node['title'].'" data-cat-id="'.$cat_id.'">

				<span class="name_category">'.$node['title'].'</span>

				<span class="total_doc text-muted">('.$total_docs.')</span>

			</a>';

			if ($hasChildren) {

				$html .= $this->renderMenu($node['children'], true, $cat_current_id);

			}

			$html .= '</li>';

		}

		$html.= '</ul>';

		return $html;

	}

	function getListResult($dataResult = [],$has_expanded = false) {

		global $clsISO,$profile_id;

		if(!empty($dataResult)){

			foreach($dataResult as $key => $val){

				$tags = $val['tags'];

				$content = $val['content'];

				$more_information = $val['more_information'];

				$more_information = !empty($more_information) 

					? json_decode(html_entity_decode($more_information), true) : array();

				if($clsISO->checkContainer($more_information['image'],"https","")){

					if(isset($more_information['gg_id']) && !empty($more_information['gg_id'])){

						$gg_id = $more_information['gg_id'];

						$more_information['image'] = sprintf('https://lh3.googleusercontent.com/d/%s=w320', $gg_id);

					}

				}else{

					$more_information['image'] = FH_URL.$more_information['image'];

				}

				

				$list_tags = !empty($tags) ? explode(',', $tags) : array();

				if($clsISO->checkContainer($content,"drive.google.com","")){

					$link_type = 'google.drive';

				} else if($clsISO->checkContainer($content,"docs.google.com","")){

					$link_type = 'google.docs';

				} else {

					$link_type = $more_information['type'];

					if($link_type == 'user.fh' && !empty($more_information['image'])){

						if($clsISO->checkContainer($more_information['image'], "https://", "")){

							$more_information['image'] = trim($more_information['image']);

						} else {

							$path_parts = pathinfo($more_information['image']);

							if($path_parts['extension']=='pdf'){

								$more_information['image'] = URL_IMAGES . '/PDF.png';

							} else {

								$more_information['image'] = FH_URL . trim($more_information['image']);

							}

						}

					}

				}

				$dataResult[$key]['link_type'] = $link_type;

				$dataResult[$key]['list_tags'] = $list_tags;

				$dataResult[$key]['title'] = $val['title'];

				$dataResult[$key]['more_information'] = $more_information;

				$dataResult[$key]['link'] = $clsISO->getIframeUrl($val['content']);

				$dataResult[$key]['reg_date_f'] = $clsISO->getTimeAgo($val['reg_date']);

				

				$is_expanded = isset($more_information['is_expanded']) 

						? (int) $more_information['is_expanded'] : 0; 

				$list_files = isset($more_information['list_files']) 

					? $more_information['list_files'] : array();

				$list_docs = $list_images = array();

				if(((!empty($list_files) && $more_information['type'] == 'google.file') || (empty($list_files) && $more_information['type'] == 'video')) && $is_expanded == 1 && $has_expanded){

					if(!empty($list_files)){

						foreach($list_files as $mkey => $mval){

							if($clsISO->isFileVideo($mval)){

								$_type = 'video';

							} else if($clsISO->isPDF($mval)){

								$_type = 'pdf';

							} else if($clsISO->isImage($mval)){

								$_type = 'google.file';

							} else {

								$_type = 'other';

							}

							$list_docs[] = array(

								'type' => $_type,

								'title' => $val['title'],

								'key' => $mkey,

								'list_images' => $list_images,

								'image' => $clsISO->genGoogleURL($mkey),

								'link' => $clsISO->genGoogleURL($mkey,($_type=='google.file'?'view':'preview')),

								'link_download' => $clsISO->genGoogleURL($mkey, 'download'),

								'more_information' => array(

									'gg_id' => $mkey,

									'type' => $_type,

									'image' =>  $clsISO->genGoogleURL($mkey)

								)

							);

						}	

					}else {

						$val['type'] = $more_information['type'];

						$val['link_download'] = $clsISO->getDownloadURL($val['content']);

						if($more_information['type'] == 'pdf' || $more_information['type'] == 'video' || $more_information['type'] == 'docs.google.com'){

							$val['link'] = $clsISO->getIframeUrl($val['content']);

						} else {

							$val['link'] = $clsISO->getGoogleUrl($val['content']);

						}

						$val['list_images'] = $list_images;

						$val['image'] = $more_information['image'];

						$list_docs[] = $val;

					}

				} else {

					$val['more_information'] = $more_information;					

					if(!empty($list_files)){ 

						$kk = 0;

						if(count($list_files) > 1){

							foreach($list_files as $n_key => $nval){

								$nkey = ($more_information['file_type'] == 'folder') ? $n_key : $nval;

								if($clsISO->isFileVideo($nval)){

									$_type = 'video';

								} else if($clsISO->isPDF($nval)){

									$_type = 'pdf';

								} else if($clsISO->isImage($nval)){

									$_type = 'google.file';

								} else {

									$_type = 'other';

								}

								// Nếu ảnh đầu tiên là video

								if($kk == 0) {

									if(in_array($_type, array('video', 'pdf','other'))){

										$val['type'] = $_type;

										$val['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'));

									} else {

										$val['type'] = $more_information['type'];

										$val['link'] = $clsISO->genGoogleURL($nkey, 'view',1,1000);

									}

								}

								if($more_information['file_type'] == 'folder' || $more_information['file_type'] == 'docs.google.com' || $more_information['file_type'] == 'youtu.be' || $more_information['type'] == 'user.fh'){

									$val['link_download'] = $val['content'];

								} else {

									$val['link_download'] = $clsISO->genGoogleURL($more_information['gg_id'], 'download');

								}

								$kk>0 && $list_images[] = array(

									'gid' => $nkey,

									'type' => $_type,

									'image' =>  $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'),1,1000)

								);

								++$kk;

							}

						} else {

							foreach($list_files as $n_key => $nval){

								$nkey = ($more_information['file_type'] == 'folder') ? $n_key : $nval;

								if($clsISO->isFileVideo($nval)){

									$_type = 'video';

								} else if($clsISO->isPDF($nval)){

									$_type = 'pdf';

								} else if($clsISO->isImage($nval)){

									$_type = 'google.file';

								} else {

									$_type = 'other';

								}

								$val['type'] = $_type;

								if($more_information['file_type'] == 'folder' || $more_information['file_type'] == 'docs.google.com' || $more_information['file_type'] == 'youtu.be' || $more_information['type'] == 'user.fh'){

									$val['link_download'] = $val['content'];

								} else {

									$val['link_download'] = $clsISO->genGoogleURL($nkey, 'download');

								}								

								$val['link'] = $clsISO->genGoogleURL($nkey,($_type=='google.file'?'view':'preview'),1,1000);

							}

						}

					} else {

						$val['type'] = $more_information['type'];

						if($more_information['type'] == 'pdf' || $more_information['type'] == 'video' || $more_information['type'] == 'docs.google.com'){

							$val['link'] = $clsISO->getIframeUrl($val['content']);

						} else if($more_information['type'] == 'user.fh'){

							$val['link'] = $more_information['image'];

						} else {

							$val['link'] = $clsISO->getGoogleUrl($val['content'],1000);

						}

						if($more_information['type'] == 'folder' || $more_information['type'] == 'docs.google.com' || $more_information['type'] == 'youtu.be' || $more_information['type'] == 'user.fh'){

							$val['link_download'] = $val['content'];

						} else {

							$val['link_download'] = $clsISO->genGoogleURL($more_information['gg_id'], 'download');

						}

						$val['list_images'] = $list_images;

						$val['image'] = $more_information['image'];

					}

					$val['list_images'] = $list_images;

					$val['image'] = $more_information['image'];	

					$list_docs[] = $val;

					

				}

				$dataResult[$key]['list_docs'] = $list_docs;

				if($more_information['file_type'] == 'folder') {

					$dataResult[$key]['link_download'] = $val['content'];	

				}else{

					$dataResult[$key]['link_download'] = $clsISO->getDownloadURL($val['content']);	

				}

				

			}

		}

		return $dataResult;

	}

	function renderhtmlProject($tree, $isSubmenu = false, $object_current_id = null, $type = 'project') {

        global $profile_id;

		$ulClass = $isSubmenu ? 'submenu' : 'ps-0 mb-0';

		$ulStyle = $isSubmenu ? 'display:none;' : 'line-height: 2';

		$html = '<ul class="' . $ulClass . '" style="list-style: none; ' . $ulStyle . '">';

        $is_foreach_current = null;

		foreach ($tree as $node) {

            if (isset($node['property_id'])) {

                $is_foreach_current = 'block';

                $fieldChidrent = 'list_menu_buildings';

            } elseif (isset($node['building_id'])) {

                $is_foreach_current = 'building';

                $fieldChidrent = '';

            } elseif (isset($node['project_id'])) {

                $is_foreach_current = 'project';

                $fieldChidrent = 'list_blocks';

            }

			$hasChildren = !empty($node['list_blocks']) || !empty($node['list_menu_buildings']);

            if ($is_foreach_current == 'project' || $is_foreach_current == 'building') {

                $field = $is_foreach_current."_id";

                $object_id = isset($node[$field]) ? $node[$field] : null;

            } elseif ($is_foreach_current == 'block') {

                $object_id = isset($node['property_id']) ? $node['property_id'] : null;

            }

			$active = $object_current_id == $object_id && $is_foreach_current == $type ? 'active' : '';

            

			$html .= '<li class="project_property_item project_property_item_'.$object_id.' '.$active.'">';

			

			if ($hasChildren) {

				$html .= '<span class="toggle-icon clickable" onClick="$Core.document.clickToggleCat(this, event);"><i class="bx bx-chevron-right"></i></span> ';

			} else {

				$html .= '<span class="toggle-icon"><i class="bx bx-chevron-right"></i></span> ';

			}

			$html .= '<a href="javascript:void(0)" class="project_property_link" onClick="$Core.document.select_project(this, event);" data-project-property-id="'.$object_id.'" data-type="'.$is_foreach_current.'" data-project-property-name="'.$node['title'].'"><span class="name_project_property">' . $node['title'] . '</span></a>';

			if ($hasChildren) {

				$html .= $this->renderhtmlProject($node[$fieldChidrent], true, $object_current_id, $type);

			}

			$html .= '</li>';

		}

		$html .= '</ul>';

		return $html;

	}

	function condByProject($project_id){

		$project_id = (int) $project_id;

		return "`project_ids` LIKE '%|{$project_id}|%'";

	}

	// Đồng bộ junction block/building/tag (dual-write; bảng default_project_meta_*)

	function syncRelations($meta_id, $blocks=array(), $buildings=array(), $tags_slug=array(), $tags_name=array()){

		global $dbconn;

		$meta_id = (int) $meta_id;

		if($meta_id <= 0) return;

		$pre = DB_PREFIX;

		$dbconn->Execute("DELETE FROM `{$pre}project_meta_block` WHERE meta_id={$meta_id}");

		foreach((array)$blocks as $b){ $b=(int)$b; if($b>0) $dbconn->Execute("INSERT IGNORE INTO `{$pre}project_meta_block` (meta_id,block_id) VALUES ({$meta_id},{$b})"); }

		$dbconn->Execute("DELETE FROM `{$pre}project_meta_building` WHERE meta_id={$meta_id}");

		foreach((array)$buildings as $b){ $b=(int)$b; if($b>0) $dbconn->Execute("INSERT IGNORE INTO `{$pre}project_meta_building` (meta_id,building_id) VALUES ({$meta_id},{$b})"); }

		$dbconn->Execute("DELETE FROM `{$pre}project_meta_tag` WHERE meta_id={$meta_id}");

		$tags_name = array_values((array)$tags_name);

		$i = 0;

		foreach(array_values((array)$tags_slug) as $slug){

			$slug = trim($slug);

			if($slug !== ''){

				$name = isset($tags_name[$i]) ? trim($tags_name[$i]) : $slug;

				$dbconn->Execute("INSERT IGNORE INTO `{$pre}project_meta_tag` (meta_id,tag_slug,tag_name) VALUES ({$meta_id},'".addslashes($slug)."','".addslashes($name)."')");

			}

			$i++;

		}

	}

}