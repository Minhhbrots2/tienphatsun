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
class Comment extends dbBasic{
	function __construct(){
		$this->pkey = "comment_id";
		$this->tbl = DB_PREFIX."comment";
	}
	function generateStar($number_star){
		$max_star = 5;
		$number_star = intval($number_star);
		$html = '<div class="rating-star__box" style="vertical-align:middle">';
		for($i=1; $i<=$number_star; $i++){
			$html .='<i class="star_full"></i>';
		}
		for($i=1; $i<=($max_star-$number_star); $i++){
			$html .='<i class="star_null"></i>';
		}
		$html .= '</div>';
		return $html;
	}
	function getTitle($comment_id){
		return $this->getOneField('title',$comment_id);	
	}
	function getContent($comment_id){
		return html_entity_decode($this->getOneField('content',$comment_id));	
	}
	function getItem($comment_id, $type_id='_reply'){
		global $core, $dbconn, $clsISO;
		$where = "is_trash=0 and is_active='1' and type_id='{$type_id}' and parent_id='{$comment_id}'";
		$lst = $this->getAll("{$where} order by order_no DESC");
		return $lst;
	}
	function getPercent($number_star, $for_id){
		$total_reviews = $this->countItem("for_id='{$for_id}'");
		$where = "type_id='_review' and for_id='{$for_id}' and number_star='{$number_star}'";
		$number_reviews = $this->countItem($where);
		//return $total_reviews;
		if($number_reviews > 0)
			return round($number_reviews/$total_reviews*100);
		return 0;
	}
	function getTotalComment($table_id, $clsTable){
		$where = "is_trash=0 and is_active=1 and table_id='{$table_id}' and clsTable='{$clsTable}'";
		$total = $this->countItem($where);
		return $total;
	}
	function getTotalReply($comment_id, $status=null){
		$where = "parent_id='{$comment_id}'";
		if(!is_null($status)) $where .= " and is_active='{$status}'";
		$total = $this->countItem($where);
		return $total;
	}
	function doDelete($comment_id){
		$this->deleteByCond("type_id='_reply' and parent_id='{$comment_id}'");
		return $this->deleteOne($comment_id);
	}
	function countTotalWishlist($comment_id, $type='_comment') {
		$clsWishlist=new Wishlist();
        $sql = "for_id='{$comment_id}' and type='{$type}'";
        return $clsWishlist->countItem($sql);
    }
	function genTotalLike($comment_id,$one=null){
		global $core,$dbconn,$_LANG_ID,$clsISO,$profile_id;
		if(!isset($one['liked_json'])) {
			$one = $this->getOne($comment_id,"liked_json");
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
		
		return $total_liked;
	}
}
?>