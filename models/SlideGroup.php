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
class SlideGroup extends dbBasic{
	function __construct(){
		$this->pkey = "slide_group_id";
		$this->tbl = DB_PREFIX."slide_group";
	}
	function getTitle($pval) {
		$one = $this->getOne($pval);
		return $one['title'];
	}
	function getSize($pval){
		$one = $this->getOne($pval);
		return $one['_width'].'x'.$one['_height'];
	}
}
?>