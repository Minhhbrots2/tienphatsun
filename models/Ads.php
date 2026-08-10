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
class Ads extends dbBasic{
	function __construct(){
		$this->pkey = "ads_id";
		$this->tbl = DB_PREFIX."ads";
	}
	function getMaxId(){
		$res = $this->getAll("1=1 order by ads_id desc");
		return intval($res[0]['ads_id'])+1;
	}
	function checkSlug($slug){
		$res = $this->getAll("slug='".$slug."'");
		if(is_array($res) && count($res)>0)
			return 0;
		return 1;
	}
	function getBySlug($slug){
		$res = $this->getAll("slug='".$slug."'");
		return $res[0]['ads_id'];
	}
	function getTitle($ads_id){
		$one = $this->getOne($ads_id, "title");
		return $one['title'];
	}
	function getIntro($ads_id){
		$one = $this->getOne($ads_id);
		return $one['intro'];
	}
	function getSlug($ads_id){
		$one = $this->getOne($ads_id);
		return $one['slug'];
	}
	function getLink($ads_id){
		$one = $this->getOne($ads_id, "url");
		return $one['url'];
	}
	function getListGroup($ads_group_id){
		$ads_group_id = '|'.$ads_group_id.'|';
		$listAds = $this->getAll("is_trash=0 and list_id like '%$ads_group_id%'");
		return $listAds;
	}
	function getListGroupLimit($ads_group_id,$limit){
		$ads_group_id = '|'.$ads_group_id.'|';
		$listAds = $this->getAll("is_trash=0 and end_date>".time()." and list_id like '%$ads_group_id%' limit 0,$limit");
		return $listAds;
	}
	function getListGroupLimitFlash($ads_group_id,$limit){
		$ads_group_id = '|'.$ads_group_id.'|';
		$listAds = $this->getAll("is_trash=0 and end_date>".time()." and list_id like '%$ads_group_id%' and image like '%.swf%' limit 0,$limit");
		return $listAds;
	}
	function checkContain($haystack,$needle){
		$pos = strpos($haystack,$needle);
		if($pos === false) {
			return 0;
		}else {
			return 1;
		}
	}
	function checkFlash($ads_id){
		$one = $this->getOne($ads_id);
		if($this->checkContain($one['image'],'.swf')) 
			return 1;
		return 0;
	}
	function checkFlashGroup($ads_group_id){
		$ads_group_id = '|'.$ads_group_id.'|';
		$listAds = $this->getAll("is_trash=0 and list_id like '%$ads_group_id%'");
		if($listAds[0]['ads_id']!=''){
			for($i=0;$i<count($listAds);$i++){
				if($this->checkFlash($listAds[$i]['ads_id']))
					return 1;
			}
		}
		return 0;
	}
	function checkFlashGroupCat($ads_group_id,$cat_id){
		$ads_group_cat_id = '|c'.$cat_id.'g'.$ads_group_id.'|';
		$listAds = $this->getAll("is_trash=0 and list_id like '%$ads_group_cat_id%'");
		if($listAds[0]['ads_id']!=''){
			for($i=0;$i<count($listAds);$i++){
				if($this->checkFlash($listAds[$i]['ads_id']))
					return 1;
			}
		}
		return 0;
	}
	function getListGroupCatLimit($ads_group_id,$cat_id,$limit){
		$ads_group_cat_id = '|c'.$cat_id.'g'.$ads_group_id.'|';
		$listAds = $this->getAll("is_trash=0 and end_date>".time()." and list_id like '%$ads_group_cat_id%' limit 0,$limit");
		return $listAds;
	}
	function getListGroupCatLimitFlash($ads_group_id,$cat_id,$limit){
		$ads_group_cat_id = '|c'.$cat_id.'g'.$ads_group_id.'|'; 
		$listAds = $this->getAll("is_trash=0 and end_date>".time()." and list_id like '%$ads_group_cat_id%' and image like '%.swf%' limit 0,$limit");
		return $listAds;
	}
	function getAdsHTML($code, $responsive=true){
		$clsAdsGroup = new AdsGroup();
		$html = '';
		$lstGroup = $clsAdsGroup->getAll("_code='$code' limit 0,1");
		if(!empty($lstGroup)){
			$ads_group_id = $lstGroup[0][$clsAdsGroup->pkey];
			$width = $lstGroup[0]['_width'];
			$height = $lstGroup[0]['_height'];
			#
			$lstAds = $this->getAll("is_trash=0 and is_online=1 and list_id like '%|$ads_group_id|%' order by order_no asc");
			if(!empty($lstAds)){
				$html .= '
				<style type="text/css">
					#adsbox_'.$ads_group_id.'{overflow:hidden;width:'.$width.'px;height:'.$height.'px;position:relative;}
					#adsbox_'.$ads_group_id.'>.adsbox__item{position:absolute;display:block;outline:none;width:'.$width.'px;height:'.$height.'px;left:0;top:0}
					@media (max-width: 991px){
						#adsbox_'.$ads_group_id.'{overflow:hidden;width:100%;height:'.$height.'px; position:relative; margin:0 0 15px 0;}
						#adsbox_'.$ads_group_id.' > .adsbox__item{ display:block; width:100%; height:auto; }
						#adsbox_'.$ads_group_id.' > .adsbox__item > img{ width:!00%; height:auto;}
					}
				</style>
				<script type="text/javascript">
					$(function(){
						var k = 0;	
						$(\'#adsbox_'.$ads_group_id.'>a.adsbox__item\').each(function(){
							k = k+1;
							$(this).attr(\'rel\',k);	
						});	
						if(k>1){
							setInterval(function(){	
								var active = $(\'#adsbox_'.$ads_group_id.'>a.adsbox__item:visible\');
								var i = $(active).attr(\'rel\');
								var n = parseInt(i);	
								if(n==k){
									$(active).hide();	
									$(\'#adsbox_'.$ads_group_id.'>a.adsbox__item:first\').show();
								}else{
									$(active).hide();
									$(active).next().show();
								}
							},3000);
						}
					});
				</script>
				<div class="adsbox banner-hover" id="adsbox_'.$ads_group_id.'">';
				$a=0;
				foreach($lstAds as $k=>$v){
					$html .= '<a href="'.($v['url']).'" title="'.($v['title']).'" '.($a==0?'':'style="display:none"').' class="adsbox__item">
						<img '.($responsive?' class="img-responsive"':'').' src="'.($v['image']).'" width="'.$width.'px" height="'.$height.'px" />
					</a>';
					++$a;
				}
				unset($lstAds);
				$html .= '</div>';
			}
		}
		return $html;
	}
}
?>