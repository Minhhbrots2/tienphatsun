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

class Interior extends dbBasic{

	function __construct(){

		global $_LANG_ID;

		$this->pkey = "interior_id";

		$this->tbl = DB_PREFIX."interior"; 

	}

	function getTitle($pvalTable, $oDataTable = array()){

		if(!isset($oDataTable['title'])){

			$oDataTable = $this->getOne($pvalTable, "title");

		}

		return $oDataTable['title'];

	}

	function getSlug($pvalTable){

		$one=$this->getOne($pvalTable);

		return $one['slug'];

	}

	function getLink($interior_id,$one=null){

		if(!isset($one['slug'])){

			$one = $this->getOne($interior_id,"slug");

		}

		return DOMAIN_URL."/thiet-ke-noi-that/".$one['slug']."-ct".$interior_id.".html";

	}

	function getIntro($pvalTable,$one=null){

		if(!isset($one['intro'])){

			$one=$this->getOne($pvalTable);

		}

		return $one['intro'];

	}

	function getIframeVideo($video_type,$video){

		global $core, $dbconn;

		$html = "";

		if($video_type == 'upload'){

			if(!empty($video)){

				$html = '<div class="iframe-wrapper">

					<iframe class="rounded-2" src="'.$video.'" frameborder="0" width="100%" height="400px"></iframe>

				</div>';

			}

		} else if($video_type=='youtube'){

			if(!empty($video) && preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/',$video)){

				preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $video, $matches);

				$html = '<div class="iframe-wrapper">

					<iframe class="rounded-2" src="https://www.youtube.com/embed/'.$matches[1].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen width="100%" height="400px"></iframe>

				</div>';

			}

		}

		return $html;

	}

	function getYouTubeThumbnail($url) {

		parse_str(parse_url($url, PHP_URL_QUERY), $query);

		return "https://img.youtube.com/vi/".$query["v"]."/sddefault.jpg";

	}

	function saveImageContent($content) {

		$regex = '/https?:\/\/[^\s]+(\.jpg|\.jpeg|\.png|\.gif)/i';

		$saveDir = ROOTPATH."/images/dropzone/interior/";

		if (!is_dir($saveDir)) {

			mkdir($saveDir, 0777, true);

		}

		$content = preg_replace_callback($regex, function ($matches) use ($saveDir) {

			$imageUrl = $matches[0];

			$imageName = "interior_".time()."_" . basename($imageUrl);

			$imageContent = file_get_contents($imageUrl);

			if ($imageContent !== false) {				

				$savePath = $saveDir.$imageName;

				// Save the image

				if (file_put_contents($savePath, $imageContent)) {

					return "/images/dropzone/interior/".$imageName;

				} else {

					return $imageUrl;

				}

			} else {

				return $imageUrl;

			}

		}, $content);

		return $content;

	}

	

	function getLinkAuthor($profile_id,$one=null) {

		$clsProfile = new Profile();

		if(!isset($one['full_name_slug'])) {

			$one = $clsProfile->getOne($profile_id,"full_name_slug");

		}

		return "/thiet-ke-noi-that/author/".$one['full_name_slug']."-pf".$profile_id.".html";

	}

	function getLinkCat($cat_id,$oneCat=null) {

		$clsProperty = new Property();

		if(!isset($oneCat['slug'])) {

			$oneCat = $clsProperty->getOne($cat_id,"slug");

		}

		return "/thiet-ke-noi-that/".$oneCat['slug'];

	}

	function getLinkCompany($company_id,$one=null) {

		$clsCompany = new Company();

		if(!isset($one['slug'])) {

			$one = $clsCompany->getOne($company_id,"slug");

		}

		return "/thiet-ke-noi-that/cong-ty/".$one['slug']."-c".$company_id.".html";

	}

}

?>