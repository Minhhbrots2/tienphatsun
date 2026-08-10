<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class Campaign extends dbBasic{
	function __construct(){
		$this->pkey = "campaign_id";
		$this->tbl = DB_PREFIX."campaign";	
	}
    function getOptTemplate($template){
        $arr_templates = array(
            'template_1' => 'Giao diện 1',
            'template_2' => 'Giao diện 2',
            'template_3' => 'Giao diện 3'
        );
        $html = "";
        foreach($arr_templates as $key => $val){
            $html.= '<option value="'.$key.'"'.($template==$key?' selected':'').'>'.$val.'</option>';
        }
        return $html;
    }
    function getLink($campaign_id){
        global $core,$dbconn,$clsISO;
        return sprintf('/campaign/%s.html',$core->encryptID($campaign_id));
    }
	function getOptProduct($selected=""){
		 $arr_product = array(
			'ALL'		=> 'Tất cả',
            'CAO_TANG' 	=> 'Cao tầng',
            'THAP_TANG' => 'Thấp tầng',
            'CHO_THUE' 	=> 'Cho thuê',
			'MWF' => 'Masteri homes'
        );
        $html = "";
        foreach($arr_product as $key => $val){
            $html.= '<option value="'.$key.'"'.($selected==$key?' selected':'').'>'.$val.'</option>';
        }
        return $html;
	}
	function getTitle($campaign_id, $oDataTable = array()) {
        global $_LANG_ID;
		if(!isset($oDataTable['title'])){
			$oDataTable = $this->getOne($campaign_id, "title");
		}
        return $oDataTable['title'];
    }
	function getTitleArray($arrs){
		$titles = "";	
		if(!empty($arrs)){
			$tmp = array();
			$list = $this->getAll("{$this->pkey} in (".implode(",", $arrs).")", "title");
			if(!empty($list)){
				foreach($list as $item){
					$tmp[] = $item['title'];
				}
				unset($list);
				$titles = @implode(",", $tmp);
			}
		}
		return $titles;
	}
	function getTitleFromCached($arrs = array(), $arr_cached=array()){
		$titles = "";
		if(!empty($arrs)){
			if(!empty($arr_cached)){
				$titles = array();
				foreach($arr_cached as $key=>$val){
					if(in_array($key, $arrs)){
						$titles[] = $val;
					}
				}
				return implode(',',$titles);
			} else {
				$titles = $this->getTitleArray($arrs);
			}
		}
		return $titles;
	}
}