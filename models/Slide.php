<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

/*======================================================================*\

|| #################################################################### ||

|| # The Classes configurations of the ISOCMS                         # ||

|| # ISOCMS 6.0.0 VietISO Techical Team (luongtiendung@gmail.com)     # ||

|| # ---------------------------------------------------------------- # ||

|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||

|| # This file may not be redistributed in whole or significant part. # ||

|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||

|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||

|| #################################################################### ||

\*======================================================================*/

class Slide extends dbBasic{

	function __construct(){

		$this->pkey = "slide_id";

		$this->tbl = DB_PREFIX."slide";

	}

	function getTitle($pvalTable, $_args= array()){

		if(is_array($_args) && @$_args['title'] != ''){

			return $_args['title'];

		}else{

			$one=$this->getOne($pvalTable);

			return $one['title'];

		}

	}

	function getSlug($pvalTable, $_args= array()){

		if(is_array($_args) && @$_args['slug'] != ''){

			return $_args['slug'];

		}else{

			$one=$this->getOne($pvalTable);

			return $one['slug'];

		}

	}

	function getLink($pvalTable, $oDataTable= array()){

		if(!isset($oDataTable['link'])){

			$oDataTable = $this->getOne($pvalTable, "link");

		}

		return $oDataTable['link'];

	}

	function getIntro($pvalTable, $oDataTable= array()){

		if(!isset($oDataTable['intro'])){

			$oDataTable = $this->getOne($pvalTable, "intro");

		}

		return $oDataTable['intro'];

	}

	function getImage($slide_id, $w, $h, $oDataTable = array()){

		global $clsISO;

		if(!isset($oDataTable['image'])){

			$oDataTable = $this->getOne($slide_id, "image");

		}

		$image = $oDataTable['image'];

		if(!empty($image) && @file_exists(ABSPATH . $image)){

			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);

		} else {

			$noimage = URL_IMAGES.'/logo-header.png';

			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($noimage);

		}

	}

	function isPdf($url){

		if(preg_match('/\b(https?:\/\/\S+(?:pdf)\S*)\b/', $url))

			return 1;

		return 0;

	}

	function isVideo($url){

		if(preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/', $url) 

		   || preg_match('/^(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*$/',$url))

			return true;

		return false;

	}

	function get_videoInfo($url){

		$info = array('type' => '', 'video_id' => 0	);

		if(preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+$/', $url)){

			preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);

			$info = array(

				'type' => 'youtube',

				'video_id' => $matches[1]

			);

		} else if(preg_match('/^(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*$/',$url)){

			preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $url, $matches);

			$info = array(

				'type' => 'vimeo',

				'video_id' => $matches[5]

			);

		}

		return $info;

	}

	function makeIframe($url, $uid) {

		$iframe  = "";

		$video = $this->get_videoInfo($url);

		if($video['type']=='youtube'){

			$iframe .= '<iframe class="radius-4" frameborder="0" width="100%" height="100%" src="https://www.youtube.com/embed/'.$video['video_id'].'" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

		}else if($video['type']=='vimeo'){

			$iframe.= '<iframe class="radius-4" frameborder="0" width="100%" height="100%" src="https://player.vimeo.com/video/'.$video['video_id'].'" webkitallowfullscreen mozallowfullscreen  allowfullscreen></iframe>';

		}

		return $iframe;

	}

	function doDelete($pvalTable){

		// Delete

		$this->deleteOne($pvalTable);

		return 1;

	}

	function getHTMLTag($slide_id){

		global $core, $dbconn, $clsISO;

		$clsTag = new Tag();

		$list_tag_id = $this->getOneField('list_tag_id', $slide_id);

		$tags_arrs = $clsISO->getArrayByTextSlash($list_tag_id);

		###

		$html = "";

		if(!empty($tags_arrs)){

			$html.= '<div class="tags mb-2">';

			foreach($tags_arrs as $tag_id){

				$html.= '<a href="/hoc-tap/tag'.$tag_id.'.html" class="tag">'.$clsTag->getTitle($tag_id).'</a>';

			}

			$html.= '</div>';

		}

		return $html;

	}

}

?>