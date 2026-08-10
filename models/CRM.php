<?
/*======================================================================*\
|| #################################################################### ||
|| # ISOCMS 4.1.0 By Luong Tien Dung (luongtiendung@gmail.com)
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2013 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class CRM{
	function __construct(){
		// Something...
	}
	static function _parse_campaign($campaigns_new, $campaigns_old){
		$ret = array();
		foreach($campaigns_new as $campaign_id){
			if(!in_array($campaign_id, $campaigns_old)){
				$ret[0][] = $campaign_id;
			}
		}
		foreach($campaigns_old as $campaign_id){
			if(!in_array($campaign_id, $campaigns_new)){
				$ret[1][] = $campaign_id;
			}
		}
		return $ret;
	}
	static function encryptID($text){
		return base64_encode($text.'-VietISO');
	}
	static function decryptID($text){
		return str_replace('-VietISO','',base64_decode($text));
	}
	static function renderHTMLLoading(){
		return '<div class="indicator">
			<img src="'.URL_IMAGES.'/loading.gif" width="30px" height="auto" alt="Loading..."> Loading...
		</div>';
	}
	static function renderHTMLNoDocument($text){
		return '<div class="text-center">
			<img class="mb-2" src="'.ICON_NODOCUMENT.'" width="40px" />
			<p>'.$text.'<p>
		</div>';
	}
	function renderHTMLButton($button='dropdown'){
		switch($button){
			case 'dropdown':
				return '';
				break;
			default:
				return '';
				break;
		}
	}
	static function renderHTMLButtonBack($params, $padding="0"){
		global $core, $clsISO;
		$props = $clsISO->make_attrs_builder($params);
		return '<a href="javascript:void();" class="back goToPage" '.$props.' style="padding:'.$padding.'px" title="'.$core->get_Lang('Back').'">
			<img src="'.ICON_BACK.'" alt="'.$core->get_Lang('Back').'" />
		</a>';
	}
	static function getSelectUserOptions($user_id=null,$any=false) {
		global $core,$adminid,$clsISO;
		$clsUser = new User();
		
			$cond = "1=1";
			if(!$any) $cond .= " and is_trash=0 and is_active='1'";
			$lstUser = $clsUser->GetAll("{$cond} order by user_id asc","user_id,full_name,first_name,last_name");
		
		$html = '';
		if(!empty($lstUser)){
			foreach($lstUser as $usr){
				$sltc = ($user_id==$usr[$clsUser->pkey])?'selected="selected"':'';
				$html .= '<option value="'.$usr[$clsUser->pkey].'" '.$sltc.'>'.$clsUser->getFullName($usr[$clsUser->pkey]).'</option>';
			}
			unset($lstUser);
		}
		return $html;
	}
	static function getSelectUserOptionsInCRM($user_id=null,$any=false) {
		global $core,$adminid,$clsISO;
		$clsUser = new User();
		
			$cond = "1=1";
			if(!$any) $cond .= " and is_trash=0 and is_active='1'";
			$cond .= " and user_group_id not in (".USER_GROUP_DESIGNER.",".USER_GROUP_TECHNICAL.",".USER_GROUP_SEO_PROJECT.",".USER_GROUP_PROBATION.",".USER_GROUP_PROJECT_MEMBER.",".USER_GROUP_OUTSOURCE.")";
			$lstUser = $clsUser->GetAll("{$cond} order by user_id asc","user_id,full_name,first_name,last_name");
		
		$html = '';
		if(!empty($lstUser)){
			foreach($lstUser as $usr){
				$sltc = ($user_id==$usr[$clsUser->pkey])?'selected="selected"':'';
				$html .= '<option value="'.$usr[$clsUser->pkey].'" '.$sltc.'>'.$clsUser->getFullName($usr[$clsUser->pkey]).'</option>';
			}
			unset($lstUser);
		}
		return $html;
	}
	static function getSelectOptionsPotential($admin_id, $potential_id=0){
		global $core,$adminid,$clsISO;	
		$clsPotential = new Potential();
		$html = ''; 
		
		$cond = "is_trash=0 and user_id='{$admin_id}'";
		$lstPotential = $clsPotential->GetAll($cond, "potential_id,name");
		if(!empty($lstPotential)){
			foreach($lstPotential as $potential){
				$sltc = ($potential[$clsPotential->pkey]==$potential_id)?'selected':'';
				$html .= '<option value="'.$potential['potential_id'].'" '.$sltc.'>#'.$potential['potential_id'].' '.ucfirst($potential['name']).'</option>';
			}
			unset($lstPotential);
		}
		return $html;
	}
	static function getSelectOptionsCampaign($potential_id){
		global $core,$adminid,$clsISO;	
		$clsBusinessCampaign = new BusinessCampaign();
		$clsBusinessCampaignPotential = new BusinessCampaignPotential();
		$cond = "is_trash=0 and type='CRM'";
		if($clsISO->checkPermission('full_permissions_crm')==0){
			$cond .= " and (user_id='{$adminid}' or use_globe='1' or admin_list like '%|{$adminid}|%')";
		}
		
		$html = '';
		$lstCampaign = $clsBusinessCampaign->GetAll($cond,"business_campaign_id,title");
		if(!empty($lstCampaign)){
			$campaignList = $clsBusinessCampaignPotential->getCampaignList($potential_id);
			foreach($lstCampaign as $campaign){
				$sltc = in_array($campaign[$clsBusinessCampaign->pkey], $campaignList)?'selected="selected"':'';
				$html .= '<option value="'.$campaign[$clsBusinessCampaign->pkey].'" '.$sltc.'>'.$campaign['title'].'</option>';
			}
			unset($lstCampaign);
		}
		return $html;
	}
	static function checkPermissAssignAdmin(){
		global $core,$adminid,$clsISO;
		$admindata = $core->_USER;
		if($adminid==1) return 1; // Supper
		if($admindata['is_super']==1) return 1;
		if($admindata['admin_assign_client']==1) return 1;
		return 0;
	}
	static function checkPermissImportClient(){
		global $core,$adminid,$clsISO;
		$admindata = $core->_USER;
		if($adminid==1) return 1; // Supper
		if($admindata['is_super']==1) return 1;
		if($admindata['admin_import_client']==1) return 1;
		return 0;
	}
	static function checkPermissAssignClient($potential_id, $onePotential=null){
		global $core,$adminid,$clsISO;	
		$clsPotential = new Potential();
		
		if($adminid==1) return 1;
		if($clsISO->checkPermission('full_permissions_crm')) return 1;
		if(is_null($onePotential) || !isset($onePotential['client_id'])){
			$onePotential = $clsPotential->getOne($potential_id,'client_id');	
		}
		$client_id = $onePotential['client_id'];
		if(empty($client_id)) 
			return 1;
		return 0;
	}
	static function getTemplateCRM($selected=0){
		global $core,$adminid,$clsISO;
		$clsEmailTemplate = new EmailTemplate();
		
		$html = '';
		$lstEmailTemplate = $clsEmailTemplate->getAll("_group='CRM'",$clsEmailTemplate->pkey.",name");
		if(!empty($lstEmailTemplate)){
			foreach($lstEmailTemplate as $template){
				$sltc = ($selected==$template[$clsEmailTemplate->pkey]) ? 'selected' : '';
				$html .= '<option value="'.$template[$clsEmailTemplate->pkey].'" '.$sltc.'>'.$template['name'].'</option>';
			}
			unset($lstEmailTemplate);
		}
		return $html;
	}
	static function getNumberDayRangeDate($fromdate, $todate){
		global $core;
		if($fromdate > $todate) return false;
		$diff = $todate - $fromdate;
		$numberday = floor($diff/86400);
		return $numberday;
	}
	static function getFORMSelectOptionsSimple($selected, $options){
		global $core;
		$html = '';
		foreach($options as $option){
			$html .= '<option value="'.$option.'">'.$option.'</option>';
		}
		return $html;
	}
	static function getFORMSelectOptionsAdvanced($selected=null, $options=array(), $uppercase=false){
		global $core;
		$html = '';
		foreach($options as $option => $text){
			$html .= '<option value="'.$option.'" '.($selected==$option?'selected="selected"':'').'>'.($uppercase?strtoupper($text):$text).'</option>';
		}
		return $html;
	}
	static function genColumn($index){
		$char = 'A';
		$listChar = array();
		// 1 characters
		$arr = array();
		for($i = 1; $i<= 26; $i++){
		  $arr[] = $char;
		  ++$char;
		}
		// 2 characters
		$listChar = $arr;
		for($i=0; $i<count($arr); $i++){
			for($j=0; $j<count($arr); $j++){
				$listChar[] = $arr[$i].$arr[$j];
			}
		}
		return $listChar[$index];
	}
	static function getFORMSelectPriority($selected=0){
		$arr = array(
			1 => 'Low',
			2 => 'Medium',
			3 => 'Important',
			4 => 'Urgent'
		);
		return self::getFORMSelectOptionsAdvanced($selected, $arr);
	}
	static function getAdminArray($arr){
		global $clsISO;
		$clsUser = new User();
		$htmlAdmin = '';
		if(!empty($arr)){ $ii=0;
			foreach($arr as $user_id){
				$htmlAdmin .= ($ii==0?'':',') . $clsUser->getFullName($user_id);
				++$ii;
			}
		}
		return $htmlAdmin;
	}
	static function getStatusCampaign($business_campaign_id, $oDataTable=null){
		global $clsISO;
		$clsBusinessCampaign = new BusinessCampaign();
		if(!isset($oDataTable['start_date']) && !isset($oDataTable['end_date'])){
			$oDataTable = $clsBusinessCampaign->getOne($business_campaign_id,'start_date,end_date');
		}
		$current_time = time();
		$start_date = $oDataTable['start_date'];
		$end_date = $oDataTable['end_date'];
		
		$label = 'warning';
		$text = __('InActive');
		if($current_time >= $start_date && $current_time < $end_date){
			$label = 'success';
			$text = __('Active');
		}
		return '<span class="label label-'.$label.'">'.$text.'</span>';
	}
	static function convertToArray($sourse=array()){
		$ret = array();
		if(!empty($sourse)){
			foreach($sourse as $item){
				$ret[$item['column']][] = $item;
			}
		}
		return $ret;
	}
	static function checkCompare($source, $haystack){
		$arraysAreEqual = ($source == $haystack);
		return $arraysAreEqual;
	}
	static function array_equal($source, $haystack){
		// giống = 1, khác = 0
		if(!empty($source) || !empty($haystack)){
			if((!empty($source) && empty($haystack)) 
			   || (!empty($haystack) && empty($source)))
			return 0;
			// Comparing the values
			$result = array_diff($source, $haystack);
			return empty($result) ? 1 : 0;
		}
		return 1;
	}
	static function sendEmail($to, $subject, $message){
		return 1;
	}
	static function makeOptionSelectUserFromSource($selected=array(), $multiple=false, $source){
		global $core, $dbconn;
		$clsVS_Admin = new VS_Admin();
		
		$html  = '';
		if(!empty($source)){
			if($multiple){
				foreach($source as $user){
					//'.(in_array($user[$clsVS_Admin->pkey], $selected)?' selected':'').'
					$html .= '<option value="'.$user[$clsVS_Admin->pkey].'">#'.$user[$clsVS_Admin->pkey].' '.$clsVS_Admin->getFullName($user[$clsVS_Admin->pkey]).'</option>';
				}
			}else{
				foreach($source as $user){
					$html .= '<option value="'.$user[$clsVS_Admin->pkey].'"'.($selected==$user[$clsVS_Admin->pkey]?' selected':'').'>#'.$user[$clsVS_Admin->pkey].' '.$clsVS_Admin->getFullName($user[$clsVS_Admin->pkey]).'</option>';
				}
			}
		}
		return $html;
	}
	static function getValueFieldInArray($array_source=array(), $field, $def_default=0){
		if(empty($array_source)) 
			return $def_default;
		
		if(isset($array_source[$field]))
			return $array_source[$field];
		return $def_default;
	}
}
?>