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
class Okrs extends dbBasic{
	function __construct(){
		$this->pkey = "okrs_id";
		$this->tbl = DB_PREFIX."okrs";
	}
	function getTitle($okrs_id, $oDataTable = array()){
		$one = $this->getOne($okrs_id, "title");
		return $one['title'];
	}
	function getProgress($done_ratio=0){
		$html = '<div class="d-flex align-items-center">
			<div class="progress mr-1 w-px-75">
				<div class="progress-bar" style="width:'.$done_ratio.'%;"></div>
			</div>' . $done_ratio . '%</div>';
		return $html;
	}
	function getSubTable($parent_id){
		global $core,$dbconn,$clsISO,$profile_id,$oneProfile;
		$clsProperty = new Property();
		$html = "";
		$department_id = $oneProfile['department_id'];
		$cond = "`is_trash`=0 and `parent_id`='{$parent_id}' and ((`type_id`='"._TYPE_COMPANY_OKRS_ID."') or (`type_id`='"._TYPE_STAFF_OKRS_ID."' and `for_id`='{$profile_id}') or (`type_id`='"._TYPE_DEPARTMENT_OKRS_ID."' and `for_id`='{$department_id}'))";
		$list_okrs = $this->getAll("{$cond} order by `reg_date` DESC");
		if(!empty($list_okrs)){
			foreach($list_okrs as $key => $val){
				$okrs_id = $val[$this->pkey];
				$kr_information = $val['kr_information'];
				$kr_information = !empty($kr_information) 
					? json_decode(html_entity_decode($kr_information), true) : array();
				$total_kr = !empty($kr_information) ? count($kr_information) : 0;
				###
				$html.='<tr data-node-id="'.$okrs_id.'" data-node-pid="'.$parent_id.'">
					<td class="text-left">'.$this->getTitle($okrs_id, $val).'</td>
					<td class="text-center">
						<button onClick="$Core.okrs.view_kr(\''.$okrs_id.'\')" okrs_id="'.$okrs_id.'" class="btn btn-sm btn-outline-default">'.$total_kr.' kết quả</button>
					</td>
					<td class="text-center">'.$this->getProgress().'</td>
					<td class="text-center">0%</td>
					<!--<td class="text-left">'.$clsProperty->getTextColor($val['group_id']).'</td> -->
					<td class="text-left">'.$this->getLabel($val['type_id']).'</td>
					<td class="text-left">'.$this->getStatus($okrs_id).'</td>
					<td class="text-center">
						<div class="dropdown">
							<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">'.$clsISO->makeIcon('bx-dots-vertical-rounded').'</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" onClick="$Core.okrs.open(this,event)" okrs_id="'.$okrs_id.'" href="javascript:void(0);">'.$clsISO->makeIcon('bx-pencil me-1','Sửa').'</a>
								<a class="dropdown-item" onClick="$Core.okrs.delete(this,event)" okrs_id="'.$okrs_id.'" href="javascript:void(0);">'.$clsISO->makeIcon('bx-trash me-1','Xóa').'</a>
							</div>
						</div>
					</td>
				</tr>';
				$html.= $this->getSubTable($okrs_id);
			}
			unset($list_okrs);
		}
		// Return
		return $html;
	}
	function getLabel($type_id){
		if($type_id==1) return 'Cá nhân';
		if($type_id==2) return 'Phòng ban';
		if($type_id==3) return 'Công ty';
	}
	function getStatus($type_id){
		return '<label class="badge bg-label-secondary">
			Chưa hoàn thành
		</label>';
	}
}