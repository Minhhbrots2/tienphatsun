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

class News extends dbBasic{

	function __construct(){

		global $_LANG_ID;

		$this->pkey = "news_id";

		$this->tbl = DB_PREFIX."news";

	}

	function getLink($news_id,$oneItem=null,$link=""){

		global $clsISO;

		return $link.'/ban-tin/'.$clsISO->base64url_encode($news_id);

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

	function getBySlug($slug) {

        $res = $this->getAll("is_trash=0 and slug='$slug'");

        return $res[0][$this->pkey];

    }

	function getRegDate($pvalTable,$type='') {

		global $clsISO;

		$one=$this->getOne($pvalTable);

		if ($type == '4'){

            //$format_day =  date_format($clsISO->formatDate($one['reg_date'],2),'d');

            $reg_date =  $clsISO->formatDate($one['reg_date']);

            $format_day = date_format(DateTime::createFromFormat('d-m-Y',$reg_date),'d');

            $format_date = date_format(DateTime::createFromFormat('d-m-Y',$reg_date),'m-Y');

            $format_date = '<span class="date_day">'.$format_day.'</span> '.$format_date.'';

            return $format_date;

        }

		return $clsISO->formatDate($one['reg_date'],2);

	}

	function getIntro($pvalTable, $oDataTable = null){

		if(is_null($oDataTable)){

			$oDataTable = $this->getOne($pvalTable, "content");

		}

		return html_entity_decode($oDataTable['content']);

	}

	function getContent($pvalTable){

		$one=$this->getOne($pvalTable);

		return html_entity_decode($one['content']).'xxx';

	}

	function getAuthor($pvalTable){

		$one=$this->getOne($pvalTable);

		return html_entity_decode($one['author']);

	}

	function getStripIntro($pvalTable){

		$one=$this->getOne($pvalTable);

		if(!empty($one['intro']))

			return strip_tags(html_entity_decode($one['intro']));

		return strip_tags(html_entity_decode($one['content']));

	}

	function getImage($pvalTable, $w, $h){

		global $clsISO;

		$oneTable = $this->getOne($pvalTable, "image");

		if($oneTable['image']!=''){

			$image = $oneTable['image'];

			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);

		}

		$noimage = URL_IMAGES.'/noimage.png';

		return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($noimage);

	}

	function getImageUrl($pvalTable){

		$one = $this->getOne($pvalTable);

		return $one['image'];

	}

	function getListNews($news_cat_id){

		$lst = $this->getAll("is_trash=0 and news_cat_id='$news_cat_id' order by order_no desc");

		return $lst;

	}

	function doDelete($news_id){

		// Delete News

		$this->deleteOne($news_id);

		return 1;

	}

	function checkContain($haystack,$needle){

		$pos = strpos($haystack,$needle);

		if($pos === false) {

			return 0;

		}else {

			return 1;

		}

	}

	function crawImage($news_id){

		global $core, $_LANG_ID, $clsISO;

		$oneNews = $this->getOne($news_id, "slug,content");

		$slug = $oneNews['slug'];

		$content = $oneNews['content'];

		preg_match_all("/src=&quot;(.*?)&quot;/si", $content, $matches);

		if($matches[1][0]==''){

			preg_match_all("/src=\"(.*?)\"/si", $content, $matches);

		}

		if(!empty($matches[1])){

			for($i=0;$i<count($matches[1]);$i++){

				$url = $tmp = $matches[1][$i];

				if($url!='' && $this->checkContain($url,'/images/content/')==0){

					$allowExt="jpeg, jpg, gif, png";

					$ext = $path_parts['extension'];

					if($ext!='jpg'&&$ext!='png'&&$ext!='gif'){

						$ext = 'jpg';

					}

					if (strpos($allowExt, $ext)===false){} else {

						list($width, $height) = getimagesize($url);

						if($width*$height != 0){

							$clsUploadFile = new UploadFile();

							$img = $clsUploadFile->uploadImageFromUrl($url, $slug);

							$content = str_replace($tmp, $img, $content);

						}

					}	

				}

			}

			$this->updateOne($news_id,"content='".addslashes($content)."'");

		}

	}

	function getTotalComment($news_id){

		$clsComment = new Comment();

		return $clsComment->countItem("table_id='{$news_id}'");

	}

	function checkLiked($news_id, $oDataTable = array()){

		global $core,$dbconn,$_LANG_ID,$clsISO,$profile_id;

		if(!isset($oDataTable['liked_json'])){

			$oDataTable = $this->getOne($news_id, "liked_json");

		}

		$liked_json = $oDataTable['liked_json'];

		$liked_json = !empty($liked_json) 

			? json_decode(html_entity_decode($liked_json), true) 

			: array();

		

		$linked = 0;

		if(!empty($liked_json)){

			foreach($liked_json as $name => $ids){

				if(!empty($ids) && in_array($profile_id, $ids)){

					$linked = 1;

					break;

				}

			}

		}

		return $linked;

	}

	function genTotalLike($news_id,$one=null){

		global $core,$dbconn,$_LANG_ID,$clsISO,$profile_id;

		if(!isset($one['liked_json'])) {

			$one = $this->getOne($news_id,"liked_json");

		}

		$like_json = $one['liked_json'];

		$like_json = !empty($like_json) ? json_decode(html_entity_decode($like_json), true) : array();

		

		$total_liked = 0; $groups_liked = array();

		if(!empty($like_json)){

			foreach($like_json as $key => $ids){

				if(!empty($ids)){

					$groups_liked[] = $key;

					$total_liked+= count($ids);

				}

			}

		}

		$html= '';

		foreach($groups_liked as $group){

			$html.= '<span class="item '.$group.'"><img src="'.URL_IMAGES.'/graphics/'.$group.'.svg"></span>';

		}

		$html.= '<a class="number" href="javascript:void(0);">'.$total_liked.' người thích điều này</a>';

		return $html;

	}

