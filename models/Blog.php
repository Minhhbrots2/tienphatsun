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
class Blog extends dbBasic {
    function __construct() {
        $this->pkey = "blog_id";
        $this->tbl = DB_PREFIX . "blog";
    }
    function getSlash($level) {
        return str_repeat("------", $level + 1);
    }
    function countByCountry($country_id) {
        $sql = "is_trash=0 and country_id='$country_id'";
        return $this->countItem($sql);
    }
    function countByCity($city_id) {
        $sql = "is_trash=0 and city_id='$city_id'";
        return $this->countItem($sql);
    }
    function getTitle($blog_id) {
        global $_LANG_ID;
        $one = $this->getOne($blog_id);
        return $one['title'];
    }

    function getSlug($blog_id) {
        global $_LANG_ID;
        $one = $this->getOne($blog_id);
        return $one['slug'];
    }
	function getRegDate($blog_id){
		global $_LANG_ID;
		$one = $this->getOne($blog_id);
		return date('F, d Y',$one['reg_date']);
	}
    function getBySlug($slug) {
        $all = $this->getAll("is_trash=0 and slug='$slug' limit 0,1");
        return $all[0][$this->pkey];
    }
    function getLink($blog_id) {
        global $extLang, $_LANG_ID;
        return $extLang . '/tin-tuc/' . $this->getSlug($blog_id) . '.html';
    }
    function getPermalink($blog_id) {
        global $_LANG_ID;
        $one = $this->getOne($blog_id);
        return $one['permalink'];
    }
	function getImage($pvalTable,$w,$h){
		global $clsISO;
		#
		$oneTable = $this->getOne($pvalTable, "image");
		if($oneTable['image']!=''){
			$image = $oneTable['image'];
			return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($image);
		}
		$noimage = URL_IMAGES.'/noimage.png';
		return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($noimage);
	}
    function getIntro($blog_id) {
        global $_LANG_ID;
        $one = $this->getOne($blog_id);
        return html_entity_decode($one['intro']);
    }
    function getContent($blog_id) {
        global $_LANG_ID;
        $one = $this->getOne($blog_id);
        return html_entity_decode($one['content']);
    }
    function makeFolder($conn_id, $dirname) {
        $lst = explode('/', $dirname);
        $str = '/' . $lst[0];
        for ($i = 1; $i < count($lst); $i++) {
            $str = $str . '/' . $lst[$i];
            if (!ftp_chdir($conn_id, $str)) {
                ftp_mkdir($conn_id, $str);
                error_reporting(0);
            }
        }
        return 1;
    }
    function getImageFromUrl($file, $news_id) {
        #
        $slug = $this->getSlug($news_id);
        $oneNews = $this->getOne($news_id);
        $reg_date = $oneNews['reg_date'];
        #
        $host = ftp_host_info;
        $usr = ftp_usr_info;
        $pwd = ftp_pwd_info;
        $abs_path = ftp_abs_path_info;
        /* Get File Extension */
        $path_parts = pathinfo($file);
        $ext = $path_parts['extension'];
        if ($ext != 'jpg' && $ext != 'png' && $ext != 'gif') {
            $ext = 'jpg';
        }
        /* Connect FTP */
        $conn_id = ftp_connect($host) or die("Cannot connect to host");
        ftp_login($conn_id, $usr, $pwd) or die("Cannot login");
        /* File Name */
        $day = date('d', $reg_date);
        $month = date('m', $reg_date);
        $year = date('Y', $reg_date);
        $dirname = 'content/' . $year . '/' . $month . '/' . $day;
        #
        $nMn = md5($file);
        $nMn = substr($nMn, 0, 4);
        #
        $name = '/' . $dirname . '/' . $slug . '-' . $nMn . '.' . $ext;
        $res = ftp_size($conn_id, $name);
        //print_r($abs_path.$name);die();
        if ($res != -1) {
            //return 'available';
            return $abs_path . $name;
        } else {
            $this->makeFolder($conn_id, $dirname);
            list($width_orig, $height_orig) = getimagesize($file);
            //print_r($width_orig.'/'.$height_orig);die();
            $temp_file = ftp_temp_file_info;
            if ($ext == "jpg") {
                $image_p = imagecreatetruecolor($width_orig, $height_orig);
                $image = imagecreatefromjpeg($file);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
                $temp_file .= $new_name . '.' . $ext;
                imagejpeg($image_p, $temp_file);
            } elseif ($ext == "png") {
                $image_p = imagecreatetruecolor($width_orig, $height_orig);
                $image = imagecreatefrompng($file);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
                $temp_file .= $new_name . '.' . $ext;
                imagepng($image_p, $temp_file);
            } elseif ($ext == "gif") {
                $image_p = imagecreatetruecolor($width_orig, $height_orig);
                $image = imagecreatefromgif($file);
                imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
                $temp_file .= $new_name . '.' . $ext;
                imagegif($image_p, $temp_file);
            } else {
                return '';
            }
            //===================================================================
            $upload = ftp_put($conn_id, $name, $temp_file, FTP_ASCII);
            //===================================================================			
            imagedestroy($image_p);
            unlink($temp_file);
        }
        ftp_close($conn_id);
        return $abs_path . $name;
    }
    function checkContain($haystack, $needle) {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return 0;
        } else {
            return 1;
        }
    }
    function updateImage($blog_id) {
        global $_LANG_ID;
        $one = $this->getOne($blog_id);
        if ($one['updateImage'] == 1) {
            return 0;
        }
        $content = $one['content'];
        preg_match_all("/src=&quot;(.*?)&quot;/si", $content, $matches);
        if ($matches[1][0] == '') {
            preg_match_all("/src=\"(.*?)\"/si", $content, $matches);
        }
        $slug = $this->getSlug($blog_id);
        #

        for ($i = 0; $i < count($matches[1]); $i++) {
            $tmp = $matches[1][$i];
            if ($tmp != '' && $this->checkContain($tmp, 'aprotravel') == 0) {
                list($width, $height) = getimagesize($tmp);
                if ($width * $height != 0) {
                    $clsUploadFile = new UploadFile();
                    $img = $clsUploadFile->uploadImageFromUrl($tmp, $slug);
                    $content = str_replace($tmp, $img, $content);
                }
            }
        }
        $this->updateOne($blog_id, 'content' . "='" . addslashes($content) . "',updateImage='1'");
    }
    function getTag($blog_id) {
        $clsTagBlog = new TagBlog();
        return $clsTagBlog->getTag($blog_id);
    }
    function getStripIntro($pvalTable) {
        $one = $this->getOne($pvalTable);
        if (!empty($one['intro']))
            return strip_tags(html_entity_decode($one['intro']));
        return strip_tags(html_entity_decode($one['content']));
    }
	function doDelete($blog_id){
		// Delete
		$clsTagBlog = new TagBlog();
		$clsTagBlog->deleteByCond("blog_id='$blog_id'");
		// Delete
		$this->deleteOne($blog_id);
		return 1;
	}
}
class BlogCategory extends dbBasic{
	function BlogCategory(){
		$this->pkey = "blogcat_id";
		$this->tbl = DB_PREFIX."blogcat";
	}
	function getSlash($level){
		return str_repeat("------", $level+1);
	}
	function getLink($cat_id){
		return '/tin-tuc/danh-muc/'.$this->getSlug($cat_id).'/';
	}
	function getTitle($cat_id){
		$one=$this->getOne($cat_id);
		return $one['title'];
	}
	function getSlug($cat_id){
		global $_LANG_ID;
		$one = $this->getOne($cat_id);
		return $one['slug'];
	}
    function getPermalink($cat_id){
		global $_LANG_ID;
		$one = $this->getOne($cat_id);
		return "/tin-tuc/danh-muc/".$one['permalink'];
	}
	function getBySlug($slug){
		$res=$this->getAll("is_trash=0 and slug='$slug' limit 0,1");
		return $res[0][$this->pkey];
	}
	function getIntro($cat_id){
		global $_LANG_ID;
		$one = $this->getOne($cat_id);
		return $one['intro'];
	}
	function getContent($cat_id){
		global $_LANG_ID;
		$one = $this->getOne($cat_id);
		return $one['content'];
	}
	function checkIsParent($cat_id,$parent_id_check){
        $one = $this->getOne($cat_id);
        $parent_id = $one['parent_id'];
        if($parent_id==$parent_id_check){
            return 1;
        }
        if($parent_id==0){return 0;}
        return $this->checkIsParent($parent_id,$parent_id_check);
    }
    function getListParent($cat_id){
        #
        $listChild = array();
        $allChild = $this->getAll();
        if($allChild[0][$this->pkey]!=''){
            for($i=0;$i<count($allChild);$i++){
                if($this->checkIsParent($cat_id,$allChild[$i][$this->pkey])){
                    $listChild[] = $allChild[$i][$this->pkey];
                }
            }
        }
        #
        $cond = "|0|".$cat_id."|";
        if(is_array($listChild)&&count($listChild)>0){           
            for($i=0;$i<count($listChild);$i++){
                $cond .= $listChild[$i]."|";
            }   
        }
        #
        return $cond;
    }
	function makeSelectboxOption($cat_id){
		global $core;
		$res = $this->getAll("is_trash=0 order by order_no asc");
		$html='<option value="">'.$core->get_Lang('Select').'</option>';
		if(!empty($res)){
			foreach($res as $item){
				$sl = ($cat_id==$item[$this->pkey])?'selected="selected"':'';
				$html.='<option value="'.$item[$this->pkey].'" '.$sl.'>'.$this->getTitle($item[$this->pkey]).'</option>';
			}
		}
		return $html;
	}
    function countItemInCat($blogcat_id){
		$clsBlog = new Blog();
		return $clsBlog->countItem("is_trash=0 and cat_id = '$blogcat_id'");
	}
	function doDelete($blogcat_id){
		// Delete
		$clsBlog = new Blog();
		$lstItem = $clsBlog->getAll("cat_id='$blogcat_id'");
		if(is_array($lstItem) && count($lstItem)>0){
			for($i=0; $i<count($lstItem); $i++){
				$clsBlog->doDelete($lstItem[$i][$clsBlog->pkey]);
			}
		}
		// Delete
		$this->deleteOne($blogcat_id);
		return 1;
	}
}


class TagBlog extends dbBasic{
	function TagBlog(){
		$this->pkey = "tag_blog_id";
		$this->tbl = DB_PREFIX."tag_blog";
	}
	function getTag($blog_id){
			return $lstItem = $this->getAll("blog_id='$blog_id'");
		}
				
		function geAllTagBlog(){
			global $dbconn;
			global $_LANG_ID;
			return $arrListCat =  $dbconn->GetAll("SELECT DISTINCT tag_id FROM ".$this->tbl." order by tag_blog_id asc");
	
		}
}
?>