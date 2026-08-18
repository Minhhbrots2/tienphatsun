<?

/*======================================================================*\

|| #################################################################### ||

|| # The modules of the ISOCMS                                        # ||

|| # ISOCMS 6.0.0 By Luong Tien Dung (luongtiendung@gmail.com)        # ||

|| # ---------------------------------------------------------------- # ||

|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||

|| # This file may not be redistributed in whole or significant part. # ||

|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||

|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||

|| #################################################################### ||

\*======================================================================*/

function default_default(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;

	global $clsConfiguration,$clsISO;

	$clsStock = new Stock(); $assign_list['clsStock'] = $clsStock;

	$clsSop = new Sop(); $assign_list['clsSop'] = $clsSop;

	$clsLeasing = new Leasing(); $assign_list['clsLeasing'] = $clsLeasing;

	$clsInterior = new Interior(); $assign_list['clsInterior'] = $clsInterior;

//	$clsInteriorRequest = new InteriorRequest(); $assign_list['clsInteriorRequest'] = $clsInteriorRequest;

	$clsService = new Service(); $assign_list['clsService'] = $clsService;	

	$clsMember = new Member(); $assign_list['clsMember'] = $clsMember;	

	

	$cond = "`is_trash`=0";

	$total_highlevel = $clsStock->countItem($cond." and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."'"); 

	$assign_list["total_highlevel"] = $total_highlevel;

	

	$total_highlevel_sold = $clsStock->countItem($cond." and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and status_id='"._STOCK_STATUS_SOLD_ID."'"); 

	$assign_list["total_highlevel_sold"] = $total_highlevel_sold;

	$total_highlevel_dq = $clsStock->countItem($cond." and `stock_type`='"._BLOCK_TYPE_HIGHLEVEL_SALE."' and status_id='"._STOCK_STATUS_DQ_ID."'"); 

	$assign_list["total_highlevel_dq"] = $total_highlevel_dq;

	

	$total_lowfloor = $clsStock->countItem($cond." and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."'"); 

	$assign_list["total_lowfloor"] = $total_lowfloor;

	$total_lowfloor_sold = $clsStock->countItem($cond." and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and status_id='"._STOCK_STATUS_SOLD_ID."'"); 

	$assign_list["total_lowfloor_sold"] = $total_lowfloor_sold;

	$total_lowfloor_dq = $clsStock->countItem($cond." and `stock_type`='"._BLOCK_TYPE_LOWFLOOR_SALE."' and status_id='"._STOCK_STATUS_DQ_ID."'");

	$assign_list["total_lowfloor_dq"] = $total_lowfloor_dq;

	// ===== Dashboard thống kê mở rộng =====
	// Nhân viên
	$clsProfile = new Profile(); $assign_list['clsProfile'] = $clsProfile;
	$staff_total  = (int) $clsProfile->countItem("`is_trash`=0");
	$staff_active = (int) $clsProfile->countItem("`is_trash`=0 AND `is_active`=1");
	$assign_list['staff_total']  = $staff_total;
	$assign_list['staff_active'] = $staff_active;
	$assign_list['staff_off']    = max(0, $staff_total - $staff_active);

	// Dự án
	$clsProject = new Project(); $assign_list['clsProject'] = $clsProject;
	$assign_list['project_total'] = (int) $clsProject->countItem("`is_trash`=0");
	$assign_list['project_show']  = (int) $clsProject->countItem("`is_trash`=0 AND `is_menu`=1");

	// Bản tin
	$clsNews = new News(); $assign_list['clsNews'] = $clsNews;
	$assign_list['news_total']  = (int) $clsNews->countItem("`is_trash`=0");
	$assign_list['news_online'] = (int) $clsNews->countItem("`is_trash`=0 AND `is_online`=1");
	$assign_list['news_draft']  = (int) $clsNews->countItem("`is_trash`=0 AND `is_online`=0");
	$assign_list['news_recent'] = $clsNews->getAll("`is_trash`=0 ORDER BY `reg_date` DESC LIMIT 0,5");

	// Đào tạo
	$clsTraining = new Training(); $assign_list['clsTraining'] = $clsTraining;
	$assign_list['training_total']  = (int) $clsTraining->countItem("`is_trash`=0");
	$assign_list['training_online'] = (int) $clsTraining->countItem("`is_trash`=0 AND `is_online`=1");
	$assign_list['training_recent'] = $clsTraining->getAll("`is_trash`=0 ORDER BY `reg_date` DESC LIMIT 0,5");

	// Quỹ căn theo trạng thái (cao + thấp)
	$cond_units = $cond." AND `stock_type` IN ('"._BLOCK_TYPE_HIGHLEVEL_SALE."','"._BLOCK_TYPE_LOWFLOOR_SALE."')";
	$units_total = $total_highlevel + $total_lowfloor;
	$units_sold  = $total_highlevel_sold + $total_lowfloor_sold;
	$units_dq    = $total_highlevel_dq + $total_lowfloor_dq;
	$units_lock  = (int) $clsStock->countItem($cond_units." AND `status_id`='"._STOCK_STATUS_LOCK_ID."'");
	$units_avail = max(0, $units_total - $units_sold - $units_dq - $units_lock);
	$assign_list['units_total'] = $units_total;
	// Donut: dựng sẵn dash-array/offset (chu vi r=54)
	$c_circ = 339.292; $u_tot = max(1, $units_total); $acc = 0; $donut = array();
	$segs = array(
		array('nm'=>'Còn hàng','v'=>$units_avail,'color'=>'#12946A'),
		array('nm'=>'Đã bán','v'=>$units_sold,'color'=>'#3B76F0'),
		array('nm'=>'Độc quyền','v'=>$units_dq,'color'=>'#D98211'),
		array('nm'=>'Giữ chỗ','v'=>$units_lock,'color'=>'#E1508A'),
	);
	foreach($segs as $s){
		$len = round($c_circ * $s['v'] / $u_tot, 1);
		$donut[] = array('nm'=>$s['nm'],'color'=>$s['color'],'v'=>$s['v'],
			'pc'=>round($s['v']*100/$u_tot),
			'dash'=>$len.' '.round($c_circ-$len,1),'offset'=>round(-$acc,1));
		$acc += $len;
	}
	$assign_list['donut'] = $donut;

	// Nhân viên theo phòng ban
	$clsProperty = new Property(); $assign_list['clsProperty'] = $clsProperty;
	$dept_rows = $dbconn->GetAll("SELECT `department_id`, COUNT(*) AS `c` FROM `".$clsProfile->tbl."`
		WHERE `is_trash`=0 AND `is_active`=1 AND `department_id`>0
		GROUP BY `department_id` ORDER BY `c` DESC LIMIT 0,6");
	$dept_stats = array(); $dept_max = 1;
	if(!empty($dept_rows)){
		foreach($dept_rows as $r){
			$c = (int) $r['c']; if($c > $dept_max) $dept_max = $c;
			$nm = $clsProperty->getTitle($r['department_id']);
			$dept_stats[] = array('name'=> ($nm !== '' ? $nm : 'Phòng '.$r['department_id']), 'count'=>$c);
		}
	}
	foreach($dept_stats as $k => $d){ $dept_stats[$k]['pct'] = round($d['count'] * 100 / $dept_max); }
	$assign_list['dept_stats'] = $dept_stats;
	$assign_list['dept_max']   = $dept_max;

	// Danh sách thay cho bảng recent cũ
	$assign_list['staff_recent'] = $clsProfile->getAll("`is_trash`=0 ORDER BY `profile_id` DESC LIMIT 0,6", "`profile_id`,`full_name`,`department_id`");
	$assign_list['project_list'] = $clsProject->getAll("`is_trash`=0 ORDER BY `is_menu` DESC LIMIT 0,8");

}

function default_loadItemHome(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;

	global $clsConfiguration,$clsISO;

	$clsSop = new Sop(); $assign_list['clsSop'] = $clsSop;

	$clsLeasing = new Leasing(); $assign_list['clsLeasing'] = $clsLeasing;

	$clsInterior = new Interior(); $assign_list['clsInterior'] = $clsInterior;

//	$clsInteriorRequest = new InteriorRequest(); $assign_list['clsInteriorRequest'] = $clsInteriorRequest;

	$clsService = new Service(); $assign_list['clsService'] = $clsService;	

	$clsMember = new Member(); $assign_list['clsMember'] = $clsMember;	

	$clsCompany = new Company(); $assign_list['clsCompany'] = $clsCompany;	

	

	$clsTable = Input::post("clsTable","");

	$clsClassTable = new $clsTable; $assign_list['clsClassTable'] = $clsClassTable;	

	

	$type = Input::post("type",""); $assign_list['type'] = $type;	

	$current_page = (int)Input::post("page",1);

	$per_page = (int) Input::post('per_page',5);	

	

	if($type == "_SOP" || $type == "_LEASING" || $type == "_SERVICES" || $type == "_INTERIOR") {

//		$cond = "is_online = '0'";

		$cond = "is_online = '1'";

	}else if($type == '_INTERIOR_REQUEST') {

		$cond = "1=1";

	}	

	$offset = ($current_page-1)*$per_page;

	$assign_list['offset'] = $offset;

	$total_record = $clsClassTable->countItem($cond);

	$total_page = ceil($total_record/$per_page);

	$limit = " limit {$offset},{$per_page}";

	$order_by = " ORDER BY reg_date ASC";	

//	$clsClassTable->setDeBug(1);

	$lstItem = $clsClassTable->getAll($cond.$order_by.$limit); 

	$array_cache = array();

	foreach ($lstItem as $key => $value) {

		$lstItem[$key]['more_information'] = $clsISO->to_array_json($value['more_information']);

	}

	$assign_list['lstItem'] = $lstItem;

//	var_dump($lstItem);die;

	$html = $core->build('_ajax.loadItemHome.tpl');

	echo json_encode(array(

		'html' => $html,

		'cond' => $cond,

		'per_page' => $per_page,

		'current_page' => $current_page,

		'total_page' => $total_page,

		'total_record' => $total_record,

		'cat_id' => $cat_id,

		'check_key_cat' => $check_key_cat,

		'url' => $url

	)); die();

	

}

function default_show_notes(){

	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current

	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO,$assign_list;

	$clsSop = new Sop();

	$clsProperty = new Property();

	###

	$msg = "_success";

	$action = Input::post('action', ""); $assign_list['action']=$action;

	$table = Input::post("table","");

	$type = Input::post("type","");

	if($table != "") {

		$id = (int) Input::post('id', 0);

		$assign_list['id'] = $id;

		$assign_list['table'] = $table;

		$assign_list['type'] = $type;



		$html = $core->build('_ajax.open_notes.tpl');

		echo $html;die;

	}

	

}

function default_approve(){

	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current

	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;

	$clsNotify = new Notify();

	$clsProperty = new Property();

	###

	$msg = "_success";

	$action = Input::post('action', "approve");

	$clsTable = Input::post("table","");

	if($clsTable != "") {

		$clsClassTable = new $clsTable;

		$id = (int) Input::post('id', 0);

		if($action == "approve"){

			if($id > 0){

				if($clsClassTable->updateOne($id, array(

					'is_online' => 1

				))){

					$field = "{$clsClassTable->pkey},`title`,`user_id`";

					$oneItem = $clsClassTable->getOne($id, $field);

					if($clsTable == "Sop") {

						$msg_notify = "Quản trị viên đã phê duyệt tin chuyển nhượng <strong>%s</strong> của bạn";

					}else if($clsTable == "Leasing") {

						$msg_notify = "Quản trị viên đã phê duyệt tin cho thuê <strong>%s</strong> của bạn";

					}else if($clsTable == "Service") {

						$msg_notify = "Quản trị viên đã phê duyệt dịch vụ tiện ích <strong>%s</strong> của bạn";

					}else if($clsTable == "Interior") {

						$msg_notify = "Quản trị viên đã phê duyệt bản thiết kế <strong>%s</strong> của bạn";

					}

					$content = sprintf($msg_notify, $oneItem['title']) ;

//					$clsNotify->insertNotify($clsClassTable->tbl, $clsClassTable->pkey, $id, $content, time(), sprintf('|%s|', $oneItem['user_id']));

				}

			}else{

				$msg = "_error";

			}

		}else if($action == "noapprove"){

			$notes = Input::post('notes', '');

			if($id > 0){

				$oneItem = $clsClassTable->getOne($id,'more_information');

				if(!empty($oneItem)){

					$more_information = json_decode($oneItem['more_information']);

					$more_information->reason_not_approved = $notes;

					

					if($clsClassTable->updateOne($id, "is_online=2,more_information='".json_encode($more_information)."'")){

						$field = "{$clsClassTable->pkey},`title`,`user_id`";

						$oneItem = $clsClassTable->getOne($id, $field);

						if($clsTable == "Sop") {

							$msg_notify = "Quản trị viên đã không phê duyệt tin chuyển nhượng <strong>%s</strong> của bạn với lý do %s";

						}else if($clsTable == "Leasing") {

							$msg_notify = "Quản trị viên đã không phê duyệt tin cho thuê <strong>%s</strong> của bạn với lý do %s";

						}else if($clsTable == "Service") {

							$msg_notify = "Quản trị viên đã không phê duyệt dịch vụ tiện ích <strong>%s</strong> của bạn với lý do %s";

						}else if($clsTable == "Interior") {

							$msg_notify = "Quản trị viên đã không phê duyệt bản thiết kế <strong>%s</strong> của bạn với lý do %s";

						}

						$content = sprintf($msg_notify, $oneItem['title'],$notes) ;

	//					$clsNotify->insertNotify($clsClassTable->tbl, $clsClassTable->pkey, $id, $content, time(), sprintf('|%s|', $oneItem['user_id']));

					}

				}

			}else{

				$msg = "_error";

			}

		}else{

			$msg = "_error";

		}

	}

	// Return

	echo $msg; die();

}

function default_delete(){

	global $smarty,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current

	,$current_page,$core,$clsModule,$clsButtonNav,$clsConfiguration,$clsISO;

	$clsTable = Input::post("table","");

	if($clsTable != "") {

		$clsClassTable = new $clsTable;

		###	

		$id = (int)Input::post('id', 0); 

		if($id > 0){

			if($clsClassTable->doDelete($id)){

				$msg = "_success";

			}else{

				$msg = "_error";

			}

		}else{

			$msg = "_error";

		}

	}

	// Return

	echo ($msg); die();

}

function default_crawl(){

	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;

	global $clsConfiguration,$clsISO;

	

	$clsBilling = new Billing();

	require DIR_INCLUDES . '/googleapiclient/vendor/autoload.php';

	/** Init Client */

	$client = new Google_Client();

	$client->setClientId(GOOGLE_CLIENT_ID);

	$client->setClientSecret(GOOGLE_CLIENT_SECRET);

	$client->refreshToken(GOOGLE_DRIVE_REFRESH_TOKEN);

	$client->setScopes(Google_Service_Sheets::SPREADSHEETS);

	$service = new Google_Service_Sheets($client);

	// Web: https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api

	// the spreadsheet id can be found in the url https://docs.google.com/spreadsheets/d/143xVs9lPopFSF4eJQWloDYAndMor/edit

	// $spreadsheet = $service->spreadsheets->get($spreadsheetId);

	// get all the rows of a sheet

	$range = 'BH'; // here we use the name of the Sheet to get all the rows

	$spreadsheetId = "1wTIp9sFeBbsJYvpymDRa7jZQVDk26OpY2HTMGqtzxbM";

	$response = $service->spreadsheets_values->get($spreadsheetId, $range);

	$tblData = $response->getValues();

	// $clsISO->print_pre($tblData); die();

	if(!empty($tblData)){

		for($i=1; $i<count($tblData); $i++){

			$stock_code = trim($tblData[$i][5]);

			$stock_resource = $tblData[$i][7];

			$deposit_date = $tblData[$i][1];

			$contract_date = $tblData[$i][2];

			$commission = $tblData[$i][8];

			$commission_sale = $tblData[$i][9];

			$commission_agency = $tblData[$i][10];

			if(!empty($stock_code) && !empty($contract_date)){

				$oBilling = $clsBilling->getByCond("stock_code='{$stock_code}'");

				// $clsISO->print_pre($oBilling); die();

				if(!empty($oBilling)){

					$more_information = $oBilling['more_information'];

					$more_information = $clsISO->to_array_json($more_information);

					if(!empty($commission)){

						$more_information['commission'] = $commission;

					}

					if(!empty($commission_sale)){

						$more_information['commission_sale'] = $commission_sale;

					}

					if(!empty($commission_agency)){

						$more_information['commission_agency'] = $commission_agency;

					}

					if(!empty($stock_resource)){

						$more_information['stock_resource'] = $stock_resource;

					}

					$clsBilling->updateOne($oBilling[$clsBilling->pkey], array(

						'commission' => $commission,

						'contract_date' => $clsISO->toTime($contract_date),

						'contract_status_id' => _CONTRACT_STATUS_DONE_ID,

						'more_information' => json_encode($more_information, JSON_UNESCAPED_UNICODE)

					));

					// $clsISO->print_pre($more_information); die();

				}

			}

		}

	}

}

function default_feedback(){

	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$title_page,$description_page,$keyword_page;

	global $clsConfiguration,$clsISO;

	#----

	$smarty->assign('shop_url', DOMAIN_NAME);

	$smarty->assign('shop_name', $clsConfiguration->getValue('company_name'));

	$smarty->assign('shop_phone', $clsConfiguration->getValue('company_phone'));

	$smarty->assign('shop_address', $clsConfiguration->getValue('company_address'));

	#----

	$req = file_get_contents('php://input');

	$data = @json_decode($req, true);

	$note = $data['note'];

	$img  = $data['img'];

	$image = $clsISO->base642imagejpeg($img, md5(DOMAIN_NAME.time()).'.jpeg', '/tmp');

	$smarty->assign('note', $note);

	$smarty->assign('image', $image);

	

	$subject = 'V/v feedback mới từ website {$shop_name}';

	$message = '<table align="center" cellpadding="0" cellspacing="0" width="700" style="min-width:700px;max-width:700px;margin:0 auto">

	<tbody style="font-family:\'Roboto\',Helvetica,Arial,sans-serif;margin:0px auto;font-size:0.875rem;overflow-x:hidden">

		<tr><td>

			<div style="max-width:1280px;margin:auto;background:#f5f6fa">

				<div style="width:100%;text-align:center;padding-bottom:64px">

					<div style="padding:32px;display:flex">

					</div>

					<div style="width:80%;background-color:#FFF;text-align:center;margin:auto;border-bottom:1px solid #f5f6fa;border-radius:12px">

						<div style="border-top:4px solid #019bfa;border-top-left-radius:3px;border-top-right-radius:3px;padding:24px 32px;display:flex">

							<a style="display:flex;width:50%"><img src="{$shop_url}/inc/assets/logo.png" style="height:20px;margin:auto 0"></a>

							<div style="display:flex;width:50%">

								<span style="display:flex;margin:auto">Hotline: {$shop_phone}</span>

								<div style="margin:auto 16px;background-color:rgba(73,73,73,0.2);width:1px;height:20px"></div>

							</div>

						</div>

						<div style="font-size:0.875rem;text-align:center;color:#494949;padding:0 32px;margin:auto">

							<h2 style="text-align:left">Feedback từ khách hàng được gửi cho bạn</h2>

							{if $note}

							<p style="margin-bottom:0;margin-top:0;text-align:left; line-height:20px">{$note}</p>

							{/if}

							{if $image}

							<div style="padding:24px;background-color:rgba(73,73,73,0.05);margin-top:24px;border-radius:4px">

								<img src="{$shop_url}{$image}" width="100%" alt="Hình ảnh" />

							</div>

							{/if}

							<br>

							<p style="margin-bottom:0;margin-top:0;text-align:left">

								Bạn vui lòng kiểm tra và xử lý nội dung feedback này. Thanks!

							</p>

							<br><br><br>

							<hr style="opacity:0.2;color:#494949;margin:0">

							<div style="text-align:left;margin-bottom:10%"><br>

								<p style="margin-bottom:0;font-size:0.875rem;color:#494949">Chúc bạn ngày mới tốt lành!</p>

								<p style="margin-bottom:0;font-size:0.875rem;color:#494949"><strong>Đội ngũ phát triển MaxxCRM</strong></p>

							</div>

						</div>

						<div style="border-bottom-left-radius:3px;border-bottom-right-radius:3px;padding:24px 32px;background-color:#267aff;display:flex">

							<div><img src="{$shop_url}/inc/assets/logo.png" alt="MaxxCRM" style="width:40px;text-align:left"></div>

							<div style="text-align:left;margin-left:20px">

								<p style="margin:0;margin-bottom:5px;color:#fff">{$shop_name}</p>

								<p style="margin:0;color:#fff;opacity:0.5">{$shop_address}</p>

							</div>

						</div>

					</div>

				</div>

			</div>

		</td></tr>

	</tbody>

	</table>';

	$subject = $smarty->fetch('eval:'.$subject);

	$message = $smarty->fetch('eval:'.$message);

	$toemail = "vanthiembui.it@gmail.com";

	$toname = "Technical Support MaxxCRM";

	$res = $clsISO->sendEmail($toemail, $toname, $subject, $message);

	$status = $res['status']=='success' ? 1: 0;

	// Return

	echo ($status); die();

}

function default_load_preview_search(){

	global $clsISO;

	$clsTable = Input::post('clsTable');

	$pvalTable = Input::post('pvalTable');

	$config_link = Input::post('config_link','');

	$config_value_title = Input::post('config_value_title');

	$config_value_intro = Input::post('config_value_intro');

	$html = $clsISO->getPreviewSEO($clsTable,$pvalTable,array(

		'config_link' => $config_link,

		'config_value_title' => $config_value_title,

		'config_value_intro' => $config_value_intro

	));

	// Return

	echo $html; die();

}

function default_ajDeleteItemImage(){

	global $dbconn,$assign_list,$_CONFIG,$_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page,$core,$clsModule;

	#

	$clsTable =  addslashes($_POST['clsTable']);

	$clsClassTable = new $clsTable();

	$pvalTable = addslashes($_POST['pvalTable']);

	#

	$clsClassTable->updateOne($pvalTable,"image=''");  

	echo($clsClassTable->getOneField('image',$pvalTable));die(); 

}

function default_ajDeleteMultiItem(){

	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page;

	global $core,$clsModule,$clsButtonNav;

	#

	$clsTable = Input::post('clsTable');

	$list_id_selected = Input::post('list_id_selected');

	if(!empty($list_id_selected) && $list_id_selected != '0' && $list_id_selected != '|'){

		$clsClassTable = new $clsTable();

		$temp = explode('|',$list_id_selected);

		if(!empty($temp)){

			if(@method_exists($clsClassTable,'doDelete')){

				for($i=0; $i<count($temp); $i++){    

					$clsClassTable->doDelete($temp[$i]);

				}

			} else {

				for($i=0; $i<count($temp); $i++){    

					$clsClassTable->deleteOne($temp[$i]);

				}

			}

		}

	}

	// Return

	echo(1); die();

}

function default_ajaxPopSiteHelp(){

	global $core;

	#

	$clsHelp = new Help();

	$mod_page = $_POST['mod_page'];

	$act_page = $_POST['act_page'];

	$area_page = $_POST['area_page'];

	

	$SiteHelpPage = 'Site_Help_'.$mod_page.'_'.$act_page;

	if($area_page != ''){

		$SiteHelpPage .= '_'.$area_page;

	}

	#

	$html='';

	$html.='

	<div class="headPop"> 

		<a class="closeEv close_pop" data-dismiss="modal" aria-hidden="true">&nbsp;</a> 

		<h3>'.$core->get_Lang('infohelpmod').' '.$core->get_Lang($mod_page).'</h3>

	</div>';

	if(_DEV == '1') {

	$html.='

	<form method="post" action="" id="formHelp" class="frmform formborder" enctype="multipart/form-data">

		<div class="wrap">

			<div class="fl" style="width:100%">

				<div class="row-span">

					<div class="fieldlabel" style="text-align:right">'.$core->get_Lang('content').'</div>

					<div class="fieldarea">

						<textarea id="textarea_help_content_editor_'.time().'" class="textarea_help_content_editor" name="'.$SiteHelpPage.'" style="width:100%">'.$clsHelp->getValue($SiteHelpPage).'</textarea>

					</div>

				</div>

			</div>

		</div>

	</form>

	<div class="modal-footer"> 

		<button class="btn btn-primary btnSaveSiteHelpPage" mod_page="'.$mod_page.'" act_page="'.$act_page.'" area_page="'.$area_page.'">

			<i class="icon-ok icon-white"></i> '.$core->get_Lang('Save').'

		</button> 

		<button type="reset" class="btn btn-warning close_pop">

			<i class="icon-retweet icon-white"></i> <span>Đóng lại</span>

		</button>

	</div>';

	} else {

		$html .= '

		<style>

			.formatTextStandard{width:99%;padding-right:1%;max-height:470px;overflow-y:scroll}

		</style>';

		$html.= '<div class="formatTextStandard">';

		if($clsHelp->getValue($SiteHelpPage) != ''){

			$html .= $clsHelp->getValue($SiteHelpPage);

		}else{

			$html .= $core->get_Lang('Help content empty !');

		}

		$html .= '</div>';

	}

	echo $html; die();

}

function default_ajaxSaveSiteHelp(){

	global $core;

	#

	$clsHelp = new Help();

	$mod_page = $_POST['mod_page'];

	$act_page = $_POST['act_page'];

	$area_page = $_POST['area_page'];

	

	$SiteHelpPage = 'Site_Help_'.$mod_page.'_'.$act_page;

	if($area_page != ''){

		$SiteHelpPage .= '_'.$area_page;

	}

	$help_content = isset($_POST['help_content'])?addslashes($_POST['help_content']):'';

	$clsHelp->updateValue($SiteHelpPage,$help_content);

	echo(1); die();

}

function default_saveField(){

	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page;

	global $core,$clsModule,$clsButtonNav,$clsISO;

	#

	$html = '';

	$clsTable = $_POST['clsTable'];

	$pkey = $_POST['pkey'];

	$pvalTable = $_POST['pvalTable'];

	$toField = $_POST['toField'];

	$val = $_POST['val'];

	$allowDuplicate = $_POST['allowDuplicate'];

	$ipn = isset($_POST['ipn']) ? $_POST['ipn'] : 'text';

	#

	$clsClassTable = new $clsTable();

	if($allowDuplicate==1){

		//allow duplicate

		if($ipn=='number'){

			$val = $clsISO->processSmartNumber($val);

		}

		$clsClassTable->updateOne($pvalTable,$toField."='".addslashes($val)."'");

		$html = $val;

	}else{

		if($ipn=='number'){

			$val = $clsISO->processSmartNumber($val);

		}

		$all = $clsClassTable->getAll($toField."='$val'");

		if($all[0][$pkey]!='' && $all[0][$pkey]!=$pvalTable){

			$html = 'IsDuplicated';

		}else{

			$clsClassTable->updateOne($pvalTable,$toField."='".addslashes($val)."'");

			$html = $val;

		}

	}

	// Return

	echo($html); die();

}

function default_ajOpenNote(){

	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod ,$_LANG_ID,$act,$menu_current,$current_page;

	global $core,$clsModule,$clsButtonNav;

	$user_id = $core->_USER['user_id'];

	#

	$clsUser = new User();

	$tp = Input::post('tp');

	$html = '<div class="headPop">

		<h3>'.$core->get_Lang('My Notes').'</h3>

		<a href="javscript:void(0);" class="close_pop closeEv"></a>

	</div>

	<form method="post" action="" id="form-feedback">

		<div class="row-span">

			<textarea id="myNotes" placeholder="'.$core->get_Lang('Enter your notes').'." class="textarea full" style="height:250px;">'.$clsUser->getOneField('notes',$user_id).'</textarea>

			<div class="clearfix"></div>

			<div class="modal-footer">

				<a id="btnUpdateNotes" user_id="'.$user_id.'" style="padding:10px" class="btn btn-primary">

					<i class="icon-ok icon-white bigger-110"></i> '.$core->get_Lang('Save').'

				</a>

			</div>

		</div> 

	</div>

	<script type="text/javascript">

		$(function(){

			$(document).on(\'click\',\'#btnUpdateNotes\',function(ev){

				var $_this = $(this);

				var adata = {};

				adata[\'clsTable\'] = \'User\';

				adata[\'pkey\'] = \'user_id\';

				adata[\'pvalTable\'] = $_this.attr(\'user_id\');

				adata[\'toField\'] = \'notes\';

				adata[\'val\'] = $(\'#myNotes\').val();

				adata[\'allowDuplicate\'] = 1;

				vietiso_loading(1);

				$.ajax({

					type: "POST",

					url: path_ajax_script + \'/?mod=home&act=saveField\',

					data: adata,

					success: function(html) {

						vietiso_loading(0);

						alertify.success(\'Sucess !\');

					}

				});

				return false;

			});

		});

	</script>';

	echo $html; die();

}

function default_loginAgain(){

	global $core;

	echo('OK');die();

}

function default_doLogin(){

	global $assign_list,$_CONFIG, $_SITE_ROOT,$mod;

	global $core,$clsModule,$clsButtonNav;

	$core->_SESS->doLoginAgain($core->_SESS->user_id);

}

?>