	function checkCanEdit($news_id, $oDataTable=array()){

		if(!isset($oDataTable['reg_date'])){

			$oDataTable = $this->getOne($news_id, "reg_date");

		}

		$reg_date = $oDataTable['reg_date'];

		if($reg_date+5*60 > time())

			return 1;

		return 0;

	}

	function getImageGrid($news_id, $images){

		global $core,$dbconn,$_LANG_ID;

		$html = '';

		$list_images = @json_decode($images, true);

		if(!empty($list_images)){ $ii = 1; // Init

			$html .= '<div id="gallery-grid-'.$news_id.'"></div>

			<script type="text/javascript">

				setTimeout(function(){

					$(\'#gallery-grid-'.$news_id.'\').imagesGrid({

						cells:4,

						images: [';

						foreach($list_images as $img){

							$html .="'".$img."'".($ii==count($list_images)?'':',');

							++$ii;

						}

						$html .= '],

						align: true

					});

				},500);

			</script>';

		}

		return $html;

	}

	function formatHTML($content){

		$content = html_entity_decode($content);

		$content = $this->restoreLineBreaks($content);

		//$regex = '~(?<!src=["\'])(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';

		//$content = preg_replace($regex, '<a href="$0" target="_blank">$0</a>', $content);

		//$content = preg_replace('/((https?):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?)/', '<a href="\1" target="_blank">\1</a>', $content);

		return $content;

	}

	function restoreLineBreaks($content){

		$blockTags = 'address|article|aside|blockquote|dd|div|dl|dt|figcaption|figure|footer|h[1-6]|header|hr|li|ol|p|pre|section|table|tbody|td|tfoot|th|thead|tr|ul';

		$content = preg_replace('#(</?(?:'.$blockTags.')\b[^>]*>)[ \t]*(?:\r\n|\r|\n)+#i', '$1', $content);

		$content = preg_replace('#(?:\r\n|\r|\n)+[ \t]*(?=</?(?:'.$blockTags.')\b)#i', '', $content);

		return nl2br($content);

	}

	function get_attachment_html($attachments){

		$html = '<hr class="my-2" />

		<div class="text-muted mb-2"><i class="fa fa-paperclip" aria-hidden="true"></i> File đính kèm</div>

		<div class="mb-3">';

		foreach($attachments as $key => $val){

			$html.= '<a href="'.$val['url'].'" class="download py-1" target="_blank">'.$val['name'].'</a>';

		}

	$html.='</div>';

		return $html;

	}

	

	function sendEmail($news_id){

		global $core, $dbconn, $clsISO, $profile_id, $oneProfile;

		$clsProfile = new Profile();

		$clsProperty = new Property();

		$clsEmailTemplate = new EmailTemplate();

		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_NEWS);

		$subject = $clsEmailTemplate->getSubject(_MAIL_NEWS,$oneEmailTemplate);

		$message = $clsEmailTemplate->getContent(_MAIL_NEWS,$oneEmailTemplate);

		$fromEmail = $clsEmailTemplate->getFromEmail(_MAIL_NEWS);

		$fromName = $clsEmailTemplate->getFromName(_MAIL_NEWS);



		$oneNews = $this->getOne($news_id);

		$lstImage = $clsISO->to_array_json($oneNews["images"]);

		$html_image = "";

		if(!empty($lstImage)) {

			foreach ($lstImage as $image) {

				$html_image .='<div class="imgs-grid-image" style="margin-bottom: 5px">

					<div class="image-wrap">

						<img src="'.DOMAIN_NAME.$image.'" alt="" title="" style="width:100%">

					</div>

				</div>';

			}

		}

		$replace_fields = array(

			'{title}' => $oneNews["title"],

			'{full_name}' => $clsProfile->getFullName($oneNews["user_id"]),

			'{content}' => $this->formatHTML($oneNews["content"]),

			'{image}' => $html_image

		);

		foreach($replace_fields as $key => $val){

			$subject = str_replace($key, $val, $subject);

			$message = str_replace($key, $val, $message);

		}

		// Send Email

		$lstProfile = $clsProfile->getAll("`status_id` > 0 AND `status_id` <> '"._STATUS_STAFF_OFF_ID."' AND `is_trash` = '0'");

		foreach ($lstProfile as $key => $val) {

			$toemail = $val['email'];

			$toname = $val['full_name'];

			$is_send_email = $clsISO->sendEmailSystem($fromEmail,$fromName,$toemail, $toname, $subject, $message);

		}

		

		return $is_send_email;

	}

}

class NewsRelated extends dbBasic{

	function __construct(){

		$this->pkey = "news_related_id";

		$this->tbl = DB_PREFIX."news_related";

	}

	function checkExist($target_id, $news_id, $type=''){

		global $dbconn, $core;

		$rs = $this->countItem("is_trash=0 and target_id='$target_id' and news_id='$news_id'");

		return ($rs > 0) ? 1: 0;

	}

	function get_Items($target_id, $limit=0){

		$cond = "is_trash=0 and target_id='$target_id' order by reg_date DESC";

		if(intval($limit) > 0){

			$cond .= " limit 0,$limit";

		}

		return $this->getAll($cond);

	}

}

?>